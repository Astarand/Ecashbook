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
                            <li class="breadcrumb-item active" aria-current="page">Ledger Report</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-ledger-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Ledger Report</h2>
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
                        <i class="ti ti-filter f-20"></i> Filter Ledger Options
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
					
					<form method="POST" id="frmLedger" action="javascript:void(0);">
						<div class="row g-3">
							<!-- Company -->
							<div class="col-md-3">
								<label class="form-label fw-semibold text-muted">Company</label>
								<select name="propId" id="propId" class="form-select">
									<option value="">{{ parentCompanyName() }}</option>
									@foreach($proprietorships as $company)
										<option value="{{ $company->id }}">
											{{ $company->comp_name }}
										</option>
									@endforeach
								</select>
							</div>

							<!-- From Date -->
							<div class="col-md-3">
								<label class="form-label fw-semibold text-muted">
									From Date <span class="text-danger">*</span>
								</label>
								<input type="date"
									   class="form-control"
									   name="from_date"
									   id="from_date"
									   required>
							</div>

							<!-- To Date -->
							<div class="col-md-3">
								<label class="form-label fw-semibold text-muted">
									To Date <span class="text-danger">*</span>
								</label>
								<input type="date"
									   class="form-control"
									   name="to_date"
									   id="to_date"
									   required>
							</div>

							<!-- Ledger -->							
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
							
						</div>

						<!-- ================= Advanced Filters ================= -->

						<div class="collapse mt-4" id="advancedLedgerFilters">
							<hr>
							<h5 class="mb-3 fw-bold">
								<i class="ti ti-filter"></i>
								Advanced Filters
							</h5>
							<div class="row g-3">
								<!-- Customer -->
								<div class="col-md-3">
									<label class="form-label">Customers</label>
									<select class="form-select"
											name="custId"
											id="custId">
										<option value="">All</option>
										@foreach($customers as $c)
											<option value="{{ $c->id }}">
												{{ $c->cust_name }}
											</option>
										@endforeach
									</select>
								</div>

								<!-- Vendor -->
								<div class="col-md-3">
									<label class="form-label">Vendors</label>
									<select class="form-select"
											name="vendId"
											id="vendId">
										<option value="">All</option>
										@foreach($vendors as $v)
											<option value="{{ $v->id }}">
												{{ $v->vendor_name }}
											</option>
										@endforeach
									</select>
								</div>

								<!-- Party -->
								<div class="col-md-3">
									<label class="form-label">Party Name</label>
									<select class="form-select"
											name="party_name"
											id="party_name">
										<option value="">All</option>
										@foreach($parties as $party)
											<option value="{{ $party }}">
												{{ $party }}
											</option>
										@endforeach
									</select>
								</div>

								<!-- Ledger Group -->
								<div class="col-md-3">
									<label class="form-label">Ledger Group</label>
									<select class="form-select"
											name="ledger_group"
											id="ledgerGroup">

										<option value="">Select Group</option>
										<option value="Asset">Assets</option>
										<option value="Liability">Liabilities</option>
										<option value="Equity">Equity</option>
										<option value="Income">Income</option>
										<option value="Expense">Expenses</option>
									</select>
								</div>

								<!-- Ledger Sub Group -->
								<!--<div class="col-md-3">
									<label class="form-label">Ledger Sub Group</label>
									<select class="form-select"
											name="ledger_sub_group"
											id="ledgerSubGroup"
											disabled>
										<option value="">Select Sub Group</option>
									</select>
								</div>-->
							</div>
						</div>

						<!-- Buttons -->
						<div class="row mt-4">
							<div class="col-md-6 d-flex gap-2">
								<button type="submit"
										class="btn btn-primary flex-fill">
									<i class="ti ti-settings"></i>
									Generate Ledger Report
								</button>

								<button type="reset"
										class="btn btn-outline-secondary flex-fill">
									<i class="ti ti-refresh"></i>
									Clear Filters
								</button>
							</div>
							<div class="col-md-6">
								<button type="button"
										class="btn btn-outline-primary"
										data-bs-toggle="collapse"
										data-bs-target="#advancedLedgerFilters"
										aria-expanded="true">
									<i class="ti ti-adjustments"></i>
									Advanced Filters
								</button>
							</div>
						</div>
					</form>
                </div>
            </div>

            <!-- LEDGER TABLE -->
            <div class="card mb-4 ledger-table-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div class="card-header py-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1.05rem;">
                        <i class="ti ti-table f-20"></i> Ledger Records
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">

                        <table id="ledgerTable" class="table table-bordered table-sm text-nowrap">
                            <thead class="table-warning text-center">
                                <tr>
                                    <th>Date</th>
                                    <th>Journal No</th>
                                    <th>Voucher No</th>
                                    <th>Source</th>
                                    <th>Voucher Type</th>
                                    <th>Transaction Type</th>
									<th>Narration / Description</th>
                                    <th>Ledger Name</th>
                                    <th>Counter Ledger</th>
                                    <!--<th>CGST (₹)</th>
                                    <th>SGST (₹)</th>
                                    <th>IGST (₹)</th>
                                    <th>Bank Ledger</th>
                                    <th>Ledger Group</th>
                                    <th>Sub Group</th>-->
                                    <th>Debit (₹)</th>
                                    <th>Credit (₹)</th>                                    
                                    <th>Running Balance (₹)</th>
									<th>Payment Status</th>
									<th>Posted By</th>
                                </tr>
                            </thead>

                            <tbody id="ledgerData">
                                <tr>
                                    <td colspan="16" class="text-center text-muted">
                                        No ledger data available
                                    </td>
                                </tr>
                            </tbody>
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
					<div class="row mt-4">
						<div class="col-md-4 offset-md-8">
							<table id="ledgerSummary" class="table table-bordered">
								<tbody>
									<tr>
										<th width="55%">Opening Balance</th>
										<td class="summary-opening">₹ 0.00</td>
									</tr>
									<tr>
										<th>Total Debit</th>
										<td class="summary-dr">₹ 0.00</td>
									</tr>
									<tr>
										<th>Total Credit</th>
										<td class="summary-cr">₹ 0.00</td>
									</tr>
									<tr class="table-primary fw-bold">
										<th>Closing Balance</th>
										<td class="summary-closing">₹ 0.00</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

                    <!-- ACTION BUTTONS -->
                    <div class="text-end mt-3">
                        <button class="btn btn-primary d-inline-flex align-items-center gap-2" onclick="exportLedgerToExcel()">
                            <i class="ti ti-download f-18"></i> Download Excel
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
	
	<div class="modal fade" id="openingBalanceModal" tabindex="-1">
	  <div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title">Opening Balance Required</h5>
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

	function exportLedgerToExcel() {

		const from_date = formatDateDMY($("#from_date").val());
		const to_date   = formatDateDMY($("#to_date").val());

		const ledgerTable  = document.getElementById("ledgerTable");
		const summaryTable = document.getElementById("ledgerSummary");

		const ledgerSheet  = XLSX.utils.table_to_sheet(ledgerTable);
		const summarySheet = XLSX.utils.table_to_sheet(summaryTable);

		/* ------------------------------------
		  Apply YELLOW header background
		------------------------------------ */
		const headerFill = {
			fill: {
				patternType: "solid",
				fgColor: { rgb: "FFFF00" }
			},
			font: { bold: true }
		};

		const ledgerRange = XLSX.utils.decode_range(ledgerSheet['!ref']);

		for (let C = ledgerRange.s.c; C <= ledgerRange.e.c; ++C) {

			const addr = XLSX.utils.encode_cell({ r: 0, c: C });

			if (!ledgerSheet[addr]) {
				ledgerSheet[addr] = { t: 's', v: '' };
			} else {
				ledgerSheet[addr].t = 's';
			}

			ledgerSheet[addr].s = {
				fill: {
					patternType: "solid",
					fgColor: { rgb: "FFFF00" }
				},
				font: { bold: true },
				alignment: { horizontal: "center" }
			};
		}

		/* ------------------------------------
		   Place summary at bottom-right
		------------------------------------ */
		let startRow = ledgerRange.e.r + 3; // space after ledger
		let startCol = Math.max(ledgerRange.e.c - 1, 0); // right side

		const summaryRange = XLSX.utils.decode_range(summarySheet['!ref']);

		for (let R = summaryRange.s.r; R <= summaryRange.e.r; ++R) {
			for (let C = summaryRange.s.c; C <= summaryRange.e.c; ++C) {

				const cell = summarySheet[XLSX.utils.encode_cell({ r: R, c: C })];
				if (!cell) continue;

				const targetCell = XLSX.utils.encode_cell({
					r: startRow + (R - summaryRange.s.r),
					c: startCol + C
				});

				ledgerSheet[targetCell] = cell;

				// Make summary labels bold
				if (C === 0) {
					ledgerSheet[targetCell].s = {
						font: { bold: true }
					};
				}
			}
		}

		/* ------------------------------------
		   Update worksheet range
		------------------------------------ */
		ledgerSheet['!ref'] = XLSX.utils.encode_range({
			s: { r: 0, c: 0 },
			e: {
				r: startRow + (summaryRange.e.r - summaryRange.s.r),
				c: startCol + summaryRange.e.c
			}
		});

		/* ------------------------------------
		   Create & Download Excel
		------------------------------------ */
		const workbook = XLSX.utils.book_new();
		XLSX.utils.book_append_sheet(workbook, ledgerSheet, "Ledger");

		const fileName = `Ledger_${from_date}_to_${to_date}.xlsx`;
		XLSX.writeFile(workbook, fileName, {
			bookType: "xlsx",
			cellStyles: true
		});
	}




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
	
	function validateLedgerForm() {
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
	
	let ledgerRows = [];
	let currentPage = 1;
	let rowsPerPage = 10;

	function renderTablePage() 
	{
		let start = 0;
		let end   = ledgerRows.length;

		if (rowsPerPage !== 'all') {
			start = (currentPage - 1) * rowsPerPage;
			end   = start + rowsPerPage;
		}

		let html = '';

		ledgerRows.slice(start, end).forEach(r => {
			let statusBadge = '';
			switch ((r.payment_status || '').toLowerCase()) {
				case 'Full':
				case 'full':
					statusBadge = '<span class="badge bg-success">Paid</span>';
					break;

				case 'Partial':
				case 'partial':
					statusBadge = '<span class="badge bg-warning text-dark">Partial</span>';
					break;

				case 'Due':
				case 'due':
					statusBadge = '<span class="badge bg-danger">Due</span>';
					break;

				default:
					statusBadge = `<span class="badge bg-danger">${r.payment_status || 'Due'}</span>`;
			}

			html += `
			<tr>
				<td>${formatDateDMY(r.date)}</td>
				<td>${r.journal_no}</td>
				<td>${r.voucher}</td>
				<td>${r.source}</td>
				<td>${r.type}</td>
				<td>${r.transaction_details}</td>
				<td>${r.narration}</td>
				<td>${r.ledgername}</td>
				<td>${r.counter}</td>
				<!--<td>${formatINR(r.cgst)}</td>
				<td>${formatINR(r.sgst)}</td>
				<td>${formatINR(r.igst)}</td>
				<td>${r.bank}</td>
				<td>${r.group}</td>
				<td>${r.sub_group}</td>-->
				<td>${formatINR(r.debit)}</td>
				<td>${formatINR(r.credit)}</td>				
				<td>${formatINR(r.balance)}</td>
				<td>${statusBadge}</td>
				<td>${r.status}</td>
			</tr>`;
		});

		$('#ledgerData').html(
			html || `<tr><td colspan="16" class="text-center">No Data</td></tr>`
		);

		const totalPages = rowsPerPage === 'all'
			? 1
			: Math.ceil(ledgerRows.length / rowsPerPage);

		$('#pageInfo').text(`Page ${currentPage} of ${totalPages}`);

		$('#prevPage').prop('disabled', currentPage === 1 || rowsPerPage === 'all');
		$('#nextPage').prop('disabled', currentPage >= totalPages || rowsPerPage === 'all');
	}

	
	$('#rowsPerPage').on('change', function () {
		const val = $(this).val();
		rowsPerPage = (val === 'all') ? 'all' : parseInt(val);
		currentPage = 1;
		renderTablePage();
	});

	$('#prevPage').on('click', function () {
		if (currentPage > 1) {
			currentPage--;
			renderTablePage();
		}
	});

	$('#nextPage').on('click', function () {
		const totalPages = Math.ceil(ledgerRows.length / rowsPerPage);
		if (currentPage < totalPages) {
			currentPage++;
			renderTablePage();
		}
	});


	
	$('#frmLedger').on('submit', function(e) {
		e.preventDefault();
		if (!validateLedgerForm()) {
			return false;
		}
		$("#loader").show();
		$.ajax({
			url: '/ledger/ajax',
			type: 'POST',
			data: $(this).serialize(),
			success: function(res) {
				$("#loader").hide();
				ledgerRows = res.rows || [];
				currentPage = 1;
				renderTablePage();
				
				$('.summary-opening').text(`₹ ${formatINR(Math.abs(res.opening_balance))}`);
				$('.summary-dr').text(`₹ ${formatINR(res.total_debit)}`);
				$('.summary-cr').text(`₹ ${formatINR(res.total_credit)}`);
				$('.summary-closing').text(`₹ ${formatINR(res.closing)}`);
			}
		});
	});
	
	function formatINR(amount) {
		//return Number(amount).toLocaleString('en-IN');
		 const sign = amount < 0 ? '-' : '';
		return sign + Math.abs(amount).toLocaleString('en-IN');
	}

    function startLedgerTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Ledger Report Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-file-text" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Ledger Report generator. Here you can generate complete financial ledger reports for General entries, sales, purchases, banks, customers, and suppliers.</p></div>'
                },
                {
                    element: '.reconciliation-filter-card',
                    title: 'Filter Ledger Parameters',
                    intro: 'Configure date ranges, companies, party names, ledger types, groups, and opening balance.'
                },
                {
                    element: '.ledger-table-card',
                    title: 'Ledger Records Table',
                    intro: 'The generated transactions will display here. You can view narration, debit/credit details, and the running balance.'
                },
                {
                    element: '#ledgerSummary',
                    title: 'Ledger Summary',
                    intro: 'Displays closing balance, total debits, and total credits calculated from the generated report.'
                },
                {
                    element: 'button[onclick="exportLedgerToExcel()"]',
                    title: 'Download Excel',
                    intro: 'Click here to download the generated ledger report as a formatted Excel spreadsheet.'
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
        $('#start-ledger-tour').on('click', function(e) {
            e.preventDefault();
            startLedgerTour();
        });
    });
</script>

@endsection
