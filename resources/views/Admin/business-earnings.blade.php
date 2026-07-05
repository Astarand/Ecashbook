@extends('App.Layout')

@section('container')
<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-6 col-sm-12">
                    <h5 class="mb-0 ms-2 d-md-block d-none">Package Analytics</h5>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="d-flex flex-nowrap align-items-center justify-content-md-end justify-content-center flex-md-nowrap flex-wrap gap-2 mt-md-0 mt-2 pe-md-2">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Weekly
                            </button>
                            <select id="timeRangeSelect" class="d-none">
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <ul class="dropdown-menu" aria-labelledby="timeRangeDropdown">
                                <li><a class="dropdown-item time-range-option" data-value="weekly" href="#">Weekly</a></li>
                                <li><a class="dropdown-item time-range-option" data-value="monthly" href="#">Monthly</a></li>
                                <li><a class="dropdown-item time-range-option" data-value="yearly" href="#">Yearly</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="weeklyUsageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Section -->
        <div class="col-lg-6 col-md-12">
            <div class="row">
                <!-- Total Companies -->
                <div class="col-md-6 col-xl-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-buildings text-primary f-24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Companies</h6>
                                <h4 id="totalCompanies" class="mb-0">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total CA/Accountant -->
                <div class="col-md-6 col-xl-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-users text-primary f-24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total CA/Accountant No.</h6>
                                <h4 id="totalCAs" class="mb-0">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Direct Attachment -->
                <div class="col-md-6 col-xl-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-paperclip text-success f-24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Direct Attachment</h6>
                                <h4 id="directAttachments" class="mb-0">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attached by CA -->
                <div class="col-md-6 col-xl-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-user-circle text-success f-24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Attached by CA</h6>
                                <h4 id="attachedByCA" class="mb-0">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Subscriber -->
                <div class="col-md-6 col-xl-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-crown text-warning f-24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Active Platform Accounts</h6>
                                <h4 id="totalSubscribers" class="mb-0">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Trial User -->
                <div class="col-md-6 col-xl-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                                <i class="ph-duotone ph-user-circle-plus text-warning f-24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Total Trial User</h6>
                                <h4 id="totalTrialUsers" class="mb-0">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Section -->
    <div class="row mt-4">
        <!-- Total Earning -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                        <i class="ph-duotone ph-currency-inr text-warning f-24"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Platform Usage Credit</h6>
                        <h4 id="totalEarnings" class="mb-0">₹0</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- CA/Accountant Earnings -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                        <i class="ph-duotone ph-currency-inr text-warning f-24"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total CA/Accountant Earnings</h6>
                        <h4 id="caEarnings" class="mb-0">₹0</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 d-flex align-items-center justify-content-center me-3">
                        <i class="ph-duotone ph-chart-line-up text-success f-24"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Net Profit</h6>
                        <h4 id="netProfit" class="mb-0">₹0.00</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filterType" id="customerRadio" value="customer" checked>
                        <label class="form-check-label" for="customerRadio">
                            Customer
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filterType" id="caRadio" value="ca">
                        <label class="form-check-label" for="caRadio">
                            CA Firm / Accountant
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card card-body table-card">
                <div class="table-responsive">
                    <!--<table class="table tbl-product my-3" id="pc-dt-simple">-->
                    <table class="table my-3" id="">
                        <thead>
                            <tr style="background-color: #cbcbcb;">
                                <th class="text-end">#</th>
                                <th>Company Name</th>
                                <th>Package Name</th>
                                <th>Subscription Type</th>
                                <th>Amount</th>
                                <th>CA Commission</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Transaction Id</th>
								<th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">                            
							 @include('partials.subscription-table', ['data' => $data])
                        </tbody>
                    </table>
					<div id="paginationLinks">
						@include('partials.pagination', ['data' => $data])
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

	<!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentDetailsModalLabel">Payment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Company Name</label>
                            <p id="modal-company-name">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Subscription Trough</label>
                            <p id="modal-subscription-trough">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Package Name</label>
                            <p id="modal-package-name">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Subscription Type</label>
                            <p id="modal-subscription-type">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Amount</label>
                            <p id="modal-amount">₹0</p>
                        </div>
						<div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CA Commission</label>
                            <p id="modal-ca-commission">₹0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Discount</label>
                            <p id="modal-discount">₹0</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Start Date</label>
                            <p id="modal-start-date">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">End Date</label>
                            <p id="modal-end-date">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Transaction ID</label>
                            <p id="modal-transaction-id">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Transaction Status</label>
                            <p id="modal-transaction-status"><span class="badge bg-success">-</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <p id="modal-payment-method">-</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Payment Date</label>
                            <p id="modal-payment-date">-</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
