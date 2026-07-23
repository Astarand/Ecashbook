<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncomeTaxSlab;
use Validator;
use Redirect;
use DB;
use Auth;

class IncomeTaxSlabController extends Controller
{
    /**
     * Display list of all Income Tax Slabs
     */
    public function incomeTaxSlabList()
    {
        $slabs = IncomeTaxSlab::orderBy('created_at', 'desc')->get();
        return view('Admin.income-tax-slab-list', compact('slabs'));
    }

    /**
     * Show create form
     */
    public function createIncomeTaxSlab()
    {
        return view('Admin.income-tax-slab-form');
    }

    /**
     * Store new Income Tax Slab
     */
    public function storeIncomeTaxSlab(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entity_type' => 'required|string|max:255',
            'company_type' => 'required|string|max:255',
            'applicable_fy' => 'required|string|max:255',
            'assessment_year' => 'required|string|max:255',
            'tax_regime' => 'required|string|max:255',
            'taxpayer_category' => 'required|string|max:255',
            'income_slab_from' => 'required|numeric|min:0',
            'income_slab_to' => 'required_unless:is_unlimited,1|nullable|numeric|min:0',
			'is_unlimited'   => 'nullable|boolean',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'surcharge_rate' => 'nullable|numeric|min:0|max:100',
            'cess_rate' => 'nullable|numeric|min:0|max:100',
            'marginal_relief_applicable' => 'nullable|in:0,1',
            'mat_applicable' => 'nullable|in:0,1',
            'amt_applicable' => 'nullable|in:0,1',
            'rebate_applicable' => 'nullable|in:0,1',
            'rebate_section' => 'nullable|string|max:255',
            'rebate_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
			$incomeSlabTo = $request->boolean('is_unlimited') ? null : $request->input('income_slab_to');
            IncomeTaxSlab::create([
                'entity_type' => $request->entity_type,
                'company_type' => $request->company_type,
                'applicable_fy' => $request->applicable_fy,
                'assessment_year' => $request->assessment_year,
                'tax_regime' => $request->tax_regime,
                'taxpayer_category' => $request->taxpayer_category,
                'income_slab_from' => $request->income_slab_from,
                'income_slab_to' => $incomeSlabTo,
                'tax_rate' => $request->tax_rate,
                'surcharge_rate' => $request->surcharge_rate ?? 0,
                'cess_rate' => $request->cess_rate ?? 0,
                'marginal_relief_applicable' => $request->marginal_relief_applicable ?? false,
                'mat_applicable' => $request->mat_applicable ?? false,
                'amt_applicable' => $request->amt_applicable ?? false,
                'rebate_applicable' => $request->rebate_applicable ?? false,
                'rebate_section' => $request->rebate_section,
                'rebate_limit' => $request->rebate_limit ?? 0,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_by' => Auth::check() ? Auth::id() : NULL,
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Income Tax Slab created successfully!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating Income Tax Slab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit form
     */
    public function editIncomeTaxSlab($id)
    {
        $slab = IncomeTaxSlab::findOrFail($id);
        return view('Admin.income-tax-slab-form', compact('slab'));
    }

    /**
     * Update Income Tax Slab
     */
    public function updateIncomeTaxSlab(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'entity_type' => 'required|string|max:255',
            'company_type' => 'required|string|max:255',
            'applicable_fy' => 'required|string|max:255',
            'assessment_year' => 'required|string|max:255',
            'tax_regime' => 'required|string|max:255',
            'taxpayer_category' => 'required|string|max:255',
            'income_slab_from' => 'required|numeric|min:0',
            'income_slab_to' => 'required_unless:is_unlimited,1|nullable|numeric|min:0',
			'is_unlimited'   => 'nullable|boolean',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'surcharge_rate' => 'nullable|numeric|min:0|max:100',
            'cess_rate' => 'nullable|numeric|min:0|max:100',
            'marginal_relief_applicable' => 'nullable|in:0,1',
            'mat_applicable' => 'nullable|in:0,1',
            'amt_applicable' => 'nullable|in:0,1',
            'rebate_applicable' => 'nullable|in:0,1',
            'rebate_section' => 'nullable|string|max:255',
            'rebate_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $slab = IncomeTaxSlab::findOrFail($id);
			$incomeSlabTo = $request->boolean('is_unlimited') ? null : $request->input('income_slab_to');
            $slab->update([
                'entity_type' => $request->entity_type,
                'company_type' => $request->company_type,
                'applicable_fy' => $request->applicable_fy,
                'assessment_year' => $request->assessment_year,
                'tax_regime' => $request->tax_regime,
                'taxpayer_category' => $request->taxpayer_category,
                'income_slab_from' => $request->income_slab_from,
                'income_slab_to' => $incomeSlabTo,
                'tax_rate' => $request->tax_rate,
                'surcharge_rate' => $request->surcharge_rate ?? 0,
                'cess_rate' => $request->cess_rate ?? 0,
                'marginal_relief_applicable' => $request->marginal_relief_applicable ?? false,
                'mat_applicable' => $request->mat_applicable ?? false,
                'amt_applicable' => $request->amt_applicable ?? false,
                'rebate_applicable' => $request->rebate_applicable ?? false,
                'rebate_section' => $request->rebate_section,
                'rebate_limit' => $request->rebate_limit ?? 0,
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => Auth::check() ? Auth::id() : NULL,
            ]);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Income Tax Slab updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error updating Income Tax Slab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Income Tax Slab
     */
    public function deleteIncomeTaxSlab($id)
    {
        try {
            $slab = IncomeTaxSlab::findOrFail($id);
            $slab->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Income Tax Slab deleted successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting Income Tax Slab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $slab = IncomeTaxSlab::findOrFail($id);
            $slab->status = $request->status;
            $slab->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show view form
     */
    public function viewIncomeTaxSlab($id)
    {
        $slab = IncomeTaxSlab::findOrFail($id);
        return view('Admin.income-tax-slab-view', compact('slab'));
    }
}
