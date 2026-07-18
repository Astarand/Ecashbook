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
use App\Services\JournalService;

class CommonController extends Controller
{
    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
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
		// $userId = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}
		$propId = $request->propId;

		$query = DB::table('mcash_credit_debits')
			->where('added_by', $userId);

		if (!empty($propId)) {
			$query->where('propId', $propId);
		}

		$total_credit = (clone $query)->where('cd_type', 'cr')->sum('cd_amount');
		$total_debit  = (clone $query)->where('cd_type', 'dr')->sum('cd_amount');

		$cash_in_hand = $total_credit - $total_debit;

		return response()->json([
			'cash_in_hand' => $cash_in_hand ?? 0
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

		return DB::table('banks')
			->where('added_by', $uid)
			->get();
	}
	
	public function getTradeReceivableAmount(Request $request)
	{
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		// If date passed → use it, else current date
		$date = $request->date 
			? Carbon::parse($request->date) 
			: Carbon::now();

		$start = $date->copy()->startOfMonth();
		$end   = $date->copy()->endOfMonth();

		$data = DB::table('sales as s')
			->leftJoin('sales_values as sv', 's.id', '=', 'sv.sid')
			->where('s.added_by', $uid)
			->whereBetween('s.inv_date', [$start, $end])
			->select(
				DB::raw('SUM(sv.amount) as total_amount'),
				DB::raw('SUM(sv.tax_amt) as total_gst'),
				DB::raw('(SUM(sv.amount) + SUM(sv.tax_amt) - IFNULL(SUM(DISTINCT s.advance_amount),0)) as pending_amount')
			)
			->first();

		return response()->json([
			'total_amount'   => $data->total_amount ?? 0,
			'total_gst'      => $data->total_gst ?? 0,
			'pending_amount' => $data->pending_amount ?? 0,
		]);
	}
	
	public function getAdvanceVendorAmount(Request $request)
	{
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		$date = $request->date 
			? Carbon::parse($request->date) 
			: Carbon::now();

		$startOfMonth = $date->copy()->startOfMonth();
		$endOfMonth   = $date->copy()->endOfMonth();

		$data = DB::table('purchases as p')
			->leftJoin('purchase_values as pv', 'p.id', '=', 'pv.sid') // ensure 'sid' is correct FK
			->where('p.added_by', $uid)
			->whereBetween('p.inv_date', [$startOfMonth, $endOfMonth])
			->select([
				DB::raw('COALESCE(SUM(pv.amount), 0) as total_amount'),
				DB::raw('COALESCE(SUM(pv.tax_amt), 0) as total_gst'),
				DB::raw('COALESCE(SUM(DISTINCT p.advance_amount), 0) as total_advance')
			])
			->first();

		$totalAmount   = $data->total_amount;
		$totalGst      = $data->total_gst;
		$totalAdvance  = $data->total_advance;

		$grandTotal    = $totalAmount + $totalGst;
		$pendingAmount = $grandTotal - $totalAdvance;

		// ✅ Response
		return response()->json([
			'total_amount'   => $totalAdvance,
			'total_gst'      => $totalGst,
			'pending_amount' => $pendingAmount,
		]);
	}
	
	
	
	public function getEmployeeAdvance()
	{
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		// Current Month
		$start = date('Y-m-01');
		$end   = date('Y-m-t');
		$total = DB::table('expenses')
			->where('added_by', $uid)
			->where('expense_type', 'employee_benefits')
			->whereBetween('expense_date', [$start, $end])
			->sum('expense_amt');

		return response()->json([
			'amount' => $total ?? 0
		]);
	}
	
	public function getPrepaidExpense(Request $request)
	{
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}
		$type = $request->expense_type ?? 'rent_expense';
		$start = date('Y-m-01');
		$end   = date('Y-m-t');

		$total = DB::table('expenses')
			->where('added_by', $uid)
			->where('expense_type', $type)
			->whereBetween('expense_date', [$start, $end])
			->sum('expense_amt');

		return response()->json([
			'amount' => $total ?? 0
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
		// $uid = currentOwnerId();
		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$uid = currentOwnerId();
		} else {
			$uid = session('compId'); //ca-accountant access
		}

		$start = Carbon::now()->startOfMonth();
		$end   = Carbon::now()->endOfMonth();

		// Input GST (Purchase)
		$input = DB::table('purchases as p')
			->leftJoin('purchase_values as pv', 'p.id', '=', 'pv.sid')
			->where('p.added_by', $uid)
			->whereBetween('p.inv_date', [$start, $end])
			->sum('pv.tax_amt');

		// Output GST (Sales)
		$output = DB::table('sales as s')
			->leftJoin('sales_values as sv', 's.id', '=', 'sv.sid')
			->where('s.added_by', $uid)
			->whereBetween('s.inv_date', [$start, $end])
			->sum('sv.tax_amt');

		return response()->json([
			'input_itc' => $input,
			'output_gst' => $output,
			'net_payable' => $output - $input
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
		// $userId = currentOwnerId();

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}

		//Handle month (fallback to current)
		$date = !empty($request->month) ? Carbon::parse($request->month): Carbon::now();

		$month = $date->month;
		$year  = $date->year;

		$bussType = $request->buss_type ?? null;

		// ================= SALES =================
		$totalSales = DB::table('sales_values as sv')
			->join('sales as s', 's.id', '=', 'sv.sid')
			->join('products as p', 'p.id', '=', 'sv.prod_id')
			->where('s.added_by', $userId)
			->whereMonth('s.inv_date', $month)
			->whereYear('s.inv_date', $year)
			->when($bussType && $bussType != 'mixed', function ($query) use ($bussType) {
				$query->where('p.item_type', $bussType);
			})
			->selectRaw('COALESCE(SUM(sv.amount - sv.disc_amt), 0) as net_sales')
			->value('net_sales');

		// ================= COGS =================
		$totalCOGS = DB::table('sales_values as sv')
			->join('sales as s', 's.id', '=', 'sv.sid')
			->join('products as p', 'p.id', '=', 'sv.prod_id')
			->where('s.added_by', $userId)
			->where('p.added_by', $userId)
			->whereMonth('s.inv_date', $month)
			->whereYear('s.inv_date', $year)
			->when($bussType && $bussType != 'mixed', function ($query) use ($bussType) {
				$query->where('p.item_type', $bussType);
			})
			->selectRaw('COALESCE(SUM(sv.quantity * p.purchase_price), 0) as cogs')
			->value('cogs');

		// ================= FINAL =================
		$grossProfit = $totalSales - $totalCOGS;

		return response()->json([
			'month' => $date->format('Y-m'),
			'gross_profit' => round($grossProfit, 2)
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
