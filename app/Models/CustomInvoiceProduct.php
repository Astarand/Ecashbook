<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomInvoiceProduct extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'custom_invoice_product';

    // The attributes that are mass assignable
    protected $fillable = [
        'custom_invoice_id',
        'product_name',
        'price',
        'hsn_sac_code',
        'quantity',
        'gst_type',
        'cgst',
        'sgst',
        'igst',
        'total_price',
    ];
}
