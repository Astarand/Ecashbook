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
                        <li class="breadcrumb-item"><a href="#">HR &amp; Payroll Management</a></li>
                        <li class="breadcrumb-item active">Multiple Generate Payslip</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2 class="mb-0 text-dark fw-bold">Bulk Payslip Generation</h2>
                    <p class="text-muted small mb-0">Select FY and month to preview and generate payslips for all eligible employees at once.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Filter Card -->
    <div class="row mb-4">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="ph-duotone ph-funnel text-primary fs-5"></i> Select Period
                    </h6>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold text-dark small">Financial Year <span class="text-danger">*</span></label>
                            <select class="form-select" id="select_financial_year">
                                <option value="">Select FY</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold text-dark small">Month <span class="text-danger">*</span></label>
                            <select class="form-select" id="monthSelect">
                                <option value="">Select Month</option>
                                <option>January</option><option>February</option><option>March</option>
                                <option>April</option><option>May</option><option>June</option>
                                <option>July</option><option>August</option><option>September</option>
                                <option>October</option><option>November</option><option>December</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" id="checkPayslipBtn" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="ph-duotone ph-magnifying-glass fs-5"></i> Load Employees
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="d-none">

        <!-- Summary bar -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-light-primary text-primary fw-bold px-3 py-2 rounded-pill" id="resultBadge">0 Employees</span>
                    <span class="text-muted small" id="periodLabel"></span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" id="selectAllBtn" onclick="toggleSelectAll()">
                        <i class="ph ph-check-square me-1"></i> Select All
                    </button>
                    <button class="btn btn-success px-4" id="generateBtn" onclick="generateSelected()" disabled>
                        <i class="ph-duotone ph-file-plus me-1 fs-5"></i> Generate Selected Payslips
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 align-middle table-bordered" id="bulkPayslipTable" style="font-size:12px;">
                        <thead class="bg-light">
                            <tr class="text-secondary fw-bold">
                                <th class="ps-3 py-3" rowspan="2" style="width:36px;vertical-align:middle;">
                                    <input type="checkbox" id="masterCheck" class="form-check-input" onchange="masterToggle(this)">
                                </th>
                                <th class="py-3" rowspan="2" style="vertical-align:middle;">#</th>
                                <th class="py-3" rowspan="2" style="min-width:70px;vertical-align:middle;">Emp ID</th>
                                <th class="py-3" rowspan="2" style="min-width:120px;vertical-align:middle;">Employee Name</th>
                                <th class="py-3" rowspan="2" style="vertical-align:middle;">Status</th>
                                {{-- Earnings --}}
                                <th class="py-2 text-center text-success bg-success bg-opacity-10" colspan="7">Earnings</th>
                                {{-- Deductions --}}
                                <th class="py-2 text-center text-danger bg-danger bg-opacity-10" colspan="7">Deductions</th>
                                {{-- Editable --}}
                                <th class="py-2 text-center text-primary bg-primary bg-opacity-10" colspan="3">Manual Entry</th>
                                {{-- Net --}}
                                <th class="py-2 text-center text-success" rowspan="2" style="min-width:110px;vertical-align:middle;">Net Payable</th>
                            </tr>
                            <tr class="text-secondary fw-bold" style="font-size:11px;">
                                {{-- Earnings sub --}}
                                <th class="py-2 bg-success bg-opacity-10">Gross Salary</th>
                                <th class="py-2 bg-success bg-opacity-10">Basic</th>
                                <th class="py-2 bg-success bg-opacity-10">HRA</th>
                                <th class="py-2 bg-success bg-opacity-10">Conveyance</th>
                                <th class="py-2 bg-success bg-opacity-10">Medical</th>
                                <th class="py-2 bg-success bg-opacity-10">Special Allow.</th>
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
                                <th class="py-2 bg-primary bg-opacity-10" style="min-width:110px;">Bonus</th>
                                <th class="py-2 bg-primary bg-opacity-10" style="min-width:110px;">Overtime (₹)</th>
                                <th class="py-2 bg-primary bg-opacity-10" style="min-width:110px;">Loan Ded.</th>
                            </tr>
                        </thead>
                        <tbody id="bulkPayslipBody"></tbody>
                        <tfoot class="bg-light fw-bold" id="bulkPayslipFoot"></tfoot>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-muted small mt-2">
            <i class="ph ph-info text-primary me-1"></i>
            Performance Bonus, Overtime and Loan Deduction are editable — Net Salary updates live.
        </p>
    </div>

    <!-- Empty state -->
    <div id="emptyState" class="d-none text-center py-5">
        <i class="ph-duotone ph-check-circle text-success" style="font-size:3rem;"></i>
        <h5 class="fw-bold text-dark mt-3">All Payslips Already Generated</h5>
        <p class="text-muted">Every active employee already has a payslip for the selected period.</p>
    </div>

