<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class LeaveManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Calculate working days between two dates based on company's weekly schedule
     */
    private function calculateWorkingDays($startDate, $endDate, $userId)
    {
        // Fetch working days from weekly_schedules where status = 'open'
        $workingDays = DB::table('weekly_schedules')
            ->where('added_by', $userId)
            ->where('status', 'open')
            ->pluck('day')
            ->toArray();

        // If no schedule found, default to Monday-Friday
        if (empty($workingDays)) {
            $workingDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        }

        // Convert to lowercase for comparison
        $workingDays = array_map('strtolower', $workingDays);

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $totalWorkingDays = 0;

        // Iterate through each day and count working days
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayName = strtolower($date->format('l')); // Get day name (monday, tuesday, etc.)
            if (in_array($dayName, $workingDays)) {
                $totalWorkingDays++;
            }
        }

        return $totalWorkingDays;
    }

    public function LeaveManagement()
    {
        $userId = currentOwnerId();

        $employees = DB::table('employees')
            ->join('users', 'users.id', '=', 'employees.empId')
            ->where('employees.added_by', $userId)
            ->select('employees.employee_id', 'users.name')
            ->get();

        // Fetch leave requests for the current user
        $leaves = Leave::where('added_by', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalLeaves = $leaves->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();

        return view('User.LeaveManagement', compact('employees', 'leaves', 'totalLeaves', 'pendingLeaves', 'approvedLeaves', 'rejectedLeaves'));
    }

    public function storeLeave(Request $request)
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();
			$uType = currentOwnerUserType();

            $request->validate([
                'employee_id' => 'required|string',
                'leave_type' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string|max:500',
            ]);

            $employee = DB::table('employees')
                ->join('users', 'users.id', '=', 'employees.empId')
                ->where('employees.employee_id', $request->employee_id)
                ->where('employees.added_by', $userId)
                ->select('employees.employee_id', 'users.name', 'employees.empId')
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found or unauthorized to assign leave.'
                ], 404);
            }

            $totalDays = $this->calculateWorkingDays($request->start_date, $request->end_date, $userId);

            $leave = Leave::create([
                'company_id'   => $userId,
                'added_by'     => $userId,
                'u_type'       => $uType ?? 'user', // Fallback if null
                'employee_id'  => $employee->employee_id,
                'emp_name'     => $employee->name,
                'emp_id'       => $employee->empId,
                'leave_type'   => $request->leave_type,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'total_days'   => $totalDays,
                'reason'       => $request->reason,
                'status'       => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request submitted successfully.',
                'data' => [
                    'leave_id'      => $leave->id,
                    'employee_name' => $employee->name,
                    'leave_type'    => $leave->leave_type,
                    'total_days'    => $leave->total_days,
                    'status'        => $leave->status
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Debug for development: show full message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // Show actual error
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);
        }
    }

    public function updateLeaveStatus(Request $request, $id)
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();

            $request->validate([
                'status' => 'required|in:approved,rejected',
                'admin_remarks' => 'nullable|string|max:500'
            ]);

            $leave = Leave::where('id', $id)
                ->where('added_by', $userId)
                ->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found or unauthorized.'
                ], 404);
            }

            $leave->update([
                'status' => $request->status,
                'admin_remarks' => $request->admin_remarks,
                'approved_by' => $userId,
                'approved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave status updated successfully.',
                'data' => [
                    'leave_id' => $leave->id,
                    'status' => $leave->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLeave($id)
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();

            $leave = Leave::where('id', $id)
                ->where('added_by', $userId)
                ->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found or unauthorized.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateLeave(Request $request, $id)
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();

            $request->validate([
                'employee_id' => 'required|string',
                'leave_type' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string|max:500',
            ]);

            $leave = Leave::where('id', $id)
                ->where('added_by', $userId)
                ->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found or unauthorized.'
                ], 404);
            }

            $employee = DB::table('employees')
                ->join('users', 'users.id', '=', 'employees.empId')
                ->where('employees.employee_id', $request->employee_id)
                ->where('employees.added_by', $userId)
                ->select('employees.employee_id', 'users.name', 'employees.empId')
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found or unauthorized.'
                ], 404);
            }

            $totalDays = $this->calculateWorkingDays($request->start_date, $request->end_date, $user->id);

            $leave->update([
                'employee_id' => $employee->employee_id,
                'emp_name' => $employee->name,
                'emp_id' => $employee->empId,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'status' => 'pending' // Reset status to pending when edited
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request updated successfully.',
                'data' => $leave
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteLeave($id)
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();

            $leave = Leave::where('id', $id)
                ->where('added_by', $userId)
                ->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found or unauthorized.'
                ], 404);
            }

            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave request deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calculateLeaveDays(Request $request)
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();

            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $totalDays = $this->calculateWorkingDays($request->start_date, $request->end_date, $userId);

            return response()->json([
                'success' => true,
                'total_days' => $totalDays
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getWorkingDays()
    {
        try {
            $user = Auth::user();
			$userId = currentOwnerId();
            
            // Fetch working days from weekly_schedules where status = 'open'
            $workingDays = DB::table('weekly_schedules')
                ->where('added_by', $userId)
                ->where('status', 'open')
                ->pluck('day')
                ->toArray();

            // If no schedule found, default to Monday-Friday
            if (empty($workingDays)) {
                $workingDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            }

            // Convert to lowercase and map to numbers (0=Sunday, 1=Monday, etc.)
            $workingDayNumbers = [];
            $dayMapping = [
                'sunday' => 0,
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6
            ];

            foreach ($workingDays as $day) {
                $dayLower = strtolower($day);
                if (isset($dayMapping[$dayLower])) {
                    $workingDayNumbers[] = $dayMapping[$dayLower];
                }
            }

            return response()->json([
                'success' => true,
                'working_days' => $workingDayNumbers,
                'working_day_names' => array_map('strtolower', $workingDays)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
