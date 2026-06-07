<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_managements extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'userId','utype','task_date','task_time','company_id','task_category','task_sub_category','agent_id','gov_fees','services_charges','total_amount','advance_payment','due_amount','emp_id','due_date','project_priority','message','project_status','added_by','created_at','updated_at'];
}