<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class ExpensesService
{
    
    public function __construct()
    {
        
    }

    public function bankChargesRows($propId, $userId, $from, $to)
	{
		$query = DB::table('bank_trans as bt')
			->leftJoin('banks as b', 'b.id', '=', 'bt.bankId')
			->where('bt.tran_type', 'Debit')
			->whereBetween('bt.tran_date', [$from, $to])
			->select(
				'bt.*',
				DB::raw("COALESCE(b.bank_name, '') as bank_name")
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('bt.prop_id', $propId);
		} else {
			$query->where('bt.added_by', $userId);
		}

		$data = $query->get();

		$rows = [];
		foreach ($data as $b) {
			$rows[] = [
				'date' => $b->tran_date,
				'voucher' => "-",
				'type' => 'Indirect Expense',
				'counter' => 'Bank Charges & Penalties',
				'narration' => $b->purpose,
				'cgst'      => 0,
				'sgst'      => 0,
				'igst'      => 0,
				'bank' => $b->bank_name,
				'group' => 'Expense',
				'sub_group' => 'Bank Charges & Penalties',
				'debit' => $b->tran_amt,
				'credit' => 0,
				'balance'   => 0,
				'dc' => 'Dr',
				'ledgername' => 'Bank Charges Ledger'
			];
		}
		return $rows;
	}
	
	public function customerDiscountRows($propId, $userId, $from, $to)
	{
		$rows = [];
		// From sales + sales_values
		$query = DB::table('sales as s')
			->join('sales_values as sv', 'sv.sid', '=', 's.id')
			->where('sv.disc_amt', '!=', 0)
			->where('s.status', 1) //only active records
			->whereBetween('s.inv_date', [$from, $to])
			->select(
				's.inv_date',
				's.inv_num',
				DB::raw('SUM(sv.disc_amt) as disc_amt')
			)
			->groupBy('s.inv_date', 's.inv_num');

		// Ownership logic
		if (!empty($propId)) {
			$query->where('s.propId', $propId);
		} else {
			$query->where('s.added_by', $userId);
		}

		$sales = $query->get();

		foreach ($sales as $s) {
			$rows[] = [
				'date' => $s->inv_date,
				'voucher' => $s->inv_num,
				'type' => 'Indirect Expense',
				'counter' => 'Customer Discount & Rebates',
				'narration' => 'Discount given to customer on sales',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => "",
				'group' => 'Expense',
				'sub_group' => 'Indirect Expense',
				'debit' => $s->disc_amt,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Discount Ledger'
			];
		}
	
		// From sales credit/debit notes
		$data = DB::table('vouchers')
			->where('added_by', $userId)
			->where('discount', '!=', 0)
			->whereBetween('inv_date', [$from, $to])
			->get();

		foreach ($data as $s) {
			$isDebit = ($s->note_type === 'Debit');
			$rows[] = [
				'date' => $s->inv_date,
				'voucher' => $s->invoice_number,
				'type' => 'Indirect Expense',
				'counter' => 'Customer Discount & Rebates',
				'narration' => 'Discount given to customer on sales',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => "",
				'group' => 'Expense',
				'sub_group' => 'Indirect Expense',
				'debit' => $isDebit ? $s->discount : 0,
				'credit' => $isDebit ? 0 : $s->discount,
				'dc' => $isDebit ? 'Dr' : 'Cr',
				'ledgername' => 'Discount Ledger'
			];
		}
		return $rows;
	}
	
	public function salaryRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('user_payslip as up')
			->join('employees as e', 'e.empId', '=', 'up.user_emp_id')
			->whereBetween('up.date', [$from, $to])
			->select(
				'up.date',
				'up.payslip_no',
				DB::raw("JSON_UNQUOTE(JSON_EXTRACT(up.emp_salary_slip_response, '$.visible_data.final_salary_calculation.net_salary')) as net_salary"),
				'e.employee_id'
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('e.propId', $propId);
		} else {
			$query->where('e.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $p) {

			$rows[] = [
				'date' => $p->date,
				'voucher' => $p->payslip_no,
				'type' => 'Indirect Expense',
				'counter' => 'Office Salaries & Wages',
				'narration' => 'Salary paid to ' . $p->employee_id,
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => "",
				'group' => 'Expense',
				'sub_group' => 'Indirect Expense',
				'debit' => (float)$p->net_salary,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Salary Ledger'
			];
		}

		return $rows;
	}

	
	public function loanInterestRows($userId, $from, $to)
	{
		$rows = [];

		$data = DB::table('loan_ins as li')
			->join('loans as l', 'l.id', '=', 'li.loanId')
			->where('li.added_by', $userId)
			->whereBetween('li.ins_date', [$from, $to])
			->select(
				'li.ins_date',
				'l.loan_ac_no',
				'l.bank_name',
				'li.ins_amt'
			)
			->get();

		foreach ($data as $l) {
			$rows[] = [
				'date' => $l->ins_date,
				'voucher' => $l->loan_ac_no,
				'type' => 'Indirect Expense',
				'counter' => 'Interest On Loan',
				'narration' => 'Loan interest paid',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => $l->bank_name,
				'group' => 'Expense',
				'sub_group' => 'Indirect Expense',
				'debit' => $l->ins_amt,   // actual interest
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Loan Interest Ledger'
			];
		}

		return $rows;
	}

	
	public function taxComplianceRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('employees as e')
			->join('user_payslip as up', 'up.user_emp_id', '=', 'e.empId')
			->whereBetween('up.date', [$from, $to])
			->select(
				'up.date',
				'up.payslip_no',
				DB::raw("JSON_UNQUOTE(JSON_EXTRACT(up.emp_salary_slip_response, '$.visible_data.final_salary_calculation.provident_fund')) as pf_amount"),
				DB::raw("JSON_UNQUOTE(JSON_EXTRACT(up.emp_salary_slip_response, '$.visible_data.final_salary_calculation.tds')) as tds_amount"),
				DB::raw("JSON_UNQUOTE(JSON_EXTRACT(up.emp_salary_slip_response, '$.visible_data.final_salary_calculation.ptax')) as ptax_amount"),
				DB::raw("JSON_UNQUOTE(JSON_EXTRACT(up.emp_salary_slip_response, '$.visible_data.final_salary_calculation.esi')) as esi_amount")
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('e.propId', $propId);
		} else {
			$query->where('e.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $t) {

			// PF
			if ((float)$t->pf_amount > 0) {
				$rows[] = [
					'date' => $t->date,
					'voucher' => $t->payslip_no,
					'type' => 'Indirect Expense',
					'counter' => 'PF on Salary',
					'narration' => 'PF deducted on salary',
					'cgst' => 0,
					'sgst' => 0,
					'igst' => 0,
					'bank' => '',
					'group' => 'Expense',
					'sub_group' => 'Indirect Expense',
					'debit' => (float)$t->pf_amount,
					'credit' => 0,
					'dc' => 'Dr',
					'ledgername' => 'PF Ledger'
				];
			}

			// TDS
			if ((float)$t->tds_amount > 0) {
				$rows[] = [
					'date' => $t->date,
					'voucher' => $t->payslip_no,
					'type' => 'Indirect Expense',
					'counter' => 'TDS on Salary',
					'narration' => 'TDS deducted on salary',
					'cgst' => 0,
					'sgst' => 0,
					'igst' => 0,
					'bank' => '',
					'group' => 'Expense',
					'sub_group' => 'Indirect Expense',
					'debit' => (float)$t->tds_amount,
					'credit' => 0,
					'dc' => 'Dr',
					'ledgername' => 'TDS Ledger'
				];
			}

			// PTAX
			if ((float)$t->ptax_amount > 0) {
				$rows[] = [
					'date' => $t->date,
					'voucher' => $t->payslip_no,
					'type' => 'Indirect Expense',
					'counter' => 'Professional Tax',
					'narration' => 'Professional tax on salary',
					'cgst' => 0,
					'sgst' => 0,
					'igst' => 0,
					'bank' => '',
					'group' => 'Expense',
					'sub_group' => 'Indirect Expense',
					'debit' => (float)$t->ptax_amount,
					'credit' => 0,
					'dc' => 'Dr',
					'ledgername' => 'PTAX Ledger'
				];
			}

			// ESI
			if ((float)$t->esi_amount > 0) {
				$rows[] = [
					'date' => $t->date,
					'voucher' => $t->payslip_no,
					'type' => 'Indirect Expense',
					'counter' => 'ESI Contribution',
					'narration' => 'ESI contribution on salary',
					'cgst' => 0,
					'sgst' => 0,
					'igst' => 0,
					'bank' => '',
					'group' => 'Expense',
					'sub_group' => 'Indirect Expense',
					'debit' => (float)$t->esi_amount,
					'credit' => 0,
					'dc' => 'Dr',
					'ledgername' => 'ESI Ledger'
				];
			}
		}

		return $rows;
	}
	
	public function getTotalSalary($userId, $from, $to)
	{
		$rows = [];

		$data = DB::table('user_payslip as up')
			->join('employees as e', 'e.empId', '=', 'up.user_emp_id')
			->where('e.added_by', $userId)
			->whereBetween('up.date', [$from, $to])
			->select(
				'up.date',
				'up.payslip_no',
				DB::raw("
					CAST(
						JSON_UNQUOTE(
							JSON_EXTRACT(
								up.emp_salary_slip_response,
								'$.visible_data.final_salary_calculation.net_salary'
							)
						) AS DECIMAL(15,2)
					) as net_salary
				"),
				'e.employee_id'
			)
			->get();

		//Total Salary
		$totalSalary = $data->sum('net_salary');

		return (float)$totalSalary;
	}

	public function getPfEsiTotal($userId, $from, $to)
	{
		$totals = DB::table('employees as e')
			->join('user_payslip as up', 'up.user_emp_id', '=', 'e.empId')
			->where('e.added_by', $userId)
			->whereBetween('up.date', [$from, $to])
			->selectRaw("
			
				COALESCE(SUM(
					CAST(JSON_UNQUOTE(JSON_EXTRACT(
						up.emp_salary_slip_response,
						'$.visible_data.final_salary_calculation.provident_fund'
					)) AS DECIMAL(15,2))
				),0) as total_pf,
				
				COALESCE(SUM(
					CAST(JSON_UNQUOTE(JSON_EXTRACT(
						up.emp_salary_slip_response,
						'$.visible_data.final_salary_calculation.tds'
					)) AS DECIMAL(15,2))
				),0) as total_tds,

				COALESCE(SUM(
					CAST(JSON_UNQUOTE(JSON_EXTRACT(
						up.emp_salary_slip_response,
						'$.visible_data.final_salary_calculation.ptax'
					)) AS DECIMAL(15,2))
				),0) as total_ptax,

				COALESCE(SUM(
					CAST(JSON_UNQUOTE(JSON_EXTRACT(
						up.emp_salary_slip_response,
						'$.visible_data.final_salary_calculation.esi'
					)) AS DECIMAL(15,2))
				),0) as total_esi
			")
			->first();

		$grand_total = (float)$totals->total_pf + (float)$totals->total_tds + (float)$totals->total_ptax +(float)$totals->total_esi;
		return $grand_total;
	}
	
	public function getCurrentTax($userId, $from, $to)
	{
		// Total Income
		$totalIncome = DB::table('income')
			->where('addBy', $userId)
			->where('status', 1)
			->whereBetween('dateInput', [$from, $to])
			->sum('amount');

		// Total Expenses
		$totalExpense = DB::table('expenses')
			->where('added_by', $userId)
			->where('status', 1)
			->whereBetween('expense_date', [$from, $to])
			->sum('expense_amt');

		// Profit Before Tax
		$profitBeforeTax = $totalIncome - $totalExpense;

		// No tax if loss
		if ($profitBeforeTax <= 0) {
			return 0;
		}

		// Example tax rate 18%
		$taxRate = 18;

		// Current Tax
		$currentTax = ($profitBeforeTax * $taxRate) / 100;

		$totalTds = DB::table('expenses')
			->where('added_by', $userId)
			->where('status', 1)
			->whereBetween('expense_date', [$from, $to])
			->sum('tds_amount');

		$netCurrentTax = $currentTax - $totalTds;

		return max(round($netCurrentTax, 2), 0);
	}

	public function getCurrentTaxPriorYear($userId, $from, $to)
	{
		$totalCurrentTax = DB::table('expenses')
        ->where('added_by', $userId)
		->where('expenses.status', 1) //only active records
        ->whereBetween('expense_date', [$from, $to])
        ->where('expense_type', 'current_tax_prior_year')
        ->selectRaw("
            COALESCE(
                SUM(
                    (expense_amt - COALESCE(tds_amount,0))
                ),0
            ) as total
        ")
        ->value('total');

		return (float) $totalCurrentTax;
	}
	
	public function getDeferredTax($userId, $from, $to)
	{
		/*
		|--------------------------------------------------------------------------
		| Deferred Tax Asset (DTA)
		|--------------------------------------------------------------------------
		| Using assets table directly
		|--------------------------------------------------------------------------
		*/
		$deferredTaxAsset = DB::table('assets')
			->where('added_by', $userId)
			->where('isActive', 1)
			->whereBetween('date', [$from, $to])
			->where('assetType', 'non-current')
			->selectRaw("
				COALESCE(
					SUM(
						COALESCE(depreciation_value, 0)
						* COALESCE(depreciation_rate, 0) / 100
					),
				0) as total_asset
			")
			->value('total_asset');

		/*
		|--------------------------------------------------------------------------
		| Deferred Tax Liability (DTL)
		|--------------------------------------------------------------------------
		*/
		$deferredTaxLiability = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->where('l.added_by', $userId)
			->where('l.status', 1)
			->whereBetween('l.added_date', [$from, $to])
			->where('ncl.liability_category', 'deferred_tax_liabilities')
			->sum('ncl.dtl_amount');

		/*
		|--------------------------------------------------------------------------
		| Net Deferred Tax
		|--------------------------------------------------------------------------
		*/
		$netDeferredTax = (float)$deferredTaxAsset + (float)$deferredTaxLiability;

		return round($netDeferredTax, 2);
	}
	
	public function getEPS($userId, $fromDate, $toDate, $netProfit)
	{
		/*
		|--------------------------------------------------------------------------
		| Equity Shares Outstanding
		|--------------------------------------------------------------------------
		*/
		$equityData = DB::table('share_holder_fund_liabilities as shf')
			->join('liabilities as l', 'l.id', '=', 'shf.liabilities_id')
			->where('shf.added_by', $userId)
			->where('l.status', 1)
			->where('shf.share_holder_fund_type', 'share_capital')
			->where('shf.share_holder_type', 'equity_share_capital')
			->whereBetween('l.added_date', [$fromDate, $toDate])
			->selectRaw("
				COALESCE(SUM(total_amount),0) as total_amount,
				COALESCE(MAX(face_value_per_share),1) as face_value
			")
			->first();

		$totalAmount = (float) ($equityData->total_amount ?? 0);

		$faceValue = (float) ($equityData->face_value ?? 1);

		// Outstanding Equity Shares
		$equityShares = 0;

		if ($faceValue > 0) {
			$equityShares = $totalAmount / $faceValue;
		}

		/*
		|--------------------------------------------------------------------------
		| Convertible Debentures
		|--------------------------------------------------------------------------
		*/
		$convertibleLoans = DB::table('non_current_liabilities')
			->where('added_by', $userId)
			->where('liability_category', 'convertible_debentures')
			->whereRaw("STR_TO_DATE(loan_disbursement_date,'%Y-%m-%d') <= ?", [$toDate])
			->where(function ($query) use ($fromDate) {
				$query->whereNull('maturity_date')
					->orWhereRaw("STR_TO_DATE(maturity_date,'%Y-%m-%d') >= ?", [$fromDate]);
			})
			->get();

		$convertibleShares = 0;

		foreach ($convertibleLoans as $loan) {

			$loanAmount = (float) ($loan->loan_amount ?? 0);

			if ($faceValue > 0) {
				$convertibleShares += ($loanAmount / $faceValue);
			}
		}

		/*
		|--------------------------------------------------------------------------
		| Basic EPS
		|--------------------------------------------------------------------------
		*/
		$basicEPS = 0;

		if ($equityShares > 0) {
			$basicEPS = $netProfit / $equityShares;
		}

		/*
		|--------------------------------------------------------------------------
		| Diluted EPS
		|--------------------------------------------------------------------------
		*/
		$totalDilutedShares = $equityShares + $convertibleShares;

		$dilutedEPS = 0;

		if ($totalDilutedShares > 0) {
			$dilutedEPS = $netProfit / $totalDilutedShares;
		}

		return [
			'equity_shares'      => round($equityShares, 2),
			'convertible_shares' => round($convertibleShares, 2),
			'basic_eps'          => round($basicEPS, 2),
			'diluted_eps'        => round($dilutedEPS, 2),
		];
	}




}
