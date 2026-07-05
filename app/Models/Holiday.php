<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;
    protected $fillable = [
        'holidayName',
        'holidayDate',
        'holidayType',
        'holidayDescription',
        'added_by',
        'u_type',
    ];
    
}
