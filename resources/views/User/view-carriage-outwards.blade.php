@extends('App.Layout')

@section('container')

    <div class="pc-content">
        <div class="row align-item-center mb-4">
            <h2 class="text-muted">View Carriage Outward</h2>
        </div>
        <div class="card">
            <div class="card-body">
            <form action="javascript:void(0);" method="POST" name="add_invoice_detail" id="add_invoice_detail">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                        <select class="form-control error" name="inv_num" id="inv_num" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                        <option value="">Select Invoice Number</option>
                        @foreach($invoiceNumbers as $invoice)
                            <option value="{{ $invoice->inv_num }}" <?php echo ($invoice->inv_num==$carriageout->inv_num)? "selected":"" ?>>{{ $invoice->inv_num }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                        <input type="date" name="inv_date" id="inv_date" value="{{$carriageout->inv_date}}" class="form-control" placeholder="Invoice Number" readonly>
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
                                    <span class="d-none d-sm-inline">Seller Details</span>
                                </a>
                            </li>
                            <!-- end nav item -->
                            <li class="nav-item" data-target-form="#customerDetailForm">
                                <a href="javascript:void(0);" id="tab-B" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                    <i class="ti ti-user-plus"></i>
                                    <span class="d-none d-sm-inline">Customer Details</span>
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
                            <div class="tab-pane show active" id="sellerDetail">
                                <form action="javascript:void(0);" method="POST" name="add_seller_detail" id="add_seller_detail">
                                <input type="hidden" name="id" id="carriageId" value="{{ $carriageout->id }}">
                                    <div class="row mt-4">
                                        <div class="col">
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                    <input type="text" id="seller_name" name="seller_name" value="{{$carriageout->seller_name}}" class="form-control" placeholder="" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                    <input type="text" id="seller_contact" name="seller_contact" value="{{$carriageout->seller_contact}}" class="form-control" placeholder="Enter Contact Number" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                    <input type="email" id="seller_email" name="seller_email" value="{{$carriageout->seller_email}}" class="form-control" placeholder="Enter Email Address" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                    <input type="text" id="seller_pan" name="seller_pan" value="{{$carriageout->seller_pan}}" class="form-control" placeholder="Enter Pan Number" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                    <input type="text" id="seller_gst" name="seller_gst" value="{{$carriageout->seller_gst}}" class="form-control" placeholder="Enter GST Number" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                    <input type="text" id="seller_addone" name="seller_addone" value="{{$carriageout->seller_addone}}" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                    <input type="text" id="seller_addtwo" name="seller_addtwo" value="{{$carriageout->seller_addtwo}}" class="form-control" placeholder="Enter Address Line 2">
                                                </div>                                                
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="seller_state" id="seller_state">
													<option value="">Select State</option>
													@foreach($states_bill as $k=>$state)
														<option value="{{ $state->id }}"<?php echo ($state->id==$carriageout->seller_state)? "selected":"" ?> >{{ $state->name }}</option>
													@endforeach
												</select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="seller_city" id="city">
													<option value="">Select City</option>
													@foreach($cities_bill as $k=>$city)
														<option value="{{ $city->id }}"<?php echo ($city->id==$carriageout->seller_city)? "selected":"" ?> >{{ $city->name }}</option>
													@endforeach
												</select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="seller_pin" name="seller_pin" value="{{$carriageout->seller_pin}}" placeholder="Enter Pin Code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="{{url('/carriage-outward')}}" class="btn btn-secondary">
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
                            <div class="tab-pane" id="customerDetails">
                                <form  action="javascript:void(0);" method="POST" name="add_customer_detail" id="add_customer_detail">
                                    <div class="row">
                                        <div class="row mb-3">
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-select" name="cust_name" id="invNameCustomer" onchange="changeCustomer(this.value);">
                                                        <option value="">Select</option>
														@foreach($custData as $k=>$cust)
															<option value="{{ $cust->id }}"<?php echo ($cust->id==$carriageout->cust_name)? "selected":"" ?> >{{ $cust->cust_name }}</option>
														@endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" name="cust_contact" id="cust_phone" value="{{$carriageout->cust_contact}}" class="form-control" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" name="cust_email" id="cust_email" value="{{$carriageout->cust_email}}" class="form-control" placeholder="Enter Email Address" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" name="cust_pan" id="cust_pan" value="{{$carriageout->cust_pan}}" class="form-control" placeholder="Enter Pan Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" name="cust_gst" id="cust_gst" value="{{$carriageout->cust_gst}}" class="form-control" placeholder="Enter GST Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Order Number<span class="text-danger">*</span></label>
                                                <input type="date" name="cust_order_no" id="cust_order_no" value="{{$carriageout->cust_order_no}}"  class="form-control" placeholder="Enter Order Number">
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Dispatch Document Number<span class="text-danger">*</span></label>
                                                <input type="date" name="cust_dispatch_no" id="cust_dispatch_no" value="{{$carriageout->cust_dispatch_no}}" class="form-control" placeholder="Enter Dispatch Document Number">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <label class="form-label">Dispatch trough<span class="text-danger">*</span></label>
                                                    <div class="form-group">
                                                        <select class="form-select" name="disp_through" id="disp_through">
                                                            <option value="">Select</option>
                                                            <option value="Road Transportation"<?php echo ('Road Transportation'==$carriageout->disp_through)? "selected":""?>>Road
                                                                Transportation</option>
                                                            <option value="Rail Transportation" <?php echo ('Rail Transportation'==$carriageout->disp_through)? "selected":""?>>Rail
                                                                Transportation</option>
                                                            <option value="Air Transportation" <?php echo ('Air Transportation'==$carriageout->disp_through)? "selected":""?>>Air
                                                                Transportation</option>
                                                            <option value="Sea Transportation" <?php echo ('Sea Transportation'==$carriageout->disp_through)? "selected":""?>>Sea
                                                                Transportation</option>
                                                            <option value="Multi model Transportation" <?php echo ('Multi model Transportation'==$carriageout->disp_through)? "selected":""?>>Multi
                                                                model Transportation</option>
                                                            <option value="Parcel &amp; Courier Service" <?php echo ('Parcel &amp; Courier Service'==$carriageout->disp_through)? "selected":""?>> Courier Service</option>
                                                            <option value="By Hand"<?php echo ('By Hand'==$carriageout->disp_through)? "selected":""?>>By Hand</option>
                                                            <option value="Other"<?php echo ('Other'==$carriageout->disp_through)? "selected":""?>>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3" style="display: none;" id="other_dispatch">
                                                <div class="form-group ">
                                                    <label>Other Dispatch Details</label>
                                                    <input type="text" name="other_dispa_det" id="other_dispa_det" value="{{$carriageout->other_dispa_det}}" class="form-control" placeholder="Other Dispatch Details" readonly="">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3 description-box">
                                                <div class="form-group" id="summernote_container">
                                                    <label class="form-control-label">Description of Goods</label>
                                                    <textarea class="summernote form-control" name="terms_delivery" id="terms_delivery"  placeholder="Write Description of Goods" rows="3">{{$carriageout->terms_delivery}}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h5 class="mb-3">Delivery Address Details</h5>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                    <input type="text" name="cust_addone" id="cust_bill_addone" value="{{$carriageout->cust_addone}}" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                    <input type="text" name="cust_addtwo" id="cust_bill_addtwo" value="{{$carriageout->cust_addtwo}}" class="form-control" placeholder="Enter Address Line 2">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="cust_state" id="cust_bill_state" class="form-control" placeholder="Enter State">
                                                    <option value="">Select State</option>
													@foreach($states_bill as $k=>$state)
														<option value="{{ $state->id }}" <?php echo ($state->id==$carriageout->cust_state)? "selected":""?> >{{ $state->name }}</option>
													@endforeach
												</select>
                                                </div>  
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="cust_city" id="cust_bill_city">
                                                        <option value="">Select City</option>
                                                        @foreach($cities_bill as $k=>$city)
														<option value="{{ $city->id }}"<?php echo ($city->id==$carriageout->cust_city)? "selected":"" ?> >{{ $city->name }}</option>
													@endforeach
                                                    </select>
                                                </div>                                                
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                    <input type="text" name="cust_pin" id="cust_bill_pin" value="{{$carriageout->cust_pin}}" class="form-control" placeholder="Enter Pin Code">
                                                </div>
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
                                <form action="javascript:void(0);" method="POST" name="add_other_detail" id="add_other_detail">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Quantity of Goods <span class="text-danger">*</span></label>
                                            <input type="text" name="other_quantity" id="other_quantity" value="{{$carriageout->other_quantity}}" class="form-control" placeholder="Quantity of Goods">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Transport Details <span class="text-danger">*</span></label>
                                            <input type="text" name="other_transport" id="other_transport" value="{{$carriageout->other_transport}}" class="form-control" placeholder="Transport Details">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Total Transport Cost <span class="text-danger">*</span></label>
                                            <input type="text" name="other_transport_cost" id="other_transport_cost" value="{{$carriageout->other_transport_cost}}" class="form-control" placeholder="Total Transport Cost">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Insurance </label>
                                            <input type="date" name="other_insurance_date" id="other_insurance_date" value="{{$carriageout->other_insurance_date}}"  class="form-control" placeholder="Date of Paymemt">
                                        </div>

                                        <div class="tds-container col-md-6 my-3">
                                            <div class="mb-3">
                                                <label class="form-label">TDS Applicable <span class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="card shadow-sm border-0 p-3 m-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="tdsApplicable" id="tdsYes" checked>
                                                                <label class="form-check-label" for="tdsYes">Yes</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="card shadow-sm border-0 p-3 m-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="tdsApplicable" id="tdsNo">
                                                                <label class="form-check-label" for="tdsNo">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div id="tds_dropdown">
                                                    <label for="tds_percentage">TDS Percentage</label>
                                                    <select name="tds_percentage" id="tds_percentage" class="form-control has-success" aria-invalid="false">
                                                        <option value="0-1" <?php echo ('0-1'==$carriageout->tds_percentage)? "selected":""?>>Salaries (upto 3L) (0%)</option>
                                                        <option value="5-2" <?php echo ('5-2'==$carriageout->tds_percentage)? "selected":""?>>Salaries (3L - 6L) (5%)</option>
                                                        <option value="10-3" <?php echo ('10-3'==$carriageout->tds_percentage)? "selected":""?>>Salaries (6L - 9L) (10%)</option>
                                                        <option value="15-4" <?php echo ('15-4'==$carriageout->tds_percentage)? "selected":""?>>Salaries (9L - 12L) (15%)</option>
                                                        <option value="20-5" <?php echo ('20-5'==$carriageout->tds_percentage)? "selected":""?>>Salaries (12L - 15L) (20%)</option>
                                                        <option value="30-6" <?php echo ('30-6'==$carriageout->tds_percentage)? "selected":""?>>Salaries (Above 15L) (30%)</option>
                                                        <option value="10-7" <?php echo ('10-7'==$carriageout->tds_percentage)? "selected":""?>>Interest on securities (10%)</option>
                                                        <option value="10-8" <?php echo ('10-8'==$carriageout->tds_percentage)? "selected":""?>>Dividend payments (10%)</option>
                                                        <option value="10-9" <?php echo ('10-9'==$carriageout->tds_percentage)? "selected":""?>>Interest other than securities (10%)</option>
                                                        <option value="30-10" <?php echo ('30-10'==$carriageout->tds_percentage)? "selected":""?>>Winnings from lotteries, crossword puzzles, etc. (30%)</option>
                                                        <option value="1-11" <?php echo ('1-11'==$carriageout->tds_percentage)? "selected":""?>>Payments to contractors/sub-contractors (Individual/HUF) (1%)</option>
                                                        <option value="2-12" <?php echo ('2-12'==$carriageout->tds_percentage)? "selected":""?>>Payments to contractors/sub-contractors (Other) (2%)</option>
                                                        <option value="5-13"<?php echo ('5-13'==$carriageout->tds_percentage)? "selected":""?>>Insurance commission (5%)</option>
                                                        <option value="5-14" <?php echo ('5-14'==$carriageout->tds_percentage)? "selected":""?>>Commission or brokerage (5%)</option>
                                                        <option value="2-15" <?php echo ('2-15'==$carriageout->tds_percentage)? "selected":""?>>Rent payments (Plant &amp; machinery) (2%)</option>
                                                        <option value="10-16" <?php echo ('10-16'==$carriageout->tds_percentage)? "selected":""?>>Rent payments (Land/building) (10%)</option>
                                                        <option value="2-17" <?php echo ('2-17'==$carriageout->tds_percentage)? "selected":""?>>Fees for technical services (2%)</option>
                                                        <option value="10-18" <?php echo ('10-18'==$carriageout->tds_percentage)? "selected":""?>>Fees for professional services (10%)</option>
                                                        <option value="5-19" <?php echo ('5-19'==$carriageout->tds_percentage)? "selected":""?>>Rent paid by individuals or HUF (5%)</option>
                                                        <option value="0.10-20" <?php echo ('0.10-20'==$carriageout->tds_percentage)? "selected":""?>>Purchase of goods (0.10%)</option>
                                                        <option value="10-21" <?php echo ('10-21'==$carriageout->tds_percentage)? "selected":""?>>Income from units of mutual funds or specified company (10%)</option>
                                                        <option value="10-22"<?php echo ('10-22'==$carriageout->tds_percentage)? "selected":""?>>Premature withdrawal from EPF (Above 50K) (10%)</option>
                                                        <option value="5-23" <?php echo ('5-23'==$carriageout->tds_percentage)? "selected":""?>>Payment under life insurance policy (5%)</option>
                                                        <option value="1-24" <?php echo ('1-24'==$carriageout->tds_percentage)? "selected":""?>>Payment for transfer of immovable property (1%)</option>
                                                        <option value="5-25" <?php echo ('5-25'==$carriageout->tds_percentage)? "selected":""?>>Rent paid by individuals or HUF (not covered under tax audit) (5%)</option>
                                                        <option value="25-26" <?php echo ('25-26'==$carriageout->tds_percentage)? "selected":""?>>Income in respect of securitization trust (25%)</option>
                                                        <option value="2-27" <?php echo ('2-27'==$carriageout->tds_percentage)? "selected":""?>>Cash withdrawals exceeding specified limits (Above 1 Cr.) (2%)</option>
                                                        <option value="1-28" <?php echo ('1-28'==$carriageout->tds_percentage)? "selected":""?>>TDS on e-commerce transactions (1%)</option>
                                                        <option value="10-29" <?php echo ('10-29'==$carriageout->tds_percentage)? "selected":""?>>Income from foreign currency bonds or GDRs (10%)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="gst-container col-md-6 my-3">
                                            <div class="mb-3">
                                                <label class="form-label">GST Applicable <span class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="card shadow-sm border-0 p-3 m-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="gstApplicable" id="gstYes" checked>
                                                                <label class="form-check-label" for="gstYes">Yes</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="card shadow-sm border-0 p-3 m-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="gstApplicable" id="gstNo">
                                                                <label class="form-check-label" for="gstNo">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="hsn_sac_code">HSN/SAC Code</label>
                                                    <input type="text" name="other_hsn_sac_code" id="other_hsn_sac_code" value="{{$carriageout->other_hsn_sac_code}}" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="gst_rate">GST Rate (%)</label>
                                                    <input type="number" name="other_gst_rate" id="other_gst_rate" value="{{$carriageout->other_gst_rate}}" class="form-control" min="0" step="0.01">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="gst_trans">GST Transaction Mode</label>
                                                    <select class="form-select" name="other_gst_mode"  id="other_gst_mode">
                                                        <option value="">Select</option>
                                                        <option value="intrastate" <?php echo('intrastate'==$carriageout->other_gst_mode)?"selected":""?>>Intra State</option>
                                                        <option value="interstate" <?php echo('interstate'==$carriageout->other_gst_mode)?"selected":""?>>Inter State</option>
                                                        <option value="union" <?php echo('union'==$carriageout->other_gst_mode)?"selected":""?>>Union Territory</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3" id="other_payment_div">
                                            <label class="form-label">Payment Date<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="other_pay_date" id="other_pay_date" value="{{$carriageout->other_pay_date}}" placeholder="Payment Date">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Mode of Payment<span class="text-danger">*</span></label>
                                            <select class="form-select" name="other_mod_pay" id="mode_of_pay">
                                                <option value="">Select</option>
                                                <option value="IMPS" <?php echo('IMPS'==$carriageout->other_mod_pay)?"selected":""?>>IMPS</option>
                                                <option value="RTGS" <?php echo('RTGS'==$carriageout->other_mod_pay)?"selected":""?>>RTGS</option>
                                                <option value="NEFT" <?php echo('NEFT'==$carriageout->other_mod_pay)?"selected":""?>>NEFT</option>
                                                <option value="UPI" <?php echo('UPI'==$carriageout->other_mod_pay)?"selected":""?>>UPI</option>
                                                <option value="CARD" <?php echo('CARD'==$carriageout->other_mod_pay)?"selected":""?>>Credit/Debit Card</option>
                                                <option value="CASH" <?php echo('CASH'==$carriageout->other_mod_pay)?"selected":""?>>Cash</option>
                                                <option value="OTHER" <?php echo('OTHER'==$carriageout->other_mod_pay)?"selected":""?>>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" id="other_payment_div" style="display: none;">
                                            <label class="form-label">Specify Other Payment Method<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_pay_method" id="other_pay_method" value="{{$carriageout->other_pay_method}}" placeholder="Specify Other Payment Method">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Payment Status<span class="text-danger">*</span></label>
                                            <select class="form-select" name="pay_status" id="pay_status">
                                                <option value="">Select</option>
                                                <option value="Full" <?php echo('Full'==$carriageout->pay_status)?"selected":""?>>Full Payment</option>
                                                <option value="Partial" <?php echo('Partial'==$carriageout->pay_status)?"selected":""?>>Partial Payment</option>
                                                <option value="Due" <?php echo('Due'==$carriageout->pay_status)?"selected":""?>>Due</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" style="display: none;">
                                            <label class="form-label">Total Amount<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_total_amount" id="other_total_amount" value="{{$carriageout->other_total_amount}}" placeholder="Total Amount">
                                        </div>
                                        <div class="col-md-4 mb-3" style="display: none;">
                                            <label class="form-label">Advance Amount<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_adv_amount" id="other_adv_amount" value="{{$carriageout->other_adv_amount}}" placeholder="Advance Amount">
                                        </div>
                                        <div class="col-md-4 mb-3" style="display: none;">
                                            <label class="form-label">Due Amount<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_due_amount" id="other_due_amount" value="{{$carriageout->other_due_amount}}" placeholder="Advance Amount">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Payment Reference No.</label>
                                            <input type="text" name="other_refe_no" id="other_refe_no" value="{{$carriageout->other_refe_no}}" class="form-control" placeholder="Payment Reference No.">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Approve By</label>
                                            <input type="text" name="other_approve_by" id="other_approve_by" value="{{$carriageout->other_approve_by}}" class="form-control" placeholder="Approve by">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Terms of Delivery</label>
                                            <textarea name="other_term" id="other_term"  class="summernote form-control" placeholder="Description of Goods" rows="3">{{$carriageout->other_term}}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Upload Document</label>
                                            <input type="file" id="other_uplode_doc" name="other_uplode_doc" class="form-control" aria-label="file example" required="">
                                        </div>
                                    </div>
                                    <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="javascript:void(0);" id="prevBtnThree" class="btn btn-secondary">
                                            Back To Previous
                                            </a>
                                        </div>
                                        <div class="last">
                                            <a href="{{url('/carriage-outward')}}" class="btn btn-secondary">
                                              Cancel
                                            </a>
                                        </div>
                                </form>
                            </div>
                            <!-- END: Define your tab pans here -->

                            <!-- START: Define your controller buttons here
                            <div class="d-flex wizard justify-content-between mt-3">
                                <div class="first">
                                    <a href="javascript:void(0);" class="btn btn-secondary">
                                        Back To Previous
                                    </a>
                                </div>
                                <div class="last">
                                    <a href="javascript:void(0);" class="btn btn-secondary">
                                        Next Step
                                    </a>
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
<script>
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

    document.getElementById('inv_num').addEventListener('change', function () {
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

                        // console.log(sales);
                        

                        // Populate seller fields
                        document.getElementById('inv_date').value = sales.inv_date || '';
                        document.getElementById('seller_name').value = sales.seller_name || '';
                        document.getElementById('seller_contact').value = sales.seller_contact || '';
                        document.getElementById('seller_email').value = sales.seller_email || '';
                        document.getElementById('seller_pan').value = sales.seller_pan || '';
                        document.getElementById('seller_gst').value = sales.seller_gst || '';
                        
                        document.getElementById('seller_addone').value = sales.seller_addone || '';
                        document.getElementById('seller_addtwo').value = sales.seller_addtwo || '';

                        document.getElementById('seller_pin').value = sales.seller_pin || '';

                        // Populate country, state, and city
                        
                            const countrySelect = document.getElementById('country');
                            countrySelect.value = sales.seller_country || '';
                            if (!countrySelect.value) {
                                Array.from(countrySelect.options).forEach(option => {
                                    if (option.text === sales.country_name) {
                                        option.selected = true;
                                    }
                                });
                            }

                            // Populate state dropdown
                            const stateSelect = document.getElementById('state');
                            stateSelect.value = sales.seller_state || '';
                            if (!stateSelect.value) {
                                Array.from(stateSelect.options).forEach(option => {
                                    if (option.text === sales.state_name) {
                                        option.selected = true;
                                    }
                                });
                            }

                            // Populate city dropdown
                            const citySelect = document.getElementById('city');
                            citySelect.value = sales.seller_city || '';
                            if (!citySelect.value) {
                                Array.from(citySelect.options).forEach(option => {
                                    if (option.text === sales.city_name) {
                                        option.selected = true;
                                    }
                                });
                            }

                            const invNameCustomer = document.getElementById('invNameCustomer');
                                invNameCustomer.value = sales.inv_name || '';  // Set the customer ID

                                // If no value is selected, find the customer by name and select the option
                                if (!invNameCustomer.value) {
                                    Array.from(invNameCustomer.options).forEach(option => {
                                        if (option.text === sales.inv_name) {
                                            option.selected = true;
                                        }
                                    });
                                }

                            

                            changeCustomer(sales.inv_name);

                        // Add more fields as necessary
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            // Clear fields if no selection
            document.getElementById('inv_date').value = '';
            document.getElementById('seller_name').value = '';
            document.getElementById('seller_addone').value = '';
            document.getElementById('seller_addtwo').value = '';
            document.getElementById('country').value = sales.country_name || '';
            document.getElementById('state').value = sales.state_name || '';
            document.getElementById('city').value = sales.city_name || '';
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
    });
    document.getElementById("mode_of_pay").addEventListener("change", function() {
        var otherPaymentDiv = document.getElementById("other_payment_div");
        if (this.value === "OTHER") {
            otherPaymentDiv.style.display = "block";
        } else {
            otherPaymentDiv.style.display = "none";
        }
    });

    const stateDropdown = document.getElementById('seller_state');
        stateDropdown.addEventListener('change', function () {
            const id = this.value; // Get the selected value
            //alert(id);
            if (id) {

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    url:  "/getCity?" + id,
                    dataType: "json",
                    //type: "post",
                    data: { id: id },
                    success: function (data) {
                        $("#city").empty();
                        var str = '<option value="">Select City</option>';
                        $.each(data, function (idx, item) {
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


        const statecustDropdown = document.getElementById('cust_bill_state');
        statecustDropdown.addEventListener('change', function () {
            const id = this.value; // Get the selected value
           // alert(id);
            if (id) {

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    url:  "/getCity?" + id,
                    dataType: "json",
                    //type: "post",
                    data: { id: id },
                    success: function (data) {
                        $("#cust_bill_state").empty();
                        var str = '<option value="">Select City</option>';
                        $.each(data, function (idx, item) {
                            str +=
                                '<option value="' +
                                item.id +
                                '">' +
                                item.name +
                                "</option>";
                        });
                        $("#cust_bill_state").html(str);
                    },
                });
            } else {
                alert("No state selected!");
            }
        });

        
       
     $(document).ready(function () {
        var base_url = "{{ url('/') }}";
        function validateForm(form) {
            if (form[0].checkValidity()) {
            return true;
            } else {
            form[0].reportValidity(); // Shows built-in validation messages
            return false;
            }
        }


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


            $("form#add_other_detail").bind("submit", function () {
            //if (addVendorBank.form()) {
                //$("#addCustomerLoader").show();
               /* var formCarriageData = $.param($.merge(
                        $("form#add_seller_detail").serializeArray(),
                        $.merge(
                            $("form#add_customer_detail").serializeArray(),
                            $("form#add_other_detail").serializeArray()
                        )add_invoice_detail
                    ));*/

                var formCarriageData=  $("form#add_invoice_detail").serialize() +
                "&" +  $("form#add_seller_detail").serialize() +
                    "&" +
                    $("form#add_customer_detail").serialize() +
                    "&" +
                    $("form#add_other_detail").serialize();
                    console.log(formCarriageData);
                var carriageId = $("#carriageId").val();
                //alert(carriageId);
                if (carriageId == "") {
                    var suburl = base_url + "/savecarriageoutwards";
                } else {
                    var suburl = base_url + "/update_carriageoutwards";
                }
                $.ajax({
                    url: suburl,
                    type: "POST",
                    data: formCarriageData,
                    success: function (response) {
                        //alert(response);
                        $("#addCustomerLoader").hide();
                        if (response.class == "succ") {
                            //alert('Helwo');
                            //$("#add_vendor_bank .message-container").html('<div class="'+response.class+'">'+response.message+'</div>');
                            window.location.href = response.redirect;
                        } else {
                            //alert('Hello');
                            $.each(response, function (idx, obj) {
                                $("#add_vendor_bank .message-container").html(
                                    '<div class="err">' + obj + "</div>"
                                );
                            });
                        }
                    },
                });
           // }
        });

        window.changeCustomer = function () {
        
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var invcustId = $("#invNameCustomer option:selected").val();
        var salesTableID = $("#sId").val();
        //alert(salesTableID);

        if (invcustId != "") {
            $.ajax({
                url: base_url + "/getinvcust?" + invcustId,
                dataType: "json",
                //type: "post",
                data: { id: invcustId, salesTableID: salesTableID },
                success: function (data) {
                    // console.log(data);
                    // alert(data);

                    $("#cust_phone").val(data.cust_phone);
                    $("#cust_email").val(data.cust_email);
                    $("#gst_reg").val(data.gst_reg);
                    $("#cust_gst").val(data.cust_gst_no);
                    $("#cust_pan").val(data.cust_pan);
                    $("#comp_type").val(data.comp_type);
                    $("#cust_gst_type").val(data.cust_gst_type);
                    // $("#bill_name").val(data.cust_bill_name);
                    $("#bill_addone").val(data.cust_bill_addone);
                    $("#bill_addtwo").val(data.cust_bill_addtwo);
                    $("#cust_bill_country")
                        .val(data.cust_bill_country)
                        .attr("selected", "selected");
                    $("#cust_bill_state").empty();
                    var stateBillOpt = '<option value="">Select State</option>';
                    $.each(data.stateBill, function (idx, item) {
                        if (item.id == item.sid) {
                            stateBillOpt +=
                                '<option value="' +
                                item.id +
                                '" selected="">' +
                                item.name +
                                "</option>";
                        } else {
                            stateBillOpt +=
                                '<option value="' +
                                item.id +
                                '" >' +
                                item.name +
                                "</option>";
                        }
                    });
                    $("#cust_bill_state").html(stateBillOpt);
                    $("#cust_bill_city").empty();
                    var cityBillOpt = '<option value="">Select City</option>';
                    $.each(data.cityBill, function (idx, item) {
                        if (item.id == item.sid) {
                            cityBillOpt +=
                                '<option value="' +
                                item.id +
                                '" selected="">' +
                                item.name +
                                "</option>";
                        } else {
                            cityBillOpt +=
                                '<option value="' +
                                item.id +
                                '" >' +
                                item.name +
                                "</option>";
                        }
                    });
                    $("#cust_bill_city").html(cityBillOpt);
                    $("#cust_bill_pin").val(data.cust_bill_pin);

                    //Ship section
                    //$("#ship_name").val(data.cust_ship_name);
                    $("#cust_ship_addone").val(data.cust_ship_addone);
                    $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
                    $("#cust_ship_country")
                        .val(data.cust_ship_country)
                        .attr("selected", "selected");
                    $("#cust_ship_state").empty();
                    var stateShipOpt = '<option value="">Select State</option>';
                    $.each(data.stateShip, function (idx, item) {
                        if (item.id == item.selid) {
                            stateShipOpt +=
                                '<option value="' +
                                item.id +
                                '" selected="">' +
                                item.name +
                                "</option>";
                        } else {
                            stateShipOpt +=
                                '<option value="' +
                                item.id +
                                '" >' +
                                item.name +
                                "</option>";
                        }
                    });
                    $("#cust_ship_state").html(stateShipOpt);
                    $("#cust_ship_city").empty();
                    var cityShipOpt = '<option value="">Select City</option>';
                    $.each(data.cityShip, function (idx, item) {
                        if (item.id == item.selid) {
                            cityShipOpt +=
                                '<option value="' +
                                item.id +
                                '" selected="">' +
                                item.name +
                                "</option>";
                        } else {
                            cityShipOpt +=
                                '<option value="' +
                                item.id +
                                '" >' +
                                item.name +
                                "</option>";
                        }
                    });
                    $("#cust_ship_city").html(cityShipOpt);
                    $("#cust_ship_pin").val(data.cust_ship_pin);

                    $("#cust_bill_gstno").val(data.cust_bill_gstno);
                    $("#cont_person").val(data.cust_bill_contact);
                    $("#contact_name").val(data.cust_bill_contact);

                    $("#cont_person_no").val(data.cust_bill_mobilno);
                    $("#cust_bill_designa").val(data.cust_bill_designa);
                     $("#cust_bill_addone").val(data.cust_bill_addone);
                    $("#cust_bill_addtwo").val(data.cust_bill_addtwo);
                    $("#cust_bill_pin").val(data.cust_bill_pin);

                    $("#cust_ship_gstno").val(data.cust_ship_gstno);
                    $("#cust_ship_contact").val(data.cust_ship_contact);
                    $("#cust_ship_mobilno").val(data.cust_ship_mobilno);
                    $("#cust_ship_designa").val(data.cust_ship_designa);
                    // $("#cust_ship_name").val(data.cust_ship_name);

                    // $("#cust_ship_addone").val(data.cust_ship_addone);
                    // $("#cust_ship_addtwo").val(data.cust_ship_addtwo);
                },
            });
        } else {
            $("#contact_no").val("");
            $("#cust_email").val("");
            $("#cust_pan").val("");
            $("#cust_gst_no").val("");
            $("#cust_name").val("");

            $("#cust_bill_addone").val("");
            $("#cust_bill_addtwo").val("");
            $("#cust_bill_state").empty();
            $("#cust_bill_city").empty();
            $("#cust_bill_pin").val("");

            $("#cust_bill_gstno").val("");
            $("#cont_person").val("");
            $("#cont_person_no").val("");
            $("#cust_bill_designa").val("");
            // $("#cust_bill_name").val("");
            $("#bill_addone").val("");
            $("#bill_addtwo").val("");

            $("#cust_ship_addone").val("");
            $("#cust_ship_addtwo").val("");
            $("#cust_ship_state").empty();
            $("#cust_ship_city").empty();
            $("#cust_ship_pin").val("");

            $("#cust_ship_gstno").val("");
            $("#cust_ship_contact").val("");
            $("#cust_ship_mobilno").val("");
            $("#cust_ship_designa").val("");
            // $("#cust_ship_name").val("");
        }
    }

    });

        
</script>
@endsection
