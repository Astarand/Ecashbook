<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;

use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HsnSacSearchController extends Controller
{
    
	public function search(Request $request)
    {
        $search = trim($request->search);
        $codeType = $request->code_type;

        if (strlen($search) < 2) {

            return response()->json([
                'success' => false,
                'message' => 'Please enter at least 2 characters.',
                'data' => []
            ]);
        }

        $query = DB::table('hsn_sac_masters')
            ->where('status', 1);

        /*
         * HSN / SAC filter
         */
        if (!empty($codeType)) {

            $query->where('code_type', $codeType);

        }

        /*
         * Search code OR description
         */
        $query->where(function ($q) use ($search) {

            $q->where('code', 'LIKE', $search . '%')
              ->orWhere('description', 'LIKE', '%' . $search . '%');

        });

        $data = $query
            ->select(
                'id',
                'code_type',
                'code',
                'description',
                'gst_rate',
                'apply_cond'
            )
            ->orderByRaw("
                CASE
                    WHEN code = ? THEN 1
                    WHEN code LIKE ? THEN 2
                    ELSE 3
                END
            ", [
                $search,
                $search . '%'
            ])
            ->orderBy('code')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    /**
     * Get single HSN/SAC
     */
    public function get($id)
    {
        $data = DB::table('hsn_sac_masters')
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$data) {

            return response()->json([
                'success' => false,
                'message' => 'HSN/SAC not found.'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    /**
     * Store manual HSN/SAC
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code_type' => 'required|in:HSN,SAC',

                'code' => [
                    'required',
                    'string',
                    'max:50'
                ],

                'description' => [
                    'required',
                    'string',
                    'max:500'
                ],

                'gst_rate' => [
                    'required',
                    'numeric',
                    'min:0',
                    'max:100'
                ],
            ],
            [
                'code_type.required' => 'Please select HSN or SAC.',
                'code.required' => 'HSN/SAC code is required.',
                'description.required' => 'Product/Service name is required.',
                'gst_rate.required' => 'GST rate is required.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Please correct the errors.',
                'errors' => $validator->errors()
            ], 422);

        }

        $codeType = strtoupper(trim($request->code_type));
        $code = trim($request->code);
        $description = trim($request->description);
        $gstRate = $request->gst_rate;

        /*
         * Check existing record
         *
         * Same code can have different GST rates,
         * therefore check code + type + GST rate.
         */
        $existing = DB::table('hsn_sac_masters')
            ->where('code_type', $codeType)
            ->where('code', $code)
            ->where('gst_rate', $gstRate)
            ->where('status', 1)
            ->first();

        if ($existing) {

            return response()->json([
                'success' => true,
                'message' => 'HSN/SAC already exists.',
                'data' => $existing,
                'existing' => true
            ]);
        }

        $id = DB::table('hsn_sac_masters')->insertGetId([

            'code_type' => $codeType,

            'code' => $code,

            'description' => $description,

            'gst_rate' => $gstRate,

            'apply_cond' => null,

            'status' => 1,

            'created_at' => now(),

            'updated_at' => now(),

        ]);

        $data = DB::table('hsn_sac_masters')
            ->where('id', $id)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'HSN/SAC added successfully.',
            'data' => $data,
            'existing' => false
        ]);
    }
	
}
