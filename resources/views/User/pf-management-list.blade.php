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
                        <li class="breadcrumb-item active" aria-current="page">Provident Fund (PF / EPF)</li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <div class="page-header-title">
                        <h2 class="mb-0 text-dark fw-bold">Provident Fund (PF / EPF)</h2>
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <a href="{{ route('payroll.payslip_update') }}#pf-section" class="btn btn-warning shadow">
                        PF Challan Update
                    </a>
					<button class="btn btn-outline-primary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#aboutPFModal">
                        <i class="ti ti-info-circle f-16"></i> About PF Registration
                    </button>
                    <a href="#pfFilterOffcanvas" class="btn btn-outline-secondary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#pfFilterOffcanvas" aria-controls="pfFilterOffcanvas">
                        <i class="ti ti-filter f-16"></i> Summary of Return
                    </a>
                    <a href="https://unifiedportal-emp.epfindia.gov.in" target="_blank" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm">
                        <i class="ti ti-credit-card f-16"></i> PF / EPF Payment
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
                                <h5 class="mb-0 fw-bold text-dark">Steps for payment of EPF:</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <ol class="list-unstyled mb-0">
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">1</span>
                                            <span>Login to <a href="https://unifiedportal-emp.epfindia.gov.in" target="_blank" class="fw-semibold text-primary text-decoration-none">https://unifiedportal-emp.epfindia.gov.in</a> or click the "PF /EPF Payment" button above.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">2</span>
                                            <span>Enter Establishment ID, username, password and Captcha code.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">3</span>
                                            <span>Click on "Payments" from Dashboard and go to "ECR (Electronic Challan cum Return)".</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">4</span>
                                            <span>Select wage month and return type.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">5</span>
                                            <span>Download ECR template from portal.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 mb-md-0 text-dark font-14">
                                            <span class="step-num-badge-new">6</span>
                                            <span>Upload the MC Excel file filled with contribution details.</span>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-6">
                                    <ol class="list-unstyled mb-0">
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">7</span>
                                            <span>Click on "Submit" button.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">8</span>
                                            <span>Check all the details and contribution amount, then click on final submit button.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">9</span>
                                            <span>Click on "Generate Challan".</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">10</span>
                                            <span>Click on "View" button next to challan.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">11</span>
                                            <span>Select contribution amount and mode of payment.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 text-dark font-14">
                                            <span class="step-num-badge-new">12</span>
                                            <span>Click on "Submit" to complete payment.</span>
                                        </li>
                                    </ol>
                                </div>
                            </div>
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
                        <select id="pf_fy" class="form-select form-select-sm" style="width:150px;">
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
                        <select id="pf_filter_type" class="form-select form-select-sm" style="width:140px;">
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="half-yearly">Half-Yearly</option>
                            <option value="yearly">Full Year</option>
                        </select>
                        <select id="pf_filter_period" class="form-select form-select-sm" style="width:160px;"></select>
                        <button class="btn btn-primary btn-sm px-4" id="pf_load_btn">
                            <i class="ti ti-refresh me-1"></i> Load
                        </button>
                    </div>
                    {{-- Export buttons --}}
                    <div class="d-flex gap-2">
                        <button onclick="exportPfPDF()" class="btn btn-sm px-3 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="background:#ffeef0;color:#dc3545;">
                            <i class="ti ti-file-type-pdf f-16"></i> PDF
                        </button>
                        <button onclick="exportPfExcel()" class="btn btn-sm px-3 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="background:#e8fadf;color:#198754;">
                            <i class="ti ti-file-spreadsheet f-16"></i> Excel
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table tbl-product m-0 custom-list-table align-middle" id="pfPageTable">
                            <thead>
                                <tr class="bg-light-header">
                                    <th class="text-end py-3 ps-4" style="width:50px;">#</th>
                                    <th class="py-3">UAN</th>
                                    <th class="py-3">Member Name</th>
                                    <th class="py-3">Gross Wages</th>
                                    <th class="py-3">EPF Wages</th>
                                    <th class="py-3">EPS Wages</th>
                                    <th class="py-3">EDLI Wages</th>
                                    <th class="py-3">Employee EPF (12%)</th>
                                    <th class="py-3">Employer EPS (8.33%)</th>
                                    <th class="py-3">EPF Diff (Employer)</th>
                                    <th class="py-3">NCP Days</th>
                                    <th class="py-3">Refund of Advances</th>
                                    <th class="text-center py-3 pe-4" style="width:90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pfPageBody">
                                <tr>
                                    <td colspan="13" class="text-center text-muted py-4">
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

