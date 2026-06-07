<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_tickets extends Model
{
    use HasFactory;
	protected $fillable = [
        'id',
        'added_by',
        'utype',
        'compId',
        'due_date',
        'msg',
        'priority',
        'isActive',
        'created_at',
        'updated_at',
       
    ];
}
