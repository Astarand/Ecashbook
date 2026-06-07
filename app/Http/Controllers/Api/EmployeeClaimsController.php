<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenditureClaim;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequisition;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Support\Facades\Validator;



class EmployeeClaimsController extends Controller
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
    
    public function submitExpenditureClaim(Request $request)
    {
        try {
            // 1) Validate input (form-data)
            $validator = Validator::make($request->all(), [
                'employee_id'    => 'required',
                'date'           => 'required|date',
                'category'       => 'required|string|max:255',
                'claim_amount'   => 'required|numeric',
                'details'        => 'required|string',
                'payment_method' => 'required|string|max:50',
                'receipt'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'comments'       => 'nullable|string',
                'secure'         => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employeeId = $request->input('employee_id');
            $secure     = $request->input('secure');

            // 2) Security validation
            if (!$this->validateSecureAccess($employeeId, $secure)) {
                return response()->json([
                    'status'     => 'error',
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch employee details
            $employee = Employees::where('employee_id', $employeeId)->first();
            if (!$employee) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            // 4) Fetch user details for u_type
            $user = User::find($employee->empId);
            $addUser = User::find($employee->added_by);
            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found for this employee'
                ], 404);
            }

            // 5) Handle file upload (from form-data)
            // $filePath = null;
            // if ($request->hasFile('receipt')) {
            //     $file = $request->file('receipt');
            //     $path = $file->store('receipts', 'public');
            //     $filePath = basename($path);
            // }

            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('receipts', $filename, 'public');
                $filePath = 'storage/' . $path;
            }

            // 6) Save claim
            $claim = ExpenditureClaim::create([
                'employee_id'    => $employeeId,
                'claim_date'     => $request->input('date'),
                'category'       => $request->input('category'),
                'claim_amount'   => $request->input('claim_amount'),
                'description'    => $request->input('details'),
                'payment_method' => $request->input('payment_method'),
                'receipt'        => $filePath,
                'comments'       => $request->input('comments'),
                'status'         => 'Pending',
                'added_by'       => $employee->added_by,  // from employees table
                'u_type'         => $addUser->u_type,        // from users table
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Expenditure claim submitted successfully!',
                'data'    => $claim
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function expenditureClaimList(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'secure'      => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employeeId = $request->input('employee_id');
            $secure     = $request->input('secure');

            // 2) Security validation
            if (!$this->validateSecureAccess($employeeId, $secure)) {
                return response()->json([
                    'status'     => 'error',
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch claims
            $claims = ExpenditureClaim::where('employee_id', $employeeId)
                    ->orderBy('claim_date', 'desc')
                    ->get()
                    ->map(function ($claim) {
                        if ($claim->receipt) {
                            $claim->receipt = config('app.url') . '/storage/receipts/' . $claim->receipt;
                        }
                        return $claim;
                    });

            return response()->json([
                'status' => 'success',
                'data'   => $claims
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function expenditureClaimDetails(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'claim_id'    => 'required|integer',
                'secure'      => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employeeId = $request->input('employee_id');
            $claimId    = $request->input('claim_id');
            $secure     = $request->input('secure');

            // 2) Security validation
            if (!$this->validateSecureAccess($employeeId, $secure)) {
                return response()->json([
                    'status'     => 'error',
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch claim details
            $claim = ExpenditureClaim::where('employee_id', $employeeId)
                    ->where('id', $claimId)
                    ->first();

            if (!$claim) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Claim not found'
                ], 404);
            }

            if ($claim->receipt) {
                $claim->receipt = config('app.url') . '/storage/receipts/' . $claim->receipt;
            }

            return response()->json([
                'status' => 'success',
                'data'   => $claim
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function submitSupplyRequisition(Request $request)
    {
        try {
            // 1) Validate form-data input
            $validator = Validator::make($request->all(), [
                'employee_id'     => 'required',
                'date'            => 'required|date',
                'category'        => 'required|string|max:255',
                'details'         => 'required|string',
                'quantity'        => 'required|integer|min:1',
                'amount'          => 'required|numeric|min:0',
                'priority'        => 'required|string',
                'return_exchange' => 'nullable|string',
                'attachment'      => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
                'comments'        => 'nullable|string',
                'secure'          => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employeeId = $request->input('employee_id');
            $secure     = $request->input('secure');

            // 2) Security validation
            if (!$this->validateSecureAccess($employeeId, $secure)) {
                return response()->json([
                    'status'     => 'error',
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch employee details
            $employee = Employees::where('employee_id', $employeeId)->first();
            if (!$employee) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Employee not found'
                ], 404);
            }

            // 4) Fetch user details for u_type  
            $user = User::find($employee->empId);
            $addUser = User::find($employee->added_by);
            

            if (!$user) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found for this employee'
                ], 404);
            }

            // 5) Handle file upload
            // $fileName = null;
            // if ($request->hasFile('attachment')) {
            //     $file = $request->file('attachment');
            //     $fileName = time() . '_' . $file->getClientOriginalName();
            //     $file->storeAs('attachments', $fileName, 'public');
            // }

            $fileName = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                
                // Generate a unique name using uniqid + hash + extension
                $fileName = uniqid() . '_' . md5(time() . rand()) . '.' . $file->getClientOriginalExtension();
                
                // Save to storage/app/public/attachments
                $file->storeAs('attachments', $fileName, 'public');
            }

            // 6) Save requisition
            $requisition = SupplyRequisition::create([
                'employee_id'     => $employeeId,
                'requisition_date'=> $request->input('date'),
                'category'        => $request->input('category'),
                'details'         => $request->input('details'),
                'quantity'        => $request->input('quantity'),
                'amount'          => $request->input('amount'),
                'priority'        => $request->input('priority'),
                'return_exchange' => $request->input('return_exchange'),
                'attachment'      => $fileName,
                'comments'        => $request->input('comments'),
                'added_by'        => $employee->added_by, 
                'u_type'          => $addUser->u_type,
            ]);

            // 7) Append full URL for attachment in response
            if ($requisition->attachment) {
                $requisition->attachment = config('app.url') . '/storage/attachments/' . $requisition->attachment;
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Supply requisition submitted successfully!',
                'data'    => $requisition
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function supplyRequisitionList(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'secure'      => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employeeId = $request->input('employee_id');
            $secure     = $request->input('secure');

            // 2) Security validation
            if (!$this->validateSecureAccess($employeeId, $secure)) {
                return response()->json([
                    'status'     => 'error',
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch requisitions
            $requisitions = SupplyRequisition::where('employee_id', $employeeId)
                    ->orderBy('requisition_date', 'desc')
                    ->get()
                    ->map(function ($req) {
                        if ($req->attachment) {
                            $req->attachment = config('app.url') . '/storage/attachments/' . $req->attachment;
                        }
                        return $req;
                    });

            return response()->json([
                'status' => 'success',
                'data'   => $requisitions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function supplyRequisitionDetails(Request $request)
    {
        try {
            // 1) Validate input
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required',
                'requisition_id' => 'required|integer',
                'secure'      => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $employeeId    = $request->input('employee_id');
            $requisitionId = $request->input('requisition_id');
            $secure        = $request->input('secure');

            // 2) Security validation
            if (!$this->validateSecureAccess($employeeId, $secure)) {
                return response()->json([
                    'status'     => 'error',
                    'message'    => 'Unauthorized access. Invalid security credentials.',
                    'error_code' => 'SECURITY_VALIDATION_FAILED'
                ], 403);
            }

            // 3) Fetch requisition details
            $requisition = SupplyRequisition::where('employee_id', $employeeId)
                    ->where('id', $requisitionId)
                    ->first();

            if (!$requisition) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Requisition not found'
                ], 404);
            }

            if ($requisition->attachment) {
                $requisition->attachment = config('app.url') . '/storage/attachments/' . $requisition->attachment;
            }

            return response()->json([
                'status' => 'success',
                'data'   => $requisition
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
