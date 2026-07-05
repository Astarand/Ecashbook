<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use DB;
use Auth;
use App\Notifications;
use App\Models\Product;
use DateTime;
use Carbon\Carbon;
//echo "hello";exit;
class Helper
{
	public static function SayHello()
	{
		return "SayHello";
	}

	public static function getProfileImage()
	{
		$uid   = Auth::user()->id;
		$utype 		=   Auth::user()->u_type;
		$imageName = "";
		if ($utype == 1) {
			$imageName =  DB::table('ca_profiles')
				->select(DB::raw('ca_profiles.comp_logo'))
				->where('ca_profiles.userId', '=', $uid)
				->get();

			$imageName = (isset($imageName[0]->comp_logo) && $imageName[0]->comp_logo != "") ? $imageName[0]->comp_logo : "";
		} else if ($utype == 2) {
			$imageName =  DB::table('company_profiles')
				->select(DB::raw('company_profiles.comp_logo'))
				->where('company_profiles.userId', '=', $uid)
				->get();

			$imageName = (isset($imageName[0]->comp_logo) && $imageName[0]->comp_logo != "") ? $imageName[0]->comp_logo : "";
		}


		return $imageName;
	}

	public static function addNotification($to_uid, $noti_title, $msg, $url)
	{
		$from_uid   = Auth::user()->id;
		$utype 		=   Auth::user()->u_type;
		if ($utype == 2) {
			$caAssign =  DB::table('ca_assigns')
				->select(DB::raw('ca_assigns.ca_id'))
				//->leftJoin('users', 'users.id', '=', 'ca_assigns.comp_id')
				->where('ca_assigns.ca_id', '=', $from_uid)
				->where('ca_assigns.ca_assign_status', '=', 1)
				->get();

			$to_uid = isset($caAssign[0]->ca_id) ? $caAssign[0]->ca_id : $to_uid;
		}
		DB::table('notifications')->insert([
			'from_uid'    => $from_uid,
			'to_uid'      => $to_uid,
			'utype'       => $utype,
			'noti_title'  => $noti_title,
			'msg'         => $msg,
			'url_action'         => $url,
			'status'      => 1,
			'created_at'  => now(),
			'updated_at'  => now(),
		]);
		// Notifications::create([
		// 	'from_uid' => $from_uid,
		// 	'to_uid' => $to_uid,
		// 	'utype' => $utype,
		// 	'noti_title' => $noti_title,
		// 	'msg'  => $msg,
		// 	'url'  => $url,
		// 	'status' => 1
		// ]);

		return true;
	}

	public static function getNotification($from_uid)
	{

		$utype 		=   Auth::user()->u_type;
		$output = '';
		$data = DB::table('notifications')
			->select(DB::raw('notifications.*'))
			->where('notifications.to_uid', $from_uid)
			//->where('notifications.utype', $utype)
			->where('notifications.status', 1)
			->orderBy('created_at', 'desc')
			->limit(10)
			->get()->toArray();
		$array = array();
		foreach ($data as $k => $val) {
			$array[$val->id]['id'] = $val->id;
			$array[$val->id]['from_uid'] = $val->from_uid;
			$array[$val->id]['to_uid'] = $val->to_uid;
			$array[$val->id]['utype'] = $val->utype;
			$array[$val->id]['noti_title'] = $val->noti_title;
			$array[$val->id]['msg'] = $val->msg;
			$array[$val->id]['url_action'] = $val->url_action;
			$array[$val->id]['status'] = $val->status;
			$array[$val->id]['created_at'] = date("d M y", strtotime($val->created_at));

			if ($utype == 1) {
				$user =  DB::table('users')
					->select(DB::raw('users.name,users.avatar,company_profiles.comp_logo'))
					->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
					->where('users.id', '=', $val->from_uid)
					->get();
			} else if ($utype == 2) {
				$user =  DB::table('users')
					->select(DB::raw('users.name,users.avatar,ca_profiles.comp_logo'))
					->leftJoin('ca_profiles', 'users.id', '=', 'ca_profiles.userId')
					->where('users.id', '=', $val->from_uid)
					->get();
			}
			$array[$val->id]['name'] = isset($user[0]->name) ? $user[0]->name : "";
			$array[$val->id]['avatar'] = isset($user[0]->comp_logo) ? 'public/uploads/profile/' . $user[0]->comp_logo : "";
		}
		$data = json_decode(json_encode($array));

		return $data;
		/* foreach($data as $row)
		{
			return $row->rating;
		} */
	}

