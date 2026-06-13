@extends('App.Layout')

@section('container')

<div class="pc-content">

<!-- [ breadcrumb ] start -->
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <ul class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Financial Reports</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cash Flow Statement</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-cf-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Cash Flow Statement</h2>
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
            <div class="card mb-4 cf-filter-card">
                <div class="card-header py-3">
                    <h4 class="text-center mb-0">Generate Cashflow Statement</h4>
                </div>

                <div class="card-body">
                    <form method="POST" name="frmCashFlow" id="frmCashFlow" action="javascript:void(0);">
                        <div class="row g-3">

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

                            <!-- FROM DATE -->
                            <div class="col-md-3">
                                <label class="form-label">From Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="from_date" id="from_date" required>
                            </div>

                            <!-- TO DATE -->
                            <div class="col-md-3">
                                <label class="form-label">To Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="to_date" id="to_date" required>
                            </div>

                            <!-- COMPANY NAME (READ ONLY) -->
                            <!--<div class="col-md-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" value="ABC Pvt Ltd" readonly>
                            </div>-->

                            <!-- CASHFLOW TYPE -->
                            <div class="col-md-3">
                                <label class="form-label">Cashflow Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="cashflow_type" id="cashflow_type" required>
                                    <option value="">Select Cashflow Type</option>
                                    <option value="all">All</option>
                                    <option value="operating">Operating Activities</option>
                                    <option value="investing">Investing Activities</option>
                                    <option value="financing">Financing Activities</option>
                                </select>
                            </div>

                            <!-- VOUCHER TYPE -->
                            <div class="col-md-3">
                                <label class="form-label">Voucher Type</label>
                                <select class="form-select" name="voucher_type" id="voucher_type">
                                    <option value="all">All</option>
                                    <option value="receipt">Receipt</option>
                                    <option value="payment">Payment</option>
                                    <option value="contra">Contra (Cash + Banking)</option>
                                </select>
                            </div>

                            <!-- MODE OF PAYMENT -->
                            <div class="col-md-3">
                                <label class="form-label">Mode of Payment</label>
                                <select class="form-select" name="payment_mode" id="payment_mode">
                                    <option value="all">All</option>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="upi">UPI / QR</option>
                                    <option value="card">Card / POS</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="cash">Cash</option>
                                    <option value="payment_gateway">Payment Gateway</option>
                                    <option value="international_transfer">International Transfer</option>
                                </select>
                            </div>

                            <!-- OPENING CASH & BANK BALANCE -->
                            <div class="col-md-3">
                                <label class="form-label">Opening Cash & Bank Balance (₹)</label>
                                <input type="number" step="0.01" readonly value="{{ $openingBalance }}" class="form-control" name="opening_balance" id="opening_balance">
                            </div>

                            <!-- GENERATE BUTTON -->
                            <div class="col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    Generate Cashflow Statement
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- CASHFLOW TABLE -->
            <div class="card cf-table-card">
                <div class="card-body">
                    <div class="table-responsive">

                        <table  id="cashFlowTable" class="table table-bordered table-sm text-nowrap"
                               style="font-size:13px; vertical-align:middle;">

                            <thead>
                                <tr style="background:#ffff00; font-weight:700; text-align:center;">
                                    <th style="border:1px solid #000;">Date</th>
                                    <th style="border:1px solid #000;">Particulars</th>
                                    <th style="border:1px solid #000;">Voucher / Invoice No</th>
                                    <th style="border:1px solid #000;">Voucher Type</th>
                                    <th style="border:1px solid #000;">Cashflow Type</th>
                                    <th style="border:1px solid #000;">Mode of Payment</th>
                                    <th style="border:1px solid #000;">Cash / Bank Ledger</th>
                                    <th style="border:1px solid #000;">Cash Inflow (₹)</th>
                                    <th style="border:1px solid #000;">Cash Outflow (₹)</th>
                                    <th style="border:1px solid #000;">Net Cash Flow (₹)</th>
                                </tr>
                            </thead>

                            <tbody id="cashFlowData">
                                <tr>
                                    <td colspan="10"
                                        style="text-align:center; color:#6c757d; padding:20px;">
                                        No cashflow data available
                                    </td>
                                </tr>
                            </tbody>

                            <!-- CLOSING ROW -->
                            <tfoot>
                                <tr style="font-weight:700;">
                                    <td colspan="6"></td>
                                    <td style="background:#ffff00; text-align:center; border:1px solid #000;">
                                        Closing Cashflow Report
                                    </td>
                                    <td class="cashInFlow" style="background:#e6b8b7; border:1px solid #000; text-align:right;">
                                        ₹ 0.00
                                    </td>
                                    <td class="cashOutFlow" style="background:#c4d79b; border:1px solid #000; text-align:right;">
                                        ₹ 0.00
                                    </td>
                                    <td class="netCashFlow" style="background:#e6b8b7; border:1px solid #000; text-align:right;">
                                        ₹ 0.00
                                    </td>
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
                    <!-- ACTION BUTTONS -->
                    <div style="text-align:right; margin-top:12px;">
                        <button class="btn btn-primary btn-sm" onclick="exportCashFlowToExcel()">Download</button>
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
			<h6>Opening balance is zero. Please update Opening Balance in (Cash & Banking -> Cash Management) to continue.</h6>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
		  </div>
		</div>
	  </div>
	</div>

