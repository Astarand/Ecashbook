<?php

// app/Models/InventoryExpense.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryExpenses extends Model
{
    protected $table = 'inventory_expenses';

    protected $fillable = [
		'uid',
		'propId',
        'expense_types',
        'expense_voucher_no',
        'expense_date',
        'purchase_invoice_ref_no',
        'supplier_name',
        'supplier_gstin',
        'expense_amount',
        'gst_amount',
        'gst_rate',
        'stock_location',
        'allocation_basis',
        'allocated_units',
        'cost_allocation_amount',
        'remarks',
		'tds_applicable',
        'tds_percentage',
        'tds_amount',
        'tds_id',
		
		'gst_applicable',
		'gst_rate',
		'gst_amt',
		'gst_trans',
		'gst_allocation',
		'itc_applicable',
        'supporting_document'
    ];
}
