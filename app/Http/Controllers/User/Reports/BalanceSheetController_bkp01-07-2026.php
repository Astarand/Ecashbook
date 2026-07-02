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
use App\Services\BalanceSheetService;

class BalanceSheetController extends Controller
{
	private $expensesService;
	private $assetsService;
	private $liabilitiesService;
	private $reportsService;
	private $balanceSheetService;

    public function __construct(BalanceSheetService $balanceSheetService,ReportsService $reportsService, ExpensesService $expensesService, AssetsService $assetsService, LiabilitiesService $liabilitiesService)
    {
        $this->reportsService = $reportsService;
        $this->expensesService = $expensesService;
        $this->assetsService = $assetsService;
        $this->liabilitiesService = $liabilitiesService;
        $this->balanceSheetService = $balanceSheetService;
		$this->middleware('auth');
    }
	
	public function BalanceSheet(request $request)
    {
		$userId = currentOwnerId();

		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		checkCoreAccess('Financial Reports');
		$previousYearData = DB::table('balance_sheets')
            ->where('added_by', $userId)
            ->first();
		$hasPreviousYearData = $previousYearData ? true : false;
        return view('User.Reports.balance-sheet-report', compact(
			'hasPreviousYearData'
		));
    }
	
	function formatAssetKeys(array $data): array
	{
		$labelMap = [
			// Non Current Assets
			'property_plant_equipment' => 'Property Plant Equipment',
			'furniture_fixtures' => 'Furniture Fixtures',
			'computer_it_equipment' => 'Computer IT Equipment',
			'machinery' => 'Machinery',
			'vehicles' => 'Vehicles',
			'intangible_assets' => 'Intangible Assets',
			'capital_work_in_progress' => 'Capital Work in Progress',
			'other_non_current_assets' => 'Other Non-Current Assets',

			// Current Assets
			'cash_in_hand' => 'Cash in Hand',
			'bank_accounts' => 'Bank Accounts',
			'trade_receivables' => 'Trade Receivables',
			'advance_to_vendor' => 'Advance to Vendor',
			'employee_advance' => 'Employee Advance',
			'prepaid_expenses' => 'Prepaid Expenses',
			'input_gst_credit' => 'Input GST Credit',
			'tds_receivable' => 'TDS Receivable',
			'inventories' => 'Inventories',
		];

		$formatted = [];

		foreach ($data as $key => $value) {
			$label = $labelMap[$key] ?? ucwords(str_replace(['_', '-'], ' ', $key));
			$formatted[$label] = (float) $value;
		}

		return $formatted;
	}
	
	public function formatNonCurrentLiabilities(array $data): array
	{
		$map = [
			'long_term_borrowings' => 'Long-term Borrowings',
			'other_financial_liabilities' => 'Other Financial Liabilities',
			'deferred_tax_liabilities' => 'Deferred Tax Liabilities',
			'other_non_current_liabilities' => 'Other Non-Current Liabilities',
			'long_term_provisions' => 'Long-term Provisions',
		];

		$formatted = [];

		foreach ($map as $key => $label) {
			$formatted[$label] = isset($data[$key]) ? (float) $data[$key] : 0;
		}

		return $formatted;
	}
	
	public function fetch_balance_sheet_data(Request $request)
    {
        $financialYear = $request->input('financial_year'); // e.g., "2024-2025"
        $periodType = $request->input('period_type');       // e.g., "monthly", "quarterly", etc.
        $dynamicPeriod = $request->input('dynamic_period'); // e.g., "april", "april-june", etc.
        $userId = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}

		$propId = $request->propId ?? null;
        [$startYear, $endYear] = explode('-', $financialYear);
        $startDate = null;
        $endDate = null;

