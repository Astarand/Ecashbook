<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\CaPaymentHistory;
use App\Helpers\AuditLogger;

class PaymentController extends Controller
{
    public function PaymentHistory()
    {
        // Fetch payment data


        $payments = DB::table('ca_payment_history')
            ->select('ca_payment_history.*',
                DB::raw('
                    CASE
                        WHEN ca_payment_history.entity_type = "company" THEN company_profiles.comp_name
                        WHEN ca_payment_history.entity_type = "agent" THEN busi_agents.agent_name
                        ELSE ca_payment_history.name
                    END as entity_name'
                )
            )
            ->leftJoin('company_profiles', 'company_profiles.userId', '=', 'ca_payment_history.customer_id') // Join company_profiles on customer_id
            ->leftJoin('busi_agents', 'busi_agents.id', '=', 'ca_payment_history.agent_id') // Join busi_agents on agent_id
            ->where('ca_payment_history.added_by', '=', Auth::user()->id) // Filter by the logged-in user
            ->whereIn('ca_payment_history.entity_type', ['company', 'agent', 'other']) // Filter only 'company', 'agent', and 'other'
            ->orderBy('ca_payment_history.id', 'desc') // Order by ID in descending order
            ->get();



        // Pass data to the view
        // echo '<pre>';
        // print_r($payments);
        // die();
        return view('Ca.payment-history', ['payments' => $payments]);
    }
    public function showAddPaymentForm()
    {
        return view('Ca.ca_payment_add');
    }
    public function showEditPaymentForm($encodedId)
    {
        // Decode the ID
        $decodedId = base64_decode($encodedId);

        // Find the payment record by the decoded ID
        $payment = CaPaymentHistory::where('id', $decodedId)->firstOrFail();

        // Pass the payment data to the view
        return view('Ca.ca_payment_edit', compact('payment'));
    }
    public function showViewPaymentForm($encodedId)
    {
        // Decode the ID
        $decodedId = base64_decode($encodedId);

        // Find the payment record by the decoded ID
        $payment = CaPaymentHistory::where('id', $decodedId)->firstOrFail();

        // Pass the payment data to the view
        return view('Ca.ca_payment_view', compact('payment'));
    }

    public function fetchCustomers()
    {
        // Step 1: Get the assigned company IDs based on the authenticated user
        $assignedCompanies = DB::table('ca_assigns')
            ->join('company_profiles', 'ca_assigns.comp_id', '=', 'company_profiles.userId')
            ->where('ca_assigns.ca_id', '=', Auth::user()->id)
            ->where('ca_assigns.ca_assign_status', '=', 1)
            ->where('ca_assigns.ca_current_status', '!=', 0)
            ->pluck('ca_assigns.comp_id');  // Get only the comp_id values

        // Step 2: Get all the users data corresponding to the assigned companies
        $customers = DB::table('users')
            ->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
            ->leftJoin('ca_assigns', 'users.id', '=', 'ca_assigns.comp_id')
            ->whereIn('users.id', $assignedCompanies)
            ->where('users.u_type', '=', 2) // Ensure users are of the correct type
            ->where('ca_assigns.ca_assign_status', '=', 1)
            ->where('ca_assigns.ca_current_status', '!=', 0)
            ->get(['users.id', 'users.name']); // Adjust this as per the required fields

        return response()->json($customers);
    }

    public function fetchAgents()
    {
        // Get agents added by the authenticated user
        $agents = DB::table('busi_agents')
            ->where('added_by', '=', Auth::user()->id)
            ->get(['id', 'agent_name']);  // Adjust this as per the required fields

        return response()->json($agents);
    }

