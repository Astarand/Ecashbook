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
                        <li class="breadcrumb-item"><a href="/purchase-invoices">Purchase Invoices</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Purchase Invoice</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-purchase-invoice-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Purchase Invoice</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <?php 
				$invoiceLink = "";
				if($sales->image_sign !=""){
					$arrayOfFiles = explode(',',$sales->image_sign); 
					foreach($arrayOfFiles as $img){
					    $invoiceLink = $img;
					}
				} 
			?>
    <div class="card">
        <div class="card-body">
        <form action="javascript:void(0);" method="post" name="addPurchaseFrmTop" id="addPurchaseFrmTop">
        @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <input type="text" name="inv_num" id="inv_num" class="form-control" value="{{ isset($sales->inv_num)?$sales->inv_num:''}}" placeholder="e.g. PI/25-26/0001" required="" readonly>
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                    <input type="date" name="inv_date" id="inv_date" value="{{ isset($sales->inv_date)?$sales->inv_date:''}}" class="form-control" placeholder="Invoice Number" readonly>
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
                            <a href="#buyerDetail" id="tab-A" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Purchaser / Buyer Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="#sellerDetails" id="tab-B" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Seller / Vendor Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#itemDetailForm">
                            <a href="#itemDetails" id="tab-C" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-archive-box"></i>
                                <span class="d-none d-sm-inline">Product / Service Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#others" id="tab-D"  data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
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
                        <div class="tab-pane show active" id="buyer" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPurchaseFrm" id="addPurchaseFrm" enctype="multipart/form-data">
							<input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
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
                                <div class="message-container"></div>
                                <div id="addSalesLoader" class="loader"></div>
                                <div class="add-customer-btns text-end">
                                    <a href="{{ url('/purchase-invoices') }}" class="btn btn-secondary cancel me-2">Cancel</a>
                                    <button type="submit" class="btn btn-secondary">Update</button>
                                    <?php if($sales->seller_name !=""){ ?>
                                    <a href="javascript:void(0);" id="nextBtnBuyer" title="Next"
                                        class="btn btn-secondary cancel me-2"> >> </a>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class="tab-pane" id="seller" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPurchaseFrmTwo" id="addPurchaseFrmTwo"
                            enctype="multipart/form-data">
                            <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                            @csrf
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Seller / Vendor Name <span class="text-danger">*</span></label>
                                            <select class="form-control error" name="inv_name" id="invNameCustomer" required="" onChange="changeCustomer_purchase();" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option value="">Select Customer</option>
                                                @foreach($custData as $k=>$cust)
                                                <option value="{{ $cust->id }}" <?php echo @($cust->
                                                    id==$sales->inv_name)? "selected":"" ?> >{{ $cust->vendor_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_no" placeholder="Enter Contact Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Email Address</label>
                                            <input type="email" class="form-control" id="cust_email" placeholder="Enter Email Address" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cust_pan" placeholder="Enter Pan Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">GST Number</label>
                                            <input type="text" class="form-control" id="cust_gst_no" placeholder="Enter GST Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="InvoiceaddressType">Address Type<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="add_type"  id="InvoiceaddressType" required>
                                            <option value="">Select</option>
                                                <option value="billing_add" <?php echo ($sales->
                                                    add_type=="billing_add")? "selected":"" ?>>Billing Address</option>
                                                <option value="shipping_add" <?php echo ($sales->
                                                    add_type=="shipping_add")? "selected":"" ?>>Shipping Address
                                                </option>
                                                <option value="both" <?php echo ($sales->add_type=="both")?
                                                    "selected":"" ?>>Both</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="container">

                                    </div>
                                    <div class="row" id="billingAddress">
                                        <h5 class="text-muted mb-4">Billing Address</h5>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">GST Number</label>
                                            <input type="text" name="cust_bill_gstno" id="cust_bill_gstno" class="form-control" placeholder="Enter GST Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Name</label>
                                            <input type="text" name="cust_bill_contact" id="cust_bill_contact" class="form-control" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Number</label>
                                            <input type="text" name="cust_bill_mobilno" id="cust_bill_mobilno" class="form-control" placeholder="Enter Contact Person Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Designation</label>
                                            <input type="text" name="cust_bill_designa" id="cust_bill_designa" class="form-control" placeholder="Enter Contact Person Designation">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" name="bill_addone" id="bill_addone" class="form-control" placeholder="Enter Address Line 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" class="form-control" name="bill_addtwo" id="bill_addtwo" placeholder="Enter Address Line 2">
                                        </div>                                        
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="cust_bill_state" id="cust_bill_state" onChange="inv_bill_cust_changeState(this);">
                                            <option value="">Select State</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="cust_bill_city" id="cust_bill_city" onChange="inv_bill_cust_changeState(this);">
                                            <option value="">Select City</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" id="cust_bill_pin" name="cust_bill_pin" class="form-control" placeholder="Enter Pin Code">
                                        </div>
                                    </div>

                                    <div class="row d-none" id="shippingAddress">
                                        <h5 class="text-muted mb-4">Shipping Address</h5>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">GST Number</label>
                                            <input type="text" name="cust_ship_gstno" id="cust_ship_gstno" class="form-control" placeholder="Enter GST Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_ship_contact" id="cust_ship_contact" class="form-control" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Number<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_ship_mobilno" id="cust_ship_mobilno" class="form-control" placeholder="Enter Contact Person Number">
                                        </div> 
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Designation<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_ship_designa" id="cust_ship_designa" class="form-control" placeholder="Enter Contact Person Designation">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_ship_addone" id="cust_ship_addone" class="form-control" placeholder="Enter Address Line 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_ship_addtwo" id="cust_ship_addtwo" class="form-control" placeholder="Enter Address Line 2">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="cust_ship_state" id="cust_ship_state" onChange="cust_ship_changeState_ship(this);">
                                            <option value="">Select State</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="cust_ship_city" id="cust_ship_city">
                                            <option value="">Select City</option>
                                                @foreach($cities_ship as $k=>$city)
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                        
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="cust_ship_pin" id="cust_ship_pin" placeholder="Enter Pin Code">
                                        </div>
                                    </div>

                                </div>
                                <div class="message-container"></div>
                                    <div id="addSalesLoader" class="loader"></div>
                                    <div class="add-customer-btns text-end">
                                        <a href="javascript:void(0);" id="prevBtnBuyer" title="Previous"
                                            class="btn btn-secondary cancel me-2">
                                            << </a>
                                                <button type="submit" class="btn btn-secondary">Save changes</button>                                                
                                                <?php if($sales->inv_name !=""){ ?>
                                                <a href="javascript:void(0);" id="nextBtnSeller" title="Next"
                                                    class="btn btn-secondary cancel me-2"> >> </a>
                                                <?php } ?>
                                    </div>
                            </form>
                        </div>
                        <!-- end job detail tab pane -->
                        <div class="tab-pane" id="product" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPurchaseFrmThree"
                            id="addPurchaseFrmThree" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Item Type<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="prod_type" id="prod_type" onChange="changeProductType()"  aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select Type"></option>
                                            <option value="Product">Product</option>
                                            <option value="Service">Services</option>
                                        </select>
                                    </div>
                                    {{-- <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Product / Service Name<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="prod_id" id="prod_id"  aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            @foreach($products as $k=>$product)
                                            <option value="{{ $product->id }}">{{ $product->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Product / Service Name<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="prod_id" id="prod_id"  aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option value="">Select</option>
                                            @foreach($products as $k=>$product)
                                            <option value="{{ $product->id }}">{{ $product->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Billing Type<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="billing_type" id="billing_type"  aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option>Product/ Service Billing </option>
                                            <option>Goverment Payment</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">HSN / SAC Code<span class="text-danger">*</span></label>
                                        <input type="text" name="hsn_sac_code" id="hsn_sac_code" class="form-control" placeholder="Enter HSN / SAC Code">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST Rate<span class="text-danger">*</span></label>
                                        <input type="text" name="gst_rate" id="gst_rate" class="form-control" placeholder="Enter GST Rate">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">GST Transaction Mode<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="gst_trans" id="gst_trans"  aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option value="intrastate">Intra State</option>
                                            <option value="interstate">Inter State</option>
                                            <option value="union">Union Territory</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3" id="billing_gstallo">
                                       <div class="form-group" id="gst_allocation">
                                        <label class="form-label">GST(%) Allocation<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter GST(%) Allocation">
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Discount on selling Price</label>
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
                                    <div class="col-md-1 mb-3"></div>
                                    <div class="col-md-11 mb-3 text-end">
                                    <a href="javascript:void(0);" onclick="addProductItems_purchase()" class="btn btn-outline-secondary me-3"> Add These Item</a>
                                    <a href="javascript:void(0);" onclick="addAnotherProduct()" class="btn btn-primary"> Add Another Item</a>                                       
                                    <a href="javascript:void(0);" id="addProductService" class="btn btn-outline-secondary me-3">Add Product/Service</a>
									<a href="javascript:void(0);" id="addShippingCost" class="btn btn-warning">Add Shipping Cost</a>
									</div>
                                </div>
                                <div class="row" id="invoiceData">
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
												
												<?php 
													$i = 0;
													$taxableAmt = 0;
													$totalDisc = 0;
													$totalTax = 0;
													$totalAmount = 0;
													$shippingCost = 0;
													$totalGovPay = 0;
													$totalSerPay = 0;
													$gst_trans = "";
												?>
                                                @foreach ($sales_values as $value)
                                                    <tr>
                                                        <td>{{ $i = $i+1 }}</td>
                                                        <td>{{ $value->item_name }}</td>
                                                        <td>{{ ($value->sac_code
                                                            !="")?$value->sac_code:$value->hsn_code }}</td>
                                                        <td><input type="text" name="quantity" id="quantity_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" data-prod_id="{{ $value->prod_id }}" value="{{ $value->quantity }}" onChange="changeQuantityPurchase(this)" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" class="form-control" placeholder="Quantity" value="8"></td>
                                                        <td><input type="text" name="rate" id="rate_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" onChange="changeRatePurchase(this)" value="{{ $value->rate }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" class="form-control" placeholder="Item Price" value="50"></td>
                                                        <td>₹{{ $value->disc_amt }}</td>
                                                        <td>₹{{ $value->amount }}</td>
                                                        <td class="text-center">
                                                            <!--<a href="javascript:void(0);" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" data-rate="{{ $value->rate }}" data-discamt="{{ $value->disc_amt }}" data-taxtype="{{ $value->tax_type }}" onclick="editItemPurchase(this)" class="avtar avtar-s btn-link-primary btn-pc-default" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"><i class="ti ti-pencil f-20"></i></a>-->
                                                            <span data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                                                <a href="javascript:void(0);" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" onclick="delItemPurchase(this)" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_item"><i class="ti ti-trash f-20"></i></a>
                                                            </span>

                                                        </td>
                                                    </tr>													
													<?php 
														$taxableAmt += ($value->rate * $value->quantity); 
														$totalDisc += $value->disc_amt;
														$totalTax += $value->tax_amt; 
														$totalAmount += $value->amount; 
														//$totalGovPay += $value->gov_pay; 
														//$totalSerPay += $value->ser_pay; 
														$gst_trans = $value->gst_trans; 
													?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <hr class="mb-3">
                                    <div class="col-12">
                                        <div class="invoice-total ms-auto">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Discount On Grand Total</label>
                                                        
                                                        <input type="text" name="discount_amount" id="discount_amount" class="form-control" value="{{ $sales->special_discount_amount }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted mb-1 text-start">Sub Total :</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-end">₹<?php echo $totalAmount ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted mb-1 text-start">Discount :</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-end text-success">₹<?php echo $totalDisc; ?></p>
                                                </div>
                                                @if($gst_trans == 'intrastate')
													<div class="col-6">
														<p class="text-muted mb-1 text-start">CGST :</p>
													</div>
													<div class="col-6">
														<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalTax/2, 2) }}</p>
													</div>

													<div class="col-6">
														<p class="text-muted mb-1 text-start">SGST :</p>
													</div>
													<div class="col-6">
														<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalTax/2, 2) }}</p>
													</div>
												@else
													<div class="col-6">
														<p class="text-muted mb-1 text-start">IGST :</p>
													</div>
													<div class="col-6">
														<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalTax, 2) }}</p>
													</div>
												@endif
												<div class="col-6">
                                                    <p class="f-w-600 mb-1 text-start">Shipping Cost :</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-end" id="shippingCost">₹<?php echo getRoundedAmount($sales->shipping_cost); ?></p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-end" id="grand_total_amount">₹<?php echo getRoundedAmount($totalAmount + $totalTax + $sales->shipping_cost); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="message-container"></div>
                                <div id="editSalesLoader" class="loader"></div>
                                <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnProd" title="Previous" class="btn btn-secondary"><< </a>
									<button type="submit" class="btn btn-secondary">Save changes</button>
									<a target="_blank" href="{{ url('/purchase-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}" class="btn btn-secondary mt-3 mt-md-0">Preview Invoice</a>
									<?php if($sales->inv_name !=""){ ?>
									<a href="javascript:void(0);" id="nextBtnProd" title="Next"
										class="btn btn-secondary cancel me-2"> >> </a>
									<?php } ?>
											
                                </div>
                            </form>
                        </div>
                        <!-- end education detail tab pane -->
                        <div class="tab-pane" id="other" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPurchaseFrmFour"
                            id="addPurchaseFrmFour" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                            @csrf
                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Mode of Payment<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select name="mode_of_pay" id="mode_of_pay" class="form-select has-success" aria-invalid="false">
                                                <option value="">Select</option>
                                                <option value="Bank" <?php echo ($sales->mode_of_pay == "Bank") ?
                                                                                "selected" : "" ?>>Bank</option>                                                    
                                                    <option value="UPI" <?php echo ($sales->mode_of_pay == "UPI") ?
                                                                            "selected" : "" ?>>UPI</option>                                                   
                                                    <option value="Cash" <?php echo ($sales->mode_of_pay == "Cash") ?
                                                                                "selected" : "" ?>>Cash</option>
                                                    <!--<option value="OTHER" <?php echo ($sales->mode_of_pay == "OTHER") ?
                                                                                "selected" : "" ?>>Other</option>-->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3" id="other_payment_div" style="display: none;">
                                        <div class="form-group">
                                            <label>Specify Other Payment Method<span class="text-danger">*</span></label>
                                            <input type="text" value="{{ $sales->other_payment }}" class="form-control" name="other_payment" id="other_payment" placeholder="Specify Other Payment Method">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Payment Status<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="form-select has-success" name="pay_status" id="pay_status" aria-invalid="false">
                                                    <option value="">Select</option>
                                                    <option value="Full" <?php echo ($sales->pay_status=="Full")?
                                                    "selected":"" ?>>Full Payment</option>
                                                <option value="Partial" <?php echo ($sales->pay_status=="Partial")?
                                                    "selected":"" ?>>Advance Payment</option>
												<option value="Due" <?php echo ($sales->pay_status == "Due") ?
                                                                                "selected" : "" ?>>Due</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Payment Section -->
									<div class="row align-items-end" id="paymentSection">
										<div class="col-md-4 mb-3">
											<label>Total Amount</label>
											<input type="text" name="total_amount" id="total_amount" readonly class="form-control">
										</div>
										<div class="col-md-4 mb-3 d-flex align-items-end">
											<button
												type="button"
												class="btn btn-primary paymentModalBtn"
												data-id="{{ $sales->id }}"
												data-type="Purchase">
												Click to Update Payment
											</button>
										</div>
									</div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Purchase order Number</label>
                                            <input type="text" name="seller_orderno" id="seller_orderno" value="{{ $sales->seller_orderno }}" class="form-control" placeholder="Buyer's Order Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Order Date</label>
                                            <input type="date" name="order_date" id="order_date" value="{{ $sales->order_date }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Suppliers Ref No.</label>
                                            <input type="text" name="buyer_refno" id="buyer_refno"  value="{{ $sales->buyer_refno }}" class="form-control" placeholder="Suppliers Ref No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Others Ref No.</label>
                                            <input type="text" name="other_refno" id="other_refno" value="{{ $sales->other_refno }}" class="form-control" placeholder="Others Ref No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Dispatch Document No.</label>
                                            <input type="text" name="dispa_docno_one" id="dispa_docno_one" value="{{ $sales->dispa_docno_one }}" class="form-control" placeholder="Dispatch Document No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Dispatch through</label>
                                            <div class="form-group">
                                                <select class="form-select" name="disp_through" id="disp_through">
                                                    <option value="">Select</option>
                                                    <option value="Road Transportation" <?php echo ($sales->
                                                    disp_through=="Road Transportation")? "selected":"" ?>>Road
                                                    Transportation</option>
                                                <option value="Rail Transportation" <?php echo ($sales->
                                                    disp_through=="Rail Transportation")? "selected":"" ?>>Rail
                                                    Transportation</option>
                                                <option value="Air Transportation" <?php echo ($sales->
                                                    disp_through=="Air Transportation")? "selected":"" ?>>Air
                                                    Transportation</option>
                                                <option value="Sea Transportation" <?php echo ($sales->
                                                    disp_through=="Sea Transportation")? "selected":"" ?>>Sea
                                                    Transportation</option>
                                                <option value="Multi model Transportation" <?php echo ($sales->
                                                    disp_through=="Multi model Transportation")? "selected":"" ?>>Multi
                                                    model Transportation</option>
                                                <option value="Parcel & Courier Service" <?php echo ($sales->
                                                    disp_through=="Parcel & Courier Service")? "selected":"" ?>>Parcel &
                                                    Courier Service</option>
                                                <option value="By Hand" <?php echo ($sales->disp_through=="By Hand")?
                                                    "selected":"" ?>>By Hand</option>
                                                <option value="Other" <?php echo ($sales->disp_through=="Other")?
                                                    "selected":"" ?>>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3" style="display: none;" id="other_dispatch">
                                        <div class="form-group ">
                                            <label>Other Dispatch Details</label>
                                            <input type="text" name="other_dispa_det" id="other_dispa_det" value="{{ $sales->other_dispa_det }}" class="form-control" placeholder="Other Dispatch Details" disabled="">
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="imageUpload">Upload Invoice</label>
                                            <input type="file" name="image_sign[]" multiple="" id="image_sign" class="form-control" accept="image/*">
                                        </div>                                        
										@php
											$signImages = !empty($sales->image_sign)
												? explode(',', $sales->image_sign)
												: [];
										@endphp

										<div id="imagePreview" class="mt-3">
											@foreach($signImages as $img)
												@php
													$imgPath = asset('uploads/invoice-signature/' . trim($img));
												@endphp

												<div class="mb-2">
													<a target="_blank" href="{{ $imgPath }}" download class="text-primary">
														Download {{ $img }}
													</a>
												</div>
											@endforeach
										</div>
                                    </div>
                                </div>
                                <div class="message-container"></div>
                                <div id="editSalesLoader" class="loader"></div>
                                <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnOther" title="Previous"
                                        class="btn btn-secondary">
                                        << </a>
                                            <button type="submit" class="btn btn-secondary">Save changes</button>
                                            <a target="_blank" href="{{ url('/purchase-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}" class="btn btn-secondary mt-3 mt-md-0">Preview Invoice</a>
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

