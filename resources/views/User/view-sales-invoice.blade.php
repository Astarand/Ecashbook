@extends('App.Layout')

@section('container')

<div class="pc-content">
    <div class="row align-item-center mb-4">
        <h2 class="text-muted">View Sales Invoice</h2>
    </div>
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
                                <div id="addSalesLoader" class="loader"></div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="{{ url('/sale-invoices') }}" class="btn btn-secondary cancel me-2">Cancel</a>
                                    <?php if ($sales->seller_name != "") { ?>
                                        <a href="javascript:void(0);" id="nextBtnSeller" title="Next"
                                            class="btn btn-secondary cancel me-2"> >> </a>
                                    <?php } ?>
                                </div> -->
                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="{{ url('/sale-invoices') }}" class=" btn btn-danger mt-3 mt-md-0">
                                            <i class="ph-duotone ph-x-circle"></i> Cancel
                                        </a>
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
                                            <select class="form-select" name="add_type" id="add_type">
                                                <option selected value="billing_add" <?php echo ($sales->add_type == "billing_add") ? "selected" : "" ?>>Billing Address</option>
                                                <option value="shipping_add" <?php echo ($sales->add_type == "shipping_add") ? "selected" : "" ?>>Shipping Address
                                                </option>
                                                <option value="both" <?php echo ($sales->add_type == "both") ?
                                                                            "selected" : "" ?>>Both</option>
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
                                            <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                            <select class="form-control select-style" name="cust_bill_state" id="cust_bill_state">
                                                <option value="">Select State</option>
                                                @foreach($states_seller as $k=>$state)
                                                <option value="{{ $state->id }}" <?php echo ($state->id == $sales->seller_state) ? "selected" : "" ?>>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                            <select class="form-control" name="cust_bill_city" id="cust_bill_city">
                                                <option value="">Select City</option>
                                                @foreach($cities_seller as $k=>$city)
                                                <option value="{{ $city->id }}" <?php echo ($city->id == $sales->seller_city) ?
                                                                                    'selected="selected"' : "" ?>>{{ $city->name }}</option>
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
                                            <input type="text" class="form-control" id="" name="" placeholder="Enter Pin Code">
                                        </div>
                                    </div>

                                </div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnCust" title="Previous"
                                        class="btn btn-secondary cancel me-2">
                                        << </a>
                                            <?php if ($sales->seller_name != "") { ?>
                                                <a href="javascript:void(0);" id="nextBtnCust" title="Next"
                                                    class="btn btn-secondary cancel me-2"> >> </a>
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
                                    <div class="col-12">
                                        <div class="table-responsive mb-2" id="invoiceData">
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
                                                        <td><input type="text" class="form-control" id="quantity_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" data-prod_id="{{ $value->prod_id }}" class="form-control quantity" placeholder="Quantity" value="{{ $value->quantity }}"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                                        <td><input type="text" class="form-control" name="rate" id="rate_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}"  class="form-control rate" placeholder="Item Price" value="{{ $value->rate }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                                        <td>₹{{ $value->disc_amt }}</td>
                                                        <td>₹{{ $value->amount }}</td>
                                                       
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
                                                <p class="f-w-600 mb-1 text-end">₹{{ number_format(getRoundedAmount($totalAmount + $totalTax + $totalGovPay + $totalSerPay), 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputEmail4">Signature<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="signature_name" id="signature_name" value="{{ $sales->signature_name }}" placeholder="Enter Name">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="inputEmail4">Upload Signature<span class="text-danger">*</span></label>
                                        <input type="file" name="signature" id="signature" value="{{ $sales->signature }}" class="form-control" placeholder="Enter Name">
                                        @if(@$sales->signature !="")
                                        <div class="downloadFile"><a target="_blank"
                                                href="{{ asset('uploads/invoice-signature/'.$sales->signature) }}">Download</a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!-- <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnProd" title="Previous" class="btn btn-secondary">
                                        << </a>
                                            <?php if ($sales->signature_name != "") { ?>
                                                <a href="javascript:void(0);" id="nextBtnProd" title="Next"
                                                    class="btn btn-secondary cancel me-2"> >> </a>
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
											<input type="text" name="total_amount" id="total_amount" value="{{ $sales->total_amount }}" class="form-control">
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
                                        <div class="form-group ">
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
                                        class="btn btn-secondary">
                                        << </a>

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
                                        
                                        <div class="next">
                                            <a target="_blank" href="{{ url('/sales-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}" class="btn btn-secondary mt-3 mt-md-0">
                                                <i class="ph-duotone ph-eye"></i> Preview Invoice
                                            </a>
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
                            <button type="button" data-bs-dismiss="modal" class="w-100 btn btn-secondary">
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
				<input type="hidden" id="isViewPage" value="1">

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
						<th>Bank</th>
                        <th id="actionHeader" width="80">Action</th>
                    </tr>
                    </thead>

                    <tbody id="voucherRows">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
	$('#tab-B, #tab-C, #tab-D').addClass('disabled');
	
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

    });

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
	
	/*document.addEventListener("DOMContentLoaded", function() {
		
		const payStatus = document.getElementById("pay_status");

		const paymentSection = document.getElementById("paymentSection");
		const totalAmount = document.getElementById("total_amount");

		const advanceBox = document.getElementById("advanceBox");
		const dueBox = document.getElementById("dueBox");
		const adjustedBox = document.getElementById("adjustedBox");

		const advanceAmount = document.getElementById("advance_amount");
		const dueAmount = document.getElementById("due_amount");
		const adjustedAmount = document.getElementById("adjusted_amount");

		const grandTotalElement = document.getElementById("grand_total_amount");

		// ✅ Detect edit mode
		let isEditMode = advanceAmount.value !== "" || dueAmount.value !== "";

		// Set total amount
		if (grandTotalElement && totalAmount) {
			let amt = grandTotalElement.textContent.replace(/[₹,]/g, '').trim();
			totalAmount.value = parseFloat(amt || 0).toFixed(2);
		}

		function resetFields() {
			advanceAmount.value = "";
			dueAmount.value = "";
			adjustedAmount.value = "";
		}

		function togglePaymentUI(reset = false) {

			let status = payStatus.value;

			if (!status) {
				paymentSection.style.display = "none";
				return;
			}

			paymentSection.style.display = "flex";

			// Hide all
			advanceBox.classList.add("d-none");
			dueBox.classList.add("d-none");
			adjustedBox.classList.add("d-none");

			// ✅ Reset ONLY when user changes
			if (reset) {
				resetFields();
			}

			if (status === "Full") {

				adjustedBox.classList.remove("d-none");

				let total = parseFloat(totalAmount.value) || 0;

				// Only overwrite if not edit mode
				if (reset || !isEditMode) {
					adjustedAmount.value = total.toFixed(2);
				}

				adjustedAmount.readOnly = true;

			} else if (status === "Partial") {

				advanceBox.classList.remove("d-none");
				dueBox.classList.remove("d-none");

				adjustedAmount.readOnly = false;

				// Only calculate if empty
				if (!dueAmount.value) {
					calculateDue();
				}
			}
		}

		function calculateDue() {

			let total = parseFloat(totalAmount.value) || 0;
			let advance = parseFloat(advanceAmount.value) || 0;

			if (advance > total) {
				advanceAmount.value = total.toFixed(2);
				advance = total;
			}

			let due = total - advance;

			dueAmount.value = due.toFixed(2);
		}

		// ✅ When user changes → reset values
		payStatus.addEventListener("change", function () {
			isEditMode = false; // now user changed manually
			togglePaymentUI(true);
		});

		advanceAmount.addEventListener("input", calculateDue);
		togglePaymentUI(false);
		
    });*/
</script>
@endsection