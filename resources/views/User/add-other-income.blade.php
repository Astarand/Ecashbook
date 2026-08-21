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
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/other-income-list') }}">Other Income</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Other Income</li>
                    </ul>
                    <a href="javascript:void(0);" id="start-add-other-income-tour" class="text-primary d-flex align-items-center gap-1 fw-semibold" style="font-size: 0.92rem;">
                        <i class="ti ti-help fs-5"></i> <u>How does this page work?</u>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row mb-3 align-items-center">
        <div class="col-md-8">
            <h3 class="mb-1 fw-bold text-dark"><i class="ti ti-cash-banknote text-primary me-2"></i>Add Other Income</h3>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">Record auxiliary operating revenue or non-operating income transactions.</p>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <a href="{{ url('/other-income-list') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="ti ti-arrow-left me-1"></i> Back to List
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
                        <label class="form-label fw-semibold text-dark">{{ $hasProprietorship ? 'Proprietorship Company' : 'Company Name' }} <span class="text-danger">*</span></label>
                        <select name="propId" class="form-select">
                            <option value="">{{ parentCompanyName() }}</option>
                            @foreach($proprietorships as $company)
                                <option value="{{ $company->id }}">{{ $company->comp_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Transaction Date <span class="text-danger">*</span></label>
                        <input type="date" name="dateInput" id="dateInput" value="{{ date('Y-m-d') }}" required class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Income Type <span class="text-danger">*</span></label>
                        <select id="incomeType" name="incomeType" class="form-select" required>
                            <option value="">-- Select Income Type --</option>
                            <option value="Revenue">Other Operating Income</option>
                            <option value="Other">Other Non-Operating Income</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Income Category / Sub-Category <span class="text-danger">*</span></label>
                        <select id="categoryIncome" name="categoryIncome" required class="form-select">
                            <option value="">-- Select Category --</option>
                        </select>
                    </div>

                    <div class="col-12" id="otherIncomeCategory" style="display: none;">
                        <label for="otherInput" class="form-label fw-semibold text-dark">Specify Other Category <span class="text-danger">*</span></label>
                        <input type="text" name="other_income" class="form-control" id="otherInput" placeholder="Enter other category title">
                    </div>
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
                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="e.g. Client Name, Tenant, Bank, Vendor">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Invoice / Reference Number</label>
                        <input type="text" name="invoice_no" id="invoice_no" class="form-control" placeholder="e.g. INV/2026/001 or REF-9842">
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
                        <label class="form-label fw-semibold text-dark">Total Income Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">₹</span>
                            <input type="number" step="0.01" name="amount" id="amount" required class="form-control fw-bold fs-6 text-dark" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Receipt Status <span class="text-danger">*</span></label>
                        <select name="pay_status" id="pay_status" required class="form-select">
                            <option value="">-- Select Status --</option>
                            <option value="Full">Full Payment</option>
                            <option value="Advance">Advance Payment</option>
                            <option value="Due">Due / Receivable</option>
                        </select>
                    </div>

                    <!-- Payment Mode -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Payment Mode <span class="text-danger">*</span></label>
                        <select name="pay_mode" id="pay_mode" required class="form-select">
                            <option value="">-- Select Mode --</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank Transfer</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>

                    <!-- Select Bank -->
                    <div class="col-md-4" id="bank_div">
                        <label class="form-label fw-semibold text-dark">Deposited Bank Account</label>
                        <select name="bank_id" id="bank_id" class="form-select">
                            <option value="">-- Select Bank Account --</option>
                            @foreach($bankDetails as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Advance / Receivable fields -->
                    <div class="col-md-4" id="advance_amt_div">
                        <label class="form-label fw-semibold text-dark">Advance Received Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="advance_amt" id="advance_amt" class="form-control" placeholder="0.00">
                        </div>
                    </div>

                    <div class="col-md-4" id="receivable_amt_div">
                        <label class="form-label fw-semibold text-dark">Balance Receivable</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="receivable_amt" id="receivable_amt" class="form-control" placeholder="0.00">
                        </div>
                    </div>

                    <div class="col-md-4" id="adjust_amt_div">
                        <label class="form-label fw-semibold text-dark">Adjust Amount (Received)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="adjust_amt" id="adjust_amt" class="form-control" placeholder="0.00">
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
                                <input type="radio" class="btn-check" name="tds_applicable" value="yes" id="tdsYes" autocomplete="off">
                                <label class="btn btn-outline-success px-3 fw-semibold" for="tdsYes">Yes</label>

                                <input type="radio" class="btn-check" name="tds_applicable" value="no" id="tdsNo" autocomplete="off" checked>
                                <label class="btn btn-outline-danger px-3 fw-semibold" for="tdsNo">No</label>
                            </div>
                        </div>

                        <div class="tds-container mt-3 p-3 bg-light-subtle rounded-3 border" id="tdsContainer" style="display: none;">
                            <div class="row g-3">
                                <div class="col-12" id="tds_dropdown_universal">
                                    <label for="tds_percent" class="form-label fw-semibold text-dark">TDS Section & Rate</label>
                                    <select name="tds_percent" id="tds_percent" class="form-select">
                                        @foreach ($purposes_of_tds as $purpose)
                                            <option value="{{ $purpose->tds_rate . '-' . $purpose->id }}">
                                                {{ $purpose->category }} ({{ $purpose->tds_rate }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="tds_amount" class="form-label fw-semibold text-dark">Calculated TDS Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">₹</span>
                                        <input type="text" id="tds_amount" class="form-control fw-bold bg-white" readonly placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GST Block -->
                    <div class="col-lg-6 ps-lg-4 gst-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label fw-bold text-dark mb-0 fs-6">GST Applicable?</label>
                            <div class="btn-group btn-group-sm toggle-radio-group" role="group">
                                <input type="radio" class="btn-check" name="gst_applicable" value="yes" id="gstYes_ca" autocomplete="off">
                                <label class="btn btn-outline-success px-3 fw-semibold" for="gstYes_ca">Yes</label>

                                <input type="radio" class="btn-check" name="gst_applicable" value="no" id="gstNo_ca" autocomplete="off" checked>
                                <label class="btn btn-outline-danger px-3 fw-semibold" for="gstNo_ca">No</label>
                            </div>
                        </div>

                        <div class="gst-fields-wrapper mt-3 p-3 bg-light-subtle rounded-3 border" id="gstFieldsWrapper" style="display: none;">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="gst_trans" class="form-label fw-semibold text-dark">GST Transaction Mode</label>
                                    <select class="form-select" name="gst_trans" id="gst_trans">
                                        <option value="">-- Select Mode --</option>
                                        <option value="intrastate">Intra State (CGST + SGST)</option>
                                        <option value="interstate">Inter State (IGST)</option>
                                        <option value="union">Union Territory (UTGST)</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="gst_rate" class="form-label fw-semibold text-dark">GST Rate (%)</label>
                                    <div class="input-group">
                                        <input type="number" name="gst_rate" id="gst_rate" class="form-control" min="0" step="0.01" placeholder="e.g. 18">
                                        <span class="input-group-text bg-white">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="gst_allocation" class="form-label fw-semibold text-dark">Tax Allocation</label>
                                    <input type="text" name="gst_allocation" id="gst_allocation" class="form-control bg-white" readonly placeholder="e.g. CGST | SGST">
                                </div>
                                <div class="col-sm-6">
                                    <label for="gst_amt" class="form-label fw-semibold text-dark">Total GST Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">₹</span>
                                        <input type="text" name="gst_amt" id="gst_amt" class="form-control fw-bold bg-white" readonly placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <textarea class="form-control" name="specification" id="projectDescription" placeholder="Add detailed remarks or terms regarding this income receipt..." rows="3"></textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Supporting Attachment</label>
                        <input type="file" name="income_doc" id="income_doc" class="form-control" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx">
                        <small class="text-muted d-block mt-1">Upload proof / voucher (PDF, JPG, PNG).</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Submit Actions -->
        <div class="col-md-12 text-end mb-5">
            <a href="{{ url('/other-income-list') }}" class="btn btn-danger me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Other Income</button>
        </div>
    </form>
</div>
@endsection

@section('page-script')
<script>
    function startAddOtherIncomeTour() {
        if (typeof introJs !== 'function') return;

        introJs().setOptions({
            steps: [
                {
                    title: 'Add Other Income',
                    intro: '<div class="text-center"><div class="welcome-tour-icon-container mb-4 d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: linear-gradient(135deg, rgba(0, 140, 173, 0.15), rgba(99, 102, 241, 0.15)); border-radius: 50%; color: #008CAD;"><i class="ti ti-cash-banknote" style="font-size: 45px;"></i></div><p class="mb-0 text-secondary" style="font-size: 1.05rem;">Fill this form to register new auxiliary operating revenue or non-operating income.</p></div>'
                },
                {
                    element: 'select[name="propId"]',
                    title: 'Proprietorship Entity',
                    intro: 'Select the specific proprietorship company entity receiving this income.'
                },
                {
                    element: '#dateInput',
                    title: 'Transaction Date',
                    intro: 'Set the official date when the income was registered.'
                },
                {
                    element: '#incomeType',
                    title: 'Income Classification',
                    intro: 'Choose Operating Revenue vs Non-operating Other Income categories.'
                },
                {
                    element: '#categoryIncome',
                    title: 'Category Subclass',
                    intro: 'Specify the subclass category (e.g. Service Income, Rental, Dividends).'
                },
                {
                    element: '#customer_name',
                    title: 'Party / Source Name',
                    intro: 'Enter the customer or entity paying this income amount.'
                },
                {
                    element: '#amount',
                    title: 'Income Amount',
                    intro: 'Specify the total revenue amount received or receivable.'
                },
                {
                    element: '#pay_status',
                    title: 'Receipt Status',
                    intro: 'Set receipt status (Full, Advance, or Due).'
                },
                {
                    element: '.currAsset',
                    title: 'TDS & GST Taxes',
                    intro: 'Toggle applicability flags to configure TDS calculations and add standard GST rate transaction details.'
                },
                {
                    element: 'button[type="submit"]',
                    title: 'Save Transaction',
                    intro: 'Click here to save the income transaction to the registry ledger.'
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
        $('#start-add-other-income-tour').on('click', function(e) {
            e.preventDefault();
            startAddOtherIncomeTour();
        });
    });

	// Income Type and Sub category
	$(document).ready(function () {

		const revenueOptions = [
			"Freight / Delivery Charges Recovery",
			"Packing & Handling Charges Recovery",
			"Installation Charges",
			"Training Charges",
			"AMC / Maintenance Charges",
			"Commission Income",
			"Service Recovery Charges",
			"Documentation Charges",
			"Processing Charges",
			"Onboarding Charges",
			"Platform / API Usage Charges",
			"SMS / Communication Charges Recovery",
			"Data Migration Charges",
			"Scrap Sales",			
			"Miscellaneous Operating Income"
		];

		const otherOptions = [
			"Interest Income",
			"Rental Income",
			"Dividend Income",
			"Profit on Sale of Fixed Assets",
			"Profit on Sale of Investments",
			"Foreign Exchange Gain",
			"Insurance Claim Received",
			"Bad Debts Recovered",
			"Government Grant / Subsidy Income",
			"Miscellaneous Non-Operating Income"
		];

		// Populate subcategory
		$('#incomeType').on('change', function () {
			let type = $(this).val();
			let categoryDropdown = $('#categoryIncome');

			// Always reset first
			categoryDropdown.empty().append('<option value="">-- Select Category --</option>');

			if (!type) {
				$('#otherIncomeCategory').hide();
				return;
			}

			let options = (type === 'Revenue') ? revenueOptions : ((type === 'Other') ? otherOptions : []);

			options.forEach(function (item) {
				categoryDropdown.append(`<option value="${item}">${item}</option>`);
			});

			$('#otherIncomeCategory').hide();
		});

		// Show "Other" field
		$('#categoryIncome').on('change', function () {
			let selected = $(this).val();
			if (
				selected === "Miscellaneous Non-Operating Income" ||
				selected === "Miscellaneous Operating Income"
			) {
				$('#otherIncomeCategory').fadeIn(200);
			} else {
				$('#otherIncomeCategory').hide();
				$('#otherInput').val('');
			}
		});

        // Bank field visibility
        function toggleBankField() {
            let mode = $('#pay_mode').val();
            if (mode === 'Bank' || mode === 'UPI') {
                $('#bank_div').fadeIn(200);
            } else {
                $('#bank_div').fadeOut(200);
            }
        }
        $('#pay_mode').on('change', toggleBankField);
        toggleBankField();
	});
	
	// Decimal validation
	function allowOnlyDecimal(el) {
		let value = el.value.replace(/[^0-9.]/g, '');
		let parts = value.split('.');
		if (parts.length > 2) {
			value = parts[0] + '.' + parts.slice(1).join('');
		}
		el.value = value;
	}
	
	$('#amount, #advance_amt, #receivable_amt, #adjust_amt').on('input', function () {
		allowOnlyDecimal(this);
	});
	
	function togglePayModeRequirement() 
	{
		let status = $('#pay_status').val();

		if (status === 'Due') {
			// Due = no payment made yet
			$('#pay_mode').val('').prop('required', false);
			$('#bank_div').hide();
			$('#bank_id').val('');
		} else if (status === 'Full' || status === 'Advance') {
			$('#pay_mode').prop('required', true);
			toggleBankField();
		} else {
			// No status selected
			$('#pay_mode').val('').prop('required', false);
			$('#bank_div').hide();
			$('#bank_id').val('');
		}
	}

	$(document).ready(function () {

		function resetFields() {
			$('#advance_amt, #receivable_amt, #adjust_amt')
				.val('')
				.prop({ readonly: true, required: false });

			$('#advance_amt_div, #receivable_amt_div, #adjust_amt_div').hide();
		}

		function calculateAmounts() {
			let amount     = parseFloat($('#amount').val()) || 0;
			let adjustAmt  = parseFloat($('#adjust_amt').val()) || 0;
			let advanceAmt = parseFloat($('#advance_amt').val()) || 0;
			let status     = $('#pay_status').val();

			// ================= FULL =================
			if (status === 'Full') {
				$('#adjust_amt_div').show();
				$('#advance_amt_div').hide();
				$('#receivable_amt_div').hide();

				$('#adjust_amt')
					.val(amount)
					.prop({ readonly: true, required: true });

				$('#advance_amt').val(0).prop({ required: false });
				$('#receivable_amt').val(0).prop({ required: false });
			}

			// ================= ADVANCE =================
			else if (status === 'Advance') {
				$('#adjust_amt_div').show();
				$('#advance_amt_div').show();
				$('#receivable_amt_div').show();

				$('#adjust_amt').prop({ readonly: false, required: true });
				$('#advance_amt').prop({ readonly: false, required: true });
				$('#receivable_amt').prop({ readonly: true, required: true });

				let usedAmount = adjustAmt || advanceAmt;

				if (usedAmount > amount) {
					usedAmount = amount;
					$('#adjust_amt').val(amount);
					$('#advance_amt').val(amount);
					alert('Amount cannot exceed total income amount');
				}

				let balance = amount - usedAmount;
				$('#receivable_amt').val(balance.toFixed(2));
			}

			// ================= DEFAULT / DUE =================
			else {
				resetFields();
			}
		}

		$('#pay_status').on('change', function () {
			resetFields();
			calculateAmounts();
			togglePayModeRequirement();
		});

		$('#amount').on('input', calculateAmounts);

		$('#adjust_amt').on('input', function () {
			$('#advance_amt').val($(this).val());
			calculateAmounts();
		});

		$('#advance_amt').on('input', function () {
			$('#adjust_amt').val($(this).val());
			calculateAmounts();
		});

		resetFields();
		calculateAmounts();
	});
	
	// TDS Toggle & Calculation
	$(document).ready(function () {
		function toggleTDS() {
			let val = $('input[name="tds_applicable"]:checked').val();

			if (val === 'yes') {
				$('#tdsContainer').slideDown(200);
				calculateTDS(); 
			} else {
				$('#tdsContainer').slideUp(200);
				$('#tds_amount').val(0); 
			}
		}

		function calculateTDS() {
			let amount = parseFloat($('#amount').val()) || 0;

			$.ajax({
				url: '/calculate-tds-income',
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					amount: amount
				},
				success: function (res) {
					if (res) {
						$('#tds_amount').val(res.tds_amount);
					}
				}
			});
		}

		$('input[name="tds_applicable"]').on('change', toggleTDS);

		$('#amount').on('input change', function () {
			if ($('#tdsYes').is(':checked')) {
				calculateTDS();
			}
		});

		toggleTDS();
	});
	
	// GST Toggle & Calculation
	$(document).ready(function () {
		function toggleGST() {
			let isYes = $('#gstYes_ca').is(':checked');
			if (isYes) {
				$('#gstFieldsWrapper').slideDown(200);
			} else {
				$('#gstFieldsWrapper').slideUp(200);
			}
		}

		$('input[name="gst_applicable"]').on('change', toggleGST);
		toggleGST();

		function calculateGST() {
			let invoiceValue = parseFloat($('#amount').val()) || 0;
			let gstRate  = parseFloat($('#gst_rate').val()) || 0;
			let gstTrans = $('#gst_trans').val();

			let gstAmount = (invoiceValue * gstRate) / 100;
			let cgst = 0, sgst = 0, igst = 0;

			if (gstTrans === 'intrastate' || gstTrans === 'union') {
				cgst = gstAmount / 2;
				sgst = gstAmount / 2;
			} else if (gstTrans === 'interstate') {
				igst = gstAmount;
			}

			$('#gst_amt').val(gstAmount.toFixed(2));

			if (igst > 0) {
				$('#gst_allocation').val(`IGST: ₹${igst.toFixed(2)}`);
			} else if (cgst > 0 || sgst > 0) {
				$('#gst_allocation').val(`CGST: ₹${cgst.toFixed(2)} | SGST: ₹${sgst.toFixed(2)}`);
			} else {
                $('#gst_allocation').val('');
            }
		}

		$('#amount, #gst_rate, #gst_trans').on('change keyup', calculateGST);
	});

	// Submit Form via AJAX
    $(document).on("submit", "#incomeForm", function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        $("#loader").show();

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: '/storeNewIncomeData',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $("#loader").hide();
                if (response.status === "success") {
                    showToast(response.message, "success");
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    showToast(response.message || "Unable to save income record", "error");
                }
            },
            error: function(xhr) {
                $("#loader").hide();
                showToast("Something went wrong! Please try again.", "error");
            }
        });
    });
</script>
@endsection