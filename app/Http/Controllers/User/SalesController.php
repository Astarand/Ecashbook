<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sales;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Carriageout;
use App\Models\Vouchers;

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
use Carbon\Carbon;
use App\Helpers\AuditLogger;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class SalesController extends Controller
{
    public function __construct(JournalService $journalService = null, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
        $this->paymentVoucherService = $paymentVoucherService;
    }
	
	public function salesInvoiceIndex(Request $request)
	{
		$title = 'Sales Invoice';
		$userId = currentOwnerId();
		$search = $request->search;
		$searchDate = $request->search_date;
		checkCoreAccess('Accounting');

		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access

		/*
		|--------------------------------------------------------------------------
		| USER
		|--------------------------------------------------------------------------
		*/
		if (Auth::user()->u_type != 3 && Auth::user()->u_type != 6) {

			$salesQuery = DB::table('sales')
				->leftJoin('company_profiles', 'sales.added_by', '=', 'company_profiles.userId')
				->leftJoin('proprietorship_profiles', 'sales.propId', '=', 'proprietorship_profiles.id')
				->leftJoin('sales_values', 'sales_values.sid', '=', 'sales.id')
				->leftJoin('customers', 'customers.id', '=', 'sales.inv_name')
				->select(
					'sales.id',
					'sales.inv_num',
					'sales.inv_name',
					'sales.inv_date',
					'sales.mode_of_pay',
					'sales.other_payment',
					'sales.pay_status',
					'sales.status',

					'company_profiles.comp_name',

					'proprietorship_profiles.comp_name as prop_name',

					'customers.cust_name',
					'customers.cust_phone',

					DB::raw('SUM(sales_values.quantity) AS total_qty'),

					DB::raw('SUM(
						COALESCE(sales_values.amount, 0) +
						COALESCE(sales_values.tax_amt, 0) +
						COALESCE(sales_values.gov_pay, 0) +
						COALESCE(sales_values.ser_pay, 0)
					) AS total_amount')
				)

				->where('sales.added_by', $userId)
				->when($search, function ($query) use ($search) {
					$query->where(function ($q) use ($search) {
						$q->where('sales.inv_num', 'LIKE', "%{$search}%")
							->orWhere('customers.cust_name', 'LIKE', "%{$search}%");

					});
				})
				->when($searchDate, function ($query) use ($searchDate) {
					$query->whereDate('sales.inv_date', $searchDate);
				})
				->groupBy(
					'sales.id',
					'sales.inv_num',
					'sales.inv_name',
					'sales.inv_date',
					'sales.mode_of_pay',
					'sales.other_payment',
					'sales.pay_status',
					'sales.status',
					'company_profiles.comp_name',
					'proprietorship_profiles.comp_name',
					'customers.cust_name',
					'customers.cust_phone'
				)
				->orderBy('sales.id', 'DESC');
			
			//for next pagination search issue
			if ($search && $request->page > 1) {
				return redirect()->to(url()->current() . '?search=' . $search);
			}
			//$sales = $salesQuery->paginate(10)->withQueryString();
			$sales = $salesQuery->get();
		}

		$sales_pagination = $sales;

		$array = array();

		foreach ($sales as $k => $val) {

			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['prop_name'] = $val->prop_name ?? '';
			$array[$val->id]['inv_num'] = $val->inv_num;
			$array[$val->id]['inv_date'] = $val->inv_date;
			$array[$val->id]['mode_of_pay'] = $val->mode_of_pay;
			$array[$val->id]['other_payment'] = $val->other_payment;
			$array[$val->id]['pay_status'] = $val->pay_status;
			$array[$val->id]['total_amount'] = $val->total_amount;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['cust_name'] = $val->cust_name ?? '';
			$array[$val->id]['cust_phone'] = $val->cust_phone ?? '';

			if ($val->id > 0) {

				$salesValue = DB::table('sales_values')
					->select(DB::raw('SUM(sales_values.amount) as grandTotal'))
					->where('sid', '=', $val->id)
					->get();

				$array[$val->id]['grandTotal'] =
					isset($salesValue[0]->grandTotal)
					? $salesValue[0]->grandTotal
					: 0;

			} else {
				$array[$val->id]['grandTotal'] = 0;
			}

		}
		//$sales_pagination->setCollection(collect(array_values($array)));
		$sales = json_decode(json_encode($array));

		return view('User.sales-invoice')->with([
			'title' => $title,
			'sales' => $sales,
			'sales_pagination' => $sales_pagination,
			'invoice_create_status' => $this->invoice_create_status(),

		]);
	}
	
	//-----------Check Company Information FIll or Not -------
    public function companyInfoFill_old()
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
	
	public function companyInfoFill($userId)
	{
		$company = DB::table('company_profiles')
			->where('userId', $userId)
			->value('comp_name');

		if (empty($company)) {
			return false;
		}

		return true;
	}

	
	public function generateInvoiceNumber($userId)
	{
		// Stop execution if company profile not filled
		if (!$this->companyInfoFill($userId)) {
			return redirect()->route('user.CompanyProfile');
		}

		$company = DB::table('company_profiles')
			->where('userId', $userId)
			->first(['comp_name', 'comp_inv_digits']);

		if (!$company || empty($company->comp_name)) {
			return false;
		}

		/* ===============================
		   FINANCIAL YEAR (APR–MAR)
		=============================== */

		$currentMonth = date('n');
		$currentYear  = date('Y');

		if ($currentMonth >= 4) {
			$startYear = substr($currentYear, 2);
			$endYear   = substr($currentYear + 1, 2);
		} else {
			$startYear = substr($currentYear - 1, 2);
			$endYear   = substr($currentYear, 2);
		}

		$financialYear = $startYear . '-' . $endYear;


		/* ===============================
		   PREFIX GENERATION
		=============================== */

		if (!empty($company->comp_inv_digits)) {

			// Example comp_inv_digits: SI/001
			$basePrefix = trim($company->comp_inv_digits, '/');

			$parts = explode('/', $basePrefix);

			// Insert financial year only if not already present
			if (!in_array($financialYear, $parts)) {
				array_splice($parts, 1, 0, $financialYear);
			}

			$finalPrefix = implode('/', $parts) . '/';

		} else {

			// Default prefix
			$prefix = strtoupper(substr($company->comp_name, 0, 3));
			$seriesType = 'SI';

			$finalPrefix = $prefix . '/' . $financialYear . '/' . $seriesType . '/';
		}


		/* ===============================
		   FETCH LAST NUMBER
		=============================== */

		$lastInvoice = DB::table('sales')
			->where('added_by', $userId)
			//->where('inv_num', 'like', $finalPrefix . '%')
			->orderBy('id', 'desc')
			->value('inv_num');


		if ($lastInvoice) {

			$lastNumber = (int) substr($lastInvoice, strrpos($lastInvoice, '/') + 1);
			$nextNumber = $lastNumber + 1;

		} else {

			$nextNumber = 1;
		}


		/* ===============================
		   FINAL INVOICE NUMBER
		=============================== */

		$increment = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

		return $finalPrefix . $increment;
	}
	
	public function generateInvoiceNumberProprietorship($id,$userId)
	{

		$company = DB::table('proprietorship_profiles')
					->where('id', $id)
					->first(['comp_name', 'comp_inv_digits']);

		if (!$company || empty($company->comp_name)) {
			return false;
		}

		/* ===============================
		   FINANCIAL YEAR (APR–MAR)
		=============================== */

		$currentMonth = date('n');
		$currentYear  = date('Y');

		if ($currentMonth >= 4) {
			$startYear = substr($currentYear, 2);
			$endYear   = substr($currentYear + 1, 2);
		} else {
			$startYear = substr($currentYear - 1, 2);
			$endYear   = substr($currentYear, 2);
		}

		$financialYear = $startYear . '-' . $endYear;


		/* ===============================
		   PREFIX GENERATION
		=============================== */

		if (!empty($company->comp_inv_digits)) {

			// Example comp_inv_digits: SI/001
			$basePrefix = trim($company->comp_inv_digits, '/');

			$parts = explode('/', $basePrefix);

			// Insert financial year only if not already present
			if (!in_array($financialYear, $parts)) {
				array_splice($parts, 1, 0, $financialYear);
			}

			$finalPrefix = implode('/', $parts) . '/';

		} else {

			// Default prefix
			$prefix = strtoupper(substr($company->comp_name, 0, 3));
			$seriesType = 'SI';

			$finalPrefix = $prefix . '/' . $financialYear . '/' . $seriesType . '/';
		}


		/* ===============================
		   FETCH LAST NUMBER
		=============================== */

		$lastInvoice = DB::table('sales')
			->where('added_by', $userId)
			//->where('inv_num', 'like', $finalPrefix . '%')
			->orderBy('id', 'desc')
			->value('inv_num');


		if ($lastInvoice) {
			$lastNumber = (int) substr($lastInvoice, strrpos($lastInvoice, '/') + 1);
			$nextNumber = $lastNumber + 1;
		} else {

			$nextNumber = 1;
		}
		//FINAL INVOICE NUMBER
		$increment = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
		return $finalPrefix . $increment;
	}

	// Maximum 2 invoices can remain pending
	public function invoice_create_status() {
		$userId = currentOwnerId();

		$count = DB::table('sales')
			->where('added_by', $userId)
			->where(function($query) {
				$query->whereNull('pay_status')->orWhere('pay_status', '');
			})
			->count();

			if($count >=2){    //---------- count empty or null payment status -------
				return "false";
			}else{
				return "true";
			}
	}



    public function CreateSalesInvoices()
	{

		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		$invoiceNo = DB::table('sales')
			->select(DB::raw('MAX(id) as id'))
			->get();
		$compData = DB::table('company_profiles')
			->select(DB::raw('gst_reg,gst_no,comp_name,udyam_reg,udyam_reg_no,comp_phone,comp_email,comp_pan_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
			->where('company_profiles.userId', '=', $userId)
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', $userId)
			->where('customers.status', '=', 1)
			->get();

		$invoiceNo = $this->generateInvoiceNumber($userId);
		// echo "<pre>"; print_r($invoiceNo); exit;
		$quotations = DB::table('proformas')
					->where('status', 3)
					->where('proformas.added_by', $userId) 
					->select('id', 'inv_num')
					->get();

		$comp_name = isset($compData[0]->comp_name) ? $compData[0]->comp_name : "";
		$comp_phone = isset($compData[0]->comp_phone) ? $compData[0]->comp_phone : "";
		$comp_email = isset($compData[0]->comp_email) ? $compData[0]->comp_email : "";
		$comp_pan_no = isset($compData[0]->comp_pan_no) ? $compData[0]->comp_pan_no : "";
		$comp_bill_addone = isset($compData[0]->comp_bill_addone) ? $compData[0]->comp_bill_addone : "";
		$comp_bill_addtwo = isset($compData[0]->comp_bill_addtwo) ? $compData[0]->comp_bill_addtwo : "";
		$comp_bill_pin = isset($compData[0]->comp_bill_pin) ? $compData[0]->comp_bill_pin : "";
		$comp_gst_reg = isset($compData[0]->gst_reg) ? $compData[0]->gst_reg : "";
		$comp_gst_no  = ($comp_gst_reg == 'Yes') ? (isset($compData[0]->gst_no) ? $compData[0]->gst_no : "") : "";

		$udyam_reg = isset($compData[0]->udyam_reg) ? $compData[0]->udyam_reg : "";
		$udyam_reg_no = isset($compData[0]->udyam_reg_no) ? $compData[0]->udyam_reg_no : "";

		// $invoiceNo = isset($invoiceNo[0]->id) ? $invoiceNo[0]->id : 0;
		// $invoiceNo = Helper::invoice_num($invoiceNo + 1, 7, "SI-");
		//$countries = Country::where('id', '>', '0')->get();
		$states = State::where('country_id', '=', 101)->get();
    	$states_bill = State::where('id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:101)->get();
		$cities_bill = City::where('state_id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:0)->get();
		//echo "<pre>";print_r($cities_bill);die;
		$states_ship = State::where('country_id', '=', isset($compData->comp_ship_country) ? $compData->comp_ship_country : 0)->get();
		$cities_ship = City::where('state_id', '=', isset($compData->comp_ship_state) ? $compData->comp_ship_state : 0)->get();

		$invoice_create_status = $this->invoice_create_status();
		
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

		return view('User.create-sales-invoice')->with([
			'invoiceNo' => $invoiceNo,
			'compData' => $compData,
			'comp_name' => $comp_name,
			'comp_phone' => $comp_phone,
			'comp_email' => $comp_email,
			'comp_pan_no' => $comp_pan_no,
			'comp_bill_addone' => $comp_bill_addone,
			'comp_bill_addtwo' => $comp_bill_addtwo,
			'comp_bill_pin' => $comp_bill_pin,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'udyam_reg_no' => $udyam_reg_no,
			'udyam_reg' => $udyam_reg,
           // 'countries'=>$countries,
            'states'=>$states,
			'custData' => $custData,
			'comp_gst_reg' => $comp_gst_reg,
			'invoice_create_status' => $invoice_create_status,
			'quotations' => $quotations,
			'proprietorships' => $proprietorships,
			'comp_gst_no' => $comp_gst_no,
		]);
	}


    protected function getinvcust(Request $request)
	{
		//echo "<pre>"; print_r($request); exit;
		$id = $request->id;
		$salesTableID = $request->salesTableID;
		$result = Sales::query()
			->where('inv_name', '=', $id)
			->where('id', '=', $salesTableID)
			->get();

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
			$resultCust = Customers::query()
					->where('id', '=', $id)
					->get();
			foreach ($result as $k => $val) {
				$array['id'] = $resultCust[0]->id;
				$array['cust_email'] = $resultCust[0]->cust_email;
				$array['cust_phone'] = $resultCust[0]->cust_phone;
				$array['cust_pan'] = $resultCust[0]->cust_pan;
				$array['gst_reg'] = $resultCust[0]->gst_reg;

				// $array['cust_gst_no'] = $val->cust_gst_no;
				// $array['cust_gst_type'] = $val->cust_gst_type;
				$array['add_type'] = $val->add_type;
				$array['cust_bill_gstno'] = $val->bill_gstno;
				$array['cust_bill_contact'] = $val->cont_person;
				$array['cust_bill_mobilno'] = $val->cont_person_no;
				$array['cust_bill_designa'] = $val->bill_designa;
				$array['cust_bill_name'] = $val->bill_name;
				$array['cust_bill_addone'] = $val->bill_addone;
				$array['cust_bill_addtwo'] = $val->bill_addtwo;
				$array['cust_bill_country'] = $val->bill_country;
				$array['cust_bill_state'] = $val->bill_state;
				$array['cust_bill_city'] = $val->bill_city;
				$array['stateBill'] = $resStateBill;
				$array['cityBill'] = $resCityBill;
				$array['cust_bill_pin'] = $val->bill_pin;

				$array['cust_ship_gstno'] = $val->ship_gstno;
				$array['cust_ship_contact'] = $val->ship_cont_name;
				$array['cust_ship_mobilno'] = $val->ship_mobilno;
				$array['cust_ship_designa'] = $val->ship_designa;
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
			$result = Customers::query()
				->where('id', '=', $id)
				->get();


			$stateBill = State::query()
				->where('country_id', '=', $result[0]->cust_bill_country)
				->get()->toArray();
			$cityBill = City::query()
				->where('state_id', '=', $result[0]->cust_bill_state)
				->get()->toArray();
			$stateShip = State::query()
				->where('country_id', '=', $result[0]->cust_ship_country)
				->get()->toArray();
			$cityShip = City::query()
				->where('state_id', '=', $result[0]->cust_ship_state)
				->get()->toArray();
			$resStateBill = [];
			$resCityBill = [];
			$resStateShip = [];
			$resCityShip = [];
			foreach ($stateBill as $row) {

				$resStateBill[] = array("id" => $row['id'], "name" => $row['name'], "sid" => $result[0]->cust_bill_state);
			}
			foreach ($cityBill as $row1) {

				$resCityBill[] = array("id" => $row1['id'], "name" => $row1['name'], "sid" => $result[0]->cust_bill_city);
			}
			foreach ($stateShip as $row) {

				$resStateShip[] = array("id" => $row['id'], "name" => $row['name'], "selid" => $result[0]->cust_ship_state);
			}
			foreach ($cityShip as $row1) {

				$resCityShip[] = array("id" => $row1['id'], "name" => $row1['name'], "selid" => $result[0]->cust_ship_city);
			}
			$array = array();
			foreach ($result as $k => $val) {
				$array['id'] = $val->id;
				$array['cust_email'] = $val->cust_email;
				$array['cust_phone'] = $val->cust_phone;
				$array['cust_pan'] = $val->cust_pan;
				$array['gst_reg'] = $val->gst_reg;
				$array['cust_gst_no'] = $val->cust_gst_no;
				$array['cust_gst_type'] = $val->cust_gst_type;
				$array['comp_type'] = $val->comp_type;

				$array['cust_bill_addone'] = $val->cust_bill_addone;
				$array['cust_bill_addtwo'] = $val->cust_bill_addtwo;
				$array['cust_bill_country'] = $val->cust_bill_country;
				$array['cust_bill_state'] = $val->cust_bill_state;
				$array['cust_bill_city'] = $val->cust_bill_city;
				$array['stateBill'] = $resStateBill;
				$array['cityBill'] = $resCityBill;
				$array['cust_bill_pin'] = $val->cust_bill_pin;


				$array['cust_bill_gstno'] = $val->cust_bill_gstno;
				$array['cust_bill_contact'] = $val->cust_bill_contact;
				$array['cust_bill_mobilno'] = $val->cust_bill_mobilno;
				$array['cust_bill_designa'] = $val->cust_bill_designa;
				$array['cust_bill_name'] = $val->cust_bill_name;



				$array['cust_ship_addone'] = $val->cust_ship_addone;
				$array['cust_ship_country'] = $val->cust_ship_country;
				$array['cust_ship_state'] = $val->cust_ship_state;
				$array['cust_ship_city'] = $val->cust_ship_city;
				$array['stateShip'] = $resStateShip;
				$array['cityShip'] = $resCityShip;
				$array['cust_ship_pin'] = $val->cust_ship_pin;

				$array['cust_ship_gstno'] = $val->cust_ship_gstno;
				$array['cust_ship_contact'] = $val->cust_ship_contact;
				$array['cust_ship_mobilno'] = $val->cust_ship_mobilno;
				$array['cust_ship_designa'] = $val->cust_ship_designa;
				$array['cust_ship_name'] = $val->cust_ship_name;
				$array['cust_ship_addtwo'] = $val->cust_ship_addtwo;
			}
		}

		//$result = json_decode(json_encode($array));
		$result = $array;
		//echo "<pre>";print_r($result);exit;
		echo json_encode($result);
	}

    protected function validator(array $data)
	{
		return Validator::make($data, [
			'inv_num' => 'required',
			'inv_date' => 'required',
			'seller_name' => 'required',
			'seller_contact' => 'required',
			'seller_email' => 'required',
			//'seller_pan' => 'required',
			'seller_addone' => 'required',
			//'seller_country' => 'required',
			'seller_state' => 'required',
			'seller_city' => 'required',
			'seller_pin' => 'required',
			//'transport_type_other' => $transport_type_other,
		]);
	}

    protected function create(array $data)
	{

		$userId = currentOwnerId();
		$propId = $data['propId'];
		if(!empty($propId)) {
			$invoiceNo = $this->generateInvoiceNumberProprietorship($propId,$userId);
		} else {
			$invoiceNo = $this->generateInvoiceNumber($userId);
		}

		return Sales::create([
			'added_by' => currentOwnerId(),
			'propId' => $propId,
			'inv_num' => $invoiceNo,
			'inv_date' => $data['inv_date'],

			'seller_name' => $data['seller_name'],
			'seller_contact' => $data['seller_contact'],
			'seller_email' => $data['seller_email'],
			'seller_pan' => $data['seller_pan'],
			'saller_gst_reg' => $data['comp_gst_reg'],
			'seller_gst' => isset($data['seller_gst']) ? $data['seller_gst'] : "",
			'udyam_reg_no' => isset($data['udyam_reg_no']) ? $data['udyam_reg_no'] : "",
			'seller_addone' => $data['seller_addone'],
			'seller_addtwo' => isset($data['seller_addtwo']) ? $data['seller_addtwo'] : "",
			//'seller_country' => $data['seller_country'],
			'seller_state' => $data['seller_state'],
			'seller_city' => $data['seller_city'],
			'seller_pin' => $data['seller_pin'],
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

    public function save_sales_invoice(Request $request)
	{
		if ( $this->invoice_create_status() == "false") {
			$msg = array(
				'message' => 'Unable to create invoice! Please complete your previous invoices first.'
			);
			return response()->json($msg);
		}else{
			//$input = Input::all();
			//dd($input);
			$validation = $this->validator($request->all());
			if ($validation->fails()) {
				return response()->json($validation->errors()->toArray());
			} else {
				$insertSales = $this->create($request->all());
				$sId = DB::getPdo()->lastInsertId();

				if ($insertSales) {
					$msg = array(
						'status' => 'success',
						'class' => 'succ',
						'redirect' => url('/edit-sales-invoice/' . base64_encode($sId)),
						'message' => 'Sales added successfully'
					);
					return response()->json($msg);
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'Sales add failed'
					);
					return response()->json($msg);
				}
			}

		}

	}


    public function items_sales_list($sid)
	{

		$array = array();
		$sales_values = DB::table('sales_values')
			->select(DB::raw('sales_values.*'))
			->where('sid', '=', $sid)
			->get();

			$sales_dataee = DB::table('sales')
				->select('special_discount', 'special_discount_amount', 'special_discount_type')
				->where('id', '=', $sid)
				->first();

		foreach ($sales_values as $k => $val) {
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
			$array[$val->id]['gst_rate'] = $val->gst_rate;
			$array[$val->id]['gov_pay'] = $val->gov_pay;
			$array[$val->id]['ser_pay'] = $val->ser_pay;
			$array[$val->id]['tax_type'] = $val->tax_type;
			$array[$val->id]['gst_trans'] = $val->gst_trans;


			if ($val->prod_id > 0) {
				$item = Product::where('id', '=', $val->prod_id)->get();
				$array[$val->id]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$val->id]['sac_code'] = isset($item[0]->sac_code) ? $item[0]->sac_code : "";
				$array[$val->id]['hsn_code'] = isset($item[0]->hsn_code) ? $item[0]->hsn_code : "";
				$array[$val->id]['base_unit'] = isset($item[0]->base_unit) ? $item[0]->base_unit : "";
				$array[$val->id]['sec_unit'] = isset($item[0]->sec_unit) ? $item[0]->sec_unit : "";
			} else {
				$array[$val->id]['item_name'] = "";
				$array[$val->id]['sac_code'] = "";
				$array[$val->id]['hsn_code'] = "";
				$array[$val->id]['base_unit'] = "";
				$array[$val->id]['sec_unit'] = "";
			}
		}

		foreach ($array as $key => $value) {
			$array[$key]['special_discount'] = isset($sales_dataee->special_discount) ? $sales_dataee->special_discount : null;
			$array[$key]['special_discount_amount'] = isset($sales_dataee->special_discount_amount) ? $sales_dataee->special_discount_amount : null;
			$array[$key]['special_discount_type'] = isset($sales_dataee->special_discount_type) ? $sales_dataee->special_discount_type : null;
		}


		$sales_values = json_decode(json_encode($array));
		return $sales_values;
	}

	public function edit_sales_invoice($sId)
	{

		if (Auth::user()->u_type == 1) {
			return redirect('/sales-invoice');
		}
		$sId = base64_decode($sId);
		$uid = currentOwnerId();
		checkCoreAccess('Accounting');
		$sales = DB::table('sales')
			->where('id', '=', $sId)
			->get();

		$sales = $sales[0];
		$products = DB::table('products')
			->select(DB::raw('products.id,products.item_name'))
			->where('added_by', '=', $uid)
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', $uid)
			->where('customers.status', '=', 1)
			->get();



		//----- TDS --------
		$purposes_of_tds = DB::table('purposes_of_tds')
						->get();

		$countries = Country::where('id', '>', '0')->get();
		$states_bill = State::where('country_id', '=', isset($sales->bill_country) ? $sales->bill_country : 101)->get();
		$cities_bill = City::where('state_id', '=', isset($sales->bill_state) ? $sales->bill_state : 0)->get();

		$states_ship = State::where('country_id', '=', isset($sales->ship_country) ? $sales->ship_country : 0)->get();
		$cities_ship = City::where('state_id', '=', isset($sales->ship_state) ? $sales->ship_state : 0)->get();

		$states_seller = State::where('country_id', '=', isset($sales->seller_country) ? $sales->seller_country : 101)->get();
		$cities_seller = City::where('state_id', '=', isset($sales->seller_state) ? $sales->seller_state : 0)->get();

		// echo "<pre>";print_r($sales);exit;

		$sales_values = $this->items_sales_list($sId);
		
		return view('User.edit-sales-invoice')->with([
			'products' => $products,
			'sales' => $sales,
			'sales_values' => $sales_values,
			'countries' => $countries,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'states_seller' => $states_seller,
			'cities_seller' => $cities_seller,
			'custData' => $custData,
			'purposes_of_tds' => $purposes_of_tds,
			'sId' => $sId
		]);
	}

	public function update_sales_customer(Request $request)
	{
		$sId = $request->id;
		$update = DB::table('sales')
			->where('id', $sId)
			->update(
				array(
					'inv_name' => $request->inv_name,
					'add_type' => $request->add_type,

					'cont_person' => $request->cont_person,
					'cont_person_no' => $request->cont_person_no,

					'bill_gstno' => $request->cust_bill_gstno,
					'bill_name' => $request->cust_bill_name,
					'bill_designa' => $request->cust_bill_designa,
					'bill_addone' => $request->bill_addone,
					'bill_addtwo' => $request->bill_addtwo,
					'bill_country' => $request->cust_bill_country,
					'bill_state' => $request->cust_bill_state,
					'bill_city' => $request->cust_bill_city,
					'bill_pin' => $request->cust_bill_pin,

					'ship_gstno' => $request->cust_ship_gstno,
					'ship_cont_name' => $request->cust_ship_contact,
					'ship_mobilno' => $request->cust_ship_mobilno,
					'ship_designa' => $request->cust_ship_designa,
					'ship_name' => $request->cust_ship_name,
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

	public function view_sales_invoice($sId)
	{
		$sId = base64_decode($sId);
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		checkCoreAccess('Accounting');
		$sales = DB::table('sales')
			->where('id', '=', $sId)
			->get();
		$sales = $sales[0];
		$products = DB::table('products')
			->select(DB::raw('products.id,products.item_name'))
			->where('added_by', '=', $uid)
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.id,customers.cust_name'))
			->where('customers.userId', '=', $uid)
			->where('customers.status', '=', 1)
			->get();
		$purposes_of_tds = DB::table('purposes_of_tds')
						->get();
		$countries = Country::where('id', '>', '0')->get();
		$states_bill = State::where('country_id', '=', isset($sales->bill_country) ? $sales->bill_country : 0)->get();
		$cities_bill = City::where('state_id', '=', isset($sales->bill_state) ? $sales->bill_state : 0)->get();

		$states_ship = State::where('country_id', '=', isset($sales->ship_country) ? $sales->ship_country : 0)->get();
		$cities_ship = City::where('state_id', '=', isset($sales->ship_state) ? $sales->ship_state : 0)->get();

		$states_seller = State::where('country_id', '=', isset($sales->seller_country) ? $sales->seller_country : 0)->get();
		$cities_seller = City::where('state_id', '=', isset($sales->seller_state) ? $sales->seller_state : 0)->get();
		//echo "<pre>";print_r($sales);exit;

		$sales_values = $this->items_sales_list($sId);
		return view('User.view-sales-invoice')->with([
			'products' => $products,
			'sales' => $sales,
			'sales_values' => $sales_values,
			'countries' => $countries,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'states_seller' => $states_seller,
			'cities_seller' => $cities_seller,
			'custData' => $custData,
			'purposes_of_tds' => $purposes_of_tds,
			'sId' => $sId
		]);
	}


	public function update_sales_invoice(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;

		$validation = $this->validator($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			/* if($request->transport_type == 'Other'){
				$transport_type_other = $request->transport_type_other;
			}else{
				$transport_type_other = "";
			} */
			$update = DB::table('sales')
				->where('id', $sId)
				->update(
					array(
						'inv_date' => $request->inv_date,
						'seller_name' => $request->seller_name,
						'seller_contact' => $request->seller_contact,
						'seller_email' => $request->seller_email,
						'seller_pan' => $request->seller_pan,
						'saller_gst_reg' => $request->saller_gst_reg,
						'seller_gst' => isset($request->seller_gst) ? $request->seller_gst : "",
						'seller_addone' => $request->seller_addone,
						'seller_addtwo' => isset($request->seller_addtwo) ? $request->seller_addtwo : "",
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
		}
	}

	public function getProductType(Request $request)
	{
		$item_type = $request->id;
		$userId = currentOwnerId();
		$result = DB::table('products')
			->select(DB::raw('products.id,products.item_name,products.service_name'))
			->where('item_type', '=', $item_type)
			->where('added_by', '=', $userId)
			->get();

		$response = [];
		//echo "<pre>";print_r($result);exit;
		foreach ($result as $row) {
			$response[] = [
				"id" => $row->id,
				"name" => !empty($row->item_name) ? $row->item_name : $row->service_name
			];
		}
		return response()->json($response);
	}

	public function getProduct(Request $request)
	{
		$itemId = $request->prod_id;
		$result = DB::table('products')
			->select(DB::raw('products.id,products.sac_code,products.hsn_code,products.disc_sell,products.disc_sell_type,products.gst_rate,products.goods_desc'))
			->where('id', '=', $itemId)
			->get();

		$response = [];
		//echo "<pre>";print_r($result);exit;
		foreach ($result as $row) {
			if ($row->hsn_code != "") {
				$hsn_sac_code = $row->hsn_code;
			} else {
				$hsn_sac_code = $row->sac_code;
			}
			$response[] = array("id" => $row->id, "hsn_sac_code" => $hsn_sac_code,"gst_rate" => $row->gst_rate, "goods_desc" => $row->goods_desc, "disc_sell" => $row->disc_sell, "disc_sell_type" => $row->disc_sell_type);
		}
		echo json_encode($response);
	}

	public function updateGstType($sid)
	{
		$custId = DB::table('sales')
			->select(DB::raw('sales.inv_name'))
			->where('id', '=', $sid)
			->get();
		$cust_gst = DB::table('customers')
			->select(DB::raw('customers.cust_gst_no'))
			->where('id', '=', $custId[0]->inv_name)
			->get();

		$taxableInvTotal = DB::table('sales_values')
			->join('sales', 'sales.id', '=', 'sales_values.sid')
			->where('sales.id', '=', $sid)
			->select(DB::raw('
				SUM(sales_values.amount - sales_values.disc_amt) as taxableInvTotal
			'))
			->value('taxableInvTotal');
		$taxableInvTotal = round($taxableInvTotal, 2);
		$gst_type = "";
		if (!empty(optional($cust_gst->first())->cust_gst_no)) {
			$gst_type = "B2B";
		}
		else if($taxableInvTotal > 250000){
			$gst_type = "B2CL";
		}else{
			$gst_type = "B2CS";
		}
		$update = DB::table('sales')
			->where('id', '=', $sid)
			->update(
				array(
					'gst_type' => $gst_type,
				)
			);
	}

	
	

	public function saveProductAndSales(Request $request)
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

			//INSERT INTO sales_values
			DB::table('sales_values')->insert([
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
				'gst_allocation_text'  => "CGST(9%) & SGST(9%)",
				'gst_trans' => $request->gst_trans,
				'gov_pay'   => $request->gov_pay ?? 0,
				'ser_pay'   => $request->ser_pay ?? 0,
				'billing_type' => "Product/ Service Billing",
				'prod_gov_fee' => isset($request->prod_gov_fee) ? $request->prod_gov_fee : 0,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			DB::commit();
			$sales_values = $this->items_sales_list($sid);
			$this->updateGstType($sid);
			$this->journalEntry($sid,$uid); //Journal Entry
			return view('User.ajax-sales-invoice-display')->with([
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


	public function sales_items_display(Request $request)
	{
		//echo "<pre>"; print_r($request);exit;
		$sid = $request->sId;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$sales_values = DB::table('sales_values')
			->select(DB::raw('sales_values.id,sales_values.quantity'))
			->where('sid', '=', $sid)
			->where('uid', '=', $uid)
			->where('prod_id', '=', $prod_id)
			->get();

		$product = DB::table('products')
			->select(DB::raw('products.*'))
			->where('id', '=', $prod_id)
			->get();
		$billing_type = isset($request->billing_type) ? $request->billing_type : "";
		$prod_gov_fee = isset($request->prod_gov_fee) ? $request->prod_gov_fee : 0;
		$gst_trans = isset($request->gst_trans) ? $request->gst_trans : "";

		if (count($sales_values) == 0) {
			$quantity = 1;
		} else {
			$quantity = $sales_values[0]->quantity;
		}

		if($product[0]->item_type =='service'){
			$rate = $product[0]->ser_selling_price;
		}else{
			$rate = $product[0]->selling_price;
		}
		$gst_allocation_text = $request->gst_allocation_text;
		$disc = $request->disc_sell;
		$disc_type = $request->disc_sell_type;

		if ($disc_type == "percentage") {
			$disc_amt = (($rate * $disc) / 100);
		} else {
			$disc_amt = $disc;
		}
		$amount = ($rate - $disc_amt) * $quantity;
		$gst_rate = $product[0]->gst_rate ?? 0;
		$gov_pay = $product[0]->gov_pay ?? 0;
		$ser_pay = $product[0]->ser_pay ?? 0;
		$tax_amt = ($amount * $gst_rate) / 100;
		$tax_type = "N/A";

		if (count($sales_values) == 0) {
			$values = array('sid' => $sid, 'uid' => $uid, 'prod_id' => $prod_id, 'quantity' => $quantity, 'rate' => $rate, 'disc' => $disc, 'disc_type' => $disc_type, 'disc_amt' => $disc_amt, 'tax_amt' => $tax_amt, 'amount' => $amount, 'tax_type' => $tax_type, 'gst_rate' => $gst_rate, 'gov_pay' => $gov_pay, 'ser_pay' => $ser_pay,'gst_allocation_text' => $gst_allocation_text, 'billing_type' => $billing_type, 'prod_gov_fee' => $prod_gov_fee, 'gst_trans' => $gst_trans);
			$insertInvoice = DB::table('sales_values')->insert($values);
		} else {
			$update = DB::table('sales_values')
				->where('sid', '=', $sid)
				->where('uid', '=', $uid)
				->where('prod_id', '=', $prod_id)
				->update(
					array(
						'quantity' => $quantity,
						'rate' => $rate,
						'disc' => $disc,
						'disc_type' => $disc_type,
						'disc_amt' => $disc_amt,
						'tax_amt' => $tax_amt,
						'amount' => $amount,
						'tax_type' => $tax_type,
						'gst_rate' => $gst_rate,
						'gov_pay' => $gov_pay,
						'ser_pay' => $ser_pay,
						'gst_allocation_text' => $request->gst_allocation_text,
						'billing_type' => $billing_type,
						'prod_gov_fee' => $prod_gov_fee,
						'gst_trans' => $gst_trans
					)
				);
		}


		$sales_values = $this->items_sales_list($sid);
		//echo "<pre>"; print_r($sales_values);exit;
		$this->updateGstType($sid);		
		$this->journalEntry($sid,$uid); //Journal Entry
		return view('User.ajax-sales-invoice-display')->with([
			'sales_values' => $sales_values,
		]);
	}
	
	public function journalEntry($sid,$uid)
	{
		$sale = DB::table('sales as s')
			->leftJoin('customers as c', 'c.id', '=', 's.inv_name')
			->select(
				's.*',
				'c.cust_name'
			)
			->where('s.id', $sid)
			->where('s.added_by', $uid)
			->first();
		$totals = DB::table('sales_values')
			->selectRaw('
				SUM(amount) as total_amount,
				SUM(tax_amt) as total_tax,
				AVG(gst_rate) as avg_gst_rate,
				MAX(gst_trans) as gst_trans
			')
			->where('sid', $sid)
			->where('uid', $uid)
			->first();

		if ($sale) {
			$this->journalService->storeSalesJournalEntries([
				'source'        => 'Sales',
				'autoId'        => $sid,
				'added_by'      => $uid,
				'propId'        => $sale->propId,
				'date'          => $sale->inv_date,
				'reference_no'  => $sale->inv_num,
				'entry_type'    => 'Sales',
				'party_name'    => $sale->cust_name ?? '',
				'total_amount'  => $totals->total_amount ?? 0,
				'base_amount'   => ($totals->total_amount - $totals->total_tax),
				'gst_amount'    => $totals->total_tax ?? 0,
				'gst_rate'      => $totals->avg_gst_rate ?? 0,
				'gst_trans'     => $totals->gst_trans ?? 'intrastate',
				'status'        => $sale->status,
			]);
		}
		
	}
	
	public function getInvoiceTotal($id)
	{
		$total_amount = DB::table('sales_values')
			->where('sid', $id)
			->sum(DB::raw('amount + tax_amt + gov_pay + ser_pay'));

		return response()->json([
			'total' => $total_amount
		]);
	}


	public function update_sales_item_rate(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$sid = $request->sid;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$sales_data = DB::table('sales_values')
			->select(DB::raw('sales_values.sid,sales_values.quantity,sales_values.disc,sales_values.disc_type,sales_values.gst_rate'))
			->where('id', '=', $request->id)
			->get();

		//$rate = $request->rate;

		$productRate = $request->rate;
		$gst_rate = $sales_data[0]->gst_rate;
		$disc = $sales_data[0]->disc;
		$disc_type = $sales_data[0]->disc_type;
		$amount = $productRate * $sales_data[0]->quantity;
		if ($disc_type == "percentage") {
			$disc_amt = (($amount * $disc) / 100);
		} else {
			$disc_amt = $disc;
		}
		$amount = ($amount - $disc_amt);
		$tax_amt = ($amount * $gst_rate) / 100;

		$update = DB::table('sales_values')
			->where('id', $id)
			->update(
				array(
					'rate' => $productRate,
					'disc_amt' => $disc_amt,
					'tax_amt' => $tax_amt,
					'amount' => $amount
				)
			);

		$sales_values = $this->items_sales_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($sales_values);exit;
		$this->updateGstType($sid);
		$this->journalEntry($sid,$uid); //Journal Entry
		return view('User.ajax-sales-invoice-display')->with([
			'sales_values' => $sales_values,
		]);
	}


public function update_sales_invoice_final(Request $request)
	{

		//print_r($_FILES);
		$signature_name = $request->signature_name;

		$tds_applicable = $request->tdsApplicable;
		$tds_percentage = $request->tdsPercentage;
		$tds_id = $request->tds_id;

		$tds_amount = $request->tds_amount;
		$special_discount_amount = $request->discount_amount;


		if ($file = $request->hasFile('signature')) {
			$file = $request->file('signature');

			$fileName1 = date("YmdHis") . '-' . $file->getClientOriginalName();
			$destinationPath1 = public_path() . '/uploads/invoice-signature';

			$file->move($destinationPath1, $fileName1);
			$signature = $fileName1;

			//Update file
			$update = DB::table('sales')
				->where('id', $request->id)
				->update(
					array(
						'signature' => $signature,
						'signature_name' => $signature_name,
						'tds_applicable' => $tds_applicable,
						'tds_percentage' => $tds_percentage,
						'tds_id' => $tds_id,
						'tds_amount' => $tds_amount,
						'status' => 1,
						'special_discount_amount' => $special_discount_amount,

					)
				);
		} else {
			$update = DB::table('sales')
				->where('id', $request->id)
				->update(
					array(
						'signature_name' => $signature_name,
						'tds_applicable' => $tds_applicable,
						'tds_percentage' => $tds_percentage,
						'tds_id' => $tds_id,
						'tds_amount' => $tds_amount,
						'status' => 1,
						'special_discount_amount' => $special_discount_amount,

					)
				);
		}

		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/sales-invoice'),
			'message' => 'Record successfully updated',
		);
		return response()->json($msg);
	}

	protected function validatorOther(array $data)
	{
		//echo "<pre>"; print_r($data);exit;
		if ($data['disp_through'] == 'Other') {
			$other_dispa_det = "required";
		} else {
			$other_dispa_det = "";
		}
		return Validator::make($data, [
			'mode_of_pay' => 'required',
			'pay_status' => 'required',
			'total_amount' => 'numeric',
			//'order_date' => 'required',
			//'disp_through' => 'required',
			'other_dispa_det' => $other_dispa_det,
		]);
	}

	public function update_sales_other(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;

		$validation = $this->validatorOther($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			
			//start get old records
			$oldRec = DB::table('sales')
				->where('id', $sId)
				->first();
			$oldAdvanceAmount  = (float) ($oldRec->advance_amount ?? 0);
			$oldAdjustedAmount = (float) ($oldRec->adjusted_amount ?? 0);
			//end get old records
			
			//Payment full-advance logic
			$payStatus = $request->pay_status;
			$totalAmount   = $request->total_amount ?? 0;
			$advanceAmount = $request->advance_amount ?? 0;
			$dueAmount     = $request->due_amount ?? 0;
			$adjustedAmount = $request->adjusted_amount ?? 0;
			$currentPayment = 0;
			if ($payStatus === 'Partial') {
				$currentPayment = $advanceAmount;
				$advanceAmount = $oldAdvanceAmount + $advanceAmount;
				$adjustedAmount = 0;
				$dueAmount = $totalAmount - $advanceAmount;
				if ($dueAmount < 0) {
					$dueAmount = 0;
				}
			}
			else if ($payStatus === 'Full') 
			{
				$remainingAmount = $totalAmount - $oldAdjustedAmount;
				if ($remainingAmount < 0) {
					$remainingAmount = 0;
				}
				$currentPayment = $remainingAmount;
				$adjustedAmount = $totalAmount;
				$advanceAmount = $oldAdvanceAmount;
				$dueAmount = 0;
			}
			else {
				$currentPayment = 0;
			}
			
			$update = DB::table('sales')
				->where('id', $sId)
				->update(
					array(
						'mode_of_pay' => $request->mode_of_pay,
						'other_payment' => $request->other_payment,
						'pay_status' => $request->pay_status,
						'total_amount' => isset($request->total_amount) ? $request->total_amount : 0,
						'advance_amount' => isset($request->advance_amount) ? $request->advance_amount : 0,
						'due_amount' => isset($request->due_amount) ? $request->due_amount : 0,
						'adjusted_amount' => $adjustedAmount,
						'buyer_orderno' => isset($request->buyer_orderno) ? $request->buyer_orderno : "",
						'order_date' => !empty($request->order_date) ? $request->order_date : null,
						'supplier_refno' => isset($request->supplier_refno) ? $request->supplier_refno : "",
						'other_refno' => isset($request->other_refno) ? $request->other_refno : "",
						'dispa_docno_one' => isset($request->dispa_docno_one) ? $request->dispa_docno_one : "",
						'dispa_docno_two' => isset($request->dispa_docno_two) ? $request->dispa_docno_two : "",
						'disp_through' => $request->disp_through,
						'other_dispa_det' => isset($request->other_dispa_det) ? $request->other_dispa_det : "",
						'terms_delivery' => isset($request->terms_delivery) ? $request->terms_delivery : ""
					)
				);
			
			//start entry for voucher payment
			if ($currentPayment <= 0) {
				$currentPayment = 0;
			}
			if ($currentPayment > 0)
			{
				$this->paymentVoucherService->storePaymentVoucherEntries($sId, 'Sales', $currentPayment);
			}
			//end entry for voucher payment
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/sale-invoices'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
		}
	}

	public function delInvoice(Request $request)
	{
		//FETCH OLD DATA (BEFORE DELETE)
		$data = DB::table('sales')->where('id', $request->id)->first();
		$oldData = [
					'record' => [
						'inv_num'  => $data->inv_num ?? null,
						'inv_date' => $data->inv_date ?? null,
					]
				];
				
		$delInvoice = DB::table('sales')->where('id', $request->id)->delete();
		$delInvoiceItemValue = DB::table('sales_values')->where('sid', $request->id)->delete();
		$delJournalRec = DB::table('journals')->where('autoId', $request->id)->delete();
		if ($delInvoice) {
			//AUDIT LOG ENTRY
            AuditLogger::logEntry(
                action: 'delete',
                module: 'Sales Invoice',
                description: "Sales Invoice deleted: {$data->inv_num}",
                oldData: $oldData,
                newData: null
            );
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/sales-invoice'),
				'message' => 'Sales invoice deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/sales-invoice'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
	}

	public function update_sales_item_quantity(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$sid = $request->sid;
		$uid = currentOwnerId();

		$sales_data = DB::table('sales_values')
			->select(DB::raw('sales_values.sid,sales_values.rate,sales_values.disc,sales_values.disc_type,sales_values.gst_rate'))
			->where('id', '=', $request->id)
			->get();
		$productRate = $sales_data[0]->rate;
		$gst_rate = $sales_data[0]->gst_rate;
		$disc = $sales_data[0]->disc;
		$disc_type = $sales_data[0]->disc_type;
		$amount = $productRate * $request->quantity;
		if ($disc_type == "percentage") {
			$disc_amt = (($amount * $disc) / 100);
		} else {
			$disc_amt = $disc;
		}
		$amount = ($amount - $disc_amt);
		$tax_amt = ($amount * $gst_rate) / 100;

		$update = DB::table('sales_values')
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

		$sales_values = $this->items_sales_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($sales_values);exit;
		$this->updateGstType($sid);
		$this->journalEntry($sid,$uid); //Journal Entry
		return view('User.ajax-sales-invoice-display')->with([
			'sales_values' => $sales_values,
		]);
	}

	public function fetchSalesItem(Request $request)
	{
		$sid = $request->id;
		$sales_values = DB::table('sales_values')
			->select(DB::raw('sales_values.*'))
			->where('sales_values.id', '=', $sid)
			->get();
		//echo "<pre>"; print_r($sales_values);exit;
		$array = array();
		foreach ($sales_values as $k => $val) {
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

			if ($val->prod_id > 0) {
				$item = Product::where('id', '=', $val->prod_id)->get();
				$array[$val->id]['item_type'] = isset($item[0]->item_type) ? $item[0]->item_type : "";
				$array[$val->id]['item_name'] = !empty($item[0]->item_name) ? $item[0]->item_name : $item[0]->service_name;
				$array[$val->id]['disc_sell'] = isset($item[0]->item_name) ? $item[0]->disc_sell : "";
				$array[$val->id]['sac_code'] = isset($item[0]->sac_code) ? $item[0]->sac_code : "";
				$array[$val->id]['hsn_code'] = isset($item[0]->hsn_code) ? $item[0]->hsn_code : "";
				$array[$val->id]['base_unit'] = isset($item[0]->base_unit) ? $item[0]->base_unit : "";
				$array[$val->id]['sec_unit'] = isset($item[0]->sec_unit) ? $item[0]->sec_unit : "";
			} else {
				$array[$val->id]['item_type'] = "";
				$array[$val->id]['item_name'] = "";
				$array[$val->id]['disc_sell'] = "";
				$array[$val->id]['sac_code'] = "";
				$array[$val->id]['hsn_code'] = "";
				$array[$val->id]['base_unit'] = "";
				$array[$val->id]['sec_unit'] = "";
			}
		}
		$sales_values = (json_encode($array));
		//echo "<pre>";print_r($sales_values);exit;
		return $sales_values;
	}

	public function delSalesItem(Request $request)
	{
		$delSalesItem = DB::table('sales_values')->where('id', $request->id)->delete();
		$sales_values = $this->items_sales_list($request->sid);
		$uid = currentOwnerId();
		$sid = $request->sid;
		$this->journalEntry($sid,$uid); //Journal Entry
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.ajax-sales-invoice-display')->with([
			'sales_values' => $sales_values,
		]);
	}
	
	public function quotationToSales(Request $request)
    {
		$userId = currentOwnerId();
        $request->validate([
            'quotation_ref_num' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $quotation = DB::table('proformas')
                ->where('inv_num', $request->quotation_ref_num)
				->where('added_by', $userId)
                ->first();
			//echo "<pre>";print_r($quotation);exit;
            if (!$quotation) {
                return response()->json([
                    'status' => false,
                    'message' => 'Proformas not found'
                ]);
            }
			
			if ($quotation->pay_status != 'Full') {
				return response()->json([
					'status' => false,
					'message' => "Payment is not full, you can't create invoice"
				]);
			}

            // Convert object → array and remove PK
            $salesData = (array) $quotation;

            unset($salesData['id']);       // remove auto id
            unset($salesData['inv_num']);       // remove auto id
            unset($salesData['created_at']);
            unset($salesData['updated_at']);

            // Change required fields
			$propId = $salesData['propId'];
			if(!empty($propId)) {
				$invoiceNo = $this->generateInvoiceNumberProprietorship($propId,$userId);
			} else {
				$invoiceNo = $this->generateInvoiceNumber($userId);
			}
            $salesData['inv_num']   = $invoiceNo;
            $salesData['added_by']  = $userId;
            $salesData['status']  = 1;

            // Insert into sales
            $salesId = DB::table('sales')->insertGetId($salesData);

            // Fetch quotation line items
            $quotationItems = DB::table('proformas_values')
                ->where('sid', $quotation->id)
                ->get();

            // Insert into sales_values
            foreach ($quotationItems as $item) {

                $itemData = (array) $item;
                unset($itemData['id']);   // VERY IMPORTANT
				unset($itemData['sid']);  // remove sales id
                $itemData['uid'] = $userId;
                $itemData['sid'] = $salesId;
                DB::table('sales_values')->insert($itemData);
            }
			$this->journalEntry($salesId,$userId); //Journal Entry

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Sales invoice created successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


	public function CarriageOutwards()
	{
		$title = 'Carriage Outward';
        $userId = currentOwnerId();

		$carrouts =  DB::table('carriageouts')
							->select(DB::raw('carriageouts.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'carriageouts.added_by', '=', 'company_profiles.userId')
							//->leftJoin('ca_assigns', 'carriageouts.added_by', '=', 'ca_assigns.comp_id')
							//->where('ca_assigns.ca_id','=',$userId)
							->where('carriageouts.added_by','=',$userId)
							->orderBy('created_at', 'DESC')->paginate(10);
		//echo "<pre>";print_r($carrouts);exit('kkk');
		$carrout_pagination = $carrouts;
		return view('User.carriage-outwards')->with([
			'title' =>$title,
			'carrouts'=>$carrouts,
			'carrout_pagination' =>$carrout_pagination,
		]);
	}

	public function AddCarriageOutward()
	{

		$vNo = DB::table('vouchers')
			->select(DB::raw('MAX(id) as id'))
			->get();
		$vNo = isset($vNo[0]->id) ? $vNo[0]->id : 0;
		$vNo = Helper::invoice_num($vNo + 1, 7, "VN-");
		$userId = currentOwnerId();
		$compData = DB::table('company_profiles')
		->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
		->where('company_profiles.userId', '=', $userId)
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', $userId)
			->where('customers.status', '=', 1)
			->get();
		$comp_name = isset($compData[0]->comp_name) ? $compData[0]->comp_name : "";
		$comp_phone = isset($compData[0]->comp_phone) ? $compData[0]->comp_phone : "";
		$countries = Country::where('id', '>', '0')->get();

		$states_bill = State::where('id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:101)->get();
		$cities_bill = City::where('state_id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:0)->get();
		//print_r($cities_bill);
		$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country) ? $compDetails->comp_ship_country : 0)->get();
		$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state) ? $compDetails->comp_ship_state : 0)->get();

		//--- Get Invoice list from saler table ------
		$invoiceNumbers = DB::table('sales')
			->select('id', 'inv_num')
			->where('added_by', '=', $userId)
			->get();

		$purposes_of_tds = DB::table('purposes_of_tds')
						->get();
		return view('User.add-carriage-outwards')->with([
			'vNo' => $vNo,
			'comp_name' => $comp_name,
			'custData' => $custData,
			'comp_phone' => $comp_phone,
			'countries' => $countries,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'custData' => $custData,
			'invoiceNumbers' => $invoiceNumbers,
			'compData'  => $compData,
			'purposes_of_tds' => $purposes_of_tds
		]);
	}

	public function fetchSalesDetails(Request $request)
	{
		$invoiceNumber = $request->input('inv_num');

		$salesDetails = DB::table('sales')
						->leftJoin('countries', 'countries.id', '=', 'sales.seller_country')
						->leftJoin('states', 'states.id', '=', 'sales.seller_state')
						->leftJoin('cities', 'cities.id', '=', 'sales.seller_city')
						->select(
							'sales.*',
							'countries.name as country_name',
							'states.name as state_name',
							'cities.name as city_name'
						)
						->where('sales.inv_num', $invoiceNumber)
						->first();

		if ($salesDetails) {
			return response()->json([
				'status' => 'success',
				'data' => $salesDetails,
			]);
		}
		else {
			return response()->json([
				'status' => 'error',
				'message' => 'Invoice not found.',
			]);
		}
	}

	protected function validatorcarriageout(array $data)
	{

		return Validator::make($data, [
			//'inv_num' => 'required',
			//'inv_date' => 'required',
			//'seller_name' => 'required',
			'seller_contact' => 'required',
			'seller_email' => 'required',
			//'seller_pan' => 'required',
			'seller_addone' => 'required',
			//'seller_country' => 'required',
			'seller_state' => 'required',
			'seller_city' => 'required',
			//'seller_pin' => 'required',
			//'transport_type_other' => $transport_type_other,
		]);
	}

	protected function createcarriageout(array $data)
	{
		//echo "<pre>";print_r($data);exit;


		return Carriageout::create([
			'added_by' => currentOwnerId(),
			'inv_num' => $data['inv_num'],
			'inv_date' => $data['inv_date'],

			'seller_name' => $data['seller_name'],
			'seller_contact' => $data['seller_contact'],
			'seller_email' => $data['seller_email'],
			'seller_pan' => $data['seller_pan'],
			'seller_gst' => isset($data['seller_gst']) ? $data['seller_gst'] : "",
			'seller_addone' => $data['seller_addone'],
			'seller_addtwo' => isset($data['seller_addtwo']) ? $data['seller_addtwo'] : "",
			//'seller_country' => $data['seller_country'],
			'seller_state' => $data['seller_state'],
			'seller_city' => $data['seller_city'],
			'seller_pin' => $data['seller_pin'],
			'cust_name'=>$data['cust_name'],
			'cust_contact'=>$data['cust_contact'],
			'cust_email'=>$data['cust_email'],
			'cust_pan'=>$data['cust_pan'],
			'cust_gst'=>$data['cust_gst'],
			'cust_order_no'=>$data['cust_order_no'],
			'cust_dispatch_no'=>$data['cust_dispatch_no'],
			'disp_through'=>$data['disp_through'],
			//'other_dispa_det'=>$data['other_dispa_det'],
			'terms_delivery'=>$data['terms_delivery'],
			'cust_addone'=>$data['cust_addone'],
			'cust_addtwo'=>$data['cust_addtwo'],
			'cust_state'=>$data['cust_state'],
			'cust_city'=>$data['cust_city'],
			'cust_pin'=>$data['cust_pin'],
			'other_quantity'=>$data['other_quantity'],
			'other_transport'=>$data['other_transport'],
			'other_transport_cost'=>$data['other_transport_cost'],
			'other_insurance_date'=>$data['other_insurance_date'],
			'tdsApplicable'=>$data['tdsApplicable'],
			'tds_percentage'=>$data['tds_percentage'],
			'gstApplicable'=>$data['gstApplicable'],
			'other_hsn_sac_code'=>$data['other_hsn_sac_code'],
			'other_gst_rate'=>$data['other_gst_rate'],
			'other_gst_mode'=>$data['other_gst_mode'],
			'other_pay_date'=>$data['other_pay_date'],
			'other_mod_pay'=>$data['other_mod_pay'],
			'other_pay_method'=>$data['other_pay_method'],
			'pay_status'=>$data['pay_status'],
			'other_total_amount'=>$data['other_total_amount'],
			'other_adv_amount'=>$data['other_adv_amount'],
			'other_due_amount'=>$data['other_due_amount'],
			'other_refe_no'=>$data['other_refe_no'],
			'other_approve_by'=>$data['other_approve_by'],
			'other_term'=>$data['other_term'],
			//'other_uplode_doc'=>$data['other_uplode_doc'],
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function savecarriageoutwards(Request $request)
	{
		$validation = $this->validatorcarriageout($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			$insertCarrout = $this->createcarriageout($request->all());
			$coId = DB::getPdo()->lastInsertId();

			if ($insertCarrout) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/carriage-outward'),
					'message' => 'Carriage-outward added successfully'
				);
				return response()->json($msg);
			} else {
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Carriage-outward add failed'
				);
				return response()->json($msg);
			}
		}
	}

	public function editcarriageoutwards($carriageId)
	{
		$carriageId = base64_decode($carriageId);
		$userId = currentOwnerId();
		$carriageout = DB::table('carriageouts')
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
		//echo "<pre>";print_r($carriageout);exit;
		$carriageout = $carriageout[0];
		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('id', '=', $carriageout->cust_state)->get();
		$cities_bill = City::where('id', '=', $carriageout->cust_city)->get();

		//$states_ship = State::where('country_id', '=', $carriageout->cust_ship_country)->get();
		//$cities_ship = City::where('state_id', '=', $carriageout->cust_ship_state)->get();
		//$states = State::where('country_id', '=', 101)->get();
		//--- Get Invoice list from saler table ------
		$invoiceNumbers = DB::table('sales')
			->select('id', 'inv_num')
			->where('added_by', '=', $userId)
			->get();


		return view('User.edit-carriage-outwards')->with([
				'countries'=>$countries,
				'states_bill'=>$states_bill,
				'cities_bill'=>$cities_bill,
				'compData' => $compData,
				'custData' => $custData,
				//'states_ship'=>$states_ship,
				//'cities_ship'=>$cities_ship,
				'invoiceNumbers'=>$invoiceNumbers,
				'carriageout' => $carriageout,
				'carriageId' => $carriageId,
				//'states' => $states
			]);
	}

	public function update_carriageoutwards(Request $request)
	{
		//echo "<pre>";print_r($request);exit;
		$carriageId = $request->id;

        $validation = $this->validatorcarriageout($request->all());
            if ($validation->fails())  {
                return response()->json($validation->errors()->toArray());
            }
            else{
          //start update customers
          $update = DB::table('carriageouts')
              ->where('id', $carriageId)
              ->update(
                array(
                        'added_by' => currentOwnerId(),
						'inv_num' => $request->inv_num,
						'inv_date' => $request->inv_date,
						'seller_name' =>$request->seller_name,
						'seller_contact' => $request->seller_contact,
						'seller_email' =>$request->seller_email,
						'seller_pan' => $request->seller_pan,
						'seller_gst' => $request->seller_gst,
						'seller_addone' => $request->seller_addone,
						'seller_addtwo'=>$request->seller_addtwo,
						'seller_state' => $request->seller_state,
						'seller_city' => $request->seller_city,
						'seller_pin'=>$request->seller_pin,
						'cust_name' => $request->cust_name,
						'cust_contact' => $request->cust_contact,
						'cust_email' => $request->cust_email,
						'cust_pan' => $request->cust_pan,

						'cust_gst' => $request->cust_gst,
						'cust_order_no' => $request->cust_order_no,
						'cust_dispatch_no' => $request->cust_dispatch_no,
						'disp_through' => $request->disp_through,
						'other_dispa_det' => $request->other_dispa_det,
						'terms_delivery' => $request->terms_delivery,
						'cust_addone' => $request->cust_addone,
						'cust_addtwo' => $request->cust_addtwo,
						'cust_state' => $request->cust_state,
						'cust_city' => $request->cust_city,
						'cust_pin' => $request->cust_pin,
						'other_quantity' => $request->other_quantity,
						'other_transport' => $request->other_transport,
						'other_transport_cost' => $request->other_transport_cost,
						'other_insurance_date' => $request->other_insurance_date,

						'tdsApplicable' => $request->tdsApplicable,
						'tds_percentage' => $request->tds_percentage,
						'gstApplicable' => $request->gstApplicable,
						'other_hsn_sac_code' => $request->other_hsn_sac_code,
						'other_gst_rate' => $request->other_gst_rate,

						'other_gst_mode' => $request->other_gst_mode,
						'other_pay_date' => $request->other_pay_date,
						'other_mod_pay' => $request->other_mod_pay,
						'other_pay_method' => $request->other_pay_method,
						'pay_status' => $request->pay_status,
						'other_total_amount' => $request->other_total_amount,

						'other_adv_amount' => $request->other_adv_amount,
						'other_due_amount' => $request->other_due_amount,
						'other_refe_no' => $request->other_refe_no,
						'other_approve_by' => $request->other_approve_by,
						'other_term' => $request->other_term,
						'other_uplode_doc' => isset($request->other_uplode_doc)?$request->other_uplode_doc:"",
						'created_at' => date('Y-m-d H:i:s'),


                )
              );
			  $msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/carriage-outward'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
		}
	}

	public function viewcarriageoutwards($carriageId)
	{
		//die('hello');
		$carriageId = base64_decode($carriageId);
		$userId = currentOwnerId();
		$carriageout = DB::table('carriageouts')
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
		//echo "<pre>";print_r($carriageout);exit;
		$carriageout = $carriageout[0];
		$countries = Country::where('id', '>', '0')->get();
        $states_bill = State::where('id', '=', $carriageout->cust_state)->get();
		$cities_bill = City::where('id', '=', $carriageout->cust_city)->get();

		//$states_ship = State::where('country_id', '=', $carriageout->cust_ship_country)->get();
		//$cities_ship = City::where('state_id', '=', $carriageout->cust_ship_state)->get();
		//$states = State::where('country_id', '=', 101)->get();
		//--- Get Invoice list from saler table ------
		$invoiceNumbers = DB::table('sales')
			->select('id', 'inv_num')
			->where('added_by', '=', $userId)
			->get();


		return view('User.view-carriage-outwards')->with([
				'countries'=>$countries,
				'states_bill'=>$states_bill,
				'cities_bill'=>$cities_bill,
				'compData' => $compData,
				'custData' => $custData,
				//'states_ship'=>$states_ship,
				//'cities_ship'=>$cities_ship,
				'invoiceNumbers'=>$invoiceNumbers,
				'carriageout' => $carriageout,
				'carriageId' => $carriageId,
				//'states' => $states
			]);
	}

	public function delcarrout(Request $request)
	{
		$delcarrout = DB::table('carriageouts')->where('id', $request->id)->delete();

		if ($delcarrout) {
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/carriage-outward'),
				'message' => 'Carriage-outward deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/carriage-outward'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
	}

	public function SalesCreditDebit(request $request)
    {

		//$this->middleware('auth');
		$title = 'Sales Credit Dabit Notes';
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');

		

		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = getAccessCompanyId($request);
			
		}
		
		//end ca-accountant access

		if (Auth::user()->u_type == 1) { //ca
		
			// $sales =  DB::table('vouchers')
			// 	->select(DB::raw('vouchers.*,company_profiles.comp_name,ca_assigns.ca_id'))
			// 	->leftJoin('company_profiles', 'vouchers.added_by', '=', 'company_profiles.userId')
			// 	->leftJoin('ca_assigns', 'vouchers.added_by', '=', 'ca_assigns.comp_id')
			// 	->where('ca_assigns.ca_id', '=', $userId)
			// 	->where('ca_assigns.ca_assign_status', '=', 1)
			// 	->orderBy('id', 'DESC')->paginate(10);

			$sales = DB::table('vouchers')
				->select(DB::raw('vouchers.*, company_profiles.comp_name'))
				->leftJoin('company_profiles', 'vouchers.added_by', '=', 'company_profiles.userId')
				->where('vouchers.added_by', '=', $userId)
				->orderBy('vouchers.id', 'DESC')
				->paginate(10);


		} else if (Auth::user()->u_type == 4) { //ca employee
			// $sales =  DB::table('vouchers')
			// 	->select(DB::raw('vouchers.*,company_profiles.comp_name,ca_assigns.ca_id'))
			// 	->leftJoin('company_profiles', 'vouchers.added_by', '=', 'company_profiles.userId')
			// 	->leftJoin('ca_assigns', 'vouchers.added_by', '=', 'ca_assigns.comp_id')
			// 	->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
			// 	->where('ca_assigns.ca_assign_status', '=', 1)
			// 	->orderBy('id', 'DESC')->paginate(10);
			$sales = DB::table('vouchers')
					->select(DB::raw('vouchers.*, company_profiles.comp_name'))
					->leftJoin('company_profiles', 'vouchers.added_by', '=', 'company_profiles.userId')
					->where('vouchers.added_by', '=', $userId)
					->orderBy('vouchers.id', 'DESC')
					->paginate(10);

		} elseif (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) { //user
			$sales =  DB::table('vouchers')
				->select(DB::raw('vouchers.*,company_profiles.comp_name'))
				->leftJoin('company_profiles', 'vouchers.added_by', '=', 'company_profiles.userId')
				->where('vouchers.added_by', '=', $userId)
				->orderBy('vouchers.id', 'DESC')->paginate(10);
		} elseif (Auth::user()->u_type == 3) { //admin
			$sales =  DB::table('vouchers')
				->select(DB::raw('vouchers.*,company_profiles.comp_name'))
				->leftJoin('company_profiles', 'vouchers.added_by', '=', 'company_profiles.userId')
				->orderBy('id', 'DESC')->paginate(10);
		}
		$sales_pagination = $sales;

		$array = array();
		foreach ($sales as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['invoice_number'] = $val->invoice_number;
			$array[$val->id]['inv_num'] = $val->inv_num;
			$array[$val->id]['inv_date'] = $val->inv_date;
			$customerName =  DB::table('customers')
				->select(DB::raw('customers.cust_name,customers.cust_phone'))
				->where('id', '=', $val->v_name)
				->get();
			$array[$val->id]['cust_name'] = isset($customerName[0]->cust_name) ? $customerName[0]->cust_name : "";
			$array[$val->id]['cust_phone'] = isset($customerName[0]->cust_phone) ? $customerName[0]->cust_phone : "";
			$array[$val->id]['v_num'] = $val->v_num;
			$array[$val->id]['note_type'] = $val->note_type;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['is_paid'] = $val->is_paid;
			$array[$val->id]['total_amt'] = $val->total_amt;
			$array[$val->id]['adjusted_amount'] = $val->adjusted_amount;
		}
		$sales = json_decode(json_encode($array));
        return view('User.sales-credit-debit')->with([
			'title' => $title,
			'sales' => $sales,
			'sales_pagination' => $sales_pagination,
		]);
    }

    public function AddSalesCreditDebit()
    {
		$vNo = DB::table('vouchers')
			->select(DB::raw('MAX(id) as id'))
			->get();
		$vNo = isset($vNo[0]->id) ? $vNo[0]->id : 0;
		$vNo = Helper::invoice_num($vNo + 1, 7, "VN-");
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		$compData = DB::table('company_profiles')
		->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
		->where('company_profiles.userId', '=', $userId)
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', $userId)
			->where('customers.status', '=', 1)
			->get();
		$comp_name = isset($compData[0]->comp_name) ? $compData[0]->comp_name : "";
		$comp_phone = isset($compData[0]->comp_phone) ? $compData[0]->comp_phone : "";
		$countries = Country::where('id', '>', '0')->get();

		$states_bill = State::where('id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:101)->get();
		$cities_bill = City::where('state_id', '=', isset($compData[0]->comp_bill_state)?$compData[0]->comp_bill_state:0)->get();
		//print_r($cities_bill);
		$states_ship = State::where('country_id', '=', isset($compDetails->comp_ship_country) ? $compDetails->comp_ship_country : 0)->get();
		$cities_ship = City::where('state_id', '=', isset($compDetails->comp_ship_state) ? $compDetails->comp_ship_state : 0)->get();

		//--- Get Invoice list from saler table ------
		$invoiceNumbers = DB::table('sales')
			->select('id', 'inv_num')
			->where('added_by', '=', $userId)
			->get();

		$purposes_of_tds = DB::table('purposes_of_tds')
						->get();
		return view('User.add-sales-credit-debit')->with([
			'vNo' => $vNo,
			'comp_name' => $comp_name,
			'custData' => $custData,
			'comp_phone' => $comp_phone,
			'countries' => $countries,
			'states_bill' => $states_bill,
			'cities_bill' => $cities_bill,
			'states_ship' => $states_ship,
			'cities_ship' => $cities_ship,
			'custData' => $custData,
			'invoiceNumbers' => $invoiceNumbers,
			'compData'  => $compData,
			'purposes_of_tds' => $purposes_of_tds
		]);

    }

	protected function validatorSalesCredit(array $data)
	{
		return Validator::make($data, [
			'inv_num' => 'required',
			'inv_date' => 'required',
			//'seller_name' => 'required',
			'seller_addone' => 'required',
			//'seller_country' => 'required',
			'seller_state' => 'required',
			'seller_city' => 'required',
			'seller_pin' => 'required',
			'v_name' => 'required',
			'note_type' => 'required',
			'note_date' => 'required',
			'reason_issuance' => 'required',
			//'otherIssuance' => $reason_issuance_other,
			'v_num' => 'required',
			//'credit_debit_amount' => 'required',
			//'adjusted_amount' => 'required',
			'challan_no' => 'required',
			'challan_date' => 'required',
			//'doc_no' => 'required',
			//'doc_date' => 'required',
		]);
	}

	protected function createSalesCredit(array $data)
	{
		//echo "<pre>";print_r($data);exit;

		$invoiceNo = DB::table('vouchers')
			->select(DB::raw('MAX(id) as id'))
			->get();
		//$vNo = isset($vNo[0]->id)?$vNo[0]->id:0;
		//$vNo = Helper::invoice_num($vNo+1,7,"VN-");

		$invoiceNo = isset($invoiceNo[0]->id) ? $invoiceNo[0]->id : 0;
		$invoiceNo = Helper::invoice_num($invoiceNo + 1, 7, "VN-");

		return Vouchers::create([
			'added_by' => currentOwnerId(),
			'inv_num' => $invoiceNo,
			'invoice_number' => $data['inv_num'],
			'inv_date' => $data['inv_date'],
			'seller_name' => $data['seller_name'],
			'seller_addone' => $data['seller_addone'],
			'seller_addtwo' => isset($data['seller_addtwo']) ? $data['seller_addtwo'] : "",
			'seller_country' => 101,
			'seller_state' => $data['seller_state'],
			'seller_city' => $data['seller_city'],
			'seller_pin' => $data['seller_pin'],
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

	public function save_sales_invoice_creditdebit(Request $request)
	{

		//echo "<pre>";print_r($request->file('prod_image'));exit;
		//$input = Input::all();
		//dd($input);
		$validation = $this->validatorSalesCredit($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			$insertSales = $this->createSalesCredit($request->all());
			$sId = DB::getPdo()->lastInsertId();

			if ($file = $request->hasFile('voucher_doc')) {
				$file = $request->file('voucher_doc');
				$fileName = time() . '-' . $file->getClientOriginalName();
				$destinationPath = public_path('uploads/sales-credit-debit');
				$file->move($destinationPath, $fileName);

				DB::table('vouchers')
					->where('id', $sId)
					->update(['voucher_doc' => $fileName]);
			}

			if ($insertSales) {
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					//'redirect' => url('/edit-sales-credit-debit/'.base64_encode($sId)),
					'redirect' => url('/sale-credit-debit'),
					'message' => 'Record added successfully'
				);
				return response()->json($msg);
			} else {
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

	public function view_sales_invoice_credit_debit($sId)
	{	
		// echo "<pre>";print_r($sId);exit;

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {

			// echo "<pre>";print_r(session('compId'));exit;
			$uid = session('compId'); //ca-accountant access
		}

		// echo "<pre>";
		// echo $uid;
		// exit;
		
		checkCoreAccess('Accounting');
		$sId = base64_decode($sId);
		$sales = DB::table('vouchers')
			->where('id', '=', $sId)
			->get();
		$sales = $sales[0];
		$compData = DB::table('company_profiles')
		->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,gst_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
		->where('company_profiles.userId', '=', $uid)
			->get();
		$compData=$compData[0];
		$products = DB::table('products')
			->select(DB::raw('products.id,products.item_name'))
			->where('added_by', '=', $uid)
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', $uid)
			->where('customers.status', '=', 1)
			->get();

			$purposes_of_tds = DB::table('purposes_of_tds')
					->get();
		//echo "<pre>";print_r($sales);exit;
		$countries = Country::where('id', '>', '0')->get();
		$states_seller = State::where('id', '=', isset($sales->seller_state) ? $sales->seller_state : 0)->get();
		$cities_seller = City::where('state_id', '=', isset($sales->seller_state) ? $sales->seller_state : 0)->get();
		//$vouchers_values = $this->items_sales_list_credit_debit($sId);
		return view('User.view-sales-credit-debit')->with([
			'products' => $products,
			'sales' => $sales,
			'compData'=>$compData,
			'custData' => $custData,
			'countries' => $countries,
			'states_seller' => $states_seller,
			'cities_seller' => $cities_seller,
			'purposes_of_tds' => $purposes_of_tds,
			//'vouchers_values' => $vouchers_values,
			'sId' => $sId
		]);
	}

	public function edit_sales_invoice_credit_debit($sId)
	{
		checkCoreAccess('Accounting');
		$sId = base64_decode($sId);
		$sales = DB::table('vouchers')
			->where('id', '=', $sId)
			->get();
		$sales = $sales[0];
		$compData = DB::table('company_profiles')
		->select(DB::raw('comp_name,comp_phone,comp_email,comp_pan_no,gst_no,comp_bill_addone,comp_bill_addtwo,comp_bill_pin,comp_bill_state,comp_bill_city'))
		->where('company_profiles.userId', '=', currentOwnerId())
			->get();
		$compData=$compData[0];
		$products = DB::table('products')
			->select(DB::raw('products.id,products.item_name'))
			->where('added_by', '=', currentOwnerId())
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', currentOwnerId())
			->where('customers.status', '=', 1)
			->get();

			$purposes_of_tds = DB::table('purposes_of_tds')
			->get();


		//echo "<pre>";print_r($sales);exit;
		$countries = Country::where('id', '>', '0')->get();
		$states_seller = State::where('id', '=', isset($sales->seller_state) ? $sales->seller_state : 0)->get();
		$cities_seller = City::where('state_id', '=', isset($sales->seller_state) ? $sales->seller_state : 0)->get();

		//$vouchers_values = $this->items_sales_list_credit_debit($sId);
		return view('User.edit-sales-credit-debit')->with([
			'products' => $products,
			'sales' => $sales,
			'compData'=>$compData,
			'custData' => $custData,
			'countries' => $countries,
			'states_seller' => $states_seller,
			'cities_seller' => $cities_seller,
			'purposes_of_tds' => $purposes_of_tds,
			//'vouchers_values' => $vouchers_values,
			'sId' => $sId
		]);
	}

	public function update_sales_invoice_creditdebit(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;

		$validation = $this->validatorSalesCredit($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			//start update
			if ($file = $request->hasFile('voucher_doc')) {				
				$file = $request->file('voucher_doc');
				$fileName = time() . '-' . $file->getClientOriginalName();
				$destinationPath = public_path('uploads/sales-credit-debit');
				$file->move($destinationPath, $fileName);

				DB::table('vouchers')
					->where('id', $sId)
					->update(['voucher_doc' => $fileName]);
			}
			if ($request->reason_issuance == 'other') {
				$otherIssuance = $request->otherIssuance;
			} else {
				$otherIssuance = "";
			}
			$update = DB::table('vouchers')
				->where('id', $sId)
				->update(
					array(
						'invoice_number' => $request->inv_num,

						'inv_date' => $request->inv_date,
						'seller_name' => $request->seller_name,
						'seller_addone' => $request->seller_addone,
						'seller_addtwo' => isset($request->seller_addtwo) ? $request->seller_addtwo : "",
						'seller_country' => 101,
						'seller_state' => $request->seller_state,
						'seller_city' => $request->seller_city,
						'seller_pin' => $request->seller_pin,
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
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/sale-credit-debit'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
			//end update item

		}
	}

	public function delInvoiceCreditDebit(Request $request)
	{
		$data = DB::table('vouchers')->where('id', $request->id)->first();
		$oldData = [
					'record' => [
						'inv_num'  => $data->inv_num ?? null,
						'inv_date' => $data->inv_date ?? null,
					]
				];
		$delInvoice = DB::table('vouchers')->where('id', $request->id)->delete();

		if ($delInvoice) {
			//AUDIT LOG ENTRY
            AuditLogger::logEntry(
                action: 'delete',
                module: 'Sales Credit & Debit',
                description: "Sales Credit & Debit deleted: {$data->inv_num}",
                oldData: $oldData,
                newData: null
            );
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/sale-credit-debit'),
				'message' => 'sale-credit-debit voucher deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/sale-credit-debit'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
	}
	public function Quotation() {
        return view('User.sales-quotation');
    }
    public function CreateQuotation() {
        return view('User.create-sales-quotation');
    }
    public function ProformaInvoice() {
        return view('User.proforma-invoice');
    }
    public function CreateProformaInvoice() {
        return view('User.create-proforma-invoice');
    }

}
