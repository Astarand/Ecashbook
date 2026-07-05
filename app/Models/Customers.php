<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $fillable = ['id','userId','utype','cust_value','gst_reg','cust_pan','cust_gst_no','cust_gst_type','cust_name','comp_type','other_comp','cin','inc_date','cust_email','cust_phone','cont_name','cont_no','cont_email','cont_notes','cust_bill_gstno','cust_bill_contact','cust_bill_mobilno','cust_bill_designa','cust_bill_name','cust_bill_addone','cust_bill_addtwo','cust_bill_country','cust_bill_state','cust_bill_city','cust_bill_pin','cust_ship_gstno','cust_ship_contact','cust_ship_mobilno','cust_ship_designa','cust_ship_name','cust_ship_addone','cust_ship_addtwo','cust_ship_country','cust_ship_state','cust_ship_city','cust_ship_pin','status'];
}
