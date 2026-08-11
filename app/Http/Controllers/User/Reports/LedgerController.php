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

class LedgerController extends Controller
{

	private $balanceSheetService;
	private $profitLossService;

    public function __construct(BalanceSheetService $balanceSheetService,ProfitLossService $profitLossService)
    {
        $this->balanceSheetService = $balanceSheetService;
		$this->profitLossService = $profitLossService;
		$this->middleware('auth');
    }
	
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

		$parties = DB::table('journals')
			->where('added_by', $userId)
			->whereNotNull('party_name')
			->where('party_name', '!=', '')
			->distinct()
			->pluck('party_name');
			
		return view('User.Reports.ledger-report')->with([
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
		$rows = $this->moduleLedgerRows(
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
		$userId = currentOwnerId();

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId');
		}

		$propId = !empty($r->propId) ? $r->propId : null;
		$from = $r->from_date;
		$to = $r->to_date;

		$custId = !empty($r->custId) ? $r->custId : null;
		$vendId = !empty($r->vendId) ? $r->vendId : null;
		$partyName = !empty($r->party_name) ? $r->party_name : null;
		$ledger = !empty($r->ledger_name) ? $r->ledger_name : null;
		$ledgerGroup = !empty($r->ledger_group) ? $r->ledger_group : null;

		//Opening Balance
		$openingData = $this->getLedgerOpeningBalance($propId,$userId,$from,$ledger,$ledgerGroup,$partyName,$custId,$vendId);

		$opening = (float) ($openingData['opening_balance'] ?? 0);
		$openingDC = $openingData['dc'] ?? 'Cr';

		/*
		|--------------------------------------------------------------------------
		| Internal Balance
		|--------------------------------------------------------------------------
		| Credit = Positive
		| Debit  = Negative
		|--------------------------------------------------------------------------
		*/
		$balance = $openingDC === 'Dr' ? -abs($opening) : abs($opening);

		/*
		|--------------------------------------------------------------------------
		| Get Module Based Ledger Rows
		|--------------------------------------------------------------------------
		*/
		$rows = $this->moduleLedgerRows($propId,$userId,$from,$to,$ledger,$ledgerGroup,$partyName,$custId,$vendId);

		usort($rows, function ($a, $b) {
			$dateA = strtotime($a['date'] ?? '');
			$dateB = strtotime($b['date'] ?? '');
			return $dateA <=> $dateB;
		});

		/*
		|--------------------------------------------------------------------------
		| Running Balance
		|--------------------------------------------------------------------------
		*/
		$totalDr = 0;
		$totalCr = 0;

		foreach ($rows as &$row) {
			$debit = (float) ($row['debit'] ?? 0);
			$credit = (float) ($row['credit'] ?? 0);
			$totalDr += $debit;
			$totalCr += $credit;
			//Balance = Credit - Debit
			$balance += ($credit - $debit);
			$row['balance'] = round($balance, 2);
			$row['balance_dc'] = $balance < 0 ? 'Dr' : 'Cr';
			$row['balance_amount'] = round(abs($balance), 2);
		}

		unset($row);

		//Closing Balance
		$closing = round(abs($balance), 2);
		$closingDC = $balance < 0? 'Dr': 'Cr';

		return response()->json([
			'rows' => $rows,
			'opening_balance' => round(abs($opening), 2),
			'closing' => round($closing, 2),
			'closing_dc' => $closingDC,
			'total_debit' => round($totalDr, 2),
			'total_credit' => round($totalCr, 2),
		]);
	}
	
	private function moduleLedgerRows($propId,$userId,$from,$to,$ledger = null,$ledgerGroup = null,$partyName = null,$custId = null,$vendId = null) 
	{
		$rows = [];

		if ((empty($ledgerGroup) || $ledgerGroup === 'Income') && empty($vendId)) {
			$salesRows = $this->getSalesLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$custId);
			$rows = array_merge($rows, $salesRows);
			
			$salesCreditRows = $this->getSalesCreditNoteLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$custId);
			$rows = array_merge($rows, $salesCreditRows);
			
			$incomeRows = $this->getIncomeLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$custId);
			$rows = array_merge($rows, $incomeRows);
		}
		
		if ((empty($ledgerGroup) || $ledgerGroup === 'Expense') && empty($custId)) {
			$purchaseRows = $this->getPurchaseLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$vendId);
			$rows = array_merge($rows, $purchaseRows);
			
			$purchaseDebitRows = $this->getPurchaseDebitNoteLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$vendId);
			$rows = array_merge($rows, $purchaseDebitRows);

			$expenseRows = $this->getExpenseLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$vendId);
			$rows = array_merge($rows, $expenseRows);
		}
		
		if ((empty($ledgerGroup) || $ledgerGroup === 'Asset') && empty($custId)) {
			$assetRows = $this->getAssetLedgerRows($propId,$userId,$from,$to,$ledger,$partyName,$vendId);
			$rows = array_merge($rows, $assetRows);
		}
		if (empty($ledgerGroup) || $ledgerGroup === 'Liability') {
			//$liabilitiesRows = $this->getLiabilityLedgerRows($propId,$userId,$from,$to,$ledger,$partyName);
			//$rows = array_merge($rows, $liabilitiesRows);
		}
		
		return $rows;
	}


	private function getSalesLedgerRows($propId,$userId,$from,$to,$ledger = null,$partyName = null,$custId = null) 
	{
		$rows = [];
		$source = 'Sales';

		$query = DB::table('sales as s')
			->whereBetween('s.inv_date', [$from, $to]);

		//Company / User Filter
		if (!empty($propId)) {
			$query->where('s.propId', $propId);
		} else {
			$query->where('s.added_by', $userId);
		}

		//Customer Filter
		if (!empty($custId)) {
			$query->where('s.inv_name', $custId);
		}

		$sales = $query
			->orderBy('s.inv_date', 'asc')
			->orderBy('s.id', 'asc')
			->get();

		foreach ($sales as $sale) {
			$customerName = '';
			if (!empty($sale->inv_name)) {
				$customerName = DB::table('customers')
					->where('id', $sale->inv_name)
					->value('cust_name');
			}

			$values = DB::table('sales_values')
				->where('sid', $sale->id)
				->get();

			$salesAmount = 0;
			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			foreach ($values as $value) {
				$amount = (float) ($value->amount ?? 0);
				$salesAmount += $amount;
				$gstRate = (float) ($value->gst_rate ?? 0);
				if ($gstRate > 0) {
					if (
						($value->gst_trans ?? '') === 'intrastate' ||
						($value->gst_trans ?? '') === 'union'
					) {
						$cgst += ($amount * ($gstRate / 2)) / 100;
						$sgst += ($amount * ($gstRate / 2)) / 100;
					}

					if (($value->gst_trans ?? '') === 'interstate') {
						$igst += ($amount * $gstRate) / 100;
					}
				}
			}

			$total = $salesAmount + $cgst + $sgst + $igst;
			$isCustomer = false;
			if (
				!empty($ledger) &&
				strtolower(trim($ledger)) === strtolower(trim($customerName))
			) {
				$isCustomer = true;
			}

			if (
				!empty($partyName) &&
				strtolower(trim($partyName)) === strtolower(trim($customerName))
			) {
				$isCustomer = true;
			}

			$debit = 0;
			$credit = 0;
			if (empty($ledger) && empty($partyName)) {
				$debit = $total;
			}else if ($isCustomer) {
				$debit = $total;
			} else {
				if ($ledger === 'Sales') {
					$credit = $salesAmount;
				}

				if ($ledger === 'Output CGST') {
					$credit = $cgst;
				}

				if ($ledger === 'Output SGST') {
					$credit = $sgst;
				}

				if ($ledger === 'Output IGST') {
					$credit = $igst;
				}
			}

			if ($debit == 0 && $credit == 0) {
				continue;
			}

			$details = [];
			if ($total > 0) {
				$details[] = [
					'ledger' => $customerName ?: 'Customer',
					'debit' => round($total, 2),
					'credit' => 0,
				];
			}

			if ($salesAmount > 0) {
				$details[] = [
					'ledger' => 'Sales',
					'debit' => 0,
					'credit' => round($salesAmount, 2),
				];
			}

			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Output CGST',
					'debit' => 0,
					'credit' => round($cgst, 2),
				];
			}

			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Output SGST',
					'debit' => 0,
					'credit' => round($sgst, 2),
				];
			}

			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Output IGST',
					'debit' => 0,
					'credit' => round($igst, 2),
				];
			}

			$rows[] = [
				'date' => $sale->inv_date,
				'voucher' => $sale->inv_num ?? '-',
				'type' => '',
				'source' => $source,
				'transaction_details' => 'Sales',
				'ledgername' => $ledger ?? '',
				'counter' => $customerName ?? '',
				'debit_ledger' =>$debit > 0 ? ($ledger ?? $customerName) : '',
				'credit_ledger' =>$credit > 0 ? ($ledger ?? 'Sales') : '',
				'narration' => 'Sales',
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => 0,
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' =>$sale->pay_status ?? 'Due',
				'status' =>$sale->status ?? '',
				'details' => $details,
			];

			$payStatus = $sale->pay_status ?? 'Due';
			$paymentRows = $this->getPaymentVoucherLedgerRows($propId,$userId,$from,$to,$ledger,$source,$sale->id,$payStatus);
			if (!empty($paymentRows)) {
				$rows = array_merge($rows, $paymentRows);
			}
		}

		return $rows;
	}
	
	
	private function getSalesCreditNoteLedgerRows($propId, $userId, $from, $to, $ledger = null, $partyName = null, $custId = null)
	{
		$rows = [];
		$source = 'Sales Credit Note';

		$query = DB::table('vouchers as v')
			->join('sales as s','s.inv_num','=','v.invoice_number')
			->leftJoin('customers as c','c.id','=','s.inv_name')
			->whereBetween('v.inv_date', [$from, $to])
			->select(
				'v.*',
				's.inv_name as sales_customer_id',
				's.pay_status as payment_status',
				'c.cust_name'
			);

		/*
		|--------------------------------------------------------------------------
		| Company / User Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($propId)) {
			$query->where('v.propId', $propId);
		} else {
			$query->where('v.added_by', $userId);
		}

		/*
		|--------------------------------------------------------------------------
		| Customer Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($custId)) {
			$query->where('s.inv_name', $custId);
		}

		$creditNotes = $query
			->orderBy('v.inv_date', 'asc')
			->orderBy('v.id', 'asc')
			->get();

		foreach ($creditNotes as $creditNote) {

			$customerName =$creditNote->cust_name ?? '';
			$salesAmount = (float) ($creditNote->taxable_value ?? 0);
			$cgst = (float) ($creditNote->cgst_amount ?? 0);
			$sgst = (float) ($creditNote->sgst_amount ?? 0);
			$igst = (float) ($creditNote->igst_amount ?? 0);

			$total =$salesAmount + $cgst + $sgst + $igst;

			/*
			|--------------------------------------------------------------------------
			| Check Selected Ledger
			|--------------------------------------------------------------------------
			*/
			$isCustomer = false;

			if (
				!empty($ledger) &&
				strtolower(trim($ledger)) ===
				strtolower(trim($customerName))
			) {
				$isCustomer = true;
			}

			if (
				!empty($partyName) &&
				strtolower(trim($partyName)) ===
				strtolower(trim($customerName))
			) {
				$isCustomer = true;
			}

			$debit = 0;
			$credit = 0;
			if (empty($ledger) && empty($partyName)) {
				$credit = $total;
			}else if ($isCustomer) {
				$credit = $total;
			} else {

				if ($ledger === 'Sales') {
					$debit = $salesAmount;
				}

				if ($ledger === 'Output CGST') {
					$debit = $cgst;
				}

				if ($ledger === 'Output SGST') {
					$debit = $sgst;
				}

				if ($ledger === 'Output IGST') {
					$debit = $igst;
				}
			}

			if ($debit == 0 && $credit == 0) {
				continue;
			}

			/*
			|--------------------------------------------------------------------------
			| Expanded Details
			|--------------------------------------------------------------------------
			*/
			$details = [];

			if ($salesAmount > 0) {
				$details[] = [
					'ledger' => 'Sales',
					'debit' => round($salesAmount, 2),
					'credit' => 0,
				];
			}

			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Output CGST',
					'debit' => round($cgst, 2),
					'credit' => 0,
				];
			}

			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Output SGST',
					'debit' => round($sgst, 2),
					'credit' => 0,
				];
			}

			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Output IGST',
					'debit' => round($igst, 2),
					'credit' => 0,
				];
			}

			if ($total > 0) {
				$details[] = [
					'ledger' => $customerName ?: 'Customer',
					'debit' => 0,
					'credit' => round($total, 2),
				];
			}

			$rows[] = [
				'date' => $creditNote->inv_date,
				'voucher' =>$creditNote->invoice_number ?? $creditNote->inv_num?? '-',
				'type' => 'Credit Note',
				'source' => $source,
				'transaction_details' =>$creditNote->reason_issuance ?? 'Sales Credit Note',
				'ledgername' => $ledger ?? '',
				'counter' => $customerName,
				'debit_ledger' =>$debit > 0 ? ($ledger ?? 'Sales') : '',
				'credit_ledger' =>$credit > 0 ? ($ledger ?? $customerName) : '',
				'narration' =>$creditNote->reason_issuance ?? 'Sales Credit Note',
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => 0,
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' =>$creditNote->payment_status ?? 'Due',
				'status' =>$creditNote->status ?? '',
				'details' => $details,
			];
		}

		return $rows;
	}
	
	
	private function getIncomeLedgerRows($propId, $userId, $from, $to, $ledger = null, $partyName = null, $custId = null)
	{
		$rows = [];
		$source = 'Income';

		$query = DB::table('income as i')
			->whereBetween('i.dateInput', [$from, $to]);

		/*
		|--------------------------------------------------------------------------
		| Company / User Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($propId)) {
			$query->where('i.propId', $propId);
		} else {
			$query->where('i.addBy', $userId);
		}

		/*
		|--------------------------------------------------------------------------
		| Customer Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($custId)) {
			$query->where('i.customer_name', $custId);
		}

		$incomes = $query
			->orderBy('i.dateInput', 'asc')
			->orderBy('i.id', 'asc')
			->get();

		foreach ($incomes as $income) {

			//Customer Name
			$customerName = '';
			if (!empty($income->customer_name)) {
				$customerName = DB::table('customers')
					->where('cust_name', $income->customer_name)
					->value('cust_name');
			}

			
			if (empty($customerName)) {
				$customerName = $income->customer_name ?? '';
			}
			$incomeLedger = $income->categoryIncome ?? $income->other_income ?? $income->incomeType ?? 'Income';
			$incomeAmount = (float) ($income->amount ?? 0);
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$gstRate = (float) ($income->gst_rate ?? 0);
			if (
				!empty($income->gst_applicable) &&
				strtolower(trim($income->gst_applicable)) === 'yes' &&
				$gstRate > 0
			) {

				if (
					($income->gst_trans ?? '') === 'intrastate' ||
					($income->gst_trans ?? '') === 'union'
				) {
					$cgst = ($incomeAmount * ($gstRate / 2)) / 100;
					$sgst = ($incomeAmount * ($gstRate / 2)) / 100;
				}

				if (($income->gst_trans ?? '') === 'interstate') {
					$igst = ($incomeAmount * $gstRate) / 100;
				}
			}

			$total = $incomeAmount + $cgst + $sgst + $igst;

			//Check Selected Customer
			$isCustomer = false;

			if (
				!empty($ledger) &&
				!empty($customerName) &&
				strtolower(trim($ledger)) === strtolower(trim($customerName))
			) {
				$isCustomer = true;
			}

			if (
				!empty($partyName) &&
				!empty($customerName) &&
				strtolower(trim($partyName)) === strtolower(trim($customerName))
			) {
				$isCustomer = true;
			}

			$debit = 0;
			$credit = 0;

			//No Ledger or Party Selected
			if (empty($ledger) && empty($partyName)) {
				$credit = $total;
			} elseif ($isCustomer) {
				$debit = $total;
			} else {
				if (
					!empty($ledger) &&
					strtolower(trim($ledger)) === strtolower(trim($incomeLedger))
				) {
					$credit = $incomeAmount;
				}

				//Output CGST
				if ($ledger === 'Output CGST' || $ledger === 'CGST Output') {
					$credit = $cgst;
				}

				//Output SGST
				if ($ledger === 'Output SGST' || $ledger === 'SGST Output') {
					$credit = $sgst;
				}

				//Output IGST
				if ($ledger === 'Output IGST' || $ledger === 'IGST Output') {
					$credit = $igst;
				}
			}


			if ($debit == 0 && $credit == 0) {
				continue;
			}

			$details = [];
			if ($total > 0 && !empty($customerName)) {
				$details[] = [
					'ledger' => $customerName,
					'debit' => round($total, 2),
					'credit' => 0,
				];
			}

			/*
			| Income
			*/
			if ($incomeAmount > 0) {
				$details[] = [
					'ledger' => $incomeLedger,
					'debit' => 0,
					'credit' => round($incomeAmount, 2),
				];
			}

			/*
			| Output CGST
			*/
			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Output CGST',
					'debit' => 0,
					'credit' => round($cgst, 2),
				];
			}

			/*
			| Output SGST
			*/
			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Output SGST',
					'debit' => 0,
					'credit' => round($sgst, 2),
				];
			}

			/*
			| Output IGST
			*/
			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Output IGST',
					'debit' => 0,
					'credit' => round($igst, 2),
				];
			}

			/*
			|--------------------------------------------------------------------------
			| Main Row
			|--------------------------------------------------------------------------
			*/
			$rows[] = [
				'date' => $income->dateInput,
				'voucher' => $income->invoice_no ?? '-',
				'type' => 'Income',
				'source' => $source,
				'transaction_details' => $incomeLedger,
				'ledgername' => $ledger ?? '',
				'counter' => $customerName,
				'debit_ledger' => $debit > 0 ? ($ledger ?? $customerName) : '',
				'credit_ledger' => $credit > 0 ? ($ledger ?? $incomeLedger) : '',
				'narration' => $income->specification ?? $incomeLedger,
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => 0,
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' => $income->pay_status ?? 'Due',
				'status' => $income->status ?? '',
				'details' => $details,
			];

			$payStatus = $income->pay_status ?? 'Due';
			$paymentRows = $this->getPaymentVoucherLedgerRows($propId,$userId,$from,$to,$ledger,$source,$income->id,$payStatus);
			if (!empty($paymentRows)) {
				$rows = array_merge($rows, $paymentRows);
			}
		}

		return $rows;
	}
	
	
	private function getPurchaseLedgerRows($propId,$userId,$from,$to,$ledger = null,$partyName = null,$vendId = null)
	{
		$rows = [];
		$source = 'Purchase';
		$query = DB::table('purchases as p')
			->whereBetween('p.inv_date', [$from, $to]);

		if (!empty($propId)) {
			$query->where('p.propId', $propId);
		} else {
			$query->where('p.added_by', $userId);
		}

		if (!empty($vendId)) {
			$query->where('p.inv_name', $vendId);
		}

		$purchases = $query->get();

		foreach ($purchases as $purchase) {

			/*
			|--------------------------------------------------------------------------
			| Vendor Name
			|--------------------------------------------------------------------------
			*/
			$vendorName = DB::table('vendors')
				->where('id', $purchase->inv_name)
				->value('vendor_name');

			/*
			|--------------------------------------------------------------------------
			| Purchase Values
			|--------------------------------------------------------------------------
			*/
			$values = DB::table('purchase_values')
				->where('sid', $purchase->id)
				->get();

			$purchaseAmount = 0;
			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			foreach ($values as $value) {

				$amount = (float) ($value->amount ?? 0);

				$purchaseAmount += $amount;

				/*
				|--------------------------------------------------------------------------
				| GST Calculation
				|--------------------------------------------------------------------------
				*/
				$gstRate = (float) ($value->gst_rate ?? 0);

				if ($gstRate > 0) {

					if (
						($value->gst_trans ?? '') === 'intrastate' ||
						($value->gst_trans ?? '') === 'union'
					) {
						$cgst += ($amount * ($gstRate / 2)) / 100;
						$sgst += ($amount * ($gstRate / 2)) / 100;
					}

					if (($value->gst_trans ?? '') === 'interstate') {
						$igst += ($amount * $gstRate) / 100;
					}
				}
			}

			/*
			|--------------------------------------------------------------------------
			| Shipping Cost / Freight Expense
			|--------------------------------------------------------------------------
			| Match expenses.exp_invno with purchases.inv_num
			|--------------------------------------------------------------------------
			*/
			$shippingCost = 0;
			if (!empty($purchase->inv_num)) {
				$shippingCost = (float) DB::table('expenses')
					->where('exp_invno', $purchase->inv_num)
					->where('added_by', $userId)
					->where('expense_type', 'Freight / Carriage Inward')
					->sum('expense_amt');
			}
			/*
			|--------------------------------------------------------------------------
			| Total Purchase Payable
			|--------------------------------------------------------------------------
			*/
			$total = $purchaseAmount
				+ $cgst
				+ $sgst
				+ $igst
				+ $shippingCost;

			/*
			|--------------------------------------------------------------------------
			| Check Selected Ledger
			|--------------------------------------------------------------------------
			*/
			$isVendor = false;

			/*if (!empty($ledger) && $ledger === $vendorName) {
				$isVendor = true;
			}

			if (!empty($partyName) && $partyName === $vendorName) {
				$isVendor = true;
			}*/
			if (!empty($ledger) && strcasecmp(trim($ledger), trim($vendorName)) === 0) {
				$isVendor = true;
			}

			if (!empty($partyName) && strcasecmp(trim($partyName), trim($vendorName)) === 0) {
				$isVendor = true;
			}

			/*
			|--------------------------------------------------------------------------
			| Vendor ID Selected
			|--------------------------------------------------------------------------
			*/
			if (!empty($vendId) && (int) $vendId === (int) $purchase->inv_name) {
				$isVendor = true;
			}

			/*
			|--------------------------------------------------------------------------
			| Main Debit / Credit
			|--------------------------------------------------------------------------
			*/
			$debit = 0;
			$credit = 0;
			if (empty($ledger) && empty($partyName) && empty($vendId)) {
				$debit = $total;
			}
			elseif ($isVendor) {
				$credit = $total;

			} else {

				/*
				|--------------------------------------------------------------------------
				| Purchase Ledger
				|--------------------------------------------------------------------------
				*/
				if ($ledger === 'Purchase') {
					$debit = $purchaseAmount;
				}

				/*
				|--------------------------------------------------------------------------
				| Input CGST
				|--------------------------------------------------------------------------
				*/
				if ($ledger === 'Input CGST') {
					$debit = $cgst;
				}

				/*
				|--------------------------------------------------------------------------
				| Input SGST
				|--------------------------------------------------------------------------
				*/
				if ($ledger === 'Input SGST') {
					$debit = $sgst;
				}

				/*
				|--------------------------------------------------------------------------
				| Input IGST
				|--------------------------------------------------------------------------
				*/
				if ($ledger === 'Input IGST') {
					$debit = $igst;
				}

				/*
				|--------------------------------------------------------------------------
				| Freight / Carriage Inward
				|--------------------------------------------------------------------------
				*/
				if (
					$ledger === 'Freight / Carriage Inward' ||
					$ledger === 'Freight \/ Carriage Inward'
				) {
					$debit = $shippingCost;
				}
			}

			/*
			|--------------------------------------------------------------------------
			| Skip if selected ledger has no amount
			|--------------------------------------------------------------------------
			*/
			if ($debit == 0 && $credit == 0) {
				continue;
			}

			/*
			|--------------------------------------------------------------------------
			| Expanded Details
			|--------------------------------------------------------------------------
			*/
			$details = [];

			/*
			| Purchase
			*/
			if ($purchaseAmount > 0) {
				$details[] = [
					'ledger' => 'Purchase',
					'debit' => round($purchaseAmount, 2),
					'credit' => 0,
				];
			}

			/*
			| Input CGST
			*/
			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Input CGST',
					'debit' => round($cgst, 2),
					'credit' => 0,
				];
			}

			/*
			| Input SGST
			*/
			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Input SGST',
					'debit' => round($sgst, 2),
					'credit' => 0,
				];
			}

			/*
			| Input IGST
			*/
			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Input IGST',
					'debit' => round($igst, 2),
					'credit' => 0,
				];
			}

			/*
			| Shipping Cost
			*/
			if ($shippingCost > 0) {
				$details[] = [
					'ledger' => 'Freight / Carriage Inward',
					'debit' => round($shippingCost, 2),
					'credit' => 0,
				];
			}

			/*
			| Vendor
			*/
			if ($total > 0) {
				$details[] = [
					'ledger' => $vendorName ?? 'Vendor',
					'debit' => 0,
					'credit' => round($total, 2),
				];
			}

			/*
			|--------------------------------------------------------------------------
			| Main Row
			|--------------------------------------------------------------------------
			*/
			$rows[] = [
				'date' => $purchase->inv_date,
				'voucher' => $purchase->inv_num ?? '-',
				'type' => '',
				'source' => $source,
				'transaction_details' => 'Purchase',
				'ledgername' => $ledger ?? '',
				'counter' => $vendorName ?? '',
				'debit_ledger' => $debit > 0 ? ($ledger ?? '') : '',
				'credit_ledger' => $credit > 0 ? ($vendorName ?? '') : '',
				'narration' => 'Purchase',
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => round($shippingCost, 2),
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' => $purchase->pay_status ?? 'Due',
				'status' => $purchase->status ?? '',
				'details' => $details,
			];
			
			$payStatus = $purchase->pay_status ?? 'Due';
			$paymentRows = $this->getPaymentVoucherLedgerRows($propId,$userId,$from,$to,$ledger,$source,$purchase->id,$payStatus);
			if (!empty($paymentRows)) {
				$rows = array_merge($rows, $paymentRows);
			}
		}
		
		

		return $rows;
	}
	
	
	private function getPurchaseDebitNoteLedgerRows($propId, $userId, $from, $to, $ledger = null, $partyName = null, $vendId = null)
	{
		$rows = [];
		$source = 'Purchase Debit Note';

		$query = DB::table('voucher_purchases as vp')
			->join('purchases as p', 'p.inv_num', '=', 'vp.inv_number')
			->leftJoin('vendors as v', 'v.id', '=', 'p.inv_name')
			->whereBetween('vp.inv_date', [$from, $to])
			->select('vp.*', 'p.pay_status as payment_status', 'p.inv_name', 'v.id', 'v.vendor_name');

		/*
		|--------------------------------------------------------------------------
		| Company / User Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($propId)) {
			$query->where('vp.propId', $propId);
		} else {
			$query->where('vp.added_by', $userId);
		}

		/*
		|--------------------------------------------------------------------------
		| Vendor Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($vendId)) {
			$query->where('p.inv_name', $vendId);
		}

		$debitNotes = $query
			->select(
				'vp.*',
				'p.inv_name as purchase_vendor_id',
				'v.vendor_name'
			)
			->orderBy('vp.inv_date', 'asc')
			->orderBy('vp.id', 'asc')
			->get();

		foreach ($debitNotes as $debitNote) {

			/*
			|--------------------------------------------------------------------------
			| Vendor Name
			|--------------------------------------------------------------------------
			*/
			$vendorName = $debitNote->vendor_name
				?? $debitNote->seller_name
				?? '';

			/*
			|--------------------------------------------------------------------------
			| Purchase Amount
			|--------------------------------------------------------------------------
			*/
			$purchaseAmount = (float) ($debitNote->taxable_value ?? 0);

			/*
			|--------------------------------------------------------------------------
			| GST
			|--------------------------------------------------------------------------
			*/
			$cgst = (float) ($debitNote->cgst_amount ?? 0);
			$sgst = (float) ($debitNote->sgst_amount ?? 0);
			$igst = (float) ($debitNote->igst_amount ?? 0);

			/*
			|--------------------------------------------------------------------------
			| Total Debit Note
			|--------------------------------------------------------------------------
			*/
			$total = $purchaseAmount + $cgst + $sgst + $igst;

			/*
			|--------------------------------------------------------------------------
			| Check Selected Ledger
			|--------------------------------------------------------------------------
			*/
			$isVendor = false;

			if (
				!empty($ledger) &&
				strtolower(trim($ledger)) === strtolower(trim($vendorName))
			) {
				$isVendor = true;
			}

			if (
				!empty($partyName) &&
				strtolower(trim($partyName)) === strtolower(trim($vendorName))
			) {
				$isVendor = true;
			}

			/*
			|--------------------------------------------------------------------------
			| Debit / Credit
			|--------------------------------------------------------------------------
			*/
			$debit = 0;
			$credit = 0;

			if (empty($ledger) && empty($partyName)) {
				$debit = $total;
			}else if ($isVendor) {
				$debit = $total;
			} else {

				/*
				| Purchase Return
				*/
				if ($ledger === 'Purchase') {
					$credit = $purchaseAmount;
				}

				/*
				| Input CGST Reversal
				*/
				if ($ledger === 'Input CGST') {
					$credit = $cgst;
				}

				/*
				| Input SGST Reversal
				*/
				if ($ledger === 'Input SGST') {
					$credit = $sgst;
				}

				/*
				| Input IGST Reversal
				*/
				if ($ledger === 'Input IGST') {
					$credit = $igst;
				}
			}

			/*
			|--------------------------------------------------------------------------
			| Skip if selected ledger has no amount
			|--------------------------------------------------------------------------
			*/
			if ($debit == 0 && $credit == 0) {
				continue;
			}

			/*
			|--------------------------------------------------------------------------
			| Expanded Details
			|--------------------------------------------------------------------------
			*/
			$details = [];

			if ($purchaseAmount > 0) {
				$details[] = [
					'ledger' => 'Purchase',
					'debit' => 0,
					'credit' => round($purchaseAmount, 2),
				];
			}

			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Input CGST',
					'debit' => 0,
					'credit' => round($cgst, 2),
				];
			}

			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Input SGST',
					'debit' => 0,
					'credit' => round($sgst, 2),
				];
			}

			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Input IGST',
					'debit' => 0,
					'credit' => round($igst, 2),
				];
			}

			if ($total > 0) {
				$details[] = [
					'ledger' => $vendorName ?: 'Vendor',
					'debit' => round($total, 2),
					'credit' => 0,
				];
			}

			/*
			|--------------------------------------------------------------------------
			| Main Row
			|--------------------------------------------------------------------------
			*/
			$rows[] = [
				'date' => $debitNote->inv_date,
				'voucher' => $debitNote->inv_number ?? $debitNote->v_num ?? '-',
				'type' => 'Debit Note',
				'source' => $source,
				'transaction_details' => $debitNote->reason_issuance ?? 'Purchase Debit Note',
				'ledgername' => $ledger ?? '',
				'counter' => $vendorName,
				'debit_ledger' => $debit > 0 ? ($ledger ?? $vendorName) : '',
				'credit_ledger' => $credit > 0 ? ($ledger ?? 'Purchase') : '',
				'narration' => $debitNote->reason_issuance ?? 'Purchase Debit Note',
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => 0,
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' => $debitNote->payment_status ?? 'Due',
				'status' => $debitNote->status ?? '',
				'details' => $details,
			];
		}

		return $rows;
	}
	
	
	private function getExpenseLedgerRows($propId, $userId, $from, $to, $ledger = null, $partyName = null, $vendId = null)
	{
		$rows = [];
		$source = 'Expense';

		$query = DB::table('expenses as e')
			->whereBetween('e.expense_date', [$from, $to]);

		/*
		|--------------------------------------------------------------------------
		| Company / User Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($propId)) {
			$query->where('e.propId', $propId);
		} else {
			$query->where('e.added_by', $userId);
		}

		/*
		|--------------------------------------------------------------------------
		| Vendor Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($vendId)) {
			$query->where('e.vendor_id', $vendId);
		}

		//Freight / Carriage Inward which is already linked with Purchase
		$query->where(function ($q) {
			$q->where('e.expense_type', '!=', 'Freight / Carriage Inward')
				->orWhereNull('e.expense_type')
				->orWhereNotExists(function ($sub) {
					$sub->select(DB::raw(1))
						->from('purchases as p')
						->whereColumn(
							'p.inv_num',
							'e.exp_invno'
						);
				});
		});

		$expenses = $query
			->orderBy('e.expense_date', 'asc')
			->orderBy('e.id', 'asc')
			->get();

		foreach ($expenses as $expense) {

			$vendorName = '';
			if (!empty($expense->vendor_id)) {
				$vendorName = DB::table('vendors')
					->where('id', $expense->vendor_id)
					->value('vendor_name');
			}


			$expenseLedger = $expense->expense_type ?? '';
			if (empty($expenseLedger)) {
				$expenseLedger = $expense->other_expenses_details ?? 'Expense';
			}

			$expenseAmount = (float) ($expense->expense_amt ?? 0);
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			$gstAmount = (float) ($expense->total_gst ?? 0);
			$gstRate = (float) ($expense->gst_rate ?? 0);
			$gstTrans = strtolower(trim((string) ($expense->gst_trans ?? '')));
			if ($gstAmount > 0) {
				if (
					$gstTrans === 'intrastate' ||
					$gstTrans === 'union'
				) {
					$cgst = $gstAmount / 2;
					$sgst = $gstAmount / 2;
				}

				if ($gstTrans === 'interstate') {
					$igst = $gstAmount;
				}

			} elseif ($gstRate > 0 && $expenseAmount > 0) {
				if (
					$gstTrans === 'intrastate' ||
					$gstTrans === 'union'
				) {
					$cgst = ($expenseAmount * ($gstRate / 2)) / 100;

					$sgst = ($expenseAmount * ($gstRate / 2)) / 100;
				}

				if ($gstTrans === 'interstate') {
					$igst = ($expenseAmount * $gstRate) / 100;
				}
			}

			//Total Expense Payable
			$total =$expenseAmount + $cgst + $sgst + $igst;

			//Check Selected Ledger
			$isVendor = false;
			$isExpense = false;

			if (
				!empty($ledger) &&
				strtolower(trim($ledger)) ===
				strtolower(trim($vendorName))
			) {
				$isVendor = true;
			}

			if (
				!empty($partyName) &&
				strtolower(trim($partyName)) ===
				strtolower(trim($vendorName))
			) {
				$isVendor = true;
			}

			if (
				!empty($ledger) &&
				strtolower(trim($ledger)) ===
				strtolower(trim($expenseLedger))
			) {
				$isExpense = true;
			}

			$debit = 0;
			$credit = 0;
			if (empty($ledger) && empty($partyName)) {
				$debit = $total;
			}else if ($isVendor) {
				$credit = $total;
			} elseif ($isExpense) {
				$debit = $expenseAmount;
			} else {
				if ($ledger === 'Input CGST') {
					$debit = $cgst;
				}

				if ($ledger === 'Input SGST') {
					$debit = $sgst;
				}

				if ($ledger === 'Input IGST') {
					$debit = $igst;
				}
			}

			if ($debit == 0 && $credit == 0) {
				continue;
			}

			$details = [];
			if ($expenseAmount > 0) {
				$details[] = [
					'ledger' => $expenseLedger,
					'debit' => round($expenseAmount, 2),
					'credit' => 0,
				];
			}
			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Input CGST',
					'debit' => round($cgst, 2),
					'credit' => 0,
				];
			}
			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Input SGST',
					'debit' => round($sgst, 2),
					'credit' => 0,
				];
			}
			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Input IGST',
					'debit' => round($igst, 2),
					'credit' => 0,
				];
			}
			if ($total > 0 && !empty($vendorName)) {
				$details[] = [
					'ledger' => $vendorName,
					'debit' => 0,
					'credit' => round($total, 2),
				];
			}

			$rows[] = [
				'date' => $expense->expense_date,
				'voucher' =>$expense->exp_invno ?? '-',
				'type' => 'Expense',
				'source' => $source,
				'transaction_details' =>$expense->other_expenses_details ?? $expenseLedger,
				'ledgername' => $ledger ?? '',
				'counter' =>$vendorName ?: $expenseLedger,
				'debit_ledger' =>$debit > 0 ? ($ledger ?? $expenseLedger) : '',
				'credit_ledger' =>$credit > 0 ? ($ledger ?? $vendorName) : '',
				'narration' =>ucwords(str_replace('_', ' ', $expenseLedger)),
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => 0,
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' =>$expense->payment_status ?? 'Due',
				'status' =>$expense->status ?? '',
				'details' => $details,
			];

			$payStatus = $expense->payment_status ?? 'Due';
			$paymentRows = $this->getPaymentVoucherLedgerRows($propId,$userId,$from,$to,$ledger,$source,$expense->id,$payStatus);		
			if (!empty($paymentRows)) {
				$rows = array_merge(
					$rows,
					$paymentRows
				);
			}
		}

		return $rows;
	}
	
	
	private function getAssetLedgerRows($propId, $userId, $from, $to, $ledger = null, $partyName = null, $vendId = null)
	{
		$rows = [];
		$source = 'Asset';

		$query = DB::table('assets as a')
			->where('a.assetType', 'non-current')
			->whereBetween('a.date', [$from, $to]);

		if (!empty($propId)) {
			$query->where('a.propId', $propId);
		} else {
			$query->where('a.added_by', $userId);
		}

		if (!empty($vendId)) {
			$query->where(function ($q) use ($vendId) {
				$q->where('a.vendor_id', $vendId)
					->orWhere('a.cwip_vendor_id', $vendId);
			});
		}

		$assets = $query
			->orderBy('a.date', 'asc')
			->orderBy('a.id', 'asc')
			->get();

		foreach ($assets as $asset) {

			//Determine Asset / CWIP
			$isCwip = (
				!empty($asset->nonCurrentAssetType) &&
				strtolower(trim($asset->nonCurrentAssetType)) === 'capital work in progress'
			);

			$vendorId = $isCwip ? $asset->cwip_vendor_id : $asset->vendor_id;
			$vendorName = '';
			if (!empty($vendorId)) {
				$vendorName = DB::table('vendors')
					->where('id', $vendorId)
					->value('vendor_name');
			}

			$assetAmount = $isCwip ? (float) ($asset->cwip_amount ?? 0) : (float) ($asset->invoice_value ?? 0);
			$cgst = 0;
			$sgst = 0;
			$igst = 0;
			if (!$isCwip) {
				$gstAmount = (float) ($asset->gst_amt ?? 0);
				$gstTrans = strtolower(trim((string) ($asset->gst_trans ?? '')));
				if ($gstAmount > 0) {
					if (
						$gstTrans === 'intrastate' ||
						$gstTrans === 'union'
					) {
						$cgst = $gstAmount / 2;
						$sgst = $gstAmount / 2;
					}

					if ($gstTrans === 'interstate') {
						$igst = $gstAmount;
					}
				}
			}

			$total = $assetAmount + $cgst + $sgst + $igst;
			//Asset Ledger Name
			$assetLedger = $isCwip ? 'Capital Work in Progress' : ($asset->nonCurrentAssetType ?? 'Asset');
			//Check Selected Ledger
			$isVendor = false;
			/*if (
				!empty($ledger) &&
				strtolower(trim($ledger)) === strtolower(trim($vendorName))
			) {
				$isVendor = true;
			}

			if (
				!empty($partyName) &&
				strtolower(trim($partyName)) === strtolower(trim($vendorName))
			) {
				$isVendor = true;
			}*/
			
			if (!empty($ledger) && strcasecmp(trim($ledger), trim($vendorName)) === 0) {
				$isVendor = true;
			}

			if (!empty($partyName) && strcasecmp(trim($partyName), trim($vendorName)) === 0) {
				$isVendor = true;
			}

			if (!empty($vendId) && (int) $vendId === (int) $vendorId) {
				$isVendor = true;
			}

			if ($isVendor) {
				$credit = $total;
			}

			$debit = 0;
			$credit = 0;
			if (empty($ledger) && empty($partyName) && empty($vendId)) {
				$debit = $total;
			}else if ($isVendor) {
				$credit = $total;
			} else {
				/*
				| Asset / CWIP
				*/
				if ($ledger === $assetLedger || ($isCwip && $ledger === 'Capital Work in Progress')) {
					$debit = $assetAmount;
				}
				/*
				| Input CGST
				*/
				if ($ledger === 'Input CGST') {
					$debit = $cgst;
				}
				/*
				| Input SGST
				*/
				if ($ledger === 'Input SGST') {
					$debit = $sgst;
				}
				/*
				| Input IGST
				*/
				if ($ledger === 'Input IGST') {
					$debit = $igst;
				}
			}

			if ($debit == 0 && $credit == 0) {
				continue;
			}

			$details = [];
			//Asset / CWIP
			if ($assetAmount > 0) {
				$details[] = [
					'ledger' => $assetLedger,
					'debit' => round($assetAmount, 2),
					'credit' => 0,
				];
			}
			/*
			| Input CGST
			*/
			if ($cgst > 0) {
				$details[] = [
					'ledger' => 'Input CGST',
					'debit' => round($cgst, 2),
					'credit' => 0,
				];
			}
			/*
			| Input SGST
			*/
			if ($sgst > 0) {
				$details[] = [
					'ledger' => 'Input SGST',
					'debit' => round($sgst, 2),
					'credit' => 0,
				];
			}
			/*
			| Input IGST
			*/
			if ($igst > 0) {
				$details[] = [
					'ledger' => 'Input IGST',
					'debit' => round($igst, 2),
					'credit' => 0,
				];
			}
			/*
			| Vendor
			*/
			if ($total > 0) {
				$details[] = [
					'ledger' => $vendorName ?: 'Vendor',
					'debit' => 0,
					'credit' => round($total, 2),
				];
			}

			$rows[] = [
				'date' => $asset->date,
				'voucher' => $asset->invoice_no ?? '-',
				'type' => 'Asset',
				'source' => $source,
				'transaction_details' => $isCwip ? 'Capital Work in Progress' : 'Asset Purchase',
				'ledgername' => $ledger ?? '',
				'counter' => $vendorName,
				'debit_ledger' => $debit > 0 ? ($ledger ?? $assetLedger) : '',
				'credit_ledger' => $credit > 0 ? ($vendorName ?? '') : '',
				'narration' => $isCwip ? 'Capital Work in Progress' : 'Asset Purchase',
				'cgst' => round($cgst, 2),
				'sgst' => round($sgst, 2),
				'igst' => round($igst, 2),
				'shipping_cost' => 0,
				'debit' => round($debit, 2),
				'credit' => round($credit, 2),
				'balance' => 0,
				'payment_status' => $isCwip ? ($asset->cwip_pay_status ?? 'Due') : ($asset->pay_status ?? 'Due'),
				'status' => $asset->asset_status ?? '',
				'details' => $details,
			];

			$payStatus = $isCwip ? ($asset->cwip_pay_status ?? 'Due') : ($asset->pay_status ?? 'Due');
			$paymentRows = $this->getPaymentVoucherLedgerRows($propId,$userId,$from,$to,$ledger,$source,$asset->id,$payStatus);
			if (!empty($paymentRows)) {
				$rows = array_merge($rows, $paymentRows);
			}
		}

		return $rows;
	}
	
	
	// Payment vouchers
	private function getPaymentVoucherLedgerRows($propId, $userId, $from, $to, $ledger = null, $source = null, $f_id = null, $payStatus)
	{
		$rows = [];

		$query = DB::table('payment_vouchers as pv')
			->where('pv.f_id', $f_id)
			->where('pv.source', $source)
			->whereBetween('pv.date', [$from, $to]);

		/*
		|--------------------------------------------------------------------------
		| Company / User Filter
		|--------------------------------------------------------------------------
		*/
		if (!empty($propId)) {
			$query->where('pv.propId', $propId);
		} else {
			$query->where('pv.added_by', $userId);
		}

		$payments = $query
			->orderBy('pv.date', 'asc')
			->orderBy('pv.id', 'asc')
			->get();

		if ($payments->isEmpty()) {
			return $rows;
		}

		/*
		|--------------------------------------------------------------------------
		| Total Payment Voucher Amount
		|--------------------------------------------------------------------------
		*/
		$totalAmount = 0;
		$paymentAccounts = [];
		$firstPayment = $payments->first();

		foreach ($payments as $payment) {

			$amount = (float) ($payment->amount ?? 0);

			if ($amount <= 0) {
				continue;
			}

			$totalAmount += $amount;

			/*
			|--------------------------------------------------------------------------
			| Get Bank / Cash / UPI
			|--------------------------------------------------------------------------
			*/
			$paymentAccount = '';

			if (!empty($payment->bank_id)) {
				$paymentAccount = DB::table('banks')
					->where('id', $payment->bank_id)
					->value('bank_name');
			}

			if (empty($paymentAccount)) {
				$paymentAccount = $payment->payment_mode ?? 'Cash';
			}

			if (!empty($paymentAccount)) {
				$paymentAccounts[] = $paymentAccount;
			}
		}

		if ($totalAmount <= 0) {
			return $rows;
		}

		/*
		|--------------------------------------------------------------------------
		| Selected Ledger
		|--------------------------------------------------------------------------
		*/
		$selectedLedger = strtolower(trim((string) $ledger));

		$debit = 0;
		$credit = 0;

		/*
		|--------------------------------------------------------------------------
		| Check Payment Account
		|--------------------------------------------------------------------------
		*/
		$isPaymentAccount = false;

		foreach ($paymentAccounts as $account) {

			if ($selectedLedger === strtolower(trim($account))) {
				$isPaymentAccount = true;
				break;
			}
		}

		/*
		|--------------------------------------------------------------------------
		| Payment Mode Check
		|--------------------------------------------------------------------------
		*/
		if (!$isPaymentAccount) {

			foreach ($payments as $payment) {

				if (
					$selectedLedger === strtolower(
						trim((string) ($payment->payment_mode ?? ''))
					)
				) {
					$isPaymentAccount = true;
					break;
				}
			}
		}

		/*
		|--------------------------------------------------------------------------
		| Get Party Name
		|--------------------------------------------------------------------------
		*/
		$partyNames = [];
		foreach ($payments as $payment) {

			if (!empty($payment->party_name)) {
				$partyNames[] = $payment->party_name;
			} elseif (!empty($payment->other_party_type)) {
				$partyNames[] = $payment->other_party_type;
			}
		}

		$partyText = implode(', ', array_unique($partyNames));

		/*
		|--------------------------------------------------------------------------
		| Payment Account Name
		|--------------------------------------------------------------------------
		*/
		$paymentAccountText = implode(', ', array_unique($paymentAccounts));

		$isSales = strtolower(trim((string) $source)) === 'sales';
		if ($isPaymentAccount) {
			if ($isSales) {
				/*
				| Sales Receipt:
				| Bank / Cash is Debited
				*/
				$debit = $totalAmount;
			} else {
				/*
				| Purchase / Expense / Asset Payment:
				| Bank / Cash is Credited
				*/
				$credit = $totalAmount;
			}
		} else {
			foreach ($payments as $payment) {
				$partyName = strtolower(trim((string) ($payment->party_name ?? '')));
				$otherParty = strtolower(trim((string) ($payment->other_party_type ?? '')));
				if ($selectedLedger === $partyName || $selectedLedger === $otherParty) {
					if ($isSales) {
						/*
						| Sales Receipt:
						| Customer is Credited
						*/
						$credit = $totalAmount;
					} else {
						/*
						| Purchase / Expense / Asset Payment:
						| Vendor / Party is Debited
						*/
						$debit = $totalAmount;
					}
					break;
				}
			}
		}

		if ($debit == 0 && $credit == 0) {
			return $rows;
		}
		
		//Expanded Details
		$details = [];
		foreach ($payments as $payment) {
			$amount = (float) ($payment->amount ?? 0);
			if ($amount <= 0) {
				continue;
			}

			$paymentAccount = '';
			if (!empty($payment->bank_id)) {
				$paymentAccount = DB::table('banks')
					->where('id', $payment->bank_id)
					->value('bank_name');
			}

			if (empty($paymentAccount)) {
				$paymentAccount = $payment->payment_mode ?? 'Cash';
			}

			//Party
			$paymentParty = $payment->party_name ?? $payment->other_party_type ?? '';
			if ($isSales) {
				/*
				| Sales Receipt:
				| Bank / Cash Dr
				| Customer Cr
				*/
				if (!empty($paymentAccount)) {
					$details[] = [
						'ledger' => $paymentAccount,
						'debit' => round($amount, 2),
						'credit' => 0,
					];
				}

				if (!empty($paymentParty)) {
					$details[] = [
						'ledger' => $paymentParty,
						'debit' => 0,
						'credit' => round($amount, 2),
					];
				}

			} else {
				/*
				| Purchase / Expense / Asset Payment:
				| Party Dr
				| Bank / Cash Cr
				*/
				if (!empty($paymentParty)) {
					$details[] = [
						'ledger' => $paymentParty,
						'debit' => round($amount, 2),
						'credit' => 0,
					];
				}

				if (!empty($paymentAccount)) {
					$details[] = [
						'ledger' => $paymentAccount,
						'debit' => 0,
						'credit' => round($amount, 2),
					];
				}
			}
		}

		/*
		|--------------------------------------------------------------------------
		| Main Row
		|--------------------------------------------------------------------------
		*/
		$rows[] = [
			'date' => $firstPayment->date,
			'voucher' => $firstPayment->voucher_no ?? 'Payment Voucher',
			'type' => 'Payment Voucher',
			'source' => 'Bank',
			'transaction_details' => $firstPayment->transaction_details ?? $firstPayment->other_transaction_details ?? 'Payment Voucher',
			'ledgername' => $ledger ?? '',
			'counter' => $isPaymentAccount ? $partyText : $paymentAccountText,
			'debit_ledger' => $debit > 0 ? ($isPaymentAccount ? $paymentAccountText : $ledger) : '',
			'credit_ledger' => $credit > 0 ? ($isPaymentAccount ? $paymentAccountText : $ledger) : '',
			'narration' => $paymentAccountText,
			'cgst' => 0,
			'sgst' => 0,
			'igst' => 0,
			'shipping_cost' => 0,
			'debit' => round($debit, 2),
			'credit' => round($credit, 2),
			'balance' => 0,
			'payment_status' => $payStatus,
			'status' => 'Posted',
			'details' => $details,
		];

		return $rows;
	}
	
	private function getLiabilityLedgerRows($propId, $userId, $from, $to, $ledger = null, $partyName = null)
	{
		$rows = [];
		$source = 'Liability';

		/*
		|--------------------------------------------------------------------------
		| Common Liability Master
		|--------------------------------------------------------------------------
		*/
		$liabilitiesQuery = DB::table('liabilities as l')
			->whereIn('l.liabilities_type', [
				'non_current_liabilities',
				'share_holder_fund',
				'share_application_money',
			])
			->where('l.status', 1);

		if (!empty($propId)) {
			$liabilitiesQuery->where('l.propId', $propId);
		} else {
			$liabilitiesQuery->where('l.added_by', $userId);
		}

		$liabilities = $liabilitiesQuery->get();

		foreach ($liabilities as $liability) {

			/*
			|--------------------------------------------------------------------------
			| Non-Current Liabilities
			|--------------------------------------------------------------------------
			*/
			if ($liability->liabilities_type === 'non_current_liabilities') {

				$query = DB::table('non_current_liabilities as ncl')
					->where('ncl.liabilities_id', $liability->id)
					->whereBetween('ncl.due_date', [$from, $to]);

				if (!empty($propId)) {
					$query->where('ncl.added_by', $userId);
				} else {
					$query->where('ncl.added_by', $userId);
				}

				$liabilityRows = $query
					->orderBy('ncl.due_date', 'asc')
					->orderBy('ncl.id', 'asc')
					->get();

				foreach ($liabilityRows as $item) {

					$amount = (float) ($item->amount ?? 0);

					if ($amount <= 0) {
						continue;
					}

					$partyNameValue = $item->party_name
						?? $item->lender_name
						?? '';

					/*
					|--------------------------------------------------------------------------
					| Liability Ledger Name
					|--------------------------------------------------------------------------
					*/
					$liabilityLedger = $item->liability_category
						?? $item->other_liability_type
						?? 'Non-Current Liability';

					/*
					|--------------------------------------------------------------------------
					| Selected Ledger Check
					|--------------------------------------------------------------------------
					*/
					$isSelected = false;

					if (
						!empty($ledger) &&
						strtolower(trim($ledger)) === strtolower(trim($liabilityLedger))
					) {
						$isSelected = true;
					}

					if (
						!empty($partyName) &&
						strtolower(trim($partyName)) === strtolower(trim($partyNameValue))
					) {
						$isSelected = true;
					}

					if (!$isSelected && !empty($ledger)) {
						continue;
					}

					/*
					|--------------------------------------------------------------------------
					| Liability = Credit
					|--------------------------------------------------------------------------
					*/
					$debit = 0;
					$credit = $amount;

					/*
					|--------------------------------------------------------------------------
					| Details
					|--------------------------------------------------------------------------
					*/
					$details = [
						[
							'ledger' => $liabilityLedger,
							'debit' => 0,
							'credit' => round($amount, 2),
						],
					];

					if (!empty($partyNameValue)) {
						$details[] = [
							'ledger' => $partyNameValue,
							'debit' => round($amount, 2),
							'credit' => 0,
						];
					}

					$rows[] = [
						'date' => $item->due_date ?? $liability->added_date,
						'voucher' => $item->invoice_no ?? '-',
						'type' => 'Liability',
						'source' => $source,
						'transaction_details' => $liabilityLedger,
						'ledgername' => $ledger ?? '',
						'counter' => $partyNameValue,
						'debit_ledger' => $debit > 0 ? ($ledger ?? $partyNameValue) : '',
						'credit_ledger' => $credit > 0 ? $liabilityLedger : '',
						'narration' => $item->notes ?? $liabilityLedger,
						'cgst' => 0,
						'sgst' => 0,
						'igst' => 0,
						'shipping_cost' => 0,
						'debit' => round($debit, 2),
						'credit' => round($credit, 2),
						'balance' => 0,
						'payment_status' => 'Due',
						'status' => $liability->status ?? '',
						'details' => $details,
					];
				}
			}

			/*
			|--------------------------------------------------------------------------
			| Share Holder Fund
			|--------------------------------------------------------------------------
			*/
			if ($liability->liabilities_type === 'share_holder_fund') {

				$query = DB::table('share_holder_fund_liabilities as shf')
					->where('shf.liabilities_id', $liability->id)
					->whereBetween('shf.allotment_date', [$from, $to]);

				$query->where('shf.added_by', $userId);

				$shareFunds = $query
					->orderBy('shf.allotment_date', 'asc')
					->orderBy('shf.id', 'asc')
					->get();

				foreach ($shareFunds as $item) {

					$amount = (float) ($item->total_amount ?? 0);

					if ($amount <= 0) {
						continue;
					}

					/*
					|--------------------------------------------------------------------------
					| Share Holder Ledger
					|--------------------------------------------------------------------------
					*/
					$liabilityLedger = 'Share Holder Fund';

					if (!empty($item->share_holder_fund_type)) {
						$liabilityLedger = ucwords(
							str_replace('_', ' ', $item->share_holder_fund_type)
						);
					}

					$partyNameValue = $item->payto ?? '';

					/*
					|--------------------------------------------------------------------------
					| Selected Ledger Check
					|--------------------------------------------------------------------------
					*/
					$isSelected = false;

					if (
						!empty($ledger) &&
						strtolower(trim($ledger)) === strtolower(trim($liabilityLedger))
					) {
						$isSelected = true;
					}

					if (
						!empty($partyName) &&
						strtolower(trim($partyName)) === strtolower(trim($partyNameValue))
					) {
						$isSelected = true;
					}

					if (!$isSelected && !empty($ledger)) {
						continue;
					}

					/*
					|--------------------------------------------------------------------------
					| Share Holder Fund = Credit
					|--------------------------------------------------------------------------
					*/
					$debit = 0;
					$credit = $amount;

					$details = [
						[
							'ledger' => $liabilityLedger,
							'debit' => 0,
							'credit' => round($amount, 2),
						],
					];

					if (!empty($partyNameValue)) {
						$details[] = [
							'ledger' => $partyNameValue,
							'debit' => round($amount, 2),
							'credit' => 0,
						];
					}

					$rows[] = [
						'date' => $item->allotment_date ?? $liability->added_date,
						'voucher' => $item->share_certificate_no ?? '-',
						'type' => 'Share Holder Fund',
						'source' => $source,
						'transaction_details' => $liabilityLedger,
						'ledgername' => $ledger ?? '',
						'counter' => $partyNameValue,
						'debit_ledger' => '',
						'credit_ledger' => $liabilityLedger,
						'narration' => $item->description ?? $liabilityLedger,
						'cgst' => 0,
						'sgst' => 0,
						'igst' => 0,
						'shipping_cost' => 0,
						'debit' => 0,
						'credit' => round($credit, 2),
						'balance' => 0,
						'payment_status' => 'Due',
						'status' => $liability->status ?? '',
						'details' => $details,
					];
				}
			}

			/*
			|--------------------------------------------------------------------------
			| Share Application Money
			|--------------------------------------------------------------------------
			*/
			if ($liability->liabilities_type === 'share_application_money') {

				$query = DB::table('share_application_money_liabilities as sam')
					->where('sam.liabilities_id', $liability->id)
					->whereBetween('sam.date_received', [$from, $to]);

				$query->where('sam.added_by', $userId);

				$applications = $query
					->orderBy('sam.date_received', 'asc')
					->orderBy('sam.id', 'asc')
					->get();

				foreach ($applications as $item) {

					$amount = (float) ($item->amount_received ?? 0);

					if ($amount <= 0) {
						continue;
					}

					/*
					|--------------------------------------------------------------------------
					| Share Application Money Ledger
					|--------------------------------------------------------------------------
					*/
					$liabilityLedger = 'Share Application Money';

					$partyNameValue = $item->applicant_name ?? '';

					/*
					|--------------------------------------------------------------------------
					| Selected Ledger Check
					|--------------------------------------------------------------------------
					*/
					$isSelected = false;

					if (
						!empty($ledger) &&
						strtolower(trim($ledger)) === strtolower(trim($liabilityLedger))
					) {
						$isSelected = true;
					}

					if (
						!empty($partyName) &&
						strtolower(trim($partyName)) === strtolower(trim($partyNameValue))
					) {
						$isSelected = true;
					}

					if (!$isSelected && !empty($ledger)) {
						continue;
					}

					/*
					|--------------------------------------------------------------------------
					| Share Application Money = Credit
					|--------------------------------------------------------------------------
					*/
					$debit = 0;
					$credit = $amount;

					$details = [
						[
							'ledger' => $liabilityLedger,
							'debit' => 0,
							'credit' => round($amount, 2),
						],
					];

					if (!empty($partyNameValue)) {
						$details[] = [
							'ledger' => $partyNameValue,
							'debit' => round($amount, 2),
							'credit' => 0,
						];
					}

					$rows[] = [
						'date' => $item->date_received ?? $liability->added_date,
						'voucher' => '-',
						'type' => 'Share Application Money',
						'source' => $source,
						'transaction_details' => $liabilityLedger,
						'ledgername' => $ledger ?? '',
						'counter' => $partyNameValue,
						'debit_ledger' => '',
						'credit_ledger' => $liabilityLedger,
						'narration' => $item->special_note ?? $liabilityLedger,
						'cgst' => 0,
						'sgst' => 0,
						'igst' => 0,
						'shipping_cost' => 0,
						'debit' => 0,
						'credit' => round($credit, 2),
						'balance' => 0,
						'payment_status' => $item->allotment_status ?? 'Pending',
						'status' => $liability->status ?? '',
						'details' => $details,
					];
				}
			}
		}

		return $rows;
	}

}
