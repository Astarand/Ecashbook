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
                            <li class="breadcrumb-item active" aria-current="page">Balance Sheet Report</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-bs-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <i class="ti ti-help-circle f-18"></i> <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class=" row">
        <!-- [ sample-page ] start -->
         <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card bs-filter-card">
                         <div class="card-header  align-items-center justify-content-between py-3">
                            <h4 class="text-center">
                                Generate Balance Sheet Report
                            </h4>
                        </div>
                        <div class="card-body">

							<?php if($hasPreviousYearData ==0){ ?>
							<div class="col-md-12 text-end">
                                <a href="{{ route('addPreviousBalanceSheet') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add Previous</a>
							</div>
							<?php } ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- Financial Year Dropdown -->
                                    <select class="form-control w-100 mt-3" id="financial-year">
                                        <option selected disabled>Select Financial Year</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <!-- Period Type Dropdown -->
                                    <select class="form-control w-100 mt-3 d-none" id="period-type">
                                        <option selected disabled>Select Period Type</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="half-yearly">Half-Yearly</option>
                                        <option value="full-yearly">Full Yearly</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                     <!-- Dynamic Period Dropdown -->
                                    <select class="form-control w-100 mt-3 d-none" id="dynamic-period"></select>
                                </div>
                                <div class="col-lg-12">
                                    <!-- Generate Button -->
                                    <button class="btn btn-primary w-100 mt-3" id="generate-balance-sheet-data">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
         </div>
         <div class="col-md-12">
            <div class="card bs-table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" style="width: 50px;"></th>
                                    <th class="text-center" colspan="2">Particulars</th>
                                    <th class="text-center">Note No.</th>
                                    <th class="text-center">Figures as at the end of Current Reporting Period</th>
                                    <th class="text-center">Figures as at the end of Previous Reporting Period</th>
                                </tr>
                                <tr>
                                    <th colspan="5"></th>
                                    <th class="text-start"><strong>Duration: <span id="frmDate"></span></strong></th>
                                    <th class="text-start"><strong>Duration: <span id="toDate"></span></strong></th>
                                </tr>
                                <tr>
                                    <th colspan="5"></th>
                                    <th class="text-start"><strong>Amount In: ₹<span id="curr-grand-total"></span></strong></th>
                                    <th class="text-start"><strong>Amount In: ₹<span id="prev-grand-total"></span></strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Revenue Section -->
                                <tr style="border: 1px solid #ddd;">
                                    <td class="text-start"><strong>A.</strong></td>
                                    <td class="text-start" colspan="6" style="background-color: #cbecd6;"><strong>EUITY &amp; LIABILITIES</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>1.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Shareholders' Funds</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">a.</td>
                                    <td class="text-start">Share Capital</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-share-capital"></td>
                                    <td class="text-start" id="prev-share-capital"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">b.</td>
                                    <td class="text-start">Reserves and Surplus</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-reserves-surplus"></td>
                                    <td class="text-start" id="prev-reserves-surplus"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">c.</td>
                                    <td class="text-start">Current Year Profit</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-year-profit"></td>
                                    <td class="text-start" id="prev-year-profit"></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Net Worth (a+b+c) </strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-net-worth"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-net-worth"></span></strong></td>
                                </tr>

                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Non-Current Liabilities</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>

								<tr id="nonCurrent-liability-body"></tr>

                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-noncurr-liab-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-noncurr-liab-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>3.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Current Liabilities</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>

								<tr id="current-liability-body"></tr>

                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-current-liab-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-current-liab-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: greenyellow; border: 1px solid #ddd;"><strong>TOTAL EUITY AND LIABILITIES</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-eq-liab-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-eq-liab-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="7"></td>
                                </tr>
                                <tr style="border: 1px solid #ddd;">
                                    <td class="text-start"><strong>B.</strong></td>
                                    <td class="text-start" colspan="6" style="background-color: #cbecd6;"><strong>ASSETS</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>1.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Non-Current Assets</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>

                                <tr id="non-current-asset-body"></tr>

                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-nonassets-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-nonassets-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Current Assets</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>

								<tr id="current-asset-body"></tr>


                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-assets-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-assets-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: greenyellow; border: 1px solid #ddd;"><strong>TOTAL ASSETS</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-totalAssets"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-totalAssets"></span></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-end mt-4">
                        <button type="button" id="" onclick="printBankReport()" class="btn btn-secondary me-2">Print</button>
                        <a href="javascript:void(0);" onclick="downloadBalanceSheetPdf(0)" class="btn btn-primary">Download</a>
						<a href="javascript:void(0)"
						   class="btn btn-success"
						   onclick="downloadBalanceSheetPdf(1)">
							Summary PDF
						</a>
                    </div>
                </div>
            </div>
         </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>

