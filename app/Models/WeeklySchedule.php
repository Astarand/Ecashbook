<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklySchedule extends Model
{
    use HasFactory;
    protected $table = 'weekly_schedules';

    protected $fillable = [
        'day',
        'opening_time',
        'closing_time',
        'lunch_time_start',
        'lunch_time_stop',
        'status',
        'working_hours',
        'added_by',
        'u_type'
    ];
}
