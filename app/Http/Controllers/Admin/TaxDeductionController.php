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
        $data = DB::table('tax_deduction_masters')
            ->orderBy('rule_priority', 'desc')
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
            'deduction_name' => 'required|string|max:255',
            'deduction_category' => 'required',
            'deduction_type' => 'required',
            'tax_treatment' => 'required',
        ]);

        DB::table('tax_deduction_masters')->insert([
            'deduction_name' => $request->deduction_name,
            'income_tax_section' => $request->income_tax_section,
            'deduction_category' => $request->deduction_category,
            'deduction_type' => $request->deduction_type,
            'tax_treatment' => $request->tax_treatment,
            'limit_type' => $request->limit_type,
            'base_amount_source' => $request->base_amount_source,
            'automation_mode' => $request->automation_mode,
            'limit_value' => $request->limit_value,
            'limit_rate' => $request->limit_rate,
            'limit_formula' => $request->limit_formula,
            'applicable_fy' => $request->applicable_fy,
            'linked_module' => $request->linked_module,
            'rule_priority' => $request->rule_priority ?? 0,
            'is_active' => 1,
            'created_at' => now(),
        ]);

        return redirect()->route('tax.index')->with('success', 'Created successfully');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $deduction = DB::table('tax_deduction_masters')->where('id', $id)->first();
        $dropdowns = $this->getDropdowns();

        return view('Admin.tax_deduction.edit', compact('deduction', 'dropdowns'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'deduction_name' => 'required',
            'deduction_category' => 'required',
            'deduction_type' => 'required',
            'tax_treatment' => 'required',
        ]);

        DB::table('tax_deduction_masters')
            ->where('id', $id)
            ->update([
                'deduction_name' => $request->deduction_name,
                'income_tax_section' => $request->income_tax_section,
                'deduction_category' => $request->deduction_category,
                'deduction_type' => $request->deduction_type,
                'tax_treatment' => $request->tax_treatment,
                'limit_type' => $request->limit_type,
                'base_amount_source' => $request->base_amount_source,
                'automation_mode' => $request->automation_mode,
                'limit_value' => $request->limit_value,
                'limit_rate' => $request->limit_rate,
                'limit_formula' => $request->limit_formula,
                'applicable_fy' => $request->applicable_fy,
                'linked_module' => $request->linked_module,
                'rule_priority' => $request->rule_priority ?? 0,
                //'is_active' => $request->is_active,
                'updated_at' => now(),
            ]);

        return redirect()->route('tax.index')->with('success', 'Updated successfully');
    }

    // =========================
    // SHOW
    // =========================
    public function show($id)
    {
        $deduction = DB::table('tax_deduction_masters')->where('id', $id)->first();
        $dropdowns = $this->getDropdowns();

        return view('Admin.tax_deduction.show', compact('deduction', 'dropdowns'));
    }

    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {
        DB::table('tax_deduction_masters')->where('id', $id)->delete();

        return redirect()->route('tax.index')->with('success', 'Deleted successfully');
    }
}
