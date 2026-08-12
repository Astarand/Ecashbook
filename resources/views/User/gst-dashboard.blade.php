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
                        <li class="breadcrumb-item"><a href="#">GST Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">GST Dashboard</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title d-flex align-items-center justify-content-between">
                        <h2 class="mb-0 fw-bold">GST Dashboard</h2>
                        <a href="{{ route('User.GSTReports') }}" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold shadow-sm" style="background-color: var(--bs-primary); color: #fff;">
                            <i class="ph-duotone ph-file-text fs-4"></i> Detailed GST Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Indicative Notice Banner -->
    <div class="alert alert-primary border-0 rounded-4 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, rgba(0, 140, 173, 0.08) 0%, rgba(0, 140, 173, 0.02) 100%); border-left: 5px solid var(--bs-primary) !important;">
        <div class="d-flex align-items-start gap-3">
            <div class="badge rounded-circle p-2 bg-light-primary text-primary fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                <i class="ph-duotone ph-info"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">Indicative Report Notice</h6>
                <p class="mb-0 text-muted" style="font-size: 0.875rem; line-height: 1.5;">
                    The summary reports is generated from your module data and is indicative only. Please refer to the GST Reports for final and accurate figures
                </p>
            </div>
        </div>
    </div>

    <!-- Company Profile & Filter Header Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-5 col-md-6 border-end-md">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avtar avtar-lg rounded-circle bg-light-primary text-primary fw-bold fs-3">
                            <i class="ph-duotone ph-buildings"></i>
                        </div>
                        <div>
                            <span class="text-uppercase text-muted fw-bold small tracking-wider" style="font-size: 0.75rem;">Company Name (In case Proprietorship)</span>
                            <h4 class="mb-1 fw-bold text-dark">{{ $company->comp_name ?? '' }}</h4>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light-secondary text-secondary font-monospace"><i class="ph-duotone ph-identification-card me-1"></i> GSTIN: {{ $company->gst_no ?? '' }}</span>
                                <span class="badge bg-light-success text-success"><i class="ph-duotone ph-check-circle me-1"></i> Active Taxpayer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="col-lg-7 col-md-6">
                    <div class="row g-2 justify-content-lg-end align-items-center">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-muted mb-1"><i class="ph-duotone ph-calendar-blank me-1"></i> Financial Year</label>
                            <select id="fySelect" class="form-select form-select-sm rounded-3 fw-bold text-dark border-light-subtle" onchange="updatePeriodOptions()">
                                <option value="2026-2027">FY 2026 - 2027</option>
                                <option value="2025-2026" selected>FY 2025 - 2026</option>
                                <option value="2024-2025">FY 2024 - 2025</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-muted mb-1"><i class="ph-duotone ph-sliders-horizontal me-1"></i> Period Frequency</label>
                            <select id="periodTypeSelect" class="form-select form-select-sm rounded-3 fw-bold text-dark border-light-subtle" onchange="updatePeriodOptions()">
                                <option value="monthly" selected>Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="halfyearly">Half-Yearly</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-muted mb-1"><i class="ph-duotone ph-clock me-1"></i> Select Period</label>
                            <select id="periodValueSelect" class="form-select form-select-sm rounded-3 fw-bold text-dark border-light-subtle" onchange="filterGstTable()">
                                
                            </select>
                        </div>
                    </div>
                </div>-->
				<div class="col-lg-7 col-md-6">
					<div class="row g-3 mb-4">

						{{-- Financial Year --}}
						<div class="col-md-4">
							<label class="form-label fw-semibold">Financial Year</label>
							<select class="form-select" id="financial_year">
								<option value="">Select Financial Year</option>
								@php
									$currentYear = now()->year;
								@endphp

								@for($year = $currentYear; $year >= $currentYear - 5; $year--)
									<option value="{{ $year }}-{{ $year + 1 }}">
										{{ $year }}-{{ $year + 1 }}
									</option>
								@endfor
							</select>
						</div>

						{{-- Period Frequency --}}
						<div class="col-md-4">
							<label class="form-label fw-semibold">Period Frequency</label>
							<select class="form-select" id="period_frequency">
								<option value="">Select Frequency</option>
								<option value="Monthly">Monthly</option>
								<option value="Quarterly">Quarterly</option>
								<option value="Half-Yearly">Half-Yearly</option>
								<option value="Yearly">Yearly</option>
							</select>
						</div>

						{{-- Select Period --}}
						<div class="col-md-4">
							<label class="form-label fw-semibold">Select Period</label>
							<select class="form-select" id="select_period">
								<option value="">Select Period</option>
							</select>
						</div>
					</div>
				</div>
				
            </div>
        </div>
    </div>

    <!-- Summary Metrics Section -->
    <div class="row g-3 mb-4">
        <!-- Total Sales -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.2s ease;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-bold small uppercase-label">Total Sales</span>
                        <div class="avtar avtar-s rounded-circle bg-light-primary text-primary">
                            <i class="ph-duotone ph-trend-up fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-dark" id="totalSales">₹0.00</h3>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <span class="badge bg-light-success text-success font-11"><i class="ph-duotone ph-arrow-up-right me-0.5"></i> Outward</span>
                        <span>Gross taxable sales turnover</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Purchase -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.2s ease;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-bold small uppercase-label">Total Purchase</span>
                        <div class="avtar avtar-s rounded-circle bg-light-info text-info">
                            <i class="ph-duotone ph-shopping-cart-simple fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-dark" id="totalPurchase">₹0.00</h3>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <span class="badge bg-light-info text-info font-11"><i class="ph-duotone ph-arrow-down-left me-0.5"></i> Inward</span>
                        <span>Inward goods & services</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Output GST -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.2s ease;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-bold small uppercase-label">Output GST</span>
                        <div class="avtar avtar-s rounded-circle bg-light-danger text-danger">
                            <i class="ph-duotone ph-receipt fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-danger" id="outputGst">₹0.00</h3>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <span class="badge bg-light-danger text-danger font-11">Tax Liability</span>
                        <span>GST collected on sales</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input GST -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.2s ease;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-bold small uppercase-label">Input GST</span>
                        <div class="avtar avtar-s rounded-circle bg-light-success text-success">
                            <i class="ph-duotone ph-percent fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-success" id="inputGst">₹0.00</h3>
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <span class="badge bg-light-success text-success font-11">ITC Claim</span>
                        <span>Eligible Input Tax Credit</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net GST Liability -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-bold small uppercase-label">Net GST Liability</span>
                        <div class="avtar avtar-s rounded-circle bg-light-warning text-warning">
                            <i class="ph-duotone ph-scales fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-dark" id="netGstLiability">₹0.00</h3>
                    <span class="small text-muted">(Output GST - Input GST Credit)</span>
                </div>
            </div>
        </div>

        <!-- GST Paid -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted fw-bold small uppercase-label">GST Paid</span>
                        <div class="avtar avtar-s rounded-circle bg-light-primary text-primary">
                            <i class="ph-duotone ph-check-square-offset fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-primary" id="gstPaid">₹0.00</h3>
                    <span class="small text-muted">Challan / E-cash ledger payments</span>
                </div>
            </div>
        </div>

        <!-- GST Payable / Refund -->
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, rgba(0, 140, 173, 0.05) 0%, rgba(0, 140, 173, 0.12) 100%); border: 1px solid rgba(0, 140, 173, 0.2) !important;">
                <div class="card-body p-3.5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-primary fw-bold small uppercase-label">GST Payable / Refund</span>
                        <div class="avtar avtar-s rounded-circle bg-primary text-white">
                            <i class="ph-duotone ph-currency-inr fs-5"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-primary" id="gstPayableRefund">₹0.00</h3>
                    <span class="small text-muted font-12 fw-semibold" id="gstPayableText">
                        <span class="text-danger"><i class="ph-duotone ph-arrow-circle-up me-1"></i> No GST Payable / Refund</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- GST Transactions Listing Table Section -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="avtar avtar-s bg-light-primary text-primary rounded-circle">
                    <i class="ph-duotone ph-list-bullets fs-5"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Suggested - GST Module Listing</h5>
                    <small class="text-muted">Module transactions breakdown & status overview</small>
                </div>
            </div>
            
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Module Filter -->
                <select id="moduleFilter" class="form-select form-select-sm rounded-3 border-light-subtle fw-semibold" style="width: 160px;" onchange="filterGstTable()">
                    <option value="">All Modules</option>
                    <option value="Sales">Sales</option>
                    <option value="Credit Note">Credit Note</option>
                    <option value="Purchase">Purchase</option>
                    <option value="Debit Note">Debit Note</option>
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                    <option value="Asset">Asset</option>
                    <option value="RCM">RCM</option>
                </select>

                <!-- Status Filter -->
                <select id="statusFilter" class="form-select form-select-sm rounded-3 border-light-subtle fw-semibold" style="width: 160px;" onchange="filterGstTable()">
                    <option value="">All Statuses</option>
                    <option value="Full">Full</option>
                    <option value="Advance">Advance</option>
                    <option value="Due">Due</option>
                </select>

                <!-- Search Input -->
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-light border-light-subtle text-muted"><i class="ph-duotone ph-magnifying-glass"></i></span>
                    <input type="text" id="gstTableSearch" class="form-control border-light-subtle" placeholder="Search Invoice / Party..." onkeyup="filterGstTable()">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table tbl-product m-0 custom-list-table align-middle table-hover table-sm" id="gstListingTable">
                    <thead class="bg-light">
                        <tr class="text-secondary small fw-bold uppercase-label">
                            <th class="ps-4 py-3">Module</th>
                            <th class="py-3">Invoice Date</th>
                            <th class="py-3">Invoice No (Show in Click)</th>
                            <th class="py-3">Customer / Vendor</th>
                            <th class="py-3">GSTIN</th>
                            <th class="py-3 text-end">Taxable Amount</th>
                            <th class="py-3 text-end">GST</th>
                            <th class="py-3 text-end">Invoice Total</th>
                            <th class="pe-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
					<tbody id="gstListingBody">

						<tr>

							<td colspan="9"
								class="text-center text-muted py-5">

								Please select Financial Year and Period.

							</td>

						</tr>

					</tbody>
                    {{--<tbody>
                        <!-- 1. Sales -->
                        <tr class="gst-row" data-module="Sales" data-status="Active">
                            <td class="ps-4">
                                <span class="badge bg-light-primary text-primary fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-trend-up me-1"></i> Sales</span>
                            </td>
                            <td class="fw-semibold text-dark">20-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="INV-2026-001" data-date="20-07-2026" data-module="Sales" data-party="Acme Technologies Pvt Ltd" data-gstin="19AAACA1234A1Z1" data-hsn="998313" data-taxable="1,50,000.00" data-cgst="13,500.00" data-sgst="13,500.00" data-igst="0.00" data-gst="27,000.00" data-total="1,77,000.00" data-status="Active">
                                    INV-2026-001
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Acme Technologies Pvt Ltd</td>
                            <td><span class="font-monospace text-muted small">19AAACA1234A1Z1</span></td>
                            <td class="text-end fw-semibold">₹1,50,000.00</td>
                            <td class="text-end fw-bold text-danger">₹27,000.00</td>
                            <td class="text-end fw-bold text-dark">₹1,77,000.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-success text-success fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-check-circle me-1"></i> Active
                                </span>
                            </td>
                        </tr>

                        <!-- 2. Credit Note -->
                        <tr class="gst-row" data-module="Credit Note" data-status="Advance">
                            <td class="ps-4">
                                <span class="badge bg-light-danger text-danger fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-file-arrow-down me-1"></i> Credit Note</span>
                            </td>
                            <td class="fw-semibold text-dark">18-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="CN-2026-004" data-date="18-07-2026" data-module="Credit Note" data-party="Zenith Solutions Ltd" data-gstin="19AAACZ9012C1Z3" data-hsn="998314" data-taxable="20,000.00" data-cgst="1,800.00" data-sgst="1,800.00" data-igst="0.00" data-gst="3,600.00" data-total="23,600.00" data-status="Advance">
                                    CN-2026-004
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Zenith Solutions Ltd</td>
                            <td><span class="font-monospace text-muted small">19AAACZ9012C1Z3</span></td>
                            <td class="text-end fw-semibold">₹20,000.00</td>
                            <td class="text-end fw-bold text-danger">₹3,600.00</td>
                            <td class="text-end fw-bold text-dark">₹23,600.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-info text-info fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-clock me-1"></i> Advance
                                </span>
                            </td>
                        </tr>

                        <!-- 3. Purchase -->
                        <tr class="gst-row" data-module="Purchase" data-status="Active">
                            <td class="ps-4">
                                <span class="badge bg-light-info text-info fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-shopping-cart me-1"></i> Purchase</span>
                            </td>
                            <td class="fw-semibold text-dark">15-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="PUR-89421" data-date="15-07-2026" data-module="Purchase" data-party="Infotech Global Suppliers" data-gstin="27AAACB5678B1Z2" data-hsn="847130" data-taxable="95,000.00" data-cgst="0.00" data-sgst="0.00" data-igst="17,100.00" data-gst="17,100.00" data-total="1,12,100.00" data-status="Active">
                                    PUR-89421
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Infotech Global Suppliers</td>
                            <td><span class="font-monospace text-muted small">27AAACB5678B1Z2</span></td>
                            <td class="text-end fw-semibold">₹95,000.00</td>
                            <td class="text-end fw-bold text-danger">₹17,100.00</td>
                            <td class="text-end fw-bold text-dark">₹1,12,100.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-success text-success fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-check-circle me-1"></i> Active
                                </span>
                            </td>
                        </tr>

                        <!-- 4. Debit Note -->
                        <tr class="gst-row" data-module="Debit Note" data-status="Active">
                            <td class="ps-4">
                                <span class="badge bg-light-warning text-warning fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-file-arrow-up me-1"></i> Debit Note</span>
                            </td>
                            <td class="fw-semibold text-dark">12-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="DN-2026-002" data-date="12-07-2026" data-module="Debit Note" data-party="Infotech Global Suppliers" data-gstin="27AAACB5678B1Z2" data-hsn="847130" data-taxable="12,000.00" data-cgst="0.00" data-sgst="0.00" data-igst="2,160.00" data-gst="2,160.00" data-total="14,160.00" data-status="Active">
                                    DN-2026-002
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Infotech Global Suppliers</td>
                            <td><span class="font-monospace text-muted small">27AAACB5678B1Z2</span></td>
                            <td class="text-end fw-semibold">₹12,000.00</td>
                            <td class="text-end fw-bold text-danger">₹2,160.00</td>
                            <td class="text-end fw-bold text-dark">₹14,160.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-success text-success fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-check-circle me-1"></i> Active
                                </span>
                            </td>
                        </tr>

                        <!-- 5. Expense -->
                        <tr class="gst-row" data-module="Expense" data-status="Due">
                            <td class="ps-4">
                                <span class="badge bg-light-secondary text-dark fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-receipt me-1"></i> Expense</span>
                            </td>
                            <td class="fw-semibold text-dark">10-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="EXP-2026-88" data-date="10-07-2026" data-module="Expense" data-party="Skyline Commercial Properties" data-gstin="19AAACS3456D1Z4" data-hsn="997212" data-taxable="50,000.00" data-cgst="4,500.00" data-sgst="4,500.00" data-igst="0.00" data-gst="9,000.00" data-total="59,000.00" data-status="Due">
                                    EXP-2026-88
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Skyline Commercial Properties</td>
                            <td><span class="font-monospace text-muted small">19AAACS3456D1Z4</span></td>
                            <td class="text-end fw-semibold">₹50,000.00</td>
                            <td class="text-end fw-bold text-danger">₹9,000.00</td>
                            <td class="text-end fw-bold text-dark">₹59,000.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-warning text-warning fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-warning me-1"></i> Due
                                </span>
                            </td>
                        </tr>

                        <!-- 6. Asset -->
                        <tr class="gst-row" data-module="Asset" data-status="Active">
                            <td class="ps-4">
                                <span class="badge bg-light-success text-success fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-desktop me-1"></i> Asset</span>
                            </td>
                            <td class="fw-semibold text-dark">08-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="AST-2026-015" data-date="08-07-2026" data-module="Asset" data-party="Dell India Electronics" data-gstin="29AAACD1234E1Z6" data-hsn="847141" data-taxable="1,20,000.00" data-cgst="0.00" data-sgst="0.00" data-igst="21,600.00" data-gst="21,600.00" data-total="1,41,600.00" data-status="Active">
                                    AST-2026-015
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Dell India Electronics</td>
                            <td><span class="font-monospace text-muted small">29AAACD1234E1Z6</span></td>
                            <td class="text-end fw-semibold">₹1,20,000.00</td>
                            <td class="text-end fw-bold text-danger">₹21,600.00</td>
                            <td class="text-end fw-bold text-dark">₹1,41,600.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-success text-success fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-check-circle me-1"></i> Active
                                </span>
                            </td>
                        </tr>

                        <!-- 7. RCM -->
                        <tr class="gst-row" data-module="RCM" data-status="Due">
                            <td class="ps-4">
                                <span class="badge bg-light-dark text-dark fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-swap me-1"></i> RCM</span>
                            </td>
                            <td class="fw-semibold text-dark">05-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="RCM-2026-003" data-date="05-07-2026" data-module="RCM" data-party="Legal Advisory & Associates" data-gstin="19AAACL7890F1Z7" data-hsn="998211" data-taxable="35,000.00" data-cgst="3,150.00" data-sgst="3,150.00" data-igst="0.00" data-gst="6,300.00" data-total="41,300.00" data-status="Due">
                                    RCM-2026-003
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Legal Advisory & Associates</td>
                            <td><span class="font-monospace text-muted small">19AAACL7890F1Z7</span></td>
                            <td class="text-end fw-semibold">₹35,000.00</td>
                            <td class="text-end fw-bold text-danger">₹6,300.00</td>
                            <td class="text-end fw-bold text-dark">₹41,300.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-warning text-warning fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-warning me-1"></i> Due
                                </span>
                            </td>
                        </tr>

                        <!-- 8. Sales Advance -->
                        <tr class="gst-row" data-module="Sales" data-status="Advance">
                            <td class="ps-4">
                                <span class="badge bg-light-primary text-primary fw-bold px-2.5 py-1.5 rounded-pill"><i class="ph-duotone ph-trend-up me-1"></i> Sales</span>
                            </td>
                            <td class="fw-semibold text-dark">02-07-2026</td>
                            <td>
                                <a href="javascript:void(0);" 
                                   class="fw-bold text-primary text-decoration-underline show-invoice-modal" 
                                   data-no="INV-2026-002" data-date="02-07-2026" data-module="Sales" data-party="Apex Retail Ventures" data-gstin="19AAACA9876G1Z8" data-hsn="998315" data-taxable="85,000.00" data-cgst="7,650.00" data-sgst="7,650.00" data-igst="0.00" data-gst="15,300.00" data-total="1,00,300.00" data-status="Advance">
                                    INV-2026-002
                                </a>
                            </td>
                            <td class="fw-bold text-dark">Apex Retail Ventures</td>
                            <td><span class="font-monospace text-muted small">19AAACA9876G1Z8</span></td>
                            <td class="text-end fw-semibold">₹85,000.00</td>
                            <td class="text-end fw-bold text-danger">₹15,300.00</td>
                            <td class="text-end fw-bold text-dark">₹1,00,300.00</td>
                            <td class="pe-4 text-center">
                                <span class="badge bg-light-info text-info fw-bold px-3 py-1.5 rounded-pill">
                                    <i class="ph-duotone ph-clock me-1"></i> Advance
                                </span>
                            </td>
                        </tr>
                    </tbody> --}}
                </table>
            </div>
        </div>
        <!--<div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="text-muted small" id="recordCountInfo">Showing 0 entries</span>
            <small class="text-muted">GST Module Data • Indicative Report Summary</small>
        </div>-->
		<div class="card-footer bg-white border-top py-3 px-4 
            d-flex justify-content-between align-items-center">

			<span class="text-muted small" id="recordCountInfo">
				Showing 0 entries
			</span>

			<div class="d-flex align-items-center gap-2">

				<label class="text-muted small mb-0">
					Show
				</label>

				<select id="pageLength" class="form-select form-select-sm" style="width: 80px;">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="100">100</option>
					<option value="all">All</option>
				</select>

				<button type="button"
						class="btn btn-sm btn-outline-secondary"
						id="prevPage">
					Previous
				</button>

				<span class="small text-muted" id="pageInfo">
					Page 1
				</span>

				<button type="button"
						class="btn btn-sm btn-outline-secondary"
						id="nextPage">
					Next
				</button>
			</div>
		</div>
		
    </div>
