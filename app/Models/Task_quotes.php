<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_quotes extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'userId','utype','task_cat','task_sub_cat','govfee','service_charge','created_at','updated_at'];
}