@extends('App.Layout')

@section('container')

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-4">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 mb-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">HR &amp; Payroll Management</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('user.GeneratePayslip') }}">Payslip Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Multiple Payslip Generate</li>
                        </ul>
                        <a href="{{ route('user.GeneratePayslip') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm px-3">
                            <i class="ti ti-arrow-left"></i> Single Payslip Generator
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <h2 class="mb-1 text-dark fw-bold">Bulk Payslip Generation</h2>
                    <p class="text-muted small mb-0">Review salary breakdown and generate monthly payslips for all eligible employees in one click.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Filter Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avtar avtar-s bg-light-primary text-primary rounded-circle">
                            <i class="ph-duotone ph-calendar-check fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">Select Payroll Period</h5>
                            <span class="text-muted small">Choose the financial year and payroll month to fetch eligible employees</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 bg-light-subtle">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label fw-semibold text-dark small mb-1">
                                <i class="ph-duotone ph-calendar text-muted me-1"></i> Financial Year <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg shadow-none bg-white border-secondary-subtle" id="select_financial_year" style="height: 44px; font-size: 14px;">
                                <option value="">Select FY</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label fw-semibold text-dark small mb-1">
                                <i class="ph-duotone ph-clock text-muted me-1"></i> Payroll Month <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg shadow-none bg-white border-secondary-subtle" id="monthSelect" style="height: 44px; font-size: 14px;">
                                <option value="">Select Month</option>
                                <option>January</option><option>February</option><option>March</option>
                                <option>April</option><option>May</option><option>June</option>
                                <option>July</option><option>August</option><option>September</option>
                                <option>October</option><option>November</option><option>December</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <button type="button" id="checkPayslipBtn" class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm" style="height: 44px; font-size: 14px; font-weight: 600;">
                                <i class="ph-duotone ph-magnifying-glass fs-5"></i> Load Employees &amp; Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="d-none">

        <!-- KPI Summary Cards -->
        <div class="row g-3 mb-4" id="kpiCards">
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-primary border-start border-primary border-4">
                    <div class="text-muted small fw-semibold text-uppercase">Eligible Employees</div>
                    <h3 class="fw-bold text-primary mb-0 mt-1" id="kpiEmployeeCount">0</h3>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-success border-start border-success border-4">
                    <div class="text-muted small fw-semibold text-uppercase">Total Gross Earnings</div>
                    <h3 class="fw-bold text-success mb-0 mt-1" id="kpiGrossTotal">₹0.00</h3>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-danger border-start border-danger border-4">
                    <div class="text-muted small fw-semibold text-uppercase">Total Deductions</div>
                    <h3 class="fw-bold text-danger mb-0 mt-1" id="kpiDeductionsTotal">₹0.00</h3>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-light-info border-start border-info border-4">
                    <div class="text-muted small fw-semibold text-uppercase">Net Payroll Payable</div>
                    <h3 class="fw-bold text-dark mb-0 mt-1" id="kpiNetTotal">₹0.00</h3>
                </div>
            </div>
        </div>

        <!-- Action bar -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary text-white fw-bold px-3 py-2 rounded-pill fs-6" id="resultBadge">0 Employees</span>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill" id="periodLabel"></span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-sm" id="selectAllBtn" onclick="toggleSelectAll()">
                        <i class="ph ph-check-square"></i> Select All
                    </button>
                    <button type="button" class="btn btn-success px-4 d-inline-flex align-items-center gap-2 shadow-sm" id="generateBtn" onclick="generateSelected()" disabled>
                        <i class="ph-duotone ph-file-plus fs-5"></i> Generate Selected Payslips
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 580px; overflow-y: auto;">
                    <table class="table m-0 align-middle table-hover table-bordered" id="bulkPayslipTable" style="font-size:12px;">
                        <thead class="sticky-top bg-light" style="z-index: 5;">
                            <tr class="text-dark fw-bold text-center">
                                <th class="ps-3 py-3 bg-light" rowspan="2" style="width:36px; vertical-align:middle;">
                                    <input type="checkbox" id="masterCheck" class="form-check-input" onchange="masterToggle(this)">
                                </th>
                                <th class="py-3 bg-light" rowspan="2" style="width:40px; vertical-align:middle;">#</th>
                                <th class="py-3 bg-light text-start" rowspan="2" style="min-width:90px; vertical-align:middle;">Emp ID</th>
                                <th class="py-3 bg-light text-start" rowspan="2" style="min-width:140px; vertical-align:middle;">Employee Name</th>
                                <th class="py-3 bg-light" rowspan="2" style="min-width:90px; vertical-align:middle;">Status</th>
                                {{-- Earnings Header Group --}}
                                <th class="py-2 text-center text-success bg-success bg-opacity-10 fw-bold border-bottom" colspan="7">
                                    <i class="ph-duotone ph-trend-up me-1"></i> Earnings Breakdown
                                </th>
                                {{-- Deductions Header Group --}}
                                <th class="py-2 text-center text-danger bg-danger bg-opacity-10 fw-bold border-bottom" colspan="7">
                                    <i class="ph-duotone ph-trend-down me-1"></i> Statutory &amp; Other Deductions
                                </th>
                                {{-- Manual Adjustments --}}
                                <th class="py-2 text-center text-primary bg-primary bg-opacity-10 fw-bold border-bottom" colspan="3">
                                    <i class="ph-duotone ph-pencil-simple me-1"></i> Manual Additions / Deductions
                                </th>
                                {{-- Net --}}
                                <th class="py-2 text-center text-dark bg-light fw-bold" rowspan="2" style="min-width:110px; vertical-align:middle;">Net Payable (₹)</th>
                            </tr>
                            <tr class="text-secondary fw-semibold text-center" style="font-size:11px;">
                                {{-- Earnings sub --}}
                                <th class="py-2 bg-success bg-opacity-10">Gross</th>
                                <th class="py-2 bg-success bg-opacity-10">Basic</th>
                                <th class="py-2 bg-success bg-opacity-10">HRA</th>
                                <th class="py-2 bg-success bg-opacity-10">Conv.</th>
                                <th class="py-2 bg-success bg-opacity-10">Med.</th>
                                <th class="py-2 bg-success bg-opacity-10">Spl. Allow</th>
                                <th class="py-2 bg-success bg-opacity-10">LOP Days</th>
                                {{-- Deductions sub --}}
                                <th class="py-2 bg-danger bg-opacity-10">PF</th>
                                <th class="py-2 bg-danger bg-opacity-10">ESI</th>
                                <th class="py-2 bg-danger bg-opacity-10">PT</th>
                                <th class="py-2 bg-danger bg-opacity-10">TDS</th>
                                <th class="py-2 bg-danger bg-opacity-10">Advance</th>
                                <th class="py-2 bg-danger bg-opacity-10">LWF</th>
                                <th class="py-2 bg-danger bg-opacity-10">LOP (₹)</th>
                                {{-- Manual entry sub --}}
                                <th class="py-2 bg-primary bg-opacity-10" style="min-width:100px;">Bonus (₹)</th>
                                <th class="py-2 bg-primary bg-opacity-10" style="min-width:100px;">Overtime (₹)</th>
                                <th class="py-2 bg-primary bg-opacity-10" style="min-width:100px;">Loan Ded. (₹)</th>
                            </tr>
                        </thead>
                        <tbody id="bulkPayslipBody"></tbody>
                        <tfoot class="sticky-bottom bg-light fw-bold" id="bulkPayslipFoot" style="z-index: 4;"></tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 text-muted small mt-2">
            <i class="ph-duotone ph-info text-primary fs-5"></i>
            <span>Performance Bonus, Overtime and Loan Deductions are editable inline. Net Salary updates automatically in real-time.</span>
        </div>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="d-none card border-0 shadow-sm rounded-4 p-5 text-center my-4">
        <div>
            <div class="avtar avtar-xl bg-light-success text-success rounded-circle mx-auto mb-3" style="width: 72px; height: 72px; font-size: 2rem;">
                <i class="ph-duotone ph-check-circle"></i>
            </div>
        </div>
        <h4 class="fw-bold text-dark mb-1">All Payslips Generated</h4>
        <p class="text-muted mb-3">Every eligible employee already has a payslip generated for the selected payroll month.</p>
        <div>
            <a href="{{ route('payroll.payslip_update') }}" class="btn btn-outline-primary shadow-sm px-4">
                <i class="ti ti-history me-1"></i> View / Update Generated Payslips
            </a>
        </div>
    </div>

