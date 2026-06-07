<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets_ncs extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'pid',
        'asid',
		'addedby',
		'nonCurrentAssetType',
		'category_of_head_nca',
		'party_name_nca',
		'voucher_type_nca',
		'amt_nca',
		'debitcredit_nca',
		'due_date_nca',
		'invoice_no_nca',
		'notes_nca',
		'location_nca',
		'tds_applicable_nca',
		'tds_percent_nca',
		'tds_amt_nca',
		'tds_id_nca',
		'gst_applicable_nca',
		'hsn_sac_code_nca',
		'gst_rate_nca',
		'gst_trans_nca',

		
        'created_at',
        'updated_at',
       
    ];
}
