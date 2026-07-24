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
                            <h4 class="mb-1 fw-bold text-dark">E-Cashbook Systems Ltd</h4>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light-secondary text-secondary font-monospace"><i class="ph-duotone ph-identification-card me-1"></i> GSTIN: 19ABCDE1234F1Z5</span>
                                <span class="badge bg-light-success text-success"><i class="ph-duotone ph-check-circle me-1"></i> Active Taxpayer</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6">
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
                                <!-- Dynamic options generated via JS (April to March per Indian Law) -->
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
                    <h3 class="mb-1 fw-bold text-dark">₹4,85,000.00</h3>
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
                    <h3 class="mb-1 fw-bold text-dark">₹3,12,000.00</h3>
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
                    <h3 class="mb-1 fw-bold text-danger">₹87,300.00</h3>
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
                    <h3 class="mb-1 fw-bold text-success">₹56,160.00</h3>
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
                    <h3 class="mb-1 fw-bold text-dark">₹31,140.00</h3>
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
                    <h3 class="mb-1 fw-bold text-primary">₹28,500.00</h3>
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
                    <h3 class="mb-1 fw-bold text-primary">₹2,640.00</h3>
                    <span class="small text-muted font-12 fw-semibold">
                        <span class="text-danger"><i class="ph-duotone ph-arrow-circle-up me-1"></i> Balance Tax Payable</span>
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
                    <option value="Expense">Expense</option>
                    <option value="Asset">Asset</option>
                    <option value="RCM">RCM</option>
                </select>

                <!-- Status Filter -->
                <select id="statusFilter" class="form-select form-select-sm rounded-3 border-light-subtle fw-semibold" style="width: 160px;" onchange="filterGstTable()">
                    <option value="">All Statuses</option>
                    <option value="Active">Active</option>
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
                    <tbody>
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
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
            <span class="text-muted small" id="recordCountInfo">Showing 8 entries</span>
            <small class="text-muted">GST Module Data • Indicative Report Summary</small>
        </div>
    </div>
</div>

<!-- Invoice Details Modal -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
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
</div>

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
</script>

@endsection
