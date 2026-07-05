<?php

namespace App\Http\Controllers\UserEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserTaskManagement extends Controller
{
   public function AssignTaskList()
    {
        return view('Employee.UserEmployee.task-management-list');
    }
}
