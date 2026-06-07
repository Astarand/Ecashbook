<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McaRocApplications extends Model
{
    use HasFactory;
	protected $fillable = [
        'uid','utype',
        'company_name','cin','pan','reg_office_address','mca_email',
        'mobile','inc_date','nic_code',
        'event_change_director','event_change_reg_office','event_share_allotment',
        'event_transfer_shares','event_appointment_auditor','event_resignation_auditor',
        'doc_moa_aoa','doc_coi','doc_prev_roc','doc_dsc_auth','doc_auditor_appointment',
		'file_doc_moa_aoa','file_doc_coi','file_doc_prev_roc','file_doc_dsc_auth','file_doc_auditor_appointment',
        'client_name','designation','signature','signed_date','created_at','updated_at'
    ];
}
