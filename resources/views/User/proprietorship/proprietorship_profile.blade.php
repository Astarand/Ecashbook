@extends('App.Layout')

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

@section('container')
<style>
  /* 💎 Clean Premium Profile Style Overrides 💎 */

  /* Alert Box: More prominent, modern warning-amber box */
  .alert-warning.card {
    background-color: #fef3c7 !important; /* Rich amber background */
    border: 1px solid #fcd34d !important; /* Solid amber border */
    border-left: 6px solid #d97706 !important; /* Bold amber side accent */
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(217, 119, 6, 0.06) !important;
    overflow: hidden !important;
  }
  
  .alert-warning .alert-heading {
    color: #92400e !important; /* Deep dark amber heading */
    font-weight: 700 !important;
    font-size: 1.1rem !important;
  }
  
  .alert-warning p {
    color: #78350f !important; /* Legible warm text */
    font-size: 0.9rem !important;
    font-weight: 500 !important;
  }

  /* Sidebar Pills styling */
  .account-pills.nav-pills {
    background-color: #ffffff !important;
    border-top: 1px solid #e2e6ee !important;
    padding: 10px 0 !important;
  }

  .account-pills.nav-pills .nav-link {
    border-radius: 8px !important;
    margin: 4px 12px !important;
    padding: 12px 16px !important;
    color: #5e6e82 !important;
    font-weight: 500 !important;
    border: none !important;
    background: transparent !important;
    transition: all 0.2s ease-in-out !important;
  }

  .account-pills.nav-pills .nav-link::after {
    display: none !important; /* Hide old generic sidebar highlight line */
  }

  .account-pills.nav-pills .nav-link:hover {
    color: #422f90 !important;
    background-color: #f5f4fa !important;
    transform: translateX(4px) !important;
  }

  .account-pills.nav-pills .nav-link.active {
    color: #ffffff !important;
    background-color: #422f90 !important; /* Brand accent color for active item */
    box-shadow: 0 4px 12px rgba(66, 47, 144, 0.2) !important;
    font-weight: 600 !important;
  }

  .account-pills.nav-pills .nav-link.active i {
    color: #ffffff !important;
  }

  .account-pills.nav-pills .nav-link.active:hover {
    transform: none !important;
    background-color: #2d1f6a !important;
  }

  /* Form controls styling */
  .form-control, .form-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    font-size: 0.9rem !important;
    transition: all 0.2s ease !important;
  }

  .form-control:focus, .form-select:focus {
    border-color: #422f90 !important;
    box-shadow: 0 0 0 3px rgba(66, 47, 144, 0.15) !important;
    outline: none !important;
  }

  /* Cards inside layout */
  .card {
    border: 1px solid #e2e6ee !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 12px rgba(66, 47, 144, 0.015) !important;
  }

  .card-header {
    border-bottom: 1px solid #e2e6ee !important;
    background-color: #ffffff !important;
    padding: 18px 24px !important;
  }

  .card-header h5 {
    font-weight: 600 !important;
    color: #2d1f6a !important;
    margin-bottom: 0 !important;
  }

  /* Standardizing Buttons */
  .btn-primary {
    background-color: #422f90 !important;
    border-color: #422f90 !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
    color: #ffffff !important;
  }

  .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #2d1f6a !important;
    border-color: #2d1f6a !important;
    color: #ffffff !important;
  }

  .btn-secondary {
    background-color: #eae7f7 !important;
    border-color: #eae7f7 !important;
    color: #422f90 !important;
    border-radius: 8px !important;
    padding: 10px 20px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
  }

  .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active {
    background-color: #dcd7f2 !important;
    color: #2d1f6a !important;
  }

  /* Upload Areas */
  .upload-area {
    border: 2px dashed #cbd5e1 !important;
    padding: 30px 20px !important;
    border-radius: 10px !important;
    color: #5e6e82 !important;
    background-color: #f8fafc !important;
    transition: all 0.2s ease-in-out !important;
    display: inline-flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    width: 100% !important;
    max-width: 100% !important;
  }

  .upload-area:hover {
    border-color: #422f90 !important;
    background-color: #f5f4fa !important;
    color: #422f90 !important;
  }

  /* Profile Picture Upload Pencil Button */
  label[for="fileUpload"].btn-primary.btn-icon {
    background-color: #ffffff !important;
    background: #ffffff !important;
    border: 1px solid #e2e6ee !important;
    color: #422f90 !important;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
    padding: 0 !important;
    width: 34px !important;
    height: 34px !important;
    border-radius: 50% !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }

  label[for="fileUpload"].btn-primary.btn-icon:hover {
    background-color: #422f90 !important;
    background: #422f90 !important;
    border-color: #422f90 !important;
    color: #ffffff !important;
    box-shadow: 0 6px 14px rgba(66, 47, 144, 0.25) !important;
    transform: scale(1.08) !important;
  }

  label[for="fileUpload"].btn-primary.btn-icon:active {
    transform: scale(0.95) !important;
  }

  /* Uniform Table Action Buttons */
  .table .btn.btn-sm {
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 6px !important;
  }
