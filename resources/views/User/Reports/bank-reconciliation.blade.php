@extends('App.Layout')

@section('container')

<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bank Reconciliation</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Bank Reconciliation Report</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    <h4 class="text-center">
                        Bank Reconciliation Report
                    </h4>
                </div>

                <div class="card-body">

                    <div class="row">
					
						<div class="col-md-3">
							<label class="form-label">Proprietorship Company</label>
							<select name="propId" id="propId" class="form-control">
								<option value="">{{ parentCompanyName() }}</option>
								@foreach($proprietorships as $company)
									<option value="{{ $company->id }}">
										{{ $company->comp_name }}
									</option>
								@endforeach
							</select>
						</div>

                        <div class="col-md-3">

                            <label>
                                Financial Year
                            </label>

                            <select class="form-select"
                                    id="financial_year">

                                <option value="">
                                    Select
                                </option>

                                <option value="2025-2026">
                                    2025-2026
                                </option>

                                <option value="2026-2027">
                                    2026-2027
                                </option>

                            </select>

                        </div>

                        <div class="col-md-3">

                            <label>
                                Report Type
                            </label>

                            <select class="form-select"
                                    id="reportType"
                                    onchange="toggleFilters()">

                                <option value="">
                                    Select
                                </option>

                                <option value="Monthly">
                                    Monthly
                                </option>

                                <option value="Quarterly">
                                    Quarterly
                                </option>

                                <option value="Half-Yearly">
                                    Half-Yearly
                                </option>

                                <option value="Yearly">
                                    Yearly
                                </option>

                            </select>

                        </div>

                        <div class="col-md-3">

                            <label>
                                Quarter
                            </label>

                            <select class="form-select"
                                    id="quarterSelect"
                                    disabled>

                                <option value="">
                                    Select Quarter
                                </option>

                                <option value="1">
                                    Q1
                                </option>

                                <option value="2">
                                    Q2
                                </option>

                                <option value="3">
                                    Q3
                                </option>

                                <option value="4">
                                    Q4
                                </option>

                            </select>

                        </div>

                        <div class="col-md-3">

                            <label>
                                Month
                            </label>

                            <select class="form-select"
                                    id="monthSelect"
                                    disabled>

                                <option value="">
                                    Select Month
                                </option>

                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>

                            </select>

                        </div>

                    </div>

                    <div class="row mt-3">

                        <div class="col-md-12 text-end">

                            <button type="button"
                                    class="btn btn-primary"
                                    onclick="fetchBankReconciliation()">

                                Generate Report

                            </button>
							<a href="javascript:void(0);" onclick="downloadPdf()" class="btn btn-primary">Download</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Amount</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Opening Balance</td>
                                    <td id="openingCash"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Deposit In Transit</td>
                                    <td></td>
                                    <td id="depositAmt"></td>
                                </tr>
                                <tr>
                                    <td>Unrepresented Cheques</td>
                                    <td></td>
                                    <td id="chequeAmt"></td>
                                </tr>
                                <tr>
                                    <td>Bank Charges</td>
                                    <td></td>
                                    <td id="charges"></td>
                                </tr>
                                <tr>
                                    <td>Closing Balance</td>
                                    <td></td>
                                    <td id="closingBalance"></td>
                                </tr>

                                <tr style="background:#ffe082">
                                    <td><strong>Reconciled Balance</strong></td>
                                    <td id="matchedBalance1"></td>
                                    <td id="matchedBalance2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
			<div class="card">

				<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

					<div class="d-flex align-items-center gap-2">

						<h5 class="mb-0">
							Matched & Unmatched Records
						</h5>

						<div class="custom-tooltip-wrapper">

							<span class="reconcile-help">

								<i class="ti ti-help-circle text-primary"></i>

								<span>How to Reconcile?</span>

							</span>

							<div class="custom-tooltip-box">

								<div class="tooltip-title">
									Bank Reconciliation Guide
								</div>

								<div class="tooltip-item success">

									<strong>
										Matched
									</strong>

									<small>
										Transaction matched successfully with voucher records.
									</small>

								</div>

								<div class="tooltip-item warning">

									<strong>
										Review Required
									</strong>

									<small>
										Verify amount, date, purpose, reference number,
										and transaction type for possible matching. (Cash & Banking >> Payment / Receipt Voucher).
									</small>

								</div>

								<div class="tooltip-item danger">

									<strong>
										Unmatched
									</strong>

									<small>
										No matching voucher found. Create or update voucher
										details to complete reconciliation.
									</small>

								</div>

							</div>

						</div>

					</div>

					<div>
						<select id="pageSize" class="form-select form-select-sm">
							<option value="10">10</option>
							<option value="25" selected>25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>

				</div>

				<div class="card-body">

					<div class="table-responsive">

						<table class="table table-bordered">

							<thead>
								<tr>
									<th>Bank Name</th>
									<th>Trans. Date</th>
									<th>Trans. Type</th>
									<th>Bank Amount</th>
									<th>Purpose</th>
									<th>Voucher No</th>
									<th>Voucher Date</th>
									<th>Voucher Amount</th>									
									<th>Status</th>
									<th>Score</th>
								</tr>
							</thead>

							<tbody id="matchedTableBody"></tbody>

						</table>

					</div>

					<div class="d-flex justify-content-between align-items-center mt-3">

						<div id="paginationInfo"></div>

						<div>
							<button class="btn btn-sm btn-primary"
									id="prevBtn">
								Previous
							</button>

							<span class="mx-2" id="pageNumber"></span>

							<button class="btn btn-sm btn-primary"
									id="nextBtn">
								Next
							</button>
						</div>

					</div>

				</div>

			</div>
		</div>
		
		
    </div>
