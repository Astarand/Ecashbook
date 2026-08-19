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
                        <li class="breadcrumb-item active" aria-current="page">Assign CA Firm / Accountant</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-assign-ca-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Assign CA Firm / Accountant</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
	@php
		$expertList = [
			'Business Registration',
			'Accounting & Bookkeeping',
			'MSME / ISO / Trade License consulting',
			'GST Registration & Filing',
			'TDS & Income Tax Filing',
			'MCA/ROC Compliance',
			'Payroll & HR Compliance',
			'Audit',
			'Business Licensing & Certifications',
			'Virtual CFO Services',
			'Project report creation',
			'Accounting Outsourcing',
			'Financial Planning & Advisory',
			'Mergers & Acquisitions',
			'IP Advisory',
			'Insolvency & Bankruptcy Advisory',
			'Valuation Services',
			'Corporate Law & Secretarial Compliances',
			'Compliance on DPDP Act, 2023',
			'Consulting & Advisory Services'
		];
	@endphp

    <div class="row">
        <div class="ca-highlight-section">
            <div class="ca-highlight-content">
                <div class="ca-highlight-background">
                    <div class="ca-highlight-shapes">
                        <div class="ca-shape-1"></div>
                        <div class="ca-shape-2"></div>
                        <div class="ca-shape-3"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center position-relative">
                    <div class="flex-grow-1 me-4">
                        <div class="ca-highlight-badge">
                            <i class="fas fa-star me-2"></i>
                            Featured Service
                        </div>
                        <h2 class="ca-highlight-title">Add your Exiting Chartered Accountant (CA) Or Accounting Professional</h2>
                        <p class="ca-highlight-description">Collaborate digitally with professionals you already trust. The platform does not replace or recommend professionals. Professional profile information provided by the CA / Accounting Professional.
                            <a href="#" class="ca-highlight-link" id="openSidePanel">
                                <span>clicking here</span>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="ca-highlight-icon">
                            <div class="ca-icon-circle">
                                <i class="ph-duotone ph-user-circle"></i>
                            </div>
                            <div class="ca-icon-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="ecom-wrapper">
                <!-- Filter Sidebar -->
                <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="offcanvas_mail_filter">
                    <div class="offcanvas-body p-0 sticky-xxl-top">
                        <div id="ecom-filter" class="show collapse collapse-horizontal">
                            <div class="ecom-filter">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
                                    <div class="card-header bg-transparent d-flex align-items-center justify-content-between py-3 border-bottom">
                                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                                            <i class="ti ti-adjustments-horizontal text-primary fs-4 me-2"></i> Filter Directory
                                        </h5>
                                        <a
                                            href="#"
                                            class="avtar avtar-s btn-link-danger btn-pc-default d-xxl-none"
                                            data-bs-dismiss="offcanvas"
                                            data-bs-target="#offcanvas_mail_filter">
                                            <i class="ti ti-x f-20"></i>
                                        </a>
                                    </div>
                                    <div class="scroll-block">
                                        <div class="card-body p-3 p-xl-4">
                                            <!-- CA Name Search -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-muted text-uppercase small" style="font-size: 0.75rem; letter-spacing: 0.5px;">CA / Firm Name</label>
                                                <div class="input-group filter-input-group">
                                                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                                                    <input type="text" class="form-control" id="caNameInput" placeholder="Search by name...">
                                                </div>
                                            </div>

                                            <!-- Zip Code Search -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-muted text-uppercase small" style="font-size: 0.75rem; letter-spacing: 0.5px;">Search by Pincode / Zip</label>
                                                <div class="input-group filter-input-group">
                                                    <span class="input-group-text"><i class="ti ti-map-pin"></i></span>
                                                    <input type="number" class="form-control" id="zipInput" placeholder="Enter Pincode">
                                                </div>
                                            </div>

                                            <div class="border-top my-3"></div>

                                            <!-- Services Filter -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-muted text-uppercase small d-flex justify-content-between align-items-center" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                    <span><i class="ti ti-briefcase text-primary me-1"></i> Specialization</span>
                                                </label>
                                                <div class="mt-2">
                                                    <div class="form-check my-2 p-2 rounded-2 bg-light bg-opacity-50">
                                                        <input class="form-check-input expert-checkbox ms-1" type="checkbox" id="expertAll" value="all">
                                                        <label class="form-check-label fw-semibold ms-2" for="expertAll">All Services</label>
                                                    </div>

                                                    @php
                                                        $visibleCount = 5;
                                                    @endphp

                                                    @foreach($expertList as $key => $expert)
                                                        @if($key == $visibleCount)
                                                            <div class="collapse" id="moreExperts">
                                                        @endif

                                                        <div class="form-check my-2">
                                                            <input class="form-check-input expert-checkbox"
                                                                   type="checkbox"
                                                                   id="expert{{ $key }}"
                                                                   value="{{ $expert }}">
                                                            <label class="form-check-label small text-secondary" for="expert{{ $key }}">
                                                                {{ $expert }}
                                                            </label>
                                                        </div>
                                                    @endforeach

                                                    @if(count($expertList) > $visibleCount)
                                                        </div>
                                                        <div class="mt-3 text-center">
                                                            <a class="btn btn-sm btn-light-primary text-decoration-none px-3 py-1 rounded-pill fw-semibold"
                                                               data-bs-toggle="collapse"
                                                               href="#moreExperts"
                                                               role="button"
                                                               id="toggleExpertBtn"
                                                               style="font-size: 0.78rem;">
                                                                + Show More Services
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Reset / Clear All -->
                                            <div class="mt-4 pt-2 border-top">
                                                <a href="#" id="clearFiltersBtn" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-refresh me-1"></i> Reset All Filters
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Directory Content -->
                <div class="ecom-content">
                    <div class="message-container"></div>
                    <div class="row g-4">
                        @if ($ca_details)
                        @foreach ($ca_details as $ca_detail)
                            @if($ca_detail->ca_current_status == 0)

                            @php
                                $expertiseList = strtolower(str_replace(' ', '', $ca_detail->ca_spec ?? ''));
                                $ca_name = strtolower($ca_detail->comp_name != "" ? $ca_detail->comp_name : $ca_detail->name);
                                $ca_zip = $ca_detail->comp_bill_pin ?? '';

                                $parts = array_filter([
                                    $ca_detail->comp_bill_addone ?? '',
                                    $ca_detail->ca_city ?? '',
                                    $ca_detail->ca_state ?? '',
                                    $ca_detail->comp_bill_pin ?? ''
                                ]);
                            @endphp

                            <!-- CA Card (col-md-6) -->
                            <div class="col-12 col-md-6 ca-card"
                                data-name="{{ $ca_name }}"
                                data-zip="{{ $ca_zip }}"
                                data-expert="{{ $expertiseList }}">
                                <div class="card user-card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden mb-0"
                                     style="background: #ffffff; border: 1px solid #e8eef5 !important; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">

                                    <!-- Top Subtle Gradient Header Accent -->
                                    <div class="w-100" style="height: 5px; background: linear-gradient(90deg, #008CAD 0%, #6366f1 50%, #8b5cf6 100%);"></div>

                                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                                        <!-- Header: Avatar, Name & Badges -->
                                        <div class="d-flex align-items-start gap-3 mb-3">
                                            <!-- Avatar with Squircle & Online Dot -->
                                            <div class="position-relative flex-shrink-0">
                                                @if(isset($ca_detail->comp_logo) && $ca_detail->comp_logo != "")
                                                    <img class="img-fluid shadow-sm" style="width: 58px; height: 58px; border-radius: 16px; object-fit: cover; border: 2px solid #f1f5f9;" src="{{ asset('storage/ca_profile/' . $ca_detail->comp_logo) }}" alt="CA logo">
                                                @else
                                                    <img class="img-fluid shadow-sm" style="width: 58px; height: 58px; border-radius: 16px; object-fit: cover; border: 2px solid #f1f5f9;" src="{{ asset('public/assets/images/user/ecashbook.png') }}" alt="CA logo">
                                                @endif
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="transform: translate(2px, 2px);"></span>
                                            </div>

                                            <!-- Name, Type & Verified -->
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center gap-1">
                                                    <h5 class="mb-0 fw-bold text-dark text-truncate" title="{{ $ca_detail->comp_name != '' ? $ca_detail->comp_name : $ca_detail->name }}" style="font-size: 1.08rem;">
                                                        {{ $ca_detail->comp_name != "" ? $ca_detail->comp_name : $ca_detail->name }}
                                                    </h5>
                                                    @if($ca_detail->isCaActive == 1)
                                                        <i class="ti ti-rosette-discount-check-filled text-primary fs-5 flex-shrink-0" title="Verified Professional"></i>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light-primary text-primary px-2 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                                        <i class="ti ti-certificate me-1"></i> CA / Accounting Firm
                                                    </span>
                                                    @if(!empty($ca_detail->ca_city))
                                                        <span class="text-muted small d-inline-flex align-items-center" style="font-size: 0.76rem;">
                                                            <i class="ti ti-map-pin text-danger me-1"></i> {{ $ca_detail->ca_city }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3-Box Stats Matrix -->
                                        <div class="row g-2 mb-3 text-center">
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #f8fafc; border: 1px solid #edf2f7;">
                                                    <span class="fw-bold fs-5 text-dark lh-1 mb-1">{{ $ca_detail->total_no_client ?? 0 }}</span>
                                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Assigned</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #f8fafc; border: 1px solid #edf2f7;">
                                                    <div class="mb-1">
                                                        @if($ca_detail->isCaActive == 1)
                                                            <span class="badge bg-light-success text-success rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                                                                <i class="ti ti-check"></i> Verified
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light-secondary text-muted rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                                                                Pending
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Verification</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #f8fafc; border: 1px solid #edf2f7;">
                                                    <div class="mb-1">
                                                        @if($ca_detail->status == 1)
                                                            <span class="badge bg-light-success text-success rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                                                                Active
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light-danger text-danger rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                                                                Inactive
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Status</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Core Specializations -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <small class="fw-bold text-muted text-uppercase d-flex align-items-center" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                                    <i class="ti ti-sparkles text-warning me-1"></i> Specializations
                                                </small>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                                @if (!empty($ca_detail->ca_spec))
                                                    @php
                                                        $allSpecs = array_filter(array_map('trim', explode(",", $ca_detail->ca_spec)));
                                                        $initialSpecs = array_slice($allSpecs, 0, 4);
                                                        $extraSpecs = array_slice($allSpecs, 4);
                                                    @endphp
                                                    @foreach ($initialSpecs as $val)
                                                        <span class="badge rounded-pill fw-medium ca-skill-badge"
                                                              style="background-color: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; font-size: 0.75rem; padding: 5px 10px;">
                                                            {{ $val }}
                                                        </span>
                                                    @endforeach

                                                    @if(count($extraSpecs) > 0)
                                                        <span class="collapse ca-extra-skills" id="extraSkills{{ $ca_detail->id }}">
                                                            @foreach ($extraSpecs as $extraVal)
                                                                <span class="badge rounded-pill fw-medium ca-skill-badge me-1 mb-1"
                                                                      style="background-color: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; font-size: 0.75rem; padding: 5px 10px;">
                                                                    {{ $extraVal }}
                                                                </span>
                                                            @endforeach
                                                        </span>
                                                        <a class="badge rounded-pill bg-light text-primary border text-decoration-none fw-semibold toggle-ca-skills-btn mb-1"
                                                           data-bs-toggle="collapse"
                                                           href="#extraSkills{{ $ca_detail->id }}"
                                                           role="button"
                                                           aria-expanded="false"
                                                           aria-controls="extraSkills{{ $ca_detail->id }}"
                                                           data-more-text="+{{ count($extraSpecs) }} more"
                                                           style="font-size: 0.72rem; padding: 5px 8px; cursor: pointer; transition: all 0.2s ease;">
                                                            +{{ count($extraSpecs) }} more
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="text-muted small fst-italic" style="font-size: 0.8rem;">General Accounting & Compliance</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Footer: Full Address & Action Button -->
                                        <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2 mt-auto">
                                            <div class="d-flex align-items-center gap-1 text-muted text-truncate" style="max-width: 62%;">
                                                <i class="ti ti-map-pin text-danger fs-5 flex-shrink-0"></i>
                                                <small class="text-muted text-truncate mb-0" style="font-size: 0.78rem;" title="{{ implode(', ', $parts) }}">
                                                    {{ !empty($parts) ? implode(', ', $parts) : 'Location not specified' }}
                                                </small>
                                            </div>

                                            <div class="flex-shrink-0">
                                                @if($ca_detail->ca_assign_status == 0)
                                                    <button class="btn btn-primary btn-sm rounded-pill px-3 py-2 shadow-sm assignCABtn d-inline-flex align-items-center fw-semibold"
                                                            data-id="{{ $ca_detail->id }}"
                                                            data-status="{{ $ca_detail->ca_assign_status }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop{{ $ca_detail->id }}"
                                                            style="background: linear-gradient(135deg, #008CAD 0%, #026d87 100%); border: none; font-size: 0.82rem;">
                                                        <i class="ti ti-user-plus me-1"></i> Assign
                                                    </button>
                                                @else
                                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 shadow-sm assignCABtn d-inline-flex align-items-center fw-semibold"
                                                            data-id="{{ $ca_detail->id }}"
                                                            data-status="{{ $ca_detail->ca_assign_status }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop{{ $ca_detail->id }}"
                                                            style="font-size: 0.82rem;">
                                                        <i class="ti ti-user-x me-1"></i> Undo-Assign
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal --}}
                            <div class="modal fade" id="staticBackdrop{{ $ca_detail->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Invite {{ $ca_detail->comp_name != "" ? $ca_detail->comp_name : $ca_detail->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="message-container"></div>
                                            <form>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold" for="exampleFormControlTextarea1">Personalized Message</label>
                                                    <textarea class="form-control rounded-3" name="msg" id="msg" rows="3" placeholder="Write a short message or note for the CA..."></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary rounded-pill px-4 assignCASendBtn" style="background-color: #008CAD; border-color: #008CAD;">
                                                <i class="ti ti-send me-1"></i> Send Invitation
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        <div class="message-container"></div>
                        @else
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                                <div class="card-body">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light-primary text-primary mb-3" style="width: 70px; height: 70px;">
                                        <i class="ti ti-user-search fs-2"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">No CA Professionals Found</h5>
                                    <p class="text-muted mb-0">Try changing your search terms or filter criteria.</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>


