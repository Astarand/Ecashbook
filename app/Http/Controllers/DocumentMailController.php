<?php

namespace App\Http\Controllers;

use App\Mail\SendInvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use App\Models\Sales;
use App\Models\Customers;
use App\Models\Product;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Sales_values;

class DocumentMailController extends Controller
{
    public function sendDocumentMail(Request $request)
	{
		$request->validate([
			'id' => 'required|integer',
			'module' => 'required|string'
		]);
		$userId = currentOwnerId();
		$companyDetails = DB::table('company_profiles')
			->where('userId', $userId)
			->first();
			
		$fromEmail = $companyDetails->comp_email ?? config('mail.from.address');
		$fromName  = $companyDetails->comp_name ?? config('mail.from.name');

		$id = $request->id;
		$module = strtolower($request->module);

		$configs = [

			'sales' => [
				'table' => 'sales',
				'party_table' => 'customers',
				'party_field' => 'inv_name',
				'email_field' => 'cust_email',
				'name_field' => 'cust_name',
				'number_field' => 'inv_num',
				'status_field' => 'is_send',
				'title' => 'Invoice',
				'file_prefix' => 'Sales-Inv-',
			],

			'quotation' => [
				'table' => 'quotations',
				'party_table' => 'customers',
				'party_field' => 'inv_name',
				'email_field' => 'cust_email',
				'name_field' => 'cust_name',
				'number_field' => 'inv_num',
				'status_field' => 'is_send',
				'title' => 'Quotation',
				'file_prefix' => 'Quotation-',
			],

			'proforma' => [
				'table' => 'proformas',
				'party_table' => 'customers',
				'party_field' => 'inv_name',
				'email_field' => 'cust_email',
				'name_field' => 'cust_name',
				'number_field' => 'inv_num',
				'status_field' => 'is_send',
				'title' => 'Proforma Invoice',
				'file_prefix' => 'Proforma-',
			],

			'purchase' => [
				'table' => 'purchases',
				'party_table' => 'vendors',
				'party_field' => 'inv_name',
				'email_field' => 'vendor_email',
				'name_field' => 'vendor_name',
				'number_field' => 'inv_num',
				'status_field' => 'is_send',
				'title' => 'Purchase Invoice',
				'file_prefix' => 'Purchase-',
			],

			'po' => [
				'table' => 'puos',
				'party_table' => 'vendors',
				'party_field' => 'inv_name',
				'email_field' => 'vendor_email',
				'name_field' => 'vendor_name',
				'number_field' => 'inv_num',
				'status_field' => 'is_send',
				'title' => 'Purchase Order',
				'file_prefix' => 'PO-',
			],
		];

		if (!isset($configs[$module])) {
			return response()->json([
				'success' => false,
				'message' => 'Invalid document type.'
			], 422);
		}

		$config = $configs[$module];

		/*
		|--------------------------------------------------------------------------
		| Get Document
		|--------------------------------------------------------------------------
		*/

		$document = DB::table($config['table'])
			->where('id', $id)
			->first();

		if (!$document) {
			return response()->json([
				'success' => false,
				'message' => $config['title'] . ' not found.'
			], 404);
		}

		/*
		|--------------------------------------------------------------------------
		| Get Customer / Vendor
		|--------------------------------------------------------------------------
		*/

		$partyId = $document->{$config['party_field']} ?? null;

		$party = DB::table($config['party_table'])
			->where('id', $partyId)
			->first();

		if (!$party) {
			return response()->json([
				'success' => false,
				'message' => 'Customer/Vendor not found.'
			], 422);
		}

		/*
		|--------------------------------------------------------------------------
		| Email
		|--------------------------------------------------------------------------
		*/

		$email = trim($party->{$config['email_field']} ?? '');

		if (empty($email)) {

			return response()->json([
				'success' => false,
				'email_missing' => true,
				'message' => 'Email is not set for this customer/vendor.'
			], 422);
		}

		/*
		|--------------------------------------------------------------------------
		| Invoice Number
		|--------------------------------------------------------------------------
		*/

		$invoiceNumber = $document->{$config['number_field']} ?? $id;

		/*
		|--------------------------------------------------------------------------
		| Generate PDF
		|--------------------------------------------------------------------------
		*/

		$pdfPath = null;

		try {

			$pdf = $this->generateDocumentPdf($module, $id);

			/*
			|--------------------------------------------------------------------------
			| Temporary Directory
			|--------------------------------------------------------------------------
			*/

			$directory = public_path('uploads/email-pdfs');

			if (!File::exists($directory)) {
				File::makeDirectory($directory, 0755, true);
			}

			$safeNumber = preg_replace('/[^A-Za-z0-9\-]/','-',$invoiceNumber);

			$fileName = $config['file_prefix']. $safeNumber. '-' . time(). '.pdf';

			$pdfPath = $directory . '/' . $fileName;

			/*
			|--------------------------------------------------------------------------
			| Save PDF
			|--------------------------------------------------------------------------
			*/
			
			//dd('Before output');

			$output = $pdf->output();
			File::put($pdfPath, $output);

			//dd('After file put');

			/*
			|--------------------------------------------------------------------------
			| Send Email
			|--------------------------------------------------------------------------
			*/

			Mail::to($email)->send(
				new \App\Mail\SendInvoiceMail(
					$party->{$config['name_field']} ?? 'Customer',
					$invoiceNumber,
					$pdfPath,
					$config['title'] . ' - ' . $invoiceNumber,
					$fromEmail,
					$fromName
				)
			);

			/*
			|--------------------------------------------------------------------------
			| Update is_send
			|--------------------------------------------------------------------------
			*/

			DB::table($config['table'])
				->where('id', $id)
				->update([
					$config['status_field'] => 1
				]);

			/*
			|--------------------------------------------------------------------------
			| Delete Temporary PDF
			|--------------------------------------------------------------------------
			*/

			if ($pdfPath && File::exists($pdfPath)) {
				File::delete($pdfPath);
			}

			return response()->json([
				'success' => true,
				'message' => $config['title'] . ' sent successfully.'
			]);

		} catch (\Throwable $e) {

			/*
			|--------------------------------------------------------------------------
			| Delete PDF if anything fails
			|--------------------------------------------------------------------------
			*/

			if ($pdfPath && File::exists($pdfPath)) {
				File::delete($pdfPath);
			}

			\Log::error('Document email error', [
				'module' => $module,
				'id' => $id,
				'error' => $e->getMessage()
			]);

			return response()->json([
				'success' => false,
				'message' => 'Unable to send email. Please try again.'
			], 500);
		}
	}
	
