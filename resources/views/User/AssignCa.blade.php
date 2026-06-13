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
                <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="offcanvas_mail_filter">
                    <div class="offcanvas-body p-0 sticky-xxl-top">
                        <div id="ecom-filter" class="show collapse collapse-horizontal">
                            <div class="ecom-filter">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5>Filter</h5>
                                        <a
                                            href="#"
                                            class="avtar avtar-s btn-link-danger btn-pc-default"
                                            data-bs-dismiss="offcanvas"
                                            data-bs-target="#offcanvas_mail_filter">
                                            <i class="ti ti-x f-20"></i>
                                        </a>
                                    </div>
                                    <div class="scroll-block">
                                        <div class="card-body">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">CA/ Accountant Name</label>
                                                    <input type="text" class="form-control" id="caNameInput" placeholder="Enter CA Name">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Serach by Zip Code</label>
                                                    <input type="number" class="form-control" id="zipInput" placeholder="Enter Zip Code">
                                                </div>
                                            </div>
                                            <ul class="list-group list-group-flush">

                                                <!-- Expert Filter -->
                                                <li class="list-group-item border-0 px-0 py-2">
                                                    <a class="btn border-0 px-0 text-start w-100 pb-0 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#filtercollapse2">
                                                        <span><i class="bi bi-person-badge me-1"></i>Choose Service</span>
                                                        <i class="ti ti-chevron-down"></i>
                                                    </a>
                                                    <div class="collapse show" id="filtercollapse2">
                                                        <div class="mt-2">
                                                            <div class="form-check my-2">
                                                                <input class="form-check-input expert-checkbox" type="checkbox" id="expertAll" value="all">
                                                                <label class="form-check-label" for="expertAll">All</label>
                                                            </div>                                                           
															<!-- Dynamic Expert List -->
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

																	<label class="form-check-label" for="expert{{ $key }}">
																		{{ $expert }}
																	</label>
																</div>

															@endforeach

															@if(count($expertList) > $visibleCount)
																</div>

																<div class="mt-2">
																	<a class="btn btn-sm btn-link text-decoration-none px-0"
																	   data-bs-toggle="collapse"
																	   href="#moreExperts"
																	   role="button"
																	   id="toggleExpertBtn">
																		+ Show More
																	</a>
																</div>
															@endif
                                                        </div>
                                                    </div>
                                                </li>

                                                <!-- Rating Filter -->
                                               {{--- <li class="list-group-item border-0 px-0 py-2">
                                                    <a class="btn border-0 px-0 text-start w-100 pb-0 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#filtercollapse5">
                                                        <span><i class="bi bi-star me-1"></i>Rating</span>
                                                        <i class="ti ti-chevron-down"></i>
                                                    </a>
                                                    <div class="collapse show" id="filtercollapse5">
                                                        <div class="mt-2">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input rating-checkbox" type="checkbox" value="4" id="rating4">
                                                                <label class="form-check-label" for="rating4">4★ & above</label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input rating-checkbox" type="checkbox" value="3" id="rating3">
                                                                <label class="form-check-label" for="rating3">3★ & above</label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input rating-checkbox" type="checkbox" value="2" id="rating2">
                                                                <label class="form-check-label" for="rating2">2★ & above</label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input rating-checkbox" type="checkbox" value="1" id="rating1">
                                                                <label class="form-check-label" for="rating1">1★ & above</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>--}}

                                                <!-- Clear All -->
                                                <li class="list-group-item border-0 px-0 py-2">
                                                    <a href="#" id="clearFiltersBtn" class="btn btn-light-danger w-100"><i class="bi bi-x-circle me-1"></i>Clear All</a>
                                                </li>

                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ecom-content">
                    <div class="message-container"></div>
                    <div class="row" >
                        @if ($ca_details)
                        @foreach ($ca_details as $ca_detail)
                            @if($ca_detail->ca_current_status == 0)

                            @php
                                $expertiseList = strtolower(str_replace(' ', '', $ca_detail->ca_spec ?? ''));

                                $ca_name = strtolower($ca_detail->comp_name != "" ? $ca_detail->comp_name : $ca_detail->name);
                                $ca_zip = $ca_detail->comp_bill_pin ?? '';
                            @endphp

                            <div class="col-md-6 col-xl-6 ca-card"
                                data-name="{{ $ca_name }}"
                                data-zip="{{ $ca_zip }}"
                                data-expert="{{ $expertiseList }}">
                                <div class="card user-card" style="min-width:400px; max-width:100%; width:100%;">
                                    <div class="card-body position-relative">
                                        <div class="chat-avtar d-inline-flex mx-auto">
                                            @if(isset($ca_detail->comp_logo) && $ca_detail->comp_logo != "")
                                                <img class="rounded-circle img-fluid wid-90 img-thumbnail" src="{{ asset('public/uploads/profile/' . $ca_detail->comp_logo) }}" alt="User image">
                                            @else
                                                <img class="rounded-circle img-fluid wid-90 img-thumbnail" src="{{ asset('public/assets/images/user/ecashbook.png') }}" alt="User image">
                                            @endif
                                        </div>

                                        <div class="d-flex flex-wrap gap-2 mt-3">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $ca_detail->comp_name != "" ? $ca_detail->comp_name : $ca_detail->name }}</h6>
                                            </div>
                                            <div class="flex-shrink-0">
                                                @if($ca_detail->ca_assign_status == 0)
                                                    <button class="btn btn-primary btn-md assignCABtn"
                                                            data-id="{{ $ca_detail->id }}"
                                                            data-status="{{ $ca_detail->ca_assign_status }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop{{ $ca_detail->id }}">Assign</button>
                                                @else
                                                    <button class="btn btn-primary btn-md assignCABtn"
                                                            data-id="{{ $ca_detail->id }}"
                                                            data-status="{{ $ca_detail->ca_assign_status }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#staticBackdrop{{ $ca_detail->id }}">Undo-Assign</button>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row g-3 my-3 text-center">
                                            <div class="col-4">
                                                <h5 class="mb-0">{{ $ca_detail->total_no_client ?? 0 }}</h5>
                                                <small class="text-muted">Company Assign</small>
                                            </div>
                                            <div class="col-4 border border-top-0 border-bottom-0">
												<h5 class="mb-0">
													@if($ca_detail->isCaActive == 1)
														<span class="text-success">Yes</span>
													@else
														<span class="text-danger">No</span>
													@endif
												</h5>
												<small class="text-muted">Verified</small>
											</div>

											<div class="col-4">
												<h5 class="mb-0">
													@if($ca_detail->status == 1)
														<span class="text-success">Active</span>
													@else
														<span class="text-danger">Inactive</span>
													@endif
												</h5>
												<small class="text-muted">Platform Status</small>
											</div>
                                        </div>

                                        <div class="saprator my-3">
                                            <span>Experts In</span>
                                        </div>
                                        <div class="text-center">
                                            @if (!empty($ca_detail->ca_spec))
                                                @php
                                                    $specArr = explode(",", $ca_detail->ca_spec);
                                                @endphp
                                                @foreach ($specArr as $val)
                                                    <span class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">{{ $val }}</span>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="saprator my-3">
                                            <span>Address</span>
                                        </div>
                                        @php
                                            $parts = array_filter([
                                                $ca_detail->comp_bill_addone ?? '',
                                                $ca_detail->ca_state ?? '',
                                                $ca_detail->ca_city ?? '',
                                                $ca_detail->comp_bill_pin ?? ''
                                            ]);
                                        @endphp

                                        @if(!empty($parts))
                                            <h6 class="text-center">{{ implode(', ', $parts) }}</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Modal --}}
                            <div class="modal fade" id="staticBackdrop{{ $ca_detail->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="message-container"></div>
                                            <form>
                                                <div class="mb-3">
                                                    <label class="form-label" for="exampleFormControlTextarea1">Write Message</label>
                                                    <textarea class="form-control" name="msg" id="msg" rows="3" placeholder="Enter Message"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary assignCASendBtn">Send Invitation</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        <div class="message-container"></div>
                        @else
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card customer-details-group">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <h6 class="text-center"> No CA found in search location</h6>
                                    </div>
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
                        <button type="submit" class="btn btn-lg w-100 fw-bold" style="background: #6f42c1; color: white; border: none; padding: 12px; border-radius: 8px;">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
  /* 💎 Premium Assign CA Page Style Overrides 💎 */

  /* Filter Card & Scroll Block */
  .ecom-filter .card {
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
    overflow: hidden !important;
  }
  .ecom-filter .card-header {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 16px 20px !important;
  }
  .ecom-filter .card-header h5 {
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    color: #1e293b !important;
  }
  .ecom-filter .scroll-block {
    height: auto !important;
    max-height: none !important;
    overflow: visible !important;
  }
  
  /* Custom scrollbar for Choose Service checklist */
  #filtercollapse2 > div {
    max-height: 250px !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding-right: 5px !important;
  }
  #filtercollapse2 > div::-webkit-scrollbar {
    width: 6px !important;
  }
  #filtercollapse2 > div::-webkit-scrollbar-track {
    background: transparent !important;
  }
  #filtercollapse2 > div::-webkit-scrollbar-thumb {
    background: #cbd5e1 !important;
    border-radius: 10px !important;
  }
  #filtercollapse2 > div::-webkit-scrollbar-thumb:hover {
    background: #94a3b8 !important;
  }

  /* Form inputs in filter */
  .ecom-filter .form-control {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    font-size: 0.85rem !important;
    transition: all 0.2s ease !important;
  }
  .ecom-filter .form-control:focus {
    border-color: #6f42c1 !important;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1) !important;
    outline: none !important;
  }
  .ecom-filter .form-label {
    font-size: 0.78rem !important;
    font-weight: 600 !important;
    color: #475569 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 6px !important;
  }

  /* Checkboxes */
  .ecom-filter .form-check-input {
    border: 1px solid #cbd5e1 !important;
    border-radius: 4px !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
  }
  .ecom-filter .form-check-input:checked {
    background-color: #6f42c1 !important;
    border-color: #6f42c1 !important;
  }
  .ecom-filter .form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.15) !important;
    border-color: #6f42c1 !important;
  }
  .ecom-filter .form-check-label {
    cursor: pointer !important;
    font-size: 0.88rem !important;
    color: #475569 !important;
    user-select: none !important;
  }

  /* Clear All Button */
  #clearFiltersBtn {
    background-color: #fcfaff !important;
    border: 1px solid #e9d5ff !important;
    color: #6f42c1 !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    padding: 10px !important;
    transition: all 0.2s ease !important;
  }
  #clearFiltersBtn:hover {
    background-color: #6f42c1 !important;
    border-color: #6f42c1 !important;
    color: #ffffff !important;
  }

  /* CA Cards Grid */
  .ca-card .card.user-card {
    border: 1px solid #e2e8f0 !important;
    border-radius: 16px !important;
    box-shadow: 0 4px 20px rgba(66, 47, 144, 0.02) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    background-color: #ffffff !important;
    overflow: hidden !important;
  }
  .ca-card .card.user-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 10px 25px rgba(66, 47, 144, 0.06) !important;
    border-color: #ddd6fe !important;
  }
  .ca-card .card-body {
    padding: 24px !important;
  }
  .ca-card .chat-avtar img {
    border: 3px solid #f1f5f9 !important;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
    transition: all 0.3s ease !important;
  }
  .ca-card .card.user-card:hover .chat-avtar img {
    transform: scale(1.05) !important;
    border-color: #e9d5ff !important;
  }
  .ca-card h6 {
    font-weight: 700 !important;
    font-size: 1.1rem !important;
    color: #1e293b !important;
  }

  /* Address styling */
  .ca-card .saprator + h6,
  .ca-card h6.text-center {
    font-size: 0.85rem !important;
    font-weight: 500 !important;
    color: #64748b !important;
    line-height: 1.5 !important;
  }

  /* Separators */
  .ca-card .saprator {
    position: relative !important;
    text-align: center !important;
    margin: 16px 0 !important;
  }
  .ca-card .saprator:before {
    content: "" !important;
    position: absolute !important;
    top: 50% !important;
    left: 0 !important;
    right: 0 !important;
    height: 1px !important;
    background-color: #f1f5f9 !important;
    z-index: 1 !important;
  }
  .ca-card .saprator span {
    position: relative !important;
    background-color: #ffffff !important;
    padding: 0 12px !important;
    font-size: 0.72rem !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    color: #94a3b8 !important;
    z-index: 2 !important;
  }

  /* Stats row grid layout */
  .user-card .row.g-3.my-3 {
    margin-left: 0 !important;
    margin-right: 0 !important;
    display: flex !important;
    gap: 8px !important;
    justify-content: space-between !important;
  }
  .user-card .row.g-3.my-3 > div {
    flex: 1 !important;
    padding: 12px 8px !important;
    background-color: #f8fafc !important;
    border: 1px solid #f1f5f9 !important;
    border-radius: 12px !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
  }
  .user-card .row.g-3.my-3 > div.border {
    border: 1px solid #f1f5f9 !important;
  }
  .user-card .row.g-3.my-3 h5 {
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
  }
  .user-card .row.g-3.my-3 small {
    font-size: 0.65rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    font-weight: 600 !important;
    margin-top: 4px !important;
  }

  /* Status Indicator Badges */
  .user-card .row.g-3.my-3 .text-success {
    background-color: #d1fae5 !important;
    color: #065f46 !important;
    padding: 2px 8px !important;
    border-radius: 20px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    display: inline-block !important;
  }
  .user-card .row.g-3.my-3 .text-danger {
    background-color: #fee2e2 !important;
    color: #991b1b !important;
    padding: 2px 8px !important;
    border-radius: 20px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    display: inline-block !important;
  }

  /* Specialty Badge Overrides */
  .user-card .badge.bg-light-secondary {
    background-color: #f5f3ff !important;
    color: #6d28d9 !important;
    border: 1px solid #ddd6fe !important;
    border-radius: 20px !important;
    font-weight: 500 !important;
    padding: 6px 12px !important;
    font-size: 0.8rem !important;
    transition: all 0.2s ease !important;
  }
  .user-card .badge.bg-light-secondary:hover {
    background-color: #6d28d9 !important;
    color: #ffffff !important;
    border-color: #6d28d9 !important;
  }

  /* Button Overrides */
  .user-card .assignCABtn {
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.85rem !important;
    padding: 8px 16px !important;
    transition: all 0.2s ease !important;
  }
  .user-card .assignCABtn[data-status="0"] {
    background-color: #422f90 !important;
    border-color: #422f90 !important;
    color: #ffffff !important;
  }
  .user-card .assignCABtn[data-status="0"]:hover {
    background-color: #2d1f6a !important;
    border-color: #2d1f6a !important;
  }
  .user-card .assignCABtn[data-status="1"] {
    background-color: #fee2e2 !important;
    border-color: #fecaca !important;
    color: #ef4444 !important;
  }
  .user-card .assignCABtn[data-status="1"]:hover {
    background-color: #ef4444 !important;
    border-color: #ef4444 !important;
    color: #ffffff !important;
  }

  /* Dialogs & Modals styling */
  .modal-content {
    border-radius: 16px !important;
    border: none !important;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important;
  }
  .modal-body {
    padding: 24px !important;
  }
  .modal-body textarea.form-control {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 12px !important;
    font-size: 0.9rem !important;
    transition: all 0.2s ease !important;
  }
  .modal-body textarea.form-control:focus {
    border-color: #6f42c1 !important;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.15) !important;
  }
  .modal-footer {
    border-top: 1px solid #f1f5f9 !important;
    padding: 16px 24px !important;
  }
  .modal-footer .btn {
    padding: 10px 20px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
  }

  /* Add CA Drawer Overrides */
  .ca-side-panel .form-control,
  .ca-side-panel .form-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 10px 12px !important;
    font-size: 14px !important;
    transition: all 0.2s ease !important;
  }
  .ca-side-panel .form-control:focus,
  .ca-side-panel .form-select:focus {
    border-color: #6f42c1 !important;
    box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.15) !important;
    outline: none !important;
  }
  .ca-side-panel button[type="submit"] {
    background: #422f90 !important;
    color: white !important;
    border-radius: 8px !important;
    padding: 12px !important;
    transition: all 0.2s ease !important;
  }
  .ca-side-panel button[type="submit"]:hover {
    background: #2d1f6a !important;
  }
</style>

@endsection

<script>
	//Show experts more/less
	document.addEventListener("DOMContentLoaded", function () {

        const moreExperts = document.getElementById('moreExperts');
        const toggleBtn = document.getElementById('toggleExpertBtn');

        if (moreExperts && toggleBtn) {
            moreExperts.addEventListener('shown.bs.collapse', function () {
                toggleBtn.innerHTML = '- Show Less';
            });

            moreExperts.addEventListener('hidden.bs.collapse', function () {
                toggleBtn.innerHTML = '+ Show More';
            });
        }
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
        const expertCheckboxes = document.querySelectorAll('.expert-checkbox');

        function getSelectedExperts() {
            return Array.from(expertCheckboxes)
                .filter(cb => cb.checked && cb.value !== "all")
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
        expertCheckboxes.forEach(cb => cb.addEventListener("change", filterCACards));
    });

    function startAssignCATour() {
        function launch() {
            introJs().setOptions({
                steps: [
                    {
                        title: 'Assign CA / Accountant Guide',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ph-duotone ph-user-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Connect with professional Chartered Accountants or Accounting Professionals to manage your accounting operations.</p></div>'
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
