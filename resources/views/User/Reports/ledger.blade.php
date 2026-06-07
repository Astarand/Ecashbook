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
                        <li class="breadcrumb-item active" aria-current="page">Accounts & Ledger</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Accounts & Ledger</h2>
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
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h4 class="text-center mb-0">Generate Ledger Report</h4>
                </div>

                <div class="card-body">
                    <form method="POST" name="frmLedger" id="frmLedger" action="javascript:void(0);">
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

                            <!-- LEDGER NAME (7 TYPES) -->
                            <div class="col-md-3">
                                <label class="form-label">Ledger Name <span class="text-danger">*</span></label>
                                <select class="form-select" name="ledger_name" id="ledger_name" required>
                                    <option value="">Select Ledger</option>
                                    <option value="all">General Entries</option>
                                    <option value="customer">Customer Ledger</option>
                                    <option value="supplier">Supplier Ledger</option>
                                    <option value="sales">Sales Ledger</option>
                                    <option value="purchase">Purchase Ledger</option>
                                    <option value="bank">Bank Ledger</option>
                                    <option value="gst_output">GST Output Ledger</option>
                                    <option value="gst_input">GST Input Ledger</option>
                                </select>
                            </div>

                            <!-- LEDGER GROUP -->
                            <div class="col-md-3">
                                <label class="form-label">Ledger Group <span class="text-danger">*</span></label>
                                <select class="form-select" name="ledger_group" id="ledgerGroup" onchange="handleLedgerGroup()" >
                                    <option value="">Select Group</option>
                                    <option value="assets">Assets</option>
                                    <option value="liabilities">Liabilities</option>
                                    <option value="income">Income</option>
                                    <option value="expenses">Expenses</option>
                                </select>
                            </div>

                            <!-- LEDGER SUB GROUP -->
                            <div class="col-md-3">
                                <label class="form-label">Ledger Sub Group</label>
                                <select class="form-select" name="ledger_sub_group" id="ledgerSubGroup" disabled>
                                    <option value="">Select Sub Group</option>
                                </select>
                            </div>

                            <!-- OPENING BALANCE -->
                            <div class="col-md-3">
                                <label class="form-label">Opening Balance (₹)</label>
                                <input type="number" step="0.01" value="{{ $openingBalance }}" class="form-control" name="opening_balance"  id="opening_balance">
                            </div>

                            <!-- GENERATE BUTTON -->
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    Generate Ledger Report
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- LEDGER TABLE -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="ledgerTable" class="table table-bordered table-sm text-nowrap">
                            <thead class="table-warning text-center">
                                <tr>
                                    <th>Date</th>
                                    <th>Voucher / Invoice No</th>
                                    <th>Voucher Type</th>
                                    <th>Counter Ledger</th>
                                    <th>Narration / Description</th>
                                    <th>CGST (₹)</th>
                                    <th>SGST (₹)</th>
                                    <th>IGST (₹)</th>
                                    <th>Bank Ledger</th>
                                    <th>Ledger Group</th>
                                    <th>Sub Group</th>
                                    <th>Debit (₹)</th>
                                    <th>Credit (₹)</th>
                                    <th>Running Balance (₹)</th>
                                    <th>Dr / Cr</th>
                                </tr>
                            </thead>

                            <tbody id="ledgerData">
                                <tr>
                                    <td colspan="15" class="text-center text-muted">
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
                                <tr>
                                    <th>Closing Balance</th>
                                    <td class="summary-closing">₹ 0.00</td>
                                </tr>
                                <tr>
                                    <th>Total Debit Amount</th>
                                    <td class="summary-dr">₹ 0.00</td>
                                </tr>
                                <tr>
                                    <th>Total Credit Amount</th>
                                    <td class="summary-cr">₹ 0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="text-end mt-3">
                        <!--<button class="btn btn-secondary me-2">Print</button>-->
                        <button class="btn btn-primary" onclick="exportLedgerToExcel()">Download</button>
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

<!-- JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>

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
		let opening_balance = $('#opening_balance').val();
		let fromDate = $('#from_date').val();
		let toDate   = $('#to_date').val();
		let ledgerName = $('#ledger_name option:selected').val();

		opening_balance = parseFloat(opening_balance || 0);
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
			html += `
			<tr>
				<td>${formatDateDMY(r.date)}</td>
				<td>${r.voucher}</td>
				<td>${r.type}</td>
				<td>${r.counter}</td>
				<td>${r.narration}</td>
				<td>${formatINR(r.cgst)}</td>
				<td>${formatINR(r.sgst)}</td>
				<td>${formatINR(r.igst)}</td>
				<td>${r.bank}</td>
				<td>${r.group}</td>
				<td>${r.sub_group}</td>
				<td>${formatINR(r.debit)}</td>
				<td>${formatINR(r.credit)}</td>
				<td>${formatINR(r.balance)}</td>
				<td>${r.dc}</td>
			</tr>`;
		});

		$('#ledgerData').html(
			html || `<tr><td colspan="15" class="text-center">No Data</td></tr>`
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

				$('.summary-closing').text(`₹ ${formatINR(res.closing)}`);
				$('.summary-dr').text(`₹ ${formatINR(res.total_debit)}`);
				$('.summary-cr').text(`₹ ${formatINR(res.total_credit)}`);
			}
		});
	});
	
	function formatINR(amount) {
		//return Number(amount).toLocaleString('en-IN');
		 const sign = amount < 0 ? '-' : '';
		return sign + Math.abs(amount).toLocaleString('en-IN');
	}
</script>

@endsection
