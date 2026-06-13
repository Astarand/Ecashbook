<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Redirect;
// use DB;
// use Auth;
// use Validator;
use App\User;
use App\Models\Assets;
use App\Models\AssetsCurr;
use App\Models\Assets_cs;
use App\Models\Assets_ncs;
use App\Models\Asset_currents;
use App\Models\Asset_non_currents;
use App\Models\Asset_vouchers;
use App\Models\Asset_series;
use App\Models\Vendor;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\AuditLogger;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;


class AssetController extends Controller
{
    public function __construct(JournalService $journalService, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
		$this->paymentVoucherService = $paymentVoucherService;
    }
	
    public function AssetList(Request $request)
    {
        //die('hhh');
        //$this->middleware('auth');
		$title = 'Assets';
		$userId = currentOwnerId();
		checkCoreAccess('Assets & Liabilities');

		$req_tag = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = getAccessCompanyId($request);
			$req_tag = 1;
		}

		if(Auth::user()->u_type ==1){ //ca
			$assets = DB::table('assets as a')
						->leftJoin('assets_currs as ac', 'ac.aid', '=', 'a.id')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'a.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'a.propId')
						->where('a.added_by', $userId)
						->orderBy('a.id', 'DESC')
						->select(
							'a.*',

							// ✅ FINAL AMOUNT (COMMON ALIAS)
							DB::raw("
								CASE
									-- CURRENT ASSETS → SUM ALL FIELDS
									WHEN a.assetType = 'current' THEN
										COALESCE(ac.cash_amount,0)
									  + COALESCE(ac.bank_balance,0)
									  + COALESCE(ac.amount,0)
									  + COALESCE(ac.pending_amount,0)
									  + COALESCE(ac.amount_vendor,0)
									  + COALESCE(ac.employee_advance_amount,0)
									  + COALESCE(ac.prepaid_amt,0)
									  + COALESCE(ac.itc_amt,0)
									  + COALESCE(ac.tds_gross_amount,0)
									  + COALESCE(ac.gross_profit,0)

									-- CWIP
									WHEN a.nonCurrentAssetType = 'Capital Work in Progress' THEN a.cwip_amount

									-- NORMAL ASSET
									ELSE a.invoice_value
								END as amount
							"),

							// TDS
							'a.tds_amt as tdsAmount',

							// Company Name
							DB::raw("
								CASE
									WHEN a.propId IS NOT NULL AND a.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca employee
			$assets = DB::table('assets as a')
						->leftJoin('assets_currs as ac', 'ac.aid', '=', 'a.id')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'a.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'a.propId')
						->where('a.added_by', $userId)
						->orderBy('a.id', 'DESC')
						->select(
							'a.*',

							// ✅ FINAL AMOUNT (COMMON ALIAS)
							DB::raw("
								CASE
									-- CURRENT ASSETS → SUM ALL FIELDS
									WHEN a.assetType = 'current' THEN
										COALESCE(ac.cash_amount,0)
									  + COALESCE(ac.bank_balance,0)
									  + COALESCE(ac.amount,0)
									  + COALESCE(ac.pending_amount,0)
									  + COALESCE(ac.amount_vendor,0)
									  + COALESCE(ac.employee_advance_amount,0)
									  + COALESCE(ac.prepaid_amt,0)
									  + COALESCE(ac.itc_amt,0)
									  + COALESCE(ac.tds_gross_amount,0)
									  + COALESCE(ac.gross_profit,0)

									-- CWIP
									WHEN a.nonCurrentAssetType = 'Capital Work in Progress' THEN a.cwip_amount

									-- NORMAL ASSET
									ELSE a.invoice_value
								END as amount
							"),

							// TDS
							'a.tds_amt as tdsAmount',

							// Company Name
							DB::raw("
								CASE
									WHEN a.propId IS NOT NULL AND a.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$assets = DB::table('assets as a')
						->leftJoin('assets_currs as ac', 'ac.aid', '=', 'a.id')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'a.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'a.propId')
						->where('a.added_by', $userId)
						->orderBy('a.id', 'DESC')
						->select(
							'a.*',

							// ✅ FINAL AMOUNT (COMMON ALIAS)
							DB::raw("
								CASE
									-- CURRENT ASSETS → SUM ALL FIELDS
									WHEN a.assetType = 'current' THEN
										COALESCE(ac.cash_amount,0)
									  + COALESCE(ac.bank_balance,0)
									  + COALESCE(ac.amount,0)
									  + COALESCE(ac.pending_amount,0)
									  + COALESCE(ac.amount_vendor,0)
									  + COALESCE(ac.employee_advance_amount,0)
									  + COALESCE(ac.prepaid_amt,0)
									  + COALESCE(ac.itc_amt,0)
									  + COALESCE(ac.tds_gross_amount,0)
									  + COALESCE(ac.gross_profit,0)

									-- CWIP
									WHEN a.nonCurrentAssetType = 'Capital Work in Progress' THEN a.cwip_amount

									-- NORMAL ASSET
									ELSE a.invoice_value
								END as amount
							"),

							// TDS
							'a.tds_amt as tdsAmount',

							// Company Name
							DB::raw("
								CASE
									WHEN a.propId IS NOT NULL AND a.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->paginate(10);
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$assets =  DB::table('assets')
							->select(DB::raw('assets.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'assets.added_by', '=', 'company_profiles.userId')
							->leftJoin('asset_currents', 'assets.id', '=', 'asset_currents.aid')
							->leftJoin('asset_non_currents', 'assets.id', '=', 'asset_non_currents.asid')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$assets_pagination = $assets;
		// echo "<pre>"; print_r($assets);exit;
		return view('User.assets-list')->with([
			'title' =>$title,
			'assets'=>$assets,
			'assets_pagination' =>$assets_pagination,
			'req_tag' => $req_tag
		]);
    }

    public function AddAsset()
    {
        //$this->middleware('auth');
		$userId = currentOwnerId();
		checkCoreAccess('Assets & Liabilities');
		//$purposes_of_tds = DB::table('purposes_of_tds')->get();
		$purposes_of_tds = DB::table('tds_rules')
							->where('module', '=', 'Assets')
							->get();
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$vendors = DB::table('vendors')
			->select('id', 'vendor_name', 'vendor_gstin')
			->where('userId', $userId)
			->where('status', 1)
			->get();
        return view('User.add-asset')->with([
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'vendors' => $vendors
        ]);
    }

	
	protected function validator(array $data)
	{
		$rules = [
			'date' => 'required|date',
			'assetType' => 'required|in:current,non-current',
		];

		$assetType = $data['assetType'] ?? '';
		$nonCurrentType = $data['nonCurrentAssetType'] ?? '';

		// ================= CURRENT ASSETS =================
		if ($assetType === 'current') {
			$rules['currentAssetType'] = 'required';
		}
		// ================= NON-CURRENT ASSETS =================
		if ($assetType === 'non-current') {

			$rules['nonCurrentAssetType'] = 'required';
			// ---------- CWIP (WORK IN PROGRESS) ----------
			if ($nonCurrentType === 'Capital Work in Progress') {

				$rules['project_name'] = 'required|string|max:255';
				$rules['project_code'] = 'required|string|max:100';
				$rules['cwip_asset_type'] = 'required';
				$rules['expense_type'] = 'required';
				$rules['cwip_amount'] = 'required|numeric';
			}else{
				$rules['asset_name']       = 'required|string|max:255';
				$rules['asset_category'] = 'required';
				$rules['asset_code'] = 'required';
				$rules['invoice_value'] = 'required';
				$rules['pay_status'] = 'required';
				$rules['adjusted_amt'] = 'required';
			}
		}

		return Validator::make($data, $rules);
	}

	
	protected function create(array $data)
	{
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$propId = $data['propId'] ?? null;
		$prefix = 'AST' . $userId . '-';

		// Generate Asset ID
		$lastAsset = DB::table('assets')
			->where('asset_id', 'like', $prefix . '%')
			->orderBy('asset_id', 'desc')
			->first();

		if ($lastAsset) {
			$lastId = intval(substr($lastAsset->asset_id, strlen($prefix)));
			$newId = $prefix . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
		} else {
			$newId = $prefix . '00001';
		}

		// ================= TDS CALCULATION =================
		$tdsData = $this->calculateTdsFromRules($data);
		$gstApplicable = ($data['gst_applicable'] ?? '') === 'yes' ? 'yes' : 'no';
		
		// ================= MAIN INSERT =================
		$isWip = (($data['assetType'] ?? '') === 'non-current' && ($data['nonCurrentAssetType'] ?? '') === 'Capital Work in Progress');
		$asset = Assets::create([
			'asset_id' => $newId,
			'added_by' => $userId,
			'propId' => $propId,
			
			'file1' => isset($data['file1']) ? $data['file1'] : null,
			'file2' => isset($data['file2']) ? $data['file2'] : null,
			'purchaseByAudit' => isset($data['purchaseByAudit']) ? $data['purchaseByAudit']:null,
			'purchaseDateAudit' => isset($data['purchaseDateAudit']) ? $data['purchaseDateAudit']:null,
			'approveByAudit' => isset($data['approveByAudit']) ? $data['approveByAudit']:null,
			'approveDateAudit' => isset($data['approveDateAudit']) ? $data['approveDateAudit']:null,
			
			'date' => $data['date'],			
			'assetType' => $data['assetType'] ?? null,
			'currentAssetType' => ($data['assetType'] === 'current')? ($data['currentAssetType'] ?? null): null,
			'nonCurrentAssetType' => ($data['assetType'] === 'non-current')? ($data['nonCurrentAssetType'] ?? null): null,

			// ================= FIXED ASSET =================
			'asset_name' => !$isWip ? ($data['asset_name'] ?? null) : null,
			'asset_category' => !$isWip ? ($data['asset_category'] ?? null) : null,
			'asset_code' => !$isWip ? ($data['asset_code'] ?? null) : null,
			'location' => !$isWip ? ($data['location'] ?? null) : null,
			'department' => !$isWip ? ($data['department'] ?? null) : null,

			'vendor_id' => !$isWip ? ($data['vendor_id'] ?? null) : null,
			'invoice_no' => !$isWip ? ($data['invoice_no'] ?? null) : null,
			'invoice_date' => !$isWip ? ($data['invoice_date'] ?? null) : null,
			'invoice_value' => !$isWip ? ($data['invoice_value'] ?? null) : null,
			'pay_status' => !$isWip ? ($data['pay_status'] ?? null) : null,
			'advance_amt' => !$isWip ? ($data['advance_amt'] ?? 0) : 0,
			'payable_amt' => !$isWip ? ($data['payable_amt'] ?? 0) : 0,
			'adjusted_amt' => !$isWip ? ($data['adjusted_amt'] ?? 0) : 0,
			'cwip_pay_status' => $isWip ? ($data['cwip_pay_status'] ?? null) : null,
			'cwip_advance_amt' => $isWip ? ($data['cwip_advance_amt'] ?? 0) : 0,
			'cwip_payable_amt' => $isWip ? ($data['cwip_payable_amt'] ?? 0) : 0,
			'cwip_adjusted_amt' => $isWip ? ($data['cwip_adjusted_amt'] ?? 0) : 0,

			'capitalization_date' => !$isWip ? ($data['capitalization_date'] ?? null) : null,
			'put_to_use_date' => !$isWip ? ($data['put_to_use_date'] ?? null) : null,
			'asset_status' => !$isWip ? ($data['asset_status'] ?? null) : null,

			'depreciation_start_date' => !$isWip ? ($data['depreciation_start_date'] ?? null) : null,
			'depreciation_frequency' => !$isWip ? ($data['depreciation_frequency'] ?? null) : null,
			'useful_life_years' => !$isWip ? ($data['useful_life_years'] ?? 0) : 0,
			'depreciation_method' => !$isWip ? ($data['depreciation_method'] ?? null) : null,
			'residual_value' => !$isWip ? ($data['residual_value'] ?? 0) : 0,
			'depreciation_value' => !$isWip ? ($data['depreciation_value'] ?? 0) : 0,
			'depreciation_rate' => !$isWip ? ($data['depreciation_rate'] ?? 0) : 0,

			// ================= CWIP (ONLY IF WIP) =================
			'project_name' => $isWip ? ($data['project_name'] ?? null) : null,
			'project_code' => $isWip ? ($data['project_code'] ?? null) : null,
			'cwip_asset_type' => $isWip ? ($data['cwip_asset_type'] ?? null) : null,

			'expense_type' => $isWip ? ($data['expense_type'] ?? null) : null,
			'cwip_vendor_id' => $isWip ? ($data['cwip_vendor_id'] ?? null) : null,
			'cwip_invoice_no' => $isWip ? ($data['cwip_invoice_no'] ?? null) : null,
			'cwip_amount' => $isWip ? ($data['cwip_amount'] ?? 0) : 0,

			'completion_percentage' => $isWip ? ($data['completion_percentage'] ?? 0) : 0,
			'capitalization_status' => $isWip ? ($data['capitalization_status'] ?? null) : null,
			'work_order_ref' => $isWip ? ($data['work_order_ref'] ?? null) : null,

			// ================= TAX =================
			'tds_applicable' => $tdsData['tds_applicable'],
			'tds_percent' => $tdsData['tds_percent'],
			'tds_id' => $tdsData['tds_id'],
			'tds_amt' => $tdsData['tds_amt'],
			
			'gst_applicable' => $gstApplicable,
			'gst_rate' => ($gstApplicable === 'yes') ? ($data['gst_rate'] ?? 0.00) : 0.00,
			'gst_amt' => ($gstApplicable === 'yes') ? ($data['gst_amt'] ?? 0.00) : 0.00,
			'gst_trans' => ($gstApplicable === 'yes') ? ($data['gst_trans'] ?? null) : null,
			'gst_allocation' => ($gstApplicable === 'yes') ? ($data['gst_allocation'] ?? null) : null,

		])->id;

		// ================= CURRENT ASSET INSERT =================
		if ($data['assetType'] === 'current') {
			$this->storeCurrentAsset($asset, $data);
		}
		// ================= JOURNAL =================
		if($data['assetType'] === 'non-current'){
			$this->journalEntry($asset);
		}
		

		// ======= Start payment voucher entry ========
		$currentPayment = 0;
		if ($data['assetType'] === 'non-current') {
			$isWip = (($data['nonCurrentAssetType'] ?? '') === 'Capital Work in Progress');
			if (!$isWip) {
				$paymentStatus = strtolower(trim($data['pay_status'] ?? ''));
				$advanceAmt = (float)($data['advance_amt'] ?? 0);
				$adjustedAmt = (float)($data['adjusted_amt'] ?? 0);
				if ($paymentStatus == 'advance') {
					$currentPayment = $advanceAmt;
				}
				else if ($paymentStatus == 'full') {
					$currentPayment = $adjustedAmt;
				}
			}
			else {
				$paymentStatus = strtolower(trim($data['cwip_pay_status'] ?? ''));
				$advanceAmt = (float)($data['cwip_advance_amt'] ?? 0);
				$adjustedAmt = (float)($data['cwip_adjusted_amt'] ?? 0);
				if ($paymentStatus == 'advance') {
					$currentPayment = $advanceAmt;
				}
				else if ($paymentStatus == 'full') {
					$currentPayment = $adjustedAmt;
				}
			}
		}

		if ($currentPayment > 0) {
			$this->paymentVoucherService->storePaymentVoucherEntries($asset,'Asset',$currentPayment);
		}
		// ======= End payment voucher entry ========

		return true;
	}
	
	protected function storeCurrentAsset($assetId, $data)
	{
		$userId = currentOwnerId();

		DB::table('assets_currs')->insert([

			'aid' => $assetId,
			'added_by' => $userId,

			// 🔹 CASH
			'cash_amount' => $data['cash_amount'] ?? 0,

			// 🔹 BANK
			'bank_id' => $data['bank_id'] ?? null,
			'bank_balance' => $data['bank_balance'] ?? 0,

			// 🔹 TRADE RECEIVABLE
			'amount' => $data['amount'] ?? 0,

			// 🔹 VENDOR ADVANCE
			'amount_vendor' => $data['amount_vendor'] ?? 0,

			// 🔹 EMPLOYEE ADVANCE
			'employee_advance_amount' => $data['employee_advance_amount'] ?? 0,

			// 🔹 PREPAID
			'prepaid_amt' => $data['prepaid_amt'] ?? 0,

			// 🔹 ITC
			'itc_amt' => $data['itc_amt'] ?? 0,

			// 🔹 TDS
			'tds_gross_amount' => $data['tds_gross_amount'] ?? 0,
			'gross_profit' => $data['gross_profit'] ?? 0

		]);
	}
	
	public function journalEntry($aid)
	{
		$uid = currentOwnerId();
		$asset = DB::table('assets')
			->where('id', $aid)
			->where('added_by', $uid)
			->first();

		$isWip = (($asset->assetType === 'non-current') &&($asset->nonCurrentAssetType === 'Capital Work in Progress'));
		$amount = $isWip ? ($asset->cwip_amount ?? 0): ($asset->invoice_value ?? 0);
		$party = $isWip ? ($asset->cwip_vendor_id ?? '') : ($asset->vendor_id ?? '');
		$vendorName = $party ? DB::table('vendors')->where('id', $party)->value('vendor_name') : '';
		$debitCredit = 'Debit'; // default (usually asset = debit)

		$this->journalService->storeAssetJournalEntries([
			'source'        => 'Asset',
			'autoId'        => $aid,
			'added_by'      => $uid,
			'propId'        => $asset->propId ?? null,
			'date'          => $asset->date,
			'entry_type'    => 'Asset',
			'asset_name'    => $asset->asset_name,
			'party_name'    => $vendorName,
			'amount'        => $amount,
			'debit_credit'  => $debitCredit,
			'gst_applicable'=> $asset->gst_applicable ?? 'no',
			'gst_rate'      => $asset->gst_rate ?? 0,
			'gst_trans'     => $asset->gst_trans ?? '',
			'status'        => $asset->isActive
		]);
	}
	
	public function calculateTdsAjax(Request $request)
	{
		$data = $request->all();

		$tds = $this->calculateTdsFromRules($data);

		return response()->json([
			'status' => 'success',
			'tds_amt' => $tds['tds_amt'],
			'tds_id' => $tds['tds_id'],
			'tds_percent' => $tds['tds_percent']
		]);
	}

	private function calculateTdsFromRules_old(array $data): array
	{
		//echo "<pre>";print_r($data);exit;
		$amount = 0;
		if (($data['assetType'] ?? '') === 'current') {
			$amount = 0;
		} else {
			$isWip = (($data['assetType'] === 'non-current') &&($data['nonCurrentAssetType'] === 'Capital Work in Progress'));
			$amount = $isWip ? ($data['cwip_amount'] ?? 0): ($data['invoice_value'] ?? 0);
		}
		$tdsPercentage = 0;
		$tdsId = null;
		$tdsAmount = 0;
		//Get all rules
		$rules = DB::table('tds_rules')
					->where('module', 'Assets')
					->orderBy('threshold_limit', 'asc')
					->get();
		//Apply rule logic
		foreach ($rules as $index => $rule) {
			$threshold = (float)($rule->threshold_limit ?? 0);
			// Apply when amount > threshold 
			if ($amount > $threshold) {
				$tdsPercentage = (float)$rule->tds_rate;
				$tdsId = $rule->id; //get tdsId from rule
				if ($amount > 0 && $tdsPercentage > 0) {
					$tdsAmount = ($amount * $tdsPercentage) / 100;
				}
				break; //stop loop once matched
			}
		}
		return [
			'tds_applicable' => $tdsAmount > 0 ? 'yes' : 'no',
			'tds_percent'    => $tdsPercentage,
			'tds_id'         => $tdsId,
			'tds_amt'        => round($tdsAmount, 2),
		];
	}

	
	private function calculateTdsFromRules(array $data): array
	{
		$amount = 0;

		if (($data['assetType'] ?? '') === 'current') {
			$amount = 0;
		} else {
			$isWip =(($data['assetType'] ?? '') === 'non-current') && (($data['nonCurrentAssetType'] ?? '') === 'Capital Work in Progress');
			$amount = $isWip ? (float)($data['cwip_amount'] ?? 0) : (float)($data['invoice_value'] ?? 0);
		}

		// =========================================
		// CATEGORY MAPPING
		// =========================================

		$assetCategory = trim($data['nonCurrentAssetType'] ?? '');

		$ruleCategory = null;

		switch ($assetCategory) {

			case 'Property Plant Equipment':
				$ruleCategory = 'Property Purchase';
				break;

			case 'Machinery':
				$ruleCategory = 'Machinery on Rent / Hire';
				break;

			case 'Vehicles':
				$ruleCategory = 'Property Purchase';
				break;

			case 'Capital Work in Progress':
				$ruleCategory = 'Contractor Payment (Company/Firm/LLP)';
				break;

			case 'Furniture Fixtures':
				$ruleCategory = 'Contractor Payment (Company/Firm/LLP)';
				break;

			case 'Computer IT Equipment':
				$ruleCategory = 'Professional Services';
				break;
		}

		// =========================================
		// DEFAULT RETURN
		// =========================================

		$response = [
			'tds_applicable' => 'no',
			'tds_percent'    => 0,
			'tds_id'         => null,
			'tds_amt'        => 0,
		];

		// No category match
		if (!$ruleCategory) {
			return $response;
		}

		// =========================================
		// FIND RULE
		// =========================================

		$rule = DB::table('tds_rules')
			->where('module', 'Assets')
			->where('status', 1)
			->where('category', $ruleCategory)
			->first();

		// Rule not found
		if (!$rule) {
			return $response;
		}

		// THRESHOLD CHECK
		$threshold = (float)($rule->threshold_limit ?? 0);

		// Amount below threshold
		if ($amount <= $threshold) {
			return $response;
		}

		// CALCULATE TDS
		$tdsPercentage = (float)($rule->tds_rate ?? 0);
		$tdsAmount = ($amount * $tdsPercentage) / 100;

		return [
			'tds_applicable' => 'yes',
			'tds_percent'    => $tdsPercentage,
			'tds_id'         => $rule->id,
			'tds_amt'        => round($tdsAmount, 2),
		];
	}
	
	public function save_add_asset(Request $request) 
	{

		$validation = $this->validator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		try {
			// Handle file uploads
			$destinationPath = 'public/documentation_files';
			$fileName1 = '';
			$fileName2 = '';
			$fileName3 = '';
			if ($request->hasFile('file1')) {
						$file = $request->file('file1');
						$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$path = $file->storeAs($destinationPath, $fileName1);
			}
			if ($request->hasFile('file2')) {
						$file = $request->file('file2');
						$fileName2 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$path = $file->storeAs($destinationPath, $fileName2);
			}
			if ($request->hasFile('otherNonCurrentAssetDocument')) {
						$file = $request->file('otherNonCurrentAssetDocument');
						$fileName3 = date("YmdHis") . '-' . $file->getClientOriginalName();
						$path = $file->storeAs($destinationPath, $fileName3);
			}

			// Prepare data for insertion
			$data = $request->all();
			$data['file1'] = $fileName1;
			$data['file2'] = $fileName2;
			$data['otherNonCurrentAssetDocument'] = $fileName3;
			

			// Insert asset record
			$insertAsset = $this->create($data);

			if ($insertAsset) {				
				$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/assets-list'),
						'message' => 'Asset added successfully!'
					);
				return response()->json($msg);
				
			} else {
				return response()->json([
					'status' => 'error',
					'message' => 'Failed to add asset. Please try again.'
				], 500);
			}
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Something went wrong. Please try again.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function getAssetData($assetId)
	{
		$asset = DB::table('assets as a')
			->leftJoin('assets_currs as ac', 'a.id', '=', 'ac.aid')
			->where('a.id', $assetId)
			->where('a.added_by', currentOwnerId()) // security
			->select(
				'a.*',

				// 🔹 Cash
				'ac.cash_amount',

				// 🔹 Bank
				'ac.bank_id',
				'ac.bank_balance',
				// 🔹 Trade Receivable
				'ac.amount',
				// 🔹 Advance to Vendor
				'ac.amount_vendor',
				// 🔹 Employee Advance
				'ac.employee_advance_amount',
				// 🔹 Prepaid
				'ac.prepaid_amt',
				// 🔹 ITC
				'ac.itc_amt',
				// 🔹 TDS
				'ac.tds_gross_amount',
				'ac.gross_profit'
			)
			->first();

		return $asset;
	}

	public function edit_asset($assetId)  {

		if(Auth::user()->u_type == 1){
			return redirect('/assets');
		}

		$assetId = base64_decode($assetId);
		$userId = currentOwnerId();
		$asset = $this->getAssetData($assetId);
					
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		
		// Check if asset exists
		if (!$asset) {
			return redirect('/assets-list')->with('error', 'Asset not found');
		}
		
		// ✅ Vendors
		$vendors = DB::table('vendors')
			->select('id', 'vendor_name', 'vendor_gstin')
			->where('userId', $userId)
			->where('status', 1)
			->get();
			
		$banks = DB::table('banks')
			->select('id', 'bank_name', 'curr_bal')
			->where('added_by', $userId)
			->where('status', 1)
			->get();

		// Fetch TDS rules for the form
		$purposes_of_tds = DB::table('tds_rules')
						->where('module', '=', 'Assets')
						->get();
		//echo "<pre>";print_r($asset);exit;
		// Return view with asset data (same fields/requirements as add-asset)
		return view('User.edit-asset')->with([
			'asset' => $asset,
			'assetId' => $assetId,
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'vendors'   => $vendors,
			'banks'   => $banks,
		]);
    }


	public function view_asset($assetId)  {

		$assetId = base64_decode($assetId);
		
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = session('compId'); //ca-accountant access
			// echo "ca session id: ". $userId; //debug
		} else {
			$userId = currentOwnerId();
			
		}
		
		
		$asset = $this->getAssetData($assetId);
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

		// ✅ Vendors
		$vendors = DB::table('vendors')
			->select('id', 'vendor_name', 'vendor_gstin')
			->where('userId', $userId)
			->where('status', 1)
			->get();
			
		$banks = DB::table('banks')
			->select('id', 'bank_name', 'curr_bal')
			->where('added_by', $userId)
			->where('status', 1)
			->get();

		//$purposes_of_tds = DB::table('purposes_of_tds')->get();
		$purposes_of_tds = DB::table('tds_rules')
							->where('module', '=', 'Assets')
							->get();
		// echo "<pre>";print_r($proprietorships);exit;
		return view('User.view-asset-details')->with([
			'asset' => $asset,
			'assetId' => $assetId,
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'vendors'   => $vendors,
			'banks'   => $banks,
		]);
    }


	
	
	public function update_asset(Request $request, $assetId)
	{
		$validation = $this->validator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		try {

			$data = $request->all();
			$uid  = currentOwnerId();
			// ================= OLD RECORD =================
			$oldRec = DB::table('assets')
				->where('id', $assetId)
				->where('added_by', $uid)
				->first();

			// ================= TDS CALC =================
			$tdsData = $this->calculateTdsFromRules($data);

			// ================= GST LOGIC =================
			$gstApplicable = ($data['gst_applicable'] ?? '') === 'yes' ? 'yes' : 'no';

			// ================= MAIN UPDATE =================
			$isWip = (($data['assetType'] ?? '') === 'non-current' && ($data['nonCurrentAssetType'] ?? '') === 'Capital Work in Progress');
			DB::table('assets')
				->where('id', $assetId)
				->where('added_by', $uid)
				->update([

					// BASIC
					'propId'        => $data['propId'],
					'date'          => $data['date'],
					'assetType'     => $data['assetType'],
					'currentAssetType' => ($data['assetType'] === 'current')? ($data['currentAssetType'] ?? null): null,
					'nonCurrentAssetType' => ($data['assetType'] === 'non-current')? ($data['nonCurrentAssetType'] ?? null): null,

					'asset_name' => !$isWip ? ($data['asset_name'] ?? null) : null,
					'asset_category' => !$isWip ? ($data['asset_category'] ?? null) : null,
					'asset_code' => !$isWip ? ($data['asset_code'] ?? null) : null,
					'location' => !$isWip ? ($data['location'] ?? null) : null,
					'department' => !$isWip ? ($data['department'] ?? null) : null,

					'vendor_id' => !$isWip ? ($data['vendor_id'] ?? null) : null,
					'invoice_no' => !$isWip ? ($data['invoice_no'] ?? null) : null,
					'invoice_date' => !$isWip ? ($data['invoice_date'] ?? null) : null,
					'invoice_value' => !$isWip ? ($data['invoice_value'] ?? null) : null,
					'pay_status' => !$isWip ? ($data['pay_status'] ?? null) : null,
					'advance_amt' => !$isWip ? ($data['advance_amt'] ?? 0) : 0,
					'payable_amt' => !$isWip ? ($data['payable_amt'] ?? 0) : 0,
					'adjusted_amt' => !$isWip ? ($data['adjusted_amt'] ?? 0) : 0,
					'cwip_pay_status' => $isWip ? ($data['cwip_pay_status'] ?? null) : null,
					'cwip_advance_amt' => $isWip ? ($data['cwip_advance_amt'] ?? 0) : 0,
					'cwip_payable_amt' => $isWip ? ($data['cwip_payable_amt'] ?? 0) : 0,
					'cwip_adjusted_amt' => $isWip ? ($data['cwip_adjusted_amt'] ?? 0) : 0,

					'capitalization_date' => !$isWip ? ($data['capitalization_date'] ?? null) : null,
					'put_to_use_date' => !$isWip ? ($data['put_to_use_date'] ?? null) : null,
					'asset_status' => !$isWip ? ($data['asset_status'] ?? null) : null,

					'depreciation_start_date' => !$isWip ? ($data['depreciation_start_date'] ?? null) : null,
					'depreciation_frequency' => !$isWip ? ($data['depreciation_frequency'] ?? null) : null,
					'useful_life_years' => !$isWip ? ($data['useful_life_years'] ?? 0) : 0,
					'depreciation_method' => !$isWip ? ($data['depreciation_method'] ?? null) : null,
					'residual_value' => !$isWip ? ($data['residual_value'] ?? 0) : 0,
					'depreciation_value' => !$isWip ? ($data['depreciation_value'] ?? 0) : 0,
					'depreciation_rate' => !$isWip ? ($data['depreciation_rate'] ?? 0) : 0,

					// ================= CWIP (ONLY IF WIP) =================
					'project_name' => $isWip ? ($data['project_name'] ?? null) : null,
					'project_code' => $isWip ? ($data['project_code'] ?? null) : null,
					'cwip_asset_type' => $isWip ? ($data['cwip_asset_type'] ?? null) : null,

					'expense_type' => $isWip ? ($data['expense_type'] ?? null) : null,
					'cwip_vendor_id' => $isWip ? ($data['cwip_vendor_id'] ?? null) : null,
					'cwip_invoice_no' => $isWip ? ($data['cwip_invoice_no'] ?? null) : null,
					'cwip_amount' => $isWip ? ($data['cwip_amount'] ?? 0) : 0,

					'completion_percentage' => $isWip ? ($data['completion_percentage'] ?? 0) : 0,
					'capitalization_status' => $isWip ? ($data['capitalization_status'] ?? null) : null,
					'work_order_ref' => $isWip ? ($data['work_order_ref'] ?? null) : null,

					// ================= TAX =================
					'tds_applicable' => $tdsData['tds_applicable'],
					'tds_percent'    => $tdsData['tds_percent'],
					'tds_id'         => $tdsData['tds_id'],
					'tds_amt'        => $tdsData['tds_amt'],
					
					'gst_applicable' => $gstApplicable,
					'gst_rate' => ($gstApplicable === 'yes') ? ($data['gst_rate'] ?? 0.00) : 0.00,
					'gst_amt' => ($gstApplicable === 'yes') ? ($data['gst_amt'] ?? 0.00) : 0.00,
					'gst_trans' => ($gstApplicable === 'yes') ? ($data['gst_trans'] ?? null) : null,
					'gst_allocation' => ($gstApplicable === 'yes') ? ($data['gst_allocation'] ?? null) : null,

					// AUDIT
					'purchaseByAudit' => $data['purchaseByAudit'] ?? null,
					'purchaseDateAudit' => $data['purchaseDateAudit'] ?? null,
					'approveByAudit' => $data['approveByAudit'] ?? null,
					'approveDateAudit' => $data['approveDateAudit'] ?? null,
				]);

			// ================= FILE UPLOAD =================
			$destinationPath = 'public/documentation_files';

			if ($request->hasFile('file1')) {
				$fileName = date("YmdHis") . '-' . $request->file('file1')->getClientOriginalName();
				$request->file('file1')->storeAs($destinationPath, $fileName);

				DB::table('assets')->where('id', $assetId)->update(['file1' => $fileName]);
			}

			if ($request->hasFile('file2')) {
				$fileName = date("YmdHis") . '-' . $request->file('file2')->getClientOriginalName();
				$request->file('file2')->storeAs($destinationPath, $fileName);

				DB::table('assets')->where('id', $assetId)->update(['file2' => $fileName]);
			}
			
			// ================= CURRENT ASSET INSERT =================
			if ($data['assetType'] === 'current') {
				$this->updateCurrentAsset($assetId, $data);
			}else {
				DB::table('assets_currs')
					->where('aid', $assetId)
					->delete();
			}

			// ================= JOURNAL =================
			$this->journalEntry($assetId);
			
			// ======= Start payment voucher entry ========
			$currentPayment = 0;
			if ($data['assetType'] === 'non-current') {

				if (!$isWip) {
					$oldPayStatus = strtolower(trim($oldRec->pay_status ?? ''));
					if ($oldPayStatus != 'full') {
						$newPayStatus = strtolower(trim($data['pay_status'] ?? ''));
						$oldAdvance = (float)($oldRec->advance_amt ?? 0);
						$newAdvance = (float)($data['advance_amt'] ?? 0);
						$totalAmount = (float)($data['invoice_value'] ?? 0);
						if ($newPayStatus == 'advance') {
							$currentPayment = $newAdvance - $oldAdvance;
						}
						else if ($newPayStatus == 'full') {
							$currentPayment = $totalAmount - $oldAdvance;
						}
					}
				}
				else {
					$oldPayStatus = strtolower(trim($oldRec->cwip_pay_status ?? ''));
					if ($oldPayStatus != 'full') {
						$newPayStatus = strtolower(trim($data['cwip_pay_status'] ?? ''));
						$oldAdvance = (float)($oldRec->cwip_advance_amt ?? 0);
						$newAdvance = (float)($data['cwip_advance_amt'] ?? 0);
						$totalAmount = (float)($data['cwip_amount'] ?? 0);
						if ($newPayStatus == 'advance') {
							$currentPayment = $newAdvance - $oldAdvance;
						}
						else if ($newPayStatus == 'full') {
							$currentPayment = $totalAmount - $oldAdvance;
						}
					}
				}
			}

			if ($currentPayment <= 0) {
				$currentPayment = 0;
			}
			if ($currentPayment > 0) {
				$this->paymentVoucherService->storePaymentVoucherEntries($assetId,'Asset',$currentPayment);
			}
			// ======= End payment voucher entry ========

			return response()->json([
				'status' => 'success',
				'message' => 'Asset updated successfully',
				'redirect' => url('/assets-list')
			]);

		} catch (\Exception $e) {

			return response()->json([
				'status' => 'error',
				'message' => 'Update failed',
				'error' => $e->getMessage()
			], 500);
		}
	}
	
	public function updateCurrentAsset($assetId, $data)
	{
		$userId = currentOwnerId();
		$type = $data['currentAssetType'] ?? '';

		$currData = [
			'aid' => $assetId,
			'added_by' => $userId,

			// 🔹 Cash
			'cash_amount' => ($type == 'Cash in Hand') ? ($data['cash_amount'] ?? 0) : 0,
			// 🔹 Bank
			'bank_id' => ($type == 'Bank Accounts') ? ($data['bank_id'] ?? null) : null,
			'bank_balance' => ($type == 'Bank Accounts') ? ($data['bank_balance'] ?? 0) : 0,
			// 🔹 Trade Receivable
			'amount' => ($type == 'Trade Receivables') ? ($data['amount'] ?? 0) : 0,
			// 🔹 Advance to Vendor
			'amount_vendor' => ($type == 'Advance to Vendor') ? ($data['amount_vendor'] ?? 0) : 0,
			// 🔹 Employee Advance
			'employee_advance_amount' => ($type == 'Employee Advance') ? ($data['employee_advance_amount'] ?? 0) : 0,
			// 🔹 Prepaid Expenses
			'prepaid_amt' => ($type == 'Prepaid Expenses') ? ($data['prepaid_amt'] ?? 0) : 0,
			// 🔹 ITC
			'itc_amt' => ($type == 'Input GST Credit') ? ($data['itc_amt'] ?? 0) : 0,
			// 🔹 TDS
			'tds_gross_amount' => ($type == 'TDS Receivable') ? ($data['tds_gross_amount'] ?? 0) : 0,
			// 🔹 Inventory / Profit
			'gross_profit' => ($type == 'Inventories') ? ($data['gross_profit'] ?? 0) : 0,
		];

		$exists = DB::table('assets_currs')
			->where('aid', $assetId)
			->first();

		if ($exists) {
			DB::table('assets_currs')
				->where('aid', $assetId)
				->update($currData);
		} else {
			DB::table('assets_currs')
				->insert($currData);
		}
	}


	public function delAsset($id)
    {
		$id = base64_decode($id);
		$asset_id = DB::table('assets')->where('id', $id)->value('asset_id');
		// deleting previous uploaded document file
		$documentationPath1 = DB::table('assets')->where('id','=',$id)->value('file1');
		if(isset($documentationPath1)){
			$documentationPath1 = public_path('storage/').$documentationPath1;
			if(file_exists($documentationPath1))
				@unlink($documentationPath1);
		}
		// deleting previous uploaded attachment file
		$attachmentPath2 = DB::table('assets')->where('id','=',$id)->value('file2');
		if(isset($attachmentPath2)){
			$attachmentPath2 = public_path('storage/').$attachmentPath2;
			if(file_exists($attachmentPath2))
				@unlink($attachmentPath2);
		}

        $delAsset = DB::table('assets')->where('id', $id)->delete();
		$delCurrAsset = DB::table('assets_currs')->where('aid', $id)->delete();
		$delJournalRec = DB::table('journals')
								->where('autoId', $id)
								->where('source', 'Asset')->delete();
		$delPaymentRec = DB::table('payment_vouchers')
							->where('f_id', $id)
							->where('source', 'Asset')->delete();
		if($delAsset){
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Asset',
				description: "Asset deleted: {$asset_id}",
				oldData: ['Asset Id' => $asset_id],
				newData: null
			);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/assets'),
				'message' => 'Asset deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/assets'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

    public function AssetVoucherList(request $request)
    {

		$title = 'Asset Vouchers';
		$userId = currentOwnerId();
		checkCoreAccess('Assets & Liabilities');

		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access

		if(Auth::user()->u_type ==1){ //ca
			// $asset_vouchers =  DB::table('asset_vouchers')
			// 				->select(DB::raw('asset_vouchers.*,company_profiles.comp_name,ca_assigns.ca_id'))
			// 				->leftJoin('company_profiles', 'asset_vouchers.added_by', '=', 'company_profiles.userId')
			// 				->leftJoin('ca_assigns', 'asset_vouchers.added_by', '=', 'ca_assigns.comp_id')
			// 				->where('ca_assigns.ca_id','=',$userId)
			// 				->where('ca_assigns.ca_assign_status','=',1)
			// 				->orderBy('id', 'DESC')->paginate(10);
			$asset_vouchers = DB::table('asset_vouchers as av')
							->leftJoin('company_profiles as cp', 'av.added_by', '=', 'cp.userId')
							->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'av.added_by')
							->select(
								'av.*',
								DB::raw("
									CASE
										WHEN av.propId IS NOT NULL AND av.propId != ''
										THEN pp.comp_name
										ELSE cp.comp_name
									END as comp_name
								")
							)
							->where('av.added_by', $userId)
							->orderBy('av.id', 'DESC')
							->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca employee
			// $asset_vouchers =  DB::table('asset_vouchers')
			// 				->select(DB::raw('asset_vouchers.*,company_profiles.comp_name,ca_assigns.ca_id'))
			// 				->leftJoin('company_profiles', 'asset_vouchers.added_by', '=', 'company_profiles.userId')
			// 				->leftJoin('ca_assigns', 'asset_vouchers.added_by', '=', 'ca_assigns.comp_id')
			// 				->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
			// 				->where('ca_assigns.ca_assign_status','=',1)
			// 				->orderBy('id', 'DESC')->paginate(10);
			$asset_vouchers = DB::table('asset_vouchers as av')
							->leftJoin('company_profiles as cp', 'av.added_by', '=', 'cp.userId')
							->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'av.added_by')
							->select(
								'av.*',
								DB::raw("
									CASE
										WHEN av.propId IS NOT NULL AND av.propId != ''
										THEN pp.comp_name
										ELSE cp.comp_name
									END as comp_name
								")
							)
							->where('av.added_by', $userId)
							->orderBy('av.id', 'DESC')
							->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$asset_vouchers = DB::table('asset_vouchers as av')
							->leftJoin('company_profiles as cp', 'av.added_by', '=', 'cp.userId')
							->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'av.added_by')
							->select(
								'av.*',
								DB::raw("
									CASE
										WHEN av.propId IS NOT NULL AND av.propId != ''
										THEN pp.comp_name
										ELSE cp.comp_name
									END as comp_name
								")
							)
							->where('av.added_by', $userId)
							->orderBy('av.id', 'DESC')
							->paginate(10);
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$asset_vouchers =  DB::table('asset_vouchers')
							->select(DB::raw('asset_vouchers.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'asset_vouchers.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$asset_vouchers_pagination = $asset_vouchers;
		//echo "<pre>"; print_r($asset_vouchers);exit;
		$array = array();
		foreach($asset_vouchers as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['v_type'] = $val->v_type;
			$array[$val->id]['voucher_no'] = $val->voucher_no;
			$array[$val->id]['voucher_name'] = $val->voucher_name;
			$array[$val->id]['invoice_date'] = $val->invoice_date;
			$array[$val->id]['total_cost'] = $val->total_cost;

			if($val->series_id >0){
				$series = Asset_series::where('id', '=', $val->series_id)->get();
				$array[$val->id]['series_name'] = isset($series[0]->series_name)?$series[0]->series_name:"";
			}else{
				$array[$val->id]['series_name'] = "";
			}
			if($val->vendor_id >0){
				$vendor = Vendor::where('id', '=', $val->vendor_id)->get();
				$array[$val->id]['vendor_name'] = isset($vendor[0]->vendor_name)?$vendor[0]->vendor_name:"";
			}else{
				$array[$val->id]['vendor_name'] = "";
			}
		}

		$asset_vouchers = json_decode(json_encode($array));

		//echo "<pre>"; print_r($asset_vouchers);exit;
		return view('User.assets-voucher-list')->with([
			'title' =>$title,
			'asset_vouchers'=>$asset_vouchers,
			'asset_vouchers_pagination' =>$asset_vouchers_pagination,
			'req_type' => $req_type
		]);
    }
    public function AddAssetVoucher()
    {
        //$this->middleware('auth');
		$userId = currentOwnerId();
		checkCoreAccess('Assets & Liabilities');
		$vendors = DB::table('vendors')
							->select(DB::raw('vendors.id,vendors.vendor_name'))
							->where('userId','=',$userId)
							->get()->toArray();
		$assetSeries = DB::table('asset_series')
							->select(DB::raw('asset_series.id,asset_series.series_name'))
							->get()->toArray();
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
        return view('User.add-assets-voucher')->with([
			'vendors'=>$vendors,
			'assetSeries'=>$assetSeries,
			'proprietorships'=>$proprietorships
        ]);
    }

	protected function validator_voucher(array $data)
	{
		return Validator::make($data, [
			'v_type'         => 'required|in:1,2',
			'voucher_no'     => 'required|string|max:100',
			'voucher_name'   => 'required|string|max:255',
			'branch_name'    => 'required|string|max:255',
			'series_id'      => 'required|exists:asset_series,id',
			'invoice_date'   => 'required|date',
			'vendor_id'      => 'required|exists:vendors,id',
			'inv_voucher_no' => 'required|string|max:100',
			'total_cost'     => 'required|numeric|min:0'
		], [
			'v_type.required' => 'Voucher Type is required',
			'voucher_no.required' => 'Voucher Number is required',
			'voucher_name.required' => 'Voucher Name is required',
			'branch_name.required' => 'Branch Name is required',
			'series_id.required' => 'Series is required',
			'vendor_id.required' => 'Vendor is required',
			'invoice_date.required' => 'Invoice Date is required',
			'inv_voucher_no.required' => 'Invoice Voucher Number is required',
			'total_cost.required' => 'Total Cost is required',
			'total_cost.numeric' => 'Total Cost must be numeric'
		]);
	}

    // protected function create_voucher(array $data)
    // {
	// 	//echo "<pre>";print_r($data);exit;
    //     return Asset_vouchers::create([
    //         'added_by' => currentOwnerId(),
    //         'v_type' => $data['v_type'],
    //         'branch_name' => $data['branch_name'],
    //         'voucher_no' => $data['voucher_no'],
    //         'voucher_name' => $data['voucher_name'],
    //         'branch_name' => $data['branch_name'],
    //         'series_id' => $data['series_id'],
    //         'invoice_date' => $data['invoice_date'],
    //         'vendor_id' => $data['vendor_id'],
    //         'inv_voucher_no' => $data['inv_voucher_no'],
    //         'total_cost' => $data['total_cost'],
	// 		'warranty_information' => date('Y-m-d H:i:s'),
	// 		'warranty_information' => date('Y-m-d H:i:s'),
    //     ]);
    // }

	protected function create_voucher(array $data)
	{
		$propId = $data['propId'];
		return Asset_vouchers::create([
			'added_by'       => currentOwnerId(),
			'propId'         => $propId,
			'v_type'         => $data['v_type'],
			'branch_name'    => $data['branch_name'],
			'voucher_no'     => $data['voucher_no'],
			'voucher_name'   => $data['voucher_name'],
			'series_id'      => $data['series_id'],
			'invoice_date'   => $data['invoice_date'],
			'vendor_id'      => $data['vendor_id'],
			'inv_voucher_no' => $data['inv_voucher_no'],
			'total_cost'     => $data['total_cost'],
			'warranty_information' => now(),
		]);
	}

	public function save_add_voucher(Request $request)
	{
		$validation = $this->validator_voucher($request->all());

		if ($validation->fails()) {
			return response()->json([
				'status' => 'error',
				'class'  => 'err',
				'message' => $validation->errors()->first()
			]);
		}

		$insertAssetVoucher = $this->create_voucher($request->all());

		if ($insertAssetVoucher){
			return response()->json([
				'status' => 'success',
				'class'  => 'succ',
				'redirect' => url('/assets-voutcher-list'),
				'message' => 'Asset voucher added successfully'
			]);
		}

		return response()->json([
			'status' => 'error',
			'class'  => 'err',
			'message' => 'Asset voucher add failed'
		]);
	}


	public function edit_asset_voucher($vId)  {

		if(Auth::user()->u_type ==1){
			return redirect('/asset-voucher');
		}
		$vId = base64_decode($vId);
		$userId = currentOwnerId();
		$assetvoucher = DB::table('asset_vouchers')
								->where('id', '=', $vId)
								->get();
		$assetvoucher = $assetvoucher[0];
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$vendors = DB::table('vendors')
							->select(DB::raw('vendors.id,vendors.vendor_name'))
							//->where('uid','=',currentOwnerId())
							->get()->toArray();
		$assetSeries = DB::table('asset_series')
							->select(DB::raw('asset_series.id,asset_series.series_name'))
							->get()->toArray();
		//echo "<pre>";print_r($assetvoucher);exit;
		return view('User.edit-asset-voucher')->with([
			'assetvoucher' => $assetvoucher,
			'vendors'=>$vendors,
			'assetSeries'=>$assetSeries,
			'proprietorships'=>$proprietorships,
			'vId' => $vId
		]);
    }

	public function view_asset_voucher($vId)  {

		$vId = base64_decode($vId);
		$userId = currentOwnerId();		
		$assetvoucher = DB::table('asset_vouchers')
								->where('id', '=', $vId)
								->get();
		$assetvoucher = $assetvoucher[0];
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$vendors = DB::table('vendors')
							->select(DB::raw('vendors.id,vendors.vendor_name'))
							//->where('uid','=',currentOwnerId())
							->get()->toArray();
		$assetSeries = DB::table('asset_series')
							->select(DB::raw('asset_series.id,asset_series.series_name'))
							->get()->toArray();
		//echo "<pre>";print_r($assetvoucher);exit;
		return view('User.view-asset-voucher')->with([
			'assetvoucher' => $assetvoucher,
			'vendors'=>$vendors,
			'assetSeries'=>$assetSeries,
			'proprietorships'=>$proprietorships,
			'vId' => $vId
		]);
    }

	public function update_voucher(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$vId = $request->id;
		$propId = $request->propId;
		$validation = $this->validator_voucher($request->all());

		if ($validation->fails()) {
			return response()->json([
				'status' => 'error',
				'class'  => 'err',
				'message' => $validation->errors()->first()
			]);
		}
        else{
			//start update project
			$update = DB::table('asset_vouchers')
					->where('id', $vId)
					->update(
						 array(
								'propId' => $propId,
								'v_type' => $request->v_type,
								'voucher_no' => $request->voucher_no,
								'voucher_name' => $request->voucher_name,
								'branch_name' => $request->branch_name,
								'series_id' => $request->series_id,
								'invoice_date' => $request->invoice_date,
								'vendor_id' => $request->vendor_id,
								'inv_voucher_no' => $request->inv_voucher_no,
								'total_cost' => $request->total_cost
						 )
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/assets-voutcher-list'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
			//end update item

		}
    }

	public function delAssetVoucher(Request $request)
    {
		$voucher_no = DB::table('asset_vouchers')->where('id', $request->id)->value('voucher_no');
        $delAssetVoucher = DB::table('asset_vouchers')->where('id', $request->id)->delete();
		if($delAssetVoucher){
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Assets Voucher',
				description: "Assets Voucher deleted: {$voucher_no}",
				oldData: ['Voucher No' => $voucher_no],
				newData: null
			);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/assets-voutcher-list'),
				'message' => 'Asset voucher deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/assets-voutcher-list'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

	//Adde series

	protected function create_series(array $data)
    {
        return  Asset_series ::create([
            'series_name' => $data['series_name'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }



	// public function save_add_series_name(Request $request)
	// {
	// 	$Series = DB::table('asset_series')
	// 							->where('series_name', '=', $request->series_name)
	// 							->get();
	// 	if(sizeof($Series) == 0)
	// 	{
	// 		$insertSeries = $this->create_series($request->all());
	// 		if ($insertSeries){
	// 			$msg = array(
	// 				'status' => 'success',
	// 				'class' => 'succ',
	// 				'redirect' => url('/'),
	// 				'message' => 'Series added successfully'
	// 			);
	// 			return response()->json($msg);
	// 		}else{
	// 			$msg = array(
	// 				'status' => 'error',
	// 				'class' => 'err',
	// 				'redirect' => url('/'),
	// 				'message' => 'Series add failed'
	// 			);
	// 			return response()->json($msg);
	// 		}
	// 	}else{
	// 			$msg = array(
	// 				'status' => 'error',
	// 				'class' => 'err',
	// 				'redirect' => url('/'),
	// 				'message' => 'Series already exists!'
	// 			);
	// 			return response()->json($msg);
	// 	}

    // }

	public function save_add_series_name(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'series_name' => 'required|string|max:255'
		], [
			'series_name.required' => 'Series Name is required'
		]);

		if ($validator->fails()) {
			return response()->json([
				'status' => 'error',
				'class'  => 'err',
				'message' => $validator->errors()->first()
			]);
		}

		$Series = DB::table('asset_series')
						->where('series_name', '=', $request->series_name)
						->get();

		if (sizeof($Series) == 0)
		{
			$insertSeries = $this->create_series($request->all());

			if ($insertSeries){
				return response()->json([
					'status' => 'success',
					'class'  => 'succ',
					'message' => 'Series added successfully'
				]);
			}else{
				return response()->json([
					'status' => 'error',
					'class'  => 'err',
					'message' => 'Series add failed'
				]);
			}
		}
		else{
			return response()->json([
				'status' => 'error',
				'class'  => 'err',
				'message' => 'Series already exists!'
			]);
		}
	}

}