<!-- Modal -->
<div class="modal custom-modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark font-20">PF Filing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">PF Month</label>
                        <p class="text-dark fw-semibold mb-0" id="pf_month"></p>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">Employee Name</label>
                        <p class="text-dark fw-semibold mb-0" id="emp_name_id"></p>
                    </div>
                    <div class="col-12"><hr class="my-1 border-light"></div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">UAN</label>
                        <p class="text-dark fw-semibold mb-0" id="uan"></p>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">EPF Wages</label>
                        <p class="text-dark fw-semibold mb-0" id="basic_salary"></p>
                    </div>
                    <div class="col-12"><hr class="my-1 border-light"></div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">Employee EPF (12%)</label>
                        <p class="text-primary fw-bold mb-0" id="emp_pf"></p>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">Employer EPF Diff</label>
                        <p class="text-success fw-bold mb-0" id="employer_pf"></p>
                    </div>
                    <div class="col-12"><hr class="my-1 border-light"></div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">Employer EPS (8.33%)</label>
                        <p class="text-dark fw-semibold mb-0" id="pension_pf"></p>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">NCP Days</label>
                        <p class="text-dark fw-bold mb-0" id="total_pf"></p>
                    </div>
                    <div class="col-12"><hr class="my-1 border-light"></div>
                    <div class="col-12">
                        <label class="fw-bold text-dark font-12 uppercase text-muted">PF Challan Reference (TRRN)</label>
                        <p class="text-dark fw-bold mb-0" id="challan_no"></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- About PF Registration Modal -->
