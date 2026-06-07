<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assets_cs extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'prid',
        'aid',
		'uid',
		'currentAssetType',
		'category_of_head',
		'party_name',
		'voucher_type',
		'amt',
		'debitcredit',
		'due_date',
		'invoice_no',
		'notes',
		'location',
		'tds_applicable',
		'tds_percent',
		'tds_amt',
		'tds_id',
		'gst_applicable',
		'hsn_sac_code',
		'gst_rate',
		'gst_trans',

		
        'created_at',
        'updated_at',
       
    ];
}
