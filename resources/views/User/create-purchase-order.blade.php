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
                        <li class="breadcrumb-item"><a href="">Purchase Order</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Purchase Order Invoice</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Create Purchase Order Invoice</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="card">
        <div class="card-body">
            <form action="javascript:void(0);" method="post" name="addPurchaseFrmTop" id="addPurchaseFrmTop">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inv_num">Purchase Order Invoice Number <span class="text-danger">*</span></label>
                        <input type="text" name="inv_num" id="inv_num" class="form-control" value="Click/PO/25/0001" placeholder="Invoice Number" readonly>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label" for="inv_date">Date<span class="text-danger">*</span></label>
                        <input type="date" name="inv_date" id="inv_date" value="2025-01-01" class="form-control" placeholder="Invoice Date">
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
                        <li class="nav-item" data-target-form="#buyerDetailForm">
                            <a href="#buyerDetail" data-bs-toggle="tab" class="nav-link active">
                                <i class="ph-duotone ph-user-circle"></i>
                                <span class="d-none d-sm-inline">Purchaser / Buyer Details</span>
                            </a>
                        </li>
                        <li class="nav-item" data-target-form="#sellerDetailForm">
                            <a href="#sellerDetails" data-bs-toggle="tab" class="nav-link icon-btn">
                                <i class="ti ti-user-plus"></i>
                                <span class="d-none d-sm-inline">Seller / Vendor Details</span>
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
                        <!-- BUYER DETAIL TAB -->
                        <div class="tab-pane show active" id="buyerDetail">
                            <form action="javascript:void(0);" method="post" name="addPurchaseFrm" id="addPurchaseFrm" enctype="multipart/form-data">
                                <div class="row mt-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" name="seller_name" id="seller_name" value="Demo Buyer Company Pvt. Ltd." class="form-control" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_contact" id="seller_contact" value="9876543210" readonly>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" name="seller_email" id="seller_email" value="buyer@example.com" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label">Pan Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_pan" id="seller_pan" value="ABCDE1234F" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label">GST Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_gst" id="seller_gst" value="22ABCDE1234F1Z5" readonly>
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label">Contact Person Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="Rahul Sharma" name="seller_person_name" id="seller_person_name" placeholder="Enter Contact Name">
                                            </div>
                                            <div class="mb-3 col-md-3">
                                                <label class="form-label">Contact Person Number<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="9876500000" name="seller_person_no" id="seller_person_no" placeholder="Enter Contact Person Number">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addone" value="123, Demo Buyer Street" id="seller_addone" placeholder="Enter Address Line 1">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="seller_addtwo" id="seller_addtwo" value="Near Central Market" placeholder="Enter Address Line 2">
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">State<span class="text-danger">*</span></label>
                                                <select class="form-control" name="seller_state" id="state">
                                                    <option value="">Select State</option>
                                                    <option value="1" selected>West Bengal</option>
                                                    <option value="2">Maharashtra</option>
                                                    <option value="3">Delhi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">City<span class="text-danger">*</span></label>
                                                <select class="form-control" name="seller_city" id="city">
                                                    <option value="">Select City</option>
                                                    <option value="1" selected>Kolkata</option>
                                                    <option value="2">Mumbai</option>
                                                    <option value="3">Delhi</option>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-4">
                                                <label class="form-label">Pincode<span class="text-danger">*</span></label>
                                                <input type="text" name="seller_pin" id="seller_pin" value="700001" class="form-control" placeholder="Enter Pin Code">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex wizard justify-content-between mt-3">
                                    <div class="first">
                                        <a href="#" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                    <div class="last">
                                        <button type="submit" id="nxtBtnVThree" class="btn btn-secondary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- SELLER / VENDOR DETAILS TAB -->
                        <div class="tab-pane" id="sellerDetails">
                            <form id="address" method="post" action="javascript:void(0);">
                                <div class="row">
                                    <div class="row mb-3">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Seller / Vendor Name <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gst" id="select_vendor" required>
                                                <option label="Select Customer"></option>
                                                <option>Seller 1</option>
                                                <option>Seller 2</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Number" readonly value="9999900000">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Email Address<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" placeholder="Enter Email Address" readonly value="seller@example.com">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Pan Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Pan Number" readonly value="AAAPL1234A">
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">GST Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number" readonly value="19AAAPL1234A1Z5">
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
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">GST Number</label>
                                            <input type="text" class="form-control" placeholder="Enter GST Number" value="19AAAPL1234A1Z5">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Name" value="Vendor Contact">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Number" value="9123456789">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Contact Person Designation<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Contact Person Designation" value="Manager">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 1<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 1" value="45, Vendor Street">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Address Line 2<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Address Line 2" value="Near Vendor Market">
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
                                            <input type="text" class="form-control" placeholder="Enter Pin Code" value="700002">
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

                        <!-- ITEM DETAILS TAB -->
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
                                            <option>Product / Service Billing</option>
                                            <option>Government Payment</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">HSN / SAC Code<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter HSN / SAC Code" value="998314">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST Rate<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter GST Rate" value="18%">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST Transaction Mode<span class="text-danger">*</span></label>
                                        <select class="form-control" name="gst_mode">
                                            <option label="Select"></option>
                                            <option>Intra State</option>
                                            <option>Inter State</option>
                                            <option>Union Territory</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">GST(%) Allocation<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Enter GST(%) Allocation" value="9% CGST + 9% SGST">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label">Discount on selling Price<span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="input-group mb-0">
                                                <input type="text" class="form-control has-success" name="disc_sell" id="disc_sell" value="0" placeholder="Discount" style="width: 60%;">
                                                <select class="form-select has-success" name="disc_sell_type" id="disc_sell_type" style="width: 40%;">
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
                                                <p class="f-w-600 mb-1 text-end text-success">₹50.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">CGST :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹720.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted mb-1 text-start">IGST :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹0.00</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-start">Grand Total :</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="f-w-600 mb-1 text-end">₹8,670.00</p>
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

                        <!-- OTHER DETAILS TAB -->
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
                                                <label>Total Amount<span class="text-danger">*</span></label>
                                                <input type="text" name="total_amount" id="total_amount" value="8670" class="form-control" placeholder="Total Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Advance Amount<span class="text-danger">*</span></label>
                                                <input type="text" name="advance_amount" id="advance_amount" value="2000" class="form-control" placeholder="Advance Amount">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Due Amount<span class="text-danger">*</span></label>
                                                <input type="text" name="due_amount" id="due_amount" value="6670" class="form-control" placeholder="Due Amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label>Interested for Dispatch through<span class="text-danger">*</span></label>
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

                                    <div class="col-md-6 mb-3 description-box">
                                        <div class="form-group" id="summernote_container">
                                            <label class="form-control-label">Validity Period <span class="text-danger">*</span></label>
                                            <input type="text" name="" id="" class="form-control" placeholder="Validity Period">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3 description-box">
                                        <div class="form-group" id="summernote_container">
                                            <label class="form-control-label">Disclaimer</label>
                                            <textarea class="summernote form-control" name="" id="" placeholder="Write Terms of Delivery" rows="5">
                                                This document represents only a commercial offer/order confirmation. It is not valid for Input Tax Credit (ITC), GST compliance, E-Way Bill or E-Invoice.
                                            </textarea>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <!-- END TABS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
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
    document.addEventListener("DOMContentLoaded", function () {
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

            if (paymentStatusDropdown.value === "Partial") {
                partialRow.style.display = "flex";
            } else {
                partialRow.style.display = "none";
            }
        }

        const fileInput = document.getElementById("imageUpload");
        const previewBox = document.getElementById("imagePreview");
        const uploadedImage = document.getElementById("uploadedImage");
        const downloadLink = document.getElementById("downloadLink");

        if (fileInput) {
            fileInput.addEventListener("change", function () {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        uploadedImage.src = e.target.result;
                        downloadLink.href = e.target.result;
                        previewBox.style.display = "block";
                    };

                    reader.readAsDataURL(file);
                } else {
                    previewBox.style.display = "none";
                }
            });
        }
    });
</script>

@endsection
