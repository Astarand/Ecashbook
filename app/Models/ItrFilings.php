<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItrFilings extends Model
{
    use HasFactory;
	protected $table = 'itr_filings';

    protected $fillable = [

        'uid','utype',

        // BASIC DETAILS
        'legal_name','trade_name','pan','aadhaar','dob_inc',
        'mobile','email',

        // FILING TYPE
        'filing_individual','filing_proprietorship','filing_partnership',
        'filing_llp','filing_company',

        // INDIVIDUAL / PROPRIETORSHIP
        'ind_salary_16','ind_bank_stmt','ind_books','ind_gst_returns',
        'ind_pl','ind_bs','ind_rental','ind_other_income',

        // FIRM / LLP / COMPANY
        'firm_final_accounts','firm_bank_stmt','firm_gst_summary',
        'firm_tds','firm_depreciation','firm_loan_conf','firm_related_party',
		
		'file_req_pan','file_req_aadhaar','file_req_bank_passbook','file_req_digital_signature','file_req_prev_itr',

        // TAX DETAILS
        'tax_26as','tax_ais_tis','tax_tds_cert',
        'tax_adv_challan','tax_self_assess',

        // BUSINESS / PROFESSIONAL INFO
        'nature_business','turnover_details','asset_liab',
        'stock_summary','capital_account',

        // VERIFICATION PERSON
        'ver_name','ver_designation','ver_pan','ver_mobile',
        'ver_email','ver_dsc',

        // REQUIRED DOCUMENTS
        'req_pan','req_aadhaar','req_bank_passbook',
        'req_digital_signature','req_prev_itr',

        // CLIENT DECLARATION
        'client_name','client_designation',
        'client_signature','client_date','status'
    ];
}
