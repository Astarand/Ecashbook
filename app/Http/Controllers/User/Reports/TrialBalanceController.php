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
use App\Services\BalanceSheetService;
use App\Services\ProfitLossService;
use App\Services\TrialBalanceService;

class TrialBalanceController extends Controller
{

	private $balanceSheetService;
	private $profitLossService;
	private $trialBalanceService;

    public function __construct(TrialBalanceService $trialBalanceService, BalanceSheetService $balanceSheetService,ProfitLossService $profitLossService)
    {
        $this->trialBalanceService = $trialBalanceService;
        $this->balanceSheetService = $balanceSheetService;
		$this->profitLossService = $profitLossService;
		$this->middleware('auth');
    }
	
	public function TrialBalance(request $request)
    {
		$userId = currentOwnerId();
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access
		$propId = null;
		checkCoreAccess('Trial Balance');
		$currentDate = Carbon::now()->toDateString(); // YYYY-MM-DD		
		$ledger = "";
		/*$opening = $this->getOpeningBalanceFromJournal($ledger, $userId, $currentDate, $propId);
		//echo "<pre>";print_r($opening);exit;
		$openingDr = $opening['dr'];
		$openingCr = $opening['cr'];
		if($openingDr == 0 && $openingCr == 0){
			$opening = $this->getOpeningBalanceCreditDebit($userId);
			$openingDr = $opening['opening_dr'];
			$openingCr = $opening['opening_cr'];
		}*/
		//echo "<pre>";print_r($opening);exit;
			
		// Ledger names from journals
		$ledgerList = collect();
		$ledgerList = $ledgerList->merge(
			DB::table('journals')
				->where('added_by', $userId)
				->whereNotNull('ledger')
				->where('ledger', '!=', '')
				->distinct()
				->pluck('ledger')
		);
		// Customer names
		$ledgerList = $ledgerList->merge(
			DB::table('customers')
				->where('userId', $userId)
				->whereNotNull('cust_name')
				->where('cust_name', '!=', '')
				->pluck('cust_name')
		);
		// Vendor names
		$ledgerList = $ledgerList->merge(
			DB::table('vendors')
				->where('userId', $userId)
				->whereNotNull('vendor_name')
				->where('vendor_name', '!=', '')
				->pluck('vendor_name')
		);
		// Remove duplicates and sort
		$ledgers = $ledgerList
			->map(fn($name) => trim($name))
			->filter()
			->unique()
			->sort()
			->values();
			
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		return view('User.Reports.trial-balance')->with([
				//'openingDr' => $openingDr,
				//'openingCr' => $openingCr,
				'proprietorships' => $proprietorships,
				'ledgers' => $ledgers,
				'req_type' => $req_type
			]);
    }
	
	public function fatch_trial_balance_data(Request $r)
	{
		$userId = currentOwnerId();
		$req_type = 0;
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
			$req_type = 1;
		}
		$propId = $r->propId ?? null;
		$fromDate   = $r->from_date;
		$toDate     = $r->to_date;

		$ledgerFilter     = $r->ledger_name;
		$ledgerGroup      = $r->ledger_group;
		$trial = [];
		
		//Asset Head
		$assetHeads = [
			'Current Assets' => [
				'Cash in Hand',
				'Bank Accounts',
				'Trade Receivables',
				'Advance to Vendor',
				'Employee Advance',
				'Prepaid Expenses',
				'Input GST Credit',
				'TDS Receivable',
				'Inventories',
			],

			'Non-Current Assets' => [
				'Fixed Assets',
				'CWIP',
				'Investments',
				'Other Non-Current Assets',
			],
		];
		
