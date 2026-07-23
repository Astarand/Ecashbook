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

class ProfitLossService
{
    
    public function __construct()
    {
        
    }
	
	public function getCurrentTax($userId, $startDate, $endDate, $pbt = 0)
	{
		$result = DB::table('expenses')
			->where('added_by', $userId)
			->where('status', 1)
			->whereBetween('expense_date', [$startDate, $endDate])
			->whereNotNull('tax_treatment')
			->selectRaw("
				SUM(
					CASE
						WHEN tax_treatment = 'Disallowed'
							THEN expense_amt
						ELSE 0
					END
				) as disallowed_total,

				SUM(
					CASE
						WHEN tax_treatment = 'Partial Allowed'
							THEN GREATEST(expense_amt - rebate_amt,0)
						ELSE 0
					END
				) as partial_disallowed_total,

				SUM(
					CASE
						WHEN tax_treatment = 'Fully Allowed'
							THEN expense_amt
						ELSE 0
					END
				) as fully_allowed_total
			")
			->first();

		$currentTax =($result->disallowed_total ?? 0) + ($result->partial_disallowed_total ?? 0);

		return round($currentTax, 2);
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
		$totalDilutedShares = $equityShares;

		$dilutedEPS = 0;

		if ($totalDilutedShares > 0) {
			$dilutedEPS = $netProfit / $totalDilutedShares;
		}

		return [
			'equity_shares'      => round($equityShares, 2),
			'basic_eps'          => round($basicEPS, 2),
			'diluted_eps'        => round($dilutedEPS, 2),
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
	
	public function calculateDepreciation($asset, $fromDate, $toDate, $period_type)
	{
		$cost      = $asset->invoice_value;
		$residual  = $asset->residual_value ?? 0;
		$rate      = $asset->depreciation_rate;
		$life      = $asset->useful_life_years;
		$method    = strtoupper($asset->depreciation_method);
		$frequency = strtolower($asset->depreciation_frequency);

		$startDate = $asset->depreciation_start_date ?? $asset->purchaseDateAudit ?? $asset->date;
		if (!$startDate) {
			return 0;
		}

		$start = Carbon::parse($startDate);
		if ($start->gt(Carbon::parse($toDate))) {
			return 0;
		}

		$years = $start->diffInYears(Carbon::parse($toDate));

		if ($method == 'SLM') 
		{
			if ($life > 0) {
				$annual = ($cost - $residual) / $life;
			} else {
				$annual = $cost * ($rate / 100);
			}
		} 
		else 
		{
			// WDV
			$opening = $cost;
			for ($i=0;$i<$years;$i++) {
				$opening -= ($opening * $rate /100);
			}
			$annual = $opening * ($rate /100);
		}

		if ($frequency == 'half year') {
			return $annual/2;
		}
		
		return $annual;
	}
	
	public function calculatePL($startDate, $endDate, $userId, $period_type)
	{
		/* ================================
		 | REVENUE – SALES & SERVICES
		 ================================*/
		$totalReseller = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->join('products as p', 'p.id', '=', 'sv.prod_id')
						->where('s.status', 1)
						->where('p.item_type', 'product')
						->where('s.added_by', $userId)
						->whereBetween('s.inv_date', [$startDate, $endDate])
						->sum('sv.amount');
		$totalService = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->join('products as p', 'p.id', '=', 'sv.prod_id')
						->where('s.status', 1)
						->where('p.item_type', 'service')
						->where('s.added_by', $userId)
						->whereBetween('s.inv_date', [$startDate, $endDate])
						->sum('sv.amount');
		
		//Start Sales credit/debit
		$invoiceType = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->join('products as p', 'p.id', '=', 'sv.prod_id')
						->select(
							's.inv_num',
							DB::raw('MAX(p.item_type) as item_type')
						)
						->where('s.added_by', $userId)
						->whereBetween('s.inv_date', [$startDate, $endDate])
						->groupBy('s.inv_num');
						
		$voucherAdjustments = DB::table('vouchers as v')
								->joinSub($invoiceType, 't', function ($join) {
									$join->on('t.inv_num', '=', 'v.invoice_number');
								})
								->selectRaw("
									t.item_type,

									SUM(
										CASE
											WHEN v.note_type = 'Credit'
											THEN COALESCE(v.total_amt, 0)
												 - COALESCE(v.cgst_amount, 0)
												 - COALESCE(v.sgst_amount, 0)
												 - COALESCE(v.igst_amount, 0)
											ELSE 0
										END
									) AS credit
								")
								->where('v.added_by', $userId)
								->whereBetween('v.inv_date', [$startDate, $endDate])
								->groupBy('t.item_type')
								->get();
		//End Sales credit/debit
		$productCr = $serviceCr = 0;
		foreach ($voucherAdjustments as $row) {
			if ($row->item_type == 'product') {
				$productCr = $row->credit;
			} else {
				$serviceCr = $row->credit;
			}
		}

		$totalReseller = ($totalReseller - $productCr);
		$totalService  = ($totalService - $serviceCr);
		$profit_sale = $totalReseller + $totalService;

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
							DB::raw("SUM(amount) as total")
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
				DB::raw("SUM(amount) as total")
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
		$itemTotal = DB::table('purchase_values as pv')
						->join('purchases as p', 'p.id', '=', 'pv.sid')
						->where('p.added_by', $userId)
						->where('p.status', 1)
						->whereBetween('p.inv_date', [$startDate, $endDate])
						->sum('pv.amount');

		$shippingTotal = DB::table('purchases')
						->where('added_by', $userId)
						->where('status', 1)
						->whereBetween('inv_date', [$startDate, $endDate])
						->sum('shipping_cost');
		
		//Start Purchase credit/debit
		$purchaseInvoiceType = DB::table('purchases as p')
						->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
						->join('products as pr', 'pr.id', '=', 'pv.prod_id')
						->selectRaw('
							p.inv_num,
							MAX(pr.item_type) as item_type
						')
						->where('p.added_by', $userId)
						->whereBetween('p.inv_date', [$startDate, $endDate])
						->groupBy('p.id', 'p.inv_num');
			
		$purchaseVoucherAdjustments = DB::table('voucher_purchases as vp')
								->joinSub($purchaseInvoiceType, 't', function ($join) {
									$join->on('t.inv_num', '=', 'vp.inv_number');
								})
								->selectRaw("
									t.item_type,

									SUM(
										CASE
											WHEN vp.note_type = 'Debit'
											THEN COALESCE(vp.total_amt, 0)
												 - COALESCE(vp.cgst_amount, 0)
												 - COALESCE(vp.sgst_amount, 0)
												 - COALESCE(vp.igst_amount, 0)
											ELSE 0
										END
									) AS debit
								")
								->where('vp.added_by', $userId)
								->whereBetween('vp.inv_date', [$startDate, $endDate])
								->groupBy('t.item_type')
								->get();

		$productPurchaseDr = 0;
		$servicePurchaseDr = 0;
		foreach ($purchaseVoucherAdjustments as $row) {
			if ($row->item_type == 'product') {
				$productPurchaseDr = $row->debit;
			} elseif ($row->item_type == 'service') {
				$servicePurchaseDr = $row->debit;
			}
		}
		
		$totalPurchaseVoucherDr = $productPurchaseDr + $servicePurchaseDr;
		$purchases = (($itemTotal ?? 0) - $totalPurchaseVoucherDr); 
		$totalPurchases = (($itemTotal ?? 0) - $totalPurchaseVoucherDr);
		//End Purchase credit/debit
		
		/*
		|--------------------------------------------------------------------------
		| Direct Expenses
		|--------------------------------------------------------------------------
		*/
		$directExpenseHeads = DB::table('dropdown_values')
								->where('module', 'Expense')
								->where('dropdown_name', 'direct')
								->where('status', 1)
								->orderBy('sort_order')
								->get();

		$directExpenseLabels = $directExpenseHeads->pluck('option_text', 'option_value')->toArray();
		$allowedDirectExpenseTypes = $directExpenseHeads->pluck('option_value')->toArray();

		$directExpenseDetails = DB::table('expenses')
								->where('added_by', $userId)
								->where('status', 1)
								->where('expense_cat', 'direct')
								->whereBetween('expense_date', [$startDate, $endDate])
								->whereIn('expense_type', $allowedDirectExpenseTypes)
								->select(
									'expense_type',
									DB::raw("SUM(COALESCE(expense_amt,0)) as total")
								)
								->groupBy('expense_type')
								->get();
								
		$directExpenseArray = [];
		// Initialize all heads
		foreach ($directExpenseHeads as $head) {
			$directExpenseArray[$head->option_text] = 0;
		}
		// Fill actual values
		foreach ($directExpenseDetails as $expense) {
			$label = $directExpenseLabels[$expense->expense_type]?? $expense->expense_type;
			$directExpenseArray[$label] += $expense->total;
		}
		$directExpenseArray = array_filter($directExpenseArray,fn($value) => $value > 0);

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
		$directExpenseTotal = array_sum($directExpenseArray);
		$cogsTotal = ($openingStock + $totalPurchases + $directExpenseTotal - $closingStock);
		$cogs = [
			'opening_stock'  => $openingStock,
			'purchases'      => $purchases,
			'direct_expense' => $directExpenseArray,
			'directExpenseTotal'=> $directExpenseTotal,
			'closing_stock'  => $closingStock,
			'total_cogs'     => $cogsTotal,
		];

		/* ================================
		 | INDIRECT EXPENSES
		 ================================*/			
		//Indirect Expense Category		
		$expenseHeads = DB::table('dropdown_values')
			->where('module', 'Expense')
			->where('dropdown_name', 'indirect')
			->where('status', 1)
			->orderBy('sort_order')
			->get();

		$allowedExpenseTypes = $expenseHeads->pluck('option_value')->toArray();
		$expenseLabels = $expenseHeads->pluck('option_text', 'option_value')->toArray();

		$indirectExpenses = DB::table('expenses')
			->whereBetween('expense_date', [$startDate, $endDate])
			->where('added_by', $userId)
			->where('status', 1)
			->where('expense_cat', 'indirect')
			->whereIn('expense_type', $allowedExpenseTypes)
			->select(
				'expense_type',
				DB::raw("SUM(COALESCE(expense_amt,0)) as total")
			)
			->groupBy('expense_type')
			->get();

		//Start Asset Depericiation Calculation
		$inputDate = Carbon::parse($endDate);
		if ($inputDate->month >= 4) {
			$fyStart = Carbon::create($inputDate->year, 4, 1)->toDateString();
			$fyEnd   = Carbon::create($inputDate->year + 1, 3, 31)->toDateString();
		} else {
			$fyStart = Carbon::create($inputDate->year - 1, 4, 1)->toDateString();
			$fyEnd   = Carbon::create($inputDate->year, 3, 31)->toDateString();
		}
		$assets = DB::table('assets')
						->where('added_by', $userId)
						->where('assetType', 'non-current')
						->whereBetween('date', [$fyStart, $fyEnd])
						->where('isActive', 1)
						->get();

		$depreciationExpense = 0;
		foreach ($assets as $asset) {
			$depreciationExpense += $this->calculateDepreciationByPeriod($asset,$fyStart,$fyEnd,$period_type);
		}
								
		$financeCosts = DB::table('expenses')
							->whereBetween('expense_date', [$startDate, $endDate])
							->where('added_by', $userId)
							->where('status', 1)
							->where('expense_cat', 'indirect')
							->whereIn('expense_type', [
								'interest_expense',
								'bank_charges',
								'interest_on_business_loan',
								'Processing Charges'
							])
							->select(
								'expense_type',
								DB::raw("SUM(COALESCE(expense_amt,0)) as total")
							)
							->groupBy('expense_type')
							->get()
							->pluck('total', 'expense_type')
							->toArray();
							
		$indirectExpenseArray = [];
		// Create all heads with zero amount first
		foreach ($expenseHeads as $head) {
			$indirectExpenseArray[$head->option_text] = 0;
		}
		foreach ($indirectExpenses as $expense) {
			$label = $expenseLabels[$expense->expense_type] ?? $expense->expense_type;
			$indirectExpenseArray[$label] += $expense->total;
		}
		$indirectExpenseArray = array_filter($indirectExpenseArray,fn($value) => $value > 0);
		$totalIndirectExpenses = array_sum($indirectExpenseArray);
		
		$operatingIncomeTotal = collect($operatingIncomeDetails)->sum('total');
		$nonOperatingIncomeTotal = collect($nonOperatingIncomeDetails)->sum('total');
		//$totalRevenue = ($totalReseller + $totalService + $operatingIncomeTotal + $nonOperatingIncomeTotal);
		$totalRevenue = ($profit_sale + $operatingIncomeTotal + $nonOperatingIncomeTotal);
		
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
		//Tax Expense
		$current_tax = $this->getCurrentTax($userId, $startDate, $endDate, $pbt);
		$start = \Carbon\Carbon::parse($startDate)->subYear();
		$end   = \Carbon\Carbon::parse($endDate)->subYear();
		$current_tax_expenses_prior_years = $this->getCurrentTaxPriorYear($userId, $startDate, $endDate);
		$deferred_tax = 0;//$this->getDeferredTax($userId, $startDate, $endDate);
		$totalTax = ($current_tax + $current_tax_expenses_prior_years + $deferred_tax);
		$tax = [
				'current_tax' => $current_tax ?? 0,
				'current_tax_expenses_prior_years' => $current_tax_expenses_prior_years ?? 0,
				'deferred_tax' => $deferred_tax,
				'minimum_alternate_tax' => 0,
				'totalTax' => $totalTax,
			];
		//Profit After Tax
		$pat = ($pbt - $totalTax);		
		
		//SHARE CAPITAL FOR EPS
		$netProfit = ($totalRevenue - $totalIndirectExpenses - $totalTax);
		$getEPS = $this->getEPS($userId, $startDate, $endDate, $netProfit);
		
		return [
			'revenue' => [
				'totalReseller' => $totalReseller,
				'totalService' => $totalService,								
				'operatingIncomeDetails' => $operatingIncomeDetails,
				'operatingIncomeTotal' => $operatingIncomeTotal,
				'nonOperatingIncomeDetails' => $nonOperatingIncomeDetails,
				'nonOperatingIncomeTotal' => $nonOperatingIncomeTotal,
				'total_sales_income' => $totalRevenue,
			],
			'cogs' => $cogs,
			'expenses' => $indirectExpenseArray,
			'ebitda' => $ebitda,
			'depreciationExp' => $depreciationExp,
			'ebit' => $ebit,
			'finance_cost' => [
				'interest_on_loan' => $interestOnLoan,
				'bank_charges' => $bankCharges,
				'od_cc_interest' => $odCcInterest,
				'processing_charges' => $processingCharges,
				'total_finance_cost' => $totalFinanceCost,
			],
			'pbt' => $pbt,
			'tax' => $tax,
			'pat' => $pat,
			'eps' => $getEPS
		];
	}
	

}
