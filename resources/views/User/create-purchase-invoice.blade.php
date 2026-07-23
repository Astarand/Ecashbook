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
                        <li class="breadcrumb-item"><a href="">Accounting & Finance</a></li>
                        <li class="breadcrumb-item"><a href="">Purchase & Procurement</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.PurchaseInvoices') }}">Purchase Invoice</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Purchase Invoice</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-create-purchase-invoice-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Create Purchase Invoice</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    
    <div class="card">
        <div class="card-body">
        <form action="javascript:void(0);" method="post" name="addPurchaseFrmTop" id="addPurchaseFrmTop">
        @csrf
            <div class="row">
                <div class="mb-3 col-md-3">
                    <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <!--<input type="text" name="inv_num" id="inv_num" class="form-control" value="{{ $invoiceNo }}" placeholder="Invoice Number" required="" readonly>-->
                    <input type="text" name="inv_num" id="inv_num" class="form-control"  placeholder="e.g. PI/25-26/0001" required="" >
                </div>
				
				<div class="mb-3 col-md-3">
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
				<div class="mb-3 col-md-3">
					<label class="form-label" for="inv_num">PO Ref Number</label>
					<select name="po_ref_num" id="po_ref_num" class="form-control">
						<option value="">Select PO Ref Number</option>
						@foreach($pos as $po)
							<option value="{{ $po->inv_num }}">
								{{ $po->inv_num }}
							</option>
						@endforeach
					</select>
				</div>
                <div class="mb-3 col-md-3">
                    <label class="form-label" for="inv_date">Date<span class="text-danger">*</span></label>
                    <input type="date" name="inv_date" id="inv_date" class="form-control" placeholder="Invoice Number">
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
                            <a href="#buyerDetail"  class="nav-link active">
                            <!--<a href="#buyerDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">-->
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Purchaser / Buyer Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="#"  class="nav-link icon-btn">
                            <!--<a href="#sellerDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">-->
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Seller / Vendor Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#itemDetailForm">
                            <a href="#"  class="nav-link icon-btn">
                            <!--<a href="#itemDetails" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">-->
                                <i class="ph-duotone ph-archive-box"></i>
                                <span class="d-none d-sm-inline">Product / Service Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#"  class="nav-link icon-btn">
                            <!--<a href="#others" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">-->
                                <i class="ti ti-accessible"></i>
                                <span class="d-none d-sm-inline">Payments & Other</span>
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
							<form action="javascript:void(0);" method="post" name="addPurchaseFrm" id="addPurchaseFrm" enctype="multipart/form-data">
							<input type="hidden" name="id" id="sId" value="">
							@csrf
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" name="seller_name" id="seller_name" value="{{ $comp_name }}" class="form-control" placeholder="" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_contact" id="seller_contact" value="{{ $comp_phone }}" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address</label>
                                                <input type="email" class="form-control" name="seller_email" id="seller_email" value="{{ $comp_email }}" placeholder="Enter Email Address" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Pan Number</label>
                                                <input type="text" class="form-control" name="seller_pan" id="seller_pan" value="{{ $comp_pan_no }}" placeholder="Enter Pan Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">GST Number</label>
                                                <input type="text" class="form-control" name="seller_gst" id="seller_gst" value="{{ $gst_no }}" placeholder="Enter GST Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Contact Person Name</label>
                                                <input type="text" class="form-control" value="{{ $comp_bill_name }}"  name="seller_person_name" id="seller_person_name" placeholder="Enter Contact Name">
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Contact Person Number</label>
                                                <input type="text" class="form-control" value="{{ $comp_bill_mobile_no }}" name="seller_person_no" id="seller_person_no" placeholder="Enter Contact Person Number">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addone" value="{{ $comp_bill_addone }}" id="seller_addone" placeholder="Enter Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 2</label>
                                                <input type="text" class="form-control" name="seller_addtwo" id="seller_addtwo" value="{{ $comp_bill_addtwo }}" placeholder="Enter Address Line 2">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                <select class="form-control error" name="seller_state" id="state" onChange="changeState(this);" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                    <option value="">Select State</option>                                           
                                                    @foreach($states_bill as $k=>$state)
                                                    <option value="{{ $state->id }}" <?php echo
                                                                        @($state->id==$comp_bill_state)?
                                                                        "selected":"" ?>>{{ $state->name }}</option>
											        @endforeach
                                            </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                <select class="form-control error" name="seller_city" id="city" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option value="">Select City</option>
                                                @foreach($cities_bill as $k=>$city)
                                                <option value="{{ $city->id }}" <?php echo ($city->id==$comp_bill_city)?
                                                                        'selected="selected"':"" ?>>{{ $city->name }}
                                                                    </option>
											    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" name="seller_pin" id="seller_pin" value="{{$comp_bill_pin}}" class="form-control" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex wizard justify-content-between mt-3">
									<div class="first">
										<a href="{{url('/purchase-invoices')}}" class="btn btn-secondary">
										   Cancel
										</a>
									</div>
									<div class="last">
									<button type="submit" id="nxtBtnVThree" class="btn btn-secondary">
									Save</button>
									</div>
								</div>
                            </form>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class=" tab-pane" id="sellerDetails">
                            <form id="address" method="post" action="#address">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Seller / Vendor Name <span class="text-danger">*</span></label>
                                            <select class="form-control error" name="gst" id="select" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option label="Select Customer"></option>
                                                <option>Seller 1</option>
                                                <option>Seller 2</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" placeholder="Enter Email Address" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" placeholder="Enter Pan Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number" readonly>
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
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">GST Number</label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Designation<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Designation">
                                        </div>
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
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">GST Number</label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Designation<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Designation">
                                        </div>
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
                                    <div class="col-md-6 mb-3 description-box">
                                        <div class="form-group" id="summernote_container">
                                            <label class="form-control-label">Terms of Delivery</label>
                                            <textarea class="summernote form-control" name="terms_delivery" id="terms_delivery" placeholder="Write Terms of Delivery" rows="1">sggh</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="imageUpload">Upload Image</label>
                                            <input type="file" id="imageUpload" class="form-control" accept="image/*">
                                        </div>
                                        <div id="imagePreview" class="mt-3" style="display: none; text-align: center;">
                                            <a id="downloadLink" href="#" download="uploaded_image.jpg">
                                                <img id="uploadedImage" src="" alt="Preview Image" style="max-width: 100%; cursor: pointer; border: 1px solid #ddd; padding: 10px;">
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
@endsection

