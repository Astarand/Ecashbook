@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Sales & Revenue</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/sales-quotation') }}">Quotation</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Sales Quotation</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Create Sales Quotation</h2>
                    </div>
                </div>

            </div>
            @if ($quotation_create_status == "false")
                <div class="alert alert-danger" role="alert">
                    Unable to create invoice! Please complete your previous invoices first.
                </div>
            @endif

        </div>

    </div>
    <!-- [ breadcrumb ] end -->

    <form action="javascript:void(0);" method="post" name="addQuotationsFrmTop" id="addQuotationsFrmTop">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="inputEmail4">Quotation ID <span class="text-danger">*</span></label>
                        <input type="text" name="inv_num" id="inv_num" class="form-control" value="{{ $invoiceNo }}" readonly>
                    </div>
					<div class="mb-3 col-md-4">
						<label class="form-label">Proprietorship Company</label>
						<select name="propId" class="form-control">
							<option value="">{{ parentCompanyName() }}</option>
							@foreach($proprietorships as $company)
								<option value="{{ $company->id }}">
									{{ $company->comp_name }}
								</option>
							@endforeach
						</select>
					</div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                        <input type="date" name="inv_date" id="inv_date" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div id="employeeEnroll" class="form-wizard row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified">
                        <li class="nav-item" data-target-form="#sellerDetailForm">
                            <a href="#sellerDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Seller Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="#" class="nav-link icon-btn">
                                {{-- <a href="#customerDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"> --}}
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Customer Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#itemDetailForm">
                            {{-- <a href="#itemDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn"> --}}
                            <a href="#" class="nav-link icon-btn">
                                <i class="ph-duotone ph-archive-box"></i>
                                <span class="d-none d-sm-inline">Product / Service Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#" class="nav-link icon-btn">
                                {{-- <a href="#others" class="nav-link icon-btn"> --}}
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
                            <form action="javascript:void(0);" method="post" name="addQuotationsFrm" id="addQuotationsFrm" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="sId" value="">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_name" value="{{ $comp_name }}" id="seller_name" placeholder="Seller Name">
                                                <input type="hidden" name="comp_gst_reg" value="{{ $comp_gst_reg }}">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_contact" value="{{ $comp_phone }}" id="seller_contact" placeholder="Contact Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_email" value="{{ $comp_email }}" id="seller_email" placeholder="Email Address">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pan" value="{{ $comp_pan_no }}" id="seller_pan" placeholder="Pan Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">GST Number</label>
                                                <input type="text" class="form-control" value="{{ $comp_gst_no }}" name="seller_gst" id="seller_gst" placeholder="GST Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">URN Number</label>
                                                <input type="text" class="form-control" value="{{ $udyam_reg === 'Yes' ? $udyam_reg_no : '' }}" name="udyam_reg_no" id="udyam_reg_no" placeholder="URN Number">
                                            </div>
 
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addone" value="{{ $comp_bill_addone }}" id="seller_addone" placeholder="Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addtwo" value="{{ $comp_bill_addtwo }}" id="seller_addtwo" placeholder="Address Line 2">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                <select class="form-control select-style" name="seller_state" id="seller_state">
                                                    <option value="">Select State</option>
                                                    @foreach($states as $k=>$state)
                                                    <option value="{{ $state->id }}" <?php echo @($state->id == $compData[0]->comp_bill_state) ? "selected" : "" ?>>{{ $state->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                <select class="form-control" name="seller_city" id="city_id">
                                                    <option value="">Select City</option>
                                                    @foreach($cities_bill as $k=>$city)
                                                    <option value="{{ $city->id }}" <?php echo ($city->id == @$compData[0]->comp_bill_city) ? 'selected="selected"' : "" ?>>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pin" id="seller_pin" value="{{ $comp_bill_pin }}" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-container"></div>
                                <div id="addSalesLoader" class="loader"></div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="{{ url('/sales-quotation') }}" class="btn btn-secondary cancel me-2">Cancel</a>
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div> -->
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="{{ url('/sales-quotation') }}" class=" btn btn-danger mt-3 mt-md-0">
                                            <i class="ph-duotone ph-x-circle"></i> Cancel
                                        </a>
                                    </div>
                                    <div class="last">
                                        <button type="submit" class="btn btn-primary mt-3 mt-md-0">
                                            Save & Procced <i class="ph-duotone ph-arrow-circle-up-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class=" tab-pane" id="customerDetails">
                            <form id="address" method="post" action="#address">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                            <select class="form-control error" name="gst" id="select" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option label="Select Customer"></option>
                                                <option>Customer 1</option>
                                                <option>Customer 2</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Number" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" placeholder="Enter Email Address" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" placeholder="Enter Pan Number" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="InvoiceaddressType">Address Type<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="gst" id="InvoiceaddressType" required>
                                                <option value="billing" selected>Billing Address</option>
                                                <option value="shipping">Shipping Address</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="container">

                                    </div>
                                    <div class="row" id="billingAddress">
                                        <h5 class="text-muted mb-4">Billing Address</h5>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 2">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter City">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter State">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Pin Code">
                                        </div>
                                    </div>

                                    <div class="row d-none" id="shippingAddress">
                                        <h5 class="text-muted mb-4">Shipping Address</h5>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 2">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter City">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter State">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Pin Code">
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- end job detail tab pane -->
                        <div class="tab-pane" id="itemDetails">
                            <form method="post" action="#">
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Item Type<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="gst" id="select" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select Type"></option>
                                            <option>Product</option>
                                            <option>Services</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Product / Service Name<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="gst" id="select" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option>Product 1 </option>
                                            <option>Product 2</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Billing Type<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="gst" id="select" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option>Product/ Service Billing </option>
                                            <option>Goverment Payment</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">HSN / SAC Code<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter HSN / SAC Code">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST Rate<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter GST Rate">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">GST Transaction Mode<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="gst" id="select" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option>Intra State</option>
                                            <option>Inter State</option>
                                            <option>Union Territory</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST(%) Allocation<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter GST(%) Allocation">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Discount on selling Price<span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="input-group mb-0">
                                                <input type="text" class="form-control has-success" name="disc_sell" id="disc_sell" value="0" aria-label="Selling Price" placeholder="Discount" aria-invalid="false" style="width: 60%;">
                                                <select class="form-select has-success" name="disc_sell_type" id="disc_sell_type" aria-label="Select Action" aria-invalid="false" style="width: 40%;">
                                                    <option value="percentage" selected="">Percentage</option>
                                                    <option value="amount">Amount</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3"></div>
                                    <div class="col-md-6 mb-3 text-end">
                                        <button class="btn btn-outline-secondary me-3">Add These Item</button>
                                        <button class="btn btn-primary">Add Another Item</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive mb-2">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Product / Service</th>
                                                        <th>HSN /SAC</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Discount</th>
                                                        <th>Total Amount</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>PHP Website</td>
                                                        <td>998314</td>
                                                        <td><input type="text" class="form-control" placeholder="Quantity" value="8"></td>
                                                        <td><input type="text" class="form-control" placeholder="Item Price" value="50"></td>
                                                        <td>₹50</td>
                                                        <td>₹8000</td>
                                                        <td class="text-center">
                                                            <a href="#" class="avtar avtar-s btn-link-primary btn-pc-default" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"><i class="ti ti-pencil f-20"></i></a>
                                                            <span data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                                                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="ti ti-trash f-20"></i></a>
                                                            </span>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="col-12">
                                    <div class="invoice-total ms-auto">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Discount On Grand Total</label>
                                                    <input type="text" class="form-control" value="₹">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">Sub Total :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹20.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">Discount :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end text-success">₹10.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">CGST :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹5.000</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">IGST :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹5.000</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹25.00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputEmail4">Signeture<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Name">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputEmail4">Upload Signeture<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" placeholder="Enter Name">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end education detail tab pane -->
                        <div class=" tab-pane" id="others">
                            <form action="javascript:void(0);" method="post">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Mode of Payment<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select name="mode_of_pay" id="mode_of_pay" class="form-select has-success" aria-invalid="false">
                                                    <option value="">Select</option>
                                                    <option value="IMPS" selected="">IMPS</option>
                                                    <option value="RTGS">RTGS</option>
                                                    <option value="NEFT">NEFT</option>
                                                    <option value="UPI">UPI</option>
                                                    <option value="CARD">Credit/Debit Card</option>
                                                    <option value="CASH">Cash</option>
                                                    <option value="OTHER">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3" id="other_payment_div" style="display: none;">
                                        <div class="form-group">
                                            <label>Specify Other Payment Method<span class="text-danger">*</span></label>
                                            <input type="text" value="" class="form-control" name="other_payment" id="other_payment" placeholder="Specify Other Payment Method">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Payment Status<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="form-select has-success" name="pay_status" id="pay_status" aria-invalid="false">
                                                    <option value="">Select</option>
                                                    <option value="Full">Full Payment</option>
                                                    <option value="Partial">Partial Payment</option>
                                                    <option value="Due" selected="">Due</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="partial" style="display: none;">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group ">
                                                <label>Total Amount<span class="text-danger">*</span></label>
                                                <input type="text" name="total_amount" id="total_amount" value="0" class="form-control" placeholder="Total Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group ">
                                                <label>Advance Amount<span class="text-danger">*</span></label>
                                                <input type="text" name="advance_amount" id="advance_amount" value="0" class="form-control" placeholder="Advance Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group ">
                                                <label>Due Amount<span class="text-danger">*</span></label>
                                                <input type="text" name="due_amount" id="due_amount" value="0" class="form-control" placeholder="Due Amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Buyer's Order Number</label>
                                            <input type="text" name="buyer_orderno" id="buyer_orderno" value="1234545" class="form-control" placeholder="Buyer's Order Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Order Date<span class="text-danger">*</span></label>
                                            <input type="date" name="order_date" id="order_date" value="2024-11-06" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Suppliers Ref No.</label>
                                            <input type="text" name="supplier_refno" id="supplier_refno" value="1233445" class="form-control" placeholder="Suppliers Ref No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Others Ref No.</label>
                                            <input type="text" name="other_refno" id="other_refno" value="56" class="form-control" placeholder="Others Ref No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Dispatch Document No.</label>
                                            <input type="text" name="dispa_docno_one" id="dispa_docno_one" value="adgff4" class="form-control" placeholder="Dispatch Document No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Dispatch trough<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="form-select" name="disp_through" id="disp_through">
                                                    <option value="">Select</option>
                                                    <option value="Road Transportation">Road
                                                        Transportation</option>
                                                    <option value="Rail Transportation">Rail
                                                        Transportation</option>
                                                    <option value="Air Transportation">Air
                                                        Transportation</option>
                                                    <option value="Sea Transportation">Sea
                                                        Transportation</option>
                                                    <option value="Multi model Transportation">Multi
                                                        model Transportation</option>
                                                    <option value="Parcel &amp; Courier Service" selected="">Parcel &amp;
                                                        Courier Service</option>
                                                    <option value="By Hand">By Hand</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3" style="display: none;" id="other_dispatch">
                                        <div class="form-group ">
                                            <label>Other Dispatch Details</label>
                                            <input type="text" name="other_dispa_det" id="other_dispa_det" value="" class="form-control" placeholder="Other Dispatch Details" disabled="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3 description-box">
                                        <div class="form-group" id="summernote_container">
                                            <label class="form-control-label">Terms of Delivery</label>
                                            <textarea class="summernote form-control" name="terms_delivery" id="terms_delivery" placeholder="Write Terms of Delivery" rows="3">sggh</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END: Define your tab pans here -->
                        <!-- START: Define your controller buttons here-->
                        <!-- <div class="d-flex wizard justify-content-between mt-3">
                            <div class="first">
                                <a href="javascript:void(0);" class="btn btn-secondary previous-btn ">
                                    Back To Previous
                                </a>
                            </div>
                            <div class="last">
                               
                                <button type="button" id="nxtBtnVThree" class="btn btn-secondary next-btn">Next Step</button>

                                 
                            </div>
                        </div>-->
                        <!-- END: Define your controller buttons here-->
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>

<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete These Service/ Product</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-primary">
                                Delete
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary paid-cancel-btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addressTypeDropdown = document.getElementById("InvoiceaddressType");

        if (addressTypeDropdown) {
            addressTypeDropdown.addEventListener("change", function() {
                const selectedValue = this.value;
                const billingAddress = document.getElementById("billingAddress");
                const shippingAddress = document.getElementById("shippingAddress");

                // Handle visibility based on the selected value
                if (selectedValue === "billing") {
                    billingAddress.classList.remove("d-none");
                    shippingAddress.classList.add("d-none");
                } else if (selectedValue === "shipping") {
                    billingAddress.classList.add("d-none");
                    shippingAddress.classList.remove("d-none");
                } else if (selectedValue === "both") {
                    billingAddress.classList.remove("d-none");
                    shippingAddress.classList.remove("d-none");
                }
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const dispatchDropdown = document.getElementById("disp_through");
        const otherDispatchDiv = document.getElementById("other_dispatch");
        const otherDispatchInput = document.getElementById("other_dispa_det");

        if (dispatchDropdown) {
            dispatchDropdown.addEventListener("change", function() {
                if (this.value === "Other") {
                    otherDispatchDiv.style.display = "block"; // Show the "Other Dispatch Details" div
                    otherDispatchInput.disabled = false; // Enable the input field
                } else {
                    otherDispatchDiv.style.display = "none"; // Hide the "Other Dispatch Details" div
                    otherDispatchInput.disabled = true; // Disable the input field
                }
            });
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        const paymentStatusDropdown = document.getElementById("pay_status");
        const partialRow = document.getElementById("partial");

        if (paymentStatusDropdown) {
            paymentStatusDropdown.addEventListener("change", function() {
                if (this.value === "Partial") {
                    partialRow.style.display = "flex"; // Show the row
                } else {
                    partialRow.style.display = "none"; // Hide the row
                }
            });

            // Initialize visibility on page load
            if (paymentStatusDropdown.value === "Partial") {
                partialRow.style.display = "flex";
            } else {
                partialRow.style.display = "none";
            }
        }

        //- ------------- Fetch City for billing -----------

        const stateDropdown = document.getElementById('seller_state');
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
                        $("#city_id").empty();
                        var str = '<option value="">Select City</option>';
                        $.each(data, function(idx, item) {
                            str +=
                                '<option value="' +
                                item.id +
                                '">' +
                                item.name +
                                "</option>";
                        });
                        $("#city_id").html(str);
                    },
                });
            } else {
                alert("No state selected!");
            }
        });




        // Correct form submission event
        $("form#addQuotationsFrm").on("submit", function(e) {
            // alert('hh');
            e.preventDefault(); // Prevent default form submission

            // if ($("#addQuotationsFrm").valid()) { // Correct validation check
            $("#loader").show();
            var sId = $("#sId").val();
            var surl = sId === "" ? "/save_quotation_invoice" : "/update_quotation_invoice";

            var salesData = $("form#addQuotationsFrmTop").serialize() + "&" + $("form#addQuotationsFrm").serialize();

            $.ajax({
                url: surl,
                type: "POST",
                data: salesData,
                success: function(response) {
                    $("#loader").hide();
                    if (response.class === "succ") {
                        if (sId === "") {
                            showToast("Quotation Create Successfully", "success");
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 2000);

                        } else {
                            $("#tab-A").removeClass("active");
                            $("#tab-B").addClass("active");
                            $("#basic").hide();
                            $("#customer").show().addClass("show active");
                        }
                    } else {
                        var errorHtml = "";
                        $.each(response, function(idx, obj) {
                            // errorHtml += '<div class="err">' + obj + "</div>";
                            showToast("Error: " + obj, "error");
                        });
                        // $("#addQuotationsFrm .message-container").html(errorHtml);
                    }
                }
            });
            //}
        });
        // });



    });
</script>
@endsection