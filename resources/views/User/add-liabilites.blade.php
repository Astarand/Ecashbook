@extends('App.Layout')

@section('container')

<!-- Add Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Add Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Liabilities & Borrowings</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/liabilites-list')}}">Liabilities & Borrowing List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Liability & Borrowing</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-liabilities-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.95rem;">
                        <u>How does this Page works?</u>
                    </a>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Add New Liabilities & Borrowings</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <form action="javascript:void(0);" method="post" name="addFrmLiabilities" id="addFrmLiabilities" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="liabId" id="liabId" value="">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 mb-3">
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
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Date<span class="text-danger">*</span></label>
                                    <input type="date" id="added_date" required name="added_date" class="form-control" placeholder="Enter Date">
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Liabilites Type<span class="text-danger">*</span></label>
                                    <select class="form-select" id="liabilitiesType" name="liabilitiesType" required>
                                        <option value="">Select Liabilites Type</option>
                                        <option value="share_holder_fund">Share Holder's Funds</option>
                                        <option value="share_application_money">Share Application Money Pending Allotment</option>
                                        <option value="non_current_liabilities">Non-Current Liabilities</option>
                                        <option value="current_liabilities">Current Liabilities</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
					
                    <div class="card" id="shareHolderFundSection" style="display:none;">
                        <div class="card-body">
                            <div class="row" id="shareHolderFund">
                                <div class="col-xl-12 mb-3">
                                    <label class="form-label">Share Holder's Funds<span class="text-danger">*</span></label>
                                    <select class="form-select" id="shareHolderFundType" name="shareHolderFundType">
                                        <option value="">Select Share Holder's Funds</option>
                                        <option value="share_capital">Share Capital</option>
                                        <option value="reserves_surplus">Reserves & Surplus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="shareCapitalSection" >
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Share Capital Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="shareHolderType" name="shareHolderType">
                                        <option value="">Select Share Holder's Funds Type</option>
                                        <option value="equity_share_capital">Equity Share Capital  </option>
                                        <option value="preference_share_capital">Preference Share Capital</option>
                                        <option value="share_premium">Share Premium</option>
                                    </select>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Class of Shares</label>
                                    <input type="text" id="classofshares" name="classofshares" class="form-control" placeholder="Class of Shares.">
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">No. of Shares Issued</label>
                                    <input type="text" id="sharesissued" name="sharesissued" class="form-control" placeholder="Class of Shares.">
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Face Value per Share</label>
                                    <input type="text" id="facevaluepershare" name="facevaluepershare" class="form-control" placeholder="Enter Value Amount" >
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Premium Amount (if any).</label>
                                    <input type="text" id="premiumamount" name="premiumamount" class="form-control" placeholder="Enter Premium Amount.">
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="text" id="totalamount" name="totalamount" class="form-control" placeholder="Enter Total Amount.">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Allotment Date-DD/MM/YYYY</label>
                                    <input type="date" id="allotmentDate" name="allotmentDate" class="form-control" placeholder="Enter Allotment Date">
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Mode of Payment</label>
                                    <select class="form-select" id="transactionMethod" name="transactionMethod">
                                        <option value="">Select Mode of Payment</option>
                                        <option value="cash">Cash</option>
                                        <option value="upi">UPI</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="imps">IMPS</option>
                                        <option value="rtgs">RTGS</option>
                                        <option value="neft">NEFT</option>
                                    </select>
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Share Certificate No.</label>
                                    <input type="text" id="sharecertificateno" name="sharecertificateno" class="form-control" placeholder="Enter Premium Amount.">
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Shareholder Ledger	(Name, PAN, Address, % Holding	shareholding to individuals)</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter description"></textarea>
                                </div>
                                <div class="col-md-6 mb-3 mt-4">
                                    <label class="upload-area" style="max-width: 100%;">
                                        <span class="upload-text {{ !empty($compDetails->inc_certificate) ? 'hidden' : '' }}">
                                            Click to Upload Board Resolution Ref
                                        </span>
                                        <input type="file" class="fileInput" id="share_holder_fund_image" name="share_holder_fund_image" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                                        <div class="file-preview-container">
                                            @if (!empty($compDetails->inc_certificate))
                                            @php
                                            $filePath = asset('storage/company_files/' . $compDetails->inc_certificate);
                                            @endphp
                                            <div class="file-preview">
                                                <div class="file-info">
                                                    <div class="file-name">{{ $compDetails->inc_certificate }}</div>
                                                    <div class="file-size">Uploaded File</div>
                                                </div>
                                                <!-- Force Download Instead of Opening -->
                                                <a href="{{ $filePath }}" download="{{ $compDetails->inc_certificate }}" class="btn btn-success btn-sm">
                                                    Download
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="row" id="reservesSurplusSection">
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Reserves Surplus Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="reserves_surplus_type" name="reserves_surplus_type">
                                        <option value="">Select Reserves Surplus Type</option>
                                        <option value="opening_balance">Opening balance  </option>
                                        {{-- <option value="equity_share_capital">Opening balance  </option> --}}
                                        <option value="dividend_declaration">Dividend Declaration  </option>
                                        <option value="transfer_to_reserve">Transfer to Reserve  </option>
                                        {{-- <option value="preference_share_capital">Current year addition</option> --}}
                                        {{-- <option value="share_application_money">Transfers to general reserve</option> --}}
                                        {{-- <option value="forfeited_shares">Dividend distribution</option> --}}
                                    </select>
                                </div>

                                <!-- Equity Share Capital Fields -->
                                <div id="equityShareCapitalFields" style="display:none; width:100%;">
                                    <div class="row">
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Opening Balance </label>
                                            <input type="text" id="opening_balance" name="opening_balance"  class="form-control" placeholder="Opening Balance " readonly>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" id="surplusdate" name="surplusdate" class="form-control" placeholder="Amount for Share ">
                                        </div>
                                        {{-- <div class="col-xl-3 mb-3">
                                            <label class="form-label">Amount</label>
                                            <input type="text" id="amountForsurplus" name="amountForsurplus" class="form-control" placeholder="Amount for Share ">
                                        </div> --}}
                                    </div>
                                </div>

                                <!-- Dividend Declaration Fields -->
                                <div id="dividendDeclarationFields" style="display:none; width:100%;">
                                    <div class="row">
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Declaration Date </label>
                                            <input type="date" id="declaration_date" name="declaration_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Financial Year </label>
                                            <input type="text" id="dividend_financial_year" name="dividend_financial_year" class="form-control" placeholder="e.g., 2025-2026">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Total Dividend Amount </label>
                                            <input type="number" id="total_dividend_amount" name="total_dividend_amount" class="form-control" placeholder="Enter amount">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Dividend Type </label>
                                            <select class="form-select" id="dividend_type" name="dividend_type">
                                                <option value="">Select Type</option>
                                                <option value="final">Final</option>
                                                <option value="interim">Interim</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Payment Due Date </label>
                                            <input type="date" id="dividend_payment_due_date" name="dividend_payment_due_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Payment Status </label>
                                            <select class="form-select" id="dividend_payment_status" name="dividend_payment_status">
                                                <option value="">Select Status</option>
                                                <option value="unpaid">Unpaid</option>
                                                <option value="paid">Paid</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea class="form-control" id="dividend_remarks" name="dividend_remarks" rows="3" placeholder="Enter remarks"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transfer to Reserve Fields -->
                                <div id="transferToReserveFields" style="display:none; width:100%;">
                                    <div class="row">
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Transfer Date </label>
                                            <input type="date" id="transfer_date" name="transfer_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Financial Year </label>
                                            <input type="text" id="transfer_financial_year" name="transfer_financial_year" class="form-control" placeholder="e.g., 2025-2026">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Transfer Amount </label>
                                            <input type="number" id="transfer_amount" name="transfer_amount" class="form-control" placeholder="Enter amount">
                                        </div>
                                        <div class="col-xl-3 mb-3">
                                            <label class="form-label">Reserve Type </label>
                                            <select class="form-select" id="reserve_type" name="reserve_type">
                                                <option value="">Select Reserve Type</option>
                                                <option value="general_reserve">General Reserve</option>
                                                <option value="capital_reserve">Capital Reserve</option>
                                                <option value="specific_reserve">Specific Reserve</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea class="form-control" id="transfer_remarks" name="transfer_remarks" rows="3" placeholder="Enter remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="shareApplicationMoneySection" style="display:none;">
                        <div class="card-body">
                            {{-- <div class="row" id="shareApplicationMoney">
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Pending For</label>
                                    <input type="text" id="pendingFor" name="pendingFor" class="form-control" placeholder="Pending For">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Amount for Share</label>
                                    <input type="text" id="amountForShare" name="amountForShare" class="form-control" placeholder="Amount for Share ">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">No. For Share</label>
                                    <input type="text" id="numberForShare" name="numberForShare" class="form-control" placeholder="Number For Share">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Reason for Delay</label>
                                    <input type="text" id="reasonForDelay" name="reasonForDelay" class="form-control" placeholder="Reason for Delay">
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Condition & Restriction</label>
                                    <textarea class="form-control" id="conditionRestriction" name="conditionRestriction" rows="5" placeholder="Enter Condition & Restriction"></textarea>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Special Note</label>
                                    <textarea class="form-control" id="specialNote" name="specialNote" rows="5" placeholder="Enter Special Note"></textarea>
                                </div>
                            </div> --}}
                            <div class="row" id="shareApplicationMoney">
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Applicant Name</label>
                                    <input type="text" name="applicant_name" class="form-control" placeholder="Enter Applicant Name">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">PAN (Optional)</label>
                                    <input type="text" name="pan" class="form-control" placeholder="Enter PAN">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Amount Received</label>
                                    <input type="text" name="amount_received" class="form-control" placeholder="Enter Amount">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Date of Received</label>
                                    <input type="date" name="date_received" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Mode of Payment</label>
                                    <select name="payment_mode" class="form-select">
                                        <option value="">Select Mode</option>
                                        <option value="cash">Cash</option>
                                        <option value="upi">UPI</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="neft">NEFT</option>
                                        <option value="rtgs">RTGS</option>
                                        <option value="imps">IMPS</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="Enter Bank Name">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">No. of Shares</label>
                                    <input type="text" name="no_of_shares" class="form-control" placeholder="Enter No. of Shares">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Face Value</label>
                                    <input type="text" name="face_value" class="form-control" placeholder="Enter Face Value">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Premium</label>
                                    <input type="text" name="premium" class="form-control" placeholder="Enter Premium">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Allotment Status</label>
                                    <select name="allotment_status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="allotted">Allotted</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Allotment Date</label>
                                    <input type="date" name="allotment_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" id="nonCurrentLiabilitiesSection" style="display:none;">
                        <div class="card-body">
                            <!-- Non Current Liabilities Section -->
                            <div id="nonCurrentLiabilities" style="display:none;">
                                <div class="row">

                                    <div class="col-xl-4 mb-3">
                                        <label class="form-label">Select Non-Current Liabilities <span class="text-danger">*</span></label>
                                        <select class="form-select mb-3" id="non_current_liabilities_type" name="non_current_liabilities_type">
                                            <option value="">Select Non-Current Liabilities Type</option>
                                            <option value="long_term_borrowings">Long-term Borrowings</option>
                                            <option value="other_financial_liabilities">Other Financial Liabilities</option>
                                            <option value="long_term_provisions">Long-term Provisions</option>
                                            <option value="deferred_tax_liabilities">Deferred Tax Liabilities</option>
                                            <option value="other_non_current_liabilities">Other Non-Current Liabilities</option>
                                            {{-- <option value="deferred_tax_liabilities">Deferred Tax (TDL)</option>
                                            <option value="provision">Provision</option>
                                            <option value="other_non_current_liabilities">Other Non Current Liabilities</option> --}}
                                        </select>
                                    </div>
                                    

                                    {{-- Generic NONC fields (hidden for deferred_tax_liabilities) --}}
                                    <div id="genericNoncFields">
                                        <div class="row">
                                            <div class="col-xl-4 mb-3 form-group" id="otherNonCurrentLiabilitiesTextContainer" style="display:none;">
                                                <label class="form-label">Other Non Current Liabilities (specify)<span class="text-danger">*</span></label>
                                                <input type="text" id="other_non_current_liabilities_text" name="other_non_current_liabilities_text" class="form-control" placeholder="Enter Other Non Current Liabilities details">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Category Of Head</label>
                                                <input type="text" id="category_of_head_nonc" name="category_of_head_nonc" class="form-control" placeholder="Enter category of head">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Party Name <span class="text-danger">*</span></label>
                                                <input type="text" id="party_name_nonc" name="party_name_nonc" class="form-control" placeholder="Enter party name">
                                            </div>

                                            {{-- <div class="col-xl-4 mb-3">
                                                <label class="form-label">Voucher Type<span class="text-danger">*</span></label>
                                                <select id="voucher_type_nonc" class="form-select" name="voucher_type_nonc">
                                                    <option value="">Select Type</option>
                                                    <option value="Journal">Journal</option>
                                                    <option value="Purchase">Purchase</option>
                                                    <option value="Sales">Sales</option>
                                                    <option value="Payment">Payment</option>
                                                    <option value="Receipt">Receipt</option>
                                                    <option value="Contra">Contra</option>
                                                </select>
                                            </div> --}}

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Amount<span class="text-danger">*</span></label>
                                                <input type="number" id="amt_nonc" name="amt_nonc" class="form-control" placeholder="Enter amount">
                                            </div>

                                            {{-- <div class="col-xl-4 mb-3">
                                                <label class="form-label">Debit/Credit<span class="text-danger">*</span></label>
                                                <select id="debitcredit_nonc" class="form-select" name="debitcredit_nonc">
                                                    <option value="">Select Debit/Credit</option>
                                                    <option value="Debit">Debit</option>
                                                    <option value="Credit">Credit</option>
                                                </select>
                                            </div> --}}

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Due Date<span class="text-danger">*</span></label>
                                                <input type="date" id="due_date_nonc" name="due_date_nonc" class="form-control">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Reference No</label>
                                                <input type="text" id="invoice_no_nonc" name="invoice_no_nonc" class="form-control" placeholder="Enter Invoice Number">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Loan Type</label>
                                                <select id="loan_type_nonc" class="form-select" name="loan_type_nonc">
                                                    <option value="">Select Loan Type</option>
                                                    <option value="Secured">Secured</option>
                                                    <option value="Unsecured">Unsecured</option>
                                                </select>
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Interest</label>
                                                <input type="text" id="interest_rate_nonc" name="interest_rate_nonc" class="form-control" placeholder="Enter Interest">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">MSME Tag</label>
                                                <input type="text" id="msme_tag_nonc" name="msme_tag_nonc" class="form-control" placeholder="Enter MSME Tag">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Attachment</label>
                                                <input type="file" id="attachment_nonc" name="attachment_nonc" class="form-control">
                                            </div>

                                            <div class="col-xl-12 mb-3">
                                                <label class="form-label">Note</label>
                                                <input type="text" id="notes_nonc" name="notes_nonc" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Deferred Tax Liabilities Section --}}
                                    <div id="deferredTaxSection" style="display:none;">
                                        <div class="row">
                                            <div class="col-xl-12 mb-2">
                                                <h6 class="text-primary fw-semibold border-bottom pb-2">Deferred Tax Liabilities (DTL)</h6>
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Difference in Accounting <span class="text-danger">*</span></label>
                                                <input type="text" name="dtl_difference_accounting" id="dtl_difference_accounting" class="form-control" placeholder="Enter Difference in Accounting">
                                            </div>

                                            <div class="col-xl-4 mb-3">
                                                <label class="form-label">Deferred Tax Liability (DTL) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="dtl_amount" id="dtl_amount" class="form-control" placeholder="Enter DTL Amount">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" id="currentLiabilitiesSection" style="display:none;">
                        <div class="card-body">
                            {{-- Current Liabilities Section --}}
                            <div class="row" id="currentLiabilities">
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Current Liabilities Type<span class="text-danger">*</span></label>
                                    <select class="form-select" id="CurrentLiabilitiesType" name="CurrentLiabilitiesType">
                                        <option value="">Select Current Liabilities Type</option>
                                        <option value="trade_payables">Trade Payables (Creditors)</option>
                                        <option value="advance_from_customer">Advance from Customer</option>
                                        <option value="outstanding_expenses">Outstanding Expenses</option>
                                        <option value="salary_payable">Salary Payable</option>
                                        <option value="gst_payable">GST Payable</option>
                                        <option value="tds_payable">TDS Payable</option>
                                        <option value="pf_payable">PF Payable</option>
                                        <option value="esi_payable">ESI Payable</option>
                                        <option value="lwf_payable">LWF Payable</option>
                                        <option value="short_term_loans">Short-term Loans</option>
                                        <option value="interest_payable">Interest Payable</option>
                                    </select>
                                </div>

                                {{-- ===== Simple payable sections (amount + link button) ===== --}}

                                {{-- Trade Payables --}}
                                <div id="clSection_trade_payables" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_trade_payables" id="cl_amount_trade_payables" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/purchase-order') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View Purchase Orders
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Advance from Customer --}}
                                <div id="clSection_advance_from_customer" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_advance_from_customer" id="cl_amount_advance_from_customer" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/sale-invoices') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View Sale Invoices
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Outstanding Expenses --}}
                                <div id="clSection_outstanding_expenses" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_outstanding_expenses" id="cl_amount_outstanding_expenses" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/expenses-list') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View Expenses
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Salary Payable --}}
                                <div id="clSection_salary_payable" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_salary_payable" id="cl_amount_salary_payable" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/expenses-list') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View Expenses
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- GST Payable --}}
                                <div id="clSection_gst_payable" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_gst_payable" id="cl_amount_gst_payable" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/gst-returns') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View GST Returns
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- TDS Payable --}}
                                <div id="clSection_tds_payable" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_tds_payable" id="cl_amount_tds_payable" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/tds-returns-filing') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View TDS Returns
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- PF Payable --}}
                                <div id="clSection_pf_payable" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_pf_payable" id="cl_amount_pf_payable" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/pf-management-list') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View PF Management
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- ESI Payable --}}
                                <div id="clSection_esi_payable" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_esi_payable" id="cl_amount_esi_payable" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/esi-management-list') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View ESI Management
                                            </a>
                                        </div>
                                    </div>
                                </div>
								
								{{-- LWF Payable --}}
                                <div id="clSection_lwf_payable" style="display:none;" class="col-xl-12">
                                    <div class="row align-items-end">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="cl_amount_lwf_payable" id="cl_amount_lwf_payable" class="form-control" placeholder="Enter Amount">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <a href="{{ url('/payroll-reports') }}" target="_blank" class="btn btn-outline-primary w-100">
                                                <i class="ti ti-external-link me-1"></i> View Payroll Reports
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Generic fields (for remaining types) --}}
                                {{-- <div id="genericClFields">
                                    <div class="row">
                                        <div class="col-xl-4 mb-3" id="otherCurrentLiabilityTextContainer" style="display:none;">
                                            <label class="form-label">Other Current Liability (specify)<span class="text-danger">*</span></label>
                                            <input type="text" id="other_current_liability_text" name="other_current_liability_text" class="form-control" placeholder="Enter Other Current Liability details">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Category Of Head</label>
                                            <input type="text" id="category_of_head_cl" name="category_of_head_cl" class="form-control" placeholder="Enter category of head">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Party Name</label>
                                            <input type="text" id="party_name_cl" name="party_name_cl" class="form-control" placeholder="Enter party name">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Voucher Type<span class="text-danger">*</span></label>
                                            <select id="voucher_type_cl" class="form-select" name="voucher_type_cl">
                                                <option value="">Select Type</option>
                                                <option value="Journal">Journal</option>
                                                <option value="Purchase">Purchase</option>
                                                <option value="Sales">Sales</option>
                                                <option value="Payment">Payment</option>
                                                <option value="Receipt">Receipt</option>
                                                <option value="Contra">Contra</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount<span class="text-danger">*</span></label>
                                            <input type="number" id="amt_cl" name="amt_cl" class="form-control" placeholder="Enter amount">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Debit/Credit<span class="text-danger">*</span></label>
                                            <select id="debitcredit_cl" class="form-select" name="debitcredit_cl">
                                                <option value="">Select Debit/Credit</option>
                                                <option value="Debit">Debit</option>
                                                <option value="Credit">Credit</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Due Date<span class="text-danger">*</span></label>
                                            <input type="date" id="due_date_cl" name="due_date_cl" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Invoice Number</label>
                                            <input type="text" id="invoice_no_cl" name="invoice_no_cl" class="form-control" placeholder="Enter Invoice Number">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Loan Type</label>
                                            <select id="loan_type_cl" class="form-select" name="loan_type_cl">
                                                <option value="">Select Loan Type</option>
                                                <option value="Secured">Secured</option>
                                                <option value="Unsecured">Unsecured</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest / Depreciation Rate</label>
                                            <input type="text" id="interest_rate_cl" name="interest_rate_cl" class="form-control" placeholder="Enter Interest / Depreciation Rate">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">MSME Tag</label>
                                            <input type="text" id="msme_tag_cl" name="msme_tag_cl" class="form-control" placeholder="Enter MSME Tag">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Attachment</label>
                                            <input type="file" id="attachment_cl" name="attachment_cl" class="form-control">
                                        </div>

                                        <div class="col-xl-12 mb-3">
                                            <label class="form-label">Note</label>
                                            <input type="text" id="notes_cl" name="notes_cl" class="form-control">
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- Short-term Loans Section --}}
                                <div id="shortTermLoansSection" style="display:none;">
                                    <div class="row">
                                        <div class="col-xl-12 mb-2">
                                            <h6 class="text-primary fw-semibold border-bottom pb-2">Short-term Loan Details</h6>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Loan ID / Loan Account No <span class="text-danger">*</span></label>
                                            <input type="text" name="stl_loan_id" id="stl_loan_id" class="form-control" placeholder="Enter Loan ID / Account No">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Lender Name <span class="text-danger">*</span></label>
                                            <input type="text" name="stl_lender_name" id="stl_lender_name" class="form-control" placeholder="Enter Lender Name">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Loan Type (Bank / NBFC / Director / Unsecured)</label>
                                            <select name="stl_loan_type" id="stl_loan_type" class="form-select">
                                                <option value="">Select Loan Type</option>
                                                <option value="Bank">Bank</option>
                                                <option value="NBFC">NBFC</option>
                                                <option value="Director">Director</option>
                                                <option value="Unsecured">Unsecured</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Secured / Unsecured</label>
                                            <select name="stl_secured_unsecured" id="stl_secured_unsecured" class="form-select">
                                                <option value="">Select</option>
                                                <option value="Secured">Secured</option>
                                                <option value="Unsecured">Unsecured</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Sanction Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="stl_sanction_amount" id="stl_sanction_amount" class="form-control" placeholder="Enter Sanction Amount">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Disbursement Date <span class="text-danger">*</span></label>
                                            <input type="date" name="stl_disbursement_date" id="stl_disbursement_date" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Amount Received</label>
                                            <input type="number" name="stl_amount_received" id="stl_amount_received" class="form-control" placeholder="Enter Amount Received">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Bank Account (where received)</label>
                                            <input type="text" name="stl_bank_account" id="stl_bank_account" class="form-control" placeholder="Enter Bank Account">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="stl_interest_rate" id="stl_interest_rate" class="form-control" placeholder="Enter Interest Rate">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="stl_interest_amount" id="stl_interest_amount" class="form-control" placeholder="Enter Interest Amount">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Type (Fixed / Floating)</label>
                                            <select name="stl_interest_type" id="stl_interest_type" class="form-select">
                                                <option value="">Select Interest Type</option>
                                                <option value="Fixed">Fixed</option>
                                                <option value="Floating">Floating</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Tenure (Months) <span class="text-danger">*</span></label>
                                            <input type="number" name="stl_tenure_months" id="stl_tenure_months" class="form-control" placeholder="Enter Tenure in Months">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Repayment Type <span class="text-danger">*</span></label>
                                            <select name="stl_repayment_type" id="stl_repayment_type" class="form-select">
                                                <option value="">Select Repayment Type</option>
                                                <option value="EMI">EMI</option>
                                                <option value="Bullet">Bullet</option>
                                                <option value="Flexible">Flexible</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">EMI Amount (if applicable) <span class="text-danger">*</span></label>
                                            <input type="number" name="stl_emi_amount" id="stl_emi_amount" class="form-control" placeholder="Enter EMI Amount">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Outstanding Principal <small class="text-muted">(Auto-calculated)</small></label>
                                            <input type="number" name="stl_outstanding_principal" id="stl_outstanding_principal" class="form-control" placeholder="Auto-calculated" readonly>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Next Due Date</label>
                                            <input type="date" name="stl_next_due_date" id="stl_next_due_date" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Maturity Date</label>
                                            <input type="date" name="stl_maturity_date" id="stl_maturity_date" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Reference (Loan Agreement No)</label>
                                            <input type="text" name="stl_reference" id="stl_reference" class="form-control" placeholder="Enter Loan Agreement No">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">MSME Related Party?</label>
                                            <select name="stl_msme_related" id="stl_msme_related" class="form-select">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-12 mb-3">
                                            <label class="form-label">Remarks</label>
                                            <textarea name="stl_remarks" id="stl_remarks" class="form-control" rows="3" placeholder="Enter Remarks"></textarea>
                                        </div>

                                        <div class="col-xl-12 mb-3">
                                            <label class="form-label">TDS Applicable on Interest?</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="stl_tds_applicable" id="stlTdsYes" value="yes">
                                                    <label class="form-check-label" for="stlTdsYes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="stl_tds_applicable" id="stlTdsNo" value="no" checked>
                                                    <label class="form-check-label" for="stlTdsNo">No</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="stlTdsFields" style="display:none;" class="col-xl-12 mb-3">
                                            <div class="row">
                                                <div class="col-xl-4 mb-3">
                                                    <label class="form-label">TDS Section (e.g., 194A)</label>
                                                    <input type="text" readonly name="stl_tds_section" id="stl_tds_section" class="form-control" placeholder="e.g., 194A">
                                                </div>
                                                <div class="col-xl-4 mb-3">
                                                    <label class="form-label">TDS Rate (%)</label>
                                                    <input type="number" readonly step="0.01" name="stl_tds_rate" id="stl_tds_rate" class="form-control" placeholder="Enter TDS Rate">
                                                </div>
                                                <div class="col-xl-4 mb-3">
                                                    <label class="form-label">TDS Amount <small class="text-muted">(Auto)</small></label>
                                                    <input type="number" name="stl_tds_amount" id="stl_tds_amount" class="form-control" placeholder="Auto-calculated" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- Interest Payable Section --}}
                                <div id="interestPayableSection" style="display:none;">
                                    <div class="row">
                                        <div class="col-xl-12 mb-2">
                                            <h6 class="text-primary fw-semibold border-bottom pb-2">Interest Payable Details</h6>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Loan ID <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select name="ip_loan_id" id="ip_loan_id" class="form-select">
                                                    <option value="">— Select Loan ID —</option>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary" id="ipLoanEditBtn" title="Edit / Enter manually">
                                                    <i class="ti ti-edit"></i> Edit
                                                </button>
                                            </div>
                                            <input type="text" name="ip_loan_id_manual" id="ip_loan_id_manual" class="form-control mt-1" placeholder="Or type Loan ID manually" style="display:none;">
                                            <small class="text-muted">Scroll to pick a Short-term or Long-term Loan. Details auto-fill on match.</small>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Lender Name</label>
                                            <input type="text" name="ip_lender_name" id="ip_lender_name" class="form-control" placeholder="Auto-filled from Loan">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Accrual Date <span class="text-danger">*</span></label>
                                            <input type="date" name="ip_accrual_date" id="ip_accrual_date" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Period From</label>
                                            <input type="date" name="ip_period_from" id="ip_period_from" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Period To</label>
                                            <input type="date" name="ip_period_to" id="ip_period_to" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="ip_interest_rate" id="ip_interest_rate" class="form-control" placeholder="Enter Interest Rate">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="ip_interest_amount" id="ip_interest_amount" class="form-control" placeholder="Enter Interest Amount">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Principal Amount <small class="text-muted">(Auto-fetch)</small></label>
                                            <input type="number" name="ip_principal_amount" id="ip_principal_amount" class="form-control" placeholder="Auto-fetched from Loan">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Days / Period</label>
                                            <input type="text" name="ip_days_period" id="ip_days_period" class="form-control" placeholder="e.g., 30 days">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Due Date</label>
                                            <input type="date" name="ip_due_date" id="ip_due_date" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Paid / Unpaid Status</label>
                                            <select name="ip_payment_status" id="ip_payment_status" class="form-select">
                                                <option value="">Select Status</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Unpaid">Unpaid</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Reference (Working / Sheet / Agreement)</label>
                                            <input type="text" name="ip_reference" id="ip_reference" class="form-control" placeholder="Enter Reference">
                                        </div>

                                        <div class="col-xl-12 mb-3">
                                            <label class="form-label">Narration</label>
                                            <textarea name="ip_narration" id="ip_narration" class="form-control" rows="3" placeholder="Enter Narration"></textarea>
                                        </div>

                                        <div class="col-xl-12 mb-3">
                                            <label class="form-label">TDS Applicable?</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ip_tds_applicable" id="ipTdsYes" value="yes">
                                                    <label class="form-check-label" for="ipTdsYes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="ip_tds_applicable" id="ipTdsNo" value="no" checked>
                                                    <label class="form-check-label" for="ipTdsNo">No</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="ipTdsFields" style="display:none;" class="col-xl-12 mb-3">
                                            <div class="row">
                                                <div class="col-xl-4 mb-3">
                                                    <label class="form-label">TDS Section (e.g., 194A)</label>
                                                    <input type="text" name="ip_tds_section" id="ip_tds_section" class="form-control" placeholder="e.g., 194A">
                                                </div>
                                                <div class="col-xl-4 mb-3">
                                                    <label class="form-label">TDS Rate (%)</label>
                                                    <input type="number" readonly step="0.01" name="ip_tds_rate" id="ip_tds_rate" class="form-control" placeholder="Enter TDS Rate">
                                                </div>
                                                <div class="col-xl-4 mb-3">
                                                    <label class="form-label">TDS Amount <small class="text-muted">(Auto)</small></label>
                                                    <input type="number" name="ip_tds_amount" id="ip_tds_amount" class="form-control" placeholder="Auto-calculated" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="text-end btn-page">
                                <a href="{{ url('/liabilites-list') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Liabilities</button>
                            </div>
                        </div>
                    </div>
    </form>
