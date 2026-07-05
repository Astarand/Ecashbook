<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function Register()
    {
        return view('Register');
    }

    public function Login()
    {
        return view('Login');
    }

    public function LockScreen()
    {
        return view('Lock');
    }
    public function ChangePassword()
    {
        return view('Change-password');
    }
	
	public function unlockUser(Request $request)
	{
		$email = $request->email;
		$password = $request->password;

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
			
			DB::table('users')
				->where('id', Auth::user()->id)
				->update(['is_online' => 1]);

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
}
