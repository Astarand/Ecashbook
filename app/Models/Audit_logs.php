<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit_logs extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
		'user_id',
        'user_type',
        'action',
        'module',
        'description',
        'url',
        'method',
        'ip',
        'user_agent',
        'old_data',
        'new_data',
		'created_at', 
		'updated_at'
    ];
	
	public function getUserTypeLabelAttribute()
	{
		return [
			1 => 'CA',
			2 => 'User',
			3 => 'Admin',
			4 => 'CA Employee',
			5 => 'User Employee',
			6 => 'Admin Employee',
		][$this->user_type] ?? 'Unknown';
	}

}


