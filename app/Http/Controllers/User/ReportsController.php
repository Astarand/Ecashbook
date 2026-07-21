<?php

namespace App\Http\Controllers\User;

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

class ReportsController extends Controller
{
	private $expensesService;
	private $assetsService;
	private $liabilitiesService;
	private $reportsService;

    public function __construct(ReportsService $reportsService, ExpensesService $expensesService, AssetsService $assetsService, LiabilitiesService $liabilitiesService)
    {
        $this->reportsService = $reportsService;
        $this->expensesService = $expensesService;
        $this->assetsService = $assetsService;
        $this->liabilitiesService = $liabilitiesService;
		$this->middleware('auth');
    }

    public function PayrollReports(Request $request)
    {
        return view('User.Reports.Payroll-reports');
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
		$opening = $this->getOpeningBalanceFromJournal($ledger, $userId, $currentDate, $propId);
		//echo "<pre>";print_r($opening);exit;
		$openingDr = $opening['dr'];
		$openingCr = $opening['cr'];
		if($openingDr == 0 && $openingCr == 0){
			$opening = $this->getOpeningBalanceCreditDebit($userId);
			$openingDr = $opening['opening_dr'];
			$openingCr = $opening['opening_cr'];
		}
		//echo "<pre>";print_r($opening);exit;
		
		$ledgers = DB::table('journals')
			->where('added_by', $userId)
			->whereNotNull('ledger')
			->distinct()
			->pluck('ledger');
			
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		return view('User.Reports.trial-balance')->with([
				'openingDr' => $openingDr,
				'openingCr' => $openingCr,
				'proprietorships' => $proprietorships,
				'ledgers' => $ledgers,
				'req_type' => $req_type
			]);
    }
	
	private function getPreviousFYRange($fromDate)
	{
		$date = Carbon::parse($fromDate);

		if ($date->month >= 4) {
			// Current FY starts Apr → Previous FY is last year Apr–Mar
			$prevFrom = Carbon::create($date->year - 1, 4, 1);
			$prevTo   = Carbon::create($date->year, 3, 31);
		} else {
			// Current FY started last year Apr
			$prevFrom = Carbon::create($date->year - 2, 4, 1);
			$prevTo   = Carbon::create($date->year - 1, 3, 31);
		}

		return [
			$prevFrom->toDateString(),
			$prevTo->toDateString()
		];
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
	
	
	//Start new trial balance logic
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
		$from   = $r->from_date;
		$to     = $r->to_date;

		$ledgerFilter     = $r->ledger_name;
		$ledgerGroup      = $r->ledger_group;

		// ================= FETCH JOURNAL =================
		$journals = DB::table('journals')
				->where('added_by', $userId)
				->when($propId, fn($q) => $q->where('propId', $propId))
				->whereBetween('journal_date', [$from, $to])
				->orderBy('journal_date')
				->orderBy('id')
				->where(function($q) {
					$q->whereNull('rev_amend_status')
					  ->orWhere('rev_amend_status', '');
				})
				->get();

		$trial = [];
		//echo "<pre>";print_r($journals);exit;
		foreach ($journals as $row) 
		{
			if (empty($row->ledger)) {
				continue;
			}
			$ledger = trim($row->ledger);
			$group    = $this->getLedgerGroup($row);
			$subGroup = $this->getLedgerSubGroup($row);

			// ================= FILTER SUPPORT =================
			if ($ledgerFilter && $ledgerFilter !== 'all' && $ledger !== $ledgerFilter) continue;
			if ($ledgerGroup && strtolower($group) !== strtolower($ledgerGroup)) continue;

			// ================= INIT =================
			if (!isset($trial[$group][$subGroup][$ledger])) {

				$opening = $this->getOpeningBalanceFromJournal($ledger, $userId, $from, $propId);
				//echo "<pre>";print_r($opening);exit;

				$trial[$group][$subGroup][$ledger] = [
					'ledgername' => $ledger,
					'opening_dr' => $opening['dr'],
					'opening_cr' => $opening['cr'],
					'debit'      => 0,
					'credit'     => 0,
					'closing_dr' => 0,
					'closing_cr' => 0,
				];
			}

			// ================= DR / CR =================
			$value = $this->getTrialAmount($row);
			if (strtolower($row->debit_credit) === 'debit') {
				$trial[$group][$subGroup][$ledger]['debit'] += $value;
			} else {
				$trial[$group][$subGroup][$ledger]['credit'] += $value;
			}
		}

		// ================= CLOSING =================
		$totalDr = 0;
		$totalCr = 0;
		foreach ($trial as &$subs) {
			foreach ($subs as &$ledgers) {
				foreach ($ledgers as &$v) {

					$totalDebit  = $v['opening_dr'] + $v['debit'];
					$totalCredit = $v['opening_cr'] + $v['credit'];

					if ($totalDebit > $totalCredit) {
						$v['closing_dr'] = $totalDebit - $totalCredit;
						$v['closing_cr'] = 0;
					} else {
						$v['closing_dr'] = 0;
						$v['closing_cr'] = $totalCredit - $totalDebit;
					}

					$totalDr += $v['closing_dr'];
					$totalCr += $v['closing_cr'];
				}
				unset($v);
			}
			unset($ledgers);
		}
		unset($subs);

		return response()->json([
			'trial'      => $trial,
			'total_dr'   => round($totalDr, 2),
			'total_cr'   => round($totalCr, 2),
			'difference' => round(abs($totalDr - $totalCr), 2),
			'diff_dc'    => $totalDr >= $totalCr ? 'Dr' : 'Cr'
		]);
	}
	
	private function getOpeningBalanceFromJournal($ledger, $userId, $fromDate, $propId)
	{
		//Company Opening Balance
		$table = !empty($propId) ? 'proprietorship_profiles' : 'company_profiles';

		$company = DB::table($table)
			->when(!empty($propId), function ($q) use ($propId) {
				$q->where('id', $propId);
			}, function ($q) use ($userId) {
				$q->where('userId', $userId);
			})
			->select(
				'openingbalancecr',
				'openingbalancedr'
			)
			->first();

		$dr = (float)($company->openingbalancedr ?? 0);
		$cr = (float)($company->openingbalancecr ?? 0);

		//Journal Opening
		$rows = DB::table('journals')
			->where('added_by', $userId)
			//->where('ledger', $ledger)
			->whereDate('journal_date', '<', $fromDate)
			->when($propId, function ($q) use ($propId) {
				$q->where('propId', $propId);
			})
			->where(function ($q) {
				$q->whereNull('rev_amend_status')
				  ->orWhere('rev_amend_status', '');
			})
			->get();

		foreach ($rows as $r) {
			$value = $this->getTrialAmount($r);
			if (strtolower($r->debit_credit) == 'debit') {
				$dr += $value;
			} else {
				$cr += $value;
			}
		}

		//Return Net Opening
		if ($dr > $cr) {
			return [
				'dr' => round($dr - $cr, 2),
				'cr' => 0
			];
		}

		return [
			'dr' => 0,
			'cr' => round($cr - $dr, 2)
		];
	}
	
