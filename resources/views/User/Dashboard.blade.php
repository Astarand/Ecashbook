@extends('App.Layout')

@section('container')
<style>
  #customer-rate-graph1 {
    width: 100%;
    height: 300px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
  }

  /* Calendar styling */
  .date-picker-container {
    display: flex;
    align-items: center;
  }

  .date-picker-wrapper {
    cursor: pointer;
  }

  #attendance_date_display {
    background-color: #fff;
    cursor: pointer;
    font-size: 14px;
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px 0 0 6px !important;
  }

  .calendar-trigger {
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: #eae7f7 !important;
    border: 1px solid #cbd5e1 !important;
    border-left: none !important;
    border-radius: 0 6px 6px 0 !important;
  }

  .calendar-trigger:hover {
    background-color: #dcd7f2 !important;
  }

  .calendar-trigger i {
    font-size: 1.2rem;
    color: #422f90 !important;
  }

  /* Flatpickr customization */
  .flatpickr-calendar {
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.15) !important;
    border-radius: 8px !important;
  }

  /* Search dropdown styling */
  #sidebarSearchResults {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: auto;
    z-index: 1080;
  }

  #sidebarSearchResults .dropdown-item {
    cursor: pointer;
  }

  #sidebarSearchResults .dropdown-divider {
    margin: 0.25rem 0;
  }

  .input-group {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    width: 80%;
  }

  /* 💎 Clean Premium Design System overrides (60-30-10 Rule) 💎 */

  /* Override default card styles to clean, flat, premium containers */
  .card {
    border: 1px solid #e2e6ee !important;
    border-radius: 12px !important;
    background-color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(66, 47, 144, 0.015) !important;
    transition: all 0.2s ease-in-out;
  }

  .card:hover {
    box-shadow: 0 6px 18px rgba(66, 47, 144, 0.03) !important;
  }

  .card-header {
    border-bottom: 1px solid #e2e6ee !important;
    background-color: #ffffff !important;
    padding: 16px 20px !important;
  }

  /* Hide busy background SVGs to remove clutter */
  .statistics-card-1 .img-bg {
    display: none !important;
  }

  /* Modern Card Icon Wrappers */
  .statistics-card-1 .d-flex.align-items-center > img:first-child {
    background-color: #f5f4fa !important;
    padding: 10px !important;
    border-radius: 10px !important;
    width: 48px !important;
    height: 48px !important;
    object-fit: contain;
  }

  /* Subdued labels */
  .text-muted, p.text-muted {
    color: #5e6e82 !important;
    font-size: 0.85rem;
  }

  /* Custom styled select boxes */
  .form-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    font-size: 0.85rem !important;
    color: #422f90 !important;
    background-color: #ffffff !important;
    transition: border-color 0.15s ease-in-out;
    padding: 0.25rem 2rem 0.25rem 0.75rem !important;
  }

  .form-select:focus {
    border-color: #422f90 !important;
    box-shadow: 0 0 0 2px rgba(66, 47, 144, 0.1) !important;
  }

  /* Buttons styling override to be flat & brand-colored */
  .btn-primary {
    background-color: #422f90 !important;
    border-color: #422f90 !important;
    color: #ffffff !important;
    font-weight: 500 !important;
    border-radius: 6px !important;
    transition: all 0.2s ease;
  }

  .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
    background-color: #2d1f6a !important;
    border-color: #2d1f6a !important;
  }

  .btn-secondary {
    background-color: #eae7f7 !important;
    border-color: #eae7f7 !important;
    color: #422f90 !important;
    font-weight: 500 !important;
    border-radius: 6px !important;
    transition: all 0.2s ease;
  }

  .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active {
    background-color: #dcd7f2 !important;
    border-color: #dcd7f2 !important;
    color: #2d1f6a !important;
  }

  /* Custom Search border logic */
  #compo-menu-search {
    border: 1px solid #cbd5e1 !important;
    border-radius: 6px !important;
    transition: all 0.2s ease;
  }

  #compo-menu-search:focus {
    border-color: #422f90 !important;
    box-shadow: 0 0 0 2px rgba(66, 47, 144, 0.1) !important;
  }

  /* Table design and table headings */
  .table {
    border-collapse: collapse;
  }

  .table thead th {
    background-color: #f8fafc !important;
    color: #5e6e82 !important;
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e6ee !important;
    padding: 12px 16px !important;
  }

  .table tbody td {
    padding: 14px 16px !important;
    border-bottom: 1px solid #f1f3f7 !important;
    vertical-align: middle;
  }

  .table-hover tbody tr:hover {
    background-color: #f8fafc !important;
  }

  /* Flat custom badges */
  .badge {
    font-weight: 500 !important;
    padding: 6px 10px !important;
    border-radius: 4px !important;
    font-size: 0.75rem !important;
  }

  .text-bg-success {
    background-color: #e6f6ec !important;
    color: #1a7f37 !important;
  }

  .text-bg-danger {
    background-color: #ffebe9 !important;
    color: #cf222e !important;
  }

  .text-bg-warning {
    background-color: #fff8e6 !important;
    color: #b07000 !important;
  }

  /* Chat Message button relative and custom unread badge */
  .btn-outline-success {
    border-color: #e2e6ee !important;
    color: #422f90 !important;
    background-color: #ffffff !important;
  }

  .btn-outline-success:hover {
    background-color: #f5f4fa !important;
    border-color: #cbd5e1 !important;
    color: #2d1f6a !important;
  }

  .mcCircle {
    background: #422f90 !important; /* Premium brand accent instead of bright red */
    color: #ffffff !important;
    box-shadow: 0 0 0 2px #ffffff !important;
  }
