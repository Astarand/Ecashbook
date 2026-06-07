<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class EmployeePolicy extends Controller
{
    public function EmployeePolicyList()
    {
        $user = Auth::user();
		$userId = currentOwnerId();

        // Define the two required policies
        $requiredPolicies = [
            'Terms and Conditions',
            'Privacy Policy'
        ];

        $policies = [];

        foreach ($requiredPolicies as $policySubject) {
            // Check if policy exists for this user
            $existingPolicy = \DB::table('employee_policies')
                ->where('subject', $policySubject)
                ->where('added_by', $userId)
                ->where('status', '!=', 'delete')
                ->first();

            if ($existingPolicy) {
                $policies[] = $existingPolicy;
            } else {
                // Create a placeholder entry for display
                $policies[] = (object) [
                    'id' => null,
                    'subject' => $policySubject,
                    'status' => 'inactive',
                    'created_at' => null,
                    'content' => null
                ];
            }
        }

        return view('User.employee-policy-list', compact('policies'));
    }

    public function AddEmployeePolicy()
    {
        return view('User.add-employee-policy');
    }

    // 🟢 Save Policy Data
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

        // Insert new policy and get inserted ID
        $policyId = DB::table('employee_policies')->insertGetId([
            'subject' => $request->subject,
            'content' => $request->content,
            'added_by' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update employee policy read status to 'unread' for all employees under this user
        $this->updateEmployeePolicyReadStatus($request->subject, $userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Policy saved successfully and acceptance recorded!'
        ]);
    }


    public function delete($id)
    {
        \DB::table('employee_policies')
            ->where('id', $id)
            ->update(['status' => 'delete', 'updated_at' => now()]);

        return response()->json(['status' => 'success', 'message' => 'Policy deleted successfully.']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,deactive,delete',
        ]);

        $user = Auth::user();
		$userId = currentOwnerId();

        // Check if policy already exists for this user and subject
        $existingPolicy = \DB::table('employee_policies')
            ->where('subject', $request->subject)
            ->where('added_by', $userId)
            ->where('status', '!=', 'delete')
            ->first();

        if ($existingPolicy) {
            // Update existing policy
            \DB::table('employee_policies')
                ->where('id', $existingPolicy->id)
                ->update([
                    'content' => $request->content,
                    'status' => $request->status,
                    'updated_at' => now(),
                ]);

            // Delete existing acceptance records for this policy
            \DB::table('policy_accept')->where('policy_id', $existingPolicy->id)->delete();

            $message = 'Policy updated successfully!';
        } else {
            // Create new policy
            $policyId = \DB::table('employee_policies')->insertGetId([
                'subject' => $request->subject,
                'content' => $request->content,
                'status' => $request->status,
                'added_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $message = 'Policy created successfully!';
        }

        // Update employee policy read status to 'unread' for all employees under this user
        $this->updateEmployeePolicyReadStatus($request->subject, $userId);

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function edit($id)
    {
        $policy = \DB::table('employee_policies')->where('id', $id)->first();

        if (!$policy) {
            return redirect()->back()->with('error', 'Policy not found.');
        }

        return view('User.edit-policy', compact('policy'));
    }

    public function create($subject)
    {
        $policy = (object) [
            'id' => null,
            'subject' => urldecode($subject),
            'content' => '',
            'status' => 'active'
        ];

        return view('User.edit-policy', compact('policy'));
    }

    public function view($id)
    {
        $policy = \DB::table('employee_policies')->where('id', $id)->first();

        if (!$policy) {
            return redirect()->back()->with('error', 'Policy not found.');
        }

        return view('User.view-policy', compact('policy'));
    }

    /**
     * Update employee policy read status to 'unread' when policy is created or updated
     */
    private function updateEmployeePolicyReadStatus($policySubject, $userId)
    {
        // Determine which column to update based on policy subject
        $columnToUpdate = null;

        if ($policySubject === 'Terms and Conditions') {
            $columnToUpdate = 'terms_and_conditions';
        } elseif ($policySubject === 'Privacy Policy') {
            $columnToUpdate = 'privacy_policy_read';
        }

        // If valid policy subject, update all employees under this user
        if ($columnToUpdate) {
            \DB::table('employees')
                ->where('added_by', $userId)
                ->update([
                    $columnToUpdate => 'unread',
                    'updated_at' => now()
                ]);
        }
    }
}
