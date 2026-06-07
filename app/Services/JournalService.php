<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Journals;
use Carbon\Carbon;

class JournalService
{
    
    public function __construct()
    {
        
    }
	
	public function checkExisting($autoId,$userId,$source)
	{
		$existing = Journals::where('autoId', $autoId)
			->where('source', $source)
			->get();
		return $existing;
	}
	
	public function getJournalNo($autoId,$userId,$source)
	{
		// ================= CHECK EXISTING =================
		$existing = Journals::where('autoId', $autoId)
			->where('source', $source)
			->get();

		// ================= JOURNAL NO =================
		if ($existing->count() > 0) {
			$journalNo = $existing->first()->journal_no;
		} else {
			$lastNo = Journals::where('added_by', $userId)->max('journal_no');
			$lastNo = $lastNo ? (int)$lastNo : 0;
			$journalNo = str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
		}
		return $journalNo;
	}
	
	//Sales journal
	public function storeSalesJournalEntries(array $data)
	{
		DB::beginTransaction();

		try {

			// ================= BASIC DATA =================
			$userId   = $data['added_by'];
			$autoId   = $data['autoId'];
			$propId   = $data['propId'] ?? null;
			$date     = $data['date'];
			$refNo    = $data['reference_no'];
			$source   = $data['source'] ?? 'Sales';
			$entryType = $data['entry_type'] ?? $source;

			$totalAmount = ($data['total_amount']) ?? 0;
			$baseAmount  = $data['total_amount'] ?? 0;
			$gstAmount   = $data['gst_amount'] ?? 0;
			$tot_amt = ($data['total_amount'] + $data['gst_amount']) ?? 0;

			$gstRate  = $data['gst_rate'] ?? 0;
			$gstTrans = $data['gst_trans'] ?? '';
			$party    = $data['party_name'] ?? '';

			// ================= GST SPLIT =================
			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			if (strtolower($gstTrans) == 'intrastate') {
				$cgst = $gstAmount / 2;
				$sgst = $gstAmount / 2;
			} else {
				$igst = $gstAmount;
			}

			
			$existing = $this->checkExisting($autoId,$userId,$source);
			$journalNo = $this->getJournalNo($autoId,$userId,$source);

			// ================= DELETE OLD (UPDATE CASE) =================
			$rev_amend_status = null;
			if ($existing->count() > 0) {
				//If status = 1 → amend, else null
				$rev_amend_status = (isset($data['status']) && $data['status'] == 1) ? 'amend' : null;
				Journals::where('autoId', $autoId)
					->where('source', $source)
					->delete();
			}

			// ================= COMMON DATA =================
			$common = [
				'journal_no'     => $journalNo,
				'added_by'       => $userId,
				'autoId'         => $autoId,
				'propId'         => $propId,
				'journal_date'   => $date,
				'reference_type' => 'New Ref',
				'reference_no'   => $refNo,
				'entry_type'     => $entryType,
				'source'         => $source,
				'tds_applicable' => 'no',
				'tds_percent'    => 0,
				'tds_amt'        => 0,
				'tds_id'         => null,
				'hsn_sac_code'   => null,
				'other_note'     => null,
				'status'         => 'Posted',
				'rev_amend_status'   => $rev_amend_status,
			];

			$entries = [];

			// ================= 1. CUSTOMER DR =================
			$entries[] = array_merge($common, [
				'ledger'         => $party,
				'party_name'     => 'Customer',
				'debit_credit'   => 'Debit',
				'amount'         => $totalAmount,
				'tot_amt'        => $tot_amt,
				'notes'          => 'Sale',
				'gst_applicable' => 'yes',
				'gst_rate'       => $gstRate,
				'gst_trans'      => $gstTrans,
			]);

			// ================= 2. SALES CR =================
			$entries[] = array_merge($common, [
				'ledger'         => 'Sales',
				'party_name'     => null,
				'debit_credit'   => 'Credit',
				'amount'         => $baseAmount,
				'tot_amt'        => $baseAmount,
				'notes'          => 'Income',
				'gst_applicable' => 'no',
				'gst_rate'       => 0,
				'gst_trans'      => null,
			]);

			// ================= CGST =================
			if ($cgst > 0) {
				$entries[] = array_merge($common, [
					'ledger'         => 'Output CGST',
					'party_name'     => null,
					'debit_credit'   => 'Credit',
					'amount'         => $cgst,
					'tot_amt'         => $cgst,
					'notes'          => 'GST',
					'gst_applicable' => 'no',
					'gst_rate'       => 0,
					'gst_trans'      => null,
				]);
			}

			// ================= SGST =================
			if ($sgst > 0) {
				$entries[] = array_merge($common, [
					'ledger'         => 'Output SGST',
					'party_name'     => null,
					'debit_credit'   => 'Credit',
					'amount'         => $sgst,
					'tot_amt'        => $sgst,
					'notes'          => 'GST',
					'gst_applicable' => 'no',
					'gst_rate'       => 0,
					'gst_trans'      => null,
				]);
			}

			// ================= IGST =================
			if ($igst > 0) {
				$entries[] = array_merge($common, [
					'ledger'         => 'Output IGST',
					'party_name'     => null,
					'debit_credit'   => 'Credit',
					'amount'         => $igst,
					'tot_amt'        => $igst,
					'notes'          => 'GST',
					'gst_applicable' => 'no',
					'gst_rate'       => 0,
					'gst_trans'      => null,
				]);
			}

			// ================= INSERT =================
			Journals::insert($entries);

			DB::commit();
			return true;

		} catch (\Exception $e) {

			DB::rollback();
			\Log::error('Sales Journal Error: ' . $e->getMessage());
			return false;
		}
	}
	