		switch ($periodType) {

			/* ---------- FULL YEAR ---------- */
			case 'full-yearly':
				$startDate = Carbon::create($startYear, 4, 1)->startOfDay();
				$endDate   = Carbon::create($endYear, 3, 31)->endOfDay();
				break;

			/* ---------- MONTHLY ---------- */
			case 'monthly':
				$monthMap = [
					'april' => 4, 'may' => 5, 'june' => 6,
					'july' => 7, 'august' => 8, 'september' => 9,
					'october' => 10, 'november' => 11, 'december' => 12,
					'january' => 1, 'february' => 2, 'march' => 3,
				];

				$month = $monthMap[$dynamicPeriod];

				// Jan–Mar belong to end year
				$year = ($month >= 4) ? $startYear : $endYear;

				$startDate = Carbon::create($year, $month, 1)->startOfMonth();
				$endDate   = Carbon::create($year, $month, 1)->endOfMonth();
				break;

			/* ---------- QUARTERLY ---------- */
			case 'quarterly':
				$quarters = [
					'april-june'        => [4, 6],
					'july-september'    => [7, 9],
					'october-december'  => [10, 12],
					'january-march'     => [1, 3],
				];

				[$startMonth, $endMonth] = $quarters[$dynamicPeriod];

				// Indian FY logic
				$startYearCalc = ($startMonth >= 4) ? $startYear : $endYear;
				$endYearCalc   = ($endMonth >= 4) ? $startYear : $endYear;

				$startDate = Carbon::create($startYearCalc, $startMonth, 1)->startOfMonth();
				$endDate   = Carbon::create($endYearCalc, $endMonth, 1)->endOfMonth();
				break;

			/* ---------- HALF YEARLY ---------- */
			case 'half-yearly':
				if ($dynamicPeriod === 'h1') { // Apr–Sep
					$startDate = Carbon::create($startYear, 4, 1)->startOfMonth();
					$endDate   = Carbon::create($startYear, 9, 30)->endOfMonth();
				} else { // h2 => Oct–Mar
					$startDate = Carbon::create($startYear, 10, 1)->startOfMonth();
					$endDate   = Carbon::create($endYear, 3, 31)->endOfMonth();
				}
				break;
		}
		$prevStartDate = $startDate->copy()->subYear();
		$prevEndDate   = $endDate->copy()->subYear();
        //Fetch previous year from balance_sheets table
		$pYearData = "";
		$cYearData = "";

        $previousYearData = DB::table('balance_sheets')
            ->where('added_by', $userId)
            ->whereBetween('endYear', [$prevStartDate,$prevEndDate])
            ->first();
		//echo "<pre>";print_r($previousYearData);exit;
		
		$equity = [];
		$nonCurrentLiabilitiesRaw = [];
		$currentLiabilities = [];
		$nonCurrAssetsRaw = [];
		$currAssetsRaw = [];
		
		if ($previousYearData) {
			$equity = json_decode($previousYearData->equity, true) ?? [];
			$nonCurrentLiabilitiesRaw = json_decode($previousYearData->non_current_liabilities, true) ?? [];
			$currentLiabilities = json_decode($previousYearData->current_liabilities, true) ?? [];
			$nonCurrAssetsRaw = json_decode($previousYearData->non_current_assets, true) ?? [];
			$currAssetsRaw = json_decode($previousYearData->current_assets, true) ?? [];
		}

		/* =========================
		   FORMAT LABELS
		========================= */
		$nonCurrAssets = $this->formatAssetKeys($nonCurrAssetsRaw);
		$currAssets = $this->formatAssetKeys($currAssetsRaw);
		$nonCurrentLiabilities = $this->formatNonCurrentLiabilities($nonCurrentLiabilitiesRaw);

		/* =========================
		   SAFE TOTALS
		========================= */
		$totalNonCurrent = array_sum(array_map('floatval', $nonCurrentLiabilities));
		$totalCurrentLiability = array_sum(array_map('floatval', $currentLiabilities));
		$totalNonCurrentAssets = array_sum(array_map('floatval', $nonCurrAssetsRaw));
		$totalCurrentAssets = array_sum(array_map('floatval', $currAssetsRaw));

