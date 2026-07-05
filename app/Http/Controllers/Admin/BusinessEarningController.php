<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Ca_profiles;
use Validator;
use Redirect;
use DB;
use Auth;
use Helper; 
use Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class BusinessEarningController extends Controller
{
 	
	public function BusinessEarnings(Request $request)
    {
        $type = $request->type ?? 'customer';
        $search = $request->search;

        if ($type === 'customer') {
            $query = DB::table('subscribers as s')
                ->leftJoin('users as u', 'u.id', '=', 's.uid')
                ->leftJoin('company_profiles as c', 'u.id', '=', 'c.userId')
				->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.pid')
                ->select(
                    's.id',
                    'c.comp_name as name',
					'sp.title as package_name',
                    's.plan_type',
                    's.paid_amount',
                    's.ca_amt',
                    's.start_at',
                    's.end_at',
                    's.transaction_id'
                );
        } else {
            $query = DB::table('subscribers as s')
                ->leftJoin('users as u', 'u.id', '=', 's.caId')
                ->leftJoin('ca_profiles as c', 'u.id', '=', 'c.userId')
				->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.pid')
                ->select(
                    's.id',
                    'c.comp_name as name',
					'sp.title as package_name',
                    's.plan_type',
                    's.paid_amount',
                    's.ca_amt',
                    's.start_at',
                    's.end_at',
                    's.transaction_id'
                );
        }

        // Search Logic
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('plan_type', 'like', "%$search%")
                  ->orWhere('transaction_id', 'like', "%$search%");
            });
        }

        // Pagination
        $data = $query->orderBy('id', 'desc')->paginate(10);

        // AJAX Request
        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.subscription-table', compact('data'))->render(),
                'pagination' => view('partials.pagination', compact('data'))->render()
            ]);
        }

        return view('Admin.business-earnings', compact('data'));
    }
	
	public function getStats(Request $request)
    {
        $timeRange = $request->input('timeRange');  // weekly / monthly / yearly exit;
		
		$week = $this->getDateRange('weekly');
		$month = $this->getDateRange('monthly');
		$year = $this->getDateRange('yearly');

		/*print_r($week);
		print_r($month);
		print_r($year);exit;*/
		$range = $this->getDateRange($request->timeRange);
		$fromDate = $range['fromDate'];
		$toDate   = $range['toDate'];

		// Total Companies (u_type = 2)
		$totalCompanies = DB::table('users')
							->where('u_type', 2)
							->whereBetween('created_at', [$fromDate, $toDate])
							->count();
		// Total CA (u_type = 1)
		$totalCAs = User::where('u_type', 1)
						->whereBetween('created_at', [$fromDate, $toDate])
						->count();
		$directAttachments = User::where('u_type', 2)
							->whereNotNull('admin_add_by')
							->whereBetween('created_at', [$fromDate, $toDate])
							->count();
		$attachedByCA = User::where('u_type', 2)
							->whereNotNull('ca_add_by')
							->whereBetween('created_at', [$fromDate, $toDate])
							->count();
			
		$totalSubscribers = DB::table('subscribers')
							->whereBetween('created_at', [$fromDate, $toDate])
							->count();
			
		$totalTrialUsers = DB::table('users as u')
							->where('u.u_type', 2)
							->whereBetween('u.created_at', [$fromDate, $toDate])
							->whereNotExists(function ($query) {
								$query->select(DB::raw(1))
									->from('subscribers as s')
									->whereColumn('s.uid', 'u.id');
							})
							->count();
			
		$totalEarnings = DB::table('subscribers')
							->where('payment_status', 'success')
							->whereBetween('created_at', [$fromDate, $toDate])
							->sum('paid_amount');
							
		$caEarnings = DB::table('subscribers')
							->where('payment_status', 'success')
							->whereBetween('created_at', [$fromDate, $toDate])
							->sum('ca_amt');
		$netProfit = ($totalEarnings - $caEarnings);

        $stats = [
            
            $timeRange => [
                'totalCompanies'   => $totalCompanies,
                'totalCAs'         => $totalCAs,
                'directAttachments'=> $directAttachments,
                'attachedByCA'     => $attachedByCA,
                'totalSubscribers' => $totalSubscribers,
                'totalTrialUsers'  => $totalTrialUsers,
                'totalEarnings'    => $totalEarnings,
                'caEarnings'       => $caEarnings,
                'netProfit'        => $netProfit,
            ],
        ];
		
		//echo "<pre>";print_r($stats);exit;
        // return selected range  
        //return response()->json($stats[$timeRange] ?? []);
        return response()->json($stats ?? []);
    }
	
	public function getSubscriberStats($range)
    {
        switch ($range) {
            case "weekly":
                return $this->weeklyStats();

            case "monthly":
                return $this->monthlyStats();

            case "yearly":
                return $this->yearlyStats();
        }
    }

   
	private function weeklyStats()
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd   = Carbon::now()->endOfWeek();

        // Subscribers
        $subscriber = DB::table('subscribers')
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->groupBy('day')
            ->pluck('total', 'day');

        // Trial Users (users.u_type = 2 and NOT in subscribers)
        $trialUsers = DB::table('users')
            ->leftJoin('subscribers', 'users.id', '=', 'subscribers.uid')
            ->whereNull('subscribers.uid')
            ->where('users.u_type', 2)
            ->whereBetween('users.created_at', [$weekStart, $weekEnd])
            ->select(DB::raw('DAYNAME(users.created_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->pluck('total', 'day');

        $days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];

        return response()->json([
            'labels'      => ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
            'subscriber'  => collect($days)->map(fn($d) => $subscriber[$d] ?? 0),
            'trial_user'  => collect($days)->map(fn($d) => $trialUsers[$d] ?? 0),
        ]);
    }

    /*===========================
      MONTHLY STATS (Current Month)
    ===========================*/
    private function monthlyStats()
    {
        $month = Carbon::now()->month;

        // Subscribers
        $subscriber = DB::table('subscribers')
            ->select(DB::raw('WEEK(created_at) as week'), DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', $month)
            ->groupBy('week')
            ->pluck('total', 'week');

        // Trial Users
        $trialUsers = DB::table('users')
            ->leftJoin('subscribers', 'users.id', '=', 'subscribers.uid')
            ->whereNull('subscribers.uid')
            ->where('users.u_type', 2)
            ->whereMonth('users.created_at', $month)
            ->select(DB::raw('WEEK(users.created_at) as week'), DB::raw('COUNT(*) as total'))
            ->groupBy('week')
            ->pluck('total', 'week');

        return response()->json([
            'labels'     => ["1st Week","2nd Week","3rd Week","4th Week"],
            'subscriber' => [
                $subscriber[1] ?? 0,
                $subscriber[2] ?? 0,
                $subscriber[3] ?? 0,
                $subscriber[4] ?? 0
            ],
            'trial_user' => [
                $trialUsers[1] ?? 0,
                $trialUsers[2] ?? 0,
                $trialUsers[3] ?? 0,
                $trialUsers[4] ?? 0
            ],
        ]);
    }

    /*===========================
      YEARLY STATS (Current Year)
    ===========================*/
    private function yearlyStats()
    {
        $year = Carbon::now()->year;

        // Subscribers
        $subscriber = DB::table('subscribers')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Trial Users
        $trialUsers = DB::table('users')
            ->leftJoin('subscribers', 'users.id', '=', 'subscribers.uid')
            ->whereNull('subscribers.uid')
            ->where('users.u_type', 2)
            ->whereYear('users.created_at', $year)
            ->select(DB::raw('MONTH(users.created_at) as month'), DB::raw('COUNT(*) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        return response()->json([
            'labels'     => ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            'subscriber' => collect(range(1,12))->map(fn($m) => $subscriber[$m] ?? 0),
            'trial_user' => collect(range(1,12))->map(fn($m) => $trialUsers[$m] ?? 0),
        ]);
    }
   
	function getDateRange($range)
	{
		$today = Carbon::today();

		switch ($range) {

			case 'weekly':
				$from = $today->copy()->startOfWeek(); // Monday
				$to   = $today->copy()->endOfWeek();   // Sunday
				break;

			case 'monthly':
				$from = $today->copy()->startOfMonth();
				$to   = $today->copy()->endOfMonth();
				break;

			case 'yearly':
				$from = $today->copy()->startOfYear();
				$to   = $today->copy()->endOfYear();
				break;

			default:
				$from = $today;
				$to   = $today;
		}

		return [
			'fromDate' => $from->format('Y-m-d'),
			'toDate'   => $to->format('Y-m-d')
		];
	}



}
