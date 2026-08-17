<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use DateInterval;
use DatePeriod;
use App\Services\JournalService;
use App\Services\PaymentVoucherService;


class PayrollReportController extends Controller
{
    protected JournalService $journalService;
    protected PaymentVoucherService $paymentVoucherService;

    public function __construct(JournalService $journalService, PaymentVoucherService $paymentVoucherService)
    {
        $this->journalService        = $journalService;
        $this->paymentVoucherService = $paymentVoucherService;
    }

    public function summary(Request $request)
    {
        $ownerId = currentOwnerId();

        $month = $request->month;
        $fy    = $request->fy;

        // Month Name => Month Number
        $months = [
            'January'   => 1,
            'February'  => 2,
            'March'     => 3,
            'April'     => 4,
            'May'       => 5,
            'June'      => 6,
            'July'      => 7,
            'August'    => 8,
            'September' => 9,
            'October'   => 10,
            'November'  => 11,
            'December'  => 12,
        ];

        $currentMonth = $months[$month] ?? date('n');

        [$fyStart, $fyEnd] = explode('-', $fy);

        // Previous Month & FY
        $previousMonth = $currentMonth - 1;
        $previousFY    = $fy;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousFY    = ($fyStart - 1) . '-' . ($fyEnd - 1);
        }

        // Determine target month date range
        $selectedMonthStart = Carbon::createFromDate($fyStart, $currentMonth, 1)->startOfMonth();

        // Jan-Feb-Mar belong to second year of FY
        if ($currentMonth <= 3) {
            $selectedMonthStart->year = $fyEnd;
        }

        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        // =========================================================
        // Employees resigned / terminated in selected month
        // =========================================================
        $resignedThisMonth = DB::table('employees')
            ->where('added_by', $ownerId)
            ->whereIn('emp_status', ['Resigned', 'Terminated'])
            ->whereNotNull('regine_date')
            ->whereBetween('regine_date', [$selectedMonthStart, $selectedMonthEnd])
            ->count();

        // =========================================================
        // Active employees for selected month
        // =========================================================
        $activeEmployees = DB::table('employees')
            ->where('added_by', $ownerId)
            ->where(function ($q) use ($selectedMonthEnd) {
                $q->whereIn('emp_status', ['Confirmed', 'In Probation'])
                ->orWhere(function ($qq) use ($selectedMonthEnd) {
                    $qq->whereIn('emp_status', ['Resigned', 'Terminated'])
                        ->whereNotNull('regine_date')
                        ->whereDate('regine_date', '>=', $selectedMonthEnd);
                });
            })
            ->get();

        // =========================================================
        // Block current / future month
        // =========================================================
        $today = now();

        $currentFY = ($today->month >= 4)
            ? $today->year . '-' . ($today->year + 1)
            : ($today->year - 1) . '-' . $today->year;

        if ($fy == $currentFY && $currentMonth >= $today->month) {

            return response()->json([
                'success'                  => true,
                'total_active_employees'  => 0,
                'total_resigned'          => 0,

                'gross_salary'            => 0,
                'net_salary'              => 0,

                'pf_liability'            => 0,
                'esi_liability'           => 0,
                'pt_liability'            => 0,
                'tds_liability'           => 0,
                'lwf_liability'           => 0,

                'pf_paid_amount'          => 0,
                'pf_unpaid_amount'        => 0,

                'esi_paid_amount'         => 0,
                'esi_unpaid_amount'       => 0,

                'pt_paid_amount'          => 0,
                'pt_unpaid_amount'        => 0,

                'tds_paid_amount'         => 0,
                'tds_unpaid_amount'       => 0,

                'lwf_paid_amount'         => 0,
                'lwf_unpaid_amount'       => 0,

                'lop_total'               => 0,
                'paid'                    => 0,
                'unpaid'                  => 0,

                'previous_month'          => $previousMonth,
                'previous_financial_year' => $previousFY,
            ]);
        }

        // =========================================================
        // Existing payslips
        // =========================================================
        $existingPayslips = DB::table('user_payslip')
            ->where('added_by', $ownerId)
            ->where('month', $currentMonth)
            ->where('financial_year', $fy)
            ->get()
            ->keyBy('user_emp_id');

        // =========================================================
        // Summary variables
        // =========================================================
        $grossSalary  = 0;
        $netSalary    = 0;

        $pfLiability  = 0;
        $esiLiability = 0;
        $ptLiability  = 0;
        $tdsLiability = 0;
        $lwfLiability = 0;

        $lopTotal     = 0;

        $paid         = 0;
        $unpaid       = 0;

        // Payment status totals
        $pfPaidAmount   = 0;
        $pfUnpaidAmount = 0;

        $esiPaidAmount   = 0;
        $esiUnpaidAmount = 0;

        $ptPaidAmount   = 0;
        $ptUnpaidAmount = 0;

        $tdsPaidAmount   = 0;
        $tdsUnpaidAmount = 0;

        $lwfPaidAmount   = 0;
        $lwfUnpaidAmount = 0;

        $salaryPaidAmount = 0;
        $salaryUnpaidAmount = 0;

        // =========================================================
        // Employee Loop
        // =========================================================
        foreach ($activeEmployees as $employee) {

            // -----------------------------------------------------
            // CASE A : Payslip Exists
            // -----------------------------------------------------
            if (isset($existingPayslips[$employee->empId])) {

                $payslip = $existingPayslips[$employee->empId];

                $response = json_decode($payslip->emp_salary_slip_response, true);

                $salary = $response['visible_data']['final_salary_calculation'] ?? [];

                $gross = (float)($salary['basic_salary'] ?? 0);
                $net   = (float)($salary['net_salary'] ?? 0);

                $pf    = (float)($salary['provident_fund'] ?? 0);
                $esi   = (float)($salary['esi'] ?? 0);
                $pt    = (float)($salary['ptax'] ?? 0);
                $tds   = (float)($salary['tds'] ?? 0);
                $lwf   = (float)($salary['lwf'] ?? 0);
                $lop   = (float)($salary['lop'] ?? 0);

                // Liability totals
                $grossSalary  += $gross;
                $netSalary    += $net;

                $pfLiability  += $pf;
                $esiLiability += $esi;
                $ptLiability  += $pt;
                $tdsLiability += $tds;
                $lwfLiability += $lwf;
                $lopTotal     += $lop;

                // ----------------------------------------------
                // Salary
                // ---------------------------------------------
                if ($payslip->payment_status == 'Done') {
                    $salaryPaidAmount += $net;
                } else {
                    $salaryUnpaidAmount += $net;
                }

                // -------------------------------------------------
                // PF
                // -------------------------------------------------
                if ($payslip->pf_payment_status == 'Done') {
                    $pfPaidAmount += $pf;
                } else {
                    $pfUnpaidAmount += $pf;
                }

                // -------------------------------------------------
                // ESI
                // -------------------------------------------------
                if ($payslip->esi_payment_status == 'Done') {
                    $esiPaidAmount += $esi;
                } else {
                    $esiUnpaidAmount += $esi;
                }

                // -------------------------------------------------
                // PT
                // -------------------------------------------------
                if ($payslip->ptax_payment_status == 'Done') {
                    $ptPaidAmount += $pt;
                } else {
                    $ptUnpaidAmount += $pt;
                }

                // -------------------------------------------------
                // TDS
                // -------------------------------------------------
                if ($payslip->tds_deposit_status == 'Done') {
                    $tdsPaidAmount += $tds;
                } else {
                    $tdsUnpaidAmount += $tds;
                }

                // -------------------------------------------------
                // LWF
                // -------------------------------------------------
                // Recommended: add lwf_payment_status ENUM('Pending','Done')
                if ($payslip->lwf_payment_status  == 'Done') {
                    $lwfPaidAmount += $lwf;
                } else {
                    $lwfUnpaidAmount += $lwf;
                }

                $paid++;
            }

            // -----------------------------------------------------
            // CASE B : Payslip NOT Exists
            // -----------------------------------------------------
            else {

                $salary = $this->calculateEmployeeSalary(
                    $employee,
                    $currentMonth,
                    $fy
                );

                $gross = (float)($salary['gross_salary'] ?? 0);
                $net   = (float)($salary['net_salary'] ?? 0);

                $pf    = (float)($salary['pf'] ?? 0);
                $esi   = (float)($salary['esi'] ?? 0);
                $pt    = (float)($salary['pt'] ?? 0);
                $tds   = (float)($salary['tds'] ?? 0);
                $lwf   = (float)($salary['lwf'] ?? 0);
                $lop   = (float)($salary['lop'] ?? 0);

                $grossSalary  += $gross;
                $netSalary    += $net;

                $pfLiability  += $pf;
                $esiLiability += $esi;
                $ptLiability  += $pt;
                $tdsLiability += $tds;
                $lwfLiability += $lwf;

                $lopTotal     += $lop;

                // No payslip => unpaid
                $pfUnpaidAmount  += $pf;
                $esiUnpaidAmount += $esi;
                $ptUnpaidAmount  += $pt;
                $tdsUnpaidAmount += $tds;
                $lwfUnpaidAmount += $lwf;
                $salaryUnpaidAmount += $net;

                $unpaid++;
            }
        }

