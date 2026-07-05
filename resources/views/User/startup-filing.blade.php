@extends('App.Layout')

@section('container')

<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/startup-filing/list') }}">Startup Filing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Apply</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-startup-filing-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Startup Filing</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

<style>
    .service-select-card {
        display: block;
        cursor: pointer;
        position: relative;
        margin-bottom: 0;
    }
    .service-select-card .srv-checkbox {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .service-select-card .card-inner {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }
    .service-select-card:hover .card-inner {
        border-color: #4f46e5;
        background: #f8fafc;
    }
    .service-select-card .srv-checkbox:checked + .card-inner {
        background: rgba(79, 70, 229, 0.08);
        border-color: #4f46e5;
        box-shadow: 0 0 0 1px #4f46e5;
    }
    .service-select-card .check-indicator {
        color: #cbd5e1;
        font-size: 1.15rem;
        transition: all 0.2s;
    }
    .service-select-card .srv-checkbox:checked + .card-inner .check-indicator {
        color: #4f46e5;
    }
    .service-select-card .service-label {
        font-size: 0.88rem;
        font-weight: 500;
        color: #334155;
    }
    .service-select-card .srv-checkbox:checked + .card-inner .service-label {
        color: #1e293b;
        font-weight: 600;
    }
    .section-title-premium {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        position: relative;
        padding-left: 12px;
    }
    .section-title-premium::before {
        content: '';
        position: absolute;
        left: 0;
        top: 3px;
        bottom: 3px;
        width: 4px;
        background: #4f46e5;
        border-radius: 2px;
    }
    .sticky-sidebar {
        position: sticky;
        top: 20px;
    }
    .bg-light-blue {
        background: #f0f4ff;
    }
</style>

    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <span class="badge bg-light-primary text-primary px-3 py-2 mb-2 text-uppercase fw-bold letter-spacing-1">Incubator Program</span>
            <h2 class="fw-extrabold text-dark mb-0">STARTUP INCUBATOR SERVICE ENGAGEMENT</h2>
            <p class="text-muted mt-2">Accelerate your business with professional setup, compliance tracking, and funding support.</p>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="row mt-3">
        <div class="col-xl-8 col-lg-7 col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 p-md-5">
                    <form id="startupFilingForm" action="javascript:void(0);">

                        <!-- A. APPLICANT DETAILS -->
                        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                            <span class="avtar avtar-s btn-light-primary rounded-circle"><i class="ti ti-user f-18"></i></span>
                            <h5 class="text-dark fw-bold mb-0">Applicant Details</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Startup / Business Name <span class="text-danger">*</span></label>
                                <input type="text" name="business_name" class="form-control" placeholder="Enter business name">
                                <span class="text-danger error-text business_name_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Founder’s Name <span class="text-danger">*</span></label>
                                <input type="text" name="founder_name" class="form-control" placeholder="Enter founder's name">
                                <span class="text-danger error-text founder_name_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control" placeholder="Enter mobile number">
                                <span class="text-danger error-text mobile_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email ID <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email address">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Business Address</label>
                                <input type="text" name="business_address" class="form-control" placeholder="Enter full business address">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Industry Type</label>
                                <input type="text" name="industry_type" class="form-control" placeholder="e.g. FinTech, SaaS, Healthcare">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Business Stage</label>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="idea_stage" value="1" class="form-check-input" id="stage_idea">
                                        <label class="form-check-label" for="stage_idea">Idea Stage</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="prototype" value="1" class="form-check-input" id="stage_proto">
                                        <label class="form-check-label" for="stage_proto">Prototype / MVP</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="early_revenue[]" value="1" class="form-check-input" id="stage_rev">
                                        <label class="form-check-label" for="stage_rev">Early Revenue</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="growth_stage[]" value="1" class="form-check-input" id="stage_growth">
                                        <label class="form-check-label" for="stage_growth">Growth Stage</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- B. CONTACT PERSON DETAILS -->
                        <div class="d-flex align-items-center gap-2 mt-4 mb-4 pb-2 border-bottom">
                            <span class="avtar avtar-s btn-light-info rounded-circle"><i class="ti ti-address-book f-18"></i></span>
                            <h5 class="text-dark fw-bold mb-0">Contact Person Details</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Primary Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" placeholder="Enter contact person's name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Designation</label>
                                <input type="text" name="designation" class="form-control" placeholder="e.g. CEO, Director, Manager">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Mobile Number</label>
                                <input type="text" name="contact_mobile" class="form-control" placeholder="Enter contact mobile number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email ID</label>
                                <input type="email" name="contact_email" class="form-control" placeholder="Enter contact email address">
                            </div>
                        </div>

                        <!-- C. REQUIRED INCUBATOR SERVICES -->
                        <div class="d-flex align-items-center gap-2 mt-4 mb-4 pb-2 border-bottom">
                            <span class="avtar avtar-s btn-light-success rounded-circle"><i class="ti ti-rocket f-18"></i></span>
                            <h5 class="text-dark fw-bold mb-0">Required Incubator Services</h5>
                        </div>

                        <!-- 1. Formation & Registration -->
                        <div class="mb-4">
                            <h6 class="section-title-premium mb-3">Business Formation & Registration</h6>
                            <div class="row g-2">
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_comp">
                                        <input type="checkbox" name="company_registration" value="1" class="srv-checkbox" id="srv_comp">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Company Registration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_gst">
                                        <input type="checkbox" name="gst_registration" value="1" class="srv-checkbox" id="srv_gst">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">GST Registration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_msme">
                                        <input type="checkbox" name="msme" value="1" class="srv-checkbox" id="srv_msme">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">MSME / UDYAM</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_pantan">
                                        <input type="checkbox" name="pan_tan" value="1" class="srv-checkbox" id="srv_pantan">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">PAN / TAN Application</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_trade">
                                        <input type="checkbox" name="trade_license" value="1" class="srv-checkbox" id="srv_trade">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Trade License / Shop Act</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_tm">
                                        <input type="checkbox" name="trademark" value="1" class="srv-checkbox" id="srv_tm">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Trademark Registration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_dsc">
                                        <input type="checkbox" name="dsc" value="1" class="srv-checkbox" id="srv_dsc">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">DSC (Digital Signature Certificate)</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_epf">
                                        <input type="checkbox" name="epf_esic" value="1" class="srv-checkbox" id="srv_epf">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">EPF & ESIC Registration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_startup">
                                        <input type="checkbox" name="startup_registration" value="1" class="srv-checkbox" id="srv_startup">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">DPIIT Startup Registration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_ptax">
                                        <input type="checkbox" name="professional_tax" value="1" class="srv-checkbox" id="srv_ptax">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Professional Tax Enrollment</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Compliance & Financial Setup -->
                        <div class="mb-4">
                            <h6 class="section-title-premium mb-3">Compliance & Financial Setup</h6>
                            <div class="row g-2">
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_acct">
                                        <input type="checkbox" name="accounting_setup" value="1" class="srv-checkbox" id="srv_acct">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Accounting Software Setup</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_coa">
                                        <input type="checkbox" name="chart_accounts" value="1" class="srv-checkbox" id="srv_coa">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Chart of Accounts Configuration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_tax">
                                        <input type="checkbox" name="tax_guidance" value="1" class="srv-checkbox" id="srv_tax">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">GST / TDS / Tax Compliance Guidance</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_roc">
                                        <input type="checkbox" name="roc_setup" value="1" class="srv-checkbox" id="srv_roc">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">ROC / MCA Filing Setup</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_pay">
                                        <input type="checkbox" name="payroll" value="1" class="srv-checkbox" id="srv_pay">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Payroll Setup & Management</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Tech & Strategy -->
                        <div class="mb-4">
                            <h6 class="section-title-premium mb-3">Technology & Strategy Consulting</h6>
                            <div class="row g-2">
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_web">
                                        <input type="checkbox" name="website" value="1" class="srv-checkbox" id="srv_web">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Website / E-commerce Development</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_crm">
                                        <input type="checkbox" name="crm" value="1" class="srv-checkbox" id="srv_crm">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">CRM & Sales Automation Setup</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_erp">
                                        <input type="checkbox" name="erp" value="1" class="srv-checkbox" id="srv_erp">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">ERP System Integration</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_dm">
                                        <input type="checkbox" name="digital_marketing" value="1" class="srv-checkbox" id="srv_dm">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Branding & Digital Marketing Support</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_bmc">
                                        <input type="checkbox" name="business_model" value="1" class="srv-checkbox" id="srv_bmc">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Business Model Canvas Advisory</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_plan">
                                        <input type="checkbox" name="financial_planning" value="1" class="srv-checkbox" id="srv_plan">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Financial Planning & Unit Economics</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Investor & Mentorship -->
                        <div class="mb-4">
                            <h6 class="section-title-premium mb-3">Investor Support & Mentorship</h6>
                            <div class="row g-2">
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_pitch">
                                        <input type="checkbox" name="pitch_deck" value="1" class="srv-checkbox" id="srv_pitch">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Investor Pitch Deck Creation</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_proj">
                                        <input type="checkbox" name="financial_projection" value="1" class="srv-checkbox" id="srv_proj">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">5-Year Financial Projections</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_val">
                                        <input type="checkbox" name="valuation" value="1" class="srv-checkbox" id="srv_val">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Valuation Advisory Assistance</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_inv">
                                        <input type="checkbox" name="investor_connect" value="1" class="srv-checkbox" id="srv_inv">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Investor Network Connect</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_mentor">
                                        <input type="checkbox" name="mentoring" value="1" class="srv-checkbox" id="srv_mentor">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">1:1 Strategic Mentorship Sessions</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="service-select-card" for="srv_kpi">
                                        <input type="checkbox" name="kpi" value="1" class="srv-checkbox" id="srv_kpi">
                                        <div class="card-inner">
                                            <span class="check-indicator"><i class="ti ti-circle-check"></i></span>
                                            <span class="service-label">Growth KPI Dashboard Setup</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- G. DECLARATION -->
                        <div class="card border-0 mb-4 bg-light-primary">
                            <div class="card-body p-4">
                                <h5 class="text-primary fw-bold mb-2"><i class="ti ti-writing me-2"></i>Declaration</h5>
                                <p class="text-muted mb-3 f-14">I/We hereby declare that the information provided above is true and correct. I/We agree to the Terms & Conditions and Payment Schedule mentioned in this form.</p>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Authorized Signatory</label>
                                        <input type="text" name="authorized_signatory" class="form-control bg-white" placeholder="Enter signatory title">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Signatory Name</label>
                                        <input type="text" name="signatory_name" class="form-control bg-white" placeholder="Enter name">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label fw-semibold">Date</label>
                                        <input type="date" name="signed_date" class="form-control bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase py-3 fs-6 mt-2 shadow-sm">
                            <i class="ti ti-circle-check me-1"></i> Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar Info panels -->
        <div class="col-xl-4 col-lg-5 col-12">
            <div class="sticky-sidebar">
                <!-- Payment Schedule Panel -->
                <div class="card shadow-sm border-0 mb-4 bg-light-blue">
                    <div class="card-body p-4">
                        <h5 class="text-dark fw-bold mb-3"><i class="ti ti-credit-card me-2 text-primary"></i>Payment Terms</h5>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">Onboarding Fee</h6>
                            <p class="text-muted mb-0 f-13">A non-refundable registration fee of <strong>₹ 750/-</strong> is payable immediately.</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">Service Breakdown</h6>
                            <ul class="text-muted ps-3 mb-0 f-13" style="line-height: 1.5;">
                                <li>Business Formation: 100% advance</li>
                                <li>Compliance Setup: 50% advance / 50% compl.</li>
                                <li>Pitch Deck Support: 70% advance / 30% deliv.</li>
                                <li>Tech Solutions: 50% advance / 50% pre-deploy.</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Refund Policy</h6>
                            <p class="text-muted mb-0 f-13">Government portal filing fees and delivered custom services are non-refundable.</p>
                        </div>
                    </div>
                </div>

                <!-- Documents Checklist Panel -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="text-dark fw-bold mb-3"><i class="ti ti-files me-2 text-primary"></i>Required Documents</h5>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                            <li class="d-flex align-items-center gap-2 text-muted f-13">
                                <i class="ti ti-circle-check text-success f-16"></i> PAN & Aadhaar Cards of Founders
                            </li>
                            <li class="d-flex align-items-center gap-2 text-muted f-13">
                                <i class="ti ti-circle-check text-success f-16"></i> Business Utility Bill / Rent Agreement
                            </li>
                            <li class="d-flex align-items-center gap-2 text-muted f-13">
                                <i class="ti ti-circle-check text-success f-16"></i> Existing Business Plan (if prepared)
                            </li>
                            <li class="d-flex align-items-center gap-2 text-muted f-13">
                                <i class="ti ti-circle-check text-success f-16"></i> GST Portal Access Credentials
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Terms & Conditions Panel -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="text-dark fw-bold mb-3"><i class="ti ti-shield-check me-2 text-primary"></i>Terms of Service</h5>
                        <div class="text-muted f-13" style="max-height: 150px; overflow-y: auto; line-height: 1.6;">
                            <p class="mb-2">1. All information submitted must be accurate and verifiable.</p>
                            <p class="mb-2">2. Processing timelines depend directly on government server availability and response periods.</p>
                            <p class="mb-2">3. Any changes in service scope during the project period will result in additional charges.</p>
                            <p class="mb-0">4. Kolkata jurisdiction shall apply to legal matters.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#startupFilingForm').submit(function(e){
    e.preventDefault();
	$("#loader").show();
    $.ajax({
        url: '/startup-filing/store',
        method: 'POST',
        data: $(this).serialize(),
        success: function(res){
			$("#loader").hide();
            if(res.success){
                showToast(res.message, 'success');
                $('#startupFilingForm')[0].reset();
				setTimeout(function(){
					window.location.href = "{{ url('/startup-filing/list') }}";
				}, 2000); // 2 sec 
            }
        },
        error: function(xhr){
			$("#loader").hide();
			if(xhr.status === 422){
				$.each(xhr.responseJSON.errors, function(key, value){
					$('[name="'+key+'"]').after(
						'<span class="text-danger error-text">'+value[0]+'</span>'
					);
				});
			}
		}
    });
});

function startStartupFilingTour() {
    if (typeof introJs !== 'function') return;

    introJs().setOptions({
        steps: [
            {
                title: 'Startup Incubator Guide',
                intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-rocket" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Apply for Startup Incubator Services, register business details, select required service modules, and accept payment terms.</p></div>'
            },
            {
                element: 'input[name="business_name"]',
                title: 'Business Name',
                intro: 'Enter your legal business name or startup name.'
            },
            {
                element: 'input[name="founder_name"]',
                title: 'Founder’s Name',
                intro: 'Enter the name of the main founder or co-founder.'
            },
            {
                element: 'button[type="submit"]',
                title: 'Submit Application',
                intro: 'Click here to submit your incubator registration and review terms.'
            }
        ],
        showBullets: true,
        showProgress: true,
        helperElementPadding: 5,
        exitOnOverlayClick: false,
        doneLabel: 'Done',
        nextLabel: 'Next',
        prevLabel: 'Prev',
        skipLabel: 'Skip'
    }).start();
}

$(document).ready(function() {
    $('#start-startup-filing-tour').on('click', function(e) {
        e.preventDefault();
        startStartupFilingTour();
    });
});
</script>

@endsection
