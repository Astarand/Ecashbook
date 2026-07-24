<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Journals;
use Carbon\Carbon;
use App\Services\ProfitLossService;

class BalanceSheetService
{
    
	private $profitLossService;

    public function __construct(ProfitLossService $profitLossService)
    {
        $this->profitLossService = $profitLossService;
    }
	
	
	public function getCurrentLiabilityAmount($type,$userId,$startDate, $endDate)
	{
		//$type = $request->type;
		//$userId = currentOwnerId();

		// Current month start & end date for payslip
		$currentMonthStartDate = $startDate;
		$currentMonthEndDate   = $endDate;

		$amount = 0;

		// Trade Payables
		if ($type == 'trade_payables') {
			 // Outstanding Purchase Amount (due->full)
			$purchaseAmount = DB::table('purchases as p')
							->leftJoin('purchase_values as pv', 'pv.sid', '=', 'p.id')
							->where('p.added_by', $userId)
							->where('p.status', 1)
							->whereBetween('p.inv_date', [$startDate, $endDate])
							->groupBy('p.id', 'p.advance_amount')
							->selectRaw('
								SUM(COALESCE(pv.amount, 0) + COALESCE(pv.tax_amt, 0))
								- COALESCE(p.advance_amount, 0) AS payable
							')
							->get()
							->sum('payable');
							
			// Purchase Credit/Debit Notes
			$voucherPurchaseTotals = DB::table('voucher_purchases')
								->selectRaw("
									SUM(CASE WHEN note_type='Credit' THEN total_amt ELSE 0 END) AS total_credit,
									SUM(CASE WHEN note_type='Debit' THEN total_amt ELSE 0 END) AS total_debit
								")
								->where('added_by', $userId)
								//->where('return_status', 'Received')
								//->where('status', 1)
								->whereBetween('inv_date', [$startDate, $endDate])
								->first();
								
			$expenseAmount = DB::table('expenses')
									->where('added_by', $userId)
									->whereBetween('expense_date', [$startDate, $endDate])
									->selectRaw('
										SUM(
											GREATEST(
												COALESCE(expense_amt, 0)
												- COALESCE(advance_amount, 0),
												0
											)
										) AS payable
									')
									->value('payable') ?? 0;

			//$purchaseCredit = $voucherPurchaseTotals->total_credit ?? 0;
			$purchaseDebit  = $voucherPurchaseTotals->total_debit ?? 0;
			$amount = ($purchaseAmount - $purchaseDebit + $expenseAmount);
		}

		// Advance from Customer
		if ($type == 'advance_from_customer') {

			$amount = DB::table('sales')
				->where('added_by', $userId)
				->where('pay_status', 'Partial')
				->whereBetween('inv_date', [$startDate, $endDate])
				->sum('advance_amount');
		}

		// Salary Payable
		if ($type == 'salary_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$currentMonthStartDate, $currentMonthEndDate])
				->where(function ($query) {
						$query->whereNull('payment_status')
							  ->orWhere('payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += $data['visible_data']['final_salary_calculation']['net_salary'] ?? 0;
				}
			}
		}

		// PF Payable
		if ($type == 'pf_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->where(function ($query) {
						$query->whereNull('pf_payment_status')
							  ->orWhere('pf_payment_status', 'Pending');
					})
				->get();

			$amount = 0;
			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += (float) ($data['visible_data']['final_salary_calculation']['provident_fund'] ?? 0);
				}
			}
		}

		// ESI Payable
		if ($type == 'esi_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->where(function ($query) {
						$query->whereNull('esi_payment_status')
							  ->orWhere('esi_payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += (float) ($data['visible_data']['final_salary_calculation']['esi'] ?? 0);
				}
			}
		}
		
