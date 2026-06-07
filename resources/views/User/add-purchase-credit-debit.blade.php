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
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.PurchaseCreditDebit') }}">Credit & Debit Note</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Credit/Debit Note</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row align-item-center mb-4">
        <h2 class="text-muted">Generate New Credit Debit</h2>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="javascript:void(0);" method="post" name="purchaseCreditDebitFrm" id="purchaseCreditDebitFrm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="pId" value="">
                @csrf
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                        <select class="form-control error" name="inv_num" id="inv_num" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                            <option label="Select Invoice"></option>
							@foreach($inv_number as $invoice)
                            <option value="{{ $invoice->inv_num }}">{{ $invoice->inv_num }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                        <input type="date" name="inv_date" id="inv_date" class="form-control" placeholder="Invoice Number">
                    </div>
                    <div class="mb-3 col-md-3">
							<label class="form-label" for="InvoiceaddressType">Note Type<span class="text-danger">*</span></label>
							<select class="form-control error" name="note_type" id="note_type" required>
								<option>Select</option>
								<option value="Credit">Credit</option>
								<option value="Debit">Debit</option>
							</select>
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
                                <span class="d-none d-sm-inline">Note Details</span>
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
                            <form action="javascript:void(0);" method="POST" name="add_buyer_detail" id="add_buyer_detail">
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>

                                                <select class="form-control" name="v_name" id="invNameCustomer" onchange="changeCustomer();">
                                                    <option value="">Select</option>
                                                    @foreach($custData as $k=>$cust)
                                                    <option value="{{ $cust->id }}">{{ $cust->cust_name }}</option>
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
                                                    @foreach($states_bill as $k=>$state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
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
                                            <input type="text" name="seller_name" id="seller_name" value="{{$comp_name}}" class="form-control" placeholder="Enter Company Name" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" name="seller_contact" id="seller_contact" value="{{$comp_phone}}" class="form-control" placeholder="Enter Contact Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" name="seller_email" id="seller_email" value="{{$comp_email}}" class="form-control" placeholder="Enter Email Address" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" id="" name="" class="form-control" value="{{$comp_pan}}" placeholder="Enter Pan Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" id="seller_gst" name="seller_gst" value="{{$comp_gst}}" class="form-control" placeholder="Enter GST Number" readonly>
                                        </div>                                        
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">Date of Note<span class="text-danger">*</span></label>
                                            <input type="Date" id="note_date" name="note_date" class="form-control" placeholder="Enter GST Number">
                                        </div>
                                        <div class="col-md-6 mb-6">
                                            <label class="form-label">Reason for Issuance<span class="text-danger">*</span></label>
                                            <select class="form-select" name="reason_issuance">
                                                <option value="">Select</option>
                                                <option value="Purchase Return (Full/Partial Return of Goods)">Purchase Return (Full/Partial Return of Goods)</option>
                                                <option value="Post-Purchase Discount Received">Post-Purchase Discount Received</option>
                                                <option value="Price Reduction after Purchase">Price Reduction after Purchase</option>
                                                <option value="Defective/Damaged Goods Returned to Supplier">Defective/Damaged Goods Returned to Supplier</option>
                                                <option value="Wrong Item Supplied by Supplier (Returned)">Wrong Item Supplied by Supplier (Returned)</option>
                                                <option value="Excess Quantity Supplied (Returned)">Excess Quantity Supplied (Returned)</option>
                                                <option value="GST Rate Change (Downward Revision)">GST Rate Change (Downward Revision)</option>
                                                <option value="Cancellation of Purchase Order">Cancellation of Purchase Order</option>
                                                <option value="Short Supply Adjustment (Vendor Over-Invoiced)">Short Supply Adjustment (Vendor Over-Invoiced)</option>
                                                <option value="Overcharging Correction by Supplier">Tax Adjustment</option>                                                
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
                                        <input type="Date" name="note_issuance_date" id="note_issuance_date" class="form-control" placeholder="Date of Note Issuance">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Voucher No.</label>
                                        <input type="text" name="v_num" id="v_num" class="form-control" placeholder="Voucher No.">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Product / Service Name</label>
                                        <input type="text" name="prodservname" id="prodservname" class="form-control" placeholder="Credit &amp; Debit Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">HSN/SAC Code</label>
                                        <input type="text" name="sachsncode" id="sachsncode" class="form-control" placeholder="Adjusted Amout">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">GST Rate</label>
                                        <input type="text" name="gst_rate" id="gst_rate" class="form-control" placeholder="GST Rate">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Taxable Value</label>
                                        <input type="text" name="taxable_value" id="taxable_value" class="form-control" placeholder="Taxable Value">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">CGST Amount</label>
                                        <input type="text" name="cgst_amount" id="cgst_amount" class="form-control" placeholder="CGST Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">SGST Amount</label>
                                        <input type="text" name="sgst_amount" id="sgst_amount" class="form-control" placeholder="SGST Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">IGST Amount</label>
                                        <input type="text" name="igst_amount" id="igst_amount" class="form-control" placeholder="IGST Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Total Amount</label>
                                        <input type="text" name="total_amt" id="total_amt" class="form-control" placeholder="Total Amount">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Quantity Return/Adjusted</label>
                                        <input type="text" name="qty_return_adjusted" id="qty_return_adjusted" class="form-control" placeholder="Quantity Return/Adjusted">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Rate / Unit Price</label>
                                        <input type="text" name="rate_unit_price" id="rate_unit_price" class="form-control" placeholder="Rate / Unit Price">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Discount</label>
                                        <input type="text" name="discount" id="discount" class="form-control" placeholder="Discount">
                                    </div>  
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Transporter / Courier Name</label>
                                        <input type="text" name="transporter_name" id="transporter_name" class="form-control" placeholder="Transporter / Courier Name">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Challan No.</label>
                                        <input type="text" name="challan_no" id="challan_no" class="form-control" placeholder="Challan No.">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Challan Date</label>
                                        <input type="Date" name="challan_date" id="challan_date" class="form-control" placeholder="Challan Date">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Terms of Delivery</label>
                                        <textarea name="terms_delivery" id="terms_delivery" class="summernote form-control" placeholder="Description of Goods" rows="3"></textarea>
                                    </div>                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="imageUpload">Upload Image</label>
                                            <input type="file" id="voucher_doc" name="voucher_doc" id="imageUpload" class="form-control" accept="image/*">
                                        </div>
                                        <div id="imagePreview" class="mt-3" style="display: none; text-align: center;">
                                            <a id="downloadLink" href="#" download="uploaded_image.jpg">
                                                <img id="uploadedImage"  src="" alt="Preview Image" style="max-width: 100%; cursor: pointer; border: 1px solid #ddd; padding: 10px;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
    
									<a href="javascript:void(0);" id="prevBtnThree" class="btn btn-secondary">
										Back To Previous
									</a>

									<button type="submit" id="nxtBtnVThree" class="btn btn-secondary">
										Save
									</button>

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
    $(document).ready(function() {
        // alert('hi');
        var base_url = "{{ url('/') }}";


        $("#nxtBtnVOne").on("click", function() {
            // alert('hello');              
            $("#tab-A").removeClass("active");
            $("#tab-B").addClass("active");
            // Activate the Customer Details tab
            $("#buyerDetail").hide();
            $("#sellerDetail").show();
            $("#sellerDetail").addClass("show");
            $("#sellerDetail").addClass("active");
        });

        $("#nxtBtnVTwo").on("click", function() {
            $("#tab-B").removeClass("active");
            $("#tab-C").addClass("active");

            $("#sellerDetail").hide();
            $("#others").show();
            $("#others").addClass("show");
            $("#others").addClass("active");
        });
        $("#prevBtnTwo").on("click", function() {
            $("#tab-B").removeClass("active");
            $("#tab-A").addClass("active");

            $("#sellerDetail").hide();
            $("#buyerDetail").show();
            $("#buyerDetail").addClass("show");
            $("#buyerDetail").addClass("active");
        });
        $("#prevBtnThree").on("click", function() {
            $("#tab-C").removeClass("active");
            $("#tab-B").addClass("active");

            $("#others").hide();
            $("#sellerDetail").show();
            $("#sellerDetail").addClass("show");
            $("#sellerDetail").addClass("active");
        });
		
		
		$('#inv_num').on('change', function() {
            const invNum = this.value;
            //alert(invNum);
            var base_url = "{{ url('/') }}";

            if (invNum) {
                fetch(base_url + '/fetch-purchases-details', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            inv_num: invNum
                        })
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


        $("form#add_other_detail").bind("submit", function() {
            $("#loader").show();           
            /*var formpurcrdrData = $("form#purchaseCreditDebitFrm").serialize() +
                "&" + $("form#add_buyer_detail").serialize() +
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

            var pId = $("#pId").val();
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
                success: function(response) {
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
</script>
@endsection