		foreach ($assetHeads as $group => $heads) {

			foreach ($heads as $ledger) {

				if ($group == 'Current Assets') {

					$amount = $this->trialBalanceService->getCurrentAssetAmount(
						$ledger,
						$userId,
						$fromDate,
						$toDate
					);

				} else {

					$amount = $this->trialBalanceService->getNonCurrentAssetAmount(
						$ledger,
						$userId,
						$fromDate,
						$toDate
					);
				}

				$trial['Assets'][$group][$ledger] = [

					'ledgername' => $ledger,

					'opening_dr' => 0,
					'opening_cr' => 0,

					'debit' => $amount,
					'credit' => 0,

					'closing_dr' => $amount,
					'closing_cr' => 0,
				];
			}
		}
		//Equity head
		$periodType = 'full-yearly';
		$current_year_profit = $this->profitLossService->calculatePL($fromDate, $toDate, $userId, $periodType)['pbt'] ?? 0; 
		$equityHeads = [
			'share_capital'        => 'Share Capital',
			'reserves_surplus'     => 'Reserves & Surplus',
			'current_year_profit'    => 'Current Year Profit',
		];

		foreach ($equityHeads as $type => $ledger) {

			if ($type == 'current_year_profit') {
				$amount = $current_year_profit;
			} else {
				$amount = $this->trialBalanceService->getEquityAmount(
					$type,
					$userId,
					$fromDate,
					$toDate
				);
			}

			$trial['Equity'][''][$ledger] = [
				'ledgername'  => $ledger,
				'opening_dr'  => 0,
				'opening_cr'  => 0,
				'debit'       => 0,
				'credit'      => $amount,
				'closing_dr'  => 0,
				'closing_cr'  => $amount,
			];
		}
		
		//Current Liabilities
		$liabilityTypes = [
			'trade_payables',
			'advance_from_customer',
			'salary_payable',
			'gst_payable',
			'output_gst',
			'tds_payable',
			'pf_payable',
			'esi_payable',
			'ptax_payable',
			'lwf_payable',
			'short_term_loans',
			'interest_payable'
		];

		foreach ($liabilityTypes as $type){

			$amount = $this->trialBalanceService->getCurrentLiabilityAmount(
				$type,
				$userId,
				$fromDate,
				$toDate
			);

			$trial['Liabilities']['Current Liabilities'][ucwords(str_replace('_',' ',$type))] = [

				'ledger'      => ucwords(str_replace('_',' ',$type)),
				'opening_dr'  => 0,
				'opening_cr'  => 0,
				'debit'       => 0,
				'credit'      => $amount,
				'closing_dr'  => 0,
				'closing_cr'  => $amount
			];
		}
		
		//Non-Current Liabilities
		$nonCurrentLiabilityHeads = [
			'long_term_borrowings'         => 'Long-term Borrowings',
			'other_financial_liabilities'  => 'Other Financial Liabilities',
			'deferred_tax_liabilities'     => 'Deferred Tax Liabilities',
			'other_non_current_liabilities'=> 'Other Non-Current Liabilities',
			'long_term_provisions'         => 'Long-term Provisions',
		];

		foreach ($nonCurrentLiabilityHeads as $type => $ledger) {

			$amount = $this->trialBalanceService->getNonCurrentLiabilityAmount(
				$type,
				$userId,
				$fromDate,
				$toDate
			);

			$trial['Liabilities']['Non-Current Liabilities'][$ledger] = [
				'ledgername' => $ledger,
				'opening_dr' => 0,
				'opening_cr' => 0,
				'debit'      => 0,
				'credit'     => $amount,
				'closing_dr' => 0,
				'closing_cr' => $amount,
			];
		}
		//Income
		$sales = DB::table('sales_values as sv')
						->join('sales as s', 's.id', '=', 'sv.sid')
						->where('s.added_by', $userId)
						->where('s.status', 1)
						->whereBetween('s.inv_date', [$fromDate, $toDate])
						->sum('sv.amount');

		$salesCreditNote = DB::table('vouchers as v')
						->where('v.added_by', $userId)
						->where('v.note_type', 'Credit')
						->whereBetween('v.inv_date', [$fromDate, $toDate])
						->sum('v.taxable_value');