	private function getLedgerGroup($row)
	{
		$ledger = strtolower(trim($row->ledger));
		$source = strtolower(trim($row->source));
		$entry  = strtolower(trim($row->entry_type));

		// GST
		if (
			str_contains($ledger, 'gst') ||
			str_contains($ledger, 'cgst') ||
			str_contains($ledger, 'sgst') ||
			str_contains($ledger, 'igst')
		) {
			return 'Liability';   // GST Payable
		}

		// Asset
		if (
			$source == 'asset' ||
			str_contains($ledger, 'cash') ||
			str_contains($ledger, 'bank')
		) {
			return 'Asset';
		}

		// Liability
		if ($source == 'liability') {
			return 'Liability';
		}

		// Equity
		if (
			str_contains($ledger, 'capital') ||
			str_contains($ledger, 'reserve') ||
			str_contains($ledger, 'equity')
		) {
			return 'Equity';
		}

		// Income
		if (
			str_contains($entry, 'sales') ||
			str_contains($ledger, 'sales') ||
			$source == 'sales' ||
			$source == 'income'
		) {
			return 'Income';
		}

		// Expense
		if (
			str_contains($entry, 'purchase') ||
			str_contains($ledger, 'purchase') ||
			$source == 'purchase' ||
			$source == 'expense'
		) {
			return 'Expense';
		}

		return 'Others';
	}
	
	private function getLedgerSubGroup($row)
	{
		$ledger = strtolower($row->ledger);

		if (
			strtolower(trim($row->party_name ?? '')) == 'customer'
			|| str_contains($ledger,'customer')
		){
			return 'Customer';
		}
		if (
			in_array(strtolower($row->party_name), ['vendor','supplier'])
			|| str_contains($ledger,'supplier')
			|| str_contains($ledger,'vendor')
		){
			return 'Vendor';
		}
		if (str_contains($ledger, 'cgst') || str_contains($ledger, 'sgst') || str_contains($ledger, 'igst')) {
			return 'GST';
		}

		return '';
	}
	
	private function getTrialAmount($row)
	{
		$ledger = strtolower(trim($row->ledger));
		$entry = strtolower(trim($row->entry_type));
		if (in_array($ledger, ['sales', 'purchase'])) {
			return (float) $row->tot_amt;
		}

		return (float) $row->amount;
	}	
	//End new trial balance logic
	
	public function downloadTrialBalanceSheetPdf(Request $request)
	{
		$userId = currentOwnerId();
		$html = $request->html; // full table HTML

		$pdf = Pdf::loadView('trial-balance-sheet-pdf', [
			'html' => $html
		])->setPaper('A4', 'landscape');

		return $pdf->download('Trial_Balance_Sheet.pdf');
	}

    public function BankReconciliation(request $request)
    {
		checkCoreAccess('Cash & Banking');
		$userId = currentOwnerId();
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		return view('User.Reports.bank-reconciliation')->with([
				'proprietorships' => $proprietorships
			]);
    }
	
