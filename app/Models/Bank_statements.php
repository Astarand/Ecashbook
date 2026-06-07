<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank_statements extends Model
{
    use HasFactory;
	protected $fillable = ['id','added_by','bank_id','startdate','enddate','created_at','updated_at'];
}
