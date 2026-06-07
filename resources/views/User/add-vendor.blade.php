@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="#">Business Operations</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.VendorList') }}">Vendors & Payables</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Vendors & Payables</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add Vendors & Payables</h2>
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
                            <form name="add_vendor_detail" id="add_vendor_detail" method="post">
                                <input type="hidden" name="id" id="vendorId" value="">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col-md-12 mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="card shadow-sm border-0 p-3 m-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="vendor_priority"
                                                            checked value="1" id="highValuevendoromers">
                                                        <label class="form-check-label" for="highValuevendoromers">High Valued vendor</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card shadow-sm border-0 p-3 m-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="vendor_priority"
                                                            value="2" id="lowValuevendoromers">
                                                        <label class="form-check-label" for="lowValuevendoromers">Low Valued vendor</label>
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
                                    <div class="col-sm-4" id="vendor_gst_field" style="display: none">
                                        <label class="form-label">GST Number <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control gst-input" name="vendor_gst_no" id="vendor_gst_no" placeholder="Enter GST Number">
                                                <button class="btn btn-primary" id="get_gst_btn" type="button"><i class="ti ti-cloud-download align-middle"></i> Get Details</button>
                                            </div>
											<small class="gst-error text-danger d-none"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="vendor_gst_type" style="display: none">
                                        <div class="mb-3">
                                            <label class="form-label">GSTIN Type <span class="text-danger">*</span></label>
                                            <select class="form-control error gstType" name="vendor_gst_type" id="vendor_gst_type" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option label="select"></option>
                                                <option value="Register">Register</option>
                                                <option value="QRMP">QRMP</option>
                                                <option value="Un-Register">Un-Register</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Company Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required name="vendor_name" id="vendor_name" placeholder="Enter Company Name">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">PAN Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required name="vendor_pan" id="vendor_pan" placeholder="Enter PAN Number" style="text-transform: uppercase;">
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Email <span class="text-danger"></span></label>
                                        <input type="text" name="vendor_email" id="vendor_email" class="form-control" placeholder="Enter Email Address">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Phone Number<span class="text-danger"></span></label>
                                        <input type="number" name="vendor_phone"  id="vendor_phone" class="form-control" placeholder="Enter Phone Number">
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

                                        {{-- <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Enter vendorom Company Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="other_comp" placeholder="Enter Company Type">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 company-type-input" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">CIN <span class="text-danger"></span></label>
                                                <input type="text" class="form-control" name="cin" id="cin" value="" placeholder="Enter CIN Number">
                                            </div>
                                        </div>
                                        <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Incorporation Date</label>
                                                <input type="date" name="inc_date" id="inc_date" value="" class="form-control" name="incorporation_date" placeholder="Enter Incorporation Date">
                                            </div>
                                        </div> --}}

                                        {{-- //---- --}}

                                        <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Enter Custom Company Type <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="other_comp" placeholder="Enter Company Type">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 company-type-input" id="company_reg_div" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" id="company_reg_label">CIN </label>
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
                                        <button type="button" onclick="copy_contact_person_details_vendor()" class="btn btn-primary"> Same as Above</button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Contact Person Name</label>
                                        <input type="text" class="form-control"  name="cont_name" id="cont_name" placeholder="Enter Contact Person Name">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">Contact Number</label>
                                        <input type="number" class="form-control"  name="cont_no" id="cont_no" placeholder="Enter Contact Number">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label" for="inputEmail4">E-mail Address</label>
                                        <input type="text" class="form-control"  name="cont_email" id="cont_email" placeholder="Enter E-mail Address">
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
                            <form method="post" name="add_vendor_bill" id="add_vendor_bill">
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
                                                    <input type="text" name="vendor_bill_gstno" id="vendor_bill_gstno" class="form-control gst-input" placeholder="Enter GST No">
													<small class="gst-error text-danger d-none"></small>
												</div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person Name </label>
                                                    {{-- <input type="text" id="billing-contact-name" class="form-control" placeholder="Enter Contact Person Name"> --}}
                                                    <input type="text" name="vendor_bill_contact" id="vendor_bill_contact" class="form-control" placeholder="Enter Contact Person Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Designation </label>
                                                    {{-- <input type="text" id="billing-designation" class="form-control" placeholder="Enter Designation"> --}}
                                                    <input type="text" name="vendor_bill_designa" id="vendor_bill_designa" class="form-control" placeholder="Enter Designation">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile No </label>
                                                    <input type="number" name="vendor_bill_mobilno" id="vendor_bill_mobilno" class="form-control" placeholder="Enter Mobile No">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                    <input type="text" name="vendor_bill_addone" required id="vendor_bill_addone" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 2 </label>
                                                    <input type="text" name="vendor_bill_addtwo"  id="vendor_bill_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="vendor_bill_state" required id="comp_bill_state">
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
                                                    <select class="form-control" name="vendor_bill_city" required id="vendor_bill_city">
                                                        <option value="">Select City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                    <input type="text" name="vendor_bill_pin" id="vendor_bill_pin" required class="form-control" placeholder="Enter Zip Code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Address -->
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5>Shipping Address</h5>
                                            <div class="btn btn-primary" onclick="copyBillingAddress_vendor()">Same as Billing Address</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GST No (For Alternate GST)</label>
                                                    <input type="text" name="vendor_ship_gstno" id="vendor_ship_gstno" class="form-control gst-input" placeholder="Enter GST No">
													<small class="gst-error text-danger d-none"></small>
												</div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Contact Person Name </label>
                                                    <input type="text" name="vendor_ship_contact" id="vendor_ship_contact" class="form-control" placeholder="Enter Contact Person Name">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Designation </label>
                                                    <input type="text" name="vendor_ship_designa" id="vendor_ship_designa" class="form-control" placeholder="Enter Designation">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile No </label>
                                                    <input type="number" name="vendor_ship_mobilno" id="vendor_ship_mobilno" class="form-control" placeholder="Enter Mobile No">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 1 </label>
                                                    <input type="text" name="vendor_ship_addone" id="vendor_ship_addone" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Address Line 2</label>
                                                    <input type="text" name="vendor_ship_addtwo" id="vendor_ship_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">State </label>
                                                    <select class="form-control select-style" name="vendor_ship_state" id="vendor_ship_state">
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
                                                    <select class="form-control" name="vendor_ship_city" id="vendor_ship_city">
                                                        <option value="">Select City</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-0">
                                                    <label class="form-label">Zip Code </label>
                                                    <input type="text" name="vendor_ship_pin" id="vendor_ship_pin" class="form-control" placeholder="Enter Zip Code">
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
                            <form action="javascript:void(0);" method="post" name="add_vendor_bank" id="add_vendor_bank">
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
                                            Add Vendor <i class="ti ti-arrow-up-right-circle ms-2"></i>
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

<script>
    //GST YES/ NO
    document.addEventListener("DOMContentLoaded", function() {
        const gstDropdown = document.querySelector("#gst_reg");
        const gstField = document.querySelector("#vendor_gst_field");
        const gstTypeContainer = document.querySelector("#vendor_gst_type");

        function toggleGSTFields(value) {
            gstField.style.display = value === "Yes" ? "block" : "none";
            gstTypeContainer.style.display = value === "Yes" ? "block" : "none";
        }

        // Ensure the default state matches the selected value
        toggleGSTFields(gstDropdown.value);

        gstDropdown.addEventListener("change", function() {
            toggleGSTFields(this.value);
        });
    });

    //Bank Account Addiition
    document.addEventListener('DOMContentLoaded', function() {
        const maxAccounts = 3;
        const bankFormContainer = document.getElementById('bankFormContainer_field');
        const addBankAccountButton = document.getElementById('addBankAccount');

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
                        <input type="text" class="form-control" name="bank_name[]" placeholder="Enter Bank Name" >
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control" name="bank_branch[]" placeholder="Enter Branch" >
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Account Holder Name</label>
                        <input type="text" class="form-control" name="acc_holder_name[]" placeholder="Enter Account Holder Name" >
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" name="acc_number[]" placeholder="Enter Account Number" >
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" class="form-control" name="acc_ifsc[]" placeholder="Enter IFSC Code" >
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">UPI ID</label>
                        <input type="text" class="form-control" name="acc_upi_id[]" placeholder="Enter UPI ID">
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
                toggleAddButton(); // Re-enable "Add" button if the number of accounts is less than max
            } else {
                alert('At least one bank account is required.');
            }
        };

        // Update account numbering after deletion
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
            addBankAccountButton.disabled = bankForms.length >= maxAccounts;
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
                    $("#vendor_bill_city").empty();
                    var str = '<option value="">Select City</option>';
                    $.each(data, function(idx, item) {
                        str +=
                            '<option value="' +
                            item.id +
                            '">' +
                            item.name +
                            "</option>";
                    });
                    $("#vendor_bill_city").html(str);
                },
            });
        } else {
            alert("No state selected!");
        }
    });

    //Fetch City for shipping
    const stateDropdownShipping = document.getElementById('vendor_ship_state');
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
                    $("#vendor_ship_city").empty();
                    var str = '<option value="">Select City</option>';
                    $.each(data, function(idx, item) {
                        str +=
                            '<option value="' +
                            item.id +
                            '">' +
                            item.name +
                            "</option>";
                    });
                    $("#vendor_ship_city").html(str);
                },
            });
        } else {
            alert("No state selected!");
        }
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
    $(document).ready(function() { 
        $("form#add_vendor_bank").on("submit", function(e) {

            e.preventDefault(); // Prevent default form submission

        // Required fields
        const requiredFields = [
            { id: 'gst_reg', name: 'GST Registration' },
            { id: 'vendor_name', name: 'Company Name' },
            { id: 'vendor_pan', name: 'PAN Number' },
            //{ id: 'vendor_email', name: 'Email' },
           // { id: 'vendor_phone', name: 'Phone Number' },
            { id: 'comp_type', name: 'Company Type' },
            // { id: 'cont_name', name: 'Contact Person Name' },
            // { id: 'cont_no', name: 'Contact Number' },
            // { id: 'cont_email', name: 'Contact Email' },

            // { id: 'vendor_bill_contact', name: 'Billing Contact Person Name' },
            // { id: 'vendor_bill_designa', name: 'Billing Designation' },
            // { id: 'vendor_bill_mobilno', name: 'Billing Mobile Number' },
            { id: 'vendor_bill_addone', name: 'Billing Address Line 1' },
            { id: 'comp_bill_state', name: 'Billing State' },
            { id: 'vendor_bill_city', name: 'Billing City' },
            { id: 'vendor_bill_pin', name: 'Billing Zip Code' },

            // { id: 'vendor_ship_contact', name: 'Shipping Contact Person Name' },
            // { id: 'vendor_ship_designa', name: 'Shipping Designation' },
            // { id: 'vendor_ship_mobilno', name: 'Shipping Mobile Number' },
            // { id: 'vendor_ship_addone', name: 'Shipping Address Line 1' },
            // { id: 'vendor_ship_state', name: 'Shipping State' },
            // { id: 'vendor_ship_city', name: 'Shipping City' },
            // { id: 'vendor_ship_pin', name: 'Shipping Zip Code' }
        ];

        // Check if 'cust_value' radio is selected
        const custValue = $("input[name='vendor_priority']:checked").val();
        if (!custValue) {
            showToast("Vendor Type (High/Low Valued) is required.", "error");
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
        const panNo = $("#vendor_pan").val().trim();
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (!panRegex.test(panNo)) {
            showToast("Invalid PAN Number. Format should be: AAAAA9999A", "error");
            $("#vendor_pan").focus();
            return false;
        }

        // Additional GST validation
        const gstReg = $("#gst_reg").val();
        if (gstReg === "Yes") {
            const gstNo = $("#vendor_gst_no").val().trim();
            const gstType = $("#vendor_gst_type").val();
            
            
            const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

            if (!gstNo) {
                showToast("GST Number is required.", "error");
                $("#vendor_gst_no").focus();
                return false;
            }

            if (!gstRegex.test(gstNo)) {
                showToast("Please enter a valid GST Number (e.g. 22AAAAA0000A1Z5).", "error");
                $("#vendor_gst_no").focus();
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
        // CIN and Incorporation Date validation removed - now optional fields
        if (regFieldId && regFieldId !== "cin" && regFieldId !== "llpin" && regFieldId !== "reg_no") {

            const regValue = $("#" + regFieldId).val().trim();

            if (!regValue) {
                showToast(`${regFieldName} is required.`, "error");
                $("#" + regFieldId).focus();
                return false;
            }

            if (regRegex && !regRegex.test(regValue)) {
                showToast(regErrorMsg, "error");
                $("#" + regFieldId).focus();
                return false;
            }
        }

        // ---- Other company type ----
        if (compType === "Other") {
            const otherComp = $("input[name='other_comp']").val();
            if (!otherComp || otherComp.trim() === "") {
                showToast("Custom Company Type is required.", "error");
                $("input[name='other_comp']").focus();
                return false;
            }
        }



            var formvendoromerData =
                $("form#add_vendor_detail").serialize() +
                "&" +
                $("form#add_vendor_bill").serialize() +
                "&" +
                $("form#add_vendor_bank").serialize();
            var vendorId = $("#vendorId").val();
            var suburl = vendorId === "" ? "/saveaddvendor" : "/update_vendor";

            $("#loader").show();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: suburl,
                type: "POST",
                data: formvendoromerData,
                success: function(response) {
                    $("#loader").hide();
                    $(".message-container").html(""); // Clear previous errors

                    if (response.class === "succ") {
                        showToast("Vendor Add Successfully", "success");
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000); // Redirect after 2s
                    } else {
                        $.each(response, function(idx, obj) {
                            // $(".message-container").append('<div class="err">' + obj + "</div>");
                            showToast("Error: " + obj, "error");
                        });
                    }
                },
                error: function() {
                    showToast("An error occurred while processing the request.", "error");
                }
            });
        });
    });

    //Previous & Next Button
    document.addEventListener("DOMContentLoaded", function() {
        // Get all next and previous buttons
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


    function copyBillingAddress_vendor() {
        const billingFields = [{
                id: "vendor_bill_gstno",
                copyTo: "vendor_ship_gstno"
            },
            {
                id: "vendor_bill_contact",
                copyTo: "vendor_ship_contact"
            },
            {
                id: "vendor_bill_designa",
                copyTo: "vendor_ship_designa"
            },
            {
                id: "vendor_bill_mobilno",
                copyTo: "vendor_ship_mobilno"
            },
            {
                id: "vendor_bill_addone",
                copyTo: "vendor_ship_addone"
            },
            {
                id: "vendor_bill_addtwo",
                copyTo: "vendor_ship_addtwo"
            },
            {
                id: "comp_bill_state",
                copyTo: "vendor_ship_state"
            },
            {
                id: "vendor_bill_pin",
                copyTo: "vendor_ship_pin"
            },
        ];

        billingFields.forEach((field) => {
            const billingInput = document.getElementById(field.id);
            const shippingInput = document.getElementById(field.copyTo);

            if (billingInput && shippingInput) {
                shippingInput.value = billingInput.value;

                // Trigger change event for dropdowns to ensure dynamic loading
                if (shippingInput.tagName === "SELECT") {
                    shippingInput.dispatchEvent(new Event("change"));
                }
            }
        });

        // Handle dynamic city dropdown update
        const billingState = document.getElementById("comp_bill_state");
        const shippingState = document.getElementById("vendor_ship_state");
        const billingCity = document.getElementById("vendor_bill_city");
        const shippingCity = document.getElementById("vendor_ship_city");

        if (billingState && shippingState) {
            shippingState.value = billingState.value;
            shippingState.dispatchEvent(new Event("change"));

            // Wait for the cities to be dynamically populated before selecting the copied city
            setTimeout(() => {
                if (billingCity && shippingCity) {
                    shippingCity.value = billingCity.value;
                    shippingCity.dispatchEvent(new Event("change"));
                }
            }, 500); // Delay to allow AJAX population
        }
    }
	
	//--------------For GST Details Fetching ----------------
	$('#get_gst_btn').click(function() {
		let gstin = $('#vendor_gst_no').val();
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

					// ================= Populate Basic Fields =================
					$('#vendor_gst_no').val(d.gstin || '');
					$('#vendor_name').val(d.tradeNam || d.lgnm);
					$('#comp_type').val(d.ctb); // Proprietorship / Pvt Ltd etc
					let fullAddress = "";

					// ================= GST Type Mapping =================
					let gstType = "";
					if (d.dty === "Regular") {
						gstType = "Register";
					} else if (d.dty === "Composition") {
						gstType = "QRMP";
					} else {
						gstType = "Un-Register";
					}

					$('.gstType').val(gstType).trigger('change');
					
					// ================== Contact Person ==================
					$('#cont_name').val(d.lgnm);

					// ================= Address Handling =================
					if (d.pradr && d.pradr.addr) {
						const a = d.pradr.addr;

						let address1 = `${a.bno || ''} ${a.bnm || ''}`.trim();
						let address2 = `${a.flno || ''} ${a.st || ''} ${a.loc || ''}`.trim();
						fullAddress = [a.bno, a.bnm, a.flno, a.st, a.loc, a.dst, a.stcd + " - " + a.pncd].filter(Boolean).join(', ');

						$('#vendor_bill_addone').val(address1);
						$('#vendor_bill_addtwo').val(address2);

						// Optional: auto fill pincode
						$('#vendor_bill_pin').val(a.pncd || '');
					}

					// ================= Modal (Optional Keep) =================
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
	
	$("#vendor_phone, #cont_no, #vendor_bill_mobilno, #vendor_ship_mobilno").on("input", function () {
		let value = this.value;
		value = value.replace(/\D/g, '');
		this.value = value.substring(0, 10);
	});
</script>
@endsection