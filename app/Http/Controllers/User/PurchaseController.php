<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Purchases;
use App\Models\Purchases_values;
use App\Models\Voucher_purchases;
use App\Models\Customers;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Carriageinwards;

use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use App\Helpers\AuditLogger;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class PurchaseController extends Controller
{
	public function __construct(JournalService $journalService, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
		$this->paymentVoucherService = $paymentVoucherService;
    }
	
    public function PurchaseInvoices(Request $request)
    {
        $title = 'Purchase Invoice';
		$userId = currentOwnerId();
		checkCoreAccess('Purchase');
		
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		
		if(Auth::user()->u_type != 3 || Auth::user()->u_type != 6){ //user
			$sales = DB::table('purchases')
					->select(DB::raw('purchases.*, company_profiles.comp_name,proprietorship_profiles.comp_name as prop_name, vendors.company_name as vendor_company_name'))
					->leftJoin('company_profiles', 'purchases.added_by', '=', 'company_profiles.userId')
					->leftJoin('proprietorship_profiles', 'purchases.propId', '=', 'proprietorship_profiles.id')
					->leftJoin('vendors', 'purchases.inv_name', '=', 'vendors.id')
					->where('purchases.added_by', '=', $userId)
					->orderBy('purchases.id', 'DESC')
					->get();


		}
		elseif(Auth::user()->u_type ==3){ //admin
			$sales =  DB::table('purchases')
							->select(DB::raw('purchases.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'purchases.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$sales_pagination = $sales;

		$array = array();

		foreach($sales as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = !empty($val->comp_name) ? $val->comp_name : null;
			$array[$val->id]['prop_name'] = $val->prop_name;
			$array[$val->id]['inv_num'] = $val->inv_num;
			$array[$val->id]['inv_name'] = $val->inv_name;
			$array[$val->id]['bill_name'] = $val->bill_name;
			$array[$val->id]['contact_no'] = $val->contact_no;
			$array[$val->id]['branch_name'] = $val->branch_name;
			$array[$val->id]['inv_date'] = $val->inv_date;
			$array[$val->id]['mode_of_pay'] = $val->mode_of_pay;
			$array[$val->id]['due_amount'] = $val->due_amount;
			$array[$val->id]['other_payment'] = $val->other_payment;
			$array[$val->id]['pay_status'] = $val->pay_status;
			$array[$val->id]['total_amount'] = getRoundedAmount($val->total_amount);
			$array[$val->id]['status'] = $val->status;

			$customerName =  DB::table('vendors')
							->select(DB::raw('vendors.vendor_name,vendors.vendor_phone'))
							->where('id', '=', $val->inv_name)
							->get();
			$array[$val->id]['cust_name'] = isset($customerName[0]->vendor_name)?$customerName[0]->vendor_name:"";
			$array[$val->id]['cust_phone'] = isset($customerName[0]->vendor_phone)?$customerName[0]->vendor_phone:"";
			if($val->id >0){				
				$data = DB::table('purchase_values as pv')
							->leftJoin('purchases as p', 'p.id', '=', 'pv.sid')
							->selectRaw('
								SUM(COALESCE(pv.amount,0) + COALESCE(pv.tax_amt,0)) as grandTotal,
								COALESCE(MAX(p.shipping_cost),0) as shipping_cost
							')
							->where('pv.sid', $val->id)
							->first();

				$array[$val->id]['shipping_cost'] = $data->shipping_cost;
				$array[$val->id]['grandTotal'] = getRoundedAmount(($data->grandTotal ?? 0) + ($data->shipping_cost ?? 0));
			}else{
				$array[$val->id]['grandTotal'] = 0;
				$array[$val->id]['shipping_cost'] = 0;

			}
		}
		$sales = json_decode(json_encode($array));
		//echo "<pre>"; print_r($sales);exit;
        return view('User.purchase-invoice')->with([
			'title' =>$title,
			'sales'=>$sales,
			'sales_pagination' =>$sales_pagination,
			'invoice_create_status' => $this->invoice_create_status(),
		]);
    }
	
	// Maximum 2 invoices can remain pending
	public function invoice_create_status() {
		$userId = currentOwnerId();

		$count = DB::table('purchases')
			->where('added_by', $userId)
			->where('status', '0')
			->count();

			if($count >=2){
				return "false";
			}else{
				return "true";
			}
	}

	public function companyInfoFill()
    {
        $userId = currentOwnerId();

        // Fetch company name prefix (first 2 letters uppercased)
        $company = DB::table('company_profiles')
                    ->where('userId', $userId)
                    ->value('comp_name');

        if (empty($company)) {
            return redirect()->route('user.CompanyProfile');
        }else{
            return true;
        }
    }

	public function create_purchase_invoice_number($userId)
	{
		
		$this->companyInfoFill(); //------------Check Company Info fill ----------

		// Fetch company name prefix (first 2 letters uppercased)
		$company = DB::table('company_profiles')
					->where('userId', $userId)
					->value('comp_name');

		if (empty($company)) {
			return false;
		}

		$prefix = strtoupper(substr($company, 0, 3));

		// Calculate current financial year (e.g., 2025-2026 => 25-26)
		$now = Carbon::now();
		$year = $now->year;
		$month = $now->month;

		if ($month >= 4) {
			$fyStart = $year;
			$fyEnd = $year + 1;
		} else {
			$fyStart = $year - 1;
			$fyEnd = $year;
		}

		$financialYear = substr($fyStart, -2) . '-' . substr($fyEnd, -2);
		$fullPrefix = $prefix . '/' . $financialYear . '/SL/';

		// Get max increment number from 'sales' table only
		$maxIncrement = DB::table('purchases')
			->where('added_by', $userId)
			//->where('inv_num', 'like', $fullPrefix . '%')
			->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(inv_num, '/', -1) AS UNSIGNED)) as max_num"))
			->value('max_num');

		$newIncrement = str_pad($maxIncrement + 1, 4, '0', STR_PAD_LEFT);
		$newInvoiceNumber = $fullPrefix . $newIncrement;

		return $newInvoiceNumber;
	}
	
	public function create_purchase_invoice_number_proprietorship($id, $userId)
	{

		// Fetch company name prefix (first 2 letters uppercased)
		$company = DB::table('proprietorship_profiles')
					->where('id', $id)
					->value('comp_name');

		if (empty($company)) {
			return false;
		}

		$prefix = strtoupper(substr($company, 0, 3));

		// Calculate current financial year (e.g., 2025-2026 => 25-26)
		$now = Carbon::now();
		$year = $now->year;
		$month = $now->month;

		if ($month >= 4) {
			$fyStart = $year;
			$fyEnd = $year + 1;
		} else {
			$fyStart = $year - 1;
			$fyEnd = $year;
		}

		$financialYear = substr($fyStart, -2) . '-' . substr($fyEnd, -2);
		$fullPrefix = $prefix . '/' . $financialYear . '/SL/';

		// Get max increment number from 'sales' table only
		$maxIncrement = DB::table('purchases')
			->where('added_by', $userId)
			//->where('inv_num', 'like', $fullPrefix . '%')
			->select(DB::raw("MAX(CAST(SUBSTRING_INDEX(inv_num, '/', -1) AS UNSIGNED)) as max_num"))
			->value('max_num');

		$newIncrement = str_pad($maxIncrement + 1, 4, '0', STR_PAD_LEFT);
		$newInvoiceNumber = $fullPrefix . $newIncrement;

		return $newInvoiceNumber;
	}
	
	public function purchaseShippingCost(Request $request)
	{
		$userId = currentOwnerId();

		// Update purchase shipping cost
		DB::table('purchases')
			->where('id', $request->sId)
			->update([
				'shipping_cost' => $request->shipping_cost
			]);

		//Start entry in Expenses table
		$purchase = DB::table('purchases')
			->where('id', $request->sId)
			->first();

		if ($purchase) 
		{
			// Tax master
			$taxMaster = DB::table('tax_deduction_masters')
				->where('expense_type', 'indirect')
				->where('expense_head', 'travel_conveyance')
				->first();

			$expenseAmt   = (float) $request->shipping_cost;
			$allowedRatio = (float) ($taxMaster->allow_start ?? 100);
			$rebateAmt = round(($expenseAmt * $allowedRatio) / 100, 2);
			$payStatus = $purchase->pay_status ?? 'due';

			$expenseData = [
				'added_by'        => $userId,
				'propId'          => $purchase->propId,
				'expense_date'    => $purchase->inv_date,
				'threshold_type'  => 'Single',
				'mode_of_expense' => $purchase->mode_of_pay ?? 'Cash',
				'expense_cat'     => 'indirect',
				'expense_type'    => 'travel_conveyance',
				'expense_amt'     => $expenseAmt,
				'vendor_id'       => $purchase->inv_name,
				'status'          => 1,
				'payment_status'  => strtolower($payStatus),
				'advance_amount'  => 0,
				'balance_amount'  => $expenseAmt,

				// Tax Master
				'tax_treatment'   => $taxMaster->tax_treatment ?? null,
				'allowed_ratio'   => $allowedRatio,
				'rebate_amt'      => $rebateAmt,

				'tds_applicable'  => 'no',
				'gst_applicable'  => 'no',

				'created_at'      => now(),
				'updated_at'      => now(),
			];

			//Update if already exists for this purchase
			$expense = DB::table('expenses')
				->where('exp_invno', $purchase->inv_num)
				->where('expense_type', 'travel_conveyance')
				->first();

			if ($expense) {
				unset($expenseData['created_at']);
				DB::table('expenses')
					->where('id', $expense->id)
					->update($expenseData);
			} else {
				$expenseData['exp_invno'] = $purchase->inv_num;
				DB::table('expenses')->insert($expenseData);
			}
		}

		$sales_values = $this->items_purchase_list($request->sId);
		return view('User.ajax-purchase-invoice-display')->with([
			'sales_values' => $sales_values,
		]);
	}


    public function CreatePurchaseInvoices()
    {
        $userId = currentOwnerId();
		checkCoreAccess('Biz Operations');
		$invoiceNo = $this->create_purchase_invoice_number($userId);
		$compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,gst_no,comp_bill_pin,comp_bill_addone,comp_bill_addtwo,comp_bill_name,comp_bill_mobile_no,comp_bill_state,comp_bill_city'))
								->where('company_profiles.userId','=',$userId)
								->get();
		$custData = DB::table('customers')
								->select(DB::raw('customers.*'))
								->where('customers.userId','=',$userId)
								->where('customers.status','=',1)
								->get();
		$comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
		$comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
		$comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
        $gst_no= isset($compData[0]->gst_no)?$compData[0]->gst_no:"";
		$comp_pan_no= isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
        $comp_bill_pin= isset($compData[0]->comp_bill_pin)?$compData[0]->comp_bill_pin:"";
		$comp_bill_addone= isset($compData[0]->comp_bill_addone)?$compData[0]->comp_bill_addone:"";
		$comp_bill_addtwo= isset($compData[0]->comp_bill_addtwo)?$compData[0]->comp_bill_addtwo:"";
		$comp_bill_name= isset($compData[0]->comp_bill_name)?$compData[0]->comp_bill_name:"";
		$comp_bill_mobile_no= isset($compData[0]->comp_bill_mobile_no)?$compData[0]->comp_bill_mobile_no:"";
        $comp_bill_state=isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:"";
        $comp_bill_city=isset($compData[0]->comp_bill_city)?$compData[0]->comp_bill_city:"";
		//echo "<pre>"; print_r($compData);exit;
		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:0)->get();
		$cities_bill = City::where('state_id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:0)->get();
		//echo "<pre>"; print_r($states_bill);exit;
		$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country)?$compDetails->comp_ship_country:0)->get();
		$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state)?$compDetails->comp_ship_state:0)->get();

		$pos = DB::table('puos')
					->where('status', 3)
					->where('puos.added_by', $userId) 
					->select('id', 'inv_num')
					->get();
					
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
					
        return view('User.create-purchase-invoice')->with([
			'invoiceNo'=>$invoiceNo,
			'comp_name'=>$comp_name,
			'comp_phone'=>$comp_phone,
			'comp_email'=>$comp_email,
            'gst_no'=>$gst_no,
			'comp_pan_no'=>$comp_pan_no,
			'comp_bill_addone'=>$comp_bill_addone,
			'comp_bill_addtwo'=>$comp_bill_addtwo,
            'comp_bill_pin'=>$comp_bill_pin,
            'comp_bill_state'=>$comp_bill_state,
            'comp_bill_city'=>$comp_bill_city,
			'comp_bill_name'=>$comp_bill_name,
			'comp_bill_mobile_no'=>$comp_bill_mobile_no,
			'countries'=>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'states_ship'=>$states_ship,
			'cities_ship'=>$cities_ship,
			'custData'=>$custData,
			'pos' => $pos,
			'proprietorships' => $proprietorships 
		]);
    }

    protected function getinvcust_purchase(Request $request)
	{

		 $id = $request->id;
		 $salesTableID = $request->salesTableID;


		$result = DB::table('purchases')
				->where('inv_name', '=', $id)
				->where('id', '=', $salesTableID)
				->get();

		//echo "<pre>";print_r($result);exit;

		if (count($result) > 0) {
			$stateBill = State::query()
				->where('country_id', '=', $result[0]->bill_country)
				->get()->toArray();
			$cityBill = City::query()
				->where('state_id', '=', $result[0]->bill_state)
				->get()->toArray();
			$stateShip = State::query()
				->where('country_id', '=', $result[0]->ship_country)
				->get()->toArray();
			$cityShip = City::query()
				->where('state_id', '=', $result[0]->ship_state)
				->get()->toArray();
			$resStateBill = [];
			$resCityBill = [];
			$resStateShip = [];
			$resCityShip = [];

			foreach ($stateBill as $row) {

				$resStateBill[] = array("id" => $row['id'], "name" => $row['name'], "sid" => $result[0]->bill_state);
			}
			foreach ($cityBill as $row1) {

				$resCityBill[] = array("id" => $row1['id'], "name" => $row1['name'], "sid" => $result[0]->bill_city);
			}
			foreach ($stateShip as $row) {

				$resStateShip[] = array("id" => $row['id'], "name" => $row['name'], "selid" => $result[0]->ship_state);
			}
			foreach ($cityShip as $row1) {

				$resCityShip[] = array("id" => $row1['id'], "name" => $row1['name'], "selid" => $result[0]->ship_city);
			}
			$array = array();
			$resultCust = vendor::query()
					->where('id', '=', $id)
					->get();
			foreach ($result as $k => $val) {
				$array['id'] = $resultCust[0]->id;
				$array['cust_email'] = $resultCust[0]->vendor_email;
				$array['cust_phone'] = $resultCust[0]->vendor_phone;
				$array['cust_pan'] = $resultCust[0]->vendor_pan;
				$array['gst_reg'] = $resultCust[0]->gst_reg;
				$array['cust_gst_no'] = $resultCust[0]->vendor_gstin;



				//$array['cust_gst_no'] = $val->vendor_gstin;
				// $array['cust_gst_type'] = $val->cust_gst_type;
				$array['add_type'] = $val->add_type;
				$array['cust_bill_gstno'] = $val->cust_bill_gstno;
				$array['cust_bill_contact'] = $val->cust_bill_contact;
				$array['cust_bill_mobilno'] = $val->cust_bill_mobilno;
				$array['cust_bill_designa'] = $val->cust_bill_designa;
				$array['cust_bill_name'] = $val->bill_name;
				$array['cust_bill_addone'] = $val->bill_addone;
				$array['cust_bill_addtwo'] = $val->bill_addtwo;
				$array['cust_bill_country'] = $val->bill_country;
				$array['cust_bill_state'] = $val->bill_state;
				$array['cust_bill_city'] = $val->bill_city;
				$array['stateBill'] = $resStateBill;
				$array['cityBill'] = $resCityBill;
				$array['cust_bill_pin'] = $val->bill_pin;

				$array['cust_ship_gstno'] = $val->cust_ship_gstno;
				$array['cust_ship_contact'] = $val->cust_ship_contact;
				$array['cust_ship_mobilno'] = $val->cust_ship_mobilno;
				$array['cust_ship_designa'] = $val->cust_ship_designa;
				$array['cust_ship_name'] = $val->ship_name;

				$array['cust_ship_addone'] = $val->ship_addone;
				$array['cust_ship_addtwo'] = $val->ship_addtwo;
				$array['cust_ship_country'] = $val->ship_country;
				$array['cust_ship_state'] = $val->ship_state;
				$array['cust_ship_city'] = $val->ship_city;
				$array['stateShip'] = $resStateShip;
				$array['cityShip'] = $resCityShip;
				$array['cust_ship_pin'] = $val->ship_pin;

			}

		} else {
			$result = vendor::query()
				->where('id', '=', $id)
				->get();
			//echo "<pre>";print_r($result);exit;

			$stateBill = State::query()
				->where('country_id', '=', 101)
				->get()->toArray();
			$cityBill = City::query()
				->where('state_id', '=', $result[0]->billing_state)
				->get()->toArray();
			$stateShip = State::query()
				->where('country_id', '=', $result[0]->billing_city)
				->get()->toArray();
			$cityShip = City::query()
				->where('state_id', '=', $result[0]->shipping_state)
				->get()->toArray();
			$resStateBill = [];
			$resCityBill = [];
			$resStateShip = [];
			$resCityShip = [];
			foreach ($stateBill as $row) {

				$resStateBill[] = array("id" => $row['id'], "name" => $row['name'], "sid" => $result[0]->billing_state);
			}
			foreach ($cityBill as $row1) {

				$resCityBill[] = array("id" => $row1['id'], "name" => $row1['name'], "sid" => $result[0]->billing_city);
			}
			foreach ($stateShip as $row) {

				$resStateShip[] = array("id" => $row['id'], "name" => $row['name'], "selid" => $result[0]->shipping_state);
			}
			foreach ($cityShip as $row1) {

				$resCityShip[] = array("id" => $row1['id'], "name" => $row1['name'], "selid" => $result[0]->shipping_city);
			}
			$array = array();
			foreach ($result as $k => $val) {
				$array['id'] = $val->id;
				$array['vendor_email'] = $val->vendor_email;
				$array['vendor_phone'] = $val->vendor_phone;
				$array['cust_pan'] = $val->vendor_pan;
				$array['gst_reg'] = $val->vendor_gstin;
				$array['cust_gst_no'] = $val->vendor_gstin;
				$array['cust_gst_type'] = $val->vendor_gstin;
				$array['comp_type'] = $val->comp_type;

				$array['cust_bill_addone'] = $val->billing_address1;
				$array['cust_bill_addtwo'] = $val->billing_address2;
				$array['cust_bill_country'] = $val->billing_country;
				$array['cust_bill_state'] = $val->billing_state;
				$array['cust_bill_city'] = $val->billing_city;
				$array['stateBill'] = $resStateBill;
				$array['cityBill'] = $resCityBill;
				$array['cust_bill_pin'] = $val->billing_pincode;


				$array['cust_bill_gstno'] = $val->cust_bill_gstno;
				$array['cust_bill_contact'] = $val->cust_bill_contact;
				$array['cust_bill_mobilno'] = $val->cust_bill_mobilno;
				$array['cust_bill_designa'] = $val->cust_bill_designa;
				$array['cust_bill_name'] = $val->billing_name;



				$array['cust_ship_addone'] = $val->shipping_address1;
				$array['cust_ship_addtwo'] = $val->shipping_address2;
				$array['cust_ship_country'] = $val->shipping_country;
				$array['cust_ship_state'] = $val->shipping_state;
				$array['cust_ship_city'] = $val->shipping_city;
				$array['stateShip'] = $resStateShip;
				$array['cityShip'] = $resCityShip;
				$array['cust_ship_pin'] = $val->shipping_pincode;

				$array['cust_ship_gstno'] = $val->cust_ship_gstno;
				$array['cust_ship_contact'] = $val->cust_ship_contact;
				$array['cust_ship_mobilno'] = $val->cust_ship_mobilno;
				$array['cust_ship_designa'] = $val->cust_ship_designa;
				$array['cust_ship_name'] = $val->cust_ship_name;


			}
		}




		//$result = json_decode(json_encode($array));
		$result = $array;
		//echo "<pre>";print_r($result);exit;
		echo json_encode($result);
	}
	
	public function saveProductAndPurchase(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		DB::beginTransaction();
		$uid = currentOwnerId();
		$sid = $request->sId;
		try {
			//SAVE PRODUCT			
			$insertItem = Helper::createProductService($request->all());
			// Save images
			if ($request->hasFile('prod_image')) {
				foreach ($request->file('prod_image') as $file) {
					$fileName = date("YmdHis") . '-' . $file->getClientOriginalName();
					$file->storeAs('public/product_images', $fileName);

					DB::table('product_images')->insert([
						'product_id' => $insertItem->id,
						'image_path' => $fileName,
						'created_at' => now(),
						'updated_at' => now(),
					]);
				}
			}

			//CALCULATIONS
			$qty   = (!empty($request->opening_stock_bal) && $request->opening_stock_bal > 0) ? $request->opening_stock_bal : 1;
			$rate  = $request->selling_price ?? $request->ser_selling_price;
			$disc  = $request->disc_sell ?? 0;
			$discType = $request->disc_sell_type ?? 'percentage';
			$gstRate  = $request->gst_rate_prod ?? $request->gst_rate_service;

			// Discount per unit
			if ($discType == 'percentage') {
				$discAmt = ($rate * $disc) / 100;
			} else {
				$discAmt = $disc;
			}
			// Per unit taxable
			$taxablePerUnit = $rate - $discAmt;
			// Total taxable
			$taxable = $taxablePerUnit * $qty;
			// Total tax
			$taxAmt  = ($taxable * $gstRate) / 100;

			// Grand amount
			$amount  = $taxable;

			//INSERT INTO purchase_values
			DB::table('purchase_values')->insert([
				'sid'       => $sid, // current sales id (replace with dynamic)
				'uid'       => $uid,
				'prod_id'   => $insertItem->id,
				'quantity'  => $qty,
				'rate'      => $rate,
				'disc'      => $disc,
				'disc_type' => $discType,
				'disc_amt'  => $discAmt,
				'tax_amt'   => $taxAmt,
				'amount'    => $amount,
				'tax_type'  => "N/A",
				'gst_rate'  => $gstRate,
				'gst_trans' => $request->gst_trans,
				'billing_type' => "Product/ Service Billing",
				'prod_gov_fee' => isset($request->prod_gov_fee) ? $request->prod_gov_fee : 0,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			DB::commit();
			$sales_values = $this->items_purchase_list($sid);
			return view('User.ajax-purchase-invoice-display')->with([
				'sales_values' => $sales_values,
			]);

		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'class'  => 'err',
				'message'=> $e->getMessage()
			], 500);
		}
	}

	public function purchase_items_display(Request $request)
    {
		//$this->middleware('auth');

		$sid = $request->sId;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$purchase_values = DB::table('purchase_values')
								->select(DB::raw('purchase_values.id,purchase_values.quantity'))
								->where('sid', '=', $sid)
								->where('uid', '=', $uid)
								->where('prod_id', '=', $prod_id)
								->get();

		// $product = DB::table('products')
		// 						->select(DB::raw('products.id,products.item_name,products.selling_price,products.disc_sell,products.disc_sell_type'))
		// 						->where('id', '=', $prod_id)
		// 						->get();

		$product = DB::table('products')
			->select(DB::raw('products.id,products.item_name,products.item_type,products.service_name,products.selling_price,products.ser_selling_price,products.disc_sell,products.disc_sell_type'))
			->where('id', '=', $prod_id)
			->get();

		$billing_type = isset($request->billing_type)?$request->billing_type:"";
		$gst_rate = isset($request->gst_rate)?$request->gst_rate:18;
		$gst_trans = isset($request->gst_trans)?$request->gst_trans:"";
		if (count($purchase_values) == 0) {
			$quantity = 1;
		}else{
			$quantity = $purchase_values[0]->quantity;
		}
		if($product[0]->item_type =='service'){
			$rate = $product[0]->ser_selling_price;
		}else{
			$rate = $product[0]->selling_price;
		}

		$disc = $request->disc_sell;
		$disc_type = $request->disc_sell_type;

		if($disc_type == "percentage")
		{
			$disc_amt = (($rate * $disc)/100);
		}else{
			$disc_amt = $disc;
		}
		$amount = ($rate - $disc_amt) * $quantity;
		$tax_amt = ($amount*$gst_rate)/100;
		$tax_type = "N/A";

		if (count($purchase_values) == 0) {
			$values = array('sid' => $sid,'uid' => $uid,'prod_id' => $prod_id,'quantity' => $quantity,'rate' => $rate,'disc' => $disc,'disc_type'=>$disc_type,'disc_amt' => $disc_amt,'tax_amt'=>$tax_amt,'amount'=>$amount,'tax_type'=>$tax_type,'billing_type'=>$billing_type,'gst_rate'=>$gst_rate,'gst_trans'=>$gst_trans);
			$insertInvoice = DB::table('purchase_values')->insert($values);
		}else{
			$update = DB::table('purchase_values')
					->where('sid', '=', $sid)
					->where('uid', '=', $uid)
					->where('prod_id', '=', $prod_id)
					->update(
						array(
								'quantity' => $quantity,
								'rate' => $rate,
								'disc' => $disc,
								'disc_type'=>$disc_type,
								'disc_amt' => $disc_amt,
								'tax_amt'=>$tax_amt,
								'amount'=>$amount,
								'tax_type'=>$tax_type,
								'billing_type'=>$billing_type,
								'gst_rate'=>$gst_rate,
								'gst_trans'=>$gst_trans
						)
					);
		}


		$sales_values = $this->items_purchase_list($sid);
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.ajax-purchase-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);
    }
	
	public function journalEntry($sid,$uid)
	{
		$purchase = DB::table('purchases as p')
			->leftJoin('vendors as v', 'v.id', '=', 'p.inv_name')
			->select(
				'p.*',
				'v.vendor_name'
			)
			->where('p.id', $sid)
			->where('p.added_by', $uid)
			->first();
		$totals = DB::table('purchase_values')
			->selectRaw('
				SUM(amount) as total_amount,
				SUM(tax_amt) as total_tax,
				AVG(gst_rate) as avg_gst_rate,
				MAX(gst_trans) as gst_trans
			')
			->where('sid', $sid)
			->where('uid', $uid)
			->first();

		if ($purchase) {
			$amount = ($totals->total_amount+$totals->total_tax);
			//$this->paymentVoucherService->storePaymentVoucherEntries($sid,'Purchase',$amount);
			
			$this->journalService->storePurchaseJournalEntries([
				'source'        => 'Purchase',
				'autoId'        => $sid,
				'added_by'      => $uid,
				'propId'        => $purchase->propId,
				'date'          => $purchase->inv_date,
				'reference_no'  => $purchase->inv_num,
				'entry_type'    => 'Purchase',
				'party_name'    => $purchase->vendor_name ?? '',
				'pay_status'    => $purchase->pay_status ?? '',
				'amount'    	=> $amount ?? 0,
				'total_amount'  => $totals->total_amount ?? 0,
				'gst_amount'    => $totals->total_tax ?? 0,
				'gst_rate'      => $totals->avg_gst_rate ?? 0,
				'gst_trans'     => $totals->gst_trans ?? 'intrastate',
				'tds_applicable'=> $purchase->tds_applicable ?? 'no',
				'tds_percent'   => $purchase->tds_percentage ?? 0,
				'tds_amt'       => $purchase->tds_amount ?? 0,
				'tds_id'        => $purchase->tds_id ?? null,
				'status'        => $purchase->status,
			]);
		}
		
	}
	
	public function journalEntryVoucher($vid,$uid)
	{			
		$voucher = DB::table('voucher_purchases as p')
					->leftJoin('vendors as v', 'v.id', '=', 'p.v_name')
					->select(
						'p.*',
						'v.vendor_name'
					)
					->where('p.id', $vid)
					->where('p.added_by', $uid)
					->first();

		if(!$voucher){
			return;
		}
		
		$gstRate = (float) $voucher->gst_rate;
		$totalAmount = (float) $voucher->total_amt;
		$gstAmount = $gstRate > 0 ? round(($totalAmount * $gstRate) / 100, 2) : 0;
		$baseAmount = $totalAmount - $gstAmount;

		$this->journalService->storePurchaseVoucherJournalEntries([
			'source'        => 'Purchase Voucher',
			'voucher_type'  => 'Purchase '.$voucher->note_type, // sales_credit / sales_debit
			'autoId'        => $voucher->id,
			'added_by'      => $uid,
			'propId'        => $voucher->propId ?? null,
			'date'          => $voucher->inv_date,
			'reference_no'  => $voucher->inv_number,
			'entry_type'    => 'Purchase '.$voucher->note_type,
			'party_name'    => $voucher->vendor_name ?? '',
			'pay_status'    => 'Full',
			'notes'   		=> !empty($voucher->otherIssuance) ? $voucher->otherIssuance : ($voucher->reason_issuance ?? ''),

			'amount'        => $totalAmount,
			'total_amount'  => $totalAmount,
			'base_amount'   => $baseAmount,
			'gst_amount'    => $gstAmount,
			'gst_rate'      => $voucher->gst_rate,
			'gst_trans'     => 'intrastate',
			'status'        => 1,
		]);
	}

    protected function validatorSeller(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
		return Validator::make($data, [
			'inv_num' => 'required',
			'inv_date' => 'required',
			'seller_name' => 'required',
			'seller_contact' => 'required',
			//'seller_email' => 'required',
			//'seller_pan' => 'required',
			'seller_addone' => 'required',
			//'seller_country' => 'required',
			'seller_state' => 'required',
			'seller_city' => 'required',
			'seller_pin' => 'required',
		]);

    }

    protected function create(array $data)
    {
		//echo "<pre>";print_r($data);exit;

		$uid = currentOwnerId();
		$propId = $data['propId'];
		if(!empty($propId)) {
			$invoiceNo = $this->create_purchase_invoice_number_proprietorship($propId,$uid);
		} else {
			$invoiceNo = $this->create_purchase_invoice_number($uid);
		}

        return Purchases::create([
            'added_by' => $uid,
			'propId' => $propId,
            'inv_num' => $data['inv_num'], //$invoiceNo,
			'inv_date' => $data['inv_date'],
			'pay_status' => 'Due',
			'seller_name' => $data['seller_name'],
			'seller_contact' => $data['seller_contact'],
			'seller_email' => $data['seller_email'],
			'seller_pan' => $data['seller_pan'],
			'seller_gst' => $data['seller_gst'],
			'seller_person_name'=> $data['seller_person_name'],
			'seller_person_no'=> $data['seller_person_no'],
			'seller_addone' => $data['seller_addone'],
			'seller_addtwo' => $data['seller_addtwo'],
			//'seller_country' => $data['seller_country'],
			'seller_state' => $data['seller_state'],
			'seller_city' => $data['seller_city'],
			'seller_pin' => $data['seller_pin'],
			'created_at' => date('Y-m-d H:i:s')

        ]);
    }

    public function save_purchase_invoice(Request $request)  {

		//echo "<pre>";print_r($request->file('prod_image'));exit;
		//$input = Input::all();
		//dd($input);
		$validation = $this->validatorSeller($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertSales = $this->create($request->all());
			$sId = DB::getPdo()->lastInsertId();

			if ($insertSales){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/edit-purchase-invoice/'.base64_encode($sId)),
					'message' => 'Purchase added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Purchase add failed'
				);
				return response()->json($msg);
			}

		}
    }

    public function items_purchase_list($sid)
	{
		$purchase = DB::table('purchases')
            ->select('shipping_cost')
            ->where('id', $sid)
            ->first();
		$shipping_cost = $purchase->shipping_cost ?? 0;
		
		$sales_values = DB::table('purchase_values')
								->select(DB::raw('purchase_values.*'))
								->where('sid', '=', $sid)
								->get();

		$array = array();
		foreach($sales_values as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['sid'] = $val->sid;
			$array[$val->id]['prod_id'] = $val->prod_id;
			$array[$val->id]['quantity'] = $val->quantity;
			$array[$val->id]['rate'] = $val->rate;
			$array[$val->id]['disc'] = $val->disc;
			$array[$val->id]['disc_type'] = $val->disc_type;
			$array[$val->id]['disc_amt'] = $val->disc_amt;
			$array[$val->id]['tax_amt'] = $val->tax_amt;
			$array[$val->id]['amount'] = $val->amount;
			$array[$val->id]['tax_type'] = $val->tax_type;
			$array[$val->id]['gst_trans'] = $val->gst_trans;
			$array[$val->id]['shipping_cost'] = $shipping_cost;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				$array[$val->id]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$val->id]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$val->id]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
				$array[$val->id]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$val->id]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
			}else{
				$array[$val->id]['item_name'] = "";
				$array[$val->id]['sac_code'] = "";
				$array[$val->id]['hsn_code'] = "";
				$array[$val->id]['base_unit'] = "";
				$array[$val->id]['sec_unit'] = "";
			}
		}
		$sales_values = json_decode(json_encode($array));
		return $sales_values;
	}


    public function edit_purchase_invoice($sId)  {

		if(Auth::user()->u_type ==1){
			return redirect('/purchase-invoice');
		}
		$uid = currentOwnerId();
		checkCoreAccess('Biz Operations');
		$sId = base64_decode($sId);
		$sales = DB::table('purchases')
								->where('id', '=', $sId)
								->get();
		$sales = $sales[0];
		$totalInvAmount = DB::table('purchases')
							->join('purchase_values', 'purchase_values.sid', '=', 'purchases.id')
							->where('purchases.id', $sId)
							->where('purchases.added_by', $uid)
							->selectRaw('
								SUM(
									COALESCE(purchase_values.amount,0)
								  + COALESCE(purchase_values.tax_amt,0)
								  - COALESCE(purchase_values.disc_amt,0)
								) AS total_invoice_amount
							')
							->first();
		$products = DB::table('products')
								->select(DB::raw('products.id,products.item_name'))
								->where('added_by', '=', currentOwnerId())
								->get();
        $compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,gst_no,comp_bill_pin,comp_bill_addone,comp_bill_addtwo,comp_bill_name,comp_bill_mobile_no,comp_bill_state,comp_bill_city'))
								->where('company_profiles.userId','=',currentOwnerId())
								->get();
		$custData = DB::table('vendors')
								->select(DB::raw('vendors.*'))
								->where('vendors.userId','=',currentOwnerId())
								->where('vendors.status','=',1)
								->get();
		

            $comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
            $comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
            $comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
            $gst_no= isset($compData[0]->gst_no)?$compData[0]->gst_no:"";
            $comp_pan_no= isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
            $comp_bill_pin= isset($compData[0]->comp_bill_pin)?$compData[0]->comp_bill_pin:"";
            $comp_bill_addone= isset($compData[0]->comp_bill_addone)?$compData[0]->comp_bill_addone:"";
            $comp_bill_addtwo= isset($compData[0]->comp_bill_addtwo)?$compData[0]->comp_bill_addtwo:"";
            $comp_bill_name= isset($compData[0]->comp_bill_name)?$compData[0]->comp_bill_name:"";
            $comp_bill_mobile_no= isset($compData[0]->comp_bill_mobile_no)?$compData[0]->comp_bill_mobile_no:"";
            $comp_bill_state=isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:"";
            $comp_bill_city=isset($compData[0]->comp_bill_city)?$compData[0]->comp_bill_city:"";
                            //echo "<pre>"; print_r($compData);exit;
            $countries = Country::where('id', '>', '0')->get();
            $states_bill = State::where('id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
             $cities_bill = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();

            $states_ship = State::where('country_id', '=', isset($sales->ship_country)?$sales->ship_country:0)->get();
             $cities_ship = City::where('state_id', '=', isset($sales->ship_state)?$sales->ship_state:0)->get();

             $states_seller = State::where('country_id', '=', isset($sales->seller_country)?$sales->seller_country:0)->get();
              $cities_seller = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
		//echo "<pre>";print_r($states_bill);exit;

		$sales_values = $this->items_purchase_list($sId);
		$totalInvoiceAmount = $totalInvAmount->total_invoice_amount ?? 0;
		//echo "<pre>";print_r($sales);exit;
		return view('User.edit-purchase-invoice')->with([
			'comp_name'=>$comp_name,
			'comp_phone'=>$comp_phone,
			'comp_email'=>$comp_email,
            'gst_no'=>$gst_no,
			'comp_pan_no'=>$comp_pan_no,
            'comp_bill_name'=>$comp_bill_name,
            'comp_bill_mobile_no'=>$comp_bill_mobile_no,
			'comp_bill_addone'=>$comp_bill_addone,
			'comp_bill_addtwo'=>$comp_bill_addtwo,
            'comp_bill_pin'=>$comp_bill_pin,
            'comp_bill_state'=>$comp_bill_state,
            'comp_bill_city'=>$comp_bill_city,
			'products' => $products,
			'sales' => $sales,
			'totalInvoiceAmount' => $totalInvoiceAmount,
			'other_payment' => $sales->other_payment,
			'sales_values' => $sales_values,
			'countries'=>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'states_ship'=>$states_ship,
			'cities_ship'=>$cities_ship,
			'states_seller'=>$states_seller,
			'cities_seller'=>$cities_seller,
			'custData'=>$custData,
			'sId' => $sId

		]);
    }

    protected function validator(array $data)
    {
			return Validator::make($data, [
				'inv_num' => 'required',
				//'inv_name' => 'required',
				'inv_date' => 'required',

			]);

    }

	public function view_purchase_invoice($sId)  {
		
		$sId = base64_decode($sId);
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		checkCoreAccess('Biz Operations');
		$sales = DB::table('purchases')
								->where('id', '=', $sId)
								->get();
		$sales = $sales[0];
		$products = DB::table('products')
								->select(DB::raw('products.id,products.item_name'))
								->where('added_by', '=', $uid)
								->get();
        $compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,gst_no,comp_bill_pin,comp_bill_addone,comp_bill_addtwo,comp_bill_name,comp_bill_mobile_no,comp_bill_state,comp_bill_city'))
								->where('company_profiles.userId','=',$uid)
								->get();
		$custData = DB::table('vendors')
								->select(DB::raw('vendors.*'))
								->where('vendors.userId','=',$uid)
								->where('vendors.status','=',1)
								->get();

            $comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
            $comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
            $comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
            $gst_no= isset($compData[0]->gst_no)?$compData[0]->gst_no:"";
            $comp_pan_no= isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
            $comp_bill_pin= isset($compData[0]->comp_bill_pin)?$compData[0]->comp_bill_pin:"";
            $comp_bill_addone= isset($compData[0]->comp_bill_addone)?$compData[0]->comp_bill_addone:"";
            $comp_bill_addtwo= isset($compData[0]->comp_bill_addtwo)?$compData[0]->comp_bill_addtwo:"";
            $comp_bill_name= isset($compData[0]->comp_bill_name)?$compData[0]->comp_bill_name:"";
            $comp_bill_mobile_no= isset($compData[0]->comp_bill_mobile_no)?$compData[0]->comp_bill_mobile_no:"";
            $comp_bill_state=isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:"";
            $comp_bill_city=isset($compData[0]->comp_bill_city)?$compData[0]->comp_bill_city:"";
                            //echo "<pre>"; print_r($compData);exit;
            $countries = Country::where('id', '>', '0')->get();
            $states_bill = State::where('id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
             $cities_bill = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();

            $states_ship = State::where('country_id', '=', isset($sales->ship_country)?$sales->ship_country:0)->get();
             $cities_ship = City::where('state_id', '=', isset($sales->ship_state)?$sales->ship_state:0)->get();

             $states_seller = State::where('country_id', '=', isset($sales->seller_country)?$sales->seller_country:0)->get();
              $cities_seller = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
		//echo "<pre>";print_r($states_bill);exit;

		$sales_values = $this->items_purchase_list($sId);
		//echo "<pre>";print_r($sales);exit;
		return view('User.view-purchase-invoice')->with([
			'comp_name'=>$comp_name,
			'comp_phone'=>$comp_phone,
			'comp_email'=>$comp_email,
            'gst_no'=>$gst_no,
			'comp_pan_no'=>$comp_pan_no,
            'comp_bill_name'=>$comp_bill_name,
            'comp_bill_mobile_no'=>$comp_bill_mobile_no,
			'comp_bill_addone'=>$comp_bill_addone,
			'comp_bill_addtwo'=>$comp_bill_addtwo,
            'comp_bill_pin'=>$comp_bill_pin,
            'comp_bill_state'=>$comp_bill_state,
            'comp_bill_city'=>$comp_bill_city,
			'products' => $products,
			'sales' => $sales,
			'other_payment' => $sales->other_payment,
			'sales_values' => $sales_values,
			'countries'=>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'states_ship'=>$states_ship,
			'cities_ship'=>$cities_ship,
			'states_seller'=>$states_seller,
			'cities_seller'=>$cities_seller,
			'custData'=>$custData,
			'sId' => $sId

		]);
    }

    public function update_purchase_invoice(Request $request)  {

		//echo "<pre>";print_r($request->all());exit('hello');
		$sId = $request->id;

		$validation = $this->validator($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update project
			$update = DB::table('purchases')
					->where('id', $sId)
					->update(
						 array(
								'inv_num' => $request->inv_num,
								'inv_date' => $request->inv_date,
								'seller_name' => $request->seller_name,
								'seller_contact' => $request->seller_contact,
								'seller_email' => $request->seller_email,
								'seller_pan' => $request->seller_pan,
								'seller_gst' => isset($request->seller_gst)?$request->seller_gst:"",
								'seller_addone' => $request->seller_addone,
								'seller_addtwo' => isset($request->seller_addtwo)?$request->seller_addtwo:"",
								'seller_country' => $request->seller_country,
								'seller_state' => $request->seller_state,
								'seller_city' => $request->seller_city,
								'seller_pin' => $request->seller_pin,
						 )
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
			//end update item

		}
    }
	public function fetchPurchaseItem(Request $request)
	{
		$sid = $request->id;
		$purchase_values = DB::table('purchase_values')
								->select(DB::raw('purchase_values.*'))
								->where('purchase_values.id', '=', $sid)
								->get();

		$array = array();
		foreach($purchase_values as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['sid'] = $val->sid;
			$array[$val->id]['prod_id'] = $val->prod_id;
			$array[$val->id]['quantity'] = $val->quantity;
			$array[$val->id]['rate'] = $val->rate;
			$array[$val->id]['disc'] = $val->disc;
			$array[$val->id]['disc_type'] = $val->disc_type;
			$array[$val->id]['disc_amt'] = $val->disc_amt;
			$array[$val->id]['tax_amt'] = $val->tax_amt;
			$array[$val->id]['amount'] = $val->amount;
			$array[$val->id]['tax_type'] = $val->tax_type;
			$array[$val->id]['gst_rate'] = $val->gst_rate;
			$array[$val->id]['billing_type'] = $val->billing_type;
			$array[$val->id]['prod_gov_fee'] = $val->prod_gov_fee;
			$array[$val->id]['gst_trans'] = $val->gst_trans;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				$array[$val->id]['item_type'] = isset($item[0]->item_type)?$item[0]->item_type:"";
				$array[$val->id]['item_name'] = isset($item[0]->item_name)?$item[0]->item_name:"";
				$array[$val->id]['disc_sell'] = isset($item[0]->item_name)?$item[0]->disc_sell:"";
				$array[$val->id]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$val->id]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
				$array[$val->id]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$val->id]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
			}else{
				$array[$val->id]['item_type'] = "";
				$array[$val->id]['item_name'] = "";
				$array[$val->id]['disc_sell'] = "";
				$array[$val->id]['sac_code'] = "";
				$array[$val->id]['hsn_code'] = "";
				$array[$val->id]['base_unit'] = "";
				$array[$val->id]['sec_unit'] = "";
			}
		}
		$purchase_values = (json_encode($array));
		//echo "<pre>";print_r($purchase_values);exit;
		return $purchase_values;
	}

	public function update_seller_details(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;


			$update = DB::table('purchases')
					->where('id', $sId)
					->update(
						array(
								'inv_name' => $request->inv_name,
								'add_type' => $request->add_type,
								'cust_bill_gstno' => $request->cust_bill_gstno,
								'cust_bill_contact' => $request->cust_bill_contact,
								'cust_bill_mobilno' => $request->cust_bill_mobilno,
								'cust_bill_designa' => $request->cust_bill_designa,
								// 'bill_name' => $request->cust_bill_name,
								'bill_addone' => $request->bill_addone,
								'bill_addtwo' => $request->bill_addtwo,
								'bill_country' => $request->cust_bill_country,
								'bill_state' => $request->cust_bill_state,
								'bill_city' => $request->cust_bill_city,
								'bill_pin' => $request->cust_bill_pin,


								'cust_ship_gstno' => $request->cust_ship_gstno,
								'cust_ship_contact' => $request->cust_ship_contact,
								'cust_ship_mobilno' => $request->cust_ship_mobilno,
								'cust_ship_designa' => $request->cust_ship_designa,
								// 'ship_name' => $request->cust_ship_name,
								'ship_addone' => $request->cust_ship_addone,
								'ship_addtwo' => $request->cust_ship_addtwo,
								'ship_country' => $request->cust_ship_country,
								'ship_state' => $request->cust_ship_state,
								'ship_city' => $request->cust_ship_city,
								'ship_pin' => $request->cust_ship_pin,




						)
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);

    }

	public function update_purchase_item_rate(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$sid = $request->sid;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$sales_data = DB::table('purchase_values')
								->select(DB::raw('purchase_values.sid,purchase_values.quantity,purchase_values.disc,purchase_values.disc_type,purchase_values.gst_rate'))
								->where('id', '=', $request->id)
								->get();

		$productRate = $request->rate;
		$gst_rate = $sales_data[0]->gst_rate;
		$disc = $sales_data[0]->disc;
		$disc_type = $sales_data[0]->disc_type;
		$amount = $productRate * $sales_data[0]->quantity;
		if($disc_type == "percentage")
		{
			$disc_amt = (($amount*$disc)/100);
		}else{
			$disc_amt = $disc;
		}
		$amount = ($amount - $disc_amt);
		$tax_amt = ($amount*$gst_rate)/100;

		$update = DB::table('purchase_values')
				->where('id', $id)
				->update(
					 array(
							'rate' => $productRate,
							'disc_amt' => $disc_amt,
							'tax_amt' => $tax_amt,
							'amount' => $amount
					 )
				);

		$sales_values = $this->items_purchase_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.ajax-purchase-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);
    }

	public function update_purchase_invoice_final(Request $request)  {

		//print_r($_FILES);
		$signature_name = $request->signature_name;
		$special_discount_amount = $request->discount_amount ?? 0;
		//$tds_applicable = $request->tdsApplicable;
		//$tds_percentage = $request->tdsPercentage;
		//$tds_amount = $request->tds_amount;
		//$tds_id = $request->tds_id;

		if($file = $request->hasFile('signature')) {
			$file = $request->file('signature') ;

			$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
			$destinationPath1 = public_path().'/uploads/invoice-signature' ;

			$file->move($destinationPath1,$fileName1);
			$signature = $fileName1 ;

			//Update file
			$update = DB::table('purchases')
			->where('id', $request->id)
			->update(
				 array(
						'signature' => $signature,
						'signature_name' => $signature_name,
						'special_discount_amount' => $special_discount_amount,
						//'tds_applicable' => $tds_applicable,
						//'tds_percentage' => $tds_percentage,
						//'tds_amount' => $tds_amount,
						//'tds_id' => $tds_id,
						'status' => 1,

				 )
			);
		}else{
			$update = DB::table('purchases')
				->where('id', $request->id)
				->update(
					 array(
							'signature_name' => $signature_name,
							'special_discount_amount' => $special_discount_amount,
							//'tds_applicable' => $tds_applicable,
							//'tds_percentage' => $tds_percentage,
							//'tds_amount' => $tds_amount,
							//'tds_id' => $tds_id,
							'status' => 1,

					 )
				);
		}

		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/purchase-invoices'),
			'message' => 'Record successfully updated',
		);
		return response()->json($msg);
	}

	protected function validatorOther(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
		if($data['mode_of_pay'] == 'IMPS' || $data['mode_of_pay'] == 'RTGS' || $data['mode_of_pay'] == 'NEFT'){
			$imps_rtgs_neft = "required";
		}else{
			$imps_rtgs_neft = "";
		}
		if($data['mode_of_pay'] == 'UPI'){
			$upi = "required";
		}else{
			$upi = "";
		}
		if($data['mode_of_pay'] == 'CARD'){
			$card = "required";
		}else{
			$card = "";
		}
		if($data['disp_through'] == 'Other'){
			$other_dispa_det = "required";
		}else{
			$other_dispa_det = "";
		}
		return Validator::make($data, [
			'mode_of_pay' => 'required',
			'pay_status' => 'required',
			//'total_amount' => 'numeric',
			//'advance_amount' => 'numeric',
			//'due_amount' => 'numeric',
			//'order_date' => 'required',
			//'disp_through' => 'required',
			'other_dispa_det' => $other_dispa_det,
			//'bankname' => $imps_rtgs_neft,
			//'ifsc_code' => $imps_rtgs_neft,
			//'bank_ac' => $imps_rtgs_neft,
			//'ac_type' => $imps_rtgs_neft,
			//'upi_holder_name' => $upi,
			//'upi_id' => $upi,
			//'card_type' => $card,
			//'card_no' => $card,
			//'card_bank_name' => $card,
		]);

    }

	public function update_purchase_other(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;

		$validation = $this->validatorOther($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{

			//start update image_sign
			$arr = [];
			if ($request->totalImages > 0) {
				for($i=0; $i < $request->totalImages; $i++){
					if ($request->hasFile('image_sign' . $i)) {
							$file = $request->file('image_sign' . $i);
							$fileName = date("YmdHis") . '-' . $file->getClientOriginalName() ;
							$destinationPath1 = public_path().'/uploads/invoice-signature' ;
							$file->move($destinationPath1,$fileName);
							$arr[] = $fileName;

						$image = implode(",", $arr);
					}
				}
				$updateImage = DB::table('purchases')
							->where('id', $sId)
							->update(
								 array(
										'image_sign' => $image,
										'status' => 1,
								 )
							);
			}
			//end update image_sign
			
			//start get old records
			$oldRec = DB::table('purchases')
				->where('id', $sId)
				->first();
			//end get old records
			
			$tdsApplicable = $request->tds_applicable === 'yes';
			$payStatus = $oldRec->pay_status ?? '';
			$totalAmount   = $request->total_amount ?? 0;			
			$dueAmount =  (float) ($oldRec->due_amount ?? 0);
			$advanceAmount  = (float) ($oldRec->advance_amount ?? 0);
			$adjustedAmount = (float) ($oldRec->adjusted_amount ?? 0);
			if ($payStatus === 'Due') 
			{
				$payStatus = 'Due';
				$dueAmount =  0;
				$advanceAmount  = 0;
				$adjustedAmount  = 0;
			}

			$update = DB::table('purchases')
					->where('id', $sId)
					->update(
						array(	
								'mode_of_pay' => $request->mode_of_pay,
								'other_payment' => $request->other_payment,
								'pay_status' => $payStatus,
								'total_amount' => isset($request->total_amount)?$request->total_amount:0,
								'advance_amount' => $advanceAmount,
								'due_amount' => $dueAmount,
								'adjusted_amount' => $adjustedAmount,
								'seller_orderno' => isset($request->seller_orderno)?$request->seller_orderno:"",
								'order_date' => !empty($request->order_date) ? $request->order_date : null,
								'buyer_refno' => isset($request->buyer_refno)?$request->buyer_refno:"",
								'other_refno' => isset($request->other_refno)?$request->other_refno:"",
								'dispa_docno_one' => isset($request->dispa_docno_one)?$request->dispa_docno_one:"",
								'disp_through' => $request->disp_through,
								'other_dispa_det' => isset($request->other_dispa_det)?$request->other_dispa_det:"",
								'terms_delivery' => isset($request->terms_delivery)?$request->terms_delivery:"",
								'status' => 1,
						)
					);
			$userId = currentOwnerId();
			$this->journalEntry($sId,$userId); //Journal Entry
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/purchase-invoices'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
		}
    }

	public function delPurchaseItem(Request $request)
    {
        $delPurchaseItem = DB::table('purchase_values')->where('id', $request->id)->delete();
		$sales_values = $this->items_purchase_list($request->sid);
		$uid = currentOwnerId();
		$sid = $request->sid;
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.ajax-purchase-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);

    }
	public function update_purchase_item_quantity(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$sid = $request->sid;
		$uid = currentOwnerId();

		$sales_data = DB::table('purchase_values')
								->select(DB::raw('purchase_values.sid,purchase_values.rate,purchase_values.disc,purchase_values.disc_type,purchase_values.gst_rate'))
								->where('id', '=', $request->id)
								->get();
		$productRate = $sales_data[0]->rate;
		$gst_rate = $sales_data[0]->gst_rate;
		$disc = $sales_data[0]->disc;
		$disc_type = $sales_data[0]->disc_type;
		$amount = $productRate * $request->quantity;
		if($disc_type == "percentage")
		{
			$disc_amt = (($amount*$disc)/100);
		}else{
			$disc_amt = $disc;
		}
		$amount = ($amount - $disc_amt);
		$tax_amt = ($amount*$gst_rate)/100;

		$update = DB::table('purchase_values')
				->where('id', $id)
				->update(
					array(
							'quantity' => $request->quantity,
							//'rate' => $rate,
							'disc_amt' => $disc_amt,
							'tax_amt' => $tax_amt,
							'amount' => $amount
					)
				);

		$sales_values = $this->items_purchase_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.ajax-purchase-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);
    }
	public function delInvoicePurchase(Request $request)
    {
		$data = DB::table('purchases')->where('id', $request->id)->first();
		$oldData = [
					'record' => [
						'inv_num'  => $data->inv_num ?? null,
						'inv_date' => $data->inv_date ?? null,
					]
				];
				
        $delInvoice = DB::table('purchases')->where('id', $request->id)->delete();
        $delInvoiceItemValue = DB::table('purchase_values')->where('sid', $request->id)->delete();
		$delJournalRec = DB::table('journals')
								->where('autoId', $request->id)
								->where('source', 'Purchase')->delete();
		$delPaymentRec = DB::table('payment_vouchers')
							->where('f_id', $request->id)
							->where('source', 'Purchase')->delete();
		// Delete Shipping Expense
		if($data){
			DB::table('expenses')
				->where('exp_invno', $data->inv_num)
				->where('expense_cat', 'indirect')
				->where('expense_type', 'travel_conveyance')
				->delete();
		}
		if($delInvoice){
			//AUDIT LOG ENTRY
            AuditLogger::logEntry(
                action: 'delete',
                module: 'Purchase Invoice',
                description: "Purchase Invoice deleted: {$data->inv_num}",
                oldData: $oldData,
                newData: null
            );
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/purchase-invoices'),
				'message' => 'Record deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/purchase-invoices'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }
	
	public function poToPurchase(Request $request)
    {
		$userId = currentOwnerId();  //_token
        $request->validate([
            'po_ref_num' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $puos = DB::table('puos')
                ->where('inv_num', $request->po_ref_num)
				->where('added_by', $userId)
                ->first();
			//echo "<pre>";print_r($puos);exit;
            if (!$puos) {
                return response()->json([
                    'status' => false,
                    'message' => 'PO not found'
                ]);
            }

            // Convert object → array and remove PK
            $purchaseData = (array) $puos;

            unset($purchaseData['id']);       // remove auto id
            unset($purchaseData['created_at']);
            unset($purchaseData['updated_at']);

            // Change required fields
			$propId = $purchaseData['propId'];
			if(!empty($propId)) {
				$invoiceNo = $this->create_purchase_invoice_number_proprietorship($propId,$userId);
			} else {
				$invoiceNo = $this->create_purchase_invoice_number($userId);
			}
            $purchaseData['inv_num']  = $invoiceNo;
            //$proformaData['inv_date']  = date('Y-m-d');
            $purchaseData['added_by']  = $userId;
            $purchaseData['status']  = 1;

            // Insert into purchases
            $purchaseId = DB::table('purchases')->insertGetId($purchaseData);

            // Fetch puos line items
            $purchaseItems = DB::table('puo_values')
                ->where('sid', $puos->id)
                ->get();

            // Insert into purchase_values
            foreach ($purchaseItems as $item) {

                $itemData = (array) $item;
                unset($itemData['id']);   // VERY IMPORTANT
				unset($itemData['sid']);  // remove sales id
                $itemData['uid'] = $userId;
                $itemData['sid'] = $purchaseId;
                DB::table('purchase_values')->insert($itemData);
            }
			$this->journalEntry($purchaseId,$userId); //Journal Entry
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Purchase invoice created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

	public function PurchaseCreditDebit(request $request)
    {
		$title = 'Purchase Credit Dabit Notessss';
		$userId = currentOwnerId();
		checkCoreAccess('Purchase');

		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = getAccessCompanyId($request);
			
		}

		if(Auth::user()->u_type ==1){ //ca
			$sales =  DB::table('voucher_purchases')
							->select(DB::raw('voucher_purchases.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'voucher_purchases.added_by', '=', 'company_profiles.userId')
							->where('voucher_purchases.added_by', '=', $userId)
							->orderBy('voucher_purchases.id', 'DESC')->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca employee
			$sales =  DB::table('voucher_purchases')
							->select(DB::raw('voucher_purchases.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'voucher_purchases.added_by', '=', 'company_profiles.userId')
							->where('voucher_purchases.added_by', '=', $userId)
							->orderBy('voucher_purchases.id', 'DESC')->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$sales =  DB::table('voucher_purchases')
							->select(DB::raw('voucher_purchases.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'voucher_purchases.added_by', '=', 'company_profiles.userId')
							->where('voucher_purchases.added_by', '=', $userId)
							->orderBy('voucher_purchases.id', 'DESC')->paginate(10);
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$sales =  DB::table('voucher_purchases')
							->select(DB::raw('voucher_purchases.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'voucher_purchases.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$sales_pagination = $sales;

		$array = array();
		foreach($sales as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['inv_num'] = $val->inv_num;
			$array[$val->id]['inv_date'] = $val->inv_date;
			$customerName =  DB::table('customers')
							->select(DB::raw('customers.cust_name,customers.cust_phone'))
							->where('id', '=', $val->v_name)
							->get();
			$array[$val->id]['cust_name'] = isset($customerName[0]->cust_name)?$customerName[0]->cust_name:"";
			$array[$val->id]['cust_phone'] = isset($customerName[0]->cust_phone)?$customerName[0]->cust_phone:"";
			$array[$val->id]['v_num'] = $val->v_num;
			$array[$val->id]['note_type'] = $val->note_type;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['is_paid'] = $val->is_paid;
			$array[$val->id]['total_amt'] = $val->total_amt;
			$array[$val->id]['inv_number'] = $val->inv_number;
			$array[$val->id]['adjusted_amount'] = $val->adjusted_amount;
		}
		$sales = json_decode(json_encode($array));

		//echo "<pre>"; print_r($sales);exit;
        return view('User.purchase-credit-debit')->with([
			'title' =>$title,
			'sales'=>$sales,
			'sales_pagination' =>$sales_pagination,
		]);
    }

    public function AddPurchaseCreditDebit()
    {
		checkCoreAccess('Biz Operations');
		$inv_voucher = DB::table('purchases')
								->where('added_by','=',currentOwnerId())
								->select('id', 'inv_num')
								->get();

		$vNo = DB::table('voucher_purchases')
								->select(DB::raw('MAX(id) as id'))
								->get();
		$vNo = isset($vNo[0]->id)?$vNo[0]->id:0;
		$vNo = Helper::invoice_num($vNo+1,7,"PN-");
		$userId = currentOwnerId();
		$compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,gst_no,comp_email,comp_pan_no'))
								->where('company_profiles.userId','=',$userId)
								->get();
		$vendorData = DB::table('vendors')
								->select(DB::raw('vendors.*'))
								->where('vendors.userId','=',currentOwnerId())
								->where('vendors.status','=',1)
								->get();
		$purposes_of_tds = DB::table('purposes_of_tds')
								->get();

		$comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
		$comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
		$comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
		$comp_pan = isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
		$comp_gst = isset($compData[0]->gst_no)?$compData[0]->gst_no:"";

		$countries = Country::where('id', '>', '0')->get();

		$states_bill = State::where('country_id', '=', isset($compDetails->comp_bill_country)?$compDetails->comp_bill_country:0)->get();
		$cities_bill = City::where('state_id', '=', isset($compDetails->comp_bill_state)?$compDetails->comp_bill_state:0)->get();

		$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country)?$compDetails->comp_ship_country:0)->get();
		$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state)?$compDetails->comp_ship_state:0)->get();

		//echo "<pre>"; print_r($inv_voucher); exit;

        return view('User.add-purchase-credit-debit')->with([
			'vNo' => $vNo,
			'comp_name' => $comp_name,
			'comp_phone' => $comp_phone,
			'comp_email'=>$comp_email,
			'comp_gst' =>$comp_gst,
			'comp_pan' =>$comp_pan,
			'countries' =>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'states_ship'=>$states_ship,
			'cities_ship'=>$cities_ship,
			'vendorData' =>$vendorData,
			'inv_number' => $inv_voucher,
			'purposes_of_tds' => $purposes_of_tds

        ]);
    }

	public function fetchParchaseDetails(Request $request)
	{
		$invoiceNumber = $request->input('inv_num');

		$purchaseDetails = DB::table('purchases')
						->leftJoin('countries', 'countries.id', '=', 'purchases.seller_country')
						->leftJoin('states', 'states.id', '=', 'purchases.seller_state')
						->leftJoin('cities', 'cities.id', '=', 'purchases.seller_city')
						->select(
							'purchases.*',
							'countries.name as country_name',
							'states.name as state_name',
							'cities.name as city_name'
						)
						->where('purchases.inv_num', $invoiceNumber)
						->first();

		if ($purchaseDetails) {
			return response()->json([
				'status' => 'success',
				'data' => $purchaseDetails,
			]);
		}
		 else {
			return response()->json([
				'status' => 'error',
				'message' => 'Invoice not found.',
			]);
		}
	}

	public function fetchVendorDetails(Request $request)
	{
		$vendorId = $request->input('id');

		$vendorDetails = DB::table('vendors')
						->leftJoin('countries', 'countries.id', '=', 'vendors.billing_country')
						->leftJoin('states', 'states.id', '=', 'vendors.billing_state')
						->leftJoin('cities', 'cities.id', '=', 'vendors.billing_city')
						->select(
							'vendors.*',
							'countries.name as country_name',
							'states.name as state_name',
							'cities.name as city_name'
						)
						->where('vendors.id', $vendorId)
						->first();

		if ($vendorDetails) {
			return response()->json([
				'status' => 'success',
				'data' => $vendorDetails,
			]);
		}
		 else {
			return response()->json([
				'status' => 'error',
				'message' => 'Invoice not found.',
			]);
		}


	}

	protected function validatorPurchaseCredit(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'inv_num' => 'required',
				'inv_date' => 'required',
				'cust_state' => 'required',
				'cust_city' => 'required',
				'cust_pin' => 'required',
				'v_name' => 'required',
				'note_type' => 'required',
				'note_date' => 'required',
				'reason_issuance' => 'required',
				'v_num' => 'required',
				'challan_no' => 'required',
				'challan_date' => 'required',
			]);

    }

	protected function createPurchaseCredit(array $data)
    {
		//echo "<pre>";print_r($data);exit;

		$invoiceNo = DB::table('voucher_purchases')
								->select(DB::raw('MAX(id) as id'))
								->get();
		//$vNo = isset($vNo[0]->id)?$vNo[0]->id:0;
		//$vNo = Helper::invoice_num($vNo+1,7,"PN-");
		$invoiceNo = isset($invoiceNo[0]->id)?$invoiceNo[0]->id:0;
		$invoiceNo = Helper::invoice_num($invoiceNo+1,7,"PN-");
        return Voucher_purchases::create([
            'added_by' => currentOwnerId(),
			'inv_num' => $invoiceNo,
			'inv_number' => $data['inv_num'],			
			'inv_date' => $data['inv_date'],
			'seller_name' => $data['seller_name'],
			'seller_contact' => $data['seller_contact'],
			'seller_email' => $data['seller_email'],
			'seller_addone' => isset($data['bill_addone']) ? $data['bill_addone'] : "",
			'seller_addtwo' => isset($data['bill_addtwo']) ? $data['bill_addtwo'] : "",
			'seller_country' => 101,
			'seller_state' => $data['cust_state'],
			'seller_city' => $data['cust_city'],
			'seller_pin' => $data['cust_pin'],
			'v_name' => $data['v_name'],
			'note_type' => $data['note_type'],
			'note_date' => $data['note_date'],
			'reason_issuance' => $data['reason_issuance'],

			'v_num' => $data['v_num'],
			'challan_no' => $data['challan_no'],
			'challan_date' => $data['challan_date'],
			'prodservname'        => $data['prodservname'] ?? null,
			'hsn_sac_code'        => $data['hsn_sac_code'] ?? null,
			'gst_rate'            => $data['gst_rate'] ?? 0,
			'taxable_value'       => $data['taxable_value'] ?? 0,
			'cgst_amount'         => $data['cgst_amount'] ?? 0,
			'sgst_amount'         => $data['sgst_amount'] ?? 0,
			'igst_amount'         => $data['igst_amount'] ?? 0,
			'total_amt'         => $data['total_amt'] ?? 0,
			'qty_return_adjusted' => $data['qty_return_adjusted'] ?? "",
			'rate_unit_price'     => $data['rate_unit_price'] ?? 0,
			'discount'            => $data['discount'] ?? 0,
			'return_status'       => $data['return_status'] ?? null,
			'transporter_name' => $data['transporter_name'] ?? null,
			'term_condition' => isset($data['term_condition']) ? $data['term_condition'] : "",
			'created_at' => date('Y-m-d H:i:s'),


        ]);
    }

	public function save_purchase_invoice_creditdebit(Request $request)  {

		//echo "<pre>";print_r($request->file('prod_image'));exit;
		//$input = Input::all();
		//dd($input);
		$validation = $this->validatorPurchaseCredit($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertSales = $this->createPurchaseCredit($request->all());
			$sId = DB::getPdo()->lastInsertId();

			if($file = $request->hasFile('voucher_doc')) {
				$file = $request->file('voucher_doc') ;
				$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
				$destinationPath1 = public_path().'/uploads/purchase-credit-debit' ;
				$file->move($destinationPath1,$fileName1);
				$voucher_doc = $fileName1 ;

				$update = DB::table('voucher_purchases')
				->where('id', $sId)
				->update(
					 array(
						'voucher_doc' => $voucher_doc,
					 )
				);
			}
			
			$userId = currentOwnerId();
			$this->journalEntryVoucher($sId,$userId); //Journal Entry

			if ($insertSales){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					//'redirect' => url('/edit-purchase-credit-debit/'.base64_encode($sId)),
					'redirect' => url('/purchase-credit-debit'),
					'message' => 'Record added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Record add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function update_purchase_invoice_creditdebit(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;

		$validation = $this->validatorPurchaseCredit($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update project
			if($file = $request->hasFile('voucher_doc')) {
				$file = $request->file('voucher_doc') ;
				$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName() ;
				$destinationPath1 = public_path().'/uploads/purchase-credit-debit' ;
				$file->move($destinationPath1,$fileName1);
				$voucher_doc = $fileName1 ;

				$update = DB::table('voucher_purchases')
				->where('id', $sId)
				->update(
					 array(
						'voucher_doc' => $voucher_doc,
					 )
				);
			}
			if($request->reason_issuance == 'other'){
				$otherIssuance = $request->otherIssuance;
			}else{
				$otherIssuance = "";
			}
			$update = DB::table('voucher_purchases')
					->where('id', $sId)
					->update(
						 array(			
								'inv_number' => $request->inv_num,
								'inv_date' => $request->inv_date,
								'seller_name' => $request->seller_name,
								'seller_contact' => $request->seller_contact,
								'seller_email' => $request->seller_email,
								'seller_addone' => $request->bill_addone,
								'seller_addtwo' => isset($request->bill_addtwo) ? $request->bill_addtwo : "",
								'seller_country' => 101,
								'seller_state' => $request->cust_state,
								'seller_city' => $request->cust_city,
								'seller_pin' => $request->cust_pin,
								'v_name' => $request->v_name,
								'note_type' => $request->note_type,
								'note_date' => $request->note_date,
								'reason_issuance' => $request->reason_issuance,
								'otherIssuance' => $otherIssuance,

								'v_num'              => $request->v_num,
								'challan_no'         => $request->challan_no,
								'challan_date'       => $request->challan_date,

								'prodservname'       => $request->prodservname ?? null,
								'hsn_sac_code'       => $request->hsn_sac_code ?? null,
								'gst_rate'           => $request->gst_rate ?? 0,
								'taxable_value'      => $request->taxable_value ?? 0,
								'cgst_amount'        => $request->cgst_amount ?? 0,
								'sgst_amount'        => $request->sgst_amount ?? 0,
								'igst_amount'        => $request->igst_amount ?? 0,
								'total_amt'          => $request->total_amt ?? 0,
								'qty_return_adjusted'=> $request->qty_return_adjusted ?? null,
								'rate_unit_price'    => $request->rate_unit_price ?? 0,
								'discount'           => $request->discount ?? 0,
								'return_status'      => $request->return_status ?? null,
								'transporter_name'   => $request->transporter_name ?? null,
								'term_condition'     => $request->term_condition ?? null,
						 )
					);
					
			$userId = currentOwnerId();
			$this->journalEntryVoucher($sId,$userId); //Journal Entry
			
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/purchase-credit-debit'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
			//end update item

		}
    }

	public function edit_purchase_invoice_credit_debit($sId)  {

		checkCoreAccess('Biz Operations');
		$sId = base64_decode($sId);
		$sales = DB::table('voucher_purchases')
								->where('id', '=', $sId)
								->get();
		$sales = $sales[0];
		$products = DB::table('items')
								->select(DB::raw('items.id,items.item_name'))
								->where('added_by', '=', currentOwnerId())
								->get();
		$compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,gst_no,comp_email,comp_pan_no'))
								->where('company_profiles.userId','=',currentOwnerId())
								->get();
		$vendorData = DB::table('vendors')
								->select(DB::raw('vendors.*'))
								->where('vendors.userId','=',currentOwnerId())
								->where('vendors.status','=',1)
								->get();

		$purposes_of_tds = DB::table('purposes_of_tds')
								->get();

		$comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
		$comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
		$comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
		$comp_pan = isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
		$comp_gst = isset($compData[0]->gst_no)?$compData[0]->gst_no:"";

		//echo "<pre>";print_r($sales);exit;
		$countries = Country::where('id', '>', '0')->get();
		$states_seller = State::where('country_id', '=', isset($sales->seller_country)?$sales->seller_country:0)->get();
		$cities_seller = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
		//echo "<pre>";print_r($sales);exit;

		//$vouchers_values = $this->items_purchase_list_credit_debit($sId);
		return view('User.edit-purchase-credit-debit')->with([
			'products' => $products,
			'sales' => $sales,
			'vendorData'=>$vendorData,
			'countries' => $countries,
			'states_seller' => $states_seller,
			'cities_seller' => $cities_seller,
			'purposes_of_tds' => $purposes_of_tds,
			'comp_name' => $comp_name,
			'comp_phone' => $comp_phone,
			'comp_email'=>$comp_email,
			'comp_gst' =>$comp_gst,
			'comp_pan' =>$comp_pan,
			//'vouchers_values' => $vouchers_values,
			'sId' => $sId
		]);
    }

	public function view_purchase_invoice_credit_debit($sId)  {
		$sId = base64_decode($sId);
		$sales = DB::table('voucher_purchases')
								->where('id', '=', $sId)
								->get();
		$sales = $sales[0];
		$products = DB::table('items')
								->select(DB::raw('items.id,items.item_name'))
								->where('added_by', '=', currentOwnerId())
								->get();
		$compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,gst_no,comp_email,comp_pan_no'))
								->where('company_profiles.userId','=',currentOwnerId())
								->get();
		$vendorData = DB::table('vendors')
								->select(DB::raw('vendors.*'))
								->where('vendors.userId','=',currentOwnerId())
								->where('vendors.status','=',1)
								->get();

		$purposes_of_tds = DB::table('purposes_of_tds')
								->get();

		$comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
		$comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
		$comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
		$comp_pan = isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
		$comp_gst = isset($compData[0]->gst_no)?$compData[0]->gst_no:"";

		//echo "<pre>";print_r($sales);exit;
		$countries = Country::where('id', '>', '0')->get();
		$states_seller = State::where('country_id', '=', isset($sales->seller_country)?$sales->seller_country:0)->get();
		$cities_seller = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();

		//$vouchers_values = $this->items_purchase_list_credit_debit($sId);
		return view('User.view-purchase-credit-debit')->with([
			'products' => $products,
			'sales' => $sales,
			'vendorData'=>$vendorData,
			'countries' => $countries,
			'states_seller' => $states_seller,
			'cities_seller' => $cities_seller,
			'purposes_of_tds' => $purposes_of_tds,
			'comp_name' => $comp_name,
			'comp_phone' => $comp_phone,
			'comp_email'=>$comp_email,
			'comp_gst' =>$comp_gst,
			'comp_pan' =>$comp_pan,
			//'vouchers_values' => $vouchers_values,
			'sId' => $sId
		]);
    }
	//carriage-inward.blade
	public function delPurchaseCreditDebit(Request $request)
    {
		$data = DB::table('voucher_purchases')->where('id', $request->id)->first();
		$oldData = [
					'record' => [
						'inv_num'  => $data->inv_num ?? null,
						'inv_date' => $data->inv_date ?? null,
					]
				];
        $delInvoice = DB::table('voucher_purchases')->where('id', $request->id)->delete();
		$delJournalRec = DB::table('journals')
								->where('autoId', $request->id)
								->where('source', 'Purchase Voucher')->delete();

		if($delInvoice){
			//AUDIT LOG ENTRY
            AuditLogger::logEntry(
                action: 'delete',
                module: 'Purchase Credit & Debit',
                description: "Purchase Credit & Debit deleted: {$data->inv_num}",
                oldData: $oldData,
                newData: null
            );
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/purchase-credit-debit'),
				'message' => 'Record deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/purchase-credit-debit'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }


	protected function validatorcarriageIn(array $data)
	{

		return Validator::make($data, [
			//'inv_num' => 'required',
			//'inv_date' => 'required',
			'buyer_name' => 'required',
			'buyer_contact' => 'required',
			'buyer_email' => 'required',
			//'seller_pan' => 'required',
			'buyer_addone' => 'required',
			'buyer_addtwo' => 'required',
			'buyer_state' => 'required',
			'buyer_city' => 'required',
			//'seller_pin' => 'required',
			//'transport_type_other' => $transport_type_other,
		]);
	}

	protected function createcarriageIn(array $data)
	{
		//echo "<pre>";print_r($data);exit;
		return Carriageinwards::create([
			'added_by' => currentOwnerId(),
			'inv_num' => $data['inv_num'],
			'inv_date' => $data['inv_date'],

			'buyer_name' => $data['buyer_name'],
			'buyer_contact' => $data['buyer_contact'],
			'buyer_email' => $data['buyer_email'],
			'buyer_pan' => $data['buyer_pan'],
			'buyer_gst' => isset($data['buyer_gst']) ? $data['buyer_gst'] : "",
			'buyer_addone' => $data['buyer_addone'],
			'buyer_addtwo' => isset($data['buyer_addtwo']) ? $data['buyer_addtwo'] : "",
			//'seller_country' => $data['seller_country'],
			'buyer_state' => $data['buyer_state'],
			'buyer_city' => $data['buyer_city'],
			'buyer_pin' => isset($data['buyer_pin']) ? $data['buyer_pin'] : "",
			'vendor_name'=>$data['vendor_name'],
			'vendor_contact'=>$data['vendor_contact'],
			'vendor_email'=>$data['vendor_email'],
			'vendor_pan'=>$data['vendor_pan'],
			'vendor_gst'=>$data['vendor_gst'],
			'vendor_order_no'=>$data['vendor_order_no'],
			'vendor_dispatch_no'=>$data['vendor_dispatch_no'],
			'disp_through'=>$data['disp_through'],
			'other_dispa_det'=>isset($data['other_dispa_det']) ? $data['other_dispa_det'] : "",
			'terms_delivery'=>$data['terms_delivery'],
			'vendor_addone'=>$data['vendor_addone'],
			'vendor_addtwo'=>$data['vendor_addtwo'],
			'vendor_state'=>$data['vendor_state'],
			'vendor_city'=>$data['vendor_city'],
			'vendor_pin'=>$data['vendor_pin'],
			'other_quantity'=>isset($data['other_quantity']) ? $data['other_quantity'] : "",
			'other_transport'=>isset($data['other_transport']) ? $data['other_transport'] : "",
			'other_transport_cost'=>isset($data['other_transport_cost']) ? $data['other_transport_cost'] : "",
			'other_insurance_date'=>isset($data['other_insurance_date']) ? $data['other_insurance_date'] : "",
			'tdsApplicable'=>$data['tdsApplicable'],
			'tds_percentage'=>$data['tds_percentage'],
			'gstApplicable'=>$data['gstApplicable'],
			'other_hsn_sac_code'=>isset($data['other_hsn_sac_code']) ? $data['other_hsn_sac_code'] : "",
			'other_gst_rate'=>isset($data['other_gst_rate']) ? $data['other_gst_rate'] : "",
			'other_gst_mode'=>isset($data['other_gst_mode']) ? $data['other_gst_mode'] : "",
			'other_pay_date'=>isset($data['other_pay_date']) ? $data['other_pay_date'] : "",
			'other_mod_pay'=>isset($data['other_mod_pay']) ? $data['other_mod_pay'] : "",
			'other_pay_method'=>isset($data['other_pay_method']) ? $data['other_pay_method'] : "",
			'pay_status'=>$data['pay_status'],
			'other_total_amount'=>isset($data['other_total_amount']) ? $data['other_total_amount'] : "",
			'other_adv_amount'=>isset($data['other_adv_amount']) ? $data['other_adv_amount'] : "",
			'other_due_amount'=>isset($data['other_due_amount']) ? $data['other_due_amount'] : "",
			'other_refe_no'=>isset($data['other_refe_no']) ? $data['other_refe_no'] : "",
			'other_approve_by'=>isset($data['other_approve_by']) ? $data['other_approve_by'] : "",
			'other_term'=>isset($data['other_term']) ? $data['other_term'] : "",
			//'other_uplode_doc'=>$data['other_uplode_doc'],
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function Carriageinwards()
	{
		$title = 'Carriage Intward';
        $userId = currentOwnerId();

		$carrIns =  DB::table('carriageinwards')
							->select(DB::raw('carriageinwards.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'carriageinwards.added_by', '=', 'company_profiles.userId')
							//->leftJoin('ca_assigns', 'carriageouts.added_by', '=', 'ca_assigns.comp_id')
							//->where('ca_assigns.ca_id','=',$userId)
							->where('carriageinwards.added_by','=',$userId)
							->orderBy('created_at', 'DESC')->paginate(10);
		//echo "<pre>";print_r($carrIns);exit('kkk');
		$carrIns_pagination = $carrIns;
		return view('User.carriage-inward')->with([
			'title' =>$title,
			'carrIns'=>$carrIns,
			'carrIns_pagination' =>$carrIns_pagination,
		]);
	}

	public function AddCarriageinwards()
	{
		$inv_voucher = DB::table('purchases')
								->where('added_by','=',currentOwnerId())
								->select('id', 'inv_num')
								->get();

		$vNo = DB::table('voucher_purchases')
								->select(DB::raw('MAX(id) as id'))
								->get();
		$vNo = isset($vNo[0]->id)?$vNo[0]->id:0;
		$vNo = Helper::invoice_num($vNo+1,7,"PN-");
		$userId = currentOwnerId();
		$compData = DB::table('company_profiles')
		->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
		->where('company_profiles.userId', '=', $userId)
			->get();

		$vendorData = DB::table('vendors')
							->select(DB::raw('vendors.*'))
							->where('vendors.userId','=',currentOwnerId())
							->where('vendors.status','=',1)
							->get();
        //echo "<pre>"; print_r($vendorData); exit;
		$custData = DB::table('customers')
								->select(DB::raw('customers.*'))
								->where('customers.userId','=',currentOwnerId())
								->where('customers.status','=',1)
								->get();
		$purposes_of_tds = DB::table('purposes_of_tds')
								->get();

		$comp_name = isset($compData[0]->comp_name)?$compData[0]->comp_name:"";
		$comp_phone = isset($compData[0]->comp_phone)?$compData[0]->comp_phone:"";
		$comp_email = isset($compData[0]->comp_email)?$compData[0]->comp_email:"";
		$comp_pan = isset($compData[0]->comp_pan_no)?$compData[0]->comp_pan_no:"";
		$comp_gst = isset($compData[0]->gst_no)?$compData[0]->gst_no:"";
		$comp_pin =isset($compData[0]->comp_bill_pin)?$compData[0]->comp_bill_pin:"";

		$countries = Country::where('id', '>', '0')->get();

		$states_bill = State::where('id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:101)->get();
		$cities_bill = City::where('state_id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:0)->get();
		$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country)?$compDetails->comp_ship_country:0)->get();
		$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state)?$compDetails->comp_ship_state:0)->get();

		//echo "<pre>"; print_r($inv_voucher); exit;
		return view('User.add-carriage-inward')->with([
			'vNo' => $vNo,
			'comp_name' => $comp_name,
			'custData' => $custData,
			'comp_phone' => $comp_phone,
			'comp_pin'=>$comp_pin,
			'countries' => $countries,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'custData' => $custData,
			'inv_voucher' => $inv_voucher,
			'compData'  => $compData,
			'vendorData'=>$vendorData,
			'purposes_of_tds' => $purposes_of_tds
		]);
	}

	public function saveCarriageinwards(Request $request)
	{
		$validation = $this->validatorcarriageIn($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			$insertCarrIn = $this->createcarriageIn($request->all());
			$coId = DB::getPdo()->lastInsertId();

			if ($insertCarrIn) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/carriage-inward'),
					'message' => 'carriage-inward added successfully'
				);
				return response()->json($msg);
			} else {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'carriage-inward add failed'
				);
				return response()->json($msg);
			}
		}
	}

	function delcarrIn(Request $request)
	{
		$delcarrout = DB::table('carriageinwards')->where('id', $request->id)->delete();

		if ($delcarrout) {
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/carriage-inward'),
				'message' => 'carriage-inward deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/carriage-inward'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
	}

	public function editcarriageinwards($carriageId)
	{
		$carriageId = base64_decode($carriageId);
		$userId = currentOwnerId();
		$carriageIn = DB::table('carriageinwards')
								->where('id', '=', $carriageId)
								->get();
		$compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
								->where('company_profiles.userId', '=', $userId)
									->get();
		$custData = DB::table('customers')
									->select(DB::raw('customers.*'))
									->where('customers.userId', '=', $userId)
									->where('customers.status', '=', 1)
									->get();
		$vendorData = DB::table('vendors')
									->select(DB::raw('vendors.*'))
									->where('vendors.userId','=',currentOwnerId())
									->where('vendors.status','=',1)
									->get();
		//echo "<pre>";print_r($carriageIn);exit;
		$carriageIn = $carriageIn[0];
		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('id', '=', $carriageIn->buyer_state)->get();
		$cities_bill = City::where('id', '=', $carriageIn->buyer_city)->get();

		//$states_ship = State::where('country_id', '=', $carriageout->cust_ship_country)->get();
		//$cities_ship = City::where('state_id', '=', $carriageout->cust_ship_state)->get();
		//$states = State::where('country_id', '=', 101)->get();
		//--- Get Invoice list from saler table ------
		$invoiceNumbers = DB::table('purchases')
			->select('id', 'inv_num')
			->where('added_by', '=', $userId)
			->get();


		return view('User.edit-carriage-inwards')->with([
				'countries'=>$countries,
				'states_bill'=>$states_bill,
				'cities_bill'=>$cities_bill,
				'compData' => $compData,
				'custData' => $custData,
				//'states_ship'=>$states_ship,
				//'cities_ship'=>$cities_ship,
				'invoiceNumbers'=>$invoiceNumbers,
				'carriageIn' => $carriageIn,
				'carriageId' => $carriageId,
				'vendorData'=>$vendorData,
			]);
	}

	public function updatecarriageinwards(Request $request)
	{
		$carriageId = $request->id;
		$validation = $this->validatorcarriageIn($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			//start update project
			$update = DB::table('carriageinwards')
				->where('id', $carriageId)
				->update(
					array(
						'inv_num' => $request->inv_num,
						'inv_date' => $request->inv_date,
						'buyer_name' => $request->buyer_name,
						'buyer_contact' => $request->buyer_contact,
						'buyer_email' => $request->buyer_email,
						'buyer_pan' => $request->buyer_pan,
						'buyer_gst' => isset($request->buyer_gst) ? $request->buyer_gst : "",
						'buyer_addone' => $request->buyer_addone,
						'buyer_addtwo' => isset($request->buyer_addtwo) ? $request->buyer_addtwo : "",
						//'seller_country' => $data['seller_country'],
						'buyer_state' => $request->buyer_state,
						'buyer_city' => $request->buyer_city,
						'buyer_pin' => isset($request->buyer_pin) ? $request->buyer_pin : "",
						//'transport_type_other' => $transport_type_other,
						'vendor_name'=>$request->vendor_name,
						'vendor_contact'=>$request->vendor_contact,
						'vendor_email'=>$request->vendor_email,
						'vendor_pan'=>$request->vendor_pan,
						'vendor_gst'=>$request->vendor_gst,
						'vendor_order_no'=>$request->vendor_order_no,
						'vendor_dispatch_no'=>$request->vendor_dispatch_no,
						'disp_through'=>$request->disp_through,
						'other_dispa_det'=>isset($request->other_dispa_det) ? $request->other_dispa_det : "",
						'terms_delivery'=>$request->terms_delivery,
						'vendor_addone'=>$request->vendor_addone,
						'vendor_addtwo'=>$request->vendor_addtwo,
						'vendor_state'=>$request->vendor_state,
						'vendor_city'=>$request->vendor_city,
						'vendor_pin'=>$request->vendor_pin,
						'other_quantity'=>isset($request->other_quantity) ? $request->other_quantity : "",
						'other_transport'=>isset($request->other_transport) ? $request->other_transport : "",
						'other_transport_cost'=>isset($request->other_transport_cost) ? $request->other_transport_cost : "",
						'other_insurance_date'=>isset($request->other_insurance_date) ? $request->other_insurance_date : "",
						'tdsApplicable'=>$request->tdsApplicable,
						'tds_percentage'=>$request->tds_percentage,
						'gstApplicable'=>$request->gstApplicable,
						'other_hsn_sac_code'=>isset($request->other_hsn_sac_code) ? $request->other_hsn_sac_code : "",
						'other_gst_rate'=>isset($request->other_gst_rate) ? $request->other_gst_rate : "",
						'other_gst_mode'=>isset($request->other_gst_mode) ? $request->other_gst_mode : "",
						'other_pay_date'=>isset($request->other_pay_date) ? $request->other_pay_date : "",
						'other_mod_pay'=>isset($request->other_mod_pay) ? $request->other_mod_pay : "",
						'other_pay_method'=>isset($request->other_pay_method) ? $request->other_pay_method : "",
						'pay_status'=>$request->pay_status,
						'other_total_amount'=>isset($request->other_total_amount) ? $request->other_total_amount : "",
						'other_adv_amount'=>isset($request->other_adv_amount) ? $request->other_adv_amount : "",
						'other_due_amount'=>isset($request->other_due_amount) ? $request->other_due_amount : "",
						'other_refe_no'=>isset($request->other_refe_no) ? $request->other_refe_no : "",
						'other_approve_by'=>isset($request->other_approve_by) ? $request->other_approve_by : "",
						'other_term'=>isset($request->other_term) ? $request->other_term : "",
						//'other_uplode_doc'=>$data['other_uplode_doc'],
						'updated_at' => date('Y-m-d H:i:s'),
					)
				);
			if ($update) {
				return response()->json([
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/carriage-inward'),
					'message' => 'Carriage inward updated successfully'
				]);
			} else {
				return response()->json([
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Carriage inward update failed'
				]);
			}
			exit;
			return response()->json($msg);
			//end update item
		}


	}

	public function viewcarriageinwards($carriageId)
	{
		$carriageId = base64_decode($carriageId);
		$userId = currentOwnerId();
		$carriageIn = DB::table('carriageinwards')
								->where('id', '=', $carriageId)
								->get();
		$compData = DB::table('company_profiles')
								->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
								->where('company_profiles.userId', '=', $userId)
									->get();
		$custData = DB::table('customers')
									->select(DB::raw('customers.*'))
									->where('customers.userId', '=', $userId)
									->where('customers.status', '=', 1)
									->get();
		$vendorData = DB::table('vendors')
									->select(DB::raw('vendors.*'))
									->where('vendors.userId','=',currentOwnerId())
									->where('vendors.status','=',1)
									->get();
		//echo "<pre>";print_r($carriageIn);exit;
		$carriageIn = $carriageIn[0];

		$countries = Country::where('id', '>', '0')->get();
		$states_bill = State::where('id', '=', $carriageIn->buyer_state)->get();
		$cities_bill = City::where('id', '=', $carriageIn->buyer_city)->get();
		$invoiceNumbers = DB::table('purchases')
			->select('id', 'inv_num')
			->where('added_by', '=', $userId)
			->get();

		return view('User.view-carriage-inward')->with([
			'countries'=>$countries,
			'states_bill'=>$states_bill,
			'cities_bill'=>$cities_bill,
			'compData' => $compData,
			'custData' => $custData,
			'carriageIn' => $carriageIn,
			'invoiceNumbers'=>$invoiceNumbers,
			'vendorData'=>$vendorData,
			'carriageId' => $carriageId
		]);

	}

	public function delcarrageIn(Request $request)
	{
		$delcarrout = DB::table('carriageinwards')->where('id', $request->id)->delete();

		if ($delcarrout) {
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/carriage-inward'),
				'message' => 'Carriage inward deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/carriage-inward'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}


	}
	public function PurchaseOrder(){
        return view('User.purchase-order');
    }
    public function CreatePurchaseOrder(){
        return view('User.create-purchase-order');
    }

}
