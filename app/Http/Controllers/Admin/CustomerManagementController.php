<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Ca_profiles;
use Validator;
use Redirect;
use DB;
use Auth;
use Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;

class CustomerManagementController extends Controller
{

	public function AdminCustomerList()
	{
		$title = 'Customer Lists';
		$userId = currentOwnerId();

		if (Auth::user()->u_type == 3 || Auth::user()->u_type == 6) {

			$users = DB::table('users')
				->select(DB::raw('
					users.id as uid,
					users.*,
					company_profiles.*,

					subscribers.plan_type,
					subscribers.paid_amount,
					subscribers.base_amount,
					subscribers.gst_amount,
					subscribers.gst_percentage,
					subscribers.adjustment_amount,
					subscribers.start_at,
					subscribers.end_at,
					subscribers.payment_status,
					subscribers.transaction_id,
					subscribers.status as subscription_status,

					subscription_plans.title as plan_title
				'))
				->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')

				// Active Subscription Join
				->leftJoin('subscribers', function ($join) {
					$join->on('users.id', '=', 'subscribers.uid')
						->where('subscribers.status', '=', 'Active');
				})

				// Subscription Plan Join
    			->leftJoin('subscription_plans', 'subscription_plans.id', '=', 'subscribers.pid')

				->where('users.u_type', '=', 2)
				->orderBy('users.id', 'DESC')
				//->paginate(10);
				->get();
		}

		$users_pagination = $users;

		$array = array();

		foreach ($users as $k => $val) {

			$array[$val->id]['id'] = $val->uid;

			$array[$val->id]['comp_logo'] =
				($val->comp_logo != "") ? $val->comp_logo : $val->avatar;

			$array[$val->id]['comp_name'] =
				($val->comp_name != "") ? $val->comp_name : $val->name;

			$array[$val->id]['comp_email'] =
				($val->comp_email != "") ? $val->comp_email : $val->email;

			$array[$val->id]['comp_phone'] =
				($val->comp_phone != "") ? $val->comp_phone : $val->phone;

			// =========================
			// Subscription Details
			// =========================

			$array[$val->id]['plan_type'] = $val->plan_type ?? '';
			$array[$val->id]['paid_amount'] = $val->paid_amount ?? '';
			$array[$val->id]['base_amount'] = $val->base_amount ?? '';
			$array[$val->id]['gst_amount'] = $val->gst_amount ?? '';
			$array[$val->id]['gst_percentage'] = $val->gst_percentage ?? '';
			$array[$val->id]['adjustment_amount'] = $val->adjustment_amount ?? '';
			$array[$val->id]['start_at'] = $val->start_at ?? '';
			$array[$val->id]['end_at'] = $val->end_at ?? '';
			$array[$val->id]['payment_status'] = $val->payment_status ?? '';
			$array[$val->id]['transaction_id'] = $val->transaction_id ?? '';
			$array[$val->id]['subscription_status'] = $val->subscription_status ?? '';

			// =========================
			// CA Assign
			// =========================

			$caAssignId = DB::table('ca_assigns')
				->select(DB::raw('ca_assigns.ca_id'))
				->where('comp_id', '=', $val->uid)
				->where('ca_assign_status', '=', 1)
				->first();

			$caAssignId = isset($caAssignId->ca_id) ? $caAssignId->ca_id : 0;

			// $assignCaName = DB::table('users')
			// 	->select(DB::raw('users.name'))
			// 	->where('id', '=', $caAssignId)
			// 	->first();

			// $array[$val->id]['assignCa'] =
			// 	isset($assignCaName->name) ? $assignCaName->name : "";

			$assignCa = DB::table('ca_profiles')
				->select('comp_name')
				->where('userId', $caAssignId)
				->first();

			$array[$val->id]['assignCa'] = $assignCa->comp_name ?? '';

			// =========================
			// State & City
			// =========================

			$state = State::where(
				'country_id',
				'=',
				($val->comp_bill_country != "") ? $val->comp_bill_country : 0
			)->first();

			$city = City::where(
				'state_id',
				'=',
				($val->comp_bill_state != "") ? $val->comp_bill_state : 0
			)->first();

			$array[$val->id]['state'] =
				isset($state->name) ? $state->name : "";

			$array[$val->id]['city'] =
				isset($city->name) ? $city->name : "";

			$array[$val->id]['comp_bill_pin'] =
				($val->comp_bill_pin != "") ? $val->comp_bill_pin : "";

			$array[$val->id]['status'] = $val->status;

			$array[$val->id]['created_at'] = $val->created_at;

			$array[$val->id]['plan_title'] = $val->plan_title ?? '';
		}

		$users = json_decode(json_encode($array));
		// echo ("<pre>"); print_r($users);exit;
		return view('Admin.admin-customer-list')->with([
			'title' => $title,
			'users' => $users,
			'users_pagination' => $users_pagination,
		]);
	}

    public function customerDetails($cId)
    {
        $cId = base64_decode($cId);
        $title = 'Customer Details';
        $userId = currentOwnerId();
        if($cId == 0){
            return redirect()->back()->with('error', 'Invalid Customer ID');
        }
		$caUsers = DB::table('users')
					->select(DB::raw('users.id,users.name'))
					->where('u_type', 1)
					->where('status', 1)
					->get();
					
		$bankDetails = DB::table('banks')
						->where('added_by', $cId)
						->first();
		
		if(Auth::user()->u_type ==3 || Auth::user()->u_type ==6){ //admin
			$users =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,company_profiles.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.id', '=', $cId)
							->where('users.u_type', '=', 2)
							->get();
		}	
		$array = array();
		foreach($users as $k=>$val)
		{
			$array['id'] = $val->uid;
			$array['comp_logo'] = ($val->comp_logo !="")?$val->comp_logo:$val->avatar;
			$array['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
			$array['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;			
			$array['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;
			$array['comp_website'] = ($val->comp_website !="")?$val->comp_website:"";
			
			$caAssignId =  DB::table('ca_assigns')
							->select(DB::raw('ca_assigns.ca_id'))
							->where('comp_id', '=', $val->uid)
							->where('ca_assign_status', '=', 1)
							->where('ca_assign_status', '=', 1)
							->get();
            //$caAssignId = isset($caAssignId[0]->ca_id)?$caAssignId[0]->ca_id:0;
            //$array['caAssignId'] = $caAssignId;
            //echo "<pre>"; print_r($caAssignId);exit;
			$assignCaName =  DB::table('users')
							->select(DB::raw('users.name'))
							->where('id', '=', $caAssignId[0]->ca_id ?? null)
							->get();
			$array['assignCa'] = isset($assignCaName[0]->name)?$assignCaName[0]->name:"";
			
			$states = State::where('country_id', '=', ($val->comp_bill_country !="")?$val->comp_bill_country:0)->get();
			$cities = City::where('state_id', '=', ($val->comp_bill_state !="")?$val->comp_bill_state:0)->get();
			$array['state'] = isset($states[0]->name)?$states[0]->name:"";
			$array['city'] = isset($cities[0]->name)?$cities[0]->name:"";
			$array['comp_bill_pin'] = ($val->comp_bill_pin !="")?$val->comp_bill_pin:"";
			$array['status'] = $val->status;
			$array['created_at'] = $val->created_at;
		}
		$users = json_decode(json_encode($array));
		//echo "<pre>"; print_r($caUsers);
		//echo "<pre>"; print_r($users);exit;
		
		//start payment
		
		// Task statistics
		$totalTasks = DB::table('task_managements')
			->where('company_id', $cId)
			->count();

		$ongoingTasks = DB::table('task_managements')
			->where('company_id', $cId)
			->where('project_status', 2)
			->count();

		$pendingTasks = DB::table('task_managements')
			->where('company_id', $cId)
			->where('project_status', 1)
			->count();

		$totalPayments = DB::table('task_managements')
			->where('company_id', $cId)
			->sum('total_amount');

		$totalDuePayments = DB::table('task_managements')
			->where('company_id', $cId)
			->sum('due_amount');

		$recurringPayments = DB::table('task_managements')
			->where('company_id', $cId)
			->sum('advance_payment');


		// Task list
		$tasks = DB::table('task_managements')
			->leftJoin('task_category', 'task_category.id', '=', 'task_managements.task_category')
			->select(
				'task_managements.*',
				'task_category.task_category_name as category_name'
			)
			->where('task_managements.company_id', $cId)
			->orderBy('task_managements.id', 'DESC')
			->get();
	
        return view('Admin.customer-details')->with([
			'users'=>$users,	
			'caUsers'=>$caUsers,	
			'bankDetails'=>$bankDetails,	
			'totalTasks' => $totalTasks,
			'ongoingTasks' => $ongoingTasks,
			'pendingTasks' => $pendingTasks,
			'totalPayments' => $totalPayments,
			'totalDuePayments' => $totalDuePayments,
			'recurringPayments' => $recurringPayments,
			'tasks' => $tasks,
        ]);
    }
}
