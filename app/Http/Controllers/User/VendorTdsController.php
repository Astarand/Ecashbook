<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Redirect;
// use DB;
// use Auth;
use Validator;
use App\Models\Purchases;
use App\Models\Purchases_values;
use App\Models\Customers;
use App\Models\Vendor;
use App\User;
use App\Http\Controllers\Helper;
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\AuditLogger;
use PDF;

class VendorTdsController extends Controller
{
			
	public function index()
    {
        return view('User.vendor-tds.vendor_purchase');
    }

    /* ==========================================================
       FILTER & CALCULATE TDS
    ========================================================== */
	
	function getFinancialYearDates($date)
	{
		$year = date('Y', strtotime($date));
		$month = date('m', strtotime($date));

		if ($month < 4) {
			return [
				'from' => ($year - 1) . '-04-01',
				'to'   => $year . '-03-31'
			];
		}

		return [
			'from' => $year . '-04-01',
			'to'   => ($year + 1) . '-03-31'
		];
	}
	
	
	public function filter(Request $request)
	{
		$from = $request->from_date;
		$to   = $request->to_date;
		$addedBy = currentOwnerId();

		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			
			$addedBy = getAccessCompanyId($request);
			
		}

		$fy = $this->getFinancialYearDates($from);
		//echo "<pre>";print_r($fy);exit;

		$data = [];

		// ================= TDS RULE =================
		$tdsRule = DB::table('tds_rules')
			->where('module', 'Purchase')
			->where('status', 1)
			->first();

		$tdsRate = $tdsRule->tds_rate ?? 0;
		$thresholdLimit = $tdsRule->threshold_limit ?? 0;

