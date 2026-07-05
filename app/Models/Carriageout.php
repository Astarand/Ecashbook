<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carriageout extends Model
{
    use HasFactory;
    protected $fillable = ['id','added_by','inv_num','inv_date','seller_name','seller_contact','seller_email','seller_pan','seller_gst','seller_state','seller_city','seller_pin','cust_name','cust_contact','cust_email','cust_pan','cust_gst','cust_order_no','cust_dispatch_no','disp_through','other_dispa_det','terms_delivery','cust_addtwo','cust_state','cust_city','cust_pin','other_quantity','other_transport','other_transport_cost','other_insurance','other_tds_applicable','other_tds_percentage','other_hsn_sac_code','other_gst_rate','other_gst_mode','other_pay_date','other_mod_pay','other_pay_method','pay_status','other_total_amount','other_adv_amount','other_due_amount','other_refe_no','other_approve_by','other_term','other_uplode_doc','created_at','updated_at'];
}