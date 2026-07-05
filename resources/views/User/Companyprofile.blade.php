@extends('App.Layout')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@section('container')


    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Company Profile</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-profile-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                        </a>
                    </div>
					<div class="col-md-4">
						<div class="page-header-title">
							<h2 class="mb-0">Company Profile</h2>
						</div>
					</div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->


        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ sample-page ] start -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-lg-5 col-xxl-3">
                        <div class="card overflow-hidden">
                            <div class="card-body position-relative">
                                <div class="text-center mt-3">
                                    <div class="col-sm-auto text-center">

                                        <div class="col-sm-auto text-center">
                                            <div class="position-relative me-3 d-inline-flex">
                                                <div class="position-absolute top-50 start-100 translate-middle">
                                                    <!-- File Upload Button with Pencil Icon -->
                                                    <label for="fileUpload" class="btn btn-sm btn-primary btn-icon">
                                                        <i class="ti ti-pencil"></i>
                                                    </label>
                                                    <input type="file" id="fileUpload" class="d-none" name="fileUpload"
                                                        accept=".jpg, .jpeg, .png">
                                                </div>

                                                <!-- Display Company Logo or Default Image -->
                                                <img src="{{ optional($compDetails)->comp_logo ? asset('storage/profile/' . optional($compDetails)->comp_logo) : asset('storage/profile/e-cashbook.png') }}"
                                                    alt="user-image" id="uploadedImage"
                                                    class="wid-150 rounded img-fluid ms-2">
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="mt-3">{{ isset($compDetails->comp_name) ? $compDetails->comp_name : '' }}
                                    </h4>
                                </div>
                            </div>
                            <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0"
                                id="company-profile-set-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link list-group-item list-group-item-action active" id="company-details-tab"
                                    data-bs-toggle="pill" href="#company-details" role="tab"
                                    aria-controls="company-details" aria-selected="true">
                                    <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Company Details</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="business-details-tab"
                                    data-bs-toggle="pill" href="#business-details" role="tab"
                                    aria-controls="business-details" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-database m-r-10"></i>Business Details</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="contact-details-tab"
                                    data-bs-toggle="pill" href="#contact-details" role="tab"
                                    aria-controls="contact-details" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-phone m-r-10"></i>Contact Details</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="director-details-tab"
                                    data-bs-toggle="pill" href="#director-details" role="tab"
                                    aria-controls="director-details" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-user-list m-r-10"></i>Directors / Owner</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="bank-details-tab"
                                    data-bs-toggle="pill" href="#bank-details" role="tab" aria-controls="bank-details"
                                    aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-wallet m-r-10"></i>Bank Details</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="attachment-tab"
                                    data-bs-toggle="pill" href="#attachment" role="tab" aria-controls="attachment"
                                    aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-arrow-square-up m-r-10"></i>Attachments</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="assign-ca-tab"
                                    data-bs-toggle="pill" href="#assign-ca" role="tab" aria-controls="assign-ca"
                                    aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-user-plus m-r-10"></i>Assign CA Details</span>
                                </a>
                                
                                <a class="nav-link list-group-item list-group-item-action" id="holidays-tab"
                                    data-bs-toggle="pill" href="#holidays" role="tab" aria-controls="holidays"
                                    aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-calendar m-r-10"></i>Company Holidays</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="schedule-tab"
                                    data-bs-toggle="pill" href="#schedule" role="tab" aria-controls="schedule"
                                    aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-clock m-r-10"></i>Day Schedule</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="locations-tab"
                                    data-bs-toggle="pill" href="#locations" role="tab" aria-controls="locations"
                                    aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-map-pin m-r-10"></i>Company Locations</span>
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-xxl-9">
                        <div class="tab-content" id="company-profile-set-tabContent">
                            <div class="tab-pane fade show active" id="company-details" role="tabpanel" aria-labelledby="company-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">This is a CIP platform. Accurate organization details ensure precise compliance, tax management, and financial reporting.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmcompdet" id="frmcompdet">
                                    @csrf
                                    <div class="card">
                                        <div class="message-container"></div>
                                        <div class="card-header">
                                            <h5>Company Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Company GST Registered <span
                                                                class="text-danger">*</span> <span id="gst_type_display"
                                                                class="badge bg-info mt-2"></span></label>
                                                        <select class="form-select" name="gst_reg" id="gst_reg"
                                                            required>
                                                            <option value="">Select</option>
                                                            <option value="Yes" <?php echo isset($compDetails->gst_reg) && $compDetails->gst_reg == 'Yes' ? 'selected' : ''; ?>>Yes
                                                            </option>
                                                            <option value="No" <?php echo isset($compDetails->gst_reg) && $compDetails->gst_reg == 'No' ? 'selected' : ''; ?>>No
                                                            </option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-sm-4" id="gst_reg_no" style="display: none">
                                                    <label class="form-label">GST Number <span
                                                            class="text-danger">*</span></label>
                                                    <div class="mb-3">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="gst_no"
                                                                id="gst_no" style="text-transform:uppercase;"
                                                                placeholder="Enter GST Number"
                                                                value="{{ isset($compDetails->gst_no) ? $compDetails->gst_no : '' }}">

                                                            <button class="btn btn-primary" type="button"
                                                                id="get_gst_btn"><i
                                                                    class="ti ti-cloud-download align-middle"></i> Get
                                                                Details</button>
                                                        </div>
                                                        <span id="gstNoError" class="text-danger"
                                                            style="display:none;">GST
                                                            Number is required</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" id="gst_reg_tran" style="display: none">
                                                    <div class="mb-3">
                                                        <label class="form-label">GST Transaction Type <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control error" name="comp_tran_type"
                                                            id="comp_tran_type">
                                                            <option label="select"></option>
                                                            <option value="Regular" <?php echo isset($compDetails->comp_tran_type) && $compDetails->comp_tran_type == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                                                            <option value="QRMP" <?php echo isset($compDetails->comp_tran_type) && $compDetails->comp_tran_type == 'QRMP' ? 'selected' : ''; ?>>QRMP</option>
                                                            <option value="Composite" <?php echo isset($compDetails->comp_tran_type) && $compDetails->comp_tran_type == 'Composite' ? 'selected' : ''; ?>>Composite
                                                            </option>
                                                        </select>

                                                        <span id="tranTypeError" class="text-danger"
                                                            style="display:none;">GST Transaction Type is required</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Company Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" required
                                                            name="comp_name" id="comp_name"
                                                            value="{{ isset($compDetails->comp_name) ? $compDetails->comp_name : '' }}"
                                                            placeholder="Enter Company Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">PAN Number <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" required
                                                            name="comp_pan_no" id="comp_pan_no"
                                                            style="text-transform:uppercase;"
                                                            value="{{ isset($compDetails->comp_pan_no) ? $compDetails->comp_pan_no : '' }}"
                                                            placeholder="Enter PAN Number">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">TAN Number</label>
                                                        <input type="text" class="form-control" name="comp_tan"
                                                            id="comp_tan"
                                                            value="{{ isset($compDetails->comp_tan) ? $compDetails->comp_tan : '' }}"
                                                            placeholder="Enter TAN Number">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">EPF Employer Registration No</label>
                                                        <input type="text" id="comp_epf" name="comp_epf"
                                                            class="form-control"
                                                            value="{{ isset($compDetails->comp_epf) ? $compDetails->comp_epf : '' }}"
                                                            placeholder="Enter EPF Registration Number">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">ESIC Employer Registration</label>
                                                        <input type="text" id="comp_esic" class="form-control"
                                                            name="comp_esic"
                                                            value="{{ isset($compDetails->comp_esic) ? $compDetails->comp_esic : '' }}"
                                                            placeholder="Enter ESIC Registration Number">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">P-Tax Enrolment Certificate No</label>
                                                        <input type="text" id="comp_ptax_cert" name="comp_ptax_cert"
                                                            class="form-control"
                                                            value="{{ isset($compDetails->comp_ptax_cert) ? $compDetails->comp_ptax_cert : '' }}"
                                                            placeholder="Enter P-Tax Enrolment Certificate No">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">P-Tax Registration Number </label>
                                                        <input type="text" id="comp_ptax" name="comp_ptax"
                                                            class="form-control"
                                                            value="{{ isset($compDetails->comp_ptax) ? $compDetails->comp_ptax : '' }}"
                                                            placeholder="Enter P-Tax Registration Number">
                                                    </div>
                                                </div>

                                                {{-- <div class="col-lg-6 mb-4">
                                                    <label class="form-label">Set Basic Salary Percentage <span class="text-danger">*</span></label>
                                                    <input type="number" required class="form-control" name="basic_percentage" value="{{ isset($compDetails->basic_percentage)?$compDetails->basic_percentage:""}}" id="basic_percentage" placeholder="Enter percentage">
                                                    
                                                </div> --}}
                                                {{-- <div class="col-lg-6 mb-4">
                                                    <label class="form-label">Set Basic Salary Percentage <span class="text-danger">*</span></label>
                                                    <input type="number" required class="form-control" name="basic_percentage" value="{{ isset($compDetails->basic_percentage)?$compDetails->basic_percentage:""}}" id="basic_percentage" placeholder="Enter percentage (40-60%)" min="40" max="60" step="1" oninput="this.value = Math.max(40, Math.min(60, this.value))">
                                                    <small class="form-text text-muted">Enter percentage between 40% to 60%</small>
                                                </div> --}}

                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Company Type <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="select form-select company-type-dropdown" name="comp_type" id="comp_type" required>
                                                                <option value="" label="select"></option>
                                                                <option value="Proprietorship" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'Proprietorship' ? 'selected' : ''; ?>>
                                                                    Proprietorship</option>
                                                                <option value="Partnership" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'Partnership' ? 'selected' : ''; ?>>Partnership
                                                                </option>
                                                                <option value="One person Company (OPC)"
                                                                    <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'One person Company (OPC)' ? 'selected' : ''; ?>>One person Company (OPC)</option>
                                                                <option value="LLP Company" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'LLP Company' ? 'selected' : ''; ?>>LLP Company
                                                                </option>
                                                                <option value="PVT Ltd Company" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'PVT Ltd Company' ? 'selected' : ''; ?>>PVT Ltd
                                                                    Company</option>
                                                                <option value="LTD Company" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'LTD Company' ? 'selected' : ''; ?>>LTD
                                                                    Company</option>
                                                                <option value="Section-8 Company" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'Section-8 Company' ? 'selected' : '';
                                                                ?>>
                                                                    Section-8 Company</option>
                                                                <option value="Society/Trust" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'Society/Trust' ? 'selected' : ''; ?>>
                                                                    Society/Trust</option>
                                                                <option value="Other" <?php echo isset($compDetails->comp_type) && $compDetails->comp_type == 'Other' ? 'selected' : '';
                                                                ?>>Other</option>
                                                            </select>
															<small id="propNote" class="mt-1 d-block text-danger">
																Note: If you select <strong>Proprietorship</strong>, you can add multiple companies under the same user.
															</small>
                                                        </div>
                                                    </div>

                                                    <div id="comp_type_other" class="col-sm-4 company-type-input"
                                                        style="display: none;">
                                                        <div class="mb-3">
                                                            <label class="form-label">Enter Custom Company Type <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="other_comp"
                                                                id="other_comp"
                                                                value="{{ isset($compDetails->other_comp_type) ? $compDetails->other_comp_type : '' }}"
                                                                placeholder="Enter Company Type">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4" id="company_reg_div" style="display: none;">
                                                        <div class="mb-3">
                                                            <label class="form-label" id="company_reg_label">CIN <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="cin"
                                                                id="cin"
                                                                value="{{ isset($compDetails->cin) ? $compDetails->cin : '' }}"
                                                                placeholder="Enter CIN Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4" id="inc_date_div" style="display: none;">
                                                        <div class="mb-3">
                                                            <label class="form-label" id="inc_date_label">Incorporation Date <span
                                                                        class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="inc_date"
                                                                id="inc_date"
                                                                value="{{ isset($compDetails->inc_date) ? $compDetails->inc_date : '' }}"
                                                                placeholder="Enter Incorporation Date">
                                                        </div>
                                                    </div>
                                                </div>

                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Udyam Registration Number (URN)</label>
                                                    <input type="text" class="form-control" name="udyam_registration_no"
                                                        id="udyam_registration_no"
                                                        value="{{ isset($compDetails->udyam_reg_no) ? $compDetails->udyam_reg_no : '' }}"
                                                        placeholder="Enter Udyam Registration Number">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Trade License Number</label>
                                                    <input type="text" class="form-control" name="trade_license_no"
                                                        id="trade_license_no"
                                                        value="{{ isset($compDetails->trade_license_no) ? $compDetails->trade_license_no : '' }}"
                                                        placeholder="Enter Trade License Number">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Shop & Establishment Reg No</label>
                                                    <input type="text" class="form-control" name="shop_establishment_no"
                                                        id="shop_establishment_no"
                                                        value="{{ isset($compDetails->shop_establishment_no) ? $compDetails->shop_establishment_no : '' }}"
                                                        placeholder="Enter Shop & Establishment Reg No">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">FEMA/IEC Reg No</label>
                                                    <input type="text" class="form-control" name="fema_iec_no"
                                                        id="fema_iec_no"
                                                        value="{{ isset($compDetails->fema_iec_no) ? $compDetails->fema_iec_no : '' }}"
                                                        placeholder="Enter FEMA/IEC Reg No">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">State Excise Reg.No</label>
                                                    <input type="text" class="form-control" name="state_excise_no"
                                                        id="state_excise_no"
                                                        value="{{ isset($compDetails->state_excise_no) ? $compDetails->state_excise_no : '' }}"
                                                        placeholder="Enter State Excise Reg.No">
                                                </div>
                                            </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="card" id="billing-address-1">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5>Billing Address</h5>
                                                    <div class="btn btn-primary" onclick="addBillingAddress()">Add New
                                                        Billing Address</div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="bill_address_view"
                                                            style="display: none">
                                                            <div class="mb-3">
                                                                <label class="form-label">GST Number</label>
                                                                {{-- <input type="text" id="comp_bill_gst_no"
                                                                class="form-control"
                                                                value="{{ isset($compDetails->comp_bill_gst_no)?$compDetails->comp_bill_gst_no:""}}"
                                                                placeholder="Enter GST Number"> --}}
                                                                <input type="text" id="comp_bill_gst_no"
                                                                    class="form-control" placeholder="Enter GST Number"
                                                                    value="{{ isset($compDetails->comp_bill_gst_no) ? $compDetails->comp_bill_gst_no : '' }}"
                                                                    oninput="this.nextElementSibling.style.display = (/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/.test(this.value.toUpperCase()) || this.value === '') ? 'none' : 'block'; this.classList.toggle('is-invalid', !(/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/.test(this.value.toUpperCase())) && this.value !== '')">
                                                                <small class="text-danger" style="display: none;">Invalid
                                                                    GST Number format</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Contact Person Name <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" id="comp_bill_name" required
                                                                    class="form-control"
                                                                    placeholder="Enter Contact Person Name"
                                                                    value="{{ isset($compDetails->comp_bill_name) ? $compDetails->comp_bill_name : '' }}">
                                                                {{-- <input type="text" id="billing-contact-name"
                                                                class="form-control"
                                                                placeholder="Enter Contact Person Name"> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Address Line 1 <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="text" name="comp_bill_addone"
                                                                    id="comp_bill_addone" required
                                                                    value="{{ isset($compDetails->comp_bill_addone) ? $compDetails->comp_bill_addone : '' }}"
                                                                    class="form-control"
                                                                    placeholder="Enter Address Line 1">
                                                                {{-- <input type="text" id="billing-address1"
                                                                class="form-control" placeholder="Enter Address Line 1">
                                                            --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Address Line 2 </label>
                                                                <input type="text" name="comp_bill_addtwo"
                                                                    id="comp_bill_addtwo"
                                                                    value="{{ isset($compDetails->comp_bill_addtwo) ? $compDetails->comp_bill_addtwo : '' }}"
                                                                    class="form-control"
                                                                    placeholder="Enter Address Line 2">
                                                                {{-- <input type="text" id="billing-address2"
                                                                class="form-control" placeholder="Enter Address Line 2">
                                                            --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-0">
                                                                <label class="form-label">State <span class="text-danger">*</span></label>
                                                                {{-- <input type="text" id="billing-state"
                                                                class="form-control" placeholder="Enter State"> --}}
                                                                <select class="form-control select-style"
                                                                    name="comp_bill_state" id="state" required>
                                                                    <option value="">Select State</option>
                                                                    @foreach ($states as $k => $state)
                                                                        <option value="{{ $state->id }}"
                                                                            <?php echo @($state->id == $compDetails->comp_bill_state) ? 'selected' : '';
                                                                            ?>>{{ $state->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-0">
                                                                <label class="form-label">City <span
                                                                        class="text-danger">*</span></label>
                                                                {{-- <input type="text" id="billing-city"
                                                                class="form-control" placeholder="Enter City"> --}}
                                                                <select class="form-control" name="city_id"
                                                                    id="billing-city" required>
                                                                    <option value="">Select City</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-0">
                                                                <label class="form-label">Zip Code <span
                                                                        class="text-danger">*</span></label>
                                                                {{-- <input type="text" id="billing-zip"
                                                                class="form-control" placeholder="Enter Zip Code"> --}}
                                                                <input type="number" name="comp_bill_pin"
                                                                    id="comp_bill_pin" required
                                                                    value="{{ isset($compDetails->comp_bill_pin) ? $compDetails->comp_bill_pin : '' }}"
                                                                    class="form-control" placeholder="Enter Zip Code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5>Shipping Address</h5>
                                                    <div class="btn btn-primary" id="sameAsBilling">Same as Billing
                                                        Address
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="ship_address_view"
                                                            style="display: none">
                                                            <div class="mb-3">
                                                                <label class="form-label">GST Number</label>
                                                                <input type="text" name="comp_ship_gst_no"
                                                                    id="comp_ship_gst_no"
                                                                    value="{{ isset($compDetails->comp_ship_gst_no) ? $compDetails->comp_ship_gst_no : '' }}"
                                                                    class="form-control" placeholder="Enter GST Number"
                                                                    oninput="this.nextElementSibling.style.display = (/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/.test(this.value.toUpperCase()) || this.value === '') ? 'none' : 'block'; this.classList.toggle('is-invalid', !(/^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}Z[A-Z\d]{1}$/.test(this.value.toUpperCase())) && this.value !== '')">
                                                                <small class="text-danger" style="display: none;">Invalid
                                                                    GST Number format</small>
                                                                {{-- <input type="text" name="comp_ship_gst_no"
                                                                id="comp_ship_gst_no"
                                                                value="{{ isset($compDetails->comp_ship_gst_no)?$compDetails->comp_ship_gst_no:""}}"
                                                                class="form-control" placeholder="Enter GST Number">
                                                            --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Contact Person Name </label>
                                                                {{-- <input type="text" id="shipping-contact-name"
                                                                class="form-control"
                                                                placeholder="Enter Contact Person Name"> --}}
                                                                <input type="text" name="comp_ship_name"
                                                                    id="comp_ship_name" 
                                                                    value="{{ isset($compDetails->comp_ship_name) ? $compDetails->comp_ship_name : '' }}"
                                                                    class="form-control"
                                                                    placeholder="Enter Contact Person Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Address Line 1</label>
                                                                {{-- <input type="text" id="shipping-address1"
                                                                class="form-control" placeholder="Enter Address Line 1">
                                                            --}}
                                                                <input type="text" name="comp_ship_addone"
                                                                    id="comp_ship_addone"
                                                                    value="{{ isset($compDetails->comp_ship_addone) ? $compDetails->comp_ship_addone : '' }}"
                                                                    class="form-control"
                                                                    placeholder="Enter Address Line 1">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Address Line 2</label>
                                                                {{-- <input type="text" id="shipping-address2"
                                                                class="form-control" placeholder="Enter Address Line 2">
                                                            --}}
                                                                <input type="text" name="comp_ship_addtwo"
                                                                    id="comp_ship_addtwo"
                                                                    value="{{ isset($compDetails->comp_ship_addtwo) ? $compDetails->comp_ship_addtwo : '' }}"
                                                                    class="form-control"
                                                                    placeholder="Enter Address Line 2">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-0">
                                                                <label class="form-label">State</label>
                                                                {{-- <input type="text" id="shipping-state"
                                                                class="form-control" placeholder="Enter State"> --}}
                                                                <select class="form-control select-style"
                                                                    name="shipping-state" id="shipping-state">
                                                                    <option value="">Select State</option>
                                                                    @foreach ($states as $k => $state)
                                                                        <option value="{{ $state->id }}"
                                                                            <?php echo @($state->id == $compDetails->comp_bill_state) ? 'selected' : '';
                                                                            ?>>{{ $state->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-0">
                                                                <label class="form-label">City</label>
                                                                {{-- <input type="text" id="shipping-city"
                                                                class="form-control" placeholder="Enter City"> --}}
                                                                <select class="form-control" name="city_id"
                                                                    id="shipping-city">
                                                                    <option value="">Select City</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="mb-0">
                                                                <label class="form-label">Zip Code</label>
                                                                <input type="number" name="comp_ship_pin"
                                                                    id="comp_ship_pin"
                                                                    value="{{ isset($compDetails->comp_ship_pin) ? $compDetails->comp_ship_pin : '' }}"
                                                                    class="form-control" placeholder="Enter Zip Code">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="billing-address-container">

                                    </div>
                                    <div class="text-end btn-page">
                                        {{-- <div class="btn btn-outline-secondary">Cancel</div> --}}
                                        <a href="javascript:void(0);" id="cancel_compDetBtn"
                                            class="btn customer-btn-cancel">Cancel</a>
                                        {{-- <div class="btn btn-primary">Save Changes</div> --}}
                                        <button type="submit" id="save_compDetBtn" class="btn btn-primary">Save
                                            Changes</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="business-details" role="tabpanel" aria-labelledby="business-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">This is a CIP platform. Accurate organization details ensure precise compliance, tax management, and financial reporting.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/application/img-accout-alert.png') }}" alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmbusdet" id="frmbusdet" method="post">
                                    @csrf
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="card">
                                            <div class="message-container"></div>
                                            <div class="card-header">
                                                <h5>Business Details</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 mb-3">
                                                        <label class="form-label">Category Of Business <span class="text-danger">*</span></label>
                                                        <div id="business">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="comp_nature" id="trading"
                                                                    value="trading"
                                                                    {{ isset($compDetails->comp_category) ? ($compDetails->comp_category == 'trading' ? 'checked' : '') : '' }}>
                                                                <label class="form-check-label"
                                                                    for="trading">Trading / Reseller</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="comp_nature" id="service_provider" value="service"
                                                                    {{ isset($compDetails->comp_nature) ? ($compDetails->comp_nature == 'service' ? 'checked' : '') : '' }}>
                                                                <label class="form-check-label"
                                                                    for="service_provider">Service Provider</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="comp_nature" id="professional" value="professional"
                                                                    {{ isset($compDetails->comp_nature) ? ($compDetails->comp_nature == 'professional' ? 'checked' : '') : '' }}>
                                                                <label class="form-check-label"
                                                                    for="professional">Professional</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="comp_nature" id="mixed" value="mixed_nature"
                                                                    {{ isset($compDetails->comp_nature) ? ($compDetails->comp_nature == 'mixed_nature' ? 'checked' : '') : '' }}>
                                                                <label class="form-check-label"
                                                                    for="mixed">Mixed Nature (Trading + Service)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Exact Nature of Business <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="exact_comp_nature" id="exact_comp_nature" required
                                                                value="{{ isset($compDetails->exact_comp_nature) ? $compDetails->exact_comp_nature : '' }}"
                                                                placeholder="Enter Exact Nature of Business">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Date of Commencement of Business</label>
                                                            <input type="date" class="form-control" name="start_date"
                                                                id="start_date"
                                                                value="{{ isset($compDetails->start_date) ? $compDetails->start_date : '' }}"
                                                                placeholder="Select Date">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Business Turnover (Last Financial Year)</label>
                                                            <input type="number" class="form-control"
                                                                name="turnover_last_year" id="turnover_last_year"
                                                                value="{{ isset($compDetails->turnover_last_year) ? $compDetails->turnover_last_year : '' }}"
                                                                placeholder="Enter Business Turnover">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Opening Balance (Last Financial Year)</label>
                                                            <input type="number" class="form-control"
                                                                name="opening_balance" id="opening_balance"
                                                                value="{{ isset($compDetails->opening_balance) ? $compDetails->opening_balance : '' }}"
                                                                placeholder="Enter Opening Balance">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Opening Balance Cr</label>
                                                            <input type="number" class="form-control"
                                                                name="openingbalancecr" id="openingbalancecr"
                                                                value="{{ isset($compDetails->openingbalancecr) ? $compDetails->openingbalancecr : '' }}"
                                                                placeholder="Enter Opening Balance Cr">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Opening Balance Dr</label>
                                                            <input type="number" class="form-control"
                                                                name="openingbalancedr" id="openingbalancedr"
                                                                value="{{ isset($compDetails->openingbalancedr) ? $compDetails->openingbalancedr : '' }}"
                                                                placeholder="Enter Opening Balance Dr">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Quotation Invoice Series Setup</label>
                                                            <input type="text" class="form-control"
                                                                name="comp_quo_digits" id="comp_quo_digits" maxlength="16"
                                                                value="{{ isset($compDetails->comp_quo_digits) ? $compDetails->comp_quo_digits : '' }}"
                                                                placeholder="e.g., QT/2024-25/">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Proforma Invoice Series Setup</label>
                                                            <input type="text" class="form-control"
                                                                name="comp_prof_digits" id="comp_prof_digits" maxlength="16"
                                                                value="{{ isset($compDetails->comp_prof_digits) ? $compDetails->comp_prof_digits : '' }}"
                                                                placeholder="e.g., PI/2024-25/">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Sales Invoice Series Setup </label>
                                                            <input type="text" class="form-control"
                                                                name="comp_inv_digits" id="comp_inv_digits" maxlength="16"
                                                                value="{{ isset($compDetails->comp_inv_digits) ? $compDetails->comp_inv_digits : '' }}"
                                                                placeholder="e.g., SI/2024-25/">
                                                            <small class="form-text text-muted">This replaces Company Invoice Number Digits</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Purchase Order (PO) Series Setup</label>
                                                            <input type="text" class="form-control"
                                                                name="comp_po_digits" id="comp_po_digits" maxlength="16"
                                                                value="{{ isset($compDetails->comp_po_digits) ? $compDetails->comp_po_digits : '' }}"
                                                                placeholder="e.g., PO/2024-25/">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end btn-page">
                                        <a href="javascript:void(0);" id="cancel_busDetBtn"
                                            class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" id="save_busDetBtn" class="btn btn-primary">Save
                                            Changes</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="contact-details" role="tabpanel" aria-labelledby="contact-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">This is a CIP platform. Accurate organization details ensure precise compliance, tax management, and financial reporting.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/application/img-accout-alert.png') }}" alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmcontdet" id="frmcontdet" method="post">
                                    @csrf
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="card">
                                            <div class="message-container"></div>
                                            <div class="card-header">
                                                <h5>Contact Details</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Company Contact No <span class="text-danger">*</span></label>
                                                            <input type="tel" class="form-control" name="comp_phone"
                                                                id="comp_phone" required
                                                                value="{{ !empty($compDetails->comp_phone) ? $compDetails->comp_phone : $compDetails->phone }}"
                                                                placeholder="Enter Company Contact No" maxlength="10">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Company Mail ID <span class="text-danger">*</span></label>
                                                            <input type="email" class="form-control" name="comp_email"
                                                                id="comp_email" required
                                                                value="{{ !empty($compDetails->comp_email) ? $compDetails->comp_email : $compDetails->email }}"
                                                                placeholder="Enter Company Mail ID">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">WhatsApp No <span class="text-danger">*</span></label>
                                                            <input type="tel" class="form-control" name="whatsapp_no"
                                                                id="whatsapp_no" required
                                                                value="{{ isset($compDetails->whatsapp_no) ? $compDetails->whatsapp_no : '' }}"
                                                                placeholder="Enter WhatsApp No" maxlength="10">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Website Address</label>
                                                            <input type="url" class="form-control" name="comp_website"
                                                                id="comp_website"
                                                                value="{{ isset($compDetails->comp_website) ? $compDetails->comp_website : '' }}"
                                                                placeholder="Enter Website URL">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end btn-page">
                                        <a href="javascript:void(0);" id="cancel_contDetBtn"
                                            class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" id="save_contDetBtn" class="btn btn-primary">Save
                                            Changes</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="director-details" role="tabpanel"
                                aria-labelledby="director-details-tab">
                               <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">This is a CIP platform. Accurate organization details ensure precise compliance, tax management, and financial reporting.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/application/img-accout-alert.png') }}" alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmdirdet" id="frmdirdet" method="post">
                                    @csrf
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="card">
                                            <div class="message-container"></div>
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h5>Director Details</h5>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDirectorModal">
                                                    <i class="ti ti-plus me-1"></i>Add Another Director / Owner
                                                </button>
                                            </div>
                                            <div class="card-body">
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
															<?php $i=1; ?>
															@foreach($directorDetails as $d)
																<tr id="row_{{ $d->id }}">
																	<td>{{ $i++ }}</td>
																	<td>{{ $d->director_name }}</td>
																	<td>{{ $d->director_designation }}</td>
																	<td>{{ $d->director_email }}</td>
																	<td>{{ $d->director_phone }}</td>
																	<td>
																		<button type="button" class="btn btn-sm btn-primary"
																			data-bs-toggle="modal" data-bs-target="#viewDirectorModal"
																			onclick="showDirectorDetails(
																				'{{ $d->director_name }}',
																				'{{ $d->director_designation }}',
																				'{{ $d->director_email }}',
																				'{{ $d->director_phone }}',
																				'{{ $d->director_din }}',
																				'{{ Storage::url($d->director_signature) }}'
																			)"
																			>
																			<i class="ti ti-eye"></i>
																		</button>
																		
																		<button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $d->id }}"><i class="ti ti-trash"></i></button>
																		
																	</td>
																</tr>
															@endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end btn-page">
                                        <a href="javascript:void(0);" id="cancel_dirDetBtn"
                                            class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" id="save_dirDetBtn" class="btn btn-primary">Save
                                            Changes</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="bank-details" role="tabpanel" aria-labelledby="bank-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">This is a CIP platform. Accurate organization details ensure precise compliance, tax management, and financial reporting.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/application/img-accout-alert.png') }}" alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-12 text-end mb-2">
										<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-bank-modal">
											<i class="ti ti-square-plus f-20"></i> Add New Bank
										</a>
									</div>
									@foreach ($bankDetails as $val)

									<div class="col-md-6 col-xxl-6">
										<div class="card">
											<div class="card-body">
												<div class="d-flex align-items-center justify-content-between mb-3">
													<h5 class="mb-0">{{ $val->bank_name }} Details</h5>
													<div class="dropdown">
														<a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<i class="ti ti-dots-vertical f-18"></i>
														</a>
														<div class="dropdown-menu dropdown-menu-end">
															<a class="dropdown-item" href="{{ route('user.BankDetails', ['id' => base64_encode($val->id)]) }}">View</a>
															<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit-bank-modal{{ $val->id }}">Edit</a>
															<a class="dropdown-item delete-btn" href="#" data-id="{{ $val->id }}" data-bs-toggle="modal" data-bs-target="#delete_modal">Delete</a>
														</div>
													</div>
												</div>
												<div class="card rounded-4 overflow-hidden" style="background-image: url(../assets/images/widget/img-card-bg.svg); background-size: cover;">
													<div class="card-body">
														
														<div class="d-flex">
															<div class="flex-grow-1 me-3">
																<p class="text-white text-sm text-opacity-50 mb-0">Bank Name</p>
																<h5 class="text-white">{{ $val->bank_name }}</h5>
															</div>
														</div>
														<div class="row">
															<div class="col-auto">
																<p class="text-white text-sm text-opacity-100 mb-0">Account Name</p>
																<h4 class="text-white mb-3">{{ $val->bank_ac_no }}</h4>
															</div>
														</div>
														<div class="row">
															<div class="col-auto">
																<p class="text-white text-sm text-opacity-50 mb-0">IFSC Code</p>
																<h6 class="text-white mb-0">{{ $val->ifsc_code }}</h6>
															</div>
															<div class="col-auto">
																<p class="text-white text-sm text-opacity-50 mb-0">Branch</p>
																<h6 class="text-white mb-0">{{ $val->bank_branch }}</h6>
															</div>
															<div class="col-auto">
																<p class="text-white text-sm text-opacity-50 mb-0">Swift Code</p>
																<h6 class="text-white mb-0">{{ $val->swift_code }}</h6>
															</div>
														</div>
														<div class="row mt-4 align-items-start">
                                                            <div class="col-auto">
                                                                <p class="text-white text-sm text-opacity-50 mb-0">Account Name</p>
                                                                <h6 class="text-white mb-3">{{ $val->accholder_name }}</h6>
                                                            </div>

                                                            <div class="col-auto">
                                                                <p class="text-white text-sm text-opacity-50 mb-0">Opening Balance</p>
                                                                <h6 class="text-white mb-0">₹{{ number_format($val->curr_bal, 2) }}</h6>
                                                            </div>

                                                            @if(!empty($val->bank_qr_code))
                                                            <div class="col-auto ms-auto text-end">
                                                                <p class="text-white text-sm text-opacity-50 mb-2">Bank QR Code</p>
                                                                <button type="button"
                                                                        class="btn btn-sm btn-light toggleQrBtn"
                                                                        data-target="qrCode{{ $val->id }}">
                                                                    <i class="ti ti-eye"></i>
                                                                    <span>View QR</span>
                                                                </button>

                                                                <div id="qrCode{{ $val->id }}" class="d-none mt-2">
                                                                    <img src="{{ asset('storage/'.$val->bank_qr_code) }}"
                                                                        alt="Bank QR Code"
                                                                        class="img-fluid bg-white rounded p-2"
                                                                        style="width:130px;height:130px;object-fit:contain;">
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
													</div>
												</div>
											</div>
										</div>
									</div>


									{{-- Edit Modal --}}
									<div class="modal custom-modal fade" id="edit-bank-modal{{ $val->id }}" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered modal-xl">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">Edit Bank Details</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<form id="upadteBankForm{{ $val->id }}" method="post" enctype="multipart/form-data">
													<input type="hidden" name="id"  value="{{ $val->id }}">
													<input type="hidden" name="redirectUrl" value="company-profile">
													<div class="modal-body">

														<!-- Bank Name -->
														<div class="row">															
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">Bank Name<span class="text-danger">*</span></label>
																<input type="text" name="bank_name" id="bank_name{{ $val->id }}" value="{{ $val->bank_name }}" class="form-control" placeholder="Enter Bank Name" required>
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">Branch<span class="text-danger">*</span></label>
																<input type="text" name="bank_branch" id="bank_branch{{ $val->id }}" value="{{ $val->bank_branch }}" class="form-control" placeholder="Enter Branch" required>
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">Account Name<span class="text-danger">*</span></label>
																<input type="text" name="accholder_name" id="accholder_name{{ $val->id }}" value="{{ $val->accholder_name }}" class="form-control" placeholder="Enter Account Name" required>
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">Account Number<span class="text-danger">*</span></label>
																<input type="text" name="bank_ac_no" id="bank_ac_no{{ $val->id }}" value="{{ $val->bank_ac_no }}" class="form-control" placeholder="Enter Account Number" required>
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">IFSC Code<span class="text-danger">*</span></label>
																<input type="text" name="ifsc_code" id="ifsc_code{{ $val->id }}" value="{{ $val->ifsc_code }}" class="form-control" placeholder="Enter IFSC Code" required>
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">Swift Code</label>
																<input type="text" name="swift_code" id="swift_code{{ $val->id }}" value="{{ $val->swift_code }}" class="form-control" placeholder="Enter Swift Code" >
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">UPI ID</label>
																<input type="text" name="upi_id" id="upi_id{{ $val->id }}" value="{{ $val->upi_id }}" class="form-control" placeholder="Enter UPI ID" >
															</div>
															<div class="col-md-4  mb-3">
																<label for="bankName" class="form-label">Current Bank Balance<span class="text-danger">*</span></label>
																<input type="text" name="curr_bal" id="curr_bal{{ $val->id }}" value="{{ $val->curr_bal }}" class="form-control" placeholder="Enter Current Bank Balance" required>
															</div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">
                                                                    Bank QR Code 
                                                                </label>

                                                                <input type="file"
                                                                    name="bank_qr_code"
                                                                    id="bank_qr_code{{ $val->id }}"
                                                                    class="form-control"
                                                                    accept=".jpg,.jpeg,.png,.webp">

                                                                @if(!empty($val->bank_qr_code))
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/'.$val->bank_qr_code) }}"
                                                                            alt="QR Code"
                                                                            style="max-width:120px; max-height:120px;"
                                                                            class="img-thumbnail">
                                                                    </div>
                                                                @endif
                                                            </div>
														</div>

													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
														<button type="submit" class="btn btn-primary">Update Bank Account</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									@endforeach
								</div>
                            </div>

                            <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">This is a CIP platform. Accurate organization details ensure precise compliance, tax management, and financial reporting.</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/application/img-accout-alert.png') }}" alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <h5>Statutory Details</h5>

                                    <form action="javascript:void(0);" name="frmattadet" id="frmattadet" method="post"
                                        enctype="multipart/form-data">


                                        <div class="row">

                                            {{-- test --}}
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Upload Incorporation Certificate</h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <label class="upload-area" for="inc_certificate" style="cursor: pointer;">
                                                            <span class="upload-text" id="inc_certificate_label">

                                                                @if(isset($docs['Certificate of Incorporation of Company']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                class="fileInput"
                                                                name="inc_certificate"
                                                                id="inc_certificate"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Certificate of Incorporation of Company']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'inc_certificate_label')">

                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Proprietor/ Company PAN Card</h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <label class="upload-area" for="pan_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="pan_doc_label">

                                                                @if(isset($docs['Pan Card of Company']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                class="fileInput"
                                                                name="pan_doc"
                                                                id="pan_doc"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Pan Card of Company']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'pan_doc_label')">

                                                        </label>
                                                    </div>
                                                </div>
                                            </div>





                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Upload Company GST Certificate</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="gst_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="gst_doc_label">

                                                                @if(isset($docs['GST Registration Certificate of Company']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="gst_doc"
                                                                id="gst_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['GST Registration Certificate of Company']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'gst_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Trade License / Shop & Establishment</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="trade_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="trade_doc_label">

                                                                @if(isset($docs['Trade License Document']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="trade_doc"
                                                                id="trade_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Trade License Document']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'trade_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>PF & ESI Certificate</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="pf_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="pf_doc_label">

                                                                @if(isset($docs['PF Establishment Code Letter']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="pf_doc"
                                                                id="pf_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['PF Establishment Code Letter']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'pf_doc_label')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>P Tax Certificate</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="ptex_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="ptex_doc_label">

                                                                @if(isset($docs['Professional Tax Registration Document']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="ptex_doc"
                                                                id="ptex_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Professional Tax Registration Document']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'ptex_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="my-4">Proprietor/Partner/Directors Details</h5>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>First Director's Aadhar Card</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="first_diraadh_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="first_diraadh_doc_label">

                                                                @if(isset($docs['First Director Aadhaar Card']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="first_diraadh_doc"
                                                                id="first_diraadh_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['First Director Aadhaar Card']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'first_diraadh_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>First Director's PAN Card</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="firstpan_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="firstpan_doc_label">

                                                                @if(isset($docs['First Director PAN Card']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="firstpan_doc"
                                                                id="firstpan_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['First Director PAN Card']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'firstpan_doc_label')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>First Director's Photo</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="first_dirphoto_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="first_dirphoto_doc_label">

                                                                @if(isset($docs['First Director Photograph']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="first_dirphoto_doc"
                                                                id="first_dirphoto_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['First Director Photograph']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'first_dirphoto_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Second Director's Aadhaar Card</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="second_aadha_doc" style="cursor: pointer;">

                                                            <span class="upload-text" id="second_aadha_doc_label">

                                                                @if(isset($docs['Second Director Aadhaar Card']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="second_aadha_doc"
                                                                id="second_aadha_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Second Director Aadhaar Card']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'second_aadha_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Second Director's PAN Card</h5>
                                                    </div>

                                                    <div class="card-body text-center">

                                                        <label class="upload-area" for="second_pan_doc" style="cursor:pointer;">

                                                            <span class="upload-text" id="second_pan_doc_label">

                                                                @if(isset($docs['Second Director PAN Card']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif

                                                            </span>

                                                            <input type="file"
                                                                name="second_pan_doc"
                                                                id="second_pan_doc"
                                                                class="fileInput"
                                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Second Director PAN Card']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this,'second_pan_doc_label')">

                                                        </label>

                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Second Director's Photo</h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <label class="upload-area" for="second_dirphoto_doc" style="cursor: pointer;">
                                                            <span class="upload-text" id="second_dirphoto_doc_label">
                                                                {{-- Logic: Toggle checkmark/text based on file existence --}}
                                                                {{-- @if (!empty($compDetails->second_dirphoto_doc))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif --}}

                                                                @if(isset($docs['Second Director Photograph']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif


                                                            </span>

                                                            {{-- Input is disabled if file exists; triggers filename update via JS --}}
                                                            <input type="file" name="second_dirphoto_doc" id="second_dirphoto_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Second Director Photograph']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'second_dirphoto_doc_label')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <h5 class="my-4">Other Documents</h5>
                                            
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Upload Signeture</h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <label class="upload-area" for="signature_doc" style="cursor: pointer;">
                                                            <span class="upload-text" id="signature_doc_label">
                                                                {{-- Logic: Show success icon if uploaded, otherwise show prompt --}}
                                                                @if(isset($docs['Authorized Signature']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif
                                                            </span>

                                                            {{-- Input is disabled if file exists; triggers JS update on selection --}}
                                                            <input type="file" name="signature_doc" id="signature_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Authorized Signature']) ? 'disabled' : '' }}
                                                                hidden
                                                                onchange="updateFileName(this, 'signature_doc_label')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Upload Company Stamp</h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <label class="upload-area" for="stamp_doc" style="cursor: pointer;">
                                                            <span class="upload-text" id="stamp_doc_label">
                                                                {{-- Logic: If file exists, show success; otherwise show upload prompt --}}
                                                                @if(isset($docs['Company Stamp']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif
                                                            </span>

                                                            {{-- Input is disabled if file already exists; triggers name preview via JS --}}
                                                            <input type="file" name="stamp_doc" id="stamp_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Company Stamp']) ? 'disabled' : '' }} hidden
                                                                onchange="updateFileName(this, 'stamp_doc_label')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Upload any other document</h5>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        <label class="upload-area" for="other_logo_doc" style="cursor: pointer;">
                                                            <span class="upload-text" id="other_logo_doc_label">
                                                                {{-- Logic: Toggle checkmark if file exists, else show upload prompt --}}
                                                                
                                                                @if(isset($docs['Company Logo']))
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif
                                                            </span>

                                                            {{-- Input is disabled if file exists; triggers JS update on selection --}}
                                                            <input type="file" name="other_logo_doc" id="other_logo_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Company Logo']) ? 'disabled' : '' }} hidden
                                                                onchange="updateFileName(this, 'other_logo_doc_label')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mt-1 justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input input-primary" type="checkbox"
                                                    id="checkbox" name="checkbox"
                                                    {{ isset($compDetails) &&
                                                    is_object($compDetails) &&
                                                    property_exists($compDetails, 'chk_agree') &&
                                                    $compDetails->chk_agree == 1
                                                        ? 'checked'
                                                        : '' }}>
                                                <label class="form-check-label text-muted" for="customCheckc1">
                                                    I agree to all the <a href="javascript:void(0);"
                                                        id="termsLink">Terms &
                                                        Conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="text-end btn-page">
                                            <a href="javascript:void(0);" id="cancel_attaBtn"
                                                class="btn btn-outline-secondary">Cancel</a>
                                            <button type="submit" id="save_attaBtn" class="btn btn-primary">Save
                                                Changes</button>
                                            {{-- <button type="submit" id="save_attaBtn"
                                            onclick="if(!this.form.checkbox.checked){alert('You must agree to Terms and Conditions.');return false}"
                                            class="btn btn-primary">Save Changes</button> --}}
                                        </div>
                                        <div class="modal fade" id="termsModal" tabindex="-1" role="dialog"
                                            aria-labelledby="termsModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-center" id="termsModalLabel">Terms
                                                            and
                                                            Conditions</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-center">The user agrees to use our accounting
                                                            software service, providing accurate information, maintaining
                                                            login confidentiality, and using it for lawful purposes only.
                                                            The company reserves the right to suspend or terminate accounts
                                                            for any violation. The software is provided 'as is' and we
                                                            disclaim warranties regarding its performance and suitability
                                                            for specific needs.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="tab-pane fade" id="assign-ca" role="tabpanel" aria-labelledby="assign-ca">
								@if(empty($ca_details))
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">
												You do not assign any CA firm / Accountant yet. Please Assign CA from – No CA firm or accountant has been assigned yet. Kindly assign a CA from the provided options.
												</p>
                                                <a href="{{ route('user.AssignCa') }}" class="alert-link"> <u>Assign New CA Firm / Accountant</u> <i class="ti ti-arrow-up-right-circle"></i></a>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('assets/images/application/img-accout-password-alert.png') }}" alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
								@else
								<div class="card alert alert-success p-0">
									<div class="card-body">
										<div class="d-flex align-items-center">
											<div class="flex-grow-1 me-3">
												<h3 class="alert-heading">Success!</h3>
												<p class="mb-2">
													You have successfully assigned a CA firm / Accountant.
												</p>
											</div>
											<div class="flex-shrink-0">
												<img src="../assets/images/application/img-accout-password-alert.png"
													alt="img" class="img-fluid wid-80">
											</div>
										</div>
									</div>
								</div>
								@endif
								
                                @if ($ca_details)
                                    @foreach ($ca_details as $ca_id => $ca_detail)
                                        <div class="row">
                                            <div class="col-md-6 col-xl-6">
                                                <div class="card user-card">
                                                    <div class="card-body position-relative">
                                                        <div class="chat-avtar d-inline-flex mx-auto">
                                                            <!--<img class="rounded-circle img-fluid wid-90 img-thumbnail" src="../assets/images/user/avatar-1.jpg" alt="User image">-->
                                                            @if (isset($ca_detail->comp_logo) && $ca_detail->comp_logo != '')
                                                                <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                                                                    src="{{ asset('storage/ca_profile/' . $ca_detail->comp_logo) }}"
                                                                    alt="User image">
                                                            @else
                                                                <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                                                                    src="../assets/images/user/avatar-1.jpg"
                                                                    alt="User image">
                                                            @endif
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">
                                                                    {{ $ca_detail->comp_name != '' ? $ca_detail->comp_name : $ca_detail->name }}
                                                                </h6>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                @if ($ca_detail->ca_assign_status == 0)
                                                                    <button class="btn btn-primary btn-md assignCABtn"
                                                                        data-id="{{ $ca_detail->id }}"
                                                                        data-status="{{ $ca_detail->ca_assign_status }}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#staticBackdrop">Assign</button>
                                                                @else
                                                                    <button class="btn btn-primary btn-md assignCABtn"
                                                                        data-id="{{ $ca_detail->id }}"
                                                                        data-status="{{ $ca_detail->ca_assign_status }}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#staticBackdrop">Un-Assign</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row g-3 my-3 text-center">
                                                            <div class="col-4">
                                                                <h5 class="mb-0">{{ $ca_detail->total_no_client }}
                                                                </h5>
                                                                <small class="text-muted">Company Assign</small>
                                                            </div>
                                                            <div class="col-4 border border-top-0 border-bottom-0">
                                                                <h5 class="mb-0">4 Years +</h5>
                                                                <small class="text-muted">Exprience</small>
                                                            </div>
                                                            <div class="col-4">
                                                                <h5 class="mb-0"></i>4.5</h5>
                                                                <small class="text-muted"><i
                                                                        class="ph-duotone ph-star text-warning me-1"></i>Rating</small>
                                                            </div>
                                                        </div>
                                                        <div class="saprator my-3">
                                                            <span>Experts In</span>
                                                        </div>
                                                        <div class="text-center">
                                                            <?php if ($ca_detail->ca_spec != "") {
                                                                $specArr = explode(",", $ca_detail->ca_spec);
                                                                foreach ($specArr as $k => $val) {
                                                            ?>
                                                            <span
                                                                class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">{{ $val }}</span>
                                                            <?php }
                                            } ?>
                                                        </div>
                                                        <div class="saprator my-3">
                                                            <span>Address</span>
                                                        </div>
                                                        <h6 class="text-center">
                                                            {{ $ca_detail->comp_bill_addone . ',' . $ca_detail->ca_state . ',' . $ca_detail->ca_city . ',' . $ca_detail->comp_bill_pin }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            $specDetails = isset($ca_detail->request_for) ? $ca_detail->request_for : '';
                                            $specDetails = explode(',', $specDetails);
											//echo "<pre>";print_r($specDetails);exit;
                                            ?>
                                            <div class="col-md-6 col-xl-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Purpose of Attachment with CA / Accountant</h5>
                                                    </div>
                                                    <form action="javascript:void(0);" name="frmRequestFor"
                                                        id="frmRequestFor" method="post">
                                                        <input type="hidden" name="comp_id"
                                                            value="{{ $ca_detail->id }}" />
                                                        <input type="hidden" name="ca_id"
                                                            value="{{ $ca_id }}" />
                                                        @csrf
                                                        <div class="message-container"></div>
															<div class="card-body">
																<!-- Company Incorporation -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Company Incorporation</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Company Incorporation',$specDetails)) echo 'checked="checked"'; ?>
																		value="Company Incorporation">
																	</div>
																</div>

																<!-- Company Compliances -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Company Compliances</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Company Compliances',$specDetails)) echo 'checked="checked"'; ?>
																		value="Company Compliances">
																	</div>
																</div>

																<!-- MCA / ROC Compliances -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">MCA / ROC Compliances</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('MCA / ROC Compliances',$specDetails)) echo 'checked="checked"'; ?>
																		value="MCA / ROC Compliances">
																	</div>
																</div>

																<!-- Accounts Preparation -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Accounts Preparation</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Accounts Preparation',$specDetails)) echo 'checked="checked"'; ?>
																		value="Accounts Preparation">
																	</div>
																</div>

																<!-- GST & Filings -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">GST & Filings</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('GST & Filings',$specDetails)) echo 'checked="checked"'; ?>
																		value="GST & Filings">
																	</div>
																</div>

																<!-- Auditing -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Auditing</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Auditing',$specDetails)) echo 'checked="checked"'; ?>
																		value="Auditing">
																	</div>
																</div>

																<!-- Auditor Recruitment -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Auditor Recruitment</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Auditor Recruitment',$specDetails)) echo 'checked="checked"'; ?>
																		value="Auditor Recruitment">
																	</div>
																</div>

																<!-- MSME / Trade License -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">MSME / Trade Lisence</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('MSME / Trade Lisence',$specDetails)) echo 'checked="checked"'; ?>
																		value="MSME / Trade Lisence">
																	</div>
																</div>

																<!-- Licensing & Registration -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Licensing & Registration</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Licensing & Registration',$specDetails)) echo 'checked="checked"'; ?>
																		value="Licensing & Registration">
																	</div>
																</div>

																<!-- Income Tax Filings -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Income Tax Filings</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Income Tax Filings',$specDetails)) echo 'checked="checked"'; ?>
																		value="Income Tax Filings">
																	</div>
																</div>

																<!-- TDS & Filing -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">TDS & Filing</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('TDS & Filing',$specDetails)) echo 'checked="checked"'; ?>
																		value="TDS & Filing">
																	</div>
																</div>

																<!-- PF & ESIC -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">PF & ESIC</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('PF & ESIC',$specDetails)) echo 'checked="checked"'; ?>
																		value="PF & ESIC">
																	</div>
																</div>

																<!-- Professional Tax -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Professional Tax</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Professional Tax',$specDetails)) echo 'checked="checked"'; ?>
																		value="Professional Tax">
																	</div>
																</div>

																<!-- Project Report / DPR with CMA Data -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Project Report / DPR with CMA Data</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Project Report / DPR with CMA Data',$specDetails)) echo 'checked="checked"'; ?>
																		value="Project Report / DPR with CMA Data">
																	</div>
																</div>

																<!-- Outsourcing of work -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Outsourcing of work</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Outsourcing of work',$specDetails)) echo 'checked="checked"'; ?>
																		value="Outsourcing of work">
																	</div>
																</div>

																<!-- Outsourcing of employee -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Outsourcing of employee</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Outsourcing of Employee',$specDetails)) echo 'checked="checked"'; ?>
																		value="Outsourcing of Employee">
																	</div>
																</div>

																<!-- Payroll & HR Compliances -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Payroll & HR Compliances</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Payroll & HR Compliances',$specDetails)) echo 'checked="checked"'; ?>
																		value="Payroll & HR Compliances">
																	</div>
																</div>

																<!-- Company Legal Service -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Company Legal Service</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Company Leagal Service',$specDetails)) echo 'checked="checked"'; ?>
																		value="Company Leagal Service">
																	</div>
																</div>

																<!-- Consulting & Advisory Services -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Consulting & Advisory Services</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Consulting & Advisory Services',$specDetails)) echo 'checked="checked"'; ?>
																		value="Consulting & Advisory Services">
																	</div>
																</div>

																<!-- DPDP Act -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">DPDP Act, 2023</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('DPDP Act 2023',$specDetails)) echo 'checked="checked"'; ?>
																		value="DPDP Act 2023">
																	</div>
																</div>

																<!-- IP Advisory -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">IP Advisory</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('IP Advisory',$specDetails)) echo 'checked="checked"'; ?>
																		value="IP Advisory">
																	</div>
																</div>

																<!-- Other -->
																<div class="d-flex align-items-center justify-content-between mb-3">
																	<div><p class="text-muted mb-0">Other</p></div>
																	<div class="form-check form-switch p-0">
																		<input class="m-0 form-check-input h5 position-relative requestForCheck"
																		type="checkbox" role="switch" name="request_for[]"
																		<?php if (in_array('Other',$specDetails)) echo 'checked="checked"'; ?>
																		value="Other">
																	</div>
																</div>

															</div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            

                            <div class="tab-pane fade" id="holidays" role="tabpanel" aria-labelledby="holidays-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4><i class="ph-duotone ph-calendar me-2"></i>Company Holidays</h4>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#holidayModal">
                                            <i class="ti ti-plus"></i> Add Holiday
                                        </button>
                                    </div>


                                    <!-- Holiday Statistics -->
                                    <div class="row mt-4 g-4 px-3">
                                        <!-- Total Holidays Card -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                                <div class="position-absolute top-0 end-0 opacity-25">
                                                    <i class="fas fa-calendar-alt"  style="font-size: 8rem; color: #667eea; transform: rotate(-15deg);"></i>
                                                </div>
                                                <div class="card-body text-center p-4 position-relative"
                                                    style="z-index: 1;">
                                                    <h1 class="display-3 fw-bold text-primary mb-2">
                                                        {{ $holidays->filter(fn($h) => \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                    </h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                        style="letter-spacing: 1.5px;">Total Holidays</h6>
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary">{{ date('Y') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- National Holidays Card -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                                <div class="position-absolute top-0 end-0 opacity-25">
                                                    <i class="fas fa-flag" style="font-size: 8rem; color: #dc3545; transform: rotate(-15deg);"></i>
                                                </div>
                                                <div class="card-body text-center p-4 position-relative"
                                                    style="z-index: 1;">
                                                    <h1 class="display-3 fw-bold text-danger mb-2">
                                                        {{ $holidays->filter(fn($h) => $h->holidayType === 'National' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                    </h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                        style="letter-spacing: 1.5px;">National Holidays</h6>
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger">Government</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Festival Holidays Card -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                                <div class="position-absolute top-0 end-0 opacity-25">
                                                    <i class="fas fa-gifts" style="font-size: 8rem; color: #ffc107; transform: rotate(-15deg);"></i>
                                                </div>
                                                <div class="card-body text-center p-4 position-relative"
                                                    style="z-index: 1;">
                                                    <h1 class="display-3 fw-bold text-warning mb-2">
                                                        {{ $holidays->filter(fn($h) => $h->holidayType === 'Festival' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                    </h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                        style="letter-spacing: 1.5px;">Festival Holidays</h6>
                                                    <span
                                                        class="badge bg-warning bg-opacity-10 text-warning">Cultural</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Company Holidays Card -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                                <div class="position-absolute top-0 end-0 opacity-25">
                                                    <i class="fas fa-building" style="font-size: 8rem; color: #0dcaf0; transform: rotate(-15deg);"></i>
                                                </div>
                                                <div class="card-body text-center p-4 position-relative"
                                                    style="z-index: 1;">
                                                    <h1 class="display-3 fw-bold text-info mb-2">
                                                        {{ $holidays->filter(fn($h) => $h->holidayType === 'Company' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                    </h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                        style="letter-spacing: 1.5px;">Company Holidays</h6>
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info">Organization</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card-body">
                                        <div class="modal fade" id="holidayModal" tabindex="-1"
                                            aria-labelledby="holidayModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="holidayModalLabel">Add Holiday</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <form id="holidayForm">
                                                        <input type="hidden" id="holidayId" name="holidayId"
                                                            value="">
                                                        <input type="hidden" id="formMethod" name="_method"
                                                            value="POST">
                                                        <div class="modal-body">
                                                            <!-- Holiday name -->
                                                            <div class="mb-3">
                                                                <label for="holidayName" class="form-label">Holiday
                                                                    Name</label>
                                                                <input type="text" id="holidayName"
                                                                    name="holidayName" class="form-control" required>
                                                            </div>

                                                            <!-- Date -->
                                                            <div class="mb-3">
                                                                <label for="holidayDate" class="form-label">Date</label>
                                                                <input type="date" id="holidayDate"
                                                                    name="holidayDate" class="form-control" required>
                                                            </div>

                                                            <!-- NEW: Type -->
                                                            <div class="mb-3">
                                                                <label for="holidayType" class="form-label">Type</label>
                                                                <select id="holidayType" name="holidayType"
                                                                    class="form-select" required>
                                                                    <option value="">Choose a type…</option>
                                                                    <option value="National">National</option>
                                                                    <option value="Festival">Festival</option>
                                                                    <option value="Regional">Regional</option>
                                                                    <option value="Company">Company</option>
                                                                    <option value="Optional">Optional</option>
                                                                </select>
                                                            </div>

                                                            <!-- NEW: Description -->
                                                            <div class="mb-3">
                                                                <label for="holidayDescription"
                                                                    class="form-label">Description</label>
                                                                <textarea id="holidayDescription" name="holidayDescription" class="form-control" rows="3"
                                                                    placeholder="Optional notes"></textarea>
                                                            </div>

                                                        </div><!-- /.modal-body -->

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="holidaySubmitBtn">Save</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- Holiday List -->
                                        <div id="holidayList">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0"><i class="ti ti-list me-2"></i>Holiday List</h6>
                                                <div class="d-flex gap-2">
                                                    <select class="form-select form-select-sm" id="filterType"
                                                        style="width: auto;">
                                                        <option value="">All Types</option>
                                                        <option value="National">National</option>
                                                        <option value="Festival">Festival</option>
                                                        <option value="Regional">Regional</option>
                                                        <option value="Company">Company</option>
                                                        <option value="Optional">Optional</option>
                                                    </select>
                                                    <select class="form-select form-select-sm" id="filterYear"
                                                        style="width: auto;">
                                                        <option value="">All Years</option>
                                                        <!-- Years will be populated dynamically by JavaScript -->
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Holiday Table -->
                                            <div class="table-responsive" id="holidayTableContainer">
                                                <table class="table table-hover" id="holidaysTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="5%">Sr.</th>
                                                            <th width="25%">Holiday Name</th>
                                                            <th width="15%">Date</th>
                                                            <th width="15%">Type</th>
                                                            <th width="20%">Description</th>
                                                            <th width="10%">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="holidayTableBody">
                                                        @if ($holidays->count() > 0)
                                                            @foreach ($holidays as $index => $holiday)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <i
                                                                                class="ti ti-calendar-event
                                                                            @if ($holiday->holidayType == 'National') text-primary
                                                                            @elseif($holiday->holidayType == 'Festival') text-warning
                                                                            @elseif($holiday->holidayType == 'Company') text-info
                                                                            @elseif($holiday->holidayType == 'Regional') text-success
                                                                            @else text-secondary @endif me-2"></i>
                                                                            <strong>{{ $holiday->holidayName }}</strong>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge
                                                                        @if ($holiday->holidayType == 'National') bg-light-primary text-primary
                                                                        @elseif($holiday->holidayType == 'Festival') bg-light-warning text-warning
                                                                        @elseif($holiday->holidayType == 'Company') bg-light-info text-info
                                                                        @elseif($holiday->holidayType == 'Regional') bg-light-success text-success
                                                                        @else bg-light-secondary text-secondary @endif">{{ \Carbon\Carbon::parse($holiday->holidayDate)->format('d-m-Y') }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge
                                                                        @if ($holiday->holidayType == 'National') bg-primary
                                                                        @elseif($holiday->holidayType == 'Festival') bg-warning
                                                                        @elseif($holiday->holidayType == 'Company') bg-info
                                                                        @elseif($holiday->holidayType == 'Regional') bg-success
                                                                        @else bg-secondary @endif">{{ $holiday->holidayType }}</span>
                                                                    </td>
                                                                    <td>{{ $holiday->holidayDescription ?? '-' }}</td>
                                                                    <td>
                                                                        <div class="btn-group" role="group">
                                                                            <button class="btn btn-sm btn-outline-primary"
                                                                                onclick="editHoliday({{ $holiday->id }})"
                                                                                title="Edit">
                                                                                <i class="ti ti-edit"></i>
                                                                            </button>
                                                                            <button class="btn btn-sm btn-outline-danger"
                                                                                onclick="deleteHolidayWithSweetAlert({{ $holiday->id }})"
                                                                                title="Delete">
                                                                                <i class="ti ti-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Empty State -->
                                            @if ($holidays->count() == 0)
                                                <div id="emptyState" class="text-center py-5">
                                                    <div class="mb-3">
                                                        <i class="ti ti-calendar-off"
                                                            style="font-size: 4rem; color: #ccc;"></i>
                                                    </div>
                                                    <h5 class="text-muted">No Holidays Found</h5>
                                                    <p class="text-muted mb-3">You haven't added any holidays yet.</p>
                                                    <button type="button" class="btn btn-primary"
                                                        data-bs-toggle="modal" data-bs-target="#holidayModal">
                                                        <i class="ti ti-plus me-1"></i>Add Your First Holiday
                                                    </button>
                                                </div>
                                            @endif


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Day Schedule Tab -->
                            <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="ph-duotone ph-clock me-2"></i>Day Schedule</h5>
                                        <p class="text-muted mb-0">Configure your company's working hours for each day</p>
                                    </div>
                                    <div class="card-body">
                                        <form id="scheduleForm">
                                            <div class="table-responsive">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr class="border-bottom">
                                                            <th class="text-muted fw-normal">DAY</th>
                                                            <th class="text-muted fw-normal">OPENING TIME</th>
                                                            <th class="text-muted fw-normal">CLOSING TIME</th>
                                                            <th class="text-muted fw-normal">LUNCH START</th>
                                                            <th class="text-muted fw-normal">LUNCH END</th>
                                                            <th class="text-muted fw-normal">STATUS</th>
                                                            <th class="text-muted fw-normal">TOTAL HOURS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Monday -->
                                                        @php
                                                            $monday = $weeklySchedule['monday'] ?? null;
                                                            $mondayOpen = $monday ? $monday->opening_time : '10:00';
                                                            $mondayClose = $monday ? $monday->closing_time : '18:00';
                                                            $mondayLunchStart = $monday
                                                                ? $monday->lunch_time_start
                                                                : '12:00';
                                                            $mondayLunchStop = $monday
                                                                ? $monday->lunch_time_stop
                                                                : '13:00';
                                                            $mondayStatus = $monday ? $monday->status == 'open' : true;
                                                            $mondayHours = $monday
                                                                ? $monday->working_hours . ' hours'
                                                                : '8
                                                    hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$mondayStatus ? 'closed-day' : '' }}"
                                                            data-day="monday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $mondayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$mondayStatus ? 'text-muted' : '' }}">Monday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $mondayOpen }}" name="monday_open"
                                                                        {{ !$mondayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $mondayClose }}"
                                                                        name="monday_close"
                                                                        {{ !$mondayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $mondayLunchStart }}"
                                                                        name="monday_lunch_start"
                                                                        {{ !$mondayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $mondayLunchStop }}"
                                                                        name="monday_lunch_stop"
                                                                        {{ !$mondayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $mondayStatus ? 'checked' : '' }}
                                                                        name="monday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $mondayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $mondayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $mondayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $mondayStatus ? $mondayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                        <!-- Tuesday -->
                                                        @php
                                                            $tuesday = $weeklySchedule['tuesday'] ?? null;
                                                            $tuesdayOpen = $tuesday ? $tuesday->opening_time : '10:00';
                                                            $tuesdayClose = $tuesday ? $tuesday->closing_time : '17:00';
                                                            $tuesdayLunchStart = $tuesday
                                                                ? $tuesday->lunch_time_start
                                                                : '12:00';
                                                            $tuesdayLunchStop = $tuesday
                                                                ? $tuesday->lunch_time_stop
                                                                : '13:00';
                                                            $tuesdayStatus = $tuesday
                                                                ? $tuesday->status == 'open'
                                                                : true;
                                                            $tuesdayHours = $tuesday
                                                                ? $tuesday->working_hours . ' hours'
                                                                : '7
                                                    hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$tuesdayStatus ? 'closed-day' : '' }}"
                                                            data-day="tuesday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $tuesdayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$tuesdayStatus ? 'text-muted' : '' }}">Tuesday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $tuesdayOpen }}"
                                                                        name="tuesday_open"
                                                                        {{ !$tuesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $tuesdayClose }}"
                                                                        name="tuesday_close"
                                                                        {{ !$tuesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $tuesdayLunchStart }}"
                                                                        name="tuesday_lunch_start"
                                                                        {{ !$tuesdayStatus ? 'disabled' : '' }}>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $tuesdayLunchStop }}"
                                                                        name="tuesday_lunch_stop"
                                                                        {{ !$tuesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $tuesdayStatus ? 'checked' : '' }}
                                                                        name="tuesday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $tuesdayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $tuesdayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $tuesdayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $tuesdayStatus ? $tuesdayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                        <!-- Wednesday -->
                                                        @php
                                                            $wednesday = $weeklySchedule['wednesday'] ?? null;
                                                            $wednesdayOpen = $wednesday
                                                                ? $wednesday->opening_time
                                                                : '09:00';
                                                            $wednesdayClose = $wednesday
                                                                ? $wednesday->closing_time
                                                                : '18:00';
                                                            $wednesdayLunchStart = $wednesday
                                                                ? $wednesday->lunch_time_start
                                                                : '12:00';
                                                            $wednesdayLunchStop = $wednesday
                                                                ? $wednesday->lunch_time_stop
                                                                : '13:00';
                                                            $wednesdayStatus = $wednesday
                                                                ? $wednesday->status == 'open'
                                                                : true;
                                                            $wednesdayHours = $wednesday
                                                                ? $wednesday->working_hours . ' hours'
                                                                : '9 hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$wednesdayStatus ? 'closed-day' : '' }}"
                                                            data-day="wednesday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $wednesdayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$wednesdayStatus ? 'text-muted' : '' }}">Wednesday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $wednesdayOpen }}"
                                                                        name="wednesday_open"
                                                                        {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $wednesdayClose }}"
                                                                        name="wednesday_close"
                                                                        {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $wednesdayLunchStart }}"
                                                                        name="wednesday_lunch_start"
                                                                        {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $wednesdayLunchStop }}"
                                                                        name="wednesday_lunch_stop"
                                                                        {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $wednesdayStatus ? 'checked' : '' }}
                                                                        name="wednesday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $wednesdayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $wednesdayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $wednesdayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $wednesdayStatus ? $wednesdayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                        <!-- Thursday -->
                                                        @php
                                                            $thursday = $weeklySchedule['thursday'] ?? null;
                                                            $thursdayOpen = $thursday
                                                                ? $thursday->opening_time
                                                                : '09:30';
                                                            $thursdayClose = $thursday
                                                                ? $thursday->closing_time
                                                                : '18:30';
                                                            $thursdayLunchStart = $thursday
                                                                ? $thursday->lunch_time_start
                                                                : '12:00';
                                                            $thursdayLunchStop = $thursday
                                                                ? $thursday->lunch_time_stop
                                                                : '13:00';
                                                            $thursdayStatus = $thursday
                                                                ? $thursday->status == 'open'
                                                                : true;
                                                            $thursdayHours = $thursday
                                                                ? $thursday->working_hours . ' hours'
                                                                : '9 hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$thursdayStatus ? 'closed-day' : '' }}"
                                                            data-day="thursday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $thursdayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$thursdayStatus ? 'text-muted' : '' }}">Thursday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $thursdayOpen }}"
                                                                        name="thursday_open"
                                                                        {{ !$thursdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $thursdayClose }}"
                                                                        name="thursday_close"
                                                                        {{ !$thursdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $thursdayLunchStart }}"
                                                                        name="thursday_lunch_start"
                                                                        {{ !$thursdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $thursdayLunchStop }}"
                                                                        name="thursday_lunch_stop"
                                                                        {{ !$thursdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $thursdayStatus ? 'checked' : '' }}
                                                                        name="thursday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $thursdayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $thursdayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $thursdayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $thursdayStatus ? $thursdayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                        <!-- Friday -->
                                                        @php
                                                            $friday = $weeklySchedule['friday'] ?? null;
                                                            $fridayOpen = $friday ? $friday->opening_time : '10:00';
                                                            $fridayClose = $friday ? $friday->closing_time : '17:00';
                                                            $fridayLunchStart = $friday
                                                                ? $friday->lunch_time_start
                                                                : '12:00';
                                                            $fridayLunchStop = $friday
                                                                ? $friday->lunch_time_stop
                                                                : '13:00';
                                                            $fridayStatus = $friday ? $friday->status == 'open' : true;
                                                            $fridayHours = $friday
                                                                ? $friday->working_hours . ' hours'
                                                                : '7
                                                    hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$fridayStatus ? 'closed-day' : '' }}"
                                                            data-day="friday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $fridayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$fridayStatus ? 'text-muted' : '' }}">Friday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $fridayOpen }}" name="friday_open"
                                                                        {{ !$fridayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $fridayClose }}"
                                                                        name="friday_close"
                                                                        {{ !$fridayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $fridayLunchStart }}"
                                                                        name="friday_lunch_start"
                                                                        {{ !$fridayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $fridayLunchStop }}"
                                                                        name="friday_lunch_stop"
                                                                        {{ !$fridayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $fridayStatus ? 'checked' : '' }}
                                                                        name="friday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $fridayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $fridayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $fridayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $fridayStatus ? $fridayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                        <!-- Saturday -->
                                                        @php
                                                            $saturday = $weeklySchedule['saturday'] ?? null;
                                                            $saturdayOpen = $saturday
                                                                ? $saturday->opening_time
                                                                : '10:00';
                                                            $saturdayClose = $saturday
                                                                ? $saturday->closing_time
                                                                : '14:00';
                                                            $saturdayLunchStart = $saturday
                                                                ? $saturday->lunch_time_start
                                                                : '12:00';
                                                            $saturdayLunchStop = $saturday
                                                                ? $saturday->lunch_time_stop
                                                                : '13:00';
                                                            $saturdayStatus = $saturday
                                                                ? $saturday->status == 'open'
                                                                : false;
                                                            $saturdayHours = $saturday
                                                                ? $saturday->working_hours . ' hours'
                                                                : '0 hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$saturdayStatus ? 'closed-day' : '' }}"
                                                            data-day="saturday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $saturdayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$saturdayStatus ? 'text-muted' : '' }}">Saturday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $saturdayOpen }}"
                                                                        name="saturday_open"
                                                                        {{ !$saturdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $saturdayClose }}"
                                                                        name="saturday_close"
                                                                        {{ !$saturdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $saturdayLunchStart }}"
                                                                        name="saturday_lunch_start"
                                                                        {{ !$saturdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $saturdayLunchStop }}"
                                                                        name="saturday_lunch_stop"
                                                                        {{ !$saturdayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $saturdayStatus ? 'checked' : '' }}
                                                                        name="saturday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $saturdayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $saturdayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $saturdayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $saturdayStatus ? $saturdayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>

                                                        <!-- Sunday -->
                                                        @php
                                                            $sunday = $weeklySchedule['sunday'] ?? null;
                                                            $sundayOpen = $sunday ? $sunday->opening_time : '11:00';
                                                            $sundayClose = $sunday ? $sunday->closing_time : '15:00';
                                                            $sundayLunchStart = $sunday
                                                                ? $sunday->lunch_time_start
                                                                : '12:00';
                                                            $sundayLunchStop = $sunday
                                                                ? $sunday->lunch_time_stop
                                                                : '13:00';
                                                            $sundayStatus = $sunday ? $sunday->status == 'open' : false;
                                                            $sundayHours = $sunday
                                                                ? $sunday->working_hours . ' hours'
                                                                : '0
                                                    hours';
                                                        @endphp
                                                        <tr class="schedule-row {{ !$sundayStatus ? 'closed-day' : '' }}"
                                                            data-day="sunday">
                                                            <td class="py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="ti ti-calendar {{ $sundayStatus ? 'text-primary' : 'text-muted' }} me-2"></i>
                                                                    <span
                                                                        class="fw-medium {{ !$sundayStatus ? 'text-muted' : '' }}">Sunday</span>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control opening-time"
                                                                        value="{{ $sundayOpen }}" name="sunday_open"
                                                                        {{ !$sundayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control closing-time"
                                                                        value="{{ $sundayClose }}"
                                                                        name="sunday_close"
                                                                        {{ !$sundayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-start-time"
                                                                        value="{{ $sundayLunchStart }}"
                                                                        name="sunday_lunch_start"
                                                                        {{ !$sundayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="position-relative">
                                                                    <input type="time"
                                                                        class="form-control lunch-end-time"
                                                                        value="{{ $sundayLunchStop }}"
                                                                        name="sunday_lunch_stop"
                                                                        {{ !$sundayStatus ? 'disabled' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input status-toggle"
                                                                        type="checkbox"
                                                                        {{ $sundayStatus ? 'checked' : '' }}
                                                                        name="sunday_status">
                                                                    <label
                                                                        class="form-check-label status-label {{ $sundayStatus ? 'text-success' : 'text-muted' }}">
                                                                        {{ $sundayStatus ? 'Open' : 'Closed' }}
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-3">
                                                                <span
                                                                    class="total-hours {{ $sundayStatus ? 'text-primary fw-medium' : 'text-muted' }}">
                                                                    {{ $sundayStatus ? $sundayHours : '0 hours' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex justify-content-end gap-2 mt-4">

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-device-floppy me-1"></i>Save Schedule
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Locations Tab -->
                            <div class="tab-pane fade" id="locations" role="tabpanel" aria-labelledby="locations-tab">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5><i class="ph-duotone ph-map-pin me-2"></i>Company Locations</h5>
                                            <p class="text-muted mb-0">Manage your company's multiple locations</p>
                                        </div>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#locationModal">
                                            <i class="ti ti-plus me-1"></i>Add Location
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <!-- Location Statistics -->
                                        <div class="row mb-4 g-4">
                                            <!-- Total Locations -->
                                            <div class="col-md-3">
                                                <div
                                                    class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                    <div class="card-body text-center p-4 position-relative"
                                                        style="z-index: 1;">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                            style="width: 70px; height: 70px;">
                                                            <i class="ti ti-map-pin fa-2x text-primary"></i>
                                                        </div>
                                                        <h1 class="display-3 fw-bold text-primary mb-2">
                                                            {{ $locations->count() }}</h1>
                                                        <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                            style="letter-spacing: 1.5px;">Total Locations</h6>
                                                        <span
                                                            class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">All
                                                            Offices</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Active Locations -->
                                            <div class="col-md-3">
                                                <div
                                                    class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                    <div class="card-body text-center p-4 position-relative"
                                                        style="z-index: 1;">
                                                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                            style="width: 70px; height: 70px;">
                                                            <i class="ti ti-circle-check fa-2x text-success"></i>
                                                        </div>
                                                        <h1 class="display-3 fw-bold text-success mb-2">
                                                            {{ $locations->where('status', 'Active')->count() }}</h1>
                                                        <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                            style="letter-spacing: 1.5px;">Active Locations</h6>
                                                        <span
                                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2">Operational</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Head Office -->
                                            <div class="col-md-3">
                                                <div
                                                    class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                    <div class="card-body text-center p-4 position-relative"
                                                        style="z-index: 1;">
                                                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                            style="width: 70px; height: 70px;">
                                                            <i class="ti ti-building fa-2x text-warning"></i>
                                                        </div>
                                                        <h1 class="display-3 fw-bold text-warning mb-2">
                                                            {{ $locations->where('location_type', 'Head Office')->count() }}
                                                        </h1>
                                                        <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                            style="letter-spacing: 1.5px;">Head Office</h6>
                                                        <span
                                                            class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Headquarters</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Branch Offices -->
                                            <div class="col-md-3">
                                                <div
                                                    class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                    <div class="card-body text-center p-4 position-relative"
                                                        style="z-index: 1;">
                                                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                            style="width: 70px; height: 70px;">
                                                            <i class="ti ti-building-community fa-2x text-info"></i>
                                                        </div>
                                                        <h1 class="display-3 fw-bold text-info mb-2">
                                                            {{ $locations->where('location_type', 'Branch Office')->count() }}
                                                        </h1>
                                                        <h6 class="text-uppercase fw-bold text-muted mb-1"
                                                            style="letter-spacing: 1.5px;">Branch Offices</h6>
                                                        <span
                                                            class="badge bg-info bg-opacity-10 text-info px-3 py-2">Regional</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Locations List -->
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="5%">Sr.</th>
                                                        <th width="25%">Location Name</th>
                                                        <th width="20%">Type</th>
                                                        <th width="25%">Geofencing Area</th>
                                                        <th width="15%">Status</th>
                                                        <th width="10%">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($locations->count() > 0)
                                                        @foreach ($locations as $index => $location)
                                                            @php
                                                                // Define icon and color based on location type
                                                                $iconClass = 'ti ti-building';
                                                                $iconColor = 'text-primary';
                                                                $badgeClass = 'bg-primary';

                                                                switch ($location->location_type) {
                                                                    case 'Head Office':
                                                                        $iconClass = 'ti ti-building';
                                                                        $iconColor = 'text-warning';
                                                                        $badgeClass = 'bg-warning';
                                                                        break;
                                                                    case 'Branch Office':
                                                                        $iconClass = 'ti ti-building-store';
                                                                        $iconColor = 'text-info';
                                                                        $badgeClass = 'bg-info';
                                                                        break;
                                                                    case 'Warehouse':
                                                                        $iconClass = 'ti ti-building-warehouse';
                                                                        $iconColor = 'text-secondary';
                                                                        $badgeClass = 'bg-secondary';
                                                                        break;
                                                                    case 'Sales Office':
                                                                        $iconClass = 'ti ti-building-bank';
                                                                        $iconColor = 'text-success';
                                                                        $badgeClass = 'bg-success';
                                                                        break;
                                                                    case 'Manufacturing Unit':
                                                                        $iconClass = 'ti ti-building-factory';
                                                                        $iconColor = 'text-danger';
                                                                        $badgeClass = 'bg-danger';
                                                                        break;
                                                                    case 'Service Center':
                                                                        $iconClass = 'ti ti-building-community';
                                                                        $iconColor = 'text-dark';
                                                                        $badgeClass = 'bg-dark';
                                                                        break;
                                                                    case 'Project Location':
                                                                        $iconClass = 'ti ti-map-pin';
                                                                        $iconColor = 'text-primary';
                                                                        $badgeClass = 'bg-primary';
                                                                        break;
                                                                }
                                                            @endphp

                                                            <tr
                                                                class="{{ $location->status == 'Inactive' ? 'table-secondary' : '' }}">
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <i
                                                                            class="{{ $iconClass }} {{ $location->status == 'Inactive' ? 'text-muted' : $iconColor }} me-2"></i>
                                                                        <div>
                                                                            <strong
                                                                                class="{{ $location->status == 'Inactive' ? 'text-muted' : '' }}">{{ $location->location_name }}</strong>
                                                                            <br><small
                                                                                class="text-muted">{{ $location->location_type }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge {{ $location->status == 'Inactive' ? 'bg-light text-dark' : $badgeClass }}">{{ $location->location_type }}</span>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <small
                                                                            class="{{ $location->status == 'Inactive' ? 'text-muted' : '' }}">
                                                                            <i
                                                                                class="ti ti-map-pin me-1 {{ $location->status == 'Inactive' ? 'text-muted' : 'text-primary' }}"></i>{{ $location->latitude }},
                                                                            {{ $location->longitude }}
                                                                        </small><br>
                                                                        <small class="text-muted">Radius:
                                                                            {{ $location->radius }}m</small>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge {{ $location->status == 'Active' ? 'bg-success' : 'bg-danger' }}">{{ $location->status }}</span>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group" role="group">
                                                                        <button class="btn btn-sm btn-outline-primary"
                                                                            onclick="editLocation({{ $location->id }})"
                                                                            title="Edit">
                                                                            <i class="ti ti-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-outline-danger"
                                                                            onclick="deleteLocation({{ $location->id }})"
                                                                            title="Delete">
                                                                            <i class="ti ti-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="6" class="text-center py-4">
                                                                <div class="mb-3">
                                                                    <i class="ti ti-map-pin-off"
                                                                        style="font-size: 3rem; color: #ccc;"></i>
                                                                </div>
                                                                <h6 class="text-muted">No Locations Found</h6>
                                                                <p class="text-muted mb-3">You haven't added any locations
                                                                    yet.
                                                                </p>
                                                                <button type="button" class="btn btn-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#locationModal">
                                                                    <i class="ti ti-plus me-1"></i>Add Your First Location
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endif
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
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

    <!-- GST Details Modal -->
    <div class="modal fade" id="gstDetailsModal" tabindex="-1" aria-labelledby="gstDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="gstDetailsModalLabel">GST Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="gst_modal_body">
                    <!-- GST details will be injected here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Add New Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="locationForm">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Location Name -->
                            <div class="col-md-6 mb-3">
                                <label for="locationName" class="form-label">Location Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="locationName" name="locationName"
                                    required>
                            </div>

                            <!-- Location Type -->
                            <div class="col-md-6 mb-3">
                                <label for="locationType" class="form-label">Location Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="locationType" name="locationType" required>
                                    <option value="">Select Type</option>
                                    <option value="Head Office">Head Office</option>
                                    <option value="Branch Office">Branch Office</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Sales Office">Sales Office</option>
                                    <option value="Manufacturing Unit">Manufacturing Unit</option>
                                    <option value="Service Center">Service Center</option>
                                    <option value="Project location">Project location</option>
                                </select>
                            </div>

                            <!-- Geofencing Location -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Geofencing Location <span class="text-danger">*</span></label>
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Latitude -->
                                            <div class="col-md-6 mb-3">
                                                <label for="latitude" class="form-label">Latitude <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="latitude"
                                                    name="latitude" step="any" placeholder="e.g., 19.0760"
                                                    required>
                                                <small class="text-muted">Enter latitude coordinates</small>
                                            </div>

                                            <!-- Longitude -->
                                            <div class="col-md-6 mb-3">
                                                <label for="longitude" class="form-label">Longitude <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="longitude"
                                                    name="longitude" step="any" placeholder="e.g., 72.8777"
                                                    required>
                                                <small class="text-muted">Enter longitude coordinates</small>
                                            </div>

                                            <!-- Radius -->
                                            <div class="col-md-6 mb-3">
                                                <label for="radius" class="form-label">Geofence Radius (meters) <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="radius"
                                                    name="radius" min="10" max="5000"
                                                    placeholder="e.g., 500" required>
                                                <small class="text-muted">Radius between 10m to 5000m</small>
                                            </div>

                                            <!-- Get Current Location Button -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Quick Actions</label>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="getCurrentLocation()">
                                                        <i class="ti ti-current-location me-1"></i>Get Current Location
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        onclick="openMapPicker()">
                                                        <i class="ti ti-map me-1"></i>Pick on Map
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location Preview -->
                                        <div class="alert alert-info mb-0">
                                            <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i>Location
                                                Preview
                                            </h6>
                                            <p class="mb-1" id="locationPreview">
                                                <strong>Coordinates:</strong> <span id="previewCoords">Not set</span><br>
                                                <strong>Geofence Area:</strong> <span id="previewRadius">Not set</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12 mb-3">
                                <label for="locationStatus" class="form-label">Status</label>
                                <select class="form-select" id="locationStatus" name="status">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>Save Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Map Picker Modal -->
    <div class="modal fade" id="mapPickerModal" tabindex="-1" aria-labelledby="mapPickerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapPickerModalLabel">
                        <i class="ti ti-map me-2"></i>Select Location on Map
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Map Container -->
                            <div id="mapContainer"
                                style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 8px;">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading map...</span>
                                        </div>
                                        <p class="mt-2 text-muted">Loading map...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Map Instructions -->
                            <div class="alert alert-info mt-3">
                                <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i>How to use:</h6>
                                <ul class="mb-0">
                                    <li>Click anywhere on the map to select a location</li>
                                    <li>The red marker shows your selected position</li>
                                    <li>Use the search box to find specific addresses</li>
                                    <li>Zoom in/out using mouse wheel or controls</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Search Box -->
                            <div class="mb-3">
                                <label class="form-label">Search Location</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="mapSearchInput"
                                        placeholder="Enter address or place name">
                                    <button class="btn btn-outline-primary" type="button" onclick="searchLocation()">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Selected Coordinates -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="ti ti-target me-1"></i>Selected Coordinates</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="form-label text-sm">Latitude</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="selectedLatitude" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-sm">Longitude</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="selectedLongitude" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-sm">Address</label>
                                        <textarea class="form-control form-control-sm" id="selectedAddress" rows="3" readonly></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="ti ti-zap me-1"></i>Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <button type="button" class="btn btn-outline-success btn-sm w-100 mb-2"
                                        onclick="getCurrentLocationOnMap()">
                                        <i class="ti ti-current-location me-1"></i>Use My Current Location
                                    </button>
                                    <button type="button" class="btn btn-outline-info btn-sm w-100"
                                        onclick="centerMapToIndia()">
                                        <i class="ti ti-map-pin me-1"></i>Center to India
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="useSelectedLocation()" disabled
                        id="useLocationBtn">
                        <i class="ti ti-check me-1"></i>Use This Location
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Director Modal -->
    <div class="modal fade" id="addDirectorModal" tabindex="-1" aria-labelledby="addDirectorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addDirectorModalLabel">
                        <i class="ph-duotone ph-user-plus me-2"></i>Add Director Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addDirectorForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="director_name" name="director_name" required placeholder="Enter Director Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="director_phone" name="director_phone" required placeholder="Enter Phone Number" pattern="[0-9]{10}" maxlength="10">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="director_designation" name="director_designation" required placeholder="Enter Designation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DIN Number</label>
                                <input type="text" class="form-control" id="director_din" name="director_din" placeholder="Enter DIN Number" maxlength="8">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="director_email" name="director_email" required placeholder="Enter Email Address">
								<span class="text-danger error-text director_email_error"></span>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Upload Signature</label>
                                <input type="file" class="form-control" id="director_signature" name="director_signature" accept="image/*">
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF (Max 2MB)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>Save Director
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Director Details Modal -->
    <div class="modal fade" id="viewDirectorModal" tabindex="-1" aria-labelledby="viewDirectorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="viewDirectorModalLabel">
                        <i class="ph-duotone ph-user-circle" style="margin-right: 8px;"></i>View Director Details
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
	
	<div class="modal fade" id="proprietorshipModal">
		<div class="modal-dialog">
			<form id="proprietorshipForm">
				@csrf
				<div class="modal-content">
					<div class="modal-header">
						<h5>Enter Proprietorship Company Name</h5>
					</div>
					<div class="modal-body">
						<input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name" required>
						<div id="companyNameError" class="text-danger mt-2"></div>
						
						<small class="mt-2 d-block" style="color:#003399; font-weight:500;">
							Note: You can enter multiple company names for <b>Proprietorship</b>. 
							Simply submit the form again to add another company.
						</small>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="saveProprietorship">
							Submit
						</button>
					</div>
				</div>

			</form>
		</div>
	</div>
	
	<div class="modal custom-modal fade" id="add-bank-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add New Bank</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="addBankForm" method="post" name="addBankFrm" enctype="multipart/form-data">
					<input type="hidden" name="id" id="bankId" value="">
					<input type="hidden" name="redirectUrl" value="company-profile">
					<div class="modal-body">

						<!-- Bank Name -->
						<div class="row">
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">Bank Name<span class="text-danger">*</span></label>
								<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter Bank Name" required>
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">Branch<span class="text-danger">*</span></label>
								<input type="text" name="bank_branch" id="bank_branch" class="form-control" placeholder="Enter Branch" required>
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">Account Name<span class="text-danger">*</span></label>
								<input type="text" name="accholder_name" id="accholder_name" class="form-control" placeholder="Enter Account Name" required>
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">Account Number<span class="text-danger">*</span></label>
								<input type="text" name="bank_ac_no" id="bank_ac_no" class="form-control" placeholder="Enter Account Number" required>
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">IFSC Code<span class="text-danger">*</span></label>
								<input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="Enter IFSC Code" required>
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">Swift Code</label>
								<input type="text" name="swift_code" id="swift_code" class="form-control" placeholder="Enter Swift Code" >
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">UPI ID</label>
								<input type="text" name="upi_id" id="upi_id" class="form-control" placeholder="Enter UPI ID" >
							</div>
							<div class="col-md-4  mb-3">
								<label for="bankName" class="form-label">Current Bank Balance<span class="text-danger">*</span></label>
								<input type="text" name="curr_bal" id="curr_bal" class="form-control" placeholder="Enter Current Bank Balance" required>
							</div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Bank QR Code
                                </label>
                                <input type="file"  name="bank_qr_code" id="bank_qr_code" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            </div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Add Bank Account</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!-- Delete Confirmation Modal -->
	<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-md">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-header text-center">
						<h3>Delete Bank</h3>
						<p>Are you sure you want to delete?</p>
					</div>
					<div class="modal-btn delete-action">
						<div class="row">
							<div class="col-6">
								<button type="button" id="confirmDelete" class="w-100 btn btn-primary">
									Delete
								</button>
							</div>
							<div class="col-6">
								<button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
									Cancel
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function updateFileName(input, labelId) {
            const label = document.getElementById(labelId);
            if (input.files && input.files.length > 0) {
                // Update the text to show the selected file name
                label.innerHTML = `<i class="fa fa-file text-primary"></i> Selected: ${input.files[0].name}`;
            }
        }
    </script>
	@php
		$userMenu = $userFeatures;
		//echo "<pre>";print_r($userMenu);exit;
		$hasPermission = in_array('ALL', $userMenu) || in_array('Multi Proprietorships', $userMenu);
	@endphp
	
    <script>	
		var hasProprietorshipAccess = @json($hasPermission);
		
		$(document).ready(function(){

			$('#comp_type').change(function(){
				let selected = $(this).val();
				if(selected === 'Proprietorship' && hasProprietorshipAccess){
					//$('#proprietorshipModal').modal('show');
				}
			});

		});
		
		$('#saveProprietorship').click(function(){
			$("#loader").show();
			$.ajax({
				url: "{{ route('check.proprietorship.company') }}",
				type: "POST",
				data: $('#proprietorshipForm').serialize(),
				success:function(response){
					$("#loader").hide();
					if(response.status == 'exists'){
						$('#companyNameError').text(response.message);
					}

					if(response.status == 'success'){
						$('#proprietorshipModal').modal('hide');
						$('#company_name').val('');
					}

				},
				error:function(xhr){
					$("#loader").hide();
					let errors = xhr.responseJSON.errors;
					if(errors.company_name){
						$('#companyNameError').text(errors.company_name[0]);
					}
				}
			});
		});
     
        // GST Number Yes or No
        document.addEventListener("DOMContentLoaded", function() {

            //---------- Modal js -----------

            const termsLink = document.getElementById('termsLink');
            const termsModal = new bootstrap.Modal(document.getElementById('termsModal'));

            termsLink.addEventListener('click', function() {
                termsModal.show();
            });


            // GST Registration dropdown handler
            const gstDropdown = document.getElementById("gst_reg");
            if (gstDropdown) {
                const gstNumberField = document.getElementById("gst_reg_no");
                const gstTransactionField = document.getElementById("gst_reg_tran");
                const gstTypeDisplay = document.getElementById("gst_type_display");
                const billAddressView = document.getElementById("bill_address_view");
                const shipAddressView = document.getElementById("ship_address_view");

                function toggleGSTFields(selectedValue) {
                    const valueLower = selectedValue.toLowerCase();

                    if (valueLower === "yes") {
                        if (gstNumberField) gstNumberField.style.display = "block";
                        if (gstTransactionField) gstTransactionField.style.display = "block";
                        if (billAddressView) billAddressView.style.display = "block";
                        if (shipAddressView) shipAddressView.style.display = "block";
                        if (gstTypeDisplay) gstTypeDisplay.textContent = "B2B";
                    } else if (valueLower === "no") {
                        if (gstNumberField) gstNumberField.style.display = "none";
                        if (gstTransactionField) gstTransactionField.style.display = "none";
                        if (billAddressView) billAddressView.style.display = "none";
                        if (shipAddressView) shipAddressView.style.display = "none";
                        if (gstTypeDisplay) gstTypeDisplay.textContent = "B2C";
                    } else {
                        if (gstNumberField) gstNumberField.style.display = "none";
                        if (gstTransactionField) gstTransactionField.style.display = "none";
                        if (billAddressView) billAddressView.style.display = "none";
                        if (shipAddressView) shipAddressView.style.display = "none";
                        if (gstTypeDisplay) gstTypeDisplay.textContent = "";
                    }
                }

                // Initial load
                toggleGSTFields(gstDropdown.value);

                // Change event
                gstDropdown.addEventListener("change", function() {
                    toggleGSTFields(this.value);
                });
            }


            $(document).ready(function() {
                
                const billingState = "{{ $compDetails->comp_bill_state ?? '' }}";
                const billingCity = "{{ $compDetails->comp_bill_city ?? '' }}";
                const shippingState = "{{ $compDetails->comp_ship_state ?? '' }}";
                const shippingCity = "{{ $compDetails->comp_ship_city ?? '' }}";

                // console.log('Initial values:', {
                //     billingState: billingState,
                //     billingCity: billingCity,
                //     shippingState: shippingState,
                //     shippingCity: shippingCity
                // });

                // Load billing cities if billing state is set
                if (billingState) {
                    $.ajax({
                        url: "{{ url('/getCity') }}",
                        type: "GET",
                        dataType: "json",
                        data: {
                            id: billingState
                        },
                        success: function(data) {
                            // console.log('Initial billing cities loaded:', data);
                            let str = '<option value="">Select City</option>';
                            $.each(data, function(idx, item) {
                                str += '<option value="' + item.id + '"' +
                                    (item.id == billingCity ? " selected" : "") + '>' +
                                    item.name + '</option>';
                            });
                            $("#billing-city").html(str);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading initial billing cities:', error);
                        }
                    });
                }

                // Load shipping cities if shipping state is set
                if (shippingState) {
                    $.ajax({
                        url: "{{ url('/getCity') }}",
                        type: "GET",
                        dataType: "json",
                        data: {
                            id: shippingState
                        },
                        success: function(data) {
                            // console.log('Initial shipping cities loaded:', data);
                            let str = '<option value="">Select City</option>';
                            $.each(data, function(idx, item) {
                                str += '<option value="' + item.id + '"' +
                                    (item.id == shippingCity ? " selected" : "") + '>' +
                                    item.name + '</option>';
                            });
                            $("#shipping-city").html(str);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading initial shipping cities:', error);
                        }
                    });
                }
            });

            //-------------- Fetch City for billing -----------

            const stateDropdown = document.getElementById('state');
            stateDropdown.addEventListener('change', function() {
                const id = this.value; // Get the selected value
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
                            $("#billing-city").empty();
                            var str = '<option value="">Select City</option>';
                            $.each(data, function(idx, item) {
                                str +=
                                    '<option value="' +
                                    item.id +
                                    '">' +
                                    item.name +
                                    "</option>";
                            });
                            $("#billing-city").html(str);
                        },
                    });
                } else {
                    $("#billing-city").html('<option value="">Select City</option>');
                }
            });

            //- ------------- Fetch City for shipping -----------

            const stateDropdownShipping = document.getElementById('shipping-state');
            stateDropdownShipping.addEventListener('change', function() {
                const id = this.value; // Get the selected value
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
                            $("#shipping-city").empty();
                            var str = '<option value="">Select City</option>';
                            $.each(data, function(idx, item) {
                                str +=
                                    '<option value="' +
                                    item.id +
                                    '">' +
                                    item.name +
                                    "</option>";
                            });
                            $("#shipping-city").html(str);
                        },
                    });
                } else {
                    $("#shipping-city").html('<option value="">Select City</option>');
                }
            });

            //----------- Form Submit Company Profile ----------
            $("form#frmcompdet").bind("submit", function(e) {

                e.preventDefault();
                const gstReg = $("#frmcompdet #gst_reg").val();
                const gstNo = $("#gst_no").val().trim();
                const tranType = $("#comp_tran_type").val();
                const compName = $("#frmcompdet #comp_name").val();
                const compPan = $("#frmcompdet #comp_pan_no").val();
                // const setBasicSalary = $("#frmcompdet #basic_percentage").val();

                const compType = $("#frmcompdet #comp_type").val();
                const cin = ($("#frmcompdet #cin").val() || $("#frmcompdet #llpin").val() || $("#frmcompdet #reg_no").val() || '').trim();
                const incDate = $("#frmcompdet #inc_date").val();
                const otherComp = $("#frmcompdet #other_comp").val().trim();

                // 🔴 GST Registered required
                if (!gstReg) {
                    showToast("Please select Company GST Registered!", "error");
                    $("#frmcompdet #gst_reg").focus();
                    return false; // stop ajax
                }

                 // 🔴 If GST = Yes → validate GST No & Transaction Type
                if (gstReg === "Yes") {
                    if (!gstNo) {
                        $("#gstNoError").show();
                        $("#gst_no").focus();
                        showToast("GST Number is required!", "error");
                        return false;
                    } else {
                        $("#gstNoError").hide();
                    }

                    if (!tranType) {
                        $("#tranTypeError").show();
                        $("#comp_tran_type").focus();
                        showToast("GST Transaction Type is required!", "error");
                        return false;
                    } else {
                        $("#tranTypeError").hide();
                    }
                }

                // Company Name Required
                if (!compName) {
                    showToast("Please Type The Company Name", "error");
                    $("#frmcompdet #comp_name").focus();
                    return false; // stop ajax
                }

                // Company Pan No required 
                if (!compPan) {
                    showToast("Please Type The Company Pan No", "error");
                    $("#frmcompdet #comp_pan_no").focus();
                    return false; // stop ajax
                }

                // Set Basic Salary Percentage
                // if (!setBasicSalary) {
                //     showToast("Please Set The Basic salary Percentage", "error");
                //     $("#frmcompdet #basic_percentage").focus();
                //     return false; // stop ajax
                // }

                // 🔴 Company Type conditional validation
                const cinRequiredTypes = [
                    "One person Company (OPC)",
                    "LLP Company",
                    "PVT Ltd Company",
                    "LTD Company",
                    "Section-8 Company"
                ];

                // if (cinRequiredTypes.includes(compType)) {
                //     if (!cin || !incDate) {
                //         showToast("CIN and Incorporation Date are required!", "error");
                //         return false;
                //     }
                // }
                if (compType === "Other" && !otherComp) {
                    showToast("Please enter Custom Company Type!", "error");
                    $("#frmcompdet #other_comp").focus();
                    return false;
                }



                // $("#loader").show();
                var formCompData = {
                    gst_reg: gstReg,
                    gst_no: gstNo,
                    comp_tran_type: tranType,
                    comp_name: compName,
                    comp_type: compType,
                    cin: cin,
                    inc_date: incDate,
                    comp_tan: $("#frmcompdet #comp_tan").val(),
                    comp_epf: $("#frmcompdet #comp_epf").val(),
                    comp_esic: $("#frmcompdet #comp_esic").val(),
                    comp_ptax_cert: $("#frmcompdet #comp_ptax_cert").val(),
                    comp_ptax: $("#frmcompdet #comp_ptax").val(),
                    //comp_phone: $("#frmcompdet #comp_phone").val(),
                    //comp_email: $("#frmcompdet #comp_email").val(),
                    comp_pan_no: compPan,
                    //comp_website: $("#frmcompdet #comp_website").val(),
                    // other_comp_type: $("#frmcompdet #other_comp").val(),
                    other_comp_type: otherComp,
                    // basic_percentage: setBasicSalary,

                    comp_bill_gst_no: $("#frmcompdet #comp_bill_gst_no").val(),
                    comp_bill_name: $("#frmcompdet #comp_bill_name").val(),
                    comp_bill_cont_name: $(
                        "#frmcompdet #comp_bill_cont_name"
                    ).val(),
                    comp_bill_mobile_no: $(
                        "#frmcompdet #comp_bill_mobile_no"
                    ).val(),
                    comp_bill_addone: $("#frmcompdet #comp_bill_addone").val(),
                    comp_bill_addtwo: $("#frmcompdet #comp_bill_addtwo").val(),
                    comp_bill_country: $(
                        "#frmcompdet #country option:selected"
                    ).val(),
                    comp_bill_state: $("#frmcompdet #state option:selected").val(),
                    comp_bill_city: $("#frmcompdet #billing-city option:selected").val(),
                    comp_bill_pin: $("#frmcompdet #comp_bill_pin").val(),

                    comp_ship_gst_no: $("#frmcompdet #comp_ship_gst_no").val(),
                    comp_ship_name: $("#frmcompdet #comp_ship_name").val(),
                    comp_ship_cont_name: $(
                        "#frmcompdet #comp_ship_cont_name"
                    ).val(),
                    comp_ship_mobile_no: $(
                        "#frmcompdet #comp_ship_mobile_no"
                    ).val(),
                    comp_ship_addone: $("#frmcompdet #comp_ship_addone").val(),
                    comp_ship_addtwo: $("#frmcompdet #comp_ship_addtwo").val(),
                    comp_ship_country: $(
                        "#frmcompdet #country_ship option:selected"
                    ).val(),
                    comp_ship_state: $(
                        "#frmcompdet #shipping-state option:selected"
                    ).val(),
                    comp_ship_city: $(
                        "#frmcompdet #shipping-city option:selected"
                    ).val(),
                    comp_ship_pin: $("#frmcompdet #comp_ship_pin").val(),

                    // Add Udyam Registration data here
                    udyam_reg_no: $("#frmcompdet #udyam_registration_no").val(),
                    trade_license_no: $("#frmcompdet #trade_license_no").val(),
                    shop_establishment_no: $("#frmcompdet #shop_establishment_no").val(),
                    fema_iec_no: $("#frmcompdet #fema_iec_no").val(),
                    state_excise_no: $("#frmcompdet #state_excise_no").val(),
                    
                };

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_compdet",
                    type: "POST",
                    data: formCompData,
                    success: function(response) {
                        var messageContainer = $("#frmcompdet .message-container");
                        messageContainer.html("");



                        if (response.class == "succ") {
                            showToast("Profile Information updated successfully!", "success");
                            setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Upload failed: " + response.message, "error");
                        }

                        // Hide message container after 3 seconds if there are messages
                        if (messageContainer.children().length > 0) {
                            setTimeout(function() {
                                messageContainer.hide("slow");
                            }, 3000);
                        }
                    },
                });

            });

            //------------


            document.getElementById("sameAsBilling").addEventListener("click", function() {
                const billingGST = document.getElementById("comp_bill_gst_no").value;
                const billingName = document.getElementById("comp_bill_name").value;
                const billingAddOne = document.getElementById("comp_bill_addone").value;
                const billingAddTwo = document.getElementById("comp_bill_addtwo").value;
                const billingState = document.getElementById("state").value;
                const billingCity = document.getElementById("billing-city").value;
                const billingPin = document.getElementById("comp_bill_pin").value;

                // Copy the values
                document.getElementById("comp_ship_gst_no").value = billingGST;
                document.getElementById("comp_ship_name").value = billingName;
                document.getElementById("comp_ship_addone").value = billingAddOne;
                document.getElementById("comp_ship_addtwo").value = billingAddTwo;
                document.getElementById("shipping-state").value = billingState;
                document.getElementById("comp_ship_pin").value = billingPin;

                // Fetch cities for shipping state, then select the correct city
                if (billingState) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                    });
                    $.ajax({
                        url: "/getCity?id=" + billingState,
                        dataType: "json",
                        data: {
                            id: billingState
                        },
                        success: function(data) {
                            let str = '<option value="">Select City</option>';
                            $.each(data, function(idx, item) {
                                str +=
                                    '<option value="' +
                                    item.id +
                                    '"' +
                                    (item.id == billingCity ? " selected" : "") +
                                    ">" +
                                    item.name +
                                    "</option>";
                            });
                            $("#shipping-city").html(str);
                        },
                    });
                }
            });

            //Start company business details


            $("form#frmbusdet").bind("submit", function (e) {
                e.preventDefault(); // stop normal submit

                var compNature = $('input[name="comp_nature"]:checked').val();
                var exactNature = $("#frmbusdet #exact_comp_nature").val().trim();

                // 👉 validation only for these 2 fields
                if (!compNature) {
                    showToast("Please select Category Of Business", "error");
                    return false;
                }

                if (exactNature === "") {
                    showToast("Please enter Exact Nature of Business", "error");
                    return false;
                }
                var formCompBusData = {
                    comp_nature: compNature,
                    exact_comp_nature: exactNature,
                    turnover_last_year: $("#frmbusdet #turnover_last_year").val(),
                    opening_balance: $("#frmbusdet #opening_balance").val(),
                    openingbalancecr: $("#frmbusdet #openingbalancecr").val(),
                    openingbalancedr: $("#frmbusdet #openingbalancedr").val(),
                    start_date: $("#frmbusdet #start_date").val(),
					comp_quo_digits: $("#frmbusdet #comp_quo_digits").val(),
					comp_prof_digits: $("#frmbusdet #comp_prof_digits").val(),
					comp_inv_digits: $("#frmbusdet #comp_inv_digits").val(),
					comp_po_digits: $("#frmbusdet #comp_po_digits").val(),
                };

                $("#loader").show();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_businessdet",
                    type: "POST",
                    data: formCompBusData,
                    success: function(response) {
                        $("#loader").hide();
                        var messageContainer = $("#frmbusdet .message-container");
                        messageContainer.html("");
                        if (response.class == "succ") {
                            // showToast("Profile picture updated successfully!", "success");
                            showToast(response.message, "success");
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Business Details Update: " + response.message, "error");
                        }

                        // Hide message container after 3 seconds if there are messages
                        if (messageContainer.children().length > 0) {
                            setTimeout(function() {
                                messageContainer.hide("slow");
                            }, 3000);
                        }
                    },
                });

            });
			//-------update contact details
			$("form#frmcontdet").bind("submit", function(e) {
                e.preventDefault();

                const compPhone = $("#frmcontdet #comp_phone").val().trim();
                const compEmail = $("#frmcontdet #comp_email").val().trim();
                const whatsappNo = $("#frmcontdet #whatsapp_no").val().trim();

                // 🔴 Company Phone required
                if (!compPhone) {
                    showToast("Please enter Company Contact No", "error");
                    $("#comp_phone").focus();
                    return false;
                }

                // 🔴 Company Email required
                if (!compEmail) {
                    showToast("Please enter Company Mail ID", "error");
                    $("#comp_email").focus();
                    return false;
                }

                // 🔴 WhatsApp No required
                if (!whatsappNo) {
                    showToast("Please enter WhatsApp No", "error");
                    $("#whatsapp_no").focus();
                    return false;
                }
                $("#loader").show();
                var formContactData = {
                    comp_phone: compPhone,
                    comp_email: compEmail,
                    whatsapp_no: whatsappNo,
                    comp_website: $("#frmcontdet #comp_website").val()
                };
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_contactDetails",
                    type: "POST",
                    data: formContactData,
                    success: function(response) {
						$("#loader").hide();
                        var messageContainer = $("#frmcontdet .message-container");
                        messageContainer.html("");
                        if (response.class == "succ") {
                            showToast("Contact Information updated successfully!", "success");
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Upload failed: " + response.message, "error");
                        }
                        if (messageContainer.children().length > 0) {
                            setTimeout(function() {
                                messageContainer.hide("slow");
                            }, 3000);
                        }
                    },
                });

            });
			
			//------Add/Edit/View Director details
			$('#addDirectorForm').submit(function(e){
				e.preventDefault();
				let formData = new FormData(this);
				$("#loader").show();
				$.ajax({
					url: '/directors/store',
					method: "POST",
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					success: function(res){
						$("#loader").hide();
						if(res.success){
							showToast('Director added successfully!', 'success');
							$('#addDirectorModal').modal('hide');
							$('#addDirectorForm')[0].reset();
							location.reload();
						}
					},
					error: function(xhr){
						$("#loader").hide();

						if(xhr.status === 422){
							let errors = xhr.responseJSON.errors;

							$.each(errors, function(key, value){
								$('.' + key + '_error').text(value[0]);
							});

							// optional toast
							//showToast('Please fix the highlighted errors', 'error');
						}
					}
				});
			});
			
			$(document).on('click', '.deleteBtn', function() {
				let id = $(this).data('id');

				if (!confirm("Are you sure you want to delete this director?")) {
					return;
				}

                $("#loader").show();
				$.ajax({
					url: "/director/delete/" + id,
					type: "DELETE",
					data: {
						_token: "{{ csrf_token() }}"
					},
					success: function(response) {
                        $("#loader").hide();
						if (response.success) {
							$("#row_" + id).remove();  // Remove row immediately
							alert("Deleted Successfully");
						} else {
							alert("Delete Failed");
						}
					},
					error: function() {
                        $("#loader").hide();
						alert("Something went wrong!");
					}
				});
			});





            //-------- Bank Details add --------
            $("form#frmbankdet").on("submit", function(e) {
                e.preventDefault();

                var formCompBank = $("form#frmbankdet").serialize();
                $("#loader").show();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_bankdet",
                    type: "POST",
                    data: formCompBank,
                    success: function(response) {
                        $("#loader").hide();
                        var messageContainer = $("#frmbankdet .message-container");
                        messageContainer.html(""); // Clear previous messages

                        if (response.class == "succ") {
                            showToast(response.message, "success");
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Update failed: " + response.message, "error");
                        }

                        // if (response.class == "succ") {
                        //     messageContainer
                        //         .html(
                        //             '<div class="' +
                        //                 response.class +
                        //                 '">' +
                        //                 response.message +
                        //                 "</div>"
                        //         )
                        //         .show();
                        // } else {
                        //     $("#loader").hide();
                        //     $.each(response, function (idx, obj) {
                        //         messageContainer.append(
                        //             '<div class="err">' + obj + "</div>"
                        //         );
                        //     });
                        // }

                        // Hide message container after 3 seconds if there are messages
                        if (messageContainer.children().length > 0) {
                            setTimeout(function() {
                                messageContainer.hide("slow");
                            }, 3000);
                        }
                    },

                });

            });

            //--------- Upload Document -----------

            $("form#frmattadet").bind("submit", function() {
                //if (frmattadet.form()) {

                if (!document.getElementById('checkbox').checked) {
                    // Prevent the form from submitting
                    event.preventDefault();

                    // Show the toast message
                    showToast("You must agree to Terms and Conditions.", "error");
                    return false;
                }

                let inc_certificate = $("#frmattadet #inc_certificate").prop(
                    "files"
                )[0];
                let pan_doc = $("#frmattadet #pan_doc").prop("files")[0];
                let gst_doc = $("#frmattadet #gst_doc").prop("files")[0];
                let trade_doc = $("#frmattadet #trade_doc").prop("files")[0];
                let pf_doc = $("#frmattadet #pf_doc").prop("files")[0];
                let ptex_doc = $("#frmattadet #ptex_doc").prop("files")[0];
                let first_diraadh_doc = $("#frmattadet #first_diraadh_doc").prop(
                    "files"
                )[0];
                let firstpan_doc = $("#frmattadet #firstpan_doc").prop("files")[0];
                let first_dirphoto_doc = $("#frmattadet #first_dirphoto_doc").prop(
                    "files"
                )[0];
                let second_aadha_doc = $("#frmattadet #second_aadha_doc").prop(
                    "files"
                )[0];
                let second_pan_doc = $("#frmattadet #second_pan_doc").prop("files")[0];
                let second_dirphoto_doc = $("#frmattadet #second_dirphoto_doc").prop(
                    "files"
                )[0];
                let other_logo_doc = $("#frmattadet #other_logo_doc").prop("files")[0];
                let signature_doc = $("#frmattadet #signature_doc").prop("files")[0];
                let stamp_doc = $("#frmattadet #stamp_doc").prop("files")[0];
                let chk_agree = $('input[name="checkbox"]:checked').val();

                let comp_atta_data = new FormData();

                comp_atta_data.append("inc_certificate", inc_certificate);
                comp_atta_data.append("pan_doc", pan_doc);
                comp_atta_data.append("gst_doc", gst_doc);
                comp_atta_data.append("trade_doc", trade_doc);
                comp_atta_data.append("pf_doc", pf_doc);
                comp_atta_data.append("ptex_doc", ptex_doc);
                comp_atta_data.append("first_diraadh_doc", first_diraadh_doc);
                comp_atta_data.append("firstpan_doc", firstpan_doc);
                comp_atta_data.append("first_dirphoto_doc", first_dirphoto_doc);
                comp_atta_data.append("second_aadha_doc", second_aadha_doc);
                comp_atta_data.append("second_pan_doc", second_pan_doc);
                comp_atta_data.append("second_dirphoto_doc", second_dirphoto_doc);
                comp_atta_data.append("other_logo_doc", other_logo_doc);
                comp_atta_data.append("signature_doc", signature_doc);
                comp_atta_data.append("stamp_doc", stamp_doc);
                // comp_atta_data.append("gstdocstate", gstdocstate);
                comp_atta_data.append("chk_agree", chk_agree);
                //console.log(comp_atta_data);

                $("#loader").show();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_comp_attachment",
                    type: "POST",
                    data: comp_atta_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#loader").hide();
                        var messageContainer = $("#frmattadet .message-container");
                        messageContainer.html(""); // Clear previous messages

                        if (response.class == "succ") {
                            $("#gstdocstate").val(response.gstdocstate);
                            // showToast("Profile picture updated successfully!", "success");
                            showToast(response.message, "success");
                            setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Business Details Update: " + response.message, "error");
                        }

                        // if (response.class == "succ") {
                        //     $("#gstdocstate").val(response.gstdocstate);
                        //     messageContainer
                        //         .html(
                        //             '<div class="' +
                        //                 response.class +
                        //                 '">' +
                        //                 response.message +
                        //                 "</div>"
                        //         )
                        //         .show();
                        // } else {
                        //     // $("#loader").hide();
                        //     $.each(response, function (idx, obj) {
                        //         messageContainer.append(
                        //             '<div class="err">' + obj + "</div>"
                        //         );
                        //     });
                        // }

                        // Hide message container after 3 seconds if there are messages
                        // if (messageContainer.children().length > 0) {
                        //     setTimeout(function() {
                        //         messageContainer.hide("slow");
                        //     }, 3000);
                        // }
                    },
                });

                //}
            });

            $('.assignCABtn').click(function() {
                var ca_id = $(this).data('id');
                var ca_assign_status = $(this).data('status');
                const that = this;
                $("#staticBackdrop .message-container").html('');

                $('.assignCASendBtn').click(function() {
                    var msg = $("#msg").val();
                    assign_ca(ca_id, ca_assign_status, msg, that);
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

                $("#loader").show();
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
                            btn.prop('disabled', false);
                            if (response.class == "succ") {
                                $('#loader').hide();
                                if (response.ca_assign_status == 1) {
                                    //$(el).find('span').text("Un-Assign");
                                } else {
                                    //$(el).find('span').text("Assign");
                                }
                                //$(el).data('status',response.ca_assign_status);
                                $('#staticBackdrop').modal('toggle');
                                $(".ecom-content .message-container").html('<div class="' + response
                                    .class + '">' + response.message + '</div>');
                                window.location.href = response.redirect;
                            } else {
                                $('#loader').hide();
                                $.each(response, function(idx, obj) {
                                    $("#staticBackdrop .message-container").html(
                                        '<div class="err">' + obj + '</div>');
                                });
                            }
                        }
                    });
                }
            }

            $('.requestForCheck').on('change', function() {
                var base_url = $("#base_url").val();
                $('#loader').show();
                var formCASpec = $('form#frmRequestFor').serialize();
                $.ajax({
                    url: base_url + '/updateRequestFor',
                    type: 'POST',
                    data: formCASpec,
                    success: function(response) {
                        if (response.class == "succ") {
                            $('#loader').hide();
                            $("#frmRequestFor .message-container").html('<div class="' +
                                response.class + '">' + response.message + '</div>');
                        } else {
                            $('#loader').hide();
                            $.each(response, function(idx, obj) {
                                $("#frmRequestFor .message-container").html(
                                    '<div class="err">' + obj + '</div>');
                            });
                        }
                    }
                });

            });



            document.getElementById('fileUpload').addEventListener('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    const maxSize = 200 * 1024; // 200 KB
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

                    // Check file type
                    if (!allowedTypes.includes(file.type)) {
                        showToast("Only image files (JPG, PNG, GIF) are allowed!", "error");
                        event.target.value = ""; // Reset file input
                        location.reload();
                        return;
                    }

                    // Check file size
                    if (file.size > maxSize) {
                        showToast("File size must not exceed 200 KB!", "error");
                        event.target.value = ""; // Reset file input
                        location.reload();
                        return;
                    }

                    // Passed validation — proceed with upload
                    let formData = new FormData();
                    formData.append('fileUpload', file);

                    fetch("{{ route('upload.profile.image') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast("Profile picture updated successfully!", "success");
                                // setTimeout(() => location.reload(), 2000);
                            } else {
                                showToast("Upload failed: " + data.message, "error");
                            }
                        })
                        .catch(error => {
                            showToast("An error occurred!", "error");
                            console.error('Error:', error);
                        });
                }
            });


        });


        // User Module Company Profile Logo Update
        function triggerFileUpload() {
            document.getElementById("fileUpload").click();
        }

        function handleFileUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("uploadedImage").src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }



        // User Module Add New Billing address In Company Profile

        let branchCount = 1;

        function addBillingAddress() {
            const billingAddressContainer = document.querySelector(
                "#billing-address-container"
            );
            const gstSelect = document.getElementById("gst_reg");
            const selectedValue = gstSelect.value;
            const gstDisplay = selectedValue === "Yes" ? "block" : "none";


            const newBranch = document.createElement("div");
            newBranch.className = "col-lg-6 col-sm-12 branch";

            newBranch.dataset.branchId = branchCount;

            newBranch.innerHTML = `
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Branch Address </h5>
                        <button class="btn btn-danger" onclick="deleteBranch(this)">Delete this Branch</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 gst-field" id="bill_address_view" style="display: ${gstDisplay}">
                                <div class="mb-3">
                                    <label class="form-label">GST Number</label>
                                    <input type="text" class="form-control" placeholder="Enter GST Number">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Contact Person Name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Address Line 1">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Address Line 2 <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Address Line 2">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-0">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter State">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-0">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter City">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-0">
                                    <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" placeholder="Enter Zip Code">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

            billingAddressContainer.appendChild(newBranch);

            branchCount++;
        }

        function deleteBranch(button) {
            const branchToDelete = button.closest(".col-lg-6");
            branchToDelete.parentNode.removeChild(branchToDelete);

            updateBranchNumbers();
        }

        function updateBranchNumbers() {
            const allBranches = document.querySelectorAll(
                "#billing-address-container .branch"
            );
            let currentNumber = 1;

            allBranches.forEach((branch) => {
                const header = branch.querySelector(".card-header h5");
                header.textContent = `Branch Address ${currentNumber}`;
                currentNumber++;
            });

            branchCount = currentNumber;

            if (allBranches.length === 0) {
                branchCount = 1;
            }
        }

        // User Module Add New Bank Account In Company Profile
        let bankAccountCount = <?php echo count($bankDetails); ?>;

        function addBankAccount() {
            const bankAccountContainer = document.querySelector(
                "#bank-account-container"
            );
            const newBankAccount = document.createElement("div");
            newBankAccount.className = "col-lg-6 col-sm-12 bank-account";
            newBankAccount.dataset.accountId = bankAccountCount;
            newBankAccount.innerHTML = `
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Bank Account </h5>
                        <button class="btn btn-danger" onclick="deleteBankAccount(this)">Delete this Bank Account</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_name[]" class="form-control" placeholder="Bank Name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Branch <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_branch[]" id="billing-contact-name" class="form-control" placeholder="Enter Branch">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Account Name <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_holder_name[]"  class="form-control" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="ac_no[]" class="form-control" placeholder="Enter Account Number">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-0">
                                    <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code[]" class="form-control" placeholder="Enter IFSC Code ">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-0">
                                    <label class="form-label">VPA / UPI ID</label>
                                    <input type="text" name="ac_upid[]" class="form-control" placeholder="Enter VPA / UPI ID">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            bankAccountContainer.appendChild(newBankAccount);
            bankAccountCount++;
            updateBankAccountNumbers();
        }

        function deleteBankAccount(button) {
            const bankAccountToDelete = button.closest(".col-lg-6");
            bankAccountToDelete.remove();
            updateBankAccountNumbers();
        }

        function updateBankAccountNumbers() {
            const allBankAccounts = document.querySelectorAll(
                "#bank-account-container .bank-account"
            );
            let currentNumber = 3;
            allBankAccounts.forEach((account) => {
                const header = account.querySelector(".card-header h5");
                //header.textContent = `Bank Account ${currentNumber}`;
                header.textContent = `Bank Account`;
                currentNumber++;
            });
            bankAccountCount = currentNumber;
            if (allBankAccounts.length === 0) {
                bankAccountCount = 3;
            }
        }


        //------------- View profile img ----------
        document.getElementById('fileUpload').addEventListener('change', function(event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('uploadedImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        //----------------Field Validation -----------------

        document.addEventListener("DOMContentLoaded", function() {
            const emailInput = document.getElementById("comp_email");
            const phoneInput = document.getElementById("comp_phone");
            const panInput = document.getElementById("comp_pan_no");
            const submitBtn = document.getElementById("save_compDetBtn");

            const gstRegSelect = document.getElementById("gst_reg");
            const gstNoInput = document.getElementById("gst_no");
            const gstNoError = document.getElementById("gstNoError");
            const gstTranType = document.getElementById("comp_tran_type");
            const tranTypeError = document.getElementById("tranTypeError");

            // Regex Patterns
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            const gstPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

            // Create error spans
            function createErrorSpan(input) {
                const error = document.createElement("small");
                error.className = "text-danger";
                error.style.display = "none";
                input.parentElement.appendChild(error);
                return error;
            }

            const emailError = createErrorSpan(emailInput);
            const phoneError = createErrorSpan(phoneInput);
            const panError = createErrorSpan(panInput);

            function validateFields() {
                let valid = true;

                // Email
                const emailVal = emailInput.value.trim();
                if (!emailPattern.test(emailVal)) {
                    emailError.textContent = emailVal === "" ? "Email is required." : "Invalid email format.";
                    emailError.style.display = "block";
                    valid = false;
                } else {
                    emailError.style.display = "none";
                }

                // Phone
                const phoneVal = phoneInput.value.trim();
                if (!/^\d{10}$/.test(phoneVal)) {
                    phoneError.textContent = phoneVal === "" ? "Phone number is required." : "Must be 10 digits.";
                    phoneError.style.display = "block";
                    valid = false;
                } else {
                    phoneError.style.display = "none";
                }

                // PAN
                const panVal = panInput.value.trim().toUpperCase();
                panInput.value = panVal;
                if (!panPattern.test(panVal)) {
                    panError.textContent = panVal === "" ? "PAN is required." : "Invalid PAN (e.g. ABCDE1234F).";
                    panError.style.display = "block";
                    valid = false;
                } else {
                    panError.style.display = "none";
                }

                return valid;
            }

            function validateGSTFields() {
                let valid = true;
                const isGstYes = gstRegSelect.value === "Yes";

                if (isGstYes) {
                    const gstVal = gstNoInput.value.trim().toUpperCase();
                    gstNoInput.value = gstVal;

                    if (!gstPattern.test(gstVal)) {
                        gstNoError.textContent = gstVal === "" ? "GST Number is required." :
                            "Invalid GST No. format.";
                        gstNoError.style.display = "block";
                        valid = false;
                    } else {
                        gstNoError.style.display = "none";
                    }

                    if (gstTranType.value === "") {
                        tranTypeError.textContent = "Transaction Type is required.";
                        tranTypeError.style.display = "block";
                        valid = false;
                    } else {
                        tranTypeError.style.display = "none";
                    }

                } else {
                    gstNoError.style.display = "none";
                    tranTypeError.style.display = "none";
                }

                return valid;
            }

            // function toggleGSTFields() {
            //     const isGstYes = gstRegSelect.value === "Yes";
            //     document.getElementById("gst_reg_no").style.display = isGstYes ? "block" : "none";
            //     document.getElementById("gst_reg_tran").style.display = isGstYes ? "block" : "none";
            //     document.getElementById("ship_address_view").style.display = isGstYes ? "block" : "none";
            //     document.getElementById("bill_address_view").style.display = isGstYes ? "block" : "none";

            // }

            function toggleGSTFields() {
                const isGstYes = gstRegSelect.value === "Yes";

                document.getElementById("gst_reg_no").style.display = isGstYes ? "block" : "none";
                document.getElementById("gst_reg_tran").style.display = isGstYes ? "block" : "none";
                document.getElementById("ship_address_view").style.display = isGstYes ? "block" : "none";
                document.getElementById("bill_address_view").style.display = isGstYes ? "block" : "none";

                // Handle all dynamically added GST fields
                document.querySelectorAll(".gst-field").forEach(el => {
                    el.style.display = isGstYes ? "block" : "none";
                });
            }

            function handleValidation() {
                const validBasic = validateFields();
                const validGST = validateGSTFields();
                submitBtn.disabled = !(validBasic && validGST);
            }

            // Event listeners
            emailInput.addEventListener("input", handleValidation);
            phoneInput.addEventListener("input", handleValidation);
            panInput.addEventListener("input", () => {
                panInput.value = panInput.value.toUpperCase();
                handleValidation();
            });

            gstRegSelect.addEventListener("change", () => {
                toggleGSTFields();
                handleValidation();
            });

            gstNoInput.addEventListener("input", handleValidation);
            gstTranType.addEventListener("change", handleValidation);

            // Initial setup
            toggleGSTFields();
            handleValidation();
        });

        //--------------For GST Details Fetching ----------------
       $('#get_gst_btn').click(function() {
			let gstin = $('#gst_no').val();

			$.ajax({
				url: '{{ route('gst.fetch') }}',
				type: 'POST',
				data: {
					gstin: gstin,
					_token: '{{ csrf_token() }}'
				},
				success: function(data) {
					if (data && data.data) {

						let d = data.data;

						let address1 = "";
						let address2 = "";
						let fullAddress = "";

						// ================= Address =================
						if (d.pradr && d.pradr.addr) {
							const a = d.pradr.addr;

							address1 = [a.bno, a.bnm].filter(Boolean).join(' ');
							address2 = [a.flno, a.st, a.loc].filter(Boolean).join(', ');
							fullAddress = [
								a.bno, a.bnm, a.flno, a.st, a.loc, a.dst,
								(a.stcd ? a.stcd : '') + (a.pncd ? ' - ' + a.pncd : '')
							].filter(Boolean).join(', ');

							// Populate fields
							$('#comp_bill_addone').val(address1);
							$('#comp_bill_addtwo').val(address2);
							$('#comp_bill_pin').val(a.pncd || '');
						}

						// ================= Basic Details =================
						$('#comp_name').val(d.tradeNam || d.lgnm);
						$('#comp_bill_name').val(d.lgnm || '');

						// ================= GST Type =================
						let gstType = "";
						if (d.dty === "Regular") {
							gstType = "Regular";
						} else if (d.dty === "Composition") {
							gstType = "QRMP";
						} else {
							gstType = "Un-Register";
						}

						$('#comp_tran_type').val(gstType).trigger('change');

						
						// ================= Modal =================
						let html = `
							<p><strong>GSTIN:</strong> ${d.gstin}</p>
							<p><strong>Legal Name:</strong> ${d.lgnm}</p>
							<p><strong>Trade Name:</strong> ${d.tradeNam}</p>
							<p><strong>Constitution of Business:</strong> ${d.ctb}</p>
							<p><strong>Taxpayer Type:</strong> ${d.dty}</p>
							<p><strong>GST Status:</strong> ${d.sts}</p>
							<p><strong>Registration Date:</strong> ${d.rgdt}</p>
							<p><strong>Location:</strong> ${fullAddress}</p>
						`;

						$('#gst_modal_body').html(html);

						var modal = new bootstrap.Modal(document.getElementById('gstDetailsModal'));
						modal.show();

					} else {
						alert("GST Details not found. Please check GST No.");
					}
				},

				error: function(xhr) {
					console.error(xhr.responseText);

					let errorMsg = "Failed to fetch GST details.";
					try {
						let error = JSON.parse(xhr.responseText);
						if (error.details) {
							errorMsg += "\n" + error.details;
						}
					} catch (e) {}

					alert(errorMsg);
				}
			});
		});


        //-------- Add/Edit Holiday ---------
        document.getElementById('holidayForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const data = new FormData(form);
            const holidayId = document.getElementById('holidayId').value;
            const method = document.getElementById('formMethod').value;

            // Determine if this is an edit or add operation
            const isEdit = holidayId && method === 'PUT';
            const url = isEdit ? `/holidays/${holidayId}` : '/holidays';
            const httpMethod = isEdit ? 'PUT' : 'POST';

            try {
                // Convert FormData to JSON for PUT requests
                let requestBody;
                let headers = {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content'),
                };

                if (isEdit) {
                    // For PUT requests, send JSON
                    const formObject = {};
                    data.forEach((value, key) => {
                        if (key !== '_method') { // Exclude the method field
                            formObject[key] = value;
                        }
                    });
                    requestBody = JSON.stringify(formObject);
                    headers['Content-Type'] = 'application/json';
                    headers['Accept'] = 'application/json';
                } else {
                    // For POST requests, send FormData
                    requestBody = data;
                }

                const response = await fetch(url, {
                    method: httpMethod,
                    headers: headers,
                    body: requestBody
                });

                const result = await response.json();
                if (response.ok) {
					localStorage.setItem('activeTab', 'holidays');
                    const message = isEdit ? 'Holiday updated successfully!' : 'Holiday added successfully!';
                    showToast(result.message || message, 'success');
                    form.reset();
                    resetHolidayForm(); // Reset form to add mode

                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('holidayModal'));
                    if (modal) {
                        modal.hide();
                    }

                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(result.message || 'An error occurred', 'error');
                }
            } catch (err) {
                showToast('Server error: ' + err.message, 'error');
            }
        });

        // Edit Holiday Function
        function editHoliday(holidayId) {
            // Fetch holiday data
            fetch(`/holidays/${holidayId}/edit`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const holiday = data.holiday;
						
						localStorage.setItem('activeTab', 'holidays');
                        // Populate form fields
                        document.getElementById('holidayId').value = holiday.id;
                        document.getElementById('holidayName').value = holiday.holidayName;
                        document.getElementById('holidayDate').value = holiday.holidayDate;
                        document.getElementById('holidayType').value = holiday.holidayType;
                        document.getElementById('holidayDescription').value = holiday.holidayDescription || '';

                        // Set form to edit mode
                        document.getElementById('formMethod').value = 'PUT';
                        document.getElementById('holidayModalLabel').textContent = 'Edit Holiday';
                        document.getElementById('holidaySubmitBtn').textContent = 'Update';

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('holidayModal'));
                        modal.show();
                    } else {
                        showToast(data.message || 'Failed to load holiday data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error loading holiday data', 'error');
                });
        }

        // Reset Holiday Form to Add Mode
        function resetHolidayForm() {
            document.getElementById('holidayId').value = '';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('holidayModalLabel').textContent = 'Add Holiday';
            document.getElementById('holidaySubmitBtn').textContent = 'Save';
            document.getElementById('holidayForm').reset();
        }

        // Reset form when modal is closed
        document.getElementById('holidayModal').addEventListener('hidden.bs.modal', function() {
            resetHolidayForm();
        });



        // Holiday Table Filter Functionality
        function filterHolidays() {
            const typeFilter = document.getElementById('filterType').value.toLowerCase();
            const yearFilter = document.getElementById('filterYear').value;
            const tableBody = document.getElementById('holidayTableBody');
            const rows = tableBody.getElementsByTagName('tr');
            let visibleRows = 0;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const typeCell = row.cells[3]; // Holiday type column
                const dateCell = row.cells[2]; // Date column

                if (typeCell && dateCell) {
                    const holidayType = typeCell.textContent.toLowerCase().trim();
                    const holidayDate = dateCell.textContent.trim();
                    const holidayYear = holidayDate.split('-')[2]; // Extract year from dd-mm-yyyy format

                    let showRow = true;

                    // Filter by type
                    if (typeFilter && !holidayType.includes(typeFilter)) {
                        showRow = false;
                    }

                    // Filter by year
                    if (yearFilter && holidayYear !== yearFilter) {
                        showRow = false;
                    }

                    if (showRow) {
                        row.style.display = '';
                        visibleRows++;
                        // Update serial number
                        row.cells[0].textContent = visibleRows;
                    } else {
                        row.style.display = 'none';
                    }
                }
            }

            // Show/hide empty state based on visible rows
            const emptyState = document.getElementById('emptyState');
            const holidayTableContainer = document.getElementById('holidayTableContainer');

            if (visibleRows === 0) {
                if (holidayTableContainer) holidayTableContainer.style.display = 'none';
                if (emptyState) {
                    emptyState.style.display = 'block';
                    const currentYear = new Date().getFullYear();
                    const selectedYear = document.getElementById('filterYear').value;
                    const selectedType = document.getElementById('filterType').value;

                    let message = 'No Holidays Match Your Filter';
                    let description = 'Try adjusting your filter criteria.';

                    if (selectedYear === currentYear.toString() && !selectedType) {
                        message = `No Holidays Found for ${currentYear}`;
                        description = 'You haven\'t added any holidays for this year yet.';
                    }

                    emptyState.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-filter-off" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                        <h5 class="text-muted">${message}</h5>
                        <p class="text-muted mb-3">${description}</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary" onclick="clearFilters()">
                                <i class="ti ti-filter-x me-1"></i>Reset Filters
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#holidayModal">
                                <i class="ti ti-plus me-1"></i>Add Holiday
                            </button>
                        </div>
                    </div>
                `;
                }
            } else {
                if (holidayTableContainer) holidayTableContainer.style.display = 'block';
                if (emptyState) emptyState.style.display = 'none';
            }
        }

        // Clear all filters
        function clearFilters() {
            const currentYear = new Date().getFullYear().toString();
            const yearFilter = document.getElementById('filterYear');

            document.getElementById('filterType').value = '';

            // Reset to current year if it exists in options, otherwise show all
            const currentYearOption = yearFilter.querySelector(`option[value="${currentYear}"]`);
            if (currentYearOption) {
                yearFilter.value = currentYear;
            } else {
                yearFilter.value = '';
            }

            filterHolidays();
        }

        // Add event listeners for filters
        document.addEventListener('DOMContentLoaded', function() {
            const typeFilter = document.getElementById('filterType');
            const yearFilter = document.getElementById('filterYear');

            if (typeFilter) {
                typeFilter.addEventListener('change', filterHolidays);
            }

            if (yearFilter) {
                yearFilter.addEventListener('change', filterHolidays);
            }

            // Populate year filter with dynamic years from holidays
            if (yearFilter) {
                const tableBody = document.getElementById('holidayTableBody');
                const rows = tableBody.getElementsByTagName('tr');
                const years = new Set();
                const currentYear = new Date().getFullYear().toString();

                for (let i = 0; i < rows.length; i++) {
                    const dateCell = rows[i].cells[2];
                    if (dateCell) {
                        const holidayDate = dateCell.textContent.trim();
                        const year = holidayDate.split('-')[2];
                        if (year) years.add(year);
                    }
                }

                // Clear existing year options except "All Years"
                const allYearsOption = yearFilter.querySelector('option[value=""]');
                yearFilter.innerHTML = '';
                yearFilter.appendChild(allYearsOption);

                // Add dynamic years
                const sortedYears = Array.from(years).sort();
                sortedYears.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearFilter.appendChild(option);
                });

                // Set current year as default if it exists in the holidays
                if (years.has(currentYear)) {
                    yearFilter.value = currentYear;
                }

                // Apply initial filter to show current year by default
                filterHolidays();
            }
        });

        // Enhanced Delete Holiday Function with SweetAlert (if available)
        function deleteHolidayWithSweetAlert(holidayId) {
            // Check if SweetAlert is available, otherwise use regular confirm
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performHolidayDeletion(holidayId);
                    }
                });
            } else {
                // Fallback to regular confirm dialog
                deleteHoliday(holidayId);
            }
        }

        // Perform the actual deletion
        function performHolidayDeletion(holidayId) {
            fetch(`/holidays/${holidayId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
						localStorage.setItem('activeTab', 'holidays');
                        // Show success message with SweetAlert if available
                        if (typeof Swal !== 'undefined') {
                            showToast(data.message || 'Holiday deleted successfully!', 'success');

                        } else {
                            showToast(data.message || 'Holiday deleted successfully!', 'success');
                        }

                        // Remove the row from table
                        const row = document.querySelector(`button[onclick*="${holidayId}"]`).closest('tr');
                        if (row) {
                            row.remove();
                        }

                        // Update statistics and re-apply filters
                        filterHolidays();

                        // Reload page after a short delay to refresh all data
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        // Show error message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to delete holiday.',
                                'error'
                            );
                        } else {
                            showToast(data.message || 'Failed to delete holiday. Please try again.', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the holiday.',
                            'error'
                        );
                    } else {
                        showToast('An error occurred while deleting the holiday. Please try again.', 'error');
                    }
                });
        }

        // Day Schedule Functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle status toggle switches
            document.querySelectorAll('.status-toggle').forEach(function(toggle) {
                toggle.addEventListener('change', function() {
                    const row = this.closest('.schedule-row');
                    const openingInput = row.querySelector('.opening-time');
                    const closingInput = row.querySelector('.closing-time');
                    const lunchStartInput = row.querySelector('.lunch-start-time');
                    const lunchEndInput = row.querySelector('.lunch-end-time');
                    const statusLabel = row.querySelector('.status-label');
                    const totalHours = row.querySelector('.total-hours');
                    const dayIcon = row.querySelector('.ti-calendar');
                    const dayName = row.querySelector('.fw-medium');

                    if (this.checked) {
                        // Enable inputs
                        openingInput.disabled = false;
                        closingInput.disabled = false;
                        lunchStartInput.disabled = false;
                        lunchEndInput.disabled = false;

                        // Update styling
                        statusLabel.textContent = 'Open';
                        statusLabel.className = 'form-check-label status-label text-success';
                        dayIcon.className = 'ti ti-calendar text-primary me-2';
                        dayName.className = 'fw-medium';
                        row.classList.remove('closed-day');

                        // Calculate hours
                        calculateHours(row);
                    } else {
                        // Disable inputs
                        openingInput.disabled = true;
                        closingInput.disabled = true;
                        lunchStartInput.disabled = true;
                        lunchEndInput.disabled = true;

                        // Update styling
                        statusLabel.textContent = 'Closed';
                        statusLabel.className = 'form-check-label status-label text-muted';
                        dayIcon.className = 'ti ti-calendar text-muted me-2';
                        dayName.className = 'fw-medium text-muted';
                        totalHours.textContent = '0 hours';
                        totalHours.className = 'total-hours text-muted';
                        row.classList.add('closed-day');
                    }
                });
            });

            // Handle time input changes
            document.querySelectorAll('.opening-time, .closing-time, .lunch-start-time, .lunch-end-time').forEach(
                function(input) {
                    input.addEventListener('change', function() {
                        const row = this.closest('.schedule-row');
                        calculateHours(row);
                    });
                });

            // Calculate working hours
            function calculateHours(row) {
                const openingInput = row.querySelector('.opening-time');
                const closingInput = row.querySelector('.closing-time');
                const lunchStartInput = row.querySelector('.lunch-start-time');
                const lunchEndInput = row.querySelector('.lunch-end-time');
                const totalHours = row.querySelector('.total-hours');
                const statusToggle = row.querySelector('.status-toggle');

                if (!statusToggle.checked) {
                    totalHours.textContent = '0 hours';
                    totalHours.className = 'total-hours text-muted';
                    return;
                }

                const openingTime = openingInput.value;
                const closingTime = closingInput.value;

                if (openingTime && closingTime) {
                    const opening = new Date('2000-01-01 ' + openingTime);
                    const closing = new Date('2000-01-01 ' + closingTime);

                    if (closing > opening) {
                        let diffMs = closing - opening;

                        // Subtract lunch break if provided
                        const lunchStartTime = lunchStartInput.value;
                        const lunchEndTime = lunchEndInput.value;

                        if (lunchStartTime && lunchEndTime) {
                            const lunchStart = new Date('2000-01-01 ' + lunchStartTime);
                            const lunchEnd = new Date('2000-01-01 ' + lunchEndTime);

                            if (lunchEnd > lunchStart) {
                                const lunchMs = lunchEnd - lunchStart;
                                diffMs -= lunchMs;
                            }
                        }

                        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                        const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                        let hoursText = diffHours + ' hours';
                        if (diffMinutes > 0) {
                            hoursText = diffHours + 'h ' + diffMinutes + 'm';
                        }

                        totalHours.textContent = hoursText;
                        totalHours.className = 'total-hours text-primary fw-medium';
                    } else {
                        totalHours.textContent = 'Invalid time';
                        totalHours.className = 'total-hours text-danger';
                    }
                }
            }

            // Initialize hours calculation for all rows
            document.querySelectorAll('.schedule-row').forEach(function(row) {
                calculateHours(row);
            });



            // Reset button functionality
            document.querySelector('button[type="button"]').addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the schedule to default values?')) {
                    // Reset to default values
                    const defaults = {
                        monday: {
                            open: '10:00',
                            close: '18:00',
                            status: true
                        },
                        tuesday: {
                            open: '10:00',
                            close: '18:00',
                            status: true
                        },
                        wednesday: {
                            open: '10:00',
                            close: '18:00',
                            status: true
                        },
                        thursday: {
                            open: '10:00',
                            close: '18:00',
                            status: true
                        },
                        friday: {
                            open: '10:00',
                            close: '18:00',
                            status: true
                        },
                        saturday: {
                            open: '10:00',
                            close: '14:00',
                            status: false
                        },
                        sunday: {
                            open: '11:00',
                            close: '15:00',
                            status: false
                        }
                    };

                    Object.keys(defaults).forEach(day => {
                        const row = document.querySelector(`[data-day="${day}"]`);
                        const openingInput = row.querySelector('.opening-time');
                        const closingInput = row.querySelector('.closing-time');
                        const statusToggle = row.querySelector('.status-toggle');

                        openingInput.value = defaults[day].open;
                        closingInput.value = defaults[day].close;
                        statusToggle.checked = defaults[day].status;

                        // Trigger change event to update UI
                        statusToggle.dispatchEvent(new Event('change'));
                    });

                    showToast('Schedule reset to default values', 'info');
                }
            });
        });

        $('#scheduleForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            $('.schedule-row').each(function() {
                const row = this;
                const day = row.dataset.day;
                const isChecked = row.querySelector('.status-toggle').checked;

                const o = row.querySelector('.opening-time');
                const c = row.querySelector('.closing-time');
                const ls = row.querySelector('.lunch-start-time');
                const le = row.querySelector('.lunch-end-time');

                const opening = (o && (o.value || o.defaultValue || ''));
                const closing = (c && (c.value || c.defaultValue || ''));
                const lunchStart = (ls && (ls.value || ls.defaultValue || ''));
                const lunchEnd = (le && (le.value || le.defaultValue || ''));

                formData.append(`${day}_open`, opening);
                formData.append(`${day}_close`, closing);
                formData.append(`${day}_lunch_start`, lunchStart);
                formData.append(`${day}_lunch_stop`, lunchEnd);
                if (isChecked) formData.append(`${day}_status`, '1');
            });



            $.ajax({
                url: '/save-schedule',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    // console.log(res);

                    if (res.status === 'success') {
                        showToast(res.message, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast(res.message, 'error');
                    }
                },
                error: function(xhr) {
                    let message = 'An error occurred while saving.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    showToast(message, 'error');
                }
            });
        });

        // Company Locations Management
        let editingLocationId = null;



        // Edit Location Function
        function editLocation(locationId) {
            editingLocationId = locationId;

            // Fetch location data from server
            $.ajax({
                url: '/get-location/' + locationId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.location;

                        // Populate form fields
                        document.getElementById('locationName').value = data.location_name || '';
                        document.getElementById('locationType').value = data.location_type || '';
                        document.getElementById('latitude').value = data.latitude || '';
                        document.getElementById('longitude').value = data.longitude || '';
                        document.getElementById('radius').value = data.radius || '';
                        document.getElementById('locationStatus').value = data.status || 'Active';

                        // Update location preview
                        updateLocationPreview();
                    } else {
                        showToast('Failed to load location data', 'error');
                    }
                },
                error: function() {
                    showToast('Error loading location data', 'error');
                }
            });

            // Update modal title
            document.getElementById('locationModalLabel').textContent = 'Edit Location';

            // Show modal
            $('#locationModal').modal('show');
        }

        // Delete Location Function
        function deleteLocation(locationId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX call to delete location
                    $.ajax({
                        url: '/delete-location/' + locationId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            showToast(response.message, 'success');
                            // Reload the page after a short delay to show the toast
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        },
                        error: function(xhr, status, error) {
                            let errorMessage = 'An error occurred while deleting the location.';

                            if (xhr.status === 404) {
                                errorMessage = 'Location not found.';
                            } else if (xhr.status === 403) {
                                errorMessage = 'You are not authorized to delete this location.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            showToast(errorMessage, 'error');
                        }
                    });
                }
            });
        }

        // Reset Location Form
        function resetLocationForm() {
            document.getElementById('locationForm').reset();
            editingLocationId = null;
            document.getElementById('locationModalLabel').textContent = 'Add New Location';
            updateLocationPreview();
        }

        // Reset form when modal is closed
        $('#locationModal').on('hidden.bs.modal', function() {
            resetLocationForm();
        });

        // Director Details Functions
        function showDirectorDetails(name, designation, email, phone, din, signature) {
			//alert(signature);
            document.getElementById('directorName').textContent = name || '-';
            document.getElementById('directorDesignation').textContent = designation || '-';
            document.getElementById('directorEmail').textContent = email || '-';
            document.getElementById('directorPhone').textContent = phone || '-';
            document.getElementById('directorDIN').textContent = din || '-';
            
            // Handle signature image
            const signatureImg = document.getElementById('signatureImage');
            const noSignature = signatureImg.nextElementSibling;
            
            if (signature && signature !== '') {			
                signatureImg.src = signature; //'{{ asset("storage/") }}' + signature;
                signatureImg.style.display = 'block';
                noSignature.style.display = 'none';
            } else {
                signatureImg.style.display = 'none';
                noSignature.style.display = 'flex';
            }
        }


        // Reset Add Director Form when modal is closed
        $('#addDirectorModal').on('hidden.bs.modal', function() {
            $('#addDirectorForm')[0].reset();
        });

        // Geofencing Functions
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                    document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                    updateLocationPreview();
                    showToast('Current location detected successfully!', 'success');
                }, function(error) {
                    showToast('Unable to get current location. Please enter manually.', 'error');
                });
            } else {
                showToast('Geolocation is not supported by this browser.', 'error');
            }
        }

        function openMapPicker() {
            // Open the map picker modal
            $('#mapPickerModal').modal('show');

            // Initialize map after modal is shown
            setTimeout(() => {
                initializeMapPicker();
            }, 300);
        }

        function updateLocationPreview() {
            const latitude = document.getElementById('latitude').value;
            const longitude = document.getElementById('longitude').value;
            const radius = document.getElementById('radius').value;

            const coordsSpan = document.getElementById('previewCoords');
            const radiusSpan = document.getElementById('previewRadius');

            if (latitude && longitude) {
                coordsSpan.textContent = `${latitude}, ${longitude}`;
            } else {
                coordsSpan.textContent = 'Not set';
            }

            if (radius) {
                radiusSpan.textContent = `${radius} meters`;
            } else {
                radiusSpan.textContent = 'Not set';
            }
        }

        // Form validation and event listeners
        document.getElementById('latitude').addEventListener('input', updateLocationPreview);
        document.getElementById('longitude').addEventListener('input', updateLocationPreview);
        document.getElementById('radius').addEventListener('input', function() {
            // Ensure radius is within limits
            const value = parseInt(this.value);
            if (value < 10) this.value = 10;
            if (value > 5000) this.value = 5000;
            updateLocationPreview();
        });

        // Save Location
        $('#locationForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                locationName: $('#locationName').val(),
                locationType: $('#locationType').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                radius: $('#radius').val(),
                status: $('#locationStatus').val(),
                _token: '{{ csrf_token() }}'
            };

            // Debug: Log form data
            // console.log('Form Data being sent:', formData);

            // Validate required fields
            if (!formData.locationName || !formData.locationType || !formData.latitude || !formData.longitude || !
                formData.radius) {
                showToast('Please fill in all required fields.', 'error');
                return;
            }

            // Determine if this is an edit or create operation
            const isEdit = editingLocationId !== null;
            const url = isEdit ? '/update-location/' + editingLocationId : '{{ route('save.location') }}';
            const method = isEdit ? 'PUT' : 'POST';

            if (isEdit) {
                formData._method = 'PUT';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    showToast(response.message, response.type);
                    if (response.type === 'success') {
                        $('#locationForm')[0].reset();
                        $('#locationModal').modal('hide');
                        updateLocationPreview();
                        // Optionally reload the page to show the new location
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr, status, error) {
                    // console.log('AJAX Error:', xhr.responseText);
                    let errorMessage = "Server error occurred.";

                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = JSON.parse(xhr.responseText);
                        if (errors.errors) {
                            errorMessage = Object.values(errors.errors).flat().join(', ');
                        }
                    } else if (xhr.status === 500) {
                        errorMessage = "Internal server error. Please check the logs.";
                    }

                    showToast(errorMessage, "error");
                }
            });
        });

        // Map Picker Functionality
        let mapPickerMap = null;
        let mapPickerMarker = null;
        let selectedCoordinates = null;

        function initializeMapPicker() {
            // Check if map is already initialized
            if (mapPickerMap) {
                mapPickerMap.remove();
            }

            // Initialize map centered on India
            mapPickerMap = L.map('mapContainer').setView([20.5937, 78.9629], 5);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mapPickerMap);

            // Add click event to map
            mapPickerMap.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                selectLocationOnMap(lat, lng);
            });

            // If editing existing location, show it on map
            const currentLat = document.getElementById('latitude').value;
            const currentLng = document.getElementById('longitude').value;

            if (currentLat && currentLng) {
                selectLocationOnMap(parseFloat(currentLat), parseFloat(currentLng));
                mapPickerMap.setView([currentLat, currentLng], 15);
            }
        }

        function selectLocationOnMap(lat, lng) {
            // Remove existing marker
            if (mapPickerMarker) {
                mapPickerMap.removeLayer(mapPickerMarker);
            }

            // Add new marker
            mapPickerMarker = L.marker([lat, lng]).addTo(mapPickerMap);

            // Store coordinates
            selectedCoordinates = {
                lat: lat,
                lng: lng
            };

            // Update coordinate displays
            document.getElementById('selectedLatitude').value = lat.toFixed(6);
            document.getElementById('selectedLongitude').value = lng.toFixed(6);

            // Enable use location button
            document.getElementById('useLocationBtn').disabled = false;

            // Try to get address (reverse geocoding)
            reverseGeocode(lat, lng);
        }

        function reverseGeocode(lat, lng) {
            // Use Nominatim for reverse geocoding (free)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('selectedAddress').value = data.display_name;
                    } else {
                        document.getElementById('selectedAddress').value = 'Address not found';
                    }
                })
                .catch(error => {
                    console.error('Reverse geocoding error:', error);
                    document.getElementById('selectedAddress').value = 'Unable to fetch address';
                });
        }

        function searchLocation() {
            const query = document.getElementById('mapSearchInput').value.trim();
            if (!query) {
                showToast('Please enter a location to search', 'error');
                return;
            }

            // Use Nominatim for geocoding (free)
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const result = data[0];
                        const lat = parseFloat(result.lat);
                        const lng = parseFloat(result.lon);

                        // Center map on result
                        mapPickerMap.setView([lat, lng], 15);

                        // Select this location
                        selectLocationOnMap(lat, lng);

                        showToast('Location found!', 'success');
                    } else {
                        showToast('Location not found. Please try a different search term.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Geocoding error:', error);
                    showToast('Error searching location. Please try again.', 'error');
                });
        }

        function getCurrentLocationOnMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Center map on current location
                    mapPickerMap.setView([lat, lng], 15);

                    // Select current location
                    selectLocationOnMap(lat, lng);

                    showToast('Current location detected!', 'success');
                }, function(error) {
                    showToast('Unable to get current location. Please select manually.', 'error');
                });
            } else {
                showToast('Geolocation is not supported by this browser.', 'error');
            }
        }

        function centerMapToIndia() {
            mapPickerMap.setView([20.5937, 78.9629], 5);
        }

        function useSelectedLocation() {
            if (selectedCoordinates) {
                // Update the main form fields
                document.getElementById('latitude').value = selectedCoordinates.lat.toFixed(6);
                document.getElementById('longitude').value = selectedCoordinates.lng.toFixed(6);

                // Update location preview
                updateLocationPreview();

                // Close modal
                $('#mapPickerModal').modal('hide');

                showToast('Location coordinates updated!', 'success');
            }
        }

        // Handle search input enter key
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('mapSearchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchLocation();
                }
            });
        });

        // Clean up map when modal is closed
        $('#mapPickerModal').on('hidden.bs.modal', function() {
            selectedCoordinates = null;
            document.getElementById('selectedLatitude').value = '';
            document.getElementById('selectedLongitude').value = '';
            document.getElementById('selectedAddress').value = '';
            document.getElementById('mapSearchInput').value = '';
            document.getElementById('useLocationBtn').disabled = true;
        });
		
		
	

    $(document).on('submit', '[id^="upadteBankForm"]', function(e) {

        e.preventDefault();

        let form = this;
        let formData = new FormData(form);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            url: "/update_bank",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(response) {

                if (response.class == "succ") {

                    localStorage.setItem('activeTab', 'bank-details');

                    showToast(response.message, "success");

                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);

                } else {

                    showToast(response.message, "error");
                }
            }
        });
    });

    $("form#addBankForm").bind("submit", function(e) {
        e.preventDefault();

        var bankId = $("#bankId").val();
        var bankurl = "/save_bank"; 
        // var bankData = $(this).serialize();
        var bankData = new FormData(this);

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: bankurl,
            type: "POST",
            data: bankData,
            processData: false,
            contentType: false,
            success: function(response) {

                if (response.class == "succ") {

                    localStorage.setItem('activeTab', 'bank-details');

                    showToast(response.message, "success");

                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);

                } 
                else if (response.class == "validation") {

                    let errorMsg = '';

                    $.each(response.errors, function(field, messages) {
                        errorMsg += messages[0] + '<br>';
                    });

                    showToast(errorMsg, "error");
                } 
                else {

                    showToast(response.message || "Something went wrong", "error");
                }
            },
            error: function(xhr) {

                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';

                    $.each(errors, function(field, messages) {
                        errorMsg += messages[0] + '<br>';
                    });

                    showToast(errorMsg, "error");
                } else {

                    showToast("Server Error", "error");
                }
            }
        });
    });
	

    // Handle the bank delete confirmation
    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');
		$('#confirmDelete').data('id', id);
    });

    $('#confirmDelete').on('click', function() {
		
		let deleteId = $(this).data('id');
        alert(deleteId);

        if (deleteId) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: '/bank_delete/' + deleteId,
                type: 'DELETE',
				data: {
					redirectUrl: 'company-profile'
				},
                success: function(response) {
					localStorage.setItem('activeTab', 'bank-details');
                    showToast(response.message, "success");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                },
                error: function(xhr) {
                    // alert("Error deleting customer!");
                    showToast("Error deleting customer!", "error");
                }
            });
        }
    });
	
	// Toggle CA Access Details sub-options
	$(document).on('change', '.ca-access-toggle', function() {		
		let target = $(this).data('target');
        if($(this).is(':checked')){
            $('#' + target).slideDown();
        } else {
            $('#' + target).slideUp();
            $('#' + target).find('.permission-checkbox').prop('checked', false);
        }
		
		showToast('Please scroll down & Click "Save Permissions" to apply updates','warning');
	});
	
	// Auto enable main toggle if view/edit checked
    $('.permission-checkbox').change(function(){
        let parentBox = $(this).closest('.p-3');
		
        let anyChecked = parentBox.find('.permission-checkbox:checked').length > 0;
        parentBox.find('.ca-access-toggle').prop('checked', anyChecked);
		
		showToast('Please scroll down & Click "Save Permissions" to apply updates','warning');

    });

	//Activate tab after redirect (on page load)
	$(document).ready(function () {

		let tab = localStorage.getItem('activeTab');

		if (tab) {
			let triggerEl = document.querySelector(`#${tab}-tab`);

			if (triggerEl) {
				new bootstrap.Tab(triggerEl).show();
			}

			//Clear after use (important)
			localStorage.removeItem('activeTab');
		}

	});
	

    $(document).on('click', '.toggleQrBtn', function () {

        let target = $('#' + $(this).data('target'));
        let icon = $(this).find('i');
        let text = $(this).find('span');

        if (target.hasClass('d-none')) {

            target.removeClass('d-none');

            icon.removeClass('ti-eye').addClass('ti-eye-off');
            text.text('Hide QR');

        } else {

            target.addClass('d-none');

            icon.removeClass('ti-eye-off').addClass('ti-eye');
            text.text('View QR');
        }
    });
