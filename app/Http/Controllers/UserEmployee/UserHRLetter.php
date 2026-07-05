<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class UserHRLetter extends Controller
{
    public function HRLetterList()
    {
        $userId = Auth::user()->id;

        // Fetch letters from company_hr_sent_letters table
        $letters = \DB::table('company_hr_sent_letters')
                    ->where('employee_id', $userId)
                    ->orderBy('sent_at', 'desc')
                    ->get();

        return view('Employee.UserEmployee.hr-letter-list', compact('letters'));
    }
    public function HRLetterView($id)
    {
        $userId = Auth::user()->id;
        $decryptedId = Crypt::decrypt($id);
        // Fetch single letter (verify that it belongs to logged-in user)
        $letter = DB::table('company_hr_sent_letters')
                    ->where('employee_id', $userId)
                    ->where('id', $decryptedId)
                    ->first();

        if (!$letter) {
            abort(404, 'Letter not found or unauthorized access.');
        }

        return view('Employee.UserEmployee.view-hr-letter', compact('letter'));
    }
    public function generatePayslip()
    {
        $userId = Auth::user()->id;
        $payslips = DB::table('user_payslip')
            ->select('id', 'financial_year', 'month', 'payslip_path')
            ->where('user_emp_id', $userId)
            ->orderByDesc('id')
            ->get();

        return view('Employee.UserEmployee.employee_payslip', compact('payslips'));
    }
}
