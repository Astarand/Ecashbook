<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\subscription_plans;
use App\Models\SubscriptionPlanFeature;
use App\Models\Coupons;
use App\Models\Plans;
use App\Models\CouponPlan;
use App\Models\CouponPlanType;
use App\Models\CouponPlanTypeFeature;
use App\Models\CouponPlanTypeFeatureValue;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Redirect;
use DB;
use Auth;
use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class SubscriptionManagement extends Controller
{
    public function SubscriptionList()
    {
		if (Auth::user() && (Auth::user()->u_type == 3 || Auth::user()->u_type == 6)) {

			$userId = Auth::user()->u_type == 3 
						? Auth::user()->id 
						: Auth::user()->admin_add_by;

			$getSubscriptionData = DB::table('subscription_plans as sp')
				->leftJoin('subscription_plan_features as f', 'sp.id', '=', 'f.subscription_plans_id')
				->leftJoin('menu_features as mf', 'f.feature_id', '=', 'mf.id') 
				->leftJoin('subscribers as s', function ($join) {
					$join->on('sp.id', '=', 's.pid')
						 ->where('s.payment_status', 'success');
				})
				->where('sp.utype', 3)
				->where('sp.userId', $userId)
				->select(
					'sp.id as plan_id',
					'sp.title',
					'sp.monthly_price',
					'sp.yearly_price',
					'sp.ca_percentage',
					'sp.icon',
					'sp.status',

					'f.id as feature_id',
					'mf.code as feature_name',

					// Aggregates
					DB::raw('COUNT(s.id) as total_subscribers'),
					DB::raw('COALESCE(SUM(s.paid_amount),0) as total_revenue')
				)
				->groupBy(
					'sp.id',
					'sp.title',
					'sp.monthly_price',
					'sp.yearly_price',
					'sp.ca_percentage',
					'sp.icon',
					'sp.status',
					'f.id',
					'mf.code'
				)
				->get();

			// Group features properly (same as your logic)
			$grouped = [];

			foreach ($getSubscriptionData as $row) {
				$planId = $row->plan_id;

				if (!isset($grouped[$planId])) {
					$grouped[$planId] = [
						'id' => $row->plan_id,
						'title' => $row->title,
						'monthly_price' => $row->monthly_price,
						'yearly_price' => $row->yearly_price,
						'ca_percentage' => $row->ca_percentage,
						'icon' => $row->icon,
						'status' => $row->status,
						'total_subscribers' => $row->total_subscribers,
						'total_revenue' => $row->total_revenue,
						'features' => [],
					];
				}

				if ($row->feature_id !== null) {
					$grouped[$planId]['features'][] = [
						'id' => $row->feature_id,
						'name' => $row->feature_name,
					];
				}
			}

			$finalData = array_values($grouped);

			return view('Admin.subscription-list')->with([
				'subscriptions' => $finalData,
				'userId' => $userId
			]);
		}
    }

    public function SubscriptionCreate()
    {
       // echo"fffff";exit;
       // return view('Admin.subscription-create');
	    $menuFeatures = \DB::table('menu_features')->get(); 
		return view('Admin.subscription-create', compact('menuFeatures'));
    }


    public function createPlan(Request $request)
    {
		$userId = Auth::user()->u_type == 3 ? Auth::user()->id : Auth::user()->admin_add_by;
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'monthly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'ca_percentage' => 'required|numeric',
            'icon' => 'required|string',
            'features' => 'array',
            //'features.*.name' => 'required|string',
            'features.*.feature_id' => 'required',
            'features.*.is_enabled' => 'required|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        $plan = subscription_plans::create([
            'userId' => $userId,
            'utype' => 3, // Or use logic
            'title' => $validated['title'],
            'monthly_price' => $validated['monthly_price'],
            'yearly_price' => $validated['yearly_price'],
            'ca_percentage' => $validated['ca_percentage'],
            'icon' => $validated['icon'],
            'status' => $request->has('is_active') ? 1 : 0,
        ]);

        foreach ($request->input('features', []) as $feature) {
            $plan->features()->create($feature);
        }

        return response()->json(['success' => true,'redirect' => url('/subscription-list'), 'message' => 'Plan created successfully.']);
    }

   public function save_plan(Request $request)
    {
        // This will throw a ValidationException automatically if validation fails
       // $this->valdateSubscriptionCreate($request);

        $insertPlan =  $this->createPlan($request);
        $cId = DB::getPdo()->lastInsertId();

        if ($insertPlan) {
            return response()->json([
                'status' => 'success',
                'class' => 'succ',
                'redirect' => url('/subscription-list'),
                'message' => 'Plans added successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'class' => 'err',
                'redirect' => url('/'),
                'message' => 'Plans add failed'
            ]);
        }
    }


    public function ViewPlan($id)
    {
        $id = base64_decode($id);
        $plan = subscription_plans::with('features')->findOrFail($id);
		$menuFeatures = DB::table('menu_features')->get();
        return view('Admin.view-plan', compact('plan','menuFeatures'));
    }

    public function EditPlan($id)
    {
        $id = base64_decode($id);
        $plan = subscription_plans::with('features')->findOrFail($id);
		$menuFeatures = DB::table('menu_features')->get(); 
        // echo "<pre>"; print_r($plan);exit;
        return view('Admin.edit-plan', compact('plan','menuFeatures'));
    }

    public function updateplan(Request $request, $id)
        {
            //echo "<pre>"; print_r($_POST);exit;
            $request->validate([
                'title' => 'required',
                'monthly_price' => 'required|numeric',
                'yearly_price' => 'required|numeric',
                'ca_percentage' => 'required|numeric',
                'icon' => 'required|string',
                'features' => 'array',
            ]);

            $plan = subscription_plans::findOrFail($id);
            $plan->update([
                'title' => $request->title,
                'monthly_price' => $request->monthly_price,
                'yearly_price' => $request->yearly_price,
                'ca_percentage' => $request->ca_percentage,
                'icon' => $request->icon,
                'status' => $request->has('is_active') ? 1 : 0,
            ]);

            // Sync Features
            $existingIds = [];
            foreach ($request->features as $featureData) {
                if (!empty($featureData['id'])) {
                    $feature = SubscriptionPlanFeature::find($featureData['id']);
                    if ($feature) {
                        $feature->update([
                            //'name' => $featureData['name'],
                            'feature_id' => $featureData['feature_id'],
                            'is_enabled' => $featureData['is_enabled'],
                        ]);
                        $existingIds[] = $feature->id;
                    }
                } else {
                    $newFeature = $plan->features()->create([
                        //'name' => $featureData['name'],
                        'feature_id' => $featureData['feature_id'],
                        'is_enabled' => $featureData['is_enabled'],
                    ]);
                    $existingIds[] = $newFeature->id;
                }
            }

            // Delete removed features
            $plan->features()->whereNotIn('id', $existingIds)->delete();

            return response()->json(['status' => 'success','redirect' => url('/subscription-list'), 'message' => 'Plan updated successfully']);
        }



    public function toggleStatus(Request $request)
    {
        $plan = subscription_plans::find($request->id);
        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found']);
        }

        $plan->status = !$plan->status;
        $plan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully',
            'new_status' => $plan->status ? 'Active' : 'Inactive',
            'class' => $plan->status ? 'btn-success' : 'btn-outline-secondary'
        ]);
    }

    public function CuponCode()
    {
        //echo"fffdffd";exit;
                $coupons = DB::table('coupons')
                ->select(
                    'coupons.id',
                    'coupons.code',
                    'coupons.discount',
                    'coupons.type',
                    'coupons.valid_from',
                    'coupons.valid_until',
                    'coupons.description',
                    'coupons.is_active',
                    'coupons.created_at',
                    'coupons.updated_at',
                    DB::raw('GROUP_CONCAT(subscription_plans.title) AS plan_titles')
                )
                ->leftJoin('coupon_plan', 'coupons.id', '=', 'coupon_plan.coupon_id')
                ->leftJoin('subscription_plans', 'coupon_plan.plan_id', '=', 'subscription_plans.id')
                ->groupBy(
                    'coupons.id',
                    'coupons.code',
                    'coupons.discount',
                    'coupons.type',
                    'coupons.valid_from',
                    'coupons.valid_until',
                    'coupons.description',
                    'coupons.is_active',
                    'coupons.created_at',
                    'coupons.updated_at'
                )
                ->get();



           // echo "<pre>"; print_r($coupons);exit;
        return view('Admin.cupon-codes', compact('coupons'));
    }

    public function saveCoupon(Request $request)
        {

            // Manual validation
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|unique:coupons,code',
                'discount' => 'required|numeric|min:0',
                'type' => 'required|in:percentage,fixed',
                'valid_from' => 'required|date',
                'valid_until' => 'required|date|after_or_equal:valid_from',
                'plans' => 'required|array',
                'plans.*' => 'exists:subscription_plans,id',
                'plan_types' => 'array',
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            try {
                DB::beginTransaction();

                $coupon = Coupons::create([
                    'code' => $request->code,
                    'discount' => $request->discount,
                    'type' => $request->type,
                    'valid_from' => $request->valid_from,
                    'valid_until' => $request->valid_until,
                    'description' => $request->description,
                    'is_active' => $request->has('is_active'),
                ]);

                foreach ($request->plans as $planId) {
                    DB::table('coupon_plan')->insert([
                        'coupon_id' => $coupon->id,
                        'plan_id' => $planId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Coupon created successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

    public function editCoupon($id)
    {
        $coupon = Coupons::with('subscription_plans')->findOrFail($id);

        return response()->json([
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'type' => $coupon->type,
            'valid_from' => Carbon::parse($coupon->valid_from)->format('Y-m-d'),
            'valid_until' => Carbon::parse($coupon->valid_until)->format('Y-m-d'),
            'description' => $coupon->description,
            'applied_plan_ids' => $coupon->subscription_plans->pluck('id')->toArray(),
            'all_plans' => subscription_plans::select('id', 'title')->get(),
        ]);
    }


    public function updateCoupon(Request $request, $id)
    {
        $coupon = Coupons::findOrFail($id);



        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            //'plans' => 'required|array',
            'plans.*' => 'exists:subscription_plans,id',
            'plan_types' => 'array',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $coupon->update([
                'code' => $request->code,
                'discount' => $request->discount,
                'type' => $request->type,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);

            // Sync plans (detaches removed ones, attaches new)
            //$coupon->coupon_plan()->sync($request->id);
            $coupon->subscription_plans()->sync($request->plans ?? []);
            //print_r($request->id);exit;

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Coupon updated successfully',
                'redirect' => url('/coupon-codes'), // or any route you want to go to
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function viewCoupon($id)
    {
        $coupon = Coupons::with('subscription_plans')->findOrFail($id);

        return response()->json([
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'type' => $coupon->type,
            'valid_from' => Carbon::parse($coupon->valid_from)->format('Y-m-d'),
            'valid_until' => Carbon::parse($coupon->valid_until)->format('Y-m-d'),
            'description' => $coupon->description,
            'applied_plan_ids' => $coupon->subscription_plans->pluck('id')->toArray(),
            'all_plans' => subscription_plans::select('id', 'title')->get(),
        ]);
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupons::find($id);
        if ($coupon) {
            $coupon->delete();
            return response()->json(['success' => true, 'message' => 'Coupon deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Coupon not found.']);
        }
    }


    public function SubscriptionPlanType()
    {
        $planTypes = CouponPlanType::with('features')->get();
        return view('Admin.subscription-plan-type', compact('planTypes'));
    }


    public function SubscriptionCustomerList()
    {
        $customers = DB::table('users')
            ->leftJoin('company_profiles', 'company_profiles.userId', '=', 'users.id')
            ->leftJoin('subscribers', 'subscribers.uid', '=', 'users.id')
            ->where('users.u_type', 2)
            ->where('users.userStatus', 1)
            // Only Active Subscription Plans
            ->where('subscribers.status', 'Active')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',

                // Company Profile Fields
                'company_profiles.*',

                // Subscriber Fields
                'subscribers.id as subscriber_id',
                'subscribers.uid',
                'subscribers.utype',
                'subscribers.pid',
                'subscribers.plan_type',
                'subscribers.paid_amount',
                'subscribers.base_amount',
                'subscribers.gst_amount',
                'subscribers.gst_percentage',
                'subscribers.adjustment_amount',
                'subscribers.start_at',
                'subscribers.end_at',
                'subscribers.caId',
                'subscribers.ca_amt',
                'subscribers.status',
                'subscribers.transaction_id',
                'subscribers.merchantTransactionId',
                'subscribers.payment_status',
                'subscribers.response_msg',
                'subscribers.providerReferenceId',
                'subscribers.merchantOrderId',
                'subscribers.checksum',
                'subscribers.paymentInstrument',
                'subscribers.created_at',
                'subscribers.updated_at'
            )
            ->orderBy('users.id', 'DESC')
            ->get();

            // echo "<pre>"; print_r($customers);exit;
        return view('Admin.subscription-customer-list', compact('customers'));
    }
    
}