	public static function invoice_num($input, $pad_len = 7, $prefix = null)
	{
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

		if (strtotime($expiry) > strtotime($today)) {
			return true;
		} else {
			return 0;
		}
	}

	public static function check_subscriber()
	{
		if (Auth::user() && (/*Auth::user()->u_type == 1 || */Auth::user()->u_type == 2)) {
			$userId = Auth::user()->id;
			$chkUser = DB::table('users')
				->select(DB::raw('users.id,users.created_at'))
				->where('users.id', '=', Auth::user()->id)
				->where('users.u_type', '=', Auth::user()->u_type)
				->get();

			$chkSubscription = DB::table('subscribers')
				->select(DB::raw('subscribers.id,subscribers.start_at,subscribers.end_at'))
				->where('subscribers.uid', '=', Auth::user()->id)
				->where('subscribers.utype', '=', Auth::user()->u_type)
				->where('subscribers.status', '=', 1)
				->where('subscribers.payment_status', '=', "SUCCESS")
				->orderBy('subscribers.id', 'DESC')->limit(1)
				->get();

			if (count($chkSubscription) == 0) {
				//echo "Not subscribers";
				$start_at = date("d-m-Y", strtotime($chkUser[0]->created_at));
				$next_date = date('d-m-Y', strtotime($start_at . ' + 354 days'));
			} else if (count($chkSubscription) != 0) {
				//echo "is subscribers";
				$start_at = date("d-m-Y", strtotime($chkSubscription[0]->start_at));
				$end_at = date("d-m-Y", strtotime($chkSubscription[0]->end_at));
				$next_date = $end_at;
			}
			$chkDate = self::dateCompare($next_date);
			//echo "<pre>";print_r($chkUser);
			//echo "<pre>";print_r($chkSubscription);exit;
			if ($chkDate) {
				//echo "yes";
				return true;
			} else {
				//echo "no";
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
		if (Auth::user() && (Auth::user()->u_type == 1)) {
			$userId = Auth::user()->id;
			$chkUser = DB::table('users')
				->select(DB::raw('users.id,users.isCaActive'))
				->where('users.id', '=', $userId)
				->get();
			if ($chkUser[0]->isCaActive == 1) {
				return true;
			} else {
				return false;
			}
		}
		if (Auth::user() && (Auth::user()->u_type == 4)) {
			$userId = Auth::user()->id;
			$chkAddedBy = DB::table('users')
				->select(DB::raw('users.ca_add_by'))
				->where('users.id', '=', $userId)
				->where('users.u_type', '=', Auth::user()->u_type)
				->get();

			$chkUser = DB::table('users')
				->select(DB::raw('users.id,users.isCaActive'))
				->where('users.id', '=', $chkAddedBy[0]->ca_add_by)
				->get();
			if ($chkUser[0]->isCaActive == 1) {
				return true;
			} else {
				return false;
			}
		}
		return true;
	}

	public static function convert_number_to_words(float $number)
	{

		$number = str_replace("-", "", $number); //remove negative

		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(
			0 => '',
			1 => 'one',
			2 => 'two',
			3 => 'three',
			4 => 'four',
			5 => 'five',
			6 => 'six',
			7 => 'seven',
			8 => 'eight',
			9 => 'nine',
			10 => 'ten',
			11 => 'eleven',
			12 => 'twelve',
			13 => 'thirteen',
			14 => 'fourteen',
			15 => 'fifteen',
			16 => 'sixteen',
			17 => 'seventeen',
			18 => 'eighteen',
			19 => 'nineteen',
			20 => 'twenty',
			30 => 'thirty',
			40 => 'forty',
			50 => 'fifty',
			60 => 'sixty',
			70 => 'seventy',
			80 => 'eighty',
			90 => 'ninety'
		);
		$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		while ($i < $digits_length) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
			} else $str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	}

	//Start send mail
	public static function emailTemplate($data)
	{
		$title = $data['title'];
		$subject = $data['subject'];
		$name = $data['comp_name'] ?? $data['name'];
		$email = $data['comp_email'] ?? $data['email'];
		$msg = $data['msg'];
		$files = $data['files'] ?? "";

		$body = '<html lang="en">
					<head>
					<title>' . $title . '</title>
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					</head>
					<body>
					<div>
						<h1>' . $subject . '</h1>
						<p>Dear ' . $name . ',</p>
						<p>' . $msg . '</p>
						<footer>Copyright © ' . date("Y") . ' E-cashbook</footer>
					</div>
					</body>
				 </html>';

		$data_email = [
			'email' => $email
		];

		return self::emailSendFunc($body, $data_email, $subject, $files);
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
	
	//compliance notifications
	/**
	 * Insert compliance reminder notification if not exists
	 *
	 * @param int    $formId
	 * @param string $formName
	 * @param int|null $reminderDay
	 * @param int|null $reminderMonth
	 * @param string $reminderYearType (current|next)
	 * @param string $url
	 * @return void
	 */
	
	private static function resolveMonthYear($form, $type)
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
	
	public static function insertComplianceReminderNotifications()
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

			[$remMonth, $remYear] = self::resolveMonthYear($form, 'reminder');

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

			[$dueMonth, $dueYear] = self::resolveMonthYear($form, 'due');

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
	}
	
	public static function generateProdId($uid, $type)
	{
		// Decide prefix
		$prefix = ($type === 'product') ? 'PRO' : 'SER';

		// Find last ID
		$last = Product::where('prodId', 'LIKE', $prefix . $uid . '-%')
						->orderBy('prodId', 'desc')
						->first();

		if ($last && preg_match("/{$prefix}{$uid}-(\d+)/", $last->prodId, $m)) {
			$new = intval($m[1]) + 1;
		} else {
			$new = 1;
		}

		return $prefix . $uid . '-' . str_pad($new, 5, '0', STR_PAD_LEFT);
	}
	
	public static function createProductService(array $data)
    {
		//echo "<pre>";print_r($data);exit;
		$uid = Auth::user()->id;
        $item =  Product::create([
            'added_by' => $uid,
            'item_type' => $data['item_type'],
			'sac_code' => ($data['sac_code'] && ($data['item_type']=='service'))?$data['sac_code']:"",
            'hsn_code' => ($data['hsn_code'] && ($data['item_type']=='product'))?$data['hsn_code']:"",
			'gst_rate' => ($data['item_type']=='product')? $data['gst_rate_prod']:$data['gst_rate_service'],
			'item_name' =>$data['item_name'],
			'service_name' =>$data['service_name'],
			'gov_pay' => isset($data['gov_pay'])?$data['gov_pay']:0,
			'ser_pay' => isset($data['ser_pay'])?$data['ser_pay']:0,
            'opening_stock_bal' => isset($data['opening_stock_bal'])?$data['opening_stock_bal']:0,
            'selling_price' => $data['selling_price'],
			'ser_selling_price' => $data['ser_selling_price'],
			'base_unit'=>$data['base_unit'],
            'disc_sell' => isset($data['disc_sell'])?$data['disc_sell']:"",
			'ser_disc_sell' => isset($data['ser_disc_sell'])?$data['ser_disc_sell']:"",
			'purchase_price'=>isset($data['purchase_price'])?$data['purchase_price']:0,
            'disc_sell_type' => $data['disc_sell_type'],
			'ser_disc_sell_type' => $data['ser_disc_sell_type'],
            'prod_desc' => isset($data['prod_desc'])?$data['prod_desc']:"",
			'ser_desc' => isset($data['ser_desc'])?$data['ser_desc']:"",
            'prod_image' => "",
			'ser_image' => isset($data['ser_image'])?$data['ser_image']:"",
			'created_at' => date('Y-m-d H:i:s'),
        ]);

		$item->prodId = self::generateProdId($uid, $data['item_type']);
		$item->save();
		return $item;
    }


}