</div>

@endsection

@section('page-script')

<script src="{{asset('assets/js/plugins/choices.min.js') }}"></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Choices.js for dropdowns with search
        var genericExamples = document.querySelectorAll('[data-trigger]');
        for (i = 0; i < genericExamples.length; ++i) {
            var element = genericExamples[i];
            new Choices(element, {
                placeholderValue: 'Please Select',
                searchPlaceholderValue: 'Search Customer / Vendor Name'
            });
        }

        // Hide all liability sections initially
        $("#shareHolderFund").hide();
        $('#reservesSurplusSection').hide();
        $("#shareApplicationMoney").hide();
        $("#nonCurrentLiabilities").hide();
        $("#currentLiabilities").hide();
        $("#long_term_borrowings_type").hide();

        // Show/hide sections based on selected liability type
        $("#liabilitiesType").on('change', function() {
            // Hide all sections first
            $("#shareHolderFund").hide();
            $('#reservesSurplusSection').hide();
            $("#shareApplicationMoney").hide();
            $("#nonCurrentLiabilities").hide();
            $("#currentLiabilities").hide();
            $("#long_term_borrowings_type").hide();

            // Show the selected section
            var selectedValue = $(this).val();
            switch (selectedValue) {
                case "share_holder_fund":
                    $("#deferred_tax_liabilities").hide();
                    $("#long_term_provisions").hide();
                    $("#other_long_term_liabilities").hide();
                    $('#shareCapitalSection').hide();
                    $('#royalty_payables').hide();
                    $("#shareHolderFund").show();
                    break;
                case "share_application_money":
                    $("#deferred_tax_liabilities").hide();
                    $("#long_term_provisions").hide();
                    $("#other_long_term_liabilities").hide();
                    $('#royalty_payables').hide();
                    $("#shareApplicationMoney").show();
                    break;
                case "non_current_liabilities":
                    $("#deferred_tax_liabilities").hide();
                    $("#long_term_provisions").hide();
                    $("#other_long_term_liabilities").hide();
                    $('#royalty_payables').hide();
                    $("#nonCurrentLiabilities").show();
                    break;
                case "current_liabilities":
                    // alert("current liabilities selected");
                    $("#deferred_tax_liabilities").hide();
                    $("#long_term_provisions").hide();
                    $("#other_long_term_liabilities").hide();
                    $('#royalty_payables').hide();
                    $("#currentLiabilities").show();
                    break;
                default:
                    // Do nothing if no valid option is selected
                    break;
            }
        });
        // Listen to change on Non-Current Liabilities dropdown
        $("#non_current_liabilities_type").on('change', function() {
                var selectedValue = $(this).val();

                if (selectedValue === "long_term_borrowings") {
                    $("#long_term_borrowings_type").show();
                    $("#deferred_tax_liabilities").hide();
                    $("#other_long_term_liabilities").hide();
                    $("#long_term_provisions").hide();
                } else if (selectedValue === "deferred_tax_liabilities") {
                    $("#deferred_tax_liabilities").show();
                    $("#long_term_borrowings_type").hide();
                    $("#other_long_term_liabilities").hide();
                    $("#long_term_provisions").hide();
                } else if (selectedValue === "other_long_term_liabilities") {
                    $("#other_long_term_liabilities").show();
                    $("#long_term_borrowings_type").hide();
                    $("#deferred_tax_liabilities").hide();
                    $("#long_term_provisions").hide();
                } else if (selectedValue === "long_term_provisions") {
                    $("#long_term_provisions").show();
                    $("#long_term_borrowings_type").hide();
                    $("#deferred_tax_liabilities").hide();
                    $("#other_long_term_liabilities").hide();
                } else {
                    // Hide all subcategories if no valid selection
                    $("#long_term_borrowings_type").hide();
                    $("#deferred_tax_liabilities").hide();
                    $("#other_long_term_liabilities").hide();
                    $("#long_term_provisions").hide();
                }

            });

            // Trigger change on page load if already selected
            $("#non_current_liabilities_type").trigger('change');

        //});


        // Check if a liability type is already selected on page load
        var initialLiabilityType = $("#liabilitiesType").val();
        if (initialLiabilityType && initialLiabilityType !== "Select Liabilites Type") {
            $("#liabilitiesType").trigger('change');
        }

        // Form submission handler
        $("form#addFrmLiabilities").submit(function(e) {
            e.preventDefault();

            var liabId = $("#liabId").val();
            var surl = liabId == "" ? "/saveLiabilities" : "/updateLiabilities";

            // 🔎 Get values
            var added_date = $("#added_date").val().trim();
            var liabilitiesType = $("#liabilitiesType").val();

            // 🔴 Required Validation
            if (added_date === "") {
                showToast("Date is required", "error");
                $("#added_date").focus();
                return false;
            }

            if (liabilitiesType === "") {
                showToast("Liabilities Type is required", "error");
                $("#liabilitiesType").focus();
                return false;
            }

            var formData = new FormData(this); // ⭐ important for file upload
            $("#loader").show();
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                url: surl,
                type: "POST",
                data: formData,
                processData: false,  // ⭐ required
                contentType: false,  // ⭐ required
                success: function(response) {
                    $("#loader").hide();
                    if (response.class == "succ") {
                        showToast(response.message, "success");
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else {
                        $.each(response, function(idx, obj) {
                            showToast("Error: " + obj, "error");
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $("#loader").hide();
                    showToast("An error occurred: " + error, "error");
                }
            });
        });

    });

    $(document).ready(function () {
        $('#shareHolderFundType').on('change', function () {
            var selectedValue = $(this).val();
            //alert(selectedValue);
            if (selectedValue === 'share_capital') {
                $('#shareCapitalSection').show();
                $('#reservesSurplusSection').hide();
            } else if (selectedValue === 'reserves_surplus') {
                $('#reservesSurplusSection').show();
                $('#shareCapitalSection').hide();
                $("#shareApplicationMoney").hide();
                $("#nonCurrentLiabilities").hide();
                $("#currentLiabilities").hide();
                $("#long_term_borrowings_type").hide();
            } else {
                // Hide both if no valid selection
                $('#shareCapitalSection').hide();
                $('#reservesSurplusSection').hide();
                $("#shareApplicationMoney").hide();
                $("#nonCurrentLiabilities").hide();
                $("#currentLiabilities").hide();
                $("#long_term_borrowings_type").hide();
            }
        });

        // Handler for Reserves Surplus Type dropdown
        $('#reserves_surplus_type').on('change', function () {
            var selectedValue = $(this).val();

            // Hide all field sections initially
            $('#equityShareCapitalFields').hide();
            $('#dividendDeclarationFields').hide();
            $('#transferToReserveFields').hide();

            // Show the selected section based on the dropdown value
            // if (selectedValue === 'opening_balance') {
            //     $('#opening_balance').val('{{ $openingBalance }}');
            //     alert("Opening Balance: {{ $openingBalance }}");
            //     $('#equityShareCapitalFields').show();
            // } else if (selectedValue === 'dividend_declaration') {
            //     $('#dividendDeclarationFields').show();
            // } else if (selectedValue === 'transfer_to_reserve') {
            //     $('#transferToReserveFields').show();
            // }

            if (selectedValue === 'opening_balance') {

                $('#equityShareCapitalFields').show();

                $.ajax({
                    url: "{{ route('get.opening.balance') }}",
                    type: "GET",
                    success: function(response) {

                        if (response.status) {

                            $('#opening_balance').val(response.openingBalance);

                        } else {

                            $('#opening_balance').val('');

                            Swal.fire({
                                icon: 'warning',
                                title: 'Warning',
                                text: response.message
                            });

                        }

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

            } 
            else if (selectedValue === 'dividend_declaration') {

                $('#dividendDeclarationFields').show();

            } else if (selectedValue === 'transfer_to_reserve') {

                $('#transferToReserveFields').show();

            }
        });

        // Trigger the reserves_surplus_type change on page load if already selected
        $('#reserves_surplus_type').trigger('change');

        $('#CurrentLiabilitiesType').on('change', function () {
            // $("#deferred_tax_liabilities").hide();
            
            var selectedValue = $(this).val();

            if (selectedValue === 'Other Current Liability') {
                $('#otherCurrentLiabilityTextContainer').show();
            } else {
                $('#otherCurrentLiabilityTextContainer').hide();
                $('#other_current_liability_text').val('');
            }

            if (selectedValue === 'Short-term Borrowings') {
                //alert("Short-term Borrowings selected");
                $('#short_term_borrowings_section').show();
                $('#tradePayables_section').hide();
                $('#advances_from_customers').hide();
                $('#statutory_dues_payable').hide();
                $('#TDS_payable').hide();
                $('#EMI_payables').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            } else if (selectedValue === 'Trade Payables') {
                //alert("Trade Payables selected");
                $('#tradePayables_section').show();
                $('#short_term_borrowings_section').hide();
                $('#advances_from_customers').hide();
                $('#statutory_dues_payable').hide();
                $('#TDS_payable').hide();
                $('#EMI_payables').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            } else if (selectedValue === 'Advances from Customers'){
                $('#advances_from_customers').show();
                $('#tradePayables_section').hide();
                $('#short_term_borrowings_section').hide();
                $('#statutory_dues_payable').hide();
                $('#TDS_payable').hide();
                $('#EMI_payables').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            } else if(selectedValue === 'Statutory Dues Payable') {
                $('#statutory_dues_payable').show();
                $('#tradePayables_section').hide();
                $('#short_term_borrowings_section').hide();
                $('#advances_from_customers').hide();
                $('#TDS_payable').hide();
                $('#EMI_payables').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            } else if(selectedValue === 'TDS Payable') {
                $('#TDS_payable').show();
                $('#tradePayables_section').hide();
                $('#short_term_borrowings_section').hide();
                $('#advances_from_customers').hide();
                $('#statutory_dues_payable').hide();
                $('#EMI_payables').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            } else if(selectedValue === 'EMI Payables') {
                $('#EMI_payables').show();
                $('#tradePayables_section').hide();
                $('#short_term_borrowings_section').hide();
                $('#advances_from_customers').hide();
                $('#statutory_dues_payable').hide();
                $('#TDS_payable').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            } else if(selectedValue === 'Accrued Expenses / Income') {
                $('#accrued_expenses_income').show();
                $('#tradePayables_section').hide();
                $('#short_term_borrowings_section').hide();
                $('#advances_from_customers').hide();
                $('#statutory_dues_payable').hide();
                $('#TDS_payable').hide();
                $('#EMI_payables').hide();
            } else if(selectedValue === 'Royalty Payables') {
                //alert("Royalty Payables selected");
                $("#royalty_payables").show();
                $("#tradePayables_section").hide();
                $("#short_term_borrowings_section").hide();
                $("#advances_from_customers").hide();
                $("#statutory_dues_payable").hide();
                $("#TDS_payable").hide();
                $("#EMI_payables").hide();
                $("#accrued_expenses_income").hide();
                $("#GST_payable").hide();
                $("#unearned_revenue").hide();
            } else if(selectedValue === 'GST Payable') {
                //alert("GST Payable selected");
                $("#GST_payable").show();
                $("#tradePayables_section").hide();
                $("#short_term_borrowings_section").hide();
                $("#advances_from_customers").hide();
                $("#statutory_dues_payable").hide();
                $("#TDS_payable").hide();
                $("#EMI_payables").hide();
                $("#accrued_expenses_income").hide();
                $("#royalty_payables").hide();
                $("#unearned_revenue").hide();
            } else if(selectedValue === 'Unearned Revenue') {
                //alert("Unearned Revenue selected");
                $("#unearned_revenue").show();
                $("#tradePayables_section").hide();
                $("#short_term_borrowings_section").hide();
                $("#advances_from_customers").hide();
                $("#statutory_dues_payable").hide();
                $("#TDS_payable").hide();
                $("#EMI_payables").hide();
                $("#accrued_expenses_income").hide();
                $("#royalty_payables").hide();
                $("#GST_payable").hide();
            } else {
                // Hide all sections if no valid selection
                $('#short_term_borrowings_section').hide();
                $('#tradePayables_section').hide();
                $('#advances_from_customers').hide();
                $('#statutory_dues_payable').hide();
                $('#TDS_payable').hide();
                $('#EMI_payables').hide();
                $('#accrued_expenses_income').hide();
                $('#royalty_payables').hide();
                $('#GST_payable').hide();
                $('#unearned_revenue').hide();
            }
        });

        $('#non_current_liabilities_type').on('change', function() {
            var selectedValue = $(this).val();

            if (selectedValue === 'deferred_tax_liabilities') {
                // Hide generic fields, show DTL section
                $('#genericNoncFields').hide();
                $('#deferredTaxSection').show();
            } else {
                // Show generic fields, hide DTL section
                $('#genericNoncFields').show();
                $('#deferredTaxSection').hide();

                // Handle "Other" text box within generic fields
                if (selectedValue === 'other_non_current_liabilities') {
                    $('#otherNonCurrentLiabilitiesTextContainer').show();
                } else {
                    $('#otherNonCurrentLiabilitiesTextContainer').hide();
                    $('#other_non_current_liabilities_text').val('');
                }
            }
        });

        // Trigger on page load to set default visibility for current/non-current option text fields
        $('#CurrentLiabilitiesType').trigger('change');
        $('#non_current_liabilities_type').trigger('change');

        // ===== Short-term Loans & Interest Payable: show/hide logic =====

        // Simple payable types that only need amount + link button
        var simpleClTypes = [
            'trade_payables', 'advance_from_customer', 'outstanding_expenses',
            'salary_payable', 'gst_payable', 'tds_payable', 'pf_payable', 'esi_payable', 'lwf_payable'
        ];

        function hideAllClSections() {
            $.each(simpleClTypes, function (i, type) {
                $('#clSection_' + type).hide();
            });
            $('#genericClFields').hide();
            $('#shortTermLoansSection').hide();
            $('#interestPayableSection').hide();
        }

        function loadCurrentLiabilityAmount(type) {

            $.ajax({
                url: "{{ url('/get-current-liability-amount') }}",
                type: "GET",
                data: {
                    type: type
                },
                success: function (response) {

                    if (response.status) {
                        // console.log("Amount for " + type + ": " + response.amount);
                        
                        // Trade Payables
                        if (type == 'trade_payables') {
                            $('#cl_amount_trade_payables').val(response.amount);
                        }

                        // Advance from Customer
                        if (type == 'advance_from_customer') {
                            $('#cl_amount_advance_from_customer').val(response.amount);
                        }

                        // Outstanding Expenses
                        if (type == 'outstanding_expenses') {
                            $('#cl_amount_outstanding_expenses').val(response.amount);
                        }

                        // Salary Payable
                        if (type == 'salary_payable') {
                            $('#cl_amount_salary_payable').val(response.amount);
                        }

                        // PF Payable
                        if (type == 'pf_payable') {
                            $('#cl_amount_pf_payable').val(response.amount);
                        }

                        // ESI Payable
                        if (type == 'esi_payable') {
                            $('#cl_amount_esi_payable').val(response.amount);
                        }

                        // GST Payable
                        if (type == 'gst_payable') {
                            $('#cl_amount_gst_payable').val(response.amount);
                        }

                        // TDS Payable
                        if (type == 'tds_payable') {
                            $('#cl_amount_tds_payable').val(response.amount);
                        }
						
						// LWF Payable
                        if (type == 'lwf_payable') {
                            $('#cl_amount_lwf_payable').val(response.amount);
                        }
                    }
                }
            });
        }

        function handleClSpecialTypes(val) {

            hideAllClSections();

            if ($.inArray(val, simpleClTypes) !== -1) {

                $('#clSection_' + val).show();

                // Load amount dynamically
                loadCurrentLiabilityAmount(val);

            } else if (val === 'short_term_loans') {

                $('#shortTermLoansSection').show();
				
				$('#cl_amount_gst_payable').prop('required', false).val('');

            } else if (val === 'interest_payable') {

                $('#interestPayableSection').show();
                loadLoanIdsForIP();

            } else {

                $('#genericClFields').show();
            }
        }

        $('#CurrentLiabilitiesType').on('change', function () {
            handleClSpecialTypes($(this).val());
        });

        // Run on page load
        handleClSpecialTypes($('#CurrentLiabilitiesType').val());

        // STL: TDS toggle
        $('input[name="stl_tds_applicable"]').on('change', function () {
           // $('#stlTdsFields').toggle($(this).val() === 'yes');
        });

        // STL: Auto-calculate TDS Amount from interest amount × rate
        function calcStlTds() {
            var interest = parseFloat($('#stl_interest_amount').val()) || 0;
            var rate     = parseFloat($('#stl_tds_rate').val()) || 0;
            $('#stl_tds_amount').val((interest > 0 && rate > 0) ? ((interest * rate) / 100).toFixed(2) : '');
        }
        $('#stl_interest_amount, #stl_tds_rate').on('input', calcStlTds);

        // STL: Auto-calculate Outstanding Principal = Sanction - Amount Received
        function calcStlOutstanding() {
            var sanction = parseFloat($('#stl_sanction_amount').val()) || 0;
            var received = parseFloat($('#stl_amount_received').val()) || 0;
            $('#stl_outstanding_principal').val(sanction > 0 ? (sanction - received).toFixed(2) : '');
        }
        $('#stl_sanction_amount, #stl_amount_received').on('input', calcStlOutstanding);

        // ===== Interest Payable: Load Loan IDs (Short-term + Long-term) =====
        function loadLoanIdsForIP() {
            $.ajax({
                url: '/get-loan-ids',
                type: 'GET',
                success: function (res) {
                    var dropdown = $('#ip_loan_id');
                    dropdown.empty().append('<option value="">— Select Loan ID —</option>');
                    $.each(res, function (i, loan) {
                        dropdown.append(
                            $('<option>', {
                                value: loan.id,
                                text: loan.loan_id + ' — ' + loan.lender_name + ' (' + loan.loan_source + ')',
                                'data-lender':    loan.lender_name,
                                'data-principal': loan.sanction_amount,
                                'data-rate':      loan.interest_rate
                            })
                        );
                    });
                }
            });
        }

        // IP: Auto-fill fields when a Loan ID is selected from dropdown
        $('#ip_loan_id').on('change', function () {
            var sel = $(this).find(':selected');
            if (sel.val()) {
                $('#ip_lender_name').val(sel.data('lender') || '');
                $('#ip_principal_amount').val(sel.data('principal') || '');
                $('#ip_interest_rate').val(sel.data('rate') || '');
                $('#ip_loan_id_manual').hide().val('');
            }
        });

        // IP: Edit button — toggle manual input
        $('#ipLoanEditBtn').on('click', function () {
            var manual = $('#ip_loan_id_manual');
            if (manual.is(':visible')) {
                manual.hide().val('');
                $(this).html('<i class="ti ti-edit"></i> Edit');
            } else {
                manual.show().focus();
                $(this).html('<i class="ti ti-x"></i> Cancel');
                // Clear auto-filled fields when switching to manual
                $('#ip_lender_name, #ip_principal_amount, #ip_interest_rate').val('');
                $('#ip_loan_id').val('');
            }
        });

        // IP: TDS toggle
        $('input[name="ip_tds_applicable"]').on('change', function () {
            //$('#ipTdsFields').toggle($(this).val() === 'yes');
        });

        // IP: Auto-calculate TDS Amount
        function calcIpTds() {
            var interest = parseFloat($('#ip_interest_amount').val()) || 0;
            var rate     = parseFloat($('#ip_tds_rate').val()) || 0;
            $('#ip_tds_amount').val((interest > 0 && rate > 0) ? ((interest * rate) / 100).toFixed(2) : '');
        }
        $('#ip_interest_amount, #ip_tds_rate').on('input', calcIpTds);

        $('input[name="trade_nature"]').on('change', function () {
        $('.select-box').hide(); // hide both
        $('#' + $(this).val() + '_select').show(); // show the chosen one
            });

            // Run once on page load (to respect default "checked" radio)
            $('input[name="trade_nature"]:checked').trigger('change');


        $('input[name="accrued_nature"]').on('change', function () {
        $('.select-box').hide(); // hide both
        $('#' + $(this).val() + '_select').show(); // show the chosen one
            });

            // Run once on page load (to respect default "checked" radio)
            $('input[name="accrued_nature"]:checked').trigger('change');


    });

    $(document).ready(function () {

        // ✅ Function for Current Liabilities
        function toggleGST_CL() {
            var shouldShow = $('input[name="gst_applicable_cl"]:checked').val() === 'yes';
            $('.gst-field-cl').toggle(shouldShow);
        }

        // ✅ Function for Non Current Liabilities
        function toggleGST_NONC() {
            var shouldShow = $('input[name="gst_applicable_nonc"]:checked').val() === 'yes';
            $('.gst-field-nonc').toggle(shouldShow);
        }

        // ✅ On page load
        toggleGST_CL();
        toggleGST_NONC();

        // ✅ On change
        $('input[name="gst_applicable_cl"]').on('change', toggleGST_CL);
        $('input[name="gst_applicable_nonc"]').on('change', toggleGST_NONC);

    });

    $(document).ready(function () {
        const hideTopCards = function () {
            $('#shareHolderFundSection').hide();
            $('#shareApplicationMoneySection').hide();
            $('#nonCurrentLiabilitiesSection').hide();
            $('#currentLiabilitiesSection').hide();
        };

        const hideTopInnerSections = function () {
            $('#shareHolderFund').hide();
            $('#shareApplicationMoney').hide();
            $('#nonCurrentLiabilities').hide();
            $('#currentLiabilities').hide();
        };

        const hideShareHolderSubsections = function () {
            $('#shareCapitalSection').hide();
            $('#reservesSurplusSection').hide();
        };

        const toggleShareHolderSubsections = function () {
            hideShareHolderSubsections();

            const typeValue = $('#shareHolderFundType').val();
            if (typeValue === 'share_capital') {
                $('#shareCapitalSection').show();
            } else if (typeValue === 'reserves_surplus') {
                $('#reservesSurplusSection').show();
            }
        };

        const toggleByLiabilityType = function () {
            hideTopCards();
            hideTopInnerSections();
            hideShareHolderSubsections();

            const selectedValue = $('#liabilitiesType').val();
            if (selectedValue === 'share_holder_fund') {
                $('#shareHolderFundSection').show();
                $('#shareHolderFund').show();
                toggleShareHolderSubsections();
            } else if (selectedValue === 'share_application_money') {
                $('#shareApplicationMoneySection').show();
                $('#shareApplicationMoney').show();
            } else if (selectedValue === 'non_current_liabilities') {
                $('#nonCurrentLiabilitiesSection').show();
                $('#nonCurrentLiabilities').show();
            } else if (selectedValue === 'current_liabilities') {
                $('#currentLiabilitiesSection').show();
                $('#currentLiabilities').show();
            }
        };

        $('#liabilitiesType').off('change').on('change.logicfix', toggleByLiabilityType);
        $('#shareHolderFundType').off('change').on('change.logicfix', toggleShareHolderSubsections);

        toggleByLiabilityType();
    });

    document.addEventListener("DOMContentLoaded", function () {

        const sharesIssued = document.getElementById("sharesissued");
        const faceValue = document.getElementById("facevaluepershare");
        const premiumAmount = document.getElementById("premiumamount");
        const totalAmount = document.getElementById("totalamount");

        function calculateTotal() {
            let shares = parseFloat(sharesIssued.value) || 0;
            let face = parseFloat(faceValue.value) || 0;
            let premium = parseFloat(premiumAmount.value) || 0;

            let total = 0;

            if (shares > 0 && face > 0 && premium > 0) {
                total = (shares * face) + premium;
            } else if (shares > 0 && face > 0) {
                total = shares * face;
            } else if (premium > 0) {
                total = premium;
            }

            totalAmount.value = total.toFixed(2);
        }

        sharesIssued.addEventListener("input", calculateTotal);
        faceValue.addEventListener("input", calculateTotal);
        premiumAmount.addEventListener("input", calculateTotal);

    });
	
	//Start TDS calculate
	function checkTdsRule(module, category, amount, prefix)
	{
		$.ajax({
			url: "{{ route('get.tds.rule') }}",
			type: "POST",
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				module: module,
				category: category
			},
			success: function(res){

				if(!res.status || !res.rule){
					return;
				}

				let rule = res.rule;
				let threshold = parseFloat(rule.threshold_limit);
				amount = parseFloat(amount) || 0;

				if(amount >= threshold){

					$("input[name='"+prefix+"_tds_applicable'][value='yes']")
						.prop("checked", true);

					$("#"+prefix+"_tds_section")
						.val(rule.tds_section);

					$("#"+prefix+"_tds_rate")
						.val(rule.tds_rate);

					let tdsAmount = (amount * parseFloat(rule.tds_rate))/100;

					$("#"+prefix+"_tds_amount")
						.val(tdsAmount.toFixed(2));

					$("#"+prefix+"TdsFields").show();

				}else{

					$("input[name='"+prefix+"_tds_applicable'][value='no']")
						.prop("checked", true);

					$("#"+prefix+"_tds_section").val('');
					$("#"+prefix+"_tds_rate").val('');
					$("#"+prefix+"_tds_amount").val('');

					$("#"+prefix+"TdsFields").hide();
				}

			}
		});
	}
	
	$("#stl_interest_amount").on("input change", function(){
		checkTdsRule('Liability','short_term_loans',$(this).val(),'stl');
	});
	
	$("#ip_interest_amount").on("input change", function(){
		checkTdsRule('Liability','interest_payable',$(this).val(),'ip');
	});
	//End TDS calculate

    function startAddLiabilitiesTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add Liabilities Guide',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-plus" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Register new company liabilities, equity investments, or borrowings. Choose the type to open dynamic details forms.</p></div>'
                },
                {
                    element: '#liabilitiesType',
                    title: 'Liability Type Selector',
                    intro: 'Select between Shareholder Funds, Share Application Money, Non-Current Liabilities, or Current Liabilities.'
                },
                {
                    element: '#propId',
                    title: 'Proprietorship Entity',
                    intro: 'If applicable, select the specific proprietorship company registration to assign this liability.'
                },
                {
                    element: '#submitBtn',
                    title: 'Submit Liabilities Details',
                    intro: 'Click here to save the details and update the balance sheet ledger.'
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
        $('#start-add-liabilities-tour').on('click', function(e) {
            e.preventDefault();
            startAddLiabilitiesTour();
        });
    });
</script>
@endsection
