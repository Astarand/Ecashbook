<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentVoucher extends Model
{
    use HasFactory;
	
	protected $table = 'payment_vouchers';

    protected $fillable = [
        'added_by',
        'propId',
        'f_id',
        'source',
        'voucher_type',
        'date',
        'voucher_no',
        'party_type',
        'other_party_type',
        'party_id',
        'party_name',
        'transaction_details',
        'other_transaction_details',
        'invoice_no',
        'amount',
        'credit_debit',
        'payment_mode',
        'bank_id',
        'is_paid',
        'reference_id',
        'narration',
        'attachment',
        'approved_by',
        'record_type'
    ];
}
