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
                        <li class="breadcrumb-item active" aria-current="page">Balance Sheet</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Balance Sheet</h2>
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
                                Generate Balance Sheet Report
                            </h4>
                        </div>
                        <div class="card-body">
                          
							<?php if($hasPreviousYearData ==0){ ?>
							<div class="col-md-12 text-end">
                                <a href="{{ route('previousbalancebalance-sheet.create') }}" class="btn btn-primary"><i class="ti ti-square-plus"></i> Add Previous</a>
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
            <div class="card">
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
                                <!--<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">c.</td>
                                    <td class="text-start">Retained Earnings</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-retained-earnings"></td>
                                    <td class="text-start" id="prev-retained-earnings"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">d.</td>
                                    <td class="text-start">Money received against share warrants</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-money-against-warrants"></td>
                                    <td class="text-start" id="prev-money-against-warrants"></td>
                                </tr>-->
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-equity-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-equity-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>2.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Share Application Money Pending Allotment</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>3.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Non-Current Liabilities</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
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
                                    <td class="text-start" id="prev-long-term-borrowings"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">b.</td>
                                    <td class="text-start"> Deferred tax liabilities (net)</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-deferred-tax-liabilities"></td>
                                    <td class="text-start" id="prev-deferred-tax-liabilities"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">c.</td>
                                    <td class="text-start"> Other long-term liabilities</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-other-long-term-liabilities"></td>
                                    <td class="text-start" id="prev-other-long-term-liabilities"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">d.</td>
                                    <td class="text-start"> Long-term provisions</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-long-term-provisions"></td>
                                    <td class="text-start" id="prev-long-term-provisions"></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-noncurr-liab-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-noncurr-liab-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"><strong>4.</strong></td>
                                    <td class="text-center" style="border: 1px solid #ddd; width: 50px;"></td>
                                    <td colspan="2" style="border: 1px solid #ddd; background-color: #f7e7b8;"><strong>Current Liabilities</strong></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                    <td style="border: 1px solid #ddd;"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">a.</td>
                                    <td class="text-start"> Borrowing (CC/OD)</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-borrowing-cc"></td>
                                    <td class="text-start" id="prev-borrowing-cc"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">b.</td>
                                    <td class="text-start"> Trade Payables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-trade-payables"></td>
                                    <td class="text-start" id="prev-trade-payables"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">c.</td>
                                    <td class="text-start"> Statutory</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-statutory"></td>
                                    <td class="text-start" id="prev-statutory"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">d.</td>
                                    <td class="text-start"> Expense</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-expense"></td>
                                    <td class="text-start" id="prev-expense"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">e.</td>
                                    <td class="text-start"> Advance</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-advance"></td>
                                    <td class="text-start" id="prev-advance"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">f.</td>
                                    <td class="text-start"> Provisions</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-provision"></td>
                                    <td class="text-start" id="prev-provision"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">g.</td>
                                    <td class="text-start"> Other Current Liabilities</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-other-current-liabilities"></td>
                                    <td class="text-start" id="prev-other-current-liabilities"></td>
                                </tr>
								
								
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">h.</td>
                                    <td class="text-start"> Short-Term Borrowings</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-short-term-borrowings"></td>
                                    <td class="text-start" id="prev-short-term-borrowings"></td>
                                </tr>
                                
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">i.</td>
                                    <td class="text-start">Advances from Customers</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-advances-from-customers"></td>
                                    <td class="text-start" id="prev-advances-from-customers"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">j.</td>
                                    <td class="text-start">Statutory Dues Payable</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-stat-dus-payable"></td>
                                    <td class="text-start" id="prev-stat-dus-payable"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">k.</td>
                                    <td class="text-start">TDS Payable</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-tds-payable"></td>
                                    <td class="text-start" id="prev-tds-payable"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">l.</td>
                                    <td class="text-start">EMI Payables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-emi-payables"></td>
                                    <td class="text-start" id="prev-emi-payables"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">m.</td>
                                    <td class="text-start">Accrued Expenses / Income</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-accrued-expenses"></td>
                                    <td class="text-start" id="prev-accrued-expenses"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">n.</td>
                                    <td class="text-start">Royalty Payables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-royalty-payables"></td>
                                    <td class="text-start" id="prev-royalty-payables"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">o.</td>
                                    <td class="text-start">GST Payable</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-gst-payable"></td>
                                    <td class="text-start" id="prev-gst-payable"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">p.</td>
                                    <td class="text-start">Unearned Revenue</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-unearned-revenue"></td>
                                    <td class="text-start" id="prev-unearned-revenue"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">q.</td>
                                    <td class="text-start">Security Deposits Payable</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-security-deposits-payable"></td>
                                    <td class="text-start" id="prev-security-deposits-payable"></td>
                                </tr>                                
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">r.</td>
                                    <td class="text-start"> Short-Term Provisions</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-short-term-provisions"></td>
                                    <td class="text-start" id="prev-short-term-provisions"></td>
                                </tr>
								
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-curr-liab-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-curr-liab-total"></span></strong></td>
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
                                    <td class="text-start"><strong>A.</strong></td>
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
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">a.</td>
                                    <td class="text-start"> Fixed Assets</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-fixed-assets"></td>
                                    <td class="text-start" id="prev-fixed-assets"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">b.</td>
                                    <td class="text-start">Tangible Assets</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-tangible-assets"></td>
                                    <td class="text-start" id="prev-tangible-assets"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">c.</td>
                                    <td class="text-start">Intangible Assets</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-intangible-assets"></td>
                                    <td class="text-start" id="prev-intangible-assets"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">d.</td>
                                    <td class="text-start">Capital WIP / Under Development</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-capital-wip"></td>
                                    <td class="text-start" id="prev-capital-wip"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">e.</td>
                                    <td class="text-start">Non-Current Investments</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-non-current-investments"></td>
                                    <td class="text-start" id="prev-non-current-investments"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">f.</td>
                                    <td class="text-start">Deferred Tax Assets (Net)</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-deferred-tax-assets"></td>
                                    <td class="text-start" id="prev-deferred-tax-assets"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">g.</td>
                                    <td class="text-start">Long-term Loans and Advances</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-long-term-loans-advances"></td>
                                    <td class="text-start" id="prev-long-term-loans-advances"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">h.</td>
                                    <td class="text-start">Investments</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-investments"></td>
                                    <td class="text-start" id="prev-investments"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">i.</td>
                                    <td class="text-start">Loans & Advance</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-loans-advances"></td>
                                    <td class="text-start" id="prev-loans-advances"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">j.</td>
                                    <td class="text-start">Other Non-Current Assets</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-other-non-current-assets"></td>
                                    <td class="text-start" id="prev-other-non-current-assets"></td>
                                </tr>
                                
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
								
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">a.</td>
                                    <td class="text-start"> Short-Term Loans and Advances</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-short-term-loans-and-advances"></td>
                                    <td class="text-start" id="prev-short-term-loans-and-advances"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">b.</td>
                                    <td class="text-start">Interest Accrued but Not Due</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-interest-accrued-but-not-due"></td>
                                    <td class="text-start" id="prev-interest-accrued-but-not-due"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">c.</td>
                                    <td class="text-start">Prepaid Expenses</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-prepaid-expenses"></td>
                                    <td class="text-start" id="prev-prepaid-expenses"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">d.</td>
                                    <td class="text-start">Group Company Receivables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-group-company-receivables"></td>
                                    <td class="text-start" id="prev-group-company-receivables"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">e.</td>
                                    <td class="text-start">Grant/Subsidy Receivables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-grant-subsidy-receivables"></td>
                                    <td class="text-start" id="prev-grant-subsidy-receivables"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">f.</td>
                                    <td class="text-start">Deferred Revenue</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-deferred-revenue"></td>
                                    <td class="text-start" id="prev-deferred-revenue"></td>
                                </tr>
                                
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">g.</td>
                                    <td class="text-start">Royalty Receivables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-royalty-receivables"></td>
                                    <td class="text-start" id="prev-royalty-receivables"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">h.</td>
                                    <td class="text-start">Inventories</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-inventories"></td>
                                    <td class="text-start" id="prev-inventories"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">i.</td>
                                    <td class="text-start"> Trade Receivables</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-trade-receivables"></td>
                                    <td class="text-start" id="prev-trade-receivables"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">j.</td>
                                    <td class="text-start"> Cash & Bank</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-cashBank"></td>
                                    <td class="text-start" id="prev-cashBank"></td>
                                </tr>
								<tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">k.</td>
                                    <td class="text-start">Other Current Assets</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-other-current-assets"></td>
                                    <td class="text-start" id="prev-other-current-assets"></td>
                                </tr>
								
								
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">l.</td>
                                    <td class="text-start"> Current Investments</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-current-investments"></td>
                                    <td class="text-start" id="prev-current-investments"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">m.</td>
                                    <td class="text-start"> Cash and Cash Equivalents</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-cash-and-cash-equivalents"></td>
                                    <td class="text-start" id="prev-cash-and-cash-equivalents"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">n.</td>
                                    <td class="text-start">Advances to Vendors</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-advances-to-vendors"></td>
                                    <td class="text-start" id="prev-advances-to-vendors"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">o.</td>
                                    <td class="text-start">Unbilled Revenue</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-unbilled-revenue"></td>
                                    <td class="text-start" id="prev-unbilled-revenue"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">p.</td>
                                    <td class="text-start">GST Receivable</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-gst-receivable"></td>
                                    <td class="text-start" id="prev-gst-receivable"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">q.</td>
                                    <td class="text-start">TDS Receivable</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-tds-receivable"></td>
                                    <td class="text-start" id="prev-tds-receivable"></td>
                                </tr>
                                 <tr>
                                    <td></td>
                                    <td class="text-center" style="width: 50px;">r.</td>
                                    <td class="text-start">Work-in-Progress</td>
                                    <td></td>
                                    <td class="text-start"></td>
                                    <td class="text-start" id="curr-work-in-progress"></td>
                                    <td class="text-start" id="prev-work-in-progress"></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: yellow; border: 1px solid #ddd;"><strong>TOTAL</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-assets-total"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-assets-total"></span></strong></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="border: 1px solid #ddd;"><strong></strong></td>
                                    <td colspan="4" class="text-start" style="background-color: greenyellow; border: 1px solid #ddd;"><strong>TOTAL ASSETS</strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="curr-total-assets"></span></strong></td>
                                    <td class="text-start" style="border: 1px solid #ddd;"><strong>₹<span id="prev-total-assets"></span></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 text-end mt-4">
                        <button type="button" id="" onclick="printBankReport()" class="btn btn-secondary me-2">Print</button>
                        <a href="javascript:void(0);" onclick="downloadBalanceSheetPdf()" class="btn btn-primary">Download</a>
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
	
	function downloadBalanceSheetPdf() {
		let tableHtml = document.querySelector('.table-responsive').innerHTML;
		$.ajax({
			url: "{{ route('balancesheet.download.pdf') }}",
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
				link.download = "Balance_Sheet.pdf";
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
                    url: '/fatch-balance-sheet-data',
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

                        /* ---------- Share Capital ---------- */
						//const prev = data.previousYearData || {};
						//const curr = data.currentYearData || {};
						
						/* ================= PREVIOUS YEAR ================= */
						const prev = data.previousYearData || {};

						const prevEquityTotal        = sumObjectValues(prev.equity);
						const prevNonCurrLiabTotal   = sumObjectValues(prev.noncurrliab);
						const prevCurrLiabTotal      = sumObjectValues(prev.currliab);
						const prevNonCurrAssetTotal  = sumObjectValues(prev.noncurrassets);
						const prevCurrAssetTotal     = sumObjectValues(prev.currassets);
						
						const prevEqLiabTotal = (prevEquityTotal + prevNonCurrLiabTotal + prevCurrLiabTotal);
						const prevAssetsTotal = (prevNonCurrAssetTotal + prevCurrAssetTotal);						
						const prevGrandTotal =(prevEquityTotal + prevNonCurrLiabTotal + prevCurrLiabTotal + prevNonCurrAssetTotal + prevCurrAssetTotal);
							  
						document.getElementById('prev-equity-total').innerText = formatAmount(prevEquityTotal);
						document.getElementById('prev-noncurr-liab-total').innerText = formatAmount(prevNonCurrLiabTotal);
						document.getElementById('prev-curr-liab-total').innerText = formatAmount(prevCurrLiabTotal);
						document.getElementById('prev-nonassets-total').innerText = formatAmount(prevNonCurrAssetTotal);							
						document.getElementById('prev-assets-total').innerText = formatAmount(prevCurrAssetTotal);

						document.getElementById('prev-eq-liab-total').innerText = formatAmount(prevEqLiabTotal);
						document.getElementById('prev-total-assets').innerText = formatAmount(prevAssetsTotal);
						document.getElementById('prev-grand-total').innerText = formatAmount(prevGrandTotal);

						/* ================= CURRENT YEAR ================= */
						const curr = data.currentYearData || {};

						const currEquityTotal        = sumObjectValues(curr.equity);
						const currNonCurrLiabTotal   = sumObjectValues(curr.noncurrliab);
						const currCurrLiabTotal      = sumObjectValues(curr.currliab);
						const currNonCurrAssetTotal  = sumObjectValues(curr.noncurrassets);
						const currCurrAssetTotal     = sumObjectValues(curr.currassets);

						const currEqLiabTotal = (currEquityTotal + currNonCurrLiabTotal + currCurrLiabTotal);
						const currAssetsTotal = (currNonCurrAssetTotal + currCurrAssetTotal);
						const currGrandTotal = (currEquityTotal + currNonCurrLiabTotal + currCurrLiabTotal + currNonCurrAssetTotal + currCurrAssetTotal);
							  
						document.getElementById('curr-equity-total').innerText = formatAmount(currEquityTotal);
						document.getElementById('curr-noncurr-liab-total').innerText = formatAmount(currNonCurrLiabTotal);
						document.getElementById('curr-curr-liab-total').innerText = formatAmount(currCurrLiabTotal);							
						document.getElementById('curr-nonassets-total').innerText = formatAmount(currNonCurrAssetTotal);							
						document.getElementById('curr-assets-total').innerText = formatAmount(currCurrAssetTotal);
							
						document.getElementById('curr-eq-liab-total').innerText = formatAmount(currEqLiabTotal);
						document.getElementById('curr-total-assets').innerText = formatAmount(currAssetsTotal);
						document.getElementById('curr-grand-total').innerText = formatAmount(currGrandTotal);
                    
						//start currentYearData
						 $('#frmDate').text(formatDate(data.start_date));
						 $('#toDate').text(formatDate(data.end_date));
						 $('#curr-share-capital').text(formatAmount(curr.equity.share_capital));

                        /* ---------- Reserves and Surplus ---------- */              
                        $('#curr-reserves-surplus').text(formatAmount(curr.equity.reserves_surplus));
                        //$('#curr-retained-earnings').text(formatAmount(curr.equity.retained_earnings));
                        
                        /* ---------- Money Received Against Share Warrants ---------- */
   
                        //$('#curr-money-against-warrants').text(formatAmount(curr.equity.m_r_a_share_warrants));                     
                        
                        /* ---------- Long Term Borrowings ---------- */
                       
                        $('#curr-long-term-borrowings').text(formatAmount(curr.noncurrliab.long_term_borrowings));
                        /* ---------- Deferred Tax Liabilities (Net) ---------- */
                        
                        $('#curr-deferred-tax-liabilities').text(formatAmount(curr.noncurrliab.deferred_tax_liabilities));
                        /* ---------- Other Long Term Liabilities ---------- */
                        
                        $('#curr-other-long-term-liabilities').text(formatAmount(curr.noncurrliab.other_long_term_liabilities));
                        /* ---------- Long Term Provisions ---------- */
                        
                        $('#curr-long-term-provisions').text(formatAmount(curr.noncurrliab.long_term_provisions));

                        /* ----------current liabilities ---------- */
                        $('#curr-borrowing-cc').text(formatAmount(curr.currliab.borrowing)); 
                        $('#curr-trade-payables').text(formatAmount(curr.currliab.trade_payables)); 
						$('#curr-statutory').text(formatAmount(curr.currliab.statutory)); 
						$('#curr-expense').text(formatAmount(curr.currliab.expense)); 
						$('#curr-advance').text(formatAmount(curr.currliab.advance)); 
						$('#curr-provision').text(formatAmount(curr.currliab.provision));  
						$('#curr-other-current-liabilities').text(formatAmount(curr.currliab.other_current_liabilities));
						
                        $('#curr-short-term-borrowings').text(formatAmount(curr.currliab.short_term_borrowings));
                        $('#curr-advances-from-customers').text(formatAmount(curr.currliab.advances_from_customers));
                        $('#curr-stat-dus-payable').text(formatAmount(curr.currliab.statutory_dues_payable));
                        $('#curr-tds-payable').text(formatAmount(curr.currliab.tds_payable));
                        $('#curr-emi-payables').text(formatAmount(curr.currliab.emi_payables));
                        $('#curr-accrued-expenses').text(formatAmount(curr.currliab.accrued_expenses_income));
                        $('#curr-royalty-payables').text(formatAmount(curr.currliab.royalty_payables));
                        $('#curr-gst-payable').text(formatAmount(curr.currliab.gst_payable));
                        $('#curr-unearned-revenue').text(formatAmount(curr.currliab.unearned_revenue));
                        $('#curr-security-deposits-payable').text(formatAmount(curr.currliab.security_deposits_payable));                        
                        $('#curr-short-term-provisions').text(formatAmount(curr.currliab.short_term_provisions));
	
                        /* ---------- Total Equity and Liabilities ---------- */
                        
                        $('#curr-total-equity-liabilities').text(formatAmount(curr.total_equity_and_liabilities));
                        /* ---------- ASSETS ---------- */
                        
                        $('#curr-fixed-assets').text(formatAmount(curr.noncurrassets.fixed_assets));                        
                        $('#curr-tangible-assets').text(formatAmount(curr.noncurrassets.tangible_assets));                        
                        $('#curr-intangible-assets').text(formatAmount(curr.noncurrassets.intangible_assets));                        
                        $('#curr-capital-wip').text(formatAmount(curr.noncurrassets.capital_wip_under_development));                        
                        $('#curr-non-current-investments').text(formatAmount(curr.noncurrassets.non_current_investments));                     
                        $('#curr-deferred-tax-assets').text(formatAmount(curr.noncurrassets.deferred_tax_assets));                       
                        $('#curr-long-term-loans-advances').text(formatAmount(curr.noncurrassets.long_term_loans_and_advances));
                        $('#curr-investments').text(formatAmount(curr.noncurrassets.investments));
                        $('#curr-loans-advances').text(formatAmount(curr.noncurrassets.loans_and_advances));
                        $('#curr-other-non-current-assets').text(formatAmount(curr.noncurrassets.other_non_current_assets));

                        /* ---------- Total Non-Current Assets ---------- */
               
                        $('#curr-total-non-current-assets').text(formatAmount(curr.total_non_current_assets));

						$('#curr-short-term-loans-and-advances').text(formatAmount(curr.currassets.short_term_loans_and_advances));
						$('#curr-interest-accrued-but-not-due').text(formatAmount(curr.currassets.interest_accrued_but_not_due));
						$('#curr-prepaid-expenses').text(formatAmount(curr.currassets.prepaid_expenses));
						$('#curr-group-company-receivables').text(formatAmount(curr.currassets.group_company_receivables));
						$('#curr-grant-subsidy-receivables').text(formatAmount(curr.currassets.grant_subsidy_receivables));
						$('#curr-deferred-revenue').text(formatAmount(curr.currassets.deferred_revenue));
						$('#curr-royalty-receivables').text(formatAmount(curr.currassets.royalty_receivables));
						$('#curr-inventories').text(formatAmount(curr.currassets.inventories));
						$('#curr-trade-receivables').text(formatAmount(curr.currassets.trade_receivables));  
						$('#curr-cashBank').text(formatAmount(curr.currassets.cash_bank));  
						$('#curr-other-current-assets').text(formatAmount(curr.currassets.other_current_assets));

                        $('#curr-current-investments').text(formatAmount(curr.currassets.current_investments));                             
                        $('#curr-cash-and-cash-equivalents').text(formatAmount(curr.currassets.cash_and_cash_equivalents));				
						$('#curr-advances-to-vendors').text(formatAmount(curr.currassets.advances_to_vendors));
						$('#curr-unbilled-revenue').text(formatAmount(curr.currassets.unbilled_revenue));
						$('#curr-gst-receivable').text(formatAmount(curr.currassets.gst_receivable));
						$('#curr-tds-receivable').text(formatAmount(curr.currassets.tds_receivable));
						$('#curr-work-in-progress').text(formatAmount(curr.currassets.work_in_progress));
						
						//////////start previousYearData//////////////
                        $('#prev-share-capital').text(formatAmount(prev.equity.share_capital));

                        /* ---------- Reserves and Surplus ---------- */              
                        $('#prev-reserves-surplus').text(formatAmount(prev.equity.reserves_surplus));
                        $('#prev-retained-earnings').text(formatAmount(prev.equity.retained_earnings));
                        
                        /* ---------- Money Received Against Share Warrants ---------- */
   
                        $('#prev-money-against-warrants').text(formatAmount(prev.equity.m_r_a_share_warrants));                     
                        
                        /* ---------- Long Term Borrowings ---------- */
                       
                        $('#prev-long-term-borrowings').text(formatAmount(prev.noncurrliab.long_term_borrowings));
                        /* ---------- Deferred Tax Liabilities (Net) ---------- */
                        
                        $('#prev-deferred-tax-liabilities').text(formatAmount(prev.noncurrliab.deferred_tax_liabilities));
                        /* ---------- Other Long Term Liabilities ---------- */
                        
                        $('#prev-other-long-term-liabilities').text(formatAmount(prev.noncurrliab.other_long_term_liabilities));
                        /* ---------- Long Term Provisions ---------- */
                        
                        $('#prev-long-term-provisions').text(formatAmount(prev.noncurrliab.long_term_provisions));
                        
                        //previous current liabilities
                        $('#prev-borrowing-cc').text(formatAmount(prev.currliab.borrowing)); 
                        $('#prev-trade-payables').text(formatAmount(prev.currliab.trade_payables)); 
						$('#prev-statutory').text(formatAmount(prev.currliab.statutory)); 
						$('#prev-expense').text(formatAmount(prev.currliab.expense)); 
						$('#prev-advance').text(formatAmount(prev.currliab.advance)); 
						$('#prev-provision').text(formatAmount(prev.currliab.provision));  
						$('#prev-other-current-liabilities').text(formatAmount(prev.currliab.other_current_liabilities));
						
                        $('#prev-short-term-borrowings').text(formatAmount(prev.currliab.short_term_borrowings));
                        $('#prev-advances-from-customers').text(formatAmount(prev.currliab.advances_from_customers));
                        $('#prev-stat-dus-payable').text(formatAmount(prev.currliab.statutory_dues_payable));                        
                        $('#prev-tds-payable').text(formatAmount(prev.currliab.tds_payable));
                        $('#prev-emi-payables').text(formatAmount(prev.currliab.emi_payables));
                        $('#prev-accrued-expenses').text(formatAmount(prev.currliab.accrued_expenses_income));
                        $('#prev-royalty-payables').text(formatAmount(prev.currliab.royalty_payables));
                        $('#prev-gst-payable').text(formatAmount(prev.currliab.gst_payable));
                        $('#prev-unearned-revenue').text(formatAmount(prev.currliab.unearned_revenue));
                        $('#prev-security-deposits-payable').text(formatAmount(prev.currliab.security_deposits_payable));
                        $('#prev-short-term-provisions').text(formatAmount(prev.currliab.short_term_provisions));
						
						

                        /* ---------- Total Equity and Liabilities ---------- */
                        
                        $('#prev-total-equity-liabilities').text(formatAmount(prev.total_equity_and_liabilities));
                        /* ---------- ASSETS ---------- */
                        
                        $('#prev-fixed-assets').text(formatAmount(prev.noncurrassets.fixed_assets));                        
                        $('#prev-tangible-assets').text(formatAmount(prev.noncurrassets.tangible_assets));                        
                        $('#prev-intangible-assets').text(formatAmount(prev.noncurrassets.intangible_assets));                        
                        $('#prev-capital-wip').text(formatAmount(prev.noncurrassets.capital_wip_under_development));                        
                        $('#prev-non-current-investments').text(formatAmount(prev.noncurrassets.non_current_investments));                     
                        $('#prev-deferred-tax-assets').text(formatAmount(prev.noncurrassets.deferred_tax_assets));                       
                        $('#prev-long-term-loans-advances').text(formatAmount(prev.noncurrassets.long_term_loans_and_advances));
                        $('#prev-investments').text(formatAmount(prev.noncurrassets.investments));
                        $('#prev-loans-advances').text(formatAmount(prev.noncurrassets.loans_and_advances));
                        $('#prev-other-non-current-assets').text(formatAmount(prev.noncurrassets.other_non_current_assets));

                        /* ---------- Total Non-Current Assets ---------- */
               
                        $('#prev-total-non-current-assets').text(formatAmount(prev.total_non_current_assets));
						$('#prev-short-term-loans-and-advances').text(formatAmount(prev.currassets.short_term_loans_and_advances));
						$('#prev-interest-accrued-but-not-due').text(formatAmount(prev.currassets.interest_accrued_but_not_due));
						$('#prev-prepaid-expenses').text(formatAmount(prev.currassets.prepaid_expenses));
						$('#prev-group-company-receivables').text(formatAmount(prev.currassets.group_company_receivables));
						$('#prev-grant-subsidy-receivables').text(formatAmount(prev.currassets.grant_subsidy_receivables));
						$('#prev-deferred-revenue').text(formatAmount(prev.currassets.deferred_revenue));
						$('#prev-royalty-receivables').text(formatAmount(prev.currassets.royalty_receivables));
						$('#prev-inventories').text(formatAmount(prev.currassets.inventories));
                        $('#prev-trade-receivables').text(formatAmount(prev.currassets.trade_receivables));  
                        $('#prev-cashBank').text(formatAmount(prev.currassets.cash_bank));  
						$('#prev-other-current-assets').text(formatAmount(prev.currassets.other_current_assets));						
						
                        $('#prev-current-investments').text(formatAmount(prev.currassets.current_investments));                       
                        $('#prev-cash-and-cash-equivalents').text(formatAmount(prev.currassets.cash_and_cash_equivalents));			
						$('#prev-advances-to-vendors').text(formatAmount(prev.currassets.advances_to_vendors));
						$('#prev-unbilled-revenue').text(formatAmount(prev.currassets.unbilled_revenue));
						$('#prev-gst-receivable').text(formatAmount(prev.currassets.gst_receivable));
						$('#prev-tds-receivable').text(formatAmount(prev.currassets.tds_receivable));
						$('#prev-work-in-progress').text(formatAmount(prev.currassets.work_in_progress));
                                    

                            
						$('#find_to_date').text(formatDate(data.end_date));
						$('#find_from_date').text(formatDate(data.start_date));

						$('#totalReseller').text(parseFloat(data.totalReseller).toFixed(2));
						$('#totalService').text(parseFloat(data.totalService).toFixed(2));
						$('#totalInterestIncome').text(parseFloat(data.totalInterestIncome).toFixed(2));
						$('#totalDividendIncome').text(parseFloat(data.totalDividendIncome).toFixed(2));
						$('#totalRentalIncome').text(parseFloat(data.totalRentalIncome).toFixed(2));
						$('#totalProfitOnSale').text(parseFloat(data.totalProfitOnSale).toFixed(2));
						$('#totalOtherIncome').text(parseFloat(data.totalOtherIncome).toFixed(2));
						$('#total_sales_income').text(parseFloat(data.total_sales_income).toFixed(2));
						$('#totalPurchaseTotal').text(parseFloat(data.totalPurchaseTotal).toFixed(2));
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
            generateButton.classList.remove('d-none');
        });
</script>

@endsection