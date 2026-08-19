@extends('App.Layout')

@section('container')

<style>
    .toggle-radio-group .btn-outline-success {
        color: #198754 !important;
        border-color: #198754 !important;
        background-color: transparent !important;
    }
    .toggle-radio-group .btn-check:checked + .btn-outline-success,
    .toggle-radio-group .btn-outline-success.active {
        background-color: #198754 !important;
        border-color: #198754 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(25, 135, 84, 0.4) !important;
    }
    .toggle-radio-group .btn-outline-danger {
        color: #dc3545 !important;
        border-color: #dc3545 !important;
        background-color: transparent !important;
    }
    .toggle-radio-group .btn-check:checked + .btn-outline-danger,
    .toggle-radio-group .btn-outline-danger.active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4) !important;
    }
</style>

<div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header mb-3">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <ul class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/other-income-list') }}">Other Income</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View Other Income</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-3 align-items-center">
        <div class="col-md-7">
            <h3 class="mb-1 fw-bold text-dark"><i class="ti ti-file-analytics text-primary me-2"></i>View Other Income</h3>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">Details for income receipt record <strong>#{{ $income->invoice_no ?? $income->id }}</strong></p>
        </div>
        <div class="col-md-5 text-md-end mt-2 mt-md-0">
            <a href="javascript:void(0);" class="btn btn-info rounded px-3 me-2 paymentModalBtn" data-id="{{ $income->id ?? '' }}" data-type="Income">
                <i class="ti ti-receipt me-1"></i> Payment Details
            </a>
            <a href="{{ route('income.edit', base64_encode($income->id)) }}" class="btn btn-primary rounded px-3 me-2">
                <i class="ti ti-edit me-1"></i> Edit
            </a>
            <a href="{{ url('/other-income-list') }}" class="btn btn-outline-secondary rounded px-3">
                <i class="ti ti-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <form id="incomeForm" enctype="multipart/form-data">
        @csrf

        <!-- 📌 SECTION 1: Income Classification & Basic Details -->
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light-subtle py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-primary d-flex align-items-center">
                    <i class="ti ti-category me-2 fs-4"></i> 1. Classification & Basic Details
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @if($hasProprietorship)
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">{{ $hasProprietorship ? 'Proprietorship Company' : 'Company Name' }}</label>
                        <select name="propId" class="form-select" disabled>
                            <option value="">{{ parentCompanyName() }}</option>
                            @foreach($proprietorships as $company)
                                <option value="{{ $company->id }}" {{ ($income->propId == $company->id) ? 'selected' : '' }}>
                                    {{ $company->comp_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Transaction Date</label>
                        <input type="date" name="dateInput" id="dateInput" value="{{ $income->dateInput }}" disabled class="form-control bg-light">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Income Type</label>
                        <select id="incomeType" name="incomeType" class="form-select" disabled>
                            <option value="Revenue" {{ ($income->incomeType == 'Revenue') ? 'selected' : '' }}>Other Operating Income</option>
                            <option value="Other" {{ ($income->incomeType == 'Other') ? 'selected' : '' }}>Other Non-Operating Income</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Income Category / Sub-Category</label>
                        <select id="categoryIncome" name="categoryIncome" disabled class="form-select">
                            <option value="{{ $income->categoryIncome }}">{{ $income->categoryIncome }}</option>
                        </select>
                    </div>

                    @if(!empty($income->other_income))
                    <div class="col-12" id="otherIncomeCategory">
                        <label for="otherInput" class="form-label fw-semibold text-dark">Specified Other Category</label>
                        <input type="text" name="other_income" class="form-control bg-light" id="otherInput" disabled value="{{ $income->other_income }}">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 📌 SECTION 2: Party & Reference Info -->
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light-subtle py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-primary d-flex align-items-center">
                    <i class="ti ti-user-check me-2 fs-4"></i> 2. Party & Reference Details
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Party / Source Name</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control bg-light" disabled value="{{ $income->customer_name ?? 'N/A' }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Invoice / Reference Number</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control bg-light" disabled value="{{ $income->invoice_no ?? 'N/A' }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- 📌 SECTION 3: Amount & Payment Information -->
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light-subtle py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-primary d-flex align-items-center">
                    <i class="ti ti-credit-card me-2 fs-4"></i> 3. Amount & Payment Details
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Income Amount -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Total Income Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">₹</span>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ $income->amount }}" disabled class="form-control fw-bold fs-6 text-dark bg-light">
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Receipt Status</label>
                        <select name="pay_status" id="pay_status" disabled class="form-select">
                            <option value="Full" {{ ($income->pay_status == 'Full') ? 'selected' : '' }}>Full Payment</option>
                            <option value="Advance" {{ ($income->pay_status == 'Advance') ? 'selected' : '' }}>Advance Payment</option>
                            <option value="Due" {{ ($income->pay_status == 'Due') ? 'selected' : '' }}>Due / Receivable</option>
                        </select>
                    </div>

                    <!-- Payment Mode -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Payment Mode</label>
                        <select name="pay_mode" id="pay_mode" disabled class="form-select">
                            <option value="Cash" {{ ($income->pay_mode == 'Cash') ? 'selected' : '' }}>Cash</option>
                            <option value="Bank" {{ ($income->pay_mode == 'Bank') ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="UPI" {{ ($income->pay_mode == 'UPI') ? 'selected' : '' }}>UPI</option>
                        </select>
                    </div>

                    <!-- Select Bank -->
                    @if(!empty($income->bank_id) || ($income->pay_mode == 'Bank' || $income->pay_mode == 'UPI'))
                    <div class="col-md-4" id="bank_div">
                        <label class="form-label fw-semibold text-dark">Deposited Bank Account</label>
                        <select name="bank_id" id="bank_id" class="form-select" disabled>
                            <option value="">-- Bank Account --</option>
                            @if(!empty($bankDetails))
                                @foreach($bankDetails as $bank)
                                    <option value="{{ $bank->id }}" {{ (isset($income->bank_id) && $income->bank_id == $bank->id) ? 'selected' : '' }}>
                                        {{ $bank->bank_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endif

                    <!-- Dynamic Advance / Receivable fields -->
                    <div class="col-md-4" id="advance_amt_div">
                        <label class="form-label fw-semibold text-dark">Advance Received Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="advance_amt" id="advance_amt" value="{{ $income->advance_amt }}" disabled class="form-control bg-light">
                        </div>
                    </div>

                    <div class="col-md-4" id="receivable_amt_div">
                        <label class="form-label fw-semibold text-dark">Balance Receivable</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="receivable_amt" id="receivable_amt" value="{{ $income->receivable_amt }}" disabled class="form-control bg-light">
                        </div>
                    </div>

                    <div class="col-md-4" id="adjust_amt_div">
                        <label class="form-label fw-semibold text-dark">Adjust Amount (Received)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="adjust_amt" id="adjust_amt" value="{{ $income->adjust_amt }}" disabled class="form-control bg-light">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📌 SECTION 4: Tax & Compliance (GST & TDS) -->
        <div class="card border-0 shadow-sm rounded-3 mb-3 currAsset">
            <div class="card-header bg-light-subtle py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-primary d-flex align-items-center">
                    <i class="ti ti-receipt-tax me-2 fs-4"></i> 4. Tax & Compliance (GST & TDS)
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- TDS Block -->
                    <div class="col-lg-6 border-end-lg pe-lg-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label fw-bold text-dark mb-0 fs-6">TDS Deducted by Payer?</label>
                            <div class="btn-group btn-group-sm toggle-radio-group" role="group">
                                <input type="radio" class="btn-check" name="tds_applicable" value="yes" id="tdsYes" disabled {{ old('tds_applicable', $income->tds_applicable ?? '') == 'yes' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success px-3 fw-semibold" for="tdsYes">Yes</label>

                                <input type="radio" class="btn-check" name="tds_applicable" value="no" id="tdsNo" disabled {{ old('tds_applicable', $income->tds_applicable ?? '') != 'yes' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger px-3 fw-semibold" for="tdsNo">No</label>
                            </div>
                        </div>

                        @if(($income->tds_applicable ?? '') == 'yes')
                        <div class="tds-container mt-3 p-3 bg-light-subtle rounded-3 border" id="tdsContainer">
                            <div class="row g-3">
                                <div class="col-12" id="tds_dropdown_universal">
                                    <label for="tds_percent" class="form-label fw-semibold text-dark">TDS Section & Rate</label>
                                    <select name="tds_percent" id="tds_percent" class="form-select" disabled>
                                        @foreach ($purposes_of_tds as $purpose)
                                            <option value="{{ $purpose->tds_rate . '-' . $purpose->id }}" {{ ($purpose->id == $income->tds_id) ? 'selected' : '' }}>
                                                {{ $purpose->category }} ({{ $purpose->tds_rate }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="tds_amount" class="form-label fw-semibold text-dark">Calculated TDS Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">₹</span>
                                        <input type="text" id="tds_amount" value="{{ $income->tds_amount }}" class="form-control fw-bold bg-white" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- GST Block -->
                    <div class="col-lg-6 ps-lg-4 gst-container">
                        @php
                            $gstApplicable = strtolower(trim($income->gst_applicable ?? 'no'));
                        @endphp
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label fw-bold text-dark mb-0 fs-6">GST Applicable?</label>
                            <div class="btn-group btn-group-sm toggle-radio-group" role="group">
                                <input type="radio" class="btn-check" name="gst_applicable" value="yes" id="gstYes_ca" disabled {{ ($gstApplicable === 'yes') ? 'checked' : '' }}>
                                <label class="btn btn-outline-success px-3 fw-semibold" for="gstYes_ca">Yes</label>

                                <input type="radio" class="btn-check" name="gst_applicable" value="no" id="gstNo_ca" disabled {{ ($gstApplicable !== 'yes') ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger px-3 fw-semibold" for="gstNo_ca">No</label>
                            </div>
                        </div>

                        @if($gstApplicable === 'yes')
                        <div class="gst-fields-wrapper mt-3 p-3 bg-light-subtle rounded-3 border" id="gstFieldsWrapper">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="gst_trans" class="form-label fw-semibold text-dark">GST Transaction Mode</label>
                                    <select class="form-select" name="gst_trans" id="gst_trans" disabled>
                                        <option value="intrastate" {{ ($income->gst_trans == 'intrastate') ? 'selected' : '' }}>Intra State (CGST + SGST)</option>
                                        <option value="interstate" {{ ($income->gst_trans == 'interstate') ? 'selected' : '' }}>Inter State (IGST)</option>
                                        <option value="union" {{ ($income->gst_trans == 'union') ? 'selected' : '' }}>Union Territory (UTGST)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="gst_rate" class="form-label fw-semibold text-dark">GST Rate (%)</label>
                                    <div class="input-group">
                                        <input type="number" name="gst_rate" id="gst_rate" value="{{ $income->gst_rate }}" class="form-control bg-white" disabled>
                                        <span class="input-group-text bg-white">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="gst_allocation" class="form-label fw-semibold text-dark">Tax Allocation</label>
                                    <input type="text" name="gst_allocation" id="gst_allocation" value="{{ $income->gst_allocation }}" class="form-control bg-white" readonly>
                                </div>
                                <div class="col-sm-6">
                                    <label for="gst_amt" class="form-label fw-semibold text-dark">Total GST Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">₹</span>
                                        <input type="text" name="gst_amt" id="gst_amt" value="{{ $income->gst_amt }}" class="form-control fw-bold bg-white" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 📌 SECTION 5: Additional Info & Supporting Document -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-light-subtle py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-primary d-flex align-items-center">
                    <i class="ti ti-file-description me-2 fs-4"></i> 5. Notes & Attachment
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-dark" for="projectDescription">Notes / Description</label>
                        <textarea class="form-control bg-light" name="specification" id="projectDescription" disabled rows="3">{{ $income->specification }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Supporting Attachment</label>
                        @if($income->income_doc)
                            <div>
                                <a href="{{ asset('uploads/income_docs/'.$income->income_doc) }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-1">
                                    <i class="ti ti-file-download fs-5"></i> View Attached Document
                                </a>
                            </div>
                        @else
                            <p class="text-muted mb-0">No document attached.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Submit Actions -->
        <div class="col-md-12 text-end mb-5">
            <a href="{{ url('/other-income-list') }}" class="btn btn-danger me-2">Back to List</a>
            <a href="{{ route('income.edit', base64_encode($income->id)) }}" class="btn btn-primary">Edit Income</a>
        </div>
    </form>
</div>

<!-- Payment Voucher Modal -->
<div class="modal fade" id="paymentVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="f_id">
                <input type="hidden" id="voucher_type">
                <input type="hidden" id="isViewPage" value="1">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Total Invoice Amount</label>
                        <input type="text" id="invoice_total" class="form-control" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Paid Amount</label>
                        <input type="text" id="total_paid" class="form-control" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Balance Due</label>
                        <input type="text" id="balance_due" class="form-control" readonly>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Bank</th>
                        <th id="actionHeader" width="80">Action</th>
                    </tr>
                    </thead>
                    <tbody id="voucherRows"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection