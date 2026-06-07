<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use Carbon\Carbon;

class EarningManagementController extends Controller
{
    public function EarningDetails()
    {
		$authUser = Auth::user();
		$userId = ($authUser->u_type == 1) ? $authUser->id : $authUser->ca_add_by;

		// Base query
		$baseQuery = DB::table('subscribers as s')
			->leftJoin('users as u', 'u.id', '=', 's.uid')
			->leftJoin('company_profiles as c', 'u.id', '=', 'c.userId')
			->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.pid')
			->leftJoin('ca_assigns as ca', 'ca.comp_id', '=', 'u.id')
			->where('ca.ca_assign_status', 1)
			->where('ca.ca_current_status', 1)
			->where('ca.ca_id', $userId)
			->where('s.caId', $userId);

		// 1. Table Data
		$data = (clone $baseQuery)
			->select(
				's.id',
				'c.comp_name as name',
				'c.comp_email as email',
				'sp.title as package_name',
				's.plan_type',
				's.paid_amount',
				's.ca_amt',
				's.start_at',
				's.end_at',
				's.payment_status',
				's.transaction_id'
			)
			->paginate(10);
			
		$data->getCollection()->transform(function ($row) {
			$row->start_at_fmt = Carbon::parse($row->start_at)->format('d M Y');
			$row->end_at_fmt   = Carbon::parse($row->end_at)->format('d M Y');
			$row->is_expired   = Carbon::parse($row->end_at)->isPast();
			$row->days_left    = Carbon::now()->diffInDays(
									Carbon::parse($row->end_at),
									false
								 ); // negative if expired
			return $row;
		});


		// 2. Total Company Attached
		$totalCompanies = (clone $baseQuery)
			->distinct('u.id')
			->count('u.id');

		// 3. Total Trial Users
		$totalTrial = DB::table('users as u')
					->leftJoin('subscribers as s', 's.uid', '=', 'u.id')
					->leftJoin('ca_assigns as ca', 'ca.comp_id', '=', 'u.id')
					->where('ca.ca_assign_status', 1)
					->where('ca.ca_current_status', 1)
					->where('ca.ca_id', $userId)
					->whereNull('s.id')   //not in subscribers
					->count();

		// 4. Total Subscribers
		$totalSubscribers = (clone $baseQuery)
			->where('s.status', 'active')
			->where('s.payment_status', "success")
			->count();

		// 5. Total Earning
		$totalEarning = (clone $baseQuery)
			->where('s.payment_status', "success")
			->sum('s.ca_amt');

		return view('Ca.earning-management', compact(
			'data',
			'totalCompanies',
			'totalTrial',
			'totalSubscribers',
			'totalEarning'
		));
    }
	
    public function EarningTransaction()
    {
        return view('Ca.earning-transaction');
    }
}
