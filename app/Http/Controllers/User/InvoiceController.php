<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use App\Models\Sales;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Sales_values;

use Redirect;
use DB;
use Auth;
use Validator;
use App\User;
use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;

class InvoiceController extends Controller
{
    public function __construct()
	{
		//$this->middleware('auth');
	}
    
    public function Index()
    {
        return view('User.invoice');
    }
	
	public function getSalesInvoice($sid, $invType)
	{
		$sid = base64_decode($sid);
		//get sales details
		$sales = DB::table('sales')
								->select(DB::raw('sales.*'))
								->where('id', '=', $sid)
								->get();
		$sales = $sales[0];	
		$inv_num = $sales->inv_num;
		$custId = $sales->inv_name;
		$added_by = $sales->added_by;
		$invDate = $sales->created_at;

		$special_discount = $sales->special_discount;
		$special_discount_amount = $sales->special_discount_amount;
		$special_discount_type = $sales->special_discount_type;
		//get company details
		$compDetails = DB::table('users')
						->leftJoin('company_profiles as cp', 'users.id', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', DB::raw((int)$sales->propId))
						->select(
							'users.name',
							DB::raw("
								CASE 
									WHEN '".$sales->propId."' IS NOT NULL AND '".$sales->propId."' != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							"),
							'cp.gst_no',
							'cp.comp_pan_no',
							'cp.comp_bill_addone'
						)
						->where('users.id', $added_by)
						->first();
		//get customer details
		$custDetails = DB::table('customers')
						->select(DB::raw('customers.*'))					                   					
						->where('customers.id','=',$custId) 
						->get();
		//echo "<pre>";print_r($custDetails);exit;
		$custDetails = isset($custDetails[0])?$custDetails[0]:$custDetails;
		$stateBill = DB::table('states')
					->select(DB::raw('states.name'))
				    ->where('states.id', '=', $custDetails->cust_bill_state) 
				    ->get();
		$cityBill = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->cust_bill_city) 
					->get();
		$stateShip = DB::table('states')
					->select(DB::raw('states.name'))
					->where('states.id', '=', $custDetails->cust_ship_state) 
					->get();
		
		$cityShip = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->cust_ship_city) 
					->get();
		//get sales items 
		$sales_values = DB::table('sales_values')
								->select(DB::raw('sales_values.*'))
								->where('sid', '=', $sid)
								->get();
								
		$array = array();
		foreach($sales_values as $k=>$val)
		{
			$array[$k]['id'] = $val->id;
			$array[$k]['sid'] = $val->sid;
			$array[$k]['prod_id'] = $val->prod_id;
			$array[$k]['quantity'] = $val->quantity;
			$array[$k]['rate'] = $val->rate;
			$array[$k]['disc'] = $val->disc;
			$array[$k]['disc_amt'] = $val->disc_amt;
			$array[$k]['tax_amt'] = $val->tax_amt;
			$array[$k]['amount'] = $val->amount;
			$array[$k]['gst_rate'] = $val->gst_rate;
			$array[$k]['gov_pay'] = $val->gov_pay;
			$array[$k]['ser_pay'] = $val->ser_pay;
			$array[$k]['tax_type'] = $val->tax_type;
			$array[$k]['gst_trans'] = $val->gst_trans;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				// $array[$k]['item_name'] = isset($item[0]->item_name)?$item[0]->item_name:"";
				$array[$k]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$k]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$k]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
				$array[$k]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$k]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
			}else{
				$array[$k]['item_name'] = "";
				$array[$k]['base_unit'] = "";
				$array[$k]['sec_unit'] = "";
			} 
			$array[$k]['inv_num'] = $inv_num;
			$array[$k]['added_by'] = $sales->added_by;
			$array[$k]['signature'] = $sales->signature;
			$array[$k]['signature_name'] = $sales->signature_name;
		}
		$sales_values = json_decode(json_encode($array));
		
		if($invType == "invoice"){
		    
			return view('User.invoice')->with([
				'sid'=>$sid,
				'sales'=>$sales,
				'sales_values'=>$sales_values,
				'inv_num' => $inv_num,
				'invDate' => $invDate,
				'compDetails' => $compDetails,
				'custDetails' => $custDetails,
				'stateBill' => $stateBill,
				'cityBill' => $cityBill,
				'stateShip' => $stateShip,
				'cityShip' => $cityShip,

				'special_discount' => $special_discount,
				'special_discount_amount' => $special_discount_amount,
				'special_discount_type' => $special_discount_type,
			]);
		}else{
			
			$inv_num = str_replace('/', '-', $inv_num);
			$pdf = \PDF::loadView('User.sales-invoice-pdf', 
			compact('sales','sales_values','inv_num','invDate','compDetails','custDetails','stateBill','cityBill','stateShip','cityShip'))
			->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
			$pdfName = 'Sales-Inv-'.$inv_num.'.pdf';
			return $pdf->stream($pdfName);
		}
	}
	
	public function getQuotationInvoice($sid, $invType)
	{
		$sid = base64_decode($sid);
		//get sales details
		$sales = DB::table('quotations')
								->select(DB::raw('quotations.*'))
								->where('id', '=', $sid)
								->get();
		$sales = $sales[0];	
		$inv_num = $sales->inv_num;
		$custId = $sales->inv_name;
		$added_by = $sales->added_by;
		$invDate = $sales->created_at;

		$special_discount = $sales->special_discount;
		$special_discount_amount = $sales->special_discount_amount;
		$special_discount_type = $sales->special_discount_type;
		//get company details
		$compDetails = DB::table('users')
						->leftJoin('company_profiles as cp', 'users.id', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', DB::raw((int)$sales->propId))
						->select(
							'users.name',
							DB::raw("
								CASE 
									WHEN '".$sales->propId."' IS NOT NULL AND '".$sales->propId."' != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							"),
							'cp.gst_no',
							'cp.comp_pan_no',
							'cp.comp_bill_addone'
						)
						->where('users.id', $added_by)
						->first();
		//get customer details
		$custDetails = DB::table('customers')
						->select(DB::raw('customers.*'))					                   					
						->where('customers.id','=',$custId) 
						->get();
		//echo "<pre>";print_r($custDetails);exit;
		$custDetails = isset($custDetails[0])?$custDetails[0]:$custDetails;
		$stateBill = DB::table('states')
					->select(DB::raw('states.name'))
				    ->where('states.id', '=', $custDetails->cust_bill_state) 
				    ->get();
		$cityBill = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->cust_bill_city) 
					->get();
		$stateShip = DB::table('states')
					->select(DB::raw('states.name'))
					->where('states.id', '=', $custDetails->cust_ship_state) 
					->get();
		
		$cityShip = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->cust_ship_city) 
					->get();
		//get sales items 
		$quotations_values = DB::table('quotations_values')
								->select(DB::raw('quotations_values.*'))
								->where('sid', '=', $sid)
								->get();
								
		$array = array();
		foreach($quotations_values as $k=>$val)
		{
			$array[$k]['id'] = $val->id;
			$array[$k]['sid'] = $val->sid;
			$array[$k]['prod_id'] = $val->prod_id;
			$array[$k]['quantity'] = $val->quantity;
			$array[$k]['rate'] = $val->rate;
			$array[$k]['disc'] = $val->disc;
			$array[$k]['disc_amt'] = $val->disc_amt;
			$array[$k]['tax_amt'] = $val->tax_amt;
			$array[$k]['amount'] = $val->amount;
			$array[$k]['gov_pay'] = $val->gov_pay;
			$array[$k]['ser_pay'] = $val->ser_pay;
			$array[$k]['tax_type'] = $val->tax_type;
			$array[$k]['gst_trans'] = $val->gst_trans;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				// $array[$k]['item_name'] = isset($item[0]->item_name)?$item[0]->item_name:"";
				$array[$k]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$k]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$k]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
				$array[$k]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$k]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
			}else{
				$array[$k]['item_name'] = "";
				$array[$k]['base_unit'] = "";
				$array[$k]['sec_unit'] = "";
			} 
			$array[$k]['inv_num'] = $inv_num;
			$array[$k]['added_by'] = $sales->added_by;
			$array[$k]['signature'] = $sales->signature;
			$array[$k]['signature_name'] = $sales->signature_name;
		}
		$quotations_values = json_decode(json_encode($array));
		
		if($invType == "quotation"){
		    
			return view('User.quotations.invoice')->with([
				'sid'=>$sid,
				'sales'=>$sales,
				'sales_values'=>$quotations_values,
				'inv_num' => $inv_num,
				'invDate' => $invDate,
				'compDetails' => $compDetails,
				'custDetails' => $custDetails,
				'stateBill' => $stateBill,
				'cityBill' => $cityBill,
				'stateShip' => $stateShip,
				'cityShip' => $cityShip,

				'special_discount' => $special_discount,
				'special_discount_amount' => $special_discount_amount,
				'special_discount_type' => $special_discount_type,
			]);
		}else{
			
			$inv_num = str_replace('/', '-', $inv_num);
			$pdf = \PDF::loadView('User.quotations.quotation-invoice-pdf', 
			compact('quotations','quotations_values','inv_num','invDate','compDetails','custDetails','stateBill','cityBill','stateShip','cityShip'))
			->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
			$pdfName = 'Sales-Inv-'.$inv_num.'.pdf';
			return $pdf->stream($pdfName);
		}
	}
	
	public function getProformaInvoice($sid, $invType)
	{
		$sid = base64_decode($sid);
		//get sales details
		$sales = DB::table('proformas')
								->select(DB::raw('proformas.*'))
								->where('id', '=', $sid)
								->get();
		$sales = $sales[0];	
		$inv_num = $sales->inv_num;
		$custId = $sales->inv_name;
		$added_by = $sales->added_by;
		$invDate = $sales->created_at;

		$special_discount = $sales->special_discount;
		$special_discount_amount = $sales->special_discount_amount;
		$special_discount_type = $sales->special_discount_type;
		//get company details
		$compDetails = DB::table('users')
						->leftJoin('company_profiles as cp', 'users.id', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', DB::raw((int)$sales->propId))
						->select(
							'users.name',
							DB::raw("
								CASE 
									WHEN '".$sales->propId."' IS NOT NULL AND '".$sales->propId."' != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							"),
							'cp.gst_no',
							'cp.comp_pan_no',
							'cp.comp_bill_addone'
						)
						->where('users.id', $added_by)
						->first();
		//get customer details
		$custDetails = DB::table('customers')
						->select(DB::raw('customers.*'))					                   					
						->where('customers.id','=',$custId) 
						->get();
		//echo "<pre>";print_r($custDetails);exit;
		$custDetails = isset($custDetails[0])?$custDetails[0]:$custDetails;
		$stateBill = DB::table('states')
					->select(DB::raw('states.name'))
				    ->where('states.id', '=', $custDetails->cust_bill_state) 
				    ->get();
		$cityBill = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->cust_bill_city) 
					->get();
		$stateShip = DB::table('states')
					->select(DB::raw('states.name'))
					->where('states.id', '=', $custDetails->cust_ship_state) 
					->get();
		
		$cityShip = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->cust_ship_city) 
					->get();
		//get sales items 
		$proformas_values = DB::table('proformas_values')
								->select(DB::raw('proformas_values.*'))
								->where('sid', '=', $sid)
								->get();
								
		$array = array();
		foreach($proformas_values as $k=>$val)
		{
			$array[$k]['id'] = $val->id;
			$array[$k]['sid'] = $val->sid;
			$array[$k]['prod_id'] = $val->prod_id;
			$array[$k]['quantity'] = $val->quantity;
			$array[$k]['rate'] = $val->rate;
			$array[$k]['disc'] = $val->disc;
			$array[$k]['disc_amt'] = $val->disc_amt;
			$array[$k]['tax_amt'] = $val->tax_amt;
			$array[$k]['amount'] = $val->amount;
			$array[$k]['gov_pay'] = $val->gov_pay;
			$array[$k]['ser_pay'] = $val->ser_pay;
			$array[$k]['tax_type'] = $val->tax_type;
			$array[$k]['gst_trans'] = $val->gst_trans;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				// $array[$k]['item_name'] = isset($item[0]->item_name)?$item[0]->item_name:"";
				$array[$k]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$k]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$k]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
				$array[$k]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$k]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
			}else{
				$array[$k]['item_name'] = "";
				$array[$k]['base_unit'] = "";
				$array[$k]['sec_unit'] = "";
			} 
			$array[$k]['inv_num'] = $inv_num;
			$array[$k]['added_by'] = $sales->added_by;
			$array[$k]['signature'] = $sales->signature;
			$array[$k]['signature_name'] = $sales->signature_name;
		}
		$proformas_values = json_decode(json_encode($array));
		
		if($invType == "proforma"){
		    
			return view('User.proformas.invoice')->with([
				'sid'=>$sid,
				'sales'=>$sales,
				'sales_values'=>$proformas_values,
				'inv_num' => $inv_num,
				'invDate' => $invDate,
				'compDetails' => $compDetails,
				'custDetails' => $custDetails,
				'stateBill' => $stateBill,
				'cityBill' => $cityBill,
				'stateShip' => $stateShip,
				'cityShip' => $cityShip,

				'special_discount' => $special_discount,
				'special_discount_amount' => $special_discount_amount,
				'special_discount_type' => $special_discount_type,
			]);
		}else{
			
			$inv_num = str_replace('/', '-', $inv_num);
			$pdf = \PDF::loadView('User.proformas.proforma-invoice-pdf', 
			compact('proformas','proformas_values','inv_num','invDate','compDetails','custDetails','stateBill','cityBill','stateShip','cityShip'))
			->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
			$pdfName = 'Sales-Inv-'.$inv_num.'.pdf';
			return $pdf->stream($pdfName);
		}
	}
	
	public function getPurchaseInvoice($sid, $invType)
	{
		$sid = base64_decode($sid);
		//get sales details
		$sales = DB::table('purchases')
								->select(DB::raw('purchases.*'))
								->where('id', '=', $sid)
								->get();
		$sales = $sales[0];	
		$inv_num = $sales->inv_num;
		$custId = $sales->inv_name;
		$added_by = $sales->added_by;
		$invDate = $sales->created_at;

		$special_discount = 0;
		$special_discount_amount = 0;
		$special_discount_type = "";
		//get company details
		$compDetails = DB::table('users')
						->leftJoin('company_profiles as cp', 'users.id', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', DB::raw((int)$sales->propId))
						->select(
							'users.name',
							DB::raw("
								CASE 
									WHEN '".$sales->propId."' IS NOT NULL AND '".$sales->propId."' != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							"),
							'cp.gst_no',
							'cp.comp_pan_no',
							'cp.comp_bill_addone'
						)
						->where('users.id', $added_by)
						->first();
		//get customer details
		$custDetails = DB::table('vendors')
						->select(DB::raw('vendors.*'))					                   					
						->where('vendors.id','=',$custId) 
						->get();
		//echo "<pre>";print_r($custDetails);exit;
		$custDetails = isset($custDetails[0])?$custDetails[0]:$custDetails;
		$stateBill = DB::table('states')
					->select(DB::raw('states.name'))
				    ->where('states.id', '=', $custDetails->billing_state) 
				    ->get();
		$cityBill = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->billing_city) 
					->get();
		$stateShip = DB::table('states')
					->select(DB::raw('states.name'))
					->where('states.id', '=', $custDetails->shipping_state) 
					->get();
		
		$cityShip = DB::table('cities')
					->select(DB::raw('cities.name'))
					->where('cities.id', '=', $custDetails->shipping_city) 
					->get();
		//get sales items 
		$sales_values = DB::table('purchase_values')
								->select(DB::raw('purchase_values.*'))
								->where('sid', '=', $sid)
								->get();
								
		$array = array();
		foreach($sales_values as $k=>$val)
		{
			$array[$k]['id'] = $val->id;
			$array[$k]['sid'] = $val->sid;
			$array[$k]['prod_id'] = $val->prod_id;
			$array[$k]['quantity'] = $val->quantity;
			$array[$k]['rate'] = $val->rate;
			$array[$k]['disc'] = $val->disc;
			$array[$k]['disc_amt'] = $val->disc_amt;
			$array[$k]['tax_amt'] = $val->tax_amt;
			$array[$k]['amount'] = $val->amount;
			$array[$k]['tax_type'] = $val->tax_type;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				// $array[$k]['item_name'] = isset($item[0]->item_name)?$item[0]->item_name:"";
				$array[$k]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$k]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$k]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
				$array[$k]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$k]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
			}else{
				$array[$k]['item_name'] = "";
				$array[$k]['base_unit'] = "";
				$array[$k]['sec_unit'] = "";
			} 
			$array[$k]['inv_num'] = $inv_num;
			$array[$k]['added_by'] = $sales->added_by;
			$array[$k]['signature'] = $sales->signature;
			$array[$k]['signature_name'] = $sales->signature_name;
		}
		$sales_values = json_decode(json_encode($array));
		
		if($invType == "invoice"){
		    
			return view('User.invoice-purchase')->with([
				'sid'=>$sid,
				'sales'=>$sales,
				'sales_values'=>$sales_values,
				'inv_num' => $inv_num,
				'invDate' => $invDate,
				'compDetails' => $compDetails,
				'custDetails' => $custDetails,
				'stateBill' => $stateBill,
				'cityBill' => $cityBill,
				'stateShip' => $stateShip,
				'cityShip' => $cityShip,

				'special_discount' => $special_discount,
				'special_discount_amount' => $special_discount_amount,
				'special_discount_type' => $special_discount_type,
			]);
		}else{
			
			$inv_num = str_replace('/', '-', $inv_num);
			$pdf = \PDF::loadView('User.sales-invoice-pdf', 
			compact('sales','sales_values','inv_num','invDate','compDetails','custDetails','stateBill','cityBill','stateShip','cityShip'))
			->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
			$pdfName = 'Sales-Inv-'.$inv_num.'.pdf';
			return $pdf->stream($pdfName);
		}
	}
	public function getPoInvoice($sid, $invType)
	{
		$sid = base64_decode($sid);
		//get sales details
		$sales = DB::table('puos')
								->select(DB::raw('puos.*'))
								->where('id', '=', $sid)
								->get();
		$sales = $sales[0];	
		$inv_num = $sales->inv_num;
		$custId = $sales->inv_name;
		$added_by = $sales->added_by;
		$invDate = $sales->created_at;

		$special_discount = 0;
		$special_discount_amount = 0;
		$special_discount_type = "";
		//get company details
		$compDetails = DB::table('users')
						->leftJoin('company_profiles as cp', 'users.id', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', DB::raw((int)$sales->propId))
						->select(
							'users.name',
							DB::raw("
								CASE 
									WHEN '".$sales->propId."' IS NOT NULL AND '".$sales->propId."' != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							"),
							'cp.gst_no',
							'cp.comp_pan_no',
							'cp.comp_bill_addone'
						)
						->where('users.id', $added_by)
						->first();
		//get customer details
		$custDetails = DB::table('vendors')
						->select(DB::raw('vendors.*'))					                   					
						->where('vendors.id','=',$custId) 
						->get();
		//echo "<pre>";print_r($custDetails);exit;
		$custDetails = isset($custDetails[0])?$custDetails[0]:$custDetails;
		$stateBill = null;
		$cityBill  = null;
		$stateShip = null;
		$cityShip  = null;

		if (!empty($custDetails->billing_state)) {
			$stateBill = DB::table('states')
				->where('id', $custDetails->billing_state)
				->value('name');
		}

		if (!empty($custDetails->billing_city)) {
			$cityBill = DB::table('cities')
				->where('id', $custDetails->billing_city)
				->value('name');
		}

		if (!empty($custDetails->shipping_state)) {
			$stateShip = DB::table('states')
				->where('id', $custDetails->shipping_state)
				->value('name');
		}

		if (!empty($custDetails->shipping_city)) {
			$cityShip = DB::table('cities')
				->where('id', $custDetails->shipping_city)
				->value('name');
		}

		//get sales items 
		$puo_values = DB::table('puo_values')
								->select(DB::raw('puo_values.*'))
								->where('sid', '=', $sid)
								->get();
								
		$array = array();
		foreach($puo_values as $k=>$val)
		{
			$array[$k]['id'] = $val->id;
			$array[$k]['sid'] = $val->sid;
			$array[$k]['prod_id'] = $val->prod_id;
			$array[$k]['quantity'] = $val->quantity;
			$array[$k]['rate'] = $val->rate;
			$array[$k]['disc'] = $val->disc;
			$array[$k]['disc_amt'] = $val->disc_amt;
			$array[$k]['tax_amt'] = $val->tax_amt;
			$array[$k]['amount'] = $val->amount;
			$array[$k]['tax_type'] = $val->tax_type;

			if($val->prod_id >0){
				$item = Product::where('id', '=', $val->prod_id)->get();
				// $array[$k]['item_name'] = isset($item[0]->item_name)?$item[0]->item_name:"";
				$array[$k]['item_name'] = ($item[0]->item_type == "service") ? $item[0]->service_name : $item[0]->item_name;
				$array[$k]['base_unit'] = isset($item[0]->base_unit)?$item[0]->base_unit:"";
				$array[$k]['sec_unit'] = isset($item[0]->sec_unit)?$item[0]->sec_unit:"";
				$array[$k]['sac_code'] = isset($item[0]->sac_code)?$item[0]->sac_code:"";
				$array[$k]['hsn_code'] = isset($item[0]->hsn_code)?$item[0]->hsn_code:"";
			}else{
				$array[$k]['item_name'] = "";
				$array[$k]['base_unit'] = "";
				$array[$k]['sec_unit'] = "";
			} 
			$array[$k]['inv_num'] = $inv_num;
			$array[$k]['added_by'] = $sales->added_by;
			$array[$k]['signature'] = $sales->signature;
			$array[$k]['signature_name'] = $sales->signature_name;
		}
		$puo_values = json_decode(json_encode($array));
		
		if($invType == "invoice"){
		    
			return view('User.po.invoice-po')->with([
				'sid'=>$sid,
				'sales'=>$sales,
				'sales_values'=>$puo_values,
				'inv_num' => $inv_num,
				'invDate' => $invDate,
				'compDetails' => $compDetails,
				'custDetails' => $custDetails,
				'stateBill' => $stateBill,
				'cityBill' => $cityBill,
				'stateShip' => $stateShip,
				'cityShip' => $cityShip,

				'special_discount' => $special_discount,
				'special_discount_amount' => $special_discount_amount,
				'special_discount_type' => $special_discount_type,
			]);
		}else{
			
			$inv_num = str_replace('/', '-', $inv_num);
			$pdf = \PDF::loadView('User.sales-invoice-pdf', 
			compact('sales','sales_values','inv_num','invDate','compDetails','custDetails','stateBill','cityBill','stateShip','cityShip'))
			->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif','isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
			$pdfName = 'Po-Inv-'.$inv_num.'.pdf';
			return $pdf->stream($pdfName);
		}
	}
}