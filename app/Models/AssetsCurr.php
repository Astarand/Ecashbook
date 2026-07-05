<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetsCurr extends Model
{
    protected $table = 'assets_currs';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [

        'aid',
        'added_by',

        // Cash
        'cash_amount',

        // Bank
        'bank_id',
        'bank_balance',

        // Trade Receivable
        'amount',

        // Vendor Advance
        'amount_vendor',

        // Employee Advance
        'employee_advance_amount',

        // Prepaid
        'prepaid_amt',

        // ITC
        'itc_amt',

        // TDS
        'tds_gross_amount',
        'gross_profit',
    ];

    protected $casts = [
        'inv_date' => 'date',
        'due_date' => 'date',
        'invoice_date_vendor' => 'date',
        'advance_date' => 'date',
        'from_date' => 'date',
        'to_date' => 'date',
        'invoice_date_itc' => 'date',
        'tds_month' => 'date',

        'cash_amount' => 'decimal:2',
        'bank_balance' => 'decimal:2',
        'amount' => 'decimal:2',
        'gst' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'amount_vendor' => 'decimal:2',
        'pending_amount_vendor' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'employee_advance_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'monthly_allocation' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'cgst' => 'decimal:2',
        'sgst' => 'decimal:2',
        'igst' => 'decimal:2',
        'tds_gross_amount' => 'decimal:2',
    ];
	
}
