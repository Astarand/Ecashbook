<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class LiabilitiesService
{
    
    public function __construct()
    {
        
    }
	
	//Non Current Liability
	


	//Current Liability
	public function tradePayableRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('purchases')
			->where('purchases.status', 1) //only active records
			->where('pay_status', '!=', 'Full')   // not fully paid
			->where('due_amount', '>', 0)         // only outstanding
			->whereBetween('inv_date', [$from, $to]);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('propId', $propId);
		} else {
			$query->where('added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $s) {
			$rows[] = [
				'date' => $s->inv_date,
				'voucher' => $s->inv_num,
				'type' => 'Current Asset',
				'counter' => 'Trade Payable',
				'narration' => 'Amount payable to suppliers',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Liability',
				'sub_group' => 'Current Liability',
				'debit' => 0,
				'credit' => $s->due_amount,
				'dc' => 'Dr',
				'ledgername' => 'Trade Payable Ledger'
			];
		}

		return $rows;
	}
	
	public function gstPayableRows($propId, $userId, $from, $to)
	{
		$rows = [];

		// 1️⃣ OUTPUT GST FROM SALES
		$query = DB::table('sales as s')
			->join('sales_values as sv', 'sv.sid', '=', 's.id')
			->where('s.status', 1) //only active records
			->whereBetween('s.inv_date', [$from, $to])
			->select(
				's.inv_date',
				's.inv_num',
				'sv.tax_amt',
				'sv.gst_trans'
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('s.propId', $propId);
		} else {
			$query->where('s.added_by', $userId);
		}

		$salesData = $query->get();

		foreach ($salesData as $sale) {

			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			if ($sale->gst_trans == 'intrastate') {
				$cgst = $sale->tax_amt / 2;
				$sgst = $sale->tax_amt / 2;
			} else {
				$igst = $sale->tax_amt;
			}

			$rows[] = [
				'date'       => $sale->inv_date,
				'voucher'    => $sale->inv_num,
				'type'       => 'Sales',
				'counter'    => 'GST Output',
				'narration'  => 'GST payable on sales',
				'cgst'       => round($cgst, 2),
				'sgst'       => round($sgst, 2),
				'igst'       => round($igst, 2),
				'bank'       => '',
				'group'      => 'Liability',
				'sub_group'  => 'Current Liability',
				'debit'      => 0,
				'credit'     => round($sale->tax_amt, 2),
				'dc'         => 'Cr',
				'ledgername' => 'GST Payable Ledger'
			];
		}

		// 2️⃣ GST PAID (GSTR3B - PDCASH)
		$returns = DB::table('gst_returns')
			->where('userid', $userId)
			->where('ret_type', 'gstr3b')
			->whereBetween('posted_date', [$from, $to])
			->pluck('req_data');

		foreach ($returns as $json) {

			$data = json_decode($json, true);

			if (isset($data['tx_pmt']['pdcash'])) {

				foreach ($data['tx_pmt']['pdcash'] as $cash) {

					$paidAmount =
						($cash['cpd'] ?? 0) +
						($cash['spd'] ?? 0) +
						($cash['ipd'] ?? 0) +
						($cash['cspd'] ?? 0);

					if ($paidAmount > 0) {

						$rows[] = [
							'date'       => $from,
							'voucher'    => 'GSTR3B',
							'type'       => 'GST Payment',
							'counter'    => 'GST Paid',
							'narration'  => 'GST paid via GSTR3B',
							'cgst'       => 0,
							'sgst'       => 0,
							'igst'       => 0,
							'bank'       => '',
							'group'      => 'Liability',
							'sub_group'  => 'Current Liability',
							'debit'      => round($paidAmount, 2),
							'credit'     => 0,
							'dc'         => 'Dr',
							'ledgername' => 'GST Payable Ledger'
						];
					}
				}
			}
		}

		return $rows;
	}
	
	public function advanceFromCustomerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('sales')
			->where('sales.status', 1) //only active records
			->where('pay_status', '!=', 'Full')   // not fully paid
			->where('advance_amount', '>', 0)     // only advance
			->whereBetween('inv_date', [$from, $to]);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('propId', $propId);
		} else {
			$query->where('added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $s) {
			$rows[] = [
				'date' => $s->inv_date,
				'voucher' => $s->inv_num,
				'type' => 'Current Asset',
				'counter' => 'Advance for Customer',
				'narration' => 'Advance for Customer',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Liability',
				'sub_group' => 'Current Liability',
				'debit' => 0,
				'credit' => $s->advance_amount, // advance only
				'dc' => 'Dr',
				'ledgername' => 'Advance for Customer Ledger'
			];
		}

		return $rows;
	}
	
	public function tdsPayableRows($propId, $userId, $from, $to)
	{
		$rows = [];

		/* ---------------- PURCHASE TDS ---------------- */

		$purchaseQuery = DB::table('purchases')
			->whereBetween('inv_date', [$from, $to])
			->where('purchases.status', 1) //only active records
			->where('tds_amount', '>', 0)
			->select('inv_date', 'inv_num', 'tds_amount');

		if (!empty($propId)) {
			$purchaseQuery->where('propId', $propId);
		} else {
			$purchaseQuery->where('added_by', $userId);
		}

		$purchaseData = $purchaseQuery->get();

		foreach ($purchaseData as $p) {

			$rows[] = [
				'date' => $p->inv_date,
				'voucher' => $p->inv_num,
				'type' => 'Purchase',
				'counter' => 'TDS on Purchase',
				'narration' => 'TDS deducted on purchase',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Liability',
				'sub_group' => 'Current Liability',
				'debit' => 0,
				'credit' => (float)$p->tds_amount,
				'balance' => 0,
				'dc' => 'Cr',
				'ledgername' => 'TDS Payable Ledger'
			];
		}

		/* ---------------- SALARY TDS ---------------- */

		$salaryQuery = DB::table('employees as e')
			->join('user_payslip as up', 'up.user_emp_id', '=', 'e.empId')
			->whereBetween('up.date', [$from, $to])
			->select(
				'up.date',
				'up.payslip_no',
				DB::raw("
					CAST(
						JSON_UNQUOTE(
							JSON_EXTRACT(
								up.emp_salary_slip_response,
								'$.visible_data.final_salary_calculation.tds'
							)
						) AS DECIMAL(15,2)
					) as tds_amount
				")
			);

		if (!empty($propId)) {
			$salaryQuery->where('e.propId', $propId);
		} else {
			$salaryQuery->where('e.added_by', $userId);
		}

		$salaryData = $salaryQuery->get();

		foreach ($salaryData as $s) {

			if ((float)$s->tds_amount <= 0) continue;

			$rows[] = [
				'date' => $s->date,
				'voucher' => $s->payslip_no,
				'type' => 'Salary',
				'counter' => 'TDS on Salary',
				'narration' => 'TDS deducted from employee salary',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Liability',
				'sub_group' => 'Current Liability',
				'debit' => 0,
				'credit' => (float)$s->tds_amount,
				'balance' => 0,
				'dc' => 'Cr',
				'ledgername' => 'TDS Payable Ledger'
			];
		}

		/* ---------------- EXPENSE TDS ---------------- */

		$expenseQuery = DB::table('expenses')
			->whereBetween('expense_date', [$from, $to])
			->where('expenses.status', 1) //only active records
			->where('tds_amount', '>', 0)
			->select('expense_date', 'exp_invno', 'tds_amount');

		if (!empty($propId)) {
			$expenseQuery->where('propId', $propId);
		} else {
			$expenseQuery->where('added_by', $userId);
		}

		$expenseData = $expenseQuery->get();

		foreach ($expenseData as $e) {

			$rows[] = [
				'date' => $e->expense_date,
				'voucher' => $e->exp_invno,
				'type' => 'Expense',
				'counter' => 'TDS on Expense',
				'narration' => 'TDS deducted on expense',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Liability',
				'sub_group' => 'Current Liability',
				'debit' => 0,
				'credit' => (float)$e->tds_amount,
				'balance' => 0,
				'dc' => 'Cr',
				'ledgername' => 'TDS Payable Ledger'
			];
		}

		return $rows;
	}



}
