@extends('App.Layout')

@section('container')

<div class="pc-content">

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Trial Balance (TB)</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-tb-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Trial Balance (TB)</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- MAIN CONTENT -->
    <div class="row">
        <div class="col-md-12">

            <!-- FILTER CARD -->
            <div class="card mb-4 reconciliation-filter-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div class="card-header py-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1.05rem;">
                        <i class="ti ti-filter f-20"></i> Filter Trial Balance Options
                    </h5>
                </div>

                <div class="card-body p-4">
					<div class="alert alert-info mb-3" style="font-size:13px;">
						<h6 class="mb-2">
							<i class="ti ti-alert-circle me-1"></i>
							Opening Balance Update Required
						</h6>
						<p class="mb-0">
							Please update the <strong>Opening Balance</strong> in <strong>(Organization profile → Business details)</strong> before generating the report. An incorrect or missing opening balance may result in inaccurate financial reports.
						</p>
					</div>
					
                    <form method="POST" name="frmTrialBalance" id="frmTrialBalance" action="javascript:void(0);">
                        <div class="row g-3">

                            <div class="col-md-3">
								<label class="form-label fw-semibold text-muted">Proprietorship Company</label>
								<select name="propId" id="propId" class="form-select">
									<option value="">{{ parentCompanyName() }}</option>
									@foreach($proprietorships as $company)
										<option value="{{ $company->id }}">
											{{ $company->comp_name }}
										</option>
									@endforeach
								</select>
							</div>
							
							<!-- FROM DATE -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">From Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="from_date" id="from_date" required>
                            </div>

                            <!-- TO DATE -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">To Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="to_date" id="to_date" required>
                            </div>

                            <!-- LEDGER NAME (7 TYPES) -->
                            <!--<div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Ledger Name <span class="text-danger">*</span></label>
                                <select class="form-select" name="ledger_name" id="ledger_name" required>
                                    <option value="all">All</option>
                                    <option value="customer">Customer Ledger</option>
                                    <option value="supplier">Supplier Ledger</option>
                                    <option value="sales">Sales Ledger</option>
                                    <option value="purchase">Purchase Ledger</option>
                                    <option value="bank">Bank Ledger</option>
                                    <option value="gst_output">GST Output Ledger</option>
                                    <option value="gst_input">GST Input Ledger</option>
                                </select>
                            </div>-->
							<div class="col-md-3">
								<label class="form-label fw-semibold text-muted">
									Ledger Name
								</label>

								<input type="text"
									   class="form-control"
									   id="ledger_name"
									   name="ledger_name"
									   placeholder="Type Ledger Name...">
							</div>
							
							<!-- LEDGER GROUP -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Ledger Group</label>
                                <select class="form-select" name="ledger_group" id="ledgerGroup">
                                    <option value="">All</option>
                                    <option value="Assets">Assets</option>
									<option value="Liabilities">Liabilities</option>
									<option value="Equity">Equity</option>
									<option value="Income">Income</option>
									<option value="Expenses">Expenses</option>
                                </select>
                            </div>

                            <!-- LEDGER SUB GROUP -->
                            <!--<div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Ledger Sub Group</label>
                                <select class="form-select" name="ledger_sub_group" id="ledgerSubGroup" disabled>
                                    <option value="">Select Sub Group</option>
                                </select>
                            </div>-->
							
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-10 text-end">
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="height: 41px;">
                                    <i class="ti ti-settings f-18"></i> Generate Report
                                </button>
                            </div>
							<div class="col-md-2 text-end">
								<button type="reset"
										class="btn btn-outline-secondary flex-fill">
									<i class="ti ti-refresh"></i>
									Clear Filters
								</button>
							</div>
                        </div>
						
                    </form>
                </div>
            </div>

            <!-- LEDGER TABLE -->
            <div class="card mb-4 tb-table-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div class="card-header py-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1.05rem;">
                        <i class="ti ti-table f-20"></i> Trial Balance Worksheet
                    </h5>
					<div class="d-flex gap-2 mt-2">
						<button type="button"
								id="expandAllGroups"
								class="btn btn-sm btn-success">
							<i class="fa fa-plus-square"></i> Expand All
						</button>

						<button type="button"
								id="collapseAllGroups"
								class="btn btn-sm btn-danger">
							<i class="fa fa-minus-square"></i> Collapse All
						</button>
					</div>
                </div>
                <div class="card-body p-4">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-nowrap"
                            style="font-size:13px; vertical-align:middle;">

                            <!-- GROUP HEADERS -->
                            <thead>
                                <tr style="text-align:center; font-weight:700; font-size: 0.9rem;">
                                    <th colspan="2"
                                        style="background: #eef2ff; color: #4f46e5; border: 1px solid #cbd5e1; padding: 10px;">
                                        Ledger Details
                                    </th>

                                    <th colspan="2"
                                        style="background: #ecfdf5; color: #059669; border: 1px solid #cbd5e1; padding: 10px;">
                                        Opening Balance
                                    </th>

                                    <th colspan="2"
                                        style="background: #fef2f2; color: #dc2626; border: 1px solid #cbd5e1; padding: 10px;">
                                        Closing Balance
                                    </th>

                                    <th colspan="2"
                                        style="background: #fffbeb; color: #d97706; border: 1px solid #cbd5e1; padding: 10px;">
                                        Reporting Section
                                    </th>
                                </tr>

                                <!-- COLUMN HEADERS -->
                                <tr style="text-align:center; font-weight:600; background: #fafafa; font-size: 0.85rem;">
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Ledger Group</th>
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Ledger Name</th>                                    

                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Opening Dr (₹)</th>
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Opening Cr (₹)</th>

                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Closing Dr (₹)</th>
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Closing Cr (₹)</th>

                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Schedule III Head</th>
                                </tr>
                            </thead>

                            <!-- DATA -->
                            <tbody id="trialBodyData">
                                <tr>
                                    <td colspan="9"
                                        style="text-align:center; color:#6c757d; padding:20px;">
                                        No ledger data available
                                    </td>
                                </tr>
                            </tbody>

                            <!-- TOTAL FOOTER -->
                            <tfoot id="trialFooterData">
                                <tr style="font-weight:700; background:#f8f9fa;">
                                    <td colspan="4"
                                        style="text-align:right; border: 1px solid #e2e8f0;">
                                        Total Closing Balance
                                    </td>

                                    <td style="text-align:right; border: 1px solid #e2e8f0;" id="totalDr">
                                        ₹ 0.00
                                    </td>

                                    <td style="text-align:right; border: 1px solid #e2e8f0;" id="totalCr">
                                        ₹ 0.00
                                    </td>

                                    <td colspan="2" style="border: 1px solid #e2e8f0;"></td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
					
					<!-- Pagination -->
					<div class="d-flex justify-content-between align-items-center mt-2">
						<div>
							Show
							<select id="rowsPerPage" class="form-select form-select-sm d-inline-block" style="width:90px">
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="all">All</option>
							</select>
							entries
						</div>

						<div>
							<button class="btn btn-sm btn-secondary me-2" id="prevPage">Prev</button>
							<span id="pageInfo"></span>
							<button class="btn btn-sm btn-secondary ms-2" id="nextPage">Next</button>
						</div>
					</div>
					
					<!-- SUMMARY -->
					<div class="row mt-4 trialSummary">
						<div class="col-md-4 offset-md-8">
							<table class="table table-bordered">
								<tbody>									
									<tr>
										<th>Total Opening Debit</th>
										<td class="summary-opening-dr">₹ 0.00</td>
									</tr>
									<tr>
										<th>Total Opening Credit</th>
										<td class="summary-opening-cr">₹ 0.00</td>
									</tr>
									<tr>
										<th>Total Closing Debit</th>
										<td class="summary-closing-dr">₹ 0.00</td>
									</tr>
									<tr>
										<th>Total Closing Credit</th>
										<td class="summary-closing-cr">₹ 0.00</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

                    <!-- ACTION BUTTONS -->
                    <div style="text-align:right; margin-top:12px;">
                        <a href="javascript:void(0);" onclick="downloadTrialBalancePdf()" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="ti ti-download f-18"></i> Download PDF
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="openingBalanceModal" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Opening Balance Cr/Dr Required</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		  </div>
		  <div class="modal-body">
			<h6>Opening balance is zero. Please update Opening Balance in (Organization profile -> Business details) to continue.</h6>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
		  </div>
		</div>
	  </div>
	</div>

