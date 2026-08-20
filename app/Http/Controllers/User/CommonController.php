<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Redirect;
// use DB;
// use Auth;
// use Validator;
use App\User;
use App\Models\Assets;
use App\Models\Vendor;
use Carbon\Carbon;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Helpers\AuditLogger;
use App\Services\BalanceSheetService;

class CommonController extends Controller
{
    public function __construct(BalanceSheetService $balanceSheetService)
    {
        $this->balanceSheetService = $balanceSheetService;
    }
	
    public function getDropdownTypes(Request $request)
	{
		$dropdown_name = $request->dropdown_name;
		$module = $request->module;

		$expenseTypes = DB::table('dropdown_values')
			->where('module', $module)
			->where('dropdown_name', $dropdown_name)
			->where('status', 1)
			->orderBy('sort_order')
			->get(['option_value', 'option_text','type']);

		return response()->json($expenseTypes);
	}
	
	public function getTaxRule(Request $request)
	{
		$rule = DB::table('tax_deduction_masters')
			->where('accounting_module', 'Expense')
			->where('expense_type', $request->expense_type)
			->where('expense_head', $request->expense_head)
			->where('is_active', 1)
			->first();

		if (!$rule) {
			return response()->json([
				'status' => false
			]);
		}

		return response()->json([
			'status'          => true,
			'tax_treatment'   => $rule->tax_treatment,
			'allowed_ratio'   => $rule->allowed_ratio,
			'allow_start'     => $rule->allow_start,
			'allow_end'       => $rule->allow_end
		]);
	}
	
	//start new
	public function getCashInHand(Request $request)
	{
		$type = 'Cash in Hand';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$propId = $request->propId;

		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString(); //Current Month
		$cashInHand = $this->balanceSheetService->getCurrentAssetAmount($type,$userId,$startDate,$endDate);

		return response()->json([
			'success'       => true,
			'cash_in_hand'  => $cashInHand
		]);
	}
	
	public function getBankAccounts()
	{
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}

		$startDate = '2020-04-01';
		$endDate   = now()->endOfMonth()->toDateString(); //Current Month

		$banks = DB::table('banks as b')
			->leftJoin('payment_vouchers as pv', function ($join) use ($startDate, $endDate, $uid) {
				$join->on('pv.bank_id', '=', 'b.id')
					->where('pv.added_by', $uid)
					->whereIn('pv.payment_mode', ['Bank', 'UPI'])
					->whereBetween('pv.date', [$startDate, $endDate]);
			})
			->where('b.added_by', $uid)
			->select(
				'b.id',
				'b.bank_name'
			)
			->selectRaw("
				COALESCE(b.curr_bal, 0)
				+
				COALESCE(
					SUM(
						CASE
							WHEN pv.credit_debit = 'Credit'
							THEN COALESCE(pv.amount, 0)
							ELSE 0
						END
					),
					0
				)
				-
				COALESCE(
					SUM(
						CASE
							WHEN pv.credit_debit = 'Debit'
							THEN COALESCE(pv.amount, 0)
							ELSE 0
						END
					),
					0
				) AS curr_bal
			")
			->groupBy(
				'b.id',
				'b.bank_name',
				'b.curr_bal'
			)
			->orderBy('b.bank_name')
			->get();

		return $banks;

	}
	
	public function getTradeReceivableAmount(Request $request)
	{
		$type = 'Trade Receivables';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
	
		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString();  //Current Month
		$amount = $this->balanceSheetService->getCurrentAssetAmount($type,$uid,$startDate,$endDate);

		return response()->json([
			'total_amount'   => $amount ?? 0,
		]);
	}
	
	public function getAdvanceVendorAmount(Request $request)
	{
		$type = 'Advance to Vendor';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		
		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString(); //Current Month
		$amount = $this->balanceSheetService->getCurrentAssetAmount($type,$uid,$startDate,$endDate);

		// ✅ Response
		return response()->json([
			'total_amount'   => $amount,
		]);
	}
	
	
	
	public function getEmployeeAdvance()
	{
		$type = 'Employee Advance';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
			
		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString(); //Current Month
		$amount = $this->balanceSheetService->getCurrentAssetAmount($type,$uid,$startDate,$endDate);

		return response()->json([
			'amount' => $amount ?? 0
		]);
	}
	
