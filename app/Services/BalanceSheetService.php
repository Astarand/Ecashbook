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
	
	public function calculatePL($startDate, $endDate, $userId)
	{
		/* ================================
		 | REVENUE – SALES & SERVICES
		 ================================*/
		$totalReseller = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->join('products as p', 'p.id', '=', 'sv.prod_id')
						->where('s.status', 1) //only active records
						->where('p.item_type', 'product')
						->where('s.added_by', $userId)
						->whereBetween('s.inv_date', [$startDate, $endDate])
						->sum(DB::raw("
							COALESCE(sv.amount,0) +
							COALESCE(sv.tax_amt,0) +
							COALESCE(sv.gov_pay,0) +
							COALESCE(sv.ser_pay,0)
						"));

		$totalService = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->join('products as p', 'p.id', '=', 'sv.prod_id')
						->where('s.status', 1) //only active records
						->where('p.item_type', 'service')
						->where('s.added_by', $userId)
						->whereBetween('s.inv_date', [$startDate, $endDate])
						->sum(DB::raw("
							COALESCE(sv.amount,0) +
							COALESCE(sv.tax_amt,0) +
							COALESCE(sv.gov_pay,0) +
							COALESCE(sv.ser_pay,0)
						"));
			
		$profit_sale = DB::table('sales')
			->join('sales_values', 'sales_values.sid', '=', 'sales.id')
			->join('products', 'products.id', '=', 'sales_values.prod_id')
			->where('sales.added_by', $userId)
			->where('sales.status', 1) //only active records
			->whereBetween('sales.inv_date', [$startDate, $endDate])
			->selectRaw('SUM(sales_values.amount) AS total')
			->value('total') ?? 0;

		/* ================================
		 | OTHER INCOME - TDS amount
		 ================================*/			
		$operatingIncomeDetails = DB::table('income')
						->whereBetween('dateInput', [$startDate, $endDate])
						->where('addBy', $userId)
						->where('status', 1)
						->where('incomeType', 'Revenue')
						->select(
							'categoryIncome',
							DB::raw("
								SUM(
									CASE
										WHEN pay_status='Full'
										THEN COALESCE(amount,0)
										ELSE COALESCE(advance_amt,0)
									END
								) as total
							")
						)
						->groupBy('categoryIncome')
						->get();

		$nonOperatingIncomeDetails = DB::table('income')
			->whereBetween('dateInput', [$startDate, $endDate])
			->where('addBy', $userId)
			->where('status', 1)
			->where('incomeType', 'Other')
			->select(
				'categoryIncome',
				DB::raw("
					SUM(
						CASE
							WHEN pay_status='Full'
							THEN COALESCE(amount,0)
							ELSE COALESCE(advance_amt,0)
						END
					) as total
				")
			)
			->groupBy('categoryIncome')
			->get();
						
		/* ================================
		| COST OF GOODS SOLD (COGS)
		================================ */

		/*
		|--------------------------------------------------------------------------
		| Opening Stock
		|--------------------------------------------------------------------------
		| Product Opening Qty × Purchase Price
		*/
		$openingStock = DB::table('products as p')
					->leftJoinSub(
						DB::table('purchase_values as pv')
							->join('purchases as pur', 'pur.id', '=', 'pv.sid')
							->where('pur.added_by', $userId)
							->where('pur.status', 1)
							->whereDate('pur.inv_date', '<', $startDate)
							->groupBy('pv.prod_id')
							->selectRaw('pv.prod_id, SUM(pv.quantity) as purchase_qty'),
						'purchase_stock',
						function ($join) {
							$join->on('purchase_stock.prod_id', '=', 'p.id');
						}
					)
					->leftJoinSub(
						DB::table('sales_values as sv')
							->join('sales as s', 's.id', '=', 'sv.sid')
							->where('s.added_by', $userId)
							->where('s.status', 1)
							->whereDate('s.inv_date', '<', $startDate)
							->groupBy('sv.prod_id')
							->selectRaw('sv.prod_id, SUM(sv.quantity) as sold_qty'),
						'sales_stock',
						function ($join) {
							$join->on('sales_stock.prod_id', '=', 'p.id');
						}
					)
					->where('p.added_by', $userId)
					->where('p.item_type', 'product')
					->selectRaw("
						SUM(
							(
								COALESCE(p.opening_stock_bal,0)
								+ COALESCE(purchase_stock.purchase_qty,0)
								- COALESCE(sales_stock.sold_qty,0)
							) * COALESCE(p.purchase_price,0)
						) as stock_value
					")
					->value('stock_value') ?? 0;

		/*
		|--------------------------------------------------------------------------
		| Purchases
		|--------------------------------------------------------------------------
		*/
		$purchases = DB::table('purchases as p')
							->leftJoin('purchase_values as pv', 'pv.sid', '=', 'p.id')
							->where('p.added_by', $userId)
							->where('p.status', 1)
							->whereBetween('p.inv_date', [$startDate, $endDate])
							->groupBy(
								'p.id',
								'p.pay_status',
								'p.advance_amount'
							)
							->selectRaw("
								CASE
									WHEN p.pay_status = 'Full'
										THEN SUM(COALESCE(pv.amount,0) + COALESCE(pv.tax_amt,0))
									ELSE
										COALESCE(p.advance_amount,0)
								END as purchase_amount
							")
							->get()
							->sum('purchase_amount');

		/*
		|--------------------------------------------------------------------------
		| Direct Expenses
		|--------------------------------------------------------------------------
		*/
		$directExpenseDetails = DB::table('expenses')
									->where('added_by', $userId)
									->where('status', 1)
									->where('expense_cat', 'direct')
									->whereBetween('expense_date', [$startDate, $endDate])
									->select(
										'expense_type',
										DB::raw("
											SUM(
												CASE
													WHEN payment_status = 'full'
														THEN COALESCE(expense_amt,0)
													ELSE
														COALESCE(advance_amount,0)
												END
											) as total
										")
									)
									->groupBy('expense_type')
									->orderBy('expense_type')
									->get();

		/*
		|--------------------------------------------------------------------------
		| Closing Stock
		|--------------------------------------------------------------------------
		| Current Stock Qty × Purchase Price
		|--------------------------------------------------------------------------
		|
		| Formula:
		| Opening Qty
		| + Purchase Qty
		| - Sold Qty
		|
		*/
		$closingStock = DB::table('products as p')
							->leftJoinSub(
								DB::table('purchase_values as pv')
									->join('purchases as pur', 'pur.id', '=', 'pv.sid')
									->where('pur.added_by', $userId)
									->where('pur.status', 1)
									->whereDate('pur.inv_date', '<=', $endDate)
									->groupBy('pv.prod_id')
									->selectRaw('pv.prod_id, SUM(pv.quantity) as purchase_qty'),
								'purchase_stock',
								function ($join) {
									$join->on('purchase_stock.prod_id', '=', 'p.id');
								}
							)
							->leftJoinSub(
								DB::table('sales_values as sv')
									->join('sales as s', 's.id', '=', 'sv.sid')
									->where('s.added_by', $userId)
									->where('s.status', 1)
									->whereDate('s.inv_date', '<=', $endDate)
									->groupBy('sv.prod_id')
									->selectRaw('sv.prod_id, SUM(sv.quantity) as sold_qty'),
								'sales_stock',
								function ($join) {
									$join->on('sales_stock.prod_id', '=', 'p.id');
								}
							)
							->where('p.added_by', $userId)
							->where('p.item_type', 'product')
							->selectRaw("
								SUM(
									(
										COALESCE(p.opening_stock_bal,0)
										+ COALESCE(purchase_stock.purchase_qty,0)
										- COALESCE(sales_stock.sold_qty,0)
									) * COALESCE(p.purchase_price,0)
								) as stock_value
							")
							->value('stock_value') ?? 0;

		$closingStock = $closingStock ?? 0;
		$directExpenseTotal = $directExpenseDetails->sum('total');
		$cogsTotal = ($openingStock + $purchases + $directExpenseTotal - $closingStock);

		/* ================================
		 | EXPENSES
		 ================================*/			
		$indirectExpenses = DB::table('expenses')
							->whereBetween('expense_date', [$startDate, $endDate])
							->where('added_by', $userId)
							->where('status', 1)
							->where('expense_cat', 'indirect')
							->whereIn('expense_type', [
								'Employee Expenses',
								'Rent Expense',
								'Electricity Expense',
								'Internet & Communication',
								'Office Expenses',
								'Printing & Stationery',
								'Travel & Conveyance',
								'Repair & Maintenance',
								'Professional Fees',
								'Audit Fees',
								'Legal Charges',
								'Bank Charges',
								'Interest Expense',
								//'Depreciation',
								'Insurance Expense',
								'Marketing & Advertisement',
								'Freight & Transport',
								'Miscellaneous Expenses'
							])
							->select(
								'expense_type',
								DB::raw("
									SUM(
										CASE
											WHEN payment_status = 'full'
												THEN COALESCE(expense_amt,0)
											ELSE
												COALESCE(advance_amount,0)
										END
									) as total
								")
							)
							->groupBy('expense_type')
							->orderBy('expense_type')
							->get();

		$depreciationExpense = DB::table('assets')
								->where('added_by', $userId)
								->where('isActive', 1)
								->where('assetType','non-current')
								//->whereBetween('depreciation_start_date', [$startDate, $endDate])
								->whereBetween('date', [$startDate, $endDate])
								->sum('depreciation_value');
								
		$financeCosts = DB::table('expenses')
							->whereBetween('expense_date', [$startDate, $endDate])
							->where('added_by', $userId)
							->where('status', 1)
							->where('expense_cat', 'indirect')
							->whereIn('expense_type', [
								'Interest Expense',
								'Bank Charges',
								'OD / CC Interest',
								'Processing Charges'
							])
							->select(
								'expense_type',
								DB::raw("
									SUM(
										CASE
											WHEN payment_status = 'Full'
												THEN COALESCE(expense_amt,0)
											ELSE
												COALESCE(advance_amount,0)
										END
									) as total
								")
							)
							->groupBy('expense_type')
							->get()
							->pluck('total', 'expense_type')
							->toArray();
							
		$expenseArray = $indirectExpenses->pluck('total', 'expense_type')->toArray();
		$totalIndirectExpenses = array_sum($expenseArray);
		
		$operatingIncomeTotal = collect($operatingIncomeDetails)->sum('total');
		$nonOperatingIncomeTotal = collect($nonOperatingIncomeDetails)->sum('total');
		$totalRevenue = ($totalReseller + $totalService + $operatingIncomeTotal + $nonOperatingIncomeTotal);
		
		$ebitda = ($totalRevenue - ($cogsTotal + $totalIndirectExpenses));
		$depreciationExp = $depreciationExpense;
		$ebit = ($ebitda - $depreciationExp);
		//Finance Cost
		$interestOnLoan    = $financeCosts['Interest Expense'] ?? 0;
		$bankCharges       = $financeCosts['Bank Charges'] ?? 0;
		$odCcInterest      = $financeCosts['OD / CC Interest'] ?? 0;
		$processingCharges = $financeCosts['Processing Charges'] ?? 0;
		$totalFinanceCost = ($interestOnLoan+ $bankCharges+ $odCcInterest+ $processingCharges);
		//Profit Before Tax
		$pbt = ($ebit - $totalFinanceCost);
		
		return $pbt;
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
			$amount = DB::table('purchases as p')
							->leftJoin('purchase_values as pv', 'pv.sid', '=', 'p.id')
							->where('p.added_by', $userId)
							->where('p.status', 1)
							->where('p.pay_status', '!=', 'Full')
							->whereBetween('p.inv_date', [$startDate, $endDate])
							->groupBy('p.id', 'p.advance_amount')
							->selectRaw('
								SUM(COALESCE(pv.amount,0) + COALESCE(pv.tax_amt,0))
								- COALESCE(p.advance_amount,0) as payable
							')
							->get()
							->sum('payable');
		}

		// Advance from Customer
		if ($type == 'advance_from_customer') {

			$amount = DB::table('sales')
				->where('added_by', $userId)
				->where('pay_status', 'Partial')
				->whereBetween('inv_date', [$startDate, $endDate])
				->sum('advance_amount');
		}

		// Outstanding Expenses
		if ($type == 'outstanding_expenses') {

			$amount = DB::table('expenses')
				->where('added_by', $userId)
				->where('payment_status', 'advance')
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('balance_amount');
		}

		// Salary Payable
		if ($type == 'salary_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$currentMonthStartDate, $currentMonthEndDate])
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
				->get();

			$amount = 0;

			foreach ($records as $row) {

				$data = json_decode($row->emp_salary_slip_response, true);

				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {

					$amount += $data['visible_data']['final_salary_calculation']['provident_fund'] ?? 0;
				}
			}
		}

		// ESI Payable
		if ($type == 'esi_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->get();

			$amount = 0;

			foreach ($records as $row) {

				$data = json_decode($row->emp_salary_slip_response, true);

				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {

					$amount += $data['visible_data']['final_salary_calculation']['esi'] ?? 0;
				}
			}
		}

		// GST Payable
		if ($type == 'gst_payable') {

			// Get sales ids
			$salesIds = DB::table('sales')
				->where('added_by', $userId)
				->whereBetween('inv_date', [$startDate, $endDate])
				->pluck('id');

			// Sum tax amount from sales_values table
			$salesTaxAmount = DB::table('sales_values')
				->whereIn('sid', $salesIds)
				->sum('tax_amt');

			// Income GST Amount
			$incomeGstAmount = DB::table('income')
				->where('addBy', $userId)
				->whereBetween('dateInput', [$startDate, $endDate])
				->sum('gst_amt');

			// Expenses GST Amount
			$expenseGstAmount = DB::table('expenses')
				->where('added_by', $userId)
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('total_gst');
			
			 // Assets GST Amount
			$assetGstAmount = DB::table('assets')
				->where('added_by', $userId)
				->whereBetween('date', [$startDate, $endDate])
				->sum('gst_amt');

			// Final Total
			$amount = $salesTaxAmount + $incomeGstAmount + $expenseGstAmount + $assetGstAmount;
		}

		// TDS Payable
		if ($type == 'tds_payable') {

			// Income TDS Amount
			$incomeTdsAmount = DB::table('income')
				->where('addBy', $userId)
				->where('tds_applicable', 'yes')
				->whereBetween('dateInput', [$startDate, $endDate])
				->sum('tds_amount');

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
				->get();

			$salaryTdsAmount = 0;

			foreach ($salaryData as $row) {

				$data = json_decode($row->emp_salary_slip_response, true);

				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {

					$salaryTdsAmount += $data['visible_data']['final_salary_calculation']['tds'] ?? 0;
				}
			}

			// Final Total
			$amount = $incomeTdsAmount + $expenseTdsAmount + $assetTdsAmount + $salaryTdsAmount;
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
				->where('payment_mode', 'Bank')
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
				->where('s.pay_status', '!=', 'Full')
				->whereBetween('s.inv_date', [$startDate, $endDate])
				->groupBy('s.id', 's.advance_amount')
				->selectRaw("
					(
						SUM(COALESCE(sv.amount,0) + COALESCE(sv.tax_amt,0))
						- COALESCE(s.advance_amount,0)
					) as receivable
				")
				->get()
				->sum('receivable');

			// Income Receivable
			$incomeReceivable = DB::table('income')
				->where('addBy', $userId)
				->where('status', 1)
				->where('pay_status', '!=', 'Full')
				->whereBetween('dateInput', [$startDate, $endDate])
				->selectRaw('SUM(COALESCE(amount,0) - COALESCE(advance_amt,0)) as receivable')
				->value('receivable');

			$amount = ($salesReceivable ?? 0) + ($incomeReceivable ?? 0);
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

			$amount = DB::table('purchase_values as pv')
				->join('purchases as p', 'p.id', '=', 'pv.sid')
				->where('p.added_by', $userId)
				->whereBetween('p.inv_date', [$startDate, $endDate])
				->sum('pv.tax_amt');
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

			$expense = DB::table('expenses')
				->where('added_by', $userId)
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('tds_amount');

			$amount = $income + $expense;
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

}
