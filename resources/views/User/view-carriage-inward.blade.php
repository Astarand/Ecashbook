@extends('App.Layout')

@section('container')

    <div class="pc-content">
        <div class="row align-item-center mb-4">
            <h2 class="text-muted">view Carriage Inward</h2>
        </div>
        <div class="card">
            <div class="card-body">
            <form action="javascript:void(0);" method="POST" name="add_invoice_detail" id="add_invoice_detail">
            <input type="hidden" name="id" id="carrinId" value="{{$carriageId}}">
                <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <select class="form-control error" name="inv_num" id="inv_num" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                    @foreach($invoiceNumbers as $invoice)
                            <option value="{{ $invoice->inv_num }}" <?php echo ($invoice->inv_num==$carriageIn->inv_num)? "selected":"" ?>>{{ $invoice->inv_num }}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inputEmail4">Date<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="inv_date" id="inv_date" value="{{$carriageIn->inv_date}}" placeholder="Invoice Number" readonly>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <div id="employeeEnroll" class="form-wizard row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item" data-target-form="#sellerDetailForm">
                                <a href="#javascript:void(0);" id="tab-A" data-bs-toggle="tab" data-toggle="tab" class="nav-link active">
                                    <i class="ph-duotone ph-user-circle"></i>
                                    <span class="d-none d-sm-inline">Buyer Details</span>
                                </a>
                            </li>
                            <!-- end nav item -->
                            <li class="nav-item" data-target-form="#customerDetailForm">
                                <a href="#javascript:void(0);" id="tab-B" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
                                    <i class="ti ti-user-plus"></i>
                                    <span class="d-none d-sm-inline">Vendor Details</span>
                                </a>
                            </li>
                            <!-- end nav item -->
                            <!-- end nav item -->
                            <li class="nav-item">
                                <a href="#javascript:void(0);" id="tab-C" data-bs-toggle="tab" data-toggle="tab" class="nav-link icon-btn">
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
                            <div class="tab-pane show active" id="customerDetails">
                            <form action="javascript:void(0);" method="POST" name="add_buyer_detail" id="add_buyer_detail">
                            <input type="hidden" name="id" id="carriageId" value="{{ $carriageIn->id }}">
                                    <div class="row mt-4">
                                        <div class="col">
                                            <div class="row">
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_name" name="buyer_name" value="{{ $carriageIn->buyer_name }}" class="form-control" placeholder="" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_contact" name="buyer_contact" value="{{ $carriageIn->buyer_contact }}" class="form-control" placeholder="Enter Contact Number" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                    <input type="email" id="buyer_email" name="buyer_email" value="{{ $carriageIn->buyer_email }}" class="form-control" placeholder="Enter Email Address" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_pan" name="buyer_pan" value="{{ $carriageIn->buyer_pan }}" class="form-control" placeholder="Enter Pan Number" readonly>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_gst" name="buyer_gst" value="{{ $carriageIn->buyer_gst }}" class="form-control" placeholder="Enter GST Number" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_addone" name="buyer_addone" value="{{ $carriageIn->buyer_addone }}" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                    <input type="text" id="buyer_addtwo" name="buyer_addtwo" value="{{ $carriageIn->buyer_addtwo }}" class="form-control" placeholder="Enter Address Line 2">
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="buyer_state" id="seller_state">													
													@foreach($states_bill as $k=>$state)
														<option value="{{ $state->id }}"<?php echo ($state->id==$carriageIn->buyer_state)? "selected":"" ?> >{{ $state->name }}</option>
													@endforeach
												</select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>
                                                    <select class="form-control select-style" name="buyer_city" id="seller_city">													
													@foreach($cities_bill as $k=>$city)
														<option value="{{ $city->id }}"<?php echo ($city->id==$carriageIn->buyer_city)? "selected":"" ?> >{{ $city->name }}</option>
													@endforeach
												</select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                    <input type="text" name="buyer_pin" id="buyer_pin" value="{{ $carriageIn->buyer_pin }}" class="form-control" value="" placeholder="Enter Pin Code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="{{url('/carriage-inward')}}" class="btn btn-secondary">
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
                            <div class=" tab-pane" id="vendorDetails">
                            <form action="javascript:void(0);" method="POST" name="add_vendor_detail" id="add_vendor_detail">
                                    <div class="row">
                                        <div class="row mb-3">
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Company Name <span class="text-danger">*</span></label>
                                                <select class="form-control error" name="vendor_name" id="vendor_name" required="" aria-describedby="bouncer-error_select" aria-invalid="true">
                                                <option label="Select Company"></option>
                                                    @foreach($vendorData as $vendor)
                                                        <option value="{{ $vendor->id }}" <?php echo ($vendor->id==$carriageIn->vendor_name)? "selected":"" ?>>{{ $vendor->vendor_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" name="vendor_contact" id="vendor_contact" value="{{ $carriageIn->vendor_contact }}" class="form-control" placeholder="Enter Contact Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" name="vendor_email" id="vendor_email" value="{{ $carriageIn->vendor_email }}" class="form-control" placeholder="Enter Email Address" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" name="vendor_pan" id="vendor_pan" value="{{ $carriageIn->vendor_pan }}" class="form-control" placeholder="Enter Pan Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" name="vendor_gst" id="vendor_gst" value="{{ $carriageIn->vendor_gst }}" class="form-control" placeholder="Enter GST Number" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Order Number<span class="text-danger">*</span></label>
                                                <input type="date" name="vendor_order_no" id="vendor_order_no" value="{{ $carriageIn->vendor_order_no }}" class="form-control" placeholder="Enter Order Number">
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label" for="inputEmail4">Dispatch Document Number<span class="text-danger">*</span></label>
                                                <input type="date" name="vendor_dispatch_no" id="vendor_dispatch_no" value="{{ $carriageIn->vendor_dispatch_no }}" class="form-control" placeholder="Enter Dispatch Document Number">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group">
                                                    <label class="form-label">Dispatch trough<span class="text-danger">*</span></label>
                                                    <div class="form-group">
                                                        <select class="form-select" name="disp_through" id="disp_through">
                                                            <option value="">Select</option>
                                                            <option value="Road Transportation"<?php echo ('Road Transportation'==$carriageIn->disp_through)? "selected":""?>>Road
                                                                Transportation</option>
                                                            <option value="Rail Transportation"<?php echo ('Rail Transportation'==$carriageIn->disp_through)? "selected":""?>>Rail
                                                                Transportation</option>
                                                            <option value="Air Transportation"<?php echo ('Air Transportation'==$carriageIn->disp_through)? "selected":""?>>Air
                                                                Transportation</option>
                                                            <option value="Sea Transportation"<?php echo ('Sea Transportation'==$carriageIn->disp_through)? "selected":""?>>Sea
                                                                Transportation</option>
                                                            <option value="Multi model Transportation"<?php echo ('Multi model Transportation'==$carriageIn->disp_through)? "selected":""?>>Multi
                                                                model Transportation</option>
                                                            <option value="Parcel Courier Service"<?php echo ('Parcel Courier Service'==$carriageIn->disp_through)? "selected":""?>>Parcel &amp;
                                                                Courier Service</option>
                                                            <option value="By Hand"<?php echo ('By Hand'==$carriageIn->disp_through)? "selected":""?>>By Hand</option>
                                                            <option value="Other"<?php echo ('Other'==$carriageIn->disp_through)? "selected":""?>>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3" style="display: none;" id="other_dispatch">
                                                <div class="form-group ">
                                                    <label>Other Dispatch Details</label>
                                                    <input type="text" name="other_dispa_det" id="other_dispa_det" value="" class="form-control" placeholder="Other Dispatch Details" readonly="">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3 description-box">
                                                <div class="form-group" id="summernote_container">
                                                    <label class="form-control-label">Description of Goods</label>
                                                    <textarea class="summernote form-control" name="terms_delivery" id="terms_delivery" placeholder="Write Description of Goods" rows="3">{{ $carriageIn->terms_delivery }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <h5 class="mb-3">Delivery Address Details</h5>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 1<span class="text-danger">*</span></label>
                                                    <input type="text" id="vendor_addone" name="vendor_addone" value="{{ $carriageIn->vendor_addone }}" class="form-control" placeholder="Enter Address Line 1">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="inputEmail4">Address Line 2<span class="text-danger">*</span></label>
                                                    <input type="text" id="vendor_addtwo" name="vendor_addtwo" value="{{ $carriageIn->vendor_addtwo }}" class="form-control" placeholder="Enter Address Line 2">
                                                </div>                                               
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">State<span class="text-danger">*</span></label>                                                    
                                                    <select class="form-control select-style" name="vendor_state" id="vendor_state">
													<option value="">Select State</option>
													@foreach($states_bill as $k=>$state)
														<option value="{{ $state->id }}"<?php echo ($state->id==$carriageIn->vendor_state)? "selected":"" ?> >{{ $state->name }}</option>
													@endforeach
												</select>
                                                
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">City<span class="text-danger">*</span></label>                                                    
                                                    <select class="form-control select-style" name="vendor_city" id="vendor_city">
													<option value="">Select City</option>
													@foreach($cities_bill as $k=>$city)
														<option value="{{ $city->id }}"<?php echo ($city->id==$carriageIn->vendor_city)? "selected":"" ?> >{{ $city->name }}</option>
													@endforeach
												</select>
                                                </div>
                                                <div class="mb-3 col-md-4">
                                                    <label class="form-label" for="inputEmail4">Pincode<span class="text-danger">*</span></label>
                                                    <input type="text" id="vendor_pin" name="vendor_pin" value="{{ $carriageIn->vendor_pin }}" class="form-control" placeholder="Enter Pin Code">
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
                            <div class=" tab-pane" id="add_other_details">
                            <form action="javascript:void(0);" method="POST" name="add_other_detail" id="add_other_detail">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Quantity of Goods <span class="text-danger">*</span></label>
                                            <input type="text" id="other_quantity" name="other_quantity" value="{{ $carriageIn->other_quantity }}" class="form-control" placeholder="Quantity of Goods">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Transport Details <span class="text-danger">*</span></label>
                                            <input type="text" id="other_transport" name="other_transport" value="{{ $carriageIn->other_transport }}" class="form-control" placeholder="Transport Details">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Total Transport Cost <span class="text-danger">*</span></label>
                                            <input type="text" id="other_transport_cost" name="other_transport_cost" value="{{ $carriageIn->other_transport_cost }}" class="form-control" placeholder="Total Transport Cost">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Insurance </label>
                                            <input type="date"  name="other_insurance_date" id="other_insurance_date" value="{{ $carriageIn->other_insurance_date }}" class="form-control" placeholder="Date of Paymemt">
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
                                                    <option value="0-1" <?php echo ('0-1'==$carriageIn->tds_percentage)? "selected":""?>>Salaries (upto 3L) (0%)</option>
                                                        <option value="5-2" <?php echo ('5-2'==$carriageIn->tds_percentage)? "selected":""?>>Salaries (3L - 6L) (5%)</option>
                                                        <option value="10-3" <?php echo ('10-3'==$carriageIn->tds_percentage)? "selected":""?>>Salaries (6L - 9L) (10%)</option>
                                                        <option value="15-4" <?php echo ('15-4'==$carriageIn->tds_percentage)? "selected":""?>>Salaries (9L - 12L) (15%)</option>
                                                        <option value="20-5" <?php echo ('20-5'==$carriageIn->tds_percentage)? "selected":""?>>Salaries (12L - 15L) (20%)</option>
                                                        <option value="30-6" <?php echo ('30-6'==$carriageIn->tds_percentage)? "selected":""?>>Salaries (Above 15L) (30%)</option>
                                                        <option value="10-7" <?php echo ('10-7'==$carriageIn->tds_percentage)? "selected":""?>>Interest on securities (10%)</option>
                                                        <option value="10-8" <?php echo ('10-8'==$carriageIn->tds_percentage)? "selected":""?>>Dividend payments (10%)</option>
                                                        <option value="10-9" <?php echo ('10-9'==$carriageIn->tds_percentage)? "selected":""?>>Interest other than securities (10%)</option>
                                                        <option value="30-10" <?php echo ('30-10'==$carriageIn->tds_percentage)? "selected":""?>>Winnings from lotteries, crossword puzzles, etc. (30%)</option>
                                                        <option value="1-11" <?php echo ('1-11'==$carriageIn->tds_percentage)? "selected":""?>>Payments to contractors/sub-contractors (Individual/HUF) (1%)</option>
                                                        <option value="2-12" <?php echo ('2-12'==$carriageIn->tds_percentage)? "selected":""?>>Payments to contractors/sub-contractors (Other) (2%)</option>
                                                        <option value="5-13"<?php echo ('5-13'==$carriageIn->tds_percentage)? "selected":""?>>Insurance commission (5%)</option>
                                                        <option value="5-14" <?php echo ('5-14'==$carriageIn->tds_percentage)? "selected":""?>>Commission or brokerage (5%)</option>
                                                        <option value="2-15" <?php echo ('2-15'==$carriageIn->tds_percentage)? "selected":""?>>Rent payments (Plant &amp; machinery) (2%)</option>
                                                        <option value="10-16" <?php echo ('10-16'==$carriageIn->tds_percentage)? "selected":""?>>Rent payments (Land/building) (10%)</option>
                                                        <option value="2-17" <?php echo ('2-17'==$carriageIn->tds_percentage)? "selected":""?>>Fees for technical services (2%)</option>
                                                        <option value="10-18" <?php echo ('10-18'==$carriageIn->tds_percentage)? "selected":""?>>Fees for professional services (10%)</option>
                                                        <option value="5-19" <?php echo ('5-19'==$carriageIn->tds_percentage)? "selected":""?>>Rent paid by individuals or HUF (5%)</option>
                                                        <option value="0.10-20" <?php echo ('0.10-20'==$carriageIn->tds_percentage)? "selected":""?>>Purchase of goods (0.10%)</option>
                                                        <option value="10-21" <?php echo ('10-21'==$carriageIn->tds_percentage)? "selected":""?>>Income from units of mutual funds or specified company (10%)</option>
                                                        <option value="10-22"<?php echo ('10-22'==$carriageIn->tds_percentage)? "selected":""?>>Premature withdrawal from EPF (Above 50K) (10%)</option>
                                                        <option value="5-23" <?php echo ('5-23'==$carriageIn->tds_percentage)? "selected":""?>>Payment under life insurance policy (5%)</option>
                                                        <option value="1-24" <?php echo ('1-24'==$carriageIn->tds_percentage)? "selected":""?>>Payment for transfer of immovable property (1%)</option>
                                                        <option value="5-25" <?php echo ('5-25'==$carriageIn->tds_percentage)? "selected":""?>>Rent paid by individuals or HUF (not covered under tax audit) (5%)</option>
                                                        <option value="25-26" <?php echo ('25-26'==$carriageIn->tds_percentage)? "selected":""?>>Income in respect of securitization trust (25%)</option>
                                                        <option value="2-27" <?php echo ('2-27'==$carriageIn->tds_percentage)? "selected":""?>>Cash withdrawals exceeding specified limits (Above 1 Cr.) (2%)</option>
                                                        <option value="1-28" <?php echo ('1-28'==$carriageIn->tds_percentage)? "selected":""?>>TDS on e-commerce transactions (1%)</option>
                                                        <option value="10-29" <?php echo ('10-29'==$carriageIn->tds_percentage)? "selected":""?>>Income from foreign currency bonds or GDRs (10%)</option>
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
                                                    <input type="text" name="other_hsn_sac_code" id="other_hsn_sac_code" value="{{ $carriageIn->other_hsn_sac_code }}" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="gst_rate">GST Rate (%)</label>
                                                    <input type="number" name="other_gst_rate" id="other_gst_rate" value="{{ $carriageIn->other_gst_rate }}" class="form-control" min="0" step="0.01">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="gst_trans">GST Transaction Mode</label>
                                                    <select class="form-select" name="other_gst_mode" id="other_gst_mode">
                                                        <option value="">Select</option>
                                                        <option value="intrastate" <?php echo('intrastate'==$carriageIn->other_gst_mode)?"selected":""?>>Intra State</option>
                                                        <option value="interstate" <?php echo('interstate'==$carriageIn->other_gst_mode)?"selected":""?>>Inter State</option>
                                                        <option value="union" <?php echo('union'==$carriageIn->other_gst_mode)?"selected":""?>>Union Territory</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3" id="other_payment_div">
                                            <label class="form-label">Payment Date<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="other_pay_date" id="other_pay_date" value="{{ $carriageIn->other_pay_date }}" placeholder="Payment Date">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Mode of Payment<span class="text-danger">*</span></label>
                                            <select class="form-select" name="other_mod_pay" id="other_mod_pay">
                                                <option value="">Select</option>
                                                <option value="IMPS" <?php echo('IMPS'==$carriageIn->other_mod_pay)?"selected":""?>>IMPS</option>
                                                <option value="RTGS" <?php echo('RTGS'==$carriageIn->other_mod_pay)?"selected":""?>>RTGS</option>
                                                <option value="NEFT"<?php echo('NEFT'==$carriageIn->other_mod_pay)?"selected":""?>>NEFT</option>
                                                <option value="UPI"<?php echo('UPI'==$carriageIn->other_mod_pay)?"selected":""?>>UPI</option>
                                                <option value="CARD"<?php echo('CARD'==$carriageIn->other_mod_pay)?"selected":""?>>Credit/Debit Card</option>
                                                <option value="CASH"<?php echo('CASH'==$carriageIn->other_mod_pay)?"selected":""?>>Cash</option>
                                                <option value="OTHER"<?php echo('OTHER'==$carriageIn->other_mod_pay)?"selected":""?>>Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" id="other_payment_div" style="display: none;">
                                            <label class="form-label">Specify Other Payment Method<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_pay_method" id="other_pay_method" value="{{ $carriageIn->other_pay_method }}" placeholder="Specify Other Payment Method">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Payment Status<span class="text-danger">*</span></label>
                                            <select class="form-select" name="pay_status" id="pay_status">
                                                <option value="">Select</option>
                                                <option value="Full"<?php echo('Full'==$carriageIn->pay_status)?"selected":""?>>Full Payment</option>
                                                <option value="Partial"<?php echo('Partial'==$carriageIn->pay_status)?"selected":""?>>Partial Payment</option>
                                                <option value="Due"<?php echo('Due'==$carriageIn->pay_status)?"selected":""?>>Due</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3" style="display: none;">
                                            <label class="form-label">Total Amount<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_total_amount" id="other_total_amount" value="{{ $carriageIn->other_total_amount }}" placeholder="Total Amount">
                                        </div>
                                        <div class="col-md-4 mb-3" style="display: none;">
                                            <label class="form-label">Advance Amount<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_adv_amount" id="other_adv_amount" value="{{ $carriageIn->other_adv_amount }}" placeholder="Advance Amount">
                                        </div>
                                        <div class="col-md-4 mb-3" style="display: none;">
                                            <label class="form-label">Due Amount<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_due_amount" id="other_due_amount" value="{{ $carriageIn->other_due_amount }}" placeholder="Advance Amount">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Payment Reference No.</label>
                                            <input type="text" id="other_refe_no" name="other_refe_no" value="{{ $carriageIn->other_refe_no }}" class="form-control" placeholder="Payment Reference No.">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Approve By</label>
                                            <input type="text" id="other_approve_by" name="other_approve_by" value="{{ $carriageIn->other_approve_by }}" class="form-control" placeholder="Approve by">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Terms of Delivery</label>
                                            <textarea name="other_term" id="other_term" class="summernote form-control" placeholder="Description of Goods" rows="3">{{ $carriageIn->other_term }}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Upload Document</label>
                                            <input type="file" id="other_uplode_doc" name="other_uplode_doc" value="{{ $carriageIn->other_uplode_doc }}"  class="form-control" aria-label="file example" required="">
                                        </div>
                                    </div>
                                    <div class="d-flex wizard justify-content-between mt-3">
                                        <div class="first">
                                            <a href="javascript:void(0);" id="prevBtnThree" class="btn btn-secondary">
                                            Back To Previous
                                            </a>
                                        </div>
                                        <div class="last">
                                        <a href="{{url('/carriage-inward')}}" class="btn btn-secondary">
                                              Cancel
                                            </a>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {
//alert('mm');

    $("#nxtBtnVOne").on("click", function () {               
                $("#tab-A").removeClass("active");
                $("#tab-B").addClass("active");
                // Activate the Customer Details tab
                $("#customerDetails").hide();
                $("#vendorDetails").show();
                $("#vendorDetails").addClass("show");
                $("#vendorDetails").addClass("active");
            });

            $("#nxtBtnVTwo").on("click", function () {
                $("#tab-B").removeClass("active");
                $("#tab-C").addClass("active");

                $("#vendorDetails").hide();
                $("#add_other_details").show();
                $("#add_other_details").addClass("show");
                $("#add_other_details").addClass("active");
            });
            $("#prevBtnTwo").on("click", function () {
                $("#tab-B").removeClass("active");
                $("#tab-A").addClass("active");

                $("#vendorDetails").hide();
                $("#customerDetails").show();
                $("#customerDetails").addClass("show");
                $("#customerDetails").addClass("active");
            });
            $("#prevBtnThree").on("click", function () {
                $("#tab-C").removeClass("active");
                $("#tab-B").addClass("active");

                $("#add_other_details").hide();
                $("#vendorDetails").show();
                $("#vendorDetails").addClass("show");
                $("#vendorDetails").addClass("active");
            });

    $('#inv_num').on('change', function () {
    //alert('hi');
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
                    if (document.getElementById('buyer_name')) {
                        document.getElementById('buyer_name').value = sales.seller_name || '';
                    }

                    if (document.getElementById('buyer_contact')) {
                        document.getElementById('buyer_contact').value = sales.seller_contact || '';
                    }

                    if (document.getElementById('buyer_email')) {
                        document.getElementById('buyer_email').value = sales.seller_email || '';
                    }
                    if (document.getElementById('buyer_pan')) {
                        document.getElementById('buyer_pan').value = sales.seller_pan || '';
                    }
                    if (document.getElementById('buyer_gst')) {
                        document.getElementById('buyer_gst').value = sales.seller_gst || '';
                    }
                    
                    if (document.getElementById('buyer_addone')) {
                        document.getElementById('buyer_addone').value = sales.seller_addone || '';
                    }
                    if (document.getElementById('buyer_addtwo')) {
                        document.getElementById('buyer_addtwo').value = sales.seller_addtwo || '';
                    }
                    if (document.getElementById('buyer_pin')) {
                        document.getElementById('buyer_pin').value = sales.seller_pin || '';
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



                    const citySelect = document.getElementById('seller_city');
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


    $('#vendor_name').on('change', function () {
        const vendorId = this.value;
        //alert(invNum);
        var base_url = "{{ url('/') }}";

        if (vendorId) {
            fetch(base_url + '/fetch-vendor-details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ id: vendorId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const sales = data.data;

                    // Populate fields only if elements exist
                    if (document.getElementById('vendor_contact')) {
                        document.getElementById('vendor_contact').value = sales.vendor_phone || '';
                    }
                    if (document.getElementById('vendor_email')) {
                        document.getElementById('vendor_email').value = sales.vendor_email || '';
                    }

                    if (document.getElementById('vendor_pan')) {
                        document.getElementById('vendor_pan').value = sales.vendor_pan || '';
                    }

                    if (document.getElementById('vendor_gst')) {
                        document.getElementById('vendor_gst').value = sales.vendor_gstin || '';
                    }
                    if (document.getElementById('vendor_addone')) {
                        document.getElementById('vendor_addone').value = sales.billing_address1 || '';
                    }
                    if (document.getElementById('vendor_addtwo')) {
                        document.getElementById('vendor_addtwo').value = sales.billing_address2 || '';
                    }
                    if (document.getElementById('vendor_pin')) {
                        document.getElementById('vendor_pin').value = sales.billing_pincode || '';
                    }
                    	
                    // Check if dropdowns exist before setting values
                    const stateSelect = document.getElementById('vendor_state');
                    if (stateSelect) {
                        const stateValue = sales.billing_state || ''; // Make sure this is the state ID
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



                    const citySelect = document.getElementById('billing_city');
                    if (citySelect) {
                        citySelect.value = sales.billing_city || '';
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

    $("form#add_other_detail").bind("submit", function () {
                var base_url = "{{ url('/') }}";
                var formCarrinData=  $("form#add_invoice_detail").serialize() +
                "&" +  $("form#add_buyer_detail").serialize() +
                    "&" +
                    $("form#add_vendor_detail").serialize() +
                    "&" +
                    $("form#add_other_detail").serialize();
                    console.log(formCarrinData);
                var carrinId = $("#carrinId").val();
                //alert(carrinId);
                if (carrinId == "") {
                    var suburl = base_url + "/savecarriageinwards";
                } else {
                    var suburl = base_url + "/updatecarriageinwards";
                }
                $.ajax({
                    url: suburl,
                    type: "POST",
                    data: formCarrinData,
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



    </script>
@endsection
