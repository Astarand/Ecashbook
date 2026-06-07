@extends('App.Layout')

@section('container')

<div class="pc-content">
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
                                <span class="d-none d-sm-inline">Address</span>
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
                    <form action="javascript:void(0);" method="post" name="addUserEmployee" id="addUserEmployee" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="empId" value="{{ $employee->id }}">
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
                                                    <input type="file" id="fileUpload" class="d-none" name="fileUpload" accept="image/*">
                                                </div>

                                                @php
                                                    $profileImg = $employee->profile_img
                                                        ? asset('storage/user_employee/' . $employee->profile_img)
                                                        : asset('storage/profile/e-cashbook.png');
                                                @endphp

                                                <img src="{{ $profileImg }}" alt="user-image" id="uploadedImage" class="wid-150 rounded img-fluid ms-2">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="name">Employee Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required value="{{ $employee->name }}" name="name" id="name" placeholder="Employee Name">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="phone">Contact Number <span class="text-danger">*</span></label>
                                                    <input required type="text" name="phone" id="phone" value="{{ $employee->phone }}" class="form-control" placeholder="Enter Contact Number" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="email">Email Address<span class="text-danger">*</span></label>
                                                    <input required type="email" name="email" id="email" value="{{ $employee->email_id }}" class="form-control" placeholder="Enter Email Address">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="dob">Date of Birth<span class="text-danger">*</span></label>
                                                    <input required type="date" name="dob" id="dob" value="{{ $employee->dob }}" class="form-control" onfocus="this.max = new Date(Date.now() - 86400000).toISOString().split('T')[0]">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="gender">Gender<span class="text-danger">*</span></label>
                                                    <div class="form-group me-2">
                                                        <select class="select form-select" name="gender" id="gender" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                            <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Aadhaar No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="aadhaar_number"
                                                        id="aadhaar_number" placeholder="Enter Aadhaar Number" maxlength="12" value="{{ $employee->aadhaar_number }}" required>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">PAN No<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="pan_number" required
                                                        id="pan_number" placeholder="Enter PAN Number" maxlength="10" value="{{ $employee->pan_number }}">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="qualification">Highest Qualification<span class="text-danger">*</span></label>
                                                    <input type="text" required class="form-control" name="qualification" id="qualification" value="{{ $employee->qualification }}" placeholder="Enter highest qualification">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="alt_phone">Alternate Mobile No <span class="text-danger">*</span></label>
                                                    <input type="text" required name="alt_phone" id="alt_phone" value="{{ $employee->alt_phone }}" class="form-control"
                                                        placeholder="Enter alternate mobile number" maxlength="10"
                                                        oninput="this.value = this.value.replace(/\D/g, '');">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                <label class="form-label" for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                                <select class="form-select" name="marital_status" id="marital_status" required>
                                                    <option value="">Select Marital Status</option>
                                                    <option value="Single"   {{ ($employee->marital_status ?? '') === 'Single'   ? 'selected' : '' }}>Single</option>
                                                    <option value="Married"  {{ ($employee->marital_status ?? '') === 'Married'  ? 'selected' : '' }}>Married</option>
                                                    <option value="Divorced" {{ ($employee->marital_status ?? '') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    <option value="Widowed"  {{ ($employee->marital_status ?? '') === 'Widowed'  ? 'selected' : '' }}>Widowed</option>
                                                </select>
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                <label class="form-label" for="pro_qualification">Professional Qualification (if any)</label>
                                                <input type="text" class="form-control" name="pro_qualification" id="pro_qualification"
                                                        value="{{ $employee->pro_qualification ?? '' }}" placeholder="Enter professional qualification">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                <label class="form-label" for="last_employer">Last Employer Name</label>
                                                <input type="text" class="form-control" name="last_employer" id="last_employer"
                                                        value="{{ $employee->last_employer ?? '' }}" placeholder="Enter last employer name">
                                                </div>

                                                <div class="mb-3 col-md-4">
                                                <label class="form-label" for="experience_years">Year of Work Experience</label>
                                                <input type="number" class="form-control" name="experience_years" id="experience_years"
                                                        value="{{ $employee->experience_years ?? '' }}" min="0" step="0.5" placeholder="Enter years of experience">
                                                </div>



                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-end mt-3">
                                    <div class="last">
                                        <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                            Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- end contact detail tab pane -->
                            <div class=" tab-pane" id="address">
                                <div class="">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                                                <h5>Permanent Address</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                                        <input required type="text" name="c_addr_lineone" id="cust_bill_addone" class="form-control"
                                                            placeholder="Enter Address Line 1" value="{{ $employee->c_addr_lineone ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="c_addr_linetwo" id="cust_bill_addtwo" class="form-control"
                                                            placeholder="Enter Address Line 2" value="{{ $employee->c_addr_linetwo ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State<span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="c_emp_state" id="state" required>
                                                            <option value="">Select State</option>
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}" {{ $state->id == $employee->c_emp_state ? 'selected' : '' }}>
                                                                    {{ $state->name }}
                                                                </option>
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
                                                        <label class="form-label">Zip Code<span class="text-danger">*</span></label>
                                                        <input required type="text" name="c_emp_pincode" id="cust_bill_pin" class="form-control"
                                                            placeholder="Enter Zip Code" value="{{ $employee->c_emp_pincode ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-lg-6 col-sm-12">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5>Current Address</h5>
                                                <div class="btn btn-primary" onclick="copyParamanentAddress()">Same as Permanent Address</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                                        <input required type="text" name="p_addr_lineone" id="cust_ship_addone"
                                                               class="form-control" placeholder="Enter Address Line 1"
                                                               value="{{ $employee->p_addr_lineone ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="p_addr_linetwo" id="cust_ship_addtwo"
                                                               class="form-control" placeholder="Enter Address Line 2"
                                                               value="{{ $employee->p_addr_linetwo ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State<span class="text-danger">*</span></label>
                                                        <select required class="form-control select-style" name="p_emp_state" id="curr_state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}"
                                                                        {{ $state->id == ($employee->p_emp_state ?? '') ? 'selected' : '' }}>
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City<span class="text-danger">*</span></label>
                                                        <select required class="form-control select-style" name="p_emp_city" id="curr_city">
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label @required(true) class="form-label">Zip Code<span class="text-danger">*</span></label>
                                                        <input type="text" name="p_emp_pincode" id="cust_ship_pin"
                                                               class="form-control" placeholder="Enter Zip Code"
                                                               value="{{ $employee->p_emp_pincode ?? '' }}">
                                                    </div>
                                                </div>
                                                </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <h5>Reference & Emergency Contact Details</h5>
                                                        <hr>
                                                    </div>

                                                    <!-- Reference 1 -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                        <label class="form-label">Reference 1 Name<span class="text-danger">*</span></label>
                                                        <input type="text" required name="ref1_name" id="ref1_name" class="form-control"
                                                                value="{{ $employee->ref1_name ?? '' }}" placeholder="Enter first reference name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                        <label class="form-label">Reference 1 Mobile No<span class="text-danger">*</span></label>
                                                        <input type="text" required name="ref1_mobile" id="ref1_mobile" class="form-control"
                                                                value="{{ $employee->ref1_mobile ?? '' }}" placeholder="Enter mobile number" maxlength="10"
                                                                oninput="this.value = this.value.replace(/\D/g, '');">
                                                        </div>
                                                    </div>

                                                    <!-- Reference 2 -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                        <label class="form-label">Reference 2 Name</label>
                                                        <input type="text" name="ref2_name" id="ref2_name" class="form-control"
                                                                value="{{ $employee->ref2_name ?? '' }}" placeholder="Enter second reference name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                        <label class="form-label">Reference 2 Mobile No</label>
                                                        <input type="text" name="ref2_mobile" id="ref2_mobile" class="form-control"
                                                                value="{{ $employee->ref2_mobile ?? '' }}" placeholder="Enter mobile number" maxlength="10"
                                                                oninput="this.value = this.value.replace(/\D/g, '');">
                                                        </div>
                                                    </div>

                                                    <!-- Emergency Contact -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                        <label class="form-label">Emergency Contact Person Name<span class="text-danger">*</span></label>
                                                        <input type="text" required name="emergency_name" id="emergency_name" class="form-control"
                                                                value="{{ $employee->emergency_name ?? '' }}" placeholder="Enter emergency contact person name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                        <label class="form-label">Emergency Contact Mobile No<<span class="text-danger">*</span>/label>
                                                        <input type="text" required name="emergency_mobile" id="emergency_mobile" class="form-control"
                                                                value="{{ $employee->emergency_mobile ?? '' }}" placeholder="Enter mobile number" maxlength="10"
                                                                oninput="this.value = this.value.replace(/\D/g, '');">
                                                        </div>
                                                    </div>
                                                    </div>




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


                            <!-- end job detail tab pane -->
                            <div class="tab-pane" id="jobDetails">
                                <div class="">
                                    <div class="row">
                                        <!-- Department -->
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="dept_id">Department<span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group me-2" style="flex-grow: 1;">
                                                    <select class="select form-select" name="dept_id" id="dept_id" onchange="getDesignationsByDept(this.value);">
                                                        <option value="">Select Department</option>
                                                        <!-- Departments loaded by JS -->
                                                    </select>
                                                </div>
                                                <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="#" data-bs-toggle="modal" data-bs-target="#depertment">
                                                    <i class="ti ti-plus py-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Designation -->
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="designation_id">Designation<span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group me-2" style="flex-grow: 1;">
                                                    <select class="select form-select" name="designation_id" id="designation_id">
                                                        <option value="">Select Designation</option>
                                                        <!-- Designations loaded by JS -->
                                                    </select>
                                                </div>
                                                <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="#" data-bs-toggle="modal" data-bs-target="#designation">
                                                    <i class="ti ti-plus py-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <!-- Location -->
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="location_id">Company Location<span class="text-danger">*</span></label>
                                            <select class="select form-select" name="location_id" id="location_id">
                                                <option value="">Select Location</option>
                                                @foreach($locations as $location)
                                                    <option value="{{ $location->id }}" {{ ($employee->location_id ?? '') == $location->id ? 'selected' : '' }}>
                                                        {{ $location->location_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Joining Date -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="emp_joining_date">Joining Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="emp_joining_date" id="emp_joining_date" value="{{ $employee->joining_date ?? '' }}" />
                                            </div>
                                        </div>
                                        <!-- Work Location -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="work_location">Work Location<span class="text-danger">*</span></label>
                                                <select class="form-control" name="work_location" id="work_location">
                                                    <option value="">Select Work Location</option>
                                                    <option value="work_from_home" {{ ($employee->work_location ?? '') == 'work_from_home' ? 'selected' : '' }}>Work From Home</option>
                                                    <option value="work_from_office" {{ ($employee->work_location ?? '') == 'work_from_office' ? 'selected' : '' }}>Work From Office</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Employee Status -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="emp_status">Employee Status<span class="text-danger">*</span></label>
                                                <select class="form-select" name="emp_status" id="emp_status" onchange="toggleStatusDate();">
                                                    <option value="In Probation" {{ ($employee->emp_status ?? '') == 'In Probation' ? 'selected' : '' }}>In Probation</option>
                                                    <option value="Confirmed" {{ ($employee->emp_status ?? '') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                    <option value="Terminated" {{ ($employee->emp_status ?? '') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                                    <option value="Resigned" {{ ($employee->emp_status ?? '') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Status Date -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="status_date" id="status_date_label">
                                                    Date (Confirmation / Termination / Resignation)<span class="text-danger">*</span>
                                                </label>
                                                <input type="date" class="form-control" name="status_date" id="status_date"
                                                    value="{{ $employee->regine_date ?? '' }}" placeholder="Select relevant date">
                                            </div>
                                        </div>
                                        <!-- Employee Type -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="emp_type">Employee Type<span class="text-danger">*</span></label>
                                                <select class="form-select" name="emp_type" id="emp_type">
                                                    <option value="">Select Employee Type</option>
                                                    <option value="Full Time" {{ ($employee->emp_type ?? '') == 'Full Time' ? 'selected' : '' }}>Full Time</option>
                                                    <option value="Part Time" {{ ($employee->emp_type ?? '') == 'Part Time' ? 'selected' : '' }}>Part Time</option>
                                                    <option value="Contract" {{ ($employee->emp_type ?? '') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                                    <option value="Temporary" {{ ($employee->emp_type ?? '') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Statutory Applicability -->
                                        <div class="col-12 mt-3">
                                            <h5>Statutory Applicability</h5>
                                            <hr>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="epf_check" name="epf_applicable" value="1"
                                                    {{ ($employee->epf_applicable ?? 0) == 1 ? 'checked' : '' }} onchange="toggleEPFFields()">
                                                <label class="form-check-label" for="epf_check">EPF Applicable</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="esic_check" name="esic_applicable" value="1"
                                                    {{ ($employee->esic_applicable ?? 0) == 1 ? 'checked' : '' }} onchange="toggleESICFields()">
                                                <label class="form-check-label" for="esic_check">ESIC Applicable</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="ptax_check" name="ptax_applicable" value="1"
                                                    {{ ($employee->ptax_applicable ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ptax_check">P-Tax Applicable</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="tds_check" name="tds_applicable" value="1"
                                                    {{ ($employee->tds_applicable ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tds_check">TDS Applicable</label>
                                            </div>
                                        </div>
                                        <!-- Conditional EPF & ESIC Fields -->
                                        <div class="col-md-6" id="epf_no_field" style="display: {{ ($employee->epf_applicable ?? 0) == 1 ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label class="form-label" for="epf_no">Employee EPF No</label>
                                                <input type="text" name="epf_no" id="epf_no" class="form-control"
                                                    value="{{ $employee->epf_no ?? '' }}" placeholder="Enter EPF Number">
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="esic_no_field" style="display: {{ ($employee->esic_applicable ?? 0) == 1 ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label class="form-label" for="esic_no">Employee ESIC No</label>
                                                <input type="text" name="esic_no" id="esic_no" class="form-control"
                                                    value="{{ $employee->esic_no ?? '' }}" placeholder="Enter ESIC Number">
                                            </div>
                                        </div>

                                        <!-- Earnings -->
                                        <h6 class="mb-3">Earnings</h6>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="total_addition">Gross Salary <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="total_addition" id="total_addition" value="{{ $employee->total_addition ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic_percentage">Basic Salary Percentage<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="basic_percentage" value="{{ $employee->basic_percentage ?? '40' }}"
                                                    id="basic_percentage" placeholder="Enter percentage"/>
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic_sal">Basic Salary <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" required name="basic_sal" id="basic_sal" value="{{ $employee->basic_sal ?? '' }}" 
                                                    placeholder="Auto-calculated based on percentage" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                                <small class="form-text text-muted">Auto-calculated based on percentage (editable)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="hra">House Rent Allowance (HRA) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" required name="hra" id="hra" value="{{ $employee->hra ?? '' }}" 
                                                    placeholder="Auto: 50% of Basic" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                                <small class="form-text text-muted">Auto-calculated as 50% of Basic Salary (editable)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="convayance">Conveyance Allowance<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" required name="convayance" id="convayance" readonly value="{{ $employee->convayance ?? '1600' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="medical_allowance">Medical Allowance</label>
                                                <input type="text" class="form-control" name="medical_allowance" id="medical_allowance" value="{{ $employee->medical_allowance ?? '1250' }}" readonly required />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="special_bonus">Special Allowance <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="special_bonus" id="special_bonus" value="{{ $employee->special_bonus ?? '0' }}" 
                                                    placeholder="Extra adjustable amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                                <small class="form-text text-muted">Adjustable extra allowance amount</small>
                                            </div>
                                        </div>

                                        <!-- Deductions -->
                                        <h6 class="mb-3">Deductions</h6>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="provident_fund">Provident Fund (PF)<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="provident_fund" id="provident_fund" value="{{ $employee->provident_fund ?? '0' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="esi">Employee State Insurance (ESI) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="esi" id="esi" value="{{ $employee->esi ?? '0' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="loan">Total Loan Amount <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="loan" id="loan" value="{{ $employee->loan ?? '0' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="loan_tenure">Tenure for Repay (in Months)</label>
                                                <input type="text" class="form-control" name="loan_tenure" id="loan_tenure" value="{{ $employee->loan_tenure ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="loan_deduction">Loan Deduction (Monthly)</label>
                                                <input type="text" class="form-control" name="loan_deduction" id="loan_deduction" value="{{ $employee->loan_deduction ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="ptax">Professional Tax (PT) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="ptax" id="ptax" value="{{ $employee->ptax ?? '0' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="tds">Tax Deducted at Source (TDS)<span class="text-danger">*</span></label>
                                                <input type="text" required class="form-control" name="tds" id="tds" value="{{ $employee->tds ?? '0' }}">
                                                <small class="form-text text-muted">
                                                    <i class="fas fa-info-circle"></i> 
                                                    TDS shows ₹0 for salary ≤ ₹10,00,000 and auto-calculates for salary > ₹10,00,000
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="total_deduction">Total Deduction <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="total_deduction" id="total_deduction" value="{{ $employee->total_deduction ?? '0' }}" readonly>
                                                <small class="form-text text-muted">Auto-calculated: PF + ESI + PTAX + TDS + Loan Deduction</small>
                                            </div>
                                        </div>

                                        <!-- Salary Calculation -->
                                        <h6 class="mb-3">Total Salary Calculation</h6>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="net_sal">Net Salary <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="net_sal" id="net_sal" value="{{ $employee->net_sal ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="net_sal_word">Net Salary in Words <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="net_sal_word" id="net_sal_word" value="{{ $employee->net_sal_word ?? '' }}">
                                            </div>
                                        </div>
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

                            <div class="tab-pane" id="bankDetails">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="bank_name">Bank Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  name="bank_name" id="bank_name" placeholder="Enter Bank Name" value="{{ $employee->bank_name ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="bank_branch">Bank Branch <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_branch" id="bank_branch" placeholder="Enter Bank Branch" value="{{ $employee->bank_branch ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="ifsc">IFSC Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  name="ifsc" id="ifsc" placeholder="Enter IFSC Code" value="{{ $employee->ifsc ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="swift_code">Swift Code </label>
                                            <input type="text" class="form-control"  name="swift_code" id="swift_code" placeholder="Enter Swift Code" value="{{ $employee->swift_code ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="account_holder_name">Account Holder Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" value="{{ $employee->account_holder_name ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="account_number">Account Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  name="account_number" id="account_number" placeholder="Enter Account Number" value="{{ $employee->account_number ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="confirm_account_no">Confirm Account Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  name="confirm_account_no" id="confirm_account_no" placeholder="Enter Confirm Account Number" value="{{ $employee->account_number ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="upi_id">UPI / VPA No</label>
                                            <input type="text" class="form-control"  name="upi_id" id="upi_id" placeholder="Enter UPI / VPA No." value="{{ $employee->upi_id ?? '' }}">
                                        </div>
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
                            <!-- end education detail tab pane -->
                            <div class="tab-pane" id="attachments">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Aadhar Card <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="aadhar_attachment" accept="application/pdf,image/*">
                                        @if(!empty($employee->aadhar_doc))
                                            <a href="{{ asset('storage/employee_document/'.$employee->aadhar_doc) }}" target="_blank">View existing Aadhar</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PAN Card <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="pan_attachment" accept="application/pdf,image/*">
                                        @if(!empty($employee->pan_doc))
                                            <a href="{{ asset('storage/employee_document/'.$employee->pan_doc) }}" target="_blank">View existing PAN</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bank Passbook / Cheque <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="bank_passbook_attachment" accept="application/pdf,image/*">
                                        @if(!empty($employee->cancelled_cheque_doc))
                                            <a href="{{ asset('storage/employee_document/'.$employee->cancelled_cheque_doc) }}" target="_blank">View existing Bank/Cheque</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">CV <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="cv_attachment" accept=".pdf,.doc,.docx">
                                        @if(!empty($employee->cv_doc))
                                            <a href="{{ asset('storage/employee_document/'.$employee->cv_doc) }}" target="_blank">View existing CV</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Qualification Certificate <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="certificate_attachment" accept="application/pdf,image/*">
                                        @if(!empty($employee->last_qualification_doc))
                                            <a href="{{ asset('storage/employee_document/'.$employee->last_qualification_doc) }}" target="_blank">View existing Certificate</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Experience Letter</label>
                                        <input type="file" class="form-control" name="experience_letter" accept="application/pdf,image/*">
                                        @if(!empty($employee->experience_letter))
                                            <a href="{{ asset('storage/employee_document/'.$employee->experience_letter) }}" target="_blank">View existing Experience Letter</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Offer Letter</label>
                                        <input type="file" class="form-control" name="offer_letter" accept="application/pdf,image/*">
                                        @if(!empty($employee->offer_letter))
                                            <a href="{{ asset('storage/employee_document/'.$employee->offer_letter) }}" target="_blank">View existing Offer Letter</a>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Other Document</label>
                                        <input type="file" class="form-control" name="other_doc" accept="application/pdf,image/*, .doc, .docx">
                                        @if(!empty($employee->other_doc))
                                            <div><a href="{{ asset('storage/employee_document/'.$employee->other_doc) }}" target="_blank">View existing Other Document</a></div>
                                        @endif
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
                                        <label class="form-label">Generate Login Email Address<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" disabled name="login_email" id="login_email" placeholder="Enter Email" value="{{ $employee->email ?? '' }}">
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputPassword4">Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" disabled name="password" placeholder="Enter Password" value="{{ $employee->password ?? '' }}">
                                            <div class="input-group-text togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputPassword4">Confirm Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_pwd" disabled name="confirm_pwd" placeholder="Confirm Password" value="{{ $employee->password ?? '' }}">
                                            <div class="input-group-text togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>
									@if(Auth::user()->u_type == 1 || Auth::user()->u_type == 4)
                                    <div class="mb-3 row">
                                        <div class="col-sm-12">
                                            <!-- Flexbox container for checkboxes -->
                                            <div class="d-flex flex-wrap justify-content-start">
                                                <!-- Checkbox Group 1 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="client_management" name="emp_permission[]"
                                                            id="customCheckinlh0">
                                                        <label class="form-check-label" for="customCheckinlh0">Client Management</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 1 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="company_assignment" name="emp_permission[]"
                                                            id="customCheckinlh1">
                                                        <label class="form-check-label"
                                                            for="customCheckinlh1">Company Assignment</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 2 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="compliances_management"
                                                            name="emp_permission[]" id="customCheckinlh2">
                                                        <label class="form-check-label" for="customCheckinlh2">Compliances Management</label>
                                                    </div>
                                                </div>
												
												<div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="HR & Payroll Management"
                                                            name="emp_permission[]" id="customCheckinlh3">
                                                        <label class="form-check-label" for="customCheckinlh3">HR & Payroll Management</label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
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
                                            </div>
                                        </div>
                                    </div>	
									@endif
                                    <!-- end col -->
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                        <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <button type='submit' id="" class="btn btn-primary d-flex align-items-center justify-content-center">
                                            Save changes <i class="ti ti-arrow-up-right-circle ms-2"></i>
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
                                <input type="text" name="dept_name" id="dept_name" class="form-control" placeholder="Enter Department">
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
            <form action="javascript:void(0);" method="post" name="addAdminDesignationFrm" id="addAdminDesignationFrm" novalidate="novalidate">
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
                                <input type="text" name="designation_name" id="designation_name" class="form-control" placeholder="Enter Designation category" required>
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

<script>

	//fetch TDS
	let TDS_SLABS = [];
	fetch('/tds-slabs')
		.then(res => res.json())
		.then(data => {
			TDS_SLABS = data;
		});
		
function toggleStatusDate() {
    const status = document.getElementById('emp_status').value;
    const label = document.getElementById('status_date_label');
    if (status === 'Confirmed') {
        label.textContent = 'Confirmation Date';
    } else if (status === 'Terminated') {
        label.textContent = 'Termination Date';
    } else if (status === 'Resigned') {
        label.textContent = 'Resignation Date';
    } else {
        label.textContent = 'Date (Confirmation / Termination / Resignation)';
    }
}

function toggleEPFFields() {
    const epfCheck = document.getElementById('epf_check');
    const epfField = document.getElementById('epf_no_field');
    epfField.style.display = epfCheck.checked ? 'block' : 'none';
    document.getElementById('epf_no').disabled = !epfCheck.checked;
    document.getElementById('provident_fund').disabled = !epfCheck.checked;
    if (!epfCheck.checked) {
        document.getElementById('epf_no').value = '';
        document.getElementById('provident_fund').value = '00.00';
    }
}

function toggleESICFields() {
    const esicCheck = document.getElementById('esic_check');
    const esicField = document.getElementById('esic_no_field');
    esicField.style.display = esicCheck.checked ? 'block' : 'none';
    document.getElementById('esic_no').disabled = !esicCheck.checked;
    document.getElementById('esi').disabled = !esicCheck.checked;
    if (!esicCheck.checked) {
        document.getElementById('esic_no').value = '';
        document.getElementById('esi').value = '00.00';
    }
}

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
    // Initial show/hide for EPF/ESIC fields
    toggleEPFFields();
    toggleESICFields();
    toggleStatusDate();

    document.getElementById('epf_check').addEventListener('change', toggleEPFFields);
    document.getElementById('esic_check').addEventListener('change', toggleESICFields);
    document.getElementById('emp_status').addEventListener('change', toggleStatusDate);

    // Salary calculation logic
    const grossInput = document.getElementById("total_addition");
    const basicPercentageInput = document.getElementById("basic_percentage");
    const basicInput = document.getElementById("basic_sal");
    const hraInput = document.getElementById("hra");
    const medicalInput = document.getElementById("medical_allowance");
    const convayanceInput = document.getElementById("convayance");
    const specialInput = document.getElementById("special_bonus");

    const pfInput = document.getElementById("provident_fund");
    const esiInput = document.getElementById("esi");
    const ptaxInput = document.getElementById("ptax");
    const tdsInput = document.getElementById("tds");
    const loanInput = document.getElementById("loan_deduction");

    const totalDeductionInput = document.getElementById("total_deduction");
    const netSalaryInput = document.getElementById("net_sal");
    const netSalaryWordInput = document.getElementById("net_sal_word");

    function calculateAll() {
        const gross = parseFloat(grossInput.value) || 0;
        const basicPercentage = parseFloat(basicPercentageInput.value) || 50;

        // Basic Salary calculation - check if user has manually entered a value
        let basic = parseFloat(basicInput.value) || 0;
        let basicIsManual = basicInput.dataset.manual === 'true';
        
        if (!basicIsManual && gross > 0) {
            basic = (gross * basicPercentage) / 100; // Calculate based on percentage
        }
        
        // HRA calculation - check if user has manually entered a value
        let hra = parseFloat(hraInput.value) || 0;
        let hraIsManual = hraInput.dataset.manual === 'true';
        
        if (!hraIsManual && basic > 0) {
            hra = basic * 0.50; // Auto: 50% of basic
        }
        
        const medical = gross > 0 ? 1250 : 0;
        const convayance = gross > 0 ? 1600 : 0;
        
        // Special Allowance - check if user has manually entered a value
        let special = parseFloat(specialInput.value) || 0;
        let specialIsManual = specialInput.dataset.manual === 'true';
        
        if (!specialIsManual && gross > 0) {
            special = gross - (basic + hra + medical + convayance);
            if (special < 0) special = 0;
        }

        // Update values
        if (!basicIsManual) {
            basicInput.value = basic.toFixed(2);
        }
        
        if (!hraIsManual) {
            hraInput.value = hra.toFixed(2);
        }
        
        medicalInput.value = medical.toFixed(2);
        convayanceInput.value = convayance.toFixed(2);
        
        if (!specialIsManual) {
            specialInput.value = special.toFixed(2);
        }

        // Deductions
        let pf = 0, esi = 0, ptax = 0, tds = 0;
        let loanDeduction = parseFloat(loanInput.value) || 0;

        // PF Calculation
        if (document.getElementById('epf_check').checked && basic > 0) {
            pf = basic * 0.12;
            // PF maximum limit
            if (pf > 1800) {
                pf = 1800;
            }
            pfInput.value = pf.toFixed(2);
        } else {
            pfInput.value = "00.00";
        }

        // ESI Calculation
        if (document.getElementById('esic_check').checked && gross > 0) {
            esi = gross <= 21000 ? gross * 0.0075 : 0;
            esiInput.value = esi.toFixed(2);
        } else {
            esiInput.value = "00.00";
        }

        // PTAX Calculation (as per slab)
        if (document.getElementById('ptax_check').checked && gross > 0) {
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
        /*if (document.getElementById('tds_check').checked) {
            tds = parseFloat(tdsInput.value) || 0;
            tdsInput.value = tds.toFixed(2);
        } else {
            tdsInput.value = "00.00";
        }*/
		// TDS Calculation (Auto from tds_salary_slabs Slabs)
		if (document.getElementById('tds_check').checked && gross > 0 && TDS_SLABS.length) {
			tds = calculateMonthlyTdsFromSlabs(gross);
			tdsInput.value = tds.toFixed(2);
		} else {
			tds = 0;
			tdsInput.value = "00.00";
		}

        // Total Deduction
        const totalDeduction = pf + esi + ptax + tds + loanDeduction;
        totalDeductionInput.value = totalDeduction.toFixed(2);

        // Net Salary
        const netSalary = gross - totalDeduction;
        netSalaryInput.value = netSalary.toFixed(2);

        // Net Salary in Word
        netSalaryWordInput.value = numberToWords(netSalary);
    }

    // Initial calculation
    calculateAll();

    // Listen for checkbox changes
    ['epf_check', 'esic_check', 'ptax_check', 'tds_check'].forEach(id => {
        document.getElementById(id).addEventListener("change", calculateAll);
    });

    // Listen for field changes
    [grossInput, basicPercentageInput, loanInput, tdsInput].forEach(input => {
        input.addEventListener("input", calculateAll);
    });

    // Special handling for Basic Salary, HRA and Special Allowance to track manual edits
    basicInput.addEventListener("input", function () {
        this.dataset.manual = 'true';
        // When basic is manually changed, recalculate HRA and Special Allowance
        hraInput.dataset.manual = 'false';
        specialInput.dataset.manual = 'false';
        calculateAll();
    });

    hraInput.addEventListener("input", function () {
        this.dataset.manual = 'true';
        // When HRA is manually changed, recalculate Special Allowance
        specialInput.dataset.manual = 'false';
        calculateAll();
    });

    specialInput.addEventListener("input", function () {
        this.dataset.manual = 'true';
        calculateAll();
    });

    // Reset manual flags when gross salary or percentage changes
    grossInput.addEventListener("input", function () {
        basicInput.dataset.manual = 'false';
        hraInput.dataset.manual = 'false';
        specialInput.dataset.manual = 'false';
        calculateAll();
    });

    // Reset manual flags when percentage changes
    basicPercentageInput.addEventListener("input", function () {
        basicInput.dataset.manual = 'false';
        hraInput.dataset.manual = 'false';
        specialInput.dataset.manual = 'false';
        calculateAll();
    });
});
</script>




<script>

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
        const esiInput = document.getElementById("esi");
        const ptaxInput = document.getElementById("ptax");
        const tdsInput = document.getElementById("tds");
        const loanInput = document.getElementById("loan_deduction");

        const totalDeductionInput = document.getElementById("total_deduction");
        const netSalaryInput = document.getElementById("net_sal");
        const netSalaryWordInput = document.getElementById("net_sal_word");

        function calculateAll() {
            const gross = parseFloat(grossInput.value) || 0;
            const basic = parseFloat(basicInput.value) || 0;
            let tds = parseFloat(tdsInput.value) || 0;
            const loan = parseFloat(loanInput.value) || 0;

            const tdsDiductAmount = 1000000; // 10 lakh as number
            // TDS Auto-calculation for salary > 10 lakh, otherwise 00.00
            if (basic > tdsDiductAmount) {
                // Call AJAX to get TDS calculation from server
                $.ajax({
                    url: "{{ route('user.calculate_tds') }}",
                    type: "POST",
                    data: {
                        basic_salary: basic,
                        tdsDiductAmount: tdsDiductAmount,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            tdsInput.value = response.tds_amount.toFixed(2);
                            tdsInput.readOnly = true;
                            tdsInput.disabled = false;
                            tdsInput.style.backgroundColor = '#f8f9fa';
                            tdsInput.title = response.message;
                        }
                    },
                    error: function() {
                        const tds = basic * 0.10; // fallback 10%
                        tdsInput.value = tds.toFixed(2);
                        tdsInput.readOnly = true;
                        tdsInput.disabled = false;
                        tdsInput.style.backgroundColor = '#f8f9fa';
                        tdsInput.title = 'TDS auto-calculated (fallback)';
                    }
                });
            } else {
                // salary <= limit: enable manual TDS entry
                tdsInput.readOnly = false;
                tdsInput.disabled = false;
                tdsInput.style.backgroundColor = ''; 
                tdsInput.title = 'Enter TDS amount manually (basic salary below ₹10,00,000)';
                // Keep existing value or set to 0 if empty
                if (!tdsInput.value || tdsInput.value === "0.00") {
                    tdsInput.value = "0";
                }
            }
			
			// TDS Calculation (Auto from tds_salary_slabs Slabs)
			if (document.getElementById('tds_check').checked && gross > 0 && TDS_SLABS.length) {
				tdsInput.readOnly = true;
				tds = calculateMonthlyTdsFromSlabs(gross);
				tdsInput.value = tds.toFixed(2);
			} else {
				tds = 0;
				tdsInput.value = "00.00";
				tdsInput.readOnly = true;
			}
        }

        function continueCalculation(gross, basic, tds, loan) {
            // PF = 12% of Basic
            const pf = basic * 0.12;

            // ESI = 0.75% if Gross <= 21000
            const esi = gross <= 21000 ? gross * 0.0075 : 0;

            // PTAX based on slab
            let ptax = 0;
            if (gross > 10000 && gross <= 15000) ptax = 110;
            else if (gross > 15000 && gross <= 25000) ptax = 130;
            else if (gross > 25000 && gross <= 40000) ptax = 150;
            else if (gross > 40000) ptax = 200;

            // Total Deduction
            const totalDeduction = pf + esi + ptax + tds + loan;
            const netSalary = gross - totalDeduction;

            pfInput.value = pf.toFixed(2);
            esiInput.value = esi.toFixed(2);
            ptaxInput.value = ptax.toFixed(2);
            totalDeductionInput.value = totalDeduction.toFixed(2);
            netSalaryInput.value = netSalary.toFixed(2);

            // Convert net salary to words
            netSalaryWordInput.value = numberToWords(netSalary);
        }

        // Trigger on gross input (auto populate salary breakup)
        grossInput.addEventListener("input", function () {
            let gross = parseFloat(this.value);

            if (isNaN(gross) || gross <= 0) {
                basicInput.value = hraInput.value = medicalInput.value = convayanceInput.value = specialInput.value = "00.00";
                return;
            }

            const medical = 1250;
            const convayance = 1600;

            const basic = gross * 0.5;
            
            // Reset manual flags when gross changes
            basicInput.dataset.manual = 'false';
            hraInput.dataset.manual = 'false';
            specialInput.dataset.manual = 'false';
            
            const hra = basic * 0.5;
            let special = gross - (basic + hra + medical + convayance);
            if (special < 0) special = 0;

            basicInput.value = basic.toFixed(2);
            hraInput.value = hra.toFixed(2);
            medicalInput.value = medical.toFixed(2);
            convayanceInput.value = convayance.toFixed(2);
            specialInput.value = special.toFixed(2);

            calculateAll();
        });

        // Trigger recalculation if any of the manual fields are updated
        [tdsInput, loanInput].forEach(input => {
            input.addEventListener("input", calculateAll);
        });

        // Additional listeners for Basic Salary, HRA and Special Allowance manual tracking
        basicInput.addEventListener("input", function () {
            this.dataset.manual = 'true';
            // When basic is manually changed, recalculate HRA and Special Allowance
            hraInput.dataset.manual = 'false';
            specialInput.dataset.manual = 'false';
            calculateAll();
        });

        hraInput.addEventListener("input", function () {
            this.dataset.manual = 'true';
            // When HRA is manually changed, recalculate Special Allowance
            specialInput.dataset.manual = 'false';
            calculateAll();
        });

        specialInput.addEventListener("input", function () {
            this.dataset.manual = 'true';
            calculateAll();
        });



    });




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
        let event = new Event("change", { bubbles: true });
        currentState.dispatchEvent(event);

        // Store the permanent city value to use after AJAX completes
        let permanentCity = document.getElementById("city").value;

        // Attach an event listener for AJAX completion
        $(document).on("cityDataLoaded", function () {
            let currentCity = document.getElementById("curr_city");

            // Check if the city exists in the dropdown before assigning
            let cityExists = [...currentCity.options].some(option => option.value === permanentCity);

            if (cityExists) {
                currentCity.value = permanentCity;
            }
        });
    }






    //--------- Department Auto Fetch ----------
    $(document).ready(function() {
        var selectedDeptId = {{ $employee->dept_id ?? 'null' }};
        var selectedDesigId = {{ $employee->desig_id ?? 'null' }};

        fetchDepartments(selectedDeptId, selectedDesigId);

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
                data: {dept_name: dept_name, _token: _token},
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('.message-container').html('<div class="alert alert-success">' + response.message + '</div>');
                        setTimeout(function() {
                            $('#depertment').modal('hide'); // Close modal
                            $('.message-container').html(''); // Clear message
                        }, 2000);
                        $('#addAdminDepertmentFrm')[0].reset();
                        fetchDepartments(); // Refresh dropdown
                    }
                },
                error: function(xhr) {
                    $('.message-container').html('<div class="alert alert-danger">Error adding department</div>');
                }
            });
        });

        $('#addAdminDesignationFrm').on('submit', function (e) {
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
                data: { dept_id: dept_id, designation_name: designation_name, _token: _token },
                dataType: "json",
                beforeSend: function () {
                    $('#addEmployeeLoader').show(); // Show loader before sending request
                },
                success: function (response) {
                    $('#addEmployeeLoader').hide(); // Hide loader on success

                    if (response.success) {
                        $('.message-container').html('<div class="alert alert-success">' + response.message + '</div>');

                        // Close modal after 2 seconds
                        setTimeout(function () {
                            $('#designation').modal('hide'); // Close modal
                            $('.message-container').html(''); // Clear message
                        }, 2000);
                        var deptField_id = $('#dept_id').val();
                        $('#addAdminDesignationFrm')[0].reset(); // Reset form
                        getDesignationsByDept(deptField_id);
                    } else {
                        $('.message-container').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function (xhr) {
                    $('#addEmployeeLoader').hide(); // Hide loader on error
                    $('.message-container').html('<div class="alert alert-danger">Error adding designation</div>');
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
                let qualification = $('#qualification').val().trim();
                let deptId = $('#dept_id').val();
                let designationId = $('#designation_id').val();
                let joiningDate = $('#emp_joining_date').val();
                let totalAddition = $('#total_addition').val();
                let basicPercentage = $('#basic_percentage').val().trim();

                let bankName = $('#bank_name').val().trim();
                let bankBranch = $('#bank_branch').val().trim();
                let ifsc = $('#ifsc').val().trim();
                let swiftCode = $('#swift_code').val().trim();
                let accHolderName = $('#account_holder_name').val().trim();
                let accNumber = $('#account_number').val().trim();
                let confirmAccNumber = $('#confirm_account_no').val().trim();

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
                if (basicPercentage === "" || isNaN(basicPercentage) || basicPercentage < 40 || basicPercentage > 60) {
                    showToast("Basic Salary Percentage is required and must be between 40% to 60%", "error");
                    $('#basic_percentage').focus();
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


            let formData = new FormData(this);


            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/update_user_employee') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $("#addAdminEmployeeFrm button[type='submit']").prop("disabled", true);
                },
                success: function (response) {
                    //console.log(response);

                    if (response.status === "success") {
                        // alert(response.message);
                        // window.location.href = response.redirect;
                        showToast(response.message, "success");
                        setTimeout(() =>  window.location.href = response.redirect, 2000); // Reload after 2s
                    } else {
                        // alert(response.message);
                        showToast(response.message, "error");
                    }
                },
                error: function (xhr) {
					let message = "Error: Update Employee";

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

					showToast(message, "error", 5000);
				}
            });
        });

        //-------------- city fetch -----------

        const stateDropdown = $("#state");
        const cityDropdown = $("#city");
        const selectedCity = "{{ $employee->c_emp_city ?? '' }}"; // Get preselected city

        stateDropdown.on("change", function () {
            const stateId = $(this).val(); // Get selected state ID

            if (stateId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: "/getCity",
                    type: "GET",
                    dataType: "json",
                    data: { id: stateId },
                    success: function (data) {
                        cityDropdown.empty();
                        let options = '<option value="">Select City</option>';

                        $.each(data, function (idx, item) {
                            let selected = item.id == selectedCity ? "selected" : "";
                            options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                        });

                        cityDropdown.html(options);
                    },
                    error: function () {
                        alert("Failed to fetch cities.");
                    },
                });
            } else {
                cityDropdown.html('<option value="">Select City</option>');
            }
        });

        // Trigger change event to load cities if a state is already selected
        if (stateDropdown.val()) {
            stateDropdown.trigger("change");
        }

        //------------ Currect city fetch ------------
        const currStateDropdown = $("#curr_state");
        const currCityDropdown = $("#curr_city");
        const selectedCurrCity = "{{ $employee->p_emp_city ?? '' }}"; // Get preselected current city

        currStateDropdown.on("change", function () {
            const stateId = $(this).val(); // Get selected state ID

            if (stateId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: "/getCity",
                    type: "GET",
                    dataType: "json",
                    data: { id: stateId },
                    success: function (data) {
                        currCityDropdown.empty();
                        let options = '<option value="">Select City</option>';

                        $.each(data, function (idx, item) {
                            let selected = item.id == selectedCurrCity ? "selected" : "";
                            options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                        });

                        currCityDropdown.html(options);

                        // Trigger custom event when city data is loaded
                        $(document).trigger("cityDataLoaded");
                    },
                    error: function () {
                        alert("Failed to fetch cities.");
                    },
                });
            } else {
                currCityDropdown.html('<option value="">Select City</option>');
            }
        });

        // Trigger change event to load cities if a state is already selected
        if (currStateDropdown.val()) {
            currStateDropdown.trigger("change");
        }

        //------------- Set Permission -------
        let selectedPermissions = @json($employee->emp_permission ?? []);
        document.querySelectorAll("input[name='emp_permission[]']").forEach(function (checkbox) {
            if (selectedPermissions.includes(checkbox.value)) {
                checkbox.checked = true;
            }
        });
    });

    function fetchDepartments(selectedDeptId, selectedDesigId) {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "{{ url('/get-departments') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                var dropdown = $('#dept_id');
                dropdown.empty();
                dropdown.append('<option value="">Select Department</option>');

                $.each(response, function(index, dept) {
                    var isSelected = dept.id == selectedDeptId ? 'selected' : '';
                    dropdown.append('<option value="' + dept.id + '" ' + isSelected + '>' + dept.dept_name + '</option>');
                });

                if (selectedDeptId) {
                    getDesignationsByDept(selectedDeptId, selectedDesigId);
                }

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


    function getDesignationsByDept(dept_id, selectedDesigId = null) {
        if (dept_id) {
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/get-designations') }}/" + dept_id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    var dropdown = $('#designation_id');
                    dropdown.empty();
                    dropdown.append('<option value="">Select Designation</option>');

                    $.each(response, function(index, designation) {
                        var isSelected = designation.id == selectedDesigId ? 'selected' : '';
                        dropdown.append('<option value="' + designation.id + '" ' + isSelected + '>' + designation.designation_name + '</option>');
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
    document.addEventListener("DOMContentLoaded", function () {
        const nextButtons = document.querySelectorAll(".next-btn");
        const prevButtons = document.querySelectorAll(".previous-btn");

        nextButtons.forEach(button => {
            button.addEventListener("click", function (event) {
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
            button.addEventListener("click", function (event) {
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
                    document.getElementById("epf_check").checked = false;
                    toggleEPFFields();
                }
                if (type === "esic" && (!response.comp_esic || response.comp_esic === "")) {
                    showCompanyPolicyPopup("esic");
                    document.getElementById("esic_check").checked = false;
                    toggleESICFields();
                }
                if (type === "ptax" && (!response.comp_ptax || response.comp_ptax === "")) {
                    showCompanyPolicyPopup("ptax");
                    document.getElementById("ptax_check").checked = false;
                    // If you have updateDeductionFields, call it here
                }
                if (type === "tds" && (!response.comp_tan || response.comp_tan === "")) {
                    showCompanyPolicyPopup("tds");
                    document.getElementById("tds_check").checked = false;
                    // If you have updateDeductionFields, call it here
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
</script>
@endsection
