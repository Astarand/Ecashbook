<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designations extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'dept_id','designation_name','created_at', 'updated_at'];
}