</style>

    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('/proprietorship-list') }}">Proprietorship Companies List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Proprietorship Companies Profile</li>
                        </ul>
                    </div>
					<div class="col-md-4">
						<div class="page-header-title">
							<h2 class="mb-0">Proprietorship Companies Profile</h2>
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
													<input type="hidden" id="uid" value="{{ $compDetails->id }}" />
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
                                    <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Company
                                        Details</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="business-details-tab"
                                    data-bs-toggle="pill" href="#business-details" role="tab"
                                    aria-controls="business-details" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-database m-r-10"></i>Business
                                        Details</span>
                                </a>
                                <a class="nav-link list-group-item list-group-item-action" id="contact-details-tab"
                                    data-bs-toggle="pill" href="#contact-details" role="tab"
                                    aria-controls="contact-details" aria-selected="false">
                                    <span class="f-w-500"><i class="ph-duotone ph-phone m-r-10"></i>Contact
                                        Details</span>
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
                                    <span class="f-w-500"><i
                                            class="ph-duotone ph-arrow-square-up m-r-10"></i>Attachments</span>
                                </a>
                                

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-xxl-9">
                        <div class="tab-content" id="company-profile-set-tabContent">
                            <div class="tab-pane fade show active" id="company-details" role="tabpanel"
                                aria-labelledby="company-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">These details help verify business identity and enable accurate tax automation with timely compliance reminders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmcompdetPro" id="frmcompdetPro">
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
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">P-Tax Registration Number </label>
                                                        <input type="text" id="comp_ptax" name="comp_ptax"
                                                            class="form-control"
                                                            value="{{ isset($compDetails->comp_ptax) ? $compDetails->comp_ptax : '' }}"
                                                            placeholder="Enter P-Tax Registration Number">
                                                    </div>
                                                </div>

                                                {{-- <div class="col-sm-4 mb-4">
                                                    <label class="form-label">Set Basic Salary Percentage <span class="text-danger">*</span></label>
                                                    
                                                    <input type="number" readonly required class="form-control" name="basic_percentage" value="{{ isset($basic_percentage)?$basic_percentage:""}}" id="basic_percentage" placeholder="Enter percentage (40-60%)" min="40" max="60" step="1" oninput="this.value = Math.max(40, Math.min(60, this.value))">
                                                    <small class="form-text text-muted">Enter percentage between 40% to 60%</small>
                                                </div> --}}

                                                <div class="row">
                                                    
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
                                    

                                    
                                    <div class="text-end btn-page">
                                        <a href="javascript:void(0);" id="cancel_compDetBtn" class="btn customer-btn-cancel">Cancel</a>
                                        <button type="submit" id="save_compDetBtn" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="business-details" role="tabpanel"
                                aria-labelledby="business-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">These details help verify business identity and enable accurate tax automation with timely compliance reminders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmbusdetPro" id="frmbusdetPro" method="post">
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
                                                    <div class="col-sm-6">
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
                                                    <div class="col-sm-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Sales Invoice Series Setup </label>
                                                            <input type="text" class="form-control"
                                                                name="comp_inv_digits" id="comp_inv_digits" maxlength="16"
                                                                value="{{ isset($compDetails->comp_inv_digits) ? $compDetails->comp_inv_digits : '' }}"
                                                                placeholder="e.g., SI/2024-25/">
                                                            <small class="form-text text-muted">This replaces Company Invoice Number Digits</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
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

                            <div class="tab-pane fade" id="contact-details" role="tabpanel"
                                aria-labelledby="contact-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">These details help verify business identity and enable accurate tax automation with timely compliance reminders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" name="frmcontdetPro" id="frmcontdetPro" method="post">
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
                                                                value="{{ $compDetails->comp_phone }}"
                                                                placeholder="Enter Company Contact No" maxlength="10">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Company Mail ID <span class="text-danger">*</span></label>
                                                            <input type="email" class="form-control" name="comp_email"
                                                                id="comp_email" required
                                                                value="{{ $compDetails->comp_email }}"
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
                                                            <input type="text" class="form-control" name="comp_website"
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
                                                <p class="mb-2">These details help verify business identity and enable accurate tax automation with timely compliance reminders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
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
                                                    <i class="ti ti-plus me-1"></i>Add Another Director
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

                            <div class="tab-pane fade" id="bank-details" role="tabpanel"
                                aria-labelledby="bank-details-tab">
                                <div class="card alert alert-warning p-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 me-3">
                                                <h3 class="alert-heading">Alert!</h3>
                                                <p class="mb-2">These details help verify business identity and enable accurate tax automation with timely compliance reminders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
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

									<div class="col-md-6 col-xxl-4">
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
														<div class="row mt-4">
															<div class="col-auto">
																<p class="text-white text-sm text-opacity-50 mb-0">Account Name</p>
																<h6 class="text-white mb-3">{{ $val->accholder_name }}</h6>
															</div>
															<div class="col-auto">
																<p class="text-white text-sm text-opacity-50 mb-0">Account Balance</p>
																<h6 class="text-white mb-0">₹{{ $val->curr_bal }}</h6>
															</div>
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
												<form id="upadteBankForm{{ $val->id }}" method="post" >
													<input type="hidden" name="id"  value="{{ $val->id }}">
													<input type="hidden" name="propId"  value="{{ $compDetails->id }}">
													<input type="hidden" name="redirectUrl" value="proprietorship-edit/{{ Crypt::encrypt($compDetails->id) }}">
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
                                                <p class="mb-2">These details help verify business identity and enable accurate tax automation with timely compliance reminders</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <img src="../assets/images/application/img-accout-alert.png"
                                                    alt="img" class="img-fluid wid-80">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <h5>Statutory Details</h5>

                                    <form action="javascript:void(0);" name="frmattadetPro" id="frmattadetPro" method="post"
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

                                                                @if(isset($docs['Certificate of Incorporation']))
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
                                                                {{ isset($docs['Certificate of Incorporation']) ? 'disabled' : '' }}
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

                                                                @if(isset($docs['Company PAN Card']))
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
                                                                {{ isset($docs['Company PAN Card']) ? 'disabled' : '' }}
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

                                                                @if(isset($docs['GST Registration Certificate']))
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
                                                                {{ isset($docs['GST Registration Certificate']) ? 'disabled' : '' }}
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

                                                                @if(isset($docs['Trade License']))
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
                                                                {{ isset($docs['Trade License']) ? 'disabled' : '' }}
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

                                                                @if(isset($docs['Professional Tax Returns']))
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
                                                                {{ isset($docs['Professional Tax Returns']) ? 'disabled' : '' }}
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

                                                                @if(isset($docs['Latest Photograph']))  {{-- Second Director --}}
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif


                                                            </span>

                                                            {{-- Input is disabled if file exists; triggers filename update via JS --}}
                                                            <input type="file" name="second_dirphoto_doc" id="second_dirphoto_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Latest Photograph']) ? 'disabled' : '' }}
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
                                                                @if(isset($docs['Powers of Attorney']))  {{-- Second Director --}}
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif
                                                            </span>

                                                            {{-- Input is disabled if file exists; triggers JS update on selection --}}
                                                            <input type="file" name="signature_doc" id="signature_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Powers of Attorney']) ? 'disabled' : '' }}
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
                                                                @if(isset($docs['Other']))  {{-- Second Director --}}
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif
                                                            </span>

                                                            {{-- Input is disabled if file already exists; triggers name preview via JS --}}
                                                            <input type="file" name="stamp_doc" id="stamp_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Other']) ? 'disabled' : '' }} hidden
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
                                                                
                                                                @if(isset($docs['Other']))  {{-- Second Director --}}
                                                                    <i class="fa fa-check-circle text-success"></i> Already Uploaded
                                                                @else
                                                                    Click to Upload or Drag & Drop
                                                                @endif
                                                            </span>

                                                            {{-- Input is disabled if file exists; triggers JS update on selection --}}
                                                            <input type="file" name="other_logo_doc" id="other_logo_doc"
                                                                class="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                                                {{ isset($docs['Other']) ? 'disabled' : '' }} hidden
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
                           

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
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
					<input type="hidden" name="compId" value="{{ $compDetails->id }}" />
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
	
	<div class="modal custom-modal fade" id="add-bank-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add New Bank</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form id="addBankForm" method="post" name="addBankFrm" enctype="multipart/form-data">
					<input type="hidden" name="id" id="bankId" value="">
					<input type="hidden" name="propId" value="{{ $compDetails->id }}">
					<input type="hidden" name="redirectUrl" value="proprietorship-edit/{{ Crypt::encrypt($compDetails->id) }}">
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
    <script>
	
		$(document).ready(function(){

			$('#comp_type').change(function(){

				if($(this).val() === 'Proprietorship'){
					$('#proprietorshipModal').modal('show');
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

                function toggleGSTFields(selectedValue) {
                    const valueLower = selectedValue.toLowerCase();

                    if (valueLower === "yes") {
                        if (gstNumberField) gstNumberField.style.display = "block";
                        if (gstTransactionField) gstTransactionField.style.display = "block";
                        if (gstTypeDisplay) gstTypeDisplay.textContent = "B2B";
                    } else if (valueLower === "no") {
                        if (gstNumberField) gstNumberField.style.display = "none";
                        if (gstTransactionField) gstTransactionField.style.display = "none";
                        if (gstTypeDisplay) gstTypeDisplay.textContent = "B2C";
                    } else {
                        if (gstNumberField) gstNumberField.style.display = "none";
                        if (gstTransactionField) gstTransactionField.style.display = "none";
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

            //----------- Form Submit Company Profile ----------
            $("form#frmcompdetPro").bind("submit", function(e) {

                e.preventDefault();
                const id = $("#uid").val();
                const gstReg = $("#frmcompdetPro #gst_reg").val();
                const gstNo = $("#gst_no").val().trim();
                const tranType = $("#comp_tran_type").val();
                const compName = $("#frmcompdetPro #comp_name").val();
                const compPan = $("#frmcompdetPro #comp_pan_no").val();
                // const setBasicSalary = $("#frmcompdetPro #basic_percentage").val();

                
                const cin = ($("#frmcompdetPro #cin").val() || $("#frmcompdetPro #llpin").val() || $("#frmcompdetPro #reg_no").val() || '').trim();
                const incDate = $("#frmcompdetPro #inc_date").val();
                const otherComp = ''; //$("#frmcompdetPro #other_comp").val().trim();

                // 🔴 GST Registered required
                if (!gstReg) {
                    showToast("Please select Company GST Registered!", "error");
                    $("#frmcompdetPro #gst_reg").focus();
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
                    $("#frmcompdetPro #comp_name").focus();
                    return false; // stop ajax
                }

                // Company Pan No required 
                if (!compPan) {
                    showToast("Please Type The Company Pan No", "error");
                    $("#frmcompdetPro #comp_pan_no").focus();
                    return false; // stop ajax
                }

                // Set Basic Salary Percentage
                // if (!setBasicSalary) {
                //     showToast("Please Set The Basic salary Percentage", "error");
                //     $("#frmcompdetPro #basic_percentage").focus();
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


                // $("#loader").show();
                var formCompData = {
					id: id,
                    gst_reg: gstReg,
                    gst_no: gstNo,
                    comp_tran_type: tranType,
                    comp_name: compName,
                    comp_type: '',
                    cin: cin,
                    inc_date: incDate,
                    comp_tan: $("#frmcompdetPro #comp_tan").val(),
                    comp_epf: $("#frmcompdetPro #comp_epf").val(),
                    comp_esic: $("#frmcompdetPro #comp_esic").val(),
                    comp_ptax_cert: $("#frmcompdetPro #comp_ptax_cert").val(),
                    comp_ptax: $("#frmcompdetPro #comp_ptax").val(),
                    comp_pan_no: compPan,
                    other_comp_type: otherComp,
                    // basic_percentage: setBasicSalary,

                    // Add Udyam Registration data here
                    udyam_reg_no: $("#frmcompdetPro #udyam_registration_no").val(),
                    trade_license_no: $("#frmcompdetPro #trade_license_no").val(),
                    shop_establishment_no: $("#frmcompdetPro #shop_establishment_no").val(),
                    fema_iec_no: $("#frmcompdetPro #fema_iec_no").val(),
                    state_excise_no: $("#frmcompdetPro #state_excise_no").val(),
                    
                };

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_compdet_proprietorship",
                    type: "POST",
                    data: formCompData,
                    success: function(response) {
                        var messageContainer = $("#frmcompdetPro .message-container");
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


            

            //Start company business details
            $("form#frmbusdetPro").bind("submit", function (e) {
                e.preventDefault(); 
				const id = $("#uid").val();
                var compNature = $('input[name="comp_nature"]:checked').val();
                var exactNature = $("#frmbusdetPro #exact_comp_nature").val().trim();

                //validation only for these 2 fields
                if (!compNature) {
                    showToast("Please select Category Of Business", "error");
                    return false;
                }

                if (exactNature === "") {
                    showToast("Please enter Exact Nature of Business", "error");
                    return false;
                }
                var formCompBusData = {
                    id: id,
                    comp_nature: compNature,
                    exact_comp_nature: exactNature,                   
                    start_date: $("#frmbusdetPro #start_date").val(),
					comp_quo_digits: $("#frmbusdetPro #comp_quo_digits").val(),
					comp_prof_digits: $("#frmbusdetPro #comp_prof_digits").val(),
					comp_inv_digits: $("#frmbusdetPro #comp_inv_digits").val(),
					comp_po_digits: $("#frmbusdetPro #comp_po_digits").val(),
                };
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_businessdet_proprietorship",
                    type: "POST",
                    data: formCompBusData,
                    success: function(response) {
                        var messageContainer = $("#frmbusdetPro .message-container");
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
			$("form#frmcontdetPro").bind("submit", function(e) {
                e.preventDefault();
				const id = $("#uid").val();
                const compPhone = $("#frmcontdetPro #comp_phone").val().trim();
                const compEmail = $("#frmcontdetPro #comp_email").val().trim();
                const whatsappNo = $("#frmcontdetPro #whatsapp_no").val().trim();

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
                    id: id,
                    comp_phone: compPhone,
                    comp_email: compEmail,
                    whatsapp_no: whatsappNo,
                    comp_website: $("#frmcontdetPro #comp_website").val()
                };

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_contactDetails_proprietorship",
                    type: "POST",
                    data: formContactData,
                    success: function(response) {
						$("#loader").hide();
                        var messageContainer = $("#frmcontdetPro .message-container");
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
					url: '/directors/proprietorship/store',
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

				$.ajax({
					url: "/director/proprietorship/delete/" + id,
					type: "DELETE",
					data: {
						_token: "{{ csrf_token() }}"
					},
					success: function(response) {
						if (response.success) {
							$("#row_" + id).remove();  // Remove row immediately
							alert("Deleted Successfully");
						} else {
							alert("Delete Failed");
						}
					},
					error: function() {
						alert("Something went wrong!");
					}
				});
			});





            //-------- Bank Details add --------
            $("form#frmbankdet").on("submit", function(e) {
                e.preventDefault();

                var formCompBank = $("form#frmbankdet").serialize();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: "/update_bankdet_proprietorship",
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

            $("form#frmattadetPro").bind("submit", function() {
                //if (frmattadetPro.form()) {

                if (!document.getElementById('checkbox').checked) {
                    // Prevent the form from submitting
                    event.preventDefault();

                    // Show the toast message
                    showToast("You must agree to Terms and Conditions.", "error");
                    return false;
                }
				const id = $("#uid").val();
                let inc_certificate = $("#frmattadetPro #inc_certificate").prop("files")[0];
                let pan_doc = $("#frmattadetPro #pan_doc").prop("files")[0];
                let gst_doc = $("#frmattadetPro #gst_doc").prop("files")[0];
                let trade_doc = $("#frmattadetPro #trade_doc").prop("files")[0];
                let pf_doc = $("#frmattadetPro #pf_doc").prop("files")[0];
                let ptex_doc = $("#frmattadetPro #ptex_doc").prop("files")[0];
                let first_diraadh_doc = $("#frmattadetPro #first_diraadh_doc").prop(
                    "files"
                )[0];
                let firstpan_doc = $("#frmattadetPro #firstpan_doc").prop("files")[0];
                let first_dirphoto_doc = $("#frmattadetPro #first_dirphoto_doc").prop(
                    "files"
                )[0];
                let second_aadha_doc = $("#frmattadetPro #second_aadha_doc").prop(
                    "files"
                )[0];
                let second_pan_doc = $("#frmattadetPro #second_pan_doc").prop("files")[0];
                let second_dirphoto_doc = $("#frmattadetPro #second_dirphoto_doc").prop(
                    "files"
                )[0];
                let other_logo_doc = $("#frmattadetPro #other_logo_doc").prop("files")[0];
                let signature_doc = $("#frmattadetPro #signature_doc").prop("files")[0];
                let stamp_doc = $("#frmattadetPro #stamp_doc").prop("files")[0];
                let chk_agree = $('input[name="checkbox"]:checked').val();

                let comp_atta_data = new FormData();

                comp_atta_data.append("id", id);
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
                    url: "/update_comp_attachment_proprietorship",
                    type: "POST",
                    data: comp_atta_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var messageContainer = $("#frmattadetPro .message-container");
                        messageContainer.html(""); // Clear previous messages

                        if (response.class == "succ") {
                            $("#gstdocstate").val(response.gstdocstate);
                            // showToast("Profile picture updated successfully!", "success");
                            showToast(response.message, "success");
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Business Details Update: " + response.message, "error");
                        }
                      
                    },

                   
                });

                //}
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
					const id = $("#uid").val();
                    let formData = new FormData();
                    formData.append('fileUpload', file);
                    formData.append('id', id);

                    fetch("{{ route('upload.profile.image.proprietorship') }}", {
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

           

            function toggleGSTFields() {
                const isGstYes = gstRegSelect.value === "Yes";

                document.getElementById("gst_reg_no").style.display = isGstYes ? "block" : "none";
                document.getElementById("gst_reg_tran").style.display = isGstYes ? "block" : "none";

                // Handle all dynamically added GST fields
                document.querySelectorAll(".gst-field").forEach(el => {
                    el.style.display = isGstYes ? "block" : "none";
                });
            }

            function handleValidation() {
                //const validBasic = validateFields();
                const validGST = validateGSTFields();
                //submitBtn.disabled = !(validBasic && validGST);
                submitBtn.disabled = !(validGST);
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
			$("#loader").show();
			$.ajax({
				url: '{{ route('gst.fetch') }}',
				type: 'POST',
				data: {
					gstin: gstin,
					_token: '{{ csrf_token() }}'
				},
				success: function(data) {
					$("#loader").hide();
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
					$("#loader").hide();

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
		
		// Director Details Functions
        function showDirectorDetails(name, designation, email, phone, din, signature) 
		{
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
		
		
		//Start bank add/update/delete
		$(document).on("submit", "form[id^='upadteBankForm']", function(e) {
			e.preventDefault();
			var form = $(this);
			var bankurl = "/update_bank";
			var bankData = form.serialize();

			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
				url: bankurl,
				type: "POST",
				data: bankData,
				success: function(response) {
					if (response.class == "succ") {
						showToast(response.message, "success");
						localStorage.setItem('activeTab', 'bank-details');
						setTimeout(() => {
							window.location.href = response.redirect;
						}, 2000);
					} else {
						showToast("Error: While Bank Update", "error");
					}
				}
			});
		});

		$("form#addBankForm").bind("submit", function(e) {
			e.preventDefault();

			var bankId = $("#bankId").val();
			var bankurl = "/save_bank"; 
			var bankData = $(this).serialize();

			$.ajax({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
				url: bankurl,
				type: "POST",
				data: bankData,
				success: function(response) {
					//console.log(response);
					if (response.class == "succ") {
						localStorage.setItem('activeTab', 'bank-details');
						showToast(response.message, "success");
						setTimeout(() => {
							window.location.href = response.redirect;
						}, 2000);
					} else {
						showToast("Error: While Bank Add", "error");
					}
				},
			});
		});
		

		// Handle the bank delete confirmation
		$(document).on('click', '.delete-btn', function () {
			let id = $(this).data('id');
			$('#confirmDelete').data('id', id);
		});

		$('#confirmDelete').on('click', function() {
			let deleteId = $(this).data('id');
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
						redirectUrl: "proprietorship-edit/{{ Crypt::encrypt($compDetails->id) }}"
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

    </script>




@endsection
