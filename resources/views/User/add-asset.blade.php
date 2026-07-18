@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/assets-list') }}">Assets</a></li>
                        <li class="breadcrumb-item" aria-current="page">Add Asset</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-asset-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add New Asset</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-lg-5 col-xxl-3">
                    <div class="card overflow-hidden">
                        <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0" id="company-profile-set-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link list-group-item list-group-item-action active" id="asset-information-tab" data-bs-toggle="pill" href="#asset-information" role="tab" aria-controls="asset-information" aria-selected="true">
                                <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Asset Information</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="gst-tds-tab" data-bs-toggle="pill" href="#gst-tds" role="tab" aria-controls="gst-tds" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-receipt m-r-10"></i>GST & TDS</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="documentation-tab" data-bs-toggle="pill" href="#documentation" role="tab" aria-controls="documentation-tab" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-wallet m-r-10"></i>Documentations<span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="audit-trail-tab" data-bs-toggle="pill" href="#audit-trail" role="tab" aria-controls="audit-trail-tab" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-arrow-square-up m-r-10"></i>Audit Trail</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xxl-9">
					
                    <form data-route="{{ route('user.SaveAsset') }}" name="addAssetFrm" id="addAssetFrm" enctype="multipart/form-data">
                        @csrf
						<div class="message-container"></div>
                        <div class="tab-content" id="company-profile-set-tabContent">
                            <div class="tab-pane fade show active" id="asset-information" role="tabpanel" aria-labelledby="asset-information-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Asset Information</h5>
                                    </div>
									
                                    <div class="card-body">
                                        <div class="row">
											<div class="col-sm-6 mb-3">
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
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date">
                                            </div>
                                            
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Asset Type<span class="text-danger">*</span></label>
                                                <select id="assetType" name="assetType" class="form-select">
                                                    <option value="">Select</option>
                                                    <option value="current">Current Assets</option>
                                                    <option value="non-current">Non Current Assets</option>
													<option value="capex">Capital Expenditure (CapEx)</option>
                                                   {{-- <option value="investment">Investment</option> --}}
                                                </select>
                                            </div>
                                            <!-- Current Assets Section -->
                                            <div id="currentAssetsSection" class="row" style="display: none;">
                                                <div class="col-sm-12 mb-3">
                                                    <label class="form-label">Current Assets Type <span class="text-danger">*</span></label>
                                                    <select id="currentAssetsType" name="currentAssetType" class="form-select">
                                                        <option value="">Select</option>
														<option value="Cash in Hand">Cash in Hand</option>
														<option value="Bank Accounts">Bank Accounts </option>
														<option value="Trade Receivables">Trade Receivables (Debtors)</option>
														<option value="Advance to Vendor">Advance to Vendor</option>
														<option value="Employee Advance">Employee Advance</option>
														<option value="Prepaid Expenses">Prepaid Expenses</option>
														<option value="Input GST Credit">Input GST Credit (ITC)</option>
														<option value="TDS Receivable">TDS Receivable</option>
														<option value="Inventories">Inventories / Stocks</option>														
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 mb-3" id="otherCurrentAssetsDiv" style="display: none;">
                                                    <label class="form-label">Other Current Asset Name<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="otherCurrentAssetName" id="otherCurrentAssetName" placeholder="Enter Other Current Asset Name">
                                                </div>
                                            </div>
											
											<!-- Non-Current Assets Section -->
                                            <div id="nonCurrentAssetsSection" class="row" style="display: none;">
												<div class="col-sm-12 mb-3">
													<label class="form-label" id="assetTypeLabel">Non-Current Assets Type <span class="text-danger">*</span></label>
													<select id="nonCurrentAssetsType" class="form-select" name="nonCurrentAssetType">
														<!--<option value="">Select</option>
														<option value="Property Plant Equipment">Property, Plant & Equipment (PPE)</option>
														<option value="Furniture Fixtures">Furniture & Fixtures</option>
														<option value="Computer IT Equipment">Computer & IT Equipment</option>
														<option value="Machinery">Machinery</option>
														<option value="Vehicles">Vehicles</option>
														<option value="Intangible Assets">Intangible / Non-physical Assets</option>
														<option value="Capital Work in Progress">Capital Work-in-Progress</option>
														<option value="Other Non-Current Assets">Other Non-Current Assets</option>-->
													</select>
												</div>
											</div>
                                           
                                            <div class="row" id="commonSection" style="display:none; margin-top:10px;">
                                                                                               
                                                <!-- ================= BASIC DETAILS ================= -->
												<h5 class="mb-3">Basic Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Name <span class="text-danger">*</span></label>
													<input type="text" name="asset_name" id="asset_name" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Category <span class="text-danger">*</span></label>
													<select name="asset_category" class="form-select">
														<option value="">Select</option>
														<option value="Land">Land</option>
														<option value="Building">Building</option>
														<option value="Plant Machinery">Plant & Machinery</option>
														<option value="Furniture Fixtures">Furniture & Fixtures</option>
														<option value="Office Equipment">Office Equipment</option>
														<option value="Computer IT Equipment">Computer & IT Equipment</option>
														<option value="Vehicles">Vehicles</option>
														<option value="Electrical Installations">Electrical Installations</option>
														<option value="Leasehold Improvements">Leasehold Improvements</option>
														<option value="Other">Other</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label"  id="assetCodeLabel">Asset Code / ID <span class="text-danger">*</span></label>
													<input type="text" name="asset_code" id="asset_code" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Location / Branch</label>
													<input type="text" name="location" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Department</label>
													<select name="department" class="form-select">
														<option value="">Select</option>
														<option value="Administration">Administration</option>
														<option value="Production">Production</option>
														<option value="Sales Marketing">Sales & Marketing</option>
														<option value="IT">IT</option>
														<option value="HR">HR</option>
														<option value="Finance">Finance</option>
														<option value="Other">Other</option>
													</select>
												</div>


												<!-- ================= PURCHASE DETAILS ================= -->
												<h5 class="mt-4 mb-3">Purchase Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Vendor Name</label>
													<select name="vendor_id" id="vendor_id" class="form-control">
														<option value="">Select Vendor</option>
														@foreach($vendors as $vendor)
															<option value="{{ $vendor->id }}">
																{{ $vendor->vendor_name }}
															</option>
														@endforeach
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Invoice/Reference No</label>
													<input type="text" name="invoice_no" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Invoice Date</label>
													<input type="date" name="invoice_date" id="invoice_date" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Amount Value<span class="text-danger">*</span></label>
													<input type="number" name="invoice_value" id="invoice_value" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Payment Status<span class="text-danger">*</span></label>
													<select name="pay_status" id="pay_status" class="form-select">
														<option value="">Select</option>
														<option value="Full">Full</option>
														<option value="Advance">Advance</option>														
														<option value="Due">Due</option>														
													</select>
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Payment Mode<span class="text-danger">*</span></label>
													<select name="pay_mode" id="pay_mode"  class="form-select">
														<option value="">Select</option>
														<option value="Cash">Cash</option>
														<option value="Bank">Bank</option>														
														<option value="UPI">UPI</option>														
													</select>
												</div>
												
												<div class="col-xl-4 mb-3">
													<div class="form-group">
														<label class="form-label">Select Bank</label>
														<select name="bank_id" id="bank_id" class="form-control">
															<option value="">-- Select Bank --</option>
															@foreach($bankDetails as $bank)
																<option value="{{ $bank->id }}">
																	{{ $bank->bank_name }}
																</option>
															@endforeach
														</select>
													</div>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Advance Amount<span class="text-danger">*</span></label>
													<input type="number" name="advance_amt" id="advance_amt" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Balance Payable Amount<span class="text-danger">*</span></label>
													<input type="number" name="payable_amt" id="payable_amt" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Adjusted Now<span class="text-danger">*</span></label>
													<input type="number" name="adjusted_amt" id="adjusted_amt" class="form-control">
												</div>
												
												


												<!-- ================= CAPITALIZATION ================= -->
												<h5 class="mt-4 mb-3">Capitalization Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Capitalization Date</label>
													<input type="date" name="capitalization_date" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Put to Use Date</label>
													<input type="date" name="put_to_use_date" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label" id="assetStatusLabel">Asset Status</label>
													<select name="asset_status" id="asset_status" class="form-select">
														<!--<option value="Active">Active</option>
														<option value="Under Construction">Under Construction</option>-->
													</select>
												</div>


												<!-- ================= DEPRECIATION ================= -->
												<h5 class="mt-4 mb-3">Depreciation Setup</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Start Date</label>
													<input type="date" name="depreciation_start_date" id="depreciation_start_date" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Method</label>
													<select name="depreciation_method" id="depreciation_method" class="form-select">
														<option value="">select</option>
														<option value="SLM">Straight Line Method (SLM)</option>
														<option value="WDV">Written Down Value (WDV)</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Frequency</label>
													<select name="depreciation_frequency" id="depreciation_frequency" class="form-select">
														<!--<option value="">select</option>-->
														<option value="Yearly">Yearly</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3" id="usefulLifeDiv" >
													<label class="form-label">Useful Life (Years)</label>
													<input type="number" name="useful_life_years" id="useful_life_years" class="form-control">
												</div>												
												
												<div class="col-xl-4 mb-3" id="residualValueDiv">
													<label class="form-label">Residual Value</label>
													<input type="number" name="residual_value" id="residual_value" class="form-control">
												</div>

												<div class="col-xl-4 mb-3" id="depreciationRateDiv" style="display:none;">
													<label class="form-label">Depreciation Rate (%)</label>
													<input type="number" name="depreciation_rate" id="depreciation_rate" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Value</label>
													<input type="number" name="depreciation_value" id="depreciation_value" class="form-control">
												</div>
												
                                            </div>
                                            
											<div class="row" id="WorkinProgressSection" style="display:none; margin-top:10px;">
												<!-- ================= PROJECT DETAILS ================= -->
												<h5 class="mb-3">Project Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Project Name <span class="text-danger">*</span></label>
													<input type="text" name="project_name" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Project Code / ID <span class="text-danger">*</span></label>
													<input type="text" name="project_code" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Type <span class="text-danger">*</span></label>
													<select name="cwip_asset_type" class="form-select">
														<option value="">Select</option>
														<option value="Land">Land</option>
														<option value="Building">Building</option>
														<option value="Plant Machinery">Plant & Machinery</option>
														<option value="Furniture Fixtures">Furniture & Fixtures</option>
														<option value="Office Equipment">Office Equipment</option>
														<option value="Computer IT Equipment">Computer & IT Equipment</option>
														<option value="Vehicles">Vehicles</option>
														<option value="Electrical Installations">Electrical Installations</option>
														<option value="Leasehold Improvements">Leasehold Improvements</option>
														<option value="Other">Other</option>
													</select>
												</div>


												<!-- ================= EXPENSE DETAILS ================= -->
												<h5 class="mt-4 mb-3">Expense Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Expense Type <span class="text-danger">*</span></label>
													<select name="expense_type" class="form-select">
														<option value="">Select</option>
														<option value="Material Cost">Material Cost</option>
														<option value="Labour Cost">Labour Cost</option>
														<option value="Contractor Charges">Contractor Charges</option>
														<option value="Professional Fees">Professional / Technical Fees</option>
														<option value="Installation Commissioning">Installation & Commissioning</option>
														<option value="Freight Transportation">Transportation / Freight</option>
														<option value="Duties Taxes">Duties & Taxes</option>
														<option value="Administrative Overheads">Administrative / Overheads</option>
														<option value="Repairs Maintenance">Repairs & Maintenance</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Vendor Name</label>
													<select name="cwip_vendor_id" id="cwip_vendor_id" class="form-control">
														<option value="">Select Vendor</option>
														@foreach($vendors as $vendor)
															<option value="{{ $vendor->id }}">
																{{ $vendor->vendor_name }}
															</option>
														@endforeach
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Invoice No</label>
													<input type="text" name="cwip_invoice_no" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Amount <span class="text-danger">*</span></label>
													<input type="number" name="cwip_amount" id="cwip_amount" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Payment Status<span class="text-danger">*</span></label>
													<select name="cwip_pay_status" id="cwip_pay_status" class="form-select">
														<option value="">Select</option>
														<option value="Full">Full</option>
														<option value="Advance">Advance</option>
														<option value="Due">Due</option>
													</select>
												</div>
												<div class="col-xl-4 mb-3">
													<label class="form-label">Payment Mode<span class="text-danger">*</span></label>
													<select name="cwip_pay_mode" id="cwip_pay_mode" class="form-select">
														<option value="">Select</option>
														<option value="Cash">Cash</option>
														<option value="Bank">Bank</option>														
														<option value="UPI">UPI</option>														
													</select>
												</div>
												<div class="col-xl-4 mb-3">
													<div class="form-group">
														<label class="form-label">Select Bank</label>
														<select name="cwip_bank_id" id="cwip_bank_id" class="form-control">
															<option value="">-- Select Bank --</option>
															@foreach($bankDetails as $bank)
																<option value="{{ $bank->id }}">
																	{{ $bank->bank_name }}
																</option>
															@endforeach
														</select>
													</div>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Advance Amount</label>
													<input type="number" name="cwip_advance_amt" id="cwip_advance_amt" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Balance Payable</label>
													<input type="number" name="cwip_payable_amt" id="cwip_payable_amt" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Adjusted Now</label>
													<input type="number" name="cwip_adjusted_amt" id="cwip_adjusted_amt" class="form-control">
												</div>


												<!-- ================= PROGRESS TRACKING ================= -->
												<h5 class="mt-4 mb-3">Progress Tracking</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Stage of Completion (%)</label>
													<input type="number" name="completion_percentage" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Capitalization Status</label>
													<select name="capitalization_status" class="form-select">
														<option value="">Select</option>
														<option value="Pending">Pending</option>
														<option value="Completed">Completed</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Work Order / Contract Ref</label>
													<input type="text" name="work_order_ref" class="form-control">
												</div>

											</div>
											
											<!--Cash in hand--->
											<div  class="row" id="cashInHandSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="number" name="cash_amount" class="form-control">
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/cash-management" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											
											<!--Bank Accounts--->
											<div  class="row" id="bankAccountSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Bank Account</label>
													<select name="bank_id" id="bank_id" class="form-control"></select>
												</div>

												<div class="col-md-4 mb-3">
													<label>Balance</label>
													<input type="text" name="bank_balance" id="bank_balance" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/bank-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											<!--TRADE RECEIVABLES -->
											<div  class="row" id="tradeReceivableSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="text" name="amount" id="amount" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/sale-invoices" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											<!--Advance to vendor-->
											<div class="row" id="advanceVendorSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="text" name="amount_vendor" id="amount_vendor" class="form-control" >
												</div>	
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/purchase-invoices" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
                                            
											<!---Employee Advance-->
											<div class="row" id="employeeAdvanceSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Advance Amount</label>
													<input type="text" name="employee_advance_amount" id="employee_advance_amount" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/expenses-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											
											<!--Prepaid expense -->
											<div class="row" id="prepaidExpenseSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="text" name="prepaid_amt" id="prepaid_amt" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/expenses-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											
											<!--Input GST Credit-->
											<div class="row" id="itcSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>ITC Amount</label>
													<input type="text" name="itc_amt" id="itc_amt" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/gst-reports" target="_blank" class="btn btn-primary">Details</a>
												</div>
												
											</div>
											
											<!--TDS Receivable-->
											<div class="row" id="tdsReceivableSection" style="display:none;">												
												<div class="col-md-4 mb-3">
													<label>Gross Amount</label>
													<input type="text" id="tds_gross_amount" name="tds_gross_amount" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/tds-returns-filing" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											
											<div class="row" id="inventorySection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Total Gross Profit</label>
													<input type="text" name="gross_profit" id="gross_profit" class="form-control" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/inventory-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											
										</div>
                                    </div>
                                    <div class="d-flex wizard justify-content-end mb-3 me-3">
                                        <div class="last">
                                            <a href="javascript:void(0);" id="nextBtn1" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                                Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="gst-tds" role="tabpanel" aria-labelledby="gst-tds-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>GST & TDS</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row currAsset">
											<div class="col-md-12">
												<label class="form-label">TDS Applicable</label>
												<div class="row">
													<div class="col-6">
														<div class="card shadow-sm border-0 p-3 m-2">
															<div class="form-check">
																<input class="form-check-input" type="radio" name="tds_applicable" value="yes" id="tdsYes">
																<label class="form-check-label" for="tdsYes">Yes</label>
															</div>
														</div>
													</div>
													<div class="col-6">
														<div class="card shadow-sm border-0 p-3 m-2">
															<div class="form-check">
																<input class="form-check-input" type="radio" name="tds_applicable" value="no" id="tdsNo" checked>
																<label class="form-check-label" for="tdsNo">No</label>
															</div>
														</div>
													</div>
												</div>
											</div>
                                            <div class="tds-container col-md-12" id="tdsContainer">
                                                <div class="row">
													<div class="col-md-6">
														<div id="tds_dropdown_universal">
															<label for="tds_percent" class="form-label">TDS Percentage</label>
															<select name="tds_percent" id="tds_percent" class="form-control">
																@foreach ($purposes_of_tds as $purpose)
																<option value="{{ $purpose->tds_rate . '-' . $purpose->id }}">
																	{{ $purpose->category }} ({{ $purpose->tds_rate }}%)
																</option>
																@endforeach
															</select>
														</div>
                                                    </div>
													<div class="col-md-6">
														<label for="tds_amt">TDS Amount</label>
														<input type="text" id="tds_amt"  class="form-control" readonly>
													</div>
                                                </div>
                                            </div>

                                            <div class="gst-container col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Applicable <span class="text-danger">*</span></label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes_ca" >
                                                                    <label class="form-check-label" for="gstYes_ca">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo_ca" checked>
                                                                    <label class="form-check-label" for="gstNo_ca">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label for="gst_trans">GST Transaction Mode</label>
                                                        <select class="form-select" name="gst_trans" id="gst_trans">
                                                            <option value="">Select</option>
                                                            <option value="intrastate">Intra State</option>
                                                            <option value="interstate">Inter State</option>
                                                            <option value="union">Union Territory</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="gst_rate">GST Rate (%)</label>
                                                        <input type="number" name="gst_rate" id="gst_rate" class="form-control" min="0" step="0.01">
                                                    </div>
													<div class="col-md-4">
                                                        <label for="gst_rate">GST Allocation</label>
                                                        <input type="text" name="gst_allocation" id="gst_allocation" class="form-control" readonly>
                                                    </div>
													<div class="col-md-4">
                                                        <label for="gst_amt">Total GST Amount</label>
                                                        <input type="text" name="gst_amt" id="gst_amt" class="form-control" readonly>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
										
										
                                        <div class="d-flex wizard justify-content-between mt-3">
                                            <div class="first">
                                                <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                                </a>
                                            </div>
                                            <div class="last">
                                                <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                                    Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="documentation" role="tabpanel" aria-labelledby="documentation-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <div class="">
                                                    <div class="card-header">
                                                        <h5>Attachment 1</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        {{-- <form class="uploadForm dropzone"> --}}
                                                        <div class="fallback">
                                                            <label class="form-label">Upload Document</label>
                                                            <input name="file1" type="file" class="fileInput form-control" accept=".pdf,.doc,.docx,.txt,.xlsx">
                                                        </div>
                                                        {{-- </form> --}}

                                                        <!-- Preview Container -->
                                                        <div class="previewBox text-center mt-4" style="display: none;">
                                                            <h5>Uploaded Document:</h5>
                                                            <a class="downloadLink" href="#" download>
                                                                <div class="alert alert-success" role="alert">
                                                                    <span class="fileName"></span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <div class="">
                                                    <div class="card-header">
                                                        <h5>Attachment 2</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        {{-- <form class="uploadForm dropzone"> --}}
                                                        <div class="fallback">
                                                            <label class="form-label">Upload Document</label>
                                                            <input name="file2" type="file" class="fileInput form-control" accept=".pdf,.doc,.docx,.txt,.xlsx">
                                                        </div>
                                                        {{-- </form> --}}

                                                        <!-- Preview Container -->
                                                        <div class="previewBox text-center mt-4" style="display: none;">
                                                            <h5>Uploaded Document:</h5>
                                                            <a class="downloadLink" href="#" download>
                                                                <div class="alert alert-success" role="alert">
                                                                    <span class="fileName"></span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex wizard justify-content-between mt-3">
                                            <div class="first">
                                                <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                                </a>
                                            </div>
                                            <div class="last">
                                                <a href="javascript:void(0);" class="btn btn-primary next-btn d-flex align-items-center justify-content-center">
                                                    Next <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="audit-trail" role="tabpanel" aria-labelledby="audit-trail-tab">
                                <div class="row">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Audit Trail</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Purchase By</label>
                                                    <input type="text" class="form-control" id="purchaseByAudit" name="purchaseByAudit" placeholder="Enter Purchaser Name">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Purchase Date</label>
                                                    <input type="date" class="form-control" id="purchaseDateAudit" name="purchaseDateAudit" placeholder="Enter Purchase Date">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Approve By</label>
                                                    <input type="text" class="form-control" id="approveByAudit" name="approveByAudit" placeholder="Enter Approver Name">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Approve Date</label>
                                                    <input type="date" class="form-control" id="approveDateAudit" name="approveDateAudit" placeholder="Enter Approve Date">
                                                </div>
                                            </div>
                                            <div class="d-flex wizard justify-content-between mt-3">
                                                <div class="first">
                                                    <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                                    </a>
                                                </div>
                                                <div class="last">
                                                    <button type='submit' class="btn btn-primary d-flex align-items-center justify-content-center">
                                                        Add Asset <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