</div>



<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>

	function exportCashFlowToExcel() 
	{

		const from_date = formatDateDMY($("#from_date").val());
		const to_date   = formatDateDMY($("#to_date").val());

		const cashFlowTable = document.getElementById("cashFlowTable");

		if (!cashFlowTable) {
			alert("Cash Flow table not found!");
			return;
		}

		/* ------------------------------------
		   Convert table to worksheet
		------------------------------------ */
		const sheet = XLSX.utils.table_to_sheet(cashFlowTable);

		/* ------------------------------------
		   Apply YELLOW header background
		------------------------------------ */
		const headerStyle = {
			fill: {
				patternType: "solid",
				fgColor: { rgb: "FFFF00" }
			},
			font: { bold: true },
			alignment: { horizontal: "center" }
		};

		const range = XLSX.utils.decode_range(sheet['!ref']);

		// Header row = first row (row 0)
		for (let C = range.s.c; C <= range.e.c; ++C) {

			const cellAddr = XLSX.utils.encode_cell({ r: 0, c: C });

			if (!sheet[cellAddr]) {
				sheet[cellAddr] = { t: 's', v: '' };
			}

			sheet[cellAddr].s = headerStyle;
		}

		/* ------------------------------------
		   Auto column width
		------------------------------------ */
		const colWidths = [];
		for (let C = range.s.c; C <= range.e.c; ++C) {
			colWidths.push({ wch: 18 });
		}
		sheet['!cols'] = colWidths;

		/* ------------------------------------
		   Create & Download Excel
		------------------------------------ */
		const workbook = XLSX.utils.book_new();
		XLSX.utils.book_append_sheet(workbook, sheet, "Cash Flow");

		const fileName = `CashFlow_${from_date}_to_${to_date}.xlsx`;

		XLSX.writeFile(workbook, fileName, {
			bookType: "xlsx",
			cellStyles: true
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
	
	function validateCashFlowForm() {
		let isValid = true;
		let msg = '';

		//let financial_year = $('#financial_year option:selected').val();
		let opening_balance = $('#opening_balance').val();
		let fromDate = $('#from_date').val();
		let toDate   = $('#to_date').val();
		let cashflow_type = $('#cashflow_type option:selected').val();
		let voucher_type = $('#voucher_type option:selected').val();
		let payment_mode = $('#payment_mode option:selected').val();

		opening_balance = parseFloat(opening_balance || 0);
		if (cashflow_type == "") {
			msg = 'Please select cashflow type';
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
		else if (opening_balance === 0) {
			// show modal & block submit
			$('#openingBalanceModal').modal('show');
			return false;
		}

		if (!isValid) {
			alert(msg); // or toastr.error(msg)
		}

		return isValid;
	}
	
	let cashFlowRows = [];
	let currentPage = 1;
	let rowsPerPage = 10;

	function renderTablePage() 
	{
		let start = 0;
		let end   = cashFlowRows.length;

		if (rowsPerPage !== 'all') {
			start = (currentPage - 1) * rowsPerPage;
			end   = start + rowsPerPage;
		}

		let html = '';

		cashFlowRows.slice(start, end).forEach(r => {
			html += `
			<tr>
				<td>${formatDateDMY(r.date)}</td>
				<td>${formatText(r.particulars)}</td>
				<td>${r.voucher ?? '-'}</td>
				<td>${r.voucher_type}</td>
				<td>${r.cashflow_type}</td>
				<td>${r.mode}</td>
				<td>${r.ledger}</td>
				<td>${formatINR(r.inflow)}</td>
				<td>${formatINR(r.outflow)}</td>
				<td>${formatINR(r.balance)}</td>
			</tr>`;
		});

		$('#cashFlowData').html(
			html || `<tr><td colspan="15" class="text-center">No Data</td></tr>`
		);

		const totalPages = rowsPerPage === 'all'
			? 1
			: Math.ceil(cashFlowRows.length / rowsPerPage);

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
		const totalPages = Math.ceil(cashFlowRows.length / rowsPerPage);
		if (currentPage < totalPages) {
			currentPage++;
			renderTablePage();
		}
	});


	
	$('#frmCashFlow').on('submit', function(e) {
		e.preventDefault();
		let openingBalance = $('#opening_balance').val();
		openingBalance = parseFloat(openingBalance || 0);
		if (!validateCashFlowForm()) {
			return false;
		}
		$("#loader").show();
		$.ajax({
			url: '/cashflow/ajax',
			type: 'POST',
			data: $(this).serialize(),
			success: function(res) {
				$("#loader").hide();
				cashFlowRows = res.rows || [];
				currentPage = 1;
				renderTablePage();

				const totalIn  = parseFloat(res.total_in  || 0);
				const totalOut = parseFloat(res.total_out || 0);

				const netCashFlow = totalIn - totalOut;

				$('.cashInFlow').text(`₹ ${formatINR(totalIn)}`);
				$('.cashOutFlow').text(`₹ ${formatINR(totalOut)}`);

				// Net Cash Flow (with sign handling)
				if (netCashFlow < 0) {
					$('.netCashFlow')
						.text(`₹ ${formatINR(netCashFlow)}`)
						.addClass('text-danger')
						.removeClass('text-black');
				} else {
					$('.netCashFlow')
						.text(`₹ ${formatINR(netCashFlow)}`)
						.addClass('text-black')
						.removeClass('text-danger');
				}
			}
		});
	});
	
	function formatINR(amount) {
		return Number(amount).toLocaleString('en-IN');
	}
	
	function formatText(str) {
		return str
			.replace(/[_-]+/g, ' ')          // replace _ and - with space
			.toLowerCase()                   // optional: normalize case
			.replace(/\b\w/g, char => char.toUpperCase()); // capitalize first letter of each word
	}

	function startCfTour() {
		if (typeof introJs !== 'function') return;

		introJs().setOptions({
			steps: [
				{
					title: 'Cash Flow Guide',
					intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-activity" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Cash Flow Statement generator. Monitor cash inflows and outflows categorized by activities.</p></div>'
				},
				{
					element: '.cf-filter-card',
					title: 'Filter Period & Options',
					intro: 'Select dates, activity type (Operating, Investing, Financing), and payment mode to generate the statement.'
				},
				{
					element: '.cf-table-card',
					title: 'Statement Table',
					intro: 'View all ledger flows, inflow, outflow, and net cash flow details.'
				},
				{
					element: 'button[onclick="exportCashFlowToExcel()"]',
					title: 'Download Excel',
					intro: 'Click here to export the generated Cash Flow report directly to Excel.'
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
		$('#start-cf-tour').on('click', function(e) {
			e.preventDefault();
			startCfTour();
		});
	});

</script>
@endsection