		// ================= BUYER TURNOVER =================
		$buyerTurnover = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->where('p.added_by', $addedBy)
			->whereBetween('p.inv_date', [$fy['from'], $fy['to']])
			->selectRaw("
				SUM(
					COALESCE(pv.amount,0)
				  + COALESCE(pv.tax_amt,0)
				  - COALESCE(pv.disc_amt,0)
				) AS buyer_turnover
			")
			->value('buyer_turnover');

		$buyerEligible = ((float)$buyerTurnover) > 100000000; // 10 Cr

		// ================= VENDOR SUMMARY (UNCHANGED) =================
		$vendors = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->join('vendors as v', 'v.id', '=', 'p.inv_name')
			->where('p.added_by', $addedBy)
			->whereBetween('p.inv_date', [$fy['from'], $fy['to']])
			->groupBy('v.id', 'v.vendor_name', 'v.vendor_pan')
			->selectRaw("
				v.id,
				v.vendor_name,
				v.vendor_pan,
				SUM(
					COALESCE(pv.amount,0)
				  + COALESCE(pv.tax_amt,0)
				  - COALESCE(pv.disc_amt,0)
				) AS fy_purchase
			")
			->get();
			
		//echo "<pre>";print_r($vendors);exit;

		// ================= DETAILS QUERY (NEW) =================
		$details = DB::table('purchases as p')
					->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
					->join('vendors as v', 'v.id', '=', 'p.inv_name')
					->leftJoin('products as pr', function ($join) use ($addedBy) {
						$join->on('pr.id', '=', 'pv.prod_id')
							 ->where('pr.added_by', '=', $addedBy);
					})
					->where('p.added_by', $addedBy)
					->whereBetween('p.inv_date', [$fy['from'], $fy['to']])
					->selectRaw("
						v.id as vendor_id,
						p.inv_num,
						CASE 
							WHEN pr.item_type = 'service' THEN pr.service_name
							ELSE pr.item_name
						END as item_name,
						(COALESCE(pv.amount,0)
						+ COALESCE(pv.tax_amt,0)
						- COALESCE(pv.disc_amt,0)) AS line_amount
					")
					->orderBy('p.inv_date', 'desc')
					->get();
	

		// ================= GROUP DETAILS BY VENDOR =================
		//echo "<pre>";print_r($details);exit;
		$detailMap = [];
		foreach ($details as $d) {
			$detailMap[$d->vendor_id][] = [
				'invoice_no'   => $d->inv_num,
				'item_name' => $d->item_name,
				'amount'       => round($d->line_amount, 2),
			];
		}

		// ================= FINAL MERGE =================
		foreach ($vendors as $row) {

			$tdsApplicable = false;
			$tdsAmount = 0;

			if ($row->fy_purchase > $thresholdLimit) {
				$tdsApplicable = true;
				$tdsAmount = round(($row->fy_purchase * $tdsRate) / 100, 2);
			}

			$data[] = [
				'vendor_name' => $row->vendor_name,
				'pan_no'      => $row->vendor_pan,
				'fy_purchase' => round($row->fy_purchase, 2),

				'tds_rate'    => $tdsApplicable ? $tdsRate : 0,
				'tds_amount'  => $tdsAmount,
				'tds_applicable' => $tdsApplicable ? 'YES' : 'NO',

				//attach product + invoice details
				'details' => $detailMap[$row->id] ?? []
			];
		}

		return response()->json([
			'buyer_turnover' => round($buyerTurnover, 2),
			'buyer_eligible' => $buyerEligible,
			'data'           => $data
		]);
	}
	
	public function filter_bkp(Request $request)
    {
        $from = $request->from_date;
        $to   = $request->to_date;
        $addedBy = currentOwnerId();
        $fy = $this->getFinancialYearDates($from);
		$data = array();
        //   TDS RATE
		$tdsRule = DB::table('tds_rules')
			->where('module', 'Purchase')
			->where('status', 1)
			->first();

		$tdsRate = $tdsRule->tds_rate ?? 0;
		$thresholdLimit = $tdsRule->threshold_limit ?? 0; 

		$buyerTurnover = DB::table('purchases as p')
			->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
			->where('p.added_by', $addedBy)
			->whereBetween('p.inv_date', [$fy['from'], $fy['to']])
			->selectRaw("
				SUM(
					COALESCE(pv.amount,0)
				  + COALESCE(pv.tax_amt,0)
				  - COALESCE(pv.disc_amt,0)
				) AS buyer_turnover
			")
			->value('buyer_turnover');

		$buyerEligible = ((float)$buyerTurnover) > 100000000; //10 cr
		
		$vendors = DB::table('purchases as p')
					->join('purchase_values as pv', 'pv.sid', '=', 'p.id')
					->join('vendors as v', 'v.id', '=', 'p.inv_name')
					->where('p.added_by', $addedBy)
					->whereBetween('p.inv_date', [$fy['from'], $fy['to']])
					->groupBy('v.id', 'v.vendor_name', 'v.vendor_pan')
					->selectRaw("
						v.id,
						v.vendor_name,
						v.vendor_pan,
						SUM(
							COALESCE(pv.amount,0)
						  + COALESCE(pv.tax_amt,0)
						  - COALESCE(pv.disc_amt,0)
						) AS fy_purchase
					")
					->get();
		foreach ($vendors as $row) {

			$tdsApplicable = false;
			$tdsAmount = 0;
			if ($row->fy_purchase <= $thresholdLimit) {
				$tdsApplicable = false;
				$tdsAmount = 0;
			} else {
				$tdsApplicable = true;
				$tdsAmount = round(($row->fy_purchase * $tdsRate) / 100, 2);
			}

			$data[] = [
				'vendor_name' => $row->vendor_name,
				'pan_no'      => $row->vendor_pan,
				'fy_purchase' => round($row->fy_purchase,2),
				'tds_rate'        => $tdsApplicable ? $tdsRate : 0,
				'tds_amount'  => $tdsAmount,
				'tds_applicable' => $tdsApplicable ? 'YES' : 'NO',
			];
		}

		 return response()->json([
            'buyer_turnover' => round($buyerTurnover, 2),
            'buyer_eligible' => $buyerEligible,
            'data'           => $data
        ]);

    }

    /* ==========================================================
       PDF DOWNLOAD
    ========================================================== */
    public function downloadPdf(Request $request)
    {
        $response = $this->filter($request)->getData(true);

        $pdf = PDF::loadView('User.vendor-tds.vendor_purchase_pdf', [
            'rows'           => $response['data'],
            'buyer_turnover' => $response['buyer_turnover'],
            'from'           => $request->from_date,
            'to'             => $request->to_date,
        ])->setOptions([
			'isRemoteEnabled' => true
		]);

        return $pdf->download('Vendor_TDS_Report.pdf');
    }
			
   

}
