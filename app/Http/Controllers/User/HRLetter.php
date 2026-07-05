<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HRLetter extends Controller
{
    
    public function LetterList()
    {
		$userId = currentOwnerId();

        $letters = DB::table('hr_letter_master as master')
            ->leftJoin('company_hr_letter as company', function($join) use ($userId){
                $join->on('master.id', '=', 'company.hr_master_table_id')
                    ->where('company.user_id', $userId);
            })
            ->where('master.status', 'active')
            ->select(
                'master.id as master_id',
                'company.id as company_id',
                'company.subject as company_subject',
                'company.content as company_content',
                'company.status as company_status',
                'master.subject as master_subject',
                'master.content as master_content',
                'master.status as master_status'
            )
            ->get()
            ->map(function($item) {
                return (object)[
                    'id' => $item->company_id ?? $item->master_id,
                    'subject' => $item->company_subject ?? $item->master_subject,
                    'content' => $item->company_content ?? $item->master_content,
                    'status' => $item->company_status ?? $item->master_status,
                    'is_company_letter' => !is_null($item->company_id)
                ];
            });

        return view('User.hr-letter-list', compact('letters'));
    }

    // public function GenerateLetter()
    // {
    //     if (auth()->user()->u_type != '3') {
    //         return redirect()->route('index');
    //     }

    //     return view('User.generate-hr-letter');
    // }

    // // ✅ Store new HR Letter
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'subject' => 'required|string|max:255',
    //         'content' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $validator->errors()->first()
    //         ], 422);
    //     }

    //     $user = Auth::user();
	// 	$userId = currentOwnerId();

    //     DB::table('hr_letter_master')->insert([
    //         'subject' => $request->subject,
    //         'content' => $request->content,
    //         'added_by' => $userId,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'HR Letter created successfully!'
    //     ]);
    // }

    // ✅ Edit HR Letter
    public function edit($id)
    {
       $userId = currentOwnerId();
        
        // First check if it's a company-specific letter
        $companyLetter = DB::table('company_hr_letter')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
            
        if ($companyLetter) {
            $letter = (object)[
                'id' => $companyLetter->id,
                'subject' => $companyLetter->subject,
                'content' => $companyLetter->content,
                'is_company_letter' => true,
                'master_id' => $companyLetter->hr_master_table_id
            ];
        } else {
            // Check if it's a master letter
            $masterLetter = DB::table('hr_letter_master')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
                
            if (!$masterLetter) {
                return redirect()->route('user.HRLetterList')->with('error', 'HR Letter not found.');
            }
            
            $letter = (object)[
                'id' => $masterLetter->id,
                'subject' => $masterLetter->subject,
                'content' => $masterLetter->content,
                'is_company_letter' => false,
                'master_id' => $masterLetter->id
            ];
        }
        
        return view('User.edit-hr-letter', compact('letter'));
    }

    // ✅ Update HR Letter
    public function update(Request $request, $id)
    {
		$userId = currentOwnerId();
        try {
            // Log the incoming request for debugging
            \Log::info('HR Letter Update Request', [
                'id' => $id,
                'user_id' => $userId,
                'subject' => $request->subject,
                'content_length' => strlen($request->content ?? ''),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type')
            ]);

            $validator = Validator::make($request->all(), [
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                \Log::error('HR Letter Update Validation Failed', $validator->errors()->toArray());
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // First check if this ID is a company-specific letter
            $companyLetter = DB::table('company_hr_letter')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();
                
            \Log::info('Company Letter Check', ['found' => !is_null($companyLetter), 'id' => $id]);
                
            if ($companyLetter) {
                \Log::info('Path: Updating existing company letter');
                // Update existing company letter
                $updated = DB::table('company_hr_letter')
                    ->where('id', $id)
                    ->where('user_id', $userId)
                    ->update([
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'updated_at' => now(),
                    ]);
                    
                \Log::info('Company letter update result', ['updated' => $updated]);
                    
                return response()->json([
                    'status' => 'success',
                    'message' => 'Company HR Letter updated successfully!',
                    'updated_rows' => $updated
                ]);
            }
            
            // Check if it's a master letter
            $masterLetter = DB::table('hr_letter_master')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();
                
            \Log::info('Master Letter Check', ['found' => !is_null($masterLetter), 'id' => $id]);
                
            if (!$masterLetter) {
                \Log::error('Master letter not found', ['id' => $id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'HR Letter not found.'
                ], 404);
            }
            
            // Check if user already has a company version of this master letter
            $existingCompanyLetter = DB::table('company_hr_letter')
                ->where('hr_master_table_id', $id)
                ->where('user_id', $userId)
                ->first();
                
            \Log::info('Existing Company Letter Check', ['found' => !is_null($existingCompanyLetter)]);
                
            if ($existingCompanyLetter) {
                \Log::info('Path: Updating existing company version of master letter');
                // Update existing company version
                $updated = DB::table('company_hr_letter')
                    ->where('hr_master_table_id', $id)
                    ->where('user_id', $userId)
                    ->update([
                        'subject' => $request->subject,
                        'content' => $request->content,
                        'updated_at' => now(),
                    ]);
                \Log::info('Update result', ['updated' => $updated]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Master HR Letter updated successfully!',
                    'updated_rows' => $updated
                ]);
            } else {
                \Log::info('Path: Creating new company-specific letter from master');
                // Create new company-specific letter
                $inserted = DB::table('company_hr_letter')->insert([
                    'hr_master_table_id' => $id,
                    'user_id' => $userId,
                    'subject' => $request->subject,
                    'content' => $request->content,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Log::info('Insert result', ['inserted' => $inserted]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'New company HR Letter created successfully!',
                    'inserted' => $inserted
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('HR Letter Update Exception', [
                'id' => $id,
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the HR Letter: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Delete HR Letter
    public function destroy($id)
    {
        $userId = currentOwnerId();
        
        // Check if it's a company-specific letter
        $companyLetter = DB::table('company_hr_letter')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
            
        if ($companyLetter) {
            // Delete company letter
            DB::table('company_hr_letter')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->delete();
                
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }

    // Debug method to test database connection and data
    public function debug($id)
    {
        $userId = currentOwnerId();
        
        $masterLetter = DB::table('hr_letter_master')->where('id', $id)->first();
        $companyLetter = DB::table('company_hr_letter')->where('id', $id)->where('user_id', $userId)->first();
        $companyByMaster = DB::table('company_hr_letter')->where('hr_master_table_id', $id)->where('user_id', $userId)->first();
        
        return response()->json([
            'id' => $id,
            'user_id' => $userId,
            'master_letter' => $masterLetter,
            'company_letter' => $companyLetter,
            'company_by_master' => $companyByMaster,
            'tables_exist' => [
                'hr_letter_master' => DB::getSchemaBuilder()->hasTable('hr_letter_master'),
                'company_hr_letter' => DB::getSchemaBuilder()->hasTable('company_hr_letter')
            ]
        ]);
    }

    public function getEmployees()
    {
        $userId = currentOwnerId();
        $owner_user_type = currentOwnerUserType();

        // 1. Determine which column to look at based on the user type
        if (in_array($owner_user_type, [3, 6])) {
            $column = 'admin_add_by';
        } elseif (in_array($owner_user_type, [2, 5])) {
            $column = 'user_add_by';
        } elseif (in_array($owner_user_type, [1, 4])) {
            $column = 'ca_add_by';
        } else {
            // Fallback: Default column if user type doesn't match expected criteria
            $column = 'user_add_by'; 
        }

        // 2. Fetch data based on the dynamically determined column
        $employees = DB::table('users')
            ->where($column, $userId)
            ->select('id', 'name')
            ->get();

        return response()->json($employees);
    }

    // ✅ Send HR Letter to Employees
    public function SendHRLetter(Request $request)
    {
        $request->validate([
            'letter_id' => 'required',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:users,id',
        ]);

       $userId = currentOwnerId();

        // Fetch letter data (either company customized or master)
        $letter = DB::table('company_hr_letter')
            ->where('id', $request->letter_id)
            ->where('user_id', $userId)
            ->first();

        if (!$letter) {
            $letter = DB::table('hr_letter_master')
                ->where('id', $request->letter_id)
                ->first();
        }

        if (!$letter) {
            return response()->json(['success' => false, 'message' => 'Letter not found.']);
        }

        $data = [];
        foreach ($request->employee_ids as $empId) {
            $data[] = [
                'added_by' => $userId,
                'employee_id' => $empId,
                'subject' => $letter->subject,
                'content' => $letter->content,
                'sent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('company_hr_sent_letters')->insert($data);

        return response()->json(['success' => true, 'message' => 'Letter sent successfully!']);
    }


}
