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
                        <li class="breadcrumb-item"><a href="#">Tax Filing & Returns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employee State Insurance (ESIC)</li>
                    </ul>
                </div>
                <div class="col-md-5">
                    <div class="page-header-title">
                        <h2 class="mb-0 text-dark fw-bold">Employee State Insurance (ESIC)</h2>
                    </div>
                </div>
                <div class="col-md-7 text-end">
                    <a href="#esiFilterOffcanvas" class="btn btn-outline-secondary px-3 py-2 rounded-3 me-2 shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#esiFilterOffcanvas" aria-controls="esiFilterOffcanvas">
                        <i class="ti ti-filter f-16"></i> Summary of Return
                    </a>
					<a href="https://portal.esic.gov.in/" 
					   target="_blank" 
					   class="btn btn-primary px-3 py-2 rounded-3 shadow-sm"
					   data-bs-toggle="tooltip"
					   data-bs-html="true"
					   data-bs-custom-class="big-tooltip"
					   title="
					   <strong>Steps for payment of ESIC:</strong><br><br>
					   1. Login to https://portal.esic.gov.in/<br>
					   2. Enter username, password and Captcha.<br>
					   3. Click on 'File monthly contribution'.<br>
					   4. Enter contribution month and year.<br>
					   5. Select contribution type.<br>
					   6. Upload ECR contribution details.<br>
					   7. Click Submit and finalize.
					   ">
					   <i class="ti ti-credit-card f-16"></i> ESIC Payment
					</a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- Premium Static Alert (Visible by default, no collapse) -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card premium-warning-alert border-0 shadow-sm rounded-4 overflow-hidden mb-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start flex-column flex-lg-row gap-3">
                        <div class="flex-grow-1 me-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="avtar avtar-s btn-light-warning rounded-circle"><i class="ti ti-help-circle f-18"></i></span>
                                <h5 class="mb-0 fw-bold text-dark">Steps for payment of ESIC:</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <ol class="list-unstyled mb-0">
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">1</span>
                                            <span>Login to <a href="https://portal.esic.gov.in/" target="_blank" class="fw-semibold text-primary text-decoration-none">https://portal.esic.gov.in/</a> or click the ESIC Payment button above.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">2</span>
                                            <span>Enter username, password and Captcha code for login to employer portal.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">3</span>
                                            <span>Click on <strong class="text-dark">"File monthly contribution"</strong>.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">4</span>
                                            <span>Enter contribution month and year.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">5</span>
                                            <span>Select contribution type.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 mb-md-0 text-dark font-14">
                                            <span class="step-num-badge-new">6</span>
                                            <span>Upload the MC Excel file filled with contribution details.</span>
                                        </li>
                                    </ol>
                                </div>
                                <div class="col-md-6">
                                    <ol class="list-unstyled mb-0">
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">7</span>
                                            <span>Click on <strong class="text-dark">"Submit"</strong> Button.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">8</span>
                                            <span>Check all the details and contribution amount, then click on final submit button.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">9</span>
                                            <span>Click on "Generate Challan".</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">10</span>
                                            <span>Click on "View" button next to challan.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 mb-2 text-dark font-14">
                                            <span class="step-num-badge-new">11</span>
                                            <span>Select contribution amount and mode of payment.</span>
                                        </li>
                                        <li class="d-flex align-items-start gap-2 text-dark font-14">
                                            <span class="step-num-badge-new">12</span>
                                            <span>Click on "Submit" to finish transaction.</span>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 align-self-lg-center d-none d-lg-block">
                            <img src="../assets/images/application/img-accout-alert.png" alt="img" class="img-fluid wid-80 opacity-85">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table tbl-product m-0 custom-list-table align-middle" id="pc-dt-simple">
                            <thead>
                                <tr class="bg-light-header">
                                    <th class="text-end py-3 ps-4" style="width: 60px;">#</th>
                                    <th class="py-3">Employee ID</th>
                                    <th class="py-3">Employee Name</th>
                                    <th class="py-3">ESIC No</th>
                                    <th class="py-3">Gross Wages</th>
                                    <th class="py-3">ESI Wages</th>
                                    <th class="py-3">Employee Contribution</th>
                                    <th class="py-3">Challan No</th>
                                    <th class="py-3">Payment Date</th>
                                    <th class="py-3">Status</th>
                                    <th class="text-center py-3 pe-4" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $key => $emp)
                                    <tr>
                                        <td class="text-end ps-4 fw-medium text-muted">{{ $key + 1 }}</td>
                                        <td class="fw-bold text-dark">{{ $emp->employee_id }}</td>
                                        <td>{{ $emp->name }}</td>
                                        <td class="text-muted">{{ $emp->esic_no }}</td>
                                        <td class="fw-semibold text-dark">₹ {{ number_format($emp->total_addition, 2) }}</td>
                                        <td class="fw-semibold text-dark">₹ {{ number_format($emp->total_addition, 2) }}</td>
                                        <td class="text-primary fw-semibold">₹ {{ number_format($emp->esi, 2) }}</td>
                                        <td class="text-muted">{{ $emp->payslip_no }}</td>
                                        <td class="text-muted">{{ \Carbon\Carbon::parse($emp->payment_date)->format('d-m-Y') }}</td>
                                        <td><span class="badge-pill-custom badge-pill-resolved">Done</span></td>
                                        <td class="text-center pe-4">
                                            <a href="javascript:void(0)"
                                                class="btn-action-detail viewESI"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewDetailsModal"
                                                data-esic="{{ $emp->esic_no }}"
                                                data-gross="{{ $emp->total_addition }}"
                                                data-empesi="{{ $emp->esi }}"
                                                data-employeresi="{{ number_format(($emp->total_addition * 3.25) / 100, 2) }}"
                                                data-total="{{ number_format($emp->esi + (($emp->total_addition * 3.25) / 100), 2) }}"
                                                data-date="{{ \Carbon\Carbon::parse($emp->payment_date)->format('d-m-Y') }}"
                                                title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            No ESI records found for this month
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark font-20">ESI Filing Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4">
                <div class="table-responsive rounded-3 overflow-hidden border">
                    <table class="table table-striped table-hover m-0 align-middle font-14">
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee ESIC Number</th>
                            <td id="esi_no" class="ps-3 text-muted"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Gross Wages</th>
                            <td id="esi_gross" class="ps-3 fw-semibold text-dark"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employee ESI (0.75%)</th>
                            <td id="esi_employee" class="ps-3 fw-semibold text-primary"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Employer ESI (3.25%)</th>
                            <td id="esi_employer" class="ps-3 fw-semibold text-success"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Total ESI</th>
                            <td id="esi_total" class="ps-3 fw-bold text-dark"></td>
                        </tr>
                        <tr>
                            <th class="w-30 border-end bg-light fw-bold text-dark ps-3">Date of Payment</th>
                            <td id="esi_date" class="ps-3 text-muted"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="esiFilterOffcanvas" aria-labelledby="esiFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold text-dark" id="esiFilterOffcanvasLabel">Filter ESI Filing Records</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="esiFilterForm" method="POST" action="{{ route('download.esi.filing') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterEsiName" class="form-label fw-semibold text-dark">Employee Name</label>
                    <input type="text" class="form-control" id="filterEsiName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterEsiEmployeeId" class="form-label fw-semibold text-dark">Employee ID</label>
                    <input type="text" class="form-control" id="filterEsiEmployeeId" name="employee_id" placeholder="Filter by employee ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3 fw-bold text-dark">Filter By Period</h6>
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
                    <label for="filterEsiMonth" class="form-label fw-semibold text-dark">Select Month</label>
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
                    <label for="filterEsiQuarter" class="form-label fw-semibold text-dark">Select Quarter</label>
                    <select class="form-select" id="filterEsiQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Quarter 1 (Apr - Jun)</option>
                        <option value="q2">Quarter 2 (Jul - Sep)</option>
                        <option value="q3">Quarter 3 (Oct - Dec)</option>
                        <option value="q4">Quarter 4 (Jan - Mar)</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterEsiYearGroup">
                    <label for="filterEsiYear" class="form-label fw-semibold text-dark">Select Year</label>
                    <select class="form-select" id="filterEsiYear" name="year">
                        <option value="">Choose year</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                        <option value="2025-2026">2025-2026</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3 fw-bold text-dark">Download Options</h6>
                <div class="row g-3 align-items-end">
                    <div class="col-12">
                        <label for="esiDownloadFormat" class="form-label fw-semibold text-dark">Choose format</label>
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
/* Premium Alert Styles (Visible by default) */
.premium-warning-alert {
    background: #fffdf5;
    border-left: 4px solid #f59e0b !important;
    border: 1px solid #ffeeba;
}
.step-num-badge-new {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}

/* Custom Table Styles */
.bg-light-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.custom-list-table th {
    font-weight: 600;
    color: #475569;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.custom-list-table td {
    padding: 14px 12px !important;
}
.custom-list-table tbody tr {
    transition: background-color 0.2s ease;
}
.custom-list-table tbody tr:hover {
    background-color: #f8fafc;
}
.btn-action-detail {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4f46e5;
    background: rgba(79, 70, 229, 0.08);
    border: none;
    transition: all 0.2s;
}
.btn-action-detail:hover {
    color: #ffffff;
    background: #4f46e5;
}

/* Custom Badge Styling */
.badge-pill-custom {
    padding: 4px 12px;
    border-radius: 50rem;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-pill-resolved {
    background: rgba(16, 185, 129, 0.08);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.15);
}

.font-12 {
    font-size: 12px;
}
.font-14 {
    font-size: 14px;
}
.w-30 {
    width: 30%;
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

            toggleGroups();
        };

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