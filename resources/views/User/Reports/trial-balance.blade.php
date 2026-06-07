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
                        <li class="breadcrumb-item active" aria-current="page">Trial Balance (TB)</li>
                    </ul>
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
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h4 class="text-center mb-0">Generate Trial Balance Report</h4>
                </div>

                <div class="card-body">
                    <form method="POST" name="frmTrialBalance" id="frmTrialBalance" action="javascript:void(0);">
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
                                    <option value="all">All</option>
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
                                <select class="form-select" name="ledger_group" id="ledgerGroup" onchange="handleLedgerGroup()">
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
                                <label class="form-label">Opening Balance (Dr.)</label>
                                <input type="number" step="0.01" value="{{ $openingDr }}" class="form-control"  name="opening_balance_dr" id="opening_balance_dr">
                            </div>
                             <div class="col-md-3">
                                <label class="form-label">Opening Balance (Cr.)</label>
                                <input type="number" step="0.01" value="{{ $openingCr }}" class="form-control"  name="opening_balance_cr" id="opening_balance_cr">
                            </div>

                            <!-- GENERATE BUTTON -->
                            <div class="col-md-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    Generate Trial Balance Report
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- LEDGER TABLE -->
            <div class="card" style="border:1px solid #dee2e6;">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-nowrap"
                            style="font-size:13px; vertical-align:middle;">

                            <!-- GROUP HEADERS -->
                            <thead>
                                <tr style="text-align:center; font-weight:700;">
                                    <th colspan="3"
                                        style="background:#ffc107; border:1px solid #000;">
                                        Ledger Details
                                    </th>

                                    <th colspan="2"
                                        style="background:#9ee7e3; border:1px solid #000;">
                                        Opening Balance
                                    </th>

                                    <th colspan="2"
                                        style="background:#ffc107; border:1px solid #000;">
                                        Closing Balance
                                    </th>

                                    <th colspan="2"
                                        style="background:#9ee7e3; border:1px solid #000;">
                                        Reporting Section
                                    </th>
                                </tr>

                                <!-- COLUMN HEADERS -->
                                <tr style="text-align:center; font-weight:600; background:#fff3cd;">
                                    <th style="border:1px solid #000;">Ledger Group</th>
                                    <th style="border:1px solid #000;">Ledger Name</th>
                                    <th style="border:1px solid #000;">Sub Group</th>

                                    <th style="border:1px solid #000;">Opening Dr (₹)</th>
                                    <th style="border:1px solid #000;">Opening Cr (₹)</th>

                                    <th style="border:1px solid #000;">Closing Dr (₹)</th>
                                    <th style="border:1px solid #000;">Closing Cr (₹)</th>

                                    <th style="border:1px solid #000;">Schedule III Head</th>
                                    <th style="border:1px solid #000;">Report Type (BS / P&amp;L)</th>
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
                                    <td colspan="5"
                                        style="text-align:right; border:1px solid #000;">
                                        Total Closing Balance
                                    </td>

                                    <td style="text-align:right; border:1px solid #000;" id="totalDr">
                                        ₹ 0.00
                                    </td>

                                    <td style="text-align:right; border:1px solid #000;" id="totalCr">
                                        ₹ 0.00
                                    </td>

                                    <td colspan="2" style="border:1px solid #000;"></td>
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
                        <a href="javascript:void(0);" onclick="downloadTrialBalancePdf()" class="btn btn-primary">Download</a>
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
    background-color: rgba(13, 110, 253, 0.18) !important;
    color: #000000 !important;
}

</style>
<!-- JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
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
		let opening_balance_dr = $('#opening_balance_dr').val();
		let opening_balance_cr = $('#opening_balance_cr').val();
		let fromDate = $('#from_date').val();
		let toDate   = $('#to_date').val();
		let ledgerName = $('#ledger_name option:selected').val();

		opening_balance_dr = parseFloat(opening_balance_dr || 0);
		opening_balance_cr = parseFloat(opening_balance_cr || 0);
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
		}else if (opening_balance_dr < 0 || opening_balance_cr < 0) {
			msg = 'Opening balance cannot be negative';
			isValid = false;
		}
		else if (opening_balance_dr === 0 || opening_balance_cr === 0) {
			// show modal & block submit
			$('#openingBalanceModal').modal('show');
			return false;
		}

		if (!isValid) {
			alert(msg); // or toastr.error(msg)
		}

		return isValid;
	}
	
	


	
	$('#frmTrialBalance').on('submit', function(e) {
		e.preventDefault();
		if (!validateLedgerForm()) {
			return false;
		}
		$("#loader").show();
		$.ajax({
			url: '/fatch-trial-balance-data',
			type: 'POST',
			data: $(this).serialize(),
			success: function(res) {
				$("#loader").hide();
				trialRows = res || [];
				currentPage = 1;
				//renderTablePage();
				
				let html = '';
				let totalDr = 0;
				let totalCr = 0;

				$.each(res.trial, function (group, subGroups) {

					/* ===== GROUP HEADER ===== */
					html += `
						<tr class="table-primary-soft">
							<td colspan="9"><strong>${group}</strong></td>
						</tr>
					`;

					$.each(subGroups, function (subGroup, ledgers) {

						$.each(ledgers, function (ledgerName, v) {

							totalDr += parseFloat(v.closing_dr) || 0;
							totalCr += parseFloat(v.closing_cr) || 0;

							html += `
								<tr>
									<td></td>
									<td>${v.ledgername}</td>
									<td>${formatText(subGroup)}</td>

									<td class="text-end">${format(v.opening_dr)}</td>
									<td class="text-end">${format(v.opening_cr)}</td>
									<td class="text-end">${format(v.closing_dr)}</td>
									<td class="text-end">${format(v.closing_cr)}</td>

									<td>${formatText(subGroup)}</td>
									<td>${getReportingType(group) }</td>
								</tr>
							`;
						});
					});
				});

				$('#trialBodyData').html(html);
				$('#totalDr').text(format(totalDr));
				$('#totalCr').text(format(totalCr));

				
			}
		});
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
		const from_date = formatDateDMY($("#from_date").val());
		const to_date   = formatDateDMY($("#to_date").val());
		const fileName = `Trial_Balance_${from_date}_to_${to_date}.pdf`;
		$("#loader").show();
		$.ajax({
			url: "{{ route('trialbalancesheet.download.pdf') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				html: tableHtml
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
</script>

@endsection
