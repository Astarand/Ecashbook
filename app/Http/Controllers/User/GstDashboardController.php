<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Sales;
use App\Models\Sales_values;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use App\Models\Gst_logins;
use Helper;
use App\Services\GstService;
use App\Services\WhiteBooksGstService;
use Illuminate\Support\Facades\Cookie;
use DateTime;
use DatePeriod;
use DateInterval;

class GstDashboardController extends Controller
{

	protected $gstService;
	protected $whiteBooksGstService;

	public function __construct(GstService $gstService, WhiteBooksGstService $whiteBooksGstService)
	{
		$this->gstService = $gstService;
		$this->whiteBooksGstService = $whiteBooksGstService;
	}

	public function GstDashboard()
	{
		$uid = currentOwnerId();

		$company = DB::table('company_profiles')
			->where('userId', $uid)
			->select('comp_name', 'gst_no')
			->first();

		return view('User.gst-dashboard', compact('company'));
	}
	
	public function getPeriods(Request $request)
	{
		$request->validate([
			'financial_year' => 'required',
			'period_frequency' => 'required|in:Monthly,Quarterly,Half-Yearly,Yearly',
		]);

		[$startYear, $endYear] =
			explode('-', $request->financial_year);

		$startYear = (int) $startYear;
		$endYear   = (int) $endYear;

		$periods = [];

		switch ($request->period_frequency) {

			case 'Monthly':

				$months = [
					['04', 'April'],
					['05', 'May'],
					['06', 'June'],
					['07', 'July'],
					['08', 'August'],
					['09', 'September'],
					['10', 'October'],
					['11', 'November'],
					['12', 'December'],
					['01', 'January'],
					['02', 'February'],
					['03', 'March'],
				];

				foreach ($months as $month) {

					$year = in_array($month[0], ['01', '02', '03']) ? $endYear : $startYear;

					$periods[] = [
						'value' =>
							$year . '-' . $month[0],

						'label' =>
							$month[1] . ' ' . $year,
					];
				}

				break;


			case 'Quarterly':

				$periods = [

					[
						'value' => 'Q1',
						'label' => 'Q1 (April - June) ' . $startYear
					],

					[
						'value' => 'Q2',
						'label' => 'Q2 (July - September) ' . $startYear
					],

					[
						'value' => 'Q3',
						'label' => 'Q3 (October - December) ' . $startYear
					],

					[
						'value' => 'Q4',
						'label' => 'Q4 (January - March) ' . $endYear
					],

				];

				break;


			case 'Half-Yearly':

				$periods = [

					[
						'value' => 'H1',
						'label' => 'H1 (April - September) ' . $startYear
					],

					[
						'value' => 'H2',
						'label' => 'H2 (October - March) ' . $startYear. '-' . $endYear
					],

				];

				break;


			case 'Yearly':

				$periods = [
					[
						'value' => $startYear . '-' . $endYear,
						'label' => 'Full Year '. $startYear. '-'. $endYear,
					]
				];

				break;

		}

		return response()->json([
			'success' => true,
			'periods' => $periods,
		]);
	}
	
