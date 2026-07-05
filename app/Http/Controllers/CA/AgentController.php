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
use App\Models\Busi_agents;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AuditLogger;

class AgentController extends Controller
{
    public function Index()
    {
        $title = 'Agents';
		$userId = Auth::user()->id;
		$agents = DB::table('busi_agents')->where('added_by', '=', $userId)->orderBy('id', 'DESC')->paginate(10);
		$agents_pagination = $agents;
        return view('Ca.agent-list')->with([
			'title' =>$title,
			'agents'=>$agents,
			'agents_pagination' =>$agents_pagination,
        ]);
    }
    public function AddAgent()
    {
        $states = State::where('country_id', '=', 101)->get();
        return view('Ca.add-agent')->with([
			'states'=>$states,
        ]);
    }

    protected function validator(array $data)
	{
		return Validator::make($data, [

			// Basic Info
			'agent_name'      => 'required|string|min:3|max:255',
			'agent_email'     => 'required|email|max:255',
			'agent_phone'     => 'required|digits_between:10,15',
			'agent_whats_no'  => 'required|digits_between:10,15',

			// Address
			'address_lineone' => 'required|string|max:255',
			// 'agent_country'   => 'required|string|max:100',
			'agent_state'     => 'required|string|max:100',
			'agent_city'      => 'required|string|max:100',
			'agent_pincode'   => 'required|digits_between:4,10',

			// Current Address
			// 'curragent_state'   => 'required|string|max:100',
			// 'curragent_city'    => 'required|string|max:100',
			// 'curragent_pincode' => 'required|digits_between:4,10',

			// Optional
			'company_name'    => 'nullable|string|max:255',
			'company_website' => 'nullable|url',

			// Image
			'agent_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

		], [

			'agent_name.required' => 'Agent name is required.',
			'agent_name.min'      => 'Agent name must be at least 3 characters.',

			'agent_email.required' => 'Email is required.',
			'agent_email.email'    => 'Enter a valid email address.',

			'agent_phone.required' => 'Contact number is required.',
			'agent_phone.digits_between' => 'Contact must be 10–15 digits.',

			'agent_whats_no.required' => 'WhatsApp number is required.',
			'agent_whats_no.digits_between' => 'WhatsApp number must be 10–15 digits.',

			'address_lineone.required' => 'Address is required.',
			// 'agent_country.required'   => 'Country is required.',
			'agent_state.required'     => 'State is required.',
			'agent_city.required'      => 'City is required.',
			'agent_pincode.required'   => 'Pincode is required.',

			// 'curragent_state.required'   => 'Current state is required.',
			// 'curragent_city.required'    => 'Current city is required.',
			// 'curragent_pincode.required' => 'Current pincode is required.',

			'company_website.url' => 'Enter a valid website URL.',

			'agent_image.image' => 'Uploaded file must be an image.',
			'agent_image.mimes' => 'Allowed image types: jpg, jpeg, png, webp.',
			'agent_image.max'   => 'Image size must be below 2MB.',
		]);
	}


    protected function create(array $data)
    {
		// echo "<pre>";print_r($data);exit;
        return Busi_agents::create([
            'added_by' => Auth::user()->id,
            'agent_id' => '',
            'agent_name' => $data['agent_name'],
            'agent_email' => $data['agent_email'],
            'agent_phone' => $data['agent_phone'],
            'agent_whats_no' => $data['agent_whats_no'],
            'company_name' => isset($data['company_name'])?$data['company_name']:"",
			'company_website' => isset($data['company_website'])?$data['company_website']:"",

			'address_lineone' => isset($data['address_lineone'])?$data['address_lineone']:"",
			'address_linetwo' => isset($data['address_linetwo'])?$data['address_linetwo']:"",
			//'agent_country' => $data['agent_country'],
			'agent_state' => $data['agent_state'],
			'agent_city' => $data['agent_city'],
			'agent_pincode' => $data['agent_pincode'],
			'curraddress_lineone' => isset($data['curraddress_lineone'])?$data['curraddress_lineone']:"",
			'curraddress_linetwo' => isset($data['curraddress_linetwo'])?$data['curraddress_linetwo']:"",
			//'agent_country' => $data['agent_country'],
			'curragent_state' => $data['curragent_state'],
			'curragent_city' => $data['curragent_city'],
			'curragent_pincode' => $data['curragent_pincode'],
			'agent_image' => isset($data['agent_image']) ? $data['agent_image'] : null,
			'status' => 1,
			'created_at' => date('Y-m-d H:i:s'),

        ]);
    }