</div>

<!-- Generate Progress Modal -->
<div class="modal fade" id="generateModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <h6 class="fw-bold text-dark mb-1">Generating Payslips…</h6>
                <p class="text-muted small mb-0" id="generateProgress">Please wait.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .editable-cell {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 4px 6px;
        width: 90px;
        font-size: 12px;
        text-align: right;
        background: #fff;
        transition: border-color .15s;
    }
    .editable-cell:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102,126,234,.18);
    }
    .net-cell { font-weight: 700; color: #198754; }
    #bulkPayslipTable thead th { white-space: nowrap; }
    #bulkPayslipTable td { white-space: nowrap; }
</style>

@endsection

@section('page-script')
<script>
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
        showToast('Please select both Financial Year and Month.', 'warning');
        return;
    }

    const monthsMap = {
        'January': 1, 'February': 2, 'March': 3, 'April': 4,
        'May': 5, 'June': 6, 'July': 7, 'August': 8,
        'September': 9, 'October': 10, 'November': 11, 'December': 12
    };

    const selectedMonthNum = isNaN(month) ? monthsMap[month] : parseInt(month, 10);

    if (selectedMonthNum) {
        const [fyStartStr, fyEndStr] = fy.split('-');
        const fyStart = parseInt(fyStartStr, 10);
        const fyEnd   = parseInt(fyEndStr, 10);
        const selectedYear = (selectedMonthNum <= 3) ? fyEnd : fyStart;

        const now = new Date();
        const currentYear  = now.getFullYear();
        const currentMonth = now.getMonth() + 1; // JS months 0-indexed

        const isFuture = (selectedYear > currentYear) ||
                        (selectedYear === currentYear && selectedMonthNum > currentMonth);

        if (isFuture) {
            showToast('Future month cannot be selected.', 'warning');
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
            alert('Failed to load employees. Please try again.');
        })
        .always(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="ph-duotone ph-magnifying-glass fs-5"></i> Load Employees';
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
            // Show effective last working day if resigned in this payroll month
            const lastDayNote = emp.effective_last_day
                ? `<small class="text-muted d-block">Last day: ${emp.effective_last_day}</small>`
                : (dateStr ? `<small class="text-muted">${dateStr}</small>` : '');
            statusBadge = `<span class="badge bg-light-${color} text-${color} d-block mb-1">${label}</span>${lastDayNote}`;
        } else {
            statusBadge = `<span class="badge bg-light-success text-success">${emp.emp_status || 'Active'}</span>`;
        }

        // Compute initial salary values from server data
        const calc = computeNet(emp, 0, 0, parseFloat(emp.loan_ded || 0));

        // Store computed values on emp
        emp._calc  = calc;
        emp._bonus = 0;
        emp._ot    = 0;
        emp._loan  = parseFloat(emp.loan_ded || 0);
        emp._net   = calc.netSalary;

        html += `<tr data-idx="${idx}">
            <td class="ps-3"><input type="checkbox" class="form-check-input row-check" data-idx="${idx}" onchange="updateGenerateBtn()" checked></td>
            <td class="text-muted">${idx + 1}</td>
            <td class="fw-bold text-dark">${emp.employee_id || '—'}</td>
            <td class="fw-bold text-dark" style="min-width:120px;">${emp.name || '—'}</td>
            <td>${statusBadge}</td>

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
            <td class="net-cell text-end" id="net-${idx}">₹${fmt(calc.netSalary)}</td>
        </tr>`;
    });

    document.getElementById('bulkPayslipBody').innerHTML = html;
    renderFooter();
    updateGenerateBtn();
}

