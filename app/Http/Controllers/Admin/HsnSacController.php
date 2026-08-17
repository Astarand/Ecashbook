<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Str;

use App\Imports\HsnSacImport;
use App\Imports\HsnSacWorkbookImport;
use App\Models\Hsn_sac_masters;
use Maatwebsite\Excel\Facades\Excel;


class HsnSacController extends Controller
{

    public function index(Request $request)
    {
        $query = Hsn_sac_masters::query();

        if ($request->filled('code_type')) {
            $query->where(
                'code_type',
                strtoupper($request->code_type)
            );
        }

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('apply_cond', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('gst_rate')) {
            $query->where(
                'gst_rate',
                $request->gst_rate
            );
        }

        $records = $query
						->orderBy('id')
						->orderBy('code_type')
						->orderBy('code')
						->orderBy('gst_rate')
						->paginate(25)
						->withQueryString();

        return view('Admin.hsn-sac.index',compact('records'));
    }


    /**
     * Store manually
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code_type' => 'required|in:HSN,SAC',
                'code' => 'required|string|max:20',
                'description' => 'required|string',
                'gst_rate' => 'required|numeric|min:0|max:100',
                'apply_cond' => 'nullable|string',
            ],
            [
                'code_type.required' => 'Please select code type.',
                'code.required' => 'Code is required.',
                'description.required' => 'Description is required.',
                'gst_rate.required' => 'GST rate is required.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        Hsn_sac_masters::create([
            'code_type' => strtoupper($request->code_type),
            'code' => trim($request->code),
            'description' => trim($request->description),
            'gst_rate' => $request->gst_rate,
            'apply_cond' => trim($request->apply_cond ?? ''),
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'HSN/SAC added successfully.',
        ]);
    }


    /**
     * Get record
     */
    public function show($id)
    {
        $record = Hsn_sac_masters::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $record,
        ]);
    }


    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $record = Hsn_sac_masters::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'code_type' => 'required|in:HSN,SAC',
                'code' => 'required|string|max:20',
                'description' => 'required|string',
                'gst_rate' => 'required|numeric|min:0|max:100',
                'apply_cond' => 'nullable|string',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $record->update([
            'code_type' => strtoupper($request->code_type),
            'code' => trim($request->code),
            'description' => trim($request->description),
            'gst_rate' => $request->gst_rate,
            'apply_cond' => trim($request->apply_cond ?? ''),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'HSN/SAC updated successfully.',
        ]);
    }


    /**
     * Delete
     */
    public function destroy($id)
    {
        $record = Hsn_sac_masters::findOrFail($id);

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'HSN/SAC deleted successfully.',
        ]);
    }


    /**
     * Upload Excel
     */
	public function upload(Request $request)
	{
		$validator = Validator::make(
			$request->all(),
			[
				'file' => [
					'required',
					'file',
					'mimes:xlsx,xls',
					'max:10240',
				],
			]
		);

		if ($validator->fails()) {

			return response()->json([
				'success' => false,
				'errors' => $validator->errors(),
			], 422);
		}

		try {

			Excel::import(
				new HsnSacWorkbookImport(),
				$request->file('file')
			);

			return response()->json([
				'success' => true,
				'message' => 'HSN and SAC data imported successfully.',
			]);

		} catch (\Throwable $e) {

			return response()->json([
				'success' => false,
				'message' => 'Unable to import HSN/SAC file.',
				'error' => $e->getMessage(),
			], 500);
		}
	}

    /**
     * Change status
     */
    public function status($id)
    {
        $record = Hsn_sac_masters::findOrFail($id);

        $record->status = !$record->status;
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
        ]);
    }
}