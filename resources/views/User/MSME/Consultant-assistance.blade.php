@extends('App.Layout')

@section('container')
<div class="pc-content">
    {{-- PAGE HEADER --}}
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center text-white">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-box bg-white bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="ph-duotone ph-users-three fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">Consultant Assistance</h3>
                                <p class="mb-0 text-white opacity-75 small">Get professional advice, project report preparation support, and scheme application filings handled by seasoned CAs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Assistance Request Form --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="fw-bold mb-1 text-dark d-flex align-items-center">
                        <span class="bg-light-success text-success p-2 rounded-3 me-2"><i class="ph-duotone ph-paper-plane-tilt fs-4"></i></span>
                        Submit Consultation Request
                    </h5>
                    <p class="text-muted small">Brief your requirements. A qualified Chartered Accountant will review and call you back.</p>
                </div>
                <div class="card-body px-4 pb-4 pt-2">
                    <form id="consultationForm" action="javascript:void(0);">
                        <div class="row g-3">
                            {{-- Assistance Category --}}
                            <div class="col-md-6">
                                <div class="form-field-group p-3 bg-light rounded-3 border">
                                    <label for="assistanceType" class="form-label fw-bold text-muted uppercase-label mb-1">Assistance Category</label>
                                    <select class="form-select border-0 bg-transparent p-0 text-dark fw-bold" id="assistanceType" required style="outline: none; box-shadow: none;">
                                        <option value="" selected disabled>Select category...</option>
                                        <option value="udyam">Udyam (MSME) Registration</option>
                                        <option value="dpiit">DPIIT Startup Recognition</option>
                                        <option value="subsidy">MSME Subsidy Application</option>
                                        <option value="project_report">Project Report & Loan Syndication</option>
                                        <option value="compliance">MSME Annual Compliance Audit</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Preferred Time --}}
                            <div class="col-md-6">
                                <div class="form-field-group p-3 bg-light rounded-3 border">
                                    <label for="preferredTime" class="form-label fw-bold text-muted uppercase-label mb-1">Callback Window</label>
                                    <select class="form-select border-0 bg-transparent p-0 text-dark fw-bold" id="preferredTime" required style="outline: none; box-shadow: none;">
                                        <option value="morning">Morning (9 AM - 12 PM)</option>
                                        <option value="afternoon" selected>Afternoon (12 PM - 4 PM)</option>
                                        <option value="evening">Evening (4 PM - 7 PM)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Contact Number --}}
                            <div class="col-12">
                                <div class="form-field-group p-3 bg-light rounded-3 border">
                                    <label for="contactNumber" class="form-label fw-bold text-muted uppercase-label mb-1">Contact Phone Number</label>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-secondary me-2">+91</span>
                                        <input type="tel" class="form-control border-0 bg-transparent p-0 text-dark fw-bold" id="contactNumber" placeholder="Enter 10-digit mobile number" pattern="[0-9]{10}" required style="outline: none; box-shadow: none;">
                                    </div>
                                </div>
                            </div>

                            {{-- Brief Requirements --}}
                            <div class="col-12">
                                <div class="form-field-group p-3 bg-light rounded-3 border">
                                    <label for="requirements" class="form-label fw-bold text-muted uppercase-label mb-1">Brief Requirements</label>
                                    <textarea class="form-control border-0 bg-transparent p-0 text-dark fw-semibold" id="requirements" rows="4" placeholder="Describe your business structure and what you need help with..." required style="outline: none; box-shadow: none; resize: none;"></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold text-white shadow-sm d-flex align-items-center justify-content-center gap-2 mt-4" id="btnSubmitRequest" style="border-radius: 8px;">
                            <i class="ph-duotone ph-paper-plane-tilt fs-5"></i> Submit Consultation Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Benefits of Consultant Assistance --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold text-dark mb-4 d-flex align-items-center">
                            <span class="bg-light-primary text-primary p-2 rounded-3 me-2"><i class="ph-duotone ph-shield-check fs-4"></i></span>
                            Why Work With Our Advisors
                        </h5>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-light-success text-success p-2.5 rounded-3 me-3">
                                <i class="ph-duotone ph-briefcase fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Qualified CAs & Corporate Advisors</h6>
                                <p class="text-secondary small mb-0">Your applications and documentation are drafted, filed, and represented by experienced Chartered Accountants.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-light-success text-success p-2.5 rounded-3 me-3">
                                <i class="ph-duotone ph-clock-counter-clockwise fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Hassle-Free Processing</h6>
                                <p class="text-secondary small mb-0">Avoid compliance bottlenecks. We ensure complete documentation audit prior to submission to secure quick approval rates.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-light-success text-success p-2.5 rounded-3 me-3">
                                <i class="ph-duotone ph-database fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Automatic Financial Sync</h6>
                                <p class="text-secondary small mb-0">We directly pull figures from your E-Cashbook ledgers to prepare P&L sheets and projection estimates required for MSME loan syndications.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3 text-center text-md-start">
                        <p class="text-muted small mb-0"><i class="ph-duotone ph-info me-2 text-success"></i>Need urgent support? Write to our support desk directly at **support@ecashbook.com**</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.header-icon-box {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
.form-field-group {
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.form-field-group:focus-within {
    border-color: #667eea;
    background-color: #ffffff !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
.uppercase-label {
    font-size: 0.7rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
</style>
@endsection

@section('page-script')
<script>
$(document).ready(function() {
    // Handle form submit
    $('#consultationForm').on('submit', function() {
        let type = $('#assistanceType option:selected').text();
        let submitBtn = $('#btnSubmitRequest');
        
        // Show loading spinner on button
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...');

        setTimeout(function() {
            // Show success using Swal
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Request Submitted!',
                    text: 'Your request for ' + type + ' assistance has been successfully queued. A Chartered Accountant will contact you on your number within 24 working hours.',
                    icon: 'success',
                    confirmButtonText: 'Great, Thanks!',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    $('#consultationForm')[0].reset();
                    submitBtn.prop('disabled', false).html('<i class="ph-duotone ph-paper-plane-tilt fs-5"></i> Submit Consultation Request');
                });
            } else {
                alert('Your request for ' + type + ' assistance has been successfully queued. A Chartered Accountant will contact you on your number within 24 working hours.');
                $('#consultationForm')[0].reset();
                submitBtn.prop('disabled', false).html('<i class="ph-duotone ph-paper-plane-tilt fs-5"></i> Submit Consultation Request');
            }
        }, 1200);
    });
});
</script>
@endsection