</div>

<!-- Invoice Details Modal -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow">
            {{-- HEADER --}}
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="avtar avtar-s bg-light-primary text-primary rounded-circle">
                        <i class="ph-duotone ph-file-text fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0"
                            id="invoiceModalLabel">
                            Invoice Breakdown Details
                        </h5>

                        <small class="text-muted"
                               id="invoiceDetailModule">
                            Module Transaction View
                        </small>
                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            {{-- BODY --}}
            <div class="modal-body p-4">

                {{-- INVOICE INFORMATION --}}
                <div class="row g-3 mb-4">

                    {{-- Invoice Details --}}
                    <div class="col-md-6">

                        <div class="p-3 bg-light rounded-3 border">

                            <span class="text-muted small text-uppercase d-block mb-1">
                                Invoice Number
                            </span>

                            <h5 class="fw-bold text-primary mb-2"
                                id="invoiceDetailNo">
                                -
                            </h5>

                            <span class="text-muted small text-uppercase d-block mb-1">
                                Invoice Date
                            </span>

                            <p class="fw-semibold text-dark mb-0"
                               id="invoiceDetailDate">
                                -
                            </p>

                        </div>

                    </div>


                    {{-- Party Details --}}
                    <div class="col-md-6">

                        <div class="p-3 bg-light rounded-3 border">

                            <span class="text-muted small text-uppercase d-block mb-1">
                                Party Name (Customer / Vendor)
                            </span>

                            <h5 class="fw-bold text-dark mb-2"
                                id="invoiceDetailParty">
                                -
                            </h5>

                            <span class="text-muted small text-uppercase d-block mb-1">
                                GSTIN Number
                            </span>

                            <p class="fw-bold text-dark font-monospace mb-0"
                               id="invoiceDetailGstin">
                                -
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ITEM BREAKDOWN --}}
                <div class="table-responsive border rounded-3 mb-4">

                    <table class="table table-sm align-middle m-0">

                        <thead class="bg-light">

                            <tr class="small fw-bold text-secondary">

                                <th class="ps-3 py-3">
                                    Item / Description
                                </th>

                                <th class="py-3">
                                    HSN / SAC
                                </th>

                                <th class="py-3 text-end">
                                    Qty
                                </th>

                                <th class="py-3 text-end">
                                    Rate
                                </th>

                                <th class="py-3 text-end">
                                    Taxable Amount
                                </th>

                                <th class="pe-3 py-3 text-end">
                                    GST
                                </th>

                            </tr>

                        </thead>

                        <tbody id="invoiceBreakdownBody">

                        </tbody>

                    </table>

                </div>


                {{-- GST SUMMARY --}}
                <div class="row justify-content-end mb-4">

                    <div class="col-md-5 col-lg-4">

                        <div class="p-3 bg-light rounded-3 border">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    Taxable Amount
                                </span>

                                <strong id="invoiceDetailTaxable">
                                    ₹0.00
                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    CGST
                                </span>

                                <span id="invoiceDetailCgst">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    SGST
                                </span>

                                <span id="invoiceDetailSgst">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">
                                    IGST
                                </span>

                                <span id="invoiceDetailIgst">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span class="text-muted">
                                    Total GST
                                </span>

                                <strong class="text-danger"
                                        id="invoiceDetailGst">
                                    ₹0.00
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FINAL SUMMARY --}}
                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light-primary border border-primary-subtle">

                    <div>

                        <span class="text-muted small text-uppercase d-block mb-1">
                            Transaction Module
                        </span>

                        <div class="fw-bold text-dark"
                             id="invoiceDetailModuleBottom">
                            -
                        </div>

                    </div>


                    <div class="text-end">

                        <span class="text-muted small text-uppercase d-block mb-1">
                            Invoice Total Amount
                        </span>

                        <h4 class="fw-bold text-dark mb-0"
                            id="invoiceDetailTotal">
                            ₹0.00
                        </h4>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="modal-footer border-top px-4 py-3">

                <button type="button"
                        class="btn btn-secondary px-4 rounded-3"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

