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
use App\User;
use App\Country;
use App\State;
use App\City;
use App\Ca_profiles;
use App\Ca_banks;
use App\Ca_partners;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class CaProfileController extends Controller
{
     public function __construct()
    {
        //$this->middleware('auth');
    }
    public function caindex()
    {

        $userId = Auth::user()->id;
		$compDetails = DB::table('ca_profiles')->where('userId', '=', $userId)->get();
		$compDetails = isset($compDetails[0])?$compDetails[0]:"";

		$bankDetails = DB::table('ca_banks')->where('uid', '=', $userId)->get();
		$bankDetails = isset($bankDetails)?$bankDetails:[];

		$partnerDetails = DB::table('ca_partners')->where('uid', '=', $userId)->get();
		$partnerDetails = isset($partnerDetails)?$partnerDetails:[];
		//echo "<pre>";print_r($bankDetails);die;

		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('country_id', '=', isset($compDetails->comp_bill_country)?$compDetails->comp_bill_country:0)->get();
		$cities_bill = City::where('state_id', '=', isset($compDetails->comp_bill_state)?$compDetails->comp_bill_state:0)->get();


		//echo "<pre>";print_r($compDetails);die;
		return view('Ca.caprofile')->with([
			'countries'=>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'compDetails' => $compDetails,
			'bankDetails' => $bankDetails,
			'partnerDetails' => $partnerDetails
		]);

    }

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

	public function update_comp_logo_ca(Request $request)  {

		//print_r($_FILES);
		$validation = $this->validator_profile($request->all());
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
						$insertLogo = ca_profiles::create([
									'userId' => Auth::user()->id,
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
							$update = DB::table('ca_profiles')
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

	protected function validator_firm(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
            'comp_name' => 'required|min:3',
            'comp_email' => 'required|email',
            'comp_phone' => 'required|min:10',
            'comp_bill_addone' => 'required',
            'comp_bill_country' => 'required',
            'comp_bill_state' => 'required',
            'comp_bill_city' => 'required',
            'comp_bill_pin' => 'required',
        ]
		);
    }

	protected function create_firm(array $data)
    {
		//print_r($data);exit;

        return Ca_profiles::create([
            'userId' => Auth::user()->id,
            'comp_name' => $data['comp_name'],
            'comp_email' => $data['comp_email'],
			'comp_phone' => $data['comp_phone'],
			'comp_bill_addone' => $data['comp_bill_addone'],
			'comp_bill_addtwo' => isset($data['comp_bill_addtwo'])?$data['comp_bill_addtwo']:"",
			'comp_bill_country' => $data['comp_bill_country'],
			'comp_bill_state' => $data['comp_bill_state'],
			'comp_bill_city' => $data['comp_bill_city'],
			'comp_bill_pin' => $data['comp_bill_pin'],
			'created_at' => date('Y-m-d H:i:s'),
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
				$update = DB::table('ca_profiles')
					->where('userId', $userId)
					->update(
						 array(
								'comp_name' => $request->comp_name,
								'comp_email' => $request->comp_email,
								'comp_phone' => $request->comp_phone,

								'comp_bill_addone' => $request->comp_bill_addone,
								'comp_bill_addtwo' => isset($request->comp_bill_addtwo)?$request->comp_bill_addtwo:"",
								'comp_bill_country' => $request->comp_bill_country,
								'comp_bill_state' => $request->comp_bill_state,
								'comp_bill_city' => $request->comp_bill_city,
								'comp_bill_pin' => $request->comp_bill_pin,
						 )
					);
			}
			if ($update){
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
	public function update_ca_speclization(Request $request)  {
		$userId = Auth::user()->id;
		$ca_spec = ($request->ca_spec);

		if(!empty($ca_spec) )
		{
			$dataCheck = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.id'))
							->where('userId','=',$userId)
							->get()->toArray();
			if(empty($dataCheck)){
				 $update = $this->create_speclization($request->all());
			}else{
				$update = DB::table('ca_profiles')
					->where('userId', $userId)
					->update(
						 array(
								'ca_spec' => implode(',',$request->ca_spec),
						 )
					);
			}
			if($update){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/'),
					'message' => 'Speclization details updated'
				);
				return response()->json($msg);

			}
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Select at least one Speclization'
			);
			return response()->json($msg);
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
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
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
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Enter all details for partners'
				);
				return response()->json($msg);
		}
    }

	//Start update CA profile attachment
	protected function validator_attachment(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$gst_doc ='';
		$pan_doc ='';
		$tan_doc ='';
		$cin_doc ='';
		$other_logo_doc ='';
		$signature_doc ='';
		$stamp_doc ='';

		if($data['gst_doc'] =='' || $data['gst_doc'] =='undefined')
		{
			$gst_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}
		else{
			$gst_doc = '';
		}
		if($data['pan_doc'] =='' || $data['pan_doc'] =='undefined')
		{
			$pan_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}
		else{
			$pan_doc = '';
		}
		if($data['tan_doc'] =='' || $data['tan_doc'] =='undefined')
		{
			$tan_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}else{
			$tan_doc = '';
		}
		if($data['cin_doc'] =='' || $data['cin_doc'] =='undefined')
		{
			$cin_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}else{
			$cin_doc = '';
		}
		if($data['other_logo_doc'] =='' || $data['other_logo_doc'] =='undefined')
		{
			$other_logo_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}else{
			$other_logo_doc = '';
		}
		if($data['signature_doc'] =='' || $data['signature_doc'] =='undefined')
		{
			$signature_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}else{
			$signature_doc = '';
		}
		if($data['stamp_doc'] =='' || $data['stamp_doc'] =='undefined')
		{
			$stamp_doc = 'required|image|mimes:jpeg,png,jpg,pdf,PDF|max:1024';
		}else{
			$stamp_doc = '';
		}
		if($data['gstdocstate'] ==''){
			return Validator::make($data, [

				'gst_doc' => $gst_doc,
				'pan_doc' => $pan_doc,
				'tan_doc' => $tan_doc,
				'cin_doc' => $cin_doc,
				'other_logo_doc' => $other_logo_doc,
				'signature_doc' => $signature_doc,
				'stamp_doc' => $stamp_doc,
			]);
		}else{

			return Validator::make($data, [

			]);
		}
    }

	public function update_ca_attachment(Request $request)  {

		//print_r($_FILES);
		$validation = $this->validator_attachment($request->all());
		if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
				$userId = Auth::user()->id;
				$dataCheck = DB::table('ca_profiles')
							->select(DB::raw('ca_profiles.id,ca_profiles.gst_doc'))
							->where('userId','=',$userId)
							->get()->toArray();

				if(empty($dataCheck)){
					 $msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'Please update Firm Details'
					);
					return response()->json($msg);
				}else{

						if($file = $request->hasFile('gst_doc')) {
						$file = $request->file('gst_doc') ;

						$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName1);
						$gst_doc = $fileName1 ;

						//Update file
						$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							 array(
									'gst_doc' => $gst_doc,
							 )
						);
					}

					if($file = $request->hasFile('pan_doc')) {
						$file = $request->file('pan_doc') ;

						$fileName2 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName2);
						$pan_doc = $fileName2 ;

						//Update file
						$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							 array(
									'pan_doc' => $pan_doc,
							 )
						);
					}
					if($file = $request->hasFile('tan_doc')) {
						$file = $request->file('tan_doc') ;

						$fileName3 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName3);
						$tan_doc = $fileName3 ;

						//Update file
						$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							 array(
									'tan_doc' => $tan_doc,
							 )
						);
					}


					if($file = $request->hasFile('cin_doc')) {
						$file = $request->file('cin_doc') ;

						$fileName4 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName4);
						$cin_doc = $fileName4 ;

						//Update file
						$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							 array(
									'cin_doc' => $cin_doc,
							 )
						);
					}
					if($file = $request->hasFile('other_logo_doc')) {
						$file = $request->file('other_logo_doc') ;

						$fileName5 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName5);
						$other_logo_doc = $fileName5 ;

						//Update file
						$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							 array(
									'other_logo_doc' => $other_logo_doc,
							 )
						);
					}
					if($file = $request->hasFile('signature_doc')) {
						$file = $request->file('signature_doc') ;

						$fileName6 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName6);
						$signature_doc = $fileName6 ;

						//Update file
						$update = DB::table('ca_profiles')
						->where('userId', $userId)
						->update(
							 array(
									'signature_doc' => $signature_doc,
							 )
						);
					}
					if($file = $request->hasFile('stamp_doc')) {
						$file = $request->file('stamp_doc') ;

						$fileName7 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
						$destinationPath1 = public_path().'/uploads/company-files' ;

						$file->move($destinationPath1,$fileName7);
						$stamp_doc = $fileName7 ;

						//Update file
						$update = DB::table('ca_profiles')
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
