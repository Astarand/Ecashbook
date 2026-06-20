<?php

namespace App\Http\Controllers\User;

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
use App\Models\Loans;

use App\Models\Banks;
use App\Models\Bank_trans;
use App\Models\Loan_ins;
use App\Models\Cash_credit_debits;
use App\Models\Mcash_credit_debits;
use App\Models\Cash_hands;
use App\Models\Bank_statements;
use App\Models\PaymentVoucher;
use Helper;
use Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Helpers\AuditLogger;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class PayController extends Controller
{
    public function __construct(JournalService $journalService = null, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
        $this->paymentVoucherService = $paymentVoucherService;
    }
	
	public function getPayments($type,$id)
	{
		if($type == 'Sales')
		{
			$invoice = DB::table('sales_values')
				->where('sid',$id)
				->first();

			$invoiceTotal = getRoundedAmount($invoice->amount + $invoice->tax_amt + $invoice->ser_pay + $invoice->gov_pay);
		}else if($type == 'Proforma'){
			$invoice = DB::table('proformas_values')
				->where('sid',$id)
				->first();

			$invoiceTotal = getRoundedAmount($invoice->amount + $invoice->tax_amt + $invoice->ser_pay + $invoice->gov_pay);
		}else{
			$invoice = DB::table('purchase_values')
				->where('sid',$id)
				->first();

			$invoiceTotal = getRoundedAmount($invoice->amount + $invoice->tax_amt);
		}

		$payments = DB::table('payment_vouchers')
			->where('f_id',$id)
			->where('source',$type)
			->orderBy('date')
			->get();

		$paid = $payments->sum('amount');

		return response()->json([
			'invoice_total'=>$invoiceTotal,
			'total_paid'=>$paid,
			'balance_due'=>$invoiceTotal-$paid,
			'payments'=>$payments
		]);
	}
	
	public function storePayments(Request $request)
	{
		$uid = currentOwnerId();
		$sid = $request->f_id;

		DB::beginTransaction();

		try {
				
			// Check if journal already exist
			$hasOldJournal = DB::table('journals')
				->where('autoId', $request->f_id)
				->where('entry_type', $request->voucher_type)
				->exists();

			// Remove old journal entries once
			if ($hasOldJournal) {

				if ($request->voucher_type == 'Sales') {
					$this->deleteSalesPaymentJournalEntries($sid);
				} else if ($request->voucher_type == 'Proforma') {
					
				} else if ($request->voucher_type == 'Purchase') {
					$this->deletePurchasePaymentJournalEntries($sid);
				}
			}

			// Delete old payment vouchers
			DB::table('payment_vouchers')
				->where('f_id', $request->f_id)
				->where('source', $request->voucher_type)
				->delete();
				
			//Entry in payment voucher
			$rows = $request->input('rows', []);
			$totalPayment = 0;
			foreach ($rows as $row) {
				if (empty($row['amount'])) {
					continue;
				}
				$totalPayment += $row['amount'];
				
				$data = [
							'date' => $row['date'],
							'payment_mode' => $row['payment_mode']
						];
				$this->paymentVoucherService->storePaymentVoucherEntries($request->f_id,$request->voucher_type,$row['amount'],$data);
			}
			//update pay status	
			$this->updatePaymentStatus($request->voucher_type,$request->f_id); 
			
			
			//Create Due Journal using Full Invoice Amount
			if ($totalPayment <= 0) {

				if ($request->voucher_type == 'Sales') {

					$invoice = DB::table('sales_values')
						->where('sid', $sid)
						->selectRaw('
							SUM(amount) as amount,
							SUM(tax_amt) as tax_amt,
							SUM(gov_pay) as gov_pay,
							SUM(ser_pay) as ser_pay
						')
						->first();

					$invoiceAmount =($invoice->amount ?? 0) + ($invoice->tax_amt ?? 0) + ($invoice->gov_pay ?? 0) + ($invoice->ser_pay ?? 0);
					$this->salesJournalEntry($sid,$uid,$invoiceAmount,'Due');

				} else if ($request->voucher_type == 'Proforma') {
					//No journal entry
				} else if ($request->voucher_type == 'Purchase') {
					$invoice = DB::table('purchase_values')
						->where('sid', $sid)
						->selectRaw('
							SUM(amount) as amount,
							SUM(tax_amt) as tax_amt
						')
						->first();

					$invoiceAmount =($invoice->amount ?? 0) + ($invoice->tax_amt ?? 0);
					$this->purchaseJournalEntry($sid,$uid,$invoiceAmount,'Due');
				}
			}
			else 
			{
				//Entry in Journal for no due
				foreach ($rows as $val) {

					if (empty($val['amount'])) {
						continue;
					}
					$payment_mode = $val['payment_mode'];
					if ($request->voucher_type == 'Sales') {
						$this->salesJournalEntry($sid,$uid,$val['amount'],$payment_mode);
					}else if ($request->voucher_type == 'Proforma') {
						//No journal entry
					} else if ($request->voucher_type == 'Purchase') {
						$this->purchaseJournalEntry($sid,$uid,$val['amount'],$payment_mode);
					}
				}
			}

			DB::commit();

			return response()->json([
				'status' => true,
				'message' => 'Saved Successfully'
			]);

		} catch (\Exception $e) {

			DB::rollBack();

			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	}
	
	private function updatePaymentStatus($type,$id)
	{
		if($type=='Sales')
		{
			$invoice = DB::table('sales_values')
				->where('sid',$id)
				->first();

			$total = getRoundedAmount($invoice->amount + $invoice->tax_amt + $invoice->ser_pay + $invoice->gov_pay);

			$paid = DB::table('payment_vouchers')
				->where('f_id',$id)
				->where('source','Sales')
				->sum('amount');

			$status = 'Due';

			if($paid == 0)
			{
				$status = 'Due';
			}
			elseif($paid >= $total)
			{
				$status = 'Full';
			}
			else
			{
				$status = 'Partial';
			}

			DB::table('sales')
				->where('id',$id)
				->update([
					'pay_status'=>$status,
					'advance_amount'=>$paid,
					'adjusted_amount'=>$paid,
					'due_amount'=>max(0,$total-$paid)
				]);
		} 
		else if($type=='Proforma') 
		{
			$invoice = DB::table('proformas_values')
				->where('sid',$id)
				->first();

			$total = getRoundedAmount($invoice->amount + $invoice->tax_amt + $invoice->ser_pay + $invoice->gov_pay);

			$paid = DB::table('payment_vouchers')
				->where('f_id',$id)
				->where('source','Proforma')
				->sum('amount');

			$status = 'Due';

			if($paid == 0)
			{
				$status = 'Due';
			}
			elseif($paid >= $total)
			{
				$status = 'Full';
			}
			else
			{
				$status = 'Partial';
			}

			DB::table('proformas')
				->where('id',$id)
				->update([
					'pay_status'=>$status,
					'advance_amount'=>$paid,
					'adjusted_amount'=>$paid,
					'due_amount'=>max(0,$total-$paid)
				]);
		}
		else if($type=='Purchase') 
		{
			$invoice = DB::table('purchase_values')
				->where('sid',$id)
				->first();

			$total = getRoundedAmount($invoice->amount + $invoice->tax_amt);

			$paid = DB::table('payment_vouchers')
				->where('f_id',$id)
				->where('source','Purchase')
				->sum('amount');

			$status='Due';

			if($paid == 0)
			{
				$status='Due';
			}
			elseif($paid >= $total)
			{
				$status='Full';
			}
			else
			{
				$status='Partial';
			}

			DB::table('purchases')
				->where('id',$id)
				->update([
					'pay_status'=>$status,
					'advance_amount'=>$paid,
					'adjusted_amount'=>$paid,
					'due_amount'=>max(0,$total-$paid)
				]);
		}
	}
	
	public function deletePayment($id)
	{
		$voucher = DB::table('payment_vouchers')
			->where('id',$id)
			->first();

		DB::table('payment_vouchers')
			->where('id',$id)
			->delete();

		$this->updatePaymentStatus(
			$voucher->source,
			$voucher->f_id
		);

		return response()->json([
			'status'=>true
		]);
	}
	
	private function deleteSalesPaymentJournalEntries($salesId)
	{
		DB::table('journals')
			->where('entry_type', 'Sales')
			->where('autoId', $salesId)
			->delete();
	}

	private function deletePurchasePaymentJournalEntries($purchaseId)
	{
		DB::table('journals')
			->where('entry_type', 'Purchase')
			->where('autoId', $purchaseId)
			->delete();
	}
	
	//Start Journal Entry
	public function salesJournalEntry($sid,$uid,$amount,$payment_mode)
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
				'pay_status'    => $sale->pay_status ?? $payment_mode,
				'amount'    	=> $amount ?? 0,
				'total_amount'  => $totals->total_amount ?? 0,
				'base_amount'   => ($totals->total_amount - $totals->total_tax),
				'gst_amount'    => $totals->total_tax ?? 0,
				'gst_rate'      => $totals->avg_gst_rate ?? 0,
				'gst_trans'     => $totals->gst_trans ?? 'intrastate',
				'status'        => $sale->status,
			]);
		}
		
	}
	
	
	public function purchaseJournalEntry($sid,$uid,$amount,$payment_mode)
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
			$this->journalService->storePurchaseJournalEntries([
				'source'        => 'Purchase',
				'autoId'        => $sid,
				'added_by'      => $uid,
				'propId'        => $purchase->propId,
				'date'          => $purchase->inv_date,
				'reference_no'  => $purchase->inv_num,
				'entry_type'    => 'Purchase',
				'party_name'    => $purchase->vendor_name ?? '',
				'pay_status'    => $purchase->pay_status ?? $payment_mode,
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
	//End Journal Entry
	



}