	public function getTransactions(Request $request)
	{
		$request->validate([
			'financial_year' => 'required',
			'period_frequency' => 'required',
			'period' => 'required',
		]);

		$uid = currentOwnerId();
		[$startDate, $endDate] = $this->getGstPeriodDates($request->financial_year,$request->period_frequency,$request->period);
		$transactions = collect();
		//SALES
		/*$sales = DB::table('sales as s')
			->leftJoin('sales_values as sv','sv.sid','=','s.id')
			->leftJoin('customers as c','c.id','=','s.inv_name')
			->where('s.added_by', $uid)
			->where('s.status', 1)
			->whereBetween('s.inv_date',[$startDate, $endDate])
			->select(
				's.id',
				's.inv_num as invoice_no',
				's.inv_date as invoice_date',
				'c.cust_name as party_name',
				'c.cust_gst_no as gstin',
				DB::raw('COALESCE(sv.amount,0) as taxable_amount'),
				DB::raw('COALESCE(sv.tax_amt,0) as gst_amount'),
				DB::raw('(COALESCE(sv.amount,0) + COALESCE(sv.tax_amt,0)) as invoice_total'),
				's.pay_status as status'
			)
			->get()
			->map(function ($row) {
				$row->module = 'Sales';
				return $row;
			});*/
		$sales = DB::table('sales as s')
					->leftJoinSub(
						DB::table('sales_values')
							->select(
								'sid',
								DB::raw('SUM(COALESCE(amount, 0)) as taxable_amount'),
								DB::raw('SUM(COALESCE(tax_amt, 0)) as gst_amount')
							)
							->groupBy('sid'),
						'sv',
						'sv.sid',
						'=',
						's.id'
					)
					->leftJoin('customers as c', 'c.id', '=', 's.inv_name')
					->where('s.added_by', $uid)
					->where('s.status', 1)
					->whereBetween('s.inv_date', [$startDate, $endDate])
					->select(
						's.id',
						's.inv_num as invoice_no',
						's.inv_date as invoice_date',
						'c.cust_name as party_name',
						'c.cust_gst_no as gstin',
						DB::raw('COALESCE(sv.taxable_amount, 0) as taxable_amount'),
						DB::raw('COALESCE(sv.gst_amount, 0) as gst_amount'),
						DB::raw('(COALESCE(sv.taxable_amount, 0) + COALESCE(sv.gst_amount, 0)) as invoice_total'),
						's.pay_status as status'
					)
					->get()
					->map(function ($row) {
						$row->module = 'Sales';
						return $row;
					});

		$transactions = $transactions->merge($sales);
		
		//Sales Credit Note
		$creditNotes = DB::table('vouchers as v')
						->leftJoin('customers as c', 'c.id', '=', 'v.v_name')
						->where('v.added_by', $uid)
						->where('v.note_type', 'Credit')
						//->where('v.status', 1)
						->whereBetween('v.inv_date', [$startDate, $endDate])
						->select(
							'v.id',
							'v.v_num as invoice_no',
							'v.inv_date as invoice_date',
							'c.cust_name as party_name',
							'c.cust_gst_no as gstin',
							DB::raw('COALESCE(v.taxable_value, 0) as taxable_amount'),
							DB::raw('(
								COALESCE(v.cgst_amount, 0) +
								COALESCE(v.sgst_amount, 0) +
								COALESCE(v.igst_amount, 0)
							) as gst_amount'),
							DB::raw('COALESCE(v.total_amt, 0) as invoice_total'),
							'v.pay_status as status'
						)
						->get()
						->map(function ($row) {
							$row->module = 'Credit Note';
							return $row;
						});

		$transactions = $transactions->merge($creditNotes);
		//echo "<pre>";print_r($transactions);exit;
		
		// INCOME
		$income = DB::table('income as i')
			->where('i.addBy', $uid)
			->where('i.status', 1)
			->whereBetween('i.dateInput', [$startDate, $endDate])
			->select(
				'i.id',
				'i.invoice_no as invoice_no',
				'i.dateInput as invoice_date',
				'i.customer_name as party_name',
				DB::raw('NULL as gstin'),
				DB::raw('COALESCE(i.amount, 0) as taxable_amount'),
				DB::raw('COALESCE(i.gst_amt, 0) as gst_amount'),
				DB::raw('(COALESCE(i.amount, 0) + COALESCE(i.gst_amt, 0)) as invoice_total'),
				'i.pay_status as status'
			)
			->get()
			->map(function ($row) {
				$row->module = 'Income';
				return $row;
			});

		$transactions = $transactions->merge($income);


		//PURCHASE
		/*$purchases = DB::table('purchases as p')
			->leftJoin('purchase_values as pv','pv.sid','=','p.id')
			->leftJoin('vendors as v','v.id','=','p.inv_name')
			->where('p.added_by', $uid)
			->where('p.status', 1)
			->whereBetween('p.inv_date',[$startDate, $endDate])
			->select(
				'p.id',
				'p.inv_num as invoice_no',
				'p.inv_date as invoice_date',
				'v.vendor_name as party_name',
				'v.vendor_gstin as gstin',
				DB::raw('COALESCE(pv.amount,0) as taxable_amount'),
				DB::raw('COALESCE(pv.tax_amt,0) as gst_amount'),
				DB::raw('(COALESCE(pv.amount,0) + COALESCE(pv.tax_amt,0)) as invoice_total'),
				'p.pay_status as status'
			)
			->get()
			->map(function ($row) {
				$row->module = 'Purchase';
				return $row;
			});*/
		$purchases = DB::table('purchases as p')
					->leftJoinSub(
						DB::table('purchase_values')
							->select(
								'sid',
								DB::raw('SUM(COALESCE(amount, 0)) as taxable_amount'),
								DB::raw('SUM(COALESCE(tax_amt, 0)) as gst_amount')
							)
							->groupBy('sid'),
						'pv',
						'pv.sid',
						'=',
						'p.id'
					)
					->leftJoin('vendors as v', 'v.id', '=', 'p.inv_name')
					->where('p.added_by', $uid)
					->where('p.status', 1)
					->whereBetween('p.inv_date', [$startDate, $endDate])
					->select(
						'p.id',
						'p.inv_num as invoice_no',
						'p.inv_date as invoice_date',
						'v.vendor_name as party_name',
						'v.vendor_gstin as gstin',
						DB::raw('COALESCE(pv.taxable_amount, 0) as taxable_amount'),
						DB::raw('COALESCE(pv.gst_amount, 0) as gst_amount'),
						DB::raw('(COALESCE(pv.taxable_amount, 0) + COALESCE(pv.gst_amount, 0)) as invoice_total'),
						'p.pay_status as status'
					)
					->get()
					->map(function ($row) {
						$row->module = 'Purchase';
						return $row;
					});

		$transactions = $transactions->merge($purchases);

		//PURCHASE DEBIT NOTE
		$debitNotes = DB::table('voucher_purchases as vp')
						->leftJoin('purchases as p', 'p.inv_num', '=', 'vp.inv_number')
						->leftJoin('vendors as v', 'v.id', '=', 'p.inv_name')
						->where('vp.added_by', $uid)
						->where('vp.note_type', 'Debit')
						//->where('vp.status', 1)
						->whereBetween('vp.inv_date', [$startDate, $endDate])
						->select(
							'vp.id',
							'vp.v_num as invoice_no',
							'vp.inv_date as invoice_date',

							'v.vendor_name as party_name',
							'v.vendor_gstin as gstin',

							DB::raw('COALESCE(vp.taxable_value, 0) as taxable_amount'),

							DB::raw('(
								COALESCE(vp.cgst_amount, 0) +
								COALESCE(vp.sgst_amount, 0) +
								COALESCE(vp.igst_amount, 0)
							) as gst_amount'),

							DB::raw('COALESCE(vp.total_amt, 0) as invoice_total'),
							'vp.pay_status as status'
						)
						->get()
						->map(function ($row) {
							$row->module = 'Debit Note';
							return $row;
						});

		$transactions = $transactions->merge($debitNotes);

		//EXPENSE
		$expenses = DB::table('expenses as e')
				->leftJoin('vendors as v', 'v.id', '=', 'e.vendor_id')
				->where('e.added_by', $uid)
				->whereBetween('e.expense_date', [$startDate, $endDate])
				->select(
					'e.id',
					'e.exp_invno as invoice_no',
					'e.expense_date as invoice_date',
					DB::raw("COALESCE(v.vendor_name, '-') as party_name"),
					DB::raw("COALESCE(v.vendor_gstin, '-') as gstin"),
					DB::raw('COALESCE(e.expense_amt, 0) as taxable_amount'),
					DB::raw('COALESCE(e.total_gst, 0) as gst_amount'),
					DB::raw('(COALESCE(e.expense_amt, 0) + COALESCE(e.total_gst, 0)) as invoice_total'),
					'e.payment_status as status'
				)
				->get()
				->map(function ($row) {
					$row->module = 'Expense';
					return $row;
				});

		$transactions = $transactions->merge($expenses);
		
		//ASSETS
		$assets = DB::table('assets as a')
					->leftJoin('vendors as v', 'v.id', '=', 'a.vendor_id')
					->where('a.added_by', $uid)
					->where('a.assetType', 'non-current')
					->whereBetween('a.date', [$startDate, $endDate])
					->select(
						'a.id',
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN a.cwip_invoice_no
								ELSE a.invoice_no
							END as invoice_no
						"),
						'a.date as invoice_date',
						'v.vendor_name as party_name',
						'v.vendor_gstin as gstin',

						// Taxable Amount
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN COALESCE(a.cwip_amount, 0)
								ELSE COALESCE(a.invoice_value, 0)
							END as taxable_amount
						"),

						// GST Amount
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN 0
								ELSE COALESCE(a.gst_amt, 0)
							END as gst_amount
						"),

						// Invoice Total
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN COALESCE(a.cwip_amount, 0)
								ELSE (
									COALESCE(a.invoice_value, 0) +
									COALESCE(a.gst_amt, 0)
								)
							END as invoice_total
						"),

						// Payment Status
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN a.cwip_pay_status
								ELSE a.pay_status
							END as status
						")
					)
					->get()
					->map(function ($row) {
						$row->module = 'Asset';
						return $row;
					});

		$transactions = $transactions->merge($assets);
		
		/*
		|--------------------------------------------------------------------------
		| SUMMARY CALCULATION
		|--------------------------------------------------------------------------
		*/

		// Total All Taxable Amount
		$totalSales = $sales->sum(function ($row) {
			return (float) $row->taxable_amount;
		});
		
		$totalIncome = $income->sum(function ($row) {
			return (float) $row->taxable_amount;
		});
		
		$totalCreditNotes = $creditNotes->sum(function ($row) {
			return (float) $row->taxable_amount;
		});

		$totalPurchase = $purchases->sum(function ($row) {
			return (float) $row->taxable_amount;
		});

		// Output GST  = Sales GST + Income GST - CreditNote GST
		$salesGst = $sales->sum(function ($row) {
			return (float) $row->gst_amount;
		});
		
		$incomeGst = $income->sum(function ($row) {
			return (float) $row->gst_amount;
		});
		
		$creditNotesGst = $creditNotes->sum(function ($row) {
			return (float) $row->gst_amount;
		});

		$outputGst = ($salesGst + $incomeGst - $creditNotesGst);

		// Input GST = Purchase GST + Expense GST + Asset GST - DebitNote
		$purchaseInputGst = $purchases->sum(function ($row) {
			return (float) $row->gst_amount;
		});
		
		$debitNotesGst = $debitNotes->sum(function ($row) {
			return (float) $row->gst_amount;
		});

		$expenseInputGst = $expenses->sum(function ($row) {
			return (float) $row->gst_amount;
		});

		$assetInputGst = $assets->sum(function ($row) {
			return (float) $row->gst_amount;
		});

		$inputGst = ($purchaseInputGst + $expenseInputGst + $assetInputGst - $debitNotesGst);

		// Net GST Liability
		$netGstLiability = $outputGst - $inputGst;
		//GST PAID
		$gstPaid = $this->gstService->gstPaidByComp($startDate, $endDate, $uid);
		//GST PAYABLE / REFUND
		$gstPayable = $netGstLiability - $gstPaid;

		return response()->json([
			'success' => true,
			'start_date' => $startDate,
			'end_date' => $endDate,
			'summary' => [
				'total_sales' => round($totalSales, 2),
				'total_purchase' => round($totalPurchase, 2),
				'output_gst' => round($outputGst, 2),
				'input_gst' => round($inputGst, 2),
				'net_gst_liability' => round($netGstLiability, 2),
				'gst_paid' => round($gstPaid, 2),
				'gst_payable' => round($gstPayable, 2),
			],
			'data' => $transactions->sortByDesc('invoice_date')->values(),

		]);
	}
	
