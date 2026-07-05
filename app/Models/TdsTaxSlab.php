<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdsTaxSlab extends Model
{
    use HasFactory;

    protected $table = 'tds_rules';

    protected $fillable = [
        'module',
        'category',
        'tds_section',
        'tds_rate',
        'entity',
        'threshold_limit',
        'notes',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relationship: Salary slabs (Section 192)
     */
    public function salarySlabs()
    {
        return $this->hasMany(TdsSalarySlab::class, 'tds_rule_id');
    }
}