</div>

<style>
    .editable-cell {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 4px 6px;
        width: 85px;
        font-size: 12px;
        text-align: right;
        background: #ffffff;
        transition: all .2s ease-in-out;
    }
    .editable-cell:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .net-cell { font-weight: 700; color: #16a34a; }
    #bulkPayslipTable thead th { white-space: nowrap; }
    #bulkPayslipTable td { white-space: nowrap; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
</style>

@endsection

@section('page-script')
<script>
// ================================================================
// Months Mapping Dictionary
// ================================================================
const monthsMap = {
    'January': 1, 'February': 2, 'March': 3, 'April': 4,
    'May': 5, 'June': 6, 'July': 7, 'August': 8,
    'September': 9, 'October': 10, 'November': 11, 'December': 12
};

// ================================================================
// FY & Month selector init
// ================================================================
document.addEventListener('DOMContentLoaded', function () {
    const fySelect = document.getElementById('select_financial_year');
    const today    = new Date();
    const cyear    = today.getFullYear();
    const cmonth   = today.getMonth();
    const fyStart  = cmonth >= 3 ? cyear : cyear - 1;

    for (let y = fyStart - 1; y <= fyStart + 1; y++) {
        const opt = document.createElement('option');
        opt.value = `${y}-${y + 1}`;
        opt.text  = `FY ${y}-${y + 1}`;
        if (y === fyStart) opt.selected = true;
        fySelect.appendChild(opt);
    }

    const prevMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1)
        .toLocaleString('default', { month: 'long' });
    document.getElementById('monthSelect').value = prevMonth;
});

