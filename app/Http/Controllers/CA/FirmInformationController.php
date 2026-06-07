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
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Ca_profiles;
use App\Models\Ca_banks;
use App\Models\Ca_partners;
use App\Models\Holiday;
use App\Models\WeeklySchedule;
use App\Models\Location;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\AuditLogger;

class FirmInformationController extends Controller
{
	public function __construct()
    {
        //$this->middleware('auth');
    }
    public function FirmInformation()
    {
		$userId = Auth::user()->id;
		$compDetails = DB::table('ca_profiles')->where('userId', '=', $userId)->get();
		$compDetails = isset($compDetails[0])?$compDetails[0]:"";

		$bankDetails = DB::table('ca_banks')->where('uid', '=', $userId)->get();
		$bankDetails = isset($bankDetails)?$bankDetails:[];

		$partnerDetails = DB::table('ca_partners')->where('uid', '=', $userId)->get();
		$partnerDetails = isset($partnerDetails)?$partnerDetails:[];
		//echo "<pre>";print_r($bankDetails);die;

		//$countries = Country::where('id', '=', '101')->get();
        $states_bill = State::where('country_id', '=', 101)->get();
		$cities_bill = City::where('state_id', '=', isset($compDetails->comp_bill_state)?$compDetails->comp_bill_state:0)->get();

		// Fetch holidays for the current user
		$holidays = Holiday::where('added_by', $userId)->orderBy('holidayDate', 'asc')->get();

		// Fetch weekly schedule for the current user
		$weeklySchedule = WeeklySchedule::where('added_by', $userId)->get()->keyBy('day');

		// Fetch locations for the current user
		$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();

		// echo "<pre>";print_r($compDetails);die;
		return view('Ca.firm-information')->with([
			//'countries'=>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'compDetails' => $compDetails,
			'bankDetails' => $bankDetails,
			'partnerDetails' => $partnerDetails,
			'holidays' => $holidays,
			'weeklySchedule' => $weeklySchedule,
			'locations' => $locations,
		]);

    }

	protected function validator_profile(array $data)
	{
		$rules = [];

		if (isset($data['comp_logo']) && $data['comp_logo'] instanceof \Illuminate\Http\UploadedFile) {
			$rules['comp_logo'] = 'required|image|mimes:jpeg,png,jpg|max:5120'; // 5MB
		}

		return Validator::make($data, $rules);
	}

