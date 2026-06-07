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
                        <li class="breadcrumb-item" aria-current="page">Professional Tax Payment</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div class="page-header-title">
                        <h2 class="mb-0">Professional Tax Payment</h2>
                    </div>
                </div>
                <div class="col-md-8 text-end">
					<a href="#ptaxFilterOffcanvas" class="btn btn-primary shadow" style="font-size: 16px;" data-bs-toggle="offcanvas" data-bs-target="#ptaxFilterOffcanvas" aria-controls="ptaxFilterOffcanvas">
                        Summary of Return
                    </a>
					<a href="https://professiontax.wb.gov.in/" 
					   target="_blank" 
					   class="btn btn-primary shadow"
					   style="font-size: 16px;">
					   Professional Tax Payment
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
                            <h4 class="alert-heading mb-3">Steps for payment of Professional tax:</h4>
                            <ol class="list-unstyled mb-0">
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">1</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Login to <a href="https://professiontax.wb.gov.in/" target="_blank">https://professiontax.wb.gov.in/</a> or Click on Professional Tax Payment Button</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">2</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "Quick Pay".</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">3</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Select Payment for registration or enrollment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">4</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Enter registration or enrollment number.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">5</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Select year or month for payment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">6</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>Click on "Pay Now" and make the payment.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2 text-dark">
                                    <span class="badge rounded-circle bg-warning text-dark d-inline-flex align-items-center justify-content-center" style="width:24px;height:24px;">7</span>
                                    <span class="text-dark fw-bold">&rarr;</span>
                                    <span>After successful payment go to payment history and download the challan copy.</span>
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
                                
                                <th>Gross Wages</th>
                                <th>PTAX Wages</th>
                                
                                <th>Challan No</th>
                                <th>Payment Date</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $index => $ptax)
                            <tr>
                                <td class="text-end">{{ $index + 1 }}</td>
                                <td>{{ $ptax->employee_id }}</td>
                                <td>{{ $ptax->name }}</td>

                                <td>{{ number_format($ptax->gross_salary, 2) }}</td>
                                <td>{{ number_format($ptax->ptax_amount, 2) }}</td>

                                <td>{{ $ptax->payslip_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($ptax->payment_date)->format('d-m-Y') }}</td>
                                <td>Done</td>

                                <td>
                                    <button class="btn btn-sm btn-info view-ptax-details-btn"
                                        data-employee-id="{{ $ptax->employee_id }}"
                                        data-employee-name="{{ $ptax->name }}"
                                        data-gross-salary="{{ number_format($ptax->gross_salary, 2) }}"
                                        data-ptax-amount="{{ number_format($ptax->ptax_amount, 2) }}"
                                        data-challan-no="{{ $ptax->payslip_no }}"
                                        data-payment-date="{{ \Carbon\Carbon::parse($ptax->payment_date)->format('d-m-Y') }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewDetailsModal">
                                        <i class="ti ti-eye f-20"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
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
					<h5 class="modal-title">PTAX Filing Details</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Employee ID</th>
							<td id="detailEmployeeId"></td>
						</tr>
						<tr>
							<th>Employee Name</th>
							<td id="detailEmployeeName"></td>
						</tr>
						<tr>
							<th>Gross Wages</th>
							<td id="detailGrossWages"></td>
						</tr>
						<tr>
							<th>PTAX Wages</th>
							<td id="detailPtaxWages"></td>
						</tr>
						<tr>
							<th>Challan No</th>
							<td id="detailChallanNo"></td>
						</tr>
						<tr>
							<th>Payment Date</th>
							<td id="detailPaymentDate"></td>
						</tr>
						<tr>
							<th>Remarks</th>
							<td id="detailRemarks"></td>
						</tr>
					</table>

					<div class="modal-footer">
						<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="ptaxFilterOffcanvas" aria-labelledby="ptaxFilterOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="ptaxFilterOffcanvasLabel">Filter PTAX Filing Records</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" >
        <form id="ptaxFilterForm" method="POST" action="{{ route('download.ptax.filing') }}">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label for="filterPtaxName" class="form-label">Employee Name</label>
                    <input type="text" class="form-control" id="filterPtaxName" name="name" placeholder="Filter by name">
                </div>
                <div class="col-12">
                    <label for="filterPtaxEmployeeId" class="form-label">Employee ID</label>
                    <input type="text" class="form-control" id="filterPtaxEmployeeId" name="employee_id" placeholder="Filter by employee ID">
                </div>
            </div>

            <div class="mb-4">
                <h6 class="mb-3">Filter By Period</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ptaxPeriodType" id="ptaxPeriodMonth" value="month" checked>
                        <label class="form-check-label" for="ptaxPeriodMonth">Monthly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ptaxPeriodType" id="ptaxPeriodQuarter" value="quarter">
                        <label class="form-check-label" for="ptaxPeriodQuarter">Quarterly</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ptaxPeriodType" id="ptaxPeriodYear" value="year">
                        <label class="form-check-label" for="ptaxPeriodYear">Yearly</label>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12" id="filterPtaxMonthGroup">
                    <label for="filterPtaxMonth" class="form-label">Select Month</label>
                    <select class="form-select" id="filterPtaxMonth" name="month">
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
                <div class="col-12 d-none" id="filterPtaxQuarterGroup">
                    <label for="filterPtaxQuarter" class="form-label">Select Quarter</label>
                    <select class="form-select" id="filterPtaxQuarter" name="quarter">
                        <option value="">Choose quarter</option>
                        <option value="q1">Quarter 1 (Apr - Jun)</option>
                        <option value="q2">Quarter 2 (Jul - Sep)</option>
                        <option value="q3">Quarter 3 (Oct - Dec)</option>
                        <option value="q4">Quarter 4 (Jan - Mar)</option>
                    </select>
                </div>
                <div class="col-12 d-none" id="filterPtaxYearGroup">
                    <label for="filterPtaxYear" class="form-label">Select Year</label>
                    <select class="form-select" id="filterPtaxYear" name="year">
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
                        <label for="ptaxDownloadFormat" class="form-label">Choose format</label>
                        <select class="form-select" id="ptaxDownloadFormat" name="download_format">
                            <option value="">Select format</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit"
                                class="btn btn-outline-primary w-100"
                                onclick="return checkPtaxFormat();">
                            <i class="ti ti-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer border-top p-3 d-flex justify-content-between">
        <button type="button" class="btn btn-light" id="ptaxFilterReset">Reset</button>
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
        const initPtaxPeriodToggle = function() {
            const periodRadios = document.querySelectorAll('input[name="ptaxPeriodType"]');
            const monthGroup = document.getElementById('filterPtaxMonthGroup');
            const quarterGroup = document.getElementById('filterPtaxQuarterGroup');
            const yearGroup = document.getElementById('filterPtaxYearGroup');
            const resetButton = document.getElementById('ptaxFilterReset');
            const form = document.getElementById('ptaxFilterForm');

            if (!periodRadios.length || !monthGroup || !quarterGroup || !yearGroup || !form) {
                console.log('Elements not found for PTAX period toggle');
                return;
            }

            const toggleGroups = function() {
                const selected = document.querySelector('input[name="ptaxPeriodType"]:checked');
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
        initPtaxPeriodToggle();

        const offcanvasElement = document.getElementById('ptaxFilterOffcanvas');
        if (offcanvasElement) {
            offcanvasElement.addEventListener('shown.bs.offcanvas', function() {
                initPtaxPeriodToggle();
            });
        }
    });

    $(document).on('click', '.view-ptax-details-btn', function () {

        // Get data from button
        let employeeId   = $(this).data('employee-id');
        let employeeName = $(this).data('employee-name');
        let grossSalary  = $(this).data('gross-salary');
        let ptaxAmount   = $(this).data('ptax-amount');
        let challanNo    = $(this).data('challan-no');
        let paymentDate  = $(this).data('payment-date');

        // Set modal values
        $('#detailEmployeeId').text(employeeId);
        $('#detailEmployeeName').text(employeeName);
        $('#detailGrossWages').text('₹ ' + grossSalary);
        $('#detailPtaxWages').text('₹ ' + ptaxAmount);
        $('#detailChallanNo').text(challanNo);
        $('#detailPaymentDate').text(paymentDate);

        // Optional (if no remarks available)
        $('#detailRemarks').text('Done');
    });

    // PTAX Download format validation function
    function checkPtaxFormat() {
        const downloadFormat = document.getElementById('ptaxDownloadFormat').value;
        
        if (!downloadFormat) {
            alert('Please select a download format (PDF or Excel)');
            return false;
        }
        
        return true;
    }

</script>
@endsection