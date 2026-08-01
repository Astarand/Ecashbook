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
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
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

    <!-- Result Section (hidden until loaded) -->
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
                    <table class="table m-0 align-middle" id="bulkPayslipTable">
                        <thead class="bg-light">
                            <tr class="text-secondary small fw-bold">
                                <th class="ps-3 py-3" style="width:40px;">
                                    <input type="checkbox" id="masterCheck" class="form-check-input" onchange="masterToggle(this)">
                                </th>
                                <th class="py-3">#</th>
                                <th class="py-3">Emp ID</th>
                                <th class="py-3">Employee Name</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Basic Salary</th>
                                <th class="py-3">Gross Salary</th>
                                <th class="py-3">Working Days</th>
                                <th class="py-3" style="min-width:120px;">Performance Bonus</th>
                                <th class="py-3" style="min-width:110px;">Overtime (₹)</th>
                                <th class="py-3" style="min-width:120px;">Loan Deduction</th>
                                <th class="py-3 fw-bold text-success">Net Salary</th>
                            </tr>
                        </thead>
                        <tbody id="bulkPayslipBody">
                        </tbody>
                        <tfoot class="bg-light fw-bold" id="bulkPayslipFoot">
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <p class="text-muted small mt-2">
            <i class="ph ph-info text-primary me-1"></i>
            Performance Bonus, Overtime and Loan Deduction are editable — Net Salary updates live.
        </p>
    </div>

    <!-- Empty state (no employees) -->
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
        padding: 5px 8px;
        width: 100%;
        font-size: 13px;
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
    const cmonth   = today.getMonth(); // 0-based
    const fyStart  = cmonth >= 3 ? cyear : cyear - 1;

    for (let y = fyStart - 1; y <= fyStart + 1; y++) {
        const opt = document.createElement('option');
        opt.value = `${y}-${y + 1}`;
        opt.text  = `FY ${y}-${y + 1}`;
        if (y === fyStart) opt.selected = true;
        fySelect.appendChild(opt);
    }

    // Default to previous month
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

    // =========================================================
    // Future Month Validation Logic
    // =========================================================
    const monthsMap = {
        'January': 1, 'February': 2, 'March': 3, 'April': 4,
        'May': 5, 'June': 6, 'July': 7, 'August': 8,
        'September': 9, 'October': 10, 'November': 11, 'December': 12
    };

    // Convert month string to integer (1-12)
    const selectedMonthNum = isNaN(month) ? monthsMap[month] : parseInt(month, 10);

    if (selectedMonthNum) {
        const [fyStartStr, fyEndStr] = fy.split('-');
        const fyStart = parseInt(fyStartStr, 10);
        const fyEnd   = parseInt(fyEndStr, 10);

        // In an Indian FY (Apr-Mar), Jan-Mar belong to fyEnd, Apr-Dec belong to fyStart
        const selectedYear = (selectedMonthNum <= 3) ? fyEnd : fyStart;

        const now = new Date();
        const currentYear  = now.getFullYear();
        const currentMonth = now.getMonth(); // JS months are 0-indexed (0 = Jan)

        // Check if selected period is strictly in the future
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
// Render table
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
        // Build status badge
        const isResigned    = emp.emp_status === 'Resigned';
        const isTerminated  = emp.emp_status === 'Terminated';
        let statusBadge;
        if (isResigned || isTerminated) {
            const dateStr = emp.regine_date
                ? new Date(emp.regine_date).toLocaleDateString('en-IN', { day:'2-digit', month:'short', year:'numeric' })
                : '';
            const label   = isResigned ? 'Resigned' : 'Terminated';
            const color   = isResigned ? 'warning' : 'danger';
            statusBadge   = `<span class="badge bg-light-${color} text-${color} d-block mb-1">${label}</span>`
                          + (dateStr ? `<small class="text-muted">${dateStr}</small>` : '');
        } else {
            statusBadge = `<span class="badge bg-light-success text-success">${emp.emp_status || 'Active'}</span>`;
        }

        html += `<tr data-idx="${idx}">
            <td class="ps-3">
                <input type="checkbox" class="form-check-input row-check" data-idx="${idx}" onchange="updateGenerateBtn()">
            </td>
            <td class="text-muted">${idx + 1}</td>
            <td class="fw-bold text-dark">${emp.employee_id || '—'}</td>
            <td class="fw-bold text-dark">${emp.name || '—'}</td>
            <td>${statusBadge}</td>
            <td>₹${fmt(emp.basic_salary)}</td>
            <td>₹${fmt(emp.gross_salary)}</td>
            <td class="text-center">${workingDays}</td>
            <td>
                <input type="number" min="0" step="0.01" class="editable-cell bonus-input"
                    data-idx="${idx}" value="0"
                    onchange="recalcNet(${idx})" oninput="recalcNet(${idx})">
            </td>
            <td>
                <input type="number" min="0" step="0.01" class="editable-cell ot-input"
                    data-idx="${idx}" value="0"
                    onchange="recalcNet(${idx})" oninput="recalcNet(${idx})">
            </td>
            <td>
                <input type="number" min="0" step="0.01" class="editable-cell loan-input"
                    data-idx="${idx}" value="${emp.loan_ded || 0}"
                    onchange="recalcNet(${idx})" oninput="recalcNet(${idx})">
            </td>
            <td class="net-cell" id="net-${idx}">₹${fmt(emp.net_salary)}</td>
        </tr>`;
    });

    document.getElementById('bulkPayslipBody').innerHTML = html;
    renderFooter(data);
    updateGenerateBtn();
}

