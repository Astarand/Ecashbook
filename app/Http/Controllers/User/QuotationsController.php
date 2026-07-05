<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Quotations;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Carriageout;
use App\Models\Vouchers;

use Redirect;
use DB;
use Auth;
// use Validator;
use App\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Helpers\AuditLogger;

class QuotationsController extends Controller
{
    public function __construct()
	{
		//$this->middleware('auth');
	}
	public function quotationInvoiceIndex(Request $request)
	{
		$title = 'Quotation Invoice';
		$userId = currentOwnerId();
		checkCoreAccess('Sales & Invoicing');
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		if (Auth::user()->u_type != 3 && Auth::user()->u_type != 6) { //user
			$quotations = DB::table('quotations')
						->leftJoin('company_profiles', 'quotations.added_by', '=', 'company_profiles.userId')
						->leftJoin('proprietorship_profiles', 'quotations.propId', '=', 'proprietorship_profiles.id')
						->leftJoin('quotations_values', 'quotations_values.sid', '=', 'quotations.id')
						->select(
							'quotations.id',
							'quotations.inv_num',
							'quotations.inv_name',
							'quotations.inv_date',
							'quotations.status',
							'quotations.signed_pdf',
							'quotations.signed_pdf_status',
							'company_profiles.comp_name',
							'proprietorship_profiles.comp_name as prop_name',
							DB::raw('SUM(quotations_values.quantity) AS total_qty'),
							DB::raw('SUM(
									COALESCE(quotations_values.amount, 0) +
									COALESCE(quotations_values.tax_amt, 0) +
									COALESCE(quotations_values.gov_pay, 0) +
									COALESCE(quotations_values.ser_pay, 0)
								) AS total_amount')
						)
						->groupBy(
							'quotations.id',
							'quotations.inv_num',
							'quotations.inv_name',
							'quotations.inv_date',
							'quotations.status',
							'quotations.signed_pdf',
							'quotations.signed_pdf_status',
							'company_profiles.comp_name',
							'proprietorship_profiles.comp_name'
						)
						->where('quotations.added_by', $userId) 
						->orderBy('quotations.id', 'DESC')
						->paginate(10);
		} elseif (Auth::user()->u_type == 3) { //admin
			$quotations = DB::table('quotations')
						->leftJoin('company_profiles', 'quotations.added_by', '=', 'company_profiles.userId')
						->leftJoin('quotations_values', 'quotations_values.sid', '=', 'quotations.id')
						->select(
							'quotations.id',
							'quotations.inv_num',
							'quotations.inv_name',
							'quotations.inv_date',
							'quotations.status',
							'company_profiles.comp_name',
							DB::raw('SUM(quotations_values.quantity) AS total_qty'),
							DB::raw('SUM(
									COALESCE(quotations_values.amount, 0) +
									COALESCE(quotations_values.tax_amt, 0) +
									COALESCE(quotations_values.gov_pay, 0) +
									COALESCE(quotations_values.ser_pay, 0)
								) AS total_amount')
						)
						->groupBy(
							'quotations.id',
							'quotations.inv_num',
							'quotations.inv_name',
							'quotations.inv_date',
							'quotations.status',
							'company_profiles.comp_name'
						)
						->orderBy('quotations.id', 'DESC')
						->paginate(10);
		}
		$sales_pagination = $quotations;
		//echo "<pre>";print_r($quotations);exit;
		$array = array();
		foreach ($quotations as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['prop_name'] = $val->prop_name;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['inv_num'] = $val->inv_num;
			$array[$val->id]['inv_date'] = $val->inv_date;
			$array[$val->id]['total_qty'] = $val->total_qty;
			$array[$val->id]['total_amount'] = $val->total_amount;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['signed_pdf'] = $val->signed_pdf;
			$array[$val->id]['signed_pdf_status'] = $val->signed_pdf_status;

			$customerName =  DB::table('customers')
				->select(DB::raw('customers.cust_name,customers.cust_phone'))
				->where('id', '=', $val->inv_name)
				->get();
			$array[$val->id]['cust_name'] = isset($customerName[0]->cust_name) ? $customerName[0]->cust_name : "";
			$array[$val->id]['cust_phone'] = isset($customerName[0]->cust_phone) ? $customerName[0]->cust_phone : "";
			
		}
		$quotations = json_decode(json_encode($array));


		//echo "<pre>"; print_r($quotations);exit;
		return view('User.quotations.sales-quotation')->with([
			'title' => $title,
			'sales' => $quotations,
			'sales_pagination' => $sales_pagination,
			'quotation_create_status' => $this->quotation_create_status(),
		]);
	}

