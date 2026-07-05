<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use Carbon\Carbon;

class AssetsService
{
    
    public function __construct()
    {
        
    }
	
	public function inventoryRows($propId,$userId, $from, $to)
	{
		$rows = [];

		// Check if any sales exist in date range
		$salesQuery = DB::table('sales')
						->where('sales.status', 1) //only active records
						->whereBetween('inv_date', [$from, $to]);

		if (!empty($propId)) {
			$salesQuery->where('propId', $propId);
		} else {
			$salesQuery->where('added_by', $userId);
		}

		$hasSales = $salesQuery->exists();

		if (!$hasSales) {
			return $rows;
		}

		// Closing stock calculation
		$closingStock = DB::table('products as p')
			->leftJoin('sales_values as sv', 'sv.prod_id', '=', 'p.id')
			->leftJoin('sales as s', function ($join) use ($propId,$userId,$from,$to) {

				$join->on('s.id', '=', 'sv.sid')
					 ->whereBetween('s.inv_date', [$from, $to]);

				if (!empty($propId)) {
					$join->where('s.propId', $propId);
				} else {
					$join->where('s.added_by', $userId);
				}
			});

		$closingStock->where('p.added_by', $userId);

		$closingStock = $closingStock
			->where('p.item_type', '!=', 'service')
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

		if ($totalClosingStock > 0) {
			$rows[] = [
				'date' => $to,
				'voucher' => 'CLOSING-STOCK',
				'type' => 'Current Asset',
				'counter' => 'Inventories',
				'narration' => 'Closing Stock as on ' . $to,
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Current Asset',
				'debit' => $totalClosingStock,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Stock Ledger'
			];
		}

		return $rows;
	}

