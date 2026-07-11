<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use DB;
// use Auth;
use Validator;
use App\User;
use App\Models\Banks;
use App\Models\Bank_trans;
use App\Models\Bank_statements;
use Carbon\Carbon;
use PDF;
use App\Services\ExpensesService;
use App\Services\AssetsService;
use App\Services\LiabilitiesService;
use App\Services\ReportsService;
use App\Services\ProfitLossService;

class ProfitLossController extends Controller
{
	private $expensesService;
	private $assetsService;
	private $liabilitiesService;
	private $reportsService;
	private $profitLossService;

    public function __construct(ProfitLossService $profitLossService,ReportsService $reportsService, ExpensesService $expensesService, AssetsService $assetsService, LiabilitiesService $liabilitiesService)
    {
        $this->reportsService = $reportsService;
        $this->expensesService = $expensesService;
        $this->assetsService = $assetsService;
        $this->liabilitiesService = $liabilitiesService;
        $this->profitLossService = $profitLossService;
		$this->middleware('auth');
    }

    public function ProfitLoss(request $request)
    {	
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		checkCoreAccess('Profit & Loss');
        return view('User.Reports.profit-loss-report');
    }
	
	public function profit_loss_data(Request $request)
    {
		$userId = currentOwnerId();

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}

		$financial_year = $request->financial_year;
		$period_type = $request->period_type;
		$dynamic_period = $request->dynamic_period;
		// Current period
		[$startDate, $endDate] = $this->getDateRange($financial_year,$period_type,$dynamic_period);
		// Previous period
		[$prevStartDate, $prevEndDate] = $this->getPreviousDateRange($startDate, $endDate);

		$pYearData = $this->calculatePL($prevStartDate, $prevEndDate, $userId);
		$cYearData = $this->calculatePL($startDate, $endDate, $userId);

        return response()->json([
            'success' => true,
            'start_date' => $startDate,
            'end_date' => $endDate,
			'previousYearData' => $pYearData,
            'currentYearData' => $cYearData,
            
        ]);
	}
	
	private function calculatePL($startDate, $endDate, $userId)
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
					SUM(CASE WHEN v.note_type='Credit' THEN v.total_amt ELSE 0 END) AS credit,
					SUM(CASE WHEN v.note_type='Debit' THEN v.total_amt ELSE 0 END) AS debit
				")
				->where('v.added_by', $userId)
				->whereBetween('v.inv_date', [$startDate, $endDate])
				->groupBy('t.item_type')
				->get();
		//End Sales credit/debit
		$productCr = $productDr = $serviceCr = $serviceDr = 0;
		foreach ($voucherAdjustments as $row) {
			if ($row->item_type == 'product') {
				$productCr = $row->credit;
				$productDr = $row->debit;
			} else {
				$serviceCr = $row->credit;
				$serviceDr = $row->debit;
			}
		}

		$totalReseller = ($totalReseller - $productCr + $productDr);
		$totalService  = ($totalService - $serviceCr + $serviceDr);
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
						->selectRaw('SUM(COALESCE(pv.amount,0) + COALESCE(pv.tax_amt,0)) as total')
						->value('total');

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
				SUM(CASE WHEN vp.note_type='Credit' THEN vp.total_amt ELSE 0 END) AS credit,
				SUM(CASE WHEN vp.note_type='Debit' THEN vp.total_amt ELSE 0 END) AS debit
			")
			->where('vp.added_by', $userId)
			->whereBetween('vp.inv_date', [$startDate, $endDate])
			->groupBy('t.item_type')
			->get();

		$productPurchaseCr = $productPurchaseDr = 0;
		$servicePurchaseCr = $servicePurchaseDr = 0;

		foreach ($purchaseVoucherAdjustments as $row) {
			if ($row->item_type == 'product') {
				$productPurchaseCr = $row->credit;
				$productPurchaseDr = $row->debit;
			} elseif ($row->item_type == 'service') {
				$servicePurchaseCr = $row->credit;
				$servicePurchaseDr = $row->debit;
			}
		}
		
		$totalPurchaseVoucherCr = $productPurchaseCr + $servicePurchaseCr;
		$totalPurchaseVoucherDr = $productPurchaseDr + $servicePurchaseDr;
		$purchases = (($itemTotal ?? 0) - $totalPurchaseVoucherCr  + $totalPurchaseVoucherDr);
		$totalPurchases = (($itemTotal ?? 0) - $totalPurchaseVoucherCr  + $totalPurchaseVoucherDr);
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
		$current_tax = $this->profitLossService->getCurrentTax($userId, $startDate, $endDate, $pbt);
		$start = \Carbon\Carbon::parse($startDate)->subYear();
		$end   = \Carbon\Carbon::parse($endDate)->subYear();
		$current_tax_expenses_prior_years = $this->profitLossService->getCurrentTaxPriorYear($userId, $startDate, $endDate);
		$deferred_tax = 0;//$this->profitLossService->getDeferredTax($userId, $startDate, $endDate);
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
		$getEPS = $this->profitLossService->getEPS($userId, $startDate, $endDate, $netProfit);
		
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
	
	public function getNetProfit($totalRevenue, $expenses, $tax)
	{
		$totalExpenses = array_sum($expenses);
		$totalTax =
			($tax['current_tax'] ?? 0) +
			($tax['current_tax_expenses_prior_years'] ?? 0) +
			($tax['deferred_tax'] ?? 0) +
			($tax['minimum_alternate_tax'] ?? 0);

		return round($totalRevenue - $totalExpenses - $totalTax, 2);
	}
	
	private function getPreviousDateRange($startDate, $endDate)
	{
		$start = Carbon::parse($startDate);
		$end   = Carbon::parse($endDate);

		// Shift exactly 1 year back
		$prevStart = $start->copy()->subYear();
		$prevEnd   = $end->copy()->subYear();

		return [$prevStart->toDateString(), $prevEnd->toDateString()];
	}
	
	private function getDateRange($financialYear, $periodType, $period)
    {
        [$fyStart, $fyEnd] = explode('-', $financialYear);

        $start = Carbon::create($fyStart, 4, 1);
        $end = Carbon::create($fyEnd, 3, 31);

        if ($periodType === 'monthly') {
            $start = Carbon::parse("first day of $period $fyStart");
            $end = Carbon::parse("last day of $period $fyStart");
        }

        if ($periodType === 'quarterly') {
            match ($period) {
                'april-june' => [$start, $end] = [Carbon::create($fyStart,4,1), Carbon::create($fyStart,6,30)],
                'july-september' => [$start, $end] = [Carbon::create($fyStart,7,1), Carbon::create($fyStart,9,30)],
                'october-december' => [$start, $end] = [Carbon::create($fyStart,10,1), Carbon::create($fyStart,12,31)],
                'jan-march','january-march' => [$start, $end] = [Carbon::create($fyEnd,1,1), Carbon::create($fyEnd,3,31)],
            };
        }

        if ($periodType === 'half-yearly') {
            $start = $period === 'april-september'
                ? Carbon::create($fyStart,4,1)
                : Carbon::create($fyStart,10,1);

            $end = $period === 'april-september'
                ? Carbon::create($fyStart,9,30)
                : Carbon::create($fyEnd,3,31);
        }

        return [$start->toDateString(), $end->toDateString()];
    }
	
	public function downloadPLSheetPdf(Request $request)
	{
		$userId = currentOwnerId();
		$html = $request->html; // full table HTML

		$pdf = Pdf::loadView('pl-sheet-pdf', [
			'html' => $html
		])->setPaper('A4', 'landscape');

		return $pdf->download('Profit_Loss_Sheet.pdf');
	}


}