	private function getBankReconciliationData($request)
	{
		$userId = currentOwnerId();
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		$propId 	   = $request->propId ?? null;
		$financialYear = $request->financial_year;
		$reportType    = $request->report_type;
		$quarter       = $request->quarter;
		$month         = $request->month;

		// ==========================================
		// DATE RANGE
		// ==========================================

		[$fyStart, $fyEnd] = explode('-', $financialYear);

		$startDate = $fyStart . '-04-01';
		$endDate   = $fyEnd . '-03-31';

		// ==========================================
		// MONTHLY
		// ==========================================

		if ($reportType == 'Monthly') {

			$monthNumber = date('m', strtotime($month));

			$year = $fyStart;

			if ($monthNumber <= 3) {
				$year = $fyEnd;
			}

			$startDate = Carbon::create($year,$monthNumber,1)
				->startOfMonth()
				->format('Y-m-d');

			$endDate = Carbon::create($year,$monthNumber,1)
				->endOfMonth()
				->format('Y-m-d');
		}

		// ==========================================
		// QUARTERLY
		// ==========================================

		if ($reportType == 'Quarterly') {

			switch ($quarter) {

				case 1:
					$startDate = $fyStart . '-04-01';
					$endDate   = $fyStart . '-06-30';
					break;

				case 2:
					$startDate = $fyStart . '-07-01';
					$endDate   = $fyStart . '-09-30';
					break;

				case 3:
					$startDate = $fyStart . '-10-01';
					$endDate   = $fyStart . '-12-31';
					break;

				case 4:
					$startDate = $fyEnd . '-01-01';
					$endDate   = $fyEnd . '-03-31';
					break;
			}
		}

		// ==========================================
		// HALF YEARLY
		// ==========================================

		if ($reportType == 'Half-Yearly') {

			if ($quarter == 1) {

				$startDate = $fyStart . '-04-01';
				$endDate   = $fyStart . '-09-30';

			} else {

				$startDate = $fyStart . '-10-01';
				$endDate   = $fyEnd . '-03-31';
			}
		}

		// ==========================================
		// BANK TRANSACTIONS
		// ==========================================

		$bankTransactions = DB::table('bank_trans as bt')
			->leftJoin('banks as b', 'b.id', '=', 'bt.bankId')
			->select('bt.*', 'b.bank_name')
			->where('bt.added_by', $userId)
			->when(!empty($propId), function ($query) use ($propId) {
				$query->where('bt.prop_id', $propId);
			})
			->whereBetween('bt.tran_date', [$startDate, $endDate])
			->orderBy('bt.tran_date', 'ASC')
			->get();
			
		$statementUploaded = $bankTransactions->count() > 0;

		// ==========================================
		// PAYMENT VOUCHERS
		// ==========================================

		$paymentVouchers = DB::table('payment_vouchers')
			->where('added_by', $userId)
			->where('propId', $propId)
			->whereBetween('date', [$startDate, $endDate])
			->whereIn('payment_mode', [
				'Bank',
				'UPI',
				'NEFT',
				'RTGS',
				'IMPS'
			])
			->get();

		// ==========================================
		// MATCHING ENGINE
		// ==========================================

		$matchedRows   = [];
		$reviewRows    = [];
		$unmatchedRows = [];

		$matchedAmount = 0;
		$depositAmount = 0;
		$chequeAmount  = 0;

		foreach ($bankTransactions as $bankTran) {

			$tranDate = $bankTran->tran_date;

			$datesToCheck = [
				$tranDate,
				date('Y-m-d', strtotime('-1 day', strtotime($tranDate))),
				date('Y-m-d', strtotime('+1 day', strtotime($tranDate))),
				date('Y-m-d', strtotime('-2 day', strtotime($tranDate))),
				date('Y-m-d', strtotime('+2 day', strtotime($tranDate))),
			];

			$bestMatch = null;
			$bestScore = 0;

			foreach ($paymentVouchers as $voucher) {

				$score = 0;

				if ((float)$voucher->amount == (float)$bankTran->tran_amt) {
					$score += 50;
				}
				// Date Matching
				if ($voucher->date == $tranDate) {
					$score += 30;

				} elseif (
					$voucher->date == date('Y-m-d', strtotime($tranDate . ' -1 day')) ||
					$voucher->date == date('Y-m-d', strtotime($tranDate . ' +1 day'))
				) {
					$score += 25;

				} elseif (
					$voucher->date == date('Y-m-d', strtotime($tranDate . ' -2 day')) ||
					$voucher->date == date('Y-m-d', strtotime($tranDate . ' +2 day'))
				) {
					$score += 20;
				}

				if (!empty($voucher->reference_id) && !empty($bankTran->ref_no)) {
					if (trim($voucher->reference_id) == trim($bankTran->ref_no)) {
						$score += 40;
					}
				}

				if (!empty($bankTran->purpose) && !empty($voucher->party_name)) {

					if (
						stripos(
							strtolower($bankTran->purpose),
							strtolower($voucher->party_name)
						) !== false
					) {

						$score += 15;
					}
				}

				if (
					($voucher->credit_debit == 'Credit'
					&& $bankTran->tran_type == 'Credit')

					||

					($voucher->credit_debit == 'Debit'
					&& $bankTran->tran_type == 'Debit')
				) {

					$score += 10;
				}

				if ($score > $bestScore) {

					$bestScore = $score;
					$bestMatch = $voucher;
				}
			}

			if ($bestScore >= 80) {

				$matchedAmount += $bankTran->tran_amt;

				$matchedRows[] = [
					'bank_name'      => $bankTran->bank_name,
					'bank_date'      => $bankTran->tran_date,
					'tran_type'      => $bankTran->tran_type,
					'bank_amount'    => $bankTran->tran_amt,
					'voucher_no'     => $bestMatch->voucher_no,
					'voucher_date'   => $bestMatch->date,
					'voucher_amount' => $bestMatch->amount,
					'purpose' 		 => $bestMatch->narration ?? '-',
					'score'          => 100,
					'status'         => 'Matched'
				];

			} elseif ($bestScore >= 60) {

				$reviewRows[] = [
					'bank_name'      => $bankTran->bank_name,
					'bank_date'      => $bankTran->tran_date,
					'tran_type'      => $bankTran->tran_type,
					'bank_amount'    => $bankTran->tran_amt,
					'voucher_no'     => $bestMatch->voucher_no ?? '-',
					'voucher_date'   => $bestMatch->date ?? '-',
					'voucher_amount' => $bestMatch->amount ?? '-',
					'purpose' 		 => $bestMatch->narration ?? '-',
					'score'          => $bestScore,
					'status'         => 'Review'
				];

			} else {

				if ($bankTran->tran_type == 'Credit') {
					$depositAmount += $bankTran->tran_amt;
				}

				if ($bankTran->tran_type == 'Debit') {
					$chequeAmount += $bankTran->tran_amt;
				}

				$unmatchedRows[] = [
					'bank_name'      => $bankTran->bank_name,
					'bank_date'      => $bankTran->tran_date,
					'tran_type'      => $bankTran->tran_type,
					'bank_amount'    => $bankTran->tran_amt,
					'voucher_no'     => '-',
					'voucher_date'   => '-',
					'voucher_amount' => '-',
					'purpose' 		 => $bestMatch->narration ?? '-',
					'score'          => 0,
					'status'         => 'Unmatched'
				];
			}
		}

		// ==========================================
		// OPENING BALANCE
		// ==========================================

		$openingCredit = DB::table('bank_trans')
			->where('added_by', $userId)
			->where('prop_id', $propId)
			->where('tran_type', 'Credit')
			->where('tran_date', '<', $startDate)
			->sum('tran_amt');

		$openingDebit = DB::table('bank_trans')
			->where('added_by', $userId)
			->where('prop_id', $propId)
			->where('tran_type', 'Debit')
			->where('tran_date', '<', $startDate)
			->sum('tran_amt');

		$openingCash = $openingCredit - $openingDebit;

		// ==========================================
		// BANK CHARGES
		// ==========================================

		$bankCharges = $bankTransactions
			->filter(function ($row) {

				return
					stripos($row->purpose, 'charge') !== false
					||
					stripos($row->purpose, 'chgs') !== false
					||
					stripos($row->purpose, 'fee') !== false;
			})
			->sum('tran_amt');

		// ==========================================
		// CLOSING BALANCE
		// ==========================================

		$creditTotal = $bankTransactions
			->where('tran_type', 'Credit')
			->sum('tran_amt');

		$debitTotal = $bankTransactions
			->where('tran_type', 'Debit')
			->sum('tran_amt');

		$closingBalance =
			($openingCash + $creditTotal)
			- $debitTotal;

		return [

			'statement_uploaded' => $statementUploaded,
			
			'opening_cash' => round($openingCash, 2),

			'deposit' => round($depositAmount, 2),

			'cheque' => round($chequeAmount, 2),

			'charges' => round($bankCharges, 2),

			'closing_bank' => round($closingBalance, 2),

			'reconciled_cash' => round($matchedAmount, 2),

			'reconciled_bank' => round($matchedAmount, 2),

			'matched_rows' => $matchedRows,

			'review_rows' => $reviewRows,

			'unmatched_rows' => $unmatchedRows,

			'start_date' => $startDate,

			'end_date' => $endDate
		];
	}
	
