<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Gst_logins;
use DB;
use DateTime;
use Carbon\Carbon;

class TokenController extends Controller
{
    /**
     * Get all users with pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateRefreshToken(): JsonResponse
    {
        try {
            $users = DB::table('gst_logins')->get();
			//echo "<pre>";print_r($users);exit;
			foreach ($users as $user) {
				$url = '';
				$client_id = '';
				$client_secret = '';
				$txn = $user->txn;
				$email = config('custom.COMP_EMAIL');
				$state_cd = substr($this->getGstNo($user->user_id), 0, 2);
				$gst_username = $user->gst_username;//'Pro_2024';
				$ip_address = '127.0.0.1';
				if (app()->environment('production')) {
					$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/authentication/refreshtoken?email=$email";
					$client_id = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
					$client_secret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
				} else {
					$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/authentication/refreshtoken?email=$email";
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
											->where('gst_username', $user->gst_username)
											//->where('app_env', env('APP_ENV'))
											->update(
												array(
													'txn' => $response['header']['txn'],
													'otp'=>1,
													'created_at' => now(),
													'updated_at' => now(),
												)
											);
						
					}else{
						
					}
				}
				
			}

			return response()->json([
				'success' => true,
				'message' => 'Refresh token updated successfully'
			], 200);
			

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getGstNo($userId){
		$getUserData =  DB::table('company_profiles')
						->select(DB::raw('company_profiles.gst_no as gst_no'))
						->where('company_profiles.userId', '=', $userId)
						->get();
		$array = array();
		foreach($getUserData as $k=>$val)
		{				
			$array['gst_no'] = ($val->gst_no !="")?$val->gst_no:"";
			
		}
		$getUserData = json_decode(json_encode($array));
		$gstin = $getUserData->gst_no;
		return $gstin;
	}
	
	public function sendComplianceReminderNotifications(): JsonResponse
    {
		
		$today = Carbon::today()->startOfDay();

		$forms = DB::table('compliance_forms')
			->where('reminderStatus', 1)
			->get();

		foreach ($forms as $form) {

			/* ============================
			   REMINDER DATE
			============================ */
			if (empty($form->reminder_day)) continue;

			[$remMonth, $remYear] = $this->resolveMonthYear($form, 'reminder');

			if (empty($remMonth)) continue;

			$reminderDate = Carbon::createFromDate(
				$remYear,
				$remMonth,
				$form->reminder_day
			)->startOfDay();

			/* ============================
			   DUE DATE
			============================ */
			if (empty($form->due_day)) continue;

			[$dueMonth, $dueYear] = $this->resolveMonthYear($form, 'due');

			if (empty($dueMonth)) continue;

			$dueDate = Carbon::createFromDate(
				$dueYear,
				$dueMonth,
				$form->due_day
			)->endOfDay();

			/* ============================
			   EXCEL LOGIC (FROM TABLE):
			   Trigger from reminder → due
			============================ */
			if (!$today->between($reminderDate, $dueDate, true)) {
				continue;
			}

			/* ============================
			   CA ASSIGNMENTS
			============================ */
			$assignments = DB::table('ca_assigns')
				->where('ca_assign_status', 1)
				->where('ca_current_status', 1)
				->get();

			foreach ($assignments as $assign) {

				$title = 'Compliance Reminder: ' . $form->form_name;

				$exists = DB::table('notifications')
					->where('from_uid', $assign->ca_id)
					->where('to_uid', $assign->comp_id)
					->where('noti_title', $title)
					->whereDate('created_at', $today)
					->exists();

				if ($exists) continue;

				DB::table('notifications')->insert([
					'from_uid'   => $assign->ca_id,
					'to_uid'     => $assign->comp_id,
					'utype'      => $assign->utype,
					'noti_title' => $title,
					'msg'        => "Reminder: {$form->form_name} compliance is pending. Due on {$dueDate->format('d M Y')}.",
					'status'     => 1,
					'created_at' => now(),
					'updated_at' => now(),
				]);
			}
		}
		
		return response()->json([
				'success' => true,
				'message' => 'Notification send successfully'
			], 200);
	}
	
	private function resolveMonthYear($form, $type)
	{
		$month = $form->{$type . '_month'};
		$year  = now()->year;

		if ($form->{$type . '_year_type'} === 'next') {
			$year++;
		}

		switch ($form->frequency) {

			case 'monthly':
				// month not stored → current month
				$month = now()->month;
				break;

			case 'quarterly':
				if (empty($month)) {
					$q = now()->quarter;
					$map = [1 => 3, 2 => 6, 3 => 9, 4 => 12];
					$month = $map[$q];
				}
				break;

			case 'half-yearly':
				if (now()->month <= 6) {
					$month = 6;
				} else {
					$month = 12;
				}
				break;

			case 'annual':
				// month already stored → use directly
				break;
		}

		return [$month, $year];
	}
}