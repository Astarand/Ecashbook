<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use DateInterval;
use DatePeriod;




class PayrollReportController extends Controller
{
    public function summary(Request $request)
    {
        $ownerId = currentOwnerId();
        $authUserId = auth()->id();

        $month = $request->month;
        $fy = $request->fy;

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
        $previousFY = $fy;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousFY = ($fyStart - 1) . '-' . ($fyEnd - 1);
        }

        // Employee Summary
        $grossSalary = 0;
        $netSalary = 0;
        $pfLiability = 0;
        $esiLiability = 0;
        $ptLiability = 0;
        $tdsLiability = 0;
        $lwfLiability = 0;
        $lopTotal = 0;
        $paid = 0;
        $unpaid = 0;
        $totalActiveEmployees = 0;
        // Active employees are those who have a payslip generated for the selected month and FY
        $selectedMonthStart = Carbon::createFromDate($fyStart, $currentMonth, 1)->startOfMonth();

        // January, February, March belong to next FY
        if ($currentMonth <= 3) {
            $selectedMonthStart->year = $fyEnd;
        }
        $selectedMonthEnd = $selectedMonthStart->copy()->endOfMonth();

        $totalActiveEmployees = DB::table('employees')
            ->where('added_by', $ownerId)
            ->where(function ($q) use ($selectedMonthEnd) {

                // Active Employees
                $q->whereIn('emp_status', ['Confirmed', 'In Probation'])

                // Resigned / Terminated
                ->orWhere(function ($qq) use ($selectedMonthEnd) {
                    $qq->whereIn('emp_status', ['Resigned', 'Terminated'])
                    ->whereNotNull('regine_date')
                    // Employee is counted if resigned/terminated on or after
                    // the selected month's last date.
                    ->whereDate('regine_date', '>=', $selectedMonthEnd);
                });
            })
            ->get();

        // Check if payslips exist for the selected month and FY

        $payslipExists = DB::table('user_payslip')
            ->where('added_by', $ownerId)
            ->where('month', $currentMonth)
            ->where('financial_year', $fy)
            ->exists();
        

        if ($payslipExists) {

            $payslips = DB::table('user_payslip')
                ->where('added_by', $ownerId)
                ->where('month', $currentMonth)
                ->where('financial_year', $fy)
                ->select('emp_salary_slip_response')
                ->get();

            foreach ($payslips as $row) {

                $response = json_decode($row->emp_salary_slip_response, true);

                if (empty($response['visible_data']['final_salary_calculation'])) {
                    continue;
                }

                $salary = $response['visible_data']['final_salary_calculation'];

                $grossSalary += (float)($salary['basic_salary'] ?? 0);
                $netSalary += (float)($salary['net_salary'] ?? 0);
                $pfLiability += (float)($salary['provident_fund'] ?? 0);
                $esiLiability += (float)($salary['esi'] ?? 0);
                $ptLiability += (float)($salary['ptax'] ?? 0);
                $tdsLiability += (float)($salary['tds'] ?? 0);
                $lwfLiability += (float)($salary['lwf'] ?? 0);
                $lopTotal += (float)($salary['lop'] ?? 0);

                $paid++;
            }

        } else {

            /*
            ==========================================================
            Write your "Payslip Not Generated" calculation here
            ==========================================================
            */

            $employees = $totalActiveEmployees;

            foreach ($employees as $employee) {

                $salary = $this->calculateEmployeeSalary(
                    $employee,
                    $currentMonth,
                    $fy
                );

                $grossSalary += $salary['gross_salary'];
                $netSalary += $salary['net_salary'];
                $pfLiability += $salary['pf'];
                $esiLiability += $salary['esi'];
                $ptLiability += $salary['pt'];
                $tdsLiability += $salary['tds'];
                $lwfLiability += $salary['lwf'];
                $lopTotal += $salary['lop'];

                $unpaid++;
            }

        }

        $totalActiveEmployees = count($totalActiveEmployees);
        $totalGrossSalary = round($grossSalary, 2);
        $totalNetSalary = round($netSalary, 2);
        $totalPfLiability = round($pfLiability, 2);
        $totalEsiLiability = round($esiLiability, 2);
        $ptLiability = round($ptLiability, 2);
        $totalLwfLiability = round($lwfLiability, 2);
        $tdsLiability = round($tdsLiability, 2);


