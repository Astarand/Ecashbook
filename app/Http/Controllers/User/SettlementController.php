<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\User;
use App\Models\Loans;

use App\Models\Banks;
use App\Models\Bank_trans;
use App\Models\Loan_ins;
use App\Models\Cash_credit_debits;
use App\Models\Mcash_credit_debits;
use App\Models\Cash_hands;
use App\Models\Bank_statements;
use App\Models\PaymentVoucher;
use App\Models\Journals;
use Helper;
use Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Helpers\AuditLogger;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class SettlementController extends Controller
{
    public function __construct(JournalService $journalService = null, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
        $this->paymentVoucherService = $paymentVoucherService;
    }
	
	public function getSettlementLedgers(Request $request)
	{
		$uid = currentOwnerId();

		$moduleType = $request->module_type;

		$data = collect();

		if ($moduleType === 'Sales') {

			$data = DB::table('customers')
				->where('userId', $uid)
				->select(
					'id',
					DB::raw("cust_name as name")
				)
				->orderBy('cust_name')
				->get();

		} elseif (
			$moduleType === 'Purchase' ||
			$moduleType === 'Expense'
		) {

			$data = DB::table('vendors')
				->where('userId', $uid)
				->select(
					'id',
					DB::raw("vendor_name as name")
				)
				->orderBy('vendor_name')
				->get();
		}

		return response()->json([
			'success' => true,
			'data' => $data
		]);
	}
	
	public function store(Request $request)
	{
		$request->validate([

			'module_type' => 'required|in:Sales,Purchase,Expense',

			'p_id' => 'required|integer',

			'settlement_mode' => 'required|in:Self,Third Party',

			'settlement_amount' => 'required|numeric|min:0.01',

			'settlement_ledger_id' => [
				'nullable',
				'required_if:settlement_mode,Third Party',
			],

			'other_settlement_ledger' => [
				'nullable',
				'required_if:settlement_ledger_id,other',
				'string',
				'max:255',
			],

			'settlement_reason' => [
				'nullable',
				'required_if:settlement_mode,Third Party',
				'string',
				'max:255',
			],
		]);

		$uid = currentOwnerId();

		try {

			$result = DB::transaction(function () use ($request, $uid) {

				$document = $this->getSettlementDocument(
					$request->module_type,
					$request->p_id,
					$uid
				);
				
				$settm = DB::table('settlements')
								->where('p_id', $request->p_id)
								->where('module_type', $request->module_type)
								->where('uid', $uid)
								->first();
				// Delete journal and settlement
				if ($settm) {
					DB::table('journals')
							->where('source', 'Settlement')
							->where('autoId', $settm->id)
							->where('source', 'Settlement')
							->where('added_by', $uid)
							->delete();
							
					DB::table('settlements')
						->where('p_id', $request->p_id)
						->where('uid', $uid)
						->delete();
				}

				/*
				|--------------------------------------------------------------------------
				| 2. Settlement Ledger
				|--------------------------------------------------------------------------
				*/

				$settlementLedgerId = null;
				$settlementLedgerName = null;

				if ($request->settlement_mode === 'Third Party') 
				{
					if ($request->settlement_ledger_id === 'other') 
					{
						$settlementLedgerName = $request->other_settlement_ledger;

					} else {
						$settlementLedgerId = $request->settlement_ledger_id;
						$settlementLedgerName = $this->getSettlementLedgerName($request->module_type,$settlementLedgerId,$uid);
					}
				}


				/*
				|--------------------------------------------------------------------------
				| 3. Create Settlement
				|--------------------------------------------------------------------------
				*/

				$settlementId = DB::table('settlements')->insertGetId([

					'uid' => $uid,

					'module_type' =>
						$request->module_type,

					'p_id' =>
						$request->p_id,

					'settlement_mode' =>
						$request->settlement_mode,

					'settlement_amount' =>
						$request->settlement_amount,

					'settlement_ledger_id' =>
						$settlementLedgerId,

					'settlement_ledger_name' =>
						$settlementLedgerName,

					'settlement_reason' =>
						$request->settlement_reason,

					'settlement_date' =>
						now()->toDateString(),

					'created_at' =>
						now(),

					'updated_at' =>
						now(),
				]);

				/*
				|--------------------------------------------------------------------------
				| 6. Create Journal
				|--------------------------------------------------------------------------
				*/
				$settlement = DB::table('settlements')
								->where('id', $settlementId)
								->first();

				$journalId = $this->createSettlementJournal($settlement,$document,$uid);

				return [
					'journal_id' => $journalId,
				];
			});


			return response()->json([
				'success' => true,
				'message' => 'Settlement saved and journal entry created successfully.',
				'data' => $result,
			]);

		} catch (\Throwable $e) {

			\Log::error(
				'Settlement Error: ' . $e->getMessage(),
				[
					'request' =>
						$request->all(),

					'trace' =>
						$e->getTraceAsString(),
				]
			);

			return response()->json([

				'success' => false,

				'message' =>
					$e->getMessage(),

			], 422);
		}
	}
	
	private function getSettlementDocument($moduleType,$pId,$uid) 
	{

		if ($moduleType === 'Sales') {

			return DB::table('sales as s')
				->leftJoin(
					'customers as c',
					'c.id',
					'=',
					's.inv_name'
				)
				->select(
					's.*',
					'c.cust_name as party_name'
				)
				->where('s.id', $pId)
				->where('s.added_by', $uid)
				->first();
		}


		if ($moduleType === 'Purchase') {

			return DB::table('purchases as p')
				->leftJoin(
					'vendors as v',
					'v.id',
					'=',
					'p.inv_name'
				)
				->select(
					'p.*',
					'v.vendor_name as party_name'
				)
				->where('p.id', $pId)
				->where('p.added_by', $uid)
				->first();
		}


		if ($moduleType === 'Expense') {

			return DB::table('expenses as p')
				->leftJoin(
					'vendors as v',
					'v.id',
					'=',
					'p.vendor_id'
				)
				->select(
					'p.*',
					'v.vendor_name as party_name'
				)
				->where('p.id', $pId)
				->where('p.added_by', $uid)
				->first();
		}


		return null;
	}
	
	private function getSettlementLedgerName($moduleType,$ledgerId,$uid) 
	{

		if ($moduleType === 'Sales') {

			return DB::table('customers')
				->where('id', $ledgerId)
				->where('userId', $uid)
				->value('cust_name');
		}


		if (
			$moduleType === 'Purchase' ||
			$moduleType === 'Expense'
		) {

			return DB::table('vendors')
				->where('id', $ledgerId)
				->where('userId', $uid)
				->value('vendor_name');
		}


		return null;
	}
	
	private function createSettlementJournal($settlement,$document,$uid) 
	{
		$amount = (float) $settlement->settlement_amount;
		$settlement_ledger_id = $settlement->settlement_ledger_id;
		$partyName = $settlement->settlement_ledger_name ?? '';
		
		
		if ($settlement->settlement_mode === 'Self') {
			return null;
		}
		return $this->journalService->storeSettlementJournalEntries([

					'module_type' => $settlement->module_type,
					'source' => 'Settlement',
					'autoId' => $settlement->id,
					'added_by' => $uid,
					'propId' => $document->propId ?? null,
					'date' => $settlement->settlement_date,
					'reference_no' => 'SET-' . $settlement->id,
					'entry_type' => $settlement->module_type.' Settlement',
					'party_name' => $partyName,
					'amount' => $settlement->settlement_amount,
					'settlement_ledger' => $partyName,
					'settlement_reason' => $settlement->settlement_reason,
					'status' => 'Posted',

				]);
		
	}


}
