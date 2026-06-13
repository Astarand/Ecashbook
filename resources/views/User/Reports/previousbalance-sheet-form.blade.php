@extends('App.Layout')

@section('container')

<div class="pc-content">

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h2 class="h4 mb-1">Balance Sheet Submission Form</h2>
                        <p class="text-muted mb-0">Fill previous year values and preview before saving.</p>
                    </div>
                    <button type="button" id="previewBtn" class="btn btn-primary">
                        Preview & Submit
                    </button>
                </div>
            </div>

            <form id="balanceForm" action="{{ route('savePreviousBalanceSheet') }}" method="POST">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">A. Equity & Liabilities</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">Shareholders' Funds</h5>
                        <div class="row g-3">
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Select Financial Year <span class="text-danger">*</span></label>
                                <select class="form-select" name="fy" id="financial-year">
                                    <option value="">Select Financial Year</option>
                                    <option value="2021-2022">2021-2022</option>
                                    <option value="2022-2023">2022-2023</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2025-2026">2025-2026</option>
                                    <option value="2026-2027">2026-2027</option>
                                    <option value="2027-2028">2027-2028</option>
                                    <option value="2028-2029">2028-2029</option>
                                    <option value="2029-2030">2029-2030</option>
                                    <option value="2030-2031">2030-2031</option>
                                    <option value="2031-2032">2031-2032</option>
                                    <option value="2032-2033">2032-2033</option>
                                    <option value="2033-2034">2033-2034</option>
                                    <option value="2034-2035">2034-2035</option>
                                    <option value="2035-2036">2035-2036</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Share Capital</label>
                                <input type="number" value="{{ old('share_capital') }}" name="share_capital" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Reserves and Surplus</label>
                                <input type="number" value="{{ old('reserves_surplus') }}" name="reserves_surplus" class="form-control">
                            </div>
							<div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Current Year Profit</label>
                                <input type="number" value="{{ old('current_year_profit') }}" name="current_year_profit" class="form-control">
                            </div>
                            
                        </div>

                        <h5 class="mb-3">Non-Current Liabilities</h5>
                        <div class="row g-3">
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Long-term Borrowings</label>
                                <input type="number" value="{{ old('long_term_borrowings') }}" name="long_term_borrowings" class="form-control">
                            </div>
							<div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Other Financial Liabilities</label>
                                <input type="number" value="{{ old('other_financial_liabilities') }}" name="other_financial_liabilities" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Deferred Tax Liabilities (Net)</label>
                                <input type="number" value="{{ old('deferred_tax_liabilities') }}" name="deferred_tax_liabilities" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Other Non-Current Liabilities</label>
                                <input type="number" value="{{ old('other_non_current_liabilities') }}" name="other_non_current_liabilities" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Long-term Provisions</label>
                                <input type="number" value="{{ old('long_term_provisions') }}" name="long_term_provisions" class="form-control">
                            </div>
                        </div>

                        <h5 class="mb-3">4. Current Liabilities</h5>
						<div class="row g-3">

							<div class="col-md-3">
								<label class="form-label">Trade Payables (Creditors)</label>
								<input type="number" name="trade_payables" class="form-control" value="{{ old('trade_payables') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">Advance from Customer</label>
								<input type="number" name="advance_from_customer" class="form-control" value="{{ old('advance_from_customer') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">Outstanding Expenses</label>
								<input type="number" name="outstanding_expenses" class="form-control" value="{{ old('outstanding_expenses') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">Salary Payable</label>
								<input type="number" name="salary_payable" class="form-control" value="{{ old('salary_payable') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">GST Payable</label>
								<input type="number" name="gst_payable" class="form-control" value="{{ old('gst_payable') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">TDS Payable</label>
								<input type="number" name="tds_payable" class="form-control" value="{{ old('tds_payable') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">PF Payable</label>
								<input type="number" name="pf_payable" class="form-control" value="{{ old('pf_payable') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">ESI Payable</label>
								<input type="number" name="esi_payable" class="form-control" value="{{ old('esi_payable') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">Short-term Loans</label>
								<input type="number" name="short_term_loans" class="form-control" value="{{ old('short_term_loans') }}">
							</div>

							<div class="col-md-3">
								<label class="form-label">Interest Payable</label>
								<input type="number" name="interest_payable" class="form-control" value="{{ old('interest_payable') }}">
							</div>

						</div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">B. Assets</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3">1. Non-Current Assets</h5>
						<div class="row g-3">

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Property, Plant & Equipment (PPE)</label>
								<input type="number"
									   value="{{ old('property_plant_equipment') }}"
									   name="property_plant_equipment"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Furniture & Fixtures</label>
								<input type="number"
									   value="{{ old('furniture_fixtures') }}"
									   name="furniture_fixtures"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Computer & IT Equipment</label>
								<input type="number"
									   value="{{ old('computer_it_equipment') }}"
									   name="computer_it_equipment"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Machinery</label>
								<input type="number"
									   value="{{ old('machinery') }}"
									   name="machinery"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Vehicles</label>
								<input type="number"
									   value="{{ old('vehicles') }}"
									   name="vehicles"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Intangible / Non-physical Assets</label>
								<input type="number"
									   value="{{ old('intangible_assets') }}"
									   name="intangible_assets"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Capital Work-in-Progress</label>
								<input type="number"
									   value="{{ old('capital_work_in_progress') }}"
									   name="capital_work_in_progress"
									   class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Other Non-Current Assets</label>
								<input type="number"
									   value="{{ old('other_non_current_assets') }}"
									   name="other_non_current_assets"
									   class="form-control">
							</div>

						</div>

                        <h5 class="mb-3">2. Current Assets</h5>
                        <div class="row g-3">
                            <div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Cash in Hand</label>
								<input type="number" name="cash_in_hand" value="{{ old('cash_in_hand') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Bank Accounts</label>
								<input type="number" name="bank_accounts" value="{{ old('bank_accounts') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Trade Receivables (Debtors)</label>
								<input type="number" name="trade_receivables" value="{{ old('trade_receivables') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Advance to Vendor</label>
								<input type="number" name="advance_to_vendor" value="{{ old('advance_to_vendor') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Employee Advance</label>
								<input type="number" name="employee_advance" value="{{ old('employee_advance') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Prepaid Expenses</label>
								<input type="number" name="prepaid_expenses" value="{{ old('prepaid_expenses') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Input GST Credit (ITC)</label>
								<input type="number" name="input_gst_credit" value="{{ old('input_gst_credit') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">TDS Receivable</label>
								<input type="number" name="tds_receivable" value="{{ old('tds_receivable') }}" class="form-control">
							</div>

							<div class="mb-3 col-md-6 col-lg-3">
								<label class="form-label">Inventories / Stocks</label>
								<input type="number" name="inventories" value="{{ old('inventories') }}" class="form-control">
							</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="button" id="previewBtnBottom" class="btn btn-primary">
                        Preview & Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- ---------- Bootstrap modal for preview ---------- --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Balance Sheet Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
          <div class="container-fluid">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center" style="width: 50px;"></th>
                            <th class="text-center" colspan="2">Particulars</th>
                            <th class="text-center">Note No.</th>
                            <th class="text-center">Figures as at the end of Current Reporting Period</th>
                        </tr>
                        <tr>
                            <th colspan="5"></th>
                            <th class="text-start"><strong>Duration: <span id="frmDate"></span></strong></th>
                        </tr>
                        <!--<tr>
                            <th colspan="5"></th>
                            <th class="text-start"><strong>Amount In: ₹<span id="curr-grand-total"></span></strong></th>
                        </tr>-->
                    </thead>
                    <tbody>
                        <!-- Revenue Section -->
                        <tr style="border: 1px solid #ddd;">
                            <td class="text-start"><strong>A.</strong></td>
                            <td class="text-start" colspan="5" style="background-color: #cbecd6;"><strong>EUITY &amp; LIABILITIES</strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>1.</strong></td>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                            <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Shareholders' Funds</strong></td>
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
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">b.</td>
                            <td class="text-start">Reserves and Surplus</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-reserves-surplus"></td>
                        </tr>
						<tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start">Current Year Profit</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-current-year-profit"></td>
                        </tr>
                        
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-equity-total"></span></strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                            <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Share Application Money Pending Allotment</strong></td>
                            <td style="border: 1px solid #ddd;"></td>
                            <td style="border: 1px solid #ddd;"></td>
                            
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>3.</strong></td>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                            <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Non-Current Liabilities</strong></td>
                            <td style="border: 1px solid #ddd;"></td>
                            <td style="border: 1px solid #ddd;"></td>
                            
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">a.</td>
                            <td class="text-start"> Long-term borrowings</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-long-term-borrowings"></td>
                        </tr>
						<tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">a.</td>
                            <td class="text-start"> Other Financial Liabilities</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-other-financial-liabilities"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">b.</td>
                            <td class="text-start"> Deferred tax liabilities (net)</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-deferred-tax-liabilities"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start"> Other Non-Current Liabilities</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-other-long-term-liabilities"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">d.</td>
                            <td class="text-start"> Long-term provisions</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-long-term-provisions"></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-noncurr-liab-total"></span></strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>4.</strong></td>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                            <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Current Liabilities</strong></td>
                            <td style="border: 1px solid #ddd;"></td>
                            <td style="border: 1px solid #ddd;"></td>
                            
                        </tr>
                        <tr>
							<td></td>
							<td class="text-center" style="width: 50px;">a.</td>
							<td class="text-start">Trade Payables</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-trade-payables"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">b.</td>
							<td class="text-start">Advance from Customer</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-advance-from-customer"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">c.</td>
							<td class="text-start">Outstanding Expenses</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-outstanding-expenses"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">d.</td>
							<td class="text-start">Salary Payable</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-salary-payable"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">e.</td>
							<td class="text-start">GST Payable</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-gst-payable"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">f.</td>
							<td class="text-start">TDS Payable</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-tds-payable"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">g.</td>
							<td class="text-start">PF Payable</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-pf-payable"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">h.</td>
							<td class="text-start">ESI Payable</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-esi-payable"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">i.</td>
							<td class="text-start">Short-term Loans</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-short-term-loans"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">j.</td>
							<td class="text-start">Interest Payable</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-interest-payable"></td>
						</tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-curr-liab-total"></span></strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: greenyellow; border: 1px solid #ddd;"><strong>TOTAL EUITY AND LIABILITIES</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-eq-liab-total"></span></strong></td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                        </tr>
                        <tr style="border: 1px solid #ddd;">
                            <td class="text-start"><strong>A.</strong></td>
                            <td class="text-start" colspan="5" style="background-color: #cbecd6;"><strong>ASSETS</strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>1.</strong></td>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                            <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Non-Current Assets</strong></td>
                            <td style="border: 1px solid #ddd;"></td>
                            <td style="border: 1px solid #ddd;"></td>
                            
                        </tr>
                        <tr>
							<td></td>
							<td class="text-center" style="width: 50px;">a.</td>
							<td class="text-start">Property, Plant & Equipment (PPE)</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-ppe"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">b.</td>
							<td class="text-start">Furniture & Fixtures</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-furniture-fixtures"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">c.</td>
							<td class="text-start">Computer & IT Equipment</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-computer-it-equipment"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">d.</td>
							<td class="text-start">Machinery</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-machinery"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">e.</td>
							<td class="text-start">Vehicles</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-vehicles"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">f.</td>
							<td class="text-start">Intangible / Non-physical Assets</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-intangible-assets"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">g.</td>
							<td class="text-start">Capital Work-in-Progress</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-capital-work-in-progress"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">h.</td>
							<td class="text-start">Other Non-Current Assets</td>
							<td></td>
							<td></td>
							<td class="text-start" id="curr-other-non-current-assets"></td>
						</tr>

						<tr>
							<th colspan="5" class="text-end">Total</th>
							<th id="curr-nonassets-total"></th>
						</tr>
                        
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-nonassets-total"></span></strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
                            <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                            <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Current Assets</strong></td>
                            <td style="border: 1px solid #ddd;"></td>
                            <td style="border: 1px solid #ddd;"></td>
                            
                        </tr>
                        <tr>
							<td></td>
							<td class="text-center">a.</td>
							<td>Cash in Hand</td>
							<td></td>
							<td></td>
							<td id="curr-cash-in-hand"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">b.</td>
							<td>Bank Accounts</td>
							<td></td>
							<td></td>
							<td id="curr-bank-accounts"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">c.</td>
							<td>Trade Receivables (Debtors)</td>
							<td></td>
							<td></td>
							<td id="curr-trade-receivables"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">d.</td>
							<td>Advance to Vendor</td>
							<td></td>
							<td></td>
							<td id="curr-advance-to-vendor"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">e.</td>
							<td>Employee Advance</td>
							<td></td>
							<td></td>
							<td id="curr-employee-advance"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">f.</td>
							<td>Prepaid Expenses</td>
							<td></td>
							<td></td>
							<td id="curr-prepaid-expenses"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">g.</td>
							<td>Input GST Credit (ITC)</td>
							<td></td>
							<td></td>
							<td id="curr-input-gst-credit"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">h.</td>
							<td>TDS Receivable</td>
							<td></td>
							<td></td>
							<td id="curr-tds-receivable"></td>
						</tr>

						<tr>
							<td></td>
							<td class="text-center">i.</td>
							<td>Inventories / Stocks</td>
							<td></td>
							<td></td>
							<td id="curr-inventories"></td>
						</tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-assets-total"></span></strong></td>
                        </tr>
                        <tr>
                            <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                            <td colspan="4" class="text-start" style="background-color: greenyellow; border: 1px solid #ddd;"><strong>TOTAL ASSETS</strong></td>
                            <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-total-assets"></span></strong></td>
                        </tr>
                    </tbody>
                </table>

              <div class="d-flex justify-content-end gap-2 mt-3">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                      Edit
                  </button>
                  <button type="button" id="confirmSubmit" class="btn btn-success">
                      Confirm & Save
                  </button>
              </div>
                  </div>
              </div>
      </div>

  </div>
