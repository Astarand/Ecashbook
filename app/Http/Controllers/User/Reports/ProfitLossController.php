<?php

namespace App\Http\Controllers\User\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use DB;
// use Auth;
use Validator;
use App\User;
use App\Models\Banks;
use App\Models\Bank_trans;
use App\Models\Bank_statements;
use Carbon\Carbon;
use PDF;
use App\Services\ExpensesService;
use App\Services\AssetsService;
use App\Services\LiabilitiesService;
use App\Services\ReportsService;
use App\Services\ProfitLossService;

class ProfitLossController extends Controller
{
	private $expensesService;
	private $assetsService;
	private $liabilitiesService;
	private $reportsService;
	private $profitLossService;

    public function __construct(ProfitLossService $profitLossService,ReportsService $reportsService, ExpensesService $expensesService, AssetsService $assetsService, LiabilitiesService $liabilitiesService)
    {
        $this->reportsService = $reportsService;
        $this->expensesService = $expensesService;
        $this->assetsService = $assetsService;
        $this->liabilitiesService = $liabilitiesService;
        $this->profitLossService = $profitLossService;
		$this->middleware('auth');
    }

    public function ProfitLoss(request $request)
    {	
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		checkCoreAccess('Profit & Loss');
        return view('User.Reports.profit-loss-report');
    }
	
	public function profit_loss_data(Request $request)
    {
		$userId = currentOwnerId();

		if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5) {
			$userId = currentOwnerId();
		} else {
			$userId = session('compId'); //ca-accountant access
		}

		$financial_year = $request->financial_year;
		$period_type = $request->period_type;
		$dynamic_period = $request->dynamic_period;
		// Current period
		[$startDate, $endDate] = $this->getDateRange($financial_year,$period_type,$dynamic_period);
		// Previous period
		[$prevStartDate, $prevEndDate] = $this->getPreviousDateRange($startDate, $endDate, $period_type);

		$pYearData = $this->profitLossService->calculatePL($prevStartDate, $prevEndDate, $userId, $period_type);
		$cYearData = $this->profitLossService->calculatePL($startDate, $endDate, $userId, $period_type);

        return response()->json([
            'success' => true,
            'start_date' => $startDate,
            'end_date' => $endDate,
			'previousYearData' => $pYearData,
            'currentYearData' => $cYearData,
            
        ]);
	}
	
	
	public function getNetProfit($totalRevenue, $expenses, $tax)
	{
		$totalExpenses = array_sum($expenses);
		$totalTax =
			($tax['current_tax'] ?? 0) +
			($tax['current_tax_expenses_prior_years'] ?? 0) +
			($tax['deferred_tax'] ?? 0) +
			($tax['minimum_alternate_tax'] ?? 0);

		return round($totalRevenue - $totalExpenses - $totalTax, 2);
	}
	
	private function getPreviousDateRange($startDate, $endDate)
	{
		$start = Carbon::parse($startDate);
		$end   = Carbon::parse($endDate);

		// Shift exactly 1 year back
		$prevStart = $start->copy()->subYear();
		$prevEnd   = $end->copy()->subYear();

		return [$prevStart->toDateString(), $prevEnd->toDateString()];
	}
	
	private function getDateRange($financialYear, $periodType, $period)
    {
        [$fyStart, $fyEnd] = explode('-', $financialYear);

        $start = Carbon::create($fyStart, 4, 1);
        $end = Carbon::create($fyEnd, 3, 31);

        if ($periodType === 'monthly') {
            $start = Carbon::parse("first day of $period $fyStart");
            $end = Carbon::parse("last day of $period $fyStart");
        }

        if ($periodType === 'quarterly') {
            match ($period) {
                'april-june' => [$start, $end] = [Carbon::create($fyStart,4,1), Carbon::create($fyStart,6,30)],
                'july-september' => [$start, $end] = [Carbon::create($fyStart,7,1), Carbon::create($fyStart,9,30)],
                'october-december' => [$start, $end] = [Carbon::create($fyStart,10,1), Carbon::create($fyStart,12,31)],
                'jan-march','january-march' => [$start, $end] = [Carbon::create($fyEnd,1,1), Carbon::create($fyEnd,3,31)],
            };
        }

        if ($periodType === 'half-yearly') {
            $start = $period === 'april-september'
                ? Carbon::create($fyStart,4,1)
                : Carbon::create($fyStart,10,1);

            $end = $period === 'april-september'
                ? Carbon::create($fyStart,9,30)
                : Carbon::create($fyEnd,3,31);
        }

        return [$start->toDateString(), $end->toDateString()];
    }
	
	public function downloadPLSheetPdf(Request $request)
	{
		$userId = currentOwnerId();
		$html = $request->html; // full table HTML

		$pdf = Pdf::loadView('pl-sheet-pdf', [
			'html' => $html
		])->setPaper('A4', 'landscape');

		return $pdf->download('Profit_Loss_Sheet.pdf');
	}


}