<!--<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <div class="avtar avtar-s bg-light-primary text-primary rounded-circle">
                        <i class="ph-duotone ph-file-text fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="invoiceModalLabel">Invoice Breakdown Details</h5>
                        <small class="text-muted" id="modalModuleTag">Module Transaction View</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small uppercase-label d-block mb-1">Invoice Number</span>
                            <h5 class="fw-bold text-primary mb-2" id="modalInvoiceNo">-</h5>
                            <span class="text-muted small uppercase-label d-block mb-1">Invoice Date</span>
                            <p class="fw-semibold text-dark mb-0" id="modalInvoiceDate">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small uppercase-label d-block mb-1">Party Name (Customer / Vendor)</span>
                            <h5 class="fw-bold text-dark mb-2" id="modalPartyName">-</h5>
                            <span class="text-muted small uppercase-label d-block mb-1">GSTIN Number</span>
                            <p class="fw-bold text-dark font-monospace mb-0" id="modalGstin">-</p>
                        </div>
                    </div>
                </div>

                <div class="table-responsive border rounded-3 mb-4">
                    <table class="table table-sm align-middle m-0">
                        <thead class="bg-light">
                            <tr class="small fw-bold text-secondary">
                                <th class="ps-3 py-2">HSN / SAC</th>
                                <th class="py-2 text-end">Taxable Amount</th>
                                <th class="py-2 text-end">CGST</th>
                                <th class="py-2 text-end">SGST</th>
                                <th class="py-2 text-end">IGST</th>
                                <th class="pe-3 py-2 text-end">Total GST</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3 font-monospace fw-bold text-dark" id="modalHsn">-</td>
                                <td class="text-end fw-semibold" id="modalTaxable">-</td>
                                <td class="text-end text-muted" id="modalCgst">-</td>
                                <td class="text-end text-muted" id="modalSgst">-</td>
                                <td class="text-end text-muted" id="modalIgst">-</td>
                                <td class="pe-3 text-end fw-bold text-danger" id="modalTotalGst">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light-primary border border-primary-subtle">
                    <div>
                        <span class="text-muted small uppercase-label d-block">Transaction Status</span>
                        <div id="modalStatusBadge">-</div>
                    </div>
                    <div class="text-end">
                        <span class="text-muted small uppercase-label d-block">Invoice Total Amount</span>
                        <h4 class="fw-bold text-dark mb-0" id="modalGrandTotal">₹0.00</h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top px-4 py-3">
                <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>-->