		$revenueFromOperations = $sales - $salesCreditNote;
		$otherIncome = DB::table('income')
						->where('addBy', $userId)
						->where('status',1)
						->whereBetween('dateInput', [$fromDate, $toDate])
						->sum('amount');
		$trial['Income']['Income']['Revenue from Operations'] = [
			'ledger'      => 'Revenue from Operations',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => 0,
			'credit'      => $revenueFromOperations,
		];

		$trial['Income']['Income']['Other Income'] = [
			'ledger'      => 'Other Income',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => 0,
			'credit'      => $otherIncome,
		];
		//Expenses
		$purchase = DB::table('purchase_values as pv')
			->join('purchases as p','p.id','=','pv.sid')
			->where('p.added_by',$userId)
			->where('p.status',1)
			->whereBetween('p.inv_date',[$fromDate,$toDate])
			->sum('pv.amount');

		$purchaseDebitNote = DB::table('voucher_purchases as vp')
			->where('vp.added_by',$userId)
			->where('vp.note_type','Debit')
			->whereBetween('vp.inv_date',[$fromDate,$toDate])
			->sum('vp.taxable_value');

		$costOfGoodsSold = $purchase - $purchaseDebitNote;
		
		//(DIRECT EXPENSES)
		$directExpenses = DB::table('expenses')
			->select('expense_type', DB::raw('SUM(expense_amt) as amount'))
			->where('added_by', $userId)
			->whereBetween('expense_date', [$fromDate, $toDate])
			->where('expense_cat', 'direct')
			->groupBy('expense_type')
			->get();

		foreach ($directExpenses as $exp) {

			$trial['Expenses']['Direct Expenses'][$exp->expense_type] = [
				'ledger'      => ucwords(str_replace('_', ' ', $exp->expense_type)),
				'opening_dr'  => 0,
				'opening_cr'  => 0,
				'debit'       => $exp->amount,
				'credit'      => 0,
			];
		}
			
		//(INDIRECT EXPENSES) 
		//Employee Benefit Expenses
		$employeeBenefit = DB::table('expenses')
				->where('added_by', $userId)
				->whereBetween('expense_date', [$fromDate, $toDate])
				->where('expense_type', 'employee_benefits')
				->sum('expense_amt');
		//Administrative Expenses
		$adminTypes = [
			'rent_expense',
			'electricity_expense',
			'internet_communication',
			'office_expenses',
			'printing_stationery',
			'travel_conveyance',
			'repair_maintenance',
			'professional_fees'
		];

		$administrativeExpense = DB::table('expenses')
			->where('added_by', $userId)
			->whereBetween('expense_date', [$fromDate, $toDate])
			->whereIn('expense_type', $adminTypes)
			->sum('expense_amt');
		//Finance Cost
		$financeTypes = [
			'interest_expense',
			'bank_charges',
			'loan_interest'
		];

		$financeCost = DB::table('expenses')
			->where('added_by', $userId)
			->whereBetween('expense_date', [$fromDate, $toDate])
			->whereIn('expense_type', $financeTypes)
			->sum('expense_amt');
		//Selling Expenses
		$sellingTypes = [
			'advertisement',
			'sales_commission',
			'marketing_expense',
			'freight_outward'
		];
		// Depreciation
		$depreciation = DB::table('assets')
			->where('added_by', $userId)
			->whereBetween('date', [$fromDate, $toDate])
			->sum('depreciation_value');
		//Other Expenses
		$usedTypes = array_merge(
			['employee_benefits'],
			$adminTypes,
			$financeTypes,
			$sellingTypes
		);

		$otherExpense = DB::table('expenses')
			->where('added_by', $userId)
			->whereBetween('expense_date', [$fromDate, $toDate])
			->whereNotIn('expense_type', $usedTypes)
			->where('expense_cat', 'indirect')
			->sum('expense_amt');

		$sellingExpense = DB::table('expenses')
			->where('added_by', $userId)
			->whereBetween('expense_date', [$fromDate, $toDate])
			->whereIn('expense_type', $sellingTypes)
			->sum('expense_amt');
			
