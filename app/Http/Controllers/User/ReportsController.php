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
		checkCoreAccess('Financial Reports');
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
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		return view('User.Reports.trial-balance')->with([
				'openingDr' => $openingDr,
				'openingCr' => $openingCr,
				'proprietorships' => $proprietorships,
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
	
	public function getPreviousFYOpeningBalance_DR_CR($propId, $userId, $fromDate)
	{
		// Step 1: Previous FY dates
		[$prevFrom, $prevTo] = $this->getPreviousFYRange($fromDate);

		// Step 2: Collect ALL ledger rows for previous FY
		$rows = [];

		$rows = array_merge($rows, $this->customerLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->supplierLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->salesLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->voucherLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->incomeLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->purchaseLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->purchaseVoucherLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->expenseLedgerRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->gstInputRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->gstOutputRows($propId, $userId, $prevFrom, $prevTo));

		// Step 3: Calculate totals
		$totalDr = 0;
		$totalCr = 0;

		foreach ($rows as $r) {
			$totalDr += (float) ($r['debit'] ?? 0);
			$totalCr += (float) ($r['credit'] ?? 0);
		}

		return [
			'opening_dr' => round($totalDr, 2),
			'opening_cr' => round($totalCr, 2),
			'prev_from'  => $prevFrom,
			'prev_to'    => $prevTo,
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
		$ledgerSubGroup   = $r->ledger_sub_group;

		// ================= FETCH JOURNAL =================
		$journals = DB::table('journals')
				->where('added_by', $userId)
				->when($propId, fn($q) => $q->where('propId', $propId))
				->whereBetween('journal_date', [$from, $to])
				->where(function($q) {
					$q->whereNull('rev_amend_status')
					  ->orWhere('rev_amend_status', '!=', 'reverse');
				})
				->get();

		$trial = [];
		//echo "<pre>";print_r($journals);exit;
		foreach ($journals as $row) {

			$ledger = $row->ledger ?: 'Unknown';

			$group    = $this->getLedgerGroup($row);
			$subGroup = $this->getLedgerSubGroup($row);

			// ================= FILTER SUPPORT =================
			if ($ledgerFilter && $ledgerFilter !== 'all' && $ledger !== $ledgerFilter) continue;
			if ($ledgerGroup && strtolower($group) !== strtolower($ledgerGroup)) continue;
			if ($ledgerSubGroup && strtolower($subGroup) !== strtolower($ledgerSubGroup)) continue;

			// ================= INIT =================
			if (!isset($trial[$group][$subGroup][$ledger])) {

				//Opening Balance (IMPORTANT FIX)
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
			if (strtolower($row->debit_credit) === 'debit') {
				//$trial[$group][$subGroup][$ledger]['debit'] += $row->tot_amt;
				$trial[$group][$subGroup][$ledger]['debit'] += $row->amount;
			} else {
				//$trial[$group][$subGroup][$ledger]['credit'] += $row->tot_amt;
				$trial[$group][$subGroup][$ledger]['credit'] += $row->amount;
			}
		}

		// ================= CLOSING =================
		$totalDr = 0;
		$totalCr = 0;

		foreach ($trial as &$subs) {
			foreach ($subs as &$ledgers) {
				foreach ($ledgers as &$v) {

					$net = ($v['opening_dr'] + $v['debit'])
						 - ($v['opening_cr'] + $v['credit']);

					if ($net > 0) {
						$v['closing_dr'] = $net;
						$v['closing_cr'] = 0;
					} else {
						$v['closing_dr'] = 0;
						$v['closing_cr'] = abs($net);
					}

					$totalDr += $v['closing_dr'];
					$totalCr += $v['closing_cr'];
				}
			}
		}

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
		// Step 1: Previous FY dates
		[$prevFrom, $prevTo] = $this->getPreviousFYRange($fromDate);
		//echo $prevFrom;echo $prevTo;exit; //2024-04-01,2025-03-31
		$rows = DB::table('journals')
			->where('added_by', $userId)
			->where('ledger', $ledger)
			->when($propId, fn($q) => $q->where('propId', $propId))
			//->whereBetween('journal_date', [$prevFrom, $prevTo])
			->where('journal_date', '<', $fromDate)
			->get();

		$dr = 0;
		$cr = 0;

		foreach ($rows as $r) {
			if (strtolower($r->debit_credit) === 'debit') {
				$dr += $r->tot_amt;
			} else {
				$cr += $r->tot_amt;
			}
		}

		return ['dr' => $dr, 'cr' => $cr];
	}
	
	private function getLedgerGroup($row)
	{
		$ledger = strtolower($row->ledger);

		//GST FIRST (highest priority)
		if (str_contains($ledger, 'gst') || str_contains($ledger, 'cgst') || str_contains($ledger, 'sgst') || str_contains($ledger, 'igst')) {
			return 'Duties & Taxes';
		}
		
		if ($row->source === 'Asset') return 'Assets';
		if ($row->source === 'Liability') return 'Liabilities';
		if ($row->source === 'Sales' || $row->source === 'Income' || str_contains($ledger, 'sales')) return 'Income';
		if ($row->source === 'Purchase' || $row->source === 'Expense' || str_contains($ledger, 'purchase')) return 'Expenses';
		//BANK FIX
		if (str_contains($ledger, 'bank') || str_contains($ledger, 'cash')) {
			//return 'Bank';
			return 'Assets';
		}

		return 'Others';
	}
	
	private function getLedgerSubGroup($row)
	{
		$ledger = strtolower($row->ledger);

		if ($row->party_name === 'Customer' || str_contains($ledger, 'customer')) return 'Customer';
		if ($row->party_name === 'Vendor' || str_contains($ledger, 'supplier')) return 'Vendor';
		if (str_contains($ledger, 'cgst') || str_contains($ledger, 'sgst') || str_contains($ledger, 'igst')) {
			return 'GST';
		}

		return '';
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
			->where('bt.prop_id', $propId)
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

				if ($voucher->date == $tranDate) {
					$score += 20;
				} elseif (in_array($voucher->date, $datesToCheck)) {
					$score += 10;
				}

				if (
					!empty($voucher->reference_id)
					&&
					!empty($bankTran->ref_no)
				) {

					if (
						trim($voucher->reference_id)
						==
						trim($bankTran->ref_no)
					) {

						$score += 40;
					}
				}

				if (
					!empty($bankTran->purpose)
					&&
					!empty($voucher->party_name)
				) {

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
					'score'          => $bestScore,
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
					'score'          => $bestScore,
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
		checkCoreAccess('Financial Reports');
		
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
		//$openingBalance = $this->getPreviousFYOpeningBalance_ledger($propId, $userId, $currentDate);
		$opening = $this->getOpeningBalanceFromJournal($ledger, $userId, $currentDate, $propId);
		//echo "<pre>";print_r($opening);exit;
		$openingDr = $opening['dr'];
		$openingCr = $opening['cr'];
		$openingBalance = $openingCr - $openingDr;
		if($openingBalance == 0){
			$openingBalance = $this->getOpeningBalance($userId);
		}
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
				'openingBalance' => $openingBalance,
				'proprietorships' => $proprietorships,
				'customers' => $customers,
				'vendors' => $vendors,
				'ledgers' => $ledgers,
				'parties' => $parties,
				'req_type' => $req_type
			]);
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
		$opening = (float)($r->opening_balance ?? 0);
		$openingDC = $r->opening_dc ?? 'Cr';

		// Opening signed balance
		$balance = $openingDC === 'Dr' ? -$opening : $opening;

		$rows = $this->journalLedgerRows($propId,$userId,$from,$to,$ledger,$ledgerGroup,$partyName,$custId,$vendId);

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

			// Core accounting formula
			$balance += ($row['credit'] - $row['debit']);

			$row['dc'] = $row['dc']; //$balance >= 0 ? 'Cr' : 'Dr';
			$row['balance'] = ($balance);
		}
		unset($row);

		return response()->json([
			'rows'          => $rows,
			'closing'       => ($balance),
			'total_debit'   => $totalDr,
			'total_credit'  => $totalCr,
			'dc'            => $balance >= 0 ? 'Cr' : 'Dr'
		]);
	}
	
	private function journalLedgerRows($propId, $userId, $from, $to, $ledger = null, $ledgerGroup = null, $partyName = null,$custId = null,$vendId = null)
	{
		$rows = [];

		$query = DB::table('journals')
			->where('status', 'Posted')
			->whereBetween('journal_date', [$from, $to]);

		// ================= FILTER: PROP OR USER =================
		if (!empty($propId)) {
			$query->where('propId', $propId);
		} else {
			$query->where('added_by', $userId);
		}
		// ================= FILTER: cutomer and vendor =================
		if (!empty($custId)) {
			$ids = DB::table('sales')
				->where('inv_name', $custId)
				->pluck('id')
				->toArray();

			$query->where('entry_type', 'Sales')
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
				$q->where('entry_type', 'Purchase')
				  ->orWhere('entry_type', 'Expense')
				  ->orWhere('entry_type', 'Asset');
			})
			->whereIn('autoId', $allIds);
		}
		
		/*
		|--------------------------------------------------------------------------
		| Customer / Vendor Name Filter
		|--------------------------------------------------------------------------
		*/

		if (!empty($partyName)) {
			$query->where('party_name', $partyName);
		}

		// ================= FILTER: LEDGER TYPE =================
		if (!empty($ledger) && $ledger !== 'all') {
			$query->where(function ($q) use ($ledger) {
				$q->where('entry_type', ucfirst($ledger))
				  ->orWhere('ledger', ucfirst($ledger));
			});
		}

		// ================= FILTER: LEDGER GROUP =================
		if (!empty($ledgerGroup)) {
			if ($ledgerGroup === 'assets') {
				$query->where('entry_type', 'Asset');
			} elseif ($ledgerGroup === 'liabilities') {
				$query->where('entry_type', 'Liability');
			} elseif ($ledgerGroup === 'income') {
				$query->where('entry_type', 'Income');
			} elseif ($ledgerGroup === 'expenses') {
				$query->where('entry_type', 'Expense');
			}
		}

		$journals = $query->orderBy('journal_date', 'desc')->get();

		// ================= LOOP =================
		foreach ($journals as $j) {

			$amount  = (float) ($j->amount ?? 0);
			$gstRate = (float) ($j->gst_rate ?? 0);
			$gstType = $j->gst_trans ?? '';
			$dcType  = strtolower(trim($j->debit_credit ?? ''));

			// ================= GST CALC =================
			$cgst = $sgst = $igst = 0;

			if ($gstRate > 0 && $amount > 0) {
				if ($gstType === 'intrastate' || $gstType === 'union') {
					$cgst = ($amount * ($gstRate / 2)) / 100;
					$sgst = ($amount * ($gstRate / 2)) / 100;
				} elseif ($gstType === 'interstate') {
					$igst = ($amount * $gstRate) / 100;
				}
			}

			// ================= DR / CR =================
			$debit  = ($dcType === 'debit') ? $amount : 0;
			$credit = ($dcType === 'credit') ? $amount : 0;

			$rows[] = [
				'date'       => $j->journal_date,
				'voucher'    => $j->reference_no ?? '-',
				'type'       => $j->entry_type ?? '',
				'counter'    => $j->party_name ?? $j->ledger ?? '-',
				'narration'  => $j->notes ?? '',

				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),

				'bank'       => '',
				'group'      => $j->entry_type ?? '',
				'sub_group'  => $j->ledger ?? '',

				'debit'  => $debit,
				'credit' => $credit,
				'payment_status' => $j->payment_status,

				'balance'    => 0,
				'dc'         => ($dcType === 'credit') ? 'Cr' : 'Dr',
				'ledgername' => $j->ledger ?? ''
			];
		}

		return $rows;
	}	
	//End Ledger Report
	
	private function customerLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		// ================= CURRENT ASSETS =================
		$query = DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->select(
				'ac.*',
				'a.date as asset_date'
			);

		if (!empty($propId)) {
			$query->where('a.propId', $propId);
		} else {
			$query->where('a.added_by', $userId);
		}

		$currentAssets = $query->get();

		foreach ($currentAssets as $a) {

			$amount   = (float)($a->amt ?? 0);
			$gstRate  = (float)($a->gst_rate ?? 0);
			$gstType  = $a->gst_trans ?? '';
			$dcType   = strtolower(trim($a->debitcredit ?? ''));

			// ================= GST Calculation =================
			$cgst = $sgst = $igst = 0;

			if ($gstRate > 0) {
				if ($gstType === 'intrastate' || $gstType === 'union') {
					$cgst = ($amount * ($gstRate / 2)) / 100;
					$sgst = ($amount * ($gstRate / 2)) / 100;
				} elseif ($gstType === 'interstate') {
					$igst = ($amount * $gstRate) / 100;
				}
			}

			// ================= Debit / Credit Logic =================
			$debit  = ($dcType === 'debit') ? $amount : 0;
			$credit = ($dcType === 'credit') ? $amount : 0;
			$dc     = ($dcType === 'credit') ? 'Cr' : 'Dr';

			$rows[] = [
				'date'       => $a->asset_date,
				'voucher'    => $a->invoice_no ?? '-',
				'type'       => $a->voucher_type ?? '',
				'counter'    => $a->currentAssetType ?? 'Current Asset',
				'narration'  => $a->notes ?? 'Current Asset',

				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),

				'bank'       => '',
				'group'      => 'Asset',
				'sub_group'  => 'Current Asset',

				'debit'  => $debit,
				'credit' => $credit,

				'balance'    => 0,
				'dc'         => $dc,
				'ledgername' => 'Customer Ledger'
			];
		}


		// ================= NON CURRENT ASSETS =================
		$queryNca = DB::table('assets_ncs as anc')
			->join('assets as a', 'a.id', '=', 'anc.asid')
			->where('a.isActive', 1) //only active records
			->whereBetween('a.date', [$from, $to])
			->select(
				'anc.*',
				'a.date as asset_date'
			);

		if (!empty($propId)) {
			$queryNca->where('a.propId', $propId);
		} else {
			$queryNca->where('a.added_by', $userId);
		}

		$nonCurrentAssets = $queryNca->get();
		//echo "<pre>";print_r($nonCurrentAssets);exit;

		foreach ($nonCurrentAssets as $v) {

			$amt_nca   = (float)($v->amt_nca ?? 0);
			$gstRate  = (float)($v->gst_rate_nca ?? 0);
			$gstType  = $v->gst_trans_nca ?? '';
			$dcTypeNca   = strtolower(trim($v->debitcredit_nca ?? ''));

			// ================= GST Calculation =================
			$cgst = $sgst = $igst = 0;

			if ($gstRate > 0) {
				if ($gstType === 'intrastate' || $gstType === 'union') {
					$cgst = ($amt_nca * ($gstRate / 2)) / 100;
					$sgst = ($amt_nca * ($gstRate / 2)) / 100;
				} elseif ($gstType === 'interstate') {
					$igst = ($amt_nca * $gstRate) / 100;
				}
			}

			// ================= Debit / Credit Logic =================
			$debit  = ($dcTypeNca === 'debit') ? $amt_nca : 0;
			$credit = ($dcTypeNca === 'credit') ? $amt_nca : 0;
			$dc2     = ($dcTypeNca === 'credit') ? 'Cr' : 'Dr';

			$rows[] = [
				'date'       => $v->asset_date,
				'voucher'    => $v->invoice_no_nca ?? '-',
				'type'       => $v->voucher_type_nca ?? '',
				'counter'    => $v->nonCurrentAssetType ?? 'Non Current Asset',
				'narration'  => $v->notes_nca ?? 'Non Current Asset',

				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),

				'bank'       => '',
				'group'      => 'Asset',
				'sub_group'  => 'Non Current Asset',

				'debit'  => $debit,
				'credit' => $credit,

				'balance'    => 0,
				'dc'         => $dc2,
				'ledgername' => 'Customer Ledger'
			];
		}


		// ================= MERGE OTHER MODULES =================
		$rows = array_merge($rows, $this->assetsService->inventoryRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->assetsService->tradeReceivableRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->assetsService->unbilledRevenueRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->assetsService->gstReceivableRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->assetsService->tdsReceivableRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->assetsService->vendorAdvanceRows($propId, $userId, $from, $to));

		return $rows;
	}


	private function supplierLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];
		/* ---------- CURRENT LIABILITIES ---------- */
			$query = DB::table('current_liabilities')
				->join('liabilities', 'liabilities.id', '=', 'current_liabilities.liabilities_id')
				->where('liabilities.status', 1) //only active records
				->whereBetween('liabilities.added_date', [$from, $to])
				->select(
					'current_liabilities.*',
					'liabilities.added_date'
				);

			if (!empty($propId)) {
				$query->where('liabilities.propId', $propId);
			} else {
				$query->where('liabilities.added_by', $userId);
			}

			$currentLiabs = $query->get();
			//echo "<pre>";print_r($currentLiabs);exit;
			foreach ($currentLiabs as $l) {

				$amount   = (float)($l->amount ?? 0);
				$gstRate  = (float)($l->gst_rate ?? 0);
				$gstType  = $l->gst_transaction ?? '';
				$dcType   = $l->debit_credit ?? 'Credit'; // default

				//GST Calculation
				$cgst = $sgst = $igst = 0;

				if ($gstRate > 0 && $amount > 0) {

					if ($gstType === 'intrastate' || $gstType === 'union') {
						$cgst = ($amount * ($gstRate / 2)) / 100;
						$sgst = ($amount * ($gstRate / 2)) / 100;
					} elseif ($gstType === 'interstate') {
						$igst = ($amount * $gstRate) / 100;
					}
				}
				$debit  = 0;
				$credit = 0;

				if (strtolower($dcType) === 'debit') {
					$debit = $amount;
				} else {
					$credit = $amount;
				}

				$rows[] = [
					'date'      => $l->added_date,
					'voucher'   => $l->invoice_no ?? '-',
					'type'      => $l->voucher_type ?? 'Supplier Liability',
					'counter'   => $l->CurrentLiabilitiesType,
					'narration' => $l->notes ?? '',
					'cgst' => round($cgst, 2),
					'sgst' => round($sgst, 2),
					'igst' => round($igst, 2),

					'bank'      => '',
					'group'     => 'Liability',
					'sub_group' => $l->CurrentLiabilitiesType,
					'debit'  => $debit,
					'credit' => $credit,
					'balance'   => 0,
					'dc'        => (strtolower($dcType) === 'debit') ? 'Dr' : 'Cr',
					'ledgername' => 'Supplier Ledger'
				];
			}
			
			$rows = array_merge($rows, $this->liabilitiesService->tradePayableRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->liabilitiesService->gstPayableRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->liabilitiesService->advanceFromCustomerRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->liabilitiesService->tdsPayableRows($propId, $userId, $from, $to));
			
			/* ---------- NON CURRENT LIABILITIES ---------- */
			$query = DB::table('non_current_liabilities')
				->join('liabilities', 'liabilities.id', '=', 'non_current_liabilities.liabilities_id')
				->where('liabilities.status', 1) //only active records
				->whereBetween('liabilities.added_date', [$from, $to])
				->select(
					'non_current_liabilities.*',
					'liabilities.added_date'
				);

			if (!empty($propId)) {
				$query->where('liabilities.propId', $propId);
			} else {
				$query->where('liabilities.added_by', $userId);
			}

			$nonCurrentLiabs = $query->get();

			foreach ($nonCurrentLiabs as $l) {
				
				$amount   = (float)($l->amount ?? 0);
				$gstRate  = (float)($l->gst_rate ?? 0);
				$gstType  = $l->gst_transaction ?? '';
				$dcType   = $l->debit_credit ?? 'Credit'; // default

				//GST Calculation
				$cgst = $sgst = $igst = 0;

				if ($gstRate > 0 && $amount > 0) {

					if ($gstType === 'intrastate' || $gstType === 'union') {
						$cgst = ($amount * ($gstRate / 2)) / 100;
						$sgst = ($amount * ($gstRate / 2)) / 100;
					} elseif ($gstType === 'interstate') {
						$igst = ($amount * $gstRate) / 100;
					}
				}
				$debit  = 0;
				$credit = 0;

				if (strtolower($dcType) === 'debit') {
					$debit = $amount;
				} else {
					$credit = $amount;
				}

				$rows[] = [
					'date'      => $l->added_date,
					'voucher'   => $l->invoice_no ?? '-',
					'type'      => $l->voucher_type ?? 'Supplier Liability',
					'counter'   => ucfirst(str_replace('_', ' ', $l->liability_category)),
					'narration' => $l->notes ?? '',
					'cgst' 		=> round($cgst, 2),
					'sgst' 		=> round($sgst, 2),
					'igst' 		=> round($igst, 2),
					'bank'      => '',
					'group'     => 'Liability',
					'sub_group' => ucfirst(str_replace('_', ' ', $l->liability_category)),
					'debit'  	=> $debit,
					'credit' 	=> $credit,
					'balance'   => 0,
					'dc'        => (strtolower($dcType) === 'debit') ? 'Dr' : 'Cr',
					'ledgername' => 'Supplier Ledger'
				];
			}
			
		return $rows;
	}
		
	
	private function salesLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('sales')
			->join('sales_values','sales.id','=','sales_values.sid')
			->join('customers', 'customers.id', '=', 'sales.inv_name')
			->where('sales.status', 1) //only active records
			->whereBetween('sales.inv_date', [$from, $to])
			->select(
				'sales.inv_date',
				'sales.inv_num',
				'customers.cust_name as seller_name',
				DB::raw("
					CASE WHEN sales_values.gst_trans='intrastate'
					THEN sales_values.tax_amt/2 ELSE 0 END AS cgst
				"),
				DB::raw("
					CASE WHEN sales_values.gst_trans='intrastate'
					THEN sales_values.tax_amt/2 ELSE 0 END AS sgst
				"),
				DB::raw("
					CASE WHEN sales_values.gst_trans='interstate'
					THEN sales_values.tax_amt ELSE 0 END AS igst
				"),
				DB::raw("(sales_values.amount + sales_values.tax_amt - sales_values.disc_amt) AS credit")
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('sales.propId', $propId);
		} else {
			$query->where('sales.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $d) {
			$rows[] = [
				'date'      => $d->inv_date,
				'voucher'   => $d->inv_num,
				'type'      => 'Sales',
				'counter'   => $d->seller_name,
				'narration' => 'Sales Invoice',
				'cgst'      => $d->cgst,
				'sgst'      => $d->sgst,
				'igst'      => $d->igst,
				'bank'      => '',
				'group'     => 'Income',
				'sub_group' => 'Sales',
				'debit'     => 0,
				'credit'    => $d->credit,
				'balance'   => 0,
				'dc'        => 'Cr',
				'ledgername'=> 'Sales Ledger'
			];
		}

		return $rows;
	}
	
	private function incomeLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('income')
			->where('income.status', 1) //only active records
			->whereBetween(DB::raw("
				CASE categoryIncome
					WHEN 'Interest Income' THEN dateInput
					WHEN 'Rental Income' THEN dateInput
					WHEN 'Royalty Income' THEN dateInput
					WHEN 'Other Non-operating Income' THEN dateInput
					WHEN 'Other Income' THEN dateInput
				END
			"), [$from, $to])
			->select(
				'categoryIncome',
				'specification',
				'dateInput',
				'amount'
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('income.propId', $propId);
		} else {
			$query->where('income.addBy', $userId);
		}

		$data = $query->get();

		foreach ($data as $d) {

			if ($d->amount <= 0) continue;

			$rows[] = [
				'date'      => $d->dateInput,
				'voucher'   => '-',
				'type'      => 'Income',
				'counter'   => $d->categoryIncome,
				'narration' => $d->specification ?? 'Income Entry',
				'cgst'      => 0,
				'sgst'      => 0,
				'igst'      => 0,
				'bank'      => '',
				'group'     => 'Income',
				'sub_group' => $d->categoryIncome,
				'debit'     => 0,
				'credit'    => $d->amount,
				'balance'   => 0,
				'dc'        => 'Cr',
				'ledgername'=> 'Sales Ledger'
			];
		}

		return $rows;
	}
	
	
	private function voucherLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$data = DB::table('vouchers')
			->join('customers', 'customers.id', '=', 'vouchers.v_name')
			->where('vouchers.added_by', $userId)
			->whereBetween('vouchers.inv_date', [$from, $to])
			->select(
				'inv_date',
				'inv_num',
				'v_no',
				'v_name',
				'customers.cust_name as seller_name',
				'note_type',              // Credit / Debit
				'credit_debit_amount',
				'mode_of_pay',
				'reason_issuance'
			)
			->get();

		foreach ($data as $v) {

			if ($v->credit_debit_amount <= 0) continue;

			$isCredit = strtolower($v->note_type) === 'credit';

			$rows[] = [
				'date'      => $v->inv_date,
				'voucher'   => $v->inv_num ?? $v->v_no,
				'type'      => $isCredit ? 'Credit Note' : 'Debit Note',
				'counter'   => $v->seller_name ?? $v->v_name,
				'narration' => $v->reason_issuance ?? 'Sales Voucher Entry',

				'cgst'      => 0,
				'sgst'      => 0,
				'igst'      => 0,

				'bank'      => $v->mode_of_pay ?? '',

				'group'     => $isCredit ? 'Income' : 'Expense',
				'sub_group' => '',

				'debit'     => $isCredit ? 0 : $v->credit_debit_amount,
				'credit'    => $isCredit ? $v->credit_debit_amount : 0,

				'balance'   => 0,
				'dc'        => $isCredit ? 'Cr' : 'Dr',
				'ledgername' => 'Sales Ledger'
			];
		}

		return $rows;
	}

	
	private function purchaseLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('purchases')
			->join('purchase_values','purchases.id','=','purchase_values.sid')
			->join('vendors', 'vendors.id', '=', 'purchases.inv_name')
			->where('purchases.status', 1) //only active records
			->whereBetween('purchases.inv_date', [$from,$to])
			->select(
				'purchases.inv_date as inv_date',
				'purchases.inv_num',
				'vendors.vendor_name as counter_ledger',

				/* CGST */
				DB::raw("
					CASE
						WHEN purchase_values.gst_trans = 'intrastate'
						THEN ROUND(purchase_values.tax_amt / 2, 2)
						ELSE 0
					END AS cgst_amt
				"),

				/* SGST */
				DB::raw("
					CASE
						WHEN purchase_values.gst_trans = 'intrastate'
						THEN ROUND(purchase_values.tax_amt / 2, 2)
						ELSE 0
					END AS sgst_amt
				"),

				/* IGST */
				DB::raw("
					CASE
						WHEN purchase_values.gst_trans = 'interstate'
						THEN purchase_values.tax_amt
						ELSE 0
					END AS igst_amt
				"),

				/* amount + gst - discount */
				DB::raw("
					(
						IFNULL(purchase_values.amount,0)
						+ IFNULL(purchase_values.tax_amt,0)
						- IFNULL(purchase_values.disc_amt,0)
					) AS debit
				")
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('purchases.propId', $propId);
		} else {
			$query->where('purchases.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $d) {

			$rows[] = [
				'date' => $d->inv_date,
				'voucher' => $d->inv_num,
				'type' => 'Purchase',
				'counter' => $d->counter_ledger,
				'narration' => "",
				'cgst' => $d->cgst_amt,
				'sgst' => $d->sgst_amt,
				'igst' => $d->igst_amt,
				'bank' => '',
				'group' => 'Expense',
				'sub_group' => 'Purchase',
				'debit' => $d->debit,
				'credit' => 0,
				'balance' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Purchase Ledger'
			];
		}

		return $rows;
	}
	
	private function purchaseVoucherLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$data = DB::table('voucher_purchases')
			->join('vendors', 'vendors.id', '=', 'voucher_purchases.v_name')
			->where('voucher_purchases.added_by', $userId)			
			->whereBetween('voucher_purchases.inv_date', [$from, $to])
			->select(
				'inv_date',
				'inv_num',
				'v_no',
				'v_name',
				'vendors.vendor_name as seller_name',
				'note_type',              // Credit / Debit
				'credit_debit_amount',
				'mode_of_pay',
				'reason_issuance'
			)
			->get();

		foreach ($data as $v) {

			if ($v->credit_debit_amount <= 0) continue;

			$isCredit = strtolower($v->note_type) === 'credit';

			$rows[] = [
				'date'      => $v->inv_date,
				'voucher'   => $v->inv_num ?? $v->v_no,
				'type'      => $isCredit ? 'Credit Note' : 'Debit Note',
				'counter'   => $v->seller_name ?? $v->v_name,
				'narration' => $v->reason_issuance ?? 'Purchase Voucher Entry',

				'cgst'      => 0,
				'sgst'      => 0,
				'igst'      => 0,

				'bank'      => $v->mode_of_pay ?? '',

				'group'     => $isCredit ? 'Income' : 'Expense',
				'sub_group' => '',

				'debit'     => $isCredit ? 0 : $v->credit_debit_amount,
				'credit'    => $isCredit ? $v->credit_debit_amount : 0,

				'balance'   => 0,
				'dc'        => $isCredit ? 'Cr' : 'Dr',
				'ledgername' => 'Purchase Ledger'
			];
		}

		return $rows;
	}

	
	private function expenseLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('expenses')
				->where('expenses.status', 1) //only active records
				->whereBetween('expenses.expense_date', [$from, $to])
				->select(
					'expense_date',
					'exp_invno',
					'pur_of_expense',
					'expense_cat',
					'expense_type',
					'other_expenses_details',
					'expense_amt',
					'expense_msg',
					'mode_of_expense'
				);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('expenses.propId', $propId);
		} else {
			$query->where('expenses.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $e) {

			if ($e->expense_amt <= 0) continue;

			$rows[] = [
				'date'      => $e->expense_date,
				'voucher'   => $e->exp_invno ?? '-',
				'type'      => 'Expense',
				'counter'   => $e->expense_cat,
				'narration' => $e->expense_msg 
								?? $e->other_expenses_details 
								?? $e->pur_of_expense,
				'cgst'      => 0,
				'sgst'      => 0,
				'igst'      => 0,
				'bank'      => $e->mode_of_expense ?? '',
				'group'     => 'Expense',
				'sub_group' => $e->expense_type ?? 'General Expense',
				'debit'     => $e->expense_amt,
				'credit'    => 0,
				'balance'   => 0,
				'dc'        => 'Dr',
				'ledgername' => 'Purchase Ledger'
			];
		}
		
		// 2. Indirect expenses (auto fetch)
		$rows = array_merge($rows, $this->expensesService->bankChargesRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->expensesService->customerDiscountRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->expensesService->salaryRows($propId, $userId, $from, $to));
		$rows = array_merge($rows, $this->expensesService->taxComplianceRows($propId, $userId, $from, $to));

		return $rows;
	}

	
	private function bankLedgerRows($propId, $userId, $from, $to)
	{
		$rows = [];
		/* ---------- PURCHASES ---------- */

		$query = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->join('vendors as v', 'v.id', '=', 'p.inv_name')
			->where('p.status', 1) //only active records
			->whereBetween('p.inv_date', [$from, $to])
			->whereIn('p.mode_of_pay', [
				'IMPS','RTGS','NEFT','UPI','CARD'
			])
			->groupBy(
				'p.id',
				'p.inv_date',
				'p.inv_num',
				'v.vendor_name',
				'p.mode_of_pay'
			)
			->select(
				'p.inv_date',
				'p.inv_num',
				'v.vendor_name',
				'p.mode_of_pay',
				DB::raw("COALESCE(SUM(pv.amount),0) as total_amount")
			);

		if (!empty($propId)) {
			$query->where('p.propId', $propId);
		} else {
			$query->where('p.added_by', $userId);
		}

		$purchaseData = $query->get();

		foreach ($purchaseData as $p) {

			if ((float)$p->total_amount <= 0) continue;

			$rows[] = [
				'date' => $p->inv_date,
				'voucher' => $p->inv_num,
				'type' => 'Bank Payment',
				'counter' => $p->vendor_name,
				'narration' => 'Purchase Payment via Bank',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => $p->mode_of_pay,
				'group' => 'Asset',
				'sub_group' => 'Bank Accounts',
				'debit' => (float)$p->total_amount,
				'credit' => 0,
				'balance' => 0,
				'dc' => 'Dr',
				'ledgername' => 'Bank Ledger'
			];
		}


		/* ---------- SALES ---------- */

		$query = DB::table('sales as s')
			->join('sales_values as sv', 'sv.sid', '=', 's.id')
			->join('customers as c', 'c.id', '=', 's.inv_name')
			->where('s.status', 1) //only active records
			->whereBetween('s.inv_date', [$from, $to])
			->whereIn('s.mode_of_pay', [
				'IMPS','RTGS','NEFT','UPI','CARD'
			])
			->groupBy(
				's.id',
				's.inv_date',
				's.inv_num',
				'c.cust_name',
				's.mode_of_pay'
			)
			->select(
				's.inv_date',
				's.inv_num',
				'c.cust_name',
				's.mode_of_pay',
				DB::raw("COALESCE(SUM(sv.amount),0) as total_amount")
			);

		if (!empty($propId)) {
			$query->where('s.propId', $propId);
		} else {
			$query->where('s.added_by', $userId);
		}

		$salesData = $query->get();

		foreach ($salesData as $s) {

			if ((float)$s->total_amount <= 0) continue;

			$rows[] = [
				'date' => $s->inv_date,
				'voucher' => $s->inv_num,
				'type' => 'Bank Receipt',
				'counter' => $s->cust_name,
				'narration' => 'Sales Receipt via Bank',
				'cgst' => 0,
				'sgst' => 0,
				'igst' => 0,
				'bank' => $s->mode_of_pay,
				'group' => 'Asset',
				'sub_group' => 'Bank Accounts',
				'debit' => 0,
				'credit' => (float)$s->total_amount,
				'balance' => 0,
				'dc' => 'Cr',
				'ledgername' => 'Bank Ledger'
			];
		}

		return $rows;
	}

	private function gstOutputRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('sales')
			->join('sales_values', 'sales.id', '=', 'sales_values.sid')
			->join('customers', 'customers.id', '=', 'sales.inv_name')
			->where('sales.status', 1) //only active records
			->whereBetween('sales.inv_date', [$from, $to])
			->select(
				'sales.inv_date as inv_date',
				'sales.inv_num as inv_num',
				DB::raw("'GST Output' as voucher_type"),
				'customers.cust_name as counter_ledger',

				/* CGST */
				DB::raw("
					CASE
						WHEN sales_values.gst_trans = 'intrastate'
						THEN ROUND(IFNULL(sales_values.tax_amt,0) / 2, 2)
						ELSE 0
					END AS cgst_amt
				"),

				/* SGST */
				DB::raw("
					CASE
						WHEN sales_values.gst_trans = 'intrastate'
						THEN ROUND(IFNULL(sales_values.tax_amt,0) / 2, 2)
						ELSE 0
					END AS sgst_amt
				"),

				/* IGST */
				DB::raw("
					CASE
						WHEN sales_values.gst_trans = 'interstate'
						THEN IFNULL(sales_values.tax_amt,0)
						ELSE 0
					END AS igst_amt
				"),

				/* CREDIT = TOTAL GST COLLECTED */
				DB::raw("IFNULL(sales_values.tax_amt,0) AS credit")
			);

		/* Ownership logic */
		if (!empty($propId)) {
			$query->where('sales.propId', $propId);
		} else {
			$query->where('sales.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $d) {

			$rows[] = [
				'date'        => $d->inv_date,
				'voucher'     => $d->inv_num,
				'type'        => $d->voucher_type,
				'counter'     => $d->counter_ledger,
				'narration'   => 'GST Collected',

				'cgst'        => $d->cgst_amt,
				'sgst'        => $d->sgst_amt,
				'igst'        => $d->igst_amt,

				'bank'        => '',
				'group'       => 'Liability',
				'sub_group'   => 'GST Payable',

				'debit'       => 0,
				'credit'      => $d->credit,
				'balance'     => 0,
				'dc'          => 'Cr',
				'ledgername'  => 'GST Output Ledger'
			];
		}

		return $rows;
	}
	
	private function gstInputRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('purchases')
			->join('purchase_values', 'purchases.id', '=', 'purchase_values.sid')
			->join('vendors', 'vendors.id', '=', 'purchases.inv_name')
			->where('purchases.status', 1) //only active records
			->whereBetween('purchases.inv_date', [$from, $to])
			->select(
				'purchases.inv_date as inv_date',
				'purchases.inv_num as inv_num',
				DB::raw("'GST Input' as voucher_type"),
				'vendors.vendor_name as counter_ledger',

				/* CGST */
				DB::raw("
					CASE
						WHEN purchase_values.gst_trans = 'intrastate'
						THEN ROUND(IFNULL(purchase_values.tax_amt,0) / 2, 2)
						ELSE 0
					END AS cgst_amt
				"),

				/* SGST */
				DB::raw("
					CASE
						WHEN purchase_values.gst_trans = 'intrastate'
						THEN ROUND(IFNULL(purchase_values.tax_amt,0) / 2, 2)
						ELSE 0
					END AS sgst_amt
				"),

				/* IGST */
				DB::raw("
					CASE
						WHEN purchase_values.gst_trans = 'interstate'
						THEN IFNULL(purchase_values.tax_amt,0)
						ELSE 0
					END AS igst_amt
				"),

				/* DEBIT = TOTAL GST PAID */
				DB::raw("IFNULL(purchase_values.tax_amt,0) AS debit")
			);

		// Ownership logic
		if (!empty($propId)) {
			$query->where('purchases.propId', $propId);
		} else {
			$query->where('purchases.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $d) {

			$rows[] = [
				'date' => $d->inv_date,
				'voucher' => $d->inv_num,
				'type' => $d->voucher_type,
				'counter' => $d->counter_ledger,
				'narration' => 'GST Paid',

				'cgst' => $d->cgst_amt,
				'sgst' => $d->sgst_amt,
				'igst' => $d->igst_amt,

				'bank' => '',
				'group' => 'Asset',
				'sub_group' => 'Input GST',

				'debit' => $d->debit,
				'credit' => 0,
				'balance' => 0,
				'dc' => 'Dr',
				'ledgername' => 'GST Input Ledger'
			];
		}

		return $rows;
	}


	
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
	
	public function getPreviousFYOpeningBalance_cashflow($propId, $userId, $fromDate)
	{
		// Step 1: Previous FY dates
		[$prevFrom, $prevTo] = $this->getPreviousFYRange($fromDate);
		//echo $prevFrom;
		//echo $prevTo;exit;
		// Step 2: Collect ALL cashflow rows for previous FY
		$rows = [];
		$rows = array_merge($rows, $this->customerCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->supplierCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->expenseCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->incomeCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->gstCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->assetCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		$rows = array_merge($rows, $this->loanCashFlowRows($propId, $userId, $prevFrom, $prevTo));
		
		$totalIn  = 0;
		$totalOut = 0;

		foreach ($rows as &$row) {

			if ($row['inflow'] > 0) {
				$totalIn += $row['inflow'];
			} else {
				$totalOut += $row['outflow'];
			}
		}
		unset($row);
		$opening_balance = round($totalIn - $totalOut, 2);
		return $opening_balance;
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
		checkCoreAccess('Financial Reports');
		$currentDate = Carbon::now()->toDateString(); // YYYY-MM-DD	
		$opening = $this->getPreviousFYOpeningBalance_cashflow($propId, $userId, $currentDate);
		//echo "<pre>";print_r($opening);exit;
		if($opening == 0){
			$opening = $this->getOpeningBalanceCashFlow($userId);
		}
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		return view('User.Reports.cashflow')->with([
				'openingBalance' => $opening,
				'proprietorships' => $proprietorships
			]);
    }
	
	public function ajaxCashFlowData(Request $r)
	{
		$userId  = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$propId = $r->propId ?? null;
		$from    = $r->from_date;
		$to      = $r->to_date;
		$cashflow_type  = $r->cashflow_type;
		$voucherType  = $r->voucher_type;
		$paymentMode  = $r->payment_mode;
		$opening = (float)($r->opening_balance ?? 0);

		$rows = [];
		/* ===============================
		   OPERATING ACTIVITIES
		===============================*/
		if($cashflow_type == 'all')
		{
			$rows = array_merge($rows, $this->customerCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->supplierCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->expenseCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->incomeCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->gstCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->assetCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->loanCashFlowRows($propId, $userId, $from, $to));
		}
		if($cashflow_type == 'operating')
		{
			$rows = array_merge($rows, $this->customerCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->supplierCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->expenseCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->incomeCashFlowRows($propId, $userId, $from, $to));
			$rows = array_merge($rows, $this->gstCashFlowRows($propId, $userId, $from, $to));
		}
		if($cashflow_type == 'investing')
		{
			$rows = array_merge($rows, $this->assetCashFlowRows($propId, $userId, $from, $to));
		}
		if($cashflow_type == 'financing')
		{
			$rows = array_merge($rows, $this->loanCashFlowRows($propId, $userId, $from, $to));
		}
		
		
		/* ===============================
		   APPLY FILTERS (IMPORTANT)
		=============================== */
		$rows = array_filter($rows, function ($row) use ($voucherType, $paymentMode) {

			// Voucher Type filter
			if ($voucherType !== 'all') {
				if (strtolower($row['voucher_type']) !== strtolower($voucherType)) {
					return false;
				}
			}
			// Payment Mode / Ledger filter
			if ($paymentMode !== 'all') {

				$ledger = strtolower(trim($row['ledger'] ?? ''));
				$mode   = strtolower(trim($row['mode'] ?? ''));

				//Cash → only cash
				if ($paymentMode === 'cash') {
					return $ledger === 'cash';
				}
				//Bank → all bank modes (upi, imps, rtgs...)
				if ($paymentMode === 'bank') {
					return $ledger === 'bank';
				}
				//Specific mode (upi / imps / rtgs / neft)
				return $mode === $paymentMode;
			}
			return true;
		});
		
		/* -------- SORT BY DATE -------- */
		usort($rows, fn($a, $b) => strtotime($a['date']) <=> strtotime($b['date']));
		
		/* ===============================
		   RUNNING CASH BALANCE
		=============================== */
		$balance   = (float) $opening;
		$totalIn  = 0.0;
		$totalOut = 0.0;

		foreach ($rows as &$row) {

			$in  = (float) ($row['inflow']  ?? 0);
			$out = (float) ($row['outflow'] ?? 0);

			// --- Inflow ---
			if ($in > 0) {
				$balance += $in;
				$totalIn += $in;
				$row['dc'] = 'In';
			}

			// --- Outflow ---
			if ($out > 0) {
				$balance -= $out;
				$totalOut += $out;
				$row['dc'] = 'Out';
			}

			// --- Running balance (can be negative) ---
			$row['balance'] = round($balance, 2);
		}
		unset($row);

		return response()->json([
			'rows'      => array_values($rows),
			'opening'   => $opening,
			'closing'   => round($balance, 2),
			'total_in'  => $totalIn,
			'total_out' => $totalOut,
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

	
	//CUSTOMER RECEIPTS (OPERATING – INFLOW)
	private function customerCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->whereBetween('a.date', [$from, $to])
			->select(
				'ac.*',
				'a.date as asset_date'
			);

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$query->where('a.propId', $propId);
		} else {
			$query->where('a.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $a) {

			$amount = (float)($a->amt ?? 0);

			/* -------- INFLOW / OUTFLOW -------- */
			$inflow  = 0;
			$outflow = 0;

			if (strtolower($a->debitcredit) === 'debit') {
				$outflow = $amount;
			} else {
				$inflow = $amount;
			}

			$rows[] = [
				'date' => $a->asset_date,
				'particulars' => $a->currentAssetType ?? 'Asset',
				'voucher' => $a->invoice_no ?? '-',
				'voucher_type' => $a->voucher_type ?? 'Receipt',
				'cashflow_type' => 'Operating',
				'mode' => '',
				'ledger' => '',
				'inflow'  => round($inflow, 2),
				'outflow' => round($outflow, 2),
				'balance' => 0,
			];
		}

		return $rows;
	}
	
	//SUPPLIER PAYMENTS (OPERATING – OUTFLOW)
	private function supplierCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('current_liabilities as cl')
			->join('liabilities as l', 'l.id', '=', 'cl.liabilities_id')
			->where('cl.CurrentLiabilitiesType', 'Trade Payables')
			->whereBetween('l.added_date', [$from, $to])
			->select('cl.*', 'l.added_date as liability_date');

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$query->where('l.propId', $propId);
		} else {
			$query->where('l.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $d) {

			$amount = (float)($d->amount ?? 0);
			$inflow  = 0;
			$outflow = 0;

			if (strtolower($d->debit_credit ?? 'credit') === 'debit') {
				$inflow = $amount;  
			} else {
				$outflow = $amount;
			}

			$rows[] = [
				'date' => $d->liability_date,
				'particulars' => 'Supplier Payment',
				'voucher' => $d->invoice_no ?? '-',
				'voucher_type' => $d->voucher_type ?? 'Payment',
				'cashflow_type' => 'Operating',
				'mode' => '',
				'ledger' => '',
				'inflow'  => round($inflow, 2),
				'outflow' => round($outflow, 2),
				'balance' => 0,
			];
		}

		return $rows;
	}
	
	//EXPENSE CASH FLOW (OPERATING – OUTFLOW)
	private function expenseCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];

		$query = DB::table('expenses')
			->whereNotNull('mode_of_expense')
			->whereBetween('expense_date', [$from, $to]);

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$query->where('expenses.propId', $propId);
		} else {
			$query->where('expenses.added_by', $userId);
		}

		$data = $query->get();

		foreach ($data as $e) {

			$ledgerType = $this->resolveLedgerType($e->mode_of_expense);

			$rows[] = [
				'date' => $e->expense_date,
				'particulars' => $e->expense_type,
				'voucher' => $e->exp_invno,
				'voucher_type' => 'Payment',
				'cashflow_type' => 'Operating',
				'mode' => $e->mode_of_expense,
				'ledger' => $ledgerType,
				'inflow' => 0,
				'outflow' => $e->expense_amt,
				'balance' => 0,
			];
		}

		return $rows;
	}
	
	private function incomeCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];
		
		$query = DB::table('income')
			->whereBetween('dateInput', [$from, $to]);

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$query->where('income.propId', $propId);
		} else {
			$query->where('income.addBy', $userId);
		}

		$data = $query->get();

		foreach ($data as $i) {

			$grossAmount = (float)($i->amount ?? 0);
			if ($grossAmount <= 0) continue;

			/* ------------------------------
			   TDS LOGIC
			------------------------------*/
			$tdsAmount = 0;

			if (
				strtolower($i->tds_applicable) === 'yes' &&
				!empty($i->tds_amount)
			) {
				$tdsAmount = (float)$i->tds_amount;
			}

			$netReceived = $grossAmount - $tdsAmount;
			if ($netReceived <= 0) continue;

			$rows[] = [
				'date' => $i->dateInput,
				'particulars' => $i->categoryIncome . ($i->name ? ' - '.$i->name : ''),
				'voucher' => '-',
				'voucher_type' => 'Receipt',
				'cashflow_type' => 'Operating',
				'mode' => 'Cash / Bank',
				'ledger' => 'Cash / Bank',

				/* -------- CASH FLOW -------- */
				'inflow'  => round($netReceived, 2),
				'outflow' => 0,
				'tds' => round($tdsAmount, 2),
				'balance' => 0,
			];
		}

		return $rows;
	}
	
	private function gstCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];

		/* =====================================================
		   SALES CASH FLOW (GST INCLUDED IN AMOUNT)
		===================================================== */

		$salesQuery = DB::table('sales')
			->join('sales_values', 'sales.id', '=', 'sales_values.sid')
			->join('customers', 'customers.id', '=', 'sales.inv_name')
			->whereBetween('sales.inv_date', [$from, $to])
			->whereNotNull('sales.mode_of_pay');

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$salesQuery->where('sales.propId', $propId);
		} else {
			$salesQuery->where('sales.added_by', $userId);
		}

		$sales = $salesQuery
			->select(
				'sales.inv_date',
				'sales.inv_num',
				'sales.pay_status',
				'sales.advance_amount',
				'sales.mode_of_pay',
				'customers.cust_name as counter_ledger',
				DB::raw("
					(IFNULL(sales_values.amount,0)
					+ IFNULL(sales_values.tax_amt,0)
					- IFNULL(sales_values.disc_amt,0)) AS total_amount
				")
			)
			->get();

		foreach ($sales as $s) {

			/* -------- DETERMINE CASH RECEIVED -------- */
			$cashReceived = 0;

			if (in_array($s->pay_status, ['Full', 'Partial'])) {
				$cashReceived = (float)$s->total_amount;
			} elseif ($s->pay_status === 'Due') {
				$cashReceived = (float)($s->advance_amount ?? 0);
			}

			if ($cashReceived <= 0) continue;

			$ledgerType = $this->resolveLedgerType($s->mode_of_pay);

			$rows[] = [
				'date' => $s->inv_date,
				'particulars' => 'Sales Receipt (GST Included)',
				'voucher' => $s->inv_num,
				'voucher_type' => 'Receipt',
				'cashflow_type' => 'Operating',
				'mode' => $s->mode_of_pay,
				'ledger' => $ledgerType,
				'inflow'  => round($cashReceived, 2),
				'outflow' => 0,
				'balance' => 0,
			];
		}

		/* =====================================================
		   PURCHASE CASH FLOW (GST INCLUDED IN AMOUNT)
		===================================================== */

		$purchaseQuery = DB::table('purchases')
			->join('purchase_values', 'purchases.id', '=', 'purchase_values.sid')
			->join('vendors', 'vendors.id', '=', 'purchases.inv_name')
			->whereBetween('purchases.inv_date', [$from, $to])
			->whereNotNull('purchases.mode_of_pay');

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$purchaseQuery->where('purchases.propId', $propId);
		} else {
			$purchaseQuery->where('purchases.added_by', $userId);
		}

		$purchases = $purchaseQuery
			->select(
				'purchases.inv_date',
				'purchases.inv_num',
				'purchases.pay_status',
				'purchases.advance_amount',
				'purchases.mode_of_pay',
				'vendors.vendor_name as counter_ledger',
				DB::raw("
					(IFNULL(purchase_values.amount,0)
					+ IFNULL(purchase_values.tax_amt,0)
					- IFNULL(purchase_values.disc_amt,0)) AS total_amount
				")
			)
			->get();

		foreach ($purchases as $p) {

			/* -------- DETERMINE CASH PAID -------- */
			$cashPaid = 0;

			if (in_array($p->pay_status, ['Full', 'Partial'])) {
				$cashPaid = (float)$p->total_amount;
			} elseif ($p->pay_status === 'Due') {
				$cashPaid = (float)($p->advance_amount ?? 0);
			}

			if ($cashPaid <= 0) continue;

			$ledgerType = $this->resolveLedgerType($p->mode_of_pay);

			$rows[] = [
				'date' => $p->inv_date,
				'particulars' => 'Purchase Payment (GST Included)',
				'voucher' => $p->inv_num,
				'voucher_type' => 'Payment',
				'cashflow_type' => 'Operating',
				'mode' => $p->mode_of_pay,
				'ledger' => $ledgerType,
				'inflow'  => 0,
				'outflow' => round($cashPaid, 2),
				'balance' => 0,
			];
		}

		return $rows;
	}

	//ASSET PURCHASE (INVESTING – OUTFLOW)
	private function assetCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];

		/* =====================================================
		   WORK IN PROGRESS (asset_currents)
		===================================================== */
			
		$query1 = DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('ac.currentAssetType', 'Work-in-Progress')
			->whereBetween('a.date', [$from, $to]);

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$query1->where('a.propId', $propId);
		} else {
			$query1->where('a.added_by', $userId);
		}

		$data = $query1->get();

		foreach ($data as $a) {

			$rows[] = [
				'date' => $a->date,
				'particulars' => $a->currentAssetType,
				'voucher' => $a->invoice_no ?? '-',
				'voucher_type' => $a->voucher_type ?? '',
				'cashflow_type' => 'Investing',
				'mode' => '',
				'ledger' => '',
				'inflow' => 0,
				'outflow' => $a->amt ?? 0,
				'balance' => 0,
			];
		}

		/* =====================================================
		   ADVANCES TO VENDORS
		===================================================== */

		$query2 = DB::table('assets_cs as ac')
			->join('assets as a', 'a.id', '=', 'ac.aid')
			->where('ac.currentAssetType', 'Advances to Vendors')
			->whereBetween('a.date', [$from, $to]);

		if (!empty($propId)) {
			$query2->where('a.propId', $propId);
		} else {
			$query2->where('a.added_by', $userId);
		}

		$data2 = $query2->get();

		foreach ($data2 as $d) {

			$rows[] = [
				'date' => $d->date,
				'particulars' => $d->currentAssetType,
				'voucher' => $d->invoice_no ?? '-',
				'voucher_type' => $d->voucher_type ?? '',
				'cashflow_type' => 'Investing',
				'mode' => '',
				'ledger' => '',
				'inflow' => 0,
				'outflow' => $d->amt ?? 0,
				'balance' => 0,
			];
		}

		/* =====================================================
		   NON CURRENT ASSETS
		===================================================== */

		$query3 = DB::table('assets_ncs as anc')
			->join('assets as a', 'a.id', '=', 'anc.asid')
			->whereBetween('a.date', [$from, $to]);

		if (!empty($propId)) {
			$query3->where('a.propId', $propId);
		} else {
			$query3->where('a.added_by', $userId);
		}

		$data3 = $query3->get();

		foreach ($data3 as $a) {

			$rows[] = [
				'date' => $a->date,
				'particulars' => $a->nonCurrentAssetType,
				'voucher' => $a->invoice_no_nca ?? '-',
				'voucher_type' => $a->voucher_type_nca ?? '',
				'cashflow_type' => 'Investing',
				'mode' => '',
				'ledger' => '',
				'inflow'  => 0,
				'outflow' => $a->amt_nca ?? 0,
				'balance' => 0,
			];
		}

		return $rows;
	}

	
	//FINANCING ACTIVITIES -- cash flow
	private function loanCashFlowRows($propId, $userId, $from, $to)
	{
		$rows = [];

		/* =====================================================
		   SHORT TERM LOANS (asset_currents)
		===================================================== */
			
		$query1 = DB::table('assets_cs as ac')
					->join('assets as a', 'a.id', '=', 'ac.aid')
					->where('ac.currentAssetType', 'Short-term Loans & Advances')
					->whereBetween('a.date', [$from, $to]);

		/* ---------- OWNERSHIP FILTER ---------- */
		if (!empty($propId)) {
			$query1->where('a.propId', $propId);
		} else {
			$query1->where('a.added_by', $userId);
		}

		$data = $query1->get();

		foreach ($data as $a) {

			$rows[] = [
				'date' => $a->date,
				'particulars' => '',
				'voucher' => $a->invoice_no ?? '-',
				'voucher_type' => $a->voucher_type ?? '',
				'cashflow_type' => 'Financing',
				'mode' => '',
				'ledger' => '',
				'inflow'  => 0,
				'outflow' => $a->amt ?? 0,
				'balance' => 0,
			];
		}

		/* =====================================================
		   LONG TERM LOANS (asset_non_currents)
		===================================================== */
			
		$query2 = DB::table('assets_ncs as anc')
					->join('assets as a', 'a.id', '=', 'anc.asid')
					->where('anc.nonCurrentAssetType', 'Long-term Loans and Advances')
					->whereBetween('a.date', [$from, $to]);


		if (!empty($propId)) {
			$query2->where('a.propId', $propId);
		} else {
			$query2->where('a.added_by', $userId);
		}

		$data2 = $query2->get();

		foreach ($data2 as $a) {

			$rows[] = [
				'date' => $a->date,
				'particulars' => '',
				'voucher' => $a->invoice_no_nca ?? '-',
				'voucher_type' => $a->voucher_type_nca ?? '',
				'cashflow_type' => 'Financing',
				'mode' => '',
				'ledger' => '',
				'inflow'  => 0,
				'outflow' => $a->amt_nca ?? 0,
				'balance' => 0,
			];
		}

		return $rows;
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
