<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ca_assigns extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'comp_id','utype','ca_id','set_permission','ca_assign_status','created_at', 'updated_at'];
}