	//Purchase journal
	public function storePurchaseJournalEntries(array $data)
	{
		DB::beginTransaction();

		try {

			// ================= BASIC DATA =================
			$userId   = $data['added_by'];
			$autoId   = $data['autoId'];
			$propId   = $data['propId'] ?? null;
			$date     = $data['date'];
			$refNo    = $data['reference_no'];
			$source   = $data['source'] ?? 'Purchase';
			$entryType = $data['entry_type'] ?? $source;

			$baseAmount = $data['total_amount'] ?? 0;   // 200000
			$gstAmount  = $data['gst_amount'] ?? 0;     // 36000
			$totalAmt   = $baseAmount;     				// 236000
			$tot_amt    = $baseAmount + $gstAmount;

			$gstRate  = $data['gst_rate'] ?? 0;
			$gstTrans = $data['gst_trans'] ?? '';
			$party    = $data['party_name'] ?? '';

			// ================= GST SPLIT =================
			$cgst = 0;
			$sgst = 0;
			$igst = 0;

			if (strtolower($gstTrans) == 'intrastate') {
				$cgst = $gstAmount / 2;
				$sgst = $gstAmount / 2;
			} else {
				$igst = $gstAmount;
			}

			$existing = $this->checkExisting($autoId,$userId,$source);
			$journalNo = $this->getJournalNo($autoId,$userId,$source);

			// ================= DELETE OLD =================
			$rev_amend_status = null;
			if ($existing->count() > 0) {
				$rev_amend_status = (isset($data['status']) && $data['status'] == 1) ? 'amend' : null;
				Journals::where('autoId', $autoId)
					->where('source', $source)
					->delete();
			}

			// ================= COMMON =================
			$common = [
				'journal_no'     => $journalNo,
				'added_by'       => $userId,
				'autoId'         => $autoId,
				'propId'         => $propId,
				'journal_date'   => $date,
				'reference_type' => 'New Ref',
				'reference_no'   => $refNo,
				'entry_type'     => $entryType,
				'source'         => $source,
				'tds_applicable' => $data['tds_applicable'] ?? 'no',
				'tds_percent'    => $data['tds_percent'] ?? 0,
				'tds_amt'        => $data['tds_amt'] ?? 0,
				'tds_id'         => $data['tds_id'] ?? null,
				'hsn_sac_code'   => null,
				'other_note'     => 'Purchase entry',
				'status'         => 'Posted',
				'rev_amend_status'   => $rev_amend_status,
			];

			$entries = [];

			// ================= 1. VENDOR CR =================
			$entries[] = array_merge($common, [
				'ledger'         => $party,
				'party_name'     => 'Vendor',
				'debit_credit'   => 'Credit',
				'amount'         => $totalAmt,
				'tot_amt'        => $tot_amt,
				'notes'          => 'Payable',
				'gst_applicable' => 'yes',
				'gst_rate'       => $gstRate,
				'gst_trans'      => $gstTrans,
			]);

			// ================= 2. PURCHASE DR =================
			$entries[] = array_merge($common, [
				'ledger'         => 'Purchase',
				'party_name'     => null,
				'debit_credit'   => 'Debit',
				'amount'         => $baseAmount,
				'tot_amt'        => $baseAmount,
				'notes'          => 'Purchase',
				'gst_applicable' => 'no',
				'gst_rate'       => 0,
				'gst_trans'      => null,
			]);

			// ================= INPUT CGST =================
			if ($cgst > 0) {
				$entries[] = array_merge($common, [
					'ledger'         => 'Input CGST',
					'party_name'     => null,
					'debit_credit'   => 'Debit',
					'amount'         => $cgst,
					'tot_amt'        => $cgst,
					'notes'          => 'GST',
					'gst_applicable' => 'no',
					'gst_rate'       => 0,
					'gst_trans'      => null,
				]);
			}

			// ================= INPUT SGST =================
			if ($sgst > 0) {
				$entries[] = array_merge($common, [
					'ledger'         => 'Input SGST',
					'party_name'     => null,
					'debit_credit'   => 'Debit',
					'amount'         => $sgst,
					'tot_amt'        => $sgst,
					'notes'          => 'GST',
					'gst_applicable' => 'no',
					'gst_rate'       => 0,
					'gst_trans'      => null,
				]);
			}

			// ================= INPUT IGST =================
			if ($igst > 0) {
				$entries[] = array_merge($common, [
					'ledger'         => 'Input IGST',
					'party_name'     => null,
					'debit_credit'   => 'Debit',
					'amount'         => $igst,
					'tot_amt'         => $igst,
					'notes'          => 'GST',
					'gst_applicable' => 'no',
					'gst_rate'       => 0,
					'gst_trans'      => null,
				]);
			}

			// ================= INSERT =================
			Journals::insert($entries);

			DB::commit();
			return true;

		} catch (\Exception $e) {

			DB::rollback();
			\Log::error('Purchase Journal Error: ' . $e->getMessage());
			return false;
		}
	}
	
