<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use DB;
// use Auth;
use Validator;
use Carbon\Carbon;
use PDF;
use App\Models\Journals;
use App\Models\JournalAttachments;
use Maatwebsite\Excel\Facades\Excel;


class JournalController extends Controller
{
    
	public function JournalList(Request $request)
	{
		$userId = currentOwnerId();

		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		} else {
			$req_type = 0;
		}

		$query = Journals::where('added_by', $userId);

		// Party Filter
		if ($request->filled('party_name')) {
			$query->where('ledger', $request->party_name);
		}

		// From Date
		if ($request->filled('from_date')) {
			$query->whereDate('journal_date', '>=', $request->from_date);
		}

		// To Date
		if ($request->filled('to_date')) {
			$query->whereDate('journal_date', '<=', $request->to_date);
		}
		// journal_no
		if ($request->filled('journal_no')) {
			$query->where('journal_no', $request->journal_no);
		}
		
		// Ledger
		if ($request->filled('ledger')) {
			$query->where('ledger', $request->ledger);
		}

		// Entry Type
		if ($request->filled('entry_type')) {
			$query->where('entry_type', $request->entry_type);
		}

		// Status
		if ($request->filled('status')) {
			$query->where('status', $request->status);
		}

		/*
		|--------------------------------------------------------------------------
		| Advance Filters
		|--------------------------------------------------------------------------
		*/

		// Party (Customer/Vendor)
		if ($request->filled('party_name')) {
			$query->where('ledger', $request->party_name)
				  ->whereIn('party_name', ['Customer', 'Vendor']);
		}

		// Source
		if ($request->filled('source')) {
			$query->where('source', $request->source);
		}

		// Amend / Reverse
		if ($request->filled('rev_amend_status')) {
			$query->where('rev_amend_status', $request->rev_amend_status);
		}

		$journals = $query
			->orderBy('journal_date', 'desc')
			->paginate(10)
			->appends($request->all());
			
		 $ledgers = Journals::where('added_by', $userId)
			->whereNotNull('ledger')
			->select('ledger')
			->distinct()
			->orderBy('ledger')
			->pluck('ledger');

		$parties = Journals::where('added_by', $userId)
			->whereIn('party_name', ['Customer', 'Vendor'])
			->where('ledger', '!=', '')
			->select('ledger')
			->distinct()
			->pluck('ledger');

		$entryTypes = Journals::where('added_by', $userId)
			->select('entry_type')
			->distinct()
			->orderBy('entry_type')
			->pluck('entry_type');

		$sources = Journals::where('added_by', $userId)
			->select('source')
			->where('source', '!=', '')
			->distinct()
			->orderBy('source')
			->pluck('source');

		foreach ($journals as $journal) {

			$gstRate = $journal->gst_rate ?? 0;
			$amount  = $journal->amount ?? 0;

			$gstAmount = ($amount * $gstRate) / 100;
			$totalAmount = $amount + $gstAmount;

			$journal->gst_amount = $gstAmount;

			if (in_array($journal->entry_type, ['Sales','Sales Credit','Sales Debit', 'Purchase','Purchase Credit','Purchase Debit'])) {
				$journal->total_amount = $amount;
			} else {
				$journal->total_amount = $totalAmount;
			}
		}

