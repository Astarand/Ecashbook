<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
// use DB;
// use Auth;
use Validator;
use App\User;
use App\Expenses;
use App\Expense_cats;
use App\Expense_cat_options;
use App\Models\Income;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class OtherIncomeController extends Controller
{
	public function __construct(JournalService $journalService, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
		$this->paymentVoucherService = $paymentVoucherService;
    }
	
    public function OtherIncomeList(request $request)
    {
        $userId = currentOwnerId();
		checkCoreAccess('Accounting');

		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = getAccessCompanyId($request);
			
		}


		$incomes = DB::table('income as i')
					->leftJoin('company_profiles as cp', 'cp.userId', '=', 'i.addBy')
					->leftJoin('proprietorship_profiles as pp', 'pp.id', '=', 'i.propId')
					->where('i.addBy', $userId)
					->orderBy('i.id', 'desc')
					->select(
						'i.*',
						DB::raw("
							CASE
								WHEN i.propId IS NOT NULL AND i.propId != ''
								THEN pp.comp_name
								ELSE cp.comp_name
							END as comp_name
						")
					)
					->paginate(10);

		$totalIncome = $incomes->sum('amount');
		//echo "<pre>";print_r($incomes);exit;
		return view('User.other-income-list', compact('incomes', 'totalIncome'));
    }

    public function AddOtherIncome()
    {
        //$this->middleware('auth');
        $userId = currentOwnerId();
		checkCoreAccess('Accounting');
		$compType = DB::table('company_profiles')
						->where('userId', $userId)
						->value('comp_type'); 
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$purposes_of_tds = DB::table('tds_rules')
								->where('module', 'Expenses')
								->where('tds_section', '!=', '192')
								->where(function ($query) use ($compType) {
									if ($compType === 'Proprietorship') {
										// Proprietorship user → All + Proprietorship only
										$query->where('entity', 'All')
											  ->orWhere('entity', 'LIKE', '%Proprietorship%');

									} else {
										// Non-Proprietorship user → All + everything except Proprietorship
										$query->where('entity', 'All')
											  ->orWhere('entity', 'NOT LIKE', '%Proprietorship%');
									}
								})
								->get();
		$customers = DB::table('customers')
					->where('userId', $userId)
					->where('status', 1)
					->select('id', 'cust_name', 'cust_gst_no')
					->get();
			
        return view('User.add-other-income')->with([
            'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'customers' => $customers,
        ]);
    }

    public function store(Request $request)
    {
		//echo"<pre>";print_r($request->all());exit;
        $userId = currentOwnerId();
		$propId = $request->propId;

        try {
			$tdsData = $this->calculateTdsFromRules($request->toArray());
			$gstApplicable = ($request->gst_applicable ?? '') === 'yes' ? 'yes' : 'no';
            $income = new Income();
            $income->addBy = $userId;
            $income->propId = $propId;
            $income->dateInput = $request->input('dateInput');
            $income->incomeType = $request->input('incomeType');
            $income->categoryIncome = $request->input('categoryIncome');
            $income->amount = $request->input('amount');
			$income->receivable_amt = $request->input('receivable_amt');
            $income->advance_amt = $request->input('advance_amt');
            $income->adjust_amt = $request->input('adjust_amt');
			 $income->invoice_no = $request->input('invoice_no');
            $income->pay_status = $request->input('pay_status');
            $income->due_date = $request->input('due_date');
            $income->pay_mode = $request->input('pay_mode');
            $income->customer_name = $request->input('customer_name');
            $income->specification = $request->input('specification');
            $income->tds_applicable = $tdsData['tds_applicable'];
			$income->tds_percentage = $tdsData['tds_percentage'];
			$income->tds_id = $tdsData['tds_id'];
			$income->tds_amount = $tdsData['tds_amount'];
			
			$income->gst_applicable = $gstApplicable;
			$income->gst_rate = ($gstApplicable === 'yes') ? ($request->input('gst_rate') ?? 0.00) : 0.00;
			$income->gst_amt = ($gstApplicable === 'yes') ? ($request->input('gst_amt') ?? 0.00) : 0.00;
			$income->gst_trans = ($gstApplicable === 'yes') ? ($request->input('gst_trans') ?? null) : null;
			$income->gst_allocation = ($gstApplicable === 'yes') ? ($request->input('gst_allocation') ?? null) : null;

            // Save 'other_income' only if 'Other Income' category is selected
            if ($request->input('categoryIncome') == 'Miscellaneous Operating Income' || $request->input('categoryIncome') == 'Miscellaneous Non-Operating Income') {
                $income->other_income = $request->input('other_income');
            } else {
                $income->other_income = null;
            }
			
			//Upload attachment
			if ($request->hasFile('income_doc')) {
				$file = $request->file('income_doc');
				$destinationPath = public_path('uploads/income_docs');
				if (!file_exists($destinationPath)) {
					mkdir($destinationPath, 0755, true);
				}
				$fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
				$file->move($destinationPath, $fileName);
				$income->income_doc = $fileName;
			}

            // Save the income record
            $income->save();
			$lastId = $income->id;
			$this->journalEntry($lastId); // Call journal entry

			//start receipt voucher entry
			$paymentStatus = strtolower($request->pay_status ?? '');
			$incomeAmount  = (float)($request->amount ?? 0);
			$advanceAmount = (float)($request->advance_amt ?? 0);
			$adjustedNow   = (float)($request->adjust_amt ?? 0);
			$currentPayment = 0;
			if ($paymentStatus == 'full') {
				$currentPayment = $adjustedNow;

			} else if ($paymentStatus == 'advance') {
				$currentPayment = $advanceAmount;
			} else {
				$currentPayment = $incomeAmount;
			}
			if ($currentPayment > 0) {
				$this->paymentVoucherService->storePaymentVoucherEntries($lastId,'Income',$currentPayment);
			}
			//end receipt voucher entry

            // Return JSON response for AJAX
            return response()->json([
                'status' => 'success',
                'message' => 'Income details saved successfully!',
                'redirect' => url('/other-income-list')
            ]);
        } catch (\Exception $e) {
            // Log the error (optional)
            \Log::error('Income saving failed: ' . $e->getMessage());

            // Return JSON response for error
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save income details. Please try again.'
            ]);
        }
    }



    public function getViewIncome($id){

        $decodedId = base64_decode($id);
		$userId = currentOwnerId();
        $income = Income::find($decodedId);
        
		$compType = DB::table('company_profiles')
						->where('userId', $userId)
						->value('comp_type'); 
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$purposes_of_tds = DB::table('tds_rules')
								->where('module', 'Expenses')
								->where('tds_section', '!=', '192')
								->where(function ($query) use ($compType) {
									if ($compType === 'Proprietorship') {
										// Proprietorship user → All + Proprietorship only
										$query->where('entity', 'All')
											  ->orWhere('entity', 'LIKE', '%Proprietorship%');

									} else {
										// Non-Proprietorship user → All + everything except Proprietorship
										$query->where('entity', 'All')
											  ->orWhere('entity', 'NOT LIKE', '%Proprietorship%');
									}
								})
								->get();
		$customers = DB::table('customers')
					->where('userId', $userId)
					->where('status', 1)
					->select('id', 'cust_name', 'cust_gst_no')
					->get();
					
        if (!$income) {
            return redirect()->back()->with('error', 'Income record not found.');
        }

        return view('User.view-income', compact('income', 'purposes_of_tds','proprietorships','customers'));

    }
    public function editIncome($id){

        $decodedId = base64_decode($id);
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
        $income = Income::find($decodedId);

        if (!$income) {
            return redirect()->back()->with('error', 'Income record not found.');
        }
        
		$compType = DB::table('company_profiles')
						->where('userId', $userId)
						->value('comp_type'); 
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
		$purposes_of_tds = DB::table('tds_rules')
								->where('module', 'Expenses')
								->where('tds_section', '!=', '192')
								->where(function ($query) use ($compType) {
									if ($compType === 'Proprietorship') {
										// Proprietorship user → All + Proprietorship only
										$query->where('entity', 'All')
											  ->orWhere('entity', 'LIKE', '%Proprietorship%');

									} else {
										// Non-Proprietorship user → All + everything except Proprietorship
										$query->where('entity', 'All')
											  ->orWhere('entity', 'NOT LIKE', '%Proprietorship%');
									}
								})
								->get();
		$customers = DB::table('customers')
					->where('userId', $userId)
					->where('status', 1)
					->select('id', 'cust_name', 'cust_gst_no')
					->get();
        return view('User.edit-income', compact('income','purposes_of_tds','proprietorships','customers'));

    }
    
    public function updateIncome(Request $request, $id)
    {
        // Find the income record by ID
        $income = Income::find($id);
		$propId = $request->propId;
		// OLD VALUES
		$oldIncome = clone $income;
		
        if (!$income) {
            return response()->json([
                'status' => 'error',
                'message' => 'Income record not found.'
            ], 404);
        }

        try {
			$tdsData = $this->calculateTdsFromRules($request->toArray());
			$gstApplicable = ($request->gst_applicable ?? '') === 'yes' ? 'yes' : 'no';
            $income->propId = $request->input('propId');
            $income->dateInput = $request->input('dateInput');
			$income->incomeType = $request->input('incomeType');
            $income->categoryIncome = $request->input('categoryIncome');
            $income->amount = $request->input('amount');
            $income->receivable_amt = $request->input('receivable_amt');
            $income->advance_amt = $request->input('advance_amt');
            $income->adjust_amt = $request->input('adjust_amt');
            $income->invoice_no = $request->input('invoice_no');
            $income->pay_status = $request->input('pay_status');
            $income->pay_mode = $request->input('pay_mode');
            $income->customer_name = $request->input('customer_name');
            $income->specification = $request->input('specification');
            if ($request->input('categoryIncome') == 'Other Income' || $request->input('categoryIncome') == 'Other Operating Income') {
                $income->other_income = $request->input('other_income');
            } else {
                $income->other_income = null;
            }
            $income->tds_applicable = $tdsData['tds_applicable'];
			$income->tds_percentage = $tdsData['tds_percentage'];
			$income->tds_id = $tdsData['tds_id'];
			$income->tds_amount = $tdsData['tds_amount'];
			
						
			$income->gst_applicable = $gstApplicable;
			$income->gst_rate = ($gstApplicable === 'yes') ? ($request->input('gst_rate') ?? 0.00) : 0.00;
			$income->gst_amt = ($gstApplicable === 'yes') ? ($request->input('gst_amt') ?? 0.00) : 0.00;
			$income->gst_trans = ($gstApplicable === 'yes') ? ($request->input('gst_trans') ?? null) : null;
			$income->gst_allocation = ($gstApplicable === 'yes') ? ($request->input('gst_allocation') ?? null) : null;
			
			
			
			//Upload attachment
			if ($request->hasFile('income_doc')) {
				$file = $request->file('income_doc');
				$destinationPath = public_path('uploads/income_docs');
				if (!file_exists($destinationPath)) {
					mkdir($destinationPath, 0755, true);
				}
				//DELETE OLD FILE
				if (!empty($request->old_income_doc)) {
					$oldPath = $destinationPath . '/' . $request->old_income_doc;
					if (file_exists($oldPath)) {
						unlink($oldPath);
					}
				}
				$fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
				$file->move($destinationPath, $fileName);
				$income->income_doc = $fileName;

			} else {
				//KEEP OLD FILE
				$income->income_doc = $request->old_income_doc;
			}

            $income->save();
			$this->journalEntry($id); // Call journal entry
			
			// START PAYMENT VOUCHER ENTRY
			$newAdvance = (float)$income->advance_amt;
			$newAdjust  = (float)$income->adjust_amt;

			$oldAdvance = (float)$oldIncome->advance_amt;
			$oldAdjust  = (float)$oldIncome->adjust_amt;

			$paymentStatus = strtolower(trim($income->pay_status));
			$currentPayment = 0;

			if ($paymentStatus == 'full') {
				$currentPayment = ($newAdjust - $oldAdjust);
			}
			else if ($paymentStatus == 'advance') {
				$currentPayment = ($newAdvance - $oldAdvance);
			}
			
			// If no increase in payment,then do NOT create voucher
			if ($currentPayment <= 0) {
				$currentPayment = 0;
			}

			if ($currentPayment > 0) {
				$this->paymentVoucherService->storePaymentVoucherEntries($income->id,'Income',$currentPayment);
			}
			// END PAYMENT VOUCHER ENTRY

            return response()->json([
                'status' => 'success',
                'message' => 'Income details updated successfully!',
                'redirect' => route('user.OtherIncomeList') // Redirect to income list page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update income details. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
	
	private function calculateTdsFromRules(array $data): array
	{
		$amount = isset($data['amount']) ? (float)$data['amount'] : 0;
		$tdsApplicable = 'no';		
		$tdsPercentage = 0;
		$tdsId = null;
		$tdsAmount = 0;
		//Get all rules
		$rules = DB::table('tds_rules')
					->where('module', 'Expenses')
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
			'tds_percentage' => $tdsPercentage,
			'tds_id' => $tdsId,
			'tds_amount' => round($tdsAmount, 2),
		];
	}
	
	private function calculateTdsFromRules_old(array $data): array
	{
		$expenseAmt = isset($data['amount']) ? (float)$data['amount'] : 0;
		$tdsApplicable = 'no';
		$tdsPercentage = null;
		$tdsId = null;
		$tdsAmount = 0;

		if (!empty($data['tds_percentage']) && $expenseAmt > 0) 
		{
			// Expected: "10-5"
			$parts = explode('-', $data['tds_percentage']);
			$tdsId = $parts[1] ?? null;
			if ($tdsId) {
				//Fetch rule from DB
				$rule = DB::table('tds_rules')
							->where('module', 'Expenses')
							->where('tds_section', '!=', '192')
							->where('id', $tdsId)
							->first();

				if ($rule) {
					$tdsPercentage = (float)$rule->tds_rate;
					$threshold = (float)($rule->threshold_limit ?? 0);
					//Threshold check
					if ($expenseAmt > $threshold) {
						$tdsAmount = ($expenseAmt * $tdsPercentage) / 100;
					}
				}
			}
		}

		return [
			'tds_applicable' => $tdsAmount > 0 ? 'yes' : 'no',
			'tds_percentage' => $tdsPercentage,
			'tds_id' => $tdsId,
			'tds_amount' => round($tdsAmount, 2),
		];
	}

	public function calculateTdsIncome(Request $request)
	{
		$data = [
			'amount' => $request->amount,
			'tds_percentage' => $request->tds_percentage
		];

		$result = $this->calculateTdsFromRules($data);

		return response()->json($result);
	}


    public function deleteIncome($id){
        $decodedId = base64_decode($id);

        $income = Income::find($decodedId);
		$delJournalRec = DB::table('journals')
								->where('autoId', $decodedId)
								->where('source', 'Income')->delete();
		$delPaymentRec = DB::table('payment_vouchers')
							->where('f_id', $decodedId)
							->where('source', 'Income')->delete();

        if (!$income) {
			return response()->json(['message' => 'Income not found'], 404);
		}

		$income->delete();

        return response()->json(['message' => 'Income deleted successfully']);
    }

    public function getIncomeData(Request $request)
    {
        $userId = currentOwnerId();
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$userId = getAccessCompanyId($request);
			
		}

        $incomeType = $request->input('incomeType');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

		$fullPayments = DB::table('sales')
						->where('added_by', $userId)
						->whereBetween('created_at', [$fromDate, $toDate])
						// ->where('pay_status', 'Full')
						->get();

		$totalIncome =0;

		foreach ($fullPayments as $payment) {

				$totalIncome += DB::table('sales_values')
								->where('sid', $payment->id)
								->sum(DB::raw('amount + tax_amt'));

		}

        if ($incomeType == 'gross_income') {

            $incomeType='Gross Income';
            $income = $totalIncome;
        } else {
            $vouchers = DB::table('vouchers')
                            ->where('added_by', $userId)
                            ->where('note_type', 'Credit')
                            ->whereBetween('created_at', [$fromDate, $toDate])
                            ->sum(DB::raw('credit_debit_amount + adjusted_amount'));
                            // ->where('pay_status', 'Full')
                            //->get();


                            // ->where('pay_status', 'Full')
            $total_discound = '0';
			foreach ($fullPayments as $payment) {

				$total_discound += DB::table('sales_values')
								->where('sid', $payment->id)
								->sum('disc_amt');;

			}

            $income = $totalIncome - ($vouchers + $total_discound);
            $incomeType='Net Income';
        }

        return response()->json(['income' => number_format($income, 2, '.', ''), 'incomeType'=> $incomeType]);
    }
	
	public function journalEntry($id)
	{
		$income = DB::table('income')->where('id', $id)->first();
		//$party = ($income->customer_id ?? '');
		//$customerName = $party ? DB::table('customers')->where('id', $party)->value('cust_name') : '';
		$customerName = $income->customer_name ?? '';
		$this->journalService->storeIncomeJournalEntries([
			'source'        => 'Income',
			'autoId'        => $income->id,
			'added_by'      => $income->addBy,
			'propId'        => $income->propId,
			'date'          => $income->dateInput,
			'reference_no'  => $income->invoice_no ?? '',
			'entry_type'    => 'Income',
			'ledger'        => $income->categoryIncome ?? 'Income',
			'party_name'    => $customerName,
			'amount'        => (float)$income->amount,
			'payment_status'=> $income->pay_status ?? '',
			'tds_applicable'=> $income->tds_applicable ?? 'no',
			'tds_percent'   => (float)$income->tds_percentage ?? 0,
			'tds_amt'       => (float)$income->tds_amount ?? 0,
			'tds_id'        => $income->tds_id ?? null,
			'other_note'    => $income->specification ?? $income->other_income,
			'status'        => $income->status,
		]);
		
	}
}
