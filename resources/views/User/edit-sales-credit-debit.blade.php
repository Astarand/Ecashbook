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
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Sales & Revenue</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.SalesCreditDebit') }}">Credit & Debit Note</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Credit/Debit Note</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Credit/Debit Note</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row align-item-center mb-4">
        <h2 class="text-muted">Edit Credit Debit</h2>
    </div>
    <div class="card">
        <div class="card-body">
        <form action="javascript:void(0);" method="POST" name="add_invoice_detail" id="add_invoice_detail">
        <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
        @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <input type="text" name="inv_num" id="inv_num" value="{{ $sales->invoice_number }}" class="form-control" readonly required="">

                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                    <input type="date" name="inv_date" id="inv_date" value="{{ $sales->inv_date }}" class="form-control" placeholder="Invoice Number" readonly>
                </div>
            </div>
        </form>
        </div>
    </div>
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item" data-target-form="#sellerDetailForm">
                            <a href="#sellerDetail" id="tab-A" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Seller Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="#customerDetails" id="tab-B" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Customer Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#others" id="tab-C" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-accessible"></i>
                                <span class="d-none d-sm-inline">Other Details</span>
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
                        <div class="tab-pane show active" id="sellerDetail">
                         <form action="javascript:void(0);" method="POST" name="add_seller_detail" id="add_seller_detail">
                            <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" id="seller_name" name="seller_name" value="{{ $sales->seller_name }}" class="form-control" placeholder="" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" id="seller_contact" name="seller_contact" value="{{$compData->comp_phone}}" class="form-control" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" id="seller_email" name="seller_email" value="{{$compData->comp_email}}" class="form-control" placeholder="Enter Email Address" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" id="seller_pan" name="seller_pan" value="{{$compData->comp_pan_no}}" class="form-control" placeholder="Enter Pan Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" id="seller_gst" name="seller_gst" value="{{$compData->gst_no}}" class="form-control" placeholder="Enter GST Number" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" id="seller_addone" name="seller_addone" value="{{ $sales->seller_addone }}" class="form-control" placeholder="Enter Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                <input type="text" id="seller_addtwo" name="seller_addtwo" value="{{ $sales->seller_addtwo }}" class="form-control" placeholder="Enter Address Line 2">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="seller_state" id="seller_state">
													<option value="">Select State</option>
													@foreach($states_seller as $k=>$state)
														<option value="{{ $state->id }}"<?php echo ($state->id==$sales->seller_state)? "selected":"" ?> >{{ $state->name }}</option>
													@endforeach
												</select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="seller_city" id="city">
													<option value="">Select City</option>
													@foreach($cities_seller as $k=>$city)
														<option value="{{ $city->id }}"<?php echo ($city->id==$sales->seller_city)? "selected":"" ?> >{{ $city->name }}</option>
													@endforeach
												</select>
                                                </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" id="seller_pin" name="seller_pin" value="{{$compData->comp_bill_pin}}" class="form-control" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                            <div class="first">
                                <a href="{{url('/sale-credit-debit')}}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                            <div class="last">
                                <a href="javascript:void(0);" class="btn btn-secondary" id="nxtBtnVOne">
                                    Next Step
                                </a>
                            </div>
                           </div>
                        </form>
                    </div>
                        <!-- end contact detail tab pane -->
                        <div class=" tab-pane" id="customerDetails">
                        <form  action="javascript:void(0);" method="POST" name="add_customer_detail" id="add_customer_detail">
                        <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                            <select class="form-select" name="v_name" id="invNameCustomer" onchange="changeCustomer(this.value);">
                                                        <option value="">Select</option>
														@foreach($custData as $k=>$cust)
															<option value="{{ $cust->id }}" <?php echo @($cust->id==$sales->v_name)? "selected":"" ?> >{{ $cust->cust_name }}</option>
														@endforeach
                                                    </select>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" id="contact_no" name="contact_no" class="form-control" placeholder="Enter Contact Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" id="cust_email" name="cust_email" class="form-control" placeholder="Enter Email Address" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="taxt" id="cust_pan" name="cust_pan" class="form-control" placeholder="Enter Pan Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" id="cust_gst_no" name="cust_gst_no" class="form-control" placeholder="Enter GST Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="InvoiceaddressType">Note Type<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="note_type" id="note_type" required>
                                                <option>Select</option>
                                                <option value="Credit" <?php echo ($sales->note_type=="Credit")? "selected":"" ?>>Credit</option>
                                                <option value="Debit" <?php echo ($sales->note_type=="Debit")? "selected":"" ?>>Debit</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Date of Note<span class="text-danger">*</span></label>
                                            <input type="Date" id="note_date" name="note_date" value="{{ $sales->note_date }}" class="form-control" placeholder="Enter GST Number">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Reason for Issuance<span class="text-danger">*</span></label>
                                            <select class="form-select" name="reason_issuance" id="reason_issuance">
                                                <option value="">Select</option>                                                
                                                <option value="returns" <?php echo ($sales->reason_issuance=="returns")? "selected":"" ?>>Returns</option>
                                                        <option value="discount" <?php echo ($sales->reason_issuance=="discount")? "selected":"" ?>>Discount</option>
                                                        <option value="price_adjustment" <?php echo ($sales->reason_issuance=="price_adjustment")? "selected":"" ?>>Price Adjustment</option>
                                                        <option value="damage_goods" <?php echo ($sales->reason_issuance=="damage_goods")? "selected":"" ?>>Damage Goods</option>
                                                        <option value="incorrect_billing" <?php echo ($sales->reason_issuance=="incorrect_billing")? "selected":"" ?>>Incorrect Billing</option>
                                                        <option value="rebates" <?php echo ($sales->reason_issuance=="rebates")? "selected":"" ?>>Rebates</option>
                                                        <option value="cancelled_order" <?php echo ($sales->reason_issuance=="cancelled_order")? "selected":"" ?>>Cancelled Order</option>
                                                        <option value="good" <?php echo ($sales->reason_issuance=="good")? "selected":"" ?>>Good Not received</option>
                                                        <option value="transportation" <?php echo ($sales->reason_issuance=="transportation")? "selected":"" ?>>Transportation Charges</option>
                                                        <option value="tax" <?php echo ($sales->reason_issuance=="tax")? "selected":"" ?>>Tax Adjustment</option>
                                                        <option value="other" <?php echo ($sales->reason_issuance=="other")? "selected":"" ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="javascript:void(0);" id="prevBtnTwo" class="btn btn-secondary">
                                                Back To Previous
                                            </a>
                                        </div>
                                        <div class="last">
                                            <a href="javascript:void(0);" class="btn btn-secondary" id="nxtBtnVTwo">
                                                Next Step
                                            </a>
                                        </div>
                                    </div>
                            </form>
                        </div>
                        <!-- end job detail tab pane -->

                        <!-- end education detail tab pane -->
                        <div class=" tab-pane" id="others">
                        <form action="javascript:void(0);" method="POST" name="add_other_detail" id="add_other_detail" enctype="multipart/form-data">
								<div class="message-container"></div>
								
								<div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Date of Note Issuance</label>
                                        <input type="Date" value="{{ $sales->note_issuance_date }}" name="note_issuance_date" id="note_issuance_date" class="form-control" placeholder="Date of Note Issuance">
                                    </div>                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Voucher No.</label>
                                        <input type="text" value="{{ $sales->v_num }}" name="v_num" id="v_num" class="form-control" placeholder="Voucher No.">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Product / Service Name</label>
                                        <input type="text" value="{{ $sales->prodservname }}" name="prodservname" id="prodservname" class="form-control" placeholder="Product / Service Name">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">HSN/SAC Code</label>
                                        <input type="text" value="{{ $sales->hsn_sac_code }}" name="hsn_sac_code" id="hsn_sac_code" class="form-control" placeholder="HSN/SAC Code">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">GST Rate</label>
                                        <input type="text" value="{{ $sales->gst_rate }}" name="gst_rate" id="gst_rate" class="form-control" placeholder="GST Rate">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Taxable Value</label>
                                        <input type="text" value="{{ $sales->taxable_value }}" name="taxable_value" id="taxable_value" class="form-control" placeholder="Taxable Value">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">CGST Amount </label>
                                        <input type="text" value="{{ $sales->cgst_amount }}" name="cgst_amount" id="cgst_amount" class="form-control" placeholder="CGST Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SGST Amount</label>
                                        <input type="text" value="{{ $sales->sgst_amount }}" name="sgst_amount" id="sgst_amount" class="form-control" placeholder="SGST Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">IGST Amount</label>   
                                        <input type="text" value="{{ $sales->igst_amount }}" name="igst_amount" id="igst_amount" class="form-control" placeholder="IGST Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Total Amount </label>
                                        <input type="text" value="{{ $sales->total_amt }}" name="total_amt" id="total_amt" class="form-control" placeholder="Total Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Quantity Return/Adjusted</label>
                                        <input type="text" value="{{ $sales->qty_return_adjusted }}" name="qty_return_adjusted" id="qty_return_adjusted" class="form-control" placeholder="Quantity Return/Adjusted">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Rate / Unit Price</label> 
                                        <input type="text" value="{{ $sales->rate_unit_price }}" name="rate_unit_price" id="rate_unit_price" class="form-control" placeholder="Rate / Unit Price">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Discount</label>
                                        <input type="text" value="{{ $sales->discount }}" name="discount" id="discount" class="form-control" placeholder="Discount">
                                    </div> 
                                                                 
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Challan No.</label>
                                        <input type="text" value="{{ $sales->challan_no }}" name="challan_no" id="challan_no" class="form-control" placeholder="Challan No.">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Challan Date</label>
                                        <input type="Date" value="{{ $sales->challan_date }}" name="challan_date" id="challan_date" class="form-control" placeholder="Challan Date">
                                    </div>                                        
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Return Status </label>  
                                        <select class="form-select" name="return_status" id="return_status">
                                            <option value="Received" {{ $sales->return_status == 'Received' ? 'selected' : '' }} >Received</option>
                                            <option value="Pending" {{ $sales->return_status == 'Pending' ? 'selected' : '' }} >Pending</option>
                                            <option value="In Transit" {{ $sales->return_status == 'In Transit' ? 'selected' : '' }} >In Transit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transporter / Courier Name</label>     
                                        <input type="text" value="{{ $sales->transporter_name }}" name="transporter_name" id="transporter_name" class="form-control" placeholder="Transporter / Courier Name">
                                    </div>                        
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Remarks</label>
                                        <textarea name="term_condition" id="term_condition" class="summernote form-control" placeholder="Terms &amp; Condition" rows="1">{{ $sales->term_condition }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Upload Document</label>
                                        <input type="file" id="voucher_doc" name="voucher_doc" class="form-control" aria-label="file example">
                                        @if(@$sales->voucher_doc !="")
												<div class="downloadFile pb-3"><a target="_blank" href="{{ asset('uploads/sales-credit-debit/'.$sales->voucher_doc) }}">Download</a></div>
											@endif
                                    </div>
                                </div>
								<div class="d-flex wizard justify-content-between mt-3">
									<div class="first">
										<a href="javascript:void(0);" id="prevBtnThree" class="btn btn-secondary">
											Back To Previous
										</a>
									</div>
									<div class="last">
										<button type="submit" id="nxtBtnVThree" class="btn btn-secondary">Update</button>
									</div>
								</div>
								<br/>
								<div class="message-container"></div>
                            </form>
                        </div>
                        <!-- END: Define your tab pans here -->
                        <!-- START: Define your controller buttons here-->
                        
                        <!-- END: Define your controller buttons here-->
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function () {


    $("#nxtBtnVOne").on("click", function () {               
                $("#tab-A").removeClass("active");
                $("#tab-B").addClass("active");
                // Activate the Customer Details tab
                $("#sellerDetail").hide();
                $("#customerDetails").show();
                $("#customerDetails").addClass("show");
                $("#customerDetails").addClass("active");
            });

            $("#nxtBtnVTwo").on("click", function () {
                $("#tab-B").removeClass("active");
                $("#tab-C").addClass("active");

                $("#customerDetails").hide();
                $("#others").show();
                $("#others").addClass("show");
                $("#others").addClass("active");
            });
            $("#prevBtnTwo").on("click", function () {
                $("#tab-B").removeClass("active");
                $("#tab-A").addClass("active");

                $("#customerDetails").hide();
                $("#sellerDetail").show();
                $("#sellerDetail").addClass("show");
                $("#sellerDetail").addClass("active");
            });
            $("#prevBtnThree").on("click", function () {
                $("#tab-C").removeClass("active");
                $("#tab-B").addClass("active");

                $("#others").hide();
                $("#customerDetails").show();
                $("#customerDetails").addClass("show");
                $("#customerDetails").addClass("active");
            });

    $('#inv_num').on('change', function () {
        const invNum = this.value;
        //alert(invNum);
        var base_url = "{{ url('/') }}";

        if (invNum) {
            fetch(base_url + '/fetch-sales-details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ inv_num: invNum })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const sales = data.data;

                    // Populate fields only if elements exist
                    if (document.getElementById('inv_date')) {
                        document.getElementById('inv_date').value = sales.inv_date || '';
                    }
                    if (document.getElementById('seller_name')) {
                        document.getElementById('seller_name').value = sales.seller_name || '';
                    }

                    if (document.getElementById('seller_contact')) {
                        document.getElementById('seller_contact').value = sales.seller_contact || '';
                    }

                    if (document.getElementById('seller_email')) {
                        document.getElementById('seller_email').value = sales.seller_email || '';
                    }
                    if (document.getElementById('seller_pan')) {
                        document.getElementById('seller_pan').value = sales.seller_pan || '';
                    }
                    if (document.getElementById('seller_gst')) {
                        document.getElementById('seller_gst').value = sales.seller_gst || '';
                    }
                    
                    if (document.getElementById('seller_addone')) {
                        document.getElementById('seller_addone').value = sales.seller_addone || '';
                    }
                    if (document.getElementById('seller_addtwo')) {
                        document.getElementById('seller_addtwo').value = sales.seller_addtwo || '';
                    }
                    if (document.getElementById('seller_pin')) {
                        document.getElementById('seller_pin').value = sales.seller_pin || '';
                    }
                    	
                    // Check if dropdowns exist before setting values
                    const stateSelect = document.getElementById('seller_state');
                    if (stateSelect) {
                        const stateValue = sales.seller_state || ''; // Make sure this is the state ID
                        let optionFound = false;

                        // First, try selecting by value (ID)
                        Array.from(stateSelect.options).forEach(option => {
                            if (option.value == stateValue) { // Non-strict comparison for number-string match
                                option.selected = true;
                                optionFound = true;
                            }
                        });

                        // If the value didn't match, try selecting by text (state name)
                        if (!optionFound) {
                            Array.from(stateSelect.options).forEach(option => {
                                if (option.text.trim().toLowerCase() === sales.state_name.trim().toLowerCase()) {
                                    option.selected = true;
                                }
                            });
                        }
                    }



                    const citySelect = document.getElementById('city');
                    if (citySelect) {
                        citySelect.value = sales.seller_city || '';
                        if (!citySelect.value) {
                            Array.from(citySelect.options).forEach(option => {
                                if (option.text === sales.city_name) {
                                    option.selected = true;
                                }
                            });
                        }
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

	$("form#add_other_detail").on("submit", function(e) {
		e.preventDefault();

		var base_url = "{{ url('/') }}";

		var formData = new FormData();

		// Append all forms data
		$.each($("form#add_invoice_detail").serializeArray(), function(i, field) {
			formData.append(field.name, field.value);
		});

		$.each($("form#add_seller_detail").serializeArray(), function(i, field) {
			formData.append(field.name, field.value);
		});

		$.each($("form#add_customer_detail").serializeArray(), function(i, field) {
			formData.append(field.name, field.value);
		});

		$.each($("form#add_other_detail").serializeArray(), function(i, field) {
			formData.append(field.name, field.value);
		});

		// Append file
		var fileInput = document.getElementById('voucher_doc');
		if (fileInput.files.length > 0) {
			formData.append('voucher_doc', fileInput.files[0]);
		}

		var sId = $("#sId").val();
		var suburl = sId == ""
			? base_url + "/save_sales_invoice_creditdebit"
			: base_url + "/update_sales_invoice_creditdebit";

		$("#loader").show();

		$.ajax({
			url: suburl,
			type: "POST",
			data: formData,
			processData: false, // IMPORTANT
			contentType: false, // IMPORTANT
			success: function(response) {
				$("#loader").hide();

				if (response.class === "succ") {
					window.location.href = response.redirect;
				} else {
					$("form#add_other_detail .message-container").html('');
					let html = '<div class="alert alert-danger"><ul class="mb-0">';
					$.each(response, function (field, messages) {
						html += '<li>' + messages[0] + '</li>';
					});

					html += '</ul></div>';
					$("form#add_other_detail .message-container").html(html);
				}
			}
		});
	});

    /*$("form#add_other_detail").bind("submit", function () {
			var base_url = "{{ url('/') }}";
                var formSalesdebitData=  $("form#add_invoice_detail").serialize() +
                "&" +  $("form#add_seller_detail").serialize() +
                    "&" +
                    $("form#add_customer_detail").serialize() +
                    "&" +
                    $("form#add_other_detail").serialize();
                    console.log(formSalesdebitData);
                var sId = $("#sId").val();
                //alert(carriageId);
                if (sId == "") {
                    var suburl = base_url + "/save_sales_invoice_creditdebit";
                } else {
                    var suburl = base_url + "/update_sales_invoice_creditdebit";
                }
				$("#loader").show();
                $.ajax({
                    url: suburl,
                    type: "POST",
                    data: formSalesdebitData,
                    success: function (response) {
                        $("#loader").hide();
                        $("#addCustomerLoader").hide();
                        if (response.class == "succ") {
                            //alert('Helwo');
                            //$("#add_other_detail .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                            window.location.href = response.redirect;
                        } else {
                            $.each(response, function (idx, obj) {
                                $("form#add_other_detail .message-container").html(
                                    '<div class="err">' + obj + "</div>"
                                );
                            });
                        }
                    },
                });
           // }
        });*/

    
});

</script>