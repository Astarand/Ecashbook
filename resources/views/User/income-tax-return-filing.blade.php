@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/itr/list') }}">Income Tax Filing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Apply</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-income-tax-return-filing-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Income Tax Return (ITR) Filing</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body px-4 py-4">
                    <form action="javascript:void(0);" method="post" id="itrForm" enctype="multipart/form-data">
                        @csrf

                        {{-- A. BASIC DETAILS --}}
                        <h5 class="text-primary fw-bold mb-3">
                            Basic Details
                            <span class="text-dark fw-normal">
                                (Existing information will be auto Fetch, rest will be filled up)
                            </span>
                        </h5>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Legal Name:</label>
                            <div class="col-md-8">
                                <input type="text" name="legal_name" value="{{ $companyProfile->comp_name ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text legal_name_error"></span>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label">Trade Name (if any):</label>
                            <div class="col-md-8">
                                <input type="text" name="trade_name" value="{{ $companyProfile->exact_comp_nature ?? '' }}"  class="form-control form-control-sm">
								<span class="text-danger error-text trade_name_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">PAN:</label>
                            <div class="col-md-8">
                                <input type="text" name="pan" value="{{ $companyProfile->comp_pan_no ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text pan_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Aadhaar:</label>
                            <div class="col-md-8">
                                <input type="text" name="aadhaar"  class="form-control form-control-sm">
								<span class="text-danger error-text aadhaar_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Date of Birth/Incorporation:</label>
                            <div class="col-md-8">
                                <input type="date" name="dob_inc" value="{{ $companyProfile->start_date ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text dob_inc_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Mobile Number:</label>
                            <div class="col-md-8">
                                <input type="text" name="mobile" value="{{ $companyProfile->comp_phone ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text mobile_error"></span>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label">Email ID:</label>
                            <div class="col-md-8">
                                <input type="email" name="email" value="{{ $companyProfile->comp_email ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <hr>

                        {{-- B. FILING TYPE --}}
                        <h5 class="text-primary fw-bold mb-3">
                            Filing Type
                        </h5>
						@php
							$compType = $companyProfile->comp_type ?? '';
						@endphp
                        <div class="row mb-4">
							<div class="col-md-6">

								<div class="form-check mb-2">
									<input class="form-check-input" type="checkbox" name="filing_individual" value="1"
										{{ in_array($compType, ['Society/Trust']) ? 'checked' : '' }}>
									<label class="form-check-label">Individual</label>
								</div>

								<div class="form-check mb-2">
									<input class="form-check-input" type="checkbox" name="filing_proprietorship" value="1"
										{{ $compType === 'Proprietorship' ? 'checked' : '' }}>
									<label class="form-check-label">Proprietorship</label>
								</div>

								<div class="form-check mb-2">
									<input class="form-check-input" type="checkbox" name="filing_partnership" value="1"
										{{ $compType === 'Partnership' ? 'checked' : '' }}>
									<label class="form-check-label">Partnership Firm</label>
								</div>

							</div>

							<div class="col-md-6">

								<div class="form-check mb-2">
									<input class="form-check-input" type="checkbox" name="filing_llp" value="1"
										{{ $compType === 'LLP Company' ? 'checked' : '' }}>
									<label class="form-check-label">LLP</label>
								</div>

								<div class="form-check mb-2">
									<input class="form-check-input" type="checkbox" name="filing_company" value="1"
										{{ in_array($compType, [
											'One person Company (OPC)',
											'PVT Ltd Company',
											'LTD Company',
											'Section-8 Company'
										]) ? 'checked' : '' }}>
									<label class="form-check-label">Pvt Ltd / Ltd Company</label>
								</div>

							</div>
						</div>


                        <hr>

                        {{-- C. INCOME DETAILS REQUIRED --}}
                        <h5 class="text-primary fw-bold mb-3">
                           Income Details Required
                        </h5>

                        <h6 class="text-danger fw-semibold mb-2">For Individuals / Proprietorship:</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_salary_16" id="ind_salary_16" value="1">
                                    <label class="form-check-label" for="ind_salary_16">Salary Form 16</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_bank_stmt" id="ind_bank_stmt" value="1">
                                    <label class="form-check-label" for="ind_bank_stmt">Bank Statements (FY)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_books" id="ind_books" value="1">
                                    <label class="form-check-label" for="ind_books">Books of Accounts (if business)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_gst_returns" id="ind_gst_returns" value="1">
                                    <label class="form-check-label" for="ind_gst_returns">
                                        GST Returns (for turnover cross-check)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_pl" id="ind_pl" value="1">
                                    <label class="form-check-label" for="ind_pl">Profit &amp; Loss A/c</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_bs" id="ind_bs" value="1">
                                    <label class="form-check-label" for="ind_bs">Balance Sheet</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_rental" id="ind_rental" value="1">
                                    <label class="form-check-label" for="ind_rental">Rental Income details</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ind_other_income" id="ind_other_income" value="1">
                                    <label class="form-check-label" for="ind_other_income">
                                        Other income (interest, capital gains, dividends)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-danger fw-semibold mb-2">For Firms / LLP / Companies:</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_final_accounts" id="firm_final_accounts" value="1">
                                    <label class="form-check-label" for="firm_final_accounts">
                                        Final Accounts (BS + P&amp;L + Notes)
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_bank_stmt" id="firm_bank_stmt" value="1">
                                    <label class="form-check-label" for="firm_bank_stmt">Bank Statements</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_gst_summary" id="firm_gst_summary" value="1">
                                    <label class="form-check-label" for="firm_gst_summary">GST Returns Summary</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_tds" id="firm_tds" value="1">
                                    <label class="form-check-label" for="firm_tds">TDS Summary</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_depreciation" id="firm_depreciation" value="1">
                                    <label class="form-check-label" for="firm_depreciation">Depreciation details</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_loan_conf" id="firm_loan_conf">
                                    <label class="form-check-label" for="firm_loan_conf">Loan confirmations</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="firm_related_party" id="firm_related_party" value="1">
                                    <label class="form-check-label" for="firm_related_party">Related party transactions</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- D. TAX DETAILS --}}
                        <h5 class="text-primary fw-bold mb-3">
                           Tax Details
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tax_26as" id="tax_26as" value="1">
                                    <label class="form-check-label" for="tax_26as">Form 26AS</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tax_ais_tis" id="tax_ais_tis" value="1">
                                    <label class="form-check-label" for="tax_ais_tis">AIS/TIS Report</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tax_tds_cert" id="tax_tds_cert" value="1">
                                    <label class="form-check-label" for="tax_tds_cert">
                                        TDS Certificates (Form 16/16A)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tax_adv_challan" id="tax_adv_challan" value="1">
                                    <label class="form-check-label" for="tax_adv_challan">Advance tax challans</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="tax_self_assess" id="tax_self_assess" value="1">
                                    <label class="form-check-label" for="tax_self_assess">Self-assessment tax challans</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- E. BUSINESS / PROFESSIONAL INFORMATION --}}
                        <h5 class="text-primary fw-bold mb-3">
                           Business / Professional Information
                        </h5>

                        <ul class="mb-4">
                            <li class="mb-2">
                                Nature of Business (Code):
                                <input type="text" name="nature_business" class="form-control form-control-sm d-inline-block w-auto ms-2">
                            </li>
                            <li class="mb-2">
                                Turnover Details:
                                <input type="text" name="turnover_details" class="form-control form-control-sm d-inline-block w-auto ms-2">
                            </li>
                            <li class="mb-2">
                                Asset &amp; Liabilities details (for ITR-3/5/6):
                                <input type="text" name="asset_liab" class="form-control form-control-sm d-inline-block w-auto ms-2">
                            </li>
                            <li class="mb-2">
                                Stock summary:
                                <input type="text" name="stock_summary" class="form-control form-control-sm d-inline-block w-auto ms-2">
                            </li>
                            <li class="mb-2">
                                Capital account statement (Partnership/LLP):
                                <input type="text" name="capital_account" class="form-control form-control-sm d-inline-block w-auto ms-2">
                            </li>
                        </ul>

                        <hr>

                        {{-- F. VERIFICATION PERSON DETAILS --}}
                        <h5 class="text-primary fw-bold mb-3">
                            Verification Person Details
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Name:</label>
                                <input type="text" name="ver_name" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Designation:</label>
                                <input type="text" name="ver_designation" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">PAN:</label>
                                <input type="text" name="ver_pan" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Mobile:</label>
                                <input type="text" name="ver_mobile" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email:</label>
                                <input type="email" name="ver_email" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Upload DSC (if applicable):</label>
                                <input type="file" name="ver_dsc" class="form-control form-control-sm">
                            </div>
                        </div>

                        <hr>

                        {{-- G. REQUIRED DOCUMENTS UPLOAD SECTION --}}
                        <h5 class="text-primary fw-bold mb-3">
                           Required Documents Upload Section
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" name="req_pan" id="req_pan" value="1">
                                    <label class="form-check-label" for="req_pan">PAN</label>
									<input type="file" name="file_req_pan" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" name="req_aadhaar" id="req_aadhaar" value="1">
                                    <label class="form-check-label" for="req_aadhaar">Aadhaar</label>
									<input type="file" name="file_req_aadhaar" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" name="req_bank_passbook" id="req_bank_passbook" value="1">
                                    <label class="form-check-label" for="req_bank_passbook">Bank Passbook (First Page)</label>
									<input type="file" name="file_req_bank_passbook" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" name="req_digital_signature" id="req_digital_signature" value="1">
                                    <label class="form-check-label" for="req_digital_signature">
                                        Digital Signature (For Companies/Firms)
                                    </label>
									<input type="file" name="file_req_digital_signature" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" name="req_prev_itr" id="req_prev_itr" value="1">
                                    <label class="form-check-label" for="req_prev_itr">Previous ITR Copy</label>
									<input type="file" name="file_req_prev_itr" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- D. DECLARATION BY CLIENT (as per sample) --}}
                        <h5 class="text-primary fw-bold mb-2">
                           Declaration by Client
                        </h5>

                        <p class="fw-semibold mb-2">I/We hereby declare that:</p>

                        <ol class="ps-3 mb-4">
                            <li class="mb-1">
                                All information, books of accounts, and documents provided for Income Tax filings are
                                true, correct, and complete to the best of my/our knowledge.
                            </li>
                            <li class="mb-1">
                                I/We understand that the accuracy of filings depends entirely on the accuracy of data
                                provided by me/us.
                            </li>
                            <li class="mb-1">
                                I/We approve the return/document before filing and authorize the service provider to
                                file on my behalf.
                            </li>
                            <li class="mb-1">
                                I/We accept all Terms &amp; Conditions and agree to the Payment Terms, including advance
                                payment and charges for additional work.
                            </li>
                            <li class="mb-1">
                                I/We take full responsibility for any late submission of documents or incorrect
                                information shared.
                            </li>
                            <li class="mb-1">
                                I/We authorize the service provider to use my documents solely for compliance purposes.
                            </li>
                        </ol>

                        <hr>

                        {{-- CLIENT SIGN SECTION --}}
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Client Name:</label>
                                <input type="text" name="client_name" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Designation:</label>
                                <input type="text" name="client_designation" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Signature:</label>
                                <input type="text" name="client_signature" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Date:</label>
                                <input type="date" name="client_date" class="form-control form-control-sm">
                            </div>
                        </div>

                        <hr class="mb-4">

                        {{-- SUBMIT --}}
                        <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<script>

$('.docCheck').change(function(){

    let fileInput = $(this).closest('.form-check').find('.docFile');

    if($(this).is(':checked')){
        fileInput.removeClass('d-none');
    }else{
        fileInput.addClass('d-none').val('');
    }

});

$('#itrForm').submit(function(e){
    e.preventDefault();

    let formData = new FormData(this);
	$("#loader").show();
    $.ajax({
        url: "{{ route('itr.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $('.error-text').remove();
        },
        success: function(res){
			$("#loader").hide();
            if(res.success){
                showToast(res.message,'success');
                $('#itrForm')[0].reset();
				setTimeout(function(){
					window.location.href = "{{ url('/itr/list') }}";
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


    function startIncomeTaxReturnFilingTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Income Tax Returns Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">File ITR filings, upload assessment orders, and log tax payments.</p></div>'
                },
                {
                    title: 'Income Tax Returns',
                    intro: 'File ITR filings, upload assessment orders, and log tax payments.'
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
        $('#start-income-tax-return-filing-tour').on('click', function(e) {
            e.preventDefault();
            startIncomeTaxReturnFilingTour();
        });
    });
</script>

@endsection
