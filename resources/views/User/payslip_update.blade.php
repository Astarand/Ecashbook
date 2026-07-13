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
                            <button type="button" class="btn btn-primary" data-target="payslip-section">Payslip</button>
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

                        $currentYear = date('Y');
                        $currentMonth = date('n');

                        $fyStart = ($currentMonth >= 4)
                            ? $currentYear
                            : $currentYear - 1;

                        $previousMonth = date('F', strtotime('first day of last month'));
                    @endphp

                    {{-- Payslip Section --}}

                    <div id="payslip-section">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Financial Year</label>
                                <select class="form-select" id="financial_year">

                                    @for($i=0;$i<5;$i++)

                                        @php
                                            $start = $fyStart-$i;
                                            $end = $start+1;
                                        @endphp

                                        <option value="{{ $start }}-{{ $end }}"
                                            {{ $i==0 ? 'selected':'' }}>
                                            {{ $start }}-{{ $end }}
                                        </option>

                                    @endfor

                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Month</label>
                                <select class="form-select" id="month">

                                    @foreach([
                                    'January',
                                    'February',
                                    'March',
                                    'April',
                                    'May',
                                    'June',
                                    'July',
                                    'August',
                                    'September',
                                    'October',
                                    'November',
                                    'December'
                                    ] as $month)

                                    <option value="{{ $month }}"
                                        {{ $month==$previousMonth ? 'selected':'' }}>

                                        {{ $month }}

                                    </option>

                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary w-100" id="btnFilter">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">Payment Date</label>
                                        <input type="date" id="payment_date_input" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Transaction ID</label>
                                        <input type="text" id="transaction_id_input" class="form-control" placeholder="Enter transaction ID">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnSingleUpdate" class="btn btn-success">Single Update</button>
                                            <button type="button" id="btnMultipleUpdate" class="btn btn-outline-success">Multiple Update</button>
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
                                <tbody id="payslipTable">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TDS Section --}}
                    <div id="tds-section" style="display:none;">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Financial Year</label>
                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('n');

                                    // Current Financial Year
                                    $fyStart = ($currentMonth >= 4) ? $currentYear : $currentYear - 1;
                                @endphp

                                <select class="form-select" id="tds_financial_year">

                                    @for($i = 0; $i < 5; $i++)
                                        @php
                                            $start = $fyStart - $i;
                                            $end = $start + 1;
                                        @endphp

                                        <option value="{{ $start }}-{{ $end }}" {{ $i == 0 ? 'selected' : '' }}>
                                            {{ $start }}-{{ $end }}
                                        </option>
                                    @endfor

                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter Type</label>
                                <select class="form-select" id="tds-period-type" >
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="half-yearly">Half Yearly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="tds-period-wrapper">
                                <label class="form-label">Period</label>
                                @php
                                    $months = [
                                        'January','February','March','April','May','June',
                                        'July','August','September','October','November','December'
                                    ];

                                    $previousMonth = date('F', strtotime('first day of last month'));
                                @endphp

                                <select class="form-select" id="tds-period-value">

                                    @foreach($months as $month)
                                        <option value="{{ $month }}"
                                            {{ $month == $previousMonth ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100" id="btnTdsFilter">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label">UTR Number</label>
                                        <input type="text" id="tds_utr_input" class="form-control" placeholder="Enter UTR number">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">BSR Code</label>
                                        <input type="text" id="tds_bsr_input" class="form-control" placeholder="Enter BSR Code">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Update Date</label>
                                        <input type="date" id="tds_deposit_date_input" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnTdsSingleUpdate" class="btn btn-success">Single Update</button>
                                            <button type="button" id="btnTdsMultipleUpdate" class="btn btn-outline-success">Multiple Update</button>
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
                                        <th>BSR Code</th>
                                    </tr>
                                </thead>
                                <tbody id="tdsTable">
                                    
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
        const selectAllTds = document.getElementById('select-all-tds');

        function getPayslipRowCheckboxes() {
            return document.querySelectorAll('.payslip-row-checkbox');
        }

        function getTdsRowCheckboxes() {
            return document.querySelectorAll('.tds-row-checkbox');
        }

        function updateSelectAllPayslipsState() {
            const checkboxes = getPayslipRowCheckboxes();
            if (!selectAllPayslips) return;
            selectAllPayslips.checked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        }

        function updateSelectAllTdsState() {
            const checkboxes = getTdsRowCheckboxes();
            if (!selectAllTds) return;
            selectAllTds.checked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        }

        if (selectAllPayslips) {
            selectAllPayslips.addEventListener('change', function () {
                const checkboxes = getPayslipRowCheckboxes();
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllPayslips.checked;
                });
            });
        }

        if (selectAllTds) {
            selectAllTds.addEventListener('change', function () {
                const checkboxes = getTdsRowCheckboxes();
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllTds.checked;
                });
            });
        }

        function attachTdsCheckboxHandlers() {
            const checkboxes = getTdsRowCheckboxes();
            checkboxes.forEach(function (checkbox) {
                checkbox.removeEventListener('change', onTdsCheckboxChange);
                checkbox.addEventListener('change', onTdsCheckboxChange);
            });
            updateSelectAllTdsState();
        }

        function onTdsCheckboxChange() {
            updateSelectAllTdsState();
        }

        const typeSelect = document.getElementById('tds-period-type');
        const periodWrapper = document.getElementById('tds-period-wrapper');
        const periodValue = document.getElementById('tds-period-value');

        const previousMonth = "{{ $previousMonth }}";

        function renderPeriodOptions() {

            const current = $('#tds-period-type').val();

            const monthOptions = [
                'January','February','March','April','May','June',
                'July','August','September','October','November','December'
            ];

            const quarterOptions = ['Q1','Q2','Q3','Q4'];
            const halfYearOptions = ['Half 1','Half 2'];

            let html = '';

            if(current === 'monthly'){

                monthOptions.forEach(function(month){

                    html += `<option value="${month}"
                                ${month === previousMonth ? 'selected' : ''}>
                                ${month}
                            </option>`;

                });

            }else if(current === 'quarterly'){

                html += `
                    <option value="Q1">Q1 (Apr-Jun)</option>
                    <option value="Q2">Q2 (Jul-Sep)</option>
                    <option value="Q3">Q3 (Oct-Dec)</option>
                    <option value="Q4">Q4 (Jan-Mar)</option>
                `;

            }else if(current === 'half-yearly'){

                html += `
                    <option value="H1">Apr-Sep</option>
                    <option value="H2">Oct-Mar</option>
                `;

            }else{

                html = '';
            }

            $('#tds-period-value').html(html);
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', function () {
                renderPeriodOptions();
                toggleTdsPeriodVisibility();
            });
            renderPeriodOptions();
            toggleTdsPeriodVisibility();
        }
    });

    $(document).ready(function(){
        loadPayslipData();

        $('#btnFilter').click(function(){
            loadPayslipData();
        });

        // TDS Section
        loadTdsData();

        $('#btnTdsFilter').click(function () {
            loadTdsData();
        });

        $('#btnTdsSingleUpdate').click(function(){
            const selected = Array.from(document.querySelectorAll('.tds-row-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                showToast('Please select a TDS record to update.', 'warning');
                return;
            }
            if (selected.length > 1) {
                showToast('Single Update requires exactly one selection. Use Multiple Update for more.', 'warning');
                return;
            }
            doTdsUpdate(selected);
        });

        $('#btnTdsMultipleUpdate').click(function(){
            const selected = Array.from(document.querySelectorAll('.tds-row-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                showToast('Please select at least one TDS record to update.', 'warning');
                return;
            }
            doTdsUpdate(selected);
        });
    });

    function toggleTdsPeriodVisibility() {
        const selectedType = $('#tds-period-type').val();
        if (selectedType === 'yearly') {
            $('#tds-period-wrapper').hide();
        } else {
            $('#tds-period-wrapper').show();
        }
    }

    // Load Payslip Data
    function loadPayslipData()
    {

        $.ajax({

            url:"{{ route('payroll.payslip.list') }}",

            type:"GET",

            data:{

                financial_year:$('#financial_year').val(),

                month:$('#month').val()

            },
            beforeSend: function () {

                $('#payslipTable').html(`
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            Loading...
                        </td>
                    </tr>
                `);

            },

            success:function(response){


                let html='';

                if(response.length==0)
                {
                    html+=`
                    <tr>
                        <td colspan="6" class="text-center">
                            No Record Found
                        </td>
                    </tr>
                    `;
                }
                else
                {

                    $.each(response,function(index,row){

                        html+=`

                        <tr>

                            <td>

                                <input
                                type="checkbox"
                                class="payslip-row-checkbox"
                                value="${row.id}">

                            </td>

                            <td>${row.employee_id ?? '-'}</td>

                            <td>${row.name ?? '-'}</td>

                            <td>${row.date ?? '-'}</td>

                            <td>${row.payment_date ?? '-'}</td>

                            <td>${row.payment_trans_id ?? '-'}</td>

                        </tr>

                        `;

                    });

                }

                $('#payslipTable').html(html);

                // After rows are inserted, rebind payslip checkbox handlers so select-all works
                attachPayslipCheckboxHandlers();


            }

        });


        function attachPayslipCheckboxHandlers() {
            const checkboxes = document.querySelectorAll('.payslip-row-checkbox');

            checkboxes.forEach(function (checkbox) {
                checkbox.removeEventListener('change', onPayslipCheckboxChange);
                checkbox.addEventListener('change', onPayslipCheckboxChange);
            });

            updateSelectAllPayslipsState();
        }

        function onPayslipCheckboxChange() {
            updateSelectAllPayslipsState();
        }

        // Update actions
        $(document).ready(function(){
            $('#btnSingleUpdate').on('click', function(){
                const selected = Array.from(document.querySelectorAll('.payslip-row-checkbox:checked')).map(cb => cb.value);
                if (selected.length === 0) {
                    
                    showToast('Please select a payslip to update.', 'warning');
                    return;
                }
                if (selected.length > 1) {
                    showToast('Single Update requires exactly one selection. Use Multiple Update for more.', 'warning');
                    return;
                }
                doPayslipUpdate(selected);
            });

            $('#btnMultipleUpdate').on('click', function(){
                const selected = Array.from(document.querySelectorAll('.payslip-row-checkbox:checked')).map(cb => cb.value);
                if (selected.length === 0) {
                    showToast('Please select at least one payslip to update.', 'warning');
                    return;
                }
                doPayslipUpdate(selected);
            });
        });

        function doPayslipUpdate(selectedIds) {
            const paymentDate = $('#payment_date_input').val();
            const transactionId = $('#transaction_id_input').val();

            if (!paymentDate || !transactionId) {
                if (!confirm('Payment Date or Transaction ID is empty. Proceed anyway?')) return;
            }

            $.ajax({
                url: "{{ route('payroll.payslip.update') }}",
                type: 'POST',
                data: {
                    ids: selectedIds,
                    payment_date: paymentDate,
                    transaction_id: transactionId,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function(){
                    // optional: show loading
                },
                success: function(resp){
                    showToast(resp.message || 'Payslip(s) updated successfully', 'success');
                    loadPayslipData();
                },
                error: function(xhr){
                    showToast('Update failed: ' + (xhr.responseJSON?.message || xhr.statusText), 'error');
                }
            });
        }
    }

    //---- Load TDS Data----
    function loadTdsData()
    {
        $.ajax({

            url: "{{ route('payroll.tds.list') }}",

            type: "GET",

            data: {

                financial_year: $('#tds_financial_year').val(),

                filter_type: $('#tds-period-type').val(),

                period: $('#tds-period-value').val()

            },

            beforeSend:function(){

                $('#tdsTable').html(`
                    <tr>
                        <td colspan="9" class="text-center">
                            Loading...
                        </td>
                    </tr>
                `);

            },

            success:function(response){

                let html='';

                if(response.length==0){

                    html=`
                        <tr>
                            <td colspan="9" class="text-center">
                                No Record Found
                            </td>
                        </tr>
                    `;

                }else{

                    $.each(response,function(index,row){

                        html+=`

                        <tr>

                            <td>
                                <input type="checkbox"
                                    class="tds-row-checkbox"
                                    value="${row.id}">
                            </td>

                            <td>${row.employee_id ?? '-'}</td>

                            <td>${row.name ?? '-'}</td>

                            <td>${row.financial_year}</td>

                            <td>${$('#tds-period-type option:selected').text()}</td>

                            <td>${row.month}</td>

                            <td>${row.tds_challan_no ?? '-'}</td>

                            <td>${row.tds_deposit_date ?? '-'}</td>

                            <td>${row.tds_bsr_code ?? '-'}</td>

                        </tr>

                        `;

                    });

                }

                $('#tdsTable').html(html);

                // Rebind TDS checkbox handlers after row injection
                attachTdsCheckboxHandlers();
            }

        });

    }

    function doTdsUpdate(selectedIds) {
        const utr = $('#tds_utr_input').val();
        const bsr = $('#tds_bsr_input').val();
        const depositDate = $('#tds_deposit_date_input').val();

        if (!utr || !bsr || !depositDate) {
            if (!confirm('UTR, BSR or Update Date is empty. Proceed anyway?')) return;
        }

        $.ajax({
            url: "{{ route('payroll.tds.update') }}",
            type: 'POST',
            data: {
                ids: selectedIds,
                tds_challan_no: utr,
                tds_bsr_code: bsr,
                tds_deposit_date: depositDate,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function(){
                // optional: show loading
            },
            success: function(resp){
                showToast(resp.message || 'TDS record(s) updated successfully', 'success');
                loadTdsData();
            },
            error: function(xhr){
                showToast('Update failed: ' + (xhr.responseJSON?.message || xhr.statusText), 'error');
            }
        });
    }


</script>
@endsection
