@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-employee-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add Employee</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item" data-target-form="#contactDetailForm">
                            <a href="#personalDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Personal Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#jobDetailForm">
                            <a href="#address" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-map-pin"></i>
                                <span class="d-none d-sm-inline">Address & Reference </span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#educationDetailForm">
                            <a href="#jobDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-graduation-cap"></i>
                                <span class="d-none d-sm-inline">Payroll Details</span>
                            </a>
                        </li>
                        <li class="nav-item" data-target-form="#bankDetailForm">
                            <a href="#bankDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-graduation-cap"></i>
                                <span class="d-none d-sm-inline">Bank Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#attachments" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-file-text"></i>
                                <span class="d-none d-sm-inline">Attachments</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#access" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-check-circle"></i>
                                <span class="d-none d-sm-inline">Access</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="javascript:void(0);" method="post" name="addUserEmployee" id="addUserEmployee"
                        enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="id" id="empId" value="">
                        @csrf
                        <div class="tab-content">
                            <!-- START: Define your tab pans here -->
                            <div class="tab-pane show active" id="personalDetail">
                                <div class="">
                                    <div class="row mt-4">
                                        <div class="col-sm-auto text-center">
                                            <div class="position-relative me-3 d-inline-flex">
                                                <div class="position-absolute top-50 start-100 translate-middle">
                                                    <!-- File Upload Button with Pencil Icon -->
                                                    <label for="fileUpload" class="btn btn-sm btn-primary btn-icon">
                                                        <i class="ti ti-pencil"></i>
                                                    </label>
                                                    <input type="file" id="fileUpload" class="d-none" name="fileUpload"
                                                        accept="image/*">
                                                </div>
                                                <img src="/storage/profile/e-cashbook.png" alt="user-image"
                                                    id="uploadedImage" class="wid-150 rounded img-fluid ms-2">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Employee Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name" id="name" placeholder="Employee Name" required>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Contact Number <span
                                                            class="text-danger">*</span></label>
                                                    {{-- <input type="text" name="phone" id="phone" class="form-control"
                                                        placeholder="Enter Contact Number"> --}}
                                                    <input type="text" name="phone" id="phone" class="form-control"
                                                        placeholder="Enter Contact Number" maxlength="10"
                                                        oninput="this.value = this.value.replace(/\D/g, '');" required>

                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Email Address<span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" name="email" id="email" class="form-control"
                                                        placeholder="Enter Email Address" required>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Date of Birth<span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" name="dob" id="dob" class="form-control"
                                                        placeholder="Enter date of birth"
                                                        onfocus="this.max = new Date(Date.now() - 86400000).toISOString().split('T')[0]" required>

                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Gender<span
                                                            class="text-danger">*</span></label>
                                                    <div class="form-group me-2">
                                                        <select class="select form-select" name="gender" id="gender" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Aadhaar No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="aadhaar_number"
                                                        id="aadhaar_number" placeholder="Enter Aadhaar Number" maxlength="12" required>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">PAN No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="pan_number"
                                                        id="pan_number" placeholder="Enter PAN Number" maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" required>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Highest
                                                        Qualification<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="qualification"
                                                        id="qualification" placeholder="Enter highest qualification" required>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="alt_phone">Alternate Mobile No <span class="text-danger">*</span></label>
                                                    <input type="text" name="alt_phone" id="alt_phone" class="form-control"
                                                        placeholder="Enter alternate mobile number" maxlength="10"
                                                        oninput="this.value = this.value.replace(/\D/g, '');" required>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="marital_status" id="marital_status" required>
                                                        <option value="">Select Marital Status</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                        <option value="Widowed">Widowed</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="pro_qualification">Professional Qualification (if any)</label>
                                                    <input type="text" class="form-control" name="pro_qualification" id="pro_qualification"
                                                        placeholder="Enter professional qualification">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="last_employer">Last Employer Name</label>
                                                    <input type="text" class="form-control" name="last_employer" id="last_employer"
                                                        placeholder="Enter last employer name">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="experience_years">Year of Work Experience</label>
                                                    <input type="number" class="form-control" name="experience_years" id="experience_years"
                                                        placeholder="Enter years of experience" min="0" step="0.5">
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- end contact detail tab pane -->
                            <div class=" tab-pane" id="address">
                                <div class="">
                                    <div class="row">
                                        <!-- Current Address -->
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                                                <h5>Current Address</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                        <input type="text" name="c_addr_lineone" id="cust_bill_addone"
                                                            class="form-control" placeholder="Enter Address Line 1" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="c_addr_linetwo" id="cust_bill_addtwo"
                                                            class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="c_emp_state" id="state" required>
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="c_emp_city" id="city" required>
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                        <input type="text" name="c_emp_pincode" id="cust_bill_pin"
                                                            class="form-control" placeholder="Enter Zip Code"
                                                            inputmode="numeric" pattern="\d*" maxlength="6"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Permanent Address -->
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5>Permanent Address</h5>
                                                <div class="btn btn-primary" id="copy-permanent-address-btn" onclick="copyParamanentAddress()">Same as Current Address</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                        <input type="text" name="p_addr_lineone" id="cust_ship_addone"
                                                            class="form-control" placeholder="Enter Address Line 1" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="p_addr_linetwo" id="cust_ship_addtwo"
                                                            class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="p_emp_state" id="curr_state" required>
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="p_emp_city" id="curr_city" required>
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                        <input type="text" name="p_emp_pincode" id="cust_ship_pin"
                                                            class="form-control" placeholder="Enter Zip Code"
                                                            inputmode="numeric" pattern="\d*" maxlength="6"
                                                            oninput="this.value = this.value.replace(/\D/g, '')" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reference & Emergency Contact Section -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <h5>Reference & Emergency Contact Details</h5>
                                            <hr>
                                        </div>

                                        <!-- Reference 1 -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Reference 1 Name <span class="text-danger">*</span></label>
                                                <input type="text" name="ref1_name" id="ref1_name" class="form-control"
                                                    placeholder="Enter first reference name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Reference 1 Mobile No <span class="text-danger">*</span></label>
                                                <input type="text" name="ref1_mobile" id="ref1_mobile" class="form-control"
                                                    placeholder="Enter mobile number" maxlength="10"
                                                    oninput="this.value = this.value.replace(/\D/g, '');" required>
                                            </div>
                                        </div>

                                        <!-- Reference 2 -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Reference 2 Name</label>
                                                <input type="text" name="ref2_name" id="ref2_name" class="form-control"
                                                    placeholder="Enter second reference name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Reference 2 Mobile No</label>
                                                <input type="text" name="ref2_mobile" id="ref2_mobile" class="form-control"
                                                    placeholder="Enter mobile number" maxlength="10"
                                                    oninput="this.value = this.value.replace(/\D/g, '');">
                                            </div>
                                        </div>

                                        <!-- Emergency Contact -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Emergency Contact Person Name <span class="text-danger">*</span></label>
                                                <input type="text" name="emergency_name" id="emergency_name" class="form-control"
                                                    placeholder="Enter emergency contact person name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Emergency Contact Mobile No <span class="text-danger">*</span></label>
                                                <input type="text" name="emergency_mobile" id="emergency_mobile" class="form-control"
                                                    placeholder="Enter mobile number" maxlength="10"
                                                    oninput="this.value = this.value.replace(/\D/g, '');" required>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);"
                                            class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- end job detail tab pane -->
                            <div class="tab-pane" id="jobDetails">
                                <div class="">
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="dept_id">Department<span
                                                    class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group me-2" style="flex-grow: 1;">
                                                    {{-- Department Auto Fetch --}}
                                                    <select class="select form-select" name="dept_id" id="dept_id"
                                                        onchange="getDesignationsByDept(this.value);" required>
                                                        <option value="">Select Department</option>
                                                    </select>
                                                </div>
                                                <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center"
                                                    href="#" data-bs-toggle="modal" data-bs-target="#depertment">
                                                    <i class="ti ti-plus py-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="designation_id">Designation<span
                                                    class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group me-2" style="flex-grow: 1;">
                                                    <select class="select form-select" name="designation_id"
                                                        id="designation_id" required>
                                                        <option value="">Select Designation</option>
                                                    </select>
                                                </div>
                                                <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center"
                                                    href="#" data-bs-toggle="modal" data-bs-target="#designation">
                                                    <i class="ti ti-plus py-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="location_id">Company Location<span
                                                    class="text-danger">*</span></label>
                                            <select class="select form-select" name="location_id" id="location_id" required>
                                                <option value="">Select Location</option>
                                                @foreach($locations as $location)
                                                <option value="{{ $location->id }}">
                                                    {{ $location->location_name }}(<small>{{ $location->location_type
                                                        }}</small>)

                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Joining Date<span
                                                        class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="emp_joining_date"
                                                    id="emp_joining_date" placeholder="Enter Employee Joining date" required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="work_location">Work Location<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control" name="work_location" id="work_location" required>
                                                    <option value="">Select Work Location</option>
                                                    <option value="work_from_home">Work From Home</option>
                                                    <option value="work_from_office">Work From Office</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="emp_status">Employee Status<span class="text-danger">*</span></label>
                                                <select class="form-select" name="emp_status" id="emp_status" onchange="toggleStatusDate();" required>
                                                    <option value="In Probation" selected>In Probation</option>
                                                    <option value="Confirmed">Confirmed</option>
                                                    {{-- <option value="Terminated">Terminated</option>
                                                    <option value="Resigned">Resigned</option> --}}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="status_date" id="status_date_label">
                                                    Date (In Probation) <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" class="form-control" name="status_date" id="status_date"
                                                    placeholder="Select relevant date" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="emp_type">Employee Type<span class="text-danger">*</span></label>
                                                <select class="form-select" name="emp_type" id="emp_type">
                                                    <option value="">Select Employee Type</option>
                                                    <option value="Full Time">Full Time</option>
                                                    <option value="Part Time">Part Time</option>
                                                    <option value="Contract">Contract</option>
                                                    <option value="Temporary">Temporary</option>
                                                </select>
                                            </div>
                                        </div>
										
										@if(Auth::user()->u_type == 2 || Auth::user()->u_type == 5)
										<div class="col-md-4 mb-3">
											<label class="form-label">Proprietorship Company</label>
											<select name="propId" id="propId" class="form-control">
												<option value="">{{ parentCompanyName() }}</option>
												@foreach($proprietorships as $company)
													<option value="{{ $company->id }}" data-basic="{{ $company->basic_percentage ?? 50 }}">
														{{ $company->comp_name }}
													</option>
												@endforeach
											</select>
										</div>
										@endif
                                        <!-- Applicability Section -->
                                        <div class="col-12 mt-3">
                                            <h5>Statutory Applicability</h5>
                                            <hr>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="epf_check" name="epf_applicable" value="1"
                                                    onchange="toggleEPFFields()">
                                                <label class="form-check-label" for="epf_check">EPF Applicable</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="esic_check" name="esic_applicable" value="1"
                                                    onchange="toggleESICFields()">
                                                <label class="form-check-label" for="esic_check">ESIC Applicable</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="ptax_check" name="ptax_applicable" value="1">
                                                <label class="form-check-label" for="ptax_check">P-Tax Applicable</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="tds_check" name="tds_applicable" value="1">
                                                <label class="form-check-label" for="tds_check">TDS Applicable</label>
                                            </div>
                                        </div>

                                        <!-- Conditional EPF & ESIC Fields -->
                                        <div class="col-md-6" id="epf_no_field" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="epf_no">Employee EPF No <span class="text-danger">*</span></label>
                                                <input type="text" name="epf_no" id="epf_no" class="form-control"
                                                    placeholder="Enter EPF Number">
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="esic_no_field" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="esic_no">Employee ESIC No <span class="text-danger">*</span></label>
                                                <input type="text" name="esic_no" id="esic_no" class="form-control"
                                                    placeholder="Enter ESIC Number">
                                            </div>
                                        </div>




                                        <h6 class="mb-3">Earnings</h6>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="total_addition">Gross Salary <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="total_addition"
                                                    id="total_addition" value="" placeholder="Enter Gross Salary"
                                                    inputmode="numeric" pattern="\d*"
                                                    oninput="this.value = this.value.replace(/\D/g, '')" required />
                                            </div>
                                        </div>
										<div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic_percentage">
                                                    Basic Salary Percentage <span class="text-danger">*</span>
                                                </label>

                                                <input type="number"
                                                    class="form-control"
                                                    name="basic_percentage"
                                                    id="basic_percentage"
                                                    value="50"
                                                    min="30"
                                                    max="50"
                                                    step="1"
                                                    oninput="if(this.value > 50) this.value = 50;
                                                        if(this.value < 30) this.value = 30;"
                                                    required
                                                />
                                                <small class="text-danger">
                                                    Percentage must be between 30 and 50
                                                </small>

                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic_sal">Basic Salary<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="basic_sal" value="00.00"
                                                    id="basic_sal" readonly  required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="hra">House Rent Allowance (HRA) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="hra" id="hra"
                                                    value="00.00" readonly required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="convayance">Conveyance Allowance <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="convayance"
                                                    id="convayance" value="1600.00" readonly required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="medical_allowance">Medical
                                                    Allowance <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="medical_allowance"
                                                    id="medical_allowance" value="1250.00" readonly required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="special_bonus">Special Allowance <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="special_bonus"
                                                    id="special_bonus" value="00.00" readonly required/>
                                            </div>
                                        </div>
                                        <h6 class="mb-3">Deductions</h6>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Provident Fund (PF) </label>
                                                <input type="text" class="form-control" name="provident_fund" value="0"
                                                    id="provident_fund" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Employee State Insurance
                                                    (ESI)</label>
                                                <input type="text" class="form-control" name="esi" id="esi" value="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Profession Tax (PT) </label>
                                                <input type="text" class="form-control" name="ptax" id="ptax"
                                                    value="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Tax Deducted at Source
                                                    (TDS)</label>
                                                <input type="text" class="form-control" name="tds" id="tds" value="0"
                                                    placeholder="Enter Employee TDS " />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Total Loan Amount</label>
                                                <input type="number" class="form-control" name="loan" id="loan"
                                                    value="0" placeholder="Enter Total Loan Amount" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="loan_tenure">Tenure for Repay (in Months)</label>
                                                <input type="text" class="form-control" name="loan_tenure" id="loan_tenure"
                                                    placeholder="Enter Tenure in Months" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="loan_deduction">Loan Deduction (Monthly)</label>
                                                <input type="text" class="form-control" name="loan_deduction" id="loan_deduction"
                                                    placeholder="Enter Loan Deduction Amount" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Total Deduction </label>
                                                <input type="text" class="form-control" name="total_deduction"
                                                    id="total_deduction" value="0" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Net Salary <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="net_sal" id="net_sal" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Net Salary in Word<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="net_sal_word"
                                                    id="net_sal_word" required/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);"
                                            class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- end job detail tab pane -->
                            <div class="tab-pane" id="bankDetails">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Bank Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_name" id="bank_name"
                                                placeholder="Enter Bank Name " required/>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Bank Branch<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_branch" id="bank_branch"
                                                placeholder="Enter Bank Branch" required/>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">IFSC Code<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ifsc" id="ifsc"
                                                placeholder="Enter IFSC Code " required/>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Swift Code</label>
                                            <input type="text" class="form-control" name="swift_code" id="swift_code"
                                                placeholder="Enter Swift Code " />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Account Holder Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="account_holder_name"
                                                id="account_holder_name" placeholder="Enter Account Holder Name" required/>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Account Number<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="account_number"
                                                id="account_number" placeholder="Enter Account Number" required>
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Confirm Account Number<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="confirm_account_no"
                                                id="confirm_account_no" placeholder="Confirm Account Number" required>
                                            <span id="accountError" class="text-danger"></span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">UPI / VPA No</label>
                                            <input type="text" class="form-control" name="upi_id" id="upi_id"
                                                placeholder="Enter UPI / VPA No." />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);"
                                            class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <a href="javascript:void(0);"
                                            class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- end education detail tab pane -->
                            <div class="tab-pane" id="attachments">
                                <div class="row">

                                    <!-- Aadhar -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Aadhar Card <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="aadhar_attachment" accept="application/pdf,image/*" required>
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- PAN -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PAN Card <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="pan_attachment" accept="application/pdf,image/*" required>
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- Bank -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bank Passbook / Cheque <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="bank_passbook_attachment" accept="application/pdf,image/*" required>
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- CV -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CV <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="cv_attachment" accept=".pdf,.doc,.docx" required>
                                        <small class="text-muted">Only PDF, DOC, DOCX files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- Certificate -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Qualification Certificate <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="certificate_attachment" accept="application/pdf,image/*" required>
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- Experience -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Experience Letter</label>
                                        <input type="file" class="form-control" name="experience_letter" accept="application/pdf,image/*">
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- Offer -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Offer Letter</label>
                                        <input type="file" class="form-control" name="offer_letter" accept="application/pdf,image/*">
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF files allowed (Max: 5MB)</small>
                                    </div>

                                    <!-- Other -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Other Document</label>
                                        <input type="file" class="form-control" name="other_doc" accept="application/pdf,image/*,.doc,.docx">
                                        <small class="text-muted">Only JPG, JPEG, PNG, PDF, DOC, DOCX files allowed (Max: 5MB)</small>
                                    </div>

                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="access">
                                <div class="row d-flex justify-content-center">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Generate Loging Email Address<span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="login_email" id="login_email"
                                            placeholder="Enter Email" required>
                                        <small id="emailError" class="text-danger"></small>
                                    </div>
                                    {{-- <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputPassword4">Password<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required placeholder="Enter Password">
                                            <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputPassword4">Confirm Password<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_pwd"
                                                name="confirm_pwd" required placeholder="Confirm Password">
                                            <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="password">Password<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                required placeholder="Enter Password">
                                            <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="confirm_pwd">Confirm Password<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_pwd"
                                                name="confirm_pwd" required placeholder="Confirm Password">
                                            <div class="input-group-text" id="toggleConfirmPassword"
                                                style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                        <small id="passwordError" class="text-danger"></small>
                                    </div>
									@if(Auth::user()->u_type == 2)
                                        <div class="row">

                                            @foreach($mainMenus as $mainMenu)
                                                @php
                                                    $subMenus = $menu_features->where('parent_id', $mainMenu->id);
                                                @endphp

                                                <div class="col-md-4 mb-4">

                                                    <div class="card border shadow-sm h-100">

                                                        <div class="card-header bg-primary text-white fw-bold">
                                                            {{ $mainMenu->menu_name }}
                                                        </div>

                                                        <div class="card-body">

                                                            @if($subMenus->count())

                                                                @foreach($subMenus as $submenu)

                                                                    <div class="form-check mb-2">
                                                                        <input
                                                                            class="form-check-input submenu-checkbox"
                                                                            type="checkbox"
                                                                            name="emp_permission[]"
                                                                            value="{{ $submenu->code }}"
                                                                            id="menu{{ $submenu->id }}">

                                                                        <label class="form-check-label"
                                                                            for="menu{{ $submenu->id }}">
                                                                            {{ $submenu->menu_name }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach

                                                            @else

                                                                <div class="form-check">

                                                                    <input
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        name="emp_permission[]"
                                                                        value="{{ $mainMenu->code }}"
                                                                        id="menu{{ $mainMenu->id }}">

                                                                    <label class="form-check-label"
                                                                        for="menu{{ $mainMenu->id }}">
                                                                        {{ $mainMenu->menu_name }}
                                                                    </label>

                                                                </div>

                                                            @endif

                                                        </div>

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>
									@else
                                        <div class="mb-3 row">
                                            <div class="col-sm-12">
                                                <!-- Flexbox container for checkboxes -->
                                                <div class="d-flex flex-wrap justify-content-start">
                                                    <!-- Checkbox Group 1 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminProfile" name="emp_permission[]"
                                                                id="customCheckinlh0">
                                                            <label class="form-check-label" for="customCheckinlh0">Admin profile</label>
                                                        </div>
                                                    </div>
                                                    <!-- Checkbox Group 1 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminSubscription" name="emp_permission[]"
                                                                id="customCheckinlh1">
                                                            <label class="form-check-label"
                                                                for="customCheckinlh1">Manage Subscription</label>
                                                        </div>
                                                    </div>
                                                    <!-- Checkbox Group 2 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="adminCa"
                                                                name="emp_permission[]" id="customCheckinlh2">
                                                            <label class="form-check-label" for="customCheckinlh2">CA Manage</label>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group 3 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="adminTds"
                                                                name="emp_permission[]" id="customCheckinlh3">
                                                            <label class="form-check-label" for="customCheckinlh3">TDS TAX</label>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group 4 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminCustomer" name="emp_permission[]"
                                                                id="customCheckinlh4">
                                                            <label class="form-check-label" for="customCheckinlh4">Customer Manage</label>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group 5 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminHrPayroll" name="emp_permission[]"
                                                                id="customCheckinlh5">
                                                            <label class="form-check-label" for="customCheckinlh5">HR & Payroll</label>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group 6 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminBusinessEarnings" name="emp_permission[]"
                                                                id="customCheckinlh6">
                                                            <label class="form-check-label" for="customCheckinlh6">Business & Earnings</label>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group 7 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminPayment" name="emp_permission[]"
                                                                id="customCheckinlh7">
                                                            <label class="form-check-label" for="customCheckinlh7">Payment Manage</label>
                                                        </div>
                                                    </div>

                                                    <!-- Checkbox Group 8 -->
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminTicket" name="emp_permission[]"
                                                                id="customCheckinlh8">
                                                            <label class="form-check-label" for="customCheckinlh8">Ticket Manage</label>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminReminder" name="emp_permission[]"
                                                                id="customCheckinlh9">
                                                            <label class="form-check-label" for="customCheckinlh9">Reminder & Communication</label>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminTaxFilling" name="emp_permission[]"
                                                                id="customCheckinlh10">
                                                            <label class="form-check-label" for="customCheckinlh10">Tax Filing & Returns</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminAgent" name="emp_permission[]"
                                                                id="customCheckinlh11">
                                                            <label class="form-check-label" for="customCheckinlh11">Agent & Channel Partner</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminDirectBusiness" name="emp_permission[]"
                                                                id="customCheckinlh12">
                                                            <label class="form-check-label" for="customCheckinlh12">Direct Business Desk</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminReport" name="emp_permission[]"
                                                                id="customCheckinlh13">
                                                            <label class="form-check-label" for="customCheckinlh13">Report Section</label>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminAudit" name="emp_permission[]"
                                                                id="customCheckinlh14">
                                                            <label class="form-check-label" for="customCheckinlh14">Audit & Log Management</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="adminSettings" name="emp_permission[]"
                                                                id="customCheckinlh15">
                                                            <label class="form-check-label" for="customCheckinlh15">Settings & Administration</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                        <div class="form-check">
                                                            <input class="form-check-input noAccessCheck" type="checkbox"
                                                                value="No Access" name="emp_permission[]">

                                                            <label class="form-check-label">
                                                                No Access
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
									@endif
                                    <!-- end col -->
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);"
                                            class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    {{-- <div class="last">
                                        <button type='submit' id=""
                                            class="btn btn-primary d-flex align-items-center justify-content-center">
                                            Add Employee <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </button>
                                    </div> --}}

                                    <div class="last">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-primary d-flex align-items-center justify-content-center"
                                            disabled>
                                            Add Employee <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>

<div class="modal fade" id="depertment" tabindex="-1" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add New Department</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0);" method="post" name="addAdminDepertmentFrm" id="addAdminDepertmentFrm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Add New Department</label>
                                <input type="text" name="dept_name" id="dept_name" class="form-control"
                                    placeholder="Enter Department">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="message-container"></div>
                    <div id="addEmployeeLoader" class="loader"></div>
                    <button type="submit" class="btn btn-primary" id="saveDepartment">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="designation" tabindex="-1" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Add New Designation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:void(0);" method="post" name="addAdminDesignationFrm" id="addAdminDesignationFrm"
                novalidate="novalidate">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12 mb-3">
                            <div class="form-group">
                                <label>Select Department</label>
                                <select class="select form-select" name="dept_id" id="deptName" required>
                                    <option value="">Select Department</option>
                                    <!-- Departments will be loaded here via AJAX -->
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Add New Designation</label>
                                <input type="text" name="designation_name" id="designation_name" class="form-control"
                                    placeholder="Enter Designation category" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="message-container"></div>
                    <div id="addEmployeeLoader" class="loader" style="display: none;"></div>
                    <button type="submit" class="btn btn-primary" id="saveDesignation">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
    // Auto Fetch filled email for login when user types email in the email field
    const emailInput = document.getElementById('email');
    const loginEmailInput = document.getElementById('login_email');

    emailInput.addEventListener('input', function () {
        loginEmailInput.value = emailInput.value;
    });

    // Auto uppercase PAN number input
    document.getElementById("pan_number").addEventListener("input", function () {
        this.value = this.value.toUpperCase();
    });

    // Set Basic Salary percentage
    $(document).ready(function(){

        $('#propId').on('change', function(){

            var basic = $(this).find(':selected').data('basic');

            if(basic){
                $('#basic_percentage').val(basic);
            }else{
                $('#basic_percentage').val({{ $basic_percentage ?? 50 }});
            }

        });

    });

	//fetch TDS
	let TDS_SLABS = [];
	fetch('/tds-slabs')
		.then(res => res.json())
		.then(data => {
			TDS_SLABS = data;
		});
		
    // JavaScript to handle dynamic label change based on employee status
    function toggleStatusDate() {
        const status = document.getElementById('emp_status').value;
        const label = document.getElementById('status_date_label');

        if (status === 'Confirmed') {
            label.textContent = 'Confirmation Date';
        }  else {
            label.textContent = 'Date (In Probation)';
        }
    }


    // JavaScript to show/hide EPF and ESIC fields based on checkbox
    function toggleEPFFields() {

        const epfCheck = document.getElementById('epf_check');
        const epfField = document.getElementById('epf_no_field');
        const epfInput = document.getElementById('epf_no');
        // epfField.style.display = epfCheck.checked ? 'block' : 'none';

        // alert('EPF Checkbox toggled. Checked: ' + epfCheck.checked);
        const pfDeduction = document.getElementById('provident_fund');

        if (epfCheck.checked) {
            epfField.style.display = 'block';
            epfInput.setAttribute('required', 'required'); // make required
            if (pfDeduction) {
                pfDeduction.disabled = false;
            }
        } else {
            epfField.style.display = 'none';
            epfInput.removeAttribute('required'); // remove required
            epfInput.value = ''; // optional: clear value
            if (pfDeduction) {
                pfDeduction.disabled = true;
                pfDeduction.value = '00.00';
            }
        }
    }

    function toggleESICFields() {
        const esicCheck = document.getElementById('esic_check');
        const esicField = document.getElementById('esic_no_field');
        const esicInput = document.getElementById('esic_no');
        const esiInput = document.getElementById('esi');

        if (esicCheck.checked) {
            esicField.style.display = 'block';
            esicInput.setAttribute('required', 'required'); // make required
            if (esiInput) {
                esiInput.disabled = false;
            }
        } else {
            esicField.style.display = 'none';
            esicInput.removeAttribute('required'); // remove required
            esicInput.value = ''; // optional: clear value
            if (esiInput) {
                esiInput.disabled = true;
                esiInput.value = '00.00';
            }
        }

    }

    // Function to convert number to words (Indian system)
    function numberToWords(num) {
        const a = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
            'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
            'Seventeen', 'Eighteen', 'Nineteen'
        ];
        const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        const numberToWordsHelper = (n) => {
            if (n < 20) return a[n];
            if (n < 100) return b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '');
            if (n < 1000) return a[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' and ' + numberToWordsHelper(n % 100) : '');
            if (n < 100000) return numberToWordsHelper(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + numberToWordsHelper(n % 1000) : '');
            if (n < 10000000) return numberToWordsHelper(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + numberToWordsHelper(n % 100000) : '');
            return numberToWordsHelper(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + numberToWordsHelper(n % 10000000) : '');
        };

        return numberToWordsHelper(Math.floor(num)) + ' Rupees Only';
    }


    document.addEventListener("DOMContentLoaded", function () {
        const grossInput = document.getElementById("total_addition");
        const basicPercentageInput = document.getElementById("basic_percentage");
        const basicInput = document.getElementById("basic_sal");
        const hraInput = document.getElementById("hra");
        const medicalInput = document.getElementById("medical_allowance");
        const convayanceInput = document.getElementById("convayance");
        const specialInput = document.getElementById("special_bonus");

        const pfInput = document.getElementById("provident_fund");
        const epfNoField = document.getElementById("epf_no");
        const esiInput = document.getElementById("esi");
        const esicNoField = document.getElementById("esic_no");
        const ptaxInput = document.getElementById("ptax");
        const tdsInput = document.getElementById("tds");
        const loanInput = document.getElementById("loan");
        const totalDeductionInput = document.getElementById("total_deduction");
        const netSalaryInput = document.getElementById("net_sal");
        const netSalaryWordInput = document.getElementById("net_sal_word");

        const epfCheck = document.getElementById("epf_check");
        const esicCheck = document.getElementById("esic_check");
        const ptaxCheck = document.getElementById("ptax_check");
        const tdsCheck = document.getElementById("tds_check");

        function updateDeductionFields() {
            pfInput.disabled = !epfCheck.checked;
            epfNoField.disabled = !epfCheck.checked;
            if (!epfCheck.checked) {
                pfInput.value = "00.00";
                epfNoField.value = "";
            }

            esiInput.disabled = !esicCheck.checked;
            esicNoField.disabled = !esicCheck.checked;
            if (!esicCheck.checked) {
                esiInput.value = "00.00";
                esicNoField.value = "";
            }

            ptaxInput.disabled = !ptaxCheck.checked;
            if (!ptaxCheck.checked) ptaxInput.value = "00.00";

            /*tdsInput.disabled = !tdsCheck.checked;
            if (!tdsCheck.checked) tdsInput.value = "00.00";*/
        }

        function calculateAll() {
            const gross = parseFloat(grossInput.value) || 0;
            const basicPercentage = parseFloat(basicPercentageInput.value) || 50;

            // Earnings calculation
            //const basic = gross > 0 ? gross * 0.50 : 0; // Basic = 50% of gross
            const basic = gross > 0 ? (gross * basicPercentage) / 100 : 0; // Basic = 50% of gross
            const hra = basic > 0 ? basic * 0.50 : 0;   // HRA = 50% of basic
            const medical = gross > 0 ? 1250 : 0;
            const convayance = gross > 0 ? 1600 : 0;
            let special = gross - (basic + hra + medical + convayance);
            if (special < 0) special = 0;

            basicInput.value = basic.toFixed(2);
            hraInput.value = hra.toFixed(2);
            medicalInput.value = medical.toFixed(2);
            convayanceInput.value = convayance.toFixed(2);
            specialInput.value = special.toFixed(2);

            // Deductions
            let pf = 0, esi = 0, ptax = 0, tds = 0;
            let loan = parseFloat(loanInput.value) || 0;

            // PF Calculation
            if (epfCheck.checked && basic > 0) {

                pf = basic * 0.12;

                // Max PF limit 1800
                if (pf > 1800) {
                    pf = 1800;
                }

                pfInput.value = pf.toFixed(2);

            } else {

                pfInput.value = "00.00";
            }

            // ESI Calculation
            if (esicCheck.checked && gross > 0) {
                esi = gross <= 21000 ? gross * 0.0075 : 0;
                esiInput.value = esi.toFixed(2);
            } else {
                esiInput.value = "00.00";
            }

            // PTAX Calculation (as per slab)
            if (ptaxCheck.checked && gross > 0) {
                if (gross <= 10000) ptax = 0;
                else if (gross > 10000 && gross <= 15000) ptax = 110;
                else if (gross > 15000 && gross <= 25000) ptax = 130;
                else if (gross > 25000 && gross <= 40000) ptax = 150;
                else if (gross > 40000) ptax = 200;
                ptaxInput.value = ptax.toFixed(2);
            } else {
                ptaxInput.value = "00.00";
            }

            // TDS Calculation (manual)
            /*if (tdsCheck.checked) {
                tds = parseFloat(tdsInput.value) || 0;
                tdsInput.value = tds.toFixed(2);
            } else {
                tdsInput.value = "00.00";
            }*/
            // TDS Calculation (Auto from tds_salary_slabs Slabs)
            if (tdsCheck.checked && gross > 0 && TDS_SLABS.length) {
                tds = calculateMonthlyTdsFromSlabs(gross);
                tdsInput.value = tds.toFixed(2);
            } else {
                tds = 0;
                tdsInput.value = "00.00";
            }

            // Total Deduction
            const totalDeduction = pf + esi + ptax + tds + loan;
            totalDeductionInput.value = totalDeduction.toFixed(2);

            // Net Salary
            const netSalary = gross - totalDeduction;
            netSalaryInput.value = netSalary.toFixed(2);

            // Net Salary in Word
            netSalaryWordInput.value = numberToWords(netSalary);
        }

        // Initial state on page load
        updateDeductionFields();
        calculateAll();

        // Listen for checkbox changes
        [epfCheck, esicCheck, ptaxCheck, tdsCheck].forEach(chk => {
            chk.addEventListener("change", function () {
                updateDeductionFields();
                calculateAll();
            });
        });

        // Listen for field changes
        [grossInput, basicPercentageInput, loanInput, tdsInput].forEach(input => {
            input.addEventListener("input", function () {
                calculateAll();
            });
        });
    });


    //------------ Password and confirm password and email check ----------

    $(document).ready(function() {
        let emailValid = false;
        let emailExists = false;
        let passwordsMatch = false;
        let accountNumbersMatch = false;
        let typingTimer;
        let doneTypingInterval = 500; // 500ms delay after typing stops

        function validateEmailFormat(email) {
            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        function validateForm() {
            if (emailValid && !emailExists && passwordsMatch && accountNumbersMatch) {
                $("#submitBtn").prop("disabled", false);
            } else {
                $("#submitBtn").prop("disabled", true);
            }
        }

        // Email validation (format + database check)
        $("#login_email, #email").on("keyup", function() {

            clearTimeout(typingTimer);
            let email = $(this).val();

            if (email.length > 0) {
                if (!validateEmailFormat(email)) {
                    $("#emailError").text("Invalid email format!");
                    emailValid = false;
                    emailExists = false;
                    validateForm();
                    return;
                } else {
                    $("#emailError").text(""); // Remove error if format is valid
                    emailValid = true;
                }

                typingTimer = setTimeout(function() {
                    $.ajax({
                        url: "/check-email",
                        type: "POST",
                        data: {
                            email: email,
                            _token: "{{ csrf_token() }}" // Required for Laravel POST requests
                        },
                        success: function(response) {
                            if (response.exists) {
                                $("#emailError").text("Email is already taken!");
                                emailExists = true;
                            } else {
                                $("#emailError").text("");
                                emailExists = false;
                            }
                            validateForm();
                        }
                    });
                }, doneTypingInterval);
            }
        });

        // Password validation
        $("#password, #confirm_pwd").on("input", function() {
            let password = $("#password").val();
            let confirmPwd = $("#confirm_pwd").val();

            if (password !== confirmPwd) {
                $("#passwordError").text("Passwords do not match!");
                passwordsMatch = false;
            } else {
                $("#passwordError").text("");
                passwordsMatch = true;
            }
            validateForm();
        });

        // Account number validation
        $("#account_number, #confirm_account_no").on("input", function() {
            let accountNumber = $("#account_number").val();
            let confirmAccountNo = $("#confirm_account_no").val();

            if (accountNumber !== confirmAccountNo) {
                $("#accountError").text("Account numbers do not match!");
                accountNumbersMatch = false;
            } else {
                $("#accountError").text("");
                accountNumbersMatch = true;
            }
            validateForm();
        });
    });



    //----------- file upload ---------
    document.getElementById('fileUpload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadedImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    function addDesignation() {
        const deptId = document.getElementById("deptName").value;
        const designationName = document
            .getElementById("designation_name")
            .value.trim();

        if (deptId && designationName) {
            departments[deptId].designations.push(designationName);
            document.getElementById("designation_name").value = "";
            const modal = new bootstrap.Modal(
                document.getElementById("designation")
            );
            modal.hide();
        }
    }
    //Add Employee Copy from Parmanent Address
    function copyParamanentAddress() {
        // Copy Address Fields
        document.getElementById("cust_ship_addone").value = document.getElementById("cust_bill_addone").value;
        document.getElementById("cust_ship_addtwo").value = document.getElementById("cust_bill_addtwo").value;
        document.getElementById("cust_ship_pin").value = document.getElementById("cust_bill_pin").value;

        // Copy State and trigger change event to fetch cities
        let permanentState = document.getElementById("state").value;
        let currentState = document.getElementById("curr_state");
        currentState.value = permanentState;

        // Trigger change event on the state dropdown
        let event = new Event("change", {
            bubbles: true
        });
        currentState.dispatchEvent(event);

        // Store the permanent city value to use after AJAX completes
        let permanentCity = document.getElementById("city").value;

        // Attach an event listener for AJAX completion
        $(document).on("cityDataLoaded", function() {
            let currentCity = document.getElementById("curr_city");

            // Check if the city exists in the dropdown before assigning
            let cityExists = [...currentCity.options].some(option => option.value === permanentCity);

            if (cityExists) {
                currentCity.value = permanentCity;
            }
        });
    }

    //------------- Present city fetch  ---------
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
                data: {
                    id: id
                },
                success: function(data) {
                    $("#city").empty();
                    var str = '<option value="">Select City</option>';
                    $.each(data, function(idx, item) {
                        str += '<option value="' + item.id + '">' + item.name + "</option>";
                    });
                    $("#city").html(str);
                },
            });
        } else {
            alert("No state selected!");
        }
    });

    //------------- Current city fetch (Modified)  ---------
    const curr_stateDropdown = document.getElementById('curr_state');
    curr_stateDropdown.addEventListener('change', function() {
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
                data: {
                    id: id
                },
                success: function(data) {
                    $("#curr_city").empty();
                    var str = '<option value="">Select City</option>';
                    $.each(data, function(idx, item) {
                        str += '<option value="' + item.id + '">' + item.name + "</option>";
                    });
                    $("#curr_city").html(str);

                    // Trigger custom event when city data is loaded
                    $(document).trigger("cityDataLoaded");
                },
            });
        } else {
            alert("No state selected!");
        }
    });


    //--------- Department Auto Fetch ----------
    $(document).ready(function() {
        fetchDepartments();

        $('#addAdminDepertmentFrm').on('submit', function(e) {
            e.preventDefault();

            var dept_name = $('#dept_name').val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/add-department') }}",
                type: "POST",
                data: {
                    dept_name: dept_name,
                    _token: _token
                },
                dataType: "json",
                success: function(response) {
                    // if (response.success) {
                    //     $('.message-container').html('<div class="alert alert-success">' + response.message + '</div>');
                    //     setTimeout(function() {
                    //         $('#depertment').modal('hide'); // Close modal
                    //         $('.message-container').html(''); // Clear message
                    //     }, 2000);
                    //     $('#addAdminDepertmentFrm')[0].reset();
                    //     fetchDepartments(); // Refresh dropdown
                    // }

                    if (response.success) {
                        // showToast("Profile picture updated successfully!", "success");
                        // showToast(response.message, "success");
                        setTimeout(function() {
                            $('#depertment').modal('hide'); // Close modal
                            showToast(response.message, "success");
                        }, 2000);
                        $('#addAdminDepertmentFrm')[0].reset();
                        fetchDepartments(); // Refresh dropdown
                    } else {
                        showToast("Error: " + response.message, "error");
                    }
                },
                error: function(xhr) {
                    showToast("Error: Error adding department", "error");
                    // $('.message-container').html('<div class="alert alert-danger">Error adding department</div>');
                }
            });
        });

        $('#addAdminDesignationFrm').on('submit', function(e) {
            e.preventDefault();

            var dept_id = $('#deptName').val(); // Fixed the variable
            var designation_name = $('#designation_name').val();
            var _token = $('input[name="_token"]').val();

            if (!dept_id || !designation_name) {
                $('.message-container').html('<div class="alert alert-warning">Please fill all fields.</div>');
                return;
            }

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/add-designation') }}",
                type: "POST",
                data: {
                    dept_id: dept_id,
                    designation_name: designation_name,
                    _token: _token
                },
                dataType: "json",
                beforeSend: function() {
                    $('#addEmployeeLoader').show(); // Show loader before sending request
                },
                success: function(response) {
                    $('#addEmployeeLoader').hide(); // Hide loader on success

                    if (response.success) {
                        // $('.message-container').html('<div class="alert alert-success">' + response.message + '</div>');

                        // Close modal after 2 seconds
                        setTimeout(function() {
                            $('#designation').modal('hide'); // Close modal
                            // $('.message-container').html(''); // Clear message
                            showToast(response.message, "success");
                        }, 2000);
                        var deptField_id = $('#dept_id').val();
                        $('#addAdminDesignationFrm')[0].reset(); // Reset form
                        getDesignationsByDept(deptField_id);
                    } else {
                        showToast("Error: " + response.message, "error");
                        // $('.message-container').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function(xhr) {
                    $('#addEmployeeLoader').hide(); // Hide loader on error
                    showToast("Error: Error adding designation", "error");
                    // $('.message-container').html('<div class="alert alert-danger">Error adding designation</div>');
                }
            });
        });

        //---------- Create User employee -------
        $('#addUserEmployee').on('submit', function(e) {

            e.preventDefault();

            let name = $('#name').val().trim();
            let phone = $('#phone').val().trim();
            let email = $('#email').val().trim();
            let dob = $('#dob').val().trim();
            let gender = $('#gender').val();

            let maritalStatus = $('#marital_status').val();
            let qualification = $('#qualification').val().trim();
            let proQualification = $('#pro_qualification').val().trim();
            let lastEmployer = $('#last_employer').val().trim();
            let experienceYears = $('#experience_years').val().trim();

            // let qualification = $('#qualification').val().trim();

            let deptId = $('#dept_id').val();
            let designationId = $('#designation_id').val();
            let joiningDate = $('#emp_joining_date').val();
			let propId = $("#propId option:selected").val();
            let totalAddition = $('#total_addition').val();

            let bankName = $('#bank_name').val().trim();
            let location_id = $('#location_id').val().trim();
            let bankBranch = $('#bank_branch').val().trim();
            let ifsc = $('#ifsc').val().trim();
            let swiftCode = $('#swift_code').val().trim();
            let accHolderName = $('#account_holder_name').val().trim();
            let accNumber = $('#account_number').val().trim();
            let confirmAccNumber = $('#confirm_account_no').val().trim();

            let ref1Name = $('#ref1_name').val().trim();
            let ref1Mobile = $('#ref1_mobile').val().trim();
            let ref2Name = $('#ref2_name').val().trim();
            let ref2Mobile = $('#ref2_mobile').val().trim();
            let emergencyName = $('#emergency_name').val().trim();
            let emergencyMobile = $('#emergency_mobile').val().trim();

            let pan_number = $('#pan_number').val().trim();
            let aadhaar_number = $('#aadhaar_number').val().trim();

            // Manual validation
            if (name === "") {
                showToast("Employee Name is required", "error");
                $('#name').focus();
                return;
            }
            if (phone === "") {
                showToast("Contact Number is required", "error");
                $('#phone').focus();
                return;
            }
            if (email === "") {
                showToast("Email Address is required", "error");
                $('#email').focus();
                return;
            }
            if (dob === "") {
                showToast("Date of Birth is required", "error");
                $('#dob').focus();
                return;
            }
            if (gender === "") {
                showToast("Gender is required", "error");
                $('#gender').focus();
                return;
            }
            if (qualification === "") {
                showToast("Qualification is required", "error");
                $('#qualification').focus();
                return;
            }
            if (deptId === "") {
                showToast("Please select a Department", "error");
                $('#dept_id').focus();
                return;
            }
            if (designationId === "") {
                showToast("Please select a Designation", "error");
                $('#designation_id').focus();
                return;
            }
            if (location_id === "") {
                showToast("Please select Company Location", "error");
                $('#location_id').focus();
                return;
            }
            if (joiningDate === "") {
                showToast("Employee Joining Date is required", "error");
                $('#emp_joining_date').focus();
                return;
            }

            // If totalAddition is optional, skip this check.
            if (totalAddition !== "" && isNaN(totalAddition)) {
                showToast("Total Addition must be a number", "error");
                $('#total_addition').focus();
                return;
            }

            if (bankName === "") {
                showToast("Bank Name is required", "error");
                $('#bank_name').focus();
                return;
            }
            if (bankBranch === "") {
                showToast("Bank Branch is required", "error");
                $('#bank_branch').focus();
                return;
            }
            if (ifsc === "") {
                showToast("IFSC Code is required", "error");
                $('#ifsc').focus();
                return;
            }
            // if (swiftCode === "") {
            //     showToast("Swift Code is required", "error");
            //     $('#swift_code').focus();
            //     return;
            // }
            if (accHolderName === "") {
                showToast("Account Holder Name is required", "error");
                $('#account_holder_name').focus();
                return;
            }
            if (accNumber === "") {
                showToast("Account Number is required", "error");
                $('#account_number').focus();
                return;
            }
            if (confirmAccNumber === "") {
                showToast("Please confirm Account Number", "error");
                $('#confirm_account_no').focus();
                return;
            }
            if (accNumber !== confirmAccNumber) {
                showToast("Account Number and Confirm Account Number do not match", "error");
                $('#confirm_account_no').focus();
                return;
            }

            // Validate experience as non-negative
            if (experienceYears !== "" && (isNaN(experienceYears) || experienceYears < 0)) {
                showToast("Experience years must be a valid non-negative number", "error");
                $('#experience_years').focus(); return;
            }

            if (ref1Name === "") return showToast("Reference 1 Name is required", '#ref1_name');
            if (ref1Mobile === "") return showToast("Reference 1 Mobile is required", '#ref1_mobile');
            if (ref1Mobile.length !== 10) return showToast("Reference 1 Mobile must be 10 digits", '#ref1_mobile');

            // if (ref2Name === "") return showToast("Reference 2 Name is required", '#ref2_name');
            // if (ref2Mobile === "") return showToast("Reference 2 Mobile is required", '#ref2_mobile');
            // if (ref2Mobile.length !== 10) return showToast("Reference 2 Mobile must be 10 digits", '#ref2_mobile');

            if (emergencyName === "") return showToast("Emergency Contact Name is required", '#emergency_name');
            if (emergencyMobile === "") return showToast("Emergency Contact Mobile is required", '#emergency_mobile');
            if (emergencyMobile.length !== 10) return showToast("Emergency Contact Mobile must be 10 digits", '#emergency_mobile');

            if (pan_number === "") {
                showToast("PAN Number is required", "error");
                $('#pan_number').focus();
                return;
            }
            if (aadhaar_number === "") {
                showToast("Aadhaar Number is required", "error");
                $('#aadhaar_number').focus();
                return;
            }
            // --- Job Details Tab Validation ---
            // Statutory Applicability
            if ($('#epf_check').is(':checked')) {
                let epfNo = $('#epf_no').val().trim();
                let pf = $('#provident_fund').val().trim();
                if (epfNo === "") {
                    showToast("Employee EPF No is required", "error");
                    $('#epf_no').focus();
                    return;
                }
                if (pf === "" || pf === "00.00" || isNaN(pf)) {
                    showToast("Provident Fund (PF) value is required and must be a number", "error");
                    $('#provident_fund').focus();
                    return;
                }
            }
            if ($('#esic_check').is(':checked')) {
                let esicNo = $('#esic_no').val().trim();
                let esi = $('#esi').val().trim();
                if (esicNo === "") {
                    showToast("Employee ESIC No is required", "error");
                    $('#esic_no').focus();
                    return;
                }
                if (esi === "" || esi === "00.00" || isNaN(esi)) {
                    showToast("Employee State Insurance (ESI) value is required and must be a number", "error");
                    $('#esi').focus();
                    return;
                }
            }
            if ($('#ptax_check').is(':checked')) {
                let ptax = $('#ptax').val().trim();
                if (ptax === "" || ptax === "00.00" || isNaN(ptax)) {
                    showToast("Profession Tax (PT) value is required and must be a number", "error");
                    $('#ptax').focus();
                    return;
                }
            }
            /*if ($('#tds_check').is(':checked')) {
                let tds = $('#tds').val().trim();
                if (tds === "" || tds === "00.00" || isNaN(tds)) {
                    showToast("Tax Deducted at Source (TDS) value is required and must be a number", "error");
                    $('#tds').focus();
                    return;
                }
            }*/

            // Earnings
            let gross = $('#total_addition').val().trim();
			let basicPercentage = $('#basic_percentage').val().trim();
            let basic = $('#basic_sal').val().trim();
            let hra = $('#hra').val().trim();
            let medical = $('#medical_allowance').val().trim();
            let convayance = $('#convayance').val().trim();
            let special = $('#special_bonus').val().trim();

            if (gross === "" || isNaN(gross)) {
                showToast("Gross Salary is required and must be a number", "error");
                $('#total_addition').focus();
                return;
            }
			if (basicPercentage === "" || isNaN(basicPercentage) || basicPercentage < 40 || basicPercentage > 60) {
                showToast("Basic Salary Percentage is required and must be between 40% to 60%", "error");
                $('#basic_percentage').focus();
                return;
            }
            if (basic === "" || isNaN(basic)) {
                showToast("Basic Salary is required and must be a number", "error");
                $('#basic_sal').focus();
                return;
            }
            if (hra === "" || isNaN(hra)) {
                showToast("HRA is required and must be a number", "error");
                $('#hra').focus();
                return;
            }
            if (medical === "" || isNaN(medical)) {
                showToast("Medical Allowance is required and must be a number", "error");
                $('#medical_allowance').focus();
                return;
            }
            if (convayance === "" || isNaN(convayance)) {
                showToast("Conveyance Allowance is required and must be a number", "error");
                $('#convayance').focus();
                return;
            }
            if (special === "" || isNaN(special)) {
                showToast("Special Allowance is required and must be a number", "error");
                $('#special_bonus').focus();
                return;
            }

            // Deductions
            let totalDeduction = $('#total_deduction').val().trim();
            let netSalary = $('#net_sal').val().trim();
            let netSalaryWord = $('#net_sal_word').val().trim();

            if (totalDeduction === "" || isNaN(totalDeduction)) {
                showToast("Total Deduction is required and must be a number", "error");
                $('#total_deduction').focus();
                return;
            }
            if (netSalary === "" || isNaN(netSalary)) {
                showToast("Net Salary is required and must be a number", "error");
                $('#net_sal').focus();
                return;
            }
            if (netSalaryWord === "") {
                showToast("Net Salary in Word is required", "error");
                $('#net_sal_word').focus();
                return;
            }

            let formData = new FormData(this);
            $("#loader").show();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/add_user_employee') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#addAdminEmployeeFrm button[type='submit']").prop("disabled", true);
                },
                success: function(response) {
                    $("#loader").hide();
                    // console.log(response);


                    if (response.status == "success") {
                        showToast(response.message, "success");

                        // Delay redirect by 2 seconds so toast can be seen
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else {
                        showToast("Error: " + response.message, "error");
                    }
                },
                error: function (xhr) {
                    $("#loader").hide();
					let message = "Error: Adding Employee";

					if (xhr.responseJSON) {
						if (xhr.responseJSON.errors) {
							let errors = xhr.responseJSON.errors;
							let errorMessages = [];

							$.each(errors, function (key, value) {
								errorMessages.push(value[0]); // first error message
							});

							message = errorMessages.join(", ");
						} else if (xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}
					}
                    $("#loader").hide();
					showToast(message, "error", 5000);
				}

            });
        });

    });

    function fetchDepartments() {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "{{ url('/get-departments') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                var dropdown = $('#dept_id');
                dropdown.empty(); // Clear previous options
                dropdown.append('<option value="">Select Department</option>');

                $.each(response, function(index, dept) {
                    dropdown.append('<option value="' + dept.id + '">' + dept.dept_name + '</option>');
                });

                //------------------ department show ---------

                var dropName = $('#deptName');
                dropName.empty();
                dropName.append('<option value="">Select Department</option>');
                $.each(response, function(index, dept) {
                    dropName.append('<option value="' + dept.id + '">' + dept.dept_name + '</option>');
                });

            },
            error: function(xhr) {
                console.log("Error fetching departments:", xhr);
            }
        });
    }

    function getDesignationsByDept(dept_id) {
        if (dept_id) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/get-designations') }}/" + dept_id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    // console.log(response);

                    var dropdown = $('#designation_id');
                    dropdown.empty();
                    dropdown.append('<option value="">Select Designation</option>');

                    $.each(response, function(index, designation) {
                        dropdown.append('<option value="' + designation.id + '">' + designation.designation_name + '</option>');
                    });
                },
                error: function(xhr) {
                    console.log("Error fetching designations:", xhr);
                }
            });
        } else {
            $('#designation_id').empty().append('<option value="">Select Designation</option>');
        }
    }
    //Previous & Next Button
    document.addEventListener("DOMContentLoaded", function() {
        const nextButtons = document.querySelectorAll(".next-btn");
        const prevButtons = document.querySelectorAll(".previous-btn");

        nextButtons.forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                const currentTab = document.querySelector(".tab-pane.active");
                const nextTab = currentTab.nextElementSibling;

                if (nextTab && nextTab.classList.contains("tab-pane")) {
                    let nextTabId = nextTab.getAttribute("id");
                    let tabTrigger = document.querySelector(`[data-bs-toggle="tab"][href="#${nextTabId}"]`);

                    if (tabTrigger) {
                        let tabToShow = new bootstrap.Tab(tabTrigger);
                        tabToShow.show();
                    } else {
                        console.error("Next tab trigger not found:", nextTabId);
                    }
                }
            });
        });

        prevButtons.forEach(button => {
            button.addEventListener("click", function(event) {
                event.preventDefault();

                const currentTab = document.querySelector(".tab-pane.active");
                const prevTab = currentTab.previousElementSibling;

                if (prevTab && prevTab.classList.contains("tab-pane")) {
                    let prevTabId = prevTab.getAttribute("id");
                    let tabTrigger = document.querySelector(`[data-bs-toggle="tab"][href="#${prevTabId}"]`);

                    if (tabTrigger) {
                        let tabToShow = new bootstrap.Tab(tabTrigger);
                        tabToShow.show();
                    } else {
                        console.error("Previous tab trigger not found:", prevTabId);
                    }
                }
            });
        });
    });

    function showCompanyPolicyPopup(type) {
        let msg = "";
        if (type === "epf") {
            msg = "Company PF Number is required. Please update your company profile with PF No.";
        } else if (type === "esic") {
            msg = "Company ESIC Number is required. Please update your company profile with ESIC No.";
        } else if (type === "ptax") {
            msg = "Company P-Tax Number is required. Please update your company profile with P-Tax No.";
        } else if (type === "tds") {
            msg = "Company TAN Number is required. Please update your company profile with TAN No.";
        }
        // Using SweetAlert for better popup
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: msg,
            });
        } else {
            showToast(msg, "error");
        }
    }

    function checkCompanyPolicy(type) {
        $.ajax({
            url: "{{ route('check.company.policies') }}",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (type === "epf" && (!response.comp_epf || response.comp_epf === "")) {
                    showCompanyPolicyPopup("epf");
                    const epfCheckbox = document.getElementById("epf_check");
                    if (epfCheckbox) {
                        epfCheckbox.checked = false;
                        toggleEPFFields();
                        epfCheckbox.dispatchEvent(new Event("change"));
                    }
                }
                if (type === "esic" && (!response.comp_esic || response.comp_esic === "")) {
                    showCompanyPolicyPopup("esic");
                    const esicCheckbox = document.getElementById("esic_check");
                    if (esicCheckbox) {
                        esicCheckbox.checked = false;
                        toggleESICFields();
                        const esiInput = document.getElementById("esi");
                        if (esiInput) {
                            esiInput.value = "00.00";
                        }
                        esicCheckbox.dispatchEvent(new Event("change"));
                    }
                }
                if (type === "ptax" && (!response.comp_ptax || response.comp_ptax === "")) {
                    showCompanyPolicyPopup("ptax");
                    const ptaxCheckbox = document.getElementById("ptax_check");
                    if (ptaxCheckbox) {
                        ptaxCheckbox.checked = false;
                        const ptaxInput = document.getElementById("ptax");
                        if (ptaxInput) {
                            ptaxInput.value = "00.00";
                        }
                        ptaxCheckbox.dispatchEvent(new Event("change"));
                    }
                }
                if (type === "tds" && (!response.comp_tan || response.comp_tan === "")) {
                    showCompanyPolicyPopup("tds");
                    const tdsCheckbox = document.getElementById("tds_check");
                    if (tdsCheckbox) {
                        tdsCheckbox.checked = false;
                        const tdsInput = document.getElementById("tds");
                        if (tdsInput) {
                            tdsInput.value = "00.00";
                        }
                        tdsCheckbox.dispatchEvent(new Event("change"));
                    }
                }
            }
        });
    }

    // Attach to checkbox change events
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("epf_check").addEventListener("change", function () {
            if (this.checked) {
                checkCompanyPolicy("epf");
            }
        });
        document.getElementById("esic_check").addEventListener("change", function () {
            if (this.checked) {
                checkCompanyPolicy("esic");
            }
        });
        document.getElementById("ptax_check").addEventListener("change", function () {
            if (this.checked) {
                checkCompanyPolicy("ptax");
            }
        });
        document.getElementById("tds_check").addEventListener("change", function () {
            if (this.checked) {
                checkCompanyPolicy("tds");
            }
        });
    });
	
	$(document).on('change', 'input[name="emp_permission[]"]', function () {

        let all = $('input[name="emp_permission[]"][value="All"]');
        let noAccess = $('input[name="emp_permission[]"][value="No Access"]');
        let others = $('input[name="emp_permission[]"]').not(all).not(noAccess);

        // ALL checked
        if ($(this).val() === "All") {

            if ($(this).is(':checked')) {
                others.prop('checked', true);
                noAccess.prop('checked', false);
            } else {
                others.prop('checked', false);
            }

            return;
        }

        // NO ACCESS checked
        if ($(this).val() === "No Access") {

            if ($(this).is(':checked')) {
                $('input[name="emp_permission[]"]')
                    .not(this)
                    .prop('checked', false);
            }

            return;
        }

        // Any normal menu checked
        if ($(this).is(':checked')) {
            noAccess.prop('checked', false);
        }

        // If any normal menu is unchecked, uncheck ALL
        if (!$(this).is(':checked')) {
            all.prop('checked', false);
        }

        // If every normal menu is checked, automatically check ALL
        if (others.length === others.filter(':checked').length) {
            all.prop('checked', true);
        }
    });

    function startAddEmployeeTour() {
        if (typeof introJs !== 'function') return;

        let tour = introJs().setOptions({
            steps: [
                {
                    title: 'Add Employee Wizard Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-user-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Follow this interactive guide to fill in all personal, address, payroll, banking, attachment, and permission settings for a new employee.</p></div>'
                },
                {
                    element: 'a[href="#personalDetail"]',
                    title: 'Personal Details Tab',
                    intro: 'This tab holds basic employee details (Name, Contact No, DOB, Aadhaar, and PAN).'
                },
                {
                    element: '#name',
                    title: 'Employee Name',
                    intro: 'Enter the official name of the employee.'
                },
                {
                    element: 'a[href="#address"]',
                    title: 'Address & References Tab',
                    intro: 'Capture both Current and Permanent addresses, and details for emergency contacts.'
                },
                {
                    element: '#copy-permanent-address-btn',
                    title: 'Same as Current Address',
                    intro: 'If the permanent address is identical to the current address, click here to copy it.'
                },
                {
                    element: 'a[href="#jobDetails"]',
                    title: 'Payroll Details Tab',
                    intro: 'Assign departments, designations, joining dates, EPF/ESIC statutory compliance status, and Gross Salary calculations.'
                },
                {
                    element: '#total_addition',
                    title: 'Gross Salary',
                    intro: 'Enter the monthly Gross Salary amount. Allowances and deductions will calculate automatically.'
                },
                {
                    element: 'a[href="#bankDetails"]',
                    title: 'Bank Details Tab',
                    intro: 'Register the employee\'s official bank name, account number, branch, and IFSC code for salary payouts.'
                },
                {
                    element: 'a[href="#attachments"]',
                    title: 'Attachments Tab',
                    intro: 'Upload scans of Aadhar, PAN, CV, educational certificates, and experience letters.'
                },
                {
                    element: 'a[href="#access"]',
                    title: 'Access Tab',
                    intro: 'Set up login credentials (email, password) and assign specific portal module access permissions.'
                },
                {
                    element: '#submitBtn',
                    title: 'Submit Details',
                    intro: 'Once all details are fully completed, click here to save the employee profile.'
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

            // Find the closest tab-pane containing the target element
            let tabPane = targetElement.closest('.tab-pane');
            if (tabPane) {
                let tabId = tabPane.getAttribute('id');
                let tabTrigger = document.querySelector(`a[href="#${tabId}"]`);
                if (tabTrigger && !tabTrigger.classList.contains('active')) {
                    let tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            } else if (targetElement.getAttribute('href') && targetElement.getAttribute('data-bs-toggle') === 'tab') {
                // If the target element is the tab trigger itself
                let tab = new bootstrap.Tab(targetElement);
                tab.show();
            }
        });

        tour.start();
    }

    $(document).ready(function() {
        $('#start-add-employee-tour').on('click', function(e) {
            e.preventDefault();
            startAddEmployeeTour();
        });
    });
    
</script>
@endsection