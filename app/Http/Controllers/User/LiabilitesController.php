<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Redirect;
// use DB;
// use Auth;
// use Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


use App\Models\Liabilities;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Customers;
use App\Helpers\AuditLogger;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class LiabilitesController extends Controller
{
	public function __construct(JournalService $journalService, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
		$this->paymentVoucherService = $paymentVoucherService;
    }
	
	public function Liabilites(request $request)
	{
		$title = 'Liabilities';
		$user = Auth::user();
		$userId = currentOwnerId();
		checkCoreAccess('Liabilities & Borrowings');

		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access

		// Financial Year
		// $startFY = now()->month >= 4
		// 	? now()->startOfYear()->addMonths(3)
		// 	: now()->subYear()->startOfYear()->addMonths(3);

		// $endFY = $startFY->copy()->addYear()->subDay();

		// Current date
		$today = Carbon::now();

		// Financial Year Start (1 April)
		if ($today->month >= 4) {
			$startFY = Carbon::create($today->year, 4, 1); // 1 April current year
			$endFY   = Carbon::create($today->year + 1, 3, 31); // 31 March next year
		} else {
			$startFY = Carbon::create($today->year - 1, 4, 1); // 1 April previous year
			$endFY   = Carbon::create($today->year, 3, 31); // 31 March current year
		}
		

		$shareHolderFund = DB::table('share_holder_fund_liabilities as s')
						->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
						->where('l.added_by', $userId)
						->where('l.status', 1)
						->whereBetween('l.added_date', [
							$startFY->toDateString(),
							$endFY->toDateString()
						])
						->selectRaw("
							SUM(
								CASE

									WHEN s.share_holder_fund_type = 'reserves_surplus'
										AND s.reserves_surplus_type = 'transfer_to_reserve'
									THEN COALESCE(s.transfer_amount, 0)

									WHEN s.share_holder_fund_type = 'reserves_surplus'
										AND s.reserves_surplus_type = 'opening_balance'
									THEN COALESCE(s.opening_balance, 0)

									WHEN s.share_holder_fund_type = 'reserves_surplus'
										AND s.reserves_surplus_type = 'dividend_declaration'
									THEN COALESCE(s.total_dividend_amount, 0)

									WHEN s.share_holder_fund_type = 'share_capital'
									THEN COALESCE(s.total_amount, 0)

									ELSE 0

								END
							) as total
						")
						->value('total') ?? 0;

		$shareAppMoney = DB::table('share_application_money_liabilities as s')
					->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
					->where('l.added_by', $userId)
					->where('l.status', 1)
					->whereBetween('l.added_date', [
						$startFY->toDateString(),
						$endFY->toDateString()
					])
					->sum('s.amount_received') ?? 0;

		$nonCurrent = DB::table('non_current_liabilities as n')
				->join('liabilities as l', 'l.id', '=', 'n.liabilities_id')
				->where('l.added_by', $userId)
				->where('l.status', 1)
				->whereBetween('l.added_date', [
					$startFY->toDateString(),
					$endFY->toDateString()
				])
				->selectRaw("
					SUM(
						CASE

							WHEN n.liability_category = 'deferred_tax_liabilities'
							THEN COALESCE(n.dtl_difference_accounting, 0)

							ELSE COALESCE(n.amount, 0)

						END
					) as total
				")
				->value('total') ?? 0;


		$current = DB::table('current_liabilities as c')
				->join('liabilities as l', 'l.id', '=', 'c.liabilities_id')
				->where('l.added_by', $userId)
				->where('l.status', 1)
				->whereBetween('l.added_date', [
					$startFY->toDateString(),
					$endFY->toDateString()
				])
				->selectRaw("
					SUM(
						CASE

							WHEN c.CurrentLiabilitiesType = 'short_term_loans'
							THEN COALESCE(c.stl_sanction_amount, 0)

							WHEN c.CurrentLiabilitiesType = 'interest_payable'
							THEN COALESCE(c.ip_principal_amount, 0)

							ELSE COALESCE(c.amount, 0)

						END
					) as total
				")
				->value('total') ?? 0;

		$totalLiabilities = $shareHolderFund + $shareAppMoney + $nonCurrent + $current;
			
		$liabilities = DB::table('liabilities as l')
			->leftJoin('company_profiles as cp', 'cp.userId', '=', 'l.added_by')
			->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'l.propId')
			->select(
				'l.*',
				DB::raw("
					CASE
						WHEN l.propId IS NOT NULL AND l.propId != ''
						THEN pp.comp_name
						ELSE cp.comp_name
					END as comp_name
				")
			)
			->where('l.added_by', $userId)
			->where('l.status', '1')
			->orderBy('l.id', 'DESC')
			->get()
			->map(function ($row) use ($userId, $startFY, $endFY) {

				switch ($row->liabilities_type) {

					case 'share_holder_fund':
						$row->amount = DB::table('share_holder_fund_liabilities as s')
							->where('s.liabilities_id', $row->id)
							->selectRaw("
								SUM(
									CASE

										WHEN s.share_holder_fund_type = 'reserves_surplus'
											AND s.reserves_surplus_type = 'transfer_to_reserve'
										THEN COALESCE(s.transfer_amount, 0)

										WHEN s.share_holder_fund_type = 'reserves_surplus'
											AND s.reserves_surplus_type = 'opening_balance'
										THEN COALESCE(s.opening_balance, 0)

										WHEN s.share_holder_fund_type = 'reserves_surplus'
											AND s.reserves_surplus_type = 'dividend_declaration'
										THEN COALESCE(s.total_dividend_amount, 0)

										WHEN s.share_holder_fund_type = 'share_capital'
										THEN COALESCE(s.total_amount, 0)

										ELSE 0

									END
								) as amount
							")
							->value('amount') ?? 0;

						break;

					case 'share_application_money':
						$row->amount = DB::table('share_application_money_liabilities')
							->where('liabilities_id', $row->id)
							->sum('amount_received') ?? 0;
						break;

					case 'non_current_liabilities':

						$row->amount = DB::table('non_current_liabilities as n')
							->where('n.liabilities_id', $row->id)
							->selectRaw("
								SUM(
									CASE

										WHEN n.liability_category = 'deferred_tax_liabilities'
										THEN COALESCE(n.dtl_difference_accounting, 0)

										ELSE COALESCE(n.amount, 0)

									END
								) as amount
							")
							->value('amount') ?? 0;

						break;

					case 'current_liabilities':
						$row->amount = DB::table('current_liabilities as c')
							->where('c.liabilities_id', $row->id)
							->selectRaw("
								SUM(
									CASE

										WHEN c.CurrentLiabilitiesType = 'short_term_loans'
										THEN COALESCE(c.stl_sanction_amount, 0)

										WHEN c.CurrentLiabilitiesType = 'interest_payable'
										THEN COALESCE(c.ip_principal_amount, 0)

										ELSE COALESCE(c.amount, 0)

									END
								) as amount
							")
							->value('amount') ?? 0;

						break;

					default:
						$row->amount = 0;
				}

				return $row;
			});

		// Return view with data
		// echo '<pre>';
		// print_r($liabilities); die();
		
		return view('User.liabilites', compact(
			'title',
			'liabilities',
			'shareHolderFund',
			'shareAppMoney',
			'nonCurrent',
			'current',
			'totalLiabilities',
			'req_type'
		));
	}
	
	public function journalEntry($lid)
	{
		$uid = currentOwnerId();

		$liability = DB::table('liabilities')
			->where('id', $lid)
			->where('added_by', $uid)
			->first();

		$current = DB::table('current_liabilities')
			->where('liabilities_id', $lid)
			->first();

		$nonCurrent = DB::table('non_current_liabilities')
			->where('liabilities_id', $lid)
			->first();

		$shareHolder = DB::table('share_holder_fund_liabilities')
			->where('liabilities_id', $lid)
			->first();

		$shareApplication = DB::table('share_application_money_liabilities')
			->where('liabilities_id', $lid)
			->first();

		// ============================================
		// DEFAULTS
		// ============================================

		$amount        = 0;
		$party         = '';
		$dc            = 'Credit';
		$gstApplicable = 'no';
		$gstRate       = 0;
		$gstTrans      = '';
		$ledgerName    = 'Liability';

		// ============================================
		// CURRENT LIABILITY
		// ============================================
		$journalCurrentLiabilityTypes = ['short_term_loans','interest_payable',];
		if ($current) {
			$clType = strtolower(trim($current->CurrentLiabilitiesType ?? ''));
			if (!in_array($clType, $journalCurrentLiabilityTypes)) {
				return true;
			}

			if ($clType === 'short_term_loans') {
				$amount = $current->stl_sanction_amount ?? 0;
				$party = $current->stl_lender_name ?? $current->party_name ?? '';
				$ledgerName = 'Short Term Loans';
			}

			elseif ($clType === 'interest_payable') {
				$amount = $current->ip_interest_amount ?? 0;
				$party = $current->ip_lender_name ?? $current->party_name ?? '';
				$ledgerName = 'Interest Payable';
			}

			$dc            = $current->debit_credit ?? 'Credit';
			$gstApplicable = $current->gst_applicable ?? 'no';
			$gstRate       = $current->gst_rate ?? 0;
			$gstTrans      = $current->gst_transaction ?? '';
		}

		// ============================================
		// NON CURRENT LIABILITY
		// ============================================

		else if ($nonCurrent) {

			$liabilityType = strtolower($nonCurrent->liability_category ?? '');

			// -------------------------------
			// DEFERRED TAX LIABILITY
			// -------------------------------

			if ($liabilityType == 'long_term_borrowings') {
				$amount = $nonCurrent->amount ?? 0;
				$ledgerName = 'Long-term Borrowings';
			}

			// -------------------------------
			// LEASE LIABILITY
			// -------------------------------

			else if ($liabilityType == 'other_financial_liabilities') {
				$amount = $nonCurrent->amount ?? 0;
				$ledgerName = 'Other Financial Liabilities';
			}

			// -------------------------------
			// LONG TERM PROVISION
			// -------------------------------

			else if ($liabilityType == 'long_term_provisions') {
				$amount = $nonCurrent->amount ?? 0;
				$ledgerName = 'Long-term Provisions';
			}
			
			else if ($liabilityType == 'other_non_current_liabilities') {
				$amount = $nonCurrent->amount ?? 0;
				$ledgerName = 'Other Non-Current Liabilities';
			}

			// -------------------------------
			// DEFAULT NON CURRENT
			// -------------------------------

			else {
				$amount =  0;
				$ledgerName = $nonCurrent->category_of_head ?? 'Non Current Liability';
			}

			$party         = $nonCurrent->party_name ?? '';
			$dc            = $nonCurrent->debit_credit ?? 'Credit';
			$gstApplicable = $nonCurrent->gst_applicable ?? 'no';
			$gstRate       = $nonCurrent->gst_rate ?? 0;
			$gstTrans      = $nonCurrent->gst_transaction ?? '';
		}

		// ============================================
		// SHARE APPLICATION MONEY
		// ============================================

		else if ($shareApplication) {
			$amount = $shareApplication->amount_received ?? 0;
			$party = $shareApplication->applicant_name ?? '';
			$dc = 'Credit';
			$ledgerName = 'Share Application Money';
			$gstApplicable = 'no';
			$gstRate = 0;
			$gstTrans = '';
		}

		// ============================================
		// SHARE HOLDER FUND
		// ============================================

		else if ($shareHolder) {
			$reserveType = strtolower($shareHolder->reserves_surplus_type ?? '');
			// TRANSFER TO RESERVE
			if ($reserveType == 'transfer_to_reserve') {
				$amount = $shareHolder->transfer_amount ?? 0;
			}
			// DIVIDEND DECLARATION
			else if ($reserveType == 'dividend_declaration') {

				$amount = $shareHolder->total_dividend_amount ?? 0;
			}
			else {

				$amount = $shareHolder->total_amount ?? 0;
			}

			$party = $shareHolder->payto ?? 'Share Holder';
			$dc = 'Credit';
			$ledgerName = $shareHolder->share_holder_fund_type ?? 'Shareholder Fund';
			$gstApplicable = 'no';
			$gstRate = 0;
			$gstTrans = '';
		}

		// ============================================
		// STORE JOURNAL ENTRY
		// ============================================
		if($amount > 0){
			$this->journalService->storeLiabilityJournalEntries([
				'source'         => 'Liability',
				'autoId'         => $lid,
				'added_by'       => $uid,
				'propId'         => $liability->propId ?? null,
				'date'           => $liability->added_date,
				'entry_type'     => 'Liability',
				'ledger_name'    => $ledgerName,
				'party_name'     => $party,
				'amount'         => $amount,
				'debit_credit'   => $dc,
				'gst_applicable' => $gstApplicable,
				'gst_rate'       => $gstRate,
				'gst_trans'      => $gstTrans,
				'status'         => $liability->status,
			]);
		}
	}
	

	public function fetchDetails(Request $request, $type)
	{
		$user = Auth::user();
		$userId = currentOwnerId();

		// Financial Year
		$today = Carbon::now();

		// Financial Year Start (1 April)
		if ($today->month >= 4) {
			$startFY = Carbon::create($today->year, 4, 1);
			$endFY   = Carbon::create($today->year + 1, 3, 31);
		} else {
			$startFY = Carbon::create($today->year - 1, 4, 1);
			$endFY   = Carbon::create($today->year, 3, 31);
		}

		switch ($type) {

			case 'share-holder':

				$query = DB::table('share_holder_fund_liabilities as s')
					->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
					->where('l.added_by', $userId)
					->where('l.status', 1)
					->whereBetween('l.added_date', [
						$startFY->toDateString(),
						$endFY->toDateString()
					])
					->select(
						's.*',
						'l.added_date',

						DB::raw("
							CASE 
								WHEN s.share_holder_fund_type = 'reserves_surplus'
								THEN s.surplusdate
								ELSE NULL
							END as surplus_date
						"),

						DB::raw("
							CASE

								WHEN s.share_holder_fund_type = 'reserves_surplus'
									AND s.reserves_surplus_type = 'transfer_to_reserve'
								THEN COALESCE(s.transfer_amount,0)

								WHEN s.share_holder_fund_type = 'reserves_surplus'
									AND s.reserves_surplus_type = 'opening_balance'
								THEN COALESCE(s.opening_balance,0)

								WHEN s.share_holder_fund_type = 'reserves_surplus'
									AND s.reserves_surplus_type = 'dividend_declaration'
								THEN COALESCE(s.total_dividend_amount,0)

								WHEN s.share_holder_fund_type = 'share_capital'
								THEN COALESCE(s.total_amount,0)

								ELSE 0

							END as amount
						")
					)
					->orderBy('l.added_date', 'desc');

				break;

			case 'share-app':

				$query = DB::table('share_application_money_liabilities as s')
					->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
					->where('l.added_by', $userId)
					->where('l.status', 1)
					->whereBetween('l.added_date', [
						$startFY->toDateString(),
						$endFY->toDateString()
					])
					->select(
						's.*',
						'l.added_date',
						DB::raw('COALESCE(s.amount_received,0) as amount')
					)
					->orderBy('l.added_date', 'desc');

				break;

			case 'non-current':

				$query = DB::table('non_current_liabilities as n')
					->join('liabilities as l', 'l.id', '=', 'n.liabilities_id')
					->where('l.added_by', $userId)
					->where('l.status', 1)
					->whereBetween('l.added_date', [
						$startFY->toDateString(),
						$endFY->toDateString()
					])
					->select(
						'n.*',
						'l.added_date',

						DB::raw("
							CASE
								WHEN n.liability_category = 'deferred_tax_liabilities'
								THEN COALESCE(n.dtl_difference_accounting, 0)

								ELSE COALESCE(n.amount, 0)

							END as amount
						")
					)
					->orderBy('l.added_date', 'desc');

				break;

			case 'current':

				$query = DB::table('current_liabilities as c')
					->join('liabilities as l', 'l.id', '=', 'c.liabilities_id')
					->where('l.added_by', $userId)
					->where('l.status', 1)
					->whereBetween('l.added_date', [
						$startFY->toDateString(),
						$endFY->toDateString()
					])
					->select(
						'c.*',
						'l.added_date',

						DB::raw("
							CASE

								WHEN c.CurrentLiabilitiesType = 'short_term_loans'
								THEN COALESCE(c.stl_sanction_amount, 0)

								WHEN c.CurrentLiabilitiesType = 'interest_payable'
								THEN COALESCE(c.ip_principal_amount, 0)

								ELSE COALESCE(c.amount, 0)

							END as amount
						")
					)
					->orderBy('l.added_date', 'desc');

				break;

			case 'total':

				$shareHolder = DB::table('share_holder_fund_liabilities as s')
					->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
					->where('l.added_by', $userId)
					->where('l.status', 1)
					->whereBetween('l.added_date', [
						$startFY->toDateString(),
						$endFY->toDateString()
					])
					->select(
						DB::raw("'Share Holder Fund' as category"),
						'l.added_date',

						DB::raw("
							CASE

								WHEN s.share_holder_fund_type = 'reserves_surplus'
									AND s.reserves_surplus_type = 'transfer_to_reserve'
								THEN COALESCE(s.transfer_amount,0)

								WHEN s.share_holder_fund_type = 'reserves_surplus'
									AND s.reserves_surplus_type = 'opening_balance'
								THEN COALESCE(s.opening_balance,0)

								WHEN s.share_holder_fund_type = 'reserves_surplus'
									AND s.reserves_surplus_type = 'dividend_declaration'
								THEN COALESCE(s.total_dividend_amount,0)

								WHEN s.share_holder_fund_type = 'share_capital'
								THEN COALESCE(s.total_amount,0)

								ELSE 0

							END as amount
						")
					);

				$shareApp = DB::table('share_application_money_liabilities as s')
					->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
					->where('l.added_by', $userId)
					->whereBetween('l.added_date', [$startFY, $endFY])
					->select(
						DB::raw("'Share Application Money' as category"),
						'l.added_date',
						DB::raw('COALESCE(s.amount_received,0) as amount')
					);

				$nonCurrent = DB::table('non_current_liabilities as n')
					->join('liabilities as l', 'l.id', '=', 'n.liabilities_id')
					->where('l.added_by', $userId)
					->whereBetween('l.added_date', [$startFY, $endFY])
					->select(
						DB::raw("'Non Current Liability' as category"),
						'l.added_date',

						DB::raw("
							CASE

								WHEN n.liability_category = 'deferred_tax_liabilities'
								THEN COALESCE(n.dtl_difference_accounting, 0)

								ELSE COALESCE(n.amount, 0)

							END as amount
						")
					);

				$current = DB::table('current_liabilities as c')
					->join('liabilities as l', 'l.id', '=', 'c.liabilities_id')
					->where('l.added_by', $userId)
					->whereBetween('l.added_date', [$startFY, $endFY])
					->select(
						DB::raw("'Current Liability' as category"),
						'l.added_date',

						DB::raw("
							CASE

								WHEN c.CurrentLiabilitiesType = 'short_term_loans'
								THEN COALESCE(c.stl_sanction_amount, 0)

								WHEN c.CurrentLiabilitiesType = 'interest_payable'
								THEN COALESCE(c.ip_principal_amount, 0)

								ELSE COALESCE(c.amount, 0)

							END as amount
						")
					);

				$union = $shareHolder
					->unionAll($shareApp)
					->unionAll($nonCurrent)
					->unionAll($current);

				$query = DB::query()
					->fromSub($union, 't')
					->orderBy('added_date', 'desc');

				break;

			default:
				return response()->json(['data' => []]);
		}

		$data = $query->paginate(10);

		return view('partials.liabilities-modal-table', compact('data'))->render();
	}

	// public function fetchDetails(Request $request, $type)
	// {
	// 	$user = Auth::user();
	// 	$userId = currentOwnerId();

	// 	// Financial Year
	// 	$today = Carbon::now();

	// 	// Financial Year Start (1 April)
	// 	if ($today->month >= 4) {
	// 		$startFY = Carbon::create($today->year, 4, 1); // 1 April current year
	// 		$endFY   = Carbon::create($today->year + 1, 3, 31); // 31 March next year
	// 	} else {
	// 		$startFY = Carbon::create($today->year - 1, 4, 1); // 1 April previous year
	// 		$endFY   = Carbon::create($today->year, 3, 31); // 31 March current year
	// 	}

	// 	switch ($type) {

	// 		case 'share-holder':

	// 			$query = DB::table('share_holder_fund_liabilities as s')
	// 				->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
	// 				->where('l.added_by', $userId)
	// 				->where('l.status', 1)
	// 				->whereBetween('l.added_date', [
	// 					$startFY->toDateString(),
	// 					$endFY->toDateString()
	// 				])
	// 				->select(
	// 					's.*',
	// 					'l.added_date',

	// 					DB::raw("
	// 						CASE 
	// 							WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 							THEN s.surplusdate
	// 							ELSE NULL
	// 						END as surplus_date
	// 					"),

	// 					DB::raw("
	// 						CASE

	// 							WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 								AND s.reserves_surplus_type = 'transfer_to_reserve'
	// 							THEN COALESCE(s.transfer_amount,0)

	// 							WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 								AND s.reserves_surplus_type = 'opening_balance'
	// 							THEN COALESCE(s.opening_balance,0)

	// 							WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 								AND s.reserves_surplus_type = 'dividend_declaration'
	// 							THEN COALESCE(s.total_dividend_amount,0)

	// 							WHEN s.share_holder_fund_type = 'share_capital'
	// 							THEN COALESCE(s.total_amount,0)

	// 							ELSE 0

	// 						END as amount
	// 					")
	// 				)
	// 				->orderBy('l.added_date', 'desc');

	// 			break;

	// 		case 'share-app':

	// 			$query = DB::table('share_application_money_liabilities as s')
	// 				->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
	// 				->where('l.added_by', $userId)
	// 				->where('l.status', 1)
	// 				->whereBetween('l.added_date', [
	// 					$startFY->toDateString(),
	// 					$endFY->toDateString()
	// 				])
	// 				->select(
	// 					's.*',
	// 					'l.added_date',
	// 					DB::raw('COALESCE(s.amount_received,0) as amount')
	// 				)
	// 				->orderBy('l.added_date', 'desc');

	// 			break;

	// 		case 'non-current':
	// 			$query = DB::table('non_current_liabilities as n')
	// 				->join('liabilities as l', 'l.id', '=', 'n.liabilities_id')
	// 				->where('l.added_by', $userId)
	// 				->where('l.status', 1)
	// 				->whereBetween('l.added_date', [
	// 					$startFY->toDateString(),
	// 					$endFY->toDateString()
	// 				])
	// 				->select(
	// 					'n.*',
	// 					'l.added_date',
	// 					DB::raw("
	// 						CASE
	// 							WHEN n.liability_category = 'deferred_tax_liabilities'
	// 							THEN COALESCE(n.dtl_difference_accounting, 0)
	// 							ELSE COALESCE(n.amount, 0)
	// 						END as amount
	// 					")
	// 				)->orderBy('l.added_date', 'desc');
	// 			break;

	// 		case 'current':
	// 			$query = DB::table('current_liabilities as c')
	// 				->join('liabilities as l', 'l.id', '=', 'c.liabilities_id')
	// 				->where('l.added_by', $userId)
	// 				->where('l.status', 1)
	// 				->whereBetween('l.added_date', [
	// 					$startFY->toDateString(),
	// 					$endFY->toDateString()
	// 				])
	// 				->select(
	// 					'c.*',
	// 					'l.added_date',
	// 					DB::raw('COALESCE(c.amount, 0) as amount')
	// 				)->orderBy('l.added_date', 'desc');
	// 			break;
	// 		case 'total':

	// 			$shareHolder = DB::table('share_holder_fund_liabilities as s')
	// 						->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
	// 						->where('l.added_by', $userId)
	// 						->where('l.status', 1)
	// 						->whereBetween('l.added_date', [
	// 							$startFY->toDateString(),
	// 							$endFY->toDateString()
	// 						])
	// 						->select(
	// 							DB::raw("'Share Holder Fund' as category"),
	// 							'l.added_date',

	// 							DB::raw("
	// 								CASE

	// 									WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 										AND s.reserves_surplus_type = 'transfer_to_reserve'
	// 									THEN COALESCE(s.transfer_amount,0)

	// 									WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 										AND s.reserves_surplus_type = 'opening_balance'
	// 									THEN COALESCE(s.opening_balance,0)

	// 									WHEN s.share_holder_fund_type = 'reserves_surplus'
	// 										AND s.reserves_surplus_type = 'dividend_declaration'
	// 									THEN COALESCE(s.total_dividend_amount,0)

	// 									WHEN s.share_holder_fund_type = 'share_capital'
	// 									THEN COALESCE(s.total_amount,0)

	// 									ELSE 0

	// 								END as amount
	// 							")
	// 						);

	// 			$shareApp = DB::table('share_application_money_liabilities as s')
	// 					->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
	// 					->where('l.added_by', $userId)
	// 					->whereBetween('l.added_date', [$startFY, $endFY])
	// 					->select(
	// 						DB::raw("'Share Application Money' as category"),
	// 						'l.added_date',
	// 						DB::raw('COALESCE(s.amount_received,0) as amount')
	// 					);

	// 			$nonCurrent = DB::table('non_current_liabilities as n')
	// 						->join('liabilities as l', 'l.id', '=', 'n.liabilities_id')
	// 						->where('l.added_by', $userId)
	// 						->whereBetween('l.added_date', [$startFY, $endFY])
	// 						->select(
	// 							DB::raw("'Non Current Liability' as category"),
	// 							'l.added_date',

	// 							DB::raw("
	// 								CASE

	// 									WHEN n.liability_category = 'deferred_tax_liabilities'
	// 									THEN COALESCE(n.dtl_difference_accounting, 0)

	// 									ELSE COALESCE(n.amount, 0)

	// 								END as amount
	// 							")
	// 						);

	// 			$current = DB::table('current_liabilities as c')
	// 				->join('liabilities as l', 'l.id', '=', 'c.liabilities_id')
	// 				->where('l.added_by', $userId)
	// 				->whereBetween('l.added_date', [$startFY, $endFY])
	// 				->select(
	// 					DB::raw("'Current Liability' as category"),
	// 					'l.added_date',
	// 					DB::raw('COALESCE(c.amount, 0) as amount')
	// 				);

	// 			$union  = $shareHolder
	// 				->unionAll($shareApp)
	// 				->unionAll($nonCurrent)
	// 				->unionAll($current);
	// 			$query = DB::query()
	// 				->fromSub($union, 't')
	// 				->orderBy('added_date', 'desc');

	// 			break;

	// 		default:
	// 			return response()->json(['data' => []]);
	// 	}

	// 	$data = $query->paginate(10);

	// 	return view('partials.liabilities-modal-table', compact('data'))->render();
	// }



	public function AddLiabilites()
	{
		$userId = currentOwnerId();
		checkCoreAccess('Liabilities & Borrowings');
		// $purposes_of_tds = DB::table('purposes_of_tds')->get();
		$purposes_of_tds = DB::table('tds_rules')
							->where('module', '=', 'Liabilities')
							->get();
		
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

		// Fetch opening balance from company_profiles
		$companyProfile = DB::table('company_profiles')
						->select('opening_balance')
						->where('userId', $userId)
						->first();
		
		$openingBalance = $companyProfile ? $companyProfile->opening_balance : 0;

		return view('User.add-liabilites')->with([
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'openingBalance' => $openingBalance
		]);
		// return view('pages.addliabilities');
	}

	protected function validator(array $data)
	{
		$rules = [
			'added_date' => ['required', 'date'],
			'liabilitiesType' => ['required', 'string'],
		];

		// Share Holder's Funds Validation
		if (isset($data['liabilitiesType']) && $data['liabilitiesType'] == 'share_holder_fund') {

			$rules['shareHolderFundType'] = ['required', 'string'];

			if (isset($data['shareHolderFundType']) && $data['shareHolderFundType'] == 'share_capital') {
				$rules['shareHolderType'] = ['required', 'string'];
				// $rules['classofshares'] = ['required', 'string'];
				// $rules['sharesissued'] = ['required', 'numeric', 'min:1'];
				// $rules['facevaluepershare'] = ['required', 'numeric', 'min:0'];
				// $rules['allotmentDate'] = ['required', 'date'];
				// $rules['transactionMethod'] = ['required', 'string'];
			}

			if (isset($data['shareHolderFundType']) && $data['shareHolderFundType'] == 'reserves_surplus') {
				$rules['reserves_surplus_type'] = ['required', 'string'];
				
				// Conditional validation based on reserves_surplus_type
				// if (($data['reserves_surplus_type'] ?? null) === 'dividend_declaration') {
				// 	$rules['declaration_date'] = ['required', 'date'];
				// 	$rules['dividend_financial_year'] = ['required', 'string'];
				// 	$rules['total_dividend_amount'] = ['required', 'numeric', 'min:0'];
				// 	$rules['dividend_type'] = ['required', 'in:final,interim'];
				// 	$rules['dividend_payment_due_date'] = ['required', 'date'];
				// 	$rules['dividend_payment_status'] = ['required', 'in:unpaid,paid'];
				// 	$rules['dividend_remarks'] = ['nullable', 'string'];
				// }
				
				// if (($data['reserves_surplus_type'] ?? null) === 'transfer_to_reserve') {
				// 	$rules['transfer_date'] = ['required', 'date'];
				// 	$rules['transfer_financial_year'] = ['required', 'string'];
				// 	$rules['transfer_amount'] = ['required', 'numeric', 'min:0'];
				// 	$rules['reserve_type'] = ['required', 'in:general_reserve,capital_reserve,specific_reserve'];
				// 	$rules['transfer_remarks'] = ['nullable', 'string'];
				// }
				
				// if (($data['reserves_surplus_type'] ?? null) === 'equity_share_capital') {
				// 	$rules['opening_balance'] = ['nullable', 'numeric'];
				// 	$rules['surplusdate'] = ['nullable', 'date'];
				// 	$rules['amountForsurplus'] = ['nullable', 'numeric', 'min:0'];
				// }
			}
		}

		// Share Application Money Validation
		if (isset($data['liabilitiesType']) && $data['liabilitiesType'] == 'share_application_money') {
			// $rules['pendingFor'] = ['required', 'string'];
			$rules['amountForShare'] = ['nullable', 'numeric', 'min:0'];
			$rules['numberForShare'] = ['nullable', 'numeric', 'min:1'];
			// $rules['reasonForDelay'] = ['required', 'string'];
		}

		// =========================
		// NON-CURRENT LIABILITIES
		// =========================
		if (($data['liabilitiesType'] ?? null) === 'non_current_liabilities') {

			// Main Type
			$rules['non_current_liabilities_type'] = ['required', 'string'];

			// Required Common Fields
			// $rules['voucher_type_nonc'] = ['required', 'string'];
			// $rules['amt_nonc'] = ['required', 'numeric', 'min:0'];
			// $rules['debitcredit_nonc'] = ['required', 'in:Debit,Credit'];
			// $rules['due_date_nonc'] = ['required', 'date'];

			// GST Required
			// $rules['gst_applicable_nonc'] = ['required', 'in:yes,no'];

			// Conditional Required
			if (($data['non_current_liabilities_type'] ?? '') === 'other_non_current_liabilities') {
				$rules['other_non_current_liabilities_text'] = ['required', 'string'];
			}
		}


		// =========================
		// CURRENT LIABILITIES
		// =========================
		if (($data['liabilitiesType'] ?? null) === 'current_liabilities') {

			// Main Type
			$rules['CurrentLiabilitiesType'] = ['required', 'string'];

			// Common Required Fields
			// $rules['voucher_type_cl'] = ['required', 'string'];
			// $rules['amt_cl'] = ['required', 'numeric', 'min:0'];
			// $rules['debitcredit_cl'] = ['required', 'in:Debit,Credit'];
			// $rules['due_date_cl'] = ['required', 'date'];

			// GST Required
			// $rules['gst_applicable_cl'] = ['required', 'in:yes,no'];

			// Conditional Required
			if (($data['CurrentLiabilitiesType'] ?? '') === 'Other Current Liability') {
				$rules['other_current_liability_text'] = ['required', 'string'];
			}
		}

		return Validator::make($data, $rules);
	}

	

	protected function create(Request $request)
	{
		$data = $request->all();
		$userId = currentOwnerId();
		$propId = $data['propId'];

		// 1. Create the Main Liability Record
		$liability = Liabilities::create([
			'added_by'         => $userId,
			'propId'           => $propId,
			'added_date'       => $data['added_date'],
			'liabilities_type' => $data['liabilitiesType'],
			'status'           => '1',
			'created_at'       => now(),
		]);

		// 2. Handle Specific Liability Sub-types
		if ($data['liabilitiesType'] == "share_holder_fund") {
			$fileName = null;
			if ($request->hasFile('share_holder_fund_image')) {
				$fileName = time() . '_' . $request->share_holder_fund_image->getClientOriginalName();
				$request->share_holder_fund_image->storeAs('liabilities_files', $fileName, 'public');
			}

			DB::table('share_holder_fund_liabilities')->insert([
				'liabilities_id'         => $liability->id,
				'added_by'               => $userId,
				'share_holder_fund_type' => $data['shareHolderFundType'] ?? null,
				'share_holder_type'      => $data['shareHolderType'] ?? null,
				'class_of_shares'        => $data['classofshares'] ?? null,
				'shares_issued'          => $data['sharesissued'] ?? null,
				'face_value_per_share'   => $data['facevaluepershare'] ?? null,
				'premium_amount'         => $data['premiumamount'] ?? null,
				'total_amount'           => $data['totalamount'] ?? null,
				'allotment_date'         => $data['allotmentDate'] ?? null,
				'transaction_method'     => $data['transactionMethod'] ?? null,
				'share_certificate_no'   => $data['sharecertificateno'] ?? null,
				'description'            => $data['description'] ?? null,
				'reserves_surplus_type'  => $data['reserves_surplus_type'] ?? null,
				'surplusdate'            => $data['surplusdate'] ?? null,
				'opening_balance'        => (($data['reserves_surplus_type'] ?? '') === 'opening_balance') ? ($data['opening_balance'] ?? null) : null,
				
				// Dividend Declaration Fields
				'declaration_date'          => $data['declaration_date'] ?? null,
				'dividend_financial_year'   => $data['dividend_financial_year'] ?? null,
				'total_dividend_amount'     => $data['total_dividend_amount'] ?? null,
				'dividend_type'             => $data['dividend_type'] ?? null,
				'dividend_payment_due_date' => $data['dividend_payment_due_date'] ?? null,
				'dividend_payment_status'   => $data['dividend_payment_status'] ?? null,
				'dividend_remarks'          => $data['dividend_remarks'] ?? null,
				
				// Transfer to Reserve Fields
				'transfer_date'             => $data['transfer_date'] ?? null,
				'transfer_financial_year'   => $data['transfer_financial_year'] ?? null,
				'transfer_amount'           => $data['transfer_amount'] ?? null,
				'reserve_type'              => $data['reserve_type'] ?? null,
				'transfer_remarks'          => $data['transfer_remarks'] ?? null,
				
				'upload_file'               => $fileName,
				'created_at'                => now(),
			]);

		} else if ($data['liabilitiesType'] == "share_application_money") {
			DB::table('share_application_money_liabilities')->insert([
				'liabilities_id'    => $liability->id,
				'added_by'          => $userId,
				'applicant_name'    => $data['applicant_name'] ?? null,
				'pan'               => $data['pan'] ?? null,
				'amount_received'   => $data['amount_received'] ?? null,
				'date_received'     => $data['date_received'] ?? null,
				'payment_mode'      => $data['payment_mode'] ?? null,
				'bank_name'         => $data['bank_name'] ?? null,
				'no_of_shares'      => $data['no_of_shares'] ?? null,
				'face_value'        => $data['face_value'] ?? null,
				'premium'           => $data['premium'] ?? null,
				'allotment_status'  => $data['allotment_status'] ?? null,
				'allotment_date'    => $data['allotment_date'] ?? null,
				'created_at'        => now(),
			]);

		} else if ($data['liabilitiesType'] == "non_current_liabilities") { 
			$fileName = null;
			if ($request->hasFile('attachment_nonc')) {
				$fileName = time() . '_' . $request->file('attachment_nonc')->getClientOriginalName();
				$request->file('attachment_nonc')->storeAs('liabilities_files', $fileName, 'public');
			}

			DB::table('non_current_liabilities')->insert([
				'liabilities_id'                     => $liability->id,
				'added_by'                           => $userId,
				'liability_category'                 => $data['non_current_liabilities_type'],
				'other_non_current_liabilities_text' => $data['other_non_current_liabilities_text'] ?? null,
				'category_of_head'                   => $data['category_of_head_nonc'] ?? null,
				'party_name'                         => $data['party_name_nonc'] ?? null,
				'amount'                             => $data['amt_nonc'] ?? null,
				'due_date'                           => $data['due_date_nonc'] ?? null,
				'invoice_no'                         => $data['invoice_no_nonc'] ?? null,
				'loan_type'                          => $data['loan_type_nonc'] ?? null,
				'interest_rate'                      => $data['interest_rate_nonc'] ?? null,
				'msme_tag'                           => $data['msme_tag_nonc'] ?? null,
				'attachment'                         => $fileName,
				'notes'                              => $data['notes_nonc'] ?? null,

				// New Deferred Tax Fields
				'dtl_difference_accounting'          => $data['dtl_difference_accounting'] ?? null,
				'dtl_amount'                         => $data['dtl_amount'] ?? null,

				'created_at'                         => now(),
				'updated_at'                         => now(),
			]);

		} else if ($data['liabilitiesType'] == "current_liabilities") {
			$attachment = null;
			if ($request->hasFile('attachment_cl')) {
				$attachment = time() . '_attachment_' . $request->file('attachment_cl')->getClientOriginalName();
				$request->file('attachment_cl')->storeAs('liabilities_files', $attachment, 'public');
			}

			// Logic to pick the amount from specific sub-type fields if the general amount is empty
			$cl_type = $request->input('CurrentLiabilitiesType');
			$amount = $request->input('amt_cl');
			if (empty($amount)) {
				$amount = $request->input('cl_amount_' . $cl_type); 
			}

			DB::table('current_liabilities')->insert([
				'liabilities_id'               => $liability->id,
				'added_by'                     => $userId,
				'CurrentLiabilitiesType'       => $cl_type,
				'other_current_liability_text' => $request->input('other_current_liability_text'),
				'category_of_head'             => $request->input('category_of_head_cl'),
				'party_name'                   => $request->input('party_name_cl'),
				'voucher_type'                 => $request->input('voucher_type_cl'),
				'amount'                       => $amount,
				'debit_credit'                 => $request->input('debitcredit_cl'),
				'due_date'                     => $request->input('due_date_cl'),
				'invoice_no'                   => $request->input('invoice_no_cl'),
				'loan_type'                    => $request->input('loan_type_cl'),
				'interest_rate'                => $request->input('interest_rate_cl'),
				'msme_tag'                     => $request->input('msme_tag_cl'),
				'attachment'                   => $attachment,
				'notes'                        => $request->input('notes_cl'),

				// New Short-term Loan Fields
				'stl_loan_id'            => $request->input('stl_loan_id'),
				'stl_lender_name'        => $request->input('stl_lender_name'),
				'stl_loan_type'          => $request->input('stl_loan_type'),
				'stl_secured_unsecured'  => $request->input('stl_secured_unsecured'),
				'stl_sanction_amount'    => $request->input('stl_sanction_amount'),
				'stl_disbursement_date'  => $request->input('stl_disbursement_date'),
				'stl_amount_received'    => $request->input('stl_amount_received'),
				'stl_bank_account'       => $request->input('stl_bank_account'),
				'stl_interest_rate'      => $request->input('stl_interest_rate'),
				'stl_interest_amount'    => $request->input('stl_interest_amount'),
				'stl_interest_type'      => $request->input('stl_interest_type'),
				'stl_tenure_months'      => $request->input('stl_tenure_months'),
				'stl_repayment_type'     => $request->input('stl_repayment_type'),
				'stl_emi_amount'         => $request->input('stl_emi_amount'),
				'stl_next_due_date'      => $request->input('stl_next_due_date'),
				'stl_maturity_date'      => $request->input('stl_maturity_date'),
				'stl_reference'          => $request->input('stl_reference'),
				'stl_msme_related'       => $request->input('stl_msme_related'),
				'stl_tds_applicable'     => $request->input('stl_tds_applicable'),
				'stl_tds_section'        => $request->input('stl_tds_section'),
				'stl_tds_rate'           => $request->input('stl_tds_rate'),
				'stl_tds_amount'         => $request->input('stl_tds_amount'),
				'stl_remarks'            => $request->input('stl_remarks'),

				// New Interest Payable Fields
				'ip_loan_id'             => $request->input('ip_loan_id') ?: $request->input('ip_loan_id_manual'),
				'ip_lender_name'         => $request->input('ip_lender_name'),
				'ip_accrual_date'        => $request->input('ip_accrual_date'),
				'ip_period_from'         => $request->input('ip_period_from'),
				'ip_period_to'           => $request->input('ip_period_to'),
				'ip_interest_rate'       => $request->input('ip_interest_rate'),
				'ip_interest_amount'     => $request->input('ip_interest_amount'),
				'ip_principal_amount'    => $request->input('ip_principal_amount'),
				'ip_days_period'         => $request->input('ip_days_period'),
				'ip_due_date'            => $request->input('ip_due_date'),
				'ip_payment_status'      => $request->input('ip_payment_status'),
				'ip_reference'           => $request->input('ip_reference'),
				'ip_narration'           => $request->input('ip_narration'),
				'ip_tds_applicable'      => $request->input('ip_tds_applicable'),
				'ip_tds_section'         => $request->input('ip_tds_section'),
				'ip_tds_rate'            => $request->input('ip_tds_rate'),
				'ip_tds_amount'          => $request->input('ip_tds_amount'),

				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
		
		 //Journal entry
		$this->journalEntry($liability->id);
		
		// Start payment voucher entry
		$currentPayment = 0;
		if ($data['liabilitiesType'] == "current_liabilities") {
			$clType = $request->input('CurrentLiabilitiesType');
			if ($clType == 'short_term_loans') {
				$currentPayment = (float)($request->input('stl_sanction_amount') ?? 0);
			}
			else if ($clType == 'interest_payable') {
				$currentPayment = (float)($request->input('ip_interest_amount') ?? 0);
			}
		}else if ($data['liabilitiesType'] == "non_current_liabilities") {
			$currentPayment = (float)($request->input('amt_nonc') ?? 0);
		}else if ($data['liabilitiesType'] == "share_application_money") {
			$currentPayment = (float)($request->input('amount_received') ?? 0);
		}else if ($data['liabilitiesType'] == "share_holder_fund") {
			$currentPayment = (float)($request->input('totalamount') ?? 0);
			// Reserve transfer amount priority
			if (($request->input('reserves_surplus_type') ?? '') == 'transfer_to_reserve') {
				$currentPayment = (float)($request->input('transfer_amount') ?? 0);
			}
			// Dividend amount priority
			else if (($request->input('reserves_surplus_type') ?? '') == 'dividend_declaration') {
				$currentPayment = (float)($request->input('total_dividend_amount') ?? 0);
			}
		}

		if ($currentPayment > 0) {
			$this->paymentVoucherService->storePaymentVoucherEntries($liability->id,'Liability',$currentPayment);
		}
		// End payment voucher entry
		return $liability;
	}



	public function saveLiabilities(Request $request)
	{
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		// Pass the full request object
		$insertLiabilities = $this->create($request);

		if ($insertLiabilities) {
			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/liabilites-list'),
				'message' => 'Liabilities added successfully'
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/'),
				'message' => 'Liabilities add failed'
			]);
		}
	}


	public function EditLiabilites($liabId)
	{
		$liabId = base64_decode($liabId);
		$userId = currentOwnerId();
		$liability = \DB::table('liabilities')->where('id', $liabId)->first();
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

		$purposes_of_tds = DB::table('tds_rules')
							->where('module', '=', 'Liabilities')
							->get();

		$details = null;
		switch ($liability->liabilities_type) {
			case 'share_holder_fund':
				$details = \DB::table('share_holder_fund_liabilities')->where('liabilities_id', $liabId)->first();
				break;
			case 'share_application_money':
				$details = \DB::table('share_application_money_liabilities')->where('liabilities_id', $liabId)->first();
				break;
			case 'non_current_liabilities':
				$details = \DB::table('non_current_liabilities')->where('liabilities_id', $liabId)->first();
				break;
			case 'current_liabilities':
				$details = \DB::table('current_liabilities')->where('liabilities_id', $liabId)->first();
				break;
		}

		// echo '<pre>';
		// print_r($details);
		// die();

		return view('User.edit-liabilites')->with([
			'liability' => $liability,
			'proprietorships' => $proprietorships,
			'subDetails' => $details,
			'purposes_of_tds' => $purposes_of_tds,
		]);
	}

	public function ViewLiabilites($liabId)
	{


		$liabId = base64_decode($liabId);
		$userId = currentOwnerId();
		$liability = \DB::table('liabilities')->where('id', $liabId)->first();
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$purposes_of_tds = DB::table('tds_rules')
							->where('module', '=', 'Liabilities')
							->get();

		$details = null;
		switch ($liability->liabilities_type) {
			case 'share_holder_fund':
				$details = \DB::table('share_holder_fund_liabilities')->where('liabilities_id', $liabId)->first();
				break;
			case 'share_application_money':
				$details = \DB::table('share_application_money_liabilities')->where('liabilities_id', $liabId)->first();
				break;
			case 'non_current_liabilities':
				$details = \DB::table('non_current_liabilities')->where('liabilities_id', $liabId)->first();
				break;
			case 'current_liabilities':
				$details = \DB::table('current_liabilities')->where('liabilities_id', $liabId)->first();
				break;
		}
			
		return view('User.view-liabilites')->with([
			'liability' => $liability,
			'proprietorships' => $proprietorships,
			'subDetails' => $details,
			'purposes_of_tds' => $purposes_of_tds,
		]);
	}


	public function updateLiabilities(Request $request)
	{
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json($validation->errors());
		}

		$data = $request->all();
		$liabId = $request->liabId;
		$userId = currentOwnerId();
		$propId = $data['propId'];

		$liability = Liabilities::where('id', $liabId)
			->where('added_by', $userId)
			->first();

		if (!$liability) {
			return response()->json([
				'class' => 'error',
				'message' => 'Liability not found or you do not have permission to update it!'
			]);
		}

		// Update main liabilities table
		Liabilities::where('id', $liabId)->update([
			'propId'           => $propId,
			'added_date'       => $data['added_date'] ?? now(),
			'liabilities_type' => $data['liabilitiesType'] ?? null,
			'status'           => '1',
			'updated_at'       => now(),
		]);

		// 1. SHARE HOLDER FUND
		if ($data['liabilitiesType'] == "share_holder_fund") {
			$fileName = null;
			if ($request->hasFile('share_holder_fund_image')) {
				$fileName = time() . '_' . $request->share_holder_fund_image->getClientOriginalName();
				$request->share_holder_fund_image->storeAs('liabilities_files', $fileName, 'public');
			}

			DB::table('share_holder_fund_liabilities')->where('liabilities_id', $liabId)->update([
				'share_holder_fund_type'    => $data['shareHolderFundType'] ?? null,
				'share_holder_type'         => $data['shareHolderType'] ?? null,
				'class_of_shares'           => $data['classofshares'] ?? null,
				'shares_issued'             => $data['sharesissued'] ?? null,
				'face_value_per_share'      => $data['facevaluepershare'] ?? null,
				'premium_amount'            => $data['premiumamount'] ?? null,
				'total_amount'				=>  !empty($data['totalamount']) ? $data['totalamount'] : (!empty($data['opening_balance'])? $data['opening_balance'] : (!empty($data['total_dividend_amount']) ? $data['total_dividend_amount'] : ($data['transfer_amount'] ?? null))),
				'allotment_date'            => $data['allotmentDate'] ?? null,
				'transaction_method'        => $data['transactionMethod'] ?? null,
				'share_certificate_no'      => $data['sharecertificateno'] ?? null,
				'description'               => $data['description'] ?? null,
				'reserves_surplus_type'     => $data['reserves_surplus_type'] ?? null,
				'surplusdate'               => $data['surplusdate'] ?? null,
				'opening_balance'           => (($data['reserves_surplus_type'] ?? '') === 'opening_balance') ? ($data['opening_balance'] ?? null) : null,
				
				// Dividend Declaration Fields
				'declaration_date'          => $data['declaration_date'] ?? null,
				'dividend_financial_year'   => $data['dividend_financial_year'] ?? null,
				'total_dividend_amount'     => $data['total_dividend_amount'] ?? null,
				'dividend_type'             => $data['dividend_type'] ?? null,
				'dividend_payment_due_date' => $data['dividend_payment_due_date'] ?? null,
				'dividend_payment_status'   => $data['dividend_payment_status'] ?? null,
				'dividend_remarks'          => $data['dividend_remarks'] ?? null,
				
				// Transfer to Reserve Fields
				'transfer_date'             => $data['transfer_date'] ?? null,
				'transfer_financial_year'   => $data['transfer_financial_year'] ?? null,
				'transfer_amount'           => $data['transfer_amount'] ?? null,
				'reserve_type'              => $data['reserve_type'] ?? null,
				'transfer_remarks'          => $data['transfer_remarks'] ?? null,
				
				'upload_file'               => $fileName ?? DB::table('share_holder_fund_liabilities')->where('liabilities_id', $liabId)->value('upload_file'),
				'updated_at'                => now(),
			]);
		} 

		// 2. SHARE APPLICATION MONEY
		else if ($data['liabilitiesType'] == "share_application_money") {
			DB::table('share_application_money_liabilities')->where('liabilities_id', $liabId)->update([
				'applicant_name'   => $data['applicant_name'] ?? null,
				'pan'              => $data['pan'] ?? null,
				'amount_received'  => $data['amount_received'] ?? null,
				'date_received'    => $data['date_received'] ?? null,
				'payment_mode'     => $data['payment_mode'] ?? null,
				'bank_name'        => $data['bank_name'] ?? null,
				'no_of_shares'     => $data['no_of_shares'] ?? null,
				'face_value'       => $data['face_value'] ?? null,
				'premium'          => $data['premium'] ?? null,
				'allotment_status' => $data['allotment_status'] ?? null,
				'allotment_date'   => $data['allotment_date'] ?? null,
				'updated_at'       => now(),
			]);
		} 

		// 3. NON-CURRENT LIABILITIES
		else if ($data['liabilitiesType'] == "non_current_liabilities") {
			$oldData = DB::table('non_current_liabilities')->where('liabilities_id', $liabId)->first();
			$fileName = $oldData->attachment ?? null;

			if ($request->hasFile('attachment_nonc')) {
				if (!empty($fileName) && Storage::disk('public')->exists('liabilities_files/' . $fileName)) {
					Storage::disk('public')->delete('liabilities_files/' . $fileName);
				}
				$fileName = time() . '_' . $request->file('attachment_nonc')->getClientOriginalName();
				$request->file('attachment_nonc')->storeAs('liabilities_files', $fileName, 'public');
			}

			DB::table('non_current_liabilities')->where('liabilities_id', $liabId)->update([
				'liability_category'                 => $data['non_current_liabilities_type'] ?? null,
				'other_non_current_liabilities_text' => $data['other_non_current_liabilities_text'] ?? null,
				'category_of_head'                   => $data['category_of_head_nonc'] ?? null,
				'party_name'                         => $data['party_name_nonc'] ?? null,
				'amount'                             => $data['amt_nonc'] ?? null,
				'due_date'                           => $data['due_date_nonc'] ?? null,
				'invoice_no'                         => $data['invoice_no_nonc'] ?? null,
				'loan_type'                          => $data['loan_type_nonc'] ?? null,
				'interest_rate'                      => $data['interest_rate_nonc'] ?? null,
				'msme_tag'                           => $data['msme_tag_nonc'] ?? null,
				'attachment'                         => $fileName,
				'notes'                              => $data['notes_nonc'] ?? null,
				
				// Deferred Tax Fields
				'dtl_difference_accounting'          => $data['dtl_difference_accounting'] ?? null,
				'dtl_amount'                         => $data['dtl_amount'] ?? null,
				
				'updated_at'                         => now()
			]);
		} 

		// 4. CURRENT LIABILITIES
		else if ($data['liabilitiesType'] == "current_liabilities") {
			$oldData = DB::table('current_liabilities')->where('liabilities_id', $liabId)->first();
			$fileName = $oldData->attachment ?? null;

			if ($request->hasFile('attachment_cl')) {
				if (!empty($fileName) && Storage::disk('public')->exists('liabilities_files/' . $fileName)) {
					Storage::disk('public')->delete('liabilities_files/' . $fileName);
				}
				$fileName = time() . '_att_' . $request->file('attachment_cl')->getClientOriginalName();
				$request->file('attachment_cl')->storeAs('liabilities_files', $fileName, 'public');
			}

			// Picking amount based on sub-type logic from create()
			$cl_type = $request->input('CurrentLiabilitiesType');
			$amount = $request->input('amt_cl');
			if (empty($amount)) {
				$amount = $request->input('cl_amount_' . $cl_type); 
			}

			DB::table('current_liabilities')->where('liabilities_id', $liabId)->update([
				'CurrentLiabilitiesType'       => $cl_type,
				'other_current_liability_text' => $request->input('other_current_liability_text'),
				'category_of_head'             => $request->input('category_of_head_cl'),
				'party_name'                   => $request->input('party_name_cl'),
				'voucher_type'                 => $request->input('voucher_type_cl'),
				'amount'                       => $amount,
				'debit_credit'                 => $request->input('debitcredit_cl'),
				'due_date'                     => $request->input('due_date_cl'),
				'invoice_no'                   => $request->input('invoice_no_cl'),
				'loan_type'                    => $request->input('loan_type_cl'),
				'interest_rate'                => $request->input('interest_rate_cl'),
				'msme_tag'                     => $request->input('msme_tag_cl'),
				'attachment'                   => $fileName,
				'notes'                        => $request->input('notes_cl'),

				// Short-term Loan Fields
				'stl_loan_id'            => $request->input('stl_loan_id'),
				'stl_lender_name'        => $request->input('stl_lender_name'),
				'stl_loan_type'          => $request->input('stl_loan_type'),
				'stl_secured_unsecured'  => $request->input('stl_secured_unsecured'),
				'stl_sanction_amount'    => $request->input('stl_sanction_amount'),
				'stl_disbursement_date'  => $request->input('stl_disbursement_date'),
				'stl_amount_received'    => $request->input('stl_amount_received'),
				'stl_bank_account'       => $request->input('stl_bank_account'),
				'stl_interest_rate'      => $request->input('stl_interest_rate'),
				'stl_interest_amount'    => $request->input('stl_interest_amount'),
				'stl_interest_type'      => $request->input('stl_interest_type'),
				'stl_tenure_months'      => $request->input('stl_tenure_months'),
				'stl_repayment_type'     => $request->input('stl_repayment_type'),
				'stl_emi_amount'         => $request->input('stl_emi_amount'),
				'stl_next_due_date'      => $request->input('stl_next_due_date'),
				'stl_maturity_date'      => $request->input('stl_maturity_date'),
				'stl_reference'          => $request->input('stl_reference'),
				'stl_msme_related'       => $request->input('stl_msme_related'),
				'stl_tds_applicable'     => $request->input('stl_tds_applicable'),
				'stl_tds_section'        => $request->input('stl_tds_section'),
				'stl_tds_rate'           => $request->input('stl_tds_rate'),
				'stl_tds_amount'         => $request->input('stl_tds_amount'),
				'stl_remarks'            => $request->input('stl_remarks'),

				// Interest Payable Fields
				'ip_loan_id'             => $request->input('ip_loan_id') ?: $request->input('ip_loan_id_manual'),
				'ip_lender_name'         => $request->input('ip_lender_name'),
				'ip_accrual_date'        => $request->input('ip_accrual_date'),
				'ip_period_from'         => $request->input('ip_period_from'),
				'ip_period_to'           => $request->input('ip_period_to'),
				'ip_interest_rate'       => $request->input('ip_interest_rate'),
				'ip_interest_amount'     => $request->input('ip_interest_amount'),
				'ip_principal_amount'    => $request->input('ip_principal_amount'),
				'ip_days_period'         => $request->input('ip_days_period'),
				'ip_due_date'            => $request->input('ip_due_date'),
				'ip_payment_status'      => $request->input('ip_payment_status'),
				'ip_reference'           => $request->input('ip_reference'),
				'ip_narration'           => $request->input('ip_narration'),
				'ip_tds_applicable'      => $request->input('ip_tds_applicable'),
				'ip_tds_section'         => $request->input('ip_tds_section'),
				'ip_tds_rate'            => $request->input('ip_tds_rate'),
				'ip_tds_amount'          => $request->input('ip_tds_amount'),

				'updated_at' => now()
			]);
		}

		$this->journalEntry($liabId);
		// Start payment voucher entry
		$currentPayment = 0;
		if ($data['liabilitiesType'] == "current_liabilities") {
			$clType = $request->input('CurrentLiabilitiesType');
			if ($clType == 'short_term_loans') {
				$currentPayment = (float)($request->input('stl_sanction_amount') ?? 0);
			}
			else if ($clType == 'interest_payable') {
				$currentPayment = (float)($request->input('ip_interest_amount') ?? 0);
			}
		}else if ($data['liabilitiesType'] == "non_current_liabilities") {
			$currentPayment = (float)($request->input('amt_nonc') ?? 0);
		}else if ($data['liabilitiesType'] == "share_application_money") {
			$currentPayment = (float)($request->input('amount_received') ?? 0);
		}else if ($data['liabilitiesType'] == "share_holder_fund") {
			$currentPayment = (float)($request->input('totalamount') ?? 0);
			// Reserve transfer amount priority
			if (($request->input('reserves_surplus_type') ?? '') == 'transfer_to_reserve') {
				$currentPayment = (float)($request->input('transfer_amount') ?? 0);
			}
			// Dividend amount priority
			else if (($request->input('reserves_surplus_type') ?? '') == 'dividend_declaration') {
				$currentPayment = (float)($request->input('total_dividend_amount') ?? 0);
			}
		}

		if ($currentPayment > 0) {
			$this->paymentVoucherService->storePaymentVoucherEntries($liability->id,'Liability',$currentPayment);
		}
		// End payment voucher entry

		return response()->json([
			'class' => 'succ',
			'message' => 'Liability updated successfully!',
			'redirect' => url('/liabilites-list')
		]);
	}






	public function delLiabilities($id)
	{
		// Fetch liability type before update
		$liabilityType = DB::table('liabilities')
			->where('id', $id)
			->value('liabilities_type');

		$updateStatus = DB::table('liabilities')
			->where('id', $id)
			->update(['status' => 0]);
			
		$delJournalRec = DB::table('journals')
								->where('autoId', $id)
								->where('source', 'Liability')->delete();
		$delPaymentRec = DB::table('payment_vouchers')
							->where('f_id', $id)
							->where('source', 'Liability')->delete();
		
		if ($updateStatus) {
			// AUDIT LOG ENTRY
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Liabilities',
				description: "Liability deleted: {$liabilityType}",
				oldData: ['Liabilities Type' => $liabilityType],
				newData: null
			);
			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/liabilites-list'),
				'message' => 'Liability deleted successfully.'
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/liabilites-list'),
				'message' => 'Delete action failed!'
			]);
		}
	}

	

	public function getCurrentLiabilityAmount(Request $request)
	{
		$type = $request->type;
		$userId = currentOwnerId();

		// Previous month start & end date
		$startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
		$endDate   = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

		// Current month start & end date for payslip
		$currentMonthStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
		$currentMonthEndDate   = Carbon::now()->endOfMonth()->format('Y-m-d');

		$amount = 0;

		// Trade Payables
		if ($type == 'trade_payables') {

			$amount = DB::table('purchases')
				->where('added_by', $userId)
				->where('pay_status', 'Partial')
				->whereBetween('inv_date', [$startDate, $endDate])
				->sum('due_amount');
		}

		// Advance from Customer
		if ($type == 'advance_from_customer') {

			$amount = DB::table('sales')
				->where('added_by', $userId)
				->where('pay_status', 'Partial')
				->whereBetween('inv_date', [$startDate, $endDate])
				->sum('advance_amount');
		}

		// Outstanding Expenses
		if ($type == 'outstanding_expenses') {

			$amount = DB::table('expenses')
				->where('added_by', $userId)
				->where('payment_status', 'advance')
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('balance_amount');
		}

		// Salary Payable
		if ($type == 'salary_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$currentMonthStartDate, $currentMonthEndDate])
				->where(function ($query) {
						$query->whereNull('payment_status')
							  ->orWhere('payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += $data['visible_data']['final_salary_calculation']['net_salary'] ?? 0;
				}
			}
		}

		// PF Payable
		if ($type == 'pf_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->where(function ($query) {
						$query->whereNull('pf_payment_status')
							  ->orWhere('pf_payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += $data['visible_data']['final_salary_calculation']['provident_fund'] ?? 0;
				}
			}
		}

		// ESI Payable
		if ($type == 'esi_payable') {

			$records = DB::table('user_payslip')
				->whereBetween('date', [$startDate, $endDate])
				->where(function ($query) {
						$query->whereNull('esi_payment_status')
							  ->orWhere('esi_payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				// Match created_by from JSON
				if (($data['created_by'] ?? 0) == $userId) {
					$amount += $data['visible_data']['final_salary_calculation']['esi'] ?? 0;
				}
			}
		}

		// GST Payable
		if ($type == 'gst_payable') {

			// Get sales ids
			$salesIds = DB::table('sales')
				->where('added_by', $userId)
				->whereBetween('inv_date', [$startDate, $endDate])
				->pluck('id');

			// Sum tax amount from sales_values table
			$salesTaxAmount = DB::table('sales_values')
				->whereIn('sid', $salesIds)
				->sum('tax_amt');

			// Income GST Amount
			$incomeGstAmount = DB::table('income')
				->where('addBy', $userId)
				->whereBetween('dateInput', [$startDate, $endDate])
				->sum('gst_amt');

			// Expenses GST Amount
			$expenseGstAmount = DB::table('expenses')
				->where('added_by', $userId)
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('total_gst');
			
			 // Assets GST Amount
			$assetGstAmount = DB::table('assets')
				->where('added_by', $userId)
				->whereBetween('date', [$startDate, $endDate])
				->sum('gst_amt');

			// Final Total
			$amount = $salesTaxAmount + $incomeGstAmount + $expenseGstAmount + $assetGstAmount;
		}

		// TDS Payable
		if ($type == 'tds_payable') {

			// Income TDS Amount
			$incomeTdsAmount = DB::table('income')
				->where('addBy', $userId)
				->where('tds_applicable', 'yes')
				->whereBetween('dateInput', [$startDate, $endDate])
				->sum('tds_amount');

			// Expenses TDS Amount
			$expenseTdsAmount = DB::table('expenses')
				->where('added_by', $userId)
				->where('tds_applicable', 'yes')
				->whereBetween('expense_date', [$startDate, $endDate])
				->sum('tds_amount');

			// Assets TDS Amount
			$assetTdsAmount = DB::table('assets')
				->where('added_by', $userId)
				->where('tds_applicable', 'yes')
				->whereBetween('date', [$startDate, $endDate])
				->sum('tds_amt');
			
			// Salary TDS Amount
			$salaryData = DB::table('user_payslip')
				->whereBetween('date', [$currentMonthStartDate, $currentMonthEndDate])
				->where(function ($query) {
						$query->whereNull('tds_deposit_status')
							  ->orWhere('tds_deposit_status', 'Pending');
					})
				->get();

			$salaryTdsAmount = 0;

			foreach ($salaryData as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);
				if (($data['created_by'] ?? 0) == $userId) {
					$salaryTdsAmount += $data['visible_data']['final_salary_calculation']['tds'] ?? 0;
				}
			}

			// Final Total
			$amount = $incomeTdsAmount + $expenseTdsAmount + $assetTdsAmount + $salaryTdsAmount;
		}
		// ESI Payable
		if ($type == 'lwf_payable') {

			$start = Carbon::parse($startDate);
			$financialYear = $start->year . '-' . ($start->year + 1);
			$month = $start->month;

			$records = DB::table('user_payslip')
				->where('added_by', $userId)
				->where('financial_year', $financialYear)
				->where('month', $month)
				->where(function ($query) {
						$query->whereNull('payment_status')
							  ->orWhere('payment_status', 'Pending');
					})
				->get();

			$amount = 0;

			foreach ($records as $row) {
				$data = json_decode($row->emp_salary_slip_response, true);		
				if (($data['created_by'] ?? 0) == $userId) {
					$lwfEmployee = $data['visible_data']['final_salary_calculation']['lwf_deduct'] ?? 0;
					$lwfCompany = $data['visible_data']['final_salary_calculation']['lwf_company_contribution'] ?? 0;
					$amount += (float) $lwfEmployee + (float) $lwfCompany;
				}
			}
		}

		return response()->json([
			'status' => true,
			'amount' => number_format($amount, 2, '.', '')
		]);
	}



}