		$equityLiabilityTotal =
			($equity['share_capital'] ?? 0)
			+ ($equity['reserves_surplus'] ?? 0)
			+ ($equity['current_year_profit'] ?? 0)
			+ $totalNonCurrent
			+ $totalCurrentLiability;

		/* =========================
		   FINAL STRUCTURE
		========================= */
		$prevYearData = [
			'equity' => [
				'share_capital'       => $equity['share_capital'] ?? 0,
				'reserves_surplus'    => $equity['reserves_surplus'] ?? 0,
				'current_year_profit' => $equity['current_year_profit'] ?? 0,
			],

			'nonCurrentLiabilities' => $nonCurrentLiabilities,
			'totalNonCurrent' => $totalNonCurrent,

			'currentLiabilities' => $currentLiabilities,
			'currentLiabilityTotal' => $totalCurrentLiability,

			'equityLiabilityTotal' => $equityLiabilityTotal,

			'nonCurrAssets' => $nonCurrAssets,
			'totalNonCurrentAssets' => $totalNonCurrentAssets,

			'currAssets' => $currAssets,
			'totalCurrentAssets' => $totalCurrentAssets,

			'totalAssets' => $totalNonCurrentAssets + $totalCurrentAssets,

			'grandTotal' =>
				$equityLiabilityTotal
				+ $totalNonCurrentAssets
				+ $totalCurrentAssets,
		];
				
		///current data
		if ($previousYearData) {
			$pYearData = $prevYearData;
			$cYearData = $this->fetchBalanceSheetData($propId,$userId,$startDate,$endDate);
		} else {
			$pYearData = $this->fetchBalanceSheetData($propId,$userId,$prevStartDate,$prevEndDate);
			$cYearData = $this->fetchBalanceSheetData($propId,$userId,$startDate,$endDate);
		}

