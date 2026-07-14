@extends('App.Layout')

@section('container')




<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item" aria-current="page">TDS Returns & Filing</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">TDS Returns & Filing List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="{{ route('payroll.payslip_update') }}#tds-section" class="btn btn-warning shadow">
                        TDS Update
                    </a>

                    <a href="#tdsFilterOffcanvas" class="btn btn-primary shadow" style="font-size: 16px;" data-bs-toggle="offcanvas" data-bs-target="#tdsFilterOffcanvas" aria-controls="tdsFilterOffcanvas">
                        Summary of Return
                    </a>
					<a href="https://www.incometax.gov.in/" 
                        target="_blank" 
                        class="btn btn-primary shadow"
                        style="font-size: 16px;">
                        Payment of TDS/TCS
					</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card alert alert-warning p-0">
                <div class="card-body">
                    <div class="d-flex align-items-start flex-column flex-lg-row gap-3">
                        <div class="flex-grow-1 me-3">
                            <h4 class="alert-heading mb-3">Steps for payment of TDS/TCS:</h4>
                            <ol class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">1</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Login to <a href="https://www.incometax.gov.in/" target="_blank">https://www.incometax.gov.in/</a> or click the Payment of TDS/ TCS Button</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">2</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Login with TAN number and password.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">3</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "E-file" &rarr; "e-pay Tax".</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">4</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span class="d-inline-flex align-items-center flex-wrap">Select the Assessment Year <i class="ti ti-arrow-right mx-1 text-dark"></i> Click on "Proceed" in the Pay TDS/TCS tab.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">5</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span class="d-inline-flex align-items-center flex-wrap">Select TDS section and click on continue <i class="ti ti-arrow-right mx-1 text-dark"></i> Select Major head as company deductee(0020).</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">6</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span class="d-inline-flex align-items-center flex-wrap">Enter tax amount <i class="ti ti-arrow-right mx-1 text-dark"></i> Click on "continue" and make the payment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">7</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>After successful payment download the challan</span>
                                </li>
                            </ol>
                        </div>
                        <div class="flex-shrink-0 align-self-lg-center">
                            <img src="../assets/images/application/img-accout-alert.png" alt="img" class="img-fluid wid-80">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <table class="table tbl-product my-3" id="pc-dt-simple">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Vendor/ Employee ID</th>
                                <th>Name</th>
                                <th>Pan</th>
                                <th>Section</th>
                                <th>Nature Of Payment</th>
                                <th>Gross Amount</th>
                                <th>TDS Rate (%)</th>
                                <th>TDS Deduction</th>
                                <th>Challan No</th>
                                <th>Payment Date</th>
                                <th>Return Quarter</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp

                            @forelse($employees as $emp)
                            <tr>
                                <td class="text-end">{{ $i }}</td>

                                <td>{{ $emp->employee_id ?? 'N/A' }}</td>

                                <td>
                                    <h6 class="mb-1">{{ $emp->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">{{ $emp->email ?? 'N/A' }}</small>
                                </td>

                                <td>{{ $emp->pan_number ?? 'N/A' }}</td>

                                <td>{{ $emp->tds_slab_section ?? 'N/A' }}</td>

                                <td>
                                    {{ $emp->payment_type ?? 'N/A' }}
                                </td>

                                <td>₹ {{ number_format($emp->total_addition ?? 0, 2) }}</td>

                                <td>
                                    {{ isset($emp->tds_slab_rate) ? $emp->tds_slab_rate . '%' : 'N/A' }}
                                </td>

                                <td>
                                    ₹ {{ number_format($emp->tds_amount ?? 0, 2) }}
                                </td>

                                <td>{{ $emp->payslip_no ?? 'N/A' }}</td>

                                <td>
                                    {{ $emp->payment_date
                                        ? \Carbon\Carbon::parse($emp->payment_date)->format('d-m-Y')
                                        : 'N/A' }}
                                </td>

                                <td>{{ $emp->quarter ?? 'N/A' }}</td>

                                <td>Paid</td>

                                <td>
                                    <a href="javascript:void(0)"
                                    class="avtar avtar-xs btn-link-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewDetailsModal{{ $i }}">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="viewDetailsModal{{ $i }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">TDS Filing Details</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <p>
                                                <b>Invoice Date:</b>
                                                {{ $emp->payment_date
                                                    ? \Carbon\Carbon::parse($emp->payment_date)->format('d-m-Y')
                                                    : 'NULL' }}
                                            </p>

                                            <p>
                                                <b>Gross Amount:</b>
                                                ₹ {{ number_format($emp->total_addition ?? 0, 2) }}
                                            </p>

                                            <p>
                                                <b>Section:</b>
                                                {{ $emp->tds_slab_section ?? 'NULL' }}
                                            </p>

                                            <p>
                                                <b>TDS Rate:</b>
                                                {{ $emp->tds_slab_rate ?? 0 }}%
                                            </p>

                                            <p>
                                                <b>TDS Amount:</b>
                                                ₹ {{ number_format($emp->tds_amount ?? 0, 2) }}
                                            </p>

                                            <p>
                                                <b>CHALLAN NO/PAYSLIP NO:</b>
                                                {{ $emp->payslip_no ?? 'NULL' }}
                                            </p>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php $i++; @endphp

                            @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted">
                                    No TDS applicable records found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>



<div class="offcanvas offcanvas-end" tabindex="-1" id="tdsFilterOffcanvas" aria-labelledby="tdsFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="tdsFilterOffcanvasLabel">Filter TDS Returns & Filing</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="tdsFilterForm" method="GET" action="{{ route('user.tds_returns_download') }}">
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterTdsName" class="form-label">Vendor/Employee Name</label>
                    <input type="text" class="form-control" id="filterTdsName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterTdsId" class="form-label">Vendor/Employee ID</label>
                    <input type="text" class="form-control" id="filterTdsId" name="vendor_id" placeholder="Filter by ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3">Filter By Period</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tdsPeriodType" id="tdsPeriodMonth" value="month" checked>
                        <label class="form-check-label" for="tdsPeriodMonth">Monthly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tdsPeriodType" id="tdsPeriodQuarter" value="quarter">
                        <label class="form-check-label" for="tdsPeriodQuarter">Quarterly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tdsPeriodType" id="tdsPeriodYear" value="year">
                        <label class="form-check-label" for="tdsPeriodYear">Yearly</label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12" id="filterTdsMonthGroup">
                    <label for="filterTdsMonth" class="form-label">Select Month</label>
                    <select class="form-select" id="filterTdsMonth" name="month">
                        <option value="">Choose month</option>
                        <option value="january">January</option>
                        <option value="february">February</option>
                        <option value="march">March</option>
                        <option value="april">April</option>
                        <option value="may">May</option>
                        <option value="june">June</option>
                        <option value="july">July</option>
                        <option value="august">August</option>
                        <option value="september">September</option>
                        <option value="october">October</option>
                        <option value="november">November</option>
                        <option value="december">December</option>
                    </select>
                </div>

                <div class="col-12 d-none" id="filterTdsQuarterGroup">
                    <label for="filterTdsQuarter" class="form-label">Select Quarter</label>
                    <select class="form-select" id="filterTdsQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Q1 (Apr - Jun)</option>
                        <option value="q2">Q2 (Jul - Sep)</option>
                        <option value="q3">Q3 (Oct - Dec)</option>
                        <option value="q4">Q4 (Jan - Mar)</option>
                    </select>
                </div>
                
                <div class="col-12 d-none" id="filterTdsYearGroup">
                    <label for="filterTdsYear" class="form-label">Select Year</label>
                    <select class="form-select" id="filterTdsYear" name="year">
                        <option value="">Choose year</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3">Download Options</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-12">
                        <label for="tdsDownloadFormat" class="form-label">Choose format</label>
                        <select class="form-select" id="tdsDownloadFormat" name="download_format">
                            <option value="">Select format</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="ti ti-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light" id="tdsFilterReset">Reset</button>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
            {{-- <button type="button" class="btn btn-primary">Apply Filter</button> --}}
        </div>
    </div>
</div>

<style>
.big-tooltip .tooltip-inner {
    max-width: 400px;  
    font-size: 14px;  
    padding: 12px;   
    text-align: left; 
    line-height: 1.6;
}
</style>

    <script>
	
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});

        document.addEventListener('DOMContentLoaded', function () {

            const monthGroup   = document.getElementById('filterTdsMonthGroup');
            const quarterGroup = document.getElementById('filterTdsQuarterGroup');
            const yearGroup    = document.getElementById('filterTdsYearGroup');

            const radios = document.querySelectorAll('input[name="tdsPeriodType"]');

            function toggleDropdowns() {
                const value = document.querySelector('input[name="tdsPeriodType"]:checked')?.value || 'month';

                monthGroup.classList.add('d-none');
                quarterGroup.classList.add('d-none');
                yearGroup.classList.add('d-none');

                if (value === 'month') {
                    monthGroup.classList.remove('d-none');
                } 
                else if (value === 'quarter') {
                    quarterGroup.classList.remove('d-none');
                } 
                else if (value === 'year') {
                    yearGroup.classList.remove('d-none');
                }
            }

            // Listen only to change (radio best practice)
            radios.forEach(radio => {
                radio.addEventListener('change', toggleDropdowns);
            });

            // Initial load
            toggleDropdowns();
        });

        document.getElementById('tdsFilterReset').addEventListener('click', function () {
            document.getElementById('tdsPeriodMonth').checked = true;
            setTimeout(toggleDropdowns, 0);
        });

    </script>
@endsection
