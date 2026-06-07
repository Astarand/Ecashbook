@extends('App.Layout')

@section('container')
    <div class="pc-content">	
		<!-- [ breadcrumb ] start -->
		<div class="page-header">
			<div class="page-block">
				<div class="row align-items-center">
					<div class="col-md-12">
						<ul class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>                        
							<li class="breadcrumb-item" aria-current="page">Accounting & Finance</li>
							<li class="breadcrumb-item"><a href="{{ url('/custom-invoice-list') }}">Custom Invoice</a></li>
						</ul>
					</div>
					<div class="col-md-4">
						<div class="page-header-title">
							<h2 class="mb-0">Generate Custom Invoice</h2>
						</div>
					</div>					
				</div>
			</div>
		</div>
		<!-- [ breadcrumb ] end -->
		
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <form method="POST" action="{{ route('invoice.store') }}"> --}}

                            <div class="row g-3">
                                    
                                    <div class="col-sm-6 col-xl-6 mb-3">
                                        <div class="mb-0">
                                            <label class="form-label">Invoice Number</label>
                                            <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ $newInvoiceNumber }}" >
                                            <div id="matchMessage" style="color: red;"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-6 mb-3">
                                        <div class="mb-0">
                                            <label class="form-label">Invoice Date</label>
                                            <input type="date" id="invoice_date" name="invoice_date" value="<?php echo date("Y-m-d") ?>" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="mb-0">Issued By:</h6>
                                            </div>
                                            <h5>{{ $comp_details[0]->comp_name ?? '' }}</h5>
                                            <p class="mb-0">
                                                {{ $comp_details[0]->comp_bill_addone ?? '' }} <br>
                                                {{ $comp_details[0]->comp_bill_addtwo ?? '' }},
                                                {{ $user_city[0]->name ?? '' }},
                                                {{ $user_state[0]->name ?? '' }},
                                                {{ $comp_details[0]->comp_bill_pin ?? '' }}
                                            </p>
                                            <p class="mb-0">{{ $comp_details[0]->comp_phone ?? '' }}</p>
                                            <p class="mb-0">{{ $comp_details[0]->comp_email ?? '' }}</p>
                                            <p class="mb-0"><strong>GST:</strong> {{ $comp_details[0]->gst_no ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-0">Issued To:</h6>
                                                <button class="btn btn-sm btn-light-secondary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                                    data-bs-target="#address-edit_add-modal">
                                                    <i class="ph-duotone ph-plus-circle"></i> Add
                                                </button>
                                            </div>
                                            <div id="issuedToDetails">
                                                <p><strong>Name:</strong> <span id="issuedName"></span></p>
                                                <p><strong>Address Line 1:</strong> <span id="issuedAddressLine1"></span></p>
                                                <p><strong>Address Line 2:</strong> <span id="issuedAddressLine2"></span></p>
                                                <p><strong>City:</strong> <span id="issuedCity"></span></p>
                                                <p><strong>Pincode:</strong> <span id="issuedPincode"></span></p>
                                                <p><strong>Phone:</strong> <span id="issuedPhone"></span></p>
                                                <p><strong>Email:</strong> <span id="issuedEmail"></span></p>
                                                <p><strong>GST Number:</strong> <span id="issuedGST"></span></p>
                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="mb-0">Payment Details:</h6>
                                                <button class="btn btn-sm btn-light-secondary d-flex align-items-center gap-2"
                                                    data-bs-toggle="modal" data-bs-target="#bank-edit_add-modal">
                                                    <i class="ph-duotone ph-pencil-simple-line"></i> Change
                                                </button>
                                            </div>
                                            <h5 id="selected-bank-name"></h5>
                                            <p class="mb-0" id="selected-ac-no"></p>
                                            <p class="mb-0" id="selected-holder-name"></p>
                                            <p class="mb-0" id="selected-branch"></p>
                                            <p class="mb-0" id="selected-ifsc"></p>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <h5>Detail</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th><span class="text-danger">*</span>Product / Service</th>
                                                        <th><span class="text-danger">*</span>Price</th>
                                                        <th><span class="text-danger">*</span>HSN/SAC Code</th>
                                                        <th><span class="text-danger">*</span>Qty</th>
                                                        <th><span class="text-danger">*</span>GST Mode</th>
                                                        <th>CGST</th>
                                                        <th>SGST</th>
                                                        <th>IGST/UGST</th>
                                                        <th>Total Amount</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableBody">
                                                    <tr>
                                                        <td>1</td>
                                                        <td><input type="text" class="form-control" placeholder="Name"></td>
                                                        <td><input type="text" class="form-control" placeholder="Price"></td>
                                                        <td><input type="number" class="form-control" placeholder="HSN/ SAC Code"></td>
                                                        <td><input type="number" class="form-control" placeholder="Quantity"></td>
                                                        <td>
                                                            <select class="form-select">
                                                                <option value="">Select</option>
                                                                <option value="Intra State">Intra State</option>
                                                                <option value="Inter State">Inter State</option>
                                                                <option value="Union Territory">Union Territory</option>
                                                            </select>
                                                        </td>
                                                        <td>₹ 0.00</td>
                                                        <td>₹ 0.00</td>
                                                        <td>₹ 0.00</td>
                                                        <td>₹ 0.00</td>
                                                        <td class="text-center">
                                                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default delete-row"><i class="ti ti-trash f-20"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-3">
                                            <button class="btn btn-light-primary gap-2 w-100" id="addNewItem"><i class="ti ti-plus"></i> Add new item</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="invoice-total ms-auto">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Discount (%)</label>
                                                        <input type="text" class="form-control" value="0" >
                                                    </div>
                                                </div>
                                                {{-- <div class="col-6"> <p class="text-muted mb-1 text-start">Sub Total Amount :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end">₹ 00.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">Discount Amount :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end text-success">₹ 00.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">CGST :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end">₹ 0.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">CGST :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end">₹ 0.00</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-start">Grand Total :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end grandTotal">₹ 00.00</p></div> --}}
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">Sub Total Amount :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end subTotal">₹ 00.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">Discount Amount :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end text-success discountAmount">₹ 00.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">CGST :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end totalCgst">₹ 0.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">SGST :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end totalSgst">₹ 0.00</p></div>
                                                <div class="col-6"> <p class="text-muted mb-1 text-start">IGST :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end totalIgst">₹ 0.00</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-start">Grand Total :</p></div>
                                                <div class="col-6"> <p class="f-w-600 mb-1 text-end grandTotal">₹ 00.00</p></div>

                                                <div class="col-12">
                                                    <div class="my-3">
                                                        <input type="text" name="signature_text" id="signature_text" class="form-control" placeholder="Write Signeture">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label for="signatureUpload">Upload DSC</label>
                                                        <input type="file" class="form-control" name="signatureUpload" id="signatureUpload" accept="image/*">
                                                    </div>
                                                    <div id="previewBox" class="border mt-3 d-flex justify-content-center align-items-center" 
                                                        style="width: 400px; height: 150px; overflow: hidden; display: none;">
                                                        <img id="signaturePreview" src="" alt="Signature Preview" style="max-width: 100%; max-height: 100%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-xl-6 mb-3">
                                        <div class="mb-0">
                                            <label class="form-label">Payment Status</label>
                                            <select class="form-select" id="paymentStatus" name="paymentStatus">
                                                <option value="">Please Select</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Unpaid">Unpaid</option>
                                                <option value="Partial Paid">Partial Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-3 mb-3">
                                        <div class="mb-0">
                                            <label class="form-label">Paid Amount</label>
                                            <input type="text" class="form-control" placeholder="Paid Amount" id="paidAmount" name="paidAmount"  >
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xl-3 mb-3">
                                        <div class="mb-0">
                                            <label class="form-label">Due Amount</label>
                                            <input type="text" class="form-control" placeholder="Due Amount" id="dueAmount" name="dueAmount">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-0">
                                            <label class="form-label">Note</label>
                                            <textarea class="form-control" rows="3" placeholder="Note" name="notes" id="notes"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-0">
                                            <label class="form-label">Terms and Conditions</label>
                                            <textarea class="form-control" rows="3" name="terms_and_conditions" id="terms_and_conditions" placeholder="Terms and Conditions"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row align-items-end justify-content-end g-3 mt-2">
                                            <div class="col-sm-auto btn-page">
                                                <button type="submit" id="customInvoiceDataSave" class="btn btn-primary">Save</button>
                                                {{-- <a href="{{ route ('user.ViewCustomInvoice')}}" class="btn btn-primary">Preview</a> --}}
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
    <div class="modal fade" id="address-edit_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
            <div class="collapse multi-collapse show">
                <h5 class="mb-0">Select address</h5>
            </div>
            <div class="collapse multi-collapse">
                <h5 class="mb-0">Add New address</h5>
            </div>
            <div class="d-flex align-items-center justify-content-end">
                <div class="collapse multi-collapse show" data-bs-toggle="tooltip" title="Add New">
                <a href="#" class="avtar avtar-s btn-link-secondary" data-bs-toggle="collapse" data-bs-target=".multi-collapse">
                    <i class="ti ti-plus f-20"></i>
                </a>
                </div>
                <a href="#" class="avtar avtar-s btn-link-danger" data-bs-dismiss="modal" data-bs-toggle="tooltip" title="Close">
                <i class="ti ti-x f-20"></i>
                </a>
            </div>
            </div>
            <div class="modal-body">
                <div class="collapse multi-collapse show">
                    {{-- <div class="address-check-block">
                        @foreach ($customer_list as $customer)
                            <div class="address-check border rounded p-3">
                                <div class="form-check">
                                    <input type="radio" name="customer_select" class="form-check-input input-primary"
                                        id="customer-{{ $customer->id }}" value="{{ $customer->id }}"
                                        data-name="{{ $customer->cust_name }}"
                                        data-address1="{{ $customer->cust_bill_addone }}"
                                        data-address2="{{ $customer->cust_bill_addtwo }}"
                                        data-city="{{ $customer->cust_bill_city }}"
                                        data-state="{{ $customer->cust_bill_state }}"
                                        data-pincode="{{ $customer->cust_bill_pin }}"
                                        data-phone="{{ $customer->cust_phone }}"
                                        data-email="{{ $customer->cust_email }}"
                                        data-gst="{{ $customer->cust_gst_no }}">
                    
                                    <label class="form-check-label d-block" for="customer-{{ $customer->id }}">
                                        <span class="h6 mb-0 d-block">{{ $customer->cust_name }}</span>
                                        <span class="text-muted address-details">
                                            {{ $customer->cust_bill_addone }}<br>
                                            {{ $customer->cust_bill_addtwo }}<br>
                                            {{ $customer->cust_bill_city }}, {{ $customer->cust_bill_state }}, {{ $customer->cust_bill_pin }}
                                        </span>
                                        <span class="text-muted address-details">{{ $customer->cust_phone }}</span>
                                        <span class="text-muted address-details">{{ $customer->cust_email }}</span>
                                        <span class="text-muted address-details"><strong>GST:</strong> {{ $customer->cust_gst_no }}</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}
                    
                    
                    
                </div>


                <div class="collapse multi-collapse">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Company Name :<small class="text-muted d-block">Enter your company name</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="companyName">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Address Line 1 :<small class="text-muted d-block">Enter Address Line 1</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="addressLine1">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Address Line 2 :<small class="text-muted d-block">Enter Address Line 2</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="addressLine2">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">City :<small class="text-muted d-block">Enter City name</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="city">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">PinCode :<small class="text-muted d-block">Enter Pincode</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="pincode">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Phone number :<small class="text-muted d-block">Enter Phone number</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="phone">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Email Id :<small class="text-muted d-block">Enter Email id</small></label>
                                <div class="col-lg-8">
                                    <input type="email" class="form-control" id="email">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">GST Number :<small class="text-muted d-block">Enter GST Number</small></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="gstNumber">
                                </div>
                            </div>
                            <div class="text-end btn-page mb-0 mt-4">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse">Cancel</button>
                                <button class="btn btn-primary" id="saveAddress">Save & Deliver to this Address</button>
                            </div>
                        </div>
                    </div>
                </div>
                

            </div>
            <div class="modal-footer justify-content-between collapse multi-collapse show">
            {{-- <ul class="list-inline me-auto mb-0">
                <li class="list-inline-item align-bottom">
                <a href="#" class="avtar avtar-s btn-link-danger w-sm-auto" data-bs-toggle="tooltip" title="Delete">
                    <i class="ti ti-trash f-18"></i>
                </a>
                </li>
            </ul> --}}
            <div class="flex-grow-1 text-end">
                <button type="button" class="btn btn-link-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
            </div>
            </div>
        </div>
        </div>
    </div>


    <div class="modal fade" id="bank-edit_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                <div class="collapse multi-collapse show">
                    <h5 class="mb-0">Select Bank</h5>
                </div>
                <div class="collapse multi-collapse">
                    <h5 class="mb-0">Add New Bank</h5>
                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <div class="collapse multi-collapse show" data-bs-toggle="tooltip" title="Add New">
                    <a href="#" class="avtar avtar-s btn-link-secondary" data-bs-toggle="collapse" data-bs-target=".multi-collapse">
                        <i class="ti ti-plus f-20"></i>
                    </a>
                    </div>
                    <a href="#" class="avtar avtar-s btn-link-danger" data-bs-dismiss="modal" data-bs-toggle="tooltip" title="Close">
                    <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                </div>
                <div class="modal-body">
                    <div class="collapse multi-collapse show">
                        <div class="address-check-block">
                            @foreach ($user_banks as $key => $bank)
                                <div class="address-check border rounded p-3">
                                    <div class="form-check">
                                        <input type="radio" name="radio1" class="form-check-input input-primary bank-radio" 
                                            id="bank-radio-{{ $key }}" 
                                            data-bank-name="{{ $bank->bank_name }}" 
                                            data-ac-no="{{ $bank->ac_no }}"
                                            data-holder-name="{{ $bank->bank_holder_name }}"
                                            data-branch="{{ $bank->bank_branch }}"
                                            data-ifsc="{{ $bank->ifsc_code }}">
                                        <label class="form-check-label d-block" for="bank-radio-{{ $key }}">
                                            <span class="h6 mb-0 d-block">{{ $bank->bank_name }}</span>
                                            <span class="p text-muted">{{ $bank->ac_no }}</span><br>
                                            <span class="text-muted">{{ $bank->bank_holder_name }}</span><br>
                                            <span class="text-muted">{{ $bank->bank_branch }}</span><br>
                                            <span class="text-muted">{{ $bank->ifsc_code }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="collapse multi-collapse">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Bank Name :</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="bank-name-input">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Account Number :</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="ac-no-input">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Account Holder Name :</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="holder-name-input">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Branch Name :</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="branch-input">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">IFSC Code :</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="ifsc-input">
                                    </div>
                                </div>
                                <div class="text-end btn-page mb-0 mt-4">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse">Cancel</button>
                                    <button class="btn btn-primary" id="save-bank-btn" data-bs-dismiss="modal">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between collapse multi-collapse show">
                    
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

    //Add New Item
    
    document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("tableBody");
    const addNewItemButton = document.getElementById("addNewItem");
    const discountInput = document.querySelector(".invoice-total input[type='text']");
    
    // Selecting correct elements
    const subTotalElement = document.querySelector(".subTotal"); 
    const discountAmountElement = document.querySelector(".discountAmount");
    const grandTotalElement = document.querySelector(".grandTotal");
    const totalCgstElement = document.querySelector(".totalCgst");
    const totalSgstElement = document.querySelector(".totalSgst");
    const totalIgstElement = document.querySelector(".totalIgst");

    const maxRows = 10;

    function calculateTotals() {
        let subTotal = 0;
        let totalCGST = 0;
        let totalSGST = 0;
        let totalIGST = 0;

        document.querySelectorAll("#tableBody tr").forEach(row => {
            let price = parseFloat(row.querySelector("td:nth-child(3) input").value) || 0;
            let qty = parseFloat(row.querySelector("td:nth-child(5) input").value) || 0;
            let gstMode = row.querySelector("td:nth-child(6) select").value;

            let totalAmount = price * qty;
            let cgst = 0, sgst = 0, igst = 0;

            if (gstMode === "Intra State") {
                cgst = totalAmount * 0.09;
                sgst = totalAmount * 0.09;
            } else if (gstMode === "Inter State" || gstMode === "Union Territory") {
                igst = totalAmount * 0.18;
            }

            row.querySelector("td:nth-child(7)").textContent = `₹ ${cgst.toFixed(2)}`;
            row.querySelector("td:nth-child(8)").textContent = `₹ ${sgst.toFixed(2)}`;
            row.querySelector("td:nth-child(9)").textContent = `₹ ${igst.toFixed(2)}`;
            row.querySelector("td:nth-child(10)").textContent = `₹ ${(totalAmount + cgst + sgst + igst).toFixed(2)}`;

            subTotal += totalAmount;
            totalCGST += cgst;
            totalSGST += sgst;
            totalIGST += igst;
        });

        let discount = parseFloat(discountInput.value) || 0;
        let discountAmount = (subTotal * discount) / 100;
        let grandTotal = subTotal + totalCGST + totalSGST + totalIGST - discountAmount;
        

        const paymentStatus = document.getElementById("paymentStatus");
        const paidAmount = document.getElementById("paidAmount");
        const dueAmount = document.getElementById("dueAmount");
        
        if (paymentStatus.value === "Partial Paid") {

                
                dueAmount.value = grandTotal;
                paidAmount.disabled = false;
                
            } else {
                
                dueAmount.value = grandTotal;
                // Clear values when disabled
                paidAmount.value = "00.00";
                dueAmount.disabled = true;
                //dueAmount.value = "";
            }
        
        // dueAmountInput.value = grandTotal.toFixed(2);

        subTotalElement.textContent = `₹ ${subTotal.toFixed(2)}`;
        discountAmountElement.textContent = `₹ ${discountAmount.toFixed(2)}`;
        totalCgstElement.textContent = `₹ ${totalCGST.toFixed(2)}`;
        totalSgstElement.textContent = `₹ ${totalSGST.toFixed(2)}`;
        totalIgstElement.textContent = `₹ ${totalIGST.toFixed(2)}`;
        grandTotalElement.textContent = `₹ ${grandTotal.toFixed(2)}`;

    }

    tableBody.addEventListener("input", function (e) {
        if (e.target.closest("input") || e.target.closest("select")) {
            calculateTotals();
        }
    });

    discountInput.addEventListener("input", function () {
        calculateTotals();
    });

    addNewItemButton.addEventListener("click", function () {
        const rowCount = tableBody.querySelectorAll("tr").length;
        if (rowCount < maxRows) {
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
                <td>${rowCount + 1}</td>
                <td><input type="text" class="form-control" placeholder="Name"></td>
                <td><input type="number" class="form-control" placeholder="Price"></td>
                <td><input type="number" class="form-control" placeholder="HSN/ SAC Code"></td>
                <td><input type="number" class="form-control" placeholder="Quantity"></td>
                <td>
                    <select class="form-select">
                        <option value="">Select</option>
                        <option value="Intra State">Intra State</option>
                        <option value="Inter State">Inter State</option>
                        <option value="Union Territory">Union Territory</option>
                    </select>
                </td>
                <td>₹ 0.00</td>
                <td>₹ 0.00</td>
                <td>₹ 0.00</td>
                <td>₹ 0.00</td>
                <td class="text-center">
                    <a href="#" class="delete-row"><i class="ti ti-trash f-20"></i></a>
                </td>
            `;
            tableBody.appendChild(newRow);

            newRow.querySelectorAll("input, select").forEach(element => {
                element.addEventListener("input", calculateTotals);
            });

            calculateTotals();
        }
    });

    tableBody.addEventListener("click", function (e) {
        if (e.target.closest(".delete-row")) {
            e.preventDefault();
            const row = e.target.closest("tr");
            if (tableBody.querySelectorAll("tr").length > 1) {
                row.remove();
                    calculateTotals();
                }
            }
        });
    });




    //Paid Amount and Due Amount
    document.addEventListener("DOMContentLoaded", function () {
        const paymentStatus = document.getElementById("paymentStatus");
        const paidAmount = document.getElementById("paidAmount");
        const dueAmount = document.getElementById("dueAmount");

        paymentStatus.addEventListener("change", function () {
            if (paymentStatus.value === "Partial Paid") {

                const grandTotalText = document.querySelector(".grandTotal");
                if (grandTotalText) {
                    const amountOnly = grandTotalText.textContent.replace(/[^\d.]/g, '');
                    // console.log(amountOnly);
                    dueAmount.value = amountOnly;

                    paidAmount.addEventListener("input", function () {
                        console.log("Paid Amount Typed:", this.value);
                        var amount = amountOnly - this.value;
                        dueAmount.value = amount;
                    });
                }

                // Enable Paid Amount and Due Amount
                paidAmount.disabled = false;
                
            } else if(paymentStatus.value === "Unpaid"){
                const grandTotalText = document.querySelector(".grandTotal");
                if (grandTotalText) {
                    const amountOnly = grandTotalText.textContent.replace(/[^\d.]/g, '');
                    // console.log(amountOnly);
                    dueAmount.value = amountOnly;
                    paidAmount.disabled = true;
                    dueAmount.disabled = true;
                }
            } else {
                // Disable Paid Amount and Due Amount
                paidAmount.disabled = true;
                dueAmount.disabled = true;

                // Clear values when disabled
                paidAmount.value = "";
                dueAmount.value = "";
            }
        });
    });

    //Upload Signeture
    document.getElementById("signatureUpload").addEventListener("change", function (event) {
        const file = event.target.files[0];
        const previewBox = document.getElementById("previewBox");
        const signaturePreview = document.getElementById("signaturePreview");

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                signaturePreview.src = e.target.result; // Set the image source to the file content
                previewBox.style.display = "block"; // Show the preview box
            };
            reader.readAsDataURL(file);
        } else {
            // Hide the preview box if no file is selected
            previewBox.style.display = "none";
            signaturePreview.src = "";
        }
    });


    //------------- Add another Issued to ---------------
    document.addEventListener("DOMContentLoaded", function () {
        // Handle radio button selection (Customer List)
        document.querySelectorAll("input[name='customer_select']").forEach(function (radio) {
        radio.addEventListener("change", function () {
        let selectedCustomer = this;
        
        document.getElementById("issuedName").textContent = selectedCustomer.getAttribute("data-name");
        document.getElementById("issuedAddressLine1").textContent = selectedCustomer.getAttribute("data-address1");
        document.getElementById("issuedAddressLine2").textContent = selectedCustomer.getAttribute("data-address2");
        document.getElementById("issuedCity").textContent = selectedCustomer.getAttribute("data-city");
        document.getElementById("issuedPincode").textContent = selectedCustomer.getAttribute("data-pincode");
        document.getElementById("issuedPhone").textContent = selectedCustomer.getAttribute("data-phone");
        document.getElementById("issuedEmail").textContent = selectedCustomer.getAttribute("data-email");
        document.getElementById("issuedGST").textContent = selectedCustomer.getAttribute("data-gst");
        
        // Pre-fill the form in modal
        document.getElementById("editName").value = selectedCustomer.getAttribute("data-name");
        document.getElementById("editAddressLine1").value = selectedCustomer.getAttribute("data-address1");
        document.getElementById("editAddressLine2").value = selectedCustomer.getAttribute("data-address2");
        document.getElementById("editCity").value = selectedCustomer.getAttribute("data-city");
        document.getElementById("editPincode").value = selectedCustomer.getAttribute("data-pincode");
        document.getElementById("editPhone").value = selectedCustomer.getAttribute("data-phone");
        document.getElementById("editEmail").value = selectedCustomer.getAttribute("data-email");
        document.getElementById("editGST").value = selectedCustomer.getAttribute("data-gst");
        
        // Hide manual address entry form
        document.querySelector(".multi-collapse").classList.remove("show");
        
        // Reset manual entry fields
        resetManualAddressForm();
        });
        });
        
        // Handle manual address entry form visibility
        document.querySelectorAll("[data-bs-toggle='collapse']").forEach(function (button) {
        button.addEventListener("click", function () {
        // If the form is about to be shown, reset customer selection
        if (!document.querySelector(".multi-collapse").classList.contains("show")) {
        resetCustomerSelection();
        }
        });
        });
        
        // Handle form submission (Save Address)
        document.getElementById("saveAddress").addEventListener("click", function () {
        let name = document.getElementById("companyName").value;
        let address1 = document.getElementById("addressLine1").value;
        let address2 = document.getElementById("addressLine2").value;
        let city = document.getElementById("city").value;
        let pincode = document.getElementById("pincode").value;
        let phone = document.getElementById("phone").value;
        let email = document.getElementById("email").value;
        let gst = document.getElementById("gstNumber").value;
        
        // Update the issuedToDetails section
        document.getElementById("issuedName").innerText = name || "-";
        document.getElementById("issuedAddressLine1").innerText = address1 || "-";
        document.getElementById("issuedAddressLine2").innerText = address2 || "-";
        document.getElementById("issuedCity").innerText = city || "-";
        document.getElementById("issuedPincode").innerText = pincode || "-";
        document.getElementById("issuedPhone").innerText = phone || "-";
        document.getElementById("issuedEmail").innerText = email || "-";
        document.getElementById("issuedGST").innerText = gst || "-";
        
        // Hide the form after saving
        document.querySelector('.multi-collapse').classList.remove('show');
        
        // Close the modal after saving
        let modal = bootstrap.Modal.getInstance(document.getElementById("address-edit_add-modal"));
        modal.hide();
        });
        
        // Handle Cancel button to close modal
        document.querySelectorAll(".btn-cancel").forEach(button => {
        button.addEventListener("click", function () {
        let modal = bootstrap.Modal.getInstance(document.getElementById("address-edit_add-modal"));
        modal.hide();
        });
        });
        
        // Function to reset the manual address form fields
        function resetManualAddressForm() {
        document.getElementById("companyName").value = "";
        document.getElementById("addressLine1").value = "";
        document.getElementById("addressLine2").value = "";
        document.getElementById("city").value = "";
        document.getElementById("pincode").value = "";
        document.getElementById("phone").value = "";
        document.getElementById("email").value = "";
        document.getElementById("gstNumber").value = "";
        }
        
        // Function to reset customer selection
        function resetCustomerSelection() {
        document.querySelectorAll("input[name='customer_select']").forEach(function (radio) {
        radio.checked = false;
        });
        }
    });



    //--------- Add Bank account -----------

    document.addEventListener("DOMContentLoaded", function () {
        // Handle Radio Button Selection
        document.querySelectorAll(".bank-radio").forEach(radio => {
            radio.addEventListener("change", function () {
                if (this.checked) {
                    document.getElementById("selected-bank-name").innerText = this.dataset.bankName;
                    document.getElementById("selected-ac-no").innerText = this.dataset.acNo;
                    document.getElementById("selected-holder-name").innerText = this.dataset.holderName;
                    document.getElementById("selected-branch").innerText = this.dataset.branch;
                    document.getElementById("selected-ifsc").innerText = this.dataset.ifsc;

                    // Clear the form fields
                    document.getElementById("bank-name-input").value = "";
                    document.getElementById("ac-no-input").value = "";
                    document.getElementById("holder-name-input").value = "";
                    document.getElementById("branch-input").value = "";
                    document.getElementById("ifsc-input").value = "";
                }
            });
        });

        // Handle Save Button Click
        document.getElementById("save-bank-btn").addEventListener("click", function () {
            let bankName = document.getElementById("bank-name-input").value.trim();
            let acNo = document.getElementById("ac-no-input").value.trim();
            let holderName = document.getElementById("holder-name-input").value.trim();
            let branch = document.getElementById("branch-input").value.trim();
            let ifsc = document.getElementById("ifsc-input").value.trim();

            if (bankName && acNo && holderName && branch && ifsc) {
                document.getElementById("selected-bank-name").innerText = bankName;
                document.getElementById("selected-ac-no").innerText = acNo;
                document.getElementById("selected-holder-name").innerText = holderName;
                document.getElementById("selected-branch").innerText = branch;
                document.getElementById("selected-ifsc").innerText = ifsc;

                // Uncheck all radio buttons
                document.querySelectorAll(".bank-radio").forEach(radio => radio.checked = false);
            }
        });
    });

    //------------ Data save into DB--------

    function numberToWords(num) {
        if (num === 0) return "Zero";

        var belowTwenty = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
        var tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
        var thousands = ["", "Thousand", "Million", "Billion", "Trillion"];

        function helper(n) {
            if (n === 0) return "";
            else if (n < 20) return belowTwenty[n] + " ";
            else if (n < 100) return tens[Math.floor(n / 10)] + " " + helper(n % 10);
            else return belowTwenty[Math.floor(n / 100)] + " Hundred " + helper(n % 100);
        }

        var result = "";
        var i = 0;
        
        while (num > 0) {
            if (num % 1000 !== 0) {
                result = helper(num % 1000) + thousands[i] + " " + result;
            }
            num = Math.floor(num / 1000);
            i++;
        }
        
        return result.trim();
    }

    $('#customInvoiceDataSave').on('click', function () {
        var invoice_number = $('#invoice_number').val();
        var invoice_date = $('#invoice_date').val();
        var sub_total = $('.subTotal').text().trim();
        var discount_amount = $('.discountAmount').text().trim();
        // var grand_total = $('.grandTotal').text().trim();
        var grand_total = $('.grandTotal').text().trim().replace('₹', '');
        var grand_total_in_words = numberToWords(parseInt(grand_total));
        

        var signature_text = $('#signature_text').val();
        var payment_status = $('#paymentStatus').val();
        var paid_amount = $('#paidAmount').val();
        var due_amount = $('#dueAmount').val();
        var notes = $('#notes').val();
        var terms_and_conditions = $('#terms_and_conditions').val();
        var signature_file = $('#signatureUpload')[0].files[0]; 

        // Get selected customer details
        var selectedCustomer = $('input[name="customer_select"]:checked');

        var customer_id = selectedCustomer.val() || null;
        
        var customer_name = selectedCustomer.data('name') || $('#companyName').val().trim();

        var address_line1 = selectedCustomer.length ? selectedCustomer.data('address1') : $('#addressLine1').val().trim();
        var address_line2 = selectedCustomer.length ? selectedCustomer.data('address2') : $('#addressLine2').val().trim();
        var city = selectedCustomer.length ? selectedCustomer.data('city') : $('#city').val().trim();
        var issued_to_state = selectedCustomer.length ? selectedCustomer.data('state') : null;
            issued_to_state = issued_to_state ? issued_to_state : null;
        var pincode = selectedCustomer.length ? selectedCustomer.data('pincode') : $('#pincode').val().trim();
        var customer_phone = selectedCustomer.length ? selectedCustomer.data('phone') : $('#phone').val().trim();
        var customer_email = selectedCustomer.length ? selectedCustomer.data('email') : $('#email').val().trim();
        var customer_gst = selectedCustomer.length ? selectedCustomer.data('gst') : $('#gstNumber').val().trim();

        //---------- Bank Add --------
        var selectedBank = $('input[name="radio1"]:checked');

        var bankName = selectedBank.length ? selectedBank.data('bank-name') : $('#bank-name-input').val().trim();
        var acNo = selectedBank.length ? selectedBank.data('ac-no') : $('#ac-no-input').val().trim();
        var holderName = selectedBank.length ? selectedBank.data('holder-name') : $('#holder-name-input').val().trim();
        var branch = selectedBank.length ? selectedBank.data('branch') : $('#branch-input').val().trim();
        var ifsc = selectedBank.length ? selectedBank.data('ifsc') : $('#ifsc-input').val().trim();

        //---------------- issued by -------------

        var company_name = "{{ $comp_details[0]->comp_name ?? '' }}";
        var company_address1 = "{{ $comp_details[0]->comp_bill_addone ?? '' }}";
        var company_address2 = "{{ $comp_details[0]->comp_bill_addtwo ?? '' }}";
        var company_city = "{{ $user_city[0]->name ?? '' }}";
        var company_state = "{{ $user_state[0]->name ?? '' }}";
        var company_pincode = "{{ $comp_details[0]->comp_bill_pin ?? '' }}";
        var company_phone = "{{ $comp_details[0]->comp_phone ?? '' }}";
        var company_email = "{{ $comp_details[0]->comp_email ?? '' }}";
        var company_gst = "{{ $comp_details[0]->gst_no ?? '' }}";



        // Collecting invoice items dynamically
        var items = [];
        $('#tableBody tr').each(function () {
            var product_name = $(this).find('td:nth-child(2) input').val();
            var price = $(this).find('td:nth-child(3) input').val().replace(/[^\d.]/g, ''); // Remove ₹
            var hsn_sac = $(this).find('td:nth-child(4) input').val();
            var quantity = $(this).find('td:nth-child(5) input').val();
            var gst_mode = $(this).find('td:nth-child(6) select').val();
            var cgst = $(this).find('td:nth-child(7)').text().trim().replace(/[^\d.]/g, ''); // Remove ₹
            var sgst = $(this).find('td:nth-child(8)').text().trim().replace(/[^\d.]/g, ''); // Remove ₹
            var igst = $(this).find('td:nth-child(9)').text().trim().replace(/[^\d.]/g, ''); // Remove ₹
            var total = $(this).find('td:nth-child(10)').text().trim().replace(/[^\d.]/g, ''); // Remove ₹

            items.push({
                product_name: product_name,
                price: price,
                hsn_sac: hsn_sac,
                quantity: quantity,
                gst_mode: gst_mode,
                cgst: cgst,
                sgst: sgst,
                igst: igst,
                total: total
            });
        });

        var formData = new FormData();
        formData.append('invoice_number', invoice_number);
        formData.append('invoice_date', invoice_date);
        formData.append('sub_total', sub_total);
        formData.append('discount_amount', discount_amount);
        formData.append('grand_total', grand_total);
        formData.append('grand_total_in_words', grand_total_in_words);

        formData.append('signature_text', signature_text);
        formData.append('payment_status', payment_status);
        formData.append('paid_amount', paid_amount);
        formData.append('due_amount', due_amount);
        formData.append('notes', notes);
        formData.append('terms_and_conditions', terms_and_conditions);

        formData.append('cust_id', customer_id);
        formData.append('issued_to_company_name', customer_name);
        formData.append('issued_to_address1', address_line1);
        formData.append('issued_to_address2', address_line2);
        formData.append('issued_to_city', city);
        formData.append('issued_to_state', issued_to_state);
        formData.append('issued_to_pincode', pincode);
        formData.append('issued_to_contact_no', customer_phone);
        formData.append('issued_to_email_address', customer_email);
        // formData.append('customer_pan', customer_pan);
        formData.append('issued_to_gst', customer_gst);

        //------ Add bank details to FormData ----------
        formData.append('bank_name', bankName);
        formData.append('account_no', acNo);
        formData.append('account_holder_name', holderName);
        formData.append('branch_name', branch);
        formData.append('ifsc_code', ifsc);
        //------ Added company details -------
        formData.append('issued_by_company_name', company_name);
        formData.append('issued_by_address1', company_address1);
        formData.append('issued_by_address2', company_address2);
        formData.append('issued_by_city', company_city);
        formData.append('issued_by_state', company_state);
        formData.append('issued_by_pincode', company_pincode);
        formData.append('issued_by_contact_no', company_phone);
        formData.append('issued_by_email_address', company_email);
        formData.append('issued_by_gst', company_gst);

        if (signature_file) {
            formData.append('signatureUpload', signature_file);
        }


        // Append items as JSON string
        formData.append('items', JSON.stringify(items));
        $("#loader").show();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: '/custom_invoice_store',  
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false,  
            success: function (response) {
                $("#loader").hide();
                if (response.redirect_url) {
					showToast("Invoice Added Successfully", "success");

					setTimeout(() => {
						window.location.href = response.redirect_url;
					}, 2000);

				} else {
					showToast(
						response.message ?? "Invoice saved, but redirect URL not found!",
						"error"
					);
				}
            },
            error: function (xhr) {
                $("#loader").hide();
                // alert("Error saving Invoice!");
                showToast("Error saving Invoice!", "error");
            }
        });
    });






</script>
@endsection