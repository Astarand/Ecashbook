<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

use DB;
use Auth;
use App\Models\Notifications;
use DateTime;

class Helper{
    public static function SayHello()
    {
        return "SayHello";
    }

	public static function addNotification($to_uid,$noti_title,$msg,$url)
    {
		$from_uid   = Auth::user()->id;
		$utype 		=   Auth::user()->u_type;
		if($utype == 2)
		{
			$caAssign =  DB::table('ca_assigns')
								->select(DB::raw('ca_assigns.ca_id'))
								//->leftJoin('users', 'users.id', '=', 'ca_assigns.comp_id')
								->where('ca_assigns.ca_id','=',$from_uid)
								->where('ca_assigns.ca_assign_status','=',1)
								->get();

			$to_uid = isset($caAssign[0]->ca_id)?$caAssign[0]->ca_id:$to_uid;
		}
        Notifications::create([
			'from_uid' => $from_uid,
			'to_uid' => $to_uid,
			'utype' => $utype,
			'noti_title' => $noti_title,
			'msg'  => $msg,
			'url'  => $url,
			'status' => 1
		]);

		return true;
    }

	public static function getNotification($from_uid) {

		$utype 		=   Auth::user()->u_type;
		$output = '';
		$data = DB::table('notifications')
			->select(DB::raw('notifications.*'))
			->where('notifications.to_uid', $from_uid)
			//->where('notifications.utype', $utype)
			->where('notifications.status', 1)
			->orderBy('created_at','desc')
			->limit(10)
			->get()->toArray();
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

			if($utype ==1){
				$user =  DB::table('users')
							->select(DB::raw('users.name,users.avatar,company_profiles.comp_logo'))
							->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
							->where('users.id', '=', $val->from_uid)
							->get();

			}else if($utype ==2){
				$user =  DB::table('users')
							->select(DB::raw('users.name,users.avatar,ca_profiles.comp_logo'))
							->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
							->where('users.id', '=', $val->from_uid)
							->get();
			}
			$array[$val->id]['name'] = isset($user[0]->name)?$user[0]->name:"";
			$array[$val->id]['avatar'] = isset($user[0]->comp_logo)?'public/uploads/profile/'.$user[0]->comp_logo:"";
		}
		$data = json_decode(json_encode($array));

