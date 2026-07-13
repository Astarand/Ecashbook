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
            return response()->json(['message' => 'No TDS IDs provided.'], 422);
        }

        $utr = $request->input('tds_challan_no');
        $bsr = $request->input('tds_bsr_code');
        $depositDate = $request->input('tds_deposit_date');

        $update = [];

        if ($utr) {
            $update['tds_challan_no'] = $utr;
        }
        if ($bsr) {
            $update['tds_bsr_code'] = $bsr;
        }
        if ($depositDate) {
            $update['tds_deposit_date'] = $depositDate;
        }

        $update['tds_deposit_status'] = 'Done';

        try {
            $affected = DB::table('user_payslip')
                ->whereIn('id', $ids)
                ->where('added_by', $ownerId)
                ->update($update);

            return response()->json(['message' => "Updated {$affected} record(s)", 'updated' => $affected]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'TDS update failed: ' . $e->getMessage()], 500);
        }
    }

    //------- TDS List -------//

    public function getTdsList(Request $request)
    {
        $ownerId = currentOwnerId();

        $financialYear = $request->financial_year;
        $filterType = $request->filter_type;
        $period = $request->period;

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

            // No month filter required.
            // Only financial_year condition will be applied.

        }

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
                'user_payslip.tds_deposit_status'
            )
            ->orderBy('users.name')
            ->get();

        return response()->json($tds);
    }

}
