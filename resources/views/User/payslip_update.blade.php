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
                            <li class="breadcrumb-item active" aria-current="page">Update Center</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Update Center</h2>
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
                            <button type="button" class="btn btn-outline-primary" data-target="pf-section">Update PF</button>
                            <button type="button" class="btn btn-outline-primary" data-target="esi-section">Update ESI</button>
                            <button type="button" class="btn btn-outline-primary" data-target="ptax-section">Update PTAX</button>
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
                                            {{-- <button type="button" id="btnSingleUpdate" class="btn btn-success">Single Update</button> --}}
                                            <button type="button" id="btnMultipleUpdate" class="btn btn-success">Update Payslips</button>
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

                                <div class="col-md-3">
                                    <label class="form-label">TAN</label>
                                    <input type="text" id="tds_tan_input" class="form-control" placeholder="Enter TAN">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Financial Year</label>
                                    <input type="text" id="tds_financial_year_input" class="form-control" placeholder="Enter Financial Year">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Nature of Payment</label>
                                    <input type="text" id="tds_nature_payment_input" class="form-control" placeholder="e.g. 92B">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Amount (₹)</label>
                                    <input type="number" id="tds_amount_input" class="form-control" placeholder="Enter Amount">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">CIN Number</label>
                                    <input type="text" id="tds_cin_input" class="form-control" placeholder="Enter CIN">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">UTR / Bank Reference Number</label>
                                    <input type="text" id="tds_utr_input" class="form-control" placeholder="Enter UTR / Bank Reference">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Date of Deposit</label>
                                    <input type="date" id="tds_deposit_date_input" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">BSR Code</label>
                                    <input type="text" id="tds_bsr_input" class="form-control" placeholder="Enter BSR Code">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Challan Number</label>
                                    <input type="text" id="tds_challan_input" class="form-control" placeholder="Enter Challan No">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tender Date</label>
                                    <input type="date" id="tds_tender_date_input" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex gap-2">
                                        <button type="button" id="btnTdsMultipleUpdate" class="btn btn-success">
                                            Update Selected TDS Records
                                        </button>
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

                    {{-- PF Section --}}
                    <div id="pf-section" style="display:none;">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Financial Year</label>
                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('n');

                                    // Current Financial Year
                                    $fyStart = ($currentMonth >= 4) ? $currentYear : $currentYear - 1;
                                @endphp

                                <select class="form-select" id="pf_financial_year">

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
                                <select class="form-select" id="pf-period-type" >
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="half-yearly">Half Yearly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="pf-period-wrapper">
                                <label class="form-label">Period</label>
                                @php
                                    $months = [
                                        'January','February','March','April','May','June',
                                        'July','August','September','October','November','December'
                                    ];

                                    $previousMonth = date('F', strtotime('first day of last month'));
                                @endphp

                                <select class="form-select" id="pf-period-value">

                                    @foreach($months as $month)
                                        <option value="{{ $month }}"
                                            {{ $month == $previousMonth ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100" id="btnPfFilter">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label">TRRN</label>
                                        <input type="text" id="pf_trrn_input" class="form-control" placeholder="Enter TRRN">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Challan Generated On</label>
                                        <input type="datetime-local" id="pf_challan_generated_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Establishment ID</label>
                                        <input type="text" id="pf_establishment_id_input" class="form-control" placeholder="Enter Establishment ID">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Wage Month</label>
                                        <input type="month" id="pf_wage_month_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Total Amount (₹)</label>
                                        <input type="number" step="0.01" id="pf_total_amount_input" class="form-control" placeholder="Enter Total Amount">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">CRN</label>
                                        <input type="text" id="pf_crn_input" class="form-control" placeholder="Enter CRN">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Payment Confirmation Date</label>
                                        <input type="date" id="pf_payment_date_input" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnPfMultipleUpdate" class="btn btn-success">
                                                Update Selected PF Records
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><input type="checkbox" id="select-all-pf"></th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Financial Year</th>
                                        <th>TRRN</th>
                                        <th>CRN</th>
                                        <th>Challan Generated On</th>
                                        <th>Payment Confirmation Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="pfTable">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ESI Section --}}
                    <div id="esi-section" style="display:none;">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Financial Year</label>
                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('n');

                                    // Current Financial Year
                                    $fyStart = ($currentMonth >= 4) ? $currentYear : $currentYear - 1;
                                @endphp

                                <select class="form-select" id="esi_financial_year">

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
                                <select class="form-select" id="esi-period-type" >
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="half-yearly">Half Yearly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="esi-period-wrapper">
                                <label class="form-label">Period</label>
                                @php
                                    $months = [
                                        'January','February','March','April','May','June',
                                        'July','August','September','October','November','December'
                                    ];

                                    $previousMonth = date('F', strtotime('first day of last month'));
                                @endphp

                                <select class="form-select" id="esi-period-value">

                                    @foreach($months as $month)
                                        <option value="{{ $month }}"
                                            {{ $month == $previousMonth ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100" id="btnEsiFilter">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">

                                    {{-- <div class="col-md-3">
                                        <label class="form-label">Employer Code</label>
                                        <input type="text" id="esi_employer_code_input" class="form-control"
                                            placeholder="Enter Employer Code">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Employer Name</label>
                                        <input type="text" id="esi_employer_name_input" class="form-control"
                                            placeholder="Enter Employer Name">
                                    </div> --}}

                                    <div class="col-md-3">
                                        <label class="form-label">Contribution Period</label>
                                        <input type="month" id="esi_contribution_period_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Challan Number</label>
                                        <input type="text" id="esi_challan_no_input" class="form-control"
                                            placeholder="Enter Challan Number">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Challan Created Date</label>
                                        <input type="datetime-local" id="esi_challan_created_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Challan Submitted Date</label>
                                        <input type="datetime-local" id="esi_challan_submitted_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Amount Paid (₹)</label>
                                        <input type="number" step="0.01" id="esi_amount_paid_input" class="form-control"
                                            placeholder="Enter Amount Paid">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Transaction Number</label>
                                        <input type="text" id="esi_transaction_no_input" class="form-control"
                                            placeholder="Enter Transaction Number">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnEsiMultipleUpdate" class="btn btn-success">
                                                Update Selected ESI Records
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="select-all-esi">
                                        </th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Financial Year</th>
                                        <th>Challan Number</th>
                                        <th>Challan Submitted Date</th>
                                        <th>Transaction Number</th>
                                        <th>Challan Created Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="esiTable">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No Record Found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    
                    {{-- PTAX Section --}}
                    <div id="ptax-section" style="display:none;">
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Financial Year</label>
                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('n');

                                    // Current Financial Year
                                    $fyStart = ($currentMonth >= 4) ? $currentYear : $currentYear - 1;
                                @endphp

                                <select class="form-select" id="ptax_financial_year">

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
                                <select class="form-select" id="ptax-period-type" >
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="half-yearly">Half Yearly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="ptax-period-wrapper">
                                <label class="form-label">Period</label>
                                @php
                                    $months = [
                                        'January','February','March','April','May','June',
                                        'July','August','September','October','November','December'
                                    ];

                                    $previousMonth = date('F', strtotime('first day of last month'));
                                @endphp

                                <select class="form-select" id="ptax-period-value">

                                    @foreach($months as $month)
                                        <option value="{{ $month }}"
                                            {{ $month == $previousMonth ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary w-100" id="btnPtaxFilter">Apply Filter</button>
                            </div>
                        </div>

                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-3">
                                        <label class="form-label">GRIPS Payment ID</label>
                                        <input type="text" id="ptax_grips_payment_id_input" class="form-control" placeholder="Enter GRIPS Payment ID">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Payment Initiated Date</label>
                                        <input type="datetime-local" id="ptax_payment_initiated_date_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">BRN</label>
                                        <input type="text" id="ptax_brn_input" class="form-control" placeholder="Enter BRN">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">GRN</label>
                                        <input type="text" id="ptax_grn_input" class="form-control" placeholder="Enter GRN">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Period From</label>
                                        <input type="date" id="ptax_period_from_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Period To</label>
                                        <input type="date" id="ptax_period_to_input" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Payment Reference No.</label>
                                        <input type="text" id="ptax_payment_ref_no_input" class="form-control" placeholder="Enter Payment Reference No">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Amount Paid (₹)</label>
                                        <input type="number" step="0.01" id="ptax_amount_paid_input" class="form-control" placeholder="Enter Amount Paid">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="btnPtaxMultipleUpdate" class="btn btn-success">
                                                Update Selected PTax Records
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="select-all-ptax">
                                        </th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Financial Year</th>
                                        <th>GRIPS Payment ID</th>
                                        <th>Period From</th>
                                        <th>Period To</th>
                                        <th>Payment Reference No</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="ptaxTable">
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No Record Found
                                        </td>
                                    </tr>
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
            'tds-section': document.getElementById('tds-section'),
            'pf-section': document.getElementById('pf-section'),
            'esi-section': document.getElementById('esi-section'),
            'ptax-section': document.getElementById('ptax-section')
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
        const selectAllPf = document.getElementById('select-all-pf');
        const selectAllEsi = document.getElementById('select-all-esi');
        const selectAllPtax = document.getElementById('select-all-ptax');

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

        if (selectAllPf) {
            selectAllPf.addEventListener('change', function () {
                const checkboxes = getPfRowCheckboxes();
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllPf.checked;
                });
            });
        }

        if (selectAllEsi) {
            selectAllEsi.addEventListener('change', function () {
                const checkboxes = getEsiRowCheckboxes();
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllEsi.checked;
                });
            });
        }

        if (selectAllPtax) {
            selectAllPtax.addEventListener('change', function () {
                const checkboxes = getPtaxRowCheckboxes();
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllPtax.checked;
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
        if (window.location.hash === '#tds-section') {
            $('#tds-section').show();
            $('[data-target="tds-section"]').trigger('click');
        }
        if (window.location.hash === '#ptax-section') {
            $('#ptax-section').show();
            $('[data-target="ptax-section"]').trigger('click');
        }
        if (window.location.hash === '#pf-section') {
            $('#pf-section').show();
            $('[data-target="pf-section"]').trigger('click');
        }

        if (window.location.hash === '#esi-section') {
            $('#esi-section').show();
            $('[data-target="esi-section"]').trigger('click');
        }

        loadPayslipData();

        $('#btnFilter').click(function(){
            loadPayslipData();
        });

        // TDS Section
        loadTdsData();

        $('#btnTdsFilter').click(function () {
            loadTdsData();
        });

        $('#btnTdsMultipleUpdate').click(function(){
            const selected = Array.from(document.querySelectorAll('.tds-row-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                showToast('Please select at least one TDS record to update.', 'warning');
                return;
            }
            doTdsUpdate(selected);
        });

        //----pf Section----
        loadPfData();
        $('#btnPfFilter').click(function () {
            loadPfData();
        });

        //----esi Section----
        loadEsiData();

        $('#btnEsiFilter').click(function () {
            loadEsiData();
        });

        //------ Ptax Section ------
        loadPtaxData();

        $('#btnPtaxFilter').click(function () {
            loadPtaxData();
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
        
        $.ajax({
            url: "{{ route('payroll.tds.update') }}",
            type: 'POST',
            data: {

                ids: selectedIds,

                tds_tan: $('#tds_tan_input').val(),
                tds_financial_year: $('#tds_financial_year').val(),
                tds_nature_of_payment: $('#tds_nature_payment_input').val(),
                tds_amount: $('#tds_amount_input').val(),
                tds_cin: $('#tds_cin_input').val(),

                tds_challan_no: $('#tds_utr_input').val(),
                tds_bsr_code: $('#tds_bsr_input').val(),
                tds_deposit_date: $('#tds_deposit_date_input').val(),
                tds_tender_date: $('#tds_tender_date_input').val(),

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

    //----- load Pf Data -----
    function loadPfData()
    {
        $.ajax({

            url: "{{ route('payroll.pf.list') }}",

            type: "GET",

            data: {

                financial_year: $('#pf_financial_year').val(),

                filter_type: $('#pf-period-type').val(),

                period: $('#pf-period-value').val()

            },

            beforeSend:function(){

                $('#pfTable').html(`
                    <tr>
                        <td colspan="8" class="text-center">
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
                            <td colspan="8" class="text-center">
                                No Record Found
                            </td>
                        </tr>
                    `;

                }else{

                    $.each(response,function(index,row){

                        html += `
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        class="pf-row-checkbox"
                                        value="${row.id}">
                                </td>

                                <td>${row.employee_id ?? '-'}</td>

                                <td>${row.name ?? '-'}</td>

                                <td>${row.financial_year ?? '-'}</td>

                                <td>${row.pf_trrn ?? '-'}</td>

                                <td>${row.pf_crn ?? '-'}</td>

                                <td>${row.pf_challan_generated_on ?? '-'}</td>

                                <td>${row.pf_payment_confirmation_date ?? '-'}</td>

                                <td>
                                    ${row.pf_payment_status === 'Done'
                                        ? '<span class="badge bg-success">Done</span>'
                                        : '<span class="badge bg-warning text-dark">Pending</span>'}
                                </td>

                            </tr>`;

                    });

                }

                $('#pfTable').html(html);

                // Rebind PF checkbox handlers after row injection
                attachPfCheckboxHandlers();
            }

        });

    }

    $('#btnPfMultipleUpdate').click(function () {

        let ids = [];

        $('.pf-row-checkbox:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            showToast('Please select at least one PF record to update.', 'warning');
            return;
        }

        $.ajax({

            url: "{{ route('payroll.pf.update') }}",

            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",

                ids: ids,

                pf_trrn: $('#pf_trrn_input').val(),

                pf_challan_generated: $('#pf_challan_generated_input').val(),

                pf_establishment_id: $('#pf_establishment_id_input').val(),

                pf_wage_month: $('#pf_wage_month_input').val(),

                pf_total_amount: $('#pf_total_amount_input').val(),

                pf_crn: $('#pf_crn_input').val(),

                pf_payment_date: $('#pf_payment_date_input').val()
            },

            success: function (response) {

                showToast(response.message, 'success');

                // Clear all input fields
                $('#pf_trrn_input').val('');
                $('#pf_challan_generated_input').val('');
                $('#pf_establishment_id_input').val('');
                $('#pf_wage_month_input').val('');
                $('#pf_total_amount_input').val('');
                $('#pf_crn_input').val('');
                $('#pf_payment_date_input').val('');

                // Uncheck all checkboxes
                $('.pf-row-checkbox').prop('checked', false);
                $('#select-all-pf').prop('checked', false);

                // Reload table
                loadPfData();
            },

            error: function (xhr) {

                let message = 'Something went wrong.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showToast(message, 'error');
            }

        });

    });

    //---- ESI Section ---
    function loadEsiData()
    {
        $.ajax({

            url: "{{ route('payroll.esi.list') }}",

            type: "GET",

            data: {

                financial_year: $('#esi_financial_year').val(),

                filter_type: $('#esi-period-type').val(),

                period: $('#esi-period-value').val()

            },

            beforeSend:function(){

                $('#esiTable').html(`
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

                        html += `
                            <tr>

                                <td>
                                    <input type="checkbox"
                                        class="esi-row-checkbox"
                                        value="${row.id}">
                                </td>

                                <td>${row.employee_id ?? '-'}</td>

                                <td>${row.name ?? '-'}</td>

                                <td>${row.financial_year ?? '-'}</td>

                                <td>${row.esi_challan_no ?? '-'}</td>

                                <td>${row.esi_transaction_no ?? '-'}</td>

                                <td>${row.esi_challan_created_date ?? '-'}</td>

                                <td>${row.esi_challan_submitted_date ?? '-'}</td>

                                <td>
                                    ${
                                        row.esi_payment_status === 'Done'
                                        ? '<span class="badge bg-success">Done</span>'
                                        : '<span class="badge bg-warning text-dark">Pending</span>'
                                    }
                                </td>

                            </tr>`;
                    });

                }

                $('#esiTable').html(html);

                attachEsiCheckboxHandlers();

            }

        });
    }

    $('#btnEsiMultipleUpdate').click(function () {

        let ids = [];

        $('.esi-row-checkbox:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            showToast('Please select at least one ESI record to update.', 'warning');
            return;
        }

        $.ajax({

            url: "{{ route('payroll.esi.update') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                ids: ids,

                // esi_employer_code: $('#esi_employer_code_input').val(),

                // esi_employer_name: $('#esi_employer_name_input').val(),

                esi_contribution_period: $('#esi_contribution_period_input').val(),

                esi_challan_no: $('#esi_challan_no_input').val(),

                esi_challan_created_date: $('#esi_challan_created_input').val(),

                esi_challan_submitted_date: $('#esi_challan_submitted_input').val(),

                esi_amount_paid: $('#esi_amount_paid_input').val(),

                esi_transaction_no: $('#esi_transaction_no_input').val()

            },

            success: function (response) {

                showToast(response.message, 'success');

                // Clear form
                $('#esi_employer_code_input').val('');
                $('#esi_employer_name_input').val('');
                $('#esi_contribution_period_input').val('');
                $('#esi_challan_no_input').val('');
                $('#esi_challan_created_input').val('');
                $('#esi_challan_submitted_input').val('');
                $('#esi_amount_paid_input').val('');
                $('#esi_transaction_no_input').val('');

                // Uncheck checkboxes
                $('.esi-row-checkbox').prop('checked', false);
                $('#select-all-esi').prop('checked', false);

                // Reload table
                loadEsiData();

            },

            error: function (xhr) {

                let message = 'Something went wrong.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showToast(message, 'error');
            }

        });

    });

    //----- PTax Section -----
    function loadPtaxData()
    {
        $.ajax({

            url: "{{ route('payroll.ptax.list') }}",

            type: "GET",

            data: {

                financial_year: $('#ptax_financial_year').val(),

                filter_type: $('#ptax-period-type').val(),

                period: $('#ptax-period-value').val()

            },

            beforeSend:function(){

                $('#ptaxTable').html(`
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

                        html += `
                        <tr>

                            <td>
                                <input type="checkbox"
                                    class="ptax-row-checkbox"
                                    value="${row.id}">
                            </td>

                            <td>${row.employee_id ?? '-'}</td>

                            <td>${row.name ?? '-'}</td>

                            <td>${row.financial_year ?? '-'}</td>

                            <td>${row.ptax_grips_payment_id ?? '-'}</td>

                            <td>${row.ptax_period_from ?? '-'}</td>

                            <td>${row.ptax_period_to ?? '-'}</td>

                            <td>${row.ptax_payment_ref_no ?? '-'}</td>

                            <td>
                                ${
                                    row.ptax_payment_status === 'Done'
                                    ? '<span class="badge bg-success">Done</span>'
                                    : '<span class="badge bg-warning text-dark">Pending</span>'
                                }
                            </td>

                        </tr>
                        `;

                    });

                }

                $('#ptaxTable').html(html);

                attachPtaxCheckboxHandlers();

            }

        });
    }

    $('#btnPtaxMultipleUpdate').click(function () {

        let ids = [];

        $('.ptax-row-checkbox:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            showToast('Please select at least one PTax record to update.', 'warning');
            return;
        }

        $.ajax({

            url: "{{ route('payroll.ptax.update') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                ids: ids,

                ptax_grips_payment_id: $('#ptax_grips_payment_id_input').val(),

                ptax_payment_initiated_date: $('#ptax_payment_initiated_date_input').val(),

                ptax_brn: $('#ptax_brn_input').val(),

                ptax_grn: $('#ptax_grn_input').val(),

                ptax_period_from: $('#ptax_period_from_input').val(),

                ptax_period_to: $('#ptax_period_to_input').val(),

                ptax_payment_ref_no: $('#ptax_payment_ref_no_input').val(),

                ptax_amount_paid: $('#ptax_amount_paid_input').val()

            },

            success: function (response) {

                showToast(response.message, 'success');

                // Clear form
                $('#ptax_grips_payment_id_input').val('');
                $('#ptax_payment_initiated_date_input').val('');
                $('#ptax_brn_input').val('');
                $('#ptax_grn_input').val('');
                $('#ptax_period_from_input').val('');
                $('#ptax_period_to_input').val('');
                $('#ptax_payment_ref_no_input').val('');
                $('#ptax_amount_paid_input').val('');

                // Uncheck checkboxes
                $('.ptax-row-checkbox').prop('checked', false);
                $('#select-all-ptax').prop('checked', false);

                // Reload table
                loadPtaxData();

            },

            error: function (xhr) {

                let message = 'Something went wrong.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showToast(message, 'error');

            }

        });

    });


</script>
@endsection