</div>

<style>
.custom-tooltip-wrapper{
    position: relative;
}

.reconcile-help{

    display: flex;
    align-items: center;
    gap: 5px;

    padding: 5px 10px;

    border-radius: 20px;

    background: #eef4ff;

    color: #0d6efd;

    font-size: 13px;

    font-weight: 600;

    cursor: pointer;

    transition: 0.3s;
}

.reconcile-help:hover{
    background: #dbe9ff;
}

.custom-tooltip-box{

    position: absolute;

    bottom: 45px;

    left: 0;

    width: 340px;

    background: #fff;

    border-radius: 14px;

    padding: 14px;

    box-shadow: 0 10px 30px rgba(0,0,0,0.15);

    border: 1px solid #e9ecef;

    display: none;

    z-index: 9999;
}

.custom-tooltip-wrapper:hover .custom-tooltip-box{
    display: block;
}

.tooltip-title{

    font-size: 15px;

    font-weight: 700;

    margin-bottom: 12px;

    color: #212529;
}

.tooltip-item{

    padding: 10px 12px;

    border-radius: 10px;

    margin-bottom: 10px;
}

.tooltip-item:last-child{
    margin-bottom: 0;
}

.tooltip-item strong{

    display: block;

    margin-bottom: 4px;

    font-size: 14px;
}

.tooltip-item small{

    color: #555;

    line-height: 1.5;
}

.tooltip-item.success{
    background: #e9f9ee;
}

.tooltip-item.warning{
    background: #fff8e1;
}

.tooltip-item.danger{
    background: #ffebee;
}
</style>