	public function save_agent(Request $request)
	{
		$validation = $this->validator($request->all());

		// ✅ Proper JSON validation response
		if ($validation->fails()) {
			return response()->json([
				'status'  => 'validation_error',
				'errors'  => $validation->errors(),
				'message' => 'Please fix the validation errors.'
			], 422);
		}

		// ✅ Check duplicate email
		if (Busi_agents::where('agent_email', $request->agent_email)->exists()) {
			return response()->json([
				'status'  => 'error',
				'class'   => 'err',
				'message' => 'Email already exists'
			]);
		}

		// ✅ Image upload
		$filename = null;

		if ($request->hasFile('agent_image')) {
			$image = $request->file('agent_image');
			$filename = 'agent_' . time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
			$image->storeAs('public/business_agent', $filename);
		}

		// ✅ Create agent
		$agentData = $request->all();
		$agentData['agent_image'] = $filename;

		$insertAgent = $this->create($agentData);

		// ✅ Generate formatted agent_id
		DB::table('busi_agents')
			->where('id', $insertAgent->id)
			->update([
				'agent_id' => str_pad($insertAgent->id, 8, '0', STR_PAD_LEFT)
			]);

		return response()->json([
			'status'   => 'success',
			'class'    => 'succ',
			'redirect' => url('/agent-list'),
			'message'  => 'Agent added successfully'
		]);
	}


    // public function save_agent(Request $request)
	// {
	// 	$validation = $this->validator($request->all());
	// 	if ($validation->fails()) {
	// 		return response()->json($validation->errors()->toArray());
	// 	}

	// 	$existingAgent = Busi_agents::where('agent_email', $request->agent_email)->first();
	// 	if ($existingAgent) {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'class' => 'err',
	// 			'redirect' => url('/'),
	// 			'message' => 'Email already exists'
	// 		]);
	// 	}

	// 	// Handle image upload
	// 	$imagePath = null;
	// 	$filename = "";
	// 	if ($request->hasFile('agent_image')) {
	// 		$image = $request->file('agent_image');
	// 		$filename = 'agent_' . time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
	// 		$imagePath = $image->storeAs('public/business_agent', $filename); // stored in storage/app/public/business_agent
	// 	}

	// 	// Create agent
	// 	$agentData = $request->all();
	// 	$agentData['agent_image'] = $filename;
	// 	$insertAgent = $this->create($agentData);

	// 	$agentId = $insertAgent->id;

	// 	// Update agent_id field
	// 	DB::table('busi_agents')->where('id', $agentId)->update([
	// 		'agent_id' => str_pad($agentId, 8, '0', STR_PAD_LEFT),
	// 	]);

	// 	if ($insertAgent) {
	// 		return response()->json([
	// 			'status' => 'success',
	// 			'class' => 'succ',
	// 			'redirect' => url('/agent-list'),
	// 			'message' => 'Agent added successfully'
	// 		]);
	// 	} else {
	// 		return response()->json([
	// 			'status' => 'error',
	// 			'class' => 'err',
	// 			'redirect' => url('/'),
	// 			'message' => 'Enter all details for agent'
	// 		]);
	// 	}
	// }

    public function edit_agent($agentId)  {

		$agentId = base64_decode($agentId);
		$agent = DB::table('busi_agents')
								->where('id', '=', $agentId)
								->get();

		$agent = $agent[0];
		$countries = Country::where('id', '>', '0')->get();
        $states = State::where('id', '=', $agent->agent_state)->get();
		$cities = City::where('state_id', '=', $agent->agent_state)->get();

		//  echo "<pre>";print_r($agent);exit;

		 return view('Ca.edit-agent')->with([
				'countries'=>$countries,
				'states'=>$states,
				'cities'=>$cities,
				'agent' => $agent,
				'agentId' => $agentId
			]);
    }

