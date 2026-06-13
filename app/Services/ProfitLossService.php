<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Validator;
use App\Models\User;
use App\Models\Journals;
use Carbon\Carbon;

class ProfitLossService
{
    
    public function __construct()
    {
        
    }
	
	public function getCurrentTax($userId, $from, $to)
	{
		// GST from Sales Items
		$salesGst = DB::table('sales as s')
						->join('sales_values as sv', 'sv.sid', '=', 's.id')
						->where('s.added_by', $userId)
						->where('s.status', 1)
						->whereBetween('s.inv_date', [$from, $to])
						->sum(DB::raw('
							COALESCE(sv.tax_amt,0) +
							COALESCE(sv.gov_pay,0) +
							COALESCE(sv.ser_pay,0)
						'));

		// GST from Income
		$incomeGst = DB::table('income')
			->where('addBy', $userId)
			->where('status', 1)
			->whereBetween('dateInput', [$from, $to])
			->sum('gst_amt');

		return round($salesGst + $incomeGst, 2);
	}

	public function getCurrentTaxPriorYear($userId, $from, $to)
	{
		$totalCurrentTax = DB::table('expenses')
        ->where('added_by', $userId)
		->where('expenses.status', 1) //only active records
        ->whereBetween('expense_date', [$from, $to])
        ->where('expense_type', 'current_tax_prior_year')
        ->selectRaw("
            COALESCE(
                SUM(
                    (expense_amt - COALESCE(tds_amount,0))
                ),0
            ) as total
        ")
        ->value('total');

		return (float) $totalCurrentTax;
	}
	
	public function getDeferredTax($userId, $from, $to)
	{
		/*
		|--------------------------------------------------------------------------
		| Deferred Tax Asset (DTA)
		|--------------------------------------------------------------------------
		| Using assets table directly
		|--------------------------------------------------------------------------
		*/
		$deferredTaxAsset = DB::table('assets')
			->where('added_by', $userId)
			->where('isActive', 1)
			->whereBetween('date', [$from, $to])
			->where('assetType', 'non-current')
			->selectRaw("
				COALESCE(
					SUM(
						COALESCE(depreciation_value, 0)
						* COALESCE(depreciation_rate, 0) / 100
					),
				0) as total_asset
			")
			->value('total_asset');

		/*
		|--------------------------------------------------------------------------
		| Deferred Tax Liability (DTL)
		|--------------------------------------------------------------------------
		*/
		$deferredTaxLiability = DB::table('non_current_liabilities as ncl')
			->join('liabilities as l', 'l.id', '=', 'ncl.liabilities_id')
			->where('l.added_by', $userId)
			->where('l.status', 1)
			->whereBetween('l.added_date', [$from, $to])
			->where('ncl.liability_category', 'deferred_tax_liabilities')
			->sum('ncl.dtl_amount');

		/*
		|--------------------------------------------------------------------------
		| Net Deferred Tax
		|--------------------------------------------------------------------------
		*/
		$netDeferredTax = (float)$deferredTaxAsset + (float)$deferredTaxLiability;

		return round($netDeferredTax, 2);
	}
	
	public function getEPS($userId, $fromDate, $toDate, $netProfit)
	{
		/*
		|--------------------------------------------------------------------------
		| Equity Shares Outstanding
		|--------------------------------------------------------------------------
		*/
		$equityData = DB::table('share_holder_fund_liabilities as shf')
			->join('liabilities as l', 'l.id', '=', 'shf.liabilities_id')
			->where('shf.added_by', $userId)
			->where('l.status', 1)
			->where('shf.share_holder_fund_type', 'share_capital')
			->where('shf.share_holder_type', 'equity_share_capital')
			->whereBetween('l.added_date', [$fromDate, $toDate])
			->selectRaw("
				COALESCE(SUM(total_amount),0) as total_amount,
				COALESCE(MAX(face_value_per_share),1) as face_value
			")
			->first();

		$totalAmount = (float) ($equityData->total_amount ?? 0);

		$faceValue = (float) ($equityData->face_value ?? 1);

		// Outstanding Equity Shares
		$equityShares = 0;

		if ($faceValue > 0) {
			$equityShares = $totalAmount / $faceValue;
		}


		/*
		|--------------------------------------------------------------------------
		| Basic EPS
		|--------------------------------------------------------------------------
		*/
		$basicEPS = 0;

		if ($equityShares > 0) {
			$basicEPS = $netProfit / $equityShares;
		}

		/*
		|--------------------------------------------------------------------------
		| Diluted EPS
		|--------------------------------------------------------------------------
		*/
		$totalDilutedShares = $equityShares;

		$dilutedEPS = 0;

		if ($totalDilutedShares > 0) {
			$dilutedEPS = $netProfit / $totalDilutedShares;
		}

		return [
			'equity_shares'      => round($equityShares, 2),
			'basic_eps'          => round($basicEPS, 2),
			'diluted_eps'        => round($dilutedEPS, 2),
		];
	}

}
