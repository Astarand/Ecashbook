<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msme_applications extends Model
{
    use HasFactory;
	
	protected $fillable = [
		'uid',
		'utype',
        'applicant_name',
        'company_name',
        'mobile',
        'email',
        'udyam_no',
        'preferred_service',
        'details',
		'status',
		'created_at',
		'updated_at'
    ];
}
