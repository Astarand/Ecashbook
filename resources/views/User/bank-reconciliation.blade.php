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
                            <h2 class="mb-0">Bank Reconciliation</h2>
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
                        <div class="card">
                            <div class="card-header  align-items-center justify-content-between py-3">
                                <h4 class="text-center">
                                    Generate Bank Reconciliation Report
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="inputEmail4">Select Financial Year<span class="text-danger">*</span></label>
                                        <select class="form-select" name="financial_year" id="financial_year">
                                            <option selected>Select Financial Year</option>
                                            <option value="2021-2022">2021-2022</option>
                                            <option value="2022-2023">2022-2023</option>
                                            <option value="2023-2024">2023-2024</option>
                                            <option value="2024-2025">2024-2025</option>
                                            <option value="2025-2026">2025-2026</option>
                                            <option value="2026-2027">2026-2027</option>
                                            <option value="2027-2028">2027-2028</option>
                                            <option value="2028-2029">2028-2029</option>
                                            <option value="2029-2030">2029-2030</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="reportType">Select Report Type<span class="text-danger">*</span></label>
                                        <select class="form-select" id="reportType" onchange="toggleQuarterSelect()">
                                            <option selected>Select Report Type</option>
                                            <option value="Yearly">Yearly</option>
                                            <option value="Quarterly">Quarterly</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="quarterSelect">Select Quarter<span class="text-danger">*</span></label>
                                        <select class="form-select" id="quarterSelect" disabled>
                                            <option selected>Select Quarter</option>
                                            <option value="1">Quarter 1 (April–June)</option>
                                            <option value="2">Quarter 2 (July–September)</option>
                                            <option value="3">Quarter 3 (October–December)</option>
                                            <option value="4">Quarter 4 (January – March)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"></label>
                                        <a href="javascript:void(0);" class="btn btn-primary w-100 mt-2" onclick="fetchBankReconciliation()">Submit</a>
                                    </div>
                                </div>
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
                                    <th>Amount (₹)</th>
                                    <th>Amount (₹)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!--<tr>
                                    <td>Balance as per Cash Book (Bank Book)</td>
                                    <td>[Opening Cash]</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Add: Deposits in Transit</td>
                                    <td></td>
                                    <td>[Deposit Amount]</td>
                                </tr>
                                <tr>
                                    <td>Add: Bank Errors (if any)</td>
                                    <td></td>
                                    <td>[Bank Error]</td>
                                </tr>
                                <tr>
                                    <td>Less: Unrepresented Cheques (Outstanding)</td>
                                    <td>[Cheque Amount]</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Less : Bank Charges of Fees</td>
                                    <td>[Charges]</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Less : Error in Cash Book</td>
                                    <td>[Cash Book Error]</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Balance as per Bank Statement</td>
                                    <td></td>
                                    <td>[Closing Balance]</td>
                                </tr>
                                <tr style="background-color: yellow">
                                    <td><strong>Reconciled Balance</strong></td>
                                    <td>[Matched Balance]</td>
                                    <td>[Matched Balance]</td>
                                </tr>-->
								<tr>
									<td>Balance as per Cash Book (Bank Book)</td>
									<td id="openingCash"></td>
									<td></td>
								</tr>
								<tr>
									<td>Add: Deposits in Transit</td>
									<td></td>
									<td id="depositAmt"></td>
								</tr>
								<tr>
									<td>Less: Unrepresented Cheques</td>
									<td></td>
									<td id="chequeAmt"></td>
								</tr>
								<tr>
									<td>Less : Bank Charges</td>
									<td></td>
									<td id="charges"></td>
								</tr>
								<tr>
									<td>Balance as per Bank Statement</td>
									<td></td>
									<td id="closingBalance"></td>
								</tr>
								<tr style="background-color: yellow">
									<td><strong>Reconciled Balance</strong></td>
									<td id="matchedBalance1"></td>
									<td id="matchedBalance2"></td>
								</tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 text-end mt-4">
                            <button type="button" id="printBankReport" class="btn btn-secondary me-2" onclick="printBankReport()">Print</button>
                            
							<a href="javascript:void(0);" onclick="downloadPdf()" class="btn btn-primary">Download</a>
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
		
		function downloadPdf() {
			let fy = $('#financial_year').val();
			let type = $('#reportType').val();
			let q = $('#quarterSelect').val();

			window.location.href =
				"{{ route('bank.reconciliation.download') }}" +
				"?financial_year=" + fy +
				"&report_type=" + type +
				"&quarter=" + q;
		}
		
		function fetchBankReconciliation() {

			let financialYear = document.querySelector('select[name="financial_year"]')?.value
				|| document.querySelector('.form-select').value;

			let reportType = document.getElementById('reportType').value;
			let quarter    = document.getElementById('quarterSelect').value;
			$("#loader").show();
			$.ajax({
				url: "{{ route('user.fetchBankReconciliation') }}",
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					financial_year: financialYear,
					report_type: reportType,
					quarter: quarter
				},
				success: function(res) {
					$('#loader').hide();
					console.log(res);					
					$("#openingCash").text(res.opening_cash.toLocaleString('en-IN'));
					$("#depositAmt").text(res.deposit.toLocaleString('en-IN'));
					$("#chequeAmt").text(res.cheque.toLocaleString('en-IN'));
					$("#charges").text(res.charges.toLocaleString('en-IN'));
					$("#closingBalance").text(res.closing_bank.toLocaleString('en-IN'));
					$("#matchedBalance1").text(res.reconciled_cash.toLocaleString('en-IN'));
					$("#matchedBalance2").text(res.reconciled_bank.toLocaleString('en-IN'));
				}
			});
		}
    </script>

@endsection
