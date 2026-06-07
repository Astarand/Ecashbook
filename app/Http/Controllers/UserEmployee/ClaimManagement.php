<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Validator;
use App\Models\ExpenditureClaim;
use App\Models\SupplyRequisition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Employees;



class ClaimManagement extends Controller
{
    //
    public function ExpenditureClaimsList()
    {
        $authId = Auth::id();

        // 1️⃣ Find employee record where empId = Auth::id()
        $employee = Employees::where('empId', $authId)->first();

        if (!$employee) {
            // Optional: handle if no employee found
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        // 2️⃣ Fetch claims belonging to that employee_id
        $claims = ExpenditureClaim::where('employee_id', $employee->employee_id)
                    ->orderBy('id', 'desc')
                    ->get();

        // 3️⃣ Return to view with claims data
        return view('Employee.UserEmployee.expenditure_claims_list', compact('claims'));
    }

    /* Supply Requisitions List */
    public function SupplyRequisitionsList()
    {
        $authId = Auth::id();

        // 1️⃣ Find employee record where empId = Auth::id()
        $employee = Employees::where('empId', $authId)->first();

        if (!$employee) {
            // Optional: handle if no employee found
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        // 2️⃣ Fetch requisitions that belong to this employee
        $requisitions = \DB::table('supply_requisitions')
            ->where('employee_id', $employee->employee_id)
            ->orderBy('id', 'desc')
            ->get();

        return view('Employee.UserEmployee.supply_requisitions_list', compact('requisitions'));
    }


    // Store Expenditure Claim
    public function UserEmployeeExpenditureStore(Request $request)
    {
        try {
            // Validate inputs
            $request->validate([
                'date'           => 'required|date',
                'category'       => 'required|string|max:255',
                'claim_amount'   => 'required|numeric',
                'details'        => 'required|string',
                'payment_method' => 'required|string|max:50',
                'receipt'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
                'comments'       => 'nullable|string',
            ]);

            // Get the logged-in user's ID
            $authId = Auth::id();

            // 1️⃣ Find the employee where empId = Auth::id()
            $employee = Employees::where('empId', $authId)->first();

            if (!$employee) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Employee record not found for the authenticated user.'
                ], 404);
            }

            // 2️⃣ Get employee_id and added_by from employees table
            $employee_id = $employee->employee_id ?? null;
            $employee_added_by = $employee->added_by ?? $authId;

            // 3️⃣ Fetch u_type from users table where id = employees.added_by
            $userAddedBy = User::find($employee_added_by);
            $u_type = $userAddedBy->u_type ?? 'user';

            // 4️⃣ Handle single file upload
            $filePath = null;
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                $path = $file->store('receipts', 'public');
                $filePath = basename($path); // store filename only
            }