</div>


<script>

$(document).on('input', 'input[type="number"]', function () {
    let value = $(this).val();

    // Allow only digits and dot
    value = value.replace(/[^0-9.]/g, '');

    // Keep only first dot, remove others
    let firstDotIndex = value.indexOf('.');
    if (firstDotIndex !== -1) {
        value = value.substring(0, firstDotIndex + 1) +
                value.substring(firstDotIndex + 1).replace(/\./g, '');
    }

    $(this).val(value);
});

$(function () {

    const modal = new bootstrap.Modal(document.getElementById('previewModal'));

    function getVal(name){
        let val = parseFloat($('[name="'+name+'"]').val());
        return isNaN(val) ? 0 : val;
    }

    function setText(id, val){
        $('#'+id).text(val.toFixed(2));
    }

    function openPreview(){

        // Financial Year
        let fy = $('#financial-year').val();
        $('#frmDate').text(fy);

        /* =========================
           EQUITY
        ========================= */
        let share_capital = getVal('share_capital');
        let reserves = getVal('reserves_surplus');
        let currYearProfit = getVal('current_year_profit');

        setText('curr-share-capital', share_capital);
        setText('curr-reserves-surplus', reserves);
        setText('curr-current-year-profit', currYearProfit);

        let equityTotal = share_capital + reserves + currYearProfit;
        setText('curr-equity-total', equityTotal);


        /* =========================
           NON CURRENT LIABILITIES
        ========================= */
        let long_term_borrowings = getVal('long_term_borrowings');
        let other_financial_liabilities = getVal('other_financial_liabilities');
        let deferred_tax_liabilities = getVal('deferred_tax_liabilities');
        let other_non_current_liabilities = getVal('other_non_current_liabilities');
        let long_term_provisions = getVal('long_term_provisions');

        setText('curr-long-term-borrowings', long_term_borrowings);
        setText('curr-other-financial-liabilities', other_financial_liabilities);
        setText('curr-deferred-tax-liabilities', deferred_tax_liabilities);
        setText('curr-other-long-term-liabilities', other_non_current_liabilities);
        setText('curr-long-term-provisions', long_term_provisions);

        let nonCurrLiabTotal = (long_term_borrowings + other_financial_liabilities + deferred_tax_liabilities + other_non_current_liabilities + long_term_provisions);
        setText('curr-noncurr-liab-total', nonCurrLiabTotal);


        /* =========================
           CURRENT LIABILITIES
        ========================= */
        let trade_payables = getVal('trade_payables');
		let advance_from_customer = getVal('advance_from_customer');
		let outstanding_expenses = getVal('outstanding_expenses');
		let salary_payable = getVal('salary_payable');
		let gst_payable = getVal('gst_payable');
		let tds_payable = getVal('tds_payable');
		let pf_payable = getVal('pf_payable');
		let esi_payable = getVal('esi_payable');
		let short_term_loans = getVal('short_term_loans');
		let interest_payable = getVal('interest_payable');

		/* preview set */
		setText('curr-trade-payables', trade_payables);
		setText('curr-advance-from-customer', advance_from_customer);
		setText('curr-outstanding-expenses', outstanding_expenses);
		setText('curr-salary-payable', salary_payable);
		setText('curr-gst-payable', gst_payable);
		setText('curr-tds-payable', tds_payable);
		setText('curr-pf-payable', pf_payable);
		setText('curr-esi-payable', esi_payable);
		setText('curr-short-term-loans', short_term_loans);
		setText('curr-interest-payable', interest_payable);

		/* TOTAL */
		let currLiabTotal =
			trade_payables +
			advance_from_customer +
			outstanding_expenses +
			salary_payable +
			gst_payable +
			tds_payable +
			pf_payable +
			esi_payable +
			short_term_loans +
			interest_payable;

		setText('curr-curr-liab-total', currLiabTotal);


        let totalEqLiab = equityTotal + nonCurrLiabTotal + currLiabTotal;
        setText('curr-eq-liab-total', totalEqLiab);


        /* =========================
           NON CURRENT ASSETS
        ========================= */
		let ppe = getVal('property_plant_equipment');
		let furniture = getVal('furniture_fixtures');
		let computer = getVal('computer_it_equipment');
		let machinery = getVal('machinery');
		let vehicles = getVal('vehicles');
		let intangible = getVal('intangible_assets');
		let cwp = getVal('capital_work_in_progress');
		let other = getVal('other_non_current_assets');

		setText('curr-ppe', ppe);
		setText('curr-furniture-fixtures', furniture);
		setText('curr-computer-it-equipment', computer);
		setText('curr-machinery', machinery);
		setText('curr-vehicles', vehicles);
		setText('curr-intangible-assets', intangible);
		setText('curr-capital-work-in-progress', cwp);
		setText('curr-other-non-current-assets', other);

		let nonAssetTotal =
			ppe +
			furniture +
			computer +
			machinery +
			vehicles +
			intangible +
			cwp +
			other;

		setText('curr-nonassets-total', nonAssetTotal);

        /* =========================
           CURRENT ASSETS
        ========================= */
		let cash = getVal('cash_in_hand');
		let bank = getVal('bank_accounts');
		let receivables = getVal('trade_receivables');
		let adv_vendor = getVal('advance_to_vendor');
		let employee_adv = getVal('employee_advance');
		let prepaid = getVal('prepaid_expenses');
		let gst_itc = getVal('input_gst_credit');
		let tds = getVal('tds_receivable');
		let inventory = getVal('inventories');

		setText('curr-cash-in-hand', cash);
		setText('curr-bank-accounts', bank);
		setText('curr-trade-receivables', receivables);
		setText('curr-advance-to-vendor', adv_vendor);
		setText('curr-employee-advance', employee_adv);
		setText('curr-prepaid-expenses', prepaid);
		setText('curr-input-gst-credit', gst_itc);
		setText('curr-tds-receivable', tds);
		setText('curr-inventories', inventory);

		let currAssetTotal =
			cash + bank + receivables + adv_vendor + employee_adv +
			prepaid + gst_itc + tds + inventory;

		setText('curr-assets-total', currAssetTotal);


        let totalAssets = nonAssetTotal + currAssetTotal;
        setText('curr-total-assets', totalAssets);


        // Grand Total Top
        $('#curr-grand-total').text(totalAssets.toFixed(2));

        modal.show();
    }


    // button click
    //$('#previewBtn, #previewBtnBottom').click(openPreview);
	$('#previewBtn, #previewBtnBottom').click(function () {
		let fy = $('#financial-year').val();
		// Check if not selected OR default option
		if (!fy || fy === '') {
			alert('Please select Financial Year');
			$('#financial-year').focus();
			return;
		}

		openPreview(); // only open if valid
	});

    // submit
    $('#confirmSubmit').click(function(){
        //$('#balanceForm').submit();
		let form = $('#balanceForm');
		$("#loader").show();
		$.ajax({
			url: form.attr('action'),
			type: 'POST',
			data: form.serialize(),
			success: function (res) {
				$("#loader").hide();
				showToast('Saved successfully', 'success');
				window.location.href = '/balance-sheet-report';
			},
			error: function (xhr) {
				$("#loader").hide();
				alert('Something went wrong!');
				console.log(xhr.responseText);
			}
		});
    });

});
</script>
@endsection



