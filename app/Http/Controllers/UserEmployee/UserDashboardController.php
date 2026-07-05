<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeRating;
use Carbon\Carbon;


class UserDashboardController extends Controller
{
    // Employee Dashboard Data
    public function getDashboardData()
    {
        $userId = auth()->id();
        $user_type = auth()->user();
        $cur_won = currentOwnerId();

        $employee = DB::table('users')
            ->leftJoin('employees', 'users.id', '=', 'employees.empId')
            ->leftJoin('depertments', 'employees.dept_id', '=', 'depertments.id')
            ->leftJoin('designations', 'employees.desig_id', '=', 'designations.id')
            ->where('users.id', $userId)
            ->select(
                'users.name as user_name',
                'users.email',
                'users.avatar',
                'employees.employee_id',
                'employees.profile_img',
                'employees.work_location',
                'employees.emp_status',
                'depertments.dept_name',
                'designations.designation_name'
            )
            ->first();

        // $companyName = DB::table('users')
        //     ->where('id', auth()->user()->user_add_by)
        //     ->value('name');

        $companyName = "";
        if ($user_type->u_type == 4) {
            $companyName = DB::table('ca_profiles')
                ->where('userId', $cur_won)
                ->value('comp_name');
        } elseif ($user_type->u_type == 5) {
            $companyName = DB::table('company_profiles')
                ->where('userId', $cur_won)
                ->value('comp_name');
        } elseif ($user_type->u_type == 6) {
            $companyName = DB::table('admin_profiles')
                ->where('userId', $cur_won)
                ->value('comp_name');
        }

        $managerName = DB::table('users')
            ->where('id', $cur_won)
            ->value('name');

        $profileImg = asset('assets/images/user/avatar-2.jpg');

        if (!empty($employee->profile_img)) {
            $profileImg = asset('storage/user_employee/' . $employee->profile_img);
        } elseif (!empty($employee->avatar)) {
            $profileImg = $profileImg;
        }

        return response()->json([
            'status' => true,
            'data' => [
                'name' => $employee->user_name,
                'employee_id' => $employee->employee_id,
                'designation' => $employee->designation_name,
                'department' => $employee->dept_name,
                'location' => $employee->work_location == 'work_from_home' ? 'Work From Home' : ($employee->work_location == 'work_from_office' ? 'Work From Office' : $employee->work_location),
                'emp_status' => $employee->emp_status,
                'manager' => $managerName,
                'company' => $companyName,
                'profile_image' => $profileImg,
            ]
        ]);
    }