	public function update_agent(Request $request)
	{
		$agentId = $request->id;

		$validation = $this->validator($request->all());

		// ✅ Proper JSON validation response (same as save)
		if ($validation->fails()) {
			return response()->json([
				'status'  => 'validation_error',
				'errors'  => $validation->errors(),
				'message' => 'Please fix the validation errors.'
			], 422);
		}

		// FETCH OLD DATA
		$agent = DB::table('busi_agents')->where('id', $agentId)->first();
		$old   = (array) $agent;

		$agentImageFilename = null;

		// ✅ Handle image upload
		if ($request->hasFile('agent_image')) {
			$image = $request->file('agent_image');
			$agentImageFilename = 'agent_' . time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
			$image->storeAs('public/business_agent', $agentImageFilename);
		}

		// ✅ Prepare update data
		$updateData = [
			'agent_name' => $request->agent_name,
			'agent_email' => $request->agent_email,
			'agent_phone' => $request->agent_phone,
			'agent_whats_no' => $request->agent_whats_no,
			'company_name' => $request->company_name ?? "",
			'company_website' => $request->company_website ?? "",

			'address_lineone' => $request->address_lineone ?? "",
			'address_linetwo' => $request->address_linetwo ?? "",
			'agent_state' => $request->agent_state,
			'agent_city' => $request->agent_city,
			'agent_pincode' => $request->agent_pincode,

			'curraddress_lineone' => $request->curraddress_lineone ?? "",
			'curraddress_linetwo' => $request->curraddress_linetwo ?? "",
			'curragent_state' => $request->curragent_state,
			'curragent_city' => $request->curragent_city,
			'curragent_pincode' => $request->curragent_pincode,
		];

		// ✅ Add image only if uploaded
		if ($agentImageFilename) {
			$updateData['agent_image'] = $agentImageFilename;
		}

		// ✅ Update agent
		$update = DB::table('busi_agents')
			->where('id', $agentId)
			->update($updateData);

		if ($update) {

			// ✅ Detect only changed fields for audit log
			$changedOld = [];
			$changedNew = [];

			foreach ($updateData as $key => $value) {
				if (array_key_exists($key, $old) && $old[$key] != $value) {
					$label = ucwords(str_replace('_', ' ', $key));
					$changedOld[$label] = $old[$key];
					$changedNew[$label] = $value;
				}
			}

			if (!empty($changedNew)) {
				AuditLogger::logEntry(
					action: 'update',
					module: 'Agent & Channel Partner',
					description: 'Agent details updated',
					oldData: $changedOld,
					newData: $changedNew
				);
			}

			$msg = [
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/agent-list'),
				'message' => 'Record details updated'
			];
		} else {
			$msg = [
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'No changes detected or update failed.'
			];
		}

		return response()->json($msg);
	}


	// public function update_agent(Request $request)
	// {
	// 	$agentId = $request->id;

	// 	$validation = $this->validator($request->all());
	// 	if ($validation->fails()) {
	// 		return response()->json($validation->errors()->toArray());
	// 	} else {
			
	// 		//FETCH OLD DATA
	// 		$agent = DB::table('busi_agents')->where('id', $agentId)->first();
	// 		$old = (array) $agent;
			
	// 		$agentImageFilename = null;

	// 		// Handle file upload if present
	// 		if ($request->hasFile('agent_image')) {
	// 			$image = $request->file('agent_image');
	// 			$agentImageFilename = 'agent_' . time() . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
	// 			$image->storeAs('public/business_agent', $agentImageFilename);
	// 		}

	// 		// Prepare data to update
	// 		$updateData = [
	// 			'agent_name' => $request->agent_name,
	// 			'agent_email' => $request->agent_email,
	// 			'agent_phone' => $request->agent_phone,
	// 			'agent_whats_no' => $request->agent_whats_no,
	// 			'company_name' => $request->company_name ?? "",
	// 			'company_website' => $request->company_website ?? "",

	// 			'address_lineone' => $request->address_lineone ?? "",
	// 			'address_linetwo' => $request->address_linetwo ?? "",
	// 			'agent_state' => $request->agent_state,
	// 			'agent_city' => $request->agent_city,
	// 			'agent_pincode' => $request->agent_pincode,

