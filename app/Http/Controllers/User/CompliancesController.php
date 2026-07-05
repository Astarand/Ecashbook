<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompliancesController extends Controller
{
    public function CompliancesList()
    {
        return view('user.compliances-list');
    }

    public function CompliancesChat()
    {
        return view('user.compliances-chat');
    }
}