// ================================================================
// Employee data store
// ================================================================
let employees = [];

// ================================================================
// Load employees
// ================================================================
document.getElementById('checkPayslipBtn').addEventListener('click', loadEmployees);

function loadEmployees() {
    const fy    = document.getElementById('select_financial_year').value;
    const month = document.getElementById('monthSelect').value;

    if (!fy || !month) {
        Swal.fire('Warning', 'Please select both Financial Year and Month.', 'warning');
        return;
    }

    const selectedMonthNum = monthsMap[month] || parseInt(month, 10);

    if (selectedMonthNum) {
        const [fyStartStr, fyEndStr] = fy.split('-');
        const fyStart = parseInt(fyStartStr, 10);
        const fyEnd   = parseInt(fyEndStr, 10);
        const selectedYear = (selectedMonthNum <= 3) ? fyEnd : fyStart;

        const now = new Date();
        const currentYear  = now.getFullYear();
        const currentMonth = now.getMonth() + 1;

        const isFuture = (selectedYear > currentYear) ||
                        (selectedYear === currentYear && selectedMonthNum > currentMonth);

        if (isFuture) {
            Swal.fire('Warning', 'Future month cannot be selected for payslip generation.', 'warning');
            return;
        }
    }

    const btn = document.getElementById('checkPayslipBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading…';

    $.get('{{ route("payroll.bulk.employees") }}', { financial_year: fy, month: month })
        .done(function(res) {
            employees = res.employees || [];
            renderTable(employees, month, fy, res.total_working_days);
            document.getElementById('periodLabel').textContent = month + ' · ' + fy;
        })
        .fail(function() {
            Swal.fire('Error', 'Failed to load employees. Please try again.', 'error');
        })
        .always(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="ph-duotone ph-magnifying-glass fs-5"></i> Load Employees &amp; Preview';
        });
}

