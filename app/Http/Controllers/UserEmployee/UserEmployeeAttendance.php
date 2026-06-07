<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Employees;

class UserEmployeeAttendance extends Controller
{
    public function showEmployeeAttendanceDetails()
    {   
        $userId = Auth::user()->id;
        // Dates
		$startOfMonth = Carbon::now()->startOfMonth()->toDateString();
		$today = Carbon::now()->toDateString();

        $employee = DB::table('users')
			->select('users.*', 'employees.*')
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->where('users.id', '=', $userId)
			->first();

		// Attendance this month up to today
		$attendance = DB::table('attendance')
			->where('userId', $userId)
			->whereBetween('present_date', [$startOfMonth, $today])
			->get();

		$presentDates = $attendance->pluck('present_date')->toArray();

        // Late/On-Time Count (unchanged)
        $lateCount = 0;
		$onTimeCount = 0;
		foreach ($attendance as $record) {
			$dayName = strtolower(Carbon::parse($record->present_date)->format('l'));
			if (isset($weeklySchedule[$dayName])) {
				$openingTime = $weeklySchedule[$dayName]->opening_time;
				// Compare in_time with opening_time
				if ($record->in_time > $openingTime) {
					$lateCount++;
				} else {
					$onTimeCount++;
				}
			}
		}

        // Holidays in this month up to today
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereBetween('holidayDate', [$startOfMonth, $today])
			->pluck('holidayDate')
			->toArray();

        
            // Approved leaves affecting this month up to today
		$leavePeriods = DB::table('leaves')
			->where('emp_id', $userId)
			->where('status', 'approved')
			->where(function ($q) use ($startOfMonth, $today) {
				$q->whereBetween('start_date', [$startOfMonth, $today])
					->orWhereBetween('end_date', [$startOfMonth, $today])
					->orWhere(function ($q2) use ($startOfMonth, $today) {
						$q2->where('start_date', '<=', $startOfMonth)
							->where('end_date', '>=', $today);
					});
			})
			->get();
        
        $leaveDates = [];
		foreach ($leavePeriods as $leave) {
			$leaveStart = Carbon::parse($leave->start_date)->lessThan(Carbon::parse($startOfMonth)) ? Carbon::parse($startOfMonth) : Carbon::parse($leave->start_date);
			$leaveEnd = Carbon::parse($leave->end_date)->greaterThan(Carbon::parse($today)) ? Carbon::parse($today) : Carbon::parse($leave->end_date);
			foreach (CarbonPeriod::create($leaveStart, $leaveEnd) as $lDay) {
				$leaveDates[] = $lDay->toDateString();
			}
		}
		$leaveDates = array_unique($leaveDates);

        // Calculate working days and absences
		$period = CarbonPeriod::create($startOfMonth, $today);
		$totalWorkingDays = 0;
		$absentDays = [];

		foreach ($period as $date) {
			$dateString = $date->toDateString();
			$dayName = strtolower($date->format('l'));
			// Only count if open in weekly schedule and not a holiday
			if (isset($weeklySchedule[$dayName]) && !in_array($dateString, $holidays)) {
				$totalWorkingDays++;
				if (
					!in_array($dateString, $presentDates) &&
					!in_array($dateString, $leaveDates)
				) {
					$absentDays[] = $dateString; // Absent
				}
			}
		}
		$totalAbsentDays = count($absentDays);
        
        // For reporting: total leave days counted in current month up to today
		$totalLeaveDaysThisMonth = 0;
		foreach ($leaveDates as $d) {
			$totalLeaveDaysThisMonth++;
		}

        return view('Employee.UserEmployee.EmployeeAttendanceDetails')->with([
			'presentThisMonth'   => count($presentDates),
            'lateCountThisMonth' => $lateCount,
            'totalAbsentDays'    => $totalAbsentDays,
            'totalLeaveDaysThisMonth' => $totalLeaveDaysThisMonth,
            'employee'           => $employee,
			
		]);
    }

    // User Employee Monthly Attendance with Enhanced Details