	public function getInvoiceDetails(Request $request)
	{
		$request->validate([
			'module' => 'required|in:Sales,Credit Note,Purchase,Debit Note,Income,Expense,Asset',
			'id'     => 'required|integer',
		]);

		$uid = currentOwnerId();

		$module = $request->module;
		$id     = $request->id;

		try 
		{
			$invoice = null;
			$items   = collect();
			//SALES
			if ($module === 'Sales') {

				$invoice = DB::table('sales as s')
					->leftJoin('customers as c','c.id','=','s.inv_name')
					->where('s.id', $id)
					->where('s.added_by', $uid)
					->select(
						's.id',
						's.inv_num as invoice_no',
						's.inv_date as invoice_date',
						's.inv_name as party_id',
						'c.cust_name as party_name',
						'c.cust_gst_no as gstin',
						's.pay_status as status'
					)
					->first();

				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Sales invoice not found.'
					], 404);

				}

				$items = DB::table('sales_values as sv')
						->leftJoin('products as p', 'p.id', '=', 'sv.prod_id')
						->where('sv.sid', $id)
						->select(
							'sv.*',
							DB::raw("CASE
								WHEN p.item_type = 'service' THEN COALESCE(p.service_name, '-')
								ELSE COALESCE(p.item_name, '-')
							END as item_name"),
							DB::raw("CASE
								WHEN p.item_type = 'service' THEN COALESCE(p.sac_code, '-')
								ELSE COALESCE(p.hsn_code, '-')
							END as hsn_sac")
						)
						->get();

				$totals = DB::table('sales_values')
					->where('sid', $id)
					->selectRaw('
						COALESCE(SUM(amount),0) as taxable_amount,
						COALESCE(SUM(tax_amt),0) as gst_amount,
						COALESCE(SUM(amount),0) +
						COALESCE(SUM(tax_amt),0) as invoice_total
					')
					->first();

			}
			// SALES CREDIT NOTE
			else if ($module === 'Credit Note') {

				$invoice = DB::table('vouchers as v')
					->leftJoin('sales as s', 's.inv_num', '=', 'v.invoice_number')
					->leftJoin('customers as c', 'c.id', '=', 'v.v_name')
					->where('v.id', $id)
					->where('v.added_by', $uid)
					->where('v.note_type', 'Credit')
					->select(
						'v.id',
						'v.v_num as invoice_no',
						'v.note_date as invoice_date',
						'v.v_name as party_id',
						'c.cust_name as party_name',
						'c.cust_gst_no as gstin',
						'v.pay_status as status',

						// Original Sales Invoice
						'v.invoice_number as original_invoice_no'
					)
					->first();

				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Sales credit note not found.'
					], 404);

				}

