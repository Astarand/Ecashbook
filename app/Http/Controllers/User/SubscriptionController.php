<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Helpers\AuditLogger;

class SubscriptionController extends Controller
{
    private $razorpayService;

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    public function Plans()
    {
        // Fetch subscription plans for userId 1 or 3
		$userId = currentOwnerId();
		$uType = Auth::user()->u_type;
		if($uType == 5){
			checkCoreAccess('Plans');
		}
        $plans = DB::table('subscription_plans')
            ->whereIn('userId', [1, 3])
            ->get();

        // Fetch features for these plans
        $planIds = $plans->pluck('id');
        $features = DB::table('subscription_plan_features as spf')
			->leftJoin('menu_features as mf', 'spf.feature_id', '=', 'mf.id')
            ->whereIn('spf.subscription_plans_id', $planIds)
            ->select(
				'spf.*',
				'mf.code as name'
			)
			->get()
			->groupBy('subscription_plans_id');

        // Attach features to each plan
        $plans->transform(function ($plan) use ($features) {
            $plan->features = $features[$plan->id] ?? [];
            return $plan;
        });

        // Check if user has active subscription
        $activeSubscription = null;
        if (Auth::check()) {
            $activeSubscription = DB::table('subscribers')
                ->leftJoin('subscription_plans', 'subscribers.pid', '=', 'subscription_plans.id')
                ->where('subscribers.uid', $userId)
                ->where('subscribers.status', 'active')
                ->where('subscribers.end_at', '>', now())
                ->select(
                    'subscribers.*',
                    'subscription_plans.monthly_price',
                    'subscription_plans.yearly_price',
                    'subscription_plans.title'
                )
                ->latest('subscribers.created_at')
                ->first();
        }
		//echo "<pre>";print_r($activeSubscription);exit;
        return view('User.Plans', compact('plans', 'activeSubscription'));
    }

