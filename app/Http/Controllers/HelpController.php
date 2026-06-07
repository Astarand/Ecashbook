<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company_profiles;
use App\Models\Ca_profiles;
use Auth;
use App\Http\Controllers\Helper;
use Illuminate\Support\Facades\Session;

class HelpController extends Controller
{
    public function Help()
    {
        $supportEmail = env('SUPPORT_EMAIL');   // from .env via config
        $supportMobile = env('SUPPORT_MOBILE');   // from .env via config
		return view('help-center', compact('supportEmail','supportMobile'));
    }
    public function SupportMail()
    {
		$a = rand(1, 9);
		$b = rand(1, 9);

		Session::put('human_answer', $a + $b);
		Session::put('human_question', "$a + $b = ?");
        return view('support-mail');
    }
	
	public function send(Request $request)
	{
		$request->validate([
			'subject' => 'required',
			'message' => 'required',
			'human_answer' => 'required'
		]);

		// Human verification
		if ($request->human_answer != session('human_answer')) {
			return back()->with('error', 'Human verification failed. Please try again.')
						 ->withInput();
		}

		$user = Auth::user();

		// Fetch profile based on user type
		if ($user->u_type == 1) {
			// CA user
			$profile = Ca_profiles::where('userId', $user->id)->first();
		} else {
			// Company user
			$profile = Company_profiles::where('userId', $user->id)->first();
		}

		// Decide sender name & email
		if ($profile && !empty($profile->comp_email) && !empty($profile->comp_name)) {
			$senderEmail = $profile->comp_email;
			$senderName  = $profile->comp_name;
		} else {
			$senderEmail = $user->email;
			$senderName  = $user->name;
		}

		// Render email template
		$emailBody = view('support_email_template', [
			'name'        => $senderName,
			'email'       => $senderEmail,
			'subject'     => $request->subject,
			'messageText' => $request->message,
		])->render();

		// Support email from .env
		$supportEmail = ['email' => env('SUPPORT_EMAIL')];
		$emailSubject = "Support Request - {$request->subject}";

		$sendMail = Helper::emailSendFunc($emailBody, $supportEmail, $emailSubject);

		if ($sendMail) {
			return back()->with('success', 'Your message has been sent to support.');
		} else {
			return back()->with('error', 'Failed to send message. Please try again later.');
		}
	}

	
    public function ComingSoon()
    {
        return view('coming-soon');
    }
}
