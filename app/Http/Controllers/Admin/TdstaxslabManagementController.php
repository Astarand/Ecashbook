<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\TdsTaxSlab;

use Validator;
use Redirect;
use DB;
use Auth;
use Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;

class TdstaxslabManagementController extends Controller
{
   
    public function TdsTaxSlabList()
    {
        $tdslist = TdsTaxSlab::with('salarySlabs')
            ->orderBy('created_at', 'desc')
            ->get();

        $dropdowns = DB::table('dropdown_values')
            ->where('status', 1)
            ->pluck('option_text', 'option_value');

        foreach ($tdslist as $tds) {
            $tds->category_name = ($tds->module == 'Expense')
                ? ($dropdowns[$tds->category] ?? $tds->category)
                : $tds->category;
        }

        return view('Admin.tdstaxslab-list', compact('tdslist'));
    }

    public function AddTdsTaxSlab()
    {
        return view('Admin.tdstaxslab-add');
    }
    public function save_tds_tax_slab_bpk(Request $request)
    {
        //echo"<pre>".print_r($request->all(), true)."</pre>";exit;
        // Validate and save the TDS tax slab data
        $request->validate([
            'tds_slab_name' => 'required|string|max:255',
            'tds_slab_section' => 'required|string|max:255',
            'tds_slab_rate' => 'required|numeric|min:0',
            'tds_slab_description' => 'nullable|string',
            'tds_slab_status' => 'required|in:1,0',
            'tds_slab_created_by' => 'nullable|string|max:255',
            'tds_slab_updated_by' => 'nullable|string|max:255',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        // Save the TDS tax slab data
        $tdsTaxSlab = new TdsTaxSlab();
        $tdsTaxSlab->tds_slab_name = $request->tds_slab_name;
        $tdsTaxSlab->tds_slab_section = $request->tds_slab_section;
        $tdsTaxSlab->tds_slab_rate = $request->tds_slab_rate;
        $tdsTaxSlab->tds_slab_description = $request->tds_slab_description;
        $tdsTaxSlab->tds_slab_status = $request->tds_slab_status;
        $tdsTaxSlab->tds_slab_created_by = $request->tds_slab_created_by;
        $tdsTaxSlab->tds_slab_updated_by = $request->tds_slab_updated_by;
        $tdsTaxSlab->created_at = $request->tds_slab_created_at;
        $tdsTaxSlab->updated_at = $request->tds_slab_updated_at;
        $tdsTaxSlab->save();

        return redirect('/tds-tax-slab-list')->with('success', 'TDS Tax Slab added successfully.');
    }

    public function save_tds_tax_slab_bpk2(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'module'        => 'required|in:Expenses,Purchase,Assets',
            'category'      => 'required|string|max:255',
            'tds_section'   => 'required|string|max:10',
            'tds_rate'      => 'required|string|max:20',
            'entity'        => 'required|string|max:50',
            'threshold'     => 'nullable|string|max:100',
            'notes'         => 'nullable|string|max:255',
            'salary_slabs'  => 'nullable|string' // JSON
        ]);

        DB::beginTransaction();

        try {

            // ✅ Save main TDS rule
            $tdsRule = new TdsTaxSlab();
            $tdsRule->module          = $request->module;
            $tdsRule->category        = $request->category;
            $tdsRule->tds_section     = $request->tds_section;
            $tdsRule->tds_rate        = $request->tds_rate;
            $tdsRule->entity          = $request->entity;
            $tdsRule->threshold_limit = $request->threshold;
            $tdsRule->notes           = $request->notes;
            $tdsRule->status          = 1;
            $tdsRule->save();

            // ✅ Salary slab handling (ONLY for Salary & Wages)
            if ($request->category === 'Salary & Wages' && $request->filled('salary_slabs')) {

                $slabs = json_decode($request->salary_slabs, true);

                foreach ($slabs as $slab) {

                    if (
                        empty($slab['from_amount']) ||
                        !is_numeric($slab['from_amount']) ||
                        empty($slab['tax_rate']) ||
                        !is_numeric($slab['tax_rate'])
                    ) {
                        continue; // skip invalid row
                    }

                    DB::table('tds_salary_slabs')->insert([
                        'tds_rule_id' => $tdsRule->id,
                        'from_amount' => (float) $slab['from_amount'],
                        'to_amount'   => $slab['to_amount'] ?? 'Above',
                        'tax_rate'    => (float) $slab['tax_rate'],
                        'created_at'  => now()
                    ]);
                }

            }

            DB::commit();

            return response()->json([
                'class'    => 'succ',
                'message'  => 'TDS rule saved successfully',
                'redirect' => url('/tds-tax-slab-list')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'class'   => 'err',
                'message' => 'Error saving TDS rule',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function save_tds_tax_slab(Request $request)
    {
        $request->validate([
            'module'      => 'required|in:Expense,Purchase,Assets',
            'category'    => 'required|string|max:255',
            'tds_section' => 'required|string|max:10',
            'tds_rate'    => 'required|string|max:20',
            'entity'      => 'required|string|max:50',
            'threshold'   => 'nullable|string|max:100',
            'notes'       => 'nullable|string|max:255',
            'salary_slabs'=> 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            // ✅ MAIN RULE SAVE
            $tdsRule = TdsTaxSlab::create([
                'module'          => $request->module,
                'category'        => $request->category,
                'tds_section'     => $request->tds_section,
                'tds_rate'        => $request->tds_rate,
                'entity'          => $request->entity,
                'threshold_limit' => $request->threshold,
                'notes'           => $request->notes,
                'status'          => 1
            ]);

            // ❌ If still not saved, stop here
            if (!$tdsRule->id) {
                throw new \Exception('TDS rule insert failed');
            }

            // ✅ SALARY SLABS
            if ($request->category === 'employee_benefits' && $request->filled('salary_slabs')) {

                $slabs = json_decode($request->salary_slabs, true);

                if (!is_array($slabs)) {
                    throw new \Exception('Invalid slab JSON');
                }

                foreach ($slabs as $slab) {

                    if (
                        !isset($slab['from_amount'], $slab['tax_rate']) ||
                        !is_numeric($slab['from_amount']) ||
                        !is_numeric($slab['tax_rate'])
                    ) {
                        continue;
                    }

                    DB::table('tds_salary_slabs')->insert([
                        'tds_rule_id' => $tdsRule->id,
                        'from_amount' => (float) $slab['from_amount'],
                        'to_amount'   => $slab['to_amount'] ?? 'Above',
                        'tax_rate'    => (float) $slab['tax_rate'],
                        'created_at'  => now()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'class'    => 'succ',
                'message'  => 'TDS rule saved successfully',
                'redirect' => url('/tds-tax-slab-list')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('TDS SAVE ERROR', [
                'error' => $e->getMessage(),
                'data'  => $request->all()
            ]);

            return response()->json([
                'class'   => 'err',
                'message' => 'Error saving TDS rule',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function EditTdsTaxSlab($id)
    {
        $tdsRule = TdsTaxSlab::with('salarySlabs')->find($id);

        if (!$tdsRule) {
            return redirect('/tds-tax-slab-list')
                ->with('error', 'TDS Rule not found');
        }

        return view('Admin.tdstaxslab-edit', compact('tdsRule'));
    }


    public function TdsTaxSlabDetails($id)
    {
        // Fetch the TDS tax slab details by ID
        $tdsTaxSlab = TdsTaxSlab::find($id);
        if (!$tdsTaxSlab) {
            return redirect()->route('tds-tax-slab-list')->with('error', 'TDS Tax Slab not found.');
        }
        return view('Admin.tdstaxslab-details', compact('tdsTaxSlab'));
    }
	
	private function validateTds(Request $request)
	{
		return Validator::make($request->all(), [
			'tds_slab_name' => 'required|string|max:255',
			'tds_slab_section' => 'required|string|max:255',
			'tds_slab_rate' => 'required|numeric|min:0',
			'tds_slab_description' => 'nullable|string',
			'tds_slab_status' => 'required|in:1,0',
			'tds_slab_updated_by' => 'nullable|string|max:255',
		]);
	}

    public function update_tds_tax_slab(Request $request, $id)
    {
        // 1️⃣ Validation
        $validator = Validator::make($request->all(), [
            'module'      => 'required|string',
            'category'    => 'required|string',
            'tds_section' => 'required|string',
            'tds_rate'    => 'required|string',
            'entity'      => 'nullable|string',
            'threshold'   => 'nullable|string',
            'notes'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'class'   => 'error',
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {

            // 2️⃣ Update main TDS slab table
            DB::table('tds_rules')
                ->where('id', $id)
                ->update([
                    'module'          => $request->module,
                    'category'        => $request->category,
                    'tds_section'     => $request->tds_section,
                    'tds_rate'        => $request->tds_rate,
                    'entity'          => $request->entity,
                    'threshold_limit' => $request->threshold,
                    'notes'           => $request->notes,
                    'updated_at'      => now(),
                ]);

            // 3️⃣ Salary Slab Handling (ONLY Salary & Wages)
            if ($request->category === 'employee_benefits' && $request->filled('salary_slabs')) {

                $salarySlabs = json_decode($request->salary_slabs, true);

                // ❌ Delete old slabs
                DB::table('tds_salary_slabs')
                    ->where('tds_rule_id', $id)
                    ->delete();

                // ✅ Insert updated slabs
                foreach ($salarySlabs as $slab) {

                    if (
                        empty($slab['from_amount']) ||
                        empty($slab['tax_rate']) ||
                        !is_numeric($slab['from_amount']) ||
                        !is_numeric($slab['tax_rate'])
                    ) {
                        continue;
                    }

                    DB::table('tds_salary_slabs')->insert([
                        'tds_rule_id' => $id,
                        'from_amount' => (float) $slab['from_amount'],
                        'to_amount'   => $slab['to_amount'] ?? 'Above',
                        'tax_rate'    => (float) $slab['tax_rate'],
                        'created_at'  => now(),
                    ]);
                }

            } else {
                // ❌ If category changed → remove salary slabs
                DB::table('tds_salary_slabs')
                    ->where('tds_rule_id', $id)
                    ->delete();
            }

            DB::commit();

            return response()->json([
                'class'    => 'succ',
                'message'  => 'TDS Tax Slab updated successfully',
                'redirect' => url('/tds-tax-slab-list')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'class'   => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }




	public function update_tds_tax_slab_bpk(Request $request, $id)
	{
		// Validate input
		$validation = $this->validateTds($request);
		if ($validation->fails()) {
			//return response()->json($validation->errors()->toArray());
			return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
		}
		// Fetch existing slab
		$tdsTaxSlab = TdsTaxSlab::findOrFail($id);

		$tdsTaxSlab->tds_slab_name = $request->tds_slab_name;
		$tdsTaxSlab->tds_slab_section = $request->tds_slab_section;
		$tdsTaxSlab->tds_slab_rate = $request->tds_slab_rate;
		$tdsTaxSlab->tds_slab_description = $request->tds_slab_description;
		$tdsTaxSlab->tds_slab_status = $request->tds_slab_status;
		$tdsTaxSlab->tds_slab_updated_by = $request->tds_slab_updated_by;
		$tdsTaxSlab->updated_at = now();
		// Save updated data
		$tdsTaxSlab->save();

		//return redirect('/tds-tax-slab-list')->with('success', 'TDS Tax Slab updated successfully.');
		return response()->json([
                'status' => 'success',
                'class' => 'succ',
                'redirect' => url('/tds-tax-slab-list'),
                'message' => 'TDS Tax Slab updated successfully'
            ]);
	}
	
	public function deleteTdsTaxSlab(Request $request)
    {
        $del = DB::table('tds_tax_slab')->where('id', $request->id)->delete();
		if($del){
			$msg = array(
				'status' => 'success',
				'class' => 'succ',
				//'redirect' => url('/tds-tax-slab-list'),
				'message' => 'Record deleted successfully.'
			);
			return response()->json($msg);
		}else{
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				//'redirect' => url('/tds-tax-slab-list'),
				'message' => 'Delete action failed!'
			);
			return response()->json($msg);
		}
    }

    public function getCategories(Request $request)
    {
        $categories = DB::table('dropdown_values')
            ->where('status', 1)
            ->where('module', $request->module)
            ->select('option_value', 'option_text')
            ->get();

        return response()->json($categories);
    }


}