<!-- Include Chart.js first -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Then include your custom business.js -->
<script src="{{ asset('assets/js/business.js') }}"></script>
<script>
    // Handle dropdown UI
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.getElementById('timeRangeDropdown');
        const hiddenSelect = document.getElementById('timeRangeSelect');
        const options = document.querySelectorAll('.time-range-option');

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const value = this.getAttribute('data-value');
                const text = this.textContent;

                // Update dropdown button text
                dropdown.textContent = text;

                // Update hidden select value and trigger change event
                hiddenSelect.value = value;
                hiddenSelect.dispatchEvent(new Event('change'));
            });
        });
		
		const viewPaymentBtns = document.querySelectorAll('.view-payment-btn');
        viewPaymentBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-id');
				let type = $("input[name='filterType']:checked").val();
				
					$.ajax({
						url: "{{ route('payment.details') }}",
						type: "GET",
						data: { paymentId,type },
						success: function (paymentData) {
							document.getElementById('modal-company-name').textContent = paymentData.companyName;
							document.getElementById('modal-subscription-trough').textContent = paymentData.subscriptionTrough;
							document.getElementById('modal-package-name').textContent = paymentData.packageName;
							document.getElementById('modal-subscription-type').textContent = paymentData.subscriptionType;
							document.getElementById('modal-amount').textContent = paymentData.amount;
							document.getElementById('modal-ca-commission').textContent = paymentData.ca_amt;
							document.getElementById('modal-discount').textContent = paymentData.discount;
							document.getElementById('modal-start-date').textContent = paymentData.startDate;
							document.getElementById('modal-end-date').textContent = paymentData.endDate;
							document.getElementById('modal-transaction-id').textContent = paymentData.transactionId;

							// Set transaction status with appropriate badge
							const statusHTML = `<span class="badge bg-${paymentData.statusColor}">${paymentData.status}</span>`;
							document.getElementById('modal-transaction-status').innerHTML = statusHTML;

							document.getElementById('modal-payment-method').textContent = paymentData.paymentMethod;
							document.getElementById('modal-payment-date').textContent = paymentData.paymentDate;
						}
					});
				
            });
        });

		
		
    });
	
	$(document).ready(function () {

		function loadData(page = 1) {
			let type = $("input[name='filterType']:checked").val();
			let search = $("#searchInput").val();
			$("#tableBody").html('');
			$("#paginationLinks").html('');
			$('#loader').show();
			$.ajax({
				url: "/business-earnings",
				type: "GET",
				data: { type: type, search: search, page: page },
				success: function (response) {
					$('#loader').hide();
					$("#tableBody").html(response.html);
					$("#paginationLinks").html(response.pagination);
				}
			});
		}

		// Load on radio button change
		$("input[name='filterType']").change(function () {
			loadData();
		});

		// Load on search
		$("#searchInput").keyup(function () {
			loadData();
		});

		// On pagination click
		$(document).on("click", ".pagination a", function (event) {
			event.preventDefault();
			let page = $(this).attr("href").split("page=")[1];
			loadData(page);
		});

	});


</script>
@endsection