        return response()->json([
            'success' => true,

            'total_active_employees' => $totalActiveEmployees,
            'gross_salary'           => $totalGrossSalary,
            'net_salary'             => $totalNetSalary,
            'pf_liability'           => $totalPfLiability,
            'esi_liability'          => $totalEsiLiability,
            'pt_liability'           => $ptLiability,
            'tds_liability'          => $tdsLiability,
            'lwf_liability'          => $totalLwfLiability,
            'lop_total'              => $lopTotal,
            'paid'                   => $paid,
            'unpaid'                 => $unpaid,

            'previous_month'         => $previousMonth,
            'previous_financial_year'=> $previousFY,
        ]);
    }

    public function summary_bpk(Request $request)
    {
        $ownerId = currentOwnerId();
        $authUserId = auth()->id();

        $month = $request->month;
        $fy = $request->fy;

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
        $previousFY = $fy;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousFY = ($fyStart - 1) . '-' . ($fyEnd - 1);
        }

        // Employee Summary
        $summary = DB::table('employees')
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
            ->selectRaw("
                COUNT(*) as total_active_employees,

                COALESCE(SUM(total_addition),0) as gross_salary,

                COALESCE(SUM(net_sal),0) as net_salary,

                COALESCE(SUM(
                    CASE
                        WHEN epf_applicable = 1 THEN provident_fund
                        ELSE 0
                    END
                ),0) as pf_liability,

                COALESCE(SUM(
                    CASE
                        WHEN esic_applicable = 1 THEN esi
                        ELSE 0
                    END
                ),0) as esi_liability,

                COALESCE(SUM(
                    CASE
                        WHEN ptax_applicable = 1 THEN ptax
                        ELSE 0
                    END
                ),0) as pt_liability,

                COALESCE(SUM(
                    CASE
                        WHEN tds_applicable = 1 THEN tds
                        ELSE 0
                    END
                ),0) as tds_liability
            ")
            ->first();

        // Employee IDs
        $employeeIds = DB::table('employees')
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
            ->pluck('empId');

        // Paid Employees
        $paid = DB::table('user_payslip')
            ->whereIn('user_emp_id', $employeeIds)
            ->where('financial_year', $previousFY)
            ->where('month', $previousMonth)
            ->distinct()
            ->count('user_emp_id');

        // Unpaid Employees
        $unpaid = max(0, $summary->total_active_employees - $paid);

        return response()->json([
            'success' => true,

            'total_active_employees' => $summary->total_active_employees,
            'gross_salary'           => $summary->gross_salary,
            'net_salary'             => $summary->net_salary,
            'pf_liability'           => $summary->pf_liability,
            'esi_liability'          => $summary->esi_liability,
            'pt_liability'           => $summary->pt_liability,
            'tds_liability'          => $summary->tds_liability,

            'paid'                  => $paid,
            'unpaid'                => $unpaid,
            'previous_month'        => $previousMonth,
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

        $basicSalary = $baseGross * 0.50;

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
            $pf = $basicSalary * 0.12;
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
        $ownerId = currentOwnerId();
        $authUserId = auth()->id();

        $month = $request->month;
        $fy = $request->fy;

        // Month Name => Month Number
        $months = [
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
            'December' => 12,
        ];

        $currentMonth = $months[$month];

        [$fyStart, $fyEnd] = explode('-', $fy);

        // Previous Month
        $previousMonth = $currentMonth - 1;
        $previousFY = $fy;

        if ($previousMonth == 0) {
            $previousMonth = 12;
            $previousFY = ($fyStart - 1) . '-' . ($fyEnd - 1);
        }

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
            ->select(
                'e.empId',
                'e.employee_id',
                'u.name',
                'd.designation_name',
                'e.joining_date',
                'e.total_addition',
                'e.net_sal',

                DB::raw("CASE WHEN e.epf_applicable=1 THEN e.provident_fund ELSE 0 END as provident_fund"),
                DB::raw("CASE WHEN e.esic_applicable=1 THEN e.esi ELSE 0 END as esi"),
                DB::raw("CASE WHEN e.ptax_applicable=1 THEN e.ptax ELSE 0 END as ptax"),
                DB::raw("CASE WHEN e.tds_applicable=1 THEN e.tds ELSE 0 END as tds")
            )
            ->get();

        foreach ($employees as $employee) {

            $paid = DB::table('user_payslip')
                ->where('user_emp_id', $employee->empId)
                ->where('financial_year', $previousFY)
                ->where('month', $previousMonth)
                ->exists();

            $employee->advance = 0;
            $employee->loan_deduction = 0;
            $employee->payment_status = $paid ? 'Salary Done' : 'Payment Pending';
        }

        return response()->json($employees);
    }

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

        $paymentDate = $request->input('payment_date');
        $transactionId = $request->input('transaction_id');

        $update = [];

        if ($paymentDate) {
            $update['payment_date'] = $paymentDate;
        }

        if ($transactionId) {
            $update['payment_trans_id'] = $transactionId;
        }

        $update['payment_status'] = 'Done';

        try {
            $affected = DB::table('user_payslip')
                ->whereIn('id', $ids)
                ->where('added_by', $ownerId)
                ->update($update);

            return response()->json(['message' => "Updated {$affected} record(s)", 'updated' => $affected]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Update failed: ' . $e->getMessage()], 500);
        }
    }

    //------- Update TDS -------//
    public function updateTds(Request $request)
    {
        $ownerId = currentOwnerId();

        $ids = (array) $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([
                'message' => 'No TDS IDs provided.'
            ], 422);
        }

        $update = [];

        if ($request->filled('tds_tan')) {
            $update['tds_tan'] = $request->tds_tan;
        }

        if ($request->filled('tds_financial_year')) {
            $update['tds_financial_year'] = $request->tds_financial_year;
        }

        if ($request->filled('tds_nature_of_payment')) {
            $update['tds_nature_of_payment'] = $request->tds_nature_of_payment;
        }

        if ($request->filled('tds_amount')) {
            $update['tds_amount'] = $request->tds_amount;
        }

        if ($request->filled('tds_cin')) {
            $update['tds_cin'] = $request->tds_cin;
        }

        if ($request->filled('tds_challan_no')) {
            $update['tds_challan_no'] = $request->tds_challan_no;
        }

        if ($request->filled('tds_bsr_code')) {
            $update['tds_bsr_code'] = $request->tds_bsr_code;
        }

        if ($request->filled('tds_deposit_date')) {
            $update['tds_deposit_date'] = $request->tds_deposit_date;
        }

        if ($request->filled('tds_tender_date')) {
            $update['tds_tender_date'] = $request->tds_tender_date;
        }

        $update['tds_deposit_status'] = 'Done';

        try {

            $affected = DB::table('user_payslip')
                ->whereIn('id', $ids)
                ->where('added_by', $ownerId)
                ->update($update);

            return response()->json([
                'message' => "Updated {$affected} record(s)",
                'updated' => $affected
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'TDS update failed: '.$e->getMessage()
            ],500);

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
        $request->validate([
            'ids' => 'required|array|min:1',
        ]);

        DB::table('user_payslip')
            ->whereIn('id', $request->ids)
            ->update([

                'pf_trrn' => $request->pf_trrn,

                'pf_challan_generated_on' => $request->pf_challan_generated,

                'pf_establishment_id' => $request->pf_establishment_id,

                'pf_wage_month' => $request->pf_wage_month,

                'pf_total_amount' => $request->pf_total_amount,

                'pf_crn' => $request->pf_crn,

                'pf_payment_confirmation_date' => $request->pf_payment_date,

                'updated_at' => now()

            ]);

        return response()->json([
            'status' => true,
            'message' => 'Selected PF records updated successfully.'
        ]);
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
        $request->validate([
            'ids' => 'required|array|min:1',
        ]);

        DB::table('user_payslip')
            ->whereIn('id', $request->ids)
            ->update([

                // 'esi_employer_code'            => $request->esi_employer_code,
                // 'esi_employer_name'            => $request->esi_employer_name,
                'esi_contribution_period'      => $request->esi_contribution_period,
                'esi_challan_no'               => $request->esi_challan_no,
                'esi_challan_created_date'     => $request->esi_challan_created_date,
                'esi_challan_submitted_date'   => $request->esi_challan_submitted_date,
                'esi_amount_paid'              => $request->esi_amount_paid,
                'esi_transaction_no'           => $request->esi_transaction_no,
                'esi_payment_status'           => 'Done',
                'updated_at'                   => now()

            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Selected ESI records updated successfully.'
        ]);
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
        $request->validate([
            'ids' => 'required|array|min:1',
        ]);

        DB::table('user_payslip')
            ->whereIn('id', $request->ids)
            ->update([

                'ptax_grips_payment_id'      => $request->ptax_grips_payment_id,
                'ptax_payment_initiated_date'=> $request->ptax_payment_initiated_date,
                'ptax_brn'                  => $request->ptax_brn,
                'ptax_grn'                  => $request->ptax_grn,
                'ptax_period_from'          => $request->ptax_period_from,
                'ptax_period_to'            => $request->ptax_period_to,
                'ptax_payment_ref_no'       => $request->ptax_payment_ref_no,
                'ptax_amount_paid'          => $request->ptax_amount_paid,
                'ptax_payment_status'       => 'Done',

                'updated_at' => now()

            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Selected PTax records updated successfully.'
        ]);
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
            ->where('up.financial_year', $financialYear)
            ->where('e.lwf_applicable', 1);

        if (!empty($months)) {
            $query->whereIn('up.month', $months);
        }

        $records = $query->select(
                'up.id',
                'up.user_emp_id',
                'up.month',
                'e.employee_id',
                'u.name',
                's.name as state_name',
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.total_earnings')
                        ) AS DECIMAL(15,2)
                    ) as gross_wages
                "),
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.lwf_deduct')
                        ) AS DECIMAL(15,2)
                    ) as lwf_employee
                "),
                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(up.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.lwf_company_contribution')
                        ) AS DECIMAL(15,2)
                    ) as lwf_employer
                ")
            )
            ->orderBy('u.name')
            ->get();

        foreach ($records as $row) {
            $row->lwf_total = ($row->lwf_employee ?? 0) + ($row->lwf_employer ?? 0);
            $row->status    = 'Filed';
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

}
