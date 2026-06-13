<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HRLetterTemplateController extends Controller
{
    //

    public function TemplateList()
    {
        $letters = DB::table('hr_letter_master')
            ->whereIn('status', ['active', 'deactive'])
            ->select('id', 'subject', 'content', 'status')
            ->orderBy('id', 'desc')
            ->get();

        return view('Admin.hr-letter-master-list', compact('letters'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $exists = DB::table('hr_letter_master')->where('id', $id)->first();
        if (!$exists) {
            return response()->json(['status' => 'error', 'message' => 'HR Letter not found.'], 404);
        }

        DB::table('hr_letter_master')->where('id', $id)->update([
            'subject'    => $request->subject,
            'content'    => $request->content,
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'HR Letter updated successfully!']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,deactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid status.'], 422);
        }

        $exists = DB::table('hr_letter_master')->where('id', $id)->first();
        if (!$exists) {
            return response()->json(['status' => 'error', 'message' => 'HR Letter not found.'], 404);
        }

        DB::table('hr_letter_master')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        $label = $request->status === 'active' ? 'activated' : 'deactivated';
        return response()->json(['status' => 'success', 'message' => "HR Letter {$label} successfully!"]);
    }

    public function destroy($id)
    {
        $exists = DB::table('hr_letter_master')->where('id', $id)->first();
        if (!$exists) {
            return response()->json(['status' => 'error', 'message' => 'HR Letter not found.'], 404);
        }

        DB::table('hr_letter_master')->where('id', $id)->delete();

        return response()->json(['status' => 'success', 'message' => 'HR Letter deleted successfully!']);
    }


    public function GenerateLetter()
    {
        if (auth()->user()->u_type != '3') {
            return redirect()->route('index');
        }

        return view('User.generate-hr-letter');
    }

    // ✅ Store new HR Letter
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
		$userId = currentOwnerId();

        DB::table('hr_letter_master')->insert([
            'subject' => $request->subject,
            'content' => $request->content,
            'added_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'HR Letter created successfully!'
        ]);
    }
}