    // Attendance Summary
    public function attendanceSummary()
    {
        $userId = auth()->id();
        $today = Carbon::today();

        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd   = $today;

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = $today;

        // Employee Details
        $employee = DB::table('employees')
            ->where('empId', $userId)
            ->select('added_by')
            ->first();

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found'
            ]);
        }

        // ===========================
        // COMPANY SCHEDULES
        // ===========================

        $schedules = DB::table('weekly_schedules')
            ->where('added_by', $employee->added_by)
            ->where('status', 'open')
            ->pluck('opening_time', 'day')
            ->toArray();

        $workingDays = array_map('strtolower', array_keys($schedules));

        // ===========================
        // FETCH APPROVED LEAVES & WFH DATES
        // ===========================
        
        $weeklyLeave = 0;
        $monthlyLeave = 0;
        $weeklyWfh = 0;
        $monthlyWfh = 0;

        $weeklyLeaveDates = [];
        $monthlyLeaveDates = [];
        $weeklyWfhDates = [];
        $monthlyWfhDates = [];

        // Process Leaves
        $approvedLeaves = DB::table('leaves')
            ->where('emp_id', $userId)
            ->where('status', 'approved')
            ->get();

        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd   = Carbon::parse($leave->end_date);

            // Weekly Leave Calculation
            $wStart = $leaveStart->copy()->max($weekStart);
            $wEnd   = $leaveEnd->copy()->min($weekEnd);
            if ($wStart <= $wEnd) {
                for ($date = $wStart->copy(); $date <= $wEnd; $date->addDay()) {
                    if (in_array(strtolower($date->format('l')), $workingDays)) {
                        $weeklyLeave++;
                        $weeklyLeaveDates[] = $date->toDateString();
                    }
                }
            }

            // Monthly Leave Calculation
            $mStart = $leaveStart->copy()->max($monthStart);
            $mEnd   = $leaveEnd->copy()->min($monthEnd);
            if ($mStart <= $mEnd) {
                for ($date = $mStart->copy(); $date <= $mEnd; $date->addDay()) {
                    if (in_array(strtolower($date->format('l')), $workingDays)) {
                        $monthlyLeave++;
                        $monthlyLeaveDates[] = $date->toDateString();
                    }
                }
            }
        }

        // Process Work From Home (WFH)
        $approvedWfh = DB::table('work_from_home')
            ->where('emp_id', $userId)
            ->where('status', 'approved')
            ->get();

        foreach ($approvedWfh as $wfh) {
            $wfhStart = Carbon::parse($wfh->from_date);
            $wfhEnd   = Carbon::parse($wfh->to_date);

            // Weekly WFH Calculation
            $wStart = $wfhStart->copy()->max($weekStart);
            $wEnd   = $wfhEnd->copy()->min($weekEnd);
            if ($wStart <= $wEnd) {
                for ($date = $wStart->copy(); $date <= $wEnd; $date->addDay()) {
                    if (in_array(strtolower($date->format('l')), $workingDays)) {
                        $weeklyWfh++;
                        $weeklyWfhDates[] = $date->toDateString();
                    }
                }
            }

            // Monthly WFH Calculation
            $mStart = $wfhStart->copy()->max($monthStart);
            $mEnd   = $wfhEnd->copy()->min($monthEnd);
            if ($mStart <= $mEnd) {
                for ($date = $mStart->copy(); $date <= $mEnd; $date->addDay()) {
                    if (in_array(strtolower($date->format('l')), $workingDays)) {
                        $monthlyWfh++;
                        $monthlyWfhDates[] = $date->toDateString();
                    }
                }
            }
        }

        // Combine exclusions for attendance query if necessary 
        // (e.g., if WFH records shouldn't count as standard physical attendance)
        $weeklyExclusions = array_merge($weeklyLeaveDates, $weeklyWfhDates);
        $monthlyExclusions = array_merge($monthlyLeaveDates, $monthlyWfhDates);

        // ===========================
        // PRESENT DAYS
        // ===========================

        $weeklyPresent = DB::table('attendance')
            ->where('userId', $userId)
            ->whereBetween('present_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->when(!empty($weeklyExclusions), function ($query) use ($weeklyExclusions) {
                $query->whereNotIn('present_date', $weeklyExclusions);
            })
            ->count();

        $monthlyPresent = DB::table('attendance')
            ->where('userId', $userId)
            ->whereBetween('present_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when(!empty($monthlyExclusions), function ($query) use ($monthlyExclusions) {
                $query->whereNotIn('present_date', $monthlyExclusions);
            })
            ->count();

        // ===========================
        // LATE MARKS
        // ===========================

        $weeklyLate = 0;
        $monthlyLate = 0;

        $weeklyAttendances = DB::table('attendance')
            ->where('userId', $userId)
            ->whereBetween('present_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->when(!empty($weeklyExclusions), function ($query) use ($weeklyExclusions) {
                $query->whereNotIn('present_date', $weeklyExclusions);
            })
            ->get();

        foreach ($weeklyAttendances as $attendance) {
            $dayName = strtolower(Carbon::parse($attendance->present_date)->format('l'));
            if (isset($schedules[$dayName]) && !empty($attendance->in_time)) {
                $officeTime = strtotime($schedules[$dayName]);
                $inTime = strtotime($attendance->in_time);
                if ($inTime > $officeTime) {
                    $weeklyLate++;
                }
            }
        }

        $monthlyAttendances = DB::table('attendance')
            ->where('userId', $userId)
            ->whereBetween('present_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->when(!empty($monthlyExclusions), function ($query) use ($monthlyExclusions) {
                $query->whereNotIn('present_date', $monthlyExclusions);
            })
            ->get();

        foreach ($monthlyAttendances as $attendance) {
            $dayName = strtolower(Carbon::parse($attendance->present_date)->format('l'));
            if (isset($schedules[$dayName]) && !empty($attendance->in_time)) {
                $officeTime = strtotime($schedules[$dayName]);
                $inTime = strtotime($attendance->in_time);
                if ($inTime > $officeTime) {
                    $monthlyLate++;
                }
            }
        }

        // ===========================
        // HOLIDAYS
        // ===========================

        $weeklyHoliday = DB::table('holidays')
            ->where('added_by', $employee->added_by)
            ->whereBetween('holidayDate', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->count();

        $monthlyHoliday = DB::table('holidays')
            ->where('added_by', $employee->added_by)
            ->whereBetween('holidayDate', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->count();

        // ===========================
        // WORKING DAYS
        // ===========================

        $weeklyWorkingDays = 0;
        for ($date = $weekStart->copy(); $date <= $weekEnd; $date->addDay()) {
            if (in_array(strtolower($date->format('l')), $workingDays)) {
                $weeklyWorkingDays++;
            }
        }

        $monthlyWorkingDays = 0;
        for ($date = $monthStart->copy(); $date <= $monthEnd; $date->addDay()) {
            if (in_array(strtolower($date->format('l')), $workingDays)) {
                $monthlyWorkingDays++;
            }
        }

        // ===========================
        // ABSENT DAYS
        // ===========================

        $weeklyAbsent = max(0, $weeklyWorkingDays - $weeklyPresent - $weeklyLeave - $weeklyWfh - $weeklyHoliday);
        $monthlyAbsent = max(0, $monthlyWorkingDays - $monthlyPresent - $monthlyLeave - $monthlyWfh - $monthlyHoliday);

        return response()->json([
            'status' => true,
            'weekly' => [
                'present' => $weeklyPresent,
                'late'    => $weeklyLate,
                'absent'  => $weeklyAbsent,
                'leave'   => $weeklyLeave,
                'wfh'     => $weeklyWfh,
            ],
            'monthly' => [
                'present' => $monthlyPresent,
                'late'    => $monthlyLate,
                'absent'  => $monthlyAbsent,
                'leave'   => $monthlyLeave,
                'wfh'     => $monthlyWfh,
            ]
        ]);
    }

    // public function getTaskCounts(Request $request)
    // {
    //     $range = $request->get('range', 'month');
    //     $userId = auth()->id();

    //     $employee = DB::table('employees')
    //         ->where('empId', $userId)
    //         ->first();

    //     if (!$employee) {
    //         return response()->json([
    //             'pending' => 0,
    //             'progress' => 0,
    //             'completed' => 0,
    //             'overdue' => 0
    //         ]);
    //     }

    //     $employeeId = $employee->employee_id;

    //     $query = DB::table('employee_task_managment')
    //         ->where('employee_id', $employeeId);

    //     $now = Carbon::now();

    //     // Filter using due_date
    //     if ($range == 'today') {
    //         $query->whereDate('due_date', $now->toDateString());
    //     } elseif ($range == 'week') {
    //         $query->whereBetween('due_date', [
    //             $now->copy()->startOfWeek(),
    //             $now->copy()->endOfWeek()
    //         ]);
    //     } else {
    //         $query->whereMonth('due_date', $now->month)
    //             ->whereYear('due_date', $now->year);
    //     }

    //     $tasks = $query->get();

    //     $pending = $tasks->where('status', 'Pending')->count();
    //     $progress = $tasks->where('status', 'In Progress')->count();
    //     $completed = $tasks->where('status', 'Completed')->count();

    //     // Overdue = due date passed and not completed
    //     $overdue = $tasks->filter(function ($task) use ($now) {
    //         return $task->status != 'Completed'
    //             && !empty($task->due_date)
    //             && Carbon::parse($task->due_date)->lt($now);
    //     })->count();

    //     return response()->json([
    //         'pending'   => $pending,
    //         'progress'  => $progress,
    //         'completed' => $completed,
    //         'overdue'   => $overdue,
    //     ]);
    // }

    public function getTaskCounts(Request $request)
    {
        $range = $request->get('range', 'month');
        $userId = auth()->id();
        $user = Auth::user();

        $now = Carbon::now();

        if ($user->u_type == 4) {

            // Same logic as Index()
            $query = DB::table('task_managements')
                ->where('emp_id', $userId);

        } else {

            $employee = DB::table('employees')
                ->where('empId', $userId)
                ->first();

            if (!$employee) {
                return response()->json([
                    'pending' => 0,
                    'progress' => 0,
                    'completed' => 0,
                    'overdue' => 0,
                    'high_priority' => 0,
                    'average_priority' => 0,
                ]);
            }

            $query = DB::table('employee_task_managment')
                ->where('employee_id', $employee->employee_id);
        }

        // Date Filter
        if ($range == 'today') {
            $query->whereDate('due_date', $now->toDateString());
        } elseif ($range == 'week') {
            $query->whereBetween('due_date', [
                $now->copy()->startOfWeek()->toDateString(),
                $now->copy()->endOfWeek()->toDateString()
            ]);
        } else {
            $query->whereMonth('due_date', $now->month)
                ->whereYear('due_date', $now->year);
        }

        $tasks = $query->get();

        if ($user->u_type == 4) {

            // task_managements table
            $pending = $tasks->where('project_status', 1)->count();
            $progress = $tasks->where('project_status', 2)->count();
            $completed = $tasks->where('project_status', 3)->count();

            $overdue = $tasks->filter(function ($task) use ($now) {
                return $task->project_status != 3
                    && !empty($task->due_date)
                    && Carbon::parse($task->due_date)->diffInDays($now, false) > 7;
            })->count();

            $highPriority = $tasks->where('project_priority', 'High')->count();
            $averagePriority = $tasks->where('project_priority', 'Average')->count();

        } else {

            // employee_task_managment table
            $pending = $tasks->where('status', 'Pending')->count();
            $progress = $tasks->where('status', 'In Progress')->count();
            $completed = $tasks->where('status', 'Completed')->count();

            $overdue = $tasks->filter(function ($task) use ($now) {
                return $task->status != 'Completed'
                    && !empty($task->due_date)
                    && Carbon::parse($task->due_date)->lt($now);
            })->count();

            $highPriority = 0;
            $averagePriority = 0;
        }

        return response()->json([
            'pending' => $pending,
            'progress' => $progress,
            'completed' => $completed,
            'overdue' => $overdue,
            'high_priority' => $highPriority,
            'average_priority' => $averagePriority,
        ]);
    }

    // public function getUpcomingTasks(Request $request)
    // {
    //     $userId = auth()->id();

    //     $employee = DB::table('employees')
    //         ->where('empId', $userId)
    //         ->first();

    //     if (!$employee) {
    //         return response()->json([]);
    //     }

    //     $query = DB::table('employee_task_managment')
    //         ->where('employee_id', $employee->employee_id);

    //     // Date picker filter
    //     if ($request->filled('date')) {

    //         $query->whereDate('due_date', $request->date);

    //     } else {

    //         if ($request->filter == 'today') {

    //             // Today's tasks (all statuses)
    //             $query->whereDate('due_date', Carbon::today());

    //         } elseif ($request->filter == 'weekly') {

    //             // Only Pending & In Progress
    //             $query->whereIn('status', ['Pending', 'In Progress'])

    //                 // Show all pending/in-progress tasks up to end of current week
    //                 ->whereDate(
    //                     'due_date',
    //                     '<=',
    //                     Carbon::now()->endOfWeek()->toDateString()
    //                 );

    //         } elseif ($request->filter == 'monthly') {

    //             // Only Pending & In Progress
    //             $query->whereIn('status', ['Pending', 'In Progress'])

    //                 // Show all pending/in-progress tasks up to end of current month
    //                 ->whereDate(
    //                     'due_date',
    //                     '<=',
    //                     Carbon::now()->endOfMonth()->toDateString()
    //                 );
    //         }
    //     }

    //     $tasks = $query
    //         ->orderBy('due_date', 'desc')
    //         ->select(
    //             'id',
    //             'title',
    //             'description',
    //             'priority',
    //             'status',
    //             DB::raw('DATE(due_date) as due_date')
    //         )
    //         ->get();

    //     return response()->json($tasks);
    // }

    public function getUpcomingTasks(Request $request)
    {
        $userId = auth()->id();
        $user = Auth::user();

        if ($user->u_type == 4) {

            $query = DB::table('task_managements')
                ->leftJoin('task_category', 'task_managements.task_category', '=', 'task_category.id')
                ->where('task_managements.emp_id', $userId);

            // Date filter
            if ($request->filled('date')) {

                $query->whereDate('task_managements.due_date', $request->date);

            } else {

                if ($request->filter == 'today') {

                    $query->whereDate('task_managements.due_date', Carbon::today());

                } elseif ($request->filter == 'weekly') {

                    $query->whereIn('task_managements.project_status', [1, 2])
                        ->whereDate(
                            'task_managements.due_date',
                            '<=',
                            Carbon::now()->endOfWeek()->toDateString()
                        );

                } elseif ($request->filter == 'monthly') {

                    $query->whereIn('task_managements.project_status', [1, 2])
                        ->whereDate(
                            'task_managements.due_date',
                            '<=',
                            Carbon::now()->endOfMonth()->toDateString()
                        );
                }
            }

            $tasks = $query
                ->orderBy('task_managements.due_date', 'desc')
                ->select(
                    'task_managements.task_id as id',
                    'task_category.task_category_name as title',
                    'task_managements.message as description',
                    'task_managements.project_priority as priority',
                    'task_managements.project_status',
                    DB::raw('DATE(task_managements.due_date) as due_date')
                )
                ->get()
                ->map(function ($task) {

                    $task->status = match ((int) $task->project_status) {
                        1 => 'Pending',
                        2 => 'In Progress',
                        3 => 'Completed',
                        default => 'Unknown',
                    };

                    unset($task->project_status);

                    return $task;
                });

            return response()->json($tasks);
        }

        // Employee Task Management Logic
        $employee = DB::table('employees')
            ->where('empId', $userId)
            ->first();

        if (!$employee) {
            return response()->json([]);
        }

        $query = DB::table('employee_task_managment')
            ->where('employee_id', $employee->employee_id);

        if ($request->filled('date')) {

            $query->whereDate('due_date', $request->date);

        } else {

            if ($request->filter == 'today') {

                $query->whereDate('due_date', Carbon::today());

            } elseif ($request->filter == 'weekly') {

                $query->whereIn('status', ['Pending', 'In Progress'])
                    ->whereDate(
                        'due_date',
                        '<=',
                        Carbon::now()->endOfWeek()->toDateString()
                    );

            } elseif ($request->filter == 'monthly') {

                $query->whereIn('status', ['Pending', 'In Progress'])
                    ->whereDate(
                        'due_date',
                        '<=',
                        Carbon::now()->endOfMonth()->toDateString()
                    );
            }
        }

        $tasks = $query
            ->orderBy('due_date', 'desc')
            ->select(
                'id',
                'title',
                'description',
                'priority',
                'status',
                DB::raw('DATE(due_date) as due_date')
            )
            ->get();

        return response()->json($tasks);
    }

    // public function updateTaskStatus(Request $request)
    // {
    //     DB::table('employee_task_managment')
    //         ->where('id', $request->task_id)
    //         ->update([
    //             'status' => $request->status,
    //             'completed_date' =>now(),
    //             'updated_at' => now()
    //         ]);

    //     return response()->json([
    //         'success' => true
    //     ]);
    // }

    public function updateTaskStatus(Request $request)
    {
        $user = Auth::user();

        if ($user->u_type == 4) {

            $statusMap = [
                'Pending' => 1,
                'In Progress' => 2,
                'Completed' => 3,
            ];

            DB::table('task_managements')
                ->where('task_id', $request->task_id)
                ->update([
                    'project_status' => $statusMap[$request->status] ?? 1,
                    'updated_at' => now(),
                ]);

        } else {

            DB::table('employee_task_managment')
                ->where('id', $request->task_id)
                ->update([
                    'status' => $request->status,
                    'completed_date' => $request->status == 'Completed' ? now() : null,
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    //--- Employee Review --
    public function getPerformanceReview()
    {
        $empId = Auth::id();

        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        // Try current month first
        $rating = EmployeeRating::where('empId', $empId)
            ->where('review_month', $currentMonth->month)
            ->where('review_year', $currentMonth->year)
            ->latest()
            ->first();

        // If current month review not found, get previous month
        if (!$rating) {
            $rating = EmployeeRating::where('empId', $empId)
                ->where('review_month', $previousMonth->month)
                ->where('review_year', $previousMonth->year)
                ->latest()
                ->first();
        }

        if (!$rating) {
            return response()->json([
                'status' => false,
                'message' => 'No review found.'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $rating
        ]);
    }
}