<script>

	//onchange of any drop down, reset the values of all div hide elements
	function resetHiddenFields() {
		$('div:hidden').each(function () {
			// Clear text, date, number, textarea
			$(this).find('input[type="text"], input[type="date"], input[type="number"], textarea').val('');
			// Reset selects
			$(this).find('select').prop('selectedIndex', 0);
			// Reset radio & checkbox if needed
			//$(this).find('input[type="radio"], input[type="checkbox"]').prop('checked', false);
		});
	}
	
	//Start on change asset type
	$('#assetType').on('change', function () {

		var assetType = $(this).val();
		// Hide section first
		$('#nonCurrentAssetsSection').hide();
		// Clear dropdown
		$('#nonCurrentAssetsType').empty();

		if (assetType == 'non-current') {
			// ===== Non Current Asset =====
			$('#assetTypeLabel').html('Non-Current Assets Type <span class="text-danger">*</span>');
			$('#assetCodeLabel').html('Asset Code / ID <span class="text-danger">*</span>');
			$('#assetStatusLabel').html('Asset Status');
			$('#asset_status').html(`
				<option value="Active">Active</option>
				<option value="Under Construction">Under Construction</option>
			`);

			$('#nonCurrentAssetsType').append(`
				<option value="">Select</option>
				<option value="Property Plant Equipment">Property, Plant & Equipment (PPE)</option>
				<option value="Furniture Fixtures">Furniture & Fixtures</option>
				<option value="Computer IT Equipment">Computer & IT Equipment</option>
				<option value="Machinery">Machinery</option>
				<option value="Vehicles">Vehicles</option>
				<option value="Intangible Assets">Intangible / Non-physical Assets</option>
				<option value="Capital Work in Progress">Capital Work-in-Progress</option>
				<option value="Other Non-Current Assets">Other Non-Current Assets</option>
			`);

			$('#nonCurrentAssetsSection').show();
		}
		else if (assetType == 'capex') 
		{
			// ===== CapEx =====
			$('#assetTypeLabel').html('CapEx Type <span class="text-danger">*</span>');
			$('#assetCodeLabel').html('CapEx ID <span class="text-danger">*</span>');
			$('#assetStatusLabel').html('Capitalisation Status');
			$('#asset_status').html(`
				<option value="Pending">Pending</option>
				<option value="Capitalised">Capitalised</option>
				<option value="CWIP">CWIP</option>
				<option value="Disposed">Disposed</option>
			`);

			$('#nonCurrentAssetsType').append(`
				<option value="">Select</option>
				<option value="New Purchase">New Purchase</option>
				<option value="Replacement">Replacement</option>
				<option value="Improvement">Improvement</option>
				<option value="Expansion">Expansion</option>
				<option value="Renovation">Renovation</option>
				<option value="Capital Repair">Capital Repair</option>
			`);

			$('#nonCurrentAssetsSection').show();
		}

	}).trigger('change');
	//End on change asset type
	

    document.addEventListener("DOMContentLoaded", function() {
        const assetTypeDropdown = document.getElementById("assetType");
        const otherAssetsGroup = document.getElementById("otherAssetsGroup");
        const customAssetsGroup = document.getElementById("customAssetsGroup");

        if (assetTypeDropdown) {
            assetTypeDropdown.addEventListener("change", function() {
                const selectedValue = this.value;

                if (selectedValue === "other") {
                    otherAssetsGroup.style.display = "block";
                    customAssetsGroup.style.display = "none";
                } else if (selectedValue === "custom") {
                    otherAssetsGroup.style.display = "none";
                    customAssetsGroup.style.display = "block";
                } else {
                    otherAssetsGroup.style.display = "none";
                    customAssetsGroup.style.display = "none";
                }
            });
        }
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Select all upload forms
        const fileInputs = document.querySelectorAll(".fileInput");

        fileInputs.forEach((fileInput) => {
            // Get corresponding preview elements
            const cardBody = fileInput.closest(".card-body");
            const previewBox = cardBody.querySelector(".previewBox");
            const fileNameSpan = previewBox.querySelector(".fileName");
            const downloadLink = previewBox.querySelector(".downloadLink");

            // Handle file upload
            fileInput.addEventListener("change", function() {
                const file = fileInput.files[0];
                if (file) {
                    const fileURL = URL.createObjectURL(file);

                    // Update preview and download link
                    previewBox.style.display = "block";
                    fileNameSpan.textContent = file.name;
                    downloadLink.href = fileURL;
                    downloadLink.download = file.name;
                } else {
                    // Hide preview if no file is selected
                    previewBox.style.display = "none";
                }
            });
        });
    });
    
	
	document.getElementById("assetType").addEventListener("change", function () {

		let assetType = this.value;

		// SHOW / HIDE
		$('#currentAssetsSection').toggle(assetType === "current");
		$('#nonCurrentAssetsSection').toggle(assetType === "non-current" || assetType === "capex");

		// BUTTON TEXT
		if (assetType === "current") {
			$('#nextBtn1').text('Submit');
		} else {
			$('#nextBtn1').html('Next <i class="ti ti-arrow-up-right-circle ms-2"></i>');
		}

		// TAB CONTROL
		if (assetType === "current") {

			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
				.addClass('disabled-tab')
				.css({
					'pointer-events': 'none',
					'opacity': '0.5'
				});

		} else {

			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
				.removeClass('disabled-tab')
				.css({
					'pointer-events': 'auto',
					'opacity': '1'
				});
		}

		// RESET
		$('#currentAssetsType').val('');
		$('#nonCurrentAssetsType').val('');

		//Hide all dependent sections
		$('#commonSection').hide();
		$('#WorkinProgressSection').hide();
		$('#cashInHandSection').hide();
		$('#bankAccountSection').hide();
		$('#tradeReceivableSection').hide();
		$('#advanceVendorSection').hide();
		$('#employeeAdvanceSection').hide();
		$('#prepaidExpenseSection').hide();
		$('#itcSection').hide();
		$('#tdsReceivableSection').hide();
		$('#inventorySection').hide();

	});


    document.getElementById("currentAssetsType").addEventListener("change", function() {
        document.getElementById("otherCurrentAssetsDiv").style.display = (this.value === "other_current_assets") ? "block" : "none";
        document.getElementById("nonCurrentAssetsSection").style.display = (this.value === "non-current") ? "block" : "none";
        document.getElementById("nonCurrentAssetsSection").style.display = (this.value === "capex") ? "block" : "none";
        //ocument.getElementById("investmentSection").style.display = (this.value === "investment") ? "block" : "none";
    });

    document.getElementById("nonCurrentAssetsType").addEventListener("change", function() {
        document.getElementById("otherNonCurrentAssetsDiv").style.display = (this.value === "other_noncurrent_assets") ? "block" : "none";
    });
	
	document.addEventListener("DOMContentLoaded", function() {

		const nextButtons = document.querySelectorAll(".next-btn");
		const prevButtons = document.querySelectorAll(".previous-btn");

		nextButtons.forEach(button => {
			button.addEventListener("click", function(event) {
				event.preventDefault();

				const currentTab = document.querySelector(".tab-pane.active");
				const currentTabId = currentTab.getAttribute("id");

				const assetType = document.getElementById("assetType").value;

				// ✅ CONDITION: First tab + current asset → submit
				if (currentTabId === 'asset-information' && assetType === 'current') {
					let form = $('#addAssetFrm')[0];
					let formData = new FormData(form);
					let formAction = $("#addAssetFrm").data("route");
					$("#loader").show();
					$.ajax({
						url: formAction,
						type: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						beforeSend: function () {
							$('.next-btn').prop('disabled', true).text('Submitting...');
						},												
						success: function(response) {
							$("#loader").hide();
							$('.next-btn').prop('disabled', false).text('Submit');
							if (response.status === "success") {
								showToast(response.message, "success");
								setTimeout(() => {
									window.location.href = response.redirect;
								}, 2000);
							} else {
								if (typeof response === 'object' && response.message === undefined) {
									let errorMessages = [];
									for (let field in response) {
										if (Array.isArray(response[field])) {
											errorMessages.push(response[field][0]); // Get first error for each field
										}
									}
									let combinedMessage = errorMessages.length > 0 ? errorMessages.join('\n') : "Validation error occurred.";
									showToast(combinedMessage, "error");
								} else {
									showToast(response.message || "An error occurred.", "error");
								}
								console.error("Error Response:", response);
							}
						},
						error: function(xhr, status, error) {
							$("#loader").hide();
							$('.next-btn').prop('disabled', false).text('Submit');
							showToast("Something went wrong! Please try again.", "error");
						}
					});
					return;
				}

				// ✅ NORMAL FLOW
				const nextTab = currentTab.nextElementSibling;

				if (nextTab && nextTab.classList.contains("tab-pane")) {
					let nextTabId = nextTab.getAttribute("id");
					let tabToShow = new bootstrap.Tab(
						document.querySelector(`[href="#${nextTabId}"]`)
					);
					tabToShow.show();
				}
			});
		});

		prevButtons.forEach(button => {
			button.addEventListener("click", function(event) {
				event.preventDefault();

				const currentTab = document.querySelector(".tab-pane.active");
				const prevTab = currentTab.previousElementSibling;

				if (prevTab && prevTab.classList.contains("tab-pane")) {
					let prevTabId = prevTab.getAttribute("id");
					let tabToShow = new bootstrap.Tab(
						document.querySelector(`[href="#${prevTabId}"]`)
					);
					tabToShow.show();
				}
			});
		});

	});

    $(document).on("submit", "#addAssetFrm", function(e) {
        e.preventDefault();

        // Get field values
        let date = $("#date").val().trim();
        let assetType = $("#assetType").val();

        // Manual Validation
        if (date === "") {
            showToast("Date is required!", "error");
            $("#date").focus();
            return false;
        }
		
        if (assetType === "") {
            showToast("Asset Type is required!", "error");
            $("#assetType").focus();
            return false;
        }

        let formData = new FormData(this);

        const fileInput = document.getElementById('fileUpload');
        if (fileInput && fileInput.files.length > 0) {
            formData.append('asset_image', fileInput.files[0]);
        }

        let formAction = $("#addAssetFrm").data("route");
		$("#loader").show();

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: formAction,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
				$("#loader").hide();
                if (response.status === "success") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 2000);
                } else {
                    // Check if response contains validation errors (object with field errors)
                    if (typeof response === 'object' && response.message === undefined) {
                        // Validation errors from server
                        let errorMessages = [];
                        for (let field in response) {
                            if (Array.isArray(response[field])) {
                                errorMessages.push(response[field][0]); // Get first error for each field
                            }
                        }
                        let combinedMessage = errorMessages.length > 0 ? errorMessages.join('\n') : "Validation error occurred.";
                        showToast(combinedMessage, "error");
                    } else {
                        // Standard error message
                        showToast(response.message || "An error occurred.", "error");
                    }
                    console.error("Error Response:", response);
                }
            },
            error: function(xhr, status, error) {
                $("#loader").hide();
                showToast("Something went wrong! Please try again.", "error");
            }
        });
    });


    function triggerFileUpload() {
        document.getElementById('fileUpload').click();
    }

    function handleFileUpload(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('uploadedImage').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
	//cash in hand
	function loadCashInHand() {
		let propId = $('select[name="propId"]').val();

		$.ajax({
			url: '/get-cash-in-hand',
			type: 'GET',
			data: { propId: propId },
			success: function (res) {
				$('input[name="cash_amount"]').val(res.cash_in_hand);
			}
		});
	}
	$('select[name="propId"]').on('change', function () {
		loadCashInHand();
	});
	//Bank Accounts
	function loadBankAccounts() {
		$.get('/bank-accounts', function (data) {

			let html = '<option value="">All Banks</option>';
			let totalBalance = 0;

			data.forEach(row => {
				let bal = parseFloat(row.curr_bal) || 0;
				totalBalance += bal;

				html += `<option value="${row.id}" data-balance="${bal}">
							${row.bank_name}
						 </option>`;
			});

			$('#bank_id').html(html);
			$('#bank_balance').val(totalBalance.toFixed(2));
		});
	}

	$('#bank_id').on('change', function () {

		let selected = $(this).val();
		if (selected === "") {
			let total = 0;

			$('#bank_id option').each(function () {
				let bal = parseFloat($(this).data('balance')) || 0;
				total += bal;
			});

			$('#bank_balance').val(total.toFixed(2));

		} else {
			let balance = $(this).find(':selected').data('balance') || 0;
			$('#bank_balance').val(parseFloat(balance).toFixed(2));
		}
	});
	
	//Trade Receivable
	function loadTradeReceivableAmount() {
		let date = $('#date').val();
		$.ajax({
			url: '/get-trade-receivable-amount',
			type: 'GET',
			data: { date: date },
			success: function(res) {
				$('#amount').val(res.total_amount);
			}
		});
	}
	
	//Advance to vendor
	function loadAdvanceVendorAmount() {
		let date = $('#date').val();
		$.ajax({
			url: '/get-advance-vendor-amount',
			type: 'GET',
			data: { date: date },
			success: function(res) {
				$('#amount_vendor').val(res.total_amount);
			}
		});
	}
	
	//Employee Advance
	function loadEmployeeAdvance() {
		$.get('/get-employee-advance', function (data) {
			$('#employee_advance_amount').val(data.amount);
		});
	}
	
	//Prepaid Expense
	function calculatePrepaid() 
	{
		$.get('/get-employee-advance', function (data) {
			$('#prepaid_amt').val(data.amount);
		});
	}
	
	//ITC input
	function loadVendorsITC() {
		$.get('/get-itc', function (data) {
			$('#itc_amt').val(data.input_itc);
		});
	}
	
	//TDS RECEIVABLES
	function calculateMonthlyTDS() {
		let date = $('#date').val();
		$.get('/calculate-monthly-tds', { month: date }, function (res) {
			$('#tds_gross_amount').val(res.tds_gross_amount || 0);
		});
	}
	
	//Inventories
	function calculateGrossProfit() {
		let date = $('#date').val();
		$.get('/calculate-gross-profit', { month: date }, function (res) {
			$('#gross_profit').val(res.gross_profit);
		});
	}


     // Current Asset Type
    $('#currentAssetsType').trigger('change');
    $('#currentAssetsType').on('change', function () {
		let value = $(this).val();
		$('#cashInHandSection').hide();
		$('#bankAccountSection').hide();
		$('#tradeReceivableSection').hide();
		$('#advanceVendorSection').hide();
		$('#employeeAdvanceSection').hide();
		$('#prepaidExpenseSection').hide();
		$('#itcSection').hide();
		$('#tdsReceivableSection').hide();
		$('#inventorySection').hide();

		if (value === 'Cash in Hand') {
			$('#cashInHandSection').show();
			loadCashInHand();

		} else if (value === 'Bank Accounts') {
			$('#bankAccountSection').show();
			loadBankAccounts();

		} else if (value === 'Trade Receivables') {
			$('#tradeReceivableSection').show();
			loadTradeReceivableAmount();
		} else if (value === 'Advance to Vendor') {
			$('#advanceVendorSection').show();
			loadAdvanceVendorAmount();
		} else if (value === 'Employee Advance') {
			$('#employeeAdvanceSection').show();
			loadEmployeeAdvance(); 
		} else if (value === 'Prepaid Expenses') {
			$('#prepaidExpenseSection').show();
			calculatePrepaid();
		} else if (value === 'Input GST Credit') {
			$('#itcSection').show();
			loadVendorsITC();
		} else if (value === 'TDS Receivable') {
			$('#tdsReceivableSection').show();
			calculateMonthlyTDS();
		} else if (value === 'Inventories') {
			$('#inventorySection').show();
			calculateGrossProfit();
		}
	});

	//Non-Current Asset Type
    $('#nonCurrentAssetsType').trigger('change');
    $('#nonCurrentAssetsType').on('change', function () {

		var selectedValue = $(this).val();

		const showSectionValues = [
			'Property Plant Equipment',
			'Furniture Fixtures',
			'Computer IT Equipment',
			'Machinery',
			'Vehicles',
			'Intangible Assets',
			'Other Non-Current Assets',
			'New Purchase',
			'Replacement',
			'Improvement',
			'Expansion',
			'Renovation',
			'Capital Repair'
		];

		if (showSectionValues.includes(selectedValue)) {
			$('#commonSection').show();
		} else {
			$('#commonSection').hide();
			$('#cashInHandSection').hide();
			$('#bankAccountSection').hide();
			$('#tradeReceivableSection').hide();
			$('#advanceVendorSection').hide();
			$('#employeeAdvanceSection').hide();
			$('#prepaidExpenseSection').hide();
			$('#itcSection').hide();
			$('#tdsReceivableSection').hide();
			$('#inventorySection').hide();
		}
	});
	
	//Work-in-Progress
	$('#nonCurrentAssetsType').on('change', function () {

		let value = $(this).val();

		if (value === 'Capital Work in Progress') {
			$('#WorkinProgressSection').show();
		} else if (value) {
			$('#WorkinProgressSection').hide();
		} else {
			$('#WorkinProgressSection').hide();
		}
	});
	
	//Calculate adjusted amount
	$(document).ready(function () {

		function resetFields() {

			$('#advance_amt, #payable_amt, #adjusted_amt')
				.val('')
				.prop({ readonly: true, required: false });

			// Hide optional fields
			$('#advance_amt').closest('.col-xl-4').hide();
			$('#payable_amt').closest('.col-xl-4').hide();
			$('#adjusted_amt').closest('.col-xl-4').hide();
		}

		function calculateAmounts() {

			let invoice  = parseFloat($('#invoice_value').val()) || 0;
			let advance  = parseFloat($('#advance_amt').val()) || 0;
			let adjusted = parseFloat($('#adjusted_amt').val()) || 0;
			let status   = $('#pay_status').val();

			// ================= FULL =================
			if (status === 'Full') {

				// Show only Adjusted
				$('#adjusted_amt').closest('.col-xl-4').show();
				$('#advance_amt').closest('.col-xl-4').hide();
				$('#payable_amt').closest('.col-xl-4').hide();

				$('#adjusted_amt')
					.val(invoice)
					.prop({ readonly: true, required: true });

				$('#advance_amt').val(0);
				$('#payable_amt').val(0);
			}

			// ================= ADVANCE =================
			else if (status === 'Advance') {

				// Show all
				$('#advance_amt').closest('.col-xl-4').show();
				$('#payable_amt').closest('.col-xl-4').show();
				$('#adjusted_amt').closest('.col-xl-4').show();

				$('#advance_amt').prop({ readonly: false, required: true });
				$('#adjusted_amt').prop({ readonly: false, required: true });
				$('#payable_amt').prop({ readonly: true, required: true });

				// Validation: advance ≤ invoice
				if (advance > invoice) {
					advance = invoice;
					$('#advance_amt').val(invoice);
					alert('Advance cannot be greater than Invoice Value');
				}

				// Sync adjusted with advance if empty
				if (!adjusted) {
					adjusted = advance;
					$('#adjusted_amt').val(adjusted);
				}

				// Validation: adjusted ≤ advance
				if (adjusted > advance) {
					adjusted = advance;
					$('#adjusted_amt').val(advance);
					alert('Adjusted cannot exceed Advance Amount');
				}

				let payable = invoice - advance;
				$('#payable_amt').val(payable.toFixed(2));
			}

			// ================= DEFAULT =================
			else {
				resetFields();
			}
		}

		// ================= EVENTS =================
		$('#pay_status').on('change', function () {
			resetFields();
			calculateAmounts();
		});

		$('#invoice_value').on('input', calculateAmounts);

		$('#advance_amt').on('input', function () {
			// sync adjusted
			$('#adjusted_amt').val($(this).val());
			calculateAmounts();
		});

		$('#adjusted_amt').on('input', function () {
			calculateAmounts();
		});

		// ================= INIT (EDIT MODE) =================
		resetFields();
		calculateAmounts();

	});
	
	//Separate logic for CWIP
	$(document).ready(function () {

		function resetWipFields() {
			$('#cwip_advance_amt, #cwip_payable_amt, #cwip_adjusted_amt')
				.val('')
				.prop({ readonly: true, required: false });

			$('#cwip_advance_amt').closest('.col-xl-4').hide();
			$('#cwip_payable_amt').closest('.col-xl-4').hide();
			$('#cwip_adjusted_amt').closest('.col-xl-4').hide();
		}

		function calculateWipAmounts() {

			let invoice  = parseFloat($('#cwip_amount').val()) || 0;
			let advance  = parseFloat($('#cwip_advance_amt').val()) || 0;
			let adjusted = parseFloat($('#cwip_adjusted_amt').val()) || 0;
			let status   = $('#cwip_pay_status').val();

			if (status === 'Full') {

				$('#cwip_adjusted_amt').closest('.col-xl-4').show();
				$('#cwip_advance_amt').closest('.col-xl-4').hide();
				$('#cwip_payable_amt').closest('.col-xl-4').hide();

				$('#cwip_adjusted_amt')
					.val(invoice)
					.prop({ readonly: true, required: true });

				$('#cwip_advance_amt').val(0);
				$('#cwip_payable_amt').val(0);
			}

			else if (status === 'Advance') {

				$('#cwip_advance_amt').closest('.col-xl-4').show();
				$('#cwip_payable_amt').closest('.col-xl-4').show();
				$('#cwip_adjusted_amt').closest('.col-xl-4').show();

				$('#cwip_advance_amt').prop({ readonly: false, required: true });
				$('#cwip_adjusted_amt').prop({ readonly: false, required: true });
				$('#cwip_payable_amt').prop({ readonly: true, required: true });

				if (advance > invoice) {
					advance = invoice;
					$('#cwip_advance_amt').val(invoice);
					alert('Advance cannot exceed invoice');
				}

				if (!adjusted) {
					adjusted = advance;
					$('#cwip_adjusted_amt').val(adjusted);
				}

				if (adjusted > advance) {
					adjusted = advance;
					$('#cwip_adjusted_amt').val(advance);
					alert('Adjusted cannot exceed advance');
				}

				let payable = invoice - advance;
				$('#cwip_payable_amt').val(payable.toFixed(2));
			}

			else {
				resetWipFields();
			}
		}

		// EVENTS
		$('#cwip_pay_status').on('change', function () {
			resetWipFields();
			calculateWipAmounts();
		});

		$('#cwip_amount').on('input', calculateWipAmounts);

		$('#cwip_advance_amt').on('input', function () {
			$('#cwip_adjusted_amt').val($(this).val());
			calculateWipAmounts();
		});

		$('#cwip_adjusted_amt').on('input', calculateWipAmounts);

		// INIT
		resetWipFields();
		calculateWipAmounts();

	});
	
	//TDS applicable
	$(document).ready(function () {

		function toggleTDS() {
			let val = $('input[name="tds_applicable"]:checked').val();

			if (val === 'yes') {
				$('#tdsContainer').show();
				calculateTDS(); 
			} else {
				$('#tdsContainer').hide();
				$('#tds_amt').val(0); 
			}
		}

		function calculateTDS() {

			let assetType = $('#assetType').val();
			let nonCurrentType = $('#nonCurrentAssetsType').val();

			let invoice = parseFloat($('#invoice_value').val()) || 0;
			let cwip = parseFloat($('[name="cwip_amount"]').val()) || 0;

			$.ajax({
				url: "{{ route('calculate.tds') }}",
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					assetType: assetType,
					nonCurrentAssetType: nonCurrentType,
					invoice_value: invoice,
					cwip_amount: cwip
				},
				success: function (res) {
					if (res.status === 'success') {
						$('#tds_amt').val(res.tds_amt);
					}
				}
			});
		}

		$('input[name="tds_applicable"]').on('change', function () {
			toggleTDS();
		});

		$('#invoice_value, [name="cwip_amount"], #assetType, #nonCurrentAssetsType').on('input change', function () {
			if ($('#tdsYes').is(':checked')) {
				calculateTDS();
			}
		});

		toggleTDS();

	});
	
	//GST applicable
	document.addEventListener("DOMContentLoaded", function () {

		document.querySelectorAll(".gst-container").forEach((container) => {

			// ✅ CURRENT ASSET GST
			const gstYes = container.querySelector('#gstYes_ca');
			const gstNo  = container.querySelector('#gstNo_ca');

			if (gstYes && gstNo) {
				toggleGST(container, 'ca');
				gstYes.addEventListener('change', () => toggleGST(container, 'ca'));
				gstNo.addEventListener('change', () => toggleGST(container, 'ca'));
			}

			// ✅ NON-CURRENT ASSET GST
			const gstYesNca = container.querySelector('#gstYes_nca');
			const gstNoNca  = container.querySelector('#gstNo_nca');

			if (gstYesNca && gstNoNca) {
				toggleGST(container, 'nca');
				gstYesNca.addEventListener('change', () => toggleGST(container, 'nca'));
				gstNoNca.addEventListener('change', () => toggleGST(container, 'nca'));
			}

		});

		function toggleGST(container, type) {

			let isYes = false;

			if (type === 'ca') {
				isYes = container.querySelector('#gstYes_ca')?.checked;
			} else {
				isYes = container.querySelector('#gstYes_nca')?.checked;
			}

			let fields = [];

			if (type === 'ca') {
				fields = container.querySelectorAll('#gst_trans, #gst_rate, #gst_allocation,#gst_amt');
			} else {
				
			}

			fields.forEach(field => {
				field.closest(".col-md-4").style.display = isYes ? "block" : "none";
			});
		}

	});
	
	//GST calculation
	function calculateGST() 
	{

		let assetType = $('#assetType').val();
		let nonCurrentType = $('#nonCurrentAssetsType').val();

		let invoiceValue = parseFloat($('#invoice_value').val()) || 0;
		let cwipAmount   = parseFloat($('input[name="cwip_amount"]').val()) || 0;

		let gstRate  = parseFloat($('#gst_rate').val()) || 0;
		let gstTrans = $('#gst_trans').val();

		let baseAmount = 0;

		// ================= BASE AMOUNT =================
		if (assetType === 'non-current') {
			if (nonCurrentType === 'Capital Work in Progress') {
				baseAmount = cwipAmount;
			} else {
				baseAmount = invoiceValue;
			}
		}

		// ================= GST CALC =================
		let gstAmount = (baseAmount * gstRate) / 100;

		// ================= GST SPLIT =================
		let cgst = 0, sgst = 0, igst = 0;

		if (gstTrans === 'intrastate' || gstTrans === 'union') {
			cgst = gstAmount / 2;
			sgst = gstAmount / 2;
		} else if (gstTrans === 'interstate') {
			igst = gstAmount;
		}

		// ================= SET VALUES =================
		$('#gst_amt').val(gstAmount.toFixed(2));

		if (igst > 0) {
			$('#gst_allocation').val(`IGST: ${igst.toFixed(2)}`);
		} else {
			$('#gst_allocation').val(`CGST: ${cgst.toFixed(2)} | SGST: ${sgst.toFixed(2)}`);
		}
	}
	
	$('#assetType, #nonCurrentAssetsType, #invoice_value, input[name="cwip_amount"], #gst_rate, #gst_trans')
	.on('change keyup', function () {
		calculateGST();
	});
	
	// Depreciation calculation (Depreciation Value = (Asset Cost – Residual Value) ÷ Useful Life)

	$(document).ready(function () {

		// =========================
		// SLM CALCULATION
		// =========================
		function calculateDepreciation() {

			let cost     = parseFloat($('#invoice_value').val()) || 0;
			let residual = parseFloat($('#residual_value').val()) || 0;
			let life     = parseFloat($('#useful_life_years').val()) || 0;

			if (cost > 0 && life > 0) {

				if (residual > cost) {
					alert('Residual value cannot be greater than Asset Amount Value');
					$('#residual_value').val(cost);
					residual = cost;
				}

				// let depreciation = (cost - residual) / life;

				// $('#depreciation_value').val(depreciation.toFixed(2));

				let depreciation = (cost - residual) / life;

				$('#depreciation_value').val(depreciation.toFixed(2));

			} else {
				$('#depreciation_value').val('');
			}
		}

		// =========================
		// WDV CALCULATION
		// =========================

		function calculateWDV() {

			let cost      = parseFloat($('#invoice_value').val()) || 0;
			let rate      = parseFloat($('#depreciation_rate').val()) || 0;
			// let life      = parseInt($('#useful_life_years').val()) || 0;
			let frequency = $('#depreciation_frequency').val();

			if (cost <= 0 || rate <= 0) {
				$('#depreciation_value').val('');
				return;
			}

			// HALF YEAR
			if (frequency === 'Half Year') {

				$('#useful_life_years').prop('disabled', true);
				$('#useful_life_years').val('');
				$('#usefulLifeDiv').hide();

				let invoiceDate = $('#invoice_date').val();
				let depDate     = $('#depreciation_start_date').val();

				if (invoiceDate && depDate) {

					let invDate = new Date(invoiceDate);
					let startDate = new Date(depDate);

					let diffDays = Math.abs(startDate - invDate) / (1000 * 60 * 60 * 24);

					// If <= 180 days then use half rate
					if (diffDays <= 180) {
						rate = rate / 2;
					}
				}

				let closingWDV = cost - ((cost * rate) / 100);

				$('#depreciation_value').val(closingWDV.toFixed(2));
			}

			// YEARLY
			else {

				$('#useful_life_years').prop('disabled', false);
				$('#usefulLifeDiv').hide();

				let depreciation = (cost * rate) / 100;
				let closingWDV = cost - depreciation;
				$('#depreciation_value').val(closingWDV.toFixed(2));
			}
		}

		
		// =========================
		// TOGGLE FIELDS
		// =========================
		function toggleDepreciationFields() {

			let method = $('#depreciation_method').val();

			if (method === 'WDV') {

				$('#depreciationRateDiv').show();
				$('#residualValueDiv').hide();
				$('#usefulLifeDiv').hide();

				$('#residual_value').val('');
				
				if ($('#depreciation_frequency option[value="Half Year"]').length === 0) {

					/*$('#depreciation_frequency').append(
						'<option value="Half Year">Half Year</option>'
					);*/
				}

				calculateWDV();
			}

			else if (method === 'SLM') {

				$('#depreciationRateDiv').hide();
				$('#residualValueDiv').show();
				$('#usefulLifeDiv').show();

				$('#useful_life_years').prop('disabled', false);

				$('#depreciation_frequency option[value="Half Year"]').remove();

				if ($('#depreciation_frequency').val() === 'Half Year') {
					$('#depreciation_frequency').val('');
				}

				calculateDepreciation();
			}

			else {

				$('#depreciationRateDiv').hide();
				$('#residualValueDiv').show();
				$('#depreciation_value').val('');
			}
		}

		// =========================
		// METHOD CHANGE
		// =========================
		$('#depreciation_method').on('change', function () {

			toggleDepreciationFields();
		});

		// =========================
		// SLM EVENTS
		// =========================
		$('#invoice_value, #residual_value, #useful_life_years').on('input', function () {

			if ($('#depreciation_method').val() === 'SLM') {

				calculateDepreciation();
			}
		});

		// =========================
		// WDV EVENTS
		// =========================
		$('#invoice_value, #depreciation_rate, #useful_life_years')
		.on('input', function () {

			if ($('#depreciation_method').val() === 'WDV') {

				calculateWDV();
			}
		});

		$('#depreciation_frequency, #invoice_date, #depreciation_start_date')
		.on('change', function () {

			if ($('#depreciation_method').val() === 'WDV') {

				calculateWDV();
			}
		});

		// =========================
		// PAGE LOAD
		// =========================
		toggleDepreciationFields();



	});
	
	function allowDecimal(el) {
		let val = el.value.replace(/[^0-9.]/g, '');
		let parts = val.split('.');
		if (parts.length > 2) {
			val = parts[0] + '.' + parts.slice(1).join('');
		}
		el.value = val;
	}

	$('#invoice_value, #residual_value, #useful_life_years').on('input', function () {
		allowDecimal(this);
	});

    function startAddAssetTour() {
        if (typeof introJs !== 'function') return;

        let tour = introJs().setOptions({
            steps: [
                {
                    title: 'Add Asset Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Register a new company asset. Follow the tabs on the left to input asset information, GST & TDS adjustments, documents, and audit details.</p></div>'
                },
                {
                    element: '#asset-information-tab',
                    title: 'Asset Info Section',
                    intro: 'Define the asset type (current/non-current), purchase date, cost, depreciation method, and useful life.'
                },
                {
                    element: '#gst-tds-tab',
                    title: 'GST & TDS Configurations',
                    intro: 'Configure tax properties, state transactions, tax rates, and TDS deduction details.'
                },
                {
                    element: '#documentation-tab',
                    title: 'Attachments & Docs',
                    intro: 'Upload digital copies of purchase invoices, registration certificates, or warrant documents.'
                },
                {
                    element: '#submitBtn',
                    title: 'Submit Asset',
                    intro: 'Click here to save the asset profile in the ledger.'
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
        });

        tour.onbeforechange(function(targetElement) {
            if (!targetElement) return;

            // Find the closest tab-pane containing the target element
            let tabPane = targetElement.closest('.tab-pane');
            if (tabPane) {
                let tabId = tabPane.getAttribute('id');
                let tabTrigger = document.getElementById(tabId + '-tab');
                if (tabTrigger && !tabTrigger.classList.contains('active')) {
                    let tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        });

        tour.start();
    }

    $(document).ready(function() {
        $('#start-add-asset-tour').on('click', function(e) {
            e.preventDefault();
            startAddAssetTour();
        });
    });
</script>
@endsection
