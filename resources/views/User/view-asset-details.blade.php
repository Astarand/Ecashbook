@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/assets-list') }}">Assets</a></li>
                        <li class="breadcrumb-item" aria-current="page">View Asset</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Asset</h2>
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
					
                    <form data-route="{{ route('user.UpdateAsset', $assetId) }}" name="addAssetFrm" id="addAssetFrm" enctype="multipart/form-data">
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
														<option value="{{ $company->id }}" <?=($asset->propId == $company->id) ? 'selected' : '' ?>>
															{{ $company->comp_name }}
														</option>
													@endforeach
												</select>
											</div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Date<span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" value="{{$asset->date}}">
                                            </div>
                                            
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Asset Type<span class="text-danger">*</span></label>
                                                <select id="assetType" name="assetType" class="form-select">
                                                    <option value="">Select</option>
                                                    <option value="current" <?= ($asset->assetType == 'current')?'selected':'' ?> >Current Assets</option>
                                                    <option value="non-current" <?= ($asset->assetType == 'non-current')?'selected':'' ?>>Non Current Assets</option>
                                                </select>
                                            </div>
                                            <!-- Current Assets Section -->
                                            <div id="currentAssetsSection" class="row" style="display: none;">
                                                <div class="col-sm-12 mb-3">
                                                    <label class="form-label">Current Assets Type <span class="text-danger">*</span></label>
                                                    <select id="currentAssetsType" name="currentAssetType" class="form-select">
                                                        <option value="">Select</option>
														<option value="Cash in Hand" <?= ($asset->currentAssetType == 'Cash in Hand') ? 'selected' : '' ?>>Cash in Hand</option>
														<option value="Bank Accounts" <?= ($asset->currentAssetType == 'Bank Accounts') ? 'selected' : '' ?>>Bank Accounts</option>
														<option value="Trade Receivables" <?= ($asset->currentAssetType == 'Trade Receivables') ? 'selected' : '' ?>>Trade Receivables (Debtors)</option>
														<option value="Advance to Vendor" <?= ($asset->currentAssetType == 'Advance to Vendor') ? 'selected' : '' ?>>Advance to Vendor</option>
														<option value="Employee Advance" <?= ($asset->currentAssetType == 'Employee Advance') ? 'selected' : '' ?>>Employee Advance</option>
														<option value="Prepaid Expenses" <?= ($asset->currentAssetType == 'Prepaid Expenses') ? 'selected' : '' ?>>Prepaid Expenses</option>
														<option value="Input GST Credit" <?= ($asset->currentAssetType == 'Input GST Credit') ? 'selected' : '' ?>>Input GST Credit (ITC)</option>
														<option value="TDS Receivable" <?= ($asset->currentAssetType == 'TDS Receivable') ? 'selected' : '' ?>>TDS Receivable</option>
														<option value="Inventories" <?= ($asset->currentAssetType == 'Inventories') ? 'selected' : '' ?>>Inventories / Stocks</option>														
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
													<label class="form-label">Non-Current Assets Type <span class="text-danger">*</span></label>
													<select id="nonCurrentAssetsType" class="form-select" name="nonCurrentAssetType">
														<option value="">Select</option>
														<option value="Property Plant Equipment" {{ $asset->nonCurrentAssetType == 'Property Plant Equipment' ? 'selected' : '' }}>Property, Plant & Equipment (PPE)</option>
														<option value="Furniture Fixtures" {{ $asset->nonCurrentAssetType == 'Furniture Fixtures' ? 'selected' : '' }}>Furniture & Fixtures</option>
														<option value="Computer IT Equipment" {{ $asset->nonCurrentAssetType == 'Computer IT Equipment' ? 'selected' : '' }}>Computer & IT Equipment</option>
														<option value="Machinery" {{ $asset->nonCurrentAssetType == 'Machinery' ? 'selected' : '' }}>Machinery</option>
														<option value="Vehicles" {{ $asset->nonCurrentAssetType == 'Vehicles' ? 'selected' : '' }}>Vehicles</option>
														<option value="Intangible Assets" {{ $asset->nonCurrentAssetType == 'Intangible Assets' ? 'selected' : '' }}>Intangible / Non-physical Assets</option>
														<option value="Capital Work in Progress" {{ $asset->nonCurrentAssetType == 'Capital Work in Progress' ? 'selected' : '' }}>Capital Work-in-Progress</option>
														<option value="Other Non-Current Assets" {{ $asset->nonCurrentAssetType == 'Other Non-Current Assets' ? 'selected' : '' }}>Other Non-Current Assets</option>
													</select>
												</div>
											</div>
                                           
                                            <div class="row" id="commonSection" style="display:none; margin-top:10px;">
                                                                                               
                                                <!-- ================= BASIC DETAILS ================= -->
												<h5 class="mb-3">Basic Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Name <span class="text-danger">*</span></label>
													<input type="text" name="asset_name" id="asset_name" value="{{ $asset->asset_name ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Category <span class="text-danger">*</span></label>
													<select name="asset_category" class="form-select">
														<option value="">Select</option>
														<option value="Land" {{ $asset->asset_category == 'Land' ? 'selected' : '' }}>Land</option>
														<option value="Building" {{ $asset->asset_category == 'Building' ? 'selected' : '' }}>Building</option>
														<option value="Plant Machinery" {{ $asset->asset_category == 'Plant Machinery' ? 'selected' : '' }}>Plant & Machinery</option>
														<option value="Furniture Fixtures" {{ $asset->asset_category == 'Furniture Fixtures' ? 'selected' : '' }}>Furniture & Fixtures</option>
														<option value="Office Equipment" {{ $asset->asset_category == 'Office Equipment' ? 'selected' : '' }}>Office Equipment</option>
														<option value="Computer IT Equipment" {{ $asset->asset_category == 'Computer IT Equipment' ? 'selected' : '' }}>Computer & IT Equipment</option>
														<option value="Vehicles" {{ $asset->asset_category == 'Vehicles' ? 'selected' : '' }}>Vehicles</option>
														<option value="Electrical Installations" {{ $asset->asset_category == 'Electrical Installations' ? 'selected' : '' }}>Electrical Installations</option>
														<option value="Leasehold Improvements" {{ $asset->asset_category == 'Leasehold Improvements' ? 'selected' : '' }}>Leasehold Improvements</option>
														<option value="Other" {{ $asset->asset_category == 'Other' ? 'selected' : '' }}>Other</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Code / ID <span class="text-danger">*</span></label>
													<input type="text" name="asset_code" value="{{ $asset->asset_code ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Location / Branch</label>
													<input type="text" name="location" value="{{ $asset->location ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Department</label>
													<select name="department" class="form-select">
														<option value="">Select</option>
														<option value="Administration" {{ $asset->department == 'Administration' ? 'selected' : '' }}>Administration</option>
														<option value="Production" {{ $asset->department == 'Production' ? 'selected' : '' }}>Production</option>
														<option value="Sales Marketing" {{ $asset->department == 'Sales Marketing' ? 'selected' : '' }}>Sales & Marketing</option>
														<option value="IT" {{ $asset->department == 'IT' ? 'selected' : '' }}>IT</option>
														<option value="HR" {{ $asset->department == 'HR' ? 'selected' : '' }}>HR</option>
														<option value="Finance" {{ $asset->department == 'Finance' ? 'selected' : '' }}>Finance</option>
														<option value="Other" {{ $asset->department == 'Other' ? 'selected' : '' }}>Other</option>
													</select>
												</div>


												<!-- ================= PURCHASE DETAILS ================= -->
												<h5 class="mt-4 mb-3">Purchase Details</h5>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Vendor Name</label>
													<select name="vendor_id" id="vendor_id" class="form-control">
														<option value="">Select Vendor</option>
														@foreach($vendors as $vendor)
															<option value="{{ $vendor->id }}"
																{{ (isset($asset->vendor_id) && $asset->vendor_id == $vendor->id) ? 'selected' : '' }}>
																{{ $vendor->vendor_name }}
															</option>
														@endforeach
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Invoice/Reference No</label>
													<input type="text" name="invoice_no" value="{{ $asset->invoice_no ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Invoice Date</label>
                                                    <input type="date" name="invoice_date" id="invoice_date" value="{{ $asset->invoice_date ?? '' }}" class="form-control">
												</div>
												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Amount Value<span class="text-danger">*</span></label>
													<input type="number" name="invoice_value" id="invoice_value" value="{{ $asset->invoice_value ?? '' }}" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Payment Status<span class="text-danger">*</span></label>
													<select name="pay_status" id="pay_status" class="form-select">
														<option value="">Select</option>
														<option value="Full" {{ $asset->pay_status == 'Full' ? 'selected' : '' }}>Full</option>
														<option value="Advance" {{ $asset->pay_status == 'Advance' ? 'selected' : '' }}>Advance</option>														
														<option value="Due" {{ $asset->pay_status == 'Due' ? 'selected' : '' }}>Due</option>														
													</select>
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Advance Amount<span class="text-danger">*</span></label>
													<input type="number" name="advance_amt" id="advance_amt" value="{{ $asset->advance_amt ?? '' }}" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Balance Payable Amount<span class="text-danger">*</span></label>
													<input type="number" name="payable_amt" id="payable_amt" value="{{ $asset->payable_amt ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Adjusted Now<span class="text-danger">*</span></label>
													<input type="number" name="adjusted_amt" id="adjusted_amt" value="{{ $asset->adjusted_amt ?? '' }}" class="form-control">
												</div>


												<!-- ================= CAPITALIZATION ================= -->
												<h5 class="mt-4 mb-3">Capitalization Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Capitalization Date</label>
													<input type="date" name="capitalization_date" value="{{ $asset->capitalization_date ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Put to Use Date</label>
													<input type="date" name="put_to_use_date" value="{{ $asset->put_to_use_date ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Status</label>
													<select name="asset_status" class="form-select">
														<option value="Active" {{ $asset->asset_status == 'Active' ? 'selected' : '' }}>Active</option>
														<option value="Under Construction" {{ $asset->asset_status == 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
													</select>
												</div>


												<!-- ================= DEPRECIATION ================= -->
												<h5 class="mt-4 mb-3">Depreciation Setup</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Start Date</label>
													<input type="date" name="depreciation_start_date" id="depreciation_start_date" value="{{ $asset->depreciation_start_date ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Method</label>
													<select name="depreciation_method" id="depreciation_method" class="form-select">
														<option value="">Select</option>
														<option value="SLM" {{ $asset->depreciation_method == 'SLM' ? 'selected' : '' }}>Straight Line Method (SLM)</option>
														<option value="WDV" {{ $asset->depreciation_method == 'WDV' ? 'selected' : '' }}>Written Down Value (WDV)</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Frequency</label>
													<select name="depreciation_frequency" id="depreciation_frequency" class="form-select">
														<option value="">Select</option>
														<option value="Yearly" {{ $asset->depreciation_frequency == 'Yearly' ? 'selected' : '' }}>Yearly</option>
														<option value="Half Year" {{ $asset->depreciation_frequency == 'Half Year' ? 'selected' : '' }}>Half Year</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3" id="usefulLifeDiv">
													<label class="form-label">Useful Life (Years)</label>
													<input type="number" name="useful_life_years" id="useful_life_years" value="{{ $asset->useful_life_years ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3" id="residualValueDiv">
													<label class="form-label">Residual Value</label>
													<input type="number" name="residual_value" id="residual_value"  value="{{ $asset->residual_value ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3" id="depreciationRateDiv" style="display:none;">
													<label class="form-label">Depreciation Rate (%)</label>
													<input type="number" name="depreciation_rate" id="depreciation_rate" value="{{ $asset->depreciation_rate ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Depreciation Value</label>
													<input type="number" name="depreciation_value" id="depreciation_value" value="{{ $asset->depreciation_value ?? '' }}" class="form-control">
												</div>
                                                
                                            </div>
                                            
                                            <div class="row" id="WorkinProgressSection" style="display:none; margin-top:10px;">
												<!-- ================= PROJECT DETAILS ================= -->
												<h5 class="mb-3">Project Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Project Name <span class="text-danger">*</span></label>
													<input type="text" name="project_name" value="{{ $asset->project_name ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Project Code / ID <span class="text-danger">*</span></label>
													<input type="text" name="project_code" value="{{ $asset->project_code ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Asset Type <span class="text-danger">*</span></label>
													<select name="cwip_asset_type" class="form-select">
														<option value="">Select</option>
														<option value="Land" {{ $asset->cwip_asset_type == 'Land' ? 'selected' : '' }}>Land</option>
														<option value="Building" {{ $asset->cwip_asset_type == 'Building' ? 'selected' : '' }}>Building</option>
														<option value="Plant Machinery" {{ $asset->cwip_asset_type == 'Plant Machinery' ? 'selected' : '' }}>Plant & Machinery</option>
														<option value="Furniture Fixtures" {{ $asset->cwip_asset_type == 'Furniture Fixtures' ? 'selected' : '' }}>Furniture & Fixtures</option>
														<option value="Office Equipment" {{ $asset->cwip_asset_type == 'Office Equipment' ? 'selected' : '' }}>Office Equipment</option>
														<option value="Computer IT Equipment" {{ $asset->cwip_asset_type == 'Computer IT Equipment' ? 'selected' : '' }}>Computer & IT Equipment</option>
														<option value="Vehicles" {{ $asset->cwip_asset_type == 'Vehicles' ? 'selected' : '' }}>Vehicles</option>
														<option value="Electrical Installations" {{ $asset->cwip_asset_type == 'Electrical Installations' ? 'selected' : '' }}>Electrical Installations</option>
														<option value="Leasehold Improvements" {{ $asset->cwip_asset_type == 'Leasehold Improvements' ? 'selected' : '' }}>Leasehold Improvements</option>
														<option value="Other" {{ $asset->cwip_asset_type == 'Other' ? 'selected' : '' }}>Other</option>
													</select>
												</div>


												<!-- ================= EXPENSE DETAILS ================= -->
												<h5 class="mt-4 mb-3">Expense Details</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Expense Type <span class="text-danger">*</span></label>
													<select name="expense_type" class="form-select">
														<option value="">Select</option>
														<option value="Material Cost" {{ $asset->expense_type == 'Material Cost' ? 'selected' : '' }}>Material Cost</option>
														<option value="Labour Cost" {{ $asset->expense_type == 'Labour Cost' ? 'selected' : '' }}>Labour Cost</option>
														<option value="Contractor Charges" {{ $asset->expense_type == 'Contractor Charges' ? 'selected' : '' }}>Contractor Charges</option>
														<option value="Professional Fees" {{ $asset->expense_type == 'Professional Fees' ? 'selected' : '' }}>Professional / Technical Fees</option>
														<option value="Installation Commissioning" {{ $asset->expense_type == 'Installation Commissioning' ? 'selected' : '' }}>Installation & Commissioning</option>
														<option value="Freight Transportation" {{ $asset->expense_type == 'Freight Transportation' ? 'selected' : '' }}>Transportation / Freight</option>
														<option value="Duties Taxes" {{ $asset->expense_type == 'Duties Taxes' ? 'selected' : '' }}>Duties & Taxes</option>
														<option value="Administrative Overheads" {{ $asset->expense_type == 'Administrative Overheads' ? 'selected' : '' }}>Administrative / Overheads</option>
														<option value="Repairs Maintenance" {{ $asset->expense_type == 'Repairs Maintenance' ? 'selected' : '' }}>Repairs & Maintenance</option>
													</select>
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Vendor Name</label>
													<select name="cwip_vendor_id" id="cwip_vendor_id" class="form-control">
														<option value="">Select Vendor</option>
														@foreach($vendors as $vendor)
															<option value="{{ $vendor->id }}"
																{{ (isset($asset->cwip_vendor_id) && $asset->cwip_vendor_id == $vendor->id) ? 'selected' : '' }}>
																{{ $vendor->vendor_name }}
															</option>
														@endforeach
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Invoice No</label>
													<input type="text" name="cwip_invoice_no" value="{{ $asset->cwip_invoice_no ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Amount <span class="text-danger">*</span></label>
													<input type="number" name="cwip_amount" id="cwip_amount" value="{{ $asset->cwip_amount ?? '' }}" class="form-control">
												</div>
												
												<div class="col-xl-4 mb-3">
													<label class="form-label">Payment Status<span class="text-danger">*</span></label>
													<select name="cwip_pay_status" id="cwip_pay_status" class="form-select">
														<option value="">Select</option>
														<option value="Full" {{ $asset->cwip_pay_status == 'Full' ? 'selected' : '' }}>Full</option>
														<option value="Advance" {{ $asset->cwip_pay_status == 'Advance' ? 'selected' : '' }}>Advance</option>
														<option value="Due" {{ $asset->cwip_pay_status == 'Due' ? 'selected' : '' }}>Due</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Advance Amount</label>
													<input type="number" name="cwip_advance_amt" id="cwip_advance_amt" value="{{ $asset->cwip_advance_amt }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Balance Payable</label>
													<input type="number" name="cwip_payable_amt" id="cwip_payable_amt" value="{{ $asset->cwip_payable_amt }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Adjusted Now</label>
													<input type="number" name="cwip_adjusted_amt" id="cwip_adjusted_amt" value="{{ $asset->cwip_adjusted_amt }}" class="form-control">
												</div>


												<!-- ================= PROGRESS TRACKING ================= -->
												<h5 class="mt-4 mb-3">Progress Tracking</h5>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Stage of Completion (%)</label>
													<input type="number" name="completion_percentage" value="{{ $asset->completion_percentage ?? '' }}" class="form-control">
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Capitalization Status</label>
													<select name="capitalization_status" class="form-select">
														<option value="">Select</option>
														<option value="Pending" {{ $asset->capitalization_status == 'Pending' ? 'selected' : '' }}>Pending</option>
														<option value="Completed" {{ $asset->capitalization_status == 'Completed' ? 'selected' : '' }}>Completed</option>
													</select>
												</div>

												<div class="col-xl-4 mb-3">
													<label class="form-label">Work Order / Contract Ref</label>
													<input type="text" name="work_order_ref"  value="{{ $asset->work_order_ref ?? '' }}" class="form-control">
												</div>
											</div>
											
											<!-- CASH IN HAND -->
											<div class="row" id="cashInHandSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="number" name="cash_amount" class="form-control"
														   value="{{ $asset->cash_amount ?? '' }}">
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/cash-management" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- BANK ACCOUNT -->
											<div class="row" id="bankAccountSection" style="display:none;">
												<div class="col-md-6 mb-3">
													<label>Bank Account</label>
													<select name="bank_id" id="bank_id" class="form-control">
														<option value="">Select</option>
														@foreach($banks as $bank)
															<option value="{{ $bank->id }}"
																{{ ($asset->bank_id ?? '') == $bank->id ? 'selected' : '' }}>
																{{ $bank->bank_name }}
															</option>
														@endforeach
													</select>
												</div>

												<div class="col-md-6 mb-3">
													<label>Balance</label>
													<input type="text" name="bank_balance" id="bank_balance"
														   class="form-control"
														   value="{{ $asset->bank_balance ?? '' }}" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/bank-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- TRADE RECEIVABLE -->
											<div class="row" id="tradeReceivableSection" style="display:none;">												
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="text" id="amount" name="amount" class="form-control"
														   value="{{ $asset->amount ?? '' }}" >
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/sale-invoices" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- ADVANCE TO VENDOR -->
											<div class="row" id="advanceVendorSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="text" name="amount_vendor" id="amount_vendor"
														   value="{{ $asset->amount_vendor ?? '' }}" class="form-control">
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/purchase-invoices" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- EMPLOYEE ADVANCE -->
											<div class="row" id="employeeAdvanceSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Advance Amount</label>
													<input type="text" name="employee_advance_amount" id="employee_advance_amount"
														   value="{{ $asset->employee_advance_amount ?? '' }}"
														   class="form-control">
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/expenses-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- PREPAID -->
											<div class="row" id="prepaidExpenseSection" style="display:none;">
												<div class="col-md-4 mb-3">
													<label>Amount</label>
													<input type="text" name="prepaid_amt" id="prepaid_amt"
														   value="{{ $asset->prepaid_amt ?? '' }}" class="form-control">
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/expenses-list" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- ITC -->
											<div class="row" id="itcSection" style="display:none;">										
												<div class="col-md-4 mb-3">
													<label>ITC Amount</label>
													<input type="text" name="itc_amt" id="itc_amt"
														   value="{{ $asset->itc_amt ?? '' }}" class="form-control">
												</div>	
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/gst-reports" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>

											<!-- TDS -->
											<div class="row" id="tdsReceivableSection" style="display:none;">												
												<div class="col-md-4 mb-3">
													<label>Gross</label>
													<input type="text" name="tds_gross_amount" id="tds_gross_amount"
														   value="{{ $asset->tds_gross_amount ?? '' }}" class="form-control">
												</div>
												<div class="col-md-4 mb-3 d-flex align-items-end">
													<a href="/tds-returns-filing" target="_blank" class="btn btn-primary">Details</a>
												</div>
											</div>
											
											<div class="row" id="inventorySection" style="display:none;">												
												<div class="col-md-4 mb-3">
													<label>Total Gross Profit</label>
													<input type="text" name="gross_profit" id="gross_profit"
														   value="{{ $asset->gross_profit ?? '' }}"
														   class="form-control" readonly>
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
																<input class="form-check-input" type="radio" name="tds_applicable" value="yes" id="tdsYes" {{ old('tds_applicable', $asset->tds_applicable ?? '') == 'yes' ? 'checked' : '' }}>
																<label class="form-check-label" for="tdsYes">Yes</label>
															</div>
														</div>
													</div>
													<div class="col-6">
														<div class="card shadow-sm border-0 p-3 m-2">
															<div class="form-check">
																<input class="form-check-input" type="radio" name="tds_applicable" value="no" id="tdsNo" {{ old('tds_applicable', $asset->tds_applicable ?? '') == 'no' ? 'checked' : '' }}>
																<label class="form-check-label" for="tdsNo">No</label>
															</div>
														</div>
													</div>
												</div>
											</div>
                                            <div class="tds-container col-md-12"  id="tdsContainer">
												<div class="row">
													<div class="col-md-6">
														<div id="tds_dropdown_universal">
															<label for="tds_percent" class="form-label">TDS Percentage</label>
															<select name="tds_percent" id="tds_percent" class="form-control">
																@foreach ($purposes_of_tds as $purpose)
																<option value="{{ $purpose->tds_rate . '-' . $purpose->id }}"  {{ ($purpose->id == $asset->tds_id) ? 'selected' : '' }}>
																	{{ $purpose->category }} ({{ $purpose->tds_rate }}%)
																</option>
																@endforeach
															</select>
														</div>
													</div>
													<div class="col-md-6">
														<label for="tds_amt">TDS Amount</label>
														<input type="text"  id="tds_amt"  value="{{$asset->tds_amt}}" class="form-control" readonly>
													</div>
												</div>
                                            </div>

                                            <div class="gst-container col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">GST Applicable <span class="text-danger">*</span></label>
                                                    <div class="row">
														@php
															$gstApplicable = strtolower(trim($asset->gst_applicable ?? 'no'));
														@endphp
                                                        <div class="col-6">
                                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="gst_applicable" value="yes" id="gstYes_ca" {{ ($gstApplicable === 'yes') ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="gstYes_ca">Yes</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="card shadow-sm border-0 p-3 m-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="gst_applicable" value="no" type="radio" id="gstNo_ca" {{ ($gstApplicable !== 'yes') ? 'checked' : '' }}>
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
                                                            <option value="intrastate" <?= ($asset->gst_trans == 'intrastate')?'selected':'' ?>>Intra State</option>
															<option value="interstate" <?= ($asset->gst_trans == 'interstate')?'selected':'' ?>>Inter State</option>
															<option value="union" <?= ($asset->gst_trans == 'union')?'selected':'' ?>>Union Territory</option>
                                                        </select>
                                                    </div>
													<div class="col-md-4">
                                                        <label for="gst_rate">GST Rate (%)</label>
                                                        <input type="number" name="gst_rate" id="gst_rate" value="{{$asset->gst_rate}}" class="form-control" min="0" step="0.01">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="gst_allocation">GST Allocation</label>
                                                        <input type="text" name="gst_allocation" id="gst_allocation" value="{{$asset->gst_allocation}}" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="gst_amt">Total GST Amount</label>
                                                        <input type="text" name="gst_amt" id="gst_amt" value="{{$asset->gst_amt}}" class="form-control" readonly>
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
														<br>
														@php
															$file1Path = storage_path('app/public/documentation_files/' . $asset->file1);
														@endphp

														@if(!empty($asset->file1) && file_exists($file1Path))
															<br>
															<a href="{{ asset('storage/documentation_files/' . $asset->file1) }}" download class="btn btn-success btn-sm">
																Download
															</a>
														@else
															<br>
															<span class="text-danger">Document Not Found</span>
														@endif
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
														<br>
														@php
                                                            $file2Path = storage_path('app/public/documentation_files/' . $asset->file2);
                                                        @endphp

                                                        @if(!empty($asset->file2) && file_exists($file2Path))
                                                            <br>
                                                            <a href="{{ asset('storage/documentation_files/' . $asset->file2) }}" download class="btn btn-success btn-sm">
                                                                Download
                                                            </a>
                                                        @else
                                                            <br>
                                                            <span class="text-danger">Document Not Found</span>
                                                        @endif
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
                                                    <input type="text" class="form-control" id="purchaseByAudit" name="purchaseByAudit" value="{{$asset->purchaseByAudit}}" placeholder="Enter Purchaser Name">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Purchase Date</label>
                                                    <input type="date" class="form-control" id="purchaseDateAudit" name="purchaseDateAudit" value="{{$asset->purchaseDateAudit}}" placeholder="Enter Purchase Date">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Approve By</label>
                                                    <input type="text" class="form-control" id="approveByAudit" name="approveByAudit" value="{{$asset->approveByAudit}}" placeholder="Enter Approver Name">
                                                </div>
                                                <div class="col-sm-3 mb-3">
                                                    <label class="form-label">Approve Date</label>
                                                    <input type="date" class="form-control" id="approveDateAudit" name="approveDateAudit" value="{{$asset->approveDateAudit}}" placeholder="Enter Approve Date">
                                                </div>
                                            </div>
                                            <div class="d-flex wizard justify-content-between mt-3">
                                                <div class="first">
                                                    <a href="javascript:void(0);" class="btn btn-secondary previous-btn d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-arrow-up-circle me-2"></i> Back To Previous
                                                    </a>
                                                </div>
                                                <div class="last">
                                                    {{-- <button type='submit' class="btn btn-primary d-flex align-items-center justify-content-center">
                                                        Save Changes <i class="ti ti-arrow-up-right-circle ms-2"></i>
                                                    </button> --}}
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
	var assetData = @json($asset);
	 
	function resetHiddenFields() {

		let assetType     = $('#assetType').val();
		let selectedValue = $('#currentAssetsType').val();

		const sectionMap = {
			'Cash in Hand': '#cashInHandSection',
			'Bank Accounts': '#bankAccountSection',
			'Trade Receivables': '#tradeReceivableSection',
			'Advance to Vendor': '#advanceVendorSection',
			'Employee Advance': '#employeeAdvanceSection',
			'Prepaid Expenses': '#prepaidExpenseSection',
			'Input GST Credit': '#itcSection',
			'TDS Receivable': '#tdsReceivableSection',
			'Inventories': '#inventorySection'
		};

		// ================= CURRENT ASSET LOGIC =================
		if (assetType === 'current') {

			// Clear non-selected current sections
			Object.keys(sectionMap).forEach(function(key) {

				if (key === selectedValue) return;

				let section = sectionMap[key];

				$(section).find('input[type="text"], input[type="date"], input[type="number"], textarea').val('');
				$(section).find('select').prop('selectedIndex', 0);
			});

			//  ALSO clear non-current sections
			$('#commonSection').find('input, select, textarea').val('').prop('selectedIndex', 0);
			$('#WorkinProgressSection').find('input, select, textarea').val('').prop('selectedIndex', 0);
		}

		// ================= NON-CURRENT ASSET LOGIC =================
		if (assetType === 'non-current') {

			// ❌ Clear ALL current sections
			Object.values(sectionMap).forEach(function(section) {

				$(section).find('input[type="text"], input[type="date"], input[type="number"], textarea').val('');
				$(section).find('select').prop('selectedIndex', 0);
			});
		}
	}

   
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
    

    function toggleAssetSections() {
		let assetType = document.getElementById("assetType").value;

		document.getElementById("currentAssetsSection").style.display = (assetType === "current") ? "block" : "none";
		document.getElementById("nonCurrentAssetsSection").style.display = (assetType === "non-current") ? "block" : "none";
		if (assetType == "current") {
			$('#commonSection').hide();
			$('#WorkinProgressSection').hide();
			$('#nextBtn1').text('Submit');
			// Disable other tabs
			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
			.addClass('disabled-tab')
			.css({
				'pointer-events': 'none',
				'opacity': '0.5'
			});
			
		} else {
			$('#commonSection').hide();
			$('#WorkinProgressSection').hide();
			$('#nextBtn1').html('Next <i class="ti ti-arrow-up-right-circle ms-2"></i>');
			// Enable tabs
			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
			.removeClass('disabled-tab')
			.css({
				'pointer-events': 'auto',
				'opacity': '1'
			});			
		}
	}
	
	//  Run on dropdown change
	document.getElementById("assetType").addEventListener("change", toggleAssetSections);

	//  Run on page load
	document.addEventListener("DOMContentLoaded", function () {
		toggleAssetSections();
	});
	
	document.getElementById("assetType").addEventListener("change", function () {

		let assetType = this.value;

		// Show/Hide sections
		$('#currentAssetsSection').toggle(assetType === "current");
		$('#nonCurrentAssetsSection').toggle(assetType === "non-current");

		// RESET DROPDOWNS
		if (assetType === "current") {
			$('#nonCurrentAssetsType').val('').trigger('change');
			$('#nextBtn1').text('Submit');
			// Disable other tabs
			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
			.addClass('disabled-tab')
			.css({
				'pointer-events': 'none',
				'opacity': '0.5'
			});
		} else if (assetType === "non-current") {
			$('#currentAssetsType').val('').trigger('change');
			$('#nextBtn1').html('Next <i class="ti ti-arrow-up-right-circle ms-2"></i>');
			// Enable tabs
			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
			.removeClass('disabled-tab')
			.css({
				'pointer-events': 'auto',
				'opacity': '1'
			});
		} else {
			$('#currentAssetsType').val('');
			$('#nonCurrentAssetsType').val('');
			$('#nextBtn1').html('Next <i class="ti ti-arrow-up-right-circle ms-2"></i>');
			// Enable tabs
			$('#gst-tds-tab, #documentation-tab, #audit-trail-tab')
			.removeClass('disabled-tab')
			.css({
				'pointer-events': 'auto',
				'opacity': '1'
			});
		}

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
					resetHiddenFields();
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

				// NORMAL FLOW
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
		resetHiddenFields();

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
		$("#loader").show();

        const fileInput = document.getElementById('fileUpload');
        if (fileInput && fileInput.files.length > 0) {
            formData.append('asset_image', fileInput.files[0]);
        }

        let formAction = $("#addAssetFrm").data("route");

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
	function loadCashInHand(selectedAmount = null) {
		let propId = $('select[name="propId"]').val();
		$.ajax({
			url: '/get-cash-in-hand',
			type: 'GET',
			data: { propId: propId },
			success: function (res) {

				// ✅ If edit value exists → use it
				if (selectedAmount !== null && selectedAmount !== '') {
					$('input[name="cash_amount"]').val(selectedAmount);
				} else {
					// Otherwise use API value
					$('input[name="cash_amount"]').val(res.cash_in_hand);
				}
			}
		});
	}
	$('select[name="propId"]').on('change', function () {
		loadCashInHand();
	});
	
	//Bank Accounts
	function loadBankAccounts(selectedBankId = null, selectedBalance = null) {

		$.get('/bank-accounts', function (data) {

			let html = '<option value="">All Banks</option>';
			let totalBalance = 0;

			data.forEach(row => {

				let bal = parseFloat(row.curr_bal) || 0;
				totalBalance += bal;

				let selected = (row.id == selectedBankId) ? 'selected' : '';

				html += `<option value="${row.id}" ${selected} data-balance="${bal}">
							${row.bank_name}
						 </option>`;
			});

			$('#bank_id').html(html);

			if (selectedBankId) {
				let balance = $('#bank_id option:selected').data('balance');
				$('#bank_balance').val(
					(selectedBalance ?? balance ?? 0).toFixed(2)
				);
			} else {
				$('#bank_balance').val(totalBalance.toFixed(2));
			}
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
	

    // Trigger on page load to set default visibility
	$(document).ready(function () {
		let selectedValue = $('#currentAssetsType option:selected').val();
		currentAssetsTypeFunc(selectedValue);
		$('#currentAssetsType').trigger('change');
		$('#currentAssetsType').on('change', function () {
			let selectedValue = $(this).val();
			currentAssetsTypeFunc(selectedValue);
		});
	});
	
	function currentAssetsTypeFunc(selectedValue)
	{
		//Hide all sections first
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

		if (selectedValue === 'Cash in Hand') {
			$('#cashInHandSection').show();
			loadCashInHand(assetData.cash_amount);

		} else if (selectedValue === 'Bank Accounts') {
			$('#bankAccountSection').show();
			loadBankAccounts(assetData.bank_id, assetData.bank_balance);

		} else if (selectedValue === 'Trade Receivables') {
			$('#tradeReceivableSection').show();
			loadTradeReceivableAmount();
		} else if (selectedValue === 'Advance to Vendor') {
			$('#advanceVendorSection').show();
			loadAdvanceVendorAmount();
		} else if (selectedValue === 'Employee Advance') {
			$('#employeeAdvanceSection').show();
			loadEmployeeAdvance();
		} else if (selectedValue === 'Prepaid Expenses') {
			$('#prepaidExpenseSection').show();
			calculatePrepaid();
		} else if (selectedValue === 'Input GST Credit') {
			$('#itcSection').show();
			loadVendorsITC();
		} else if (selectedValue === 'TDS Receivable') {
			$('#tdsReceivableSection').show();
			calculateMonthlyTDS();
		} else if (selectedValue === 'Inventories') {
			$('#inventorySection').show();
			calculateGrossProfit();
		}
	}
	
	
	var selectedValue2 = $('#nonCurrentAssetsType option:selected').val();
	nonCurrentAssetsTypeFunc(selectedValue2);
    $('#nonCurrentAssetsType').trigger('change');
    $('#nonCurrentAssetsType').on('change', function () {
        var selectedValue = $(this).val();
        nonCurrentAssetsTypeFunc(selectedValue);
    });
	
	function nonCurrentAssetsTypeFunc(selectedValue)
	{
        const showSectionValues = [
			'Property Plant Equipment',
			'Furniture Fixtures',
			'Computer IT Equipment',
			'Machinery',
			'Vehicles',
			'Intangible Assets',
			'Other Non-Current Assets'
		];

		if (showSectionValues.includes(selectedValue)) {
			$('#commonSection').show();
			$('#cashInHandSection').hide();
			$('#bankAccountSection').hide();
			$('#tradeReceivableSection').hide();
			$('#advanceVendorSection').hide();
			$('#employeeAdvanceSection').hide();
			$('#prepaidExpenseSection').hide();
			$('#itcSection').hide();
			$('#tdsReceivableSection').hide();
			$('#inventorySection').hide();
		} else {
			$('#commonSection').hide();
		}
    }
	
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
	
	$(document).ready(function () {
		let value = $('#nonCurrentAssetsType').val();

		if (value === 'Capital Work in Progress') {
			$('#WorkinProgressSection').show();
			$('#commonSection').hide();
		} else if (value) {
			$('#commonSection').show();
			$('#WorkinProgressSection').hide();
		}
	});
	
	//Calculate adjusted amount
	$(document).ready(function () {

		function handlePaymentLogic(prefix, amountField) {

			let isInitialLoad = true;

			// correct ID generator
			function getId(name) {
				return prefix ? `#${prefix}_${name}` : `#${name}`;
			}

			function resetFields() {
				$(`${getId('advance_amt')}, ${getId('payable_amt')}, ${getId('adjusted_amt')}`)
					.val('')
					.prop({ readonly: true, required: false });

				$(getId('advance_amt')).closest('.col-xl-4').hide();
				$(getId('payable_amt')).closest('.col-xl-4').hide();
				$(getId('adjusted_amt')).closest('.col-xl-4').hide();
			}

			function calculate() {

				let amount   = parseFloat($(amountField).val()) || 0;
				let advance  = parseFloat($(getId('advance_amt')).val()) || 0;
				let adjusted = parseFloat($(getId('adjusted_amt')).val()) || 0;
				let status   = $(getId('pay_status')).val();

				// ================= FULL =================
				if (status === 'Full') {

					$(getId('adjusted_amt')).closest('.col-xl-4').show();
					$(getId('advance_amt')).closest('.col-xl-4').hide();
					$(getId('payable_amt')).closest('.col-xl-4').hide();

					$(getId('adjusted_amt'))
						.val(amount)
						.prop({ readonly: true, required: true });

					$(getId('advance_amt')).val(0);
					$(getId('payable_amt')).val(0);
				}

				// ================= ADVANCE =================
				else if (status === 'Advance') {

					$(getId('advance_amt')).closest('.col-xl-4').show();
					$(getId('payable_amt')).closest('.col-xl-4').show();
					$(getId('adjusted_amt')).closest('.col-xl-4').show();

					$(getId('advance_amt')).prop({ readonly: false, required: true });
					$(getId('adjusted_amt')).prop({ readonly: false, required: true });
					$(getId('payable_amt')).prop({ readonly: true, required: true });

					if (advance > amount) {
						advance = amount;
						$(getId('advance_amt')).val(amount);
						alert('Advance cannot exceed total amount');
					}

					if (adjusted > advance) {
						adjusted = advance;
						$(getId('adjusted_amt')).val(advance);
						alert('Adjusted cannot exceed Advance Amount');
					}

					let payable = amount - advance;

					$(getId('payable_amt'))
						.val(payable.toFixed(2))
						.prop('readonly', true);
				}

				// ================= DEFAULT =================
				else {
					resetFields();
				}

				isInitialLoad = false;
			}

			// ================= EVENTS =================
			$(getId('pay_status')).on('change', function () {
				resetFields();
				calculate();
			});

			$(amountField).on('input', calculate);

			$(getId('advance_amt')).on('input', function () {
				$(getId('adjusted_amt')).val($(this).val());
				calculate();
			});

			$(getId('adjusted_amt')).on('input', calculate);

			// ================= INIT (EDIT MODE FIX) =================
			if (!$(getId('pay_status')).val()) {
				resetFields();
			}

			calculate();
		}

		// NORMAL ASSET
		handlePaymentLogic('', '#invoice_value');

		// CWIP
		handlePaymentLogic('cwip', '#cwip_amount');

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
	// $(document).ready(function () {

	// 	function calculateDepreciation() {

	// 		let cost      = parseFloat($('#invoice_value').val()) || 0;
	// 		let residual  = parseFloat($('#residual_value').val()) || 0;
	// 		let life      = parseFloat($('#useful_life_years').val()) || 0;
	// 		let rate      = parseFloat($('#depreciation_rate').val()) || 0;
	// 		let frequency = $('#depreciation_frequency').val();

	// 		if (cost <= 0) {
	// 			$('#depreciation_value').val('');
	// 			return;
	// 		}

	// 		if ($('#depreciation_method').val() === 'WDV') {
	// 			if (rate <= 0) {
	// 				$('#depreciation_value').val('');
	// 				return;
	// 			}

	// 			if (frequency === 'Half Year') {
	// 				$('#useful_life_years').prop('disabled', true);
	// 				$('#useful_life_years').val('');
	// 				$('#usefulLifeDiv').hide();

	// 				let invoiceDate = $('#invoice_date').val();
	// 				let depDate     = $('#depreciation_start_date').val();

	// 				if (invoiceDate && depDate) {
	// 					let invDate = new Date(invoiceDate);
	// 					let startDate = new Date(depDate);
	// 					let diffDays = Math.abs(startDate - invDate) / (1000 * 60 * 60 * 24);

	// 					if (diffDays <= 180) {
	// 						rate = rate / 2;
	// 					}
	// 				}

	// 				let closingWDV = cost - ((cost * rate) / 100);
	// 				$('#depreciation_value').val(closingWDV.toFixed(2));
	// 			} else {
	// 				$('#useful_life_years').prop('disabled', false);
	// 				$('#usefulLifeDiv').hide();

	// 				// if (life <= 0) {
	// 				// 	$('#depreciation_value').val('');
	// 				// 	return;
	// 				// }

	// 				// let closingWDV = cost;
	// 				// for (let year = 1; year <= life; year++) {
	// 				// 	let depreciation = (closingWDV * rate) / 100;
	// 				// 	closingWDV = closingWDV - depreciation;
	// 				// }
	// 				let closingWDV = cost - ((cost * rate) / 100);
					

	// 				$('#depreciation_value').val(closingWDV.toFixed(2));
	// 			}
	// 		} else {
	// 			if (life <= 0) {
	// 				$('#depreciation_value').val('');
	// 				return;
	// 			}

	// 			if (residual > cost) {
	// 				alert('Residual value cannot be greater than Asset Amount Value');
	// 				$('#residual_value').val(cost);
	// 				residual = cost;
	// 			}

	// 			let depreciation = (cost - residual) / life;
	// 			$('#depreciation_value').val(depreciation.toFixed(2));
	// 		}
	// 	}

	// 	function toggleDepreciationFields() {
	// 		let method = $('#depreciation_method').val();
	// 		let frequency = $('#depreciation_frequency').val();

	// 		if (method === 'WDV') {
	// 			$('#depreciationRateDiv').show();
	// 			$('#residualValueDiv').hide();
	// 			$('#residual_value').val('');
	// 			$('#usefulLifeDiv').hide();

	// 			if ($('#depreciation_frequency option[value="Half Year"]').length === 0) {
	// 				$('#depreciation_frequency').append('<option value="Half Year">Half Year</option>');
	// 			}

	// 			// if (frequency === 'Half Year') {
	// 			// 	$('#usefulLifeDiv').hide();
	// 			// } else {
	// 			// 	$('#usefulLifeDiv').show();
	// 			// }

	// 			calculateDepreciation();
	// 		} else if (method === 'SLM') {
	// 			$('#depreciationRateDiv').hide();
	// 			$('#residualValueDiv').show();
	// 			$('#useful_life_years').prop('disabled', false);
	// 			$('#usefulLifeDiv').show();

	// 			$('#depreciation_frequency option[value="Half Year"]').remove();
	// 			if ($('#depreciation_frequency').val() === 'Half Year') {
	// 				$('#depreciation_frequency').val('');
	// 			}

	// 			calculateDepreciation();
	// 		} else {
	// 			$('#depreciationRateDiv').hide();
	// 			$('#residualValueDiv').show();
	// 			$('#usefulLifeDiv').show();
	// 			$('#depreciation_value').val('');
	// 		}
	// 	}

	// 	$('#depreciation_method').on('change', function () {
	// 		toggleDepreciationFields();
	// 	});

	// 	$('#invoice_value, #residual_value, #useful_life_years').on('input', function () {
	// 		if ($('#depreciation_method').val() === 'SLM') {
	// 			calculateDepreciation();
	// 		}
	// 	});

	// 	$('#invoice_value, #depreciation_rate, #useful_life_years').on('input', function () {
	// 		if ($('#depreciation_method').val() === 'WDV') {
	// 			calculateDepreciation();
	// 		}
	// 	});

	// 	$('#depreciation_frequency, #invoice_date, #depreciation_start_date').on('change', function () {
	// 		if ($('#depreciation_method').val() === 'WDV') {
	// 			calculateDepreciation();
	// 		}
	// 	});

	// 	// =========================
	// 	// PAGE LOAD
	// 	// =========================
	// 	toggleDepreciationFields();
	// });

	$(document).ready(function () {

    // =========================
    // SLM CALCULATION
    // =========================
    function calculateDepreciation() {

        let cost = parseFloat($('#invoice_value').val()) || 0;
        let residual = parseFloat($('#residual_value').val()) || 0;
        let life = parseFloat($('#useful_life_years').val()) || 0;

        if (cost > 0 && life > 0) {

            if (residual > cost) {
                alert('Residual value cannot be greater than Asset Amount Value');
                $('#residual_value').val(cost);
                residual = cost;
            }

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

        let cost = parseFloat($('#invoice_value').val()) || 0;
        let rate = parseFloat($('#depreciation_rate').val()) || 0;
        let frequency = $('#depreciation_frequency').val();

        if (cost <= 0 || rate <= 0) {
            $('#depreciation_value').val('');
            return;
        }

        // Half Year
        if (frequency === 'Half Year') {

            let invoiceDate = $('#invoice_date').val();
            let depDate = $('#depreciation_start_date').val();

            if (invoiceDate && depDate) {

                let invDate = new Date(invoiceDate);
                let startDate = new Date(depDate);

                let diffDays = Math.abs(startDate - invDate) / (1000 * 60 * 60 * 24);

                if (diffDays <= 180) {
                    rate = rate / 2;
                }
            }
        }

        let depreciation = (cost * rate) / 100;
        let closingWDV = cost - depreciation;

        $('#depreciation_value').val(closingWDV.toFixed(2));
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
            $('#useful_life_years').val('');

            if ($('#depreciation_frequency option[value="Half Year"]').length === 0) {
                $('#depreciation_frequency').append(
                    '<option value="Half Year">Half Year</option>'
                );
            }

            calculateWDV();
        }

        else if (method === 'SLM') {

            $('#depreciationRateDiv').hide();
            $('#residualValueDiv').show();
            $('#usefulLifeDiv').show();

            $('#depreciation_frequency option[value="Half Year"]').remove();

            if ($('#depreciation_frequency').val() === 'Half Year') {
                $('#depreciation_frequency').val('');
            }

            calculateDepreciation();
        }

        else {

            $('#depreciationRateDiv').hide();
            $('#residualValueDiv').show();
            $('#usefulLifeDiv').show();

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
    $('#invoice_value, #residual_value, #useful_life_years')
        .on('input', function () {

            if ($('#depreciation_method').val() === 'SLM') {
                calculateDepreciation();
            }
        });

    // =========================
    // WDV EVENTS
    // =========================
    $('#invoice_value, #depreciation_rate')
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

</script>
@endsection
