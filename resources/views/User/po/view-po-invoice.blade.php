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
                        <li class="breadcrumb-item"><a href="{{ route('user.PurchaseOrder') }}">Purchase Order (PO)</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Purchase Order</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Purchase Order</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="row align-item-center mb-4">
        <h2 class="text-muted">View Purchase Order</h2>
    </div>
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
        <form action="javascript:void(0);" method="post" name="addPoFrmTop" id="addPoFrmTop">
        @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <input type="text" name="inv_num" id="inv_num" class="form-control" value="{{ isset($sales->inv_num)?$sales->inv_num:''}}" placeholder="Invoice Number" required="" readonly>
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
                        <div class="tab-pane show active" id="buyer" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPoFrm" id="addPoFrm" enctype="multipart/form-data">
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
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="seller_email" id="seller_email" value="{{ $comp_email }}" placeholder="Enter Email Address" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pan" id="seller_pan" value="{{ $comp_pan_no }}" placeholder="Enter Pan Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_gst" id="seller_gst" value="{{ $gst_no }}" placeholder="Enter GST Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Contact Person Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ $comp_bill_name }}"  name="seller_person_name" id="seller_person_name" placeholder="Enter Contact Name">
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Contact Person Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ $seller_person_no }}" name="seller_person_no" id="seller_person_no" placeholder="Enter Contact Person Number">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addone" value="{{ $comp_bill_addone }}" id="seller_addone" placeholder="Enter Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
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
                                    <a href="{{ url('/purchase-order') }}" class="btn btn-secondary cancel me-2">Cancel</a>
                                    
                                    <?php if($sales->seller_name !=""){ ?>
                                    <a href="javascript:void(0);" id="nextBtnBuyer" title="Next"
                                        class="btn btn-secondary cancel me-2"> >> </a>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <!-- end contact detail tab pane -->
                        <div class="tab-pane" id="seller" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPoFrmTwo" id="addPoFrmTwo"
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
                                            <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="cust_email" placeholder="Enter Email Address" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cust_pan" placeholder="Enter Pan Number" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
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
                                            <label class="form-label">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_bill_contact" id="cust_bill_contact" class="form-control" placeholder="Enter Contact Person Name">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Number<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_bill_mobilno" id="cust_bill_mobilno" class="form-control" placeholder="Enter Contact Person Number">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Designation<span class="text-danger">*</span></label>
                                            <input type="text" name="cust_bill_designa" id="cust_bill_designa" class="form-control" placeholder="Enter Contact Person Designation">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" name="bill_addone" id="bill_addone" class="form-control" placeholder="Enter Address Line 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
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
                                            class="btn btn-secondary cancel me-2"><< </a>
                                                                                               
											<?php if($sales->seller_name !=""){ ?>
											<a href="javascript:void(0);" id="nextBtnSeller" title="Next"
												class="btn btn-secondary cancel me-2"> >> </a>
											<?php } ?>
                                    </div>
                            </form>
                        </div>
                        <!-- end job detail tab pane -->
                        <div class="tab-pane" id="product" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPoFrmThree"
                            id="addPoFrmThree" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                                @csrf
                                
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
                                                        <td>{{ ($value->sac_code
                                                            !="")?$value->sac_code:$value->hsn_code }}</td>
                                                        <td><input type="text" name="quantity" id="quantity_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}" data-prod_id="{{ $value->prod_id }}" value="{{ $value->quantity }}"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" class="form-control" placeholder="Quantity" value="8"></td>
                                                        <td><input type="text" name="rate" id="rate_<?php echo $value->id; ?>" data-id="{{ $value->id }}" data-sid="{{ $value->sid }}"  value="{{ $value->rate }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" class="form-control" placeholder="Item Price" value="50"></td>
                                                        <td>₹{{ $value->disc_amt }}</td>
                                                        <td>₹{{ $value->amount }}</td>
                                                       
                                                    </tr>
                                                    <?php 
														$taxableAmt += ($value->rate * $value->quantity); 
														$totalDisc += $value->disc_amt;
														$totalTax += $value->tax_amt; 
														$totalAmount += $value->amount;  
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
                                                        
                                                        <input type="text" name="discount_amount" id="discount_amount" class="form-control" value="">
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
                                                    <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="f-w-600 mb-1 text-end" id="grand_total_amount">₹<?php echo $totalAmount + $totalTax; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="message-container"></div>
                                <div id="editSalesLoader" class="loader"></div>
                                <div class="add-customer-btns text-end">
                                    <a href="javascript:void(0);" id="prevBtnProd" title="Previous" class="btn btn-secondary"><< </a>
									
									<a target="_blank" href="{{ url('/po-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}" class="btn btn-secondary mt-3 mt-md-0">Preview Invoice</a>
									<?php if($sales->seller_name !=""){ ?>
									<a href="javascript:void(0);" id="nextBtnProd" title="Next"
										class="btn btn-secondary cancel me-2"> >> </a>
									<?php } ?>
											
                                </div>
                            </form>
                        </div>
                        <!-- end education detail tab pane -->
                        <div class="tab-pane" id="other" role="tabpanel">
                        <form action="javascript:void(0);" method="post" name="addPoFrmFour"
                            id="addPoFrmFour" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="sId" value="{{ $sales->id }}">
                            @csrf
                                <div class="row">
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Mode of Payment<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select name="mode_of_pay" id="mode_of_pay" class="form-select has-success" aria-invalid="false">
                                                <option value="">Select</option>
                                                <option value="IMPS" <?php echo ($sales->mode_of_pay=="IMPS")?
                                                    "selected":"" ?>>IMPS</option>
                                                <option value="RTGS" <?php echo ($sales->mode_of_pay=="RTGS")?
                                                    "selected":"" ?>>RTGS</option>
                                                <option value="NEFT" <?php echo ($sales->mode_of_pay=="NEFT")?
                                                    "selected":"" ?>>NEFT</option>
                                                <option value="UPI" <?php echo ($sales->mode_of_pay=="UPI")?
                                                    "selected":"" ?>>UPI</option>
                                                <option value="CARD" <?php echo ($sales->mode_of_pay=="CARD")?
                                                    "selected":"" ?>>Credit/Debit Card</option>
                                                <option value="CASH" <?php echo ($sales->mode_of_pay=="CASH")?
                                                    "selected":"" ?>>Cash</option>
                                                <option value="OTHER" <?php echo ($sales->mode_of_pay=="OTHER")?
                                                    "selected":"" ?>>Other</option>
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
													<option value="Due" <?php echo ($sales->pay_status=="Due")?
                                                    "selected":"" ?>>Due</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Payment Section -->
									<div class="row" id="paymentSection" style="display:none;">
										<div class="col-md-4 mb-3">
											<label>Total Amount</label>
											<input type="text" name="total_amount" id="total_amount" value="{{ $sales->total_amount }}" class="form-control">
										</div>

										<!-- Advance Only -->
										<div class="col-md-4 mb-3 d-none" id="advanceBox">
											<label>Advance Amount</label>
											<input type="text"  name="advance_amount" id="advance_amount" value="{{ $sales->advance_amount }}" class="form-control">
										</div>

										<!-- Due Only -->
										<div class="col-md-4 mb-3 d-none" id="dueBox">
											<label>Balance Receivable</label>
											<input type="text" name="due_amount" id="due_amount" value="{{ $sales->due_amount }}" class="form-control" readonly>
										</div>

										<!-- Adjusted Only -->
										<div class="col-md-4 mb-3 d-none" id="adjustedBox">
											<label>Adjusted Amount</label>
											<input type="text" name="adjusted_amount" id="adjusted_amount" value="{{ $sales->adjusted_amount }}" class="form-control">
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
                                            <label>Order Date<span class="text-danger">*</span></label>
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
                                            <label>Dispatch through<span class="text-danger">*</span></label>
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
                                            <label for="imageUpload">Upload Image</label>
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
                                            
                                            <a target="_blank" href="{{ url('/po-invoice-pdf/'.base64_encode($sales->id).'/invoice') }}" class="btn btn-secondary mt-3 mt-md-0">Preview Invoice</a>
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
<script>
 $(document).ready(function(){
            

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
	
    document.addEventListener("DOMContentLoaded", function() {
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
    
</script>
@endsection