function renderFooter(data) {
    const totalGross = data.reduce((s, e) => s + (e.gross_salary || 0), 0);
    const totalNet   = data.reduce((s, e) => s + (e.net_salary   || 0), 0);
    document.getElementById('bulkPayslipFoot').innerHTML = `
        <tr>
            <td colspan="5" class="ps-3 py-2 text-end text-dark">Total (${data.length} employees)</td>
            <td></td>
            <td>₹${fmt(totalGross)}</td>
            <td colspan="3"></td>
            <td></td>
            <td class="net-cell">₹${fmt(totalNet)}</td>
        </tr>`;
}

// ================================================================
// Recalculate net salary when editable cell changes
// ================================================================
function recalcNet(idx) {
    const emp     = employees[idx];
    const bonus   = parseFloat(document.querySelector(`.bonus-input[data-idx="${idx}"]`).value) || 0;
    const ot      = parseFloat(document.querySelector(`.ot-input[data-idx="${idx}"]`).value)    || 0;
    const loan    = parseFloat(document.querySelector(`.loan-input[data-idx="${idx}"]`).value)  || 0;

    const gross   = (emp.gross_salary || 0);
    const pf      = (emp.pf           || 0);
    const esi     = (emp.esi_amount   || 0);
    const pt      = (emp.ptax_amount  || 0);
    const tds     = (emp.tds_amount   || 0);
    const lwf     = (emp.lwf_amount   || 0);

    const net = gross + bonus + ot - pf - esi - pt - tds - loan - lwf;
    const netRounded = Math.max(net, 0);

    // Update display
    document.getElementById(`net-${idx}`).textContent = '₹' + fmt(netRounded);

    // Store back
    emp._bonus   = bonus;
    emp._ot      = ot;
    emp._loan    = loan;
    emp._net     = netRounded;

    // Refresh footer
    const totalNet = employees.reduce((s, e, i) => {
        const n = e._net !== undefined ? e._net : (e.net_salary || 0);
        return s + n;
    }, 0);
    const totalGross = employees.reduce((s, e) => s + (e.gross_salary || 0), 0);

    document.getElementById('bulkPayslipFoot').innerHTML = `
        <tr>
            <td colspan="5" class="ps-3 py-2 text-end text-dark">Total (${employees.length} employees)</td>
            <td></td>
            <td>₹${fmt(totalGross)}</td>
            <td colspan="3"></td>
            <td></td>
            <td class="net-cell">₹${fmt(totalNet)}</td>
        </tr>`;
}

// ================================================================
// Select all / master toggle
// ================================================================
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.row-check');
    const allChecked = [...checkboxes].every(c => c.checked);
    checkboxes.forEach(c => c.checked = !allChecked);
    document.getElementById('masterCheck').checked = !allChecked;
    document.getElementById('selectAllBtn').textContent = allChecked ? '☐ Select All' : '✓ Deselect All';
    updateGenerateBtn();
}

function masterToggle(master) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = master.checked);
    updateGenerateBtn();
}

function updateGenerateBtn() {
    const count = document.querySelectorAll('.row-check:checked').length;
    const btn   = document.getElementById('generateBtn');
    btn.disabled = count === 0;
    btn.innerHTML = count
        ? `<i class="ph-duotone ph-file-plus me-1 fs-5"></i> Generate ${count} Payslip${count !== 1 ? 's' : ''}`
        : `<i class="ph-duotone ph-file-plus me-1 fs-5"></i> Generate Selected Payslips`;
}

// ================================================================
// Generate selected
// ================================================================
function generateSelected() {
    const fy    = document.getElementById('select_financial_year').value;
    const month = document.getElementById('monthSelect').value;
    const monthNum = new Date(`1 ${month} 2000`).getMonth() + 1;

    const selected = [];
    document.querySelectorAll('.row-check:checked').forEach(chk => {
        const idx = parseInt(chk.dataset.idx);
        const emp = employees[idx];
        selected.push({
            emp_id:            emp.empId,
            employee_id:       emp.employee_id,
            name:              emp.name,
            basic_salary:      emp.basic_salary  || 0,
            gross_salary:      emp.gross_salary  || 0,
            pf:                emp.pf            || 0,
            esi:               emp.esi_amount    || 0,
            ptax:              emp.ptax_amount   || 0,
            tds:               emp.tds_amount    || 0,
            loan:              emp._loan !== undefined ? emp._loan : (emp.loan_ded || 0),
            lwf:               emp.lwf_amount    || 0,
            performance_bonus: emp._bonus        || 0,
            overtime:          emp._ot           || 0,
            net_salary:        emp._net !== undefined ? emp._net : (emp.net_salary || 0),
            total_working_days:emp.total_working_days || 0,
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
