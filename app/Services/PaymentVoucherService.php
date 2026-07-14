<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\PaymentVoucher;
use App\Models\Journals;
use Carbon\Carbon;

class PaymentVoucherService
{
    
    public function __construct()
    {
        
    }
	
	public function getPaymentMode($mode)
	{
		$mode = strtoupper(trim($mode));
		if (in_array($mode, ['IMPS', 'RTGS', 'NEFT', 'CARD','CHEQUE', 'DEMAND DRAFT','PAY ORDER', 'BANK'])) {
			return 'Bank';
		} elseif ($mode == 'UPI') {
			return 'UPI';
		} elseif ($mode == 'CASH') {
			return 'Cash';
		}
		return 'Cash';
	}


	// ===============================================
	// GENERATE VOUCHER NO
	// ===============================================
	public function getVoucherNo($userId, $voucherType)
	{
		$prefix = ($voucherType == 'Receipt Voucher') ? 'RV' : 'PV';
		$year = date('y');

		// =========================================
		// GET LAST VOUCHER
		// =========================================
		$lastVoucher = PaymentVoucher::where('voucher_type', $voucherType)
			->where('added_by', $userId)
			->orderBy('id', 'DESC')
			->first();

		$nextNumber = 1;

		// =========================================
		// EXTRACT LAST NUMBER
		// =========================================

		if ($lastVoucher) {
			preg_match('/(\d+)$/', $lastVoucher->voucher_no, $matches);
			if (isset($matches[1])) {
				$nextNumber = intval($matches[1]) + 1;
			}
		}

		return $prefix . '-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
	}