	//-----------Check Company Information FIll or Not -------

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
	
	private function getNextNumber($table, $column, $where, $configLastPart = null)
	{
		$last = DB::table($table)
			->where($where)
			->select(DB::raw("MAX(CAST(SUBSTRING_INDEX($column,'/',-1) AS UNSIGNED)) as max_num"))
			->value('max_num');

		if ($last) {
			return $last + 1;
		}

		// fallback from config
		if ($configLastPart && is_numeric($configLastPart)) {
			return ((int)$configLastPart) + 1;
		}

		return 1;
	}
	
	public function generateQuotationNumber($userId)
	{
		$this->companyInfoFill($userId);

		$company = DB::table('company_profiles')
			->where('userId', $userId)
			->first(['comp_name', 'comp_quo_digits']);

		if (!$company) return false;

		/* FY */
		$year = date('Y');
		$month = date('n');

		$fyStart = substr($month >= 4 ? $year : $year - 1, 2);
		$fyEnd   = substr($month >= 4 ? $year + 1 : $year, 2);

		$financialYear = $fyStart . '-' . $fyEnd;

		/* PREFIX */
		if (!empty($company->comp_quo_digits)) {
			$base = trim($company->comp_quo_digits, '/');
			$parts = explode('/', $base);

			if (is_numeric(end($parts))) {
				$configLast = array_pop($parts);
			} else {
				$configLast = null;
			}

			$parts = array_values(array_filter($parts, fn($p) => !preg_match('/^\d{2}-\d{2}$/', $p)));
			array_splice($parts, 1, 0, $financialYear);

			$prefix = implode('/', $parts) . '/';
		} else {
			$configLast = null;
			$prefix = strtoupper(substr($company->comp_name, 0, 3))
				. '/QT/' . $financialYear . '/';
		}

		$lastPart = $configLast;

		$next = $this->getNextNumber(
			'quotations',
			'inv_num',
			[['added_by', '=', $userId]],
			$lastPart
		);

		return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
	}
	
	public function generateQuotationNumberProprietorship($id, $userId)
	{
		$company = DB::table('proprietorship_profiles')
			->where('id', $id)
			->first(['comp_name', 'comp_quo_digits']);

		if (!$company) return false;

		/* FY */
		$year = date('Y');
		$month = date('n');

		$fyStart = substr($month >= 4 ? $year : $year - 1, 2);
		$fyEnd   = substr($month >= 4 ? $year + 1 : $year, 2);

		$financialYear = $fyStart . '-' . $fyEnd;

		/* PREFIX */
		if (!empty($company->comp_quo_digits)) {
			$base = trim($company->comp_quo_digits, '/');
			$parts = explode('/', $base);

			if (is_numeric(end($parts))) {
				$configLast = array_pop($parts);
			} else {
				$configLast = null;
			}

			$parts = array_values(array_filter($parts, fn($p) => !preg_match('/^\d{2}-\d{2}$/', $p)));
			array_splice($parts, 1, 0, $financialYear);

			$prefix = implode('/', $parts) . '/';
		} else {
			$configLast = null;
			$prefix = strtoupper(substr($company->comp_name, 0, 3))
				. '/QT/' . $financialYear . '/';
		}

		$next = $this->getNextNumber(
			'quotations',
			'inv_num',
			[
				['added_by', '=', $userId],
				['propId', '=', $id]
			],
			$configLast
		);

		return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
	}
	
	public function quotation_create_status() {
		$userId = currentOwnerId();

		$count = DB::table('quotations')
			->where('added_by', $userId)
			->where(function($query) {
				$query->whereNull('pay_status')->orWhere('pay_status', '');
			})
			->count();

			if($count >2){    //---------- count empty or null payment status -------
				return "false";
			}else{
				return "true";
			}

		// return $count < 3 ? true : false;
	}



