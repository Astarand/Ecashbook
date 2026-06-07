<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Get employee details by empId with secure validation
     *
     * Security Features:
     * - Requires authentication via Sanctum
     * - Validates secure token/empId match
     * - Logs all access attempts
     * - Returns only specific fields (name, employee_id, email, gender)
     * - Input validation and sanitization
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getEmployeeDetails(Request $request): JsonResponse
    {

        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'empId' => 'required|string|max:50',
                'secure' => 'required|string|min:8',
                'today_date'   => 'nullable|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                Log::warning('Employee details access - Validation failed', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors(),
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $empId = $request->input('empId');
            $secureKey = $request->input('secure');

            $expectedHash = hash('sha256', $empId . config('app.key'));

            // Security validation: Check if secure key matches expected pattern
            // This is a basic implementation - you should implement your own secure validation
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

            // Find employee by empId
            $employee = Employees::where('employee_id', $empId)
                                ->select('empId', 'employee_id', 'email_id as email', 'gender', 'work_location','location_id','dept_id', 'desig_id', 'profile_img', 'joining_date','regine_date')
                                ->first();

            if (!$employee) {
                Log::info('Employee details access - Employee not found', [
                    'user_id' => Auth::id(),
                    'empId' => $empId,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found',
                    'error_code' => 'EMPLOYEE_NOT_FOUND'
                ], 404);
            }

            // Office Location (select all requested fields)
            $location = DB::table('locations')
                        ->where('id', $employee->location_id)
                        ->where('status', 'Active')
                        ->first();

            // Department (from 'depertments')
            $department = DB::table('depertments')
                ->where('id', $employee->dept_id)
                ->first();

            // Designation (from 'designations')
            $designation = DB::table('designations')
                ->where('id', $employee->desig_id)
                ->first();

            // ---------- NEW: Work-location for a given date ----------
            $targetDate = $request->input('today_date') ?: Carbon::now()->toDateString();
            $hasWfh = DB::table('work_from_home') //
                ->where('employee_id', $employee->employee_id) // 'emp24-00002' Like This
                ->where('status', 'approved')
                ->whereDate('from_date', '<=', $targetDate)
                ->whereDate('to_date', '>=', $targetDate)
                ->exists();

            // $todayWorkLocation = $hasWfh ? 'Work_From_Home' : 'Work_From_Office';
            // Determine today's work location
            if (strtolower($employee->work_location) === 'work_from_home') {
                $todayWorkLocation = 'Work_From_Home';
            } else {
                $todayWorkLocation = $hasWfh ? 'Work_From_Home' : 'Work_From_Office';
            }
            // --------------------------Profile Image URL --------------------------
            // base URL from .env (config/app.php → 'url')
            $baseUrl   = rtrim(config('app.url'), '/');

            // raw filename from DB
            $rawFile   = $employee->profile_img;

            // Normalize + build full URL
            if (!empty($rawFile)) {
                if (preg_match('/^https?:\/\//i', $rawFile)) {
                    $profileUrl = $rawFile;
                } else {

                    $file = ltrim(str_replace('\\', '/', $rawFile), '/');
                    $profileUrl = $baseUrl . '/public/storage/user_employee/' . $file;
                }
            } else {
                $profileUrl = null;
            }

            // ------------------------------------

            $userMeta = $this->getEmployeeName($employee->empId);
            $name     = is_array($userMeta) ? ($userMeta['name'] ?? '') : (string) $userMeta;


            // ---------- Today Working Status -----------
            $existing = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $request->input('today_date'))
                ->first();

            $attendanceStatus    = 'not_present';
            $lunchStatus         = 'pending';
            $breakStatus         = 'not_break';
            $todayWorkingStatus  = 'not_present';

            if ($existing) {
                // Attendance
                $attendanceStatus = 'present';

                // Working / Punch status
                if ($existing->login_status === 'punch_out') {
                    $todayWorkingStatus = 'punch_out';
                } elseif ($existing->login_status === 'yes') {
                    if ($existing->lunch_status === 'yes') {
                        $todayWorkingStatus = 'lunch';
                    } else {
                        $todayWorkingStatus = 'working';
                    }
                }

                // Lunch status mapping
                if ($existing->lunch_status === 'yes') {
                    $lunchStatus = 'ongoing';
                } elseif ($existing->lunch_status === 'complete') {
                    $lunchStatus = 'complete';
                } else {
                    $lunchStatus = 'pending';
                }
            }

            // ---------- Fetch Break Status from employee_office_break table -----------
            $latestBreak = DB::table('employee_office_break')
                ->where('empId', $empId)
                ->where('break_date', $request->input('today_date'))
                ->orderBy('id', 'desc') // get latest entry
                ->first();

            if ($latestBreak ) {
                $breakStatus = $latestBreak->break_status;

                if ($breakStatus === 'start') {
                    $breakStatus = 'ongoing';
                } elseif ($breakStatus === 'end') {
                    $breakStatus = 'not_break';
                }

            } else {
                $breakStatus = 'not_break';
            }

            $employeeData = [
                'date'                     => $request->input('today_date') ?: Carbon::now()->toDateString(),
                'name'                     => $name,
                'status'                   => empty($employee->regine_date) ? 'Active' : 'Resigned',
                'employee_id'              => $employee->employee_id,
                'email'                    => $employee->email,
                'gender'                   => $employee->gender,
                'profile_img'              => $profileUrl,
                'department_name'          => $department->dept_name ?? null,
                'designation_name'         => $designation->designation_name ?? null,
                'today_work_location'      => $todayWorkLocation,
                'todayWorkingStatus'       => $todayWorkingStatus,

                // 👇 updated fields
                'attendance_status'        => $attendanceStatus,
                'lunch_status'             => $lunchStatus,
                'break_status'             => $breakStatus, // now coming from employee_office_break

                'office_location' => $location ? [
                    'id'            => $location->id,
                    'location_name' => $location->location_name,
                    'location_type' => $location->location_type,
                    'latitude'      => $location->latitude,
                    'longitude'     => $location->longitude,
                    'radius'        => $location->radius,
                    'radius_unit'   => 'meters',
                ] : null,
            ];






            // $existing = DB::table('attendance')
            //                 ->where('empId', $empId)
            //                 ->where('present_date', $request->input('today_date'))
            //                 ->first();
            // $todayWorkingStatus = 'not_present';
            // if ($existing) {
            //         if ($existing->login_status === 'punch_out') {
            //             $todayWorkingStatus = 'punch_out'; // If login_status is 'punch_out'
            //         }
            //         // Check if login_status is 'yes' and lunch_status is 'yes'
            //         if ($existing->login_status === 'yes') {
            //             if ($existing->lunch_status === 'yes') {
            //                 $todayWorkingStatus = 'lunch'; // If lunch_status is 'yes'
            //             } else {
            //                 $todayWorkingStatus = 'working'; // If lunch_status is 'no' or 'complete'
            //             }
            //         }
            //     }

            // $employeeData = [
            //     'date'                     => $request->input('today_date') ?: Carbon::now()->toDateString(),
            //     'name'                     => $name,
            //     'status'                   => empty($employee->regine_date) ? 'Active' : 'Resigned',
            //     'employee_id'              => $employee->employee_id,
            //     'email'                    => $employee->email,
            //     'gender'                   => $employee->gender,
            //     'profile_img'              => $profileUrl,
            //     'department_name'          => $department->dept_name ?? null,
            //     'designation_name'         => $designation->designation_name ?? null,
            //     // 'permanent_work_location'  => $employee->work_location,
            //     'today_work_location'      => $todayWorkLocation,
            //     'todayWorkingStatus'       => $todayWorkingStatus,

            //     // NEW: nested blocks
            //     'office_location' => $location ? [
            //         'id'            => $location->id,
            //         'location_name' => $location->location_name,
            //         'location_type' => $location->location_type,
            //         'latitude'      => $location->latitude,
            //         'longitude'     => $location->longitude,
            //         'radius'        => $location->radius,
            //         'radius_unit'        => 'meters',

            //     ] : null,

            // ];


            // Log successful access
            Log::info('Employee details accessed successfully', [
                'user_id' => Auth::id(),
                'empId' => $empId,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee details retrieved successfully',
                'data' => $employeeData
            ], 200);

        } catch (\Exception $e) {
            Log::error('Employee details access - Exception occurred', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving employee details',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

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
     * Get employee name from related table or generate from available data
     *
     * @param string $empId
     * @return string
     */
    private function getEmployeeName(string $empId): array
    {



        $user = User::where('id', $empId)->select('name', 'status')->first();

        if ($user) {
            return [
                'name' => $user->name ?? '',
            ];
        }

        // Fallbacks if no user found
        return [
            'name' => '',
            'status' => ''
        ];
    }


    /**
     * Generate secure key for testing purposes
     * This endpoint should be removed in production
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateSecureKey(Request $request): JsonResponse
    {
        if (config('app.env') === 'production') {
            return response()->json([
                'success' => false,
                'message' => 'This endpoint is not available in production'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'empId' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $empId = $request->input('empId');
        $secureKey = hash('sha256', $empId . config('app.key'));

        return response()->json([
            'success' => true,
            'message' => 'Secure key generated (for testing only)',
            'data' => [
                'empId' => $empId,
                'secure_key' => $secureKey
            ]
        ], 200);
    }

    /**
     * Employee Punch In
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function punchIn(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'todayDate'    => 'required|date_format:Y-m-d',
                'punchInTime'  => 'required|date_format:H:i:s',
                'empId'        => 'required|string|max:50',
                'secure'       => 'required|string|min:8',
                'punchInLat'   => 'required|numeric',
                'punchInLong'  => 'required|numeric',
                'work_location_status' => 'required|in:WFH,WFO',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId      = $request->input('empId');
            $secureKey  = $request->input('secure');
            $work_location_status = $request->input('work_location_status');  // 'WFH' or 'WFO'

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch userId from the employee table based on empId
            $user = DB::table('employees')
                ->where('employee_id', $empId)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                    'error_code' => 'EMPLOYEE_NOT_FOUND'
                ], 404);
            }

            $userId = $user->empId;  // Assuming empId is the primary key in employees table

            // Save punch in data (assuming the table name is 'attendance')
            $punchData = [
                'empId'         => $empId,
                'userId'        => $userId,
                'present_date'  => $request->input('todayDate'),
                'in_time'       => $request->input('punchInTime'),
                'punchInLat'    => $request->input('punchInLat'),
                'punchInLong'   => $request->input('punchInLong'),
                'present_status' => 'present',
                'work_location_status' => $work_location_status,
                'status'        => '1',
                'login_status'   => 'yes',
                'lunch_status'   => 'no',
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            // You may want to check if already punched in for today in 'attendance' table
            $existing = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $request->input('todayDate'))
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already punched in for today.',
                    'error_code' => 'ALREADY_PUNCHED_IN'
                ], 409);
            }

            DB::table('attendance')->insert($punchData);

            return response()->json([
                'success' => true,
                'message' => 'Punch in successful',
                'data'    => $punchData
            ], 200);

        } catch (\Exception $e) {
            Log::error('Punch in error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while punching in',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Employee Punch Out
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function punchOut(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'todayDate'     => 'required|date_format:Y-m-d',
                'punchOutTime'  => 'required|date_format:H:i:s',
                'empId'         => 'required|string|max:50',
                'secure'        => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId        = $request->input('empId');
            $secureKey    = $request->input('secure');
            $todayDate    = $request->input('todayDate');
            $punchOutTime = $request->input('punchOutTime');

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch today's attendance
            $attendance = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $todayDate)
                ->first();

            if (!$attendance || !$attendance->in_time) {
                return response()->json([
                    'success' => false,
                    'message' => 'Punch in record not found for today.',
                    'error_code' => 'PUNCH_IN_NOT_FOUND'
                ], 404);
            }

            $punchInTime = $attendance->in_time;

            // Calculate lunch break
            $lunchBreak = 0;
            if ($attendance->lunch_in && $attendance->lunch_out) {
                $lunchBreak = (strtotime($attendance->lunch_out) - strtotime($attendance->lunch_in)) / 3600;
            }

            // Calculate other breaks
            $breaks = DB::table('employee_office_break')
                ->where('empId', $empId)
                ->where('break_date', $todayDate)
                ->get();

            $totalBreakHours = 0;
            foreach ($breaks as $b) {
                if ($b->break_in && $b->break_out) {
                    $totalBreakHours += (strtotime($b->break_out) - strtotime($b->break_in)) / 3600;
                }
            }

            // Total presence
            $totalPresence = (strtotime($punchOutTime) - strtotime($punchInTime)) / 3600;

            // Net working hours
            $netWorkingHours = $totalPresence - $lunchBreak - $totalBreakHours;

            // Convert hours to HH:MM:SS
            $netWorkingTime = gmdate('H:i:s', round($netWorkingHours * 3600));
            $lunchBreakTime = gmdate('H:i:s', round($lunchBreak * 3600));

            // Update attendance table
            DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $todayDate)
                ->update([
                    'out_time'            => $punchOutTime,
                    'login_status'        => 'punch_out',
                    'total_working_hours' => $netWorkingTime,
                    'total_lunch_time'    => $lunchBreakTime,
                    'updated_at'          => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Punch out successful',
                'data'    => [
                    'employee_id'       => $empId,
                    'date'              => $todayDate,
                    'punch_in_time'     => $punchInTime,
                    'punch_out_time'    => $punchOutTime,
                    'total_presence'    => gmdate('H:i:s', round($totalPresence * 3600)),
                    'lunch_break'       => $lunchBreakTime,
                    'other_breaks'      => gmdate('H:i:s', round($totalBreakHours * 3600)),
                    'net_working_hours' => $netWorkingTime
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Punch out error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while punching out',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }



    /**
     * Employee Lunch In
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function lunchIn(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'todayDate'    => 'required|date_format:Y-m-d',
                'lunchInTime'  => 'required|date_format:H:i:s',
                'empId'        => 'required|string|max:50',
                'secure'       => 'required|string|min:8',
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

            // Check if the attendance record exists for today
            $existing = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $request->input('todayDate'))
                ->first();

            if (!$existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found for today.',
                    'error_code' => 'ATTENDANCE_NOT_FOUND'
                ], 404);
            }

            // Check the current lunch status
            if ($existing->lunch_status === 'complete') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lunch already completed for today.',
                    'error_code' => 'LUNCH_ALREADY_DONE'
                ], 400);
            }

            if ($existing->lunch_status === 'yes') {
                return response()->json([
                    'success' => true,
                    'message' => 'You are already in lunch.',
                    'data'    => [
                        'employee_id'   => $empId,
                        'date'          => $request->input('todayDate'),
                        'lunch_in_time' => $existing->lunch_in,
                    ]
                ], 200);
            }

            // If lunch_status is 'no', update the lunch_in time
            $updated = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $request->input('todayDate'))
                ->update([
                    'lunch_in'    => $request->input('lunchInTime'),
                    'lunch_status'=> 'yes',
                    'updated_at'  => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update lunch time.',
                    'error_code' => 'UPDATE_FAILED'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lunch in successful',
                'data'    => [
                    'employee_id'   => $empId,
                    'date'          => $request->input('todayDate'),
                    'lunch_in_time' => $request->input('lunchInTime'),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Lunch in error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while lunch in',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Employee Lunch Out
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function lunchOut(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'todayDate'    => 'required|date_format:Y-m-d',
                'lunchOutTime' => 'required|date_format:H:i:s',
                'empId'        => 'required|string|max:50',
                'secure'       => 'required|string|min:8',
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

            // Check if the attendance record exists for today
            $existing = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $request->input('todayDate'))
                ->first();

            if (!$existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found for today.',
                    'error_code' => 'ATTENDANCE_NOT_FOUND'
                ], 404);
            }

            if ($existing->login_status === 'punch_out') {
                return response()->json([
                    'success' => false,
                    'message' => 'Already logged out. Cannot proceed with lunch out.',
                    'error_code' => 'ALREADY_LOGGED_OUT'
                ], 400);
            }

            // Check if lunch has already been completed
            if ($existing->lunch_status === 'complete') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lunch already completed for today.',
                    'error_code' => 'LUNCH_ALREADY_DONE'
                ], 400);
            }

            // Check if lunch is still in progress (lunch_status == 'yes')
            if ($existing->lunch_status === 'no') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lunch has not started yet.',
                    'error_code' => 'LUNCH_NOT_STARTED'
                ], 400);
            }

            // Calculate total lunch time
            $lunchInTime = Carbon::parse($existing->lunch_in);
            $lunchOutTime = Carbon::parse($request->input('lunchOutTime'));

            $totalLunchTime = $lunchInTime->diffInMinutes($lunchOutTime); // Get the difference in minutes

            // Update the lunch out time and mark lunch as complete
            $updated = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $request->input('todayDate'))
                ->update([
                    'lunch_out'     => $request->input('lunchOutTime'),
                    'lunch_status'  => 'complete',
                    'total_lunch_time' => $totalLunchTime,
                    'updated_at'    => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update lunch out time.',
                    'error_code' => 'UPDATE_FAILED'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Lunch out successful',
                'data'    => [
                    'employee_id'   => $empId,
                    'date'          => $request->input('todayDate'),
                    'lunch_out_time' => $request->input('lunchOutTime'),
                    'total_lunch_time' => $totalLunchTime . ' minutes',
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Lunch out error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while lunch out',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Employee Break In
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function breakIn(Request $request): JsonResponse
    {

        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'break_date'   => 'required|date_format:Y-m-d',
                'break_in'     => 'required|date_format:H:i:s',
                'empId'        => 'required|string|max:50',
                'secure'       => 'required|string|min:8',
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
            $breakDate = $request->input('break_date');
            $breakIn   = $request->input('break_in');

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch userId from employees table
            $user = DB::table('employees')
                ->where('employee_id', $empId)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                    'error_code' => 'EMPLOYEE_NOT_FOUND'
                ], 404);
            }

            $userId = $user->empId;

            // Check if a break is already in progress (status = start) for today
            $existing = DB::table('employee_office_break')
                ->where('empId', $empId)
                ->where('break_date', $breakDate)
                ->where('break_status', 'start')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Break already in progress for today.',
                    'error_code' => 'BREAK_ALREADY_IN'
                ], 409);
            }


            // Insert break start record
            $breakData = [
                'empId'        => $empId,
                'userId'       => $userId,
                'break_date'   => $breakDate,
                'break_in'     => $breakIn,
                'break_status' => 'start',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];


            $id = DB::table('employee_office_break')->insertGetId($breakData);
            //  DB::table('attendance')->insert($breakData);

            return response()->json([
                'success' => true,
                'message' => 'Break started successfully',
                'data'    => array_merge(['id' => $id], $breakData)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Break in error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while starting the break',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }


    /**
     * Employee Break Out
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function breakOut(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'break_date'    => 'required|date_format:Y-m-d',
                'breakOutTime'  => 'required|date_format:H:i:s',
                'empId'         => 'required|string|max:50',
                'secure'        => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId      = $request->input('empId');
            $secureKey  = $request->input('secure');
            $breakDate  = $request->input('break_date');
            $breakOut   = $request->input('breakOutTime');

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch the active break record (status = start)
            $break = DB::table('employee_office_break')
                ->where('empId', $empId)
                ->where('break_date', $breakDate)
                ->where('break_status', 'start')
                ->first();

            if (!$break) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active break found for today or already ended.',
                    'error_code' => 'BREAK_NOT_FOUND'
                ], 404);
            }

            // Calculate total break time in seconds
            $breakInTime = strtotime($break->break_in);
            $breakOutTime = strtotime($breakOut);
            $totalBreakSeconds = $breakOutTime - $breakInTime;

            // Convert seconds to H:i:s format
            $hours = floor($totalBreakSeconds / 3600);
            $minutes = floor(($totalBreakSeconds % 3600) / 60);
            $seconds = $totalBreakSeconds % 60;
            $totalBreakTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

            // Update break record
            DB::table('employee_office_break')
                ->where('id', $break->id)
                ->update([
                    'break_out'        => $breakOut,
                    'break_status'     => 'end',
                    'total_break_time' => $totalBreakTime,
                    'updated_at'       => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Break ended successfully',
                'data'    => [
                    'employee_id'       => $empId,
                    'date'              => $breakDate,
                    'break_in_time'     => $break->break_in,
                    'break_out_time'    => $breakOut,
                    'total_break_time'  => $totalBreakTime
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Break out error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while ending the break',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get company holiday list for an employee
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompanyHolidays(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'empId' => 'required|string|max:50',
                'secure' => 'required|string|min:8',
                'year' => 'required|digits:4|integer|min:1900|max:2099', // Validate year
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $employeeId = $request->input('empId');
            $secureKey  = $request->input('secure');
            $year       = $request->input('year');

            // Security validation
            if (!$this->validateSecureAccess($employeeId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch added_by from employees table
            $employee = DB::table('employees')
                ->where('employee_id', $employeeId)
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.',
                    'error_code' => 'EMPLOYEE_NOT_FOUND'
                ], 404);
            }

            $addedBy = $employee->added_by;

            // Fetch holidays for this added_by and year
            $holidays = DB::table('holidays')
                ->where('added_by', $addedBy)
                ->whereYear('holidayDate', $year) // Filter by year
                ->select('id', 'holidayName', 'holidayDate', 'holidayType', 'holidayDescription')
                ->orderBy('holidayDate', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Holiday list fetched successfully',
                'data'    => $holidays
            ], 200);

        } catch (\Exception $e) {
            Log::error('Holiday list error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip'    => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching holidays',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get today's overview for an employee: total working time, break time, lunch time
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTodaysAttendance(Request $request): JsonResponse
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'empId'      => 'required|string|max:50',
                'todayDate'  => 'required|date_format:Y-m-d',
                'currentTime'=> 'required|date_format:H:i:s',
                'secure'     => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $empId       = $request->input('empId');
            $todayDate   = $request->input('todayDate');
            $currentTime = $request->input('currentTime');
            $secureKey   = $request->input('secure');

            // Security validation
            if (!$this->validateSecureAccess($empId, $secureKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // Fetch attendance record
            $attendance = DB::table('attendance')
                ->where('empId', $empId)
                ->where('present_date', $todayDate)
                ->first();

            if (!$attendance || !$attendance->in_time) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance record not found for today.',
                    'error_code' => 'ATTENDANCE_NOT_FOUND'
                ], 404);
            }

            $inTime  = strtotime($attendance->in_time);
            $outTime = $attendance->out_time ? strtotime($attendance->out_time) : strtotime($currentTime);

            // Total presence
            $totalPresenceSeconds = max(0, $outTime - $inTime);

            // Lunch break (only subtract if lunch_status is 'complete')
            $lunchSeconds = 0;
            if ($attendance->lunch_status === 'complete' && $attendance->lunch_in && $attendance->lunch_out) {
                $lunchSeconds = strtotime($attendance->lunch_out) - strtotime($attendance->lunch_in);
            }

            // Total other breaks
            $breaks = DB::table('employee_office_break')
                ->where('empId', $empId)
                ->where('break_date', $todayDate)
                ->get();

            $totalBreakSeconds = 0;
            foreach ($breaks as $break) {
                if ($break->break_in && $break->break_out) {
                    $totalBreakSeconds += strtotime($break->break_out) - strtotime($break->break_in);
                } elseif ($break->break_in && !$break->break_out && $break->break_status === 'start') {
                    $totalBreakSeconds += strtotime($currentTime) - strtotime($break->break_in);
                }
            }

            // Net working seconds
            $netWorkingSeconds = $totalPresenceSeconds - $lunchSeconds - $totalBreakSeconds;

            // Format seconds to H:i:s
            $formatSeconds = function($seconds) {
                $seconds = max(0, $seconds);
                $h = floor($seconds / 3600);
                $m = floor(($seconds % 3600) / 60);
                $s = $seconds % 60;
                return sprintf('%02d:%02d:%02d', $h, $m, $s);
            };

            return response()->json([
                'success' => true,
                'message' => 'Today\'s overview fetched successfully',
                'data'    => [
                    'empId'             => $empId,
                    'date'              => $todayDate,
                    'total_office_present_time' => $formatSeconds($totalPresenceSeconds),
                    'lunch_break'       => $formatSeconds($lunchSeconds),
                    'other_breaks'      => $formatSeconds($totalBreakSeconds),
                    'net_working_time'  => $formatSeconds($netWorkingSeconds),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Today overview error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'ip'      => $request->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching today\'s overview',
                'error_code' => 'INTERNAL_SERVER_ERROR'
            ], 500);
        }
    }

}
