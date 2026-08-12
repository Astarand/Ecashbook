<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DB;
use Auth;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Models\Sales;
use App\Models\Sales_values;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use App\Models\Gst_logins;
use Helper;
use DateTime;
use DatePeriod;
use DateInterval;
class GstService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $apiKey;
    // possibly certificate paths etc.

    public function __construct()
    {
		if (app()->environment('production')) {
			$this->baseUrl = config('custom.MASTERSINDIA_PROD_BASEURL');
			$this->clientId = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
			$this->clientSecret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
			$this->email = config('custom.COMP_EMAIL');
		}else{
			$this->baseUrl = config('custom.MASTERSINDIA_BASEURL');
			$this->clientId = config('custom.MASTERSINDIA_CLIENT_ID');
			$this->clientSecret = config('custom.MASTERSINDIA_CLIENT_SECRET');
			$this->email = config('custom.COMP_EMAIL');
		}
    }

    public function gstPaidByComp($startDate, $endDate, $uid)
	{
		$gstReturns = DB::table('gst_returns')
			->where('userid', $uid)
			->where('ret_type', 'gstr3b')
			->whereBetween('posted_date', [$startDate, $endDate])
			->whereNotNull('ack_num')
			->where('ack_num', '!=', '')
			->get();

		$gstPaid = 0;

		foreach ($gstReturns as $gstReturn) {
			$reqData = json_decode($gstReturn->req_data, true);

			foreach ($reqData['tx_pmt']['pdcash'] ?? [] as $payment) {
				$gstPaid += (float) ($payment['ipd'] ?? 0);
				$gstPaid += (float) ($payment['cpd'] ?? 0);
				$gstPaid += (float) ($payment['spd'] ?? 0);
				$gstPaid += (float) ($payment['cspd'] ?? 0);
			}
		}
		
		return $gstPaid;

	}
	
}