	public function update_comp_logo_ca(Request $request)
	{
		$validation = $this->validator_profile($request->all());

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		$userId = Auth::user()->id;
		$comp_logo = '';
		$dataCheck = DB::table('ca_profiles')
					->select('id', 'comp_logo')
					->where('userId', $userId)
					->first();

		if ($request->hasFile('comp_logo')) {
			$file = $request->file('comp_logo');
			$fileName = date("YmdHis") . '-' . $file->getClientOriginalName();
			$destinationPath = public_path('storage/ca_profile');

			// Check if the old logo exists and delete it
			if (!empty($dataCheck) && file_exists($destinationPath . '/' . $dataCheck->comp_logo)) {
				unlink($destinationPath . '/' . $dataCheck->comp_logo);  // Delete old image
			}

			$file->move($destinationPath, $fileName);
			$comp_logo = $fileName;
		}

		if (empty($dataCheck)) {
			$insertLogo = ca_profiles::create([
				'userId' => $userId,
				'comp_logo' => $comp_logo,
				'created_at' => now(),
			]);

			if ($insertLogo) {
				return response()->json([
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Logo successfully updated',
					'image_name' => $comp_logo
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Logo update failed!'
				]);
			}
		} else {
			$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(['comp_logo' => $comp_logo]);

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/'),
				'message' => 'Logo successfully updated',
				'image_name' => $comp_logo
			]);
		}
	}




	public function delete_comp_logo_ca(Request $request)  {

		$userId = Auth::user()->id;
		$dataCheck = DB::table('ca_profiles')
					->select(DB::raw('ca_profiles.id'))
					->where('userId','=',$userId)
					->get()->toArray();
		if(empty($dataCheck)){
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Please upload new Logo'
				);
				return response()->json($msg);
		}else{

			$update = DB::table('ca_profiles')
				->where('userId', $userId)
				->update(
					 array(
							'comp_logo' => "",
					 )
				);

			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/'),
				'message' => 'Logo deleted successfully'
			);
			return response()->json($msg);

		}
	}

	// protected function validator_firm(array $data)
    // {
	// 	//echo "<pre>"; print_r($data);exit;
    //     return Validator::make($data, [
    //         'comp_name' => 'required|min:3',
    //         'comp_email' => 'required|email',
    //         'comp_phone' => 'required|min:10',
	// 		'no_ca_firm' => 'required',
	// 		'no_employee' => 'required',
	// 		// 'total_no_client' => 'required',
	// 		// 'comp_gst_no' => 'required',
	// 		'about_firm' => 'required',
    //         'comp_bill_addone' => 'required',
    //         //'comp_bill_country' => 'required',
    //         'comp_bill_state' => 'required',
    //         'comp_bill_city' => 'required',
    //         'comp_bill_pin' => 'required',
    //     ]
	// 	);
    // }

	protected function validator_firm(array $data)
	{
		return Validator::make($data, [

			'comp_name' => 'required|min:3',
			'comp_email' => 'required|email',
			'comp_phone' => 'required|digits:10',

			'no_ca_firm' => 'required|numeric',
			'no_employee' => 'required|numeric',

			'type_of_firm' => 'required',
			'constitution_type' => 'required',

			'year_of_experience' => 'required|numeric|min:0',

			'software_licenses' => 'required|string',

			// 'basic_percentage' => 'required|numeric|min:40|max:60',

			'about_firm' => 'required|string',

			'comp_bill_addone' => 'required|string',
			'comp_bill_state' => 'required',
			'comp_bill_city' => 'required',

			'comp_bill_pin' => 'required|digits:6',

			// Optional fields (no validation needed unless you want strict rules)
			// 'total_no_client' => 'nullable|numeric',
			// 'comp_gst_no' => 'nullable|string',
			// 'tan_no' => 'nullable|string',
			// 'pt_reg_no' => 'nullable|string',
			// 'epf_reg_no' => 'nullable|string',
			// 'esic_reg_no' => 'nullable|string',
		]);
	}

	protected function create_firm(array $data)
    {
		//print_r($data);exit;

        return Ca_profiles::create([
            'userId' => Auth::user()->id,
            'comp_name' => $data['comp_name'],
            'comp_email' => $data['comp_email'],
			'comp_phone' => $data['comp_phone'],
			'no_ca_firm' => $data['no_ca_firm'],
			'no_employee' => $data['no_employee'],
			'total_no_client' => $data['total_no_client'],
			
			'type_of_firm'       => $data['type_of_firm'] ?? "",
			'constitution_type'  => $data['constitution_type'] ?? "",
			'year_of_experience' => $data['year_of_experience'] ?? 0,
			'software_licenses'  => $data['software_licenses'] ?? "",
			'tan_no'             => $data['tan_no'] ?? "",
			'pt_reg_no'          => $data['pt_reg_no'] ?? "",
			'epf_reg_no'         => $data['epf_reg_no'] ?? "",
			'esic_reg_no'        => $data['esic_reg_no'] ?? "",
			
			// 'basic_percentage'   => $data['basic_percentage'],

			'comp_gst_no' 		=> $data['comp_gst_no'],
			'about_firm' 		=> isset($data['about_firm'])?$data['about_firm']:"",
			'comp_bill_addone'  => $data['comp_bill_addone'],
			'comp_bill_addtwo'  => isset($data['comp_bill_addtwo'])?$data['comp_bill_addtwo']:"",
			//'comp_bill_country' => $data['comp_bill_country'],
			'comp_bill_state'   => $data['comp_bill_state'],
			'comp_bill_city'    => $data['comp_bill_city'],
			'comp_bill_pin'     => $data['comp_bill_pin'],
			'created_at'        => date('Y-m-d H:i:s'),
        ]);
    }

     public function update_compdet_ca(Request $request)  {
        $validation = $this->validator_firm($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{

			$userId = Auth::user()->id;
			$dataCheck = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.id'))
							->where('userId','=',$userId)
							->get()->toArray();
			if(empty($dataCheck)){
				 $update = $this->create_firm($request->all());
			}else{
				$oldProfile = DB::table('ca_profiles')->where('userId', $userId)->first();

				$update = DB::table('ca_profiles')
					->where('userId', $userId)
					->update(
						 array(
								'comp_name' => $request->comp_name,
								'comp_email' => $request->comp_email,
								'comp_phone' => $request->comp_phone,
								'no_ca_firm' => $request->no_ca_firm,
								'no_employee' => $request->no_employee,
								'total_no_client' => $request->total_no_client,
								'comp_gst_no' => $request->comp_gst_no,
								// 'basic_percentage' => $request->basic_percentage,
								'about_firm' => isset($request->about_firm)?$request->about_firm:"",

								'comp_bill_addone' => $request->comp_bill_addone,
								'comp_bill_addtwo' => isset($request->comp_bill_addtwo)?$request->comp_bill_addtwo:"",
								//'comp_bill_country' => $request->comp_bill_country,
								'comp_bill_state' => $request->comp_bill_state,
								'comp_bill_city' => $request->comp_bill_city,
								'comp_bill_pin' => $request->comp_bill_pin,

								// ⭐ NEW FIELDS ADDED — Optional
								'type_of_firm'       => $request->type_of_firm ?? "",
								'constitution_type'  => $request->constitution_type ?? "",
								'year_of_experience' => $request->year_of_experience ?? 0,
								'software_licenses'  => $request->software_licenses ?? "",
								'tan_no'             => $request->tan_no ?? "",
								'pt_reg_no'          => $request->pt_reg_no ?? "",
								'epf_reg_no'         => $request->epf_reg_no ?? "",
								'esic_reg_no'        => $request->esic_reg_no ?? "",
						 )
					);
			}
			if ($update)
			{
				if (!empty($oldProfile)) 
				{
					$old = (array) $oldProfile;
					$newData = [
						'comp_name'           => $request->comp_name,
						'comp_email'          => $request->comp_email,
						'comp_phone'          => $request->comp_phone,
						'no_ca_firm'          => $request->no_ca_firm,
						'no_employee'         => $request->no_employee,
						'total_no_client'     => $request->total_no_client,
						'comp_gst_no'         => $request->comp_gst_no,
						// 'basic_percentage'    => $request->basic_percentage,
						'about_firm'          => $request->about_firm ?? '',
						'type_of_firm'        => $request->type_of_firm ?? '',
						'constitution_type'   => $request->constitution_type ?? '',
						'year_of_experience'  => $request->year_of_experience ?? '',
						'software_licenses'   => $request->software_licenses ?? '',
						'tan_no'              => $request->tan_no ?? '',
						'pt_reg_no'           => $request->pt_reg_no ?? '',
						'epf_reg_no'          => $request->epf_reg_no ?? '',
						'esic_reg_no'         => $request->esic_reg_no ?? '',
					];

					$changedOld = [];
					$changedNew = [];

					foreach ($newData as $key => $value) {
						if (!array_key_exists($key, $old)) continue;
						if ((string) $old[$key] !== (string) $value) {
							//Friendly & secure key names
							$label = ucwords(str_replace('_', ' ', $key));
							$changedOld[$label] = $old[$key];
							$changedNew[$label] = $value;
						}
					}

					if (!empty($changedNew)) {
						AuditLogger::logEntry(
							action: 'update',
							module: 'CA Profile',
							description: 'CA profile details updated',
							oldData: $changedOld,
							newData: $changedNew
						);
					}
				}

				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Profile details updated'
				);
				return response()->json($msg);

			}
        }
    }

	//Start CA Speclization details
	protected function create_speclization(array $data)
    {
		//print_r($data);exit;

        return Ca_profiles::create([
            'userId' => Auth::user()->id,
            'ca_spec' => $data['ca_spec'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
	public function update_ca_speclization(Request $request)
	{
		$userId = Auth::user()->id;
		$ca_spec = $request->ca_spec;

		// Validate: at least one must be selected
		if (empty($ca_spec)) {
			return response()->json([
				'status' => 'error',
				'class'  => 'err',
				'message' => 'Select at least one specialization'
			]);
		}

		// Check if "Other specialized services" is selected
		$otherServiceText = null;
		if (in_array('Other specialized services', $ca_spec)) {
			$otherServiceText = trim($request->other_service_text);

			// Validate textbox
			if ($otherServiceText == "" || $otherServiceText == null) {
				return response()->json([
					'status' => 'error',
					'class'  => 'err',
					'message' => 'Please describe your other specialized services'
				]);
			}
		}

		// Check entry existence
		$dataCheck = DB::table('ca_profiles')
						->select('id')
						->where('userId', $userId)
						->first();

		if (!$dataCheck) {
			// New entry
			$update = $this->create_speclization($request->all());
		} else {
			// Update existing
			$update = DB::table('ca_profiles')
				->where('userId', $userId)
				->update([
					'ca_spec'            => implode(',', $ca_spec),
					'other_service_text' => $otherServiceText
				]);
		}

		if ($update) {
			return response()->json([
				'status'  => 'success',
				'class'   => 'succ',
				'redirect'=> url('/'),
				'message' => 'Specialization details updated'
			]);
		}
	}


	//Start CA bank details
	public function update_bankdet_ca(Request $request)  {
			$userId = Auth::user()->id;
			$bank_name = array_filter($request->bank_name);
			$bank_branch = array_filter($request->bank_branch);
			$bank_holder_name = array_filter($request->bank_holder_name);
			$ac_no = array_filter($request->ac_no);
			$ifsc_code = array_filter($request->ifsc_code);
			$ac_upid = array_filter($request->ac_upid);

			if(!empty($bank_name) && !empty($bank_branch) && !empty($bank_holder_name) && !empty($ac_no) && !empty($ifsc_code) )
			{
				//get old data
				$oldBanks = DB::table('ca_banks')
							->where('uid', $userId)
							->get()
							->map(function ($row) {
								return [
									'bank name' => $row->bank_name,
									'bank branch' => $row->bank_branch,
									'bank holder ame' => $row->bank_holder_name,
									'ac no' => $row->ac_no,
									'ifsc code' => $row->ifsc_code,
									'ac upid' => $row->ac_upid,
								];
							})->toArray();

				$delBank = DB::table('ca_banks')->where('uid', $userId)->delete();

				foreach ($bank_name as $index => $value) {

					$insertBank = DB::table('ca_banks')->insertGetId([
									'uid' => $userId,
									'bank_name' => isset($bank_name[$index])?$bank_name[$index]:"",
									'bank_branch' => isset($bank_branch[$index])?$bank_branch[$index]:"",
									'bank_holder_name' => isset($bank_holder_name[$index])?$bank_holder_name[$index]:"",
									'ac_no' => isset($ac_no[$index])?$ac_no[$index]:"",
									'ifsc_code' => isset($ifsc_code[$index])?$ifsc_code[$index]:"",
									'ac_upid' => isset($ac_upid[$index])?$ac_upid[$index]:"",

								]);
				}
				if ($insertBank){
					//Log capture
					$newBanks = [];
					foreach ($bank_name as $index => $value) {
						$newBanks[] = [
							'bank name' => $bank_name[$index] ?? "",
							'bank branch' => $bank_branch[$index] ?? "",
							'bank holder name' => $bank_holder_name[$index] ?? "",
							'ac no' => $ac_no[$index] ?? "",
							'ifsc code' => $ifsc_code[$index] ?? "",
							'ac upid' => $ac_upid[$index] ?? "",
						];
					}
					AuditLogger::logEntry(
						action: 'updated',
						module: 'CA Bank Details',
						description: 'Bank details updated',
						oldData: $oldBanks,
						newData: $newBanks
					);

					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'Bank details updated'
					);
					return response()->json($msg);
				}
			}else{
					$msg = array(
						// 'status' => 'error',
						// 'class' => 'err',
						// 'redirect' => url('/'),
						'message' => 'Enter all details for bank'
					);
					return response()->json($msg);
			}
    }

	//Start CA partners details
	public function update_partner_ca(Request $request)  {
		$userId = Auth::user()->id;
		$partner_name = array_filter($request->partner_name);
		$partner_no = array_filter($request->partner_no);
		$partner_email = array_filter($request->partner_email);
		$practicing = array_filter($request->practicing);
		$partner_role = array_filter($request->partner_role);


		if(!empty($partner_name) && !empty($partner_no) && !empty($partner_email) && !empty($practicing) && !empty($partner_role) )
		{
			//get old data
			$oldPartners = DB::table('ca_partners')
							->where('uid', $userId)
							->get()
							->map(function ($row) {
								return [
									'Partner Name'  => $row->partner_name,
									'Partner No'    => $row->partner_no,
									'Partner Email' => $row->partner_email,
									'Practicing'    => $row->practicing,
									'Role'          => $row->partner_role,
								];
							})
							->toArray();

			$delPartners = DB::table('ca_partners')->where('uid', $userId)->delete();

			foreach ($partner_name as $index => $value) {

				$insertPartners = DB::table('ca_partners')->insertGetId([
								'uid' => $userId,
								'partner_name' => isset($partner_name[$index])?$partner_name[$index]:"",
								'partner_no' => isset($partner_no[$index])?$partner_no[$index]:"",
								'partner_email' => isset($partner_email[$index])?$partner_email[$index]:"",
								'practicing' => isset($practicing[$index])?$practicing[$index]:"",
								'partner_role' => isset($partner_role[$index])?$partner_role[$index]:"",

							]);
			}
			if ($insertPartners){
				//Log capture
				$newPartners = [];
				foreach ($partner_name as $index => $value) {
					$newPartners[] = [
						'Partner Name'  => $partner_name[$index] ?? "",
						'Partner No'    => $partner_no[$index] ?? "",
						'Partner Email' => $partner_email[$index] ?? "",
						'Practicing'    => $practicing[$index] ?? "",
						'Role'          => $partner_role[$index] ?? "",
					];
				}
				AuditLogger::logEntry(
					action: 'updated',
					module: 'CA Partners',
					description: 'CA Partners Updated',
					oldData: $oldPartners,
					newData: $newPartners
				);
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Partners details updated'
				);
				return response()->json($msg);
			}
		}else{
				$msg = array(
					// 'status' => 'error',
					// 'class' => 'err',
					// 'redirect' => url('/'),
					'message' => 'Enter all details for partners'
				);
				return response()->json($msg);
		}
    }

	//Start update CA profile attachment
	protected function validator_attachment(array $data)
	{
		return Validator::make($data, [
			// 'gst_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
			// 'pan_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
			// 'tan_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
			// 'cin_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
			// 'other_logo_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
			// 'signature_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
			// 'stamp_doc' => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
		]);
	}

	public function update_ca_attachment(Request $request) {

		$validation = $this->validator_attachment($request->all());
		if ($validation->fails())  {
			return response()->json($validation->errors()->toArray());
		} else {
			$userId = Auth::user()->id;
			$dataCheck = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.id, ca_profiles.gst_doc, ca_profiles.pan_doc, ca_profiles.tan_doc, ca_profiles.cin_doc, ca_profiles.other_logo_doc, ca_profiles.signature_doc, ca_profiles.stamp_doc'))
							->where('userId', '=', $userId)
							->get()->toArray();

			if (empty($dataCheck)) {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Please update Firm Details'
				);
				return response()->json($msg);
			} else {
				$update_check = DB::table('ca_profiles')
					->where('userId', $userId)
					->update(
						array(
							'chk_agree' => '1',
						)
					);

				// Define new folder location for all files
				$destinationPath = public_path('storage/ca_company_files');

				// Helper function to delete old file
				$deleteOldFile = function($oldFile) use ($destinationPath) {
					$oldFilePath = $destinationPath . '/' . $oldFile;
					if (!empty($oldFile) && file_exists($oldFilePath)) {
						unlink($oldFilePath); // Delete old file
					}
				};

				// Handle GST document upload
				if ($file = $request->hasFile('gst_doc')) {
					// Delete the old GST file if exists
					$deleteOldFile($dataCheck[0]->gst_doc);

					$file = $request->file('gst_doc');
					$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName1);
					$gst_doc = $fileName1;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'gst_doc' => $gst_doc,
							)
						);
				}

				// Handle PAN document upload
				if ($file = $request->hasFile('pan_doc')) {
					// Delete the old PAN file if exists
					$deleteOldFile($dataCheck[0]->pan_doc);

					$file = $request->file('pan_doc');
					$fileName2 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName2);
					$pan_doc = $fileName2;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'pan_doc' => $pan_doc,
							)
						);
				}

				// Handle TAN document upload
				if ($file = $request->hasFile('tan_doc')) {
					// Delete the old TAN file if exists
					$deleteOldFile($dataCheck[0]->tan_doc);

					$file = $request->file('tan_doc');
					$fileName3 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName3);
					$tan_doc = $fileName3;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'tan_doc' => $tan_doc,
							)
						);
				}

				// Handle CIN document upload
				if ($file = $request->hasFile('cin_doc')) {
					// Delete the old CIN file if exists
					$deleteOldFile($dataCheck[0]->cin_doc);

					$file = $request->file('cin_doc');
					$fileName4 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName4);
					$cin_doc = $fileName4;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'cin_doc' => $cin_doc,
							)
						);
				}

				// Handle Other Logo document upload
				if ($file = $request->hasFile('other_logo_doc')) {
					// Delete the old Other Logo file if exists
					$deleteOldFile($dataCheck[0]->other_logo_doc);

					$file = $request->file('other_logo_doc');
					$fileName5 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName5);
					$other_logo_doc = $fileName5;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'other_logo_doc' => $other_logo_doc,
							)
						);
				}

				// Handle Signature document upload
				if ($file = $request->hasFile('signature_doc')) {
					// Delete the old Signature file if exists
					$deleteOldFile($dataCheck[0]->signature_doc);

					$file = $request->file('signature_doc');
					$fileName6 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName6);
					$signature_doc = $fileName6;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'signature_doc' => $signature_doc,
							)
						);
				}

				// Handle Stamp document upload
				if ($file = $request->hasFile('stamp_doc')) {
					// Delete the old Stamp file if exists
					$deleteOldFile($dataCheck[0]->stamp_doc);

					$file = $request->file('stamp_doc');
					$fileName7 = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->move($destinationPath, $fileName7);
					$stamp_doc = $fileName7;

					// Update file in the database
					DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							array(
								'stamp_doc' => $stamp_doc,
							)
						);
				}

				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Document successfully updated',
					'gstdocstate' => "gstdocstate"
				);
				return response()->json($msg);
			}
		}
	}
}