	public function getPrepaidExpense(Request $request)
	{
		$type = 'Prepaid Expenses';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}

		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString(); //Current Month
		$amount = $this->balanceSheetService->getCurrentAssetAmount($type,$uid,$startDate,$endDate);

		return response()->json([
			'amount' => $amount ?? 0
		]);
	}
	
	public function getVendorsITC()
	{
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}

		$vendors = DB::table('vendors')
			->where('userId', $uid)
			->where('status', 1)
			->select('id', 'vendor_name', 'vendor_gstin')
			->get();

		return response()->json($vendors);
	}
	
	public function getVendorPurchaseInvoices(Request $request)
	{
		$vendorId = $request->vendor_id;

		$invoices = DB::table('purchases')
			->where('inv_name', $vendorId)
			->select('id', 'inv_num')
			->get();

		return response()->json($invoices);
	}
	
	public function getGSTSummary()
	{
		$type = 'Input GST Credit';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
			
		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString(); //Current Month
		$amount = $this->balanceSheetService->getCurrentAssetAmount($type,$uid,$startDate,$endDate);

		return response()->json([
			'input_itc' => $amount,
		]);
	}
	
	public function calculateMonthlyTDS(Request $request)
	{
		// $userId = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
			
		$date = !empty($request->month) ? Carbon::parse($request->month): Carbon::now();

		$month = $date->month;
		$year  = $date->year;

		// ================= FINANCIAL YEAR =================
		// FY: April to March
		$fy = ($month >= 4) 
			? $year . '-' . ($year + 1)
			: ($year - 1) . '-' . $year;

		// ================= EMPLOYEE TDS =================
		$payslips = DB::table('user_payslip')
			->join('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
			->where('employees.added_by', $userId)
			->where('user_payslip.month', $month)
			->where('user_payslip.financial_year', $fy)
			->pluck('user_payslip.emp_salary_slip_response');

		$employeeTds = 0;

		foreach ($payslips as $jsonStr) {
			$json = json_decode($jsonStr, true);

			if (!empty($json['visible_data']['final_salary_calculation']['tds'])) {
				$employeeTds += (float) $json['visible_data']['final_salary_calculation']['tds'];
			}
		}

		// ================= VENDOR PURCHASE =================
		$vendorPurchase = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->where('p.added_by', $userId)
			->whereMonth('p.inv_date', $month)
			->whereYear('p.inv_date', $year)
			->selectRaw("
				COALESCE(SUM(
					COALESCE(pv.amount,0)
				  + COALESCE(pv.tax_amt,0)
				  - COALESCE(pv.disc_amt,0)
				), 0) as total_purchase
			")
			->value('total_purchase');

		// ================= TDS RULE =================
		$tdsRule = DB::table('tds_rules')
			->where('module', 'Purchase')
			->where('status', 1)
			->first();

		$tdsRate   = $tdsRule->tds_rate ?? 0;
		$threshold = $tdsRule->threshold_limit ?? 0;

		// ================= VENDOR TDS =================
		$vendorTdsAmount = 0;

		if ($vendorPurchase > $threshold) {
			$vendorTdsAmount = round(($vendorPurchase * $tdsRate) / 100, 2);
		}

		// ================= FINAL =================
		$grossAmount = round($employeeTds + $vendorTdsAmount, 2);

		return response()->json([
			'month' => $date->format('Y-m'),
			'financial_year' => $fy,
			'employee_tds' => $employeeTds,
			'vendor_purchase' => $vendorPurchase,
			'vendor_tds' => $vendorTdsAmount,
			'tds_gross_amount' => $grossAmount
		]);
	}
	

	public function calculateGrossProfit(Request $request)
	{
		$type = 'Inventories';
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}

		$startDate = date('2020-04-01');
		$endDate = now()->endOfMonth()->toDateString(); //Current Month
		$amount = $this->balanceSheetService->getCurrentAssetAmount($type,$userId,$startDate,$endDate);

		return response()->json([
			'gross_profit' => round($amount, 2)
		]);
	}
	
	//Export Assets in Excel
	public function exportAssets(Request $request)
	{
		$from = $request->from_date;
		$to   = $request->to_date;
		$type = $request->asset_type;
		$userId = currentOwnerId();
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}

		$query = DB::table('assets as a')
			->leftJoin('assets_currs as ad', 'ad.aid', '=', 'a.id')
			->leftJoin('vendors as v', 'v.id', '=', 'a.vendor_id')
			->leftJoin('vendors as vn', 'vn.id', '=', 'a.cwip_vendor_id')
			->where('a.added_by', $userId);

		if ($from && $to) {
			$query->whereBetween('a.date', [$from, $to]);
		}

		if ($type) {
			$query->where('a.assetType', $type);
		}

		$data = $query->select(
			'a.asset_id','a.asset_name','a.assetType','a.currentAssetType','a.nonCurrentAssetType',
			'a.asset_category','a.asset_code','a.location','a.department',

			DB::raw('v.vendor_name as vendor_name'),

			'a.invoice_no','a.invoice_date','a.purchase_date',
			'a.invoice_value','a.pay_status','a.advance_amt','a.capitalization_date','a.put_to_use_date',
			'a.asset_status','a.depreciation_start_date','a.depreciation_frequency',
			'a.useful_life_years','a.depreciation_method','a.depreciation_rate',
			'a.residual_value','a.project_name','a.project_code','a.cwip_asset_type',
			'a.expense_type',

			DB::raw('vn.vendor_name as cwip_vendor_name'),

			'a.cwip_invoice_no','a.cwip_expense_date',
			'a.cwip_amount','a.completion_percentage','a.capitalization_status',
			'a.work_order_ref','a.tds_applicable','a.tds_percent','a.tds_amt','a.tds_id',
			'a.gst_applicable','a.gst_rate','a.gst_amt','a.gst_trans',

			'ad.cash_amount','ad.bank_id','ad.bank_balance','ad.amount',
			'ad.amount_vendor','ad.employee_advance_amount','ad.prepaid_amt',
			'ad.itc_amt','ad.tds_gross_amount','ad.gross_profit'
		)->get();

		// ================= HEADER =================
		$excelData[] = [
			'Asset ID','Asset Name','Asset Type','Current Type','Non Current Type',
			'Category','Code','Location','Department','Vendor','Invoice No','Invoice Date',
			'Purchase Date','Invoice Value','Pay Status','Advance Amt','Capitalization Date','Put To Use',
			'Status','Dep Start','Dep Frequency','Life Years','Method','Rate','Residual',
			'Project Name','Project Code','CWIP Type','Expense Type','CWIP Vendor',
			'CWIP Invoice','CWIP Date','CWIP Amount','Completion %','Capital Status',
			'Work Order','TDS Applicable','TDS %','TDS Amt','TDS ID','GST Applicable',
			'GST Rate','GST Amount','GST Type',

			'Cash','Bank ID','Bank Balance','Amount','Vendor Amount','Employee Advance','Prepaid Amount',
			'ITC Amount','TDS Gross','Gross Profit'
		];

		foreach ($data as $row) {
			$excelData[] = (array) $row;
		}

		// ================= FILE NAME =================
		$fileName = 'assets_';
		if ($from && $to) {
			$fileName .= $from . '_to_' . $to;
		}
		$fileName .= '.xlsx';

		// ================= EXPORT =================
		return Excel::download(new class($excelData) implements FromArray, WithStyles {

			protected $data;

			public function __construct($data)
			{
				$this->data = $data;
			}

			public function array(): array
			{
				return $this->data;
			}

			// ✅ HEADER STYLE
			public function styles(Worksheet $sheet)
			{
				return [
					1 => [ // first row (header)
						'font' => [
							'bold' => true,
							'color' => ['rgb' => 'FFFFFF'],
						],
						'fill' => [
							'fillType' => Fill::FILL_SOLID,
							'startColor' => ['rgb' => '4CAF50'], // green header
						],
					],
				];
			}

		}, $fileName);
	}
	
	public function getBankList()
	{
		$userId = currentOwnerId();

		$banks = DB::table('banks')
			->select('id', 'bank_name')
			->where('added_by', $userId)
			->where('status', 1)
			->orderBy('bank_name')
			->get();

		return response()->json($banks);
	}
	
	public function getTdsRuleLiability(Request $request)
	{
		$rule = DB::table('tds_rules')
			->where('module', $request->module)
			->where('category', $request->category)
			->where('status', 1)
			->first();

		return response()->json([
			'status' => true,
			'rule' => $rule
		]);
	}
	
	//end new

}
