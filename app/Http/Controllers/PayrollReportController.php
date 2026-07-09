<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