<script>
  function toggleQuarterSelect() {
    const reportType = document.getElementById('reportType').value;
    const quarterSelect = document.getElementById('quarterSelect');
    if (reportType === 'Quarterly') {
      quarterSelect.disabled = false; // Enable the Quarter select
    } else {
      quarterSelect.disabled = true;  // Disable the Quarter select
      quarterSelect.selectedIndex = 0; // Reset the selection
    }
  }

  function printBankReport() {
		let printContents = document.querySelector('.table-responsive').innerHTML;

		let originalContents = document.body.innerHTML;

		document.body.innerHTML = `
			<h3 style="text-align:center;">Bank Reconciliation Report</h3>
			${printContents}
		`;

		window.print();
		document.body.innerHTML = originalContents;
		location.reload();
	}

	function downloadBalanceSheetPdf(details) {
		let tableHtml = document.querySelector('.table-responsive').innerHTML;
		$.ajax({
			url: "{{ route('balancesheet.download.pdf') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				financial_year:$('#financial-year').val(),
				period_type:$('#period-type').val(),
				dynamic_period:$('#dynamic-period').val(),
				details:details,
				html: tableHtml
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function(response) {

				let blob = new Blob([response], {
					type: "application/pdf"
				});

				let link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);

				if(details == 1){
					link.download = "Balance_Sheet_Summary.pdf";
				}else{
					link.download = "Balance_Sheet.pdf";
				}

				link.click();
			}
		});
	}

	function toNumber(val) {
		return parseFloat(val || 0);
	}

	function sumObjectValues(obj) {
		return Object.values(obj || {}).reduce((sum, val) => {
			return sum + toNumber(val);
		}, 0);
	}
	function formatAmount(value) {
		return Number(value || 0).toLocaleString('en-IN', {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2
		});
	}

	function formatDate(dateString) {
		const date = new Date(dateString);

		const day = String(date.getDate()).padStart(2, '0');
		const month = String(date.getMonth() + 1).padStart(2, '0');
		const year = date.getFullYear();

		return `${day}-${month}-${year}`;
	}
	function money(val) {
		val = parseFloat(val || 0);
		return val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}


  $(document).ready(function () {
            $('#generate-balance-sheet-data').on('click', function () {
                const financialYear = $('#financial-year').val();
                const periodType = $('#period-type').val();
                const dynamicPeriod = $('#dynamic-period').val();

                if (!financialYear) {
                    alert('Please select a financial year.');
                    return;
                }

                if (!periodType) {
                    alert('Please select a period type.');
                    return;
                }

                if (periodType !== 'full-yearly' && !dynamicPeriod) {
                    alert('Please select a period.');
                    return;
                }
				$("#loader").show();
                $.ajax({
                    url: '/fetch_balance_sheet_data',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        financial_year: financialYear,
                        period_type: periodType,
                        dynamic_period: dynamicPeriod
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
						$("#loader").hide();
                        if (data.success)
						{

							/* ---------- Share Capital ---------- */
							//const prev = data.previousYearData || {};
							//const curr = data.currentYearData || {};

							/* ================= PREVIOUS YEAR ================= */
							const prev = data.previousYearData || {};

							const prevEquityTotal  = sumObjectValues(prev.equity);

							document.getElementById('prev-net-worth').innerText = formatAmount(prevEquityTotal);
							document.getElementById('prev-eq-liab-total').innerText = formatAmount(prev.equityLiabilityTotal);

							document.getElementById('prev-grand-total').innerText = formatAmount(prev.grandTotal);

							/* ================= CURRENT YEAR ================= */
							const curr = data.currentYearData || {};

							const currEquityTotal  = sumObjectValues(curr.equity);

							document.getElementById('curr-net-worth').innerText = formatAmount(currEquityTotal);
							document.getElementById('curr-eq-liab-total').innerText = formatAmount(curr.equityLiabilityTotal);

							document.getElementById('curr-grand-total').innerText = formatAmount(curr.grandTotal);

							//start currentYearData
							 $('#frmDate').text(formatDate(data.start_date));
							 $('#toDate').text(formatDate(data.end_date));
							 $('#curr-share-capital').text(formatAmount(curr.equity?.share_capital ?? 0));

							/* ---------- Reserves and Surplus ---------- */
							$('#curr-reserves-surplus').text(formatAmount(curr.equity?.reserves_surplus ?? 0));
							$('#curr-year-profit').text(formatAmount(curr.equity?.current_year_profit ?? 0));

							/* ----------Non-current liabilities ---------- */
							let alpha = 'abcdefghijklmnopqrstuvwxyz';
							let rows = '';
							let currData = curr.nonCurrentLiabilities || {};
							let prevData = prev.nonCurrentLiabilities || {};

							$('.non-current-row').remove();

							let i = 0;

							for (let key in currData) {

								let currVal = parseFloat(currData[key] || 0);
								let prevVal = parseFloat(prevData[key] || 0);

								rows += `
									<tr class="non-current-row">
										<td></td>
										<td class="text-center">${alpha[i]}.</td>
										<td>${key}</td>
										<td></td>
										<td></td>
										<td>${money(currVal)}</td>
										<td>${money(prevVal)}</td>
									</tr>
								`;

								i++;
							}

							$('#nonCurrent-liability-body').after(rows);

							// totals
							$('#curr-noncurr-liab-total').text(money(curr.totalNonCurrent || 0));
							$('#prev-noncurr-liab-total').text(money(prev.totalNonCurrent || 0));

							/* ----------current liabilities ---------- */
							let rows2 = '';
							let curr2 = curr.currentLiabilities || {};
							let prev2 = prev.currentLiabilities || {};

							$('.current-liab-row').remove();

							let j = 0;

							let labelMap = {
								trade_payables: "Trade Payables",
								advance_from_customer: "Advance from Customer",
								outstanding_expenses: "Outstanding Expenses",
								salary_payable: "Salary Payable",
								gst_payable: "GST Payable",
								tds_payable: "TDS Payable",
								pf_payable: "PF Payable",
								esi_payable: "ESI Payable",
								short_term_loans: "Short-term Loans",
								interest_payable: "Interest Payable",
							};

							for (let key in labelMap) {

								let currVal = parseFloat(curr2[key] || 0);
								let prevVal = parseFloat(prev2[key] || 0);

								rows2 += `
									<tr class="current-liab-row">
										<td></td>
										<td class="text-center">${alpha[j]}.</td>
										<td>${labelMap[key]}</td>
										<td></td>
										<td></td>
										<td>${money(currVal)}</td>
										<td>${money(prevVal)}</td>
									</tr>
								`;

								j++;
							}

							$('#current-liability-body').after(rows2);

							// totals
							$('#curr-current-liab-total').text(money(curr.currentLiabilityTotal || 0));
							$('#prev-current-liab-total').text(money(prev.currentLiabilityTotal || 0));

							/* ---------- Total Equity and Liabilities ---------- */

							$('#curr-total-equity-liabilities').text(formatAmount(curr.total_equity_and_liabilities));

							/* ---------- Non-Current ASSETS ---------- */
							let assetRows = '';
							$('.non-current-asset-row').remove();
							let currAssets = curr.nonCurrAssets || {};
							let prevAssets = prev.nonCurrAssets || {};

							let assetMap = {};

							Object.keys(currAssets).forEach(key => {
								assetMap[key] = {
									curr: parseFloat(currAssets[key] || 0),
									prev: 0
								};
							});

							Object.keys(prevAssets).forEach(key => {
								if (!assetMap[key]) {
									assetMap[key] = { curr: 0, prev: 0 };
								}
								assetMap[key].prev = parseFloat(prevAssets[key] || 0);
							});

							let k = 0;

							Object.keys(assetMap).forEach(key => {

								assetRows += `
									<tr class="non-current-asset-row">
										<td></td>
										<td class="text-center">${alpha[k]}.</td>
										<td class="text-start">${key}</td>
										<td></td>
										<td></td>
										<td>${money(assetMap[key].curr)}</td>
										<td>${money(assetMap[key].prev)}</td>
									</tr>
								`;
								k++;
							});

							$('#non-current-asset-body').after(assetRows);
							$('#curr-nonassets-total').text(money(curr.totalNonCurrentAssets));
							$('#prev-nonassets-total').text(money(prev.totalNonCurrentAssets));

							/* ---------- Current Assets ---------- */
							let currAssetRows = '';
							let n = 0;

							$('.current-asset-row').remove();

							let currData2 = curr.currAssets || {};
							let prevData2 = prev.currAssets || {};

							Object.keys(currData2).forEach(type => {

								let currVal = parseFloat(currData2[type] || 0);
								let prevVal = parseFloat(prevData2[type] || 0);

								currAssetRows += `
									<tr class="current-asset-row">
										<td></td>
										<td class="text-center">${alpha[n]}.</td>
										<td class="text-start">${type}</td>
										<td></td>
										<td></td>
										<td>${money(currVal)}</td>
										<td>${money(prevVal)}</td>
									</tr>
								`;
								n++;
							});


							$('#current-asset-body').after(currAssetRows);

							/* ---------- TOTALS ---------- */
							$('#curr-assets-total').text(money(curr.totalCurrentAssets || 0));
							$('#prev-assets-total').text(money(prev.totalCurrentAssets || 0));

							/* ---------- GRAND TOTAL ---------- */
							$('#curr-totalAssets').text(money(curr.totalAssets || 0));
							$('#prev-totalAssets').text(money(prev.totalAssets || 0));

							//////////start previousYearData//////////////
							$('#prev-share-capital').text(formatAmount(prev.equity?.share_capital ?? 0));

							/* ---------- Reserves and Surplus ---------- */
							$('#prev-reserves-surplus').text(formatAmount(prev.equity?.reserves_surplus ?? 0));
							$('#prev-year-profit').text(formatAmount(prev.equity?.current_year_profit ?? 0));


							/* ---------- Total Equity and Liabilities ---------- */

							$('#prev-total-equity-liabilities').text(formatAmount(prev.total_equity_and_liabilities));


							$('#find_to_date').text(formatDate(data.end_date));
							$('#find_from_date').text(formatDate(data.start_date));

                        } else {
                            $('.income-values').text("00.00");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            });
        });

        //------------ Year select ------------

        document.addEventListener('DOMContentLoaded', function () {
            const financialYearDropdown = document.getElementById('financial-year');
            const periodTypeDropdown = document.getElementById('period-type');
            const dynamicPeriodDropdown = document.getElementById('dynamic-period');
            const generateButton = document.getElementById('generate-balance-sheet-data');

            // Populate Financial Year Dropdown
            const currentYear = new Date().getFullYear();
            const financialYears = [
                `${currentYear - 1}-${currentYear}`,
                `${currentYear}-${currentYear + 1}`,
            ];
            financialYears.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = `FY ${year}`;
                financialYearDropdown.appendChild(option);
            });

            // Show "Period Type" Dropdown when Financial Year is Selected
            financialYearDropdown.addEventListener('change', function () {
                periodTypeDropdown.classList.remove('d-none'); // Show Period Type Dropdown
                dynamicPeriodDropdown.classList.add('d-none'); // Reset and hide Dynamic Period Dropdown
            });

            // Handle Period Type Selection
            periodTypeDropdown.addEventListener('change', function () {
                const selectedType = this.value;

                // Reset and hide Dynamic Period Dropdown
                dynamicPeriodDropdown.innerHTML = '';
                dynamicPeriodDropdown.classList.add('d-none');

                if (selectedType === 'monthly') {
                    // Populate Monthly Periods
                    const months = [
                        'April', 'May', 'June', 'July', 'August', 'September',
                        'October', 'November', 'December', 'January', 'February', 'March'
                    ];
                    months.forEach(month => {
                        const option = document.createElement('option');
                        option.value = month.toLowerCase();
                        option.textContent = month;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    dynamicPeriodDropdown.classList.remove('d-none');
                } else if (selectedType === 'quarterly') {
                    // Populate Quarterly Periods
                    const quarters = [
                        'April-June', 'July-September', 'October-December', 'January-March'
                    ];
                    quarters.forEach(quarter => {
                        const option = document.createElement('option');
                        option.value = quarter.toLowerCase().replace(/\s/g, '-');
                        option.textContent = quarter;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    dynamicPeriodDropdown.classList.remove('d-none');
                } else if (selectedType === 'half-yearly') {
                    // Populate Half-Yearly Periods
                    const halfYearly = ['April-September', 'October-March'];
                    halfYearly.forEach(period => {
                        const option = document.createElement('option');
                        option.value = period.toLowerCase().replace(/\s/g, '-');
                        option.textContent = period;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    dynamicPeriodDropdown.classList.remove('d-none');
                } else if (selectedType === 'full-yearly') {
                    // Hide Dynamic Dropdown for Full Yearly
                    dynamicPeriodDropdown.classList.add('d-none');
                }
            });

            // Ensure Generate Button is Always Visible
            if (generateButton) {
                generateButton.classList.remove('d-none');
            }
        });

    function startBsTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Balance Sheet Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(66, 47, 144, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #422f90;"><i class="ti ti-book" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Balance Sheet Statement generator. Track assets, equity, and liabilities for your business.</p></div>'
                },
                {
                    element: '.bs-filter-card',
                    title: 'Filter Period',
                    intro: 'Configure Financial Year and Period type to retrieve specific balance sheet details.'
                },
                {
                    element: '.bs-table-card',
                    title: 'Balance Sheet Worksheet',
                    intro: 'Review your total equity, non-current and current liabilities, non-current assets, and current assets side-by-side.'
                },
                {
                    element: 'button[onclick="printBankReport()"]',
                    title: 'Print Report',
                    intro: 'Click here to print the generated statement.'
                },
                {
                    element: 'a[onclick="downloadBalanceSheetPdf()"]',
                    title: 'Download PDF',
                    intro: 'Click here to download the statement as a PDF file.'
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
        $('#start-bs-tour').on('click', function(e) {
            e.preventDefault();
            startBsTour();
        });
    });
</script>

@endsection
