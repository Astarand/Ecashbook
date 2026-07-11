@extends('App.Layout')

@section('container')
<style>
    .custom-action-btn-pdf {
        background-color: #ffeef0 !important;
        color: #dc3545 !important;
    }
    .custom-action-btn-pdf:hover {
        background-color: #dc3545 !important;
        color: #ffffff !important;
    }
    .custom-action-btn-excel {
        background-color: #e8fadf !important;
        color: #198754 !important;
    }
    .custom-action-btn-excel:hover {
        background-color: #198754 !important;
        color: #ffffff !important;
    }
    .custom-action-btn-print {
        background-color: #e7f3ff !important;
        color: #0d6efd !important;
    }
    .custom-action-btn-print:hover {
        background-color: #0d6efd !important;
        color: #ffffff !important;
    }

    /* Datatable style normalization */
    .datatable-top {
        padding: 10px 10px 15px 10px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 10px !important;
    }
    .datatable-bottom {
        padding: 15px 10px 10px 10px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 10px !important;
    }
    .datatable-search {
        float: none !important;
        margin-left: 0 !important;
        position: relative !important;
    }
    .datatable-search input {
        border-radius: 8px !important;
        border: 1px solid #e3e6f6 !important;
        padding: 8px 16px 8px 38px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: 14px center !important;
        background-size: 14px !important;
        font-size: 13.5px !important;
        width: 220px !important;
    }
    .datatable-selector {
        border-radius: 8px !important;
        border: 1px solid #e3e6f6 !important;
        padding: 6px 32px 6px 12px !important;
        font-size: 13.5px !important;
    }
    .datatable-pagination a {
        border-radius: 6px !important;
        margin: 0 2px !important;
        font-size: 13.5px !important;
    }
    .datatable-container {
        border-top: 1px solid #f1f3f9 !important;
        border-bottom: 1px solid #f1f3f9 !important;
    }
    .datatable-search .icon-search {
        display: none !important;
    }
