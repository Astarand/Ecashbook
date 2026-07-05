<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonCurrentLiability extends Model
{
    use HasFactory;

    protected $table = 'non_current_liabilities';

    protected $fillable = [
        'collateralDetails',
        'disbursedamount',
        'disbursementdate',
        'interestRate',
        'deferredtax',
        'conditionRestriction',
        'collateral_details',
        'empbenefitorovision',
        'description',
        'added_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}