	public function tradeReceivableRows($propId,$userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('sales')
			->where('sales.status', 1) //only active records
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
				'counter' => 'Trade Receivables',
				'narration' => 'Outstanding invoice',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Current Asset',
				'debit' => $s->due_amount,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Customer Ledger'
			];
		}

		return $rows;
	}

	
	public function unbilledRevenueRows($propId,$userId, $from, $to)
	{
		$rows = [];

		//Partial Purchases (Advance Given but not fully adjusted)
		$query = DB::table('purchases')
				->where('purchases.status', 1) //only active records
				->where('pay_status', 'Partial')
				->whereBetween('inv_date', [$from, $to]);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('propId', $propId);
		} else {
			$query->where('added_by', $userId);
		}

		$purchaseData = $query->get();

		foreach ($purchaseData as $p) {

			$balanceAmount = (float)$p->total_amount - (float)$p->advance_amount;

			if ($balanceAmount <= 0) continue;

			$rows[] = [
				'date'       => $p->inv_date,
				'voucher'    => $p->inv_num ?? '',
				'type'       => 'Current Asset',
				'counter'    => $p->seller_name ?? 'Supplier',
				'narration'  => 'Advance paid - Balance pending',
				'cgst'       => 0,
				'sgst'       => 0,
				'igst'       => 0,
				'bank'       => '',
				'group'      => 'Asset',
				'sub_group'  => 'Current Asset',
				'debit'      => $balanceAmount,
				'credit'     => 0,
				'dc'         => 'Dr',
				'ledgername' => 'Unearned Revenue Ledger'
			];
		}

		//Projects Completed but Not Billed (Unbilled Revenue)
		$projectData = DB::table('projects')
			->where('added_by', $userId)
			->where('proj_status','=', 'Done')
			->whereBetween('proj_start_date', [$from, $to])
			->get();

		foreach ($projectData as $p) {

			if ((float)$p->proj_cost <= 0) continue;

			$rows[] = [
				'date'       => $p->proj_start_date,
				'voucher'    => '',
				'type'       => 'Current Asset',
				'counter'    => 'Unbilled Revenue',
				'narration'  => 'Work completed not billed',
				'cgst'       => 0,
				'sgst'       => 0,
				'igst'       => 0,
				'bank'       => '',
				'group'      => 'Asset',
				'sub_group'  => 'Current Asset',
				'debit'      => (float)$p->proj_cost,
				'credit'     => 0,
				'dc'         => 'Dr',
				'ledgername' => 'Unbilled Ledger'
			];
		}

		return $rows;
	}
	
	public function gstReceivableRows($propId,$userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->whereBetween('p.inv_date', [$from, $to])
			->where('p.status', 1) //only active records
			->select(
				'p.inv_date',
				'p.inv_num',
				'pv.gst_rate',
				'pv.gst_trans',
				'pv.tax_amt'
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('p.propId', $propId);
		} else {
			$query->where('p.added_by', $userId);
		}

		$purchaseData = $query->get();

		foreach ($purchaseData as $p) {

			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			$totalTax = ((float)$p->tax_amt * (float)$p->gst_rate) / 100;

			if ($p->gst_trans == 'intrastate') {

				$cgst = $totalTax / 2;
				$sgst = $totalTax / 2;

			} elseif ($p->gst_trans == 'interstate') {

				$igst = $totalTax;
			}

			if ($totalTax > 0) {

				$rows[] = [
					'date'       => $p->inv_date,
					'voucher'    => $p->inv_num,
					'type'       => 'Purchase',
					'counter'    => 'GST Input Credit',
					'narration'  => 'GST receivable on purchase',
					'cgst'       => round($cgst, 2),
					'sgst'       => round($sgst, 2),
					'igst'       => round($igst, 2),
					'bank'       => '',
					'group'      => 'Current Asset',
					'sub_group'  => 'GST Receivable',
					'debit'      => round($totalTax, 2),
					'credit'     => 0,
					'dc'         => 'Dr',
					'ledgername' => 'GST Receivable Ledger'
				];
			}
		}

		return $rows;
	}
	
	public function tdsReceivableRows($propId,$userId, $from, $to)
	{
		$rows = [];

		// TDS from Sales
		$salesQuery = DB::table('sales')
			->whereBetween('inv_date', [$from, $to])
			->where('sales.status', 1) //only active records
			->where('tds_amount', '>', 0)
			->select('inv_date', 'inv_num', 'tds_amount');

		if (!empty($propId)) {
			$salesQuery->where('propId', $propId);
		} else {
			$salesQuery->where('added_by', $userId);
		}

		$salesData = $salesQuery->get();

		foreach ($salesData as $s) {

			$rows[] = [
				'date' => $s->inv_date,
				'voucher' => $s->inv_num,
				'type' => 'Current Asset',
				'counter' => 'TDS Receivable',
				'narration' => 'TDS deducted by customer (Sales)',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Current Asset',
				'debit' => (float)$s->tds_amount,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'TDS Receivable Ledger'
			];
		}

		// TDS from Incomes
		$incomeQuery = DB::table('income')
			->whereBetween('dateInput', [$from, $to])
			->where('income.status', 1) //only active records
			->where('tds_amount', '>', 0)
			->select('dateInput', 'tds_amount');

		if (!empty($propId)) {
			$incomeQuery->where('propId', $propId);
		} else {
			$incomeQuery->where('addBy', $userId);
		}

		$incomeData = $incomeQuery->get();

		foreach ($incomeData as $i) {

			$rows[] = [
				'date' => $i->dateInput,
				'voucher' => 'INCOME',
				'type' => 'Current Asset',
				'counter' => 'TDS Receivable',
				'narration' => 'TDS deducted on income',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Current Asset',
				'debit' => (float)$i->tds_amount,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'TDS Receivable Ledger'
			];
		}

		return $rows;
	}
	
	public function wipRows($userId, $from, $to)
	{
		$data = DB::table('projects')
			->where('added_by', $userId)
			->where('proj_status', '=', 'Ongoing')
			->whereBetween('proj_start_date', [$from, $to])
			->get();

		$rows = [];
		foreach ($data as $p) {
			$rows[] = [
				'date' => $p->proj_start_date,
				'voucher' => '',
				'type' => 'Current Asset',
				'counter' => 'Unbilled Revenue',
				'narration' => 'Work completed not billed',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Current Asset',
				'debit' => $p->proj_cost,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Unbilled Ledger'
			];
		}
		return $rows;
	}
	
	
	
	public function vendorAdvanceRows($propId,$userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('purchases')
			->whereBetween('inv_date', [$from, $to])
			->where('purchases.status', 1) //only active records
			->where('advance_amount', '>', 0);   // only advances

		// Ownership logic
		if (!empty($propId)) {
			$query->where('propId', $propId);
		} else {
			$query->where('added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $p) {
			$rows[] = [
				'date' => $p->inv_date,
				'voucher' => $p->inv_num,
				'type' => 'Current Asset',
				'counter' => 'Vendor Advances',
				'narration' => 'Advance paid to vendor',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Current Asset',
				'debit' => $p->advance_amount,
				'credit' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Vendor Advance Ledger'
			];
		}

		return $rows;
	}

}
