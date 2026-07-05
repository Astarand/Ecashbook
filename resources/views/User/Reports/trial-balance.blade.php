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
                            <div class="col-md-3">
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
                            </div>
							
							<!-- LEDGER GROUP -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Ledger Group</label>
                                <select class="form-select" name="ledger_group" id="ledgerGroup">
                                    <option value="">Select Group</option>
                                    <option value="assets">Assets</option>
                                    <option value="liabilities">Liabilities</option>
                                    <option value="income">Income</option>
                                    <option value="expenses">Expenses</option>
                                </select>
                            </div>

                            <!-- LEDGER SUB GROUP -->
                            <!--<div class="col-md-3">
                                <label class="form-label fw-semibold text-muted">Ledger Sub Group</label>
                                <select class="form-select" name="ledger_sub_group" id="ledgerSubGroup" disabled>
                                    <option value="">Select Sub Group</option>
                                </select>
                            </div>-->
							
							<!-- OPENING BALANCE -->
							<div class="col-md-3">
								<label class="form-label fw-semibold text-muted">Opening Balance (Dr.)</label>
								<input type="number" step="0.01" value="{{ $openingDr }}" class="form-control" name="opening_balance_dr" id="opening_balance_dr">
							</div>
							<div class="col-md-3">
								<label class="form-label fw-semibold text-muted">Opening Balance (Cr.)</label>
								<input type="number" step="0.01" value="{{ $openingCr }}" class="form-control" name="opening_balance_cr" id="opening_balance_cr">
							</div>
                        </div>
                        <div class="row g-3 mt-1">
                            <!-- GENERATE BUTTON -->
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" style="height: 41px;">
                                    <i class="ti ti-settings f-18"></i> Generate Trial Balance Report
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
                                    <th colspan="3"
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
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Sub Group</th>

                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Opening Dr (₹)</th>
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Opening Cr (₹)</th>

                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Closing Dr (₹)</th>
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Closing Cr (₹)</th>

                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Schedule III Head</th>
                                    <th style="border: 1px solid #e2e8f0; color: #475569;">Report Type (BS / P&amp;L)</th>
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
    display:inline-flex !important;
    align-items:center;
    justify-content:center;
    width:22px;
    height:22px;
    border-radius:50%;
    background:#2563eb !important;
    color:#fff !important;
    font-size:18px;
    font-weight:bold;
    line-height:22px;
    text-align:center;
    margin-right:8px;
    border:1px solid #2563eb;
}

.toggle-icon.plus{
    background:#16a34a !important;
    color:#fff !important;
}

.toggle-icon.minus{
    background:#dc2626 !important;
    color:#fff !important;
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
					/*html += `
						<tr class="table-primary-soft">
							<td colspan="9"><strong>${group}</strong></td>
						</tr>
					`;*/
					let groupId = group.replace(/[^a-zA-Z0-9]/g, '');

					html += `
					<tr class="table-primary-soft group-header"
						data-group="${groupId}"
						style="cursor:pointer;">
						<td colspan="9">
							<span class="toggle-icon"
								  style="display:inline-block;width:18px;text-align:center;
										 border:1px solid #999;border-radius:2px;
										 font-weight:bold;margin-right:8px;">−</span>

							<strong>${group}</strong>
						</td>
					</tr>
					`;

					$.each(subGroups, function (subGroup, ledgers) {

						$.each(ledgers, function (ledgerName, v) {

							totalDr += parseFloat(v.closing_dr) || 0;
							totalCr += parseFloat(v.closing_cr) || 0;

							html += `
								<tr class="group-row group-${groupId}">
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
				
				// Collapse all groups,  Expand first group only
				$('.group-row').hide();
				$('.toggle-icon').text('+');
				let firstHeader = $('.group-header').first();
				if (firstHeader.length) {
					let firstGroup = firstHeader.data('group');
					$('.group-' + firstGroup).show();
					firstHeader.find('.toggle-icon').text('−');
				}	
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
	
	$(document).on('click', '.group-header', function () {

		$('.group-row').hide();

		$('.toggle-icon')
			.text('+')
			.removeClass('minus')
			.addClass('plus');

		let group = $(this).data('group');

		$('.group-' + group).show();

		$(this).find('.toggle-icon')
			.text('−')
			.removeClass('plus')
			.addClass('minus');
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
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-scale" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Trial Balance (TB) report sheet. Review opening and closing balances for all active ledger groups.</p></div>'
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
