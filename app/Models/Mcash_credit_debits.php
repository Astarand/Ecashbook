<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcash_credit_debits extends Model
{
    use HasFactory;
    protected $fillable = ['id','added_by','propId','cd_date','particulars','cd_type','cd_amount','created_at','updated_at'];
}