				// Credit Note Items / Details
				// If one row represents one credit note, use the voucher itself
				$items = DB::table('vouchers as v')
					->where('v.id', $id)
					->where('v.note_type', 'Credit')
					->select(
						'v.id',
						'v.prodservname',
						'v.hsn_sac_code',
						'v.gst_rate',
						'v.taxable_value as taxable_amount',
						'v.cgst_amount',
						'v.sgst_amount',
						'v.igst_amount',
						'v.qty_return_adjusted',
						'v.rate_unit_price',
						'v.discount',
						'v.total_amt'
					)
					->get();

				// Credit Note Totals
				$totals = DB::table('vouchers')
					->where('id', $id)
					->where('note_type', 'Credit')
					->selectRaw('
						COALESCE(taxable_value, 0) as taxable_amount,

						(
							COALESCE(cgst_amount, 0) +
							COALESCE(sgst_amount, 0) +
							COALESCE(igst_amount, 0)
						) as gst_amount,

						COALESCE(total_amt, 0) as invoice_total
					')
					->first();

			}
			// INCOME
			elseif ($module === 'Income') {

				$invoice = DB::table('income as i')
					->leftJoin('customers as c', 'c.id', '=', 'i.customer_name')
					->where('i.id', $id)
					->where('i.addBy', $uid)
					->select(
						'i.id',

						// Invoice Number
						'i.invoice_no',

						// Invoice Date
						'i.dateInput as invoice_date',

						// Party
						'i.customer_name as party_id',
						'c.cust_name as party_name',
						'c.cust_gst_no as gstin',

						// Amount
						'i.amount as taxable_amount',
						'i.gst_amt as gst_amount',

						// Total
						DB::raw('
							COALESCE(i.amount, 0) +
							COALESCE(i.gst_amt, 0) as invoice_total
						'),

						// Status
						'i.pay_status as status',

						// Income Details
						'i.incomeType',
						'i.categoryIncome',
						'i.other_income',
						'i.receivable_amt',
						'i.adjust_amt',
						'i.advance_amt',
						'i.due_date',
						'i.pay_mode',

						// GST Details
						'i.gst_applicable',
						'i.gst_rate',
						'i.gst_trans',
						'i.gst_allocation',

						// TDS Details
						'i.tds_applicable',
						'i.tds_percentage',
						'i.tds_amount'
					)
					->first();


				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Income entry not found.'
					], 404);

				}


				// INCOME ITEMS
				$items = collect([
					(object) [
						'description' => $invoice->categoryIncome
							?? $invoice->incomeType
							?? 'Income',

						'income_type' => $invoice->incomeType,

						'category' => $invoice->categoryIncome,

						'amount' => $invoice->taxable_amount ?? 0,

						'gst_amount' => $invoice->gst_amount ?? 0,

						'total' => $invoice->invoice_total ?? 0
					]
				]);


				// INCOME TOTALS
				$totals = (object) [
					'taxable_amount' => $invoice->taxable_amount ?? 0,
					'gst_amount' => $invoice->gst_amount ?? 0,
					'invoice_total' => $invoice->invoice_total ?? 0
				];

			}
			//PURCHASE
			elseif ($module === 'Purchase') {

				$invoice = DB::table('purchases as p')
					->leftJoin('vendors as v','v.id','=','p.inv_name')
					->where('p.id', $id)
					->where('p.added_by', $uid)
					->select(
						'p.id',
						'p.inv_num as invoice_no',
						'p.inv_date as invoice_date',
						'p.inv_name as party_id',
						'v.vendor_name as party_name',
						'v.vendor_gstin as gstin',
						'p.shipping_cost',
						'p.pay_status as status'
					)
					->first();


				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Purchase invoice not found.'
					], 404);

				}

				$items = DB::table('purchase_values as pv')
						->leftJoin('products as p', 'p.id', '=', 'pv.prod_id')
						->where('pv.sid', $id)
						->select(
							'pv.*',
							DB::raw("CASE
								WHEN p.item_type = 'service' THEN COALESCE(p.service_name, '-')
								ELSE COALESCE(p.item_name, '-')
							END as item_name"),
							DB::raw("CASE
								WHEN p.item_type = 'service' THEN COALESCE(p.sac_code, '-')
								ELSE COALESCE(p.hsn_code, '-')
							END as hsn_sac")
						)
						->get();

				$totals = DB::table('purchase_values')
					->where('sid', $id)
					->selectRaw('
						COALESCE(SUM(amount),0) as taxable_amount,
						COALESCE(SUM(tax_amt),0) as gst_amount,
						COALESCE(SUM(amount),0) +
						COALESCE(SUM(tax_amt),0) as invoice_total
					')
					->first();

				$shippingCost = $invoice->shipping_cost ?? 0;
				$totals->invoice_total =($totals->invoice_total ?? 0) + $shippingCost;
			}
			// PURCHASE DEBIT NOTE
			elseif ($module === 'Debit Note') {

				$invoice = DB::table('voucher_purchases as vp')
					->leftJoin('purchases as p', 'p.inv_num', '=', 'vp.inv_number')
					->leftJoin('vendors as v', 'v.id', '=', 'vp.v_name')
					->where('vp.id', $id)
					->where('vp.added_by', $uid)
					->where('vp.note_type', 'Debit')
					->select(
						'vp.id',
						'vp.v_num as invoice_no',
						'vp.note_date as invoice_date',
						'vp.v_name as party_id',
						'v.vendor_name as party_name',
						'v.vendor_gstin as gstin',
						'vp.pay_status as status',

						// Original Purchase Invoice
						'vp.inv_number as original_invoice_no'
					)
					->first();

				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Purchase debit note not found.'
					], 404);

				}

				// Debit Note Items / Details
				$items = DB::table('voucher_purchases as vp')
					->where('vp.id', $id)
					->where('vp.added_by', $uid)
					->where('vp.note_type', 'Debit')
					->select(
						'vp.id',
						'vp.prodservname',
						'vp.hsn_sac_code',
						'vp.gst_rate',
						'vp.taxable_value as taxable_amount',
						'vp.cgst_amount',
						'vp.sgst_amount',
						'vp.igst_amount',
						'vp.qty_return_adjusted',
						'vp.rate_unit_price',
						'vp.discount',
						'vp.total_amt'
					)
					->get();

				// Debit Note Totals
				$totals = DB::table('voucher_purchases as vp')
					->where('vp.id', $id)
					->where('vp.added_by', $uid)
					->where('vp.note_type', 'Debit')
					->selectRaw('
						COALESCE(vp.taxable_value, 0) as taxable_amount,

						(
							COALESCE(vp.cgst_amount, 0) +
							COALESCE(vp.sgst_amount, 0) +
							COALESCE(vp.igst_amount, 0)
						) as gst_amount,

						COALESCE(vp.total_amt, 0) as invoice_total
					')
					->first();

			}
			//EXPENSE
			elseif ($module === 'Expense') {

				$invoice = DB::table('expenses as e')
					->leftJoin('vendors as v','v.id','=','e.vendor_id')
					->where('e.id', $id)
					->where('e.added_by', $uid)
					->select(
						'e.id',
						DB::raw(
							"CONCAT('EXP-', e.id) as invoice_no"
						),
						'e.expense_date as invoice_date',
						'e.vendor_id as party_id',
						DB::raw("
							CASE
								WHEN e.employee_id IS NOT NULL
									THEN CONCAT('Employee - ', e.employee_id)
								WHEN e.vendor_id IS NOT NULL
									THEN v.vendor_name
								ELSE 'Expense Entry'
							END as party_name
						"),

						'v.vendor_gstin as gstin',

						'e.expense_cat',
						'e.expense_type',
						'e.mode_of_expense',
						'e.expense_amt as taxable_amount',
						'e.total_gst as gst_amount',

						DB::raw('COALESCE(e.expense_amt,0) +COALESCE(e.total_gst,0) as invoice_total'),
						'e.payment_status as status',
						
						'e.vendor_pan',
						'e.tds_applicable',
						'e.tds_percentage',
						'e.tds_amount',
						'e.tds_section',

						'e.gst_applicable',
						'e.gst_trans',
						'e.gst_rate',
						'e.itc_eligibility',

						'e.deduction_amount',
						'e.tax_treatment',
						'e.allowed_ratio',
						'e.rebate_amt'
					)
					->first();


				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Expense entry not found.'
					], 404);

				}

				$items = collect([
					(object) [
						'description' =>$invoice->expense_cat ?? 'Expense',
						'expense_type' =>$invoice->expense_type,
						'amount' =>$invoice->taxable_amount,
						'gst_amount' =>$invoice->gst_amount,
						'total' =>$invoice->invoice_total
					]
				]);


				$totals = (object) [
					'taxable_amount' =>$invoice->taxable_amount ?? 0,
					'gst_amount' =>$invoice->gst_amount ?? 0,
					'invoice_total' =>$invoice->invoice_total ?? 0
				];

			}
			// ASSET
			elseif ($module === 'Asset') {

				$invoice = DB::table('assets as a')
					->leftJoin('vendors as v', 'v.id', '=', 'a.vendor_id')
					->where('a.id', $id)
					->where('a.added_by', $uid)
					->select(
						'a.id',
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN a.cwip_invoice_no
								ELSE a.invoice_no
							END as invoice_no
						"),
						'a.date as invoice_date',
						'a.vendor_id as party_id',
						'v.vendor_name as party_name',
						'v.vendor_gstin as gstin',
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN COALESCE(a.cwip_amount, 0)
								ELSE COALESCE(a.invoice_value, 0)
							END as taxable_amount
						"),
						DB::raw('COALESCE(a.gst_amt, 0) as gst_amount'),
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN COALESCE(a.cwip_amount, 0) + COALESCE(a.gst_amt, 0)
								ELSE COALESCE(a.invoice_value, 0) + COALESCE(a.gst_amt, 0)
							END as invoice_total
						"),
						DB::raw("
							CASE
								WHEN a.nonCurrentAssetType = 'Capital Work in Progress'
									THEN a.cwip_pay_status
								ELSE a.pay_status
							END as status
						"),

						'a.asset_name',
						'a.assetType',
						'a.nonCurrentAssetType',

						// Depreciation
						'a.depreciation_method',
						'a.depreciation_rate',
						'a.useful_life_years',
						'a.residual_value'
					)
					->first();


				if (!$invoice) {

					return response()->json([
						'success' => false,
						'message' => 'Asset entry not found.'
					], 404);

				}


				// COMMON ITEMS FOR ASSET AND CWIP
				$items = collect([
					(object) [
						'description' => $invoice->asset_name ?? 'Asset',
						'asset_type' => $invoice->assetType,
						'non_current_asset_type' => $invoice->nonCurrentAssetType,
						'amount' => $invoice->taxable_amount ?? 0,
						'gst_amount' => $invoice->gst_amount ?? 0,
						'total' => $invoice->invoice_total ?? 0
					]
				]);


				// COMMON TOTALS FOR ASSET AND CWIP
				$totals = (object) [
					'taxable_amount' => $invoice->taxable_amount ?? 0,
					'gst_amount' => $invoice->gst_amount ?? 0,
					'invoice_total' => $invoice->invoice_total ?? 0
				];
			}
			/*
			|--------------------------------------------------------------------------
			| GST BREAKUP
			|--------------------------------------------------------------------------
			*/

			$gstBreakup = ['cgst' => 0,'sgst' => 0,'igst' => 0,];

			//SALES GST
			if ($module === 'Sales') {
				$gst = DB::table('sales_values')
					->where('sid', $id)
					->selectRaw("
						COALESCE(SUM(
							CASE
								WHEN LOWER(gst_trans) = 'intrastate'
								THEN tax_amt / 2
								ELSE 0
							END
						), 0) AS cgst,

						COALESCE(SUM(
							CASE
								WHEN LOWER(gst_trans) = 'intrastate'
								THEN tax_amt / 2
								ELSE 0
							END
						), 0) AS sgst,

						COALESCE(SUM(
							CASE
								WHEN LOWER(gst_trans) = 'interstate'
								THEN tax_amt
								ELSE 0
							END
						), 0) AS igst
					")
					->first();

				if ($gst) {

					$gstBreakup = [
						'cgst' => $gst->cgst ?? 0,
						'sgst' => $gst->sgst ?? 0,
						'igst' => $gst->igst ?? 0,
					];
				}
			}
			//Credit Note
			elseif ($module === 'Credit Note') {

				$gst = DB::table('vouchers')
					->where('id', $id)
					->where('note_type', 'Credit')
					->selectRaw("
						COALESCE(cgst_amount, 0) AS cgst,
						COALESCE(sgst_amount, 0) AS sgst,
						COALESCE(igst_amount, 0) AS igst
					")
					->first();

				if ($gst) {

					$gstBreakup = [
						'cgst' => $gst->cgst ?? 0,
						'sgst' => $gst->sgst ?? 0,
						'igst' => $gst->igst ?? 0,
					];
				}
			}
			//Income
			elseif ($module === 'Income') {
				$gstTrans = strtolower($invoice->gst_trans ?? '');
				$gstAmount = $invoice->gst_amount ?? 0;
				if (str_contains($gstTrans, 'igst')) {
					$gstBreakup['igst'] = $gstAmount;
				} else {
					$gstBreakup['cgst'] = $gstAmount / 2;
					$gstBreakup['sgst'] = $gstAmount / 2;
				}
			}
			//PURCHASE GST
			else if ($module === 'Purchase') {

				$gst = DB::table('purchase_values')
					->where('sid', $id)
					->selectRaw("
						COALESCE(SUM(
							CASE
								WHEN LOWER(gst_trans) = 'intrastate'
								THEN tax_amt / 2
								ELSE 0
							END
						), 0) AS cgst,

						COALESCE(SUM(
							CASE
								WHEN LOWER(gst_trans) = 'intrastate'
								THEN tax_amt / 2
								ELSE 0
							END
						), 0) AS sgst,

						COALESCE(SUM(
							CASE
								WHEN LOWER(gst_trans) = 'interstate'
								THEN tax_amt
								ELSE 0
							END
						), 0) AS igst
					")
					->first();

				if ($gst) {

					$gstBreakup = [
						'cgst' => $gst->cgst ?? 0,
						'sgst' => $gst->sgst ?? 0,
						'igst' => $gst->igst ?? 0,
					];
				}
			}
			//Debit Note
			elseif ($module === 'Debit Note') {
				$gst = DB::table('voucher_purchases')
					->where('id', $id)
					->where('note_type', 'Debit')
					->selectRaw("
						COALESCE(cgst_amount, 0) AS cgst,
						COALESCE(sgst_amount, 0) AS sgst,
						COALESCE(igst_amount, 0) AS igst
					")
					->first();

				if ($gst) {
					$gstBreakup = [
						'cgst' => $gst->cgst ?? 0,
						'sgst' => $gst->sgst ?? 0,
						'igst' => $gst->igst ?? 0,
					];
				}
			}
			//EXPENSE GST
			elseif ($module === 'Expense') {
				$gstTrans = strtolower($invoice->gst_trans ?? '');
				$gstAmount = $invoice->gst_amount ?? 0;
				if (str_contains($gstTrans, 'igst')) {
					$gstBreakup['igst'] = $gstAmount;
				} else {
					$gstBreakup['cgst'] = $gstAmount / 2;
					$gstBreakup['sgst'] = $gstAmount / 2;
				}
			}
			//ASSET GST
			elseif ($module === 'Asset') {
				$gstTrans = strtolower($invoice->gst_trans ?? '');
				$gstAmount = $invoice->gst_amount ?? 0;
				if (str_contains($gstTrans, 'igst')) {
					$gstBreakup['igst'] = $gstAmount;
				} else {
					$gstBreakup['cgst'] = $gstAmount / 2;
					$gstBreakup['sgst'] = $gstAmount / 2;
				}
			}

			return response()->json([

				'success' => true,
				'module' => $module,
				'invoice' => [
					'id' => $invoice->id,
					'invoice_no' => $invoice->invoice_no,
					'invoice_date' => $invoice->invoice_date,
					'party_id' => $invoice->party_id ?? null,
					'party_name' => $invoice->party_name ?? '',
					'gstin' => $invoice->gstin ?? '',
					'status' => $invoice->status ?? '',
					'taxable_amount' => $totals->taxable_amount ?? 0,
					'gst_amount' => $totals->gst_amount ?? 0,
					'invoice_total' => $totals->invoice_total ?? 0,
				],
				'gst_breakup' => $gstBreakup,
				'items' => $items,
			]);

		} catch (\Throwable $e) {
			\Log::error(
				'GST Dashboard Invoice Details Error',
				[
					'module' => $module,
					'id' => $id,
					'error' => $e->getMessage(),
					'trace' => $e->getTraceAsString()
				]
			);

			return response()->json([
				'success' => false,
				'message' =>'Unable to load invoice details.',
				'error' =>$e->getMessage()
			], 500);
		}
	}
	
	private function getGstPeriodDates($financialYear, $frequency, $period)
	{
		// Example: 2026-2027
		[$startYear, $endYear] = explode('-', $financialYear);

		$startYear = (int) $startYear;
		$endYear   = (int) $endYear;

		switch ($frequency) {

			case 'Monthly':
				$date = Carbon::createFromFormat(
					'Y-m',
					$period
				);

				return [
					$date->copy()->startOfMonth()->toDateString(),
					$date->copy()->endOfMonth()->toDateString()
				];
			/*
			|--------------------------------------------------------------------------
			| Quarterly
			| Q1 = April - June
			| Q2 = July - September
			| Q3 = October - December
			| Q4 = January - March
			|--------------------------------------------------------------------------
			*/
			case 'Quarterly':

				switch ($period) {
					case 'Q1':
						return [
							$startYear . '-04-01',
							$startYear . '-06-30'
						];
					case 'Q2':
						return [
							$startYear . '-07-01',
							$startYear . '-09-30'
						];
					case 'Q3':
						return [
							$startYear . '-10-01',
							$startYear . '-12-31'
						];
					case 'Q4':
						return [
							$endYear . '-01-01',
							$endYear . '-03-31'
						];
				}

				break;

			case 'Half-Yearly':
				if ($period === 'H1') {
					return [
						$startYear . '-04-01',
						$startYear . '-09-30'
					];
				}
				if ($period === 'H2') {
					return [
						$startYear . '-10-01',
						$endYear . '-03-31'
					];
				}

				break;

			case 'Yearly':
				return [
					$startYear . '-04-01',
					$endYear . '-03-31'
				];
		}

		return [
			null,
			null
		];
	}

}