</style>
<div class="pc-content">
    {{-- PAGE HEADER --}}
    <div class="page-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center text-white">
                        <div class="d-flex align-items-center">
                            <div class="header-icon-box bg-white bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="ph-duotone ph-users-three fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">Payroll Reports Dashboard</h3>
                                <p class="mb-0 opacity-75 small">Comprehensive management dashboard for employee payroll register, attendance, statutory summaries & audit sheets</p>
                            </div>
                        </div>
                        {{-- Controls --}}
                        <div class="d-flex gap-2">
                            <select id="payrollMonth"
                                class="form-select form-select-sm border-0 text-dark fw-bold bg-white bg-opacity-90 ps-3 pe-4"
                                style="width: auto; min-width: 130px; border-radius: 8px;">
                            </select>

                            <select id="payrollFY"
                                class="form-select form-select-sm border-0 text-dark fw-bold bg-white bg-opacity-90 ps-3 pe-4"
                                style="width: auto; min-width: 140px; border-radius: 8px;">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DASHBOARD SUMMARY CARDS --}}
    <div class="row mb-4">
        {{-- Card 1: Active Employees --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">Total Active Employees</span>
                        <i class="ph-duotone ph-users text-primary fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="totalActiveEmployees">0</h3>
                    <p class="text-muted small mb-0"><span class="text-success"><i class="ph ph-trend-up"></i> +2</span> compared to last month</p>
                </div>
            </div>
        </div>

        {{-- Card 2: Gross Salary --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">Total Gross Salary</span>
                        <i class="ph-duotone ph-currency-inr text-info fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="grossSalary">₹0</h3>
                    <p class="text-muted small mb-0">Calculated for active employees</p>
                </div>
            </div>
        </div>

        {{-- Card 3: Net Payable --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">Net Salary Payable</span>
                        <i class="ph-duotone ph-wallet text-success fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="netSalary">₹0</h3>
                    <p class="text-muted small mb-0">Calculated for active employees</p>
                </div>
            </div>
        </div>

        {{-- Card 4: PF Liability --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">PF Liability</span>
                        <i class="ph-duotone ph-coins text-warning fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="pfLiability">₹0</h3>
                    <p class="text-muted small mb-0">Due Date: 15th of next month</p>
                </div>
            </div>
        </div>

        {{-- Card 5: ESI Liability --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">ESI Liability</span>
                        <i class="ph-duotone ph-first-aid text-danger fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="esiLiability">₹0</h3>
                    <p class="text-muted small mb-0">Due Date: 15th of next month</p>
                </div>
            </div>
        </div>

        {{-- Card 6: PT Liability --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">PT Liability</span>
                        <i class="ph-duotone ph-bank text-secondary fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="ptLiability">₹0</h3>
                    <p class="text-muted small mb-0">Professional tax slab deductions</p>
                </div>
            </div>
        </div>

        {{-- Card 7: TDS Liability --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">TDS Liability</span>
                        <i class="ph-duotone ph-receipt text-indigo fs-3" style="color: #6610f2 !important;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="tdsLiability">₹0</h3>
                    <p class="text-muted small mb-0">IT Section 192 compliance</p>
                </div>
            </div>
        </div>

        {{-- Card 8: Payment Status Summary --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary small fw-bold uppercase-label">Payment Status</span>
                            <span class="badge bg-light-success text-success px-2.5 py-1 rounded-pill small">Processed</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1" id="paymentSummary">Paid: 0 / 0</h4>
                    </div>
                    <div class="pt-2 border-top mt-2 d-flex justify-content-between align-items-center">
                        <span class="text-success small fw-semibold" id="paidCount"><i class="ph ph-check-circle"></i> 0 Paid</span>
                        <span class="text-danger small fw-semibold" id="unpaidCount"><i class="ph ph-x-circle"></i> 0 Unpaid</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABBED INTERFACE --}}
    <div class="row justify-content-center mb-4">
        <div class="col-12">
            <div class="card border shadow-none mb-3">
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-justified" id="payrollTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#register" data-bs-toggle="tab" class="nav-link active py-3 d-flex align-items-center justify-content-center gap-2" id="register-tab" role="tab" aria-controls="register" aria-selected="true">
                                <i class="ph-duotone ph-notebook fs-5"></i>
                                <span class="d-none d-sm-inline">Payroll Register</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#attendance" data-bs-toggle="tab" class="nav-link py-3 d-flex align-items-center justify-content-center gap-2" id="attendance-tab" role="tab" aria-controls="attendance" aria-selected="false">
                                <i class="ph-duotone ph-calendar-check fs-5"></i>
                                <span class="d-none d-sm-inline">Attendance & Leave</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#summaries" data-bs-toggle="tab" class="nav-link py-3 d-flex align-items-center justify-content-center gap-2" id="summaries-tab" role="tab" aria-controls="summaries" aria-selected="false">
                                <i class="ph-duotone ph-file-arrow-down fs-5"></i>
                                <span class="d-none d-sm-inline">Report Downloads & Summaries</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3.5">
                    <div class="tab-content" id="payrollTabContent">

                        {{-- TAB 1: PAYROLL REGISTER --}}
                        <div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center"><i class="ph-duotone ph-table text-primary me-2 fs-5"></i> Payroll Register - <span class="active-month-text text-primary">July 2026</span></h5>
                            <div class="table-responsive border rounded-3">
                                <table class="table tbl-product m-0 custom-list-table align-middle" id="payrollRegisterTable">
                                    <thead class="bg-light">
                                        <tr class="text-secondary small fw-bold">
                                            <th class="ps-3 py-3">Employee ID</th>
                                            <th class="py-3">Employee Name</th>
                                            <th class="py-3">Designation</th>
                                            <th class="py-3">DOJ</th>
                                            <th class="py-3">Gross Salary</th>
                                            <th class="py-3">Net Salary</th>
                                            <th class="py-3">PF Amount</th>
                                            <th class="py-3">ESI Amount</th>
                                            <th class="py-3">P-Tax</th>
                                            <th class="py-3">TDS Amount</th>
                                            <th class="py-3">Advance</th>
                                            <th class="py-3">Loan Ded.</th>
                                            <th class="pe-3 py-3">Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payrollRegisterBody">
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Action Buttons for Register Tab --}}
                            <div class="d-flex justify-content-end gap-3 align-items-center mt-3 pt-3 border-top">
                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                </button>
                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                </button>
                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                </button>
                            </div>
                        </div>

                        {{-- TAB 2: ATTENDANCE & LEAVE --}}
                        <div class="tab-pane fade" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                            <h5 class="fw-bold mb-3 text-dark d-flex align-items-center"><i class="ph-duotone ph-calendar text-primary me-2 fs-5"></i> Attendance & Leave Register - <span class="active-month-text text-primary">July 2026</span></h5>
                            <div class="table-responsive border rounded-3">
                                <table class="table tbl-product m-0 custom-list-table align-middle" id="payrollAttendance">
                                {{-- <table class="table tbl-product m-0 custom-list-table align-middle" id="pc-dt-attendance"> --}}
                                    <thead class="bg-light">
                                        <tr class="text-secondary small fw-bold">
                                            <th class="ps-3 py-3">Employee ID</th>
                                            <th class="py-3">Employee Name</th>
                                            <th class="py-3">Attendance (Days)</th>
                                            <th class="py-3">Absent (Days)</th>
                                            <th class="py-3">Leave (Days)</th>
                                            <th class="py-3">Late (Hours)</th>
                                            <th class="py-3">Overtime (Hours)</th>
                                            <th class="pe-3 py-3">WFH (Days)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceRegisterBody">
                                        
                                    </tbody>
                                </table>
                            </div>

                            {{-- Action Buttons for Attendance Tab --}}
                            <div class="d-flex justify-content-end gap-3 align-items-center mt-3 pt-3 border-top">
                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                </button>
                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                </button>
                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                </button>
                            </div>
                        </div>

                        {{-- TAB 3: SUMMARIES & DOWNLOADS --}}
                        <div class="tab-pane fade" id="summaries" role="tabpanel" aria-labelledby="summaries-tab">
                            <div class="row">
                                {{-- Left panel: Report selection --}}
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <!-- Financial Year -->
                                    <select class="form-select form-select-sm" id="financial_year" style="width:150px;">
                                        @php
                                            $currentYear = date('Y');
                                            $currentMonth = date('n');
                                            $fyStart = ($currentMonth >= 4) ? $currentYear : $currentYear - 1;
                                        @endphp

                                        @for($i = 0; $i < 5; $i++)
                                            @php
                                                $start = $fyStart - $i;
                                                $end = $start + 1;
                                            @endphp

                                            <option value="{{ $start }}-{{ $end }}" {{ $i==0 ? 'selected' : '' }}>
                                                FY {{ $start }}-{{ $end }}
                                            </option>
                                        @endfor
                                    </select>

                                    <!-- Summary Type -->
                                    <select class="form-select form-select-sm" id="summary_type" style="width:130px;">
                                        <option value="monthly" selected>Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>

                                    <!-- Month -->
                                    <select class="form-select form-select-sm" id="month" style="width:130px;">
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July" selected>July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                    </select>

                                    <!-- Quarter -->
                                    <select class="form-select form-select-sm d-none" id="quarter" style="width:150px;">
                                        <option value="Q1">Q1 (Apr-Jun)</option>
                                        <option value="Q2" selected>Q2 (Jul-Sep)</option>
                                        <option value="Q3">Q3 (Oct-Dec)</option>
                                        <option value="Q4">Q4 (Jan-Mar)</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                                        <h6 class="fw-bold mb-0 text-dark text-uppercase small-text">
                                            <i class="ph-duotone ph-folders text-primary me-2"></i>
                                            Available Summaries
                                        </h6>
                                    </div>

                                    <div class="list-group rounded-3 shadow-none border" id="summarySelectorList">
                                        <button onclick="selectSummary('salary_sheet')" class="list-group-item list-group-item-action active py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-file-text fs-4 text-primary"></i> Salary Sheet Audit View
                                            </span>
                                            <span class="rounded-circle bg-primary" style="width: 8px; height: 8px;"></span>
                                        </button>
                                        <button onclick="selectSummary('pf_summary')" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-coins fs-4 text-warning"></i> PF Liability Summary
                                            </span>
                                            <span class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></span>
                                        </button>
                                        <button onclick="selectSummary('esi_summary')" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-first-aid fs-4 text-danger"></i> ESI Contribution Summary
                                            </span>
                                            <span class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></span>
                                        </button>
                                        <button onclick="selectSummary('pt_summary')" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-bank fs-4 text-secondary"></i> Professional Tax (P-Tax) Summary
                                            </span>
                                            <span class="rounded-circle bg-secondary" style="width: 8px; height: 8px;"></span>
                                        </button>
                                        <button onclick="selectSummary('tds_summary')" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-receipt fs-4 text-indigo"></i> TDS Compliance Summary
                                            </span>
                                            <span class="rounded-circle bg-indigo" style="width: 8px; height: 8px; background-color: #6610f2 !important;"></span>
                                        </button>
                                        <button onclick="selectSummary('lwf_summary')" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-umbrella fs-4 text-success"></i> Labour Welfare Fund (LWF)
                                            </span>
                                            <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                                        </button>
                                        <button onclick="selectSummary('gratuity_summary')" class="list-group-item list-group-item-action py-3 d-flex align-items-center justify-content-between fw-semibold">
                                            <span class="d-flex align-items-center gap-2">
                                                <i class="ph-duotone ph-hand-heart fs-4 text-info"></i> Gratuity Payment
                                            </span>
                                            <span class="rounded-circle bg-info" style="width: 8px; height: 8px;"></span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Right panel: Active Summary details & visual preview --}}
                                <div class="col-md-8">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" id="printReportContainer">
                                        {{-- Report Official Header --}}
                                        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
                                            <div class="d-flex align-items-center gap-3">
                                                {{-- Mock Company Logo --}}
                                                <div class="bg-gradient-primary text-white rounded-3 p-2 fw-bold d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; font-size: 1.2rem;">
                                                    EC
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold text-dark mb-0 uppercase-label" id="reportCompanyHeader">E-Cashbook Systems Ltd</h5>
                                                    <span class="text-muted small">Generated on: <span id="generationDate">05-07-2026</span> &nbsp;·&nbsp; Month: <span class="active-month-text text-primary fw-bold">July 2026</span></span>
                                                </div>
                                            </div>
                                            <span class="badge bg-light-primary text-primary fw-bold px-3 py-1.5 rounded-pill small uppercase-label" id="summaryBadge">Salary Sheet Summary</span>
                                        </div>

                                        {{-- DYNAMIC PREVIEW DATA TABLES --}}

                                        {{-- Sub-table 1: Salary Sheet --}}
                                        <div id="summary_salary_sheet" class="summary-table-section">
                                            <div class="table-responsive border rounded-3 mb-3">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="salarySheetTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">EMP ID</th>
                                                            <th class="py-2.5">Employee Name</th>
                                                            <th class="py-2.5">Bank Name</th>
                                                            <th class="py-2.5">Account Number</th>
                                                            <th class="py-2.5">IFSC Code</th>
                                                            <th class="py-2.5">Net Salary</th>
                                                            <th class="py-2.5">Month</th>
                                                            <th class="py-2.5">Payment Date</th>
                                                            <th class="py-2.5">UTR / Ref</th>
                                                            <th class="pe-3 py-2.5">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP001</td>
                                                            <td class="fw-bold">Rahul Verma</td>
                                                            <td>HDFC Bank</td>
                                                            <td>502000452319</td>
                                                            <td>HDFC0000120</td>
                                                            <td class="fw-bold text-dark">₹1,07,400</td>
                                                            <td>July 2026</td>
                                                            <td>01-07-2026</td>
                                                            <td>UTR74523190</td>
                                                            <td><span class="badge bg-light-success text-success px-2 py-0.5 small rounded">Paid</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP002</td>
                                                            <td class="fw-bold">Sneha Iyer</td>
                                                            <td>ICICI Bank</td>
                                                            <td>000401562391</td>
                                                            <td>ICIC0000004</td>
                                                            <td class="fw-bold text-dark">₹84,850</td>
                                                            <td>July 2026</td>
                                                            <td>01-07-2026</td>
                                                            <td>UTR98542310</td>
                                                            <td><span class="badge bg-light-success text-success px-2 py-0.5 small rounded">Paid</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP003</td>
                                                            <td class="fw-bold">David Miller</td>
                                                            <td>SBI</td>
                                                            <td>31234918239</td>
                                                            <td>SBIN0000212</td>
                                                            <td class="fw-bold text-dark">₹75,850</td>
                                                            <td>July 2026</td>
                                                            <td>01-07-2026</td>
                                                            <td>UTR12903841</td>
                                                            <td><span class="badge bg-light-success text-success px-2 py-0.5 small rounded">Paid</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP004</td>
                                                            <td class="fw-bold">Ananya Sen</td>
                                                            <td>Axis Bank</td>
                                                            <td>912010045213</td>
                                                            <td>UTIB0000010</td>
                                                            <td class="fw-bold text-dark">₹70,400</td>
                                                            <td>July 2026</td>
                                                            <td>01-07-2026</td>
                                                            <td>UTR83940212</td>
                                                            <td><span class="badge bg-light-success text-success px-2 py-0.5 small rounded">Paid</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP005</td>
                                                            <td class="fw-bold">Vikram Rathore</td>
                                                            <td>Kotak Bank</td>
                                                            <td>120038475213</td>
                                                            <td>KKBK0000182</td>
                                                            <td class="fw-bold text-dark">₹1,26,600</td>
                                                            <td>July 2026</td>
                                                            <td>01-07-2026</td>
                                                            <td>UTR48237512</td>
                                                            <td><span class="badge bg-light-success text-success px-2 py-0.5 small rounded">Paid</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP006</td>
                                                            <td class="fw-bold">Priya Sharma</td>
                                                            <td>HDFC Bank</td>
                                                            <td>502000854291</td>
                                                            <td>HDFC0000120</td>
                                                            <td class="fw-bold text-dark">₹62,400</td>
                                                            <td>July 2026</td>
                                                            <td>Pending</td>
                                                            <td>N/A</td>
                                                            <td><span class="badge bg-light-warning text-warning px-2 py-0.5 small rounded" id="emp6SalaryStatus">Pending</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row bg-light rounded p-3 mb-4 mx-0">
                                                <div class="col-6">
                                                    <span class="d-block text-secondary small uppercase-label">Total Employees</span>
                                                    <h6 class="fw-bold text-dark mb-0">6 Active Records</h6>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="d-block text-secondary small uppercase-label">Total Salary Amount</span>
                                                    <h6 class="fw-bold text-primary mb-0" id="totalSalaryText">₹5,27,500</h6>
                                                </div>
                                            </div>

                                            {{-- Signatory Blocks --}}
                                            <div class="row pt-4 mt-5 border-top text-center" style="font-size: 0.8rem;">
                                                <div class="col-3">
                                                    <div class="border-bottom pb-2 mb-1" style="height: 40px;"></div>
                                                    <span class="fw-bold text-dark">Prepared By</span>
                                                </div>
                                                <div class="col-3">
                                                    <div class="border-bottom pb-2 mb-1" style="height: 40px;"></div>
                                                    <span class="fw-bold text-dark">Checked By</span>
                                                </div>
                                                <div class="col-3">
                                                    <div class="border-bottom pb-2 mb-1" style="height: 40px;"></div>
                                                    <span class="fw-bold text-dark">Approved By</span>
                                                </div>
                                                <div class="col-3">
                                                    <div class="border-bottom pb-2 mb-1" style="height: 40px;"></div>
                                                    <span class="fw-bold text-dark">Authorized Signatory</span>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                </button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                </button>
                                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Sub-table 2: PF Summary --}}
                                        <div id="summary_pf_summary" class="summary-table-section d-none">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold text-dark mb-0 uppercase-label" id="pfSummaryTitle">PF ECR - Table View</h6>
                                                <div class="btn-group btn-group-sm border rounded-pill overflow-hidden p-0.5 bg-light" role="group">
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-pf-tab-btn active bg-white text-primary border-0 fw-bold" onclick="switchPfTab('table', this)">Table View</button>
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-pf-tab-btn text-secondary border-0 fw-bold" onclick="switchPfTab('text', this)">ECR TXT Format</button>
                                                </div>
                                            </div>

                                            {{-- PF Table View --}}
                                            <div id="pf_table_view">
                                                <div class="table-responsive border rounded-3 mb-4">
                                                    <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="pfTable">
                                                        <thead class="bg-light">
                                                            <tr class="text-secondary small fw-bold">
                                                                <th class="ps-3 py-2.5">UAN</th>
                                                                <th class="py-2.5">Member Name</th>
                                                                <th class="py-2.5">Gross Wages</th>
                                                                <th class="py-2.5">EPF Wages</th>
                                                                <th class="py-2.5">EPS Wages</th>
                                                                <th class="py-2.5">EDLI Wages</th>
                                                                <th class="py-2.5">EPF Contribution (Employee 12%)</th>
                                                                <th class="py-2.5">EPS Contribution (Employer 8.33%)</th>
                                                                <th class="py-2.5">EPF Contribution (Employer 3.67%)</th>
                                                                <th class="py-2.5">NCP Days</th>
                                                                <th class="pe-3 py-2.5">Refund of Advances</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">100984523190</td>
                                                                <td class="fw-bold text-dark">Rahul Verma</td>
                                                                <td>₹1,20,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td class="text-primary fw-bold">₹1,800.00</td>
                                                                <td class="text-danger fw-semibold">₹1,249.50</td>
                                                                <td class="text-success fw-semibold">₹550.50</td>
                                                                <td>0</td>
                                                                <td class="pe-2">₹0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">100984523910</td>
                                                                <td class="fw-bold text-dark">Sneha Iyer</td>
                                                                <td>₹95,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td class="text-primary fw-bold">₹1,800.00</td>
                                                                <td class="text-danger fw-semibold">₹1,249.50</td>
                                                                <td class="text-success fw-semibold">₹550.50</td>
                                                                <td>1</td>
                                                                <td class="pe-2">₹0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">100984523212</td>
                                                                <td class="fw-bold text-dark">David Miller</td>
                                                                <td>₹85,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td class="text-primary fw-bold">₹1,800.00</td>
                                                                <td class="text-danger fw-semibold">₹1,249.50</td>
                                                                <td class="text-success fw-semibold">₹550.50</td>
                                                                <td>0</td>
                                                                <td class="pe-2">₹0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">100984523010</td>
                                                                <td class="fw-bold text-dark">Ananya Sen</td>
                                                                <td>₹80,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td class="text-primary fw-bold">₹1,800.00</td>
                                                                <td class="text-danger fw-semibold">₹1,249.50</td>
                                                                <td class="text-success fw-semibold">₹550.50</td>
                                                                <td>0</td>
                                                                <td class="pe-2">₹0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">100984523182</td>
                                                                <td class="fw-bold text-dark">Vikram Rathore</td>
                                                                <td>₹1,50,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td class="text-primary fw-bold">₹1,800.00</td>
                                                                <td class="text-danger fw-semibold">₹1,249.50</td>
                                                                <td class="text-success fw-semibold">₹550.50</td>
                                                                <td>2</td>
                                                                <td class="pe-2">₹0.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">100984523120</td>
                                                                <td class="fw-bold text-dark">Priya Sharma</td>
                                                                <td>₹72,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td>₹15,000.00</td>
                                                                <td class="text-primary fw-bold">₹1,800.00</td>
                                                                <td class="text-danger fw-semibold">₹1,249.50</td>
                                                                <td class="text-success fw-semibold">₹550.50</td>
                                                                <td>0</td>
                                                                <td class="pe-2">₹0.00</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                    <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                    </button>
                                                    <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                    </button>
                                                    <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- PF ECR Text File Format View --}}
                                            <div id="pf_text_view" class="border rounded mb-4 p-4 bg-light d-none">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="fw-bold text-dark font-14 mb-0"><i class="ti ti-file-text text-primary f-18 me-1"></i> ECR Text Return Output (.txt)</h6>
                                                    <span class="badge bg-light-warning text-warning border border-warning-subtle">Separator: #~#</span>
                                                </div>
                                                <pre class="bg-dark text-success p-3 rounded-3 mb-0 text-start overflow-auto fw-bold" style="font-family: monospace; font-size: 13.5px; line-height: 1.8; letter-spacing: 0.5px;">
                                                100984523190#~#Rahul Verma#~#120000#~#15000#~#15000#~#15000#~#1800#~#1250#~#550#~#0#~#0
                                                100984523910#~#Sneha Iyer#~#95000#~#15000#~#15000#~#15000#~#1800#~#1250#~#550#~#1#~#0
                                                100984523212#~#David Miller#~#85000#~#15000#~#15000#~#15000#~#1800#~#1250#~#550#~#0#~#0
                                                100984523010#~#Ananya Sen#~#80000#~#15000#~#15000#~#15000#~#1800#~#1250#~#550#~#0#~#0
                                                100984523182#~#Vikram Rathore#~#150000#~#15000#~#15000#~#15000#~#1800#~#1250#~#550#~#2#~#0
                                                100984523120#~#Priya Sharma#~#72000#~#15000#~#15000#~#15000#~#1800#~#1250#~#550#~#0#~#0</pre>
                                                <div class="mt-3">
                                                    <h6 class="fw-bold text-dark font-13 mb-1.5">Guidelines for EPFO Portal upload:</h6>
                                                    <ul class="list-unstyled mb-0 text-secondary font-12">
                                                        <li class="d-flex align-items-center gap-1.5 mb-1"><i class="ti ti-circle-check text-success"></i> Only use alphabets and numbers in file names. Remove special characters and spaces from the file name.</li>
                                                        <li class="d-flex align-items-center gap-1.5 mb-1"><i class="ti ti-circle-check text-success"></i> Max Size of File Upload is 8 MB. Text files over 2 MB should be compressed as a ZIP.</li>
                                                        <li class="d-flex align-items-center gap-1.5 mb-1"><i class="ti ti-circle-check text-success"></i> Only one text file can be packed inside the ZIP. Do not pack other formats like xls, doc, etc.</li>
                                                    </ul>
                                                </div>
                                                <div class="d-flex justify-content-end mt-4 pt-3 border-top no-print">
                                                    <button onclick="downloadEcrText()" class="btn btn-light-warning px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm custom-action-btn-excel" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-download-simple fs-4"></i> Download ECR (.txt)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sub-table 3: ESI Summary --}}
                                        <div id="summary_esi_summary" class="summary-table-section d-none">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-bold text-dark mb-0 uppercase-label" id="esiSummaryTitle">ESI Summary - MC Excel</h6>
                                                <div class="btn-group btn-group-sm border rounded-pill overflow-hidden p-0.5 bg-light" role="group">
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-esi-tab-btn active bg-white text-primary border-0 fw-bold" onclick="switchEsiTab('summary', this)">ESI Summary</button>
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-esi-tab-btn text-secondary border-0 fw-bold" onclick="switchEsiTab('upload', this)">ESIC Upload Sheet</button>
                                                </div>
                                            </div>

                                            {{-- ESI Contribution Summary Table --}}
                                            <div id="esi_summary_table_view">
                                                <div class="table-responsive border rounded-3 mb-4">
                                                    <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="esiTable">
                                                        <thead class="bg-light">
                                                            <tr class="text-secondary small fw-bold">
                                                                <th class="ps-3 py-2.5">Employee ID</th>
                                                                <th class="py-2.5">Employee Name</th>
                                                                <th class="py-2.5">ESI Number</th>
                                                                <th class="py-2.5">ECR Gross Wages</th>
                                                                <th class="py-2.5">ESI Wages</th>
                                                                <th class="py-2.5">Employee ESI (0.75%)</th>
                                                                <th class="py-2.5">Employer ESI (3.25%)</th>
                                                                <th class="pe-3 py-2.5">Total ESI (4%)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">EMP001</td>
                                                                <td class="fw-bold text-dark">Rahul Verma</td>
                                                                <td>21-00-123456-001-0001</td>
                                                                <td>₹1,20,000.00</td>
                                                                <td>₹21,000.00</td>
                                                                <td class="text-primary fw-semibold">₹157.50</td>
                                                                <td class="text-success fw-semibold">₹682.50</td>
                                                                <td class="pe-2 fw-bold text-dark">₹840.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">EMP002</td>
                                                                <td class="fw-bold text-dark">Sneha Iyer</td>
                                                                <td>21-00-123456-002-0002</td>
                                                                <td>₹95,000.00</td>
                                                                <td>₹21,000.00</td>
                                                                <td class="text-primary fw-semibold">₹157.50</td>
                                                                <td class="text-success fw-semibold">₹682.50</td>
                                                                <td class="pe-2 fw-bold text-dark">₹840.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">EMP003</td>
                                                                <td class="fw-bold text-dark">David Miller</td>
                                                                <td>21-00-123456-003-0003</td>
                                                                <td>₹85,000.00</td>
                                                                <td>₹21,000.00</td>
                                                                <td class="text-primary fw-semibold">₹157.50</td>
                                                                <td class="text-success fw-semibold">₹682.50</td>
                                                                <td class="pe-2 fw-bold text-dark">₹840.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">EMP004</td>
                                                                <td class="fw-bold text-dark">Ananya Sen</td>
                                                                <td>21-00-123456-004-0004</td>
                                                                <td>₹80,000.00</td>
                                                                <td>₹21,000.00</td>
                                                                <td class="text-primary fw-semibold">₹157.50</td>
                                                                <td class="text-success fw-semibold">₹682.50</td>
                                                                <td class="pe-2 fw-bold text-dark">₹840.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">EMP005</td>
                                                                <td class="fw-bold text-dark">Vikram Rathore</td>
                                                                <td>21-00-123456-005-0005</td>
                                                                <td>₹1,50,000.00</td>
                                                                <td>₹21,000.00</td>
                                                                <td class="text-primary fw-semibold">₹157.50</td>
                                                                <td class="text-success fw-semibold">₹682.50</td>
                                                                <td class="pe-2 fw-bold text-dark">₹840.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-2 fw-bold text-dark">EMP006</td>
                                                                <td class="fw-bold text-dark">Priya Sharma</td>
                                                                <td>21-00-123456-006-0006</td>
                                                                <td>₹72,000.00</td>
                                                                <td>₹21,000.00</td>
                                                                <td class="text-primary fw-semibold">₹157.50</td>
                                                                <td class="text-success fw-semibold">₹682.50</td>
                                                                <td class="pe-2 fw-bold text-dark">₹840.00</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                    <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                    </button>
                                                    <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                    </button>
                                                    <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                        <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- ESIC Upload Sheet Table --}}
                                            <div id="esi_upload_table_view" class="table-responsive border rounded-3 mb-4 d-none">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="esiUploadTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">IP Number (10 Digits)</th>
                                                            <th class="py-2.5">IP Name (Only alphabets and space)</th>
                                                            <th class="py-2.5">No of Days for which wages paid/payable during the month</th>
                                                            <th class="py-2.5">Total Monthly Wages</th>
                                                            <th class="py-2.5">Reason Code for Zero working days</th>
                                                            <th class="pe-3 py-2.5">Last Working Day</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">2100123456</td>
                                                            <td class="fw-bold text-dark">Rahul Verma</td>
                                                            <td>26</td>
                                                            <td class="fw-semibold">₹1,20,000.00</td>
                                                            <td>0</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">2100123457</td>
                                                            <td class="fw-bold text-dark">Sneha Iyer</td>
                                                            <td>26</td>
                                                            <td class="fw-semibold">₹95,000.00</td>
                                                            <td>0</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">2100123458</td>
                                                            <td class="fw-bold text-dark">David Miller</td>
                                                            <td>26</td>
                                                            <td class="fw-semibold">₹85,000.00</td>
                                                            <td>0</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">2100123459</td>
                                                            <td class="fw-bold text-dark">Ananya Sen</td>
                                                            <td>26</td>
                                                            <td class="fw-semibold">₹80,000.00</td>
                                                            <td>0</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">2100123460</td>
                                                            <td class="fw-bold text-dark">Vikram Rathore</td>
                                                            <td>26</td>
                                                            <td class="fw-semibold">₹1,50,000.00</td>
                                                            <td>0</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">2100123461</td>
                                                            <td class="fw-bold text-dark">Priya Sharma</td>
                                                            <td>26</td>
                                                            <td class="fw-semibold">₹72,000.00</td>
                                                            <td>0</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToExcel()" class="btn btn-light-success px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm custom-action-btn-excel" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-xls fs-4"></i> Download ESIC Upload Sheet
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Sub-table 4: PT Summary --}}
                                        <div id="summary_pt_summary" class="summary-table-section d-none">
                                            <h6 class="fw-bold mb-3 text-dark uppercase-label">P-Tax Return Summary</h6>
                                            <div class="table-responsive border rounded-3 mb-4">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="ptTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">Registration No</th>
                                                            <th class="py-2.5">Employer Name</th>
                                                            <th class="py-2.5">Employee Count</th>
                                                            <th class="py-2.5">Gross Salary</th>
                                                            <th class="py-2.5">PT Deduction</th>
                                                            <th class="py-2.5">Total PT Payable</th>
                                                            <th class="pe-3 py-2.5">Period</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                             <td class="ps-2 fw-bold text-dark">REG-WB-123459</td>
                                                             <td class="fw-bold text-dark">E-CASHBOOK SYSTEMS LTD</td>
                                                             <td>24 Employees</td>
                                                             <td class="fw-semibold">₹6,02,000.00</td>
                                                             <td class="fw-bold text-danger">₹1,200.00</td>
                                                             <td class="fw-bold text-success">₹1,200.00</td>
                                                             <td class="pe-2 text-muted">June 2026</td>
                                                        </tr>
                                                        <tr>
                                                             <td class="ps-2 fw-bold text-dark">REG-WB-123459</td>
                                                             <td class="fw-bold text-dark">E-CASHBOOK SYSTEMS LTD</td>
                                                             <td>20 Employees</td>
                                                             <td class="fw-semibold">₹5,10,000.00</td>
                                                             <td class="fw-bold text-danger">₹1,000.00</td>
                                                             <td class="fw-bold text-success">₹1,000.00</td>
                                                             <td class="pe-2 text-muted">May 2026</td>
                                                        </tr>
                                                        <tr>
                                                             <td class="ps-2 fw-bold text-dark">REG-WB-123459</td>
                                                             <td class="fw-bold text-dark">E-CASHBOOK SYSTEMS LTD</td>
                                                             <td>18 Employees</td>
                                                             <td class="fw-semibold">₹4,85,000.00</td>
                                                             <td class="fw-bold text-danger">₹900.00</td>
                                                             <td class="fw-bold text-success">₹900.00</td>
                                                             <td class="pe-2 text-muted">April 2026</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                </button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                </button>
                                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Sub-table 5: TDS Summary --}}
                                        <div id="summary_tds_summary" class="summary-table-section d-none">
                                            <h6 class="fw-bold mb-3 text-dark uppercase-label">TDS FVU (File Validation Utility) - RPU/FVU compatible</h6>
                                            <div class="table-responsive border rounded-3 mb-4">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="tdsTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">TAN</th>
                                                            <th class="py-2.5">PAN</th>
                                                            <th class="py-2.5">Employee Name</th>
                                                            <th class="py-2.5">Salary Amount</th>
                                                            <th class="py-2.5">TDS Amount</th>
                                                            <th class="py-2.5">Section Code</th>
                                                            <th class="py-2.5">Challan No</th>
                                                            <th class="py-2.5">BSR Code</th>
                                                            <th class="pe-3 py-2.5">Deposit Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">CALG01234E</td>
                                                            <td>ABCDE1234F</td>
                                                            <td class="fw-bold text-dark">Rahul Verma</td>
                                                            <td>₹1,20,000.00</td>
                                                            <td class="fw-bold text-danger">₹4,300.00</td>
                                                            <td>Section 192</td>
                                                            <td>CHL-984321</td>
                                                            <td>0210452</td>
                                                            <td class="pe-2 text-muted">05-07-2026</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">CALG01234E</td>
                                                            <td>FGHIJ5678K</td>
                                                            <td class="fw-bold text-dark">Sneha Iyer</td>
                                                            <td>₹95,000.00</td>
                                                            <td class="fw-bold text-danger">₹3,538.00</td>
                                                            <td>Section 192</td>
                                                            <td>CHL-984322</td>
                                                            <td>0210452</td>
                                                            <td class="pe-2 text-muted">05-07-2026</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">CALG01234E</td>
                                                            <td>KLMNO9012P</td>
                                                            <td class="fw-bold text-dark">David Miller</td>
                                                            <td>₹85,000.00</td>
                                                            <td class="fw-bold text-danger">₹3,213.00</td>
                                                            <td>Section 192</td>
                                                            <td>CHL-984323</td>
                                                            <td>0210452</td>
                                                            <td class="pe-2 text-muted">05-07-2026</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">CALG01234E</td>
                                                            <td>PQRST3456Q</td>
                                                            <td class="fw-bold text-dark">Ananya Sen</td>
                                                            <td>₹80,000.00</td>
                                                            <td class="fw-bold text-danger">₹1,000.00</td>
                                                            <td>Section 192</td>
                                                            <td>CHL-984324</td>
                                                            <td>0210452</td>
                                                            <td class="pe-2 text-muted">05-07-2026</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">CALG01234E</td>
                                                            <td>UVWXY7890R</td>
                                                            <td class="fw-bold text-dark">Vikram Rathore</td>
                                                            <td>₹1,50,000.00</td>
                                                            <td class="fw-bold text-danger">₹8,075.00</td>
                                                            <td>Section 192</td>
                                                            <td>CHL-984325</td>
                                                            <td>0210452</td>
                                                            <td class="pe-2 text-muted">05-07-2026</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold text-dark">CALG01234E</td>
                                                            <td>ZEXRD4523S</td>
                                                            <td class="fw-bold text-dark">Priya Sharma</td>
                                                            <td>₹72,000.00</td>
                                                            <td class="fw-bold text-danger">₹1,540.00</td>
                                                            <td>Section 192</td>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            <td class="pe-2 text-muted">N/A</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                </button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                </button>
                                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Sub-table 5: Labour Welfare Fund --}}
                                        <div id="summary_lwf_summary" class="summary-table-section d-none">
                                            <h6 class="fw-bold text-dark mb-3">Labour Welfare Fund (LWF) Liability Report</h6>
                                            <div class="table-responsive border rounded-3 mb-4">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="lwfTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">EMP ID</th>
                                                            <th class="py-2.5">Employee Name</th>
                                                            <th class="py-2.5">State</th>
                                                            <th class="py-2.5">Gross Wages</th>
                                                            <th class="py-2.5">Employee Share (LWF)</th>
                                                            <th class="py-2.5">Employer Share (LWF)</th>
                                                            <th class="py-2.5">Total LWF Contribution</th>
                                                            <th class="pe-3 py-2.5">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP001</td>
                                                            <td class="fw-bold">Rahul Verma</td>
                                                            <td>West Bengal</td>
                                                            <td>₹1,20,000</td>
                                                            <td class="fw-semibold">₹10.00</td>
                                                            <td class="fw-semibold">₹30.00</td>
                                                            <td class="fw-bold text-dark">₹40.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Filed</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP002</td>
                                                            <td class="fw-bold">Sneha Iyer</td>
                                                            <td>West Bengal</td>
                                                            <td>₹95,000</td>
                                                            <td class="fw-semibold">₹10.00</td>
                                                            <td class="fw-semibold">₹30.00</td>
                                                            <td class="fw-bold text-dark">₹40.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Filed</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP003</td>
                                                            <td class="fw-bold">David Miller</td>
                                                            <td>West Bengal</td>
                                                            <td>₹85,000</td>
                                                            <td class="fw-semibold">₹10.00</td>
                                                            <td class="fw-semibold">₹30.00</td>
                                                            <td class="fw-bold text-dark">₹40.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Filed</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP004</td>
                                                            <td class="fw-bold">Ananya Sen</td>
                                                            <td>West Bengal</td>
                                                            <td>₹80,000</td>
                                                            <td class="fw-semibold">₹10.00</td>
                                                            <td class="fw-semibold">₹30.00</td>
                                                            <td class="fw-bold text-dark">₹40.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Filed</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP005</td>
                                                            <td class="fw-bold">Vikram Rathore</td>
                                                            <td>West Bengal</td>
                                                            <td>₹1,50,000</td>
                                                            <td class="fw-semibold">₹10.00</td>
                                                            <td class="fw-semibold">₹30.00</td>
                                                            <td class="fw-bold text-dark">₹40.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Filed</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP006</td>
                                                            <td class="fw-bold">Priya Sharma</td>
                                                            <td>West Bengal</td>
                                                            <td>₹72,000</td>
                                                            <td class="fw-semibold">₹10.00</td>
                                                            <td class="fw-semibold">₹30.00</td>
                                                            <td class="fw-bold text-dark">₹40.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Filed</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                </button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                </button>
                                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Sub-table 6: Gratuity Payment --}}
                                        <div id="summary_gratuity_summary" class="summary-table-section d-none">
                                            <h6 class="fw-bold text-dark mb-3">Gratuity Liability & Accrual Statement</h6>
                                            <div class="table-responsive border rounded-3 mb-4">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="gratuityTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">EMP ID</th>
                                                            <th class="py-2.5">Employee Name</th>
                                                            <th class="py-2.5">Date of Joining</th>
                                                            <th class="py-2.5">Completed Years</th>
                                                            <th class="py-2.5">Basic Salary</th>
                                                            <th class="py-2.5">Gratuity Accrued (Current FY)</th>
                                                            <th class="py-2.5">Total Gratuity Accrued</th>
                                                            <th class="pe-3 py-2.5">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP001</td>
                                                            <td class="fw-bold">Rahul Verma</td>
                                                            <td>12-04-2019</td>
                                                            <td>7 Years</td>
                                                            <td>₹60,000</td>
                                                            <td class="fw-semibold text-danger">₹34,615</td>
                                                            <td class="fw-bold text-dark">₹2,42,307</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Provisioned</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP002</td>
                                                            <td class="fw-bold">Sneha Iyer</td>
                                                            <td>15-08-2020</td>
                                                            <td>5 Years</td>
                                                            <td>₹48,000</td>
                                                            <td class="fw-semibold text-danger">₹27,692</td>
                                                            <td class="fw-bold text-dark">₹1,38,461</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Provisioned</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP003</td>
                                                            <td class="fw-bold">David Miller</td>
                                                            <td>10-10-2021</td>
                                                            <td>4 Years</td>
                                                            <td>₹42,000</td>
                                                            <td class="fw-semibold text-secondary">N/A <span class="small text-muted">(<5 Yrs)</span></td>
                                                            <td class="fw-bold text-muted">₹0.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-warning text-warning">Not Eligible</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP004</td>
                                                            <td class="fw-bold">Ananya Sen</td>
                                                            <td>01-02-2022</td>
                                                            <td>4 Years</td>
                                                            <td>₹40,000</td>
                                                            <td class="fw-semibold text-secondary">N/A <span class="small text-muted">(<5 Yrs)</span></td>
                                                            <td class="fw-bold text-muted">₹0.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-warning text-warning">Not Eligible</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP005</td>
                                                            <td class="fw-bold">Vikram Rathore</td>
                                                            <td>12-04-2018</td>
                                                            <td>8 Years</td>
                                                            <td>₹75,000</td>
                                                            <td class="fw-semibold text-danger">₹43,269</td>
                                                            <td class="fw-bold text-dark">₹3,46,153</td>
                                                            <td class="pe-2"><span class="badge bg-light-success text-success">Provisioned</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="ps-2 fw-bold">EMP006</td>
                                                            <td class="fw-bold">Priya Sharma</td>
                                                            <td>15-05-2025</td>
                                                            <td>1 Year</td>
                                                            <td>₹36,000</td>
                                                            <td class="fw-semibold text-secondary">N/A <span class="small text-muted">(<5 Yrs)</span></td>
                                                            <td class="fw-bold text-muted">₹0.00</td>
                                                            <td class="pe-2"><span class="badge bg-light-warning text-warning">Not Eligible</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF
                                                </button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-file-xls fs-4"></i> Export Excel
                                                </button>
                                                <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .header-icon-box {
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .uppercase-label {
        font-size: 0.72rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    /* Tab design overrides */
    .nav-tabs .nav-link {
        color: #6c757d;
        background-color: transparent;
        border-color: transparent;
        transition: all 0.2s ease;
    }
    .nav-tabs .nav-link:hover {
        color: #495057;
        border-color: transparent;
    }
    .nav-tabs .nav-link.active {
        color: #667eea !important;
        border-bottom-color: #667eea !important;
        background-color: transparent !important;
    }

    /* Mirror datatable custom theme styling for payroll tables */
    #registerTable, #attendanceTable {
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        border-bottom: none;
    }
    #registerTable thead, #attendanceTable thead {
        padding: 5px;
    }
    #registerTable thead th, #attendanceTable thead th {
        background-color: #cbcbcb !important;
        color: #4b4b4b !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        padding: 17px 15px;
        border-top: none;
    }
    #registerTable tbody tr, #attendanceTable tbody tr {
        transition: background-color 0.2s ease;
    }
    #registerTable tbody tr:hover, #attendanceTable tbody tr:hover {
        background-color: #f8f9fa;
    }
    #registerTable tbody td, #attendanceTable tbody td {
        padding: 12px 15px;
        border-top: 1px solid #eeeeee;
        vertical-align: middle;
    }
    #registerTable + .datatable-bottom .datatable-pagination button,
    #attendanceTable + .datatable-bottom .datatable-pagination button {
        border-radius: 4px;
        margin: 0 2px;
    }
    #registerTable + .datatable-bottom .datatable-pagination .datatable-active a,
    #registerTable + .datatable-bottom .datatable-pagination .datatable-active button,
    #registerTable + .datatable-bottom .datatable-pagination .active a,
    #registerTable + .datatable-bottom .datatable-pagination .active button,
    #attendanceTable + .datatable-bottom .datatable-pagination .datatable-active a,
    #attendanceTable + .datatable-bottom .datatable-pagination .datatable-active button,
    #attendanceTable + .datatable-bottom .datatable-pagination .active a,
    #attendanceTable + .datatable-bottom .datatable-pagination .active button {
        background-color: #422f90 !important;
        border-color: #422f90 !important;
        color: white !important;
    }
    #registerTable + .datatable-bottom, #attendanceTable + .datatable-bottom {
        border-top: none;
        margin-top: 0;
        box-shadow: none;
    }
    #registerTable + .datatable-bottom .datatable-info, #attendanceTable + .datatable-bottom .datatable-info {
        border-top: none;
        margin-top: 10px;
    }
    #registerTable + .datatable-top .datatable-selector, #attendanceTable + .datatable-top .datatable-selector {
        border: 1px solid #e4e7eb;
        border-radius: 4px;
        padding: 5px 10px;
        background-color: #fff;
    }
    #registerTable + .datatable-container, #attendanceTable + .datatable-container {
        border-bottom: none;
    }

    /* Premium Sidebar Menu in Report Downloads */
    #summarySelectorList {
        background-color: transparent !important;
        border: none !important;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    #summarySelectorList .list-group-item {
        border: 1px solid #e4e7eb !important;
        background-color: #ffffff !important;
        border-radius: 8px !important;
        color: #4b4b4b !important;
        padding: 14px 16px !important;
        transition: all 0.25s ease-in-out;
        position: relative;
        overflow: hidden;
    }
    #summarySelectorList .list-group-item:hover {
        background-color: #f8f9fa !important;
        border-color: #cbcbcb !important;
        color: #111111 !important;
        transform: translateX(4px);
    }
    #summarySelectorList .list-group-item.active {
        background-color: #f2f0fa !important;
        border-color: #422f90 !important;
        color: #422f90 !important;
        font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(66, 47, 144, 0.08) !important;
    }
    #summarySelectorList .list-group-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: #422f90;
    }

    /* Print Report Container Styles */
    #printReportContainer {
        background-color: #ffffff;
        transition: all 0.3s ease;
    }
    #printReportContainer table th {
        background-color: #f8f9fa !important;
        color: #555555 !important;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        font-weight: 700;
    }
    #printReportContainer table td {
        font-size: 13px;
        padding: 10px 12px !important;
    }
    .summary-table-section h6 {
        position: relative;
        padding-left: 12px;
    }
    .summary-table-section h6::before {
        content: '';
        position: absolute;
        left: 0;
        top: 3px;
        bottom: 3px;
        width: 3px;
        background-color: #422f90;
        border-radius: 2px;
    }
    #summarySelectorList .list-group-item i {
        transition: transform 0.25s ease;
    }
    #summarySelectorList .list-group-item:hover i {
        transform: scale(1.15);
    }

    /* Printing optimization styling */
    @media print {
        body * {
            visibility: hidden;
        }
        #printReportContainer, #printReportContainer * {
            visibility: visible;
        }
        #printReportContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        /* Hide scrollbars & page menus */
        .pc-sidebar, .pc-header, .pc-footer, .nav-tabs, .btn, .page-header, #summarySelectorList, .nav-item, #payrollMonth, #payrollFY, .pc-content > .row:first-of-type {
            display: none !important;
        }
    }