<div class="modal fade" id="productServiceModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Product / Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        {{-- PASTE YOUR FULL FORM HERE --}}
        @include('User.product.add_product_form')

      </div>
    </div>
  </div>
</div>

<div class="modal custom-modal fade" id="delete_item" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete These Item</h3>
                    <p>Are you sure you want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" id="delItemPurchase" data-bs-dismiss="modal" class="w-100 btn btn-primary">
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

<div class="modal fade" id="shippingCostModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Shipping Cost</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="shipping_purchase_id" value="{{ $sales->id }}">

                <div class="mb-3">
                    <label>Shipping Cost</label>
                    <input type="number"
                           class="form-control"
                           id="shipping_cost_input"
                           value="{{ $sales->shipping_cost ?? 0 }}"
                           min="0"
                           step="0.01">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary"
                        onclick="saveShippingCostPurchase()">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="paymentVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="f_id">
                <input type="hidden" id="voucher_type">
				<input type="hidden" id="isViewPage" value="0">
				
				<div id="paymentNoteArea" class="alert alert-warning mt-2">
					<strong>Note:</strong>
					Please click <strong>Save</strong> to update payment vouchers,
					journal entries and payment status.
				</div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Total Invoice Amount</label>
                        <input type="text"
                            id="invoice_total"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Paid Amount</label>
                        <input type="text"
                            id="total_paid"
                            class="form-control"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Balance Due</label>
                        <input type="text"
                            id="balance_due"
                            class="form-control"
                            readonly>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
						<th>Mode</th>
                        <th width="80">Action</th>
                    </tr>
                    </thead>

                    <tbody id="voucherRows">

                    </tbody>
                </table>

                <button type="button"
                    class="btn btn-success"
                    id="addVoucherRow">
                    Add Payment
                </button>

            </div>

            <div class="modal-footer">
                <button type="button"
                    class="btn btn-primary"
                    id="saveVoucherPayments">
                    Save
                </button>
            </div>

        </div>
    </div>