// ================================================================
// Render table — full salary breakdown
// ================================================================
function renderTable(data, month, fy, workingDays) {
    document.getElementById('resultSection').classList.add('d-none');
    document.getElementById('emptyState').classList.add('d-none');

    if (!data.length) {
        document.getElementById('emptyState').classList.remove('d-none');
        return;
    }

    document.getElementById('resultBadge').textContent = data.length + ' Employee' + (data.length !== 1 ? 's' : '');
    document.getElementById('resultSection').classList.remove('d-none');

    let html = '';
    data.forEach((emp, idx) => {
        // Status badge
        const isResigned   = emp.emp_status === 'Resigned';
        const isTerminated = emp.emp_status === 'Terminated';
        let statusBadge;
        if (isResigned || isTerminated) {
            const dateStr = emp.regine_date
                ? new Date(emp.regine_date).toLocaleDateString('en-IN', { day:'2-digit', month:'short', year:'numeric' })
                : '';
            const label = isResigned ? 'Resigned' : 'Terminated';
            const color = isResigned ? 'warning' : 'danger';
            const lastDayNote = emp.effective_last_day
                ? `<small class="text-muted d-block">Last day: ${emp.effective_last_day}</small>`
                : (dateStr ? `<small class="text-muted">${dateStr}</small>` : '');
            statusBadge = `<span class="badge bg-light-${color} text-${color} d-block mb-1">${label}</span>${lastDayNote}`;
        } else {
            statusBadge = `<span class="badge bg-light-success text-success">${emp.emp_status || 'Active'}</span>`;
        }

        // Compute initial salary values
        const calc = computeNet(emp, 0, 0, parseFloat(emp.loan_ded || 0));

        emp._calc  = calc;
        emp._bonus = 0;
        emp._ot    = 0;
        emp._loan  = parseFloat(emp.loan_ded || 0);
        emp._net   = calc.netSalary;

        html += `<tr data-idx="${idx}">
            <td class="ps-3 text-center"><input type="checkbox" class="form-check-input row-check" data-idx="${idx}" onchange="updateGenerateBtn()" checked></td>
            <td class="text-muted text-center">${idx + 1}</td>
            <td class="fw-bold text-dark">${emp.employee_id || '—'}</td>
            <td class="fw-bold text-dark">
                ${emp.name || '—'}
                ${emp.designation_name ? `<small class="text-muted d-block fw-normal">${emp.designation_name}</small>` : ''}
            </td>
            <td class="text-center">${statusBadge}</td>

            {{-- Earnings --}}
            <td class="text-end">₹${fmt(calc.baseGross)}</td>
            <td class="text-end">₹${fmt(calc.basicSalary)}</td>
            <td class="text-end">₹${fmt(calc.hra)}</td>
            <td class="text-end">₹${fmt(calc.conveyance)}</td>
            <td class="text-end">₹${fmt(calc.medicalAllowance)}</td>
            <td class="text-end">₹${fmt(calc.specialAllowance)}</td>
            <td class="text-center">${calc.lopDays}</td>

            {{-- Deductions --}}
            <td class="text-end text-danger">₹${fmt(calc.pf)}</td>
            <td class="text-end text-danger">₹${fmt(calc.esi)}</td>
            <td class="text-end text-danger">₹${fmt(calc.pt)}</td>
            <td class="text-end text-danger">₹${fmt(calc.tds)}</td>
            <td class="text-end text-danger">₹${fmt(calc.advance)}</td>
            <td class="text-end text-danger">₹${fmt(calc.lwf)}</td>
            <td class="text-end text-danger">₹${fmt(calc.lopAmount)}</td>

            {{-- Manual Entry --}}
            <td><input type="number" min="0" step="0.01" class="editable-cell bonus-input" data-idx="${idx}" value="0" oninput="recalcNet(${idx})"></td>
            <td><input type="number" min="0" step="0.01" class="editable-cell ot-input"    data-idx="${idx}" value="0" oninput="recalcNet(${idx})"></td>
            <td><input type="number" min="0" step="0.01" class="editable-cell loan-input"  data-idx="${idx}" value="${emp._loan}" oninput="recalcNet(${idx})"></td>

            {{-- Net --}}
            <td class="net-cell text-end fw-bold" id="net-${idx}">₹${fmt(calc.netSalary)}</td>
        </tr>`;
    });

    document.getElementById('bulkPayslipBody').innerHTML = html;
    renderFooter();
    updateGenerateBtn();
}

