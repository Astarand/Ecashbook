<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MSMEBenefitHubController extends Controller
{
    /**
     * Display Discover Schemes page.
     */
    public function discoverSchemes()
    {
        return view('User.MSME.Discover-Schemes');
    }

    /**
     * Display Eligibility Checker page.
     */
    public function eligibilityChecker()
    {
        return view('User.MSME.Eligibility-checker');
    }

    /**
     * Display Loan and Subsidies page.
     */
    public function loanAndSubsidies()
    {
        return view('User.MSME.Loan-and-subsidies');
    }

    /**
     * Display Startup Benefits page.
     */
    public function startupBenefits()
    {
        return view('User.MSME.Startup-benifits');
    }

    /**
     * Display Govt Updates page.
     */
    public function govtUpdates()
    {
        return view('User.MSME.Govt-updates');
    }

    /**
     * Display Consultant Assistance page.
     */
    public function consultantAssistance()
    {
        return view('User.MSME.Consultant-assistance');
    }
}
