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
			$userId = session('compId');
			$req_type = 1;
		}

		$propId       = $r->propId ?? null;
		$ledgerFilter = $r->ledger_name ?? null;
		$ledgerGroup  = $r->ledger_group ?? null;

		/*
		|--------------------------------------------------------------------------
		| DATE PARSER
		|--------------------------------------------------------------------------
		*/

		$parseDate = function ($date) {

			if (empty($date)) {
				return null;
			}

			$date = trim($date);

			$formats = [
				'd-m-Y',
				'd/m/Y',
				'Y-m-d',
			];

			foreach ($formats as $format) {

				try {

					$dt = \DateTime::createFromFormat($format, $date);

					if ($dt && $dt->format($format) === $date) {
						return $dt->format('Y-m-d');
					}

				} catch (\Exception $e) {
					// Continue
				}
			}

			$timestamp = strtotime($date);

			return $timestamp
				? date('Y-m-d', $timestamp)
				: null;
		};

		$fromDate = $parseDate($r->from_date);
		$toDate   = $parseDate($r->to_date);

		if (!$fromDate || !$toDate) {

			return response()->json([
				'success' => false,
				'message' => 'Invalid From Date or To Date.'
			], 422);
		}

		if ($fromDate > $toDate) {

			return response()->json([
				'success' => false,
				'message' => 'From Date cannot be greater than To Date.'
			], 422);
		}


		/*
		|--------------------------------------------------------------------------
		| SELECTED FINANCIAL YEAR
		|--------------------------------------------------------------------------
		*/

		$fromYear  = (int) date('Y', strtotime($fromDate));
		$fromMonth = (int) date('m', strtotime($fromDate));

		if ($fromMonth >= 4) {

			$financialYearStart =
				$fromYear . '-04-01';

			$financialYearEnd =
				($fromYear + 1) . '-03-31';

			$financialYear =
				$fromYear . '-' . ($fromYear + 1);

		} else {

			$financialYearStart =
				($fromYear - 1) . '-04-01';

			$financialYearEnd =
				$fromYear . '-03-31';

			$financialYear =
				($fromYear - 1) . '-' . $fromYear;
		}


		/*
		|--------------------------------------------------------------------------
		| PREVIOUS DATE
		|--------------------------------------------------------------------------
		*/

		$prevToDate = date(
			'Y-m-d',
			strtotime($fromDate . ' -1 day')
		);


		/*
		|--------------------------------------------------------------------------
		| PREVIOUS FINANCIAL YEAR
		|--------------------------------------------------------------------------
		*/

		$prevFromDate = date(
			'Y-m-d',
			strtotime($financialYearStart . ' -1 year')
		);

		$prevToDateFY = date(
			'Y-m-d',
			strtotime($financialYearStart . ' -1 day')
		);


		/*
		|--------------------------------------------------------------------------
		| FIND INITIAL BALANCE SHEET
		|--------------------------------------------------------------------------
		|
		| IMPORTANT:
		|
		| balance_sheets may contain ONLY ONE initial entry.
		|
		| We DO NOT require one row for every financial year.
		|
		| The first/initial Balance Sheet is used as BASE BALANCE.
		|
		|--------------------------------------------------------------------------
		*/

		$openingBalanceSheet = DB::table('balance_sheets')
			->where('added_by', $userId)
			->where(function ($q) use ($fromDate) {

				$q->where(function ($q2) use ($fromDate) {

					$q2->whereNotNull('startYear')
						->whereDate(
							'startYear',
							'<=',
							$fromDate
						);

				})->orWhere(function ($q2) use ($fromDate) {

					$q2->whereNotNull('endYear')
						->whereDate(
							'endYear',
							'<',
							$fromDate
						);

				});

			})
			->orderByDesc('startYear')
			->orderByDesc('endYear')
			->first();


		/*
		|--------------------------------------------------------------------------
		| FALLBACK
		|--------------------------------------------------------------------------
		|
		| If no matching snapshot is found, use the oldest/initial snapshot.
		|
		*/

		if (!$openingBalanceSheet) {

			$openingBalanceSheet = DB::table('balance_sheets')
				->where('added_by', $userId)
				->orderBy('startYear')
				->orderBy('endYear')
				->first();
		}


		/*
		|--------------------------------------------------------------------------
		| BASE BALANCE SHEET DATA
		|--------------------------------------------------------------------------
		*/

		$baseEquity = [];
		$baseCurrentLiabilities = [];
		$baseNonCurrentLiabilities = [];
		$baseCurrentAssets = [];
		$baseNonCurrentAssets = [];

		if ($openingBalanceSheet) {

			$baseEquity = !empty($openingBalanceSheet->equity)
				? json_decode(
					$openingBalanceSheet->equity,
					true
				)
				: [];

			$baseCurrentLiabilities = !empty(
				$openingBalanceSheet->current_liabilities
			)
				? json_decode(
					$openingBalanceSheet->current_liabilities,
					true
				)
				: [];

			$baseNonCurrentLiabilities = !empty(
				$openingBalanceSheet->non_current_liabilities
			)
				? json_decode(
					$openingBalanceSheet->non_current_liabilities,
					true
				)
				: [];

			$baseCurrentAssets = !empty(
				$openingBalanceSheet->current_assets
			)
				? json_decode(
					$openingBalanceSheet->current_assets,
					true
				)
				: [];

			$baseNonCurrentAssets = !empty(
				$openingBalanceSheet->non_current_assets
			)
				? json_decode(
					$openingBalanceSheet->non_current_assets,
					true
				)
				: [];
		}


		/*
		|--------------------------------------------------------------------------
		| BASE BALANCE DATE
		|--------------------------------------------------------------------------
		|
		| The balance sheet is treated as the initial/base position.
		|
		| If endYear exists:
		| historical movement starts from endYear + 1 day.
		|
		| Otherwise:
		| historical movement starts from 1900-01-01.
		|
		|--------------------------------------------------------------------------
		*/

		$baseBalanceDate = null;

		if ($openingBalanceSheet) {

			if (!empty($openingBalanceSheet->endYear)) {

				$baseBalanceDate = $parseDate(
					$openingBalanceSheet->endYear
				);

			} elseif (!empty($openingBalanceSheet->startYear)) {

				$baseBalanceDate = $parseDate(
					$openingBalanceSheet->startYear
				);
			}
		}


		/*
		|--------------------------------------------------------------------------
		| HISTORICAL MOVEMENT START DATE
		|--------------------------------------------------------------------------
		*/

		if ($baseBalanceDate) {

			$historicalFromDate = date(
				'Y-m-d',
				strtotime(
					$baseBalanceDate . ' +1 day'
				)
			);

		} else {

			/*
			 * No Balance Sheet exists.
			 *
			 * Start from a sufficiently early date so previous
			 * transactions are carried forward.
			 */
			$historicalFromDate = '1900-01-01';
		}


		/*
		|--------------------------------------------------------------------------
		| HELPER - JSON VALUE
		|--------------------------------------------------------------------------
		*/

		$getValue = function ($data, $key) {

			if (!is_array($data)) {
				return 0;
			}

			return isset($data[$key])
				? (float) $data[$key]
				: 0;
		};


		/*
		|--------------------------------------------------------------------------
		| HELPER - DISPLAY NAME
		|--------------------------------------------------------------------------
		*/

		$displayName = function ($key) {

			return ucwords(
				str_replace(
					'_',
					' ',
					$key
				)
			);
		};


		/*
		|--------------------------------------------------------------------------
		| TRIAL ARRAY
		|--------------------------------------------------------------------------
		*/

		$trial = [];


		/*
		|--------------------------------------------------------------------------
		| ASSET HEADS
		|--------------------------------------------------------------------------
		*/

		$currentAssetHeads = [

			'Current Assets' => [

				'cash_in_hand' =>
					'Cash in Hand',

				'bank_accounts' =>
					'Bank Accounts',

				'trade_receivables' =>
					'Trade Receivables',

				'advance_to_vendor' =>
					'Advance to Vendor',

				'employee_advance' =>
					'Employee Advance',

				'prepaid_expenses' =>
					'Prepaid Expenses',

				'input_gst_credit' =>
					'Input GST Credit',

				'tds_receivable' =>
					'TDS Receivable',

				'inventories' =>
					'Inventories',
			],

			'Non-Current Assets' => [

				'property_plant_equipment' =>
					'Fixed Assets',

				'capital_work_in_progress' =>
					'CWIP',

				'investments' =>
					'Investments',

				'other_non_current_assets' =>
					'Other Non-Current Assets',
			],
		];


		/*
		|--------------------------------------------------------------------------
		| EXTRA ASSET HEADS
		|--------------------------------------------------------------------------
		*/

		$extraNonCurrentAssets = [

			'furniture_fixtures' =>
				'Furniture & Fixtures',

			'computer_it_equipment' =>
				'Computer & IT Equipment',

			'machinery' =>
				'Machinery',

			'vehicles' =>
				'Vehicles',

			'intangible_assets' =>
				'Intangible Assets',
		];


		foreach ($extraNonCurrentAssets as $key => $name) {

			if (
				array_key_exists(
					$key,
					$baseNonCurrentAssets
				)
				&& !isset(
					$currentAssetHeads[
						'Non-Current Assets'
					][$key]
				)
			) {

				$currentAssetHeads[
					'Non-Current Assets'
				][$key] = $name;
			}
		}


		foreach ($baseCurrentAssets as $key => $value) {

			if (
				!isset(
					$currentAssetHeads[
						'Current Assets'
					][$key]
				)
			) {

				$currentAssetHeads[
					'Current Assets'
				][$key] =
					$displayName($key);
			}
		}


		foreach ($baseNonCurrentAssets as $key => $value) {

			if (
				!isset(
					$currentAssetHeads[
						'Non-Current Assets'
					][$key]
				)
			) {

				$currentAssetHeads[
					'Non-Current Assets'
				][$key] =
					$displayName($key);
			}
		}


		/*
		|--------------------------------------------------------------------------
		| ASSETS
		|--------------------------------------------------------------------------
		|
		| Opening =
		| BASE BALANCE
		| +
		| ALL MOVEMENT FROM BASE DATE TO DAY BEFORE FROM DATE
		|
		|--------------------------------------------------------------------------
		*/

		foreach ($currentAssetHeads as $group => $heads) {

			foreach ($heads as $openingKey => $ledger) {

				$baseOpening = 0;

				if ($group === 'Current Assets') {

					$baseOpening = $getValue(
						$baseCurrentAssets,
						$openingKey
					);

				} else {

					$baseOpening = $getValue(
						$baseNonCurrentAssets,
						$openingKey
					);
				}


				/*
				 * Historical movement
				 */
				$historicalMovement = 0;

				if (
					$historicalFromDate <=
					$prevToDate
				) {

					if ($group === 'Current Assets') {

						$historicalMovement =
							(float)
							$this->trialBalanceService
								->getCurrentAssetAmount(
									$ledger,
									$userId,
									$historicalFromDate,
									$prevToDate
								);

					} else {

						$historicalMovement =
							(float)
							$this->trialBalanceService
								->getNonCurrentAssetAmount(
									$ledger,
									$userId,
									$historicalFromDate,
									$prevToDate
								);
					}
				}


				/*
				 * Current selected period movement
				 */
				if ($group === 'Current Assets') {

					$current =
						(float)
						$this->trialBalanceService
							->getCurrentAssetAmount(
								$ledger,
								$userId,
								$fromDate,
								$toDate
							);

				} else {

					$current =
						(float)
						$this->trialBalanceService
							->getNonCurrentAssetAmount(
								$ledger,
								$userId,
								$fromDate,
								$toDate
							);
				}


				$opening =
					$baseOpening +
					$historicalMovement;


				$trial['Assets'][$group][$ledger] = [

					'ledgername' =>
						$ledger,

					'opening_dr' =>
						$opening >= 0
							? $opening
							: 0,

					'opening_cr' =>
						$opening < 0
							? abs($opening)
							: 0,

					'debit' =>
						$current >= 0
							? $current
							: 0,

					'credit' =>
						$current < 0
							? abs($current)
							: 0,

					'closing_dr' => 0,
					'closing_cr' => 0,
				];
			}
		}


		/*
		|--------------------------------------------------------------------------
		| EQUITY
		|--------------------------------------------------------------------------
		*/

		$equityHeads = [

			'share_capital' =>
				'Share Capital',

			'reserves_surplus' =>
				'Reserves & Surplus',

			'current_year_profit' =>
				'Current Year Profit',
		];


		/*
		|--------------------------------------------------------------------------
		| CURRENT YEAR PROFIT
		|--------------------------------------------------------------------------
		|
		| CASE 1:
		|
		| 01-04-2026 -> 31-03-2027
		|
		| Opening Current Year Profit = 0
		| Current Year Profit = FY 2026-27 PBT
		|
		|
		| CASE 2:
		|
		| 01-04-2027 -> 31-03-2028
		|
		| Opening Current Year Profit =
		| PBT of FY 2026-27
		|
		| Current Year Profit =
		| PBT of FY 2027-28
		|
		|--------------------------------------------------------------------------
		*/

		$periodType = 'full-yearly';

		$current_year_profit = 0;

		if ($fromDate <= $toDate) {

			$current_year_profit =
				(float)
				(
					$this->profitLossService
						->calculatePL(
							$fromDate,
							$toDate,
							$userId,
							$periodType
						)['pbt']
					?? 0
				);
		}


		/*
		|--------------------------------------------------------------------------
		| PREVIOUS FY PROFIT
		|--------------------------------------------------------------------------
		*/

		$opening_year_profit = 0;

		/*
		 * Only carry previous FY profit when selected period
		 * starts at a new FY.
		 */
		if ($fromDate === $financialYearStart) {

			$opening_year_profit =
				(float)
				(
					$this->profitLossService
						->calculatePL(
							$prevFromDate,
							$prevToDateFY,
							$userId,
							$periodType
						)['pbt']
					?? 0
				);
		}


		foreach ($equityHeads as $openingKey => $ledger) {

			$baseOpening = $getValue(
				$baseEquity,
				$openingKey
			);


			/*
			 * Current Year Profit
			 */
			if ($openingKey === 'current_year_profit') {

				/*
				 * If selected period starts at FY start:
				 *
				 * Opening = previous FY PBT
				 * Current = selected FY PBT
				 */
				if ($fromDate === $financialYearStart) {

					$opening =
						$opening_year_profit;

					$current =
						$current_year_profit;

				} else {

					/*
					 * Mid-year report:
					 *
					 * Opening = accumulated PBT from FY start
					 * to previous day.
					 *
					 * Current = PBT for selected date range.
					 */
					$opening =
						(float)
						(
							$this->profitLossService
								->calculatePL(
									$financialYearStart,
									$prevToDate,
									$userId,
									$periodType
								)['pbt']
							?? 0
						);

					$current =
						$current_year_profit;
				}

			} else {

				/*
				 * Historical equity movement.
				 */
				$historicalMovement = 0;

				if (
					$historicalFromDate <=
					$prevToDate
				) {

					$historicalMovement =
						(float)
						$this->trialBalanceService
							->getEquityAmount(
								$openingKey,
								$userId,
								$historicalFromDate,
								$prevToDate
							);
				}


				$opening =
					$baseOpening +
					$historicalMovement;


				/*
				 * Current selected period movement.
				 */
				$current =
					(float)
					$this->trialBalanceService
						->getEquityAmount(
							$openingKey,
							$userId,
							$fromDate,
							$toDate
						);
			}


			/*
			 * Equity normally has credit balance.
			 */
			$trial['Equity'][''][$ledger] = [

				'ledgername' =>
					$ledger,

				'opening_dr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'opening_cr' =>
					$opening >= 0
						? $opening
						: 0,

				'debit' =>
					$current < 0
						? abs($current)
						: 0,

				'credit' =>
					$current >= 0
						? $current
						: 0,

				'closing_dr' => 0,
				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| CURRENT LIABILITIES
		|--------------------------------------------------------------------------
		*/

		$liabilityTypes = [

			'trade_payables',
			'advance_from_customer',
			'outstanding_expenses',
			'salary_payable',
			'gst_payable',
			'tds_payable',
			'pf_payable',
			'esi_payable',
			'ptax_payable',
			'lwf_payable',
			'output_gst',
			'short_term_loans',
			'interest_payable'
		];


		foreach ($baseCurrentLiabilities as $key => $value) {

			if (!in_array(
				$key,
				$liabilityTypes,
				true
			)) {

				$liabilityTypes[] = $key;
			}
		}


		foreach ($liabilityTypes as $type) {

			$ledger =
				$displayName($type);


			/*
			 * Base opening
			 */
			$baseOpening =
				$getValue(
					$baseCurrentLiabilities,
					$type
				);


			/*
			 * Historical movement
			 */
			$historicalMovement = 0;

			if (
				$historicalFromDate <=
				$prevToDate
			) {

				$historicalMovement =
					(float)
					$this->trialBalanceService
						->getCurrentLiabilityAmount(
							$type,
							$userId,
							$historicalFromDate,
							$prevToDate
						);
			}


			$opening =
				$baseOpening +
				$historicalMovement;


			/*
			 * Current movement
			 */
			$current =
				(float)
				$this->trialBalanceService
					->getCurrentLiabilityAmount(
						$type,
						$userId,
						$fromDate,
						$toDate
					);


			$trial[
				'Liabilities'
			]['Current Liabilities'][$ledger] = [

				'ledger' =>
					$ledger,

				'opening_dr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'opening_cr' =>
					$opening >= 0
						? $opening
						: 0,

				'debit' =>
					$current < 0
						? abs($current)
						: 0,

				'credit' =>
					$current >= 0
						? $current
						: 0,

				'closing_dr' => 0,
				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| NON CURRENT LIABILITIES
		|--------------------------------------------------------------------------
		*/

		$nonCurrentLiabilityHeads = [

			'long_term_borrowings' =>
				'Long-term Borrowings',

			'other_financial_liabilities' =>
				'Other Financial Liabilities',

			'deferred_tax_liabilities' =>
				'Deferred Tax Liabilities',

			'other_non_current_liabilities' =>
				'Other Non-Current Liabilities',

			'long_term_provisions' =>
				'Long-term Provisions',
		];


		foreach (
			$baseNonCurrentLiabilities
			as $key => $value
		) {

			if (
				!isset(
					$nonCurrentLiabilityHeads[$key]
				)
			) {

				$nonCurrentLiabilityHeads[$key] =
					$displayName($key);
			}
		}


		foreach (
			$nonCurrentLiabilityHeads
			as $type => $ledger
		) {

			$baseOpening =
				$getValue(
					$baseNonCurrentLiabilities,
					$type
				);


			$historicalMovement = 0;

			if (
				$historicalFromDate <=
				$prevToDate
			) {

				$historicalMovement =
					(float)
					$this->trialBalanceService
						->getNonCurrentLiabilityAmount(
							$type,
							$userId,
							$historicalFromDate,
							$prevToDate
						);
			}


			$opening =
				$baseOpening +
				$historicalMovement;


			$current =
				(float)
				$this->trialBalanceService
					->getNonCurrentLiabilityAmount(
						$type,
						$userId,
						$fromDate,
						$toDate
					);


			$trial[
				'Liabilities'
			]['Non-Current Liabilities'][$ledger] = [

				'ledgername' =>
					$ledger,

				'opening_dr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'opening_cr' =>
					$opening >= 0
						? $opening
						: 0,

				'debit' =>
					$current < 0
						? abs($current)
						: 0,

				'credit' =>
					$current >= 0
						? $current
						: 0,

				'closing_dr' => 0,
				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| INCOME
		|--------------------------------------------------------------------------
		|
		| Revenue / Income opening is accumulated only inside
		| the current financial year.
		|
		| At a new FY, opening = 0.
		|
		|--------------------------------------------------------------------------
		*/

		$openingSales = 0;

		if ($fromDate > $financialYearStart) {

			$openingSales =
				DB::table('sales_values as sv')
					->join(
						'sales as s',
						's.id',
						'=',
						'sv.sid'
					)
					->where(
						's.added_by',
						$userId
					)
					->where(
						's.status',
						1
					)
					->whereBetween(
						's.inv_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('sv.amount');
		}


		$openingSalesCreditNote = 0;

		if ($fromDate > $financialYearStart) {

			$openingSalesCreditNote =
				DB::table('vouchers')
					->where(
						'added_by',
						$userId
					)
					->where(
						'note_type',
						'Credit'
					)
					->whereBetween(
						'inv_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('taxable_value');
		}


		$openingRevenue =
			(float) $openingSales -
			(float) $openingSalesCreditNote;


		$currentSales =
			DB::table('sales_values as sv')
				->join(
					'sales as s',
					's.id',
					'=',
					'sv.sid'
				)
				->where(
					's.added_by',
					$userId
				)
				->where(
					's.status',
					1
				)
				->whereBetween(
					's.inv_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('sv.amount');


		$currentSalesCreditNote =
			DB::table('vouchers')
				->where(
					'added_by',
					$userId
				)
				->where(
					'note_type',
					'Credit'
				)
				->whereBetween(
					'inv_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('taxable_value');


		$currentRevenue =
			(float) $currentSales -
			(float) $currentSalesCreditNote;


		/*
		|--------------------------------------------------------------------------
		| OTHER INCOME
		|--------------------------------------------------------------------------
		*/

		$openingOtherIncome = 0;

		if ($fromDate > $financialYearStart) {

			$openingOtherIncome =
				DB::table('income')
					->where(
						'addBy',
						$userId
					)
					->where(
						'status',
						1
					)
					->whereBetween(
						'dateInput',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('amount');
		}


		$currentOtherIncome =
			DB::table('income')
				->where(
					'addBy',
					$userId
				)
				->where(
					'status',
					1
				)
				->whereBetween(
					'dateInput',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('amount');


		$trial['Income']['Income']['Revenue from Operations'] = [

			'ledger' =>
				'Revenue from Operations',

			'opening_dr' =>
				$openingRevenue < 0
					? abs($openingRevenue)
					: 0,

			'opening_cr' =>
				$openingRevenue >= 0
					? $openingRevenue
					: 0,

			'debit' =>
				$currentRevenue < 0
					? abs($currentRevenue)
					: 0,

			'credit' =>
				$currentRevenue >= 0
					? $currentRevenue
					: 0,

			'closing_dr' => 0,
			'closing_cr' => 0,
		];


		$trial['Income']['Income']['Other Income'] = [

			'ledger' =>
				'Other Income',

			'opening_dr' =>
				$openingOtherIncome < 0
					? abs($openingOtherIncome)
					: 0,

			'opening_cr' =>
				$openingOtherIncome >= 0
					? $openingOtherIncome
					: 0,

			'debit' =>
				$currentOtherIncome < 0
					? abs($currentOtherIncome)
					: 0,

			'credit' =>
				$currentOtherIncome >= 0
					? $currentOtherIncome
					: 0,

			'closing_dr' => 0,
			'closing_cr' => 0,
		];


		/*
		|--------------------------------------------------------------------------
		| PURCHASE / COGS
		|--------------------------------------------------------------------------
		*/

		$openingPurchase = 0;

		if ($fromDate > $financialYearStart) {

			$openingPurchase =
				DB::table('purchase_values as pv')
					->join(
						'purchases as p',
						'p.id',
						'=',
						'pv.sid'
					)
					->where(
						'p.added_by',
						$userId
					)
					->where(
						'p.status',
						1
					)
					->whereBetween(
						'p.inv_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('pv.amount');
		}


		$openingPurchaseDebitNote = 0;

		if ($fromDate > $financialYearStart) {

			$openingPurchaseDebitNote =
				DB::table('voucher_purchases')
					->where(
						'added_by',
						$userId
					)
					->where(
						'note_type',
						'Debit'
					)
					->whereBetween(
						'inv_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('taxable_value');
		}


		$openingCOGS =
			(float) $openingPurchase -
			(float) $openingPurchaseDebitNote;


		$currentPurchase =
			DB::table('purchase_values as pv')
				->join(
					'purchases as p',
					'p.id',
					'=',
					'pv.sid'
				)
				->where(
					'p.added_by',
					$userId
				)
				->where(
					'p.status',
					1
				)
				->whereBetween(
					'p.inv_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('pv.amount');


		$currentPurchaseDebitNote =
			DB::table('voucher_purchases')
				->where(
					'added_by',
					$userId
				)
				->where(
					'note_type',
					'Debit'
				)
				->whereBetween(
					'inv_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('taxable_value');


		$currentCOGS =
			(float) $currentPurchase -
			(float) $currentPurchaseDebitNote;


		/*
		|--------------------------------------------------------------------------
		| DIRECT EXPENSES
		|--------------------------------------------------------------------------
		*/

		$currentDirectExpenses =
			DB::table('expenses')
				->select(
					'expense_type',
					DB::raw(
						'SUM(expense_amt) as amount'
					)
				)
				->where(
					'added_by',
					$userId
				)
				->where(
					'expense_cat',
					'direct'
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->groupBy(
					'expense_type'
				)
				->pluck(
					'amount',
					'expense_type'
				);


		$openingDirectExpenses = collect();

		if ($fromDate > $financialYearStart) {

			$openingDirectExpenses =
				DB::table('expenses')
					->select(
						'expense_type',
						DB::raw(
							'SUM(expense_amt) as amount'
						)
					)
					->where(
						'added_by',
						$userId
					)
					->where(
						'expense_cat',
						'direct'
					)
					->whereBetween(
						'expense_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->groupBy(
						'expense_type'
					)
					->pluck(
						'amount',
						'expense_type'
					);
		}


		$expenseTypes =
			collect($currentDirectExpenses)
				->keys()
				->merge(
					$openingDirectExpenses->keys()
				)
				->unique();


		foreach ($expenseTypes as $type) {

			$opening =
				(float)
				(
					$openingDirectExpenses[$type]
					?? 0
				);

			$current =
				(float)
				(
					$currentDirectExpenses[$type]
					?? 0
				);


			$trial[
				'Expenses'
			]['Direct Expenses'][$type] = [

				'ledger' =>
					$displayName($type),

				'opening_dr' =>
					$opening >= 0
						? $opening
						: 0,

				'opening_cr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'debit' =>
					$current >= 0
						? $current
						: 0,

				'credit' =>
					$current < 0
						? abs($current)
						: 0,

				'closing_dr' => 0,
				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| INDIRECT EXPENSES
		|--------------------------------------------------------------------------
		*/

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


		$financeTypes = [

			'interest_expense',
			'bank_charges',
			'loan_interest'
		];


		$sellingTypes = [

			'advertisement',
			'sales_commission',
			'marketing_expense',
			'freight_outward'
		];


		/*
		|--------------------------------------------------------------------------
		| EMPLOYEE BENEFITS
		|--------------------------------------------------------------------------
		*/

		$currentEmployeeBenefit =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->where(
					'expense_type',
					'employee_benefits'
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingEmployeeBenefit = 0;

		if ($fromDate > $financialYearStart) {

			$openingEmployeeBenefit =
				DB::table('expenses')
					->where(
						'added_by',
						$userId
					)
					->where(
						'expense_type',
						'employee_benefits'
					)
					->whereBetween(
						'expense_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('expense_amt');
		}


		/*
		|--------------------------------------------------------------------------
		| ADMINISTRATIVE
		|--------------------------------------------------------------------------
		*/

		$currentAdministrativeExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$adminTypes
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingAdministrativeExpense = 0;

		if ($fromDate > $financialYearStart) {

			$openingAdministrativeExpense =
				DB::table('expenses')
					->where(
						'added_by',
						$userId
					)
					->whereIn(
						'expense_type',
						$adminTypes
					)
					->whereBetween(
						'expense_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('expense_amt');
		}


		/*
		|--------------------------------------------------------------------------
		| FINANCE COST
		|--------------------------------------------------------------------------
		*/

		$currentFinanceCost =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$financeTypes
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingFinanceCost = 0;

		if ($fromDate > $financialYearStart) {

			$openingFinanceCost =
				DB::table('expenses')
					->where(
						'added_by',
						$userId
					)
					->whereIn(
						'expense_type',
						$financeTypes
					)
					->whereBetween(
						'expense_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('expense_amt');
		}


		/*
		|--------------------------------------------------------------------------
		| SELLING EXPENSE
		|--------------------------------------------------------------------------
		*/

		$currentSellingExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$sellingTypes
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingSellingExpense = 0;

		if ($fromDate > $financialYearStart) {

			$openingSellingExpense =
				DB::table('expenses')
					->where(
						'added_by',
						$userId
					)
					->whereIn(
						'expense_type',
						$sellingTypes
					)
					->whereBetween(
						'expense_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('expense_amt');
		}


		/*
		|--------------------------------------------------------------------------
		| DEPRECIATION
		|--------------------------------------------------------------------------
		*/

		$currentDepreciation =
			DB::table('assets')
				->where(
					'added_by',
					$userId
				)
				->whereBetween(
					'date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('depreciation_value');


		$openingDepreciation = 0;

		if ($fromDate > $financialYearStart) {

			$openingDepreciation =
				DB::table('assets')
					->where(
						'added_by',
						$userId
					)
					->whereBetween(
						'date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('depreciation_value');
		}


		/*
		|--------------------------------------------------------------------------
		| OTHER EXPENSES
		|--------------------------------------------------------------------------
		*/

		$usedTypes = array_merge(

			[
				'employee_benefits'
			],

			$adminTypes,

			$financeTypes,

			$sellingTypes
		);


		$currentOtherExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereNotIn(
					'expense_type',
					$usedTypes
				)
				->where(
					'expense_cat',
					'indirect'
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingOtherExpense = 0;

		if ($fromDate > $financialYearStart) {

			$openingOtherExpense =
				DB::table('expenses')
					->where(
						'added_by',
						$userId
					)
					->whereNotIn(
						'expense_type',
						$usedTypes
					)
					->where(
						'expense_cat',
						'indirect'
					)
					->whereBetween(
						'expense_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('expense_amt');
		}


		/*
		|--------------------------------------------------------------------------
		| EXPENSE LEDGERS
		|--------------------------------------------------------------------------
		*/

		$expenseLedgerData = [

			'Cost of Goods Sold' => [

				$openingCOGS,
				$currentCOGS
			],

			'Employee Benefit Expenses' => [

				$openingEmployeeBenefit,
				$currentEmployeeBenefit
			],

			'Finance Cost' => [

				$openingFinanceCost,
				$currentFinanceCost
			],

			'Depreciation' => [

				$openingDepreciation,
				$currentDepreciation
			],

			'Administrative Expenses' => [

				$openingAdministrativeExpense,
				$currentAdministrativeExpense
			],

			'Selling Expenses' => [

				$openingSellingExpense,
				$currentSellingExpense
			],

			'Other Expenses' => [

				$openingOtherExpense,
				$currentOtherExpense
			],
		];


		foreach (
			$expenseLedgerData
			as $ledger => [$opening, $current]
		) {

			$opening = (float) $opening;
			$current = (float) $current;


			$trial[
				'Expenses'
			]['Expenses'][$ledger] = [

				'ledger' =>
					$ledger,

				'opening_dr' =>
					$opening >= 0
						? $opening
						: 0,

				'opening_cr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'debit' =>
					$current >= 0
						? $current
						: 0,

				'credit' =>
					$current < 0
						? abs($current)
						: 0,

				'closing_dr' => 0,
				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| FILTERS
		|--------------------------------------------------------------------------
		*/

		if (
			!empty($ledgerGroup)
			|| !empty($ledgerFilter)
		) {

			foreach (
				$trial as $mainGroup => &$subGroups
			) {

				if (
					!empty($ledgerGroup)
					&& $mainGroup !== $ledgerGroup
				) {

					unset(
						$trial[$mainGroup]
					);

					continue;
				}


				foreach (
					$subGroups as $subGroup => &$ledgers
				) {

					if (!empty($ledgerFilter)) {

						$ledgers =
							array_filter(
								$ledgers,
								function ($row)
									use ($ledgerFilter) {

									$ledgerName =
										$row['ledgername']
										?? $row['ledger']
										?? '';

									return stripos(
										$ledgerName,
										$ledgerFilter
									) !== false;
								}
							);
					}


					if (empty($ledgers)) {

						unset(
							$subGroups[$subGroup]
						);
					}
				}


				if (empty($subGroups)) {

					unset(
						$trial[$mainGroup]
					);
				}
			}

			unset(
				$subGroups,
				$ledgers
			);
		}


		/*
		|--------------------------------------------------------------------------
		| CALCULATE CLOSING BALANCES
		|--------------------------------------------------------------------------
		*/

		$openingDrTotal = 0;
		$openingCrTotal = 0;

		$totalDr = 0;
		$totalCr = 0;


		foreach ($trial as &$groups) {

			foreach ($groups as &$ledgers) {

				foreach ($ledgers as &$row) {

					$openingDr =
						round(
							(float)(
								$row['opening_dr']
								?? 0
							),
							2
						);

					$openingCr =
						round(
							(float)(
								$row['opening_cr']
								?? 0
							),
							2
						);

					$debit =
						round(
							(float)(
								$row['debit']
								?? 0
							),
							2
						);

					$credit =
						round(
							(float)(
								$row['credit']
								?? 0
							),
							2
						);


					/*
					 * Opening totals
					 */
					$openingDrTotal +=
						$openingDr;

					$openingCrTotal +=
						$openingCr;


					/*
					 * Closing calculation
					 */
					$dr =
						$openingDr +
						$debit;

					$cr =
						$openingCr +
						$credit;


					if ($dr > $cr) {

						$row['closing_dr'] =
							round(
								$dr - $cr,
								2
							);

						$row['closing_cr'] =
							0;

					} elseif ($cr > $dr) {

						$row['closing_dr'] =
							0;

						$row['closing_cr'] =
							round(
								$cr - $dr,
								2
							);

					} else {

						$row['closing_dr'] =
							0;

						$row['closing_cr'] =
							0;
					}


					$totalDr +=
						$row['closing_dr'];

					$totalCr +=
						$row['closing_cr'];
				}
			}
		}


		unset(
			$groups,
			$ledgers,
			$row
		);


		/*
		|--------------------------------------------------------------------------
		| ROUND TOTALS
		|--------------------------------------------------------------------------
		*/

		$openingDrTotal =
			round(
				$openingDrTotal,
				2
			);

		$openingCrTotal =
			round(
				$openingCrTotal,
				2
			);

		$totalDr =
			round(
				$totalDr,
				2
			);

		$totalCr =
			round(
				$totalCr,
				2
			);


		/*
		|--------------------------------------------------------------------------
		| DIFFERENCE
		|--------------------------------------------------------------------------
		*/

		$openingDifference =
			round(
				$openingDrTotal -
				$openingCrTotal,
				2
			);

		$closingDifference =
			round(
				$totalDr -
				$totalCr,
				2
			);


		/*
		|--------------------------------------------------------------------------
		| BALANCE SHEET INFORMATION
		|--------------------------------------------------------------------------
		*/

		$openingBalanceSheetInfo = [

			'id' =>
				$openingBalanceSheet->id
				?? null,

			'fy' =>
				$openingBalanceSheet->fy
				?? null,

			'startYear' =>
				$openingBalanceSheet->startYear
				?? null,

			'endYear' =>
				$openingBalanceSheet->endYear
				?? null,
		];


		/*
		|--------------------------------------------------------------------------
		| RESPONSE
		|--------------------------------------------------------------------------
		*/

		return response()->json([

			'success' =>
				true,

			'trial' =>
				$trial,

			'from_date' =>
				$fromDate,

			'to_date' =>
				$toDate,

			'financial_year' =>
				$financialYear,

			'financial_year_start' =>
				$financialYearStart,

			'financial_year_end' =>
				$financialYearEnd,

			/*
			 * Base Balance Sheet
			 */
			'opening_balance_sheet' =>
				$openingBalanceSheetInfo,

			'opening_balance_sheet_id' =>
				$openingBalanceSheet->id
				?? null,

			'opening_balance_sheet_fy' =>
				$openingBalanceSheet->fy
				?? null,

			'opening_balance_sheet_start_year' =>
				$openingBalanceSheet->startYear
				?? null,

			'opening_balance_sheet_end_year' =>
				$openingBalanceSheet->endYear
				?? null,

			/*
			 * Base date
			 */
			'base_balance_date' =>
				$baseBalanceDate,

			'historical_from_date' =>
				$historicalFromDate,

			/*
			 * Previous FY
			 */
			'previous_fy_start' =>
				$prevFromDate,

			'previous_fy_end' =>
				$prevToDateFY,

			/*
			 * Profit
			 */
			'current_year_profit' =>
				round(
					$current_year_profit,
					2
				),

			'opening_year_profit' =>
				round(
					$opening_year_profit,
					2
				),

			/*
			 * Opening totals
			 */
			'opening_dr' =>
				$openingDrTotal,

			'opening_cr' =>
				$openingCrTotal,

			'opening_difference' =>
				$openingDifference,

			/*
			 * Closing totals
			 */
			'closing_dr' =>
				$totalDr,

			'closing_cr' =>
				$totalCr,

			'closing_difference' =>
				$closingDifference,

			'diff' =>
				$closingDifference,
		]);
	}
	

	public function fatch_trial_balance_data_old2(Request $r)
	{
		$userId = currentOwnerId();
		$req_type = 0;

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); // CA / Accountant access
			$req_type = 1;
		}

		$propId       = $r->propId ?? null;
		$ledgerFilter = $r->ledger_name ?? null;
		$ledgerGroup  = $r->ledger_group ?? null;


		/*
		|--------------------------------------------------------------------------
		| DATE PARSER
		|--------------------------------------------------------------------------
		*/

		$parseDate = function ($date) {

			if (empty($date)) {
				return null;
			}

			$date = trim($date);

			$formats = [
				'd-m-Y',
				'd/m/Y',
				'Y-m-d',
			];

			foreach ($formats as $format) {
				try {
					$dt = \DateTime::createFromFormat(
						'!' . $format,
						$date
					);

					if ($dt && $dt->format($format) === $date) {
						return $dt->format('Y-m-d');
					}

				} catch (\Exception $e) {
					
				}
			}

			$timestamp = strtotime($date);
			return $timestamp ? date('Y-m-d', $timestamp) : null;
		};


		$fromDate = $parseDate($r->from_date);
		$toDate   = $parseDate($r->to_date);

		if (!$fromDate || !$toDate) {
			return response()->json([
				'success' => false,
				'message' => 'Invalid From Date or To Date.'
			], 422);
		}


		if ($fromDate > $toDate) {
			return response()->json([
				'success' => false,
				'message' => 'From Date cannot be greater than To Date.'
			], 422);
		}

		$trial = [];
		//PREVIOUS DATE
		$prevToDate = date('Y-m-d',strtotime($fromDate . ' -1 day'));

		//FINANCIAL YEAR START
		$fromYear  = (int) date('Y', strtotime($fromDate));
		$fromMonth = (int) date('m', strtotime($fromDate));

		if ($fromMonth >= 4) {
			$financialYearStart = $fromYear . '-04-01';
			$financialYear = $fromYear . '-' . ($fromYear + 1);
		} else {
			$financialYearStart = ($fromYear - 1) . '-04-01';
			$financialYear = ($fromYear - 1) . '-' . $fromYear;
		}


		//GET ONE-TIME BASE BALANCE SHEET
		$baseBalanceSheet = DB::table('balance_sheets')
			->where('added_by', $userId)
			->orderBy('id', 'asc')
			->first();

		//BASE BALANCE SHEET DATE
		$baseEndDate = null;
		if ($baseBalanceSheet) {
			$baseEndDate = $baseBalanceSheet->endYear ?? $baseBalanceSheet->startYear ?? null;
			if ($baseEndDate) {
				$baseEndDate = $parseDate($baseEndDate);
			}
		}

		//DECODE BASE BALANCE SHEET
		$baseEquity = [];
		$baseCurrentLiabilities = [];
		$baseNonCurrentLiabilities = [];
		$baseCurrentAssets = [];
		$baseNonCurrentAssets = [];

		if ($baseBalanceSheet) {

			$baseEquity = !empty($baseBalanceSheet->equity)
				? json_decode(
					$baseBalanceSheet->equity,
					true
				)
				: [];

			$baseCurrentLiabilities =
				!empty($baseBalanceSheet->current_liabilities)
					? json_decode(
						$baseBalanceSheet->current_liabilities,
						true
					)
					: [];

			$baseNonCurrentLiabilities =
				!empty($baseBalanceSheet->non_current_liabilities)
					? json_decode(
						$baseBalanceSheet->non_current_liabilities,
						true
					)
					: [];

			$baseCurrentAssets =
				!empty($baseBalanceSheet->current_assets)
					? json_decode(
						$baseBalanceSheet->current_assets,
						true
					)
					: [];

			$baseNonCurrentAssets =
				!empty($baseBalanceSheet->non_current_assets)
					? json_decode(
						$baseBalanceSheet->non_current_assets,
						true
					)
					: [];
		}


		/*
		|--------------------------------------------------------------------------
		| HELPER
		|--------------------------------------------------------------------------
		*/

		$getValue = function ($data, $key) {

			if (!is_array($data)) {
				return 0;
			}

			return isset($data[$key])
				? (float) $data[$key]
				: 0;
		};


		$displayName = function ($key) {

			return ucwords(
				str_replace(
					'_',
					' ',
					$key
				)
			);
		};


		/*
		|--------------------------------------------------------------------------
		| HELPER - BASE TO OPENING PERIOD
		|--------------------------------------------------------------------------
		|
		| Permanent Balance Sheet accounts:
		|
		| Opening =
		| Base Balance Sheet
		| +
		| all movements after base date and before fromDate
		|
		|--------------------------------------------------------------------------
		*/

		$historicalFromDate = null;
		if ($baseEndDate) {
			$historicalFromDate = date('Y-m-d',strtotime($baseEndDate . ' +1 day'));
		}


		/*
		|--------------------------------------------------------------------------
		| HELPER - CALCULATE HISTORICAL ASSET MOVEMENT
		|--------------------------------------------------------------------------
		*/

		$getHistoricalAssetAmount = function (
			$ledger,
			$isCurrentAsset
		) use (
			$historicalFromDate,
			$prevToDate,
			$userId
		) {

			if (
				empty($historicalFromDate)
				|| $historicalFromDate > $prevToDate
			) {
				return 0;
			}

			if ($isCurrentAsset) {
				return (float) $this->trialBalanceService->getCurrentAssetAmount($ledger,$userId,$historicalFromDate,$prevToDate);
			}
			return (float) $this->trialBalanceService->getNonCurrentAssetAmount($ledger,$userId,$historicalFromDate,$prevToDate);
			
		};


		/*
		|--------------------------------------------------------------------------
		| ASSETS
		|--------------------------------------------------------------------------
		*/

		$assetHeads = [

			'Current Assets' => [
				'cash_in_hand' =>'Cash in Hand',
				'bank_accounts' =>'Bank Accounts',
				'trade_receivables' =>'Trade Receivables',
				'advance_to_vendor' =>'Advance to Vendor',
				'employee_advance' =>'Employee Advance',
				'prepaid_expenses' =>'Prepaid Expenses',
				'input_gst_credit' =>'Input GST Credit',
				'tds_receivable' =>'TDS Receivable',
				'inventories' =>'Inventories',
			],

			'Non-Current Assets' => [
				'property_plant_equipment' =>'Fixed Assets',
				'capital_work_in_progress' =>'CWIP',
				'investments' =>'Investments',
				'other_non_current_assets' =>'Other Non-Current Assets',
			],
		];


		/*
		|--------------------------------------------------------------------------
		| EXTRA ASSET HEADS
		|--------------------------------------------------------------------------
		*/

		$extraAssetHeads = [

			'furniture_fixtures' =>'Furniture & Fixtures',
			'computer_it_equipment' =>'Computer & IT Equipment',
			'machinery' =>'Machinery',
			'vehicles' =>'Vehicles',
			'intangible_assets' =>'Intangible Assets',
		];


		foreach ($extraAssetHeads as $key => $name) {

			if (
				isset($baseNonCurrentAssets[$key])
				&& !isset(
					$assetHeads['Non-Current Assets'][$key]
				)
			) {

				$assetHeads['Non-Current Assets'][$key] =
					$name;
			}
		}


		/*
		|--------------------------------------------------------------------------
		| ADD ALL SNAPSHOT ASSET KEYS
		|--------------------------------------------------------------------------
		*/

		foreach ($baseCurrentAssets as $key => $value) {

			if (!isset($assetHeads['Current Assets'][$key])) {

				$assetHeads['Current Assets'][$key] =
					$displayName($key);
			}
		}


		foreach ($baseNonCurrentAssets as $key => $value) {

			if (!isset($assetHeads['Non-Current Assets'][$key])) {

				$assetHeads['Non-Current Assets'][$key] =
					$displayName($key);
			}
		}


		/*
		|--------------------------------------------------------------------------
		| PROCESS ASSETS
		|--------------------------------------------------------------------------
		*/

		foreach ($assetHeads as $group => $heads) {

			foreach ($heads as $openingKey => $ledger) {

				$isCurrentAsset = $group === 'Current Assets';

				if ($isCurrentAsset) {
					$baseOpening = $getValue(
						$baseCurrentAssets,
						$openingKey
					);

				} else {
					$baseOpening = $getValue(
						$baseNonCurrentAssets,
						$openingKey
					);
				}


				/*
				 * Historical movement
				 *
				 * Base -> day before selected From Date
				 */
				$historicalMovement =
					$getHistoricalAssetAmount(
						$ledger,
						$isCurrentAsset
					);


				/*
				 * Opening balance for selected period
				 */
				$opening = $baseOpening +$historicalMovement;


				/*
				 * Current period movement
				 */
				if ($isCurrentAsset) {
					$current = (float) $this->trialBalanceService->getCurrentAssetAmount($ledger,$userId,$fromDate,$toDate);
				} else {
					$current = (float) $this->trialBalanceService->getNonCurrentAssetAmount($ledger,$userId,$fromDate,$toDate);
				}


				$trial['Assets'][$group][$ledger] = [

					'ledgername' => $ledger,
					'opening_dr' =>$opening >= 0 ? $opening: 0,
					'opening_cr' =>$opening < 0 ? abs($opening) : 0,
					'debit' => $current >= 0 ? $current: 0,
					'credit' =>$current < 0 ? abs($current): 0,
					'closing_dr' => 0,
					'closing_cr' => 0,
				];
			}
		}


		/*
		|--------------------------------------------------------------------------
		| EQUITY
		|--------------------------------------------------------------------------
		*/

		$equityHeads = [
			'share_capital' =>'Share Capital',
			'reserves_surplus' =>'Reserves & Surplus',
			'current_year_profit' =>'Current Year Profit',
		];
		
		$periodType = 'full-yearly';
		$current_year_profit = $this->profitLossService->calculatePL($fromDate, $toDate, $userId, $periodType)['pbt'] ?? 0; 
		
		foreach ($equityHeads as $openingKey => $ledger) {

			$baseOpening = $getValue($baseEquity,$openingKey);

			if ($openingKey === 'current_year_profit') {
				$opening = $baseOpening;
				$current = $current_year_profit;
			} else {
				$historicalMovement = 0;
				if (!empty($historicalFromDate)&& $historicalFromDate <= $prevToDate) {
					$historicalMovement =(float)$this->trialBalanceService->getEquityAmount($openingKey,$userId,$historicalFromDate,$prevToDate);
				}
				$opening = $baseOpening + $historicalMovement;
				$current =(float)$this->trialBalanceService->getEquityAmount($openingKey,$userId,$fromDate,$toDate);
			}


			$trial['Equity'][''][$ledger] = [

				'ledgername' => $ledger,
				'opening_dr' =>$opening < 0 ? abs($opening) : 0,
				'opening_cr' =>$opening >= 0 ? $opening : 0,
				'debit' =>$current < 0 ? abs($current) : 0,
				'credit' =>$current >= 0 ? $current : 0,
				'closing_dr' => 0,
				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| CURRENT LIABILITIES
		|--------------------------------------------------------------------------
		*/

		$liabilityTypes = [

			'trade_payables',
			'advance_from_customer',
			'outstanding_expenses',
			'salary_payable',
			'gst_payable',
			'tds_payable',
			'pf_payable',
			'esi_payable',
			'ptax_payable',
			'lwf_payable',
			'output_gst',
			'short_term_loans',
			'interest_payable'
		];


		foreach ($baseCurrentLiabilities as $key => $value) {

			if (!in_array(
				$key,
				$liabilityTypes,
				true
			)) {

				$liabilityTypes[] = $key;
			}
		}


		foreach ($liabilityTypes as $type) {

			$ledger = $displayName($type);


			$baseOpening = $getValue(
				$baseCurrentLiabilities,
				$type
			);


			/*
			 * Historical movement
			 */
			$historicalMovement = 0;

			if (
				!empty($historicalFromDate)
				&& $historicalFromDate <= $prevToDate
			) {

				$historicalMovement =
					(float)
					$this->trialBalanceService
						->getCurrentLiabilityAmount(
							$type,
							$userId,
							$historicalFromDate,
							$prevToDate
						);
			}


			$opening =
				$baseOpening +
				$historicalMovement;


			/*
			 * Current movement
			 */
			$current =
				(float)
				$this->trialBalanceService
					->getCurrentLiabilityAmount(
						$type,
						$userId,
						$fromDate,
						$toDate
					);


			$trial['Liabilities']['Current Liabilities'][$ledger] = [

				'ledger' => $ledger,

				'opening_dr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'opening_cr' =>
					$opening >= 0
						? $opening
						: 0,

				'debit' =>
					$current < 0
						? abs($current)
						: 0,

				'credit' =>
					$current >= 0
						? $current
						: 0,

				'closing_dr' => 0,

				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| NON-CURRENT LIABILITIES
		|--------------------------------------------------------------------------
		*/

		$nonCurrentLiabilityHeads = [

			'long_term_borrowings' =>
				'Long-term Borrowings',

			'other_financial_liabilities' =>
				'Other Financial Liabilities',

			'deferred_tax_liabilities' =>
				'Deferred Tax Liabilities',

			'other_non_current_liabilities' =>
				'Other Non-Current Liabilities',

			'long_term_provisions' =>
				'Long-term Provisions',
		];


		foreach ($baseNonCurrentLiabilities as $key => $value) {

			if (!isset(
				$nonCurrentLiabilityHeads[$key]
			)) {

				$nonCurrentLiabilityHeads[$key] =
					$displayName($key);
			}
		}


		foreach (
			$nonCurrentLiabilityHeads
			as $type => $ledger
		) {

			$baseOpening = $getValue(
				$baseNonCurrentLiabilities,
				$type
			);


			/*
			 * Historical movement
			 */
			$historicalMovement = 0;

			if (
				!empty($historicalFromDate)
				&& $historicalFromDate <= $prevToDate
			) {

				$historicalMovement =
					(float)
					$this->trialBalanceService
						->getNonCurrentLiabilityAmount(
							$type,
							$userId,
							$historicalFromDate,
							$prevToDate
						);
			}


			$opening =
				$baseOpening +
				$historicalMovement;


			/*
			 * Current movement
			 */
			$current =
				(float)
				$this->trialBalanceService
					->getNonCurrentLiabilityAmount(
						$type,
						$userId,
						$fromDate,
						$toDate
					);


			$trial['Liabilities']['Non-Current Liabilities'][$ledger] = [

				'ledgername' => $ledger,

				'opening_dr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'opening_cr' =>
					$opening >= 0
						? $opening
						: 0,

				'debit' =>
					$current < 0
						? abs($current)
						: 0,

				'credit' =>
					$current >= 0
						? $current
						: 0,

				'closing_dr' => 0,

				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| INCOME - REVENUE
		|--------------------------------------------------------------------------
		|
		| Income accounts reset every FY.
		|
		| Opening = FY start -> previous day
		| Current = selected period
		|
		|--------------------------------------------------------------------------
		*/

		$openingSales = 0;

		if ($financialYearStart <= $prevToDate) {

			$openingSales =
				DB::table('sales_values as sv')
					->join(
						'sales as s',
						's.id',
						'=',
						'sv.sid'
					)
					->where(
						's.added_by',
						$userId
					)
					->where(
						's.status',
						1
					)
					->whereBetween(
						's.inv_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('sv.amount');
		}


		$openingSalesCreditNote = 0;

		if ($financialYearStart <= $prevToDate) {

			$openingSalesCreditNote =
				DB::table('vouchers')
					->where(
						'added_by',
						$userId
					)
					->where(
						'note_type',
						'Credit'
					)
					->whereBetween(
						'inv_date',
						[
							$financialYearStart,
							$prevToDate
						]
					)
					->sum('taxable_value');
		}


		$openingRevenue =
			(float) $openingSales -
			(float) $openingSalesCreditNote;


		$currentSales = DB::table('sales_values as sv')
					->join('sales as s','s.id','=','sv.sid')
					->where('s.added_by',$userId)
					->where('s.status',1)
					->whereBetween('s.inv_date',[$fromDate,$toDate])
					->sum('sv.amount');


		$currentSalesCreditNote =
			DB::table('vouchers')
				->where('added_by',$userId)
				->where('note_type','Credit')
				->whereBetween('inv_date',[$fromDate,$toDate])
				->sum('taxable_value');


		$currentRevenue = (float) $currentSales - (float) $currentSalesCreditNote;


		/*
		|--------------------------------------------------------------------------
		| OTHER INCOME
		|--------------------------------------------------------------------------
		*/

		$openingOtherIncome =
			DB::table('income')
				->where(
					'addBy',
					$userId
				)
				->where(
					'status',
					1
				)
				->when(
					$financialYearStart <= $prevToDate,
					function ($q) use (
						$financialYearStart,
						$prevToDate
					) {

						$q->whereBetween(
							'dateInput',
							[
								$financialYearStart,
								$prevToDate
							]
						);
					},
					function ($q) {

						$q->whereRaw('1 = 0');
					}
				)
				->sum('amount');


		$currentOtherIncome =
			DB::table('income')
				->where(
					'addBy',
					$userId
				)
				->where(
					'status',
					1
				)
				->whereBetween(
					'dateInput',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('amount');


		$trial['Income']['Income']['Revenue from Operations'] = [

			'ledger' =>'Revenue from Operations',

			'opening_dr' =>
				$openingRevenue < 0
					? abs($openingRevenue)
					: 0,

			'opening_cr' =>
				$openingRevenue >= 0
					? $openingRevenue
					: 0,

			'debit' =>
				$currentRevenue < 0
					? abs($currentRevenue)
					: 0,

			'credit' =>
				$currentRevenue >= 0
					? $currentRevenue
					: 0,

			'closing_dr' => 0,

			'closing_cr' => 0,
		];


		$trial['Income']['Income']['Other Income'] = [

			'ledger' =>'Other Income',

			'opening_dr' =>
				$openingOtherIncome < 0
					? abs($openingOtherIncome)
					: 0,

			'opening_cr' =>
				$openingOtherIncome >= 0
					? $openingOtherIncome
					: 0,

			'debit' =>
				$currentOtherIncome < 0
					? abs($currentOtherIncome)
					: 0,

			'credit' =>
				$currentOtherIncome >= 0
					? $currentOtherIncome
					: 0,

			'closing_dr' => 0,

			'closing_cr' => 0,
		];


		/*
		|--------------------------------------------------------------------------
		| PURCHASE / COGS
		|--------------------------------------------------------------------------
		*/

		$currentPurchase =
			DB::table('purchase_values as pv')
				->join(
					'purchases as p',
					'p.id',
					'=',
					'pv.sid'
				)
				->where(
					'p.added_by',
					$userId
				)
				->where(
					'p.status',
					1
				)
				->whereBetween(
					'p.inv_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('pv.amount');


		$currentPurchaseDebitNote =
			DB::table('voucher_purchases')
				->where(
					'added_by',
					$userId
				)
				->where(
					'note_type',
					'Debit'
				)
				->whereBetween(
					'inv_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('taxable_value');


		$currentCOGS = (float) $currentPurchase - (float) $currentPurchaseDebitNote;


		$openingPurchase =
			DB::table('purchase_values as pv')
				->join(
					'purchases as p',
					'p.id',
					'=',
					'pv.sid'
				)
				->where(
					'p.added_by',
					$userId
				)
				->where(
					'p.status',
					1
				)
				->whereBetween(
					'p.inv_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('pv.amount');


		$openingPurchaseDebitNote =
			DB::table('voucher_purchases')
				->where(
					'added_by',
					$userId
				)
				->where(
					'note_type',
					'Debit'
				)
				->whereBetween(
					'inv_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('taxable_value');


		$openingCOGS = (float) $openingPurchase - (float) $openingPurchaseDebitNote;


		/*
		|--------------------------------------------------------------------------
		| DIRECT EXPENSES
		|--------------------------------------------------------------------------
		*/

		$currentDirectExpenses =
			DB::table('expenses')
				->select(
					'expense_type',
					DB::raw(
						'SUM(expense_amt) as amount'
					)
				)
				->where(
					'added_by',
					$userId
				)
				->where(
					'expense_cat',
					'direct'
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->groupBy(
					'expense_type'
				)
				->pluck(
					'amount',
					'expense_type'
				);


		$openingDirectExpenses =
			DB::table('expenses')
				->select(
					'expense_type',
					DB::raw(
						'SUM(expense_amt) as amount'
					)
				)
				->where(
					'added_by',
					$userId
				)
				->where(
					'expense_cat',
					'direct'
				)
				->whereBetween(
					'expense_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->groupBy(
					'expense_type'
				)
				->pluck(
					'amount',
					'expense_type'
				);


		$expenseTypes =
			collect($currentDirectExpenses)
				->keys()
				->merge(
					$openingDirectExpenses->keys()
				)
				->unique();


		foreach ($expenseTypes as $type) {

			$opening =
				(float)
				($openingDirectExpenses[$type] ?? 0);

			$current =
				(float)
				($currentDirectExpenses[$type] ?? 0);


			$trial['Expenses']['Direct Expenses'][$type] = [

				'ledger' =>
					$displayName($type),

				'opening_dr' =>
					$opening >= 0
						? $opening
						: 0,

				'opening_cr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'debit' =>
					$current >= 0
						? $current
						: 0,

				'credit' =>
					$current < 0
						? abs($current)
						: 0,

				'closing_dr' => 0,

				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| INDIRECT EXPENSES
		|--------------------------------------------------------------------------
		*/

		$currentEmployeeBenefit =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->where(
					'expense_type',
					'employee_benefits'
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingEmployeeBenefit =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->where(
					'expense_type',
					'employee_benefits'
				)
				->whereBetween(
					'expense_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('expense_amt');


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


		$currentAdministrativeExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$adminTypes
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingAdministrativeExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$adminTypes
				)
				->whereBetween(
					'expense_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('expense_amt');


		$financeTypes = [

			'interest_expense',
			'bank_charges',
			'loan_interest'
		];


		$currentFinanceCost =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$financeTypes
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingFinanceCost =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$financeTypes
				)
				->whereBetween(
					'expense_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('expense_amt');


		$sellingTypes = [

			'advertisement',
			'sales_commission',
			'marketing_expense',
			'freight_outward'
		];


		$currentSellingExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$sellingTypes
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingSellingExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereIn(
					'expense_type',
					$sellingTypes
				)
				->whereBetween(
					'expense_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('expense_amt');


		/*
		|--------------------------------------------------------------------------
		| DEPRECIATION
		|--------------------------------------------------------------------------
		*/

		$currentDepreciation =
			DB::table('assets')
				->where(
					'added_by',
					$userId
				)
				->whereBetween(
					'date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('depreciation_value');


		$openingDepreciation =
			DB::table('assets')
				->where(
					'added_by',
					$userId
				)
				->whereBetween(
					'date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('depreciation_value');


		/*
		|--------------------------------------------------------------------------
		| OTHER EXPENSES
		|--------------------------------------------------------------------------
		*/

		$usedTypes = array_merge(
			['employee_benefits'],
			$adminTypes,
			$financeTypes,
			$sellingTypes
		);


		$currentOtherExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereNotIn(
					'expense_type',
					$usedTypes
				)
				->where(
					'expense_cat',
					'indirect'
				)
				->whereBetween(
					'expense_date',
					[
						$fromDate,
						$toDate
					]
				)
				->sum('expense_amt');


		$openingOtherExpense =
			DB::table('expenses')
				->where(
					'added_by',
					$userId
				)
				->whereNotIn(
					'expense_type',
					$usedTypes
				)
				->where(
					'expense_cat',
					'indirect'
				)
				->whereBetween(
					'expense_date',
					[
						$financialYearStart,
						$prevToDate
					]
				)
				->sum('expense_amt');


		/*
		|--------------------------------------------------------------------------
		| EXPENSE LEDGERS
		|--------------------------------------------------------------------------
		*/

		$expenseLedgerData = [

			'Cost of Goods Sold' => [
				$openingCOGS,
				$currentCOGS
			],

			'Employee Benefit Expenses' => [
				$openingEmployeeBenefit,
				$currentEmployeeBenefit
			],

			'Finance Cost' => [
				$openingFinanceCost,
				$currentFinanceCost
			],

			'Depreciation' => [
				$openingDepreciation,
				$currentDepreciation
			],

			'Administrative Expenses' => [
				$openingAdministrativeExpense,
				$currentAdministrativeExpense
			],

			'Selling Expenses' => [
				$openingSellingExpense,
				$currentSellingExpense
			],

			'Other Expenses' => [
				$openingOtherExpense,
				$currentOtherExpense
			],
		];


		foreach (
			$expenseLedgerData
			as $ledger => [$opening, $current]
		) {

			$opening = (float) $opening;
			$current = (float) $current;


			$trial['Expenses']['Expenses'][$ledger] = [

				'ledger' => $ledger,

				'opening_dr' =>
					$opening >= 0
						? $opening
						: 0,

				'opening_cr' =>
					$opening < 0
						? abs($opening)
						: 0,

				'debit' =>
					$current >= 0
						? $current
						: 0,

				'credit' =>
					$current < 0
						? abs($current)
						: 0,

				'closing_dr' => 0,

				'closing_cr' => 0,
			];
		}


		/*
		|--------------------------------------------------------------------------
		| FILTER
		|--------------------------------------------------------------------------
		*/

		if (
			!empty($ledgerGroup)
			|| !empty($ledgerFilter)
		) {

			foreach ($trial as $mainGroup => &$subGroups) {

				if (
					!empty($ledgerGroup)
					&& $mainGroup !== $ledgerGroup
				) {

					unset(
						$trial[$mainGroup]
					);

					continue;
				}


				foreach (
					$subGroups
					as $subGroup => &$ledgers
				) {

					if (!empty($ledgerFilter)) {

						$ledgers =
							array_filter(
								$ledgers,
								function ($row)
								use ($ledgerFilter) {

									$name =
										$row['ledgername']
										?? $row['ledger']
										?? '';

									return stripos(
										$name,
										$ledgerFilter
									) !== false;
								}
							);
					}


					if (empty($ledgers)) {

						unset(
							$subGroups[$subGroup]
						);
					}
				}


				if (empty($subGroups)) {

					unset(
						$trial[$mainGroup]
					);
				}
			}


			unset(
				$subGroups,
				$ledgers
			);
		}


		/*
		|--------------------------------------------------------------------------
		| CALCULATE CLOSING
		|--------------------------------------------------------------------------
		|
		| Closing = Opening + Current Period Movement
		|
		|--------------------------------------------------------------------------
		*/

		$openingDrTotal = 0;
		$openingCrTotal = 0;

		$totalDr = 0;
		$totalCr = 0;


		foreach ($trial as &$groups) {

			foreach ($groups as &$ledgers) {

				foreach ($ledgers as &$row) {

					$openingDr =
						round(
							(float)
							($row['opening_dr'] ?? 0),
							2
						);

					$openingCr =
						round(
							(float)
							($row['opening_cr'] ?? 0),
							2
						);

					$debit =
						round(
							(float)
							($row['debit'] ?? 0),
							2
						);

					$credit =
						round(
							(float)
							($row['credit'] ?? 0),
							2
						);


					$openingDrTotal +=
						$openingDr;

					$openingCrTotal +=
						$openingCr;


					/*
					 * Calculate closing
					 */
					$dr = $openingDr + $debit;
					$cr = $openingCr + $credit;

					if ($dr > $cr) {
						$row['closing_dr'] = round($dr - $cr,2);
						$row['closing_cr'] = 0;
					} elseif ($cr > $dr) {
						$row['closing_dr'] = 0;
						$row['closing_cr'] = round($cr - $dr,2);
					} else {
						$row['closing_dr'] = 0;
						$row['closing_cr'] = 0;
					}


					$totalDr += $row['closing_dr'];

					$totalCr += $row['closing_cr'];
				}
			}
		}


		unset($groups,$ledgers,$row);

		/*
		|--------------------------------------------------------------------------
		| TOTALS
		|--------------------------------------------------------------------------
		*/

		$openingDrTotal =
			round(
				$openingDrTotal,
				2
			);

		$openingCrTotal =
			round(
				$openingCrTotal,
				2
			);

		$totalDr =
			round(
				$totalDr,
				2
			);

		$totalCr =
			round(
				$totalCr,
				2
			);


		$openingDifference =
			round(
				$openingDrTotal -
				$openingCrTotal,
				2
			);

		$closingDifference =
			round(
				$totalDr -
				$totalCr,
				2
			);


		/*
		|--------------------------------------------------------------------------
		| RESPONSE
		|--------------------------------------------------------------------------
		*/

		return response()->json([
			'success' => true,
			'trial' => $trial,
			'from_date' =>$fromDate,
			'to_date' =>$toDate,
			'financial_year' =>$financialYear,
			'financial_year_start' =>$financialYearStart,
			'opening_balance_sheet' => [
				'id' =>$baseBalanceSheet->id ?? null,
				'fy' =>$baseBalanceSheet->fy ?? null,
				'startYear' => $baseBalanceSheet->startYear ?? null,
				'endYear' =>$baseBalanceSheet->endYear ?? null,
			],

			'opening_balance_sheet_id' =>$baseBalanceSheet->id ?? null,
			'opening_balance_sheet_fy' =>$baseBalanceSheet->fy ?? null,
			'opening_balance_sheet_start_year' =>$baseBalanceSheet->startYear ?? null,
			'opening_balance_sheet_end_year' =>$baseBalanceSheet->endYear ?? null,

			'opening_dr' =>$openingDrTotal,
			'opening_cr' =>$openingCrTotal,
			'opening_difference' =>$openingDifference,
			'closing_dr' =>$totalDr,
			'closing_cr' =>$totalCr,
			'closing_difference' =>$closingDifference,
			'diff' =>$closingDifference,
		]);
	}


	
	public function fatch_trial_balance_data_old(Request $r)
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
		$prevToDate = date('Y-m-d', strtotime($fromDate.' -1 day'));
		$prevFromDate = date('Y-m-d', strtotime($fromDate . ' -20 years'));
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
					$current = $this->trialBalanceService->getCurrentAssetAmount($ledger,$userId,$fromDate,$toDate);
					$opening = $this->trialBalanceService->getCurrentAssetAmount($ledger,$userId,$prevFromDate,$prevToDate);
				} else {
					$current = $this->trialBalanceService->getNonCurrentAssetAmount($ledger,$userId,$fromDate,$toDate);
					$opening = $this->trialBalanceService->getNonCurrentAssetAmount($ledger,$userId,$prevFromDate,$prevToDate);
				}

				$trial['Assets'][$group][$ledger] = [
					'ledgername' => $ledger,
					'opening_dr' => $opening,
					'opening_cr' => 0,
					'debit' => $current,
					'credit' => 0,
					'closing_dr' => $current,
					'closing_cr' => 0,
				];
			}
		}
		//Equity head
		$periodType = 'full-yearly';
		$current_year_profit = $this->profitLossService->calculatePL($fromDate, $toDate, $userId, $periodType)['pbt'] ?? 0; 
		$opening_year_profit = $this->profitLossService->calculatePL($prevFromDate,$prevToDate, $userId, $periodType)['pbt'] ?? 0; 
		$equityHeads = [
			'share_capital'        => 'Share Capital',
			'reserves_surplus'     => 'Reserves & Surplus',
			'current_year_profit'    => 'Current Year Profit',
		];

		foreach ($equityHeads as $type => $ledger) {

			if ($type == 'current_year_profit') {
				$current = $current_year_profit;
				$opening = $opening_year_profit;
			} else {
				$current = $this->trialBalanceService->getEquityAmount($type,$userId,$fromDate,$toDate);
				$opening = $this->trialBalanceService->getEquityAmount($type,$userId,$prevFromDate,$prevToDate);
			}

			$trial['Equity'][''][$ledger] = [
				'ledgername'  => $ledger,
				'opening_dr'  => 0,
				'opening_cr'  => $opening,
				'debit'       => 0,
				'credit'      => $current,
				'closing_dr'  => 0,
				'closing_cr'  => $current,
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

			$current = $this->trialBalanceService->getCurrentLiabilityAmount($type,$userId,$fromDate,$toDate);
			$opening = $this->trialBalanceService->getCurrentLiabilityAmount($type,$userId,$prevFromDate,$prevToDate);

			$trial['Liabilities']['Current Liabilities'][ucwords(str_replace('_',' ',$type))] = [

				'ledger'      => ucwords(str_replace('_',' ',$type)),
				'opening_dr'  => 0,
				'opening_cr'  => $opening,
				'debit'       => 0,
				'credit'      => $current,
				'closing_dr'  => 0,
				'closing_cr'  => $current
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

			$current = $this->trialBalanceService->getNonCurrentLiabilityAmount($type,$userId,$fromDate,$toDate);
			$opening = $this->trialBalanceService->getNonCurrentLiabilityAmount($type,$userId,$prevFromDate,$prevToDate);

			$trial['Liabilities']['Non-Current Liabilities'][$ledger] = [
				'ledgername' => $ledger,
				'opening_dr' => 0,
				'opening_cr' => $opening,
				'debit'      => 0,
				'credit'     => $current,
				'closing_dr' => 0,
				'closing_cr' => $current,
			];
		}
		//Income
		$openingSales = DB::table('sales_values as sv')
			->join('sales as s', 's.id', '=', 'sv.sid')
			->where('s.added_by', $userId)
			->where('s.status', 1)
			->when($prevToDate, function ($q) use ($prevToDate) {
				$q->whereDate('s.inv_date', '<=', $prevToDate);
			})
			->sum('sv.amount');

		$openingSalesCreditNote = DB::table('vouchers')
			->where('added_by', $userId)
			->where('note_type', 'Credit')
			->when($prevToDate, function ($q) use ($prevToDate) {
				$q->whereDate('inv_date', '<=', $prevToDate);
			})
			->sum('taxable_value');

		$openingRevenue = $openingSales - $openingSalesCreditNote;

		$currentSales = DB::table('sales_values as sv')
			->join('sales as s', 's.id', '=', 'sv.sid')
			->where('s.added_by', $userId)
			->where('s.status', 1)
			->whereBetween('s.inv_date', [$fromDate, $toDate])
			->sum('sv.amount');

		$currentSalesCreditNote = DB::table('vouchers')
			->where('added_by', $userId)
			->where('note_type', 'Credit')
			->whereBetween('inv_date', [$fromDate, $toDate])
			->sum('taxable_value');

		$currentRevenue = $currentSales - $currentSalesCreditNote;


		// ================= Other Income =================
		$openingOtherIncome = DB::table('income')
			->where('addBy', $userId)
			->where('status', 1)
			->when($prevToDate, function ($q) use ($prevToDate) {
				$q->whereDate('dateInput', '<=', $prevToDate);
			})
			->sum('amount');

		$currentOtherIncome = DB::table('income')
			->where('addBy', $userId)
			->where('status', 1)
			->whereBetween('dateInput', [$fromDate, $toDate])
			->sum('amount');
			
		$trial['Income']['Income']['Revenue from Operations'] = [
			'ledger'      => 'Revenue from Operations',
			'opening_dr'  => 0,
			'opening_cr'  => $openingRevenue,
			'debit'       => 0,
			'credit'      => $currentRevenue,
		];

		$trial['Income']['Income']['Other Income'] = [
			'ledger'      => 'Other Income',
			'opening_dr'  => 0,
			'opening_cr'  => $openingOtherIncome,
			'debit'       => 0,
			'credit'      => $currentOtherIncome,
		];
		//Expenses
		// ================= Cost of Goods Sold =================
		$currentPurchase = DB::table('purchase_values as pv')
			->join('purchases as p','p.id','=','pv.sid')
			->where('p.added_by',$userId)
			->where('p.status',1)
			->whereBetween('p.inv_date',[$fromDate,$toDate])
			->sum('pv.amount');

		$currentPurchaseDebitNote = DB::table('voucher_purchases')
			->where('added_by',$userId)
			->where('note_type','Debit')
			->whereBetween('inv_date',[$fromDate,$toDate])
			->sum('taxable_value');

		$currentCOGS = $currentPurchase - $currentPurchaseDebitNote;

		$openingPurchase = DB::table('purchase_values as pv')
			->join('purchases as p','p.id','=','pv.sid')
			->where('p.added_by',$userId)
			->where('p.status',1)
			->whereDate('p.inv_date','<=',$prevToDate)
			->sum('pv.amount');

		$openingPurchaseDebitNote = DB::table('voucher_purchases')
			->where('added_by',$userId)
			->where('note_type','Debit')
			->whereDate('inv_date','<=',$prevToDate)
			->sum('taxable_value');

		$openingCOGS = $openingPurchase - $openingPurchaseDebitNote;
		
		//(DIRECT EXPENSES)		
		$currentDirectExpenses = DB::table('expenses')
			->select('expense_type', DB::raw('SUM(expense_amt) amount'))
			->where('added_by',$userId)
			->where('expense_cat','direct')
			->whereBetween('expense_date',[$fromDate,$toDate])
			->groupBy('expense_type')
			->pluck('amount','expense_type');

		$openingDirectExpenses = DB::table('expenses')
			->select('expense_type', DB::raw('SUM(expense_amt) amount'))
			->where('added_by',$userId)
			->where('expense_cat','direct')
			->whereDate('expense_date','<=',$prevToDate)
			->groupBy('expense_type')
			->pluck('amount','expense_type');

		$expenseTypes = collect($currentDirectExpenses)
			->keys()
			->merge($openingDirectExpenses->keys())
			->unique();

		foreach ($expenseTypes as $type) {

			$trial['Expenses']['Direct Expenses'][$type] = [
				'ledger'      => ucwords(str_replace('_',' ',$type)),
				'opening_dr'  => $openingDirectExpenses[$type] ?? 0,
				'opening_cr'  => 0,
				'debit'       => $currentDirectExpenses[$type] ?? 0,
				'credit'      => 0,
			];
		}
			
		//(INDIRECT EXPENSES) 
		//Employee Benefit Expenses
		$currentEmployeeBenefit = DB::table('expenses')
			->where('added_by',$userId)
			->where('expense_type','employee_benefits')
			->whereBetween('expense_date',[$fromDate,$toDate])
			->sum('expense_amt');

		$openingEmployeeBenefit = DB::table('expenses')
			->where('added_by',$userId)
			->where('expense_type','employee_benefits')
			->whereDate('expense_date','<=',$prevToDate)
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

		$currentAdministrativeExpense = DB::table('expenses')
			->where('added_by',$userId)
			->whereIn('expense_type',$adminTypes)
			->whereBetween('expense_date',[$fromDate,$toDate])
			->sum('expense_amt');

		$openingAdministrativeExpense = DB::table('expenses')
			->where('added_by',$userId)
			->whereIn('expense_type',$adminTypes)
			->whereDate('expense_date','<=',$prevToDate)
			->sum('expense_amt');
		//Finance Cost
		$financeTypes = [
			'interest_expense',
			'bank_charges',
			'loan_interest'
		];

		$currentFinanceCost = DB::table('expenses')
			->where('added_by',$userId)
			->whereIn('expense_type',$financeTypes)
			->whereBetween('expense_date',[$fromDate,$toDate])
			->sum('expense_amt');

		$openingFinanceCost = DB::table('expenses')
			->where('added_by',$userId)
			->whereIn('expense_type',$financeTypes)
			->whereDate('expense_date','<=',$prevToDate)
			->sum('expense_amt');
		//Selling Expenses
		$sellingTypes = [
			'advertisement',
			'sales_commission',
			'marketing_expense',
			'freight_outward'
		];
		// Depreciation
		$currentDepreciation = DB::table('assets')
			->where('added_by',$userId)
			->whereBetween('date',[$fromDate,$toDate])
			->sum('depreciation_value');

		$openingDepreciation = DB::table('assets')
			->where('added_by',$userId)
			->whereDate('date','<=',$prevToDate)
			->sum('depreciation_value');
		//Other Expenses
		$usedTypes = array_merge(
			['employee_benefits'],
			$adminTypes,
			$financeTypes,
			$sellingTypes
		);

		$currentOtherExpense = DB::table('expenses')
			->where('added_by',$userId)
			->whereNotIn('expense_type',$usedTypes)
			->where('expense_cat','indirect')
			->whereBetween('expense_date',[$fromDate,$toDate])
			->sum('expense_amt');

		$openingOtherExpense = DB::table('expenses')
			->where('added_by',$userId)
			->whereNotIn('expense_type',$usedTypes)
			->where('expense_cat','indirect')
			->whereDate('expense_date','<=',$prevToDate)
			->sum('expense_amt');

		$currentSellingExpense = DB::table('expenses')
			->where('added_by',$userId)
			->whereIn('expense_type',$sellingTypes)
			->whereBetween('expense_date',[$fromDate,$toDate])
			->sum('expense_amt');

		$openingSellingExpense = DB::table('expenses')
			->where('added_by',$userId)
			->whereIn('expense_type',$sellingTypes)
			->whereDate('expense_date','<=',$prevToDate)
			->sum('expense_amt');
			
		$trial['Expenses']['Expenses']['Cost of Goods Sold'] = [
			'ledger'      => 'Cost of Goods Sold',
			'opening_dr'  => $openingCOGS,
			'opening_cr'  => 0,
			'debit'       => $currentCOGS,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Employee Benefit Expenses'] = [
			'ledger'      => 'Employee Benefit Expenses',
			'opening_dr'  => $openingEmployeeBenefit,
			'opening_cr'  => 0,
			'debit'       => $currentEmployeeBenefit,
			'credit'      => 0,
		];
		$trial['Expenses']['Expenses']['Finance Cost'] = [
			'ledger'      => 'Finance Cost',
			'opening_dr'  => $openingFinanceCost,
			'opening_cr'  => 0,
			'debit'       => $currentFinanceCost,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Depreciation'] = [
			'ledger'      => 'Depreciation',
			'opening_dr'  => $openingDepreciation,
			'opening_cr'  => 0,
			'debit'       => $currentDepreciation,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Administrative Expenses'] = [
			'ledger'      => 'Administrative Expenses',
			'opening_dr'  => $openingAdministrativeExpense,
			'opening_cr'  => 0,
			'debit'       => $currentAdministrativeExpense,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Selling Expenses'] = [
			'ledger'      => 'Selling Expenses',
			'opening_dr'  => $openingSellingExpense,
			'opening_cr'  => 0,
			'debit'       => $currentSellingExpense,
			'credit'      => 0,
		];

		$trial['Expenses']['Expenses']['Other Expenses'] = [
			'ledger'      => 'Other Expenses',
			'opening_dr'  => $openingOtherExpense,
			'opening_cr'  => 0,
			'debit'       => $currentOtherExpense,
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
		$companyProfile = DB::table('company_profiles')
			->where('userId', $userId)
			->first();

		$companyOpeningDr = (float) ($companyProfile->openingbalancedr ?? 0);
		$companyOpeningCr = (float) ($companyProfile->openingbalancecr ?? 0);

		$openingDrTotal = 0;
		$openingCrTotal = 0;
		$totalDr = 0;
		$totalCr = 0;

		foreach ($trial as &$groups) {

			foreach ($groups as &$ledgers) {

				foreach ($ledgers as &$row) {

					$openingDr = max(0, (float) ($row['opening_dr'] ?? 0));
					$openingCr = max(0, (float) ($row['opening_cr'] ?? 0));

					$debit  = max(0, (float) ($row['debit'] ?? 0));
					$credit = max(0, (float) ($row['credit'] ?? 0));

					$openingDrTotal += $openingDr;
					$openingCrTotal += $openingCr;

					$dr = $openingDr + $debit;
					$cr = $openingCr + $credit;

					if ($dr > $cr) {

						$row['closing_dr'] = round($dr - $cr, 2);
						$row['closing_cr'] = 0;

					} elseif ($cr > $dr) {

						$row['closing_dr'] = 0;
						$row['closing_cr'] = round($cr - $dr, 2);

					} else {

						$row['closing_dr'] = 0;
						$row['closing_cr'] = 0;
					}

					$totalDr += $row['closing_dr'];
					$totalCr += $row['closing_cr'];
				}
			}
		}

		unset($groups, $ledgers, $row);

		$openingDrTotal += $companyOpeningDr;
		$openingCrTotal += $companyOpeningCr;

		$openingDifference = $openingDrTotal - $openingCrTotal;

		if ($openingDifference > 0) {
			$totalDr += $openingDifference;
		} elseif ($openingDifference < 0) {
			$totalCr += abs($openingDifference);
		}

		return response()->json([
			'success'    => true,
			'trial'      => $trial,
			'opening_dr' => round($openingDrTotal, 2),
			'opening_cr' => round($openingCrTotal, 2),
			'closing_dr' => round($totalDr, 2),
			'closing_cr' => round($totalCr, 2),
			'diff'       => round($totalCr - $totalDr, 2),
		]);	
		
		/*$openingDrTotal = 0;
		$openingCrTotal = 0;
		$totalDr = 0;
		$totalCr = 0;

		foreach ($trial as &$groups) {
			foreach ($groups as &$ledgers) {
				foreach ($ledgers as &$row) {

					$openingDr = max(0, (float)$row['opening_dr']);
					$openingCr = max(0, (float)$row['opening_cr']);

					$debit  = max(0, (float)$row['debit']);
					$credit = max(0, (float)$row['credit']);

					$openingDrTotal += $openingDr;
					$openingCrTotal += $openingCr;

					$dr = $openingDr + $debit;
					$cr = $openingCr + $credit;
					
					if ($dr > $cr) {
						$row['closing_dr'] = round($dr - $cr, 2);
						$row['closing_cr'] = 0;
					} elseif ($cr > $dr) {
						$row['closing_dr'] = 0;
						$row['closing_cr'] = round($cr - $dr, 2);
					} else {
						$row['closing_dr'] = 0;
						$row['closing_cr'] = 0;
					}

					$totalDr += $row['closing_dr'];
					$totalCr += $row['closing_cr'];
				}
			}
		}
		
		return response()->json([
			'success'    => true,
			'trial'      => $trial,
			'opening_dr' => round($openingDrTotal, 2),
			'opening_cr' => round($openingCrTotal, 2),
			'closing_dr' => round($totalDr, 2),
			'closing_cr' => round($totalCr, 2),
			'diff'   	 => round($totalCr, 2) - round($totalDr, 2),
		]);*/
		
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
