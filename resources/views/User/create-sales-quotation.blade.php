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
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Sales</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Sales Quotation Invoice</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-create-sales-quotation-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Create Sales Quotation</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    {{-- Top Invoice Info (static) --}}
    <form action="javascript:void(0);" method="post" name="addSalesFrmTop" id="addSalesFrmTop">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inv_num">Quotation ID<span class="text-danger">*</span></label>
                        <input type="text" name="inv_num" id="inv_num" class="form-control" value="Click/QTN/25/0001" readonly>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inv_date">Date<span class="text-danger">*</span></label>
                        <input type="date" name="inv_date" id="inv_date" class="form-control">
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
                            <a href="#sellerDetail" data-bs-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Seller Details</span>
                            </a>
                        </li>
                        <li class="nav-item" data-target-form="#customerDetailForm">
                            <a href="#customerDetails" data-bs-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Customer Details</span>
                            </a>
                        </li>
                        <li class="nav-item" data-target-form="#itemDetailForm">
                            <a href="#itemDetails" data-bs-toggle="tab" class="nav-link icon-btn">
                                <i class="ph-duotone ph-archive-box"></i>
                                <span class="d-none d-sm-inline">Product / Service Details</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#others" data-bs-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-accessible"></i>
                                <span class="d-none d-sm-inline">Other Details</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        {{-- SELLER DETAILS TAB --}}
                        <div class="tab-pane show active" id="sellerDetail">
                            <form action="javascript:void(0);" method="post" name="addSalesFrm" id="addSalesFrm" enctype="multipart/form-data">
                                {{-- Static page: no hidden DB id --}}
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_name" id="seller_name" placeholder="Seller Name" value="Demo Company Pvt. Ltd.">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_contact" id="seller_contact" placeholder="Contact Number" value="9876543210">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Email Address<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_email" id="seller_email" placeholder="Email Address" value="info@democompany.com">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pan" id="seller_pan" placeholder="Pan Number" value="ABCDE1234F">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">GST Number</label>
                                                <input type="text" class="form-control" name="seller_gst" id="seller_gst" placeholder="GST Number" value="22ABCDE1234F1Z5">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">URN Number</label>
                                                <input type="text" class="form-control" name="udyam_reg_no" id="udyam_reg_no" placeholder="URN Number" value="UDYAM-WB-01-0000001">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addone" id="seller_addone" placeholder="Address Line 1" value="123, Demo Street">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addtwo" id="seller_addtwo" placeholder="Address Line 2" value="Near Demo Market">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">State<span class="text-danger">*</span></label>
                                                <select class="form-control select-style" name="seller_state" id="seller_state">
                                                    <option value="">Select State</option>
                                                    <option value="1" selected>West Bengal</option>
                                                    <option value="2">Maharashtra</option>
                                                    <option value="3">Delhi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">City<span class="text-danger">*</span></label>
                                                <select class="form-control" name="seller_city" id="city_id">
                                                    <option value="">Select City</option>
                                                    <option value="1" selected>Kolkata</option>
                                                    <option value="2">Mumbai</option>
                                                    <option value="3">Delhi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pin" id="seller_pin" placeholder="Enter Pin Code" value="700001">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="message-container"></div>
                                <div id="addSalesLoader" class="loader" style="display:none;"></div>

                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="#" class="btn btn-danger mt-3 mt-md-0">
                                            <i class="ph-duotone ph-x-circle"></i> Cancel
                                        </a>
                                    </div>
                                    <div class="last">
                                        <button type="submit" class="btn btn-primary mt-3 mt-md-0">
                                            Save &amp; Proceed <i class="ph-duotone ph-arrow-circle-up-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- CUSTOMER DETAILS TAB --}}
                        <div class="tab-pane" id="customerDetails">
                            <form id="address" method="post" action="javascript:void(0);">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gst" id="select_customer" required>
                                                <option label="Select Customer"></option>
                                                <option>Customer 1</option>
                                                <option>Customer 2</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Number" disabled value="9876500000">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" placeholder="Enter Email Address" disabled value="customer@example.com">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Pan Number" disabled value="AAAPL1234A">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number" disabled value="22AAAPL1234A1Z5">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label" for="InvoiceaddressType">Address Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="gst" id="InvoiceaddressType" required>
                                                <option value="billing" selected>Billing Address</option>
                                                <option value="shipping">Shipping Address</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" id="billingAddress">
                                        <h5 class="text-muted mb-4">Billing Address</h5>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 1" value="Customer Billing Address 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 2" value="Customer Billing Address 2">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter City" value="Kolkata">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter State" value="West Bengal">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Pin Code" value="700001">
                                        </div>
                                    </div>

                                    <div class="row d-none" id="shippingAddress">
                                        <h5 class="text-muted mb-4">Shipping Address</h5>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 1" value="Customer Shipping Address 1">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 2" value="Customer Shipping Address 2">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">City<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter City" value="Kolkata">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">State<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter State" value="West Bengal">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Pin Code" value="700001">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- ITEM DETAILS TAB --}}
                        <div class="tab-pane" id="itemDetails">
                            <form method="post" action="javascript:void(0);">
                                <div class="row">
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Item Type<span class="text-danger">*</span></label>
                                        <select class="form-control" name="item_type">
                                            <option label="Select Type"></option>
                                            <option>Product</option>
                                            <option>Services</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Product / Service Name<span class="text-danger">*</span></label>
                                        <select class="form-control" name="item_name">
                                            <option label="Select"></option>
                                            <option>Product 1</option>
                                            <option>Product 2</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Billing Type<span class="text-danger">*</span></label>
                                        <select class="form-control" name="billing_type">
                                            <option label="Select"></option>
                                            <option>Product/ Service Billing</option>
                                            <option>Government Payment</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">HSN / SAC Code</label>
                                        <input type="text" class="form-control" placeholder="Enter HSN / SAC Code" value="998314">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST Rate</label>
                                        <input type="text" class="form-control" placeholder="Enter GST Rate" value="18%">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST Transaction Mode</label>
                                        <select class="form-control" name="gst_mode">
                                            <option label="Select"></option>
                                            <option>Intra State</option>
                                            <option>Inter State</option>
                                            <option>Union Territory</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST(%) Allocation</label>
                                        <input type="text" class="form-control" placeholder="Enter GST(%) Allocation" value="9% CGST + 9% SGST">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Discount on selling Price</label>
                                        <div class="form-group">
                                            <div class="input-group mb-0">
                                                <input type="text" class="form-control has-success" name="disc_sell" id="disc_sell" value="0" aria-label="Selling Price" placeholder="Discount" style="width: 60%;">
                                                <select class="form-select has-success" name="disc_sell_type" id="disc_sell_type" aria-label="Select Action" style="width: 40%;">
                                                    <option value="percentage" selected>Percentage</option>
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
                                                        <td><input type="text" class="form-control" placeholder="Quantity" value="1"></td>
                                                        <td><input type="text" class="form-control" placeholder="Item Price" value="8000"></td>
                                                        <td>₹0</td>
                                                        <td>₹8000</td>
                                                        <td class="text-center">
                                                            <a href="#" class="avtar avtar-s btn-link-primary btn-pc-default" data-bs-toggle="tooltip" aria-label="Edit" data-bs-original-title="Edit">
                                                                <i class="ti ti-pencil f-20"></i>
                                                            </a>
                                                            <span data-bs-toggle="tooltip" aria-label="Delete" data-bs-original-title="Delete">
                                                                <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                                    <i class="ti ti-trash f-20"></i>
                                                                </a>
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
                                                    <input type="text" class="form-control" value="₹0.00">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">Sub Total :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹8,000.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">Discount :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end text-success">₹0.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">CGST (9%) :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹720.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">SGST (9%) :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹720.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹9,440.00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Signature<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Name" value="Authorized Signatory">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label">Upload Signature<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- OTHER DETAILS TAB --}}
                        <div class="tab-pane" id="others">
                            <form action="javascript:void(0);" method="post">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Mode of Payment<span class="text-danger">*</span></label>
                                            <select name="mode_of_pay" id="mode_of_pay" class="form-select has-success">
                                                <option value="">Select</option>
                                                <option value="IMPS" selected>IMPS</option>
                                                <option value="RTGS">RTGS</option>
                                                <option value="NEFT">NEFT</option>
                                                <option value="UPI">UPI</option>
                                                <option value="CARD">Credit/Debit Card</option>
                                                <option value="CASH">Cash</option>
                                                <option value="OTHER">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" id="other_payment_div" style="display: none;">
                                        <div class="form-group">
                                            <label>Specify Other Payment Method<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="other_payment" id="other_payment" placeholder="Specify Other Payment Method">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Payment Status</label>
                                            <select class="form-select has-success" name="pay_status" id="pay_status">
                                                <option value="">Select</option>
                                                <option value="Full">Full Payment</option>
                                                <option value="Partial">Partial Payment</option>
                                                <option value="Due" selected>Due</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" id="partial" style="display: none;">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Total Amount</label>
                                                <input type="text" name="total_amount" id="total_amount" value="9440" class="form-control" placeholder="Total Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Advance Amount</label>
                                                <input type="text" name="advance_amount" id="advance_amount" value="2000" class="form-control" placeholder="Advance Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Due Amount</label>
                                                <input type="text" name="due_amount" id="due_amount" value="7440" class="form-control" placeholder="Due Amount">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Dispatch Information</label>
                                            <select class="form-select" name="disp_through" id="disp_through">
                                                <option value="">Select</option>
                                                <option value="Road Transportation">Road Transportation</option>
                                                <option value="Rail Transportation">Rail Transportation</option>
                                                <option value="Air Transportation">Air Transportation</option>
                                                <option value="Sea Transportation">Sea Transportation</option>
                                                <option value="Multi model Transportation">Multi model Transportation</option>
                                                <option value="Parcel &amp; Courier Service" selected>Parcel &amp; Courier Service</option>
                                                <option value="By Hand">By Hand</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" style="display: none;" id="other_dispatch">
                                        <div class="form-group">
                                            <label>Other Dispatch Details</label>
                                            <input type="text" name="other_dispa_det" id="other_dispa_det" class="form-control" placeholder="Other Dispatch Details" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Validity Period<span class="text-danger">*</span></label>
                                            <input type="text" name="" id="" value="" class="form-control" placeholder="Validity Period">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3 description-box">
                                        <div class="form-group" id="summernote_container">
                                            <label class="form-control-label">Terms</label>
                                            <textarea class="summernote form-control" name="terms_delivery" id="terms_delivery" placeholder="Write Terms of Delivery" rows="5">
                                                A) This document represents only a commercial offer/order confirmation. It is not valid for Input Tax Credit (ITC), GST compliance, E-Way Bill or E-Invoice.
                                                B) Final GST Invoice will be issued at the time of supply or advance receipt as per Section 31 of GST Act, 2017.
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- END TABS --}}
                    </div>
                </div>
            </div>
            <!-- end tab content-->
        </div>
    </div>
