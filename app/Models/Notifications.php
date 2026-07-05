<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;
	protected $fillable = ['id','from_uid','to_uid','utype','noti_title','msg','url_action','status','created_at','updated_at'];
}
