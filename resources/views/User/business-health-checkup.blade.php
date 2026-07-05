@extends('App.Layout')

@section('container')

<div class="pc-content">

    {{-- PAGE TITLE --}}
    <div class="page-header" 
		 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
				border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
		<div class="page-block">
			<div class="row align-items-center">
				<div class="col-md-12">
					<div class="d-flex justify-content-between align-items-center text-white">
						
						<!-- Left: Title -->
						<div class="d-flex align-items-center">
							<i class="ph-duotone ph-heart-straight fs-1 me-3"></i>
							<div>
								<h3 class="mb-1 fw-bold text-white">Company Health Check-up</h3>
								<p class="mb-0 opacity-75 small">
									Complete assessment of your company's compliance and operational status
								</p>
							</div>
						</div>

						<!-- Right: Back Button -->
						<div>
							<a href="{{ route('admin.company.checks') }}" 
							   class="btn btn-light fw-semibold">
								<i class="ph ph-arrow-left me-1"></i> Back
							</a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>


    {{-- MAIN CARD --}}
    <div class="row mt-3">
        <div class="col-lg-11 mx-auto">
			@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
            <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <div class="card-body px-4 py-4" style="background: #f8f9fa;">

                    {{-- ONE BIG FORM WITH TWO STEPS --}}
                    <form name="companyHealthForm" id="companyHealthForm" action="javascript:void(0);" method="post" enctype="multipart/form-data">
						@csrf
                        {{-- STEP 1: COMPANY DETAILS + DOCUMENT UPLOAD (IMAGE 1 & 2) --}}
                        <div id="step1Section">
                            <div class="mb-4 p-3 rounded" style="background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%); border-left: 4px solid #f39c12;">
                                <h5 class="fw-bold mb-1 d-flex align-items-center">
                                    <i class="ph-duotone ph-building-office me-2 fs-4"></i>
                                    Company Details
                                </h5>
                                <small class="text-dark opacity-75">
                                    <i class="ph-duotone ph-info me-1"></i>
                                    Existing information will be auto-fetched, rest will be filled up
                                </small>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-buildings me-1 text-primary"></i>Company Name</label>
                                    <input type="text" name="comp_name" id="comp_name" value="{{ $profile->comp_name ?? '' }}" class="form-control" placeholder="Enter company name" style="border-radius: 6px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-identification-card me-1 text-primary"></i>Registration Number (CIN)</label>
                                    <input type="text" class="form-control" name="cin" id="cin" value="{{ $profile->cin ?? '' }}" placeholder="Enter CIN number" style="border-radius: 6px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-calendar me-1 text-primary"></i>Incorporation Date</label>
                                    <input type="date" name="inc_date" id="inc_date" value="{{ $profile->inc_date ?? '' }}" class="form-control" style="border-radius: 6px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-tag me-1 text-primary"></i>Category of Business</label>
                                    <input type="text"  name="comp_type" id="comp_type" value="{{ $profile->comp_type ?? '' }}"  class="form-control" placeholder="Enter business category" style="border-radius: 6px;">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-map-pin me-1 text-primary"></i>Registered Office Address</label>
                                    <textarea rows="2" name="comp_addr" id="comp_addr" class="form-control" placeholder="Enter complete registered address" style="border-radius: 6px;">{{ $profile->comp_bill_addone ?? '' }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-git-branch me-1 text-primary"></i>No. of Branch</label>
                                    <input type="number"  name="no_of_branch" id="no_of_branch" class="form-control" placeholder="0" style="border-radius: 6px;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-currency-inr me-1 text-success"></i>Annual Turnover / Revenue</label>
                                    <input type="text"   name="turnover_last_year" id="turnover_last_year"  value="{{ $profile->turnover_last_year ?? '' }}" class="form-control" placeholder="₹0.00" style="border-radius: 6px;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-credit-card me-1 text-primary"></i>Company PAN No.</label>
                                    <input type="text"  name="comp_pan_no" id="comp_pan_no"  value="{{ $profile->comp_pan_no ?? '' }}" class="form-control" placeholder="ABCDE1234F" style="border-radius: 6px; text-transform: uppercase;">
                                </div>
                            </div>
                                </div>
                            </div>

                            {{-- Licenses & Registrations Section --}}
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
                                <div class="card-header bg-white border-bottom" style="border-radius: 8px 8px 0 0;">
                                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                        <i class="ph-duotone ph-certificate me-2 text-info"></i>
                                        Licenses & Registrations
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Trade License</label>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="trade_license" value="yes" data-target="trade_license_input" id="trade_yes">
                                            <label class="form-check-label fw-semibold text-success" for="trade_yes"><i class="ph-duotone ph-check-circle me-1"></i>Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="trade_license" value="no"  data-target="trade_license_input" id="trade_no">
                                            <label class="form-check-label fw-semibold text-danger" for="trade_no"><i class="ph-duotone ph-x-circle me-1"></i>No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="trade_license_input" id="trade_license_input" class="form-control mt-1"
                                           placeholder="Municipality Name / Reg. No." style="display: none; border-radius: 6px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Shop &amp; Establishment</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="shop_est" value="yes" data-target="shop_est_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="shop_est" value="no" data-target="shop_est_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="shop_est_input" id="shop_est_input" class="form-control form-control-sm mt-1"
                                           placeholder="Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Professional Tax Registration</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="prof_tax" value="yes" data-target="prof_tax_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="prof_tax" value="no" data-target="prof_tax_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="prof_tax_input" id="prof_tax_input" class="form-control form-control-sm mt-1"
                                           placeholder="Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Company GST No.</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="gst" value="yes" data-target="gst_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="gst" value="no" data-target="gst_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="gst_input" id="gst_input" class="form-control form-control-sm mt-1"
                                           placeholder="GST Reg. No." style="display: none;">
                                </div>

                            </div>
                                </div>
                            </div>

                            {{-- Business Information Section --}}
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
                                <div class="card-header bg-white border-bottom" style="border-radius: 8px 8px 0 0;">
                                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                                        <i class="ph-duotone ph-briefcase me-2 text-warning"></i>
                                        Business Information
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Business Type (Product / Service / Both)</label>
                                    <input type="text" name="buss_type" id="buss_type" value="{{ $profile->comp_nature ?? '' }}" class="form-control" placeholder="Enter business type" style="border-radius: 6px;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Industry / Sector</label>
                                    <input type="text"  name="industry" id="industry" value="{{ $profile->comp_type ?? '' }}" class="form-control"
                                           placeholder="If category available on portal then auto fetched" style="border-radius: 6px;">
                                </div>

                                {{-- License / registration yes/no fields --}}
                                <div class="col-md-6">
                                    <label class="form-label">TAN No.</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="tan" value="yes" data-target="tan_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="tan" value="no" data-target="tan_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="tan_input" id="tan_input" class="form-control form-control-sm mt-1"
                                           placeholder="TAN Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">MSME / UDYAM</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="msme" value="yes" data-target="msme_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="msme" value="no" data-target="msme_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="msme_input" id="msme_input" class="form-control form-control-sm mt-1"
                                           placeholder="UDYAM Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">EPF</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="epf" value="yes" data-target="epf_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="epf" value="no" data-target="epf_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="epf_input" id="epf_input" class="form-control form-control-sm mt-1"
                                           placeholder="EPF Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">ESI</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="esi" value="yes" data-target="esi_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="esi" value="no" data-target="esi_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="esi_input" id="esi_input" class="form-control form-control-sm mt-1"
                                           placeholder="ESI Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Industry Certifications (ISO, Quality etc.)</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="industry_cert" value="yes" data-target="industry_cert_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="industry_cert" value="no" data-target="industry_cert_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="industry_cert_input" id="industry_cert_input" class="form-control form-control-sm mt-1"
                                           placeholder="Details" style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">FSSAI License</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="fssai" value="yes" data-target="fssai_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="fssai" value="no" data-target="fssai_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="fssai_input" id="fssai_input" class="form-control form-control-sm mt-1"
                                           placeholder="FSSAI Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pollution Control Board Clearances</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="pollution_cert" value="yes" data-target="pollution_cert_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="pollution_cert" value="no" data-target="pollution_cert_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="pollution_cert_input" id="pollution_cert_input" class="form-control form-control-sm mt-1"
                                           placeholder="Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Import Export Code (IEC)</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="iec" value="yes" data-target="iec_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="iec" value="no" data-target="iec_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="iec_input" id="iec_input" class="form-control form-control-sm mt-1"
                                           placeholder="IEC Reg. Code" style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Factory License / Registration</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="factory_license" value="yes" data-target="factory_license_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="factory_license" value="no" data-target="factory_license_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="factory_license_input" id="factory_license_input" class="form-control form-control-sm mt-1"
                                           placeholder="Reg. No." style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Fire License (NOC)</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="fire_noc" value="yes" data-target="fire_noc_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="fire_noc" value="no" data-target="fire_noc_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="fire_noc_input" id="fire_noc_input" class="form-control form-control-sm mt-1"
                                           placeholder="Fire NOC No." style="display: none;">
                                </div>

                                {{-- Website / software availability --}}
                                <div class="col-md-6">
                                    <label class="form-label">Website Available</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="website" value="yes" data-target="website_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="website" value="no" data-target="website_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="website_input" id="website_input" class="form-control form-control-sm mt-1"
                                           placeholder="URL Address" style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">HR &amp; Payroll Management Software</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="hr_payroll" value="yes" data-target="hr_payroll_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="hr_payroll" value="no" data-target="hr_payroll_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="hr_payroll_input" id="hr_payroll_input" class="form-control form-control-sm mt-1"
                                           placeholder="Details" style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Accounting Software Available</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="acc_soft" value="yes" data-target="acc_soft_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="acc_soft" value="no" data-target="acc_soft_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="acc_soft_input" id="acc_soft_input" class="form-control form-control-sm mt-1"
                                           placeholder="Details" style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Any ERP / CRM / App Available</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="erp_crm" value="yes" data-target="erp_crm_input">
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input radio-toggle" type="radio" name="erp_crm" value="no" data-target="erp_crm_input">
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                    <input type="text" name="erp_crm_input" id="erp_crm_input" class="form-control form-control-sm mt-1"
                                           placeholder="Details" style="display: none;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-users me-1 text-primary"></i>Number of Employees</label>
                                    <div class="d-flex gap-2">
                                        <input type="number" name="per_emp" id="per_emp" class="form-control"
                                               placeholder="Permanent" style="border-radius: 6px;">
                                        <input type="number"  name="con_emp" id="con_emp" class="form-control"
                                               placeholder="Contractual" style="border-radius: 6px;">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-dark"><i class="ph-duotone ph-money me-1 text-success"></i>Employees on Salary Range</label>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <input type="number" name="sal_range_one" id="sal_range_one" class="form-control"
                                                   placeholder="₹0 – ₹15,000 per month" style="border-radius: 6px;">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="sal_range_two" id="sal_range_two" class="form-control"
                                                   placeholder="₹15,001 – ₹21,000 per month" style="border-radius: 6px;">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="sal_range_three" id="sal_range_three" class="form-control"
                                                   placeholder="₹21,000 & Above" style="border-radius: 6px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>

                            {{-- UPLOAD DOCUMENTS (IMAGE 2) --}}
                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
                                <div class="card-header border-bottom" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); border-radius: 8px 8px 0 0;">
                                    <h5 class="fw-bold mb-1 text-white d-flex align-items-center">
                                        <i class="ph-duotone ph-upload-simple me-2 fs-4"></i>
                                        Upload the Documents
                                    </h5>
                                    <small class="text-white opacity-75">
                                        <i class="ph-duotone ph-info me-1"></i>
                                        If previously uploaded, then auto fetch
                                    </small>
                                </div>
                                <div class="card-body p-4">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="inc_cert_chk" id="inc_cert_chk" data-target="inc_cert_doc">
                                                <label class="form-check-label" for="inc_cert_chk">Incorporation Certificate</label>
												<div id="inc_cert_doc_link"></div>
                                            </div>
                                            <input type="file" name="inc_cert_doc" id="inc_cert_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="comp_pan_chk" id="comp_pan_chk" data-target="comp_pan_doc">
                                                <label class="form-check-label" for="comp_pan_chk">Company PAN Card</label>
												<div id="comp_pan_doc_link"></div>
                                            </div>
                                            <input type="file" name="comp_pan_doc" id="comp_pan_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="trade_chk" id="trade_chk" data-target="trade_doc">
                                                <label class="form-check-label" for="trade_chk">Trade License</label>
												<div id="trade_doc_link"></div>									
                                            </div>
                                            <input type="file" name="trade_doc" id="trade_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="shop_est_chk" id="shop_est_chk" data-target="shop_est_doc">
                                                <label class="form-check-label" for="shop_est_chk">Shop &amp; Establishment</label>
												<div id="shop_est_doc_link"></div>									
											</div>
                                            <input type="file" name="shop_est_doc" id="shop_est_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="ptax_chk" id="ptax_chk" data-target="ptax_doc">
                                                <label class="form-check-label" for="ptax_chk">Professional Tax Registration</label>
												<div id="ptax_doc_link"></div>									
											</div>
                                            <input type="file" name="ptax_doc" id="ptax_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="gst_chk" id="gst_chk" data-target="gst_doc">
                                                <label class="form-check-label" for="gst_chk">Company GST Certificate</label>
												<div id="gst_doc_link"></div>									
                                            </div>
                                            <input type="file" name="gst_doc" id="gst_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="tan_chk" id="tan_chk" data-target="tan_doc">
                                                <label class="form-check-label" for="tan_chk">TAN Registration</label>
												<div id="tan_doc_link"></div>									
                                            </div>
                                            <input type="file" name="tan_doc" id="tan_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="msme_chk" id="msme_chk" data-target="msme_doc">
                                                <label class="form-check-label" for="msme_chk">MSME Certificate</label>
												<div id="msme_doc_link"></div>									
                                            </div>
                                            <input type="file" name="msme_doc" id="msme_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="epf_chk" id="epf_chk" data-target="epf_doc">
                                                <label class="form-check-label" for="epf_chk">EPF Certificate</label>
												<div id="epf_doc_link"></div>									
                                            </div>
                                            <input type="file" name="epf_doc" id="epf_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="esi_chk" id="esi_chk" data-target="esi_doc">
                                                <label class="form-check-label" for="esi_chk">ESI No Certificate</label>
												<div id="esi_doc_link"></div>									
                                            </div>
                                            <input type="file" name="esi_doc" id="esi_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="ind_chk" id="ind_chk" data-target="ind_doc">
                                                <label class="form-check-label" for="ind_chk">Industry Certifications</label>
												<div id="ind_doc_link"></div>									
                                            </div>
                                            <input type="file" name="ind_doc" id="ind_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="fssai_chk" id="fssai_chk" data-target="fssai_doc">
                                                <label class="form-check-label" for="fssai_chk">FSSAI License</label>
												<div id="fssai_doc_link"></div>									
                                            </div>
                                            <input type="file" name="fssai_doc" id="fssai_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="poll_chk" id="poll_chk" data-target="poll_doc">
                                                <label class="form-check-label" for="poll_chk">Pollution Certificate</label>
												<div id="poll_doc_link"></div>									
                                            </div>
                                            <input type="file" name="poll_doc" id="poll_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="import_chk" id="import_chk" data-target="import_doc">
                                                <label class="form-check-label" for="import_chk">Import Export Code (IEC)</label>
												<div id="import_doc_link"></div>									
											</div>
                                            <input type="file" name="import_doc" id="import_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="fact_chk" id="fact_chk" data-target="fact_doc">
                                                <label class="form-check-label" for="fact_chk">Factory License / Registration</label>
												<div id="fact_doc_link"></div>									
											</div>
                                            <input type="file" name="fact_doc" id="fact_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                        <div class="mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input doc-checkbox" type="checkbox" name="fire_chk" id="fire_chk" data-target="fire_doc">
                                                <label class="form-check-label" for="fire_chk">Fire License (NOC)</label>
												<div id="fire_doc_link"></div>
                                            </div>
                                            <input type="file" name="fire_doc" id="fire_doc" class="form-control form-control-sm mt-1 doc-upload" style="display: none;">
                                        </div>
                                    </div>
									
                                </div>

                                <div class="alert alert-info border-0 mt-3 mb-0" style="background-color: #e3f2fd; border-radius: 6px;">
                                    <div class="d-flex align-items-start">
                                        <i class="ph-duotone ph-info text-info fs-5 me-2 mt-1"></i>
                                        <p class="mb-0 small text-dark">
                                            <strong>Note:</strong> If upload option is available in Company Profile, then we have one
                                            attachment option here as a single PDF of remaining documents (with auto compress logic).
                                        </p>
                                    </div>
                                </div>
                                </div>
                            </div>

                            {{-- STEP 1 SUBMIT BUTTON --}}
                            <div class="mt-4 text-center">
                                <button type="submit" 
                                        class="btn btn-lg px-5 py-3 mb-5 fw-bold shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px;">
                                    <i class="ph-duotone ph-arrow-right me-2"></i>
                                    Submit Company Details &amp; Continue
                                </button>
                            </div>
							<div class="mt-4 alert border-0 shadow-sm mb-0" style="background: linear-gradient(135deg, #ff7675 0%, #d63031 100%); color: white; border-radius: 8px;">
                                <strong>Note :</strong> Same information (filled &amp; non-filled) will be shown in the
                                admin section. Based on this, our CA / Accountant / Compliance Officer will provide the
                                Final Review &amp; Recommendations as per the Company Health Check-up Summary, with name
                                and designation.
                            </div>
						</form>
                        </div> {{-- /step1Section --}}
						
						@endif
						
						
                        {{-- STEP 2: SUMMARY + FINAL REVIEW (IMAGE 3 & 4) --}}
						@if(Auth::user()->u_type == 3 || Auth::user()->u_type == 6)
                        <div id="step2Section" class="mt-4">
							<form name="companyHealthForm2" id="companyHealthForm2" action="javascript:void(0);" method="post" enctype="multipart/form-data">
                            @csrf
							<input type="hidden" name="userId" value="{{ decrypt(request('uid')) }}">
							<div class="mb-4 p-3 rounded" style="background: linear-gradient(135deg, #55efc4 0%, #00b894 100%); border-left: 4px solid #00b894;">
                                <h5 class="fw-bold mb-1 text-white d-flex align-items-center">
                                    <i class="ph-duotone ph-clipboard-text me-2 fs-4"></i>
                                    Company Health Check-up Summary
                                </h5>
                            </div>

                            <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                                <div class="card-body p-3">
                                <label class="form-label fw-bold text-dark d-flex align-items-center">
                                    <span class="badge bg-primary me-2">1</span>
                                    <i class="ph-duotone ph-currency-circle-dollar me-2 text-success"></i>
                                    Financial Review (Reply box)
                                </label>
                                <textarea rows="3" name="financial_review" id="financial_review" class="form-control" placeholder="Enter financial review details..." style="border-radius: 6px;"></textarea>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                                <div class="card-body p-3">
                                <label class="form-label fw-bold text-dark d-flex align-items-center">
                                    <span class="badge bg-info me-2">2</span>
                                    <i class="ph-duotone ph-receipt-x me-2 text-info"></i>
                                    Tax Assessment Review (Reply box)
                                </label>
                                <textarea rows="3" name="tax_review" id="tax_review" class="form-control" placeholder="Enter tax assessment details..." style="border-radius: 6px;"></textarea>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                                <div class="card-body p-3">
                                <label class="form-label fw-bold text-dark d-flex align-items-center">
                                    <span class="badge bg-warning me-2">3</span>
                                    <i class="ph-duotone ph-shield-check me-2 text-warning"></i>
                                    Compliance Review (Reply box)
                                </label>
                                <textarea rows="3" name="compliance_review" id="compliance_review" class="form-control" placeholder="Enter compliance review details..." style="border-radius: 6px;"></textarea>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
                                <div class="card-header bg-white border-bottom" style="border-radius: 8px 8px 0 0;">
                                    <p class="fw-bold mb-0 text-dark d-flex align-items-center">
                                        <i class="ph-duotone ph-list-checks me-2 text-primary"></i>
                                        Other Compliance / Review Points
                                    </p>
                                </div>
                                <div class="card-body p-4">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small">Appointment of Statutory Auditors</label>
                                        <input type="text"  name="statutory_auditor" id="statutory_auditor" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Business Commencement Status</label>
                                        <input type="text"  name="bus_status" id="bus_status" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label small">Director KYC &amp; DIN Compliance</label>
                                        <input type="text"  name="kyc_din" id="kyc_din" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Return of Deposits / Loans (DPT-3)</label>
                                        <input type="text"  name="return_loan" id="return_loan" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Annual General Meeting (AGM)</label>
                                        <input type="text"  name="agm_status" id="agm_status" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">ROC Annual Return Filing Status</label>
                                        <input type="text"  name="roc_status" id="roc_status" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Statutory Audit Status &amp; Reports</label>
                                        <input type="text"  name="audit_report" id="audit_report" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">GST Compliance Status</label>
                                        <input type="text"  name="gst_status" id="gst_status" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Income Tax Status</label>
                                        <input type="text"  name="income_tax_status" id="income_tax_status" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">TDS Compliance Status</label>
                                        <input type="text"  name="tds_status" id="tds_status" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">PF &amp; ESI Compliance Status</label>
                                        <input type="text"  name="pf_esi_status" id="pf_esi_status" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Trade License Renewal Status</label>
                                        <input type="text"  name="trade_status" id="trade_status" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">P-Tax Compliance Status</label>
                                        <input type="text"  name="ptax_status" id="ptax_status" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Labour Law Compliances</label>
                                        <input type="text"  name="labour_law" id="labour_law" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">MSME Compliance Status</label>
                                        <input type="text"  name="msme_status" id="labour_law" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">DPDP Act Applicability</label>
                                        <input type="text"  name="dpdp_act" id="dpdp_act" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Financial Statements filing in XBRL</label>
                                        <input type="text"  name="xbrl_filing" id="xbrl_filing" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Intellectual Property Registration</label>
                                        <input type="text"  name="intellectual_property" id="intellectual_property" class="form-control form-control-sm">
                                    </div>
                                </div>
                                </div>
                            </div>

                            {{-- RISK & PRIORITY --}}
                            <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                                <div class="card-body p-4">
                                <p class="fw-bold mb-2 text-dark d-flex align-items-center">
                                    <i class="ph-duotone ph-warning-diamond me-2 text-danger"></i>
                                    Risk Assessment
                                </p>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="low" name="risk_level" id="risk_low">
                                        <label class="form-check-label fw-semibold" for="risk_low">
                                            <span class="badge bg-success">Low</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="medium" name="risk_level" id="risk_medium">
                                        <label class="form-check-label fw-semibold" for="risk_medium">
                                            <span class="badge bg-warning">Medium</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="high" name="risk_level" id="risk_high">
                                        <label class="form-check-label fw-semibold" for="risk_high">
                                            <span class="badge bg-danger">High</span>
                                        </label>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                                <div class="card-body p-4">
                                <p class="fw-bold mb-3 text-dark d-flex align-items-center">
                                    <i class="ph-duotone ph-list-numbers me-2 text-primary"></i>
                                    Priority for Actions
                                </p>
                                <input type="text" name="priority_one" id="priority_one" class="form-control mb-2"
                                       placeholder="1. Enter first priority action" style="border-radius: 6px;">
                                <input type="text" name="priority_two" id="priority_two" class="form-control mb-2"
                                       placeholder="2. Enter second priority action" style="border-radius: 6px;">
                                <input type="text" name="priority_three" id="priority_three" class="form-control mb-2"
                                       placeholder="3. Enter third priority action" style="border-radius: 6px;">
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
                                <div class="card-body p-4">
                                <p class="fw-bold mb-2 text-dark d-flex align-items-center">
                                    <i class="ph-duotone ph-lightbulb me-2 text-warning"></i>
                                    Specialist Review &amp; Recommendations
                                </p>
                                <textarea rows="3" name="specialist_review" id="specialist_review" class="form-control" placeholder="Enter specialist recommendations..." style="border-radius: 6px;"></textarea>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
                                <div class="card-body p-4">
                                <p class="fw-bold mb-3 text-dark d-flex align-items-center">
                                    <i class="ph-duotone ph-user-circle me-2 text-info"></i>
                                    Prepared by (CA / Accountant / Compliance Officer)
                                </p>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text"  name="officer_name" id="officer_name" class="form-control" placeholder="Enter full name" style="border-radius: 6px;">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text"  name="designation" id="designation" class="form-control" placeholder="Enter designation" style="border-radius: 6px;">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="date"  name="app_date" id="app_date" class="form-control" placeholder="Select date" style="border-radius: 6px;">
                                    </div>
                                </div>
                                </div>
                            </div>
							
							<div class="card border-0 shadow-sm mb-4" style="border-radius: 8px;">
								<div class="card-body p-4">
									<p class="fw-bold mb-3 text-dark d-flex align-items-center">
										<i class="ph-duotone ph-check-circle me-2 text-success"></i>
										Admin Approval & Certificate
									</p>

									<div class="row g-3">
										<div class="col-md-4">
											<label class="form-label fw-semibold">Status</label>
											<select name="admin_status" class="form-select" required style="border-radius: 6px;">
												<option value="0">Pending</option>
												<option value="1">Approve</option>												
											</select>
										</div>

										<div class="col-md-4">
											<label class="form-label fw-semibold">Remark</label>
											<textarea name="admin_remark" 
													  class="form-control" 
													  placeholder="Enter remark"
													  style="border-radius: 6px; min-height: 38px;"></textarea>
										</div>

										<div class="col-md-4">
											<label class="form-label fw-semibold">Upload Certificate</label>
											<input type="file"
												   name="admin_certificate"
												   class="form-control"
												   accept=".pdf,.jpg,.jpeg,.png"
												   style="border-radius: 6px;">
											<small class="text-muted">
												PDF / JPG / PNG (max 2MB)
											</small>
										</div>
									</div>
								</div>
							</div>


                            {{-- FINAL SUBMIT BUTTON --}}
                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-lg w-100 py-3 fw-bold shadow" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); color: white; border: none; border-radius: 8px;">
                                    <i class="ph-duotone ph-check-circle me-2 fs-5"></i>
                                    Generate the Final Review &amp; Recommendations
                                </button>
                            </div>
                            
							</form>
                        </div> {{-- /step2Section --}}
						@endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- POPUP MODAL SHOWN ON PAGE LOAD --}}
