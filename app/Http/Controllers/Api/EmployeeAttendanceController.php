<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EmployeeAttendanceController extends Controller
{   
    private function validateSecureAccess(string $empId, string $secureKey): bool
    {
        // Hash-based validation
        $expectedHash = hash('sha256', $empId . config('app.key'));
        
        // Compare the provided secure key with the expected hash
        if (hash_equals($expectedHash, $secureKey)) {
            return true;
        }

        return false;
    }
    /**
     * Fetch attendance summary for an employee over a date range.
     * 
     * Expected inputs:
     * - empId: Employee ID (string)
     * - secure: Security hash (string)
     * - from_date: Start date (Y-m-d)
     * - to_date: End date (Y-m-d)
     * 
     * Response includes daily status, check-in/out times, and totals.
     */

    public function attendanceRangeSummary(Request $request): JsonResponse
    {
        try {
            // 1) Validate inputs
            $validator = Validator::make($request->all(), [
                'empId'     => 'required|string|max:50',
                'secure'    => 'required|string|min:8',
                'from_date' => 'required|date_format:Y-m-d',
                'to_date'   => 'required|date_format:Y-m-d|after_or_equal:from_date',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            } // validation and date rules [web:36]

            $empId    = $request->input('empId');
            $secure   = $request->input('secure');
            $fromDate = $request->input('from_date');
            $toDate   = $request->input('to_date');

            // 2) Security
            if (!$this->validateSecureAccess($empId, $secure)) {
                return response()->json([
                    'success'    => false,
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            } // secure access [web:4]

            // 3) Resolve employee and ownership
            $employee = DB::table('employees')
                ->leftJoin('users', 'employees.empId', '=', 'users.id')
                ->select('employees.*', 'users.name as emp_name')
                ->where('employees.employee_id', $empId)
                ->first();
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                ], 404);
            } // employee fetch [web:4]

            // Normalize bounds
            $start = Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay();
            $end   = Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay();

            // 4) Prefetch schedules, holidays, leaves, attendance within range

            // Weekly schedule keyed by lowercase weekday
            $weeklySchedule = DB::table('weekly_schedules')
                ->where('added_by', $employee->added_by)
                ->get()
                ->keyBy(function ($row) { return strtolower($row->day); }); // schedule map [web:41]

            // Holidays within range (date-only column assumed)
            $holidayDates = DB::table('holidays')
                ->where('added_by', $employee->added_by)
                ->whereDate('holidayDate', '>=', $start->toDateString())
                ->whereDate('holidayDate', '<=', $end->toDateString())
                ->pluck('holidayDate')
                ->map(fn($d) => Carbon::parse($d)->toDateString())
                ->flip(); // set-like map for O(1) lookup [web:34]

            // Approved leave overlaps within range; expand to set of dates
            $leaves = DB::table('leaves')
                ->where('employee_id', $employee->employee_id) // adjust if schema uses emp_id
                ->where('status', 'approved')
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_date', '<=', $end->toDateString())
                    ->where('end_date', '>=', $start->toDateString());
                })
                ->get(); // overlap condition [web:34]

            $leaveDates = [];
            foreach ($leaves as $lv) {
                $lvStart = Carbon::parse($lv->start_date)->startOfDay();
                $lvEnd   = Carbon::parse($lv->end_date)->endOfDay();

                // Clip to requested window
                $rangeStart = $lvStart->greaterThan($start) ? $lvStart : $start->copy();
                $rangeEnd   = $lvEnd->lessThan($end) ? $lvEnd : $end->copy();

                // Expand using CarbonPeriod
                $period = CarbonPeriod::create($rangeStart->toDateString(), $rangeEnd->toDateString());
                foreach ($period as $d) {
                    $leaveDates[$d->toDateString()] = $lv->leave_type; // last wins if overlapping [web:45][web:42]
                }
            }

            // Attendance rows in range; present_date assumed datetime
            $attendanceRows = DB::table('attendance')
                ->where('userId', $employee->empId) // internal id used in table
                ->whereBetween('present_date', [$start, $end])
                ->get(); // prefetch once [web:34][web:52]

            $attendanceMap = [];
            foreach ($attendanceRows as $row) {
                $k = Carbon::parse($row->present_date)->toDateString();
                $attendanceMap[$k] = $row; // if multiple, last wins; adapt if needed [web:4]
            }

            // 5) Iterate date range and resolve daily status
            $period = CarbonPeriod::create($start->toDateString(), $end->toDateString()); // inclusive [web:45][web:42]

            $totalPresent = 0;
            $totalAbsent = 0;
            $totalLeave  = 0;
            $totalHoliday = 0;
            $totalOfficeOff = 0;

            $timeline = [];
            $today = Carbon::today()->toDateString();

            foreach ($period as $date) {
                $dateStr = $date->toDateString();
                $dayName = strtolower($date->format('l'));
                $schedule = $weeklySchedule[$dayName] ?? null;

                // Default fields
                $status = 'Absent';
                $badge  = 'bg-danger';
                $notes  = '-';
                $checkIn = '-';
                $checkOut = '-';

                // Determine schedule state: treat missing schedule as "closed" or explicit "No Schedule"
                $isClosed = false;
                $isWorking = false;
                if ($schedule) {
                    $isClosed = ($schedule->status === 'closed');
                    $isWorking = ($schedule->status === 'open');
                } else {
                    // No schedule provided: treat as Office Off to avoid counting as absent
                    $isClosed = true;
                } // schedule handling [web:41]

                // Priority 1: Office Off
                if ($isClosed) {
                    $status = 'Office Off';
                    $badge = 'bg-dark';
                    $notes = 'Office Off';
                    $totalOfficeOff++;
                }
                // Priority 2: Holiday
                elseif (isset($holidayDates[$dateStr])) {
                    $status = 'Holiday';
                    $badge = 'bg-secondary';
                    $notes = 'Public Holiday';
                    $totalHoliday++;
                }
                // Priority 3: Leave
                elseif (isset($leaveDates[$dateStr])) {
                    $status = 'Leave';
                    $badge = 'bg-info';
                    $notes = ucfirst($leaveDates[$dateStr]) . ' Leave';
                    $totalLeave++;
                }
                // Priority 4: Attendance on working day
                elseif ($isWorking) {
                    $att = $attendanceMap[$dateStr] ?? null;
                    if ($att) {
                        $checkIn  = $att->in_time ? Carbon::parse($att->in_time)->format('g:i A') : '-';
                        $checkOut = $att->out_time ? Carbon::parse($att->out_time)->format('g:i A') : '-';
                        $notes    = $att->reason ?: '-';

                        // Present vs Late relative to opening_time (if defined)
                        if (in_array($att->present_status, ['present','working'], true)) {
                            if (!empty($schedule->opening_time) && $att->in_time) {
                                $in  = Carbon::parse($att->in_time);
                                $opn = Carbon::parse($schedule->opening_time);
                                if ($in->gt($opn)) {
                                    $status = 'Late';
                                    $badge  = 'bg-warning';
                                } else {
                                    $status = 'Present';
                                    $badge  = 'bg-success';
                                }
                            } else {
                                $status = 'Present';
                                $badge  = 'bg-success';
                            }
                            $totalPresent++;
                        } else {
                            // If an explicit 'absent' row is stored
                            $status = 'Absent';
                            $badge = 'bg-danger';
                            $totalAbsent++;
                        }
                    } else {
                        // No record on past working day => Absent; future => Scheduled
                        if ($dateStr > $today) {
                            $status = 'Scheduled';
                            $badge = 'bg-light text-dark';
                            $notes = 'Scheduled Working Day';
                        } else {
                            $status = 'Absent';
                            $badge = 'bg-danger';
                            $totalAbsent++;
                        }
                    }
                } else {
                    // Non-working day that is not closed? Keep as neutral
                    $status = 'Office Off';
                    $badge = 'bg-dark';
                    $notes = 'Office Off';
                    $totalOfficeOff++;
                }

                $timeline[] = [
                    'date'        => $date->format('d-m-Y'),
                    'status'      => $status,
                    'badge_class' => $badge,
                    'check_in'    => $checkIn,
                    'check_out'   => $checkOut,
                    'notes'       => $notes,
                ];
            } // foreach period [web:45][web:42]

            // 6) Response
            return response()->json([
                'success' => true,
                'message' => 'Attendance range summary fetched successfully.',
                'data' => [
                    'totals' => [
                        'totalPresent'   => $totalPresent,
                        'totalAbsent'    => $totalAbsent,
                        'totalLeave'     => $totalLeave,
                        'totalHoliday'   => $totalHoliday,
                        'totalOfficeOff' => $totalOfficeOff,
                    ],
                    'timeline' => $timeline, // daily statuses in range
                ],
                'context' => [
                    'employee'  => ['name' => $employee->emp_name, 'employee_id' => $employee->employee_id],
                    'from'      => $fromDate,
                    'to'        => $toDate,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Attendance range summary error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success'    => false,
                'message'    => 'An error occurred while fetching attendance range summary.',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Fetch detailed daily activity for an employee on a specific date.
     * 
     * Expected inputs:
     * - empId: Employee ID (string)
     * - secure: Security hash (string)
     * - date: Date of interest (Y-m-d)
     * 
     * Response includes status, check-in/out times, working hours, lateness, leave/holiday info.
     */

    public function getDailyActivity(Request $request): JsonResponse
    {
        // 1) Validate
        $validator = Validator::make($request->all(), [
            'empId'  => 'required|string|max:50',
            'date'   => 'required|date_format:Y-m-d',
            'secure' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        } // validation [web:36][web:2]

        $empId  = $request->input('empId');
        $date   = $request->input('date');
        $secure = $request->input('secure');

        // 2) Security
        if (!$this->validateSecureAccess($empId, $secure)) {
            return response()->json([
                'success'    => false,
                'message'    => 'Unauthorized access. Invalid security credentials.',
                'error_code' => 'SECURITY_VALIDATION_FAILED'
            ], 403);
        } // secure [web:2]

        // 3) Resolve employee + owner
        $employee = DB::table('employees')
            ->leftJoin('users', 'employees.empId', '=', 'users.id')
            ->select('employees.*', 'users.name as emp_name')
            ->where('employees.employee_id', $empId)
            ->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        } // employee [web:4]

        // 4) Attendance / schedule / holiday / leave
        $attendance = DB::table('attendance')
            ->where('userId', $employee->empId)
            ->whereDate('present_date', $date)
            ->first(); // attendance-of-day [web:2]

        $dayName = strtolower(Carbon::parse($date)->format('l'));
        $weeklySchedule = DB::table('weekly_schedules')
            ->where('added_by', $employee->added_by)
            ->where('day', $dayName)
            ->first(); // schedule-of-day [web:2]

        $holiday = DB::table('holidays')
            ->where('added_by', $employee->added_by)
            ->whereDate('holidayDate', $date)
            ->first(); // holiday-of-day [web:2]

        $leave = DB::table('leaves')
            ->where('employee_id', $employee->employee_id) // switch to emp_id if schema requires
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first(); // overlapping leave [web:2]

        // 5) Working hours + late
        $workingHours = null;
        $isLate = false;
        $lateBy = null;

        if ($attendance && $attendance->in_time && $attendance->out_time) {
            $baseDate = Carbon::parse($date)->format('Y-m-d');
            $inTime  = Carbon::parse($baseDate.' '.$attendance->in_time);
            $outTime = Carbon::parse($baseDate.' '.$attendance->out_time);
            $totalMin = $outTime->diffInMinutes($inTime);
            $workingHours = sprintf('%02d:%02d', intdiv($totalMin, 60), $totalMin % 60); // HH:MM [web:94][web:60]
        } // working hours [web:94][web:60]

        if ($attendance && $attendance->in_time && $weeklySchedule && $weeklySchedule->status === 'open') {
            $baseDate = Carbon::parse($date)->format('Y-m-d');
            $inTime      = Carbon::parse($baseDate.' '.$attendance->in_time);
            $openingTime = Carbon::parse($baseDate.' '.$weeklySchedule->opening_time);
            if ($inTime->gt($openingTime)) {
                $isLate = true;
                $diffM = $inTime->diffInMinutes($openingTime);
                $lateBy = sprintf('%02d:%02d', intdiv($diffM, 60), $diffM % 60); // HH:MM [web:94][web:60]
            }
        } // lateness [web:94][web:60]

        // 5a) Lunch fields and totals from attendance
        $lunchIn  = $attendance && !empty($attendance->lunch_in)  ? $attendance->lunch_in  : null;
        $lunchOut = $attendance && !empty($attendance->lunch_out) ? $attendance->lunch_out : null;
        $workLocationStatus = $attendance && !empty($attendance->work_location_status) ? $attendance->work_location_status : null;

        $totalLunchTime = null;
        $lunchStatus = null;
        if ($attendance) {
            if ($lunchIn && $lunchOut) {
                $baseDate = Carbon::parse($date)->format('Y-m-d');
                $li = Carbon::parse($baseDate.' '.$lunchIn);
                $lo = Carbon::parse($baseDate.' '.$lunchOut);
                $lm = $lo->diffInMinutes($li);
                $totalLunchTime = sprintf('%02d:%02d', intdiv($lm, 60), $lm % 60); // HH:MM [web:94]
                $lunchStatus = 'ended';
            } elseif ($lunchIn && !$lunchOut) {
                $lunchStatus = 'ongoing';
            } else {
                $lunchStatus = 'not_taken';
            }
        } // lunch duration/status [web:94]

        // 6) Status precedence
        $status = 'absent';
        $statusColor = 'danger';
        $statusIcon = 'ph-x-circle';
        $isClosed = ($weeklySchedule && $weeklySchedule->status === 'closed');

        if ($isClosed) {
            $status = 'weekend';
            $statusColor = 'secondary';
            $statusIcon = 'ph-calendar-x';
        } elseif ($holiday) {
            $status = 'holiday';
            $statusColor = 'secondary';
            $statusIcon = 'ph-calendar-x';
        } elseif ($leave) {
            $status = 'leave';
            $statusColor = 'info';
            $statusIcon = 'ph-airplane-takeoff';
        } elseif ($attendance && in_array($attendance->present_status, ['present','working'], true)) {
            if ($isLate) {
                $status = 'late';
                $statusColor = 'warning';
                $statusIcon = 'ph-clock';
            } else {
                $status = 'present';
                $statusColor = 'success';
                $statusIcon = 'ph-check';
            }
        } else {
            $status = 'absent';
            $statusColor = 'danger';
            $statusIcon = 'ph-x-circle';
        } // precedence

        // 7) Task history: Completed tasks for the date
        $taskHistory = DB::table('employee_task_managment')
            ->join('users', 'users.id', '=', 'employee_task_managment.added_by')
            ->where('employee_task_managment.employee_id', $employee->employee_id)
            ->whereIn('employee_task_managment.status', ['Completed','Complete'])
            ->where(function ($q) use ($date) {
                $q->whereDate('employee_task_managment.completed_date', $date)
                ->orWhere(function ($q2) use ($date) {
                    $q2->whereNull('employee_task_managment.completed_date')
                        ->whereDate('employee_task_managment.due_date', $date);
                });
            })
            ->select([
                'employee_task_managment.id',
                'employee_task_managment.title',
                'employee_task_managment.priority',
                'employee_task_managment.due_date',
                'employee_task_managment.completed_date',
                'employee_task_managment.description',
                'employee_task_managment.added_by',
                DB::raw('users.name as added_by_name'),
            ])
            ->orderByDesc('employee_task_managment.completed_date')
            ->orderByDesc('employee_task_managment.due_date')
            ->get(); // [web:4][web:71][web:74]

        // 8) Breaks from employee_office_break for this date
        // Aggregate total break time with SQL (HH:MM)
        $totalBreakRow = DB::table('employee_office_break')
            ->where('userId', $employee->empId)
            ->whereDate('break_date', $date)
            ->select(DB::raw("TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(total_break_time))), '%H:%i') as total_break")) // HH:MM [web:90][web:89][web:95]
            ->first();

        $totalBreakTime = $totalBreakRow && $totalBreakRow->total_break ? $totalBreakRow->total_break : '00:00'; // [web:90][web:89]

        // Fetch detailed break entries
        $breakEntries = DB::table('employee_office_break')
            ->where('userId', $employee->empId)
            ->whereDate('break_date', $date)
            ->orderBy('break_in', 'asc')
            ->select([
                'id',
                'break_in',
                'break_out',
                'break_status',
                'total_break_time',
            ])
            ->get(); // [web:2][web:88]

        // 9) Response
        return response()->json([
            'success' => true,
            'message' => 'Daily activity fetched successfully.',
            'data' => [
                'date'               => Carbon::parse($date)->format('d M Y'), // 30 Aug 2025 [web:60]
                'dayName'            => ucfirst($dayName),
                'status'             => $status,
                'statusColor'        => $statusColor,
                'statusIcon'         => $statusIcon,
                'inTime'             => $attendance ? $attendance->in_time : null,
                'outTime'            => $attendance ? $attendance->out_time : null,
                'workingHours'       => $workingHours,           // HH:MM [web:94]
                'isLate'             => $isLate,
                'lateBy'             => $lateBy,                 // HH:MM [web:94]
                'reason'             => $attendance ? ($attendance->reason ?: null) : null,
                'leaveType'          => $leave ? $leave->leave_type : null,
                'leaveReason'        => $leave ? ($leave->reason ?: null) : null,
                'holidayName'        => $holiday ? $holiday->holidayName : null,
                'openingTime'        => $weeklySchedule ? ($weeklySchedule->opening_time ?: null) : null,
                'closingTime'        => $weeklySchedule ? ($weeklySchedule->closing_time ?: null) : null,

                // Lunch and location
                'lunch_in'           => $lunchIn,                // from attendance [web:2]
                'lunch_out'          => $lunchOut,               // from attendance [web:2]
                'lunch_status'       => $lunchStatus,            // ended | ongoing | not_taken
                'total_lunch_time'   => $totalLunchTime,         // HH:MM [web:94]
                'work_location_status'=> $workLocationStatus,    // from attendance [web:2]

                // Breaks
                'breaks' => [
                    'entries'          => $breakEntries,         // list for the day [web:2]
                    'total_break_time' => $totalBreakTime,       // HH:MM aggregate [web:90][web:89],
                ],

                // Tasks
                'task_history' => $taskHistory,                  // Completed for this date
            ],
        ], 200);
    }





}