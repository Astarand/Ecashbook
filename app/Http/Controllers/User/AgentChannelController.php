<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentChannelController extends Controller
{
    public function AgentChannelList()
    {
        return view('User.agent-channel-list');
    }

    public function AddAgentChannelList()
    {
        return view('User.add-agent-channel-list');
    }
}
