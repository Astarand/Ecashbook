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
                        <li class="breadcrumb-item" aria-current="page">Employee State Insurance (ESIC)</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Employee State Insurance (ESIC) Payment List</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
                    <a href="#esiFilterOffcanvas" class="btn btn-primary shadow" style="font-size: 16px;" data-bs-toggle="offcanvas" data-bs-target="#esiFilterOffcanvas" aria-controls="esiFilterOffcanvas">
                        Summary of Return
                    </a>
					<a href="https://portal.esic.gov.in/" 
					   target="_blank" 
					   class="btn btn-primary shadow"
					   style="font-size: 16px;"
					   data-bs-toggle="tooltip"
					   data-bs-html="true"
					   data-bs-custom-class="big-tooltip"
					   title="
					   Steps for payment of ESIC:<br><br>
					   1. Login to https://portal.esic.gov.in/<br>
					   2. Enter username, password and Captcha code for login to employer portal.<br>
					   3. Click on 'File monthly contribution'.<br>
					   4. Enter contribution month and year.<br>
					   5. Select contribution type.<br>
					   6. Upload the MC Excel file filled with contribution details.<br>
					   7. Click on 'Submit' Button.<br>
					   8. Check all the details and contribution amount, then click on final submit button.<br>
					   9. Click on Generate Challan.<br>
					   10. Click on 'View' button.<br>
					   11. Select contribution amount and mode of payment.<br>
					   12. Click on 'Submit'.
					   ">
					   ESIC Payment
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
                            <h4 class="alert-heading mb-3">Steps for payment of ESIC:</h4>
                            <ol class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">1</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Login to <a href="https://portal.esic.gov.in/" target="_blank">https://portal.esic.gov.in/</a> or Click on ESIC Payment Button</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">2</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Enter username, password and Captcha code for login to employer portal.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">3</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "File monthly contribution".</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">4</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Enter contribution month and year.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">5</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Select contribution type.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">6</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Upload the MC Excel file filled with contribution details.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">7</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "Submit" Button.</span>
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
                                <th>ESIC No</th>
                                <th>Gross Wages</th>
                                <th>ESI Wages</th>
                                <th>ESI Employee Contribution</th>
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

                                    {{-- Employee ID --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            {{ $emp->employee_id }}
                                        </span>
                                    </td>

                                    {{-- Employee Name --}}
                                    <td>
                                        <h6 class="mb-1">{{ $emp->name }}</h6>
                                    </td>

                                    {{-- ESIC No --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            {{ $emp->esic_no }}
                                        </span>
                                    </td>

                                    {{-- Gross Wages --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->total_addition, 2) }}
                                        </span>
                                    </td>

                                    {{-- ESI Wages (same as gross unless capped later) --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->total_addition, 2) }}
                                        </span>
                                    </td>

                                    {{-- ESI Employee Contribution --}}
                                    <td>
                                        <span class="text-muted text-hover-primary">
                                            ₹{{ number_format($emp->esi, 2) }}
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
                                            class="avtar avtar-xs btn-link-secondary viewESI"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewDetailsModal"

                                            data-esic="{{ $emp->esic_no }}"
                                            data-gross="{{ $emp->total_addition }}"
                                            data-empesi="{{ $emp->esi }}"
                                            data-employeresi="{{ number_format(($emp->total_addition * 3.25) / 100, 2) }}"
                                            data-total="{{ number_format($emp->esi + (($emp->total_addition * 3.25) / 100), 2) }}"
                                            data-date="{{ \Carbon\Carbon::parse($emp->payment_date)->format('d-m-Y') }}">
                                                <i class="ti ti-eye f-20"></i>
                                        </a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">
                                        No ESI records found for this month
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

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ESI Filing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Employee ESIC Number:</label>
                        <p class="text-muted" id="esi_no"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Gross Wages:</label>
                        <p class="text-muted" id="esi_gross"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Employee ESI (0.75%):</label>
                        <p class="text-muted" id="esi_employee"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Employer ESI (3.25%):</label>
                        <p class="text-muted" id="esi_employer"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Total ESI:</label>
                        <p class="text-muted" id="esi_total"></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Date of Payment:</label>
                        <p class="text-muted" id="esi_date"></p>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="esiFilterOffcanvas" aria-labelledby="esiFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="esiFilterOffcanvasLabel">Filter ESI Filing Records</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" >
        <form id="esiFilterForm" method="POST" action="{{ route('download.esi.filing') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterEsiName" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" id="filterEsiName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterEsiEmployeeId" class="form-label">Employee ID</label>
                    <input type="text" class="form-control" id="filterEsiEmployeeId" name="employee_id" placeholder="Filter by employee ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3">Filter By Period</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="esiPeriodType" id="esiPeriodMonth" value="month" checked>
                        <label class="form-check-label" for="esiPeriodMonth">Monthly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="esiPeriodType" id="esiPeriodQuarter" value="quarter">
                        <label class="form-check-label" for="esiPeriodQuarter">Quarterly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="esiPeriodType" id="esiPeriodYear" value="year">
                        <label class="form-check-label" for="esiPeriodYear">Yearly</label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12" id="filterEsiMonthGroup">
                    <label for="filterEsiMonth" class="form-label">Select Month</label>
                    <select class="form-select" id="filterEsiMonth" name="month">
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
                <div class="col-12 d-none" id="filterEsiQuarterGroup">
                    <label for="filterEsiQuarter" class="form-label">Select Quarter</label>
                    <select class="form-select" id="filterEsiQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Quarter 1 (Apr - Jun)</option>
                        <option value="q2">Quarter 2 (Jul - Sep)</option>
                        <option value="q3">Quarter 3 (Oct - Dec)</option>
                        <option value="q4">Quarter 4 (Jan - Mar)</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterEsiYearGroup">
                    <label for="filterEsiYear" class="form-label">Select Year</label>
                    <select class="form-select" id="filterEsiYear" name="year">
                        <option value="">Choose year</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3">Download Options</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-12">
                        <label for="esiDownloadFormat" class="form-label">Choose format</label>
                        <select class="form-select" id="esiDownloadFormat" name="download_format">
                            <option value="">Select format</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit"
                                class="btn btn-outline-primary w-100"
                                onclick="return checkEsiFormat();">
                            <i class="ti ti-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light" id="esiFilterReset">Reset</button>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
            
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
	
    document.addEventListener('DOMContentLoaded', function() {
        const initEsiPeriodToggle = function() {
            const periodRadios = document.querySelectorAll('input[name="esiPeriodType"]');
            const monthGroup = document.getElementById('filterEsiMonthGroup');
            const quarterGroup = document.getElementById('filterEsiQuarterGroup');
            const yearGroup = document.getElementById('filterEsiYearGroup');
            const resetButton = document.getElementById('esiFilterReset');
            const form = document.getElementById('esiFilterForm');

            if (!periodRadios.length || !monthGroup || !quarterGroup || !yearGroup || !form) {
                console.log('Elements not found for ESI period toggle');
                return;
            }

            const toggleGroups = function() {
                const selected = document.querySelector('input[name="esiPeriodType"]:checked');
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
        initEsiPeriodToggle();

        const offcanvasElement = document.getElementById('esiFilterOffcanvas');
        if (offcanvasElement) {
            offcanvasElement.addEventListener('shown.bs.offcanvas', function() {
                initEsiPeriodToggle();
            });
        }
    });

    $(document).on('click', '.viewESI', function () {

        let gross = parseFloat($(this).data('gross'));

        $('#esi_no').text($(this).data('esic'));
        $('#esi_gross').text('₹ ' + gross.toFixed(2));
        $('#esi_employee').text('₹ ' + $(this).data('empesi'));
        $('#esi_employer').text('₹ ' + $(this).data('employeresi'));
        $('#esi_total').text('₹ ' + $(this).data('total'));
        $('#esi_date').text($(this).data('date'));

    });

    function checkEsiFormat() {
        const format = document.getElementById('esiDownloadFormat').value;
        if (!format) {
            alert('Please select download format (PDF or Excel)');
            return false;
        }
        return true;
    }



</script>
@endsection