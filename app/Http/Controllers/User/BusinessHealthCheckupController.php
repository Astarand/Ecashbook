<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Models\Company_profile_checks;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class BusinessHealthCheckupController extends Controller
{
    public function BusinessHealthCheckup(Request $request)
    {
		//$userId = Auth::user()->id;
		$authUser = Auth::user();
		// If admin and userId is passed
		if (($authUser->u_type == 3 || $authUser->u_type == 6) && $request->has('uid')) {
			$userId = decrypt($request->uid);
		} else {
			$userId = ($authUser->u_type == 2) ? $authUser->id : $authUser->user_add_by;
		}
		$profile = DB::table('company_profiles')
            ->where('userId', $userId)
            ->first();

        return view('User.business-health-checkup', compact('profile'));
    }
	

    public function fetch(Request $request)
    {
        
        try {
			//$userId = Auth::user()->id;
			$authUser = Auth::user();
			if (($authUser->u_type == 3 || $authUser->u_type == 6) && $request->has('uid')) {
				$userId = decrypt($request->uid);
			} else {
				$userId = ($authUser->u_type == 2) ? $authUser->id : $authUser->user_add_by;
			}

			$data = Company_profile_checks::where('userId', $userId)->first();
			//echo "<pre>";print_r($data);exit;
			return response()->json($data ?? []);
		} catch (\Exception $e) {
			return response()->json([
				'error' => true,
				'message' => $e->getMessage()
			], 500);
		}
    }

    public function save(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$user = Auth::user();
		$userId = ($user->u_type == 2) ? $user->id : $user->user_add_by;

		$data = $request->except(['_token']);
		$data['userId'] = $userId;

		// Checkboxes
		$chkFields = [
			'inc_cert_chk','comp_pan_chk','trade_chk','shop_est_chk','ptax_chk',
			'gst_chk','tan_chk','msme_chk','epf_chk','esi_chk','ind_chk',
			'fssai_chk','poll_chk','import_chk','fact_chk','fire_chk'
		];

		foreach ($chkFields as $field) {
			$data[$field] = $request->has($field) ? 1 : 0;
		}

		// Files
		$uploadPath = public_path('uploads/health-checkup');
		// Create folder if not exists
		if (!File::exists($uploadPath)) {
			File::makeDirectory($uploadPath, 0777, true, true);
		}

		$fileFields = [
			'inc_cert_doc','comp_pan_doc','trade_doc','shop_est_doc','ptax_doc',
			'gst_doc','tan_doc','msme_doc','epf_doc','esi_doc','ind_doc',
			'fssai_doc','poll_doc','import_doc','fact_doc','fire_doc'
		];
		
		foreach ($fileFields as $field) {
			if ($request->hasFile($field)) {
				$file = $request->file($field);
				$fileName = time().'_'.$field.'.'.$file->getClientOriginalExtension();
				$file->move($uploadPath, $fileName);
				$data[$field] = 'uploads/health-checkup/'.$fileName;
			} else {
				unset($data[$field]);
			}
		}

		// Check if 3 months completed
		$record = Company_profile_checks::where('userId', $userId)->first();
		$currentYear = now()->year;
		if ($record) {
			$lastAppliedDate = \Carbon\Carbon::parse($record->created_at);
			$nextAllowedDate = $lastAppliedDate->copy()->addMonths(3);
			
			if (now()->lt($nextAllowedDate)) {
				return response()->json([
					'status' => false,
					'message' => 'You can apply again after '.$nextAllowedDate->format('d-m-Y')
				], 403);
			}
		}
		$data['attempt_count'] = 1;
		$data['attempt_year'] = $currentYear;

		Company_profile_checks::updateOrCreate(
			['userId' => $userId],
			$data
		);

		return response()->json([
			'status' => true,
			'message' => 'Data saved successfully'
		]);
	}
	
	public function saveByAdmin(Request $request)
	{
		//echo "<pre>";print_r($_POST);exit;
		$data = $request->except(['_token']);

		$record = Company_profile_checks::where('userId', $request->userId)->first();

		$uploadPath = public_path('uploads/health-checkup');
		// Create folder if not exists
		if (!File::exists($uploadPath)) {
			File::makeDirectory($uploadPath, 0777, true, true);
		}

		if ($request->hasFile('admin_certificate')) {
			$file = $request->file('admin_certificate');
			$fileName = time().'_'.$file->getClientOriginalName();
			$file->move($uploadPath, $fileName);
			$data['admin_certificate'] = 'uploads/health-checkup/'.$fileName;
		}
		
		// Approved date logic
		if($request->admin_status == 1){
			$data['approved_on'] = date('Y-m-d');
		}else{
			$data['approved_on'] = null;
		}

		Company_profile_checks::updateOrCreate(
			['userId' => $request->userId],
			$data
		);

		return response()->json([
			'status' => true,
			'message' => 'Data updated successfully'
		]);
	}

	
	// List
    public function listingProfile()
    {					
		$user = Auth::user();
		$userId = ($user->u_type == 2) ? $user->id : $user->user_add_by;
		$uType = Auth::user()->u_type;
		checkCoreAccess('Business Health Check-up');
		$query = DB::table('company_profile_checks as cpc')
			->leftJoin('company_profiles as cp', 'cp.userId', '=', 'cpc.userId')
			->select(
				'cpc.*',
				'cp.comp_name'
			)
			->orderBy('cpc.id', 'desc');

		// If not admin, filter by logged-in user
		if ($uType != 3 && $uType != 6 ) {
			$query->where('cpc.userId', $userId);
		}
		 $list = $query->get();
		 
		// 3 Months Apply Logic
		$canApply = true;
		$nextApplyDate = null;

		$lastRecord = DB::table('company_profile_checks')
			->where('userId', $userId)
			->orderBy('id', 'desc')
			->first();

		if ($lastRecord) {
			$lastAppliedDate = \Carbon\Carbon::parse($lastRecord->created_at);
			$nextApplyDate = $lastAppliedDate
				->copy()
				->addMonths(3);
			if (now()->lt($nextApplyDate)) {
				$canApply = false;
			}
		}

        return view('Admin.company-profile-checks.index', compact('list','canApply','nextApplyDate'));
    }

    // View single
    public function showProfile($id)
    {
        $data = DB::table('company_profile_checks as cpc')
			->join('company_profiles as cp', 'cp.userId', '=', 'cpc.userId')
			->where('cpc.id', $id)
			->select(
				'cpc.*',
				'cp.comp_name',
				'cp.comp_email',
				'cp.comp_phone'
			)
			->first();

		if (!$data) {
			abort(404);
		}
        return view('Admin.company-profile-checks.view', compact('data'));
    }

    // Approve / Reject
    public function updateProfileStatus(Request $request, $id)
    {
        $request->validate([
            'admin_status' => 'required|in:1,0',
            'admin_remark' => 'nullable|string',
			'admin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

		$data = Company_profile_checks::findOrFail($id);
		
		// Ensure directory exists
		$uploadPath = public_path('uploads/health-checkup');

		if (!File::exists($uploadPath)) {
			File::makeDirectory($uploadPath, 0777, true, true);
		}

		// Existing file path
		$certPath = $data->admin_certificate;

		if ($request->hasFile('admin_certificate')) {
			if ($certPath && File::exists(public_path($certPath))) {
				File::delete(public_path($certPath));
			}

			$file = $request->file('admin_certificate');
			$fileName = time().'_'.$file->getClientOriginalName();
			$file->move($uploadPath, $fileName);
			$certPath = 'uploads/health-checkup/'.$fileName;
		}

		$data->update([
			'admin_status' => $request->admin_status,
			'admin_remark' => $request->admin_remark,
			'admin_certificate' => $certPath
		]);


        return redirect()->back()->with('success', 'Status updated successfully');
    }
}
