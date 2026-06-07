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
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Liabilities & Borrowings</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/liabilites-list')}}">Liabilities & Borrowing List</a></li>
                        <li class="breadcrumb-item" aria-current="page">View Liabilities</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">View Liabilities & Borrowings</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-4">
        <h3>View Liabilities</h3>
        <?php
        // echo "<pre>";print_r($liability);        
        // echo "<pre>";print_r($subDetails);        

        ?>
    </div>
    <div class="card">
        <div class="card-body">
            <?php
            // echo "<pre>"; print_r($liability); echo "</pre>"; 

            ?>

            <form action="javascript:void(0);" method="post" name="addFrmLiabilities" id="addFrmLiabilities" enctype="multipart/form-data">
                <input type="hidden" name="liabId" id="liabId" value="{{ $liability->id }}">
                <div class="row">
					<div class="col-xl-4 mb-3">
						<label class="form-label">Proprietorship Company</label>
						<select name="propId" class="form-control">
							<option value="">{{ parentCompanyName() }}</option>
							@foreach($proprietorships as $company)
								<option value="{{ $company->id }}" <?=($liability->propId == $company->id) ? 'selected' : '' ?>>
									{{ $company->comp_name }}
								</option>
							@endforeach
						</select>
					</div>
                    <div class="col-xl-4 mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" id="added_date" name="added_date" value="{{ $liability->added_date }}" class="form-control" placeholder="Enter Date" readonly>
                    </div>
                    <div class="col-xl-4 mb-3">
                        <label class="form-label">Liabilites Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="liabilitiesType" name="liabilitiesType" disabled>
                            <option value="">Select Liabilites Type <span class="text-danger">*</span></option>
                            <option value="share_holder_fund" {{ (isset($liability) && $liability->liabilities_type === 'share_holder_fund') ? 'selected' : '' }}>Share Holder's Funds</option>
                            <option value="share_application_money" {{ (isset($liability) && $liability->liabilities_type === 'share_application_money') ? 'selected' : '' }}>Share Application Money Pending Allotment</option>
                            <option value="non_current_liabilities" {{ (isset($liability) && $liability->liabilities_type === 'non_current_liabilities') ? 'selected' : '' }}>Non-Current Liabilities</option>
                            <option value="current_liabilities" {{ (isset($liability) && $liability->liabilities_type === 'current_liabilities') ? 'selected' : '' }}>Current Liabilities</option>
                        </select>
                        <input type="hidden" name="liabilitiesType" value="{{ $liability->liabilities_type }}">

                    </div>

                    <div class="row" id="shareHolderFund" >
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Share Holder''s Funds <span class="text-danger">*</span></label>
                            <select class="form-select" id="shareHolderFundType" name="shareHolderFundType">
                                <option value="">Select Share Holder's Funds</option>
                    
                    
                                <option value="share_capital" {{ (isset($subDetails->share_holder_fund_type) &&
                                    $subDetails->share_holder_fund_type == 'share_capital') ? 'selected' : '' }}>
                                    Share Capital
                                </option>
                    
                                <option value="reserves_surplus" {{ (isset($subDetails->share_holder_fund_type) &&
                                    $subDetails->share_holder_fund_type == 'reserves_surplus') ? 'selected' : '' }}>
                                    Reserves & Surplus
                                </option>
                            </select>
                        </div>
                        <div class="row" id="shareCapitalSection">
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Share Capital Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="shareHolderType" name="shareHolderType">
                                    <option value="">Select Share Holder's Funds Type</option>
                    
                                    <option value="equity_share_capital" {{ (isset($subDetails->share_holder_type) &&
                                        $subDetails->share_holder_type == 'equity_share_capital') ? 'selected' : '' }}>
                                        Equity Share Capital
                                    </option>
                    
                                    <option value="preference_share_capital" {{ (isset($subDetails->share_holder_type) &&
                                        $subDetails->share_holder_type == 'preference_share_capital') ? 'selected' : '' }}>
                                        Preference Share Capital
                                    </option>
                    
                                    <option value="share_premium" {{ (isset($subDetails->share_holder_type) &&
                                        $subDetails->share_holder_type == 'share_premium') ? 'selected' : '' }}>
                                        Share Premium
                                    </option>
                                </select>
                            </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Class of Shares</label>
                                <input type="text" id="classofshares" name="classofshares" class="form-control"
                                    value="{{ isset($subDetails->class_of_shares) ? $subDetails->class_of_shares : '' }}"
                                    placeholder="Class of Shares.">
                            </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">No. of Shares Issued</label>
                                <input type="text" id="sharesissued" name="sharesissued" class="form-control"
                                    value="{{ isset($subDetails->shares_issued) ? $subDetails->shares_issued : '' }}"
                                    placeholder="No. of Shares Issued">
                            </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Face Value per Share</label>
                                <input type="text" id="facevaluepershare" name="facevaluepershare" class="form-control"
                                    value="{{ isset($subDetails->face_value_per_share) ? $subDetails->face_value_per_share : '' }}"
                                    placeholder="Enter Value Amount">
                            </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Premium Amount (if any)</label>
                                <input type="text" id="premiumamount" name="premiumamount" class="form-control"
                                    value="{{ isset($subDetails->premium_amount) ? $subDetails->premium_amount : '' }}"
                                    placeholder="Enter Premium Amount.">
                            </div>
                            <div class="col-xl-4 mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="text" id="totalamount" name="totalamount" class="form-control" 
                                    value="{{ isset($subDetails->total_amount) ? $subDetails->total_amount : '' }}"
                                    placeholder="Enter Total Amount.">
                                </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Allotment Date</label>
                                <input type="date" id="allotmentDate" name="allotmentDate" class="form-control"
                                    value="{{ isset($subDetails->allotment_date) ? $subDetails->allotment_date : '' }}">
                            </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Mode of Payment</label>
                                <select class="form-select" id="transactionMethod" name="transactionMethod">
                                    <option value="">Select Mode of Payment</option>
                    
                                    @php
                                    $methods = ['cash','upi','cheque','credit_card','debit_card','imps','rtgs','neft'];
                                    @endphp
                    
                                    @foreach($methods as $method)
                                    <option value="{{ $method }}" {{ (isset($subDetails->transaction_method) &&
                                        $subDetails->transaction_method == $method) ? 'selected' : '' }}>
                                        {{ strtoupper($method) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Share Certificate No.</label>
                                <input type="text" id="sharecertificateno" name="sharecertificateno" class="form-control"
                                    value="{{ isset($subDetails->share_certificate_no) ? $subDetails->share_certificate_no : '' }}"
                                    placeholder="Enter Certificate No.">
                            </div>
                    
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">Shareholder Ledger</label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="Enter description">{{ isset($subDetails->description) ? $subDetails->description : '' }}</textarea>
                            </div>
                    
                            <div class="col-md-6 mb-3 mt-4">
                                <label class="upload-area" style="max-width: 100%;">
                                    <span class="upload-text {{ !empty($subDetails->upload_file ?? '') ? 'hidden' : '' }}">
                                        Click to Upload Board Resolution Ref
                                    </span>
                    
                                    <input type="file" class="fileInput" id="share_holder_fund_image" name="share_holder_fund_image"
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" hidden>
                    
                                    <div class="file-preview-container">
                                        @if (!empty($subDetails->upload_file ?? ''))
                                        @php
                                        $filePath = asset('storage/liabilities_files/' . $subDetails->upload_file);
                                        @endphp
                                        <div class="file-preview">
                                            <div class="file-info">
                                                <div class="file-name">{{ $subDetails->upload_file }}</div>
                                                <div class="file-size">Uploaded File</div>
                                            </div>
                                            <a href="{{ $filePath }}" download="{{ $subDetails->upload_file }}"
                                                class="btn btn-success btn-sm">
                                                Download
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </label>
                            </div>
                    
                        </div>
                    </div>

                    <div class="row" id="reservesSurplusSection">

                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Reserves Surplus Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="reserves_surplus_type" name="reserves_surplus_type">
                                <option value="">Select Reserves Surplus Type</option>

                                <option value="opening_balance"
                                    {{ (isset($subDetails->reserves_surplus_type) && $subDetails->reserves_surplus_type == 'opening_balance') ? 'selected' : '' }}>
                                    Opening balance
                                </option>

                                <option value="dividend_declaration"
                                    {{ (isset($subDetails->reserves_surplus_type) && $subDetails->reserves_surplus_type == 'dividend_declaration') ? 'selected' : '' }}>
                                    Dividend distribution
                                </option>
                                <option value="transfer_to_reserve"
                                    {{ (isset($subDetails->reserves_surplus_type) && $subDetails->reserves_surplus_type == 'transfer_to_reserve') ? 'selected' : '' }}>
                                    Transfers to Reserve
                                </option>
                            </select>
                        </div>

                        <!-- Equity Share Capital Fields -->
                        <div id="equityShareCapitalFields" style="display:none; width:100%;">
                            <div class="row">
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Opening Balance </label>
                                    <input type="text" id="opening_balance" name="opening_balance" value="{{ $subDetails->opening_balance ?? '' }}" class="form-control" placeholder="Opening Balance ">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" id="surplusdate" name="surplusdate" value="{{ $subDetails->surplusdate ?? '' }}" class="form-control" placeholder="Amount for Share ">
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
                                    <input type="date" id="declaration_date" name="declaration_date" value="{{ $subDetails->declaration_date ?? '' }}" class="form-control">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Financial Year </label>
                                    <input type="text" id="dividend_financial_year" name="dividend_financial_year" value="{{ $subDetails->dividend_financial_year ?? '' }}" class="form-control" placeholder="e.g., 2025-2026">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Total Dividend Amount </label>
                                    <input type="number" id="total_dividend_amount" name="total_dividend_amount" value="{{ $subDetails->total_dividend_amount ?? '' }}" class="form-control" placeholder="Enter amount">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Dividend Type </label>
                                    <select class="form-select" id="dividend_type" name="dividend_type">
                                        <option value="">Select Type</option>
                                        <option value="final" {{ (isset($subDetails->dividend_type) && $subDetails->dividend_type == 'final') ? 'selected' : '' }}>Final</option>
                                        <option value="interim" {{ (isset($subDetails->dividend_type) && $subDetails->dividend_type == 'interim') ? 'selected' : '' }}>Interim</option>
                                    </select>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Payment Due Date </label>
                                    <input type="date" id="dividend_payment_due_date" name="dividend_payment_due_date" value="{{ $subDetails->dividend_payment_due_date ?? '' }}" class="form-control">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Payment Status </label>
                                    <select class="form-select" id="dividend_payment_status" name="dividend_payment_status">
                                        <option value="">Select Status</option>
                                        <option value="unpaid" {{ (isset($subDetails->dividend_payment_status) && $subDetails->dividend_payment_status == 'unpaid') ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ (isset($subDetails->dividend_payment_status) && $subDetails->dividend_payment_status == 'paid') ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control" id="dividend_remarks" name="dividend_remarks" rows="3" placeholder="Enter remarks">{{ $subDetails->dividend_remarks ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Transfer to Reserve Fields -->
                        <div id="transferToReserveFields" style="display:none; width:100%;">
                            <div class="row">
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Transfer Date </label>
                                    <input type="date" value="{{ $subDetails->transfer_date ?? '' }}" id="transfer_date" name="transfer_date" class="form-control">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Financial Year </label>
                                    <input type="text" value="{{ $subDetails->transfer_financial_year ?? '' }}" id="transfer_financial_year" name="transfer_financial_year" class="form-control" placeholder="e.g., 2025-2026">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Transfer Amount </label>
                                    <input type="number" value="{{ $subDetails->transfer_amount ?? '' }}" id="transfer_amount" name="transfer_amount" class="form-control" placeholder="Enter amount">
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Reserve Type </label>
                                    <select class="form-select" id="reserve_type" name="reserve_type">
                                        <option value="">Select Reserve Type</option>
                                        <option value="general_reserve" {{ (isset($subDetails->reserve_type) && $subDetails->reserve_type == 'general_reserve') ? 'selected' : '' }}>General Reserve</option>
                                        <option value="capital_reserve" {{ (isset($subDetails->reserve_type) && $subDetails->reserve_type == 'capital_reserve') ? 'selected' : '' }}>Capital Reserve</option>
                                        <option value="specific_reserve" {{ (isset($subDetails->reserve_type) && $subDetails->reserve_type == 'specific_reserve') ? 'selected' : '' }}>Specific Reserve</option>
                                    </select>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea class="form-control" id="transfer_remarks" name="transfer_remarks" rows="3" placeholder="Enter remarks">{{ $subDetails->transfer_remarks ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Share Application Money Pending Allotment --}}
                    <div class="row" id="shareApplicationMoney" style="display: none;">
                        <div class="row" id="shareApplicationMoney">
                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Applicant Name </label>
                                <input type="text" name="applicant_name" value="{{ $subDetails->applicant_name ?? '' }}" class="form-control" placeholder="Enter Applicant Name">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">PAN (Optional)</label>
                                <input type="text" name="pan" value="{{ $subDetails->pan ?? '' }}" class="form-control" placeholder="Enter PAN">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Amount Received</label>
                                <input type="text" name="amount_received" value="{{ $subDetails->amount_received ?? '' }}" class="form-control" placeholder="Enter Amount">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Date of Received</label>
                                <input type="date" name="date_received" value="{{ $subDetails->date_received ?? '' }}" class="form-control">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Mode of Payment</label>
                                <select name="payment_mode" class="form-select">
                                    <option value="">Select Mode</option>
                                    <option value="cash" {{ (isset($subDetails->payment_mode) && $subDetails->payment_mode == 'cash') ? 'selected' : '' }}>Cash</option>
                                    <option value="upi" {{ (isset($subDetails->payment_mode) && $subDetails->payment_mode == 'upi') ? 'selected' : '' }}>UPI</option>
                                    <option value="cheque" {{ (isset($subDetails->payment_mode) && $subDetails->payment_mode == 'cheque') ? 'selected' : '' }}>Cheque</option>
                                    <option value="neft" {{ (isset($subDetails->payment_mode) && $subDetails->payment_mode == 'neft') ? 'selected' : '' }}>NEFT</option>
                                    <option value="rtgs" {{ (isset($subDetails->payment_mode) && $subDetails->payment_mode == 'rtgs') ? 'selected' : '' }}>RTGS</option>
                                    <option value="imps" {{ (isset($subDetails->payment_mode) && $subDetails->payment_mode == 'imps') ? 'selected' : '' }}>IMPS</option>
                                </select>
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ $subDetails->bank_name ?? '' }}" class="form-control" placeholder="Enter Bank Name">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">No. of Shares</label>
                                <input type="text" name="no_of_shares" value="{{ $subDetails->no_of_shares ?? '' }}" class="form-control" placeholder="Enter No. of Shares">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Face Value</label>
                                <input type="text" name="face_value" value="{{ $subDetails->face_value ?? '' }}" class="form-control" placeholder="Enter Face Value">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Premium</label>
                                <input type="text" name="premium" value="{{ $subDetails->premium ?? '' }}" class="form-control" placeholder="Enter Premium">
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Allotment Status</label>
                                <select name="allotment_status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="pending" {{ (isset($subDetails->allotment_status) && $subDetails->allotment_status == 'pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="allotted" {{ (isset($subDetails->allotment_status) && $subDetails->allotment_status == 'allotted') ? 'selected' : '' }}>Allotted</option>
                                    <option value="rejected" {{ (isset($subDetails->allotment_status) && $subDetails->allotment_status == 'rejected') ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="col-xl-4 mb-3">
                                <label class="form-label">Allotment Date</label>
                                <input type="date" name="allotment_date" value="{{ $subDetails->allotment_date ?? '' }}" class="form-control">
                            </div>
                        </div>
                    </div>



                    <!-- Non Current Liabilities Section -->
                    <div id="nonCurrentLiabilities" style="display:none;">
                        <div class="row">

                            <div class="row">

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Select Non-Current Liabilities <span class="text-danger">*</span></label>
                                    <select class="form-select mb-3" id="non_current_liabilities_type" name="non_current_liabilities_type">
                                        <option value="">Select Non-Current Liabilities Type</option>
                                        <option value="long_term_borrowings" {{ (isset($subDetails->liability_category) && $subDetails->liability_category == 'long_term_borrowings') ? 'selected' : '' }}>Long-term Borrowings</option>
                                        <option value="other_financial_liabilities" {{ (isset($subDetails->liability_category) && $subDetails->liability_category == 'other_financial_liabilities') ? 'selected' : '' }}>Other Financial Liabilities</option>
                                        <option value="long_term_provisions" {{ (isset($subDetails->liability_category) && $subDetails->liability_category == 'long_term_provisions') ? 'selected' : '' }}>Long-term Provisions</option>
                                        <option value="deferred_tax_liabilities" {{ (isset($subDetails->liability_category) && $subDetails->liability_category == 'deferred_tax_liabilities') ? 'selected' : '' }}>Deferred Tax Liabilities</option>
                                        <option value="other_non_current_liabilities" {{ (isset($subDetails->liability_category) && $subDetails->liability_category == 'other_non_current_liabilities') ? 'selected' : '' }}>Other Non-Current Liabilities</option>
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
                                            <input type="text" value="{{ isset($subDetails->other_non_current_liabilities_text) ? $subDetails->other_non_current_liabilities_text : '' }}" id="other_non_current_liabilities_text" name="other_non_current_liabilities_text" class="form-control" placeholder="Enter Other Non Current Liabilities details">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Category Of Head</label>
                                            <input type="text" value="{{ isset($subDetails->category_of_head) ? $subDetails->category_of_head : '' }}" id="category_of_head_nonc" name="category_of_head_nonc" class="form-control" placeholder="Enter category of head">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Party Name <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ isset($subDetails->party_name) ? $subDetails->party_name : '' }}" id="party_name_nonc" name="party_name_nonc" class="form-control" placeholder="Enter party name">
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
                                            <input type="number" value="{{ isset($subDetails->amount) ? $subDetails->amount : '' }}" id="amt_nonc" name="amt_nonc" class="form-control" placeholder="Enter amount">
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
                                            <input type="date" value="{{ isset($subDetails->due_date) ? $subDetails->due_date : '' }}" id="due_date_nonc" name="due_date_nonc" class="form-control">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Reference No</label>
                                            <input type="text" value="{{ isset($subDetails->invoice_no) ? $subDetails->invoice_no : '' }}" id="invoice_no_nonc" name="invoice_no_nonc" class="form-control" placeholder="Enter Invoice Number">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Loan Type</label>
                                            <select id="loan_type_nonc" class="form-select" name="loan_type_nonc">
                                                <option value="">Select Loan Type</option>
                                                <option value="Secured" {{ isset($subDetails->loan_type) && $subDetails->loan_type == 'Secured' ? 'selected' : '' }}>Secured</option>
                                                <option value="Unsecured" {{ isset($subDetails->loan_type) && $subDetails->loan_type == 'Unsecured' ? 'selected' : '' }}>Unsecured</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Interest</label>
                                            <input type="text" value="{{ isset($subDetails->interest_rate) ? $subDetails->interest_rate : '' }}" id="interest_rate_nonc" name="interest_rate_nonc" class="form-control" placeholder="Enter Interest">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">MSME Tag</label>
                                            <input type="text" value="{{ isset($subDetails->msme_tag) ? $subDetails->msme_tag : '' }}" id="msme_tag_nonc" name="msme_tag_nonc" class="form-control" placeholder="Enter MSME Tag">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Attachment</label>
                                            <input type="file" value="{{ isset($subDetails->attachment) ? $subDetails->attachment : '' }}" id="attachment_nonc" name="attachment_nonc" class="form-control">
                                        </div>

                                        <div class="col-xl-12 mb-3">
                                            <label class="form-label">Note</label>
                                            <input type="text" value="{{ isset($subDetails->notes) ? $subDetails->notes : '' }}" id="notes_nonc" name="notes_nonc" class="form-control">
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
                                            <input type="text" value="{{ isset($subDetails->dtl_difference_accounting) ? $subDetails->dtl_difference_accounting : '' }}" name="dtl_difference_accounting" id="dtl_difference_accounting" class="form-control" placeholder="Enter Difference in Accounting">
                                        </div>

                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">Deferred Tax Liability (DTL) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" value="{{ isset($subDetails->dtl_amount) ? $subDetails->dtl_amount : '' }}" name="dtl_amount" id="dtl_amount" class="form-control" placeholder="Enter DTL Amount">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="row" id="currentLiabilities" style="display:none;">
                        <div class="col-xl-4 mb-3">
                            <label class="form-label">Current Liabilities Type<span class="text-danger">*</span></label>
                            <select class="form-select" id="CurrentLiabilitiesType" name="CurrentLiabilitiesType">
                                <option value="">Select Current Liabilities Type</option>
                                <option value="trade_payables" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'trade_payables' ? 'selected' : '' }}>Trade Payables (Creditors)</option>
                                <option value="advance_from_customer" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'advance_from_customer' ? 'selected' : '' }}>Advance from Customer</option>
                                <option value="outstanding_expenses" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'outstanding_expenses' ? 'selected' : '' }}>Outstanding Expenses</option>
                                <option value="salary_payable" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'salary_payable' ? 'selected' : '' }}>Salary Payable</option>
                                <option value="gst_payable" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'gst_payable' ? 'selected' : '' }}>GST Payable</option>
                                <option value="tds_payable" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'tds_payable' ? 'selected' : '' }}>TDS Payable</option>
                                <option value="pf_payable" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'pf_payable' ? 'selected' : '' }}>PF Payable</option>
                                <option value="esi_payable" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'esi_payable' ? 'selected' : '' }}>ESI Payable</option>
                                <option value="short_term_loans" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'short_term_loans' ? 'selected' : '' }}>Short-term Loans</option>
                                <option value="interest_payable" {{ ($subDetails->CurrentLiabilitiesType ?? '') == 'interest_payable' ? 'selected' : '' }}>Interest Payable</option>
                            </select>
                        </div>

                        {{-- ===== Simple payable sections (amount + link button) ===== --}}

                        {{-- Trade Payables --}}
                        <div id="clSection_trade_payables" style="display:none;" class="col-xl-12">
                            <div class="row align-items-end">
                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_trade_payables" id="cl_amount_trade_payables" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_advance_from_customer" id="cl_amount_advance_from_customer" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_outstanding_expenses" id="cl_amount_outstanding_expenses" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_salary_payable" id="cl_amount_salary_payable" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_gst_payable" id="cl_amount_gst_payable" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_tds_payable" id="cl_amount_tds_payable" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_pf_payable" id="cl_amount_pf_payable" class="form-control" placeholder="Enter Amount">
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
                                    <input type="number" value="{{ $subDetails->amount ?? '' }}" name="cl_amount_esi_payable" id="cl_amount_esi_payable" class="form-control" placeholder="Enter Amount">
                                </div>
                                <div class="col-xl-4 mb-3">
                                    <a href="{{ url('/esi-management-list') }}" target="_blank" class="btn btn-outline-primary w-100">
                                        <i class="ti ti-external-link me-1"></i> View ESI Management
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Generic fields (for remaining types) --}}
                        <div id="genericClFields">
                            <div class="row">
                                <div class="col-xl-4 mb-3" id="otherCurrentLiabilityTextContainer" style="display:none;">
                                    <label class="form-label">Other Current Liability (specify)<span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $subDetails->other_current_liability_text ?? '' }}" id="other_current_liability_text" name="other_current_liability_text" class="form-control" placeholder="Enter Other Current Liability details">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Category Of Head</label>
                                    <input type="text" value="{{ $subDetails->category_of_head_cl ?? '' }}" id="category_of_head_cl" name="category_of_head_cl" class="form-control" placeholder="Enter category of head">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Party Name</label>
                                    <input type="text" value="{{ $subDetails->party_name_cl ?? '' }}" id="party_name_cl" name="party_name_cl" class="form-control" placeholder="Enter party name">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Voucher Type<span class="text-danger">*</span></label>
                                    <select id="voucher_type_cl" class="form-select" name="voucher_type_cl">
                                        <option value="">Select Type</option>
                                        <option value="Journal" {{ (isset($subDetails->voucher_type_cl) && $subDetails->voucher_type_cl == 'Journal') ? 'selected' : '' }}>Journal</option>
                                        <option value="Purchase" {{ (isset($subDetails->voucher_type_cl) && $subDetails->voucher_type_cl == 'Purchase') ? 'selected' : '' }}>Purchase</option>
                                        <option value="Sales" {{ (isset($subDetails->voucher_type_cl) && $subDetails->voucher_type_cl == 'Sales') ? 'selected' : '' }}>Sales</option>
                                        <option value="Payment" {{ (isset($subDetails->voucher_type_cl) && $subDetails->voucher_type_cl == 'Payment') ? 'selected' : '' }}>Payment</option>
                                        <option value="Receipt" {{ (isset($subDetails->voucher_type_cl) && $subDetails->voucher_type_cl == 'Receipt') ? 'selected' : '' }}>Receipt</option>
                                        <option value="Contra" {{ (isset($subDetails->voucher_type_cl) && $subDetails->voucher_type_cl == 'Contra') ? 'selected' : '' }}>Contra</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Amount<span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->amt_cl ?? '' }}" id="amt_cl" name="amt_cl" class="form-control" placeholder="Enter amount">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Debit/Credit<span class="text-danger">*</span></label>
                                    <select id="debitcredit_cl" class="form-select" name="debitcredit_cl">
                                        <option value="">Select Debit/Credit</option>
                                        <option value="Debit" {{ (isset($subDetails->debitcredit_cl) && $subDetails->debitcredit_cl == 'Debit') ? 'selected' : '' }}>Debit</option>
                                        <option value="Credit" {{ (isset($subDetails->debitcredit_cl) && $subDetails->debitcredit_cl == 'Credit') ? 'selected' : '' }}>Credit</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Due Date<span class="text-danger">*</span></label>
                                    <input type="date" value="{{ $subDetails->due_date_cl ?? '' }}" id="due_date_cl" name="due_date_cl" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Invoice Number</label>
                                    <input type="text" value="{{ $subDetails->invoice_no_cl ?? '' }}" id="invoice_no_cl" name="invoice_no_cl" class="form-control" placeholder="Enter Invoice Number">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Loan Type</label>
                                    <select id="loan_type_cl" class="form-select" name="loan_type_cl">
                                        <option value="">Select Loan Type</option>
                                        <option value="Secured" {{ (isset($subDetails->loan_type_cl) && $subDetails->loan_type_cl == 'Secured') ? 'selected' : '' }}>Secured</option>
                                        <option value="Unsecured" {{ (isset($subDetails->loan_type_cl) && $subDetails->loan_type_cl == 'Unsecured') ? 'selected' : '' }}>Unsecured</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest / Depreciation Rate</label>
                                    <input type="text" value="{{ $subDetails->interest_rate_cl ?? '' }}" id="interest_rate_cl" name="interest_rate_cl" class="form-control" placeholder="Enter Interest / Depreciation Rate">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">MSME Tag</label>
                                    <input type="text" value="{{ $subDetails->msme_tag_cl ?? '' }}" id="msme_tag_cl" name="msme_tag_cl" class="form-control" placeholder="Enter MSME Tag">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Attachment</label>
                                    <input type="file" value="{{ $subDetails->attachment_cl ?? '' }}" id="attachment_cl" name="attachment_cl" class="form-control">
                                </div>

                                <div class="col-xl-12 mb-3">
                                    <label class="form-label">Note</label>
                                    <input type="text" value="{{ $subDetails->notes_cl ?? '' }}" id="notes_cl" name="notes_cl" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Short-term Loans Section --}}
                        <div id="shortTermLoansSection" style="display:none;">
                            <div class="row">
                                <div class="col-xl-12 mb-2">
                                    <h6 class="text-primary fw-semibold border-bottom pb-2">Short-term Loan Details</h6>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Loan ID / Loan Account No <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $subDetails->stl_loan_id ?? '' }}" name="stl_loan_id" id="stl_loan_id" class="form-control" placeholder="Enter Loan ID / Account No">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Lender Name <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ $subDetails->stl_lender_name ?? '' }}" name="stl_lender_name" id="stl_lender_name" class="form-control" placeholder="Enter Lender Name">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Loan Type (Bank / NBFC / Director / Unsecured)</label>
                                    <select name="stl_loan_type" id="stl_loan_type" class="form-select">
                                        <option value="">Select Loan Type</option>
                                        <option value="Bank" {{ (isset($subDetails->stl_loan_type) && $subDetails->stl_loan_type == 'Bank') ? 'selected' : '' }}>Bank</option>
                                        <option value="NBFC" {{ (isset($subDetails->stl_loan_type) && $subDetails->stl_loan_type == 'NBFC') ? 'selected' : '' }}>NBFC</option>
                                        <option value="Director" {{ (isset($subDetails->stl_loan_type) && $subDetails->stl_loan_type == 'Director') ? 'selected' : '' }}>Director</option>
                                        <option value="Unsecured" {{ (isset($subDetails->stl_loan_type) && $subDetails->stl_loan_type == 'Unsecured') ? 'selected' : '' }}>Unsecured</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Secured / Unsecured</label>
                                    <select name="stl_secured_unsecured" id="stl_secured_unsecured" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Secured" {{ (isset($subDetails->stl_secured_unsecured) && $subDetails->stl_secured_unsecured == 'Secured') ? 'selected' : '' }}>Secured</option>
                                        <option value="Unsecured" {{ (isset($subDetails->stl_secured_unsecured) && $subDetails->stl_secured_unsecured == 'Unsecured') ? 'selected' : '' }}>Unsecured</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Sanction Amount <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->stl_sanction_amount ?? '' }}" name="stl_sanction_amount" id="stl_sanction_amount" class="form-control" placeholder="Enter Sanction Amount">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Disbursement Date <span class="text-danger">*</span></label>
                                    <input type="date" value="{{ $subDetails->stl_disbursement_date ?? '' }}" name="stl_disbursement_date" id="stl_disbursement_date" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Amount Received</label>
                                    <input type="number" value="{{ $subDetails->stl_amount_received ?? '' }}" name="stl_amount_received" id="stl_amount_received" class="form-control" placeholder="Enter Amount Received">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Bank Account (where received)</label>
                                    <input type="text" value="{{ $subDetails->stl_bank_account ?? '' }}" name="stl_bank_account" id="stl_bank_account" class="form-control" placeholder="Enter Bank Account">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" value="{{ $subDetails->stl_interest_rate ?? '' }}" name="stl_interest_rate" id="stl_interest_rate" class="form-control" placeholder="Enter Interest Rate">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Amount <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->stl_interest_amount ?? '' }}" name="stl_interest_amount" id="stl_interest_amount" class="form-control" placeholder="Enter Interest Amount">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Type (Fixed / Floating)</label>
                                    <select name="stl_interest_type" id="stl_interest_type" class="form-select">
                                        <option value="">Select Interest Type</option>
                                        <option value="Fixed" {{ (isset($subDetails->stl_interest_type) && $subDetails->stl_interest_type == 'Fixed') ? 'selected' : '' }}>Fixed</option>
                                        <option value="Floating" {{ (isset($subDetails->stl_interest_type) && $subDetails->stl_interest_type == 'Floating') ? 'selected' : '' }}>Floating</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Tenure (Months) <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->stl_tenure_months ?? '' }}" name="stl_tenure_months" id="stl_tenure_months" class="form-control" placeholder="Enter Tenure in Months">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Repayment Type <span class="text-danger">*</span></label>
                                    <select name="stl_repayment_type" id="stl_repayment_type" class="form-select">
                                        <option value="">Select Repayment Type</option>
                                        <option value="EMI" {{ (isset($subDetails->stl_repayment_type) && $subDetails->stl_repayment_type == 'EMI') ? 'selected' : '' }}>EMI</option>
                                        <option value="Bullet" {{ (isset($subDetails->stl_repayment_type) && $subDetails->stl_repayment_type == 'Bullet') ? 'selected' : '' }}>Bullet</option>
                                        <option value="Flexible" {{ (isset($subDetails->stl_repayment_type) && $subDetails->stl_repayment_type == 'Flexible') ? 'selected' : '' }}>Flexible</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">EMI Amount (if applicable) <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->stl_emi_amount ?? '' }}" name="stl_emi_amount" id="stl_emi_amount" class="form-control" placeholder="Enter EMI Amount">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Outstanding Principal <small class="text-muted">(Auto-calculated)</small></label>
                                    <input type="number" value="{{ $subDetails->stl_outstanding_principal ?? '' }}" name="stl_outstanding_principal" id="stl_outstanding_principal" class="form-control" placeholder="Auto-calculated" readonly>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Next Due Date</label>
                                    <input type="date" value="{{ $subDetails->stl_next_due_date ?? '' }}" name="stl_next_due_date" id="stl_next_due_date" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Maturity Date</label>
                                    <input type="date" value="{{ $subDetails->stl_maturity_date ?? '' }}" name="stl_maturity_date" id="stl_maturity_date" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Reference (Loan Agreement No)</label>
                                    <input type="text" value="{{ $subDetails->stl_reference ?? '' }}" name="stl_reference" id="stl_reference" class="form-control" placeholder="Enter Loan Agreement No">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">MSME Related Party?</label>
                                    <select name="stl_msme_related" id="stl_msme_related" class="form-select">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ (isset($subDetails->stl_msme_related) && $subDetails->stl_msme_related == 'Yes') ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ (isset($subDetails->stl_msme_related) && $subDetails->stl_msme_related == 'No') ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>

                                <div class="col-xl-12 mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea name="stl_remarks" id="stl_remarks" class="form-control" rows="3" placeholder="Enter Remarks">{{ $subDetails->stl_remarks ?? '' }}</textarea>
                                </div>

                                <div class="col-xl-12 mb-3">
                                    <label class="form-label">TDS Applicable on Interest?</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stl_tds_applicable" id="stlTdsYes" value="yes" {{ (isset($subDetails->stl_tds_applicable) && $subDetails->stl_tds_applicable == 'yes') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stlTdsYes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stl_tds_applicable" id="stlTdsNo" value="no" {{ (isset($subDetails->stl_tds_applicable) && $subDetails->stl_tds_applicable == 'no') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stlTdsNo">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="stlTdsFields" style="display:none;" class="col-xl-12 mb-3">
                                    <div class="row">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">TDS Section (e.g., 194A)</label>
                                            <input type="text" value="{{ $subDetails->stl_tds_section ?? '' }}" name="stl_tds_section" id="stl_tds_section" class="form-control" placeholder="e.g., 194A">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">TDS Rate (%)</label>
                                            <input type="number" step="0.01" value="{{ $subDetails->stl_tds_rate ?? '' }}" name="stl_tds_rate" id="stl_tds_rate" class="form-control" placeholder="Enter TDS Rate">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">TDS Amount <small class="text-muted">(Auto)</small></label>
                                            <input type="number" value="{{ $subDetails->stl_tds_amount ?? '' }}" name="stl_tds_amount" id="stl_tds_amount" class="form-control" placeholder="Auto-calculated" readonly>
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
                                        <input 
                                            type="text" 
                                            name="ip_loan_id" 
                                            id="ip_loan_id" 
                                            class="form-control" 
                                            placeholder="Enter Loan ID"
                                            value="{{ $subDetails->ip_loan_id ?? '' }}"
                                        >
                                    </div>
                                    {{-- <div class="input-group">
                                        <select name="ip_loan_id" id="ip_loan_id" class="form-select">
                                            <option value="" >— Select Loan ID —</option>
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary" id="ipLoanEditBtn" title="Edit / Enter manually">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>
                                    </div>
                                    <input type="text" name="ip_loan_id_manual" id="ip_loan_id_manual" class="form-control mt-1" placeholder="Or type Loan ID manually" style="display:none;">
                                    <small class="text-muted">Scroll to pick a Short-term or Long-term Loan. Details auto-fill on match.</small> --}}
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Lender Name</label>
                                    <input type="text" value="{{ $subDetails->ip_lender_name ?? '' }}" name="ip_lender_name" id="ip_lender_name" class="form-control" placeholder="Auto-filled from Loan">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Accrual Date <span class="text-danger">*</span></label>
                                    <input type="date" value="{{ $subDetails->ip_accrual_date ?? '' }}" name="ip_accrual_date" id="ip_accrual_date" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Period From</label>
                                    <input type="date" value="{{ $subDetails->ip_period_from ?? '' }}" name="ip_period_from" id="ip_period_from" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Period To</label>
                                    <input type="date" value="{{ $subDetails->ip_period_to ?? '' }}" name="ip_period_to" id="ip_period_to" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" value="{{ $subDetails->ip_interest_rate ?? '' }}" name="ip_interest_rate" id="ip_interest_rate" class="form-control" placeholder="Enter Interest Rate">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Interest Amount <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $subDetails->ip_interest_amount ?? '' }}" name="ip_interest_amount" id="ip_interest_amount" class="form-control" placeholder="Enter Interest Amount">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Principal Amount <small class="text-muted">(Auto-fetch)</small></label>
                                    <input type="number" value="{{ $subDetails->ip_principal_amount ?? '' }}" name="ip_principal_amount" id="ip_principal_amount" class="form-control" placeholder="Auto-fetched from Loan">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Days / Period</label>
                                    <input type="text" value="{{ $subDetails->ip_days_period ?? '' }}" name="ip_days_period" id="ip_days_period" class="form-control" placeholder="e.g., 30 days">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" value="{{ $subDetails->ip_due_date ?? '' }}" name="ip_due_date" id="ip_due_date" class="form-control">
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Paid / Unpaid Status</label>
                                    <select name="ip_payment_status" id="ip_payment_status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="Paid" {{ (isset($subDetails->ip_payment_status) && $subDetails->ip_payment_status == 'Paid') ? 'selected' : '' }}>Paid</option>
                                        <option value="Unpaid" {{ (isset($subDetails->ip_payment_status) && $subDetails->ip_payment_status == 'Unpaid') ? 'selected' : '' }}>Unpaid</option>
                                    </select>
                                </div>

                                <div class="col-xl-4 mb-3">
                                    <label class="form-label">Reference (Working / Sheet / Agreement)</label>
                                    <input type="text" value="{{ $subDetails->ip_reference ?? '' }}" name="ip_reference" id="ip_reference" class="form-control" placeholder="Enter Reference">
                                </div>

                                <div class="col-xl-12 mb-3">
                                    <label class="form-label">Narration</label>
                                    <textarea name="ip_narration" id="ip_narration" class="form-control" rows="3" placeholder="Enter Narration">{{ $subDetails->ip_narration ?? '' }}</textarea>
                                </div>

                                <div class="col-xl-12 mb-3">
                                    <label class="form-label">TDS Applicable?</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="ip_tds_applicable" id="ipTdsYes" value="yes" {{ (isset($subDetails->ip_tds_applicable) && $subDetails->ip_tds_applicable == 'yes') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ipTdsYes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="ip_tds_applicable" id="ipTdsNo" value="no" {{ (isset($subDetails->ip_tds_applicable) && $subDetails->ip_tds_applicable == 'no') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ipTdsNo">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="ipTdsFields" style="display:none;" class="col-xl-12 mb-3">
                                    <div class="row">
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">TDS Section (e.g., 194A)</label>
                                            <input type="text" value="{{ $subDetails->ip_tds_section ?? '' }}" name="ip_tds_section" id="ip_tds_section" class="form-control" placeholder="e.g., 194A">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">TDS Rate (%)</label>
                                            <input type="number" step="0.01" value="{{ $subDetails->ip_tds_rate ?? '' }}" name="ip_tds_rate" id="ip_tds_rate" class="form-control" placeholder="Enter TDS Rate">
                                        </div>
                                        <div class="col-xl-4 mb-3">
                                            <label class="form-label">TDS Amount <small class="text-muted">(Auto)</small></label>
                                            <input type="number" value="{{ $subDetails->ip_tds_amount ?? '' }}" name="ip_tds_amount" id="ip_tds_amount" class="form-control" placeholder="Auto-calculated" readonly>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="text-end btn-page mt-4">
                        <a href="{{ url('/liabilites-list') }}" class="btn btn-outline-warning">Cancel</a>
                        {{-- <button type="submit" class="btn btn-primary">Save Changes</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-script')

<script src="{{asset('assets/js/plugins/choices.min.js') }}"></script>
<script>

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


    $('#CurrentLiabilitiesType').on('change', function () {
        // $("#deferred_tax_liabilities").hide();
        
        var selectedValue = $(this).val();
        // alert(selectedValue);

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
        } else if(selectedValue == 'interest_payable') {
            $('#interestPayableSection').show();
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

        }else {
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

    // Trigger change on page load if already selected
    $("#non_current_liabilities_type").trigger('change');

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
            'salary_payable', 'gst_payable', 'tds_payable', 'pf_payable', 'esi_payable'
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

            // loader.show(); // Show loader while fetching data
            $.ajax({
                url: "{{ url('/get-current-liability-amount') }}",
                type: "GET",
                data: {
                    type: type
                },
                success: function (response) {
                    // loader.hide(); // Hide loader after receiving response
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
                    }
                }
            });
        }

        function handleClSpecialTypes(val) {
            hideAllClSections();

            if ($.inArray(val, simpleClTypes) !== -1) {
                // Simple: amount + link button
                $('#clSection_' + val).show();
                // Load amount dynamically
                loadCurrentLiabilityAmount(val);
            } else if (val === 'short_term_loans') {
                $('#shortTermLoansSection').show();
            } else if (val === 'interest_payable') {
                $('#interestPayableSection').show();
                // loadLoanIdsForIP();
            } else {
                // Fallback: generic fields for any other/future types
                $('#genericClFields').show();
            }
        }

        $('#CurrentLiabilitiesType').on('change', function () {
            handleClSpecialTypes($(this).val());
        });

        // Run on page load
        handleClSpecialTypes($('#CurrentLiabilitiesType').val());


    // Handler for Reserves Surplus Type dropdown
    $('#reserves_surplus_type').on('change', function () {
        var selectedValue = $(this).val();

        // Hide all field sections initially
        $('#equityShareCapitalFields').hide();
        $('#dividendDeclarationFields').hide();
        $('#transferToReserveFields').hide();

        // Show the selected section based on the dropdown value
        if (selectedValue === 'opening_balance') {
            $('#equityShareCapitalFields').show();
        } else if (selectedValue === 'dividend_declaration') {
            $('#dividendDeclarationFields').show();
        } else if (selectedValue === 'transfer_to_reserve') {
            $('#transferToReserveFields').show();
        }
    });

    // Trigger the reserves_surplus_type change on page load if already selected
    $('#reserves_surplus_type').trigger('change');

	function resetHiddenFields() {
		$('div:hidden').each(function () {
			// Clear text, date, number, textarea
			$(this).find('input[type="text"], input[type="date"], input[type="number"], textarea').val('');
			// Reset selects
			$(this).find('select').prop('selectedIndex', 0);
			// Reset radio & checkbox if needed
			//$(this).find('input[type="radio"], input[type="checkbox"]').prop('checked', false);
		});
	}

    (function($){
        // initialize Choices for any data-trigger selects
        function initChoices() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (var i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                // guard: don't re-init
                if (!element.classList.contains('choices-initialized')) {
                    new Choices(element, {
                        placeholderValue: 'Please Select',
                        searchPlaceholderValue: 'Search Customer / Vendor Name'
                    });
                    element.classList.add('choices-initialized');
                }
            }
        }

        // hide all related sections
        function hideAllSections() {
            $("#shareHolderFund").hide();
            $("#shareCapitalSection").hide();
            $('#reservesSurplusSection').hide();
            $("#shareApplicationMoney").hide();
            $("#nonCurrentLiabilities").hide();
            $("#currentLiabilities").hide();
            $("#long_term_borrowings_type").hide();
            $("#deferred_tax_liabilities").hide();
            $("#other_long_term_liabilities").hide();
            $("#long_term_provisions").hide();

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
            $('#otherNonCurrentLiabilitiesTextContainer').hide();
            $('#otherCurrentLiabilityTextContainer').hide();
            $('.select-box').hide();
            $('#purchase_select').hide();
            $('#expense_select').hide();
            $('#expenses_select').hide();
            $('#income_select').hide();
        }

        // bind handlers
        function bindHandlers() {
            // main liabilities type
            $("#liabilitiesType").off('change').on('change', function() {
                hideAllSections();
                var selectedValue = $(this).val();
                switch (selectedValue) {
                    case "share_holder_fund":
                        $("#shareHolderFund").show();
                        // if sub-type already selected, show its section
                        var shType = $('#shareHolderFundType').val();
                        if (shType === 'share_capital') $('#shareCapitalSection').show();
                        else if (shType === 'reserves_surplus') $('#reservesSurplusSection').show();
                        break;
                    case "share_application_money":
                        $("#shareApplicationMoney").show();
                        break;
                    case "non_current_liabilities":
                        $("#nonCurrentLiabilities").show();
                        // trigger sub selection
                        $("#non_current_liabilities_type").trigger('change');
                        break;
                    case "current_liabilities":
                        $("#currentLiabilities").show();
                        // trigger current sub selection
                        $("#CurrentLiabilitiesType").trigger('change');
                        // --- FIX: ensure correct sub-section is shown (interest_payable, etc.)
                        handleClSpecialTypes($('#CurrentLiabilitiesType').val());
                        break;
                    default:
                        // nothing
                        break;
                }
            });

            // share holder fund sub-type
            $('#shareHolderFundType').off('change').on('change', function() {
                $('#shareCapitalSection').hide();
                $('#reservesSurplusSection').hide();
                var v = $(this).val();
                if (v === 'share_capital') $('#shareCapitalSection').show();
                else if (v === 'reserves_surplus') $('#reservesSurplusSection').show();
            });

            // non-current sub-type
            // $("#non_current_liabilities_type").off('change').on('change', function() {
            //     var selectedValue = $(this).val();

            //     // reset all non-current-specific functional sections
            //     $("#long_term_borrowings_type, #deferred_tax_liabilities, #other_long_term_liabilities, #long_term_provisions").hide();
            //     $("#otherNonCurrentLiabilitiesTextContainer").hide();

            //     if (selectedValue === "deferred_tax_liabilities") {
            //         $("#deferred_tax_liabilities").show();
            //     } else if (selectedValue === "provision") {
            //         $("#long_term_provisions").show();
            //     } else if (selectedValue === "other_non_current_liabilities") {
            //         $("#otherNonCurrentLiabilitiesTextContainer").show();
            //     }
            // });

            // current liabilities main sub-type
            // $('#CurrentLiabilitiesType').off('change').on('change', function () {
            //     var selectedValue = $(this).val();
            //     // hide all current sub-sections
            //     $('#short_term_borrowings_section').hide();
            //     $('#tradePayables_section').hide();
            //     $('#advances_from_customers').hide();
            //     $('#statutory_dues_payable').hide();
            //     $('#TDS_payable').hide();
            //     $('#EMI_payables').hide();
            //     $('#accrued_expenses_income').hide();
            //     $('#royalty_payables').hide();
            //     $('#GST_payable').hide();
            //     $('#unearned_revenue').hide();

            //     if (selectedValue === 'Short-term Borrowings') {
            //         $('#short_term_borrowings_section').show();
            //     } else if (selectedValue === 'Trade Payables') {
            //         $('#tradePayables_section').show();
            //         // ensure radio state honored
            //         $('input[name="trade_nature"]:checked').trigger('change');
            //     } else if (selectedValue === 'Advances from Customers') {
            //         $('#advances_from_customers').show();
            //     } else if (selectedValue === 'Statutory Dues Payable') {
            //         $('#statutory_dues_payable').show();
            //     } else if (selectedValue === 'TDS Payable') {
            //         $('#TDS_payable').show();
            //     } else if (selectedValue === 'EMI Payables') {
            //         $('#EMI_payables').show();
            //     } else if (selectedValue === 'Accrued Expenses / Income') {
            //         $('#accrued_expenses_income').show();
            //         $('input[name="accrued_nature"]:checked').trigger('change');
            //     } else if (selectedValue === 'Royalty Payables') {
            //         $('#royalty_payables').show();
            //     } else if (selectedValue === 'GST Payable') {
            //         $('#GST_payable').show();
            //     } else if (selectedValue === 'Unearned Revenue') {
            //         $('#unearned_revenue').show();
            //     } else if (selectedValue === 'Other Current Liability') {
            //         $('#otherCurrentLiabilityTextContainer').show();
            //     }
            // });

            // trade_nature radio
            $('input[name="trade_nature"]').off('change').on('change', function () {
                $('.select-box').hide();
                var v = $(this).val();
                if (v === 'purchase') $('#purchase_select').show();
                else if (v === 'expense') $('#expense_select').show();
            });

            // accrued_nature radio
            $('input[name="accrued_nature"]').off('change').on('change', function () {
                $('#expenses_select, #income_select').hide();
                var v = $(this).val();
                if (v === 'expenses') $('#expenses_select').show();
                else if (v === 'income') $('#income_select').show();
            });

            // form submit
            // $("form#addFrmLiabilities").off('submit').on('submit', function(e) {
            //     e.preventDefault();
			// 	resetHiddenFields();
            //     var liabId = $("#liabId").val();
            //     var surl = liabId == "" ? "/saveLiabilities" : "/updateLiabilities";

            //     // 🔎 Get values
            //     var added_date = $("#added_date").val().trim();
            //     var liabilitiesType = $("#liabilitiesType").val();

            //     // 🔴 Required Validation
            //     if (added_date === "") {
            //         showToast("Date is required", "error");
            //         $("#added_date").focus();
            //         return false;
            //     }

            //     if (liabilitiesType === "") {
            //         showToast("Liabilities Type is required", "error");
            //         $("#liabilitiesType").focus();
            //         return false;
            //     }


            //     var formData = new FormData(this);
            //     $("#loader").show();
            //     $.ajax({
            //         headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            //         url: surl,
            //         type: "POST",
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function(response) {
            //             $("#loader").hide();
            //             if (response.class == "succ") {
            //                 showToast(response.message, "success");
            //                 setTimeout(() => {
            //                     window.location.href = response.redirect;
            //                 }, 2000);
            //             } else {
            //                 $.each(response, function(idx, obj) {
            //                     showToast("Error: " + obj, "error");
            //                 });
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             $("#loader").hide();
            //             showToast("An error occurred: " + error, "error");
            //         }
            //     });
            // });
        }

        // initialize on DOM ready
        $(function(){
            initChoices();
            hideAllSections();
            bindHandlers();

            // Trigger visibility based on pre-selected values (page load)
            var liab = $("#liabilitiesType").val();
            if (liab) {
                $("#liabilitiesType").trigger('change');
                // If current liabilities, ensure correct sub-section is shown
                if (liab === "current_liabilities") {
                    handleClSpecialTypes($('#CurrentLiabilitiesType').val());
                }
            }

            // Ensure shareHolderFund sub-type reacts on load if present
            var shf = $('#shareHolderFundType').val();
            if (shf) $('#shareHolderFundType').trigger('change');

            // Ensure non-current and current sub-types react on load
            var nct = $('#non_current_liabilities_type').val();
            if (nct) $('#non_current_liabilities_type').trigger('change');

            var cur = $('#CurrentLiabilitiesType').val();
            if (cur && liab === "current_liabilities") {
                handleClSpecialTypes(cur);
            }

            // ensure radio-dependent selects show
            var tradeChecked = $('input[name="trade_nature"]:checked').val();
            if (tradeChecked) $('input[name="trade_nature"][value="'+tradeChecked+'"]').trigger('change');

            var accruedChecked = $('input[name="accrued_nature"]:checked').val();
            if (accruedChecked) $('input[name="accrued_nature"][value="'+accruedChecked+'"]').trigger('change');

            // --- Interest Payable: show TDS fields on page load if applicable ---
            // If the radio 'ipTdsYes' exists and is checked, show the TDS fields
            if ($('#ipTdsYes').length && $('#ipTdsYes').is(':checked')) {
                $('#ipTdsFields').show();
            } else {
                $('#ipTdsFields').hide();
            }
            // Bind change handler so toggling at runtime shows/hides fields
            $('input[name="ip_tds_applicable"]').off('change.ipTds').on('change.ipTds', function() {
                if ($(this).val() === 'yes') {
                    $('#ipTdsFields').show();
                } else {
                    $('#ipTdsFields').hide();
                }
            });
        });
    })(jQuery);
</script>
<script>
$(document).ready(function () {

    function toggleGST_NONC() {
        let val = $('input[name="gst_applicable_nonc"]:checked').val();

        if (val === 'yes') {
            $('.gst-fields-nonc').show();
        } else {
            $('.gst-fields-nonc').hide();

            // ✅ Optional: clear values when No selected
            $('input[name="hsn_sac_code_nonc"]').val('');
            $('input[name="gst_rate_nonc"]').val('');
            $('select[name="gst_trans_nonc"]').val('');
        }
    }

    // ✅ Run on page load (IMPORTANT for edit)
    toggleGST_NONC();

    // ✅ Run on change
    $('input[name="gst_applicable_nonc"]').on('change', function () {
        toggleGST_NONC();
    });


    function toggleGST_CL() {
        let val = $('input[name="gst_applicable_cl"]:checked').val();

        if (val === 'yes') {
            $('.gst-fields-cl').show();
        } else {
            $('.gst-fields-cl').hide();

            // ✅ Optional: clear values when No selected
            $('input[name="hsn_sac_code_cl"]').val('');
            $('input[name="gst_rate_cl"]').val('');
            $('select[name="gst_trans_cl"]').val('');
        }
    }

    // ✅ Run on page load (important for edit)
    toggleGST_CL();

    // ✅ Run on change
    $('input[name="gst_applicable_cl"]').on('change', function () {
        toggleGST_CL();
    });

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

    
</script>

@endsection
