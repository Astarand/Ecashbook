<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank_trans extends Model
{
    use HasFactory;
    protected $fillable = ['id','added_by','prop_id','bankId','tran_date','payment_mode','tran_amt','tran_type','ref_no','purpose','curr_amt','message','tran_doc','created_at','updated_at'];

}
