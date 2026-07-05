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
class WhiteBooksGstService
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

    protected function getHeaders()
    {
        // Example headers — these will depend on spec
        return [
            /*'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Client-Id' => $this->clientId,
            'X-Client-Secret' => $this->clientSecret,
            'Authorization' => "Bearer " . $this->apiKey,*/
			'accept' => '*/*',
			'client_id'    => $this->clientId,
			'client_secret'=> $this->clientSecret,
        ];
    }

    public function getGstinProfile(string $gstin)
    {
        $url = $this->baseUrl . 'public/search';

        $response = Http::withHeaders($this->getHeaders())
            ->get($url, [
				'gstin' => $gstin,
				'email' => $this->email,
			]);

        if ($response->failed()) {
            Log::error("WhiteBooks GSTIN profile fetch failed", [
                'gstin' => $gstin,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to fetch GSTIN profile");
        }

        $data = $response->json();
        // Map or return as needed
		//echo "<pre>";print_r($data);
        return $data;
    }

    public function getReturnStatus(string $gstin, string $period, string $returnType)
    {
        $url = $this->baseUrl . 'public/rettrack';

        $response = Http::withHeaders($this->getHeaders())
            ->get($url, [
				'gstin' => $gstin,
				'fy' => $period,
				'email' => $this->email,
			]);

        if ($response->failed()) {
            Log::error("WhiteBooks GST return status fetch failed", [
                'gstin' => $gstin,
                'period' => $period,
                'returnType' => $returnType,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to fetch return status");
        }

        $data = $response->json();
        return $data;
    }

	protected function getReportHeaders($gst_username, $state_cd, $ip_address, $txn)
    {
        return [
			'accept' => '*/*',
			'gst_username'    => $gst_username,
			'state_cd'    => $state_cd,
			'ip_address'    => $ip_address,
			'txn'    => $txn,
			'client_id'    => $this->clientId,
			'client_secret'=> $this->clientSecret,
        ];
    }
	public function getGstReportService(string $gstin, string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,String $grandchildRepType)
    {
		$url = "";
		$returnPeriod = "";
		$fromtime = '';
		$rectype  = '';
		$fromtime_key = '';
		$rectype_key  = '';
		if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="b2b"){
			$url = $this->baseUrl . 'gstr1/b2b';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="b2cl"){
			$url = $this->baseUrl . 'gstr1/b2cl';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="b2cs"){
			$url = $this->baseUrl . 'gstr1/b2cs';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="cdnr"){
			$url = $this->baseUrl . 'gstr1/cdnr';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="cdnur"){
			$url = $this->baseUrl . 'gstr1/cdnur';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="exp"){
			$url = $this->baseUrl . 'gstr1/exp';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="at"){
			$url = $this->baseUrl . 'gstr1/at';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="atadj"){
			$url = $this->baseUrl . 'gstr1/ata';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="nil"){
			$url = $this->baseUrl . 'gstr1/nil';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr1" && $grandchildRepType=="hsn"){
			$url = $this->baseUrl . 'gstr1/hsnsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr3b_outward" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="outward_supplies" && $childRepType =="gstr9_sales" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr9/getdet';
			$returnPeriod = 'retperiod';
		}


		else if($mainRepType =="inward_supplies" && $childRepType =="gstr2a" && $grandchildRepType=="b2b_invoices"){
			$url = $this->baseUrl . 'gstr2a/b2b';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2a" && $grandchildRepType=="cdns_received"){
			$url = $this->baseUrl . 'gstr2a/cdn';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2a" && $grandchildRepType=="isd_credits"){
			$url = $this->baseUrl . 'gstr2a/isd';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2a" && $grandchildRepType=="import_goods"){
			$url = $this->baseUrl . 'gstr2a/impgsez';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2a" && $grandchildRepType=="tds_tcs_credits"){
			$url = $this->baseUrl . 'gstr2a/tds';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2b" && $grandchildRepType=="eligible_itc"){
			//$url = $this->baseUrl . 'gstr2b/all';
			//$returnPeriod = 'rtnprd';
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2b" && $grandchildRepType=="ineligible_itc"){
			//$url = $this->baseUrl . 'gstr2b/all';
			//$returnPeriod = 'rtnprd';
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr2b" && $grandchildRepType=="blocked_itc"){
			//$url = $this->baseUrl . 'gstr2b/all';
			//$returnPeriod = 'rtnprd';
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr3b_itc" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="inward_supplies" && $childRepType =="gstr9_purchase" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr9/getdet';
			$returnPeriod = 'retperiod';
		}


		else if($mainRepType =="tax_payment" && $childRepType =="gstr3b_tax" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="tax_payment" && $childRepType =="pmt06" && $grandchildRepType==""){
			$url = $this->baseUrl . 'payment/chllnsum';
			$returnPeriod = 'cpin'; // "payment/chllnsum"=>"cpin" we need to use, not returnPeriod
		}else if($mainRepType =="tax_payment" && $childRepType =="drc03" && $grandchildRepType==""){
			$url = $this->baseUrl . 'payment/chllnsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="tax_payment" && $childRepType =="gstr9c" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr9c/retsum';
			$returnPeriod = 'retperiod';
		}

		else if($mainRepType =="filing_status" && $childRepType =="gstr1_status" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr/rettrack';
			$returnPeriod = 'returnperiod';
		}else if($mainRepType =="filing_status" && $childRepType =="gstr3b_status" && $grandchildRepType==""){
			//$url = $this->baseUrl . 'gstr3b/retsum';
			//$returnPeriod = 'retperiod';
			$url = $this->baseUrl . 'public/rettrack';
			$returnPeriod = 'fy';
		}else if($mainRepType =="filing_status" && $childRepType =="gstr4_status" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr4annual/getsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="filing_status" && $childRepType =="cmp08_status" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr4annual/tdscmp';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="filing_status" && $childRepType =="gstr9_status" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr9c/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="filing_status" && $childRepType =="gstr10_status" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr9c/retsum';
			$returnPeriod = 'retperiod';
		}

		else if($mainRepType =="composition" && $childRepType =="cmp08" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr8/retsum';
			$returnPeriod = 'retperiod';
			$fnl  = 'Y';
		}else if($mainRepType =="composition" && $childRepType =="gstr4" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr4/retsum';
			$returnPeriod = 'retperiod';
		}

		else if($mainRepType =="tds_tcs" && $childRepType =="gstr7" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr7/tds';
			$returnPeriod = 'retperiod';
			$fromtime_key = 'fromtime';
			$rectype_key  = 'rectype';
			$fromtime = '31-03-2023 11:23'; //'14-05-2018 11:23';
			$rectype  = 'TDS';		//TDS/TDSA

		}else if($mainRepType =="tds_tcs" && $childRepType =="gstr8" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr8/tcs';
			$returnPeriod = 'retperiod';
			$fromtime_key = 'fromtime';
			$rectype_key = 'rettype';
			$fromtime = '31-03-2023 11:23';
			$rectype  = 'TCS';
		}

		else if($mainRepType =="isd" && $childRepType =="gstr6" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr6/isd';
			$returnPeriod = 'retperiod';
		}

		else if($mainRepType =="job_work" && $childRepType =="itc04" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr4/retsum';
			$returnPeriod = 'retperiod';
		}

		else if($mainRepType =="other_returns" && $childRepType =="gstr5" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr5/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="other_returns" && $childRepType =="gstr5a" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr5/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="other_returns" && $childRepType =="gstr10" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr5/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="other_returns" && $childRepType =="gstr11" && $grandchildRepType==""){
			$url = $this->baseUrl . 'gstr5/retsum';
			$returnPeriod = 'retperiod';
		}

		//send reurest to whitebook
		if($mainRepType =="tds_tcs" && ($childRepType =="gstr7" || $childRepType =="gstr8") && $grandchildRepType==""){
			$response = Http::withHeaders($this->getReportHeaders($gst_username, $state_cd, $ip_address, $txn))
            ->get($url, [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'email' => $this->email,
				$fromtime_key => $fromtime,
				$rectype_key => $rectype,
			]);
		}else if($mainRepType =="filing_status" && $childRepType =="gstr3b_status" && $grandchildRepType==""){
			$lastFourChars = substr($period, -4);
			$fy = $lastFourChars + 1;
			$lastTwo = substr($fy, -2);
			$financialYear = $lastFourChars.'-'.$lastTwo;
			$response = Http::withHeaders($this->getHeaders())
            ->get($url, [
				'gstin' => $gstin,
				'fy' => $financialYear, //'2023-24',
				'email' => $this->email,
			]);
		}else{
        $response = Http::withHeaders($this->getReportHeaders($gst_username, $state_cd, $ip_address, $txn))
            ->get($url, [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'email' => $this->email,
			]);
		}

        if ($response->failed()) {
            Log::error("WhiteBooks GST return status fetch failed", [
                'gstin' => $gstin,
                'period' => $period,
                'returnType' => $returnType,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to fetch return status");
        }

        $data = $response->json();
        return $data;
    }


	public function getGstReturnService($gstin,$period,$gst_username,$state_cd,$ip_address,$txn,$mainReportType,$childReportType,$isNilReturn)
    {
		$url = "";
		$returnPeriod = "";
		$fromtime = '';
		$rectype  = '';
		$fromtime_key = '';
		$rectype_key  = '';
		if($mainReportType =="gstr3b"){
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}
        $response = Http::withHeaders($this->getReportHeaders($gst_username, $state_cd, $ip_address, $txn))
            ->get($url, [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'email' => $this->email,
			]);


        if ($response->failed()) {
            Log::error("WhiteBooks GST return status fetch failed", [
                'gstin' => $gstin,
                'period' => $period,
                'returnType' => $returnType,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to fetch return status");
        }

        $data = $response->json();
        return $data;
    }

	protected function submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn)
    {
        $res =  [
			'accept' => '*/*',
			'gst_username'    => $gst_username,
			'gstin' => $gstin,
			$returnPeriod => $period,
			'state_cd'    => $state_cd,
			'ip_address'    => $ip_address,
			'txn'    => $txn,
			'client_id'    => $this->clientId,
			'client_secret'=> $this->clientSecret,
        ];
		return $res;
    }
	//Step-2 : Save GSTR1 data
	public function submitGstReturnsService($merged, string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,$isNilReturn)
    {
		//echo "<pre>";print_r(json_encode($merged));exit;
		$url = "";
		$returnPeriod = "";
		$type = "";
		$isNil = ($isNilReturn=="true")?"Y":"N";
		$body = ($merged);
		$status = 0;
		$referenceId = "";
		//echo $isNil;echo $mainRepType;exit;
		if($mainRepType =="gstr1" && $isNil=="Y")
		{
			$type = "GSTR1";
			$responseData = $this->createRequestRespone($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
			$res = json_decode($responseData, true);
			if (isset($res['status_cd']) && ($res['status_cd'] === '1' || ($res['status_cd'] === '0' && $res['error']['error_cd'] === 'RET13510'))) {
				$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
				return $resOtpEVC;
			}
			$data = $responseData->json();
			return $data;
			/*$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			return $resOtpEVC;*/
		}
		else if($mainRepType =="gstr1" && $isNil=="N")
		{
			$url = $this->baseUrl . 'gstr1/retsave';
			$returnPeriod = 'ret_period';
			$type = "GSTR1";
			$query = [
				'email' => $this->email,
			];
			$response1 = Http::withHeaders($this->submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
				->put($url . '?' . http_build_query($query), $body);
			$resp1 = json_decode($response1, true);
			//echo "<pre>";print_r($resp1);exit;
			if (isset($resp1['status_cd']) && $resp1['status_cd'] === '1') {
				$status = $resp1['status_cd'];
				$referenceId = $resp1['data']['reference_id'] ?? $this->getLatestRefId($mainRepType, $period);

				$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,$status,"",$referenceId, json_encode($body),($response1->body()));
				//Get the status of the Returns submitted
				$resReturn = $this->getReturnStatusService($referenceId,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
				$resReturnStatus = json_decode($resReturn, true);
				//echo "<pre>";print_r($resReturnStatus);exit;
				if (isset($resReturnStatus['status_cd']) && $resReturnStatus['status_cd'] === '1') {
					Session::forget('gstr1_summary_data'); // removes session
					Session::save();
					$responseData = $this->createRequestRespone($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
					$res = json_decode($responseData, true);
					if (isset($res['status_cd']) && $res['status_cd'] === '1') {
						Session::put('gstr1_summary_data', $res['data']);
						$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						return $resOtpEVC;
					}else{
						return $res;
					}
				}else{
					$data = $resReturn->json();
					return $data;
				}
			}else{
				$data = $response1->json();
				return $data;
			}


		}
		else if($mainRepType =="gstr3b" && $isNil=="Y")
		{
			$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			return $resOtpEVC;
		}
		else if($mainRepType =="gstr3b" && $isNil=="N")
		{
			$responseData = $this->getGstr3bAutoLiabServiceNew($gstin, $financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
			$res = json_decode($responseData, true);
			//echo "<pre>"; print_r($res); exit;
			if (isset($res['status_cd']) && $res['status_cd'] === '1') {
				Session::forget('gstr3b_summary_data'); // removes session
				Session::save();
				Session::put('gstr3b_summary_data', $res['data']);
				$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
				return $resOtpEVC;
			}else{
				return $res;
			}
		}
		else if($mainRepType =="gstr9" && $isNil=="Y")
		{
			$period = $this->getGstr9Period($period);
			$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			return $resOtpEVC;
		}
		else if($mainRepType =="gstr9" && $isNil=="N")
		{			
			//echo "<pre>";print_r(json_encode($merged));exit;
			if (isset($merged) && $merged !=null) {				
				$body = $merged; //gstr9-retsave payload
				$url = $this->baseUrl . 'gstr9/retsave';
				$returnPeriod = 'ret_period';
				$period = $this->getGstr9Period($period);
				$type = "GSTR9";
				$query = [
					'email' => $this->email,
				];
				$response2 = Http::withHeaders($this->submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
					->put($url . '?' . http_build_query($query), $body);
				$resp2 = json_decode($response2, true);
				//echo "<pre>";print_r($resp2);exit;
				if (isset($resp2['status_cd']) && $resp2['status_cd'] === '1') {
					$referenceId = $resp2['data']['reference_id'] ?? $this->getLatestRefId($mainRepType, $period);
					$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,$status,"",$referenceId, json_encode($body),($response2->body()));
				
					Session::forget('gstr9_summary_data'); // removes session
					Session::save();
					$responseData = $this->createReqResGstr9($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
					$res = json_decode($responseData, true);
					//echo "<pre>";print_r($res);exit;
					if(isset($res['status_cd']) && $res['status_cd'] === '1') {
						Session::put('gstr9_summary_data', $res['data']);
						$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						return $resOtpEVC;
					}else if(isset($res['error']['error_cd']) && $res['error']['error_cd'] === 'RET00009') {
						$retSumRes = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						$resp = json_decode($retSumRes, true);
						//echo "<pre>"; print_r(json_encode($resp['data'])); exit;
						if (isset($resp['status_cd']) && ($resp['status_cd'] === '1')) {			
							Session::put('gstr9_summary_data', $resp['data']);
							$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
							return $resOtpEVC;
						}else{
							return $retSumRes;
						}
					}else{
						$data = $responseData->json();
						return $data;
					}
				}else{
					$data = $response2->json();
					return $data;
				}
			}else{
				return "no data";
			}
		}
		else if($mainRepType =="gstr9c" && $isNil=="Y")
		{
			$period = $this->getGstr9Period($period);
			$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			return $resOtpEVC;
		}
		else if($mainRepType =="gstr9c" && $isNil=="N")
		{
			echo "<pre>";print_r(json_encode($merged));exit;
			if (isset($merged) && $merged !=null) {		
				$body = $merged; //gstr9c-retsave payload
				$url = $this->baseUrl . 'gstr9c/retsave';
				$period = $this->getGstr9Period($period);
				$returnPeriod = 'ret_period';
				$type = "R9C";
				$query = [
					'email' => $this->email,
				];
				$response2 = Http::withHeaders($this->submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
					->put($url . '?' . http_build_query($query), $body);
				$resp2 = json_decode($response2, true);
				//echo "<pre>";print_r($resp2);exit;
				if (isset($resp2['status_cd']) && $resp2['status_cd'] === '1') {
					$referenceId = $resp2['data']['reference_id'] ?? $this->getLatestRefId($mainRepType, $period);
					$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,$status,"",$referenceId, json_encode($body),($response2->body()));
					
					Session::forget('gstr9c_summary_data'); // removes session
					Session::save();
					$responseData = $this->createReqResGstr9c($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
					$res = json_decode($responseData, true);
					if (isset($res['status_cd']) && $res['status_cd'] === '1') {
						Session::put('gstr9c_summary_data', $res['data']);
						$resOtpEVC = $this->sendOtpEVC($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						return $resOtpEVC;
					}else{
						return $responseData;
					}
				}else{
					$data = $response2->json();
					return $data;
				}
			}else{
				return "no data";
			}
		}
    }

	//This API is called for new proceed to file
	public function createRequestRespone($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil)
	{
		$response = $this->getNewProceedFileService($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
		$resp = json_decode($response, true);
		//echo "<pre>"; print_r($resp); exit;
		if (isset($resp['status_cd']) && ($resp['status_cd'] === '1' || $resp['status_cd'] === '0')) {
			$referenceId = $resp['data']['reference_id'] ?? $this->getLatestRefId($mainRepType, $period); //b755fb92-6cc2-45f8-8335-ba9283e14ca8
			//added on 03-12-2025
			if (isset($resp['data']['reference_id'])) { 
				$status = $resp['status_cd'];
				$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,$status,"",$referenceId, json_encode($resp),($response->body()));
			}
			//Get the status of the Returns submitted
			$resReturn = $this->getReturnStatusService($referenceId,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
			$resReturnStatus = json_decode($resReturn, true);
			//echo "<pre>"; print_r($resReturnStatus); exit;
			if (isset($resReturnStatus['status_cd']) && ($resReturnStatus['status_cd'] === '1' || $resReturnStatus['status_cd'] === '0')) {
				if($mainRepType =="gstr1" && $isNil=='N'){
					$retSumRes = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
					//echo "<pre>"; print_r(json_decode($retSumRes, true)); exit;
					return $retSumRes;
				}else if($mainRepType =="gstr1" && $isNil=='Y'){
					return $resReturn;
				}
			}else{
				return $resReturn;
			}
		}else{
			return $response;
		}
	}
	
	//GSTR9 Auto Calculated details
	public function getGstr9AutoCalDetails(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,$childRepType,$isNil)
    {
		$url = "";
		$returnPeriod = "";
		$period = $this->getGstr9Period($period);
		if($mainRepType =="gstr9"){
			//$url = $this->baseUrl . 'gstr9/getautocal';
			$url = $this->baseUrl . 'gstr9/getdet';
			$returnPeriod = 'retperiod';
		}
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		$resp = json_decode($response, true);
		//echo "<pre>";print_r($resp);exit;
		if(isset($resp['status_cd']) && ($resp['status_cd'] === '1')) {
			
		}
		return $resp;
    }

	
	public function createReqResGstr9($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil)
	{
		$retSumRes = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
		$resp = json_decode($retSumRes, true);
		//echo "<pre>"; print_r($resp); exit;
		if (isset($resp['status_cd']) && ($resp['status_cd'] === '1')) {			
			$response = $this->getNewProceedFileService($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
			$respFile = json_decode($response, true);
			//echo "<pre>";print_r($respFile);exit;
			//Insert referenceId
			$referenceId = $respFile['data']['reference_id']  ?? '';
			$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,0,"",$referenceId, null,($response->body()));
			return $response;
		}else{
			return $retSumRes;
		}
	}
	
	public function createReqResGstr9c($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil)
	{
		
		$getGSTR9Cdetails  = $this->getGstr9cDetails($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
		$resp1 = json_decode($getGSTR9Cdetails, true);
		if (isset($resp1['status_cd']) && ($resp1['status_cd'] === '1')) {
			$retSumRes = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			$resp2 = json_decode($retSumRes, true);
			//echo "<pre>"; print_r($resp); exit;
			if (isset($resp2['status_cd']) && ($resp2['status_cd'] === '1')) {			
				$response = $this->getNewProceedFileService($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
				$respFile = json_decode($response, true);
				return $response;
			}else{
				return $retSumRes;
			}
		}else{
			return $getGSTR9Cdetails;
		}
	}
	
	//GSTR9 get details
	public function getGstr9Details(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,$childRepType,$isNil)
    {
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr9c"){
			$period = $this->getGstr9Period($period);
			$url = $this->baseUrl . 'gstr9/getdet';
			$returnPeriod = 'retperiod';
		}
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		$resp = json_decode($response, true);
		//echo "<pre>";print_r($resp);exit;
		return $resp;
    }
	
	//GSTR9c get details
	public function getGstr9cDetails(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,$childRepType,$isNil)
    {
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr9c"){
			$url = $this->baseUrl . 'gstr9c/getrecds';
			$returnPeriod = 'retperiod';
		}
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		//$resp = json_decode($response, true);
		//echo "<pre>";print_r($resp);exit;
		return $response;
    }

	public function insertGSTReturn_ReqRes($fy,$quarter,$period,$ret_type,$status,$ack_num,$referenceId,$req_data,$res_data){
		DB::table('gst_returns')->insert([
					'userid' => currentOwnerId(),
					'fy' => $fy,
					'quarter' => $quarter,
					'period' => $period,
					'ret_type' => $ret_type,
					'report_type' => "",
					'status' => $status,
					'posted_date' => date('Y-m-d'),
					'ack_num' => $ack_num,
					'reference_id' => $referenceId,
					'req_data' => $req_data,
					'res_data' => $res_data,
					'created_at' => now(),
					'updated_at' => now(),
				]);
	}

	public function getLatestRefId($mainRepType,$period){
			$userId = currentOwnerId();
			$latestData = DB::table('gst_returns')
							->where('userid', $userId)
							->where('ret_type', $mainRepType)
							->where('period', $period)
							->whereNotNull('reference_id')
							->latest('id')
							->first();
			//echo "<pre>"; print_r($getUserData);exit;
			$reference_id = $latestData->reference_id;
		return $reference_id;
	}

	//Get return status
	protected function getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn)
    {
        $res =  [
			'accept' => '*/*',
			'gst_username'    => $gst_username,
			'state_cd'    => $state_cd,
			'ip_address'    => $ip_address,
			'txn'    => $txn,
			'client_id'    => $this->clientId,
			'client_secret'=> $this->clientSecret,
        ];
		return $res;
    }
	public function getReturnStatusService($referenceId,string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,$isNil)
    {
		//echo "<pre>";print_r($merged);exit;
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr1" || $mainRepType =="gstr3b"){
			$url = $this->baseUrl . 'gstr/retstatus';
			$returnPeriod = 'returnperiod';
		}
		//send reurest to whitebook
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'refid' => $referenceId,
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		if ($response->failed()) {
            Log::error("GST return status failed", [
                'gstin' => $gstin,
                'period' => $period,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to get return status");
        }

        //$data = $response->json();
		//echo "<pre>";print_r($data);exit;
        //return $data;
		return $response;
    }

	//Step-3 :  Go to ALL API call then go to New Proceed to File API call
	//New Proceed To File(GSTR1,GSTR5,GSTR6)
	public function getNewProceedFileService($type,$gstin,$financialYear,$quarterSelect,$period,$gst_username,
	$state_cd, $ip_address,$txn,$mainRepType,$childRepType,$isNil)
    {

		$url = "";
		$returnPeriod = "";
		$query = [];
		if($mainRepType =="gstr1" && $isNil=='Y'){
			$url = $this->baseUrl . 'all/newproceedfile';
			$returnPeriod = 'retperiod';
			$query = [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'type' => $type,
				'isNil' => $isNil,
				'email' => $this->email,
			];
		}else if($mainRepType =="gstr1" && $isNil=='N'){
			$url = $this->baseUrl . 'all/newproceedfile';
			$returnPeriod = 'retperiod';
			$query = [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'type' => $type,
				'email' => $this->email,
			];
		}else if($mainRepType =="gstr9" && $isNil=='N'){
			$url = $this->baseUrl . 'all/proceedfile';
			$returnPeriod = 'retperiod';
			$query = [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'type' => $type,
				'email' => $this->email,
			];
		}else if($mainRepType =="gstr9c" && $isNil=='N'){
			$url = $this->baseUrl . 'all/proceedfile';
			$returnPeriod = 'retperiod';
			$query = [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'type' => $type,
				'email' => $this->email,
			];
		}
		//send reurest to whitebook

		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		if ($response->failed()) {
            Log::error("GST return status failed", [
                'gstin' => $gstin,
                'period' => $period,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to get return status");
        }
        //$data = $response->json();
		//echo "<pre>";print_r($data);exit;
        //return $data;
		return $response;
    }
	

	//Step-4 : Get GSTR1 Summary API
	//Get GSTR1 Summary API and  use the response as payload in retevcfile
	public function getGstr1RetSumService(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType)
    {
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr1"){
			$url = $this->baseUrl . 'gstr1/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="gstr3b"){
			$url = $this->baseUrl . 'gstr3b/retsum';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="gstr9"){
			$url = $this->baseUrl . 'gstr9/getdet';
			$returnPeriod = 'retperiod';
		}else if($mainRepType =="gstr9c"){
			$url = $this->baseUrl . 'gstr9c/retsum';
			$returnPeriod = 'retperiod';
		}
		//send reurest to whitebook
		$query = [
				'gstin' => $gstin,
				$returnPeriod => $period,
				'email' => $this->email,
			];
		
		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		
		return $response;
    }

	//Step-5 : Initiate Otp for EVC
	//Trigger this api call which is the last api call in Authentication.Form type should be R1.
	public function sendOtpEVC(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType)
	{
		if(Auth::user() && (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)){
			$url = '';
			$client_id = '';
			$client_secret = '';
			$form_type  = '';
			$gst_username = $gst_username;//'Pro_2024';
			$email = config('custom.COMP_EMAIL');
			$pan  = $this->getCompanyPanNo();
			if($mainRepType=="gstr1"){
				$form_type  = 'R1';
			}else if($mainRepType=="gstr3b"){
				$form_type  = 'R3B';
			}else if($mainRepType=="gstr9"){
				$form_type  = 'R9';
			}else if($mainRepType=="gstr9c"){
				$form_type  = 'R9C';
			}
			$ip_address = '127.0.0.1';//Helper::getClientIp();
			$state_cd = $state_cd;
			if (app()->environment('production')) {
				$url = rtrim(config('custom.MASTERSINDIA_PROD_BASEURL'), '/') . "/authentication/otpforevc?email=$email&gstin=$gstin&pan=$pan&form_type=$form_type";
				$client_id = config('custom.MASTERSINDIA_PROD_CLIENT_ID');
				$client_secret = config('custom.MASTERSINDIA_PROD_CLIENT_SECRET');
			} else {
				$url = rtrim(config('custom.MASTERSINDIA_BASEURL'), '/') . "/authentication/otpforevc?email=$email&gstin=$gstin&pan=$pan&form_type=$form_type";
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
				//echo 'cURL Error #:' . $err;
			}
			$response = json_decode($response, true);
			return $response;
		}
	}

	//Step-6 : File GSTR1
	//Trigger the retevcfile in GSTR1 use the response of Get GSTR1 Summary api call as the payload in retevcfile
	//and file GSTR3B.In the response of Get GSTR3B summary use the content inside of the data object as payload.
	public function fileGSTR1Service($payload,string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,$isNilReturn,$evc_otp)
    {
		//echo "<pre>";print_r($payload);exit;
		$status = 0;
		$referenceId = "";
		$ack_num = "";
		$isNil = ($isNilReturn=="Y")?"Y":"N";
		$payload = "";
		if($mainRepType =="gstr9" || $mainRepType =="gstr9c"){
			$period = $this->getGstr9Period($period);
		}
		
		
		if(($mainRepType =="gstr1" || $mainRepType =="gstr3b") && $isNil=="Y"){
			$payload = [
						"gstin" => $gstin,
						"ret_period" => $period,
						"isnil" => "Y",
						];
		}else if(($mainRepType =="gstr9") && $isNil=="Y"){
			$payload = [
						"gstin" => $gstin,
						"fp" => $period,
						"isnil" => "Y",
						];
		}else if($mainRepType =="gstr1" && $isNil=="N"){
			$payload =  Session::get('gstr1_summary_data');
		}else if($mainRepType =="gstr3b" && $isNil=="N"){
			$payload =  Session::get('gstr3b_summary_data');
		}else if($mainRepType =="gstr9" && $isNil=="N"){
			$payload =  Session::get('gstr9_summary_data');
			$payload['isnil'] = $isNil;
			/* ================= ADD tax_pay ================= */
			$payload['tax_pay'] = [
				[
					"sgst" => [
						"tx"   => 0,
						"intr" => 0,
						"pen"  => 0,
						"fee"  => 0,
						"oth"  => 0,
						"tot"  => 0
					],
					"cgst" => [
						"tx"   => 0,
						"intr" => 0,
						"pen"  => 0,
						"fee"  => 0,
						"oth"  => 0,
						"tot"  => 0
					],
					"liab_id" => 8855, //rand(1000, 9999),
					"trancd"  => 30002,
					"trandate"=> date('d-m-Y') // current date
				]
			];
		//echo "<pre>";print_r(json_encode($payload));exit;
		}else if($mainRepType =="gstr9c" && $isNil=="N"){
			$payload =  Session::get('gstr9c_summary_data');
		}
		$url = "";
		$returnPeriod = "";
		$body = ($payload);
		$pan = $this->getCompanyPanNo();
		if($mainRepType =="gstr1"){
			$url = $this->baseUrl . 'gstr1/retevcfile';
			$returnPeriod = 'ret_period';
		}else if($mainRepType =="gstr3b"){
			$url = $this->baseUrl . 'gstr3b/retevcfile';
			$returnPeriod = 'ret_period';
		}else if($mainRepType =="gstr9"){
			$url = $this->baseUrl . 'gstr9/retevcfile';
			//$url = $this->baseUrl . 'gstr9/retfile';
			$returnPeriod = 'ret_period';
		}else if($mainRepType =="gstr9c"){
			$url = $this->baseUrl . 'gstr9c/retevcfile';
			$returnPeriod = 'ret_period';
		}
		//send reurest to whitebook
		$query = [
			'email' => $this->email,
			'pan' => $pan,
			'evcotp' => $evc_otp,
		];
		$response = Http::withHeaders($this->submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->post($url . '?' . http_build_query($query), $body);
		$status = 0;
		if ($response->failed()) {
            Log::error("GST return submit status failed", [
                'gstin' => $gstin,
                'period' => $period,
                'returnType' => $returnType,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception("Failed to submit return status");
        }
		$resp = json_decode($response, true);
		//echo "<pre>";print_r($resp);exit;
		if (isset($resp['status_cd']) && $resp['status_cd'] === '1') {
			$status = $response['status_cd'];
			$ack_num = $response['data']['ack_num'];
			Session::forget('gstr1_summary_data'); // removes session
			Session::forget('gstr3b_summary_data'); // removes session
			Session::forget('gstr9_summary_data'); // removes session
			Session::forget('gstr9c_summary_data'); // removes session
			Session::save();
		}

        //$data = $response->json();
		//echo "<pre>";print_r($resp);exit;
        //return $data;
		$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,$status,$ack_num,$referenceId, json_encode($body),($response->body()));
		return $resp;
    }


	public function resetGSTR1($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType)
    {
		$gst_username = $gst_username;//'Pro_2024';
		$email = config('custom.COMP_EMAIL');
		$ip_address = '127.0.0.1';//Helper::getClientIp();
		$state_cd = $state_cd;
        $url = $this->baseUrl . 'gstr1/reset?email='.$email;

        $headers = [
            'accept' => '*/*',
            'gstin' => $gstin,
            'ret_period' => $period,
            'gst_username' => $gst_username,
            'state_cd' => $state_cd,
            'ip_address' => '127.0.0.1',
            'txn' => $txn,
            'client_id'  => $this->clientId,
			'client_secret'=> $this->clientSecret,
        ];

        $body = [
            "gstin" => $gstin,
            "ret_period" => $period
        ];

        $response = Http::withHeaders($headers)
                        ->post($url, $body);
		$resp = json_decode($response, true);
        return $resp;
    }

	//start return for GSTR-3B
	//Step-1 : Get GSTR1 Liability Auto Calc Details.
	public function getGstr3bAutoLiabService(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,$isNil)
    {
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr3b"){
			$url = $this->baseUrl . 'gstr2b/all';
			$returnPeriod = 'rtnprd';
		}
		//send reurest to whitebook
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'email' => $this->email,
		];
		$response1 = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		$resp1 = json_decode($response1, true);
		//echo "<pre>";print_r(json_encode($resp1));exit;
		if (isset($resp1['status_cd']) && $resp1['status_cd'] === '1') {
			$threeRetSummay = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			$threeRetSumm = json_decode($threeRetSummay, true);
			return $threeRetSummay;
		}else{
			//$data = $response->json();
			//echo "<pre>";print_r($data);exit;
			//return $data;
			return $response1;
		}

    }
	
	public function getGstr3bAutoLiab(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,$isNil)
    {
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr3b"){
			$url = $this->baseUrl . 'gstr3b/autoliab';
			$returnPeriod = 'retperiod';
		}
		//send reurest to whitebook
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		return $response;
    }
	
	public function getGstr3bAutoLiabServiceNew(string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType,$isNil)
    {
		$url = "";
		$returnPeriod = "";
		if($mainRepType =="gstr3b"){
			$url = $this->baseUrl . 'gstr2b/all';
			$returnPeriod = 'rtnprd';
		}
		//send reurest to whitebook
		$query = [
			'gstin' => $gstin,
			$returnPeriod => $period,
			'email' => $this->email,
		];
		$response1 = Http::withHeaders($this->getReturnStatusHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->get($url . '?' . http_build_query($query));
		$gstr2bResponse = json_decode($response1, true);
		//echo "<pre>";print_r(json_encode($gstr2bResponse));exit;
		
		if (isset($gstr2bResponse['status_cd']) && $gstr2bResponse['status_cd'] === '1') 
		{
			//1. call autoliab/retsum api call
			$ret3bSummary  = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
			$ret3bSummaryRes = json_decode($ret3bSummary, true);
			if (isset($ret3bSummaryRes['status_cd']) && $ret3bSummaryRes['status_cd'] === '1') 
			{
				$buildGstr3bSavePayload = $this->buildGstr3bSavePayload($ret3bSummaryRes);
				//echo "<pre>";print_r(json_encode($buildGstr3bSavePayload));exit;
				//2. call gstr3b/retsave api call
				$saveResp = $this->putRetSaveService($buildGstr3bSavePayload,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
				$saveResult = json_decode($saveResp, true);		
				if (isset($saveResult['status_cd']) && ($saveResult['status_cd'] === '1' || $saveResult['status_cd'] === '0')) 
				{
					$referenceId = $saveResult['data']['reference_id'] ?? $this->getLatestRefId($mainRepType, $period); 
					if (isset($saveResult['data']['reference_id'])) { 
						$status = $saveResult['status_cd'];
						$this->insertGSTReturn_ReqRes($financialYear,$quarterSelect,$period,$mainRepType,$status,"",$referenceId, json_encode($saveResult),($saveResp->body()));
					}
					//3. call gstr/retstatus api call
					$resReturn = $this->getReturnStatusService($referenceId,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType,$isNil);
					$resReturnStatus = json_decode($resReturn, true);
					if (isset($resReturnStatus['status_cd']) && $resReturnStatus['status_cd'] === '1') {
						//4. call gstr3b/retsum api call
						$retSummaryResp  = $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						$retSummary = json_decode($retSummaryResp, true);
						//echo "<pre>";print_r(($retSummary));exit;
						$payloadLiab = $this->mapGstr3bRetOffSetPayload($retSummary['data']);
						//echo "<pre>";print_r(json_encode($payloadLiab));exit;
						//5. call gstr3b/retoffset api call
						$offsetResp = $this->putRetOffsetService($payloadLiab,$gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						$offsetResult = json_decode($offsetResp, true);
						//echo "<pre>";print_r($offsetResult);exit;
						if (isset($offsetResult['status_cd']) && $offsetResult['status_cd'] === '1') {
							//6. Get gstr3b/retsum  api call 
							return $this->getGstr1RetSumService($gstin,$financialYear,$quarterSelect,$period,$gst_username,$state_cd,$ip_address,$txn,$mainRepType,$childRepType);
						}else{
							return $offsetResp;
						}
					}else{
						return $resReturn;
					}
					
				}else{
					return $saveResp;
				}
			}else{
				return $ret3bSummary;
			}
		}else{
			return $response1;
		}
    }

	public function putRetOffsetService($payoadLiab,string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType)
    {
		$url = "";
		$returnPeriod = "";
		$url = $this->baseUrl . 'gstr3b/retoffset';
		$returnPeriod = 'ret_period';
		$query = [
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->put($url . '?' . http_build_query($query), $payoadLiab);
		$resp = json_decode($response, true);
		return 	$response;
	}
	
	public function putRetSaveService($payoadLiab,string $gstin, $financialYear,$quarterSelect,string $period,string $gst_username,
	string $state_cd,string $ip_address,string $txn, String $mainRepType,String $childRepType)
    {
		$url = "";
		$returnPeriod = "";
		$url = $this->baseUrl . 'gstr3b/retsave';
		$returnPeriod = 'ret_period';
		$query = [
			'email' => $this->email,
		];
		$response = Http::withHeaders($this->submitReturnsHeaders($gst_username,$gstin,$returnPeriod,$period,$state_cd,$ip_address,$txn))
			->put($url . '?' . http_build_query($query), $payoadLiab);
		$resp = json_decode($response, true);
		return 	$response;
	}
	
	

	
	private function mapGstr3bRetOffSetPayload(array $data): array
	{
		$payload = [
			'nettaxpay' => [],
			'pdcash'    => []
		];


		/* ===============================
		   NET TAX PAYABLE (FROM tx_py)
		=============================== */
		foreach ($data['tx_pmt']['tx_py'] as $row) {

			$payload['nettaxpay'][] = [
				'trans_typ'   => $row['trans_typ'],
				'trans_desc'  => ucfirst(strtolower($row['tran_desc'] ?? '')),
				'liab_ldg_id' => $row['liab_ldg_id'],

				'igst' => [
					'tx'   => $row['igst']['tx']   ?? 0,
					'intr' => $row['igst']['intr'] ?? 0,
					'fee'  => $row['igst']['fee']  ?? 0,
				],
				'cgst' => [
					'tx'   => $row['cgst']['tx']   ?? 0,
					'intr' => $row['cgst']['intr'] ?? 0,
					'fee'  => $row['cgst']['fee']  ?? 0,
				],
				'sgst' => [
					'tx'   => $row['sgst']['tx']   ?? 0,
					'intr' => $row['sgst']['intr'] ?? 0,
					'fee'  => $row['sgst']['fee']  ?? 0,
				],
				'cess' => [
					'tx'   => $row['cess']['tx']   ?? 0,
					'intr' => $row['cess']['intr'] ?? 0,
					'fee'  => $row['cess']['fee']  ?? 0,
				],
			];
		}

		/* ===============================
		   PAID THROUGH CASH (FROM pdcash)
		=============================== */
		if (!empty($data['tx_pmt']['pdcash'])) {
			foreach ($data['tx_pmt']['pdcash'] as $row) {

				$payload['pdcash'][] = [
					'liab_ldg_id' => $row['liab_ldg_id'],
					'trans_typ'  => $row['trans_typ'],

					// Tax paid
					'ipd'  => $row['ipd']  ?? 0,
					'cpd'  => $row['cpd']  ?? 0,
					'spd'  => $row['spd']  ?? 0,
					'cspd' => $row['cspd'] ?? 0,

					// Interest paid
					'i_intrpd'  => $row['i_intrpd']  ?? 0,
					'c_intrpd'  => $row['c_intrpd']  ?? 0,
					's_intrpd'  => $row['s_intrpd']  ?? 0,
					'cs_intrpd' => $row['cs_intrpd'] ?? 0,

					// Late fee paid (IMPORTANT)
					'i_lfeepd'  => $row['i_lfeepd']  ?? 0,
					'c_lfeepd'  => $row['c_lfeepd']  ?? 0,
					's_lfeepd'  => $row['s_lfeepd']  ?? 0,
					'cs_lfeepd' => $row['cs_lfeepd'] ?? 0,
				];
			}
		}

		/* ===============================
		   PAID THROUGH ITC
		=============================== */
		$itc = $data['itc_elg']['itc_net'];

		/*$payload['pditc'] = [
			'liab_ldg_id' => rand(10000, 99999),
			'trans_typ'  => 30002,
			'i_pdi'      => $itc['iamt'] ?? 0,
			'i_pdc'      => 0,
			'i_pds'      => 0,
			'c_pdi'      => $itc['camt'] ?? 0,
			'c_pdc'      => 0,
			's_pdi'      => $itc['samt'] ?? 0,
			's_pds'      => 0,
			'cs_pdcs'    => $itc['csamt'] ?? 0,
		];*/

		/* ===============================
		   NEGATIVE LIABILITY SETOFF
		=============================== */
		/*$payload['pdnls'] = [];

		foreach ($data['tx_pmt']['adjnegliab'] as $row) {
			$payload['pdnls'][] = [
				'liab_ldg_id' => rand(10000, 99999),
				'trans_typ'  => $row['trans_typ'],
				'ipd'        => $row['igst']['tx'] ?? 0,
				'cpd'        => $row['cgst']['tx'] ?? 0,
				'spd'        => $row['sgst']['tx'] ?? 0,
				'cspd'       => $row['cess']['tx'] ?? 0,
			];
		}*/

		return $payload;
	}


	public function getPreviousMonthStartEnd($financialYear, $month){
		/*$year = (int)($financialYear);
		// Create a date for the 1st day of the given month
		$date = DateTime::createFromFormat('Y-n-j', "$year-$month-1");
		// Move to the previous month
		$date->modify('-1 month');
		// Get first date of previous month
		$firstDate = $date->format('Y-m-01');
		// Get last date of previous month
		$lastDate = $date->format('Y-m-t');
		return $firstDate.'#'.$lastDate;*/
		
		$year = (int)($financialYear);
		// Create a date for the 1st day of the given month
		$date = DateTime::createFromFormat('Y-n-j', "$year-$month-1");
		// Get first and last date of current month
		$firstDate = $date->format('Y-m-01');
		$lastDate  = $date->format('Y-m-t');
		return $firstDate . '#' . $lastDate;
	}
	public function create3bRequest($TwoBResp,$autoLiabResp,$threeRetSummay,$gstin,$period,$financialYear,$periodSelect){
		$userId = currentOwnerId();
		//$resp = $resp['data'];
		$dateBetween = $this->getPreviousMonthStartEnd($financialYear,$periodSelect);
		$dateBetween = explode('#', $dateBetween);
		$fromDate = $dateBetween[0];
		$toDate = $dateBetween[1];

		//2B data calculation
		function topLevel($arr)
		{
			return [
				'igst' => $arr['igst'] ?? 0,
				'cgst' => $arr['cgst'] ?? 0,
				'sgst' => $arr['sgst'] ?? 0,
				'cess' => $arr['cess'] ?? 0,
			];
		}

		$itcavl = $TwoBResp['data']['itcsumm']['itcavl'] ?? [];

		// Sections to sum (top-level only)
		$sections = ['nonrevsup', 'isdsup', 'revsup', 'imports', 'othersup'];

		// Initialize totals
		$total = ['igst'=>0,'cgst'=>0,'sgst'=>0,'cess'=>0];

		foreach ($sections as $sec) {
			$row = topLevel($itcavl[$sec] ?? []);
			$total['igst'] += $row['igst'];
			$total['cgst'] += $row['cgst'];
			$total['sgst'] += $row['sgst'];
			$total['cess'] += $row['cess'];
		}

		// FINAL itc_avl → ONLY ONE ROW, ALWAYS ty = OTH
		$itc_avl = [
			[
				'ty'   => 'OTH',
				'iamt' => $total['igst'],
				'camt' => $total['cgst'],
				'samt' => $total['sgst'],
				'csamt'=> $total['cess']
			]
		];

		// itc_rev & itc_inelg EMPTY (as required)
		$itc_rev   = [];
		$itc_inelg = [];

		// itc_net = avl - rev - inelg
		$itc_net = [
			'iamt' => $total['igst'],
			'camt' => $total['cgst'],
			'samt' => $total['sgst'],
			'csamt'=> $total['cess']
		];

		// Final Output
		$itc_elg = [
			'itc_avl'   => $itc_avl,
			'itc_rev'   => $itc_rev,
			'itc_inelg' => $itc_inelg,
			'itc_net'   => $itc_net
		];
		//echo "<pre>";print_r($itc_elg);exit;
		
		//Auto calculated liability
		$liabitc = $autoLiabResp['r3bautopop']['liabitc']['sup_details'];
		function getSubtotal($data, $key)
		{
			return $data[$key]['subtotal'] ?? [
				'txval' => 0, 'iamt' => 0, 'camt' => 0, 'samt' => 0, 'csamt' => 0
			];
		}

		$osup_3_1a = getSubtotal($liabitc, 'osup_3_1a');
		$osup_3_1b = getSubtotal($liabitc, 'osup_3_1b');
		$osup_3_1c = getSubtotal($liabitc, 'osup_3_1c');
		$isup_3_1d = getSubtotal($liabitc, 'isup_3_1d');
		$osup_3_1e = getSubtotal($liabitc, 'osup_3_1e');

		$response = [
			'sup_details' => [
				'osup_det' => [
					'txval' => $osup_3_1a['txval'] ?? 0,
					'iamt'  => $osup_3_1a['iamt'] ?? 0,
					'camt'  => $osup_3_1a['camt'] ?? 0,
					'samt'  => $osup_3_1a['samt'] ?? 0,
					'csamt' => $osup_3_1a['csamt'] ?? 0,
				],

				'osup_zero' => [
					'txval' => $osup_3_1b['txval'] ?? 0,
					'iamt'  => $osup_3_1b['iamt'] ?? 0,
					'csamt' => $osup_3_1b['csamt'] ?? 0,
				],

				'osup_nil_exmp' => [
					'txval' => $osup_3_1c['txval'] ?? 0,
				],

				'isup_rev' => [
					'txval' => $isup_3_1d['txval'] ?? 0,
					'iamt'  => $isup_3_1d['iamt'] ?? 0,
					'camt'  => $isup_3_1d['camt'] ?? 0,
					'samt'  => $isup_3_1d['samt'] ?? 0,
					'csamt' => $isup_3_1d['csamt'] ?? 0,
				],

				'osup_nongst' => [
					'txval' => $osup_3_1e['txval'] ?? 0,
				],
			]
		];
		//echo "<pre>";print_r($response);exit;

		$gstin = $gstin;
		$ret_period = $period;
		$isup_details = $this->get_isup_details($userId,$fromDate,$toDate);
		//Interest map calculation
		$intrLtFee = $threeRetSummay['intr_ltfee'] ?? [];
		function mapIntrFields($arr)
		{
			return [
				'iamt'  => $arr['iamt']  ?? 0,
				'camt'  => $arr['camt']  ?? 0,
				'samt'  => $arr['samt']  ?? 0,
				'csamt' => $arr['csamt'] ?? 0,
			];
		}
		/*$intr_ltfee = [
			'intr_details' => mapIntrFields($intrLtFee['intr_details'] ?? []),
			'ltfee_details' => mapIntrFields($intrLtFee['ltfee_details'] ?? []),
		];*/

		$response = [
			'gstin' => $gstin,
			'ret_period' => $ret_period,
			'sup_details' => $response['sup_details'],
			/*'inter_sup' => [
				'unreg_details' => $unreg_details,
				'comp_details' => [],
				'uin_details' => []
			],*/
			'eco_dtls' => [
				'eco_sup' => ['txval' => 0,'iamt' => 0, 'camt' => 0, 'samt' => 0, 'csamt' => 0],
				'eco_reg_sup' => ['txval' => 0]
			],
			'itc_elg' => $itc_elg,
			'inward_sup' => [
				'isup_details' => $isup_details
			],
			'intr_ltfee' => [
				'intr_details' => mapIntrFields($intrLtFee['intr_details'] ?? [])
			]
		];
		//echo "<pre>";print_r($response);exit;
		//return response()->json($response);
		return $response;

	}
	

	/**
	 * Prepare GSTR-3B Save Payload
	*/
	
	public function buildGstr3bSavePayload(array $apiResponse): array
	{
		$data = $apiResponse['data'];

		return [
			"gstin"      => $data['gstin'],
			"ret_period" => $data['ret_period'],

			"sup_details" => [
				"osup_det" => $data['sup_details']['osup_det'],
				"osup_zero" => $data['sup_details']['osup_zero'],
				"osup_nil_exmp" => [
					"txval" => $data['sup_details']['osup_nil_exmp']['txval']
				],
				"isup_rev" => $data['sup_details']['isup_rev'],
				"osup_nongst" => [
					"txval" => $data['sup_details']['osup_nongst']['txval']
				]
			],

			"inter_sup" => [
				"unreg_details" => array_values($data['inter_sup']['unreg_details'] ?? []),
				"comp_details"  => array_values($data['inter_sup']['comp_details'] ?? []),
				"uin_details"   => array_values($data['inter_sup']['uin_details'] ?? [])
			],

			"eco_dtls" => [
				"eco_sup" => $data['eco_dtls']['eco_sup'],
				"eco_reg_sup" => [
					"txval" => $data['eco_dtls']['eco_reg_sup']['txval']
				]
			],

			"itc_elg" => [
				"itc_avl"   => array_values($data['itc_elg']['itc_avl']),
				"itc_rev"   => array_values($data['itc_elg']['itc_rev']),
				"itc_net"   => $data['itc_elg']['itc_net'],
				"itc_inelg" => array_values($data['itc_elg']['itc_inelg'])
			],

			"intr_ltfee" => [
				"intr_details" => $data['intr_ltfee']['intr_details'],
				"ltfee_details" => $data['intr_ltfee']['ltfee_details']
			]
		];
	}
	public function prepareGstr3bSavePayload(string $gstin,string $period,array $gstr2bResponse,array $gstr3bAutoLiabResponse) 
	{

		/* -------------------------------------------------
		   Helper: deep empty check (recursive)
		------------------------------------------------- */
		$isDeepEmpty = function ($data) use (&$isDeepEmpty) {
			if (!is_array($data)) {
				return ($data === 0 || $data === null || $data === '');
			}

			foreach ($data as $value) {
				if (!$isDeepEmpty($value)) {
					return false;
				}
			}
			return true;
		};

		/* -------------------------------------------------
		   Base payload
		------------------------------------------------- */
		$payload = [
			'gstin'      => $gstin,
			'ret_period' => $period
		];

		/* =================================================
		   OUTWARD SUPPLIES (TABLE 3.1)
		   Source: Auto-liability
		   Correct Path:
		   data → r3bautopop → liabitc → sup_details
		================================================= */
		$supDetails =
			$gstr3bAutoLiabResponse['data']['r3bautopop']['liabitc']['sup_details']
			?? null;

		if ($supDetails && !$isDeepEmpty($supDetails)) {
			$payload['sup_details'] = $supDetails;
		}

		/* =================================================
		   INWARD SUPPLIES (EXEMPT / NIL / NON-GST)
		   (Mandatory – keep zero if no data)
		================================================= */
		$payload['inward_sup'] = [
			'isup_details' => [
				'inter' => 0,
				'intra' => 0
			]
		];

		/* =================================================
		   ITC NET (STRICTLY FROM GSTR-2B)
		   Path:
		   itcsumm → itcavl → nonrevsup
		================================================= */
		$nonRevSup =
			$gstr2bResponse['data']['data']['itcsumm']['itcavl']['nonrevsup']
			?? [];

		$payload['itc_elg'] = [
			'itc_net' => [
				'iamt'  => (float) ($nonRevSup['igst'] ?? 0),
				'camt'  => (float) ($nonRevSup['cgst'] ?? 0),
				'samt'  => (float) ($nonRevSup['sgst'] ?? 0),
				'csamt' => (float) ($nonRevSup['cess'] ?? 0)
			]
		];

		/* =================================================
		   INTEREST & LATE FEE
		   Include ONLY if non-zero
		================================================= */
		$intrLtFee =
			$gstr3bAutoLiabResponse['data']['r3bautopop']['liabitc']['intr_ltfee']
			?? null;

		if ($intrLtFee && !$isDeepEmpty($intrLtFee)) {
			$payload['intr_ltfee'] = $intrLtFee;
		}

		/* =================================================
		   ECO DETAILS (MANDATORY EVEN IF ZERO)
		================================================= */
		$payload['eco_dtls'] = [
			'eco_sup'     => 0,
			'eco_reg_sup' => 0
		];

		return $payload;
	}


	public function getITCList($userId,$fromDate,$toDate){
		$itcList = DB::table('purchases')
					->leftJoin('purchase_values', 'purchases.id', '=', 'purchase_values.sid')
					->leftJoin('products', 'purchase_values.prod_id', '=', 'products.id')
					->select(
						DB::raw("
							CASE
								WHEN products.item_type = 'product' THEN 'IMPG'
								WHEN products.item_type = 'service' THEN 'IMPS'
								ELSE 'OTH'
							END AS ty
						"),
						DB::raw('
							SUM(purchase_values.amount * (purchase_values.gst_rate / 100)) AS iamt,
							SUM((purchase_values.amount * (purchase_values.gst_rate / 100)) / 2) AS camt,
							SUM((purchase_values.amount * (purchase_values.gst_rate / 100)) / 2) AS samt
						')
					)
					->whereBetween('purchases.inv_date', [$fromDate, $toDate])
					->where('purchases.added_by', $userId)
					->groupBy('ty')
					->get()
					->map(function ($row) {
						return [
							'ty'   => $row->ty,
							'iamt' => round($row->iamt, 2),
							'camt' => round($row->camt, 2),
							'samt' => round($row->samt, 2),
							'csamt' => 0.00, // default cess = 0
						];
					});


		//echo "<pre>";print_r($itcList);exit;
		//return response()->json($itcList);
		return $itcList->toArray();

	}

	public function get_isup_details($userId,$fromDate,$toDate){
		$isupDetails = DB::table('purchase_values')
				->join('purchases', 'purchases.id', '=', 'purchase_values.sid')
				->join('customers', 'customers.id', '=', 'purchases.inv_name')
				->select(
					DB::raw("
						CASE
							WHEN customers.cust_gst_no IS NOT NULL AND customers.cust_gst_no <> '' THEN 'GST'
							ELSE 'NONGST'
						END AS ty
					"),
					DB::raw("
						SUM(CASE WHEN purchase_values.gst_trans = 'interstate'
								 THEN purchase_values.tax_amt ELSE 0 END) AS inter
					"),
					DB::raw("
						SUM(CASE WHEN purchase_values.gst_trans = 'intrastate'
								 THEN purchase_values.tax_amt ELSE 0 END) AS intra
					")
				)
				->whereBetween('purchases.inv_date', [$fromDate, $toDate])
				->where('purchases.added_by', $userId)
				->groupBy('ty')
				->get()
				->map(function ($row) {
					return [
						'ty' => $row->ty,
						'inter' => round($row->inter, 2),
						'intra' => round($row->intra, 2),
					];
				});

			$defaultTypes = collect(['GST', 'NONGST']);
			$isupDetails = $defaultTypes->map(function ($type) use ($isupDetails) {
				$found = $isupDetails->firstWhere('ty', $type);
				return [
					'ty' => $type,
					'inter' => $found['inter'] ?? 0,
					'intra' => $found['intra'] ?? 0,
				];
			});
		//echo "<pre>";print_r($isupDetails);exit;
		//return response()->json($itcList);
		return $isupDetails->toArray();
	}


	public function payloadOffSetLiab($retSummaryData,$gstin,$period,$financialYear,$periodSelect)
	{

		$userId = currentOwnerId();
		//$resp = $resp['data'];
		$dateBetween = $this->getPreviousMonthStartEnd($financialYear,$periodSelect);
		$dateBetween = explode('#', $dateBetween);
		$fromDate = $dateBetween[0];
		$toDate = $dateBetween[1];
		// ---- PDCASH ----
		$response = [];
		//Get interest and late fee details
		$intrLtfee = '';
		$nettaxpay = '';
		$pdnls = '';
		if (isset($retSummaryData['intr_ltfee']) || !empty($retSummaryData['intr_ltfee'])) {
			$intrLtfee = $retSummaryData['intr_ltfee'];
		} else {
			$intrLtfee = [
				"intr_details" => [
					"iamt" => 0,
					"camt" => 0,
					"samt" => 0,
					"csamt" => 0
				],
				"ltfee_details" => [
					"camt" => 0,
					"samt" => 0
				]
			];
		}

		//Fetch cash-mode sales and their tax values
		/*$pdcashData = DB::table('sales')
			->join('sales_values', 'sales.id', '=', 'sales_values.sid')
			->where('sales.mode_of_pay', 'CASH')
			->whereBetween('sales.inv_date', [$fromDate, $toDate])
			->where('sales.added_by', $userId)
			->select(
				'sales.id as sale_id',
				'sales.trans_type as trans_typ',
				'sales.id as liab_ldg_id',
				DB::raw('SUM(CASE WHEN sales_values.gst_trans = "interstate" THEN (sales_values.amount * sales_values.gst_rate / 100) ELSE 0 END) as ipd'),
				// CGST + SGST for intrastate
				DB::raw('SUM(CASE WHEN sales_values.gst_trans = "intrastate" THEN (sales_values.amount * sales_values.gst_rate / 200) ELSE 0 END) as cpd'),
				DB::raw('SUM(CASE WHEN sales_values.gst_trans = "intrastate" THEN (sales_values.amount * sales_values.gst_rate / 200) ELSE 0 END) as spd')
			)
			->groupBy('sales.id', 'sales.trans_type')
			->get()
			->map(function ($item) use ($intrLtfee) {
				return [
					"liab_ldg_id" => rand(10000, 99999), // random number
					"trans_typ" => $item->trans_typ ?? 30002,
					"ipd" => (float) $item->ipd,
					"cpd" => (float) $item->cpd,
					"spd" => (float) $item->spd,
					"cspd" => 0,
					//Inject interest & late fee from intr_ltfee
					"i_intrpd" => $intrLtfee["intr_details"]["iamt"],
					"c_intrpd" => $intrLtfee["intr_details"]["camt"],
					"s_intrpd" => $intrLtfee["intr_details"]["samt"],
					"cs_intrpd" => $intrLtfee["intr_details"]["csamt"],
					"c_lfeepd" => $intrLtfee["ltfee_details"]["camt"],
					"s_lfeepd" => $intrLtfee["ltfee_details"]["samt"]
				];
			})
			->values();*/
				
				// SUM all values
				$itcNet = $retSummaryData['itc_elg']['itc_net'] ?? [
					"iamt" => 0,
					"camt" => 0,
					"samt" => 0,
					"csamt" => 0
				];

				// Prepare final pdcash block
				$pdcashData[] = [
					"liab_ldg_id" => rand(10000,99999),
					"trans_typ"   => 30002,
					// ITC NET
					"ipd"       => 0,//round($itcNet["iamt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"cpd"       => 0,//round($itcNet["camt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"spd"       => 0,//round($itcNet["samt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"cspd"      => 0,//round($itcNet["csamt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					//Interest
					"i_intrpd"  => round($intrLtfee["intr_details"]["iamt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"c_intrpd"  => round($intrLtfee["intr_details"]["camt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"s_intrpd"  => round($intrLtfee["intr_details"]["samt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"cs_intrpd" => round($intrLtfee["intr_details"]["csamt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					//Late fees
					"c_lfeepd"  => round($intrLtfee["ltfee_details"]["camt"] ?? 0, 0, PHP_ROUND_HALF_UP),
					"s_lfeepd"  => round($intrLtfee["ltfee_details"]["samt"] ?? 0, 0, PHP_ROUND_HALF_UP),
				];
	
				// Get ITC AVL values
				$itcAvl = $retSummaryData['itc_elg']['itc_avl'] ?? [];
				// Initialize totals
				$totalITC = [
					'iamt'  => 0,
					'camt'  => 0,
					'samt'  => 0,
					'csamt' => 0,
				];
				// 1) Sum regular itc_avl values
				foreach ($itcAvl as $row) {
					$totalITC['iamt']  += $row['iamt']  ?? 0;
					$totalITC['camt']  += $row['camt']  ?? 0;
					$totalITC['samt']  += $row['samt']  ?? 0;
					$totalITC['csamt'] += $row['csamt'] ?? 0;
				}
				// 2) Sum intr_ltfee values (intr_details + ltfee_details)
				/*$intrLtFee = $retSummaryData['intr_ltfee'] ?? [];
				$intrDetails  = $intrLtFee['intr_details']  ?? [];
				$ltfeeDetails = $intrLtFee['ltfee_details'] ?? [];

				$totalITC['iamt'] += ($intrDetails['iamt'] ?? 0) + 0;
				$totalITC['camt'] += ($intrDetails['camt'] ?? 0) + 0;
				$totalITC['samt'] += ($intrDetails['samt'] ?? 0) + 0;
				$totalITC['csamt'] += ($intrDetails['csamt'] ?? 0) + 0;*/

				// 3) Map into pditc response
				$pditc = [
					"liab_ldg_id" => rand(10000, 99999),
					"trans_typ"   => 30002,
					// IGST
					"i_pdi" => round($totalITC['iamt']?? 0, 0, PHP_ROUND_HALF_UP),
					"i_pdc" => 0,
					"i_pds" => 0,
					// CGST
					"c_pdi" => 0,
					"c_pdc" => round($totalITC['camt']?? 0, 0, PHP_ROUND_HALF_UP),
					// SGST
					"s_pdi" => 0,
					"s_pds" => round($totalITC['samt']?? 0, 0, PHP_ROUND_HALF_UP),
					// CESS
					"cs_pdcs" => round($totalITC['csamt']?? 0, 0, PHP_ROUND_HALF_UP)
				];
			
			if (isset($retSummaryData['tx_pmt']['tx_py']) && !empty($retSummaryData['tx_pmt']['tx_py'])) {
				$txPy = $retSummaryData['tx_pmt']['tx_py'] ?? [];
				$itcNet = $retSummaryData['itc_elg']['itc_net'] ?? [
					'iamt' => 0,
					'camt' => 0,
					'samt' => 0,
					'csamt' => 0,
				];

				function mapTax($arr)
				{	/*if($arr['intr'] ==18){
					return [
							'tx'   => $arr['tx']   ?? 0,
							'intr' => 17.78,
							'fee'  => $arr['fee']  ?? 0
						];
					}else{
						return [
							'tx'   => $arr['tx']   ?? 0,
							'intr' => $arr['intr'] ?? 0,
							'fee'  => $arr['fee']  ?? 0
						];
					}*/
					return [
						'tx'   => $arr['tx']   ?? 0,
						'intr' => $arr['intr'] ?? 0,
						'fee'  => $arr['fee']  ?? 0
					];
				}
				$nettaxpay = [];
				foreach ($txPy as $row) {
					$item = [
						'trans_typ'   => $row['trans_typ'] ?? 0,
						'trans_desc'  => ucfirst(strtolower($row['tran_desc'] ?? '')),
						'liab_ldg_id' => rand(10000, 99999),

						'sgst' => mapTax($row['sgst'] ?? []),
						'cgst' => mapTax($row['cgst'] ?? []),
						'cess' => mapTax($row['cess'] ?? []),
						'igst' => mapTax($row['igst'] ?? [])
					];

					// ✅ ADD ITC NET ONLY WHEN trans_typ = 30003, will be comment
					/*if (($row['trans_typ'] ?? 0) == 30002) {
						$item['igst']['tx'] += $itcNet['iamt'] ?? 0;
						$item['cgst']['tx'] += $itcNet['camt'] ?? 0;
						$item['sgst']['tx'] += $itcNet['samt'] ?? 0;
						$item['cess']['tx'] += $itcNet['csamt'] ?? 0;
					}*/
					if (($row['trans_typ'] ?? 0) == 30002) {
						$item['igst']['tx'] -= (round($itcNet['iamt']?? 0, 0, PHP_ROUND_HALF_UP));
						$item['cgst']['tx'] -= (round($itcNet['camt']?? 0, 0, PHP_ROUND_HALF_UP));
						$item['sgst']['tx'] -= (round($itcNet['samt']?? 0, 0, PHP_ROUND_HALF_UP));
						$item['cess']['tx'] -= (round($itcNet['csamt']?? 0, 0, PHP_ROUND_HALF_UP));
						// prevent negative values
						$item['igst']['tx'] = max(0, $item['igst']['tx']);
						$item['cgst']['tx'] = max(0, $item['cgst']['tx']);
						$item['sgst']['tx'] = max(0, $item['sgst']['tx']);
						$item['cess']['tx'] = max(0, $item['cess']['tx']);
					}

					$nettaxpay[] = $item;
				}
			}

			if (isset($retSummaryData['tx_pmt']['pdnls']) || !empty($retSummaryData['tx_pmt']['pdnls'])) {
				$pdnls = $retSummaryData['tx_pmt']['pdnls'];
			}

			//echo "<pre>";print_r($pdcashData->toArray());
			//echo "<pre>";print_r($pditc);
			//echo "<pre>";print_r($nettaxpay);
			$pdcashDataArr = !empty($pdcashData) ? $pdcashData : [];
			$pditcArr = !empty($pditc) ? (array) $pditc : [];
			$nettaxpayArr = !empty($nettaxpay) ? (array) $nettaxpay : [];
			$pdnlsArr = !empty($pdnls) ? (array) $pdnls : [];

			// Append only if not empty
			if (!empty($pdcashDataArr)) {
				$response['pdcash'] = $pdcashDataArr;
			}
			if (!empty($pditcArr)) {
				$response['pditc'] = $pditcArr;
			}
			if (!empty($nettaxpayArr)) {
				$response['nettaxpay'] = $nettaxpayArr;
			}
			if (!empty($pdnlsArr)) {
				$response['pdnls'] = $pdnlsArr;
			}
			//echo "<pre>";print_r(json_encode($response));exit;
			return $response;
	}

	//end return for GSTR-3B
	
	public function cleanPayload($data)
	{
		// Convert Collection to array first
		if ($data instanceof \Illuminate\Support\Collection) {
			$data = $data->toArray();
		}

		// If it's array, clean recursively
		if (is_array($data)) {
			$cleaned = [];

			foreach ($data as $key => $value) {

				// Recursively clean children
				$value = $this->cleanPayload($value);

				// Skip empty values
				if (
					$value === null ||
					$value === '' ||
					$value === [] ||
					$value === false
				) {
					continue;
				}

				$cleaned[$key] = $value;
			}

			return $cleaned;
		}

		// Return scalar values
		return $data;
	}
	
	function getGstr9Period($ym) {
		$year = intval(substr($ym, 2, 4));
		return "03" . $year;
	}
	
	public function getCompanyPanNo()
	{
		$userId = currentOwnerId();

		return DB::table('users')
			->leftJoin('company_profiles', 'users.id', '=', 'company_profiles.userId')
			->where('users.id', $userId)
			->value('company_profiles.comp_pan_no'); // returns single value or null
	}
	
}
