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
                        <li class="breadcrumb-item" aria-current="page">Provident Fund (PF / EPF)</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Provident Fund (PF / EPF) List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#pfFilterOffcanvas" class="btn btn-primary shadow" style="font-size: 16px;" data-bs-toggle="offcanvas" data-bs-target="#pfFilterOffcanvas" aria-controls="pfFilterOffcanvas">
                        Summary of Return
                    </a>
                     <a href="https://unifiedportal-emp.epfindia.gov.in" target="_blank" class="btn btn-primary shadow" style="font-size: 16px;" data-bs-toggle="offcanvas" >
                        PF /EPF Payment
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
                            <h4 class="alert-heading mb-3">Steps for payment of EPF:</h4>
                            <ol class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">1</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Login to <a href="https://unifiedportal-emp.epfindia.gov.in" target="_blank">https://unifiedportal-emp.epfindia.gov.in</a> or click on "PF /EPF Payment" Button</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">2</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Enter Establishment ID, username, password and Captcha code for login to employer portal.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">3</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "Payments" from Dashboard and go to "ECR (Electronic Challan cum Return)".</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">4</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Select wage month and return type.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">5</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Download ECR template from portal.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">6</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Upload the MC Excel file filled with contribution details.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">7</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "Submit" button.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">8</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Check all the details and contribution amount, then click on final submit button.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">9</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on Generate Challan.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">10</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "View" button.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">11</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Select contribution amount and mode of payment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">12</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "Submit".</span>
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
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>UAN</th>
                                <th>Gross Wages</th>
                                <th>PF Wages</th>
                                <th>PF Employee Contribution</th>
                                <th>PF Employer Contribution</th>
                                <th>Challan No</th>
                                <th>Payment Date</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $key => $emp)
                                <tr>
                                    <td class="text-end">{{ $key + 1 }}</td>

                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            {{ $emp->employee_id }}
                                        </span>
                                    </td>

                                    <td>
                                        <h6 class="mb-1">{{ $emp->name }}</h6>
                                    </td>

                                    {{-- UAN --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            {{ $emp->epf_no }}
                                        </span>
                                    </td>

                                    {{-- Gross Wages --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->total_addition, 2) }}
                                        </span>
                                    </td>

                                    {{-- PF Wages (same as gross or you can cap later) --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->total_addition, 2) }}
                                        </span>
                                    </td>

                                    {{-- PF Employee Contribution --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->provident_fund, 2) }}
                                        </span>
                                    </td>

                                    {{-- PF Employer Contribution (same value or modify later) --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->provident_fund, 2) }}
                                        </span>
                                    </td>

                                    {{-- Challan No --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            {{ $emp->payslip_no }}
                                        </span>
                                    </td>

                                    {{-- Payment Date --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            {{ \Carbon\Carbon::parse($emp->payment_date)->format('d-m-Y') }}
                                        </span>
                                    </td>

                                    {{-- Remarks --}}
                                    <td>
                                        <span class="badge bg-success">Done</span>
                                    </td>

                                    {{-- Action --}}
                                    <td>
                                        <a href="javascript:void(0)"
   class="avtar avtar-xs btn-link-secondary viewPF"
   data-bs-toggle="modal"
   data-bs-target="#viewDetailsModal"

   data-month="{{ \Carbon\Carbon::parse($emp->payment_date)->format('F Y') }}"
   data-name="{{ $emp->name }} ({{ $emp->employee_id }})"
   data-uan="{{ $emp->epf_no }}"
   data-basic="{{ number_format($emp->total_addition, 2) }}"
   data-emp_pf="{{ number_format($emp->provident_fund, 2) }}"
   data-employer_pf="{{ number_format($emp->provident_fund, 2) }}"
   data-pension="{{ number_format(($emp->total_addition * 8.33) / 100, 2) }}"
   data-total="{{ number_format(($emp->provident_fund * 2), 2) }}"
   data-challan="{{ $emp->payslip_no }}"
>
    <i class="ti ti-eye f-20"></i>
</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted">
                                        No PF records found for this month
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

<div class="modal custom-modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PF Filing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">PF Month:</label>
                        <p class="text-muted" id="pf_month"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Employee Name & ID:</label>
                        <p class="text-muted" id="emp_name_id"></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">UAN:</label>
                        <p class="text-muted" id="uan"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Basic Salary:</label>
                        <p class="text-muted" id="basic_salary"></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Employee PF (12%):</label>
                        <p class="text-muted" id="emp_pf"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Employer PF (3.67%):</label>
                        <p class="text-muted" id="employer_pf"></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Pension (8.33%):</label>
                        <p class="text-muted" id="pension_pf"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Total Contribution:</label>
                        <p class="text-muted" id="total_pf"></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="fw-bold">PF Challan Reference:</label>
                        <p class="text-muted" id="challan_no"></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<div class="offcanvas offcanvas-end" tabindex="-1" id="pfFilterOffcanvas" aria-labelledby="pfFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="pfFilterOffcanvasLabel">Filter PF Filing Records</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="pfFilterForm">
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterPfName" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" id="filterPfName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterPfEmployeeId" class="form-label">Employee ID</label>
                    <input type="text" class="form-control" id="filterPfEmployeeId" name="employee_id" placeholder="Filter by employee ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3">Filter By Period</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfPeriodType" id="pfPeriodMonth" value="month" checked>
                        <label class="form-check-label" for="pfPeriodMonth">Monthly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfPeriodType" id="pfPeriodQuarter" value="quarter">
                        <label class="form-check-label" for="pfPeriodQuarter">Quarterly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pfPeriodType" id="pfPeriodYear" value="year">
                        <label class="form-check-label" for="pfPeriodYear">Yearly</label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12" id="filterPfMonthGroup">
                    <label for="filterPfMonth" class="form-label">Select Month</label>
                    <select class="form-select" id="filterPfMonth" name="month">
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
                <div class="col-12 d-none" id="filterPfQuarterGroup">
                    <label for="filterPfQuarter" class="form-label">Select Quarter</label>
                    <select class="form-select" id="filterPfQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Quarter 1 (Apr - Jun)</option>
                        <option value="q2">Quarter 2 (Jul - Sep)</option>
                        <option value="q3">Quarter 3 (Oct - Dec)</option>
                        <option value="q4">Quarter 4 (Jan - Mar)</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterPfYearGroup">
                    <label for="filterPfYear" class="form-label">Select Year</label>
                    <select class="form-select" id="filterPfYear" name="year">
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
                        <label for="pfDownloadFormat" class="form-label">Choose format</label>
                        <select class="form-select" id="pfDownloadFormat" name="download_format">
                            <option value="">Select format</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary w-100">
                            <i class="ti ti-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light" id="pfFilterReset">Reset</button>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
            
        </div>
    </div>
</div>



<script>
     $(document).on('click', '.viewPF', function () {

        $('#pf_month').text($(this).data('month'));
        $('#emp_name_id').text($(this).data('name'));
        $('#uan').text($(this).data('uan'));
        $('#basic_salary').text('₹' + $(this).data('basic'));
        $('#emp_pf').text('₹' + $(this).data('emp_pf'));
        $('#employer_pf').text('₹' + $(this).data('employer_pf'));
        $('#pension_pf').text('₹' + $(this).data('pension'));
        $('#total_pf').text('₹' + $(this).data('total'));
        $('#challan_no').text($(this).data('challan'));

    });

    document.addEventListener('DOMContentLoaded', function() {
        const initPfPeriodToggle = function() {
            const periodRadios = document.querySelectorAll('input[name="pfPeriodType"]');
            const monthGroup = document.getElementById('filterPfMonthGroup');
            const quarterGroup = document.getElementById('filterPfQuarterGroup');
            const yearGroup = document.getElementById('filterPfYearGroup');
            const resetButton = document.getElementById('pfFilterReset');
            const form = document.getElementById('pfFilterForm');

            if (!periodRadios.length || !monthGroup || !quarterGroup || !yearGroup || !form) {
                console.log('Elements not found for PF period toggle');
                return;
            }

            const toggleGroups = function() {
                const selected = document.querySelector('input[name="pfPeriodType"]:checked');
                const value = selected ? selected.value : 'month';

                monthGroup.classList.toggle('d-none', value !== 'month');
                quarterGroup.classList.toggle('d-none', value !== 'quarter');
                yearGroup.classList.toggle('d-none', value !== 'year');
            };

            periodRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    toggleGroups();
                });
            });

            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    form.reset();
                    setTimeout(function() {
                        toggleGroups();
                    }, 50);
                });
            }

            // Initial toggle
            toggleGroups();
        };

        // Initialize immediately and also when offcanvas is shown
        initPfPeriodToggle();

        const offcanvasElement = document.getElementById('pfFilterOffcanvas');
        if (offcanvasElement) {
            offcanvasElement.addEventListener('shown.bs.offcanvas', function() {
                initPfPeriodToggle();
            });
        }
    });

    $('#pfFilterForm button').on('click', function () {

        let form = $('#pfFilterForm');

        $('<form>', {
            action: "{{ route('download.pf.filing') }}",
            method: 'POST'
        }).append(
            $('<input>', { type: 'hidden', name: '_token', value: "{{ csrf_token() }}" }),
            $('<input>', { type: 'hidden', name: 'name', value: $('#filterPfName').val() }),
            $('<input>', { type: 'hidden', name: 'employee_id', value: $('#filterPfEmployeeId').val() }),
            $('<input>', { type: 'hidden', name: 'pfPeriodType', value: $('input[name="pfPeriodType"]:checked').val() }),
            $('<input>', { type: 'hidden', name: 'month', value: $('#filterPfMonth').val() }),
            $('<input>', { type: 'hidden', name: 'quarter', value: $('#filterPfQuarter').val() }),
            $('<input>', { type: 'hidden', name: 'year', value: $('#filterPfYear').val() }),
            $('<input>', { type: 'hidden', name: 'download_format', value: $('#pfDownloadFormat').val() })
        ).appendTo('body').submit().remove();
    });


</script>

@endsection