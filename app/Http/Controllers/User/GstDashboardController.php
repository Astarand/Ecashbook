<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Redirect;
use DB;
use Auth;
use Validator;
use App\Models\Sales;
use App\Models\Sales_values;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use App\Models\Gst_logins;
use Helper;
use App\Services\WhiteBooksGstService;
use Illuminate\Support\Facades\Cookie;
use DateTime;
use DatePeriod;
use DateInterval;

class GstDashboardController extends Controller
{

	protected $gstService;

	public function __construct(WhiteBooksGstService $gstService)
    {
        $this->gstService = $gstService;
    }

	public function GstDashboard()
    {
        return view('User.gst-dashboard');
    }

}
