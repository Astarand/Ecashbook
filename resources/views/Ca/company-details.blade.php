@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="row">
                <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                    <div class="card shadow-sm p-4 h-100 d-flex flex-column justify-content-center align-items-center">
                        <div class="company-logo mb-3 d-flex justify-content-center">
                            {{-- <div class="logo-wrapper rounded-circle d-flex align-items-center justify-content-center bg-light"
                                style="width: 280px; height: 280px; overflow: hidden;">

                                @if(!empty($customers->comp_logo))
                                    <img src="{{ asset('storage/profile/' . $customers->comp_logo) }}"
                                        alt="{{ $customers->comp_name }}"
                                        class="w-100 h-100"
                                        style="object-fit: cover;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif

                                <div class="placeholder-image d-flex align-items-center justify-content-center w-100 h-100"
                                    style="{{ !empty($customers->comp_logo) ? 'display:none;' : 'display:flex;' }}">
                                    <i class="ph-duotone ph-image text-muted" style="font-size: 80px;"></i>
                                </div>

                            </div> --}}

                            <div class="logo-wrapper rounded-circle d-flex align-items-center justify-content-center bg-light"
                                style="width: 280px; height: 280px; overflow: hidden;">

                                <img 
                                    src="{{ !empty($customers->comp_logo) ? asset('storage/profile/' . $customers->comp_logo) : asset('assets/images/user/ecashbook.png') }}"
                                    alt="{{ $customers->comp_name ?? 'Company Logo' }}"
                                    class="w-100 h-100"
                                    style="object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/user/ecashbook.png') }}';">

                            </div>
                        </div>

                        <h5 class="fw-bold mb-2 text-center">{{ $customers->comp_name }}</h5>
                        <div>
                            @if($customers->status == 1)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Deactivated</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body p-3">
                            <ul class="nav nav-pills d-flex" id="companyDetailsTab" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link active w-100" id="basic-details-tab" data-bs-toggle="pill"
                                        data-bs-target="#basic-details" type="button" role="tab"
                                        aria-controls="basic-details" aria-selected="true">
                                        Basic Details
                                    </button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="business-details-tab" data-bs-toggle="pill"
                                        data-bs-target="#business-details" type="button" role="tab"
                                        aria-controls="business-details" aria-selected="false">
                                        Business Details
                                    </button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="bank-details-tab" data-bs-toggle="pill"
                                        data-bs-target="#bank-details" type="button" role="tab"
                                        aria-controls="bank-details" aria-selected="false">
                                        Bank Details
                                    </button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="director-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#director-profile" type="button" role="tab"
                                        aria-controls="director-profile" aria-selected="false">
                                        Director Profile
                                    </button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="purpose-attachment-tab" data-bs-toggle="pill"
                                        data-bs-target="#purpose-attachment" type="button" role="tab"
                                        aria-controls="purpose-attachment" aria-selected="false">
                                        Purpose of Attachment
                                    </button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="access-details-tab" data-bs-toggle="pill"
                                        data-bs-target="#access-details" type="button" role="tab"
                                        aria-controls="access-details" aria-selected="false">
                                        Access Details
                                    </button>
                                </li>
								<li class="nav-item d-flex align-items-center ps-2" role="presentation">
									<a href="javascript:void(0);"
									   class="btn btn-sm btn-success rounded-pill px-3 py-1 position-relative start-chat"
									   data-company="{{ $custId }}"
									   data-ca="{{ $caId }}">
										<i class="ph ph-chat-circle-dots me-1"></i>
										Chat
									</a>
								</li>
                            </ul>
                        </div>
                    </div>
                    <?php
                        // echo '<pre>';
                        // print_r($customers);
                        
                        
                    ?>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="tab-content" id="companyDetailsTabContent">
                                <!-- Basic Details Tab -->
                                <div class="tab-pane fade show active" id="basic-details" role="tabpanel"
                                    aria-labelledby="basic-details-tab">
                                    <div class="row g-3">
                                        
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-buildings"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Company Name</h6>
                                                        <p>{{ $customers->comp_name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-envelope-open"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Company Email</h6>
                                                        <p>{{ $customers->comp_email }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-phone-call"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Phone Number</h6>
                                                        <p>{{ $customers->comp_phone }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-globe"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Website</h6>
                                                        <p><a href="https://{{ $customers->comp_website }}" target="_blank">{{ $customers->comp_website }}</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-receipt"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>GST Number</h6>
                                                        <p>{{ $customers->gst_no }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-arrows-left-right"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Company Transaction Type</h6>
                                                        <p>{{ $customers->comp_tran_type }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-identification-card"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>PAN Number</h6>
                                                        <p>{{ $customers->comp_pan_no }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-identification-badge"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>TAN Number</h6>
                                                        <p>{{ $customers->comp_tan }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-file-text"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>EPF Employer Registration No</h6>
                                                        <p>{{ $customers->comp_epf }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-first-aid-kit"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>ESIC Employer Registration</h6>
                                                        <p>{{ $customers->comp_esic }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-certificate"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>P-Tax Enrolment Certificate No</h6>
                                                        <p>{{ $customers->comp_ptax_cert }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-seal-check"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>P-Tax Registration Number</h6>
                                                        <p>{{ $customers->comp_ptax }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-factory"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Udyam Registration Number (URN)</h6>
                                                        <p>{{ $customers->udyam_reg_no }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-map-pin"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Billing Address</h6>
                                                        <p>{{ $customers->comp_bill_addone }}, 
                                                            {{ $customers->comp_bill_addtwo }},
                                                            {{ $customers->comp_bill_city }},
                                                            {{ $customers->comp_bill_state }}, 
                                                            {{ $customers->comp_bill_pin }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-navigation-arrow"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Shipping Address</h6>
                                                        <p>
                                                            {{ $customers->comp_ship_addone }}, 
                                                            {{ $customers->comp_ship_addtwo }},
                                                            {{ $customers->comp_ship_city }},
                                                            {{ $customers->comp_ship_state }},
                                                            {{ $customers->comp_ship_pin }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Details Tab -->
                                <div class="tab-pane fade" id="business-details" role="tabpanel"
                                    aria-labelledby="business-details-tab">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-briefcase"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Company Type</h6>
                                                        <p>{{ $customers->comp_type }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-chart-line-up"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Exact Nature of Business</h6>
                                                        <p>{{ $customers->comp_nature }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-currency-inr"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Turnover in Last Year</h6>
                                                        <p>₹ {{ $customers->turnover_last_year }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="customer-details">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="customer-widget-icon">
                                                        <i class="ph-duotone ph-calendar-check"></i>
                                                    </span>
                                                    <div class="customer-details-cont">
                                                        <h6>Start Date of Business</h6>
                                                        <p>{{ $customers->start_date }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Details Tab -->
                                <div class="tab-pane fade" id="bank-details" role="tabpanel" aria-labelledby="bank-details-tab">
                                    <div class="row g-3">
                                
                                        @forelse($bankDetails as $index => $bank)
                                            <div class="col-md-12">
                                                <div class="card border shadow-sm">
                                
                                                    <div class="card-header {{ $index == 0 ? 'bg-primary' : 'bg-success' }} text-white">
                                                        <h6 class="mb-0">
                                                            <i class="ph-duotone ph-bank me-2"></i>
                                                            {{ $index == 0 ? 'Primary Bank Account' : 'Secondary Bank Account' }}
                                                        </h6>
                                                    </div>
                                
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                
                                                            <div class="col-md-6">
                                                                <div class="customer-details d-flex align-items-center gap-3">
                                                                    <span class="customer-widget-icon">
                                                                        <i class="ph-duotone ph-bank"></i>
                                                                    </span>
                                                                    <div>
                                                                        <h6>Bank Name</h6>
                                                                        <p>{{ $bank->bank_name }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                
                                                            <div class="col-md-6">
                                                                <div class="customer-details d-flex align-items-center gap-3">
                                                                    <span class="customer-widget-icon">
                                                                        <i class="ph-duotone ph-user-circle"></i>
                                                                    </span>
                                                                    <div>
                                                                        <h6>Account Holder Name</h6>
                                                                        <p>{{ $bank->bank_holder_name }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                
                                                            <div class="col-md-6">
                                                                <div class="customer-details d-flex align-items-center gap-3">
                                                                    <span class="customer-widget-icon">
                                                                        <i class="ph-duotone ph-credit-card"></i>
                                                                    </span>
                                                                    <div>
                                                                        <h6>Account Number</h6>
                                                                        <p>{{ $bank->ac_no }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                
                                                            <div class="col-md-6">
                                                                <div class="customer-details d-flex align-items-center gap-3">
                                                                    <span class="customer-widget-icon">
                                                                        <i class="ph-duotone ph-code"></i>
                                                                    </span>
                                                                    <div>
                                                                        <h6>IFSC Code</h6>
                                                                        <p>{{ $bank->ifsc_code }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                
                                                            <div class="col-md-6">
                                                                <div class="customer-details d-flex align-items-center gap-3">
                                                                    <span class="customer-widget-icon">
                                                                        <i class="ph-duotone ph-map-pin-line"></i>
                                                                    </span>
                                                                    <div>
                                                                        <h6>Branch Name</h6>
                                                                        <p>{{ $bank->bank_branch }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                
                                                            @if(!empty($bank->ac_upid))
                                                                <div class="col-md-6">
                                                                    <div class="customer-details d-flex align-items-center gap-3">
                                                                        <span class="customer-widget-icon">
                                                                            <i class="ph-duotone ph-wallet"></i>
                                                                        </span>
                                                                        <div>
                                                                            <h6>UPI ID</h6>
                                                                            <p>{{ $bank->ac_upid }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-md-12 text-center text-muted">
                                                No bank details available
                                            </div>
                                        @endforelse
                                
                                    </div>
                                </div>
                                

                                <!-- Director Profile Tab -->
                                <div class="tab-pane fade" id="director-profile" role="tabpanel"
                                    aria-labelledby="director-profile-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Director Name</th>
                                                    <th>Designation</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($comp_director as $key => $director)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $director->director_name }}</td>
                                                        <td>{{ $director->director_designation }}</td>
                                                        <td>{{ $director->director_email }}</td>
                                                        <td>{{ $director->director_phone }}</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#directorDetailsModal"
                                                                onclick="showDirectorDetails(
                                                                    '{{ $director->director_name }}',
                                                                    '{{ $director->director_designation }}',
                                                                    '{{ $director->director_email }}',
                                                                    '{{ $director->director_phone }}',
                                                                    '{{ $director->director_din }}',
                                                                    '{{ $director->director_signature }}'
                                                                )">
                                                                <i class="ti ti-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">
                                                            No director details found
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                </tbody>                                                
                                        </table>
                                    </div>
                                </div>

                                <!-- Purpose of Attachment Tab -->
                                @php
                                    $requestFor = [];
                                    $otherSpecify = '';

                                    if (!empty($ca_assigns_details) && isset($ca_assigns_details[0])) {
                                        if (!empty($ca_assigns_details[0]->request_for)) {
                                            $requestFor = array_map('trim', explode(',', $ca_assigns_details[0]->request_for));
                                        }
                                        $otherSpecify = $ca_assigns_details[0]->other_specify ?? '';
                                    }
                                @endphp

                                <div class="tab-pane fade" id="purpose-attachment" role="tabpanel"
                                    aria-labelledby="purpose-attachment-tab">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose1"
                                                    value="Company Incorporation"
                                                    {{ in_array('Company Incorporation', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose1">
                                                    <i class="ph-duotone ph-buildings me-2"></i>Company Incorporation
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose2"
                                                    value="Company Compliances"
                                                    {{ in_array('Company Compliances', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose2">
                                                    <i class="ph-duotone ph-check-square me-2"></i>Company Compliances
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose3"
                                                    value="MCA / ROC Compliances"
                                                    {{ in_array('MCA / ROC Compliances', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose3">
                                                    <i class="ph-duotone ph-file-text me-2"></i>MCA / ROC Compliances
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose4"
                                                    value="Accounts Preparation"
                                                    {{ in_array('Accounts Preparation', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose4">
                                                    <i class="ph-duotone ph-calculator me-2"></i>Accounts Preparation
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose5"
                                                    value="GST & Filings"
                                                    {{ in_array('GST & Filings', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose5">
                                                    <i class="ph-duotone ph-receipt me-2"></i>GST & Filings
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose6"
                                                    value="Auditing"
                                                    {{ in_array('Auditing', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose6">
                                                    <i class="ph-duotone ph-magnifying-glass me-2"></i>Auditing
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose7"
                                                    value="Auditor Recruitment"
                                                    {{ in_array('Auditor Recruitment', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose7">
                                                    <i class="ph-duotone ph-user-plus me-2"></i>Auditor Recruitment
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose8"
                                                    value="MSME / Trade Lisence"
                                                    {{ in_array('MSME / Trade Lisence', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose8">
                                                    <i class="ph-duotone ph-factory me-2"></i>MSME / Trade Lisence
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose9"
                                                    value="Licensing & Registration"
                                                    {{ in_array('Licensing & Registration', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose9">
                                                    <i class="ph-duotone ph-certificate me-2"></i>Licensing & Registration
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose10"
                                                    value="Income Tax Filings"
                                                    {{ in_array('Income Tax Filings', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose10">
                                                    <i class="ph-duotone ph-file-doc me-2"></i>Income Tax Filings
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose11"
                                                    value="TDS & Filing"
                                                    {{ in_array('TDS & Filing', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose11">
                                                    <i class="ph-duotone ph-receipt-x me-2"></i>TDS & Filing
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose12"
                                                    value="PF & ESIC"
                                                    {{ in_array('PF & ESIC', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose12">
                                                    <i class="ph-duotone ph-first-aid-kit me-2"></i>PF & ESIC
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose13"
                                                    value="Professional Tax"
                                                    {{ in_array('Professional Tax', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose13">
                                                    <i class="ph-duotone ph-currency-circle-dollar me-2"></i>Professional Tax
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose14"
                                                    value="Project Report / DPR with CMA Data"
                                                    {{ in_array('Project Report / DPR with CMA Data', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose14">
                                                    <i class="ph-duotone ph-chart-bar me-2"></i>Project Report / DPR with CMA Data
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose15"
                                                    value="Outsourcing of work"
                                                    {{ in_array('Outsourcing of work', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose15">
                                                    <i class="ph-duotone ph-handshake me-2"></i>Outsourcing of work
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose16"
                                                    value="Outsourcing of Employee"
                                                    {{ in_array('Outsourcing of Employee', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose16">
                                                    <i class="ph-duotone ph-users-three me-2"></i>Outsourcing of employee
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose17"
                                                    value="Payroll & HR Compliances"
                                                    {{ in_array('Payroll & HR Compliances', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose17">
                                                    <i class="ph-duotone ph-identification-card me-2"></i>Payroll & HR Compliances
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose18"
                                                    value="Company Leagal Service"
                                                    {{ in_array('Company Leagal Service', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose18">
                                                    <i class="ph-duotone ph-scales me-2"></i>Company Leagal Service
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose19"
                                                    value="Consulting & Advisory Services"
                                                    {{ in_array('Consulting & Advisory Services', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose19">
                                                    <i class="ph-duotone ph-lightbulb me-2"></i>Consulting & Advisory Services
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose20"
                                                    value="DPDP Act 2023"
                                                    {{ in_array('DPDP Act 2023', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose20">
                                                    <i class="ph-duotone ph-shield-check me-2"></i>DPDP Act,2023
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose21"
                                                    value="IP Advisory"
                                                    {{ in_array('IP Advisory', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose21">
                                                    <i class="ph-duotone ph-copyright me-2"></i>IP Advisory
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="purpose22"
                                                    value="Other"
                                                    {{ in_array('Other', $requestFor) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="purpose22">
                                                    <i class="ph-duotone ph-copyright me-2"></i>Other
                                                </label>
                                            </div>
                                        </div>

                                        @if(in_array('Other', $requestFor))
                                            <div class="col-md-12">
                                                <div class="mt-2 p-3 border rounded bg-light">
                                                    <strong>Other (Specify):</strong>
                                                    <p class="mb-0">{{ $otherSpecify }}</p>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>


                                <!-- Access Details Tab -->
                                <div class="tab-pane fade" id="access-details" role="tabpanel" aria-labelledby="access-details-tab">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div>
                                            <h5 class="mb-1">Access Authorization Details</h5>
                                            <p class="text-muted small mb-0">Overview of modules authorized by the client for CA access</p>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light sticky-top" style="z-index: 1;">
                                                <tr>
                                                    <th>Module Name</th>
                                                    <th>Access Status</th>
                                                    <th>Permission Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
											@php
												$permissions = json_decode($customers->ca_permissions ?? '{}', true);
											@endphp

											<tbody>
												@foreach($accountant_access as $module)
													@php
														$moduleKey = $module->module_value;
														$hasView = $permissions[$moduleKey]['view'] ?? false;
														$hasEdit = $permissions[$moduleKey]['edit'] ?? false;
														$hasAccess = ($hasView || $hasEdit);
														$moduleUrl = url($module->module_action);
													@endphp
													<tr>
														<td class="align-middle fw-semibold text-dark">
															{{ $module->module_name }}
														</td>
														<td class="align-middle">
															@if($hasAccess)
																<span class="badge bg-light-success text-dark f-12">
																	Access Provided
																</span>
															@else
																<span class="badge bg-light-danger text-danger f-12">
																	No Access
																</span>
															@endif
														</td>
														<td class="align-middle">
															@if($hasView && $hasEdit)
																<span class="badge bg-light-info text-dark f-12">
																	<i class="ph-duotone ph-eye me-1"></i>
																	View Only
																</span>
															@elseif($hasView)
																<span class="badge bg-light-primary text-dark f-12">
																	<i class="ph-duotone ph-eye me-1"></i>
																	View Only
																</span>
															@else
																<span class="text-muted">—</span>
															@endif
														</td>

														{{-- Action Button --}}
														<td class="align-middle">
															@if($hasAccess)
																<!--<a href="{{ $moduleUrl . '?compId=' . encrypt($custId) }}"
																	target="_blank"
																	class="btn btn-sm btn-icon btn-light-secondary"
																	title="View Module">
																	<i class="ph-duotone ph-arrow-square-out"></i>
																</a>-->
																<button type="button"
																		class="btn btn-sm btn-icon btn-light-secondary open-module"
																		data-url="{{ $moduleUrl . '?compId=' . encrypt($custId) }}"
																		title="View Module">
																	<i class="ph-duotone ph-arrow-square-out"></i>
																</button>
															@else
																<button
																	type="button"
																	class="btn btn-sm btn-icon btn-light-secondary disabled"
																	title="No Access">
																	<i class="ph-duotone ph-arrow-square-out"></i>
																</button>
															@endif
														</td>
													</tr>
												@endforeach
											</tbody>                                            
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 mb-4">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-list-numbers text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total No of Task</h6>
                                <h4 class="fw-bold mb-0">{{ $totalTasks }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-list-magnifying-glass text-warning f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">No of Ongoing Task</h6>
                                <h4 class="fw-bold mb-0">{{ $ongoingTasks }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-list-plus text-danger f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">No of Pending Task</h6>
                                <h4 class="fw-bold mb-0">{{ $pendingTasks }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-currency-inr text-success f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Total Amount</h6>
                                <h4 class="fw-bold mb-0">₹ {{ number_format($total_amount, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-currency-inr text-primary f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Received Amount</h6>
                                <h4 class="fw-bold mb-0">₹ {{ number_format($totalReceivedAmount, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-hourglass text-warning f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Due Amount</h6>
                                <h4 class="fw-bold mb-0">₹ {{ number_format($dueAmount, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="card shadow-sm p-3 position-relative">
                        <!-- Pencil Icon Top Right -->
                        <div class="d-flex align-items-center">
                            <div class="task-icon d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-infinity text-success f-30"></i>
                            </div>
                            <div class="ms-2">
                                <h6 class="fw-bold text-muted mb-1">Recurring</h6>
                                <h4 class="fw-bold mb-0">₹ {{ number_format($recurringAmount, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12 justify-content-end d-flex">
            <button type="button" class="btn btn-primary btn-xl mb-3 me-2" data-bs-toggle="modal" data-bs-target="#BankModal">
                <i class="ph-duotone ph-piggy-bank"></i> Show Customer Account Details
            </button>
        </div> --}}
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-body table-card py-3">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs mb-3" id="taskTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="task-assign-tab" data-bs-toggle="tab" data-bs-target="#task-assign" type="button" role="tab" aria-controls="task-assign" aria-selected="true">
                                Task Assign List
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="transaction-history-tab" data-bs-toggle="tab" data-bs-target="#transaction-history" type="button" role="tab" aria-controls="transaction-history" aria-selected="false">
                                Transaction History
                            </button>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" id="taskTabContent">
                        <!-- Task Assign List Table -->
                        <div class="tab-pane fade show active" id="task-assign" role="tabpanel" aria-labelledby="task-assign-tab">
                            <table class="table tbl-product" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th class="text-end">#</th>
                                        <th>Date</th>
                                        <th>Task Category</th>
                                        <th>Govt. Fees</th>
                                        <th>Service Charges</th>
                                        <th>Total Amount</th>
                                        <th>Task Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($tasks as $key => $task)
                                    <tr>
                                        <td class="text-end">{{ $i++ }}</td>
                                        <td><a class="text-muted text-hover-primary">{{ \Carbon\Carbon::parse($task->task_date)->format('d-m-Y') }}</a></td>
                                        <td><a class="text-muted text-hover-primary">{{ $task->task_category_name }}</a></td>
                                        <td><a class="text-muted text-hover-primary">₹ {{ number_format($task->gov_fees, 2) }}</a></td>
                                        <td><a class="text-muted text-hover-primary">₹ {{ number_format($task->services_charges, 2) }}</a></td>
                                        <td><a class="text-muted text-hover-primary">₹ {{ number_format($task->total_amount, 2) }}</a></td>
                                        <td>
                                            @if ($task->project_status == 1)
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif ($task->project_status == 2)
                                            <span class="badge bg-info">Ongoing</span>
                                            @elseif ($task->project_status == 3)
                                            <span class="badge bg-success">Done</span>
                                            @else
                                            <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span><i class="ti ti-dots-vertical f-20"></i></span>
                                            <div class="prod-action-links">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="View">
                                                        <a href="#" class="avtar avtar-xs btn-link-secondary btn-pc-default" data-bs-toggle="offcanvas" data-bs-target="#productOffcanvas">
                                                            <i class="ti ti-eye f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                                        <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" data-bs-toggle="offcanvas" data-bs-target="#productOffcanvas">
                                                            <i class="ti ti-trash f-18"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Transaction History Table -->
                        <div class="tab-pane fade" id="transaction-history" role="tabpanel" aria-labelledby="transaction-history-tab">
                            <table class="table tbl-product" id="transaction-history-table">
                                <thead>
                                    <tr>
                                        <th class="text-end">#</th>
                                        <th>Date</th>
                                        <th>Transaction Type</th>
                                        <th>Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-end">1</td>
                                        <td><a class="text-muted text-hover-primary">02-01-2025</a></td>
                                        <td><a class="text-muted text-hover-primary">Credit</a></td>
                                        <td><a class="text-muted text-hover-primary">₹ 500</a></td>
                                        <td><a class="text-muted text-hover-primary">Bank Transfer</a></td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>
                                            <span><i class="ti ti-dots-vertical f-20"></i></span>
                                            <div class="prod-action-links">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" aria-label="View" data-bs-original-title="View">
                                                        <a href="#" class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-eye f-18"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Add more transaction rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Director Details Modal -->
<div class="modal fade" id="directorDetailsModal" tabindex="-1" aria-labelledby="directorDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="directorDetailsModalLabel">
                    <i class="ph-duotone ph-user-circle" style="margin-right: 8px;"></i>Director Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="customer-details">
                            <div class="d-flex align-items-center gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-user"></i>
                                </span>
                                <div class="customer-details-cont">
                                    <h6>Name</h6>
                                    <p id="directorName">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="customer-details">
                            <div class="d-flex align-items-center gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-phone"></i>
                                </span>
                                <div class="customer-details-cont">
                                    <h6>Phone Number</h6>
                                    <p id="directorPhone">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="customer-details">
                            <div class="d-flex align-items-center gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-briefcase"></i>
                                </span>
                                <div class="customer-details-cont">
                                    <h6>Designation</h6>
                                    <p id="directorDesignation">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="customer-details">
                            <div class="d-flex align-items-center gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-identification-card"></i>
                                </span>
                                <div class="customer-details-cont">
                                    <h6>DIN Number</h6>
                                    <p id="directorDIN">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="customer-details">
                            <div class="d-flex align-items-center gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-check-circle"></i>
                                </span>
                                <div class="customer-details-cont">
                                    <h6>DIN Status</h6>
                                    <p id="directorDINStatus">
                                        <span class="badge bg-success">Activated</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="customer-details">
                            <div class="d-flex align-items-center gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-envelope"></i>
                                </span>
                                <div class="customer-details-cont">
                                    <h6>Email</h6>
                                    <p id="directorEmail">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="customer-details">
                            <div class="d-flex align-items-start gap-3">
                                <span class="customer-widget-icon">
                                    <i class="ph-duotone ph-signature"></i>
                                </span>
                                <div class="customer-details-cont w-100">
                                    <h6 class="mb-3">Signature</h6>
                                    <div class="signature-box border rounded p-3 bg-light text-center" style="min-height: 150px; max-width: 400px;">
                                        <img id="signatureImage" src="" alt="Director Signature"
                                            class="img-fluid" style="max-height: 120px; display: none;"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="d-flex align-items-center justify-content-center text-muted" style="min-height: 120px;">
                                            <span><i class="ph-duotone ph-signature me-2"></i>No signature available</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="BankModal" tabindex="-1" aria-labelledby="customerAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="customerAccountModalLabel">
                    <i class="ph-duotone ph-bank" style="margin-right: 8px;"></i>Customer Bank Account Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Bank Name</h6>
                        <p class="fw-bold">State Bank of India</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Account Holder</h6>
                        <p class="fw-bold">Paul PVT</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Account Number</h6>
                        <p class="fw-bold">123456789012</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">IFSC Code</h6>
                        <p class="fw-bold">SBIN0001234</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Branch</h6>
                        <p class="fw-bold">Kolkata Main Branch</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Account Type</h6>
                        <p class="fw-bold">Current Account</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="moduleModal" tabindex="-1">    
    <div class="modal-dialog modal-xl modal-dialog-centered">        
        <div class="modal-content">
            <div class="modal-header">               
                <h5 class="modal-title">
                    Module View
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body p-0">
                <iframe id="moduleFrame"
                        src=""
                        width="100%"
                        height="600px"
                        style="border:none;">
                </iframe>
            </div>
        </div>
    </div>
</div>



<script>

	$(document).on('click', '.open-module', function () {
		let url = $(this).data('url');
		$('#moduleFrame').attr('src', url);
		$('#moduleModal').modal('show');

	});


	// Hide sidebar/header inside iframe
	$('#moduleFrame').on('load', function () {

		let iframe = document.getElementById('moduleFrame');

		let iframeDoc = iframe.contentWindow.document;

		// Hide sidebar
		$(iframeDoc).find('.pc-sidebar').hide();

		// Hide top navbar/header
		$(iframeDoc).find('.pc-header').hide();

		// Expand content full width
		$(iframeDoc).find('.pc-container').css({
			marginLeft: '0px',
			width: '100%',
			maxWidth: '100%'
		});

		$(iframeDoc).find('.pcoded-content').css({
			marginLeft: '0px',
			width: '100%'
		});

	});


	// Clear iframe on modal close
	$('#moduleModal').on('hidden.bs.modal', function () {
		$('#moduleFrame').attr('src', '');
	});


    document.addEventListener('DOMContentLoaded', function() {
        // Add new task row
        document.getElementById('addTaskRowBtn').addEventListener('click', function() {
            const rowHtml = `
                            <div class="row align-items-end mb-3 recurring-task-row">
                                <div class="col-md-4">
                                    <label class="form-label d-md-none">Task Name</label>
                                    <input type="text" class="form-control" name="task_name[]" placeholder="Enter Task Name" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-md-none">Government Fees</label>
                                    <input type="number" class="form-control" name="gov_fee[]" placeholder="Enter Govt. Fees" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label d-md-none">Service Charges</label>
                                    <input type="number" class="form-control" name="service_charge[]" placeholder="Enter Service Charges" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-center">
                                    
                                    <button type="button" class="btn btn-danger delete-task-btn" title="Delete Task">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                            `;
            document.getElementById('recurringTasksRows').insertAdjacentHTML('beforeend', rowHtml);
        });

        // Delegate delete button click
        document.getElementById('recurringTasksRows').addEventListener('click', function(e) {
            if (e.target.closest('.delete-task-btn')) {
                const row = e.target.closest('.recurring-task-row');
                if (row) row.remove();
            }
        });

        // Optionally, handle save button click (currently just a placeholder)
        document.getElementById('recurringTasksRows').addEventListener('click', function(e) {
            if (e.target.closest('.save-task-btn')) {
                // You can add your save logic here
                // For now, just a visual feedback
                e.target.closest('.save-task-btn').classList.add('btn-outline-success');
                setTimeout(() => {
                    e.target.closest('.save-task-btn').classList.remove('btn-outline-success');
                }, 500);
            }
        });
    });

    document.getElementById('submitRecurringForm').addEventListener('click', function() {
        const form = document.getElementById('recurringTasksForm');
        const formData = new FormData(form);

        fetch("{{ route('payment.saveRecurringTasks') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast("Recurring tasks saved successfully!", 'success');
                    location.reload();
                } else {
                    alert("Something went wrong!");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Server error occurred!");
            });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-existing-task').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this task?')) {
                    fetch(`/delete-recurring-task/${taskId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Task deleted successfully!', 'success');
                                location.reload();
                            } else {
                                alert('Something went wrong!');
                            }
                        });
                }
            });
        });
    });

    // Function to show director details in modal
    function showDirectorDetails(name, designation, email, phone, din, signature) {
        document.getElementById('directorName').textContent = name ?? '-';
        document.getElementById('directorDesignation').textContent = designation ?? '-';
        document.getElementById('directorEmail').textContent = email ?? '-';
        document.getElementById('directorPhone').textContent = phone ?? '-';
        document.getElementById('directorDIN').textContent = din ?? '-';

        const signatureImg = document.getElementById('signatureImage');
        const noSign = signatureImg.nextElementSibling;

        if (signature) {
            signatureImg.src = '{{ asset("storage") }}/' + signature;
            signatureImg.style.display = 'block';
            noSign.style.display = 'none';
        } else {
            signatureImg.style.display = 'none';
            noSign.style.display = 'flex';
        }
    }
</script>


@endsection