     public function addPayment(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'paymentDate' => 'required|date',
            'entityType' => 'required|string',
            'name' => 'nullable|string',
            'paymentPhone' => 'required|string',
            'paymentType' => 'required|string',
            'paymentGovtFees' => 'required|numeric',
            'paymentServiceFees' => 'required|numeric',
            'paymentAmount' => 'required|numeric',
            'paymentMethod' => 'required|string',
            'paymentPurpose' => 'required|string',
        ]);

        // Determine the customer or agent based on entityType
        $customerId = null;
        $agentId = null;

        if ($request->entityType == 'company') {
            $customerId = $request->customerSelect; // Assuming you have customer name data, adjust accordingly
        } elseif ($request->entityType == 'agent') {
            $agentId = $request->agentSelect; // Assuming you have agent name, adjust accordingly
        }



        // Save the payment history to the database
        CaPaymentHistory::create([
            'payment_date' => $request->paymentDate,
            'entity_type' => $request->entityType,
            'name' => $request->name,
            'customer_id' => $customerId,
            'agent_id' => $agentId,
            'payment_phone' => $request->paymentPhone,
            'payment_type' => $request->paymentType,
            'gov_fees' => $request->paymentGovtFees,
            'service_fees' => $request->paymentServiceFees,
            'total_amount' => $request->paymentAmount,
            'payment_method' => $request->paymentMethod,
            'payment_purpose' => $request->paymentPurpose,
            'added_by' => Auth::user()->id,
        ]);

        return response()->json(['success' => 'Payment added successfully', 'redirect_url' => route('ca.PaymentHistory')]);
    }

    public function updatePayment(Request $request)
    {
        // Validate incoming request
        // $request->validate([
        //     'paymentDate' => 'required|date',
        //     'entityType' => 'required|string',
        //     'paymentPhone' => 'required|string',
        //     'paymentType' => 'required|string',
        //     'paymentAmount' => 'required|numeric',
        //     'paymentMethod' => 'required|string',
        //     'paymentPurpose' => 'required|string',
        //     'pay_id' => 'required|exists:ca_payment_history,id', // Validate the ID exists in the table
        // ]);

        // Find the payment record by id
        $payment = CaPaymentHistory::findOrFail($request->pay_id);
		
		// clone old data BEFORE update
		$oldData = $payment->getOriginal();

        // Update the payment record
        $payment->payment_date = $request->paymentDate;
        $payment->entity_type = $request->entityType;
        $payment->name = $request->name;
        $payment->customer_id = $request->entityType == 'company' ? $request->customerSelect : null;
        $payment->agent_id = $request->entityType == 'agent' ? $request->agentSelect : null;
        $payment->payment_phone = $request->paymentPhone;
        $payment->payment_type = $request->paymentType;
        $payment->gov_fees = $request->paymentGovtFees;
        $payment->service_fees = $request->paymentServiceFees;
        $payment->total_amount = $request->paymentAmount;
        $payment->payment_method = $request->paymentMethod;
        $payment->payment_purpose = $request->paymentPurpose;
        $payment->save(); // Save the updated record

		$newData = $payment->getAttributes();
		$changedOld = [];
		$changedNew = [];

		$logFields = [
			'payment_date',
			'entity_type',
			'name',
			'customer_id',
			'agent_id',
			'payment_phone',
			'payment_type',
			'gov_fees',
			'service_fees',
			'total_amount',
			'payment_method',
			'payment_purpose'
		];

		foreach ($logFields as $field) {

			$old = $oldData[$field] ?? null;
			$new = $newData[$field] ?? null;

			// ⚠ Fix date-format false changes
			if ($field === 'payment_date') {
				$old = \Carbon\Carbon::parse($old)->format('Y-m-d H:i');
				$new = \Carbon\Carbon::parse($new)->format('Y-m-d H:i');
			}

			if ((string) $old !== (string) $new) {
				$label = ucwords(str_replace('_', ' ', $field));
				$changedOld[$label] = $old;
				$changedNew[$label] = $new;
			}
		}
		
		if (!empty($changedNew)) {
			AuditLogger::logEntry(
				action: 'update',
				module: 'Payment Management',
				description: 'Payment details updated',
				oldData: $changedOld,
				newData: $changedNew
			);
		}

        // Return success response
        return response()->json(['success' => 'Payment updated successfully.', 'redirect_url' => route('ca.PaymentHistory')]);
    }
	
	public function deletePayment(Request $request)
	{
		// Find payment
		$payment = CaPaymentHistory::findOrFail(base64_decode($request->pay_id));

		// Capture required OLD data for log (keep minimal)
		$oldData = [
			'Payment Date'   => $payment->payment_date,
			'Payment Type' => $payment->payment_type,
			'Payment Method' => $payment->payment_method,
			'Total Amount'   => $payment->total_amount,
			'Purpose'        => $payment->payment_purpose,
		];

		// Delete payment
		$deleted = $payment->delete();

		if ($deleted) {
			// 🔐 AUDIT LOG ENTRY
			AuditLogger::logEntry(
				action: 'delete',
				module: 'Payment Management',
				description: 'Payment deleted',
				oldData: $oldData,
				newData: null
			);

			return response()->json([
				'status' => 'success',
				'message' => 'Payment deleted successfully.',
				'redirect_url' => route('ca.PaymentHistory')
			]);
		}

		return response()->json([
			'status' => 'error',
			'message' => 'Failed to delete payment.'
		], 500);
	}


    public function saveRecurringTasks(Request $request)
    {
        $taskNames = $request->input('task_name');
        $govFees = $request->input('gov_fee');
        $serviceCharges = $request->input('service_charge');

        if ($taskNames && is_array($taskNames)) {
            foreach ($taskNames as $index => $name) {
                DB::table('ca_recurring_amount')->insert([
                    'added_by' => Auth::user()->id,
                    'task_name' => $name,
                    'gov_fee' => $govFees[$index],
                    'service_charge' => $serviceCharges[$index],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function deleteRecurringTask($id)
    {
        $deleted = DB::table('ca_recurring_amount')->where('id', $id)->delete();

        return response()->json([
            'success' => $deleted ? true : false
        ]);
    }
}
