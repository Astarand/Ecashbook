<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentLiability extends Model
{
    use HasFactory;

    protected $table = 'current_liabilities';

    protected $fillable = [
        'CurrentLiabilitiesType',
        'accruedexpensesType',
        'gst_payable_type',
        'gst_payableamount',
        'gst_payableDate',
        'advances_cust_type',
        'advpaid',
        'advstatus',
        'advrecdate',
        'adjustdetails',
        'account_credited',
        'added_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}