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

    <!-- Page Title -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <h2 class="fw-bold text-uppercase mb-0">STARTUP INCUBATOR SERVICE ENGAGEMENT</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="row mt-3">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-body px-4 py-4">

                    <form id="startupFilingForm" action="javascript:void(0);">

                        <!-- A. APPLICANT DETAILS -->
                        <h5 class="text-primary fw-bold mb-3">
                            Applicant Details
                            <span class="fw-normal text-dark"></span>
                        </h5>

                        <ol class="ps-3">
                            <li class="mb-3">
                                Startup / Business Name:
                                <input type="text" name="business_name" class="form-control form-control-sm mt-1">
								<span class="text-danger error-text business_name_error"></span>
                            </li>
                            <li class="mb-3">
                                Founder’s Name:
                                <input type="text" name="founder_name" class="form-control form-control-sm mt-1">
								<span class="text-danger error-text founder_name_error"></span>
                            </li>
                            <li class="mb-3">
                                Mobile Number:
                                <input type="text" name="mobile" class="form-control form-control-sm mt-1">
								<span class="text-danger error-text mobile_error"></span>
                            </li>
                            <li class="mb-3">
                                Email ID:
                                <input type="email" name="email" class="form-control form-control-sm mt-1">
								<span class="text-danger error-text email_error"></span>
                            </li>
                            <li class="mb-3">
                                Business Address:
                                <input type="text" name="business_address" class="form-control form-control-sm mt-1">
                            </li>
                            <li class="mb-3">
                                Business Stage:
                                <div class="mt-2 ms-3">
                                    <div class="form-check mb-2"><input type="checkbox" name="idea_stage" value="1" class="form-check-input"> Idea Stage</div>
                                    <div class="form-check mb-2"><input type="checkbox" name="prototype" value="1" class="form-check-input"> Prototype / MVP</div>
                                    <div class="form-check mb-2"><input type="checkbox" name="early_revenue[]" value="1" class="form-check-input"> Early Revenue</div>
                                    <div class="form-check mb-2"><input type="checkbox" name="growth_stage[]" value="1" class="form-check-input"> Growth Stage</div>
                                </div>
                            </li>
                            <li class="mb-3">
                                Industry Type:
                                <input type="text" name="industry_type"  class="form-control form-control-sm mt-1">
                            </li>
                        </ol>

                        <hr class="my-4">

                        <!-- B. CONTACT PERSON DETAILS -->
                        <h5 class="text-primary fw-bold mb-3">
                            Contact Person Details
                            <span class="fw-normal text-dark"></span>
                        </h5>

                        <ol class="ps-3">
                            <li class="mb-3">
                                Primary Contact Person:
                                <input type="text" name="contact_person" class="form-control form-control-sm mt-1">
                            </li>
                            <li class="mb-3">
                                Designation:
                                <input type="text" name="designation" class="form-control form-control-sm mt-1">
                            </li>
                            <li class="mb-3">
                                Mobile Number:
                                <input type="text" name="contact_mobile" class="form-control form-control-sm mt-1">
                            </li>
                            <li class="mb-3">
                                Email ID:
                                <input type="email" name="contact_email" class="form-control form-control-sm mt-1">
                            </li>
                        </ol>

                        <hr class="my-4">

                        <!-- C. REQUIRED INCUBATOR SERVICES -->
                        <h5 class="text-primary fw-bold mb-3">Required Incubator Services</h5>

                        <div class="row">

                            <!-- 1 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Business Formation & Registration</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="company_registration" value="1" class="form-check-input"> Company Registration</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="gst_registration" value="1" class="form-check-input"> GST Registration</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="msme" value="1" class="form-check-input"> MSME/UDYAM</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="pan_tan" value="1" class="form-check-input"> PAN / TAN</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="trade_license" value="1" class="form-check-input"> Trade License / Shop Act</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="trademark" value="1" class="form-check-input"> Trademark Registration</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="dsc" value="1" class="form-check-input"> DSC</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="epf_esic" value="1" class="form-check-input"> EPF & ESIC</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="startup_registration" value="1" class="form-check-input"> Startup Registration</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="professional_tax" value="1" class="form-check-input"> Professional Tax Enrollment</div>
                                </div>
                            </div>

                            <!-- 2 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Compliance & Financial Setup</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="accounting_setup" value="1" class="form-check-input"> Accounting Software Setup</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="chart_accounts" value="1" class="form-check-input"> Chart of Accounts Setup</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="tax_guidance" value="1" class="form-check-input"> GST / TDS / Income Tax Guidance</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="roc_setup" value="1" class="form-check-input"> ROC / MCA Compliance Setup</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="payroll" value="1" class="form-check-input"> Payroll Compliance</div>
                                </div>
                            </div>

                            <!-- 3 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Business Model & Strategy Consulting</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="business_model" value="1" class="form-check-input"> Business Model Canvas</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="swot" value="1" class="form-check-input"> SWOT & Market Research</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="pricing" value="1" class="form-check-input"> Pricing Strategy</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="financial_planning" value="1" class="form-check-input"> Financial Planning & Unit Economics</div>
                                </div>
                            </div>

                            <!-- 4 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Funding & Investor Support</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="pitch_deck" value="1" class="form-check-input"> Pitch Deck Creation</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="financial_projection" value="1" class="form-check-input"> Financial Projections</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="valuation" value="1" class="form-check-input"> Valuation Assistance</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="investor_connect" value="1" class="form-check-input"> Investor Connect</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="govt_scheme" value="1" class="form-check-input"> Govt. Scheme Support</div>
                                </div>
                            </div>

                            <!-- 5 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Mentorship & Skill Development</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="mentoring" value="1" class="form-check-input"> 1:1 Mentoring</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="workshop" value="1" class="form-check-input"> Workshops / Masterclasses</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="legal_mentoring" value="1" class="form-check-input"> Legal / Compliance Mentoring</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="marketing_mentoring" value="1" class="form-check-input"> Marketing & Sales Mentoring</div>
                                </div>
                            </div>

                            <!-- 6 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Technology & Digital Enablement</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="website" value="1" class="form-check-input"> Website / E-commerce Development</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="crm" value="1" class="form-check-input"> CRM & Sales Pipeline Setup</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="erp" value="1" class="form-check-input"> ERP Access</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="digital_marketing" value="1" class="form-check-input"> Digital Marketing Support</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="automation" value="1" class="form-check-input"> Automation Tools Setup</div>
                                </div>
                            </div>

                            <!-- 7 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Go-To-Market & Branding Support</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="brand_identity" value="1" class="form-check-input"> Brand Identity (Logo / Colours / Deck)</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="social_media" value="1" class="form-check-input"> Social Media Setup</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="product_plan" value="1" class="form-check-input"> Product Launch Plan</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="marketing_template" value="1" class="form-check-input"> Marketing Templates</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="dealer" value="1" class="form-check-input"> Dealer / Distributor Support</div>
                                </div>
                            </div>

                            <!-- 8 -->
                            <div class="col-md-6 col-lg-3 mb-4">
                                <p class="fw-semibold mb-2">Growth Monitoring & Health Check</p>
                                <div class="ms-3">
                                    <div class="form-check mb-1"><input type="checkbox" name="monthly_report" value="1" class="form-check-input"> Monthly Business Health Report</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="kpi" value="1" class="form-check-input"> Growth KPI Dashboard</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="cashflow" value="1" class="form-check-input"> Cashflow Advisory</div>
                                    <div class="form-check mb-1"><input type="checkbox" name="scaling_support" value="1" class="form-check-input"> Scaling Strategy Support</div>
                                </div>
                            </div>

                        </div>


                        <hr class="my-4">

                        <!-- D. PAYMENT TERMS -->
                        <h5 class="text-primary fw-bold mb-3">Payment Schedule Terms</h5>

                        <p class="mb-2 fw-semibold">1. Engagement Fee</p>
                        <p>A non-refundable onboarding fee: ₹ 750/- payable at registration.</p>

                        <p class="mb-2 fw-semibold">2. Service-wise Payment Terms</p>
                        <ul class="mb-3">
                            <li>Business Registration Services: 100% advance</li>
                            <li>Compliance & Accounting Setup: 50% advance + 50% on completion</li>
                            <li>Pitch Deck / Consulting: 70% advance + 30% on delivery</li>
                            <li>Technology Solutions: 50% advance + 50% before deployment</li>
                            <li>Branding & GTM Support: 60% advance + 40% on completion</li>
                            <li>Mentorship Program: Monthly/Quarterly subscription</li>
                            <li>Investor Connect: Success fee if applicable</li>
                        </ul>

                        <p class="mb-2 fw-semibold">3. Refund Policy</p>
                        <ul class="mb-4">
                            <li>Government fees are non-refundable.</li>
                            <li>Professional services are non-refundable once delivered.</li>
                            <li>Delays caused by the client are not refundable.</li>
                        </ul>

                        <hr class="my-4">

                        <!-- E. TERMS & CONDITIONS -->
                        <h5 class="text-primary fw-bold mb-3">Basic Terms & Conditions</h5>

                        <ol class="mb-4 ps-3">
                            <li>The applicant agrees to provide correct information & documents.</li>
                            <li>Timelines depend on government processing.</li>
                            <li>The incubator will not be responsible for delays due to portals or incomplete docs.</li>
                            <li>Confidentiality will be maintained.</li>
                            <li>Payments must follow schedule.</li>
                            <li>Scope change requires revised pricing & approval.</li>
                            <li>Investor connection does not guarantee funding.</li>
                            <li>Technology services follow licensing terms.</li>
                            <li>Rights reserved to discontinue service for non-compliance.</li>
                            <li>Jurisdiction: Kolkata courts.</li>
                        </ol>

                        <hr class="my-4">

                        <!-- F. DOCUMENTS REQUIRED -->
                        <h5 class="text-primary fw-bold mb-3">Documents Required (As Applicable)</h5>

                        <ul class="mb-4">
                            <li>Aadhaar / PAN of founder</li>
                            <li>Address proof</li>
                            <li>Business plan (if available)</li>
                            <li>Previous year financial data (if any)</li>
                            <li>GST login credentials</li>
                            <li>Digital documents required for company registration</li>
                        </ul>

                        <hr class="my-4">

                        <!-- G. DECLARATION -->
                        <h5 class="text-primary fw-bold mb-2">Declaration</h5>

                        <p>
                            I/We hereby declare that the information provided above is true and correct.<br>
                            I/We agree to the Terms & Conditions and Payment Schedule mentioned in this form.
                        </p>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                Authorized Signatory:
                                <input type="text" name="authorized_signatory" class="form-control form-control-sm mt-1">
                            </div>
                            <div class="col-md-6 mb-3">
                                Name:
                                <input type="text" name="signatory_name" class="form-control form-control-sm mt-1">
                            </div>
                            <div class="col-md-6 mb-3">
                                Date:
                                <input type="date" name="signed_date"  class="form-control form-control-sm mt-1">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase py-2">
                            Submit
                        </button>
                    </form>

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
