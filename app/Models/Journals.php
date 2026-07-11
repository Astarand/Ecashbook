<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journals extends Model
{
    use HasFactory;
	
	protected $fillable = [
        'autoId',
        'added_by',
        'propId',
        'journal_no',
        'journal_date',
        'reference_type',
        'reference_no',
        'entry_type',
        'source',
        'ledger',
        'party_name',
        'debit_credit',
        'amount',
        'tot_amt',
        'payment_status',
        'notes',
        'other_note',
		'tds_applicable',
		'tds_percent',
		'tds_amt',
		'tds_id',
		'gst_applicable',
		'hsn_sac_code',
		'gst_rate',
		'gst_trans',
		'status',
		'rev_amend_status',
        'settlement_type',
        'narration',
        'against_ledger',
    ];

    public function attachments()
    {
        return $this->hasMany(JournalAttachments::class);
    }
}
