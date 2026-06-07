<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $table = 'income'; 

    protected $fillable = [
        'addBy',
        'propId',
        'dateInput',
        'name',
        'incomeType',
        'categoryIncome',
        'other_income',
        'amount',
        'receivable_amt',
        'adjust_amt',
        'advance_amt',
        'invoice_no',
        'pay_status',
        'due_date',
        'pay_mode',
        'customer_id',
        'specification',
        'tds_applicable',
        'tds_percentage',
        'tds_amount',
        'tds_id',
		
		'gst_applicable',
		'gst_rate',
		'gst_amt',
		'gst_trans',
		'gst_allocation',
		'income_doc',
		'status',
        'created_at',
        'updated_at'
    ];
}
