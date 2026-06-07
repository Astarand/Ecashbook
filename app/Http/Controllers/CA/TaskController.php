<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Task_managements;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Models\TaskCategory;
use App\Helpers\AuditLogger;

class TaskController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function Index()
    {
		
        $title = 'Task';
		$userId = Auth::user()->id;
		
		if (Auth::user() && Auth::user()->u_type == 1) {

			$tasks = DB::table('users')
				->leftJoin('task_managements', 'users.id', '=', 'task_managements.company_id')
				->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
				->select(
					'users.*',
					'task_managements.*',
					DB::raw('COALESCE(company_profiles.comp_name, users.name) as name')
				)
				->where('task_managements.added_by', $userId)
				->orderBy('users.created_at', 'desc')
				->paginate(10);

		} else if (Auth::user() && Auth::user()->u_type == 4) {

			$tasks = DB::table('users')
				->leftJoin('task_managements', 'users.id', '=', 'task_managements.company_id')
				->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
				->select(
					'users.*',
					'task_managements.*',
					DB::raw('COALESCE(company_profiles.comp_name, users.name) as name')
				)
				->where('task_managements.emp_id', $userId)
				->orderBy('users.created_at', 'desc')
				->paginate(10);
		}
        
        $tasks_pagination = $tasks;

       
       $array = array();
		foreach($tasks as $k => $val)
        {
            $array[$val->id]['id'] = $val->id;
            $array[$val->id]['u_type'] = $val->u_type;
            $array[$val->id]['name'] = $val->name;
            $array[$val->id]['phone'] = $val->phone;
            $array[$val->id]['status'] = $val->status;
            $array[$val->id]['email'] = $val->email;
            $array[$val->id]['task_id'] = $val->task_id;
            $array[$val->id]['task_date'] = $val->task_date;
            $array[$val->id]['task_time'] = $val->task_time;
            $array[$val->id]['task_category'] = $val->task_category;
            $array[$val->id]['due_date'] = $val->due_date;
            $array[$val->id]['project_status'] = $val->project_status;

            // Get employee name
            $employee_name = User::where('id', $val->emp_id)->value('name');
            $array[$val->id]['empname'] = $employee_name;

            // Get task category name
            $category_name = DB::table('task_category')
                                ->where('id', $val->task_category)
                                ->value('task_category_name');
            $array[$val->id]['task_category_name'] = $category_name;
        }


		$tasks = json_decode(json_encode($array));
        // echo "<pre>"; print_r($tasks);exit;
        //$this->middleware('auth');
        return view('Ca.task-list')->with([
            'title' =>$title,
			'tasks'=>$tasks,
			'tasks_pagination' =>$tasks_pagination,

        ]);
    }

    protected function validatorTask(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
            //'task_date' => 'required',
            //'task_time' => 'required',
            //'company_id' => 'required',
            'task_category' => 'required',
            // 'task_sub_category' => 'required',
            //'gov_fees' => 'required',
            //'services_charges' => 'required',
            //'task_team' => 'required',
            'emp_id' => 'required',
			'due_date'  => 'required',
            'project_priority' => 'required',
            'project_status' => 'required'				
        ]
		);
    }

    protected function createTask(array $data)
    {
		//print_r($data);exit;
        return Task_managements::create([
            'userId' => Auth::user()->id,
            'task_id' =>'',
            'task_date' => $data['task_date'],
            'task_time' => $data['task_time'],
            'company_id' => $data['company_id'],
			'task_category' => $data['task_category'],
			// 'task_sub_category' => $data['task_sub_category'],
			'agent_id' => $data['agent_id'],
			'gov_fees' => $data['gov_fees'],
			'services_charges' => $data['services_charges'],
            'total_amount' => $data['total_amount'],
			// 'advance_payment' => $data['advance_payment'],
			// 'due_amount' => $data['due_amount'],
			//'task_team' => $data['task_team'],
			'emp_id' => $data['emp_id'],
            'due_date' => $data['due_date'],
			'project_priority' => $data['project_priority'],
			'message' => $data['message'],
			'project_status' => $data['project_status'],
            'added_by' => Auth::user()->id,
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getcat(Request $request){
		
		//$term = $btbid->term;
		$taskcatid=$request;
		$taskcat = DB::table('task_quotes')
		->select(DB::raw('task_quotes.id,task_quotes.govfee,task_quotes.service_charge'))
		->where('task_quotes.task_cat','=',$request->id)
		->where('task_quotes.is_delete','=','no')
		->get()->toArray();
		//echo "<pre>";print_r($taskcat);exit;
		$taskcat = isset($taskcat[0])?$taskcat[0]:"";
		//$taskcat = json_decode(json_encode($array));
		echo json_encode($taskcat); 
	}

    public function AddTask()
    {

        $agents = DB::table('busi_agents')
                ->select(DB::raw('busi_agents.id,busi_agents.agent_name'))
                ->where('added_by','=',Auth::user()->id)
                ->get()->toArray();

		$company = DB::table('users')
					->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
					->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
					->select(
						'users.id',
						DB::raw('COALESCE(company_profiles.comp_name, users.name) as name')
					)
					->where('users.u_type', 2)
					->where(function ($query) {
						$query->where('users.ca_add_by', Auth::id())
							  ->orWhere(function ($q) {
								  $q->where('ca_assigns.ca_assign_status', 1)
									->where('ca_assigns.ca_id', Auth::id());
							  });
					})
					->get();
        
        $employees = DB::table('users')
                ->select(DB::raw('users.id,users.name'))
                ->where('u_type','=',4)
                ->where('ca_add_by','=',Auth::user()->id)
                ->get()->toArray();  
        return view('Ca.add-task')->with([
            'agents' => $agents,
            'company'=>$company,
            'employees'=>$employees

        ]);
    }

    public function save_task(Request $request)  {  
        //echo "<pre>";print_r($request);exit;
    $emp_permission = ($request->emp_permission);
    $validation = $this->validatorTask($request->all());
    if ($validation->fails())  {  
        return response()->json($validation->errors()->toArray());
    }
    else{
        $insertTask = $this->createTask($request->all());
        $taskId = DB::getPdo()->lastInsertId();
        $update = DB::table('task_managements')
            ->where('id', $taskId)
            ->update(
                array(
                        'task_id' => str_pad($taskId, 8, '0', STR_PAD_LEFT),
                )
            );

        if ($insertTask){
            $msg = array(
                'status' => 'success',
                'class' => 'succ',
                'redirect' => url('/task-list'),
                'message' => 'Task added successfully'
            );
            return response()->json($msg);	
        }else{
            $msg = array(
                'status' => 'error',
                'class' => 'err',
                'redirect' => url('/'),
                'message' => 'Task add failed'
            );
            return response()->json($msg);	
        }
            
    }	
}

public function edit_task($taskId)
{  
    $taskId = base64_decode($taskId);

    $task = DB::table('task_managements')
        ->where('id', $taskId)
        ->first();   // better than get()[0]

    if (!$task) {
        abort(404);
    }

    $userId = $task->userId ?? 0;
    $authId = currentOwnerId();   // your global helper

    $agents = DB::table('busi_agents')
        ->select('id','agent_name')
        ->where('added_by', $userId)
        ->get();

    $company = DB::table('users')
        ->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
        ->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
        ->select(
            'users.id',
            DB::raw('COALESCE(company_profiles.comp_name, users.name) as name')
        )
        ->where('users.u_type', 2)
        ->where(function ($query) use ($authId) {   // ✅ important
            $query->where('users.ca_add_by', $authId)
                  ->orWhere(function ($q) use ($authId) {
                      $q->where('ca_assigns.ca_assign_status', 1)
                        ->where('ca_assigns.ca_id', $authId);
                  });
        })
        ->get();

    $employees = DB::table('users')
        ->select('id','name')
        ->where('u_type', 4)
        ->where('ca_add_by', $userId)
        ->get(); 

    return view('Ca.edit-task')->with([
        'task' => $task,
        'agents' => $agents,
        'company' => $company,
        'employees' => $employees,		
        'taskId' => $taskId
    ]); 
}


public function update_task(Request $request)  {  
        
   // echo "<pre>";print_r($request->all());exit;
    $taskId = $request->id;
    
    $validation = $this->validatorTask($request->all());
    if ($validation->fails())  {  
        return response()->json($validation->errors()->toArray());
    }
    else{
		// Fetch OLD task data
		$task = DB::table('task_managements')->where('id', $taskId)->first();
		$old = (array) $task;
		$newData = [
			'task_date' => $request->task_date,
			'task_time' => $request->task_time,
			'gov_fees' => $request->gov_fees,
			'services_charges' => $request->services_charges,
			'total_amount' => $request->total_amount,
			'due_date' => $request->due_date,
			'project_priority' => $request->project_priority,
			'message' => $request->message,
			'project_status' => $request->project_status   
		];
        //start update task
        $update = DB::table('task_managements')
                ->where('id', $taskId)
                ->update(
                     array( 
                        'task_date' => $request->task_date,
                        'task_time' => $request->task_time,
                        'company_id' => $request->company_id,
                        'task_category' =>$request->task_category,
                        'task_sub_category' => $request->task_sub_category,
                        'agent_id' => $request->agent_id,
                        'gov_fees' => $request->gov_fees,
                        'services_charges' => $request->services_charges,
                        'total_amount' => $request->total_amount,
                        // 'advance_payment' =>$request->advance_payment,
                        // 'due_amount' => $request->due_amount,
                        //'task_team' => $request->task_team,
                        'emp_id' => $request->emp_id,
                        'due_date' => $request->due_date,
                        'project_priority' => $request->project_priority,
                        'message' => $request->message,
                        'project_status' => $request->project_status                          

                            
                     )
                );
        if ($update)
		{
			$changedOld = [];
			$changedNew = [];

			foreach ($newData as $key => $value) {
				if (!array_key_exists($key, $old)) {
					continue;
				}
				$oldValue = $old[$key];
				$newValue = $value;
				// Normalize date fields
				if (in_array($key, ['due_date', 'task_date'])) {
					$oldValue = \Carbon\Carbon::parse($oldValue)->format('Y-m-d H:i');
					$newValue = \Carbon\Carbon::parse($newValue)->format('Y-m-d H:i');
				}
				if ($oldValue != $newValue) {
					$label = ucwords(str_replace('_', ' ', $key));
					$changedOld[$label] = $oldValue;
					$changedNew[$label] = $newValue;
				}
			}

			if (!empty($changedNew)) {
				AuditLogger::logEntry(
					action: 'update',
					module: 'Task Management',
					description: "Task updated: {$task->task_id}",
					oldData: $changedOld,
					newData: $changedNew
				);
			}
            $msg = array(
                'status' => 'success',
                'class' => 'succ',
                'redirect' => url('/task-list'),
                'message' => 'Record details updated'
            );
            return response()->json($msg);	
        }
        else{
                $msg = array(
                    'status' => 'error',
                    'class' => 'err',
                    'redirect' => url('/'),
                    'message' => 'Update not success!'
                );
                return response()->json($msg);	
        }
    }	
}


public function view_task($taskId)
{  
    $taskId = base64_decode($taskId);

    $task = DB::table('task_managements')
        ->where('id', $taskId)
        ->first();   // better than get()[0]

    if (!$task) {
        abort(404);
    }

    $userId = $task->userId ?? 0;
    $authId = currentOwnerId();   // your global helper

    $agents = DB::table('busi_agents')
        ->select('id','agent_name')
        ->where('added_by', $userId)
        ->get();

    $company = DB::table('users')
        ->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
        ->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
        ->select(
            'users.id',
            DB::raw('COALESCE(company_profiles.comp_name, users.name) as name')
        )
        ->where('users.u_type', 2)
        ->where(function ($query) use ($authId) {   // ✅ important
            $query->where('users.ca_add_by', $authId)
                  ->orWhere(function ($q) use ($authId) {
                      $q->where('ca_assigns.ca_assign_status', 1)
                        ->where('ca_assigns.ca_id', $authId);
                  });
        })
        ->get();

    $employees = DB::table('users')
        ->select('id','name')
        ->where('u_type', 4)
        ->where('ca_add_by', $userId)
        ->get(); 

    return view('Ca.view-task')->with([
        'task' => $task,
        'agents' => $agents,
        'company' => $company,
        'employees' => $employees,		
        'taskId' => $taskId
    ]); 
}

	//Delete task
	public function delTask(Request $request)
    {
		$task = DB::table('task_managements')->where('id', $request->id)->first();
		$delTask = DB::table('task_managements')->where('id', $request->id)->delete();
		if($delTask){
			 AuditLogger::logEntry(
				action: 'delete',
				module: 'Task Management',
				description: "Task deleted: {$task->task_id}",
				oldData: null,
				newData: null
			);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/task-list'),
				'message' => 'Task deleted successfully.'
			);
			return response()->json($msg);
        }else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/task-list'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
        }
    }
    
    public function TaskQuoteset()
    {
        return view('ca.task-quote-set');
    }
    public function AddNewQuote()
    {
        return view('ca.add-new-quote');
    }

    public function destroy($id)
    {
        try {
            $deleted = DB::table('task_managements')->where('id', $id)->delete();

            if ($deleted === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Task not found or already deleted.'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete the task.'
            ]);
        }
    }

    public function fetchCategories()
    {
        $userId = Auth::user()->id;

        $categories = TaskCategory::where('add_by', 'admin')
                        ->orWhere('add_by', $userId)
                        ->orderBy('task_category_name')
                        ->get();

        return response()->json($categories);
    }


}
