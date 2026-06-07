<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{
    use HasFactory;
    protected $fillable = ['id','added_by','propId','bank_name','bank_branch','accholder_name','bank_ac_no','ifsc_code','swift_code','upi_id','curr_bal','status','created_at','updated_at'];

}
