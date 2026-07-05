<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class ReportsService
{
    
    public function __construct()
    {
        
    }
	
	//Shareholders’ Funds	
	public function shareCapitalRows($userId, $from, $to)
	{
		$rows = [];
		$equity = DB::table('share_holder_fund_liabilities as shfl')
			->join('liabilities as l', 'l.id', '=', 'shfl.liabilities_id')
			->whereBetween('l.added_date', [$from, $to])   // transaction date
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)                   // filter once
			->selectRaw("
				COALESCE(SUM(CASE 
					WHEN shfl.share_holder_fund_type = 'share_capital' 
					THEN shfl.premium_amount 
				END),0) AS share_capital,

				COALESCE(SUM(CASE 
					WHEN shfl.share_holder_fund_type = 'reserves_surplus' 
					THEN shfl.amountForsurplus 
				END),0) AS reserves_surplus,

				COALESCE(SUM(CASE 
					WHEN shfl.share_holder_fund_type = 'retained_earnings' 
					THEN shfl.amount 
				END),0) AS retained_earnings,

				COALESCE(SUM(CASE 
					WHEN shfl.share_holder_fund_type = 'money_received_against_share_warrants' 
					THEN shfl.amount 
				END),0) AS m_r_a_share_warrants
			")
			->first();

		$rows = [
			'equity' => [
				'share_capital'        => (float) $equity->share_capital,
				'reserves_surplus'     => (float) $equity->reserves_surplus,
				'retained_earnings'    => (float) $equity->retained_earnings,
				'm_r_a_share_warrants' => (float) $equity->m_r_a_share_warrants,
			]
		];
		return $rows;
	}

	//Non-Current Liabilities
	public function longTermBorrowingsRows($userId, $from, $to)
	{
		$data = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->whereBetween('ncl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('ncl.liability_category', 'long_term_borrowings')
			->selectRaw("COALESCE(SUM(ncl.amount),0) as total")
			->first();

		return (float) $data->total;
	}
	public function deferredTaxLiabilitiesRows($userId, $from, $to)
	{
		$data = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->whereBetween('ncl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('ncl.liability_category', 'deferred_tax_liabilities')
			->selectRaw("COALESCE(SUM(ncl.amount),0) as total")
			->first();

		return (float) $data->total;
	}
	
	public function otherLongTermLiabilitiesRows($userId, $from, $to)
	{
		$data = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->whereBetween('ncl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('ncl.liability_category', 'other_long_term_liabilities')
			->selectRaw("COALESCE(SUM(ncl.amount),0) as total")
			->first();

		return (float) $data->total;
	}
	
	public function longTermProvisionsRows($userId, $from, $to)
	{
		$data = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->whereBetween('ncl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('ncl.liability_category', 'long_term_provisions')
			->selectRaw("COALESCE(SUM(ncl.amount),0) as total")
			->first();

		return (float) $data->total;
	}
	//Current Liabilities
	public function shortTermBorrowingsAmount($userId, $from, $to)
	{
		$data = DB::table('current_liabilities as cl')
			->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
			->whereBetween('cl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('cl.CurrentLiabilitiesType', 'Short-term Borrowings')
			->selectRaw("COALESCE(SUM(cl.amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function tradePayableRows($userId, $from, $to)
	{
		$data = DB::table('purchases')
				->where('added_by', $userId)
				->where('purchases.status', 1) //only active records
				->where('pay_status', '!=', 'Full')   // not fully paid
				->where('due_amount', '>', 0)         // only outstanding
				->whereBetween('inv_date', [$from, $to])
				->selectRaw("COALESCE(SUM(due_amount),0) as total")
				->first();

		return (float) $data->total;
	}

	public function advancesFromCustomerRows($userId, $from, $to)
	{
		$data = DB::table('sales')
			->where('added_by', $userId)
			->where('sales.status', 1) //only active records
			->where('pay_status', '!=', 'Full')   // not fully paid
			->where('advance_amount', '>', 0)     // only advance entries
			->whereBetween('inv_date', [$from, $to])
			->selectRaw("COALESCE(SUM(advance_amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function statutoryDuesPayableRows($userId, $from, $to)
	{
		$data = DB::table('current_liabilities as cl')
			->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
			->whereBetween('cl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('cl.CurrentLiabilitiesType', 'Statutory Dues Payable')
			->selectRaw("COALESCE(SUM(cl.amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function tdsPayableRows($userId, $from, $to)
	{
		// Income TDS Amount
		$incomeTdsAmount = DB::table('income')
			->where('addBy', $userId)
			->where('tds_applicable', 'yes')
			->whereBetween('dateInput', [$from, $to])
			->sum('tds_amount');

		// Expenses TDS Amount
		$expenseTdsAmount = DB::table('expenses')
			->where('added_by', $userId)
			->where('tds_applicable', 'yes')
			->whereBetween('expense_date', [$from, $to])
			->sum('tds_amount');

		// Assets TDS Amount
		$assetTdsAmount = DB::table('assets')
			->where('added_by', $userId)
			->where('tds_applicable', 'yes')
			->whereBetween('date', [$from, $to])
			->sum('tds_amt');
		
		// Salary TDS Amount
		$salaryTdsAmount = DB::table('user_payslip as up')
			->join('employees as e', 'e.empId', '=', 'up.user_emp_id')
			->whereBetween('up.date', [$from, $to])
			->where('e.added_by', $userId)
			->selectRaw("
				COALESCE(
					SUM(
						CAST(
							JSON_UNQUOTE(
								JSON_EXTRACT(
									up.emp_salary_slip_response,
									'$.visible_data.final_salary_calculation.tds'
								)
							) AS DECIMAL(15,2)
						)
					),
				0) as total_tds
			")
			->value('total_tds');

		$salaryTdsAmount = round((float) $salaryTdsAmount, 2);

		// Final Total
		$amount = $incomeTdsAmount + $expenseTdsAmount + $assetTdsAmount + $salaryTdsAmount;


		return $amount;
	}

	public function emiPayableRows($userId, $from, $to)
	{
		$data = DB::table('current_liabilities as cl')
			->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
			->whereBetween('cl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('cl.CurrentLiabilitiesType', 'EMI Payables')
			->selectRaw("COALESCE(SUM(cl.amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function accruedExpenseIncomeRows($userId, $from, $to)
	{
		$data = DB::table('current_liabilities as cl')
			->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
			->whereBetween('cl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('cl.CurrentLiabilitiesType', 'Accrued Expenses / Income')
			->selectRaw("COALESCE(SUM(cl.amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function royaltyPayableRows($userId, $from, $to)
	{
		$data = DB::table('current_liabilities as cl')
			->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
			->whereBetween('cl.added_date', [$from, $to])
			->where('l.status', 1) //only active records
			->where('l.added_by', $userId)
			->where('cl.CurrentLiabilitiesType', 'Royalty Payables')
			->selectRaw("COALESCE(SUM(cl.amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function gstPayableRows($userId, $from, $to)
	{
		// Get sales ids
			$salesIds = DB::table('sales')
				->where('added_by', $userId)
				->whereBetween('inv_date', [$from, $to])
				->pluck('id');

			// Sum tax amount from sales_values table
			$salesTaxAmount = DB::table('sales_values')
				->whereIn('sid', $salesIds)
				->sum('tax_amt');

			// Income GST Amount
			$incomeGstAmount = DB::table('income')
				->where('addBy', $userId)
				->whereBetween('dateInput', [$from, $to])
				->sum('gst_amt');

			// Expenses GST Amount
			$expenseGstAmount = DB::table('expenses')
				->where('added_by', $userId)
				->whereBetween('expense_date', [$from, $to])
				->sum('total_gst');
			
			 // Assets GST Amount
			$assetGstAmount = DB::table('assets')
				->where('added_by', $userId)
				->whereBetween('date', [$from, $to])
				->sum('gst_amt');

			// Final Total
			$amount = $salesTaxAmount + $incomeGstAmount + $expenseGstAmount + $assetGstAmount;
			
			return $amount;
	}
	
	public function outstanding_expenses($userId, $from, $to)
	{
		$amount = DB::table('expenses')
				->where('added_by', $userId)
				->where('payment_status', 'advance')
				->whereBetween('expense_date', [$from, $to])
				->sum('balance_amount');
		return $amount;
	}
	
	// PF Payable
	public function pfPayable($userId, $from, $to)
	{
		$amount = DB::table('user_payslip as up')
			->join('employees as e', 'e.empId', '=', 'up.user_emp_id')
			->whereBetween('up.date', [$from, $to])
			->where('e.added_by', $userId)
			->selectRaw("
				COALESCE(
					SUM(
						CAST(
							JSON_UNQUOTE(
								JSON_EXTRACT(
									up.emp_salary_slip_response,
									'$.visible_data.final_salary_calculation.provident_fund'
								)
							) AS DECIMAL(15,2)
						)
					),
				0) as total_pf
			")
			->value('total_pf');

		return round((float) $amount, 2);
	}


	// ESI Payable
	public function esiPayable($userId, $from, $to)
	{
		$amount = DB::table('user_payslip as up')
			->join('employees as e', 'e.empId', '=', 'up.user_emp_id')
			->whereBetween('up.date', [$from, $to])
			->where('e.added_by', $userId)
			->selectRaw("
				COALESCE(
					SUM(
						CAST(
							JSON_UNQUOTE(
								JSON_EXTRACT(
									up.emp_salary_slip_response,
									'$.visible_data.final_salary_calculation.esi'
								)
							) AS DECIMAL(15,2)
						)
					),
				0) as total_esi
			")
			->value('total_esi');

		return round((float) $amount, 2);
	}

	//Advance Received – Sales Side (Advance from Customer) -- Accounting Nature: Current Liability
	public function unearnedRevenueCurrLiabRows($userId, $from, $to)
	{
		$data = DB::table('sales')
			->where('added_by', $userId)
			->where('sales.status', 1) //only active records
			->where('pay_status', 'Partial')
			->whereBetween('inv_date', [$from, $to])
			->selectRaw("
				COALESCE(SUM(total_amount - advance_amount),0) as total
			")
			->first();

		return (float) $data->total;
	}
	
	public function unearnedRevenueCurrAssetRows($userId, $from, $to)
	{
		//Partial Purchases Balance
		$purchaseTotal = DB::table('purchases')
			->where('added_by', $userId)
			->where('purchases.status', 1) //only active records
			->where('pay_status', 'Partial')
			->whereBetween('inv_date', [$from, $to])
			->selectRaw("
				COALESCE(SUM(total_amount - advance_amount),0) as total
			")
			->value('total');

		//Projects Done, not billed
		$projectTotal = DB::table('projects')
			->where('added_by', $userId)
			->where('proj_status', '=', 'Done')
			->whereBetween('proj_start_date', [$from, $to])
			->selectRaw("
				COALESCE(SUM(proj_cost),0) as total
			")
			->value('total');

		return (float)$purchaseTotal + (float)$projectTotal;
	}

	public function securityDepositPayableRows($userId, $from, $to)
	{
		return 0;
	}

	public function otherCurrentLiabilityRows($userId, $from, $to)
	{
		return 0;
	}

	public function shortTermProvisionRows($userId, $from, $to)
	{
		return 0;
	}
	
	/////Non-Current Assets//////
	public function fixedAssetRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
        ->join('assets as a', 'a.id', '=', 'anc.asid')
        ->where('a.added_by', $userId)
		->where('a.isActive', 1) //only active records
        ->whereBetween('a.date', [$from, $to])
        ->where('anc.nonCurrentAssetType', 'fixed_asset')
        ->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
        ->first();

		return (float) $data->total;
	}

	public function tangibleAssetRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
        ->join('assets as a', 'a.id', '=', 'anc.asid')
        ->where('a.added_by', $userId)
		->where('a.isActive', 1) //only active records
        ->whereBetween('a.date', [$from, $to])
        ->where('anc.nonCurrentAssetType', 'Tangible Assets')
        ->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
        ->first();

		return (float) $data->total;
	}

	public function intangibleAssetRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
				->join('assets as a', 'a.id', '=', 'anc.asid')
				->where('a.added_by', $userId)
				->where('a.isActive', 1) //only active records
				->whereBetween('a.date', [$from, $to])
				->where('anc.nonCurrentAssetType', 'Intangible Assets')
				->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
				->first();

		return (float) $data->total;
	}

	public function capitalWipRows($userId, $from, $to)
	{
		 $data = DB::table('assets_ncs as anc')
        ->join('assets as a', 'a.id', '=', 'anc.asid')
        ->where('a.added_by', $userId)
		->where('a.isActive', 1) //only active records
        ->whereBetween('a.date', [$from, $to])
        ->where('anc.nonCurrentAssetType', 'Capital WIP / Under Development')
        ->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
        ->first();

		return (float) $data->total;
	}

	public function nonCurrentInvestmentRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
        ->join('assets as a', 'a.id', '=', 'anc.asid')
        ->where('a.added_by', $userId)
		->where('a.isActive', 1) //only active records
        ->whereBetween('a.date', [$from, $to])
        ->where('anc.nonCurrentAssetType', 'Non-Current Investments')
        ->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
        ->first();

		return (float) $data->total;
	}

	public function deferredTaxAssetRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
        ->join('assets as a', 'a.id', '=', 'anc.asid')
        ->where('a.added_by', $userId)
		->where('a.isActive', 1) //only active records
        ->whereBetween('a.date', [$from, $to])
        ->where('anc.nonCurrentAssetType', 'Deferred Tax Assets (Net)')
        ->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
        ->first();

		return (float) $data->total;
	}

	public function longTermLoanAdvanceRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
			->join('assets as a', 'a.id', '=', 'anc.asid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('anc.nonCurrentAssetType', 'Long-term Loans and Advances')
			->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
			->first();

		return (float) $data->total;
	}

	public function otherNonCurrentAssetRows($userId, $from, $to)
	{
		$data = DB::table('assets_ncs as anc')
			->join('assets as a', 'a.id', '=', 'anc.asid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('anc.nonCurrentAssetType', 'Other Non-Current Assets')
			->selectRaw("COALESCE(SUM(anc.amt_nca),0) as total")
			->first();

		return (float) $data->total;
	}
	
	//Current Assets
	public function currentInvestmentRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Current Investments')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function shortTermLoanAdvanceRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Short-term Loans & Advances')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function interestAccruedRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Interest Accrued but Not Due')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function prepaidExpenseRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Prepaid Expenses')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function groupCompanyReceivableRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Group Company Receivables')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function otherCurrentAssetRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Other Current Assets')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function grantSubsidyRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Grant/Subsidy Receivables')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function deferredRevenueRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Deferred Revenue')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function royaltyReceivableRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Royalty Receivables')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function workInProgressRows($userId, $from, $to)
	{
		return DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.added_by', $userId)
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->where('ac.currentAssetType', 'Work-in-Progress')
			->select(
				'a.date',
				'ac.amt as amount',
				'a.id as asset_id'
			)
			->get();
	}
	
	public function cashAndBankRows($userId, $from, $to)
	{
		$amount = DB::table('bank_trans as bt')
			->join('banks as b', 'b.id', '=', 'bt.bankId')
			->where('b.added_by', $userId)
			->where('b.status', 1)
			->whereBetween('bt.tran_date', [$from, $to])
			->selectRaw("
				COALESCE(
					SUM(
						CASE
							WHEN bt.tran_type = 'Credit'
							THEN COALESCE(bt.tran_amt, 0)

							WHEN bt.tran_type = 'Debit'
							THEN -COALESCE(bt.tran_amt, 0)

							ELSE 0
						END
					),
				0) as total_amount
			")
			->value('total_amount');

		return round((float) $amount, 2);
	}


	
	public function inventoryRows($userId, $from, $to)
	{
		// Check if any sales exist in date range
		$hasSales = DB::table('sales')
			->where('added_by', $userId)
			->where('sales.status', 1) //only active records
			->whereBetween('inv_date', [$from, $to])
			->exists();

		if (!$hasSales) {
			return 0; // 🚫 No data should show
		}

		// Calculate closing stock only if sales exist
		$closingStock = DB::table('products as p')
			->leftJoin('sales_values as sv', 'sv.prod_id', '=', 'p.id')
			->leftJoin('sales as s', function ($join) use ($userId, $from, $to) {
				$join->on('s.id', '=', 'sv.sid')
					 ->where('s.added_by', $userId)
					 ->whereBetween('s.inv_date', [$from, $to]);
			})
			->where('p.added_by', $userId)
			->where('p.item_type', '!=', 'service')
			->where('s.status', 1) //only active records
			->select(
				'p.id',
				'p.item_name',
				'p.opening_stock_bal',
				'p.purchase_price',

				DB::raw('COALESCE(SUM(CASE 
					WHEN s.id IS NOT NULL 
					THEN sv.quantity 
					ELSE 0 
				END),0) as sold_qty'),

				DB::raw('
					GREATEST(
						(p.opening_stock_bal 
						 - COALESCE(SUM(CASE 
							WHEN s.id IS NOT NULL 
							THEN sv.quantity 
							ELSE 0 
						END),0)
						) * p.purchase_price,
						0
					) as closing_stock_amount
				')
			)
			->groupBy(
				'p.id',
				'p.item_name',
				'p.opening_stock_bal',
				'p.purchase_price'
			)
			->get();

		$totalClosingStock = $closingStock->sum('closing_stock_amount');
		
		return $totalClosingStock;
	}

	public function advancesToVendorRows($userId, $from, $to)
	{
		$data = DB::table('purchases')
			->where('added_by', $userId)
			->where('purchases.status', 1) //only active records
			->where('pay_status', '!=', 'Full')   // not fully paid
			->where('advance_amount', '>', 0)     // only advance entries
			->whereBetween('inv_date', [$from, $to])
			->selectRaw("COALESCE(SUM(advance_amount),0) as total")
			->first();

		return (float) $data->total;
	}
	
	public function tradeReceivableRows($userId, $from, $to)
	{
		$data = DB::table('sales')
			->where('added_by', $userId)
			->where('sales.status', 1) //only active records
			->where('pay_status', '!=', 'Full')   // not fully paid
			->where('due_amount', '>', 0)         // outstanding only
			->whereBetween('inv_date', [$from, $to])
			->selectRaw("COALESCE(SUM(due_amount),0) as total")
			->first();

		return (float) $data->total;
	}

	public function gstReceivableRows($userId, $from, $to)
	{
		//INPUT GST FROM PURCHASES
		$inputGST = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->where('p.added_by', $userId)
			->where('p.status', 1) //only active records
			->whereBetween('p.inv_date', [$from, $to])
			->sum('pv.tax_amt');

		return $inputGST > 0 ? (float) $inputGST : 0;
	}

	public function tdsReceivableRows($userId, $from, $to)
	{
		//TDS from Sales
		$salesTds = DB::table('sales')
			->where('added_by', $userId)
			->where('sales.status', 1) //only active records
			->whereBetween('inv_date', [$from, $to])
			->where('tds_amount', '>', 0)
			->selectRaw("COALESCE(SUM(tds_amount),0) as total_tds")
			->value('total_tds');

		//TDS from Incomes
		$incomeTds = DB::table('income')
			->where('addBy', $userId)
			->where('income.status', 1) //only active records
			->whereBetween('dateInput', [$from, $to])
			->where('tds_amount', '>', 0)
			->selectRaw("COALESCE(SUM(tds_amount),0) as total_tds")
			->value('total_tds');

		$totalTdsReceivable = (float)$salesTds + (float)$incomeTds;

		return $totalTdsReceivable;
	}



}