</script>

<script>
		function startProfileTour() {
			function launch() {
				let tour = introJs().setOptions({
					steps: [
						{
							title: 'Company Profile Guide',
							intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-building" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Let\'s walk through the profile section to configure your company details, bank accounts, CA access, and policies.</p></div>'
						},
						{
							element: '#uploadedImage',
							title: 'Company Logo',
							intro: 'Click the pencil icon here to upload your company logo (max size 200 KB).',
							tabId: 'company-details-tab'
						},
						{
							element: '#gst_reg',
							title: 'GST Registered Status',
							intro: 'Select if this company is registered under GST. Choosing Yes enables GST number and transaction type setups.',
							tabId: 'company-details-tab'
						},
						{
							element: '#comp_name',
							title: 'Company Name',
							intro: 'Enter your business\'s registered legal name.',
							tabId: 'company-details-tab'
						},
						{
							element: '#comp_type',
							title: 'Company Constitution Type',
							intro: 'Select the legal structure of your business (e.g. Proprietorship, Partnership, PVT Ltd). Proprietorship allows adding multiple businesses under one user.',
							tabId: 'company-details-tab'
						},
						{
							element: '#billing-address-1',
							title: 'Billing Address',
							intro: 'Fill in your billing details here. You can also add dynamic billing addresses.',
							tabId: 'company-details-tab'
						},
						{
							element: '#business-details-tab',
							title: 'Business & GST Details',
							intro: 'Click next to view the Business Details tab for nature of business and invoice sequences.',
							tabId: 'business-details-tab'
						},
						{
							element: '#business',
							title: 'Category of Business',
							intro: 'Specify whether your business is Trading, Service, Professional, or Mixed.',
							tabId: 'business-details-tab'
						},
						{
							element: '#exact_comp_nature',
							title: 'Exact Nature of Business',
							intro: 'Provide a description of the products or services your company trades/provides.',
							tabId: 'business-details-tab'
						},
						{
							element: '#comp_inv_digits',
							title: 'Invoice Series Prefixes',
							intro: 'Set up prefixes (e.g., PI/2024-25/ for Proforma, SI/2024-25/ for Sales) to auto-generate sequence numbers.',
							tabId: 'business-details-tab'
						},
						{
							element: '#contact-details-tab',
							title: 'Contact Details',
							intro: 'Click next to manage contact details.',
							tabId: 'contact-details-tab'
						},
						{
							element: '#comp_phone',
							title: 'Contact Information',
							intro: 'Verify your company phone, email address, WhatsApp number, and official website URL.',
							tabId: 'contact-details-tab'
						},
						{
							element: '#director-details-tab',
							title: 'Directors & Owners List',
							intro: 'Manage partners, directors, or proprietors. You can add new ones, check DIN status, and upload digital signatures.',
							tabId: 'director-details-tab'
						},
						{
							element: '#bank-details-tab',
							title: 'Bank Accounts Tab',
							intro: 'Click here to view, register, and update bank account details for your organization.',
							tabId: 'bank-details-tab'
						},
						{
							element: '#bank-details a[data-bs-target="#add-bank-modal"]',
							title: 'Add New Bank Account',
							intro: 'Click here to record a new bank account with branch details, account number, IFSC code, and current balance.',
							tabId: 'bank-details-tab'
						},
						{
							element: '#bank-details .card',
							title: 'Bank Account Card',
							intro: 'Displays registered bank details, branch codes, Swift code, and current balance. You can view, edit, or delete accounts using the menu.',
							tabId: 'bank-details-tab'
						},
						{
							element: '#attachment-tab',
							title: 'Locker & Attachments',
							intro: 'Securely upload corporate certificates (PAN, Incorporation, Trade license, Signatures) for safe digital locker storage.',
							tabId: 'attachment-tab'
						},
						
						{
							element: '#holidays-tab',
							title: 'Company Holidays',
							intro: 'Establish the yearly office holiday calendar, linking directly to automated attendance and payroll computations.',
							tabId: 'holidays-tab'
						},
						{
							element: '#schedule-tab',
							title: 'Shift Timings & Day Schedule',
							intro: 'Configure shifts, weekend offs, working hours, and grace times for automated employee logs.',
							tabId: 'schedule-tab'
						},
						{
							element: '#locations-tab',
							title: 'Office Locations Tab',
							intro: 'Click here to view, register, and update office locations and branches.',
							tabId: 'locations-tab'
						},
						{
							element: '#locations button[data-bs-target="#locationModal"]',
							title: 'Add New Location',
							intro: 'Click here to register a new branch or office location with latitude and longitude coordinates.',
							tabId: 'locations-tab'
						},
						{
							element: '#locations .row.mb-4.g-4',
							title: 'Location Summary Statistics',
							intro: 'Quickly review count summaries of operational Head Offices and Branch Offices.',
							tabId: 'locations-tab'
						},
						{
							element: '#locations .table-responsive',
							title: 'Office Locations Table',
							intro: 'Manage geofenced location entries. These are used to verify and secure employee attendance logs.',
							tabId: 'locations-tab'
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
				});

				tour.onbeforechange(function(targetElement) {
					if (!targetElement) return;

					// Find closest tab-pane containing the target element
					let tabPane = targetElement.closest('.tab-pane');
					if (tabPane) {
						let tabId = tabPane.getAttribute('id');
						let tabTrigger = document.getElementById(tabId + '-tab');
						if (tabTrigger && !tabTrigger.classList.contains('active')) {
							let tab = new bootstrap.Tab(tabTrigger);
							tab.show();
						}
					} else if (targetElement.classList.contains('nav-link') && targetElement.getAttribute('data-bs-toggle') === 'pill') {
						let tab = new bootstrap.Tab(targetElement);
						tab.show();
					}
				});

				tour.start();
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

		function bindProfileTour() {
			const btn = document.getElementById('start-profile-tour');
			if (btn) {
				btn.addEventListener('click', function(e) {
					e.preventDefault();
					startProfileTour();
				});
			}
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', bindProfileTour);
		} else {
			bindProfileTour();
		}
    </script>




@endsection
