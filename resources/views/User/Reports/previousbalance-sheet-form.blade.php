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
                        <li class="breadcrumb-item"><a href="{{ route('user.BalanceSheetReport') }}">Balance Sheet</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Submission Form</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Balance Sheet Submission Form</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

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

            <form id="balanceForm" action="{{ route('balance-sheet.savebalancesheetprivious') }}" method="POST">
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
                            
                        </div>

                        <h5 class="mb-3">2. Share Application Money Pending Allotment</h5>

                        <h5 class="mb-3">Non-Current Liabilities</h5>
                        <div class="row g-3">
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Long-term Borrowings</label>
                                <input type="number" value="{{ old('long_term_borrowings') }}" name="long_term_borrowings" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Deferred Tax Liabilities (Net)</label>
                                <input type="number" value="{{ old('deferred_tax_liabilities') }}" name="deferred_tax_liabilities" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Other Long-term Liabilities</label>
                                <input type="number" value="{{ old('other_long_term_liabilities') }}" name="other_long_term_liabilities" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Long-term Provisions</label>
                                <input type="number" value="{{ old('long_term_provisions') }}" name="long_term_provisions" class="form-control">
                            </div>
                        </div>

                        <h5 class="mb-3">4. Current Liabilities</h5>
                        <div class="row g-3">
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Short-term Borrowings</label>
                                <input type="number" value="{{ old('short_term_borrowings') }}" name="short_term_borrowings" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Trade Payables</label>
                                <input type="number" value="{{ old('trade_payables') }}" name="trade_payables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Advances from Customers</label>
                                <input type="number" value="{{ old('advances_from_customers') }}" name="advances_from_customers" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Statutory Dues Payable</label>
                                <input type="number" value="{{ old('statutory_dues_payable') }}" name="statutory_dues_payable" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">TDS Payable</label>
                                <input type="number" value="{{ old('tds_payable') }}" name="tds_payable" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">EMI Payables</label>
                                <input type="number" value="{{ old('emi_payables') }}" name="emi_payables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Accrued Expenses / Income</label>
                                <input type="number" value="{{ old('accrued_expenses_income') }}" name="accrued_expenses_income" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Royalty Payables</label>
                                <input type="number" value="{{ old('royalty_payables') }}" name="royalty_payables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">GST Payable</label>
                                <input type="number" value="{{ old('gst_payable') }}" name="gst_payable" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Unearned Revenue</label>
                                <input type="number" value="{{ old('unearned_revenue') }}" name="unearned_revenue" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Security Deposits Payable</label>
                                <input type="number" value="{{ old('security_deposits_payable') }}" name="security_deposits_payable" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Other Current Liabilities</label>
                                <input type="number" value="{{ old('other_current_liabilities') }}" name="other_current_liabilities" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Short-term Provisions</label>
                                <input type="number" value="{{ old('short_term_provisions') }}" name="short_term_provisions" class="form-control">
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
                                <label class="form-label">Fixed Assets</label>
                                <input type="number" value="{{ old('fixed_assets') }}" name="fixed_assets" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Tangible Assets</label>
                                <input type="number" value="{{ old('tangible_assets') }}" name="tangible_assets" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Intangible Assets</label>
                                <input type="number" value="{{ old('intangible_assets') }}" name="intangible_assets" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Capital WIP / Under Development</label>
                                <input type="number" value="{{ old('capital_wip_under_development') }}" name="capital_wip_under_development" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Non-Current Investments</label>
                                <input type="number" value="{{ old('non_current_investments') }}" name="non_current_investments" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Deferred Tax Assets (Net)</label>
                                <input type="number" value="{{ old('deferred_tax_assets') }}" name="deferred_tax_assets" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Long-term Loans and Advances</label>
                                <input type="number" value="{{ old('long_term_loans_and_advances') }}" name="long_term_loans_and_advances" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Other Non-Current Assets</label>
                                <input type="number" value="{{ old('other_non_current_assets') }}" name="other_non_current_assets" class="form-control">
                            </div>
                        </div>

                        <h5 class="mb-3">2. Current Assets</h5>
                        <div class="row g-3">
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Current Investments</label>
                                <input type="number" value="{{ old('current_investments') }}" name="current_investments" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Inventories</label>
                                <input type="number" value="{{ old('inventories') }}" name="inventories" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Trade Receivables</label>
                                <input type="number" value="{{ old('trade_receivables') }}" name="trade_receivables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Cash and Cash Equivalents</label>
                                <input type="number" value="{{ old('cash_and_cash_equivalents') }}" name="cash_and_cash_equivalents" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Short-Term Loans and Advances</label>
                                <input type="number" value="{{ old('short_term_loans_and_advances') }}" name="short_term_loans_and_advances" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Advances to Vendors</label>
                                <input type="number" value="{{ old('advances_to_vendors') }}" name="advances_to_vendors" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Unbilled Revenue</label>
                                <input type="number" value="{{ old('unbilled_revenue') }}" name="unbilled_revenue" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">GST Receivable</label>
                                <input type="number" value="{{ old('gst_receivable') }}" name="gst_receivable" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">TDS Receivable</label>
                                <input type="number" value="{{ old('tds_receivable') }}" name="tds_receivable" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Interest Accrued but Not Due</label>
                                <input type="number" value="{{ old('interest_accrued_but_not_due') }}" name="interest_accrued_but_not_due" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Prepaid Expenses</label>
                                <input type="number" value="{{ old('prepaid_expenses') }}" name="prepaid_expenses" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Group Company Receivables</label>
                                <input type="number" value="{{ old('group_company_receivables') }}" name="group_company_receivables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Other Current Assets</label>
                                <input type="number" value="{{ old('other_current_assets') }}" name="other_current_assets" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Grant/Subsidy Receivables</label>
                                <input type="number" value="{{ old('grant_subsidy_receivables') }}" name="grant_subsidy_receivables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Deferred Revenue</label>
                                <input type="number" value="{{ old('deferred_revenue') }}" name="deferred_revenue" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Royalty Receivables</label>
                                <input type="number" value="{{ old('royalty_receivables') }}" name="royalty_receivables" class="form-control">
                            </div>
                            <div class="mb-3 col-md-6 col-lg-3">
                                <label class="form-label">Work-in-Progress</label>
                                <input type="number" value="{{ old('work_in_progress') }}" name="work_in_progress" class="form-control">
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
                        <!--<tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start">Retained Earnings</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-retained-earnings"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">d.</td>
                            <td class="text-start">Money received against share warrants</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-money-against-warrants"></td>
                        </tr>-->
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
                            <td class="text-center" style="width: 50px;">b.</td>
                            <td class="text-start"> Deferred tax liabilities (net)</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-deferred-tax-liabilities"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start"> Other long-term liabilities</td>
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
                            <td class="text-start"> Short-Term Borrowings</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-short-term-borrowings"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">b.</td>
                            <td class="text-start"> Trade Payables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-trade-payables"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start">Advances from Customers</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-advances-from-customers"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">d.</td>
                            <td class="text-start">Statutory Dues Payable</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-stat-dus-payable"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">e.</td>
                            <td class="text-start">TDS Payable</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-tds-payable"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">f.</td>
                            <td class="text-start">EMI Payables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-emi-payables"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">g.</td>
                            <td class="text-start">Accrued Expenses / Income</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-accrued-expenses"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">h.</td>
                            <td class="text-start">Royalty Payables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-royalty-payables"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">i.</td>
                            <td class="text-start">GST Payable</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-gst-payable"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">j.</td>
                            <td class="text-start">Unearned Revenue</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-unearned-revenue"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">k.</td>
                            <td class="text-start">Security Deposits Payable</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-security-deposits-payable"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">l.</td>
                            <td class="text-start"> Other Current Liabilities</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-other-current-liabilities"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">m.</td>
                            <td class="text-start"> Short-Term Provisions</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-short-term-provisions"></td>
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
                            <td class="text-start"> Fixed Assets</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-fixed-assets"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">b.</td>
                            <td class="text-start">Tangible Assets</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-tangible-assets"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start">Intangible Assets</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-intangible-assets"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">d.</td>
                            <td class="text-start">Capital WIP / Under Development</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-capital-wip"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">e.</td>
                            <td class="text-start">Non-Current Investments</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-non-current-investments"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">f.</td>
                            <td class="text-start">Deferred Tax Assets (Net)</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-deferred-tax-assets"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">g.</td>
                            <td class="text-start">Long-term Loans and Advances</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-long-term-loans-advances"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">h.</td>
                            <td class="text-start">Other Non-Current Assets</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-other-non-current-assets"></td>
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
                            <td class="text-center" style="width: 50px;">a.</td>
                            <td class="text-start"> Current Investments</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-current-investments"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">b.</td>
                            <td class="text-start">Inventories</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-inventories"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">c.</td>
                            <td class="text-start"> Trade Receivables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-trade-receivables"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">d.</td>
                            <td class="text-start"> Cash and Cash Equivalents</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-cash-and-cash-equivalents"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">e.</td>
                            <td class="text-start"> Short-Term Loans and Advances</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-short-term-loans-and-advances"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">f.</td>
                            <td class="text-start">Advances to Vendors</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-advances-to-vendors"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">g.</td>
                            <td class="text-start">Unbilled Revenue</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-unbilled-revenue"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">h.</td>
                            <td class="text-start">GST Receivable</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-gst-receivable"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">i.</td>
                            <td class="text-start">TDS Receivable</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-tds-receivable"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">j.</td>
                            <td class="text-start">Interest Accrued but Not Due</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-interest-accrued-but-not-due"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">k.</td>
                            <td class="text-start">Prepaid Expenses</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-prepaid-expenses"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">l.</td>
                            <td class="text-start">Group Company Receivables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-group-company-receivables"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">m.</td>
                            <td class="text-start">Other Current Assets</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-other-current-assets"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">n.</td>
                            <td class="text-start">Grant/Subsidy Receivables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-grant-subsidy-receivables"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">o.</td>
                            <td class="text-start">Deferred Revenue</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-deferred-revenue"></td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">p.</td>
                            <td class="text-start">Royalty Receivables</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-royalty-receivables"></td>
                        </tr>
                            <tr>
                            <td></td>
                            <td class="text-center" style="width: 50px;">q.</td>
                            <td class="text-start">Work-in-Progress</td>
                            <td></td>
                            <td class="text-start"></td>
                            <td class="text-start" id="curr-work-in-progress"></td>
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

        setText('curr-share-capital', share_capital);
        setText('curr-reserves-surplus', reserves);

        let equityTotal = share_capital + reserves;
        setText('curr-equity-total', equityTotal);


        /* =========================
           NON CURRENT LIABILITIES
        ========================= */
        let long_term_borrowings = getVal('long_term_borrowings');
        let deferred_tax_liabilities = getVal('deferred_tax_liabilities');
        let other_long_term_liabilities = getVal('other_long_term_liabilities');
        let long_term_provisions = getVal('long_term_provisions');

        setText('curr-long-term-borrowings', long_term_borrowings);
        setText('curr-deferred-tax-liabilities', deferred_tax_liabilities);
        setText('curr-other-long-term-liabilities', other_long_term_liabilities);
        setText('curr-long-term-provisions', long_term_provisions);

        let nonCurrLiabTotal = long_term_borrowings + deferred_tax_liabilities + other_long_term_liabilities + long_term_provisions;
        setText('curr-noncurr-liab-total', nonCurrLiabTotal);


        /* =========================
           CURRENT LIABILITIES
        ========================= */
        let short_term_borrowings = getVal('short_term_borrowings');
        let trade_payables = getVal('trade_payables');
        let advances = getVal('advances_from_customers');
        let statutory = getVal('statutory_dues_payable');
        let tds = getVal('tds_payable');
        let emi = getVal('emi_payables');
        let accrued = getVal('accrued_expenses_income');
        let royalty = getVal('royalty_payables');
        let gst = getVal('gst_payable');
        let unearned = getVal('unearned_revenue');
        let security = getVal('security_deposits_payable');
        let other = getVal('other_current_liabilities');
        let shortProv = getVal('short_term_provisions');

        setText('curr-short-term-borrowings', short_term_borrowings);
        setText('curr-trade-payables', trade_payables);
        setText('curr-advances-from-customers', advances);
        setText('curr-stat-dus-payable', statutory);
        setText('curr-tds-payable', tds);
        setText('curr-emi-payables', emi);
        setText('curr-accrued-expenses', accrued);
        setText('curr-royalty-payables', royalty);
        setText('curr-gst-payable', gst);
        setText('curr-unearned-revenue', unearned);
        setText('curr-security-deposits-payable', security);
        setText('curr-other-current-liabilities', other);
        setText('curr-short-term-provisions', shortProv);

        let currLiabTotal = short_term_borrowings + trade_payables + advances + statutory + tds + emi + accrued + royalty + gst + unearned + security + other + shortProv;
        setText('curr-curr-liab-total', currLiabTotal);


        let totalEqLiab = equityTotal + nonCurrLiabTotal + currLiabTotal;
        setText('curr-eq-liab-total', totalEqLiab);


        /* =========================
           NON CURRENT ASSETS
        ========================= */
        let fixed_assets = getVal('fixed_assets');
        let tangible_assets = getVal('tangible_assets');
        let intangible_assets = getVal('intangible_assets');
        let capital_wip = getVal('capital_wip_under_development');
        let investments = getVal('non_current_investments');
        let dta = getVal('deferred_tax_assets');
        let loans = getVal('long_term_loans_and_advances');
        let other_assets = getVal('other_non_current_assets');

        setText('curr-fixed-assets', fixed_assets);
        setText('curr-tangible-assets', tangible_assets);
        setText('curr-intangible-assets', intangible_assets);
        setText('curr-capital-wip', capital_wip);
        setText('curr-non-current-investments', investments);
        setText('curr-deferred-tax-assets', dta);
        setText('curr-long-term-loans-advances', loans);
        setText('curr-other-non-current-assets', other_assets);

        let nonAssetTotal = fixed_assets + tangible_assets + intangible_assets + capital_wip + investments + dta + loans + other_assets;
        setText('curr-nonassets-total', nonAssetTotal);


        /* =========================
           CURRENT ASSETS
        ========================= */
        let current_inv = getVal('current_investments');
        let inventories = getVal('inventories');
        let receivables = getVal('trade_receivables');
        let cash = getVal('cash_and_cash_equivalents');
        let short_loans = getVal('short_term_loans_and_advances');
        let adv_vendors = getVal('advances_to_vendors');
        let unbilled = getVal('unbilled_revenue');
        let gst_rec = getVal('gst_receivable');
        let tds_rec = getVal('tds_receivable');
        let interest = getVal('interest_accrued_but_not_due');
        let prepaid = getVal('prepaid_expenses');
        let group = getVal('group_company_receivables');
        let other_curr = getVal('other_current_assets');
        let grant = getVal('grant_subsidy_receivables');
        let deferred = getVal('deferred_revenue');
        let royalty_rec = getVal('royalty_receivables');
        let wip = getVal('work_in_progress');

        setText('curr-current-investments', current_inv);
        setText('curr-inventories', inventories);
        setText('curr-trade-receivables', receivables);
        setText('curr-cash-and-cash-equivalents', cash);
        setText('curr-short-term-loans-and-advances', short_loans);
        setText('curr-advances-to-vendors', adv_vendors);
        setText('curr-unbilled-revenue', unbilled);
        setText('curr-gst-receivable', gst_rec);
        setText('curr-tds-receivable', tds_rec);
        setText('curr-interest-accrued-but-not-due', interest);
        setText('curr-prepaid-expenses', prepaid);
        setText('curr-group-company-receivables', group);
        setText('curr-other-current-assets', other_curr);
        setText('curr-grant-subsidy-receivables', grant);
        setText('curr-deferred-revenue', deferred);
        setText('curr-royalty-receivables', royalty_rec);
        setText('curr-work-in-progress', wip);

        let currAssetTotal = current_inv + inventories + receivables + cash + short_loans + adv_vendors + unbilled + gst_rec + tds_rec + interest + prepaid + group + other_curr + grant + deferred + royalty_rec + wip;

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



