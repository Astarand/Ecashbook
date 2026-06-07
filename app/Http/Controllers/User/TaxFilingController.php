<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Msme_applications;
use App\Models\Msme_applications_messages;
use App\Models\McaRocApplications;
use App\Models\McaRocApplicationsMessages;
use App\Models\StartupIncubatorApplications;
use App\Models\ItrFilings;
use App\Models\ItrFilingsMessages;

use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class TaxFilingController extends Controller
{
    public function TDSReturns()
    {
        return view('User.tds-returns-filing');
    }
    public function AddTDSReturns()
    {
        return view('User.add-tds-returns-filing');
    }
    public function PFManagementList()
    {
        return view('User.pf-management-list');
    }
    public function AddPFfiling()
    {
        return view('User.add-pf-filing');
    }
    public function ESIManagementList()
    {
        return view('User.esi-management-list');
    }
    public function AddESIfiling()
    {
        return view('User.add-esi-filing');
    }
    public function ProfessionalTax()
    {
        return view('User.professional-tax');
    }
    public function MSMECompliences()
    {
        return view('User.msme-compliences');
    }
	
	// LIST
    public function msmeApplcationListing()
    {
		$userId = currentOwnerId();
        $applications = Msme_applications::when(
			auth()->user()->u_type != 3,
			fn ($q) => $q->where('uid', $userId)
		)
		->orderBy('created_at', 'desc')   // newest first
		->get();
        return view('User.msme-compliences-list', compact('applications'));
    }
	
	public function applyMsmeApplication(Request $request)
    {
        $request->validate([
            'applicant_name' => 'required',
            'company_name'   => 'required',
            'mobile'         => 'required|digits_between:10,15',
            'email'          => 'required|email',
            'preferred_service' => 'required'
        ]);

		$uid   = Auth::check() ? currentOwnerId() : null;
		$utype = Auth::check() ? currentOwnerUserType() : null;

		$application = Msme_applications::create([
							'uid' => $uid,
							'utype' => $utype,
							'applicant_name' => $request->applicant_name,
							'company_name'   => $request->company_name,
							'mobile'         => $request->mobile,
							'email'          => $request->email,
							'udyam_no'       => $request->udyam_no,
							'preferred_service' => $request->preferred_service,
							'details'        => $request->details,
						]);
		
		//First message
		$lastInsertedId = $application->id;
		Msme_applications_messages::create([
			'ticket_id'    => $lastInsertedId,
			'sender_id'    => $uid,
			'sender_utype' => $utype,
			'message'      => null,
			'attachment'   => null,
		]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully'
        ]);
    }
	
	/* =========================
       LOAD CHAT
    ========================== */
    public function messages($id)
    {
        $messages = Msme_applications_messages::where('ticket_id', $id)
                    ->with('sender')
                    ->orderBy('id')
                    ->get();

        return response()->json($messages);
    }

    /* =========================
       SEND MESSAGE
    ========================== */
    public function sendMessage(Request $request)
    {
		$userType = Auth::user()->u_type;
		$userId = null;
		if (!in_array($userType, [3, 6])) {
			$userId = (in_array($userType, [2])) ? Auth::user()->id : Auth::user()->user_add_by;
		}else{
			$userId = (in_array($userType, [3])) ? Auth::user()->id : Auth::user()->admin_add_by;
		}
        $request->validate([
            'ticket_id' => 'required',
            'message'    => 'nullable|string',
			'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);
		
		// If no attachment, message is required
		if (!$request->hasFile('attachment') && trim($request->message) === '') {
			return response()->json([
				'status' => false,
				'errors' => [
					'message' => ['Message is required when no file is attached']
				]
			], 422);
		}

		$path = null;
		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
			$destination = public_path('uploads/msme_compliance');
			if (!file_exists($destination)) {
				mkdir($destination, 0777, true);
			}
			$file->move($destination, $filename);
			$path = 'uploads/msme_compliance/' . $filename;
		}
        Msme_applications_messages::create([
            'ticket_id'   => $request->ticket_id,
            'sender_id'   => $userId,
            'sender_utype'=> $userType,
            'message'     => $request->message,
			'attachment'=> $path
        ]);

        return response()->json(['status' => true]);
    }

    // DELETE
    public function deleteMsmeApplcation(Request $request)
    {
        Msme_applications_messages::where('ticket_id', $request->id)->delete();
        Msme_applications::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ]);
    }
	
    public function MCAROCFiling()
    {
        //return view('User.mca-roc-filing');
		$userId = currentOwnerId();

		$company = DB::table('company_profiles')
			->where('userId', $userId)
			->first();

		return view('User.mca-roc-filing', compact('company'));
    }
	
	// LIST
    public function MCAROCFilingListing()
    {
		$userId = currentOwnerId();
        $applications = McaRocApplications::when(
            auth()->user()->u_type != 3,
            fn ($q) => $q->where('uid', $userId)
        )
        ->orderBy('created_at', 'desc')
		->get();

        return view('User.mca-roc-filing-list', compact('applications'));
    }
	
	// ADD
	public function addMCAROCFiling(Request $request)
	{
		$userId = currentOwnerId();
		$uType  = currentOwnerUserType();

		$request->validate([
			'company_name'       => 'required|string|max:255',
			'pan'                => 'required|string|max:20',
			'reg_office_address' => 'required|string',
			'mca_email'          => 'required|email',
			'mobile'             => 'required',
			'inc_date'           => 'required|date',
			'client_name'        => 'required',
			'designation'        => 'required',
			'signature'          => 'required',
			'signed_date'        => 'required|date',
		]);

		//Upload path
		$path = public_path('uploads/mca_roc/');

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		$data = $request->except(['_token']);

		//File fields (MATCH DB COLUMN EXACTLY)
		$fileFields = [
			'file_doc_moa_aoa',
			'file_doc_coi',
			'file_doc_prev_roc',
			'file_doc_dsc_auth',
			'file_doc_auditor_appointment',
		];

		foreach ($fileFields as $fileField) {

			if ($request->hasFile($fileField)) {

				$file = $request->file($fileField);

				$fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

				$file->move($path, $fileName);

				// EXACT column match
				$data[$fileField] = 'uploads/mca_roc/'.$fileName;
			}
		}

		//Checkbox normalize
		$checkboxFields = [
			'event_change_director',
			'event_change_reg_office',
			'event_share_allotment',
			'event_transfer_shares',
			'event_appointment_auditor',
			'event_resignation_auditor',
			'doc_moa_aoa',
			'doc_coi',
			'doc_prev_roc',
			'doc_dsc_auth',
			'doc_auditor_appointment',
		];

		foreach ($checkboxFields as $field) {
			$data[$field] = $request->boolean($field);
		}

		//Add extra fields
		$data['uid'] = $userId;
		$data['utype'] = $uType;

		$application = McaRocApplications::create($data);

		//First message
		McaRocApplicationsMessages::create([
			'ticket_id'    => $application->id,
			'sender_id'    => $userId,
			'sender_utype' => $uType,
			'message'      => null,
			'attachment'   => null,
		]);

		return response()->json([
			'success' => true,
			'message' => 'MCA / ROC filing request submitted successfully'
		]);
	}
	
	public function updateStatus(Request $request)
	{
		DB::table('mca_roc_applications')
			->where('id', $request->id)
			->update([
				'status' => $request->status,
				'updated_at' => now()
			]);

		return response()->json([
			'success' => true,
			'message' => 'Status updated successfully'
		]);
	}
	
	/* =========================
       MCAROC LOAD CHAT
    ========================== */
    public function messagesMCAROC($id)
    {
        $messages = McaRocApplicationsMessages::where('ticket_id', $id)
                    ->with('sender')
                    ->orderBy('id')
                    ->get();

        return response()->json($messages);
    }

    /* =========================
       MCAROC SEND MESSAGE 
    ========================== */
    public function sendMessageMCAROC(Request $request)
    {
		$userType = Auth::user()->u_type;
		$userId = null;
		if (!in_array($userType, [3, 6])) {
			$userId = (in_array($userType, [2])) ? Auth::user()->id : Auth::user()->user_add_by;
		}else{
			$userId = (in_array($userType, [3])) ? Auth::user()->id : Auth::user()->admin_add_by;
		}
        $request->validate([
            'ticket_id' => 'required',
            'message'    => 'nullable|string',
			'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);
		
		// If no attachment, message is required
		if (!$request->hasFile('attachment') && trim($request->message) === '') {
			return response()->json([
				'status' => false,
				'errors' => [
					'message' => ['Message is required when no file is attached']
				]
			], 422);
		}

		$path = null;
		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
			$destination = public_path('uploads/mca_roc');
			if (!file_exists($destination)) {
				mkdir($destination, 0777, true);
			}
			$file->move($destination, $filename);
			$path = 'uploads/mca_roc/' . $filename;
		}
        McaRocApplicationsMessages::create([
            'ticket_id'   => $request->ticket_id,
            'sender_id'   => $userId,
            'sender_utype'=> $userType,
            'message'     => $request->message,
			'attachment'=> $path
        ]);

        return response()->json(['status' => true]);
    }
	
	public function viewMCAROCFiling($id)
	{
		$userId = currentOwnerId();
		$query = McaRocApplications::where('id', $id);

		// u_type 3 → admin (no restriction)
		if (auth()->user()->u_type != 3) {
			$query->where('uid', $userId);
		}

		$application = $query->firstOrFail();

		return view('User.mca-roc-view', compact('application'));
	}


    // DELETE
    public function deleteMCAROCFiling(Request $request)
    {
		McaRocApplicationsMessages::where('ticket_id', $request->id)->delete();
        McaRocApplications::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ]);
    }
 
    public function IncomeTaxReturnFiling()
    {
		$userId = currentOwnerId();
		$companyProfile = DB::table('company_profiles')
			->where('userId', $userId)
			->first();

		return view('User.income-tax-return-filing', compact('companyProfile'));
    }
	
	public function incomeTaxReturnApply_old(Request $request)
    {
		$uid = currentOwnerId();
		$utype = currentOwnerUserType();
		
		$rules = [
			// BASIC DETAILS
			'legal_name'   => 'required|string|max:255',
			'pan'          => 'required|string|size:10',
			'mobile'       => 'required|digits:10',
			'email'        => 'email',
			'aadhaar'      => 'required|digits:12',
			'dob_inc'      => 'required|date',

			// FILE VALIDATION
			'ver_dsc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
		];

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors()
			], 422);
		}

		// Prepare data
		$data = $request->except('_token');
		$data['uid'] = $uid;
		$data['utype'] = $utype;

		/**
		 * CHECKBOX HANDLING
		 * Unchecked checkboxes are not sent → set default 0
		 */
		$checkboxFields = [
			// FILING TYPE
			'filing_individual',
			'filing_proprietorship',
			'filing_partnership',
			'filing_llp',
			'filing_company',
			// INDIVIDUAL / PROPRIETORSHIP – INCOME DETAILS
			'ind_salary_16',
			'ind_bank_stmt',
			'ind_books',
			'ind_gst_returns',
			'ind_pl',
			'ind_bs',
			'ind_rental',
			'ind_other_income',
			// FIRM / LLP / COMPANY – INCOME DETAILS
			'firm_final_accounts',
			'firm_bank_stmt',
			'firm_gst_summary',
			'firm_tds',
			'firm_depreciation',
			'firm_loan_conf',
			'firm_related_party',
			// TAX DETAILS
			'tax_26as',
			'tax_ais_tis',
			'tax_tds_cert',
			'tax_adv_challan',
			'tax_self_assess',
			// REQUIRED DOCUMENTS
			'req_pan',
			'req_aadhaar',
			'req_bank_passbook',
			'req_digital_signature',
			'req_prev_itr',
		];

		foreach ($checkboxFields as $field) {
			$data[$field] = $request->has($field) ? 1 : 0;
		}

		// FILE UPLOAD
		if ($request->hasFile('ver_dsc')) {
			$file = $request->file('ver_dsc');
			$name = time().'_'.$file->getClientOriginalName();
			$file->move(public_path('uploads/itr_filing'), $name);
			$data['ver_dsc'] = $name;
		}

		$application = ItrFilings::create($data);
		
		//First message
		ItrFilingsMessages::create([
			'ticket_id'    => $application->id,
			'sender_id'    => $uid,
			'sender_utype' => $utype,
			'message'      => null,
			'attachment'   => null,
		]);

		return response()->json([
			'success' => true,
			'message' => 'ITR Filing submitted successfully'
		]);
    }
	
	
	public function incomeTaxReturnApply(Request $request)
	{
		$uid   = currentOwnerId();
		$utype = currentOwnerUserType();

		// ✅ Validation
		$rules = [
			'legal_name' => 'required|string|max:255',
			'pan'        => 'required|string|size:10',
			'mobile'     => 'required|digits:10',
			'email'      => 'nullable|email',
			'aadhaar'    => 'required|digits:12',
			'dob_inc'    => 'required|date',

			// FILE VALIDATION
			'ver_dsc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

			// NEW FILES
			'file_req_pan'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
			'file_req_aadhaar'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
			'file_req_bank_passbook'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
			'file_req_digital_signature' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
			'file_req_prev_itr'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
		];

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors()
			], 422);
		}

		// ✅ Prepare data
		$data = $request->except('_token');
		$data['uid']   = $uid;
		$data['utype'] = $utype;

		// ✅ Checkbox normalize
		$checkboxFields = [
			'filing_individual','filing_proprietorship','filing_partnership','filing_llp','filing_company',
			'ind_salary_16','ind_bank_stmt','ind_books','ind_gst_returns','ind_pl','ind_bs','ind_rental','ind_other_income',
			'firm_final_accounts','firm_bank_stmt','firm_gst_summary','firm_tds','firm_depreciation','firm_loan_conf','firm_related_party',
			'tax_26as','tax_ais_tis','tax_tds_cert','tax_adv_challan','tax_self_assess',
			'req_pan','req_aadhaar','req_bank_passbook','req_digital_signature','req_prev_itr',
		];

		foreach ($checkboxFields as $field) {
			$data[$field] = $request->has($field) ? 1 : 0;
		}

		// ✅ Create folder if not exists
		$path = public_path('uploads/itr_filing/');
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}

		// ✅ SINGLE FILE (existing)
		if ($request->hasFile('ver_dsc')) {
			$file = $request->file('ver_dsc');
			$name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
			$file->move($path, $name);
			$data['ver_dsc'] = 'uploads/itr_filing/'.$name;
		}

		// ✅ MULTIPLE FILES (NEW 🔥)
		$fileFields = [
			'file_req_pan',
			'file_req_aadhaar',
			'file_req_bank_passbook',
			'file_req_digital_signature',
			'file_req_prev_itr',
		];

		foreach ($fileFields as $field) {

			if ($request->hasFile($field)) {

				$file = $request->file($field);
				$fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

				$file->move($path, $fileName);

				// ✅ Save exact column name
				$data[$field] = 'uploads/itr_filing/'.$fileName;
			}
		}

		// ✅ Insert
		$application = ItrFilings::create($data);

		// ✅ First message
		ItrFilingsMessages::create([
			'ticket_id'    => $application->id,
			'sender_id'    => $uid,
			'sender_utype' => $utype,
			'message'      => null,
			'attachment'   => null,
		]);

		return response()->json([
			'success' => true,
			'message' => 'ITR Filing submitted successfully'
		]);
	}
	
	public function updateItrFilingStatus(Request $request)
	{
		DB::table('itr_filings')
			->where('id', $request->id)
			->update([
				'status' => $request->status,
				'updated_at' => now()
			]);

		return response()->json([
			'success' => true,
			'message' => 'Status updated successfully'
		]);
	}
	
	/* =========================
       ITR Filings LOAD CHAT
    ========================== */
    public function messagesItrFiling($id)
    {
        $messages = ItrFilingsMessages::where('ticket_id', $id)
                    ->with('sender')
                    ->orderBy('id')
                    ->get();

        return response()->json($messages);
    }

    /* =========================
       ITR Filings SEND MESSAGE 
    ========================== */
    public function sendMessageItrFiling(Request $request)
    {
		$userType = Auth::user()->u_type;
		$userId = null;
		if (!in_array($userType, [3, 6])) {
			$userId = (in_array($userType, [2])) ? Auth::user()->id : Auth::user()->user_add_by;
		}else{
			$userId = (in_array($userType, [3])) ? Auth::user()->id : Auth::user()->admin_add_by;
		}
        $request->validate([
            'ticket_id' => 'required',
            'message'    => 'nullable|string',
			'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx'
        ]);
		
		// If no attachment, message is required
		if (!$request->hasFile('attachment') && trim($request->message) === '') {
			return response()->json([
				'status' => false,
				'errors' => [
					'message' => ['Message is required when no file is attached']
				]
			], 422);
		}

		$path = null;
		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
			$destination = public_path('uploads/itr_filing');
			if (!file_exists($destination)) {
				mkdir($destination, 0777, true);
			}
			$file->move($destination, $filename);
			$path = 'uploads/itr_filing/' . $filename;
		}
        ItrFilingsMessages::create([
            'ticket_id'   => $request->ticket_id,
            'sender_id'   => $userId,
            'sender_utype'=> $userType,
            'message'     => $request->message,
			'attachment'=> $path
        ]);

        return response()->json(['status' => true]);
    }

    public function incomeTaxReturnListing()
    {
		$applications = ItrFilings::latest()->get();
        return view('User.income-tax-return-filing-list', compact('applications'));
    }

    public function incomeTaxReturnShow($id)
    {
        $application = ItrFilings::findOrFail($id);
        return view('User.income-tax-return-filing-view', compact('application'));
    }

    public function incomeTaxReturnDelete($id)
    {
		ItrFilingsMessages::where('ticket_id', $id)->delete();
        $row = ItrFilings::findOrFail($id);

        if ($row->ver_dsc && file_exists(public_path('uploads/itr_filing/'.$row->ver_dsc))) {
            unlink(public_path('uploads/itr_filing/'.$row->ver_dsc));
        }

        $row->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ]);
    }
	
	
   
    public function StartupFiling()
    {
        return view('User.startup-filing');
    }
	
	public function startupFilingListing()
	{
		$userId = currentOwnerId();
		checkCoreAccess('Startup Incubator Services');
		$applications = StartupIncubatorApplications::when(
			auth()->user()->u_type != 3,
			fn($q) => $q->where('uid', $userId)
		)
		->latest()
		->get();

		return view('User.startup-filing-list', compact('applications'));
	}

	
	public function startupFilingApply(Request $request)
	{
		$userId = currentOwnerId();
		$uType = currentOwnerUserType();
		$data = $request->validate([
			'business_name' => 'required|string|max:255',
			'founder_name'  => 'required|string|max:255',
			'mobile'        => 'required|digits_between:10,15',
			'email'         => 'required|email'
		]);

		// ALL CHECKBOX FIELDS
		$checkboxFields = [
			'idea_stage','prototype','early_revenue','growth_stage',

			'company_registration','gst_registration','msme','pan_tan',
			'trade_license','trademark','dsc','epf_esic',
			'startup_registration','professional_tax',

			'accounting_setup','chart_accounts','tax_guidance','roc_setup','payroll',

			'business_model','swot','pricing','financial_planning',

			'pitch_deck','financial_projection','valuation','investor_connect','govt_scheme',

			'mentoring','workshop','legal_mentoring','marketing_mentoring',

			'website','crm','erp','digital_marketing','automation',

			'brand_identity','social_media','product_plan','marketing_template','dealer',

			'monthly_report','kpi','cashflow','scaling_support',
		];

		foreach ($checkboxFields as $field) {
			$data[$field] = $request->has($field) ? 1 : 0;
		}

		StartupIncubatorApplications::create(array_merge(
			$data,
			[
				'uid'   => $userId,
				'utype' => $uType,
			],
			$request->except(array_merge($checkboxFields, [
				'_token','signed_date'
			]))
		));

		return response()->json([
			'success' => true,
			'message' => 'Startup Incubator Application submitted successfully'
		]);
	}
	
	public function startupFilingView($id)
	{
		$application = StartupIncubatorApplications::findOrFail($id);
		return view('User.startup-filing-view', compact('application'));
	}
	
	public function startupFilingDelete(Request $request)
	{
		StartupIncubatorApplications::where('id',$request->id)->delete();

		return response()->json([
			'success' => true,
			'message' => 'Record deleted successfully'
		]);
	}
	
	public function getAppStatus($table, $id)
    {
        $data = DB::table($table)->where('id', $id)->first();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function updateAppStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'table' => 'required',
            'app_status' => 'required'
        ]);

        // Security: whitelist tables
        $allowedTables = ['msme_applications', 'mca_roc_applications', 'itr_filings', 'startup_incubator_applications'];

        if (!in_array($request->table, $allowedTables)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid table'
            ]);
        }

        DB::table($request->table)
            ->where('id', $request->id)
            ->update([
                'app_status' => $request->app_status,
                'process_date' => $request->process_date,
                'payment_status' => $request->payment_status,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true
        ]);
    }


}