	//Expense journal
	public function storeExpenseJournalEntries(array $data)
	{
		DB::beginTransaction();

		try {

			// ================= BASIC =================
			$userId   = $data['added_by'];
			$autoId   = $data['autoId'];
			$propId   = $data['propId'] ?? null;
			$date     = $data['date'];
			$refNo    = $data['reference_no'];
			$source   = $data['source'] ?? 'Expense';
			$entryType = $data['entry_type'] ?? 'Expense';

			$expenseLedger = $data['ledger']; // Expense name
			$party         = $data['party_name'] ?? '';		
			
			$amount        = (float) ($data['amount'] ?? 0);
			$tot_amt       = (float) ($data['amount'] ?? 0);

			$tdsApplicable = $data['tds_applicable'] ?? 'no';
			$tdsPercent    = (float) ($data['tds_percent'] ?? 0);
			$tdsAmount     = (float) ($data['tds_amt'] ?? 0);


			// If TDS applicable but amount not given → calculate
			if ($tdsApplicable == 'yes' && $tdsAmount == 0 && $tdsPercent > 0) {
				$tdsAmount = ($amount * $tdsPercent) / 100;
			}

			$netPayable = $amount - $tdsAmount;

			$existing = $this->checkExisting($autoId,$userId,$source);
			$journalNo = $this->getJournalNo($autoId,$userId,$source);

			// ================= DELETE OLD =================
			$rev_amend_status = null;
			if ($existing->count() > 0) {
				$rev_amend_status = (isset($data['status']) && $data['status'] == 1) ? 'amend' : null;
				Journals::where('autoId', $autoId)
					->where('source', $source)
					->delete();
			}

			// ================= COMMON =================
			$common = [
				'journal_no'     => $journalNo,
				'added_by'       => $userId,
				'autoId'         => $autoId,
				'propId'         => $propId,
				'journal_date'   => $date,
				'reference_type' => 'New Ref',
				'reference_no'   => $refNo,
				'entry_type'     => $entryType,
				'source'         => $source,
				'tds_applicable' => $tdsApplicable,
				'tds_percent'    => $tdsPercent,
				'tds_amt'        => $tdsAmount,
				'tds_id'         => $data['tds_id'] ?? null,
				'status'         => 'Posted',
				'rev_amend_status'   => $rev_amend_status,
			];

			$entries = [];

			// ================= 1. EXPENSE DR =================
			$entries[] = array_merge($common, [
				'ledger'       => $expenseLedger,
				'party_name'   => $party,
				'debit_credit' => 'Debit',
				'amount'       => $amount,
				'tot_amt'      => $tot_amt,
				'notes'        => $data['other_note'] ?? 'Expense',
			]);

			// ================= 2. TDS PAYABLE CR =================
			if ($tdsApplicable == 'yes' && $tdsAmount > 0) {
				$entries[] = array_merge($common, [
					'ledger'       => 'TDS Payable',
					'party_name'   => null,
					'debit_credit' => 'Credit',
					'amount'       => $tdsAmount,
					'tot_amt'      => $tdsAmount,
					'notes'        => 'TDS',
				]);
			}

			// ================= 3. BANK CR =================
			$entries[] = array_merge($common, [
				'ledger'       => 'Bank',
				'party_name'   => null,
				'debit_credit' => 'Credit',
				'amount'       => $netPayable,
				'tot_amt'      => $netPayable,
				'notes'        => 'Payment',
			]);

			// ================= INSERT =================
			Journals::insert($entries);

			DB::commit();
			return true;

		} catch (\Exception $e) {

			DB::rollback();
			\Log::error('Expense Journal Error: ' . $e->getMessage());
			return false;
		}
	}
	
