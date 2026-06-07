<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//die('hrrr');

class Quotations extends Model
{
    use HasFactory;
	protected $fillable = ['id', 'added_by','propId','inv_num','inv_name','inv_date','udyam_reg_no','seller_name','seller_contact','seller_email','seller_pan','seller_gst','saller_gst_reg','seller_addone','seller_addtwo','seller_country','seller_state','seller_city','seller_pin','add_type','cont_person','cont_person_no','gst_reg','comp_type','cust_pan','cust_gst_no','cust_gst_type','cust_email','contact_no','branch_name','cont_per_name','cont_num','trans_type','tax_nature','tax_applicable','branch','bill_to_party','ship_to_party','transport_type','transport_type_other','bill_name','bill_addone','bill_addtwo','bill_country','bill_state','bill_city','bill_pin','ship_name','ship_addone','ship_addtwo','ship_country','ship_state','ship_city','ship_pin','disc_type','discount','tax','total_amt','signature','signature_name','special_discount','special_discount_amount','special_discount_type','gst_type','status','created_at','updated_at'];
}