        // =========================================================
        // Response
        // =========================================================
        return response()->json([

            'success'                  => true,

            // Employee Summary
            'total_active_employees'  => count($activeEmployees),
            'total_resigned'          => $resignedThisMonth,

            // Salary Summary
            'gross_salary'            => round($grossSalary, 2),
            'net_salary'              => round($netSalary, 2),
            'salary_paid_amount'      => round($salaryPaidAmount, 2),
            'salary_unpaid_amount'    => round($salaryUnpaidAmount, 2),

            // Liabilities
            'pf_liability'            => round($pfLiability, 2),
            'esi_liability'           => round($esiLiability, 2),
            'pt_liability'            => round($ptLiability, 2),
            'tds_liability'           => round($tdsLiability, 2),
            'lwf_liability'           => round($lwfLiability, 2),

            // PF
            'pf_paid_amount'          => round($pfPaidAmount, 2),
            'pf_unpaid_amount'        => round($pfUnpaidAmount, 2),

            // ESI
            'esi_paid_amount'         => round($esiPaidAmount, 2),
            'esi_unpaid_amount'       => round($esiUnpaidAmount, 2),

            // PT
            'pt_paid_amount'          => round($ptPaidAmount, 2),
            'pt_unpaid_amount'        => round($ptUnpaidAmount, 2),

            // TDS
            'tds_paid_amount'         => round($tdsPaidAmount, 2),
            'tds_unpaid_amount'       => round($tdsUnpaidAmount, 2),

            // LWF
            'lwf_paid_amount'         => round($lwfPaidAmount, 2),
            'lwf_unpaid_amount'       => round($lwfUnpaidAmount, 2),

            // Other
            'lop_total'               => round($lopTotal, 2),

            'paid'                    => $paid,
            'unpaid'                  => $unpaid,

            'previous_month'          => $previousMonth,
            'previous_financial_year' => $previousFY,
        ]);
    }

    private function calculateEmployeeSalary($employee, $month, $financialYear)
    {
        $ownerId = currentOwnerId();

        [$fyStart, $fyEnd] = explode('-', $financialYear);
        $year = ($month >= 4) ? $fyStart : $fyEnd;

        $firstDay = Carbon::create($year, $month, 1)->startOfMonth();
        $lastDay  = $firstDay->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Weekly Schedule
        |--------------------------------------------------------------------------
        */

        $weeklySchedule = DB::table('weekly_schedules')
            ->where('added_by', $ownerId)
            ->get()
            ->keyBy(function ($row) {
                return strtolower($row->day);
            });

        /*
        |--------------------------------------------------------------------------
        | Holidays
        |--------------------------------------------------------------------------
        */

        $holidayDates = DB::table('holidays')
            ->where('added_by', $ownerId)
            ->whereBetween('holidayDate', [$firstDay, $lastDay])
            ->pluck('holidayDate')
            ->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */

        $attendance = DB::table('attendance')
            ->where('userId', $employee->empId)
            ->whereBetween('present_date', [$firstDay, $lastDay])
            ->get();

        $attendanceDates = [];
        $lateCount = 0;
        $earlyLogoutCount = 0;
        $overtimeHours = 0;

        foreach ($attendance as $record) {

            $attendanceDates[] = Carbon::parse($record->present_date)->format('Y-m-d');

            $dayName = strtolower(Carbon::parse($record->present_date)->format('l'));

            $schedule = $weeklySchedule[$dayName] ?? null;

            if (!$schedule || strtolower($schedule->status) != 'open') {
                continue;
            }

            // Late
            if (!empty($record->in_time)) {

                $opening = Carbon::parse($record->present_date . ' ' . $schedule->opening_time);
                $login   = Carbon::parse($record->present_date . ' ' . $record->in_time);

                if ($login->gt($opening->copy()->addMinutes(5))) {
                    $lateCount++;
                }
            }

            // Early Logout
            if (!empty($record->out_time)) {

                $closing = Carbon::parse($record->present_date . ' ' . $schedule->closing_time);
                $logout  = Carbon::parse($record->present_date . ' ' . $record->out_time);

                if ($logout->lt($closing)) {
                    $earlyLogoutCount++;
                }

                if ($logout->gt($closing)) {
                    $overtimeHours += $closing->diffInMinutes($logout) / 60;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Leave
        |--------------------------------------------------------------------------
        */

        $approvedLeaves = DB::table('leaves')
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'approved')
            ->where(function ($q) use ($firstDay, $lastDay) {

                $q->whereBetween('start_date', [$firstDay, $lastDay])
                    ->orWhereBetween('end_date', [$firstDay, $lastDay])
                    ->orWhere(function ($qq) use ($firstDay, $lastDay) {

                        $qq->where('start_date', '<=', $firstDay)
                            ->where('end_date', '>=', $lastDay);
                    });
            })
            ->get();

        $leaveDates = [];

        foreach ($approvedLeaves as $leave) {

            $period = new DatePeriod(
                Carbon::parse($leave->start_date),
                new DateInterval('P1D'),
                Carbon::parse($leave->end_date)->addDay()
            );

            foreach ($period as $day) {
                $leaveDates[] = $day->format('Y-m-d');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Absent Days
        |--------------------------------------------------------------------------
        */

        $absentDays = 0;

        for ($date = $firstDay->copy(); $date->lte($lastDay); $date->addDay()) {

            $currentDate = $date->format('Y-m-d');
            $dayName = strtolower($date->format('l'));

            if (!isset($weeklySchedule[$dayName]) || strtolower($weeklySchedule[$dayName]->status) != 'open') {
                continue;
            }

            if (in_array($currentDate, $holidayDates)) {
                continue;
            }

            if (in_array($currentDate, $leaveDates)) {
                continue;
            }

            if (in_array($currentDate, $attendanceDates)) {
                continue;
            }

            $absentDays++;
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Calculation
        |--------------------------------------------------------------------------
        */

        $grossSalary = (float)$employee->total_addition;

        $perDaySalary = round($grossSalary / 30, 2);

        $lateDeductionDays = intdiv($lateCount, 3);

        $earlyLogoutDeductionDays = intdiv($earlyLogoutCount, 3);

        $lopDays = $absentDays + $lateDeductionDays + $earlyLogoutDeductionDays;

        $lop = $perDaySalary * $lopDays;

        $baseGross = $grossSalary - $lop;

        $medicalAllowance = 1250;
        $conveyance = 1600;

        $basicPercentage = isset($employee->basic_percentage)
            ? (float) $employee->basic_percentage
            : 50;

        $basicSalary = $baseGross * ($basicPercentage / 100);

        $hra = $basicSalary * 0.50;

        $specialAllowance = $baseGross - ($basicSalary + $hra + $medicalAllowance + $conveyance);

        if ($specialAllowance < 0) {
            $specialAllowance = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | PF
        |--------------------------------------------------------------------------
        */

        $pf = 0;

        if ($employee->epf_applicable) {
            $pf = min($basicSalary * 0.12, 1800);
        }

        /*
        |--------------------------------------------------------------------------
        | ESI
        |--------------------------------------------------------------------------
        */

        $esi = 0;

        if ($employee->esic_applicable && $baseGross <= 21000) {
            $esi = $baseGross * 0.0075;
        }

        /*
        |--------------------------------------------------------------------------
        | PT
        |--------------------------------------------------------------------------
        */

        $pt = 0;

        if ($employee->ptax_applicable) {

            if ($baseGross > 10000 && $baseGross <= 15000)
                $pt = 110;
            elseif ($baseGross <= 25000)
                $pt = 130;
            elseif ($baseGross <= 40000)
                $pt = 150;
            elseif ($baseGross > 40000)
                $pt = 200;
        }

        /*
        |--------------------------------------------------------------------------
        | TDS
        |--------------------------------------------------------------------------
        */

        $tds = $employee->tds_applicable ? (float)$employee->tds : 0;

        /*
        |--------------------------------------------------------------------------
        | Loan
        |--------------------------------------------------------------------------
        */

        $loan = (float)($employee->loan_deduction ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Advance
        |--------------------------------------------------------------------------
        */

        $advance = DB::table('expenses')
            ->where('employee_id', $employee->empId)
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->sum('expense_amt');

        /*
        |--------------------------------------------------------------------------
        | LWF
        |--------------------------------------------------------------------------
        */

        $lwf = $employee->lwf_applicable ? (float)$employee->lwf_deduct : 0;

        /*
        |--------------------------------------------------------------------------
        | Totals
        |--------------------------------------------------------------------------
        */

        $totalEarnings = $basicSalary + $hra + $conveyance + $medicalAllowance + $specialAllowance;

        $totalDeductions = $pf + $esi + $pt + $tds + $loan + $advance + $lwf + $lop;

        $netSalary = $totalEarnings - ($pf + $esi + $pt + $tds + $loan + $advance + $lwf);

        return [
            'gross_salary' => round($basicSalary, 2), // Same as your stored payslip
            'basic_salary' => round($basicSalary, 2),
            'net_salary'   => round($netSalary, 2),
            'pf'           => round($pf, 2),
            'esi'          => round($esi, 2),
            'pt'           => round($pt, 2),
            'tds'          => round($tds, 2),
            'lwf'          => round($lwf, 2),
            'lop'          => round($lop, 2),
            'total_deductions' => round($totalDeductions, 2),
            'overtime_hours' => round($overtimeHours, 2),
            'absent_days' => $absentDays,
        ];
    }

    //------- Payroll Register Report -------//

    public function payrollRegister(Request $request)
    {
        $ownerId   = currentOwnerId();
        $authUserId = auth()->id();

        $month = $request->month;
        $fy    = $request->fy;

        // =========================================================
        // Month Name => Month Number
        // =========================================================
        $months = [
            'January'   => 1,
            'February'  => 2,
            'March'     => 3,
            'April'     => 4,
            'May'       => 5,
            'June'      => 6,
            'July'      => 7,
            'August'    => 8,
            'September' => 9,
            'October'   => 10,
            'November'  => 11,
            'December'  => 12,
        ];

        $currentMonth = $months[$month] ?? date('n');

        [$fyStart, $fyEnd] = explode('-', $fy);

        // =========================================================
        // Previous Payroll Month
        // =========================================================
        $previousMonth = $currentMonth - 1;
        $previousFY    = $fy;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousFY = ($fyStart - 1) . '-' . ($fyEnd - 1);
        }

        // =========================================================
        // Previous Payroll Month Date Range
        // =========================================================
        [$previousFYStart, $previousFYEnd] = explode('-', $previousFY);

        $previousYear = ($previousMonth >= 4)
            ? $previousFYStart
            : $previousFYEnd;

        $payrollStart = Carbon::create(
            $previousYear,
            $previousMonth,
            1
        )->startOfMonth();

        $payrollEnd = $payrollStart->copy()->endOfMonth();

        // =========================================================
        // Employees
        //
        // Employee should be included if:
        //
        // 1. Joined on or before payroll month end
        //
        // 2. If resigned/terminated:
        //    resignation date must be on/after payroll month start
        //
        // 3. Confirmed / In Probation employees are included
        // =========================================================
        $employees = DB::table('employees as e')
            ->leftJoin('users as u', 'u.id', '=', 'e.empId')
            ->leftJoin('designations as d', 'd.id', '=', 'e.desig_id')

            ->where(function ($query) use ($ownerId, $authUserId) {

                if ($ownerId !== null) {
                    $query->where('e.added_by', $ownerId);
                }

                if ($authUserId !== null) {
                    $query->orWhere('e.added_by', $authUserId);
                }

                if ($ownerId === null && $authUserId === null) {
                    $query->whereRaw('1 = 0');
                }
            })

            // =====================================================
            // Joining Date
            // Employee must have joined before/during this month
            // =====================================================
            ->where(function ($q) use ($payrollEnd) {

                $q->whereNull('e.joining_date')
                    ->orWhereDate('e.joining_date', '<=', $payrollEnd);
            })

            // =====================================================
            // Employee Status
            // =====================================================
            ->where(function ($q) use ($payrollStart) {

                // Normal active employees
                $q->whereIn('e.emp_status', [
                    'Confirmed',
                    'In Probation'
                ])

                // Resigned / Terminated employees
                // are included if they worked during this month
                ->orWhere(function ($qq) use ($payrollStart) {

                    $qq->whereIn('e.emp_status', [
                        'Resigned',
                        'Terminated'
                    ])
                    ->whereNotNull('e.regine_date')
                    ->whereDate(
                        'e.regine_date',
                        '>=',
                        $payrollStart
                    );
                });
            })

            ->select(
                'e.*',
                'u.name',
                'd.designation_name'
            )
            ->get();

        // =========================================================
        // Existing Payslips
        //
        // Get all payslips for this payroll month at once
        // instead of querying inside every employee loop.
        // =========================================================
        $existingPayslips = DB::table('user_payslip')
            ->where(function ($query) use ($ownerId, $authUserId) {

                if ($ownerId !== null) {
                    $query->where('added_by', $ownerId);
                }

                if ($authUserId !== null) {
                    $query->orWhere('added_by', $authUserId);
                }

                if ($ownerId === null && $authUserId === null) {
                    $query->whereRaw('1 = 0');
                }
            })
            ->where('financial_year', $previousFY)
            ->where('month', $previousMonth)
            ->get()
            ->keyBy('user_emp_id');

        // =========================================================
        // Final Payroll Register
        // =========================================================
        foreach ($employees as $employee) {

            // Default values
            $employee->gross_salary       = 0;
            $employee->basic_salary       = 0;
            $employee->net_salary         = 0;

            $employee->provident_fund     = 0;
            $employee->esi                = 0;
            $employee->ptax               = 0;
            $employee->tds                = 0;
            $employee->lwf                = 0;
            $employee->lop                = 0;

            $employee->advance            = 0;
            $employee->loan_deduction     = 0;

            $employee->total_deductions   = 0;

            $employee->overtime_hours     = 0;
            $employee->absent_days        = 0;

            $employee->payment_status     = 'Payment Pending';

            // =====================================================
            // CASE A
            // Payslip Already Generated
            // =====================================================
            if (isset($existingPayslips[$employee->empId])) {

                $payslip = $existingPayslips[$employee->empId];

                $response = json_decode(
                    $payslip->emp_salary_slip_response,
                    true
                );

                $salary = $response['visible_data']['final_salary_calculation']
                    ?? [];

                // -------------------------------------------------
                // Salary values from generated payslip
                // -------------------------------------------------
                $employee->basic_salary = (float) (
                    $salary['basic_salary'] ?? 0
                );

                $employee->gross_salary = (float) (
                    $salary['gross_salary']
                    ?? $salary['basic_salary']
                    ?? 0
                );

                $employee->net_salary = (float) (
                    $salary['net_salary'] ?? 0
                );

                // -------------------------------------------------
                // Deductions
                // -------------------------------------------------
                $employee->provident_fund = (float) (
                    $salary['provident_fund'] ?? 0
                );

                $employee->esi = (float) (
                    $salary['esi'] ?? 0
                );

                $employee->ptax = (float) (
                    $salary['ptax'] ?? 0
                );

                $employee->tds = (float) (
                    $salary['tds'] ?? 0
                );

                $employee->lwf = (float) (
                    $salary['lwf'] ?? 0
                );

                $employee->lop = (float) (
                    $salary['lop'] ?? 0
                );

                $employee->total_deductions = (float) (
                    $salary['total_deductions'] ?? 0
                );

                $employee->overtime_hours = (float) (
                    $salary['overtime_hours'] ?? 0
                );

                $employee->absent_days = (float) (
                    $salary['absent_days'] ?? 0
                );

                // -------------------------------------------------
                // Advance / Loan
                // -------------------------------------------------
                $employee->advance = (float) (
                    $salary['advance']
                    ?? $payslip->advance
                    ?? 0
                );

                $employee->loan_deduction = (float) (
                    $salary['loan']
                    ?? $salary['loan_deduction']
                    ?? $payslip->loan_deduction
                    ?? 0
                );

                // -------------------------------------------------
                // Payment Status
                // -------------------------------------------------
                $employee->payment_status =
                    ($payslip->payment_status ?? '') === 'Done'
                        ? 'Salary Done'
                        : 'Payment Pending';

                $employee->payslip_generated = true;
                $employee->payslip_id = $payslip->id ?? null;
            }

            // =====================================================
            // CASE B
            // Payslip NOT Generated
            // Calculate using existing calculateEmployeeSalary()
            // =====================================================
            else {

                $salary = $this->calculateEmployeeSalary(
                    $employee,
                    $previousMonth,
                    $previousFY
                );

                // -------------------------------------------------
                // Salary
                // -------------------------------------------------
                $employee->gross_salary = (float) (
                    $salary['gross_salary'] ?? 0
                );

                $employee->basic_salary = (float) (
                    $salary['basic_salary'] ?? 0
                );

                $employee->net_salary = (float) (
                    $salary['net_salary'] ?? 0
                );

                // -------------------------------------------------
                // Deductions
                // -------------------------------------------------
                $employee->provident_fund = (float) (
                    $salary['pf'] ?? 0
                );

                $employee->esi = (float) (
                    $salary['esi'] ?? 0
                );

                $employee->ptax = (float) (
                    $salary['pt'] ?? 0
                );

                $employee->tds = (float) (
                    $salary['tds'] ?? 0
                );

                $employee->lwf = (float) (
                    $salary['lwf'] ?? 0
                );

                $employee->lop = (float) (
                    $salary['lop'] ?? 0
                );

                $employee->total_deductions = (float) (
                    $salary['total_deductions'] ?? 0
                );

                $employee->overtime_hours = (float) (
                    $salary['overtime_hours'] ?? 0
                );

                $employee->absent_days = (float) (
                    $salary['absent_days'] ?? 0
                );

                // -------------------------------------------------
                // Advance
                // -------------------------------------------------
                $employee->advance = (float) (
                    $employee->advance ?? 0
                );

                // -------------------------------------------------
                // Loan
                // -------------------------------------------------
                $employee->loan_deduction = (float) (
                    $employee->loan_deduction ?? 0
                );

                // -------------------------------------------------
                // No payslip = Payment Pending
                // -------------------------------------------------
                $employee->payment_status = 'Payment Pending';

                $employee->payslip_generated = false;
                $employee->payslip_id = null;
            }

            // =====================================================
            // Round Values
            // =====================================================
            $employee->gross_salary =
                round($employee->gross_salary, 2);

            $employee->basic_salary =
                round($employee->basic_salary, 2);

            $employee->net_salary =
                round($employee->net_salary, 2);

            $employee->provident_fund =
                round($employee->provident_fund, 2);

            $employee->esi =
                round($employee->esi, 2);

            $employee->ptax =
                round($employee->ptax, 2);

            $employee->tds =
                round($employee->tds, 2);

            $employee->lwf =
                round($employee->lwf, 2);

            $employee->lop =
                round($employee->lop, 2);

            $employee->advance =
                round($employee->advance, 2);

            $employee->loan_deduction =
                round($employee->loan_deduction, 2);

            $employee->total_deductions =
                round($employee->total_deductions, 2);

            $employee->overtime_hours =
                round($employee->overtime_hours, 2);

            // =====================================================
            // Useful Register Information
            // =====================================================
            $employee->payroll_month = $previousMonth;
            $employee->payroll_financial_year = $previousFY;

            $employee->joining_date =
                $employee->joining_date
                    ? Carbon::parse($employee->joining_date)->format('Y-m-d')
                    : null;

            $employee->regine_date =
                $employee->regine_date
                    ? Carbon::parse($employee->regine_date)->format('Y-m-d')
                    : null;
        }

        return response()->json($employees);
    }

    // public function payrollRegister(Request $request)
    // {
    //     $ownerId = currentOwnerId();
    //     $authUserId = auth()->id();

    //     $month = $request->month;
    //     $fy = $request->fy;

    //     // Month Name => Month Number
    //     $months = [
    //         'January' => 1,
    //         'February' => 2,
    //         'March' => 3,
    //         'April' => 4,
    //         'May' => 5,
    //         'June' => 6,
    //         'July' => 7,
    //         'August' => 8,
    //         'September' => 9,
    //         'October' => 10,
    //         'November' => 11,
    //         'December' => 12,
    //     ];

    //     $currentMonth = $months[$month];

    //     [$fyStart, $fyEnd] = explode('-', $fy);

    //     // Previous Month
    //     $previousMonth = $currentMonth - 1;
    //     $previousFY = $fy;

    //     if ($previousMonth == 0) {
    //         $previousMonth = 12;
    //         $previousFY = ($fyStart - 1) . '-' . ($fyEnd - 1);
    //     }

    //     $employees = DB::table('employees as e')
    //         ->leftJoin('users as u', 'u.id', '=', 'e.empId')
    //         ->leftJoin('designations as d', 'd.id', '=', 'e.desig_id')
    //         ->where(function ($query) use ($ownerId, $authUserId) {
    //             if ($ownerId !== null) {
    //                 $query->where('e.added_by', $ownerId);
    //             }
    //             if ($authUserId !== null) {
    //                 $query->orWhere('e.added_by', $authUserId);
    //             }
    //             if ($ownerId === null && $authUserId === null) {
    //                 $query->whereRaw('1 = 0');
    //             }
    //         })
    //         ->select(
    //             'e.empId',
    //             'e.employee_id',
    //             'u.name',
    //             'd.designation_name',
    //             'e.joining_date',
    //             'e.total_addition',
    //             'e.net_sal',

    //             DB::raw("CASE WHEN e.epf_applicable=1 THEN e.provident_fund ELSE 0 END as provident_fund"),
    //             DB::raw("CASE WHEN e.esic_applicable=1 THEN e.esi ELSE 0 END as esi"),
    //             DB::raw("CASE WHEN e.ptax_applicable=1 THEN e.ptax ELSE 0 END as ptax"),
    //             DB::raw("CASE WHEN e.tds_applicable=1 THEN e.tds ELSE 0 END as tds")
    //         )
    //         ->get();

    //     foreach ($employees as $employee) {

    //         $paid = DB::table('user_payslip')
    //             ->where('user_emp_id', $employee->empId)
    //             ->where('financial_year', $previousFY)
    //             ->where('month', $previousMonth)
    //             ->exists();

    //         $employee->advance = 0;
    //         $employee->loan_deduction = 0;
    //         $employee->payment_status = $paid ? 'Salary Done' : 'Payment Pending';
    //     }

    //     return response()->json($employees);
    // }

    public function attendanceRegister(Request $request)
    {
        $ownerId = currentOwnerId();

        $month = Carbon::parse('1 ' . $request->month)->month;

        [$fyStart, $fyEnd] = explode('-', $request->fy);

        $year = ($month >= 4) ? $fyStart : $fyEnd;

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Weekly Schedule
        |--------------------------------------------------------------------------
        */

        $weeklySchedule = DB::table('weekly_schedules')
            ->where('added_by', $ownerId)
            ->get()
            ->keyBy(function ($row) {
                return strtolower($row->day);
            });

        /*
        |--------------------------------------------------------------------------
        | Holidays
        |--------------------------------------------------------------------------
        */

        $holidayDates = DB::table('holidays')
            ->where('added_by', $ownerId)
            ->whereBetween('holidayDate', [$startDate, $endDate])
            ->pluck('holidayDate')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        $employees = DB::table('employees')
            ->where('added_by', $ownerId)
            ->get();

        $data = [];

        foreach ($employees as $employee) {

            $user = DB::table('users')
                ->where('id', $employee->empId)
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Attendance Records
            |--------------------------------------------------------------------------
            */

            $attendanceRecords = DB::table('attendance')
                ->where('userId', $employee->empId)
                ->whereBetween('present_date', [$startDate, $endDate])
                ->get();

            $attendanceDays = $attendanceRecords->count();

            $wfhDays = $attendanceRecords
                ->where('work_location_status', 'WFH')
                ->count();

            $attendanceDates = $attendanceRecords
                ->pluck('present_date')
                ->map(function ($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | Late Hours & Overtime Hours
            |--------------------------------------------------------------------------
            */

            $lateHours = 0;
            $overtimeHours = 0;

            foreach ($attendanceRecords as $record) {

                $dayName = strtolower(Carbon::parse($record->present_date)->format('l'));

                $schedule = $weeklySchedule[$dayName] ?? null;

                if (!$schedule || $schedule->status != 'open') {
                    continue;
                }

                // Late Hours
                if (!empty($record->in_time)) {

                    $openingTime = Carbon::parse($record->present_date . ' ' . $schedule->opening_time);

                    $inTime = Carbon::parse($record->present_date . ' ' . $record->in_time);

                    if ($inTime->gt($openingTime)) {

                        $lateHours += $openingTime->diffInMinutes($inTime) / 60;
                    }
                }

                // Overtime Hours
                if (!empty($record->out_time)) {

                    $closingTime = Carbon::parse($record->present_date . ' ' . $schedule->closing_time);

                    $outTime = Carbon::parse($record->present_date . ' ' . $record->out_time);

                    if ($outTime->gt($closingTime)) {

                        $overtimeHours += $closingTime->diffInMinutes($outTime) / 60;
                    }
                }
            }

            $lateHours = round($lateHours, 2);
            $overtimeHours = round($overtimeHours, 2);

            /*
            |--------------------------------------------------------------------------
            | Approved Leave
            |--------------------------------------------------------------------------
            */

            $approvedLeaves = DB::table('leaves')
                ->where('employee_id', $employee->employee_id)
                ->where('status', 'approved')
                ->where(function ($q) use ($startDate, $endDate) {

                    $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($qq) use ($startDate, $endDate) {

                            $qq->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->get();

            $leaveDays = 0;
            $leaveDates = [];

            foreach ($approvedLeaves as $leave) {

                $leaveStart = Carbon::parse($leave->start_date);

                $leaveEnd = Carbon::parse($leave->end_date);

                if ($leaveStart->lt($startDate)) {
                    $leaveStart = $startDate->copy();
                }

                if ($leaveEnd->gt($endDate)) {
                    $leaveEnd = $endDate->copy();
                }

                $leaveDays += $leaveStart->diffInDays($leaveEnd) + 1;

                $period = CarbonPeriod::create($leaveStart, $leaveEnd);

                foreach ($period as $day) {

                    $leaveDates[] = $day->format('Y-m-d');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Absent Days
            |--------------------------------------------------------------------------
            */

            $absentDays = 0;

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {

                $currentDate = $date->format('Y-m-d');

                $dayName = strtolower($date->format('l'));

                // Weekly Off
                if (!isset($weeklySchedule[$dayName]) || $weeklySchedule[$dayName]->status != 'open') {
                    continue;
                }

                // Holiday
                if (in_array($currentDate, $holidayDates)) {
                    continue;
                }

                // Leave
                if (in_array($currentDate, $leaveDates)) {
                    continue;
                }

                // Present
                if (in_array($currentDate, $attendanceDates)) {
                    continue;
                }

                $absentDays++;
            }

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            $data[] = [
                'employee_id'     => $employee->employee_id,
                'employee_name'   => $user->name ?? '',
                'attendance_days' => $attendanceDays,
                'absent_days'     => $absentDays,
                'leave_days'      => $leaveDays,
                'late_hours'      => $lateHours,
                'overtime_hours'  => $overtimeHours,
                'wfh_days'        => $wfhDays,
            ];
        }

        return response()->json($data);
    }

    //------- Payslip List -------//
    public function getPayslipList(Request $request)
    {
        $ownerId = currentOwnerId();

        $financialYear = $request->financial_year;
        $month = Carbon::parse('1 ' . $request->month)->month;

        $payslips = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear)
            ->where('user_payslip.month', $month)
            ->select(
                'user_payslip.id',
                'user_payslip.user_emp_id',
                'employees.employee_id',
                'users.name',
                'user_payslip.date',
                'user_payslip.payment_date',
                'user_payslip.payment_trans_id'
            )
            ->orderBy('users.name')
            ->get();

        return response()->json($payslips);
    }

    //------- Update Payslips -------//
    public function updatePayslips(Request $request)
    {
        $ownerId = currentOwnerId();

        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No payslip IDs provided.'], 422);
        }

        $paymentDate   = $request->input('payment_date');
        $transactionId = $request->input('transaction_id');
        $salaryAmount  = (float) $request->input('amount_salary_input', 0);

        $update = ['payment_status' => 'Done'];

        if ($paymentDate) {
            $update['payment_date'] = $paymentDate;
        }

        if ($transactionId) {
            $update['payment_trans_id'] = $transactionId;
        }

        try {
            $affected = DB::table('user_payslip')
                ->whereIn('id', $ids)
                ->where('added_by', $ownerId)
                ->update($update);

            // ------- Single Payment Voucher from form data -------
            // One PV for the entire batch using the amount entered in the form
            if ($salaryAmount > 0) {

                $firstPayslip = DB::table('user_payslip as up')
                    ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
                    ->where('up.id', $ids[0])
                    ->where('up.added_by', $ownerId)
                    ->select('up.id', 'up.payslip_no', 'up.date', 'e.propId')
                    ->first();

                $this->paymentVoucherService->storePaymentVoucherEntries(
                    $firstPayslip->id ?? $ids[0],
                    'Payroll',
                    $salaryAmount,
                    [
                        'propId'        => $firstPayslip->propId ?? null,
                        'date'          => $paymentDate ?: ($firstPayslip->date ?? now()->toDateString()),
                        'reference_no'  => $transactionId ?: ($firstPayslip->payslip_no ?? null),
                        'party_name'    => 'Salary Payment (' . count($ids) . ' employees)',
                        'payroll_month' => '',
                        'added_by'      => $ownerId,
                    ]
                );
            }

            return response()->json(['message' => "Updated {$affected} record(s)", 'updated' => $affected]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    //------- Update TDS -------//
    // public function updateTds(Request $request)
    // {
    //     $ownerId = currentOwnerId();

    //     $ids = (array) $request->input('ids', []);

    //     if (empty($ids)) {
    //         return response()->json(['message' => 'No TDS IDs provided.'], 422);
    //     }

    //     $update = [];

    //     if ($request->filled('tds_tan'))                { $update['tds_tan']                = $request->tds_tan; }
    //     if ($request->filled('tds_financial_year'))     { $update['tds_financial_year']     = $request->tds_financial_year; }
    //     if ($request->filled('tds_payment_from_month')) { $update['tds_payment_from_month'] = $request->tds_payment_from_month; }
    //     if ($request->filled('tds_payment_to_month'))   { $update['tds_payment_to_month']   = $request->tds_payment_to_month; }
    //     if ($request->filled('tds_nature_of_payment'))  { $update['tds_nature_of_payment']  = $request->tds_nature_of_payment; }
    //     if ($request->filled('tds_amount'))             { $update['tds_amount']             = $request->tds_amount; }
    //     if ($request->filled('tds_cin'))                { $update['tds_cin']                = $request->tds_cin; }
    //     if ($request->filled('tds_challan_no'))         { $update['tds_challan_no']         = $request->tds_challan_no; }
    //     if ($request->filled('tds_bsr_code'))           { $update['tds_bsr_code']           = $request->tds_bsr_code; }
    //     if ($request->filled('tds_deposit_date'))       { $update['tds_deposit_date']       = $request->tds_deposit_date; }
    //     if ($request->filled('tds_tender_date'))        { $update['tds_tender_date']        = $request->tds_tender_date; }

    //     $update['tds_deposit_status'] = 'Done';

    //     try {
    //         $affected = DB::table('user_payslip')
    //             ->whereIn('id', $ids)
    //             ->where('added_by', $ownerId)
    //             ->update($update);

    //         // Single Receipt Voucher for TDS deposited amount
    //         $tdsAmount = (float) $request->input('tds_amount', 0);
    //         if ($tdsAmount > 0) {
    //             $firstPayslip = DB::table('user_payslip as up')
    //                 ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
    //                 ->where('up.id', $ids[0])
    //                 ->where('up.added_by', $ownerId)
    //                 ->select('up.id', 'up.payslip_no', 'up.date', 'e.propId')
    //                 ->first();

    //             $this->paymentVoucherService->storePaymentVoucherEntries(
    //                 $firstPayslip->id ?? $ids[0],
    //                 'Payroll',
    //                 $tdsAmount,
    //                 [
    //                     'propId'        => $firstPayslip->propId ?? null,
    //                     'date'          => $request->tds_deposit_date ?: ($firstPayslip->date ?? now()->toDateString()),
    //                     'reference_no'  => $request->tds_challan_no ?: ($firstPayslip->payslip_no ?? null),
    //                     'party_name'    => 'TDS Deposit (' . count($ids) . ' records)',
    //                     'payroll_month' => '',
    //                     'added_by'      => $ownerId,
    //                     'net_salary'    => 0,
    //                     'pf'            => 0,
    //                     'esi'           => 0,
    //                     'tds'           => $tdsAmount,
    //                     'lwf'           => 0,
    //                     'ptax'          => 0,
    //                     'loan'          => 0,
    //                 ]
    //             );
    //         }

    //         return response()->json(['message' => "Updated {$affected} record(s)", 'updated' => $affected]);

    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'TDS update failed: ' . $e->getMessage()], 500);
    //     }
    // }

    public function updateTds(Request $request)
    {
        $ownerId = currentOwnerId();

        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'message' => 'No TDS IDs provided.'
            ], 422);
        }

        try {

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | TDS Deposit Amount
            |--------------------------------------------------------------------------
            */
            $tdsAmount = (float) $request->input('tds_amount', 0);

            /*
            |--------------------------------------------------------------------------
            | Save TDS Deposit Record
            |--------------------------------------------------------------------------
            */
            $tdsDepositId = DB::table('tds_deposit_records')->insertGetId([
                'added_by'               => $ownerId,

                'tds_tan'                => $request->input('tds_tan'),

                'tds_financial_year'     => $request->input('tds_financial_year'),

                'tds_payment_from_month' => $request->input('tds_payment_from_month'),

                'tds_payment_to_month'   => $request->input('tds_payment_to_month'),

                'tds_nature_of_payment'  => $request->input('tds_nature_of_payment'),

                'tds_amount'             => $tdsAmount,

                'tds_cin'                => $request->input('tds_cin'),

                'tds_utr'                => $request->input('tds_utr'),

                'tds_deposit_date'       => $request->input('tds_deposit_date'),

                'tds_bsr_code'           => $request->input('tds_bsr_code'),

                'tds_challan_no'         => $request->input('tds_challan_no'),

                'tds_tender_date'        => $request->input('tds_tender_date'),

                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Selected Payslips
            |--------------------------------------------------------------------------
            */
            $update = [];

            if ($request->filled('tds_tan')) {
                $update['tds_tan'] = $request->tds_tan;
            }

            if ($request->filled('tds_financial_year')) {
                $update['tds_financial_year'] = $request->tds_financial_year;
            }

            if ($request->filled('tds_payment_from_month')) {
                $update['tds_payment_from_month'] =
                    $request->tds_payment_from_month;
            }

            if ($request->filled('tds_payment_to_month')) {
                $update['tds_payment_to_month'] =
                    $request->tds_payment_to_month;
            }

            if ($request->filled('tds_nature_of_payment')) {
                $update['tds_nature_of_payment'] =
                    $request->tds_nature_of_payment;
            }

            if ($request->filled('tds_amount')) {
                $update['tds_amount'] =
                    $request->tds_amount;
            }

            if ($request->filled('tds_cin')) {
                $update['tds_cin'] =
                    $request->tds_cin;
            }

            if ($request->filled('tds_challan_no')) {
                $update['tds_challan_no'] =
                    $request->tds_challan_no;
            }

            if ($request->filled('tds_bsr_code')) {
                $update['tds_bsr_code'] =
                    $request->tds_bsr_code;
            }

            if ($request->filled('tds_deposit_date')) {
                $update['tds_deposit_date'] =
                    $request->tds_deposit_date;
            }

            if ($request->filled('tds_tender_date')) {
                $update['tds_tender_date'] =
                    $request->tds_tender_date;
            }

            // TDS deposited
            $update['tds_deposit_status'] = 'Done';

            $affected = DB::table('user_payslip')
                ->whereIn('id', $ids)
                ->where('added_by', $ownerId)
                ->update($update);

            /*
            |--------------------------------------------------------------------------
            | Single Receipt Voucher for TDS Deposited Amount
            |--------------------------------------------------------------------------
            */
            if ($tdsAmount > 0) {

                $firstPayslip = DB::table('user_payslip as up')
                    ->leftJoin(
                        'employees as e',
                        'e.empId',
                        '=',
                        'up.user_emp_id'
                    )
                    ->where('up.id', $ids[0])
                    ->where('up.added_by', $ownerId)
                    ->select(
                        'up.id',
                        'up.payslip_no',
                        'up.date',
                        'e.propId'
                    )
                    ->first();

                $this->paymentVoucherService->storePaymentVoucherEntries(
                    $firstPayslip->id ?? $ids[0],
                    'Payroll',
                    $tdsAmount,
                    [
                        'propId' =>
                            $firstPayslip->propId ?? null,

                        'date' =>
                            $request->tds_deposit_date
                            ?: ($firstPayslip->date
                                ?? now()->toDateString()),

                        'reference_no' =>
                            $request->tds_challan_no
                            ?: ($firstPayslip->payslip_no ?? null),

                        'party_name' =>
                            'TDS Deposit (' . count($ids) . ' records)',

                        'payroll_month' => '',

                        'added_by' => $ownerId,

                        'net_salary' => 0,
                        'pf'         => 0,
                        'esi'        => 0,
                        'tds'        => $tdsAmount,
                        'lwf'        => 0,
                        'ptax'       => 0,
                        'loan'       => 0,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Commit Transaction
            |--------------------------------------------------------------------------
            */
            DB::commit();

            return response()->json([
                'message'        => "Updated {$affected} record(s)",
                'updated'        => $affected,
                'tds_deposit_id' => $tdsDepositId
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'TDS update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    

    //------- TDS List -------//

    public function getTdsList(Request $request)
    {
        $ownerId = currentOwnerId();

        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        $query = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear);

        // Apply filter based on filter type
        if ($filterType == 'monthly' && !empty($period)) {

            $month = Carbon::parse('1 ' . $period)->month;
            $query->where('user_payslip.month', $month);

        } elseif ($filterType == 'quarterly' && !empty($period)) {

            switch ($period) {
                case 'Q1':
                    $months = [4, 5, 6];
                    break;

                case 'Q2':
                    $months = [7, 8, 9];
                    break;

                case 'Q3':
                    $months = [10, 11, 12];
                    break;

                case 'Q4':
                    $months = [1, 2, 3];
                    break;

                default:
                    $months = [];
            }

            if (!empty($months)) {
                $query->whereIn('user_payslip.month', $months);
            }

        } elseif ($filterType == 'half-yearly' && !empty($period)) {

            switch ($period) {
                case 'H1':
                    $months = [4, 5, 6, 7, 8, 9];
                    break;

                case 'H2':
                    $months = [10, 11, 12, 1, 2, 3];
                    break;

                default:
                    $months = [];
            }

            if (!empty($months)) {
                $query->whereIn('user_payslip.month', $months);
            }

        } elseif ($filterType == 'yearly') {

            // No additional month filter

        }

        // Only employees having TDS > 0
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        user_payslip.emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.tds'
                    )
                ) AS DECIMAL(15,2)
            ) > 0
        ");

        $tds = $query->select(
                'user_payslip.id',
                'user_payslip.user_emp_id',
                'user_payslip.month',
                'user_payslip.financial_year',
                'employees.employee_id',
                'employees.pan_number',
                'users.name',
                'user_payslip.tds_challan_no',
                'user_payslip.tds_bsr_code',
                'user_payslip.tds_deposit_date',
                'user_payslip.tds_tender_date',
                'user_payslip.tds_deposit_status',
                'user_payslip.tds_tan',
                'user_payslip.tds_financial_year',
                'user_payslip.tds_nature_of_payment',
                'user_payslip.tds_cin',
                // TDS amount from the dedicated column (falls back to JSON extraction)
                DB::raw("
                    COALESCE(
                        NULLIF(user_payslip.tds_amount, 0),
                        CAST(
                            JSON_UNQUOTE(JSON_EXTRACT(
                                user_payslip.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.tds'))
                            AS DECIMAL(15,2)
                        )
                    ) as tds_amount
                "),
                // Gross salary (salary amount for FVU)
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(
                            user_payslip.emp_salary_slip_response,
                            '$.visible_data.salary_details.gross_salary'))
                        AS DECIMAL(15,2)
                    ) as gross_salary
                ")
            )
            ->orderBy('users.name')
            ->get();

        // Fetch company TAN for the owner
        $company = \DB::table('company_profiles')
            ->where('userId', $ownerId)
            ->value('comp_tan');

        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        foreach ($tds as $row) {
            $row->comp_tan    = $company ?? '—';
            $row->month_name  = $monthNames[$row->month] ?? $row->month;
        }

        return response()->json($tds);
    }

    //------- PF List -------//
    public function getPfList(Request $request)
    {
        $ownerId = currentOwnerId();

        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        $query = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear);

        // Monthly
        if ($filterType == 'monthly' && !empty($period)) {
            $month = Carbon::parse('1 '.$period)->month;
            $query->where('user_payslip.month', $month);
        }
        // Quarterly
        elseif ($filterType == 'quarterly' && !empty($period)) {
            switch ($period) {
                case 'Q1': $months = [4,5,6]; break;
                case 'Q2': $months = [7,8,9]; break;
                case 'Q3': $months = [10,11,12]; break;
                case 'Q4': $months = [1,2,3]; break;
                default:   $months = [];
            }
            if ($months) $query->whereIn('user_payslip.month', $months);
        }
        // Half Yearly
        elseif ($filterType == 'half-yearly' && !empty($period)) {
            switch ($period) {
                case 'H1': $months = [4,5,6,7,8,9]; break;
                case 'H2': $months = [10,11,12,1,2,3]; break;
                default:   $months = [];
            }
            if ($months) $query->whereIn('user_payslip.month', $months);
        }

        // Only employees having PF > 0 in the JSON
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        emp_salary_slip_response,
                        '$.visible_data.salary_details.provident_fund'
                    )
                ) AS DECIMAL(12,2)
            ) > 0
        ");

        $records = $query->select(
                'user_payslip.id',
                'user_payslip.user_emp_id',
                'user_payslip.month',
                'user_payslip.financial_year',
                'employees.employee_id',
                'users.name',
                'employees.epf_no',
                'user_payslip.pf_trrn',
                'user_payslip.pf_crn',
                'user_payslip.pf_challan_generated_on',
                'user_payslip.pf_payment_confirmation_date',
                'user_payslip.pf_payment_status',

                'user_payslip.pf_establishment_id',
                'user_payslip.pf_wage_month',
                
                'user_payslip.pf_total_amount',
                'user_payslip.pf_payment_type',
                
                // Gross wages from JSON
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.salary_details.gross_salary'))
                        AS DECIMAL(15,2)
                    ) as gross_salary
                "),
                // Basic salary = EPF wages (capped at 15000 as per EPFO rules)
                DB::raw("
                    LEAST(
                        CAST(
                            JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.basic_salary'))
                            AS DECIMAL(15,2)
                        ),
                        15000
                    ) as epf_wages
                "),
                // Employee PF contribution (12%)
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.salary_details.provident_fund'))
                        AS DECIMAL(12,2)
                    ) as provident_fund
                "),
                // Absent days for NCP
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.attendance_details.total_absent'))
                        AS UNSIGNED
                    ) as ncp_days
                ")
            )
            ->orderBy('users.name')
            ->get();

        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        foreach ($records as $row) {
            $epfWages = (float)($row->epf_wages ?? 0);
            $empPf    = (float)($row->provident_fund ?? 0);

            // Employer EPS = 8.33% of EPF wages (max ₹1250)
            $empEps   = min(round($epfWages * 0.0833, 2), 1250.00);
            // Employer EPF difference = employee PF - EPS
            $empEpfDiff = max(round($empPf - $empEps, 2), 0);

            $row->eps_wages          = $epfWages;   // EPS wages = EPF wages
            $row->edli_wages         = $epfWages;   // EDLI wages = EPF wages
            $row->employer_eps       = $empEps;
            $row->employer_epf_diff  = $empEpfDiff;
            $row->month_name         = $monthNames[$row->month] ?? $row->month;
        }

        return response()->json($records);
    }

    //------- Update PF -------//
    public function updatePf(Request $request)
    {
        $ownerId = currentOwnerId();

        $request->validate(['ids' => 'required|array|min:1']);

        $ids = $request->ids;

        DB::table('user_payslip')
            ->whereIn('id', $ids)
            ->update([
                'pf_trrn'                      => $request->pf_trrn,
                'pf_challan_generated_on'       => $request->pf_challan_generated,
                'pf_establishment_id'           => $request->pf_establishment_id,
                'pf_wage_month'                 => $request->pf_wage_month,
                'pf_total_amount'               => $request->pf_total_amount,
                'pf_payment_type'               => $request->pf_payment_type,
                'pf_crn'                        => $request->pf_crn,
                'pf_payment_confirmation_date'  => $request->pf_payment_date,
                'pf_payment_status'             => 'Done',
                'updated_at'                    => now(),
            ]);

        // Single Receipt Voucher for PF deposited amount
        $pfAmount = (float) $request->input('pf_total_amount', 0);
        if ($pfAmount > 0) {
            $firstPayslip = DB::table('user_payslip as up')
                ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
                ->where('up.id', $ids[0])
                ->where('up.added_by', $ownerId)
                ->select('up.id', 'up.payslip_no', 'up.date', 'e.propId')
                ->first();

            $this->paymentVoucherService->storePaymentVoucherEntries(
                $firstPayslip->id ?? $ids[0],
                'Payroll',
                $pfAmount,
                [
                    'propId'        => $firstPayslip->propId ?? null,
                    'date'          => $request->pf_payment_date ?: ($firstPayslip->date ?? now()->toDateString()),
                    'reference_no'  => $request->pf_trrn ?: ($firstPayslip->payslip_no ?? null),
                    'party_name'    => 'PF Deposit (' . count($ids) . ' records)',
                    'payroll_month' => $request->pf_wage_month ?? '',
                    'added_by'      => $ownerId,
                    'net_salary'    => 0,
                    'pf'            => $pfAmount,
                    'esi'           => 0,
                    'tds'           => 0,
                    'lwf'           => 0,
                    'ptax'          => 0,
                    'loan'          => 0,
                ]
            );
        }

        return response()->json(['status' => true, 'message' => 'Selected PF records updated successfully.']);
    }

    //------- ESI List -------//
    public function getEsiList(Request $request)
    {
        $ownerId = currentOwnerId();

        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        $query = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear);

        // Monthly
        if ($filterType == 'monthly' && !empty($period)) {

            $month = Carbon::parse('1 ' . $period)->month;

            $query->where('user_payslip.month', $month);

        }

        // Quarterly
        elseif ($filterType == 'quarterly' && !empty($period)) {

            switch ($period) {
                case 'Q1':
                    $months = [4, 5, 6];
                    break;
                case 'Q2':
                    $months = [7, 8, 9];
                    break;
                case 'Q3':
                    $months = [10, 11, 12];
                    break;
                case 'Q4':
                    $months = [1, 2, 3];
                    break;
                default:
                    $months = [];
            }

            if ($months) {
                $query->whereIn('user_payslip.month', $months);
            }

        }

        // Half Yearly
        elseif ($filterType == 'half-yearly' && !empty($period)) {

            switch ($period) {
                case 'H1':
                    $months = [4, 5, 6, 7, 8, 9];
                    break;
                case 'H2':
                    $months = [10, 11, 12, 1, 2, 3];
                    break;
                default:
                    $months = [];
            }

            if ($months) {
                $query->whereIn('user_payslip.month', $months);
            }

        }

        // Only employees having ESI > 0
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.esi'
                    )
                ) AS DECIMAL(12,2)
            ) > 0
        ");

        $records = $query->select(
                'user_payslip.id',
                'user_payslip.user_emp_id',
                'user_payslip.month',
                'user_payslip.financial_year',
                'employees.employee_id',
                'employees.esic_no',
                'users.name',
                'user_payslip.esi_employer_code',
                'user_payslip.esi_employer_name',
                'user_payslip.esi_contribution_period',
                'user_payslip.esi_challan_no',
                'user_payslip.esi_challan_created_date',
                'user_payslip.esi_challan_submitted_date',
                'user_payslip.esi_amount_paid',
                'user_payslip.esi_transaction_no',
                'user_payslip.esi_payment_status',

                // Gross wages from JSON
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.salary_details.gross_salary'))
                        AS DECIMAL(15,2)
                    ) as gross_wages
                "),
                // Employee ESI (0.75%) from final_salary_calculation
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.final_salary_calculation.esi'))
                        AS DECIMAL(12,2)
                    ) as employee_esi
                "),
                // Attendance present days for ESIC upload sheet
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.attendance_details.total_present'))
                        AS UNSIGNED
                    ) as present_days
                "),
                // Total working days
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.month_details.total_working_days'))
                        AS UNSIGNED
                    ) as total_working_days
                ")
            )
            ->orderBy('users.name')
            ->get();

        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        foreach ($records as $row) {
            $empEsi  = (float)($row->employee_esi ?? 0);
            $gross   = (float)($row->gross_wages ?? 0);

            // ESI wages = gross (only applicable if gross <= 21000)
            $esiWages = $gross <= 21000 ? $gross : 0;

            // Employer ESI = 3.25% of gross (if applicable)
            $employerEsi = $esiWages > 0 ? round($esiWages * 0.0325, 2) : 0;

            $row->esi_wages     = $esiWages;
            $row->employer_esi  = $employerEsi;
            $row->total_esi     = round($empEsi + $employerEsi, 2);
            $row->month_name    = $monthNames[$row->month] ?? $row->month;
        }

        return response()->json($records);
    }

    //------- Update ESI -------//
    public function updateEsi(Request $request)
    {
        $ownerId = currentOwnerId();

        $request->validate(['ids' => 'required|array|min:1']);

        $ids = $request->ids;

        DB::table('user_payslip')
            ->whereIn('id', $ids)
            ->update([
                'esi_employer_code'          => $request->esi_employer_code,
                'esi_contribution_period'    => $request->esi_contribution_period,
                'esi_challan_no'             => $request->esi_challan_no,
                'esi_challan_created_date'   => $request->esi_challan_created_date,
                'esi_challan_submitted_date' => $request->esi_challan_submitted_date,
                'esi_amount_paid'            => $request->esi_amount_paid,
                'esi_transaction_no'         => $request->esi_transaction_no,
                'esi_payment_status'         => 'Done',
                'updated_at'                 => now(),
            ]);

        // Single Receipt Voucher for ESI deposited amount
        $esiAmount = (float) $request->input('esi_amount_paid', 0);
        if ($esiAmount > 0) {
            $firstPayslip = DB::table('user_payslip as up')
                ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
                ->where('up.id', $ids[0])
                ->where('up.added_by', $ownerId)
                ->select('up.id', 'up.payslip_no', 'up.date', 'e.propId')
                ->first();

            $this->paymentVoucherService->storePaymentVoucherEntries(
                $firstPayslip->id ?? $ids[0],
                'Payroll',
                $esiAmount,
                [
                    'propId'        => $firstPayslip->propId ?? null,
                    'date'          => $request->esi_challan_submitted_date ?: ($firstPayslip->date ?? now()->toDateString()),
                    'reference_no'  => $request->esi_challan_no ?: ($firstPayslip->payslip_no ?? null),
                    'party_name'    => 'ESI Deposit (' . count($ids) . ' records)',
                    'payroll_month' => $request->esi_contribution_period ?? '',
                    'added_by'      => $ownerId,
                    'net_salary'    => 0,
                    'pf'            => 0,
                    'esi'           => $esiAmount,
                    'tds'           => 0,
                    'lwf'           => 0,
                    'ptax'          => 0,
                    'loan'          => 0,
                ]
            );
        }

        return response()->json(['status' => true, 'message' => 'Selected ESI records updated successfully.']);
    }

    //------- PTAX List -------//
    public function getPtaxList(Request $request)
    {
        $ownerId = currentOwnerId();

        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        $query = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear);

        // Monthly
        if ($filterType == 'monthly' && !empty($period)) {

            $month = Carbon::parse('1 '.$period)->month;

            $query->where('user_payslip.month', $month);

        }

        // Quarterly
        elseif ($filterType == 'quarterly' && !empty($period)) {

            switch ($period) {

                case 'Q1':
                    $months = [4,5,6];
                    break;

                case 'Q2':
                    $months = [7,8,9];
                    break;

                case 'Q3':
                    $months = [10,11,12];
                    break;

                case 'Q4':
                    $months = [1,2,3];
                    break;

                default:
                    $months = [];

            }

            if ($months) {
                $query->whereIn('user_payslip.month', $months);
            }

        }

        // Half-Yearly
        elseif ($filterType == 'half-yearly' && !empty($period)) {

            switch ($period) {

                case 'H1':
                    $months = [4,5,6,7,8,9];
                    break;

                case 'H2':
                    $months = [10,11,12,1,2,3];
                    break;

                default:
                    $months = [];

            }

            if ($months) {
                $query->whereIn('user_payslip.month', $months);
            }

        }

        // Only PTAX > 0
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.ptax'
                    )
                ) AS DECIMAL(12,2)
            ) > 0
        ");

        $records = $query->select(
                'user_payslip.id',
                'user_payslip.user_emp_id',
                'user_payslip.financial_year',
                'user_payslip.month',
                'employees.employee_id',
                'users.name',
                'user_payslip.ptax_grips_payment_id',
                'user_payslip.ptax_payment_initiated_date',
                'user_payslip.ptax_brn',
                'user_payslip.ptax_grn',
                'user_payslip.ptax_period_from',
                'user_payslip.ptax_period_to',
                'user_payslip.ptax_payment_ref_no',
                'user_payslip.ptax_amount_paid',
                'user_payslip.ptax_payment_status',

                // PT deduction from final_salary_calculation
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.final_salary_calculation.ptax'))
                        AS DECIMAL(12,2)
                    ) AS ptax
                "),
                // Gross salary for slab display
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.salary_details.gross_salary'))
                        AS DECIMAL(15,2)
                    ) AS gross_salary
                ")
            )
            ->orderBy('users.name')
            ->get();

        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        foreach ($records as $row) {
            $gross = (float)($row->gross_salary ?? 0);
            $ptax  = (float)($row->ptax ?? 0);

            // Derive PT slab label from gross
            if ($gross <= 10000)       $slab = 'Up to ₹10,000 — Nil';
            elseif ($gross <= 15000)   $slab = '₹10,001–₹15,000 — ₹110';
            elseif ($gross <= 25000)   $slab = '₹15,001–₹25,000 — ₹130';
            elseif ($gross <= 40000)   $slab = '₹25,001–₹40,000 — ₹150';
            else                        $slab = 'Above ₹40,000 — ₹200';

            $row->pt_slab    = $slab;
            $row->month_name = $monthNames[$row->month] ?? $row->month;
        }

        return response()->json($records);
    }
    
    //------- PTAX Summary (grouped by period) -------//
    public function getPtaxSummary(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        // Fetch company registration details
        $company = DB::table('company_profiles')
            ->where('userId', $ownerId)
            ->select('comp_name', 'comp_ptax')
            ->first();

        $compName  = $company->comp_name  ?? '—';
        $compPtax  = $company->comp_ptax  ?? '—';

        $query = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear);

        // Period filters
        if ($filterType == 'monthly' && !empty($period)) {
            $month = Carbon::parse('1 ' . $period)->month;
            $query->where('user_payslip.month', $month);
        } elseif ($filterType == 'quarterly' && !empty($period)) {
            switch ($period) {
                case 'Q1': $months = [4, 5, 6]; break;
                case 'Q2': $months = [7, 8, 9]; break;
                case 'Q3': $months = [10, 11, 12]; break;
                case 'Q4': $months = [1, 2, 3]; break;
                default:   $months = [];
            }
            if (!empty($months)) $query->whereIn('user_payslip.month', $months);
        } elseif ($filterType == 'half-yearly' && !empty($period)) {
            switch ($period) {
                case 'H1': $months = [4, 5, 6, 7, 8, 9]; break;
                case 'H2': $months = [10, 11, 12, 1, 2, 3]; break;
                default:   $months = [];
            }
            if (!empty($months)) $query->whereIn('user_payslip.month', $months);
        }

        // Only employees having ptax > 0
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.ptax'
                    )
                ) AS DECIMAL(12,2)
            ) > 0
        ");

        $records = $query->select(
                'user_payslip.month',
                'user_payslip.financial_year',
                DB::raw("COUNT(DISTINCT user_payslip.user_emp_id) as employee_count"),
                DB::raw("
                    SUM(CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.salary_details.gross_salary'))
                        AS DECIMAL(15,2)
                    )) as total_gross_salary
                "),
                DB::raw("
                    SUM(CAST(
                        JSON_UNQUOTE(JSON_EXTRACT(emp_salary_slip_response,
                            '$.visible_data.final_salary_calculation.ptax'))
                        AS DECIMAL(12,2)
                    )) as total_ptax
                ")
            )
            ->groupBy('user_payslip.month', 'user_payslip.financial_year')
            ->orderBy('user_payslip.month')
            ->get();

        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        foreach ($records as $row) {
            $row->reg_no        = $compPtax;
            $row->employer_name = $compName;
            $row->month_name    = $monthNames[$row->month] ?? $row->month;
        }

        return response()->json($records);
    }

    public function updatePtax(Request $request)
    {
        $ownerId = currentOwnerId();

        $request->validate(['ids' => 'required|array|min:1']);

        $ids = $request->ids;

        DB::table('user_payslip')
            ->whereIn('id', $ids)
            ->update([
                'ptax_grips_payment_id'       => $request->ptax_grips_payment_id,
                'ptax_payment_initiated_date' => $request->ptax_payment_initiated_date,
                'ptax_brn'                    => $request->ptax_brn,
                'ptax_grn'                    => $request->ptax_grn,
                'ptax_period_from'            => $request->ptax_period_from,
                'ptax_period_to'              => $request->ptax_period_to,
                'ptax_payment_ref_no'         => $request->ptax_payment_ref_no,
                'ptax_amount_paid'            => $request->ptax_amount_paid,
                'ptax_payment_status'         => 'Done',
                'updated_at'                  => now(),
            ]);

        // Single Receipt Voucher for PTAX deposited amount
        $ptaxAmount = (float) $request->input('ptax_amount_paid', 0);
        if ($ptaxAmount > 0) {
            $firstPayslip = DB::table('user_payslip as up')
                ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
                ->where('up.id', $ids[0])
                ->where('up.added_by', $ownerId)
                ->select('up.id', 'up.payslip_no', 'up.date', 'e.propId')
                ->first();

            $this->paymentVoucherService->storePaymentVoucherEntries(
                $firstPayslip->id ?? $ids[0],
                'Payroll',
                $ptaxAmount,
                [
                    'propId'        => $firstPayslip->propId ?? null,
                    'date'          => $request->ptax_payment_initiated_date ?: ($firstPayslip->date ?? now()->toDateString()),
                    'reference_no'  => $request->ptax_payment_ref_no ?: ($firstPayslip->payslip_no ?? null),
                    'party_name'    => 'PTAX Deposit (' . count($ids) . ' records)',
                    'payroll_month' => ($request->ptax_period_from ?? '') . ($request->ptax_period_to ? ' to ' . $request->ptax_period_to : ''),
                    'added_by'      => $ownerId,
                    'net_salary'    => 0,
                    'pf'            => 0,
                    'esi'           => 0,
                    'tds'           => 0,
                    'lwf'           => 0,
                    'ptax'          => $ptaxAmount,
                    'loan'          => 0,
                ]
            );
        }

        return response()->json(['status' => true, 'message' => 'Selected PTax records updated successfully.']);
    }

    //------- LWF Full List -------//
    public function getLwfFullList(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        $query = DB::table('user_payslip')
            ->leftJoin('employees', 'employees.empId', '=', 'user_payslip.user_emp_id')
            ->leftJoin('users', 'users.id', '=', 'user_payslip.user_emp_id')
            ->where('user_payslip.added_by', $ownerId)
            ->where('user_payslip.financial_year', $financialYear);

        // Period Filter
        if ($filterType == 'monthly' && !empty($period)) {

            $month = Carbon::parse('1 ' . $period)->month;
            $query->where('user_payslip.month', $month);

        } elseif ($filterType == 'quarterly' && !empty($period)) {

            switch ($period) {
                case 'Q1':
                    $months = [4, 5, 6];
                    break;
                case 'Q2':
                    $months = [7, 8, 9];
                    break;
                case 'Q3':
                    $months = [10, 11, 12];
                    break;
                case 'Q4':
                    $months = [1, 2, 3];
                    break;
                default:
                    $months = [];
            }

            if (!empty($months)) {
                $query->whereIn('user_payslip.month', $months);
            }

        } elseif ($filterType == 'half-yearly' && !empty($period)) {

            switch ($period) {
                case 'H1':
                    $months = [4, 5, 6, 7, 8, 9];
                    break;
                case 'H2':
                    $months = [10, 11, 12, 1, 2, 3];
                    break;
                default:
                    $months = [];
            }

            if (!empty($months)) {
                $query->whereIn('user_payslip.month', $months);
            }
        }

        // Only LWF Applicable Employees
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.lwf_applicable'
                    )
                ) AS UNSIGNED
            ) = 1
        ");

        $records = $query->select(
                'user_payslip.id',
                'employees.employee_id',
                DB::raw("COALESCE(users.name) as name"),
                'user_payslip.financial_year',

                // Employee Contribution
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.lwf_deduct'
                            )
                        ) AS DECIMAL(12,2)
                    ) AS employee_contribution
                "),

                // Employer Contribution
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.lwf_company_contribution'
                            )
                        ) AS DECIMAL(12,2)
                    ) AS company_contribution
                "),

                'user_payslip.lwf_receipt_date',
                'user_payslip.lwf_receipt_no',
                'user_payslip.lwf_payment_status',
                
                'user_payslip.lwf_payment_month',
                'user_payslip.lwf_total_payment',
                'user_payslip.lwf_interest_amount',
                'user_payslip.lwf_employee_count',
                'user_payslip.lwf_employee_contribution',
                'user_payslip.lwf_employer_contribution',
            )
            ->orderBy('employees.employee_id')
            ->get();

        return response()->json($records);
    }

    public function updateLwf(Request $request)
    {
        $ownerId = currentOwnerId();

        $ids = (array) $request->input('ids', []);

        DB::table('user_payslip')
            ->whereIn('id', $ids)
            ->update([
                'lwf_grips_payment_id'        => $request->lwf_grips_payment_id,
                'lwf_receipt_date'            => $request->lwf_receipt_date,
                'lwf_receipt_no'              => $request->lwf_receipt_no,
                'lwf_organization_account_no' => $request->lwf_organization_account_no,
                'lwf_payment_month'           => $request->lwf_payment_month,
                'lwf_employee_count'          => $request->lwf_employee_count,
                'lwf_employee_contribution'   => $request->lwf_employee_contribution,
                'lwf_employer_contribution'   => $request->lwf_employer_contribution,
                'lwf_total_payment'           => $request->lwf_total_payment,
                'lwf_interest_amount'         => $request->lwf_interest_amount,
                'lwf_payment_status'          => 'Done',
                'updated_at'                  => now(),
            ]);

        // Single Receipt Voucher for LWF deposited amount
        $lwfAmount = (float) $request->input('lwf_total_payment', 0);
        if ($lwfAmount > 0 && !empty($ids)) {
            $firstPayslip = DB::table('user_payslip as up')
                ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
                ->where('up.id', $ids[0])
                ->where('up.added_by', $ownerId)
                ->select('up.id', 'up.payslip_no', 'up.date', 'e.propId')
                ->first();

            $this->paymentVoucherService->storePaymentVoucherEntries(
                $firstPayslip->id ?? $ids[0],
                'Payroll',
                $lwfAmount,
                [
                    'propId'        => $firstPayslip->propId ?? null,
                    'date'          => $request->lwf_receipt_date ?: ($firstPayslip->date ?? now()->toDateString()),
                    'reference_no'  => $request->lwf_receipt_no ?: ($firstPayslip->payslip_no ?? null),
                    'party_name'    => 'LWF Deposit (' . count($ids) . ' records)',
                    'payroll_month' => $request->lwf_payment_month ?? '',
                    'added_by'      => $ownerId,
                    'net_salary'    => 0,
                    'pf'            => 0,
                    'esi'           => 0,
                    'tds'           => 0,
                    'lwf'           => $lwfAmount,
                    'ptax'          => 0,
                    'loan'          => 0,
                ]
            );
        }

        return response()->json(['status' => true, 'message' => 'Selected LWF records updated successfully.']);
    }

    // ------- Salary Sheet (Bank Transfer Sheet) -------//
    public function getSalarySheetData(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type; // monthly | quarterly | half-yearly | yearly
        $period        = $request->period;       // month name | Q1-Q4 | H1-H2 | null

        $months = $this->resolveMonths($filterType, $period);

        $query = DB::table('user_payslip as up')
            ->leftJoin('users as u', 'u.id', '=', 'up.user_emp_id')
            ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
            ->where('up.added_by', $ownerId)
            ->where('up.financial_year', $financialYear);

        if (!empty($months)) {
            $query->whereIn('up.month', $months);
        }

        $records = $query->select(
                'up.id',
                'up.user_emp_id',
                'up.month',
                'up.financial_year',
                'up.date',
                'up.payment_date',
                'up.payment_trans_id',
                'up.payment_status',
                'e.employee_id',
                'u.name',
                'e.bank_name',
                'e.account_number',
                'e.ifsc',
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.net_salary')
                        ) AS DECIMAL(15,2)
                    ) as net_salary
                ")
            )
            ->orderBy('up.month')
            ->orderBy('u.name')
            ->get();

        // Month number → name map
        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        foreach ($records as $row) {
            $row->month_name = $monthNames[$row->month] ?? $row->month;
        }

        return response()->json($records);
    }

    // ------- LWF List -------//
    // public function getLwfList(Request $request)
    // {
    //     $ownerId       = currentOwnerId();
    //     $financialYear = $request->financial_year;
    //     $filterType    = $request->filter_type;
    //     $period        = $request->period;

    //     $months = $this->resolveMonths($filterType, $period);

    //     $query = DB::table('user_payslip as up')
    //         ->leftJoin('users as u', 'u.id', '=', 'up.user_emp_id')
    //         ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
    //         ->leftJoin('states as s', 's.id', '=', 'e.c_emp_state')
    //         ->where('up.added_by', $ownerId)
    //         ->where('up.financial_year', $financialYear)
    //         ->where('e.lwf_applicable', 1);

    //     if (!empty($months)) {
    //         $query->whereIn('up.month', $months);
    //     }

    //     $records = $query->select(
    //             'up.id',
    //             'up.user_emp_id',
    //             'up.month',
    //             'e.employee_id',
    //             'u.name',
    //             's.name as state_name',
    //             DB::raw("
    //                 CAST(
    //                     JSON_UNQUOTE(
    //                         JSON_EXTRACT(up.emp_salary_slip_response,
    //                             '$.visible_data.final_salary_calculation.total_earnings')
    //                     ) AS DECIMAL(15,2)
    //                 ) as gross_wages
    //             "),
    //             DB::raw("
    //                 CAST(
    //                     JSON_UNQUOTE(
    //                         JSON_EXTRACT(up.emp_salary_slip_response,
    //                             '$.visible_data.final_salary_calculation.lwf_deduct')
    //                     ) AS DECIMAL(15,2)
    //                 ) as lwf_employee
    //             "),
    //             DB::raw("
    //                 CAST(
    //                     JSON_UNQUOTE(
    //                         JSON_EXTRACT(up.emp_salary_slip_response,
    //                             '$.visible_data.final_salary_calculation.lwf_company_contribution')
    //                     ) AS DECIMAL(15,2)
    //                 ) as lwf_employer
    //             ")
    //         )
    //         ->orderBy('u.name')
    //         ->get();

    //     foreach ($records as $row) {
    //         $row->lwf_total = ($row->lwf_employee ?? 0) + ($row->lwf_employer ?? 0);
    //         $row->status    = 'Filed';
    //     }

    //     return response()->json($records);
    // }

    public function getLwfList(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;
        $filterType    = $request->filter_type;
        $period        = $request->period;

        $months = $this->resolveMonths($filterType, $period);
        

        $query = DB::table('user_payslip as up')
            ->leftJoin('users as u', 'u.id', '=', 'up.user_emp_id')
            ->leftJoin('employees as e', 'e.empId', '=', 'up.user_emp_id')
            ->leftJoin('states as s', 's.id', '=', 'e.c_emp_state')
            ->where('up.added_by', $ownerId)
            ->where('up.financial_year', $financialYear);

        if (!empty($months)) {
            $query->whereIn('up.month', $months);
        }

        // Show only payslips where LWF is applicable
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        up.emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.lwf_applicable'
                    )
                ) AS UNSIGNED
            ) = 1
        ");

        $records = $query->select(
                'up.id',
                'up.user_emp_id',
                'up.month',
                'up.lwf_payment_status',
                'e.employee_id',
                'u.name',
                's.name as state_name',

                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.total_earnings'
                            )
                        ) AS DECIMAL(15,2)
                    ) AS gross_wages
                "),

                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.lwf_deduct'
                            )
                        ) AS DECIMAL(15,2)
                    ) AS lwf_employee
                "),

                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.lwf_company_contribution'
                            )
                        ) AS DECIMAL(15,2)
                    ) AS lwf_employer
                ")
            )
            ->orderBy('u.name')
            ->get();

        foreach ($records as $row) {

            $row->lwf_total = ($row->lwf_employee ?? 0) + ($row->lwf_employer ?? 0);

            // Status from database
            $row->status = $row->lwf_payment_status ?? 'Pending';
        }

        return response()->json($records);
    }

    // ------- Gratuity List -------//
    public function getGratuityList(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;

        $employees = DB::table('employees as e')
            ->leftJoin('users as u', 'u.id', '=', 'e.empId')
            ->where('e.added_by', $ownerId)
            ->select(
                'e.empId',
                'e.employee_id',
                'u.name',
                'e.joining_date',
                'e.basic_sal'
            )
            ->get();

        $data = [];

        foreach ($employees as $emp) {
            if (empty($emp->joining_date)) continue;

            $joiningDate   = Carbon::parse($emp->joining_date);
            $today         = Carbon::now();
            $yearsCompleted = $joiningDate->diffInYears($today);

            // Gratuity formula: (Basic / 26) * 15 * years
            $basicSalary = (float)($emp->basic_sal ?? 0);
            $currentFYGratuity  = $yearsCompleted > 0 ? round(($basicSalary / 26) * 15, 2) : 0;
            $totalGratuity      = $yearsCompleted >= 5 ? round(($basicSalary / 26) * 15 * $yearsCompleted, 2) : 0;

            $status = $yearsCompleted >= 5 ? 'Provisioned' : 'Not Eligible';

            $data[] = [
                'employee_id'         => $emp->employee_id,
                'employee_name'       => $emp->name ?? '',
                'joining_date'        => $joiningDate->format('d-m-Y'),
                'years_completed'     => $yearsCompleted . ' Year' . ($yearsCompleted !== 1 ? 's' : ''),
                'basic_salary'        => $basicSalary,
                'current_fy_gratuity' => $currentFYGratuity,
                'total_gratuity'      => $totalGratuity,
                'status'              => $status,
            ];
        }

        return response()->json($data);
    }

    // ------- Helper: resolve month numbers from filter type -------//
    private function resolveMonths(string $filterType, ?string $period): array
    {
        if ($filterType === 'monthly' && !empty($period)) {
            return [Carbon::parse('1 ' . $period)->month];
        }

        if ($filterType === 'quarterly' && !empty($period)) {
            return match($period) {
                'Q1'    => [4, 5, 6],
                'Q2'    => [7, 8, 9],
                'Q3'    => [10, 11, 12],
                'Q4'    => [1, 2, 3],
                default => [],
            };
        }

        if ($filterType === 'half-yearly' && !empty($period)) {
            return match($period) {
                'H1'    => [4, 5, 6, 7, 8, 9],
                'H2'    => [10, 11, 12, 1, 2, 3],
                default => [],
            };
        }

        // yearly — no month filter
        return [];
    }

    //-------- Multiple Payslip Generate ---------
    public function multiplePayslipGenerate(){
        return view('User.multiple-generate-payslip');
    }

    private function computeBulkPayslipValuesForEmployee($employee, int $year, int $monthNum, int $ownerId, float $bonus = 0, float $overtime = 0): array
    {
        $grossSalary = (float)($employee->total_addition ?? 0);
        $perDaySalary = round($grossSalary / 30, 2);

        $firstDay = Carbon::create($year, $monthNum, 1)->startOfMonth();
        $lastDay  = $firstDay->copy()->endOfMonth();

        $weeklySchedule = DB::table('weekly_schedules')
            ->where('added_by', $ownerId)
            ->pluck('status', 'day')
            ->mapWithKeys(fn($v, $k) => [strtolower(trim($k)) => $v])
            ->toArray();

        $holidays = DB::table('holidays')
            ->where('added_by', $ownerId)
            ->whereBetween('holidayDate', [$firstDay, $lastDay])
            ->pluck('holidayDate')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $totalPresent = 0;
        $totalPresentOnTime = 0;
        $totalPresentLate = 0;
        $totalEarlyLogout = 0;
        $totalLate = 0;
        $totalOvertimeSeconds = 0;

        $attendanceRecords = DB::table('attendance')
            ->where('userId', $employee->empId)
            ->whereYear('present_date', $year)
            ->whereMonth('present_date', $monthNum)
            ->get();

        foreach ($attendanceRecords as $record) {
            $dayName = strtolower(Carbon::parse($record->present_date)->format('l'));
            $dateStr = Carbon::parse($record->present_date)->format('Y-m-d');
            $isWeekend = isset($weeklySchedule[$dayName]) && strtolower($weeklySchedule[$dayName]) === 'closed';
            $isHoliday = in_array($dateStr, $holidays, true);

            if ($isWeekend || $isHoliday) {
                continue;
            }

            $totalPresent++;
            $schedule = DB::table('weekly_schedules')
                ->where('added_by', $ownerId)
                ->where('day', $dayName)
                ->first();

            if ($schedule && strtolower($schedule->status) === 'open') {
                if (!empty($schedule->opening_time) && !empty($record->in_time)) {
                    $scheduledStart = Carbon::parse($schedule->opening_time);
                    $actualLogin = Carbon::parse($record->in_time);
                    $graceTime = $scheduledStart->copy()->addMinutes(5);
                    if ($actualLogin->lte($graceTime)) {
                        $totalPresentOnTime++;
                    } else {
                        $totalPresentLate++;
                        $totalLate++;
                    }
                }

                if (!empty($schedule->closing_time) && !empty($record->out_time)) {
                    $scheduledEnd = Carbon::parse($schedule->closing_time);
                    $actualLogout = Carbon::parse($record->out_time);
                    if ($actualLogout->lt($scheduledEnd)) {
                        $totalEarlyLogout++;
                    }

                    if ($actualLogout->gt($scheduledEnd)) {
                        $totalOvertimeSeconds += $actualLogout->diffInSeconds($scheduledEnd);
                    }
                }
            }
        }

        $leaves = DB::table('leaves')
            ->where('emp_id', $employee->empId)
            ->where('status', 'approved')
            ->where(function ($q) use ($monthNum, $year) {
                $q->whereMonth('start_date', $monthNum)->whereYear('start_date', $year)
                    ->orWhereMonth('end_date', $monthNum)->whereYear('end_date', $year);
            })
            ->get();

        $totalLeave = (float)$leaves->sum('total_days');

        $totalWorkingDays = 0;
        for ($d = $firstDay->copy(); $d->lte($lastDay); $d->addDay()) {
            $dayName = strtolower($d->format('l'));
            $dateStr = $d->format('Y-m-d');
            $isWeekend = isset($weeklySchedule[$dayName]) && strtolower($weeklySchedule[$dayName]) === 'closed';
            if (!$isWeekend && !in_array($dateStr, $holidays, true)) {
                $totalWorkingDays++;
            }
        }
        $totalWorkingDays = max($totalWorkingDays - 1, 0);

        $lateDeduct = intdiv($totalLate, 3);
        $totalEarlyLogoutDeduct = intdiv($totalEarlyLogout, 3);
        $totalAbsent = max($totalWorkingDays - ($totalPresent + $totalLeave), 0);
        $lopDeduction = $perDaySalary * ($totalAbsent + $lateDeduct + $totalEarlyLogoutDeduct);

        $baseGross = max($grossSalary - $lopDeduction, 0);
        $basicPercentage = isset($employee->basic_percentage)
            ? (float) $employee->basic_percentage
            : 50;
        $basicSalary = round($baseGross * ($basicPercentage / 100), 2);
        $hra = round($basicSalary * 0.50, 2);
        $conveyance = 1600;
        $medicalAllowance = 1250;
        $specialAllowance = max($baseGross - ($basicSalary + $hra + $medicalAllowance + $conveyance), 0);

        $pf = $employee->epf_applicable ? min(round(($basicSalary * 0.12), 2), 1800) : 0;
        $esi = 0;
        if ($employee->esic_applicable && (float)($employee->esi ?? 0) > 0) {
            $esiBase = $baseGross + $overtime;
            $esi = $esiBase <= 21000 ? round($esiBase * 0.0075, 2) : 0;
        }

        $pt = 0;
        if ($employee->ptax_applicable && (float)($employee->ptax ?? 0) > 0) {
            $ptBase = $baseGross + $bonus + $overtime;
            if ($ptBase > 10000 && $ptBase <= 15000) {
                $pt = 110;
            } elseif ($ptBase > 15000 && $ptBase <= 25000) {
                $pt = 130;
            } elseif ($ptBase > 25000 && $ptBase <= 40000) {
                $pt = 150;
            } elseif ($ptBase > 40000) {
                $pt = 200;
            }
        }

        $tds = $employee->tds_applicable ? (float)($employee->tds ?? 0) : 0;
        $loan = (float)($employee->loan_deduction ?? 0);
        $lwf = $employee->lwf_applicable ? (float)($employee->lwf_deduct ?? 0) : 0;

        $totalEarnings = $basicSalary + $hra + $conveyance + $medicalAllowance + $specialAllowance + $bonus + $overtime;
        $totalDeductions = $pf + $esi + $pt + $tds + $loan + $lwf;
        $netSalary = $totalEarnings - $totalDeductions;

        return [
            'gross_salary' => round($baseGross, 2),
            'basic_salary' => round($basicSalary, 2),
            'hra' => round($hra, 2),
            'conveyance' => round($conveyance, 2),
            'medical_allowance' => round($medicalAllowance, 2),
            'special_allowance' => round($specialAllowance, 2),
            'pf' => round($pf, 2),
            'esi' => round($esi, 2),
            'ptax' => round($pt, 2),
            'tds' => round($tds, 2),
            'loan' => round($loan, 2),
            'lwf' => round($lwf, 2),
            'lop' => round($lopDeduction, 2),
            'per_day_salary' => round($perDaySalary, 2),
            'total_absent' => (int) $totalAbsent,
            'late_deduction_days' => (int) $lateDeduct,
            'total_early_logout_deduction_days' => (int) $totalEarlyLogoutDeduct,
            'total_working_days' => (int) $totalWorkingDays,
            'total_earnings' => round($totalEarnings, 2),
            'total_deductions' => round($totalDeductions, 2),
            'net_salary' => round($netSalary, 2),
        ];
    }

    // ------- Bulk Payslip: get employees without payslip for a given month/FY -------//
    public function getBulkPayslipEmployees(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;
        $month         = Carbon::parse('1 ' . $request->month)->month;

        [$fyStart, $fyEnd] = explode('-', $financialYear);
        $year = ($month >= 4) ? $fyStart : $fyEnd;

        // IDs that already have a payslip
        $existingEmpIds = DB::table('user_payslip')
            ->where('added_by', $ownerId)
            ->where('financial_year', $financialYear)
            ->where('month', $month)
            ->pluck('user_emp_id')
            ->toArray();

        $firstDayOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $lastDayOfMonth  = $firstDayOfMonth->copy()->endOfMonth();

        // Show employee for a month only if:
        // 1. They joined on or before the last day of the selected month
        // 2. AND they were active (Confirmed/In Probation)
        //    OR resigned/terminated but their last day (regine_date) is within or after the first day of that month
        //    meaning: if Khokan resigned 15-June, show in June (regine_date >= June-01),
        //             hide in July (regine_date < July-01),
        //             and hide in May-2025 because joining_date 2026-01-20 > May-2025 last day
        $query = DB::table('employees as e')
            ->leftJoin('users as u', 'u.id', '=', 'e.empId')
            ->leftJoin('designations as d', 'd.id', '=', 'e.desig_id')
            ->where('e.added_by', $ownerId)
            // Must have joined on or before the last day of the selected month
            ->where(function ($q) use ($lastDayOfMonth) {
                $q->whereNull('e.joining_date')
                  ->orWhereDate('e.joining_date', '<=', $lastDayOfMonth);
            })
            // Must be active OR resigned/terminated with last day >= first day of month
            ->where(function ($q) use ($firstDayOfMonth) {
                $q->whereIn('e.emp_status', ['Confirmed', 'In Probation'])
                  ->orWhere(function ($qq) use ($firstDayOfMonth) {
                      $qq->whereIn('e.emp_status', ['Resigned', 'Terminated'])
                         ->whereNotNull('e.regine_date')
                         ->whereDate('e.regine_date', '>=', $firstDayOfMonth);
                  });
            });

        // Only exclude existing payslip employees if there are any
        if (!empty($existingEmpIds)) {
            $query->whereNotIn('e.empId', $existingEmpIds);
        }

        $employees = $query
            ->select(
                'e.empId',
                'e.employee_id',
                'u.name',
                'e.emp_status',
                'e.regine_date',
                'e.basic_sal',
                'e.basic_percentage',
                'e.hra',
                'e.convayance',
                'e.medical_allowance',
                'e.special_bonus',
                'e.total_addition',
                'e.net_sal',
                'e.provident_fund',
                'e.esi',
                'e.ptax',
                'e.tds',
                'e.loan_deduction',
                'e.lwf_applicable',
                'e.lwf_deduct',
                'e.epf_applicable',
                'e.esic_applicable',
                'e.ptax_applicable',
                'e.tds_applicable',
                'd.designation_name'
            )
            ->orderBy('u.name')
            ->get();

        // Working days for the month
        $firstDay = Carbon::create($year, $month, 1)->startOfMonth();
        $lastDay  = $firstDay->copy()->endOfMonth();

        $weeklySchedule = DB::table('weekly_schedules')
            ->where('added_by', $ownerId)
            ->pluck('status', 'day')
            ->mapWithKeys(fn($v, $k) => [strtolower(trim($k)) => $v])
            ->toArray();

        $holidayDates = DB::table('holidays')
            ->where('added_by', $ownerId)
            ->whereBetween('holidayDate', [$firstDay, $lastDay])
            ->pluck('holidayDate')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $totalWorkingDays = 0;
        for ($d = $firstDay->copy(); $d->lte($lastDay); $d->addDay()) {
            $dayName = strtolower($d->format('l'));
            $dateStr = $d->format('Y-m-d');
            $isWeekend = isset($weeklySchedule[$dayName]) && strtolower($weeklySchedule[$dayName]) === 'closed';
            if (!$isWeekend && !in_array($dateStr, $holidayDates)) {
                $totalWorkingDays++;
            }
        }
        $totalWorkingDays = max($totalWorkingDays - 1, 0);

        foreach ($employees as $emp) {
            $calc = $this->computeBulkPayslipValuesForEmployee($emp, $year, $month, $ownerId, 0, 0);

            $emp->gross_salary       = $calc['gross_salary'];
            $emp->basic_salary       = $calc['basic_salary'];
            $emp->pf                 = $calc['pf'];
            $emp->esi_amount         = $calc['esi'];
            $emp->ptax_amount        = $calc['ptax'];
            $emp->tds_amount         = $calc['tds'];
            $emp->loan_ded           = $calc['loan'];
            $emp->lwf_amount         = $calc['lwf'];
            $emp->net_salary         = $calc['net_salary'];
            $emp->total_working_days = $calc['total_working_days'];
            $emp->per_day_salary     = $calc['per_day_salary'];
            $emp->total_absent       = $calc['total_absent'];
            $emp->late_deduction_days = $calc['late_deduction_days'];
            $emp->total_early_logout_deduction_days = $calc['total_early_logout_deduction_days'];
            $emp->performance_bonus  = 0;
            $emp->overtime           = 0;
        }

        return response()->json([
            'employees'         => $employees,
            'total_working_days'=> $totalWorkingDays,
            'month'             => $month,
            'financial_year'    => $financialYear,
        ]);
    }

    // ------- Bulk Payslip: generate & save all -------//
    public function bulkGeneratePayslip(Request $request)
    {
        $ownerId       = currentOwnerId();
        $financialYear = $request->financial_year;
        $monthNum      = (int)$request->month;
        $employeesData = $request->employees; // array from JS

        [$fyStart, $fyEnd] = explode('-', $financialYear);
        $year = ($monthNum >= 4) ? $fyStart : $fyEnd;

        $monthNames = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                       7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

        $firstDay = Carbon::create($year, $monthNum, 1)->startOfMonth();

        // Working days calculation (same as getBulkPayslipEmployees)
        $weeklySchedule = DB::table('weekly_schedules')
            ->where('added_by', $ownerId)
            ->pluck('status', 'day')
            ->mapWithKeys(fn($v, $k) => [strtolower(trim($k)) => $v])
            ->toArray();

        $holidayDates = DB::table('holidays')
            ->where('added_by', $ownerId)
            ->whereBetween('holidayDate', [$firstDay, $firstDay->copy()->endOfMonth()])
            ->pluck('holidayDate')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $totalWorkingDays = 0;
        $totalHolidays   = count($holidayDates);
        $totalWeekends   = 0;
        for ($d = $firstDay->copy(); $d->lte($firstDay->copy()->endOfMonth()); $d->addDay()) {
            $dayName = strtolower($d->format('l'));
            $isWeekend = isset($weeklySchedule[$dayName]) && strtolower($weeklySchedule[$dayName]) === 'closed';
            if ($isWeekend) { $totalWeekends++; continue; }
            if (!in_array($d->format('Y-m-d'), $holidayDates)) $totalWorkingDays++;
        }
        $totalWorkingDays = max($totalWorkingDays - 1, 0);

        $generated = 0;
        $skipped   = 0;
        $errors    = [];

        // Accumulators for the consolidated journal entry
        $totalGross = 0;
        $totalNet   = 0;
        $totalPf    = 0;
        $totalEsi   = 0;
        $totalPt    = 0;
        $totalTds   = 0;
        $totalLoan  = 0;
        $totalLwf   = 0;

        foreach ($employeesData as $row) {
            $empId = $row['emp_id'];

            // Skip if payslip already exists
            $exists = DB::table('user_payslip')
                ->where('user_emp_id', $empId)
                ->where('financial_year', $financialYear)
                ->where('month', $monthNum)
                ->exists();

            if ($exists) { $skipped++; continue; }

            try {
                $employee = DB::table('employees as e')
                    ->leftJoin('users as u', 'u.id', '=', 'e.empId')
                    ->where('e.empId', $empId)
                    ->select(
                        'e.empId',
                        'e.employee_id',
                        'u.name as employee_name',
                        'e.total_addition',
                        'e.basic_sal',
                        'e.basic_percentage',
                        'e.hra',
                        'e.convayance',
                        'e.medical_allowance',
                        'e.special_bonus',
                        'e.provident_fund',
                        'e.esi',
                        'e.ptax',
                        'e.tds',
                        'e.loan_deduction',
                        'e.lwf_applicable',
                        'e.lwf_deduct',
                        'e.epf_applicable',
                        'e.esic_applicable',
                        'e.ptax_applicable',
                        'e.tds_applicable'
                    )
                    ->first();

                if (!$employee) {
                    throw new \Exception('Employee not found for bulk payslip generation.');
                }

                $perfBonus  = (float)($row['performance_bonus'] ?? 0);
                $overtime   = (float)($row['overtime'] ?? 0);
                $calc = $this->computeBulkPayslipValuesForEmployee($employee, $year, $monthNum, $ownerId, $perfBonus, $overtime);

                $gross      = $calc['gross_salary'];
                $basic      = $calc['basic_salary'];
                $pf         = $calc['pf'];
                $esi        = $calc['esi'];
                $pt         = $calc['ptax'];
                $tds        = $calc['tds'];
                $loan       = $calc['loan'];
                $lwf        = $calc['lwf'];
                $lop        = $calc['lop'];
                $hra        = $calc['hra'];
                $conveyance = $calc['conveyance'];
                $medAllowance = $calc['medical_allowance'];
                $specialAllowance = $calc['special_allowance'];
                $totalEarnings = $calc['total_earnings'];
                $totalDeductions = $calc['total_deductions'];
                $net        = $calc['net_salary'];

                // Payslip number — matches the format used in checkPayslip
                $payslipNo = 'PS/' . ($row['employee_id'] ?? $empId) . '/' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . $year;

                // -------------------------------------------------------
                // Build the SAME structure as generate-payslip.blade.php
                // savePayslip stores: { visible_data: finalSalaryJson, raw_api_response: empResponse, ... }
                // finalSalaryJson = fullPayslipJson from JS which has all sections at top level
                // -------------------------------------------------------
                $finalSalaryJson = [
                    // Meta
                    'payslip_no'     => $payslipNo,
                    'financial_year' => $financialYear,
                    'month'          => $monthNum,
                    'generate_date'  => now()->toDateString(),
                    'notes'          => '',
                    'created_at'     => now()->toISOString(),

                    // Employee details (matches generate-payslip employee object)
                    'employee_details' => [
                        'name'               => $row['name']        ?? '',
                        'employee_id'        => $row['employee_id'] ?? '',
                        'empId'              => $empId,
                        'dept_name'          => '',
                        'designation_name'   => $row['designation_name'] ?? '',
                        'joining_date'       => '',
                        'epf_no'             => '',
                        'bank_name'          => '',
                        'bank_branch'        => '',
                        'ifsc'               => '',
                        'account_holder_name'=> '',
                        'account_number'     => '',
                        'pan_number'         => '',
                        'aadhaar_number'     => '',
                    ],

                    // Month details
                    'month_details' => [
                        'total_days'         => $firstDay->daysInMonth,
                        'total_working_days' => $totalWorkingDays,
                        'total_holidays'     => $totalHolidays,
                        'total_weekends'     => $totalWeekends,
                        'month_name'         => $monthNames[$monthNum] ?? '',
                        'financial_year'     => $financialYear,
                    ],

                    // Attendance details (no per-employee attendance in bulk)
                    'attendance_details' => [
                        'total_present'             => 0,
                        'total_present_on_time'     => 0,
                        'total_present_late'        => 0,
                        'total_early_logout'        => 0,
                        'total_absent'              => 0,
                        'total_leave_approved'      => 0,
                        'total_holiday'             => $totalHolidays,
                        'total_office_weekend'      => $totalWeekends,
                        'total_overtime_hours'      => '00:00:00',
                        'totalEarlyLogoutDeductionDays' => 0,
                    ],

                    // Salary details (matches salaryDetails from checkPayslip response)
                    'salary_details' => [
                        'gross_salary'       => $gross,
                        'base_salary'        => $basic,
                        'hra'                => $hra,
                        'conveyance'         => $conveyance,
                        'medical_allowance'  => $medAllowance,
                        'special_bonus'      => $specialAllowance,
                        'total_addition'     => $totalEarnings,
                        'provident_fund'     => $pf,
                        'esi'                => $esi,
                        'ptax'               => $pt,
                        'tds'                => $tds,
                        'loan'               => $loan,
                        'advance_amount'     => 0,
                        'per_day_salary'     => round($calc['per_day_salary'], 2),
                        'lateDeductionDays'  => $calc['late_deduction_days'],
                        'lwf_applicable'     => $lwf > 0 ? 1 : 0,
                        'lwf_deduct'         => $lwf,
                        'lwf_company_contribution' => 0,
                    ],

                    // Final salary calculation — the key section read by all report queries
                    'final_salary_calculation' => [
                        'basic_salary'           => $basic,
                        'gross_salary'           => $gross,
                        'hra'                    => $hra,
                        'conveyance'             => $conveyance,
                        'medical_allowance'      => $medAllowance,
                        'special_allowance'      => $specialAllowance,
                        'performance_bonus'      => $perfBonus,
                        'overtime_payment'       => $overtime,
                        'total_earnings'         => $totalEarnings,
                        'provident_fund'         => $pf,
                        'esi'                    => $esi,
                        'ptax'                   => $pt,
                        'tds'                    => $tds,
                        'loan'                   => $loan,
                        'lop'                    => $lop,
                        'lwf_applicable'         => $lwf > 0 ? 1 : 0,
                        'lwf_deduct'             => $lwf,
                        'lwf_company_contribution' => 0,
                        'total_deductions'       => $totalDeductions,
                        'net_salary'             => $net,
                        'in_words'               => '',
                        'generated_at'           => now()->toISOString(),
                    ],
                ];

                // Outer wrapper — matches what savePayslip stores
                $payslipData = [
                    'payslip_no'       => $payslipNo,
                    'employee_id'      => $empId,
                    'financial_year'   => $financialYear,
                    'month'            => $monthNum,
                    'generate_date'    => now()->toDateString(),
                    'notes'            => '',
                    'visible_data'     => $finalSalaryJson,  // same key as savePayslip
                    'raw_api_response' => [],
                    'created_by'       => auth()->id(),
                    'created_at'       => now()->toISOString(),
                ];

                $jsonToStore = json_encode($payslipData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

                DB::table('user_payslip')->insert([
                    'payslip_no'               => $payslipNo,
                    'user_emp_id'              => $empId,
                    'financial_year'           => $financialYear,
                    'month'                    => $monthNum,
                    'payslip_text'             => '',
                    'date'                     => now()->toDateString(),
                    'emp_salary_slip_response' => $jsonToStore,
                    'added_by'                 => $ownerId,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);

                // Accumulate totals for the consolidated journal entry
                $totalGross += $totalEarnings;
                $totalNet   += $net;
                $totalPf    += $pf;
                $totalEsi   += $esi;
                $totalPt    += $pt;
                $totalTds   += $tds;
                $totalLoan  += $loan;
                $totalLwf   += $lwf;

                $generated++;

            } catch (\Exception $e) {
                $errors[] = ['emp_id' => $empId, 'error' => $e->getMessage()];
            }
        }

        // -------------------------------------------------------
        // ONE consolidated journal entry for the entire bulk run
        // autoId = max(journals.autoId) + 1  — a fresh unique ID
        // shared across all lines of this single entry so they
        // can be grouped / deleted together later.
        // -------------------------------------------------------
        if ($generated > 0) {
            try {
                $ownerPropId = DB::table('employees')
                    ->where('added_by', $ownerId)
                    ->whereNotNull('propId')
                    ->value('propId');

                $monthLabel = ($monthNames[$monthNum] ?? '') . ' ' . $financialYear;
                $bulkRef    = 'BLK/' . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '/' . $financialYear;

                // Fresh autoId: max in journals + 1 (scoped to owner to avoid cross-user collision)
                $nextAutoId = (int) DB::table('journals')
                    ->where('added_by', $ownerId)
                    ->max('autoId') + 1;

                // Journal number: max for this user + 1
                $lastJournalNo = (int) DB::table('journals')
                    ->where('added_by', $ownerId)
                    ->max('journal_no');
                $journalNo = str_pad($lastJournalNo + 1, 5, '0', STR_PAD_LEFT);

                $now = now();

                $common = [
                    'autoId'           => $nextAutoId,
                    'added_by'         => $ownerId,
                    'propId'           => $ownerPropId,
                    'journal_no'       => $journalNo,
                    'journal_date'     => $now->toDateString(),
                    'reference_type'   => 'New Ref',
                    'reference_no'     => $bulkRef,
                    'entry_type'       => 'Payroll',
                    'source'           => 'Payroll',
                    'settlement_type'  => null,
                    'against_ledger'   => null,
                    'narration'        => null,
                    'party_name'       => 'Bulk Payroll (' . $generated . ' employees)',
                    'payment_status'   => 'Full',
                    'status'           => 'Posted',
                    'rev_amend_status' => null,
                    'tds_applicable'   => 'no',
                    'tds_percent'      => 0,
                    'tds_amt'          => 0,
                    'tds_id'           => null,
                    'gst_applicable'   => 'no',
                    'gst_rate'         => 0,
                    'gst_trans'        => null,
                    'other_note'       => null,
                    'hsn_sac_code'     => null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];

                $entries = [];

                // Salary Expense — Debit (gross = total_earnings across all employees)
                $entries[] = array_merge($common, [
                    'ledger'       => 'Salary Expense',
                    'debit_credit' => 'Debit',
                    'amount'       => round($totalGross, 2),
                    'tot_amt'      => round($totalGross, 2),
                    'notes'        => 'Salary Expense - ' . $monthLabel,
                ]);

                // PF Payable — Credit
                if ($totalPf > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'PF Payable',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalPf, 2),
                        'tot_amt'      => round($totalPf, 2),
                        'notes'        => 'PF Deduction - ' . $monthLabel,
                    ]);
                }

                // ESI Payable — Credit
                if ($totalEsi > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'ESI Payable',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalEsi, 2),
                        'tot_amt'      => round($totalEsi, 2),
                        'notes'        => 'ESI Deduction - ' . $monthLabel,
                    ]);
                }

                // PTAX Payable — Credit
                if ($totalPt > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'PTAX Payable',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalPt, 2),
                        'tot_amt'      => round($totalPt, 2),
                        'notes'        => 'Professional Tax Deduction - ' . $monthLabel,
                    ]);
                }

                // TDS Payable — Credit
                if ($totalTds > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'TDS Payable',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalTds, 2),
                        'tot_amt'      => round($totalTds, 2),
                        'notes'        => 'TDS Deduction - ' . $monthLabel,
                    ]);
                }

                // LWF Payable — Credit
                if ($totalLwf > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'LWF Payable',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalLwf, 2),
                        'tot_amt'      => round($totalLwf, 2),
                        'notes'        => 'Labour Welfare Fund Deduction - ' . $monthLabel,
                    ]);
                }

                // Loan Recovery — Credit
                if ($totalLoan > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'Employee Loan Recovery',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalLoan, 2),
                        'tot_amt'      => round($totalLoan, 2),
                        'notes'        => 'Loan Recovery - ' . $monthLabel,
                    ]);
                }

                // Net Salary Payable — Credit
                if ($totalNet > 0) {
                    $entries[] = array_merge($common, [
                        'ledger'       => 'Salary Payable',
                        'debit_credit' => 'Credit',
                        'amount'       => round($totalNet, 2),
                        'tot_amt'      => round($totalNet, 2),
                        'notes'        => 'Net Salary Payable - ' . $monthLabel,
                    ]);
                }

                DB::table('journals')->insert($entries);

            } catch (\Exception $e) {
                \Log::error('Bulk payroll journal entry failed', [
                    'error' => $e->getMessage(),
                    'line'  => $e->getLine(),
                    'file'  => $e->getFile(),
                ]);
                $errors[] = ['journal_error' => $e->getMessage()];
            }
        }

        return response()->json([
            'success'   => true,
            'generated' => $generated,
            'skipped'   => $skipped,
            'errors'    => $errors,
            'message'   => "{$generated} payslip(s) generated successfully." . ($skipped ? " {$skipped} already existed." : ''),
        ]);
    }

}