	private function generateDocumentPdf($module, $id)
	{
		switch ($module) {

			//sales
			case 'sales':

				$sales = DB::table('sales')
					->where('id', $id)
					->first();

				if (!$sales) {
					throw new \Exception('Sales invoice not found.');
				}

				$inv_num = $sales->inv_num;
				$custId = $sales->inv_name;
				$added_by = $sales->added_by;
				$invDate = $sales->inv_date;

				$special_discount = $sales->special_discount;
				$special_discount_amount = $sales->special_discount_amount;
				$special_discount_type = $sales->special_discount_type;

				$compDetails = $this->getCompanyDetails(
					$added_by,
					$sales->propId
				);

				$custDetails = DB::table('customers')
					->where('id', $custId)
					->first();

				$locations = $this->getCustomerLocations($custDetails);

				$sales_values = DB::table('sales_values')
					->where('sid', $id)
					->get();

				$sales_values = $this->prepareInvoiceItems(
					$sales_values,
					$inv_num,
					$sales
				);

				$bankDetails = DB::table('banks')
					->where('id', $sales->bank_id)
					->first();

				$inv_num = str_replace('/', '-', $inv_num);

				return \PDF::loadView(
					'emails.attached-invoice-pdf',
					[
						'sid'                    => $id,
						'sales'                  => $sales,
						'sales_values'           => $sales_values,
						'inv_num'                => $inv_num,
						'invDate'                => $invDate,
						'compDetails'            => $compDetails,
						'custDetails'            => $custDetails,
						'stateBill'              => $locations['stateBill'],
						'cityBill'               => $locations['cityBill'],
						'stateShip'              => $locations['stateShip'],
						'cityShip'               => $locations['cityShip'],
						'special_discount'       => $special_discount,
						'special_discount_amount'=> $special_discount_amount,
						'special_discount_type'  => $special_discount_type,
						'bankDetails'            => $bankDetails,
					]
				)->setOptions([
					'dpi' => 150,
					'defaultFont' => 'sans-serif',
					'isHtml5ParserEnabled' => true,
					'isRemoteEnabled' => true
				]);
				
			//quotation
			case 'quotation':

				$quotation = DB::table('quotations')
					->where('id', $id)
					->first();

				if (!$quotation) {
					throw new \Exception('Quotation not found.');
				}

				$inv_num = $quotation->inv_num;
				$custId = $quotation->inv_name;
				$added_by = $quotation->added_by;
				$invDate = $quotation->created_at;

				$special_discount = $quotation->special_discount;
				$special_discount_amount = $quotation->special_discount_amount;
				$special_discount_type = $quotation->special_discount_type;

				$compDetails = $this->getCompanyDetails(
					$added_by,
					$quotation->propId
				);

				$custDetails = DB::table('customers')
					->where('id', $custId)
					->first();

				$locations = $this->getCustomerLocations($custDetails);

				$quotations_values = DB::table('quotations_values')
					->where('sid', $id)
					->get();

				$quotations_values = $this->prepareInvoiceItems(
					$quotations_values,
					$inv_num,
					$quotation
				);

				$inv_num = str_replace('/', '-', $inv_num);

				return \PDF::loadView(
					'emails.attached-invoice-pdf',
					[
						'quotations'       => $quotation,
						'quotations_values'=> $quotations_values,
						'inv_num'          => $inv_num,
						'invDate'          => $invDate,
						'compDetails'      => $compDetails,
						'custDetails'      => $custDetails,
						'stateBill'        => $locations['stateBill'],
						'cityBill'         => $locations['cityBill'],
						'stateShip'        => $locations['stateShip'],
						'cityShip'         => $locations['cityShip'],
					]
				)->setOptions([
					'dpi' => 150,
					'defaultFont' => 'sans-serif',
					'isHtml5ParserEnabled' => true,
					'isRemoteEnabled' => true
				]);

			//proforma
			case 'proforma':

				$proforma = DB::table('proformas')
					->where('id', $id)
					->first();

				if (!$proforma) {
					throw new \Exception('Proforma invoice not found.');
				}

				$inv_num = $proforma->inv_num;
				$custId = $proforma->inv_name;
				$added_by = $proforma->added_by;
				$invDate = $proforma->created_at;

				$compDetails = $this->getCompanyDetails(
					$added_by,
					$proforma->propId
				);

				$custDetails = DB::table('customers')
					->where('id', $custId)
					->first();

				$locations = $this->getCustomerLocations($custDetails);

				$proformas_values = DB::table('proformas_values')
					->where('sid', $id)
					->get();

				$proformas_values = $this->prepareInvoiceItems(
					$proformas_values,
					$inv_num,
					$proforma
				);

				$inv_num = str_replace('/', '-', $inv_num);

				return \PDF::loadView(
					'emails.attached-invoice-pdf',
					[
						'proformas'        => $proforma,
						'proformas_values' => $proformas_values,
						'inv_num'          => $inv_num,
						'invDate'          => $invDate,
						'compDetails'      => $compDetails,
						'custDetails'      => $custDetails,
						'stateBill'        => $locations['stateBill'],
						'cityBill'         => $locations['cityBill'],
						'stateShip'        => $locations['stateShip'],
						'cityShip'         => $locations['cityShip'],
					]
				)->setOptions([
					'dpi' => 150,
					'defaultFont' => 'sans-serif',
					'isHtml5ParserEnabled' => true,
					'isRemoteEnabled' => true
				]);

			//purchase
			case 'purchase':

				$purchase = DB::table('purchases')
					->where('id', $id)
					->first();

				if (!$purchase) {
					throw new \Exception('Purchase invoice not found.');
				}

				$inv_num = $purchase->inv_num;
				$custId = $purchase->inv_name;
				$added_by = $purchase->added_by;
				$invDate = $purchase->created_at;

				$compDetails = $this->getCompanyDetails(
					$added_by,
					$purchase->propId
				);

				$custDetails = DB::table('vendors')
					->where('id', $custId)
					->first();

				$locations = $this->getVendorLocations($custDetails);

				$sales_values = DB::table('purchase_values')
					->where('sid', $id)
					->get();

				$sales_values = $this->prepareInvoiceItems(
					$sales_values,
					$inv_num,
					$purchase
				);

				$inv_num = str_replace('/', '-', $inv_num);

				return \PDF::loadView(
					'emails.attached-invoice-pdf',
					[
						'sales'        => $purchase,
						'sales_values' => $sales_values,
						'inv_num'      => $inv_num,
						'invDate'      => $invDate,
						'compDetails'  => $compDetails,
						'custDetails'  => $custDetails,
						'stateBill'    => $locations['stateBill'],
						'cityBill'     => $locations['cityBill'],
						'stateShip'    => $locations['stateShip'],
						'cityShip'     => $locations['cityShip'],
					]
				)->setOptions([
					'dpi' => 150,
					'defaultFont' => 'sans-serif',
					'isHtml5ParserEnabled' => true,
					'isRemoteEnabled' => true
				]);

			//PO
			case 'po':

				$po = DB::table('puos')
					->where('id', $id)
					->first();

				if (!$po) {
					throw new \Exception('Purchase Order not found.');
				}

				$inv_num = $po->inv_num;
				$custId = $po->inv_name;
				$added_by = $po->added_by;
				$invDate = $po->created_at;

				$compDetails = $this->getCompanyDetails(
					$added_by,
					$po->propId
				);

				$custDetails = DB::table('vendors')
					->where('id', $custId)
					->first();

				$locations = $this->getVendorLocations($custDetails);

				$puo_values = DB::table('puo_values')
					->where('sid', $id)
					->get();

				$puo_values = $this->prepareInvoiceItems(
					$puo_values,
					$inv_num,
					$po
				);

				$inv_num = str_replace('/', '-', $inv_num);

				return \PDF::loadView(
					'emails.attached-invoice-pdf',
					[
						'sales'        => $po,
						'sales_values' => $puo_values,
						'inv_num'      => $inv_num,
						'invDate'      => $invDate,
						'compDetails'  => $compDetails,
						'custDetails'  => $custDetails,
						'stateBill'    => $locations['stateBill'],
						'cityBill'     => $locations['cityBill'],
						'stateShip'    => $locations['stateShip'],
						'cityShip'     => $locations['cityShip'],
					]
				)->setOptions([
					'dpi' => 150,
					'defaultFont' => 'sans-serif',
					'isHtml5ParserEnabled' => true,
					'isRemoteEnabled' => true
				]);


			default:
				throw new \Exception('Invalid document type.');
		}
	}
	
