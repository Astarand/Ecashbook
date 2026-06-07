<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
// use Auth;
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
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Helpers\AuditLogger;

class ContraController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
	public function BankList(Request $request)
    {
		//$this->middleware('auth');
		$title = 'Banks';
		$userId = currentOwnerId();
		checkCoreAccess('Cash & Banking');

		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access

		if(Auth::user()->u_type ==1){ //ca
			// $banks =  DB::table('banks')
			// 				->select(DB::raw('banks.*,company_profiles.comp_name,ca_assigns.ca_id'))
			// 				->leftJoin('company_profiles', 'banks.added_by', '=', 'company_profiles.userId')
			// 				->leftJoin('ca_assigns', 'banks.added_by', '=', 'ca_assigns.comp_id')
			// 				->where('ca_assigns.ca_id','=',$userId)
			// 				->where('ca_assigns.ca_assign_status','=',1)
			// 				->orderBy('id', 'DESC')->paginate(10);

			$banks = DB::table('banks as b')
						->leftJoin('company_profiles as cp', 'b.added_by', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'b.added_by')
						->select(
							'b.*',
							DB::raw("
								CASE
									WHEN b.propId IS NOT NULL AND b.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->where('b.added_by', $userId)
						->orderBy('b.id', 'DESC')
						->get();
		}else if(Auth::user()->u_type ==4){ //ca employee
			// $banks =  DB::table('banks')
			// 				->select(DB::raw('banks.*,company_profiles.comp_name,ca_assigns.ca_id'))
			// 				->leftJoin('company_profiles', 'banks.added_by', '=', 'company_profiles.userId')
			// 				->leftJoin('ca_assigns', 'banks.added_by', '=', 'ca_assigns.comp_id')
			// 				->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
			// 				->where('ca_assigns.ca_assign_status','=',1)
			// 				->orderBy('id', 'DESC')->paginate(10);

			$banks = DB::table('banks as b')
						->leftJoin('company_profiles as cp', 'b.added_by', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'b.added_by')
						->select(
							'b.*',
							DB::raw("
								CASE
									WHEN b.propId IS NOT NULL AND b.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->where('b.added_by', $userId)
						->orderBy('b.id', 'DESC')
						->get();

		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user

			/*$banks = DB::table('banks')
					->select(DB::raw('banks.*, company_profiles.comp_name'))
					->leftJoin('company_profiles', 'banks.added_by', '=', 'company_profiles.userId')
					->where('banks.added_by', '=', $userId)
					->orderBy('banks.id', 'DESC')
					->get();*/
			$banks = DB::table('banks as b')
						->leftJoin('company_profiles as cp', 'b.added_by', '=', 'cp.userId')
						->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'b.added_by')
						->select(
							'b.*',
							DB::raw("
								CASE
									WHEN b.propId IS NOT NULL AND b.propId != ''
									THEN pp.comp_name
									ELSE cp.comp_name
								END as comp_name
							")
						)
						->where('b.added_by', $userId)
						->orderBy('b.id', 'DESC')
						->get();
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$banks =  DB::table('banks')
							->select(DB::raw('banks.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'banks.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$banks_pagination = $banks;
		
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();

		$array = array();
		foreach($banks as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['propId'] = $val->propId;
			$array[$val->id]['comp_name'] = $val->comp_name;
			$array[$val->id]['bank_name'] = $val->bank_name;
			$array[$val->id]['bank_branch'] = $val->bank_branch;
			$array[$val->id]['accholder_name'] = $val->accholder_name;
			$array[$val->id]['bank_ac_no'] = $val->bank_ac_no;
			$array[$val->id]['ifsc_code'] = $val->ifsc_code;
			$array[$val->id]['swift_code'] = $val->swift_code;
			$array[$val->id]['upi_id'] = $val->upi_id;
			$array[$val->id]['curr_bal'] = $val->curr_bal;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['proprietorships'] = $proprietorships;

			$totalTransaction =  DB::table('bank_trans')
							->select(DB::raw('SUM(bank_trans.tran_amt) as totalTransaction'))
							->where('bankId', '=', $val->id)
							->get();

			$cashLimit = ($val->curr_bal);
			$totalTransaction = ($totalTransaction[0]->totalTransaction);
			$outstanding = ($cashLimit - $totalTransaction);
			$availableLimit = ($cashLimit - $outstanding);

			$array[$val->id]['outstanding'] = $outstanding;
			$array[$val->id]['availableLimit'] = $availableLimit;
		}
		$banks = json_decode(json_encode($array));
		//echo "<pre>";print_r($banks);exit;
		return view('User.bank-list')->with([
			'title' =>$title,
			'banks'=>$banks,
			'proprietorships'=>$proprietorships,
			'banks_pagination' =>$banks_pagination,
			'req_type' => $req_type

		]);
    }

	protected function validatorBank(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'bank_name' => 'required',
				'bank_branch' => 'required',
				'accholder_name' => 'required',
				'bank_ac_no' => 'required',
				'ifsc_code' => 'required',
				'curr_bal' => 'required'
			]);

    }

	protected function createBank(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$propId = $data['propId'] ?? null;
        return Banks::create([
            'added_by' => $userId,
            'propId' => $propId,
            'bank_name' => $data['bank_name'],
			'bank_branch' => $data['bank_branch'],
			'accholder_name' => $data['accholder_name'],
			'bank_ac_no' => $data['bank_ac_no'],
			'ifsc_code' => $data['ifsc_code'],
			'swift_code' => $data['swift_code'],
			'upi_id'  => $data['upi_id'],
			'curr_bal' => $data['curr_bal'],
			'created_at' => date('Y-m-d H:i:s'),
			'status' => '1',
        ]);
    }

	public function save_bank(Request $request)  {

		//echo "<pre>";print_r($request->file('prod_image'));exit;
		//$input = Input::all();
		//dd($input);
		$redirectUrl = !empty($request->redirectUrl) ? url('/' . $request->redirectUrl) : url('/bank-list');
		$validation = $this->validatorBank($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertBank = $this->createBank($request->all());
			$sId = DB::getPdo()->lastInsertId();

			if ($insertBank){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => $redirectUrl,
					'message' => 'Bank account added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Bank add failed'
				);
				return response()->json($msg);
			}
		}
    }


	public function update_bank(Request $request)  {

		//echo "<pre>";print_r($_POST);exit;
		$bankId = $request->id;
		$propId = $request->propId ?? null;
		$redirectUrl = !empty($request->redirectUrl) ? url('/' . $request->redirectUrl) : url('/bank-list');
		
		$validation = $this->validatorBank($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update project
			$update = DB::table('banks')
					->where('id', $bankId)
					->update(
						array(
							'propId' => $propId,
							'bank_name' => $request->bank_name,
							'bank_branch' => $request->bank_branch,
							'accholder_name' => $request->accholder_name,
							'bank_ac_no' => $request->bank_ac_no,
							'ifsc_code' => $request->ifsc_code,
							'swift_code' => $request->swift_code,
							'upi_id'  => $request->upi_id,
							'curr_bal' => $request->curr_bal
						)
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => $redirectUrl,
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);

		}
    }
	
	public function deleteBank($id)
	{
		$redirectUrl = !empty($request->redirectUrl) ? url('/' . $request->redirectUrl) : url('/bank-list');
		try {

			DB::beginTransaction();

			//Delete from bank_trans first (child table)
			DB::table('bank_trans')
				->where('bankId', $id)
				->delete();

			//Delete from banks table
			DB::table('banks')
				->where('id', $id)
				->delete();

			DB::commit();

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => $redirectUrl,
				'message' => 'Bank and related transactions deleted successfully'
			]);

		} catch (\Exception $e) {

			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Something went wrong!'
			]);
		}
	}

    public function BankDetails($bankId)
    {
		$title = 'Banks';
		$userId = currentOwnerId();
		checkCoreAccess('Cash & Banking');

		$req_type = 0;
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
			$req_type = 0;
		} else {
			$userId = session('compId'); //ca-accountant access
			$req_type = 1;
		}

		$bankId = base64_decode($bankId);
		/*$bank = DB::table('banks')
								->where('id', '=', $bankId)
								->get();
		$bank = $bank[0];*/
		$bank = DB::table('banks as b')
				->leftJoin('company_profiles as cp', 'b.added_by', '=', 'cp.userId')
				->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'b.added_by')
				->select(
					'b.*',
					DB::raw("
						CASE
							WHEN b.propId IS NOT NULL AND b.propId != ''
							THEN pp.comp_name
							ELSE cp.comp_name
						END as comp_name
					")
				)
				->where('b.id', $bankId)
				->first();
		$prop_id = $bank->propId;

		$totaltransaction =  DB::table('bank_trans')
							->select(DB::raw('SUM(bank_trans.tran_amt) as totaltransaction'))
							->where('bankId', '=', $bankId)
							->get();

		$cashLimit = ($bank->curr_bal);
		$totaltransaction = ($totaltransaction[0]->totaltransaction);
		$outstanding = ($cashLimit - $totaltransaction);
		$availableLimit = ($cashLimit - $outstanding);


		if(Auth::user()->u_type ==1){ //ca
			$bank_trans =  DB::table('bank_trans')
							->select(DB::raw('bank_trans.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'bank_trans.added_by', '=', 'company_profiles.userId')
							->where('bank_trans.added_by', '=', $userId)
							->where('bank_trans.bankId','=',$bankId)
							->orderBy('bank_trans.tran_date', 'DESC')->get();
		}else if(Auth::user()->u_type ==4){ //ca employee
			$bank_trans =  DB::table('bank_trans')
							->select(DB::raw('bank_trans.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'bank_trans.added_by', '=', 'company_profiles.userId')
							->where('bank_trans.added_by', '=', $userId)
							->where('bank_trans.bankId','=',$bankId)
							->orderBy('bank_trans.tran_date', 'DESC')->get();
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$bank_trans =  DB::table('bank_trans')
							->select(DB::raw('bank_trans.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'bank_trans.added_by', '=', 'company_profiles.userId')
							->where('bank_trans.added_by', '=', $userId)
							->where('bank_trans.bankId','=',$bankId)
							->orderBy('bank_trans.tran_date', 'DESC')->get();
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$bank_trans =  DB::table('bank_trans')
							->select(DB::raw('bank_trans.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'bank_trans.added_by', '=', 'company_profiles.userId')
							->where('bank_trans.bankId','=',$bankId)
							->orderBy('id', 'DESC')->paginate(10);
		}
		$bank_trans_pagination = $bank_trans;

		$array = array();
		$array = array();

		foreach ($bank_trans as $k => $val) 
		{
			$tranDate = date('Y-m-d', strtotime($val->tran_date));
			$tranAmt  = round($val->tran_amt, 2);
			$tranType = $val->tran_type;
			$matchedData = null;

			// Add to result array
			$array[$val->id] = [
				'id' => $val->id,
				'tran_date' => $val->tran_date,
				'ref_no' => $val->ref_no,
				'tran_amt' => $tranAmt,
				'purpose' => $val->purpose,
				'tran_type' => $tranType,
				'reference' =>  null,
			];
		}
		$bank_trans = json_decode(json_encode($array));
		//echo "<pre>";print_r($bank_trans);exit;

		if(Auth::user()->u_type ==1){ //ca
			$cash_credit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.added_by', '=', $userId)
							->where('cash_credit_debits.cd_type','=',"cr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cash_debit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.added_by', '=', $userId)
							->where('cash_credit_debits.cd_type','=',"dr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cashAsOnDate =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.cd_date'))
							->where('cash_credit_debits.added_by', '=', $userId)
							->orderBy('cash_credit_debits.cd_date', 'DESC')
							->get();
			$cashInHand =  DB::table('cash_hands')
							->select(DB::raw('cash_hands.amount_in_hand,cash_hands.updated_at'))
							->where('cash_hands.added_by', '=', $userId)
							->get();
			$cashInHandData = (isset($cashInHand) && (count($cashInHand)>0))?$cashInHand[0]->amount_in_hand:0;
			$cashInHandDate = (isset($cashInHand) && (count($cashInHand)>0))? date("d-m-Y",strtotime($cashInHand[0]->updated_at)):"";
			$cashAsOnDate = (isset($cashAsOnDate) && (count($cashAsOnDate)>0))? date("d-m-Y",strtotime($cashAsOnDate[0]->cd_date)):"";

		}else if(Auth::user()->u_type ==4){ //ca employee
			$cash_credit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.added_by', '=', $userId)
							->where('cash_credit_debits.cd_type','=',"cr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cash_debit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.added_by', '=', $userId)
							->where('cash_credit_debits.cd_type','=',"dr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cashAsOnDate =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.cd_date'))
							->where('cash_credit_debits.added_by', '=', $userId)
							->orderBy('cash_credit_debits.cd_date', 'DESC')
							->get();
			$cashInHand =  DB::table('cash_hands')
							->select(DB::raw('cash_hands.amount_in_hand,cash_hands.updated_at'))
							->where('cash_hands.added_by', '=', $userId)
							->get();
			$cashInHandData = (isset($cashInHand) && (count($cashInHand)>0))?$cashInHand[0]->amount_in_hand:0;
			$cashInHandDate = (isset($cashInHand) && (count($cashInHand)>0))? date("d-m-Y",strtotime($cashInHand[0]->updated_at)):"";
			$cashAsOnDate = (isset($cashAsOnDate) && (count($cashAsOnDate)>0))? date("d-m-Y",strtotime($cashAsOnDate[0]->cd_date)):"";

		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$cash_credit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.added_by', '=', $userId)
							->where('cash_credit_debits.cd_type','=',"cr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cash_debit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.added_by', '=', $userId)
							->where('cash_credit_debits.cd_type','=',"dr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cashAsOnDate =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.cd_date'))
							->where('cash_credit_debits.added_by', '=', $userId)
							->orderBy('cash_credit_debits.cd_date', 'DESC')
							->get();
			$cashInHand =  DB::table('cash_hands')
							->select(DB::raw('cash_hands.amount_in_hand,cash_hands.updated_at'))
							->where('cash_hands.added_by', '=', $userId)
							->get();
			$cashInHandData = (isset($cashInHand) && (count($cashInHand)>0))?$cashInHand[0]->amount_in_hand:0;
			$cashInHandDate = (isset($cashInHand) && (count($cashInHand)>0))? date("d-m-Y",strtotime($cashInHand[0]->updated_at)):"";
			$cashAsOnDate = (isset($cashAsOnDate) && (count($cashAsOnDate)>0))? date("d-m-Y",strtotime($cashAsOnDate[0]->cd_date)):"";
			//echo $cashAsOnDate;exit;
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$cash_credit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.cd_type','=',"cr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cash_debit =  DB::table('cash_credit_debits')
							->select(DB::raw('cash_credit_debits.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'cash_credit_debits.added_by', '=', 'company_profiles.userId')
							->where('cash_credit_debits.cd_type','=',"dr")
							->orderBy('cash_credit_debits.cd_date', 'DESC')->paginate(10);

			$cashInHandData = 0;
			$cashInHandDate = "";
			$cashAsOnDate = "";
		}
		$cash_credit_pagination = $cash_credit;
		$cash_debit_pagination = $cash_debit;

		$array = array();
		foreach($cash_credit as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['cd_date'] = $val->cd_date;
			$array[$val->id]['particulars'] = $val->particulars;
			$array[$val->id]['cd_amount'] = $val->cd_amount;
			$array[$val->id]['comp_name'] = $val->comp_name;
		}
		$cash_credit = json_decode(json_encode($array));

		$array2 = array();
		foreach($cash_debit as $k=>$val)
		{
			$array2[$val->id]['id'] = $val->id;
			$array2[$val->id]['cd_date'] = $val->cd_date;
			$array2[$val->id]['particulars'] = $val->particulars;
			$array2[$val->id]['cd_amount'] = $val->cd_amount;
			$array2[$val->id]['comp_name'] = $val->comp_name;
		}
		$cash_debit = json_decode(json_encode($array2));

		$totalTransAmounts = DB::table('bank_trans')
							->select(
								DB::raw("COALESCE(SUM(CASE WHEN tran_type = 'Credit' THEN tran_amt ELSE 0 END), 0) as totalCredit"),
								DB::raw("COALESCE(SUM(CASE WHEN tran_type = 'Debit' THEN tran_amt ELSE 0 END), 0) as totalDebit")
							)
							->where('bankId', '=', $bankId)
							->first();

        //$this->middleware('auth');
		//dd($bank_trans);
        return view('User.bank-details')->with([

			'bank' => $bank,
			'cash_credit' => $cash_credit,
			'cash_debit'=> $cash_debit,
			'outstanding' => $outstanding,
			'availableLimit' => $availableLimit,
			'bank_trans' => $bank_trans,
			'bankId' => $bankId,
			'prop_id' => $prop_id,
			'bank_trans_pagination' => $bank_trans_pagination,
			'totalTransAmounts' => $totalTransAmounts,
			'req_type' => $req_type,

        ]);
    }

	public function cash_trans_delete(Request $request)
	{
		$tranId = base64_decode($request->id);


		if (!$tranId) {
			return response()->json([
				'status' => 'error',
				'message' => 'Transaction ID is required.'
			], 400);
		}

		//FETCH OLD TRANSACTION
		$transaction = DB::table('mcash_credit_debits')->where('id', $tranId)->first();
		$oldData = [
			'transaction' => [
				'tran_date' => $transaction->cd_date ?? null,
				'particulars' => $transaction->particulars ?? '',
				'type'    => $transaction->cd_type ?? '',
				'amount'    => $transaction->cd_amount ?? 0,
			]
		];
		// Delete the transaction from the bank_trans table
		$delete = DB::table('mcash_credit_debits')->where('id', $tranId)->delete();

		if ($delete) {
			//AUDIT LOG ENTRY
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Cash Transaction',
				description: "Cash transaction deleted: {$transaction->cd_type} (amount: {$transaction->cd_amount})",
				oldData: $oldData,
				newData: null
			);
			return response()->json([
				'status' => 'success',
				'message' => 'Transaction deleted successfully.'
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to delete transaction.'
			], 500);
		}
	}

    /*public function AddBankTransaction($bankId)
    {
		$bankId = base64_decode($bankId);
        //$this->middleware('auth');
        return view('pages.add-bank-transaction')->with([
			'bankId' => $bankId
        ]);
    }*/

	protected function validatorTransaction(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'tran_date' => 'required',

				'tran_amt' => 'required',
				'tran_type' => 'required',
				'purpose' => 'required',
				// 'curr_amt' => 'required',
				// 'message' => 'required',
				// 'tran_doc' => 'required'
			]);

    }

    protected function createTransaction(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$prop_id = $data['prop_id'];
        return Bank_trans::create([
            'added_by' => $userId,
            'prop_id' => $prop_id,
            'bankId' => $data['bankId'],
            'tran_date' => $data['tran_date'],
			'payment_mode' => $data['payment_mode'] ?? null,
			'tran_amt' => $data['tran_amt'],
			'tran_type' => $data['tran_type'],
			'purpose' => isset($data['purpose'])?$data['purpose']:"",
			'curr_amt' => $data['curr_amt'] ?? null,
			'ref_no' => $data['ref_no'] ?? null,
			'message' => $data['message'] ?? null,
			'tarn_doc' => "",
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_transaction(Request $request)  {

		//echo "<pre>";print_r($request);exit;

		$bankId = $request->bankId;
		$validation = $this->validatorTransaction($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertTransaction = $this->createTransaction($request->all());
			$tranId = DB::getPdo()->lastInsertId();

			if ($insertTransaction){

				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/bank-details/'.base64_encode($bankId)),
					'message' => 'Transaction added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Transaction add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function update_transaction(Request $request)  {

		//echo "<pre>";print_r($_REQUEST);exit;
		//echo "<pre>";print_r($request->all());exit;
		$tranId = $request->id;
		$bankId = $request->bankId;
		$validation = $this->validatorTransaction($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{

			$update = DB::table('bank_trans')
					->where('id', $tranId)
					->update(
						array(
								'tran_date' => $request->tran_date,
								'tran_type' => $request->tran_type,
								'purpose' => $request->purpose,
								'tran_amt' => $request->tran_amt,
								'ref_no' => $request->ref_no,
						)
					);

			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/bank-details/'.base64_encode($bankId)),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);

		}
    }

	public function bank_statement_delete(Request $request)
	{
		$tranId = $request->id;


		if (!$tranId) {
			return response()->json([
				'status' => 'error',
				'message' => 'Transaction ID is required.'
			], 400);
		}
		
		//FETCH OLD TRANSACTION
		$transaction = DB::table('bank_trans')->where('id', $tranId)->first();
		$oldData = [
			'transaction' => [
				'tran_date' => $transaction->tran_date ?? null,
				'purpose' => $transaction->purpose ?? '',   
				'type'    => $transaction->tran_type ?? '',
				'amount'    => $transaction->tran_amt ?? 0
			]
		];

		// Delete the transaction from the bank_trans table
		$delete = DB::table('bank_trans')->where('id', $tranId)->delete();

		if ($delete) {
			//AUDIT LOG ENTRY
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Bank Transaction',
				description: "Bank transaction deleted: {$transaction->tran_type} (amount: {$transaction->tran_amt})",
				oldData: $oldData,
				newData: null
			);
			return response()->json([
				'status' => 'success',
				'message' => 'Transaction deleted successfully.'
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to delete transaction.'
			], 500);
		}
	}
	
	//Start loan section
	private function recalculateLoanBalance($loanId)
	{
		/* 1. Get total loan */
		$loan = DB::table('loans')
			->where('id', $loanId)
			->first();

		if (!$loan) {
			throw new \Exception("Loan not found");
		}

		/* 2. Recalculate total paid */
		$totalPaid = DB::table('loan_ins')
			->where('loanId', $loanId)
			->sum('ins_amt');

		/* 3. Calculate remaining */
		$remaining = $loan->total_lone_amount - $totalPaid;

		/* 4. Update loans table */
		DB::table('loans')
			->where('id', $loanId)
			->update([
				'remains_loan_amount' => $remaining
			]);

		return $remaining;
	}

	public function deleteLoan($id)
	{
		DB::beginTransaction();

		try {
			DB::table('loan_ins')->where('loanId', $id)->delete();
			DB::table('loans')->where('id', $id)->delete();
			DB::commit();
			return response()->json([
				'class' => 'succ',
				'message' => 'Loan account deleted successfully',
				'redirect' => url('/loan-list')
			]);

		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'class' => 'err',
				'message' => 'Delete failed'
			], 500);
		}
	}

	public function loan_trans_delete(Request $request)
	{
		$tranId = $request->id;

		if (!$tranId) {
			return response()->json([
				'status' => 'error',
				'message' => 'Loan Transaction ID is required.'
			], 400);
		}

		DB::beginTransaction();

		try {

			/* 1. Fetch old transaction */
			$transaction = DB::table('loan_ins')->where('id', $tranId)->first();

			if (!$transaction) {
				return response()->json([
					'status' => 'error',
					'message' => 'Transaction not found.'
				], 404);
			}

			$loanId = $transaction->loanId;

			$oldData = [
				'transaction' => [
					'ins_date' => $transaction->ins_date ?? null,
					'message' => $transaction->message ?? '',
					'payment_mode' => $transaction->payment_mode ?? '',
					'amount' => $transaction->ins_amt ?? 0
				]
			];

			/* 2. Delete installment */
			DB::table('loan_ins')->where('id', $tranId)->delete();

			$remaining = $this->recalculateLoanBalance($loanId);

			/* 7. Audit log */
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Loan Transaction',
				description: "Loan transaction deleted: {$transaction->payment_mode} (amount: {$transaction->ins_amt})",
				oldData: $oldData,
				newData: [
					'remaining_balance' => $remaining
				]
			);

			DB::commit();

			return response()->json([
				'status' => 'success',
				'message' => 'Loan transaction deleted & balance recalculated.'
			]);

		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'message' => 'Failed to delete loan transaction.'
			], 500);
		}
	}

	
    public function LoanList()
    {
		$title = 'Loans';
		$userId = currentOwnerId();
		if(Auth::user()->u_type ==1){ //ca
			$loans =  DB::table('loans')
							->select(DB::raw('loans.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'loans.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'loans.added_by', '=', 'ca_assigns.comp_id')
							->where('ca_assigns.ca_id','=',$userId)
							->where('ca_assigns.ca_assign_status','=',1)
							->orderBy('id', 'DESC')->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca employee
			$loans =  DB::table('loans')
							->select(DB::raw('loans.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'loans.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'loans.added_by', '=', 'ca_assigns.comp_id')
							->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
							->where('ca_assigns.ca_assign_status','=',1)
							->orderBy('id', 'DESC')->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$loans =  DB::table('loans')
							->select(DB::raw('loans.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'loans.added_by', '=', 'company_profiles.userId')
							->where('loans.added_by', '=', $userId)
							->orderBy('loans.id', 'DESC')->paginate(10);
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$loans =  DB::table('loans')
							->select(DB::raw('loans.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'loans.added_by', '=', 'company_profiles.userId')
							->orderBy('id', 'DESC')->paginate(10);
		}
		$loans_pagination = $loans;

		$array = array();
		$array = array();

		foreach ($loans as $k => $val) {
			$bank = DB::table('banks')
					->where('id', '=', $val->bank_name) // Assuming bank_name is actually bank_id in 'loans' table
					->value('bank_name');
			$array[$val->id] = [
				'id'             => $val->id,
				'bank_name'      => $bank,
				'branch'         => $val->branch,
				'app_name'       => $val->app_name,
				'loan_ac_no'     => $val->loan_ac_no,
				'bank_code'      => $val->bank_code,
				'credit_limit'   => $val->credit_limit,
				'status'         => $val->status,
				'ifsc_code'         => $val->ifsc_code,
				'lone_type'         => $val->lone_type,
				'total_lone_amount'         => $val->total_lone_amount,
				'remains_loan_amount'         => $val->remains_loan_amount,
			];

			// Fetch total installment and last updated date
			$totalInstallment = DB::table('loan_ins')
				->select(DB::raw('SUM(ins_amt) as totalInstallment, MAX(updated_at) as lastUpdated'))
				->where('loanId', '=', $val->id)
				->first();  // Use first() instead of get() to prevent array issues

			// Check if totalInstallment data exists, otherwise set default values
			$totalInstallmentAmt = $totalInstallment->totalInstallment ?? 0;
			$loanAsOn = !empty($totalInstallment->lastUpdated)
				? date("d-m-Y", strtotime($totalInstallment->lastUpdated))
				: "";

			// Calculate outstanding and available limit
			$creditLimit = $val->credit_limit;
			$outstanding = $creditLimit - $totalInstallmentAmt;
			$availableLimit = $creditLimit - $outstanding;

			// Add calculated values to the array
			$array[$val->id]['outstanding'] = $outstanding;
			$array[$val->id]['availableLimit'] = $availableLimit;
			$array[$val->id]['loanAsOn'] = $loanAsOn;
		}

		$loans = json_decode(json_encode($array));

		$query = DB::table('banks')->orderBy('id', 'DESC');

			if (!empty($userId)) {
				$query->where('added_by', '=', $userId);
			}

		$banks = $query->get();

		// echo "<pre>"; print_r($loans);exit;
		return view('User.loan-list')->with([
			'title' =>$title,
			'loans'=>$loans,
			'loans_pagination' =>$loans_pagination,
			'banks' =>$banks,
		]);
    }
	
	public function getLoan($id)
	{
		$loan = DB::table('loans')
			->join('banks', 'banks.id', '=', 'loans.bank_name')
			->where('loans.id', $id)
			->select(
				'loans.*',
				'banks.bank_name as bank_name_text'
			)
			->first();

		return response()->json($loan);
	}

	protected function validatorLoan(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'bank_name' => 'required',
				'branch' => 'required',
				'app_name' => 'required',
				'loan_ac_no' => 'required',
				// 'bank_code' => 'required',
				// 'credit_limit' => 'required'
			]);

    }

    protected function createLoan(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
        return Loans::create([
            'added_by' => $userId,
            'bank_name' => $data['bank_name'],
			'branch' => $data['branch'],
			'app_name' => $data['app_name'],
			'loan_ac_no' => $data['loan_ac_no'],
			'ifsc_code' => $data['ifsc_code'],
			'lone_type' => $data['lone_type'],
			'upi_id' => $data['upi_id'],
			'total_lone_amount' => $data['total_lone_amount'],
			'remains_loan_amount' => $data['remains_loan_amount'],
			// 'bank_code' => $data['bank_code'],
			// 'credit_limit' => $data['credit_limit'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_loan(Request $request)  {

		//echo "<pre>";print_r($request->file('prod_image'));exit;
		//$input = Input::all();
		//dd($input);
		$validation = $this->validatorLoan($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertLoan = $this->createLoan($request->all());
			$sId = DB::getPdo()->lastInsertId();

			if ($insertLoan){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/loan-list'),
					'message' => 'Loan added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Loan add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function update_loan(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$loanId = $request->id;

		$validation = $this->validatorLoan($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//start update project
			$update = DB::table('loans')
					->where('id', $loanId)
					->update(
						 array(
								'bank_name' => $request->bank_name,
								'branch' => $request->branch,
								'app_name' => $request->app_name,
								'loan_ac_no' => $request->loan_ac_no,
								'bank_code' => $request->bank_code,
								'lone_type' => $request->lone_type,
								'upi_id' => $request->upi_id,
								'total_lone_amount' => $request->total_lone_amount,
								'remains_loan_amount' => $request->remains_loan_amount,
						 )
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/loan-list'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);

		}
    }

    public function LoanAccountDetails($loanId)
    {

		$title = 'Loans';
		$userId = currentOwnerId();

		$loanId = base64_decode($loanId);
		$loan = DB::table('loans')
				->leftJoin('banks', 'banks.id', '=', 'loans.bank_name') // Join banks table
				->select('loans.*', 'banks.bank_name as bank_real_name') // Fetch actual bank name
				->where('loans.id', '=', $loanId)
				->first(); // Fetch a single record


		$totalInstallment = DB::table('loan_ins')
						->where('loanId', '=', $loanId)
						->sum('ins_amt'); // Directly sum the column instead of using select()

		// Ensure default values if null
		$totalLoan = $loan->total_lone_amount ?? 0;
		$totalPaid = $totalInstallment ?? 0;
		$remainingLoan = $totalLoan - $totalPaid;



		if(Auth::user()->u_type ==1){ //ca
			$loan_ins =  DB::table('loan_ins')
							->select(DB::raw('loan_ins.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'loan_ins.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'loan_ins.added_by', '=', 'ca_assigns.comp_id')
							->where('ca_assigns.ca_id','=',$userId)
							->where('ca_assigns.ca_assign_status','=',1)
							->where('loan_ins.loanId','=',$loanId)
							->orderBy('id', 'DESC')->paginate(10);
		}else if(Auth::user()->u_type ==4){ //ca employee
			$loan_ins =  DB::table('loan_ins')
							->select(DB::raw('loan_ins.*,company_profiles.comp_name,ca_assigns.ca_id'))
							->leftJoin('company_profiles', 'loan_ins.added_by', '=', 'company_profiles.userId')
							->leftJoin('ca_assigns', 'loan_ins.added_by', '=', 'ca_assigns.comp_id')
							->leftJoin('users', 'ca_assigns.ca_id', '=', 'users.ca_add_by')
							->where('ca_assigns.ca_assign_status','=',1)
							->where('loan_ins.loanId','=',$loanId)
							->orderBy('id', 'DESC')->paginate(10);
		}elseif(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$loan_ins =  DB::table('loan_ins')
							->select(DB::raw('loan_ins.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'loan_ins.added_by', '=', 'company_profiles.userId')
							->where('loan_ins.added_by', '=', $userId)
							->where('loan_ins.loanId','=',$loanId)
							->orderBy('loan_ins.id', 'DESC')->paginate(10);
		}
		elseif(Auth::user()->u_type ==3){ //admin
			$loan_ins =  DB::table('loan_ins')
							->select(DB::raw('loan_ins.*,company_profiles.comp_name'))
							->leftJoin('company_profiles', 'loan_ins.added_by', '=', 'company_profiles.userId')
							->where('loan_ins.loanId','=',$loanId)
							->orderBy('id', 'DESC')->paginate(10);
		}
		$loan_ins_pagination = $loan_ins;

		$array = array();
		foreach($loan_ins as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['ins_date'] = $val->ins_date;
			$array[$val->id]['payment_mode'] = $val->payment_mode;
			$array[$val->id]['ins_amt'] = $val->ins_amt;
			$array[$val->id]['message'] = $val->message;
		}
		$loan_ins = json_decode(json_encode($array));
		// echo "<pre>";
		// print_r($loan_ins);
		// die();
		return view('User.loan-account-details')->with([
			'loan' => $loan,
			'totalPaid' => $totalPaid,
			'remainingLoan' => $remainingLoan,
			'loan_ins' => $loan_ins,
			'loanId' => $loanId,
			'loan_ins_pagination' => $loan_ins_pagination,
		]);
    }

	protected function validatorInstallment(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'ins_date' => 'required',
				'payment_mode' => 'required',
				'ins_amt' => 'required',
				// 'curr_amt' => 'required',
				'message' => 'required',
				// 'ins_doc' => 'required'
			]);

    }

    protected function createInstallment(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
        return Loan_ins::create([
            'added_by' => $userId,
            'loanId' => $data['loanId'],
            'ins_date' => $data['ins_date'],
			'payment_mode' => $data['payment_mode'],
			'ins_amt' => $data['ins_amt'],
			// 'curr_amt' => $data['curr_amt'],
			'message' => $data['message'],
			// 'ins_doc' => $data['ins_doc'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_installment(Request $request)
	{
		$loanId = $request->loanId;

		$validation = $this->validatorInstallment($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		DB::beginTransaction();

		try {

			/* 1. Insert installment */
			$this->createInstallment($request->all());

			$remaining = $this->recalculateLoanBalance($loanId);

			DB::commit();

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/loan-account-details/' . base64_encode($loanId)),
				'message' => 'Installment added & loan balance updated'
			]);

		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Installment failed'
			], 500);
		}
	}

	public function update_installment(Request $request)
	{
		$insId  = $request->id;
		$loanId = $request->loanId;

		$validation = $this->validatorInstallment($request->all());
		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		DB::beginTransaction();

		try {

			/* 1. Update installment */
			DB::table('loan_ins')
				->where('id', $insId)
				->update([
					'ins_date'     => $request->ins_date,
					'payment_mode'=> $request->payment_mode,
					'ins_amt' => $request->ins_amt,
					'message'     => $request->message,
				]);

			$remaining = $this->recalculateLoanBalance($loanId);

			DB::commit();

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/loan-account-details/' . base64_encode($loanId)),
				'message' => 'Installment updated & loan balance recalculated'
			]);

		} catch (\Exception $e) {
			DB::rollBack();

			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Update failed'
			], 500);
		}
	}

	
	public function delInstallment(Request $request)
    {
		$loan = DB::table('loan_ins')
								->where('id', '=', $request->id)
								->get();
		$loanId = $loan[0]->loanId;

        $delInstallment = DB::table('loan_ins')->where('id', $request->id)->delete();
		if($delInstallment){
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/loan-statement/'.base64_encode($loanId)),
				'message' => 'Installment deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'redirect' => url('/loan-statement/'.base64_encode($loanId)),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }
	//End loan section

    public function CashManagement(Request $request)
    {
        $title = 'Cash Management';
		$userId = currentOwnerId();
		checkCoreAccess('Cash & Banking');

		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {  // CA or CA Employee
			$userId = getAccessCompanyId($request);
			$req_type = 1;
		}

		$format="Y";
		$today = date('Y-m-d');
		$date=date_create($today);
		$financial_year = "";
		if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
			$financial_year = (date_format($date,$format)) . '-' . (date_format($date,$format)+1);
		} else {
			//On or Before March (FY is previous year - current year)
			$financial_year = (date_format($date,$format)-1) . '-' . date_format($date,$format);
		}
		$financial_year = explode("-",$financial_year);

		$financialStart = carbon::parse($financial_year[0]."-04-01");
		$financialEnd = carbon::parse($financial_year[1]."-03-31");
		$totalCredit =  DB::table('cash_credit_debits')
							->select(DB::raw('SUM(cash_credit_debits.cd_amount) as totalCredit'))
							->where('cash_credit_debits.cd_type','=',"cr")
							->WhereBetween('cd_date', [$financialStart, $financialEnd])
							->get();
		$totalCredit = $totalCredit[0]->totalCredit;

		$totalDebit =  DB::table('cash_credit_debits')
							->select(DB::raw('SUM(cash_credit_debits.cd_amount) as totalDebit'))
							->where('cash_credit_debits.cd_type','=',"dr")
							->WhereBetween('cd_date', [$financialStart, $financialEnd])
							->get();
		$totalDebit = $totalDebit[0]->totalDebit;
		
		$cashInHandData = 0;
		$cashInHandDate = "";
		$cashAsOnDate = "";

		if(Auth::user()->u_type ==2 || Auth::user()->u_type ==5){ //user
			$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
			$cash_trans_data = DB::table('mcash_credit_debits as m')
							->leftJoin('company_profiles as cp', 'm.added_by', '=', 'cp.userId')
							->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'm.added_by')
							->select(
								'm.*',
								DB::raw("
									CASE
										WHEN m.propId IS NOT NULL AND m.propId != ''
										THEN pp.comp_name
										ELSE cp.comp_name
									END as comp_name
								")
							)
							->where('m.added_by', $userId)
							->orderBy('m.cd_date', 'DESC')
							->paginate(10);

			$cashAsOnDate =  DB::table('mcash_credit_debits')
							->select(DB::raw('mcash_credit_debits.cd_date'))
							->where('mcash_credit_debits.added_by', '=', $userId)
							->orderBy('mcash_credit_debits.cd_date', 'DESC')
							->get();
			$cashInHand =  DB::table('cash_hands')
							->select(DB::raw('cash_hands.amount_in_hand,cash_hands.updated_at'))
							->where('cash_hands.added_by', '=', $userId)
							->get();
			$cashInHandData = (isset($cashInHand) && (count($cashInHand)>0))?$cashInHand[0]->amount_in_hand:0;
			$cashInHandDate = (isset($cashInHand) && (count($cashInHand)>0))? date("d-m-Y",strtotime($cashInHand[0]->updated_at)):"";
			$cashAsOnDate = (isset($cashAsOnDate) && (count($cashAsOnDate)>0))? date("d-m-Y",strtotime($cashAsOnDate[0]->cd_date)):"";

			$total_credit_raw = $cash_trans_data->where('cd_type', 'cr')->sum('cd_amount');
			$total_debit_raw = $cash_trans_data->where('cd_type', 'dr')->sum('cd_amount');
			$total_cash_hand_raw = $total_credit_raw - $total_debit_raw;

			$total_credit = number_format($total_credit_raw ?? 0, 2);
			$total_debit = number_format($total_debit_raw ?? 0, 2);
			$total_cash_hand = number_format($total_cash_hand_raw ?? 0, 2);


		}else if(Auth::user()->u_type ==1 || Auth::user()->u_type ==4){
			$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$userId)
						->get();
			$cash_trans_data = DB::table('mcash_credit_debits as m')
							->leftJoin('company_profiles as cp', 'm.added_by', '=', 'cp.userId')
							->leftJoin('proprietorship_profiles as pp', 'pp.userId', '=', 'm.added_by')
							->select(
								'm.*',
								DB::raw("
									CASE
										WHEN m.propId IS NOT NULL AND m.propId != ''
										THEN pp.comp_name
										ELSE cp.comp_name
									END as comp_name
								")
							)
							->where('m.added_by', $userId)
							->orderBy('m.cd_date', 'DESC')
							->paginate(10);

			$cashAsOnDate =  DB::table('mcash_credit_debits')
							->select(DB::raw('mcash_credit_debits.cd_date'))
							->where('mcash_credit_debits.added_by', '=', $userId)
							->orderBy('mcash_credit_debits.cd_date', 'DESC')
							->get();
			$cashInHand =  DB::table('cash_hands')
							->select(DB::raw('cash_hands.amount_in_hand,cash_hands.updated_at'))
							->where('cash_hands.added_by', '=', $userId)
							->get();
			$cashInHandData = (isset($cashInHand) && (count($cashInHand)>0))?$cashInHand[0]->amount_in_hand:0;
			$cashInHandDate = (isset($cashInHand) && (count($cashInHand)>0))? date("d-m-Y",strtotime($cashInHand[0]->updated_at)):"";
			$cashAsOnDate = (isset($cashAsOnDate) && (count($cashAsOnDate)>0))? date("d-m-Y",strtotime($cashAsOnDate[0]->cd_date)):"";

			$total_credit_raw = $cash_trans_data->where('cd_type', 'cr')->sum('cd_amount');
			$total_debit_raw = $cash_trans_data->where('cd_type', 'dr')->sum('cd_amount');
			$total_cash_hand_raw = $total_credit_raw - $total_debit_raw;

			$total_credit = number_format($total_credit_raw ?? 0, 2);
			$total_debit = number_format($total_debit_raw ?? 0, 2);
			$total_cash_hand = number_format($total_cash_hand_raw ?? 0, 2);
		}
		
		return view('User.cash-management')->with([
			'totalCredit' => $totalCredit,
			'totalDebit' => $totalDebit,
			'cashInHand' => $cashInHandData,
			'cashInHandDate' => $cashInHandDate,
			'cashAsOnDate' => $cashAsOnDate,
			'cash_trans_data' => $cash_trans_data,
			'proprietorships' => $proprietorships,
			'total_debit' => $total_debit,
			'total_credit' => $total_credit,
			'cash_in_hand' => $total_cash_hand,
			'req_type' => $req_type
		]);
    }

	protected function validatorCash(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
			return Validator::make($data, [
				'cd_date' => 'required',
				'particulars' => 'required',
				'cd_amount' => 'required',
			]);

    }

    protected function createCashCredit(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
		$propId = $data['propId'];
        return Mcash_credit_debits::create([
            'added_by' => $userId,
            'propId' => $propId,
            'cd_date' => $data['cd_date'],
			'particulars' => $data['particulars'],
			'cd_amount' => $data['cd_amount'],
			'cd_type' => $data['cd_type'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_cash_credit(Request $request)  {
		//print_r($request);exit;

		$validation = $this->validatorCash($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertData = $this->createCashCredit($request->all());
			$cId = DB::getPdo()->lastInsertId();

			if ($insertData){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/cash-management'),
					'message' => 'Record added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Record add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function update_cash_credit(Request $request)
	{
		// Retrieve the ID from the request
		$cId = $request->cId;

		// Validate the input data
		$validation = $this->validatorCash($request->all());
		if ($validation->fails()) {
			return response()->json([
				'status' => 'error',
				'errors' => $validation->errors()->toArray()
			]);
		}

		// Update the record in the database
		$update = DB::table('mcash_credit_debits')
			->where('id', $cId)
			->update([
				'propId' => $request->propId,
				'cd_date' => $request->cd_date,
				'particulars' => $request->particulars,
				'cd_amount' => $request->cd_amount,
			]);

		if ($update) {
			return redirect()->route('user.CashManagement')
				->with('success', 'Record updated successfully');
		} else {
			return redirect()->back()
				->with('error', 'Failed to update record. Please try again.');
		}
	}

	protected function createCashDebit(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
        return Cash_credit_debits::create([
            'added_by' => $userId,
            'cd_date' => $data['cd_date'],
			'particulars' => $data['particulars'],
			'cd_amount' => $data['cd_amount'],
			'cd_type' => "dr",
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function save_cash_debit(Request $request)  {

		$validation = $this->validatorCash($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			$insertData = $this->createCashDebit($request->all());
			$cId = DB::getPdo()->lastInsertId();

			if ($insertData){
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/cash'),
					'message' => 'Record added successfully'
				);
				return response()->json($msg);
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/'),
					'message' => 'Record add failed'
				);
				return response()->json($msg);
			}

		}
    }

	public function update_cash_debit(Request $request)  {

		//echo "<pre>";print_r($request->all());exit;
		$cId = $request->id;

		$validation = $this->validatorCash($request->all());
        if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{

			$update = DB::table('cash_credit_debits')
					->where('id', $cId)
					->update(
						 array(
								'cd_date' => $request->cd_date,
								'particulars' => $request->particulars,
								'cd_amount' => $request->cd_amount,
						 )
					);
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/cash'),
				'message' => 'Record updated successfully'
			);
			return response()->json($msg);

		}
    }

	protected function createCashHand(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
        return Cash_hands::create([
            'added_by' => $userId,
            'amount_in_hand' => $data['amount_in_hand'],
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }


	public function update_cashinhand(Request $request)
	{
		$added_by = currentOwnerId();
		$getData = DB::table('cash_hands')
								->where('added_by', '=', $added_by)
								->get()->toArray();
		if(count($getData) == 0 )
		{

			$insertCashHand = $this->createCashHand($request->all());
		}else{

		$update = DB::table('cash_hands')
					->where('added_by', $added_by)
					->update(
						 array(
								'amount_in_hand' => $request->amount_in_hand
						 )
					);
		}
		$msg = array(
			'status' => 'success',
			'class' => 'succ',
			'redirect' => url('/cash'),
			'message' => 'Record updated successfully'
		);
		return response()->json($msg);
	}

	public function bank_statement_upload()
    {
		$userId = currentOwnerId();
		$banks =  DB::table('banks')
						->select(DB::raw('banks.id,banks.bank_name'))
						->where('banks.added_by', '=', $userId)
						->get();
		return view('pages.bank-statement-upload')->with([
			'banks' => $banks,
		]);
    }

	protected function validator_attachment(array $data)
    {
		//echo "<pre>";print_r($data);exit;

		return Validator::make($data, [
			'bank_id' => 'required',
			//'startdate' => 'required',
			//'enddate' => 'required',
			//'bankstatement' => 'required|file|mimes:xlsx,xls,csv,xlsm|max:5120',
			'bankstatement' => 'required|file|max:5120',
		]);

    }

	protected function createBankStatement(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$userId = currentOwnerId();
        return Bank_statements::create([
            'added_by' => $userId,
			'bank_id' => $data['bank_id'],
            'startdate' => $data['startdate'] ?? date('Y-m-d H:i:s'),
			'enddate' => $data['enddate'] ?? date('Y-m-d H:i:s'),
			'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

	public function uploadBank_statement_old(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$added_by = currentOwnerId();
		$bankId = $request->bank_id;
		$validation = $this->validator_attachment($request->all());
		if ($validation->fails())  {
            return response()->json($validation->errors()->toArray());
        }
        else{
			//die("sdzfgsdfg");
			$the_file = $request->file('bankstatement');

		   $spreadsheet = IOFactory::load($the_file->getRealPath());
           $sheet        = $spreadsheet->getActiveSheet();
           $row_limit    = $sheet->getHighestDataRow();
           $column_limit = $sheet->getHighestDataColumn();
           $row_range    = range( 2, $row_limit );
           $column_range = range( 'F', $column_limit );
           $startcount = 2;
           $data = array();
		   if(count($row_range) ==0){
			   $msg = array(
					'status' => 'error',
					'class' => 'err',
					'redirect' => url('/bank-details/'.base64_encode($bankId)),
					'message' => 'Bank statement uploaded failed!'
				);
				return response()->json($msg);
		   }else{
				$insertData = $this->createBankStatement($request->all());
				$statement_id = DB::getPdo()->lastInsertId();
				
				foreach ( $row_range as $row ) {
				   /*$data[] = [
					   'slno' =>$sheet->getCell( 'A' . $row )->getValue(),
					   'cd_date' => $sheet->getCell( 'B' . $row )->getValue(),
					   'debit' => $sheet->getCell( 'C' . $row )->getValue(),
					   'credit' => $sheet->getCell( 'D' . $row )->getValue(),
				   ];*/
				   $tran_date = $sheet->getCell( 'B' . $row )->getValue();
				   $tran_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tran_date)->format('Y-m-d');
				   $purpose =   $sheet->getCell( 'C' . $row )->getValue();
				   $debit  = str_replace(',', '', $sheet->getCell('D' . $row)->getValue());
				   $credit = str_replace(',', '', $sheet->getCell('E' . $row)->getValue());
				   $payment_mode =  strtoupper($sheet->getCell( 'F' . $row )->getValue());
				   $tran_type = "";
				   $tran_amt = 0;
				   $curr_amt = 0;
				   if($debit =="")
				   {
					   $tran_type = "Credit";
					   $tran_amt = $credit;
				   }
				   if($credit =="")
				   {
					   $tran_type = "Debit";
					   $tran_amt = $debit;
				   }

				   $data[] = [
					   'added_by' => $added_by,
					   'bankId' => $bankId,
					   'tran_date' => $tran_date,
					   'payment_mode' => $payment_mode,
					   'tran_amt' => $tran_amt,
					   'tran_type' => $tran_type,
					   'purpose' => $purpose,
					   'curr_amt' => $curr_amt,
				   ];

				   $startcount++;
				}
			   //echo "<pre>";print_r($data); exit;
			   DB::table('bank_trans')->insert($data);
				$msg = array(
					'status' => 'success',
					'class' => 'succ',
					'redirect' => url('/bank-details/'.base64_encode($bankId)),
					'message' => 'Bank statement uploaded successfully'
				);
				return response()->json($msg);
		   }
		}

	}

	
	public function uploadBank_statement(Request $request)
	{
		$added_by = currentOwnerId();
		$bankId   = $request->bank_id;
		$prop_id   = $request->prop_id;

		if (!$request->hasFile('bankstatement')) {
			return response()->json(['bankstatement' => ['File is required.']]);
		}

		$extension = strtolower($request->file('bankstatement')->getClientOriginalExtension());
		if (!in_array($extension, ['xlsx','xls','csv','xlsm'])) {
			return response()->json([
				'bankstatement' => ['Invalid file type. Only Excel files allowed.']
			]);
		}

		try {
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
				$request->file('bankstatement')->getRealPath()
			);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid or corrupted Excel file.'
			]);
		}

		$sheet = $spreadsheet->getActiveSheet();
		$highestRow    = $sheet->getHighestDataRow();
		$highestColumn = $sheet->getHighestDataColumn();

		$headerRow = null;
		$headerMap = [];

		/* ---------------- HEADER DETECTION ---------------- */

		for ($row = 1; $row <= 100; $row++) {

			$rowData = $sheet->rangeToArray("A$row:$highestColumn$row", null, true, false);

			foreach ($rowData[0] as $index => $cellValue) {

				$value = strtolower(trim(preg_replace('/\s+/', ' ', $cellValue)));

				if (preg_match('/(transaction date|txn date|tran date|^date$)/', $value)) {
					$headerMap['date'] = $index;
				}

				if (preg_match('/(cheque|chq|chq.\/ref.no|chq.|reference|ref no)/', $value)) {
					$headerMap['ref_no'] = $index;
				}

				if (preg_match('/(debit|withdrawal|withdrawal amt.|dr amount|^dr$)/', $value)) {
					$headerMap['debit'] = $index;
				}

				if (preg_match('/(credit|deposit|deposit amt.|cr amount|^cr$)/', $value)) {
					$headerMap['credit'] = $index;
				}

				if (preg_match('/(description|details|narration|particular|particulars)/', $value)) {
					$headerMap['purpose'] = $index;
				}
			}

			if (isset($headerMap['date']) &&
				(isset($headerMap['debit']) || isset($headerMap['credit']))) {
				$headerRow = $row;
				break;
			}
		}

		if (!$headerRow) {
			return response()->json([
				'status' => 'error',
				'message' => 'Header row not detected.'
			]);
		}

		//$this->createBankStatement($request->all());

		$data = [];

		/* ---------------- READ TRANSACTIONS ---------------- */

		for ($row = $headerRow + 1; $row <= $highestRow; $row++) {

			$rowData = $sheet->rangeToArray("A$row:$highestColumn$row", null, true, false)[0];

			if (empty(array_filter($rowData))) {
				continue;
			}

			$fullRowText = strtolower(implode(' ', $rowData));

			// Stop at summary
			if (str_contains($fullRowText, 'statement summary')) {
				break;
			}

			$rawDate = trim($rowData[$headerMap['date']] ?? '');

			// Skip ******** lines
			if (preg_match('/^\*+$/', str_replace(' ', '', $rawDate))) {
				continue;
			}

			if (empty($rawDate)) {
				continue;
			}

			/* ---------------- UNIVERSAL DATE PARSER ---------------- */

			$tran_date = null;

			// Excel numeric date
			if (is_numeric($rawDate)) {
				$tran_date = \PhpOffice\PhpSpreadsheet\Shared\Date
					::excelToDateTimeObject($rawDate)
					->format('Y-m-d');
			} else {

				$formats = [
					'd/m/Y',
					'd/m/y',
					'd-m-Y',
					'd-m-y',
					'd-M-Y',
					'd-M-y',
					'd F Y',
					'd F y'
				];

				foreach ($formats as $format) {
					$dateObj = \DateTime::createFromFormat($format, $rawDate);
					if ($dateObj && $dateObj->format($format) == $rawDate) {
						$tran_date = $dateObj->format('Y-m-d');
						break;
					}
				}

				// fallback (last try)
				if (!$tran_date) {
					$timestamp = strtotime($rawDate);
					if ($timestamp) {
						$tran_date = date('Y-m-d', $timestamp);
					}
				}

				if (!$tran_date) {
					continue; // skip invalid date
				}
			}

			/* ---------------- AMOUNT CLEANING ---------------- */

			$debitRaw  = $rowData[$headerMap['debit']]  ?? 0;
			$creditRaw = $rowData[$headerMap['credit']] ?? 0;

			$debit  = preg_replace('/[^0-9.\-]/', '', $debitRaw);
			$credit = preg_replace('/[^0-9.\-]/', '', $creditRaw);

			$debit  = ($debit !== '') ? (float)$debit : 0;
			$credit = ($credit !== '') ? (float)$credit : 0;

			if ($debit == 0 && $credit == 0) {
				continue;
			}

			$tran_type = $debit > 0 ? 'Debit' : 'Credit';
			$tran_amt  = $debit > 0 ? $debit : $credit;

			$data[] = [
				'added_by'  => $added_by,
				'prop_id'  => $prop_id,
				'bankId'    => $bankId,
				'tran_date' => $tran_date,
				'tran_amt'  => round($tran_amt, 2),
				'tran_type' => $tran_type,
				'ref_no'    => trim($rowData[$headerMap['ref_no']] ?? ''),
				'purpose'   => trim($rowData[$headerMap['purpose']] ?? ''),
				'curr_amt'  => 0,
			];
		}

		if (empty($data)) {
			return response()->json([
				'status' => 'error',
				'message' => 'No valid transactions found.'
			]);
		}

		DB::table('bank_trans')->insert($data);

		return response()->json([
			'status'   => 'success',
			'class'    => 'succ',
			'redirect' => url('/bank-details/' . base64_encode($bankId)),
			'message'  => 'Bank statement uploaded successfully'
		]);
	}
	
	public function tally_credit_debit(Request $request)
	{
		$uid = currentOwnerId();
		$id = $request->id;
		$type = $request->type;
		$cash_credit = DB::table('cash_credit_debits')
					->select(DB::raw('cash_credit_debits.added_by,cash_credit_debits.cd_amount,cash_credit_debits.cd_date'))
					->where('cash_credit_debits.id', '=', $id)
					->get();
		$added_by = $cash_credit[0]->added_by;
		$cd_amount = $cash_credit[0]->cd_amount;
		$createdAt = date("Y-m-d",strtotime($cash_credit[0]->cd_date));
		if($type == "credit"){
			//tally data from sales data with date
			$salesRec = DB::table('sales')
					->select(DB::raw('sales.id,sales.inv_num,sales.created_at'))
					->where('sales.added_by', '=', $added_by)
					->whereDate('sales.created_at', '=', $createdAt)
					->get();
			//echo "<pre>";print_r($salesRec);exit;
			$salesAmount = 0;
			if($salesRec->count() !=0){
				$sid = $salesRec[0]->id;
				//$salesDate = date("Y-m-d",strtotime($salesRec[0]->created_at));
				$salesItems = DB::table('sales_values')
						->select(DB::raw('SUM(sales_values.amount) amount'))
						->where('sales_values.sid', '=', $sid)
						->where('sales_values.uid', '=', $added_by)
						->get();
				$salesAmount = $salesItems[0]->amount;

				$array = array();
				foreach($salesRec as $k=>$val)
				{
					$array[$val->inv_num]['inv_num'] = $val->inv_num;
					$array[$val->inv_num]['created_at'] = date("d-m-Y",strtotime($val->created_at));

					$saleItms =  DB::table('sales_values')
							->select(DB::raw('sales_values.amount'))
							->where('sales_values.sid', '=', $val->id)
							->where('sales_values.uid', '=', $added_by)
							->get();
					$array[$val->inv_num]['amount'] = $saleItms;
				}
				$saleItms = json_decode(json_encode($array));
				//echo "<pre>";print_r($saleItms);//exit;
				$pUrl = url('/').'/sales-invoice';
				$html = '<table border="1" class="table table-center table-hover datatable">';
				$html .= '<thead class="thead-light">';
				$html .= '<tr>';
				$html .= '<th>Date</th>';
				$html .= '<th>Inv no.</th>';
				$html .= '<th>Amount</th>';
				$html .= '<th>Action</th>';
				$html .= '</tr>';
				$html .= '</thead>';

				foreach($saleItms as $row) {
					$html .= '<tr>';
					$html .= '<td>' . $row->created_at . '</td>';
					$html .= '<td>' . $row->inv_num . '</td>';
					$html .= '<td><table>';
					foreach($row->amount as $value) {
						$html .= '<tr><td>₹' . $value->amount . '</td></tr>';
					}
					$html .= '</table></td>';
					$html .= '<td><a href="'.$pUrl.'">click</a></td>';
					$html .= '</tr>';
				}
				$html .= '</table>';
				return $html;
			}
			else{
				echo "<h5>Record is failed to matched in sales data</h5>";
			}
		}
		else if($type == "debit"){
			//tally data from purchases data with date
			$purRec = DB::table('purchases')
					->select(DB::raw('purchases.id,purchases.inv_num,purchases.created_at'))
					->where('purchases.added_by', '=', $added_by)
					->whereDate('purchases.created_at', '=', $createdAt)
					->get();
			$purAmount = 0;
			if($purRec->count() !=0){
				$sid = $purRec[0]->id;
				$purItems = DB::table('purchase_values')
						->select(DB::raw('SUM(purchase_values.amount) amount'))
						->where('purchase_values.sid', '=', $sid)
						->where('purchase_values.uid', '=', $added_by)
						->get();
				$purAmount = $purItems[0]->amount;

				$array = array();
				foreach($purRec as $k=>$val)
				{
					$array[$val->inv_num]['inv_num'] = $val->inv_num;
					$array[$val->inv_num]['created_at'] = date("d-m-Y",strtotime($val->created_at));

					$purItms =  DB::table('purchase_values')
							->select(DB::raw('purchase_values.amount'))
							->where('purchase_values.sid', '=', $val->id)
							->where('purchase_values.uid', '=', $added_by)
							->get();
					$array[$val->inv_num]['amount'] = $purItms;
				}
				$purItemsRec = json_decode(json_encode($array));
				//echo "<pre>";print_r($purItemsRec);//exit;
				$sUrl = url('/').'/purchase-invoice';
				$html = '<table border="1" class="table table-center thead-light table-hover datatable">';
				$html .= '<thead class="thead-light">';
				$html .= '<tr>';
				$html .= '<th>Date</th>';
				$html .= '<th>Inv no.</th>';
				$html .= '<th>Amount</th>';
				$html .= '<th>Action</th>';
				$html .= '</tr>';
				$html .= '</thead>';

				foreach($purItemsRec as $row) {
					$html .= '<tr>';
					$html .= '<td>' . $row->created_at . '</td>';
					$html .= '<td>' . $row->inv_num . '</td>';
					$html .= '<td><table>';
					foreach($row->amount as $value) {
						$html .= '<tr><td>₹' . $value->amount . '</td></tr>';
					}
					$html .= '</table></td>';
					$html .= '<td><a href="'.$sUrl.'">click</a></td>';
					$html .= '</tr>';
				}
				$html .= '</table>';
				return $html;
			}
			else{
				echo "<h5>Record is failed to matched in purchases data</h5>";
			}
		}
	}

	
	public function PaymentVoucherList(Request $request)
	{
		$added_by = currentOwnerId();

		//start ca-accountant access
		$req_type = 0;
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$added_by = getAccessCompanyId($request);
			$req_type = 1;
		}
		//end ca-accountant access
		
		$proprietorships = DB::table('proprietorship_profiles')
						->select('id','comp_name')
						->where('userId',$added_by)
						->get();
		$banks = DB::table('banks')
            ->where('added_by', $added_by)
            ->orderBy('bank_name')
            ->get();

		$query = PaymentVoucher::leftJoin(
				'company_profiles as cp',
				'cp.userId',
				'=',
				'payment_vouchers.added_by'
			)
			->leftJoin(
				'proprietorship_profiles as pp',
				'pp.id',
				'=',
				'payment_vouchers.propId'
			)
			->leftJoin(
				'banks as b',
				'b.id',
				'=',
				'payment_vouchers.bank_id'
			)
			->select(
				'payment_vouchers.*',
				'cp.comp_name',
				'pp.comp_name as prop_comp_name',
				'b.bank_name'
			)
			->where('payment_vouchers.added_by', $added_by);
			
		// Proprietorship Company
		if (!empty($request->prop_Id)) {
			$query->where('payment_vouchers.propId', $request->prop_Id);
		}

		// DATE FILTER
		if (!empty($request->from_date)) {
			$query->whereDate('payment_vouchers.date', '>=', $request->from_date);
		}

		if (!empty($request->to_date)) {
			$query->whereDate('payment_vouchers.date', '<=', $request->to_date);
		}
		
		// VOUCHER NO FILTER
		if (!empty($request->voucher_no)) {
			$query->where(
				'payment_vouchers.voucher_no',
				'like',
				'%' . $request->voucher_no . '%'
			);
		}
		
		// BANK FILTER
		if (!empty($request->bank_id)) {
			$query->where('payment_vouchers.bank_id', $request->bank_id);
		}

		// PARTY NAME FILTER
		if (!empty($request->party_name)) {
			$query->where('payment_vouchers.party_name', 'like', '%' . $request->party_name . '%');
		}

		// PAYMENT MODE FILTER
		if (!empty($request->payment_mode)) {
			$query->where('payment_vouchers.payment_mode', $request->payment_mode);
		}

		// PAID STATUS FILTER
		if ($request->filled('is_paid')) {
			$query->where('payment_vouchers.is_paid', $request->is_paid);
		}

		// VOUCHER TYPE FILTER
		if (!empty($request->voucher_type)) {
			$query->where('payment_vouchers.voucher_type', $request->voucher_type);
		}

		// PARTY TYPE FILTER
		if (!empty($request->party_type)) {
			$query->where('payment_vouchers.party_type', $request->party_type);
		}

		$data = $query
			->orderBy('payment_vouchers.id', 'DESC')
			->paginate(10)
			->appends($request->all());

		return view('User.payment-voucher-list', compact('data','proprietorships','banks', 'req_type'));
	}
	
	public function AddPaymentVoucher()
	{
		return view('User.add-payment-voucher');
	}
	

}
