<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenditureClaim extends Model
{
    protected $fillable = [
        'employee_id',
        'claim_date',
        'category',
        'claim_amount',
        'description',
        'payment_method',
        'receipt',
        'comments',
        'status',
        'added_by',
        'u_type'
    ];
}
