<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TdsSalarySlab extends Model
{
    use HasFactory;

    protected $table = 'tds_salary_slabs';

    protected $fillable = [
        'tds_rule_id',
        'from_amount',
        'to_amount',
        'tax_rate'
    ];

    public $timestamps = false;

    /**
     * Relationship: Parent TDS Rule
     */
    public function tdsRule()
    {
        return $this->belongsTo(TdsTaxSlab::class, 'tds_rule_id');
    }
}
