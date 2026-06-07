@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Payroll Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
                    </ul>
                </div>
                <div class="col-md-12">
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
                                <span class="d-none d-sm-inline">Address</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#educationDetailForm">
                            <a href="#jobDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-graduation-cap"></i>
                                <span class="d-none d-sm-inline">Official Details</span>
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
                                                    <input type="file" id="fileUpload" class="d-none" name="fileUpload" accept="image/*">
                                                </div>
                                                <img src="/storage/profile/e-cashbook.png" alt="user-image" id="uploadedImage" class="wid-150 rounded img-fluid ms-2">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Employee Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name"  id="name" placeholder="Employee Name">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                    {{-- <input type="text" name="phone" id="phone"  class="form-control" placeholder="Enter Contact Number"> --}}
                                                    <input type="text" name="phone" id="phone"  class="form-control" placeholder="Enter Contact Number" maxlength="10" oninput="this.value = this.value.replace(/\D/g, '');">

                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                    <input type="email" name="email" id="email"  class="form-control" placeholder="Enter Email Address">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Date of Birth<span class="text-danger">*</span></label>
                                                    <input type="date" name="dob" id="dob"  class="form-control" placeholder="Enter date of birth" onfocus="this.max = new Date(Date.now() - 86400000).toISOString().split('T')[0]">

                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Gander<span class="text-danger">*</span></label>
                                                    <div class="form-group me-2">
                                                        <select class="select form-select" name="gender" id="gender" >
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Highest Qualification<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="qualification"  id="qualification" placeholder="Enter highest qualification">
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
                                                        <label class="form-label">Address Line 1</label>
                                                        <input type="text" name="c_addr_lineone" id="cust_bill_addone" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="c_addr_linetwo" id="cust_bill_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State</label>
                                                        <select class="form-control select-style" name="c_emp_state" id="state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City</label>
                                                        <select class="form-control select-style" name="c_emp_city" id="city">
                                                            <option value="">Select City</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code</label>
                                                        <input type="text" name="c_emp_pincode" id="cust_bill_pin" class="form-control" placeholder="Enter Zip Code"
                                                            inputmode="numeric" pattern="\d*" maxlength="6"
                                                            oninput="this.value = this.value.replace(/\D/g, '')">
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
                                                        <label class="form-label">Address Line 1</label>
                                                        <input type="text" name="p_addr_lineone" id="cust_ship_addone" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="p_addr_linetwo" id="cust_ship_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State</label>
                                                        <select class="form-control select-style" name="p_emp_state" id="curr_state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City</label>
                                                        <select class="form-control select-style" name="p_emp_city" id="curr_city">
                                                            <option value="">Select City</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code</label>
                                                        <input type="text" name="p_emp_pincode" id="cust_ship_pin" class="form-control" placeholder="Enter Zip Code">
                                                    </div>
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
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="dept_id">Department<span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group me-2" style="flex-grow: 1;">
                                                    {{-- Department Auto Fetch --}}
                                                    <select class="select form-select" name="dept_id" id="dept_id" onchange="getDesignationsByDept(this.value);">
                                                        <option value="">Select Department</option>
                                                    </select>
                                                </div>
                                                <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="#" data-bs-toggle="modal" data-bs-target="#depertment">
                                                    <i class="ti ti-plus py-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="designation_id">Designation<span class="text-danger">*</span></label>
                                            <div class="d-flex align-items-center">
                                                <div class="form-group me-2" style="flex-grow: 1;">
                                                    <select class="select form-select" name="designation_id"  id="designation_id">
                                                        <option value="">Select Designation</option>
                                                    </select>
                                                </div>
                                                <a class="btn btn-primary form-plus-btn d-flex align-items-center justify-content-center" href="#" data-bs-toggle="modal" data-bs-target="#designation">
                                                    <i class="ti ti-plus py-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Joining Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="emp_joining_date"  id="emp_joining_date" placeholder="Enter Employee Joining date" />
                                            </div>
                                        </div>
                                        <h6 class="mb-3">Earnings</h6>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="total_addition">Gross Salary <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="total_addition" id="total_addition" value="" placeholder="Enter Gross Salary" inputmode="numeric" pattern="\d*" oninput="this.value = this.value.replace(/\D/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic_sal">Basic Salary<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="basic_sal" value="00.00" id="basic_sal" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="hra">House Rent Allowance (HRA)</label>
                                                <input type="text" class="form-control" name="hra" id="hra" value="00.00" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="convayance">Conveyance Allowance</label>
                                                <input type="text" class="form-control" name="convayance" id="convayance" value="1600.00" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="medical_allowance">Medical Allowance</label>
                                                <input type="text" class="form-control" name="medical_allowance" id="medical_allowance" value="1250.00" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="special_bonus">Special Allowance</label>
                                                <input type="text" class="form-control" name="special_bonus" id="special_bonus" value="00.00" readonly />
                                            </div>
                                        </div>




                                        <h6 class="mb-3">Deductions</h6>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Provident Fund (PF) </label>
                                                <input type="text" class="form-control" name="provident_fund" value="0" id="provident_fund"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Employee State Insurance (ESI)</label>
                                                <input type="text" class="form-control" name="esi" id="esi" value="0"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Profession Tax (PT) </label>
                                                <input type="text" class="form-control" name="ptax" id="ptax" value="0"  />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Tax Deducted at Source (TDS)</label>
                                                <input type="number" class="form-control" name="tds" id="tds" value="0" placeholder="Enter Employee TDS " />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Loan</label>
                                                <input type="number" class="form-control" name="loan" id="loan" value="0" placeholder="Enter Employee Loan " />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Total Deduction </label>
                                                <input type="text" class="form-control" name="total_deduction" id="total_deduction" value="0" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Net Salary <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="net_sal" id="net_sal"  />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="schoolName">Net Salary in Word<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="net_sal_word"  id="net_sal_word" />
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
                                            <label class="form-label" for="schoolName">Bank Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_name"  id="bank_name" placeholder="Enter Bank Name " />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Bank Branch<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bank_branch"  id="bank_branch" placeholder="Enter Bank Branch " />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">IFSC Code<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="ifsc" id="ifsc"  placeholder="Enter IFSC Code " />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Swift Code</label>
                                            <input type="text" class="form-control" name="swift_code"  id="swift_code" placeholder="Enter Swift Code " />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">Account Holder Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="account_holder_name"  id="account_holder_name" placeholder="Enter Account Holder Name" />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Account Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="account_number"  id="account_number" placeholder="Enter Account Number">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Confirm Account Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="confirm_account_no"  id="confirm_account_no" placeholder="Confirm Account Number">
                                            <span id="accountError" class="text-danger"></span>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label" for="schoolName">UPI / VPA No</label>
                                            <input type="text" class="form-control" name="upi_id" id="upi_id" placeholder="Enter UPI / VPA No." />
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
                            <div class="tab-pane" id="access">
                                <div class="row d-flex justify-content-center">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Generate Loging Email Address<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="login_email" id="login_email" placeholder="Enter Email">
                                        <small id="emailError" class="text-danger"></small>
                                    </div>
                                    {{-- <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputPassword4">Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter Password">
                                            <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputPassword4">Confirm Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd" required placeholder="Confirm Password">
                                            <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="password">Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter Password">
                                            <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="confirm_pwd">Confirm Password<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd" required placeholder="Confirm Password">
                                            <div class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                                                <i class="ti ti-eye"></i>
                                            </div>
                                        </div>
                                        <small id="passwordError" class="text-danger"></small>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-12">
                                            <!-- Flexbox container for checkboxes -->
                                            <div class="d-flex flex-wrap justify-content-start">
                                                <!-- Checkbox Group 1 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="manage_subscription" name="emp_permission[]" id="customCheckinlh0">
                                                        <label class="form-check-label" for="customCheckinlh0">Manage Subscription</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 2 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="ca_management" name="emp_permission[]" id="customCheckinlh1">
                                                        <label class="form-check-label" for="customCheckinlh1">CA Management</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 3 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="customer_management" name="emp_permission[]" id="customCheckinlh2">
                                                        <label class="form-check-label" for="customCheckinlh2">Customer Management</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 4 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="payroll_management" name="emp_permission[]" id="customCheckinlh3">
                                                        <label class="form-check-label" for="customCheckinlh3">Payroll Management</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 5 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="business_earnings" name="emp_permission[]" id="customCheckinlh4">
                                                        <label class="form-check-label" for="customCheckinlh4">Business & Earnings</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 6 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="payment_management" name="emp_permission[]" id="customCheckinlh5">
                                                        <label class="form-check-label" for="customCheckinlh5">Payment Management</label>
                                                    </div>
                                                </div>
                                                <!-- Checkbox Group 7 -->
                                                <div class="card shadow-sm border-0 p-3 m-2" style="width: 18%;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="ticket_management" name="emp_permission[]" id="customCheckinlh6">
                                                        <label class="form-check-label" for="customCheckinlh6">Ticket Management</label>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    {{-- <div class="last">
                                        <button type='submit' id="" class="btn btn-primary d-flex align-items-center justify-content-center">
                                            Add Employee <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </button>
                                    </div> --}}

                                    <div class="last">
                                        <button type="submit" id="submitBtn" class="btn btn-primary d-flex align-items-center justify-content-center" disabled>
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
        const basicInput = document.getElementById("basic_sal");
        const hraInput = document.getElementById("hra");
        const medicalInput = document.getElementById("medical_allowance");
        const convayanceInput = document.getElementById("convayance");
        const specialInput = document.getElementById("special_bonus");

        const pfInput = document.getElementById("provident_fund");
        const esiInput = document.getElementById("esi");
        const ptaxInput = document.getElementById("ptax");
        const tdsInput = document.getElementById("tds");
        const loanInput = document.getElementById("loan");

        const totalDeductionInput = document.getElementById("total_deduction");
        const netSalaryInput = document.getElementById("net_sal");
        const netSalaryWordInput = document.getElementById("net_sal_word");

        function calculateAll() {
            const gross = parseFloat(grossInput.value) || 0;
            const basic = parseFloat(basicInput.value) || 0;
            const tds = parseFloat(tdsInput.value) || 0;
            const loan = parseFloat(loanInput.value) || 0;

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

            // Optional: convert net salary to words (can be implemented or used with plugin)
            // For now, just leave it empty or auto fill something like:
            netSalaryWordInput.value = ""; // Leave blank or use a library
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
        [basicInput, tdsInput, loanInput].forEach(input => {
            input.addEventListener("input", calculateAll);
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
        $("#login_email").on("keyup", function() {
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
                let qualification = $('#qualification').val().trim();
                let deptId = $('#dept_id').val();
                let designationId = $('#designation_id').val();
                let joiningDate = $('#emp_joining_date').val();
                let totalAddition = $('#total_addition').val();

                let bankName = $('#bank_name').val().trim();
                let bankBranch = $('#bank_branch').val().trim();
                let ifsc = $('#ifsc').val().trim();
                let swiftCode = $('#swift_code').val().trim();
                let accHolderName = $('#account_holder_name').val().trim();
                let accNumber = $('#account_number').val().trim();
                let confirmAccNumber = $('#confirm_account_no').val().trim();

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

            let formData = new FormData(this);

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: "{{ url('/add_admin_employee') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#addAdminEmployeeFrm button[type='submit']").prop("disabled", true);
                },
                success: function(response) {
                    // console.log(response);

                    // if (response.status === "success") {
                    //     alert(response.message);
                    //     window.location.href = response.redirect;
                    // } else {
                    //     alert(response.message);
                    // }


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


</script>
@endsection