    public function createOrder(Request $request)
    {
		$userId = currentOwnerId();
        try {
            $planId = $request->plan_id;
            $totalAmount = $request->amount; // This now includes GST from frontend
            $amountInPaise = $totalAmount * 100; // Convert to paise
            $billingType = $request->billing_type;

            // Generate unique receipt ID
            $receipt = 'sub_' . time() . '_' . $planId . '_' . $userId;

            // Create Razorpay order
            $orderResult = $this->razorpayService->createOrder($amountInPaise, 'INR', $receipt);



            if ($orderResult['success']) {
                return response()->json([
                    'success' => true,
                    'order' => $orderResult['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $orderResult['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order'
            ]);
        }
    }

    public function verifyPayment(Request $request)
    {
		$userId = currentOwnerId();
        try {
            $paymentId = $request->razorpay_payment_id;
            $orderId = $request->razorpay_order_id;
            $signature = $request->razorpay_signature;
            $planId = $request->plan_id;
            $totalAmount = $request->amount; // Total amount including GST
            $billingType = $request->billing_type;

            // Log the received data for debugging
            Log::info('Payment verification started', [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'signature' => $signature,
                'plan_id' => $planId,
                'amount' => $totalAmount,
                'user_id' => $userId
            ]);

            // Get current active subscription for money adjustment calculation
            $currentSubscription = DB::table('subscribers')
                ->leftJoin('subscription_plans', 'subscribers.pid', '=', 'subscription_plans.id')
                ->where('subscribers.uid', $userId)
                ->where('subscribers.status', 'active')
                ->where('subscribers.end_at', '>', now())
                ->select(
                    'subscribers.*',
                    'subscription_plans.monthly_price',
                    'subscription_plans.yearly_price'
                )
                ->latest('subscribers.created_at')
                ->first();

            // Calculate money adjustment
            $adjustmentAmount = $this->calculateMoneyAdjustment($currentSubscription, $planId, $billingType);

            // Calculate GST breakdown (totalAmount already includes adjustment)
            $adjustedTotal = $totalAmount + $adjustmentAmount; // Add back adjustment to get original subtotal
            $baseAmount = $adjustedTotal / 1.18; // Reverse calculate base amount
            $gstAmount = $adjustedTotal - $baseAmount;
			
			//Calculate CA commision
			$caAssign = DB::table('ca_assigns')
				->where('comp_id', $userId)
				->where('ca_assign_status', 1)
				->where('ca_current_status', 1)
				->first();

			$caId = $caAssign ? $caAssign->ca_id : 0;
			$customPercentage = (float) DB::table('ca_profiles')
					->where('userId', $caId)
					->value('subs_percentage');

			$planPercentage = (float) DB::table('subscription_plans')
				->where('id', $planId)
				->value('ca_percentage');

			$finalPercentage = $customPercentage > 0 ? $customPercentage : $planPercentage;
			$caAmount = 0;
			if ($caId && $finalPercentage > 0) {
				$caAmount = round(($totalAmount * $finalPercentage) / 100, 2);
			}

            // Verify signature
            $isSignatureValid = $this->razorpayService->verifySignature($orderId, $paymentId, $signature);

            Log::info('Signature verification result', [
                'is_valid' => $isSignatureValid,
                'payment_id' => $paymentId,
                'order_id' => $orderId
            ]);

            if ($isSignatureValid) {
                // Get payment details from Razorpay
                $paymentDetails = $this->razorpayService->getPaymentDetails($paymentId);

                Log::info('Payment details fetch result', [
                    'success' => $paymentDetails['success'],
                    'payment_id' => $paymentId
                ]);

                if (!$paymentDetails['success']) {
                    Log::error('Failed to fetch payment details', [
                        'payment_id' => $paymentId,
                        'error' => $paymentDetails['message'] ?? 'Unknown error'
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to fetch payment details: ' . ($paymentDetails['message'] ?? 'Unknown error')
                    ]);
                }

                $payment = $paymentDetails['data'];

                // Calculate subscription dates
                $startDate = Carbon::now();
                $endDate = $billingType === 'yearly'
                    ? $startDate->copy()->addDays(365)
                    : $startDate->copy()->addDays(28);

                // Deactivate current subscription if exists
                if ($currentSubscription) {
                    DB::table('subscribers')
                        ->where('id', $currentSubscription->id)
                        ->update([
                            'status' => 'upgraded',
                            'updated_at' => now()
                        ]);
                }

                // Save subscription to database
                $subscriptionId = DB::table('subscribers')->insertGetId([
                    'uid' => $userId,
                    'utype' => Auth::user()->u_type ?? 'user',
                    'pid' => $planId,
                    'plan_type' => $billingType,
                    'paid_amount' => $totalAmount,
                    'base_amount' => round($baseAmount, 2),
                    'gst_amount' => round($gstAmount, 2),
                    'gst_percentage' => 18.00,
                    'adjustment_amount' => round($adjustmentAmount, 2),
					'caId' => $caId,
					'ca_amt' => $caAmount,
                    'start_at' => $startDate,
                    'end_at' => $endDate,
                    'status' => 'active',
                    'transaction_id' => $paymentId,
                    'merchantTransactionId' => $orderId,
                    'payment_status' => 'success',
                    'response_msg' => 'Payment successful - ' . ($payment['method'] ?? 'razorpay'),
                    'providerReferenceId' => $paymentId,
                    'merchantOrderId' => $orderId,
                    'checksum' => $signature,
                    'paymentInstrument' => $payment['method'] ?? 'razorpay',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info('Subscription created successfully', [
                    'subscription_id' => $subscriptionId,
                    'user_id' => $userId,
                    'plan_id' => $planId,
                    'payment_id' => $paymentId
                ]);
				
				//Log entry
				AuditLogger::logEntry(
					action: 'create',
					module: 'subscription',
					description: 'Subscription created successfully',
					oldData: null,
					newData: [
						'subscription_id' => $subscriptionId,
						'user_id' => $userId,
						'plan_id' => $planId,
						'plan_type' => $billingType,
						'paid_amount' => $totalAmount,
						'payment_status' => 'success',
						'transaction_id' => $paymentId,
						'provider' => 'razorpay',
						'start_at' => $startDate,
						'end_at' => $endDate,
						'status' => 'active'
					]
				);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment verified and subscription activated',
                    'subscription_id' => $subscriptionId
                ]);
            } else {
                Log::warning('Payment signature verification failed', [
                    'payment_id' => $paymentId,
                    'order_id' => $orderId,
                    'signature' => $signature,
                    'user_id' => $userId,
                    'razorpay_key' => config('services.razorpay.key'),
                    'secret_length' => strlen(config('services.razorpay.secret'))
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed. Please contact support.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage(), [
                'payment_id' => $request->razorpay_payment_id ?? null,
                'user_id' => $userId,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }

    // Temporary test method to bypass signature verification for debugging
    public function testVerifyPayment(Request $request)
    {
		$userId = currentOwnerId();
        try {
            $paymentId = $request->razorpay_payment_id;
            $orderId = $request->razorpay_order_id;
            $signature = $request->razorpay_signature;
            $planId = $request->plan_id;
            $totalAmount = $request->amount;
            $billingType = $request->billing_type;

            Log::info('TEST: Payment verification (bypassing signature)', [
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'user_id' => $userId
            ]);

            // Get current active subscription for money adjustment calculation
            $currentSubscription = DB::table('subscribers')
                ->leftJoin('subscription_plans', 'subscribers.pid', '=', 'subscription_plans.id')
                ->where('subscribers.uid', $userId)
                ->where('subscribers.status', 'active')
                ->where('subscribers.end_at', '>', now())
                ->select(
                    'subscribers.*',
                    'subscription_plans.monthly_price',
                    'subscription_plans.yearly_price'
                )
                ->latest('subscribers.created_at')
                ->first();

            // Calculate money adjustment
            $adjustmentAmount = $this->calculateMoneyAdjustment($currentSubscription, $planId, $billingType);

            // Calculate GST breakdown (totalAmount already includes adjustment)
            $adjustedTotal = $totalAmount + $adjustmentAmount; // Add back adjustment to get original subtotal
            $baseAmount = $adjustedTotal / 1.18;
            $gstAmount = $adjustedTotal - $baseAmount;
			
			//Calculate CA commision
			$caAssign = DB::table('ca_assigns')
				->where('comp_id', $userId)
				->where('ca_assign_status', 1)
				->where('ca_current_status', 1)
				->first();

			$caId = $caAssign ? $caAssign->ca_id : 0;
			$customPercentage = (float) DB::table('ca_profiles')
					->where('userId', $caId)
					->value('subs_percentage');

			$planPercentage = (float) DB::table('subscription_plans')
				->where('id', $planId)
				->value('ca_percentage');

			$finalPercentage = $customPercentage > 0 ? $customPercentage : $planPercentage;
			$caAmount = 0;
			if ($caId && $finalPercentage > 0) {
				$caAmount = round(($totalAmount * $finalPercentage) / 100, 2);
			}

            // Calculate subscription dates
            $startDate = Carbon::now();
            $endDate = $billingType === 'yearly'
                ? $startDate->copy()->addDays(365)
                : $startDate->copy()->addDays(28);

            // Deactivate current subscription if exists
            if ($currentSubscription) {
                DB::table('subscribers')
                    ->where('id', $currentSubscription->id)
                    ->update([
                        'status' => 'upgraded',
                        'updated_at' => now()
                    ]);
            }

            // Save subscription to database
            $subscriptionId = DB::table('subscribers')->insertGetId([
                'uid' => $userId,
                'utype' => Auth::user()->u_type ?? 'user',
                'pid' => $planId,
                'plan_type' => $billingType,
                'paid_amount' => $totalAmount,
                'base_amount' => round($baseAmount, 2),
                'gst_amount' => round($gstAmount, 2),
                'gst_percentage' => 18.00,
                'adjustment_amount' => round($adjustmentAmount, 2),
				'caId' => $caId,
				'ca_amt' => $caAmount,
                'start_at' => $startDate,
                'end_at' => $endDate,
                'status' => 'active',
                'transaction_id' => $paymentId,
                'merchantTransactionId' => $orderId,
                'payment_status' => 'success',
                'response_msg' => 'TEST: Payment successful - bypassed signature verification',
                'providerReferenceId' => $paymentId,
                'merchantOrderId' => $orderId,
                'checksum' => $signature,
                'paymentInstrument' => 'razorpay',
                'created_at' => now(),
                'updated_at' => now()
            ]);
			
			//Log entry
			AuditLogger::logEntry(
				action: 'create',
				module: 'subscription',
				description: 'Subscription created successfully',
				oldData: null,
				newData: [
					'subscription_id' => $subscriptionId,
					'user_id' => $userId,
					'plan_id' => $planId,
					'plan_type' => $billingType,
					'paid_amount' => $totalAmount,
					'payment_status' => 'success',
					'transaction_id' => $paymentId,
					'provider' => 'razorpay',
					'start_at' => $startDate,
					'end_at' => $endDate,
					'status' => 'active'
				]
			);

            return response()->json([
                'success' => true,
                'message' => 'TEST: Payment verified and subscription activated (bypassed signature)',
                'subscription_id' => $subscriptionId
            ]);

        } catch (\Exception $e) {
            Log::error('TEST: Payment verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'TEST: Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }

    public function subscriptionHistory()
    {
		$userId = currentOwnerId();
        $subscriptions = DB::table('subscribers')
            ->leftJoin('subscription_plans', 'subscribers.pid', '=', 'subscription_plans.id')
            ->where('subscribers.uid', $userId)
            ->select(
                'subscribers.*',
                'subscription_plans.title as plan_title',
                'subscription_plans.monthly_price',
                'subscription_plans.yearly_price'
            )
            ->orderBy('subscribers.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions
        ]);
    }

    // Calculate money adjustment for plan upgrades
    private function calculateMoneyAdjustment($currentSubscription, $newPlanId, $newBillingType)
    {
        if (!$currentSubscription) {
            return 0; // No active subscription, no adjustment
        }

        // Get new plan details
        $newPlan = DB::table('subscription_plans')->where('id', $newPlanId)->first();
        if (!$newPlan) {
            return 0;
        }

        // Get current and new plan amounts
        $currentPlanAmount = $currentSubscription->plan_type === 'monthly'
            ? (float)$currentSubscription->monthly_price
            : (float)$currentSubscription->yearly_price;

        $newPlanAmount = $newBillingType === 'monthly'
            ? (float)$newPlan->monthly_price
            : (float)$newPlan->yearly_price;

        // Only apply adjustment for upgrades (new plan costs more)
        if ($newPlanAmount <= $currentPlanAmount) {
            return 0; // Not an upgrade, no adjustment
        }

        // Calculate remaining days
        $currentDate = Carbon::now();
        $endDate = Carbon::parse($currentSubscription->end_at);
        $remainingDays = max(0, $endDate->diffInDays($currentDate));

        if ($remainingDays <= 0) {
            return 0; // Subscription expired, no adjustment
        }

        // Calculate daily rate of current plan
        $totalDaysInCurrentPlan = $currentSubscription->plan_type === 'monthly' ? 28 : 365;
        $dailyRate = $currentPlanAmount / $totalDaysInCurrentPlan;

        // Calculate adjustment amount (money back for unused days)
        $adjustmentAmount = $dailyRate * $remainingDays;

        Log::info('Money Adjustment Calculation', [
            'current_plan_amount' => $currentPlanAmount,
            'new_plan_amount' => $newPlanAmount,
            'remaining_days' => $remainingDays,
            'daily_rate' => $dailyRate,
            'adjustment_amount' => $adjustmentAmount,
            'current_plan_type' => $currentSubscription->plan_type,
            'total_days_in_current_plan' => $totalDaysInCurrentPlan
        ]);

        return $adjustmentAmount;
    }

    // Debug method to check configuration
    public function debugConfig()
    {
		$userId = currentOwnerId();
        return response()->json([
            'razorpay_key' => config('services.razorpay.key'),
            'secret_length' => strlen(config('services.razorpay.secret')),
            'verify_ssl' => config('services.razorpay.verify_ssl'),
            'app_env' => config('app.env'),
            'user_id' => $userId,
            'user_type' => Auth::user()->user_type ?? 'user'
        ]);
    }
}
