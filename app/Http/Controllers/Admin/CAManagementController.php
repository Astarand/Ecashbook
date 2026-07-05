<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Ca_profiles;
use App\Models\CaMessages;
use Validator;
use Redirect;
use DB;
use Auth;
use Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class CAManagementController extends Controller
{
    public function CAList()
    {
        $title = 'CA Lists';
		$userId = Auth::user()->id;
		if(Auth::user()->u_type ==3 || Auth::user()->u_type ==6){ //admin
			$users =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,ca_profiles.*'))
							->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
							->where('users.u_type', '=', 1)
							->orderBy('users.id', 'DESC')->paginate(10);
		}
		$users_pagination = $users;		
		//echo "<pre>"; print_r($users);exit;
		$array = array();
		foreach($users as $k=>$val)
		{
			$array[$val->uid]['id'] = $val->uid;
			$array[$val->uid]['comp_logo'] = ($val->comp_logo !="")?$val->comp_logo:$val->avatar;
			$array[$val->uid]['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
			$array[$val->uid]['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;			
			$array[$val->uid]['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;
            $array[$val->uid]['ca_add_by'] = ($val->ca_add_by !="")?$val->ca_add_by:$val->ca_add_by;
			
			$customerNo =  DB::table('ca_assigns')
							->select(DB::raw('ca_assigns.id'))
							->where('ca_id', '=', $val->uid)
							->where('ca_assign_status', '=', 1)
							->where('ca_assign_status', '=', 1)
							->get();
               //echo "<pre>"; print_r($customerNo);exit;             
			$array[$val->uid]['customerNo'] = count($customerNo);
			
			$states = State::where('country_id', '=', ($val->comp_bill_country !="")?$val->comp_bill_country:0)->get();
			$cities = City::where('state_id', '=', ($val->comp_bill_state !="")?$val->comp_bill_state:0)->get();
			$array[$val->uid]['state'] = isset($states[0]->name)?$states[0]->name:"";
			$array[$val->uid]['city'] = isset($cities[0]->name)?$cities[0]->name:"";
			$array[$val->uid]['comp_bill_pin'] = ($val->comp_bill_pin !="")?$val->comp_bill_pin:"";
			$array[$val->uid]['subs_percentage'] = $val->subs_percentage ?? 0;
			$array[$val->uid]['isCaActive'] = $val->isCaActive;
			$array[$val->uid]['status'] = $val->status;
			$array[$val->uid]['created_at'] = $val->created_at;
		}
		$users = json_decode(json_encode($array));
		
		//echo "<pre>"; print_r($users);exit;
		return view('Admin.ca-list')->with([
			'title' =>$title,
			'users'=>$users,
			'users_pagination' =>$users_pagination,
		]); 
        
       // return view('Admin.ca-list');
    }
	
	public function updateCaSubscription(Request $request)
	{
		$request->validate([
			'percentage' => 'required|integer|min:0|max:100'
		]);
		DB::table('ca_profiles')
			->where('userId', $request->ca_id)
			->update([
				'subs_percentage' => $request->percentage
			]);

		return response()->json(['success' => true]);
	}


	public function cadetails($caId)
    {
        $caId = $caId;
		//echo $caId;exit;
		$userId = Auth::user()->id;
		if(Auth::user()->u_type ==3 || Auth::user()->u_type ==6){ //admin
			$users =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,ca_profiles.*'))
							->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
							->where('users.id', '=', $caId)
							->where('users.u_type', '=', 1)
							->orderBy('users.id', 'DESC')->paginate(10);
		}
		//echo "<pre>"; print_r($users);exit;
		$array = array();
		foreach($users as $k=>$val)
		{
			$array['id'] = $val->uid;
			$array['comp_logo'] = ($val->comp_logo !="")?$val->comp_logo:$val->avatar;
			$array['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
			$array['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;			
			$array['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;
			$array['comp_website'] = ($val->comp_website !="")?$val->comp_website:"";
			$array['comp_bill_addone'] = ($val->comp_bill_addone !="")?$val->comp_bill_addone:$val->comp_bill_addone;
			
			//echo "<pre>"; print_r($val->uid);exit;
			$customerNo =  DB::table('ca_assigns')
							->select(DB::raw('ca_assigns.id'))
							->where('ca_id', '=', $val->uid)
							->where('ca_assign_status', '=', 1)
							->where('ca_assign_status', '=', 1)
							->get();
			$array['customerNo'] = count($customerNo);
			//Total tasks and earning
			$taskData = DB::table('task_managements')
							->where('userId', $val->uid)
							//->where('project_status', 3)
							->selectRaw('COUNT(id) as total_tasks, SUM(total_amount) as total_earning')
							->first();

			$array['total_tasks'] = $taskData->total_tasks ?? 0;
			$array['total_earning'] = $taskData->total_earning ?? 0;
			
			$states = State::where('country_id', '=', ($val->comp_bill_country !="")?$val->comp_bill_country:0)->get();
			$cities = City::where('state_id', '=', ($val->comp_bill_state !="")?$val->comp_bill_state:0)->get();
			$array['state'] = isset($states[0]->name)?$states[0]->name:"";
			$array['city'] = isset($cities[0]->name)?$cities[0]->name:"";
			$array['comp_bill_pin'] = ($val->comp_bill_pin !="")?$val->comp_bill_pin:"";
			$array['ca_spec'] = ($val->ca_spec !="")?$val->ca_spec:"";
			$array['status'] = $val->status;
			$array['created_at'] = $val->created_at;
		}
		$users = json_decode(json_encode($array));
		//echo "<pre>"; print_r($users);exit;
		
		$customers = DB::table('users')
					->select(
						'users.*',
						'company_profiles.comp_name',
						'company_profiles.comp_logo',
						'company_profiles.comp_bill_addone',
						'company_profiles.comp_bill_country',
						'company_profiles.comp_bill_state',
						'company_profiles.comp_bill_city',
						'company_profiles.comp_bill_pin',
						'ca_assigns.ca_assign_status',
						'ca_assigns.ca_current_status'
					)
					->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
					->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
					->where('users.ca_add_by', $caId)
					->paginate(10);




		//echo "<pre>"; print_r($customers);exit;
		$customers_pagination = $customers;
		
		$array2 = array();
		foreach($customers as $k=>$val)
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
			$array2[$val->id]['ca_assign_status'] = $val->ca_assign_status;
			$array2[$val->id]['ca_current_status'] = $val->ca_current_status;
		}
		
		$customers = json_decode(json_encode($array2));
		//echo "<pre>"; print_r($customers);exit;
        return view('Admin.ca-details')->with([
			'users'=>$users,
			'customers'=>$customers,
			'customers_pagination' => $customers_pagination
        ]);
    }
	
	// Activate / Deactivate CA
    public function statusUpdate(Request $request)
    {
        User::where('id', $request->ca_id)
            ->update(['status' => $request->status]);

        return response()->json([
            'status' => true,
            'message' => 'CA status updated successfully'
        ]);
    }

    // Send Message to CA
    public function sendCaMessage(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'ca_id'   => 'required',
			'subject' => 'required|string|max:255',
			'message' => 'required|string',
		]);

		// If validation fails → return JSON
		if ($validator->fails()) {
			return response()->json([
				'status' => false,
				'errors' => $validator->errors()
			], 422);
		}

		$fileName = null;
		$uploadPath = public_path('uploads/ca_messages');

		// Create folder if not exists
		if (!File::exists($uploadPath)) {
			File::makeDirectory($uploadPath, 0755, true);
		}

		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$fileName = time().'_'.$file->getClientOriginalName();
			$file->move($uploadPath, $fileName);
		}

		CaMessages::create([
			'ca_id'      => $request->ca_id,
			'admin_id'   => Auth::id(),
			'subject'    => $request->subject,
			'message'    => $request->message,
			'attachment' => $fileName
		]);

		return response()->json([
			'status' => true,
			'message' => 'Message sent successfully'
		]);
    }
    
}
