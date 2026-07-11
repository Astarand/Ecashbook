@extends('App.Layout')

@section('container')
<style>
    .editable-field:read-only {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .editable-field:not(:read-only) {
        background-color: #fff3cd;
        border-color: #ffc107;
    }

    .edit-btn {
        border-left: none;
    }

    .input-group .form-control:not(:last-child) {
        border-right: none;
    }
</style>
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                        <li class="breadcrumb-item"><a href="#">HR, Payroll & Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payslip Management</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-generate-payslip-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payslip Management</h2>
                    </div>
                    <a href="{{ route('payroll.payslip_update') }}" class="btn btn-primary float-end">
                        Update Payslip
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header  align-items-center justify-content-between py-3">
                            <h4 class="text-center">
                                Generate Payslip
                            </h4>
                        </div>
                        <div class="card-body">
                            <form id="payslip_form">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Select Employee Name <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="employee_id" id="employee_id">
                                            <option selected disabled>Select Employee</option>
                                            @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Select Financial Year <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="select_financial_year"
                                            name="select_financial_year">
                                            <option selected>Select Financial Year</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Select Month <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="monthSelect" name="monthSelect">
                                            <option selected>Select Month</option>
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
                                    <div class="col-md-3">
                                        <label class="form-label"></label>
                                        <button type="button" id="checkPayslipBtn"
                                            class="btn btn-primary w-100 mt-2">Submit</button>
                                    </div>
                                </div>
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section to Show Payslip Details -->
        <div id="payslip_result" class="mt-4">

        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
<script>
$(document).ready(function() {
    // helper: safely parse numbers from text (removes commas, currency, etc.)
    function parseNumber(txt) {
        if (txt === undefined || txt === null) return 0;
        const cleaned = String(txt).replace(/[^0-9.-]/g, '');
        return parseFloat(cleaned) || 0;
    }

    $('#checkPayslipBtn').click(function() {
        var employeeId = $('#employee_id').val();
        var financialYear = $('#select_financial_year').val();
        var month = $('#monthSelect').val();

        if (!employeeId || !financialYear || !month) {
            showToast('Please select all fields.', 'error');
            return;
        }

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "{{ route('check.payslip') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                employee_id: employeeId,
                select_financial_year: financialYear,
                monthSelect: month
            },
            success: function(response) {
                // console.log(response);

                if (response.status === 'exists') {
                    $('#payslip_result').html(`
                        <div class="text-center mt-3">
                            <a href="/download-payslip/${response.payslipId}" class="btn btn-success" target="_blank">
                                Download Payslip
                            </a>
                        </div>
                    `);
                } else {
                    window.latestPayslipResponse = response; // Store for calculation use
                    let emp = response.employee || {};
                    let month = response.monthDetails || {};
                    let attendance = response.attendanceDetails || {};
                    let salary = response.salaryDetails || {};

                   $('#payslip_result').html(`
                        <div class="col-sm-12">
                            <!-- Header Card -->
                            <div class="card border shadow-sm mb-3">
                                <div class="card-body p-3" style="background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="text-white mb-1 fw-bold">
                                                Payslip Generation - ${response.month_name}, ${response.financialYear}
                                            </h5>
                                            <small class="text-white opacity-75">Complete employee payslip information</small>
                                        </div>
                                        <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                            <span class="badge bg-white text-dark px-3 py-2">
                                                #${response.payslipNo.payslip_no}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Card -->
                            <div class="card border shadow-sm">
                                <div class="card-body p-4">

                                    <!-- Basic Information -->
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-semibold">Payslip Number</label>
                                            <input type="text" name="payslipNo" id="payslipNo"
                                                class="form-control bg-light"
                                                value="${response.payslipNo.payslip_no}" disabled>
                                            <input type="hidden" name="empId" id="empId" value="${emp.empId}">
                                            <input type="hidden" name="financialYear" id="financialYear" value="${response.financialYear}">
                                            <input type="hidden" name="month" id="month" value="${response.month}">
                                            <input type="hidden" name="emp_response" id="emp_response"
                                                value='${encodeURIComponent(JSON.stringify(emp))}'>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small fw-semibold">Generation Date</label>
                                            <input type="date" class="form-control" name="generate_date"
                                                id="generate_date" value="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>

                                    <!-- Employee Details Section -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            Employee Information
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Employee Name</div>
                                                <div class="fw-semibold">${emp.name || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Employee ID</div>
                                                <div class="fw-semibold">${emp.employee_id || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Joining Date</div>
                                                <div class="fw-semibold">${emp.joining_date || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Department</div>
                                                <div class="fw-semibold">${emp.dept_name || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Designation</div>
                                                <div class="fw-semibold">${emp.designation_name || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">PF Number</div>
                                                <div class="fw-semibold">${emp.epf_no || 'N/A'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Email Address</div>
                                                <div class="fw-semibold">${emp.email || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Phone Number</div>
                                                <div class="fw-semibold">${emp.phone || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">PAN Number</div>
                                                <div class="fw-semibold">${emp.pan_number || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Aadhaar Number</div>
                                                <div class="fw-semibold">${emp.aadhaar_number || '-'}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Details Section -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            Bank Account Details
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Bank Name</div>
                                                <div class="fw-semibold">${emp.bank_name || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">Branch</div>
                                                <div class="fw-semibold">${emp.bank_branch || '-'}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-muted mb-1">IFSC Code</div>
                                                <div class="fw-semibold">${emp.ifsc || '-'}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted mb-1">Account Holder Name</div>
                                                <div class="fw-semibold">${emp.account_holder_name || '-'}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted mb-1">Account Number</div>
                                                <div class="fw-semibold">${emp.account_number || '-'}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Month Details Section -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            Month Statistics - ${response.month_name}, ${response.financialYear}
                                        </h6>
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <div class="small text-muted mb-1">Total Days</div>
                                                    <div class="h5 mb-0 fw-bold">${month.total_days || 0}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <div class="small text-muted mb-1">Working Days</div>
                                                    <div class="h5 mb-0 fw-bold text-success">${month.total_working_days || 0}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <div class="small text-muted mb-1">Holidays</div>
                                                    <div class="h5 mb-0 fw-bold text-warning">${month.total_holidays || 0}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border rounded p-2 text-center bg-light">
                                                    <div class="small text-muted mb-1">Weekends</div>
                                                    <div class="h5 mb-0 fw-bold text-info">${month.total_weekends || 0}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attendance Details Section -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            Attendance Summary
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center small">Total Present</th>
                                                        <th class="text-center small">On-Time</th>
                                                        <th class="text-center small">Late</th>
                                                        <th class="text-center small">Early Logout</th>
                                                        <th class="text-center small">Leaves</th>
                                                        <th class="text-center small">Holidays</th>
                                                        <th class="text-center small">Weekends</th>
                                                        <th class="text-center small">Overtime</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td class="fw-bold text-success">${attendance.total_present || 0}</td>
                                                        <td class="fw-semibold">${attendance.total_present_on_time || 0}</td>
                                                        <td class="fw-semibold text-warning">${attendance.total_present_late || 0}</td>
                                                        <td class="fw-semibold text-danger">${attendance.total_early_logout || 0}</td>
                                                        <td class="fw-semibold">${attendance.total_leave_approved || 0}</td>
                                                        <td class="fw-semibold">${attendance.total_holiday || 0}</td>
                                                        <td class="fw-semibold">${attendance.total_office_weekend || 0}</td>
                                                        <td class="fw-semibold text-primary">${attendance.total_overtime_hours || '00:00:00'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Salary Components Section -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            Salary Components
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-muted small">Gross Salary</td>
                                                        <td class="text-end fw-semibold">₹ ${salary.gross_salary || 0}</td>
                                                        <td class="text-muted small">Base Salary</td>
                                                        <td class="text-end fw-semibold">₹ ${salary.base_salary || 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted small">Per Day Salary</td>
                                                        <td class="text-end fw-semibold">₹ ${salary.per_day_salary || 0}</td>
                                                        <td class="text-muted small">Late Deduction Days</td>
                                                        <td class="text-end fw-semibold text-danger">${salary.lateDeductionDays || 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted small">Early Logout Deduction</td>
                                                        <td class="text-end fw-semibold text-danger">${attendance.totalEarlyLogoutDeductionDays || 0}</td>
                                                        <td class="text-muted small">HRA</td>
                                                        <td class="text-end fw-semibold">₹ ${salary.hra || 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted small">Conveyance Allowance</td>
                                                        <td class="text-end fw-semibold">₹ ${salary.conveyance || 0}</td>
                                                        <td class="text-muted small">Medical Allowance</td>
                                                        <td class="text-end fw-semibold">₹ ${salary.medical_allowance || 0}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted small">Special Bonus</td>
                                                        <td class="text-end fw-semibold text-success">₹ ${salary.special_bonus || 0}</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Final Salary Calculation -->
                                    <div id="finalSalaryCalc"></div>

                                    <!-- Notes Section -->
                                    <div class="mb-4">
                                        <label class="form-label text-muted small fw-semibold">Additional Notes / Remarks (Optional)</label>
                                        <textarea rows="3" name="notes" id="notes" class="form-control"
                                            placeholder="Enter any additional notes, remarks, or special instructions..."></textarea>
                                    </div>

                                    <!-- Generate Button -->
                                    <div class="d-grid">
                                        <button type="button" class="btn btn-dark btn-lg py-3" id="generatePayslipBtn">
                                            <i class="ph-duotone ph-file-pdf me-2"></i>Generate Payslip PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);


                    // Trigger calculation after render
                    setTimeout(function() { calculateNetSalary(); }, 150);
                }
            }
        });
    });

    // Include full calculation function from previous answer
    function calculateNetSalary() {
        function numberToWords(num) {
            const a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ',
                'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            if ((num = num.toString()).length > 9) return 'Overflow';
            let n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return;
            let str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') +
                (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
            return str.trim() + 'Only';
        }

        const parseNumber = (v) => parseFloat(String(v).replace(/[^0-9.]/g, '')) || 0;

        const salary = window.latestPayslipResponse?.salaryDetails || {};
        const attendance = window.latestPayslipResponse?.attendanceDetails || {};

        const gross_salary = parseNumber(salary.gross_salary);
        const per_day_salary = parseNumber(salary.per_day_salary);
        const total_absent_days_for_salary = parseNumber(attendance.total_absent);
        const lateDeductionDays = parseNumber(salary.lateDeductionDays);
        const totalEarlyLogoutDeductionDays = parseNumber(attendance.totalEarlyLogoutDeductionDays);
        const advanceAmount = parseNumber(salary.advance_amount || 0);

        // Editable fields
        const manualBonus = parseNumber($('#performance_bonus_input').val() || 0);
        const manualOvertime = parseNumber($('#overtime_input').val() || 0);
        // const tds = parseNumber($('#tds_input').val() || salary.tds);
        const loan = parseNumber($('#loan_input').val() || salary.loan);

        let tdsFinal = 0;

        if (parseNumber(salary.tds) > 0) {
            tdsFinal = parseNumber($('#tds_input').val() || salary.tds);
        }

        // Loss of Pay (LOP)
        const diduct = per_day_salary * (total_absent_days_for_salary + lateDeductionDays + totalEarlyLogoutDeductionDays);

        // Base gross (after LOP)
        const baseGross = gross_salary - diduct;

        // Fixed components (stay constant)
        const medicalAllowance = 1250;
        const conveyance = 1600;
        const basicSalary = baseGross * 0.5;
        const hra = basicSalary * 0.5;
        let specialAllowance = baseGross - (basicSalary + hra + medicalAllowance + conveyance);
        if (specialAllowance < 0) specialAllowance = 0;

        // --- DEDUCTIONS ---
        // const pf = basicSalary * 0.12;
        let pf = 0;
        if (parseNumber(salary.provident_fund) > 0) {
            pf = basicSalary * 0.12;
        }

        // Adjust ESI and PT based on rules
        // const esiGrossCheck = baseGross + manualOvertime; // Only overtime affects ESI
        // const esi = (esiGrossCheck <= 21000) ? (esiGrossCheck * 0.0075) : 0;
        let esi = 0;
        if (parseNumber(salary.esi) > 0) {
            const esiBase = baseGross + manualOvertime;
            esi = (esiBase <= 21000) ? (esiBase * 0.0075) : 0;
        }

        // const ptGrossCheck = baseGross + manualBonus + manualOvertime; // Bonus & overtime affect PT
        // let ptax = 0;
        // if (ptGrossCheck > 10000 && ptGrossCheck <= 15000) ptax = 110;
        // else if (ptGrossCheck > 15000 && ptGrossCheck <= 25000) ptax = 130;
        // else if (ptGrossCheck > 25000 && ptGrossCheck <= 40000) ptax = 150;
        // else if (ptGrossCheck > 40000) ptax = 200;
        let ptax = 0;
        if (parseNumber(salary.ptax) > 0) {
            const ptBase = baseGross + manualBonus + manualOvertime;

            if (ptBase > 10000 && ptBase <= 15000) ptax = 110;
            else if (ptBase > 15000 && ptBase <= 25000) ptax = 130;
            else if (ptBase > 25000 && ptBase <= 40000) ptax = 150;
            else if (ptBase > 40000) ptax = 200;
        }

        // Totals
        const totalEarnings = basicSalary + hra + conveyance + medicalAllowance + specialAllowance + manualBonus + manualOvertime;
        const totalDeductions_show = pf + esi + ptax + tdsFinal + loan + advanceAmount;
        const totalDeductions = totalDeductions_show + diduct;

        const netSalary = diduct + (totalEarnings - totalDeductions);
        // const netSalary = totalEarnings - totalDeductions;

        // --- UPDATE UI ---
        let html = `
            <div class="mb-4">
                <h6 class="border-bottom pb-2 mb-3 fw-bold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    Final Salary Calculation
                </h6>

                <div class="row g-3">
                    <!-- Earnings Column -->
                    <div class="col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-light border-bottom">
                                <h6 class="mb-0 fw-bold text-success">EARNINGS</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Gross Salary:</span>
                                    <span class="fw-semibold">₹${baseGross.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Basic Salary:</span>
                                    <span class="fw-semibold">₹${basicSalary.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">HRA:</span>
                                    <span class="fw-semibold">₹${hra.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Conveyance Allowance:</span>
                                    <span class="fw-semibold">₹${conveyance.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Medical Allowance:</span>
                                    <span class="fw-semibold">₹${medicalAllowance.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted small">Special Allowance:</span>
                                    <span class="fw-semibold">₹${specialAllowance.toFixed(2)}</span>
                                </div>

                                <hr class="my-2">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Performance Bonus:</span>
                                    <div class="input-group input-group-sm" style="width: 130px;">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="performance_bonus_input" value="${manualBonus}"
                                            min="0" step="0.01" class="form-control text-end">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small">Overtime:</span>
                                    <div class="input-group input-group-sm" style="width: 130px;">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="overtime_input" value="${manualOvertime}"
                                            min="0" step="0.01" class="form-control text-end">
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-3 bg-light p-2 rounded d-flex justify-content-between">
                                    <span class="fw-bold text-success">Total Earnings:</span>
                                    <span class="fw-bold text-success">₹${totalEarnings.toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions Column -->
                    <div class="col-md-6">
                        <div class="card border shadow-sm h-100">
                            <div class="card-header bg-light border-bottom">
                                <h6 class="mb-0 fw-bold text-danger">DEDUCTIONS</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">PF (12% of Basic):</span>
                                    <span class="fw-semibold text-danger">₹${pf.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">ESI (0.75% if Gross ≤ 21K):</span>
                                    <span class="fw-semibold text-danger">₹${esi.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">PT:</span>
                                    <span class="fw-semibold text-danger">₹${ptax.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Advance Deduction:</span>
                                    <span class="fw-semibold text-danger">₹${advanceAmount.toFixed(2)}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted small">Loss of Pay (LOP):</span>
                                    <span class="fw-semibold text-danger">₹${diduct.toFixed(2)}</span>
                                </div>
                                
                                

                                <hr class="my-2">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">TDS:</span>
                                    <div class="input-group input-group-sm" style="width: 130px;">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="tds_input" value="${tdsFinal}"
                                            min="0" step="0.01" class="form-control text-end" readonly>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted small">Loan Deduction:</span>
                                    <div class="input-group input-group-sm" style="width: 130px;">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="loan_input" value="${loan}"
                                            min="0" step="0.01" class="form-control text-end">
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-3 bg-light p-2 rounded d-flex justify-content-between">
                                    <span class="fw-bold text-danger">Total Deductions:</span>
                                    <span class="fw-bold text-danger">₹${totalDeductions.toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Salary Section -->
                    <div class="col-12">
                        <div class="card border-success shadow-sm">
                            <div class="card-body p-4 text-center" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                                <div class="mb-2">
                                    <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                                        NET PAYABLE SALARY
                                    </small>
                                    <h3 class="mb-0 fw-bold text-success">₹${netSalary.toFixed(2)}</h3>
                                </div>
                                <div class="border-top border-success pt-3 mt-3">
                                    <small class="text-muted fw-semibold">Amount in Words: </small>
                                    <span class="fw-bold text-dark">${numberToWords(Math.round(netSalary))}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Render or update
        if ($('#finalSalaryCalc').length === 0) {
            $('#payslip_result .row.g-3').append(`<div id="finalSalaryCalc" class="row mt-4">${html}</div>`);
        } else {
            $('#finalSalaryCalc').html(html);
        }

        // Live recalculation on input
        $('#performance_bonus_input, #overtime_input, #tds_input, #loan_input')
            .off('input')
            .on('input', calculateNetSalary);
    }

    

    // Handle "Generate Payslip" button
    $(document).on('click', '#generatePayslipBtn', function () {
        let payslipNo = $('#payslipNo').val();
        let empId = $('#empId').val();
        let financialYear = $('#financialYear').val();
        let month = $('#month').val();
        let notes = $('#notes').val() || '';
        let generate_date = $('#generate_date').val();
        let emp_response = $('#emp_response').val();

        // Recalculate salary to ensure latest values
        calculateNetSalary();

        // --- Collect all major sections from latestPayslipResponse ---
        const fullResponse = window.latestPayslipResponse || {};
        const employee = fullResponse.employee || {};
        const bank = {
            bank_name: employee.bank_name,
            branch: employee.bank_branch,
            ifsc: employee.ifsc,
            account_holder_name: employee.account_holder_name,
            account_number: employee.account_number
        };
        const monthDetails = fullResponse.monthDetails || {};
        const attendanceDetails = fullResponse.attendanceDetails || {};
        const salaryDetails = fullResponse.salaryDetails || {};


        // --- Collect Final Salary Calculation section (card-based UI) ---
        const finalSalarySection = $('#finalSalaryCalc');
        // Helper to get value from label in card-based UI
        function getCardValue(label) {
            // Find the div with the label, then get the last span in that div
            let val = finalSalarySection.find('.d-flex:contains("'+label+'") span:last').text();
            return parseNumber(val);
        }

        const basic_salary = getCardValue('Basic Salary');
        const hra = getCardValue('HRA');
        const conveyance = getCardValue('Conveyance Allowance');
        const medical_allowance = getCardValue('Medical Allowance');
        const special_allowance = getCardValue('Special Allowance');
        const performance_bonus = parseNumber($('#performance_bonus_input').val());
        const overtime = parseNumber($('#overtime_input').val());
        // Total Earnings
        const total_earnings = finalSalarySection.find('.border-top span.fw-bold.text-success:last').text() ? parseNumber(finalSalarySection.find('.border-top span.fw-bold.text-success:last').text()) : 0;

        // ---- Deductions ----
        const pf = getCardValue('PF');
        const esi = getCardValue('ESI');
        const pt = getCardValue('PT');
        const tds = parseNumber($('#tds_input').val());
        const loan = parseNumber($('#loan_input').val());
        const lop = getCardValue('Loss of Pay');
        // Total Deductions
        const total_deductions = finalSalarySection.find('.border-top span.fw-bold.text-danger:last').text() ? parseNumber(finalSalarySection.find('.border-top span.fw-bold.text-danger:last').text()) : 0;

        // ---- Final ----
        // Net Salary
        const net_salary = finalSalarySection.find('h3.fw-bold.text-success').text() ? parseNumber(finalSalarySection.find('h3.fw-bold.text-success').text()) : 0;
        // In Words
        const in_words = finalSalarySection.find('.border-top.border-success span.fw-bold.text-dark').text() || '';


        const finalSalaryJson = {
             // Earnings
            basic_salary: basic_salary,
            hra: hra,
            conveyance: conveyance,
            medical_allowance: medical_allowance,
            special_allowance: special_allowance,
            performance_bonus: performance_bonus,
            overtime_payment: overtime,
            total_earnings: total_earnings,

            // Deductions
            provident_fund: pf,
            esi: esi,
            ptax: pt,
            tds: tds,
            loan: loan,
            lop: lop,
            total_deductions: total_deductions,

            // Final values
            net_salary: net_salary,
            in_words: in_words,
            generated_at: new Date().toISOString()
        };

        // --- Combine everything into one unified JSON for saving ---
        const fullPayslipJson = {
            payslip_no: payslipNo,
            financial_year: financialYear,
            month: month,
            generate_date: generate_date,
            notes: notes,
            employee_details: employee,
            bank_details: bank,
            month_details: monthDetails,
            attendance_details: attendanceDetails,
            salary_details: salaryDetails,
            final_salary_calculation: finalSalaryJson,
            created_at: new Date().toISOString()
        };

        // --- Prepare payload for backend ---
        const payslipFormData = {
            payslip_no: payslipNo,
            employee_id: empId,
            financial_year: financialYear,
            month: month,
            notes: notes,
            generate_date: generate_date,
            emp_response: JSON.stringify(fullResponse),  // raw API data
            final_salary_json: JSON.stringify(fullPayslipJson) // full visible snapshot
        };

        // --- Send to backend ---
        $.ajax({
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            url: '/save-payslip',
            type: 'POST',
            data: payslipFormData,
            success: function (response) {
                if (response.success) {
                    showToast(response.message, "success");
                    $('#payslip_result').html(`
                        <div class="text-center mt-3">
                            <a href="/download-payslip/${response.payslipId}" class="btn btn-success" target="_blank">
                                Download Payslip
                            </a>
                        </div>
                    `);
                } else {
                    showToast(response.message, "error");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                showToast("Failed to save payslip. Please try again.", "error");
            }
        });
    });

});

    function startGeneratePayslipTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Salary Slip Generator Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-info-circle" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Process monthly payroll, allowances, deductions, and print payslips.</p></div>'
                },
                {
                    title: 'Salary Slip Generator',
                    intro: 'Process monthly payroll, allowances, deductions, and print payslips.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-generate-payslip-tour').on('click', function(e) {
            e.preventDefault();
            startGeneratePayslipTour();
        });
    });
</script>


@endsection
