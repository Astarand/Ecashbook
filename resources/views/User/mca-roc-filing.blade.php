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
                        <li class="breadcrumb-item"><a href="{{ url('/mca-roc/list') }}">MCA/ROC Filings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Apply for MCA/ROC Filings</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Apply for MCA/ROC Filings</h2>
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

                    <form id="mcaRocForm" action="javascript:void(0);" method="post" enctype="multipart/form-data">
                        @csrf

                        <!-- A. Company Basic Details -->
                        <h5 class="text-primary fw-bold mb-3">
                            Company Basic Details
                        </h5>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">
                                Company Name (as per MCA):
                            </label>
                            <div class="col-md-8">
                                <input type="text" name="company_name" value="{{ $company->comp_name ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text company_name_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">CIN:</label>
                            <div class="col-md-8">
                                <input type="text" name="cin" value="{{ $company->cin ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text cin_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">PAN of Company:</label>
                            <div class="col-md-8">
                                <input type="text" name="pan" value="{{ $company->comp_pan_no ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text pan_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Registered Office Address:</label>
                            <div class="col-md-8">
                                <textarea name="reg_office_address" rows="2" class="form-control form-control-sm">{{ ($company->comp_bill_addone ?? '') . ' ' . ($company->comp_bill_addtwo ?? '') }}</textarea>
								<span class="text-danger error-text reg_office_address_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Email ID (MCA Registered):</label>
                            <div class="col-md-8">
                                <input type="email" name="mca_email" value="{{ $company->comp_email ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text mca_email_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Mobile No.:</label>
                            <div class="col-md-8">
                                <input type="text" name="mobile" value="{{ $company->comp_phone ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text mobile_error"></span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label">Date of Incorporation:</label>
                            <div class="col-md-8">
                                <input type="date" name="inc_date" value="{{ $company->start_date ?? '' }}" class="form-control form-control-sm">
								<span class="text-danger error-text inc_date_error"></span>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="col-md-4 col-form-label">
                                Business Activity (NIC Code):
                            </label>
                            <div class="col-md-8">
                                <input type="text" name="nic_code" value="{{ $company->comp_nature ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <hr>

                        <!-- B. Event-Based Filing Inputs -->
                        <h5 class="text-primary fw-bold mb-3">
                            Event-Based Filing Inputs <span class="fw-normal">(if applicable)</span>
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="event_change_director" id="event_change_director">
                                    <label class="form-check-label" for="event_change_director">Change in director</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="event_change_reg_office" id="event_change_reg_office">
                                    <label class="form-check-label" for="event_change_reg_office">Change in registered office</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="event_share_allotment" id="event_share_allotment">
                                    <label class="form-check-label" for="event_share_allotment">Share allotment</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="event_transfer_shares" id="event_transfer_shares">
                                    <label class="form-check-label" for="event_transfer_shares">Transfer of shares</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="event_appointment_auditor" id="event_appointment_auditor">
                                    <label class="form-check-label" for="event_appointment_auditor">Appointment of auditor</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="event_resignation_auditor" id="event_resignation_auditor">
                                    <label class="form-check-label" for="event_resignation_auditor">Resignation of auditor</label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- C. Required Documents Upload -->
                        <h5 class="text-primary fw-bold mb-3">
                            Required Documents Upload
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" value="1" name="doc_moa_aoa" id="doc_moa_aoa">
                                    <label class="form-check-label" for="doc_moa_aoa">MOA &amp; AOA</label>
									<input type="file" name="file_doc_moa_aoa" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" value="1" name="doc_coi" id="doc_coi">
                                    <label class="form-check-label" for="doc_coi">Certificate of Incorporation</label>
									<input type="file" name="file_doc_coi" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" value="1" name="doc_prev_roc" id="doc_prev_roc">
                                    <label class="form-check-label" for="doc_prev_roc">Previous year ROC filing copies</label>
									<input type="file" name="file_doc_prev_roc" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" value="1" name="doc_dsc_auth" id="doc_dsc_auth">
                                    <label class="form-check-label" for="doc_dsc_auth">
                                        Digital Signature (DSC) Authorization Letter
                                    </label>
									<input type="file" name="file_doc_dsc_auth" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input docCheck" type="checkbox" value="1" name="doc_auditor_appointment" id="doc_auditor_appointment">
                                    <label class="form-check-label" for="doc_auditor_appointment">
                                        Auditor Appointment Letter
                                    </label>
									<input type="file" name="file_doc_auditor_appointment" class="form-control form-control-sm mt-1 d-none docFile">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- D. Declaration -->
                        <h5 class="text-primary fw-bold mb-2">
                            Declaration by Client
                        </h5>

                        <p class="fw-semibold mb-2">I/We hereby declare that:</p>

                        <ol class="ps-3 mb-4">
                            <li class="mb-1">
                                All information, books of accounts, and documents provided for MCA/ROC and
                                Income Tax filings are true, correct, and complete to the best of my/our knowledge.
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

                        <!-- Client Details -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Client Name:</label>
                                <input type="text" name="client_name" class="form-control form-control-sm">
								<span class="text-danger error-text client_name_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Designation:</label>
                                <input type="text" name="designation" class="form-control form-control-sm">
								<span class="text-danger error-text designation_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Signature:</label>
                                <input type="text" name="signature" class="form-control form-control-sm">
								<span class="text-danger error-text signature_error"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Date:</label>
                                <input type="date" name="signed_date" class="form-control form-control-sm">
								<span class="text-danger error-text signed_date_error"></span>
                            </div>
                        </div>

                        <hr class="mb-4">

                        <!-- Submit Button -->
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
$('#mcaRocForm').submit(function(e){
    e.preventDefault();
	$("#loader").show();
	let formData = new FormData(this);
    $.ajax({
        url: '/mca-roc/apply',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(res){
			$("#loader").hide();
            if(res.success){
                showToast(res.message, 'success');
                $('#mcaRocForm')[0].reset();
				setTimeout(function(){
					window.location.href = "{{ url('/mca-roc/list') }}";
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