<div class="modal fade" id="aboutPFModal" tabindex="-1" aria-labelledby="aboutPFModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="avtar avtar-s btn-light-primary rounded-circle"><i class="ti ti-info-circle f-18"></i></span>
                    <h5 class="modal-title fw-bold text-dark font-20" id="aboutPFModalLabel">About PF (Employees' Provident Fund) Registration</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-dark font-14">

                {{-- SECTION 1: WHY REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-help f-18"></i> Why Required</h6>
                    <p class="text-secondary mb-2">PF is governed by Employees' Provident Fund Organization under EPF & MP Act.</p>
                    <p class="text-secondary mb-1">Purpose:</p>
                    <ul class="list-unstyled ps-3 mb-3">
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16 mt-0.5"></i> Retirement savings</li>
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16 mt-0.5"></i> Pension benefits</li>
                        <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-success f-16 mt-0.5"></i> Employee social security</li>
                    </ul>

                    <h6 class="fw-bold text-dark mb-1 font-14">Applicability</h6>
                    <p class="text-secondary mb-0">Generally mandatory when **Employee count reaches 20 or more**. Certain establishments may voluntarily register earlier.</p>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 2: RESPONSIBILITIES & CONTRIBUTION --}}
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-briefcase f-18"></i> Employer Responsibilities</h6>
                            <ul class="list-unstyled ps-1 mb-0">
                                <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> PF deduction from salary</li>
                                <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> Employer contribution</li>
                                <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> Monthly PF return filing</li>
                                <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> UAN management</li>
                                <li class="d-flex align-items-start gap-2 mb-1.5"><i class="ti ti-circle-check text-primary f-16 mt-0.5"></i> PF challan payment</li>
                            </ul>
                        </div>
                        <div class="col-md-5">
                            <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-percentage f-18"></i> Standard Contribution</h6>
                            <div class="card bg-light border-0 rounded-3 p-3 mb-0 shadow-none">
                                <div class="mb-2">
                                    <span class="small text-muted d-block">Employee Portion</span>
                                    <strong class="text-dark font-18">12%</strong>
                                </div>
                                <div>
                                    <span class="small text-muted d-block">Employer Portion</span>
                                    <strong class="text-dark font-18">12% <span class="font-11 text-muted fw-normal">(split into EPF + EPS)</span></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 3: DOCUMENTS REQUIRED --}}
                <div class="mb-4">
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-file-text f-18"></i> Documents Required</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-id f-16 text-muted"></i> PAN of Company</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-award f-16 text-muted"></i> Certificate of Incorporation / Partnership</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-home f-16 text-muted"></i> Address Proof</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-wallet f-16 text-muted"></i> Cancelled Cheque</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-key f-16 text-muted"></i> DSC (Digital Signature)</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-fingerprint f-16 text-muted"></i> Aadhaar & PAN of Directors/Partners</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-user-plus f-16 text-muted"></i> Employee Joining Details</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-report-money f-16 text-muted"></i> Salary Structure</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-mail f-16 text-muted"></i> Mobile & Email</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-license f-16 text-muted"></i> Shops Registration / Trade License</li>
                                <li class="d-flex align-items-center gap-2 mb-2"><i class="ti ti-writing f-16 text-muted"></i> Specimen Signature</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr class="opacity-10 my-3">

                {{-- SECTION 4: IMPORTANT EMPLOYEE DOCUMENTS --}}
                <div>
                    <h6 class="fw-bold text-primary mb-2.5 d-flex align-items-center gap-1.5"><i class="ti ti-users f-18"></i> Important Employee Documents</h6>
                    <div class="row g-2">
                        <div class="col-6 col-sm-4 col-md-2 text-center">
                            <div class="bg-light p-2.5 rounded-3">
                                <i class="ti ti-fingerprint f-20 text-primary"></i>
                                <span class="d-block small text-dark mt-1 font-12 fw-semibold">Aadhaar</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 text-center">
                            <div class="bg-light p-2.5 rounded-3">
                                <i class="ti ti-id f-20 text-primary"></i>
                                <span class="d-block small text-dark mt-1 font-12 fw-semibold">PAN</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 text-center">
                            <div class="bg-light p-2.5 rounded-3">
                                <i class="ti ti-wallet f-20 text-primary"></i>
                                <span class="d-block small text-dark mt-1 font-12 fw-semibold">Bank Account</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 text-center">
                            <div class="bg-light p-2.5 rounded-3">
                                <i class="ti ti-device-mobile f-20 text-primary"></i>
                                <span class="d-block small text-dark mt-1 font-12 fw-semibold">Mobile No</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 text-center">
                            <div class="bg-light p-2.5 rounded-3">
                                <i class="ti ti-calendar-event f-20 text-primary"></i>
                                <span class="d-block small text-dark mt-1 font-12 fw-semibold">DOJ</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2 text-center">
                            <div class="bg-light p-2.5 rounded-3">
                                <i class="ti ti-cash f-20 text-primary"></i>
                                <span class="d-block small text-dark mt-1 font-12 fw-semibold">Salary Breakup</span>
                            </div>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="pfFilterOffcanvas" aria-labelledby="pfFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold text-dark" id="pfFilterOffcanvasLabel">Filter PF Filing Records</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="pfFilterForm">
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterPfName" class="form-label fw-semibold text-dark">Employee Name</label>
                    <input type="text" class="form-control" id="filterPfName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterPfEmployeeId" class="form-label fw-semibold text-dark">Employee ID</label>
                    <input type="text" class="form-control" id="filterPfEmployeeId" name="employee_id" placeholder="Filter by employee ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3 fw-bold text-dark">Filter By Period</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfPeriodType" id="pfPeriodMonth" value="month" checked>
                        <label class="form-check-label" for="pfPeriodMonth">Monthly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfPeriodType" id="pfPeriodQuarter" value="quarter">
                        <label class="form-check-label" for="pfPeriodQuarter">Quarterly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfPeriodType" id="pfPeriodYear" value="year">
                        <label class="form-check-label" for="pfPeriodYear">Yearly</label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12" id="filterPfMonthGroup">
                    <label for="filterPfMonth" class="form-label fw-semibold text-dark">Select Month</label>
                    <select class="form-select" id="filterPfMonth" name="month">
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
                <div class="col-12 d-none" id="filterPfQuarterGroup">
                    <label for="filterPfQuarter" class="form-label fw-semibold text-dark">Select Quarter</label>
                    <select class="form-select" id="filterPfQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Quarter 1 (Apr - Jun)</option>
                        <option value="q2">Quarter 2 (Jul - Sep)</option>
                        <option value="q3">Quarter 3 (Oct - Dec)</option>
                        <option value="q4">Quarter 4 (Jan - Mar)</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterPfYearGroup">
                    <label for="filterPfYear" class="form-label fw-semibold text-dark">Select Year</label>
                    <select class="form-select" id="filterPfYear" name="year">
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
                        <label for="pfDownloadFormat" class="form-label fw-semibold text-dark">Choose format</label>
                        <select class="form-select" id="pfDownloadFormat" name="download_format">
                            <option value="">Select format</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary w-100">
                            <i class="ti ti-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light" id="pfFilterReset">Reset</button>
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
</style>