<script>
function filterGstTable() {
    const selectedModule = document.getElementById('moduleFilter').value.toLowerCase();
    const selectedStatus = document.getElementById('statusFilter').value.toLowerCase();
    const searchVal = document.getElementById('gstTableSearch').value.toLowerCase().trim();

    const rows = document.querySelectorAll('#gstListingTable tbody .gst-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowModule = row.getAttribute('data-module').toLowerCase();
        const rowStatus = row.getAttribute('data-status').toLowerCase();
        const rowText = row.innerText.toLowerCase();

        const matchesModule = !selectedModule || rowModule === selectedModule;
        const matchesStatus = !selectedStatus || rowStatus.includes(selectedStatus);
        const matchesSearch = !searchVal || rowText.includes(searchVal);

        if (matchesModule && matchesStatus && matchesSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('recordCountInfo').innerText = `Showing ${visibleCount} entries`;
}

function updatePeriodOptions() {
    const fySelect = document.getElementById('fySelect');
    const periodTypeSelect = document.getElementById('periodTypeSelect');
    const periodValueSelect = document.getElementById('periodValueSelect');

    if (!fySelect || !periodTypeSelect || !periodValueSelect) return;

    const fyVal = fySelect.value; // e.g. "2025-2026"
    const periodType = periodTypeSelect.value; // "monthly", "quarterly", "halfyearly"
    const years = fyVal.split('-');
    const startYear = parseInt(years[0]);
    const endYear = parseInt(years[1]);

    periodValueSelect.innerHTML = '';

    if (periodType === 'monthly') {
        const months = [
            { name: `April ${startYear}`, val: `Apr-${startYear}` },
            { name: `May ${startYear}`, val: `May-${startYear}` },
            { name: `June ${startYear}`, val: `Jun-${startYear}` },
            { name: `July ${startYear}`, val: `Jul-${startYear}`, defaultSelected: true },
            { name: `August ${startYear}`, val: `Aug-${startYear}` },
            { name: `September ${startYear}`, val: `Sep-${startYear}` },
            { name: `October ${startYear}`, val: `Oct-${startYear}` },
            { name: `November ${startYear}`, val: `Nov-${startYear}` },
            { name: `December ${startYear}`, val: `Dec-${startYear}` },
            { name: `January ${endYear}`, val: `Jan-${endYear}` },
            { name: `February ${endYear}`, val: `Feb-${endYear}` },
            { name: `March ${endYear}`, val: `Mar-${endYear}` }
        ];
        months.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.val;
            opt.innerText = m.name;
            if (m.defaultSelected) opt.selected = true;
            periodValueSelect.appendChild(opt);
        });
    } else if (periodType === 'quarterly') {
        const quarters = [
            { name: `Q1 (Apr - Jun ${startYear})`, val: `Q1-${startYear}`, defaultSelected: true },
            { name: `Q2 (Jul - Sep ${startYear})`, val: `Q2-${startYear}` },
            { name: `Q3 (Oct - Dec ${startYear})`, val: `Q3-${startYear}` },
            { name: `Q4 (Jan - Mar ${endYear})`, val: `Q4-${endYear}` }
        ];
        quarters.forEach(q => {
            const opt = document.createElement('option');
            opt.value = q.val;
            opt.innerText = q.name;
            if (q.defaultSelected) opt.selected = true;
            periodValueSelect.appendChild(opt);
        });
    } else if (periodType === 'halfyearly') {
        const halfYears = [
            { name: `H1 (Apr - Sep ${startYear})`, val: `H1-${startYear}`, defaultSelected: true },
            { name: `H2 (Oct ${startYear} - Mar ${endYear})`, val: `H2-${startYear}-${endYear}` }
        ];
        halfYears.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h.val;
            opt.innerText = h.name;
            if (h.defaultSelected) opt.selected = true;
            periodValueSelect.appendChild(opt);
        });
    }

    filterGstTable();
}