</style>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="pc-content">
  <div class="page-header">
    <div class="page-block">
      <div class="row align-items-center">
        <div class="col-md-3 col-sm-12">
          <h2 class="mb-0 ms-2 d-md-block d-none">Dashboard & Overview</h2>
        </div>
        <div class="col-md-9 col-sm-12">
          <div class="d-flex flex-nowrap align-items-center justify-content-md-end justify-content-center flex-md-nowrap flex-wrap gap-2 mt-md-0 mt-2 pe-md-2">
            {{-- Search Options --}}
            <div class="form-search" style="max-height: 35px;">
              <i class="ph-duotone ph-magnifying-glass icon-search" style="color: #422f90;"></i>
              <input type="search" class="form-control" placeholder="Search.." id="compo-menu-search" autocomplete="off">
              <div id="sidebarSearchResults" class="dropdown-menu w-100 mt-1 shadow" style="display: none; max-height: 240px; overflow-y: auto;"></div>
            </div>
            <a href="{{ route('user.AssignCa') }}" class="btn btn-primary btn-sm flex-shrink-0 d-flex align-items-center" data-bs-toggle="tooltip" title="Assign CA">
              <i class="ti ti-user-plus me-1"></i> <span class="d-md-inline d-none">Assign CA Firm</span>
            </a>
            <a href="{{ route('user.CreateSalesInvoices') }}" class="btn btn-secondary btn-sm flex-shrink-0 d-flex align-items-center" data-bs-toggle="tooltip" title="Add Sales">
              <i class="ti ti-download me-1"></i> <span class="d-md-inline d-none">Add Sales</span>
            </a>
            <a href="{{ route('user.CreatePurchaseInvoices') }}" class="btn btn-primary btn-sm flex-shrink-0 d-flex align-items-center">
              <i class="ti ti-square-plus me-1"></i> <span class="d-md-inline d-none">Add Purchases</span>
            </a>
            <select name="slet_financial_year" id="slet_financial_year" class="form-select form-select-sm flex-shrink-0" style="max-width: 180px; min-width: 150px;">
              <option disabled selected>Select Financial Year</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] start -->
  <div class="row">
    <div class="col-lg-5">
      <div class="col-md-12 col-xl-12">
        <div class="card statistics-card-1 overflow-hidden">
          <div class="card-body">
            <img src="../assets/images/widget/img-status-8.svg" alt="img" class="img-fluid img-bg">
            <div class="d-flex align-items-center">
              <img src="../assets/images/widget/takeover.png" alt="img" class="img-fluid">
              <div class="flex-grow-1 ms-3">
                <h4 class="mb-2">Total Receivables <i class="ph-duotone ph-question" data-bs-toggle="tooltip" title="View"></i></h4>
                <div class="row">
                  <div class="col-md-8">
                    <h5 class="f-w-300 d-flex align-items-center m-b-0">₹ <span id="total_unpaid"></span></h5>
                  </div>
                  <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <select class="select form-select" id="total_receivales">
                      <option value="January">January</option>
                      <option value="February">February</option>
                      <option value="March">March</option>
                      <option value="April">April</option>
                      <option value="May">May</option>
                      <option value="June">June</option>
                      <option value="July">July</option>
                      <option value="August">August</option>
                      <option value="September">September</option>
                      <option value="October">October</option>
                      <option value="November">November</option>
                      <option value="December">December</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-3 text-center">
              <div class="col-6">
                <p class="mb-0 text-muted">Current</p>
                <h5 class="mb-0 text-success">₹ <span id="receivables_current"></span></h5>
              </div>
              <div class="col-6 border-start">
                <p class="mb-0 text-muted">Overdue</p>
                <h5 class="mb-0 text-danger">₹ <span id="receivables_overdue"></span></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-xl-12">
        <div class="card statistics-card-1 overflow-hidden ">
          <div class="card-body">
            <img src="../assets/images/widget/img-status-9.svg" alt="img" class="img-fluid img-bg">
            <div class="d-flex align-items-center">
              <img src="../assets/images/widget/clock-time.png" alt="img" class="img-fluid">
              <div class="flex-grow-1 ms-3">
                <h4 class="mb-2">Total Payables <i class="ph-duotone ph-question" data-bs-toggle="tooltip"
                    title="Prosanta"></i></h4>
                <div class="row">
                  <div class="col-md-8">
                    <h5 class="f-w-300 d-flex align-items-center m-b-0">₹ <span id="total_unpaid_Payables"></span></h5>
                  </div>
                  <div class="col-md-4">
                    <select class="select form-select" id="total_payables" name="total_payables">
                      <option value="January">January</option>
                      <option value="February">February</option>
                      <option value="March">March</option>
                      <option value="April">April</option>
                      <option value="May">May</option>
                      <option value="June">June</option>
                      <option value="July">July</option>
                      <option value="August">August</option>
                      <option value="September">September</option>
                      <option value="October">October</option>
                      <option value="November">November</option>
                      <option value="December">December</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row g-3 mt-3 text-center">
              <div class="col-6">
                <p class="mb-0 text-muted">Current</p>
                <h5 class="mb-0 text-success">₹ <span id="Payables_current"></span></h5>
              </div>
              <div class="col-6 border-start">
                <p class="mb-0 text-muted">Overdue</p>
                <h5 class="mb-0 text-danger">₹ <span id="Payables_overdue"></span></h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <div class="d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Turnover Details</h4>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" id="turnover_view_type" style="width: 120px;">
                <option value="monthly" selected>Monthly</option>
                <option value="quarterly">Quarterly</option>
              </select>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-1">
            <h3 class="mb-0" id="turn_over_total_amount">₹00.00</h3>
          </div>
          <p id="turnover_label">Month wise Turnover</p>
          <div id="mothwise-turnover"></div>
          <!-- <div id="s-line"></div> -->
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5>Income And Expenses</h5>
          <div class="dropdown">
            <select class="form-select form-select-sm" id="income_expense_view_type" style="width: 120px;">
              <option value="monthly" selected>Monthly</option>
              <option value="quarterly">Quarterly</option>
            </select>
          </div>
        </div>
        <div class="card-body">
          <div class="row justify-content-center g-3 text-center mb-3">
            <div class="col-6 col-md-4">
              <div class="overview-product-legends">
                <p class="text-muted mb-1"><span>Total Income</span></p>
                <h4 class="mb-0">₹ <span id="total_transaction"></span></h4>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="overview-product-legends">
                <p class="text-muted mb-1"><span>Total Expenses</span></p>
                <h4 class="mb-0">₹ <span id="total_rec"></span></h4>
              </div>
            </div>
            <div class="col-6 col-md-4">
              <div class="overview-product-legends">
                <p class="text-muted mb-1"><span>Total Profit</span></p>
                <h4 class="mb-0">₹ <span id="total_exp"></span></h4>
              </div>
            </div>
          </div>
          <div id="user-income-expense"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Cashflow Summary</h5>
          <div class="dropdown">
            <select class="form-select form-select-sm" id="cashflow_view_type" style="width: 120px;">
              <option value="monthly" selected>Monthly</option>
              <option value="quarterly">Quarterly</option>
            </select>
          </div>
        </div>
        <div class="card-body">
          <div id="cashflow-chart"></div>
          <div class="row g-3 mt-3 text-center">
            <div class="col-4">
              <p class="mb-0 text-muted">Cash In</p>
              <h5 class="mb-0 text-success">₹ <span id="cash_in_total">00</span></h5>
            </div>
            <div class="col-4 border-start">
              <p class="mb-0 text-muted">Cash Out</p>
              <h5 class="mb-0 text-danger">₹ <span id="cash_out_total">00</span></h5>
            </div>
            <div class="col-4 border-start">
              <p class="mb-0 text-muted">Net Cash</p>
              <h5 class="mb-0 text-primary">₹ <span id="net_cash_total">00</span></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <div class="row">
        <div class="col-lg-4">
          <div class="card statistics-card-1 overflow-hidden ">
            <div class="card-body p-3">
              <img src="../assets/images/widget/img-status-8.svg" alt="img" class="img-fluid img-bg">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                  <img src="../assets/images/widget/asset.png" alt="img" class="img-fluid">
                  <div>
                    <h5 class="mb-0 ms-3">Asset Summary</h5>
                    <a href="{{ route('user.AssetList') }}" class="text-primary small d-flex align-items-center ms-3">
                      View All <i class="ti ti-arrow-up-right ms-1"></i>
                    </a>
                  </div>
                </div>
                <div class="dropdown">
                  <select class="select form-select" id="asset_summary_month">
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                  </select>
                </div>
              </div>

              <div class="row mt-4" style="border-top: 1px solid #f0f0f0; padding-top: 15px;">
                <div class="col-12 text-center">
                  <p class="text-primary mb-1">Total Assets Value</p>
                  <h4 class="mb-0 asset-value">₹ 0.00</h4>
                </div>
              </div>

            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card statistics-card-1 overflow-hidden ">
            <div class="card-body p-3">
              <img src="../assets/images/widget/img-status-8.svg" alt="img" class="img-fluid img-bg">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                  <img src="../assets/images/widget/liabilities.png" alt="img" class="img-fluid">
                  <div>
                    <h5 class="mb-0 ms-3">Liabilities Summary</h5>
                    <a href="{{ route('user.Liabilites') }}" class="text-primary small d-flex align-items-center ms-3">
                      View All <i class="ti ti-arrow-up-right ms-1"></i>
                    </a>
                  </div>
                </div>
                <div class="dropdown">
                  <select class="select form-select" id="liabilities-month-dropdown">
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                  </select>
                </div>
              </div>

              <div class="row mt-4" style="border-top: 1px solid #f0f0f0; padding-top: 15px;">
                <div class="col-12 text-center">
                  <p class="text-primary mb-1">Total Liabilities Value</p>
                  <h4 class="mb-0 liability-value">₹ 0.00</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card statistics-card-1 overflow-hidden ">
            <div class="card-body p-3">
              <img src="../assets/images/widget/img-status-8.svg" alt="img" class="img-fluid img-bg">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                  <img src="../assets/images/widget/gst.png" alt="img" class="img-fluid">
                  <div>
                    <h5 class="mb-0 ms-3">GST Summary</h5>
                    <a href="javascript:void(0);" class="text-primary small d-flex align-items-center ms-3">
                      Refresh <i class="ti ti-refresh ms-1"></i>
                    </a>
                  </div>
                </div>
                <div class="dropdown">
                  <select class="select form-select" id="gst-month-dropdown">
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August" selected>August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                  </select>
                </div>
              </div>

              <div class="row mt-4" style="border-top: 1px solid #f0f0f0; padding-top: 15px;">
                <div class="col-6 text-center" style="border-right: 1px solid #f0f0f0;">
                  <p class="text-success mb-1">Receivables</p>
                  <h4 class="mb-0 gst-receivables">₹ 0.00</h4>
                </div>
                <div class="col-6 text-center">
                  <p class="text-danger mb-1">Payables</p>
                  <h4 class="mb-0 gst-payables">₹ 0.00</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5>Employee Attendance List</h5>
              <div class="date-picker-container">
                <div class="input-group">
                  <input type="text" id="attendance_date_display" class="form-control" readonly>
                  <button type="button" class="input-group-text calendar-trigger" id="calendar-btn">
                    <i class="ti ti-calendar"></i>
                  </button>
                  <input type="hidden" id="attendence_count" name="attendence_count">
                </div>
              </div>
            </div>
            <div class="p-3">
              <div class="bg-body mt-3 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-purple-500 f-12"></i> Total Employees</p>
                <h5 class="mb-0 ms-1" id="totalEmployee">0</h5>
              </div>
              <div class="bg-body mt-1 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-primary f-12"></i> Ontime Employees</p>
                <h5 class="mb-0 ms-1" id="ontimeEmployee">0</h5>
              </div>
              <div class="bg-body mt-1 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-warning f-12"></i> Late Employees</p>
                <h5 class="mb-0 ms-1" id="lateEmployee">0</h5>
              </div>
              <div class="bg-body mt-1 py-2 px-3 rounded d-flex align-items-center justify-content-between">
                <p class="mb-0"><i class="ph-duotone ph-circle text-danger f-12"></i> Absent Employees</p>
                <h5 class="mb-0 ms-1" id="absentEmployee">0</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card table-card pb-2">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h5 class="mb-0">Compliances Status</h5>
              <a href="{{ route('ca.CompliancesList') }}" class="btn btn-sm btn-link-primary">View All</a>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <div>
                  <table class="table table-hover">
                    <thead class="sticky-top bg-white">
                      <tr class="text-center">
                        <th>Name</th>
                        <th>Document</th>
                        <th>Message By</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="statutoryTableBody">
                      <?php $i = 1; ?>
                      @foreach ($statutory as $val)
                      <tr>
                        <td>{{ $val->comp_name }}</td>
                        <td>{{ $val->statutory_doc }}</td>
                        <td>{{ $val->messages_by }}</td>
                        <td>{{ date('d-m-Y', strtotime($val->statutory_due_date)) }}</td>
                        <td>
                          @if($val->status == 0)
                          <span class="badge text-bg-danger">Pending</span>
                          @elseif($val->status == 1)
                          <span class="badge text-bg-success">Complete</span>
                          @elseif($val->status == 2)
                          <span class="badge text-bg-warning">On-going</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ url('/chat-response/'.base64_encode(Auth::user()->id).'/'.base64_encode($val->compId).'/'.base64_encode($val->id)) }}" class="btn btn-outline-success relative">
                            <span data-toggle="tooltip" data-placement="top" title="Message">
                              <i class="ti ti-message-circle f-20"></i>
                            </span>
                            <span class="mcCircle">{{ count($val->messages) }}</span>
                          </a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
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

