<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TaxDeductionController extends Controller
{
    // =========================
    // MASTER DROPDOWNS
    // =========================
    private function getDropdowns()
    {
        return [
            'deduction_category' => [
                'Business Deduction','Asset Deduction','Employee Deduction','Statutory Deduction',
                'Startup Deduction','Donation Deduction','Research Deduction','Partnership Deduction',
                'Export Deduction','Loss Adjustment','Tax Credit Adjustment',
            ],

            'deduction_type' => [
                'Business Expense','Asset Related','Employee Related','Statutory Payment',
                'Startup Benefit','Donation','Research & Development','Partner Related',
                'Export Related','Loss Adjustment','Tax Credit','Investment Related',
            ],

            'tax_treatment' => [
                'Allowable','Disallowed','Partially Allowable',
            ],

            'limit_type' => [
                'Formula Based','Percentage Based','Actual Eligible Amount','Carry Forward Based',
            ],

            'base_amount_source' => [
                'Actual Expense Amount','Asset Register Value','Eligible Salary','Actual Paid Amount',
                'Capital Balance','Donation Amount','Profit Before Deduction','Carried Forward Loss',
                'Project Cost','Eligible Turnover','Export Profit','Net Profit','Partner Capital',
                'Taxable Profit','Eligible Investment Amount','MAT Credit Balance',
            ],

            'automation_mode' => [
                'Fully Auto','Semi Auto','Manual','CA Verified',
            ],

            'linked_module' => [
                'Accounting Module','Expense Module','Asset Module','Payroll Module','GST Module',
                'TDS Module','Purchase Module','Sales Module','Loan Module','Donation Module',
                'Startup Module','Partner Capital Module','Export Module','Tax Computation Module',
                'MAT Register','Manual Adjustment Module',
            ],
        ];
    }

    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = DB::table('tax_deduction_masters as tdm')
            ->leftJoin('dropdown_values as dv', function ($join) {
                $join->on('dv.option_value', '=', 'tdm.expense_head')
                    ->where('dv.status', 1);
            })
            ->select(
                'tdm.*',
                'dv.option_text as expense_head_name',
                'dv.type as expense_head_type'
            )
            ->orderBy('tdm.id', 'desc')
            ->get();

        return view('Admin.tax_deduction.index', compact('data'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $dropdowns = $this->getDropdowns();
        return view('Admin.tax_deduction.create', compact('dropdowns'));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            
            'accounting_module' => 'required',
            'tax_treatment' => 'required',
        ]);

        DB::table('tax_deduction_masters')->insert([

            'accounting_module'   => $request->accounting_module,
            'expense_type'        => $request->expense_type,
            'expense_head'        => $request->expense_head,

            'tax_treatment'       => $request->tax_treatment,
            'allowed_ratio'       => $request->allowed_ratio,
            'allow_start'         => $request->allow_start,
            'allow_end'           => $request->allow_end,

            'is_active'           => 1,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        return redirect()->route('tax.index')
            ->with('success', 'Created successfully');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $deduction = DB::table('tax_deduction_masters')
            ->where('id', $id)
            ->first();

        return view('Admin.tax_deduction.edit', compact('deduction'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            
            'accounting_module' => 'required',
            'tax_treatment'     => 'required',
        ]);

        DB::table('tax_deduction_masters')
            ->where('id', $id)
            ->update([

                'accounting_module'  => $request->accounting_module,
                'expense_type'       => $request->expense_type,
                'expense_head'       => $request->expense_head,

                'tax_treatment'      => $request->tax_treatment,

                'allowed_ratio'      => $request->tax_treatment == 'Disallowed'
                                            ? 0
                                            : $request->allowed_ratio,

                'allow_start'        => $request->tax_treatment == 'Partial Allowed'
                                            ? $request->allow_start
                                            : null,

                'allow_end'          => $request->tax_treatment == 'Partial Allowed'
                                            ? $request->allow_end
                                            : null,

                'updated_at'         => now(),
            ]);

        return redirect()
            ->route('tax.index')
            ->with('success', 'Updated successfully');
    }

    // =========================
    // SHOW
    // =========================
    public function show($id)
    {
        $deduction = DB::table('tax_deduction_masters as tdm')
            ->leftJoin('dropdown_values as dv', function ($join) {
                $join->on('dv.option_value', '=', 'tdm.expense_head')
                    ->where('dv.status', 1);
            })
            ->select(
                'tdm.*',
                'dv.option_text as expense_head_name'
            )
            ->where('tdm.id', $id)
            ->first();

        return view('Admin.tax_deduction.show', compact('deduction'));
    }

    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {
        DB::table('tax_deduction_masters')->where('id', $id)->delete();

        return redirect()->route('tax.index')->with('success', 'Deleted successfully');
    }

    // Get the Expense 
    public function getExpenseHead(Request $request)
    {
        $heads = DB::table('dropdown_values')
            ->where('status', 1)
            ->where('module', 'Expense')
            ->where('dropdown_name', $request->expense_type)
            ->select('option_value', 'option_text')
            ->orderBy('option_text')
            ->get();

        return response()->json($heads);
    }
}