        if ($prevYearData) {
            return response()->json([
                'success' => true,
                'previousYearData' => $pYearData,
                'currentYearData' => $cYearData,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        } else {
            return response()->json([
                'success' => false,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }
    }
	
	public function fetchBalanceSheetData($propId,$userId,$startDate,$endDate)
	{
		$equity = DB::table('share_holder_fund_liabilities as shfl')
				->join('liabilities as l', 'l.id', '=', 'shfl.liabilities_id')
				->whereBetween('l.added_date', [$startDate, $endDate])
				->where('l.status', 1) 
				->where('l.added_by', $userId)
				->selectRaw("
					SUM(
						CASE
							WHEN shfl.share_holder_fund_type = 'share_capital'
							THEN COALESCE(shfl.total_amount, 0)
							ELSE 0
						END
					) AS share_capital,

					SUM(
						CASE

							WHEN shfl.share_holder_fund_type = 'reserves_surplus'
								AND shfl.reserves_surplus_type = 'transfer_to_reserve'
							THEN COALESCE(shfl.transfer_amount, 0)

							WHEN shfl.share_holder_fund_type = 'reserves_surplus'
								AND shfl.reserves_surplus_type = 'opening_balance'
							THEN COALESCE(shfl.opening_balance, 0)

							WHEN shfl.share_holder_fund_type = 'reserves_surplus'
								AND shfl.reserves_surplus_type = 'dividend_declaration'
							THEN COALESCE(shfl.total_dividend_amount, 0)

							ELSE 0

						END
					) AS reserves_surplus,
					0.00 AS retained_earnings,
					0.00 AS m_r_a_share_warrants

				")->first();
				
		$current_year_profit = $this->balanceSheetService->calculatePL($startDate, $endDate, $userId);
							
		//Non-Current Liabilities
		$nonCurrLiab = DB::table('non_current_liabilities as ncl')
							->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
							->whereBetween('l.added_date', [$startDate, $endDate])
							->where('l.status', 1)
							->where('l.added_by', $userId)
							->selectRaw("
								SUM(CASE 
									WHEN ncl.liability_category = 'long_term_borrowings'
									THEN COALESCE(ncl.amount,0)
									ELSE 0 END) AS long_term_borrowings,
									
								SUM(CASE 
									WHEN ncl.liability_category = 'other_financial_liabilities'
									THEN COALESCE(ncl.amount,0)
									ELSE 0 END) AS other_financial_liabilities,

								SUM(CASE 
									WHEN ncl.liability_category = 'deferred_tax_liabilities'
									THEN COALESCE(ncl.dtl_amount,0)
									ELSE 0 END) AS deferred_tax_liabilities,

								SUM(CASE 
									WHEN ncl.liability_category = 'other_non_current_liabilities'
									THEN COALESCE(ncl.amount,0)
									ELSE 0 END) AS other_non_current_liabilities,

								SUM(CASE 
									WHEN ncl.liability_category = 'long_term_provisions'
									THEN COALESCE(ncl.amount,0)
									ELSE 0 END) AS long_term_provisions
							")
							->first();
		$nonCurrentLiabilityDetails = [
			'Long-term Borrowings' => $nonCurrLiab->long_term_borrowings ?? 0,
			'Other Financial Liabilities' => $nonCurrLiab->other_financial_liabilities ?? 0,
			'Deferred Tax Liabilities' => $nonCurrLiab->deferred_tax_liabilities ?? 0,
			'Other Non-Current Liabilities' => $nonCurrLiab->other_non_current_liabilities ?? 0,
			'Long-term Provisions' => $nonCurrLiab->long_term_provisions ?? 0,
		];

		$totalNonCurrent = array_sum($nonCurrentLiabilityDetails);
					
		//Current Liabilities	
		$liabilityTypes = [
			'trade_payables',
			'advance_from_customer',
			'outstanding_expenses',
			'salary_payable',
			'gst_payable',
			'tds_payable',
			'pf_payable',
			'esi_payable',
			'short_term_loans',
			'interest_payable',
		];

		$currLiabData = [];
		$totalCurrentLiab = 0;

		foreach ($liabilityTypes as $type) {

			$amount = $this->balanceSheetService->getCurrentLiabilityAmount(
				$type,
				$userId,
				$startDate,
				$endDate
			);

			$currLiabData[$type] = (float) $amount;

			$totalCurrentLiab += (float) $amount;
		}
		
		// =========================
		// NON-CURRENT ASSETS
		// =========================
		$nonCurrAssetRaw = DB::table('assets')
			->whereBetween('date', [$startDate, $endDate])
			->where('assetType', 'non-current')
			->where('isActive', 1)
			->where('added_by', $userId)
			->select(
				'nonCurrentAssetType',
				DB::raw("
					SUM(
						CASE 
							WHEN nonCurrentAssetType = 'Capital Work in Progress' THEN 
								CASE 
									WHEN pay_status = 'Full' THEN COALESCE(cwip_amount,0)
									ELSE COALESCE(cwip_advance_amt,0)
								END
							ELSE 
								CASE 
									WHEN pay_status = 'Full' THEN COALESCE(invoice_value,0)
									ELSE COALESCE(advance_amt,0)
								END
						END
					) as total
				")
			)
			->groupBy('nonCurrentAssetType')
			->get();

		// Convert to SAFE associative array (IMPORTANT)
		$nonCurrAssetTypes = [
			'Property Plant Equipment',
			'Furniture Fixtures',
			'Computer IT Equipment',
			'Machinery',
			'Vehicles',
			'Intangible Assets',
			'Capital Work in Progress',
			'Other Non-Current Assets'
		];

		$nonCurrAssetData = array_fill_keys($nonCurrAssetTypes, 0);

		foreach ($nonCurrAssetRaw as $row) {
			$key = $row->nonCurrentAssetType;
			if (isset($nonCurrAssetData[$key])) {
				$nonCurrAssetData[$key] = (float) $row->total;
			}
		}

		$totalNonCurrentAssets = array_sum($nonCurrAssetData);


		// =========================
		// CURRENT ASSETS
		// =========================
		$currentAssetTypes = [
			'Cash in Hand',
			'Bank Accounts',
			'Trade Receivables',
			'Advance to Vendor',
			'Employee Advance',
			'Prepaid Expenses',
			'Input GST Credit',
			'TDS Receivable',
			'Inventories'
		];

		$currAssetData = array_fill_keys($currentAssetTypes, 0);

		$totalCurrentAsset = 0;

		foreach ($currentAssetTypes as $type) {

			$amount = $this->balanceSheetService->getCurrentAssetAmount(
				$type,
				$userId,
				$startDate,
				$endDate
			);

			$currAssetData[$type] = (float) $amount;
			$totalCurrentAsset += (float) $amount;
		}


		// =========================
		// GRAND TOTAL
		// =========================
		$grandTotal =
			($equity->share_capital ?? 0)
			+ ($equity->reserves_surplus ?? 0)
			+ ($current_year_profit ?? 0)
			+ ($totalNonCurrent ?? 0)
			+ ($totalCurrentLiab ?? 0)
			+ ($totalNonCurrentAssets ?? 0)
			+ ($totalCurrentAsset ?? 0);


		// =========================
		// FINAL RESPONSE
		// =========================
		$dataArray = [
			'equity' => [
				'share_capital'       => $equity->share_capital ?? 0,
				'reserves_surplus'    => $equity->reserves_surplus ?? 0,
				'current_year_profit' => $current_year_profit ?? 0,
			],

			'nonCurrentLiabilities' => $nonCurrentLiabilityDetails,
			'totalNonCurrent' => $totalNonCurrent,

			'currentLiabilities' => $currLiabData,
			'currentLiabilityTotal' => $totalCurrentLiab,

			'equityLiabilityTotal' =>
				($equity->share_capital ?? 0)
				+ ($equity->reserves_surplus ?? 0)
				+ ($current_year_profit ?? 0)
				+ ($totalNonCurrent ?? 0)
				+ ($totalCurrentLiab ?? 0),

			// ✅ ALWAYS SAME STRUCTURE, NEVER EMPTY
			'nonCurrAssets' => $nonCurrAssetData,
			'totalNonCurrentAssets' => $totalNonCurrentAssets,

			'currAssets' => $currAssetData,
			'totalCurrentAssets' => $totalCurrentAsset,

			'totalAssets' => ($totalNonCurrentAssets + $totalCurrentAsset),
			'grandTotal' => $grandTotal
		];

		return $dataArray;
		
	}

	public function downloadBalanceSheetPdf(Request $request)
	{
		$userId = currentOwnerId();
		$html = $request->html; // full table HTML

		$pdf = Pdf::loadView('balance-sheet-pdf', [
			'html' => $html
		])->setPaper('A4', 'landscape');

		return $pdf->download('Balance_Sheet.pdf');
	}    
	
	//Start to enter previous balance-sheet
	public function addPreviousBalanceSheet() {

		$userId = currentOwnerId();
		$prevBalData = DB::table('balance_sheets')
            ->where('added_by', $userId)
            ->first();
		return view('User.Reports.previousbalance-sheet-form', compact('prevBalData'));

    }
	
	public function savePreviousBalanceSheet(Request $request)
	{
		$userId = currentOwnerId();

		DB::table('balance_sheets')
			->where('added_by', $userId)
			->delete();

		DB::transaction(function () use ($request, $userId) {

			[$startYear, $endYear] = explode('-', $request->fy);

			$startDate = date('Y-m-d', strtotime($startYear . '-04-01'));
			$endDate   = date('Y-m-d', strtotime($endYear . '-03-31'));

			/* =========================
			   EQUITY
			========================= */
			$equity = [
				'share_capital'       => (float) $request->share_capital,
				'reserves_surplus'    => (float) $request->reserves_surplus,
				'current_year_profit'   => (float) $request->current_year_profit,
			];

			/* =========================
			   NON CURRENT LIABILITIES
			========================= */
			$nonCurrentLiabilities = [
				'long_term_borrowings'       => (float) $request->long_term_borrowings,
				'other_financial_liabilities'=> (float) $request->other_financial_liabilities,
				'deferred_tax_liabilities'   => (float) $request->deferred_tax_liabilities,
				'other_non_current_liabilities'=> (float) $request->other_non_current_liabilities,
				'long_term_provisions'       => (float) $request->long_term_provisions,
			];

			/* =========================
			   CURRENT LIABILITIES
			========================= */
			$currentLiabilities = [
				'trade_payables'        => (float) $request->trade_payables,
				'advance_from_customer' => (float) $request->advance_from_customer,
				'outstanding_expenses'  => (float) $request->outstanding_expenses,
				'salary_payable'        => (float) $request->salary_payable,
				'gst_payable'           => (float) $request->gst_payable,
				'tds_payable'           => (float) $request->tds_payable,
				'pf_payable'            => (float) $request->pf_payable,
				'esi_payable'           => (float) $request->esi_payable,
				'short_term_loans'      => (float) $request->short_term_loans,
				'interest_payable'      => (float) $request->interest_payable,
			];

			/* =========================
			   NON CURRENT ASSETS
			========================= */
			$nonCurrentAssets = [
				'property_plant_equipment'                       => (float) $request->property_plant_equipment,
				'furniture_fixtures'        => (float) $request->furniture_fixtures,
				'computer_it_equipment'     => (float) $request->computer_it_equipment,
				'machinery'                 => (float) $request->machinery,
				'vehicles'                  => (float) $request->vehicles,
				'intangible_assets'         => (float) $request->intangible_assets,
				'capital_work_in_progress'  => (float) $request->capital_work_in_progress,
				'other_non_current_assets'  => (float) $request->other_non_current_assets,
			];

			/* =========================
			   CURRENT ASSETS
			========================= */
			$currentAssets = [
				'cash_in_hand'                => (float) $request->cash_in_hand,
				'bank_accounts'              => (float) $request->bank_accounts,
				'trade_receivables'         => (float) $request->trade_receivables,
				'advance_to_vendor'         => (float) $request->advance_to_vendor,
				'employee_advance'          => (float) $request->employee_advance,
				'prepaid_expenses'          => (float) $request->prepaid_expenses,
				'input_gst_credit'          => (float) $request->input_gst_credit,
				'tds_receivable'            => (float) $request->tds_receivable,
				'inventories'               => (float) $request->inventories,
			];

			/* =========================
			   TOTALS (you calculate here)
			========================= */
			$totals = [
				'equity_total' => array_sum($equity),
				'non_current_liabilities_total' => array_sum($nonCurrentLiabilities),
				'current_liabilities_total' => array_sum($currentLiabilities),
				'non_current_assets_total' => array_sum($nonCurrentAssets),
				'current_assets_total' => array_sum($currentAssets),
			];

			$totals['equity_liabilities_total'] =
				$totals['equity_total'] +
				$totals['non_current_liabilities_total'] +
				$totals['current_liabilities_total'];

			$totals['assets_total'] =
				$totals['non_current_assets_total'] +
				$totals['current_assets_total'];

			$totals['grand_total'] =
				$totals['equity_liabilities_total'] + $totals['assets_total'];

			/* =========================
			   FINAL INSERT
			========================= */
			DB::table('balance_sheets')->insert([
				'added_by' => $userId,
				'fy' => $request->fy,
				'startYear' => $startDate,
				'endYear' => $endDate,

				'equity' => json_encode($equity),
				'non_current_liabilities' => json_encode($nonCurrentLiabilities),
				'current_liabilities' => json_encode($currentLiabilities),
				'non_current_assets' => json_encode($nonCurrentAssets),
				'current_assets' => json_encode($currentAssets),
				'totals' => json_encode($totals),

				'created_at' => now(),
				'updated_at' => now(),
			]);
		});

		return redirect()
            ->back()
            ->withInput()                      
            ->with([
                'success' => 'Balance Sheet data saved successfully.',
                'lock'    => true,              
            ]);
	}	
	//End to enter previous balance-sheet
	

    


}
