<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'empId',
        'added_by',
        'review_month',
        'review_year',
        'work_rating',
        'skill_rating',
        'attendance_rating',
        'teamwork_rating',
        'total_percentage',
        'review'
    ];
}

