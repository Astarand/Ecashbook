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
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Ca_profiles;
use App\Models\Ca_assigns;
// use App\Company_profiles;
// use App\Company_banks;
use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use App\Models\Company_profiles;
use App\Models\Holiday;
use App\Models\WeeklySchedule;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\AuditLogger;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\User\DocLockerController;


class CompanyProfileController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function CompanyProfile()
    // {
		
	// 	//$this->middleware('auth'); 
	// 	$userId = Auth::user()->id;
		
	// 	$compDetails = DB::table('company_profiles')->where('userId', '=', $userId)->get();
	// 	// $compDetails = isset($compDetails[0])?$compDetails[0]:"";
	// 	$compDetails = isset($compDetails[0]) ? $compDetails[0] : (object)[];
		
	// 	$bankDetails = DB::table('company_banks')->where('uid', '=', $userId)->get();
	// 	$bankDetails = isset($bankDetails)?$bankDetails:[];
	// 	//echo "<pre>";print_r($bankDetails);die;
		
	// 	$countries = Country::where('id', '>', '0')->get();
	// 	$states = State::where('country_id', '=', 101)->get();
    // 	$states_bill = State::where('country_id', '=', isset($compDetails->comp_bill_country)?$compDetails->comp_bill_country:0)->get();
	// 	$cities_bill = City::where('state_id', '=', isset($compDetails->comp_bill_state)?$compDetails->comp_bill_state:0)->get();
		
	// 	$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country)?$compDetails->comp_ship_country:0)->get();
	// 	$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state)?$compDetails->comp_ship_state:0)->get();

		
	// 	// echo "<pre>";print_r($compDetails);die;
		
	// 	$ca_details = DB::table('users')
	// 				->select(DB::raw('users.*,ca_profiles.comp_logo,ca_profiles.comp_name,ca_profiles.total_no_client,
	// 				ca_profiles.comp_bill_addone,ca_profiles.comp_bill_country,ca_profiles.comp_bill_state,
	// 				ca_profiles.comp_bill_city,ca_profiles.comp_bill_pin,ca_profiles.ca_spec,ca_assigns.request_for,ca_assigns.ca_assign_status'))
	// 				->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
	// 				->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.ca_id')
	// 				->where('users.u_type','=',1) 
	// 				->where('ca_assigns.comp_id', '=', $userId)
	// 				->where('ca_assigns.ca_current_status', '=', 1)
	// 				->get();
	// 	$array = array();
	// 	foreach($ca_details as $k=>$val)
	// 	{
	// 		$array[$val->id]['id'] = $val->id;
	// 		$array[$val->id]['u_type'] = $val->u_type;
	// 		$array[$val->id]['name'] = $val->name;
	// 		$array[$val->id]['email'] = $val->email;
	// 		$array[$val->id]['phone'] = $val->phone;
	// 		$array[$val->id]['addr_one'] = $val->addr_one;
	// 		$array[$val->id]['addr_two'] = $val->addr_two;
	// 		$array[$val->id]['pincode'] = $val->pincode;
	// 		$array[$val->id]['status'] = $val->status;
	// 		$array[$val->id]['comp_name'] = $val->comp_name;
	// 		$array[$val->id]['comp_logo'] = $val->comp_logo;
	// 		$array[$val->id]['total_no_client'] = $val->total_no_client;
	// 		$array[$val->id]['comp_bill_addone'] = isset($val->comp_bill_addone)?$val->comp_bill_addone:"";
	// 		$array[$val->id]['comp_bill_pin'] = isset($val->comp_bill_pin)?$val->comp_bill_pin:"";
	// 		$array[$val->id]['ca_spec'] = $val->ca_spec;
	// 		$array[$val->id]['request_for'] = $val->request_for;

	// 		$state = State::where('id', '=', isset($val->comp_bill_state)?$val->comp_bill_state:0)->get();
	// 		$array[$val->id]['ca_state'] = isset($state[0]->name)?$state[0]->name:"";
			
	// 		$city = City::where('id', '=', isset($val->comp_bill_city)?$val->comp_bill_city:0)->get();
	// 		$array[$val->id]['ca_city'] = isset($city[0]->name)?$city[0]->name:"";

	// 		$array[$val->id]['ca_assign_status'] = isset($val->ca_assign_status)?$val->ca_assign_status:0;
			
	// 	}
	// 	$ca_details = json_decode(json_encode($array));
	// 	// echo "<pre>";print_r($compDetails);die;

	// 	return view('User.Companyprofile')->with([
	// 		'countries'=>$countries,
	// 		'states'=>$states,
	// 		'states_bill'=>$states_bill,
	// 		'cities_bill'=>$cities_bill,
	// 		'states_ship'=>$states_ship,
	// 		'cities_ship'=>$cities_ship,
	// 		'compDetails' => $compDetails,		
	// 		'bankDetails' => $bankDetails,		
	// 		'ca_details' => $ca_details		
	// 	]); 
    // }
	
	public function CompanyProfile()
	{

		//$this->middleware('auth'); 
		$userId = currentOwnerId();
		
		//$compDetails = DB::table('company_profiles')->where('userId', '=', $userId)->get();
		// $compDetails = isset($compDetails[0])?$compDetails[0]:"";
		$compDetails = DB::table('users as u')
				->leftJoin('company_profiles as c', 'c.userId', '=', 'u.id')
				->where('u.id', $userId)
				->select(
					'c.*',
					'u.email',
					'u.phone',
					'u.ca_permissions'
				)
				->get();
		$compDetails = isset($compDetails[0]) ? $compDetails[0] : (object)[];
		
		$directorDetails = DB::table('comp_directors')->where('compId', '=', $userId)->get();
		$directorDetails = isset($directorDetails) ? $directorDetails : [];

		//$bankDetails = DB::table('company_banks')->where('uid', '=', $userId)->get();
		$bankDetails = DB::table('banks')
						->where('added_by', '=', $userId)
						->where('propId', '=', null)
						->get();
		$bankDetails = isset($bankDetails) ? $bankDetails : [];
		//echo "<pre>";print_r($compDetails);die;

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

		$ca_details = DB::table('users')
			->select(DB::raw('users.*,ca_profiles.comp_logo,ca_profiles.comp_name,ca_profiles.total_no_client,
					ca_profiles.comp_bill_addone,ca_profiles.comp_bill_country,ca_profiles.comp_bill_state,
					ca_profiles.comp_bill_city,ca_profiles.comp_bill_pin,ca_profiles.ca_spec,ca_assigns.request_for,ca_assigns.ca_assign_status'))
			->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
			->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.ca_id')
			->where('users.u_type', '=', 1)
			->where('ca_assigns.comp_id', '=', $userId)
			->where('ca_assigns.ca_current_status', '=', 1)
			->get();
		
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
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['comp_logo'] = $val->comp_logo;			
			$array[$val->id]['total_no_client'] = $this->getCaTotalClient($val->id);
			$array[$val->id]['comp_bill_addone'] = isset($val->comp_bill_addone) ? $val->comp_bill_addone : "";
			$array[$val->id]['comp_bill_pin'] = isset($val->comp_bill_pin) ? $val->comp_bill_pin : "";
			$array[$val->id]['ca_spec'] = $val->ca_spec;
			$array[$val->id]['request_for'] = $val->request_for;

			$state = State::where('id', '=', isset($val->comp_bill_state) ? $val->comp_bill_state : 0)->get();
			$array[$val->id]['ca_state'] = isset($state[0]->name) ? $state[0]->name : "";

			$city = City::where('id', '=', isset($val->comp_bill_city) ? $val->comp_bill_city : 0)->get();
			$array[$val->id]['ca_city'] = isset($city[0]->name) ? $city[0]->name : "";

			$array[$val->id]['ca_assign_status'] = isset($val->ca_assign_status) ? $val->ca_assign_status : 0;
		}
		$ca_details = json_decode(json_encode($array));

		// Fetch the Company Documents for the current user

		$companyDocs = DB::table('user_documents')
						->where('user_id', $userId)
						->where(function($q){
							$q->where([
								['document_type','=','Company & Ownership Documents'],
								['file_type','=','Certificate of Incorporation']
							])
							->orWhere([
								['document_type','=','Licensing & Registration'],
								['file_type','=','Company PAN Card']
							])
							->orWhere([
								['document_type','=','Licensing & Registration'],
								['file_type','=','GST Registration Certificate']
							])
							->orWhere([
								['document_type','=','Licensing & Registration'],
								['file_type','=','Trade License']
							])
							->orWhere([
								['document_type','=','Licensing & Registration'],
								['file_type','=','PF Establishment Code Letter']
							])

							->orWhere([
								['document_type','=','Statutory & Compliance – PF-ESI & Labor Law'],
								['file_type','=','Professional Tax Returns']
							])
							->orWhere([
								['document_type','=','Owner / Director KYC & Other'],
								['file_type','=','Aadhar Card']
							])
							->orWhere([
								['document_type','=','Owner / Director KYC & Other'],
								['file_type','=','PAN Card (Individual)']
							])
							->orWhere([
								['document_type','=','Owner / Director KYC & Other'],
								['file_type','=','Latest Photograph']
							])
							->orWhere([
								['document_type','=','Owner / Director KYC & Other'],
								['file_type','=','Aadhar Card']
							])
							->orWhere([
								['document_type','=','Owner / Director KYC & Other'],
								['file_type','=','PAN Card (Individual)']
							])
							->orWhere([
								['document_type','=','Owner / Director KYC & Other'],
								['file_type','=','Latest Photograph']
							])
							->orWhere([
								['document_type','=','Company & Ownership Documents'],
								['file_type','=','Other']
							])
							->orWhere([
								['document_type','=','Board & Management Records'],
								['file_type','=','Powers of Attorney']
							])
							->orWhere([
								['document_type','=','Board & Management Records'],
								['file_type','=','Other']
							]);
						})
						->get();
		$docs = [];
		foreach($companyDocs as $doc){
			$docs[$doc->file_type] = $doc;
		}

		$accountant_access = DB::table('accountant_access')->where('is_active', 1)->get();
		//echo "<pre>";print_r($ca_details);die;
		// echo "<pre>";print_r($companyDocs);die;

		return view('User.Companyprofile')->with([
			'userId' => $userId,
			'countries' => $countries,
			'states' => $states,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'compDetails' => $compDetails,
			'directorDetails' => $directorDetails,
			'bankDetails' => $bankDetails,
			'ca_details' => $ca_details,
			'holidays' => $holidays,
			'weeklySchedule' => $weeklySchedule,
			'locations' => $locations,
			'docs' => $docs,
			'accountant_access' => $accountant_access
		]);
	}
	
	public function getCaTotalClient($caId)
	{
		$totalClients = DB::table('ca_assigns')
						->where('ca_assign_status', 1)
						->where('ca_current_status', 1)
						->where('ca_id', $caId)
						->count();
		return $totalClients;
	}

	public function updateRequestFor(Request $request)
	{
		$userId = currentOwnerId();
		$ca_id = $request->ca_id;
		$request_for = $request->request_for;
		if (!empty($request_for)) {
			$update = DB::table('ca_assigns')
				->where('comp_id', $userId)
				->where('ca_id', $ca_id)
				->update(
					array(
						'request_for' => implode(',', $request_for),
					)
				);
			if ($update) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Request success'
				);
				return response()->json($msg);
			} else {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Request failed!'
				);
				return response()->json($msg);
			}
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Please check atleast one item'
			);
			return response()->json($msg);
		}
	}
	
	// protected function validator(array $data)
    // {
	// 	//echo "<pre>"; print_r($data);exit;
    //     return Validator::make($data, [
    //         'comp_name' => 'required|min:3',
    //         // 'comp_gst_no' => 'required',
    //         // 'comp_email' => 'required|email',
    //         // 'comp_phone' => 'required|min:10',
    //         'comp_pan_no' => 'required'			
    //     ]
	// 	);
    // }

	protected function validator(array $data)
	{
		return Validator::make($data, [

			// Basic required
			'gst_reg'        => 'required',
			'comp_name'      => 'required|min:3',
			'comp_pan_no'    => 'required',
			// 'basic_percentage' => 'required',

			// GST conditional validation
			'gst_no' => 'required_if:gst_reg,Yes',
			'comp_tran_type' => 'required_if:gst_reg,Yes',

			// Company type conditional validation
// 			'cin' => 'required_if:comp_type,One person Company (OPC),LLP Company,PVT Ltd Company,LTD Company,Section-8 Company',

// 			'inc_date' => 'required_if:comp_type,One person Company (OPC),LLP Company,PVT Ltd Company,LTD Company,Section-8 Company',

			'other_comp_type' => 'required_if:comp_type,Other',

			// Billing required fields
			'comp_bill_name'   => 'required',
			'comp_bill_addone' => 'required',
			'comp_bill_state'  => 'required',
			'comp_bill_city'   => 'required',
			'comp_bill_pin'    => 'required',

		]);
	}


    protected function create(array $data)
    {
		//print_r($data);exit;
		$userId = currentOwnerId();		
		return Company_profiles::create([
				'userId' => $userId,

				'gst_reg'=> $data['gst_reg'],
				'gst_no' => $data['gst_no'],
				'comp_tran_type' => $data['comp_tran_type'],
				'comp_name' => $data['comp_name'],
				'comp_type' => $data['comp_type'],
				'cin' => $data['cin'],
				'inc_date' => $data['inc_date'],

				'comp_tan' => $data['comp_tan'],
				'comp_pan_no' => $data['comp_pan_no'],
				'udyam_reg' => !empty($data['udyam_reg_no']) ? 'Yes' : 'No',
				'udyam_reg_no' => $data['udyam_reg_no'],

				'trade_license_no' => $data['trade_license_no'],
				'shop_establishment_no' => $data['shop_establishment_no'],
				'fema_iec_no' => $data['fema_iec_no'],
				'state_excise_no' => $data['state_excise_no'],

				'comp_epf' => $data['comp_epf'],
				'comp_esic' => $data['comp_esic'],
				'comp_ptax_cert' => $data['comp_ptax_cert'],
				'comp_ptax' => $data['comp_ptax'],

				// 'basic_percentage' => $data['basic_percentage'],

				// Billing Details
				'comp_bill_gst_no' => $data['comp_bill_gst_no'],
				'comp_bill_name' => $data['comp_bill_name'],
				'comp_bill_addone' => $data['comp_bill_addone'],
				'comp_bill_addtwo' => $data['comp_bill_addtwo'],
				'comp_bill_country' => $data['comp_bill_country'] ?? '101',
				'comp_bill_state' => $data['comp_bill_state'],
				'comp_bill_city' => $data['comp_bill_city'],
				'comp_bill_pin' => $data['comp_bill_pin'],

				// Shipping Details
				'comp_ship_gst_no' => $data['comp_ship_gst_no'],
				'comp_ship_name' => $data['comp_ship_name'],
				'comp_ship_addone' => $data['comp_ship_addone'],
				'comp_ship_addtwo' => $data['comp_ship_addtwo'],
				'comp_ship_country' => $data['comp_ship_country'] ?? '101',
				'comp_ship_state' => $data['comp_ship_state'],
				'comp_ship_city' => $data['comp_ship_city'],
				'comp_ship_pin' => $data['comp_ship_pin'],

				'created_at' => date('Y-m-d H:i:s'),
			]);
    }

    public function update_compdet(Request $request)  {  
		//print_r($request);exit;
        $validation = $this->validator($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
            
			$userId = currentOwnerId();
			$dataCheck = DB::table('company_profiles')
							->select(DB::raw('company_profiles.id'))
							->where('userId','=',$userId)
							->get()->toArray();
			if(empty($dataCheck)){
				$update = $this->create($request->all());
			}else{
				//CHECK EXISTING PROFILE
				$existing = DB::table('company_profiles')->where('userId', $userId)->first();
				$update = DB::table('company_profiles')
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
								'udyam_reg' => empty($request->udyam_reg_no) ? 'No' : 'Yes',
								'udyam_reg_no' => $request->udyam_reg_no,
								'trade_license_no' => $request->trade_license_no,
								'shop_establishment_no' => $request->shop_establishment_no,
								'fema_iec_no' => $request->fema_iec_no,
								'state_excise_no' => $request->state_excise_no,
								'comp_tan' => $request->comp_tan,
								'comp_epf' => $request->comp_epf,
								'comp_esic' => $request->comp_esic,
								'comp_ptax_cert' => $request->comp_ptax_cert,
								'comp_ptax' => $request->comp_ptax,
								// 'comp_email' => $request->comp_email,
								// 'comp_phone' => $request->comp_phone,
								'comp_pan_no' => $request->comp_pan_no,
								'comp_website' => $request->comp_website,
								// 'basic_percentage' => $request->basic_percentage,
								
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
				//Audit log entry	
				$newData = [
					'gst_reg' => $request->gst_reg,
					'gst_no' => $request->gst_no,
					'comp_tran_type'=> $request->comp_tran_type,
					'comp_name' => $request->comp_name,
					'comp_type' => $request->comp_type,
					'cin' => $request->cin,
					'inc_date' => $request->inc_date,
					'udyam_reg_no' => $request->udyam_reg_no,
					'comp_tan' => $request->comp_tan,
					'comp_epf' => $request->comp_epf,
					'comp_esic' => $request->comp_esic,
					'comp_ptax_cert' => $request->comp_ptax_cert,
					'comp_ptax' => $request->comp_ptax,
					'comp_pan_no' => $request->comp_pan_no,
					// 'basic_percentage' => $request->basic_percentage
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
							module: 'Company Profile',
							description: "Company profile updated: {$request->comp_name}",
							oldData: $changedOld,
							newData: $changedNew
						);
				}
			}						
			$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => route('user.CompanyProfile'),
					'message' => 'Company details updated'
				);
			return response()->json($msg);	
        }
    }
	
	//Company business details update
	
	protected function validator_businessdet(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        // return Validator::make($data, [
        //     'comp_nature' => 'required',
        //     'exact_comp_nature' => 'required',
        //     // 'turnover_last_year' => 'required',
		// 	'opening_balance' => 'required',
        //     'start_date' => 'required',
        //     'comp_inv_digits' => 'required',
        // ]);

		return Validator::make(
			$data,
			[
				'comp_nature'       => 'required',
				'exact_comp_nature' => 'required',
			],
			[
				'comp_nature.required'       => 'Please select business category.',
				'exact_comp_nature.required' => 'Please enter exact nature of business.',
			]
		);
    }

    protected function create_businessdet(array $data)
    {
		//print_r($data);exit;
		$userId = currentOwnerId();
        return Company_profiles::create([
            'userId' => $userId,
            'comp_nature' => $data['comp_nature'],
            'exact_comp_nature' => $data['exact_comp_nature'],
            'turnover_last_year' => $data['turnover_last_year'],
			'start_date'=> $data['start_date'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

     public function update_businessdet(Request $request)
	{
		$validation = $this->validator_businessdet($request->all());
		if ($validation->fails()) {
			return response()->json([
				'status'  => 'error',
				'class'   => 'error',
				'message' => $validation->errors()->first()
			]);
		} else {

			$userId = currentOwnerId();;
			$dataCheck = DB::table('company_profiles')
				->select(DB::raw('company_profiles.id'))
				->where('userId', '=', $userId)
				->get()->toArray();
			if (empty($dataCheck)) {
				$update = $this->create_businessdet($request->all());
			} else {
				$update = DB::table('company_profiles')
					->where('userId', $userId)
					->update(
						array(
							'comp_nature' => $request->comp_nature,
							'exact_comp_nature' => $request->exact_comp_nature,
							'turnover_last_year' => $request->turnover_last_year,
							'opening_balance' => $request->opening_balance,
							'openingbalancecr' => $request->openingbalancecr ?? 0,
							'openingbalancedr' => $request->openingbalancedr ?? 0,
							'start_date' => $request->start_date,
							'comp_quo_digits' => $request->comp_quo_digits,
							'comp_prof_digits' => $request->comp_prof_digits,
							'comp_inv_digits' => $request->comp_inv_digits,
							'comp_po_digits' => $request->comp_po_digits,
						)
					);
			}
			if ($update) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('user.CompanyProfile'),
					'message' => 'Company business details updated'
				);
				return response()->json($msg);
			}
		}
	}
	
	protected function validatorContact(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make(
        $data,
			[
				'comp_email'   => 'required|email',
				'comp_phone'   => 'required|digits:10',
				'whatsapp_no'  => 'required|digits:10',
			],
			[
				'comp_email.required'  => 'Company email is required.',
				'comp_email.email'     => 'Please enter a valid email address.',

				'comp_phone.required'  => 'Company contact number is required.',
				'comp_phone.digits'    => 'Company contact number must be exactly 10 digits.',

				'whatsapp_no.required' => 'WhatsApp number is required.',
				'whatsapp_no.digits'   => 'WhatsApp number must be exactly 10 digits.',
			]
		);
    }
	
	public function update_contactDetails(Request $request)  {  
		//print_r($request);exit;
        $validation = $this->validatorContact($request->all());
        if ($validation->fails()) {
			return response()->json([
				'status'  => 'error',
				'class'   => 'error',
				'message' => $validation->errors()->first()
			]);
		}
        else{
            
			$userId = currentOwnerId();
			$dataCheck = DB::table('company_profiles')
							->select(DB::raw('company_profiles.id'))
							->where('userId','=',$userId)
							->get()->toArray();
			if(empty($dataCheck)){
				 $update = $this->create($request->all());
			}else{
				$update = DB::table('company_profiles')
					->where('userId', $userId)
					->update(
						array(
								
								'comp_email' => $request->comp_email,
								'comp_phone' => $request->comp_phone,
								'whatsapp_no' => $request->whatsapp_no,
								'comp_website' => $request->comp_website
								
						 )
					);
			}						
			if ($update){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => route('user.CompanyProfile'),
					'message' => 'Company details updated'
				);
				return response()->json($msg);	
			}
        }
    }

	//Start update bank details
	
	protected function validator_bank(array $data)
    {
		echo "<pre>"; print_r($data);exit;
        
    }

    protected function create_bank(array $data)
    {
		echo "<pre>"; print_r($data);exit;
        
    }

    public function update_bankdet(Request $request)
	{


		$userId = currentOwnerId();
		$bank_name = array_filter($request->bank_name);
		$bank_branch = array_filter($request->bank_branch);
		$bank_holder_name = array_filter($request->bank_holder_name);
		$ac_no = array_filter($request->ac_no);
		$ifsc_code = array_filter($request->ifsc_code);
		$ac_upid = array_filter($request->ac_upid);

		if (!empty($bank_name) && !empty($bank_branch) && !empty($bank_holder_name) && !empty($ac_no) && !empty($ifsc_code)) {
			$delBank = DB::table('company_banks')->where('uid', $userId)->delete();

			foreach ($bank_name as $index => $value) {

				$insertBank = DB::table('company_banks')->insertGetId([
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
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('user.CompanyProfile'),
					'message' => 'Bank details updated'
				);
				return response()->json($msg);
			}
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('user.CompanyProfile'),
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
	
	public function update_comp_attachment(Request $request)  {  
	
				$userId = currentOwnerId();
				$dataCheck = DB::table('company_profiles')
							->select(DB::raw('company_profiles.id,company_profiles.gst_doc'))
							->where('userId','=',$userId)
							->get()->toArray();
							
				if(empty($dataCheck)){
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('user.CompanyProfile'),
						'message' => 'Please update company details'
					);
					return response()->json($msg);
				}else{
					
					if ($request->hasFile('inc_certificate')) {
						$file = $request->file('inc_certificate');

						// Send to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Company & Ownership Documents",
							"Certificate of Incorporation",
							"Certificate of Incorporation of Company"
						);
					}
					
					
					if ($request->hasFile('pan_doc')) {
						$file = $request->file('pan_doc');

						// Send to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Licensing & Registration",
							"Company PAN Card",
							"Pan Card of Company"
						);
					}
					

					if ($request->hasFile('gst_doc')) {
						$file = $request->file('gst_doc');

						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Licensing & Registration",
							"GST Registration Certificate",
							"GST Registration Certificate of Company"
						);
					}
					
					if ($request->hasFile('trade_doc')) {
						$file = $request->file('trade_doc');
						
						// Save also in Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Licensing & Registration",   // Document Type
							"Trade License",              // File Type
							"Trade License Document"      // Document Name
						);
						
					}
					
					

					if ($request->hasFile('pf_doc')) {
						$file = $request->file('pf_doc');
						
						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Licensing & Registration",          // document_type
							"PF Establishment Code Letter",      // file_type
							"PF Establishment Code Letter"       // document_name
						);
					}

					
					if ($request->hasFile('ptex_doc')) {
						$file = $request->file('ptex_doc');

						// Also save in Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Statutory & Compliance – PF-ESI & Labor Law",   // document_type
							"Professional Tax Returns",                      // file_type
							"Professional Tax Registration Document"         // document_name
						);
					}
					
					if ($request->hasFile('first_diraadh_doc')) {
						$file = $request->file('first_diraadh_doc');
						
						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Owner / Director KYC & Other",   // document_type
							"Aadhar Card",                    // file_type
							"First Director Aadhaar Card"     // document_name
						);
					}
					
					if ($request->hasFile('firstpan_doc')) {
						$file = $request->file('firstpan_doc');
						
						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Owner / Director KYC & Other",  // document_type
							"PAN Card (Individual)",         // file_type
							"First Director PAN Card"        // document_name
						);
					}
					
					if ($request->hasFile('first_dirphoto_doc')) {
						$file = $request->file('first_dirphoto_doc');
						
						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Owner / Director KYC & Other",   // document_type
							"Latest Photograph",              // file_type
							"First Director Photograph"       // document_name
						);
					}
					
					if ($request->hasFile('second_aadha_doc')) {
						$file = $request->file('second_aadha_doc');
						
						 // Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Owner / Director KYC & Other",   // document_type
							"Aadhar Card",                    // file_type
							"Second Director Aadhaar Card"    // document_name
						);
					}
					
					if ($request->hasFile('second_pan_doc')) {
						$file = $request->file('second_pan_doc');

						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Owner / Director KYC & Other",  // document_type
							"PAN Card (Individual)",         // file_type
							"Second Director PAN Card"       // document_name
						);
					}
					
					$destinationPath = 'public/company_files'; // Stored in storage/app/public/company_files

					if ($request->hasFile('second_dirphoto_doc')) {
						$file = $request->file('second_dirphoto_doc');
						
						// Also save in Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Owner / Director KYC & Other",   // document_type
							"Latest Photograph",              // file_type
							"Second Director Photograph"      // document_name
						);
					}

					if ($request->hasFile('other_logo_doc')) {
						$file = $request->file('other_logo_doc');
						
						 // Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Company & Ownership Documents",   // document_type
							"Other",                           // file_type
							"Company Logo"                     // document_name
						);
					}

					if ($request->hasFile('signature_doc')) {
						$file = $request->file('signature_doc');
						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Board & Management Records",   // document_type
							"Powers of Attorney",           // file_type
							"Authorized Signature"          // document_name
						);
					}

					if ($request->hasFile('stamp_doc')) {
						$file = $request->file('stamp_doc');
						
						// Also save to Document Locker
						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Board & Management Records",  // document_type
							"Other",                       // file_type
							"Company Stamp"                // document_name
						);
					}
					
					
					//update chk_agree
					$update = DB::table('company_profiles')
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
	
	//Start update company profile
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
				$userId = currentOwnerId();
				$dataCheck = DB::table('company_profiles')
							->select(DB::raw('company_profiles.id'))
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
						$insertLogo = Company_profiles::create([
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
							$update = DB::table('company_profiles')
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
	
		$userId = currentOwnerId();
		$dataCheck = DB::table('company_profiles')
					->select(DB::raw('company_profiles.id'))
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
			
			$update = DB::table('company_profiles')
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


	public function uploadProfileImage(Request $request)
	{
		$request->validate([
			'fileUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		$userId = currentOwnerId();
		$file = $request->file('fileUpload');
		$fileName = 'comp_logo_' . $userId . '.' . $file->getClientOriginalExtension(); 
		$filePath = public_path('storage/profile/'); 

		// Check if company profile exists for the user
		$companyProfile = DB::table('company_profiles')->where('userId', $userId)->first();

		if ($companyProfile && !empty($companyProfile->comp_logo)) {
			$oldFilePath = $filePath . $companyProfile->comp_logo;

			// Delete the old file if it exists
			if (File::exists($oldFilePath)) {
				File::delete($oldFilePath);
			}
		}

		// Move the new file to storage/profile directory
		$file->move($filePath, $fileName);

		if ($companyProfile) {
			// Update existing record
			DB::table('company_profiles')
				->where('userId', $userId)
				->update(['comp_logo' => $fileName, 'updated_at' => now()]);
		} else {
			// Insert new record
			DB::table('company_profiles')->insert([
				'userId' => $userId,
				'comp_logo' => $fileName,
				'created_at' => now(),
				'updated_at' => now()
			]);
		}

		return response()->json([
			'success' => true,
			'message' => 'Company logo uploaded successfully!',
			'fileName' => $fileName
		]);
	}


	
	public function holidayStore(Request $request)
	{
		$userId = currentOwnerId();
		// Validate incoming request
		$validated = $request->validate([
			'holidayName' => 'required|string|max:255',
			'holidayDate' => 'required|date',
			'holidayType' => 'required|string|max:50',
			'holidayDescription' => 'nullable|string',

		]);
		$validated['added_by'] = $userId;           // user's ID
		$validated['u_type'] = Auth::user()->u_type;
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

	public function scheduleStore(Request $request)
    {
        $userId = currentOwnerId();
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
 
        DB::beginTransaction();
        try {
            foreach ($days as $day) {
                $incoming = [
                    'open'        => $this->cleanTimeInput($request->input($day.'_open')),
                    'close'       => $this->cleanTimeInput($request->input($day.'_close')),
                    'lunch_start' => $this->cleanTimeInput($request->input($day.'_lunch_start')),
                    'lunch_stop'  => $this->cleanTimeInput($request->input($day.'_lunch_stop')),
                    'status'      => $request->has($day.'_status') ? 'open' : 'closed',
                ]; // [attached_file:2]
 
                $existing = DB::table('weekly_schedules')
                    ->where('day',$day)->where('added_by',$userId)->first(); // [attached_file:2]
 
                // Preserve existing values when incoming is null/empty
                $opening = $incoming['open']        ?: ($existing->opening_time     ?? null);
                $closing = $incoming['close']       ?: ($existing->closing_time     ?? null);
                $lunchS  = $incoming['lunch_start'] ?: ($existing->lunch_time_start ?? null);
                $lunchE  = $incoming['lunch_stop']  ?: ($existing->lunch_time_stop  ?? null); // [attached_file:2]
 
                $workingHours = 0;
                if ($incoming['status'] === 'open' && $opening && $closing) {
                    $workingHours = $this->calculateWorkingHours($opening,$closing,$lunchS,$lunchE);
                } // [attached_file:2]
 
                $payload = [
                    'opening_time'     => $opening,
                    'closing_time'     => $closing,
                    'lunch_time_start' => $lunchS,
                    'lunch_time_stop'  => $lunchE,
                    'status'           => $incoming['status'],
                    'working_hours'    => round($workingHours,2),
                    'u_type'           => Auth::user()->u_type,
                    'updated_at'       => now(),
                ]; // [attached_file:2]
 
                DB::table('weekly_schedules')->updateOrInsert(
                    ['day'=>$day,'added_by'=>$userId],
                    $existing ? $payload : ($payload + ['created_at'=>now()])
                ); // [attached_file:2]
            }
 
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Schedule updated successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status'=>'error','message'=>'Error updating schedule: '.$e->getMessage()],500);
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

	public function holidayEdit($id)
	{
		try {
			$userId = currentOwnerId();

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

	public function holidayUpdate(Request $request, $id)
	{
		try {
			$userId = currentOwnerId();

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

	public function holidayDestroy($id)
	{
		try {
			$userId = currentOwnerId();

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

		$userId = currentOwnerId();
		$uType = Auth::user()->u_type;

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
	public function saveLocation(Request $request)
	{
		$userId = currentOwnerId();
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
				'u_type' => Auth::user()->u_type
			]);

			return response()->json(['message' => 'Location saved successfully.', 'type' => 'success']);
		} catch (\Exception $e) {
			return response()->json(['message' => 'Error: ' . $e->getMessage(), 'type' => 'error']);
		}
	}

	public function getLocation($id)
	{
		$userId = currentOwnerId();
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

	public function updateLocation(Request $request, $id)
	{
		$validated = $request->validate([
			'locationName' => 'required|string|max:100',
			'locationType' => 'required|string|max:50',
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'radius' => 'required|integer|min:10|max:5000',
			'status' => 'required|in:Active,Inactive'
		]);
		$userId = currentOwnerId();
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

	public function deleteLocation($id)
	{
		$userId = currentOwnerId();
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
	
	//Set CA menu permission from user
	public function saveCaPermissions(Request $request)
	{
		$user = User::find($request->user_id);

		$permissions = $request->permissions;

		$user->ca_permissions = $permissions;
		$user->save();

		return response()->json([
			'status' => true,
			'message' => 'Permissions updated successfully'
		]);
	}
}