		return view(
			'User.Reports.journal-list',
			compact(
				'journals',
				'req_type',
				'ledgers',
				'parties',
				'entryTypes',
				'sources'
			)
		);
	}
	
	public function export(Request $request)
	{
		$userId = currentOwnerId();
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		} 

		$query = Journals::where('added_by', $userId);

		if ($request->filled('party_name')) {
			$query->where('ledger', $request->party_name);
		}

		if ($request->filled('from_date')) {
			$query->whereDate('journal_date', '>=', $request->from_date);
		}

		if ($request->filled('to_date')) {
			$query->whereDate('journal_date', '<=', $request->to_date);
		}

		$journals = $query->orderBy('journal_date')->get();

		$data = [];

		$data[] = [
			'Date',
			'Journal No',
			'Entry Type',
			'Ledger',
			'Dr/Cr',
			'Amount',
			'GST %',
			'TDS Amount',
			'Party',
			'Status',
			'Notes'
		];

		foreach ($journals as $row) {

			$data[] = [
				$row->journal_date,
				'JV-'.$row->journal_no,
				$row->entry_type,
				$row->ledger,
				$row->debit_credit,
				$row->amount,
				$row->gst_rate,
				$row->tds_amt,
				$row->party_name,
				$row->status,
				$row->notes
			];
		}

		return Excel::download(
			new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
				private $data;

				public function __construct($data)
				{
					$this->data = $data;
				}

				public function array(): array
				{
					return $this->data;
				}
			},
			'Journal_Report_'.date('Ymd_His').'.xlsx'
		);
	}
	
	public function AddJournal()
    {
		$userId = currentOwnerId();
		$purposes_of_tds = DB::table('tds_rules')
							->where('tds_section', '!=', '192')
							->get();
		$journalNo = $this->generateJournalNo($userId);
		return view('User.Reports.add-journal')->with([
				'purposes_of_tds' => $purposes_of_tds,
				'journalNo' => $journalNo
		]);
    }
	
	public function editJournal($id)
	{
		$id = base64_decode($id);
		$journal = Journals::with('attachments')->findOrFail($id);
		$purposes_of_tds = DB::table('tds_rules')
							->where('tds_section', '!=', '192')
							->get();
		return view('User.Reports.edit-journal', compact('journal','purposes_of_tds'));
	}
    public function viewJournal($id)
    {
		$id = base64_decode($id);
		$journal = Journals::with('attachments')->findOrFail($id);
		$purposes_of_tds = DB::table('tds_rules')
							->where('tds_section', '!=', '192')
							->get();
		return view('User.Reports.view-journal', compact('journal','purposes_of_tds'));
    }
	
	public function generateJournalNo($userId)
	{
		// Get last journal for this user
		$lastJournal = Journals::where('added_by', $userId)
			->orderBy('id', 'desc')
			->first();

		if ($lastJournal && $lastJournal->journal_no) {

			// Extract number from JV-00001
			$lastNumber = (int) substr($lastJournal->journal_no, -5);
			$nextNumber = $lastNumber + 1;

		} else {
			$nextNumber = 1;
		}

		// Format with leading zeros (00001)
		return str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
	}
	
	private function calculateTdsFromRules(array $data): array
	{
		//echo "<pre>";print_r($data);exit;
		$amount = isset($data['amount']) ? (float)$data['amount'] : 0;

		$tdsPercentage = 0;
		$tdsId = null;
		$tdsAmount = 0;

		// Get all rules
		$rules = DB::table('tds_rules')
					->where('tds_section', '!=', '192')
					->orderBy('threshold_limit', 'asc')
					->get();
		//Apply rule logic
		foreach ($rules as $index => $rule) {

			$threshold = (float)($rule->threshold_limit ?? 0);

			// Apply when amount > threshold 
			if ($amount > $threshold) {

				$tdsPercentage = (float)$rule->tds_rate;
				$tdsId = $rule->id; //get tdsId from rule

				if ($amount > 0 && $tdsPercentage > 0) {
					$tdsAmount = ($amount * $tdsPercentage) / 100;
				}
				break; //stop loop once matched
			}
		}
		return [
			'tds_applicable' => $tdsAmount > 0 ? 'yes' : 'no',
			'tds_percent'    => $tdsPercentage,
			'tds_id'         => $tdsId,
			'tds_amt'        => round($tdsAmount, 2),
		];
	}
	
	// ================= SAVE =================
	public function save(Request $request)
	{
		//Validation
		$request->validate([
			'journal_date'   => 'required|date',
			'entry_type'     => 'required',
			'source'         => 'nullable|string|max:100',
			'ledger'         => 'required',
			'party_name'     => 'nullable|string|max:100',
			'debit_credit'   => 'required',
			'amount'         => 'required|numeric|min:0',

			// New Fields
			'settlement_type' => 'nullable|string|max:100',
			'narration'       => 'nullable|string',
			'against_ledger' => 'nullable|string|max:100',

			'attachments.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
		]);

		$userId = currentOwnerId();
		$propId = null;
		$data = $request->all();
		$gstApplicable = $data['gst_applicable'] ?? 'no';
		DB::beginTransaction();

		try {
			$tdsData = $this->calculateTdsFromRules($data);
			//Create Journal
			$journal = Journals::create([
				'added_by'      => $userId,
				'propId'        => $propId,
				'journal_no'    => $journalNo = $this->generateJournalNo($userId),
				'journal_date'  => $request->journal_date,
				'reference_type'=> $request->reference_type,
				'reference_no'  => $request->reference_no,
				'entry_type'    => $request->entry_type,
				'source'        => $request->source ?? null,
				'ledger'        => $request->ledger,
				'party_name'    => $request->party_name ?? null,
				'debit_credit'  => $request->debit_credit,
				'amount'        => $request->amount,
				'notes'         => $request->notes,
				'other_note'    => $request->other_note,
				'tds_applicable' 	  => $tdsData['tds_applicable'],
				'tds_percent'      	  => $tdsData['tds_percent'],
				'tds_id' 			  => $tdsData['tds_id'],
				'tds_amt' 			  => $tdsData['tds_amt'],
				'gst_applicable'      => $gstApplicable,
				'hsn_sac_code' 		  => ($gstApplicable === 'no') ? null : ($data['hsn_sac_code'] ?? null),
				'gst_rate' 			  => ($gstApplicable === 'no') ? 0.00 : ($data['gst_rate'] ?? 0.00),
				'gst_trans' 		  => ($gstApplicable === 'no') ? null : ($data['gst_trans'] ?? null),

				'settlement_type' => $request->settlement_type ?? null,
    			'narration'       => $request->narration ?? null,
    			'against_ledger' => $request->against_ledger ?? null,
			]);

			// ================= FILE UPLOAD =================
			if ($request->hasFile('attachments')) {

				$uploadPath = public_path('uploads/journal');
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}

				foreach ($request->file('attachments') as $file) {
					$fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
					//Move file
					$file->move($uploadPath, $fileName);
					//Save in DB
					JournalAttachments::create([
						'journals_id' => $journal->id,
						'file_path'  => 'uploads/journal/' . $fileName
					]);
				}
			}

			DB::commit();

			return response()->json([
				'status'  => true,
				'message' => 'Journal saved successfully'
			]);

		} catch (\Exception $e) {

			DB::rollback();

			return response()->json([
				'status'  => false,
				'message' => $e->getMessage()
			], 500);
		}
	}

    // ================= UPDATE =================
	public function update(Request $request, $id)
	{
		$data = $request->all();
		$tdsData = $this->calculateTdsFromRules($data);
		$gstApplicable = $data['gst_applicable'] ?? 'no';
		DB::beginTransaction();

		try {
			$journal = Journals::findOrFail($id);

			$journal->update([
				'journal_date'   => $request->journal_date,
				'reference_type' => $request->reference_type,
				'reference_no'   => $request->reference_no,
				'entry_type'     => $request->entry_type,
				'source'         => $request->source ?? null,
				'ledger'         => $request->ledger,
				'party_name'     => $request->party_name ?? null,
				'debit_credit'   => $request->debit_credit,
				'amount'         => $request->amount,
				'notes'          => $request->notes,
				'other_note'     => $request->other_note,
				'tds_applicable' 	  => $tdsData['tds_applicable'],
				'tds_percent'      	  => $tdsData['tds_percent'],
				'tds_id' 			  => $tdsData['tds_id'],
				'tds_amt' 			  => $tdsData['tds_amt'],
				'gst_applicable'      => $gstApplicable,
				'hsn_sac_code' 		  => ($gstApplicable === 'no') ? null : ($data['hsn_sac_code'] ?? null),
				'gst_rate' 			  => ($gstApplicable === 'no') ? 0.00 : ($data['gst_rate'] ?? 0.00),
				'gst_trans' 		  => ($gstApplicable === 'no') ? null : ($data['gst_trans'] ?? null),

				'settlement_type'  => $request->settlement_type ?? null,
    			'narration'        => $request->narration ?? null,
    			'against_ledger'   => $request->against_ledger ?? null,
			]);

			// Upload new files
			if ($request->hasFile('attachments')) {

				$uploadPath = public_path('uploads/journal');

				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}

				foreach ($request->file('attachments') as $file) {

					$fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
					$file->move($uploadPath, $fileName);

					JournalAttachments::create([
						'journals_id' => $journal->id,
						'file_path'  => 'uploads/journal/'.$fileName
					]);
				}
			}

			DB::commit();

			return response()->json([
				'status' => true,
				'message' => 'Updated successfully'
			]);

		} catch (\Exception $e) {
			DB::rollback();

			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	}
	
	public function deleteFile($id)
	{
		$file = JournalAttachments::findOrFail($id);

		$path = public_path($file->file_path);

		if (file_exists($path)) {
			unlink($path);
		}

		$file->delete();

		return response()->json(['status' => true]);
	}
	
	public function delete($id)
	{
		DB::beginTransaction();

		try {
			$journal = Journals::with('attachments')->findOrFail($id);

			// Delete attachments files
			foreach ($journal->attachments as $file) {

				$path = public_path($file->file_path);

				if (file_exists($path)) {
					unlink($path);
				}

				$file->delete();
			}

			// Delete journal
			$journal->delete();

			DB::commit();

			return response()->json([
				'status' => true,
				'message' => 'Journal deleted successfully'
			]);

		} catch (\Exception $e) {

			DB::rollback();

			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	}
	
	//Reverse journal entry
	public function reverseJournal(Request $req)
	{
		$autoId = $req->autoId;
		$source = $req->source;

		DB::beginTransaction();

		try {

			// ================= UPDATE ORIGINAL TABLE =================
			switch ($source) {

				case 'Sales':
					DB::table('sales')
						->where('id', $autoId)
						->update(['status' => 2]);
					break;

				case 'Purchase':
					DB::table('purchases')
						->where('id', $autoId)
						->update(['status' => 2]);
					break;

				case 'Expense':
					DB::table('expenses')
						->where('id', $autoId)
						->update(['status' => 0]);
					break;

				case 'Income':
					DB::table('income')
						->where('id', $autoId)
						->update(['status' => 0]);
					break;

				case 'Asset':
					DB::table('assets')
						->where('id', $autoId)
						->update(['isActive' => 0]);
					break;

				case 'Liability':
					DB::table('liabilities')
						->where('id', $autoId)
						->update(['status' => 0]);
					break;
			}

			// ================= UPDATE JOURNAL =================
			Journals::where('autoId', $autoId)
				->where('source', $source)
				//->whereNull('rev_amend_status')
				->update([
					'rev_amend_status' => 'reverse'
				]);

			DB::commit();

			return response()->json([
				'success' => true,
				'message' => 'Journal reversed successfully'
			]);

		} catch (\Exception $e) {

			DB::rollback();

			return response()->json([
				'success' => false,
				'message' => 'Error: ' . $e->getMessage()
			]);
		}
	}
	
}
