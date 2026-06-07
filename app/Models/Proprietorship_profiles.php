<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proprietorship_profiles extends Model
{
    use HasFactory;

	protected $fillable = [
		'id',
		'userId',
		'comp_logo',
		'gst_reg',
		'gst_no',
		'comp_tran_type',
		'comp_name',
		'comp_type',
		'cin',
		'udyam_reg',
		'udyam_reg_no',
		'inc_date',
		'comp_tan',
		'comp_epf',
		'comp_esic',
		'comp_ptax_cert',
		'comp_ptax',
		'comp_email',
		'comp_phone',
		'comp_pan_no',
		'comp_website',

		'trade_license_no',
		'shop_establishment_no',
		'fema_iec_no',
		'state_excise_no',
		'basic_percentage',

		'comp_nature',
		'exact_comp_nature',
		'turnover_last_year',
		'opening_balance',
		'openingbalancecr',
		'openingbalancedr',
		'satrt_date',

		'gst_doc',
		'pan_doc',
		'tan_doc',
		'cin_doc',
		'trade_license_doc',
		'pf_certificate_doc',
		'esi_certificate_doc',
		'dire_aadha_card1_doc',
		'dire_aadha_card2_doc',
		'pan_card_photo_doc',
		'director_photo_doc',
		'other_logo_doc',
		'signature_doc',
		'stamp_doc',

		'comp_bill_gst_no',
		'comp_bill_name',
		'comp_bill_addone',
		'comp_bill_addtwo',
		'comp_bill_country',
		'comp_bill_state',
		'comp_bill_city',
		'comp_bill_pin',

		'comp_ship_gst_no',
		'comp_ship_name',
		'comp_ship_addone',
		'comp_ship_addtwo',
		'comp_ship_country',
		'comp_ship_state',
		'comp_ship_city',
		'comp_ship_pin',

		'compincorp',
		'other_specify',
		'agent_name',
		'chk_agree',
		'comp_quo_digits',
		'comp_prof_digits',
		'comp_po_digits'
	];
}