	//Income journal
	public function storeIncomeJournalEntries(array $data)
	{
		DB::beginTransaction();

		try {

			// ================= BASIC =================
			$userId   = $data['added_by'];
			$autoId   = $data['autoId'];
			$propId   = $data['propId'] ?? null;
			$date     = $data['date'];
			$refNo    = $data['reference_no'];
			$source   = $data['source'] ?? 'Income';
			$entryType = $data['entry_type'] ?? 'Income';

			$incomeLedger = $data['ledger']; // e.g. Interest Income
			$party        = $data['party_name'] ?? '';
			$amount       = $data['amount'] ?? 0;
			$tot_amt      = $data['amount'] ?? 0;

			$tdsApplicable = $data['tds_applicable'] ?? 'no';
			$tdsPercent    = $data['tds_percent'] ?? 0;
			$tdsAmount     = $data['tds_amt'] ?? 0;

			// Auto calculate TDS if needed
			if ($tdsApplicable == 'yes' && $tdsAmount == 0 && $tdsPercent > 0) {
				$tdsAmount = ($amount * $tdsPercent) / 100;
			}

			$bankAmount = $amount - $tdsAmount;

			$existing = $this->checkExisting($autoId,$userId,$source);
			$journalNo = $this->getJournalNo($autoId,$userId,$source);
			// ================= DELETE OLD =================
			$rev_amend_status = null;
			if ($existing->count() > 0) {
				$rev_amend_status = (isset($data['status']) && $data['status'] == 1) ? 'amend' : null;
				Journals::where('autoId', $autoId)
					->where('source', $source)
					->delete();
			}

			// ================= COMMON =================
			$common = [
				'journal_no'     => $journalNo,
				'added_by'       => $userId,
				'autoId'         => $autoId,
				'propId'         => $propId,
				'journal_date'   => $date,
				'reference_type' => 'New Ref',
				'reference_no'   => $refNo,
				'entry_type'     => $entryType,
				'source'         => $source,
				'tds_applicable' => $tdsApplicable,
				'tds_percent'    => $tdsPercent,
				'tds_amt'        => $tdsAmount,
				'tds_id'         => $data['tds_id'] ?? null,
				'status'         => 'Posted',
				'rev_amend_status'   => $rev_amend_status,
			];

			$entries = [];

			// ================= 1. BANK DR =================
			$entries[] = array_merge($common, [
				'ledger'       => 'Bank',
				'party_name'   => $party,
				'debit_credit' => 'Debit',
				'amount'       => $bankAmount,
				'tot_amt'      => $bankAmount,
				'notes'        => $data['other_note'] ?? 'Income',
			]);

			// ================= 2. TDS RECEIVABLE DR =================
			if ($tdsApplicable == 'yes' && $tdsAmount > 0) {
				$entries[] = array_merge($common, [
					'ledger'       => 'TDS Receivable',
					'party_name'   => null,
					'debit_credit' => 'Debit',
					'amount'       => $tdsAmount,
					'tot_amt'      => $tdsAmount,
					'notes'        => 'TDS',
				]);
			}

			// ================= 3. INCOME CR =================
			$entries[] = array_merge($common, [
				'ledger'       => $incomeLedger,
				'party_name'   => null,
				'debit_credit' => 'Credit',
				'amount'       => $amount,
				'tot_amt'      => $amount,
				'notes'        => 'Income',
			]);

			// ================= INSERT =================
			Journals::insert($entries);

			DB::commit();
			return true;

		} catch (\Exception $e) {

			DB::rollback();
			\Log::error('Income Journal Error: ' . $e->getMessage());
			return false;
		}
	}
	
