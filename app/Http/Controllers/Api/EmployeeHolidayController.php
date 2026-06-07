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

class EmployeeHolidayController extends Controller
{
    /**
     * Validate secure access based on empId and secure key
     * 
     * @param string $empId
     * @param string $secureKey
     * @return bool
     */
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
     * Apply for a leave
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function applyLeave(Request $request): JsonResponse
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'empId'     => 'required|string|max:50',       // e.g. "emp00090" (your external employee_id)
                'fromDate'  => 'required|date_format:Y-m-d',
                'toDate'    => 'required|date_format:Y-m-d|after_or_equal:fromDate',
                'reason'    => 'required|string|max:500',
                'leaveType' => 'required|string|max:50',
                'secure'    => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId              = $request->input('empId');         // employees.employee_id (e.g., emp00090)
            $fromDate           = $request->input('fromDate');
            $toDate             = $request->input('toDate');
            $leaveType          = $request->input('leaveType');
            $reason             = $request->input('reason');
            $secureKey          = $request->input('secure');

            // 2) Validate secure access
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                Log::warning('Employee details access - Security validation failed', [
                    'user_id' => Auth::id(),
                    'empId' => $empId,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch the employee and related info
            //    - employees.employee_id    => public identifier (e.g., emp00090)
            //    - employees.empId          => internal numeric (e.g., 90)
            //    - employees.added_by       => owning company/user id (used for schedules & ownership)
            //    - users.name / users.u_type
            $employee = DB::table('employees')
                ->join('users', 'users.id', '=', 'employees.empId')
                ->where('employees.employee_id', $empId)
                ->select([
                    'employees.employee_id',
                    'employees.empId',
                    'employees.added_by',
                    'users.name',
                    'users.u_type'
                ])
                ->first();


            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }

            // 4) Calculate total working days for this company/owner (employees.added_by)
            $totalDays = $this->calculateWorkingDays($fromDate, $toDate, $employee->added_by);
            
            if ($totalDays <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected date range has no working days as per schedule.',
                    'error_code' => 'NO_WORKING_DAYS'
                ], 422);
            }

            // 5) (Optional) Check overlapping leaves (pending/approved)
            $overlapExists = DB::table('leaves')
                ->where('employee_id', $employee->employee_id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($q) use ($fromDate, $toDate) {
                    // overlap if (start <= to) and (end >= from)
                    $q->where('start_date', '<=', $toDate)
                    ->where('end_date', '>=', $fromDate);
                })
                ->exists();

            if ($overlapExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Overlapping leave request exists for the selected dates.',
                    'error_code' => 'LEAVE_OVERLAP'
                ], 422);
            }

            // 6) Prepare the insert payload (mirror of storeLeave)
            $data = [
                'company_id'   => $employee->added_by,          // owner/tenant (mirrors storeLeave’s company_id)
                'added_by'     => $employee->added_by,          // same as company (service account scenario)
                'u_type'       => $employee->u_type ?? 'user',
                'employee_id'  => $employee->employee_id,       // public id like "emp00090"
                'emp_name'     => $employee->name,
                'emp_id'       => $employee->empId,             // numeric internal id like 90
                'leave_type'   => $leaveType,
                'start_date'   => $fromDate,
                'end_date'     => $toDate,
                'total_days'   => $totalDays,
                'reason'       => $reason,
                'status'       => 'pending',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            DB::table('leaves')->insert($data);

            return response()->json([
                'success' => true,
                'message' => 'Leave application submitted successfully.',
                'data'    => [
                    'employee_name' => $employee->name,
                    'employee_id'   => $employee->employee_id,
                    'leave_type'    => $leaveType,
                    'total_days'    => $totalDays,
                    'status'        => 'pending',
                    'period'        => ['from' => $fromDate, 'to' => $toDate],
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Apply leave error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);

            // Avoid leaking internal exception details in production responses
            return response()->json([
                'success'    => false,
                'message'    => 'An error occurred while applying for leave.',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get list of leaves for an employee
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listOfLeave(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'empId'  => 'required|string|max:50',
                'secure' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId     = $request->input('empId');
            $secureKey = $request->input('secure');

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch employee to get the public employee_id
            $employee = DB::table('employees')
                ->where('employee_id', $empId)
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }

            // Leave list
            $leaves = DB::table('leaves')
                ->where('employee_id', $employee->employee_id)
                ->orderBy('start_date', 'desc')
                ->select([
                    'id',
                    'leave_type',
                    'start_date',
                    'end_date',
                    'total_days',
                    'reason',
                    'status',
                    'created_at'
                ])
                ->get();

            // Summary counts
            $summary = DB::table('leaves')
                ->where('employee_id', $employee->employee_id)
                ->selectRaw("
                    COUNT(*)                                  as total,
                    SUM(CASE WHEN status = 'pending'  THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                ")
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Leave list fetched successfully.',
                'data'    => [
                    
                    'summary' => [
                        'total'    => (int)($summary->total ?? 0),
                        'pending'  => (int)($summary->pending ?? 0),
                        'approved' => (int)($summary->approved ?? 0),
                        'rejected' => (int)($summary->rejected ?? 0),
                    ],
                    'leaves'  => $leaves,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('List of leave error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching leave list.',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }


    /**
     * Get details of a specific leave by leave ID and employee ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function leaveDetails(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'empId'   => 'required|string|max:50',
                'leaveId' => 'required|integer',
                'secure'  => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId     = $request->input('empId');
            $leaveId   = $request->input('leaveId');
            $secureKey = $request->input('secure');

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch employee to get the public employee_id
            $employee = DB::table('employees')
                ->where('employee_id', $empId)
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }

            // Fetch leave details
            $leave = DB::table('leaves')
                ->where('id', $leaveId)
                ->where('employee_id', $employee->employee_id)
                ->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Leave details fetched successfully.',
                'data'    => $leave
            ], 200);

        } catch (\Exception $e) {
            Log::error('Leave details error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching leave details.',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Calculate number of working days between two dates (inclusive),
     * using weekly_schedules for the given company/owner (added_by).
     * Defaults to Monday–Friday if no schedule is set.
     */
    private function calculateWorkingDays(string $startDate, string $endDate, int $ownerId): int
    {
        $workingDays = DB::table('weekly_schedules')
            ->where('added_by', $ownerId)
            ->where('status', 'open')
            ->pluck('day')
            ->toArray();

        if (empty($workingDays)) {
            $workingDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        }

        $workingDays = array_map('strtolower', $workingDays);

        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);
        $count = 0;

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dayName = strtolower($d->format('l'));
            if (in_array($dayName, $workingDays, true)) {
                $count++;
            }
        }

        return $count;
    }

    //---- Today Task Functionality ----//
    /**
     * Get today's task metrics and list for an employee
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function todayTask(Request $request): JsonResponse
    {
        // Validate inputs
        $request->validate([
            'empId'       => 'required|string|max:50',
            'secure'      => 'required|string|min:8',
            'toDaydate'   => 'sometimes|date_format:Y-m-d', // optional override
            'currentTime' => 'sometimes|date_format:H:i:s', // optional override
        ]); // request validation [11]

        $empId  = $request->input('empId');
        $secure = $request->input('secure');

        // secure access
        if (!$this->validateSecureAccess($empId, $secure)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Invalid security credentials.',
                'error_code' => 'SECURITY_VALIDATION_FAILED'
            ], 403);
        } // security check [11]

        // Ensure employee exists
        $employee = DB::table('employees')
            ->where('employee_id', $empId)
            ->select(['employee_id'])
            ->first(); // existence check [11]

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        } // not found [11]

        // Determine "today" and "now" (overridable for tests)
        $todayDate   = $request->input('toDaydate', Carbon::today()->toDateString()); // Y-m-d [13]
        $currentTime = $request->input('currentTime', now()->format('H:i:s'));        // H:i:s [14]
        $cutoff      = $todayDate . ' ' . $currentTime;                                // full datetime [12]

        // Base query: employee’s tasks due today (by date only)
        $baseToday = DB::table('employee_task_managment')
            ->where('employee_id', $employee->employee_id)
            ->whereDate('due_date', $todayDate); // only today’s date [13]

        // List all tasks due today (any status), most recent first
        $totalTaskList = (clone $baseToday)
            ->select([
                'id','title','priority','due_date','description',
                'status','added_by','completed_date','created_at','updated_at'
            ])
            ->orderByRaw("FIELD(priority, 'High', 'Medium', 'Low') ASC")   // custom priority order [2]
            ->orderBy('due_date', 'desc')                                  // tie-breaker within each priority [7]
            ->get(); // task rows [1]

        // KPI counts (date = today)
        $totalTask = (clone $baseToday)->count(); // all due today [11]

        // Completed today
        $completeTask = (clone $baseToday)
            ->whereIn('status', ['Complete','complete','Completed'])
            ->count(); // completed today [11]

        // Due by now subset (today and time <= selected time)
        $dueByNow = (clone $baseToday)
            ->whereTime('due_date', '<=', $currentTime)
            ->count(); // due up to current time today [11]

        // Overdue/due tasks: open statuses and full datetime strictly before cutoff
        $dueTask = DB::table('employee_task_managment')
            ->where('employee_id', $employee->employee_id)
            ->whereIn('status', ['Pending','In Progress'])
            ->where('due_date', '<', $cutoff) // full datetime compare [12]
            ->count(); // overdue open tasks [11]

        // Progress based on completed out of total
        $progressPercent = $totalTask > 0 ? round(($completeTask / $totalTask) * 100) : 0; // percent [11]
        $workProgress = $progressPercent . '% complete'; // display string [11]

        return response()->json([
            'success' => true,
            'message' => 'Today task metrics fetched successfully.',
            'data' => [
                'total_task'       => $totalTask,
                'complete_task'    => $completeTask,
                'work_progress'    => $workProgress,
                'due_by_now'       => $dueByNow,
                'due_task'         => $dueTask,       // overdue: open + past cutoff
                'total_task_list'  => $totalTaskList,
                'context'          => [
                    'date' => $todayDate,
                    'time' => $currentTime,
                ],
            ],
        ], 200);
    }

    //------ Task suatus upadte function (not in use now) -------//
    /** 
     * Update task status (not currently used in routes)
     * @param Request $request
     * @return JsonResponse
     */

    public function taskStatusUpdate(Request $request): JsonResponse
    {
        // Validate inputs
        $request->validate([
            'empId'         => 'required|string|max:50',
            'secure'        => 'required|string|min:8',
            'taskId'        => 'required|integer',
            'status'        => 'required|string|in:Pending,In Progress,Completed',
            'completedDate' => 'sometimes|date_format:Y-m-d H:i:s', // optional override for tests
        ]); // validation [3]

        $empId   = $request->input('empId');
        $secure  = $request->input('secure');
        $taskId  = (int) $request->input('taskId');
        $status  = $request->input('status');

        // Secure access
        if (!$this->validateSecureAccess($empId, $secure)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Invalid security credentials.',
                'error_code' => 'SECURITY_VALIDATION_FAILED'
            ], 403);
        } // security check [3]

        // Ensure employee exists
        $employee = DB::table('employees')
            ->where('employee_id', $empId)
            ->select(['employee_id'])
            ->first(); // existence check [3]

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        } // not found [3]

        // Ensure the task belongs to this employee
        $task = DB::table('employee_task_managment')
            ->where('id', $taskId)
            ->where('employee_id', $employee->employee_id)
            ->first(); // fetch task [3]

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found for this employee.',
            ], 404);
        } // not found [3]

        // Build update payload
        $update = [
            'status'     => $status,
            'updated_at' => now(),
        ]; // base update [3]

        // If marking Completed, set completed_date (allow override for testing)
        if ($status === 'Completed') {
            $update['completed_date'] = $request->input('completedDate', now()->format('Y-m-d H:i:s'));
        } else {
            // If reverting from Completed to any other state, clear completed_date
            $update['completed_date'] = null;
        } // conditional update fields [3][2]

        // Persist update
        DB::table('employee_task_managment')
            ->where('id', $taskId)
            ->where('employee_id', $employee->employee_id)
            ->update($update); // query builder update [3][2]

        // Return the latest task row
        $updated = DB::table('employee_task_managment')
            ->where('id', $taskId)
            ->first(); // readback [3]

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'data'    => $updated,
        ], 200);
    }

    /**
     * Get task list with overview metrics for an employee
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function taskList(Request $request): JsonResponse
    {
        try {
            // 1) Validate inputs
            $validator = Validator::make($request->all(), [
                'empId'       => 'required|string|max:50',
                'secure'      => 'required|string|min:8',
                'toDayDate'   => 'required|date_format:Y-m-d',
                'currentTime' => 'required|date_format:H:i:s',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            } // validation per Laravel query builder/validation rules [12]

            $empId       = $request->input('empId');
            $secureKey   = $request->input('secure');
            $todayDate   = $request->input('toDayDate');   // Y-m-d
            $currentTime = $request->input('currentTime'); // H:i:s

            // 2) Security
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                Log::warning('Task list access - Security validation failed', [
                    'user_id' => Auth::id(),
                    'empId'   => $empId,
                    'ip'      => $request->ip(),
                ]);
                return response()->json([
                    'success'    => false,
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            } // consistent secure access [12]

            // 3) Ensure employee exists
            $employee = DB::table('employees')
                ->where('employee_id', $empId)
                ->select(['employee_id'])
                ->first();
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                ], 404);
            } // existence check [12]

            // 4) Build reference datetimes
            $nowRef     = Carbon::createFromFormat('Y-m-d H:i:s', $todayDate.' '.$currentTime);
            $overdueRef = $nowRef->copy()->subDays(2); // “more than 2 days pending” threshold [2]

            // 5) Overview metrics across ALL tasks (no date filter)
            $allTasks = DB::table('employee_task_managment')
                ->where('employee_id', $employee->employee_id);

            $totalDueTask = (clone $allTasks)
                ->where('status', 'Pending')
                ->count(); // all pending [12]

            $totalOngoingTask = (clone $allTasks)
                ->where('status', 'In Progress')
                ->count(); // all in progress [12]

            $completeTask = (clone $allTasks)
                ->whereIn('status', ['Complete','complete','Completed'])
                ->count(); // all completed [12]

            // Overdue > 2 days: Pending with due_date < (toDayDate currentTime - 2 days), compare full datetime
            $totalOverdueTask = (clone $allTasks)
                ->where('status', 'Pending')
                ->where('due_date', '<', $overdueRef->toDateTimeString())
                ->count(); // datetime comparison older than interval [2][8]

            // 6) Task list: ALL pending and in-progress tasks, not limited by date
            $taskList = DB::table('employee_task_managment')
                    ->join('users', 'users.id', '=', 'employee_task_managment.added_by')
                    ->where('employee_id', $employee->employee_id)
                    ->whereIn('employee_task_managment.status', ['Pending', 'In Progress'])
                    ->select([
                        'employee_task_managment.id','employee_task_managment.title',
                        'employee_task_managment.priority','employee_task_managment.due_date',
                        'employee_task_managment.description','employee_task_managment.status',
                        'employee_task_managment.added_by','employee_task_managment.completed_date',
                        'employee_task_managment.created_at','employee_task_managment.updated_at',
                        DB::raw('users.name as added_by_name'),
                    ])
                    ->orderByRaw("FIELD(employee_task_managment.priority, 'High', 'Medium', 'Low') ASC")
                    ->orderBy('employee_task_managment.due_date', 'desc')
                    ->get(); // join + alias for creator name [1]


            // Optionally annotate due_status per row (overdue, dueSoon, upcoming) in PHP if desired:
            // foreach ($taskList as $t) { ... } // omitted here to keep DB-only response

            return response()->json([
                'success' => true,
                'message' => 'Task list fetched successfully.',
                'data'    => [
                    'taskOverView' => [
                        'totalDueTask'      => $totalDueTask,
                        'totalOngoingTask'  => $totalOngoingTask,
                        'completeTask'      => $completeTask,
                        'totalOverdueTask'  => $totalOverdueTask,
                    ],
                    'taskList' => $taskList,
                    'context'  => [
                        'now_reference' => $nowRef->toDateTimeString(),
                        'overdue_after' => $overdueRef->toDateTimeString(),
                    ],
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Task list error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success'    => false,
                'message'    => 'An error occurred while fetching task list.',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get detailed info for a specific task by task ID and employee ID
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function taskDetails(Request $request): JsonResponse
    {
        try {
            // 1) Validate
            $validator = Validator::make($request->all(), [
                'empId'       => 'required|string|max:50',
                'taskId'      => 'required|integer',
                'secure'      => 'required|string|min:8',
                'toDayDate'   => 'sometimes|date_format:Y-m-d',
                'currentTime' => 'sometimes|date_format:H:i:s',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors(),
                ], 422);
            } // basic input and date/time formats [2]

            $empId       = $request->input('empId');
            $taskId      = (int) $request->input('taskId');
            $secureKey   = $request->input('secure');

            // 2) Security
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            } // secure access [2]

            // 3) Ensure employee exists
            $employee = DB::table('employees')
                ->where('employee_id', $empId)
                ->select(['employee_id'])
                ->first();
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                ], 404);
            } // existence check [2]

            // 4) Fetch task that belongs to employee
            $task = DB::table('employee_task_managment')
                    ->join('users', 'users.id', '=', 'employee_task_managment.added_by')
                    ->where('employee_task_managment.id', $taskId)
                    ->where('employee_task_managment.employee_id', $employee->employee_id)
                    ->select([
                        'employee_task_managment.id',
                        'employee_task_managment.title',
                        'employee_task_managment.priority',
                        'employee_task_managment.due_date',
                        'employee_task_managment.description',
                        'employee_task_managment.status',
                        'employee_task_managment.added_by',
                        'employee_task_managment.completed_date',
                        'employee_task_managment.created_at',
                        'employee_task_managment.updated_at',
                        DB::raw('users.name as added_by_name'),
                    ])
                    ->first(); // join to resolve added_by -> users.name [1]

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found for this employee.',
                ], 404);
            } // ownership and existence [2]

            // 5) Build reference moments (optional overrides)
            $todayDate   = $request->input('toDayDate', Carbon::today()->toDateString());
            $currentTime = $request->input('currentTime', now()->format('H:i:s'));
            $nowRef      = Carbon::createFromFormat('Y-m-d H:i:s', $todayDate.' '.$currentTime);
            $overdueRef  = $nowRef->copy()->subDays(2); // more than 2 days overdue threshold [11][12]

            // 6) Compute dynamic flags
            $status            = (string) $task->status;
            $isCompleted       = in_array($status, ['Complete','Completed'], true);
            $isActionable      = in_array($status, ['Pending','In Progress'], true);

            $dueAt   = Carbon::parse($task->due_date);
            $dueByNow = $isActionable && $dueAt->lte($nowRef); // time-aware due check [2]

            $isOverdue2d = ($status === 'Pending') && $dueAt->lt($overdueRef); // older than now-2d [11][12]

            // days overdue (0 if not past)
            $overdueDays = 0;
            if ($isActionable && $dueAt->lt($nowRef)) {
                // diffInDays with signed result; negative means past relative to nowRef
                $diff = $dueAt->diffInDays($nowRef, false); // signed difference
                $overdueDays = max(0, $diff); // days past due (non-negative) [7][10]
            }

            // 7) Shape response
            $details = [
                'id'             => $task->id,
                'title'          => $task->title,
                'priority'       => $task->priority,
                'description'    => $task->description,
                'status'         => $task->status,
                'due_date'       => $task->due_date,
                'added_by'       => $task->added_by,
                'added_by_name'  => $task->added_by_name,
                'completed_date' => $task->completed_date,
                'created_at'     => $task->created_at,
                'updated_at'     => $task->updated_at,
                // computed
                'is_completed'   => $isCompleted,
                'due_by_now'     => $dueByNow,
                'is_overdue_2d'  => $isOverdue2d,
                'overdue_days'   => $overdueDays,
                'context'        => [
                    'now_reference' => $nowRef->toDateTimeString(),
                    'overdue_after' => $overdueRef->toDateTimeString(),
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Task details fetched successfully.',
                'data'    => $details,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Task details error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching task details.',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }




    
}