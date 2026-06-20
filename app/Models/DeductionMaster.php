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

        'limit_type',
        'base_amount_source',
        'automation_mode',

        'limit_value',
        'limit_formula',
        'applicable_fy',

        'linked_module',
        'remarks',

        'active_status',
        'allow_disallow_status',
    ];
}