            // 5️⃣ Create the expenditure claim
            ExpenditureClaim::create([
                'employee_id'    => $employee_id,
                'claim_date'     => $request->date,
                'category'       => $request->category,
                'claim_amount'   => $request->claim_amount,
                'description'    => $request->details,
                'payment_method' => $request->payment_method,
                'receipt'        => $filePath,
                'comments'       => $request->comments,
                'added_by'       => $employee_added_by,
                'u_type'         => $u_type,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Expenditure claim submitted successfully!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


	// Update Expenditure Claim
    public function UserEmployeeExpenditureUpdate(Request $request, $id)
    {
        $claim = ExpenditureClaim::findOrFail($id);

        $request->validate([
            'date'           => 'required|date',
            'category'       => 'required|string|max:255',
            'claim_amount'   => 'required|numeric|min:0',
            'details'        => 'required|string',
            'payment_method' => 'required|string|max:50',
            'receipt'        => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'comments'       => 'nullable|string',
        ]);

        // 🔹 Handle single receipt file
        $filePath = $claim->receipt; // keep old file by default
        if ($request->hasFile('receipt')) {
            // Delete old file if exists
            if ($claim->receipt && Storage::disk('public')->exists('receipts/' . $claim->receipt)) {
                Storage::disk('public')->delete('receipts/' . $claim->receipt);
            }

            // Store new file
            $path = $request->file('receipt')->store('receipts', 'public');
            $filePath = basename($path);
        }

        // 🔹 Update claim data
        $claim->update([
            'claim_date'     => $request->date,
            'category'       => $request->category,
            'claim_amount'   => $request->claim_amount,
            'description'    => $request->details,
            'payment_method' => $request->payment_method,
            'receipt'        => $filePath,
            'comments'       => $request->comments,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Expenditure claim updated successfully!'
        ]);
    }

    // Store Supply Requisition
    public function UserEmployeeSupplyRequisitionsStore(Request $request)
    {
        try {
            // ✅ Validate inputs
            $request->validate([
                'date'            => 'required|date',
                'category'        => 'required|string|max:255',
                'details'         => 'required|string',
                'quantity'        => 'required|integer|min:1',
                'amount'          => 'required|numeric|min:0',
                'priority'        => 'required|string',
                'return_exchange' => 'nullable|string',
                'attachment'      => 'nullable|mimes:jpeg,png,jpg,gif,pdf|max:5120',
                'comments'        => 'nullable|string',
            ]);

            // ✅ Step 1: Get logged-in user ID
            $authId = Auth::id();

            // ✅ Step 2: Find employee where empId = Auth::id()
            $employee = Employees::where('empId', $authId)->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee record not found for the authenticated user.'
                ], 404);
            }

            // ✅ Step 3: Extract employee_id and added_by from Employees table
            $employee_id = $employee->employee_id ?? null;
            $employee_added_by = $employee->added_by ?? $authId;

            // ✅ Step 4: Get u_type from Users table using added_by
            $userAddedBy = User::find($employee_added_by);
            $u_type = $userAddedBy->u_type ?? 'user';

            // ✅ Step 5: Handle file upload
            $fileName = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('attachments', $fileName, 'public');
            }

            // ✅ Step 6: Store Supply Requisition
            SupplyRequisition::create([
                'employee_id'      => $employee_id,
                'requisition_date' => $request->date,
                'category'         => $request->category,
                'details'          => $request->details,
                'quantity'         => $request->quantity,
                'amount'           => $request->amount,
                'priority'         => $request->priority,
                'return_exchange'  => $request->return_exchange,
                'attachment'       => $fileName,
                'comments'         => $request->comments,
                'added_by'         => $employee_added_by,
                'u_type'           => $u_type,
            ]);

            // ✅ Success response
            return response()->json([
                'success' => true,
                'message' => 'Supply requisition submitted successfully!',
            ]);

        } catch (ValidationException $e) {
            // Validation error handling
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // General exception handling
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function UserEmployeeSupplyRequisitionsUpdate(Request $request, $id)
	{
		$requisition = \App\Models\SupplyRequisition::findOrFail($id);

		$request->validate([
			'date'            => 'required|date',
			'category'        => 'required|string|max:255',
			'details'         => 'required|string',
			'quantity'        => 'required|integer|min:1',
			'amount'          => 'required|numeric|min:0',
			'priority'        => 'required|string',
			'return_exchange' => 'nullable|string',
			'attachment'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
			'comments'        => 'nullable|string',
		]);

		// Handle attachment
		if ($request->hasFile('attachment')) {
			// Delete old file if exists
			if ($requisition->attachment && \Storage::disk('public')->exists('attachments/' . $requisition->attachment)) {
				\Storage::disk('public')->delete('attachments/' . $requisition->attachment);
			}
			$fileName = time() . '_' . $request->file('attachment')->getClientOriginalName();
			$request->file('attachment')->storeAs('attachments', $fileName, 'public');
		} else {
			$fileName = $requisition->attachment;
		}

		$requisition->update([
			
			'requisition_date'=> $request->date,
			'category'        => $request->category,
			'details'         => $request->details,
			'quantity'        => $request->quantity,
			'amount'          => $request->amount,
			'priority'        => $request->priority,
			'return_exchange' => $request->return_exchange,
			'attachment'      => $fileName,
			'comments'        => $request->comments,
			
		]);

		return response()->json(['success' => true, 'message' => 'Supply requisition updated successfully!']);
	}





}
