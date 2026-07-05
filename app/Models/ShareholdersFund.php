<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareholdersFund extends Model
{
    use HasFactory;

    protected $table = 'shareholders_funds';

    protected $fillable = [
        'classofshares',
        'facevaluepershare',
        'amountForShare',
        'amountForsurplus',
        'basisnotes',
        'calculbasis',
        'allotmentDate',
        'description',
        'added_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}