@if (Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
<div class="modal fade" id="healthInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient text-white py-3 border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex align-items-center">
                    <i class="ph-duotone ph-warning-circle fs-2 me-3"></i>
                    <h5 class="modal-title fw-bold mb-0">Company Health Check-up – Important Information</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning border-0 mb-3" style="background-color: #fff3cd;">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-info fs-4 text-warning me-2 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-2">Why This Information Matters</h6>
                            <p class="mb-0 small">
                                The information below is crucial for accurate assessment. Without comprehensive details, 
                                the company's health check-up cannot be completed accurately. Incomplete data may cause 
                                unreliable results, so please provide all available information.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="ph-duotone ph-check-circle text-success fs-5 me-2 mt-1"></i>
                            <div>
                                <h6 class="fw-semibold mb-1 small">Complete Information</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Fill all relevant fields for comprehensive analysis</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="ph-duotone ph-shield-check text-primary fs-5 me-2 mt-1"></i>
                            <div>
                                <h6 class="fw-semibold mb-1 small">Data Security</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Your information is securely stored and protected</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="ph-duotone ph-file-text text-info fs-5 me-2 mt-1"></i>
                            <div>
                                <h6 class="fw-semibold mb-1 small">Document Upload</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Upload supporting documents for verification</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <i class="ph-duotone ph-chart-line text-warning fs-5 me-2 mt-1"></i>
                            <div>
                                <h6 class="fw-semibold mb-1 small">Detailed Report</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Get comprehensive health check-up summary</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light py-3">
                <button type="button" class="btn btn-primary px-4 fw-semibold" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                    <i class="ph-duotone ph-check me-1"></i> I Understand, Let's Proceed
                </button>
            </div>
        </div>
    </div>
</div>
@endif
{{-- SCRIPT: SHOW POPUP ON LOAD + STEP SWITCH + RADIO TOGGLES + DOCUMENT UPLOAD TOGGLES --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Show popup modal when page opens
        if (window.bootstrap) {
            var healthModal = new bootstrap.Modal(document.getElementById('healthInfoModal'));
            healthModal.show();
        } else if (window.$) {
            $('#healthInfoModal').modal('show');
        }

        // Switch from Step 1 to Step 2 when first submit is clicked
        /*var step1Btn = document.getElementById('step1Submit');
        if (step1Btn) {
            step1Btn.addEventListener('click', function () {
                document.getElementById('step1Section').classList.add('d-none');
                document.getElementById('step2Section').classList.remove('d-none');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }*/

        // Handle radio button toggles (Yes = show input, No = hide input)
        /*const radioToggles = document.querySelectorAll('.radio-toggle');
        radioToggles.forEach(function(radio) {
            radio.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                
                if (targetInput) {
                    if (this.value === 'yes' && this.checked) {
                        // Show input when Yes is selected
                        targetInput.style.display = 'block';
                    } else if (this.value === 'no' && this.checked) {
                        // Hide input when No is selected
                        targetInput.style.display = 'none';
                        targetInput.value = ''; // Clear the input value
                    }
                }
            });
        });*/
		$(document).on('change', '.radio-toggle', function () {

			let target = $(this).data('target');
			let value  = $(this).val();

			if (value === 'yes') {
				$('#' + target)
					.show()
					.prop('required', true);
			} else {
				$('#' + target)
					.hide()
					.val('')
					.prop('required', false);
			}
		});

        // Handle document upload checkboxes (checked = show upload input)
        const docCheckboxes = document.querySelectorAll('.doc-checkbox');
        docCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const targetId = this.getAttribute('data-target');
                const uploadInput = document.getElementById(targetId);
                
                if (uploadInput) {
                    if (this.checked) {
                        // Show upload input when checkbox is checked
                        uploadInput.style.display = 'block';
                    } else {
                        // Hide upload input when checkbox is unchecked
                        uploadInput.style.display = 'none';
                        uploadInput.value = ''; // Clear the file selection
                    }
                }
            });
        });
    });

	$(document).ready(function () {
		
		function getQueryParam(param) {
			const urlParams = new URLSearchParams(window.location.search);
			return urlParams.get(param);
		}

		const userId = getQueryParam('uid');

		// FETCH DATA
		$.ajax({
			url: "{{ url('/company-profile-check/fetch') }}",
			type: "GET",
			dataType: "json",
			data: { uid: userId },
			success: function (res) {

				if (!res || typeof res !== 'object') return;

				$.each(res, function (key, value) {

					// TEXT / SELECT / TEXTAREA
					let el = $('[name="' + key + '"]');

					if (el.length && !['checkbox','radio','file'].includes(el.attr('type'))) {
						if (value !== null && value !== undefined && value !== '') {
							el.val(String(value));
						} else {
							el.val(''); // clear field
						}
					}

					// CHECKBOX (_chk)
					if (el.length && el.attr('type') === 'checkbox') {
						el.prop('checked', value == 1);
					}

					// RADIO (yes / no)
					// RADIO (yes / no)
					let radios = $('input[type="radio"][name="' + key + '"]');

					if (radios.length) {

						if (value) {
							radios.filter('[value="' + value + '"]')
								.prop('checked', true)
								.trigger('change');
						} else {
							radios.filter('[value="no"]')
								.prop('checked', true)
								.trigger('change');
						}
					}

					// FILE PREVIEW (_doc)
					if (key.endsWith('_doc') && value) {
						$('#' + key + '_link').html(
							`<a href="/storage/${value}" target="_blank">View Document</a>`
						);
					}

				});
			},
			error: function (xhr) {
				console.error(xhr.responseText);
			}
		});

		
		// SAVE DATA
		$('#companyHealthForm').on('submit', function (e) {

			e.preventDefault();
			let form = this;

			Swal.fire({
				title: 'Confirm Submission',
				html: `
					<div style="font-size:15px; line-height:1.7;">
						Review all details and documents before submission.
						<br>
						The company health check will be based on your submission.
						<br>
						<b>The next health check is allowed only after 3 months.</b>
					</div>
				`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, Submit',
				cancelButtonText: 'Cancel',
				confirmButtonColor: '#0d6efd',
				cancelButtonColor: '#6c757d',
				reverseButtons: true
			}).then((result) => {

				if (result.isConfirmed) {
					let formData = new FormData(form);
					$("#loader").show();
					$.ajax({
						url: "{{ url('/company-profile-check/save') }}",
						type: "POST",
						data: formData,
						contentType: false,
						processData: false,
						success: function (res) {
							$("#loader").hide();
							showToast(res.message, "success");
							setTimeout(() => {
								window.location.href = "{{ route('admin.company.checks') }}";
							}, 2000);

						},
						error: function (xhr) {
							$("#loader").hide();
							if (xhr.responseJSON) {
								showToast(xhr.responseJSON.message, "error");
							} else {
								showToast("Something went wrong", "error");
							}
						}
					});
				}
			});
		});
		
		$('#companyHealthForm2').on('submit', function (e) {
			e.preventDefault();
			let formData = new FormData(this);
			$("#loader").show();

			$.ajax({
				url: "{{ url('/company-profile-check/saveByAdmin') }}",
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				success: function (res) {
					$("#loader").hide();
					showToast(res.message, "success");
					setTimeout(() => {
						window.location.href = "{{ route('admin.company.checks') }}";
					}, 2000);
				},
				error: function (xhr) {
					$("#loader").hide();
					if (xhr.responseJSON) {
						showToast(xhr.responseJSON.message, "error");
					} else {
						showToast("Something went wrong", "error");
					}
				}
			});
		});


	});	
	
</script>

@endsection
