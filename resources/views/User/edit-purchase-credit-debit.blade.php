@extends('App.Layout')

@section('container')

<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.PurchaseCreditDebit') }}">Credit & Debit Note</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Credit/Debit Note</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-purchase-credit-debit-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Purchase Credit Debit</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row align-item-center mb-4">
        <h2 class="text-muted">Edit Purchase Credit Debit</h2>
    </div>
    <div class="card">
        <div class="card-body">
        <form action="javascript:void(0);" method="post" name="purchaseCreditDebitFrm" id="purchaseCreditDebitFrm" enctype="multipart/form-data">
			<input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
			@csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="inv_num" id="inv_num" value="{{ $sales->inv_number }}" readonly required="">
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                    <input type="date" name="inv_date" id="inv_date" value="{{ $sales->inv_date }}" class="form-control" placeholder="Invoice Number" required="">
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
                            <a href="javascript:void(0);" id="tab-A" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Buyer Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="javascript:void(0);" id="tab-B" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Seller Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="javascript:void(0);" id="tab-C" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
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
                        <div class="tab-pane show active" id="buyerDetail">
                            <form action="javascript:void(0);"  method="POST" name="add_buyer_detail" id="add_buyer_detail">
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                
                                                <select class="form-control" name="v_name" id="invNameCustomer" onchange="changeCustomer();">
                                                        <option value="">Select</option>
														@foreach($custData as $k=>$cust)
															<option value="{{ $cust->id }}"<?php echo @($cust->id==$sales->v_name)? "selected":"" ?> >{{ $cust->cust_name }}</option>
														@endforeach
                                                    </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" id="contact_no" name="contact_no" class="form-control" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" id="cust_email" name="cust_email" class="form-control" placeholder="Enter Email Address" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" id="cust_pan" name="cust_pan" class="form-control" placeholder="Enter Pan Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" id="cust_gst_no" name="cust_gst_no" class="form-control" placeholder="Enter GST Number" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" id="bill_addone" name="bill_addone" class="form-control" placeholder="Enter Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                <input type="text" id="bill_addtwo" name="bill_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="cust_state" id="cust_bill_state" class="form-control" placeholder="Enter State">
                                                    <option value="">Select State</option>
													@foreach($states_seller as $k=>$state)
														<option value="{{ $state->id }}" <?php echo ($state->id==$sales->seller_state)? "selected":"" ?>>{{ $state->name }}</option>
													@endforeach
												</select>
                                            </div> 
                                            <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="cust_city" id="cust_bill_city">
                                                        <option value="">Select City</option>
                                                    </select>
                                                </div> 
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" name="cust_pin" id="cust_bill_pin" class="form-control" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="{{url('/purchase-credit-debit')}}" class="btn btn-secondary">
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
                        <div class="tab-pane" id="sellerDetail">
                            <form action="javascript:void(0);" method="POST" name="add_seller_detail" id="add_seller_detail">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" name="seller_name" id="seller_name" value="{{ $sales->seller_name }}"  class="form-control" placeholder="Enter Company Name" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" name="seller_contact" id="seller_contact" value="{{ $sales->seller_contact }}" class="form-control" placeholder="Enter Contact Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" name="seller_email" id="seller_email" value="{{ $sales->seller_email }}" class="form-control" placeholder="Enter Email Address" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" id="" name="" class="form-control" value="{{$comp_pan}}" placeholder="Enter Pan Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" id="seller_gst" name="seller_gst" value="{{$comp_gst}}"  class="form-control" placeholder="Enter GST Number" readonly>
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

												<option value="Purchase Return (Full/Partial Return of Goods)"
													{{ ($sales->reason_issuance == 'Purchase Return (Full/Partial Return of Goods)') ? 'selected' : '' }}>
													Purchase Return (Full/Partial Return of Goods)
												</option>

												<option value="Post-Purchase Discount Received"
													{{ ($sales->reason_issuance == 'Post-Purchase Discount Received') ? 'selected' : '' }}>
													Post-Purchase Discount Received
												</option>

												<option value="Price Reduction after Purchase"
													{{ ($sales->reason_issuance == 'Price Reduction after Purchase') ? 'selected' : '' }}>
													Price Reduction after Purchase
												</option>

												<option value="Defective/Damaged Goods Returned to Supplier"
													{{ ($sales->reason_issuance == 'Defective/Damaged Goods Returned to Supplier') ? 'selected' : '' }}>
													Defective/Damaged Goods Returned to Supplier
												</option>

												<option value="Wrong Item Supplied by Supplier (Returned)"
													{{ ($sales->reason_issuance == 'Wrong Item Supplied by Supplier (Returned)') ? 'selected' : '' }}>
													Wrong Item Supplied by Supplier (Returned)
												</option>

												<option value="Excess Quantity Supplied (Returned)"
													{{ ($sales->reason_issuance == 'Excess Quantity Supplied (Returned)') ? 'selected' : '' }}>
													Excess Quantity Supplied (Returned)
												</option>

												<option value="GST Rate Change (Downward Revision)"
													{{ ($sales->reason_issuance == 'GST Rate Change (Downward Revision)') ? 'selected' : '' }}>
													GST Rate Change (Downward Revision)
												</option>

												<option value="Cancellation of Purchase Order"
													{{ ($sales->reason_issuance == 'Cancellation of Purchase Order') ? 'selected' : '' }}>
													Cancellation of Purchase Order
												</option>

												<option value="Short Supply Adjustment (Vendor Over-Invoiced)"
													{{ ($sales->reason_issuance == 'Short Supply Adjustment (Vendor Over-Invoiced)') ? 'selected' : '' }}>
													Short Supply Adjustment (Vendor Over-Invoiced)
												</option>

												<option value="Overcharging Correction by Supplier"
													{{ ($sales->reason_issuance == 'Overcharging Correction by Supplier') ? 'selected' : '' }}>
													Tax Adjustment
												</option>

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
                        <div class="tab-pane" id="others">
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
												<div class="downloadFile pb-3"><a target="_blank" href="{{ asset('uploads/purchase-credit-debit/'.$sales->voucher_doc) }}">Download</a></div>
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
                                        <button type="submit" id="nxtBtnVThree" class="btn btn-secondary">
                                                Update</button>
                                            </a>
                                        </div>
								</div>
								<br/>
								<div class="message-container"></div>
                            </form>
                        
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
<script>
    /*document.getElementById("imageUpload").addEventListener("change", function() {
        const fileInput = this;
        const previewBox = document.getElementById("imagePreview");
        const uploadedImage = document.getElementById("uploadedImage");
        const downloadLink = document.getElementById("downloadLink");

        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                uploadedImage.src = e.target.result; // Set the preview image source
                downloadLink.href = e.target.result; // Set the download link
                previewBox.style.display = "block"; // Show the preview box
            };

            reader.readAsDataURL(file); // Read the file as a Data URL
        } else {
            previewBox.style.display = "none"; // Hide the preview box if no file is selected
        }
    });*/
    $(document).ready(function () {
           // alert('hi');
        var base_url = "{{ url('/') }}";
        

            $("#nxtBtnVOne").on("click", function () { 
               // alert('hello');              
                $("#tab-A").removeClass("active");
                $("#tab-B").addClass("active");
                // Activate the Customer Details tab
                $("#buyerDetail").hide();
                $("#sellerDetail").show();
                $("#sellerDetail").addClass("show");
                $("#sellerDetail").addClass("active");
            });

            $("#nxtBtnVTwo").on("click", function () {
                $("#tab-B").removeClass("active");
                $("#tab-C").addClass("active");

                $("#sellerDetail").hide();
                $("#others").show();
                $("#others").addClass("show");
                $("#others").addClass("active");
            });
            $("#prevBtnTwo").on("click", function () {
                $("#tab-B").removeClass("active");
                $("#tab-A").addClass("active");

                $("#sellerDetail").hide();
                $("#buyerDetail").show();
                $("#buyerDetail").addClass("show");
                $("#buyerDetail").addClass("active");
            });
            $("#prevBtnThree").on("click", function () {
                $("#tab-C").removeClass("active");
                $("#tab-B").addClass("active");

                $("#others").hide();
                $("#sellerDetail").show();
                $("#sellerDetail").addClass("show");
                $("#sellerDetail").addClass("active");
            });


            $("form#add_other_detail").bind("submit", function () {
				$("#loader").show();
                /*var formpurcrdrData=  $("form#purchaseCreditDebitFrm").serialize() +
                "&" +  $("form#add_buyer_detail").serialize() +
                    "&" +
                    $("form#add_seller_detail").serialize() +
                    "&" +
                    $("form#add_other_detail").serialize();*/
					
				var formData = new FormData();
				$.each($("form#purchaseCreditDebitFrm").serializeArray(), function(i, field) {
					formData.append(field.name, field.value);
				});

				$.each($("form#add_buyer_detail").serializeArray(), function(i, field) {
					formData.append(field.name, field.value);
				});

				$.each($("form#add_seller_detail").serializeArray(), function(i, field) {
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
                    
                var pId = $("#sId").val();
                //alert(carriageId);
                if (pId == "") {
                    var suburl = base_url + "/save_purchase_invoice_creditdebit";
                } else {
                    var suburl = base_url + "/update_purchase_invoice_creditdebit";
                }
                $.ajax({
                    url: suburl,
                    type: "POST",
                    data: formData,
					processData: false, // IMPORTANT
					contentType: false, // IMPORTANT
                    success: function (response) {
                        //alert(response);
                        $("#loader").hide();
                        if (response.class == "succ") {
                            //alert('Helwo');
                            //$("#add_vendor_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                            window.location.href = response.redirect;
                        } else {
                            //alert('Hello');
                            $("form#add_other_detail .message-container").html('');
							let html = '<div class="alert alert-danger"><ul class="mb-0">';
							$.each(response, function (field, messages) {
								html += '<li>' + messages[0] + '</li>';
							});

							html += '</ul></div>';
							$("form#add_other_detail .message-container").html(html);
                        }
                    },
                });
           
        });

    });

    function startEditPurchaseCreditDebitTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Purchase Credit/Debit Note Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Modify credit or debit adjustments for supplier purchases.</p></div>'
                },
                {
                    title: 'Edit Purchase Credit/Debit Note',
                    intro: 'Modify credit or debit adjustments for supplier purchases.'
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
        }).start();
    }

    $(document).ready(function() {
        $('#start-edit-purchase-credit-debit-tour').on('click', function(e) {
            e.preventDefault();
            startEditPurchaseCreditDebitTour();
        });
    });
</script>
@endsection