		// LWF Payable
		if ($type == 'lwf_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->where(function ($query) {
						$query->whereNull('lwf_payment_status')
							  ->orWhere('lwf_payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				if (($data['created_by'] ?? 0) == $userId) {
					$lwfEmployee = $data['visible_data']['final_salary_calculation']['lwf_deduct'] ?? 0;
					$lwfCompany = $data['visible_data']['final_salary_calculation']['lwf_company_contribution'] ?? 0;
					$amount += (float) $lwfEmployee + (float) $lwfCompany;
				}
			}
		}

		// GST Payable
		if ($type == 'gst_payable') {
			$gst = $this->calculateGST($userId, $startDate, $endDate);
			//$amount = $gst['gst_payable'];
			$amount = $gst['output_gst'];
		}

		// TDS Payable
		if ($type == 'tds_payable') {

			// Expenses TDS Amount
			$expenseTdsAmount = DB::table('expenses')
				->where('added_by', $userId)
				->where('tds_applicable', 'yes')
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('tds_amount');

			// Assets TDS Amount
			$assetTdsAmount = DB::table('assets')
				->where('added_by', $userId)
				->where('tds_applicable', 'yes')
				->whereBetween('date', [$startDate, $endDate])
				->sum('tds_amt');
			
			// Salary TDS Amount
			$salaryData = DB::table('user_payslip')
				->whereBetween('date', [$currentMonthStartDate, $currentMonthEndDate])
				->where(function ($query) {
						$query->whereNull('tds_deposit_status')
							  ->orWhere('tds_deposit_status', 'Pending');
					})
				->get();

			$salaryTdsAmount = 0;

			foreach ($salaryData as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				if (($data['created_by'] ?? 0) == $userId) {
					$salaryTdsAmount += $data['visible_data']['final_salary_calculation']['tds'] ?? 0;
				}
			}

			// Final Total
			$amount = $expenseTdsAmount + $assetTdsAmount + $salaryTdsAmount;
		}
		if($type =='short_term_loans'){
			$currLiab = DB::table('current_liabilities as cl')
					->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
					->whereBetween('l.added_date', [$startDate, $endDate]) // transaction date
					->where('l.status', 1) //only active records
					->where('l.added_by', $userId)
					->selectRaw("
						SUM(CASE WHEN cl.CurrentLiabilitiesType='short_term_loans' 
							THEN CAST(cl.stl_sanction_amount AS DECIMAL(15,2)) ELSE 0 END) 
							AS amount
					")
					->first();
			$amount = $currLiab->amount ?? 0;
			
		}
		if($type =='interest_payable'){
			$currLiab = DB::table('current_liabilities as cl')
					->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
					->whereBetween('l.added_date', [$startDate, $endDate]) // transaction date
					->where('l.status', 1) //only active records
					->where('l.added_by', $userId)
					->selectRaw("
						SUM(CASE WHEN cl.CurrentLiabilitiesType='interest_payable' 
							THEN CAST(cl.ip_interest_amount AS DECIMAL(15,2)) ELSE 0 END) 
							AS amount
					")
					->first();
					
			$amount = $currLiab->amount ?? 0;
		}

		return (float) $amount;
	}
	
	//Get current assets
	public function getCurrentAssetAmount($type, $userId, $startDate, $endDate)
	{
		$amount = 0;

		/*
		|--------------------------------------------------------------------------
		| Cash in Hand
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Cash in Hand') {

			// ================= MCASH =================
			$cashCredit = DB::table('mcash_credit_debits')
				->where('added_by', $userId)
				->where('cd_type', 'cr')
				->sum('cd_amount');

			$cashDebit = DB::table('mcash_credit_debits')
				->where('added_by', $userId)
				->where('cd_type', 'dr')
				->sum('cd_amount');


			// ================= PAYMENT VOUCHERS (CASH ONLY) =================
			$cashVoucherCredit = DB::table('payment_vouchers')
				->where('added_by', $userId)
				->where('payment_mode', 'Cash')
				->where('credit_debit', 'Credit')
				->sum('amount');

			$cashVoucherDebit = DB::table('payment_vouchers')
				->where('added_by', $userId)
				->where('payment_mode', 'Cash')
				->where('credit_debit', 'Debit')
				->sum('amount');


			// ================= FINAL CASH =================
			$amount = ($cashCredit + $cashVoucherCredit) - ($cashDebit + $cashVoucherDebit);
		}

		/*
		|--------------------------------------------------------------------------
		| Bank Accounts
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Bank Accounts') {

			// ================= BANK MASTER BALANCE =================
			$bankBalance = DB::table('banks')
				->where('added_by', $userId)
				->sum('curr_bal');


			// ================= PAYMENT VOUCHERS (BANK MODE) =================
			$bankVoucher = DB::table('payment_vouchers')
				->where('added_by', $userId)
				->whereIn('payment_mode', ['Bank', 'UPI'])
				->selectRaw("
					SUM(CASE WHEN credit_debit = 'Credit' THEN amount ELSE 0 END) as credit,
					SUM(CASE WHEN credit_debit = 'Debit' THEN amount ELSE 0 END) as debit
				")
				->first();


			// ================= FINAL BANK BALANCE =================
			$amount = ($bankBalance + ($bankVoucher->credit ?? 0)) - ($bankVoucher->debit ?? 0);
		}

		/*
		|--------------------------------------------------------------------------
		| Trade Receivables
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Trade Receivables') {

			// Sales Receivable
			$salesReceivable = DB::table('sales as s')
							->leftJoin('sales_values as sv', 'sv.sid', '=', 's.id')
							->where('s.added_by', $userId)
							->where('s.status', 1)
							->whereBetween('s.inv_date', [$startDate, $endDate])
							->groupBy('s.id', 's.advance_amount')
							->selectRaw("
								(
									SUM(COALESCE(sv.amount, 0) + COALESCE(sv.tax_amt, 0))
									- COALESCE(s.advance_amount, 0)
								) AS receivable
							")
							->get()
							->sum('receivable');

			// Income Receivable
			$incomeReceivable = DB::table('income')
				->where('addBy', $userId)
				->where('status', 1)
				->whereBetween('dateInput', [$startDate, $endDate])
				->selectRaw('SUM(COALESCE(amount,0) - COALESCE(advance_amt,0)) as receivable')
				->value('receivable');

			// Sales Credit/Debit Notes
			$voucherSalesTotals = DB::table('vouchers')
									->selectRaw("
										SUM(CASE WHEN note_type = 'Credit' THEN total_amt ELSE 0 END) AS total_credit,
										SUM(CASE WHEN note_type = 'Debit' THEN total_amt ELSE 0 END) AS total_debit
									")
									->where('added_by', $userId)
									//->where('return_status', 'Received')
									//->where('status', 1)
									->whereBetween('inv_date', [$startDate, $endDate])
									->first();

			$salesCredit = $voucherSalesTotals->total_credit ?? 0;
			//$salesDebit  = $voucherSalesTotals->total_debit ?? 0;
			$amount = ($salesReceivable - $salesCredit) + ($incomeReceivable ?? 0);
		}

		/*
		|--------------------------------------------------------------------------
		| Advance to Vendor
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Advance to Vendor') {

			$amount = DB::table('purchases')
					->where('added_by', $userId)
					->whereBetween('inv_date', [$startDate, $endDate])
					->sum('advance_amount');
		}

		/*
		|--------------------------------------------------------------------------
		| Employee Advance
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Employee Advance') {

			$amount = DB::table('expenses')
				->where('added_by', $userId)
				->where('expense_type', 'Employee Expenses')
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('advance_amount');
		}

		/*
		|--------------------------------------------------------------------------
		| Prepaid Expenses
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Prepaid Expenses') {

			$amount = DB::table('expenses')
				->where('added_by', $userId)
				->where('expense_type', 'prepaid_expense')
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('expense_amt');
		}

		/*
		|--------------------------------------------------------------------------
		| Input GST Credit (ITC)
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Input GST Credit') {
			$gst = $this->calculateGST($userId, $startDate, $endDate);
			//$amount = $gst['gst_receivable'];
			$amount = $gst['input_gst'];
		}

		/*
		|--------------------------------------------------------------------------
		| TDS Receivable
		|--------------------------------------------------------------------------
		*/
		if ($type == 'TDS Receivable') {

			$income = DB::table('income')
				->where('addBy', $userId)
				->whereBetween('dateInput', [$startDate, $endDate])
				->sum('tds_amount');

			$amount = $income;
		}

		/*
		|--------------------------------------------------------------------------
		| Inventories
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Inventories') {

			// OPTION 1: Stock valuation from sales (COST approach like COGS inverse)
			$soldQtyValue = DB::table('sales_values as sv')
				->join('sales as s', 's.id', '=', 'sv.sid')
				->join('products as p', 'p.id', '=', 'sv.prod_id')
				->where('s.added_by', $userId)
				->whereBetween('s.inv_date', [$startDate, $endDate])
				->selectRaw("
					COALESCE(SUM(sv.quantity * p.purchase_price), 0) as consumed_stock
				")
				->value('consumed_stock');

			// OPTION 2: Opening stock from purchases (inventory creation)
			$purchaseStock = DB::table('purchase_values as pv')
				->join('purchases as p', 'p.id', '=', 'pv.sid')
				->where('p.added_by', $userId)
				->whereBetween('p.inv_date', [$startDate, $endDate])
				->selectRaw("
					COALESCE(SUM(pv.quantity * pv.rate), 0) as stock_in
				")
				->value('stock_in');

			// FINAL INVENTORY VALUE (simple model)
			$amount = max(0, $purchaseStock - $soldQtyValue);
		}

		return $amount;
	}
	
	private function calculateGST($userId, $startDate, $endDate)
	{
		// Sales IDs
		$salesIds = DB::table('sales')
			->where('added_by', $userId)
			->where('status', 1)
			->whereBetween('inv_date', [$startDate, $endDate])
			->pluck('id');

		// Purchase IDs
		$purchaseIds = DB::table('purchases')
			->where('added_by', $userId)
			->where('status', 1)
			->whereBetween('inv_date', [$startDate, $endDate])
			->pluck('id');

		// Output GST
		$salesTaxAmount = DB::table('sales_values')
			->whereIn('sid', $salesIds)
			->sum('tax_amt');

		$incomeGstAmount = DB::table('income')
			->where('addBy', $userId)
			->whereBetween('dateInput', [$startDate, $endDate])
			->sum('gst_amt');

		// Input GST
		$purchaseTaxAmount = DB::table('purchase_values')
			->whereIn('sid', $purchaseIds)
			->sum('tax_amt');

		$expenseGstAmount = DB::table('expenses')
			->where('added_by', $userId)
			->whereBetween('expense_date', [$startDate, $endDate])
			->sum('total_gst');

		$assetGstAmount = DB::table('assets')
			->where('added_by', $userId)
			->whereBetween('date', [$startDate, $endDate])
			->sum('gst_amt');

		// Sales Credit Notes - GST
		$voucherSalesGst = DB::table('vouchers')
			->where('added_by', $userId)
			->where('note_type', 'Credit')
			->whereBetween('inv_date', [$startDate, $endDate])
			->selectRaw('
				SUM(
					COALESCE(cgst_amount, 0) +
					COALESCE(sgst_amount, 0) +
					COALESCE(igst_amount, 0)
				) AS credit_gst
			')
			->value('credit_gst') ?? 0;

		// Purchase Debit Notes - GST
		$voucherPurchaseGst = DB::table('voucher_purchases')
					->where('added_by', $userId)
					->where('note_type', 'Debit')
					->whereBetween('inv_date', [$startDate, $endDate])
					->selectRaw('
						SUM(
							COALESCE(cgst_amount, 0) +
							COALESCE(sgst_amount, 0) +
							COALESCE(igst_amount, 0)
						) AS debit_gst
					')
					->value('debit_gst') ?? 0;

		$outputGST = ($salesTaxAmount + $incomeGstAmount - $voucherSalesGst);
		$inputGST = ($purchaseTaxAmount + $expenseGstAmount + $assetGstAmount - $voucherPurchaseGst);
		$netGST = $outputGST - $inputGST;

		return [
			'output_gst'     => $outputGST,
			'input_gst'      => $inputGST,
			'net_gst'        => $netGST,
			'gst_payable'    => max($netGST, 0),
			'gst_receivable' => max(-$netGST, 0),
		];
	}
	
	public function calculateDepreciationByPeriod($asset, $fromDate, $toDate, $periodType = 'full-yearly')
	{
		$cost      = (float) $asset->invoice_value;
		$residual  = (float) ($asset->residual_value ?? 0);
		$rate      = (float) ($asset->depreciation_rate ?? 0);
		$life      = (float) ($asset->useful_life_years ?? 0);
		$method    = strtoupper($asset->depreciation_method ?? '');

		$startDate = $asset->depreciation_start_date
			?? $asset->purchaseDateAudit
			?? $asset->date;

		if (empty($startDate)) {
			return 0;
		}

		$start = Carbon::parse($startDate);
		$end   = Carbon::parse($toDate);

		if ($start->gt($end)) {
			return 0;
		}

		$years = $start->diffInYears($end);

		// Calculate Annual Depreciation
		if ($method === 'SLM') {

			if ($life > 0) {
				$annualDepreciation = ($cost - $residual) / $life;
			} else {
				$annualDepreciation = $cost * ($rate / 100);
			}

		} elseif ($method === 'WDV') {

			$opening = $cost;

			for ($i = 0; $i < $years; $i++) {
				$opening -= ($opening * $rate / 100);
			}

			$annualDepreciation = $opening * ($rate / 100);

		} else {
			return 0;
		}

		// Return depreciation according to selected period
		switch (strtolower($periodType)) {

			case 'monthly':
				return $annualDepreciation / 12;

			case 'quarterly':
				return $annualDepreciation / 4;

			case 'half-yearly':
				return $annualDepreciation / 2;

			case 'full-yearly':
			default:
				return $annualDepreciation;
		}
	}
}