<style>
  .relative {
    position: relative;
  }

  .mcCircle {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ff3b3b;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    border-radius: 50%;
    padding: 0 5px;
    box-shadow: 0 0 0 2px #fff;
    /* white border */
  }
</style>
@endsection

@section('page-script')
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('assets/js/user-chart.js') }}"></script>

<!-- Direct date picker initialization -->
<script>
  $(document).ready(function() {
    try {
      // Format today's date as DD-MM-YYYY for display
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0');
      var yyyy = today.getFullYear();
      var todayFormatted = dd + '-' + mm + '-' + yyyy;
      var todayApi = yyyy + '-' + mm + '-' + dd; // YYYY-MM-DD format for API

      // Set initial values
      $("#attendance_date_display").val(todayFormatted);
      $("#attendence_count").val(todayApi);

      // IMPORTANT: Fetch attendance data immediately with high priority
      if (typeof fetchAttendance === 'function') {
        // Call immediately without waiting for document ready
        setTimeout(function() {
          fetchAttendance(todayApi);
        }, 0);
      }

      // Initialize flatpickr
      var fp = flatpickr("#attendance_date_display", {
        dateFormat: "d-m-Y",
        defaultDate: today,
        onChange: function(selectedDates, dateStr) {
          // Convert DD-MM-YYYY to YYYY-MM-DD
          var parts = dateStr.split('-');
          var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
          $("#attendence_count").val(formattedDate);

          // Fetch attendance data
          if (typeof fetchAttendance === 'function') {
            fetchAttendance(formattedDate);
          }
        }
      });

      // When calendar icon is clicked, explicitly open flatpickr
      $("#calendar-btn").on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        fp.open();
      });

      // GST Summary dropdown functionality
      $("#gst-month-dropdown").change(function() {
        var selectedMonth = $(this).val();
        fetchGstSummary(selectedMonth);
      });

      // Function to fetch GST summary data
      // GST Summary AJAX Function
      function fetchGstSummary(month) {

          let financialYear = $('#slet_financial_year').val();

          if (!financialYear || financialYear === 'Select Financial Year') {
              return;
          }

          // Loading State
          $(".gst-receivables").html('<i class="ti ti-loader ti-spin"></i>');
          $(".gst-payables").html('<i class="ti ti-loader ti-spin"></i>');

          $.ajax({
              url: "{{ route('get-gst-summary') }}",
              type: "GET",

              data: {
                  month: month,
                  financial_year: financialYear
              },

              success: function(response) {

                  $(".gst-receivables").text(
                      '₹ ' + (response.receivables || '0.00')
                  );

                  $(".gst-payables").text(
                      '₹ ' + (response.payables || '0.00')
                  );
              },

              error: function(xhr) {

                  console.error(xhr.responseText);

                  $(".gst-receivables").text('₹ 0.00');
                  $(".gst-payables").text('₹ 0.00');
              }
          });
      }


      // GST Month Change
      $("#gst-month-dropdown").on('change', function () {

          fetchGstSummary($(this).val());

      });


      // Financial Year Change
      $("#slet_financial_year").on('change', function () {

          fetchGstSummary($("#gst-month-dropdown").val());

      });


      // Default Load
      setTimeout(function () {

          let currentMonthName = new Date().toLocaleString('default', {
              month: 'long'
          });

          $("#gst-month-dropdown").val(currentMonthName);

          fetchGstSummary(currentMonthName);

      }, 500);

      // Initial GST data fetch - would use the current month
      var currentMonthName = today.toLocaleString('default', {
        month: 'long'
      });
      $("#gst-month-dropdown").val(currentMonthName);
      fetchGstSummary(currentMonthName);

    } catch (error) {
      // console.error("Error initializing components:", error);

      // Fallback to vanilla JavaScript with today's date
      document.addEventListener('DOMContentLoaded', function() {
        var calendarBtn = document.getElementById('calendar-btn');
        var dateInput = document.getElementById('attendence_count');
        var dateDisplay = document.getElementById('attendance_date_display');

        if (calendarBtn && dateInput && dateDisplay) {
          // Set today's date
          var today = new Date();
          var todayIso = today.toISOString().split('T')[0]; // YYYY-MM-DD
          dateInput.value = todayIso;

          // Format for display
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0');
          var yyyy = today.getFullYear();
          dateDisplay.value = dd + '-' + mm + '-' + yyyy;

          // Fetch data for today immediately
          if (typeof fetchAttendance === 'function') {
            fetchAttendance(todayIso);
          }

          // Create a temporary date input
          var tempInput = document.createElement('input');
          tempInput.type = 'date';
          tempInput.style.position = 'absolute';
          tempInput.style.left = '-9999px';
          document.body.appendChild(tempInput);

          // When calendar button is clicked
          calendarBtn.addEventListener('click', function() {
            // Trigger click on the temp date input
            tempInput.click();
          });

          // When a date is selected in the temp input
          tempInput.addEventListener('change', function() {
            dateInput.value = this.value;

            // Format for display
            var selectedDate = new Date(this.value);
            var dd = String(selectedDate.getDate()).padStart(2, '0');
            var mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
            var yyyy = selectedDate.getFullYear();
            dateDisplay.value = dd + '-' + mm + '-' + yyyy;

            if (typeof fetchAttendance === 'function') {
              fetchAttendance(this.value);
            }
          });
        }
      });
    }
  });

  //-------- Asset Summary Script --------//
  function fetchAssetSummary(month, financialYear) {
    if (!financialYear || financialYear === 'Select Financial Year') return;
    $(".asset-value").html('<i class="ti ti-loader ti-spin"></i>');
    $.ajax({
      url: '{{ route("get-asset-summary") }}',
      type: 'GET',
      data: {
        month: month,
        financial_year: financialYear
      },
      success: function(res) {
        $('.asset-value').text('₹ ' + res.total_assets_value);
      },
      error: function() {
        $('.asset-value').text('₹ 0.00');
      }
    });
  }

  // Set current month as default on page load
  var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var currentMonthForAsset = monthNames[new Date().getMonth()];
  $('#asset_summary_month').val(currentMonthForAsset);

  // Fetch on page load once financial year is ready
  $(document).on('change', '#slet_financial_year', function() {
    fetchAssetSummary($('#asset_summary_month').val(), $(this).val());
  });

  $('#asset_summary_month').on('change', function() {
    var fy = $('#slet_financial_year').val();
    fetchAssetSummary($(this).val(), fy);
  });

  // Auto-fetch after a short delay to allow financial year dropdown to populate
  setTimeout(function() {
    var fy = $('#slet_financial_year').val();
    fetchAssetSummary(currentMonthForAsset, fy);
  }, 800);

  //-------- Liabilities Summary Script --------//
  $('#liabilities-month-dropdown').val(currentMonthForAsset);

  function fetchLiabilitiesSummary(month, financialYear) {
    if (!financialYear || financialYear === 'Select Financial Year') return;
    $(".liability-value").html('<i class="ti ti-loader ti-spin"></i>');
    $.ajax({
      url: '{{ route("get-liabilities-summary") }}',
      type: 'GET',
      data: {
        month: month,
        financial_year: financialYear
      },
      success: function(res) {
        $('.liability-value').text('₹ ' + res.total_liabilities_value);
      },
      error: function() {
        $('.liability-value').text('₹ 0.00');
      }
    });
  }

  $('#liabilities-month-dropdown').val(currentMonthForAsset);

  $(document).on('change', '#slet_financial_year', function() {
    fetchLiabilitiesSummary($('#liabilities-month-dropdown').val(), $(this).val());
  });

  $('#liabilities-month-dropdown').on('change', function() {
    fetchLiabilitiesSummary($(this).val(), $('#slet_financial_year').val());
  });

  setTimeout(function() {
    fetchLiabilitiesSummary(currentMonthForAsset, $('#slet_financial_year').val());
  }, 800);

  //-------- Searchable Sidebar Menu Script --------//
  document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('compo-menu-search');
    const results = document.getElementById('sidebarSearchResults');
    if (!input || !results) return;

    // Collect all sidebar menu links (text + href)
    const links = Array.from(document.querySelectorAll('.pc-navbar a.pc-link'))
      .map(a => ({
        text: a.textContent.trim().replace(/\s+/g, ' '),
        href: a.href
      }))
      .filter(l => l.text);

    const normalize = (str) => (str || '').replace(/\s+/g, ' ').trim().toLowerCase();

    const renderResults = (items) => {
      if (!items.length) {
        results.style.display = 'none';
        results.innerHTML = '';
        return;
      }

      results.innerHTML = items.map(item => `
            <button type="button" class="dropdown-item py-2">${item.text}</button>
        `).join('');

      results.style.display = 'block';

      // Attach click handlers
      Array.from(results.querySelectorAll('.dropdown-item')).forEach((btn, idx) => {
        btn.addEventListener('click', () => {
          window.location.href = items[idx].href;
        });
      });
    };

    input.addEventListener('input', function() {
      const q = normalize(this.value);
      if (!q) {
        renderResults([]);
        return;
      }

      const filtered = links.filter(link => normalize(link.text).includes(q));
      renderResults(filtered);
    });

    // Hide results if user clicks outside
    document.addEventListener('click', function(evt) {
      if (!results.contains(evt.target) && evt.target !== input) {
        results.style.display = 'none';
      }
    });

    input.addEventListener('focus', function() {
      if (results.innerHTML.trim()) {
        results.style.display = 'block';
      }
    });
  });

  //-------- Cashflow Chart Script --------//
  cashflowChart = null;

  $(document).ready(function () {

      initCashflowChart();

      // Default Load
      loadCashflowChart();

      // Change Event
      $('#cashflow_view_type, #slet_financial_year').on('change', function () {
          loadCashflowChart();
      });

  });

  function initCashflowChart()
  {
      var options = {

          chart: {
              height: 300,
              type: 'line',
              stacked: false,
              toolbar: {
                  show: false
              }
          },

          stroke: {
              width: [0, 0, 3],
              curve: 'smooth'
          },

          plotOptions: {
              bar: {
                  columnWidth: '50%'
              }
          },

          colors: ['#10b981', '#ef4444', '#2563eb'],

          series: [],

          xaxis: {
              categories: []
          },

          yaxis: {
              title: {
                  text: 'Amount (in Lakhs)'
              },
              labels: {
                  formatter: function (val) {
                      return '₹ ' + val + 'L';
                  }
              }
          },

          tooltip: {
              shared: true,
              intersect: false,
              y: {
                  formatter: function (y) {
                      return '₹ ' + y + ' Lakhs';
                  }
              }
          },

          legend: {
              position: 'top'
          }
      };

      cashflowChart = new ApexCharts(
          document.querySelector("#cashflow-chart"),
          options
      );

      cashflowChart.render();
  }


  function loadCashflowChart()
  {
      if (!cashflowChart) {
          initCashflowChart();
      }

      let financialYear = $('#slet_financial_year').val();
      let viewType = $('#cashflow_view_type').val() || 'monthly';

      if (!financialYear || financialYear === 'Select Financial Year') {
          const validOption = $('#slet_financial_year option:not(:disabled):first');
          if (validOption.length && validOption.val()) {
              financialYear = validOption.val();
              $('#slet_financial_year').val(financialYear);
          }
      }

      if (!financialYear || financialYear === 'Select Financial Year') {
          if ($('#slet_financial_year option').length === 0 || $('#slet_financial_year option:selected').text().trim() === 'Select Financial Year') {
              setTimeout(loadCashflowChart, 100);
              return;
          }

          console.warn('Cashflow summary skipped: no financial year selected.');
          return;
      }

      $.ajax({
          url: "{{ route('get.cashflow.summary') }}",
          type: "GET",
          data: {
              financial_year: financialYear,
              view_type: viewType
          },
          error: function (xhr, status, error) {
              console.error('Cashflow summary load failed:', status, error, xhr.responseText);
          },
          success: function (response) {
              if (!cashflowChart) return;

              cashflowChart.updateOptions({
                  xaxis: {
                      categories: response.labels || []
                  }
              });

              cashflowChart.updateSeries(response.series || []);

              $('#cash_in_total').text(response.cash_in_total || '0.00');
              $('#cash_out_total').text(response.cash_out_total || '0.00');
              $('#net_cash_total').text(response.net_cash_total || '0.00');
          }
      });
  }
</script>
@endsection