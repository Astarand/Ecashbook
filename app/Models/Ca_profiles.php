<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ca_profiles extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'userId',
        'comp_logo',
        'comp_gst_no',
        'comp_name',
        'comp_email',
        'comp_phone',
        'no_ca_firm',
        'no_employee',
        'total_no_client',
        'about_firm',
        'comp_pan_no',
        'comp_website',
        'comp_nature',
        'exact_comp_nature',
        'turnover_last_year',
        'no_of_project',
        'credit_period',
        'credit_limit',
        'gst_doc',
        'pan_doc',
        'tan_doc',
        'cin_doc',
        'other_logo_doc',
        'signature_doc',
        'stamp_doc',
        'comp_bill_name',
        'comp_bill_addone',
        'comp_bill_addtwo',
        'comp_bill_country',
        'comp_bill_state',
        'comp_bill_city',
        'comp_bill_pin',
        'type_of_firm',
        'constitution_type',
        'year_of_experience',
        'software_licenses',
        'tan_no',
        'pt_reg_no',
        'epf_reg_no',
        'esic_reg_no',
        'comp_ship_name',
        'comp_ship_addone',
        'comp_ship_addtwo',
        'comp_ship_country',
        'comp_ship_state',
        'comp_ship_city',
        'comp_ship_pin',
        'ca_spec',
        'other_service_text',
        'chk_agree',
    ];
}