</div>

<style>
.table td {
    color: #212529 !important;
}
.table-primary-soft {
    background-color: #f1f5f9 !important;
    color: #1e293b !important;
    font-weight: 600;
}

.toggle-icon{
    width:24px;
    height:24px;
    border-radius:50%;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background:#2563eb;
    color:#fff;
    font-weight:bold;
    margin-right:8px;
}

.group-header:hover{
    opacity:.9;
}

.toggle-icon.plus{
    background:#16a34a !important;
    color:#fff !important;
}

.toggle-icon.minus{
    background:#dc2626 !important;
    color:#fff !important;
}

.asset-header{
    background:#dbeafe !important;
    color:#1e40af;
    font-weight:700;
}

.liability-header{
    background:#fee2e2 !important;
    color:#991b1b;
    font-weight:700;
}

.equity-header{
    background:#ede9fe !important;
    color:#5b21b6;
    font-weight:700;
}

.income-header{
    background:#dcfce7 !important;
    color:#166534;
    font-weight:700;
}

.expense-header{
    background:#fef3c7 !important;
    color:#92400e;
    font-weight:700;
}

.sub-group{
    background:#f8fafc;
    font-weight:600;
}

.group-header td{
    font-size:15px;
    padding:12px !important;
}

.group-row td{
    padding:8px 10px;
}

