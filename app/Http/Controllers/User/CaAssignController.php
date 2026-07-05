<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
// use Validator;
use App\Models\User;
use App\Models\Company_profiles;
use App\Models\Ca_profiles;
use App\Models\Ca_details;
use App\Models\Ca_assigns;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
// use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Helper;
use App\Helpers\AuditLogger;


class CaAssignController extends Controller
{
	public function AssignCA()
	{

		$authUser = auth()->user();
		$userType = $authUser->u_type;		
		$userId = currentOwnerId();
		checkCoreAccess('CA Connect');
		$ca_details = DB::table('users')
			->select(DB::raw('users.*, ca_profiles.comp_logo, ca_profiles.comp_name, ca_profiles.total_no_client,
						ca_profiles.comp_bill_addone, ca_profiles.comp_bill_country, ca_profiles.comp_bill_state,
						ca_profiles.comp_bill_city, ca_profiles.comp_bill_pin, ca_profiles.ca_spec'))
			->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
			->where('users.u_type', 1)
			//->where('users.status', 1)
			->orderBy('users.created_at', 'desc')
			->get();

		$ca_pagination = $ca_details;

		$array = array();
		foreach ($ca_details as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['u_type'] = $val->u_type;
			$array[$val->id]['name'] = $val->name;
			$array[$val->id]['email'] = $val->email;
			$array[$val->id]['phone'] = $val->phone;
			$array[$val->id]['addr_one'] = $val->addr_one;
			$array[$val->id]['addr_two'] = $val->addr_two;
			$array[$val->id]['pincode'] = $val->pincode;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['isCaActive'] = $val->isCaActive;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['comp_logo'] = $val->comp_logo;
			$array[$val->id]['total_no_client'] = $val->total_no_client;
			$array[$val->id]['comp_bill_addone'] = isset($val->comp_bill_addone) ? $val->comp_bill_addone : "";
			$array[$val->id]['comp_bill_pin'] = isset($val->comp_bill_pin) ? $val->comp_bill_pin : "";
			$array[$val->id]['ca_spec'] = $val->ca_spec;

			$state = State::where('id', '=', isset($val->comp_bill_state) ? $val->comp_bill_state : 0)->get();
			$array[$val->id]['ca_state'] = isset($state[0]->name) ? $state[0]->name : "";

			$city = City::where('id', '=', isset($val->comp_bill_city) ? $val->comp_bill_city : 0)->get();
			$array[$val->id]['ca_city'] = isset($city[0]->name) ? $city[0]->name : "";


			$ca_assign = DB::table('ca_assigns')
				->select('ca_assign_status', 'ca_current_status')
				->where('ca_id', $val->id)
				->where('comp_id', $userId)
				->orderBy('id', 'desc')
				->first();

			$array[$val->id]['ca_assign_status'] = $ca_assign->ca_assign_status ?? 0;
			$array[$val->id]['ca_current_status'] = $ca_assign->ca_current_status ?? 0;
		}
		$ca_details = json_decode(json_encode($array));

		//------- Fetch The State ---------
		$states = State::where('country_id', 101)->orderBy('name')->get();


		// echo "<pre>"; print_r($ca_details);exit;
		return view('User.AssignCa')->with([
			'ca_details' => $ca_details,
			'ca_pagination' => $ca_pagination,
			'states' => $states,
		]);
	}

	protected function create_ca_assign(array $data)
	{
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$uType = currentOwnerUserType();
		return Ca_assigns::create([
			'comp_id' => $userId,
			'utype' => $uType,
			'ca_id' => $data['ca_id'],
			'set_permission' => $data['set_permission'],
			'ca_assign_status' => 1,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function assign_ca(Request $request)
	{
		$comp_id = currentOwnerId();
		$ca_id = $request->ca_id;
		$ca_assign_status = $request->ca_assign_status;
		$msgPosted = $request->msg;

		// Step 1: Check if already permanently assigned
		$alreadyAssigned = DB::table('ca_assigns')
			->where('comp_id', $comp_id)
			->where('ca_current_status', 1)
			->exists();

		if ($alreadyAssigned) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/assign-ca-firm'),
				'message' => 'Already assigned CA'
			]);
		}

		// Step 2: Check for existing assign record
		$existingAssign = DB::table('ca_assigns')
			->where('ca_id', $ca_id)
			->where('comp_id', $comp_id)
			->orderBy('id', 'desc')
			->first();

		if ($existingAssign) {
			if ($existingAssign->ca_assign_status == 1) {
				// Undo request
				DB::table('ca_assigns')
					->where('ca_id', $ca_id)
					->where('comp_id', $comp_id)
					->orderBy('id', 'desc')
					->limit(1)
					->update(['ca_assign_status' => $ca_assign_status]);

				$noti_title = "Undo CA Assignment";
				$msg = $msgPosted ?: $noti_title;
				Helper::addNotification($ca_id, $noti_title, $msg, '');
				
				//AUDIT LOG ENTRY
				AuditLogger::logEntry(
					action: 'assign',
					module: 'request',
					description: 'Request assigned to CA and notification sent',
					oldData: null,
					newData: [
						'notification_title' => $noti_title,
						'notification_message' => $msg
					]
				);

				return response()->json([
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/assign-ca-firm'),
					'message' => 'Undo Request',
					'ca_assign_status' => $ca_assign_status
				]);
			} else {
				// Re-send assignment request
				DB::table('ca_assigns')
					->where('ca_id', $ca_id)
					->where('comp_id', $comp_id)
					->orderBy('id', 'desc')
					->limit(1)
					->update(['ca_assign_status' => $ca_assign_status]);

				$noti_title = "Assign Request to CA";
				$msg = $msgPosted ?: $noti_title;
				Helper::addNotification($ca_id, $noti_title, $msg, '');
				
				//AUDIT LOG ENTRY
				AuditLogger::logEntry(
					action: 'assign',
					module: 'request',
					description: 'Request assigned to CA and notification sent',
					oldData: null,
					newData: [
						'notification_title' => $noti_title,
						'notification_message' => $msg
					]
				);

				return response()->json([
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/assign-ca-firm'),
					'message' => 'Assign request sent to CA',
					'ca_assign_status' => $ca_assign_status
				]);
			}
		} else {
			// Step 3: New record
			$insertCa = $this->create_ca_assign($request->all());

			if ($insertCa) {
				$noti_title = "Assign Request to CA";
				$msg = $msgPosted ?: $noti_title;
				Helper::addNotification($ca_id, $noti_title, $msg, '');
				
				//AUDIT LOG ENTRY
				AuditLogger::logEntry(
					action: 'assign',
					module: 'request',
					description: 'Request assigned to CA and notification sent',
					oldData: null,
					newData: [
						'notification_title' => $noti_title,
						'notification_message' => $msg
					]
				);

				return response()->json([
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/assign-ca-firm'),
					'message' => 'Assign request sent to CA',
					'ca_assign_status' => $ca_assign_status
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'CA Assign failed!'
				]);
			}
		}
	}

	public function storeCA(Request $request)
	{
		$userId = currentOwnerId();
		$validator = Validator::make($request->all(), [
			'ca_name'     => 'required|string|max:255',
			'ca_email'    => 'required|email|unique:users,email',
			'ca_phone'    => 'required|digits:10',
			'ca_address1' => 'required|string|max:255',
			'ca_address2' => 'nullable|string|max:255',
			'ca_state'    => 'required|integer|exists:states,id',
			'ca_city'     => 'required|integer|exists:cities,id',
			'ca_pincode'  => 'required|digits:6',
		]);

		if ($validator->fails()) {
			return response()->json([
				'status' => 'error',
				'errors' => $validator->errors()
			], 422);
		}

		// Generate a 6-digit password
		$plainPassword = rand(100000, 999999);

		// Create the CA user in users table
		$user = User::create([
			'name'         => $request->ca_name,
			'email'        => $request->ca_email,
			'phone'        => $request->ca_phone,
			'u_type'       => 1, // For Ca Create
			'addr_one'     => $request->ca_address1,
			'addr_two'     => $request->ca_address2,
			'country_id'   => 101,
			'state_id'     => $request->ca_state,
			'city_id'      => $request->ca_city,
			'pincode'      => $request->ca_pincode,
			'user_add_by'  => $userId,
			'status'       => 0,
			'password'     => Hash::make($plainPassword),
			'remember_token' => Str::random(60),
			'isActive'     => '1',
		]);

		// Fetch the adding user's company profile
		$companyProfile = DB::table('company_profiles')
			->where('userId', $userId)
			->first();

		$id = $user->id;
		$ca_name   = $request->ca_name;
		$ca_email  = $request->ca_email;
		$verifyUrl = url('/') . '/verify_email/' . base64_encode($id) . '/' . $ca_email;

		$body = view('User.email_template.verify_email_template_ca', compact('ca_name', 'ca_email', 'verifyUrl', 'plainPassword', 'companyProfile'))->render();
		$data_email = ['email' => $ca_email];

		$emailSubject = "Confirm your Email Address to verify your account";

		$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);

		return response()->json([
			'status' => 'success',
			'message' => 'CA Added Successfully, Verification Email send to Your CA',
		]);
	}

	public function CaAccessControl()
	{
		$authUser = auth()->user();
		$userId = currentOwnerId();

		checkCoreAccess('CA Access Control');

		// fetch company details (including stored ca_permissions)
		$compDetails = DB::table('users as u')
			->leftJoin('company_profiles as c', 'c.userId', '=', 'u.id')
			->where('u.id', $userId)
			->select('c.*', 'u.ca_permissions')
			->get();
		$compDetails = isset($compDetails[0]) ? $compDetails[0] : (object)[];

		$ca_assigns = DB::table('ca_assigns')
			->select(
				'ca_assigns.*',
				'users.name as ca_name',
				'users.email as ca_email',
				'users.ca_permissions'
			)
			->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.id')
			->where('ca_assigns.comp_id', $userId)
			->orderBy('ca_assigns.id', 'desc')
			->get();

		$accountant_access = DB::table('accountant_access')
			->where('is_active', 1)
			->orderBy('id')
			->get();

		return view('User.CaAccessControl', compact(
			'ca_assigns',
			'accountant_access',
			'compDetails',
			'userId'
		));
	}
}
