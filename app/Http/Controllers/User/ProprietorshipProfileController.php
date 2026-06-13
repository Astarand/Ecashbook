<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Redirect;
// use Validator;
// use DB;
use Auth;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use App\Models\Proprietorship_profiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\AuditLogger;
use App\Http\Controllers\User\DocLockerController;


class ProprietorshipProfileController extends Controller
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
	
	public function checkProprietorshipCompany(Request $request)
	{
		$userId = currentOwnerId();

		// ✅ Validate company name
		$request->validate([
			'company_name' => 'required|string|max:255'
		]);

		// Check if company already exists
		$exists = DB::table('proprietorship_profiles')
			->where('comp_name', $request->company_name)
			->exists();

		if ($exists) {
			return response()->json([
				'status' => 'exists',
				'message' => 'Company name already exists'
			]);
		}

		// Get company profile data
		$company = DB::table('company_profiles')
			->where('userId', $userId)
			->first();

		//Insert only (no update)
		DB::table('proprietorship_profiles')->insert([
			'userId'      => $userId,
			'comp_name'   => $request->company_name,
			'gst_reg'     => !empty($company->gst_no) ? 'Yes' : 'No',
			'gst_no'      => $company->gst_no ?? '',
			'comp_pan_no' => $company->comp_pan_no ?? '',
			'comp_type'   => $company->comp_type ?? '',
			'created_at'  => now(),
			'updated_at'  => now()
		]);

		return response()->json([
			'status' => 'success',
			'message' => 'Company created successfully'
		]);
	}
	
	public function proprietorship_lists()
	{
		$userId = currentOwnerId();
		checkCoreAccess('Multi Proprietorships');

		// Parent Company
		$parentCompany = DB::table('company_profiles')
			->where('userId', $userId)
			->select(
				'id',
				'comp_name',
				'comp_pan_no',
				'gst_no',
				DB::raw("'parent' as company_type")
			);

		// Proprietorship Companies
		$proprietorships = DB::table('proprietorship_profiles')
			->where('userId', $userId)
			->select(
				'id',
				'comp_name',
				'comp_pan_no',
				'gst_no',
				DB::raw("'proprietorship' as company_type")
			);

		// Merge Data
		$data = $parentCompany
			->unionAll($proprietorships)
			->get();

		return view(
			'User.proprietorship.proprietorship-list',
			compact('data')
		);
	}


    public function proprietorship_edit($id)
	{
		checkCoreAccess('Multi Proprietorships');
		$id = Crypt::decrypt($id);
		
		$compDetails = DB::table('proprietorship_profiles')
						->where('id', $id)
						->first();
		//echo "<pre>";print_r($compDetails);exit;
		$userId = $compDetails->id;
		
		$directorDetails = DB::table('prop_directors')->where('compId', '=', $userId)->get();
		$directorDetails = isset($directorDetails) ? $directorDetails : [];

		//$bankDetails = DB::table('prop_banks')->where('uid', '=', $userId)->get();
		$bankDetails = DB::table('banks')->where('propId', '=', $userId)->get();
		$bankDetails = isset($bankDetails) ? $bankDetails : [];

		$main_comp_id = $compDetails->userId;

		//------- Basic Percentage --------
		// $basic_percentage = DB::table('company_profiles')
		// 					->where('userId', $main_comp_id)
		// 					->value('basic_percentage');
		

		$companyDocs = DB::table('user_documents')
						->where('user_id', $main_comp_id)
						->where('proprietorship_id', $id)
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
			$docs[$doc->document_name] = $doc;
		}

		
		return view('User.proprietorship.proprietorship_profile')->with([
			'compDetails' => $compDetails,
			'directorDetails' => $directorDetails,
			'bankDetails' => $bankDetails,
			'docs' => $docs,
			// 'basic_percentage' => $basic_percentage
		]);
	}

	
	public function proprietorship_delete($id)
	{
		DB::beginTransaction();

		try {

			// Delete from all related tables
			DB::table('sales')->where('propId', $id)->delete();
			DB::table('purchases')->where('propId', $id)->delete();
			DB::table('quotations')->where('propId', $id)->delete();
			DB::table('proformas')->where('propId', $id)->delete();
			DB::table('puos')->where('propId', $id)->delete();

			DB::table('income')->where('propId', $id)->delete();
			DB::table('expenses')->where('propId', $id)->delete();

			DB::table('assets')->where('propId', $id)->delete();
			DB::table('asset_vouchers')->where('propId', $id)->delete();
			DB::table('liabilities')->where('propId', $id)->delete();

			DB::table('banks')->where('propId', $id)->delete();
			DB::table('bank_trans')->where('prop_id', $id)->delete(); // ⚠️ different column

			DB::table('mcash_credit_debits')->where('propId', $id)->delete();
			DB::table('inventory_expenses')->where('propId', $id)->delete();
			DB::table('employees')->where('propId', $id)->delete();

			// user documents table
			DB::table('user_documents')->where('proprietorship_id', $id)->delete();

			// Finally delete main record
			DB::table('proprietorship_profiles')->where('id', $id)->delete();

			DB::commit();

			return redirect()->route('proprietorship.list')
				->with('success', 'Record Deleted Successfully');

		} catch (\Exception $e) {

			DB::rollback();

			return redirect()->back()
				->with('error', 'Something went wrong! ' . $e->getMessage());
		}
	}
	

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
			'cin' => 'required_if:comp_type,One person Company (OPC),LLP Company,PVT Ltd Company,LTD Company,Section-8 Company',

			'inc_date' => 'required_if:comp_type,One person Company (OPC),LLP Company,PVT Ltd Company,LTD Company,Section-8 Company',

			'other_comp_type' => 'required_if:comp_type,Other',

		]);
	}


    protected function create(array $data)
    {
		//print_r($data);exit;
		$userId = currentOwnerId();		
		return Proprietorship_profiles::create([
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

				'created_at' => date('Y-m-d H:i:s'),
			]);
    }

    public function update_compdet_proprietorship(Request $request)  
	{  
		//echo "<pre>";print_r($_POST);exit;
		
        $validation = $this->validator($request->all());
        if ($validation->fails())  {  
            return response()->json($validation->errors()->toArray());
        }
        else{
			$userId = currentOwnerId();
			$id = $request->id;
			$encryptedId = Crypt::encrypt($id);
			$existing = DB::table('proprietorship_profiles')->where('id', $id)->first();
			$update = DB::table('proprietorship_profiles')
				->where('id', $id)
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
							'comp_pan_no' => $request->comp_pan_no,
							'comp_website' => $request->comp_website,
							// 'basic_percentage' => $request->basic_percentage,
					)
				);
				
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('proprietorship-edit/'.$encryptedId),
				'message' => 'Company details updated'
			);
			return response()->json($msg);
        }
    }
	
	//Company business details update
	protected function validator_businessdet(array $data)
    {

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
        return Proprietorship_profiles::create([
            'userId' => $userId,
            'comp_nature' => $data['comp_nature'],
            'exact_comp_nature' => $data['exact_comp_nature'],
            'turnover_last_year' => $data['turnover_last_year'],
			'start_date'=> $data['start_date'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function update_businessdet_proprietorship(Request $request)
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
			$id = $request->id;
			$encryptedId = Crypt::encrypt($id);
			$update = DB::table('proprietorship_profiles')
				->where('id', $id)
				->update(
					array(
						'comp_nature' => $request->comp_nature,
						'exact_comp_nature' => $request->exact_comp_nature,
						'start_date' => $request->start_date,
						'comp_quo_digits' => $request->comp_quo_digits,
						'comp_prof_digits' => $request->comp_prof_digits,
						'comp_inv_digits' => $request->comp_inv_digits,
						'comp_po_digits' => $request->comp_po_digits,
					)
				);
				
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('proprietorship-edit/'.$encryptedId),
				'message' => 'Company business details updated'
			);
			return response()->json($msg);
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
	
	public function update_contactDetails_proprietorship(Request $request)  {  
		//print_r($request);exit;
        $validation = $this->validatorContact($request->all());
        if ($validation->fails()) {
			return response()->json([
				'status'  => 'error',
				'class'   => 'error',
				'message' => $validation->errors()->first()
			]);
		} else {
            
			$userId = currentOwnerId();
			$id = $request->id;
			$encryptedId = Crypt::encrypt($id);
			$update = DB::table('proprietorship_profiles')
					->where('id', $id)
					->update(
						array(
								
								'comp_email' => $request->comp_email,
								'comp_phone' => $request->comp_phone,
								'whatsapp_no' => $request->whatsapp_no,
								'comp_website' => $request->comp_website
								
						 )
					);
					
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('proprietorship-edit/'.$encryptedId),
				'message' => 'Company details updated'
			);
			return response()->json($msg);	
        }
    }
	
	public function update_bankdet_proprietorship(Request $request)
	{

		//echo "<pre>";print_r($_POST);exit;
		$userId = $request->userId;
		$addBy = currentOwnerId();
		$bank_name = array_filter($request->bank_name);
		$bank_branch = array_filter($request->bank_branch);
		$bank_holder_name = array_filter($request->bank_holder_name);
		$ac_no = array_filter($request->ac_no);
		$ifsc_code = array_filter($request->ifsc_code);
		$ac_upid = array_filter($request->ac_upid);

		if (!empty($bank_name) && !empty($bank_branch) && !empty($bank_holder_name) && !empty($ac_no) && !empty($ifsc_code)) {
			$delBank = DB::table('prop_banks')->where('uid', $userId)->delete();

			foreach ($bank_name as $index => $value) {

				$insertBank = DB::table('prop_banks')->insertGetId([
					'uid' => $userId,
					'addBy' => $addBy,
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
					'redirect' => url('/'),
					'message' => 'Bank details updated'
				);
				return response()->json($msg);
			}
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Enter all details for bank'
			);
			return response()->json($msg);
		}
	}

	//Start update attached details
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
	
	public function update_comp_attachment_proprietorship(Request $request)  
	{  
	
			//print_r($_FILES);		
			$userId = currentOwnerId();
			$id = $request->id;
			$proprietorship_id = $id;
			$encryptedId = Crypt::encrypt($id);
			$dataCheck = DB::table('proprietorship_profiles')
						->select(DB::raw('proprietorship_profiles.id,proprietorship_profiles.gst_doc'))
						->where('userId','=',$userId)
						->get()->toArray();
						
			if(empty($dataCheck)){
				 $msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('proprietorship-edit/'.$encryptedId),
					'message' => 'Please update company details'
				);
				return response()->json($msg);
			}else{
				
				if ($request->hasFile('inc_certificate')) {
					$file = $request->file('inc_certificate');

					DocLockerController::saveToLocker(
							$userId,
							$file,
							"Company & Ownership Documents",
							"Certificate of Incorporation",
							"Certificate of Incorporation of Company",
							$proprietorship_id
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
							"Pan Card of Company",
							$proprietorship_id

						);
					}
					

					if ($request->hasFile('gst_doc')) {
						$file = $request->file('gst_doc');

						DocLockerController::saveToLocker(
							$userId,
							$file,
							"Licensing & Registration",
							"GST Registration Certificate",
							"GST Registration Certificate of Company",
							$proprietorship_id
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
							"Trade License Document",      // Document Name
							$proprietorship_id

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
							"PF Establishment Code Letter",
							$proprietorship_id      // document_name
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
							"Professional Tax Registration Document",
							$proprietorship_id         // document_name
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
							"First Director Aadhaar Card",
							$proprietorship_id     // document_name
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
							"First Director PAN Card",
							$proprietorship_id        // document_name
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
							"First Director Photograph",
							$proprietorship_id       // document_name
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
							"Second Director Aadhaar Card",
							$proprietorship_id    // document_name
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
							"Second Director PAN Card",
							$proprietorship_id       // document_name
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
							"Second Director Photograph",
							$proprietorship_id      // document_name
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
							"Company Logo",
							$proprietorship_id                     // document_name
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
							"Authorized Signature",
							$proprietorship_id          // document_name
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
							"Company Stamp",
							$proprietorship_id                // document_name
						);
					}
				
				
				//update chk_agree
				$update = DB::table('proprietorship_profiles')
						->where('id', $id)
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
				$dataCheck = DB::table('proprietorship_profiles')
							->select(DB::raw('proprietorship_profiles.id'))
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
						$insertLogo = Proprietorship_profiles::create([
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
							$update = DB::table('proprietorship_profiles')
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
		$dataCheck = DB::table('proprietorship_profiles')
					->select(DB::raw('proprietorship_profiles.id'))
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
			
			$update = DB::table('proprietorship_profiles')
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


	public function uploadProfileImage_Proprietorship(Request $request)
	{
		$request->validate([
			'fileUpload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
		]);

		$userId = currentOwnerId();
		$id = $request->id;
		$file = $request->file('fileUpload');
		$fileName = 'comp_logo_' . $userId . '.' . $file->getClientOriginalExtension(); 
		$filePath = public_path('storage/profile/'); 

		// Check if company profile exists for the user
		$companyProfile = DB::table('proprietorship_profiles')->where('id', $id)->first();

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
			DB::table('proprietorship_profiles')
				->where('id', $id)
				->update(['comp_logo' => $fileName, 'updated_at' => now()]);
		} 

		return response()->json([
			'success' => true,
			'message' => 'Company logo uploaded successfully!',
			'fileName' => $fileName
		]);
	}
	
	
}
