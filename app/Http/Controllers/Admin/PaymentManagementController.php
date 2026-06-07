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

class PaymentManagementController extends Controller
{
    	
	public function PaymentManagement(Request $request)
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
                    's.transaction_id',
                    's.payment_status'
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
                    's.transaction_id',
                    's.payment_status'
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

        return view('Admin.payment-management', compact('data'));
    }
	
	public function paymentDetails(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$id   = $request->paymentId;
		$type = $request->type ?? 'customer';

		if ($type === 'customer') 
		{

				$row = DB::table('subscribers as s')
					->leftJoin('users as u', 'u.id', '=', 's.uid')
					->leftJoin('company_profiles as c', 'u.id', '=', 'c.userId')
					->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.pid')
					->where('s.id', $id)
					->select(
						'c.comp_name',
						's.plan_type',
						's.paid_amount',
						's.ca_amt',
						's.start_at',
						's.end_at',
						's.transaction_id',
						's.payment_status'
					)
					->first();

				$data = [
					'companyName'         => $row->comp_name,
					'subscriptionTrough'  => 'Direct',
					'packageName'         => $row->plan_type,
					'subscriptionType'    => ucfirst($row->plan_type),
					'amount'              => '₹' . number_format($row->paid_amount, 2),
					'ca_amt'              => '₹' . number_format($row->ca_amt, 2),
					'discount'            => '₹0',
					'startDate'           => date('d-m-Y', strtotime($row->start_at)),
					'endDate'             => date('d-m-Y', strtotime($row->end_at)),
					'transactionId'       => $row->transaction_id,
					'status'              => ucfirst($row->payment_status),
					'statusColor'         => $row->payment_status === 'success' ? 'success' : 'danger',
					'paymentMethod'       => 'Online',
					'paymentDate'         => date('d-m-Y', strtotime($row->start_at)),
				];

		} else {

				$row = DB::table('subscribers as s')
					->leftJoin('users as u', 'u.id', '=', 's.caId')
					->leftJoin('ca_profiles as c', 'u.id', '=', 'c.userId')
					->leftJoin('subscription_plans as sp', 'sp.id', '=', 's.pid')
					->where('s.id', $id)
					->select(
						'c.comp_name',
						's.plan_type',
						's.paid_amount',
						's.ca_amt',
						's.start_at',
						's.end_at',
						's.transaction_id',
						's.payment_status'
					)
					->first();

				$data = [
					'companyName'         => $row->comp_name,
					'subscriptionTrough'  => 'Direct',
					'packageName'         => $row->plan_type,
					'subscriptionType'    => ucfirst($row->plan_type),
					'amount'              => '₹' . number_format($row->paid_amount, 2),
					'ca_amt'              => '₹' . number_format($row->ca_amt, 2),
					'discount'            => '₹0',
					'startDate'           => date('d-m-Y', strtotime($row->start_at)),
					'endDate'             => date('d-m-Y', strtotime($row->end_at)),
					'transactionId'       => $row->transaction_id,
					'status'              => ucfirst($row->payment_status),
					'statusColor'         => $row->payment_status === 'success' ? 'success' : 'danger',
					'paymentMethod'       => 'Online',
					'paymentDate'         => date('d-m-Y', strtotime($row->start_at)),
				];
		}

		return response()->json($data);
	}

}