@section('page-script')
<script>
    function startCreatePurchaseInvoiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Create Purchase Invoice',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-file-text" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Record a supplier purchase invoice using this 4-step wizard.</p></div>'
                },
                {
                    element: '#inv_num',
                    title: 'Invoice Number',
                    intro: 'Input the unique invoice number as printed on the supplier\'s physical invoice.'
                },
                {
                    element: '#po_ref_num',
                    title: 'PO Reference',
                    intro: 'If applicable, select a pre-existing Purchase Order to import and auto-fill lines.'
                },
                {
                    element: '#inv_date',
                    title: 'Invoice Date',
                    intro: 'Set the official billing date of the vendor invoice.'
                },
                {
                    element: '#buyerDetail',
                    title: 'Step 1: Purchaser Details',
                    intro: 'Confirm your own business details (PAN/GST numbers, address details).'
                },
                {
                    element: '#sellerDetails',
                    title: 'Step 2: Seller/Vendor Details',
                    intro: 'Select the supplying vendor and review billing/shipping addresses.'
                },
                {
                    element: '#itemDetails',
                    title: 'Step 3: Item Entries',
                    intro: 'Record items purchased, specifying quantity, pricing, discounts, and input tax details.'
                },
                {
                    element: '#others',
                    title: 'Step 4: Payments & ITC',
                    intro: 'Choose payment mode/status, input buyer references, delivery terms, and upload scanned invoice images.'
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
        }).onbeforechange(function(targetElement) {
            if (targetElement.id === 'buyerDetail' || targetElement.id === 'sellerDetails' || targetElement.id === 'itemDetails' || targetElement.id === 'others') {
                $('.tab-pane').removeClass('show active');
                $('.nav-pills .nav-link').removeClass('active');
                
                $(targetElement).addClass('show active');
                
                // Highlight corresponding nav-link tab header
                let tabIndexMap = {
                    'buyerDetail': 0,
                    'sellerDetails': 1,
                    'itemDetails': 2,
                    'others': 3
                };
                let idx = tabIndexMap[targetElement.id];
                $('.nav-pills .nav-link').eq(idx).addClass('active');
            }
        }).start();
    }

    document.addEventListener("DOMContentLoaded", function() {
        const tourBtn = document.getElementById('start-create-purchase-invoice-tour');
        if (tourBtn) {
            tourBtn.addEventListener('click', function(e) {
                e.preventDefault();
                startCreatePurchaseInvoiceTour();
            });
        }

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
                    otherDispatchDiv.style.display = "block";
                    otherDispatchInput.disabled = false;
                } else {
                    otherDispatchDiv.style.display = "none";
                    otherDispatchInput.disabled = true;
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
                    partialRow.style.display = "flex";
                } else {
                    partialRow.style.display = "none";
                }
            });
            if (paymentStatusDropdown.value === "Partial") {
                partialRow.style.display = "flex";
            } else {
                partialRow.style.display = "none";
            }
        }
		
		
		$('#po_ref_num').on('change', function () {
			let $dropdown = $(this);
			let po_ref_num = $dropdown.val();

			if (po_ref_num === '') return;

			if (!confirm('Are you sure you want to convert this Purchase Order to Purchase Invoice?')) {
				$dropdown.prop('selectedIndex', 0); 
				return;
			}
			$("#loader").show();
			$.ajax({
				url: "{{ route('po.to.purchase') }}",
				type: "POST",
				data: {
					po_ref_num: po_ref_num
				},
				success: function (res) {
					$("#loader").hide();
					if (res.status) {
						alert(res.message);
						window.location.href = "{{ url('purchase-invoices') }}";
					} else {
						alert(res.message);
						$dropdown.prop('selectedIndex', 0);
					}
				},
				error: function () {
					alert('Server error');
					$dropdown.prop('selectedIndex', 0);
				}
			});
		});
		
    });
    document.getElementById("imageUpload").addEventListener("change", function() {
        const fileInput = this;
        const previewBox = document.getElementById("imagePreview");
        const uploadedImage = document.getElementById("uploadedImage");
        const downloadLink = document.getElementById("downloadLink");

        if (fileInput.files && fileInput.files[0]) {
            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                uploadedImage.src = e.target.result;
                downloadLink.href = e.target.result;
                previewBox.style.display = "block";
            };

            reader.readAsDataURL(file);
        } else {
            previewBox.style.display = "none";
        }
    });
</script>
@endsection