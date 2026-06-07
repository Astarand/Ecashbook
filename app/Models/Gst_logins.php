<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gst_logins extends Model
{
    use HasFactory;
	protected $fillable = ['id','user_id','app_env','gst_username','gstno','txn','auth_token','refresh_token','otp','created_at','updated_at'];
}
