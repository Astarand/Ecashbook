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
                        <li class="breadcrumb-item"><a href="{{ url('/customer-list') }}">Customer & Receivables</a></li>
                        <li class="breadcrumb-item" aria-current="page">View Customer & Receivables</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Customer & Receivables</h2>
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
                                                <select class="select form-select" name="gst_reg" id="gst_reg">
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
                                                    <input type="text" class="form-control" name="cust_gst_no" id="cust_gst_no" value="{{ $customer->cust_gst_no}}" placeholder="Enter GST Number">
                                                    <button class="btn btn-primary" type="button"><i class="ti ti-cloud-download align-middle"></i> Get Details</button>
                                                </div>
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
                                            <input type="text" class="form-control" name="cust_name" id="cust_name" value="{{ $customer->cust_name}}" placeholder="Enter Company Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">PAN Number</label>
                                            <input type="text" class="form-control"  name="cust_pan" id="cust_pan" value="{{ $customer->cust_pan}}" placeholder="Enter PAN Number">
                                        </div>

                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cust_email" id="cust_email" value="{{ $customer->cust_email}}" placeholder="Enter Email Address">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Phone Number<span class="text-danger">*</span></label>
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
                                                    <label class="form-label">Enter Custom Company Type <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required name="other_comp" id="other_comp" value="{{ $customer->other_comp}}" placeholder="Enter Company Type">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">CIN <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"  name="cin" id="cin" value="{{ $customer->cin}}" placeholder="Enter CIN Number">
                                                </div>
                                            </div>
                                            <div id="comp_type_other" class="col-sm-4 company-type-input" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Incorporation Date</label>
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
                                            <label class="form-label" for="inputEmail4">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cont_name" id="cont_name" value="{{ $customer->cont_name}}" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cont_no" id="cont_no" value="{{ $customer->cont_no}}" placeholder="Enter Contact Number">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">E-mail Address<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cont_email" id="cont_email" value="{{ $customer->cont_email}}" placeholder="Enter E-mail Address">
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label" for="exampleFormControlTextarea1">Special Note</label>
                                            <textarea class="form-control" name="cont_notes" id="cont_notes" value="{{ $customer->cont_notes}}" rows="3" placeholder="Write Special Note"></textarea>
                                        </div>
                                    </div>
                                </form>
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
                                                        <input type="text" name="cust_bill_gstno" id="cust_bill_gstno" value="{{ $customer->cust_bill_gstno }}" class="form-control" placeholder="Enter GST No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_contact" id="cust_bill_contact" value="{{ $customer->cust_bill_contact }}" class="form-control" placeholder="Enter Contact Person Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_designa" id="cust_bill_designa" value="{{ $customer->cust_bill_designa }}"  class="form-control" placeholder="Enter Designation">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_mobilno" id="cust_bill_mobilno" value="{{ $customer->cust_bill_mobilno }}" class="form-control" placeholder="Enter Mobile No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_addone" id="cust_bill_addone" value="{{ $customer->cust_bill_addone }}" class="form-control" placeholder="Enter Address Line 1">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Address Line 2 <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_addtwo" id="cust_bill_addtwo" value="{{ $customer->cust_bill_addtwo }}" class="form-control" placeholder="Enter Address Line 2">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">State <span class="text-danger">*</span></label>
                                                        <select class="form-control select-style" name="cust_bill_state" id="comp_bill_state">
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
                                                        <select class="form-control" name="cust_bill_city" id="cust_bill_city">
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-0">
                                                        <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_bill_pin" id="cust_bill_pin" value="{{ $customer->cust_bill_pin }}" class="form-control" placeholder="Enter Zip Code">
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
                                                        <input type="text" name="cust_ship_gstno" id="cust_ship_gstno" value="{{ $customer->cust_ship_gstno }}" class="form-control" placeholder="Enter GST No">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_ship_contact"  id="cust_ship_contact" value="{{ $customer->cust_ship_contact }}" class="form-control" placeholder="Enter Contact Person Name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_ship_designa" id="cust_ship_designa" value="{{ $customer->cust_ship_designa }}" class="form-control" placeholder="Enter Designation">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                                        <input type="text" name="cust_ship_mobilno"  id="cust_ship_mobilno" value="{{ $customer->cust_ship_mobilno }}" class="form-control" placeholder="Enter Mobile No">
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
                            </div>

                            <!-- end job detail tab pane -->
                            <div class="tab-pane" id="bankDetails">
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

                                    <div class="row align-items-center">
                                        <div class="col-md-12 text-end">
                                            <a href="{{ route('user.CustomerList') }}" class="btn btn-secondary"> Cancel </a>
                                            {{-- <button type="button" id="addBankAccount" class="btn btn-primary me-2">Add Another Account</button>
                                            <button type="submit" id="nxtBtnVThree" class="btn btn-secondary">Add Customer</button> --}}
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
                    <input type="text" class="form-control" placeholder="Enter Bank Name">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Branch<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Branch">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Account Holder Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Account Holder Name">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">Account Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Account Number">
                </div>
                <div class="mb-3 col-md-4">
                    <label class="form-label">IFSC Code<span class="text-danger">*</span></label>
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

        //------------- Update the data -------------
        $("form#add_cust_bank").bind("submit", function () {
            
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
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    url: suburl,
                    type: "POST",
                    data: formCustomerData,
                    success: function (response) {
                        //console.log(response);
                        $("#addCustomerLoader").hide();
                        if (response.class == "succ") {
                            //console.log(response);
                            //$("#add_cust_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                            window.location.href = response.redirect;
                        } else {
                            $.each(response, function (idx, obj) {
                                $("#add_cust_bank .message-container").html(
                                    '<div class="err">' + obj + "</div>"
                                );
                            });
                        }
                    },
                });
            
        });

    </script>
@endsection