	private function getCompanyDetails($addedBy, $propId = null)
	{
		return DB::table('users')
			->leftJoin('company_profiles as cp', 'users.id', '=', 'cp.userId')
			->leftJoin(
				'proprietorship_profiles as pp',
				'pp.id',
				'=',
				DB::raw((int) $propId)
			)
			->select(
				'users.name',
				DB::raw("
					CASE
						WHEN '{$propId}' IS NOT NULL
						AND '{$propId}' != ''
						THEN pp.comp_name
						ELSE cp.comp_name
					END as comp_name
				"),
				'cp.gst_no',
				'cp.comp_pan_no',
				'cp.comp_bill_addone'
			)
			->where('users.id', $addedBy)
			->first();
	}
	
	private function getCustomerLocations($customer)
	{
		return [
			'stateBill' => !empty($customer?->cust_bill_state)
				? DB::table('states')
					->where('id', $customer->cust_bill_state)
					->value('name')
				: null,

			'cityBill' => !empty($customer?->cust_bill_city)
				? DB::table('cities')
					->where('id', $customer->cust_bill_city)
					->value('name')
				: null,

			'stateShip' => !empty($customer?->cust_ship_state)
				? DB::table('states')
					->where('id', $customer->cust_ship_state)
					->value('name')
				: null,

			'cityShip' => !empty($customer?->cust_ship_city)
				? DB::table('cities')
					->where('id', $customer->cust_ship_city)
					->value('name')
				: null,
		];
	}
	
	private function getVendorLocations($vendor)
	{
		return [
			'stateBill' => !empty($vendor?->billing_state)
				? DB::table('states')
					->where('id', $vendor->billing_state)
					->value('name')
				: null,

			'cityBill' => !empty($vendor?->billing_city)
				? DB::table('cities')
					->where('id', $vendor->billing_city)
					->value('name')
				: null,

			'stateShip' => !empty($vendor?->shipping_state)
				? DB::table('states')
					->where('id', $vendor->shipping_state)
					->value('name')
				: null,

			'cityShip' => !empty($vendor?->shipping_city)
				? DB::table('cities')
					->where('id', $vendor->shipping_city)
					->value('name')
				: null,
		];
	}
	
	private function prepareInvoiceItems($items, $invNum, $document)
	{
		$array = [];

		foreach ($items as $k => $val) {
			$array[$k] = (array) $val;
			if ($val->prod_id > 0) {
				$item = Product::find($val->prod_id);
				if ($item) {
					$array[$k]['item_name'] = $item->item_type == 'service' ? $item->service_name : $item->item_name;
					$array[$k]['base_unit'] = $item->base_unit ?? '';
					$array[$k]['sec_unit'] = $item->sec_unit ?? '';
					$array[$k]['sac_code'] = $item->sac_code ?? '';
					$array[$k]['hsn_code'] = $item->hsn_code ?? '';
				}

			} else {
				$array[$k]['item_name'] = '';
				$array[$k]['base_unit'] = '';
				$array[$k]['sec_unit'] = '';
				$array[$k]['sac_code'] = '';
				$array[$k]['hsn_code'] = '';
			}

			$array[$k]['inv_num'] = $invNum;
			$array[$k]['added_by'] = $document->added_by;
			$array[$k]['signature'] = $document->signature ?? '';
			$array[$k]['signature_name'] = $document->signature_name ?? '';
		}

		return json_decode(json_encode($array));
	}

}