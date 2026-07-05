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
                        <li class="breadcrumb-item"><a href="/sale-invoices">Sales Invoices</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Sales Invoice</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-edit-sales-invoice-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Edit Sales Invoice</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <form action="javascript:void(0);" method="post" name="addSalesFrmTop" id="addSalesFrmTop">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                        <input type="text" name="inv_num" id="inv_num" class="form-control" value="{{ $sales->inv_num }}" readonly>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                        <input type="date" name="inv_date" id="inv_date" value="{{ $sales->inv_date }}" class="form-control" required>
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
                            <a href="javascript:void(0);" id="tab-A" data-bs-toggle="tab" data-toggle="tab" aria-expanded="false" aria-selected="false" class="nav-link active" tabindex="-1">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Seller Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="javascript:void(0);" id="tab-B" data-bs-toggle="tab" data-toggle="tab" aria-expanded="true" aria-selected="true" class="nav-link icon-btn">
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Customer Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item" data-target-form="#itemDetailForm">
                            <a href="javascript:void(0);" id="tab-C" data-bs-toggle="tab" data-toggle="tab" aria-expanded="false" aria-selected="false" class="nav-link icon-btn" tabindex="-1">
                                <i class="ph-duotone ph-archive-box"></i>
                                <span class="d-none d-sm-inline">Product / Service Details</span>
                            </a>
                        </li>
                        <!-- end nav item -->
                        <li class="nav-item">
                            <a href="#others" id="tab-D" data-bs-toggle="tab" data-toggle="tab" aria-expanded="false" aria-selected="false" class="nav-link icon-btn" tabindex="-1">
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
                        <?php if ($sales->saller_gst_reg == 'Yes'): ?>
                            <small class="badge rounded-pill bg-success">B2B</small>
                        <?php else: ?>
                            <small class="badge rounded-pill bg-secondary">B2C</small>
                        <?php endif; ?>
                        <!-- START: Define your tab pans here -->
                        <div class="tab-pane active show" id="basic" role="tabpanel">
                            <form action="javascript:void(0);" method="post" name="addSalesFrm" id="addSalesFrm" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                                @csrf
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_name" value="{{ $sales->seller_name }}" id="seller_name" placeholder="Seller Name">
                                                <input type="hidden" name="saller_gst_reg" value="{{ $sales->saller_gst_reg }}" id="saller_gst_reg">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_contact" value="{{ $sales->seller_contact }}" id="seller_contact" placeholder="Contact Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_email" value="{{ $sales->seller_email }}" id="seller_email" placeholder="Email Address">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pan" value="{{ $sales->seller_pan }}" id="seller_pan" placeholder="Pan Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ $sales->seller_gst }}" name="seller_gst" id="seller_gst" placeholder="GST Number">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">URN Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ $sales->udyam_reg_no }}" name="udyam_reg_no" id="udyam_reg_no" placeholder="URN Number">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addone" value="{{ $sales->seller_addone }}" id="seller_addone" placeholder="Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addtwo" value="{{ $sales->seller_addtwo }}" id="seller_addtwo" placeholder="Address Line 2">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                <select class="form-control select-style" name="seller_state" id="seller_state">
                                                    <option value="">Select State</option>
                                                    @foreach($states_seller as $k=>$state)
                                                    <option value="{{ $state->id }}" <?php echo ($state->id == $sales->seller_state) ? "selected" : "" ?>>{{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                <select class="form-control" name="seller_city" id="city">
                                                    <option value="">Select City</option>
                                                    @foreach($cities_seller as $k=>$city)
                                                    <option value="{{ $city->id }}" <?php echo ($city->id == $sales->seller_city) ?
                                                                                        'selected="selected"' : "" ?>>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pin" id="seller_pin" value="{{ $sales->seller_pin }}" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="message-container"></div>
                                <div id="addSalesLoader" class="loader"></div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="{{ url('/sale-invoices') }}" class="btn btn-secondary cancel me-2">Cancel</a>
                                    <button type="submit" class="btn btn-secondary">Update</button>
                                    <a href="{{ url('/sales-invoice-pdf/OQ==/invoice')}}"
                                        class="btn customer-btn-cancel">Preview Invoice</a>
                                    <?php if ($sales->seller_name != "") { ?>
                                        <a href="javascript:void(0);" id="nextBtnSeller" title="Next"
                                            class="btn btn-secondary cancel me-2"> >> </a>
                                    <?php } ?>
                                </div> -->
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <!-- <div class="first">
                                        <a href="javascript:void(0);" class="btn btn-primary">
                                            <i class="ph-duotone ph-arrow-circle-up-left"></i> Back To Previous
                                        </a>
                                    </div> -->
                                    <div class="d-flex">
                                        <!-- <div class="previous me-2">
                                            <a href="{{ url('/sales-invoice-pdf/OQ==/invoice')}}" class="btn btn-secondary">
                                                <i class="ph-duotone ph-eye"></i> View Invoice
                                            </a>
                                        </div> -->
                                        <div class="next">
                                            <a href="{{ url('/sale-invoices') }}" class=" btn btn-danger mt-3 mt-md-0">
                                                <i class="ph-duotone ph-x-circle"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                    <div class="last">
                                        <?php if ($sales->seller_name != "") { ?>
                                            <button type="submit" class="btn btn-primary mt-3 mt-md-0" id="nextBtnSeller">
                                                Next <i class="ph-duotone ph-arrow-circle-up-right"></i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class="tab-pane" id="customer" role="tabpanel">
                            <form action="javascript:void(0);" method="post" name="addSalesFrmTwo" id="addSalesFrmTwo"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                                @csrf
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                            <select class="form-control error" name="inv_name" id="invNameCustomer" onChange="changeCustomer();">
                                                <option label="Select Customer"></option>
                                                @foreach($custData as $k=>$cust)
                                                <option value="{{ $cust->id }}" <?php echo @($cust->id == $sales->inv_name) ? "selected" : "" ?>>{{ $cust->cust_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact_no" placeholder="Contact Number" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="cust_email" name="cust_email" placeholder="Enter Email Address" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cust_pan" name="cust_pan" placeholder="Enter Pan Number" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cust_gst_no" name="cust_gst_no" placeholder="Enter GST Number" disabled>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="InvoiceaddressType">Address Type<span class="text-danger">*</span></label>
                                            {{-- <select class="form-select" name="add_type" id="add_type">
                                            <option selected value="billing_add" <?php echo ($sales->add_type == "billing_add") ? "selected" : "" ?>>Billing Address</option>
                                                <option value="shipping_add" <?php echo ($sales->add_type == "shipping_add") ? "selected" : "" ?>>Shipping Address
                                                </option>
                                                <option value="both" <?php echo ($sales->add_type == "both") ?
                                                                            "selected" : "" ?>>Both</option>
                                            </select> --}}

                                            <select id="addressType" class="form-control">
                                                <option value="billing_add" <?php echo ($sales->add_type == "billing_add") ? "selected" : "" ?>>Billing Address</option>
                                                <option value="shipping_add" <?php echo ($sales->add_type == "shipping_add") ? "selected" : "" ?>>Shipping Address</option>
                                                <option value="both" <?php echo ($sales->add_type == "both") ? "selected" : "" ?>>Both</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="container">

                                    </div>

                                    <div class="row" id="billingAddress">
                                        <h5 class="text-muted mb-4">Billing Address</h5>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bill_addone" id="bill_addone" placeholder="Enter Address Line 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bill_addtwo" id="bill_addtwo" placeholder="Enter Address Line 2">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <select class="form-control select-style" name="cust_bill_state" id="cust_bill_state">
                                                <option value="">Select State</option>
                                                @foreach($states_seller as $state)
                                                <option value="{{ $state->id }}" <?php echo ($state->id == $sales->seller_state) ? "selected" : "" ?>>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <select class="form-control" name="cust_bill_city" id="cust_bill_city">
                                                <option value="">Select City</option>
                                                @foreach($cities_seller as $city)
                                                <option value="{{ $city->id }}" <?php echo ($city->id == $sales->seller_city) ? 'selected="selected"' : "" ?>>{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cust_bill_pin" name="cust_bill_pin" placeholder="Enter Pin Code">
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
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnCust" title="Previous"
                                        class="btn btn-primary cancel me-2">
                                        << </a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                            <a href="{{ url('/sales-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}"
                                                class="btn customer-btn-cancel">Preview Invoice</a>
                                            <?php if ($sales->seller_name != "") { ?>
                                                <a href="javascript:void(0);" id="nextBtnCust" title="Next"
                                                    class="btn btn-primary cancel me-2"> >> </a>
                                            <?php } ?>
                                </div> -->
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" id="prevBtnCust" class="btn btn-primary">
                                            <i class="ph-duotone ph-arrow-circle-up-left"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="previous me-2">
                                            <a href="{{ url('/sale-invoices') }}" class=" btn btn-danger mt-3 mt-md-0">
                                                <i class="ph-duotone ph-x-circle"></i> Cancel
                                            </a>
                                        </div>
                                        <!-- <div class="next">
                                            <button type="submit" class="btn btn-primary"> <i class="ph-duotone ph-check-fat"></i> Save changes</button>
                                        </div> -->
                                    </div>
                                    <div class="last">
                                        <?php if ($sales->seller_name != "") { ?>
                                            <button type="submit" class="btn btn-primary mt-3 mt-md-0" id="nextBtnCust">
                                                Next <i class="ph-duotone ph-arrow-circle-up-right"></i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end job detail tab pane -->
                        <div class="tab-pane" id="product" role="tabpanel">
                            <form action="javascript:void(0);" method="post" name="addSalesFrmThree" id="addSalesFrmThree"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                                <input type="hidden" id="sign" value="{{ $sales->signature }}">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Item Type<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="prod_type" id="prod_type"
                                            onChange="changeProductType()" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select Type"></option>
                                            <option value="product">Product</option>
                                            <option value="service">Service</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Product / Service Name<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="prod_id" id="prod_id" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                            <option label="Select"></option>
                                            <option value="">Select</option>
                                            @foreach($products as $k=>$product)
                                            <option value="{{ $product->id }}">{{ $product->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label" for="inputEmail4">Billing Type<span class="text-danger">*</span></label>
                                        <select class="form-control error" name="billing_type" id="billing_type" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
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
                                            <label class="form-label">Goods Description<span class="text-danger">*</span></label>
                                            <input type="text" name="goods_desc" id="goods_desc" class="form-control" placeholder="Enter Goods Description">
                                    </div>                                   

                                    <?php
                                    if ($sales->saller_gst_reg == 'Yes') {
                                    ?>
                                      
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label" for="inputEmail4">GST Transaction Mode<span class="text-danger">*</span></label>
                                            <select class="form-control error" name="gst_trans" id="gst_trans" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option value="">Select</option>
                                                <option value="intrastate">Intra State</option>
                                                <option value="interstate">Inter State</option>
                                                <option value="union">Union Territory</option>
                                            </select>
                                        </div>
                                       <div class="mb-3 col-md-3">
                                            <label class="form-label">GST(%) Rate<span class="text-danger">*</span></label>
                                            <input type="number" id="gst_rate" name="gst_rate" class="form-control" placeholder="Enter GST(%) Rate">
                                        </div>

                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">GST(%) Allocation<span class="text-danger">*</span></label>
                                            <input type="text" id="gst_allocation_text" name="gst_allocation_text" class="form-control" readonly>
                                        </div>

                                    <?php } ?>

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
                                    <div class="col-md-12 mb-3 text-end">
                                        <a href="javascript:void(0);" onclick="addProductItems()"
                                            class="btn btn-outline-secondary me-3"> Add These Item</a>
                                        <a href="javascript:void(0);" onclick="addAnotherProduct()"
                                            class="btn btn-primary"> Add Another Item</a>
                                        <a href="javascript:void(0);" id="addProductService" class="btn btn-outline-secondary me-3">Add Product/Service</a>
                                        <!-- <button class="btn btn-primary">Add Another Item</button>-->
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
														$totalGovPay = 0;
														$totalSerPay = 0;
														$gst_trans = "";
													?>
                                                    @foreach ($sales_values as $value)
                                                    <tr>
                                                        <td>{{ $i = $i+1 }}</td>
                                                        <td>{{ $value->item_name }}</td>
                                                        <td>{{ ($value->sac_code!="")?$value->sac_code:$value->hsn_code }}</td>
                                                        <td><input type="text" class="form-control" id="quantity_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" data-prod_id="{{ $value->prod_id }}" class="form-control quantity" placeholder="Quantity" value="{{ $value->quantity }}" onChange="changeQuantity(this);" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                                        <td><input type="text" class="form-control" name="rate" id="rate_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" onChange="changeRate(this);" class="form-control rate" placeholder="Item Price" value="{{ $value->rate }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                                        <td>₹{{ $value->disc_amt }}</td>
                                                        <td>₹{{ $value->amount }}</td>
                                                        <td class="text-center">
                                                            <!--<a href="javascript:void(0);" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" data-rate="{{ $value->rate }}" data-discamt="{{ $value->disc_amt }}" data-taxtype="{{ $value->tax_type }}" onclick="editItem(this)" class="avtar avtar-s btn-link-primary btn-pc-default" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit"><i class="ti ti-pencil f-20"></i></a>-->
                                                            <span data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                                                <a href="javascript:void(0);" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" onclick="delItem(this)" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_item"><i class="ti ti-trash f-20"></i></a>
                                                            </span>

                                                        </td>
                                                    </tr>													
													<?php 
														$taxableAmt += ($value->rate * $value->quantity); 
														$totalDisc += $value->disc_amt;
														$totalTax += $value->tax_amt; 
														$totalAmount += $value->amount; 
														$totalGovPay += $value->gov_pay; 
														$totalSerPay += $value->ser_pay; 
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
													<p class="text-muted mb-1 text-start">Government Fees :</p>
												</div>
												<div class="col-6">
													<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalGovPay, 2) }}</p>
												</div>

												<div class="col-6">
													<p class="text-muted mb-1 text-start">Service Charges :</p>
												</div>
												<div class="col-6">
													<p class="f-w-600 mb-1 text-end">₹{{ number_format($totalSerPay, 2) }}</p>
												</div>

                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-end" id="grand_total_amount">₹{{ number_format(getRoundedAmount($totalAmount + $totalTax + $totalGovPay + $totalSerPay), 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label" for="inputEmail4">Signature Type<span class="text-danger">*</span></label>
                                        <select class="form-control" name="signature_type" id="signature_type">
                                            <option value="">Select Signature Type</option>
                                            <option value="normal" {{ isset($sales->signature_type) && $sales->signature_type == 'normal' ? 'selected' : '' }}>Normal Signature</option>
                                            <!--<option value="digital" {{ isset($sales->signature_type) && $sales->signature_type == 'digital' ? 'selected' : '' }}>Digital Signature</option>-->
                                        </select>
                                    </div>
                                </div>

                                <div class="row" id="normalSignatureFields" style="display: none;">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputEmail4">Signature Name<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="signature_name" id="signature_name" value="{{ $sales->signature_name ?? '' }}" placeholder="Enter Name">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputEmail4">Upload Signature<span class="text-danger">*</span></label>
                                        <input type="file" name="signature" id="signature" class="form-control" placeholder="Enter Name">
                                        @if(isset($sales->signature) && $sales->signature != "")
                                        <div class="downloadFile"><a target="_blank"
                                                href="{{ asset('uploads/invoice-signature/'.$sales->signature) }}">Download</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row" id="digitalSignatureField" style="display: none;">
                                    <div class="mb-3 col-md-12 text-end">
                                        <label class="form-label" for="inputEmail4">Digital Signature<span class="text-danger">*</span></label>
                                        <div class="digital-signature-upload d-flex justify-content-end">
                                            <input type="file" class="file-input" name="digital_signature" id="digital_signature" accept="image/*" style="display: none;">
                                            <div class="upload-area" id="uploadArea">
                                                <div class="upload-content text-center">
                                                    <i class="ti ti-upload f-20 mb-2"></i>
                                                    <p class="mb-0">Click to Upload or Drag & Drop</p>
                                                    <p class="text-muted small mb-0">Supported formats: JPEG, PNG, GIF</p>
                                                </div>
                                            </div>
                                            <div id="previewContainer" class="mt-2" style="display: none;">
                                                <img id="signaturePreview" src="" alt="Signature Preview" class="img-fluid mb-2" style="max-height: 100px;">
                                                <button type="button" class="btn btn-sm btn-danger" id="removeSignature">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnProd" title="Previous" class="btn btn-primary">
                                        << </a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                            <a href="{{ url('/sales-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}"
                                                class="btn customer-btn-cancel">Preview Invoice</a>
                                            <?php if ($sales->signature_name != "") { ?>
                                                <a href="javascript:void(0);" id="nextBtnProd" title="Next"
                                                    class="btn btn-primary cancel me-2"> >> </a>
                                            <?php } ?>
                                </div> -->
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" id="prevBtnProd" class="btn btn-primary">
                                            <i class="ph-duotone ph-arrow-circle-up-left"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="previous me-2">
                                            <a href="{{ url('/sale-invoices') }}" class=" btn btn-danger mt-3 mt-md-0">
                                                <i class="ph-duotone ph-x-circle"></i> Cancel
                                            </a>
                                        </div>
                                        <!-- <div class="next">
                                            <button type="submit" class="btn btn-primary"> <i class="ph-duotone ph-check-fat"></i> Save changes</button>
                                        </div> -->
                                    </div>
                                    <div class="last">
                                        <button type="submit" id="nextBtnProd" class="btn btn-primary mt-3 mt-md-0">
                                            Next <i class="ph-duotone ph-arrow-circle-up-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end education detail tab pane -->
                        <div class="tab-pane" id="other">
                            <form action="javascript:void(0);" method="post" name="addSalesFrmFour" id="addSalesFrmFour"
                                enctype="multipart/form-data">
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
                                            <input type="text" value="{{ $sales->other_payment }}" class="form-control" name="other_payment" id="other_payment"
                                                placeholder="Specify Other Payment Method">

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Payment Status<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select class="form-select" name="pay_status" id="pay_status">
                                                    <option value="">Select</option>
                                                    <option value="Full" <?php echo ($sales->pay_status == "Full") ?
                                                                                "selected" : "" ?>>Full Payment</option>
                                                    <option value="Partial" <?php echo ($sales->pay_status == "Partial") ?
                                                                                "selected" : "" ?>>Advance Payment</option>
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
												data-type="Sales">
												Click to Update Payment
											</button>
										</div>
									</div>
                                    
                                    {{-- Start Bank Details --}}
                                    {{-- <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Select Bank <span class="text-danger">*</span></label>
                                            <select name="bank_id" id="bank_id" class="form-control">
                                                <option value="">-- Select Bank --</option>
                                                @foreach($bankDetails as $bank)
                                                    <option value="{{ $bank->id }}"
                                                            data-qr="{{ asset('storage/' . $bank->bank_qr_code) }}">
                                                        {{ $bank->bank_name }} - {{ $bank->bank_branch }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <img id="bankQrPreview" style="max-width:200px; display:none;">
                                    </div> --}}


                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Select Bank <span class="text-danger">*</span></label>
                                            <select name="bank_id" id="bank_id" class="form-control">
                                                <option value="">-- Select Bank --</option>

                                                @foreach($bankDetails as $bank)
                                                    <option value="{{ $bank->id }}"
                                                            data-qr="{{ asset('storage/' . $bank->bank_qr_code) }}"
                                                            {{ $sales->bank_id == $bank->id ? 'selected' : '' }}>
                                                        {{ $bank->bank_name }} - {{ $bank->bank_branch }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <img id="bankQrPreview" style="max-width:200px; display:none;">
                                    </div>
                                    {{-- End Bank Details --}}

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Buyer's Order Number</label>
                                            <input type="text" name="buyer_orderno" id="buyer_orderno"
                                                value="{{ $sales->buyer_orderno }}" class="form-control"
                                                placeholder="Buyer's Order Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Order Date</label>
                                            <input type="date" name="order_date" id="order_date"
                                                value="{{ $sales->order_date }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Suppliers Ref No.</label>
                                            <input type="text" name="supplier_refno" id="supplier_refno"
                                                value="{{ $sales->supplier_refno }}" class="form-control"
                                                placeholder="Suppliers Ref No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Others Ref No.</label>
                                            <input type="text" name="other_refno" id="other_refno"
                                                value="{{ $sales->other_refno }}" class="form-control"
                                                placeholder="Others Ref No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group ">
                                            <label>Dispatch Document No.</label>
                                            <input type="text" name="dispa_docno_one" id="dispa_docno_one"
                                                value="{{ $sales->dispa_docno_one }}" class="form-control"
                                                placeholder="Dispatch Document No.">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Dispatch through</label>
                                            <div class="form-group">
                                                <select class="form-select" name="disp_through" id="disp_through">
                                                    <option value="">Select</option>
                                                    <option value="Road Transportation" <?php echo ($sales->disp_through == "Road Transportation") ? "selected" : "" ?>>Road
                                                        Transportation</option>
                                                    <option value="Rail Transportation" <?php echo ($sales->disp_through == "Rail Transportation") ? "selected" : "" ?>>Rail
                                                        Transportation</option>
                                                    <option value="Air Transportation" <?php echo ($sales->disp_through == "Air Transportation") ? "selected" : "" ?>>Air
                                                        Transportation</option>
                                                    <option value="Sea Transportation" <?php echo ($sales->disp_through == "Sea Transportation") ? "selected" : "" ?>>Sea
                                                        Transportation</option>
                                                    <option value="Multi model Transportation" <?php echo ($sales->disp_through == "Multi model Transportation") ? "selected" : "" ?>>Multi
                                                        model Transportation</option>
                                                    <option value="Parcel & Courier Service" <?php echo ($sales->disp_through == "Parcel & Courier Service") ? "selected" : "" ?>>Parcel &
                                                        Courier Service</option>
                                                    <option value="By Hand" <?php echo ($sales->disp_through == "By Hand") ?
                                                                                "selected" : "" ?>>By Hand</option>
                                                    <option value="Other" <?php echo ($sales->disp_through == "Other") ?
                                                                                "selected" : "" ?>>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3" style="display: none;" id="other_dispatch">
                                        <div class="form-group ">
                                            <label>Other Dispatch Details</label>
                                            <input type="text" name="other_dispa_det" id="other_dispa_det"
                                                value="{{ $sales->other_dispa_det }}" class="form-control"
                                                placeholder="Other Dispatch Details">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3 description-box">
                                        <div class="form-group" id="summernote_container">
                                            <label class="form-control-label">Terms of Delivery</label>
                                            <textarea class="summernote form-control" name="terms_delivery"
                                                id="terms_delivery" placeholder="Write Terms of Delivery"
                                                rows="1">{{ $sales->terms_delivery }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnOther" title="Previous"
                                        class="btn btn-primary">
                                        << </a>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                            <a href="{{ url('/sales-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}"
                                                class="btn customer-btn-cancel">Preview Invoice</a>
                                </div> -->
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="javascript:void(0);" id="prevBtnOther" class="btn btn-primary">
                                            <i class="ph-duotone ph-arrow-circle-up-left"></i> Back To Previous
                                        </a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="previous">
                                            <a href="{{ url('/sale-invoices') }}" class=" btn btn-danger mt-3 mt-md-0">
                                                <i class="ph-duotone ph-x-circle"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                    <div class="last d-flex">
                                        <div class="previous me-2">
                                            <button type="submit" class="btn btn-primary"> <i class="ph-duotone ph-check-fat"></i> Save changes</button>
                                        </div>
                                        <div class="next">
                                            <a href="{{ url('/sales-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}" class="btn btn-secondary mt-3 mt-md-0">
                                                <i class="ph-duotone ph-eye"></i> Preview Invoice
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                            <button type="button" id="delItemSales" data-bs-dismiss="modal" class="w-100 btn btn-primary">
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

<style>
    .digital-signature-upload .upload-area {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .digital-signature-upload .upload-area:hover {
        border-color: #6571ff;
        background: #f0f2ff;
    }

    .digital-signature-upload .upload-content {
        color: #666;
    }

    .digital-signature-upload .upload-area.dragover {
        border-color: #6571ff;
        background: #f0f2ff;
    }
</style>

<script>

	function changeProductType() {

			var base_url = "{{ url('/') }}";
			var id = $("#prod_type").val();

			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
			});

			if (id != "") {

				$.ajax({
					url: base_url + "/getProductType",
					dataType: "json",
					type: "POST",
					data: {
						id: id
					},

					success: function(data) {

						$("#prod_id").empty();

						var str = '<option value="">Select</option>';

						$.each(data, function(idx, item) {

							str += '<option value="' + item.id + '">' +
								item.name +
								'</option>';
						});

						$("#prod_id").html(str);

					},

					error: function(xhr) {
						console.log(xhr.responseText);
					}
				});

			} else {

				$("#prod_id").html('<option value="">Select</option>');

				$("#hsn_sac_code").val("");
				$("#gst_rate").val(0);
				$("#goods_desc").val("");
				$("#disc_sell").val(0);
				$("#prod_gov_fee").val(0);

				$("#billing_type").prop("selectedIndex", 0);
				$("#gst_trans").prop("selectedIndex", 0);
				$("#disc_sell_type").prop("selectedIndex", 0);
			}
		}


		function addProductItems() {

			var base_url = "{{ url('/') }}";

			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
				},
			});

			var prod_id = $("#prod_id").val();
			var billing_type = $("#billing_type").val();
			var prod_gov_fee = $("#prod_gov_fee").val();
			var gst_trans = $("#gst_trans").val();
			var disc_sell = $("#disc_sell").val();
			var disc_sell_type = $("#disc_sell_type").val();
			var gst_allocation_text = $("#gst_allocation_text").val();
			var sId = $("#sId").val();

			if (prod_id == "") {

				alert("Please select product");

			} else if (billing_type == "") {

				alert("Please select billing type");

			} else if (billing_type == "gov" && prod_gov_fee == "") {

				alert("Please enter fees");

			} else {

				$("#invoiceData").html("");

				$.ajax({
					method: "POST",
					url: base_url + "/sales_items_display",

					data: {
						sId: sId,
						prod_id: prod_id,
						billing_type: billing_type,
						prod_gov_fee: prod_gov_fee,
						gst_trans: gst_trans,
						disc_sell: disc_sell,
						disc_sell_type: disc_sell_type,
						gst_allocation_text: gst_allocation_text
					},

					datatype: "json",

					success: function(result) {

						$("#invoiceData").html(result);

					},

					error: function(xhr) {

						console.log(xhr.responseText);

					}
				});
			}
		}
		
	$(document).ready(function () {

		$('#tab-B, #tab-C, #tab-D').addClass('disabled');

		$("#addProductService").on("click", function () {
			$("#productServiceModal").modal("show");
		});

		$(document).on("change", "#prod_id", function () {

			var base_url = "{{ url('/') }}";
			var prod_id = $(this).val();

			if (!prod_id) return;

			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
			});

			$.ajax({
				url: base_url + "/getProduct",
				type: "POST",
				dataType: "json",
				data: {
					prod_id: prod_id
				},

				success: function (data) {

					if (data.length > 0) {

						var product = data[0];

						$("#hsn_sac_code").val(product.hsn_sac_code);
						$("#goods_desc").val(product.goods_desc);
						$("#disc_sell").val(product.disc_sell);
						$("#disc_sell_type").val(product.disc_sell_type);
						$("#gst_rate").val(product.gst_rate);

						if (typeof updateGSTAllocation === "function") {
							updateGSTAllocation();
						}
					}
				}
			});
		});

	});
	
	function loadGrandTotal() 
	{
		let invoiceId = $("#sId").val();
		let payStatus = $("#pay_status").val();

		if (invoiceId !== "") {
			$.get('/get-invoice-total/' + invoiceId, function (res) {

				$("#total_amount").val(res.total.toFixed(2));
			});
		}
	}
	$(document).ready(function () {
		$("#pay_status").on("change", function () {
			loadGrandTotal();
		});
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
                    otherDispatchDiv.style.display = "block"; // Show the "Other Dispatch Details" div
                    otherDispatchInput.disabled = false; // Enable the input field
                } else {
                    otherDispatchDiv.style.display = "none"; // Hide the "Other Dispatch Details" div
                    otherDispatchInput.disabled = true; // Disable the input field
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
	
    document.addEventListener("DOMContentLoaded", function() {
 
        var base_url = "{{ url('/') }}";

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
                        $("#city").empty();
                        var str = '<option value="">Select City</option>';
                        $.each(data, function(idx, item) {
                            str +=
                                '<option value="' +
                                item.id +
                                '">' +
                                item.name +
                                "</option>";
                        });
                        $("#city").html(str);
                    },
                });
            } else {
                alert("No state selected!");
            }
        });




        // Correct form submission event
        $("form#addSalesFrm").on("submit", function(e) {
            // alert('hh');
            e.preventDefault(); // Prevent default form submission

            // if ($("#addSalesFrm").valid()) { // Correct validation check
            $("#addSalesLoader").show();
            var sId = $("#sId").val();
            var surl = sId === "" ? "/save_sales_invoice" : "/update_sales_invoice";

            var salesData = $("form#addSalesFrmTop").serialize() + "&" + $("form#addSalesFrm").serialize();

            $.ajax({
                url: surl,
                type: "POST",
                data: salesData,
                success: function(response) {
                    $("#addSalesLoader").hide();
                    if (response.class === "succ") {
                        if (sId === "") {
                            window.location.href = response.redirect;
                        } else {
                            $("#tab-A").removeClass("active");
                            $("#tab-B").addClass("active");
                            $("#basic").hide();
                            $("#customer").show().addClass("show active");

                        }
                    } else {
                        var errorHtml = "";
                        $.each(response, function(idx, obj) {
                            errorHtml += '<div class="err">' + obj + "</div>";
                        });
                        $("#addSalesFrm .message-container").html(errorHtml);
                    }
                }
            });
            //}
        });
        $("form#addSalesFrm #nextBtnSeller").on("click", function() {
            // alert('Hello');
            $("#tab-A").removeClass("active");
            $("#tab-B").addClass("active");
            $("#basic").hide();
            $("#customer").show();
            $("#customer").addClass("show");
            $("#customer").addClass("active");
        })
        $("form#addSalesFrmTwo #prevBtnCust").on("click", function() {
            $("#tab-B").removeClass("active");
            $("#tab-A").addClass("active");

            $("#customer").hide();
            $("#basic").show();
            $("#basic").addClass("show");
            $("#basic").addClass("active");
        });

        $("form#addSalesFrmThree #prevBtnProd").on("click", function() {
            $("#tab-C").removeClass("active");
            $("#tab-B").addClass("active");

            $("#product").hide();
            $("#customer").show();
            $("#customer").addClass("show");
            $("#customer").addClass("active");
        });
        // });

        $("form#addSalesFrmTwo").on("submit", function(e) {
            e.preventDefault();
            //if (addSalesFrmTwo.form()) {
            $("#editSalesLoader").show();
            var itemurl = "/update_sales_customer";
            var custData = $("form#addSalesFrmTwo").serialize();

            $.ajax({
                url: itemurl,
                type: "POST",
                data: custData,
                success: function(result) {
                    $("#editSalesLoader").hide();
                    $("#tab-B").removeClass("active");
                    $("#tab-C").addClass("active");

                    $("#customer").hide();
                    $("#product").show();
                    $("#product").addClass("show");
                    $("#product").addClass("active");
                },
            });
            //}
        });







        


        $("form#addSalesFrmThree").on("submit", function(e) {
            e.preventDefault();



            // if (addSalesFrmThree.form()) {
            $("#editSalesLoader").show();
            let signature = $("#addSalesFrmThree #signature").prop("files")[0];
            let signature_name = $("#addSalesFrmThree #signature_name").val();
            let discount_amount = $("#addSalesFrmThree #discount_amount").val();
            let id = $("#sId").val();
            let sales_data = new FormData();

            let tdsApplicable = $("input[name='tds_applicable']:checked").val();

            let tdsPercentage = "";
            let taxableAmt = "";
            let tds_amount = "";
            let tdsPercentageWithId = "";
            let tds_id = "";


            if (tdsApplicable === "yes") {
                tdsPercentageWithId = $("#tds_percentage").val();

                // Split the 'tds_percentage' value by the hyphen ('-') to get the percentage part
                tdsPercentage = tdsPercentageWithId.split('-')[0];
                tds_id = tdsPercentageWithId.split('-')[1];

                taxableAmt = $("#taxableAmt").val();
                tds_amount = parseFloat(taxableAmt) + (parseFloat(taxableAmt) * (parseFloat(tdsPercentage) / 100));
            }


            sales_data.append("signature", signature);
            sales_data.append("signature_name", signature_name);
            sales_data.append("id", id);
            sales_data.append("tdsApplicable", tdsApplicable);
            sales_data.append("tdsPercentage", tdsPercentage);
            sales_data.append("tds_id", tds_id);
            sales_data.append("tds_amount", tds_amount);
            sales_data.append("discount_amount", discount_amount);

            $.ajax({
                url: base_url + "/update_sales_invoice_final",
                type: "POST",
                data: sales_data,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#editSalesLoader").hide();
                    if (response.class == "succ") {
						loadGrandTotal();
                        $("#addSalesFrmThree .message-container").html(
                            '<div class="' +
                            response.class +
                            '">' +
                            response.message +
                            "</div>"
                        );
                        //window.location.href=response.redirect;
                        $("#tab-C").removeClass("active");
                        $("#tab-D").addClass("active");

                        $("#product").hide();
                        $("#other").show();
                        $("#other").addClass("show");
                        $("#other").addClass("active");
                    } else {
                        $.each(response, function(idx, obj) {
                            $("#addSalesFrmThree .message-container").html(
                                '<div class="err">' + obj + "</div>"
                            );
                        });
                    }
                },
            });
            //}
        });

        $("form#addSalesFrmFour").on("submit", function(e) {
            e.preventDefault();
            $("#editSalesLoader").show(); // Show the loader

            var itemurl = base_url + "/update_sales_other";
            var custData = $("form#addSalesFrmFour").serialize(); // Serialize form data

            $.ajax({
                url: itemurl,
                type: "POST",
                data: custData,
                success: function(response) {
                    
                    // console.log(response);  
                    $("#editSalesLoader").hide(); 
                    
                    if (response.status == "success") {
                        
                        showToast(response.message, "success");
                        
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 3000); 
                    } else if (response.status == "error") {
                    
                        showToast(response.message, "error");
                    } else {
                        
                        showToast("An unexpected error occurred. Please try again.", "error");
                    }
                },
                // error: function(xhr, status, error) {
                //     // Handle AJAX error
                //     $("#editSalesLoader").hide();
                //     showToast("An unexpected error occurred. Please try again.", "error");
                // }
            });
        });


    });


    document.addEventListener("DOMContentLoaded", function() {
        let discountInput = document.getElementById("discount_amount");
        let grandTotalElement = document.getElementById("grand_total_amount");

        // alert(grandTotalElement);
        // Store the initial grand total
        let grandTotal = <?php echo getRoundedAmount($totalAmount + $totalTax); ?>;

        discountInput.addEventListener("input", function() {

            let discount = parseFloat(this.value) || 0; // Get discount value, default to 0 if invalid
            let newTotal = grandTotal - discount; // Subtract discount from grand total
            grandTotalElement.innerHTML = `₹${newTotal.toFixed(2)}`; // Update the total amount display
        });
    });


    //--------------- Address type Dropdown change -------------

    document.addEventListener("DOMContentLoaded", function() {
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


    document.addEventListener("DOMContentLoaded", function() {
        // Existing code...

        // Signature Type Handling
        const signatureTypeSelect = document.getElementById('signature_type');
        const normalSignatureFields = document.getElementById('normalSignatureFields');
        const digitalSignatureField = document.getElementById('digitalSignatureField');

        function toggleSignatureFields() {
            const selectedValue = signatureTypeSelect.value;

            if (selectedValue === 'normal') {
                normalSignatureFields.style.display = 'flex';
                digitalSignatureField.style.display = 'none';
            } else if (selectedValue === 'digital') {
                normalSignatureFields.style.display = 'none';
                digitalSignatureField.style.display = 'flex';
            } else {
                normalSignatureFields.style.display = 'none';
                digitalSignatureField.style.display = 'none';
            }
        }

        // Add event listener for signature type change
        signatureTypeSelect.addEventListener('change', toggleSignatureFields);

        // Initialize fields on page load
        toggleSignatureFields();
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Existing digital signature upload code...

        // Digital Signature Upload Handling
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.querySelector('.file-input');
        const previewContainer = document.getElementById('previewContainer');
        const signaturePreview = document.getElementById('signaturePreview');
        const removeButton = document.getElementById('removeSignature');

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length) {
                handleFile(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleFile(e.target.files[0]);
            }
        });

        removeButton.addEventListener('click', () => {
            fileInput.value = '';
            previewContainer.style.display = 'none';
            uploadArea.style.display = 'block';
        });

        function handleFile(file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    signaturePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    uploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                alert('Please upload an image file');
            }
        }

        // Show preview if signature exists
        if (fileInput.dataset.existing) {
            signaturePreview.src = fileInput.dataset.existing;
            previewContainer.style.display = 'block';
            uploadArea.style.display = 'none';
        }
    });

    //-------Bank Details Dropdown change --------
    


    $(document).ready(function () {
        let qr = $('#bank_id option:selected').data('qr');

        if (qr) {
            $('#bankQrPreview').attr('src', qr).show();
        }

        $('#bank_id').on('change', function () {
            let qr = $(this).find(':selected').data('qr');

            if (qr) {
                $('#bankQrPreview').attr('src', qr).show();
            } else {
                $('#bankQrPreview').hide();
            }
        });
    });

    function startEditSalesInvoiceTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Edit Sales Invoice Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Modify customer invoice items, taxes, discounts, or terms.</p></div>'
                },
                {
                    title: 'Edit Sales Invoice',
                    intro: 'Modify customer invoice items, taxes, discounts, or terms.'
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
        $('#start-edit-sales-invoice-tour').on('click', function(e) {
            e.preventDefault();
            startEditSalesInvoiceTour();
        });
    });
</script>
@endsection