</div>

{{-- Delete modal (static) --}}
<div class="modal custom-modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header text-center">
                    <h3>Delete This Service / Product</h3>
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
    document.addEventListener("DOMContentLoaded", function () {
        // Address Type toggle
        const addressTypeDropdown = document.getElementById("InvoiceaddressType");
        if (addressTypeDropdown) {
            addressTypeDropdown.addEventListener("change", function () {
                const selectedValue = this.value;
                const billingAddress = document.getElementById("billingAddress");
                const shippingAddress = document.getElementById("shippingAddress");

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

        // Dispatch "Other" toggle
        const dispatchDropdown = document.getElementById("disp_through");
        const otherDispatchDiv = document.getElementById("other_dispatch");
        const otherDispatchInput = document.getElementById("other_dispa_det");

        if (dispatchDropdown) {
            dispatchDropdown.addEventListener("change", function () {
                if (this.value === "Other") {
                    otherDispatchDiv.style.display = "block";
                    otherDispatchInput.disabled = false;
                } else {
                    otherDispatchDiv.style.display = "none";
                    otherDispatchInput.disabled = true;
                }
            });
        }

        // Payment status (Partial) toggle
        const paymentStatusDropdown = document.getElementById("pay_status");
        const partialRow = document.getElementById("partial");

        if (paymentStatusDropdown && partialRow) {
            paymentStatusDropdown.addEventListener("change", function () {
                if (this.value === "Partial") {
                    partialRow.style.display = "flex";
                } else {
                    partialRow.style.display = "none";
                }
            });

            // Initialize on load
            if (paymentStatusDropdown.value === "Partial") {
                partialRow.style.display = "flex";
            } else {
                partialRow.style.display = "none";
            }
        }

        // Static demo: Show loader + fake success when Seller form submitted
        const sellerForm = document.getElementById("addSalesFrm");
        const loader = document.getElementById("addSalesLoader");

        if (sellerForm && loader) {
            sellerForm.addEventListener("submit", function (e) {
                e.preventDefault();
                loader.style.display = "block";

                setTimeout(function () {
                    loader.style.display = "none";
                    alert("Static Demo: Seller details saved (no backend).");
                }, 1000);
            });
        }
    });

    function startCreateSalesQuotationTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Create Sales Quotation Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Design and draft sales proposals for potential deals.</p></div>'
                },
                {
                    title: 'Create Sales Quotation',
                    intro: 'Design and draft sales proposals for potential deals.'
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
        $('#start-create-sales-quotation-tour').on('click', function(e) {
            e.preventDefault();
            startCreateSalesQuotationTour();
        });
    });
</script>

@endsection
