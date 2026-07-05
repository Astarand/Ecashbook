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
use App\Modals\User;
use App\Country;
use App\Models\State;
use App\Models\City;
use App\Statutorys;
use App\Coupons;
use Carbon\Carbon;
use App\Task_managements;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


	public function getBankDetails(Request $request)
    {
        $uid = Auth::user()->id;
        $Get_financial_year = $request->Get_financial_year;

        // Parse the financial year into start and end dates
        $financial_year_dates = explode('-', $Get_financial_year);
        $start_date = $financial_year_dates[0] . '-04-01';
        $end_date = ($financial_year_dates[1]) . '-03-31';
        $bankId = $request->id;

        $allBanks = DB::table('banks')
                    ->where('id', '=', $request->id)
                    ->get();

        $total_credit = DB::table('bank_trans')
                    ->where('bankId', $bankId)
                    ->where('tran_type', 'Credit')
                    ->whereBetween('tran_date', [$start_date, $end_date])
                    ->sum('tran_amt');

        // Calculate total Debit
        $total_debit = DB::table('bank_trans')
                        ->where('bankId', $bankId)
                        ->where('tran_type', 'Debit')
                        ->whereBetween('tran_date', [$start_date, $end_date])
                        ->sum('tran_amt');




            $total_loan = DB::table('loans')
                        ->where('added_by', '=', $uid)
                        ->where('bank_name', '=', $request->id)
                        ->whereBetween('created_at', [$start_date, $end_date])
                        ->sum('credit_limit');





        if ($allBanks->isNotEmpty()) {
            return response()->json([
                'bank_name' => $allBanks[0]->bank_name,
                'bank_ac_no' => $allBanks[0]->bank_ac_no,
                'accholder_name' => $allBanks[0]->accholder_name,
                'ifsc_code' => $allBanks[0]->ifsc_code,
                'bank_branch' => $allBanks[0]->bank_branch,
                'swift_code' => $allBanks[0]->swift_code,
                'curr_bal' => $allBanks[0]->curr_bal,
                'total_credit' => $total_credit,
                'total_debit' => $total_debit,
                'total_loan' => $total_loan ? number_format($total_loan, 2) : '00.00'

            ]);
        } else {
            return response()->json([
                'error' => 'Bank not found'
            ], 404);
        }
    }

    public function getMonthlyData(Request $request)
    {
        $uid = Auth::user()->id;
        $SelectFinincialYear = $request->input('financial_year');
        $viewType = $request->input('view_type', 'monthly');

        list($startYear, $endYear) = explode('-', $SelectFinincialYear);

        $startDate = "$startYear-04-01";
        $endDate = ($endYear) . "-03-31";

        if ($viewType == 'quarterly') {
            $quarterlyIncome = DB::table('income')
                ->select(DB::raw('QUARTER(dateInput) as q'), DB::raw('SUM(amount) as total_income'))
                ->where('addBy', $uid)
                ->whereBetween('dateInput', [$startDate, $endDate])
                ->groupBy(DB::raw('QUARTER(dateInput)'))
                ->pluck('total_income', 'q');

            $quarterlyExpenses = DB::table('expenses')
                ->select(DB::raw('QUARTER(expense_date) as q'), DB::raw('SUM(expense_amt) as total_expense'))
                ->where('added_by', $uid)
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->groupBy(DB::raw('QUARTER(expense_date)'))
                ->pluck('total_expense', 'q');

            // Q1: Apr-Jun(2), Q2: Jul-Sep(3), Q3: Oct-Dec(4), Q4: Jan-Mar(1)
            $incomeData = [
                round($quarterlyIncome[2] ?? 0, 2),
                round($quarterlyIncome[3] ?? 0, 2),
                round($quarterlyIncome[4] ?? 0, 2),
                round($quarterlyIncome[1] ?? 0, 2),
            ];
            $expensesData = [
                round($quarterlyExpenses[2] ?? 0, 2),
                round($quarterlyExpenses[3] ?? 0, 2),
                round($quarterlyExpenses[4] ?? 0, 2),
                round($quarterlyExpenses[1] ?? 0, 2),
            ];
        } else {
            $monthlyIncome = DB::table('income')
                ->select(DB::raw('MONTH(dateInput) as month'), DB::raw('SUM(amount) as total_income'))
                ->where('addBy', $uid)
                ->whereBetween('dateInput', [$startDate, $endDate])
                ->groupBy(DB::raw('MONTH(dateInput)'))
                ->pluck('total_income', 'month');

            $monthlyExpenses = DB::table('expenses')
                ->select(DB::raw('MONTH(expense_date) as month'), DB::raw('SUM(expense_amt) as total_expense'))
                ->where('added_by', $uid)
                ->whereBetween('expense_date', [$startDate, $endDate])
                ->groupBy(DB::raw('MONTH(expense_date)'))
                ->pluck('total_expense', 'month');

            $incomeData = array_fill(0, 12, 0);
            $expensesData = array_fill(0, 12, 0);

            for ($i = 0; $i < 12; $i++) {
                $month = ($i + 3) % 12 + 1; // 0=Apr(4), 1=May(5) ... 11=Mar(3)
                $incomeData[$i] = round($monthlyIncome[$month] ?? 0, 2);
                $expensesData[$i] = round($monthlyExpenses[$month] ?? 0, 2);
            }
        }

        $totalIncome = array_sum($incomeData);
        $totalExpenses = array_sum($expensesData);
        $profit = $totalIncome - $totalExpenses;

        return response()->json([
            'income' => $incomeData,
            'expenses' => $expensesData,
            'total_income' => number_format($totalIncome, 2),
            'total_expenses' => number_format($totalExpenses, 2),
            'profit' => number_format($profit, 2),
            'view_type' => $viewType
        ]);
    }


    public function getStatutoryData(Request $request) {
        $uid = Auth::user()->id;

        // Get the selected financial year from the request (e.g., '2024-2025')
        $SelectFinincialYear = $request->input('financial_year');
        list($startYear, $endYear) = explode('-', $SelectFinincialYear);

        // Define the start and end date for the financial year
        $startDate = "$startYear-04-01 00:00:00";
        $endDate = "$endYear-03-31 23:59:59";

        // Fetch the statutory data with financial year and exact date filter
        $statutory_data = DB::table('statutorys')
                            ->select(DB::raw('statutorys.*, company_profiles.comp_name, ca_assigns.ca_id'))
                            ->leftJoin('company_profiles', 'statutorys.compId', '=', 'company_profiles.userId')
                            ->leftJoin('ca_assigns', 'statutorys.compId', '=', 'ca_assigns.comp_id')
                            ->where('statutorys.compId', '=', $uid)
                            ->where('ca_assigns.ca_assign_status', '=', 1)
                            ->whereBetween('statutorys.created_at', [$startDate, $endDate])
                            ->orderBy('id', 'DESC')
                            ->take(10)
                            ->get();

        // Return empty array if no data matches the criteria
        if ($statutory_data->isEmpty()) {
            return response()->json([]);
        }

        // Return the fetched data if matches are found
        return response()->json([
            'received' => $statutory_data,
        ]);
    }

    public function getCustomerData(Request $request){
        $uid = Auth::user()->id;

        // Get current week's start and end dates
        $currentWeekStart = \Carbon\Carbon::now()->startOfWeek();
        $currentWeekEnd = \Carbon\Carbon::now()->endOfWeek();

        // Get last week's start and end dates
        $lastWeekStart = \Carbon\Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = \Carbon\Carbon::now()->subWeek()->endOfWeek();

        // Count customers added this week
        $currentWeekCustomers = DB::table('customers')
                                ->where('userId', '=', $uid)
                                ->whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
                                ->count();

        // // Count customers added last week
        $lastWeekCustomers = DB::table('customers')
                                ->where('userId', '=', $uid)
                                ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                                ->count();



        // Calculate the percentage change
        if ($lastWeekCustomers == 0) {
            if ($currentWeekCustomers > 0) {
                $percentageChange = 100;
            } else {
                $percentageChange = 0;
            }
        } else {
            $percentageChange = (($currentWeekCustomers - $lastWeekCustomers) / $lastWeekCustomers) * 100;
        }

        // Get the total number of customers
        $totalCustomers = DB::table('customers')
                            ->where('userId', '=', $uid)
                            ->count();

        return response()->json([
            'total_customers' => $totalCustomers,
            'current_week_customers' => $currentWeekCustomers,
            'percentage_change' => $percentageChange,

        ]);
    }

    public function getMonthlyturnoverData(Request $request)
    {
        $uid = Auth::user()->id;
        $SelectFinincialYear = $request->input('financial_year');
        $viewType = $request->input('view_type', 'monthly');

        list($startYear, $endYear) = explode('-', $SelectFinincialYear);
        $startDate = "$startYear-04-01 00:00:00";
        $endDate = "$endYear-03-31 23:59:59";

        $query = DB::table('sales')
                    ->join('sales_values', 'sales.id', '=', 'sales_values.sid')
                    ->leftJoin('vouchers', 'sales.inv_num', '=', 'vouchers.invoice_number')
                    ->where('sales.added_by', $uid);

        if ($viewType == 'quarterly') {
            $query->whereBetween('sales.created_at', [$startDate, $endDate])
                  ->select(
                      DB::raw('QUARTER(sales.created_at) as q_index'),
                      DB::raw('SUM(sales_values.amount + sales_values.tax_amt +
                                CASE
                                    WHEN vouchers.note_type = "Credit" THEN vouchers.adjusted_amount
                                    WHEN vouchers.note_type = "Debit" THEN -vouchers.adjusted_amount
                                    ELSE 0
                                END
                            ) as total_amount')
                  )
                  ->groupBy('q_index')
                  ->orderBy('q_index');
        } else {
            // Monthly (default)
            $query->whereBetween('sales.created_at', [$startDate, $endDate])
                  ->select(
                      DB::raw('DATE_FORMAT(sales.created_at, "%Y-%m") as label'),
                      DB::raw('SUM(sales_values.amount + sales_values.tax_amt +
                                CASE
                                    WHEN vouchers.note_type = "Credit" THEN vouchers.adjusted_amount
                                    WHEN vouchers.note_type = "Debit" THEN -vouchers.adjusted_amount
                                    ELSE 0
                                END
                            ) as total_amount')
                  )
                  ->groupBy('label')
                  ->orderBy('label');
        }

        $data = $query->get();
        return response()->json($data);
    }

    // public function getReceivablesData(Request $request)
    // {
    //     $uid = Auth::user()->id;
    //     $SelectFinancialYear = $request->input('financial_year');
    //     $MonthName = $request->input('Month');
    //     list($startYear, $endYear) = explode('-', $SelectFinancialYear);

    //     // Convert month name to month number
    //     $monthNumbers = [
    //         'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
    //         'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
    //         'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
    //     ];
    //     $Month = $monthNumbers[$MonthName];

    //     // Define the start and end date for the financial year
    //     $startDate = "$startYear-04-01 00:00:00";
    //     $endDate = "$endYear-03-31 23:59:59";

    //     // Calculate unpaid bills based on the provided month and year
    //     $unpaidBills = DB::table('sales')
    //         ->where('added_by', $uid)
    //         ->whereBetween('inv_date', [$startDate, $endDate])
    //         ->whereMonth('inv_date', '=', $Month)
    //         ->where(function($query) use ($startYear, $endYear) {
    //             $query->whereYear('inv_date', $startYear)
    //                 ->orWhereYear('inv_date', $endYear);
    //         })
    //         ->where(function($query) {
    //             $query->where('pay_status', 'Partial')
    //                 ->orWhere('pay_status', 'Due');
    //         })
    //         ->get();

    //     $partialSum = 0;
    //     $dueSum = 0;
    //     $totalPayment = 0;
    //     $totalDuePayment = 0;
    //     $currentDue = 0;
    //     $overDue = 0;

    //     foreach ($unpaidBills as $bill) {
    //         if ($bill->pay_status === 'Partial') {
    //             $partialSum += $bill->due_amount;
    //             $totalPayment += $bill->advance_amount;
    //         } elseif ($bill->pay_status === 'Due') {
    //             $billAmount = DB::table('sales_values')
    //                             ->where('sid', $bill->id)
    //                             ->sum(DB::raw('amount + tax_amt'));

    //             $invoiceDate = \Carbon\Carbon::parse($bill->inv_date);
    //             $daysDifference = $invoiceDate->diffInDays(now());

    //             if ($daysDifference > 45) {
    //                 $overDue += $billAmount;
    //             } else {
    //                 $currentDue += $billAmount;
    //             }

    //             $dueSum += $billAmount;
    //         }
    //     }

    //     $totalUnpaid = $partialSum + $dueSum;

    //     // Calculate the total payment for "Full" status
    //     $fullPayments = DB::table('sales')
    //         ->where('added_by', $uid)
    //         ->whereBetween('inv_date', [$startDate, $endDate])
    //         ->whereMonth('inv_date', '=', $Month)
    //         ->where(function($query) use ($startYear, $endYear) {
    //             $query->whereYear('inv_date', $startYear)
    //                 ->orWhereYear('inv_date', $endYear);
    //         })
    //         ->where('pay_status', 'Full')
    //         ->get();

    //     foreach ($fullPayments as $payment) {
    //         $totalPayment += DB::table('sales_values')
    //                             ->where('sid', $payment->id)
    //                             ->sum(DB::raw('amount + tax_amt'));
    //     }

    //     //------------- Additional Due Payment Calculation for dashboard ------
    //     $currentMonth = date('m');
    //     $currentYear = date('Y');

    //     $duePayments = DB::table('sales')
    //         ->where('added_by', $uid)
    //         ->whereBetween('inv_date', [$startDate, $endDate])
    //         ->where('pay_status', 'Due')
    //         ->where(function($query) use ($currentMonth, $currentYear) {
    //             $query->where(function($query) use ($currentMonth, $currentYear) {
    //                 $query->whereYear('inv_date', '<>', $currentYear)
    //                     ->orWhere(function($query) use ($currentMonth) {
    //                         $query->whereYear('inv_date', date('Y'))
    //                                 ->whereMonth('inv_date', '<>', $currentMonth);
    //                     });
    //             });
    //         })
    //         ->get();

    //     foreach ($duePayments as $paymentDue) {
    //         $totalDuePayment += DB::table('sales_values')
    //                             ->where('sid', $paymentDue->id)
    //                             ->sum(DB::raw('amount + tax_amt'));
    //     }

    //     //--------- amount fetch from income table ------------
    //     $totalAmount = DB::table('income')
    //         ->where('addBy', $uid)
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->whereMonth('created_at', '=', $Month)
    //         ->sum('amount');

    //     $totalPayment += $totalAmount;

    //     return response()->json([
    //         'partial_sum' => number_format($partialSum, 2, '.', ''),
    //         'due_sum' => number_format($dueSum, 2, '.', ''),
    //         'total_unpaid' => number_format($totalUnpaid, 2, '.', ''),
    //         'total_payment_receivables' => number_format($totalPayment, 2, '.', ''),
    //         'total_over_due_receivables' => number_format($totalDuePayment, 2, '.', ''),
    //         'current_due' => number_format($currentDue, 2, '.', ''),
    //         'over_due' => number_format($overDue, 2, '.', ''),
    //     ]);
    // }

    public function getReceivablesData(Request $request)
    {
        $uid = Auth::user()->id;

        $SelectFinancialYear = $request->input('financial_year');
        $MonthName = $request->input('Month');

        list($startYear, $endYear) = explode('-', $SelectFinancialYear);

        // Convert month name to month number
        $monthNumbers = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12
        ];

        $Month = $monthNumbers[$MonthName];

        // Financial Year Date Range
        $startDate = "$startYear-04-01 00:00:00";
        $endDate   = "$endYear-03-31 23:59:59";

        /*
        |--------------------------------------------------------------------------
        | Month Wise Pending Bills
        | Used For:
        | partial_sum
        | due_sum
        | total_unpaid
        |--------------------------------------------------------------------------
        */
        $unpaidBills = DB::table('sales')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->whereMonth('inv_date', $Month)
            ->where(function ($query) {
                $query->where('pay_status', 'Partial')
                    ->orWhere('pay_status', 'Due');
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Full Financial Year Pending Bills
        | Used For:
        | current_due
        | over_due
        |--------------------------------------------------------------------------
        */
        $allPendingBills = DB::table('sales')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('pay_status', 'Partial')
                    ->orWhere('pay_status', 'Due');
            })
            ->get();

        $partialSum = 0;
        $dueSum = 0;
        $totalPayment = 0;
        $totalDuePayment = 0;
        $currentDue = 0;
        $overDue = 0;

        /*
        |--------------------------------------------------------------------------
        | Month Wise Unpaid Calculation
        |--------------------------------------------------------------------------
        */
        foreach ($unpaidBills as $bill) {

            // Partial Bills
            if ($bill->pay_status == 'Partial') {

                $partialSum += $bill->due_amount;
                $totalPayment += $bill->advance_amount;
            }

            // Due Bills
            elseif ($bill->pay_status == 'Due') {

                $billAmount = DB::table('sales_values')
                    ->where('sid', $bill->id)
                    ->sum(DB::raw('amount + tax_amt'));

                $dueSum += $billAmount;
            }
        }

        $totalUnpaid = $partialSum + $dueSum;

        /*
        |--------------------------------------------------------------------------
        | Current Due & Over Due Calculation
        |--------------------------------------------------------------------------
        */
        foreach ($allPendingBills as $bill) {

            $invoiceDate = \Carbon\Carbon::parse($bill->inv_date);
            $daysDifference = $invoiceDate->diffInDays(now());

            // Partial Bills
            if ($bill->pay_status == 'Partial') {

                if ($daysDifference > 45) {
                    $overDue += $bill->due_amount;
                } else {
                    $currentDue += $bill->due_amount;
                }
            }

            // Due Bills
            elseif ($bill->pay_status == 'Due') {

                $billAmount = DB::table('sales_values')
                    ->where('sid', $bill->id)
                    ->sum(DB::raw('amount + tax_amt'));

                if ($daysDifference > 45) {
                    $overDue += $billAmount;
                } else {
                    $currentDue += $billAmount;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Full Payment Calculation
        |--------------------------------------------------------------------------
        */
        $fullPayments = DB::table('sales')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->whereMonth('inv_date', $Month)
            ->where('pay_status', 'Full')
            ->get();

        foreach ($fullPayments as $payment) {

            $totalPayment += DB::table('sales_values')
                ->where('sid', $payment->id)
                ->sum(DB::raw('amount + tax_amt'));
        }

        /*
        |--------------------------------------------------------------------------
        | Additional Due Payment Calculation
        |--------------------------------------------------------------------------
        */
        $currentMonth = date('m');
        $currentYear  = date('Y');

        $duePayments = DB::table('sales')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->where('pay_status', 'Due')
            ->where(function ($query) use ($currentMonth, $currentYear) {

                $query->where(function ($query) use ($currentMonth, $currentYear) {

                    $query->whereYear('inv_date', '<>', $currentYear)

                        ->orWhere(function ($query) use ($currentMonth) {

                            $query->whereYear('inv_date', date('Y'))
                                ->whereMonth('inv_date', '<>', $currentMonth);
                        });
                });
            })
            ->get();

        foreach ($duePayments as $paymentDue) {

            $totalDuePayment += DB::table('sales_values')
                ->where('sid', $paymentDue->id)
                ->sum(DB::raw('amount + tax_amt'));
        }

        /*
        |--------------------------------------------------------------------------
        | Income Table Amount
        |--------------------------------------------------------------------------
        */
        $totalAmount = DB::table('income')
            ->where('addBy', $uid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereMonth('created_at', $Month)
            ->sum('amount');

        $totalPayment += $totalAmount;

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */
        return response()->json([

            'partial_sum' => number_format($partialSum, 2, '.', ''),

            'due_sum' => number_format($dueSum, 2, '.', ''),

            'total_unpaid' => number_format($totalUnpaid, 2, '.', ''),

            'total_payment_receivables' => number_format($totalPayment, 2, '.', ''),

            'total_over_due_receivables' => number_format($totalDuePayment, 2, '.', ''),

            'current_due' => number_format($currentDue, 2, '.', ''),

            'over_due' => number_format($overDue, 2, '.', ''),
        ]);
    }

    public function getPayablesData(Request $request)
    {
        $uid = Auth::user()->id;

        $SelectFinancialYear = $request->input('financial_year');
        $MonthName = $request->input('Month');

        list($startYear, $endYear) = explode('-', $SelectFinancialYear);

        // Convert month name to month number
        $monthNumbers = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12
        ];

        $Month = $monthNumbers[$MonthName];

        // Financial Year Date Range
        $startDate = "$startYear-04-01 00:00:00";
        $endDate   = "$endYear-03-31 23:59:59";

        /*
        |--------------------------------------------------------------------------
        | Month Wise Pending Bills
        |--------------------------------------------------------------------------
        */
        $unpaidBills = DB::table('purchases')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->whereMonth('inv_date', $Month)
            ->where(function ($query) {
                $query->where('pay_status', 'Partial')
                    ->orWhere('pay_status', 'Due');
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Full FY Pending Bills
        | For Current Due & Over Due
        |--------------------------------------------------------------------------
        */
        $allPendingBills = DB::table('purchases')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('pay_status', 'Partial')
                    ->orWhere('pay_status', 'Due');
            })
            ->get();

        $partialSum = 0;
        $dueSum = 0;
        $totalPayment = 0;
        $totalDuePayment = 0;
        $currentDue = 0;
        $overDue = 0;

        /*
        |--------------------------------------------------------------------------
        | Month Wise Unpaid Calculation
        |--------------------------------------------------------------------------
        */
        foreach ($unpaidBills as $bill) {

            // Partial Bills
            if ($bill->pay_status == 'Partial') {

                $partialSum += $bill->due_amount;

                // Advance Payment
                $totalPayment += $bill->advance_amount;
            }

            // Due Bills
            elseif ($bill->pay_status == 'Due') {

                $billAmount = DB::table('purchase_values')
                    ->where('sid', $bill->id)
                    ->sum(DB::raw('amount + tax_amt'));

                $dueSum += $billAmount;
            }
        }

        $totalUnpaid = $partialSum + $dueSum;

        /*
        |--------------------------------------------------------------------------
        | Current Due & Over Due Calculation
        |--------------------------------------------------------------------------
        */
        foreach ($allPendingBills as $bill) {

            $invoiceDate = \Carbon\Carbon::parse($bill->inv_date);

            $daysDifference = $invoiceDate->diffInDays(now());

            // Partial Bills
            if ($bill->pay_status == 'Partial') {

                if ($daysDifference > 45) {

                    $overDue += $bill->due_amount;

                } else {

                    $currentDue += $bill->due_amount;
                }
            }

            // Due Bills
            // elseif ($bill->pay_status == 'Due') {

            //     $billAmount = DB::table('purchase_values')
            //         ->where('sid', $bill->id)
            //         ->sum(DB::raw('amount + tax_amt'));

            //     if ($daysDifference > 45) {

            //         $overDue += $billAmount;

            //     } else {

            //         $currentDue += $billAmount;
            //     }
            // }
        }

        /*
        |--------------------------------------------------------------------------
        | Full Payment Calculation
        |--------------------------------------------------------------------------
        */
        $fullPayments = DB::table('purchases')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->whereMonth('inv_date', $Month)
            ->where('pay_status', 'Full')
            ->get();

        foreach ($fullPayments as $payment) {

            $totalPayment += DB::table('purchase_values')
                ->where('sid', $payment->id)
                ->sum(DB::raw('amount + tax_amt'));
        }

        /*
        |--------------------------------------------------------------------------
        | Additional Due Payment Calculation
        |--------------------------------------------------------------------------
        */
        $currentMonth = date('m');

        $currentYear = date('Y');

        $duePayments = DB::table('purchases')
            ->where('added_by', $uid)
            ->whereBetween('inv_date', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('pay_status', 'Due')
                    ->orWhere('pay_status', 'Partial');
            })
            ->where(function ($query) use ($currentMonth, $currentYear) {

                $query->where(function ($query) use ($currentMonth, $currentYear) {

                    $query->whereYear('inv_date', '<>', $currentYear)

                        ->orWhere(function ($query) use ($currentMonth) {

                            $query->whereYear('inv_date', date('Y'))
                                ->whereMonth('inv_date', '<>', $currentMonth);
                        });
                });
            })
            ->get();

        foreach ($duePayments as $paymentDue) {

            // Partial Bills
            if ($paymentDue->pay_status == 'Partial') {

                $totalDuePayment += $paymentDue->due_amount;
            }

            // Due Bills
            elseif ($paymentDue->pay_status == 'Due') {

                $totalDuePayment += DB::table('purchase_values')
                    ->where('sid', $paymentDue->id)
                    ->sum(DB::raw('amount + tax_amt'));
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */
        return response()->json([

            'partial_sum_Payables' => number_format($partialSum, 2, '.', ''),

            'due_sum_Payables' => number_format($dueSum, 2, '.', ''),

            'total_unpaid_Payables' => number_format($totalUnpaid, 2, '.', ''),

            'total_payment_Payables' => number_format($totalPayment, 2, '.', ''),

            'total_over_due_Payables' => number_format($totalDuePayment, 2, '.', ''),

            'current_due_Payables' => number_format($currentDue, 2, '.', ''),

            'over_due_Payables' => number_format($overDue, 2, '.', ''),
        ]);
    }

    // public function getPayablesData(Request $request){
    //     $uid = Auth::user()->id;
    //     $SelectFinancialYear = $request->input('financial_year');
    //     $MonthName = $request->input('Month');
    //     list($startYear, $endYear) = explode('-', $SelectFinancialYear);

    //     // Convert month name to month number
    //     $monthNumbers = [
    //         'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
    //         'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
    //         'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
    //     ];
    //     $Month = $monthNumbers[$MonthName];

    //     // Define the start and end date for the financial year
    //     $startDate = "$startYear-04-01 00:00:00";
    //     $endDate = "$endYear-03-31 23:59:59";

    //     // Calculate unpaid bills based on the provided month and year
    //     $unpaidBills = DB::table('purchases')
    //                 ->where('added_by', $uid)
    //                 ->whereBetween('inv_date', [$startDate, $endDate])
    //                 ->whereMonth('inv_date', '=', $Month)
    //                 ->where(function($query) use ($startYear, $endYear) {
    //                     $query->whereYear('inv_date', $startYear)
    //                         ->orWhereYear('inv_date', $endYear);
    //                 })
    //                 ->where(function($query) {
    //                     $query->where('pay_status', 'Partial')
    //                         ->orWhere('pay_status', 'Due');
    //                 })
    //                 ->get();

    //     $partialSum = 0;
    //     $dueSum = 0;
    //     $totalPayment = 0;
    //     $totalDuePayment = 0;

    //     foreach ($unpaidBills as $bill) {
    //         if ($bill->pay_status === 'Partial') {
    //             $partialSum += $bill->due_amount;
    //             $totalPayment += $bill->advance_amount;  // Sum advance amount for Partial payments
    //         } elseif ($bill->pay_status === 'Due') {
    //             $dueSum += DB::table('purchase_values')
    //                         ->where('sid', $bill->id)
    //                         ->sum(DB::raw('amount + tax_amt'));
    //         }
    //     }
    //     $totalUnpaid = $partialSum + $dueSum;

    //     // Calculate the total payment for "Full" status
    //     $fullPayments = DB::table('purchases')
    //                 ->where('added_by', $uid)
    //                 ->whereBetween('inv_date', [$startDate, $endDate])
    //                 ->whereMonth('inv_date', '=', $Month)
    //                 ->where(function($query) use ($startYear, $endYear) {
    //                     $query->whereYear('inv_date', $startYear)
    //                             ->orWhereYear('inv_date', $endYear);
    //                 })
    //                 ->where('pay_status', 'Full')
    //                 ->get();

    //     foreach ($fullPayments as $payment) {
    //         $totalPayment += DB::table('purchase_values')
    //                             ->where('sid', $payment->id)
    //                             ->sum(DB::raw('amount + tax_amt'));
    //     }

    //     $currentMonth = date('m');
    //         $currentYear = date('Y');

    //         $duePayments = DB::table('purchases')
    //                         ->where('added_by', $uid)
    //                         ->whereBetween('inv_date', [$startDate, $endDate])
    //                         ->where('pay_status', 'Due')
    //                         ->where(function($query) use ($currentMonth, $currentYear) {
    //                             $query->where(function($query) use ($currentMonth, $currentYear) {
    //                                 $query->whereYear('inv_date', '<>', $currentYear)
    //                                     ->orWhere(function($query) use ($currentMonth) {
    //                                         $query->whereYear('inv_date', date('Y'))
    //                                                 ->whereMonth('inv_date', '<>', $currentMonth);
    //                                     });
    //                             });
    //                         })
    //                         ->get();

    //         foreach ($duePayments as $paymentDue) {
    //             $totalDuePayment += DB::table('purchase_values')
    //                                 ->where('sid', $paymentDue->id)
    //                                 ->sum(DB::raw('amount + tax_amt'));
    //         }




    //     return response()->json([
    //         'partial_sum_Payables' => number_format($partialSum, 2, '.', ''),
    //         'due_sum_Payables' => number_format($dueSum, 2, '.', ''),
    //         'total_unpaid_Payables' => number_format($totalUnpaid, 2, '.', ''),
    //         'total_payment_Payables' => number_format($totalPayment, 2, '.', ''),
    //         'total_over_due_Payables' => number_format($totalDuePayment, 2, '.', ''),
    //     ]);


    // }

    public function applyCoupon(Request $request)
    {
        // Get the authenticated user's email
        $email = Auth::user()->email;

        // Validate the coupon code input with a custom error message for the required field
        $request->validate([
            'code_coupon' => 'required|string',
        ], [
            'code_coupon.required' => 'The coupon code field is empty.',
        ]);

        $coupon = Coupons::where('coupon_code', $request->input('code_coupon'))
                        ->where('user', $email)
                        ->where('active_by_user', '0')
                        ->first();

        if ($coupon) {

            if (Carbon::now()->greaterThan($coupon->expiry_date)) {
                return redirect()->back()->with('error', 'This coupon code is expired.');
            }

            // Update the coupon to be active
            $coupon->update([
                'active_by_user' => 1,
                'activation_date' => Carbon::now(),
            ]);

            // Return a success message
            return redirect()->back()->with('success', 'Coupon applied successfully!');
        } else {

            return redirect()->back()->with('error', 'Invalid coupon code or this coupon does not belong to you.');
        }
    }

    // public function getLiabilitiesSummary(Request $request)
    // {
    //     $uid = Auth::user()->id;
    //     $monthName = $request->input('month');
    //     $financialYear = $request->input('financial_year');

    //     $monthNumbers = [
    //         'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
    //         'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
    //         'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
    //     ];

    //     $month = $monthNumbers[$monthName] ?? (int) date('n');
    //     list($startYear, $endYear) = explode('-', $financialYear);
    //     $year = ($month >= 4) ? $startYear : $endYear;

    //     // 1. current_liabilities — filter by unrevenueDate month/year
    //     $currentLiabilities = DB::table('current_liabilities')
    //         ->where('added_by', $uid)
    //         ->whereMonth('unrevenueDate', $month)
    //         ->whereYear('unrevenueDate', $year)
    //         ->sum('unrevenueamount');

    //     // 2. non_current_liabilities — filter by created_at month/year
    //     $nonCurrentLiabilities = DB::table('non_current_liabilities')
    //         ->where('added_by', $uid)
    //         ->whereMonth('created_at', $month)
    //         ->whereYear('created_at', $year)
    //         ->sum('loan_amount');

    //     // 3. share_application_money_liabilities — filter by created_at month/year
    //     $shareApplicationMoney = DB::table('share_application_money_liabilities')
    //         ->where('added_by', $uid)
    //         ->whereMonth('created_at', $month)
    //         ->whereYear('created_at', $year)
    //         ->sum('amount_for_share');

    //     // 4. share_holder_fund_liabilities — filter by created_at month/year
    //     $shareHolderFund = DB::table('share_holder_fund_liabilities')
    //         ->where('added_by', $uid)
    //         ->whereMonth('created_at', $month)
    //         ->whereYear('created_at', $year)
    //         ->sum('premium_amount');

    //     $total = ($currentLiabilities ?? 0)
    //            + ($nonCurrentLiabilities ?? 0)
    //            + ($shareApplicationMoney ?? 0)
    //            + ($shareHolderFund ?? 0);

    //     return response()->json([
    //         'total_liabilities_value' => number_format($total, 2, '.', ''),
    //     ]);
    // }

    public function getLiabilitiesSummary(Request $request)
    {
        $uid = Auth::user()->id;
        $monthName = $request->input('month');
        $financialYear = $request->input('financial_year');

        $monthNumbers = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12
        ];

        $month = $monthNumbers[$monthName] ?? (int) date('n');

        list($startYear, $endYear) = explode('-', $financialYear);

        // Apr-Dec = startYear, Jan-Mar = endYear
        $year = ($month >= 4) ? $startYear : $endYear;

        // Start & End Date of Selected Month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Share Holder Fund
        |--------------------------------------------------------------------------
        */
        $shareHolderFund = DB::table('share_holder_fund_liabilities as s')
            ->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
            ->where('l.added_by', $uid)
            ->where('l.status', 1)
            ->whereBetween('l.added_date', [$startDate, $endDate])
            ->selectRaw("
                SUM(
                    CASE

                        WHEN s.share_holder_fund_type = 'reserves_surplus'
                            AND s.reserves_surplus_type = 'transfer_to_reserve'
                        THEN COALESCE(s.transfer_amount, 0)

                        WHEN s.share_holder_fund_type = 'reserves_surplus'
                            AND s.reserves_surplus_type = 'opening_balance'
                        THEN COALESCE(s.opening_balance, 0)

                        WHEN s.share_holder_fund_type = 'reserves_surplus'
                            AND s.reserves_surplus_type = 'dividend_declaration'
                        THEN COALESCE(s.total_dividend_amount, 0)

                        WHEN s.share_holder_fund_type = 'share_capital'
                        THEN COALESCE(s.total_amount, 0)

                        ELSE 0

                    END
                ) as total
            ")
            ->value('total') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Share Application Money
        |--------------------------------------------------------------------------
        */
        $shareAppMoney = DB::table('share_application_money_liabilities as s')
            ->join('liabilities as l', 'l.id', '=', 's.liabilities_id')
            ->where('l.added_by', $uid)
            ->where('l.status', 1)
            ->whereBetween('l.added_date', [$startDate, $endDate])
            ->sum('s.amount_received') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Non Current Liabilities
        |--------------------------------------------------------------------------
        */
        $nonCurrent = DB::table('non_current_liabilities as n')
            ->join('liabilities as l', 'l.id', '=', 'n.liabilities_id')
            ->where('l.added_by', $uid)
            ->where('l.status', 1)
            ->whereBetween('l.added_date', [$startDate, $endDate])
            ->selectRaw("
                SUM(
                    CASE

                        WHEN n.liability_category = 'deferred_tax_liabilities'
                        THEN COALESCE(n.dtl_difference_accounting, 0)

                        ELSE COALESCE(n.amount, 0)

                    END
                ) as total
            ")
            ->value('total') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Current Liabilities
        |--------------------------------------------------------------------------
        */
        $current = DB::table('current_liabilities as c')
            ->join('liabilities as l', 'l.id', '=', 'c.liabilities_id')
            ->where('l.added_by', $uid)
            ->where('l.status', 1)
            ->whereBetween('l.added_date', [$startDate, $endDate])
            ->selectRaw("
                SUM(
                    CASE

                        WHEN c.CurrentLiabilitiesType = 'short_term_loans'
                        THEN COALESCE(c.stl_sanction_amount, 0)

                        WHEN c.CurrentLiabilitiesType = 'interest_payable'
                        THEN COALESCE(c.ip_principal_amount, 0)

                        ELSE COALESCE(c.amount, 0)

                    END
                ) as total
            ")
            ->value('total') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Total Liabilities
        |--------------------------------------------------------------------------
        */
        $totalLiabilities = $shareHolderFund
            + $shareAppMoney
            + $nonCurrent
            + $current;

        return response()->json([
            'share_holder_fund'        => number_format($shareHolderFund, 2, '.', ''),
            'share_application_money' => number_format($shareAppMoney, 2, '.', ''),
            'non_current_liabilities' => number_format($nonCurrent, 2, '.', ''),
            'current_liabilities'     => number_format($current, 2, '.', ''),
            'total_liabilities_value' => number_format($totalLiabilities, 2, '.', ''),
        ]);
    }

    
    public function getAssetSummary(Request $request)
    {
        $uid = Auth::user()->id;
        $monthName = $request->input('month');
        $financialYear = $request->input('financial_year');

        $monthNumbers = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12
        ];

        $month = $monthNumbers[$monthName] ?? (int) date('n');

        list($startYear, $endYear) = explode('-', $financialYear);

        // Apr-Dec = startYear, Jan-Mar = endYear
        $year = ($month >= 4) ? $startYear : $endYear;

        /*
        |-------------------------------------------------------------
        | Current Assets
        | assetType = current
        | Sum multiple fields from assets_currs
        |-------------------------------------------------------------
        */
        $currentAssetsTotals = DB::table('assets')
            ->join('assets_currs', 'assets.id', '=', 'assets_currs.aid')
            ->where('assets.added_by', $uid)
            ->where('assets.assetType', 'current')
            ->whereMonth('assets.date', $month)
            ->whereYear('assets.date', $year)
            ->selectRaw('
                SUM(cash_amount) as total_cash_amount,
                SUM(bank_balance) as total_bank_balance,
                SUM(amount) as total_amount,
                SUM(pending_amount) as total_pending_amount,
                SUM(amount_vendor) as total_amount_vendor,
                SUM(employee_advance_amount) as total_employee_advance_amount,
                SUM(prepaid_amt) as total_prepaid_amt,
                SUM(itc_amt) as total_itc_amt,
                SUM(tds_gross_amount) as total_tds_gross_amount,
                SUM(gross_profit) as total_gross_profit
            ')
            ->first();

        /*
        |-------------------------------------------------------------
        | Total Current Assets
        |-------------------------------------------------------------
        */
        $currentAssetsTotal =
            ($currentAssetsTotals->total_cash_amount ?? 0) +
            ($currentAssetsTotals->total_bank_balance ?? 0) +
            ($currentAssetsTotals->total_amount ?? 0) +
            ($currentAssetsTotals->total_pending_amount ?? 0) +
            ($currentAssetsTotals->total_amount_vendor ?? 0) +
            ($currentAssetsTotals->total_employee_advance_amount ?? 0) +
            ($currentAssetsTotals->total_prepaid_amt ?? 0) +
            ($currentAssetsTotals->total_itc_amt ?? 0) +
            ($currentAssetsTotals->total_tds_gross_amount ?? 0) +
            ($currentAssetsTotals->total_gross_profit ?? 0);

        /*
        |-------------------------------------------------------------
        | Non Current Assets
        |-------------------------------------------------------------
        */
        $nonCurrentAssetsTotal = DB::table('assets')
            ->where('added_by', $uid)
            ->where('assetType', 'non-current')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('invoice_value');

        $totalAssetsValue = $currentAssetsTotal + $nonCurrentAssetsTotal;

        return response()->json([
            'cash_amount_total'               => number_format($currentAssetsTotals->total_cash_amount ?? 0, 2, '.', ''),
            'bank_balance_total'             => number_format($currentAssetsTotals->total_bank_balance ?? 0, 2, '.', ''),
            'amount_total'                   => number_format($currentAssetsTotals->total_amount ?? 0, 2, '.', ''),
            'pending_amount_total'           => number_format($currentAssetsTotals->total_pending_amount ?? 0, 2, '.', ''),
            'amount_vendor_total'            => number_format($currentAssetsTotals->total_amount_vendor ?? 0, 2, '.', ''),
            'employee_advance_amount_total'  => number_format($currentAssetsTotals->total_employee_advance_amount ?? 0, 2, '.', ''),
            'prepaid_amt_total'              => number_format($currentAssetsTotals->total_prepaid_amt ?? 0, 2, '.', ''),
            'itc_amt_total'                  => number_format($currentAssetsTotals->total_itc_amt ?? 0, 2, '.', ''),
            'tds_gross_amount_total'         => number_format($currentAssetsTotals->total_tds_gross_amount ?? 0, 2, '.', ''),
            'gross_profit_total'             => number_format($currentAssetsTotals->total_gross_profit ?? 0, 2, '.', ''),

            'current_assets_total'           => number_format($currentAssetsTotal ?? 0, 2, '.', ''),
            'non_current_assets_total'       => number_format($nonCurrentAssetsTotal ?? 0, 2, '.', ''),
            'total_assets_value'             => number_format($totalAssetsValue ?? 0, 2, '.', ''),
        ]);
    }

    public function get_attendance_details(Request $request)
    {
        $uid = Auth::user()->id;
        $selectedDate = $request->input('selectedDate', now()->toDateString());

        $employeeIds = DB::table('employees')
            ->where('added_by', $uid)
            ->pluck('empId');

        $attendances = DB::table('attendance')
            ->whereIn('userId', $employeeIds)
            ->whereDate('present_date', $selectedDate)
            ->get();

        $totalPresent = 0;
        $latePresent = 0;
        $onTimePresent = 0;
        $totalAbsent = 0;

        foreach ($attendances as $attendance) {
            $status = strtolower(trim($attendance->present_status));
            $time = $attendance->attendance_time ?? null;

            if ($status === 'present' || $status === 'working') {
                $totalPresent++;

                if ($status === 'present' && $time) {
                    if (strtotime($time) > strtotime("10:10:00")) {
                        $latePresent++;
                    } else {
                        $onTimePresent++;
                    }
                }
            } elseif ($status === 'absent') {
                $totalAbsent++;
            }
        }

        return response()->json([
            'date' => $selectedDate,
            'total_present' => $totalPresent,
            'late_present' => $latePresent,
            'on_time_present' => $onTimePresent,
            'total_absent' => $totalAbsent,
            'total_employee' => count($employeeIds)
        ]);
    }

    public function getCashflowSummary(Request $request)
    {
        $uid = Auth::user()->id;

        $viewType = $request->view_type ?? 'monthly';
        $financialYear = $request->financial_year;

        list($startYear, $endYear) = explode('-', $financialYear);

        // Financial Year Months (Apr-Mar)
        $months = [
            ['month' => 4, 'label' => 'Apr', 'year' => $startYear],
            ['month' => 5, 'label' => 'May', 'year' => $startYear],
            ['month' => 6, 'label' => 'Jun', 'year' => $startYear],
            ['month' => 7, 'label' => 'Jul', 'year' => $startYear],
            ['month' => 8, 'label' => 'Aug', 'year' => $startYear],
            ['month' => 9, 'label' => 'Sep', 'year' => $startYear],
            ['month' => 10, 'label' => 'Oct', 'year' => $startYear],
            ['month' => 11, 'label' => 'Nov', 'year' => $startYear],
            ['month' => 12, 'label' => 'Dec', 'year' => $startYear],
            ['month' => 1, 'label' => 'Jan', 'year' => $endYear],
            ['month' => 2, 'label' => 'Feb', 'year' => $endYear],
            ['month' => 3, 'label' => 'Mar', 'year' => $endYear],
        ];

        $labels = [];
        $cashIn = [];
        $cashOut = [];
        $netCash = [];

        $totalCashIn = 0;
        $totalCashOut = 0;

        foreach ($months as $m) {

            // Credit = Cash In
            $credit = DB::table('mcash_credit_debits')
                ->where('added_by', $uid)
                ->where('cd_type', 'Cr')
                ->whereMonth('cd_date', $m['month'])
                ->whereYear('cd_date', $m['year'])
                ->sum('cd_amount');

            // Debit = Cash Out
            $debit = DB::table('mcash_credit_debits')
                ->where('added_by', $uid)
                ->where('cd_type', 'Dr')
                ->whereMonth('cd_date', $m['month'])
                ->whereYear('cd_date', $m['year'])
                ->sum('cd_amount');

            $labels[] = $m['label'];

            $cashIn[] = round($credit / 100000, 2); // Lakhs
            $cashOut[] = round($debit / 100000, 2); // Lakhs
            $netCash[] = round(($credit - $debit) / 100000, 2);

            $totalCashIn += $credit;
            $totalCashOut += $debit;
        }

        // Quarterly View
        if ($viewType == 'quarterly') {

            $labels = [
                'Q1 (Apr-Jun)',
                'Q2 (Jul-Sep)',
                'Q3 (Oct-Dec)',
                'Q4 (Jan-Mar)'
            ];

            $cashIn = [
                array_sum(array_slice($cashIn, 0, 3)),
                array_sum(array_slice($cashIn, 3, 3)),
                array_sum(array_slice($cashIn, 6, 3)),
                array_sum(array_slice($cashIn, 9, 3)),
            ];

            $cashOut = [
                array_sum(array_slice($cashOut, 0, 3)),
                array_sum(array_slice($cashOut, 3, 3)),
                array_sum(array_slice($cashOut, 6, 3)),
                array_sum(array_slice($cashOut, 9, 3)),
            ];

            $netCash = [
                $cashIn[0] - $cashOut[0],
                $cashIn[1] - $cashOut[1],
                $cashIn[2] - $cashOut[2],
                $cashIn[3] - $cashOut[3],
            ];
        }

        return response()->json([
            'labels' => $labels,

            'series' => [
                [
                    'name' => 'Cash Inflow',
                    'type' => 'column',
                    'data' => $cashIn,
                ],
                [
                    'name' => 'Cash Outflow',
                    'type' => 'column',
                    'data' => $cashOut,
                ],
                [
                    'name' => 'Net Cash Position',
                    'type' => 'line',
                    'data' => $netCash,
                ],
            ],

            'cash_in_total' => number_format($totalCashIn, 2),
            'cash_out_total' => number_format($totalCashOut, 2),
            'net_cash_total' => number_format(($totalCashIn - $totalCashOut), 2),
        ]);
    }

    public function getGstSummary(Request $request)
    {
        $uid = Auth::user()->id;

        $monthName = $request->month;
        $financialYear = $request->financial_year;

        $monthNumbers = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12
        ];

        $month = $monthNumbers[$monthName] ?? date('n');

        list($startYear, $endYear) = explode('-', $financialYear);

        $year = ($month >= 4) ? $startYear : $endYear;

        // Example Queries
        $receivables = DB::table('sales')
            ->where('added_by', $uid)
            ->whereMonth('inv_date', $month)
            ->whereYear('inv_date', $year)
            ->whereIn('pay_status', ['Due', 'Partial'])
            ->sum('due_amount');
        $payables = DB::table('purchases')
            ->where('added_by', $uid)
            ->whereMonth('inv_date', $month)
            ->whereYear('inv_date', $year)
            ->whereIn('pay_status', ['Due', 'Partial'])
            ->sum('due_amount');    
        
            

        return response()->json([
            'receivables' => number_format($receivables, 2, '.', ''),
            'payables' => number_format($payables, 2, '.', '')
        ]);
    }

    public function completeTour(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['tour_completed' => 1]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 401);
    }

}

