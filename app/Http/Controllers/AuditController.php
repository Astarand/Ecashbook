<?php

namespace App\Http\Controllers;

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
use App\Models\Audit_logs;

use App\Helpers\AuditLogger;
// use Image;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;


class AuditController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

	public function auditIndex(Request $request)
	{
		checkCoreAccess('Audit Trail');
		$userId = Auth::user()->id;
		$userType = Auth::user()->u_type;
		$search   = $request->search;
		$query = Audit_logs::join('users', 'users.id', '=', 'audit_logs.user_id')
			->select(
				'audit_logs.*',
				'users.name as user_name',
				'users.email as user_email'
			);

		/*
		|--------------------------------------------------------------------------
		| MAIN LOGIC
		|--------------------------------------------------------------------------
		*/
		if (in_array($userType, [1, 2, 3])) {
			// CA / User / Admin → see own + employees

			$query->where(function ($q) use ($userId, $userType) {

				// Own logs
				$q->where('audit_logs.user_id', $userId)
				  ->where('audit_logs.user_type', $userType);

				// Employee logs
				if ($userType == 1) { // CA
					$q->orWhere(function ($e) use ($userId) {
						$e->where('users.ca_add_by', $userId)
						  ->where('audit_logs.user_type', 4);
					});
				}

				if ($userType == 2) { // User
					$q->orWhere(function ($e) use ($userId) {
						$e->where('users.user_add_by', $userId)
						  ->where('audit_logs.user_type', 5);
					});
				}

				if ($userType == 3) { // Admin
					$q->orWhere(function ($e) use ($userId) {
						$e->where('users.admin_add_by', $userId)
						  ->where('audit_logs.user_type', 6);
					});
				}
			});

		} else {
			// Employee → only self logs
			$query->where('audit_logs.user_id', $userId)
				  ->where('audit_logs.user_type', $userType);
		}

		//SEARCH LOGIC (NEW)
		$query->when($search, function ($q) use ($search) {
			$q->where(function ($s) use ($search) {
				$s->where('users.name', 'like', "%{$search}%")
				  ->orWhere('audit_logs.action', 'like', "%{$search}%")
				  ->orWhere('audit_logs.module', 'like', "%{$search}%")
				  ->orWhere('audit_logs.ip', 'like', "%{$search}%")
				  ->orWhereDate('audit_logs.created_at', $search);
			});
		});

		$logs = $query
			->latest('audit_logs.created_at')
			->get();
		return view('audit.index', compact('logs'));
	}

    



	

}
