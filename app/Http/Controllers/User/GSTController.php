<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Sales;
use App\Models\Sales_values;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use App\Models\Gst_logins;
use Helper;
use App\Services\WhiteBooksGstService;
use Illuminate\Support\Facades\Cookie;
use DateTime;
use DatePeriod;
use DateInterval;

class GSTController extends Controller
{
	//start demo
	protected $gstService;

	public function __construct(WhiteBooksGstService $gstService)
    {
        $this->gstService = $gstService;
    }

	public function gstOtherProfile(Request $req)
    {
        $gstin = $req->input('gstin');
        $profile = $this->gstService->getGstinProfile($gstin);
        return response()->json($profile);
    }

    public function gstOtherReturnStatus(Request $req)
    {
        $gstin = $req->input('gstin');
        $period = ($req->input('period'))?$req->input('period'):"";
        $rtype = ($req->input('return_type'))?$req->input('return_type'):"";
        $status = $this->gstService->getReturnStatus($gstin, $period, $rtype);
        return response()->json($status);
    }
	//End demo

    public function GSTProfile(Request $request)
    {
		$userId = currentOwnerId();
		checkCoreAccess('GST Returns & Reports');
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		
		if(Auth::user() && (Auth::user()->u_type != 3 && Auth::user()->u_type != 6)){
			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,company_profiles.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();
			$getBankData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_banks.*'))
							->leftJoin('company_banks', 'users.id', '=', 'company_banks.uid')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();
			//echo "<pre>"; print_r($getUserData);exit;
			$array = array();
			$array2 = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
				$array['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;
				$array['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;
				$array['comp_pan_no'] = ($val->comp_pan_no !="")?$val->comp_pan_no:"";
				$array['comp_website'] = ($val->comp_website !="")?$val->comp_website:"";
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";

			}
			$getUserData = json_decode(json_encode($array));
			$email = config('custom.COMP_EMAIL');
			//$email = $getUserData->comp_email;
			$gstin = $getUserData->gst_no;

			if (app()->environment('production')) {
				$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/public/search?email=$email&gstin=$gstin";
				$client_id = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
			} else {
				$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/public/search?email=$email&gstin=$gstin";
				$client_id = config('custom.MASTERSINDIA_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_CLIENT_SECRET');
			}
			$response = Http::withHeaders([
				'accept' => '*/*',
				'client_id'    => $client_id,
				'client_secret'=> $client_secret,
			])->get($url);

			$jsonData = json_encode([]);
			$status_cd = 0;
			$status_desc = "";
			if ($response->successful()) {
				//return response()->json($response->json());
				if($response->json('status_cd') && $response->json('status_cd')=='1'){
					$jsonData = $response->json('data');
					$status_cd = $response->json('status_cd');
					$status_desc = $response->json('status_desc');
				}else{
					$jsonData = array();
					$status_cd = 0;
					$status_desc = $response->json('status_desc');
				}
			} else {
				$jsonData = array();
				$status_cd = 0;
				$status_desc = $response->json('status_desc');
			}

			return view('User.gst-profile',
				['compData' => $getUserData,
				'jsonData' => $jsonData,
				'status_cd'=>$status_cd,
				'status_desc'=>$status_desc,
				'bankData'=>$getBankData]
			);
		}
    }
    public function OtherGSTProfile()
    {
		checkCoreAccess('GST Returns & Reports');
        return view('User.other-gst-profile');
    }
    public function GSTReturns()
    {
		if(Auth::user() && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)){
			$userId = currentOwnerId();
			checkCoreAccess('GST Returns & Reports');
			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.comp_tran_type,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();



			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['comp_tran_type'] = ($val->comp_tran_type !="")?$val->comp_tran_type:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['otp'] = ($val->otp !="")?$val->otp:0;
				$array['time'] = $this->checkTime($val->created_at);

			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>"; print_r($getUserData);exit;
			return view('User.gst-returns', ['compData' => $getUserData]);
		}
    }
    public function GSTReports(Request $request)
    {
		$userId = currentOwnerId();
		checkCoreAccess('GST Returns & Reports');
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		
		if(Auth::user() && (Auth::user()->u_type != 3 && Auth::user()->u_type != 6)){
			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,gst_logins.*'))
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();



			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['otp'] = ($val->otp !="")?$val->otp:0;
				$array['time'] = $this->checkTime($val->created_at);

			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>"; print_r($getUserData);exit;
			return view('User.gst-reports', ['compData' => $getUserData]);
		}
    }

	public function generate_GSTReports(Request $request)
    {
		$userId = currentOwnerId();
		//start ca-accountant access
		if (Auth::user()->u_type == 1 || Auth::user()->u_type == 4) {
			$userId = getAccessCompanyId($request);
		}
		//end ca-accountant access
		
		if(Auth::user() && (Auth::user()->u_type != 3 && Auth::user()->u_type != 6)){
			//echo "<pre>";print_r($_POST);exit;
			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['txn'] = ($val->txn !="")?$val->txn:"";
			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>";print_r($getUserData);exit;
			$gstin = $getUserData->gst_no;
			$period = ($request->input('financialYear'))?($request->input('periodSelect').$request->input('financialYear')):"";
			$mainRepType = ($request->input('mainReportType'))?$request->input('mainReportType'):"";
			$childRepType = ($request->input('childReportType'))?$request->input('childReportType'):"";
			$grandchildRepType = ($request->input('grandchildRepType'))?$request->input('grandchildRepType'):"";
			$gst_username = $getUserData->gst_username;
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$ip_address = '127.0.0.1'; //Helper::getClientIp();
			$txn = $getUserData->txn;
			$status = $this->gstService->getGstReportService($gstin,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$grandchildRepType);
			return response()->json($status);

		}
    }
	public function authenticate(Request $request)
	{
		$params = [
			'email' => $request->email,
			'otp'   => $request->otp
		];

		$headers = [
			'gst_username' => $request->gst_username,
			'state_cd'     => $request->state_cd,
			'ip_address'   => $request->ip(),
			'txn'          => $request->txn
		];

		$data = Helper::getAuthToken($params, $headers);

		return response()->json($data);
	}


	public function refreshToken()
    {
        $result = Helper::getRefreshToken();
        return response()->json($result);
    }

