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
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Admin Profile</li>
                    </ul>
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
                <div class="col-lg-3 col-xxl-3">
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
                                <h4 class="mt-3">{{ isset($compDetails->comp_name)?$compDetails->comp_name:''}}</h4>
                            </div>
                        </div>
                        <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0"
                            id="company-profile-set-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link list-group-item list-group-item-action active" id="company-details-tab"
                                data-bs-toggle="pill" href="#company-details" role="tab" aria-controls="company-details"
                                aria-selected="true">
                                <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Company
                                    Details</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="business-details-tab"
                                data-bs-toggle="pill" href="#business-details" role="tab"
                                aria-controls="business-details" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-database m-r-10"></i>Business
                                    Details</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="bank-details-tab"
                                data-bs-toggle="pill" href="#bank-details" role="tab" aria-controls="bank-details"
                                aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-wallet m-r-10"></i>Bank Details</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="attachment-tab"
                                data-bs-toggle="pill" href="#attachment" role="tab" aria-controls="attachment"
                                aria-selected="false">
                                <span class="f-w-500"><i
                                        class="ph-duotone ph-arrow-square-up m-r-10"></i>Attachments</span>
                            </a>
                            
                            <a class="nav-link list-group-item list-group-item-action" id="holidays-tab"
                                data-bs-toggle="pill" href="#holidays" role="tab" aria-controls="holidays"
                                aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-calendar m-r-10"></i>Company
                                    Holidays</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="schedule-tab"
                                data-bs-toggle="pill" href="#schedule" role="tab" aria-controls="schedule"
                                aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-clock m-r-10"></i>Day Schedule</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="locations-tab"
                                data-bs-toggle="pill" href="#locations" role="tab" aria-controls="locations"
                                aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-map-pin m-r-10"></i>Company
                                    Locations</span>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-xxl-9">
                    <div class="tab-content" id="company-profile-set-tabContent">
                        <div class="tab-pane fade show active" id="company-details" role="tabpanel"
                            aria-labelledby="company-details-tab">
                            <form action="javascript:void(0);" name="frmcompdet" id="frmcompdet">
                                @csrf


                                <div class="card">
                                    <div class="message-container"></div>
                                    <div class="card-header">
                                        <h5>Personal Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Company GST Registered <span
                                                            class="text-danger">*</span> <span id="gst_type_display"
                                                            class="badge bg-info mt-2"></span></label>
                                                    <select class="form-select" name="gst_reg" id="gst_reg" required>
                                                        <option value="">Select</option>
                                                        <option value="Yes" <?php echo (isset($compDetails->gst_reg)
                                                            && $compDetails->gst_reg == 'Yes') ? "selected" : "" ?>>Yes
                                                        </option>
                                                        <option value="No" <?php echo (isset($compDetails->gst_reg)
                                                            && $compDetails->gst_reg == 'No') ? "selected" : "" ?>>No
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-sm-6" id="gst_reg_no" style="display: none">
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
                                                    <span id="gstNoError" class="text-danger" style="display:none;">GST
                                                        Number is required</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6" id="gst_reg_tran" style="display: none">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Transaction Type <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control error" name="comp_tran_type"
                                                        id="comp_tran_type">
                                                        <option label="select"></option>
                                                        <option value="Regular" <?php echo (isset($compDetails->
                                                            comp_tran_type) && $compDetails->comp_tran_type
                                                            == 'Regular') ? "selected" : "" ?>>Regular</option>
                                                        <option value="QRMP" <?php echo (isset($compDetails->
                                                            comp_tran_type) && $compDetails->comp_tran_type
                                                            == 'QRMP') ? "selected" : "" ?>>QRMP</option>
                                                        <option value="Composite" <?php echo (isset($compDetails->
                                                            comp_tran_type) && $compDetails->comp_tran_type
                                                            == 'Composite') ? "selected" : "" ?>>Composite</option>
                                                    </select>

                                                    <span id="tranTypeError" class="text-danger"
                                                        style="display:none;">GST Transaction Type is required</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Company Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required name="comp_name"
                                                        id="comp_name"
                                                        value="{{ isset($compDetails->comp_name)?$compDetails->comp_name:''}}"
                                                        placeholder="Enter Company Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Company Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" required name="comp_email"
                                                        id="comp_email"
                                                        value="{{ isset($compDetails->comp_email)?$compDetails->comp_email:""}}"
                                                        placeholder="Enter Company Email">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" required name="comp_phone"
                                                        id="comp_phone"
                                                        value="{{ isset($compDetails->comp_phone)?$compDetails->comp_phone:""}}"
                                                        placeholder="Enter Phone Number">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Pan Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required name="comp_pan_no"
                                                        id="comp_pan_no" style="text-transform:uppercase;"
                                                        value="{{ isset($compDetails->comp_pan_no)?$compDetails->comp_pan_no:""}}"
                                                        placeholder="Enter Pan Number">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Tan Number</label>
                                                    <input type="text" class="form-control" name="comp_tan"
                                                        id="comp_tan"
                                                        value="{{ isset($compDetails->comp_tan)?$compDetails->comp_tan:""}}"
                                                        placeholder="Enter Tan Number">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">EPF Employer Registration No<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="comp_epf" name="comp_epf" class="form-control" required
                                                        value="{{ isset($compDetails->comp_epf)?$compDetails->comp_epf:""}}"
                                                        placeholder="Enter EPF Registration Number">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">ESIC Employer Registration<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="comp_esic" class="form-control" required                                                       name="comp_esic"
                                                        value="{{ isset($compDetails->comp_esic)?$compDetails->comp_esic:""}}"
                                                        placeholder="Enter ESIC Registration Number">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">P-Tax Enrolment Certificate No <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="comp_ptax_cert" name="comp_ptax_cert" class="form-control" required
                                                        value="{{ isset($compDetails->comp_ptax_cert)?$compDetails->comp_ptax_cert:""}}"
                                                        placeholder="Enter P-Tax Enrolment Certificate No">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">P-Tax Registration Number <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="comp_ptax" name="comp_ptax" class="form-control" required
                                                        value="{{ isset($compDetails->comp_ptax)?$compDetails->comp_ptax:""}}"
                                                        placeholder="Enter P-Tax Registration Number">
                                                </div>
                                            </div>

                                            <div class="col-sm-4 mb-4">
                                                <label class="form-label">Set Basic Salary Percentage <span class="text-danger">*</span></label>
                                                <input type="number" required class="form-control" name="basic_percentage" value="{{ isset($compDetails->basic_percentage)?$compDetails->basic_percentage:""}}" id="basic_percentage" placeholder="Enter percentage">
                                                
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Website </label>
                                                    <input type="text" class="form-control" name="comp_website"
                                                        id="comp_website"
                                                        value="{{ isset($compDetails->comp_website)?$compDetails->comp_website:""}}"
                                                        placeholder="Enter Website URL">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Company Type <span
                                                                class="text-danger">*</span></label>
                                                        <select class="select form-select company-type-dropdown"
                                                            name="comp_type" id="comp_type" required>
                                                            <option value="" label="select"></option>
                                                            <option value="Proprietorship" <?php echo
                                                                (isset($compDetails->comp_type) &&
                                                                $compDetails->comp_type == 'Proprietorship') ?
                                                                "selected" : "" ?>>Proprietorship</option>
                                                            <option value="Partnership" <?php echo (isset($compDetails->
                                                                comp_type) && $compDetails->comp_type == 'Partnership')
                                                                ?
                                                                "selected" : "" ?>>Partnership</option>
                                                            <option value="One person Company (OPC)" <?php echo
                                                                (isset($compDetails->comp_type) &&
                                                                $compDetails->comp_type == 'One person Company (OPC)') ?
                                                                "selected" : "" ?>>One person Company (OPC)</option>
                                                            <option value="LLP Company" <?php echo (isset($compDetails->
                                                                comp_type) && $compDetails->comp_type == 'LLP Company')
                                                                ?
                                                                "selected" : "" ?>>LLP Company</option>
                                                            <option value="PVT Ltd Company" <?php echo
                                                                (isset($compDetails->comp_type) &&
                                                                $compDetails->comp_type == 'PVT Ltd Company') ?
                                                                "selected" : "" ?>>PVT Ltd Company</option>
                                                            <option value="LTD Company" <?php echo (isset($compDetails->
                                                                comp_type) && $compDetails->comp_type == 'LTD Company')
                                                                ?
                                                                "selected" : "" ?>>LTD Company</option>
                                                            <option value="Section-8 Company" <?php echo
                                                                (isset($compDetails->comp_type) &&
                                                                $compDetails->comp_type == 'Section-8 Company') ?
                                                                "selected" : ""
                                                                ?>>Section-8 Company</option>
                                                            <option value="Society/Trust" <?php echo
                                                                (isset($compDetails->comp_type) &&
                                                                $compDetails->comp_type == 'Society/Trust') ?
                                                                "selected" : "" ?>>Society/Trust</option>
                                                            <option value="Other" <?php echo (isset($compDetails->
                                                                comp_type)
                                                                && $compDetails->comp_type == 'Other') ? "selected" : ""
                                                                ?>>Other</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div id="comp_type_other" class="col-sm-4 company-type-input"
                                                    style="display: none;">
                                                    <div class="mb-3">
                                                        <label class="form-label">Enter Custom Company Type <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="other_comp"
                                                            id="other_comp"
                                                            value="{{ isset($compDetails->other_comp_type)?$compDetails->other_comp_type:''}}"
                                                            placeholder="Enter Company Type">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 company-type-input" style="display: none;">
                                                    <div class="mb-3">
                                                        <label class="form-label">CIN <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="cin" id="cin"
                                                            value="{{ isset($compDetails->cin)?$compDetails->cin:""}}"
                                                            placeholder="Enter CIN Number">
                                                    </div>
                                                </div>
                                                <div id="comp_type_other" class="col-sm-4 company-type-input"
                                                    style="display: none;">
                                                    <div class="mb-3">
                                                        <label class="form-label">Incorporation Date</label>
                                                        <input type="date" class="form-control" name="inc_date"
                                                            id="inc_date"
                                                            value="{{ isset($compDetails->inc_date)?$compDetails->inc_date:""}}"
                                                            placeholder="Enter Incorporation Date">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Company have Udyam Registration Number
                                                        (URN)?<span class="text-danger">*</span></label>
                                                    <select class="form-select" name="udyam_reg" id="udyam_reg"
                                                        required>
                                                        <option value="">Select</option>
                                                        <option value="Yes" {{ isset($compDetails->udyam_reg) &&
                                                            $compDetails->udyam_reg == 'Yes' ? 'selected' : '' }}>Yes
                                                        </option>
                                                        <option value="No" {{ isset($compDetails->udyam_reg) &&
                                                            $compDetails->udyam_reg == 'No' ? 'selected' : '' }}>No
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6" id="udyam_reg_no_field"
                                                    style="display: {{ isset($compDetails->udyam_reg) && $compDetails->udyam_reg == 'Yes' ? 'block' : 'none' }};">
                                                    <label class="form-label">Udyam Registration Number(URN)<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="udyam_reg_no"
                                                        id="udyam_reg_no"
                                                        value="{{ isset($compDetails->udyam_reg_no) ? $compDetails->udyam_reg_no : '' }}"
                                                        placeholder="Enter Udyam Registration Number">
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
                                                    <div class="col-sm-12" id="bill_address_view" style="display: none">
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
                                                                value="{{ isset($compDetails->comp_bill_name)?$compDetails->comp_bill_name:""}}">
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
                                                                value="{{ isset($compDetails->comp_bill_addone)?$compDetails->comp_bill_addone:""}}"
                                                                class="form-control" placeholder="Enter Address Line 1">
                                                            {{-- <input type="text" id="billing-address1"
                                                                class="form-control" placeholder="Enter Address Line 1">
                                                            --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Address Line 2 <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="comp_bill_addtwo"
                                                                id="comp_bill_addtwo" required
                                                                value="{{ isset($compDetails->comp_bill_addtwo)?$compDetails->comp_bill_addtwo:""}}"
                                                                class="form-control" placeholder="Enter Address Line 2">
                                                            {{-- <input type="text" id="billing-address2"
                                                                class="form-control" placeholder="Enter Address Line 2">
                                                            --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-0">
                                                            <label class="form-label">State <span
                                                                    class="text-danger">*</span></label>
                                                            {{-- <input type="text" id="billing-state"
                                                                class="form-control" placeholder="Enter State"> --}}
                                                            <select class="form-control select-style"
                                                                name="comp_bill_state" id="state" required>
                                                                <option value="">Select State</option>
                                                                @foreach($states as $k=>$state)
                                                                <option value="{{ $state->id }}" <?php echo @($state->id
                                                                    == $compDetails->comp_bill_state) ? "selected" : ""
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
                                                            <input type="number" name="comp_bill_pin" id="comp_bill_pin"
                                                                required
                                                                value="{{ isset($compDetails->comp_bill_pin)?$compDetails->comp_bill_pin:""}}"
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
                                                <div class="btn btn-primary" id="sameAsBilling">Same as Billing Address
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12" id="ship_address_view" style="display: none">
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
                                                            <label class="form-label">Contact Person Name <span
                                                                    class="text-danger">*</span></label>
                                                            {{-- <input type="text" id="shipping-contact-name"
                                                                class="form-control"
                                                                placeholder="Enter Contact Person Name"> --}}
                                                            <input type="text" name="comp_ship_name" id="comp_ship_name"
                                                                required
                                                                value="{{ isset($compDetails->comp_ship_name)?$compDetails->comp_ship_name:""}}"
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
                                                                value="{{ isset($compDetails->comp_ship_addone)?$compDetails->comp_ship_addone:""}}"
                                                                class="form-control" placeholder="Enter Address Line 1">
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
                                                                value="{{ isset($compDetails->comp_ship_addtwo)?$compDetails->comp_ship_addtwo:""}}"
                                                                class="form-control" placeholder="Enter Address Line 2">
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
                                                                @foreach($states as $k=>$state)
                                                                <option value="{{ $state->id }}" <?php echo @($state->id
                                                                    == $compDetails->comp_bill_state) ? "selected" : ""
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
                                                            <input type="number" name="comp_ship_pin" id="comp_ship_pin"
                                                                value="{{ isset($compDetails->comp_ship_pin)?$compDetails->comp_ship_pin:""}}"
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
                        <div class="tab-pane fade" id="business-details" role="tabpanel"
                            aria-labelledby="business-details-tab">
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
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Exact Nature of Business <span
                                                                class="text-danger">*</span></label>
                                                        {{-- <input type="text" class="form-control"
                                                            name="exact_comp_nature" id="exact_comp_nature"
                                                            placeholder="Enter Nature of Business"> --}}
                                                        <input type="text" class="form-control" name="exact_comp_nature"
                                                            id="exact_comp_nature" class="form-control" required
                                                            value="{{ isset($compDetails->exact_comp_nature)?$compDetails->exact_comp_nature:""}}"
                                                            placeholder="Enter Nature of Business">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Last Year’s Turn Over</label>                                                        
                                                        <input type="number" class="form-control"
                                                            name="turnover_last_year" id="turnover_last_year"
                                                            class="form-control"
                                                            value="{{ isset($compDetails->turnover_last_year)?$compDetails->turnover_last_year:""}}"
                                                            placeholder="Enter Turn over in Last Year">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Start Date of Business</label>
                                                        {{-- <input type="date" class="form-control" name="start_date"
                                                            id="start_date" placeholder="Enter Nature of Business"> --}}
                                                        <input type="date" class="form-control" name="start_date"
                                                            id="start_date" class="form-control"
                                                            value="{{ isset($compDetails->start_date)?$compDetails->start_date:""}}"
                                                            placeholder="Enter Nature of Business">
                                                    </div>
                                                </div>
                                                <div class="mb-0 row align-items-center">
                                                    <label class="col-lg-3 col-form-label">Business Product
                                                        Type:</label>
                                                    <div class="col-lg-6" id="business">
                                                        <!-- <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input input-primary" name="business_product_type" id="Manufacturing" checked>
                                                            <label class="form-check-label" for="Manufacturing">Manufacturing</label>
                                                        </div> -->
                                                        <div class="form-check form-check-inline">
                                                            {{-- <input type="radio"
                                                                class="form-check-input input-primary"
                                                                name="business_product_type" id="Trading" checked> --}}
                                                            <input class="form-check-input" type="radio"
                                                                name="comp_nature" id="profession"
                                                                value="Trading/Reseller" {{
                                                                isset($compDetails->comp_nature)?($compDetails->comp_nature
                                                            =='Trading/Reseller') ? 'checked' : '':"" }}>
                                                            <label class="form-check-label"
                                                                for="Trading">Trading/Reseller</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            {{-- <input type="radio"
                                                                class="form-check-input input-primary"
                                                                name="business_product_type" id="Service"> --}}
                                                            <input class="form-check-input" type="radio"
                                                                name="comp_nature" id="service" value="service" {{
                                                                isset($compDetails->comp_nature)?($compDetails->comp_nature
                                                            =='service') ? 'checked' : '':""}}>
                                                            <label class="form-check-label"
                                                                for="Service">Service</label>
                                                        </div>
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
                        <div class="tab-pane fade" id="bank-details" role="tabpanel" aria-labelledby="bank-details-tab">
                            <form action="javascript:void(0);" name="frmbankdet" id="frmbankdet" method="post">
                                @csrf
                                <div class="message-container"></div>
                                <div class="row">
                                    <?php
                                if (!empty($bankDetails)) {
                                    $i = 1;
                                    foreach ($bankDetails as $bankData) {
                                ?>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="card bank-account">
                                            <div class="message-container"></div>
                                            <div class="card-header mt-3 mb-2">
                                                <h5>Bank Account </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Bank Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="bank_name[]" id=""
                                                                value="{{ $bankData->bank_name }}" class="form-control"
                                                                placeholder="Bank Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Branch <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="bank_branch[]" id=""
                                                                value="{{ $bankData->bank_branch }}"
                                                                class="form-control" placeholder="Enter Branch">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Account Holder Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="bank_holder_name[]" id=""
                                                                value="{{ $bankData->bank_holder_name }}"
                                                                class="form-control" placeholder="Enter Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Account Number <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="ac_no[]" id=""
                                                                value="{{ $bankData->ac_no }}" class="form-control"
                                                                placeholder="Enter Account Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-0">
                                                            <label class="form-label">IFSC Code <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="ifsc_code[]" id=""
                                                                value="{{ $bankData->ifsc_code }}" class="form-control"
                                                                placeholder="Enter IFSC Code ">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-0">
                                                            <label class="form-label">VPA / UPI ID</label>
                                                            <input type="text" name="ac_upid[]" id=""
                                                                value="{{ $bankData->ac_upid }}" class="form-control"
                                                                placeholder="Enter VPA / UPI ID">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                }
                                ?>


                                    <div class="col-lg-6 col-sm-12">
                                        <div class="card bank-account">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h5>Bank Account</h5>
                                                <span class="btn btn-primary" onclick="addBankAccount()">Add New Bank
                                                    Account</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Bank Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="bank_name[]" class="form-control"
                                                                placeholder="Bank Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Branch <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="bank_branch[]"
                                                                id="billing-contact-name" class="form-control"
                                                                placeholder="Enter Branch">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Account Holder Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="bank_holder_name[]"
                                                                class="form-control" placeholder="Enter Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Account Number <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="ac_no[]" class="form-control"
                                                                placeholder="Enter Account Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-0">
                                                            <label class="form-label">IFSC Code <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="ifsc_code[]" class="form-control"
                                                                placeholder="Enter IFSC Code ">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-0">
                                                            <label class="form-label">VPA / UPI ID</label>
                                                            <input type="text" name="ac_upid[]" class="form-control"
                                                                placeholder="Enter VPA / UPI ID">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="bank-account-container">
                                    <!-- Bank accounts will be added here dynamically -->
                                </div>

                                <div class="text-end btn-page">
                                    {{-- <div class="btn btn-outline-secondary">Cancel</div>
                                    <div class="btn btn-primary">Save Changes</div> --}}

                                    <a href="javascript:void(0);" id="cancel_bankDetBtn"
                                        class="btn btn-outline-secondary">Cancel</a>
                                    <button type="submit" id="save_bankDetBtn" class="btn btn-primary">Save
                                        Changes</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->inc_certificate) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" class="fileInput" name="inc_certificate"
                                                            id="inc_certificate"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            @if (!empty($compDetails->inc_certificate))
                                                            @php
                                                            $filePath = asset('storage/admin_files/' .
                                                            $compDetails->inc_certificate);
                                                            @endphp
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->inc_certificate }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                <!-- Force Download Instead of Opening -->
                                                                <a href="{{ $filePath }}"
                                                                    download="{{ $compDetails->inc_certificate }}"
                                                                    class="btn btn-success btn-sm">
                                                                    Download
                                                                </a>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>



                                        {{-- <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Incorporation Certificate</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="upload-text">Click to Upload or Drag & Drop</span>
                                                        <input type="file" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt"
                                                            hidden>
                                                        <div class="file-preview-container"></div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- test --}}




                                        {{-- <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Incorporation Certificate</h5>
                                                </div>


                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="upload-text">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="inc_certificate" id="inc_certificate"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->inc_certificate)) {
                                                                $filePath = asset('storage/admin_files/' . $compDetails->inc_certificate);
                                                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                            ?>
                                                            <div class="existing-file">
                                                                <!-- Preview for Images -->
                                                                <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) { ?>
                                                                <img src="<?= $filePath ?>" alt="Preview"
                                                                    class="preview-image" style="max-width: 100px;">
                                                                <?php } ?>

                                                                <!-- Download Link -->
                                                                <p>
                                                                    <a href="<?= $filePath ?>" target="_blank" download
                                                                        class="btn btn-success btn-sm">
                                                                        Download File
                                                                    </a>
                                                                </p>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
                                                    </label>
                                                </div>

                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Proprietor/ Company Pan Card</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="upload-text">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="pan_doc" id="pan_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->pan_doc)) {
                                                                $filePath = asset('storage/admin_files/' . $compDetails->pan_doc);
                                                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                            ?>
                                                            <div class="existing-file">
                                                                <!-- Preview for Images -->
                                                                <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) { ?>
                                                                <img src="<?= $filePath ?>" alt="Preview"
                                                                    class="preview-image" style="max-width: 100px;">
                                                                <?php } ?>

                                                                <!-- Download Link -->
                                                                <p>
                                                                    <a href="<?= $filePath ?>" target="_blank" download
                                                                        class="btn btn-success btn-sm">
                                                                        Download File
                                                                    </a>
                                                                </p>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Proprietor/ Company PAN Card</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->pan_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" class="fileInput" name="pan_doc" id="pan_doc"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            @if (!empty($compDetails->pan_doc))
                                                            @php
                                                            $filePath = asset('storage/admin_files/' .
                                                            $compDetails->pan_doc);
                                                            @endphp
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->pan_doc }}
                                                                    </div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            @endif
                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->gst_doc) ? 'hidden' : '' }}">Click
                                                            to Upload or Drag & Drop</span>
                                                        <input type="file" name="gst_doc" id="gst_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            @if (!empty($compDetails->gst_doc))
                                                            @php
                                                            $filePath = asset('storage/admin_files/' .
                                                            $compDetails->gst_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                            @endphp
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->gst_doc }}
                                                                    </div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg',
                                                                'png']))
                                                                <img src="{{ $filePath }}" alt="Preview"
                                                                    class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            @endif
                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->trade_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="trade_doc" id="trade_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            @if (!empty($compDetails->trade_doc))
                                                            @php
                                                            $filePath = asset('storage/admin_files/' .
                                                            $compDetails->trade_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                            @endphp
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->trade_doc }}
                                                                    </div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                <!-- Optionally display image preview -->
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg',
                                                                'png'])) --}}

                                                                {{-- @endif --}}
                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            @endif
                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->pf_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="pf_doc" id="pf_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>

                                                        <div class="file-preview-container">
                                                            @if (!empty($compDetails->pf_doc))
                                                            @php
                                                            $filePath = asset('storage/admin_files/' .
                                                            $compDetails->pf_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                            @endphp
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->trade_doc }}
                                                                    </div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            @endif
                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->ptex_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>

                                                        <input type="file" name="ptex_doc" id="ptex_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->ptex_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->ptex_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->ptex_doc }}
                                                                    </div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->first_diraadh_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>

                                                        <input type="file" name="first_diraadh_doc"
                                                            id="first_diraadh_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->first_diraadh_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->first_diraadh_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->first_diraadh_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->firstpan_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="firstpan_doc" id="firstpan_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->firstpan_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->firstpan_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->firstpan_doc
                                                                        }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->first_dirphoto_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="first_dirphoto_doc"
                                                            id="first_dirphoto_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->first_dirphoto_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->first_dirphoto_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->first_dirphoto_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Second Director's Aadhar Card</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->second_aadha_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="second_aadha_doc" id="second_aadha_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->second_aadha_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->second_aadha_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->second_aadha_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->second_pan_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="second_pan_doc" id="second_pan_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->second_pan_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->second_pan_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->second_pan_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->second_dirphoto_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="second_dirphoto_doc"
                                                            id="second_dirphoto_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->second_dirphoto_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->second_dirphoto_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->second_dirphoto_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <h5 class="my-4">Other Documents</h5>
                                        <!-- <div class="col-lg-4 col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Upload Logo</h5>
                                            </div>
                                            <div class="card-body text-center">
                                                <label class="upload-area">
                                                    <span class="upload-text {{ !empty($compDetails->other_logo_doc) ? 'hidden' : '' }}">
                                                        Click to Upload or Drag & Drop
                                                    </span>
                                                    <input type="file" name="other_logo_doc" id="other_logo_doc" class="fileInput"
                                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>

                                                    <div class="file-preview-container">
                                                        <?php if (!empty($compDetails->other_logo_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->other_logo_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->other_logo_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                        <?php } ?>


                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div> -->
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Signeture</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->signature_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="signature_doc" id="signature_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->signature_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->signature_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->signature_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->stamp_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="stamp_doc" id="stamp_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->stamp_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->stamp_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{ $compDetails->stamp_doc }}
                                                                    </div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
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
                                                    <label class="upload-area">
                                                        <span
                                                            class="upload-text {{ !empty($compDetails->other_logo_doc) ? 'hidden' : '' }}">
                                                            Click to Upload or Drag & Drop
                                                        </span>
                                                        <input type="file" name="other_logo_doc" id="other_logo_doc"
                                                            class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                            hidden>

                                                        <div class="file-preview-container">
                                                            <?php if (!empty($compDetails->other_logo_doc)) {
                                                            $filePath = asset('storage/admin_files/' . $compDetails->other_logo_doc);
                                                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        ?>
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name">{{
                                                                        $compDetails->other_logo_doc }}</div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>

                                                                <a href="{{ $filePath }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-1 justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input input-primary" type="checkbox" id="checkbox"
                                                name="checkbox" {{ isset($compDetails) && is_object($compDetails) &&
                                                property_exists($compDetails, 'chk_agree' ) && $compDetails->chk_agree
                                            == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted" for="customCheckc1">
                                                I agree to all the <a href="javascript:void(0);" id="termsLink">Terms &
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
                                                    <h5 class="modal-title text-center" id="termsModalLabel">Terms and
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

                        

                        <div class="tab-pane fade" id="holidays" role="tabpanel" aria-labelledby="holidays-tab">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5><i class="ph-duotone ph-calendar me-2"></i>Company Holidays</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#holidayModal">
                                        <i class="ti ti-plus"></i> Add Holiday
                                    </button>
                                </div>


                                <!-- Holiday Statistics -->
                                <div class="row mt-4">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h4 class="mb-1">{{ $holidays->filter(fn($h) =>
                                                    \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count()
                                                    }}</h4>
                                                <small>Total Holidays</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h4 class="mb-1">{{ $holidays->filter(fn($h) => $h->holidayType ===
                                                    'National' && \Carbon\Carbon::parse($h->holidayDate)->year ==
                                                    date('Y'))->count() }}</h4>
                                                <small>National Holidays</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h4 class="mb-1">{{ $holidays->filter(fn($h) => $h->holidayType ===
                                                    'Festival' && \Carbon\Carbon::parse($h->holidayDate)->year ==
                                                    date('Y'))->count() }}</h4>
                                                <small>Festival Holidays</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h4 class="mb-1">{{ $holidays->filter(fn($h) => $h->holidayType ===
                                                    'Company' && \Carbon\Carbon::parse($h->holidayDate)->year ==
                                                    date('Y'))->count() }}</h4>
                                                <small>Company Holidays</small>
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
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <form id="holidayForm">
                                                    <input type="hidden" id="holidayId" name="holidayId" value="">
                                                    <input type="hidden" id="formMethod" name="_method" value="POST">
                                                    <div class="modal-body">
                                                        <!-- Holiday name -->
                                                        <div class="mb-3">
                                                            <label for="holidayName" class="form-label">Holiday
                                                                Name</label>
                                                            <input type="text" id="holidayName" name="holidayName"
                                                                class="form-control" required>
                                                        </div>

                                                        <!-- Date -->
                                                        <div class="mb-3">
                                                            <label for="holidayDate" class="form-label">Date</label>
                                                            <input type="date" id="holidayDate" name="holidayDate"
                                                                class="form-control" required>
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
                                                            <textarea id="holidayDescription" name="holidayDescription"
                                                                class="form-control" rows="3"
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
                                                    @if($holidays->count() > 0)
                                                    @foreach($holidays as $index => $holiday)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="ti ti-calendar-event 
                                                                            @if($holiday->holidayType == 'National') text-primary
                                                                            @elseif($holiday->holidayType == 'Festival') text-warning
                                                                            @elseif($holiday->holidayType == 'Company') text-info
                                                                            @elseif($holiday->holidayType == 'Regional') text-success
                                                                            @else text-secondary
                                                                            @endif me-2"></i>
                                                                <strong>{{ $holiday->holidayName }}</strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                        @if($holiday->holidayType == 'National') bg-light-primary text-primary
                                                                        @elseif($holiday->holidayType == 'Festival') bg-light-warning text-warning
                                                                        @elseif($holiday->holidayType == 'Company') bg-light-info text-info
                                                                        @elseif($holiday->holidayType == 'Regional') bg-light-success text-success
                                                                        @else bg-light-secondary text-secondary
                                                                        @endif">{{
                                                                \Carbon\Carbon::parse($holiday->holidayDate)->format('d-m-Y')
                                                                }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                        @if($holiday->holidayType == 'National') bg-primary
                                                                        @elseif($holiday->holidayType == 'Festival') bg-warning
                                                                        @elseif($holiday->holidayType == 'Company') bg-info
                                                                        @elseif($holiday->holidayType == 'Regional') bg-success
                                                                        @else bg-secondary
                                                                        @endif">{{ $holiday->holidayType }}</span>
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
                                        @if($holidays->count() == 0)
                                        <div id="emptyState" class="text-center py-5">
                                            <div class="mb-3">
                                                <i class="ti ti-calendar-off" style="font-size: 4rem; color: #ccc;"></i>
                                            </div>
                                            <h5 class="text-muted">No Holidays Found</h5>
                                            <p class="text-muted mb-3">You haven't added any holidays yet.</p>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#holidayModal">
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
                                                    $mondayLunchStart = $monday ? $monday->lunch_time_start : '12:00';
                                                    $mondayLunchStop = $monday ? $monday->lunch_time_stop : '13:00';
                                                    $mondayStatus = $monday ? ($monday->status == 'open') : true;
                                                    $mondayHours = $monday ? $monday->working_hours . ' hours' : '8
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $mondayOpen }}" name="monday_open" {{
                                                                    !$mondayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $mondayClose }}" name="monday_close" {{
                                                                    !$mondayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $mondayLunchStart }}"
                                                                    name="monday_lunch_start" {{ !$mondayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $mondayLunchStop }}"
                                                                    name="monday_lunch_stop" {{ !$mondayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $mondayStatus ? 'checked' : '' }}
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
                                                    $tuesdayLunchStart = $tuesday ? $tuesday->lunch_time_start :
                                                    '12:00';
                                                    $tuesdayLunchStop = $tuesday ? $tuesday->lunch_time_stop : '13:00';
                                                    $tuesdayStatus = $tuesday ? ($tuesday->status == 'open') : true;
                                                    $tuesdayHours = $tuesday ? $tuesday->working_hours . ' hours' : '7
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $tuesdayOpen }}" name="tuesday_open" {{
                                                                    !$tuesdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $tuesdayClose }}" name="tuesday_close" {{
                                                                    !$tuesdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $tuesdayLunchStart }}"
                                                                    name="tuesday_lunch_start" {{ !$tuesdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $tuesdayLunchStop }}"
                                                                    name="tuesday_lunch_stop" {{ !$tuesdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $tuesdayStatus ? 'checked' : ''
                                                                    }} name="tuesday_status">
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
                                                    $wednesdayOpen = $wednesday ? $wednesday->opening_time : '09:00';
                                                    $wednesdayClose = $wednesday ? $wednesday->closing_time : '18:00';
                                                    $wednesdayLunchStart = $wednesday ? $wednesday->lunch_time_start :
                                                    '12:00';
                                                    $wednesdayLunchStop = $wednesday ? $wednesday->lunch_time_stop :
                                                    '13:00';
                                                    $wednesdayStatus = $wednesday ? ($wednesday->status == 'open') :
                                                    true;
                                                    $wednesdayHours = $wednesday ? $wednesday->working_hours . ' hours'
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $wednesdayOpen }}" name="wednesday_open"
                                                                    {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $wednesdayClose }}" name="wednesday_close"
                                                                    {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $wednesdayLunchStart }}"
                                                                    name="wednesday_lunch_start" {{ !$wednesdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $wednesdayLunchStop }}"
                                                                    name="wednesday_lunch_stop" {{ !$wednesdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $wednesdayStatus ? 'checked' : ''
                                                                    }} name="wednesday_status">
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
                                                    $thursdayOpen = $thursday ? $thursday->opening_time : '09:30';
                                                    $thursdayClose = $thursday ? $thursday->closing_time : '18:30';
                                                    $thursdayLunchStart = $thursday ? $thursday->lunch_time_start :
                                                    '12:00';
                                                    $thursdayLunchStop = $thursday ? $thursday->lunch_time_stop :
                                                    '13:00';
                                                    $thursdayStatus = $thursday ? ($thursday->status == 'open') : true;
                                                    $thursdayHours = $thursday ? $thursday->working_hours . ' hours' :
                                                    '9 hours';
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $thursdayOpen }}" name="thursday_open" {{
                                                                    !$thursdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $thursdayClose }}" name="thursday_close"
                                                                    {{ !$thursdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $thursdayLunchStart }}"
                                                                    name="thursday_lunch_start" {{ !$thursdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $thursdayLunchStop }}"
                                                                    name="thursday_lunch_stop" {{ !$thursdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $thursdayStatus ? 'checked' : ''
                                                                    }} name="thursday_status">
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
                                                    $fridayLunchStart = $friday ? $friday->lunch_time_start : '12:00';
                                                    $fridayLunchStop = $friday ? $friday->lunch_time_stop : '13:00';
                                                    $fridayStatus = $friday ? ($friday->status == 'open') : true;
                                                    $fridayHours = $friday ? $friday->working_hours . ' hours' : '7
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $fridayOpen }}" name="friday_open" {{
                                                                    !$fridayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $fridayClose }}" name="friday_close" {{
                                                                    !$fridayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $fridayLunchStart }}"
                                                                    name="friday_lunch_start" {{ !$fridayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $fridayLunchStop }}"
                                                                    name="friday_lunch_stop" {{ !$fridayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $fridayStatus ? 'checked' : '' }}
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
                                                    $saturdayOpen = $saturday ? $saturday->opening_time : '10:00';
                                                    $saturdayClose = $saturday ? $saturday->closing_time : '14:00';
                                                    $saturdayLunchStart = $saturday ? $saturday->lunch_time_start :
                                                    '12:00';
                                                    $saturdayLunchStop = $saturday ? $saturday->lunch_time_stop :
                                                    '13:00';
                                                    $saturdayStatus = $saturday ? ($saturday->status == 'open') : false;
                                                    $saturdayHours = $saturday ? $saturday->working_hours . ' hours' :
                                                    '0 hours';
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $saturdayOpen }}" name="saturday_open" {{
                                                                    !$saturdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $saturdayClose }}" name="saturday_close"
                                                                    {{ !$saturdayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $saturdayLunchStart }}"
                                                                    name="saturday_lunch_start" {{ !$saturdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $saturdayLunchStop }}"
                                                                    name="saturday_lunch_stop" {{ !$saturdayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $saturdayStatus ? 'checked' : ''
                                                                    }} name="saturday_status">
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
                                                    $sundayLunchStart = $sunday ? $sunday->lunch_time_start : '12:00';
                                                    $sundayLunchStop = $sunday ? $sunday->lunch_time_stop : '13:00';
                                                    $sundayStatus = $sunday ? ($sunday->status == 'open') : false;
                                                    $sundayHours = $sunday ? $sunday->working_hours . ' hours' : '0
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
                                                                <input type="time" class="form-control opening-time"
                                                                    value="{{ $sundayOpen }}" name="sunday_open" {{
                                                                    !$sundayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $sundayClose }}" name="sunday_close" {{
                                                                    !$sundayStatus ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $sundayLunchStart }}"
                                                                    name="sunday_lunch_start" {{ !$sundayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $sundayLunchStop }}"
                                                                    name="sunday_lunch_stop" {{ !$sundayStatus
                                                                    ? 'disabled' : '' }}>
                                                                <i class="ti ti-clock position-absolute"
                                                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input status-toggle"
                                                                    type="checkbox" {{ $sundayStatus ? 'checked' : '' }}
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

                        <!-- Admin Locations Tab -->
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
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="card bg-primary text-white">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-1">{{ $locations->count() }}</h4>
                                                    <small>Total Locations</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-success text-white">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-1">{{ $locations->where('status', 'Active')->count()
                                                        }}</h4>
                                                    <small>Active Locations</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-warning text-white">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-1">{{ $locations->where('location_type', 'Head
                                                        Office')->count() }}</h4>
                                                    <small>Head Office</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-info text-white">
                                                <div class="card-body text-center">
                                                    <h4 class="mb-1">{{ $locations->where('location_type', 'Branch
                                                        Office')->count() }}</h4>
                                                    <small>Branch Offices</small>
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
                                                @if($locations->count() > 0)
                                                @foreach($locations as $index => $location)
                                                @php
                                                // Define icon and color based on location type
                                                $iconClass = 'ti ti-building';
                                                $iconColor = 'text-primary';
                                                $badgeClass = 'bg-primary';

                                                switch($location->location_type) {
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
                                                                    class="{{ $location->status == 'Inactive' ? 'text-muted' : '' }}">{{
                                                                    $location->location_name }}</strong>
                                                                <br><small class="text-muted">{{
                                                                    $location->location_type }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $location->status == 'Inactive' ? 'bg-light text-dark' : $badgeClass }}">{{
                                                            $location->location_type }}</span>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <small
                                                                class="{{ $location->status == 'Inactive' ? 'text-muted' : '' }}">
                                                                <i
                                                                    class="ti ti-map-pin me-1 {{ $location->status == 'Inactive' ? 'text-muted' : 'text-primary' }}"></i>{{
                                                                $location->latitude }}, {{ $location->longitude }}
                                                            </small><br>
                                                            <small class="text-muted">Radius: {{ $location->radius
                                                                }}m</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $location->status == 'Active' ? 'bg-success' : 'bg-danger' }}">{{
                                                            $location->status }}</span>
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
                                                        <p class="text-muted mb-3">You haven't added any locations yet.
                                                        </p>
                                                        <button type="button" class="btn btn-primary"
                                                            data-bs-toggle="modal" data-bs-target="#locationModal">
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

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="message-container"></div>
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="exampleFormControlTextarea1">Write Message</label>
                        <textarea class="form-control" name="msg" id="msg" rows="3"
                            placeholder="Enter Message"></textarea>
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
<div class="modal fade" id="gstDetailsModal" tabindex="-1" aria-labelledby="gstDetailsModalLabel" aria-hidden="true">
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
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
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
                            <input type="text" class="form-control" id="locationName" name="locationName" required>
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
                                            <input type="number" class="form-control" id="latitude" name="latitude"
                                                step="any" placeholder="e.g., 19.0760" required>
                                            <small class="text-muted">Enter latitude coordinates</small>
                                        </div>

                                        <!-- Longitude -->
                                        <div class="col-md-6 mb-3">
                                            <label for="longitude" class="form-label">Longitude <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="longitude" name="longitude"
                                                step="any" placeholder="e.g., 72.8777" required>
                                            <small class="text-muted">Enter longitude coordinates</small>
                                        </div>

                                        <!-- Radius -->
                                        <div class="col-md-6 mb-3">
                                            <label for="radius" class="form-label">Geofence Radius (meters) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="radius" name="radius" min="10"
                                                max="5000" placeholder="e.g., 500" required>
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
                                        <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i>Location Preview
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
<div class="modal fade" id="mapPickerModal" tabindex="-1" aria-labelledby="mapPickerModalLabel" aria-hidden="true">
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
                                    <input type="text" class="form-control form-control-sm" id="selectedLatitude"
                                        readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-sm">Longitude</label>
                                    <input type="text" class="form-control form-control-sm" id="selectedLongitude"
                                        readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-sm">Address</label>
                                    <textarea class="form-control form-control-sm" id="selectedAddress" rows="3"
                                        readonly></textarea>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
            // Check the value of udyam_reg and display/hide the field accordingly
            if ($('#udyam_reg').val() === 'Yes') {
                $('#udyam_reg_no_field').show();
                $('#udyam_reg_no').attr('required', true);
            } else {
                $('#udyam_reg_no_field').hide();
                $('#udyam_reg_no').removeAttr('required').val('');
            }

            // Listen for changes on the udyam_reg dropdown
            $('#udyam_reg').on('change', function () {
                if ($(this).val() === 'Yes') {
                    $('#udyam_reg_no_field').show();
                    $('#udyam_reg_no').attr('required', true);
                } else {
                    $('#udyam_reg_no_field').hide();
                    $('#udyam_reg_no').removeAttr('required').val('');
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
                gstDropdown.addEventListener("change", function () {
                    toggleGSTFields(this.value);
                });
            }


                    $(document).ready(function () {
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
                        data: { id: billingState },
                        success: function (data) {
                            // console.log('Initial billing cities loaded:', data);
                            let str = '<option value="">Select City</option>';
                            $.each(data, function (idx, item) {
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
                        data: { id: shippingState },
                        success: function (data) {
                            // console.log('Initial shipping cities loaded:', data);
                            let str = '<option value="">Select City</option>';
                            $.each(data, function (idx, item) {
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

            //----------- Form Submit Admin Profile ----------
            $("form#frmcompdet").bind("submit", function() {

                // $("#loader").show();
                var formCompData = {
                    gst_reg: $("#frmcompdet #gst_reg").val(),
                    gst_no: $("#frmcompdet #gst_no").val(),
                    comp_tran_type: $("#frmcompdet #comp_tran_type").val(),
                    comp_name: $("#frmcompdet #comp_name").val(),
                    comp_type: $("#frmcompdet #comp_type").val(),
                    cin: $("#frmcompdet #cin").val(),
                    inc_date: $("#frmcompdet #inc_date").val(),
                    comp_tan: $("#frmcompdet #comp_tan").val(),
                    comp_epf: $("#frmcompdet #comp_epf").val(),
                    comp_esic: $("#frmcompdet #comp_esic").val(),
                    comp_ptax_cert: $("#frmcompdet #comp_ptax_cert").val(),
                    comp_ptax: $("#frmcompdet #comp_ptax").val(),
                    comp_phone: $("#frmcompdet #comp_phone").val(),
                    comp_email: $("#frmcompdet #comp_email").val(),
                    comp_pan_no: $("#frmcompdet #comp_pan_no").val(),
                    comp_website: $("#frmcompdet #comp_website").val(),
                    other_comp_type: $("#frmcompdet #other_comp").val(),
                    basic_percentage: $("#frmcompdet #basic_percentage").val(),

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
                    udyam_reg: $("#frmcompdet #udyam_reg").val(),
                    udyam_reg_no: $("#frmcompdet #udyam_reg_no").val(),

                };



                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/admin_update_compdet",
                    type: "POST",
                    data: formCompData,
                    success: function(response) {
                        var messageContainer = $("#frmcompdet .message-container");
                        messageContainer.html("");



                        if (response.class == "succ") {
                            showToast("Profile Information updated successfully!", "success");
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Upload failed: " + data.message, "error");
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


            document.getElementById("sameAsBilling").addEventListener("click", function () {
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
                        data: { id: billingState },
                        success: function (data) {
                            let str = '<option value="">Select City</option>';
                            $.each(data, function (idx, item) {
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

            //Start admin business details


            $("form#frmbusdet").bind("submit", function() {


                var formCompBusData = {
                    comp_nature: $('input[name="comp_nature"]:checked').val(),
                    exact_comp_nature: $("#frmbusdet #exact_comp_nature").val(),
                    turnover_last_year: $("#frmbusdet #turnover_last_year").val(),
                    start_date: $("#frmbusdet #start_date").val(),
                };
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/admin_update_businessdet",
                    type: "POST",
                    data: formCompBusData,
                    success: function(response) {
                        var messageContainer = $("#frmbusdet .message-container");
                        messageContainer.html("");

                        // if (response.class == "succ") {
                        //     $("#frmbusdet .message-container")
                        //         .append(
                        //             '<div class="' +
                        //                 response.class +
                        //                 '">' +
                        //                 response.message +
                        //                 "</div>"
                        //         )
                        //         .show();
                        // } else {

                        //     $.each(response, function (idx, obj) {
                        //         messageContainer.append(
                        //             '<div class="err">' + obj + "</div>"
                        //         );
                        //     });
                        // }

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

            //-------- Bank Details add --------
            $("form#frmbankdet").on("submit", function(e) {
                //e.preventDefault();

                var formCompBank = $("form#frmbankdet").serialize();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/admin_update_bankdet",
                    type: "POST",
                    data: formCompBank,
                    success: function(response) {
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
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/admin_update_comp_attachment",
                    type: "POST",
                    data: comp_atta_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var messageContainer = $("#frmattadet .message-container");
                        messageContainer.html(""); // Clear previous messages

                        if (response.class == "succ") {
                            $("#gstdocstate").val(response.gstdocstate);
                            // showToast("Profile picture updated successfully!", "success");
                            showToast(response.message, "success");
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
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

                    //  // success: function(response) {
                    //  //  if (response.class=="succ") {
                    //  //      $("#gstdocstate").val(response.gstdocstate);
                    //  //      $("#frmattadet .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                    //  //  } else {
                    //  //      $('#loader').hide();
                    //  //      $.each(response, function(idx, obj) {
                    //  //          //alert(obj);
                    //  //          $("#frmattadet .message-container").html('<div class="err">'+obj+'</div>');
                    //  //      });
                    //  //  }
                    //  // }
                });

                //}
            });

            document.getElementById('fileUpload').addEventListener('change', function(event) {
                let file = event.target.files[0];
                if (file) {
                    let formData = new FormData();
                    formData.append('fileUpload', file);

                    fetch("{{ route('adminUpload.profile.image') }}", {
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
                                // setTimeout(() => location.reload(), 2000); // Reload after 2s
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


        // User Module admin Profile Logo Update
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



        // User Module Add New Billing address In admin Profile

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

        // User Module Add New Bank Account In admin Profile
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
                                    <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
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

    document.addEventListener("DOMContentLoaded", function () {
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
                    gstNoError.textContent = gstVal === "" ? "GST Number is required." : "Invalid GST No. format.";
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
        $('#get_gst_btn').click(function () {
        let gstin = $('#gst_no').val();

        $.ajax({
            url: '{{ route("gst.fetch") }}',
            type: 'POST',
            data: {
                gstin: gstin,
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
        if (data && data.data) {
            let d = data.data;
            let address = "";

            if (d.pradr && d.pradr.addr) {
                const a = d.pradr.addr;
                address = `
                    ${a.bno || ''} ${a.bnm || ''}, ${a.flno || ''}, ${a.st || ''}, ${a.loc || ''}, ${a.dst || ''}, ${a.stcd || ''} - ${a.pncd || ''}
                `;
            }

            let html = `
                <p><strong>GSTIN:</strong> ${d.gstin}</p>
                <p><strong>Legal Name:</strong> ${d.lgnm}</p>
                <p><strong>Trade Name:</strong> ${d.tradeNam}</p>
                <p><strong>Constitution of Business:</strong> ${d.ctb}</p>
                <p><strong>Taxpayer Type:</strong> ${d.dty}</p>
                <p><strong>GST Status:</strong> ${d.sts}</p>
                <p><strong>Registration Date:</strong> ${d.rgdt}</p>
                <p><strong>Location:</strong> ${address}</p>
            `;

            $('#gst_modal_body').html(html);

                // Show modal (Bootstrap 5)
                var modal = new bootstrap.Modal(document.getElementById('gstDetailsModal'));
                modal.show();
                    } else {
                        alert("GST Details is not Found, Please check you GST No.");
                        }
                    },

            error: function (xhr) {
                console.error(xhr.responseText);

                // Show a readable error message
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
        const url = isEdit ? `/adminHolidays/${holidayId}` : '/adminHolidays';
        const httpMethod = isEdit ? 'PUT' : 'POST';

        try {
            // Convert FormData to JSON for PUT requests
            let requestBody;
            let headers = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
            if(response.ok) {
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
        fetch(`/adminHolidays/${holidayId}/edit`, {
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
    document.getElementById('holidayModal').addEventListener('hidden.bs.modal', function () {
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
        fetch(`/adminHolidays/${holidayId}`, {
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
        document.querySelectorAll('.opening-time, .closing-time, .lunch-start-time, .lunch-end-time').forEach(function(input) {
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
                    monday: { open: '10:00', close: '18:00', status: true },
                    tuesday: { open: '10:00', close: '18:00', status: true },
                    wednesday: { open: '10:00', close: '18:00', status: true },
                    thursday: { open: '10:00', close: '18:00', status: true },
                    friday: { open: '10:00', close: '18:00', status: true },
                    saturday: { open: '10:00', close: '14:00', status: false },
                    sunday: { open: '11:00', close: '15:00', status: false }
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

    $('#scheduleForm').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        $('.schedule-row').each(function () {
            const $row = $(this);
            const day = $row.data('day');
            const isChecked = $row.find('.status-toggle').is(':checked');
            const opening = $row.find('.opening-time').val();
            const closing = $row.find('.closing-time').val();
            const lunchStart = $row.find('.lunch-start-time').val();
            const lunchEnd = $row.find('.lunch-end-time').val();

            formData.append(day + '_open', opening);
            formData.append(day + '_close', closing);
            formData.append(day + '_lunch_start', lunchStart);
            formData.append(day + '_lunch_stop', lunchEnd);
            if (isChecked) {
                formData.append(day + '_status', '1');
            }
        });

        $.ajax({
            url: '/admin-save-schedule',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === 'success') {
                    showToast(res.message, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(res.message, 'error');
                }
            },
            error: function (xhr) {
                let message = 'An error occurred while saving.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
            }
        });
    });

    // admin Locations Management
    let editingLocationId = null;



    // Edit Location Function
    function editLocation(locationId) {
        editingLocationId = locationId;
        
        // Fetch location data from server
        $.ajax({
            url: '/get-location-admin/' + locationId,
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
                    url: '/delete-location-admin/' + locationId,
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
    $('#locationForm').on('submit', function (e) {
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
        console.log('Form Data being sent:', formData);

        // Validate required fields
        if (!formData.locationName || !formData.locationType || !formData.latitude || !formData.longitude || !formData.radius) {
            showToast('Please fill in all required fields.', 'error');
            return;
        }

        // Determine if this is an edit or create operation
        const isEdit = editingLocationId !== null;
        const url = isEdit ? '/update-location-admin/' + editingLocationId : '{{ route("adminsave.location") }}';
        const method = isEdit ? 'PUT' : 'POST';
        
        if (isEdit) {
            formData._method = 'PUT';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
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
            error: function (xhr, status, error) {
                console.log('AJAX Error:', xhr.responseText);
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
        selectedCoordinates = { lat: lat, lng: lng };
        
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

</script>




@endsection