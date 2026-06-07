<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartupIncubatorApplications extends Model
{
    use HasFactory;
	
	protected $table = 'startup_incubator_applications';

    protected $fillable = [
        'uid','utype',

        'business_name','founder_name','mobile','email',
        'business_address','industry_type',

        'idea_stage','prototype','early_revenue','growth_stage',

        'contact_person','designation','contact_mobile','contact_email',

        'company_registration','gst_registration','msme','pan_tan',
        'trade_license','trademark','dsc','epf_esic',
        'startup_registration','professional_tax',

        'accounting_setup','chart_accounts','tax_guidance','roc_setup','payroll',

        'business_model','swot','pricing','financial_planning',

        'pitch_deck','financial_projection','valuation','investor_connect','govt_scheme',

        'mentoring','workshop','legal_mentoring','marketing_mentoring',

        'website','crm','erp','digital_marketing','automation',

        'brand_identity','social_media','product_plan','marketing_template','dealer',

        'monthly_report','kpi','cashflow','scaling_support',

        'authorized_signatory','signatory_name','signed_date','status'
    ];

    protected $casts = [
        'signed_date' => 'date',
    ];
}