		return $data;
		/* foreach($data as $row)
		{
			return $row->rating;
		} */
	}

	public static function invoice_num ($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}


	public static function dateCompare($toBeComparedDate)
    {
        //$toBeComparedDate = '2014-08-12';
		$today = (new DateTime())->format('d-m-Y');
		$expiry = (new DateTime($toBeComparedDate))->format('d-m-Y');

		if(strtotime($expiry) > strtotime($today)){
			return true;
		}else{
			return 0;
		}
    }

	public static function check_subscriber(){
		if(Auth::user() && (/*Auth::user()->u_type == 1 || */Auth::user()->u_type == 2)){
			$userId = Auth::user()->id;
			$chkUser = DB::table('users')
					->select(DB::raw('users.id,users.created_at'))
					->where('users.id','=',Auth::user()->id)
					->where('users.u_type','=',Auth::user()->u_type)
					->get();

			$chkSubscription = DB::table('subscribers')
					->select(DB::raw('subscribers.id,subscribers.start_at,subscribers.end_at'))
					->where('subscribers.uid','=',Auth::user()->id)
					->where('subscribers.utype','=',Auth::user()->u_type)
					->where('subscribers.status','=',1)
					->where('subscribers.payment_status','=',"SUCCESS")
					->orderBy('subscribers.id', 'DESC')->limit(1)
					->get();

			if(count($chkSubscription) == 0){
				$start_at = date("d-m-Y",strtotime($chkUser[0]->created_at));
				$next_date = date('d-m-Y', strtotime($start_at. ' + 15 days'));

			}else if(count($chkSubscription) != 0){
				$start_at = date("d-m-Y",strtotime($chkSubscription[0]->start_at));
				$end_at = date("d-m-Y",strtotime($chkSubscription[0]->end_at));
				$next_date = $end_at;
			}
			$chkDate = self::dateCompare($next_date);
			if($chkDate){
				return true;
			}else{
				$update = DB::table('users')
					->where('id', $userId)
					->update(
						array(
							'isCaActive' => 0,
						)
					);
				return 0;
			}
		}
		if(Auth::user() && (Auth::user()->u_type == 1)){
			$userId = Auth::user()->id;
			$chkUser = DB::table('users')
					->select(DB::raw('users.id,users.isCaActive'))
					->where('users.id','=',$userId)
					->get();
			if($chkUser[0]->isCaActive == 1){
				return true;
			}else{
				return false;
			}
		}
		if(Auth::user() && (Auth::user()->u_type == 4)){
			$userId = Auth::user()->id;
			$chkAddedBy = DB::table('users')
					->select(DB::raw('users.ca_add_by'))
					->where('users.id','=',$userId)
					->where('users.u_type','=',Auth::user()->u_type)
					->get();

			$chkUser = DB::table('users')
					->select(DB::raw('users.id,users.isCaActive'))
					->where('users.id','=',$chkAddedBy[0]->ca_add_by)
					->get();
			if($chkUser[0]->isCaActive == 1){
				return true;
			}else{
				return false;
			}
		}
		return true;
	}

	public static function convert_number_to_words(float $number) {

		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(0 => '', 1 => 'one', 2 => 'two',
			3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
			7 => 'seven', 8 => 'eight', 9 => 'nine',
			10 => 'ten', 11 => 'eleven', 12 => 'twelve',
			13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
			16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
			19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
			40 => 'forty', 50 => 'fifty', 60 => 'sixty',
			70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
		$digits = array('', 'hundred','thousand','lakh', 'crore');
		while( $i < $digits_length ) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
			} else $str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	}

	//Start send mail
	public static function emailTemplate($data) {
		$title = $data['title'];
		$subject = $data['subject'];
		$name = isset($data['comp_name'])?$data['comp_name']:$data['name'];
		$email = isset($data['comp_email'])?$data['comp_email']:$data['email'];
		$msg = $data['msg'];
		$files = isset($data['files'])?$data['files']:"";
		$body = '<html lang="en">
					<head>
					<title>'.$title.'</title>
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
							<h1 style="color: #1fa8b8;font-size: 50px;text-align: center;margin-bottom: 0;">'.$subject.'</h1>
							<div style="width: 141px;background: #f57e20;height: 2px;margin: 8px auto 0;"></div>
						</div>
						<div class="content-wraper" style="margin-top: 50px;display: block;padding: 0 30px;">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr>
									<td align="left" style="padding-bottom: 20px;"><b>Dear '.$name.',</b></td>
								</tr>

								<tr>
									<td style="padding-bottom: 5px;"><p style="text-align: left;margin: 0;font-weight:600;">'.$msg.'</p></td>
								</tr>

								<tr>
									<td style="padding-bottom: 5px;">
									<p style="text-align: left;margin: 0;font-weight:600;">

									</p>
									</td>
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
		//echo $body;exit;
		$data_email = [
			'email' => $email
		];
		$sendMail = self::emailSendFunc($body,$data_email,$subject,$files);
		return $sendMail;
	}

	public static function emailSendFunc($body, $data_email, $subject, $files = null)
	{
		Mail::send([], [], function ($message) use ($body, $data_email, $subject, $files) {
			$message->to($data_email['email'])
				->subject($subject)
				->html($body); // ✅ Use html() instead of setBody()

			// Attach files if available
			if (!empty($files)) {
				foreach ($files as $file) {
					$message->attach($file);
				}
			}
		});

		return true; // Return success (modify as needed)
	}

	// public static function emailSendFunc($body,$data_email,$subject,$files=null) {
	// 	$url = url()->current();
	// 	if (str_contains($url, 'localhost')) {
	// 		Mail::send([], [], function ($message) use ($body,$data_email,$subject,$files) {
	// 		    $message->to($data_email['email'])
	// 			->subject($subject)
	// 			->from(env('MAIL_FROM_ADDRESS'))
	// 			->setBody($body, 'text/html');
	// 			if(isset($files) && $files!="") {
	// 				$message->attach($files->getRealPath(), array(
	// 						'as' => $files->getClientOriginalName(),
	// 						'mime' => $files->getMimeType())
	// 				);
	// 			}
	// 		});
	// 	}else{
	// 		Mail::send([], [], function ($message) use ($body,$data_email,$subject,$files) {
	// 		  $message->to($data_email['email'])
	// 			->subject($subject)
	// 			->from(env('MAIL_FROM_ADDRESS'))
	// 			->setBody($body, 'text/html');
	// 			if(isset($files) && $files!="") {
	// 				$message->attach($files->getRealPath(), array(
	// 						'as' => $files->getClientOriginalName(),
	// 						'mime' => $files->getMimeType())
	// 				);
	// 			}
	// 		});
	// 	}
	// 	return true;
	// }
	//End send mail

	//Start Setup GST API implementation
	public static function getClientIp(){
		$clientIP = $_SERVER['HTTP_CLIENT_IP']
					?? $_SERVER["HTTP_CF_CONNECTING_IP"] # when behind cloudflare
					?? $_SERVER['HTTP_X_FORWARDED']
					?? $_SERVER['HTTP_X_FORWARDED_FOR']
					?? $_SERVER['HTTP_FORWARDED']
					?? $_SERVER['HTTP_FORWARDED_FOR']
					?? $_SERVER['REMOTE_ADDR']
					?? '0.0.0.0';

		# Earlier than PHP7
		$clientIP = '0.0.0.0';

		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$clientIP = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			# when behind cloudflare
			$clientIP = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
			$clientIP = $_SERVER['HTTP_X_FORWARDED'];
		} elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$clientIP = $_SERVER['HTTP_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_FORWARDED'])) {
			$clientIP = $_SERVER['HTTP_FORWARDED'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$clientIP = $_SERVER['REMOTE_ADDR'];
		}

		return $clientIP;
	}

	public static function get_otprequest(array $params = [],array $headers = [])
    {
        $baseUrl = config('custom.MASTERSINDIA_BASEURL').'authentication/otprequest';
		$headers["client_id"] = config('custom.MASTERSINDIA_CLIENT_ID');
		$headers["client_secret"] = config('custom.MASTERSINDIA_CLIENT_SECRET');
		$headers["accept"] = '*/*';
		return $response = Http::withHeaders($headers)->get($baseUrl, $params);
    }

	public static function get_authtoken(array $params = [],array $headers = [])
    {
        $baseUrl = config('custom.MASTERSINDIA_BASEURL').'authentication/authtoken';
		$headers["client_id"] = config('custom.MASTERSINDIA_CLIENT_ID');
		$headers["client_secret"] = config('custom.MASTERSINDIA_CLIENT_SECRET');
		$headers["accept"] = '*/*';
		return $response = Http::withHeaders($headers)->get($baseUrl, $params);
		$res = json_decode($response);
    }

	public static function get_refreshtoken(array $params = [],array $headers = [])
    {
        $baseUrl = config('custom.MASTERSINDIA_BASEURL').'authentication/refreshtoken';
		$headers["client_id"] = config('custom.MASTERSINDIA_CLIENT_ID');
		$headers["client_secret"] = config('custom.MASTERSINDIA_CLIENT_SECRET');
		$headers["accept"] = '*/*';
		 return $response = Http::withHeaders($headers)->get($baseUrl, $params);
    }


	public static function getAuthToken(array $params = [], array $headers = [])
    {
        $url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . '/authentication/authtoken';

        $headers["client_id"]     = config('custom.MASTERSINDIA_CLIENT_ID');
        $headers["client_secret"] = config('custom.MASTERSINDIA_CLIENT_SECRET');
        $headers["accept"]        = 'application/json';
        $headers["Content-Type"]  = 'application/json';

        $response = Http::withHeaders($headers)->get($url, $params);
		//dd($response->json());

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data['auth_token'])) {
                // Save token in Cache
                Cache::put('gst_auth_token', $data['auth_token'], now()->addMinutes(55));
                Cache::put('gst_refresh_token', $data['refresh_token'] ?? null, now()->addDays(1));
            }

            return $data;
        }

        return [
            'error'   => true,
            'message' => $response->body()
        ];
    }



	public static function refreshAuthToken()
    {
        $url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . '/authentication/refreshToken';

        $refreshToken = Cache::get('gst_refresh_token');

        if (!$refreshToken) {
            return [
                'error'   => true,
                'message' => 'Refresh token missing. Please authenticate again with OTP.'
            ];
        }

        $headers = [
            "client_id"     => config('custom.MASTERSINDIA_CLIENT_ID'),
            "client_secret" => config('custom.MASTERSINDIA_CLIENT_SECRET'),
            "accept"        => 'application/json',
            "Content-Type"  => 'application/json'
        ];

        $response = Http::withHeaders($headers)->post($url, [
            "refresh_token" => $refreshToken
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data['auth_token'])) {
                Cache::put('gst_auth_token', $data['auth_token'], now()->addMinutes(55));
            }

            return $data;
        }

        return [
            'error'   => true,
            'message' => $response->body()
        ];
    }


	public static function requestOtp($email)
    {
        $url = "https://apisandbox.whitebooks.in/authentication/otprequest?email=" . urlencode($email);

        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body()
        ];
    }

	public static function verifyOtp(array $params, array $headers)
    {
        $url = "https://apisandbox.whitebooks.in/authentication/authtoken";

        $headers["accept"] = "application/json";
        $headers["Content-Type"] = "application/json";

        $response = Http::withHeaders($headers)->post($url, $params);

        if ($response->successful()) {
            $data = $response->json();

            // Save tokens
            Session::put('gst_auth_token', $data['auth_token'] ?? null);
            Session::put('gst_refresh_token', $data['refresh_token'] ?? null);

            return $data;
        }
		return [
			'error'   => true,
			'message' => $response->body()
		];
	}


	public static function getHSNDetails($hsnCode)
    {
        $url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/public/hsn/$hsnCode";

        $token = Cache::get('gst_auth_token');

        if (!$token) {
            return [
                'error'   => true,
                'message' => 'Auth token missing. Please authenticate first.'
            ];
        }

        $headers = [
            'Authorization' => "Bearer $token",
            'accept'        => 'application/json',
        ];

        $response = Http::withHeaders($headers)->get($url);

        if ($response->status() === 401) {
            // Token expired → refresh
            $refresh = self::refreshAuthToken();
            if (!empty($refresh['auth_token'])) {
                $headers['Authorization'] = "Bearer " . $refresh['auth_token'];
                $response = Http::withHeaders($headers)->get($url);
            }
        }

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error'   => true,
            'message' => $response->body()
        ];
    }


	public static function get_gstr1(array $params = [],array $headers = [])
    {
        $baseUrl = config('custom.MASTERSINDIA_BASEURL').'gstr1/b2b';
		$headers["client_id"] = config('custom.MASTERSINDIA_CLIENT_ID');
		$headers["client_secret"] = config('custom.MASTERSINDIA_CLIENT_SECRET');
		$headers["accept"] = '*/*';
		 return $response = Http::withHeaders($headers)->get($baseUrl, $params);

    }
	
	public static function hasProprietorship()
	{
		$userId = currentOwnerId();

		return DB::table('proprietorship_profiles')
			->where('userId', $userId)
			->exists();
	}
}


