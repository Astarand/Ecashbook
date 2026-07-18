<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Symfony\Component\HttpFoundation\Response;

class LoadUserFeatures
{

	public function handle(Request $request, Closure $next): Response
	{
		if (auth()->check()) {

			$authUser = Auth::user();
			
			//Only apply subscription system for u_type 2 and 5
			if (!in_array($authUser->u_type, [2, 5])) {
				view()->share('userFeatures', ['ALL']);
				view()->share('accessType', 'paid');
				view()->share('trialDaysLeft', null);
				return $next($request);
			}

			// Trial must be checked on logged user
			$trialUserId = $authUser->id;

			// Subscription can be parent
			$subscriptionUserId = $authUser->u_type == 2 
									? $authUser->id 
									: $authUser->user_add_by;

			$features = [];
			$trialDaysLeft = null;
			$accessType = 'expired';

			// Trial info (REAL USER)
			$trialUser = DB::table('users')
				->select('trial_start_at', 'trial_days')
				->where('id', $subscriptionUserId)
				->first();

			// Active paid plan (PARENT LOGIC)
			$hasActivePlan = DB::table('subscribers')
				->where('uid', $subscriptionUserId)
				->where('status', 'active')
				->where('end_at', '>', now())
				->exists();

			// ---------- PAID ----------
			if ($hasActivePlan) {

				$features = DB::table('subscribers as s')
					->join('subscription_plan_features as spf', 's.pid', '=', 'spf.subscription_plans_id')
					->join('menu_features as mf', 'spf.feature_id', '=', 'mf.id')
					->where('s.uid', $subscriptionUserId)
					->where('s.status', 'active')
					->where('s.end_at', '>', now())
					->pluck('mf.code')
					->unique()
					->toArray();

				$accessType = 'paid';
			}

			// ---------- TRIAL ----------
			//elseif ($trialUser && $trialUser->trial_start_at) { //later open
			elseif ($trialUser) {  //31st May 2026 (Fixed trial end date), later removed

				//$trialEnd = Carbon::parse($trialUser->trial_start_at)->addDays($trialUser->trial_days); //later open
				  $trialEnd = Carbon::create(2026, 8, 31); // 31st August 2026 (Fixed trial end date), later removed

				if (now()->lte($trialEnd)) {
					$features = ['ALL'];
					$trialDaysLeft = now()->diffInDays($trialEnd);
					$accessType = 'trial';
				} else {
					$features = ['plans'];
					$accessType = 'expired';
				}
			}

			// ---------- NO PLAN ----------
			else {
				$features = ['plans'];
				$accessType = 'expired';
			}

			view()->share('userFeatures', $features);
			view()->share('trialDaysLeft', $trialDaysLeft);
			view()->share('accessType', $accessType);
		}

		return $next($request);
	}

}