	public function fetchBankReconciliation(Request $request)
	{
		$data = $this->getBankReconciliationData($request);

		return response()->json($data);
	}
	
	
	public function downloadPdf(Request $request)
	{
		$userId = currentOwnerId();
		$data = $this->getBankReconciliationData($request);
		$pdf = PDF::loadView('bank_reconciliation_pdf', [

			// Summary
			'openingCash'      => $data['opening_cash'] ?? 0,
			'depositAmt'       => $data['deposit'] ?? 0,
			'chequeAmt'        => $data['cheque'] ?? 0,
			'charges'          => $data['charges'] ?? 0,
			'closingBalance'   => $data['closing_bank'] ?? 0,

			'matchedBalance1'  => $data['reconciled_cash'] ?? 0,
			'matchedBalance2'  => $data['reconciled_bank'] ?? 0,

			'from'             => $data['start_date'] ?? '',
			'to'               => $data['end_date'] ?? '',

			'matchedRows'      => $data['matched_rows'] ?? [],
			'reviewRows'       => $data['review_rows'] ?? [],
			'unmatchedRows'    => $data['unmatched_rows'] ?? [],

			// Filters
			'financialYear'    => $request->financial_year,
			'reportType'       => $request->report_type,
			'quarter'          => $request->quarter,
			'month'            => $request->month,

		])->setPaper('a4', 'landscape');

		return $pdf->download(
			'bank_reconciliation_report_' . date('YmdHis') . '.pdf'
		);
	}

    public function GSTReports()
    {
        return view('User.Reports.gst-reports');
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
	
	//Start Ledger section
	public function getOpeningBalance($userId)
	{
		$openingBal = DB::table('company_profiles')
			->where('userId', $userId)
			->value('opening_balance');
		$openingBal = $openingBal ?? 0;
		return $openingBal;
	}
	public function getPreviousFYOpeningBalance_ledger($propId, $userId, $fromDate)
	{
		// Step 1: Previous FY dates
		[$prevFrom, $prevTo] = $this->getPreviousFYRange($fromDate);
		$partyName = null;
		$custId     = null;
		$vendId     = null;
		
		//echo $prevFrom;
		//echo $prevTo; exit;

		// Step 2: Collect ALL ledger rows for previous FY		
		$ledger  = null;
		$ledgerGroup  = null;
		$rows = $this->journalLedgerRows($propId, $userId, $prevFrom, $prevTo , $ledger, $ledgerGroup,$partyName,$custId,$vendId);

		// Step 3: Calculate totals
		$totalDr = 0;
		$totalCr = 0;
		//echo "<pre>";print_r($rows);exit;
		foreach ($rows as $r) {
			$totalDr += (float) ($r['debit'] ?? 0);
			$totalCr += (float) ($r['credit'] ?? 0);
		}
		$openingBalance = round($totalCr - $totalDr, 2);
		return $openingBalance;
	}
	
	//Start Ledger Report
    public function ledger(request $request)
    {
		$userId = currentOwnerId();
		checkCoreAccess('Account Ledgers');
		
		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = getAccessCompanyId($request);
			$req_type = 0;
		}
		//end ca-accountant access

		$currentDate = Carbon::now()->toDateString(); // YYYY-MM-DD	
		$propId = null;
		$ledger = "";
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$customers = DB::table('customers')
					->select('cust_name', 'id')
					->where('userId', $userId)
					->get();
					
		$vendors = DB::table('vendors')
					->select('vendor_name', 'id')
					->where('userId', $userId)
					->get();
	
		$ledgers = DB::table('journals')
			->where('added_by', $userId)
			->whereNotNull('ledger')
			->distinct()
			->pluck('ledger');

		$parties = DB::table('journals')
			->where('added_by', $userId)
			->whereNotNull('party_name')
			->where('party_name', '!=', '')
			->distinct()
			->pluck('party_name');
			
		return view('User.Reports.ledger')->with([
				'proprietorships' => $proprietorships,
				'customers' => $customers,
				'vendors' => $vendors,
				'ledgers' => $ledgers,
				'parties' => $parties,
				'req_type' => $req_type
			]);
    }
	
	private function getLedgerOpeningBalance($propId, $userId, $from, $ledger, $ledgerGroup, $partyName, $custId, $vendId)
	{
		$previousDate = Carbon::parse($from)->subDay()->toDateString();

		//Company Opening Balance
		$company = DB::table(!empty($propId) ? 'proprietorship_profiles' : 'company_profiles')
					->when(
						!empty($propId),
						fn($q) => $q->where('id', $propId),
						fn($q) => $q->where('userId', $userId)
					)
					->select(
						'opening_balance',
						'openingbalancecr',
						'openingbalancedr'
					)
					->first();

		$openingBalance = (float)($company->opening_balance ?? 0);
		$balance = $openingBalance;
		//Journal Balance till Previous Date
		$rows = $this->journalLedgerRows(
			$propId,
			$userId,
			'1900-01-01',
			$previousDate,
			$ledger,
			$ledgerGroup,
			$partyName,
			$custId,
			$vendId
		);

		foreach ($rows as $row) {
			$balance += ($row['credit'] - $row['debit']);
		}

		return [
			'opening_balance' => abs(round($balance, 2)),
			'dc' => $balance >= 0 ? 'Cr' : 'Dr'
		];
	}

	public function ajaxLedgerData(Request $r)
	{
		//echo "<pre>";print_r($_POST);exit;
		$userId  = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}

		$propId 	= $r->propId ?? null;
		$from    	= $r->from_date;
		$to      	= $r->to_date;
		$custId     = $r->custId;
		$vendId     = $r->vendId;
		$partyName  = $r->party_name;
		$ledger  	= $r->ledger_name;
		$ledgerGroup  = $r->ledger_group;

		$openingData = $this->getLedgerOpeningBalance($propId, $userId, $from, $ledger, $ledgerGroup, $partyName, $custId, $vendId);

		$opening   = $openingData['opening_balance'];
		$openingDC = $openingData['dc'];

