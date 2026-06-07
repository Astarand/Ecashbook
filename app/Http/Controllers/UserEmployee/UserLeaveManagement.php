<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Employees;

class UserLeaveManagement extends Controller
{
    public function LeaveRequestList()
    {

        $userId = Auth::user()->id;


        // Fetch leave requests for the current user
        $leaves = Leave::where('emp_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalLeaves = $leaves->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();

        return view('Employee.UserEmployee.leave-requests-list',compact('leaves', 'totalLeaves', 'pendingLeaves', 'approvedLeaves', 'rejectedLeaves'));
    }

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

    public function calculateLeaveDays(Request $request)
    {
        try {
            $user = Auth::user();

            $employee = Employees::where('empId', $user->id)->first();
		    $authUserId = $employee->added_by;

            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $totalDays = $this->calculateWorkingDays($request->start_date, $request->end_date, $authUserId);

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

    public function storeLeave(Request $request)
    {
        try {
            $user = Auth::user();
            $employee = \DB::table('employees')
                    ->join('users', 'users.id', '=', 'employees.empId')
                    ->where('employees.empId', $user->id)
                    ->select('employees.*', 'users.name as user_name')
                    ->first();
            
		    $added_byId = $employee->added_by;
		    

            $request->validate([
                'leave_type' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string|max:500',
            ]);

            // $employee = DB::table('employees')
            //     ->join('users', 'users.id', '=', 'employees.empId')
            //     ->where('employees.employee_id', $employee_id)
            //     ->where('employees.added_by', $added_byId)
            //     ->select('employees.employee_id', 'users.name', 'employees.empId')
            //     ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found or unauthorized to assign leave.'
                ], 404);
            }

            $totalDays = $this->calculateWorkingDays($request->start_date, $request->end_date, $added_byId);

            $leave = Leave::create([
                'company_id'   => $added_byId,
                'added_by'     => $added_byId,
                'u_type'       => $user->u_type ?? 'user', 
                'employee_id'  => $employee->employee_id,
                'emp_name'     => $employee->user_name,
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

    public function getUserEmployeeLeave($id)
    {
        try {
            $user = Auth::user();

            $leave = Leave::where('id', $id)
                ->where('emp_id', $user->id)
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

    public function updateLeaveUserEmployee(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'leave_type' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'required|string|max:500',
            ]);

            $leave = Leave::where('id', $id)
                ->where('emp_id', $user->id)
                ->first();

            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave request not found or unauthorized.'
                ], 404);
            }

            $employee = \DB::table('employees')
                    ->join('users', 'users.id', '=', 'employees.empId')
                    ->where('employees.empId', $user->id)
                    ->select('employees.*', 'users.name as user_name')
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
                'emp_name' => $employee->user_name,
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

    public function deleteLeaveUserEmployee($id)
    {
        try {
            $user = Auth::user();

            $leave = Leave::where('id', $id)
                ->where('emp_id', $user->id)
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
}
