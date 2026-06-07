<?php

namespace App\Http\Controllers\Admin;

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
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use App\Models\Admin_profiles;
use App\Models\Holiday;
use App\Models\WeeklySchedule;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\AuditLogger;


class AdminProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
	
	public function AdminProfile()
	{

		//$this->middleware('auth'); 
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;

		$compDetails = DB::table('admin_profiles')->where('userId', '=', $userId)->get();
		// $compDetails = isset($compDetails[0])?$compDetails[0]:"";
		$compDetails = isset($compDetails[0]) ? $compDetails[0] : (object)[];

		$bankDetails = DB::table('admin_banks')->where('uid', '=', $userId)->get();
		$bankDetails = isset($bankDetails) ? $bankDetails : [];
		//echo "<pre>";print_r($bankDetails);die;

		// Fetch holidays for the current user
		$holidays = Holiday::where('added_by', $userId)->orderBy('holidayDate', 'asc')->get();

		// Fetch weekly schedule for the current user
		$weeklySchedule = WeeklySchedule::where('added_by', $userId)->get()->keyBy('day');

		// Fetch locations for the current user
		$locations = Location::where('added_by', $userId)->orderBy('created_at', 'desc')->get();

		$countries = Country::where('id', '>', '0')->get();
		$states = State::where('country_id', '=', 101)->get();
		$states_bill = State::where('country_id', '=', isset($compDetails->comp_bill_country) ? $compDetails->comp_bill_country : 0)->get();
		$cities_bill = City::where('state_id', '=', isset($compDetails->comp_bill_state) ? $compDetails->comp_bill_state : 0)->get();

		$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country) ? $compDetails->comp_ship_country : 0)->get();
		$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state) ? $compDetails->comp_ship_state : 0)->get();


		// echo "<pre>";print_r($compDetails);die;

		return view('Admin.Adminprofile')->with([
			'countries' => $countries,
			'states' => $states,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'compDetails' => $compDetails,
			'bankDetails' => $bankDetails,
			'holidays' => $holidays,
			'weeklySchedule' => $weeklySchedule,
			'locations' => $locations
		]);
	}

	
	
	protected function validator(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
            'comp_name' => 'required|min:3',
            // 'comp_gst_no' => 'required',
            'comp_email' => 'required|email',
            'comp_phone' => 'required|min:10',
            'comp_pan_no' => 'required'			
        ]
		);
    }

    protected function create(array $data)
    {
		//print_r($data);exit;
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
        return Admin_profiles::create([
            'userId' => $userId,
			'gst_reg'=> $data['gst_reg'],
            'gst_no' => $data['gst_no'],
			'comp_tran_type' => $data['comp_tran_type'],
            'comp_name' => $data['comp_name'],
			'comp_type' => $data['comp_type'],
			'cin' => $data['cin'],
			'inc_date' => $data['inc_date'],
            'comp_email' => $data['comp_email'],
			'comp_phone' => $data['comp_phone'],
			'comp_tan' => $data['comp_tan'],
			'comp_pan_no' => $data['comp_pan_no'],
			'comp_website' => $data['comp_website'],
			'udyam_reg' => $data['udyam_reg'],
			'udyam_reg_no' => $data['udyam_reg_no'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function admin_update_compdet(Request $request)  {  
		//print_r($request);exit;
        $validation = $this->validator($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
            
			$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
			$dataCheck = DB::table('admin_profiles')
							->select(DB::raw('admin_profiles.id'))
							->where('userId','=',$userId)
							->get()->toArray();
			if(empty($dataCheck)){
				 $update = $this->create($request->all());
			}else{
				//CHECK EXISTING PROFILE
				$existing = DB::table('admin_profiles')->where('userId', $userId)->first();
				$update = DB::table('admin_profiles')
					->where('userId', $userId)
					->update(
						array(
								'gst_reg' => $request->gst_reg,
								'gst_no' => $request->gst_no,
								'comp_tran_type'=> $request->comp_tran_type,
								'comp_name' => $request->comp_name,
								'comp_type' => $request->comp_type,
								'cin' => $request->cin,
								'inc_date' => $request->inc_date,
								'udyam_reg' => $request->udyam_reg,
								'udyam_reg_no' => $request->udyam_reg_no,
								'comp_tan' => $request->comp_tan,
								'comp_epf' => $request->comp_epf,
								'comp_esic' => $request->comp_esic,
								'comp_ptax_cert' => $request->comp_ptax_cert,
								'comp_ptax' => $request->comp_ptax,
								'comp_email' => $request->comp_email,
								'comp_phone' => $request->comp_phone,
								'comp_pan_no' => $request->comp_pan_no,
								'comp_website' => $request->comp_website,
								'basic_percentage' => $request->basic_percentage,
								
								'comp_bill_gst_no' => $request->comp_bill_gst_no,
								'comp_bill_name' => $request->comp_bill_name,
								'comp_bill_addone' => $request->comp_bill_addone,
								'comp_bill_addtwo' => $request->comp_bill_addtwo,
								'comp_bill_country' => $request->comp_bill_country ?? '101',
								'comp_bill_state' => $request->comp_bill_state,
								'comp_bill_city' => $request->comp_bill_city,
								'comp_bill_pin' => $request->comp_bill_pin,
								
								'comp_ship_gst_no' => $request->comp_ship_gst_no,
								'comp_ship_name' => $request->comp_ship_name,
								'comp_ship_addone' => $request->comp_ship_addone,
								'comp_ship_addtwo' => $request->comp_ship_addtwo,
								'comp_ship_country' => $request->comp_ship_country ?? '101',
								'comp_ship_state' => $request->comp_ship_state,
								'comp_ship_city' => $request->comp_ship_city,
								'comp_ship_pin' => $request->comp_ship_pin,
						 )
					);
			}						
			if ($update)
			{
				$newData = [
					'gst_reg' => $request->gst_reg,
					'gst_no' => $request->gst_no,
					'comp_tran_type'=> $request->comp_tran_type,
					'comp_name' => $request->comp_name,
					'comp_type' => $request->comp_type,
					'cin' => $request->cin,
					'inc_date' => $request->inc_date,
					'udyam_reg' => $request->udyam_reg,
					'udyam_reg_no' => $request->udyam_reg_no,
					'comp_tan' => $request->comp_tan,
					'comp_epf' => $request->comp_epf,
					'comp_esic' => $request->comp_esic,
					'comp_ptax_cert' => $request->comp_ptax_cert,
					'comp_ptax' => $request->comp_ptax,
					'comp_email' => $request->comp_email,
					'comp_phone' => $request->comp_phone,
					'comp_pan_no' => $request->comp_pan_no,
					'comp_website' => $request->comp_website,
					'basic_percentage' => $request->basic_percentage,
				];
				// FIND ONLY CHANGED FIELDS
				$old = (array) $existing;
				$changedOld = [];
				$changedNew = [];
				foreach ($newData as $key => $value) {
					if (array_key_exists($key, $old) && $old[$key] != $value) {
						$safeKey = ucwords(str_replace('_', ' ', $key));
						$changedOld[$safeKey] = $old[$key];
						$changedNew[$safeKey] = $value;
					}
				}				
				if (!empty($changedNew)) {
						AuditLogger::logEntry(
							action: 'update',
							module: 'Admin Profile',
							description: "Admin profile updated: {$request->comp_name}",
							oldData: $changedOld,
							newData: $changedNew
						);
				}
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => route('admin.AdminProfile'),
					'message' => 'Admin details updated'
				);
				return response()->json($msg);	
			}
        }
    }
	
	//Admin business details update
	
	protected function validator_businessdet(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
            'comp_nature' => 'required',
            'exact_comp_nature' => 'required',
            // 'turnover_last_year' => 'required',
            'start_date' => 'required',
        ]
		);
    }

    protected function admin_create_businessdet(array $data)
    {
		//print_r($data);exit;
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
        return Admin_profiles::create([
            'userId' => $userId,
            'comp_nature' => $data['comp_nature'],
            'exact_comp_nature' => $data['exact_comp_nature'],
            'turnover_last_year' => $data['turnover_last_year'],
			'start_date'=> $data['start_date'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

     public function admin_update_businessdet(Request $request)
	{
		$validation = $this->validator_businessdet($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {

			$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
			$dataCheck = DB::table('admin_profiles')
				->select(DB::raw('admin_profiles.id'))
				->where('userId', '=', $userId)
				->get()->toArray();
			if (empty($dataCheck)) {
				$update = $this->admin_create_businessdet($request->all());
			} else {
				//CHECK EXISTING PROFILE
				$existing = DB::table('admin_profiles')->where('userId', $userId)->first();
				$update = DB::table('admin_profiles')
					->where('userId', $userId)
					->update(
						array(
							'comp_nature' => $request->comp_nature,
							'exact_comp_nature' => $request->exact_comp_nature,
							'turnover_last_year' => $request->turnover_last_year,
							'start_date' => $request->start_date,
						)
					);
			}
			if ($update) 
			{
				$newData = [
					'comp_nature' => $request->comp_nature,
					'exact_comp_nature' => $request->exact_comp_nature,
					'turnover_last_year' => $request->turnover_last_year,
					'start_date' => $request->start_date,
				];
				// FIND ONLY CHANGED FIELDS
				$old = (array) $existing;
				$changedOld = [];
				$changedNew = [];
				foreach ($newData as $key => $value) {
					if (array_key_exists($key, $old) && $old[$key] != $value) {
						$safeKey = ucwords(str_replace('_', ' ', $key));
						$changedOld[$safeKey] = $old[$key];
						$changedNew[$safeKey] = $value;
					}
				}				
				if (!empty($changedNew)) {
						AuditLogger::logEntry(
							action: 'update',
							module: 'Admin Business Details',
							description: "Admin Business Details Updated",
							oldData: $changedOld,
							newData: $changedNew
						);
				}
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('admin.AdminProfile'),
					'message' => 'Admin business details updated'
				);
				return response()->json($msg);
			}
		}
	}

	//Start update bank details
	
	protected function admin_validator_bank(array $data)
    {
		echo "<pre>"; print_r($data);exit;
        
    }

    protected function admin_create_bank(array $data)
    {
		echo "<pre>"; print_r($data);exit;
        
    }

    public function admin_update_bankdet(Request $request)
	{


		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$bank_name = array_filter($request->bank_name);
		$bank_branch = array_filter($request->bank_branch);
		$bank_holder_name = array_filter($request->bank_holder_name);
		$ac_no = array_filter($request->ac_no);
		$ifsc_code = array_filter($request->ifsc_code);
		$ac_upid = array_filter($request->ac_upid);

		if (!empty($bank_name) && !empty($bank_branch) && !empty($bank_holder_name) && !empty($ac_no) && !empty($ifsc_code)) {
			//get old data before delete
			$oldBanks = DB::table('admin_banks')
						->where('uid', $userId)
						->get()
						->map(function ($row) {
							return [
								'Bank Name'       => $row->bank_name,
								'Branch'          => $row->bank_branch,
								'Account Holder'  => $row->bank_holder_name,
								'Account No'      => $row->ac_no,
								'IFSC Code'       => $row->ifsc_code,
								'UPI ID'          => $row->ac_upid,
							];
						})
						->toArray();
			$delBank = DB::table('admin_banks')->where('uid', $userId)->delete();

			foreach ($bank_name as $index => $value) {

				$insertBank = DB::table('admin_banks')->insertGetId([
					'uid' => $userId,
					'bank_name' => isset($bank_name[$index]) ? $bank_name[$index] : "",
					'bank_branch' => isset($bank_branch[$index]) ? $bank_branch[$index] : "",
					'bank_holder_name' => isset($bank_holder_name[$index]) ? $bank_holder_name[$index] : "",
					'ac_no' => isset($ac_no[$index]) ? $ac_no[$index] : "",
					'ifsc_code' => isset($ifsc_code[$index]) ? $ifsc_code[$index] : "",
					'ac_upid' => isset($ac_upid[$index]) ? $ac_upid[$index] : "",

				]);
			}
			if ($insertBank) {
				//Log capture
				$newBanks = [];
				foreach ($bank_name as $index => $value) {
					$newBanks[] = [
						'Bank Name'       => $bank_name[$index] ?? "",
						'Branch'          => $bank_branch[$index] ?? "",
						'Account Holder'  => $bank_holder_name[$index] ?? "",
						'Account No'      => $ac_no[$index] ?? "",
						'IFSC Code'       => $ifsc_code[$index] ?? "",
						'UPI ID'          => $ac_upid[$index] ?? "",
					];
				}
				AuditLogger::logEntry(
					action: 'updated',
					module: 'Admin Bank Details',
					description: 'Admin Bank Details',
					oldData: $oldBanks,
					newData: $newBanks
				);
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('admin.AdminProfile'),
					'message' => 'Bank details updated'
				);
				return response()->json($msg);
			}
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('admin.AdminProfile'),
				'message' => 'Enter all details for bank'
			);
			return response()->json($msg);
		}
	}

	


	protected function validator_attachment(array $data)
	{
		return Validator::make($data, [
			'inc_certificate' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'pan_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'gst_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'trade_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'pf_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'ptex_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'first_diraadh_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'firstpan_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'first_dirphoto_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'second_aadha_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'second_pan_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'second_dirphoto_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'other_logo_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'signature_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
			'stamp_doc' => 'image|mimes:jpeg,png,jpg,pdf,PDF|max:1024',
		]);
	}
	
	public function admin_update_comp_attachment(Request $request)  {  
	
		//print_r($_FILES);		
		//$validation = $this->validator_attachment($request->all());
		// if ($validation->fails())  {  
        //     return response()->json($validation->errors()->toArray());
        // }
        // else{
				$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
				$dataCheck = DB::table('admin_profiles')
							->select(DB::raw('admin_profiles.id,admin_profiles.gst_doc'))
							->where('userId','=',$userId)
							->get()->toArray();
							
				if(empty($dataCheck)){
					 $msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('admin.AdminProfile'),
						'message' => 'Please update admin details'
					);
					return response()->json($msg);
				}else{
					
					if ($request->hasFile('inc_certificate')) {
						$file = $request->file('inc_certificate');
						
						// Generate unique filename
						$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path
						$destinationPath1 = 'public/admin_files'; // Laravel Storage Path
					
						// Store the file
						$file->storeAs($destinationPath1, $fileName1);
					
						// Save the filename to the database
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['inc_certificate' => $fileName1]);
					}
					
					
					if ($request->hasFile('pan_doc')) {
						$file = $request->file('pan_doc');
					
						// Generate unique filename
						$fileName2 = date("YmdHis") . '-' . $file->getClientOriginalName();
					
						// Define the storage path
						$destinationPath2 = 'public/admin_files'; // Laravel Storage Path
					
						// Store the file
						$file->storeAs($destinationPath2, $fileName2);
					
						// Save the filename to the database
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['pan_doc' => $fileName2]);
					}
					

					if ($request->hasFile('gst_doc')) {
						$file = $request->file('gst_doc');
					
						// Generate unique filename
						$fileName3 = date("YmdHis") . '-' . $file->getClientOriginalName();
					
						// Define the storage path
						$destinationPath3 = 'public/admin_files'; 
					
						// Store the file
						$file->storeAs($destinationPath3, $fileName3);
					
						// Save the filename to the database
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['gst_doc' => $fileName3]);
					}
					
					
					
					
					if ($request->hasFile('trade_doc')) {
						$file = $request->file('trade_doc');
						
						// Generate unique filename
						$fileName4 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path
						$destinationPath4 = 'public/admin_files';
						
						// Store the file
						$file->storeAs($destinationPath4, $fileName4);
						
						// Save the filename to the database
						$trade_doc = $fileName4;
						
						// Update file path in the database
						$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['trade_doc' => $trade_doc]);
					}
					
					

					if ($request->hasFile('pf_doc')) {
						$file = $request->file('pf_doc');
						
						// Generate unique filename
						$fileName5 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path (use 'public' disk)
						$destinationPath5 = 'public/admin_files'; // Adjust the path as per your storage config
						
						// Store the file
						$path = $file->storeAs($destinationPath5, $fileName5);
						
						// Save the filename (relative path) to the database
						$pf_doc = $fileName5;
						
						// Update file path in the database
						$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['pf_doc' => $pf_doc]);
					}

					
					if ($request->hasFile('ptex_doc')) {
						$file = $request->file('ptex_doc');
						
						// Generate unique filename
						$fileName6 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path (using 'public' disk)
						$destinationPath6 = 'public/admin_files'; // Adjust the path as needed
						
						// Store the file
						$path = $file->storeAs($destinationPath6, $fileName6);
						
						// Save the filename (relative path) to the database
						$ptex_doc = $fileName6;
						
						// Update file path in the database
						$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['ptex_doc' => $ptex_doc]);
					}
					
					if ($request->hasFile('first_diraadh_doc')) {
						$file = $request->file('first_diraadh_doc');
						
						// Generate unique filename
						$fileName7 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path (using 'public' disk)
						$destinationPath7 = 'public/admin_files'; // Adjust path as needed
						
						// Store the file using the Laravel Storage system
						$path = $file->storeAs($destinationPath7, $fileName7);
						
						// Save the filename (relative path) to the database
						$first_diraadh_doc = $fileName7;
						
						// Update file path in the database
						$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['first_diraadh_doc' => $first_diraadh_doc]);
					}
					
					if ($request->hasFile('firstpan_doc')) {
						$file = $request->file('firstpan_doc');
						
						// Generate unique filename
						$fileName8 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path (using 'public' disk)
						$destinationPath1 = 'public/admin_files'; // Adjust path as needed
						
						// Store the file using the Laravel Storage system
						$path = $file->storeAs($destinationPath1, $fileName8);
						
						// Save the filename (relative path) to the database
						$firstpan_doc = $fileName8;
						
						// Update file path in the database
						$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['firstpan_doc' => $firstpan_doc]);
					}
					
					if ($request->hasFile('first_dirphoto_doc')) {
						$file = $request->file('first_dirphoto_doc');
						
						// Generate unique filename
						$fileName9 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path (using 'public' disk)
						$destinationPath1 = 'public/admin_files'; // Adjust path as needed
						
						// Store the file using Laravel Storage
						$path = $file->storeAs($destinationPath1, $fileName9);
						
						// Save the filename (relative path) to the database
						$first_dirphoto_doc = $fileName9;
						
						// Update file path in the database
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['first_dirphoto_doc' => $first_dirphoto_doc]);
					}
					
					if ($request->hasFile('second_aadha_doc')) {
						$file = $request->file('second_aadha_doc');
						
						// Generate unique filename
						$fileName10 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path
						$destinationPath = 'public/admin_files'; // Stored in storage/app/public/admin_files
						
						// Store the file
						$file->storeAs($destinationPath, $fileName10);
						
						// Update file path in the database
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['second_aadha_doc' => $fileName10]);
					}
					
					if ($request->hasFile('second_pan_doc')) {
						$file = $request->file('second_pan_doc');
						
						// Generate unique filename
						$fileName11 = date("YmdHis") . '-' . $file->getClientOriginalName();
						
						// Define the storage path
						$destinationPath = 'public/admin_files'; 
						
						// Store the file
						$file->storeAs($destinationPath, $fileName11);
						
						// Update file path in the database
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['second_pan_doc' => $fileName11]);
					}
					
					$destinationPath = 'public/admin_files'; // Stored in storage/app/public/admin_files

					if ($request->hasFile('second_dirphoto_doc')) {
						$file = $request->file('second_dirphoto_doc');
						$fileName12 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$file->storeAs($destinationPath, $fileName12);
						
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['second_dirphoto_doc' => $fileName12]);
					}

					if ($request->hasFile('other_logo_doc')) {
						$file = $request->file('other_logo_doc');
						$fileName13 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$file->storeAs($destinationPath, $fileName13);
						
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['other_logo_doc' => $fileName13]);
					}

					if ($request->hasFile('signature_doc')) {
						$file = $request->file('signature_doc');
						$fileName14 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$file->storeAs($destinationPath, $fileName14);
						
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['signature_doc' => $fileName14]);
					}

					if ($request->hasFile('stamp_doc')) {
						$file = $request->file('stamp_doc');
						$fileName15 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$file->storeAs($destinationPath, $fileName15);
						
						DB::table('admin_profiles')
							->where('userId', $userId)
							->update(['stamp_doc' => $fileName15]);
					}
					
					
					//update chk_agree
					$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(
								 array(
										'chk_agree' => $request->chk_agree ? 1 : 0,
								 )
							);
							
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'Document successfully updated',
						'gstdocstate' => "gstdocstate"
					);
					return response()->json($msg);
					
				}
		
		//}
	
	}
	
	//Start update admin profile
	protected function validator_profile(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$comp_logo ='';
		
		if($data['comp_logo'] =='undefined')
		{
			$comp_logo = 'required|image|mimes:jpeg,png,jpg|max:1024';
		}
		else{
			$comp_logo = '';
		}
		
        return Validator::make($data, [
			
			'comp_logo' => $comp_logo,
        ]);
    }
	
	public function update_comp_logo(Request $request)  {  
	
		//print_r($_FILES);		
		$validation = $this->validator_profile($request->all());
		if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
				$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
				$dataCheck = DB::table('admin_profiles')
							->select(DB::raw('admin_profiles.id'))
							->where('userId','=',$userId)
							->get()->toArray();
				if(empty($dataCheck)){
					if($file = $request->hasFile('comp_logo')) {
						$file = $request->file('comp_logo') ;
						
						$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath_thumb = public_path().'/uploads/profile' ;
						
						$img = Image::make($file->getRealPath());
						$img->fit(243, 168, function ($constraint) {
							$constraint->aspectRatio();
						})->save($destinationPath_thumb.'/'.$fileName1);
						$comp_logo = $fileName1 ;
						
						//Insert logo file
						$insertLogo = Admin_profiles::create([
									'userId' => $userId,
									'comp_logo' => $comp_logo,
									'created_at' => date('Y-m-d H:i:s'),
								]);
					}
					if($insertLogo){
						$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'redirect' => url('/'),
							'message' => 'Logo successfully updated',
							'image_name' => $comp_logo
						);
						return response()->json($msg);
					}else{
						$msg = array(
							'status' => 'error',
							'class' => 'err',
							'redirect' => url('/'),
							'message' => 'Logo update failed!'
						);
						return response()->json($msg);
					}
				}else{
					
						if($file = $request->hasFile('comp_logo')) {
							$file = $request->file('comp_logo') ;
							
							$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
							$destinationPath_thumb = public_path().'/uploads/profile' ;
							
							$img = Image::make($file->getRealPath());
							$img->fit(243, 168, function ($constraint) {
								$constraint->aspectRatio();
							})->save($destinationPath_thumb.'/'.$fileName1);
							$comp_logo = $fileName1 ;
							
							//Update file
							$update = DB::table('admin_profiles')
							->where('userId', $userId)
							->update(
								 array(
										'comp_logo' => $comp_logo,
								 )
							);
						}
						
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/'),
						'message' => 'Logo successfully updated',
						'image_name' => $comp_logo
					);
					return response()->json($msg);
					
				}
		
		}
	
	}
	
	
	public function delete_comp_logo(Request $request)  {  
	
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$dataCheck = DB::table('admin_profiles')
					->select(DB::raw('admin_profiles.id'))
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
			
			$update = DB::table('admin_profiles')
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
	
	public function getState(Request $request)
    {
		
		 $id = $request->id; 
					
			$result =State::query()
				   ->where('country_id', '=', $id) 
				   ->get()->toArray();
			
				$response = [];
		//echo "<pre>";print_r($result);exit;
		 foreach($result as $row){
		   $response[] = array("id"=>$row['id'], "name"=>$row['name']);
		}
		echo json_encode($response); 

    }
	
	public function getCity(Request $request)
    {
		
		  $id = $request->id; 
					
			$result =City::query()
				   ->where('state_id', '=', $id) 
				   ->get()->toArray();
			
				$response = [];
		//echo "<pre>";print_r($result);exit;
		 foreach($result as $row){
		   $response[] = array("id"=>$row['id'], "name"=>$row['name']);
		}
		echo json_encode($response); 
    }


	public function adminUploadProfileImage(Request $request)
	{
		$request->validate([
			'fileUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$file = $request->file('fileUpload');
		$fileName = 'comp_logo_' . $userId . '.' . $file->getClientOriginalExtension(); 
		$filePath = public_path('storage/profile/'); 

		// Check if admin profile exists for the user
		$adminProfile = DB::table('admin_profiles')->where('userId', $userId)->first();

		if ($adminProfile && !empty($adminProfile->comp_logo)) {
			$oldFilePath = $filePath . $adminProfile->comp_logo;

			// Delete the old file if it exists
			if (File::exists($oldFilePath)) {
				File::delete($oldFilePath);
			}
		}

		// Move the new file to storage/profile directory
		$file->move($filePath, $fileName);

		if ($adminProfile) {
			// Update existing record
			DB::table('admin_profiles')
				->where('userId', $userId)
				->update(['comp_logo' => $fileName, 'updated_at' => now()]);
		} else {
			// Insert new record
			DB::table('admin_profiles')->insert([
				'userId' => $userId,
				'comp_logo' => $fileName,
				'created_at' => now(),
				'updated_at' => now()
			]);
		}

		return response()->json([
			'success' => true,
			'message' => 'Admin logo uploaded successfully!',
			'fileName' => $fileName
		]);
	}


	
	public function adminHolidayStore(Request $request)
	{
		// Validate incoming request
		$validated = $request->validate([
			'holidayName' => 'required|string|max:255',
			'holidayDate' => 'required|date',
			'holidayType' => 'required|string|max:50',
			'holidayDescription' => 'nullable|string',

		]);
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$validated['added_by'] = $userId;           // user's ID
		$validated['u_type'] = 3;
		try {

			$holiday = Holiday::create($validated);

			if ($holiday) {
				return response()->json([
					'message' => 'Holiday added successfully',
					'holiday' => $holiday
				]);
			} else {
				// In case create() returns false
				return response()->json([
					'message' => 'Failed to add holiday'
				], 500);
			}
		} catch (\Exception $e) {
			return response()->json([
				'message' => 'Error occurred: ' . $e->getMessage()
			], 500);
		}
	}

	public function adminScheduleStore(Request $request)
	{
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
		
		try {
			// Check if weekly_schedules table exists, create if not
			if (!$this->tableExists('weekly_schedules')) {
				if (!$this->createWeeklySchedulesTable()) {
					return response()->json([
						'status' => 'error',
						'message' => 'Could not create weekly schedules table.'
					], 500);
				}
			}
			
			DB::beginTransaction();
			
			foreach ($days as $day) {
				$openTime = $request->input($day . '_open');
				$closeTime = $request->input($day . '_close');
				$lunchStart = $request->input($day . '_lunch_start');
				$lunchStop = $request->input($day . '_lunch_stop');
				$status = $request->has($day . '_status') ? 'open' : 'closed';
				
				// Log the raw data for this day
				Log::info("Processing {$day}:", [
					'open' => $openTime,
					'close' => $closeTime,
					'lunch_start' => $lunchStart,
					'lunch_stop' => $lunchStop,
					'status' => $status
				]);
				
				// Clean and validate time inputs
				$openTime = $this->cleanTimeInput($openTime);
				$closeTime = $this->cleanTimeInput($closeTime);
				$lunchStart = $this->cleanTimeInput($lunchStart);
				$lunchStop = $this->cleanTimeInput($lunchStop);
				
				// Calculate working hours using simple time calculation
				$workingHours = 0;
				if ($status === 'open' && $openTime && $closeTime) {
					$workingHours = $this->calculateWorkingHours($openTime, $closeTime, $lunchStart, $lunchStop);
				}
				
				// Use direct DB query to avoid model issues
				DB::table('weekly_schedules')->updateOrInsert(
					[
						'day' => $day,
						'added_by' => $userId
					],
					[
						'opening_time' => $openTime ?: '09:00',
						'closing_time' => $closeTime ?: '17:00',
						'lunch_time_start' => $lunchStart,
						'lunch_time_stop' => $lunchStop,
						'status' => $status,
						'working_hours' => round($workingHours, 2),
						'u_type' => 3,
						'updated_at' => now(),
						'created_at' => now()
					]
				);
			}
			
			DB::commit();
			
			return response()->json([
				'status' => 'success',
				'message' => 'Schedule updated successfully'
			]);
			
		} catch (\Exception $e) {
			DB::rollback();
			
			Log::error('Schedule Store Error:', [
				'message' => $e->getMessage(),
				'trace' => $e->getTraceAsString()
			]);
			
			return response()->json([
				'status' => 'error',
				'message' => 'Error updating schedule: ' . $e->getMessage()
			], 500);
		}
	}

	private function calculateWorkingHours($openTime, $closeTime, $lunchStart = null, $lunchStop = null)
	{
		if (!$openTime || !$closeTime) {
			return 0;
		}
		
		try {
			// Convert times to minutes for easier calculation
			$openMinutes = $this->timeToMinutes($openTime);
			$closeMinutes = $this->timeToMinutes($closeTime);
			
			if ($closeMinutes <= $openMinutes) {
				return 0; // Invalid time range
			}
			
			$totalMinutes = $closeMinutes - $openMinutes;
			
			// Subtract lunch break if provided
			if ($lunchStart && $lunchStop) {
				$lunchStartMinutes = $this->timeToMinutes($lunchStart);
				$lunchStopMinutes = $this->timeToMinutes($lunchStop);
				
				if ($lunchStopMinutes > $lunchStartMinutes) {
					$lunchDuration = $lunchStopMinutes - $lunchStartMinutes;
					$totalMinutes -= $lunchDuration;
				}
			}
			
			return max(0, $totalMinutes / 60);
		} catch (\Exception $e) {
			return 0;
		}
	}

	private function timeToMinutes($time)
	{
		if (!$time || !preg_match('/^\d{1,2}:\d{2}$/', $time)) {
			return 0;
		}
		
		list($hours, $minutes) = explode(':', $time);
		return (int)$hours * 60 + (int)$minutes;
	}

	private function tableExists($tableName)
	{
		try {
			return DB::getSchemaBuilder()->hasTable($tableName);
		} catch (\Exception $e) {
			return false;
		}
	}

	private function createWeeklySchedulesTable()
	{
		try {
			DB::statement("
				CREATE TABLE IF NOT EXISTS weekly_schedules (
					id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					day VARCHAR(255) NOT NULL,
					opening_time TIME NOT NULL,
					closing_time TIME NOT NULL,
					lunch_time_start TIME NULL,
					lunch_time_stop TIME NULL,
					status ENUM('open', 'closed') DEFAULT 'open',
					working_hours DECIMAL(4,2) DEFAULT 0,
					added_by BIGINT UNSIGNED NOT NULL,
					u_type INT DEFAULT 2,
					created_at TIMESTAMP NULL DEFAULT NULL,
					updated_at TIMESTAMP NULL DEFAULT NULL,
					UNIQUE KEY unique_day_user (day, added_by),
					FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE CASCADE
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
			");
			return true;
		} catch (\Exception $e) {
			Log::error('Error creating weekly_schedules table: ' . $e->getMessage());
			return false;
		}
	}

	private function cleanTimeInput($time)
	{
		if (empty($time)) {
			return null;
		}
		
		// Remove any extra whitespace
		$time = trim($time);
		
		// Ensure the format is HH:MM
		if (preg_match('/^\d{1,2}:\d{2}$/', $time)) {
			// Split and pad hours if needed
			list($hours, $minutes) = explode(':', $time);
			$hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
			return $hours . ':' . $minutes;
		}
		
		return null;
	}

	public function adminHolidayEdit($id)
	{
		try {
			$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;

			// Find the holiday and ensure it belongs to the current user
			$holiday = Holiday::where('id', $id)
				->where('added_by', $userId)
				->first();

			if (!$holiday) {
				return response()->json([
					'status' => 'error',
					'message' => 'Holiday not found or you do not have permission to edit this holiday.'
				], 404);
			}

			return response()->json([
				'status' => 'success',
				'holiday' => $holiday
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'An error occurred while fetching holiday data: ' . $e->getMessage()
			], 500);
		}
	}

	public function adminHolidayUpdate(Request $request, $id)
	{
		try {
			$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;

			// Find the holiday and ensure it belongs to the current user
			$holiday = Holiday::where('id', $id)
				->where('added_by', $userId)
				->first();

			if (!$holiday) {
				return response()->json([
					'status' => 'error',
					'message' => 'Holiday not found or you do not have permission to update this holiday.'
				], 404);
			}

			// Validate incoming request
			$validated = $request->validate([
				'holidayName' => 'required|string|max:255',
				'holidayDate' => 'required|date',
				'holidayType' => 'required|string|max:50',
				'holidayDescription' => 'nullable|string',
			]);

			// Update the holiday
			$updated = $holiday->update($validated);

			if ($updated) {
				return response()->json([
					'status' => 'success',
					'message' => 'Holiday updated successfully!',
					'holiday' => $holiday->fresh()
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'message' => 'Failed to update holiday. Please try again.'
				], 500);
			}
		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Validation failed.',
				'errors' => $e->errors()
			], 422);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'An error occurred while updating the holiday: ' . $e->getMessage()
			], 500);
		}
	}

	public function adminHolidayDestroy($id)
	{
		try {
			$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;

			// Find the holiday and ensure it belongs to the current user
			$holiday = Holiday::where('id', $id)
				->where('added_by', $userId)
				->first();

			if (!$holiday) {
				return response()->json([
					'status' => 'error',
					'message' => 'Holiday not found or you do not have permission to delete this holiday.'
				], 404);
			}

			// Delete the holiday
			$deleted = $holiday->delete();

			if ($deleted) {
				return response()->json([
					'status' => 'success',
					'message' => 'Holiday deleted successfully!'
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'message' => 'Failed to delete holiday. Please try again.'
				], 500);
			}
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'An error occurred while deleting the holiday: ' . $e->getMessage()
			], 500);
		}
	}

	public function saveSchedule(Request $request)
	{
		$scheduleData = $request->input('schedule');

		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$uType = 3;

		try {
			foreach ($scheduleData as $dayData) {
				WeeklySchedule::updateOrCreate(
					['day' => $dayData['day'], 'added_by' => $userId],
					[
						'opening_time' => $dayData['opening_time'],
						'closing_time' => $dayData['closing_time'],
						'status' => $dayData['status'],
						'working_hours' => $dayData['working_hours'],
						'added_by' => $userId,
						'u_type' => $uType
					]
				);
			}

			return response()->json(['message' => 'Schedule saved successfully', 'type' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['message' => 'Error saving schedule: ' . $e->getMessage(), 'type' => 'error']);
		}
	}
	public function adminSaveLocation(Request $request)
	{
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$validated = $request->validate([
			'locationName' => 'required|string|max:100',
			'locationType' => 'required|string|max:50',
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'radius' => 'required|integer|min:10|max:5000',
			'status' => 'required|in:Active,Inactive'
		]);

		try {
			Location::create([
				'location_name' => $validated['locationName'],
				'location_type' => $validated['locationType'],
				'latitude' => $validated['latitude'],
				'longitude' => $validated['longitude'],
				'radius' => $validated['radius'],
				'status' => $validated['status'],
				'added_by' => $userId,
				'u_type' => 3
			]);

			return response()->json(['message' => 'Location saved successfully.', 'type' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['message' => 'Error: ' . $e->getMessage(), 'type' => 'error']);
		}
	}

	public function getLocationAdmin($id)
	{
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		try {
			$location = Location::where('id', $id)
				->where('added_by', $userId)
				->first();

			if (!$location) {
				return response()->json(['success' => false, 'message' => 'Location not found']);
			}

			return response()->json(['success' => true, 'location' => $location]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
		}
	}

	public function updateLocationAdmin(Request $request, $id)
	{
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		$validated = $request->validate([
			'locationName' => 'required|string|max:100',
			'locationType' => 'required|string|max:50',
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'radius' => 'required|integer|min:10|max:5000',
			'status' => 'required|in:Active,Inactive'
		]);

		try {
			$location = Location::where('id', $id)
				->where('added_by', $userId)
				->first();

			if (!$location) {
				return response()->json(['message' => 'Location not found'], 404);
			}

			$location->update([
				'location_name' => $validated['locationName'],
				'location_type' => $validated['locationType'],
				'latitude' => $validated['latitude'],
				'longitude' => $validated['longitude'],
				'radius' => $validated['radius'],
				'status' => $validated['status']
			]);

			return response()->json(['message' => 'Location updated successfully.', 'type' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['message' => 'Error updating location: ' . $e->getMessage(), 'type' => 'error']);
		}
	}

	public function deleteLocationAdmin($id)
	{
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
		try {
			$location = Location::where('id', $id)
				->where('added_by', $userId)
				->first();

			if (!$location) {
				return response()->json(['message' => 'Location not found'], 404);
			}

			$locationName = $location->location_name;
			$location->delete();

			return response()->json(['message' => "Location '{$locationName}' has been deleted successfully."]);
		} catch (\Exception $e) {
			return response()->json(['message' => 'Error deleting location: ' . $e->getMessage()], 500);
		}
	}
}
