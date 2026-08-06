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

class TrialBalanceService
{
    
	private $profitLossService;

    public function __construct(ProfitLossService $profitLossService)
    {
        $this->profitLossService = $profitLossService;
    }
	
	
	public function getAssetHead($asset)
	{
		// -------------------------------
		// CURRENT ASSETS
		// -------------------------------

		if ($asset->assetType == 'current') {

			switch (trim($asset->currentAssetType)) {

				case 'Cash in Hand':
					return [
						'main'   => 'Assets',
						'group'  => 'Current Assets',
						'ledger' => 'Cash'
					];

				case 'Bank Accounts':
					return [
						'main'   => 'Assets',
						'group'  => 'Current Assets',
						'ledger' => 'Bank Accounts'
					];

				case 'Trade Receivables':
					return [
						'main'   => 'Assets',
						'group'  => 'Current Assets',
						'ledger' => 'Trade Receivables'
					];

				case 'Inventory':
					return [
						'main'   => 'Assets',
						'group'  => 'Current Assets',
						'ledger' => 'Inventory'
					];

				case 'GST Receivable':
					return [
						'main'   => 'Assets',
						'group'  => 'Current Assets',
						'ledger' => 'GST Receivable'
					];

				default:
					return [
						'main'   => 'Assets',
						'group'  => 'Current Assets',
						'ledger' => 'Other Current Assets'
					];
			}
		}

		// -------------------------------
		// NON CURRENT ASSETS
		// -------------------------------

		switch (trim($asset->nonCurrentAssetType)) {

			case 'Capital Work in Progress':
				return [
					'main'   => 'Assets',
					'group'  => 'Non-Current Assets',
					'ledger' => 'CWIP'
				];

			case 'Investment':
			case 'Investments':
				return [
					'main'   => 'Assets',
					'group'  => 'Non-Current Assets',
					'ledger' => 'Investments'
				];

			// Fixed Assets
			case 'Property Plant Equipment':
			case 'Furniture Fixtures':
			case 'Computer IT Equipment':
			case 'Machinery':
			case 'Vehicles':
			case 'Intangible Assets':
				return [
					'main'   => 'Assets',
					'group'  => 'Non-Current Assets',
					'ledger' => 'Fixed Assets'
				];

			case 'Other Non-Current Assets':
				return [
					'main'   => 'Assets',
					'group'  => 'Non-Current Assets',
					'ledger' => 'Other Non-Current Assets'
				];

			default:
				return [
					'main'   => 'Assets',
					'group'  => 'Non-Current Assets',
					'ledger' => 'Other Non-Current Assets'
				];
		}
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
				->where('pay_status', '!=', 'Full')
				->where('status', 1)
				->whereBetween('dateInput', [$startDate, $endDate])
				->selectRaw('
					SUM(
						GREATEST(
							COALESCE(amount, 0)
							+ COALESCE(gst_amt, 0)
							- COALESCE(advance_amt, 0),
							0
						)
					) AS receivable
				')
				->value('receivable') ?? 0;

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
		| Input GST Credit (ITC)
		|--------------------------------------------------------------------------
		*/
		if ($type == 'Input GST Credit') {
			$gst = $this->calculateGST($userId, $startDate, $endDate);
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
			
			$purchaseQty = DB::table('purchase_values as pv')
				->join('purchases as p', 'p.id', '=', 'pv.sid')
				->where('p.added_by', $userId)
				->whereBetween('p.inv_date', [$startDate, $endDate])
				->select(
					'pv.prod_id',
					DB::raw('SUM(pv.quantity) as purchase_qty')
				)
				->groupBy('pv.prod_id');

			$soldQty = DB::table('sales_values as sv')
				->join('sales as s', 's.id', '=', 'sv.sid')
				->where('s.added_by', $userId)
				->whereBetween('s.inv_date', [$startDate, $endDate])
				->select(
					'sv.prod_id',
					DB::raw('SUM(sv.quantity) as sold_qty')
				)
				->groupBy('sv.prod_id');

			$amount = DB::table('products as p')
				->leftJoinSub($purchaseQty, 'pq', function ($join) {
					$join->on('p.id', '=', 'pq.prod_id');
				})
				->leftJoinSub($soldQty, 'sq', function ($join) {
					$join->on('p.id', '=', 'sq.prod_id');
				})
				->where('p.added_by', $userId)
				->where('p.item_type', 'product')
				->selectRaw("
					SUM(
						GREATEST(
							p.opening_stock_bal
							+ COALESCE(pq.purchase_qty,0)
							- COALESCE(sq.sold_qty,0),
							0
						) * p.selling_price
					) as inventory_value
				")
				->value('inventory_value');
		}

		return $amount;
	}
	
	public function getNonCurrentAssetAmount($type, $userId, $fromDate, $toDate, $propId = null)
	{
		switch ($type) {

			/*
			|--------------------------------------------------------------------------
			| Fixed Assets
			|--------------------------------------------------------------------------
			*/
			case 'Fixed Assets':

				return DB::table('assets')
					->where('added_by', $userId)
					->when($propId, function ($q) use ($propId) {
						$q->where('propId', $propId);
					})
					->where('assetType', 'non-current')
					->whereIn('nonCurrentAssetType', [
						'Property Plant Equipment',
						'Furniture Fixtures',
						'Computer IT Equipment',
						'Machinery',
						'Vehicles',
						'Intangible Assets',
					])
					->whereDate('date', '<=', $toDate)
					->sum('invoice_value');

			/*
			|--------------------------------------------------------------------------
			| Capital Work In Progress
			|--------------------------------------------------------------------------
			*/
			case 'CWIP':

				return DB::table('assets')
					->where('added_by', $userId)
					->when($propId, function ($q) use ($propId) {
						$q->where('propId', $propId);
					})
					->where('assetType', 'non-current')
					->where('nonCurrentAssetType', 'Capital Work in Progress')
					->whereDate('date', '<=', $toDate)
					->sum('cwip_amount');

			/*
			|--------------------------------------------------------------------------
			| Investments
			|--------------------------------------------------------------------------
			*/
			case 'Investments':

				return DB::table('assets')
					->where('added_by', $userId)
					->when($propId, function ($q) use ($propId) {
						$q->where('propId', $propId);
					})
					->where('assetType', 'non-current')
					->where('nonCurrentAssetType', 'Investments')
					->whereDate('date', '<=', $toDate)
					->sum('invoice_value');

			/*
			|--------------------------------------------------------------------------
			| Other Non Current Assets
			|--------------------------------------------------------------------------
			*/
			case 'Other Non-Current Assets':

				return DB::table('assets')
					->where('added_by', $userId)
					->when($propId, function ($q) use ($propId) {
						$q->where('propId', $propId);
					})
					->where('assetType', 'non-current')
					->whereNotIn('nonCurrentAssetType', [
						'Other Non-Current Assets',
					])
					->whereDate('date', '<=', $toDate)
					->sum('invoice_value');

			default:
				return 0;
		}
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
			'gst_payable'    => $netGST,//max($netGST, 0),
			'gst_receivable' => max(-$netGST, 0),
		];
	}
	
	public function getEquityAmount($type, $userId, $startDate, $endDate)
	{
		$equity = DB::table('share_holder_fund_liabilities as shfl')
			->join('liabilities as l', 'l.id', '=', 'shfl.liabilities_id')
			->where('l.added_by', $userId)
			->where('l.status', 1)
			->whereBetween('l.added_date', [$startDate, $endDate])
			->selectRaw("
				SUM(
					CASE
						WHEN shfl.share_holder_fund_type = 'share_capital'
						THEN COALESCE(shfl.total_amount,0)
						ELSE 0
					END
				) AS share_capital,

				SUM(
					CASE
						WHEN shfl.share_holder_fund_type = 'reserves_surplus'
							AND shfl.reserves_surplus_type = 'transfer_to_reserve'
						THEN COALESCE(shfl.transfer_amount,0)

						WHEN shfl.share_holder_fund_type = 'reserves_surplus'
							AND shfl.reserves_surplus_type = 'opening_balance'
						THEN COALESCE(shfl.opening_balance,0)

						WHEN shfl.share_holder_fund_type = 'reserves_surplus'
							AND shfl.reserves_surplus_type = 'dividend_declaration'
						THEN COALESCE(shfl.total_dividend_amount,0)

						ELSE 0
					END
				) AS reserves_surplus,

				0 AS retained_earnings,

				0 AS m_r_a_share_warrants
			")
			->first();

		switch ($type) {

			case 'share_capital':
				return (float)($equity->share_capital ?? 0);

			case 'reserves_surplus':
				return (float)($equity->reserves_surplus ?? 0);

			case 'retained_earnings':
				return (float)($equity->retained_earnings ?? 0);

			case 'm_r_a_share_warrants':
				return (float)($equity->m_r_a_share_warrants ?? 0);

			default:
				return 0;
		}
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
											+ COALESCE(total_gst, 0)
											- COALESCE(advance_amount, 0),
											0
										)
									) AS payable
								')
								->value('payable') ?? 0;
			// Outstanding Non-Current Asset Payables
			$assetAmount = DB::table('assets')
							->where('added_by', $userId)
							->where('assetType', 'non-current')
							->where('isActive', 1)
							->where('pay_status', '!=', 'Full')
							->whereBetween('date', [$startDate, $endDate])
							->selectRaw('
								SUM(
									GREATEST(
										COALESCE(invoice_value, 0)
										- COALESCE(advance_amt, 0)
										- COALESCE(adjusted_amt, 0),
										0
									)
								) AS payable
							')
							->value('payable') ?? 0;

			$purchaseDebit  = $voucherPurchaseTotals->total_debit ?? 0;
			$amount = ($purchaseAmount - $purchaseDebit + $expenseAmount + $assetAmount);
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
		
		// PTAX Payable
		if ($type == 'ptax_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->where(function ($query) {
						$query->whereNull('ptax_payment_status')
							  ->orWhere('ptax_payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += (float) ($data['visible_data']['final_salary_calculation']['ptax'] ?? 0);
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
					$lwfCompany = $data['visible_data']['final_salary_calculation']['lwf_company_contribution'] ?? 0;
					$amount += (float) $lwfCompany;
				}
			}
		}

		// GST Payable
		if ($type == 'gst_payable') {
			$gst = $this->calculateGST($userId, $startDate, $endDate);
			$amount = $gst['gst_payable'];
		}
		
		// GST Output
		if ($type == 'output_gst') {
			$gst = $this->calculateGST($userId, $startDate, $endDate);
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
	
	public function getNonCurrentLiabilityAmount($type, $userId, $startDate, $endDate)
	{
		$nonCurrLiab = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->where('l.added_by', $userId)
			->where('l.status', 1)
			->whereBetween('l.added_date', [$startDate, $endDate])
			->selectRaw("
				SUM(
					CASE
						WHEN ncl.liability_category = 'long_term_borrowings'
						THEN COALESCE(ncl.amount,0)
						ELSE 0
					END
				) AS long_term_borrowings,

				SUM(
					CASE
						WHEN ncl.liability_category = 'other_financial_liabilities'
						THEN COALESCE(ncl.amount,0)
						ELSE 0
					END
				) AS other_financial_liabilities,

				SUM(
					CASE
						WHEN ncl.liability_category = 'deferred_tax_liabilities'
						THEN COALESCE(ncl.dtl_amount,0)
						ELSE 0
					END
				) AS deferred_tax_liabilities,

				SUM(
					CASE
						WHEN ncl.liability_category = 'other_non_current_liabilities'
						THEN COALESCE(ncl.amount,0)
						ELSE 0
					END
				) AS other_non_current_liabilities,

				SUM(
					CASE
						WHEN ncl.liability_category = 'long_term_provisions'
						THEN COALESCE(ncl.amount,0)
						ELSE 0
					END
				) AS long_term_provisions
			")
			->first();

		switch ($type) {

			case 'long_term_borrowings':
				return (float)($nonCurrLiab->long_term_borrowings ?? 0);

			case 'other_financial_liabilities':
				return (float)($nonCurrLiab->other_financial_liabilities ?? 0);

			case 'deferred_tax_liabilities':
				return (float)($nonCurrLiab->deferred_tax_liabilities ?? 0);

			case 'other_non_current_liabilities':
				return (float)($nonCurrLiab->other_non_current_liabilities ?? 0);

			case 'long_term_provisions':
				return (float)($nonCurrLiab->long_term_provisions ?? 0);

			default:
				return 0;
		}
	}
	
}
