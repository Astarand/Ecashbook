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
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/customer-list') }}">Customer & Receivables</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add Customer & Receivables</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-customer-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add Customer & Receivables</h2>
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
                            <a href="#basicDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Personal Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#jobDetailForm">
                            <a href="#billingDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-map-pin"></i>
                                <span class="d-none d-sm-inline">Billing Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#educationDetailForm">
                            <a href="#bankDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-bank"></i>
                                <span class="d-none d-sm-inline">Bank Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <!-- START: Define your tab pans here -->
                        <div class="tab-pane show active" id="basicDetail">
                            <form name="add_cust_detail" id="add_cust_detail" method="post">
                                <input type="hidden" name="id" id="custId" value="">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col-md-12 mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="card shadow-sm border-0 p-3 m-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="cust_value"
                                                            checked value="1" id="highValueCustomers">
                                                        <label class="form-check-label" for="highValueCustomers">High Valued Customers</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card shadow-sm border-0 p-3 m-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="cust_value"
                                                            value="2" id="lowValueCustomers">
                                                        <label class="form-check-label" for="lowValueCustomers">Low Valued Customers</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="inputEmail4">Company GST Registered?<span class="text-danger">*</span></label>
                                        <div class="form-group me-2">
                                            <select class="select form-select" name="gst_reg" id="gst_reg" required>
                                                <option value="">Select</option>
                                                <option value="Yes" selected>Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="cust_gst_field" style="display: none">
                                        <label class="form-label">GST Number <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control gst-input" name="cust_gst_no" id="cust_gst_no" placeholder="Enter GST Number">
                                                <button id="get_gst_btn" class="btn btn-primary" type="button"><i class="ti ti-cloud-download align-middle"></i> Get Details</button>
                                            </div>
											<small class="gst-error text-danger d-none"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="cust_gst_type" style="display: none">
                                        <div class="mb-3">
                                            <label class="form-label">GSTIN Type <span class="text-danger">*</span></label>
                                            <select class="form-control error gstType" name="cust_gst_type" id="cust_gst_type" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option label="select"></option>
                                                <option value="Register">Register</option>
                                                <option value="QRMP">QRMP</option>
                                                <option value="Un-Register">Un-Register</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Company Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required name="cust_name" id="cust_name" placeholder="Enter Company Name">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">PAN Number</label>
                                        <input type="text" class="form-control" name="cust_pan" id="cust_pan" placeholder="Enter PAN Number" style="text-transform:uppercase">
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Email </label>
                                        <input type="email" name="cust_email" id="cust_email" required class="form-control" placeholder="Enter Email Address">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Phone Number</label>
                                        <input type="number" name="cust_phone" id="cust_phone" required minlength="10" maxlength="10" class="form-control" placeholder="Enter Phone Number">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="mb-3">
                                                <label class="form-label">Company Type <span class="text-danger">*</span></label>
                                                <select class="select form-select company-type-dropdown" name="comp_type" id="comp_type" required>
                                                    <option value="" label="select"></option>
                                                    <option value="Proprietorship">Proprietorship</option>
                                                    <option value="Partnership">Partnership</option>
                                                    <option value="One person Company (OPC)">One person Company (OPC)</option>
                                                    <option value="LLP Company">LLP Company</option>
                                                    <option value="PVT Ltd Company">PVT Ltd Company</option>
                                                    <option value="LTD Company">LTD Company</option>
                                                    <option value="Section-8 Company">Section-8 Company</option>
                                                    <option value="Society/Trust">Society/Trust</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Enter Custom Company Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="other_comp" placeholder="Enter Company Type">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 company-type-input" id="company_reg_div" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" id="company_reg_label">CIN <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cin" id="cin" value="" placeholder="Enter CIN Number">
                                            </div>
                                        </div>
                                        <div id="inc_date_div" class="col-sm-4 company-type-input" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" id="inc_date_label">Incorporation Date</label>
                                                <input type="date" name="inc_date" id="inc_date" value="" class="form-control" placeholder="Enter Incorporation Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <h4 class="mb-0">Contact Person Details</h4>
                                    </div>
                                    <div class="col-md-8 text-end">
                                        <button type="button" onclick="copy_contact_person_details()" class="btn btn-primary"> Same as Above</button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Contact Person Name </label>
                                        <input type="text" class="form-control" name="cont_name" id="cont_name" placeholder="Enter Contact Person Name">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Contact Number </label>
                                        <input type="number" class="form-control" name="cont_no" id="cont_no" minlength="10" maxlength="10" placeholder="Enter Contact Number">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">E-mail Address </label>
                                        <input type="text" class="form-control" name="cont_email" id="cont_email" placeholder="Enter E-mail Address">
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="exampleFormControlTextarea1">Special Note</label>
                                        <textarea class="form-control" name="cont_notes" id="cont_notes" rows="3" placeholder="Write Special Note"></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="d-flex wizard justify-content-end mt-3">
                                <div class="last">
                                    <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                        Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class="tab-pane" id="billingDetails">
                            <form method="post" name="add_cust_bill" id="add_cust_bill">
                                <div class="row">
                                    <!-- Billing Address -->
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                                            <h5>Billing Address</h5>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST No (For Alternate GST)</label>
                                                    {{-- <input type="text" id="billing-gst" class="form-control" placeholder="Enter GST No"> --}}
                                                    <input type="text" name="cust_bill_gstno" id="cust_bill_gstno" class="form-control gst-input" placeholder="Enter alternate GST No">
													<small class="gst-error text-danger d-none"></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person Name </label>
                                                    {{-- <input type="text" id="billing-contact-name" class="form-control" placeholder="Enter Contact Person Name"> --}}
                                                    <input type="text" name="cust_bill_contact" id="cust_bill_contact" class="form-control" placeholder="Enter Contact Person Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Designation </label>
                                                    {{-- <input type="text" id="billing-designation" class="form-control" placeholder="Enter Designation"> --}}
                                                    <input type="text" name="cust_bill_designa" id="cust_bill_designa" class="form-control" placeholder="Enter Designation">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile No </label>
                                                    <input type="number" name="cust_bill_mobilno" id="cust_bill_mobilno" minlength="10" maxlength="10" class="form-control" placeholder="Enter Mobile No">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                    <input type="text" name="cust_bill_addone" required id="cust_bill_addone" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 2 </label>
                                                    <input type="text" name="cust_bill_addtwo" id="cust_bill_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" required name="cust_bill_state" id="comp_bill_state">
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
                                                    <select class="form-control" name="cust_bill_city" required id="cust_bill_city">
                                                        <option value="">Select City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                    <input type="text" name="cust_bill_pin" id="cust_bill_pin" required class="form-control" placeholder="Enter Zip Code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Address -->
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5>Shipping Address</h5>
                                            <div class="btn btn-primary" id="copy-billing-address-btn" onclick="copyBillingAddress()">Same as Billing Address</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST No (For Alternate GST)</label>
                                                    <input type="text" name="cust_ship_gstno" id="cust_ship_gstno" class="form-control gst-input" placeholder="Enter alternate GST No">
													<small class="gst-error text-danger d-none"></small>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person Name </label>
                                                    <input type="text" name="cust_ship_contact"  id="cust_ship_contact" class="form-control" placeholder="Enter Contact Person Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Designation </label>
                                                    <input type="text" name="cust_ship_designa"  id="cust_ship_designa" class="form-control" placeholder="Enter Designation">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile No </label>
                                                    <input type="number" name="cust_ship_mobilno"  id="cust_ship_mobilno" minlength="10" maxlength="10" class="form-control" placeholder="Enter Mobile No">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 1 </label>
                                                    <input type="text" name="cust_ship_addone" id="cust_ship_addone" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 2</label>
                                                    <input type="text" name="cust_ship_addtwo" id="cust_ship_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">State </label>
                                                    <select class="form-control select-style" name="cust_ship_state" id="cust_ship_state">
                                                        <option value="">Select State</option>
                                                        @foreach($states as $k=>$state)
                                                        <option value="{{ $state->id }}">{{ $state->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">City </label>
                                                    <select class="form-control" name="cust_ship_city" id="cust_ship_city">
                                                        <option value="">Select City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">Zip Code </label>
                                                    <input type="text" name="cust_ship_pin" id="cust_ship_pin" class="form-control" placeholder="Enter Zip Code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

                        <div class="tab-pane" id="bankDetails">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end align-items-center mb-4 mt-3">
                                        <button type="button" id="addBankAccount" class="btn btn-shadow btn-primary me-2 w-100">Add Another Account</button>
                                    </div>
                                </div>
                            </div>
                            <form action="javascript:void(0);" method="post" name="add_cust_bank" id="add_cust_bank">
                                @csrf
                                <div id="bankFormContainer_field"></div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="last">
                                        <button type='submit' id="nxtBtnVThree" class="btn btn-primary d-flex align-items-center justify-content-center">
                                            Add Customer <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!-- END: Define your controller buttons here-->
                    </div>
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
            <form action="javascript:void(0);" method="post" name="addAdminDepertmentFrm" id="addAdminDepertmentFrm" novalidate="novalidate">
                <input type="hidden" name="_token" value="W0toh99gQub89hiQ1JDskqVuPKG9XUALbwOvPRIk">
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
                    <button type="submit" class="btn btn-primary">Save</button>
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
                <input type="hidden" name="_token" value="W0toh99gQub89hiQ1JDskqVuPKG9XUALbwOvPRIk">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-12 mb-3">
                            <div class="form-group">
                                <label>Select Department</label>
                                <select class="select form-select" name="deptName" id="deptName">
                                    <option value="">Select Department</option>
                                    <option value="1">Sales</option>
                                    <option value="2">Operation</option>
                                    <option value="3">Accounts</option>
                                    <option value="4">HR</option>
                                    <option value="5">GR-4</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Add New Designation</label>
                                <input type="text" name="designation_name" id="designation_name" class="form-control" placeholder="Enter Designation category">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="message-container"></div>
                    <div id="addEmployeeLoader" class="loader"></div>
                    <button type="submit" class="btn btn-primary">Save</button>
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
@endsection

@section('page-script')
<script>
    //GST YES/ NO
    document.addEventListener("DOMContentLoaded", function() {
        // Select the GST Registration dropdown
        const gstDropdown = document.querySelector("#gst_reg");

        // Get the related fields
        const gstField = document.querySelector("#cust_gst_field");
        const gstTypeField = document.querySelector("#cust_gst_type");

        // Function to toggle GST fields
        function toggleGSTFields(value) {
            if (value === "Yes") {
                gstField.style.display = "block"; // Show GST Number field
                gstTypeField.style.display = "block"; // Show GSTIN Type field
            } else {
                gstField.style.display = "none"; // Hide GST Number field
                gstTypeField.style.display = "none"; // Hide GSTIN Type field
            }
        }

        // Set default state (Yes selected by default)
        gstDropdown.value = "Yes"; // Default to Yes
        toggleGSTFields(gstDropdown.value);

        // Add event listener for changes
        gstDropdown.addEventListener("change", function() {
            toggleGSTFields(this.value);
        });
    });



    //Bank Account Addiition
    document.addEventListener('DOMContentLoaded', function() {
        const maxAccounts = 3;
        const bankFormContainer = document.getElementById('bankFormContainer_field');
        const addBankAccountButton = document.getElementById('addBankAccount');
        const addBankButtonContainer = addBankAccountButton.parentElement;

        // Initialize the form with at least one bank account
        const initializeBankAccount = () => {
            createBankAccount(1);
            toggleAddButton();
        };

        // Create a new bank account form
        const createBankAccount = (index) => {
            const bankForm = document.createElement('div');
            bankForm.className = 'bank-form mb-3';
            bankForm.dataset.index = index;

            bankForm.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4>Bank Account ${index}</h4>
                </div>
                <div class="col-md-8 text-end">
                    <button type="button" class="btn btn-danger delete-bank" aria-label="Delete Bank Account">
                        <i class="ph-duotone ph-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-md-4">
                    <label class="form-label">Bank Name</label>
                    <input type="text" class="form-control" name="cust_bank_name[]" placeholder="Enter Bank Name">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Branch</label>
                    <input type="text" class="form-control" name="cust_bank_branch[]" placeholder="Enter Branch">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Account Holder Name</label>
                    <input type="text" class="form-control" name="cust_bank_holder_name[]" placeholder="Enter Account Holder Name">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Account Number</label>
                    <input type="text" class="form-control" name="cust_ac_no[]" placeholder="Enter Account Number">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" class="form-control" name="cust_ifsc_code[]" placeholder="Enter IFSC Code">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">UPI ID</label>
                    <input type="text" class="form-control" name="cust_ac_upid[]" placeholder="Enter UPI ID">
                </div>
            </div>
        `;

            // Add delete button functionality
            const deleteButton = bankForm.querySelector('.delete-bank');
            deleteButton.addEventListener('click', () => deleteBankAccount(bankForm));

            bankFormContainer.appendChild(bankForm);
            toggleAddButton();
        };

        // Delete a bank account form
        const deleteBankAccount = (bankForm) => {
            const bankForms = document.querySelectorAll('.bank-form');
            if (bankForms.length > 1) {
                bankForm.remove();
                updateAccountNumbers();
            } else {
                alert('At least one bank account is required.');
            }
            toggleAddButton();
        };

        // Update account numbering
        const updateAccountNumbers = () => {
            const bankForms = document.querySelectorAll('.bank-form');
            bankForms.forEach((form, index) => {
                const accountIndex = index + 1;
                form.dataset.index = accountIndex;
                form.querySelector('h4').textContent = `Bank Account ${accountIndex}`;
            });
        };

        // Toggle the Add button based on the number of forms
        const toggleAddButton = () => {
            const bankForms = document.querySelectorAll('.bank-form');
            if (bankForms.length >= maxAccounts) {
                addBankButtonContainer.style.display = 'none';
            } else {
                addBankButtonContainer.style.display = 'flex';
                addBankAccountButton.disabled = bankForms.length >= maxAccounts;
            }
        };

        // Add new bank account on button click
        addBankAccountButton.addEventListener('click', () => {
            const bankForms = document.querySelectorAll('.bank-form');
            if (bankForms.length < maxAccounts) {
                createBankAccount(bankForms.length + 1);
            }
        });

        // Initialize with one bank account
        initializeBankAccount();
    });

    //Fetch City for billing
    const stateDropdown = document.getElementById('comp_bill_state');
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
                    $("#cust_bill_city").empty();
                    var str = '<option value="">Select City</option>';
                    $.each(data, function(idx, item) {
                        str +=
                            '<option value="' +
                            item.id +
                            '">' +
                            item.name +
                            "</option>";
                    });
                    $("#cust_bill_city").html(str);
                },
            });
        } else {
            alert("No state selected!");
        }
    });

    //Fetch City for shipping
    const stateDropdownShipping = document.getElementById('cust_ship_state');
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
                    $("#cust_ship_city").empty();
                    var str = '<option value="">Select City</option>';
                    $.each(data, function(idx, item) {
                        str +=
                            '<option value="' +
                            item.id +
                            '">' +
                            item.name +
                            "</option>";
                    });
                    $("#cust_ship_city").html(str);
                },
            });
        } else {
            alert("No state selected!");
        }
    });

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
	
	//GST validation
	const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
	document.addEventListener("input", function (e) {

		if (e.target.classList.contains("gst-input")) 
		{

			let input = e.target;
			let value = input.value.toUpperCase();
			input.value = value;

			let errorEl = input.closest(".mb-3")?.querySelector(".gst-error");

			if (!errorEl) return;

			// Empty
			if (value.length === 0) {
				errorEl.classList.add("d-none");
				input.classList.remove("is-invalid", "is-valid");
				return;
			}

			// Length check
			if (value.length < 15) {
				errorEl.innerText = "GST must be 15 characters.";
				errorEl.classList.remove("d-none");
				input.classList.add("is-invalid");
				input.classList.remove("is-valid");
				return;
			}

			// Regex check
			if (!gstRegex.test(value)) {
				errorEl.innerText = "Invalid GST format (e.g. 22AAAAA0000A1Z5)";
				errorEl.classList.remove("d-none");
				input.classList.add("is-invalid");
				input.classList.remove("is-valid");
			} else {
				errorEl.innerText = "";
				errorEl.classList.add("d-none");
				input.classList.remove("is-invalid");
				input.classList.add("is-valid");
			}
		}
	});

    //Data Save
    $("form#add_cust_bank").bind("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        // Required fields
        const requiredFields = [
            { id: 'gst_reg', name: 'GST Registration' },
            { id: 'cust_name', name: 'Company Name' },
            //{ id: 'cust_pan', name: 'PAN Number' },
            // { id: 'cust_email', name: 'Email' },
            // { id: 'cust_phone', name: 'Phone Number' },
            { id: 'comp_type', name: 'Company Type' },
            // { id: 'cont_name', name: 'Contact Person Name' },
            // { id: 'cont_no', name: 'Contact Number' },
            // { id: 'cont_email', name: 'Contact Email' },

            // { id: 'cust_bill_contact', name: 'Billing Contact Person Name' },
            // { id: 'cust_bill_designa', name: 'Billing Designation' },
            // { id: 'cust_bill_mobilno', name: 'Billing Mobile Number' },
            { id: 'cust_bill_addone', name: 'Billing Address Line 1' },
            { id: 'comp_bill_state', name: 'Billing State' },
            { id: 'cust_bill_city', name: 'Billing City' },
            { id: 'cust_bill_pin', name: 'Billing Zip Code' },

            // { id: 'cust_ship_contact', name: 'Shipping Contact Person Name' },
            // { id: 'cust_ship_designa', name: 'Shipping Designation' },
            // { id: 'cust_ship_mobilno', name: 'Shipping Mobile Number' },
            // { id: 'cust_ship_addone', name: 'Shipping Address Line 1' },
            // { id: 'cust_ship_state', name: 'Shipping State' },
            // { id: 'cust_ship_city', name: 'Shipping City' },
            // { id: 'cust_ship_pin', name: 'Shipping Zip Code' }
        ];

        // Check if 'cust_value' radio is selected
        const custValue = $("input[name='cust_value']:checked").val();
        if (!custValue) {
            showToast("Customer Type (High/Low Valued) is required.", "error");
            return false;
        }

        // Validate base required fields
        for (let field of requiredFields) {
            const value = $("#" + field.id).val();
            if (!value || value.trim() === "") {
                showToast(`${field.name} is required.`, "error");
                $("#" + field.id).focus();
                return false;
            }
        }

        // PAN Number validation
        const panNo = $("#cust_pan").val().trim().toUpperCase();
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (panNo !== '' && !panRegex.test(panNo)) {
            showToast("Invalid PAN Number. Format should be: AAAAA9999A", "error");
            $("#cust_pan").focus();
            return false;
        }

        // Additional GST validation
        const gstReg = $("#gst_reg").val();
        if (gstReg === "Yes") {
            const gstNo = $("#cust_gst_no").val().trim();
            const gstType = $("#cust_gst_type").val();
            
            
            const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

            if (!gstNo) {
                showToast("GST Number is required.", "error");
                $("#cust_gst_no").focus();
                return false;
            }

            if (!gstRegex.test(gstNo)) {
                showToast("Please enter a valid GST Number (e.g. 22AAAAA0000A1Z5).", "error");
                $("#cust_gst_no").focus();
                return false;
            }

            // if (!gstType || gstType === "select" || gstType === "") {
            //     showToast("GSTIN Type is required.", "error");
            //     $("#cust_gst_type").focus();
            //     return false;
            // }
        }

        
        // Company Type validations
        const compType = $("#comp_type").val();

        const cinTypes = [
            "One person Company (OPC)",
            "PVT Ltd Company",
            "LTD Company",
            "Section-8 Company"
        ];

        let regFieldId = "";
        let regFieldName = "";
        let regRegex = null;
        let regErrorMsg = "";
        let dateLabel = "Incorporation Date";

        // ---- Decide field based on company type ----
        if (cinTypes.includes(compType)) {
            regFieldId = "cin";
            regFieldName = "CIN Number";
            regRegex = /^[A-Z]{1}[0-9]{5}[A-Z]{2}[0-9]{4}[A-Z]{3}[0-9]{6}$/;
            regErrorMsg = "Invalid CIN Number. Format: L12345MH2020PLC123456";
        }

        else if (compType === "LLP Company") {
            regFieldId = "llpin";
            regFieldName = "LLPIN";
            dateLabel = "Incorporation Date";
        }

        else if (compType === "Society/Trust") {
            regFieldId = "reg_no";
            regFieldName = "Registration No";
            dateLabel = "Registration Date";
        }

        // ---- Validate when required ----
        // if (regFieldId) {

        //     const regValue = $("#" + regFieldId).val().trim();
        //     const incDate = $("#inc_date").val();

        //     if (!regValue) {
        //         showToast(`${regFieldName} is required.`, "error");
        //         $("#" + regFieldId).focus();
        //         return false;
        //     }

        //     if (regRegex && !regRegex.test(regValue)) {
        //         showToast(regErrorMsg, "error");
        //         $("#" + regFieldId).focus();
        //         return false;
        //     }

        //     if (!incDate) {
        //         showToast(`${dateLabel} is required.`, "error");
        //         $("#inc_date").focus();
        //         return false;
        //     }
        // }

        // ---- Other company type ----
        if (compType === "Other") {
            const otherComp = $("input[name='other_comp']").val();
            if (!otherComp || otherComp.trim() === "") {
                showToast("Custom Company Type is required.", "error");
                $("input[name='other_comp']").focus();
                return false;
            }
        }


        // If all validations pass
        const formCustomerData =
            $("form#add_cust_detail").serialize() +
            "&" +
            $("form#add_cust_bill").serialize() +
            "&" +
            $("form#add_cust_bank").serialize();

        const custId = $("#custId").val();
        const suburl = custId === "" ? "/add_customer" : "/update_customer";

        
        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: suburl,
            type: "POST",
            data: formCustomerData,
            success: function (response) {
                
                $("#loader").hide();
                if (response.class === "succ") {
                    showToast("Customer added successfully.", "success");
                    setTimeout(function () {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    showToast("Business Details Update: " + response.message, "error");
                }
            },
        });
    });




    document.addEventListener("DOMContentLoaded", function() {
        let gstReg = document.getElementById("gst_reg");
        let gstField = document.getElementById("cust_gst_field");
        let gstInput = document.getElementById("cust_gst_no");

        let gstTypeField = document.getElementById("cust_gst_type");
        let gstTypeInput = document.getElementById("cust_gst_type").querySelector("select");

        function toggleGSTFields() {
            if (gstReg.value === "Yes") {
                gstField.style.display = "block";
                gstInput.setAttribute("required", "required");

                gstTypeField.style.display = "block";
                gstTypeInput.setAttribute("required", "required");
            } else {
                gstField.style.display = "none";
                gstInput.removeAttribute("required");
                gstInput.value = ""; // Clear input when hidden

                gstTypeField.style.display = "none";
                gstTypeInput.removeAttribute("required");
                gstTypeInput.value = ""; // Clear selection when hidden
            }
        }

        // Run on page load in case "Yes" is selected by default
        toggleGSTFields();

        // Listen for changes
        gstReg.addEventListener("change", toggleGSTFields);
    });
	
	//--------------For GST Details Fetching ----------------
	$('#get_gst_btn').click(function() {
		let gstin = $('#cust_gst_no').val();
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

					// ================== Populate Basic Fields ==================
					$('#cust_gst_no').val(d.gstin);
					$('#cust_name').val(d.tradeNam || d.lgnm); // Prefer Trade Name
					$('#comp_type').val(d.ctb); // Proprietorship / Pvt Ltd etc
					let fullAddress = "";
					// ================== Address ==================
					if (d.pradr && d.pradr.addr) {
						let a = d.pradr.addr;

						let address1 = `${a.bno || ''} ${a.bnm || ''}`;
						let address2 = `${a.flno || ''} ${a.st || ''}, ${a.loc || ''}`;
						fullAddress = [a.bno, a.bnm, a.flno, a.st, a.loc, a.dst, a.stcd + " - " + a.pncd].filter(Boolean).join(', ');

						$('#cust_bill_addone').val(address1);
						$('#cust_bill_addtwo').val(address2);
						$('#cust_bill_pin').val(a.pncd);
					}

					// ================== Contact Person ==================
					$('#cont_name').val(d.lgnm);

					// ================== GST Type ==================
					$('.gstType').val('Register').trigger('change');

					// ================== Show Modal (optional) ==================
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
					alert("GST Details not found.");
				}
			},

			error: function(xhr) {
				console.error(xhr.responseText);
				$("#loader").hide();
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
	
	$("#cust_phone, #cont_no, #cust_bill_mobilno, #cust_ship_mobilno").on("input", function () {
		let value = this.value;
		value = value.replace(/\D/g, '');
		this.value = value.substring(0, 10);
	});

    function startAddCustomerTour() {
        if (typeof introJs !== 'function') return;

        let tour = introJs().setOptions({
            steps: [
                {
                    title: 'Add Customer Wizard Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-user-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Follow this step-by-step guide to fill in the customer registration details, billing/shipping addresses, and banking info.</p></div>'
                },
                {
                    element: 'a[href="#basicDetail"]',
                    title: 'Personal Details Tab',
                    intro: 'This tab holds the customer\'s company profile, PAN, contact information, and GST status.'
                },
                {
                    element: '#cust_name',
                    title: 'Company Name',
                    intro: 'Enter the official name of the company or buyer.'
                },
                {
                    element: '#gst_reg',
                    title: 'GST Status',
                    intro: 'Indicate whether the customer is GST registered. Selecting "Yes" opens additional fields to fetch GST details automatically.'
                },
                {
                    element: 'a[href="#billingDetails"]',
                    title: 'Billing Details Tab',
                    intro: 'This tab is where you enter the billing and shipping addresses.'
                },
                {
                    element: '#cust_bill_addone',
                    title: 'Billing Address',
                    intro: 'Fill in the customer\'s primary billing address, state, city, and zip code.'
                },
                {
                    element: '#copy-billing-address-btn',
                    title: 'Same as Billing Address',
                    intro: 'If the shipping address is the same as the billing address, click here to copy all fields instantly.'
                },
                {
                    element: 'a[href="#bankDetails"]',
                    title: 'Bank Details Tab',
                    intro: 'Register and manage bank account info, IFSC codes, and UPI IDs for this customer.'
                },
                {
                    element: '#addBankAccount',
                    title: 'Manage Bank Accounts',
                    intro: 'You can add up to 3 bank accounts for each customer using this button.'
                },
                {
                    element: '#nxtBtnVThree',
                    title: 'Save Customer',
                    intro: 'After filling out all three sections, click this button to submit and save the customer details.'
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
        $('#start-add-customer-tour').on('click', function(e) {
            e.preventDefault();
            startAddCustomerTour();
        });
    });
</script>
@endsection