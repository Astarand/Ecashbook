<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;



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
                'employees.employee_id',
                'users.name',
                'user_payslip.financial_year',
                'user_payslip.month',
                'user_payslip.tds_challan_no',
                'user_payslip.tds_bsr_code',
                'user_payslip.tds_deposit_date',
                'user_payslip.tds_deposit_status',

                DB::raw("
                    CAST(
                        JSON_UNQUOTE(
                            JSON_EXTRACT(
                                user_payslip.emp_salary_slip_response,
                                '$.visible_data.final_salary_calculation.tds'
                            )
                        ) AS DECIMAL(15,2)
                    ) as tds_amount
                ")
            )
            ->orderBy('users.name')
            ->get();

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

        // Half Yearly
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

        // Only employees having PF > 0
        $query->whereRaw("
            CAST(
                JSON_UNQUOTE(
                    JSON_EXTRACT(
                        emp_salary_slip_response,
                        '$.visible_data.final_salary_calculation.provident_fund'
                    )
                ) AS DECIMAL(12,2)
            ) > 0
        ");

        $pf = $query->select(
                'user_payslip.id',
                'user_payslip.user_emp_id',
                'employees.employee_id',
                'users.name',
                'user_payslip.financial_year',
                'user_payslip.month',
                'user_payslip.pf_trrn',
                'user_payslip.pf_crn',
                'user_payslip.pf_challan_generated_on',
                'user_payslip.pf_payment_confirmation_date',
                'user_payslip.pf_payment_status',
                DB::raw("
                    JSON_UNQUOTE(
                        JSON_EXTRACT(
                            emp_salary_slip_response,
                            '$.visible_data.final_salary_calculation.provident_fund'
                        )
                    ) as provident_fund
                ")
            )
            ->orderBy('users.name')
            ->get();

        return response()->json($pf);
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

                'employees.employee_id',
                'users.name',

                'user_payslip.financial_year',
                'user_payslip.month',

                'user_payslip.esi_employer_code',
                'user_payslip.esi_employer_name',
                'user_payslip.esi_contribution_period',
                'user_payslip.esi_challan_no',
                'user_payslip.esi_challan_created_date',
                'user_payslip.esi_challan_submitted_date',
                'user_payslip.esi_amount_paid',
                'user_payslip.esi_transaction_no',
                'user_payslip.esi_payment_status',

                DB::raw("
                    JSON_UNQUOTE(
                        JSON_EXTRACT(
                            emp_salary_slip_response,
                            '$.visible_data.final_salary_calculation.esi'
                        )
                    ) as esi
                ")
            )
            ->orderBy('users.name')
            ->get();

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

                'employees.employee_id',
                'users.name',

                'user_payslip.financial_year',
                'user_payslip.month',

                'user_payslip.ptax_grips_payment_id',
                'user_payslip.ptax_payment_initiated_date',
                'user_payslip.ptax_brn',
                'user_payslip.ptax_grn',
                'user_payslip.ptax_period_from',
                'user_payslip.ptax_period_to',
                'user_payslip.ptax_payment_ref_no',
                'user_payslip.ptax_amount_paid',
                'user_payslip.ptax_payment_status',

                DB::raw("
                    JSON_UNQUOTE(
                        JSON_EXTRACT(
                            emp_salary_slip_response,
                            '$.visible_data.final_salary_calculation.ptax'
                        )
                    ) AS ptax
                ")

            )
            ->orderBy('users.name')
            ->get();

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

}
