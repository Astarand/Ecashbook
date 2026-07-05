<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_banks extends Model
{
    use HasFactory;
	protected $fillable = ['id','eid','uid','utype','emp_bank_name','emp_bank_branch','emp_bank_holder_name','emp_ac_no','emp_ifsc_code','emp_swift_code','emp_ac_upid'];
}