document.addEventListener('DOMContentLoaded', function () {
    updatePeriodOptions();

    const invoiceModalEl = document.getElementById('invoiceDetailsModal');
    if (invoiceModalEl) {
        const bsModal = new bootstrap.Modal(invoiceModalEl);

        document.querySelectorAll('.show-invoice-modal').forEach(link => {
            link.addEventListener('click', function () {
                document.getElementById('modalInvoiceNo').innerText = this.getAttribute('data-no');
                document.getElementById('modalInvoiceDate').innerText = this.getAttribute('data-date');
                document.getElementById('modalPartyName').innerText = this.getAttribute('data-party');
                document.getElementById('modalGstin').innerText = this.getAttribute('data-gstin');
                document.getElementById('modalHsn').innerText = this.getAttribute('data-hsn');

                document.getElementById('modalTaxable').innerText = '₹' + this.getAttribute('data-taxable');
                document.getElementById('modalCgst').innerText = '₹' + this.getAttribute('data-cgst');
                document.getElementById('modalSgst').innerText = '₹' + this.getAttribute('data-sgst');
                document.getElementById('modalIgst').innerText = '₹' + this.getAttribute('data-igst');
                document.getElementById('modalTotalGst').innerText = '₹' + this.getAttribute('data-gst');
                document.getElementById('modalGrandTotal').innerText = '₹' + this.getAttribute('data-total');

                const modName = this.getAttribute('data-module');
                document.getElementById('modalModuleTag').innerText = `Module: ${modName}`;

                const status = this.getAttribute('data-status');
                let badgeHtml = '';
                if (status.includes('Active')) {
                    badgeHtml = '<span class="badge bg-light-success text-success fw-bold px-3 py-1.5 rounded-pill"><i class="ph-duotone ph-check-circle me-1"></i> Active</span>';
                } else if (status.includes('Advance')) {
                    badgeHtml = '<span class="badge bg-light-info text-info fw-bold px-3 py-1.5 rounded-pill"><i class="ph-duotone ph-clock me-1"></i> Advance</span>';
                } else {
                    badgeHtml = '<span class="badge bg-light-warning text-warning fw-bold px-3 py-1.5 rounded-pill"><i class="ph-duotone ph-warning me-1"></i> Due</span>';
                }
                document.getElementById('modalStatusBadge').innerHTML = badgeHtml;

                bsModal.show();
            });
        });
    }
});