// ================================================================
// Core salary computation
// ================================================================
function computeNet(emp, bonus, ot, loanVal) {
    const gross      = parseFloat(emp.total_addition || 0);
    const perDay     = parseFloat(emp.per_day_salary || 0);
    const basicPct   = parseFloat(emp.basic_percentage != null ? emp.basic_percentage : 50);

    let lopDays, lopAmount;
    if (emp.resigned_this_month) {
        lopDays   = parseFloat(emp.total_absent || 0);
        lopAmount = perDay * lopDays;
    } else {
        const absent      = parseFloat(emp.total_absent || 0);
        const lateDeduct  = parseFloat(emp.late_deduction_days || 0);
        const earlyDeduct = parseFloat(emp.total_early_logout_deduction_days || 0);
        lopDays   = absent + lateDeduct + earlyDeduct;
        lopAmount = perDay * lopDays;
    }

    const baseGross = Math.max(gross - lopAmount, 0);

    const conveyance       = 1600;
    const medicalAllowance = 1250;
    const basicSalary      = baseGross * (basicPct / 100);
    const hra              = basicSalary * 0.5;
    let specialAllowance   = baseGross - (basicSalary + hra + medicalAllowance + conveyance);
    if (specialAllowance < 0) specialAllowance = 0;

    let pf = 0;
    if (parseFloat(emp.pf || 0) > 0) {
        pf = Math.min(parseFloat((basicSalary * 0.12).toFixed(2)), 1800);
    }

    let esi = 0;
    if (parseFloat(emp.esi_amount || 0) > 0) {
        const esiBase = baseGross + ot;
        esi = (esiBase <= 21000) ? parseFloat((esiBase * 0.0075).toFixed(2)) : 0;
    }

    let pt = 0;
    if (parseFloat(emp.ptax_amount || 0) > 0) {
        const ptBase = baseGross + bonus + ot;
        if      (ptBase > 10000 && ptBase <= 15000) pt = 110;
        else if (ptBase > 15000 && ptBase <= 25000) pt = 130;
        else if (ptBase > 25000 && ptBase <= 40000) pt = 150;
        else if (ptBase > 40000)                    pt = 200;
    }

    const tds     = parseFloat(emp.tds_amount || 0);
    const advance = 0;
    const lwf     = parseFloat(emp.lwf_amount || 0);
    const loan    = parseFloat(loanVal        || 0);

    const totalEarnings   = basicSalary + hra + conveyance + medicalAllowance + specialAllowance + bonus + ot;
    const totalDeductions = pf + esi + pt + tds + advance + lwf + loan;
    const netSalary       = Math.max(totalEarnings - totalDeductions, 0);

    return {
        baseGross, basicSalary, hra, conveyance, medicalAllowance, specialAllowance,
        lopDays, lopAmount,
        pf, esi, pt, tds, advance, lwf, loan,
        totalEarnings, totalDeductions, netSalary
    };
}

// ================================================================
// Recalculate net when editable cell changes
// ================================================================
function recalcNet(idx) {
    const emp   = employees[idx];
    const bonus = parseFloat(document.querySelector(`.bonus-input[data-idx="${idx}"]`).value) || 0;
    const ot    = parseFloat(document.querySelector(`.ot-input[data-idx="${idx}"]`).value)    || 0;
    const loan  = parseFloat(document.querySelector(`.loan-input[data-idx="${idx}"]`).value)  || 0;

    const calc  = computeNet(emp, bonus, ot, loan);

    emp._bonus = bonus;
    emp._ot    = ot;
    emp._loan  = loan;
    emp._calc  = calc;
    emp._net   = calc.netSalary;

    document.getElementById(`net-${idx}`).textContent = '₹' + fmt(calc.netSalary);
    renderFooter();
}

// ================================================================
// Footer totals & KPI cards update
// ================================================================
function renderFooter() {
    const totalGross      = employees.reduce((s, e) => s + (e._calc ? e._calc.baseGross : 0), 0);
    const totalEarnings   = employees.reduce((s, e) => s + (e._calc ? e._calc.totalEarnings : 0), 0);
    const totalNet        = employees.reduce((s, e) => s + (e._net !== undefined ? e._net : 0), 0);
    const totalPf         = employees.reduce((s, e) => s + (e._calc ? e._calc.pf   : 0), 0);
    const totalEsi        = employees.reduce((s, e) => s + (e._calc ? e._calc.esi  : 0), 0);
    const totalPt         = employees.reduce((s, e) => s + (e._calc ? e._calc.pt   : 0), 0);
    const totalDeductions = employees.reduce((s, e) => s + (e._calc ? e._calc.totalDeductions : 0), 0);

    // Update KPIs
    document.getElementById('kpiEmployeeCount').textContent  = employees.length;
    document.getElementById('kpiGrossTotal').textContent      = '₹' + fmt(totalEarnings);
    document.getElementById('kpiDeductionsTotal').textContent = '₹' + fmt(totalDeductions);
    document.getElementById('kpiNetTotal').textContent        = '₹' + fmt(totalNet);

    document.getElementById('bulkPayslipFoot').innerHTML = `
        <tr class="bg-light">
            <td colspan="5" class="ps-3 py-2 text-end text-dark fw-bold">Total (${employees.length} employees)</td>
            <td colspan="6" class="text-end fw-bold text-success">₹${fmt(totalEarnings)}</td>
            <td></td>
            <td class="text-end text-danger fw-bold">₹${fmt(totalPf)}</td>
            <td class="text-end text-danger fw-bold">₹${fmt(totalEsi)}</td>
            <td class="text-end text-danger fw-bold">₹${fmt(totalPt)}</td>
            <td colspan="4" class="text-end text-danger fw-bold">₹${fmt(totalDeductions)}</td>
            <td colspan="3"></td>
            <td class="net-cell text-end fw-bold">₹${fmt(totalNet)}</td>
        </tr>`;
}

