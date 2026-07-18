<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Depertments;
use App\Models\Designations;
use App\Models\Employees;
use App\Models\Location;
use App\Models\WorkFromHome;
use App\Models\Company_profiles;
use App\Models\EmployeeRating;
use App\Models\CA_profiles;
use App\Models\Admin_profiles;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DatePeriod;
use DateInterval;
use PDF;
use Excel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

// use App\Company_banks;
use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\AuditLogger;
use App\Services\JournalService;

class EmployeeManagemnet extends Controller
{
	public function __construct(JournalService $journalService = null)
    {
        $this->journalService = $journalService;
    }
	
	public function EmployeeList()
	{
		$userId = Auth::user()->id;
		$uType  = Auth::user()->u_type;
		$ownerId = currentOwnerId();
		checkCoreAccess('Payroll Management');
		$query = DB::table('users')
			->select(
				'users.id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name',
				DB::raw("
					CASE
						WHEN employees.propId IS NOT NULL AND employees.propId != ''
						THEN pp.comp_name
						ELSE cp.comp_name
					END as comp_name
				")
			)
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->leftJoin('company_profiles as cp', 'users.user_add_by', '=', 'cp.userId')
			->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'employees.propId');

		if ($uType == 2 || $uType == 5) {
			// User
			$query->where('users.user_add_by', $ownerId);
		} elseif ($uType == 3 || $uType == 6) {
			// Admin
			$query->where('users.admin_add_by', $ownerId);
		} elseif ($uType == 1 || $uType == 4) {
			// Employees
			$query->where('users.ca_add_by', $ownerId);
		} else {
			$query->where('users.id', $userId);
		}

		$employees = $query
			->orderBy('users.created_at', 'desc')
			->get();
			// ->paginate(10);

		return view('User.Employeelist', [
			'employees' => $employees,
		]);
	}

	public function resignEmployee()
	{
		$userId = Auth::user()->id;
		$uType  = Auth::user()->u_type;
		$ownerId = currentOwnerId();

		checkCoreAccess('Payroll Management');

		$query = DB::table('resign_employee')
			->select(
				'resign_employee.id',
				'resign_employee.name',
				'resign_employee.phone',
				'resign_employee.status',
				'resign_employee.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name',
				DB::raw("
					CASE
						WHEN employees.propId IS NOT NULL AND employees.propId != ''
						THEN pp.comp_name
						ELSE cp.comp_name
					END as comp_name
				")
			)
			->leftJoin('employees', 'resign_employee.id', '=', 'employees.empId')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->leftJoin('company_profiles as cp', 'resign_employee.user_add_by', '=', 'cp.userId')
			->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'resign_employee.user_add_by');

		if ($uType == 2 || $uType == 5) {
			$query->where('resign_employee.user_add_by', $ownerId);
		} elseif ($uType == 3 || $uType == 6) {
			$query->where('resign_employee.admin_add_by', $ownerId);
		} elseif ($uType == 1 || $uType == 4) {
			$query->where('resign_employee.ca_add_by', $ownerId);
		} else {
			$query->where('resign_employee.id', $userId);
		}

		$employees = $query
			->orderBy('resign_employee.created_at', 'desc')
			->paginate(10);

		return view('User.resignEmployee', [
			'employees' => $employees,
		]);
	}

	public function AddEmployee()
    {
        $userId = currentOwnerId();
        $proprietorships = DB::table('proprietorship_profiles')
                        ->select('id','comp_name','basic_percentage')
                        ->where('userId',$userId)
                        ->get();
        $locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();
        $states = State::where('country_id', '=', 101)->get();

        $menu_features = DB::table('menu_features')
			->orderBy('parent_id')
			->orderBy('id')
			->get();

		$mainMenus = $menu_features->where('type', 'MAIN');
       
        $parentCompany = DB::table('company_profiles')
                        ->select('basic_percentage')
                        ->where('userId',$userId)
                        ->first();
        $basic_percentage = $parentCompany->basic_percentage ?? 50;
 
 
        return view('User.AddEmployee')->with([
            'proprietorships' => $proprietorships,
            'states' => $states,
            'locations' => $locations,
            'menu_features' => $menu_features,
			'mainMenus'       => $mainMenus,
            'basic_percentage' => $basic_percentage
 
        ]);
    }

	public function EmployeeDetails(Request $request)
	{
		$id = base64_decode($request->id);
		$userId = currentOwnerId();

		// Fetch weekly schedules for the authenticated user
		$weeklySchedules = DB::table('weekly_schedules')
			->select('id', 'day', 'opening_time', 'closing_time', 'status', 'working_hours', 'added_by', 'u_type')
			->where('added_by', $userId)
			->get();

		// Fetch holidays for the authenticated user
		$holidays = DB::table('holidays')
			->select('holidayDate', 'holidayName')
			->where('added_by', $userId)
			->get();

		return view('User.employee-details')->with([
			'id' => $id,
			'weeklySchedules' => $weeklySchedules,
			'holidays' => $holidays
		]);
	}

