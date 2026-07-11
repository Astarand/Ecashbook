@extends('App.Layout')

@section('container')
<div class="pc-content">
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">HR & Payroll Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payslip & TDS Update</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Payslip & TDS Update</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h4 class="mb-0">Update Center</h4>
                        <div class="btn-group" role="group" aria-label="Update section switcher">
                            <button type="button" class="btn btn-primary active" data-target="payslip-section">Payslip</button>
                            <button type="button" class="btn btn-outline-primary" data-target="tds-section">Update TDS</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $currentMonth = date('F');
                        $currentYear = date('Y');
                        $financialYears = [];
                        for ($year = $currentYear - 2; $year <= $currentYear + 1; $year++) {
                            $financialYears[] = $year . '-' . ($year + 1);
                        }

                        $payslipRecords = [
                            ['emp_id' => 'EMP-001', 'name' => 'John Doe', 'generated_on' => '2026-07-01', 'payment_date' => '2026-07-10', 'transaction_id' => 'TXN-1001'],
                            ['emp_id' => 'EMP-002', 'name' => 'Jane Smith', 'generated_on' => '2026-07-02', 'payment_date' => '', 'transaction_id' => ''],
                            ['emp_id' => 'EMP-003', 'name' => 'Mark Wilson', 'generated_on' => '2026-07-03', 'payment_date' => '2026-07-11', 'transaction_id' => 'TXN-1003'],
                        ];

                        $tdsRecords = [
                            ['emp_id' => 'EMP-001', 'name' => 'John Doe', 'financial_year' => '2025-2026', 'period_type' => 'Monthly', 'period' => 'July', 'amount' => '₹ 5,000', 'utr' => '-', 'update_date' => '-'],
                            ['emp_id' => 'EMP-002', 'name' => 'Jane Smith', 'financial_year' => '2026-2027', 'period_type' => 'Quarterly', 'period' => 'Q1 (Jan-Mar)', 'amount' => '₹ 8,400', 'utr' => '-', 'update_date' => '-'],
                            ['emp_id' => 'EMP-003', 'name' => 'Mark Wilson', 'financial_year' => '2024-2025', 'period_type' => 'Yearly', 'period' => '-', 'amount' => '₹ 12,000', 'utr' => '-', 'update_date' => '-'],
                        ];
                    @endphp

                    <div id="payslip-section">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Financial Year</label>
                                <select class="form-select">
                                    @foreach($financialYears as $financialYear)
                                        <option value="{{ $financialYear }}" {{ $financialYear == end($financialYears) ? 'selected' : '' }}>{{ $financialYear }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Month</label>
                                <select class="form-select">
                                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $month)
                                        <option value="{{ $month }}" {{ $month == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary w-100">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Payment Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" placeholder="Enter transaction ID">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success">Single Update</button>
                                            <button type="button" class="btn btn-outline-success">Multiple Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="select-all-payslips"></th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Payslip Generate Date</th>
                                        <th>Payment Date</th>
                                        <th>Transaction ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payslipRecords as $record)
                                        <tr>
                                            <td><input type="checkbox" class="payslip-row-checkbox"></td>
                                            <td>{{ $record['emp_id'] }}</td>
                                            <td>{{ $record['name'] }}</td>
                                            <td>{{ $record['generated_on'] }}</td>
                                            <td>{{ $record['payment_date'] ?: '-' }}</td>
                                            <td>{{ $record['transaction_id'] ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="tds-section" style="display:none;">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Financial Year</label>
                                <select class="form-select">
                                    @foreach($financialYears as $financialYear)
                                        <option value="{{ $financialYear }}" {{ $financialYear == end($financialYears) ? 'selected' : '' }}>{{ $financialYear }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Type</label>
                                <select class="form-select" id="tds-period-type">
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="half-yearly">Half Yearly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="tds-period-wrapper">
                                <label class="form-label">Period</label>
                                <select class="form-select" id="tds-period-value"></select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">UTR Number</label>
                                        <input type="text" class="form-control" placeholder="Enter UTR number">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Update Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success">Single Update</button>
                                            <button type="button" class="btn btn-outline-success">Multiple Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="select-all-tds"></th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Financial Year</th>
                                        <th>Filter Type</th>
                                        <th>Period</th>
                                        <th>UTR</th>
                                        <th>Update Date</th>
                                        <th>TDS Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tdsRecords as $record)
                                        <tr>
                                            <td><input type="checkbox" class="tds-row-checkbox"></td>
                                            <td>{{ $record['emp_id'] }}</td>
                                            <td>{{ $record['name'] }}</td>
                                            <td>{{ $record['financial_year'] }}</td>
                                            <td>{{ $record['period_type'] }}</td>
                                            <td>{{ $record['period'] }}</td>
                                            <td>{{ $record['utr'] }}</td>
                                            <td>{{ $record['update_date'] }}</td>
                                            <td>{{ $record['amount'] }}</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabButtons = document.querySelectorAll('[data-target]');
        const sections = {
            'payslip-section': document.getElementById('payslip-section'),
            'tds-section': document.getElementById('tds-section')
        };

        tabButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                tabButtons.forEach(function (btn) {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-primary');
                });

                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');

                Object.keys(sections).forEach(function (key) {
                    sections[key].style.display = key === this.getAttribute('data-target') ? '' : 'none';
                }.bind(this));
            });
        });

        const selectAllPayslips = document.getElementById('select-all-payslips');
        const payslipRowCheckboxes = document.querySelectorAll('.payslip-row-checkbox');
        const selectAllTds = document.getElementById('select-all-tds');
        const tdsRowCheckboxes = document.querySelectorAll('.tds-row-checkbox');

        if (selectAllPayslips) {
            selectAllPayslips.addEventListener('change', function () {
                payslipRowCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllPayslips.checked;
                });
            });

            payslipRowCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const allChecked = Array.from(payslipRowCheckboxes).every(function (item) {
                        return item.checked;
                    });
                    selectAllPayslips.checked = allChecked;
                });
            });
        }

        if (selectAllTds) {
            selectAllTds.addEventListener('change', function () {
                tdsRowCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllTds.checked;
                });
            });

            tdsRowCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const allChecked = Array.from(tdsRowCheckboxes).every(function (item) {
                        return item.checked;
                    });
                    selectAllTds.checked = allChecked;
                });
            });
        }

        const typeSelect = document.getElementById('tds-period-type');
        const periodWrapper = document.getElementById('tds-period-wrapper');
        const periodValue = document.getElementById('tds-period-value');

        function renderPeriodOptions() {
            if (!typeSelect || !periodValue) return;

            const current = typeSelect.value;
            const monthOptions = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const quarterOptions = ['Q1 (Jan-Mar)', 'Q2 (Apr-Jun)', 'Q3 (Jul-Sep)', 'Q4 (Oct-Dec)'];
            const halfYearOptions = ['Half 1 (Jan-Jun)', 'Half 2 (Jul-Dec)'];

            if (periodWrapper) {
                periodWrapper.style.display = current === 'yearly' ? 'none' : '';
            }

            periodValue.innerHTML = '';

            if (current === 'yearly') {
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'No period required';
                periodValue.appendChild(placeholder);
                return;
            }

            let options = [];
            if (current === 'monthly') {
                options = monthOptions;
            } else if (current === 'quarterly') {
                options = quarterOptions;
            } else if (current === 'half-yearly') {
                options = halfYearOptions;
            }

            options.forEach(function (value) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                periodValue.appendChild(option);
            });
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', renderPeriodOptions);
            renderPeriodOptions();
        }
    });
</script>
@endsection
