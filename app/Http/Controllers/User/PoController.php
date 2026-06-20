<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Puos;
use App\Models\Puo_values;
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

class PoController extends Controller
{
    public function PurchaseOrder(Request $request)
    {
        $title = 'Purchase Invoice';
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		if(Auth::user()->u_type != 3 && Auth::user()->u_type != 6){ //user
			$sales = DB::table('puos')
					->select(DB::raw('puos.*, company_profiles.comp_name,proprietorship_profiles.comp_name as prop_name, vendors.company_name as vendor_company_name'))
					->leftJoin('company_profiles', 'puos.added_by', '=', 'company_profiles.userId')
					->leftJoin('proprietorship_profiles', 'puos.propId', '=', 'proprietorship_profiles.id')
					->leftJoin('vendors', 'puos.inv_name', '=', 'vendors.id')
					->where('puos.added_by', '=', $userId)
					->orderBy('puos.id', 'DESC')
					->paginate(10);

		}
		elseif(Auth::user()->u_type ==3){ //admin
			$sales =  DB::table('puos')
							->select(DB::raw('puos.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'puos.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$sales_pagination = $sales;

		$array = array();

		foreach($sales as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = !empty($val->comp_name) ? $val->comp_name : '';
			$array[$val->id]['prop_name'] = $val->prop_name;
			$array[$val->id]['inv_num'] = $val->inv_num;
			$array[$val->id]['inv_name'] = $val->inv_name;
			$array[$val->id]['bill_name'] = $val->bill_name;
			$array[$val->id]['contact_no'] = $val->contact_no;
			$array[$val->id]['branch_name'] = $val->branch_name;
			$array[$val->id]['inv_date'] = $val->inv_date;
			$array[$val->id]['mode_of_pay'] = $val->mode_of_pay;
			$array[$val->id]['other_payment'] = $val->other_payment;
			$array[$val->id]['pay_status'] = $val->pay_status;
			$array[$val->id]['total_amount'] = $val->total_amount;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['signed_pdf'] = $val->signed_pdf;
			$array[$val->id]['signed_pdf_status'] = $val->signed_pdf_status;

			$customerName =  DB::table('vendors')
							->select(DB::raw('vendors.vendor_name,vendors.vendor_phone'))
							->where('id', '=', $val->inv_name)
							->get();
			$array[$val->id]['cust_name'] = isset($customerName[0]->vendor_name)?$customerName[0]->vendor_name:"";
			$array[$val->id]['cust_phone'] = isset($customerName[0]->vendor_phone)?$customerName[0]->vendor_phone:"";
			if($val->id >0){
				$salesValue = DB::table('puo_values')
								->select(DB::raw('
									SUM(
										COALESCE(puo_values.amount, 0) +
										COALESCE(puo_values.tax_amt, 0)
									) as grandTotal
								'))
								->where('sid', $val->id)
								->get();
				$array[$val->id]['grandTotal'] = isset($salesValue[0]->grandTotal)?$salesValue[0]->grandTotal:0;
			}else{
				$array[$val->id]['grandTotal'] = 0;

			}
		}
		$sales = json_decode(json_encode($array));
		//echo "<pre>"; print_r($sales);exit;
        return view('User.po.po-invoice')->with([
			'title' =>$title,
			'sales'=>$sales,
			'sales_pagination' =>$sales_pagination,
		]);
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
	
	private function buildPoPrefix($raw, $financialYear, $companyName)
	{
		$parts = explode('/', trim($raw, '/'));

		// remove FY if exists
		$parts = array_values(array_filter($parts, function ($p) {
			return !preg_match('/^\d{2}-\d{2}$/', $p);
		}));

		$companyCode = $parts[0] ?? strtoupper(substr($companyName, 0, 3));
		$seriesType  = $parts[1] ?? 'PO';

		return $companyCode . '/' . $financialYear . '/' . $seriesType . '/';
	}
	
	public function create_po_invoice_number($userId)
	{
		$this->companyInfoFill($userId);

		$company = DB::table('company_profiles')
			->where('userId', $userId)
			->first(['comp_name', 'comp_po_digits']);

		if (!$company) return false;

		/* FY */
		$year  = date('Y');
		$month = date('n');

		$fyStart = substr($month >= 4 ? $year : $year - 1, 2);
		$fyEnd   = substr($month >= 4 ? $year + 1 : $year, 2);

		$financialYear = $fyStart . '-' . $fyEnd;

		/* PREFIX */
		$prefix = !empty($company->comp_po_digits)
			? $this->buildPoPrefix($company->comp_po_digits, $financialYear, $company->comp_name)
			: strtoupper(substr($company->comp_name, 0, 3)) . '/' . $financialYear . '/PO/';

		/* LAST SAFE NUMBER */
		$last = DB::table('puos')
			->where('added_by', $userId)
			->where('inv_num', 'like', $prefix . '%')
			->orderBy('id', 'desc')
			->value('inv_num');

		$next = 1;

		if ($last) {
			$parts = explode('/', $last);
			$lastNum = end($parts);

			if (preg_match('/^\d+$/', $lastNum)) {
				$next = ((int)$lastNum) + 1;
			}
		} else {

			// config fallback
			if (!empty($company->comp_po_digits)) {
				$parts = explode('/', trim($company->comp_po_digits, '/'));
				$lastPart = end($parts);

				if (preg_match('/^\d+$/', $lastPart)) {
					$next = ((int)$lastPart) + 1;
				}
			}
		}

		return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
	}
	
	public function create_po_invoice_number_proprietorship($id, $userId)
	{
		$company = DB::table('proprietorship_profiles')
			->where('id', $id)
			->first(['comp_name', 'comp_po_digits']);

		if (!$company) return false;

		/* FY */
		$year  = date('Y');
		$month = date('n');

		$fyStart = substr($month >= 4 ? $year : $year - 1, 2);
		$fyEnd   = substr($month >= 4 ? $year + 1 : $year, 2);

		$financialYear = $fyStart . '-' . $fyEnd;

		/* PREFIX */
		$prefix = !empty($company->comp_po_digits)
			? $this->buildPoPrefix($company->comp_po_digits, $financialYear, $company->comp_name)
			: strtoupper(substr($company->comp_name, 0, 3)) . '/' . $financialYear . '/PO/';

		/* LAST SAFE NUMBER */
		$last = DB::table('puos')
			->where('added_by', $userId)
			->where('propId', $id)
			->where('inv_num', 'like', $prefix . '%')
			->orderBy('id', 'desc')
			->value('inv_num');

		$next = 1;

		if ($last) {
			$parts = explode('/', $last);
			$lastNum = end($parts);

			if (preg_match('/^\d+$/', $lastNum)) {
				$next = ((int)$lastNum) + 1;
			}
		} else {

			if (!empty($company->comp_po_digits)) {
				$parts = explode('/', trim($company->comp_po_digits, '/'));
				$lastPart = end($parts);

				if (preg_match('/^\d+$/', $lastPart)) {
					$next = ((int)$lastPart) + 1;
				}
			}
		}

		return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
	}

    public function CreatePurchaseOrder()
    {
        $userId = currentOwnerId();
		checkCoreAccess('Accounting');
		$invoiceNo = $this->create_po_invoice_number($userId);
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
		
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

        return view('User.po.create-po-invoice')->with([
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
			'proprietorships' => $proprietorships 
		]);
    }

    protected function getinvcust_po(Request $request)
	{

		 $id = $request->id;
		 $salesTableID = $request->salesTableID;


		$result = DB::table('puos')
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

	public function po_items_display(Request $request)
    {
		//$this->middleware('auth');

		$sid = $request->sId;
		$uid = currentOwnerId();
		$prod_id = $request->prod_id;

		$puo_values = DB::table('puo_values')
								->select(DB::raw('puo_values.id,puo_values.quantity'))
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
		if (count($puo_values) == 0) {
			$quantity = 1;
		}else{
			$quantity = $puo_values[0]->quantity;
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

		if (count($puo_values) == 0) {
			$values = array('sid' => $sid,'uid' => $uid,'prod_id' => $prod_id,'quantity' => $quantity,'rate' => $rate,'disc' => $disc,'disc_type'=>$disc_type,'disc_amt' => $disc_amt,'tax_amt'=>$tax_amt,'amount'=>$amount,'tax_type'=>$tax_type,'billing_type'=>$billing_type,'gst_rate'=>$gst_rate,'gst_trans'=>$gst_trans);
			$insertInvoice = DB::table('puo_values')->insert($values);
		}else{
			$update = DB::table('puo_values')
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


		$puo_values = $this->items_po_list($sid);
		//echo "<pre>"; print_r($puo_values);exit;
		return view('User.po.ajax-po-invoice-display')->with([
			'sales_values'=>$puo_values,
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
			$invoiceNo = $this->create_po_invoice_number_proprietorship($propId,$uid);
		} else {
			$invoiceNo = $this->create_po_invoice_number($uid);
		}

        return Puos::create([
            'added_by' => $uid,
			'propId' => $propId,
            'inv_num' => $invoiceNo,
			'inv_date' => $data['inv_date'],
			//'inv_name' => $data['inv_name'],
			//'add_type' => $data['add_type'],
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

    public function save_po_invoice(Request $request)  {

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
					'redirect' => url('/edit-po-invoice/'.base64_encode($sId)),
					'message' => 'PO added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'PO add failed'
				);
				return response()->json($msg);
			}

		}
    }

    public function items_po_list($sid)
	{

		$sales_values = DB::table('puo_values')
								->select(DB::raw('puo_values.*'))
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


    public function edit_po_invoice($sId)  {

		if(Auth::user()->u_type ==1){
			return redirect('/purchase-order');
		}
		$uid = currentOwnerId();
		checkCoreAccess('Accounting');
		$sId = base64_decode($sId);
		$sales = DB::table('puos')
								->where('id', '=', $sId)
								->get();
		$sales = $sales[0];
		$totalInvAmount = DB::table('puos')
							->join('puo_values', 'puo_values.sid', '=', 'puos.id')
							->where('puos.id', $sId)
							->where('puos.added_by', $uid)
							->selectRaw('
								SUM(
									COALESCE(puo_values.amount,0)
								  + COALESCE(puo_values.tax_amt,0)
								  - COALESCE(puo_values.disc_amt,0)
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

		//echo "<pre>"; print_r($natureof_payment);exit;

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
			$seller_person_no= isset($sales->seller_person_no)?$sales->seller_person_no:"";
            $countries = Country::where('id', '>', '0')->get();
            $states_bill = State::where('id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
             $cities_bill = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();

            $states_ship = State::where('country_id', '=', isset($sales->ship_country)?$sales->ship_country:0)->get();
             $cities_ship = City::where('state_id', '=', isset($sales->ship_state)?$sales->ship_state:0)->get();

             $states_seller = State::where('country_id', '=', isset($sales->seller_country)?$sales->seller_country:0)->get();
              $cities_seller = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
		//echo "<pre>";print_r($states_bill);exit;

		$sales_values = $this->items_po_list($sId);
		$totalInvoiceAmount = $totalInvAmount->total_invoice_amount ?? 0;
		//echo "<pre>";print_r($sales);exit;
		return view('User.po.edit-po-invoice')->with([
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
			'seller_person_no'=>$seller_person_no,
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

	public function view_po_invoice($sId)  {
		$sId = base64_decode($sId);
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		$sales = DB::table('puos')
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
			$seller_person_no= isset($sales->seller_person_no)?$sales->seller_person_no:"";
            $countries = Country::where('id', '>', '0')->get();
            $states_bill = State::where('id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
             $cities_bill = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();

            $states_ship = State::where('country_id', '=', isset($sales->ship_country)?$sales->ship_country:0)->get();
             $cities_ship = City::where('state_id', '=', isset($sales->ship_state)?$sales->ship_state:0)->get();

             $states_seller = State::where('country_id', '=', isset($sales->seller_country)?$sales->seller_country:0)->get();
              $cities_seller = City::where('state_id', '=', isset($sales->seller_state)?$sales->seller_state:0)->get();
		//echo "<pre>";print_r($states_bill);exit;

		$sales_values = $this->items_po_list($sId);
		//echo "<pre>";print_r($sales);exit;
		return view('User.po.view-po-invoice')->with([
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
            'seller_person_no'=>$seller_person_no,
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

    public function update_po_invoice(Request $request)  {

		//echo "<pre>";print_r($request->all());exit('hello');
		$sId = $request->id;

		$validation = $this->validator($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update project
			$update = DB::table('puos')
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
	public function fetchPoItem(Request $request)
	{
		$sid = $request->id;
		$purchase_values = DB::table('puo_values')
								->select(DB::raw('puo_values.*'))
								->where('puo_values.id', '=', $sid)
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

	public function update_po_seller_details(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$sId = $request->id;
		$update = DB::table('puos')
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

	public function update_po_item_rate(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;
		$prod_id = $request->prod_id;

		$sales_data = DB::table('puo_values')
								->select(DB::raw('puo_values.*'))
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

		$update = DB::table('puo_values')
				->where('id', $id)
				->update(
					 array(
							'rate' => $productRate,
							'disc_amt' => $disc_amt,
							'tax_amt' => $tax_amt,
							'amount' => $amount
					 )
				);

		$sales_values = $this->items_po_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.po.ajax-po-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);
    }

	public function update_po_invoice_final(Request $request)  {

		//print_r($_FILES);
		$signature_name = $request->signature_name;
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
			$update = DB::table('puos')
			->where('id', $request->id)
			->update(
				 array(
						'signature' => $signature,
						'signature_name' => $signature_name,
						//'tds_applicable' => $tds_applicable,
						//'tds_percentage' => $tds_percentage,
						//'tds_amount' => $tds_amount,
						//'tds_id' => $tds_id,
						'status' => 1,

				 )
			);
		}else{
			$update = DB::table('puos')
				->where('id', $request->id)
				->update(
					 array(
							'signature_name' => $signature_name,
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
			'redirect' => url('/purchase-order'),
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
			'order_date' => 'required',
			'disp_through' => 'required',
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

	public function update_po_other(Request $request)  {

		//echo "<pre>";print_r($_POST);exit;
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
				$updateImage = DB::table('puos')
							->where('id', $sId)
							->update(
								 array(
										'image_sign' => $image,
								 )
							);
			}

			//end update image_sign
			//$tdsApplicable = $request->tds_applicable === 'yes';
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
				$adjustedAmount = 0;              // Optional (depends on your logic)
				$dueAmount = $totalAmount - $advanceAmount;
			}
			
			$update = DB::table('puos')
					->where('id', $sId)
					->update(
						array(	
								'mode_of_pay' => $request->mode_of_pay,
								'other_payment' => $request->other_payment,
								'pay_status' => $request->pay_status,
								'total_amount' => isset($request->total_amount)?$request->total_amount:0,
								'advance_amount' => isset($request->advance_amount)?$request->advance_amount:0,
								'due_amount' => isset($request->due_amount)?$request->due_amount:0,
								'adjusted_amount' => $adjustedAmount,
								'seller_orderno' => isset($request->seller_orderno)?$request->seller_orderno:"",
								'order_date' => isset($request->order_date)?$request->order_date:"",
								'buyer_refno' => isset($request->buyer_refno)?$request->buyer_refno:"",
								'other_refno' => isset($request->other_refno)?$request->other_refno:"",
								'dispa_docno_one' => isset($request->dispa_docno_one)?$request->dispa_docno_one:"",
								'disp_through' => $request->disp_through,
								'other_dispa_det' => isset($request->other_dispa_det)?$request->other_dispa_det:"",
								'terms_delivery' => isset($request->terms_delivery)?$request->terms_delivery:"",
						)
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/purchase-order'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);
		}
    }

	public function delPoItem(Request $request)
    {
        $delPurchaseItem = DB::table('puo_values')->where('id', $request->id)->delete();
		$sales_values = $this->items_po_list($request->sid);

		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.po.ajax-po-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);

    }
	public function update_po_item_quantity(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$id = $request->id;

		$sales_data = DB::table('puo_values')
								->select(DB::raw('puo_values.sid,puo_values.rate,puo_values.disc,puo_values.disc_type,puo_values.gst_rate'))
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

		$update = DB::table('puo_values')
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

		$sales_values = $this->items_po_list($sales_data[0]->sid);
		//echo "<pre>"; print_r($sales_values);exit;
		return view('User.po.ajax-po-invoice-display')->with([
			'sales_values'=>$sales_values,
		]);
    }
	public function delInvoicePo(Request $request)
    {
		$data = DB::table('puos')->where('id', $request->id)->first();
		$oldData = [
					'record' => [
						'inv_num'  => $data->inv_num ?? null,
						'inv_date' => $data->inv_date ?? null,
					]
				];
				
        $delInvoice = DB::table('puos')->where('id', $request->id)->delete();
        $delInvoiceItemValue = DB::table('puo_values')->where('sid', $request->id)->delete();
		if($delInvoice){
			//AUDIT LOG ENTRY
            AuditLogger::logEntry(
                action: 'delete',
                module: 'Purchase Order',
                description: "Purchase Order deleted: {$data->inv_num}",
                oldData: $oldData,
                newData: null
            );
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/purchase-order'),
				'message' => 'Record deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/purchase-order'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

	

    

	public function fetchPoDetails(Request $request)
	{
		$invoiceNumber = $request->input('inv_num');

		$purchaseDetails = DB::table('puos')
						->leftJoin('countries', 'countries.id', '=', 'puos.seller_country')
						->leftJoin('states', 'states.id', '=', 'puos.seller_state')
						->leftJoin('cities', 'cities.id', '=', 'puos.seller_city')
						->select(
							'puos.*',
							'countries.name as country_name',
							'states.name as state_name',
							'cities.name as city_name'
						)
						->where('puos.inv_num', $invoiceNumber)
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

	public function fetchPoVendorDetails(Request $request)
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
	
	public function updatePoStatus(Request $request)
	{
		DB::table('puos')
			->where('id', $request->id)
			->update([
				'status' => $request->status
			]);

		return response()->json(['success' => true]);
	}

}