	// app/Http/Controllers/User/GSTController.php
	public function otpRequest(Request $request)
	{
		$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . '/authentication/otprequest';

		$params = [
			'email' => config('custom.COMP_EMAIL'),
		];

		$headers = [
			'gst_username' => 'TN_NT2.152384',
			'state_cd'     => '33',
			'ip_address'   => $request->ip(), // dynamic client IP
			'client_id'    => 'GSTP3ea8ba92-18cd-487d-b74a-d17760eaf713',
			'client_secret'=> 'GSTP5290e9a9-962d-40fa-88e3-6c85c538e3e4',
			'accept'       => 'application/json',
			'Content-Type' => 'application/json',
		];
		//echo "<pre>"; print_r($params); exit;
		$response = Http::withHeaders($headers)->post($url, $params);
		//	dd($response->json());
		if ($response->successful()) {
			return response()->json($response->json());
		}

		return response()->json([
			'error'   => true,
			'message' => $response->body()
		]);
	}




	public function otpVerify(Request $request)
	{
		$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . '/authentication/authtoken';

		$params = [
			'email' => config('custom.COMP_EMAIL'),
			'otp'   => $request->otp,
		];

		$headers = [
			'gst_username' => 'TN_NT2.152383',
			'state_cd'     => '33',
			'ip_address'   => $request->ip(),
			'txn'          => $request->txn,
			'client_id'    => config('custom.MASTERSINDIA_CLIENT_ID'),
			'client_secret'=> config('custom.MASTERSINDIA_CLIENT_SECRET'),
		];

		$response = Http::withHeaders($headers)->post($url, $params);

		if ($response->successful()) {
			$data = $response->json();

			// Auth token save to session
			Session::put('gst_auth_token', $data['auth_token'] ?? null);
			Session::put('gst_refresh_token', $data['refresh_token'] ?? null);

			return response()->json([
				'success' => true,
				'data'    => $data
			]);
		}

		return response()->json([
			'error' => true,
			'message' => $response->body()
		]);
	}


