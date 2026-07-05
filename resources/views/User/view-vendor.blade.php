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
                            <li class="breadcrumb-item active" aria-current="page">View Vendor</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">View Vendor</h2>
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
                                <form  action="javascript:void(0);" method="POST" name="add_vendor_detail" id="add_vendor_detail">
                                    <input type="hidden" name="id" id="vendorId" value="{{ $vendor->id }}">
                                    @csrf
                                    <div class="row mt-4">
                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="card shadow-sm border-0 p-3 m-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="vendor_priority" id="highValueCustomers" value="1" <?php echo ($vendor->vendor_priority=='1')? "checked":"" ?>>
                                                            <label class="form-check-label" for="highValueCustomers">High Valued Customers</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card shadow-sm border-0 p-3 m-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="vendor_priority" id="lowValueCustomers" value="2" <?php echo ($vendor->vendor_priority=='2')? "checked":"" ?>>
                                                            <label class="form-check-label" for="lowValueCustomers">Low Valued Customers</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label" for="inputEmail4">Company GST Registered?<span class="text-danger">*</span></label>
                                            <div class="form-group me-2">
                                                <select class="select form-select" name="gst_reg" id="gst_reg">
                                                    <option value="">Select</option>
                                                    <option value="Yes" <?php echo ($vendor->gst_reg=='Yes')? "selected":"" ?>>Yes</option>
                                                    <option value="No" <?php echo ($vendor->gst_reg=='No')? "selected":"" ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" id="cust_gst_field" style="display: none">
                                            <label class="form-label">GST Number <span class="text-danger">*</span></label>
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="vendor_gst_no" id="vendor_gst_no" value="{{ $vendor->vendor_gstin}}" placeholder="Enter GST Number">
                                                    <button class="btn btn-primary" type="button"><i class="ti ti-cloud-download align-middle"></i> Get Details</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" id="vendor_gst_type" style="display: none">
                                            <div class="mb-3">
                                                <label class="form-label">GSTIN Type <span class="text-danger">*</span></label>
                                                <select class="form-control error" name="vendor_gst_type" id="vendor_gst_type" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                    <option label="select"></option>
                                                    <option value="Register" <?php echo ($vendor->vendor_gst_type=='Register')? "selected":"" ?>>Register</option>
                                                    <option value="QRMP" <?php echo ($vendor->vendor_gst_type=='QRMP')? "selected":"" ?>>QRMP</option>
                                                    <option value="Un-Register" <?php echo ($vendor->vendor_gst_type=='Un-Register')? "selected":"" ?>>Un-Register</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Company Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="vendor_name" id="vendor_name" value="{{ $vendor->vendor_name }}" placeholder="Enter Company Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">PAN Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"  name="vendor_pan" id="vendor_pan" value="{{ $vendor->vendor_pan}}" placeholder="Enter PAN Number">
                                        </div>

                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="vendor_email" id="vendor_email" value="{{ $vendor->vendor_email}}" placeholder="Enter Email Address">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Phone Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="vendor_phone" id="vendor_phone" value="{{ $vendor->vendor_phone}}" placeholder="Enter Phone Number">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Company Type <span class="text-danger">*</span></label>
                                                    <select class="select form-select company-type-dropdown" name="comp_type" id="comp_type" required>
                                                        <option value="" label="select"></option>
                                                        <option value="Proprietorship" <?php echo ($vendor->comp_type=='Proprietorship')? "selected":"" ?>>Proprietorship</option>
                                                        <option value="Partnership" <?php echo ($vendor->comp_type=='Partnership')? "selected":"" ?>>Partnership</option>
                                                        <option value="One person Company (OPC)" <?php echo ($vendor->comp_type=='One person Company (OPC)')? "selected":"" ?>>One person Company (OPC)</option>
                                                        <option value="LLP Company" <?php echo ($vendor->comp_type=='LLP Company')? "selected":"" ?>>LLP Company</option>
                                                        <option value="PVT Ltd Company" <?php echo ($vendor->comp_type=='PVT Ltd Company')? "selected":"" ?>>PVT Ltd Company</option>
                                                        <option value="LTD Company" <?php echo ($vendor->comp_type=='LTD Company')? "selected":"" ?>>LTD Company</option>
                                                        <option value="Section-8 Company" <?php echo ($vendor->comp_type=='Section-8 Company')? "selected":"" ?>>Section-8 Company</option>
                                                        <option value="Society/Trust" <?php echo ($vendor->comp_type=='Society/Trust')? "selected":"" ?>>Society/Trust</option>
                                                        <option value="Other" <?php echo ($vendor->comp_type=='Other')? "selected":"" ?>>Other</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Enter Custom Company Type <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required name="other_comp" id="other_comp" value="{{ $vendor->other_comp}}" placeholder="Enter Company Type">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">CIN <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"  name="cin" id="cin" value="{{ $vendor->cin}}" placeholder="Enter CIN Number">
                                                </div>
                                            </div>
                                            <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Incorporation Date</label>
                                                    <input type="date" class="form-control" name="inc_date" id="inc_date" value="{{ $vendor->inc_date}}" placeholder="Enter Incorporation Date">
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
                                            <label class="form-label" for="inputEmail4">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cont_name" id="cont_name" value="{{ $vendor->cont_per_name}}" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cont_no" id="cont_no" value="{{ $vendor->cont_per_number}}" placeholder="Enter Contact Number">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">E-mail Address<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cont_email" id="cont_email" value="{{ $vendor->cont_per_email}}" placeholder="Enter E-mail Address">
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label" for="exampleFormControlTextarea1">Special Note</label>
                                            <textarea class="form-control" name="cont_notes" id="cont_notes" value="{{ $vendor->special_note}}" rows="3" placeholder="Write Special Note"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- end contact detail tab pane -->
                            <div class="tab-pane" id="billingDetails">
                                <form action="javascript:void(0);" method="post" name="add_vendor_bill" id="add_vendor_bill">
                                    @csrf
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
                                                        <input type="text" name="vendor_bill_gstno" id="vendor_bill_gstno" value="{{ $vendor->cust_bill_gstno }}" class="form-control" placeholder="Enter GST No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_bill_contact" id="vendor_bill_contact" value="{{ $vendor->cust_bill_contact }}" class="form-control" placeholder="Enter Contact Person Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_bill_designa" id="vendor_bill_designa" value="{{ $vendor->cust_bill_designa }}"  class="form-control" placeholder="Enter Designation">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_bill_mobilno" id="vendor_bill_mobilno" value="{{ $vendor->cust_bill_mobilno }}" class="form-control" placeholder="Enter Mobile No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_bill_addone" id="vendor_bill_addone" value="{{ $vendor->billing_address1 }}" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2 <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_bill_addtwo" id="vendor_bill_addtwo" value="{{ $vendor->billing_address2 }}" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="vendor_bill_state" id="comp_bill_state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}" {{ $state->id == $vendor->billing_state ? 'selected' : '' }}>
                                                                    {{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        {{-- <select class="form-control select-style" name="cust_bill_state" id="comp_bill_state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                                <option value="{{ $state->id }}" >{{ $state->name }}</option>
                                                            @endforeach
                                                        </select> --}}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <input type="hidden" id="selected_city" value="{{ $vendor->billing_city }}">
                                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="vendor_bill_city" id="vendor_bill_city">
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_bill_pin" id="vendor_bill_pin" value="{{ $vendor->billing_pincode }}" class="form-control" placeholder="Enter Zip Code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Shipping Address -->
                                        <div class="col-lg-6 col-sm-12">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5>Shipping Address</h5>
                                                <div class="btn btn-primary" onclick="copyBillingAddress()">Same as Billing Address</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">GST No (For Alternate GST)</label>
                                                        <input type="text" name="vendor_ship_gstno" id="vendor_ship_gstno" value="{{ $vendor->cust_ship_gstno }}" class="form-control" placeholder="Enter GST No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_ship_contact"  id="vendor_ship_contact" value="{{ $vendor->cust_ship_contact }}" class="form-control" placeholder="Enter Contact Person Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_ship_designa" id="vendor_ship_designa" value="{{ $vendor->cust_ship_designa }}" class="form-control" placeholder="Enter Designation">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                                        <input type="text" name="vendor_ship_mobilno"  id="vendor_ship_mobilno" value="{{ $vendor->cust_ship_mobilno }}" class="form-control" placeholder="Enter Mobile No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1</label>
                                                        <input type="text" name="vendor_ship_addone" id="vendor_ship_addone" value="{{ $vendor->shipping_address1 }}" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="vendor_ship_addtwo" id="vendor_ship_addtwo" value="{{ $vendor->shipping_address2 }}" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State</label>
                                                        <select class="form-control select-style" name="vendor_ship_state" id="vendor_ship_state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                                <option value="{{ $state->id }}" {{ $state->id == $vendor->shipping_state ? 'selected' : '' }}>{{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City</label>
                                                        <select class="form-control" name="vendor_ship_city" id="vendor_ship_city">
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code</label>
                                                        <input type="text" name="vendor_ship_pin" id="vendor_ship_pin" value="{{ $vendor->shipping_pincode }}" class="form-control" placeholder="Enter Zip Code">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- end job detail tab pane -->
                            <div class="tab-pane" id="bankDetails">
                                <form action="javascript:void(0);" method="post" name="add_vendor_bank" id="add_vendor_bank">
                                    @csrf
                                    
                                    @foreach ($bankDetails as $key => $bank)
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <h4>Bank Account </h4>
                                        </div>
                                        
                                    </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Bank Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bank_name[]" id="bank_name{{ $key + 1 }}" value="{{ $bank->bank_name }}" placeholder="Enter Bank Name" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Branch<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="bank_branch[]" id="bank_branch{{ $key + 1 }}" value="{{ $bank->bank_branch }}" placeholder="Enter Branch" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Account Holder Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="acc_holder_name[]" id="acc_holder_name{{ $key + 1 }}" value="{{ $bank->acc_holder_name }}" placeholder="Enter Account Holder Name" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Account Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="acc_number[]" id="acc_number{{ $key + 1 }}" value="{{ $bank->acc_number }}" placeholder="Enter Account Number" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">IFSC Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="acc_ifsc[]" id="acc_ifsc{{ $key + 1 }}" value="{{ $bank->acc_ifsc }}" placeholder="Enter IFSC Code" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">UPI ID</label>
                                                <input type="text" class="form-control" name="acc_upi_id[]" id="acc_upi_id{{ $key + 1 }}" value="{{ $bank->acc_upi_id }}" placeholder="Enter UPI ID">
                                            </div>
                                        </div>
                                    @endforeach
                                

                                    <div id="bankFormContainer"></div>

                                    <div class="row align-items-center">
                                        <div class="col-md-12 text-end">
                                            <a href="{{ route('user.VendorList') }}" class="btn btn-secondary">Cancel</a>
                                            {{-- <button type="button" id="addBankAccount" class="btn btn-primary me-2">Add Another Account</button> --}}
                                            {{-- <button type="submit" id="nxtBtnVThree" class="btn btn-secondary">Add Customer</button> --}}
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

    <script>
        //GST YES/ NO
        document.addEventListener("DOMContentLoaded", function () {
            // Select the GST Registration dropdown
            const gstDropdown = document.querySelector("#gst_reg");

            // Get the related fields
            const gstField = document.querySelector("#cust_gst_field");
            const gstTypeField = document.querySelector("#vendor_gst_type");

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
            gstDropdown.value = "{{ $vendor->gst_reg }}"; // Default to Yes
            toggleGSTFields(gstDropdown.value);

            // Add event listener for changes
            gstDropdown.addEventListener("change", function () {
                toggleGSTFields(this.value);
            });
        });

        //Bank Account Addiition
        document.addEventListener('DOMContentLoaded', function() {
            const maxAccounts = 3;
            const bankFormContainer = document.getElementById('bankFormContainer');
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
                    <label class="form-label">Bank Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="bank_name[]" id="vendor_bank_name1" placeholder="Enter Bank Name">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Branch<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="bank_branch[]" id="vendor_bank_branch1" placeholder="Enter Branch">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Account Holder Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="acc_holder_name[]" id="vendor_bank_holder_name1" placeholder="Enter Account Holder Name">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Account Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="acc_number[]" id="vendor_ac_no1" placeholder="Enter Account Number">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">IFSC Code<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="acc_ifsc[]" id="vendor_ifsc_code1" placeholder="Enter IFSC Code">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">UPI ID</label>
                    <input type="text" class="form-control" name="acc_upi_id[]" id="vendor_ac_upid1" placeholder="Enter UPI ID">
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

        //- ------------- Fetch City for billing -----------

        const stateDropdown = $("#comp_bill_state");
        const cityDropdown = $("#cust_bill_city");
        const selectedCity = $("#selected_city").val(); // Get preselected city ID

        function fetchCities(stateId, preselectedCity = null) {
            if (stateId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: "/getCity?" + stateId,
                    dataType: "json",
                    data: { id: stateId },
                    success: function (data) {
                        cityDropdown.empty();
                        let options = '<option value="">Select City</option>';
                        
                        $.each(data, function (idx, item) {
                            options += `<option value="${item.id}" ${item.id == preselectedCity ? 'selected' : ''}>${item.name}</option>`;
                        });

                        cityDropdown.html(options);
                    },
                });
            } else {
                cityDropdown.html('<option value="">Select City</option>');
            }
        }

        // Fetch cities on state change
        stateDropdown.change(function () {
            fetchCities($(this).val());
        });

        // On page load, check if a state is preselected and fetch cities
        if (stateDropdown.val()) {
            fetchCities(stateDropdown.val(), selectedCity);
        }
        
        //- ------------- Fetch City for shipping -----------

        const stateDropdownShipping = $("#vendor_ship_state");
        const cityDropdownShipping = $("#vendor_ship_city");
        const selectedShipCity = "{{ $vendor->billing_city }}"; // Get preselected shipping city ID

        function fetchShippingCities(stateId, preselectedCity = null) {
            if (stateId) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: "/getCity?" + stateId,
                    dataType: "json",
                    data: { id: stateId },
                    success: function (data) {
                        cityDropdownShipping.empty();
                        let options = '<option value="">Select City</option>';

                        $.each(data, function (idx, item) {
                            options += `<option value="${item.id}" ${item.id == preselectedCity ? 'selected' : ''}>${item.name}</option>`;
                        });

                        cityDropdownShipping.html(options);
                    },
                });
            } else {
                cityDropdownShipping.html('<option value="">Select City</option>');
            }
        }

        // Fetch cities on state change
        stateDropdownShipping.change(function () {
            fetchShippingCities($(this).val());
        });

        
        if (stateDropdownShipping.val()) {
            fetchShippingCities(stateDropdownShipping.val(), selectedShipCity);
        }

        //------------- Update the data -------------
        $("form#add_vendor_bank").bind("submit", function () {
            
            //$('#addvendoromerLoader').show();
            var formvendoromerData =
                $("form#add_vendor_detail").serialize() +
                "&" +
                $("form#add_vendor_bill").serialize() +
                "&" +
                $("form#add_vendor_bank").serialize();
            var vendorId = $("#vendorId").val();
            //console.log(vendorId);
            if (vendorId == "") {
                var suburl = "/saveaddvendor";
            } else {
                var suburl = "/update_vendor";
            }
            //console.log($("form#add_vendor_bank").serialize());
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: suburl,
                type: "POST",
                data: formvendoromerData,
                success: function (response) {
                    //console.log(response);
                    // $("#addvendoromerLoader").hide();
                    if (response.class == "succ") {
                        //console.log(response);
                        //$("#add_vendor_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                        window.location.href = response.redirect;
                    } else {
                        $.each(response, function (idx, obj) {
                            $("#add_vendor_bank .message-container").html(
                                '<div class="err">' + obj + "</div>"
                            );
                        });
                    }
                },
            });
        
});

    </script>
@endsection
