<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;
// use App\Country;
// use App\State;
use App\Models\City;
use App\Models\State;
// use App\Statutorys;
// use App\Task_managements;
use App\Http\Controllers\Helper;
use App\Helpers\AuditLogger;
// use Image;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

	public function home_page()
    {

		return view('pages.home')->with([

			]);
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::check()) {	
			return redirect('/login');
		}
		if (!Auth::check()) {
			return redirect('/login');
		}
		$userId = Auth::user()->id;
		if(Auth::user() && Auth::user()->u_type == 1){
			
			
			return view('Ca.Dashboard')->with([
				
			]);
		}else if(Auth::user() && Auth::user()->u_type == 2){
			$userId = Auth::user()->id;
			$statutory =  DB::table('statutorys')
							->select(DB::raw('statutorys.*'))
							->where('statutorys.compId','=',$userId)
							->orderBy('id', 'DESC')->offset(0)->limit(3)->get();
			$statutoryTwo =  DB::table('statutorys')
							->select(DB::raw('statutorys.*'))
							->where('statutorys.compId','=',$userId)
							->orderBy('id', 'DESC')->offset(3)->limit(6)->get();

			//--------------- Bank Details select (Dashboard)-----------
			$allBanks =  DB::table('banks')
						->where('added_by','=',$userId)
						->get();
			//--------------- Statutory Status ------------
			$statutory = DB::table('statutorys')
						->select(DB::raw('statutorys.*, company_profiles.comp_name, ca_assigns.ca_id'))
						->leftJoin('company_profiles', 'statutorys.compId', '=', 'company_profiles.userId')
						->leftJoin('ca_assigns', 'statutorys.compId', '=', 'ca_assigns.comp_id')
						->where('statutorys.compId', '=', $userId)
						->where('ca_assigns.ca_assign_status', '=', 1)
						->orderBy('id', 'DESC')
						->take(10)
						->get();
						
			$array = array();
			foreach ($statutory as $k => $val) {
				$array[$val->id]['id'] = $val->id;
				$array[$val->id]['added_by'] = $val->added_by;
				$array[$val->id]['compId'] = $val->compId;
				$array[$val->id]['comp_name'] = $val->comp_name;
				$array[$val->id]['statutory_doc'] = $val->statutory_doc;
				$array[$val->id]['statutory_due_date'] = $val->statutory_due_date;
				$array[$val->id]['statutory_msg'] = $val->statutory_msg;
				$array[$val->id]['status'] = $val->status;

				$caId = $val->added_by;
				$compName = DB::table('users')
					->select(DB::raw('users.name'))
					->where('id', '=', $caId)
					->get();
				$array[$val->id]['messages_by'] = $compName[0]->name;
				$array[$val->id]['messages'] = DB::table('chat_messages')
					->where('c_qid', '=', $val->id)
					->where(function ($q) use ($caId) {
						$q->where(function ($q2) use ($caId) {
							$q2->where('to_user_id', Auth::user()->id)->Where('from_user_id', $caId);
						});
					})
					->where('status', '=', 0)
					->get();
				
			}
			$statutory = json_decode(json_encode($array));
			$promoCodeStatus= '1';
			$states = State::where('country_id', '=', 101)->get();
			return view('User.Dashboard')->with([
				'states'=>$states,
				'allBanks' => $allBanks,
				'statutory' => $statutory

			]);
		}else if(Auth::user() && (Auth::user()->u_type == 3 || Auth::user()->u_type == 6)){
			$statutory =  DB::table('statutorys')
							->select(DB::raw('statutorys.*'))
							->orderBy('id', 'DESC')->offset(0)->limit(3)->get();
			$statutoryTwo =  DB::table('statutorys')
							->select(DB::raw('statutorys.*'))
							->orderBy('id', 'DESC')->offset(3)->limit(6)->get();
			//On-boarding CA
			$onboardCa =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,ca_profiles.*'))
							->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
							->where('users.u_type', '=', 1)
							->orderBy('users.id', 'DESC')
							->limit(3)->get();
			$array = array();
			foreach($onboardCa as $k=>$val)
			{
				$array[$val->id]['id'] = $val->uid;
				$array[$val->id]['comp_logo'] = ($val->comp_logo !="")?$val->comp_logo:$val->avatar;
				$array[$val->id]['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
				$array[$val->id]['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;
				$array[$val->id]['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;

				$states = State::where('country_id', '=', ($val->comp_bill_country !="")?$val->comp_bill_country:0)->get();
				$cities = City::where('state_id', '=', ($val->comp_bill_state !="")?$val->comp_bill_state:0)->get();
				$array[$val->id]['state'] = isset($states[0]->name)?$states[0]->name:"";
				$array[$val->id]['city'] = isset($cities[0]->name)?$cities[0]->name:"";
				$array[$val->id]['comp_bill_pin'] = ($val->comp_bill_pin !="")?$val->comp_bill_pin:"";
				$array[$val->id]['isCaActive'] = $val->isCaActive;
				$array[$val->id]['status'] = $val->status;
				$array[$val->id]['created_at'] = $val->created_at;
			}
			$onboardCa = json_decode(json_encode($array));

			//On-boarding Customer
			$onboardCust =  DB::table('users')
							->select(DB::raw('users.id as uid,users.*,company_profiles.*'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.u_type', '=', 2)
							->orderBy('users.id', 'DESC')->limit(3)->get();


			$array2 = array();
			foreach($onboardCust as $k=>$val)
			{
				$array2[$val->id]['id'] = $val->uid;
				$array2[$val->id]['comp_logo'] = ($val->comp_logo !="")?$val->comp_logo:$val->avatar;
				$array2[$val->id]['comp_name'] = ($val->comp_name !="")?$val->comp_name:$val->name;
				$array2[$val->id]['comp_email'] = ($val->comp_email !="")?$val->comp_email:$val->email;
				$array2[$val->id]['comp_phone'] = ($val->comp_phone !="")?$val->comp_phone:$val->phone;

				$states = State::where('country_id', '=', ($val->comp_bill_country !="")?$val->comp_bill_country:0)->get();
				$cities = City::where('state_id', '=', ($val->comp_bill_state !="")?$val->comp_bill_state:0)->get();
				$array2[$val->id]['state'] = isset($states[0]->name)?$states[0]->name:"";
				$array2[$val->id]['city'] = isset($cities[0]->name)?$cities[0]->name:"";
				$array2[$val->id]['comp_bill_pin'] = ($val->comp_bill_pin !="")?$val->comp_bill_pin:"";
				$array2[$val->id]['status'] = $val->status;
				$array2[$val->id]['created_at'] = $val->created_at;
			}
			$onboardCust = json_decode(json_encode($array2));

			$employees = DB::table('users')
					->select(DB::raw('users.id,
						SUM(IF((users.is_online = 0 || users.is_online = 1), 1, 0)) as totalEmp,
						SUM(IF((users.is_online = 1), 1, 0)) as totalPresent,
						SUM(IF((users.is_online = 0), 1, 0)) as totalAbsent'))
					->where('users.u_type', '=', 3)
					->where('users.id', '!=', 1)
					->where('users.ca_add_by', '=', Auth::user()->id)
					->groupBy('users.id')
					->get();
			return view('Admin.home')->with([
				'statutory' => $statutory,
				'statutoryTwo' => $statutoryTwo,
				'employees' =>$employees,
				'onboardCa' => $onboardCa,
				'onboardCust' => $onboardCust,

			]);
		}else if(Auth::user() && Auth::user()->u_type == 4){
			$userId = Auth::user()->id;
			$tasks = DB::table('task_managements')
						->where('emp_id', $userId)
						->selectRaw('
							COUNT(CASE WHEN project_status = 1 THEN 1 END) as pending,
							COUNT(CASE WHEN project_status = 2 THEN 1 END) as ongoing,
							COUNT(CASE WHEN project_status = 3 THEN 1 END) as completed
						')
						->first();
			//return view('pages.employee.emp-home')->with([
			return view('Ca.Dashboard')->with([
				'tasks' => $tasks
			]);
		}
		else if(Auth::user() && Auth::user()->u_type == 5){
			$userId = Auth::user()->id;
			$promoCodeStatus= '1';

			return view('Employee.UserEmployee.Dashboard')->with([
				
				

			]);
		}
		else{
			return redirect('/login');
		}
    }

	public function login(Request $request)
    {
		//print_r($request);exit;
		$title = 'Login';

			 return view('Login')->with([
				'title' =>$title
			]);


    }

	public function register(Request $request)
    {
		$title = 'Register';
		$states = State::where('country_id', '=', 101)->get();
		 return view('Register')->with([
			'title' =>$title,
			'states'=>$states
		]);

    }





	protected function validator(array $data)
    {
		//echo "<pre>"; print_r($data);exit;
        return Validator::make($data, [
            //'username' => 'required|max:255|unique:users',
            //'company_name' => 'required|min:3',
            'name' => 'required|min:3',
            'email' => 'required|email|max:255',
			'state_id' => 'required',
			'city_id' => 'required',
            'password' => 'required|min:6',
            'phone' => 'required|min:10|max:10'
        ]
		);
    }

    protected function create(array $data)
    {
		//print_r($data);exit;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'state_id' => $data['state_id'],
            'city_id' => $data['city_id'],
            'password' => Hash::make($data['password']),
			'phone' => $data['phone'],
			'u_type' => $data['u_type'],
			'status'   =>  0,
			'userStatus'   => 1,
			'isActive'   => 1,
			'is_online'   => 0,
			'created_at' => date('Y-m-d H:i:s'),
			'trial_start_at' => now(),
        ]);
    }



	public function resendVerificationEmail(Request $request)
	{
		$request->validate([
			'email' => 'required|email'
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user) {
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid email.'
			], 404);
		}

		if ($user->status == 1) {
			return response()->json([
				'status' => 'info',
				'message' => 'Already verified! Please login.'
			]);
		}

		// Resend verification email if status = 0
		$id = $user->id;
		$name = $user->name;
		$email = $user->email;
		$verifyUrl = url('/') . '/verify_email/' . base64_encode($id) . '/' . $email;

		$body = view('verify_email_template', compact('name', 'email', 'verifyUrl'))->render();

		$data_email = ['email' => $email];
		$emailSubject = "Confirm your Email Address to verify your account";

		$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);

		return response()->json([
			'status' => 'success',
			'message' => 'Verification email has been resent successfully.'
		]);


	}

	public function registerUser(Request $request)
	{
		$validation = $this->validator($request->all());

		if ($validation->fails()) {
			return response()->json($validation->errors()->toArray());
		}

		$user = User::where('email', $request->email)->first();

		if ($user) {
			if ($user->status == 1) {
				return response()->json([
					'status' => 'info',
					'message' => 'This email is already registered. Please login.'
				]);
			}

			if ($user->status == 0) {
				$this->resendVerificationEmail($request);
				return response()->json([
					'status' => 'success',
					'class' => 'succ',
					'message' => 'This email is already registered. We have resent the verification email.'
				]);
			}
		}

		// Create user
		$user = $this->create($request->all());

		if ($user) {
			$verifyUrl = url("/verify_email/" . base64_encode($user->id) . "/" . $user->email);

			$emailBody = view('verify_email_template', [
				'name' => $user->name,
				'email' => $user->email,
				'verifyUrl' => $verifyUrl,
			])->render();




			Helper::emailSendFunc($emailBody, ['email' => $user->email], 'Confirm your Email Address to verify your account');

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'message' => 'Registration successful! Please verify your email address.',
				'user_type' => $user->u_type
			]);
		}

		return response()->json([
			'status' => 'error',
			'message' => 'Something went wrong. Please try again.'
		]);
	}



	public function verify_email($id,$email)  {
		//die("fff");

		$id = base64_decode($id);
		$exists_data = DB::table('users')
							->where('id','=',$id)
							->where('email','=',$email)
							->where('status','=',0)
							->get()->toArray();

							//print_r($exists_data);exit;
		if(!empty($exists_data))
		{
			DB::table('users')
				->where('id', $id)
				->where('email', $email)
				->update(array('status' => 1));
			 		 $flag = 1;
		 $u_type = $exists_data[0]->u_type;
		 return view('email-verify')->with(['flag'=>$flag,'u_type'=>$u_type]);
		}else{
		 $flag = 0;
		 $u_type = 0;
		 return view('email-verify')->with(['flag'=>$flag,'u_type'=>$u_type]);
		}
	}

	public function test_email(Request $request)  {

		$body = '<html lang="en">
						<head>
						<title>Test Email</title>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						</head>
						<body style="margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;">
						<div style="width: 100%;display: block;position: relative;">
							Test Email
						</div>
						</body>
						</html> ';

		$data_email = [
			'email' => "binaysamanta@gmail.com"
		];
		$emailSubject = "Test Email";

		try {
			Mail::send([], [], function ($message) use ($body,$data_email,$emailSubject) {
			  $message->to($data_email['email'])
				->subject($emailSubject)
				->from(env('MAIL_FROM_ADDRESS'))
				->setBody($body, 'text/html');
			});
			return 'Email sent successfully!';
		}catch (Exception $ex) {
			// Debug via $ex->getMessage();
			return $ex;
		}

		/* if(count(Mail::failures()) > 0){
			echo 'Failed to send email, please try again.';
		}else{
			echo 'Email sent successfully!';
		} */
	}

	

	public function loginUser(Request $request)
	{
		$email = $request->email;
		$password = $request->password;
		$remember = $request->remember === 'true' || $request->remember === true;

		

		$userCheck = DB::table('users')
			->select('id', 'u_type','ca_add_by','user_add_by','admin_add_by','emp_permission','status','isActive','isdeleted', 'isCaActive')
			->where('email', $email)
			->get()
			->toArray();

		if (empty($userCheck)) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'User not exists !',
				'sendActive' => 0
			]);
		}

		$user = $userCheck[0];

		if ($user->status == 0) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Account email not verified !',
				'sendActive' => 1
			]);
		}

		if ($user->isActive == 0) {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Account inactive. Please contact with site Admin !'
			]);
		}

		if (Auth::attempt([
			'email' => $email,
			'password' => $password,
			'u_type' => $user->u_type,
			'ca_add_by' => $user->ca_add_by,
			'user_add_by' => $user->user_add_by,
			'admin_add_by' => $user->admin_add_by,
			'emp_permission' => $user->emp_permission,
			'status' => 1,
			'isActive' => 1,
			'isCaActive' => $user->isCaActive
		])) {
			
			AuditLogger::logEntry('Login','','Login successful',null,null); //log entry
			
			DB::table('users')
				->where('id', Auth::user()->id)
				->update(['is_online' => 1]);

			if ($remember) {
				$minutes = 60 * 24 * 15; // 15 days
				Cookie::queue(Cookie::make('loginId', $email, $minutes));
				Cookie::queue(Cookie::make('loginPass', $password, $minutes));
			} else {
				Cookie::queue(Cookie::forget('loginId'));
				Cookie::queue(Cookie::forget('loginPass'));
			}

			return response()->json([
				'status' => 'success',
				'class' => 'succ',
				'redirect' => url('/'),
				'message' => 'Login Successful'
			]);
		} else {
			return response()->json([
				'status' => 'error',
				'class' => 'err',
				'message' => 'Login Fail !',
				'sendActive' => 0
			]);
		}
	}


	public function forgotpassword(Request $request)
    {
		$title = 'Forgot Password';
		 return view('pages.forgotpassword')->with([
			'title' =>$title
		]);

    }

	public function getCity(Request $request)
    {

		  $id = $request->id;

			$result =City::query()
				   ->where('state_id', '=', $id)
				   ->get()->toArray();

				$response = [];
		//echo "<pre>";print_r($result);exit;
		 foreach($result as $row){
		   $response[] = array("id"=>$row['id'], "name"=>$row['name']);
		}
		echo json_encode($response);
    }

	public function save_forgotpassword(Request $request)
	{

		//print_r($request->all());exit;
		$email = $request->email;
		$userCheck = DB::table('users')
						->select(DB::raw('users.id,users.name,users.u_type,users.status,users.isActive,users.isdeleted'))
						->where('email','=',$email)
						->get()->toArray();
		if(empty($userCheck)){
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'message' => 'User not exists !',
				'sendActive' => 0
			);
			return response()->json($msg);
		}
		else if(!empty($userCheck) && ($userCheck[0]->status==0) ){
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'message' => 'Account email not verified !',
				'sendActive' => 1
			);
			return response()->json($msg);
		}
		else if(!empty($userCheck) && ($userCheck[0]->status==1) && ($userCheck[0]->isActive==0) ){
			$msg = array(
				'status' => 'error',
				'class' => 'err',
				'message' => 'Account inactive.Please contact with site Admin !',
			);
			return response()->json($msg);
		}
		else
		{
			$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$pass = array(); //remember to declare $pass as an array
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
				 }
			$pass_fog=(implode($pass));

			$hashed_random_password = Hash::make($pass_fog);
			$updatePass = DB::table('users')
						->where('email', $email)
						->update(array('password' => $hashed_random_password));

			if($updatePass){
				//Start send mail
			   $name = $userCheck[0]->name;
			   $email = $request->email;

			   $body = '<html lang="en">
								<head>
								<title>New Password</title>
								<meta charset="utf-8">
								<meta name="viewport" content="width=device-width, initial-scale=1">

								</head>
								<body style="margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;">

								<div style="width: 100%;display: block;position: relative;">
									<div style="display: block;">
										<a href="">
											<img src="'.asset('public/assets/img/logo.png').'" alt="logo" style="margin: 0 auto;padding: 20px 0;height: auto;max-width: 100%;display: block;">
										</a>
									</div>

									<div class="main-wraper" style="max-width: 600px;margin: 0 auto;position: relative;">
									<div style="margin-top: 50px;display: block;">
										<h1 style="color: #1fa8b8;font-size: 50px;text-align: center;margin-bottom: 0;">New Password</h1>
										<div style="width: 141px;background: #f57e20;height: 2px;margin: 8px auto 0;"></div>
									</div>
									<div class="content-wraper" style="margin-top: 50px;display: block;padding: 0 30px;">
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tr>
												<td align="left" style="padding-bottom: 20px;"><b>Dear '.$name.',</b></td>
											</tr>

											<tr>
												<td style="padding-bottom: 5px;"><p style="text-align: left;margin: 0;font-weight:600;">New Password: '.$pass_fog.'</p></td>
											</tr>



										</table>

									</div>


								</div>
								<div class="ft" style="background: #76bed0;display: block;">
										<p style="text-align: center;color: #ffffff;font-size: 14px;padding:5px 0;">Copyright © '.date("Y").' E-cashbook</p>
									</div>
								</div>

								</body>
								</html> ';

				$data_email = [
					'email' => $email
				];
				$emailSubject = "New Password";
				$sendMail = Helper::emailSendFunc($body,$data_email,$emailSubject);
				//End send mail
				if($sendMail){
					$msg = array(
							'status' => 'success',
							'class' => 'succ',
							'redirect' => url('/'),
							'message' => 'Password sent to your register Email'
						);
					return response()->json($msg);
				}
			}else{
				$msg = array(
					'status' => 'error',
					'class' => 'err',
					'message' => 'Action failed !',
					'sendActive' => 1
				);
				return response()->json($msg);
			}
		}

	}



	public function logout(Request $request) {
	  //$this->guard()->logout();
      //$request->session()->flush();
      //$request->session()->regenerate();
	  //$id = Auth::user()->id;
		if (Auth::check()) {
			DB::table('users')
				->where('id', Auth::id())
				->update(['is_online' => 0]);

			Auth::logout();
		}

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/login');
	}
	
	

	public function about_us(Request $request)
    {
		$title = 'About Us';
		 return view('pages.about')->with([
			'title' =>$title
		]);

    }
	public function pagenotfound()
	{
		return view('errors.pagenotfound');
	}
	public function forgetPassword()
	{
		return view('forget-password');
	}
	public function forgetPasswordOTP()
	{
		//---- Check if 'forgot_otp' exists in session -----
		if (!session()->has('forgot_otp') || empty(session('forgot_otp'))) {
			// If the OTP does not exist or is empty, redirect to the forget password page
			return redirect()->route('forgetPassword');
		}
		return view('forget-password-otp');
	}
	public function ResetPassword()
	{	
		if (!session()->has('email') || empty(session('email'))) {
			
			return redirect()->route('forgetPassword');
		}
		return view('reset-password');
	}

	public function checkVerificationStatus(Request $request)
	{
		$request->validate([
			'email' => 'required|email'
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user) {
			return response()->json([
				'verified' => false,
				'message' => 'User not found.'
			], 404);
		}

		return response()->json([
			'verified' => $user->status == 1,
			'message' => $user->status == 1 ? 'Email verified successfully.' : 'Email not verified yet.'
		]);
	}

	public function checkEmailExistence(Request $request)
	{
		// Validate email input
		$request->validate([
			'email' => 'required|email',
		]);

		// Check if the email exists in the 'users' table
		$user = User::where('email', $request->email)->first();

		if ($user) {
			// Generate a 6-digit OTP
			$otp = mt_rand(100000, 999999);

			// Send OTP email using Helper function
			$body = "
				<p>Hello,</p>
				<p>We received a request to reset your password. Please use the following One-Time Password (OTP) to complete the process:</p>
				<h2>$otp</h2>
				<p>This OTP will be valid for the next 10 minutes. If you did not request this, please ignore this email.</p>
				<p>Best regards,</p>
				<p>Ecashbook</p>
			";
			
			$data_email = [
					'email' => $user->email
				];
			$emailSubject = "Password Reset OTP";

			$maskedEmail = $this->maskEmail($user->email);
			
			// Call the email sending function
			$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);

			if ($sendMail) {
				// Store OTP in session (or a more secure location in production)
				$expirationTime = Carbon::now()->addMinutes(10)->toIso8601String(); // 10 minutes expiration time
            	session(['forgot_otp' => $otp, 'email' => $user->email, 'maskedEmail' => $maskedEmail, 'otp_expiration' => $expirationTime]);

				// Return success status and redirect to OTP verification page
				$msg = array(
					'status' => 'success',
					'message' => 'OTP sent successfully.',
					'redirect' => route('forgetPasswordOTP')
				);
				return response()->json($msg);
				
			} else {

				$msg = array(
					'status' => 'error',
					'message' => 'Failed to send OTP. Please try again later.',
				
				);
				return response()->json($msg);
				
			}
		} else {
			$msg = array(
					'status' => 'error',
					'message' => 'Email ID not found',
					
				);
				return response()->json($msg);
			
		}
	}

	public function forgetPasswordOTPDestroy(Request $request)
	{
		// Check if OTP has expired by comparing current time to the expiration time
		$otpExpiration = session('otp_expiration');

		if (!session()->has('forgot_otp') || !$otpExpiration || now()->greaterThan($otpExpiration)) {
			// OTP has expired or does not exist, so clear the session and redirect to login
			session()->forget(['forgot_otp', 'email', 'maskedEmail', 'otp_expiration']);
			return response()->json([
				'status' => 'redirect',
				'redirect' => route('login') 
			]);
		}

		// Continue with the OTP page logic if OTP is still valid
		return response()->json([
			'status' => 'valid',
			'message' => 'OTP is still valid'
		]);
	}

	public function verifyOtp(Request $request)
	{
		// Validate OTP input
		$request->validate([
			'otp' => 'required|numeric|digits:6',
		]);

		// Get the OTP entered by the user
		$enteredOtp = $request->otp;

		// Check if the entered OTP matches the one stored in the session
		if ($enteredOtp == session('forgot_otp')) {
			
			session()->forget(['forgot_otp']);
			// OTP matches, redirect to reset password page
			return response()->json([
				'status' => 'success',
				'message' => 'OTP Verify Successfully',
				'redirect' => route('resetPassword')
			]);
		} else {
			// OTP does not match
			return response()->json([
				'status' => 'error',
				'message' => 'Invalid OTP. Please try again.'
			]);
		}
	}

	private function maskEmail($email)
	{
		$emailParts = explode('@', $email);
		$localPart = $emailParts[0];
		$domainPart = $emailParts[1];

		// Mask the local part (show the first 3 characters, mask the rest with *)
		$maskedLocalPart = substr($localPart, 0, 3) . str_repeat('*', strlen($localPart) - 3);

		return $maskedLocalPart . '@' . $domainPart;
	}

	public function resendOtp(Request $request)
	{
		// Get the email from session
		$email = session('email');

		if (!$email) {
			return response()->json([
				'status' => 'error',
				'message' => 'No email found in session.'
			]);
		}

		// Find the user using the email stored in session
		$user = User::where('email', $email)->first();

		if (!$user) {
			return response()->json([
				'status' => 'error',
				'message' => 'User not found.'
			]);
		}

		// Generate a new OTP
		$otp = mt_rand(100000, 999999);
		
		$body = "
				<p>Hello,</p>
				<p>We received a request to reset your password. Please use the following One-Time Password (OTP) to complete the process:</p>
				<h2>$otp</h2>
				<p>This OTP will be valid for the next 10 minutes. If you did not request this, please ignore this email.</p>
				<p>Best regards,</p>
				<p>Ecashbook</p>
			";
		$data_email = ['email' => $user->email];
		$emailSubject = "Password Reset OTP";

		// Call the email sending function
		$sendMail = Helper::emailSendFunc($body, $data_email, $emailSubject);

		if ($sendMail) {
			// Store the new OTP in session
			session(['forgot_otp' => $otp]);

			// Update OTP expiration time to 10 minutes from now
			$expirationTime = Carbon::now()->addMinutes(10)->toIso8601String();
			session(['otp_expiration' => $expirationTime]);

			// Return success response
			return response()->json([
				'status' => 'success',
				'message' => 'OTP sent successfully.',
				'otp_expiration' => $expirationTime // Send the updated expiration time
			]);
		} else {
			// Return error response if OTP could not be sent
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to send OTP. Please try again later.'
			]);
		}
	}

	public function updatePassword(Request $request)
	{
		// Validate the request
		$request->validate([
			'email' => 'required|email',
			'password' => 'required|min:6', // Ensure password meets minimum length
		]);

		// Check if email exists in the session and update the password
		$email = $request->input('email');
		$user = User::where('email', $email)->first();

		if ($user) {
			// Update the user's password
			
			$user->password = Hash::make($request->input('password'));
			$user->save();

			return response()->json([
				'status' => 'success',
				'message' => 'Password updated successfully.'
			]);
		}

		// If user not found, return an error
		return response()->json([
			'status' => 'error',
			'message' => 'User not found.'
		]);
	}



	

}
