<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\AuditLogger;

class TaskManagementController extends Controller
{
    public function index()
    {
        $userId = currentOwnerId();
		checkCoreAccess('Payroll Management');
        // Fetch employees added by this user, join with users table to get employee name
        $employees = DB::table('employees')
            ->join('users', 'employees.empId', '=', 'users.id')
            ->where('employees.added_by', $userId)
            ->select('employees.employee_id', 'users.name')
            ->get();

        // Fetch tasks assigned by this user
        $assignedTasks = DB::table('employee_task_managment')
            ->join('employees', 'employee_task_managment.employee_id', '=', 'employees.employee_id')
            ->join('users', 'employees.empId', '=', 'users.id')
            ->where('employee_task_managment.added_by', $userId)
            ->select(
                'employee_task_managment.*',
                'users.name as employee_name'
            )
            ->orderBy('employee_task_managment.due_date', 'asc')
            ->get();
        // echo "<pre>"; print_r($assignedTasks); exit;

        return view('User.UserTaskManagement', compact('employees', 'assignedTasks'));
    }
	
	public function getTask($id)
	{
		$task = DB::table('employee_task_managment')
			->where('id', $id)
			->first();

		return response()->json($task);
	}
	
	public function storeTask(Request $request)
	{
		$userId = currentOwnerId();

		$request->validate([

			'title'            => 'required|array|min:1',
			'title.*'          => 'required|string|max:255',

			'priority'         => 'required|array|min:1',
			'priority.*'       => 'required|in:High,Medium,Low',

			'due_date'         => 'required|array|min:1',
			'due_date.*'       => 'required|date',

			'employee_id'      => 'required|exists:employees,employee_id',

			'description'      => 'nullable|string',

		], [

			'title.*.required'      => 'Task title is required.',
			'priority.*.required'   => 'Priority is required.',
			'due_date.*.required'   => 'Deadline is required.',

		]);

		$insertData = [];

		foreach ($request->title as $key => $taskTitle) {

			$insertData[] = [

				'title'        => $taskTitle,

				'employee_id'  => $request->employee_id,

				'priority'     => $request->priority[$key],

				'due_date'     => $request->due_date[$key],

				'description'  => $request->description,

				'status'       => 'Pending',

				'added_by'     => $userId,

				'created_at'   => now(),

				'updated_at'   => now(),
			];
		}

		DB::table('employee_task_managment')
			->insert($insertData);

		return response()->json([
			'status'  => true,
			'message' => 'Tasks assigned successfully!',
		]);
	}
	
	public function update(Request $request, $id)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'employee_id'  => 'required|exists:employees,employee_id',
            'priority'     => 'required|in:High,Medium,Low',
            'due_date'     => 'required|date',
            'description'  => 'nullable|string',
            'status'       => 'required|in:Pending,In Progress,Completed',
        ]);
		// Fetch OLD task data
		$task = DB::table('employee_task_managment')->where('id', $id)->first();
		$old = (array) $task;
		// Prepare NEW data
		$newData = [
			'title'        => $request->title,
			'priority'     => $request->priority,
			'due_date'     => $request->due_date,
			'description'  => $request->description,
			'status'       => $request->status,
		];

        DB::table('employee_task_managment')
            ->where('id', $id)
            ->update([
                'title'        => $request->title,
                'employee_id'  => $request->employee_id,
                'priority'     => $request->priority,
                'due_date'     => $request->due_date,
                'description'  => $request->description,
                'status'       => $request->status,
                'updated_at'   => now(),
            ]);

        if ($request->ajax()) {
			
			// Detect changes only
			$changedOld = [];
			$changedNew = [];
			foreach ($newData as $key => $value) {
				if (!array_key_exists($key, $old)) {
					continue;
				}
				$oldValue = $old[$key];
				$newValue = $value;
				// Normalize date fields
				if (in_array($key, ['due_date', 'created_at', 'updated_at'])) {
					$oldValue = \Carbon\Carbon::parse($oldValue)->format('Y-m-d H:i');
					$newValue = \Carbon\Carbon::parse($newValue)->format('Y-m-d H:i');
				}
				if ($oldValue != $newValue) {
					$label = ucwords(str_replace('_', ' ', $key));
					$changedOld[$label] = $oldValue;
					$changedNew[$label] = $newValue;
				}
			}

			// Audit log
			if (!empty($changedNew)) {
				AuditLogger::logEntry(
					action: 'update',
					module: 'Task Management',
					description: "Task updated: {$task->title}",
					oldData: $changedOld,
					newData: $changedNew
				);
			}
            return response()->json([
                'status'  => true,
                'message' => 'Task updated successfully!',
            ]);
        }

        return redirect()->route('user.TaskManagement')->with('success', 'Task updated successfully!');
    }

    public function destroy($id)
    {
		// Fetch task before delete
		$task = DB::table('employee_task_managment')->where('id', $id)->first();
		$oldData = [
			'task title'   => $task->title ?? null,
			'due date'     => $task->due_date ?? null,
			'priority'     => $task->priority ?? null,
		];
        $deleted = DB::table('employee_task_managment')->where('id', $id)->delete();

        if(request()->ajax()) {
			 AuditLogger::logEntry(
				action: 'delete',
				module: 'Task Management',
				description: "Task deleted: {$task->title}",
				oldData: $oldData,
				newData: null
			);
            return response()->json([
                'status' => $deleted ? true : false,
                'message' => $deleted ? 'Task deleted successfully!' : 'Task not found or already deleted.'
            ]);
        }

        return redirect()->route('user.TaskManagement')->with('success', 'Task deleted successfully!');
    }

}
