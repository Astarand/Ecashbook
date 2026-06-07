<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use App\Models\EmployeeRating;

class UserEmployerPolicy extends Controller
{
    // public function UserEmployerList()
    // {
    //     $user = Auth::user();
       
    //     $policies = DB::table('employee_policies')
    //         ->where('status', 'active')
    //         ->orderByDesc('created_at')
    //         ->get()
    //         ->map(function ($policy) use ($user) {
    //             $policy->created_at = Carbon::parse($policy->created_at);

    //             // ✅ Check if this user has accepted the policy
    //             $policy->is_accepted = DB::table('policy_accept')
    //                 ->where('policy_id', $policy->id)
    //                 ->where('accept_emp_id', $user->id)
    //                 ->exists();

    //             return $policy;
    //         });

    //     // echo "<pre>";
    //     // print_r($policies);
    //     // die()   ;

    //     return view('Employee.UserEmployee.user-employer-list', compact('policies'));
    // }
    public function UserEmployerList()
    {
        $user = Auth::user();

        // Fetch employee policy status
        $employee = DB::table('employees')
            ->where('empId', $user->id)
            ->first();

        $policies = DB::table('employee_policies')
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($policy) use ($employee) {

                // Default
                $policy->policy_status = 'not_create';

                // Check Privacy Policy
                if ($policy->subject == 'Privacy Policy') {
                    $policy->policy_status = $employee->privacy_policy_read ?? 'not_create';
                }

                // Check Terms and Conditions
                if ($policy->subject == 'Terms and Conditions') {
                    $policy->policy_status = $employee->terms_and_conditions ?? 'not_create';
                }

                return $policy;
            });

        // echo "<pre>";
        // print_r($policies);
        // die();

        return view('Employee.UserEmployee.user-employer-list', compact('policies'));
    }
    

    // public function acceptPolicy(Request $request)
    // {
    //     $request->validate([
    //         'policy_id' => 'required|integer',
    //     ]);

    //     $user = Auth::user();

    //     // ✅ Find the policy
    //     $policy = DB::table('employee_policies')->where('id', $request->policy_id)->first();
    //     if (!$policy) {
    //         return response()->json(['status' => 'error', 'message' => 'Policy not found.']);
    //     }

    //     // ✅ Check if already accepted
    //     $exists = DB::table('policy_accept')
    //         ->where('policy_id', $request->policy_id)
    //         ->where('accept_emp_id', $user->id)
    //         ->exists();

    //     if ($exists) {
    //         return response()->json(['status' => 'info', 'message' => 'You have already accepted this policy.']);
    //     }

    //     // ✅ Insert acceptance record
    //     DB::table('policy_accept')->insert([
    //         'policy_id' => $request->policy_id,
    //         'added_by' => $policy->added_by,
    //         'accept_emp_id' => $user->id,
    //         'accept_date_time' => now(),
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Policy accepted successfully!',
    //         'policy_id' => $request->policy_id
    //     ]);
    // }

    public function acceptPolicy(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // Find the policy
        $policy = DB::table('employee_policies')
            ->where('id', $request->policy_id)
            ->first();

        if (!$policy) {
            return response()->json([
                'status' => 'error',
                'message' => 'Policy not found.'
            ]);
        }

        // Get employee details
        $employee = DB::table('employees')
            ->where('empId', $user->id)
            ->first();

        // Check already accepted from employees table
        if (
            ($policy->subject == 'Privacy Policy' && $employee->privacy_policy_read == 'read') ||
            ($policy->subject == 'Terms and Conditions' && $employee->terms_and_conditions == 'read')
        ) {
            return response()->json([
                'status' => 'info',
                'message' => 'You have already accepted this policy.'
            ]);
        }

        // Insert acceptance record
        DB::table('policy_accept')->insert([
            'policy_id'        => $request->policy_id,
            'added_by'         => $policy->added_by,
            'accept_emp_id'    => $user->id,
            'accept_date_time' => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        // Update employees table status
        if ($policy->subject == 'Privacy Policy') {

            DB::table('employees')
                ->where('empId', $user->id)
                ->update([
                    'privacy_policy_read' => 'read',
                    'updated_at' => now(),
                ]);

        } elseif ($policy->subject == 'Terms and Conditions') {

            DB::table('employees')
                ->where('empId', $user->id)
                ->update([
                    'terms_and_conditions' => 'read',
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Policy accepted successfully!',
            'policy_id' => $request->policy_id
        ]);
    }

    public function performace_review()
    {
		$empId = Auth::user()->id;

		// Fetch all ratings for this employee added by current user
		$ratings = EmployeeRating::where('empId', $empId)
			->orderBy('created_at', 'desc')
			->get();

		return view('Employee.UserEmployee.performace-review', compact('ratings', 'empId'));
    }

    public function viewRating($id)
    {
        $rating = EmployeeRating::find($id);
        if (! $rating) {
            return response()->json(['success' => false, 'message' => 'Rating not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'work_rating' => (int) $rating->work_rating,
                'skill_rating' => (int) $rating->skill_rating,
                'attendance_rating' => (int) $rating->attendance_rating,
                'teamwork_rating' => (int) $rating->teamwork_rating,
                'review' => $rating->review,
            ],
        ]);
    }



}