</div>

<script>
	$('#tab-B, #tab-C, #tab-D').addClass('disabled');
	
	$("#addProductService").on("click", function () {
		$("#productServiceModal").modal("show");
	});
	
	//update total amount
	document.addEventListener("DOMContentLoaded", function() {
		const grandTotalElement = document.getElementById("grand_total_amount");
		const totalAmount = document.getElementById("total_amount");

		if (grandTotalElement && totalAmount) {
			let amt = grandTotalElement.textContent.replace(/[₹,]/g, '').trim();
			totalAmount.value = parseFloat(amt || 0).toFixed(2);
		}
	});
        
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
                    otherDispatchDiv.style.display = "block";
                    otherDispatchInput.disabled = false;
                } else {
                    otherDispatchDiv.style.display = "none";
                    otherDispatchInput.disabled = true;
                }
            });
        }
    });
	
	document.addEventListener("DOMContentLoaded", function () {
		const paymentStatusDropdown = document.getElementById("pay_status");
		const paymentBtn = document.querySelector(".paymentModalBtn");

		function toggleFields() {
			const status = paymentStatusDropdown.value;
			if (status === "Due") {
				paymentBtn.style.display = "none";
			} else {
				paymentBtn.style.display = "inline-block";
			}
		}

		if (paymentStatusDropdown) {
			paymentStatusDropdown.addEventListener("change", toggleFields);
			toggleFields(); // Initial page load
		}
	});
	
	
    

    function changeProductType() {
        var base_url = $("#base_url").val();
        var id = $("#prod_type option:selected").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        if (id != "") {
            $.ajax({
                url: base_url + "/getProductType",
                dataType: "json",
                type: "post",
                data: { id: id },
                success: function (data) {
                    console.log(data);
                    
                    $("#prod_id").empty();
                    var str = '<option value="">Select</option>';
                    $.each(data, function (idx, item) {
                        //$("#state").append('<option value="' + item.id + '">' + item.name + '</option>');
                        str +=
                            '<option value="' +
                            item.id +
                            '">' +
                            item.name +
                            "</option>";
                    });
                    $("#prod_id").html(str);
                },
            });
        } else {
            $("#prod_id").html("");
            $("#hsn_sac_code").val("");
            $("#disc_sell").val(0);
            $("#prod_gov_fee").val(0);
            $("#billing_type").prop("selectedIndex", 0);
            $("#gst_trans").prop("selectedIndex", 0);
            $("#disc_sell_type").prop("selectedIndex", 0);
        }
    }
	
	//  Product selection triggers GST data fetch
	$(document).on("change", "#prod_id", function () {
		var prod_id = $(this).val();
		if (!prod_id) return;

		$.ajaxSetup({
			headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
		});

		$.ajax({
			url: base_url + "/getProduct",
			type: "POST",
			dataType: "json",
			data: { prod_id: prod_id },
			success: function (data) {
				if (data.length > 0) {
					var product = data[0];

					// populate fields
					$("#hsn_sac_code").val(product.hsn_sac_code);
					$("#goods_desc").val(product.goods_desc);
					$("#disc_sell").val(product.disc_sell);
					$("#disc_sell_type").val(product.disc_sell_type);
					$("#gst_rate").val(product.gst_rate);

				}
			},
		});
	});

    document.addEventListener("DOMContentLoaded", function () {
        let discountInput = document.getElementById("discount_amount");
        let grandTotalElement = document.getElementById("grand_total_amount");

        // Store the initial grand total
        let grandTotal = <?php echo $totalAmount + $totalTax; ?>;

        discountInput.addEventListener("input", function () {
            
            let discount = parseFloat(this.value) || 0; // Get discount value, default to 0 if invalid
            let newTotal = grandTotal - discount; // Subtract discount from grand total
            grandTotalElement.innerHTML = `₹${newTotal.toFixed(2)}`; // Update the total amount display
        });
    });
    

    //--------------- Address type Dropdown change -------------

    document.addEventListener("DOMContentLoaded", function () {
        const addressType = document.getElementById("addressType");
        const billingDiv = document.getElementById("billingAddress");
        const shippingDiv = document.getElementById("shippingAddress");

        function toggleAddressFields() {
            let selectedValue = addressType.value;
            billingDiv.classList.add("d-none");
            shippingDiv.classList.add("d-none");

            if (selectedValue === "billing_add") {
                billingDiv.classList.remove("d-none");
            } else if (selectedValue === "shipping_add") {
                shippingDiv.classList.remove("d-none");
            } else if (selectedValue === "both") {
                billingDiv.classList.remove("d-none");
                shippingDiv.classList.remove("d-none");
            }
        }

        addressType.addEventListener("change", toggleAddressFields);

        // Set default visibility on page load
        toggleAddressFields();
    });    
    

    function startEditPurchaseInvoiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Purchase Invoice Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Modify supplier bill details, items, tax values, or payment terms.</p></div>'
                },
                {
                    title: 'Edit Purchase Invoice',
                    intro: 'Modify supplier bill details, items, tax values, or payment terms.'
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
        $('#start-edit-purchase-invoice-tour').on('click', function(e) {
            e.preventDefault();
            startEditPurchaseInvoiceTour();
        });
    });
</script>
@endsection