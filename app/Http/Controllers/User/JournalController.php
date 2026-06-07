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


class JournalController extends Controller
{
    public function JournalList(request $request)
    {
		$userId = currentOwnerId();

		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		
		//end ca-accountant access

		$journals = Journals::where('added_by', $userId)
					->orderBy('id', 'desc') // or 'asc' as per requirement
					->paginate(10);
		// Calculate GST + Total Amount
		foreach ($journals as $journal) {

			$gstRate = $journal->gst_rate ?? 0;
			$amount  = $journal->amount ?? 0;

			$gstAmount = ($amount * $gstRate) / 100;
			$totalAmount = $amount + $gstAmount;

			// Attach dynamic values
			$journal->gst_amount   = $gstAmount;
			$journal->total_amount = $totalAmount;
		}
		//echo "<pre>";print_r($journals);exit;
		return view('User.Reports.journal-list', compact('journals', 'req_type'));
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
			'source'         => 'required',
			'ledger'         => 'required',
			'party_name'     => 'required',
			'debit_credit'   => 'required',
			'amount'         => 'required|numeric|min:0',
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
				'source'        => $request->source,
				'ledger'        => $request->ledger,
				'party_name'    => $request->party_name,
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
				'source'         => $request->source,
				'ledger'         => $request->ledger,
				'party_name'     => $request->party_name,
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
