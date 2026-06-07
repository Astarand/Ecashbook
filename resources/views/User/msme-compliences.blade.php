@extends('App.Layout')

@section('container')

<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/msme/list') }}">MSME Compliance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Apply</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">MSME Compliances & Benefits</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h2 class="mb-0 fw-bold text-uppercase">MSME Compliances & Benefits</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body px-4 py-4">

                    {{-- MSME COMPLIANCES --}}
                    <h4 class="fw-bold mb-3">MSME Compliances</h4>

                    <p class="mb-2">
                        If your startup is registered under MSME, then the following compliances become mandatory:
                    </p>
                    <ol class="mb-4">
                        <li>45-Day Payment Rule (under MSME Act Sec. 15 &amp; 16)</li>
                        <li>Interest on delayed payments (3× RBI rate)</li>
                        <li>IT Act Sec. 43B(h) disallowance of expenses not paid in 45 days</li>
                        <li>MSME Form I (for companies with overdue payments)</li>
                        <li>Vendor MSME status verification</li>
                        <li>Displaying MSME registration details in records</li>
                    </ol>

                    {{-- BENEFITS --}}
                    <h4 class="fw-bold mb-3">Benefits of MSME Registration (Udyam)</h4>

                    <div class="mb-4">
                        <h6 class="fw-semibold">1) Collateral-Free Loans from Banks (CGTMSE &amp; Mudra Loans)</h6>
                        <ul class="mb-2">
                            <li>MSMEs are eligible for collateral-free credit under CGTMSE Scheme as per the Credit Guarantee Fund Trust for Micro &amp; Small Enterprises notification.</li>
                            <li>Mudra loans (Shishu / Kishor / Tarun) under PMMY provide collateral-free loans up to ₹10 lakh for eligible micro enterprises.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">2) Concessionary / Lower Interest Rates to MSMEs</h6>
                        <ul class="mb-2">
                            <li>As per RBI Master Directions on MSME Lending, banks may provide concessionary interest rates to MSME borrowers under their internal credit policies.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">3) Protection Against Delayed Payments – 45-Day Rule</h6>
                        <ul class="mb-2">
                            <li>Under MSME Act, 2006 – Section 15, buyers must make payment to MSMEs within the agreed period (maximum 45 days).</li>
                            <li>Under Section 16, delayed payments attract compound interest at three times the RBI Bank Rate.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">4) MSME Samadhaan – Delayed Payment Monitoring</h6>
                        <ul class="mb-2">
                            <li>MSMEs can file applications for delayed payments under Section 18 of the MSME Act on the MSME Samadhaan Portal for resolution before the MSE Facilitation Council.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">5) Income Tax – Section 43B(h) Treatment of MSME Payments</h6>
                        <ul class="mb-2">
                            <li>As per Finance Act 2023, expenses payable to MSMEs are allowed as deduction only if paid within 15/45 days as per MSME Act; otherwise they are disallowed in income tax computation.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">6) Higher Preference in Government Tenders</h6>
                        <ul class="mb-2">
                            <li>As per Public Procurement Policy for Micro &amp; Small Enterprises Order, 2012 (as amended):</li>
                            <li>Minimum 25% of total annual procurement by Central Ministries/PSUs must be from MSEs.</li>
                            <li>EMD exemption is available to Micro &amp; Small Enterprises.</li>
                            <li>Tender fee exemption is allowed for MSEs.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">7) Subsidies &amp; Incentives Available to MSMEs</h6>
                        <ul class="mb-2">
                            <li>Capital subsidy under CLCSS (Credit Linked Capital Subsidy Scheme).</li>
                            <li>ZED Certification subsidy under the ZED Scheme.</li>
                            <li>Reimbursement of ISO certification costs by the Ministry of MSME.</li>
                            <li>IPR / Patent / Trademark subsidy under MSME Innovative Scheme.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">8) Electricity Bill Concessions (State-Specific)</h6>
                        <ul class="mb-2">
                            <li>Several state industrial policies provide electricity tariff concessions or subsidies specifically for registered MSMEs.</li>
                            <li>These concessions vary by state and are issued under respective State Industrial Promotion Policies.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">9) Overdraft Facility with Interest Concession</h6>
                        <ul class="mb-2">
                            <li>Some banks offer interest rate concessions on overdraft facilities for MSMEs as per their internal lending policies.</li>
                            <li>Certain state-specific MSME policies provide interest subsidy on working capital loans / OD.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">10) Eligibility for Central &amp; State Government Schemes</h6>
                        <ul class="mb-2">
                            <li>PMEGP (Prime Minister’s Employment Generation Programme)</li>
                            <li>SFURTI (Cluster Development)</li>
                            <li>TReDS (Trade Receivable Discounting System)</li>
                            <li>NSIC schemes</li>
                            <li>State industrial promotion subsidies</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">11) Marketing &amp; Export Promotion Support</h6>
                        <ul class="mb-2">
                            <li>International Cooperation (IC) Scheme offers financial assistance to MSMEs for participation in exhibitions/trade fairs.</li>
                            <li>Procurement &amp; Marketing Support (PMS) Scheme assists MSMEs in marketing, branding and awareness activities.</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-semibold">12) Enterprise Rating &amp; Credit Facilitation</h6>
                        <ul class="mb-0">
                            <li>MSME Ministry and SIDBI facilitate credit rating support under official credit rating schemes to improve MSME access to credit.</li>
                            <li>Banks consider MSME registration as part of PSL (Priority Sector Lending) eligibility, improving credit access.</li>
                        </ul>
                    </div>

                    <!-- APPLY NOW BUTTON (SHOW FORM) -->
                    <div class="mt-4 text-center">
                        <button type="button" id="showApplyFormBtn"
                                class="btn btn-primary px-5 fw-semibold text-uppercase">
                            Apply for MSME Compliance &amp; Benefits Support
                        </button>
                    </div>

                    <!-- APPLY FORM SECTION (HIDDEN INITIALLY) -->
                    <div id="applyFormSection" class="mt-5 d-none">
                        <hr class="mb-4">
                        <h4 class="fw-bold mb-3">Apply for MSME Certification</h4>

                        <form action="javascript:void(0);" method="post" id="msmeApplyForm">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name</label>
                                    <input type="text" name="applicant_name"
                                           class="form-control form-control-sm"
                                           placeholder="Enter your name">
									<span class="text-danger error-text applicant_name_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name"
                                           class="form-control form-control-sm"
                                           placeholder="Enter company name">
									<span class="text-danger error-text company_name_error"></span>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" name="mobile"
                                           class="form-control form-control-sm"
                                           placeholder="Enter mobile number">
									<span class="text-danger error-text mobile_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email ID</label>
                                    <input type="email" name="email"
                                           class="form-control form-control-sm"
                                           placeholder="Enter email address">
									<span class="text-danger error-text email_error"></span>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">MSME / Udyam Registration No.</label>
                                    <input type="text" name="udyam_no"
                                           class="form-control form-control-sm"
                                           placeholder="Udyam-XXXXXXXXXX">
									<span class="text-danger error-text udyam_no_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Preferred Service</label>
                                    <select name="preferred_service" class="form-select form-select-sm">
                                        <option value="">Select an option</option>
                                        <option value="MSME Registration">MSME Registration</option>
                                        <option value="Compliance Review & Health Check">Compliance Review & Health Check</option>
                                        <option value="Loan / Credit Support">Loan / Credit Support</option>
                                        <option value="Tender & Govt. Scheme Support">Tender & Govt. Scheme Support</option>
                                        <option value="Other">Other (Specify Below)</option>
                                    </select>
									<span class="text-danger error-text preferred_service_error"></span>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Additional Details (optional)</label>
                                    <textarea name="details" rows="3"
                                              class="form-control form-control-sm"
                                              placeholder="Describe your requirement"></textarea>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <button type="submit"
                                        class="btn btn-success px-5 fw-semibold text-uppercase">
                                    Apply Now
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- SIMPLE SCRIPT TO SHOW FORM WHEN BUTTON CLICKED --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('showApplyFormBtn');
        var section = document.getElementById('applyFormSection');

        if (btn && section) {
            btn.addEventListener('click', function () {
                section.classList.remove('d-none');
                section.scrollIntoView({ behavior: 'smooth' });
            });
        }
    });
	
	$('#msmeApplyForm').submit(function(e){
		e.preventDefault();
		$("#loader").show();
		$.ajax({
			url: '/msme/apply',
			type: 'POST',
			data: $(this).serialize(),
			beforeSend: function(){
				$('.error-text').text('');
			},
			success: function(res){
				$("#loader").hide();
				if(res.success){
					showToast(res.message, 'success');
					$('#msmeApplyForm')[0].reset();
					setTimeout(function(){
						window.location.href = "{{ url('/msme/list') }}";
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
	
	
</script>

@endsection
