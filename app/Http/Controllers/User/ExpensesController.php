<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Redirect;
// use DB;
// use Auth;
use Validator;
use App\Models\User;
use App\Models\Expenses;
use App\Models\Expense_cats;
use App\Models\Expense_cat_options;
use App\Models\InventoryExpenses;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AuditLogger;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;

class ExpensesController extends Controller
{
    
	public function __construct(JournalService $journalService, PaymentVoucherService $paymentVoucherService = null)
    {
        $this->journalService = $journalService;
		$this->paymentVoucherService = $paymentVoucherService;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ExpensesList(request $request)
    {
        $title = 'Expenses';
        $userId = currentOwnerId();
		checkCoreAccess('Accounting');
        $userType = Auth::user()->u_type;

		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}

        if ($userType == 1 || $userType == 4) { // CA
            // $expenses = DB::table('expenses')
            //     ->select('expenses.*', 'company_profiles.comp_name', 'ca_assigns.ca_id')
            //     ->leftJoin('company_profiles', 'expenses.added_by', '=', 'company_profiles.userId')
            //     ->leftJoin('ca_assigns', 'expenses.added_by', '=', 'ca_assigns.comp_id')
            //     ->where('ca_assigns.ca_id', '=', $userId)
            //     ->where('ca_assigns.ca_assign_status', '=', 1)
            //     ->orderBy('expenses.id', 'DESC')
            //     ->paginate(10);
			$expenses = DB::table('expenses as e')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'e.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'e.added_by')
						->where('e.added_by', $userId)
						->orderBy('e.id', 'DESC')
						->select(
							'e.*',
							DB::raw("
								CASE
									WHEN e.propId IS NOT NULL AND e.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->paginate(10);

        } elseif ($userType == 2 || $userType == 5) { // User
			$expenses = DB::table('expenses as e')
						->leftJoin('company_profiles as cp', 'cp.userId', '=', 'e.added_by')
						->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'e.added_by')
						->where('e.added_by', $userId)
						->orderBy('e.id', 'DESC')
						->select(
							'e.*',
							DB::raw("
								CASE
									WHEN e.propId IS NOT NULL AND e.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->paginate(10);

        } elseif ($userType == 3) { // Admin
            $expenses = DB::table('expenses')
                ->select('expenses.*', 'company_profiles.comp_name')
                ->leftJoin('company_profiles', 'expenses.added_by', '=', 'company_profiles.userId')
                ->orderBy('expenses.id', 'DESC')
                ->paginate(10);
        }

        // Store pagination
        $expenses_pagination = $expenses;
		// echo "<pre>";
		// print_r($expenses);
		// die();

        // Convert expenses collection to structured array
        $array = [];
        foreach ($expenses as $val) {
            $array[$val->id] = [
                'id' => $val->id,
                'comp_name' => $val->comp_name,
                'expense_date' => $val->expense_date,
                'exp_invno' => $val->exp_invno,

                'pur_of_expense' => $val->pur_of_expense,
                'mode_of_expense' => $val->mode_of_expense,
                'expense_cat' => $val->expense_cat,
                'expense_type' => $val->expense_type,
                'other_expenses_details' => $val->other_expenses_details,
                'payment_status' => $val->payment_status,
                'expense_amt' => $val->expense_amt,
                'tds_amount' => $val->tds_amount,
                'deduction_amount' => $val->deduction_amount,
                'approved_by' => $val->approved_by,
                'status' => $val->status,
            ];
        }

        // Convert array to object
        $expenses = json_decode(json_encode($array));

        // Return view with data
        return view('User.expenses-list')->with([
            'title' => $title,
            'expenses' => $expenses,
            // 'monthWiseExpenses' => $monthWiseExpenses,
            'expenses_pagination' => $expenses_pagination,
        ]);
    }


	public function getExpenseOptions(Request $request)
    {

		$id = $request->id;
		$response = [];
		if($id !="")
		{
			$expCat = Expense_cats::query()
					->where('cat_name', '=', $id)
					->get()->toArray();
			$result = Expense_cat_options::query()
					->where('cat_id', '=', $expCat[0]['id'])
					->get()->toArray();

			//echo "<pre>";print_r($result);exit;
			 foreach($result as $row){
			   $response[] = array("id"=>$row['opt_val'], "name"=>$row['opt_val']);
			}
		}
		echo json_encode($response);
    }

    public function AddExpenses()
    {
        //$this->middleware('auth');
		//$purposes_of_tds = DB::table('purposes_of_tds')->get();
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
		// ✅ Fetch Vendors
		$vendors = DB::table('vendors')
					->select('id', 'vendor_name', 'vendor_pan')
					->where('userId', $userId)
					->where('status', 1)
					->get();
		
		//echo "<pre>";print_r($purposes_of_tds);exit;
        return view('User.add-expenses')->with([
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'vendors' => $vendors
        ]);
    }

		//Start for purchase invoice
	protected function validator(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				// 'expense_date' => 'required',
				// 'pur_of_expense' => 'required',
				// 'mode_of_expense' => 'required',
				'expense_cat' => 'required',
				'expense_amt' => 'required',
				//'expense_msg' => 'required',

				// 'approved_by' => 'required',
				// 'designation' => 'required',
				// 'approved_date' => 'required',
				'employee_id' => [
					'nullable',
					function ($attribute, $value, $fail) use ($data) {
						if (
							isset($data['expense_type']) &&
							$data['expense_type'] === 'employee_benefits' &&
							empty($value)
						) {
							$fail('Employee is required for Employee Benefits.');
						}
					}
				]
			]);

    }

    protected function create(array $data)
    {
		// echo "<pre>";print_r($data);exit;
		
		//get TDS data
		$tdsData = $this->calculateTdsFromRules($data);
		$propId = $data['propId'];
        return Expenses::create([
            'added_by' => currentOwnerId(),
			'propId' => $propId,
            'expense_date' => $data['expense_date'],
			'pur_of_expense' => $data['pur_of_expense'] ?? null,
			'mode_of_expense' => $data['mode_of_expense'],
			'expense_cat' => $data['expense_cat'],
			'expense_type' => isset($data['expense_type'])?$data['expense_type']:"",
			'expense_amt' => $data['expense_amt'],
			'other_expenses_details' => $data['other'] ?? null,
			//'expense_msg' => $data['expense_msg'],
			'exp_invno' => $data['exp_invno'] ?? null,
			'approved_by' => $data['approved_by'] ?? null,
			'designation' => $data['designation'] ?? null,
			'approved_date' => $data['approved_date'] ?? null,
			'spec_note' => isset($data['spec_note'])?$data['spec_note']:"",
			'employee_id' => isset($data['employee_id']) ? $data['employee_id'] : null,
			'employee_code' => isset($data['employee_code']) ? $data['employee_code'] : null,
			'created_at' => date('Y-m-d H:i:s'),

			// ✅ TDS Fields
			'tds_applicable' => $data['tds_applicable'] ?? 'no',
			'tds_percentage' => $data['tds_percentage'] ?? null,
			'tds_id' => $data['tds_id'] ?? null,
			'tds_amount' => $data['tds_amount'] ?? 0,
			'tds_section' => $data['tds_section'] ?? null,
			'tds_rate' => $data['tds_rate'] ?? null,
			'tds_threshold_limit' => $data['tds_threshold_limit'] ?? null,

			// ✅ GST Fields
			'gst_applicable' => $data['gst_applicable'] ?? 'no',
			'gst_trans' => $data['gst_trans'] ?? null,
			'gst_rate' => $data['gst_rate'] ?? 0,
			'gst_allocation' => $data['gst_allocation'] ?? null,
			'total_gst' => $data['total_gst'] ?? 0,

			// ✅ Vendor
			'vendor_id' => isset($data['vendor_id']) ? $data['vendor_id'] : null,
			'vendor_pan' => isset($data['vendor_pan']) ? strtoupper($data['vendor_pan']) : null,
			'payment_status' => isset($data['payment_status']) ? $data['payment_status'] : null,

			'advance_amount' => isset($data['advance_amount']) ? $data['advance_amount'] : null,
			'balance_amount' => isset($data['balance_amount']) ? $data['balance_amount'] : null,
			'adjusted_now' => isset($data['adjusted_now']) ? $data['adjusted_now'] : null,

			// ✅ Depreciation Fields
			// 'dep_start_date' => $data['dep_start_date'] ?? null,
			// 'dep_frequency'  => $data['dep_frequency'] ?? null,
			// 'useful_life'    => $data['useful_life'] ?? null,
			// 'dep_method'     => $data['dep_method'] ?? null,
			'dep_value'      => $data['dep_value'] ?? null,
			// 'residual_value' => $data['residual_value'] ?? null,
			
        ]);
    }

	public function journalEntry($eId)
	{
		$expense = DB::table('expenses')->where('id', $eId)->first();
		$this->journalService->storeExpenseJournalEntries([
			'source'        => 'Expense',
			'autoId'        => $expense->id,
			'added_by'      => currentOwnerId(),
			'propId'        => $expense->propId,
			'date'          => $expense->expense_date,
			'reference_no'  => $expense->exp_invno,
			'entry_type'    => 'Expense',
			'ledger'        => ucwords(str_replace(['_', '-'], ' ', $expense->expense_type)),
			'party_name'    => $expense->approved_by,
			'amount'        => $expense->expense_amt,
			'payment_status'=> $expense->payment_status,
			'tds_applicable'=> $expense->tds_applicable ?? 'no',
			'tds_percent'   => $expense->tds_percentage ?? 0,
			'tds_amt'       => $expense->tds_amount ?? 0,
			'tds_id'        => $expense->tds_id ?? null,
			'other_note'    => $expense->pur_of_expense,
			'status'    	=> $expense->status,
		]);
	}
	
	// public function getEmployees()
	// {
	// 	$employees = DB::table('employees as e')
	// 		->join('users as u', 'e.empId', '=', 'u.id')
	// 		->where('e.added_by', currentOwnerId())
	// 		->select(
	// 			'e.empId as id',
	// 			'u.name as name'
	// 		)
	// 		->get();

	// 	return response()->json($employees);
	// }
	public function getEmployees()
	{
		$employees = DB::table('employees as e')
			->join('users as u', 'e.empId', '=', 'u.id')
			->where('e.added_by', currentOwnerId())
			->select(
				'e.empId as id',
				'u.name as name',
				'e.employee_id as employee_code' // 👈 ADD THIS
			)
			->get();

		return response()->json($employees);
	}

	public function save_expenses(Request $request)  {

		$validation = $this->validator($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start payment logic
			$expenseAmount   = (float) ($request->expense_amt ?? 0);
			$totalGst        = (float) ($request->total_gst ?? 0);
			$grandTotal      = $expenseAmount;
			$paymentStatus   = strtolower($request->payment_status ?? '');
			$advanceAmount   = 0;
			$adjustedNow     = 0;
			if ($paymentStatus == 'full') {
				$adjustedNow  = $grandTotal;
				$advanceAmount = 0;
			}
			else if ($paymentStatus == 'advance') {
				$advanceAmount = (float) ($request->advance_amount ?? 0);
				$adjustedNow = 0;
			}			
			//end payment logic
		
			$insertExpenses = $this->create($request->all());
			$eId = DB::getPdo()->lastInsertId();
			$this->journalEntry($eId); //Journal Entry
			
			//start entry for voucher payment
			$currentPayment = 0;
			if ($paymentStatus == 'full') {
				$currentPayment = $adjustedNow;
			}else if ($paymentStatus == 'advance') {
				$currentPayment = $advanceAmount;
			}else{
				$currentPayment = $expenseAmount;
			}
			if ($currentPayment > 0) {
				$this->paymentVoucherService->storePaymentVoucherEntries($eId, 'Expense', $currentPayment);
			}
			//end entry for voucher payment

            if ($request->hasFile('exp_inv_doc')) {
                $file = $request->file('exp_inv_doc');
                $fileName2 = date("YmdHis") . '-' . $file->getClientOriginalName();

                // Store file in storage/app/public/expense-invoice
                $filePath = $file->storeAs('public/expense-invoice', $fileName2);

                // Save only the relative path for retrieval
                $exp_inv_doc = str_replace('public/', '', $filePath);

                // Update the database
                $update = DB::table('expenses')
                    ->where('id', $eId)
                    ->update([
                        'exp_inv_doc' => $exp_inv_doc,
                    ]);
            }


			if ($insertExpenses){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/expenses-list'),
					'message' => 'Expenses added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Expenses add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function EditExpenses($eId)  {

		if(Auth::user()->u_type ==1){
			return redirect('/expenses');
		}
		$eId = base64_decode($eId);
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		$expenses = DB::table('expenses')
								->where('id', '=', $eId)
								->get();
		$expenses = $expenses[0];

		//$purposes_of_tds = DB::table('purposes_of_tds')->get();
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
								
		// ✅ Fetch Vendors
		$vendors = DB::table('vendors')
					->select('id', 'vendor_name', 'vendor_pan')
					->where('userId', $userId)
					->where('status', 1)
					->get();

		// echo "<pre>";print_r($expenses);exit;

		return view('User.edit-expenses')->with([
			'expenses' => $expenses,
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'vendors' => $vendors,
			// 'expenseCatOpt' => $expenseCatOpt,
			'eId' => $eId
		]);
    }

	

	public function ViewExpenses($eId)  {

		$eId = base64_decode($eId);
		$userId = currentOwnerId();
		checkCoreAccess('Accounting');
		$expenses = DB::table('expenses')
								->where('id', '=', $eId)
								->get();
		$expenses = $expenses[0];
		//$purposes_of_tds = DB::table('purposes_of_tds')->get();
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
		$vendors = DB::table('vendors')
					->select('id', 'vendor_name', 'vendor_pan')
					->where('userId', $userId)
					->where('status', 1)
					->get();


		return view('User.view-expenses')->with([
			'expenses' => $expenses,
			'purposes_of_tds' => $purposes_of_tds,
			'proprietorships' => $proprietorships,
			'vendors' => $vendors,
			// 'expenseCatOpt' => $expenseCatOpt,
			'eId' => $eId
		]);
    }

	

	public function update_expenses(Request $request)
	{

		// echo "<pre>";print_r($request->all());
		// $tdsData = $this->calculateTdsFromRules($request->all());
		// echo "<pre>";print_r($tdsData);
		// die();

		$eId = $request->id;

		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		} else {

			// Get old record
			$oldRec = DB::table('expenses')
				->where('id', $eId)
				->first();

			/*
			|--------------------------------------------------------------------------
			| Payment Logic
			|--------------------------------------------------------------------------
			*/

			$expenseAmount = (float) ($request->expense_amt ?? 0);
			$totalGst      = (float) ($request->total_gst ?? 0);

			// Grand Total = Expense + GST
			$grandTotal = $expenseAmount;

			$paymentStatus = strtolower($request->payment_status ?? '');

			$newAdvanceInput = (float) ($request->advance_amount ?? 0);

			$currentPayment = 0;
			$advanceAmount  = 0;
			$balanceAmount  = 0;
			$adjustedNow    = 0;

			// Advance Payment
			if ($paymentStatus == 'advance') {

				$advanceAmount = $newAdvanceInput;

				// Prevent overpayment
				if ($advanceAmount > $grandTotal) {
					$advanceAmount = $grandTotal;
				}

				$balanceAmount = $grandTotal - $advanceAmount;

				$adjustedNow = 0;

				$currentPayment = $advanceAmount;
			}

			// Full Payment
			else if ($paymentStatus == 'full') {

				$advanceAmount = 0.00;

				$balanceAmount = 0.00;

				// Only expense amount
				$adjustedNow = $expenseAmount;

				$currentPayment = $grandTotal;
			}

			// Default
			else {

				$currentPayment = $grandTotal;

				$advanceAmount = 0.00;

				$balanceAmount = 0.00;

				$adjustedNow = 0.00;
			}

			//--------------------------------------
			// Payment History Logic
			//--------------------------------------

			$oldHistory = [];

			if (!empty($oldRec->payment_history)) {

				$decodedHistory = json_decode($oldRec->payment_history, true);

				if (is_array($decodedHistory)) {
					$oldHistory = $decodedHistory;
				}
			}

			$paymentEntry = [
				'payment_date'   => now()->format('Y-m-d H:i:s'),
				'payment_status' => $paymentStatus,
				'expense_amount' => $expenseAmount,
				'gst_amount'     => $totalGst,
				'grand_total'    => $grandTotal,
				'paid_amount'    => $currentPayment,
				'advance_amount' => $advanceAmount,
				'balance_amount' => $balanceAmount,
				'adjusted_now'   => $adjustedNow,
				'old_advance'    => (float) ($oldRec->advance_amount ?? 0),
				'remarks'        => ($paymentStatus == 'advance')
					? 'Advance payment updated'
					: (($paymentStatus == 'full')
						? 'Full payment completed'
						: 'Expense updated')
			];

			// Add new history entry
			$oldHistory[] = $paymentEntry;
			$paymentHistoryJson = json_encode($oldHistory);

			/*
			|--------------------------------------------------------------------------
			| Upload Invoice File
			|--------------------------------------------------------------------------
			*/

			if ($request->hasFile('exp_inv_doc')) {

				$file = $request->file('exp_inv_doc');

				$fileName2 = date("YmdHis") . '-' . $file->getClientOriginalName();

				// Store file
				$filePath = $file->storeAs('public/expense-invoice', $fileName2);

				// Save relative path
				$exp_inv_doc = str_replace('public/', '', $filePath);

				DB::table('expenses')
					->where('id', $eId)
					->update([
						'exp_inv_doc' => $exp_inv_doc,
					]);
			}

			/*
			|--------------------------------------------------------------------------
			| TDS Calculation
			|--------------------------------------------------------------------------
			*/

			// $tdsData = $this->calculateTdsFromRules($request->all());

			/*
			|--------------------------------------------------------------------------
			| Update Expense
			|--------------------------------------------------------------------------
			*/

			$update = DB::table('expenses')
				->where('id', $eId)
				->update([

					'propId'           => $request->propId,
					'expense_date'     => $request->expense_date,
					'pur_of_expense'   => $request->pur_of_expense,
					'mode_of_expense'  => $request->mode_of_expense,
					'expense_cat'      => $request->expense_cat,
					'expense_type'     => $request->expense_type,
					'expense_amt'      => $request->expense_amt,

					/*
					|--------------------------------------------------------------------------
					| Payment Fields
					|--------------------------------------------------------------------------
					*/

					'payment_status' => $request->payment_status ?? null,
					'advance_amount' => $advanceAmount,
					'balance_amount' => $balanceAmount,
					'adjusted_now'   => $adjustedNow,
					'payment_history' => $paymentHistoryJson,

					/*
					|--------------------------------------------------------------------------
					| Other Fields
					|--------------------------------------------------------------------------
					*/

					'other_expenses_details' => $request->other,

					'exp_invno' => $request->exp_invno,

					'employee_id' => ($request->expense_type == 'employee_benefits')
						? $request->employee_id
						: null,

					'employee_code' => $request->employee_code ?? null,

					'approved_by'   => $request->approved_by,
					'designation'   => $request->designation,
					'approved_date' => $request->approved_date,

					'spec_note' => isset($request->spec_note)
						? $request->spec_note
						: "",

					/*
					|--------------------------------------------------------------------------
					| TDS Fields
					|--------------------------------------------------------------------------
					*/

					// 'tds_applicable'      => $tdsData['tds_applicable'],
					// 'tds_percentage'      => $tdsData['tds_percentage'],
					// 'tds_id'              => $tdsData['tds_id'],
					// 'tds_amount'          => $tdsData['tds_amount'],
					// 'tds_section'         => $tdsData['tds_section'] ?? null,
					// 'tds_rate'            => $tdsData['tds_rate'] ?? null,
					// 'tds_threshold_limit' => $tdsData['tds_threshold_limit'] ?? null,

					'tds_applicable'      => $request->tds_applicable ?? 'no',
					'tds_percentage'      => $request->tds_percentage ?? null,
					'tds_id'              => $request->tds_id ?? null,
					'tds_amount'          => $request->tds_amount ?? null,
					'tds_section'         => $request->tds_section ?? null,
					'tds_rate'            => $request->tds_rate ?? null,
					'tds_threshold_limit' => $request->tds_threshold_limit ?? null,

					/*
					|--------------------------------------------------------------------------
					| GST Fields
					|--------------------------------------------------------------------------
					*/

					'gst_applicable' => $request->gst_applicable ?? 'no',

					'gst_trans' => ($request->gst_applicable == 'no')
						? null
						: ($request->gst_trans ?? null),

					'gst_rate' => ($request->gst_applicable == 'no')
						? 0
						: ($request->gst_rate ?? 0),

					'gst_allocation' => ($request->gst_applicable == 'no')
						? null
						: ($request->gst_allocation ?? null),

					'total_gst' => ($request->gst_applicable == 'no')
						? 0
						: ($request->total_gst ?? 0),

					/*
					|--------------------------------------------------------------------------
					| Vendor Fields
					|--------------------------------------------------------------------------
					*/

					'vendor_id' => $request->vendor_id ?? null,

					'vendor_pan' => isset($request->vendor_pan)
						? strtoupper($request->vendor_pan)
						: null,

					/*
					|--------------------------------------------------------------------------
					| Depreciation
					|--------------------------------------------------------------------------
					*/

					'dep_value' => $request->dep_value ?? null,
				]);

			/*
			|--------------------------------------------------------------------------
			| Journal Entry
			|--------------------------------------------------------------------------
			*/

			$this->journalEntry($eId);

			//start payment voucher entry
			$oldAdvance = (float)($oldRec->advance_amount ?? 0);
			$oldAdjust  = (float)($oldRec->adjusted_now ?? 0);
			$newAdvance = (float)$advanceAmount;
			$newAdjust  = (float)$adjustedNow;

			$currentPayment = 0;
			if ($paymentStatus == 'advance') {
				$currentPayment = $newAdvance - $oldAdvance;
			}
			else if ($paymentStatus == 'full') {
				$currentPayment = $grandTotal - $oldAdjust;
			}else{
				$currentPayment = 0;
			}

			// If no increase in payment,then do NOT create voucher
			if ($currentPayment <= 0) {
				$currentPayment = 0;
			}
			if ($currentPayment > 0) {
				$this->paymentVoucherService->storePaymentVoucherEntries($eId,'Expense',$currentPayment);
			}
			//end payment voucher entry
			
			/*
			|--------------------------------------------------------------------------
			| Response
			|--------------------------------------------------------------------------
			*/

			$msg = array(
				'status'   => 'success',
				'class'    => 'succ',
				'redirect' => url('/expenses-list'),
				'message'  => 'Record updated successfully'
			);

			return response()->json($msg);
		}
	}
	
	private function calculateTdsFromRules(array $data): array
	{
		$expenseAmt = isset($data['expense_amt']) ? (float)$data['expense_amt'] : 0;
		$tdsApplicable = 'no';
		$tdsPercentage = null;
		$tdsId = null;
		$tdsAmount = 0;

		if (!empty($data['tds_rate']) && $expenseAmt > 0) 
		{
			// Expected: "10-5"
			$parts = explode('-', $data['tds_rate']);
			$tdsId = $parts[1] ?? null;
			if ($tdsId) {
				//Fetch rule from DB
				$rule = DB::table('tds_rules')
							->where('module', 'Expenses')
							// ->where('tds_section', '!=', '192')
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



	public function delExpenses(Request $request)
    {
		// Fetch old data BEFORE delete
		$expense = DB::table('expenses')->where('id', $request->id)->first();
		$oldData = [
			'Expense Category'   => $expense->expense_cat ?? '',
			'Expense Type'   => $expense->expense_type ?? '',
			'Amount'         => $expense->expense_amt ?? '',
			'Date'           => $expense->expense_date ?? ''
		];

        $delExpenses = DB::table('expenses')->where('id', $request->id)->delete();
		$delJournalRec = DB::table('journals')
								->where('autoId', $request->id)
								->where('source', 'Expense')->delete();
		$delPaymentRec = DB::table('payment_vouchers')
							->where('f_id', $request->id)
							->where('source', 'Expense')->delete();
		if($delExpenses)
		{
			// Capture log entry
			AuditLogger::logEntry(
				action: 'deleted',
				module: 'Expenses',
				description: 'Expense deleted: ' . ($expense->expense_cat ?? 'N/A'),
				oldData: $oldData,
				newData: null
			);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				// 'redirect' => url('/expenses'),
				'message' => 'Record deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				// 'redirect' => url('/expenses'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

    public function viewmonthexpenses($monthWise,$added_by)
    {
		$monthWise = base64_decode($monthWise);
		$added_by = base64_decode($added_by);
		$monthWise = explode("-",$monthWise);
		$year = $monthWise[0];
		$month = $monthWise[1];

		$title = 'Monthly Expenses';
		$userId = currentOwnerId();
		if(Auth::user()->u_type ==1){ //ca
			$expenses =  DB::table('expenses')
							->select(DB::raw('expenses.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'expenses.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'expenses.added_by', '=', 'ca_assigns.comp_id')
							->where('ca_assigns.ca_id','=',$userId)
							->where('ca_assigns.ca_assign_status','=',1)
							->where('expenses.added_by','=',$added_by)
							->whereYear('expense_date', '=', $year)
							->whereMonth('expense_date', '=', $month)
							->orderBy('id', 'DESC')->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$expenses =  DB::table('expenses')
							->select(DB::raw('expenses.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'expenses.added_by', '=', 'company_profiles.userId')
							->where('expenses.added_by','=',$added_by)
							->whereYear('expense_date', '=', $year)
							->whereMonth('expense_date', '=', $month)
							->orderBy('expenses.id', 'DESC')->paginate(10);
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$expenses =  DB::table('expenses')
							->select(DB::raw('expenses.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'expenses.added_by', '=', 'company_profiles.userId')
							->where('expenses.added_by','=',$added_by)
							->whereYear('expense_date', '=', $year)
							->whereMonth('expense_date', '=', $month)
							->orderBy('id', 'DESC')->paginate(10);
		}
		$expenses_pagination = $expenses;

		$array = array();
		foreach($expenses as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['expense_date'] = $val->expense_date;
			$array[$val->id]['pur_of_expense'] = $val->pur_of_expense;
			$array[$val->id]['mode_of_expense'] = $val->mode_of_expense;
			$array[$val->id]['expense_cat'] = $val->expense_cat;
			$array[$val->id]['expense_type'] = $val->expense_type;
			$array[$val->id]['expense_amt'] = $val->expense_amt;
			$array[$val->id]['expense_msg'] = $val->expense_msg;
			$array[$val->id]['status'] = $val->status;

		}
		$expenses = json_decode(json_encode($array));


		//echo "<pre>"; print_r($monthWiseExpenses);exit;
		//echo "<pre>"; print_r($expenses);exit;
		return view('pages.viewmonthexpenses')->with([
			'title' =>$title,
			'expenses'=>$expenses,
			'expenses_pagination' =>$expenses_pagination,
		]);
    }
	
	// STORE Inventory expenses
    public function addInventoryExpenses(Request $request)
	{
		$uid = Auth::id();

		$request->validate([
			'expense_types' => 'required',
			'expense_date' => 'required|date',
			'expense_amount' => 'required|numeric',
			'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png'
		]);

		// ================= FILE UPLOAD =================
		$filePath = null;
		$folder = public_path('uploads/supporting_document');

		// Create folder if not exists
		if (!file_exists($folder)) {
			mkdir($folder, 0777, true);
		}

		if ($request->hasFile('supporting_document')) {
			$file = $request->file('supporting_document');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$file->move($folder, $fileName);

			$filePath = 'uploads/supporting_document/' . $fileName;
		}

		// ================= INSERT DATA =================
		$tdsData = $this->calculateTdsForInvExp($request->toArray());
		$gstApplicable = ($request->gst_applicable ?? '') === 'yes' ? 'yes' : 'no';
		InventoryExpenses::create([
			'uid' => $uid,
			'propId' => $request->propId,
			'expense_types' => $request->expense_types,
			'expense_voucher_no' => $request->expense_voucher_no,
			'expense_date' => $request->expense_date,
			'purchase_invoice_ref_no' => $request->purchase_invoice_ref_no,
			'supplier_name' => $request->supplier_name,
			'supplier_gstin' => $request->supplier_gstin,
			'expense_amount' => $request->expense_amount,
			'stock_location' => $request->stock_location,
			'allocation_basis' => $request->allocation_basis,
			'allocated_units' => $request->allocated_units,
			'cost_allocation_amount' => $request->cost_allocation_amount,
			'remarks' => $request->remarks,
			
			'tds_applicable' => $tdsData['tds_applicable'],
			'tds_percentage' => $tdsData['tds_percentage'],
			'tds_id' => $tdsData['tds_id'],
			'tds_amount' => $tdsData['tds_amount'],						
			'gst_applicable' => $gstApplicable,
			'gst_rate' => ($gstApplicable === 'yes') ? ($request->input('gst_rate') ?? 0.00) : 0.00,
			'gst_amt' => ($gstApplicable === 'yes') ? ($request->input('gst_amt') ?? 0.00) : 0.00,
			'gst_trans' => ($gstApplicable === 'yes') ? ($request->input('gst_trans') ?? null) : null,
			'gst_allocation' => ($gstApplicable === 'yes') ? ($request->input('gst_allocation') ?? null) : null,
			'itc_applicable' => $request->itc_applicable ?? 'no',

			'supporting_document' => $filePath
		]);

		// ================= RESPONSE =================
		return response()->json([
			'class' => 'succ',
			'message' => 'Expense added successfully',
			'redirect' => url('/expenses_inventorylist')
		]);
	}

	// EDIT
	public function editInventoryExpenses($id)
	{
		$userId = currentOwnerId();
		$expense = InventoryExpenses::findOrFail($id);
		
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
		return view('User.edit-inventory-expenses', compact('expense','proprietorships','purposes_of_tds'));
	}
	
	

	// UPDATE
	public function updateInventoryExpenses(Request $request)
	{
		$expense = InventoryExpenses::findOrFail($request->id);

		// ================= VALIDATION =================
		$request->validate([
			'expense_types' => 'required',
			'expense_date' => 'required|date',
			'expense_amount' => 'required|numeric',
			'supporting_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png'
		]);

		// ================= FILE UPLOAD =================
		$filePath = $expense->supporting_document;
		$folder = public_path('uploads/supporting_document');

		// Create folder if not exists
		if (!file_exists($folder)) {
			mkdir($folder, 0777, true);
		}

		if ($request->hasFile('supporting_document')) {

			// Delete old file
			if (!empty($expense->supporting_document)) {
				$oldFile = public_path($expense->supporting_document);
				if (file_exists($oldFile)) {
					unlink($oldFile);
				}
			}

			// Upload new file
			$file = $request->file('supporting_document');
			$fileName = time() . '_' . $file->getClientOriginalName();
			$file->move($folder, $fileName);

			$filePath = 'uploads/supporting_document/' . $fileName;
		}

		// ================= UPDATE DATA =================
		$tdsData = $this->calculateTdsForInvExp($request->toArray());
		$gstApplicable = ($request->gst_applicable ?? '') === 'yes' ? 'yes' : 'no';
		
		$expense->update([
			'propId' => $request->propId,
			'expense_types' => $request->expense_types,
			'expense_voucher_no' => $request->expense_voucher_no,
			'expense_date' => $request->expense_date,
			'purchase_invoice_ref_no' => $request->purchase_invoice_ref_no,
			'supplier_name' => $request->supplier_name,
			'supplier_gstin' => $request->supplier_gstin,
			'expense_amount' => $request->expense_amount,
			'stock_location' => $request->stock_location,
			'allocation_basis' => $request->allocation_basis,
			'allocated_units' => $request->allocated_units,
			'cost_allocation_amount' => $request->cost_allocation_amount,
			'remarks' => $request->remarks,

			'tds_applicable' => $tdsData['tds_applicable'],
			'tds_percentage' => $tdsData['tds_percentage'],
			'tds_id' => $tdsData['tds_id'],
			'tds_amount' => $tdsData['tds_amount'],						
			'gst_applicable' => $gstApplicable,
			'gst_rate' => ($gstApplicable === 'yes') ? ($request->input('gst_rate') ?? 0.00) : 0.00,
			'gst_amt' => ($gstApplicable === 'yes') ? ($request->input('gst_amt') ?? 0.00) : 0.00,
			'gst_trans' => ($gstApplicable === 'yes') ? ($request->input('gst_trans') ?? null) : null,
			'gst_allocation' => ($gstApplicable === 'yes') ? ($request->input('gst_allocation') ?? null) : null,
			'itc_applicable' => $request->itc_applicable ?? 'no',

			'supporting_document' => $filePath
		]);

		// ================= RESPONSE =================
		return response()->json([
			'class' => 'succ',
			'message' => 'Expense updated successfully',
			'redirect' => url('/expenses_inventorylist')
		]);
	}

	// DELETE
	public function deleteInventoryExpenses($id)
	{
		$expense = InventoryExpenses::findOrFail($id);

		// Safe delete file
		if ($expense->supporting_document && 
			Storage::disk('public')->exists($expense->supporting_document)) {
			Storage::disk('public')->delete($expense->supporting_document);
		}

		$expense->delete();

		return response()->json([
			'class' => 'succ',
			'message' => 'Expense deleted successfully'
		]);
	}

	// Fetch TDS rule based on category
	// public function getTdsRule(Request $request)
	// {
	// 	$category = $request->category;

	// 	$rule = DB::table('tds_rules')
	// 		->where('module', 'Expenses')
	// 		->where('category', $category)
	// 		->first();

	// 	return response()->json($rule);
	// }

	// public function getTdsRule(Request $request)
	// {
	// 	$category = $request->category;


	// 	$rule = DB::table('tds_rules')
	// 		->where('module', 'Expenses')
    //         // Updated line below: maps the dropdown value inline and uses LIKE for partial matching
	// 		->where('category', 'LIKE', '%' . match($category) {
    //             'raw_material' => 'Goods Purchase',
    //             'direct_labor' => 'Salary & Wages',
    //             'job_outsourcing', 'freight_inwards' => 'Job Work',
    //             default => $category
    //         } . '%')
	// 		->first();

	// 	return response()->json($rule);
	// }

	public function getTdsRule(Request $request)
	{
		$category = $request->category;

		// $searchCategory = match ($category) {
		// 	'raw_material' => 'Goods Purchase',
		// 	'direct_labor' => 'Salary & Wages',
		// 	'job_outsourcing', 'freight_inwards' => 'Job Work',
		// 	default => $category
		// };

		$rule = DB::table('tds_rules')
			->where('module', 'Expenses')
			->where('category', 'LIKE', "%{$category}%")
			->first();

		return response()->json($rule);
	}

	
	
	public function calculateTdsInvexp(Request $request)
	{
		$data = [
			'expense_amount' => $request->expense_amount,
			'tds_percentage' => $request->tds_percentage
		];

		$result = $this->calculateTdsForInvExp($data);

		return response()->json($result);
	}
	
	private function calculateTdsForInvExp(array $data): array
	{
		$amount = isset($data['expense_amount']) ? (float)$data['expense_amount'] : 0;
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

}
