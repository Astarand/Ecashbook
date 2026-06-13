<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionMaster extends Model
{
    protected $fillable = [
        'deduction_name',
        'income_tax_section',
        'deduction_category',
        'deduction_type',
        'amount',
        'maximum_limit',
        'applicable_fy',
        'deduction_mode',
        'linked_module',
        'active_status',
        'deduction_amount_logic'
    ];
}
