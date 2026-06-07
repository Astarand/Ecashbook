<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Client;
use Redirect;
use DB;
use Auth;
use Validator;

class ReminderController extends Controller
{

    public function Index()
	{
		//$this->middleware('auth');
		$utype = Auth::user()->u_type;
		if ($utype == 3) {
			return view('pages.superadmin.ca-reminder')->with([]);
		} else {
			return redirect("/");
		}
	}

    public function remider_from_ca()
	{
		//$this->middleware('auth');
		$utype = Auth::user()->u_type;
		if ($utype == 1) {
			return view('Ca.reminder')->with([]);
		}else if($utype == 3){
			return view('Admin.reminder')->with([]);
		} else {
			return redirect("/");
		}
	}

    public function userLists(Request $request)
	{
		$reminder_type = $request->reminder_type;
		$user_type = $request->id;
		$status = $request->customer_type;
		$reminder_through = $request->reminder_through;

		$result = User::query()
			->where('u_type', '=', $user_type)
			->where('status', '=', $status)
			->get();

		$array = array();
		foreach ($result as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['u_type'] = $val->u_type;
			if ($val->u_type == 1) {
				$company = DB::table('ca_profiles')
					->select(DB::raw('ca_profiles.comp_name'))
					->where('ca_profiles.userId', '=', $val->id)
					->get();
			} else if ($val->u_type == 2) {
				$company = DB::table('company_profiles')
					->select(DB::raw('company_profiles.comp_name'))
					->where('company_profiles.userId', '=', $val->id)
					->get();
			}
			$array[$val->id]['comp_name'] = isset($company[0]->comp_name) ? $company[0]->comp_name : $val->name;
			$array[$val->id]['comp_phone'] = isset($company[0]->comp_phone) ? $company[0]->comp_phone : $val->phone;
			$array[$val->id]['comp_email'] = isset($company[0]->comp_email) ? $company[0]->comp_email : $val->email;
		}
		$result = json_decode(json_encode($array));

		$response = [];
		//echo "<pre>";print_r($result);exit;
		foreach ($result as $row) {
			$response[] = array("id" => $row->id, "name" => $row->comp_name);
		}

		echo json_encode($response);
	}

    public function sendReminder(Request $request)
	{
		//echo "<pre>";print_r ($request->userId);exit;
		$reminder_type = $request->reminder_type;
		$u_type = $request->user_type;
		$status = $request->customer_type;
		$reminder_through = $request->reminder_through;
		$userIdArr = $request->userId;
		$sub_text = $request->sub_text;
		$msg_text = $request->msg_text;

		if (!($userIdArr) && $userIdArr == "" && $reminder_type == "specific") {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please select user'
			);
			return response()->json($msg);
		}