    public function CreateQuotationInvoices()
	{

		$userId = currentOwnerId();
		checkCoreAccess('Biz Operations');
		$invoiceNo = DB::table('quotations')
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

		$invoiceNo = $this->generateQuotationNumber($userId);

		// echo "<pre>"; print_r($invoiceNo); exit;

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

		$quotation_create_status = $this->quotation_create_status();
		
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

		return view('User.quotations.create-quotation-invoice')->with([
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
			'comp_gst_no' => $comp_gst_no,
			'quotation_create_status' => $quotation_create_status,
			'proprietorships' => $proprietorships 
		]);
	}


    protected function getQuotationcust(Request $request)
	{
		//echo "<pre>"; print_r($request); exit;
		$id = $request->id;
		$salesTableID = $request->salesTableID;
		$result = Quotations::query()
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
		//echo "<pre>"; print_r($data);exit;
		/*if($data['transport_type'] == 'Other'){
			$transport_type_other = "required";
		}else{
			$transport_type_other = "";
		}*/
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
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$propId = $data['propId'];
		if(!empty($propId)) {
			$invoiceNo = $this->generateQuotationNumberProprietorship($propId,$userId);
		} else {
			$invoiceNo = $this->generateQuotationNumber($userId);
		}

		return Quotations::create([
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

    public function save_quotation_invoice(Request $request)
	{
		if ( $this->quotation_create_status() == "false") {
			$msg = array(
				'message' => 'Unable to create quotation! Please complete your previous invoices first.'
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
						'redirect' => url('/edit-quotation-invoice/' . base64_encode($sId)),
						'message' => 'Quotation added successfully'
					);
					return response()->json($msg);
				} else {
					$msg = array(
						'status' => 'error',
						'class' => 'err',
						'redirect' => url('/'),
						'message' => 'Quotation add failed'
					);
					return response()->json($msg);
				}
			}

		}



	}


    public function items_quotation_list($sid)
	{

		$array = array();
		$sales_values = DB::table('quotations_values')
			->select(DB::raw('quotations_values.*'))
			->where('sid', '=', $sid)
			->get();

			$sales_dataee = DB::table('quotations')
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

	public function edit_quotation_invoice($sId)
	{

		if (Auth::user()->u_type == 1) {
			return redirect('/sales-quotation');
		}
		checkCoreAccess('Biz Operations');
		$sId = base64_decode($sId);
		$sales = DB::table('quotations')
			->where('id', '=', $sId)
			->get();

		$sales = $sales[0];
		$products = DB::table('products')
			->select(DB::raw('products.id,products.item_name'))
			->where('added_by', '=', currentOwnerId())
			->get();
		$custData = DB::table('customers')
			->select(DB::raw('customers.*'))
			->where('customers.userId', '=', currentOwnerId())
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

		$sales_values = $this->items_quotation_list($sId);
		return view('User.quotations.edit-quotation-invoice')->with([
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

	public function update_quotation_customer(Request $request)
	{
		$sId = $request->id;
		$update = DB::table('quotations')
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

	public function view_quotation_invoice($sId)
	{
		
		$sId = base64_decode($sId);
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		checkCoreAccess('Biz Operations');
		
		$sales = DB::table('quotations')
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

		$sales_values = $this->items_quotation_list($sId);
		return view('User.quotations.view-quotation-invoice')->with([
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


	public function update_quotation_invoice(Request $request)
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
			$update = DB::table('quotations')
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

	

	public function updateGstType($sid)
	{
		$custId = DB::table('quotations')
			->select(DB::raw('quotations.inv_name'))
			->where('id', '=', $sid)
			->get();
		$cust_gst = DB::table('customers')
			->select(DB::raw('customers.cust_gst_no'))
			->where('id', '=', $custId[0]->inv_name)
			->get();

		$taxableInvTotal = DB::table('quotations_values')
			->join('quotations', 'quotations.id', '=', 'quotations_values.sid')
			->where('quotations.id', '=', $sid)
			->select(DB::raw('
				SUM(quotations_values.amount - quotations_values.disc_amt) as taxableInvTotal
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
		$update = DB::table('quotations')
			->where('id', '=', $sid)
			->update(
				array(
					'gst_type' => $gst_type,
				)
			);
	}
	
	public function saveProductAndQuotation(Request $request)
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

			//INSERT INTO quotations_values
			DB::table('quotations_values')->insert([
				'sid'       => $sid, 
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
			$quotations_values = $this->items_quotation_list($sid);
			$this->updateGstType($sid);
			return view('User.quotations.ajax-quotation-invoice-display')->with([
				'quotations_values' => $quotations_values,
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

	public function quotation_items_display(Request $request)
	{
		//echo "<pre>"; print_r($request);exit;
		$sid = $request->sId;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$quotations_values = DB::table('quotations_values')
			->select(DB::raw('quotations_values.id,quotations_values.quantity'))
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

		if (count($quotations_values) == 0) {
			$quantity = 1;
		} else {
			$quantity = $quotations_values[0]->quantity;
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
		$gst_rate = $product[0]->gst_rate;
		$gov_pay = $product[0]->gov_pay ?? 0;
		$ser_pay = $product[0]->ser_pay ?? 0;
		$tax_amt = ($amount * $gst_rate) / 100;
		$tax_type = "N/A";

		if (count($quotations_values) == 0) {
			$values = array('sid' => $sid, 'uid' => $uid, 'prod_id' => $prod_id, 'quantity' => $quantity, 'rate' => $rate, 'disc' => $disc, 'disc_type' => $disc_type, 'disc_amt' => $disc_amt, 'tax_amt' => $tax_amt, 'amount' => $amount, 'tax_type' => $tax_type, 'gst_rate' => $gst_rate, 'gov_pay' => $gov_pay, 'ser_pay' => $ser_pay,'gst_allocation_text' => $gst_allocation_text, 'billing_type' => $billing_type, 'prod_gov_fee' => $prod_gov_fee, 'gst_trans' => $gst_trans);
			$insertInvoice = DB::table('quotations_values')->insert($values);
		} else {
			$update = DB::table('quotations_values')
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
						'gst_allocation_text' => $request->gst_allocation_text,
						'billing_type' => $billing_type,
						'prod_gov_fee' => $prod_gov_fee,
						'gst_trans' => $gst_trans
					)
				);
		}


		$quotations_values = $this->items_quotation_list($sid);
		//echo "<pre>"; print_r($quotations_values);exit;
		$this->updateGstType($sid);
		return view('User.quotations.ajax-quotation-invoice-display')->with([
			'quotations_values' => $quotations_values,
		]);
	}

	public function update_quotation_item_rate(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$sid = $request->sid;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$sales_data = DB::table('quotations_values')
			->select(DB::raw('quotations_values.sid,quotations_values.quantity,quotations_values.disc,quotations_values.disc_type,quotations_values.gst_rate'))
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

		$update = DB::table('quotations_values')
			->where('id', $id)
			->update(
				array(
					'rate' => $productRate,
					'disc_amt' => $disc_amt,
					'tax_amt' => $tax_amt,
					'amount' => $amount
				)
			);

		$quotations_values = $this->items_quotation_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($quotations_values);exit;
		$this->updateGstType($sid);
		return view('User.quotations.ajax-quotation-invoice-display')->with([
			'quotations_values' => $quotations_values,
		]);
	}


	public function update_quotation_invoice_final(Request $request)
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
			$update = DB::table('quotations')
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
			$update = DB::table('quotations')
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
			'redirect' => url('/sales-quotation'),
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
			// 'advance_amount' => 'numeric',
			// 'due_amount' => 'numeric',
			'order_date' => 'required',
			'disp_through' => 'required',
			'other_dispa_det' => $other_dispa_det,
		]);
	}

	public function update_quotation_other(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;

		$validation = $this->validatorOther($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {
			//Payment full-advance logic
			$payStatus = $request->pay_status;
			$totalAmount   = $request->total_amount ?? 0;
			$advanceAmount = $request->advance_amount ?? 0;
			$dueAmount     = $request->due_amount ?? 0;
			$adjustedAmount = $request->adjusted_amount ?? 0;
			if ($payStatus === 'Full') {
				$adjustedAmount = $totalAmount;   // Full → adjusted = total
				$advanceAmount  = 0;
				$dueAmount      = 0;
			}
			if ($payStatus === 'Partial') {
				$adjustedAmount = 0;       
				$dueAmount = $totalAmount - $advanceAmount;
			}
			
			$update = DB::table('quotations')
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
						'order_date' => isset($request->order_date) ? $request->order_date : "",
						'supplier_refno' => isset($request->supplier_refno) ? $request->supplier_refno : "",
						'other_refno' => isset($request->other_refno) ? $request->other_refno : "",
						'dispa_docno_one' => isset($request->dispa_docno_one) ? $request->dispa_docno_one : "",
						'dispa_docno_two' => isset($request->dispa_docno_two) ? $request->dispa_docno_two : "",
						'disp_through' => $request->disp_through,
						'other_dispa_det' => isset($request->other_dispa_det) ? $request->other_dispa_det : "",
						'terms_delivery' => isset($request->terms_delivery) ? $request->terms_delivery : ""
					)
				);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/sales-quotation'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
		}
	}

	public function delQuotationInvoice(Request $request)
	{
		//FETCH OLD DATA (BEFORE DELETE)
		$data = DB::table('quotations')->where('id', $request->id)->first();
		$oldData = [
					'record' => [
						'inv_num'  => $data->inv_num ?? null,
						'inv_date' => $data->inv_date ?? null,
					]
				];
	
		$delInvoice = DB::table('quotations')->where('id', $request->id)->delete();
		$delInvoiceItemValue = DB::table('quotations_values')->where('sid', $request->id)->delete();
		if ($delInvoice) {
			//AUDIT LOG ENTRY
            AuditLogger::logEntry(
                action: 'delete',
                module: 'Sales Quotation',
                description: "Sales Quotation deleted: {$data->inv_num}",
                oldData: $oldData,
                newData: null
            );
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/sales-quotation'),
				'message' => 'Quotation invoice deleted successfully.'
			);
			return response()->json($msg);
		} else {
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/sales-quotation'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
	}

	public function update_quotation_item_quantity(Request $request)
	{

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$sid = $request->sid;
		$uid = currentOwnerId();

		$sales_data = DB::table('quotations_values')
			->select(DB::raw('quotations_values.sid,quotations_values.rate,quotations_values.disc,quotations_values.disc_type,quotations_values.gst_rate'))
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

		$update = DB::table('quotations_values')
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

		$quotations_values = $this->items_quotation_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($quotations_values);exit;
		$this->updateGstType($sid);
		return view('User.quotations.ajax-quotation-invoice-display')->with([
			'quotations_values' => $quotations_values,
		]);
	}

	public function fetchQuotationItem(Request $request)
	{
		$sid = $request->id;
		$quotations_values = DB::table('quotations_values')
			->select(DB::raw('quotations_values.*'))
			->where('quotations_values.id', '=', $sid)
			->get();
		//echo "<pre>"; print_r($quotations_values);exit;
		$array = array();
		foreach ($quotations_values as $k => $val) {
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
				$array[$val->id]['item_name'] = isset($item[0]->item_name) ? $item[0]->item_name : "";
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
		$quotations_values = (json_encode($array));
		//echo "<pre>";print_r($quotations_values);exit;
		return $quotations_values;
	}

	public function delQuotationItem(Request $request)
	{
		//echo "<pre>"; print_r($_POST);exit;
		$delSalesItem = DB::table('quotations_values')->where('id', $request->id)->delete();
		$quotations_values = $this->items_quotation_list($request->sid);

		//echo "<pre>"; print_r($quotations_values);exit;
		return view('User.quotations.ajax-quotation-invoice-display')->with([
			'quotations_values' => $quotations_values,
		]);
	}


	public function updateQuotationStatus(Request $request)
	{
		DB::table('quotations')
			->where('id', $request->id)
			->update([
				'status' => $request->status
			]);

		return response()->json(['success' => true]);
	}



}