<!-- Side Panel Overlay -->
<div class="ca-side-panel-overlay" id="sidePanelOverlay"></div>

<!-- Add CA Side Panel -->
<div class="ca-side-panel" id="addCAPanel">
    <div class="ca-side-panel-content">
        <div class="ca-side-panel-header" style="background: white; border-bottom: 1px solid #e5e7eb;">
            <button type="button" class="ca-close-btn" id="closeSidePanel">
                <i class="fas fa-times text-dark"></i>
            </button>
            <div class="text-center py-4">
                <div class="mb-3">
                    <img src="{{asset('assets/images/logo-small.png')}}" alt="e-Cashbook" class="img-fluid" style="max-height: 60px;">
                </div>
            </div>
        </div>
        <div class="ca-side-panel-body p-4">
            <div class="add-ca-message-container"></div>
            <form id="addCAForm">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">CA / Professional Accountant Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ca_name" id="ca_name" placeholder="Enter CA/Professional Accountant Name" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ca_phone" id="ca_phone" placeholder="Enter Contact Number" required maxlength="10" pattern="\d{10}" title="Please enter a 10-digit mobile number" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-muted">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="ca_email" id="ca_email"
                            placeholder="Enter Email Address" required>
                        <small id="email_check_msg" class="text-danger mt-1" style="display: none;"></small>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ca_address1" id="ca_address1" placeholder="Enter Address Line 1" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label text-muted">Address Line 2</label>
                        <input type="text" class="form-control" name="ca_address2" id="ca_address2" placeholder="Enter Address Line 2">
                    </div>

                    <div class="col-4 mb-3">
                        <label class="form-label text-muted">State <span class="text-danger">*</span></label>
                        <select name="ca_state" id="ca_state" class="form-select" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 mb-3">
                        <label class="form-label text-muted">City <span class="text-danger">*</span></label>
                        <select name="ca_city" id="ca_city" class="form-select" required>
                            <option value="">Select City</option>
                        </select>

                    </div>
                    <div class="col-4 mb-4">
                        <label class="form-label text-muted">Pin Code <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="ca_pincode" id="ca_pincode" placeholder="Enter Pin Code" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-lg w-100 fw-bold" style="background: #008CAD; color: white; border: none; padding: 12px; border-radius: 8px;">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
	//Show experts more/less in Filter
	document.addEventListener("DOMContentLoaded", function () {

        const moreExperts = document.getElementById('moreExperts');
        const toggleBtn = document.getElementById('toggleExpertBtn');

        if (moreExperts && toggleBtn) {
            moreExperts.addEventListener('shown.bs.collapse', function () {
                toggleBtn.innerHTML = '- Show Less';
            });

            moreExperts.addEventListener('hidden.bs.collapse', function () {
                toggleBtn.innerHTML = '+ Show More Services';
            });
        }

        // Toggle CA Card skill badges on click
        $(document).on('click', '.toggle-ca-skills-btn', function (e) {
            e.preventDefault();
            const targetId = $(this).attr('href');
            const $target = $(targetId);
            if ($target.length) {
                $target.collapse('toggle');
            }
        });

        $(document).on('shown.bs.collapse', '[id^="extraSkills"]', function () {
            const collapseId = this.id;
            const btn = $(`a[href="#${collapseId}"]`);
            if (btn.length) {
                btn.text('Show less');
            }
        });

        $(document).on('hidden.bs.collapse', '[id^="extraSkills"]', function () {
            const collapseId = this.id;
            const btn = $(`a[href="#${collapseId}"]`);
            if (btn.length) {
                const moreText = btn.data('more-text') || '+ more';
                btn.text(moreText);
            }
        });
    });
	
    document.addEventListener("DOMContentLoaded", function() {
        $('.assignCABtn').click(function () {
            var ca_id = $(this).data('id');
            var ca_assign_status = $(this).data('status');
            const that = this;

            // Clear message field and message container
            $("#staticBackdrop" + ca_id + " .message-container").html('');
            $("#staticBackdrop" + ca_id + " textarea[name='msg']").val('');

            // Prevent multiple bindings
            $("#staticBackdrop" + ca_id + " .assignCASendBtn")
                .off('click')
                .on('click', function () {
                    var msg = $("#staticBackdrop" + ca_id + " textarea[name='msg']").val();
                    assign_ca(ca_id, ca_assign_status, msg, that);
                });
        });

        // Handle Side Panel Open/Close
        $('#openSidePanel').on('click', function(e) {
            e.preventDefault();
            $('#sidePanelOverlay').addClass('active');
            $('#addCAPanel').addClass('active');
            $('body').addClass('overflow-hidden');
        });

        $('#closeSidePanel, #sidePanelOverlay').on('click', function(e) {
            e.preventDefault();
            $('#sidePanelOverlay').removeClass('active');
            $('#addCAPanel').removeClass('active');
            $('body').removeClass('overflow-hidden');
        });

        // Prevent closing when clicking inside the panel
        $('#addCAPanel').on('click', function(e) {
            e.stopPropagation();
        });

        // Handle Add CA Form submission
        $('#addCAForm').on('submit', function(e) {
            e.preventDefault();

            let formData = {
                'ca_name': $('#ca_name').val(),
                'ca_email': $('#ca_email').val(),
                'ca_phone': $('#ca_phone').val(),
                'ca_address1': $('#ca_address1').val(),
                'ca_address2': $('#ca_address2').val(),
                'ca_city': $('#ca_city').val(),
                'ca_state': $('#ca_state').val(),
                'ca_pincode': $('#ca_pincode').val()
            };

            // Disable the submit button and update text
            const $submitBtn = $(this).find('button[type="submit"]');
            $submitBtn.prop('disabled', true).text('Submitting...');

            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });

            let base_url = $('#base_url').val(); // make sure base_url is defined in a hidden input or JS var

            $.ajax({

                url: '/add_ca_request',
                type: 'POST',
                data: formData,
                success: function(response) {
                    $submitBtn.prop('disabled', false).text('SUBMIT');

                    if (response.status === 'success') {
                        showToast(response.message, 'success');
                        $('#addCAForm')[0].reset();

                        // Close panel after delay (optional)
                        setTimeout(function() {
                            $('#sidePanelOverlay').removeClass('active');
                            $('#addCAPanel').removeClass('active');
                            $('body').removeClass('overflow-hidden');
                        }, 2000);
                    } else {
                        showToast(response.message || "Something went wrong.", 'error');
                    }
                },
                error: function(xhr) {
                    $submitBtn.prop('disabled', false).text('SUBMIT');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = Object.values(xhr.responseJSON.errors).map(msg => msg[0]).join("<br>");
                        showToast(errors, 'error');
                    } else {
                        showToast("Something went wrong. Please try again.", 'error');
                    }
                }
            });
        });


        function assign_ca(ca_id, ca_assign_status, msg, that) {
            var base_url = $("#base_url").val();
            var btn = $(this);
            btn.prop('disabled', true);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });
            //const that = this;

            if (ca_assign_status == 1) {
                ca_assign_status = 0;
            } else {
                ca_assign_status = 1;
            }

            if (ca_id > 0) {
                $.ajax({
                    url: base_url + '/assign_ca',
                    type: 'POST',
                    data: {
                        'ca_id': ca_id,
                        'ca_assign_status': ca_assign_status,
                        'set_permission': "",
                        'msg': msg,
                    },
                    success: function(response) {

                        if(response.status == "success"){
                            showToast( response.message, "success");
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                        else if(response.status == "error"){
                            showToast(response.message, "error");
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1500);
                        }else{
                            $.each(response, function(idx, obj) {
                                showToast(obj, "error");
                            });
                        }
                    }

                    //----------- Old Response --------
                    // success: function(response) {
                    //     btn.prop('disabled', false);
                    //     if (response.class == "succ") {
                    //         $('#loader').hide();
                    //         if (response.ca_assign_status == 1) {
                    //             //$(el).find('span').text("Un-Assign");
                    //         } else {
                    //             //$(el).find('span').text("Assign");
                    //         }
                    //         //$(el).data('status',response.ca_assign_status);
                    //         $('#staticBackdrop').modal('toggle');
                    //         $(".ecom-content .message-container").html('<div class="' + response.class + '">' + response.message + '</div>');
                    //         window.location.href = response.redirect;

                    //     } else {
                    //         $('#loader').hide();
                    //         $.each(response, function(idx, obj) {
                    //             $("#staticBackdrop .message-container").html('<div class="err">' + obj + '</div>');
                    //         });
                    //     }
                    // }
                });
            }
        }


        //------------- Fetch city ------

        const stateDropdown = document.getElementById('ca_state');
            stateDropdown.addEventListener('change', function() {
                const id = this.value; // Get the selected value
                $('#ca_city').html('<option value="">Loading...</option>');
                if (id) {

                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                    });
                    $.ajax({
                        url: "/getCity?" + id,
                        dataType: "json",
                        //type: "post",
                        data: {
                            id: id
                        },
                        success: function(data) {
                            $("#ca_city").empty();
                            var str = '<option value="">Select City</option>';
                            $.each(data, function(idx, item) {
                                str +=
                                    '<option value="' +
                                    item.id +
                                    '">' +
                                    item.name +
                                    "</option>";
                            });
                            $("#ca_city").html(str);
                        },
                    });
                } else {
                    // alert("No state selected!");
                    showToast("No state selected!", "error");
                }
            });

        //------- Check Email avaiable or not -----

        $('#ca_email').on('input', function () {
            const email = $(this).val();
            const $msg = $('#email_check_msg');
            const $submit = $('#addCAForm button[type="submit"]');
            const base_url = $('#base_url').val(); // Make sure this exists

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                $msg.hide();
                $submit.prop('disabled', true);
                return;
            }

            // Send AJAX request
            $.ajax({
                url: base_url + '/check-email',
                method: 'POST',
                data: { email: email },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.exists) {
                        $msg.text('This email is already registered.').show();
                        $submit.prop('disabled', true);
                    } else {
                        $msg.hide();
                        $submit.prop('disabled', false);
                    }
                },
                error: function () {
                    $msg.text('Something went wrong while checking email.').show();
                    $submit.prop('disabled', true);
                }
            });
        });


        //---- Clear All Function ----------
        document.getElementById('clearFiltersBtn').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('caNameInput').value = '';
            document.getElementById('zipInput').value = '';
            document.querySelectorAll('.expert-checkbox, .rating-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.ca-card').forEach(card => card.style.removeProperty('display'));
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        const caCards = document.querySelectorAll('.ca-card');
        const nameInput = document.getElementById('caNameInput');
        const zipInput = document.getElementById('zipInput');
        const expertAllCheckbox = document.getElementById('expertAll');
        const individualExpertCheckboxes = document.querySelectorAll('.expert-checkbox:not(#expertAll)');

        // Handle "All Services" checkbox toggling all individual checkboxes
        if (expertAllCheckbox) {
            expertAllCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;
                individualExpertCheckboxes.forEach(cb => {
                    cb.checked = isChecked;
                });
                filterCACards();
            });

            // Handle individual checkboxes updating the "All Services" state
            individualExpertCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const allChecked = Array.from(individualExpertCheckboxes).every(item => item.checked);
                    expertAllCheckbox.checked = allChecked;
                    filterCACards();
                });
            });
        }

        function getSelectedExperts() {
            // If "All Services" is checked, we don't need to filter by specific service
            if (expertAllCheckbox && expertAllCheckbox.checked) {
                return [];
            }
            return Array.from(individualExpertCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value.toLowerCase().replace(/\s/g, '')); // remove spaces
        }

        function filterCACards() {
            const nameVal = nameInput.value.toLowerCase().trim();
            const zipVal = zipInput.value.trim();
            const selectedExperts = getSelectedExperts();

            caCards.forEach(card => {
                const cardName = card.dataset.name || "";
                const cardZip = card.dataset.zip || "";
                const cardExpert = card.dataset.expert || "";

                const matchesName = nameVal === "" || cardName.includes(nameVal);
                const matchesZip = zipVal === "" || cardZip.includes(zipVal);
                const matchesExpert = selectedExperts.length === 0 || selectedExperts.some(expert => cardExpert.includes(expert));

                if (matchesName && matchesZip && matchesExpert) {
                    card.style.removeProperty('display');
                } else {
                    card.style.display = "none";
                }
            });
        }

        nameInput.addEventListener("input", filterCACards);
        zipInput.addEventListener("input", filterCACards);
    });

    function startAssignCATour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Assign CA / Accountant Guide',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ph-duotone ph-user-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Connect with professional Chartered Accountants or Accounting Professionals to manage your accounting operations.</p></div>'
                    },
                    {
                        element: '.ca-highlight-section',
                        title: 'Add Existing Professional',
                        intro: 'Click "clicking here" to open the drawer panel. Enter their name, mobile, email, and location details to add your existing CA.'
                    },
                    {
                        element: '#ecom-filter',
                        title: 'Search & Filters',
                        intro: 'Filter CA firms by name, pincode/zip code, or select specific categories (e.g., GST Registration, TDS Filing) to find the right professional.'
                    },
                    {
                        element: '.ecom-content',
                        title: 'CA / Accountant Directory',
                        intro: 'View details of available CA firms: their total assigned clients, verification status, active status, areas of expertise, and office address.'
                    },
                    {
                        element: '.assignCABtn',
                        title: 'Send Assignment Invitation',
                        intro: 'Click here to send an invitation request to assign your bookkeeping, taxation, or payroll modules to the CA.'
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

        if (typeof introJs === 'function') {
            launch();
        } else {
            // CSS
            if (!document.getElementById('introjs-cdn-css')) {
                let css = document.createElement('link');
                css.id = 'introjs-cdn-css';
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/introjs.min.css';
                document.head.appendChild(css);
            }

            // JS
            let js = document.createElement('script');
            js.src = 'https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js';
            js.onload = function() {
                launch();
            };
            document.body.appendChild(js);
        }
    }

    function bindAssignCATour() {
        const btn = document.getElementById('start-assign-ca-tour');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                startAssignCATour();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindAssignCATour);
    } else {
        bindAssignCATour();
    }
</script>