		if ($reminder_through == "mail") { //for mail
			$filePath = "";
			$file = "";
			if ($file = $request->hasFile('fileAttached')) {
				$file = $request->file('fileAttached');
			}
			if ($reminder_type == "bulk") {
				$users = DB::table('users')
					->select(DB::raw('users.id,users.u_type,users.name,users.email'))
					->where('users.u_type', '=', $u_type)
					->where('users.status', '=', $status)
					->get();
				if ($users->count() != 0) {
					foreach ($users as $val) {
						if ($val->u_type == 1) {
							$uData = DB::table('ca_profiles')
								->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_email'))
								->where('ca_profiles.userId', '=', $val->id)
								->get();
						} else if ($val->u_type == 2) {
							$uData = DB::table('company_profiles')
								->select(DB::raw('company_profiles.comp_name,company_profiles.comp_email'))
								->where('company_profiles.userId', '=', $val->id)
								->get();
						}
						$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $val->name;
						$comp_email = isset($uData[0]->comp_email) ? $uData[0]->comp_email : $val->email;
						$dataEmail = array(
							'title' => $sub_text,
							'subject' => $sub_text,
							'name' => $comp_name,
							'email' => $comp_email,
							'msg' => $msg_text,
							'files' => $file,
						);
						$sendMail = Helper::emailTemplate($dataEmail);
					}
					if ($sendMail) {
						$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'redirect' => url('/'),
							'message' => 'Email sent successfully'
						);
						return response()->json($msg);
					}
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'User not exists!'
					);
					return response()->json($msg);
				}
			} else if ($reminder_type == "specific") {
				$userIdArr = explode(",", $userIdArr);
				foreach ($userIdArr as $id) {
					//get user type
					$ud =  DB::table('users')
						->select(DB::raw('users.u_type,users.name,users.email'))
						->where('users.id', '=', $id)
						->get();
					if ($ud[0]->u_type == 1) {
						$uData = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_email'))
							->where('ca_profiles.userId', '=', $id)
							->get();
					} else if ($ud[0]->u_type == 2) {
						$uData = DB::table('company_profiles')
							->select(DB::raw('company_profiles.comp_name,company_profiles.comp_email'))
							->where('company_profiles.userId', '=', $id)
							->get();
					}
					$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $ud[0]->name;
					$comp_email = isset($uData[0]->comp_email) ? $uData[0]->comp_email : $ud[0]->email;
					$dataEmail = array(
						'title' => $sub_text,
						'subject' => $sub_text,
						'name' => $comp_name,
						'email' => $comp_email,
						'msg' => $msg_text,
						'files' => $file,
					);
					$sendMail = Helper::emailTemplate($dataEmail);
				}
				if ($sendMail) {
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'Email sent successfully'
					);
					return response()->json($msg);
				}
			}
		} else if ($reminder_through == "whatsapp") { //for whatsapp
			if ($reminder_type == "bulk") {
				$users = DB::table('users')
					->select(DB::raw('users.id,users.u_type,users.name,users.phone'))
					->where('users.u_type', '=', $u_type)
					->where('users.status', '=', $status)
					->get();
				if ($users->count() != 0) {
					foreach ($users as $val) {
						if ($val->u_type == 1) {
							$uData = DB::table('ca_profiles')
								->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_phone'))
								->where('ca_profiles.userId', '=', $val->id)
								->get();
						} else if ($val->u_type == 2) {
							$uData = DB::table('company_profiles')
								->select(DB::raw('company_profiles.comp_name,company_profiles.comp_phone'))
								->where('company_profiles.userId', '=', $val->id)
								->get();
						}
						$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $val->name;
						$comp_phone = isset($uData[0]->comp_phone) ? $uData[0]->comp_phone : $val->phone;
						// WhatsApp Business API endpoint
						$apiUrl = 'https://api.whatsapp.com/send?phone=' . $comp_phone . '&text=' . urlencode($msg_text);
						$client = new Client();
						$response = $client->get($apiUrl);
					}
					if ($response->getStatusCode() == 200) {
						$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'redirect' => url('/'),
							'message' => 'WhatsApp message sent successfully'
						);
						return response()->json($msg);
					}
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'User not exists!'
					);
					return response()->json($msg);
				}
			} else if ($reminder_type == "specific") {
				$userIdArr = explode(",", $userIdArr);
				foreach ($userIdArr as $id) {
					//get user type
					$ud =  DB::table('users')
						->select(DB::raw('users.u_type,users.name,users.phone'))
						->where('users.id', '=', $id)
						->get();
					if ($ud[0]->u_type == 1) {
						$uData = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_phone'))
							->where('ca_profiles.userId', '=', $id)
							->get();
					} else if ($ud[0]->u_type == 2) {
						$uData = DB::table('company_profiles')
							->select(DB::raw('company_profiles.comp_name,company_profiles.comp_phone'))
							->where('company_profiles.userId', '=', $id)
							->get();
					}
					$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $ud[0]->name;
					$comp_phone = isset($uData[0]->comp_phone) ? $uData[0]->comp_phone : $ud[0]->phone;
					// WhatsApp Business API endpoint
					$apiUrl = 'https://api.whatsapp.com/send?phone=' . $comp_phone . '&text=' . urlencode($msg_text);
					$client = new Client();
					$response = $client->get($apiUrl);
				}
				if ($response->getStatusCode() == 200) {
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'WhatsApp message sent successfully'
					);
					return response()->json($msg);
				}
			}
		} else if ($reminder_through == "notification") {	//for notification

			if ($reminder_type == "bulk") {
				$users = DB::table('users')
					->select(DB::raw('users.id,users.u_type'))
					->where('users.u_type', '=', $u_type)
					->where('users.status', '=', $status)
					->get();
				if ($users->count() != 0) {
					foreach ($users as $val) {
						//Add notification
						$from_uid = Auth::user()->id;
						$to_uid = $val->id;
						$utype = Auth::user()->u_type;
						$noti_title = $sub_text;
						$msg = $msg_text;
						$url = "";
						$notifications = Helper::addNotification($to_uid, $noti_title, $msg, $url);
						//End notification
					}
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'User not exists!'
					);
					return response()->json($msg);
				}
			} else if ($reminder_type == "specific") {
				$userIdArr = explode(",", $userIdArr);
				foreach ($userIdArr as $id) {
					//Add notification
					$from_uid = Auth::user()->id;
					$to_uid = $id;
					$utype = Auth::user()->u_type;
					$noti_title = $sub_text;
					$msg = $msg_text;
					$url = "";
					$notifications = Helper::addNotification($to_uid, $noti_title, $msg, $url);
					//End notification
				}
			}
			if ($notifications) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Notification sent successfully'
				);
				return response()->json($msg);
			}
		} else if ($reminder_through == "sms") {	//for sms

		} else if ($reminder_through == "all") {	//for all

		}
	}

    public function company_task_list(Request $request)
	{
		$task_category = $request->task_category;
		$userId = Auth::user()->id;
		$tasks = DB::table('users')
			->select(DB::raw('users.name,users.u_type,task_managements.id,task_managements.company_id,task_managements.task_category'))
			->leftJoin('task_managements', 'users.id', '=', 'task_managements.company_id')
			->where('task_managements.added_by', '=', $userId)
			->where('task_managements.task_category', '=', $task_category)
			->get();
		$array = array();
		foreach ($tasks as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['u_type'] = $val->u_type;
			$array[$val->id]['task_category'] = $val->task_category;
			$company = DB::table('company_profiles')
				->select(DB::raw('company_profiles.comp_name'))
				->where('company_profiles.userId', '=', $val->company_id)
				->get();
			$array[$val->id]['comp_name'] = isset($company[0]->comp_name) ? $company[0]->comp_name : $val->name;
		}
		$tasks = json_decode(json_encode($array));
		//echo "<pre>"; print_r($tasks);exit;
		return view('Ca.company-task-list')->with([
			'tasks' => $tasks,
		]);
	}

    public function send_bulk_message(Request $request)
	{
		$reminderText = trim($request->reminderText);
		$subject_text = trim($request->subject_text);
		$task_category = $request->task_category;

		if ($reminderText == "") {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Message is required'
			);
			return response()->json($msg);
		} else if ($task_category == "") {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please select task category'
			);
			return response()->json($msg);
		} else {
			$userId = Auth::user()->id;
			$tasksToSend = DB::table('users')
				->select(DB::raw('users.name,users.email,users.u_type,task_managements.id,task_managements.company_id'))
				->leftJoin('task_managements', 'users.id', '=', 'task_managements.company_id')
				->where('task_managements.added_by', '=', $userId)
				->where('task_managements.task_category', '=', $task_category)
				->get();
			if (sizeof($tasksToSend) == 0) {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'No task item for selected category'
				);
				return response()->json($msg);
			}

			$array = array();
			foreach ($tasksToSend as $k => $val) {
				$array[$val->id]['id'] = $val->id;
				$array[$val->id]['u_type'] = $val->u_type;
				$array[$val->id]['task_category'] = $task_category;
				$company = DB::table('company_profiles')
					->select(DB::raw('company_profiles.comp_name,company_profiles.comp_email'))
					->where('company_profiles.userId', '=', $val->company_id)
					->get();
				$comp_name = isset($company[0]->comp_name) ? $company[0]->comp_name : $val->name;
				$email = isset($company[0]->comp_email) ? $company[0]->comp_email : $val->email;
				$array[$val->id]['comp_name'] = $comp_name;
				$array[$val->id]['comp_email'] = $email;

				//Add notification
				$from_uid = Auth::user()->id;
				$to_uid = $val->company_id;
				$utype = Auth::user()->u_type;
				$noti_title = "CA Reminder";
				$msg = $reminderText;
				$url = "";
				$notifications = Helper::addNotification($to_uid, $noti_title, $msg, $url);
				//End notification

				//Start send mail
				$name = $comp_name;
				$email = $email;
				$body = '<html lang="en">
								<head>
								<title>Reminder Email</title>
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
										<h1 style="color: #1fa8b8;font-size: 50px;text-align: center;margin-bottom: 0;">Reminder From CA</h1>
										<div style="width: 141px;background: #f57e20;height: 2px;margin: 8px auto 0;"></div>
									</div>
									<div class="content-wraper" style="margin-top: 50px;display: block;padding: 0 30px;">
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tr>
												<td align="left" style="padding-bottom: 20px;"><b>Dear ' . $name . ',</b></td>
											</tr>
											
											<tr>
												<td style="padding-bottom: 5px;"><p style="text-align: left;margin: 0;font-weight:600;">Reminder from CA below:</p></td>
											</tr>
											
											<tr>
												<td style="padding-bottom: 5px;"><p style="text-align: left;margin: 0;font-weight:600;">' . $reminderText . '</p></td>
											</tr>
											
											<tr>
												<td style="padding-bottom: 5px;">
												<p style="text-align: left;margin: 0;font-weight:600;">
												
												</p>
												</td>
											</tr>
											
										</table>
										
									</div>
									
									
								</div>
								<div class="ft" style="background: #76bed0;display: block;">
										<p style="text-align: center;color: #ffffff;font-size: 14px;padding:5px 0;">Copyright © ' . date("Y") . ' E-cashbook</p>
									</div>
								</div>    

								</body>
								</html> ';

				$data_email = [
					'email' => $email
				];
				// $emailSubject = "Reminder from CA";
				$emailSubject = $subject_text;
				$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);
				//End send mail

			}

			if ($notifications) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Message sent successfully'
				);
				return response()->json($msg);
			} else {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Message send failed.Try again!'
				);
				return response()->json($msg);
			}
			$tasksToSend = json_decode(json_encode($array));


			echo "<pre>";
			print_r($tasksToSend);
			exit;
		}
	}

	
	public function userListsAccess(Request $request)
	{
		$select_type    = $request->select_type;
		$customer_type  = $request->customer_type;
		$CAId           = Auth::user()->id;

		$query = DB::table('company_profiles as cp')
			->join('users as u', 'u.id', '=', 'cp.userId')
			->join('ca_assigns as ca', 'ca.comp_id', '=', 'cp.userId')
			->where('ca.ca_id', $CAId)
			->where('ca.ca_assign_status', 1)
			->where('ca.ca_current_status', 1);

		// Filter by customer type
		if ($customer_type != 'All') {
			$query->where('u.status', $customer_type);
		}

		// Filter by company incorporation type
		if ($select_type != 'All') {
			$query->where('cp.compincorp', 'like', '%' . $select_type . '%');
		}

		$matchedResults = $query->select('cp.comp_name', 'cp.userId')->get();

		return response()->json([
			'matched_names' => $matchedResults
		]);
	}

    public function getUserAccessByStatus(Request $request)
	{
		$select_type = $request->select_type;
		$customer_type = $request->customer_type;
		$CAId = Auth::user()->id;

		if ($customer_type == 'All') {
			$result = User::query()
				->where('ca_add_by', '=', $CAId)
				->get();
		} else {
			$result = User::query()
				->where('ca_add_by', '=', $CAId)
				->where('status', '=', $customer_type)
				->get();
		}

		$matchedResults = [];

		foreach ($result as $user) {
			if ($select_type == "All") {
				$companyProfile = DB::table('company_profiles')
					->where('userId', '=', $user->id)
					->first();
			} else {
				$companyProfile = DB::table('company_profiles')
					->where('userId', '=', $user->id)
					->where('compincorp', 'like', '%' . $select_type . '%')
					->first();
			}

			if ($companyProfile) {
				$matchedResults[] = $companyProfile;
			}
		}


		return response()->json([
			'matched_names' => $matchedResults,
		]);
	}

    public function sendReminderCA(Request $request)
	{
		// echo "<pre>";print_r ($_POST);exit;
		$reminder_type = $request->reminder_type;
		$u_type = $request->user_type;
		$status = $request->customer_type;
		$reminder_through = $request->reminder_through;
		$userIdArr = $request->userId;
		$sub_text = $request->sub_text;
		$msg_text = $request->msg_text;
		//$userIdArr = explode(",", $userIdArr);
		
		if (empty($sub_text) || empty($msg_text)) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Subject and Message are required'
			]);
		}

		if (!($userIdArr) && $userIdArr == "" && $reminder_type == "specific") {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please select user'
			);
			return response()->json($msg);
		}

		if ($reminder_through == "mail") { //for mail
			$filePath = "";
			$file = "";
			if ($file = $request->hasFile('fileAttached')) {
				$file = $request->file('fileAttached');
			}
			if ($reminder_type == "bulk") {
				$users = DB::table('users')
					->select(DB::raw('users.id,users.u_type,users.name,users.email'))
					->whereIn('users.id', $userIdArr)
					// ->where('users.u_type', '=', $u_type)
					// ->where('users.status', '=', $status)
					->get();
				if ($users->count() != 0) {
					foreach ($users as $val) {
						if ($val->u_type == 1) {
							$uData = DB::table('ca_profiles')
								->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_email'))
								->where('ca_profiles.userId', '=', $val->id)
								->get();
						} else if ($val->u_type == 2) {
							$uData = DB::table('company_profiles')
								->select(DB::raw('company_profiles.comp_name,company_profiles.comp_email'))
								->where('company_profiles.userId', '=', $val->id)
								->get();
						}
						$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $val->name;
						$comp_email = isset($uData[0]->comp_email) ? $uData[0]->comp_email : $val->email;
						$dataEmail = array(
							'title' => $sub_text,
							'subject' => $sub_text,
							'name' => $comp_name,
							'email' => $comp_email,
							'msg' => $msg_text,
							'files' => $file,
						);
						$sendMail = Helper::emailTemplate($dataEmail);
					}
					if ($sendMail) {
						$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'redirect' => url('/'),
							'message' => 'Email sent successfully'
						);
						return response()->json($msg);
					}
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'User not exists!'
					);
					return response()->json($msg);
				}
			} else if ($reminder_type == "specific") {

				// $userIdArr = explode(",", $userIdArr);
				// echo '<pre>'; print_r($userIdArr); exit;

				foreach ($userIdArr as $id) {
					//get user type
					$ud =  DB::table('users')
						->select(DB::raw('users.u_type,users.name,users.email'))
						->whereIn('users.id', $userIdArr)
						// ->where('users.id', '=', $id)
						->get();
					
					if ($ud[0]->u_type == 1) {
						$uData = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_email'))
							->where('ca_profiles.userId', '=', $id)
							->get();
					} else if ($ud[0]->u_type == 2) {
						$uData = DB::table('company_profiles')
							->select(DB::raw('company_profiles.comp_name,company_profiles.comp_email'))
							->where('company_profiles.userId', '=', $id)
							->get();
					}

					// echo '<pre>'; print_r($uData); exit;

					$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $ud[0]->name;
					$comp_email = isset($uData[0]->comp_email) ? $uData[0]->comp_email : $ud[0]->email;

					$dataEmail = array(
						'title' => $sub_text,
						'subject' => $sub_text,
						'name' => $comp_name,
						'email' => $comp_email,
						'msg' => $msg_text,
						'files' => $file,
					);

					// echo '<pre>'; print_r($dataEmail); exit;
					
					$sendMail = Helper::emailTemplate($dataEmail);
				}
				if ($sendMail) {
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'Email sent successfully'
					);
					return response()->json($msg);
				}
			}
		} else if ($reminder_through == "whatsapp") { //for whatsapp
			if ($reminder_type == "bulk") {
				$users = DB::table('users')
					->select(DB::raw('users.id,users.u_type,users.name,users.phone'))
					->whereIn('users.id', $userIdArr)
					->get();
				if ($users->count() != 0) {
					foreach ($users as $val) {
						if ($val->u_type == 1) {
							$uData = DB::table('ca_profiles')
								->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_phone'))
								->where('ca_profiles.userId', '=', $val->id)
								->get();
						} else if ($val->u_type == 2) {
							$uData = DB::table('company_profiles')
								->select(DB::raw('company_profiles.comp_name,company_profiles.comp_phone'))
								->where('company_profiles.userId', '=', $val->id)
								->get();
						}
						$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $val->name;
						$comp_phone = isset($uData[0]->comp_phone) ? $uData[0]->comp_phone : $val->phone;
						// WhatsApp Business API endpoint
						$apiUrl = 'https://api.whatsapp.com/send?phone=' . $comp_phone . '&text=' . urlencode($msg_text);
						$client = new Client();
						$response = $client->get($apiUrl);
					}
					if ($response->getStatusCode() == 200) {
						$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'redirect' => url('/'),
							'message' => 'WhatsApp message sent successfully'
						);
						return response()->json($msg);
					}
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'User not exists!'
					);
					return response()->json($msg);
				}
			} else if ($reminder_type == "specific") {
				$userIdArr = explode(",", $userIdArr);
				foreach ($userIdArr as $id) {
					//get user type
					$ud =  DB::table('users')
						->select(DB::raw('users.u_type,users.name,users.phone'))
						->whereIn('users.id', $userIdArr)
						->get();
					if ($ud[0]->u_type == 1) {
						$uData = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.comp_name,ca_profiles.comp_phone'))
							->where('ca_profiles.userId', '=', $id)
							->get();
					} else if ($ud[0]->u_type == 2) {
						$uData = DB::table('company_profiles')
							->select(DB::raw('company_profiles.comp_name,company_profiles.comp_phone'))
							->where('company_profiles.userId', '=', $id)
							->get();
					}
					$comp_name = isset($uData[0]->comp_name) ? $uData[0]->comp_name : $ud[0]->name;
					$comp_phone = isset($uData[0]->comp_phone) ? $uData[0]->comp_phone : $ud[0]->phone;
					// WhatsApp Business API endpoint
					$apiUrl = 'https://api.whatsapp.com/send?phone=' . $comp_phone . '&text=' . urlencode($msg_text);
					$client = new Client();
					$response = $client->get($apiUrl);
				}
				if ($response->getStatusCode() == 200) {
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'WhatsApp message sent successfully'
					);
					return response()->json($msg);
				}
			}
		} else if ($reminder_through == "notification") {	//for notification
			//echo '<pre>'; print_r($reminder_through); exit;
			if ($reminder_type == "bulk") {
				$users = DB::table('users')
					->select(DB::raw('users.id,users.u_type'))
					->whereIn('users.id', $userIdArr)
					->get();
				if ($users->count() != 0) {
					foreach ($users as $val) {
						//Add notification
						$from_uid = Auth::user()->id;
						$to_uid = $val->id;
						$utype = Auth::user()->u_type;
						$noti_title = $sub_text;
						$msg = $msg_text;
						$url = "";
						$notifications = Helper::addNotification($to_uid, $noti_title, $msg, $url);
						//End notification
					}
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'User not exists!'
					);
					return response()->json($msg);
				}
			} else if ($reminder_type == "specific") {
				// $userIdArr = explode(",", $userIdArr);
				foreach ($userIdArr as $id) {
					//Add notification
					$from_uid = Auth::user()->id;
					$to_uid = $id;
					$utype = Auth::user()->u_type;
					$noti_title = $sub_text;
					$msg = $msg_text;
					$url = "";
					$notifications = Helper::addNotification($to_uid, $noti_title, $msg, $url);
					//End notification
				}
			}
			if ($notifications) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Notification sent successfully'
				);
				return response()->json($msg);
			}
		} else if ($reminder_through == "sms") {	//for sms

		} else if ($reminder_through == "all") {	//for all

		}
	}

	public function allUserCA(Request $request)
	{
		$select_type = $request->select_type;
		$customer_type = $request->customer_type;

		$query = DB::table('users')
			->select(
				'id as userId',
				'name',
				'u_type',
				'status'
			)
			->where('isdeleted', 0);

		// Access User Filter
		if ($select_type == 'all_ca') {
			$query->where('u_type', 1);
		} elseif ($select_type == 'all_company') {
			$query->where('u_type', 2);
		} elseif ($select_type == 'All') {
			$query->whereIn('u_type', [1,2]);
		}

		// Status Filter
		if ($customer_type != 'All') {
			$query->where('status', $customer_type);
		}

		$users = $query->get()->map(function ($user) {

			return [
				'userId'        => $user->userId,
				'name'          => $user->name,
				'type_label'    => $user->u_type == 1 ? 'CA' : 'Company',
				'status_label'  => $user->status == 1 ? 'Active' : 'Inactive',
				'status_value'  => $user->status
			];
		});

		

		return response()->json([
			'matched_names' => $users
		]);
	}

	public function userCaListsAccess(Request $request)
	{
		$select_type = $request->select_type;
		$customer_type = $request->customer_type;

		// $query = DB::table('users')
		// 			->select('id as userId', 'name as comp_name')
		// 			->where('isdeleted', 0);
		$query = DB::table('users')
				->select(
					'id as userId',
					'name',
					'status',
					'u_type'   // ✅ ADD THIS
				)
				->where('isdeleted', 0);

		// Access User Filter
		if ($select_type == 'all_ca') {
			$query->where('u_type', 1);
		} elseif ($select_type == 'all_company') {
			$query->where('u_type', 2);
		} elseif ($select_type == 'All') {
			$query->whereIn('u_type', [1,2]);
		}

		// Customer Type Filter
		if ($customer_type != 'All') {
			$query->where('status', $customer_type);
		}

		// $users = $query->get();
		$users = $query->get()->map(function ($user) {

			return [
				'userId'        => $user->userId,
				'name'          => $user->name,
				'type_label'    => $user->u_type == 1 ? 'CA' : 'Company',
				'status_label'  => $user->status == 1 ? 'Active' : 'Inactive',
				'status_value'  => $user->status
			];
		});

		return response()->json([
			'matched_names' => $users
		]);
	}
    
}
