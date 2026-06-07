<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans extends Model
{
    use HasFactory;

    protected $table = 'loans'; // Ensure this matches your database table name

    protected $fillable = [
        'id',
        'added_by',
        'bank_name',
        'branch',
        'app_name',
        'loan_ac_no',
        'bank_code',
        'credit_limit',
        'ifsc_code',
        'lone_type',
        'upi_id',
        'total_lone_amount',
        'remains_loan_amount',
        'status',
        'created_at',
        'updated_at',
    ];
}
