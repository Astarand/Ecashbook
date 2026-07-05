<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carriageinwards extends Model
{
    use HasFactory;
    protected $fillable = ['id','added_by','inv_num','inv_date','buyer_name','buyer_contact','buyer_email','buyer_pan','buyer_gst','buyer_addone','buyer_addtwo','buyer_state','buyer_city','buyer_pin','vendor_name','vendor_contact','vendor_email','vendor_pan','vendor_gst','vendor_order_no','vendor_dispatch_no','disp_through','other_dispa_det','terms_delivery','vendor_addone','vendor_addtwo','vendor_state','vendor_city','vendor_pin','other_quantity','other_transport','other_transport_cost','other_insurance','other_tds_applicable','other_tds_percentage','other_hsn_sac_code','other_gst_rate','other_gst_mode','other_pay_date','other_mod_pay','other_pay_method','pay_status','other_total_amount','other_adv_amount','other_due_amount','other_refe_no','other_approve_by','other_term','other_uplode_doc','created_at','updated_at'];
}