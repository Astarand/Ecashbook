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
                    <p class="text-muted small mb-0">Due date : 10th - 21st of next month</p>
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
                    <p class="text-muted small mb-0">Due date : 7th of the next month</p>
                </div>
            </div>
        </div>

        {{-- Card 8: LWF Liability --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-bold uppercase-label">LWF Liability</span>
                        <i class="ph-duotone ph-receipt text-indigo fs-3" style="color: #6610f2 !important;"></i>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark" id="lwfLiability">₹0</h3>
                    <p class="text-muted small mb-0">Due date : 7th of the next month</p>
                </div>
            </div>
        </div>

        {{-- Card 9: Payment Status Summary --}}
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="card border shadow-none rounded-3 h-100">
                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary small fw-bold uppercase-label">Salary Payment Status</span>
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
                                
                            </div>
                        </div>

                        {{-- TAB 3: SUMMARIES & DOWNLOADS --}}
                        <div class="tab-pane fade" id="summaries" role="tabpanel" aria-labelledby="summaries-tab">
                            <div class="row">
                                {{-- Left panel: Report selection --}}

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
                                            {{-- Filter bar --}}
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                <select id="ss_type" class="form-select form-select-sm" style="width:140px;" onchange="renderSsFilter()">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="half-yearly">Half-Yearly</option>
                                                    <option value="yearly">Full Year</option>
                                                </select>
                                                <select id="ss_period" class="form-select form-select-sm" style="width:160px;"></select>
                                                <button class="btn btn-primary btn-sm px-3" onclick="loadSalarySheet()">Load</button>
                                            </div>

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
                                                    <tbody id="salarySheetBody">
                                                        <tr><td colspan="10" class="text-center text-muted py-4">Select filters and click Load</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row bg-light rounded p-3 mb-4 mx-0">
                                                <div class="col-6">
                                                    <span class="d-block text-secondary small uppercase-label">Total Records</span>
                                                    <h6 class="fw-bold text-dark mb-0" id="ssTotalCount">—</h6>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <span class="d-block text-secondary small uppercase-label">Total Net Salary</span>
                                                    <h6 class="fw-bold text-primary mb-0" id="ssTotalSalary">—</h6>
                                                </div>
                                            </div>
                                            {{-- Signatory Blocks --}}
                                            <div class="row pt-4 mt-5 border-top text-center" style="font-size: 0.8rem;">
                                                <div class="col-3"><div class="border-bottom pb-2 mb-1" style="height: 40px;"></div><span class="fw-bold text-dark">Prepared By</span></div>
                                                <div class="col-3"><div class="border-bottom pb-2 mb-1" style="height: 40px;"></div><span class="fw-bold text-dark">Checked By</span></div>
                                                <div class="col-3"><div class="border-bottom pb-2 mb-1" style="height: 40px;"></div><span class="fw-bold text-dark">Approved By</span></div>
                                                <div class="col-3"><div class="border-bottom pb-2 mb-1" style="height: 40px;"></div><span class="fw-bold text-dark">Authorized Signatory</span></div>
                                            </div>
                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF</button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-xls fs-4"></i> Export Excel</button>
                                                
                                            </div>
                                        </div>

                                        {{-- Sub-table 2: PF Summary --}}
                                        <div id="summary_pf_summary" class="summary-table-section d-none">
                                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                                <h6 class="fw-bold text-dark mb-0 uppercase-label" id="pfSummaryTitle">PF ECR - Table View</h6>
                                                <div class="btn-group btn-group-sm border rounded-pill overflow-hidden p-0.5 bg-light" role="group">
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-pf-tab-btn active bg-white text-primary border-0 fw-bold" onclick="switchPfTab('table', this)">Table View</button>
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-pf-tab-btn text-secondary border-0 fw-bold" onclick="switchPfTab('text', this)">ECR TXT Format</button>
                                                </div>
                                            </div>

                                            {{-- Filter bar --}}
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                <select id="pf_type" class="form-select form-select-sm" style="width:140px;" onchange="renderPfFilter()">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="half-yearly">Half-Yearly</option>
                                                    <option value="yearly">Full Year</option>
                                                </select>
                                                <select id="pf_period" class="form-select form-select-sm" style="width:160px;"></select>
                                                <button class="btn btn-primary btn-sm px-3" onclick="loadPfData()">Load</button>
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
                                                                <th class="py-2.5">Employee EPF Contribution</th>
                                                                <th class="py-2.5">Employer EPS Contribution</th>
                                                                <th class="py-2.5">EPF Difference (Employer Share)</th>
                                                                <th class="py-2.5">NCP Days</th>
                                                                <th class="pe-3 py-2.5">Refund of Advances</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="pfTableBody">
                                                            <tr><td colspan="11" class="text-center text-muted py-4">Select filters and click Load</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                    <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF</button>
                                                    <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-xls fs-4"></i> Export Excel</button>
                                                    {{-- <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-printer fs-4"></i> Print Report</button> --}}
                                                </div>
                                            </div>

                                            {{-- PF ECR Text File Format View --}}
                                            <div id="pf_text_view" class="border rounded mb-4 p-4 bg-light d-none">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="fw-bold text-dark font-14 mb-0"><i class="ti ti-file-text text-primary f-18 me-1"></i> ECR Text Return Output (.txt)</h6>
                                                    <span class="badge bg-light-warning text-warning border border-warning-subtle">Separator: #~#</span>
                                                </div>
                                                <pre class="bg-dark text-success p-3 rounded-3 mb-0 text-start overflow-auto fw-bold" id="pfEcrTextOutput" style="font-family: monospace; font-size: 13.5px; line-height: 1.8; letter-spacing: 0.5px;">Load data first to generate ECR text...</pre>
                                                <div class="mt-3">
                                                    <h6 class="fw-bold text-dark font-13 mb-1.5">Guidelines for EPFO Portal upload:</h6>
                                                    <ul class="list-unstyled mb-0 text-secondary font-12">
                                                        <li class="d-flex align-items-center gap-1.5 mb-1"><i class="ti ti-circle-check text-success"></i> Only use alphabets and numbers in file names. Remove special characters and spaces from the file name.</li>
                                                        <li class="d-flex align-items-center gap-1.5 mb-1"><i class="ti ti-circle-check text-success"></i> Max Size of File Upload is 8 MB. Text files over 2 MB should be compressed as a ZIP.</li>
                                                        <li class="d-flex align-items-center gap-1.5 mb-1"><i class="ti ti-circle-check text-success"></i> Only one text file can be packed inside the ZIP. Do not pack other formats like xls, doc, etc.</li>
                                                    </ul>
                                                </div>
                                                <div class="d-flex justify-content-end mt-4 pt-3 border-top no-print">
                                                    <button onclick="downloadEcrText()" class="btn btn-light-warning px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm custom-action-btn-excel">
                                                        <i class="ph-duotone ph-download-simple fs-4"></i> Download ECR (.txt)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                                
                                        {{-- Sub-table 3: ESI Summary --}}
                                        <div id="summary_esi_summary" class="summary-table-section d-none">
                                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                                <h6 class="fw-bold text-dark mb-0 uppercase-label" id="esiSummaryTitle">ESI Summary - MC Excel</h6>
                                                <div class="btn-group btn-group-sm border rounded-pill overflow-hidden p-0.5 bg-light" role="group">
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-esi-tab-btn active bg-white text-primary border-0 fw-bold" onclick="switchEsiTab('summary', this)">ESI Summary</button>
                                                    <button type="button" class="btn btn-sm rounded-pill px-3 active-esi-tab-btn text-secondary border-0 fw-bold" onclick="switchEsiTab('upload', this)">ESIC Upload Sheet</button>
                                                </div>
                                            </div>

                                            {{-- Filter bar --}}
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                <select id="esi_type" class="form-select form-select-sm" style="width:140px;" onchange="renderEsiFilter()">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="half-yearly">Half-Yearly</option>
                                                    <option value="yearly">Full Year</option>
                                                </select>
                                                <select id="esi_period" class="form-select form-select-sm" style="width:160px;"></select>
                                                <button class="btn btn-primary btn-sm px-3" onclick="loadEsiData()">Load</button>
                                            </div>

                                            {{-- ESI Contribution Summary Table --}}
                                            <div id="esi_summary_table_view">
                                                <div class="table-responsive border rounded-3 mb-4">
                                                    <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="esiTable">
                                                        <thead class="bg-light">
                                                            <tr class="text-secondary small fw-bold">
                                                                <th class="ps-3 py-2.5">Employee ID</th>
                                                                <th class="py-2.5">Employee Name</th>
                                                                <th class="py-2.5">ESI (IP) Number</th>
                                                                <th class="py-2.5">ECR Gross Wages</th>
                                                                <th class="py-2.5">ESI Wages</th>
                                                                <th class="py-2.5">Employee ESI (0.75%)</th>
                                                                <th class="py-2.5">Employer ESI (3.25%)</th>
                                                                <th class="pe-3 py-2.5">Total ESI (4%)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="esiTableBody">
                                                            <tr><td colspan="8" class="text-center text-muted py-4">Select filters and click Load</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                    <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF</button>
                                                    <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-xls fs-4"></i> Export Excel</button>
                                                    {{-- <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-printer fs-4"></i> Print Report</button> --}}
                                                </div>
                                            </div>

                                            {{-- ESIC Upload Sheet Table --}}
                                            <div id="esi_upload_table_view" class="d-none">
                                                <div class="table-responsive border rounded-3 mb-4">
                                                    <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="esiUploadTable">
                                                        <thead class="bg-light">
                                                            <tr class="text-secondary small fw-bold">
                                                                <th class="ps-3 py-2.5">IP Number (10 Digits)</th>
                                                                <th class="py-2.5">IP Name</th>
                                                                <th class="py-2.5">Days Wages Paid</th>
                                                                <th class="py-2.5">Total Monthly Wages</th>
                                                                <th class="py-2.5">Reason Code</th>
                                                                <th class="pe-3 py-2.5">Last Working Day</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="esiUploadTableBody">
                                                            <tr><td colspan="6" class="text-center text-muted py-4">Load ESI Summary first</td></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-end mt-4 pt-3 border-top no-print">
                                                    <button onclick="exportToExcel()" class="btn btn-light-success px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm custom-action-btn-excel">
                                                        <i class="ph-duotone ph-file-xls fs-4"></i> Download ESIC Upload Sheet
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Sub-table 4: PT Summary --}}
                                        <div id="summary_pt_summary" class="summary-table-section d-none">
                                            <h6 class="fw-bold mb-3 text-dark uppercase-label">P-Tax Return Summary</h6>

                                            {{-- Filter bar --}}
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                <select id="pt_type" class="form-select form-select-sm" style="width:140px;" onchange="renderPtFilter()">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="half-yearly">Half-Yearly</option>
                                                    <option value="yearly">Full Year</option>
                                                </select>
                                                <select id="pt_period" class="form-select form-select-sm" style="width:160px;"></select>
                                                <button class="btn btn-primary btn-sm px-3" onclick="loadPtData()">Load</button>
                                            </div>

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
                                                    <tbody id="ptTableBody">
                                                        <tr><td colspan="7" class="text-center text-muted py-4">Select filters and click Load</td></tr>
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
                                                {{-- <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button> --}}
                                            </div>
                                        </div>

                                        {{-- Sub-table 5: TDS Summary --}}
                                        <div id="summary_tds_summary" class="summary-table-section d-none">
                                            <h6 class="fw-bold mb-3 text-dark uppercase-label">TDS FVU (File Validation Utility) - RPU/FVU compatible</h6>

                                            {{-- Filter bar --}}
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                <select id="tds_type" class="form-select form-select-sm" style="width:140px;" onchange="renderTdsFilter()">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="half-yearly">Half-Yearly</option>
                                                    <option value="yearly">Full Year</option>
                                                </select>
                                                <select id="tds_period" class="form-select form-select-sm" style="width:160px;"></select>
                                                <button class="btn btn-primary btn-sm px-3" onclick="loadTdsData()">Load</button>
                                            </div>

                                            <div class="table-responsive border rounded-3 mb-4">
                                                <table class="table tbl-product m-0 custom-list-table align-middle table-sm" id="tdsTable">
                                                    <thead class="bg-light">
                                                        <tr class="text-secondary small fw-bold">
                                                            <th class="ps-3 py-2.5">TAN</th>
                                                            <th class="py-2.5">PAN</th>
                                                            <th class="py-2.5">Employee Name</th>
                                                            <th class="py-2.5">Salary Amount</th>
                                                            <th class="py-2.5">TDS Amount</th>
                                                            <th class="py-2.5">Nature of Payment</th>
                                                            <th class="py-2.5">Challan No</th>
                                                            <th class="py-2.5">BSR Code</th>
                                                            <th class="py-2.5">Deposit Date</th>
                                                            <th class="py-2.5">Tender Date</th>
                                                            <th class="py-2.5">CIN</th>
                                                            <th class="pe-3 py-2.5">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tdsTableBody">
                                                        <tr><td colspan="12" class="text-center text-muted py-4">Select filters and click Load</td></tr>
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
                                                {{-- <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm" style="transition: all 0.2s ease;">
                                                    <i class="ph-duotone ph-printer fs-4"></i> Print Report
                                                </button> --}}
                                            </div>
                                        </div>

                                        {{-- Sub-table 5: Labour Welfare Fund --}}
                                        <div id="summary_lwf_summary" class="summary-table-section d-none">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <h6 class="fw-bold text-dark mb-0">Labour Welfare Fund (LWF) Liability Report</h6>
                                                <button type="button" class="btn btn-sm btn-light border-0 p-1 lh-1" onclick="document.getElementById('lwfInfoModal').style.display='flex'" title="About LWF">
                                                    <i class="ph-duotone ph-eye fs-5 text-primary"></i>
                                                </button>
                                            </div>
                                            {{-- Filter bar --}}
                                            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                                <select id="lwf_type" class="form-select form-select-sm" style="width:140px;" onchange="renderLwfFilter()">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="half-yearly">Half-Yearly</option>
                                                    <option value="yearly">Full Year</option>
                                                </select>
                                                <select id="lwf_period" class="form-select form-select-sm" style="width:160px;"></select>
                                                <button class="btn btn-primary btn-sm px-3" onclick="loadLwfData()">Load</button>
                                            </div>
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
                                                    <tbody id="lwfTableBody">
                                                        <tr><td colspan="8" class="text-center text-muted py-4">Select filters and click Load</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF</button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-xls fs-4"></i> Export Excel</button>
                                                {{-- <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-printer fs-4"></i> Print Report</button> --}}
                                            </div>
                                        </div>

                                        {{-- Sub-table 6: Gratuity Payment --}}
                                        <div id="summary_gratuity_summary" class="summary-table-section d-none">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <h6 class="fw-bold text-dark mb-0">Gratuity Liability & Accrual Statement</h6>
                                                <button type="button" class="btn btn-sm btn-light border-0 p-1 lh-1" onclick="document.getElementById('gratuityInfoModal').style.display='flex'" title="About Gratuity">
                                                    <i class="ph-duotone ph-eye fs-5 text-primary"></i>
                                                </button>
                                            </div>
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
                                                    <tbody id="gratuityTableBody">
                                                        <tr><td colspan="8" class="text-center text-muted py-4">Loading...</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-end gap-3 align-items-center mt-4 pt-3 border-top no-print">
                                                <button onclick="exportToPDF()" class="btn custom-action-btn-pdf px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-pdf fs-4"></i> Export PDF</button>
                                                <button onclick="exportToExcel()" class="btn custom-action-btn-excel px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-file-xls fs-4"></i> Export Excel</button>
                                                {{-- <button onclick="printReport()" class="btn custom-action-btn-print px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-bold border-0 shadow-sm"><i class="ph-duotone ph-printer fs-4"></i> Print Report</button> --}}
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

{{-- LWF Info Modal --}}
<div id="lwfInfoModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; max-width:480px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#3b82f6,#1d4ed8); padding:18px 22px; display:flex; align-items:center; justify-content:space-between;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-duotone ph-umbrella fs-4 text-white"></i>
                <span class="fw-bold text-white fs-6">Labour Welfare Fund (LWF)</span>
            </div>
            <button type="button" onclick="document.getElementById('lwfInfoModal').style.display='none'" style="background:none;border:none;color:#fff;font-size:1.4rem;line-height:1;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:24px 22px;">
            <p class="text-dark mb-0" style="line-height:1.7;">
                Labour Welfare Fund (LWF) is a statutory employee welfare contribution collected by the <strong>State Labour Welfare Board</strong>. It is governed under each state's respective LWF Act and provides benefits such as housing, education, medical aid, and social security to workers and their families.
            </p>
        </div>
        <div style="padding:0 22px 18px; text-align:right;">
            <button type="button" onclick="document.getElementById('lwfInfoModal').style.display='none'" class="btn btn-primary btn-sm px-4">Close</button>
        </div>
    </div>
</div>

{{-- Gratuity Info Modal --}}
<div id="gratuityInfoModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; max-width:480px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.18); overflow:hidden;">
        <div style="background:linear-gradient(135deg,#f59e0b,#d97706); padding:18px 22px; display:flex; align-items:center; justify-content:space-between;">
            <div class="d-flex align-items-center gap-2">
                <i class="ph-duotone ph-hand-coins fs-4 text-white"></i>
                <span class="fw-bold text-white fs-6">Gratuity</span>
            </div>
            <button type="button" onclick="document.getElementById('gratuityInfoModal').style.display='none'" style="background:none;border:none;color:#fff;font-size:1.4rem;line-height:1;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:24px 22px;">
            <ul class="mb-0 ps-3" style="line-height:1.9;">
                <li class="text-dark">Gratuity is a <strong>retirement / separation benefit</strong> paid by employer to employee.</li>
                <li class="text-dark">Gratuity becomes applicable after <strong>5 years continuous service</strong> (except death / disability cases).</li>
            </ul>
        </div>
        <div style="padding:0 22px 18px; text-align:right;">
            <button type="button" onclick="document.getElementById('gratuityInfoModal').style.display='none'" class="btn btn-warning btn-sm px-4 text-white">Close</button>
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

        // Auto-load data for sections that don't need extra filters
        if (type === 'gratuity_summary') loadGratuityData();
        if (type === 'lwf_summary')      { renderLwfFilter(); loadLwfData(); }
        if (type === 'salary_sheet')     { renderSsFilter();  loadSalarySheet(); }
        if (type === 'pf_summary')       { renderPfFilter(); }
        if (type === 'esi_summary')      { renderEsiFilter(); }
        if (type === 'pt_summary')       { renderPtFilter(); }
        if (type === 'tds_summary')      { renderTdsFilter(); }
    }

    // ============================================================
    // Helpers: period dropdown builders
    // ============================================================
    const MONTHS = ['January','February','March','April','May','June',
                    'July','August','September','October','November','December'];

    function buildPeriodOptions(type, prevMonthIdx) {
        // prevMonthIdx: 0-based index of previous month (default selection)
        let html = '';
        if (type === 'monthly') {
            MONTHS.forEach((m, i) => {
                const sel = (i === prevMonthIdx) ? 'selected' : '';
                html += `<option value="${m}" ${sel}>${m}</option>`;
            });
        } else if (type === 'quarterly') {
            const quarters = [
                {v:'Q1',l:'Q1 (Apr–Jun)'}, {v:'Q2',l:'Q2 (Jul–Sep)'},
                {v:'Q3',l:'Q3 (Oct–Dec)'}, {v:'Q4',l:'Q4 (Jan–Mar)'}
            ];
            quarters.forEach(q => { html += `<option value="${q.v}">${q.l}</option>`; });
        } else if (type === 'half-yearly') {
            html = `<option value="H1">H1 (Apr–Sep)</option><option value="H2">H2 (Oct–Mar)</option>`;
        } else {
            html = `<option value="full">Full Year</option>`;
        }
        return html;
    }

    function prevMonthIndex() {
        const d = new Date();
        d.setMonth(d.getMonth() - 1);
        return d.getMonth(); // 0-based
    }

    // ============================================================
    // Salary Sheet
    // ============================================================
    function renderSsFilter() {
        const type = $('#ss_type').val();
        $('#ss_period').html(buildPeriodOptions(type, prevMonthIndex()));
        $('#ss_period').toggle(type !== 'yearly');
    }

    // ============================================================
    // PF Summary
    // ============================================================
    function renderPfFilter() {
        const type = $('#pf_type').val();
        $('#pf_period').html(buildPeriodOptions(type, prevMonthIndex()));
        $('#pf_period').toggle(type !== 'yearly');
    }
    function loadPfData() {
        const fy   = $('#payrollFY').val();
        const type = $('#pf_type').val();
        const per  = type === 'yearly' ? '' : $('#pf_period').val();

        $('#pfTableBody').html('<tr><td colspan="11" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>');

        $.get('{{ route("payroll.pf.list") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#pfTableBody').html('<tr><td colspan="11" class="text-center text-muted py-4">No EPF applicable records found for selected period.</td></tr>');
                // Clear ECR text too
                $('#pfEcrTextOutput').text('No data found.');
                return;
            }

            let tableHtml = '';
            let ecrLines  = [];

            data.forEach(r => {
                const gross    = parseFloat(r.gross_salary   || 0);
                const epfWages = parseFloat(r.epf_wages      || 0);
                const empPf    = parseFloat(r.provident_fund || 0);
                const empEps   = parseFloat(r.employer_eps   || 0);
                const empDiff  = parseFloat(r.employer_epf_diff || 0);
                const ncp      = parseInt(r.ncp_days         || 0);
                const uan      = r.epf_no || '—';

                tableHtml += `<tr>
                    <td class="ps-2 fw-bold text-dark">${uan}</td>
                    <td class="fw-bold text-dark">${r.name || '—'}</td>
                    <td>₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${epfWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-primary fw-bold">₹${empPf.toFixed(2)}</td>
                    <td class="text-danger fw-semibold">₹${empEps.toFixed(2)}</td>
                    <td class="text-success fw-semibold">₹${empDiff.toFixed(2)}</td>
                    <td>${ncp}</td>
                    <td class="pe-2">₹0.00</td>
                </tr>`;

                // ECR format: UAN#~#Name#~#Gross#~#EPFWages#~#EPSWages#~#EDLIWages#~#EmpPF#~#EmpEPS#~#EmpDiff#~#NCP#~#Refund
                if (uan !== '—') {
                    ecrLines.push([
                        uan,
                        r.name || '',
                        Math.round(gross),
                        Math.round(epfWages),
                        Math.round(epfWages),
                        Math.round(epfWages),
                        Math.round(empPf),
                        Math.round(empEps),
                        Math.round(empDiff),
                        ncp,
                        0
                    ].join('#~#'));
                }
            });

            $('#pfTableBody').html(tableHtml);
            $('#pfEcrTextOutput').text(ecrLines.length ? ecrLines.join('\n') : 'No UAN numbers found for ECR generation.');
        }).fail(() => {
            $('#pfTableBody').html('<tr><td colspan="11" class="text-center text-danger py-4">Failed to load PF data.</td></tr>');
        });
    }

    function loadSalarySheet() {
        const fy   = $('#payrollFY').val();
        const type = $('#ss_type').val();
        const per  = type === 'yearly' ? '' : $('#ss_period').val();

        $('#salarySheetBody').html('<tr><td colspan="10" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>');

        $.get('{{ route("payroll.salary.sheet") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#salarySheetBody').html('<tr><td colspan="10" class="text-center text-muted py-4">No records found for selected period.</td></tr>');
                $('#ssTotalCount').text('0 records');
                $('#ssTotalSalary').text('₹0.00');
                return;
            }
            let html = '', total = 0;
            data.forEach(r => {
                const net = parseFloat(r.net_salary || 0);
                total += net;
                const statusBadge = (r.payment_status === 'Done')
                    ? `<span class="badge bg-light-success text-success">Paid</span>`
                    : `<span class="badge bg-light-warning text-warning">Pending</span>`;
                html += `<tr>
                    <td class="ps-2 fw-bold">${r.employee_id || '—'}</td>
                    <td class="fw-bold">${r.name || '—'}</td>
                    <td>${r.bank_name || '—'}</td>
                    <td>${r.account_number || '—'}</td>
                    <td>${r.ifsc || '—'}</td>
                    <td class="fw-bold text-dark">₹${net.toFixed(2)}</td>
                    <td>${r.month_name || '—'}</td>
                    <td>${r.payment_date || '—'}</td>
                    <td>${r.payment_trans_id || 'N/A'}</td>
                    <td class="pe-2">${statusBadge}</td>
                </tr>`;
            });
            $('#salarySheetBody').html(html);
            $('#ssTotalCount').text(data.length + ' record' + (data.length !== 1 ? 's' : ''));
            $('#ssTotalSalary').text('₹' + total.toLocaleString('en-IN', {minimumFractionDigits:2}));
        }).fail(() => {
            $('#salarySheetBody').html('<tr><td colspan="10" class="text-center text-danger py-4">Failed to load data.</td></tr>');
        });
    }

    // ============================================================
    // ESI Summary
    // ============================================================
    function renderEsiFilter() {
        const type = $('#esi_type').val();
        $('#esi_period').html(buildPeriodOptions(type, prevMonthIndex()));
        $('#esi_period').toggle(type !== 'yearly');
    }

    function loadEsiData() {
        const fy   = $('#payrollFY').val();
        const type = $('#esi_type').val();
        const per  = type === 'yearly' ? '' : $('#esi_period').val();

        const loadingRow8 = '<tr><td colspan="8" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>';
        const loadingRow6 = '<tr><td colspan="6" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>';
        $('#esiTableBody').html(loadingRow8);
        $('#esiUploadTableBody').html(loadingRow6);

        $.get('{{ route("payroll.esi.list") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#esiTableBody').html('<tr><td colspan="8" class="text-center text-muted py-4">No ESIC applicable records found for selected period.</td></tr>');
                $('#esiUploadTableBody').html('<tr><td colspan="6" class="text-center text-muted py-4">No data.</td></tr>');
                return;
            }

            let summaryHtml = '';
            let uploadHtml  = '';

            data.forEach(r => {
                const gross       = parseFloat(r.gross_wages    || 0);
                const esiWages    = parseFloat(r.esi_wages      || 0);
                const empEsi      = parseFloat(r.employee_esi   || 0);
                const emplEsi     = parseFloat(r.employer_esi   || 0);
                const totalEsi    = parseFloat(r.total_esi      || 0);
                const presentDays = parseInt(r.present_days     || 0);
                const workingDays = parseInt(r.total_working_days || 0);
                const esicNo      = r.esic_no || '—';

                summaryHtml += `<tr>
                    <td class="ps-2 fw-bold text-dark">${r.employee_id || '—'}</td>
                    <td class="fw-bold text-dark">${r.name || '—'}</td>
                    <td>${esicNo}</td>
                    <td>₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>₹${esiWages.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-primary fw-semibold">₹${empEsi.toFixed(2)}</td>
                    <td class="text-success fw-semibold">₹${emplEsi.toFixed(2)}</td>
                    <td class="pe-2 fw-bold text-dark">₹${totalEsi.toFixed(2)}</td>
                </tr>`;

                // ESIC upload sheet: IP Number, Name, Days paid, Monthly Wages, Reason Code (0), Last Working Day (N/A)
                const reasonCode = presentDays === 0 ? 1 : 0;
                uploadHtml += `<tr>
                    <td class="ps-2 fw-bold text-dark">${esicNo !== '—' ? esicNo.replace(/\D/g,'').substring(0,10) : '—'}</td>
                    <td class="fw-bold text-dark">${r.name || '—'}</td>
                    <td>${presentDays}</td>
                    <td class="fw-semibold">₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>${reasonCode}</td>
                    <td class="pe-2 text-muted">N/A</td>
                </tr>`;
            });

            $('#esiTableBody').html(summaryHtml);
            $('#esiUploadTableBody').html(uploadHtml);
        }).fail(() => {
            $('#esiTableBody').html('<tr><td colspan="8" class="text-center text-danger py-4">Failed to load ESI data.</td></tr>');
        });
    }

    // ============================================================
    // PT (Professional Tax)
    // ============================================================
    function renderPtFilter() {
        const type = $('#pt_type').val();
        $('#pt_period').html(buildPeriodOptions(type, prevMonthIndex()));
        $('#pt_period').toggle(type !== 'yearly');
    }

    function loadPtData() {
        const fy   = $('#payrollFY').val();
        const type = $('#pt_type').val();
        const per  = type === 'yearly' ? '' : $('#pt_period').val();

        $('#ptTableBody').html('<tr><td colspan="7" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>');

        $.get('{{ route("payroll.ptax.summary") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#ptTableBody').html('<tr><td colspan="7" class="text-center text-muted py-4">No P-Tax applicable records found for selected period.</td></tr>');
                return;
            }

            let html        = '';
            let totalEmp    = 0;
            let totalGross  = 0;
            let totalPtax   = 0;

            data.forEach(r => {
                const empCount = parseInt(r.employee_count   || 0);
                const gross    = parseFloat(r.total_gross_salary || 0);
                const ptax     = parseFloat(r.total_ptax     || 0);

                totalEmp   += empCount;
                totalGross += gross;
                totalPtax  += ptax;

                html += `<tr>
                    <td class="ps-2 fw-bold text-dark">${r.reg_no || '—'}</td>
                    <td class="fw-bold text-dark">${r.employer_name || '—'}</td>
                    <td>${empCount} Employee${empCount !== 1 ? 's' : ''}</td>
                    <td class="fw-semibold">₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="fw-bold text-danger">₹${ptax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="fw-bold text-success">₹${ptax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="pe-2 text-muted">${r.month_name} ${r.financial_year || ''}</td>
                </tr>`;
            });

            // Totals footer row (only if more than one period row)
            if (data.length > 1) {
                html += `<tr class="table-light fw-bold border-top">
                    <td class="ps-2" colspan="2">Total</td>
                    <td>${totalEmp} Employees</td>
                    <td>₹${totalGross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-danger">₹${totalPtax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="text-success">₹${totalPtax.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="pe-2">—</td>
                </tr>`;
            }

            $('#ptTableBody').html(html);
        }).fail(() => {
            $('#ptTableBody').html('<tr><td colspan="7" class="text-center text-danger py-4">Failed to load P-Tax data.</td></tr>');
        });
    }

    // ============================================================
    // TDS
    // ============================================================
    function renderTdsFilter() {
        const type = $('#tds_type').val();
        $('#tds_period').html(buildPeriodOptions(type, prevMonthIndex()));
        $('#tds_period').toggle(type !== 'yearly');
    }

    function loadTdsData() {
        const fy   = $('#payrollFY').val();
        const type = $('#tds_type').val();
        const per  = type === 'yearly' ? '' : $('#tds_period').val();

        $('#tdsTableBody').html('<tr><td colspan="12" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>');

        $.get('{{ route("payroll.tds.list") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#tdsTableBody').html('<tr><td colspan="12" class="text-center text-muted py-4">No TDS applicable records found for selected period.</td></tr>');
                return;
            }

            let html = '';

            data.forEach(r => {
                const gross      = parseFloat(r.gross_salary  || 0);
                const tdsAmt     = parseFloat(r.tds_amount    || 0);
                const tan        = r.comp_tan                 || '—';
                const pan        = r.pan_number               || '—';
                const challanNo  = r.tds_challan_no           || 'N/A';
                const bsrCode    = r.tds_bsr_code             || 'N/A';
                const depositDt  = r.tds_deposit_date         || 'N/A';
                const tenderDt   = r.tds_tender_date          || 'N/A';
                const cin        = r.tds_cin                  || 'N/A';
                const nature     = r.tds_nature_of_payment    || 'Section 192';
                const status     = r.tds_deposit_status;

                let statusBadge = '—';
                if (status == 1 || status === 'paid' || status === 'Paid') {
                    statusBadge = '<span class="badge bg-light-success text-success">Paid</span>';
                } else if (status == 0 || status === 'pending' || status === 'Pending') {
                    statusBadge = '<span class="badge bg-light-warning text-warning">Pending</span>';
                } else if (status) {
                    statusBadge = `<span class="badge bg-light-secondary text-secondary">${status}</span>`;
                }

                html += `<tr>
                    <td class="ps-2 fw-bold text-dark">${tan}</td>
                    <td>${pan}</td>
                    <td class="fw-bold text-dark">${r.name || '—'}</td>
                    <td class="fw-semibold">₹${gross.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="fw-bold text-danger">₹${tdsAmt.toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td>${nature}</td>
                    <td>${challanNo}</td>
                    <td>${bsrCode}</td>
                    <td class="text-muted">${depositDt}</td>
                    <td class="text-muted">${tenderDt}</td>
                    <td>${cin}</td>
                    <td class="pe-2">${statusBadge}</td>
                </tr>`;
            });

            $('#tdsTableBody').html(html);
        }).fail(() => {
            $('#tdsTableBody').html('<tr><td colspan="12" class="text-center text-danger py-4">Failed to load TDS data.</td></tr>');
        });
    }

    // ============================================================
    // LWF
    // ============================================================
    function renderLwfFilter() {
        const type = $('#lwf_type').val();
        $('#lwf_period').html(buildPeriodOptions(type, prevMonthIndex()));
        $('#lwf_period').toggle(type !== 'yearly');
    }

    function loadLwfData() {
        const fy   = $('#payrollFY').val();
        const type = $('#lwf_type').val();
        const per  = type === 'yearly' ? '' : $('#lwf_period').val();

        $('#lwfTableBody').html('<tr><td colspan="8" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>');

        $.get('{{ route("payroll.lwf.list") }}', {
            financial_year: fy,
            filter_type: type,
            period: per
        }, function(data) {
            if (!data.length) {
                $('#lwfTableBody').html('<tr><td colspan="8" class="text-center text-muted py-4">No LWF applicable employees found for selected period.</td></tr>');
                return;
            }
            let html = '';
            data.forEach(r => {
                const emp  = parseFloat(r.lwf_employee || 0);
                const empr = parseFloat(r.lwf_employer || 0);
                const tot  = parseFloat(r.lwf_total || 0);
                html += `<tr>
                    <td class="ps-2 fw-bold">${r.employee_id || '—'}</td>
                    <td class="fw-bold">${r.name || '—'}</td>
                    <td>${r.state_name || '—'}</td>
                    <td>₹${parseFloat(r.gross_wages || 0).toLocaleString('en-IN', {minimumFractionDigits:2})}</td>
                    <td class="fw-semibold">₹${emp.toFixed(2)}</td>
                    <td class="fw-semibold">₹${empr.toFixed(2)}</td>
                    <td class="fw-bold text-dark">₹${tot.toFixed(2)}</td>
                    <td class="pe-2"><span class="badge bg-light-success text-success">${r.status}</span></td>
                </tr>`;
            });
            $('#lwfTableBody').html(html);
        }).fail(() => {
            $('#lwfTableBody').html('<tr><td colspan="8" class="text-center text-danger py-4">Failed to load data.</td></tr>');
        });
    }

    // ============================================================
    // Gratuity (no period filter — always full list)
    // ============================================================
    function loadGratuityData() {
        const fy = $('#payrollFY').val();

        $('#gratuityTableBody').html('<tr><td colspan="8" class="text-center py-3"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>');

        $.get('{{ route("payroll.gratuity.list") }}', {
            financial_year: fy
        }, function(data) {
            if (!data.length) {
                $('#gratuityTableBody').html('<tr><td colspan="8" class="text-center text-muted py-4">No employees found.</td></tr>');
                return;
            }
            let html = '';
            data.forEach(r => {
                const eligible    = r.status === 'Provisioned';
                const fyGratCell  = eligible
                    ? `<span class="fw-semibold text-danger">₹${parseFloat(r.current_fy_gratuity).toLocaleString('en-IN',{minimumFractionDigits:2})}</span>`
                    : `<span class="fw-semibold text-secondary">N/A <span class="small text-muted">(&lt;5 Yrs)</span></span>`;
                const totalCell   = eligible
                    ? `<span class="fw-bold text-dark">₹${parseFloat(r.total_gratuity).toLocaleString('en-IN',{minimumFractionDigits:2})}</span>`
                    : `<span class="fw-bold text-muted">₹0.00</span>`;
                const badge       = eligible
                    ? `<span class="badge bg-light-success text-success">Provisioned</span>`
                    : `<span class="badge bg-light-warning text-warning">Not Eligible</span>`;

                html += `<tr>
                    <td class="ps-2 fw-bold">${r.employee_id || '—'}</td>
                    <td class="fw-bold">${r.employee_name || '—'}</td>
                    <td>${r.joining_date}</td>
                    <td>${r.years_completed}</td>
                    <td>₹${parseFloat(r.basic_salary).toLocaleString('en-IN',{minimumFractionDigits:2})}</td>
                    <td>${fyGratCell}</td>
                    <td>${totalCell}</td>
                    <td class="pe-2">${badge}</td>
                </tr>`;
            });
            $('#gratuityTableBody').html(html);
        }).fail(() => {
            $('#gratuityTableBody').html('<tr><td colspan="8" class="text-center text-danger py-4">Failed to load data.</td></tr>');
        });
    }

    // Init filters on page load
    $(document).ready(function () {
        renderSsFilter();
        renderLwfFilter();
        renderPfFilter();
    });

    // Print Handler
    function printReport() {
        window.print();
    }

    // Export PDF Handler
    function exportToPDF() {
        // Find the currently visible summary section
        const section = document.querySelector('.summary-table-section:not(.d-none)');
        if (!section) { alert('No report visible to export.'); return; }

        const table = section.querySelector('table');
        if (!table) { alert('No table found in this report.'); return; }

        // Report title from the badge
        const reportTitle = document.getElementById('summaryBadge')?.textContent?.trim() || 'Payroll Report';
        const monthText   = document.querySelector('.active-month-text')?.textContent?.trim() || '';
        const companyName = document.getElementById('reportCompanyHeader')?.textContent?.trim() || '';

        // Collect headers
        const headers = [];
        table.querySelectorAll('thead tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('th').forEach(th => row.push(th.innerText.trim()));
            headers.push(row);
        });

        // Collect body rows
        const body = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach(td => row.push(td.innerText.trim()));
            if (row.some(c => c !== '')) body.push(row);
        });

        if (!body.length) { alert('No data rows to export.'); return; }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

        // Header block
        doc.setFontSize(13);
        doc.setFont('helvetica', 'bold');
        doc.text(companyName, 14, 14);
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text(reportTitle + (monthText ? '  ·  ' + monthText : ''), 14, 21);
        doc.text('Generated on: ' + new Date().toLocaleDateString('en-IN'), 14, 27);

        doc.autoTable({
            head: headers,
            body: body,
            startY: 32,
            styles: { fontSize: 8, cellPadding: 2, overflow: 'linebreak' },
            headStyles: { fillColor: [66, 47, 144], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [245, 245, 250] },
            margin: { left: 14, right: 14 },
        });

        const fileName = reportTitle.replace(/\s+/g, '_') + (monthText ? '_' + monthText.replace(/\s+/g, '_') : '') + '.pdf';
        doc.save(fileName);
    }

    // Export Excel Handler
    function exportToExcel() {
        const section = document.querySelector('.summary-table-section:not(.d-none)');
        if (!section) { alert('No report visible to export.'); return; }

        const table = section.querySelector('table');
        if (!table) { alert('No table found in this report.'); return; }

        const reportTitle = document.getElementById('summaryBadge')?.textContent?.trim() || 'Payroll Report';
        const monthText   = document.querySelector('.active-month-text')?.textContent?.trim() || '';
        const companyName = document.getElementById('reportCompanyHeader')?.textContent?.trim() || '';

        // Build worksheet data: company/title rows then table rows
        const wsData = [];
        wsData.push([companyName]);
        wsData.push([reportTitle + (monthText ? ' — ' + monthText : '')]);
        wsData.push(['Generated on: ' + new Date().toLocaleDateString('en-IN')]);
        wsData.push([]);   // blank row

        // Headers
        table.querySelectorAll('thead tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('th').forEach(th => row.push(th.innerText.trim()));
            wsData.push(row);
        });

        // Body
        table.querySelectorAll('tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach(td => row.push(td.innerText.trim()));
            if (row.some(c => c !== '')) wsData.push(row);
        });

        if (wsData.length <= 5) { alert('No data rows to export.'); return; }

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(wsData);

        // Auto column widths
        const colWidths = wsData.reduce((acc, row) => {
            row.forEach((cell, i) => {
                const len = (cell || '').toString().length;
                acc[i] = Math.max(acc[i] || 10, Math.min(len + 2, 40));
            });
            return acc;
        }, {});
        ws['!cols'] = Object.values(colWidths).map(w => ({ wch: w }));

        const sheetName = reportTitle.substring(0, 31);   // Excel sheet name max 31 chars
        XLSX.utils.book_append_sheet(wb, ws, sheetName);

        const fileName = reportTitle.replace(/\s+/g, '_') + (monthText ? '_' + monthText.replace(/\s+/g, '_') : '') + '.xlsx';
        XLSX.writeFile(wb, fileName);
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
        const previousMonthDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const previousMonthName = previousMonthDate.toLocaleString('default', { month: 'long' });

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

            const defaultMonth = selectedFY === `${fyStartYear}-${fyStartYear + 1}`
                ? previousMonthName
                : previousMonthName;

            fyMonths.forEach(month => {
                let option = document.createElement("option");
                option.value = month;
                option.text = month;

                if (month === defaultMonth) {
                    option.selected = true;
                }

                monthSelect.appendChild(option);
            });

            if (monthSelect.querySelector(`option[value="${defaultMonth}"]`)) {
                monthSelect.value = defaultMonth;
            }
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
                    $('#lwfLiability').text('₹' + Number(res.lwf_liability).toLocaleString('en-IN'));

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

                                    <td>₹${Number(row.lwf || 0).toLocaleString('en-IN')}</td>

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
