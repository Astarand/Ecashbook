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

class PaymentVoucherController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
	
	//Get bank list
	public function getBankList(Request $request)
	{
		$added_by = currentOwnerId();
		$banks = DB::table('banks')
			->select('id', 'bank_name')
			->where('added_by', $added_by)
			->where('status', 1)
			->orderBy('bank_name', 'ASC')
			->get();

		return response()->json($banks);
	}
	
	//Update date and paid status
	public function quickUpdate(Request $request)
	{
		$request->validate([
			'id'    => 'required',
			'field' => 'required',
			'value' => 'nullable'
		]);

		$voucher = PaymentVoucher::find($request->id);

		if(!$voucher)
		{
			return response()->json([
				'status' => false,
				'message' => 'Voucher not found'
			], 404);
		}

		// update date
		if($request->field == 'date')
		{
			$voucher->date = $request->value;
		}

		// update paid status
		if($request->field == 'is_paid')
		{
			$voucher->is_paid = $request->value;
		}

		$voucher->save();

		return response()->json([
			'status'  => true,
			'message' => 'Updated Successfully'
		]);
	}
	
	public function generatePaymentVoucherNo(Request $request)
	{
		$voucherType = $request->voucher_type;
		$added_by = currentOwnerId();
		$prefix = $voucherType == 'Receipt Voucher' ? 'RV' : 'PV';
		$year = date('y');

		//GET LAST RECORD
		$lastVoucher = PaymentVoucher::where('voucher_type', $voucherType)
			->where('added_by', $added_by)
			->orderBy('id', 'DESC')
			->first();

		// NEXT NUMBER
		$nextNumber = 1;

		if ($lastVoucher) {

			preg_match('/(\d+)$/', $lastVoucher->voucher_no, $matches);

			if (isset($matches[1])) {

				$nextNumber = intval($matches[1]) + 1;
			}
		}

		// =============================
		// FORMAT
		// Example:
		// PV-25-0001
		// RV-25-0001
		// =============================
		$voucherNo = $prefix . '-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

		return response()->json([
			'status'      => true,
			'voucher_no'  => $voucherNo
		]);
	}
	
	//Get party name, party id
	private function getPartyDetails($partyType, $partyValue)
	{
		// OTHER
		if ($partyType == 'Other') {

			return [
				'party_id'   => null,
				'party_name' => $partyValue
			];
		}
		// CUSTOMER
		if ($partyType == 'Customer') {

			$party = DB::table('customers')
				->where('id', $partyValue)
				->select('id', 'cust_name as name')
				->first();
		}
		// VENDOR
		elseif ($partyType == 'Vendor') {

			$party = DB::table('vendors')
				->where('id', $partyValue)
				->select('id', 'vendor_name as name')
				->first();
		}

		// =============================
		// EMPLOYEE
		// =============================
		elseif ($partyType == 'Employee') {

			$party = DB::table('users')
				->where('id', $partyValue)
				->select('id', 'name')
				->first();
		}

		else {

			$party = null;
		}

		return [
			'party_id'   => $party->id ?? null,
			'party_name' => $party->name ?? null
		];
	}
	
	public function store(Request $request)
    {
        $request->validate([
            'voucher_type' => 'required',
            'date' => 'required',
            'voucher_no' => 'required',
            'party_type' => 'required',
            'party_name' => 'required',
            'transaction_details' => 'required',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
			'is_paid'      => 'required|in:0,1',
			'bank_id'      => 'required_if:payment_mode,Bank',
			'narration' => 'required',
        ], [
			'narration.required' => 'Please enter purpose',
		]);

		$added_by = currentOwnerId();
		$propId   = $request->propId ?? null;

		// =============================
		// CREATE FOLDER IF NOT EXISTS
		// =============================
		$uploadPath = public_path('uploads/payment_vouchers');
		if (!File::exists($uploadPath)) {
			File::makeDirectory($uploadPath, 0777, true, true);
		}

		$attachment = null;
		if ($request->hasFile('attachment')) {
			$file = $request->file('attachment');
			$fileName = time() . '_' . rand(1111,9999) . '.' . $file->getClientOriginalExtension();
			$file->move($uploadPath, $fileName);
			$attachment = 'uploads/payment_vouchers/' . $fileName;
		}

		// =============================
		// SAVE DATA
		// =============================
		$partyDetails = $this->getPartyDetails($request->party_type,$request->party_name);
		
		$voucher = new PaymentVoucher();
		$voucher->voucher_type              = $request->voucher_type;
		$voucher->date                      = $request->date;
		$voucher->voucher_no                = $request->voucher_no;
		$voucher->party_type                = $request->party_type;
		$voucher->other_party_type          = $request->other_party_type;
		$voucher->party_id                  = $partyDetails['party_id'];
		$voucher->party_name                = $partyDetails['party_name'];
		$voucher->transaction_details       = $request->transaction_details;
		$voucher->other_transaction_details = $request->other_transaction_details;
		$voucher->invoice_no                = $request->invoice_no;
		$voucher->amount                    = $request->amount;
		$voucher->credit_debit              = ($request->voucher_type == 'Payment Voucher')? 'Debit': 'Credit';
		$voucher->payment_mode              = $request->payment_mode;
		$voucher->bank_id 					= $request->bank_id;
		$voucher->is_paid 					= $request->is_paid;
		$voucher->reference_id              = $request->reference_id;
		$voucher->narration                 = $request->narration;
		$voucher->attachment                = $attachment;
		$voucher->approved_by               = $request->approved_by;
		$voucher->added_by                  = $added_by;
		$voucher->propId                    = $propId;
		$voucher->save();

		return response()->json([
			'status'  => true,
			'message' => 'Saved Successfully'
		]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'voucher_type' => 'required',
            'date' => 'required',
            'voucher_no' => 'required',
            'party_type' => 'required',
            'party_name' => 'required',
            'transaction_details' => 'required',
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required',
			'is_paid'      => 'required|in:0,1',
			'bank_id'      => 'required_if:payment_mode,Bank',
			'narration'	   => 'required',
        ], [
			'narration.required' => 'Please enter purpose',
		]);

        $voucher = PaymentVoucher::findOrFail($id);

        // =============================
		// CREATE FOLDER IF NOT EXISTS
		// =============================
		$uploadPath = public_path('uploads/payment_vouchers');
		if (!File::exists($uploadPath)) {
			File::makeDirectory($uploadPath, 0777, true, true);
		}

		if ($request->hasFile('attachment')) {
			if (!empty($voucher->attachment) && File::exists(public_path($voucher->attachment))) {
				File::delete(public_path($voucher->attachment));
			}

			$file = $request->file('attachment');
			$fileName = time() . '_' . rand(1111,9999) . '.' . $file->getClientOriginalExtension();
			$file->move($uploadPath, $fileName);
			$voucher->attachment = 'uploads/payment_vouchers/' . $fileName;
		}

		// =============================
		// UPDATE FIELDS ONE BY ONE
		// =============================
		$partyDetails = $this->getPartyDetails($request->party_type,$request->party_name);
		$propId   = $request->propId ?? null;
		
		$voucher->voucher_type              = $request->voucher_type;
		$voucher->propId              		= $propId;
		$voucher->date                      = $request->date;
		$voucher->voucher_no                = $request->voucher_no;
		$voucher->party_type                = $request->party_type;
		$voucher->other_party_type          = $request->other_party_type;
		$voucher->party_id                  = $partyDetails['party_id'];
		$voucher->party_name                = $partyDetails['party_name'];
		$voucher->transaction_details       = $request->transaction_details;
		$voucher->other_transaction_details = $request->other_transaction_details;
		$voucher->invoice_no                = $request->invoice_no;
		$voucher->amount                    = $request->amount;
		$voucher->credit_debit              = ($request->voucher_type == 'Payment Voucher')? 'Debit': 'Credit';
		$voucher->payment_mode              = $request->payment_mode;
		$voucher->bank_id 					= $request->bank_id;
		$voucher->is_paid 					= $request->is_paid;
		$voucher->reference_id              = $request->reference_id;
		$voucher->narration                 = $request->narration;
		$voucher->approved_by               = $request->approved_by;

		$voucher->save();

		return response()->json([
			'status'  => true,
			'message' => 'Updated Successfully'
		]);
    }

    public function edit($id)
    {
        return PaymentVoucher::findOrFail($id);
    }

    // =============================
    // GET PARTY LIST
    // =============================
    public function getPartyList(Request $request)
    {
        $type = $request->party_type;
		$added_by = currentOwnerId();
		
        if($type == 'Customer')
        {
            $data = DB::table('customers')
                ->where('userId', $added_by)
                ->select('id', 'cust_name as name')
                ->get();
        }
        elseif($type == 'Vendor')
        {
            $data = DB::table('vendors')
                ->where('userId', $added_by)
                ->select('id', 'vendor_name as name')
                ->get();
        }
        elseif($type == 'Employee')
        {
            $data = DB::table('employees')
                ->join('users', 'employees.empId', '=', 'users.id')
                ->where('employees.added_by', $added_by)
                ->select('users.id', 'users.name')
                ->get();
        }
        else
        {
            $data = [];
        }

        return response()->json($data);
    }

    // =============================
    // GET INVOICE LIST
    // =============================
    public function getInvoiceList(Request $request)
    {
		$added_by = currentOwnerId();
		$type = $request->party_type;
		$party_name = $request->party_name;
        $voucherType = $request->voucher_type;
		$inv_date = $request->inv_date ?? date('Y-m-d');
		$month = date('m', strtotime($inv_date));
		$year  = date('Y', strtotime($inv_date));

        // Receipt Voucher -> Sales Invoice
        if($voucherType == 'Receipt Voucher' && $type =='Customer')
        {
            $data = DB::table('sales')
                ->where('added_by', $added_by)
                ->where('inv_name', $party_name)
				->whereMonth('sales.inv_date', $month)
				->whereYear('sales.inv_date', $year)
                ->select('id', 'inv_num')
                ->get();
        }
        else if($voucherType == 'Payment Voucher' && $type =='Vendor')
        {
            // Payment Voucher -> Purchase Invoice
            $data = DB::table('purchases')
                ->where('added_by', $added_by)
				->where('inv_name', $party_name)
				->whereMonth('purchases.inv_date', $month)
				->whereYear('purchases.inv_date', $year)
                ->select('id', 'inv_num')
                ->get();
        }else{
			$data = collect([]);
		}

        return response()->json($data);
    }

    // =============================
    // GET INVOICE AMOUNT
    // =============================
    public function getInvoiceAmount(Request $request)
    {
        $voucherType = $request->voucher_type;
        $invoiceId = $request->invoice_id;


        if($voucherType == 'Receipt Voucher')
        {
            $invoice = DB::table('sales_values')
					->join('sales', 'sales.id', '=', 'sales_values.sid')
					->where('sales_values.sid', $invoiceId)
					->selectRaw('
						sales_values.sid,
						SUM(sales_values.amount + sales_values.tax_amt + sales_values.gov_pay + sales_values.ser_pay) as total_amount
					')
					->groupBy('sales_values.sid')
					->first();
        }
        else
        {
            $invoice = DB::table('purchase_values')
					->join('purchases', 'purchases.id', '=', 'purchase_values.sid')
					->where('purchase_values.sid', $invoiceId)					
					->selectRaw('
						purchase_values.sid,
						SUM(purchase_values.amount + purchase_values.tax_amt) as total_amount
					')
					->groupBy('purchase_values.sid')
					->first();
        }

        return response()->json([
            'amount' => $invoice->total_amount  ?? 0
        ]);
    }
	
	public function voucherDelete($id)
	{
		try {

			PaymentVoucher::findOrFail($id)->delete();

			return response()->json([
				'status' => true,
				'message' => 'Voucher deleted successfully'
			]);

		} catch (\Exception $e) {

			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			]);
		}
	}



}