		$balance = ($openingDC == 'Dr') ? -$opening : $opening;
		$rows = $this->journalLedgerRows($propId,$userId,$from,$to,$ledger,$ledgerGroup,$partyName,$custId,$vendId);
		//echo "<pre>";print_r($rows);exit;
		/* -------- SORT DATE WISE -------- */
		usort($rows, function ($a, $b) {
			return strtotime($b['date']) <=> strtotime($a['date']);
		});

		/* -------- RUNNING BALANCE -------- */
		$totalDr = 0;
		$totalCr = 0;

		foreach ($rows as &$row) {
			$totalDr += $row['debit'];
			$totalCr += $row['credit'];
			$balance += ($row['credit'] - $row['debit']);
			$row['balance'] = ($balance);
		}
		unset($row);

		return response()->json([
			'rows'            => $rows,
			'opening_balance' => round(abs($opening),2),
			'opening_dc'      => $openingDC,
			'closing'         => round(abs($balance),2),
			'closing_dc'      => $balance >= 0 ? 'Cr' : 'Dr',
			'total_debit'     => round($totalDr,2),
			'total_credit'    => round($totalCr,2),
		]);
	}
	
	private function journalLedgerRows($propId, $userId, $from, $to, $ledger = null, $ledgerGroup = null, $partyName = null,$custId = null,$vendId = null)
	{
		$rows = [];
			
		$query = DB::table('journals as j')
					->leftJoin('payment_vouchers as pv', function ($join) {
						$join->on('pv.f_id', '=', 'j.autoId')
							 ->on('pv.source', '=', 'j.entry_type')
							 ->whereRaw('pv.id = (
								 SELECT MAX(id)
								 FROM payment_vouchers p2
								 WHERE p2.f_id = j.autoId
								 AND p2.source = j.entry_type
							 )');
					})
					->whereBetween('j.journal_date', [$from, $to])
					->select(
						'j.*',
						'pv.transaction_details',
						'pv.voucher_type'
					);

		// ================= FILTER: PROP OR USER =================
		if (!empty($propId)) {
			$query->where('j.propId', $propId);
		} else {
			$query->where('j.added_by', $userId);
		}
		// ================= FILTER: cutomer and vendor =================
		if (!empty($custId)) {
			$ids = DB::table('sales')
				->where('inv_name', $custId)
				->pluck('id')
				->toArray();

			$query->where('j.entry_type', 'Sales')
				  ->whereIn('autoId', $ids);
		}

		if (!empty($vendId)) {

			// ---------------- PURCHASE ----------------
			$purchaseIds = DB::table('purchases')
				->where('inv_name', $vendId)   
				->pluck('id')
				->toArray();

			// ---------------- EXPENSE ----------------
			$expenseIds = DB::table('expenses')
				->where('vendor_id', $vendId)
				->pluck('id')
				->toArray();

			// ---------------- ASSETS ----------------
			$assetIds = DB::table('assets')
				->where('vendor_id', $vendId)
				->pluck('id')
				->toArray();

			// Merge all IDs
			$allIds = array_merge($purchaseIds, $expenseIds, $assetIds);

			$query->where(function ($q) {
				$q->where('j.entry_type', 'Purchase')
				  ->orWhere('j.entry_type', 'Expense')
				  ->orWhere('j.entry_type', 'Asset');
			})
			->whereIn('j.autoId', $allIds);
		}
		
		/*
		|--------------------------------------------------------------------------
		| Customer / Vendor Name Filter
		|--------------------------------------------------------------------------
		*/

		if (!empty($partyName)) {
			$query->where('j.party_name', $partyName);
		}

		// ================= FILTER: LEDGER TYPE =================
		if (!empty($ledger)) {
			$query->where(function ($q) use ($ledger) {
				$q->where('j.ledger', $ledger)
				  ->orWhere('j.party_name', $ledger);
			});
		}

		// ================= FILTER: LEDGER GROUP =================
		if (!empty($ledgerGroup)) {
			$query->where('j.entry_type', $ledgerGroup);
		}

		$journals = $query->orderBy('j.journal_date', 'desc')->get();
		$grouped = $journals->groupBy(function ($item) {
			return $item->reference_no.'_'.$item->entry_type.'_'.$item->autoId;
		});
		
		foreach ($grouped as $entries) {

			$first = $entries->first();

			$debitLedger = '';
			$creditLedger = '';

			$debit = 0;
			$credit = 0;

			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			foreach ($entries as $j) {

				$amount  = (float)$j->amount;
				$gstRate = (float)$j->gst_rate;

				if($gstRate>0){

					if($j->gst_trans=='intrastate' || $j->gst_trans=='union'){

						$cgst += ($amount*($gstRate/2))/100;
						$sgst += ($amount*($gstRate/2))/100;

					}elseif($j->gst_trans=='interstate'){

						$igst += ($amount*$gstRate)/100;

					}
				}

				if(strtolower($j->debit_credit)=='debit'){

					$debitLedger = $j->party_name ?: $j->ledger;
					$debit += $amount;

				}else{

					$creditLedger = $j->party_name ?: $j->ledger;
					$credit += $amount;

				}

			}

			$rows[] = [
				'date'=>$first->journal_date,
				'journal_no'=>$first->journal_no ?? '-',
				'voucher'=>$first->reference_no ?? '-',
				'type'=>$first->voucher_type ?? '',
				'source'=>$first->source ?? '-',
				'transaction_details'=>$first->transaction_details ?? '-',
				'ledgername' => $first->ledger ?? '',
				'counter' => $first->party_name ?? $first->ledger ?? '-',
				'debit_ledger'=>$debitLedger,
				'credit_ledger'=>$creditLedger,
				'narration'=>$first->notes,
				'cgst'=>round($cgst,2),
				'sgst'=>round($sgst,2),
				'igst'=>round($igst,2),
				'debit'=>$debit,
				'credit'=>$credit,
				'balance'=>0,
				'payment_status'=>$first->payment_status ?? '',
				'status'=>$first->status ?? ''
			];
		}
		
		return $rows;
	}	
	//End Ledger Report

	
	public function fetch_ledger_data(Request $request)
    {
        $financialYear = $request->input('financial_year'); // e.g., "2024-2025"
        $periodType = $request->input('period_type');       // e.g., "monthly", "quarterly", etc.
        $dynamicPeriod = $request->input('dynamic_period'); // e.g., "april", "april-june", etc.
        $userId = currentOwnerId();

        // Parse the financial year to determine start and end years
        [$startYear, $endYear] = explode('-', $financialYear);

        // Initialize start and end dates
        $startDate = null;
        $endDate = null;

        // Determine start and end dates based on the selected period type
        if ($periodType === 'monthly') {
            // Map months to their numeric equivalents
            $monthsMap = [
                'april' => 4,
                'may' => 5,
                'june' => 6,
                'july' => 7,
                'august' => 8,
                'september' => 9,
                'october' => 10,
                'november' => 11,
                'december' => 12,
                'january' => 1,
                'february' => 2,
                'march' => 3
            ];

            $month = $monthsMap[$dynamicPeriod];
            $year = ($month >= 4) ? $startYear : $endYear; // April to December -> startYear, January to March -> endYear

            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Kolkata')->endOfMonth()->endOfDay(); // Correct last day of the selected month
            // $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay(); // Correct last day of the selected month

        } elseif ($periodType === 'quarterly') {
            // Define quarters and their month ranges
            $quartersMap = [
                'april-june' => [4, 6],
                'july-september' => [7, 9],
                'October–December' => [10, 12],
                'january-march' => [1, 3]
            ];
            [$startMonth, $endMonth] = $quartersMap[$dynamicPeriod];
            $startYearForQuarter = ($startMonth >= 4) ? $startYear : $endYear;
            $endYearForQuarter = ($endMonth >= 4) ? $startYear : $endYear;
            $startDate = Carbon::create($startYearForQuarter, $startMonth, 1)->startOfDay();
            // Adjusted to ensure we get the last date of the quarter, not just the first date of the next month

            $endDate = Carbon::create($endYearForQuarter, $endMonth, 1, 0, 0, 0, 'Asia/Kolkata')->endOfMonth()->endOfDay();
        } elseif ($periodType === 'half-yearly') {
            // Define half-year period
            $halfYearMap = [
                'april-september' => [4, 9],
                'october-march' => [10, 3]
            ];

            [$startMonth, $endMonth] = $halfYearMap[$dynamicPeriod];
            $startYearForHalfYear = ($startMonth >= 4) ? $startYear : $endYear;
            $endYearForHalfYear = ($endMonth >= 4) ? $startYear : $endYear;

            $startDate = Carbon::create($startYearForHalfYear, $startMonth, 1)->startOfDay();
            // Adjusted to ensure correct end date for half-yearly periods
            $endDate = Carbon::create($endYearForHalfYear, $endMonth, 1, 0, 0, 0, 'Asia/Kolkata')->endOfMonth()->endOfDay();
        } elseif ($periodType === 'full-yearly') {
            // Full year: From April 1st of the start year to March 31st of the end year
            $startDate = Carbon::create($startYear, 4, 1)->startOfDay();
            $endDate = Carbon::create($endYear, 3, 31, 0, 0, 0, 'Asia/Kolkata')->endOfDay();
        }
        // Fetch total amount from the sales table
        $totalAmounts = \DB::table('sales')
            ->join('sales_values', 'sales.id', '=', 'sales_values.sid')
            ->join('products', 'sales_values.prod_id', '=', 'products.id')
            ->where('sales.added_by', $userId)
            ->whereBetween('sales.inv_date', [$startDate, $endDate])
            ->selectRaw("
                SUM(CASE WHEN products.item_type = 'reseller' THEN products.selling_price * sales_values.quantity ELSE 0 END) as total_reseller,
                SUM(CASE WHEN products.item_type = 'service' THEN products.selling_price * sales_values.quantity ELSE 0 END) as total_service
            ")
            ->first();
        $totalReseller = $totalAmounts->total_reseller;
        $totalService = $totalAmounts->total_service;
        //--------- Other Income  -------
        $totalIncome = \DB::table('income')
            ->where('addBy', $userId)
            ->whereBetween('dateInput', [$startDate, $endDate])
            ->selectRaw("
                SUM(CASE WHEN categoryIncome = 'Interest Income' THEN amount ELSE 0 END) as total_interest_income,
                SUM(CASE WHEN categoryIncome = 'Dividend Income' THEN amount ELSE 0 END) as total_dividend_income,
                SUM(CASE WHEN categoryIncome = 'Rental Income' THEN amount ELSE 0 END) as total_rental_income,
                SUM(CASE WHEN categoryIncome = 'Profit on Sale of Investments' THEN amount ELSE 0 END) as total_profit_on_sale,
                SUM(CASE WHEN categoryIncome = 'Other Non-operating Income' THEN amount ELSE 0 END) as total_other_income
            ")
            ->first();
        $totalInterestIncome = $totalIncome->total_interest_income;
        $totalDividendIncome = $totalIncome->total_dividend_income;
        $totalRentalIncome = $totalIncome->total_rental_income;
        $totalProfitOnSale = $totalIncome->total_profit_on_sale;
        $totalOtherIncome = $totalIncome->total_other_income;
        //----- Fetch Purchased Table Data --------

        $totalPurchaseAmounts = \DB::table('purchases')
            ->join('purchase_values', 'purchases.id', '=', 'purchase_values.sid')
            ->join('products', 'purchase_values.prod_id', '=', 'products.id')
            ->where('purchases.added_by', $userId)
            ->whereBetween('purchases.inv_date', [$startDate, $endDate])
            ->selectRaw("
                SUM(CASE WHEN products.item_type = 'reseller' THEN products.selling_price * purchase_values.quantity ELSE 0 END) as total_reseller,
                SUM(CASE WHEN products.item_type = 'service' THEN products.selling_price * purchase_values.quantity ELSE 0 END) as total_service
            ")
            ->first();
        $totalPurchaseReseller = $totalPurchaseAmounts->total_reseller;
        $totalPurchaseService = $totalPurchaseAmounts->total_service;
        $totalPurchaseTotal = $totalPurchaseReseller + $totalPurchaseService;
        //----------- Fecth assets table data ---------
        $assets_data = \DB::table('assets')
            ->where('added_by', $userId)
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->get();
        //----------- Fecth Banks table data ---------
        $banks_data = \DB::table('banks')
            ->where('added_by', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        //----- Fetch liabilities Table Data --------
        $liabilities_data = \DB::table('liabilities')
            ->where('added_by', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        //--------- Fetch Expenses Table Data --------
        $expenses_data = \DB::table('expenses')
            ->where('added_by', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        echo "<pre>";
        print_r($expenses_data);
        echo "</pre>";
        exit;
        return response()->json([
            'success' => true,

            'totalReseller' => $totalReseller,
            'totalService' => $totalService,

            'totalInterestIncome' => $totalInterestIncome,
            'totalDividendIncome' => $totalDividendIncome,
            'totalRentalIncome' => $totalRentalIncome,
            'totalProfitOnSale' => $totalProfitOnSale,
            'totalOtherIncome' => $totalOtherIncome,

            // 'total_sales_income' => $total_sales_income,

            'totalPurchaseTotal' => $totalPurchaseTotal,

            'assets_data' => $assets_data,
            'banks_data' => $banks_data,
            'liabilities_data' => $liabilities_data,
            'expenses_data' => $expenses_data,

            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
	
	/*------Start for Cashflow---------------------------*/
	public function getOpeningBalanceCashFlow($userId)
	{
		$openingBal = DB::table('company_profiles')
			->where('userId', $userId)
			->value('opening_balance');
		$openingBal = $openingBal ?? 0;
		return $openingBal;
	}
	
	
	public function getPreviousFYOpeningBalance_cashflow($propId, $userId, $fromDate, $paymentMode = 'all',$bankId = null)
	{
		$openingDate = Carbon::parse($fromDate)->subDay()->toDateString();
		//$openingDate = $fromDate;

		/*
		|--------------------------------------------------------------------------
		| 1. Legacy Cash Opening (mcash_credit_debits)
		|--------------------------------------------------------------------------
		*/
		$cashQuery = DB::table('mcash_credit_debits');

		if (!empty($propId)) {
			$cashQuery->where('propId', $propId);
		} else {
			$cashQuery->where('added_by', $userId);
		}

		$legacyCash = (float) $cashQuery
			->whereDate('cd_date', '<=', $openingDate)
			->selectRaw("
				COALESCE(
					SUM(
						CASE
							WHEN LOWER(cd_type)='cr' THEN cd_amount
							ELSE -cd_amount
						END
					),0
				) as balance
			")
			->value('balance');

		/*
		|--------------------------------------------------------------------------
		| 2. Legacy Bank Opening (banks.curr_bal)
		|--------------------------------------------------------------------------
		*/
		$bankQuery = DB::table('banks');

		if (!empty($propId)) {
			$bankQuery->where('propId', $propId);
		} else {
			$bankQuery->where('added_by', $userId);
		}

		if (!empty($bankId)) {
			$bankQuery->where('id', $bankId);
		}
		$legacyBank = (float) $bankQuery->sum('curr_bal');

		/*
		|--------------------------------------------------------------------------
		| 3. Payment Voucher Adjustment
		|--------------------------------------------------------------------------
		*/
		$voucherQuery = DB::table('payment_vouchers');

		if (!empty($propId)) {
			$voucherQuery->where('propId', $propId);
		} else {
			$voucherQuery->where('added_by', $userId);
		}

		$voucherQuery->whereDate('date', '<', $openingDate);

		if ($paymentMode == 'cash') {
			$voucherQuery->where('payment_mode','Cash');
			$voucher = $voucherQuery
				->selectRaw("
					SUM(
						CASE
							WHEN LOWER(credit_debit)='credit'
							THEN amount
							ELSE -amount
						END
					) as cash_balance
				")
				->first();

			$cashBalance = $legacyCash + ($voucher->cash_balance ?? 0);

			return [
				'cash'=>round($cashBalance,2),
				'bank'=>0,
				'total'=>round($cashBalance,2)
			];
		}
		// Bank selected -> Bank only
		if ($paymentMode == 'bank' || !empty($bankId)) {

			$bankVoucher = clone $voucherQuery;

			$bankVoucher->where('bank_id', $bankId);

			$bankVoucher = $bankVoucher
				->selectRaw("
					SUM(
						CASE
							WHEN LOWER(credit_debit)='credit'
							THEN amount
							ELSE -amount
						END
					) as bank_balance
				")
				->first();

			$bankBalance = $legacyBank + ($bankVoucher->bank_balance ?? 0);

			return [
				'cash'  => 0,
				'bank'  => round($bankBalance,2),
				'total' => round($bankBalance,2)
			];
		}
		if ($paymentMode == 'all') {

			$cashVoucher = clone $voucherQuery;

			$cashVoucher = $cashVoucher
				->where('payment_mode','Cash')
				->selectRaw("
					SUM(
						CASE
							WHEN LOWER(credit_debit)='credit'
							THEN amount
							ELSE -amount
						END
					) as cash_balance
				")
				->first();

			$bankVoucher = clone $voucherQuery;

			//$bankVoucher->whereIn('payment_mode',['Bank','UPI']);
			if (!empty($bankId)) {
				$bankVoucher->where('bank_id', $bankId);
			} else {
				$bankVoucher->whereIn('payment_mode', ['Bank', 'UPI']);
			}

			if(!empty($bankId)){
				$bankVoucher->where('bank_id',$bankId);
			}

			$bankVoucher = $bankVoucher
				->selectRaw("
					SUM(
						CASE
							WHEN LOWER(credit_debit)='credit'
							THEN amount
							ELSE -amount
						END
					) as bank_balance
				")
				->first();

			$cashBalance = $legacyCash + ($cashVoucher->cash_balance ?? 0);
			$bankBalance = $legacyBank + ($bankVoucher->bank_balance ?? 0);

			return [
				'cash'=>round($cashBalance,2),
				'bank'=>round($bankBalance,2),
				'total'=>round($cashBalance+$bankBalance,2)
			];
		}
	}
	
	public function cashflow(request $request)
    {
		$userId = currentOwnerId();
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		$propId = null;
		checkCoreAccess('Cashflow Statement');
		$currentDate = Carbon::now()->toDateString(); // YYYY-MM-DD	
		$previousDate = Carbon::now()->subDay()->toDateString();
		$paymentMode = 'all';
		$bankId = null;
		$bankDetails = DB::table('banks')
					->select('id', 'bank_name')
					->where('added_by', $userId)
					->where('status', 1)
					->get();
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		return view('User.Reports.cashflow')->with([
				'bankDetails' => $bankDetails,
				'proprietorships' => $proprietorships
			]);
    }
	
	
	public function ajaxCashFlowData(Request $r)
	{
		$userId = currentOwnerId();

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$propId = $r->propId ?? null;
		$from   = $r->from_date;
		$to     = $r->to_date;

		$cashflowType = strtolower($r->cashflow_type ?? 'all');
		$voucherType  = strtolower($r->voucher_type ?? 'all');
		$paymentMode  = strtolower($r->payment_mode ?? 'all');
		$bankId = $r->bank_id ?? '';

		$openingData = $this->getPreviousFYOpeningBalance_cashflow(
			$propId,
			$userId,
			$from,
			$paymentMode,
			$bankId
		);

		$openingCash = $openingData['cash'];
		$openingBank = $openingData['bank'];
		$opening = $openingCash + $openingBank;
		// Initialize closing balances
		$closingCash = $openingCash;
		$closingBank = $openingBank;

		$query = DB::table('payment_vouchers as pv')
					->leftJoin('banks as b', 'b.id', '=', 'pv.bank_id')
					->select('pv.*', 'b.bank_name')
					->whereBetween('pv.date', [$from, $to]);

		if(!empty($propId)){
			$query->where('pv.propId',$propId);
		}else{
			$query->where('pv.added_by',$userId);
		}

		if($voucherType!='all'){
			if($voucherType=='payment'){
				$query->where('pv.voucher_type','Payment Voucher');
			}
			if($voucherType=='receipt'){
				$query->where('pv.voucher_type','Receipt Voucher');
			}
		}

		if (!empty($bankId)) {
			$query->whereIn('pv.payment_mode', ['Bank', 'UPI'])
				  ->where('pv.bank_id', $bankId);
		} elseif ($paymentMode == 'cash') {
			$query->where('pv.payment_mode', 'Cash');
		} elseif ($paymentMode == 'bank') {
			$query->whereIn('pv.payment_mode', ['Bank', 'UPI']);
		}

		$transactions = $query
			->orderBy('date')
			->orderBy('id')
			->get();

		$rows=[];

		foreach($transactions as $t){

			$activity='Operating';

			if(in_array(strtolower($t->source),['asset'])){
				$activity='Investing';
			}

			if(in_array(strtolower($t->source),['loan','capital'])){
				$activity='Financing';
			}

			if($cashflowType!='all'){
				if(
					strtolower($activity)!=ucfirst($cashflowType)
					&&
					strtolower($activity)!=strtolower($cashflowType)
				){
					continue;
				}
			}

			$amount=(float)$t->amount;

			$inflow=0;
			$outflow=0;

			if(strtolower($t->voucher_type)=='receipt voucher'){
				$inflow=$amount;
			}else{
				$outflow=$amount;
			}
			// Calculate closing Cash / Bank separately
			if (strtolower($t->voucher_type) == 'receipt voucher') {
				if (strtolower($t->payment_mode) == 'cash') {
					$closingCash += $amount;
				} else {
					$closingBank += $amount;
				}
			} else {
				if (strtolower($t->payment_mode) == 'cash') {
					$closingCash -= $amount;
				} else {
					$closingBank -= $amount;
				}
			}

			$rows[]=[
				'date'=>$t->date,
				'voucher_no'=>$t->voucher_no,
				'voucher_type'=>$t->voucher_type,
				'transaction_details'=>$t->transaction_details ?? '',
				'activity'=>$activity,
				'source'=>$t->source,
				'party'=>$t->party_name,
				'ledger'=> $t->payment_mode == 'Cash'? 'Cash': ($t->bank_name ?? 'UPI'),
				'mode'=>$t->payment_mode,
				'narration'=>$t->narration,
				'inflow'=>$inflow,
				'outflow'=>$outflow,
				'balance'=>0,
				'dc'=>''
			];
		}

		usort($rows, function ($a, $b) {
			if ($a['date'] == $b['date']) {
				return strcmp($a['voucher_no'], $b['voucher_no']);
			}
			return strtotime($a['date']) <=> strtotime($b['date']);
		});

		$balance=$opening;

		$totalIn=0;
		$totalOut=0;

		foreach($rows as &$row){
			$balance += $row['inflow'];
			$balance -= $row['outflow'];
			$totalIn += $row['inflow'];
			$totalOut += $row['outflow'];
			$row['balance']=round($balance,2);
			$row['dc']=$row['inflow']>0 ? 'In' : 'Out';
		}
		unset($row);
		$closingBalance = $opening + $totalIn - $totalOut;
		
		return response()->json([
			'rows'           => $rows,
			'opening'        => round($opening, 2),
			'opening_cash'   => round($openingCash, 2),
			'opening_bank'   => round($openingBank, 2),
			'closing'        => round($closingBalance, 2),
			'closing_cash'   => round($closingCash, 2),
			'closing_bank'   => round($closingBank, 2),
			'total_in'       => round($totalIn, 2),
			'total_out'      => round($totalOut, 2)
		]);

	}
	
	private function resolveLedgerType($mode)
	{
		$mode = strtolower(trim($mode));

		$bankModes = [
			'upi', 'imps', 'neft', 'rtgs',
			'net banking', 'online',
			'bank', 'card', 'cheque', 'dd'
		];

		if ($mode === 'cash') {
			return 'Cash';
		}

		if (in_array($mode, $bankModes)) {
			return 'Bank';
		}

		// Default fallback
		return 'Bank';
	}
	

	public function getOpeningBalanceAjax()
	{
		$userId = currentOwnerId();

		$currentDate = Carbon::now()->toDateString();

		$propId = null;
		$ledger = "";

		$opening = $this->getOpeningBalanceFromJournal(
			$ledger,
			$userId,
			$currentDate,
			$propId
		);

		$openingDr = $opening['dr'];
		$openingCr = $opening['cr'];

		$openingBalance = $openingCr - $openingDr;

		// Check company profile opening balance
		$companyOpeningBalance = DB::table('company_profiles')
			->where('userId', $userId)
			->value('opening_balance');

		// If journal opening balance is 0
		if ($openingBalance == 0) {

			// If company profile opening balance not set
			if ($companyOpeningBalance === null || $companyOpeningBalance === '') {

				return response()->json([
					'status' => false,
					'message' => 'Please Set The Opening Balance In Company Profile'
				]);
			}

			$openingBalance = $companyOpeningBalance;
		}

		return response()->json([
			'status' => true,
			'openingBalance' => $openingBalance
		]);
	}


}
