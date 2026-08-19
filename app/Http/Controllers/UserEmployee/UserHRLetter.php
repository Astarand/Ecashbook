<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use PDF;

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

    public function downloadLetterPdf($id)
    {
        $userId = Auth::user()->id;
        $decryptedId = Crypt::decrypt($id);

        $letter = DB::table('company_hr_sent_letters')
            ->where('employee_id', $userId)
            ->where('id', $decryptedId)
            ->first();

        if (!$letter) {
            abort(404, 'Letter not found or unauthorized access.');
        }

        $company = DB::table('company_profiles')
            ->where('userId', $letter->added_by)
            ->select('comp_logo', 'comp_name', 'gst_reg', 'gst_no', 'comp_email', 'comp_phone', 'comp_pan_no')
            ->first();

        $companyData = $company ?? (object)[
            'comp_logo' => '',
            'comp_name' => '',
            'gst_reg' => '',
            'gst_no' => '',
            'comp_email' => '',
            'comp_phone' => '',
            'comp_pan_no' => '',
        ];

        $showGst = is_string($companyData->gst_reg)
            ? strtolower(trim($companyData->gst_reg)) === 'yes'
            : (bool) $companyData->gst_reg;

        $logoFile = trim((string) ($companyData->comp_logo ?? ''));
        $logoPath = $logoFile !== '' && file_exists(public_path('storage/profile/' . $logoFile))
            ? public_path('storage/profile/' . $logoFile)
            : public_path('storage/profile/e-cashbook.png');
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoExt = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $companyLogo = 'data:image/' . $logoExt . ';base64,' . $logoData;

        $pdf = PDF::loadView('User.hr-letter-pdf', [
            'companyData' => $companyData,
            'companyLogo' => $companyLogo,
            'letter' => $letter,
            'showGst' => $showGst,
        ]);

        $filename = preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($letter->subject ?? 'hr-letter'));

        return $pdf->download(($filename ?: 'hr-letter') . '.pdf');
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
