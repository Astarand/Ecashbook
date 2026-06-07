<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

class UserEmployeeController extends Controller
{
    public function todayAttendance()
    {
        $userId = Auth::user()->id;
        $today = date('Y-m-d');

        // 🕒 Fetch Attendance
        $attendance = DB::table('attendance')
            ->where('userId', $userId)
            ->whereDate('present_date', $today)
            ->first();

        // 🍵 Fetch Breaks
        $breaks = DB::table('employee_office_break')
            ->where('userId', $userId)
            ->whereDate('break_date', $today)
            ->get();

        // 🧮 Calculate Total Break Duration (if any)
        $totalBreakMinutes = 0;
        foreach ($breaks as $break) {
            if ($break->total_break_time) {
                // assuming total_break_time saved as "HH:MM:SS" or "15 min"
                if (preg_match('/(\d+):(\d+):(\d+)/', $break->total_break_time, $m)) {
                    $totalBreakMinutes += ($m[1] * 60) + $m[2];
                } elseif (preg_match('/(\d+)\s*min/', $break->total_break_time, $m)) {
                    $totalBreakMinutes += $m[1];
                }
            } elseif ($break->break_in && $break->break_out) {
                $start = strtotime($break->break_in);
                $end = strtotime($break->break_out);
                $totalBreakMinutes += ($end - $start) / 60;
            }
        }

        $totalBreakHours = floor($totalBreakMinutes / 60);
        $totalBreakRemain = $totalBreakMinutes % 60;
        $formattedTotalBreak = sprintf("%dh %02dm", $totalBreakHours, $totalBreakRemain);

        // If no attendance, mark absent
        if (!$attendance) {
            return response()->json(['status' => 'absent']);
        }

        return response()->json([
            'status' => 'present',
            'data' => [
                // Attendance
                'in_time' => $attendance->in_time,
                'out_time' => $attendance->out_time,
                'lunch_in' => $attendance->lunch_in,
                'lunch_out' => $attendance->lunch_out,
                'total_working_hours' => $attendance->total_working_hours,
                'total_lunch_time' => $attendance->total_lunch_time,
                'present_date' => date('d M, Y', strtotime($attendance->present_date)),

                // Breaks
                'breaks' => $breaks,
                'total_break_time' => $formattedTotalBreak,
                'break_count' => count($breaks),
            ]
        ]);
    }

    public function getEmployeeTasks()
    {
        $userId = Auth::user()->id;

        // Step 1: Find the employee_id for the logged-in user
        $employee = DB::table('employees')
            ->where('empId', $userId)
            ->select('employee_id')
            ->first();

        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found']);
        }

        // Step 2: Fetch tasks for this employee
        $tasks = DB::table('employee_task_managment')
            ->where('employee_id', $employee->employee_id)
            ->orderBy('due_date', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }


    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string|in:Pending,In Progress,Completed'
        ]);

        $task = DB::table('employee_task_managment')->where('id', $request->id)->first();

        if (!$task) {
            return response()->json(['status' => 'error', 'message' => 'Task not found']);
        }

        // Prevent re-update if already completed
        if ($task->status === 'Completed') {
            return response()->json(['status' => 'error', 'message' => 'Task already completed']);
        }

        $updateData = ['status' => $request->status, 'updated_at' => now()];

        if ($request->status === 'Completed') {
            $updateData['completed_date'] = now();
        }

        DB::table('employee_task_managment')->where('id', $request->id)->update($updateData);

        return response()->json(['status' => 'success', 'message' => 'Task updated successfully']);
    }



}
