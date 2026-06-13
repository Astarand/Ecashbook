<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTaxSlab extends Model
{
    use HasFactory;

    protected $table = 'income_tax_slabs';

    protected $fillable = [
        'entity_type',
        'company_type',
        'applicable_fy',
        'assessment_year',
        'tax_regime',
        'taxpayer_category',
        'income_slab_from',
        'income_slab_to',
        'tax_rate',
        'surcharge_rate',
        'cess_rate',
        'marginal_relief_applicable',
        'mat_applicable',
        'amt_applicable',
        'rebate_applicable',
        'rebate_section',
        'rebate_limit',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'boolean',
        'marginal_relief_applicable' => 'boolean',
        'mat_applicable' => 'boolean',
        'amt_applicable' => 'boolean',
        'rebate_applicable' => 'boolean',
        'income_slab_from' => 'decimal:2',
        'income_slab_to' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'surcharge_rate' => 'decimal:2',
        'cess_rate' => 'decimal:2',
        'rebate_limit' => 'decimal:2',
    ];
}