	public function EmployeeLeaves(Request $request)
	{
		$id = base64_decode($request->id);
		$userId = currentOwnerId();
		$currentYear = date('Y');

		// Get employee details
		$employee = DB::table('employees')
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->select(
				'employees.*',
				'users.name',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->where('employees.empId', $id)
			->where('employees.added_by', $userId)
			->first();

		if (!$employee) {
			return redirect()->back()->with('error', 'Employee not found');
		}

		// Get all leave requests for this employee
		$leaves = DB::table('leaves')
			->where('employee_id', $employee->employee_id)
			->orderBy('created_at', 'desc')
			->get();

		// Get leave summary for current year
		$leaveSummary = DB::table('leaves')
			->select(
				'leave_type',
				DB::raw('COUNT(*) as total_requests'),
				DB::raw('SUM(CASE WHEN status = "approved" THEN total_days ELSE 0 END) as approved_days'),
				DB::raw('SUM(CASE WHEN status = "pending" THEN total_days ELSE 0 END) as pending_days'),
				DB::raw('SUM(CASE WHEN status = "rejected" THEN total_days ELSE 0 END) as rejected_days')
			)
			->where('employee_id', $employee->employee_id)
			->whereYear('start_date', $currentYear)
			->groupBy('leave_type')
			->get();

		// Get years for filter dropdown
		$years = DB::table('leaves')
			->select(DB::raw('YEAR(start_date) as year'))
			->where('employee_id', $employee->employee_id)
			->distinct()
			->orderBy('year', 'desc')
			->pluck('year');

		if ($years->isEmpty()) {
			$years = collect([$currentYear]);
		}



		return view('User.employee-leaves')->with([
			'employee' => $employee,
			'leaves' => $leaves,
			'leaveSummary' => $leaveSummary,
			'years' => $years,
			'currentYear' => $currentYear,
			'encodedId' => $request->id
		]);
	}

	public function AttendanceList()
	{
		$userId = currentOwnerId();
		$currentMonth = date('Y-m');
		$currentYear = date('Y');

		// Date range: 1st day of current month to today
		$startOfMonth = date('Y-m-01');
		$today = date('Y-m-d');

		// Get basic employee data
		$employees = DB::table('employees')
			->select(
				'employees.empId as id',
				'employees.employee_id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->where('employees.added_by', '=', $userId)
			->where('users.status', '=', 1)
			->orderBy('employees.created_at', 'desc')
			->get();

		// Get weekly schedule
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $userId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Get holidays for current month
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereYear('holidayDate', $currentYear)
			->whereMonth('holidayDate', date('m'))
			->pluck('holidayDate')
			->toArray();

		// Process each employee
		foreach ($employees as $employee) {
			// Get attendance records for this employee
			$attendanceRecords = DB::table('attendance')
				->where('userId', $employee->id)
				->whereBetween('present_date', [$startOfMonth, $today])
				->get()
				->keyBy('present_date');

			// Get approved leaves for this employee
			$leaves = DB::table('leaves')
				->where('emp_id', $employee->id)
				->where('status', 'approved')
				->where(function ($query) use ($startOfMonth, $today) {
					$query->whereBetween('start_date', [$startOfMonth, $today])
						->orWhereBetween('end_date', [$startOfMonth, $today])
						->orWhere(function ($q) use ($startOfMonth, $today) {
							$q->where('start_date', '<=', $startOfMonth)
								->where('end_date', '>=', $today);
						});
				})
				->get();

			// Create leave dates array
			$leaveDates = [];
			foreach ($leaves as $leave) {
				$startDate = Carbon::parse($leave->start_date);
				$endDate = Carbon::parse($leave->end_date);
				$period = CarbonPeriod::create($startDate, $endDate);

				foreach ($period as $date) {
					if ($date->format('Y-m-d') >= $startOfMonth && $date->format('Y-m-d') <= $today) {
						$leaveDates[] = $date->format('Y-m-d');
					}
				}
			}

			// Calculate attendance metrics
			$totalPresent = 0;
			$totalOnTime = 0;
			$totalLate = 0;
			$totalAbsent = 0;
			$totalLeaves = count($leaveDates);
			$totalWorkingDays = 0;

			// Loop through each day from start of month to today
			$period = CarbonPeriod::create($startOfMonth, $today);

			foreach ($period as $date) {
				$dateString = $date->format('Y-m-d');
				$dayName = strtolower($date->format('l'));

				// Check if it's a working day
				$daySchedule = $weeklySchedule[$dayName] ?? null;
				$isWorkingDay = $daySchedule && $daySchedule->status === 'open';

				// Skip non-working days
				if (!$isWorkingDay) {
					continue;
				}

				$totalWorkingDays++;

				// Check if it's a holiday
				if (in_array($dateString, $holidays)) {
					continue; // Skip holidays
				}

				// Check if it's a leave day
				if (in_array($dateString, $leaveDates)) {
					continue; // Skip leave days
				}

				// Check attendance record
				$attendanceRecord = $attendanceRecords[$dateString] ?? null;

				if ($attendanceRecord) {
					if (in_array($attendanceRecord->present_status, ['present', 'working'])) {
						$totalPresent++;

						// Check if on time or late
						if ($attendanceRecord->in_time && $daySchedule) {
							$inTime = Carbon::parse($attendanceRecord->in_time);
							$openingTime = Carbon::parse($daySchedule->opening_time);

							if ($inTime->greaterThan($openingTime)) {
								$totalLate++;
							} else {
								$totalOnTime++;
							}
						} else {
							$totalOnTime++; // Default to on time if no time data
						}
					} elseif ($attendanceRecord->present_status === 'absent') {
						$totalAbsent++;
					}
				} else {
					// No attendance record = absent
					$totalAbsent++;
				}
			}

			// Add calculated metrics to employee object
			$employee->total_present = $totalPresent;
			$employee->total_ontime = $totalOnTime;
			$employee->total_late = $totalLate;
			$employee->total_leaves = $totalLeaves;
			$employee->total_absent = $totalAbsent;
			$employee->total_working_days = $totalWorkingDays;
		}

		// echo '<pre>';
		// print_r($employees);
		// die();


		return view('User.attendance-list')->with([
			'employees' => $employees,
			'startDate' => $startOfMonth,
			'endDate' => $today,
		]);
	}

	public function AttendanceListFilter(Request $request)
	{
		$userId = Auth::user()->id;

		// Get date range from request
		$startDate = $request->input('from_date', date('Y-m-01'));
		$endDate = $request->input('to_date', date('Y-m-d'));

		// Get basic employee data
		$employees = DB::table('employees')
			->select(
				'employees.empId as id',
				'employees.employee_id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->where('employees.added_by', '=', $userId)
			->where('users.status', '=', 1)
			->orderBy('employees.created_at', 'desc')
			->get();

		// Get weekly schedule
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $userId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Get holidays for the date range
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereBetween('holidayDate', [$startDate, $endDate])
			->pluck('holidayDate')
			->toArray();

		// Process each employee
		foreach ($employees as $employee) {
			// Get attendance records for this employee in the date range
			$attendanceRecords = DB::table('attendance')
				->where('userId', $employee->id)
				->whereBetween('present_date', [$startDate, $endDate])
				->get()
				->keyBy('present_date');

			// Get approved leaves for this employee in the date range
			$leaves = DB::table('leaves')
				->where('emp_id', $employee->id)
				->where('status', 'approved')
				->where(function ($query) use ($startDate, $endDate) {
					$query->whereBetween('start_date', [$startDate, $endDate])
						->orWhereBetween('end_date', [$startDate, $endDate])
						->orWhere(function ($q) use ($startDate, $endDate) {
							$q->where('start_date', '<=', $startDate)
								->where('end_date', '>=', $endDate);
						});
				})
				->get();

			// Create leave dates array
			$leaveDates = [];
			foreach ($leaves as $leave) {
				$leaveStart = Carbon::parse($leave->start_date);
				$leaveEnd = Carbon::parse($leave->end_date);
				$period = CarbonPeriod::create($leaveStart, $leaveEnd);

				foreach ($period as $date) {
					if ($date->format('Y-m-d') >= $startDate && $date->format('Y-m-d') <= $endDate) {
						$leaveDates[] = $date->format('Y-m-d');
					}
				}
			}

			// Calculate attendance metrics
			$totalPresent = 0;
			$totalOnTime = 0;
			$totalLate = 0;
			$totalAbsent = 0;
			$totalLeaves = count($leaveDates);
			$totalWorkingDays = 0;

			// Loop through each day in the date range
			$period = CarbonPeriod::create($startDate, $endDate);

			foreach ($period as $date) {
				$dateString = $date->format('Y-m-d');
				$dayName = strtolower($date->format('l'));

				// Check if it's a working day
				$daySchedule = $weeklySchedule[$dayName] ?? null;
				$isWorkingDay = $daySchedule && $daySchedule->status === 'open';

				// Skip non-working days
				if (!$isWorkingDay) {
					continue;
				}

				$totalWorkingDays++;

				// Check if it's a holiday
				if (in_array($dateString, $holidays)) {
					continue; // Skip holidays
				}

				// Check if it's a leave day
				if (in_array($dateString, $leaveDates)) {
					continue; // Skip leave days
				}

				// Check attendance record
				$attendanceRecord = $attendanceRecords[$dateString] ?? null;

				if ($attendanceRecord) {
					if (in_array($attendanceRecord->present_status, ['present', 'working'])) {
						$totalPresent++;

						// Check if on time or late
						if ($attendanceRecord->in_time && $daySchedule) {
							$inTime = Carbon::parse($attendanceRecord->in_time);
							$openingTime = Carbon::parse($daySchedule->opening_time);

							if ($inTime->greaterThan($openingTime)) {
								$totalLate++;
							} else {
								$totalOnTime++;
							}
						} else {
							$totalOnTime++; // Default to on time if no time data
						}
					} elseif ($attendanceRecord->present_status === 'absent') {
						$totalAbsent++;
					}
				} else {
					// No attendance record = absent
					$totalAbsent++;
				}
			}

			// Add calculated metrics to employee object
			$employee->total_present = $totalPresent;
			$employee->total_ontime = $totalOnTime;
			$employee->total_late = $totalLate;
			$employee->total_leaves = $totalLeaves;
			$employee->total_absent = $totalAbsent;
			$employee->total_working_days = $totalWorkingDays;
		}

		// echo '<pre>';
		// print_r($employees);
		// die();


		return view('User.attendance-list')->with([
			'employees' => $employees,
			'startDate' => $startDate,
			'endDate' => $today,
		]);
	}

	public function AttendanceListPDF(Request $request)
	{
		$userId = Auth::user()->id;

		// Get date range from request
		$startDate = $request->input('from_date', date('Y-m-01'));
		$endDate = $request->input('to_date', date('Y-m-d'));

		// Get basic employee data
		$employees = DB::table('employees')
			->select(
				'employees.empId as id',
				'employees.employee_id',
				'users.name',
				'users.phone',
				'users.status',
				'users.u_type',
				'employees.dept_id',
				'employees.desig_id',
				'employees.email_id',
				'employees.profile_img',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->where('employees.added_by', '=', $userId)
			->where('users.status', '=', 1)
			->orderBy('employees.created_at', 'desc')
			->get();

		// Get weekly schedule
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $userId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Get holidays for the date range
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereBetween('holidayDate', [$startDate, $endDate])
			->pluck('holidayDate')
			->toArray();

		// Process each employee
		foreach ($employees as $employee) {
			// Get attendance records for this employee in the date range
			$attendanceRecords = DB::table('attendance')
				->where('userId', $employee->id)
				->whereBetween('present_date', [$startDate, $endDate])
				->get()
				->keyBy('present_date');

			// Get approved leaves for this employee in the date range
			$leaves = DB::table('leaves')
				->where('emp_id', $employee->id)
				->where('status', 'approved')
				->where(function ($query) use ($startDate, $endDate) {
					$query->whereBetween('start_date', [$startDate, $endDate])
						->orWhereBetween('end_date', [$startDate, $endDate])
						->orWhere(function ($q) use ($startDate, $endDate) {
							$q->where('start_date', '<=', $startDate)
								->where('end_date', '>=', $endDate);
						});
				})
				->get();

			// Create leave dates array
			$leaveDates = [];
			foreach ($leaves as $leave) {
				$leaveStart = Carbon::parse($leave->start_date);
				$leaveEnd = Carbon::parse($leave->end_date);
				$period = CarbonPeriod::create($leaveStart, $leaveEnd);

				foreach ($period as $date) {
					if ($date->format('Y-m-d') >= $startDate && $date->format('Y-m-d') <= $endDate) {
						$leaveDates[] = $date->format('Y-m-d');
					}
				}
			}

			// Calculate attendance metrics
			$totalPresent = 0;
			$totalOnTime = 0;
			$totalLate = 0;
			$totalAbsent = 0;
			$totalLeaves = count($leaveDates);
			$totalWorkingDays = 0;

			// Loop through each day in the date range
			$period = CarbonPeriod::create($startDate, $endDate);

			foreach ($period as $date) {
				$dateString = $date->format('Y-m-d');
				$dayName = strtolower($date->format('l'));

				// Check if it's a working day
				$daySchedule = $weeklySchedule[$dayName] ?? null;
				$isWorkingDay = $daySchedule && $daySchedule->status === 'open';

				// Skip non-working days
				if (!$isWorkingDay) {
					continue;
				}

				$totalWorkingDays++;

				// Check if it's a holiday
				if (in_array($dateString, $holidays)) {
					continue; // Skip holidays
				}

				// Check if it's a leave day
				if (in_array($dateString, $leaveDates)) {
					continue; // Skip leave days
				}

				// Check attendance record
				$attendanceRecord = $attendanceRecords[$dateString] ?? null;

				if ($attendanceRecord) {
					if (in_array($attendanceRecord->present_status, ['present', 'working'])) {
						$totalPresent++;

						// Check if on time or late
						if ($attendanceRecord->in_time && $daySchedule) {
							$inTime = Carbon::parse($attendanceRecord->in_time);
							$openingTime = Carbon::parse($daySchedule->opening_time);

							if ($inTime->greaterThan($openingTime)) {
								$totalLate++;
							} else {
								$totalOnTime++;
							}
						} else {
							$totalOnTime++; // Default to on time if no time data
						}
					} elseif ($attendanceRecord->present_status === 'absent') {
						$totalAbsent++;
					}
				} else {
					// No attendance record = absent
					$totalAbsent++;
				}
			}

			// Add calculated metrics to employee object
			$employee->total_present = $totalPresent;
			$employee->total_ontime = $totalOnTime;
			$employee->total_late = $totalLate;
			$employee->total_leaves = $totalLeaves;
			$employee->total_absent = $totalAbsent;
			$employee->total_working_days = $totalWorkingDays;
		}

		// Calculate overall summary
		$totalDays = 0;
		$totalHolidays = count($holidays);
		$totalOfficeOff = 0;
		$totalWorkingDays = 0;

		// Calculate total days in the date range
		$period = CarbonPeriod::create($startDate, $endDate);
		foreach ($period as $date) {
			$totalDays++;
			$dayName = strtolower($date->format('l'));
			$daySchedule = $weeklySchedule[$dayName] ?? null;
			$isWorkingDay = $daySchedule && $daySchedule->status === 'open';

			if ($isWorkingDay) {
				$totalWorkingDays++;
			} else {
				$totalOfficeOff++;
			}
		}

		$summary = [
			'total_days' => $totalDays,
			'total_holidays' => $totalHolidays,
			'total_office_off' => $totalOfficeOff,
			'total_working_days' => $totalWorkingDays
		];

		// Format dates for display
		$formattedStartDate = Carbon::parse($startDate)->format('F d, Y');
		$formattedEndDate = Carbon::parse($endDate)->format('F d, Y');

		// Generate PDF
		$pdf = PDF::loadView('User.attendance-list-pdf', [
			'employees' => $employees,
			'startDate' => $formattedStartDate,
			'endDate' => $formattedEndDate,
			'summary' => $summary
		]);

		// Set paper size and orientation
		$pdf->setPaper('A4', 'landscape');

		// Generate filename
		$filename = 'attendance-list-' . $startDate . '-to-' . $endDate . '.pdf';

		return $pdf->download($filename);
	}

	public function GeneratePayslip()
	{
		$userId = currentOwnerId();
		$userType = Auth::user()->u_type;
		$employees = "";
		// Fetch employees
		if($userType == 1 || $userType == 4){
			$employees = DB::table('users')
				->where('u_type', 4)
				->where('ca_add_by', $userId)
				->where('status', 1) // Optional: Fetch only active employees
				->orderBy('name', 'asc')
				->get();
		}else if($userType == 2 || $userType == 5){
			$employees = DB::table('users')
				->where('u_type', 5)
				->where('user_add_by', $userId)
				->where('status', 1) // Optional: Fetch only active employees
				->orderBy('name', 'asc')
				->get();
		}else if($userType == 3 || $userType == 6){
			$employees = DB::table('users')
				->where('u_type', 6)
				->where('admin_add_by', $userId)
				->where('status', 1) // Optional: Fetch only active employees
				->orderBy('name', 'asc')
				->get();
		}

		return view('User.generate-payslip', compact('employees'));
	}

	public function getDepartments()
	{
		// Get the currently authenticated user's ID
		$userId = Auth::user()->id;


		$departments = DB::table('depertments')
			->where(function ($query) use ($userId) {
				$query->where('created_user_id', $userId)
					->orWhere('created_user_id', 'admin');
			})
			->select('id', 'dept_name')
			->get();

		// Return the departments as JSON
		return response()->json($departments);
	}

	public function getDesignationsByDept($dept_id)
	{
		// Get the current user's ID
		$userId = Auth::user()->id;

		// Fetch designations where the dept_id matches and created_user_id is either 'admin' or the current user's ID
		$designations = DB::table('designations')
			->where('dept_id', $dept_id)
			->where(function ($query) use ($userId) {
				$query->where('created_user_id', $userId)
					->orWhere('created_user_id', 'admin');
			})
			->select('id', 'designation_name')
			->get();

		return response()->json($designations);
	}

	public function DeptStore(Request $request)
	{
		$userId = Auth::user()->id;
		$request->validate([
			'dept_name' => 'required|unique:depertments,dept_name|max:255'
		]);

		DB::table('depertments')->insert([
			'dept_name' => $request->dept_name,
			'created_user_id' => $userId
		]);

		return response()->json(['success' => true, 'message' => 'Department added successfully!']);
	}

	public function DesignationStore(Request $request)
	{
		$userId = Auth::user()->id;
		$request->validate([
			'dept_id' => 'required',
			'designation_name' => 'required|max:255',
		]);

		DB::table('designations')->insert([
			'dept_id' => $request->dept_id,
			'designation_name' => $request->designation_name,
			"created_user_id"  => $userId

		]);

		return response()->json(['success' => true, 'message' => 'Designation added successfully!']);
	}

	protected function validator(array $data)
	{
		//echo "<pre>"; print_r($data);exit;
		return Validator::make(
			$data,
			[
				'name' => 'required|min:3',
				'email' => 'required|email',
				'phone' => 'required|min:10',
				'password' => 'required',
				// 'dept_id' => 'required',
				// 'desig_id' => 'required',
				// 'dob' => 'required',
				// 'gender' => 'required',
				// 'qualification' => 'required',
				// 'c_addr_lineone' => 'required',
				// 'c_emp_country' => 'required',
				// 'c_emp_state' => 'required',
				// 'c_emp_city' => 'required',
				// 'c_emp_pincode' => 'required',
				// 'p_addr_lineone' => 'required',
				// 'p_emp_country' => 'required',
				// 'p_emp_state' => 'required',
				// 'p_emp_city' => 'required',
				// 'p_emp_pincode' => 'required',
				// 'basic_sal' => 'required',
				// 'hra' => 'required',
				// 'convayance' => 'required',
				// 'special_bonus' => 'required',
				// 'provident_fund' => 'required',
				// 'esi' => 'required',
				// 'loan' => 'required',
				// 'ptax' => 'required',
				// 'tds' => 'required',
				// 'total_deduction' => 'required',
				// 'total_addition' => 'required',
				// 'net_sal' => 'required',
				// 'net_sal_word' => 'required',
			]
		);
	}

	public function getEmployeeId()
	{
		$userId = Auth::user()->id;

		$prefix = 'emp' . $userId . '-';

		$lastEmployee = DB::table('employees')
			->select('employee_id')
			->where('employee_id', 'like', $prefix . '%')
			->orderBy('id', 'desc')
			->first();

		if ($lastEmployee && isset($lastEmployee->employee_id)) {
			$lastNumber = (int) str_replace($prefix, '', $lastEmployee->employee_id);
			$newNumber = $lastNumber + 1;
		} else {
			$newNumber = 1;
		}
		// Format the new employee_id
		$employee_id = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
		return $employee_id;
	}

	protected function createUser(array $data)
	{
		//echo "<pre>";print_r($data);exit;
		$userType = Auth::user()->u_type;
		$UserUserId = null;
		$CAuserId = null;
		$AdminUserId = null;
		if ($userType == "1") {
			$u_type = '4';
			$CAuserId = Auth::user()->id;
			$UserUserId = null;
		} else if ($userType == "2") {
			$u_type = '5';
			$UserUserId = Auth::user()->id;
			$CAuserId = null;
		}else if ($userType == "4") {
			$u_type = '4';
			$CAuserId = currentOwnerId();
			$UserUserId = null;
		}else if ($userType == "5") {
			$u_type = '5';
			$UserUserId = currentOwnerId();
			$CAuserId = null;
		}else if ($userType == "3") {
			$u_type = '6';
			$AdminUserId = Auth::user()->id;
		}else if ($userType == "6") {
			$u_type = '6';
			$AdminUserId = currentOwnerId();
		}

		// $userId = Auth::user()->id;

		return User::create([
			'name' => $data['name'],
			'email' => $data['login_email'],
			'phone' => $data['phone'],
			'u_type' => $u_type,
			'password' => Hash::make($data['password']),
			'status' => 1,
			'userStatus' => 1,
			'isActive' => 1,
			'user_add_by' => $UserUserId,
			'ca_add_by' => $CAuserId,
			'admin_add_by' => $AdminUserId,
			'country_id' => '101',
			'state_id' =>  $data['c_emp_state'],
			'city_id' => $data['c_emp_city'],
			'pincode' => $data['c_emp_pincode'],
			'emp_permission' => !empty($data['emp_permission']) ? implode(",", $data['emp_permission']): null,

		]);
	}

	public function add_user_employee(Request $request)
	{
		// 0) Normalize Blade snake_case → controller keys (run BEFORE validation)
		$request->merge([
			// Personal details
			'altphone' => $request->input('alt_phone'),
			'maritalstatus' => $request->input('marital_status'),
			'proqualification' => $request->input('pro_qualification'),
			'lastemployer' => $request->input('last_employer'),
			'experienceyears' => $request->input('experience_years'),

			// Address & reference
			'caddrlineone' => $request->input('c_addr_lineone'),
			'caddrlinetwo' => $request->input('c_addr_linetwo'),
			'cempstate' => $request->input('c_emp_state'),
			'cempcity' => $request->input('c_emp_city'),
			'cemppincode' => $request->input('c_emp_pincode'),

			'paddrlineone' => $request->input('p_addr_lineone'),
			'paddrlinetwo' => $request->input('p_addr_linetwo'),
			'pempstate' => $request->input('p_emp_state'),
			'pempcity' => $request->input('p_emp_city'),
			'pemppincode' => $request->input('p_emp_pincode'),

			'ref1name' => $request->input('ref1_name'),
			'ref1mobile' => $request->input('ref1_mobile'),
			'ref2name' => $request->input('ref2_name'),
			'ref2mobile' => $request->input('ref2_mobile'),
			'emergencyname' => $request->input('emergency_name'),
			'emergencymobile' => $request->input('emergency_mobile'),

			// Official
			'deptid' => $request->input('dept_id'),
			'designationid' => $request->input('designation_id'),
			'locationid' => $request->input('location_id'),
			'empjoiningdate' => $request->input('emp_joining_date'),
			'worklocation' => $request->input('work_location'),
			'empstatus' => $request->input('emp_status'),
			'statusdate' => $request->input('status_date'),
			'emptype' => $request->input('emp_type'),

			// Statutory
			'epfapplicable' => $request->boolean('epf_applicable'),
			'esicapplicable' => $request->boolean('esic_applicable'),
			'ptaxapplicable' => $request->boolean('ptax_applicable'),
			'tdsapplicable' => $request->boolean('tds_applicable'),
			'epfno' => $request->input('epf_no'),
			'esicno' => $request->input('esic_no'),

			// Earnings
			'totaladdition' => $request->input('total_addition'),
			'basicsal' => $request->input('basic_sal'),
			'basic_percentage' => $request->input('basic_percentage'),
			'medicalallowance' => $request->input('medical_allowance'),
			'specialbonus' => $request->input('special_bonus'),

			// Deductions
			'providentfund' => $request->input('provident_fund'),
			'totaldeduction' => $request->input('total_deduction'),
			'netsal' => $request->input('net_sal'),
			'netsalword' => $request->input('net_sal_word'),
			'loantenure' => $request->input('tenure_months') ?? $request->input('loantenure'),
			'loandeduction' => $request->input('loan_deduction') ?? $request->input('loandeduction'),

			// Bank
			'bankname' => $request->input('bank_name'),
			'bankbranch' => $request->input('bank_branch'),
			'swiftcode' => $request->input('swift_code'),
			'accountholdername' => $request->input('account_holder_name'),
			'accountnumber' => $request->input('account_number'),
			'confirmaccountno' => $request->input('confirm_account_number') ?? $request->input('account_number'),
			'upiid' => $request->input('upi_id'),

			// Access
			'emppermission' => $request->input('emp_permission'),
			'confirmpwd' => $request->input('confirm_pwd'),

			// IDs
			'pan_number' => $request->input('pan_number'),
			'aadhaar_number' => $request->input('aadhaar_number'),

			//LWF 
			'lwfapplicable' => $request->boolean('lwf_applicable'),
			'lwf_deduct' => $request->input('lwf_deduct'),
		]); 
		// [attached_file:1] status_date

		// 1) Basic pre-checks (Access tab)
		// $emp_permission = (array) $request->input('emppermission', []);
		// if (empty($emp_permission)) {
		// 	return response()->json([
		// 		'status' => 'error',
		// 		'class' => 'err',
		// 		'redirect' => url('/'),
		// 		'message' => 'Please set employee permission'
		// 	]);
		// }
		if (empty($request->locationid)) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please select company location'
			]);
		} // [attached_file:1]

		// 2) Validation (all five tabs)
		$wl = strtolower((string)$request->worklocation);
		$worklocationValue = match ($wl) {
			'workfromhome', 'wfh', 'home' => 'workfromhome',
			'workfromoffice', 'wfo', 'office' => 'workfromoffice',
			'hybrid' => 'hybrid',
			default => $request->worklocation,
		};
		$request->merge(['worklocation' => $worklocationValue]); // [attached_file:1]

		$request->validate([
			// Personal details
			'name' => ['required', 'string', 'max:150'],
			'phone' => ['required', 'regex:/^[0-9]{10}$/'],
			'email' => ['required', 'email'],
			'dob' => ['required', 'date'],
			'gender' => ['required', 'in:Male,Female,Other'],
			'qualification' => ['required', 'string', 'max:150'],

			'altphone' => ['nullable', 'regex:/^[0-9]{10}$/'],
			'maritalstatus' => ['nullable', 'in:Single,Married,Divorced,Widowed'],
			'proqualification' => ['nullable', 'string', 'max:150'],
			'lastemployer' => ['nullable', 'string', 'max:150'],
			'experienceyears' => ['nullable', 'numeric', 'min:0'],

			// Address and reference
			'caddrlineone' => ['required', 'string', 'max:255'],
			'caddrlinetwo' => ['nullable', 'string', 'max:255'],
			'cempstate' => ['required', 'integer'],
			'cempcity' => ['required', 'integer'],
			'cemppincode' => ['required', 'string', 'max:10'],

			'paddrlineone' => ['required', 'string', 'max:255'],
			'paddrlinetwo' => ['nullable', 'string', 'max:255'],
			'pempstate' => ['required', 'integer'],
			'pempcity' => ['required', 'integer'],
			'pemppincode' => ['required', 'string', 'max:10'],

			'ref1name' => ['nullable', 'string', 'max:150'],
			'ref1mobile' => ['nullable', 'regex:/^[0-9]{10}$/'],
			'ref2name' => ['nullable', 'string', 'max:150'],
			'ref2mobile' => ['nullable', 'regex:/^[0-9]{10}$/'],
			'emergencyname' => ['nullable', 'string', 'max:150'],
			'emergencymobile' => ['nullable', 'regex:/^[0-9]{10}$/'],

			// Official details
			'deptid' => ['required', 'integer'],
			'designationid' => ['required', 'integer'],
			'locationid' => ['required', 'integer'],
			'empjoiningdate' => ['required', 'date'],
			'worklocation' => ['required'],

			'empstatus' => ['nullable', 'in:In Probation,Confirmed,Terminated,Resigned'],
			'statusdate' => ['nullable', 'date'],
			'emptype' => ['nullable', 'in:Full Time,Part Time,Contract,Temporary'],

			// Statutory applicability
			'epfapplicable' => ['nullable', 'boolean'],
			'esicapplicable' => ['nullable', 'boolean'],
			'ptaxapplicable' => ['nullable', 'boolean'],
			'tdsapplicable' => ['nullable', 'boolean'],
			'epfno' => ['nullable', 'string', 'max:60'],
			'esicno' => ['nullable', 'string', 'max:60'],
			'lwfapplicable' => ['nullable', 'boolean'],
			'lwf_deduct' => ['nullable', 'numeric', 'min:0'],

			// Earnings
			'totaladdition' => ['required', 'numeric', 'min:0'],
			'basicsal' => ['required', 'numeric', 'min:0'],
			'basic_percentage' => ['required', 'numeric', 'between:40,60'],
			'hra' => ['required', 'numeric', 'min:0'],
			'convayance' => ['required', 'numeric', 'min:0'],
			'medicalallowance' => ['required', 'numeric', 'min:0'],
			'specialbonus' => ['required', 'numeric', 'min:0'],

			// Deductions
			'providentfund' => ['nullable', 'numeric', 'min:0'],
			'esi' => ['nullable', 'numeric', 'min:0'],
			'ptax' => ['nullable', 'numeric', 'min:0'],
			'tds' => ['nullable', 'numeric', 'min:0'],
			'loan' => ['nullable', 'numeric', 'min:0'],
			'loantenure' => ['nullable', 'integer', 'min:0'],
			'loandeduction' => ['nullable', 'numeric', 'min:0'],
			'totaldeduction' => ['required', 'numeric', 'min:0'],
			'netsal' => ['required', 'numeric', 'min:0'],
			'netsalword' => ['required', 'string', 'max:255'],

			// Bank details
			'bankname' => ['required', 'string', 'max:150'],
			'bankbranch' => ['required', 'string', 'max:150'],
			'ifsc' => ['required', 'string', 'max:20'],
			'swiftcode' => ['nullable', 'string', 'max:20'],
			'accountholdername' => ['required', 'string', 'max:150'],
			'accountnumber' => ['required', 'string', 'max:50'],
			'confirmaccountno' => ['required', 'same:accountnumber'],

			// Access
			'password' => ['required', 'string', 'min:6', 'max:64'],
			'confirmpwd' => ['required', 'same:password'],

			// Profile image
			'fileUpload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
			'pan_number' => ['nullable', 'string', 'max:20'],
			'aadhaar_number' => ['nullable', 'string', 'max:20'],

			// Employee attachments (up to 5 MB, common formats)
			'aadhar_attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
			'pan_attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
			'bank_passbook_attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
			'cv_attachment' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
			'certificate_attachment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],

			'experience_letter' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
			'offer_letter' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
			'other_doc' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:5120'],
		]); // [attached_file:1][attached_file:2]

		// 3) Conditional statutory rules
		$epfApplicable  = $request->boolean('epfapplicable');
		$esicApplicable = $request->boolean('esicapplicable');
		$ptaxApplicable = $request->boolean('ptaxapplicable');
		$tdsApplicable  = $request->boolean('tdsapplicable');
		$lwfApplicable = $request->boolean('lwfapplicable');

		if ($epfApplicable) {
			$request->validate([
				'epfno' => ['required', 'string', 'max:60'],
				'providentfund' => ['required', 'numeric', 'min:0'],
			]);
		} else {
			$request->merge(['epfno' => null, 'providentfund' => 0]);
		}
		if ($esicApplicable) {
			$request->validate([
				'esicno' => ['required', 'string', 'max:60'],
				'esi' => ['required', 'numeric', 'min:0'],
			]);
		} else {
			$request->merge(['esicno' => null, 'esi' => 0]);
		}
		if (!$ptaxApplicable) {
			$request->merge(['ptax' => 0]);
		}
		if (!$tdsApplicable) {
			$request->merge(['tds' => 0]);
		}
		if (!$lwfApplicable) {
			$request->merge(['lwf_deduct' => 0]);
		} // [attached_file:1]

		try {
			DB::beginTransaction();

			// 4) Create system user (Access tab)
			$user = User::where('email', '=', $request->email)->get();
			$user = @$user[0];
			if (!empty($user)) {
				return response()->json([
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Email already exists'
				]);
			}
			$insertEmployee = $this->createUser($request->all());
			$empUserId = DB::getPdo()->lastInsertId(); // [attached_file:1]

			$addedBy = currentOwnerId();
			$propId = $request->propId ?? null;

			// 5) Profile image: guarded store
			$profileFileName = null;
			if ($request->hasFile('fileUpload')) {
				$pf = $request->file('fileUpload');
				if (!$pf->isValid()) {
					throw ValidationException::withMessages([
						'fileUpload' => ["The file upload failed to upload."]
					]);
				}
				try {
					$ext = $pf->getClientOriginalExtension();
					$profileFileName = time() . '.' . $ext;
					$pf->storeAs('public/user_employee', $profileFileName);
				} catch (\Throwable $e) {
					throw ValidationException::withMessages([
						'fileUpload' => ["The file upload failed to upload."]
					]);
				}
			} // [attached_file:1]

			// 5.1) Employee documents helper (stores under public/employee_document) with guarded store
			// $storeEmpDoc = function (string $key) use ($request) {
			// 	if (!$request->hasFile($key)) return null;
			// 	$file = $request->file($key);
			// 	if (!$file->isValid()) {
			// 		throw ValidationException::withMessages([
			// 			$key => ["The file upload failed to upload."]
			// 		]);
			// 	}
			// 	try {
			// 		$ext  = $file->getClientOriginalExtension();
			// 		$name = time() . '_' . $key . '.' . $ext;
			// 		$file->storeAs('public/employee_document', $name);
			// 		return $name;
			// 	} catch (\Throwable $e) {
			// 		throw ValidationException::withMessages([
			// 			$key => ["The file upload failed to upload."]
			// 		]);
			// 	}
			// }; 

			$storeFile = function ($key) use ($request) {
				if (!$request->hasFile($key)) return null;

				$file = $request->file($key);
				if (!$file->isValid()) return null;

				$name = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
				$file->storeAs('public/employee_document', $name);

				return $name;
			};

			
			// [attached_file:1][attached_file:2]

			// 6) Save employee row
			$emp = new Employees();
			$emp->added_by = $addedBy;
			$emp->propId = $propId;
			$emp->empId = $empUserId;
			if ($profileFileName) $emp->profile_img = $profileFileName;

			// Employee code
			$emp->employee_id = $this->getEmployeeId();

			// Personal
			$emp->gender = $request->gender;
			$emp->dob = $request->dob;
			$emp->email_id = $request->email;
			$emp->qualification = $request->qualification;
			$emp->pan_number = $request->pan_number ?: null;
			$emp->aadhaar_number = $request->aadhaar_number ?: null;

			// Extra personal
			$emp->alt_phone = $request->altphone ?: null;
			$emp->marital_status = $request->maritalstatus ?: null;
			$emp->pro_qualification = $request->proqualification ?: null;
			$emp->last_employer = $request->lastemployer ?: null;
			$emp->experience_years = $request->experienceyears ?: null;

			// Addresses
			$emp->c_addr_lineone = $request->caddrlineone;
			$emp->c_addr_linetwo = $request->caddrlinetwo ?? '';
			$emp->c_emp_country = 101;
			$emp->c_emp_state = $request->cempstate;
			$emp->c_emp_city = $request->cempcity;
			$emp->c_emp_pincode = $request->cemppincode;

			$emp->p_addr_lineone = $request->paddrlineone;
			$emp->p_addr_linetwo = $request->paddrlinetwo ?? '';
			$emp->p_emp_country = 101;
			$emp->p_emp_state = $request->pempstate;
			$emp->p_emp_city = $request->pempcity;
			$emp->p_emp_pincode = $request->pemppincode;

			// References
			$emp->ref1_name = $request->ref1name ?: null;
			$emp->ref1_mobile = $request->ref1mobile ?: null;
			$emp->ref2_name = $request->ref2name ?: null;
			$emp->ref2_mobile = $request->ref2mobile ?: null;
			$emp->emergency_name = $request->emergencyname ?: null;
			$emp->emergency_mobile = $request->emergencymobile ?: null;

			// Official
			$emp->dept_id = $request->deptid;
			$emp->desig_id = $request->designationid;
			$emp->location_id = $request->locationid;
			$emp->joining_date = $request->empjoiningdate;
			$emp->work_location = $request->worklocation;

			// Status and type
			$emp->emp_status = $request->empstatus ?: 'In Probation';
			// $emp->regine_date = $request->statusdate ?: null;
			$emp->statusdate = $request->status_date ?: null;
			$emp->emp_type = $request->emptype ?: 'Full Time';

			// Earnings
			$emp->basic_sal = $request->basicsal;
			$emp->basic_percentage = $request->basic_percentage;
			$emp->hra = $request->hra;
			$emp->convayance = $request->convayance;
			$emp->medical_allowance = $request->medicalallowance;
			$emp->special_bonus = $request->specialbonus;
			$emp->total_addition = $request->totaladdition;

			// Statutory
			$emp->epf_applicable = $epfApplicable ? 1 : 0;
			$emp->esic_applicable = $esicApplicable ? 1 : 0;
			$emp->ptax_applicable = $ptaxApplicable ? 1 : 0;
			$emp->tds_applicable  = $tdsApplicable ? 1 : 0;
			$emp->epf_no = $request->epfno;
			$emp->esic_no = $request->esicno;
			$emp->lwf_applicable = $lwfApplicable ? 1 : 0;
			$emp->lwf_company_contribution = $lwfApplicable ? 30.00 : 0;

			// Deductions and loan
			$emp->provident_fund = $request->providentfund ?? 0;
			$emp->esi = $request->esi ?? 0;
			$emp->ptax = $request->ptax ?? 0;
			$emp->tds = $request->tds ?? 0;
			$emp->lwf_deduct = $request->lwf_deduct ?? 0;
			$emp->loan = $request->loan ?? 0;
			$emp->loan_tenure = $request->loantenure ?? 0;
			$emp->loan_deduction = $request->loandeduction ?? 0;
			$emp->total_deduction = $request->totaldeduction;
			$emp->net_sal = $request->netsal;
			$emp->net_sal_word = $request->netsalword;

			// Bank
			$emp->bank_name = $request->bankname;
			$emp->bank_branch = $request->bankbranch;
			$emp->ifsc = $request->ifsc;
			$emp->swift_code = $request->swiftcode ?? null;
			$emp->account_holder_name = $request->accountholdername;
			$emp->account_number = $request->accountnumber;
			$emp->upi_id = $request->upiid ?? null;

			// Attachments: store filenames into columns (guarded)
			// $emp->pan_doc                     = $storeEmpDoc('pan_doc');
			// $emp->aadhar_doc                  = $storeEmpDoc('aadhar_doc');
			// $emp->last_qualification_doc      = $storeEmpDoc('last_qualifaction_doc');
			// $emp->signed_appointment_letter   = $storeEmpDoc('sign_appoinment_doc');
			// $emp->cancelled_cheque_doc        = $storeEmpDoc('cancell_cheque_doc');
			// $emp->last_company_release_letter = $storeEmpDoc('relese_letter_doc'); // [attached_file:2][attached_file:1]

			// Documents
			$emp->aadhar_doc = $storeFile('aadhar_attachment');  //
			$emp->pan_doc = $storeFile('pan_attachment');  //
			$emp->cancelled_cheque_doc = $storeFile('bank_passbook_attachment'); //
			$emp->last_qualification_doc = $storeFile('certificate_attachment'); //
			$emp->cv_doc = $storeFile('cv_attachment');

			$emp->last_company_release_letter = $storeFile('experience_letter'); //
			$emp->signed_appointment_letter = $storeFile('offer_letter'); //
			$emp->other_doc = $storeFile('other_doc');

			$emp->save();

			DB::commit();

			$userType = Auth::user()->u_type;
			$routeLink = ($userType == 2 || $userType == 3 || $userType == 5 || $userType == 6)  ? route('user.EmployeeList') : route('CA.EmployeeList');

			return response()->json([
				'status' => 'success',
				'message' => 'Employee added successfully',
				'redirect' => $routeLink
			]);
		} catch (ValidationException $ve) {
			DB::rollBack();
			// Standard Laravel validation JSON with 422
			throw $ve;
		} catch (\Throwable $e) {
			DB::rollBack();
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to add employee: ' . $e->getMessage(),
				'redirect' => url('/')
			], 422);
		}
	}



	public function add_user_employee_bpk(Request $request)
	{
		$emp_permission = ($request->emp_permission);

		if (empty($emp_permission)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please set employee permission'
			);
			return response()->json($msg);
		}

		if (empty($request->location_id)) {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please select company location'
			);
			return response()->json($msg);
		}

		$validation = $this->validator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {

			$user = User::where('email', '=', $request->email)->get();
			$user = @$user[0];
			if (!empty($user)) {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Email already exists'
				);
				return response()->json($msg);
			}
			$insertEmployee = $this->createUser($request->all());

			$empId = DB::getPdo()->lastInsertId();
			$added_by = Auth::user()->id;

			$emp = new Employees;
			$emp->added_by = $added_by;
			$emp->empId = $empId;

			if ($request->hasFile('fileUpload')) {
				$file = $request->file('fileUpload');
				$fileName = time() . '.' . $file->getClientOriginalExtension();

				// Store file in storage/app/public/user_employee
				$file->storeAs('public/user_employee', $fileName);

				// Save only the file name in the database
				$emp->profile_img = $fileName;
			}

			$emp->employee_id = $this->getEmployeeId();
			$emp->gender = $request->gender;
			$emp->dob = $request->dob;
			$emp->email_id = $request->email;
			$emp->qualification = $request->qualification;

			$emp->c_addr_lineone = $request->c_addr_lineone;
			$emp->c_addr_linetwo = isset($request->c_addr_linetwo) ? $request->c_addr_linetwo : "";
			$emp->c_emp_country = '101';
			$emp->c_emp_state = $request->cempstate;
			$emp->c_emp_city = $request->cempcity;
			$emp->c_emp_pincode = $request->cemppincode;

			$emp->p_addr_lineone = $request->p_addr_lineone;
			$emp->p_addr_linetwo = isset($request->p_addr_linetwo) ? $request->p_addr_linetwo : "";
			$emp->p_emp_country = "101";
			$emp->p_emp_state = $request->pempstate;
			$emp->p_emp_city = $request->pempcity;
			$emp->p_emp_pincode = $request->p_emp_pincode;

			$emp->dept_id = $request->dept_id;
			$emp->desig_id = $request->designation_id;
			$emp->location_id = $request->location_id;
			$emp->basic_sal = $request->basic_sal;
			$emp->hra = $request->hra;
			$emp->convayance = $request->convayance;
			$emp->medical_allowance = $request->medical_allowance;   //---------- medical_allowance ----------
			$emp->special_bonus = $request->special_bonus;
			$emp->provident_fund = $request->provident_fund;
			$emp->esi = $request->esi;
			$emp->loan = $request->loan;
			$emp->ptax = $request->ptax;
			$emp->tds = $request->tds;
			$emp->total_deduction = $request->total_deduction;
			$emp->total_addition = $request->total_addition;
			$emp->net_sal = $request->net_sal;
			$emp->net_sal_word = $request->net_sal_word;
			$emp->joining_date = $request->emp_joining_date;
			$emp->work_location = $request->work_location;

			$emp->bank_name = $request->bank_name;
			$emp->bank_branch = $request->bank_branch;
			$emp->ifsc = $request->ifsc;
			$emp->swift_code = $request->swift_code;
			$emp->account_holder_name = $request->account_holder_name;
			$emp->account_number = $request->account_number;
			$emp->upi_id = $request->upi_id;
			$emp->save();

			if ($insertEmployee) {
				$userType = Auth::user()->u_type;
				if ($userType == 2) {
					$routeLink = route('user.EmployeeList');
				} else if ($userType == 1) {
					$routeLink = route('CA.EmployeeList');
				}

				return response()->json([
					'status' => 'success',
					'message' => 'Employee added successfully',
					'redirect' => $routeLink
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'message' => 'Enter all details for employee',
					'redirect' => url('/')
				]);
			}
		}
	}

	// public function view_user_employee($encodedId)
	// {
	// 	$id = base64_decode($encodedId);
		
	// 	$userId = currentOwnerId();

	// 	// Fetch employee and basic info
	// 	$employee = DB::table('users')
	// 		->select('users.*', 'employees.*')
	// 		->leftJoin('employees', 'users.id', '=', 'employees.empId')
	// 		->where('users.id', '=', $id)
	// 		->first();

	// 	$stateName = DB::table('states')->where('id', $employee->state_id)->value('name');
	// 	$cityName = DB::table('cities')->where('id', $employee->city_id)->value('name');
	// 	$departmentName = DB::table('depertments')->where('id', $employee->dept_id)->value('dept_name');
	// 	$designationName = DB::table('designations')->where('id', $employee->desig_id)->value('designation_name');

	// 	$states = State::where('country_id', 101)->get();
	// 	$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();

	// 	// Dates
	// 	$startOfMonth = Carbon::now()->startOfMonth()->toDateString();
	// 	$today = Carbon::now()->toDateString();

	// 	// Attendance this month up to today
	// 	$attendance = DB::table('attendance')
	// 		->where('userId', $id)
	// 		->whereBetween('present_date', [$startOfMonth, $today])
	// 		->get();

	// 	$presentDates = $attendance->pluck('present_date')->toArray();

	// 	// Weekly schedule (open days)
	// 	$weeklySchedule = DB::table('weekly_schedules')
	// 		->where('added_by', $userId)
	// 		->where('status', 'open')
	// 		->get()
	// 		->keyBy(function ($item) {
	// 			return strtolower($item->day);
	// 		});

	// 	// Holidays in this month up to today
	// 	$holidays = DB::table('holidays')
	// 		->where('added_by', $userId)
	// 		->whereBetween('holidayDate', [$startOfMonth, $today])
	// 		->pluck('holidayDate')
	// 		->toArray();

	// 	// Approved leaves affecting this month up to today
	// 	$leavePeriods = DB::table('leaves')
	// 		->where('emp_id', $id)
	// 		->where('status', 'approved')
	// 		->where(function ($q) use ($startOfMonth, $today) {
	// 			$q->whereBetween('start_date', [$startOfMonth, $today])
	// 				->orWhereBetween('end_date', [$startOfMonth, $today])
	// 				->orWhere(function ($q2) use ($startOfMonth, $today) {
	// 					$q2->where('start_date', '<=', $startOfMonth)
	// 						->where('end_date', '>=', $today);
	// 				});
	// 		})
	// 		->get();

	// 	$leaveDates = [];
	// 	foreach ($leavePeriods as $leave) {
	// 		$leaveStart = Carbon::parse($leave->start_date)->lessThan(Carbon::parse($startOfMonth)) ? Carbon::parse($startOfMonth) : Carbon::parse($leave->start_date);
	// 		$leaveEnd = Carbon::parse($leave->end_date)->greaterThan(Carbon::parse($today)) ? Carbon::parse($today) : Carbon::parse($leave->end_date);
	// 		foreach (CarbonPeriod::create($leaveStart, $leaveEnd) as $lDay) {
	// 			$leaveDates[] = $lDay->toDateString();
	// 		}
	// 	}
	// 	$leaveDates = array_unique($leaveDates);

	// 	// Calculate working days and absences
	// 	$period = CarbonPeriod::create($startOfMonth, $today);
	// 	$totalWorkingDays = 0;
	// 	$absentDays = [];

	// 	foreach ($period as $date) {
	// 		$dateString = $date->toDateString();
	// 		$dayName = strtolower($date->format('l'));
	// 		// Only count if open in weekly schedule and not a holiday
	// 		if (isset($weeklySchedule[$dayName]) && !in_array($dateString, $holidays)) {
	// 			$totalWorkingDays++;
	// 			if (
	// 				!in_array($dateString, $presentDates) &&
	// 				!in_array($dateString, $leaveDates)
	// 			) {
	// 				$absentDays[] = $dateString; // Absent
	// 			}
	// 		}
	// 	}
	// 	$totalAbsentDays = count($absentDays);
			

	// 	// Late/On-Time Count (unchanged)
	// 	$lateCount = 0;
	// 	$onTimeCount = 0;
	// 	foreach ($attendance as $record) {
	// 		$dayName = strtolower(Carbon::parse($record->present_date)->format('l'));
	// 		if (isset($weeklySchedule[$dayName])) {
	// 			$openingTime = $weeklySchedule[$dayName]->opening_time;
	// 			// Compare in_time with opening_time
	// 			if ($record->in_time > $openingTime) {
	// 				$lateCount++;
	// 			} else {
	// 				$onTimeCount++;
	// 			}
	// 		}
	// 	}

	// 	// For reporting: total leave days counted in current month up to today
	// 	$totalLeaveDaysThisMonth = 0;
	// 	foreach ($leaveDates as $d) {
	// 		$totalLeaveDaysThisMonth++;
	// 	}

	// 	// Pending leave requests
	// 	$pendingLeaves = DB::table('leaves')
	// 			->where('emp_id', $id)
	// 			->where('status', 'pending')
	// 			->get();


	// 	return view('User.viewUserEmployee')->with([
	// 		'states'             => $states,
	// 		'locations'          => $locations,
	// 		'employee'           => $employee,
	// 		'stateName'          => $stateName,
	// 		'cityName'           => $cityName,
	// 		'departmentName'     => $departmentName,
	// 		'designationName'    => $designationName,
	// 		'attendance'         => $attendance,
	// 		'currentMonth'       => Carbon::now()->format('Y-m'),
	// 		'presentThisMonth'   => count($presentDates),
	// 		'lateCountThisMonth' => $lateCount,
	// 		'onTimeCountThisMonth' => $onTimeCount,
	// 		'totalWorkingDays'   => $totalWorkingDays,
	// 		'totalLeaveDaysThisMonth' => $totalLeaveDaysThisMonth,
	// 		'totalAbsentDays'    => $totalAbsentDays,
	// 		'absentDates'        => $absentDays, // you can show which dates were absent
	// 		'pendingLeaves'      => $pendingLeaves, // ✅ New data for view
	// 	]);
	// }

	public function view_user_employee($encodedId)
	{
		$id = base64_decode($encodedId);
		$userId = currentOwnerId();

		// Fetch employee and basic info
		$employee = DB::table('users')
			->select('users.*', 'employees.*')
			->leftJoin('employees', 'users.id', '=', 'employees.empId')
			->where('users.id', '=', $id)
			->first();

		// Prevent error if employee not found
		if (!$employee) {
			return redirect()->back()->with('error', 'Employee not found');
		}

		$stateName = DB::table('states')->where('id', $employee->state_id)->value('name');
		$cityName = DB::table('cities')->where('id', $employee->city_id)->value('name');
		$departmentName = DB::table('depertments')->where('id', $employee->dept_id)->value('dept_name');
		$designationName = DB::table('designations')->where('id', $employee->desig_id)->value('designation_name');

		$states = State::where('country_id', 101)->get();
		$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();

		// Dates
		$today = Carbon::now()->toDateString();
		$monthStart = Carbon::now()->startOfMonth();

		// ✅ Joining date logic (FIXED)
		if (!empty($employee->joining_date)) {
			$joiningDateCarbon = Carbon::parse($employee->joining_date);

			$startOfMonth = $joiningDateCarbon->greaterThan($monthStart)
				? $joiningDateCarbon->toDateString()
				: $monthStart->toDateString();
		} else {
			$startOfMonth = $monthStart->toDateString();
		}

		// Attendance this month up to today
		$attendance = DB::table('attendance')
			->where('userId', $id)
			->whereBetween('present_date', [$startOfMonth, $today])
			->get();

		$presentDates = $attendance->pluck('present_date')->toArray();

		// Weekly schedule (open days)
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $userId)
			->where('status', 'open')
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Holidays
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereBetween('holidayDate', [$startOfMonth, $today])
			->pluck('holidayDate')
			->toArray();

		// Approved leaves
		$leavePeriods = DB::table('leaves')
			->where('emp_id', $id)
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

		// Expand leave dates
		$leaveDates = [];
		foreach ($leavePeriods as $leave) {
			$leaveStart = Carbon::parse($leave->start_date)->lessThan(Carbon::parse($startOfMonth))
				? Carbon::parse($startOfMonth)
				: Carbon::parse($leave->start_date);

			$leaveEnd = Carbon::parse($leave->end_date)->greaterThan(Carbon::parse($today))
				? Carbon::parse($today)
				: Carbon::parse($leave->end_date);

			foreach (CarbonPeriod::create($leaveStart, $leaveEnd) as $lDay) {
				$leaveDates[] = $lDay->toDateString();
			}
		}
		$leaveDates = array_unique($leaveDates);

		// ✅ Working days & absent calculation (corrected with joining date)
		$period = CarbonPeriod::create($startOfMonth, $today);

		$totalWorkingDays = 0;
		$absentDays = [];

		foreach ($period as $date) {
			$dateString = $date->toDateString();
			$dayName = strtolower($date->format('l'));

			if (isset($weeklySchedule[$dayName]) && !in_array($dateString, $holidays)) {
				$totalWorkingDays++;

				if (
					!in_array($dateString, $presentDates) &&
					!in_array($dateString, $leaveDates)
				) {
					$absentDays[] = $dateString;
				}
			}
		}
		
		$totalAbsentDays = count($absentDays);

		// Late / On-time
		$lateCount = 0;
		$onTimeCount = 0;

		foreach ($attendance as $record) {
			$dayName = strtolower(Carbon::parse($record->present_date)->format('l'));

			if (isset($weeklySchedule[$dayName])) {
				$openingTime = $weeklySchedule[$dayName]->opening_time;

				if ($record->in_time > $openingTime) {
					$lateCount++;
				} else {
					$onTimeCount++;
				}
			}
		}

		// Total leave days
		$totalLeaveDaysThisMonth = count($leaveDates);

		// Pending leaves
		$pendingLeaves = DB::table('leaves')
			->where('emp_id', $id)
			->where('status', 'pending')
			->get();

		return view('User.viewUserEmployee')->with([
			'states'                   => $states,
			'locations'                => $locations,
			'employee'                 => $employee,
			'stateName'                => $stateName,
			'cityName'                 => $cityName,
			'departmentName'           => $departmentName,
			'designationName'          => $designationName,
			'attendance'               => $attendance,
			'currentMonth'             => Carbon::now()->format('Y-m'),
			'presentThisMonth'         => count($presentDates),
			'lateCountThisMonth'       => $lateCount,
			'onTimeCountThisMonth'     => $onTimeCount,
			'totalWorkingDays'         => $totalWorkingDays,
			'totalLeaveDaysThisMonth'  => $totalLeaveDaysThisMonth,
			'totalAbsentDays'          => $totalAbsentDays,
			'absentDates'              => $absentDays,
			'pendingLeaves'            => $pendingLeaves,
		]);
	}

	

	public function view_resign_user_employee($encodedId)
	{
		$id = base64_decode($encodedId);
		$userId = currentOwnerId();

		// Fetch employee and basic info
		$employee = DB::table('resign_employee')
			->select('resign_employee.*', 'employees.*')
			->leftJoin('employees', 'resign_employee.id', '=', 'employees.empId')
			->where('resign_employee.id', '=', $id)
			->first();

		$stateName = DB::table('states')->where('id', $employee->state_id)->value('name');
		$cityName = DB::table('cities')->where('id', $employee->city_id)->value('name');
		$departmentName = DB::table('depertments')->where('id', $employee->dept_id)->value('dept_name');
		$designationName = DB::table('designations')->where('id', $employee->desig_id)->value('designation_name');

		$states = State::where('country_id', 101)->get();
		$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();

		// Dates
		$startOfMonth = Carbon::now()->startOfMonth()->toDateString();
		$today = Carbon::now()->toDateString();

		// Attendance
		$attendance = DB::table('attendance')
			->where('userId', $id)
			->whereBetween('present_date', [$startOfMonth, $today])
			->get();

		$presentDates = $attendance->pluck('present_date')->toArray();

		// Weekly schedule
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $userId)
			->where('status', 'open')
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Holidays
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereBetween('holidayDate', [$startOfMonth, $today])
			->pluck('holidayDate')
			->toArray();

		// Leaves
		$leavePeriods = DB::table('leaves')
			->where('emp_id', $id)
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
			$leaveStart = Carbon::parse($leave->start_date)->lessThan(Carbon::parse($startOfMonth))
				? Carbon::parse($startOfMonth)
				: Carbon::parse($leave->start_date);

			$leaveEnd = Carbon::parse($leave->end_date)->greaterThan(Carbon::parse($today))
				? Carbon::parse($today)
				: Carbon::parse($leave->end_date);

			foreach (CarbonPeriod::create($leaveStart, $leaveEnd) as $lDay) {
				$leaveDates[] = $lDay->toDateString();
			}
		}

		$leaveDates = array_unique($leaveDates);

		// Working days & absent
		$period = CarbonPeriod::create($startOfMonth, $today);
		$totalWorkingDays = 0;
		$absentDays = [];

		foreach ($period as $date) {
			$dateString = $date->toDateString();
			$dayName = strtolower($date->format('l'));

			if (isset($weeklySchedule[$dayName]) && !in_array($dateString, $holidays)) {
				$totalWorkingDays++;

				if (
					!in_array($dateString, $presentDates) &&
					!in_array($dateString, $leaveDates)
				) {
					$absentDays[] = $dateString;
				}
			}
		}

		$totalAbsentDays = count($absentDays);

		// Late / On-time
		$lateCount = 0;
		$onTimeCount = 0;

		foreach ($attendance as $record) {
			$dayName = strtolower(Carbon::parse($record->present_date)->format('l'));

			if (isset($weeklySchedule[$dayName])) {
				$openingTime = $weeklySchedule[$dayName]->opening_time;

				if ($record->in_time > $openingTime) {
					$lateCount++;
				} else {
					$onTimeCount++;
				}
			}
		}

		$totalLeaveDaysThisMonth = count($leaveDates);

		// Pending leaves
		$pendingLeaves = DB::table('leaves')
			->where('emp_id', $id)
			->where('status', 'pending')
			->get();

		// echo '<pre>';
		// print_r($employee);
		// die();

		return view('User.viewUserEmployee')->with([
			'states'             => $states,
			'locations'          => $locations,
			'employee'           => $employee,
			'stateName'          => $stateName,
			'cityName'           => $cityName,
			'departmentName'     => $departmentName,
			'designationName'    => $designationName,
			'attendance'         => $attendance,
			'currentMonth'       => Carbon::now()->format('Y-m'),
			'presentThisMonth'   => count($presentDates),
			'lateCountThisMonth' => $lateCount,
			'onTimeCountThisMonth' => $onTimeCount,
			'totalWorkingDays'   => $totalWorkingDays,
			'totalLeaveDaysThisMonth' => $totalLeaveDaysThisMonth,
			'totalAbsentDays'    => $totalAbsentDays,
			'absentDates'        => $absentDays,
			'pendingLeaves'      => $pendingLeaves,
		]);
	}



	// Edit Employee
	public function edit_user_employee($encodedId)
	{
		$id = base64_decode($encodedId);
		$uType = Auth::user()->u_type;
		$userId = currentOwnerId();

		$menu_features = DB::table('menu_features')
			->orderBy('parent_id')
			->orderBy('id')
			->get();

		$mainMenus = $menu_features->where('type', 'MAIN');

		if($uType == 2 || $uType == 5){
			// Fetch employee details with all fields from 'users' and 'employees' tables
			$employee = DB::table('users')
				->select(
					'users.*',
					'employees.*'
				)
				->leftJoin('employees', 'users.id', '=', 'employees.empId')
				->where('users.id', '=', $id)
				->first();

			// Fetch states and locations
			$userId = currentOwnerId();
			$states = State::where('country_id', '=', 101)->get();
			$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();
			$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
			// echo '<pre>';
			// print_r($employee);
			// die();
			return view('User.editUserEmployee')->with([
				'states'   => $states,
				'locations' => $locations,
				'employee' => $employee,
				'menu_features'   => $menu_features,
        		'mainMenus'       => $mainMenus,
				'proprietorships' => $proprietorships,
			]);
		}else if($uType == 1 ||  $uType == 4){
			// Fetch employee details with all fields from 'ca' and 'ca employees' tables
			$employee = DB::table('users')
				->select(
					'users.*',
					'employees.*'
				)
				->leftJoin('employees', 'users.id', '=', 'employees.empId')
				->where('users.id', '=', $id)
				->first();

			// Fetch states and locations
			$userId = currentOwnerId();
			$states = State::where('country_id', '=', 101)->get();
			$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();
			// echo '<pre>';
			// print_r($employee);
			// die();
			return view('Ca.edit-employee')->with([
				'states'   => $states,
				'locations' => $locations,
				'employee' => $employee,
			]);
		}else if($uType == 3 || $uType == 6){
			// Fetch employee details with all fields from 'admin' and 'admin employees' tables
			$employee = DB::table('users')
				->select(
					'users.*',
					'employees.*'
				)
				->leftJoin('employees', 'users.id', '=', 'employees.empId')
				->where('users.id', '=', $id)
				->first();

			// Fetch states and locations
			$userId = currentOwnerId();
			$states = State::where('country_id', '=', 101)->get();
			$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();
			// echo '<pre>';
			// print_r($employee);
			// die();
			return view('User.editUserEmployee')->with([
				'states'   => $states,
				'locations' => $locations,
				'employee' => $employee,
			]);
		}

		
	}



	// public function delet_user_employee($encodedId) {
	// 	$id = base64_decode($encodedId);		
	// 	// Find the employee and update the status
	// 	$user = User::findOrFail($id);
	// 	$user->status = 0;
	// 	$user->save();

	// 	return redirect()->back()->with('success', 'Employee Delete Successfully.');
	// }

	public function delet_user_employee($encodedId)
	{
		$id = base64_decode($encodedId);
		$user = User::find($id);

		if ($user) {
			$user->status = 0;
			$user->save();

			// AUDIT LOG
			AuditLogger::logEntry(
				action: 'update',
				module: 'Employee',
				description: "Employee deactivated: {$user->name}",
				oldData: null,
				newData: null
			);
			return response()->json(['status' => 'succ', 'message' => 'Employee Deactive successfully.']);
		}

		return response()->json(['status' => 'error', 'message' => 'Employee not found.'], 404);
	}

	// public function update_user_employee(Request $request)
	// {
	// 	$empId = $request->input('id'); // Get employee ID from hidden input

	// 	if (!$empId) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'message' => 'Invalid Employee ID',
	// 		]);
	// 	}

	// 	$employee = Employees::find($empId);
	// 	if (!$employee) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'message' => 'Employee not found',
	// 		]);
	// 	}

	// 	$user = User::find($employee->empId);
	// 	if (!$user) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'message' => 'User not found',
	// 		]);
	// 	}

	// 	// Validate input data (add all required fields)
	// 	$validation = Validator::make($request->all(), [
	// 		'name' => 'required|min:3',
	// 		'phone' => 'required|min:10',
	// 		'dob' => 'required|date',
	// 		'gender' => 'required',
	// 		'qualification' => 'required',
	// 		'dept_id' => 'required',
	// 		'designation_id' => 'required',
	// 		'location_id' => 'required',
	// 		'emp_joining_date' => 'required|date',
	// 		'bank_name' => 'required',
	// 		'bank_branch' => 'required',
	// 		'ifsc' => 'required',
	// 		'account_holder_name' => 'required',
	// 		'account_number' => 'required',
	// 		'confirm_account_no' => 'required|same:account_number',
	// 		'total_addition' => 'required|numeric',
	// 		'basic_sal' => 'required|numeric',
	// 		'hra' => 'required|numeric',
	// 		'convayance' => 'required|numeric',
	// 		'medical_allowance' => 'required|numeric',
	// 		'special_bonus' => 'required|numeric',
	// 		'total_deduction' => 'required|numeric',
	// 		'net_sal' => 'required|numeric',
	// 		'net_sal_word' => 'required',
	// 		'pan_number' => 'required|nullable|string|max:20',
	// 		'aadhaar_number' => 'required|nullable|string|max:20',
	// 		// Add more validation as needed
	// 	]);

	// 	if ($validation->fails()) {
	// 		return response()->json($validation->errors()->toArray());
	// 	}

	// 	// Update User Table (excluding email & password)
	// 	$user->name = $request->name;
	// 	$user->phone = $request->phone;
	// 	$user->state_id = $request->c_emp_state;
	// 	$user->city_id = $request->c_emp_city;
	// 	$user->pincode = $request->c_emp_pincode;
	// 	$user->emp_permission = is_array($request->emp_permission) ? implode(",", $request->emp_permission) : $request->emp_permission;
	// 	$user->save();

	// 	// Update Employee Table (all fields from form)
	// 	$employee->gender = $request->gender;
	// 	$employee->dob = $request->dob;
	// 	$employee->qualification = $request->qualification;
	// 	$employee->pan_number = $request->pan_number ?? null;
	// 	$employee->aadhaar_number = $request->aadhaar_number ?? null;

	// 	$employee->alt_phone = $request->alt_phone ?? null;
	// 	$employee->marital_status = $request->marital_status ?? null;
	// 	$employee->pro_qualification = $request->pro_qualification ?? null;
	// 	$employee->last_employer = $request->last_employer ?? null;
	// 	$employee->experience_years = $request->experience_years ?? null;

	// 	$employee->c_addr_lineone = $request->c_addr_lineone;
	// 	$employee->c_addr_linetwo = $request->c_addr_linetwo ?? "";
	// 	$employee->c_emp_state = $request->c_emp_state;
	// 	$employee->c_emp_city = $request->c_emp_city;
	// 	$employee->c_emp_pincode = $request->c_emp_pincode;

	// 	$employee->p_addr_lineone = $request->p_addr_lineone;
	// 	$employee->p_addr_linetwo = $request->p_addr_linetwo ?? "";
	// 	$employee->p_emp_state = $request->p_emp_state;
	// 	$employee->p_emp_city = $request->p_emp_city;
	// 	$employee->p_emp_pincode = $request->p_emp_pincode;

	// 	// References & Emergency
	// 	$employee->ref1_name = $request->ref1_name ?? null;
	// 	$employee->ref1_mobile = $request->ref1_mobile ?? null;
	// 	$employee->ref2_name = $request->ref2_name ?? null;
	// 	$employee->ref2_mobile = $request->ref2_mobile ?? null;
	// 	$employee->emergency_name = $request->emergency_name ?? null;
	// 	$employee->emergency_mobile = $request->emergency_mobile ?? null;

	// 	// Official Details
	// 	$employee->dept_id = $request->dept_id;
	// 	$employee->desig_id = $request->designation_id;
	// 	$employee->location_id = $request->location_id;
	// 	$employee->joining_date = $request->emp_joining_date;
	// 	$employee->work_location = $request->work_location;
	// 	$employee->emp_status = $request->emp_status ?? 'In Probation';
	// 	$employee->regine_date = $request->status_date ?? null;
	// 	$employee->emp_type = $request->emp_type ?? 'Full Time';

	// 	// Statutory Applicability
	// 	$employee->epf_applicable = $request->has('epf_applicable') ? 1 : 0;
	// 	$employee->esic_applicable = $request->has('esic_applicable') ? 1 : 0;
	// 	$employee->ptax_applicable = $request->has('ptax_applicable') ? 1 : 0;
	// 	$employee->tds_applicable = $request->has('tdsapplicable') ? 1 : 0;
	// 	$employee->epf_no = $request->epf_no ?? null;
	// 	$employee->esic_no = $request->esic_no ?? null;

	// 	// Earnings
	// 	$employee->total_addition = $request->total_addition;
	// 	$employee->basic_sal = $request->basic_sal;
	// 	$employee->hra = $request->hra;
	// 	$employee->convayance = $request->convayance;
	// 	$employee->medical_allowance = $request->medical_allowance;
	// 	$employee->special_bonus = $request->special_bonus;

	// 	// Deductions & Loan
	// 	$employee->provident_fund = $request->provident_fund ?? 0;
	// 	$employee->esi = $request->esi ?? 0;
	// 	$employee->loan = $request->loan ?? 0;
	// 	$employee->loan_tenure = $request->loan_tenure ?? 0;
	// 	$employee->loan_deduction = $request->loan_deduction ?? 0;
	// 	$employee->ptax = $request->ptax ?? 0;
	// 	$employee->tds = $request->tds ?? 0;
	// 	$employee->total_deduction = $request->total_deduction;
	// 	$employee->net_sal = $request->net_sal;
	// 	$employee->net_sal_word = $request->net_sal_word;

	// 	// Bank Details
	// 	$employee->bank_name = $request->bank_name;
	// 	$employee->bank_branch = $request->bank_branch;
	// 	$employee->ifsc = $request->ifsc;
	// 	$employee->swift_code = $request->swift_code ?? null;
	// 	$employee->account_holder_name = $request->account_holder_name;
	// 	$employee->account_number = $request->account_number;
	// 	$employee->upi_id = $request->upi_id ?? null;

	// 	// Update profile image if a new file is uploaded
	// 	if ($request->hasFile('fileUpload')) {
	// 		$file = $request->file('fileUpload');
	// 		$fileName = time() . '.' . $file->getClientOriginalExtension();
	// 		$file->storeAs('public/user_employee', $fileName);
	// 		$employee->profile_img = $fileName;
	// 	}

	// 	$employee->save();

	// 	$userType = Auth::user()->u_type;
	// 	if ($userType == 2) {
	// 		$routeLink = route('user.EmployeeList');
	// 	} else if ($userType == 1) {
	// 		$routeLink = route('CA.EmployeeList');
	// 	}

	// 	return response()->json([
	// 		'status' => 'success',
	// 		'message' => 'Employee updated successfully',
	// 		'redirect' => $routeLink,
	// 	]);
	// }

	public function update_user_employee(Request $request)
	{
		$empId = $request->input('id');

		if (!$empId) {
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid Employee ID',
			]);
		}

		$employee = Employees::find($empId);
		if (!$employee) {
			return response()->json([
				'status' => 'error',
				'message' => 'Employee not found',
			]);
		}

		$user = User::find($employee->empId);
		if (!$user) {
			return response()->json([
				'status' => 'error',
				'message' => 'User not found',
			]);
		}

		// ✅ Validation
		$validation = Validator::make($request->all(), [
			'name' => 'required|min:3',
			'phone' => 'required|min:10',
			'dob' => 'required|date',
			'gender' => 'required',
			'qualification' => 'required',
			'dept_id' => 'required',
			'designation_id' => 'required',
			'location_id' => 'required',
			'emp_joining_date' => 'required|date',
			'bank_name' => 'required',
			'bank_branch' => 'required',
			'ifsc' => 'required',
			'account_holder_name' => 'required',
			'account_number' => 'required',
			'confirm_account_no' => 'required|same:account_number',
			'total_addition' => 'required|numeric',
			'basic_sal' => 'required|numeric',
			'basic_percentage' => 'required|numeric|between:40,60',

			'hra' => 'required|numeric',
			'convayance' => 'required|numeric',
			'medical_allowance' => 'required|numeric',
			'special_bonus' => 'required|numeric',
			'total_deduction' => 'required|numeric',
			'net_sal' => 'required|numeric',
			'net_sal_word' => 'required',
			'pan_number' => 'required|nullable|string|max:20',
			'aadhaar_number' => 'required|nullable|string|max:20',

			'aadhar_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
			'pan_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
			'bank_passbook_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
			'cv_attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
			'certificate_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
			'experience_letter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
			'offer_letter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
			'other_doc' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
			'lwf_applicable' => 'nullable|boolean',
			'lwf_deduct' => 'nullable|numeric|min:0',
		]);

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}
		
		// ================= OLD DATA SNAPSHOT =================
		$oldUser = $user->only([
			'name',
			'phone',
			'state_id',
			'city_id',
			'pincode',
			'emp_permission'
		]);

		$oldEmployee = $employee->only([
			'gender',
			'dob',
			'qualification',
			'dept_id',
			'desig_id',
			'location_id',
			'joining_date',
			'emp_status',
			'emp_type',
			'total_addition',
			'basic_sal',
			'net_sal',
			'bank_name',
			'ifsc'
		]);

		// ✅ Update User
		$user->name = $request->name;
		$user->phone = $request->phone;
		$user->state_id = $request->c_emp_state;
		$user->city_id = $request->c_emp_city;
		$user->pincode = $request->c_emp_pincode;
		$user->emp_permission = is_array($request->emp_permission)
			? implode(",", $request->emp_permission)
			: $request->emp_permission;
		$user->save();

		$lwfApplicable = $request->boolean('lwf_applicable');
		if (!$lwfApplicable) {
			$request->merge(['lwf_deduct' => 0]);
		}

		// ✅ Update Employee
		$employee->fill([
			'gender' => $request->gender,
			'dob' => $request->dob,
			'qualification' => $request->qualification,
			'pan_number' => $request->pan_number,
			'aadhaar_number' => $request->aadhaar_number,
			'alt_phone' => $request->alt_phone ?? null,
			'marital_status' => $request->marital_status ?? null,
			'pro_qualification' => $request->pro_qualification ?? null,
			'last_employer' => $request->last_employer ?? null,
			'experience_years' => $request->experience_years ?? null,

			// Address
			'c_addr_lineone' => $request->c_addr_lineone,
			'c_addr_linetwo' => $request->c_addr_linetwo ?? '',
			'c_emp_state' => $request->c_emp_state,
			'c_emp_city' => $request->c_emp_city,
			'c_emp_pincode' => $request->c_emp_pincode,
			'p_addr_lineone' => $request->p_addr_lineone,
			'p_addr_linetwo' => $request->p_addr_linetwo ?? '',
			'p_emp_state' => $request->p_emp_state,
			'p_emp_city' => $request->p_emp_city,
			'p_emp_pincode' => $request->p_emp_pincode,

			// Reference
			'ref1_name' => $request->ref1_name ?? null,
			'ref1_mobile' => $request->ref1_mobile ?? null,
			'ref2_name' => $request->ref2_name ?? null,
			'ref2_mobile' => $request->ref2_mobile ?? null,
			'emergency_name' => $request->emergency_name ?? null,
			'emergency_mobile' => $request->emergency_mobile ?? null,

			// Official
			'dept_id' => $request->dept_id,
			'desig_id' => $request->designation_id,
			'location_id' => $request->location_id,
			'joining_date' => $request->emp_joining_date,
			'work_location' => $request->work_location,
			'emp_status' => $request->emp_status ?? 'In Probation',
			'statusdate' => $request->status_date ?? null,
			'emp_type' => $request->emp_type ?? 'Full Time',
			'propId' => $request->propId ?? null,

			// Statutory
			'epf_applicable' => $request->has('epf_applicable') ? 1 : 0,
			'esic_applicable' => $request->has('esic_applicable') ? 1 : 0,
			'ptax_applicable' => $request->has('ptax_applicable') ? 1 : 0,
			'tds_applicable' => $request->has('tds_applicable') ? 1 : 0,
			'epf_no' => $request->epf_no ?? null,
			'esic_no' => $request->esic_no ?? null,
			'lwf_applicable' => $request->boolean('lwf_applicable'),
			'lwf_company_contribution' => $request->boolean('lwf_applicable') ? 30.00 : 0,

			// Earnings
			'total_addition' => $request->total_addition,
			'basic_sal' => $request->basic_sal,
			'basic_percentage' => $request->basic_percentage,
			'hra' => $request->hra,
			'convayance' => $request->convayance,
			'medical_allowance' => $request->medical_allowance,
			'special_bonus' => $request->special_bonus,

			// Deductions
			'provident_fund' => $request->provident_fund ?? 0,
			'esi' => $request->esi ?? 0,
			'loan' => $request->loan ?? 0,
			'loan_tenure' => $request->loan_tenure ?? 0,
			'loan_deduction' => $request->loan_deduction ?? 0,
			'ptax' => $request->ptax ?? 0,
			'tds' => $request->tds ?? 0,
			'lwf_deduct' => $request->lwf_deduct ?? 0,
			'total_deduction' => $request->total_deduction,
			'net_sal' => $request->net_sal,
			'net_sal_word' => $request->net_sal_word,

			// Bank
			'bank_name' => $request->bank_name,
			'bank_branch' => $request->bank_branch,
			'ifsc' => $request->ifsc,
			'swift_code' => $request->swift_code ?? null,
			'account_holder_name' => $request->account_holder_name,
			'account_number' => $request->account_number,
			'upi_id' => $request->upi_id ?? null,
		]);

		// ✅ Profile Image Upload
		if ($request->hasFile('fileUpload')) {
			if (!empty($employee->profile_img) && Storage::exists('public/user_employee/' . $employee->profile_img)) {
				Storage::delete('public/user_employee/' . $employee->profile_img);
			}

			$file = $request->file('fileUpload');
			$fileName = time() . '.' . $file->getClientOriginalExtension();
			$file->storeAs('public/user_employee', $fileName);
			$employee->profile_img = $fileName;
		}

		// ✅ Document Uploads (single-file each)
		$documentMap = [
			'aadhar_attachment' => 'aadhar_doc',
			'pan_attachment' => 'pan_doc',
			'bank_passbook_attachment' => 'cancelled_cheque_doc',
			'cv_attachment' => 'cv_doc',
			'certificate_attachment' => 'last_qualification_doc',
			'experience_letter' => 'last_company_release_letter',
			'offer_letter' => 'signed_appointment_letter',
			'other_doc' => 'other_doc',
		];

		foreach ($documentMap as $inputName => $dbField) {
			if ($request->hasFile($inputName)) {
				$file = $request->file($inputName);
				$fileName = time() . '_' . $dbField . '.' . $file->getClientOriginalExtension();

				if (!empty($employee->$dbField) && Storage::exists('public/employee_document/' . $employee->$dbField)) {
					Storage::delete('public/employee_document/' . $employee->$dbField);
				}

				$file->storeAs('public/employee_document', $fileName);
				$employee->$dbField = $fileName;
			}
		}

		$employee->save();

		$userType = Auth::user()->u_type;
		$routeLink = ($userType == 2 || $userType == 3 || $userType == 5 || $userType == 6) ? route('user.EmployeeList') : route('CA.EmployeeList');
		
		// ================= NEW DATA SNAPSHOT =================
		$newUser = $user->only([
			'name',
			'phone',
			'state_id',
			'city_id',
			'pincode',
			'emp_permission'
		]);

		$newEmployee = $employee->only([
			'gender',
			'dob',
			'qualification',
			'dept_id',
			'desig_id',
			'location_id',
			'joining_date',
			'emp_status',
			'emp_type',
			'total_addition',
			'basic_sal',
			'net_sal',
			'bank_name',
			'ifsc'
		]);

		$changedOld = [];
		$changedNew = [];
		foreach ($newEmployee as $key => $value) {
			if (
				array_key_exists($key, $oldEmployee) &&
				$oldEmployee[$key] != $value
			) {
				$label = ucwords(str_replace('_', ' ', $key)); // Friendly key

				$changedOld[$label] = $oldEmployee[$key];
				$changedNew[$label] = $value;
			}
		}
		foreach ($newUser as $key => $value) {
			if (
				array_key_exists($key, $oldUser) &&
				$oldUser[$key] != $value
			) {
				$label = ucwords(str_replace('_', ' ', $key));

				$changedOld[$label] = $oldUser[$key];
				$changedNew[$label] = $value;
			}
		}		
		if (!empty($changedNew)) {
			AuditLogger::logEntry(
				action: 'update',
				module: 'Employee',
				description: "Employee updated: {$user->name}",
				oldData: $changedOld,
				newData: $changedNew
			);
		}

		return response()->json([
			'status' => 'success',
			'message' => 'Employee updated successfully',
			'redirect' => $routeLink,
		]);
	}



	public function getEmployeeAttendanceLog(Request $request)
	{
		$employeeId = $request->input('employee_id');
		$year = $request->input('year', Carbon::now()->year);
		$month = $request->input('month', Carbon::now()->month);
		$page = $request->input('page', 1);
		$perPage = 15;
		$authUserId = currentOwnerId();

		// Get employee details
		$employee = DB::table('employees')
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->select('employees.*', 'users.name')
			->where('employees.empId', $employeeId)
			->where('employees.added_by', $authUserId)
			->first();

		if (!$employee) {
			return response()->json(['error' => 'Employee not found'], 404);
		}

		// Get weekly schedule for the authenticated user
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $authUserId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		// Get holidays for the month
		$holidays = DB::table('holidays')
			->where('added_by', $authUserId)
			->whereYear('holidayDate', $year)
			->whereMonth('holidayDate', $month)
			->pluck('holidayDate')
			->toArray();

		// Get approved leaves for the month
		$leaves = DB::table('leaves')
			->where('emp_id', $employeeId)
			->where('status', 'approved')
			->where(function ($query) use ($year, $month) {
				$query->whereYear('start_date', $year)
					->whereMonth('start_date', $month)
					->orWhereYear('end_date', $year)
					->whereMonth('end_date', $month);
			})
			->get();

		// Create leave dates array
		$leaveDates = [];
		foreach ($leaves as $leave) {
			$startDate = Carbon::parse($leave->start_date);
			$endDate = Carbon::parse($leave->end_date);
			$period = CarbonPeriod::create($startDate, $endDate);

			foreach ($period as $date) {
				if ($date->year == $year && $date->month == $month) {
					$leaveDates[$date->toDateString()] = $leave->leave_type;
				}
			}
		}

		// Get all days in the month
		$startOfMonth = Carbon::create($year, $month, 1);
		$endOfMonth = $startOfMonth->copy()->endOfMonth();
		$period = CarbonPeriod::create($startOfMonth, $endOfMonth);

		$attendanceLog = [];

		foreach ($period as $date) {
			$dateString = $date->toDateString();
			$dayName = strtolower($date->format('l'));

			// Check if it's a working day or officially closed
			$daySchedule = $weeklySchedule[$dayName] ?? null;
			$isWorkingDay = $daySchedule && $daySchedule->status === 'open';
			$isOfficiallyClose = $daySchedule && $daySchedule->status === 'closed';

			// Skip days that have no schedule (neither open nor close)
			if (!$daySchedule) {
				continue;
			}

			// Get attendance record for this date
			$attendanceRecord = DB::table('attendance')
				->where('userId', $employeeId)
				->whereDate('present_date', $dateString)
				->first();

			$status = 'Absent';
			$checkIn = '-';
			$checkOut = '-';
			$workingHours = '0h 00m';
			$overtime = '0h 00m';
			$notes = '-';
			$badgeClass = 'bg-danger';

			// Check if it's officially closed day
			if ($isOfficiallyClose) {
				$status = 'Office Off';
				$badgeClass = 'bg-dark';
				$notes = 'Office Off';
			}
			// Check if it's a holiday
			elseif (in_array($dateString, $holidays)) {
				$status = 'Holiday';
				$badgeClass = 'bg-secondary';
				$notes = 'Public Holiday';
			}
			// Check if it's a leave day
			elseif (isset($leaveDates[$dateString])) {
				$status = 'Leave';
				$badgeClass = 'bg-info';
				$notes = ucfirst($leaveDates[$dateString]) . ' Leave';
			}
			// Check attendance record (only for working days)
			elseif ($attendanceRecord && $isWorkingDay) {
				$checkIn = $attendanceRecord->in_time ? Carbon::parse($attendanceRecord->in_time)->format('g:i A') : '-';
				$checkOut = $attendanceRecord->out_time ? Carbon::parse($attendanceRecord->out_time)->format('g:i A') : '-';
				$notes = $attendanceRecord->reason ?: '-';

				// Calculate working hours
				if ($attendanceRecord->in_time && $attendanceRecord->out_time) {
					$inTime = Carbon::parse($attendanceRecord->in_time);
					$outTime = Carbon::parse($attendanceRecord->out_time);
					$totalMinutes = $inTime->diffInMinutes($outTime);
					$hours = intval($totalMinutes / 60);
					$minutes = $totalMinutes % 60;
					$workingHours = $hours . 'h ' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';

					// Calculate overtime (assuming 8 hours standard)
					$standardMinutes = 8 * 60;
					if ($totalMinutes > $standardMinutes) {
						$overtimeMinutes = $totalMinutes - $standardMinutes;
						$overtimeHours = intval($overtimeMinutes / 60);
						$overtimeMin = $overtimeMinutes % 60;
						$overtime = $overtimeHours . 'h ' . str_pad($overtimeMin, 2, '0', STR_PAD_LEFT) . 'm';
					}
				}

				// Determine status based on schedule
				if ($attendanceRecord->present_status === 'present' || $attendanceRecord->present_status === 'working') {
					if ($daySchedule && $attendanceRecord->in_time) {
						$inTime = Carbon::parse($attendanceRecord->in_time);
						$openingTime = Carbon::parse($daySchedule->opening_time);

						if ($inTime->greaterThan($openingTime)) {
							$status = 'Late';
							$badgeClass = 'bg-warning';
						} else {
							$status = 'Present';
							$badgeClass = 'bg-success';
						}
					} else {
						$status = 'Present';
						$badgeClass = 'bg-success';
					}
				}
			}
			// For officially closed days, set default absent status only if it's a working day
			elseif ($isWorkingDay) {
				// Check if this is a future date
				$today = Carbon::now()->toDateString();
				if ($dateString > $today) {
					// For future dates, show as pending/scheduled
					$status = 'Scheduled';
					$badgeClass = 'bg-light text-dark';
					$notes = 'Scheduled Working Day';
				} else {
					// Keep the default 'Absent' status for past working days with no attendance
					$status = 'Absent';
					$badgeClass = 'bg-danger';
					$notes = '-';
				}
			}

			$attendanceLog[] = [
				'date' => $date->format('M d, Y'),
				'status' => $status,
				'badge_class' => $badgeClass,
				'check_in' => $checkIn,
				'check_out' => $checkOut,
				'working_hours' => $workingHours,
				'overtime' => $overtime,
				'notes' => $notes
			];
		}

		// Keep in ascending order (oldest first)
		// $attendanceLog is already in ascending order from the date loop

		// Paginate results
		$total = count($attendanceLog);
		$offset = ($page - 1) * $perPage;
		$paginatedLog = array_slice($attendanceLog, $offset, $perPage);

		return response()->json([
			'success' => true,
			'data' => $paginatedLog,
			'pagination' => [
				'current_page' => $page,
				'per_page' => $perPage,
				'total' => $total,
				'last_page' => ceil($total / $perPage),
				'has_more' => $page < ceil($total / $perPage)
			],
			'employee' => [
				'name' => $employee->name,
				'employee_id' => $employee->employee_id
			]
		]);
	}

	public function exportEmployeeAttendance(Request $request)
	{	
		$employeeId = $request->input('employee_id');
		$year = $request->input('year', Carbon::now()->year);
		$month = $request->input('month', Carbon::now()->month);
		$authUserId = Auth::user()->id;

		// Get employee details
		$employee = DB::table('employees')
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->select(
				'employees.*',
				'users.name',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->where('employees.empId', $employeeId)
			->where('employees.added_by', $authUserId)
			->first();

		if (!$employee) {
			return redirect()->back()->with('error', 'Employee not found');
		}

		// Get attendance data (reuse the logic from getEmployeeAttendanceLog)
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $authUserId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		$holidays = DB::table('holidays')
			->where('added_by', $authUserId)
			->whereYear('holidayDate', $year)
			->whereMonth('holidayDate', $month)
			->pluck('holidayDate')
			->toArray();

		$leaves = DB::table('leaves')
			->where('emp_id', $employeeId)
			->where('status', 'approved')
			->where(function ($query) use ($year, $month) {
				$query->whereYear('start_date', $year)
					->whereMonth('start_date', $month)
					->orWhereYear('end_date', $year)
					->whereMonth('end_date', $month);
			})
			->get();

		$leaveDates = [];
		foreach ($leaves as $leave) {
			$startDate = Carbon::parse($leave->start_date);
			$endDate = Carbon::parse($leave->end_date);
			$period = CarbonPeriod::create($startDate, $endDate);

			foreach ($period as $date) {
				if ($date->year == $year && $date->month == $month) {
					$leaveDates[$date->toDateString()] = $leave->leave_type;
				}
			}
		}

		$startOfMonth = Carbon::create($year, $month, 1);
		$endOfMonth = $startOfMonth->copy()->endOfMonth();
		$period = CarbonPeriod::create($startOfMonth, $endOfMonth);

		$attendanceData = [];
		$summary = [
			'present' => 0,
			'late' => 0,
			'absent' => 0,
			'leave' => 0,
			'holiday' => 0,
			'office_off' => 0,
			'scheduled' => 0
		];

		foreach ($period as $date) {
			$dateString = $date->toDateString();
			$dayName = strtolower($date->format('l'));

			// Check if it's a working day or officially closed
			$daySchedule = $weeklySchedule[$dayName] ?? null;
			$isWorkingDay = $daySchedule && $daySchedule->status === 'open';
			$isOfficiallyClose = $daySchedule && $daySchedule->status === 'closed';

			// Skip days that have no schedule (neither open nor closed)
			if (!$daySchedule) {
				continue;
			}

			$attendanceRecord = DB::table('attendance')
				->where('userId', $employeeId)
				->whereDate('present_date', $dateString)
				->first();

			$status = 'Absent';
			$checkIn = '-';
			$checkOut = '-';
			$workingHours = '0h 00m';
			$notes = '-';

			// Check if it's officially closed day
			if ($isOfficiallyClose) {
				$status = 'Office Off';
				$notes = 'Office Off';
				$summary['office_off']++;
			} elseif (in_array($dateString, $holidays)) {
				$status = 'Holiday';
				$notes = 'Public Holiday';
				$summary['holiday']++;
			} elseif (isset($leaveDates[$dateString])) {
				$status = 'Leave';
				$notes = ucfirst($leaveDates[$dateString]) . ' Leave';
				$summary['leave']++;
			} elseif ($attendanceRecord && $isWorkingDay) {
				$checkIn = $attendanceRecord->in_time ? Carbon::parse($attendanceRecord->in_time)->format('g:i A') : '-';
				$checkOut = $attendanceRecord->out_time ? Carbon::parse($attendanceRecord->out_time)->format('g:i A') : '-';
				$notes = $attendanceRecord->reason ?: '-';

				if ($attendanceRecord->in_time && $attendanceRecord->out_time) {
					$inTime = Carbon::parse($attendanceRecord->in_time);
					$outTime = Carbon::parse($attendanceRecord->out_time);
					$totalMinutes = $inTime->diffInMinutes($outTime);
					$hours = intval($totalMinutes / 60);
					$minutes = $totalMinutes % 60;
					$workingHours = $hours . 'h ' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';
				}

				if ($attendanceRecord->present_status === 'present' || $attendanceRecord->present_status === 'working') {
					if ($daySchedule && $attendanceRecord->in_time) {
						$inTime = Carbon::parse($attendanceRecord->in_time);
						$openingTime = Carbon::parse($daySchedule->opening_time);

						if ($inTime->greaterThan($openingTime)) {
							$status = 'Late';
							$summary['late']++;
						} else {
							$status = 'Present';
							$summary['present']++;
						}
					} else {
						$status = 'Present';
						$summary['present']++;
					}
				}
			} elseif ($isWorkingDay) {
				$today = Carbon::now()->toDateString();
				if ($dateString > $today) {
					// For future dates, show as scheduled
					$status = 'Scheduled';
					$notes = 'Scheduled Working Day';
					$summary['scheduled'] = ($summary['scheduled'] ?? 0) + 1;
				} else {
					$status = 'Absent';
					$summary['absent']++;
				}
			}

			$attendanceData[] = [
				'date' => $date->format('M d, Y'),
				'day' => $date->format('D'),
				'status' => $status,
				'check_in' => $checkIn,
				'check_out' => $checkOut,
				'working_hours' => $workingHours,
				'notes' => $notes
			];
		}

		// Prepare data for Excel
		$excelData = [];

		// Add employee info
		$excelData[] = ['Employee Attendance Report'];
		$excelData[] = ['Employee Name:', $employee->name];
		$excelData[] = ['Employee ID:', $employee->empId];
		$excelData[] = ['Department:', $employee->dept_name ?? 'N/A'];
		$excelData[] = ['Designation:', $employee->designation_name ?? 'N/A'];
		$excelData[] = ['Period:', Carbon::create($year, $month)->format('F Y')];
		$excelData[] = [''];

		// Add summary
		$excelData[] = ['Monthly Summary'];
		$excelData[] = ['Present Days', 'Late Days', 'Absent Days', 'Leave Days', 'Holidays', 'Office Off', 'Scheduled'];
		$excelData[] = [
			$summary['present'],
			$summary['late'],
			$summary['absent'],
			$summary['leave'],
			$summary['holiday'],
			$summary['office_off'],
			$summary['scheduled']
		];
		$excelData[] = [''];

		// Add attendance data header
		$excelData[] = ['Date', 'Day', 'Status', 'Check In', 'Check Out', 'Working Hours', 'Notes'];

		// Add attendance records
		foreach ($attendanceData as $record) {
			$excelData[] = [
				$record['date'],
				$record['day'],
				$record['status'],
				$record['check_in'],
				$record['check_out'],
				$record['working_hours'],
				$record['notes']
			];
		}

		$filename = 'attendance_' . $employee->name . '_' . Carbon::create($year, $month)->format('M_Y') . '.xlsx';

		// return Excel::download(collect($excelData), $filename);
		return Excel::download(new class($excelData) implements \Maatwebsite\Excel\Concerns\FromArray {
			private $data;

			public function __construct($data)
			{
				$this->data = $data;
			}

			public function array(): array
			{
				return $this->data;
			}
		}, $filename);
	}

	public function exportEmployeeAttendancePDF(Request $request)
	{	
		$employeeId = $request->input('employee_id');
		$year = $request->input('year', Carbon::now()->year);
		$month = $request->input('month', Carbon::now()->month);
		$authUserId = Auth::user()->id;

		// Get employee details
		$employee = DB::table('employees')
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
			->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
			->select(
				'employees.*',
				'users.name',
				'depertments.dept_name',
				'designations.designation_name'
			)
			->where('employees.empId', $employeeId)
			->where('employees.added_by', $authUserId)
			->first();

		if (!$employee) {
			return redirect()->back()->with('error', 'Employee not found');
		}

		// Get attendance data (reuse the logic from getEmployeeAttendanceLog)
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $authUserId)
			->get()
			->keyBy(function ($item) {
				return strtolower($item->day);
			});

		$holidays = DB::table('holidays')
			->where('added_by', $authUserId)
			->whereYear('holidayDate', $year)
			->whereMonth('holidayDate', $month)
			->pluck('holidayDate')
			->toArray();

		$leaves = DB::table('leaves')
			->where('emp_id', $employeeId)
			->where('status', 'approved')
			->where(function ($query) use ($year, $month) {
				$query->whereYear('start_date', $year)
					->whereMonth('start_date', $month)
					->orWhereYear('end_date', $year)
					->whereMonth('end_date', $month);
			})
			->get();

		$leaveDates = [];
		foreach ($leaves as $leave) {
			$startDate = Carbon::parse($leave->start_date);
			$endDate = Carbon::parse($leave->end_date);
			$period = CarbonPeriod::create($startDate, $endDate);

			foreach ($period as $date) {
				if ($date->year == $year && $date->month == $month) {
					$leaveDates[$date->toDateString()] = $leave->leave_type;
				}
			}
		}

		$startOfMonth = Carbon::create($year, $month, 1);
		$endOfMonth = $startOfMonth->copy()->endOfMonth();
		$period = CarbonPeriod::create($startOfMonth, $endOfMonth);

		$attendanceData = [];
		$summary = [
			'present' => 0,
			'late' => 0,
			'absent' => 0,
			'leave' => 0,
			'holiday' => 0,
			'office_off' => 0,
			'scheduled' => 0
		];

		foreach ($period as $date) {
			$dateString = $date->toDateString();
			$dayName = strtolower($date->format('l'));

			// Check if it's a working day or officially closed
			$daySchedule = $weeklySchedule[$dayName] ?? null;
			$isWorkingDay = $daySchedule && $daySchedule->status === 'open';
			$isOfficiallyClose = $daySchedule && $daySchedule->status === 'closed';

			// Skip days that have no schedule (neither open nor closed)
			if (!$daySchedule) {
				continue;
			}

			$attendanceRecord = DB::table('attendance')
				->where('userId', $employeeId)
				->whereDate('present_date', $dateString)
				->first();

			$status = 'Absent';
			$checkIn = '-';
			$checkOut = '-';
			$workingHours = '0h 00m';
			$notes = '-';

			// Check if it's officially closed day
			if ($isOfficiallyClose) {
				$status = 'Office Off';
				$notes = 'Office Off';
				$summary['office_off']++;
			} elseif (in_array($dateString, $holidays)) {
				$status = 'Holiday';
				$notes = 'Public Holiday';
				$summary['holiday']++;
			} elseif (isset($leaveDates[$dateString])) {
				$status = 'Leave';
				$notes = ucfirst($leaveDates[$dateString]) . ' Leave';
				$summary['leave']++;
			} elseif ($attendanceRecord && $isWorkingDay) {
				$checkIn = $attendanceRecord->in_time ? Carbon::parse($attendanceRecord->in_time)->format('g:i A') : '-';
				$checkOut = $attendanceRecord->out_time ? Carbon::parse($attendanceRecord->out_time)->format('g:i A') : '-';
				$notes = $attendanceRecord->reason ?: '-';

				if ($attendanceRecord->in_time && $attendanceRecord->out_time) {
					$inTime = Carbon::parse($attendanceRecord->in_time);
					$outTime = Carbon::parse($attendanceRecord->out_time);
					$totalMinutes = $inTime->diffInMinutes($outTime);
					$hours = intval($totalMinutes / 60);
					$minutes = $totalMinutes % 60;
					$workingHours = $hours . 'h ' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . 'm';
				}

				if ($attendanceRecord->present_status === 'present' || $attendanceRecord->present_status === 'working') {
					if ($daySchedule && $attendanceRecord->in_time) {
						$inTime = Carbon::parse($attendanceRecord->in_time);
						$openingTime = Carbon::parse($daySchedule->opening_time);

						if ($inTime->greaterThan($openingTime)) {
							$status = 'Late';
							$summary['late']++;
						} else {
							$status = 'Present';
							$summary['present']++;
						}
					} else {
						$status = 'Present';
						$summary['present']++;
					}
				}
			} elseif ($isWorkingDay) {
				$today = Carbon::now()->toDateString();
				if ($dateString > $today) {
					// For future dates, show as scheduled
					$status = 'Scheduled';
					$notes = 'Scheduled Working Day';
					$summary['scheduled'] = ($summary['scheduled'] ?? 0) + 1;
				} else {
					$status = 'Absent';
					$summary['absent']++;
				}
			}

			$attendanceData[] = [
				'date' => $date->format('M d, Y'),
				'day' => $date->format('D'),
				'status' => $status,
				'check_in' => $checkIn,
				'check_out' => $checkOut,
				'working_hours' => $workingHours,
				'notes' => $notes
			];
		}

		$data = [
			'employee' => $employee,
			'attendanceData' => $attendanceData,
			'summary' => $summary,
			'period' => Carbon::create($year, $month)->format('F Y'),
			'year' => $year,
			'month' => $month
		];

		$pdf = PDF::loadView('User.attendance-pdf', $data);
		$filename = 'attendance_' . $employee->name . '_' . Carbon::create($year, $month)->format('M_Y') . '.pdf';

		return $pdf->download($filename);
	}

	public function getMonthlyAttendance(Request $request)
	{
		$year = $request->input('year', Carbon::now()->year);
		$month = $request->input('month', Carbon::now()->month);
		$userId = $request->input('user_id'); // Fetch user_id from request
		$authUserId = Auth::user()->id; // Get authenticated user ID

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


	public function updateAttendance(Request $request)
	{
		$userId = $request->input('user_id');
		$presentDate = $request->input('present_date');
		$inTime = $request->input('in_time');
		$outTime = $request->input('out_time');
		$present_status = $request->input('present_status');
		$reason = $request->input('reason');

		// Validate that reason is provided when any time is entered (in-time or out-time)
		if (($inTime || $outTime) && empty(trim($reason))) {
			return response()->json([
				'message' => 'Reason is required when updating attendance with in-time or out-time',
				'error' => 'validation_failed'
			], 422);
		}

		$date = Carbon::parse($presentDate);
		$month = $date->month;
		$year = $date->year;

		// Determine present_status based on time entries
		$finalPresentStatus = $present_status;
		if (!empty($inTime) && empty($outTime)) {
			$finalPresentStatus = 'working';
		} elseif (!empty($inTime) && !empty($outTime)) {
			$finalPresentStatus = 'present';
		} elseif (empty($inTime) && !empty($outTime)) {
			// If only out-time is provided, set as present (assuming they were working)
			$finalPresentStatus = 'present';
		} elseif (empty($inTime) && empty($outTime) && !empty($present_status)) {
			$finalPresentStatus = $present_status;
		} else {
			$finalPresentStatus = 'present';
		}

		$attendance = DB::table('attendance')
			->where('userId', $userId)
			->whereDate('present_date', $presentDate)
			->first();

		$updateData = [
			'in_time' => $inTime,
			'out_time' => $outTime,
			'present_status' => $finalPresentStatus,
			'reason' => $reason,
			'updated_at' => now(),
		];

		if ($attendance) {
			DB::table('attendance')->where('id', $attendance->id)->update($updateData);
			return response()->json([
				'message' => 'Attendance updated successfully',
				'month' => $month,
				'year' => $year,
				'status' => $finalPresentStatus
			], 200);
		} else {
			$insertData = array_merge($updateData, [
				'userId' => $userId,
				'present_date' => $presentDate,
				'status' => '1',
				'created_at' => now(),
			]);

			DB::table('attendance')->insert($insertData);
			return response()->json([
				'message' => 'Attendance recorded successfully',
				'month' => $month,
				'year' => $year,
				'status' => $finalPresentStatus
			], 201);
		}
	}



	public function checkPayslip(Request $request)
	{
		$employeeId = $request->employee_id;
		$financialYear = $request->select_financial_year;
		$monthName = trim($request->monthSelect, "'");

		//$month = (int) date('m', strtotime($monthName));
		$month = \Carbon\Carbon::parse("1 $monthName")->month;
		$financialYears = explode("-", $financialYear);
		$requestedYear = ($month >= 4) ? $financialYears[0] : $financialYears[1];

		$payslip = DB::table('user_payslip')
			->where('user_emp_id', $employeeId)
			->where('financial_year', $financialYear)
			->where('month', $month)
			->first();

		if ($payslip) {
			return response()->json(['status' => 'exists', 'payslipId' => $payslip->id]);
		}

		$payslipNo = DB::table('employees')
			->selectRaw("CONCAT('PS/', employee_id, '/', DATE_FORMAT(STR_TO_DATE(CONCAT('01-', ?, '-'), '%d-%M-%Y'), '%d%m%Y')) AS payslip_no", [$monthName . '-' . $financialYear])
			->where('empId', '=', $employeeId)
			->first();

		$employee = DB::table('employees as e')
			->leftJoin('users as u', 'u.id', '=', 'e.empId')
			->leftJoin('depertments as d', 'd.id', '=', 'e.dept_id')
			->leftJoin('designations as des', 'des.id', '=', 'e.desig_id')
			->select(
				'u.name as employee_name',
				'u.email as user_email',
				'u.phone as user_phone',
				'e.employee_id',
				'e.joining_date',
				'e.epf_no',
				'e.dept_id',
				'e.desig_id',
				'd.dept_name',
				'des.designation_name',
				'e.epf_applicable',
				'e.esic_applicable',
				'e.ptax_applicable',
				'e.tds_applicable',
				'e.basic_sal',
				'e.hra',
				'e.convayance',
				'e.medical_allowance',
				'e.special_bonus',
				'e.provident_fund',
				'e.esi',
				'e.loan',
				'e.loan_tenure',
				'e.loan_deduction',
				'e.ptax',
				'e.tds',
				// LWF
				'e.lwf_applicable',
				'e.lwf_deduct',
				'e.lwf_company_contribution',

				'e.bank_name',
				'e.bank_branch',
				'e.ifsc',
				'e.account_holder_name',
				'e.account_number',
				'e.net_sal',
				'e.net_sal_word',
				'e.total_addition',
				'e.total_deduction',
				'e.pan_number',
				'e.aadhaar_number',
			)
			->where('e.empId', $employeeId)
			->first();

		if (!$employee) return response()->json(['status' => 'error', 'message' => 'Employee not found.'], 404);

		$userId = currentOwnerId();
		$firstDay = Carbon::createFromDate($requestedYear, $month, 1)->startOfMonth();
		$lastDay = (clone $firstDay)->endOfMonth();

		// Weekly schedule
		$weeklySchedule = DB::table('weekly_schedules')
			->where('added_by', $userId)
			->pluck('status', 'day')
			->mapWithKeys(fn($v, $k) => [strtolower(trim($k)) => $v])
			->toArray();

		// Holidays
		$holidays = DB::table('holidays')
			->where('added_by', $userId)
			->whereMonth('holidayDate', $month)
			->whereYear('holidayDate', $requestedYear)
			->pluck('holidayDate')
			->toArray();

		$totalWeekends = $totalWorkingDays = 0;

		$period = new DatePeriod($firstDay, new DateInterval('P1D'), $lastDay->copy()->addDay());
		foreach ($period as $day) {
			$dayName = strtolower($day->format('l'));
			$dateStr = $day->format('Y-m-d');

			$isWeekend = isset($weeklySchedule[$dayName]) && strtolower($weeklySchedule[$dayName]) === 'closed';
			$isHoliday = in_array($dateStr, $holidays);

			if ($isWeekend) $totalWeekends++;
			if (!$isWeekend && !$isHoliday) $totalWorkingDays++;
		}

		// Keep original behaviour (if you really need to exclude 1 day, keep this; otherwise remove)
		$totalWorkingDays = max($totalWorkingDays - 1, 0); // exclude 1 day for calculation as before

		// Attendance
		$attendanceRecords = DB::table('attendance')
			->where('userId', $employeeId)
			->whereYear('present_date', $requestedYear)
			->whereMonth('present_date', $month)
			->get();

		// New detailed counters
		$totalPresent = $totalPresentOnTime = $totalPresentLate = $totalEarlyLogout = 0;
		$totalLate = 0; // late login count (for late-deduction rule)
		$totalOvertime = 0.0;
		$totalOvertimeFormatted = '00:00:00';

		foreach ($attendanceRecords as $record) {
			$dayName = strtolower(Carbon::parse($record->present_date)->format('l'));
			$dateStr = Carbon::parse($record->present_date)->format('Y-m-d');

			$isWeekend = isset($weeklySchedule[$dayName]) && strtolower($weeklySchedule[$dayName]) === 'closed';
			$isHoliday = in_array($dateStr, $holidays);

			// Count weekend/holiday attendance separately but continue processing
			if ($isWeekend) {
				// if you'd like to count weekend attendances as present, you can increment here
				continue; // skip weekends for normal present/late/on-time calculation
			}

			if ($isHoliday) {
				// skip holidays from present calculation
				continue;
			}

			// It's a normal working day
			$totalPresent++;

			// fetch schedule for that day to check timings
			$schedule = DB::table('weekly_schedules')->where('added_by', $userId)->where('day', $dayName)->first();

			if ($schedule && strtolower($schedule->status) === 'open') {
				// On-time vs late login
				if (!empty($schedule->opening_time) && !empty($record->in_time)) {
					$scheduledStart = Carbon::parse($schedule->opening_time);
					$actualLogin = Carbon::parse($record->in_time);
					$graceTime = $scheduledStart->copy()->addMinutes(5);
					if ($actualLogin->lte($graceTime)) {
						$totalPresentOnTime++;
					} else {
						$totalPresentLate++;
						$totalLate++; // contributes to late deduction rule
					}
				}

				// Early logout check
				if (!empty($schedule->closing_time) && !empty($record->out_time)) {
					$scheduledEnd = Carbon::parse($schedule->closing_time);
					$actualLogout = Carbon::parse($record->out_time);
					if ($actualLogout->lt($scheduledEnd)) {
						$totalEarlyLogout++;
					}
				}
			}

			// accumulate overtime hours if any (assuming total_working_hours contains overtime portion)
			// $totalOvertime += (float)$record->total_working_hours;
			// Overtime calculation (HH:MM:SS)
			if (!empty($schedule->closing_time) && !empty($record->out_time)) {
				$scheduledEnd = Carbon::parse($schedule->closing_time);
				$actualLogout = Carbon::parse($record->out_time);

				if ($actualLogout->gt($scheduledEnd)) {
					$diffInSeconds = $actualLogout->diffInSeconds($scheduledEnd);
					$totalOvertime += $diffInSeconds;
				}
			}
			$hours = floor($totalOvertime / 3600);
			$minutes = floor(($totalOvertime % 3600) / 60);
			$seconds = $totalOvertime % 60;
			$totalOvertimeFormatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
		}

		// Leaves
		$leaves = DB::table('leaves')->where('emp_id', $employeeId)->where('status', 'approved')
			->where(function ($q) use ($month, $requestedYear) {
				$q->whereMonth('start_date', $month)->whereYear('start_date', $requestedYear)
					->orWhereMonth('end_date', $month)->whereYear('end_date', $requestedYear);
			})->get();

		$totalLeave = $leaves->sum('total_days'); // approved leaves count

		// Absent calculation remains similar: working days minus (present + leaves)
		$totalAbsent = max($totalWorkingDays - ($totalPresent + $totalLeave), 0);

		// PF number
		$pfNo = $employee->epf_applicable ? $employee->epf_no : null;

		// LWF Applicable check
		$lwfDeduct = 0;
		$lwfCompanyContribution = 0;

		if ($employee->lwf_applicable == 1) {
			$lwfDeduct = $employee->lwf_deduct;
			$lwfCompanyContribution = $employee->lwf_company_contribution;
		}

		// ----- Dynamic Salary Calculation (unchanged) -----
		$perDaySalary = round($employee->total_addition / 30, 2);

		// Deduct 1 day for every 3 late days
		$lateDeduct = intdiv($totalLate, 3);
		$totalEarlyLogoutDeduct = intdiv($totalEarlyLogout, 3);

		$totalAbsentForSalary = $totalAbsent + $lateDeduct;

		$totalDayInMonth = $firstDay->daysInMonth;
		$payableDays = max($totalDayInMonth - $totalAbsentForSalary, 0);

		$monthlySalary = $employee->total_addition - ($perDaySalary * $totalAbsentForSalary);

		$totalAddition = $employee->basic_sal + $employee->hra + $employee->convayance + $employee->medical_allowance + $employee->special_bonus;

		$totalDeduction = $employee->provident_fund + $employee->esi + $employee->ptax + $employee->tds + $employee->loan;

		$netSalary = $monthlySalary - $totalDeduction;

		// Convert number to words (simple fallback)
		$salaryInWords = $employee->net_sal_word ?? '-';

		// Advance Amount (from expenses table)
		$advanceAmount = DB::table('expenses')
			->where('employee_id', $employeeId)
			->whereYear('expense_date', $requestedYear)
			->whereMonth('expense_date', $month)
			->sum('expense_amt');

		return response()->json([
			'status' => 'new',
			'payslipNo' => $payslipNo,
			'employee' => [
				'empId' => $employeeId,
				'name' => $employee->employee_name,
				'email' => $employee->user_email,
				'phone' => $employee->user_phone,
				'employee_id' => $employee->employee_id,
				'joining_date' => $employee->joining_date,
				'dept_name' => $employee->dept_name,
				'designation_name' => $employee->designation_name,
				'epf_no' => $pfNo,
				'bank_name' => $employee->bank_name,
				'bank_branch' => $employee->bank_branch,
				'ifsc' => $employee->ifsc,
				'account_holder_name' => $employee->account_holder_name,
				'account_number' => $employee->account_number,
				'pan_number' => $employee->pan_number,
				'aadhaar_number' => $employee->aadhaar_number,
			],
			'salaryDetails' => [
				'gross_salary' => $employee->total_addition,
				'base_salary' => $employee->basic_sal,
				'hra' => $employee->hra,
				'conveyance' => $employee->convayance,
				'medical_allowance' => $employee->medical_allowance,
				'special_bonus' => $employee->special_bonus,
				'total_addition' => $totalAddition,
				'provident_fund' => $employee->provident_fund,
				'esi' => $employee->esi,
				'ptax' => $employee->ptax,
				'tds' => $employee->tds,
				//--- LWF Details
				'lwf_applicable' => $employee->lwf_applicable,
				'lwf_deduct' => $lwfDeduct,
				'lwf_company_contribution' => $lwfCompanyContribution,
				
				'loan' => $employee->loan,
				'total_absent_days_for_salary' => $totalAbsentForSalary,
				'lateDeductionDays' => $lateDeduct,
				'per_day_salary' => $perDaySalary,

				'advance_amount' => $advanceAmount,
				
				// 'payable_days' => $payableDays,
				// 'monthly_salary' => $monthlySalary - $employee->special_bonus,
				// 'net_salary' => $netSalary,
			],
			'monthDetails' => [
				'total_days' => $firstDay->daysInMonth,
				'total_working_days' => $totalWorkingDays,
				'total_holidays' => count($holidays),
				'total_weekends' => $totalWeekends,
			],
			'attendanceDetails' => [
				'total_present' => $totalPresent,
				'total_present_on_time' => $totalPresentOnTime,
				'total_present_late' => $totalPresentLate,
				'total_early_logout' => $totalEarlyLogout,
				'total_absent' => $totalAbsent,
				'total_leave_approved' => $totalLeave,
				'total_holiday' => count($holidays),
				'total_office_weekend' => $totalWeekends,
				'total_overtime_hours' => $totalOvertimeFormatted,
				'totalEarlyLogoutDeductionDays' => $totalEarlyLogoutDeduct,
			],
			'financialYear' => $financialYear,
			'month' => $month,
			'month_name' => Carbon::create()->month($month)->format('F')
		]);
	}

	public function savePayslip(Request $request)
	{
		$uid = currentOwnerId();
		try {
			// Decode all data
			$empResponse = json_decode($request->emp_response, true);
			$finalSalary = json_decode($request->final_salary_json, true);

			// Combine into one clean structure for storage
			$payslipData = [
				'payslip_no' => $request->payslip_no,
				'employee_id' => $request->employee_id,
				'financial_year' => $request->financial_year,
				'month' => $request->month,
				'generate_date' => $request->generate_date,
				'notes' => $request->notes,
				'visible_data' => $finalSalary,        // All UI-visible data
				'raw_api_response' => $empResponse,    // Original API call response
				'created_by' => Auth::user()->id,
				'created_at' => now()->toISOString()
			];

			// Store JSON in DB
			$jsonToStore = json_encode($payslipData, JSON_PRETTY_PRINT);

			$payslipId = DB::table('user_payslip')->insertGetId([
				'payslip_no' => $request->payslip_no,
				'user_emp_id' => $request->employee_id,
				'financial_year' => $request->financial_year,
				'month' => $request->month,
				'payslip_text' => $request->notes ?? '',
				'date' => $request->generate_date,
				'emp_salary_slip_response' => $jsonToStore,
				'added_by' => $uid,
				'created_at' => now(),
				'updated_at' => now(),
			]);
			
			
			$this->journalEntry($payslipId,$uid);


			return response()->json([
				'success' => true,
				'message' => 'Payslip saved successfully with all details!',
				'payslipId' => $payslipId
			]);
		} catch (\Exception $e) {
			\Log::error('Payslip save error', ['error' => $e->getMessage()]);
			return response()->json([
				'success' => false,
				'message' => 'Error saving payslip.',
				'error' => $e->getMessage()
			], 500);
		}
	}
	
	public function journalEntry($payslipId,$uid)
	{
		$payslip = DB::table('user_payslip as up')
						->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
						->select(
							'up.*',
							'e.propId'
						)
						->where('up.id', $payslipId)
						->first();

		if (!$payslip) {
			return;
		}

		$json = json_decode($payslip->emp_salary_slip_response, true);

		$visibleData = $json['visible_data'] ?? [];
		$employeeName = $visibleData['employee_details']['name'] ?? '';
		$salaryData = $visibleData['final_salary_calculation'] ?? [];
		$grossSalary = $salaryData['total_earnings'] ?? 0;
		$netSalary = $salaryData['net_salary'] ?? 0;
		$monthName = \Carbon\Carbon::create()->month($payslip->month)->format('F'); // June, July, August

		$pf = $salaryData['provident_fund'] ?? 0;
		$esi = $salaryData['esi']?? 0;
		$ptax = $salaryData['ptax']?? 0;
		$tds = $salaryData['tds'] ?? 0;
		$loan = $salaryData['loan'] ?? 0;

		$this->journalService->storePayrollJournalEntries([
			'added_by'      => $uid,
			'autoId'        => $payslipId,
			'propId'        => $payslip->propId ?? null,
			'source'        => 'Payroll',
			'entry_type'    => 'Payroll',
			'date'          => $payslip->date,
			'reference_no'  => $payslip->payslip_no,
			'employee_name' => $employeeName,
			'gross_salary'  => $grossSalary,
			'net_salary'  	=> $netSalary,
			'pf'            => $pf,
			'esi'           => $esi,
			'ptax'          => $ptax,
			'tds'          	=> $tds,
			'loan'          => $loan,
			'payroll_month' => $monthName
		]);
	}



// 	public function downloadPayslip($id)
// 	{
// 		$UserId = Auth::user()->id;
// 		$UserType = Auth::user()->u_type;

// 		// Fetch the payslip data from 'user_payslip' table
// 		$payslip = DB::table('user_payslip')
// 			->where('id', $id)
// 			->select('emp_salary_slip_response')
// 			->first();

// 		// Fetch the user's company profile info
// 		if ($UserType == 2) {
// 			$company_data = DB::table('company_profiles')
// 				->where('userId', $UserId)
// 				->first();

// 			// If company data is not available, redirect to company profile page
// 			if (!$company_data) {
// 				return redirect()->route('user.CompanyProfile');
// 			}
// 		} else if ($UserType == 1) {
// 			$company_data = DB::table('ca_profiles')
// 				->where('userId', $UserId)
// 				->first();

// 			// If company data is not available, redirect to company profile page
// 			if (!$company_data) {
// 				return redirect()->route('CA.FirmInformation');
// 			}
// 		} else if ($UserType == 3) {
// 			$company_data = 'Admin';
// 			// $company_data = DB::table('ca_profiles')
// 			// 	->where('userId', $UserId)
// 			// 	->first();

// 			// // If company data is not available, redirect to company profile page
// 			// if (!$company_data) {
// 			// 	return redirect()->route('CA.FirmInformation');
// 			// }
// 		}


// 		// Check if payslip exists
// 		if (!$payslip) {
// 			return abort(404, 'Payslip not found');
// 		}

// 		// Decode the stored JSON response (assuming it's stored as JSON)
// 		$salaryData = json_decode($payslip->emp_salary_slip_response, true);



// 		// Load the payslip view and pass both salary and company data
// 		$pdf = PDF::loadView('User.payslip-pdf', compact('salaryData', 'company_data'))
// 			->setOptions([
// 				'dpi' => 150,
// 				'defaultFont' => 'sans-serif',
// 				'isHtml5ParserEnabled' => true,
// 				'isRemoteEnabled' => true,
// 			]);

// 		$pdfName = 'Payslip-' . $id . '.pdf';
// 		return $pdf->stream($pdfName);
// 	}

	public function downloadPayslip($id)
	{
		$UserId = Auth::user()->id;
		$UserType = Auth::user()->u_type;

		// Fetch the payslip data from 'user_payslip' table
		$payslip = DB::table('user_payslip')
			->where('id', $id)
			->select('emp_salary_slip_response', 'user_emp_id')
			->first();

		$company_data = null;
		// Fetch the user's company profile info
		if ($UserType == 2) {
			/*$company_data = DB::table('company_profiles')
				->where('userId', $UserId)
				->first();*/
			$company_data = DB::table('employees as e')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'e.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'e.propId')
						->select(
							'cp.*',
							DB::raw("
								CASE
									WHEN e.propId IS NOT NULL AND e.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->where('e.empId', $payslip->user_emp_id)
						->first();

			// If company data is not available, redirect to company profile page
			if (!$company_data) {
				return redirect()->route('user.CompanyProfile');
			}
		} else if ($UserType == 1) {
			$company_data = DB::table('ca_profiles')
				->where('userId', $UserId)
				->first();

			// If company data is not available, redirect to company profile page
			if (!$company_data) {
				return redirect()->route('CA.FirmInformation');
			}
		} else if ($UserType == 3) {
			//$company_data = 'Admin';
			$company_data = DB::table('admin_profiles')
				->where('userId', $UserId)
				->first();

			// If company data is not available, redirect to company profile page
			if (!$company_data) {
				return redirect()->route('admin.AdminProfile');
			}
		}else if($UserType == 5){
			$employee = DB::table('employees')
				->where('empId', $payslip->user_emp_id)
				->select('added_by')
				->first();

			if (!$employee) {
				return abort(404, 'Employee not found');
			}
			
			/*$company_data = DB::table('company_profiles')
				->where('userId', $employee->added_by)
				->first();*/
			$company_data = DB::table('employees as e')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'e.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'e.propId')
						->select(
							'cp.*',
							DB::raw("
								CASE
									WHEN e.propId IS NOT NULL AND e.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->where('e.empId', $payslip->user_emp_id)
						->first();

			if (!$company_data) {
				return abort(404, 'Company profile not found for this employee');
			}
		}else if($UserType == 6){
			$employee = DB::table('employees')
				->where('empId', $payslip->user_emp_id)
				->select('added_by')
				->first();

			if (!$employee) {
				return abort(404, 'Employee not found');
			}
			
			$company_data = DB::table('admin_profiles')
				->where('userId', $employee->added_by)
				->first();

			if (!$company_data) {
				return abort(404, 'Company profile not found for this employee');
			}
		}


		// Check if payslip exists
		if (!$payslip) {
			return abort(404, 'Payslip not found');
		}

		// Decode the stored JSON response (assuming it's stored as JSON)
		$salaryData = json_decode($payslip->emp_salary_slip_response, true);



		// Load the payslip view and pass both salary and company data
		$pdf = PDF::loadView('User.payslip-pdf', compact('salaryData', 'company_data'))
			->setOptions([
				'dpi' => 150,
				'defaultFont' => 'sans-serif',
				'isHtml5ParserEnabled' => true,
				'isRemoteEnabled' => true,
			]);

		$pdfName = 'Payslip-' . $id . '.pdf';
		return $pdf->stream($pdfName);
	}

	public function updateResignation(Request $request)
	{
		$request->validate([
			'regdate' => 'required|date',
			'reg_documet' => 'required|mimes:pdf,doc,docx,jpg,png|max:2048',
			'empId' => 'required'
		]);

		DB::beginTransaction();

		try {
			$employee = Employees::where('empId', $request->empId)->firstOrFail();

			if ($request->hasFile('reg_documet')) {
				$file = $request->file('reg_documet');
				$fileName = 'resignation_' . $employee->empId . '.' . $file->getClientOriginalExtension();
				$file->storeAs('public/resignation_file', $fileName);

				// Update employee table
				$employee->regine_date = $request->regdate;
				$employee->regine_document = $fileName;
				$employee->save();

				// 🔥 Call separate function
				$this->moveUserToResigned($employee->empId, $request->regdate);

				DB::commit();

				return response()->json([
					'class' => 'succ',
					'message' => 'Employee resigned and moved successfully!'
				]);
			}

			return response()->json([
				'class' => 'error',
				'message' => 'File upload failed!'
			]);

		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'class' => 'error',
				'message' => $e->getMessage()
			]);
		}
	}


	private function moveUserToResigned($empId, $resignDate)
	{
		// Get user data
		$user = DB::table('users')->where('id', $empId)->first();

		if (!$user) {
			throw new \Exception('User not found');
		}

		// Convert object to array
		$data = (array) $user;

		// Optional: add resignation date
		$data['resign_date'] = $resignDate;

		// Insert into resign_employee
		DB::table('resign_employee')->insert($data);

		// Delete from users
		DB::table('users')->where('id', $empId)->delete();
	}


	public function getDailyActivity(Request $request)
	{
		$userId = $request->input('user_id');
		$date = $request->input('date');
		$authUserId = Auth::user()->id;

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

	public function checkEmail(Request $request)
	{
		$emailExists = User::where('email', $request->email)->exists();
		return response()->json(['exists' => $emailExists]);
	}

	public function workFromHome($id = null)
	{
		$userId = Auth::user()->id;
		$employee = null;
		$encodedId = $id;

		if ($id) {
			// Decode the employee ID
			$empId = base64_decode($id);

			// Get employee details
			$employee = DB::table('employees')
				->join('users', 'employees.empId', '=', 'users.id')
				->select('employees.*', 'users.name', 'users.phone')
				->where('employees.empId', $empId)
				->first();
		}

		// Get WFH history for the employee or all employees if admin
		$wfhHistory = $this->getWFHHistory($id ? base64_decode($id) : null);

		return view('User.workFromHome', [
			'employee' => $employee,
			'encodedId' => $encodedId,
			'wfhHistory' => $wfhHistory
		]);
	}

	private function getWFHHistory($empId = null)
	{
		$userId = Auth::user()->id;
		$query = DB::table('work_from_home')
			->leftJoin('employees', 'work_from_home.emp_id', '=', 'employees.empId')
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->select(
				'work_from_home.*',
				'users.name as employee_name',
				'employees.employee_id'
			)
			->where('work_from_home.added_by', $userId)
			->orderBy('work_from_home.created_at', 'desc');

		if ($empId) {
			$query->where('work_from_home.emp_id', $empId);
		}

		return $query->get();
	}

	public function storeWFHRequest(Request $request)
	{
		try {
			$userId = Auth::user()->id;

			$request->validate([
				'fromDate' => 'required|date|after_or_equal:today',
				'toDate' => 'required|date|after_or_equal:fromDate',
				'reason' => 'required|string|max:1000',
				'workPlan' => 'required|string|max:2000'
			]);

			$fromDate = Carbon::parse($request->fromDate);
			$toDate = Carbon::parse($request->toDate);
			$totalDays = $fromDate->diffInDays($toDate) + 1;

			// Get employee details
			$empId = null;
			$employeeId = null;

			if ($request->employeeId) {
				$id = base64_decode($request->employeeId);
				$employee = DB::table('employees')
					->where('empId', $id)
					->where('added_by', $userId)
					->first();

				if ($employee) {
					$empId = $id;
					$employeeId = $employee->employee_id;
				}
			} else {
				// For current user if no employee specified
				$employee = DB::table('employees')
					->where('empId', $userId)
					->first();

				if ($employee) {
					$empId = $userId;
					$employeeId = $employee->employee_id;
				}
			}

			if (!$empId || !$employeeId) {
				return response()->json([
					'status' => 'error',
					'message' => 'Employee not found'
				]);
			}

			// Check for overlapping requests
			$existingRequest = DB::table('work_from_home')
				->where('emp_id', $empId)
				->where('status', '!=', 'rejected')
				->where(function ($query) use ($fromDate, $toDate) {
					$query->whereBetween('from_date', [$fromDate, $toDate])
						->orWhereBetween('to_date', [$fromDate, $toDate])
						->orWhere(function ($q) use ($fromDate, $toDate) {
							$q->where('from_date', '<=', $fromDate)
								->where('to_date', '>=', $toDate);
						});
				})
				->exists();

			if ($existingRequest) {
				return response()->json([
					'status' => 'error',
					'message' => 'You already have a WFH request for overlapping dates'
				]);
			}

			// Determine status based on user type (admin gets approved automatically)
			$userType = Auth::user()->u_type;
			$status = ($userType == 1 || $userType == 2) ? 'approved' : 'pending'; // Admin/User gets approved, Employee gets pending
			$approvedBy = ($status == 'approved') ? $userId : null;
			$approvedAt = ($status == 'approved') ? now() : null;

			// Insert WFH request
			DB::table('work_from_home')->insert([
				'employee_id' => $employeeId,
				'emp_id' => $empId,
				'from_date' => $fromDate->format('Y-m-d'),
				'to_date' => $toDate->format('Y-m-d'),
				'total_days' => $totalDays,
				'reason' => $request->reason,
				'work_plan' => $request->workPlan,
				'status' => $status,
				'approved_by' => $approvedBy,
				'approved_at' => $approvedAt,
				'added_by' => $userId,
				'created_at' => now(),
				'updated_at' => now()
			]);

			return response()->json([
				'status' => 'success',
				'message' => 'Work from Home request submitted successfully!'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Please select future date'
			]);
		}
	}

	public function getWFHDetails($id)
	{
		$userId = Auth::user()->id;

		$wfhDetails = DB::table('work_from_home')
			->leftJoin('employees', 'work_from_home.emp_id', '=', 'employees.empId')
			->leftJoin('users', 'employees.empId', '=', 'users.id')
			->leftJoin('users as approver', 'work_from_home.approved_by', '=', 'approver.id')
			->select(
				'work_from_home.*',
				'users.name as employee_name',
				'employees.employee_id',
				'approver.name as approved_by_name'
			)
			->where('work_from_home.id', $id)
			->where('work_from_home.added_by', $userId)
			->first();

		if (!$wfhDetails) {
			return response()->json([
				'status' => 'error',
				'message' => 'WFH request not found'
			]);
		}

		return response()->json([
			'status' => 'success',
			'data' => $wfhDetails
		]);
	}

	public function acceptWFHRequest($id)
	{
		try {
			$userId = Auth::user()->id;

			// Check if the WFH request exists and belongs to the current user's company
			$wfhRequest = DB::table('work_from_home')
				->where('id', $id)
				->where('added_by', $userId)
				->where('status', 'pending')
				->first();

			if (!$wfhRequest) {
				return response()->json([
					'status' => 'error',
					'message' => 'WFH request not found or already processed'
				]);
			}

			// Update the request status to approved
			DB::table('work_from_home')
				->where('id', $id)
				->update([
					'status' => 'approved',
					'approved_by' => $userId,
					'approved_at' => now(),
					'updated_at' => now()
				]);

			return response()->json([
				'status' => 'success',
				'message' => 'WFH request accepted successfully!'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to accept WFH request. Please try again.'
			]);
		}
	}

	public function rejectWFHRequest(Request $request, $id)
	{
		try {
			$userId = Auth::user()->id;

			$request->validate([
				'rejection_reason' => 'required|string|max:500'
			]);

			// Check if the WFH request exists and belongs to the current user's company
			$wfhRequest = DB::table('work_from_home')
				->where('id', $id)
				->where('added_by', $userId)
				->where('status', 'pending')
				->first();

			if (!$wfhRequest) {
				return response()->json([
					'status' => 'error',
					'message' => 'WFH request not found or already processed'
				]);
			}

			// Update the request status to rejected
			DB::table('work_from_home')
				->where('id', $id)
				->update([
					'status' => 'rejected',
					'rejection_reason' => $request->rejection_reason,
					'approved_by' => $userId,
					'approved_at' => now(),
					'updated_at' => now()
				]);

			return response()->json([
				'status' => 'success',
				'message' => 'WFH request rejected successfully!'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to reject WFH request. Please try again.'
			]);
		}
	}

	public function getWFHCalendarData(Request $request)
	{
		$userId = Auth::user()->id;
		$year = $request->input('year');
		$month = $request->input('month');
		$employeeId = $request->input('employeeId');

		// Create date range for the month
		$startDate = Carbon::create($year, $month, 1)->startOfMonth();
		$endDate = Carbon::create($year, $month, 1)->endOfMonth();

		// Build query using DB table directly to match existing methods
		$query = DB::table('work_from_home')
			->where('added_by', $userId)
			->where(function ($q) use ($startDate, $endDate) {
				$q->whereBetween('from_date', [$startDate, $endDate])
					->orWhereBetween('to_date', [$startDate, $endDate])
					->orWhere(function ($subQ) use ($startDate, $endDate) {
						$subQ->where('from_date', '<=', $startDate)
							->where('to_date', '>=', $endDate);
					});
			});

		// Filter by employee if provided
		if ($employeeId) {
			$empId = base64_decode($employeeId);
			$query->where('emp_id', $empId);
		}

		$wfhRequests = $query->get();

		// Create calendar data array
		$calendarData = [];

		foreach ($wfhRequests as $wfh) {
			$fromDate = Carbon::parse($wfh->from_date);
			$toDate = Carbon::parse($wfh->to_date);

			// Create date range for this WFH request
			$period = CarbonPeriod::create($fromDate, $toDate);

			foreach ($period as $date) {
				// Only include dates within the requested month
				if ($date->year == $year && $date->month == $month) {
					$dateString = $date->format('Y-m-d');
					$calendarData[$dateString] = [
						'id' => $wfh->id,
						'status' => $wfh->status,
						'reason' => $wfh->reason,
						'work_plan' => $wfh->work_plan,
						'from_date' => $wfh->from_date,
						'to_date' => $wfh->to_date,
						'total_days' => $wfh->total_days,
						'rejection_reason' => $wfh->rejection_reason
					];
				}
			}
		}

		return response()->json([
			'status' => 'success',
			'data' => $calendarData
		]);
	}


	// public function checkCompanyPolicies()
	// {
	// 	$userId = Auth::id();
	// 	$company = Company_profiles::where('userId', $userId)->first();

	// 	return response()->json([
	// 		'comp_epf' => $company->comp_epf,
	// 		'comp_esic' => $company->comp_esic,
	// 		'comp_ptax'  => $company ? $company->comp_ptax : '',
	// 		'comp_tan'   => $company ? $company->comp_tan : '',
	// 	]);
	// }

	public function checkCompanyPolicies()
	{
		$user = Auth::user();
		//$userId = $user->id;
		$userId = currentOwnerId();

		// Check user type
		if ($user->u_type == 2 || $user->u_type == 5) {

			// Fetch from company_profiles
			$company = Company_profiles::where('userId', $userId)->first();

			return response()->json([
				'comp_epf'  => $company ? $company->comp_epf : '',
				'comp_esic' => $company ? $company->comp_esic : '',
				'comp_ptax' => $company ? $company->comp_ptax : '',
				'comp_tan'  => $company ? $company->comp_tan : '',
			]);

		} elseif ($user->u_type == 1 || $user->u_type == 4) {

			// Fetch from ca_profiles
			$ca = Ca_profiles::where('userId', $userId)->first();

			return response()->json([
				'comp_epf'  => $ca ? $ca->epf_reg_no : '',
				'comp_esic' => $ca ? $ca->esic_reg_no : '',
				'comp_ptax' => $ca ? $ca->pt_reg_no : '',
				'comp_tan'  => $ca ? $ca->tan_no : '', 
			]);
		} elseif ($user->u_type == 3 || $user->u_type == 6) {
			// Fetch from Admin_profiless
			$admin = Admin_profiles::where('userId', $userId)->first();
			return response()->json([
				'comp_epf'  => $admin ? $admin->comp_epf : '',
				'comp_esic' => $admin ? $admin->comp_esic : '',
				'comp_ptax' => $admin ? $admin->comp_ptax : '',
				'comp_tan'  => $admin ? $admin->comp_tan : '', 
			]);
		}

		// Default fallback
		return response()->json([
			'comp_epf' => '',
			'comp_esic' => '',
			'comp_ptax' => '',
			'comp_tan' => '',
		]);
	}


	public function employeeHrLetter($empId)
	{
		$empId = base64_decode($empId);

		$userId = Auth::id();

		// Fetch all letters created by this user (company)
		$letters = DB::table('company_hr_sent_letters')
			->where('added_by', $userId)
			->where('employee_id', $empId)
			->select('id', 'subject', 'content', 'sent_at')
			->orderByDesc('id')
			->get();

		return view('User.employeeHrletter', compact('letters'));
	}

	public function Performace(Request $request)
	{
		$empId = base64_decode($request->id); // Employee small ID
		$userId = Auth::user()->id;

		// Fetch all ratings for this employee added by current user
		$ratings = EmployeeRating::where('empId', $empId)
			->where('added_by', $userId)
			->orderBy('created_at', 'desc')
			->get();

		return view('User.performace-review', compact('ratings', 'empId'));
	}


	public function saveRating(Request $request)
	{
		$userId = Auth::user()->id;
		$currentYear = now()->year;

		$request->validate([
			'empId' => 'required',
			'month' => 'required',
			'work_rating' => 'required|integer|min:0|max:5',
			'skill_rating' => 'required|integer|min:0|max:5',
			'attendance_rating' => 'required|integer|min:0|max:5',
			'teamwork_rating' => 'required|integer|min:0|max:5',
			'total_percentage' => 'required|numeric|min:0|max:100',
		]);

		 // Check for duplicate record for same month & year
		$exists = EmployeeRating::where('empId', $request->empId)
			->where('added_by', $userId)
			->where('review_month', $request->month)
			->where('review_year', $currentYear)
			->exists();

		if ($exists) {
			return response()->json([
				'success' => false,
				'message' => 'You have already added a review for this month!',
			]);
		}

		// Create new rating
		$rating = EmployeeRating::create([
			'empId' => $request->empId,
			'added_by' => $userId,
			'review_month' => $request->month,
        	'review_year' => $currentYear,
			'work_rating' => $request->work_rating,
			'skill_rating' => $request->skill_rating,
			'attendance_rating' => $request->attendance_rating,
			'teamwork_rating' => $request->teamwork_rating,
			'total_percentage' => $request->total_percentage,
			'review' => $request->review,
		]);

		return response()->json([
			'success' => true,
			'message' => 'Rating saved successfully!',
		]);
	}

	public function editRating($id)
	{
		$userId = Auth::user()->id;

		$rating = EmployeeRating::where('id', $id)
			->where('added_by', $userId)
			->first();

		if (!$rating) {
			return response()->json([
				'success' => false,
				'message' => 'Rating not found!',
			], 404);
		}

		return response()->json([
			'success' => true,
			'data' => $rating,
		]);
	}

	public function updateRating(Request $request, $id)
	{
		$userId = Auth::user()->id;

		$request->validate([
			'work_rating' => 'required|integer|min:0|max:5',
			'skill_rating' => 'required|integer|min:0|max:5',
			'attendance_rating' => 'required|integer|min:0|max:5',
			'teamwork_rating' => 'required|integer|min:0|max:5',
			'total_percentage' => 'required|numeric|min:0|max:100',
		]);

		$rating = EmployeeRating::where('id', $id)
			->where('added_by', $userId)
			->first();

		if (!$rating) {
			return response()->json([
				'success' => false,
				'message' => 'Rating not found!',
			], 404);
		}

		$rating->update([
			'work_rating' => $request->work_rating,
			'skill_rating' => $request->skill_rating,
			'attendance_rating' => $request->attendance_rating,
			'teamwork_rating' => $request->teamwork_rating,
			'total_percentage' => $request->total_percentage,
			'review' => $request->review,
		]);

		return response()->json([
			'success' => true,
			'message' => 'Rating updated successfully!',
		]);
	}

	public function deleteRating($id)
	{
		$userId = Auth::user()->id;

		$rating = EmployeeRating::where('id', $id)
			->where('added_by', $userId)
			->first();

		if (!$rating) {
			return response()->json([
				'success' => false,
				'message' => 'Rating not found!',
			], 404);
		}

		$rating->delete();

		return response()->json([
			'success' => true,
			'message' => 'Rating deleted successfully!',
		]);
	}

	public function calculateTDS($grossSalary, $tdsDiductAmount)
	{
		// Auto calculate TDS if gross salary is greater than 10 lakh
		if ($grossSalary > $tdsDiductAmount) {
			// Get TDS rate from database or use default 10%
			try {
				$tdsRate = DB::table('tds_tax_slab')
					->where('tds_slab_name', 'Salary')
					->first();
				
				$rate = $tdsRate ? ($tdsRate->tds_slab_rate / 100) : 0.10; // Default 10%
				return $grossSalary * $rate;
			} catch (\Exception $e) {
				// Fallback to 10% if database query fails
				return $grossSalary * 0.10;
			}
		}
		return 0; // No TDS for salary <= 10 lakh
	}

	public function calculateTDSAjax(Request $request)
	{
		try {
			$basicSalary = floatval($request->basic_salary);
			$tdsDiductAmount = floatval($request->tdsDiductAmount);
			$tdsAmount = $this->calculateTDS($basicSalary, $tdsDiductAmount);
			
			return response()->json([
				'success' => true,
				'tds_amount' => $tdsAmount,
				'is_auto_calculated' => $basicSalary > $tdsDiductAmount,
				'message' => $basicSalary > $tdsDiductAmount ? 'TDS auto-calculated for salary above ₹' . $tdsDiductAmount : 'No TDS required for salary below ₹' . $tdsDiductAmount,
			]);
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => 'Error calculating TDS: ' . $e->getMessage()
			]);
		}
	}
	
	public function getTdsSlabs()
	{
		$slabs = DB::table('tds_salary_slabs')
					->where('tds_rule_id', 3)
					->orderBy('from_amount')
					->get(['from_amount', 'to_amount', 'tax_rate']);

		return response()->json($slabs);
	}

	public function updatePayslip()
    {	
        return view('User.payslip_update');
    }

}
