<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Company_profiles;
use App\Models\Ca_profiles;
use App\Models\Ca_assigns;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Notifications;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class CompanyAssignment extends Controller
{
    public function CompanyAssignment()
    {
        $userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$customerLists = DB::table('users')
				->select(DB::raw('users.*,
					ca_assigns.id as ca_assign_id,
					ca_assigns.request_for,
					ca_assigns.ca_assign_status,
					ca_assigns.ca_current_status,
					ca_assigns.created_at as requestedAt,
					company_profiles.*'))  // Select all fields from company_profiles
				->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
				->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
				->where('users.u_type', '=', 2)
				->where('ca_assigns.ca_assign_status', '=', 1)
				->where('ca_assigns.ca_current_status', '=', 0)  // Check if ca_current_status is 0
				->where('ca_assigns.ca_id', '=', $userId)  // Only select rows where ca_id matches the current authenticated user
				->orderBy('ca_assigns.created_at', 'desc')  // Order by ca_assigns created_at
				->get();



		// echo "<pre>"; print_r($customerLists);exit;

		$customerLists_pagination = $customerLists;

       $customers = DB::table('users')
				->select(DB::raw('users.*,
					ca_assigns.request_for,
					ca_assigns.ca_assign_status,
					ca_assigns.ca_current_status,
					ca_assigns.created_at as requestedAt,
					company_profiles.*'))  // Select all fields from company_profiles
				->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')  // Join ca_assigns first
				->leftJoin('company_profiles', 'company_profiles.userId', '=', 'ca_assigns.comp_id')  // Then join company_profiles
				->where('users.u_type', '=', 2)
				->where('ca_assigns.ca_assign_status', '=', 1)
				->where('ca_assigns.ca_current_status', '=', 1)  // Filter where ca_current_status is 1
				->where('ca_assigns.ca_id', '=', $userId)  // Only show assignments for the authenticated user
				->orderBy('ca_assigns.created_at', 'desc')  // Order by assignment created_at
				->get();  // Use get() to fetch all results without pagination



		$customers_pagination = $customers;
		// echo "<pre>"; print_r($customers);exit;

		$array = array();
		foreach($customers as $k=>$val)
		{
			// Unread company messages for CA
			$conversation = DB::table('chat_ca_conversations')
				->where('ca_id', $userId)
				->where('company_id', $val->userId)
				->first();

			$unreadCount = 0;
			if ($conversation) {				
				$unreadCount = DB::table('chat_ca_messages')
					->where('conversation_id', $conversation->id)
					->where('sender_type', 'company')
					->where('is_read', 0)
					->count();
			}
			
			$array[$val->id]['id'] = $val->userId;
			$array[$val->id]['userId'] = $val->userId;
			$array[$val->id]['u_type'] = $val->u_type;
			$array[$val->id]['ca_add_by'] = $val->ca_add_by;
			$array[$val->id]['name'] = $val->name;
			$array[$val->id]['email'] = $val->email;
			$array[$val->id]['phone'] = $val->phone;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['comp_email'] = $val->comp_email;
			$array[$val->id]['comp_phone'] = $val->comp_phone;
			$array[$val->id]['comp_logo'] = $val->comp_logo;
			$array[$val->id]['exact_comp_nature'] = $val->exact_comp_nature;
			$array[$val->id]['comp_bill_addone'] = isset($val->comp_bill_addone)?$val->comp_bill_addone:"";
			$array[$val->id]['comp_bill_pin'] = isset($val->comp_bill_pin)?$val->comp_bill_pin:"";
			$array[$val->id]['created_at'] = $val->created_at;
			$array[$val->id]['ca_assign_status'] = $val->ca_assign_status;
			$array[$val->id]['ca_current_status'] = $val->ca_current_status;
			$array[$val->id]['unread_count'] = $unreadCount; // Add unread count
		}
		$customers = json_decode(json_encode($array));

		$array2 = array();
		foreach($customerLists as $k=>$val)
		{
			$array2[$val->id]['id'] = $val->id;
			$array2[$val->id]['u_type'] = $val->u_type;
			$array2[$val->id]['ca_add_by'] = $val->ca_add_by;
			$array2[$val->id]['name'] = $val->name;
			$array2[$val->id]['email'] = $val->email;
			$array2[$val->id]['phone'] = $val->phone;
			$array2[$val->id]['comp_name'] = $val->comp_name;
			$array2[$val->id]['comp_logo'] = $val->comp_logo;
			$array2[$val->id]['comp_bill_addone'] = isset($val->comp_bill_addone)?$val->comp_bill_addone:"";
			$array2[$val->id]['comp_bill_pin'] = isset($val->comp_bill_pin)?$val->comp_bill_pin:"";
			$array2[$val->id]['created_at'] = $val->created_at;
			$array2[$val->id]['request_for'] = $val->request_for;
			$array2[$val->id]['ca_assign_status'] = $val->ca_assign_status;
			$array2[$val->id]['ca_current_status'] = $val->ca_current_status;
			$array2[$val->id]['ca_assign_id'] = $val->ca_assign_id;
		}
		$customerLists = json_decode(json_encode($array2));
		// echo '<pre>';
		// print_r($customers);
		// die();
        return view('Ca.company-assignment')->with([
			'customerLists' =>$customerLists,
			'customerLists_pagination' => $customerLists_pagination,
			'customers' =>$customers,
			'caId' => $userId,
			'customers_pagination' => $customers_pagination
        ]);
    }

    public function EditCompanyAssignment($clientId)
    {
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$clientId = base64_decode($clientId);
		$client = DB::table('company_profiles')
								->where('userId', '=', $clientId)
								->get();

		$client = $client[0];
		$agents = DB::table('busi_agents')
							->select(DB::raw('busi_agents.id,busi_agents.agent_name'))
							->where('added_by','=',$userId)
							->get()->toArray();

		//echo "<pre>";print_r($client);exit;
        return view('Ca.edit-company-assignment')->with([
			'client' => $client,
			'agents'=>$agents,
			'clientId' => $clientId

        ]);

    }

	protected function validatorclient(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
            'comp_name' => 'required|min:3',
            'gst_no' => 'required',
            'comp_email' => 'required|email',
            'comp_phone' => 'required|min:10',
            'comp_pan_no' => 'required'
        ]
		);
    }

	protected function createClient(array $data)
    {
		//print_r($data);exit;
        return Company_profiles::create([
           // 'userId' => Auth::user()->id,
            'comp_gst_no' => $data['comp_gst_no'],
            'comp_name' => $data['comp_name'],
            'comp_email' => $data['comp_email'],
			'comp_phone' => $data['comp_phone'],
			'comp_pan_no' => $data['comp_pan_no'],
			'comp_website' => $data['comp_website'],
			'agent_name' => $data['agent_name'],
			'compincorp' => implode(',', $data['compincorp']),
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	protected function createClientuser(array $data)
    {
		//print_r($data);exit;
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
        return User::create([
            'name' => $data['comp_name'],
            'email' => $data['comp_email'],
			'password' => Hash::make($data['comp_phone']),
			'phone' => $data['comp_phone'],
			'u_type' =>'2',
			'status' =>'1',
			'userStatus' =>'1',
			'isActive' =>'1',
			'ca_add_by'=>$userId,
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_client(Request $request)  {

		//echo "<pre>";print_r($request);exit;
		//$input = Input::all();
		//dd($input);
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$validation = $this->validatorclient($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertClient = $this->createClientuser($request->all());
			$cId = DB::getPdo()->lastInsertId();
			if($cId){
				$insertClient = $this->createClient($request->all());
				$id = DB::getPdo()->lastInsertId();
				$update = DB::table('company_profiles')
					->where('id', $id)
					->update(
						array(
							'userId' => $cId,
						)
					);
				//add in ca_assigns

				$user = new Ca_assigns;

				$user->comp_id = $cId;
				$user->utype = 2;
				$user->ca_id = $userId;
				$user->ca_assign_status = 1;
				$user->ca_current_status = 1;
				$user->save();
			}

			if ($insertClient){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/client'),
					'message' => 'Client account added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'client add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function update_client(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$clientId = $request->id;

		$validation = $this->validatorclient($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update agent
			$update = DB::table('company_profiles')
					->where('userId', $clientId)
					->update(
						 array(

							'gst_no' => $request->gst_no,
							'comp_name' => $request->comp_name,
							'comp_email' =>$request->comp_email,
							'comp_phone' => $request->comp_phone,
							'comp_pan_no' => $request->comp_pan_no,
							'comp_website' => $request->comp_website,
							'agent_name' => $request->agent_name,
							'compincorp' => implode(',', $request->compincorp),

						 )
					);
			if ($update){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/company-assignment'),
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

	public function ClientDetails($custId)
	{
		if(Auth::user()->u_type == 2){
			return redirect('/');
		}

		$custId = base64_decode($custId);
		
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();

		$customers = DB::table('users')
			->select(
				'users.*',
				'company_profiles.*',
				'states.name as state_name',
				'cities.name as city_name'
			)
			->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
			->leftJoin('states', 'states.id', '=', 'company_profiles.comp_bill_state')
			->leftJoin('cities', 'cities.id', '=', 'company_profiles.comp_bill_city')
			->where('users.id', $custId)
			->first();


		// Get task details for the customer
		$taskDetails = DB::table('task_managements')
			->select(DB::raw('
				SUM(IF(project_status = 1, 1, 0)) as totalPending,
				SUM(IF(project_status = 2, 1, 0)) as totalOngoing,
				SUM(IF(project_status = 3, 1, 0)) as totalCompleted,
				SUM(IF(project_status IN (1,2,3), 1, 0)) as totalTask,
				SUM(IF(total_amount >= 0, total_amount, 0)) as totalAmount,
				SUM(IF(advance_payment >= 0, advance_payment, 0)) as totalRecurring,
				SUM(IF(due_amount >= 0, due_amount, 0)) as totalDue
			'))
			->where('company_id', '=', $custId)
			->get();

		// Get the tasks along with task category name
		$tasks = DB::table('users')
			->select(DB::raw('users.*, task_managements.*, task_category.task_category_name'))
			->leftJoin('task_managements', 'users.id', '=', 'task_managements.company_id')
			->leftJoin('task_category', 'task_managements.task_category', '=', 'task_category.id')
			->where('task_managements.company_id', '=', $custId)
			->orderBy('users.created_at', 'desc')
			->get();
		// Get total received amount from ca_payment_history
		$totalReceivedAmount = DB::table('ca_payment_history')
			->where('added_by', $userId)
			->where('customer_id', $custId)
			->sum('total_amount');

		// Get ca recurring amount from ca_payment_history

		// $recurringAmount = DB::table('ca_recurring_amount')
		// 	->where('added_by', Auth::user()->id)
		// 	->get();
		// $totalGovFee = $recurringAmount->sum(function ($item) {
		// 	return is_numeric($item->gov_fee) ? (float)$item->gov_fee : 0;
		// });
		// $totalServiceCharge = $recurringAmount->sum(function ($item) {
		// 	return is_numeric($item->service_charge) ? (float)$item->service_charge : 0;
		// });

		//-- Recurring Amount Calculation --//
		$recurringAmount = DB::table('task_managements')
			->where('company_id', $custId)
			->whereIn('task_category', [9, 13])
			->sum('total_amount');

		// Initialize variables for task counts
		$array2 = array();
		$totalTasks = 0;
		$dueTasks = 0;
		$pendingTasks = 0;
		$ongoingTasks = 0;
		$doneTasks = 0;
		$total_amount = 0;

		foreach ($tasks as $k => $val) {
			$array2[$val->id]['id'] = $val->id;
			$array2[$val->id]['u_type'] = $val->u_type;
			$array2[$val->id]['task_id'] = $val->task_id;
			$array2[$val->id]['task_date'] = $val->task_date;
			$array2[$val->id]['task_time'] = $val->task_time;
			$array2[$val->id]['task_category'] = $val->task_category;
			$array2[$val->id]['gov_fees'] = $val->gov_fees;
			$array2[$val->id]['services_charges'] = $val->services_charges;
			$array2[$val->id]['total_amount'] = $val->total_amount;
			$array2[$val->id]['task_category_name'] = $val->task_category_name;
			$array2[$val->id]['project_status'] = $val->project_status;

			$total_amount = $total_amount + $val->total_amount;
			// Increment total task count
			$totalTasks++;

			// Count tasks based on project_status
			switch ($val->project_status) {
				case 1: // Pending
					$pendingTasks++;
					break;
				case 2: // Ongoing
					$ongoingTasks++;
					break;
				case 3: // Done
					$doneTasks++;
					break;
			}

			// Check if task is due based on task_date
			if (strtotime($val->task_date) < strtotime(date('Y-m-d'))) {
				$dueTasks++; // Increment due tasks if the task date is in the past
			}
		}

		// Convert the array to JSON object for use in view
		$tasks = json_decode(json_encode($array2));

		//-- Bank Details --

		$bankDetails = DB::table('company_banks')->where('uid', '=', $custId)->get();
		$bankDetails = isset($bankDetails)?$bankDetails:[];

		//----- Director Details -------
		$comp_director = DB::table('comp_directors')->where('compId', '=', $custId)->get();
		$comp_director = isset($comp_director)?$comp_director:[];

		//------- Ca Assigns --------
		$ca_assigns_details = DB::table('ca_assigns')
			->where('comp_id', $custId)
			->where('ca_id', $userId)
			->where('ca_current_status', 1)
			->get();

		$ca_assigns_details = $ca_assigns_details ?? [];
		
		$accountant_access = DB::table('accountant_access')->where('is_active', 1)->get();

		/* echo '<pre>';
		print_r($accountant_access);
		die();  */

		return view('Ca.company-details')->with([
			'custId' => $custId,
			'caId' => $userId,
			'customers' => $customers,
			'accountant_access' => $accountant_access,
			'taskDetails' => $taskDetails,
			'tasks' => $tasks,
			'totalTasks' => $totalTasks,
			'dueTasks' => $dueTasks,
			'pendingTasks' => $pendingTasks,
			'ongoingTasks' => $ongoingTasks,
			'doneTasks' => $doneTasks,
			'total_amount' => $total_amount,
			'totalReceivedAmount' => $totalReceivedAmount,
			'dueAmount' => $total_amount - $totalReceivedAmount,
			'recurringAmount' => $recurringAmount,
			'bankDetails'   => $bankDetails,
			'comp_director'  => $comp_director,
			'ca_assigns_details' => $ca_assigns_details
		]);
	}


	public function viewCustomerDet(Request $request)
    {
        if(Auth::user()->u_type ==2){
			return redirect('/');
		}
		$custId = ($request->id);
		$customers = DB::table('users')
					->select(DB::raw('users.*,company_profiles.*,ca_assigns.request_for,ca_assigns.created_at as requestedAt'))
					->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
					->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
					->where('users.id','=',$custId)
					->get();
		$customers = $customers[0];
		//echo "<pre>";print_r($customers);exit;
        echo json_encode($customers);
    }

	public function acceptCustomerStatus(Request $request)
	{
		// Validate the incoming request data
		//echo "<pre>";print_r($_POST);exit;
		$userId = currentOwnerId();
		$userType = currentOwnerUserType();
		
		$request->validate([
			'status' => 'required|in:1,2,3', // Ensure the status is valid
			'id' => 'required|integer'       // Ensure 'id' is passed as an integer
		]);

		// Fetch all the comp_id values that match the request ID
		$comp_id = DB::table('ca_assigns')
					->where('id', $request->id)
					->value('comp_id');

		if (!$comp_id) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'ID Not Found.'
			]);
		}
		if($request->status == 1){
			// Check if any of the fetched comp_ids already have a ca_current_status of 1 (already assigned)
				$existingAssignment = DB::table('ca_assigns')
					->where('comp_id', '=', $comp_id)
					->where('ca_current_status', '=', 1)  // Check if already assigned
					->first();

				if ($existingAssignment) {
					// Return an error message if any of the comp_ids already have ca_current_status = 1
					return response()->json([
						'status' => 'error',
						'class' => 'err',
						'message' => 'Already Assigned.'
					]);
				}
		}

		// If no existing assignment found, update the ca_assign_status to 1 for the provided id
		$update = DB::table('ca_assigns')
			->where('id', '=', $request->id)
			->where('ca_assign_status', '=', 1)
			->update(['ca_current_status' => $request->status]);

		if ($update) {
			// Add notification and send email (same as before)
			$from_uid = $userId;
			$to_uid = (int) $comp_id;
			$utype = $userType;
			$noti_title = "CA Activity";

			// Determine the message based on the status
			if ($request->status == 1) {
				$msg = "Request accepted successfully.";
				$sts = 'success';
			} elseif ($request->status == 2) {
				$msg = "Request deactivated.";
				$sts = 'error';
			} elseif ($request->status == 3) {
				$msg = "Request rejected.";
				$sts = 'error';
			}

			// Add the notification
			$url = "";
			$notifications = Helper::addNotification($to_uid, $noti_title, $msg, $url);

			// Send email to the user
			$userDet = DB::table('users')
				->select(DB::raw('users.name, users.email'))
				->where('id', $to_uid)
				->get();
			$name = $userDet[0]->name;
			$email = $userDet[0]->email;

			$caDet = DB::table('users')
				->select(DB::raw('users.name'))
				->where('id', $from_uid)
				->get();
			$caName = $caDet[0]->name;

			$body = '<html lang="en">
				<head>
					<title>Assign Request Confirmation</title>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1">
				</head>
				<body style="margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;">
					<div style="width: 100%;display: block;position: relative;">
						<div style="display: block;">
							<a href="">
								<img src="' . asset('public/assets/img/logo.png') . '" alt="logo" style="margin: 0 auto;padding: 20px 0;height: auto;max-width: 100%;display: block;">
							</a>
						</div>
						<div class="main-wraper" style="max-width: 600px;margin: 0 auto;position: relative;">
							<div style="margin-top: 50px;display: block;">
								<h1 style="color: #1fa8b8;font-size: 50px;text-align: center;margin-bottom: 0;">Assign Request Confirmation</h1>
								<div style="width: 141px;background: #f57e20;height: 2px;margin: 8px auto 0;"></div>
							</div>
							<div class="content-wraper" style="margin-top: 50px;display: block;padding: 0 30px;">
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
									<tr>
										<td align="left" style="padding-bottom: 20px;"><b>Dear ' . $name . ',</b></td>
									</tr>
									<tr>
										<td style="padding-bottom: 5px;"><p style="text-align: left;margin: 0;font-weight:600;"> ' . $msg . ' by ' . $caName . '</p></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="ft" style="background: #76bed0;display: block;">
							<p style="text-align: center;color: #ffffff;font-size: 14px;padding:5px 0;">Copyright © ' . date("Y") . ' E-cashbook</p>
						</div>
					</div>
				</body>
			</html>';

			$data_email = [
				'email' => $email
			];
			$emailSubject = "Assign request confirmation";
			$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);
		}

		// Success response
		$msg_send = array(
			'status' => $sts,
			'class' => 'succ',
			'redirect' => url('/company-assignment'),
			'message' => $msg
		);
		return response()->json($msg_send);
	}




    public function AssignmentDetails($custId)
    {
        if(Auth::user()->u_type ==2){
			return redirect('/');
		}

		//echo "<pre>";print_r($customers);exit;
        return view('Ca.assignment-details')->with([

        ]);
    }
	
	public function getAssignRequestChart(Request $request)
	{
		$userId = currentOwnerId();

		/*
		|--------------------------------------------------------------------------
		| DAILY
		|--------------------------------------------------------------------------
		*/
		if ($request->type == "daily")
		{
			$slots = [
				['start' => '00:00:00', 'end' => '02:00:00'],
				['start' => '02:00:00', 'end' => '04:00:00'],
				['start' => '04:00:00', 'end' => '06:00:00'],
				['start' => '06:00:00', 'end' => '08:00:00'],
				['start' => '08:00:00', 'end' => '10:00:00'],
				['start' => '10:00:00', 'end' => '12:00:00'],
				['start' => '12:00:00', 'end' => '14:00:00'],
				['start' => '14:00:00', 'end' => '16:00:00'],
				['start' => '16:00:00', 'end' => '18:00:00'],
				['start' => '18:00:00', 'end' => '20:00:00'],
				['start' => '20:00:00', 'end' => '22:00:00'],
				['start' => '22:00:00', 'end' => '23:59:59'],
			];

			$result = [];

			foreach ($slots as $slot)
			{
				$startTime = Carbon::today()->format('Y-m-d') . ' ' . $slot['start'];
				$endTime = Carbon::today()->format('Y-m-d') . ' ' . $slot['end'];

				$activeCount = DB::table('ca_assigns')
					->leftJoin('users', 'users.id', '=', 'ca_assigns.comp_id')
					->whereBetween('ca_assigns.created_at', [$startTime, $endTime])
					->where('users.u_type', 2)
					->where('ca_assigns.ca_id', $userId)
					->where('ca_assigns.ca_current_status', 1)
					->count();

				$rejectedCount = DB::table('ca_assigns')
					->leftJoin('users', 'users.id', '=', 'ca_assigns.comp_id')
					->whereBetween('ca_assigns.created_at', [$startTime, $endTime])
					->where('users.u_type', 2)
					->where('ca_assigns.ca_id', $userId)
					->where('ca_assigns.ca_current_status', 3)
					->count();

				$start = date('g:i A', strtotime($slot['start']));
				$end = date('g:i A', strtotime($slot['end']));

				$result[] = [
					'slot' => "{$start} - {$end}",
					'active' => $activeCount,
					'rejected' => $rejectedCount,
				];
			}

			$active = [];
			$rejected = [];
			$categories = [];

			foreach ($result as $row)
			{
				$active[] = $row['active'];
				$rejected[] = $row['rejected'];
				$categories[] = $row['slot'];
			}

			return response()->json([
				'active' => implode(',', $active),
				'rejected' => implode(',', $rejected),
				'categories' => implode(',', $categories),
			]);
		}

		/*
		|--------------------------------------------------------------------------
		| MONTHLY
		|--------------------------------------------------------------------------
		*/
		else if ($request->type == "monthly")
		{
			$users = DB::table('ca_assigns')
				->leftJoin('users', 'users.id', '=', 'ca_assigns.comp_id')
				->selectRaw('
					MONTH(ca_assigns.created_at) as month,
					SUM(CASE WHEN ca_assigns.ca_current_status = 1 THEN 1 ELSE 0 END) as active,
					SUM(CASE WHEN ca_assigns.ca_current_status = 3 THEN 1 ELSE 0 END) as rejected
				')
				->whereYear('ca_assigns.created_at', date('Y'))
				->where('users.u_type', 2)
				->where('ca_assigns.ca_id', $userId)
				->groupBy('month')
				->orderBy('month')
				->get();

			$monthNames = [
				1 => 'Jan',
				2 => 'Feb',
				3 => 'Mar',
				4 => 'Apr',
				5 => 'May',
				6 => 'Jun',
				7 => 'Jul',
				8 => 'Aug',
				9 => 'Sep',
				10 => 'Oct',
				11 => 'Nov',
				12 => 'Dec'
			];

			$formatted = [];

			foreach ($users as $row)
			{
				$formatted[$row->month] = [
					'active' => $row->active,
					'rejected' => $row->rejected,
				];
			}

			$active = [];
			$rejected = [];
			$categories = [];

			for ($i = 1; $i <= 12; $i++)
			{
				$active[] = $formatted[$i]['active'] ?? 0;
				$rejected[] = $formatted[$i]['rejected'] ?? 0;
				$categories[] = $monthNames[$i];
			}

			return response()->json([
				'active' => implode(',', $active),
				'rejected' => implode(',', $rejected),
				'categories' => implode(',', $categories),
			]);
		}

		/*
		|--------------------------------------------------------------------------
		| YEARLY
		|--------------------------------------------------------------------------
		*/
		else if ($request->type == "yearly")
		{
			$users = DB::table('ca_assigns')
				->leftJoin('users', 'users.id', '=', 'ca_assigns.comp_id')
				->selectRaw('
					YEAR(ca_assigns.created_at) as year,
					SUM(CASE WHEN ca_assigns.ca_current_status = 1 THEN 1 ELSE 0 END) as active,
					SUM(CASE WHEN ca_assigns.ca_current_status = 3 THEN 1 ELSE 0 END) as rejected
				')
				->where('users.u_type', 2)
				->where('ca_assigns.ca_id', $userId)
				->groupBy('year')
				->orderBy('year', 'desc')
				->take(5)
				->get();

			$active = [];
			$rejected = [];
			$categories = [];

			foreach ($users as $row)
			{
				$active[] = $row->active;
				$rejected[] = $row->rejected;
				$categories[] = $row->year;
			}

			return response()->json([
				'active' => implode(',', $active),
				'rejected' => implode(',', $rejected),
				'categories' => implode(',', $categories),
			]);
		}

		return response()->json([
			'status' => false,
			'message' => 'Invalid request type'
		]);
	}

	


}
