<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\User;

use App\Http\Controllers\Helper; 
use Image;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function EditProfile()
    {
        //return view('editProfile');
		$userId = Auth::id();
		$user = DB::table('users as u')
				->leftJoin('states as s', 's.id', '=', 'u.state_id')
				->leftJoin('cities as c', 'c.id', '=', 'u.city_id')
				->select(
					'u.*',
					's.name as state_name',
					'c.name as city_name'
				)
				->where('u.id', $userId)
				->first();

		return view('editProfile', compact('user'));
    }
	
	public function updateProfile(Request $request)
	{
		$request->validate([
			'name'  => 'required|string|max:100',
			'phone' => 'required|digits:10',
			'email' => 'required|email|unique:users,email,'.Auth::id(),
		]);

		DB::table('users')
			->where('id', Auth::id())
			->update([
				'name'        => $request->name,
				'designation' => $request->designation,
				'bio'         => $request->bio,
				'phone'       => $request->phone,
				//'email'       => $request->email,
				'address'     => $request->address,
				'updated_at'  => now()
			]);

		return response()->json([
			'success' => true,
			'message' => 'Profile updated successfully'
		]);
		 
	}
	
	public function changeEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,'.Auth::id()
        ]);

        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'email' => $request->new_email,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Email updated successfully'
        ]);
    }

    public function passwordChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 403);
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }
	
}
