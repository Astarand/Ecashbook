<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gst_returns extends Model
{
    use HasFactory;
	protected $fillable = ['id','userid','fy','quarter','period','ret_type','report_type','status','posted_date','ack_num','reference_id','req_data','res_data','created_at','updated_at'];
}
