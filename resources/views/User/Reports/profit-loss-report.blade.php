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
                            <li class="breadcrumb-item active" aria-current="page">Profit & Loss Report</li>
                        </ul>
                        <a href="javascript:void(0);" id="start-pl-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                            <u>How does this Page works?</u>
                        </a>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Profit & Loss Report</h2>
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
             <!-- FILTER CARD -->
             <div class="card mb-4 reconciliation-filter-card" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                 <div class="card-header py-3" style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                     <h5 class="mb-0 text-primary d-flex align-items-center gap-2 fw-bold" style="font-size: 1.05rem;">
                         <i class="ti ti-filter f-20"></i> Filter Profit & Loss Options
                     </h5>
                 </div>

                 <div class="card-body p-4">
                     <div class="row g-3 align-items-end">
                         <div class="col-md-3">
                             <label class="form-label fw-semibold text-muted">Financial Year</label>
                             <select class="form-select w-100" id="financial-year">
                                 <option selected disabled>Select Financial Year</option>
                             </select>
                         </div>
                         <div class="col-md-3 d-none" id="col-period-type">
                             <label class="form-label fw-semibold text-muted">Period Type</label>
                             <select class="form-select w-100" id="period-type">
                                 <option selected disabled>Select Period Type</option>
                                 <option value="monthly">Monthly</option>
                                 <option value="quarterly">Quarterly</option>
                                 <option value="half-yearly">Half-Yearly</option>
                                 <option value="full-yearly">Full Yearly</option>
                             </select>
                         </div>
                         <div class="col-md-3 d-none" id="col-dynamic-period">
                             <label class="form-label fw-semibold text-muted">Select Period</label>
                             <select class="form-select w-100" id="dynamic-period"></select>
                         </div>
                         <div class="col-md-3 ms-auto">
                             <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" id="generate-profit-loss-data" style="height: 41px;">
                                 <i class="ti ti-settings f-18"></i> Generate Report
                             </button>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="col-md-12">
            <div class="card">
                <div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered mb-0">
							<thead>
								<tr style="font-size:12px;">
									<th class="text-center">#</th>
									<th class="text-center" style="width: 50px;"></th>
									<th class="text-center" colspan="2">Particulars</th>
									<th class="text-center">Note No.</th>
									<th class="text-center">Figures as at the end of Current Reporting Period</th>
									<th class="text-center">Figures as at the end of Previous Reporting Period</th>
								</tr>
								<tr>
									<th colspan="5"></th>
									<th class="text-start"><strong>From Date: <span id="find_from_date"></span></strong></th>
									<th class="text-start"><strong>To Date: <span id="find_to_date"></span></strong></th>
								</tr>
								<tr>
									<th colspan="5"></th>
									<th class="text-start"><strong>Amount In: ₹<span id="curr_total_profit_loss">00.00</span></strong></th>
									<th class="text-start"><strong>Amount In: ₹<span id="prev_total_profit_loss">00.00</span></strong></th>
								</tr>
							</thead>
							<tbody>
								<!-- Revenue Section -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>A.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6;"><strong>REVENUE / INCOME</strong></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>1.</strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Revenue From Operations</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Sales of Products/Goods</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalReseller">00.00</td>
									<td class="text-start" id="prev_totalReseller"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start">Income from Services</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_totalService">00.00</td>
									<td class="text-start" id="prev_totalService"></td>
								</tr>
								
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Other Operating Income</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
								</tr>
								
								<tr id="operating-income-body"></tr>
								
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong> </strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="3" class="text-start" style="background-color:#ffffe6;color:#664d03;border:1px solid #ddd;"><strong>Total Operating Income</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="curr_operatingIncomeTotal">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="prev_operatingIncomeTotal">00.00</span></strong></td>
								</tr>
								
								
								<tr>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>3.</strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Other Non Operating Income</strong></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
									<td style="border: 1px solid #ddd;"></td>
								</tr>
								
								<tr id="non-operating-income-body"></tr>
								
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong> </strong></td>
									<td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
									<td colspan="3" class="text-start" style="background-color:#ffffe6;color:#664d03;border:1px solid #ddd;"><strong>Total Non Operating Income</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="curr_nonOperatingIncomeTotal">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="prev_nonOperatingIncomeTotal">00.00</span></strong></td>
								</tr>
								
								
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>4.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL REVENUE (1 + 2)</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="curr_total_sales_income">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="prev_total_sales_income">00.00</span></strong></td>
								</tr>

								<!-- Cost of Goods Sold (COGS) -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>B.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6;"><strong>Cost of Goods Sold (COGS)</strong></td>
								</tr>								
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Opening Stock</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_openingStock">00.00</td>
									<td class="text-start" id="prev_openingStock"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start">Purchases</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_purchase">00.00</td>
									<td class="text-start" id="prev_purchase"></td>
								</tr>
								
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start">Direct Expenses </td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_directExpenseTotal">00.00</td>
									<td class="text-start" id="prev_directExpenseTotal">00.00</td>
								</tr>
								
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">d.</td>
									<td class="text-start">Less: Closing stock </td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_closingStock">00.00</td>
									<td class="text-start" id="prev_closingStock"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>5.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Total Cost of Goods Sold (a+b+c) - (d)</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="curr_totalCogs">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹ <span id="prev_totalCogs">00.00</span></strong></td>
								</tr>
								
								<!-- Expenses Section -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>C.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Indirect Expense (Operating Expenses) </strong></td>
								</tr>
								
								<!--<tbody id="indirect-expenses-body"></tbody>-->
								<tr id="indirect-expenses-body"></tr>

								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>6.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Total Indirect Expense</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_total_expenses">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_total_expenses">00.00</span></strong></td>
								</tr>
								
								<!-- EBITDA Section -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>D.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Earnings Before Interest, Taxes, Depreciation, and Amortization (EBITDA)</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;"></td>
									<td class="text-start">Total Revenue -(Total Cost of Goods Sold - Indirect Expenses)</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_ebitda">0.00</td>
									<td class="text-start" id="prev_ebitda"></td>
								</tr>
								
								<!-- Depreciation and Amortization Expense -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>E.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Depreciation and Amortization Expense</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;"></td>
									<td class="text-start">Depreciation and Amortization Expense</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_depreciationExp">0.00</td>
									<td class="text-start" id="prev_depreciationExp"></td>
								</tr>
								
								<!-- EBIT -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>F.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Earnings Before Interest and Taxes (EBIT)</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;"></td>
									<td class="text-start">(EBITDA - Depreciation and Amortization Expense) ( D - E )</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_ebit">0.00</td>
									<td class="text-start" id="prev_ebit"></td>
								</tr>
								
								<!-- FINANCE COST -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>G.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>FINANCE COST</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start">Interest on Loan</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_loan">0.00</td>
									<td class="text-start" id="prev_loan"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start">Bank Charges</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_bank">0.00</td>
									<td class="text-start" id="prev_bank"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start">OD / CC Interest</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_odcc">0.00</td>
									<td class="text-start" id="prev_odcc"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">d.</td>
									<td class="text-start">Processing Charges</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_processing">0.00</td>
									<td class="text-start" id="prev_processing"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>H.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Total Finance Cost</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_total_finance_cost">00.00</span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_total_finance_cost">00.00</span></strong></td>
								</tr>
								
								<!-- Profit Before Tax (PBT)  -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>I.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Profit Before Tax (PBT)</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;"></td>
									<td class="text-start">EBIT - Finance Cost ( F - G )</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_pbt">0.00</td>
									<td class="text-start" id="prev_pbt"></td>
								</tr>
								
								
								<!-- Profit Before Tax (PBT)  -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>J.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Tax Expense</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start"> Current Tax</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_current_tax"></td>
									<td class="text-start" id="prev_current_tax"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start"> Prior Year Tax</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_current_tax_expenses_prior_years">0.00</td>
									<td class="text-start" id="prev_current_tax_expenses_prior_years"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">c.</td>
									<td class="text-start"> Deferred Tax</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_deferred_tax">0.00</td>
									<td class="text-start" id="prev_deferred_tax"></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">d.</td>
									<td class="text-start"> Minimum Alternate Tax (MAT) Credit</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_minimum_alternate_tax">0.00</td>
									<td class="text-start" id="prev_minimum_alternate_tax"></td>
								</tr>
								<tr>
									<td class="text-center" style="border: 1px solid #ddd;"><strong>7.</strong></td>
									<td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>Total Tax Expenses</strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr_totalTax"></span></strong></td>
									<td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev_totalTax"></span></strong></td>
								</tr>
								<!-- PROFIT AFTER TAX (PAT)  -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>K.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>PROFIT AFTER TAX (PAT)</strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;"></td>
									<td class="text-start">PROFIT AFTER TAX (PAT) - Tax Expenses (I-J)</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_pat">0.00</td>
									<td class="text-start" id="prev_pat"></td>
								</tr>
								
								<!-- Earnings per equity  -->
								<tr style="border: 1px solid #ddd;">
									<td class="text-start"><strong>L.</strong></td>
									<td class="text-start" colspan="6" style="background-color: #cbecd6; border: 1px solid #ddd;"><strong>Earnings per equity share : </strong></td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">a.</td>
									<td class="text-start"> Basic</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_basic">0.00</td>
									<td class="text-start" id="prev_basic">0.00</td>
								</tr>
								<tr>
									<td></td>
									<td class="text-center" style="width: 50px;">b.</td>
									<td class="text-start"> Diluted</td>
									<td></td>
									<td class="text-start"></td>
									<td class="text-start" id="curr_diluted">0.00</td>
									<td class="text-start" id="prev_diluted"></td>
								</tr>
							</tbody>
						</table>
					</div>
                    <div class="col-md-12 text-end mt-4">
                        <button type="button" id="" onclick="printPLReport()"   class="btn btn-secondary me-2">Print</button>
                        <a href="javascript:void(0);" onclick="downloadPLPdf()" class="btn btn-primary">Download</a>
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

	function printPLReport() {
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
	
	function downloadPLPdf() {
		let tableHtml = document.querySelector('.table-responsive').innerHTML;
		$.ajax({
			url: "{{ route('plsheet.download.pdf') }}",
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				html: tableHtml
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function (response) {
				let blob = new Blob([response], { type: "application/pdf" });
				let link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = "Profit_Loss_Sheet.pdf";
				link.click();
			}
		});
	}
		function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            return date.toLocaleDateString('en-GB', options); // You can adjust the locale and options as needed.
        }
		
		function money(val) {
			val = parseFloat(val || 0);
			return val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}

		function setText(id, value) {
			$('#' + id).text(money(value));
		}
		
		function calculateTotalTax(tax) {
			return (
				Number(tax.current_tax || 0) +
				Number(tax.current_tax_expenses_prior_years || 0) +
				Number(tax.deferred_tax || 0) +
				Number(tax.minimum_alternate_tax || 0)
			);
		}


		$(document).ready(function () {
            $('#generate-profit-loss-data').on('click', function () {
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
                    url: '/fetch-profit-loss-data',
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
                        if (data.success) {
                            $('#find_to_date').text(formatDate(data.end_date));
                            $('#find_from_date').text(formatDate(data.start_date));
							const curr = data.currentYearData;
							const prev = data.previousYearData;

							/* ======================
							   CURRENT YEAR – REVENUE
							====================== */
							setText('curr_totalReseller', curr.revenue.totalReseller);
							setText('curr_totalService', curr.revenue.totalService);	
							
							//Start Operating Income
							let opRows = '';
							let currOp = curr.revenue.operatingIncomeDetails || [];
							let prevOp = prev.revenue.operatingIncomeDetails || [];
							let opMap = {};

							currOp.forEach(r => {
								opMap[r.categoryIncome] = {
									curr: parseFloat(r.total || 0),
									prev: 0
								};
							});

							prevOp.forEach(r => {
								if (!opMap[r.categoryIncome]) {
									opMap[r.categoryIncome] = {
										curr: 0,
										prev: 0
									};
								}

								opMap[r.categoryIncome].prev = parseFloat(r.total || 0);
							});

							let roman = [
								'i', 'ii', 'iii', 'iv', 'v',
								'vi', 'vii', 'viii', 'ix', 'x',
								'xi', 'xii', 'xiii', 'xiv', 'xv',
								'xvi', 'xvii', 'xviii', 'xix', 'xx'
							];
							let i = 0;
							$('.operating-income-row').remove();
							Object.keys(opMap).forEach(head => {

								opRows += `
									<tr class="operating-income-row">
										<td></td>
										<td class="text-center">${roman[i] || (i + 1)}.</td>
										<td class="text-start">${head}</td>
										<td></td>
										<td></td>
										<td>${money(opMap[head].curr)}</td>
										<td>${money(opMap[head].prev)}</td>
									</tr>
								`;

								i++;
							});

							$('#operating-income-body').after(opRows);
							//End Operating Income
							
							setText('curr_operatingIncomeTotal', curr.revenue.operatingIncomeTotal);		
							setText('curr_nonOperatingIncomeTotal', curr.revenue.nonOperatingIncomeTotal);
							setText('curr_total_sales_income', curr.revenue.total_sales_income);
							
							/* ======================
							   CURRENT YEAR – Cost of Goods Sold (COGS)
							====================== */
							setText('curr_openingStock', curr.cogs.opening_stock);
							setText('curr_purchase', curr.cogs.purchases);
							
							//Start Direct Expense
							let directRows = '';
							let currDirect = curr.cogs.direct_expense || {};
							let prevDirect = prev.cogs.direct_expense || {};

							let directMap = {};

							// Current Year
							Object.keys(currDirect).forEach(head => {
								directMap[head] = {
									curr: parseFloat(currDirect[head] || 0),
									prev: 0
								};
							});

							// Previous Year
							Object.keys(prevDirect).forEach(head => {

								if (!directMap[head]) {
									directMap[head] = {
										curr: 0,
										prev: 0
									};
								}

								directMap[head].prev = parseFloat(prevDirect[head] || 0);
							});

							let d = 0;
							let currDirectTotal = 0;
							let prevDirectTotal = 0;

							$('.direct-expense-row').remove();

							Object.keys(directMap).forEach(head => {

								currDirectTotal += directMap[head].curr;
								prevDirectTotal += directMap[head].prev;

								directRows += `
									<tr class="direct-expense-row">
										<td></td>
										<td class="text-center">${d + 1}.</td>
										<td class="text-start">${head}</td>
										<td></td>
										<td></td>
										<td>${money(directMap[head].curr)}</td>
										<td>${money(directMap[head].prev)}</td>
									</tr>
								`;

								d++;
							});

							$('#direct-expense-body').after(directRows);

							$('#curr_directExpenseTotal').text(money(currDirectTotal));
							$('#prev_directExpenseTotal').text(money(prevDirectTotal));
							//End Direct Expense
							
							setText('curr_closingStock', curr.cogs.closing_stock);
							setText('curr_totalCogs', curr.cogs.total_cogs);
							
							/* ======================
							   CURRENT YEAR – EXPENSES
							====================== */
							
							let expenseRows = '';
							let alphabet = 'abcdefghijklmnopqrstuvwxyz';

							let currTotalIndirectExp = 0;
							let prevTotalIndirectExp = 0;
							let index = 0;

							// Get all unique expense heads from current and previous years
							const expenseHeads = new Set([
								...Object.keys(curr.expenses || {}),
								...Object.keys(prev.expenses || {})
							]);
							
							$('.indirect-expense-row').remove();
							
							expenseHeads.forEach(function(head) {

								let currAmount = parseFloat(curr.expenses?.[head] || 0);
								let prevAmount = parseFloat(prev.expenses?.[head] || 0);

								currTotalIndirectExp += currAmount;
								prevTotalIndirectExp += prevAmount;

								expenseRows += `
									<tr class="indirect-expense-row">
										<td></td>
										<td class="text-center">${index + 1}.</td>
										<td class="text-start">${head}</td>
										<td></td>
										<td></td>
										<td class="text-start">${money(currAmount)}</td>
										<td class="text-start">${money(prevAmount)}</td>
									</tr>
								`;

								index++;
							});

							$('#indirect-expenses-body').after(expenseRows);

							$('#curr_total_expenses').text(money(currTotalIndirectExp));
							$('#prev_total_expenses').text(money(prevTotalIndirectExp));
							
							setText('curr_ebitda', curr.ebitda);
							setText('curr_depreciationExp', curr.depreciationExp);
							setText('curr_ebit', curr.ebit);
							/* ======================
							   CURRENT YEAR – FINANCE COST
							====================== */
							setText('curr_loan', curr.finance_cost.interest_on_loan);
							setText('curr_bank', curr.finance_cost.bank_charges);
							setText('curr_odcc', curr.finance_cost.od_cc_interest);
							setText('curr_processing', curr.finance_cost.processing_charges);
							setText('curr_total_finance_cost', curr.finance_cost.total_finance_cost);
							/* ======================
							   CURRENT YEAR – PROFIT BEFORE TAX
							====================== */
							setText('curr_pbt', curr.pbt);

							
							/* ======================
							   CURRENT YEAR – TAX Expense
							====================== */
							setText('curr_current_tax', curr.tax.current_tax);
							setText('curr_current_tax_expenses_prior_years', curr.tax.current_tax_expenses_prior_years);
							setText('curr_deferred_tax', curr.tax.deferred_tax);
							setText('curr_minimum_alternate_tax', curr.tax.minimum_alternate_tax);
							setText('curr_totalTax', curr.tax.totalTax);
							/* ======================
							   CURRENT YEAR – PROFIT AFTER TAX
							====================== */
							setText('curr_pat', curr.pat);
							
							/* ======================
							   CURRENT YEAR – Earnings per equity 
							====================== */
							setText('curr_basic', curr.eps.basic_eps);
							setText('curr_diluted', curr.eps.diluted_eps);							
							setText('curr_total_profit_loss', curr.pat);
							
							
							/* ======================
							   PREVIOUS YEAR – REVENUE
							====================== */
							setText('prev_totalReseller', prev.revenue.totalReseller);
							setText('prev_totalService', prev.revenue.totalService);
							
							//Start Operating Income
							let nonRows = '';
							let currNon = curr.revenue.nonOperatingIncomeDetails || [];
							let prevNon = prev.revenue.nonOperatingIncomeDetails || [];
							let nonMap = {};

							currNon.forEach(r => {
								nonMap[r.categoryIncome] = {
									curr: parseFloat(r.total || 0),
									prev: 0
								};
							});

							prevNon.forEach(r => {
								if (!nonMap[r.categoryIncome]) {
									nonMap[r.categoryIncome] = {
										curr: 0,
										prev: 0
									};
								}

								nonMap[r.categoryIncome].prev = parseFloat(r.total || 0);
							});

							i = 0;
							$('.non-operating-income-row').remove();
							Object.keys(nonMap).forEach(head => {

								nonRows += `
									<tr class="non-operating-income-row">
										<td></td>
										<td class="text-center">${roman[i] || (i + 1)}.</td>
										<td class="text-start">${head}</td>
										<td></td>
										<td></td>
										<td>${money(nonMap[head].curr)}</td>
										<td>${money(nonMap[head].prev)}</td>
									</tr>
								`;

								i++;
							});

							$('#non-operating-income-body').after(nonRows);							
							//End Operating Income
							
							setText('curr_operatingIncomeTotal', curr.revenue.operatingIncomeTotal);		
							setText('curr_nonOperatingIncomeTotal', curr.revenue.nonOperatingIncomeTotal);
							setText('prev_total_sales_income', prev.revenue.total_sales_income);
							
							/* ======================
							   PREVIOUS YEAR – Cost of Goods Sold (COGS)
							====================== */
							setText('prev_openingStock', prev.cogs.opening_stock);
							setText('prev_purchase', prev.cogs.purchases);
							setText('prev_closingStock', prev.cogs.closing_stock);
							setText('prev_totalCogs', prev.cogs.total_cogs);
							

							/* ======================
							   PREVIOUS YEAR – EXPENSES
							====================== */
							setText('prev_ebitda', prev.ebitda);
							setText('prev_depreciationExp', prev.depreciationExp);
							setText('prev_ebit', prev.ebit);
							
							/* ======================
							   PREVIOUS YEAR – FINANCE COST
							====================== */
							setText('prev_loan', prev.finance_cost.interest_on_loan);
							setText('prev_bank', prev.finance_cost.bank_charges);
							setText('prev_odcc', prev.finance_cost.od_cc_interest);
							setText('prev_processing', prev.finance_cost.processing_charges);
							setText('prev_total_finance_cost', prev.finance_cost.total_finance_cost);
							
							/* ======================
							   PREVIOUS YEAR – PROFIT BEFORE TAX
							====================== */
							setText('prev_pbt', prev.pbt);
							
							/* ======================
							   PREVIOUS YEAR – TAX Expense
							====================== */
							setText('prev_current_tax', prev.tax.current_tax);
							setText('prev_current_tax_expenses_prior_years', prev.tax.current_tax_expenses_prior_years);
							setText('prev_deferred_tax', prev.tax.deferred_tax);
							setText('prev_minimum_alternate_tax', prev.tax.minimum_alternate_tax);
							setText('prev_totalTax', prev.tax.totalTax);
							
							/* ======================
							   PREVIOUS YEAR – PROFIT AFTER TAX
							====================== */
							setText('prev_pat', prev.pat);
                            
							/* ======================
							   PREVIOUS YEAR – Earnings per equity 
							====================== */
							setText('prev_basic', prev.eps.basic_eps);
							setText('prev_diluted', prev.eps.diluted_eps);
							setText('prev_total_profit_loss', prev.pat);
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
            const generateButton = document.getElementById('generate-balance-sheet');

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
                document.getElementById('col-period-type').classList.remove('d-none'); // Show Period Type Dropdown
                document.getElementById('col-dynamic-period').classList.add('d-none'); // Reset and hide Dynamic Period Dropdown
            });

            // Handle Period Type Selection
            periodTypeDropdown.addEventListener('change', function () {
                const selectedType = this.value;

                // Reset and hide Dynamic Period Dropdown
                dynamicPeriodDropdown.innerHTML = '';
                document.getElementById('col-dynamic-period').classList.add('d-none');

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
                    document.getElementById('col-dynamic-period').classList.remove('d-none');
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
                    document.getElementById('col-dynamic-period').classList.remove('d-none');
                } else if (selectedType === 'half-yearly') {
                    // Populate Half-Yearly Periods
                    const halfYearly = ['April-September', 'October-March'];
                    halfYearly.forEach(period => {
                        const option = document.createElement('option');
                        option.value = period.toLowerCase().replace(/\s/g, '-');
                        option.textContent = period;
                        dynamicPeriodDropdown.appendChild(option);
                    });
                    document.getElementById('col-dynamic-period').classList.remove('d-none');
                } else if (selectedType === 'full-yearly') {
                    // Hide Dynamic Dropdown for Full Yearly
                    document.getElementById('col-dynamic-period').classList.add('d-none');
                }
            });

            // Ensure Generate Button is Always Visible
            // generateButton.classList.remove('d-none');
        });

    function startPlTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Profit & Loss Report Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-presentation" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Welcome to the Profit & Loss Statement generator. Track your revenues, Cost of Goods Sold (COGS), operating expenses, and net margins.</p></div>'
                },
                {
                    element: '.reconciliation-filter-card',
                    title: 'Filter Period',
                    intro: 'Select the Financial Year and Period type (Monthly, Quarterly, etc.) to load data.'
                },
                {
                    element: '.table-responsive',
                    title: 'Profit & Loss Worksheet',
                    intro: 'This statement breaks down Revenues, Cost of Goods Sold, EBITDA, EBIT, and Profit After Tax (PAT) for current and previous periods side-by-side.'
                },
                {
                    element: 'button[onclick="printPLReport()"]',
                    title: 'Print Statement',
                    intro: 'Click here to print the currently generated Profit & Loss report.'
                },
                {
                    element: 'a[onclick="downloadPLPdf()"]',
                    title: 'Download PDF',
                    intro: 'Click here to download the report as a PDF document.'
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
        $('#start-pl-tour').on('click', function(e) {
            e.preventDefault();
            startPlTour();
        });
    });
</script>

@endsection
