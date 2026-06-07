<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareApplicationMoneyPendingAllotment extends Model
{
    use HasFactory;

    protected $table = 'share_application_money_pending_allotments';

    protected $fillable = [
        'applicant_name',
        'agreementId',
        'amountForShare',
        'allotmentDate',
        'advstatus',
        'advrecdate',
        'description',
        'added_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}