// ================================================================
// Select all / master toggle
// ================================================================
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.row-check');
    const allChecked = [...checkboxes].every(c => c.checked);
    checkboxes.forEach(c => c.checked = !allChecked);
    updateSelectAllButtonState();
    updateGenerateBtn();
}

function masterToggle(master) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = master.checked);
    updateSelectAllButtonState();
    updateGenerateBtn();
}

function updateSelectAllButtonState() {
    const checkboxes  = document.querySelectorAll('.row-check');
    const masterCheck = document.getElementById('masterCheck');
    const selectAllBtn = document.getElementById('selectAllBtn');

    if (!checkboxes.length) {
        masterCheck.checked = false;
        selectAllBtn.innerHTML = '<i class="ph ph-check-square me-1"></i> Select All';
        return;
    }

    const allChecked = [...checkboxes].every(c => c.checked);
    masterCheck.checked = allChecked;
    selectAllBtn.innerHTML = allChecked
        ? '<i class="ph ph-check-square me-1"></i> Deselect All'
        : '<i class="ph ph-check-square me-1"></i> Select All';
}

function updateGenerateBtn() {
    const count = document.querySelectorAll('.row-check:checked').length;
    const btn   = document.getElementById('generateBtn');
    btn.disabled = count === 0;
    btn.innerHTML = count
        ? `<i class="ph-duotone ph-file-plus fs-5"></i> Generate ${count} Payslip${count !== 1 ? 's' : ''}`
        : `<i class="ph-duotone ph-file-plus fs-5"></i> Generate Selected Payslips`;
    updateSelectAllButtonState();
}

// ================================================================
// Generate selected
// ================================================================
function generateSelected() {
    const fy       = document.getElementById('select_financial_year').value;
    const month    = document.getElementById('monthSelect').value;
    const monthNum = monthsMap[month] || parseInt(month, 10);

    if (!monthNum) {
        Swal.fire('Error', 'Please select a valid month.', 'warning');
        return;
    }

    const selected = [];
    document.querySelectorAll('.row-check:checked').forEach(chk => {
        const idx = parseInt(chk.dataset.idx);
        const emp = employees[idx];
        const calc = emp._calc || computeNet(emp, emp._bonus || 0, emp._ot || 0, emp._loan || 0);

        selected.push({
            emp_id:             emp.empId,
            employee_id:        emp.employee_id,
            name:               emp.name,
            basic_salary:       calc.basicSalary,
            gross_salary:       calc.baseGross,
            pf:                 calc.pf,
            esi:                calc.esi,
            ptax:               calc.pt,
            tds:                calc.tds,
            loan:               emp._loan !== undefined ? emp._loan : (emp.loan_ded || 0),
            lwf:                calc.lwf,
            performance_bonus:  emp._bonus || 0,
            overtime:           emp._ot    || 0,
            net_salary:         emp._net   !== undefined ? emp._net : calc.netSalary,
            total_working_days: emp.total_working_days || 0,
        });
    });

    if (!selected.length) {
        Swal.fire('Warning', 'Please select at least one employee.', 'warning');
        return;
    }

    const generateBtn = document.getElementById('generateBtn');
    generateBtn.disabled = true;

    Swal.fire({
        title: 'Generating Payslips...',
        html: `Processing <b>${selected.length}</b> employee payslip(s). Please wait...`,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url:  '{{ route("payroll.bulk.generate") }}',
        type: 'POST',
        data: {
            _token:         '{{ csrf_token() }}',
            financial_year: fy,
            month:          monthNum,
            employees:      selected,
        },
        success: function(res) {
            if (res.success) {
                Swal.fire({
                    icon:             'success',
                    title:            'Payslips Generated!',
                    text:             res.message,
                    confirmButtonText:'OK',
                    confirmButtonColor:'#16a34a',
                }).then(() => loadEmployees());
            } else {
                Swal.fire('Error', res.message || 'Generation failed.', 'error');
                generateBtn.disabled = false;
            }
        },
        error: function(xhr) {
            generateBtn.disabled = false;
            let errorMsg = 'Server error occurred during payslip generation.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire('Error', errorMsg, 'error');
        }
    });
}

// ================================================================
// Format helper
// ================================================================
function fmt(n) {
    return parseFloat(n || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>
@endsection
