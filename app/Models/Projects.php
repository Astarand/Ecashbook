<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'added_by','proj_name','proj_cat','proj_cat','project_sub_cat','proj_status','client_name','client_contact','client_email','proj_start_date','proj_end_date','proj_cost','proj_details','status','created_at','updated_at'];
}


