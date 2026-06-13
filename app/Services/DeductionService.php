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

class DeductionService
{

    public function __construct()
    {
        
    }
	
	/*
    |--------------------------------------------------------------------------
    | GET FINANCIAL YEAR
    |--------------------------------------------------------------------------
    */
    public function getFinancialYear($date)
    {
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));

        return ($month >= 4)
            ? $year . '-' . ($year + 1)
            : ($year - 1) . '-' . $year;
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN UNIVERSAL METHOD
    |--------------------------------------------------------------------------
    */
    public function calculateAndApply(
		$module,
		$table,
		$columnId,
		$baseAmount,
		$date
	) {
		// =========================
		// 1. FY detection
		// =========================
		$fy = null;

		if (!empty($date)) {
			$fy = $this->getFinancialYear($date);
		}

		// =========================
		// 2. Get deductions (FY wise)
		// =========================
		$deductions = DB::table('deduction_masters')
			->where('linked_module', $module)
			->where('active_status', 1)
			->when($fy, function ($q) use ($fy) {
				$q->where('applicable_fy', $fy);
			})
			->get();

		$results = [];

		foreach ($deductions as $deduction) 
		{
			$calculated = 0;

			if ($deduction->deduction_type === 'Percentage') {
				$calculated = ($baseAmount * $deduction->amount) / 100;
			}
			elseif ($deduction->deduction_type === 'Fixed') {
				$calculated = $deduction->amount;
			}
			elseif ($deduction->deduction_type === 'Formula') {
				$calculated = $baseAmount;
			}
			elseif ($deduction->deduction_type === 'Auto') {
				$calculated = $this->applySlabLogic($calculated,$deduction->income_tax_section ?? null,$fy);
			}

			// =========================
			// 7. SLAB + REBATE ENGINE
			// =========================
			if ($deduction->deduction_type !== 'Auto') {
				$calculated = $this->applySlabLogic($calculated,$deduction->income_tax_section ?? null,$fy);
			}

			// =========================
			// 8. UPDATE TABLE
			// =========================
			DB::table($table)
				->where('id', $columnId)
				->update([
					'deduction_amount' => $calculated
				]);

			// =========================
			// 9. RESULT
			// =========================
			$results[] = [
				'deduction' => $deduction->deduction_name,
				'fy' => $fy,
				'amount' => round($calculated, 2)
			];
		}

		return $results;
	}

    /**
	 * SLAB + REBATE ENGINE
	 */
	private function applySlabLogic($amount, $section, $fy = null)
	{
		if (empty($section)) {
			return $amount;
		}

		$slab = DB::table('income_tax_slabs')
			->where('rebate_section', $section)
			->where('status', 1)
			->when($fy, function ($q) use ($fy) {
				$q->where('applicable_fy', $fy);
			})
			->first();

		if (!$slab) {
			return $amount;
		}

		$from = $slab->income_slab_from ?? 0;
		$to   = $slab->income_slab_to ?? 0;
		$rate = $slab->tax_rate ?? 0;
		$rebateLimit = $slab->rebate_limit ?? null;

		// =========================
		// CASE 1: BELOW SLAB START
		// =========================
		if ($amount <= $from) {
			return 0;
		}

		// =========================
		// CASE 2: ABOVE SLAB LIMIT
		// 👉 DIRECT REBATE LIMIT RETURN
		// =========================
		if ($to > 0 && $amount > $to) {
			return $rebateLimit ?? 0;
		}

		// =========================
		// CASE 3: NORMAL SLAB CALCULATION
		// =========================
		$taxable = $amount - $from;
		$calculated = ($taxable * $rate) / 100;

		// =========================
		// FINAL REBATE CAP (safety)
		// =========================
		if (!is_null($rebateLimit) && $calculated > $rebateLimit) {
			$calculated = $rebateLimit;
		}

		return $calculated;
	}
}