		$trial['Expenses']['Expenses']['Cost of Goods Sold'] = [
			'ledger'      => 'Cost of Goods Sold',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $costOfGoodsSold,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Employee Benefit Expenses'] = [
			'ledger'      => 'Employee Benefit Expenses',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $employeeBenefit,
			'credit'      => 0,
		];
		$trial['Expenses']['Expenses']['Finance Cost'] = [
			'ledger'      => 'Finance Cost',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $financeCost,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Depreciation'] = [
			'ledger'      => 'Depreciation',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $depreciation,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Administrative Expenses'] = [
			'ledger'      => 'Administrative Expenses',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $administrativeExpense,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Selling Expenses'] = [
			'ledger'      => 'Selling Expenses',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $sellingExpense,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Other Expenses'] = [
			'ledger'      => 'Other Expenses',
			'opening_dr'  => 0,
			'opening_cr'  => 0,
			'debit'       => $otherExpense,
			'credit'      => 0,
		];
		
		
		// Apply Ledger Group and Ledger Name filters
		if (!empty($ledgerGroup) || !empty($ledgerFilter)) {

			foreach ($trial as $mainGroup => &$subGroups) {

				// Ledger Group filter
				if (!empty($ledgerGroup) && $mainGroup !== $ledgerGroup) {
					unset($trial[$mainGroup]);
					continue;
				}

				foreach ($subGroups as $subGroup => &$ledgers) {

					// Ledger Name filter
					if (!empty($ledgerFilter)) {

						$ledgers = array_filter($ledgers, function ($row) use ($ledgerFilter) {

							$ledgerName = $row['ledgername'] ?? $row['ledger'] ?? '';

							return stripos($ledgerName, $ledgerFilter) !== false;
						});
					}

					// Remove empty sub-groups
					if (empty($ledgers)) {
						unset($subGroups[$subGroup]);
					}
				}

				// Remove empty main groups
				if (empty($subGroups)) {
					unset($trial[$mainGroup]);
				}
			}

			unset($subGroups, $ledgers);
		}
		
		//Calculate closing
		$totalDr = 0;
		$totalCr = 0;

		foreach ($trial as &$groups) {

			foreach ($groups as &$ledgers) {

				foreach ($ledgers as &$row) {

					$dr = $row['opening_dr'] + $row['debit'];
					$cr = $row['opening_cr'] + $row['credit'];

					if ($dr >= $cr) {

						$row['closing_dr'] = round($dr - $cr, 2);
						$row['closing_cr'] = 0;

					} else {

						$row['closing_dr'] = 0;
						$row['closing_cr'] = round($cr - $dr, 2);
					}

					$totalDr += $row['closing_dr'];
					$totalCr += $row['closing_cr'];
				}
			}
		}
		
		return response()->json([
			'success'    => true,
			'trial'      => $trial,
			'total_dr'   => round($totalDr, 2),
			'total_cr'   => round($totalCr, 2),
			'difference' => round(abs($totalDr - $totalCr), 2),
			'diff_dc'    => $totalDr >= $totalCr ? 'Dr' : 'Cr',
		]);
		
	}
	
	public function downloadTrialBalanceSheetPdf(Request $request)
	{
		$userId = currentOwnerId();
		$html = $request->html; // full table HTML

		$pdf = Pdf::loadView('trial-balance-sheet-pdf', [
			'html' => $html
		])->setPaper('A4', 'landscape');

		return $pdf->download('Trial_Balance_Sheet.pdf');
	}
    
	
	public function getOpeningBalanceCreditDebit($userId)
	{
		$balance = DB::table('company_profiles')
				->where('userId', $userId)
				->select('openingbalancedr', 'openingbalancecr')
				->first();

		return [
			'opening_dr' => $balance->openingbalancedr ?? 0,
			'opening_cr' => $balance->openingbalancecr ?? 0,
		];
	}


}