<script>

	let allRows = [];
	let currentPage = 1;
	let pageSize = 25;

	function toggleFilters()
	{
		let type = $("#reportType").val();

		$("#quarterSelect").prop('disabled', true);
		$("#monthSelect").prop('disabled', true);

		if(type == 'Quarterly' || type == 'Half-Yearly')
		{
			$("#quarterSelect").prop('disabled', false);
		}

		if(type == 'Monthly')
		{
			$("#monthSelect").prop('disabled', false);
		}
	}

	function downloadPdf()
	{
		let propId = $("#propId").val();
		let financialYear = $("#financial_year").val();
		let reportType    = $("#reportType").val();
		let quarter       = $("#quarterSelect").val();
		let month         = $("#monthSelect").val();

		if(financialYear == '')
		{
			alert('Select Financial Year');
			return;
		}

		if(reportType == '')
		{
			alert('Select Report Type');
			return;
		}

		let url =
			"{{ route('bank.reconciliation.download') }}" +
			"?propId=" + propId +
			"&financial_year=" + financialYear +
			"&report_type=" + reportType +
			"&quarter=" + quarter +
			"&month=" + month;

		window.open(url, '_blank');
	}

	function fetchBankReconciliation()
	{
		let propId = $("#propId").val();
		let financialYear = $("#financial_year").val();
		let reportType    = $("#reportType").val();
		let quarter       = $("#quarterSelect").val();
		let month         = $("#monthSelect").val();

		if(financialYear == '')
		{
			alert('Select Financial Year');
			return;
		}

		if(reportType == '')
		{
			alert('Select Report Type');
			return;
		}

		$("#loader").show();

		$.ajax({

			url: "{{ route('user.fetchBankReconciliation') }}",

			type: "POST",

			data: {

				_token: "{{ csrf_token() }}",

				propId: propId,
				
				financial_year: financialYear,

				report_type: reportType,

				quarter: quarter,

				month: month
			},

			success: function(res)
			{
				$("#loader").hide();
				
				// NOTIFY IF NO STATEMENT UPLOADED
				if(!res.statement_uploaded)
				{
					showToast('Bank statement is not uploaded for the selected period.','warning');
					return;
				}

				$("#openingCash").text(res.opening_cash);

				$("#depositAmt").text(res.deposit);

				$("#chequeAmt").text(res.cheque);

				$("#charges").text(res.charges);

				$("#closingBalance").text(res.closing_bank);

				$("#matchedBalance1").text(res.reconciled_cash);

				$("#matchedBalance2").text(res.reconciled_bank);

				allRows = [];

				// ==================================
				// MATCHED
				// ==================================

				res.matched_rows.forEach(function(item){

					item.badge = 'success';

					allRows.push(item);
				});

				// ==================================
				// REVIEW
				// ==================================

				res.review_rows.forEach(function(item){

					item.badge = 'warning';

					allRows.push(item);
				});

				// ==================================
				// UNMATCHED
				// ==================================

				res.unmatched_rows.forEach(function(item){

					item.badge = 'danger';

					allRows.push(item);
				});

				currentPage = 1;

				renderTable();
			}
		});
	}

	function renderTable()
	{
		pageSize = parseInt($("#pageSize").val());

		let start = (currentPage - 1) * pageSize;

		let end = start + pageSize;

		let paginatedRows = allRows.slice(start, end);

		let rows = '';

		paginatedRows.forEach(function(item){

			rows += `
				<tr>
					<td>${item.bank_name}</td>
					<td>${item.bank_date}</td>
					<td>${item.tran_type}</td>
					<td>${item.bank_amount}</td>
					<td>${item.purpose}</td>
					<td>${item.voucher_no}</td>
					<td>${item.voucher_date}</td>
					<td>${item.voucher_amount}</td>					
					<td>
						<span class="badge bg-${item.badge}">
							${item.status}
						</span>
					</td>
					<td>${item.score}</td>
				</tr>
			`;
		});

		$("#matchedTableBody").html(rows);

		let totalPages = Math.ceil(allRows.length / pageSize);

		$("#pageNumber").text(
			`Page ${currentPage} of ${totalPages}`
		);

		$("#paginationInfo").text(
			`Showing ${start + 1} to ${Math.min(end, allRows.length)} of ${allRows.length} entries`
		);

		$("#prevBtn").prop('disabled', currentPage == 1);

		$("#nextBtn").prop('disabled', currentPage == totalPages);
	}

	// ======================================
	// NEXT PAGE
	// ======================================

	$(document).on('click', '#nextBtn', function(){

		let totalPages = Math.ceil(allRows.length / pageSize);

		if(currentPage < totalPages)
		{
			currentPage++;

			renderTable();
		}
	});

	// ======================================
	// PREVIOUS PAGE
	// ======================================

	$(document).on('click', '#prevBtn', function(){

		if(currentPage > 1)
		{
			currentPage--;

			renderTable();
		}
	});

	// ======================================
	// PAGE SIZE CHANGE
	// ======================================

	$(document).on('change', '#pageSize', function(){

		currentPage = 1;

		renderTable();
	});

</script>

@endsection