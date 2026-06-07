<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Employees;

use Carbon\Carbon;

class EmployeeHrController extends Controller
{
    private function validateSecureAccess(string $empId, string $secureKey): bool
    {
        // Hash-based validation
        $expectedHash = hash('sha256', $empId . config('app.key'));

        // Compare the provided secure key with the expected hash
        if (hash_equals($expectedHash, $secureKey)) {
            return true;
        }

        return false;
    }

    public function hrLetterList(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'secure' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employee_id = $request->input('employee_id');
            $secure = $request->input('secure');

            // 2) Fetch employee_id from employees table using empId
            $employee = Employees::where('employee_id', $employee_id)->first();
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }



            // 3) Security validation using employee_id
            if (!$this->validateSecureAccess($employee_id, $secure)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }
            $employeeId = $employee->empId;
            // 4) Fetch HR letters from company_hr_sent_letters table
            $hrLetters = DB::table('company_hr_sent_letters')
                ->select('id', 'added_by', 'employee_id', 'subject', 'content', 'sent_at')
                ->where('employee_id', $employeeId)
                ->orderBy('sent_at', 'desc')
                ->get()
                ->map(function ($letter) {
                    // Convert HTML content to plain text
                    $letter->content = strip_tags($letter->content);
                    // Remove extra whitespace and &nbsp; entities
                    $letter->content = html_entity_decode($letter->content, ENT_QUOTES, 'UTF-8');
                    $letter->content = preg_replace('/\s+/', ' ', trim($letter->content));
                    return $letter;
                });