<script>
    // ============================================================
    // Period dropdown helpers
    // ============================================================
    const PF_MONTHS = ['January','February','March','April','May','June',
                       'July','August','September','October','November','December'];

    function prevPfMonthIdx() {
        const d = new Date();
        d.setMonth(d.getMonth() - 1);
        return d.getMonth();
    }

    function buildPfPeriodOptions(type) {
        let html = '';
        const prev = prevPfMonthIdx();
        if (type === 'monthly') {
            PF_MONTHS.forEach((m, i) => {
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

    function renderPfPageFilter() {
        const type = ($('#pf_filter_type').val() || 'monthly').toLowerCase();
        const $period = $('#pf_filter_period');

        if (type === 'yearly') {
            $period.empty().append('<option value="full" selected>Full Year</option>').hide();
            return;
        }

        $period.show();
        $period.empty();

        if (type === 'monthly') {
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            const now = new Date();
            let prevIdx = now.getMonth() - 1;
            if (prevIdx < 0) prevIdx = 11;

            months.forEach(function(m, i) {
                const isSelected = (i === prevIdx);
                $period.append(new Option(m, m, isSelected, isSelected));
            });
        } else if (type === 'quarterly') {
            const quarters = [
                { val: 'Q1', text: 'Q1 (Apr–Jun)' },
                { val: 'Q2', text: 'Q2 (Jul–Sep)' },
                { val: 'Q3', text: 'Q3 (Oct–Dec)' },
                { val: 'Q4', text: 'Q4 (Jan–Mar)' }
            ];
            quarters.forEach(function(q, i) {
                const isSelected = (i === 0);
                $period.append(new Option(q.text, q.val, isSelected, isSelected));
            });
        } else if (type === 'half-yearly') {
            $period.append(new Option('H1 (Apr–Sep)', 'H1', true, true));
            $period.append(new Option('H2 (Oct–Mar)', 'H2', false, false));
        }
    }

    // ============================================================
    // Load PF data via AJAX — same route as Payroll-reports
    // ============================================================
    function loadPfPageData() {
        const fy   = $('#pf_fy').val();
        const type = $('#pf_filter_type').val();
        const per  = type === 'yearly' ? '' : $('#pf_filter_period').val();

        $('#pfPageBody').html(
            '<tr><td colspan="13" class="text-center py-4"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>'
        );

        $.get('{{ route("payroll.pf.list") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#pfPageBody').html(
                    '<tr><td colspan="13" class="text-center text-muted py-4">No EPF applicable records found for selected period.</td></tr>'
                );
                return;
            }

            let html  = '';
            let idx   = 1;
            let totalEmpPf  = 0;
            let totalEps    = 0;
            let totalDiff   = 0;

            data.forEach(r => {
                const gross    = parseFloat(r.gross_salary      || 0);
                const epfWages = parseFloat(r.epf_wages         || 0);
                const empPf    = parseFloat(r.provident_fund    || 0);
                const empEps   = parseFloat(r.employer_eps      || 0);
                const empDiff  = parseFloat(r.employer_epf_diff || 0);
                const ncp      = parseInt(r.ncp_days            || 0);
                const uan      = r.epf_no    || '—';
                const trrn     = r.pf_trrn   || 'N/A';
                const month    = r.month_name || '—';

                totalEmpPf += empPf;
                totalEps   += empEps;
                totalDiff  += empDiff;

                html += `<tr>
                    <td class="text-end ps-4 text-muted">${idx++}</td>
                    <td class="fw-bold text-dark">${uan}</td>
                    <td class="fw-bold text-dark">${r.name || '—'}</td>
                    <td>₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-primary fw-bold">₹${empPf.toFixed(2)}</td>
                    <td class="text-danger fw-semibold">₹${empEps.toFixed(2)}</td>
                    <td class="text-success fw-semibold">₹${empDiff.toFixed(2)}</td>
                    <td>${ncp}</td>
                    <td>₹0.00</td>
                    <td class="text-center pe-4">
                        <button class="btn-action-detail viewPF"
                            data-month="${month}"
                            data-name="${r.name || '—'}"
                            data-uan="${uan}"
                            data-epf-wages="${'₹' + epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}"
                            data-emp-pf="${'₹' + empPf.toFixed(2)}"
                            data-emp-diff="${'₹' + empDiff.toFixed(2)}"
                            data-eps="${'₹' + empEps.toFixed(2)}"
                            data-ncp="${ncp}"
                            data-trrn="${trrn}"
                            data-bs-toggle="modal"
                            data-bs-target="#viewDetailsModal"
                            title="View Details">
                            <i class="ti ti-eye f-16"></i>
                        </button>
                    </td>
                </tr>`;
            });

            // Totals footer
            if (data.length > 1) {
                html += `<tr class="table-light fw-bold border-top">
                    <td class="ps-4 text-end text-muted" colspan="7">Total</td>
                    <td class="text-primary">₹${totalEmpPf.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-danger">₹${totalEps.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-success">₹${totalDiff.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td colspan="3">—</td>
                </tr>`;
            }

            $('#pfPageBody').html(html);

        }).fail(() => {
            $('#pfPageBody').html(
                '<tr><td colspan="13" class="text-center text-danger py-4">Failed to load PF data. Please try again.</td></tr>'
            );
        });
    }

    // ============================================================
    // View Details modal population
    // ============================================================
    $(document).on('click', '.viewPF', function () {
        $('#pf_month').text($(this).data('month'));
        $('#emp_name_id').text($(this).data('name'));
        $('#uan').text($(this).data('uan'));
        $('#basic_salary').text($(this).data('epf-wages'));
        $('#emp_pf').text($(this).data('emp-pf'));
        $('#employer_pf').text($(this).data('emp-diff'));
        $('#pension_pf').text($(this).data('eps'));
        $('#total_pf').text($(this).data('ncp') + ' days');
        $('#challan_no').text($(this).data('trrn'));
    });

    // ============================================================
    // Export helpers
    // ============================================================
    function exportPfPDF() {
        const table = document.getElementById('pfPageTable');
        if (!table) return;
        if (typeof window.jspdf === 'undefined') { alert('jsPDF library not loaded.'); return; }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        doc.setFontSize(13); doc.setFont('helvetica', 'bold');
        doc.text('Provident Fund (PF / EPF) Summary', 14, 14);
        doc.setFontSize(9); doc.setFont('helvetica', 'normal');
        doc.text('FY: ' + $('#pf_fy').val() + '   Generated: ' + new Date().toLocaleDateString('en-IN'), 14, 21);

        const headers = [['#','UAN','Member Name','Gross Wages','EPF Wages','EPS Wages','EDLI Wages','Emp EPF (12%)','Empr EPS (8.33%)','EPF Diff','NCP Days','Refund']];
        const body = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length >= 12 && cells[0].textContent.trim() !== '—') {
                body.push([...cells].slice(0, 12).map(td => td.innerText.trim()));
            }
        });
        if (!body.length) { alert('No data to export.'); return; }
        doc.autoTable({
            head: headers, body,
            startY: 26,
            styles: { fontSize: 7, cellPadding: 1.5 },
            headStyles: { fillColor: [66, 47, 144], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 250] },
            margin: { left: 10, right: 10 }
        });
        doc.save('PF_Summary_' + $('#pf_fy').val() + '.pdf');
    }

    function exportPfExcel() {
        const table = document.getElementById('pfPageTable');
        if (!table || typeof XLSX === 'undefined') { alert('XLSX library not loaded.'); return; }
        const fy = $('#pf_fy').val();
        const wsData = [
            ['Provident Fund (PF / EPF) Summary'],
            ['Financial Year: ' + fy, '', '', '', '', '', '', '', '', '', '', 'Generated: ' + new Date().toLocaleDateString('en-IN')],
            [],
            ['#','UAN','Member Name','Gross Wages','EPF Wages','EPS Wages','EDLI Wages','Employee EPF (12%)','Employer EPS (8.33%)','EPF Difference','NCP Days','Refund of Advances']
        ];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length >= 12 && cells[0].textContent.trim() !== '—') {
                wsData.push([...cells].slice(0, 12).map(td => td.innerText.trim()));
            }
        });
        if (wsData.length <= 4) { alert('No data to export.'); return; }
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        ws['!cols'] = [5,16,22,14,14,14,14,18,18,16,10,14].map(w => ({ wch: w }));
        XLSX.utils.book_append_sheet(wb, ws, 'PF Summary');
        XLSX.writeFile(wb, 'PF_Summary_' + fy + '.xlsx');
    }

    // ============================================================
    // Offcanvas period toggle (unchanged)
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        // Init filter dropdowns and auto-load
        renderPfPageFilter();
        loadPfPageData();

        const initPfPeriodToggle = function() {
            const periodRadios = document.querySelectorAll('input[name="pfPeriodType"]');
            const monthGroup   = document.getElementById('filterPfMonthGroup');
            const quarterGroup = document.getElementById('filterPfQuarterGroup');
            const yearGroup    = document.getElementById('filterPfYearGroup');
            const resetButton  = document.getElementById('pfFilterReset');
            const form         = document.getElementById('pfFilterForm');

            if (!periodRadios.length || !monthGroup || !quarterGroup || !yearGroup || !form) return;

            const toggleGroups = function() {
                const val = (document.querySelector('input[name="pfPeriodType"]:checked') || {}).value || 'month';
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

        initPfPeriodToggle();

        const offcanvasEl = document.getElementById('pfFilterOffcanvas');
        if (offcanvasEl) {
            offcanvasEl.addEventListener('shown.bs.offcanvas', initPfPeriodToggle);
        }

        // Re-render and reload data on filter changes
        $('#pf_fy').on('change', function() {
            loadPfPageData();
        });

        $('#pf_filter_type').on('change', function() {
            renderPfPageFilter();
            loadPfPageData();
        });

        $('#pf_filter_period').on('change', function() {
            loadPfPageData();
        });

        $('#pf_load_btn').on('click', function() {
            loadPfPageData();
        });
    });

    // Offcanvas download button — builds and submits form
    $('#pfFilterForm button').on('click', function () {
        $('<form>', {
            action: "{{ route('download.pf.filing') }}",
            method: 'POST'
        }).append(
            $('<input>', { type: 'hidden', name: '_token',         value: "{{ csrf_token() }}" }),
            $('<input>', { type: 'hidden', name: 'name',           value: $('#filterPfName').val() }),
            $('<input>', { type: 'hidden', name: 'employee_id',    value: $('#filterPfEmployeeId').val() }),
            $('<input>', { type: 'hidden', name: 'pfPeriodType',   value: $('input[name="pfPeriodType"]:checked').val() }),
            $('<input>', { type: 'hidden', name: 'month',          value: $('#filterPfMonth').val() }),
            $('<input>', { type: 'hidden', name: 'quarter',        value: $('#filterPfQuarter').val() }),
            $('<input>', { type: 'hidden', name: 'year',           value: $('#filterPfYear').val() }),
            $('<input>', { type: 'hidden', name: 'download_format',value: $('#pfDownloadFormat').val() })
        ).appendTo('body').submit().remove();
    });
</script>

@endsection