	// ===============================================
	// SALES / PURCHASE PAYMENT VOUCHER ENTRY
	// ===============================================
	public function storePaymentVoucherEntries($id, $source = 'Sales', $currentPayment = 0, array $data = [])
	{
		DB::beginTransaction();

		try {

			$userId = currentOwnerId();

			// ======================================================
			// SALES
			// ======================================================

			if ($source == 'Sales') 
			{

				$sales = DB::table('sales as s')
					->leftJoin('customers as c', 'c.id', '=', 's.inv_name')
					->leftJoin('sales_values as sv', 'sv.sid', '=', 's.id')
					->where('s.id', $id)
					->select(
						's.id',
						's.propId',
						's.inv_date',
						's.mode_of_pay',
						's.pay_status',
						's.advance_amount',
						's.due_amount',
						's.supplier_refno',

						'c.id as customer_id',
						'c.cust_name',

						DB::raw('SUM(sv.amount) as total_amount'),
						DB::raw('SUM(sv.tax_amt) as total_gst'),
						DB::raw('SUM(sv.gov_pay) as gov_pay'),
						DB::raw('SUM(sv.ser_pay) as ser_pay')
					)
					->groupBy(
						's.id',
						's.propId',
						's.inv_date',
						's.mode_of_pay',
						's.pay_status',
						's.advance_amount',
						's.due_amount',
						's.supplier_refno',
						'c.id',
						'c.cust_name'
					)
					->first();

					if (!$sales) {
						return false;
					}

					$voucherType = 'Receipt Voucher';
					$propId = $sales->propId;
					$date = $data['date'] ?? $sales->inv_date;
					$invoiceNo = $id; //$sales->inv_num;
					$partyType = 'Customer';
					$partyId = $sales->customer_id ?? null;
					$partyName = $sales->cust_name ?? '';	
					
					$amount = $currentPayment;
					$transactionDetails = '';
					if (($sales->due_amount ?? 0) <= 0)
					{
						$transactionDetails = 'Adjustment';
					}
					else
					{
						$transactionDetails = 'Advance';
					}
					$creditDebit = 'Credit';
					$paymentMode = $this->getPaymentMode($data['payment_mode'] ?? $sales->mode_of_pay ?? null);
					$bankId = $data['bank_id'] ?? null;
					$referenceId = $sales->supplier_refno ?? null;
					$narration = 'Sales Invoice Entry';
			}
			// ======================================================
			// Proforma
			// ======================================================
			else if ($source == 'Proforma') 
			{

				$sales = DB::table('proformas as s')
					->leftJoin('customers as c', 'c.id', '=', 's.inv_name')
					->leftJoin('proformas_values as sv', 'sv.sid', '=', 's.id')
					->where('s.id', $id)
					->select(
						's.id',
						's.propId',
						's.inv_date',
						's.mode_of_pay',
						's.pay_status',
						's.advance_amount',
						's.due_amount',
						's.supplier_refno',

						'c.id as customer_id',
						'c.cust_name',

						DB::raw('SUM(sv.amount) as total_amount'),
						DB::raw('SUM(sv.tax_amt) as total_gst'),
						DB::raw('SUM(sv.gov_pay) as gov_pay'),
						DB::raw('SUM(sv.ser_pay) as ser_pay')
					)
					->groupBy(
						's.id',
						's.propId',
						's.inv_date',
						's.mode_of_pay',
						's.pay_status',
						's.advance_amount',
						's.due_amount',
						's.supplier_refno',
						'c.id',
						'c.cust_name'
					)
					->first();

					if (!$sales) {
						return false;
					}

					$voucherType = 'Receipt Voucher';
					$propId = $sales->propId;
					$date = $data['date'] ?? $sales->inv_date;
					$invoiceNo = $id; //$sales->inv_num;
					$partyType = 'Customer';
					$partyId = $sales->customer_id ?? null;
					$partyName = $sales->cust_name ?? '';	
					
					$amount = $currentPayment;
					$transactionDetails = '';
					if (($sales->due_amount ?? 0) <= 0)
					{
						$transactionDetails = 'Adjustment';
					}
					else
					{
						$transactionDetails = 'Advance';
					}
					$creditDebit = 'Credit';
					$paymentMode = $this->getPaymentMode($data['payment_mode'] ?? $sales->mode_of_pay ?? null);
					$bankId = $data['bank_id'] ?? null;
					$referenceId = $sales->supplier_refno ?? null;
					$narration = 'Proforma Invoice Entry';
			}
			// ======================================================
			// PURCHASE
			// ======================================================

			elseif ($source == 'Purchase') 
			{
				$purchase = DB::table('purchases as p')
					->leftJoin('vendors as v', 'v.id', '=', 'p.inv_name')
					->leftJoin('purchase_values as pv', 'pv.sid', '=', 'p.id')
					->where('p.id', $id)
					->select(
						'p.id',
						'p.propId',
						'p.inv_date',
						'p.mode_of_pay',
						'p.pay_status',
						'p.advance_amount',
						'p.due_amount',
						'p.supplier_refno',

						'v.id as vendor_id',
						'v.vendor_name',

						DB::raw('SUM(pv.amount) as total_amount'),
						DB::raw('SUM(pv.tax_amt) as total_gst')
					)
					->groupBy(
						'p.id',
						'p.propId',
						'p.inv_date',
						'p.mode_of_pay',
						'p.pay_status',
						'p.advance_amount',
						'p.due_amount',
						'p.supplier_refno',
						'v.id',
						'v.vendor_name'
					)
					->first();

				if (!$purchase) {
					return false;
				}

				$voucherType = 'Payment Voucher';
				$propId = $purchase->propId;
				$date = $data['date'] ?? $purchase->inv_date;
				$invoiceNo = $id; //$purchase->inv_num;
				$partyType = 'Vendor';
				$partyId = $purchase->vendor_id ?? null;
				$partyName = $purchase->vendor_name ?? '';

				$amount = $currentPayment;
				$transactionDetails = '';
				if (($purchase->due_amount ?? 0) <= 0)
				{
					$transactionDetails = 'Adjustment';
				}
				else
				{
					$transactionDetails = 'Advance';
				}
				$creditDebit = 'Debit';
				$paymentMode = $this->getPaymentMode($data['payment_mode'] ?? $purchase->mode_of_pay ?? null);
				$bankId = $data['bank_id'] ?? null;
				$referenceId = $purchase->supplier_refno ?? null;
				$narration = 'Purchase Invoice Entry';
			}
			
			// ======================================================
			// EXPENSE
			// ======================================================

			elseif ($source == 'Expense') 
			{
				$expense = DB::table('expenses as e')
					->leftJoin('vendors as v', 'v.id', '=', 'e.vendor_id')
					->leftJoin('users as emp', 'emp.id', '=', 'e.employee_id')
					->where('e.id', $id)
					->select(
						'e.id',
						'e.propId',
						'e.expense_type',
						'e.expense_date',
						'e.mode_of_expense',
						'e.payment_status',
						'e.advance_amount',
						'e.expense_amt',
						'e.total_gst',
						'e.exp_invno',
						'e.approved_by',

						'v.id as vendor_id',
						'v.vendor_name',

						'emp.id as employee_id',
						'emp.name as employee_name'
					)
					->first();

				if (!$expense) {
					return false;
				}

				$voucherType = 'Payment Voucher';
				$propId = $expense->propId;
				$date = $expense->expense_date;
				$invoiceNo = $expense->id;
				$partyType = 'Vendor';
				// ==========================================
				// PARTY NAME PRIORITY
				// Vendor -> Employee -> Other
				// ==========================================

				if (!empty($expense->employee_id) && $expense->expense_type == 'employee_benefits') {
					$partyType = 'Employee';
					$partyId = $expense->employee_id;
					$partyName = $expense->employee_name ?? '';

				}else if (!empty($expense->vendor_id)) {
					$partyType = 'Vendor';
					$partyId = $expense->vendor_id;
					$partyName = $expense->vendor_name ?? '';
				} else {
					$partyType = 'Other';
					$partyId = null;
					$partyName = 'Expense Entry';
				}

				// ==========================================
				// AMOUNT LOGIC
				// Full -> Expense + GST
				// Advance -> Advance Amount
				// ==========================================

				$amount = $currentPayment;
				$transactionDetails = '';
				if (strtolower($expense->payment_status) == 'full') {
					$transactionDetails = 'Adjustment';
				} else if (strtolower($expense->payment_status) == 'advance') {
					$transactionDetails = 'Advance';
				}else{	
					$transactionDetails = 'Due';
				}
				
				if ($expense->payment_status == 'due') {
					return true; // don't create voucher
				}
				$creditDebit = 'Debit';
				$paymentMode = $this->getPaymentMode($data['payment_mode'] ?? $expense->mode_of_pay ?? null);
				$bankId = $data['bank_id'] ?? null;
				$referenceId = $expense->exp_invno ?? null;
				$narration = 'Expense Entry';
				$approved_by = $expense->approved_by ?? null;
			}
			
			// ======================================================
			// INCOME
			// ======================================================

			elseif ($source == 'Income') 
			{
				$income = DB::table('income as i')
					->where('i.id', $id)
					->select(
						'i.id',
						'i.propId',
						'i.dateInput',
						'i.incomeType',
						'i.categoryIncome',
						'i.other_income',
						'i.amount',
						'i.receivable_amt',
						'i.adjust_amt',
						'i.advance_amt',
						'i.invoice_no',
						'i.pay_status',
						'i.pay_mode',
						'i.customer_name',
						'i.specification',
						'i.gst_amt'
					)
					->first();

				if (!$income) {
					return false;
				}
				

				$voucherType = 'Receipt Voucher';
				$propId = $income->propId;
				$date = $income->dateInput;
				$invoiceNo = $income->id;
				// ==========================================
				// PARTY DETAILS
				// ==========================================
				if (!empty($income->customer_name)) {
					$partyType = 'Customer';
					$partyId = null;
					$partyName = $income->customer_name ?? '';
				} else {
					$partyType = 'Other';
					$partyId = null;
					$partyName = 'Income Entry';
				}
				// ==========================================
				// AMOUNT LOGIC
				// Full -> Amount + GST
				// Advance -> Advance Amount
				// ==========================================
				$amount = $currentPayment;
				$transactionDetails = '';
				if (strtolower($income->pay_status) == 'full') {
					$transactionDetails = 'Adjustment';
				} else if (strtolower($income->pay_status) == 'advance') {
					$transactionDetails = 'Advance';
				} else {
					$transactionDetails = 'Due';
				}
				
				if ($income->pay_status == 'Due') {
					return true; // don't create voucher
				}
				// ==========================================
				// TRANSACTION DETAILS
				// ==========================================
				$creditDebit = 'Credit';
				$paymentMode = $this->getPaymentMode($income->pay_mode ?? '');
				$referenceId = $income->invoice_no ?? null;
				$narration = $income->categoryIncome ?? 'Income Entry';
				$approved_by = null;
			}
			// ======================================================
			// ASSET
			// ======================================================

			elseif ($source == 'Asset')
			{
				$asset = DB::table('assets as a')
					->leftJoin('vendors as v', function ($join) {
						$join->on('v.id', '=', 'a.vendor_id')
							 ->orOn('v.id', '=', 'a.cwip_vendor_id');
					})
					->where('a.id', $id)
					->select(
						'a.id',
						'a.propId',
						'a.date',

						'a.assetType',
						'a.nonCurrentAssetType',

						'a.asset_name',
						'a.asset_category',

						'a.invoice_no',
						'a.invoice_date',
						'a.invoice_value',

						'a.pay_status',
						'a.advance_amt',
						'a.adjusted_amt',

						'a.cwip_invoice_no',
						'a.cwip_amount',

						'a.cwip_pay_status',
						'a.cwip_advance_amt',
						'a.cwip_adjusted_amt',

						'a.vendor_id',
						'a.cwip_vendor_id',

						'v.id as vendorId',
						'v.vendor_name'
					)

					->first();

				if (!$asset) {
					return false;
				}

				$voucherType = 'Payment Voucher';
				$propId = $asset->propId;
				$date = $asset->date;
				$isWip = (
					strtolower($asset->assetType ?? '') == 'non-current'
					&&
					strtolower($asset->nonCurrentAssetType ?? '') == 'capital work in progress'
				);

				if ($isWip) {
					$invoiceNo = $asset->cwip_invoice_no;

				} else {
					$invoiceNo = $asset->invoice_no;
				}

				// ==========================================
				// PARTY DETAILS
				// ==========================================

				if (!empty($asset->vendorId)) {
					$partyType = 'Vendor';
					$partyId = $asset->vendorId;
					$partyName = $asset->vendor_name ?? '';
				} else {
					$partyType = 'Other';
					$partyId = null;
					$partyName = 'Asset Entry';
				}

				$amount = $currentPayment;

				if ($isWip) {
					$paymentStatus = strtolower(trim($asset->cwip_pay_status ?? ''));
				} else {
					$paymentStatus = strtolower(trim($asset->pay_status ?? ''));
				}
				
				if ($paymentStatus == 'due') {
					return true; // don't create voucher
				}

				$transactionDetails = '';
				if ($paymentStatus == 'full') {
					$transactionDetails = 'Adjustment';
				} else if ($paymentStatus == 'advance') {
					$transactionDetails = 'Advance';
				} else {
					$transactionDetails = 'Due';
				}

				$creditDebit = 'Debit';
				$paymentMode = 'Bank';
				$referenceId = $invoiceNo ?? null;
				$narration = $isWip ? 'CWIP Asset Entry' : ($asset->asset_category ?? 'Asset Entry');
				$approved_by = null;
			}
			// ======================================================
			// Liability
			// ======================================================
			elseif ($source == 'Liability')
			{
				$liability = DB::table('liabilities as l')
					->leftJoin('current_liabilities as cl', 'cl.liabilities_id', '=', 'l.id')
					->leftJoin('non_current_liabilities as ncl', 'ncl.liabilities_id', '=', 'l.id')
					->leftJoin('share_application_money_liabilities as sam', 'sam.liabilities_id', '=', 'l.id')
					->leftJoin('share_holder_fund_liabilities as shf', 'shf.liabilities_id', '=', 'l.id')
					->where('l.id', $id)
					->select(

						// ================= MAIN =================
						'l.id',
						'l.propId',
						'l.added_date',
						'l.liabilities_type',

						// ================= CURRENT =================
						'cl.CurrentLiabilitiesType',
						// SHORT TERM LOAN LENDER
						'cl.stl_lender_name',

						// INTEREST PAYABLE LENDER
						'cl.ip_lender_name',
						'cl.amount as cl_amount',
						'cl.invoice_no as cl_invoice_no',
						'cl.voucher_type as cl_voucher_type',
						'cl.debit_credit as cl_debit_credit',
						'cl.notes as cl_notes',

						'cl.advamorecd',
						'cl.amount_current_lib',
						'cl.stl_amount_received',
						'cl.ip_interest_amount',
						'cl.gst_payableamount',
						'cl.tds_payableamount',
						'cl.unrevenueamount',
						'cl.prorateamount',

						// ================= NON CURRENT =================
						'ncl.liability_category',
						'ncl.party_name as ncl_party_name',
						'ncl.amount as ncl_amount',
						'ncl.invoice_no as ncl_invoice_no',
						'ncl.notes as ncl_notes',

						'ncl.dtl_amount',
						'ncl.lease_liability_amount',
						'ncl.provision_amount',

						// ================= SHARE APPLICATION =================
						'sam.applicant_name',
						'sam.amount_received',
						'sam.payment_mode',

						// ================= SHARE HOLDER =================
						'shf.share_holder_fund_type',
						'shf.total_amount',
						'shf.transfer_amount',
						'shf.total_dividend_amount',
						'shf.reserves_surplus_type',
						'shf.description'

					)

					->first();

				if (!$liability) {
					return false;
				}

				// ======================================================
				// COMMON
				// ======================================================

				$voucherType = 'Payment Voucher';

				$propId = $liability->propId;

				$date = $liability->added_date;

				$invoiceNo = null;

				$partyType = 'Other';

				$partyId = null;

				$partyName = 'Liability Entry';

				$transactionDetails = 'Adjustment';

				$creditDebit = 'Credit';

				$paymentMode = 'Bank';

				$referenceId = null;

				$narration = 'Liability Entry';

				$approved_by = null;

				$amount = $currentPayment;

				// ======================================================
				// CURRENT LIABILITY
				// ======================================================

				if ($liability->liabilities_type == 'current_liabilities') {

					$invoiceNo = $liability->cl_invoice_no;
					$referenceId = $liability->cl_invoice_no;				
					$currentType = strtolower($liability->CurrentLiabilitiesType ?? '');
					if ($currentType == 'short_term_loans') {
						$partyName = $liability->stl_lender_name ?? 'Short Term Loan';
					}
					else if ($currentType == 'interest_payable') {
						$partyName = $liability->ip_lender_name ?? 'Interest Payable';
					}
					else {
						$partyName = 'Current Liability';
					}

					$narration = $liability->CurrentLiabilitiesType ?? 'Current Liability';

					// ----------------------------------------
					// TRANSACTION DETAILS
					// ----------------------------------------

					if (
						!empty($liability->advamorecd)
						||
						!empty($liability->stl_amount_received)
					) {
						$transactionDetails = 'Advance';
					}
					else {
						$transactionDetails = 'Adjustment';
					}

					// ----------------------------------------
					// PAYMENT MODE
					// ----------------------------------------

					if (
						strtolower($liability->cl_voucher_type ?? '') == 'cash'
					) {
						$paymentMode = 'Cash';
					}
					else {
						$paymentMode = 'Bank';
					}
				}

				// ======================================================
				// NON CURRENT LIABILITY
				// ======================================================

				else if ($liability->liabilities_type == 'non_current_liabilities') {

					$invoiceNo = $liability->ncl_invoice_no;

					$referenceId = $liability->ncl_invoice_no;

					$partyName = $liability->ncl_party_name ?? 'Non Current Liability';

					$narration = $liability->liability_category ?? 'Non Current Liability';

					$transactionDetails = 'Adjustment';

					$paymentMode = 'Bank';
				}

				// ======================================================
				// SHARE APPLICATION MONEY
				// ======================================================

				else if ($liability->liabilities_type == 'share_application_money') {

					$partyType = 'Customer';

					$partyName = $liability->applicant_name ?? 'Share Applicant Money';

					$narration = 'Share Application Money';

					$transactionDetails = 'Advance';

					$paymentMode = $this->getPaymentMode($liability->payment_mode ?? '');

					$referenceId = null;
				}

				// ======================================================
				// SHARE HOLDER FUND
				// ======================================================

				else if ($liability->liabilities_type == 'share_holder_fund') {

					$partyName = 'Share Holder Fund';

					$narration = $liability->share_holder_fund_type ?? 'Share Holder Fund';

					$reserveType = strtolower(
						$liability->reserves_surplus_type ?? ''
					);

					if ($reserveType == 'dividend_declared') {

						$transactionDetails = 'Dividend';
					}
					else if ($reserveType == 'transfer_to_reserve') {

						$transactionDetails = 'Transfer';
					}
					else {

						$transactionDetails = 'Capital';
					}

					$paymentMode = 'Bank';

					$referenceId = null;
				}
				
				// CHECK EXISTING VOUCHER
				$existingVoucher = PaymentVoucher::where('added_by', $userId)
					->where('f_id', $id)
					->where('source', 'Liability')
					->first();

				$voucherData = [
					'source'              => $source,
					'added_by'            => $userId,
					'propId'              => $propId ?? null,
					'f_id'                => $id ?? null,
					'voucher_type'        => $voucherType,
					'date'                => $date,
					'party_type'          => $partyType,
					'party_id'            => $partyId,
					'party_name'          => $partyName,
					'transaction_details' => $transactionDetails,
					'invoice_no'          => $invoiceNo,
					'amount'              => $amount,
					'credit_debit'        => $creditDebit,
					'payment_mode'        => $paymentMode,
					'reference_id'        => $referenceId,
					'narration'           => $narration,
					'approved_by'         => $approved_by,
					'record_type'         => 'Posted',
				];

				// UPDATE EXISTING
				if ($existingVoucher) {
					$existingVoucher->update($voucherData);
					
					DB::commit();
					return true;
				}
			}
			elseif ($source == 'Journal')
			{
				$journals = Journals::find($id);

				if (!$journals) {
					return false;
				}

				$voucherType = strtolower($journals->debit_credit) == 'credit' ? 'Receipt Voucher' : 'Payment Voucher';

				$paymentVoucher = PaymentVoucher::where('source', 'Journal')
					->where('f_id', $journals->id)
					->first();

				$voucherNo = $paymentVoucher
					? $paymentVoucher->voucher_no
					: $this->getVoucherNo($userId, $voucherType);

				$propId             = $journals->propId;
				$date               = $journals->journal_date;
				$invoiceNo          = $journals->id;
				$partyType          = $journals->ledger;
				$partyId            = null;
				$partyName          = $journals->ledger;
				$amount             = $journals->amount;
				$transactionDetails = $journals->reference_type;
				$creditDebit        = $journals->debit_credit;
				$paymentMode        = 'Bank';
				$referenceId        = $journals->reference_no;
				$narration          = $journals->notes;

				if ($paymentVoucher) {

					$paymentVoucher->update([
						'added_by'            => $userId,
						'propId'              => $propId,
						'voucher_type'        => $voucherType,
						'date'                => $date,
						'voucher_no'          => $voucherNo,
						'party_type'          => $partyType,
						'party_id'            => $partyId,
						'party_name'          => $partyName,
						'transaction_details' => $transactionDetails,
						'invoice_no'          => $invoiceNo,
						'amount'              => $amount,
						'credit_debit'        => $creditDebit,
						'payment_mode'        => $paymentMode,
						'reference_id'        => $referenceId,
						'narration'           => $narration,
						'record_type'         => 'Posted',
					]);

					DB::commit();
					return true;
				}
			}

			// GET VOUCHER NO
			$voucherNo = $this->getVoucherNo($userId,$voucherType);

			// Insert record
			PaymentVoucher::create([

				'source'       	 => $source,
				'added_by'       => $userId,
				'propId'         => $propId ?? null,
				'f_id'         	 => $id ?? null,
				'voucher_type'   => $voucherType,
				'date'           => $date,
				'voucher_no'     => $voucherNo,
				'party_type'     => $partyType,
				'party_id'       => $partyId,
				'party_name'     => $partyName,
				'transaction_details' => $transactionDetails,
				'invoice_no'     => $invoiceNo,
				'amount'         => $amount,
				'credit_debit'   => $creditDebit,
				'payment_mode'   => $paymentMode,
				'bank_id'   	 => $bankId,
				'reference_id'   => $referenceId,
				'narration'      => $narration,
				'approved_by'    => auth()->user()->name ?? null,
				'record_type'    => 'Posted',
			]);

			DB::commit();

			return true;

		} catch (\Exception $e) {

			DB::rollback();

			\Log::error('Payment Voucher Error : ' . $e->getMessage());

			return false;
		}
	}
	
	

}