//Start custom code
$(document).ready(function () {

    //Financial Year + Frequency Change
    $('#financial_year, #period_frequency').on('change', function () {

        let financialYear = $('#financial_year').val();
        let frequency = $('#period_frequency').val();
		resetGstSummary();
        // Reset Period
        $('#select_period').html('<option value="">Select Period</option>');

        $('#gstListingBody').html(`
            <tr>
                <td colspan="9"
                    class="text-center text-muted py-5">
                    Please select period.
                </td>
            </tr>
        `);

        if (!financialYear || !frequency) {
            return;
        }

        $.ajax({
            url: "{{ route('gst.dashboard.periods') }}",
            type: "GET",
            data: {
                financial_year: financialYear,
                period_frequency: frequency
            },

            beforeSend: function () {
                $('#select_period').html(`<option value="">Loading periods...</option>`);

            },

            success: function (response) {
                $('#select_period').html('<option value="">Select Period</option>');
                if (response.success && response.periods.length > 0) {
                    $.each(response.periods, function (index, period) {
                        $('#select_period').append(`<option value="${period.value}">${period.label}</option>`);
                    });
                } else {
                    $('#select_period').html(`<option value="">No period available</option>`);
                }
            },

            error: function () {
                $('#select_period').html(`<option value="">Unable to load periods</option>`);
            }
        });
    });


    /*
    |--------------------------------------------------------------------------
    | Select Period
    |--------------------------------------------------------------------------
    */

    $('#select_period').on('change', function () {

        let financialYear = $('#financial_year').val();
        let frequency = $('#period_frequency').val();
        let period = $(this).val();

        if (!financialYear || !frequency || !period) {
            return;
        }

        loadTransactions(financialYear,frequency,period);

    });


    /*
    |--------------------------------------------------------------------------
    | Load Transactions
    |--------------------------------------------------------------------------
    */
	let allTransactions = [];
	let currentPage = 1;
	let pageLength = 10;
	
    function loadTransactions(financialYear,frequency,period) 
	{

        $('#gstListingBody').html(`
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div class="spinner-border text-primary"
                         role="status">
                    </div>

                    <div class="mt-2 text-muted">
                        Loading transactions...
                    </div>
                </td>
            </tr>
        `);


        $.ajax({
            url: "{{ route('gst.dashboard.transactions') }}",
            type: "GET",
            data: {
                financial_year: financialYear,
                period_frequency: frequency,
                period: period
            },

            success: function (response) {

                if (response.success && response.data.length > 0) {
                    allTransactions = response.data;
                    currentPage = 1;
                    pageLength = $('#pageLength').val() === 'all' ? allTransactions.length : parseInt($('#pageLength').val() || 10);
                    updateGstSummary(response.summary);
                    renderGstPage();
                } else {
                    allTransactions = [];
                    renderEmptyTransactions('No transactions found.');
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                $('#gstListingBody').html(`
                    <tr>
                        <td colspan="9"
                            class="text-center text-danger py-5">

                            Failed to load transactions.

                        </td>
                    </tr>
                `);
            }
        });
    }

	/*
    |--------------------------------------------------------------------------
    | Render Summary
    |--------------------------------------------------------------------------
    */

	function updateGstSummary(summary) 
	{

		summary = summary || {};

		$('#totalSales').text(
			'₹' + formatAmount(summary.total_sales || 0)
		);

		$('#totalPurchase').text(
			'₹' + formatAmount(summary.total_purchase || 0)
		);

		$('#outputGst').text(
			'₹' + formatAmount(summary.output_gst || 0)
		);

		$('#inputGst').text(
			'₹' + formatAmount(summary.input_gst || 0)
		);

		$('#netGstLiability').text(
			'₹' + formatAmount(summary.net_gst_liability || 0)
		);

		$('#gstPaid').text(
			'₹' + formatAmount(summary.gst_paid || 0)
		);

		let gstPayable = parseFloat(
			summary.gst_payable || 0
		);

		$('#gstPayableRefund').text(
			'₹' + formatAmount(Math.abs(gstPayable))
		);

		if (gstPayable > 0) {
			$('#gstPayableText').html(`
				<span class="text-danger">
					<i class="ph-duotone ph-arrow-circle-up me-1"></i>
					Balance Tax Payable
				</span>
			`);
		} else if (gstPayable < 0) {
			$('#gstPayableText').html(`
				<span class="text-success">
					<i class="ph-duotone ph-arrow-circle-down me-1"></i>
					GST Refund / Credit Available
				</span>
			`);
		} else {
			$('#gstPayableText').html(`
				<span class="text-muted">
					No GST Payable / Refund
				</span>
			`);
		}
	}
	
	function resetGstSummary() {

		$('#totalSales').text('₹0.00');
		$('#totalPurchase').text('₹0.00');
		$('#outputGst').text('₹0.00');
		$('#inputGst').text('₹0.00');
		$('#netGstLiability').text('₹0.00');
		$('#gstPaid').text('₹0.00');
		$('#gstPayableRefund').text('₹0.00');

	}
	
    /*
    |--------------------------------------------------------------------------
    | Render Transaction Table
    |--------------------------------------------------------------------------
    */

    function renderGstTransactions(data) {

        let html = '';

        $.each(data, function (index, row) {

			let status = (row.status || '').trim().toLowerCase();
			let statusClass = 'bg-light-secondary text-secondary';
			let statusIcon = '';
			let displayStatus = '-';

			if (status === 'active' || status === 'full') {
				statusClass = 'bg-light-success text-success';
				statusIcon = 'ph-check-circle';
				displayStatus = status === 'active' ? 'Active' : 'Full';
			} else if (status === 'advance' || status === 'partial') {
				statusClass = 'bg-light-info text-info';
				statusIcon = 'ph-clock';
				displayStatus = 'Advance';
			} else if (status === 'due') {
				statusClass = 'bg-light-warning text-warning';
				statusIcon = 'ph-warning';
				displayStatus = 'Due';
			} else if (row.status) {
				displayStatus = row.status;
			}


			// ==========================================
			// MODULE BADGE
			// ==========================================

			let moduleClass = 'bg-light-secondary text-dark';
			let moduleIcon = 'ph-receipt';

			switch (row.module) {
				case 'Sales':
					moduleClass = 'bg-light-primary text-dark';
					moduleIcon = 'ph-trend-up';
					break;
				case 'Credit Note':
					moduleClass = 'bg-light-danger text-danger';
					moduleIcon = 'ph-file-arrow-down';
					break;
				case 'Purchase':
					moduleClass = 'bg-light-info text-dark';
					moduleIcon = 'ph-shopping-cart';
					break;
				case 'Debit Note':
					moduleClass = 'bg-light-warning text-warning';
					moduleIcon = 'ph-file-arrow-up';
					break;
				case 'Expense':
					moduleClass = 'bg-light-secondary text-dark';
					moduleIcon = 'ph-receipt';
					break;
				case 'Asset':
					moduleClass = 'bg-light-success text-dark';
					moduleIcon = 'ph-desktop';
					break;
				case 'RCM':
					moduleClass = 'bg-light-dark text-dark';
					moduleIcon = 'ph-swap';
					break;
			}

            html += `
                <tr class="gst-row" data-module="${row.module}" data-status="${displayStatus}">
                     <td class="ps-4">
						<span class="badge ${moduleClass} fw-bold px-2.5 py-1.5 rounded-pill">
							<i class="ph-duotone ${moduleIcon} me-1"></i>
							${row.module}
						</span>
					</td>
                     <td class="fw-semibold text-dark">${row.invoice_date ?? '-'}</td>
                    <td>
                        <a href="javascript:void(0);"
                           class="fw-bold text-primary text-decoration-underline show-invoice-details" data-module="${row.module}"
                           data-id="${row.id}"
                           data-invoice-no="${row.invoice_no || ''}">
                            ${row.invoice_no || 'View'}
                        </a>
                    </td>
                    <td class="fw-bold text-dark">${row.party_name ?? '-'} </td>
                    <td>
                        <span class="font-monospace text-muted small">
                            ${row.gstin ?? '-'}
                        </span>
                    </td>
                    <td class="text-end fw-semibold">₹${formatAmount(row.taxable_amount)}</td>
                    <td class="text-end fw-bold text-danger">₹${formatAmount(row.gst_amount)}</td>
                    <td class="text-end fw-bold text-dark"> ₹${formatAmount(row.invoice_total)}</td>
                    <td class="pe-4 text-center">
						<span class="badge ${statusClass} fw-bold px-3 py-1.5 rounded-pill">
							${statusIcon
								? `<i class="ph-duotone ${statusIcon} me-1"></i>`
								: ''
							}
							${displayStatus}
						</span>
					</td>
                </tr>`;
        });

        $('#gstListingBody').html(html);

    }

    function renderEmptyTransactions(message) {
        $('#gstListingBody').html(`
            <tr>
                <td colspan="9"
                    class="text-center text-muted py-5">
                    ${message}
                </td>
            </tr>
        `);
        $('#recordCountInfo').text('Showing 0 entries');
        $('#pageInfo').text('Page 0 of 0');
        $('#prevPage, #nextPage').prop('disabled', true);
    }

    function renderGstPage() {
        const totalRecords = allTransactions.length;
        const effectivePageLength = pageLength === 'all' ? totalRecords : pageLength || 10;
        const totalPages = effectivePageLength > 0 ? Math.max(1, Math.ceil(totalRecords / effectivePageLength)) : 0;

        if (totalRecords === 0) {
            renderEmptyTransactions('No transactions found.');
            return;
        }

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        const startIndex = effectivePageLength === totalRecords ? 0 : (currentPage - 1) * effectivePageLength;
        const endIndex = effectivePageLength === totalRecords ? totalRecords : Math.min(totalRecords, startIndex + effectivePageLength);
        const pageData = allTransactions.slice(startIndex, endIndex);

        renderGstTransactions(pageData);

        const showingText = effectivePageLength === totalRecords
            ? `Showing ${totalRecords} entries`
            : `Showing ${startIndex + 1} - ${endIndex} of ${totalRecords} entries`;

        $('#recordCountInfo').text(showingText);
        $('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);
        $('#prevPage').prop('disabled', currentPage <= 1);
        $('#nextPage').prop('disabled', currentPage >= totalPages);
    }

    function updatePaginationLength(value) {
        if (value === 'all') {
            pageLength = allTransactions.length;
        } else {
            pageLength = parseInt(value || 10);
        }
        currentPage = 1;
        renderGstPage();
    }

    $('#pageLength').on('change', function () {
        updatePaginationLength($(this).val());
    });

    $('#prevPage').on('click', function () {
        if (currentPage > 1) {
            currentPage -= 1;
            renderGstPage();
        }
    });

    $('#nextPage').on('click', function () {
        const effectivePageLength = pageLength === 'all' ? allTransactions.length : pageLength || 10;
        const totalPages = effectivePageLength > 0 ? Math.max(1, Math.ceil(allTransactions.length / effectivePageLength)) : 0;
        if (currentPage < totalPages) {
            currentPage += 1;
            renderGstPage();
        }
    });

    $(document).on('click', '.show-invoice-details', function () {
        const module = $(this).data('module');
        const id = $(this).data('id');
        const invoiceNo = $(this).data('invoice-no');

        $('#invoiceDetailsModal').modal('show');
        $('#invoiceBreakdownBody').html(`
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">Loading invoice details...</div>
                </td>
            </tr>
        `);

        $.ajax({
            url: "{{ route('gst.dashboard.invoice.details') }}",
            type: "GET",
            data: {
                module: module,
                id: id,
                invoice_no: invoiceNo
            },
            success: function (response) {
                if (response.success) {
                    renderInvoiceBreakdown(response);
                } else {
                    $('#invoiceBreakdownBody').html(`
                        <tr>
                            <td colspan="6" class="text-center text-muted">No details found.</td>
                        </tr>
                    `);
                }
            },
            error: function () {
                $('#invoiceBreakdownBody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">Failed to load invoice details.</td>
                    </tr>
                `);
            }
        });
    });

    function renderInvoiceBreakdown(data) {
        const invoice = data.invoice || {};
        const items = data.items || [];
        const gstBreakup = data.gst_breakup || {};

        let html = '';

        $.each(items, function (index, item) {
            const itemName = item.item_name || item.prodservname || item.product_name || item.description || '-';
            const hsnSac = item.hsn_sac || item.hsn_sac_code || '-';
            const qty = parseFloat(item.qty || item.quantity || 1);
            const rate = parseFloat(item.rate || item.rate_unit_price || 0);
            const taxableAmount = parseFloat(item.taxable_amount || item.amount || 0);
            const gstAmount = parseFloat(item.gst_amount || item.tax_amt || 0);

            html += `
                <tr>
                    <td class="ps-3 fw-semibold text-dark">${itemName}</td>
                    <td class="font-monospace">${hsnSac}</td>
                    <td class="text-end">${qty.toFixed(2)}</td>
                    <td class="text-end">₹${formatAmount(rate)}</td>
                    <td class="text-end fw-semibold">₹${formatAmount(taxableAmount)}</td>
                    <td class="pe-3 text-end">₹${formatAmount(gstAmount)}</td>
                </tr>
            `;
        });

        if (html === '') {
            html = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No item details found.</td>
                </tr>
            `;
        }

        $('#invoiceBreakdownBody').html(html);

        const moduleName = data.module || '-';
        $('#invoiceDetailModule').text(moduleName);
        $('#invoiceDetailModuleBottom').text(moduleName);
        $('#invoiceDetailNo').text(invoice.invoice_no || 'View');
        $('#invoiceDetailDate').text(invoice.invoice_date || '-');
        $('#invoiceDetailParty').text(invoice.party_name || '-');
        $('#invoiceDetailGstin').text(invoice.gstin || '-');

        $('#invoiceDetailTaxable').text('₹' + formatAmount(invoice.taxable_amount || 0));
        $('#invoiceDetailGst').text('₹' + formatAmount(invoice.gst_amount || 0));
        $('#invoiceDetailTotal').text('₹' + formatAmount(invoice.invoice_total || 0));

        $('#invoiceDetailCgst').text('₹' + formatAmount(gstBreakup.cgst || 0));
        $('#invoiceDetailSgst').text('₹' + formatAmount(gstBreakup.sgst || 0));
        $('#invoiceDetailIgst').text('₹' + formatAmount(gstBreakup.igst || 0));
    }

    function formatAmount(amount) {

        return Number(
            amount || 0
        ).toLocaleString(
            'en-IN',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }

});

//End custom code


</script>

@endsection
