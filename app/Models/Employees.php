<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// class Employees extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'id', 'added_by','propId', 'empId', 'employee_id', 'dept_id', 'desig_id', 'location_id', 'dob', 'gender', 'email_id', 
//         'qualification', 'profile_img', 'c_addr_lineone', 'c_addr_linetwo', 'c_emp_country', 'c_emp_state', 
//         'c_emp_city', 'c_emp_pincode', 'p_addr_lineone', 'p_addr_linetwo', 'p_emp_country', 'p_emp_state', 
//         'p_emp_city', 'p_emp_pincode', 'basic_sal','basic_percentage', 'hra', 'convayance', 'special_bonus', 'provident_fund', 
//         'esi', 'loan', 'ptax', 'tds', 'total_deduction', 'total_addition', 'net_sal', 'net_sal_word', 
//         'bank_name', 'bank_branch', 'ifsc', 'swift_code', 'account_holder_name', 'account_number', 'upi_id', 
//         'joining_date', 'work_location', 'regine_date', 'regine_document', 
//         'aadhar_doc', 'pan_doc', 'cancelled_cheque_doc', 'cv_doc', 'last_qualification_doc','signed_appointment_letter', 'last_company_release_letter', 'offer_letter', 'other_doc',
//         'created_at', 'updated_at'
//     ];
// }


class Employees extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic Info
        'added_by','propId','empId','employee_id',
        'dept_id','desig_id','location_id',
        'dob','gender','aadhaar_number','pan_number',
        'email_id','alt_phone','qualification','marital_status',
        'pro_qualification','last_employer','experience_years',

        // Profile
        'profile_img',

        // Current Address
        'c_addr_lineone','c_addr_linetwo','c_emp_country','c_emp_state',
        'c_emp_city','c_emp_pincode',

        // Permanent Address
        'p_addr_lineone','p_addr_linetwo','p_emp_country','p_emp_state',
        'p_emp_city','p_emp_pincode',

        // References & Emergency
        'ref1_name','ref1_mobile','ref2_name','ref2_mobile',
        'emergency_name','emergency_mobile',

        // Salary Structure
        'basic_sal','basic_percentage','hra','convayance','medical_allowance','special_bonus',
        'epf_applicable','esic_applicable','ptax_applicable','tds_applicable',
        'epf_no','esic_no','provident_fund','esi','loan',
        'loan_tenure','loan_deduction','ptax','tds',
        'total_deduction','total_addition','net_sal','net_sal_word',

        // Bank Details
        'bank_name','bank_branch','ifsc','swift_code',
        'account_holder_name','account_number','upi_id',

        // Job Details
        'joining_date','work_location','emp_status','statusdate','emp_type',

        // ✅ Resignation (IMPORTANT)
        'regine_date','regine_document',

        // Documents
        'aadhar_doc','pan_doc','cancelled_cheque_doc','cv_doc',
        'last_qualification_doc','signed_appointment_letter',
        'last_company_release_letter','offer_letter','other_doc',

        // Policy
        'privacy_policy_read','terms_and_conditions'
    ];
}