	public function getGstRateByHsn(Request $request)
    {
        $hsnCode = $request->hsn_code;
        $token   = Session::get('gst_auth_token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Auth token missing. Please authenticate first.'
            ]);
        }

        try {
            $url = "";
			if (app()->environment('production')) {
				$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/public/hsn/$hsnCode";
			} else {
				$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/public/hsn/$hsnCode";
			}

            $response = Http::withHeaders([
                'Authorization' => "Bearer $token",
                'Accept'        => 'application/json',
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'data'    => [
                        'cgst_rate' => $data['cgst_rate'] ?? null,
                        'sgst_rate' => $data['sgst_rate'] ?? null,
                        'igst_rate' => $data['igst_rate'] ?? null,
                        'cess_rate' => $data['cess_rate'] ?? null,
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response->body()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



	public function gst_authentication(Request $request)
    {
		if(Auth::user() && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)){
			$userId = currentOwnerId();
			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,company_profiles.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
				$array['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;
				$array['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;
				$array['comp_bill_state'] = ($val->comp_bill_state !="")? $val->comp_bill_state:"";
			}
			$getUserData = json_decode(json_encode($array));
			$clientIp = Helper::getClientIp();
			$params = [
				'email' => config('custom.COMP_EMAIL'),
				'otp' => $request->otp
			];

			$headers = [
				'gst_username' => 'TN_NT2.152384',
				'state_cd' => "33",//"$getUserData->comp_bill_state",
				'ip_address' => "$clientIp", //"1.39.21.133",
				'txn' =>$request->txn
			];

			$response = Helper::get_authtoken($params,$headers);
			if ($response->successful()) {
				$returnData = ($response->body());
				//echo "<pre>"; print_r (json_decode($returnData)); exit;
				$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'data' => $returnData,
							'message' => ""
						);
						return response()->json($msg);


			}else{
				$msg = array(
							'status' => 'error',
							'class' => 'err',
							'data' => "",
							'message' => "Service unavailable!"
						);
						return response()->json($msg);
			}
		}
    }



	public function fetchGSTDetails(Request $request)
	{
    $request->validate([
        'gstin' => 'required|string',
    ]);

    $userId = Auth::id();

    // Fetch email from users or company_profiles
    /*$userData = DB::table('users')
        ->select('users.email', 'company_profiles.email as company_email')
        ->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
        ->where('users.id', $userId)
        ->first();*/

    // Choose email preference: company email > user email
   // $email = $userData->company_email ?? $userData->email;
	$email = config('custom.COMP_EMAIL');
    $gstin = $request->gstin;

    //$url = "https://api.whitebooks.in/public/search?email=$email&gstin=$gstin";

	if (app()->environment('production')) {
		$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/public/search?email=$email&gstin=$gstin";
		$client_id = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
		$client_secret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
	} else {
		$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/public/search?email=$email&gstin=$gstin";
		$client_id = config('custom.MASTERSINDIA_CLIENT_ID');
		$client_secret = config('custom.MASTERSINDIA_CLIENT_SECRET');
	}


    $response = Http::withHeaders([
        'accept' => '*/*',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
    ])->get($url);

		if ($response->successful()) {
			return response()->json($response->json());
		} else {
			return response()->json([
				'error' => 'Failed to fetch GST details',
				'details' => $response->body()
			], 500);
		}
	}


	//start with whitebook otp, authentication
	public function whitebookOtpRequest(Request $request)
	{
		if(Auth::user() && (Auth::user()->u_type != 3 && Auth::user()->u_type != 6)){
			$url = '';
			$client_id = '';
			$client_secret = '';
			$gst_username = $request->gst_username;//'Pro_2024';
			$email = config('custom.COMP_EMAIL');
			$ip_address = '127.0.0.1';//Helper::getClientIp();
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$this->get_gst_username($gst_username);
			if (app()->environment('production')) {
				$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/authentication/otprequest?email=$email";
				$client_id = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
			} else {
				$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/authentication/otprequest?email=$email";
				$client_id = config('custom.MASTERSINDIA_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_CLIENT_SECRET');
			}
			//$url = 'https://apisandbox.whitebooks.in/authentication/otprequest?email=' . urlencode($email);

			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => [
					'Accept: application/json',
					'client_id: ' . $client_id,
					'client_secret: ' . $client_secret,
					'gst_username: ' . $gst_username,
					'state_cd: ' . $state_cd,
					'ip_address: ' . $ip_address,
				],
			]);

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				echo 'cURL Error #:' . $err;
			} else {
				$response = json_decode($response, true);
				//echo "<pre>"; print_r($response); exit;
				if (isset($response['status_cd']) && $response['status_cd'] === '1') {
					$update = DB::table('gst_logins')
										->where('gst_username', $gst_username)
										//->where('app_env', env('APP_ENV'))
										->update(
											array(
												'txn' => $response['header']['txn'],
												'otp'=>0,
												'created_at' => now(),
												'updated_at' => now(),
											)
										);
					$msg = array(
								'status' => 'success',
								'class' => 'succ',
								'data' => $response['status_cd'],
								'message' => $response['status_desc']
							);
							return response()->json($msg);


				}else{
					$msg = array(
								'status' => 'error',
								'class' => 'err',
								'data' => $response['status_cd'],
								'message' => $response['status_desc']
							);
							return response()->json($msg);
				}
			}
		}
	}

	public function whitebookAuthenticationRequest(Request $request)
	{
		if(Auth::user() && (Auth::user()->u_type != 3 && Auth::user()->u_type != 6)){
			$gst_username = $request->gst_username;
			$userExist = $this->userExist($gst_username);
			if($userExist){
				$loginData = DB::table('gst_logins')
									->where('gst_username', '=', $gst_username)
									->get();
			}
			$url = '';
			$client_id = '';
			$client_secret = '';
			$txn = $loginData[0]->txn;
			$otp = $request->otp; // Get the OTP from the registered email/mobile(GST User Recived OTP,Default OTP for SandBox is 575757)
			$email = config('custom.COMP_EMAIL');
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			//$gst_username = $loginData[0]->gst_username;//'Pro_2024';
			$ip_address = '127.0.0.1';

			if (app()->environment('production')) {
				$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/authentication/authtoken?email=$email&otp=$otp";
				$client_id = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
			} else {
				$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/authentication/authtoken?email=$email&otp=$otp";
				$client_id = config('custom.MASTERSINDIA_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_CLIENT_SECRET');
			}

			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => [
					'Accept: application/json',
					'client_id: ' . $client_id,
					'client_secret: ' . $client_secret,
					'gst_username: ' . $gst_username,
					'state_cd: ' . $state_cd,
					'ip_address: ' . $ip_address,
					'txn: ' . $txn,
				],
			]);

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				echo 'cURL Error #:' . $err;
			} else {
				$response = json_decode($response, true);
				if (isset($response['status_cd']) && $response['status_cd'] === '1') {
					$update = DB::table('gst_logins')
										->where('gst_username', $gst_username)
										//->where('app_env', env('APP_ENV'))
										->update(
											array(
												'txn' => $response['header']['txn'],
												'otp'=>1,
												'created_at' => now(),
												'updated_at' => now(),
											)
										);
					$msg = array(
								'status' => 'success',
								'class' => 'succ',
								'data' => $response['status_cd'],
								'message' => $response['status_desc']
							);
							return response()->json($msg);


				}else{
					$msg = array(
								'status' => 'error',
								'class' => 'err',
								'data' => $response['status_cd'],
								'message' => $response['status_desc']
							);
							return response()->json($msg);
				}
			}
		}
	}



	public function returnUserGstNo(){

		$userId = currentOwnerId();
		if(Auth::user() && (Auth::user()->u_type == 1 || Auth::user()->u_type == 4)){
			if (session()->has('compId')) {
				$userId = session('compId');
			}
		}
		$getUserData =  DB::table('users')
						->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no'))
						->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
						->where('users.u_type', '=', 2)
						->where('users.id', '=', $userId)
						->get();
		//echo "<pre>"; print_r($getUserData);exit;
		$array = array();
		foreach($getUserData as $k=>$val)
		{
			$array['uid'] = $val->uid;
			$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";

		}
		$getUserData = json_decode(json_encode($array));
		$gstin = $getUserData->gst_no;
		return $gstin;
	}

	public function get_gst_username($gst_username){
		$userId = currentOwnerId();
		if(Auth::user() && (Auth::user()->u_type == 1 || Auth::user()->u_type == 4)){
			if (session()->has('compId')) {
				$userId = session('compId');
			}
		}
		$gst_username = $gst_username;
		$app_env = env('APP_ENV');;
		$user = Gst_logins::where('gst_username', $gst_username)->first();
		if ($user) {
			return true;
		} else {
			DB::table('gst_logins')->insert([
					'user_id' => $userId,
					'gst_username' => $gst_username,
					'app_env' => $app_env,
				]);
			return true;
		}
	}
	public function userExist($gst_username){
		$user = Gst_logins::where('gst_username', $gst_username)->first();
		if ($user) {
			return true;
		} else {
			return false;
		}
	}
	// Check if it's within the next 5 hours
	public function checkTime($created_at){
		$currentDateTime = new DateTime();
		$tokenExpiry = new DateTime($created_at);
		// add 5 hours to current time
		$tokenExpiry->modify('+5 hours');
		//echo "Time after 5 hours: " . $tokenExpiry->format('Y-m-d H:i:s') . "\n";
		//echo "currentDateTime Time: " . $currentDateTime->format('Y-m-d H:i:s') . "\n";
		$tokenExpiry = new DateTime($tokenExpiry->format('Y-m-d H:i:s'));
		$currentDateTime = new DateTime($currentDateTime->format('Y-m-d H:i:s'));
		if ($currentDateTime->getTimestamp() < $tokenExpiry->getTimestamp() ) {
			return "1";
		} else {
			return "0";
		}
	}

	public function getGstReturnsData(Request $request)
    {
		//echo "<pre>";print_r($_POST);exit;
		if(Auth::user() && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)){
			$financialYear = $request->financialYear;
			$quarterSelect = $request->quarterSelect;
			$periodSelect = $request->periodSelect;
			$mainReportType = $request->mainReportType;
			$childReportType = $request->childReportType;
			$isNilReturn = $request->isNilReturn;
			$period = $periodSelect.$financialYear;
			$dateBetween = $this->getPreviousMonthStartEnd($financialYear,$periodSelect,$mainReportType);
			$dateBetween = explode('#', $dateBetween);
			$fromDate = $dateBetween[0];
			$toDate = $dateBetween[1];
			//echo "<pre>";print_r($_POST);exit;
			$userId = currentOwnerId();
			if($mainReportType =='gstr1'){
				$getB2BData = $this->getB2BData($userId, $fromDate, $toDate, $financialYear, $periodSelect);
				$getB2CLData = $this->getB2CLData($userId, $fromDate, $toDate, $financialYear, $periodSelect);
				$getB2CSData = $this->getB2CSData($userId, $fromDate, $toDate, $financialYear, $periodSelect);
				$get_hsn_b2b_b2c = $this->get_hsn_b2b_b2c($userId, $fromDate, $toDate, $financialYear, $periodSelect);
				$get_doc_issue = $this->get_doc_issue($userId, $fromDate, $toDate, $financialYear, $periodSelect);

				$merged = [
					"gstin" => $this->returnUserGstNo(),
					"fp" => $this->getFP($financialYear, $periodSelect),
					"gt" => $this->getGrossTurnoverPreviousFY(),
					"cur_gt" => $this->getGrossTurnoverCurrentFY($period),
				];

				if (!empty($getB2BData['b2b'])) {
					$merged['b2b'] = $getB2BData['b2b'];
				}
				if (!empty($getB2CLData['b2cl'])) {
					$merged['b2cl'] = $getB2CLData['b2cl'];
				}
				if (!empty($getB2CSData['b2cs'])) {
					$merged['b2cs'] = $getB2CSData['b2cs'];
				}
				if (!empty($get_hsn_b2b_b2c['hsn'])) {
					$merged['hsn'] = $get_hsn_b2b_b2c['hsn'];
				}
				if (!empty($get_doc_issue['doc_issue'])) {
					$merged['doc_issue'] = $get_doc_issue['doc_issue'];
				}

				return response()->json($merged, 200, [], JSON_PRETTY_PRINT);
			}else if($mainReportType =='gstr3b'){
				$status = $this->getReturnOfSummary($financialYear,$quarterSelect,$periodSelect,$mainReportType,$childReportType,$isNilReturn);
				return ($status);
			}else if($mainReportType =='gstr9'){
				$merged = $this->getGstr9AutoCalDet($financialYear,$quarterSelect,$periodSelect,$mainReportType,$childReportType,$isNilReturn);
				return response()->json($merged, 200, [], JSON_PRETTY_PRINT);
			}else if($mainReportType =='gstr9c'){
				$merged = $this->getGstr9Details($financialYear,$quarterSelect,$periodSelect,$mainReportType,$childReportType,$isNilReturn);
				return response()->json($merged, 200, [], JSON_PRETTY_PRINT);
			}

		}
    }

	public function getReturnOfSummary($financialYear,$quarterSelect,$periodSelect,$mainReportType,$childReportType,$isNilReturn){
		//echo "<pre>";print_r($request);exit;
		$userId = currentOwnerId();
		$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['txn'] = ($val->txn !="")?$val->txn:"";
			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>";print_r($getUserData);exit;
			$gstin = $getUserData->gst_no;
			$period = $this->getFP($financialYear, $periodSelect);
			$gst_username = $getUserData->gst_username;
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$ip_address = '127.0.0.1'; //Helper::getClientIp();
			$txn = $getUserData->txn;
			$status = $this->gstService->getGstReturnService($gstin,$period,$gst_username,$state_cd,$ip_address,$txn,$mainReportType,$childReportType,$isNilReturn);
			//echo"<pre>";print_r($status);
			return $status;
	}
	
	public function getGstr9AutoCalDet($financialYear,$quarterSelect,$periodSelect,$mainReportType,$childReportType,$isNilReturn){
		//echo "<pre>";print_r($request);exit;
		$userId = currentOwnerId();
		$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['txn'] = ($val->txn !="")?$val->txn:"";
			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>";print_r($getUserData);exit;
			$gstin = $getUserData->gst_no;
			$period = $this->getFP($financialYear, $periodSelect);
			$gst_username = $getUserData->gst_username;
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$ip_address = '127.0.0.1'; //Helper::getClientIp();
			$txn = $getUserData->txn;
			$status = $this->gstService->getGstr9AutoCalDetails($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainReportType,$childReportType,$isNilReturn);
			//echo"<pre>";print_r($status);
			return $status;
	}
	
	public function getGstr9Details($financialYear,$quarterSelect,$periodSelect,$mainReportType,$childReportType,$isNilReturn){
		//echo "<pre>";print_r($request);exit;
		$userId = currentOwnerId();
		$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['txn'] = ($val->txn !="")?$val->txn:"";
			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>";print_r($getUserData);exit;
			$gstin = $getUserData->gst_no;
			$period = $this->getFP($financialYear, $periodSelect);
			$gst_username = $getUserData->gst_username;
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$ip_address = '127.0.0.1'; //Helper::getClientIp();
			$txn = $getUserData->txn;
			$status = $this->gstService->getGstr9Details($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainReportType,$childReportType,$isNilReturn);
			//echo"<pre>";print_r($status);
			return $status;
	}

	public function getPreviousMonthStartEnd($financialYear, $month,$type){
				
		$year = (int)($financialYear);
		if (strtolower($type) === 'gstr9' || strtolower($type) === 'gstr9c') {
			$startDate = $year . "-04-01";          // 1st April of financial year
			$endDate   = ($year + 1) . "-03-31";    // 31st March of next year
			return $startDate . '#' . $endDate;
		}
		// Create a date for the 1st day of the given month
		$date = DateTime::createFromFormat('Y-n-j', "$year-$month-1");
		// Get first and last date of current month
		$firstDate = $date->format('Y-m-01');
		$lastDate  = $date->format('Y-m-t');
		return $firstDate . '#' . $lastDate;
	}

	public function getFP($financialYear, $month){
		$year = substr($financialYear, 0, 4); // 2024
		$nextYear = (int)$year;
		return $month.$nextYear;
	}

	public function getGrossTurnoverPreviousFY()
	{
		// Get the current year and determine the previous financial year range
		$today = Carbon::today();
		$currentYear = $today->year;
		$userId = currentOwnerId();
		// Financial year in India runs from April 1 to March 31
		if ($today->month >= 4) {
			// Current FY is 2025–2026 → previous FY = 2024–2025
			$fyStart = Carbon::create($currentYear - 1, 4, 1)->startOfDay();
			$fyEnd   = Carbon::create($currentYear, 3, 31)->endOfDay();
		} else {
			// Before April → Current FY is 2024–2025 → previous FY = 2023–2024
			$fyStart = Carbon::create($currentYear - 2, 4, 1)->startOfDay();
			$fyEnd   = Carbon::create($currentYear - 1, 3, 31)->endOfDay();
		}

		// Now calculate total turnover within that period
		$grossTurnover = DB::table('sales_values')
			->join('sales', 'sales.id', '=', 'sales_values.sid')
			->whereBetween('sales.inv_date', [$fyStart, $fyEnd])
			->where('sales.added_by', '=', $userId)
			->select(DB::raw('
				SUM(sales_values.amount + sales_values.tax_amt) as gross_turnover_previous_fy
			'))
			->value('gross_turnover_previous_fy');

		return round($grossTurnover, 2);
	}

	public function getGrossTurnoverCurrentFY($period)
	{
		$userId = currentOwnerId();
		[$startDate, $endDate] = $this->getLastQuarterRange($period);

		$grossTurnover = DB::table('sales_values')
			->join('sales', 'sales.id', '=', 'sales_values.sid')
			->whereBetween('sales.inv_date', [$startDate, $endDate])
			->where('sales.added_by', '=', $userId)
			->select(DB::raw('
				SUM(sales_values.amount + sales_values.tax_amt) as gross_turnover_current_fy
			'))
			->value('gross_turnover_current_fy');
		return round($grossTurnover, 2);
	}
	
	function getLastQuarterRange($period)
	{
		$month = (int)substr($period, 0, 2);
		$year = (int)substr($period, 2, 4);

		if ($month >= 4 && $month <= 6) {
			// Q1: Apr–Jun
			$start = "$year-04-01";
			$end   = "$year-06-30";
		} elseif ($month >= 7 && $month <= 9) {
			// Q2: Jul–Sep
			$start = "$year-07-01";
			$end   = "$year-09-30";
		} elseif ($month >= 10 && $month <= 12) {
			// Q3: Oct–Dec
			$start = "$year-10-01";
			$end   = "$year-12-31";
		} else {
			// Q4: Jan–Mar (next year)
			$start = ($year - 1) . "-01-01";
			$end   = ($year - 1) . "-03-31";
		}

		return [$start, $end];
	}


	public function get_diff_percent(){
		return 0.65;
	}

	public function getTotalInvoiceValue($invoiceNo){
		$totalInvoiceValue = DB::table('sales_values')
			->join('sales', 'sales.id', '=', 'sales_values.sid')
			->where('sales.inv_num', '=', $invoiceNo)
			->select(DB::raw('
				SUM(sales_values.amount + sales_values.tax_amt) as totalInvAmount
			'))
			->value('totalInvAmount');

		return round($totalInvoiceValue, 2);
	}

	public function getTotalTaxableValue($invoiceNo){
		$totalTaxableAmt = DB::table('sales_values')
			->join('sales', 'sales.id', '=', 'sales_values.sid')
			->where('sales.inv_num', '=', $invoiceNo)
			->select(DB::raw('
				SUM(sales_values.amount) as totalTaxableAmt
			'))
			->value('totalTaxableAmt');

		return round($totalTaxableAmt, 2);
	}

	public function getTotalQuantity($invoiceNo){
		$totalQuantity = DB::table('sales_values')
			->join('sales', 'sales.id', '=', 'sales_values.sid')
			->where('sales.inv_num', '=', $invoiceNo)
			->select(DB::raw('
				SUM(sales_values.quantity) as totalQuantity
			'))
			->value('totalQuantity');

		return $totalQuantity;
	}

	public function getSalesData($userId, $fromDate, $toDate, $financialYear, $periodSelect, $flag){
		$salesData = DB::table('sales')
			->join('sales_values', 'sales.id', '=', 'sales_values.sid')
			->join('customers', 'sales.inv_name', '=', 'customers.id')
			->join('products', 'sales_values.prod_id', '=', 'products.id')
			->select(
				'sales.id',
				'sales.seller_gst',
				'sales.inv_num',
				'sales.inv_date',
				'sales.seller_state',
				'sales.inv_name',
				'sales.gst_type',
				'sales_values.rate',
				'sales_values.quantity',
				'sales_values.amount',
				'sales_values.tax_amt',
				'sales_values.gst_rate',
				'sales_values.gst_trans',
				'customers.cust_gst_no','customers.cin','customers.cust_bill_state',
				'products.hsn_code','products.sac_code','products.item_name','products.service_name','products.base_unit',
			)
			//->groupBy('sales.id','sales.inv_num','sales.seller_gst','sales.inv_date','sales.seller_state','sales.inv_name')
			->whereBetween('sales.inv_date', [$fromDate, $toDate])
			->where('sales.status', 1) //only active records
			->where('sales.pay_status', 'Full')
			->where('sales.added_by', '=', $userId)
			->when($flag === 'B2B', function ($q) {
				$q->where('gst_type', 'B2B');
			})
			->when($flag === 'B2CL', function ($q) {
				$q->where('gst_type', 'B2CL');
			})
			->when($flag === 'B2CS', function ($q) {
				$q->where('gst_type', 'B2CS');
			})
			->when($flag === 'not_b2b', function ($q) {
				$q->where('gst_type', '!=', 'B2B');
			})
			->when($flag === '', function ($q) {

			})
			->get();
		return $salesData;
	}

	public function getB2BData($userId, $fromDate, $toDate, $financialYear, $periodSelect){
			$flag = 'B2B';
			$salesData = $this->getSalesData($userId, $fromDate, $toDate, $financialYear, $periodSelect,$flag);
			//echo "<pre>";print_r($salesData);exit;
			$data = [
				'b2b' => []
			];
			//echo "<pre>";print_r($salesData);exit;
			foreach ($salesData->groupBy('inv_num') as $inv_num => $invoices) {
				$firstData = $invoices->first();
				$b2bEntry = [
					'ctin' => $firstData->cust_gst_no,
					'inv' => []
				];

				foreach ($invoices->groupBy('inv_num') as $invoiceNo => $items) {
					$first = $items->first();

					$invoice = [
						'inum' => $first->inv_num,
						'idt' => date('d-m-Y', strtotime($first->inv_date)),
						'val' => $this->getTotalInvoiceValue($first->inv_num),
						'pos' => substr($first->cust_gst_no, 0, 2),
						'rchrg' => "N",
						//'etin' => $first->seller_gst,
						'inv_typ' => 'R',
						'diff_percent' => $this->get_diff_percent(),
						'itms' => []
					];

					foreach ($items as $index => $item) {
						$igst = 0;
						$sgst = 0;
						$cgst = 0;
						$csamt = 0;
						if($item->gst_trans='intrastate'){
							$sgst = $this->get_sgst($item->amount,$item->gst_rate);
							$cgst = $this->get_cgst($item->amount,$item->gst_rate);
						}else{
							$igst = $this->get_igst($item->amount,$item->gst_rate);
						}						
						$invoice['itms'][] = [
							'num' => $index + 1,
							'itm_det' => [
								'rt' => $item->gst_rate,
								'txval' => $item->amount,
								'iamt' => $igst,
								'camt' => $cgst,
								'samt' => $sgst,
								'csamt' => $csamt,
							]
						];
					}

					$b2bEntry['inv'][] = $invoice;
				}

				$data['b2b'][] = $b2bEntry;
			}
		//echo "<pre>";print_r($data);exit;
		/*$finalData = [
			"data" => $data
		];*/
		return $data;
	}

	public function getB2CLData($userId, $fromDate, $toDate, $financialYear, $periodSelect){
			$flag = 'B2CL';
			$salesData = $this->getSalesData($userId, $fromDate, $toDate, $financialYear, $periodSelect,$flag);
			//echo "<pre>";print_r($salesData);exit;
			$data = [
				'b2cl' => []
			];
			//echo "<pre>";print_r($salesData);exit;
			foreach ($salesData->groupBy('inv_num') as $inv_num => $invoices) {
				$firstData = $invoices->first();
				$b2clEntry = [
					//'pos' => substr($firstData->cust_gst_no, 0, 2),
					'pos' => !empty($firstData->cust_gst_no) ? substr($firstData->seller_gst, 0, 2) : substr($firstData->seller_gst, 0, 2),
					'inv' => []
				];

				foreach ($invoices->groupBy('inv_num') as $invoiceNo => $items) {
					$first = $items->first();

					$invoice = [
						'inum' => $first->inv_num,
						'idt' => date('d-m-Y', strtotime($first->inv_date)),
						'val' => $this->getTotalInvoiceValue($first->inv_num),
						//'inv_typ' => 'R',
						//'etin' => $first->seller_gst,
						'diff_percent' => $this->get_diff_percent(),
						'itms' => []
					];

					foreach ($items as $index => $item) {
						$igst = $this->get_igst($item->amount,$item->gst_rate);
						$csamt = 0;
						$invoice['itms'][] = [
							'num' => $index + 1,
							'itm_det' => [
								'rt' => $item->gst_rate,
								'txval' => $item->amount,
								'iamt' => $igst,
								'csamt' => $csamt,
							]
						];
					}

					$b2clEntry['inv'][] = $invoice;
				}

				$data['b2cl'][] = $b2clEntry;
			}
		//echo "<pre>";print_r($data);exit;
		return $data;
	}

	public function getB2CSData($userId, $fromDate, $toDate, $financialYear, $periodSelect){
			$flag = 'B2CS';
			$salesData = $this->getSalesData($userId, $fromDate, $toDate, $financialYear, $periodSelect,$flag);
			//echo "<pre>";print_r($salesData);exit;
			$data = [
				'b2cs' => []
			];
			//echo "<pre>";print_r($salesData);exit;
			foreach ($salesData->groupBy('inv_num') as $inv_num => $invoices) {

				$b2csEntry = [
					//'ctin' => $cust_gst_no,
					//'inv' => []
				];

				foreach ($invoices->groupBy('inv_num') as $invoiceNo => $items) {
					$first = $items->first();
					$etin = $first->seller_gst;
					//$pos = substr($first->cust_gst_no, 0, 2);
					$pos = !empty($first->cust_gst_no) ? substr($first->seller_gst, 0, 2) : substr($first->seller_gst, 0, 2);

					foreach ($items as $index => $item) {
						$igst = 0;
						$sgst = 0;
						$cgst = 0;
						$csamt = 0;
						if($item->gst_trans='intrastate'){
							$sgst = $this->get_sgst($this->getTotalTaxableValue($first->inv_num),$item->gst_rate);
							$cgst = $this->get_cgst($this->getTotalTaxableValue($first->inv_num),$item->gst_rate);
						}else{
							$igst = $this->get_igst($this->getTotalTaxableValue($first->inv_num),$item->gst_rate);
						}
						$invoice = [
							'sply_ty' => strtoupper(substr($item->gst_trans, 0, 5)),
							'diff_percent' => $this->get_diff_percent(),
							"rt" => $item->gst_rate,
							"typ" => "E",
							//"etin" => $etin,
							"pos" => $pos,
							"txval" => $this->getTotalTaxableValue($first->inv_num),
							"iamt" => $igst,
							"camt" => $cgst,
							"samt" => $sgst,
							"csamt" => $csamt,
						];
					}

					$b2csEntry = $invoice;
				}

				$data['b2cs'][] = $b2csEntry;
			}
		//echo "<pre>";print_r($data);exit;
		return $data;
	}

	public function get_hsn_b2b_b2c($userId, $fromDate, $toDate, $financialYear, $periodSelect)
	{

		 // --- B2B (customer with GST number)
        $b2b = DB::table('sales')
            ->join('customers', 'sales.inv_name', '=', 'customers.id')
            ->join('sales_values', 'sales.id', '=', 'sales_values.sid')
            ->join('products', 'sales_values.prod_id', '=', 'products.id')
            ->select(
                'products.hsn_code as hsn_sc',
                'products.sac_code as sac_code',
                'products.base_unit as uqc',
				'sales_values.gst_trans',
                DB::raw('SUM(sales_values.quantity) as qty'),
                DB::raw('SUM(sales_values.amount) as txval'),
                'sales_values.gst_rate as rt'
            )
            ->whereBetween('sales.inv_date', [$fromDate, $toDate])
            ->whereNotNull('customers.cust_gst_no')
            ->where('customers.cust_gst_no', '!=', '')
            ->where('sales.gst_type', '=', 'B2B')
			->where('sales.added_by', '=', $userId)
			->where('sales.status', 1) //only active records
			->where('sales.pay_status', 'Full')
            ->groupBy('products.hsn_code','products.sac_code', 'products.base_unit','sales_values.gst_trans', 'sales_values.gst_rate')
            ->get()
            ->map(function ($item, $index) {
				$igst = 0;
				$sgst = 0;
				$cgst = 0;
				$csamt = 0;
				if($item->gst_trans='intrastate'){
					$sgst = $this->get_sgst($item->txval, $item->rt);
					$cgst = $this->get_cgst($item->txval, $item->rt);
				}else{
					$igst = $this->get_igst($item->txval, $item->rt);
				}
                return [
                    'num' => $index + 1,
                    'hsn_sc' => $item->hsn_sc ?: $item->sac_code,
                    'uqc' => !empty($item->uqc) ? $item->uqc : 'NA',
                    'qty' => ($item->hsn_sc !="")?(float)$item->qty:0,
                    'txval' => (float)$item->txval,
					'iamt' => $igst,
					'camt' => $cgst,
					'samt' => $sgst,
					'csamt' => $csamt,
                    'rt' => (float)$item->rt,
                ];
            });

        // --- B2C (customer without GST number)
        $b2c = DB::table('sales')
            ->join('customers', 'sales.inv_name', '=', 'customers.id')
            ->join('sales_values', 'sales.id', '=', 'sales_values.sid')
            ->join('products', 'sales_values.prod_id', '=', 'products.id')
            ->select(
                'products.hsn_code as hsn_sc',
				'products.sac_code as sac_code',
                'products.base_unit as uqc',
				'sales_values.gst_trans',
                DB::raw('SUM(sales_values.quantity) as qty'),
                DB::raw('SUM(sales_values.amount) as txval'),
                'sales_values.gst_rate as rt'
            )
            ->whereBetween('sales.inv_date', [$fromDate, $toDate])
			->where('sales.gst_type', '!=', 'B2B')
			->where('sales.added_by', '=', $userId)
			->where('sales.status', 1) //only active records
			->where('sales.pay_status', 'Full')
            ->where(function ($q) {
                $q->whereNull('customers.cust_gst_no')
                  ->orWhere('customers.cust_gst_no', '=', '');
            })
            ->groupBy('products.hsn_code','products.sac_code', 'products.base_unit','sales_values.gst_trans', 'sales_values.gst_rate')
            ->get()
            ->map(function ($item, $index) {				
				$igst = 0;
				$sgst = 0;
				$cgst = 0;
				$csamt = 0;
				if($item->gst_trans='intrastate'){
					$sgst = $this->get_sgst($item->txval, $item->rt);
					$cgst = $this->get_cgst($item->txval, $item->rt);
				}else{
					$igst = $this->get_igst($item->txval, $item->rt);
				}
                return [
                    'num' => $index + 1,
                    'hsn_sc' => $item->hsn_sc ?: $item->sac_code,
                    'uqc' => !empty($item->uqc) ? $item->uqc : 'NA',
                    'qty' => ($item->hsn_sc !="")?(float)$item->qty:0,
                    'txval' => (float)$item->txval,
					'iamt' => $igst,
					'camt' => $cgst,
					'samt' => $sgst,
					'csamt' => $csamt,
                    'rt' => (float)$item->rt,
                ];
            });

        $response = [
            'hsn' => [
                'hsn_b2b' => $b2b,
                'hsn_b2c' => $b2c
            ]
        ];
		//echo "<pre>";print_r($response);exit;
		return $response;
	}

	public function get_doc_issue($userId, $fromDate, $toDate, $financialYear, $periodSelect)
	{

		$sales = DB::table('sales')
			->leftJoin('customers', 'sales.inv_name', '=', 'customers.id')
			->join('sales_values', 'sales.id', '=', 'sales_values.sid')
			->select(
				'sales.id',
				'sales.inv_num',
				'sales.inv_date',
				'sales.status',
				'customers.cust_gst_no',
				DB::raw('SUM(sales_values.amount) as total_amount')
			)
			->whereBetween('sales.inv_date', [$fromDate, $toDate])
			->where('sales.added_by', '=', $userId)
			->where('sales.status', 1) //only active records
			->where('sales.pay_status', 'Full')
			->groupBy('sales.id', 'sales.inv_num', 'sales.inv_date', 'sales.status', 'customers.cust_gst_no')
			//->havingRaw('SUM(sales_values.amount) > 250000')
			->orderBy('sales.inv_num', 'asc')
			->get();

		// Separate B2B and B2C
		$b2b = $sales->filter(function ($sale) {
			return trim((string)$sale->cust_gst_no) !== '';
		});

		$b2c = $sales->filter(function ($sale) {
			return trim((string)$sale->cust_gst_no) === '';
		});

		// Function to build doc_issue data for a given dataset
		$buildDocs = function ($records, $docNum) {
			if ($records->isEmpty()) return null;

			$total = $records->count();
			$cancel = $records->where('status', 0)->count();
			$net = $total - $cancel;
			$from = $records->first()->inv_num;
			$to = $records->last()->inv_num;

			return [
				'doc_num' => $docNum,
				'docs' => [
					[
						'num' => 1,
						'from' => $from,
						'to' => $to,
						'totnum' => $total,
						'cancel' => $cancel,
						'net_issue' => $net,
					]
				]
			];
		};

		// Build both sections
		$doc_det = [];
		$docNum = 1;

		if ($b2b->isNotEmpty()) {
			$doc_det[] = $buildDocs($b2b->values(), $docNum++);
		}

		if ($b2c->isNotEmpty()) {
			$doc_det[] = $buildDocs($b2c->values(), $docNum++);
		}

		// Remove nulls if any
		$doc_det = array_filter($doc_det);

		$response = [
			'doc_issue' => [
				'doc_det' => array_values($doc_det)
			]
		];
		//echo "<pre>";print_r($response);exit;
		return $response;

	}
	public function get_igst($amount, $gstRate){
		$igst = ($amount * $gstRate) / 100;
		return $igst;
	}
	public function get_sgst($amount, $gstRate){
		$igst = ($amount * $gstRate) / 100;
		$sgst = $igst / 2;
		return $sgst;
	}
	public function get_cgst($amount, $gstRate){
		$igst = ($amount * $gstRate) / 100;
		$cgst = $igst / 2;
		return $cgst;
	}
	
	function getGstr9Period($ym) {
		$year = intval(substr($ym, 2, 4));
		return "03" . $year;
	}

	public function submit_GSTReturns(Request $request)
    {
		if(Auth::user() && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)){
			//echo "<pre>";print_r($_POST);exit;
			$userId = currentOwnerId();
			$financialYear = $request->financialYear;
			$quarterSelect = $request->quarterSelect;
			$periodSelect = $request->periodSelect;
			$returnType = $request->mainReportType;
			$reportType = $request->childReportType;
			$isNilReturn = $request->isNilReturn;
			$uipayload = $request->uipayload;
			$period = $periodSelect.$financialYear;

			$exists = DB::table('gst_returns')
					->where('userid', $userId)
					->where('status', 1)
					->where('fy', $financialYear)
					->where('quarter', $quarterSelect)
					->where('period', $this->getFP($financialYear, $periodSelect))
					->where('ret_type', $returnType)
					->whereNotNull('ack_num')     // NOT NULL
					->where('ack_num', '!=', '')  // NOT empty
					->count();
					
			if ($exists) {
				return response()->json([
					'status_cd' => '0',
					'error' => [
						'message' => 'GST return already applied for this period and type - '.strtoupper($returnType),
						'error_cd' => '0'
					]
				]);
			} 
		
			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['txn'] = ($val->txn !="")?$val->txn:"";
			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>";print_r($getUserData);exit;

			$dateBetween = $this->getPreviousMonthStartEnd($financialYear,$periodSelect,$returnType);
			$dateBetween = explode('#', $dateBetween);
			$fromDate = $dateBetween[0];
			$toDate = $dateBetween[1];
			$merged = [];
			$getB2BData = $this->getB2BData($userId, $fromDate, $toDate, $financialYear, $periodSelect);
			$getB2CLData = $this->getB2CLData($userId, $fromDate, $toDate, $financialYear, $periodSelect);
			$getB2CSData = $this->getB2CSData($userId, $fromDate, $toDate, $financialYear, $periodSelect);
			$get_hsn_b2b_b2c = $this->get_hsn_b2b_b2c($userId, $fromDate, $toDate, $financialYear, $periodSelect);
			$get_doc_issue = $this->get_doc_issue($userId, $fromDate, $toDate, $financialYear, $periodSelect);
			if($returnType =='gstr1'){
				$merged = [
					"gstin" => $this->returnUserGstNo(),
					"fp" => $this->getFP($financialYear, $periodSelect),
					"gt" => $this->getGrossTurnoverPreviousFY(),
					"cur_gt" => $this->getGrossTurnoverCurrentFY($period),
				];

				if (!empty($getB2BData['b2b'])) {
					$merged['b2b'] = $getB2BData['b2b'];
				}
				if (!empty($getB2CLData['b2cl'])) {
					$merged['b2cl'] = $getB2CLData['b2cl'];
				}
				if (!empty($getB2CSData['b2cs'])) {
					$merged['b2cs'] = $getB2CSData['b2cs'];
				}
				if (!empty($get_hsn_b2b_b2c['hsn'])) {
					$merged['hsn'] = $get_hsn_b2b_b2c['hsn'];
				}
				if (!empty($get_doc_issue['doc_issue'])) {
					$merged['doc_issue'] = $get_doc_issue['doc_issue'];
				}
			}else if($returnType =='gstr3b'){
				$merged = [
					"gstin" => $this->returnUserGstNo(),
					"fp" => $this->getFP($financialYear, $periodSelect),
					"gt" => $this->getGrossTurnoverPreviousFY(),
					"cur_gt" => $this->getGrossTurnoverCurrentFY($period),
				];
			}else if($returnType =='gstr9'){
				$merged = [
					"gstin" => $this->returnUserGstNo(),
					"fp" => $this->getGstr9Period($period)//$this->getFP($financialYear, $periodSelect)
				];
				
				// Decode UI payload JSON to array
				$uiPayloadArr = json_decode($request->uipayload, true);
				$merged = array_merge($merged, $uiPayloadArr);
				//echo "<pre>";print_r($merged);exit;
				//return response()->json($merged, 200, [], JSON_PRETTY_PRINT);
			}else if($returnType =='gstr9c'){				
				$uiPayloadArr = json_decode($request->uipayload, true);
				/* Ensure structure exists */
				$uiPayloadArr['gstr9cdata']['audited_data'] = $uiPayloadArr['gstr9cdata']['audited_data'] ?? [];
				/* Merge gstin & fp INSIDE audited_data */
				$uiPayloadArr['gstr9cdata']['audited_data'] = array_merge(
					[
						"gstin" => $this->returnUserGstNo(),
						"fp"    => $this->getGstr9Period($period),
					],
					$uiPayloadArr['gstr9cdata']['audited_data']
				);
				$merged = $uiPayloadArr;
				//echo "<pre>";print_r($merged);exit;
				//return response()->json($merged, 200, [], JSON_PRETTY_PRINT);
			}
			//echo "<pre>";print_r($merged);exit;

			$gstin = $getUserData->gst_no;
			$period = $this->getFP($financialYear, $periodSelect);
			$mainRepType = ($request->input('mainReportType'))?$request->input('mainReportType'):"";
			$childRepType = ($request->input('childReportType'))?$request->input('childReportType'):"";
			$grandchildRepType = ($request->input('grandchildRepType'))?$request->input('grandchildRepType'):"";
			$gst_username = $getUserData->gst_username;
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$ip_address = '127.0.0.1'; //Helper::getClientIp();
			$txn = $getUserData->txn;
			$status = $this->gstService->submitGstReturnsService($merged, $gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNilReturn);
			return response()->json($status);

		}
    }

	public function final_submit_GSTReturns(Request $request)
    {
		if(Auth::user() && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)){
			//echo "<pre>";print_r($_POST);exit;
			$userId = currentOwnerId();
			$financialYear = $request->financialYear;
			$quarterSelect = $request->quarterSelect;
			$periodSelect = $request->periodSelect;
			$returnType = $request->mainReportType;
			$reportType = $request->childReportType;
			$isNilReturn = $request->isNilReturn;
			$evc_otp = $request->evc_otp;

			/*$exists = DB::table('gst_returns')
					->where('status', 1)
					->where('fy', $financialYear)
					->where('quarter', $quarterSelect)
					->where('period', $this->getFP($financialYear, $periodSelect))
					->where('ret_type', $returnType)
					->count();
			if ($exists) {
				return response()->json([
					'status_cd' => '0',
					'error' => [
						'message' => 'GST return already applied for this period and type - '.strtoupper($returnType),
						'error_cd' => '0'
					]
				]);
			} */

			$getUserData =  DB::table('users')
							->select(DB::raw('users.id as uid,company_profiles.gst_no as gst_no,gst_logins.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->leftJoin('gst_logins', 'users.id', '=', 'gst_logins.user_id')
							->where('users.u_type', '=', 2)
							->where('users.id', '=', $userId)
							->get();

			$array = array();
			foreach($getUserData as $k=>$val)
			{
				$array['uid'] = $val->uid;
				$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
				$array['gst_username'] = ($val->gst_username !="")?$val->gst_username:"";
				$array['txn'] = ($val->txn !="")?$val->txn:"";
			}
			$getUserData = json_decode(json_encode($array));
			//echo "<pre>";print_r($getUserData);exit;

			$dateBetween = $this->getPreviousMonthStartEnd($financialYear,$periodSelect,$returnType);
			$dateBetween = explode('#', $dateBetween);
			$fromDate = $dateBetween[0];
			$toDate = $dateBetween[1];
			$merged = [];
			//return response()->json($merged, 200, [], JSON_PRETTY_PRINT);

			$gstin = $getUserData->gst_no;
			$period = $this->getFP($financialYear, $periodSelect);
			$mainRepType = ($request->input('mainReportType'))?$request->input('mainReportType'):"";
			$childRepType = ($request->input('childReportType'))?$request->input('childReportType'):"";
			$grandchildRepType = ($request->input('grandchildRepType'))?$request->input('grandchildRepType'):"";
			$gst_username = $getUserData->gst_username;
			$state_cd = substr($this->returnUserGstNo(), 0, 2);
			$ip_address = '127.0.0.1'; //Helper::getClientIp();
			$txn = $getUserData->txn;
			$status = $this->gstService->fileGSTR1Service($merged, $gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNilReturn,$evc_otp);
			return response()->json($status);

		}
    }
	
	function extractTotals($arr)
	{
		return [
			"txval" => $arr["txval"] ?? 0,
			"iamt"  => $arr["iamt"] ?? 0,
			"camt"  => $arr["camt"] ?? 0,
			"samt"  => $arr["samt"] ?? 0,
			"csamt" => $arr["csamt"] ?? 0
		];
	}
	public function GSTComplianceSupport(){

    return view('User.gst-compliance-support');

    }

}
