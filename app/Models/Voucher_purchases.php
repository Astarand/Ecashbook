<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher_purchases extends Model
{
    use HasFactory;
	protected $fillable = [
        'id',
        'inv_number',
        'added_by',
        'inv_num',
        'inv_date',
        'v_num',
        'v_name',
        'seller_name',
        'seller_contact',
        'seller_email',
        'seller_addone',
        'seller_addtwo',
        'seller_country',
        'seller_state',
        'seller_city',
        'seller_pin',
        'contact_name',
        'note_date',
        'reason_issuance',
        'otherIssuance',
        'credit_debit_amount',
        'adjusted_amount',
        'date_of_payment',
        'mode_of_pay',
        'other_payment',
        'pay_status',
        'terms_delivery',
        'term_condition',
        'voucher_doc',
        'contact_no',
        'branch_name',
        'trans_type',
        'note_type',
        'return_reason',
        'purchase_no',
        'purchase_date',
        'sales_no',
        'sales_date',
        'doc_no',
        'doc_date',
        'challan_no',
        'challan_date',
        'v_date',
        'v_due_date',
        'v_no',
        'tax_nature',
        'total_amt',
        'signature',
        'signature_name',
        'status',
        'is_paid',
        'tds_applicable',
        'tds_percentage',
        'tds_amount',
        'created_at',
        'updated_at'
    ];
    
}