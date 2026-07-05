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
                        <li class="breadcrumb-item" aria-current="page">Edit Customer & Receivables</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-customer-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Customer & Receivables</h2>
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
                                <form name="add_cust_detail" id="add_cust_detail" method="post" >
                                    <input type="hidden" name="id" id="custId" value="{{ $customer->id }}">
                                    <div class="row mt-4">
                                        <div class="col-md-12 mb-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="card shadow-sm border-0 p-3 m-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cust_value" id="highValueCustomers" value="1" <?php echo ($customer->cust_value=='1')? "checked":"" ?>>
                                                            <label class="form-check-label" for="highValueCustomers">High Valued Customers</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card shadow-sm border-0 p-3 m-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="cust_value" id="lowValueCustomers" value="2" <?php echo ($customer->cust_value=='2')? "checked":"" ?>>
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
                                                    <option value="Yes" <?php echo ($customer->gst_reg=='Yes')? "selected":"" ?>>Yes</option>
                                                    <option value="No" <?php echo ($customer->gst_reg=='No')? "selected":"" ?>>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" id="cust_gst_field" style="display: none">
                                            <label class="form-label">GST Number <span class="text-danger">*</span></label>
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control gst-input" name="cust_gst_no" id="cust_gst_no" value="{{ $customer->cust_gst_no}}" placeholder="Enter GST Number">
                                                    <button  id="get_gst_btn" class="btn btn-primary" type="button"><i class="ti ti-cloud-download align-middle"></i> Get Details</button>
                                                </div>
												<small class="gst-error text-danger d-none"></small>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" id="cust_gst_type" style="display: none">
                                            <div class="mb-3">
                                                <label class="form-label">GSTIN Type <span class="text-danger">*</span></label>
                                                <select class="form-control error" name="cust_gst_type" id="cust_gst_type" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                    <option label="select"></option>
                                                    <option value="Register" <?php echo ($customer->cust_gst_type=='Register')? "selected":"" ?>>Register</option>
                                                    <option value="QRMP" <?php echo ($customer->cust_gst_type=='QRMP')? "selected":"" ?>>QRMP</option>
                                                    <option value="Un-Register" <?php echo ($customer->cust_gst_type=='Un-Register')? "selected":"" ?>>Un-Register</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Company Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" required name="cust_name" id="cust_name" value="{{ $customer->cust_name}}" placeholder="Enter Company Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">PAN Number</label>
                                            <input type="text" class="form-control"  name="cust_pan" id="cust_pan" value="{{ $customer->cust_pan}}" placeholder="Enter PAN Number" style="text-transform:uppercase">
                                        </div>

                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Email</label>
                                            <input type="text" class="form-control"  name="cust_email" id="cust_email" value="{{ $customer->cust_email}}" placeholder="Enter Email Address">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Phone Number</label>
                                            <input type="text" class="form-control" name="cust_phone" id="cust_phone" value="{{ $customer->cust_phone}}" placeholder="Enter Phone Number">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Company Type <span class="text-danger">*</span></label>
                                                    <select class="select form-select company-type-dropdown" name="comp_type" id="comp_type" required>
                                                        <option value="" label="select"></option>
                                                        <option value="Proprietorship" <?php echo ($customer->comp_type=='Proprietorship')? "selected":"" ?>>Proprietorship</option>
                                                        <option value="Partnership" <?php echo ($customer->comp_type=='Partnership')? "selected":"" ?>>Partnership</option>
                                                        <option value="One person Company (OPC)" <?php echo ($customer->comp_type=='One person Company (OPC)')? "selected":"" ?>>One person Company (OPC)</option>
                                                        <option value="LLP Company" <?php echo ($customer->comp_type=='LLP Company')? "selected":"" ?>>LLP Company</option>
                                                        <option value="PVT Ltd Company" <?php echo ($customer->comp_type=='PVT Ltd Company')? "selected":"" ?>>PVT Ltd Company</option>
                                                        <option value="LTD Company" <?php echo ($customer->comp_type=='LTD Company')? "selected":"" ?>>LTD Company</option>
                                                        <option value="Section-8 Company" <?php echo ($customer->comp_type=='Section-8 Company')? "selected":"" ?>>Section-8 Company</option>
                                                        <option value="Society/Trust" <?php echo ($customer->comp_type=='Society/Trust')? "selected":"" ?>>Society/Trust</option>
                                                        <option value="Other" <?php echo ($customer->comp_type=='Other')? "selected":"" ?>>Other</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Enter Custom Company Type</label>
                                                    <input type="text" class="form-control" name="other_comp" id="other_comp" value="{{ $customer->other_comp}}" placeholder="Enter Company Type">
                                                </div>
                                            </div>
                                            <div id="company_reg_div" class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label id="company_reg_label" class="form-label">CIN <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"  name="cin" id="cin" value="{{ $customer->cin}}" placeholder="Enter CIN Number">
                                                </div>
                                            </div>
                                            <div id="inc_date_div" class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label id="inc_date_label" class="form-label">Incorporation Date <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="inc_date" id="inc_date" value="{{ $customer->inc_date}}" placeholder="Enter Incorporation Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <h4 class="mb-0">Contact Person Details</h4>
                                        </div>
                                        <div class="col-md-8 text-end">
                                            <a href="#" class="btn btn-primary"> Same as Above</a>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                            <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Person Name</label>
                                            <input type="text" class="form-control" name="cont_name" id="cont_name" value="{{ $customer->cont_name}}" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number</label>
                                            <input type="text" class="form-control" name="cont_no" id="cont_no" value="{{ $customer->cont_no}}" placeholder="Enter Contact Number">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">E-mail Address</label>
                                            <input type="text" class="form-control" name="cont_email" id="cont_email" value="{{ $customer->cont_email}}" placeholder="Enter E-mail Address">
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label" for="exampleFormControlTextarea1">Special Note</label>
                                            <textarea class="form-control" name="cont_notes" id="cont_notes" value="{{ $customer->cont_notes}}" rows="3" placeholder="Write Special Note"></textarea>
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
                                <form  method="post" name="add_cust_bill" id="add_cust_bill">
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
                                                        <input type="text" name="cust_bill_gstno" id="cust_bill_gstno" value="{{ $customer->cust_bill_gstno }}" class="form-control gst-input" placeholder="Enter GST No">
														<small class="gst-error text-danger d-none"></small>
													</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Person Name</label>
                                                            <input type="text" name="cust_bill_contact" id="cust_bill_contact" value="{{ $customer->cust_bill_contact }}" class="form-control" placeholder="Enter Contact Person Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation</label>
                                                        <input type="text" name="cust_bill_designa" id="cust_bill_designa" value="{{ $customer->cust_bill_designa }}"  class="form-control" placeholder="Enter Designation">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile No</label>
                                                        <input type="text" name="cust_bill_mobilno" id="cust_bill_mobilno" value="{{ $customer->cust_bill_mobilno }}" class="form-control" placeholder="Enter Mobile No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_addone" required id="cust_bill_addone" value="{{ $customer->cust_bill_addone }}" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="cust_bill_addtwo" id="cust_bill_addtwo" value="{{ $customer->cust_bill_addtwo }}" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="cust_bill_state" id="comp_bill_state" required>
                                                            <option value="">Select State</option>
                                                            @foreach($states as $state)
                                                                <option value="{{ $state->id }}" {{ $state->id == $customer->cust_bill_state ? 'selected' : '' }}>
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
                                                        <input type="hidden" id="selected_city" value="{{ $customer->cust_bill_city }}">
                                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="cust_bill_city" id="cust_bill_city" required>
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_pin" required id="cust_bill_pin" value="{{ $customer->cust_bill_pin }}" class="form-control" placeholder="Enter Zip Code">
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
                                                        <input type="text" name="cust_ship_gstno" id="cust_ship_gstno" value="{{ $customer->cust_ship_gstno }}" class="form-control gst-input" placeholder="Enter GST No">
														<small class="gst-error text-danger d-none"></small>
													</div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Person Name</label>
                                                        <input type="text" name="cust_ship_contact" id="cust_ship_contact" value="{{ $customer->cust_ship_contact }}" class="form-control" placeholder="Enter Contact Person Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation</label>
                                                        <input type="text" name="cust_ship_designa" id="cust_ship_designa" value="{{ $customer->cust_ship_designa }}" class="form-control" placeholder="Enter Designation">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile No</label>
                                                        <input type="text" name="cust_ship_mobilno" id="cust_ship_mobilno" value="{{ $customer->cust_ship_mobilno }}" class="form-control" placeholder="Enter Mobile No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1</label>
                                                        <input type="text" name="cust_ship_addone" id="cust_ship_addone" value="{{ $customer->cust_ship_addone }}" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2</label>
                                                        <input type="text" name="cust_ship_addtwo" id="cust_ship_addtwo" value="{{ $customer->cust_ship_addtwo }}" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State</label>
                                                        <select class="form-control select-style" name="cust_ship_state" id="cust_ship_state">
                                                            <option value="">Select State</option>
                                                            @foreach($states as $k=>$state)
                                                                <option value="{{ $state->id }}" {{ $state->id == $customer->cust_ship_state ? 'selected' : '' }}>{{ $state->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">City</label>
                                                        <select class="form-control" name="cust_ship_city" id="cust_ship_city">
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code</label>
                                                        <input type="text" name="cust_ship_pin" id="cust_ship_pin" value="{{ $customer->cust_ship_pin }}" class="form-control" placeholder="Enter Zip Code">
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
                                    @foreach ($bankDetails as $key => $bank)
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <h4>Bank Account </h4>
                                        </div>
                                        
                                    </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Bank Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cust_bank_name[]" id="cust_bank_name{{ $key + 1 }}" value="{{ $bank->cust_bank_name }}" placeholder="Enter Bank Name" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Branch<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cust_bank_branch[]" id="cust_bank_branch{{ $key + 1 }}" value="{{ $bank->cust_bank_branch }}" placeholder="Enter Branch" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Account Holder Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cust_bank_holder_name[]" id="cust_bank_holder_name{{ $key + 1 }}" value="{{ $bank->cust_bank_holder_name }}" placeholder="Enter Account Holder Name" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Account Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cust_ac_no[]" id="cust_ac_no{{ $key + 1 }}" value="{{ $bank->cust_ac_no }}" placeholder="Enter Account Number" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">IFSC Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="cust_ifsc_code[]" id="cust_ifsc_code{{ $key + 1 }}" value="{{ $bank->cust_ifsc_code }}" placeholder="Enter IFSC Code" required>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">UPI ID</label>
                                                <input type="text" class="form-control" name="cust_ac_upid[]" id="cust_ac_upid{{ $key + 1 }}" value="{{ $bank->cust_ac_upid }}" placeholder="Enter UPI ID">
                                            </div>
                                        </div>
                                    @endforeach
                                    <div id="bankFormContainer"></div>
                                    <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                            <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                            </a>
                                        </div>
                                        <div class="last">
                                            <button type='submit' id="nxtBtnVThree" class="btn btn-primary d-flex align-items-center justify-content-center">
                                                Save Changes <i class="ti ti-arrow-up-right-circle ms-2"></i>
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

    <script>
	
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
		
        //GST YES/ NO
        document.addEventListener("DOMContentLoaded", function () {
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
            gstDropdown.value = "{{ $customer->gst_reg }}"; // Default to Yes
            toggleGSTFields(gstDropdown.value);

            // Add event listener for changes
            gstDropdown.addEventListener("change", function () {
                toggleGSTFields(this.value);
            });
        });

        // Trigger company type change on load
        document.addEventListener("DOMContentLoaded", function () {
            const compTypeSelect = document.getElementById('comp_type');
            if (compTypeSelect.value) {
                compTypeSelect.dispatchEvent(new Event('change'));
            }
        });

        //Bank Account Addiition
        
        var existingBankCount = <?php echo json_encode(count($bankDetails)); ?>;


        document.addEventListener('DOMContentLoaded', function() {
            const maxAccounts = 3 - existingBankCount; // Maximum allowed accounts
            const bankFormContainer = document.getElementById('bankFormContainer');
            const addBankAccountButton = document.getElementById('addBankAccount');

            let initialCount = existingBankCount || 0; // Use the count from backend

            // Initialize with existing bank accounts
            const initializeBankAccounts = () => {
                for (let i = 1; i <= initialCount; i++) {
                    createBankAccount(i);
                }
                toggleAddButton();
            };

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
                        <input type="text" class="form-control" placeholder="Enter Bank Name">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control" placeholder="Enter Branch">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Account Holder Name</label>
                        <input type="text" class="form-control" placeholder="Enter Account Holder Name">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" placeholder="Enter Account Number">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" class="form-control" placeholder="Enter IFSC Code">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">UPI ID</label>
                        <input type="text" class="form-control" placeholder="Enter UPI ID">
                    </div>
                </div>
                `;

                // Add delete button functionality
                const deleteButton = bankForm.querySelector('.delete-bank');
                deleteButton.addEventListener('click', () => deleteBankAccount(bankForm));

                bankFormContainer.appendChild(bankForm);
                toggleAddButton();
            };

            const deleteBankAccount = (bankForm) => {
                bankForm.remove();
                updateAccountNumbers();
                toggleAddButton();
            };

            const updateAccountNumbers = () => {
                const bankForms = document.querySelectorAll('.bank-form');
                bankForms.forEach((form, index) => {
                    form.dataset.index = index + 1;
                    form.querySelector('h4').textContent = `Bank Account ${index + 1}`;
                });
            };

            const toggleAddButton = () => {
                const bankForms = document.querySelectorAll('.bank-form');
                if (bankForms.length >= maxAccounts) {
                    addBankAccountButton.disabled = true;
                    addBankAccountButton.style.cursor = "not-allowed";
                } else {
                    addBankAccountButton.disabled = false;
                    addBankAccountButton.style.cursor = "pointer";
                }
            };

            addBankAccountButton.addEventListener('click', () => {
                const bankForms = document.querySelectorAll('.bank-form');
                if (bankForms.length < maxAccounts) {
                    createBankAccount(bankForms.length + 1);
                }
            });

            initializeBankAccounts();
        });



        //Fetch City for billing

        const stateDropdown = $("#comp_bill_state");
        const cityDropdown = $("#cust_bill_city");
        const selectedCity = $("#selected_city").val(); 

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
        
        //Fetch City for shipping

        const stateDropdownShipping = $("#cust_ship_state");
        const cityDropdownShipping = $("#cust_ship_city");
        const selectedShipCity = "{{ $customer->cust_ship_city }}"; // Get preselected shipping city ID

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
		
		

        //Update the data
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
                { id: 'cust_bill_addone', name: 'Billing Address Line 1' },
                { id: 'comp_bill_state', name: 'Billing State' },
                { id: 'cust_bill_city', name: 'Billing City' },
                { id: 'cust_bill_pin', name: 'Billing Zip Code' }
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

            if (compType === "Other") {
                const otherComp = $("input[name='other_comp']").val();
                if (!otherComp || otherComp.trim() === "") {
                    showToast("Custom Company Type is required.", "error");
                    $("input[name='other_comp']").focus();
                    return false;
                }
            }


            
                //$('#addCustomerLoader').show();
                var formCustomerData =
                    $("form#add_cust_detail").serialize() +
                    "&" +
                    $("form#add_cust_bill").serialize() +
                    "&" +
                    $("form#add_cust_bank").serialize();
                var custId = $("#custId").val();
                //console.log(custId);
                if (custId == "") {
                    var suburl = "/add_customer";
                } else {
                    var suburl = "/update_customer";
                }
                //console.log($("form#add_cust_bank").serialize());
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
                        //console.log(response);
                        // $("#addCustomerLoader").hide();
                        if (response.class == "succ") {
                            showToast("Customer updated successfully!", "success");
                            // showToast(response.message, "success");
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                            // setTimeout(() => location.reload(), 2000); // Reload after 2s
                        } else {
                            showToast("Business Details Update: " + response.message, "error");
                        }
                        // if (response.class == "succ") {
                        //     //console.log(response);
                        //     //$("#add_cust_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                        //     window.location.href = response.redirect;
                        // } else {
                        //     $.each(response, function (idx, obj) {
                        //         $("#add_cust_bank .message-container").html(
                        //             '<div class="err">' + obj + "</div>"
                        //         );
                        //     });
                        // }
                    },
                });
            
        });

        //Previous & Next Button
        document.addEventListener("DOMContentLoaded", function () {
            // Get all next and previous buttons
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
						let address = "";

						if (d.pradr && d.pradr.addr) {
							const a = d.pradr.addr;
							address = `${a.bno || ''} ${a.bnm || ''}, ${a.flno || ''}, ${a.st || ''}, ${a.loc || ''}, ${a.dst || ''}, ${a.stcd || ''} - ${a.pncd || ''}`;
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

        function startEditCustomerTour() {
            if (typeof introJs !== 'function') return;

            let tour = introJs().setOptions({
                steps: [
                    {
                        title: 'Edit Customer Wizard Guide',
                        intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-edit" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Modify existing customer registration details, update billing/shipping addresses, and manage banking info.</p></div>'
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
                        element: 'a[href="#bankDetails"]',
                        title: 'Bank Details Tab',
                        intro: 'Register and manage bank account info, IFSC codes, and UPI IDs for this customer.'
                    },
                    {
                        element: '#nxtBtnVThree',
                        title: 'Save Customer Changes',
                        intro: 'After updating the details, click this button to submit and save the changes.'
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
            $('#start-edit-customer-tour').on('click', function(e) {
                e.preventDefault();
                startEditCustomerTour();
            });
        });
    </script>
@endsection
