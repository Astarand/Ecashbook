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
		checkCoreAccess('Financial Reports');
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
						->selectRaw('SUM(COALESCE(pv.amount,0) + COALESCE(pv.tax_amt,0)) as total_purchase')
						->value('total_purchase');

		$purchases = $purchases ?? 0;
		/*
		|--------------------------------------------------------------------------
		| Trade Payable
		|--------------------------------------------------------------------------
		*/					
		$tradePayable = DB::table('purchases as p')
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
		$cogsTotal = ($openingStock + ($purchases - $tradePayable) + $directExpenseTotal - $closingStock);
		$cogs = [
			'opening_stock'  => $openingStock,
			'purchases'      => $purchases,
			'trade_payable'  => $tradePayable,
			'direct_expense' => $directExpenseDetails,
			'directExpenseTotal'=> $directExpenseTotal,
			'closing_stock'  => $closingStock,
			'total_cogs'     => $cogsTotal,
		];

		/* ================================
		 | EXPENSES
		 ================================*/			
		//Indirect Expense Category
		$allowedExpenseTypes = [
				'employee_benefits',
				'rent_expense',
				'electricity_expense',
				'internet_communication',
				'office_expenses',
				'printing_stationery',
				'travel_conveyance',
				'repair_maintenance',
				'professional_fees',
				'audit_fees',
				'legal_charges',
				'bank_charges',
				'interest_expense',
				'depreciation',
				'insurance_expense',
				'marketing_advertisement',
				'miscellaneous_expenses',

				'income_tax_paid',
				'gst_interest_penalty',
				'late_filing_penalty',
				'personal_expenses',
				'cash_payment_above_income_tax_limit',
				'donation_non_approved',
				'provision_for_expenses',
				'provision_for_doubtful_debts',
				'penalty_for_law_violation',
				'wealth_tax_personal_tax',
				'capital_loss',
				'drawings_owner_withdrawals',
				'csr_expense(certain_cases)',
				'unpaid_pf_esi_beyond_due_date',
				'tds_not_deducted_deposited',
				'expenses_without_proper_bills',

				'interest_on_business_loan',
				'software_subscription',
				'hosting_cloud_expense',
				'motor_car_expense',
				'entertainment_expense',
				'director_expense',
			];
			
		//Indirect Expense Labels
		$expenseLabels = [

			// Employee
			'employee_benefits'   => 'Employee Expenses (Salary, Benefits)',
			'Employee Expenses'   => 'Employee Expenses (Salary, Benefits)',

			// Rent
			'rent_expense'        => 'Rent Expense',
			'Rent Expense'        => 'Rent Expense',

			// Electricity
			'electricity_expense' => 'Electricity Expense',
			'Electricity Expense' => 'Electricity Expense',

			// Internet
			'internet_communication'      => 'Internet & Communication',
			'Internet & Communication'    => 'Internet & Communication',

			// Office
			'office_expenses'    => 'Office Expenses',
			'Office Expenses'    => 'Office Expenses',

			// Printing
			'printing_stationery'      => 'Printing & Stationery',
			'Printing & Stationery'    => 'Printing & Stationery',

			// Travel
			'travel_conveyance'      => 'Travel & Conveyance',
			'Travel & Conveyance'    => 'Travel & Conveyance',

			// Repair
			'repair_maintenance'      => 'Repair & Maintenance',
			'Repair & Maintenance'    => 'Repair & Maintenance',

			// Professional
			'professional_fees'   => 'Professional Fees',
			'Professional Fees'   => 'Professional Fees',

			// Audit
			'audit_fees'          => 'Audit Fees',
			'Audit Fees'          => 'Audit Fees',

			// Legal
			'legal_charges'       => 'Legal Charges',
			'Legal Charges'       => 'Legal Charges',

			// Bank
			'bank_charges'        => 'Bank Charges',
			'Bank Charges'        => 'Bank Charges',

			// Interest
			'interest_expense'    => 'Interest Expense',
			'Interest Expense'    => 'Interest Expense',

			// Depreciation
			'depreciation'        => 'Depreciation',
			'Depreciation'        => 'Depreciation',

			// Insurance
			'insurance_expense'   => 'Insurance Expense',
			'Insurance Expense'   => 'Insurance Expense',

			// Marketing
			'marketing_advertisement' => 'Marketing & Advertisement',
			'Marketing & Advertisement' => 'Marketing & Advertisement',

			// Freight
			'Freight & Transport' => 'Freight & Transport',

			// Misc
			'miscellaneous_expenses' => 'Miscellaneous Expenses',
			'Miscellaneous Expenses' => 'Miscellaneous Expenses',

			'income_tax_paid'                  => 'Income Tax Paid',
			'gst_interest_penalty'             => 'GST Interest & Penalty',
			'late_filing_penalty'              => 'Late Filing Penalty',
			'personal_expenses'                => 'Personal Expenses',
			'cash_payment_above_income_tax_limit' => 'Cash Payment above Income Tax limit',
			'donation_non_approved'            => 'Donation (Non-approved)',
			'provision_for_expenses'           => 'Provision for Expenses',
			'provision_for_doubtful_debts'     => 'Provision for Doubtful Debts',
			'penalty_for_law_violation'        => 'Penalty for Law Violation',
			'wealth_tax_personal_tax'          => 'Wealth Tax / Personal Tax',
			'capital_loss'                     => 'Capital Loss',
			'drawings_owner_withdrawals'       => 'Drawings / Owner Withdrawals',
			'csr_expense(certain_cases)'       => 'CSR Expense (certain cases)',
			'unpaid_pf_esi_beyond_due_date'    => 'Unpaid PF/ESI beyond due date',
			'tds_not_deducted_deposited'       => 'TDS not deducted / deposited',
			'expenses_without_proper_bills'    => 'Expenses without proper bills',
			'interest_on_business_loan'        => 'Interest on Business Loan',
			'software_subscription'            => 'Software Subscription',
			'hosting_cloud_expense'            => 'Hosting / Cloud Expense',
			'motor_car_expense'                => 'Motor Car Expense',
			'entertainment_expense'            => 'Entertainment Expense',
			'director_expense'                 => 'Director Expense',
		];

		$indirectExpenses = DB::table('expenses')
			->whereBetween('expense_date', [$startDate, $endDate])
			->where('added_by', $userId)
			->where('status', 1)
			->where('expense_cat', 'indirect')
			//->whereIn('expense_type', $allowedExpenseTypes)
			->whereIn('expense_type', array_keys($expenseLabels))
			->select(
				'expense_type',
				DB::raw("
					SUM(
						CASE
							WHEN payment_status = 'full'
							THEN COALESCE(expense_amt,0)
							ELSE COALESCE(advance_amount,0)
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
							
		//$expenseArray = $indirectExpenses->pluck('total', 'expense_type')->toArray();
		//$totalIndirectExpenses = array_sum($expenseArray);
		/*$expenseArray = [];
		foreach($indirectExpenses as $expense)
		{
			$label = $expenseLabels[$expense->expense_type] ?? $expense->expense_type;

			$expenseArray[$label] = $expense->total;
		}*/
		$expenseArray = [];

		foreach ($indirectExpenses as $expense) {

			$head = $expenseLabels[$expense->expense_type] ?? $expense->expense_type;

			$expenseArray[$head] = ($expenseArray[$head] ?? 0) + $expense->total;
		}

		ksort($expenseArray);
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
		//Tax Expense
		$current_tax = $this->profitLossService->getCurrentTax($userId, $startDate, $endDate);
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
			'expenses' => $expenseArray,
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
