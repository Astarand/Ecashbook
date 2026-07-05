<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors'; // Ensure Laravel maps the model to the correct table

    protected $fillable = [
        'userId', 'vendor_id', 'utype', 'vendor_priority', 'vendor_name', 'vendor_pan', 'vendor_gstin', 'vendor_gst_type', 
        'vendor_email', 'vendor_phone', 'cont_per_name', 'cont_per_number', 'cont_per_email', 'special_note', 
        'billing_name', 'billing_address1', 'billing_address2', 'billing_country', 'billing_state', 'billing_city', 
        'billing_pincode', 'shipping_name', 'shipping_address1', 'shipping_address2', 'shipping_country', 
        'shipping_state', 'shipping_city', 'shipping_pincode', 'gst_reg', 'company_name', 'comp_type', 
        'other_comp', 'cin', 'inc_date', 'cust_bill_gstno', 'cust_bill_contact', 'cust_bill_mobilno', 
        'cust_bill_designa', 'cust_ship_gstno', 'cust_ship_contact', 'cust_ship_mobilno', 'cust_ship_designa', 
        'status', 'created_at', 'updated_at'
    ];
}