	// 			'curraddress_lineone' => $request->curraddress_lineone ?? "",
	// 			'curraddress_linetwo' => $request->curraddress_linetwo ?? "",
	// 			'curragent_state' => $request->curragent_state,
	// 			'curragent_city' => $request->curragent_city,
	// 			'curragent_pincode' => $request->curragent_pincode,
	// 		];

	// 		// Only add the image if a new one is uploaded
	// 		if ($agentImageFilename) {
	// 			$updateData['agent_image'] = $agentImageFilename;
	// 		}

	// 		// Update the agent
	// 		$update = DB::table('busi_agents')
	// 			->where('id', $agentId)
	// 			->update($updateData);

	// 		if ($update) 
	// 		{
	// 			//PREPARE LOG DATA (ONLY CHANGES)
	// 			$changedOld = [];
	// 			$changedNew = [];
	// 			foreach ($updateData as $key => $value) {
	// 				if (array_key_exists($key, $old) && $old[$key] != $value) {
	// 					$label = ucwords(str_replace('_', ' ', $key));
	// 					$changedOld[$label] = $old[$key];
	// 					$changedNew[$label] = $value;
	// 				}
	// 			}
	// 			if (!empty($changedNew)) {
	// 				AuditLogger::logEntry(
	// 					action: 'update',
	// 					module: 'Agent & Channel Partner',
	// 					description: 'Agent details updated',
	// 					oldData: $changedOld,
	// 					newData: $changedNew
	// 				);
	// 			}
	// 			$msg = [
	// 				'status' => 'success',
	// 				'class' => 'succ',
	// 				'redirect' => url('/agent-list'),
	// 				'message' => 'Record details updated'
	// 			];
	// 		} else {
	// 			$msg = [
	// 				'status' => 'error',
	// 				'class' => 'err',
	// 				'redirect' => url('/'),
	// 				'message' => 'Update not success!'
	// 			];
	// 		}

	// 		return response()->json($msg);
	// 	}
	// }

    public function delAgent(Request $request)
    {
		//FETCH AGENT BEFORE DELETE
		$agent = DB::table('busi_agents')->where('id', $request->id)->first();
		$oldData = [
			'Agent Name'  => $agent->agent_name ?? '',
			'Agent Phone' => $agent->agent_phone ?? '',
			'Company'     => $agent->company_name ?? '',
		];
        $delCust = DB::table('busi_agents')->where('id', $request->id)->delete();
		if($delCust){
			AuditLogger::logEntry(
						action: 'delete',
						module: 'Agent & Channel Partner',
						description: 'Agent deleted: ' . ($agent->agent_name ?? 'N/A'),
						oldData: $oldData,
						newData: null
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/agent-list'),
				'message' => 'Agent deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/agent-list'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

	public function AgentDetails($agentId)
    {
        $agentId = base64_decode($agentId);
		$agent = DB::table('busi_agents')
								->where('id', '=', $agentId)
								->get();

		$agent = $agent[0];
		$countries = Country::where('id', '>', '0')->get();
        $states = State::where('id', '=', $agent->agent_state)->get();
		$cities = City::where('state_id', '=', $agent->agent_state)->get();

		//  echo "<pre>";print_r($agent);exit;

		 return view('Ca.view-agent')->with([
				'countries'=>$countries,
				'states'=>$states,
				'cities'=>$cities,
				'agent' => $agent,
				'agentId' => $agentId
			]);
    }

	public function changeStatus(Request $request)
	{
		try {
			$agent = DB::table('busi_agents')->where('id', $request->agent_id)->first();
			DB::table('busi_agents')
				->where('id', $request->agent_id)
				->update(['status' => $request->status]);

			$action = ($request->status == 1) ? 'activated' : 'deactivated';
			AuditLogger::logEntry(
						action: $action,
						module: 'Agent & Channel Partner',
						description: 'Agent : ' . ($agent->agent_name ?? 'N/A'),
						oldData: null,
						newData: null
					);
			return response()->json([
				'status' => 'success',
				'message' => 'Agent status updated successfully.'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to update agent status.'
			]);
		}
	}


}
