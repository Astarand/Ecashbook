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
						<li class="breadcrumb-item active" aria-current="page">Firm Profile</li>
					</ul>
				</div>
				<div class="col-md-4">
					<div class="page-header-title">
						<h2 class="mb-0">Firm Profile</h2>
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
                                    <div class="position-relative me-3 d-inline-flex">
										<form action="javascript:void(0);" id="frmprofileimageCA" name="frmprofileimageCA">
                                            @csrf
                                            <div class="message-container"></div>
                                            <div class="position-absolute top-50 start-100 translate-middle">
                                                <!-- File Upload Button -->
                                                <button class="btn btn-sm btn-primary btn-icon" id="triggerFileUpload">
                                                    <i class="ti ti-pencil"></i>
                                                </button>
                                                <!--<input type="file" name="comp_logo" id="comp_logo_ca" class="d-none" onchange="handleFileUpload(this)" accept="image/*" />-->
                                                <input type="file" name="comp_logo" id="comp_logo_ca" class="d-none" />
                                                <!--<a class="btn btn-remove compimagedelCA">Remove</a>-->
                                            </div>
										</form>
											@if(isset($compDetails->comp_logo) && $compDetails->comp_logo !="")
											<img id="image-preview" class="wid-150 rounded img-fluid ms-2" src="{{asset('storage/ca_profile/'.$compDetails->comp_logo)}}" width="243" height="168" alt>
											@else
											<img id="image-preview" class="wid-150 rounded img-fluid ms-2" src="{{asset('assets/images/user/ecashbook.png')}}" alt>
											@endif
                                        <!--<img src="{{asset('assets/images/user/avatar-10.jpg')}}" alt="user-image" id="uploadedImage" class="wid-150 rounded img-fluid ms-2" />-->
                                    </div>
                                </div>
                                <h4 class="mt-3">E-Cashbook</h4>
                            </div>
                        </div>
                        <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0" id="company-profile-set-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link list-group-item list-group-item-action active" id="firm-details-tab" data-bs-toggle="pill" href="#firm-details" role="tab" aria-controls="firm-details" aria-selected="true">
                                <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Firm Details</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="speclization-tab" data-bs-toggle="pill" href="#speclization" role="tab" aria-controls="speclization" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-database m-r-10"></i>Services Offered</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="bank-details-tab" data-bs-toggle="pill" href="#bank-details" role="tab" aria-controls="bank-details" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-wallet m-r-10"></i>Bank Details</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="partner-details-tab" data-bs-toggle="pill" href="#partner-details" role="tab" aria-controls="partner" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-user-plus m-r-10"></i>Partner Details</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="attachment-tab" data-bs-toggle="pill" href="#attachment" role="tab" aria-controls="attachment" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-arrow-square-up m-r-10"></i>Attachments</span>
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
                <div class="col-lg-7 col-xxl-9">
                    <div class="tab-content" id="firm-profile-set-tabContent">
                        <div class="tab-pane fade show active" id="firm-details" role="tabpanel" aria-labelledby="firm-details-tab">
                            <form action="javascript:void(0);" name="CAfrmcompdet" id="CAfrmcompdet">
                                @csrf
                                <div class="card">
                                    <div class="message-container"></div>
                                    <div class="card-header">
                                        <h5>Company Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 mb-3">
                                                <label class="form-label">CA /CA Firm / Tax Consultant Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="comp_name" id="comp_name" value="{{ isset($compDetails->comp_name)?$compDetails->comp_name:""}}" placeholder="Enter Company Name" required>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label class="form-label">Company Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control"  name="comp_email" id="comp_email" value="{{ isset($compDetails->comp_email)?$compDetails->comp_email:""}}" placeholder="Enter Company Email" required>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="comp_phone" id="comp_phone" value="{{ isset($compDetails->comp_phone)?$compDetails->comp_phone:""}}" placeholder="Enter Phone Number" required>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">No of CA in the Firm <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="no_ca_firm" id="no_ca_firm" value="{{ isset($compDetails->no_ca_firm)?$compDetails->no_ca_firm:""}}"  placeholder="Enter Number of CA" required>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">No of Employees <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"  name="no_employee" id="no_employee" value="{{ isset($compDetails->no_employee)?$compDetails->no_employee:""}}" placeholder="Enter Number of Employees" required>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">No of Platform User </label>
                                                <input type="text" class="form-control"  name="total_no_client" id="total_no_client" value="{{ isset($compDetails->total_no_client)?$compDetails->total_no_client:""}}"  placeholder="Enter No of Platform User">
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">Type of Firm<span class="text-danger">*</span></label>
                                                <select class="form-select" name="type_of_firm" id="type_of_firm" required>
                                                    <option value="">Select Type</option>
                                                    <option value="CA Firm" {{ (isset($compDetails->type_of_firm) && $compDetails->type_of_firm == "CA Firm") ? 'selected' : '' }}>CA Firm</option>
                                                    <option value="Accounting Firm" {{ (isset($compDetails->type_of_firm) && $compDetails->type_of_firm == "Accounting Firm") ? 'selected' : '' }}>Accounting Firm</option>
                                                    <option value="Consultancy" {{ (isset($compDetails->type_of_firm) && $compDetails->type_of_firm == "Consultancy") ? 'selected' : '' }}>Consultancy</option>
                                                    <option value="Tax Firm" {{ (isset($compDetails->type_of_firm) && $compDetails->type_of_firm == "Tax Firm") ? 'selected' : '' }}>Tax Firm</option>
                                                </select>
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">Constitution Type <span class="text-danger">*</span></label>
                                                <select class="form-select" name="constitution_type" id="constitution_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="Proprietorship" {{ (isset($compDetails->constitution_type) && $compDetails->constitution_type == "Proprietorship") ? 'selected' : '' }}>Proprietorship</option>
                                                    <option value="Partnership" {{ (isset($compDetails->constitution_type) && $compDetails->constitution_type == "Partnership") ? 'selected' : '' }}>Partnership</option>
                                                    <option value="LLP" {{ (isset($compDetails->constitution_type) && $compDetails->constitution_type == "LLP") ? 'selected' : '' }}>LLP</option>
                                                    <option value="Pvt Ltd" {{ (isset($compDetails->constitution_type) && $compDetails->constitution_type == "Pvt Ltd") ? 'selected' : '' }}>Pvt Ltd</option>
                                                </select>
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">Year of Firm Experience <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="year_of_experience" id="year_of_experience"
                                                    value="{{ isset($compDetails->year_of_experience) ? $compDetails->year_of_experience : '' }}"
                                                    placeholder="Enter Years" required>
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">Software Licenses Used <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="software_licenses"  id="software_licenses"
                                                    value="{{ isset($compDetails->software_licenses) ? $compDetails->software_licenses : '' }}"
                                                    placeholder="Example: Tally, Busy, Zoho Books" required>
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">TAN Reg. No</label>
                                                <input type="text" class="form-control" name="tan_no" id="tan_no"
                                                    value="{{ isset($compDetails->tan_no) ? $compDetails->tan_no : '' }}"
                                                    placeholder="Enter TAN Number">
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">Profession Tax Reg. No</label>
                                                <input type="text" class="form-control" name="pt_reg_no" id="pt_reg_no"
                                                    value="{{ isset($compDetails->pt_reg_no) ? $compDetails->pt_reg_no : '' }}"
                                                    placeholder="Enter PT Registration No">
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">EPF Reg. No</label>
                                                <input type="text" class="form-control" name="epf_reg_no" id="epf_reg_no"
                                                    value="{{ isset($compDetails->epf_reg_no) ? $compDetails->epf_reg_no : '' }}"
                                                    placeholder="Enter EPF Registration No">
                                            </div>

                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">ESIC Reg. No</label>
                                                <input type="text" class="form-control" name="esic_reg_no" id="esic_reg_no"
                                                    value="{{ isset($compDetails->esic_reg_no) ? $compDetails->esic_reg_no : '' }}"
                                                    placeholder="Enter ESIC Registration No">
                                            </div>
                                            {{-- <div class="col-sm-3 mb-3">
                                                <label class="form-label">Set Basic Salary Percentage <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="basic_percentage" value="{{ isset($compDetails->basic_percentage) ? $compDetails->basic_percentage : 40 }}" id="basic_percentage" placeholder="Enter percentage">
                                                
                                            </div> --}}
                                            <div class="col-sm-3 mb-3">
                                                <label class="form-label">GST No </label>
                                                <input type="text" class="form-control" name="comp_gst_no" id="comp_gst_no" value="{{ isset($compDetails->comp_gst_no)?$compDetails->comp_gst_no:""}}" placeholder="Enter GST Number">
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                                <label class="form-label">About <span class="text-danger">*</span></label>
                                                <textarea name="about_firm" id="about_firm"  class="form-control" rows="5" required>{{ isset($compDetails->about_firm)?$compDetails->about_firm:""}}</textarea>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="comp_bill_addone" id="comp_bill_addone" required value="{{ isset($compDetails->comp_bill_addone)?$compDetails->comp_bill_addone:""}}" placeholder="Enter Address Line 1">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Address Line 2<span class="text-danger"></span></label>
                                                <input type="text" class="form-control" name="comp_bill_addtwo" id="comp_bill_addtwo" value="{{ isset($compDetails->comp_bill_addtwo)?$compDetails->comp_bill_addtwo:""}}" placeholder="Enter Address Line 2">
                                            </div>
											<div class="col-sm-4 mb-3">
                                                <label class="form-label">State<span class="text-danger">*</span></label>
												<select class="form-control select-style" name="comp_bill_state" id="state" onChange="changeState(this);" required>
													<option value="">Select State</option>
													@foreach($states_bill as $k=>$state)
														<option value="{{ $state->id }}" <?php echo ($state->id==@$compDetails->comp_bill_state)? "selected":"" ?>>{{ $state->name }}</option>
													@endforeach
												</select>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label class="form-label">City<span class="text-danger">*</span></label>
                                                <select class="form-control select-style" name="comp_bill_city" id="city" required>
													<option value="">Select City</option>
													@foreach($cities_bill as $k=>$city)
														<option value="{{ $city->id }}" <?php echo ($city->id==@$compDetails->comp_bill_city)? 'selected="selected"':"" ?>>{{ $city->name }}</option>
													@endforeach
												</select>
                                            </div>
                                            
                                            <div class="col-sm-4 mb-3">
                                                <label class="form-label">Pin Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="comp_bill_pin" id="comp_bill_pin" required value="{{ isset($compDetails->comp_bill_pin)?$compDetails->comp_bill_pin:""}}" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
										<button type="submit" id="save_compDetBtn" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">Save Changes <i class="ti ti-arrow-right-circle ms-2"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="speclization" role="tabpanel" aria-labelledby="speclization">
							<form action="javascript:void(0);" name="frmCa_spec" id="frmCa_spec" method="post">
                                @csrf
                                <div class="message-container"></div>
                                <?php 
                                    $specDetails = isset($compDetails->ca_spec)?$compDetails->ca_spec:"";
                                    $specDetails = explode(',', $specDetails);
                                    $other_text = $compDetails->other_service_text ?? '';
                                ?>
                                <div class="row">
                                    <div class="col-md-12 col-xl-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>You Have Services Offered <span class="text-danger">*</span></h5>
                                            </div>
                                            <div class="card-body">
                                                <!-- Business Registration -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Business Registration (LLP, OPC, Pvt Ltd)</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Business Registration"
                                                            <?php if (in_array('Business Registration', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Accounting & Bookkeeping -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Accounting & Bookkeeping</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Accounting & Bookkeeping"
                                                            <?php if (in_array('Accounting & Bookkeeping', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- MSME / ISO / Trade License consulting -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">MSME / ISO / Trade License consulting</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="MSME / ISO / Trade License consulting"
                                                            <?php if (in_array('MSME / ISO / Trade License consulting', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- GST Registration & Filing -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">GST Registration & Filing</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="GST Registration & Filing"
                                                            <?php if (in_array('GST Registration & Filing', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- TDS & Income Tax Filing -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">TDS & Income Tax Filing</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="TDS & Income Tax Filing"
                                                            <?php if (in_array('TDS & Income Tax Filing', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- MCA/ROC Compliance -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">MCA/ROC Compliance</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="MCA/ROC Compliance"
                                                            <?php if (in_array('MCA/ROC Compliance', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Payroll & HR Compliance -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Payroll & HR Compliance</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Payroll & HR Compliance"
                                                            <?php if (in_array('Payroll & HR Compliance', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Audit -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Audit (Statutory / Internal / Tax)</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Audit"
                                                            <?php if (in_array('Audit', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Business Licensing & Certifications -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Business Licensing & Certifications</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Business Licensing & Certifications"
                                                            <?php if (in_array('Business Licensing & Certifications', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Virtual CFO Services -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Virtual CFO Services</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Virtual CFO Services"
                                                            <?php if (in_array('Virtual CFO Services', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Project Report -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Project report creation</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Project report creation"
                                                            <?php if (in_array('Project report creation', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Accounting Outsourcing -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Accounting Outsourcing</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Accounting Outsourcing"
                                                            <?php if (in_array('Accounting Outsourcing', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Financial Planning & Advisory -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Financial Planning & Advisory</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Financial Planning & Advisory"
                                                            <?php if (in_array('Financial Planning & Advisory', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Mergers & Acquisitions -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Mergers & Acquisitions</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Mergers & Acquisitions"
                                                            <?php if (in_array('Mergers & Acquisitions', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- IP Advisory -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">IP Advisory (Trademark, Patent, Copyright)</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="IP Advisory"
                                                            <?php if (in_array('IP Advisory', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Insolvency & Bankruptcy -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Insolvency & Bankruptcy Advisory</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Insolvency & Bankruptcy Advisory"
                                                            <?php if (in_array('Insolvency & Bankruptcy Advisory', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Valuation Services -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Valuation Services (Business, Assets, Brands)</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Valuation Services"
                                                            <?php if (in_array('Valuation Services', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Corporate Law -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Corporate Law & Secretarial Compliances</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Corporate Law & Secretarial Compliances"
                                                            <?php if (in_array('Corporate Law & Secretarial Compliances', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- DPDP Act Compliance -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Compliance on DPDP Act, 2023</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Compliance on DPDP Act, 2023"
                                                            <?php if (in_array('Compliance on DPDP Act, 2023', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Consulting & Advisory -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Consulting & Advisory Services</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input type="checkbox" class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Consulting & Advisory Services"
                                                            <?php if (in_array('Consulting & Advisory Services', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Other Specialized Services -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="text-muted mb-0">Other specialized services</p>
                                                    <div class="form-check form-switch p-0">
                                                        <input id="other_service_chk"
                                                            type="checkbox"
                                                            class="m-0 form-check-input h5 position-relative"
                                                            name="ca_spec[]"
                                                            value="Other specialized services"
                                                            <?php if (in_array('Other specialized services', $specDetails)) echo 'checked'; ?>>
                                                    </div>
                                                </div>

                                                <!-- Input box for "other" -->
                                                <div id="other_service_box_field" style="display: <?php echo in_array('Other specialized services', $specDetails) ? 'block' : 'none'; ?>;">
                                                    <input type="text" class="form-control" name="other_service_text" id="other_service_text" value="<?php echo isset($compDetails->other_service_text) ? $other_text : ''; ?>">
                                                    
                                                </div>

                                            </div>

                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    
                                    <div class="last">
                                        <button type="submit" id="save_busDetBtn" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">Save Changes <i class="ti ti-arrow-right-circle ms-2"></i></button>
                                    </div>
                                </div>
							</form>
                        </div>
                        <div class="tab-pane fade" id="bank-details" role="tabpanel" aria-labelledby="bank-details-tab">
                            <form action="javascript:void(0);" name="CAfrmbankdet" id="CAfrmbankdet" method="post">
                                @csrf
                                <div class="message-container"></div>
								<?php  
								if(!empty($bankDetails)) { 
								$i = 1;	
										foreach($bankDetails as $bankData ) {
								?>
								<div class="containerVariant">
									<div class="row">
										<div class="col-lg-12 col-sm-12">
											<div class="card bank-account">
												<div class="card-header d-flex justify-content-between align-items-center">
													<h5>Bank Account <?php echo $i; ?></h5>
													<!--<span class="btn btn-primary" onclick="addBankAccount()">Add New Bank Account</span>-->
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-sm-4 mb-3">
															<label class="form-label">Bank Name <span class="text-danger">*</span></label>
															<input type="text" name="bank_name[]" id="" value="{{ $bankData->bank_name }}" class="form-control" placeholder="Bank Name">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Branch <span class="text-danger">*</span></label>
															<input type="text" name="bank_branch[]" id="" value="{{ $bankData->bank_branch }}" class="form-control" placeholder="Enter Branch">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
															<input type="text" name="bank_holder_name[]" id="" value="{{ $bankData->bank_holder_name }}"  class="form-control" placeholder="Enter Name">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Account Number <span class="text-danger">*</span></label>
															<input type="text" name="ac_no[]" id="" value="{{ $bankData->ac_no }}" class="form-control" placeholder="Enter Account Number">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">IFSC Code <span class="text-danger">*</span></label>
															<input type="text" name="ifsc_code[]" id="" value="{{ $bankData->ifsc_code }}" class="form-control" placeholder="Enter IFSC Code ">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">VPA / UPI ID</label>
															<input type="text" name="ac_upid[]" id="" value="{{ $bankData->ac_upid }}" class="form-control" placeholder="Enter VPA / UPI ID">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php $i++;} }?>
								<div class="containerVariant">
									<div class="row">
										<div class="col-lg-12 col-sm-12">
											<div class="card bank-account">
												<div class="card-header d-flex justify-content-between align-items-center">
													<h5>Bank Account 1</h5>
													<!--<span class="btn btn-primary" onclick="addBankAccount()">Add New Bank Account</span>-->
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-sm-4 mb-3">
															<label class="form-label">Bank Name <span class="text-danger">*</span></label>
															<input type="text" name="bank_name[]" id=""  class="form-control" placeholder="Bank Name">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Branch <span class="text-danger">*</span></label>
															<input type="text" name="bank_branch[]" id=""  class="form-control" placeholder="Enter Branch">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
															<input type="text" name="bank_holder_name[]" id=""   class="form-control" placeholder="Enter Name">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Account Number <span class="text-danger">*</span></label>
															<input type="text" name="ac_no[]" id=""  class="form-control" placeholder="Enter Account Number">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">IFSC Code <span class="text-danger">*</span></label>
															<input type="text" name="ifsc_code[]" id="" class="form-control" placeholder="Enter IFSC Code ">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">VPA / UPI ID</label>
															<input type="text" name="ac_upid[]" id=""  class="form-control" placeholder="Enter VPA / UPI ID">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<span class="btn btn-primary" onclick="addBankAccount()">Add New Bank Account</span>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    
                                    <div class="last">
										<button type="submit" id="save_bankDetBtn" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
										Save Changes <i class="ti ti-arrow-right-circle ms-2"></i>
										</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="partner-details" role="tabpanel" aria-labelledby="partner-details-tab">
                            <form action="javascript:void(0);" name="frmPartnerdet" id="frmPartnerdet" method="post">
                                @csrf
                                <div class="message-container"></div>
								<?php  
								if(!empty($partnerDetails)) { 
								$j = 1;	
										foreach($partnerDetails as $partnerData ) {
								?>
								<div class="form-group-customer customer-additional-form">
									<div class="row">
										<div class="col-lg-12 col-sm-12">
											<div class="card bank-account">
												<div class="card-header d-flex justify-content-between align-items-center">
													<h5>Partner <?php echo $j;?> Details</h5>
													<!--<span class="btn btn-primary" onclick="addPartner()">Add New Partner</span>-->
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-sm-4 mb-3">
															<label class="form-label">Partner Name <span class="text-danger">*</span></label>
															<input type="text" name="partner_name[]" value="{{ $partnerData->partner_name }}" class="form-control" placeholder="Partner Name">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Contact Number <span class="text-danger">*</span></label>
															<input type="text" name="partner_no[]" value="{{ $partnerData->partner_no }}" class="form-control" placeholder="Enter Contact Number">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Email Address <span class="text-danger">*</span></label>
															<input type="text" name="partner_email[]" value="{{ $partnerData->partner_email }}" class="form-control" placeholder="Enter Email Address">
														</div>
														<div class="col-sm-6 mb-3">
															<label class="form-label">Tenure Of Practicing <span class="text-danger">*</span></label>
															<input type="text" name="practicing[]" value="{{ $partnerData->practicing }}" class="form-control" placeholder="Enter Tenure Of Practicing">
														</div>
														<div class="col-sm-6 mb-3">
															<label class="form-label">Role / Designation<span class="text-danger">*</span></label>
															<input type="text" name="partner_role[]" value="{{ $partnerData->partner_role }}" class="form-control" placeholder="Enter IFSC Code ">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php $j++;} }?>
								<div class="form-group-customer customer-additional-form">
									<div class="row">
										<div class="col-lg-12 col-sm-12">
											<div class="card bank-account">
												<div class="card-header d-flex justify-content-between align-items-center">
													<h5>Partner 1 Details</h5>
													<!--<span class="btn btn-primary" onclick="addPartner()">Add New Partner</span>-->
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-sm-4 mb-3">
															<label class="form-label">Partner Name <span class="text-danger">*</span></label>
															<input type="text" name="partner_name[]" class="form-control" placeholder="Partner Name">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Contact Number <span class="text-danger">*</span></label>
															<input type="text" name="partner_no[]" id="" class="form-control" placeholder="Enter Contact Number">
														</div>
														<div class="col-sm-4 mb-3">
															<label class="form-label">Email Address <span class="text-danger">*</span></label>
															<input type="text" name="partner_email[]"  class="form-control" placeholder="Enter Email Address">
														</div>
														<div class="col-sm-6 mb-3">
															<label class="form-label">Tenure Of Practicing <span class="text-danger">*</span></label>
															<input type="text" name="practicing[]" class="form-control" placeholder="Enter Tenure Of Practicing">
														</div>
														<div class="col-sm-6 mb-3">
															<label class="form-label">Role / Designation<span class="text-danger">*</span></label>
															<input type="text" name="partner_role[]" class="form-control" placeholder="Enter IFSC Code ">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<span class="btn btn-primary" onclick="addPartner()">Add New Partner</span>
								<div class="d-flex wizard justify-content-between mt-3">
									
									<div class="last">
										<button type="submit" id="save_bankDetBtn" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">Save Changes <i class="ti ti-arrow-right-circle ms-2"></i></button>
									</div>
								</div>
							</form>
                        </div>
                        <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                            <div class="row">
                                <h5>Statutory Details</h5>

                                <form action="javascript:void(0);" name="CAfrmattadet" id="CAfrmattadet" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="gstdocstate" value="{{isset($compDetails->gst_doc)?$compDetails->gst_doc:""}}">
                                    @csrf
                                    <div class="message-container"></div>
                                
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Company GST Certificate</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="gst_doc" id="gst_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->gst_doc !="")
                                                                <a target="_blank" href="{{ asset('storage/ca_company_files/'.$compDetails->gst_doc) }}"
                                                                    download class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Proprietor/ Company Pan Card</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="pan_doc" id="pan_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->pan_doc !="")
                                                                <a target="_blank" href="{{ asset('storage/ca_company_files/'.$compDetails->pan_doc) }}"
                                                                    download class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload TAN Document</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="tan_doc" id="tan_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->tan_doc !="")
                                                                <a target="_blank" href="{{ asset('storage/ca_company_files/'.$compDetails->tan_doc) }}"
                                                                    download class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload CIN Document</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="cin_doc" id="cin_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->cin_doc !="")
                                                                <a target="_blank" href="{{ asset('storage/ca_company_files/'.$compDetails->cin_doc) }}"
                                                                    download class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <h3 class="my-3">Other Documents</h3>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Logo</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="other_logo_doc" id="other_logo_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->other_logo_doc !="")
                                                                <a target="_blank"
                                                                    href="{{ asset('storage/ca_company_files/'.$compDetails->other_logo_doc) }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Upload Signature</h5>
                                                </div>
                                                <div class="card-body text-center">
                                                    <label class="upload-area">
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="signature_doc" id="signature_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->signature_doc !="")
                                                                <a target="_blank"
                                                                    href="{{ asset('storage/ca_company_files/'.$compDetails->signature_doc) }}" download
                                                                    class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
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
                                                        <span class="">Click to Upload or Drag & Drop</span>
                                                        <input type="file" name="stamp_doc" id="stamp_doc" class="fileInput"
                                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                                        <div class="file-preview-container">
                                                            <div class="file-preview">
                                                                <div class="file-info">
                                                                    <div class="file-name"></div>
                                                                    <div class="file-size">Uploaded File</div>
                                                                </div>
                                                                {{-- @if (in_array($fileExtension, ['jpg', 'jpeg', 'png']))
                                                                <img src="" alt="Preview" class="preview-image" style="max-width: 100px;">
                                                                @endif --}}
                                                                @if(@$compDetails->stamp_doc !="")
                                                                <a target="_blank" href="{{ asset('storage/ca_company_files/'.$compDetails->stamp_doc) }}"
                                                                    download class="btn btn-success btn-sm">Download</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-1 justify-content-between">
                                        <div class="form-check">
                                            {{-- <input class="form-check-input input-primary" type="checkbox" id="checkboxAccept" name="checkboxAccept" {{ $compDetails->chk_agree == 1 ? 'checked' : '' }}>
                                            --}}
                                            <input class="form-check-input input-primary" type="checkbox" id="checkboxAccept" name="checkboxAccept"
                                                {{ is_object($compDetails) && $compDetails->chk_agree == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted" for="customCheckc1">
                                                I agree to all the <a href="javascript:void(0);" id="termsLink">Terms & Conditions</a>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-center" id="termsModalLabel">Terms and Conditions</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-center">The user agrees to use our accounting software service, providing accurate
                                                        information, maintaining login confidentiality, and using it for lawful purposes only. The
                                                        company reserves the right to suspend or terminate accounts for any violation. The software is
                                                        provided 'as is' and we disclaim warranties regarding its performance and suitability for
                                                        specific needs.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="d-flex wizard justify-content-between mt-3">
                                
                                        <div class="last">
                                            <button type="submit" id="save_attaBtn"
                                                class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                                Save Changes <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="holidays" role="tabpanel" aria-labelledby="holidays-tab">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4><i class="ph-duotone ph-calendar me-2"></i>Company Holidays</h4>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#holidayModal">
                                        <i class="ti ti-plus"></i> Add Holiday
                                    </button>
                                </div>


                                <!-- Holiday Statistics -->
                                <div class="row mt-4 g-4 px-3">
                                    <!-- Total Holidays Card -->
                                    <div class="col-md-3">
                                        <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                            <div class="position-absolute top-0 end-0 opacity-25">
                                                <i class="fas fa-calendar-alt" style="font-size: 8rem; color: #667eea; transform: rotate(-15deg);"></i>
                                            </div>
                                            <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                <h1 class="display-3 fw-bold text-primary mb-2">
                                                    {{ $holidays->filter(fn($h) => \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                </h1>
                                                <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Total Holidays</h6>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">{{ date('Y') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- National Holidays Card -->
                                    <div class="col-md-3">
                                        <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                            <div class="position-absolute top-0 end-0 opacity-25">
                                                <i class="fas fa-flag" style="font-size: 8rem; color: #dc3545; transform: rotate(-15deg);"></i>
                                            </div>
                                            <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                <h1 class="display-3 fw-bold text-danger mb-2">
                                                    {{ $holidays->filter(fn($h) => $h->holidayType === 'National' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                </h1>
                                                <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">National Holidays</h6>
                                                <span class="badge bg-danger bg-opacity-10 text-danger">Government</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Festival Holidays Card -->
                                    <div class="col-md-3">
                                        <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                            <div class="position-absolute top-0 end-0 opacity-25">
                                                <i class="fas fa-gifts" style="font-size: 8rem; color: #ffc107; transform: rotate(-15deg);"></i>
                                            </div>
                                            <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                <h1 class="display-3 fw-bold text-warning mb-2">
                                                    {{ $holidays->filter(fn($h) => $h->holidayType === 'Festival' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                </h1>
                                                <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Festival Holidays</h6>
                                                <span class="badge bg-warning bg-opacity-10 text-warning">Cultural</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Company Holidays Card -->
                                    <div class="col-md-3">
                                        <div class="card border-0 shadow-sm position-relative overflow-hidden">
                                            <div class="position-absolute top-0 end-0 opacity-25">
                                                <i class="fas fa-building" style="font-size: 8rem; color: #0dcaf0; transform: rotate(-15deg);"></i>
                                            </div>
                                            <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                <h1 class="display-3 fw-bold text-info mb-2">
                                                    {{ $holidays->filter(fn($h) => $h->holidayType === 'Company' && \Carbon\Carbon::parse($h->holidayDate)->year == date('Y'))->count() }}
                                                </h1>
                                                <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Company Holidays</h6>
                                                <span class="badge bg-info bg-opacity-10 text-info">Organization</span>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $mondayClose }}" name="monday_close" {{
                                                                    !$mondayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $mondayLunchStart }}"
                                                                    name="monday_lunch_start" {{ !$mondayStatus
                                                                    ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $mondayLunchStop }}"
                                                                    name="monday_lunch_stop" {{ !$mondayStatus
                                                                    ? 'disabled' : '' }}>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $tuesdayClose }}" name="tuesday_close" {{
                                                                    !$tuesdayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $tuesdayLunchStart }}"
                                                                    name="tuesday_lunch_start" {{ !$tuesdayStatus
                                                                    ? 'disabled' : '' }}>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $tuesdayLunchStop }}"
                                                                    name="tuesday_lunch_stop" {{ !$tuesdayStatus
                                                                    ? 'disabled' : '' }}>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $wednesdayClose }}" name="wednesday_close"
                                                                    {{ !$wednesdayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $wednesdayLunchStart }}"
                                                                    name="wednesday_lunch_start" {{ !$wednesdayStatus
                                                                    ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $wednesdayLunchStop }}"
                                                                    name="wednesday_lunch_stop" {{ !$wednesdayStatus
                                                                    ? 'disabled' : '' }}>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $thursdayClose }}" name="thursday_close"
                                                                    {{ !$thursdayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $thursdayLunchStart }}"
                                                                    name="thursday_lunch_start" {{ !$thursdayStatus
                                                                    ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $thursdayLunchStop }}"
                                                                    name="thursday_lunch_stop" {{ !$thursdayStatus
                                                                    ? 'disabled' : '' }}>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $fridayClose }}" name="friday_close" {{
                                                                    !$fridayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $fridayLunchStart }}"
                                                                    name="friday_lunch_start" {{ !$fridayStatus
                                                                    ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $fridayLunchStop }}"
                                                                    name="friday_lunch_stop" {{ !$fridayStatus
                                                                    ? 'disabled' : '' }}>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $saturdayClose }}" name="saturday_close"
                                                                    {{ !$saturdayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $saturdayLunchStart }}"
                                                                    name="saturday_lunch_start" {{ !$saturdayStatus
                                                                    ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $saturdayLunchStop }}"
                                                                    name="saturday_lunch_stop" {{ !$saturdayStatus
                                                                    ? 'disabled' : '' }}>
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
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control closing-time"
                                                                    value="{{ $sundayClose }}" name="sunday_close" {{
                                                                    !$sundayStatus ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-start-time"
                                                                    value="{{ $sundayLunchStart }}"
                                                                    name="sunday_lunch_start" {{ !$sundayStatus
                                                                    ? 'disabled' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">
                                                            <div class="position-relative">
                                                                <input type="time" class="form-control lunch-end-time"
                                                                    value="{{ $sundayLunchStop }}"
                                                                    name="sunday_lunch_stop" {{ !$sundayStatus
                                                                    ? 'disabled' : '' }}>
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
                                            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                        style="width: 70px; height: 70px;">
                                                        <i class="ti ti-map-pin fa-2x text-primary"></i>
                                                    </div>
                                                    <h1 class="display-3 fw-bold text-primary mb-2">{{ $locations->count() }}</h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Total Locations</h6>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">All Offices</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Active Locations -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                        style="width: 70px; height: 70px;">
                                                        <i class="ti ti-circle-check fa-2x text-success"></i>
                                                    </div>
                                                    <h1 class="display-3 fw-bold text-success mb-2">{{ $locations->where('status', 'Active')->count() }}</h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Active Locations</h6>
                                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2">Operational</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Head Office -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                        style="width: 70px; height: 70px;">
                                                        <i class="ti ti-building fa-2x text-warning"></i>
                                                    </div>
                                                    <h1 class="display-3 fw-bold text-warning mb-2">{{ $locations->where('location_type', 'Head Office')->count() }}</h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Head Office</h6>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2">Headquarters</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Branch Offices -->
                                        <div class="col-md-3">
                                            <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden">
                                                <div class="card-body text-center p-4 position-relative" style="z-index: 1;">
                                                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                        style="width: 70px; height: 70px;">
                                                        <i class="ti ti-building-community fa-2x text-info"></i>
                                                    </div>
                                                    <h1 class="display-3 fw-bold text-info mb-2">{{ $locations->where('location_type', 'Branch Office')->count() }}</h1>
                                                    <h6 class="text-uppercase fw-bold text-muted mb-1" style="letter-spacing: 1.5px;">Branch Offices</h6>
                                                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2">Regional</span>
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

<script>
    
    //------ Validation add -----------
    document.addEventListener("DOMContentLoaded", function () {
        const submitBtn = document.getElementById('save_compDetBtn');

        const fieldIds = [
            'comp_name',
            'comp_email',
            'comp_phone',
            'no_ca_firm',
            'no_employee',
            // 'total_no_client',
            // 'comp_gst_no',
            'about_firm',
            'comp_bill_addone',
            'state',
            'city',
            'comp_bill_pin',
            'type_of_firm',
            'constitution_type',
            'year_of_experience',
            'software_licenses',
            // 'tan_no',
            // 'pt_reg_no',
            // 'epf_reg_no',
            // 'esic_reg_no'
        ];

        function isValidGST(gstNo) {
            const gstPattern = /^\d{2}[A-Z]{5}\d{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
            return gstPattern.test(gstNo.toUpperCase());
        }

        function validateForm() {
            let allValid = true;

            // Check if all fields are filled
            fieldIds.forEach(id => {
                const field = document.getElementById(id);
                if (!field || !field.value.trim()) {
                    allValid = false;
                }
            });

            // GST Validation
            const gstField = document.getElementById('comp_gst_no');
            const gstValue = gstField.value.trim();
            let gstError = document.getElementById('gstError');

            if (!gstError) {
                gstError = document.createElement('div');
                gstError.id = 'gstError';
                gstError.style.color = 'red';
                gstField.parentNode.appendChild(gstError);
            }

            if (gstValue && !isValidGST(gstValue)) {
                gstError.textContent = 'Please enter a valid GST number.';
                allValid = false;
            } else {
                gstError.textContent = '';
            }

            // Enable or disable submit button
            submitBtn.disabled = !allValid;
        }

        // Attach input and change listeners
        fieldIds.forEach(id => {
            const field = document.getElementById(id);
            if (field) {
                field.addEventListener('input', validateForm);
                field.addEventListener('change', validateForm);
            }
        });

        // Initial check
        validateForm();
    });





    //Bank Account Add/Remove
    let bankAccountCount = 1;
    const maxBankAccounts = 3;
    function addBankAccount() {
        if (bankAccountCount >= maxBankAccounts) {
            alert("You can only add up to 3 bank accounts.");
            return;
        }
        
        bankAccountCount++;
        const bankAccountContainer = document.querySelector("#bank-details .row");
        const newAccount = document.createElement("div");
        newAccount.classList.add("col-lg-12", "col-sm-12", "bank-account");
        newAccount.innerHTML = `
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Bank Account ${bankAccountCount}</h5>
                    <span class="btn btn-danger" onclick="removeBankAccount(this)"><i class="ti ti-trash"></i></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name[]" class="form-control" placeholder="Bank Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                            <input type="text" name="bank_branch[]" class="form-control" placeholder="Enter Branch">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                            <input type="text" name="bank_holder_name[]" class="form-control" placeholder="Enter Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Account Number <span class="text-danger">*</span></label>
                            <input type="text" name="ac_no[]" class="form-control" placeholder="Enter Account Number">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                            <input type="text" name="ifsc_code[]" class="form-control" placeholder="Enter IFSC Code">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">VPA / UPI ID</label>
                            <input type="text" name="ac_upid[]" class="form-control" placeholder="Enter VPA / UPI ID">
                        </div>
                    </div>
                </div>
            </div>
        `;
        bankAccountContainer.appendChild(newAccount);
    }
    function removeBankAccount(element) {
        const accountToRemove = element.closest(".col-lg-12.bank-account");
        if (accountToRemove) {
            accountToRemove.remove();
            bankAccountCount--;
        }
    }

    //Partner Add/Remove
    let partnerCount = 1;
    function addPartner() {
        partnerCount++;
        const partnerContainer = document.querySelector("#partner-details .row");
        const newPartner = document.createElement("div");
        newPartner.classList.add("col-lg-12", "col-sm-12", "partner-account");
        newPartner.innerHTML = `
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Partner ${partnerCount} Details</h5>
                    <span class="btn btn-danger" onclick="removePartner(this)"><i class="ti ti-trash"></i> Delete</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Partner Name <span class="text-danger">*</span></label>
                            <input type="text" name="partner_name[]" class="form-control" placeholder="Partner Name">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" name="partner_no[]" class="form-control" placeholder="Enter Contact Number">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="text" name="partner_email[]" class="form-control" placeholder="Enter Email Address">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Tenure Of Practicing <span class="text-danger">*</span></label>
                            <input type="text" name="practicing[]" class="form-control" placeholder="Enter Tenure Of Practicing">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Role / Designation <span class="text-danger">*</span></label>
                            <input type="text" name="partner_role[]" class="form-control" placeholder="Enter Role / Designation">
                        </div>
                    </div>
                </div>
            </div>
        `;
        partnerContainer.appendChild(newPartner);
    }
    function removePartner(element) {
        const partnerToRemove = element.closest(".col-lg-12.partner-account");
        if (partnerToRemove) {
            partnerToRemove.remove();
        }
    }

    //Terms & Conditions Modal
    document.getElementById("termsLink").addEventListener("click", function() {
        const termsModal = new bootstrap.Modal(document.getElementById("termsModal"));
        termsModal.show();
    });

    //-- Other field Textbox --//
    $('#other_service_chk').on('change', function () {
        if ($(this).is(':checked')) {
            $('#other_service_box_field').show();
        } else {
            $('#other_service_box_field').hide();
        }
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

    // Reset Holiday Form to Add Mode
    function resetHolidayForm() {
        document.getElementById('holidayId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('holidayModalLabel').textContent = 'Add Holiday';
        document.getElementById('holidaySubmitBtn').textContent = 'Save';
        document.getElementById('holidayForm').reset();
    }

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
        // document.querySelector('button[type="button"]').addEventListener('click', function() {
        //     if (confirm('Are you sure you want to reset the schedule to default values?')) {
        //         // Reset to default values
        //         const defaults = {
        //             monday: { open: '10:00', close: '18:00', status: true },
        //             tuesday: { open: '10:00', close: '18:00', status: true },
        //             wednesday: { open: '10:00', close: '18:00', status: true },
        //             thursday: { open: '10:00', close: '18:00', status: true },
        //             friday: { open: '10:00', close: '18:00', status: true },
        //             saturday: { open: '10:00', close: '14:00', status: false },
        //             sunday: { open: '11:00', close: '15:00', status: false }
        //         };

        //         Object.keys(defaults).forEach(day => {
        //             const row = document.querySelector(`[data-day="${day}"]`);
        //             const openingInput = row.querySelector('.opening-time');
        //             const closingInput = row.querySelector('.closing-time');
        //             const statusToggle = row.querySelector('.status-toggle');

        //             openingInput.value = defaults[day].open;
        //             closingInput.value = defaults[day].close;
        //             statusToggle.checked = defaults[day].status;

        //             // Trigger change event to update UI
        //             statusToggle.dispatchEvent(new Event('change'));
        //         });

        //         showToast('Schedule reset to default values', 'info');
        //     }
        // });
    });

    $('#scheduleForm').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        $('.schedule-row').each(function () {
            const row = this;
            const day = row.dataset.day;
            const isChecked = row.querySelector('.status-toggle').checked;

            const o = row.querySelector('.opening-time');
            const c = row.querySelector('.closing-time');
            const ls = row.querySelector('.lunch-start-time');
            const le = row.querySelector('.lunch-end-time');

            const opening = (o && (o.value || o.defaultValue || '')) ;
            const closing = (c && (c.value || c.defaultValue || '')) ;
            const lunchStart = (ls && (ls.value || ls.defaultValue || '')) ;
            const lunchEnd = (le && (le.value || le.defaultValue || '')) ;

            formData.append(`${day}_open`, opening);
            formData.append(`${day}_close`, closing);
            formData.append(`${day}_lunch_start`, lunchStart);
            formData.append(`${day}_lunch_stop`, lunchEnd);
            if (isChecked) formData.append(`${day}_status`, '1');
        });

        console.log("=== SCHEDULE FORM DATA ===");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ": " + pair[1]);
        }


        $.ajax({
            url: '/save-schedule',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
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
            error: function (xhr) {
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
        const url = isEdit ? '/update-location/' + editingLocationId : '{{ route("save.location") }}';
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