// ================================================================
// Core salary computation (mirrors generate-payslip.blade.php JS exactly)
// For resigned-this-month employees, lopDays comes directly from the
// server (30 - resignationDay) — do NOT recalculate from attendance.
// ================================================================
function computeNet(emp, bonus, ot, loanVal) {
    const gross      = parseFloat(emp.total_addition || 0);
    const perDay     = parseFloat(emp.per_day_salary || 0);
    const basicPct   = parseFloat(emp.basic_percentage != null ? emp.basic_percentage : 50);

    // ---------------------------------------------------------------
    // LOP calculation
    // For resigned-this-month: server already computed lopDays = 30 - resignDay
    // For active: derive from attendance (absent + late + early logout)
    // ---------------------------------------------------------------
    let lopDays, lopAmount;
    if (emp.resigned_this_month) {
        // Use server-computed absent count (= 30 - resignation day)
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

    // PF: server sends computed pf value; if > 0, applicable — recalculate dynamically
    let pf = 0;
    if (parseFloat(emp.pf || 0) > 0) {
        pf = Math.min(parseFloat((basicSalary * 0.12).toFixed(2)), 1800);
    }

    // ESI: server sends computed esi; recalculate with overtime impact
    let esi = 0;
    if (parseFloat(emp.esi_amount || 0) > 0) {
        const esiBase = baseGross + ot;
        esi = (esiBase <= 21000) ? parseFloat((esiBase * 0.0075).toFixed(2)) : 0;
    }

    // PT: server sends computed ptax; recalculate with bonus+ot impact
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
// Footer totals
// ================================================================
function renderFooter() {
    const totalEarnings   = employees.reduce((s, e) => s + (e._calc ? e._calc.totalEarnings : 0), 0);
    const totalNet        = employees.reduce((s, e) => s + (e._net !== undefined ? e._net : 0), 0);
    const totalPf         = employees.reduce((s, e) => s + (e._calc ? e._calc.pf   : 0), 0);
    const totalEsi        = employees.reduce((s, e) => s + (e._calc ? e._calc.esi  : 0), 0);
    const totalPt         = employees.reduce((s, e) => s + (e._calc ? e._calc.pt   : 0), 0);
    const totalDeductions = employees.reduce((s, e) => s + (e._calc ? e._calc.totalDeductions : 0), 0);

    document.getElementById('bulkPayslipFoot').innerHTML = `
        <tr>
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
        ? `<i class="ph-duotone ph-file-plus me-1 fs-5"></i> Generate ${count} Payslip${count !== 1 ? 's' : ''}`
        : `<i class="ph-duotone ph-file-plus me-1 fs-5"></i> Generate Selected Payslips`;
    updateSelectAllButtonState();
}

// ================================================================
// Generate selected
// ================================================================
function generateSelected() {
    const fy       = document.getElementById('select_financial_year').value;
    const month    = document.getElementById('monthSelect').value;
    const monthNum = new Date(`1 ${month} 2000`).getMonth() + 1;

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

    if (!selected.length) return;

    document.getElementById('generateProgress').textContent = `Generating ${selected.length} payslip(s)…`;
    const modal = new bootstrap.Modal(document.getElementById('generateModal'));
    modal.show();

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
            modal.hide();
            if (res.success) {
                Swal.fire({
                    icon:             'success',
                    title:            'Done!',
                    text:             res.message,
                    confirmButtonText:'OK',
                    confirmButtonColor:'#198754',
                }).then(() => loadEmployees());
            } else {
                Swal.fire('Error', res.message || 'Generation failed.', 'error');
            }
        },
        error: function() {
            modal.hide();
            Swal.fire('Error', 'Server error. Please try again.', 'error');
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
