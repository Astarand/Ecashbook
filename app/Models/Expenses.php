<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'added_by',
        'propId',

        // Basic Expense Info
        'expense_date',
        'pur_of_expense',
        'mode_of_expense',
        'expense_cat',
        'expense_type',
        'other_expenses_details',
        'expense_amt',
        'expense_msg',
        'exp_invno',

        // Employee
        'employee_id',
        'employee_code',

        // Approval
        'approved_by',
        'designation',
        'approved_date',
        'spec_note',

        // File
        'exp_inv_doc',

        // Status
        'status',

        // ✅ PAYMENT (NEW)
        'payment_status',
        'advance_amount',
        'balance_amount',
        'adjusted_now',

        // ✅ TDS
        'tds_applicable',
        'tds_percentage',
        'tds_id',
        'tds_amount',
        'tds_section',
        'tds_rate',
        'tds_threshold_limit',

        // ✅ GST
        'gst_applicable',
        'gst_trans',
        'gst_rate',
        'gst_allocation',
        'total_gst',
        'itc_eligibility',

        // ✅ Depreciation Fields
        'dep_start_date',
        'dep_frequency',
        'useful_life',
        'dep_method',
        'dep_value',
        'residual_value',

        // ✅ Vendor
        'vendor_id',
        'vendor_pan',

        // Timestamps
        'created_at',
        'updated_at',
    ];
}