</style>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        // Keep payroll register rendering simple so rows appear immediately after the AJAX response.
        if (typeof simpleDatatables !== 'undefined') {
            const attendanceTable = document.getElementById('pc-dt-attendance');
            if (attendanceTable && !attendanceTable.dataset.initialized) {
                attendanceTable.dataset.initialized = 'true';
                new simpleDatatables.DataTable(attendanceTable, {
                    sortable: true,
                    perPage: 10,
                    perPageSelect: [5, 10, 15, 20, 25, 50, attendanceTable.querySelectorAll('tbody tr').length],
                    searchable: true,
                    fixedHeight: false,
                    labels: {
                        placeholder: "Search...",
                        perPage: "entries per page",
                        noRows: "No data available",
                        info: "Showing {start} to {end} of {rows} entries",
                    },
                    layout: {
                        top: "{select}{search}",
                        bottom: "{info}{pager}",
                    },
                    classes: {
                        active: "active",
                        disabled: "disabled",
                        selector: "form-select",
                        input: "form-control",
                        paginationList: "pagination",
                    },
                });
            }
        }

        // Dynamic month selector sync
        $('#payrollMonth, #payrollFY').on('change', function() {
            let m = $('#payrollMonth').val();
            let fy = $('#payrollFY').val();
            let fullStr = m + ' ' + (fy === '2026-27' ? '2026' : '2025');

            $('.active-month-text').text(fullStr);
            $('#generationDate').text('05-' + (m === 'July' ? '07' : m === 'June' ? '06' : m === 'May' ? '05' : '04') + '-' + (fy === '2026-27' ? '2026' : '2025'));
        });
    });

    // Select Summary display logic
    function selectSummary(type) {
        // Manage active menu highlights
        $('#summarySelectorList button').removeClass('active');
        event.currentTarget.classList.add('active');

        // Update badge text
        let badgeText = 'Salary Sheet Summary';
        if(type === 'pf_summary') badgeText = 'PF Liability Summary';
        if(type === 'esi_summary') badgeText = 'ESI Summary';
        if(type === 'pt_summary') badgeText = 'P-Tax Summary';
        if(type === 'tds_summary') badgeText = 'TDS Summary';
        if(type === 'lwf_summary') badgeText = 'LWF Summary';
        if(type === 'gratuity_summary') badgeText = 'Gratuity Summary';
        $('#summaryBadge').text(badgeText);

        // Switch visible table section
        $('.summary-table-section').addClass('d-none');
        $('#summary_' + type).removeClass('d-none');
    }

    // Print Handler
    function printReport() {
        window.print();
    }

    // Export PDF Handler
    function exportToPDF() {
        window.print();
    }

    // Export Excel Handler
    function exportToExcel() {
        alert('Exporting report as Excel spreadsheet...');
    }

    // Download ECR Text file return
    function downloadEcrText() {
        const preElement = document.querySelector("#pf_text_view pre");
        if (preElement) {
            const text = preElement.innerText;
            const blob = new Blob([text], { type: "text/plain" });
            const anchor = document.createElement("a");
            anchor.download = "PF_ECR_Return.txt";
            anchor.href = window.URL.createObjectURL(blob);
            anchor.target = "_blank";
            anchor.style.display = "none";
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
        } else {
            alert("ECR text content not found.");
        }
    }

    // Switch ESI tabs
    function switchEsiTab(tab, btn) {
        $('.active-esi-tab-btn').removeClass('active bg-white text-primary').addClass('text-secondary');
        $(btn).addClass('active bg-white text-primary').removeClass('text-secondary');
        
        if (tab === 'summary') {
            $('#esiSummaryTitle').text('ESI Summary - MC Excel');
            $('#esi_summary_table_view').removeClass('d-none');
            $('#esi_upload_table_view').addClass('d-none');
        } else {
            $('#esiSummaryTitle').text('ESIC ONLINE SHEET FOR UPLOAD');
            $('#esi_summary_table_view').addClass('d-none');
            $('#esi_upload_table_view').removeClass('d-none');
        }
    }

    // Switch PF tabs
    function switchPfTab(tab, btn) {
        $('.active-pf-tab-btn').removeClass('active bg-white text-primary').addClass('text-secondary');
        $(btn).addClass('active bg-white text-primary').removeClass('text-secondary');
        
        if (tab === 'table') {
            $('#pfSummaryTitle').text('PF ECR - Table View');
            $('#pf_table_view').removeClass('d-none');
            $('#pf_text_view').addClass('d-none');
        } else {
            $('#pfSummaryTitle').text('EPF ECR Text File Return Format');
            $('#pf_table_view').addClass('d-none');
            $('#pf_text_view').removeClass('d-none');
        }
    }

    // Initialize custom Datatables for Attendance
    document.addEventListener("DOMContentLoaded", function () {
        const attendanceTable = document.getElementById("pc-dt-attendance");
        if (attendanceTable && typeof simpleDatatables !== 'undefined') {
            const totalRows = attendanceTable.querySelectorAll('tbody tr').length;
            const dataTable = new simpleDatatables.DataTable(attendanceTable, {
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 15, 20, 25, 50, totalRows],
                searchable: true,
                fixedHeight: false,
                labels: {
                    placeholder: "Search...",
                    perPage: "entries per page",
                    noRows: "No data available",
                    info: "Showing {start} to {end} of {rows} entries",
                },
                layout: {
                    top: "{select}{search}",
                    bottom: "{info}{pager}",
                },
                classes: {
                    active: "active",
                    disabled: "disabled",
                    selector: "form-select",
                    input: "form-control",
                    paginationList: "pagination",
                }
            });

            // Add action buttons and style layout like pc-dt-simple
            setTimeout(function () {
                const wrapper = attendanceTable.closest(".datatable-wrapper");
                if (wrapper) {
                    const topSection = wrapper.querySelector(".datatable-top");
                    if (topSection) {
                        // Create search and buttons container
                        const searchAndButtonsContainer = document.createElement("div");
                        searchAndButtonsContainer.className = "datatable-search-and-buttons";

                        // Get search element
                        const searchElement = topSection.querySelector(".datatable-search");
                        if (searchElement) {
                            topSection.removeChild(searchElement);
                            searchAndButtonsContainer.appendChild(searchElement);
                        }

                        // Append container to top section
                        topSection.appendChild(searchAndButtonsContainer);

                        // Add download and print buttons
                        const buttonContainer = document.createElement("div");
                        buttonContainer.className = "datatable-custom-buttons";

                        const downloadBtn = document.createElement("a");
                        downloadBtn.href = "#";
                        downloadBtn.className = "btn btn-secondary me-2";
                        downloadBtn.setAttribute("data-bs-toggle", "tooltip");
                        downloadBtn.setAttribute("title", "Download Now");
                        downloadBtn.innerHTML = '<i class="ti ti-download"></i>';
                        downloadBtn.addEventListener("click", function(e) {
                            e.preventDefault();
                            exportToExcel();
                        });

                        const printBtn = document.createElement("a");
                        printBtn.href = "#";
                        printBtn.className = "btn btn-primary";
                        printBtn.setAttribute("data-bs-toggle", "tooltip");
                        printBtn.setAttribute("title", "Print");
                        printBtn.innerHTML = '<i class="ti ti-printer"></i>';
                        printBtn.addEventListener("click", function(e) {
                            e.preventDefault();
                            printReport();
                        });

                        buttonContainer.appendChild(downloadBtn);
                        buttonContainer.appendChild(printBtn);
                        searchAndButtonsContainer.appendChild(buttonContainer);
                    }

                    // Move Entries selector to bottom
                    const bottomSection = wrapper.querySelector(".datatable-bottom");
                    if (bottomSection && topSection) {
                        const topSelector = topSection.querySelector(".datatable-selector-wrapper");
                        if (topSelector) {
                            const infoElement = bottomSection.querySelector(".datatable-info");
                            if (infoElement) {
                                bottomSection.insertBefore(topSelector, infoElement);
                            } else {
                                bottomSection.prepend(topSelector);
                            }
                            topSelector.style.float = "left";
                            topSelector.style.clear = "left";
                            topSelector.style.marginBottom = "10px";
                            topSelector.style.width = "auto";
                            if (infoElement) {
                                infoElement.style.clear = "left";
                                infoElement.style.marginTop = "5px";
                            }
                        }
                    }

                    // Normalize perPage label and All text
                    const selectorLabel = wrapper.querySelector(".datatable-selector-wrapper label, .datatable-dropdown label");
                    if (selectorLabel) {
                        selectorLabel.innerHTML = selectorLabel.innerHTML.replace(/\{select\}\s*/g, "");
                    }
                    const selector = wrapper.querySelector('.datatable-dropdown select');
                    if (selector && selector.lastElementChild) {
                        selector.lastElementChild.textContent = 'All';
                    }

                    // Make pagination float right
                    if (bottomSection) {
                        const paginationElement = bottomSection.querySelector(".datatable-pagination");
                        if (paginationElement) {
                            paginationElement.style.float = "right";
                            paginationElement.style.marginTop = "-45px";
                        }
                    }

                    // Bind perPage selector for pc-dt-attendance
                    const bindAttendancePerPageSelector = function (wrapperElement) {
                        if (!wrapperElement || wrapperElement.dataset.boundPerPageDelegate === "1") return;

                        wrapperElement.addEventListener("change", function (e) {
                            const target = e.target;
                            if (!(target instanceof HTMLSelectElement)) return;

                            if (
                                !target.closest(".datatable-dropdown") &&
                                !target.classList.contains("datatable-selector") &&
                                !target.classList.contains("form-select")
                            ) {
                                return;
                            }

                            const nextPerPage = parseInt(target.value, 10);
                            if (!Number.isFinite(nextPerPage) || nextPerPage <= 0) return;

                            dataTable.options.perPage = nextPerPage;
                            dataTable._currentPage = 1;
                            dataTable.update();

                            if (typeof dataTable._fixHeight === "function") {
                                dataTable._fixHeight();
                            }
                        });

                        wrapperElement.dataset.boundPerPageDelegate = "1";
                    };

                    bindAttendancePerPageSelector(wrapper);
                }
            }, 500);
        }
    });


    //------------ Yearly and monthly Initialization ------------
    
    document.addEventListener("DOMContentLoaded", function () {

        const monthSelect = document.getElementById("payrollMonth");
        const fySelect = document.getElementById("payrollFY");

        // Financial Year starts from April
        const fyMonths = [
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
            "January",
            "February",
            "March"
        ];

        const today = new Date();
        const currentYear = today.getFullYear();
        const currentMonth = today.getMonth(); // 0=Jan

        // Current FY
        let fyStartYear = (currentMonth >= 3) ? currentYear : currentYear - 1;

        // Generate FY options (Previous, Current, Next)
        for (let y = fyStartYear - 1; y <= fyStartYear + 1; y++) {
            let option = document.createElement("option");
            option.value = `${y}-${y + 1}`;
            option.text = `FY ${y}-${y + 1}`;
            if (y === fyStartYear) {
                option.selected = true;
            }
            fySelect.appendChild(option);
        }

        // Populate months
        function loadMonths(selectedFY) {

            monthSelect.innerHTML = "";

            fyMonths.forEach(month => {
                let option = document.createElement("option");
                option.value = month;
                option.text = month;

                // Default current month only for current FY
                const currentMonthName = today.toLocaleString('default', { month: 'long' });

                if (
                    selectedFY === `${fyStartYear}-${fyStartYear + 1}` &&
                    month === currentMonthName
                ) {
                    option.selected = true;
                }

                monthSelect.appendChild(option);
            });
        }

        // Initial load
        loadMonths(fySelect.value);

        // Reload months when FY changes
        fySelect.addEventListener("change", function () {
            loadMonths(this.value);
        });

    });

    
    
    $(document).ready(function () {
        $('#register-tab').on('shown.bs.tab', function () {
            loadPayrollRegister();
        });

        loadPayrollSummary();
        loadPayrollRegister();

        $('#payrollMonth, #payrollFY').on('change', function () {
            loadPayrollSummary();
            loadAttendanceRegister();

            if ($('#register-tab').hasClass('active')) {
                loadPayrollRegister();
            }
        });

        function loadPayrollSummary() {

            $.ajax({
                url: "{{ route('payroll.report.summary') }}",
                type: "GET",
                data: {
                    month: $('#payrollMonth').val(),
                    fy: $('#payrollFY').val()
                },
                success: function(res) {

                    $('#totalActiveEmployees').text(res.total_active_employees);

                    $('#grossSalary').text('₹' + Number(res.gross_salary).toLocaleString('en-IN'));
                    $('#netSalary').text('₹' + Number(res.net_salary).toLocaleString('en-IN'));
                    $('#pfLiability').text('₹' + Number(res.pf_liability).toLocaleString('en-IN'));
                    $('#esiLiability').text('₹' + Number(res.esi_liability).toLocaleString('en-IN'));
                    $('#ptLiability').text('₹' + Number(res.pt_liability).toLocaleString('en-IN'));
                    $('#tdsLiability').text('₹' + Number(res.tds_liability).toLocaleString('en-IN'));

                    $('#paymentSummary').text(
                        'Paid: ' + res.paid + ' / ' + res.total_active_employees
                    );

                    $('#paidCount').html(
                        '<i class="ph ph-check-circle"></i> ' + res.paid + ' Paid'
                    );

                    $('#unpaidCount').html(
                        '<i class="ph ph-x-circle"></i> ' + res.unpaid + ' Unpaid'
                    );

                }
            });

        }

        function loadPayrollRegister() {

            $.ajax({
                url: "{{ route('payroll.report.register') }}",
                type: "GET",
                data: {
                    month: $('#payrollMonth').val(),
                    fy: $('#payrollFY').val()
                },
                beforeSend: function () {
                    $('#payrollRegisterBody').html(`
                        <tr>
                            <td colspan="13" class="text-center py-4">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Loading...
                            </td>
                        </tr>
                    `);
                },
                success: function (response) {

                    const rows = Array.isArray(response)
                        ? response
                        : (response?.data || response?.employees || []);

                    let html = '';

                    if (rows.length > 0) {

                        $.each(rows, function(index, row){

                            html += `
                                <tr>
                                    <td class="fw-bold">${row.employee_id || ''}</td>

                                    <td>${row.name || ''}</td>

                                    <td>${row.designation_name || ''}</td>

                                    <td>${row.joining_date || ''}</td>

                                    <td>₹${Number(row.total_addition || 0).toLocaleString('en-IN',{
                                        minimumFractionDigits:2,
                                        maximumFractionDigits:2
                                    })}</td>

                                    <td class="fw-bold text-primary">
                                        ₹${Number(row.net_sal || 0).toLocaleString('en-IN',{
                                            minimumFractionDigits:2,
                                            maximumFractionDigits:2
                                        })}
                                    </td>

                                    <td>₹${Number(row.provident_fund || 0).toLocaleString('en-IN')}</td>

                                    <td>₹${Number(row.esi || 0).toLocaleString('en-IN')}</td>

                                    <td>₹${Number(row.ptax || 0).toLocaleString('en-IN')}</td>

                                    <td>₹${Number(row.tds || 0).toLocaleString('en-IN')}</td>

                                    <td>₹${Number(row.advance || 0).toLocaleString('en-IN')}</td>

                                    <td>₹${Number(row.loan_deduction || 0).toLocaleString('en-IN')}</td>

                                    <td>
                                        ${
                                            row.payment_status === 'Salary Done'
                                            ? '<span class="badge bg-light-success text-success rounded-pill">Salary Done</span>'
                                            : '<span class="badge bg-light-warning text-warning rounded-pill">Payment Pending</span>'
                                        }
                                    </td>
                                </tr>`;
                        });

                    } else {

                        html = `
                            <tr>
                                <td colspan="13" class="text-center text-muted">
                                    No employee found.
                                </td>
                            </tr>`;
                    }

                    $('#payrollRegisterBody').html(html);
                    

                },
                error: function(xhr) {
                    console.log(xhr);

                    $('#payrollRegisterBody').html(`
                        <tr>
                            <td colspan="13" class="text-center text-danger">
                                Failed to load payroll register.
                            </td>
                        </tr>
                    `);
                }
            });
        }

        //------------ Attendance Register Initialization ------------
        $('#attendance-tab').on('click', function () {
            loadAttendanceRegister();
        });

        function loadAttendanceRegister() {

            
            $.ajax({
                url: "{{ route('payroll.report.attendance') }}",
                type: "GET",
                data: {
                    month: $('#payrollMonth').val(),
                    fy: $('#payrollFY').val()
                },
                beforeSend: function () {
                    $('#attendanceRegisterBody').html(`
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Loading...
                            </td>
                        </tr>
                    `);
                },

                success: function(response) {

                    let html = '';

                    if(response.length == 0){

                        html += `
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                No Record Found
                            </td>
                        </tr>`;

                    }else{

                        $.each(response, function(index, row){

                            html += `
                            <tr>
                                <td class="ps-3 fw-bold">${row.employee_id}</td>
                                <td class="fw-bold text-dark">${row.employee_name}</td>
                                <td>${row.attendance_days}</td>
                                <td>${row.absent_days}</td>
                                <td>${row.leave_days}</td>
                                <td>${row.late_hours}</td>
                                <td>${row.overtime_hours}</td>
                                <td class="pe-3">${row.wfh_days}</td>
                            </tr>`;
                        });

                    }

                    $('#attendanceRegisterBody').html(html);
                }
            });

        }


        ///------- --- Report Financial Year dropdown ------------

        function updateFilters() {

            let type = $('#summary_type').val();

            $('#month').addClass('d-none');
            $('#quarter').addClass('d-none');

            if (type === 'monthly') {
                $('#month').removeClass('d-none');
            } else if (type === 'quarterly') {
                $('#quarter').removeClass('d-none');
            }
            // Yearly: neither month nor quarter is shown.
        }

        updateFilters();

        $('#summary_type').on('change', function () {
            updateFilters();
        });

        

    });


</script>
@endsection
