<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckSubscriptionPopup
{
    public function handle($request, Closure $next)
    {
        $popupData = null;

        if (Auth::check()) {
            $uid = Auth::id();

            $subscription = DB::table('subscribers')
                ->where('uid', $uid)
                ->where('payment_status', 'success')
                ->orderByDesc('end_at')
                ->first();

            if ($subscription) {
                $today = Carbon::today();
                $endAt = Carbon::parse($subscription->end_at);
                $daysLeft = $today->diffInDays($endAt, false);

                if ($daysLeft < 0) {
                    // Expired
                    $popupData = [
                        'type' => 'expired',
                        'message' => 'Your subscription has expired. Please renew to continue services.'
                    ];
                } elseif ($daysLeft <= 3) {
                    // Expiring in 3 days
                    $popupData = [
                        'type' => 'warning',
                        'message' => "Your subscription will expire in {$daysLeft} day(s). Please renew soon."
                    ];
                }
            }
			// ---------- TRIAL, later remove else part ----------
			else 
			{

				// ✅ Fixed trial date
				$trialEnd = Carbon::create(2026, 8, 31);

				$daysLeft = $today->diffInDays($trialEnd, false);

				if ($daysLeft < 0) {
					$popupData = [
						'type' => 'expired',
						'message' => 'Your free trial has expired. Please subscribe to continue.'
					];
				} elseif ($daysLeft <= 3) {
					$popupData = [
						'type' => 'warning',
						'message' => "Your free trial will expire in {$daysLeft} day(s)."
					];
				} else {
					// Optional: show info message
					$popupData = [
						'type' => 'info',
						'message' => "Free trial till " . $trialEnd->format('jS F Y')
					];
				}
			}
        }

        view()->share('subscriptionPopup', $popupData);

        return $next($request);
    }
}
