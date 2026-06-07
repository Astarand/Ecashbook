<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentsHistory extends Model
{
    use HasFactory;
    protected $table = 'payments_history';

    protected $fillable = [
        'sales_inv_id',
        'invoice_num',
        'pay_amount',
        'payment_mode',
        'received_by',
        'pay_type',
        'payment_date',
    ];
}
