<?php

namespace App\Http\Controllers\CA;

use App\Http\Controllers\Controller;
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
use App\Models\Notifications;
use App\Models\Ca_assigns;

use Helper;
use Image;
use Illuminate\Support\Facades\Cookie;

class NotificationController extends Controller
{
     public function index()
    {
		//$this->middleware('auth');
		$title = 'Notifications';
		$from_uid = Auth::user()->id;
		$data = DB::table('notifications')
			->select(DB::raw('notifications.*'))
			->where('notifications.to_uid', $from_uid)
			//->where('notifications.utype', $utype)
			//->where('notifications.status', 1)
			->orderBy('id', 'DESC')->paginate(10);

		$notifications_pagination = $data;

		$array = array();
		foreach($data as $k=>$val)
		{
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['from_uid'] = $val->from_uid;
			$array[$val->id]['to_uid'] = $val->to_uid;
			$array[$val->id]['utype'] = $val->utype;
			$array[$val->id]['noti_title'] = $val->noti_title;
			$array[$val->id]['msg'] = $val->msg;
			$array[$val->id]['url_action'] = $val->url_action;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['created_at'] = date("d M y", strtotime($val->created_at));

			$user =  DB::table('users')
							->select(DB::raw('users.name,users.avatar'))
							->where('id', '=', $val->from_uid)
							->get();
			$array[$val->id]['name'] = isset($user[0]->name)?$user[0]->name:"";;
			$array[$val->id]['avatar'] = isset($user[0]->avatar)?'public/uploads/profile/'.$user[0]->avatar:"";
		}
		$data = json_decode(json_encode($array));

		//echo "<pre>"; print_r($customers);exit;
		return view('Ca.notifications')->with([
			'title' =>$title,
			'notifications'=>$data,
			'notifications_pagination' =>$notifications_pagination,
		]);
    }

	public function clearNotification(Request $request) {

		$update = DB::table('notifications')
				->where('notifications.to_uid', $request->to_uid)
				->where('notifications.status', 1)
				->update(
					 array(
							'status' => 0,
					 )
				);
		return true;
	}
}