            return response()->json([
                'status' => 'success',
                'data' => $hrLetters
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function payslipDetails(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'financial_year' => 'required|string',
                'month' => 'required|integer|min:1|max:12',
                'secure' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employee_id = $request->input('employee_id');
            $financial_year = $request->input('financial_year');
            $month = (int) $request->input('month');
            $secure = $request->input('secure');

            // 2) Fetch employee record
            $employee = DB::table('employees')->where('employee_id', $employee_id)->first();

            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            // 3) Security validation
            if (!$this->validateSecureAccess($employee_id, $secure)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            $empId = $employee->empId;
            $addedBy = $employee->added_by;

            // 4) Fetch payslip details
            $payslip = DB::table('user_payslip')
                ->select('id', 'user_emp_id', 'financial_year', 'month', 'payslip_text', 'payslip_path', 'payslip_no', 'date', 'emp_salary_slip_response', 'created_at', 'updated_at')
                ->where('user_emp_id', $empId)
                ->where('financial_year', $financial_year)
                ->where('month', $month)
                ->first();

            if (!$payslip) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payslip not found for the given month and financial year.'
                ], 404);
            }

            // 5) Fetch company information
            $company = DB::table('company_profiles')->where('userId', $addedBy)->first();

            if ($company) {
                // Fetch related country, state, and city names
                $countryName = DB::table('countries')->where('id', $company->comp_bill_country)->value('name');
                $stateName = DB::table('states')->where('id', $company->comp_bill_state)->value('name');
                $cityName = DB::table('cities')->where('id', $company->comp_bill_city)->value('name');
                $logoPath = $company->comp_logo ? url('/storage/profile/' . $company->comp_logo) : null;
                $companyInfo = [
                    'comp_logo' => $logoPath,
                    'comp_name' => $company->comp_name,
                    'comp_email' => $company->comp_email,
                    'comp_pan_no' => $company->comp_pan_no,
                    'comp_bill_gst_no' => $company->comp_bill_gst_no,
                    'comp_bill_addone' => $company->comp_bill_addone,
                    'comp_bill_addtwo' => $company->comp_bill_addtwo,
                    'comp_bill_country' => $countryName,
                    'comp_bill_state' => $stateName,
                    'comp_bill_city' => $cityName,
                ];
            } else {
                $companyInfo = null;
            }

            // 6) Prepare payslip data
            $payslipData = [
                'id' => $payslip->id,
                'user_emp_id' => $payslip->user_emp_id,
                'financial_year' => $payslip->financial_year,
                'month' => $payslip->month,
                'payslip_no' => $payslip->payslip_no,
                'payslip_text' => $payslip->payslip_text,
                'payslip_path' => $payslip->payslip_path,
                'date' => $payslip->date,
                'created_at' => $payslip->created_at,
                'updated_at' => $payslip->updated_at,
                'salary_details' => json_decode($payslip->emp_salary_slip_response, true),
            ];

            // 7) Return final JSON response
            return response()->json([
                'status' => 'success',
                'data' => [
                    'payslip' => $payslipData,
                    'company_info' => $companyInfo,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function employeeReviewList(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'secure' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employee_id = $request->input('employee_id');
            $secure = $request->input('secure');

            // 2) Fetch employee record from employees table
            $employee = Employees::where('employee_id', $employee_id)->first();
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            // 3) Security validation
            if (!$this->validateSecureAccess($employee_id, $secure)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            $employeeEmpId = $employee->empId;

            // 4) Fetch Employee Reviews from employee_ratings table
            $reviews = DB::table('employee_ratings')
                ->select(
                    'empId',
                    'review_month',
                    'review_year',
                    'work_rating',
                    'skill_rating',
                    'attendance_rating',
                    'teamwork_rating',
                    'total_percentage',
                    'review',
                    'created_at',
                )
                ->where('empId', $employeeEmpId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($review) {
                    // Clean up review text if any HTML tags exist
                    $review->review = strip_tags($review->review);
                    $review->review = html_entity_decode($review->review, ENT_QUOTES, 'UTF-8');
                    $review->review = preg_replace('/\s+/', ' ', trim($review->review));
                    return $review;
                });

            return response()->json([
                'status' => 'success',
                'data' => $reviews
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function employeePolicyList(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'secure' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employee_id = $request->input('employee_id');
            $secure = $request->input('secure');

            // 2) Fetch employee record from employees table
            $employee = Employees::where('employee_id', $employee_id)->first();
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            // 3) Security validation
            if (!$this->validateSecureAccess($employee_id, $secure)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 4) Get employee details
            $employeeEmpId = $employee->empId;
            $addedBy = $employee->added_by;
            $privacy_policy_read = $employee->privacy_policy_read ?? null;
            $terms_and_conditions = $employee->terms_and_conditions ?? null;

            // 5) Fetch policies from employee_policies table (added_by = employee.added_by)
            $policies = DB::table('employee_policies')
                ->select(
                    'id',
                    'subject',
                    'content',
                    'added_by',
                    'status',
                    'created_at',
                    'updated_at'
                )
                ->where('added_by', $addedBy)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($policy) {
                    // Clean content: remove HTML and extra spaces
                    $policy->content = strip_tags($policy->content);
                    $policy->content = html_entity_decode($policy->content, ENT_QUOTES, 'UTF-8');
                    $policy->content = preg_replace('/\s+/', ' ', trim($policy->content));
                    return $policy;
                });

            // 6) Return combined response
            return response()->json([
                'status' => 'success',
                'employee_info' => [
                    'empId' => $employeeEmpId,
                    'privacy_policy_read' => $privacy_policy_read,
                    'terms_and_conditions' => $terms_and_conditions,
                ],
                'data' => $policies
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePolicyReadStatus(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'secure' => 'required|string|min:8', // required security key
                'type' => 'required|in:privacy_policy,terms_and_conditions', // allowed types only
                'status' => 'required|boolean', // must be true or false
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employee_id = $request->input('employee_id');
            $secure = $request->input('secure');
            $type = $request->input('type');
            $status = $request->input('status');

            // 2) Fetch employee record
            $employee = Employees::where('employee_id', $employee_id)->first();
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            // 3) Security validation (same pattern as other APIs)
            if (!$this->validateSecureAccess($employee_id, $secure)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 4) Define string status value for database
            $statusValue = $status ? 'read' : 'unread';

            // 5) Update specific read status field
            if ($type === 'privacy_policy') {
                $employee->privacy_policy_read = $statusValue;
            } elseif ($type === 'terms_and_conditions') {
                $employee->terms_and_conditions = $statusValue;
            }

            $employee->save();

            // 6) Response
            return response()->json([
                'status' => 'success',
                'message' => ucfirst(str_replace('_', ' ', $type)) . ' status updated successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }




}