.group-row:hover{
    background:#f9fafb;
}

tfoot tr{
    background:#1f2937;
    color:#fff;
    font-size:15px;
    font-weight:bold;
}

tfoot td{
    border-color:#374151 !important;
}

</style>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<!-- JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>

	var ledgerList = @json($ledgers);

	$("#ledger_name").autocomplete({
		source: function(request, response) {

			var results = $.ui.autocomplete.filter(ledgerList, request.term);

			response(results.slice(0, 10)); // show max 10 items
		},
		minLength: 1
	});

    function handleLedgerGroup() {
        const group = document.getElementById('ledgerGroup').value;
        const sub = document.getElementById('ledgerSubGroup');

        sub.innerHTML = '<option value="">Select Sub Group</option>';
        sub.disabled = false;

        const data = {
            assets: [
                'Bank Accounts','Cash-in-Hand','Trade Receivables','Other Current Assets',
                'Loans & Advances','Fixed Assets','Intangible Assets','Capital WIP',
                'Input GST (CGST / SGST / IGST)'
            ],
            liabilities: [
                'Trade Payables','Other Current Liabilities',
                'GST Payable (CGST / SGST / IGST Output)',
                'Statutory Dues Payable','Short-Term Borrowings',
                'Long-Term Borrowings','Provisions'
            ],
            income: [
                'Sales Income (Goods)','Service Income',
                'Other Operating Income','Non-operating Income'
            ],
            expenses: [
                'Cost of Goods Sold (COGS)','Direct Expenses',
                'Employee Benefit Expenses','Administrative Expenses',
                'Selling & Distribution Expenses',
                'Finance Costs','Depreciation & Amortization'
            ]
        };

        data[group]?.forEach(val => {
            sub.innerHTML += `<option value="${val}">${val}</option>`;
        });
    }
	
	function formatDateDMY(dateStr) {
		const d = new Date(dateStr);

		if (isNaN(d)) return '';

		const day   = String(d.getDate()).padStart(2, '0');
		const month = String(d.getMonth() + 1).padStart(2, '0');
		const year  = d.getFullYear();

		return `${day}-${month}-${year}`;
	}
	
	function validateLedgerForm() 
	{
		let isValid = true;
		let msg = '';

		//let financial_year = $('#financial_year option:selected').val();
		let fromDate = $('#from_date').val();
		let toDate   = $('#to_date').val();
		let ledgerName = $('#ledger_name option:selected').val();

		if (ledgerName == "") {
			msg = 'Please select ledger name';
			isValid = false;
		} 
		else if (!fromDate) {
			msg = 'Please select From Date';
			isValid = false;
		} 
		else if (!toDate) {
			msg = 'Please select To Date';
			isValid = false;
		} 
		else if (fromDate > toDate) {
			msg = 'From Date cannot be greater than To Date';
			isValid = false;
		}

		if (!isValid) {
			alert(msg); // or toastr.error(msg)
		}

		return isValid;
	}
	
	let allRows = [];
	let currentPage = 1;
	let rowsPerPage = 'all';
	
	$('#frmTrialBalance').on('submit', function (e) {

		e.preventDefault();

		if (!validateLedgerForm()) {
			return false;
		}

		$("#loader").show();

		$.ajax({
			url: '/fatch-trial-balance-data',
			type: 'POST',
			data: $(this).serialize(),

			success: function (res) {

				$("#loader").hide();
				let html = '';

				$.each(res.trial, function (mainGroup, groups) {

					let mainId = mainGroup.replace(/[^a-zA-Z0-9]/g, '');
					let headerClass='';
					switch(mainGroup){
						case 'Assets':
							headerClass='asset-header';
							break;
						case 'Liabilities':
							headerClass='liability-header';
							break;
						case 'Equity':
							headerClass='equity-header';
							break;
						case 'Income':
							headerClass='income-header';
							break;
						case 'Expenses':
							headerClass='expense-header';
							break;
					}

					html += `
						<tr class="${headerClass} group-header"
							data-group="${mainId}"
							style="cursor:pointer;">
							<td colspan="7">
								<span class="toggle-icon"
									style="display:inline-block;
										   width:18px;
										   text-align:center;
										   border:1px solid #999;
										   border-radius:2px;
										   font-weight:bold;
										   margin-right:8px;">
									−
								</span>

								<strong>${mainGroup}</strong>
							</td>
						</tr>
					`;

					$.each(groups, function (subGroup, ledgers) {

						if (subGroup) {
							html += `
								<tr class="group-row group-${mainId}">
									<td></td>
									<td colspan="6">
										<h5>${subGroup}</h5>
									</td>
								</tr>
							`;
						}

						$.each(ledgers, function (key, row) {

							html += `
								<tr class="group-row group-${mainId}">
									<td></td>

									<td style="padding-left:35px;">
										${row.ledgername ?? row.ledger}
									</td>

									<td class="text-end">${format(row.opening_dr)}</td>
									<td class="text-end">${format(row.opening_cr)}</td>

									<td class="text-end">${format(row.closing_dr)}</td>
									<td class="text-end">${format(row.closing_cr)}</td>

									<td></td>
								</tr>
							`;
						});

					});

				});

				$("#trialBodyData").html(html);
				
				allRows = $("#trialBodyData tr");
				currentPage = 1;
				renderPagination();

				// Use totals from API
				$("#totalDr").text(format(res.closing_dr));
				$("#totalCr").text(format(res.closing_cr));
				
				$(".summary-opening-dr").text("₹ " + format(res.opening_dr));
				$(".summary-opening-cr").text("₹ " + format(res.opening_cr));
				$(".summary-closing-dr").text("₹ " + format(res.closing_dr));
				$(".summary-closing-cr").text("₹ " + format(res.closing_cr));

				// Collapse all groups
				$(".group-row").hide();
				$(".toggle-icon").text("+");

				// Expand first group
				let firstHeader = $(".group-header").first();

				if (firstHeader.length) {
					let groupId = firstHeader.data("group");
					$(".group-" + groupId).show();
					firstHeader.find(".toggle-icon").text("−");
				}
			},

			error: function () {

				$("#loader").hide();

				alert("Unable to load Trial Balance.");
			}
		});

	});
	
	function renderPagination(){

		if(rowsPerPage==="all"){

			allRows.show();

			$("#pageInfo").text("");

			return;
		}

		let start=(currentPage-1)*rowsPerPage;
		let end=start+parseInt(rowsPerPage);

		allRows.hide();

		allRows.slice(start,end).show();

		let totalPages=Math.ceil(allRows.length/rowsPerPage);

		$("#pageInfo").text(currentPage+" / "+totalPages);

		$("#prevPage").prop("disabled",currentPage==1);

		$("#nextPage").prop("disabled",currentPage>=totalPages);
	}
	
	$("#rowsPerPage").change(function(){

		rowsPerPage=$(this).val();

		currentPage=1;

		renderPagination();

	});
	
	$("#prevPage").click(function(){

		if(currentPage>1){

			currentPage--;

			renderPagination();

		}

	});
	
	$("#nextPage").click(function(){

		if(rowsPerPage==="all") return;

		let totalPages=Math.ceil(allRows.length/rowsPerPage);

		if(currentPage<totalPages){

			currentPage++;

			renderPagination();

		}

	});

	
	function formatINR(amount) {
		return Number(amount).toLocaleString('en-IN');
	}
	function format(val) {
		return parseFloat(val || 0).toLocaleString('en-IN', {
			minimumFractionDigits: 2
		});
	}
	function formatText(str) {
		return str
			.replace(/[_-]+/g, ' ')          // replace _ and - with space
			.toLowerCase()                   // optional: normalize case
			.replace(/\b\w/g, char => char.toUpperCase()); // capitalize first letter of each word
	}
	
	function getReportingType(group) {
		if (group === 'Asset' || group === 'Liability') {
			return 'BS';
		}
		if (group === 'Income' || group === 'Expense') {
			return 'P&L';
		}
		return '';
	}
	
	function downloadTrialBalancePdf() {
		let tableHtml = document.querySelector('.table-responsive').innerHTML;
		let summaryHtml = document.querySelector('.trialSummary').innerHTML;
		let htmlContent = tableHtml + summaryHtml;
		
		const from_date = formatDateDMY($("#from_date").val());
		const to_date   = formatDateDMY($("#to_date").val());
		const fileName = `Trial_Balance_${from_date}_to_${to_date}.pdf`;
		$("#loader").show();
		$.ajax({
			url: "{{ route('trialbalancesheet.download.pdf') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				html: htmlContent
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function (response) {
				$("#loader").hide();
				let blob = new Blob([response], { type: "application/pdf" });
				let link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = fileName;
				link.click();
			}
		});
	}
	
	// Expand / Collapse
	$(document).on("click", ".group-header", function () {

		let groupId = $(this).data("group");

		$(".group-" + groupId).toggle();

		let icon = $(this).find(".toggle-icon");

		icon.text(icon.text() === "+" ? "−" : "+");
	});
	
	// Expand All
	$(document).off('click', '#expandAllGroups').on('click', '#expandAllGroups', function () {
		$('.group-row').show();
		$('.toggle-icon').text('−');
	});

	// Collapse All
	$(document).off('click', '#collapseAllGroups').on('click', '#collapseAllGroups', function () {
		$('.group-row').hide();
		$('.toggle-icon').text('+');
	});

    function startTbTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Trial Balance Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-scale" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Trial Balance (TB) report sheet. Review opening and closing balances for all active ledger groups.</p></div>'
                },
                {
                    element: '.reconciliation-filter-card',
                    title: 'Filter Parameters',
                    intro: 'Set your dates, select ledger names, groups, subgroups, and opening balances, then generate the report.'
                },
                {
                    element: '.tb-table-card',
                    title: 'Worksheet Table',
                    intro: 'Review your ledger details, opening balances, closing balances, and reporting groups side by side.'
                },
                {
                    element: 'a[onclick="downloadTrialBalancePdf()"]',
                    title: 'Download PDF',
                    intro: 'Click here to save the generated trial balance worksheet as a PDF file.'
                }
            ],
            showBullets: true,
            showProgress: true,
            helperElementPadding: 5,
            exitOnOverlayClick: false,
            doneLabel: 'Done',
            nextLabel: 'Next',
            prevLabel: 'Prev',
            skipLabel: 'Skip'
        }).start();
    }

    $(document).ready(function() {
        $('#start-tb-tour').on('click', function(e) {
            e.preventDefault();
            startTbTour();
        });
    });
</script>

@endsection