    public function getMonthlyAttendance(Request $request)
	{
		$year = $request->input('year', Carbon::now()->year);
		$month = $request->input('month', Carbon::now()->month);
		$userId = $request->input('user_id'); // Fetch user_id from request

        $employee = Employees::where('empId', $userId)->first();

    
		$authUserId = $employee->added_by; // added_by field from employees table

		// Fetch attendance data with enhanced details
		$attendance = DB::select("
			SELECT id, present_date, in_time, out_time, present_status, reason
			FROM attendance 
			WHERE userId = ? AND YEAR(present_date) = ? AND MONTH(present_date) = ?
		", [$userId, $year, $month]);

		// Fetch weekly schedule to determine closed days and opening times
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $authUserId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Process attendance data to determine actual status based on weekly schedule
		$processedAttendance = [];
		foreach ($attendance as $record) {
			$dayName = strtolower(Carbon::parse($record->present_date)->format('l'));
			$attendanceData = [
				'id' => $record->id,
				'present_date' => $record->present_date,
				'in_time' => $record->in_time,
				'out_time' => $record->out_time,
				'present_status' => $record->present_status,
				'reason' => $record->reason,
				'day_name' => $dayName,
				'is_late' => false,
				'opening_time' => null
			];

			// Check if there's a schedule for this day and determine if late
			$scheduleForDay = $weeklySchedule->get($dayName);

			if ($scheduleForDay && $scheduleForDay->status === 'open' && $record->in_time) {
				$attendanceData['opening_time'] = $scheduleForDay->opening_time;

				// Compare in_time with opening_time to determine if late
				$inTime = Carbon::parse($record->in_time);
				$openingTime = Carbon::parse($scheduleForDay->opening_time);

				if ($inTime->greaterThan($openingTime)) {
					$attendanceData['is_late'] = true;
					// Override status if originally marked as present but actually late
					if ($record->present_status === 'present' || $record->present_status === 'working') {
						$attendanceData['actual_status'] = 'late';
					}
				} else {
					$attendanceData['actual_status'] = 'present';
				}
			}

			$processedAttendance[] = $attendanceData;
		}

		// Fetch holidays for the month
		$holidays = DB::table('holidays')
			->where('added_by', $authUserId)
			->whereYear('holidayDate', $year)
			->whereMonth('holidayDate', $month)
			->pluck('holidayDate')
			->toArray();

		// Fetch approved leaves for the employee that overlap with the selected month
		$leaves = DB::table('leaves')
			->where('emp_id', $userId)
			->where('status', 'approved')
			->where(function ($query) use ($year, $month) {
				$query->whereYear('start_date', $year)
					->whereMonth('start_date', $month)
					->orWhereYear('end_date', $year)
					->whereMonth('end_date', $month);
			})
			->select('start_date', 'end_date', 'leave_type', 'reason')
			->get();

		// Generate leave dates array with details
		$leaveDates = [];
		foreach ($leaves as $leave) {
			$startDate = Carbon::parse($leave->start_date);
			$endDate = Carbon::parse($leave->end_date);

			// Ensure we only include dates within the selected month
			$monthStart = Carbon::create($year, $month, 1)->startOfMonth();
			$monthEnd = Carbon::create($year, $month, 1)->endOfMonth();

			$leaveStart = $startDate->lessThan($monthStart) ? $monthStart : $startDate;
			$leaveEnd = $endDate->greaterThan($monthEnd) ? $monthEnd : $endDate;

			$period = CarbonPeriod::create($leaveStart, $leaveEnd);
			foreach ($period as $date) {
				$leaveDates[$date->toDateString()] = [
					'leave_type' => $leave->leave_type,
					'reason' => $leave->reason
				];
			}
		}

		return response()->json([
			'attendance' => $processedAttendance,
			'weeklySchedule' => $weeklySchedule,
			'holidays' => $holidays,
			'leaves' => $leaveDates
		]);
	}

    // Daily Activity Details for User Employee
    public function getDailyActivity(Request $request)
	{
		$userId = $request->input('user_id');
		$date = $request->input('date');

        $employee = Employees::where('empId', $userId)->first();
		$authUserId = $employee->added_by;
		

		// Get attendance record for the specific date
		$attendance = DB::table('attendance')
			->where('userId', $userId)
			->whereDate('present_date', $date)
			->first();

		// Get weekly schedule for the day
		$dayName = strtolower(Carbon::parse($date)->format('l'));
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $authUserId)
			->where('day', $dayName)
			->first();

		// Check if it's a holiday
		$holiday = DB::table('holidays')
			->where('added_by', $authUserId)
			->whereDate('holidayDate', $date)
			->first();

		// Check if it's a leave day
		$leave = DB::table('leaves')
			->where('emp_id', $userId)
			->where('status', 'approved')
			->whereDate('start_date', '<=', $date)
			->whereDate('end_date', '>=', $date)
			->first();

		// Calculate working hours if both in_time and out_time exist
		$workingHours = null;
		$isLate = false;
		$lateBy = null;

		if ($attendance && $attendance->in_time && $attendance->out_time) {
			$baseDate = Carbon::parse($date)->format('Y-m-d');
			$inTime = Carbon::parse($baseDate . ' ' . $attendance->in_time);
			$outTime = Carbon::parse($baseDate . ' ' . $attendance->out_time);

			$diffInMinutes = $inTime->diffInMinutes($outTime);
			$hours = intval($diffInMinutes / 60);
			$minutes = $diffInMinutes % 60;
			$workingHours = sprintf('%02d:%02d', $hours, $minutes);
		}

		// Check if late
		if ($attendance && $attendance->in_time && $weeklySchedule && $weeklySchedule->status === 'open') {
			// Parse times with the same date to ensure proper comparison
			$baseDate = Carbon::parse($date)->format('Y-m-d');
			$inTime = Carbon::parse($baseDate . ' ' . $attendance->in_time);
			$openingTime = Carbon::parse($baseDate . ' ' . $weeklySchedule->opening_time);

			if ($inTime->greaterThan($openingTime)) {
				$isLate = true;
				$diffInMinutes = $inTime->diffInMinutes($openingTime);
				$hours = intval($diffInMinutes / 60);
				$minutes = $diffInMinutes % 60;
				$lateBy = sprintf('%02d:%02d', $hours, $minutes);
			}
		}

		// Determine status
		$status = 'absent';
		$statusColor = 'danger';
		$statusIcon = 'ph-x-circle';

		if ($holiday) {
			$status = 'holiday';
			$statusColor = 'secondary';
			$statusIcon = 'ph-calendar-x';
		} elseif ($leave) {
			$status = 'leave';
			$statusColor = 'info';
			$statusIcon = 'ph-airplane-takeoff';
		} elseif ($attendance) {
			if ($isLate) {
				$status = 'late';
				$statusColor = 'warning';
				$statusIcon = 'ph-clock';
			} else {
				$status = 'present';
				$statusColor = 'success';
				$statusIcon = 'ph-check';
			}
		} elseif ($weeklySchedule && $weeklySchedule->status === 'closed') {
			$status = 'weekend';
			$statusColor = 'secondary';
			$statusIcon = 'ph-calendar-x';
		}

		return response()->json([
			'date' => Carbon::parse($date)->format('d M Y'),
			'dayName' => ucfirst($dayName),
			'status' => $status,
			'statusColor' => $statusColor,
			'statusIcon' => $statusIcon,
			'inTime' => $attendance ? $attendance->in_time : null,
			'outTime' => $attendance ? $attendance->out_time : null,
			'workingHours' => $workingHours,
			'isLate' => $isLate,
			'lateBy' => $lateBy,
			'reason' => $attendance ? $attendance->reason : null,
			'leaveType' => $leave ? $leave->leave_type : null,
			'leaveReason' => $leave ? $leave->reason : null,
			'holidayName' => $holiday ? $holiday->holidayName : null,
			'openingTime' => $weeklySchedule ? $weeklySchedule->opening_time : null,
			'closingTime' => $weeklySchedule ? $weeklySchedule->closing_time : null,
		]);
	}
}
