<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

use DB;
use Auth;
use App\Models\Notifications;
use App\Models\Audit_logs;
use DateTime;

class AuditLogger{
    

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
	
	//Add log entry for every users
	public static function logEntry(string $action,string $module,string $description = null,array $oldData = null,array $newData = null) 
	{

        Audit_logs::create([
            'user_id'    => Auth::user()->id ?? null,
            'user_type'  => Auth::user()->u_type ?? null, 

            'action'     => $action,
            'module'     => $module,
            'description'=> $description,

            'url'        => request()->fullUrl(),
            'method'     => request()->method(),
            'ip'         => self::getClientIp(),
            'user_agent' => '',//request()->userAgent(),

            'old_data'   => $oldData ? json_encode($oldData) : null,
            'new_data'   => $newData ? json_encode($newData) : null,
        ]);
    }
	

}


