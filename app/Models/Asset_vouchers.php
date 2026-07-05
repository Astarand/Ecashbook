<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset_vouchers extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'added_by','propId','v_type','voucher_no','voucher_name','branch_name','series_id','invoice_date','vendor_id','inv_voucher_no','total_cost','created_at','updated_at'];

}
