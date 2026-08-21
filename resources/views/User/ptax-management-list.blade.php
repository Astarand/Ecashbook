@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-4">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 mb-2">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Professional Tax Payment</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <div class="page-header-title">
                        <h2 class="mb-0 text-dark fw-bold">Professional Tax Payment</h2>
                    </div>
                </div>
                <div class="col-md-9 text-end">
                    <a href="{{ route('payroll.payslip_update') }}#ptax-section" class="btn btn-warning shadow">
                        PTAX Challan Update
                    </a>

					<button class="btn btn-outline-primary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#aboutPTaxModal">
                        <i class="ti ti-info-circle f-16"></i> About P Tax Registration
                    </button>
					<a href="#ptaxFilterOffcanvas" class="btn btn-outline-secondary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#ptaxFilterOffcanvas" aria-controls="ptaxFilterOffcanvas">
                        <i class="ti ti-filter f-16"></i> Summary of Return
                    </a>
					<a href="https://professiontax.wb.gov.in/"
					   target="_blank"
					   class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
					   <i class="ti ti-credit-card f-16"></i> Professional Tax Payment
					</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Premium Static Alert (Visible by default, no collapse) -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card premium-warning-alert border-0 shadow-sm rounded-4 overflow-hidden mb-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start flex-column flex-lg-row gap-3">
                        <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="avtar avtar-s btn-light-warning rounded-circle"><i class="ti ti-help-circle f-18"></i></span>
                                <h5 class="mb-0 fw-bold text-dark">Steps for payment of Professional Tax:</h5>
                            </div>
                            <ol class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">1</span>
                                    <span>Login to <a href="https://professiontax.wb.gov.in/" target="_blank" class="fw-semibold text-primary text-decoration-none">https://professiontax.wb.gov.in/</a> or click the Professional Tax Payment button above.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">2</span>
                                    <span>Click on <strong class="text-dark">"Quick Pay"</strong>.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">3</span>
                                    <span>Select Payment for registration or enrollment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">4</span>
                                    <span>Enter registration or enrollment number.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">5</span>
                                    <span>Select year or month for payment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                    <span class="step-num-badge-new">6</span>
                                    <span>Click on <strong class="text-dark">"Pay Now"</strong> and make the payment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark font-14">
                                    <span class="step-num-badge-new">7</span>
                                    <span>After successful payment go to payment history and download the challan copy.</span>
                                </li>
                            </ol>
                        </div>
                        <div class="flex-shrink-0 align-self-lg-center d-none d-lg-block">
                            <img src="../assets/images/application/img-accout-alert.png" alt="img" class="img-fluid wid-80 opacity-85">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom px-4 py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    {{-- Filter controls --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <select id="ptax_fy" class="form-select form-select-sm" style="width:150px;">
                            @php
                                $today = now();
                                $fyStart = $today->month >= 4 ? $today->year : $today->year - 1;
                            @endphp
                            @for ($y = $fyStart - 1; $y <= $fyStart + 1; $y++)
                                <option value="{{ $y }}-{{ $y + 1 }}" {{ $y === $fyStart ? 'selected' : '' }}>
                                    FY {{ $y }}-{{ $y + 1 }}
                                </option>
                            @endfor
                        </select>
                        <select id="ptax_filter_type" class="form-select form-select-sm" style="width:140px;" onchange="renderPtaxFilter()">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="half-yearly">Half-Yearly</option>
                            <option value="yearly">Full Year</option>
                        </select>
                        <select id="ptax_filter_period" class="form-select form-select-sm" style="width:160px;"></select>
                        <button class="btn btn-primary btn-sm px-4" onclick="loadPtaxSummary()">
                            <i class="ti ti-refresh me-1"></i> Load
                        </button>
                    </div>
                    {{-- Export buttons --}}
                    <div class="d-flex gap-2">
                        <button onclick="exportPtaxPDF()" class="btn btn-sm px-3 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="background:#ffeef0;color:#dc3545;">
                            <i class="ti ti-file-type-pdf f-16"></i> PDF
                        </button>
                        <button onclick="exportPtaxExcel()" class="btn btn-sm px-3 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="background:#e8fadf;color:#198754;">
                            <i class="ti ti-file-spreadsheet f-16"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table tbl-product m-0 custom-list-table align-middle" id="ptaxSummaryTable">
                            <thead>
                                <tr class="bg-light-header">
                                    <th class="text-end py-3 ps-4" style="width:50px;">#</th>
                                    <th class="py-3">Registration No</th>
                                    <th class="py-3">Employee Name</th>
                                    <th class="py-3">Employee Count</th>
                                    <th class="py-3">Gross Salary</th>
                                    <th class="py-3">PT Deduction</th>
                                    <th class="py-3">Total PT Payable</th>
                                    <th class="py-3">Period</th>
                                    <th class="text-center py-3 pe-4" style="width:90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="ptaxSummaryBody">
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        Select filters and click Load
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark font-20">P-Tax Period Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="table-responsive rounded-3 overflow-hidden border">
                    <table class="table table-striped table-hover m-0 align-middle font-14">
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Registration No</th>
                            <td id="detailRegNo" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee Name</th>
                            <td id="detailEmployerName" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Period</th>
                            <td id="detailPeriod" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee Count</th>
                            <td id="detailEmpCount" class="ps-3 fw-semibold text-dark"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Gross Salary</th>
                            <td id="detailGrossSalary" class="ps-3 fw-semibold text-dark"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">PT Deduction</th>
                            <td id="detailPtDeduction" class="ps-3 fw-semibold text-danger"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Total PT Payable</th>
                            <td id="detailPtPayable" class="ps-3 fw-bold text-success"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- About P-Tax Registration Modal -->
<div class="modal fade" id="aboutPTaxModal" tabindex="-1" aria-labelledby="aboutPTaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="avtar avtar-s btn-light-primary rounded-circle"><i class="ti ti-info-circle f-18"></i></span>
                    <h5 class="modal-title fw-bold text-dark font-20" id="aboutPTaxModalLabel">About Professional Tax Registration</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-dark font-14">

                {{-- SECTION 1: WHY REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-help f-18"></i> Why Required</h6>
                    <p class="text-secondary mb-2">Professional Tax is a State Government tax applicable on:</p>
                    <ul class="list-unstyled ps-3 mb-3">
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16 mt-0.5"></i> Employers</li>
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16 mt-0.5"></i> Employees earning salary/wages</li>
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16 mt-0.5"></i> Professionals, traders, companies, firms</li>
                    </ul>
                    <p class="text-secondary mb-2">Employer must:</p>
                    <ul class="list-unstyled ps-3 mb-3">
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> Deduct PT from employee salary (where applicable)</li>
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> Deposit to State Government</li>
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> File periodic returns</li>
                    </ul>
                    <div class="alert alert-warning border-0 bg-light-warning rounded-3 d-flex align-items-center gap-2 mb-0">
                        <i class="ti ti-exclamation-circle f-18 text-warning"></i>
                        <span class="small font-weight-semibold">In West Bengal, PT registration is mandatory for most business establishments employing staff.</span>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 2: MAIN TYPES --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-list f-18"></i> Main Types</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 rounded-3 p-3 h-100 mb-0 shadow-none">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-light-primary text-primary fw-bold px-2.5 py-1">PTEC</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 font-15">P-Tax Enrollment Certificate</h6>
                                <p class="text-muted small mb-0">Required for businesses, professionals, partners, directors, or self-employed practitioners.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0 rounded-3 p-3 h-100 mb-0 shadow-none">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-light-success text-success fw-bold px-2.5 py-1">PTRC</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 font-15">P-Tax Registration Certificate</h6>
                                <p class="text-muted small mb-0">Required for employers who employ staff and deduct Professional Tax from employee salaries.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 3: BASIC DOCUMENTS REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-file-text f-18"></i> Basic Documents Required</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-id f-16 text-muted"></i> PAN Card of Company/Firm/Proprietor</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-fingerprint f-16 text-muted"></i> Aadhaar Card of Authorized Signatory</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-license f-16 text-muted"></i> Trade License / Shops Registration</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-home f-16 text-muted"></i> Address Proof of Business</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-mail f-16 text-muted"></i> Mobile No & Email ID</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-award f-16 text-muted"></i> Incorporation / Partnership / LLP</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-wallet f-16 text-muted"></i> Cancelled Cheque / Bank Statement</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-users f-16 text-muted"></i> Employee Details</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-photo f-16 text-muted"></i> Passport Size Photo</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-signature f-16 text-muted"></i> Authorization Letter / Board Res.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 4: COMPLIANCE REQUIREMENT --}}
                <div>
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-checklist f-18"></i> Compliance Requirement</h6>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <div class="flex-fill bg-light-primary p-3 rounded-3 text-center">
                            <h6 class="fw-bold text-primary mb-1">Deduction</h6>
                            <p class="text-secondary small mb-0">Monthly PT deduction</p>
                        </div>
                        <div class="flex-fill bg-light-success p-3 rounded-3 text-center">
                            <h6 class="fw-bold text-success mb-1">Payment</h6>
                            <p class="text-secondary small mb-0">PT payment challan</p>
                        </div>
                        <div class="flex-fill bg-light-warning p-3 rounded-3 text-center">
                            <h6 class="fw-bold text-warning mb-1">Filing</h6>
                            <p class="text-secondary small mb-0">Periodic PT return filing</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="ptaxFilterOffcanvas" aria-labelledby="ptaxFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold text-dark" id="ptaxFilterOffcanvasLabel">Filter PTAX Filing Records</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="ptaxFilterForm" method="POST" action="{{ route('download.ptax.filing') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterPtaxName" class="form-label fw-semibold text-dark">Employee Name</label>
                    <input type="text" class="form-control" id="filterPtaxName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterPtaxEmployeeId" class="form-label fw-semibold text-dark">Employee ID</label>
                    <input type="text" class="form-control" id="filterPtaxEmployeeId" name="employee_id" placeholder="Filter by employee ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3 fw-bold text-dark">Filter By Period</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ptaxPeriodType" id="ptaxPeriodMonth" value="month" checked>
                        <label class="form-check-label" for="ptaxPeriodMonth">Monthly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ptaxPeriodType" id="ptaxPeriodQuarter" value="quarter">
                        <label class="form-check-label" for="ptaxPeriodQuarter">Quarterly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ptaxPeriodType" id="ptaxPeriodYear" value="year">
                        <label class="form-check-label" for="ptaxPeriodYear">Yearly</label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12" id="filterPtaxMonthGroup">
                    <label for="filterPtaxMonth" class="form-label fw-semibold text-dark">Select Month</label>
                    <select class="form-select" id="filterPtaxMonth" name="month">
                        <option value="">Choose month</option>
                        <option value="january">January</option>
                        <option value="february">February</option>
                        <option value="march">March</option>
                        <option value="april">April</option>
                        <option value="may">May</option>
                        <option value="june">June</option>
                        <option value="july">July</option>
                        <option value="august">August</option>
                        <option value="september">September</option>
                        <option value="october">October</option>
                        <option value="november">November</option>
                        <option value="december">December</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterPtaxQuarterGroup">
                    <label for="filterPtaxQuarter" class="form-label fw-semibold text-dark">Select Quarter</label>
                    <select class="form-select" id="filterPtaxQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Quarter 1 (Apr - Jun)</option>
                        <option value="q2">Quarter 2 (Jul - Sep)</option>
                        <option value="q3">Quarter 3 (Oct - Dec)</option>
                        <option value="q4">Quarter 4 (Jan - Mar)</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterPtaxYearGroup">
                    <label for="filterPtaxYear" class="form-label fw-semibold text-dark">Select Year</label>
                    <select class="form-select" id="filterPtaxYear" name="year">
                        <option value="">Choose year</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3 fw-bold text-dark">Download Options</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-12">
                        <label for="ptaxDownloadFormat" class="form-label fw-semibold text-dark">Choose format</label>
                        <select class="form-select" id="ptaxDownloadFormat" name="download_format">
                            <option value="">Select format</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit"
                                class="btn btn-outline-primary w-100"
                                onclick="return checkPtaxFormat();">
                            <i class="ti ti-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light" id="ptaxFilterReset">Reset</button>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
        </div>
    </div>
</div>

<style>
/* Premium Alert Styles (Visible by default) */
.premium-warning-alert {
    background: #fffdf5;
    border-left: 4px solid #f59e0b !important;
    border: 1px solid #ffeeba;
}
.step-num-badge-new {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}

/* Custom Table Styles */
.bg-light-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.custom-list-table th {
    font-weight: 600;
    color: #475569;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.custom-list-table td {
    padding: 14px 12px !important;
}
.custom-list-table tbody tr {
    transition: background-color 0.2s ease;
}
.custom-list-table tbody tr:hover {
    background-color: #f8fafc;
}
.btn-action-detail {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    background: rgba(79, 70, 229, 0.08);
    border: none;
    transition: all 0.2s;
}
.btn-action-detail:hover {
    color: #ffffff;
    background: #4f46e5;
}

/* Custom Badge Styling */
.badge-pill-custom {
    padding: 4px 12px;
    border-radius: 50rem;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-pill-resolved {
    background: rgba(16, 185, 129, 0.08);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.15);
}

.font-12 {
    font-size: 12px;
}
.font-14 {
    font-size: 14px;
}
.w-30 {
    width: 30%;
}
</style>

<script>
    // ============================================================
    // Period dropdown helpers (same pattern as Payroll-reports)
    // ============================================================
    const PTAX_MONTHS = ['January','February','March','April','May','June',
                         'July','August','September','October','November','December'];

    function prevMonthIdx() {
        const d = new Date();
        d.setMonth(d.getMonth() - 1);
        return d.getMonth(); // 0-based
    }

    function buildPtaxPeriodOptions(type) {
        let html = '';
        const prev = prevMonthIdx();
        if (type === 'monthly') {
            PTAX_MONTHS.forEach((m, i) => {
                html += `<option value="${m}" ${i === prev ? 'selected' : ''}>${m}</option>`;
            });
        } else if (type === 'quarterly') {
            [['Q1','Q1 (Apr–Jun)'],['Q2','Q2 (Jul–Sep)'],['Q3','Q3 (Oct–Dec)'],['Q4','Q4 (Jan–Mar)']].forEach(([v,l]) => {
                html += `<option value="${v}">${l}</option>`;
            });
        } else if (type === 'half-yearly') {
            html = `<option value="H1">H1 (Apr–Sep)</option><option value="H2">H2 (Oct–Mar)</option>`;
        } else {
            html = `<option value="full">Full Year</option>`;
        }
        return html;
    }

    function renderPtaxFilter() {
        const sel  = document.getElementById('ptax_filter_type');
        const type = sel ? sel.value : 'monthly';
        const period = document.getElementById('ptax_filter_period');
        if (!period) return;

        let html = '';
        const prev = prevMonthIdx();

        if (type === 'monthly') {
            PTAX_MONTHS.forEach((m, i) => {
                html += `<option value="${m}"${i === prev ? ' selected' : ''}>${m}</option>`;
            });
            period.style.display = '';
        } else if (type === 'quarterly') {
            [['Q1','Q1 (Apr–Jun)'],['Q2','Q2 (Jul–Sep)'],['Q3','Q3 (Oct–Dec)'],['Q4','Q4 (Jan–Mar)']].forEach(([v,l]) => {
                html += `<option value="${v}">${l}</option>`;
            });
            period.style.display = '';
        } else if (type === 'half-yearly') {
            html = `<option value="H1">H1 (Apr–Sep)</option><option value="H2">H2 (Oct–Mar)</option>`;
            period.style.display = '';
        } else {
            html = `<option value="full">Full Year</option>`;
            period.style.display = 'none';
        }

        period.innerHTML = html;
    }

    // ============================================================
    // Load PT Summary via AJAX (reuses payroll.ptax.summary route)
    // ============================================================
    function loadPtaxSummary() {
        const fy   = $('#ptax_fy').val();
        const type = $('#ptax_filter_type').val();
        const per  = type === 'yearly' ? '' : $('#ptax_filter_period').val();

        $('#ptaxSummaryBody').html(
            '<tr><td colspan="9" class="text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>'
        );

        $.get('{{ route("payroll.ptax.summary") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#ptaxSummaryBody').html(
                    '<tr><td colspan="9" class="text-center text-muted py-4">No P-Tax applicable records found for selected period.</td></tr>'
                );
                return;
            }

            let html       = '';
            let totalEmp   = 0;
            let totalGross = 0;
            let totalPtax  = 0;
            let idx        = 1;

            data.forEach(r => {
                const empCount = parseInt(r.employee_count      || 0);
                const gross    = parseFloat(r.total_gross_salary || 0);
                const ptax     = parseFloat(r.total_ptax        || 0);
                const period   = (r.month_name || '') + (r.financial_year ? ' ' + r.financial_year : '');

                totalEmp   += empCount;
                totalGross += gross;
                totalPtax  += ptax;

                html += `<tr>
                    <td class="text-end ps-4 text-muted">${idx++}</td>
                    <td class="fw-bold text-dark">${r.reg_no || '—'}</td>
                    <td class="fw-bold text-dark">${r.employee_name || '—'}</td>
                    <td>${empCount} Employee${empCount !== 1 ? 's' : ''}</td>
                    <td class="fw-semibold">₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="fw-bold text-danger">₹${ptax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="fw-bold text-success">₹${ptax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-muted">${period}</td>
                    <td class="text-center pe-4">
                        <button class="btn-action-detail view-ptax-details-btn"
                            data-reg-no="${r.reg_no || '—'}"
                            data-employer-name="${r.employee_name || '—'}"
                            data-period="${period}"
                            data-emp-count="${empCount}"
                            data-gross="${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}"
                            data-ptax="${ptax.toLocaleString('en-IN', {minimumFractionDigits:2})}"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDetailsModal"
                            title="View Details">
                            <i class="ti ti-eye f-16"></i>
                        </button>
                    </td>
                </tr>`;
            });

            // Totals footer when multiple rows
            if (data.length > 1) {
                html += `<tr class="table-light fw-bold border-top">
                    <td class="ps-4 text-end text-muted">—</td>
                    <td colspan="2" class="fw-bold text-dark">Total</td>
                    <td>${totalEmp} Employees</td>
                    <td>₹${totalGross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-danger">₹${totalPtax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-success">₹${totalPtax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td colspan="2">—</td>
                </tr>`;
            }

            $('#ptaxSummaryBody').html(html);

        }).fail(() => {
            $('#ptaxSummaryBody').html(
                '<tr><td colspan="9" class="text-center text-danger py-4">Failed to load P-Tax data. Please try again.</td></tr>'
            );
        });
    }

    // ============================================================
    // View Details modal population
    // ============================================================
    $(document).on('click', '.view-ptax-details-btn', function () {
        $('#detailRegNo').text($(this).data('reg-no'));
        $('#detailEmployerName').text($(this).data('employer-name'));
        $('#detailPeriod').text($(this).data('period'));
        $('#detailEmpCount').text($(this).data('emp-count'));
        $('#detailGrossSalary').text('₹ ' + $(this).data('gross'));
        $('#detailPtDeduction').text('₹ ' + $(this).data('ptax'));
        $('#detailPtPayable').text('₹ ' + $(this).data('ptax'));
    });

    // ============================================================
    // Export helpers
    // ============================================================
    function exportPtaxPDF() {
        const table = document.getElementById('ptaxSummaryTable');
        if (!table) return;
        const { jsPDF } = window.jspdf;
        if (!jsPDF) { alert('jsPDF library not loaded.'); return; }
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        doc.setFontSize(13); doc.setFont('helvetica','bold');
        doc.text('Professional Tax (P-Tax) Summary', 14, 14);
        doc.setFontSize(9); doc.setFont('helvetica','normal');
        doc.text('FY: ' + $('#ptax_fy').val() + '   Generated: ' + new Date().toLocaleDateString('en-IN'), 14, 21);

        const headers = [['#','Registration No','Employer Name','Employee Count','Gross Salary','PT Deduction','Total PT Payable','Period']];
        const body = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length >= 8 && cells[0].textContent.trim() !== '—') {
                body.push([...cells].slice(0, 8).map(td => td.innerText.trim()));
            }
        });
        if (!body.length) { alert('No data to export.'); return; }
        doc.autoTable({ head: headers, body, startY: 26,
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [66, 47, 144], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245,245,250] },
            margin: { left: 14, right: 14 }
        });
        doc.save('PTax_Summary_' + $('#ptax_fy').val() + '.pdf');
    }

    function exportPtaxExcel() {
        const table = document.getElementById('ptaxSummaryTable');
        if (!table || typeof XLSX === 'undefined') { alert('XLSX library not loaded.'); return; }
        const fy = $('#ptax_fy').val();
        const wsData = [
            ['Professional Tax (P-Tax) Summary'],
            ['Financial Year: ' + fy, '', '', '', '', '', '', 'Generated: ' + new Date().toLocaleDateString('en-IN')],
            [],
            ['#','Registration No','Employer Name','Employee Count','Gross Salary','PT Deduction','Total PT Payable','Period']
        ];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length >= 8 && cells[0].textContent.trim() !== '—') {
                wsData.push([...cells].slice(0, 8).map(td => td.innerText.trim()));
            }
        });
        if (wsData.length <= 4) { alert('No data to export.'); return; }
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        ws['!cols'] = [5,20,25,18,18,16,18,20].map(w => ({ wch: w }));
        XLSX.utils.book_append_sheet(wb, ws, 'PTax Summary');
        XLSX.writeFile(wb, 'PTax_Summary_' + fy + '.xlsx');
    }

    // ============================================================
    // Offcanvas period toggle (unchanged)
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        // Init table filter dropdowns
        renderPtaxFilter();
        // Auto-load on page ready
        loadPtaxSummary();

        const initPtaxPeriodToggle = function() {
            const periodRadios = document.querySelectorAll('input[name="ptaxPeriodType"]');
            const monthGroup   = document.getElementById('filterPtaxMonthGroup');
            const quarterGroup = document.getElementById('filterPtaxQuarterGroup');
            const yearGroup    = document.getElementById('filterPtaxYearGroup');
            const resetButton  = document.getElementById('ptaxFilterReset');
            const form         = document.getElementById('ptaxFilterForm');

            if (!periodRadios.length || !monthGroup || !quarterGroup || !yearGroup || !form) return;

            const toggleGroups = function() {
                const val = (document.querySelector('input[name="ptaxPeriodType"]:checked') || {}).value || 'month';
                monthGroup.classList.toggle('d-none',   val !== 'month');
                quarterGroup.classList.toggle('d-none', val !== 'quarter');
                yearGroup.classList.toggle('d-none',    val !== 'year');
            };

            periodRadios.forEach(r => r.addEventListener('change', toggleGroups));

            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    form.reset();
                    setTimeout(toggleGroups, 50);
                });
            }
            toggleGroups();
        };

        initPtaxPeriodToggle();

        const offcanvasEl = document.getElementById('ptaxFilterOffcanvas');
        if (offcanvasEl) {
            offcanvasEl.addEventListener('shown.bs.offcanvas', initPtaxPeriodToggle);
        }

        // Re-load table when FY changes
        $('#ptax_fy, #ptax_filter_type').on('change', function() {
            renderPtaxFilter();
        });
    });

    function checkPtaxFormat() {
        const downloadFormat = document.getElementById('ptaxDownloadFormat').value;
        if (!downloadFormat) {
            alert('Please select a download format (PDF or Excel)');
            return false;
        }
        return true;
    }
</script>
@endsection
