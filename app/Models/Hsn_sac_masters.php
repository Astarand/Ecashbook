<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hsn_sac_masters extends Model
{
    use HasFactory;
	protected $table = 'hsn_sac_masters';

    protected $fillable = [
        'code_type',
        'code',
        'description',
        'gst_rate',
        'apply_cond',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'gst_rate' => 'decimal:2',
    ];
}
