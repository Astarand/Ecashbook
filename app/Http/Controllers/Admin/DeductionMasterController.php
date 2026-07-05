<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DeductionMaster;

class DeductionMasterController extends Controller
{
    public function index()
    {
        $deductions = DeductionMaster::latest()->get();

        return view(
            'Admin.deduction_master.index',
            compact('deductions')
        );
    }

    public function create()
    {
        return view('Admin.deduction_master.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'deduction_name'      => 'required|string|max:255',
            'income_tax_section'  => 'required|string|max:100',
        ]);

        DeductionMaster::create([
            'deduction_name'      => $request->deduction_name,
            'income_tax_section'  => $request->income_tax_section,
            'deduction_category'  => $request->deduction_category,
            'deduction_type'      => $request->deduction_type,

            'limit_type'          => $request->limit_type,
            'base_amount_source'  => $request->base_amount_source,
            'automation_mode'     => $request->automation_mode,

            'limit_value'         => $request->limit_value,
            'limit_formula'       => $request->limit_formula,
            'applicable_fy'       => $request->applicable_fy,

            'linked_module'       => $request->linked_module,
            'remarks'             => $request->remarks,

            'active_status'       => $request->active_status,
            'allow_disallow_status' => $request->allow_disallow_status,
        ]);

        return redirect()
            ->route('deduction-master.index')
            ->with('success', 'Deduction created successfully.');
    }

    public function edit($id)
    {
        $deduction = DeductionMaster::findOrFail($id);

        return view(
            'Admin.deduction_master.edit',
            compact('deduction')
        );
    }

    public function show($id)
    {
        $deduction = DeductionMaster::findOrFail($id);

        return view(
            'Admin.deduction_master.show',
            compact('deduction')
        );
    }

    // public function update(Request $request, $id)
    // {
    //     $deduction = DeductionMaster::findOrFail($id);

    //     $request->validate([
    //         'deduction_name' => 'required',
    //         'income_tax_section' => 'required'
    //     ]);

    //     $deduction->update([
    //         'deduction_name'        => $request->deduction_name,
    //         'income_tax_section'    => $request->income_tax_section,
    //         'deduction_category'    => $request->deduction_category,
    //         'deduction_amount_logic'=> $request->deduction_amount_logic,
    //         'deduction_type'        => $request->deduction_type,
    //         'amount'                => $request->amount,
    //         'maximum_limit'         => $request->maximum_limit,
    //         'applicable_fy'         => $request->applicable_fy,
    //         'deduction_mode'        => $request->deduction_mode,
    //         'linked_module'         => $request->linked_module,
    //         'active_status'         => $request->active_status,
    //         'allow_disallow_status' => $request->allow_disallow_status
    //     ]);

    //     return redirect()
    //         ->route('deduction-master.index')
    //         ->with('success','Deduction updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        $deduction = DeductionMaster::findOrFail($id);

        $request->validate([
            'deduction_name'      => 'required',
            'income_tax_section'  => 'required',
        ]);

        $deduction->update([
            'deduction_name'          => $request->deduction_name,
            'income_tax_section'      => $request->income_tax_section,
            'deduction_category'      => $request->deduction_category,
            'deduction_type'          => $request->deduction_type,
            'limit_type'             => $request->limit_type,
            'base_amount_source'     => $request->base_amount_source,
            'automation_mode'        => $request->automation_mode,
            'limit_value'            => $request->limit_value,
            'limit_formula'          => $request->limit_formula,
            'applicable_fy'          => $request->applicable_fy,
            'linked_module'          => $request->linked_module,
            'remarks'                => $request->remarks,
            'allow_disallow_status'  => $request->allow_disallow_status,
            'active_status'          => $request->active_status,
        ]);

        return redirect()
            ->route('deduction-master.index')
            ->with('success', 'Deduction updated successfully.');
    }

    public function destroy($id)
    {
        DeductionMaster::findOrFail($id)->delete();

        return redirect()
            ->route('deduction-master.index')
            ->with('success','Deduction deleted successfully.');
    }
}