	//Assets journal
	public function storeAssetJournalEntries(array $data)
	{
		DB::beginTransaction();

		try {

			$userId   = $data['added_by'];
			$autoId   = $data['autoId'];
			$propId   = $data['propId'] ?? null;
			$date     = $data['date'];
			$source   = $data['source'] ?? 'Asset';
			$entryType = $data['entry_type'] ?? 'Asset';

			$assetName = $data['asset_name'];
			$party     = $data['party_name'];
			$amount    = $data['amount'];
			$dcType = strtolower($data['debit_credit'] ?? 'debit');

			// ================= GST =================
			$gstApplicable = strtolower($data['gst_applicable'] ?? 'no');
			$gstRate  = $data['gst_rate'] ?? 0;
			$gstTrans = strtolower($data['gst_trans'] ?? '');

			$gstAmount = 0;
			$cgst = $sgst = $igst = 0;

			if ($gstApplicable === 'yes' && $gstRate > 0) {
				$gstAmount = ($amount * $gstRate) / 100;
				if ($gstTrans === 'intrastate') {
					$cgst = $gstAmount / 2;
					$sgst = $gstAmount / 2;
				} else {
					$igst = $gstAmount;
				}
			}

			$totalAmount = $amount + $gstAmount;

			$existing = $this->checkExisting($autoId,$userId,$source);
			$journalNo = $this->getJournalNo($autoId,$userId,$source);
			// ================= DELETE OLD =================
			$rev_amend_status = null;
			if ($existing->count() > 0) {
				$rev_amend_status = (isset($data['status']) && $data['status'] == 1) ? 'amend' : null;
				Journals::where('autoId', $autoId)
					->where('source', $source)
					->delete();
			}
			// ================= COMMON =================
			$baseRow = [
				'journal_no'     => $journalNo,
				'added_by'       => $userId,
				'propId'         => $propId,
				'autoId'         => $autoId,
				'journal_date'   => $date,
				'reference_type' => 'New Ref',
				'reference_no'   => null,
				'entry_type'     => $entryType,
				'source'         => $source,
				'party_name'     => null,
				'ledger'         => null,
				'debit_credit'   => null,
				'amount'         => 0,
				'notes'          => null,
				'other_note'     => null,
				'tds_applicable' => 'no',
				'tds_percent'    => 0,
				'tds_amt'        => 0,
				'tds_id'         => null,
				'gst_applicable' => 'no',
				'gst_rate'       => 0,
				'gst_trans'      => null,
				'hsn_sac_code'   => null,
				'status'         => 'Posted',
				'rev_amend_status'   => $rev_amend_status,
			];

			$entries = [];

			// ================= DEBIT CASE =================
			if ($dcType === 'debit') 
			{
				// Asset DR
				$entries[] = array_merge($baseRow, [
					'ledger'         => $assetName,
					'debit_credit'   => 'Debit',
					'amount'         => $amount,
					'tot_amt'         => $amount,
					'notes'          => 'Asset',
				]);

				// GST DR
				if ($cgst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger'       => 'Input CGST',
						'debit_credit' => 'Debit',
						'amount'       => $cgst,
						'tot_amt'       => $cgst,
						'notes'        => 'GST',
					]);
				}

				if ($sgst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger'       => 'Input SGST',
						'debit_credit' => 'Debit',
						'amount'       => $sgst,
						'tot_amt'       => $sgst,
						'notes'        => 'GST',
					]);
				}

				if ($igst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger'       => 'Input IGST',
						'debit_credit' => 'Debit',
						'amount'       => $igst,
						'tot_amt'       => $igst,
						'notes'        => 'GST',
					]);
				}

				// Vendor CR
				$entries[] = array_merge($baseRow, [
					'ledger'       => $party,
					'party_name'   => 'Vendor',
					'debit_credit' => 'Credit',
					'amount'       => $amount,
					'tot_amt'       => $totalAmount,
					'notes'        => 'Payable',
					'gst_applicable' => $gstApplicable,
					'gst_rate'       => $gstRate,
					'gst_trans'      => $gstTrans,
				]);
			}
			else 
			{
				// Vendor DR
				$entries[] = array_merge($baseRow, [
					'ledger'       => $party,
					'party_name'   => 'Vendor',
					'debit_credit' => 'Debit',
					'amount'       => $amount,
					'tot_amt'      => $totalAmount,
					'notes'        => 'Receivable',
					'gst_applicable' => $gstApplicable,
					'gst_rate'       => $gstRate,
					'gst_trans'      => $gstTrans,
				]);

				// Asset CR
				$entries[] = array_merge($baseRow, [
					'ledger'         => $assetName,
					'debit_credit'   => 'Credit',
					'amount'         => $amount,
					'tot_amt'        => $amount,
					'notes'          => 'Asset',
					
				]);

				// GST CR
				if ($cgst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger'       => 'Output CGST',
						'debit_credit' => 'Credit',
						'amount'       => $cgst,
						'tot_amt'       => $cgst,
						'notes'        => 'GST',
					]);
				}

				if ($sgst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger'       => 'Output SGST',
						'debit_credit' => 'Credit',
						'amount'       => $sgst,
						'tot_amt'       => $sgst,
						'notes'        => 'GST',
					]);
				}

				if ($igst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger'       => 'Output IGST',
						'debit_credit' => 'Credit',
						'amount'       => $igst,
						'tot_amt'       => $igst,
						'notes'        => 'GST',
					]);
				}
			}

			Journals::insert($entries);
			DB::commit();
			return true;

		} catch (\Exception $e) {
			DB::rollback();
			\Log::error('Asset Journal Error: ' . $e->getMessage());
			return false;
		}
	}
	
	//Liability journal
	public function storeLiabilityJournalEntries(array $data)
	{
		DB::beginTransaction();

		try {

			$userId   = $data['added_by'];
			$autoId   = $data['autoId'];
			$propId   = $data['propId'] ?? null;
			$date     = $data['date'];
			$source   = $data['source'] ?? 'Liability';
			$entryType= $data['entry_type'] ?? 'Liability';

			$ledgerName = $data['ledger_name'];
			$party      = $data['party_name'];
			$amount     = $data['amount'];

			$dcType = strtolower($data['debit_credit'] ?? 'credit');

			// ================= GST =================
			$gstApplicable = strtolower($data['gst_applicable'] ?? 'no');
			$gstRate  = $data['gst_rate'] ?? 0;
			$gstTrans = strtolower($data['gst_trans'] ?? '');

			$gstAmount = 0;
			$cgst = $sgst = $igst = 0;

			if ($gstApplicable === 'yes' && $gstRate > 0) {
				$gstAmount = ($amount * $gstRate) / 100;
				if ($gstTrans === 'intrastate') {
					$cgst = $gstAmount / 2;
					$sgst = $gstAmount / 2;
				} else {
					$igst = $gstAmount;
				}
			}

			$totalAmount = $amount + $gstAmount;

			// ================= EXISTING =================
			$existing = $this->checkExisting($autoId,$userId,$source);
			$journalNo = $this->getJournalNo($autoId,$userId,$source);

			$rev_amend_status = null;
			if ($existing->count() > 0) {
				$rev_amend_status = (isset($data['status']) && $data['status'] == 1) ? 'amend' : null;
				Journals::where('autoId', $autoId)
					->where('source', $source)
					->delete();
			}

			// ================= COMMON =================
			$baseRow = [
				'journal_no'     => $journalNo,
				'added_by'       => $userId,
				'propId'         => $propId,
				'autoId'         => $autoId,
				'journal_date'   => $date,
				'reference_type' => 'New Ref',
				'reference_no'   => null,
				'entry_type'     => $entryType,
				'source'         => $source,
				'party_name'     => null,
				'ledger'         => null,
				'debit_credit'   => null,
				'amount'         => 0,
				'notes'          => null,
				'other_note'     => null,
				'tds_applicable' => 'no',
				'tds_percent'    => 0,
				'tds_amt'        => 0,
				'tds_id'         => null,
				'gst_applicable' => 'no',
				'gst_rate'       => 0,
				'gst_trans'      => null,
				'hsn_sac_code'   => null,
				'status'         => 'Posted',
				'rev_amend_status'   => $rev_amend_status,
			];

			$entries = [];

			// ================= CREDIT (NORMAL LIABILITY) =================
			if ($dcType === 'credit') 
			{
				// Expense / Asset DR
				$entries[] = array_merge($baseRow, [
					'ledger'       => $ledgerName,
					'debit_credit' => 'Debit',
					'amount'       => $amount,
					'tot_amt'      => $amount,
					'notes'        => 'Liability',
				]);

				// GST DR
				if ($cgst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger' => 'Input CGST',
						'debit_credit' => 'Debit',
						'amount' => $cgst,
						'tot_amt' => $cgst,
						'notes'  => 'GST',
					]);
				}

				if ($sgst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger' => 'Input SGST',
						'debit_credit' => 'Debit',
						'amount' => $sgst,
						'tot_amt' => $sgst,
						'notes'  => 'GST',
					]);
				}

				if ($igst > 0) {
					$entries[] = array_merge($baseRow, [
						'ledger' => 'Input IGST',
						'debit_credit' => 'Debit',
						'amount' => $igst,
						'tot_amt' => $igst,
						'notes'  => 'GST',
					]);
				}

				// Liability CR
				$entries[] = array_merge($baseRow, [
					'ledger'       => $party ?: 'Liability',
					'party_name'   => 'Vendor',
					'debit_credit' => 'Credit',
					'amount'       => $amount,
					'tot_amt'       => $totalAmount,
					'notes'        => 'Payable',
					'gst_applicable' => $gstApplicable,
					'gst_rate'       => $gstRate,
					'gst_trans'      => $gstTrans,
				]);
			}
			else 
			{
				// Liability DR
				$entries[] = array_merge($baseRow, [
					'ledger'       => $party ?: 'Liability',
					'party_name'   => 'Vendor',
					'debit_credit' => 'Debit',
					'amount'       => $totalAmount,
					'tot_amt'       => $totalAmount,
					'notes'        => 'Payment',
				]);

				// Bank CR
				$entries[] = array_merge($baseRow, [
					'ledger'       => 'Bank',
					'debit_credit' => 'Credit',
					'amount'       => $totalAmount,
					'tot_amt'       => $totalAmount,
					'notes'        => 'Payment',
				]);
			}

			Journals::insert($entries);

			DB::commit();
			return true;

		} catch (\Exception $e) {
			DB::rollback();
			\Log::error('Liability Journal Error: ' . $e->getMessage());
			return false;
		}
	}

}
